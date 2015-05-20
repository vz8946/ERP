<?php
$payments['yeepay'] = 'YeePay易宝';
class Custom_Model_Payment_Yeepay extends Custom_Model_Payment_Abstract
{
  
    function getFields($config=null){
        $html .= '<table><tr><th width="150">';
        $html .= '商户编号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][yp_account]" value="'.$config['yp_account'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '商户密钥';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][yp_key]" value="'.$config['yp_key'].'">';
        $html .= '</td></tr></table>';

        return $html;
    }
    function getCode($arg=null) {
        /*
         * $arg['bank'] 是否显示银行列表(目前只针对支付宝)
         * $arg['pay_hidden'] 是否让顾客更改支付金额
         * $arg['target'] 是否新开窗口支付
         */
        $time = $this -> time;
        $order = $this -> order;
        $payment = $this -> payment;

        if ($arg['pay_hidden'] == false) {
            $type = '';
        } else {
            $type = 'none';
        }
        if ($arg['target'] == true) {
            $target = 'target="_blank"';
        } else {
            $target = '';
        }
		$payAmount = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
        $def_url  = "<form id='form_yeepay' action='https://www.yeepay.com/app-merchant-proxy/node' method='post' {$target}>\n";
        $def_url .= "<div style='display:none;' id='htm_yeepay'></div>";
        $def_url .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="6" id="pay_yeepay" readonly value="'.$payAmount.'"></div> ';
        $def_url .= "<input type='button' onclick='authYeepay();' value='YeePay易宝支付'   class='buttons4'>";
        $def_url .= "</form>\n";
        $def_url .= '
<script>
function authYeepay(){
    $.ajax({
        url: "/payment/auth/pay_type/yeepay/",
        type: "post",
        data: {amount:$("#pay_yeepay").val(),batch_sn:'.$order['batch_sn'].'},
        beforeSend: function(){},
        success: function(data){$("#htm_yeepay").html(data);$("#form_yeepay").submit();}
        });
}
</script>';
        return $def_url;
    }

    function respond(){
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'yeepay')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！';
            return $this -> returnRes;
        }

        $merchant_id    = $payment['yp_account'];       // 获取商户编号
        $merchant_key   = $payment['yp_key'];           // 获取秘钥

        $message_type   = trim($_REQUEST['r0_Cmd']);
        $succeed        = trim($_REQUEST['r1_Code']);   // 获取交易结果,1成功,-1失败
        $trxId          = trim($_REQUEST['r2_TrxId']);
        $amount         = trim($_REQUEST['r3_Amt']);    // 获取订单金额
        $cur            = trim($_REQUEST['r4_Cur']);    // 获取订单货币单位
        $product_id     = trim($_REQUEST['r5_Pid']);    // 获取产品ID
        $orderid        = trim($_REQUEST['r6_Order']);  // 获取订单ID
        $userId         = trim($_REQUEST['r7_Uid']);    // 获取产品ID
        $merchant_param = trim($_REQUEST['r8_MP']);     // 获取商户私有参数
        $bType          = trim($_REQUEST['r9_BType']);  // 获取订单ID

        $mac            = trim($_REQUEST['hmac']);      // 获取安全加密串

        ///生成加密串,注意顺序
        $ScrtStr  = $merchant_id . $message_type . $succeed . $trxId . $amount . $cur . $product_id .
                      $orderid . $userId . $merchant_param . $bType;
        $mymac    = $this -> hmac($ScrtStr, $merchant_key);

        if (strtoupper($mac) == strtoupper($mymac)) {
            if ($succeed == '1') {
                ///支付成功
                $apiReturnRes = $this -> _api -> update($orderid, $amount, 'yeepay');
                if ($apiReturnRes['result']) {
                    $this -> returnRes['stats'] = true;
                    $this -> returnRes['thisPaid'] = $amount;
                    $this -> returnRes['difference'] = $apiReturnRes['remainder'];//差额
                } else {
                    $this -> returnRes['stats'] = false;
                    $this -> returnRes['msg'] = $apiReturnRes['msg'] != '' ? $apiReturnRes['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
                }
            } else {
                $this -> returnRes['stats'] = false;
                $this -> returnRes['msg'] = '支付失败';
            }
        } else {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
        }

        return $this -> returnRes;
    }

    function auth() {
        $time = time();
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'yeepay')));
        $payment = unserialize($payment['config']);

        $data_merchant_id = $payment['yp_account'];
        $data_order_id    = $order['batch_sn'] . '-' . $time;
        $data_amount      = $_POST['amount'];
        $message_type     = 'Buy';
        $data_cur         = 'CNY';
        $product_id       = '';
        $product_cat      = '';
        $product_desc     = '';
        $address_flag     = '0';

        $data_return_url  = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/yeepay";

        $data_pay_key     = $payment['yp_key'];
        $data_pay_account = $payment['yp_account'];
        $mct_properties   = '';
        $def_url = $message_type . $data_merchant_id . $data_order_id . $data_amount . $data_cur . $product_id . $product_cat
                             . $product_desc . $data_return_url . $address_flag . $mct_properties;
        $MD5KEY = $this -> hmac($def_url, $data_pay_key);

        $def_url = "<input type='hidden' name='p0_Cmd' value='".$message_type."'>\n";
        $def_url .= "<input type='hidden' name='p1_MerId' value='".$data_merchant_id."'>\n";
        $def_url .= "<input type='hidden' name='p2_Order' value='".$data_order_id."'>\n";
        $def_url .= "<input type='hidden' name='p3_Amt' value='".$data_amount."'>\n";
        $def_url .= "<input type='hidden' name='p4_Cur' value='".$data_cur."'>\n";
        $def_url .= "<input type='hidden' name='p5_Pid' value='".$product_id."'>\n";
        $def_url .= "<input type='hidden' name='p6_Pcat' value='".$product_cat."'>\n";
        $def_url .= "<input type='hidden' name='p7_Pdesc' value='".$product_desc."'>\n";
        $def_url .= "<input type='hidden' name='p8_Url' value='".$data_return_url."'>\n";
        $def_url .= "<input type='hidden' name='p9_SAF' value='".$address_flag."'>\n";
        $def_url .= "<input type='hidden' name='pa_MP' value='".$mct_properties."'>\n";
        $def_url .= "<input type='hidden' name='hmac' value='".$MD5KEY."'>\n";

        echo $def_url;
    }
    function hmac($data, $key)
    {
        // RFC 2104 HMAC implementation for php.
        // Creates an md5 HMAC.
        // Eliminates the need to install mhash to compute a HMAC
        // Hacked by Lance Rushing(NOTE: Hacked means written)

        //$key  = ecs_iconv('GB2312', 'UTF8', $key);
        //$data = ecs_iconv('GB2312', 'UTF8', $data);

        $b = 64; // byte length for md5
        if (strlen($key) > $b)
        {
            $key = pack('H*', md5($key));
        }

        $key    = str_pad($key, $b, chr(0x00));
        $ipad   = str_pad('', $b, chr(0x36));
        $opad   = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack('H*', md5($k_ipad . $data)));
    }


}