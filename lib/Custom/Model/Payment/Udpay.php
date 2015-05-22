<?php
$payments['udpay'] = '网汇通';
class Custom_Model_Payment_Udpay extends Custom_Model_Payment_Abstract
{
  
    function getFields($config=null){
        $html .= '<table><tr><th width="250">';
        $html .= '商户帐号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][udpay_account]" value="'.$config['udpay_account'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= 'merchantPrivateModulus的值';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][udpay_merchantPrivateModulus]" value="'.$config['udpay_merchantPrivateModulus'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= 'merchantPrivateExponent的值';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][udpay_merchantPrivateExponent]" value="'.$config['udpay_merchantPrivateExponent'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= 'wht.publicModulus的值';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][udpay_whtpublicModulus]" value="'.$config['udpay_whtpublicModulus'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= 'wht.publicExponent的值';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][udpay_whtpublicExponent]" value="'.$config['udpay_whtpublicExponent'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '您在网汇通注册的公司名称(英文)';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][udpay_orderInfo]" value="'.$config['udpay_orderInfo'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '错误校验文件地址';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][udpay_errorfile]" value="'.$config['udpay_errorfile'].'">';
        $html .= '</td></tr></table>';


        return $html;
    }
    function getCode($arg=null) {
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
        $def_url  = '<form id="form_udpay" style="text-align:center;" action="https://www.udpay.com.cn/gateway/transForward.jsp" method="post" name=sendOrder '.$target.'>';
        $def_url .= "<div style='display:none;' id='htm_udpay'></div>";
        $def_url .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="6" id="pay_udpay" readonly value="'.$payAmount.'"></div> ';

        $def_url .= "<input type='button' onclick='authUdpay();' value='网汇通支付'  class='buttons4' >";
        $def_url .= "</form>";
        $def_url .= '
<script>
function authUdpay(){
    $.ajax({
        url: "/payment/auth/pay_type/udpay/",
        type: "post",
        data: {amount:$("#pay_udpay").val(),batch_sn:'.$order['batch_sn'].'},
        beforeSend: function(){},
        success: function(data){$("#htm_udpay").html(data);$("#form_udpay").submit();}
        });
}
</script>';

        return $def_url;
    }

    function respond(){
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'udpay')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！';
            return $this -> returnRes;
        }

        $merchant_id = $payment['udpay_account']; // 获取商户编号
        $udpay_whtpublicModulus  = $payment['udpay_whtpublicModulus'];
        $udpay_whtpublicExponent = $payment['udpay_whtpublicExponent'];

        /* read the post from udpay system and add 'cmd' */
        $req = 'cmd=_notify-validate';
        foreach ($_GET AS $key => $value) {
            $value = urlencode(stripslashes($value));
            $req  .= "&$key=$value";
        }

        /* post back to udpay system to validate */
        //$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        //$header .= "Content-Length: " . strlen($req) ."\r\n\r\n";

        /* 获取网汇通get过来的需要签名的数据 */
        $sign = $_GET['sign'];
        $msg = "txCode=$_GET[txCode]&merchantId=$_GET[merchantId]&transDate=$_GET[transDate]&transFlow=$_GET[transFlow]&orderId=$_GET[orderId]&curCode=$_GET[curCode]&amount=$_GET[amount]&orderInfo=$_GET[orderInfo]&comment=$_GET[comment]&whtFlow=$_GET[whtFlow]&success=$_GET[success]&errorType=$_GET[errorType]";

        /* 公钥数据 */
        $publicModulus  = $udpay_whtpublicModulus;
        $publicExponent = $udpay_whtpublicExponent;
        $verifySigature = $this-> verifySigature($msg, $sign, $publicExponent, $publicModulus);
        if ($verifySigature) {
            $total_fee = intval(trim($_GET['amount'])) / 100;
            $apiReturnRes = $this -> _api -> update($_GET['transFlow'], $total_fee, 'udpay');
            if ($apiReturnRes['result']) {
                $this -> returnRes['stats'] = true;
                $this -> returnRes['thisPaid'] = $total_fee;
                $this -> returnRes['difference'] = $apiReturnRes['remainder'];//差额
            } else {
                $this -> returnRes['stats'] = false;
                $this -> returnRes['msg'] = $apiReturnRes['msg'] != '' ? $apiReturnRes['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
            }
        } else {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
        }

        return $this -> returnRes;
    }
    function sync()
    {
        $this->writeLog('udpay');
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'udpay')));
        $payment = unserialize($payment['config']);
        $merchant_id = $payment['udpay_account']; // 获取商户编号

        $udpay_whtpublicModulus  = $payment['udpay_whtpublicModulus'];
        $udpay_whtpublicExponent = $payment['udpay_whtpublicExponent'];

        /* read the post from udpay system and add 'cmd' */
        $req = 'cmd=_notify-validate';
        foreach ($_REQUEST AS $key => $value)
        {
            $value = urlencode(stripslashes($value));
            $req  .= "&$key=$value";
        }

        /* post back to udpay system to validate */
        //$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        //$header .= "Content-Length: " . strlen($req) ."\r\n\r\n";

        /* 获取网汇通get过来的需要签名的数据 */

        $sign = $_REQUEST['sign'];
        $msg = "txCode=$_REQUEST[txCode]&merchantId=$_REQUEST[merchantId]&transDate=$_REQUEST[transDate]&transFlow=$_REQUEST[transFlow]&orderId=$_REQUEST[orderId]&curCode=$_REQUEST[curCode]&amount=$_REQUEST[amount]&orderInfo=$_REQUEST[orderInfo]&comment=$_REQUEST[comment]&whtFlow=$_REQUEST[whtFlow]&success=$_REQUEST[success]&errorType=$_REQUEST[errorType]";

        /* 公钥数据 */
        $publicModulus  = $udpay_whtpublicModulus;
        $publicExponent = $udpay_whtpublicExponent;

        $verifySigature = $this -> verifySigature($msg, $sign, $publicExponent, $publicModulus);
        if ($verifySigature)
        {
            $total_fee = intval(trim($_REQUEST['amount'])) / 100;
            $this -> _api -> update($_REQUEST['transFlow'], $total_fee, 'udpay');
            echo 'received';
        }
    }
    function auth() {
        $time = time();
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'udpay')));
        $payment = unserialize($payment['config']);

        $data_order_id    = $order['batch_sn'] . '-' . $time;
        $data_amount      = $_POST['amount']*100;
        $data_pay_account = $payment['udpay_account'];
        $udpay_merchantPrivateModulus  = $payment['udpay_merchantPrivateModulus'];
        $udpay_merchantPrivateExponent = $payment['udpay_merchantPrivateExponent'];
        $udpay_orderInfo  = $payment['udpay_orderInfo'];

        $data_notify_url  = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/udpay";
		$trans_date = date('Ymd');
		$interface_Type = 5;
        $msg="txCode=TP001&merchantId={$data_pay_account}&transDate={$trans_date}&transFlow={$data_order_id}&orderId={$order['batch_sn']}&curCode=156&amount={$data_amount}&orderInfo={$udpay_orderInfo}&comment=comment&merURL={$data_notify_url}&interfaceType={$interface_Type}"; //交易数据签名信息原始串
		$privateModulus  = $udpay_merchantPrivateModulus;  // 交易数据签名信息商户私钥
        $privateExponent = $udpay_merchantPrivateExponent; // 交易数据签名信息商户私钥
        $RsaDecrypt      = $this->generateSigature($msg, $privateExponent, $privateModulus);
        $def_url         = 
            "<input type='hidden' name='txCode' value='TP001'>" .                  // 交易代码 (固定值不可修改)
            "<input type='hidden' name='merchantId' value='$data_pay_account'>" .  // 网汇通商户号
            "<input type='hidden' name='transDate' value='$trans_date'>" .         // 交易日期
            "<input type='hidden' name='transFlow' value='$data_order_id'>" .      // 交易流水号
            "<input type='hidden' name='orderId' value='$order[order_sn]'>" .      // 订单号
            "<input type='hidden' name='curCode' value='156'>" .                   // 币种（固定值不可修改）
            "<input type='hidden' name='amount' value='$data_amount'>" .           // 订单金额
            "<input type='hidden' name='orderInfo' value=$udpay_orderInfo>" .      // 订单信息（测试商户请在此输入贵公司名称信息）
            "<input type='hidden' name='comment' value='comment'>" .               // 附加信息
            "<input type='hidden' name='merURL' value='$data_notify_url'>" .       // 接收网汇通系统的支付结果信息的URL
            "<input type='hidden' name='interfaceType' value='$interface_Type'>" . // 接口模式
            "<input type='hidden' name='sign' value='$RsaDecrypt'>";               // 交易数据签名信息

        echo $def_url;
    }


    function generateSigature($message, $exponent, $modulus)
    {
        $md5Message = md5($message);
        $fillStr    = '01ff003020300c06082a864886f70d020505000410';
        $md5Message = $fillStr.$md5Message;
        $intMessage = $this->bin2int(@$this->hex2bin($md5Message));
        $intE       = $this->bin2int(@$this->hex2bin($exponent));
        $intM       = $this->bin2int(@$this->hex2bin($modulus));
        $intResult  = $this->powmod($intMessage, $intE, $intM);
        $hexResult  = bin2hex($this->int2bin($intResult));

        return $hexResult;
    }

    function verifySigature($message, $sign, $exponent, $modulus)
    {
        $intSign     = @$this->bin2int($this->hex2bin($sign));
        $intExponent = @$this->bin2int($this->hex2bin($exponent));
        $intModulus  = @$this->bin2int($this->hex2bin($modulus));
        $intResult   = $this->powmod($intSign, $intExponent, $intModulus);
        $hexResult   = bin2hex($this->int2bin($intResult));
        $md5Message  = md5($message);
        if ($md5Message == substr($hexResult, -32))
        {
            return '1';
        }
        else
        {
            return '0';
        }
    }

    function hex2bin($hexdata)
    {
        for ($i = 0, $count = strlen($hexdata); $i < $count; $i += 2)
        {
            $bindata = chr(hexdec(substr($hexdata, $i, 2))) . $bindata;
        }

        return $bindata;
    }

    function bin2int($str)
    {
        $result = '0';
        $n = strlen($str);

        do
        {
            $result = bcadd(bcmul($result, '256'), ord($str{--$n}));
        } while ($n > 0);

        return $result;
    }

    function int2bin($num)
    {
        $result = '';

        do
        {
            $result= chr(bcmod($num, '256')) . $result;
            $num = bcdiv($num, '256');
        } while (bccomp($num, '0'));

        return $result;
    }

    function powmod($num, $pow, $mod)
    {
        $result = '1';

        do
        {
          if (!bccomp(bcmod($pow, '2'), '1'))
          {
              $result = bcmod(bcmul($result, $num), $mod);
          }
          $num = bcmod(bcpow($num, '2'), $mod);
          $pow = bcdiv($pow, '2');
      } while (bccomp($pow, '0'));

      return $result;
    }

}