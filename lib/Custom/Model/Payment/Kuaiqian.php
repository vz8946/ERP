<?php
$payments['kuaiqian'] = '快钱';
class Custom_Model_Payment_Kuaiqian extends Custom_Model_Payment_Abstract
{
  
    function getFields($config=null){
        $html .= '<table><tr><th width="150">';
        $html .= '商户编号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][kq_account]" value="'.$config['kq_account'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '商户密钥';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][kq_key]" value="'.$config['kq_key'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '快钱银行';
        $html .= '</th><td>';
        $html .= '<textarea name="payment[config][bank]">'.$config['bank'].'</textarea>';
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
        $def_url = '<form id="form_kuaiqian" name="kqPay" method="post" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm" '.$target.'>';
        $def_url .= "<div style='display:none;' id='htm_kuaiqian'></div>";
        $def_url .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="6" id="pay_kuaiqian" readonly  value="'.$payAmount.'"></div> ';
        $def_url .= "<input type='button' onclick='authKuaiqian();' value='快钱支付'>";
        $def_url .= "</form>";
        $def_url .= '
<script>
function authKuaiqian(){
    var bankID = $.cookie("bank_id");
    if (!bankID) {
        bankID = "";
    }
    $.ajax({
        url: "/payment/auth/pay_type/kuaiqian/",
        type: "post",
		data: {amount:$("#pay_kuaiqian").val(),batch_sn:'.$order['batch_sn'].',bank_id:bankID},
        beforeSend: function(){},
        success: function(data){$("#htm_kuaiqian").html(data);$("#form_kuaiqian").submit();$.cookie("bank_id",null);}
        });
}
</script>';
           
        $tmp = $this -> bank($arg);
        setcookie("bank_id", "");

        return $tmp.$def_url;
    }

    function bank($arg)
    {
        if ($arg['bank']) {
            setcookie("bank_id", "");
            $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'kuaiqian')));
            $payment = unserialize($payment['config']);
            $qk_bank = unserialize($payment['bank']);
            $i=0;
            if(is_array($qk_bank)){
                foreach($qk_bank as $k=>$v){
                    if ($k == 'kuaiqian_account') {
						$tmp .= '<label><input type="radio" name="temp" value="'.$k.'" onclick="$.cookie(\'bank_id\',\'\',{path: \'/\'});"/>'.$v['label'].'</label>';
                    } else {
                        $pic = $this -> imgBaseUrl . '/images/bank/' . $v['pic'];
						$tmp .= '<label><input type="radio" name="temp" value="'.$k.'" onclick="$.cookie(\'bank_id\',this.value,{path: \'/\'});" /><img src="'.$pic.'"></label>';
                    }
                    $i++;
                    if($i%4==0) $tmp .= '<br>';
                }
            }           
            if ($arg['bank_hidden']) {
                $hidden = 'style="display:none;"';
            }
            return '<tr id="kuaiqian_bank" '.$hidden.'><td width="20" valign="top"></td><td colspan="2" valign="top">'.$tmp.'</td></tr>';
        }
    }
    function respond(){
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'kuaiqian')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '系统执行错误，请联系客服人员手工处理此单！';
            return $this -> returnRes;
        }
        
        $get_merchant_acctid= $payment['kq_account'].'01';
		$merchantAcctId=trim($_REQUEST['merchantAcctId']);
		$key=trim($payment['kq_key']);
		$version=trim($_REQUEST['version']);
		$language=trim($_REQUEST['language']);
		$signType=trim($_REQUEST['signType']);
		$payType=trim($_REQUEST['payType']);
		$bankId=trim($_REQUEST['bankId']);
		$orderId=trim($_REQUEST['orderId']);
		$orderTime=trim($_REQUEST['orderTime']);
		$orderAmount=trim($_REQUEST['orderAmount']);
		$dealId=trim($_REQUEST['dealId']);
		$bankDealId=trim($_REQUEST['bankDealId']);
		$dealTime=trim($_REQUEST['dealTime']);
		$payAmount=trim($_REQUEST['payAmount']);
		$fee=trim($_REQUEST['fee']);
		$ext1=trim($_REQUEST['ext1']);
		$ext2=trim($_REQUEST['ext2']);
		$payResult=trim($_REQUEST['payResult']);
		$errCode=trim($_REQUEST['errCode']);
		$signMsg=trim($_REQUEST['signMsg']);

		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"merchantAcctId",$merchantAcctId);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"version",$version);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"language",$language);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"signType",$signType);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"payType",$payType);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"bankId",$bankId);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"orderId",$orderId);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"orderTime",$orderTime);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"orderAmount",$orderAmount);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"dealId",$dealId);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"bankDealId",$bankDealId);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"dealTime",$dealTime);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"payAmount",$payAmount);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"fee",$fee);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"ext1",$ext1);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"ext2",$ext2);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"payResult",$payResult);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"errCode",$errCode);
		$merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"key",$key);
		$merchantSignMsg= md5($merchantSignMsgVal);

        //首先对获得的商户号进行比对
        if ($get_merchant_acctid != $merchantAcctId) {
            //商户号错误
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
            return $this -> returnRes;
        }

        if (strtoupper($signMsg) == strtoupper($merchantSignMsg)) {
            if ($payResult == '10' || $payResult == '00') {
                $orderAmount = intval($orderAmount) / 100;
                $apiReturnRes = $this -> _api -> update($orderId, $orderAmount, 'kuaiqian');
                if ($apiReturnRes['result']) {
                    $this -> returnRes['stats'] = true;
                    $this -> returnRes['thisPaid'] = $orderAmount;
                    $this -> returnRes['difference'] = $apiReturnRes['remainder'];//差额
                } else {
                    $this -> returnRes['stats'] = false;
                    $this -> returnRes['msg'] = $apiReturnRes['msg'] != '' ? $apiReturnRes['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
                }
            } else {
                //'支付结果失败';
                $this -> returnRes['stats'] = false;
                $this -> returnRes['msg'] = '支付失败';
            }
        } else {
            //'密钥校对错误';
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
        }
        return $this -> returnRes;
    }
    function sync()
    {
        $this->writeLog('kuaiqian');
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'kuaiqian')));
        $payment = unserialize($payment['config']);
        $get_merchant_acctid= $payment['kq_account'].'01';

        $merchantAcctId=trim($_REQUEST['merchantAcctId']);
        $key=trim($payment['kq_key']);
        $version=trim($_REQUEST['version']);
        $language=trim($_REQUEST['language']);
        $signType=trim($_REQUEST['signType']);
        $payType=trim($_REQUEST['payType']);
        $bankId=trim($_REQUEST['bankId']);
        $orderId=trim($_REQUEST['orderId']);
        $orderTime=trim($_REQUEST['orderTime']);
        $orderAmount=trim($_REQUEST['orderAmount']);
        $dealId=trim($_REQUEST['dealId']);
        $bankDealId=trim($_REQUEST['bankDealId']);
        $dealTime=trim($_REQUEST['dealTime']);
        $payAmount=trim($_REQUEST['payAmount']);
        $fee=trim($_REQUEST['fee']);
        $ext1=trim($_REQUEST['ext1']);
        $ext2=trim($_REQUEST['ext2']);
        $payResult=trim($_REQUEST['payResult']);
        $errCode=trim($_REQUEST['errCode']);
        $signMsg=trim($_REQUEST['signMsg']);

        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"merchantAcctId",$merchantAcctId);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"version",$version);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"language",$language);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"signType",$signType);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"payType",$payType);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"bankId",$bankId);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"orderId",$orderId);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"orderTime",$orderTime);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"orderAmount",$orderAmount);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"dealId",$dealId);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"bankDealId",$bankDealId);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"dealTime",$dealTime);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"payAmount",$payAmount);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"fee",$fee);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"ext1",$ext1);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"ext2",$ext2);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"payResult",$payResult);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"errCode",$errCode);
        $merchantSignMsgVal=$this->append_param($merchantSignMsgVal,"key",$key);
        $merchantSignMsg=md5($merchantSignMsgVal);

        //首先对获得的商户号进行比对
        if ($get_merchant_acctid != $merchantAcctId)
        {
            //商户号错误
            return false;
        }

        if (strtoupper($signMsg) == strtoupper($merchantSignMsg))
        {
            if ($payResult == '10' || $payResult == '00')
            {
                $orderAmount = intval($orderAmount) / 100;
                $this -> _api -> update($orderId, $orderAmount, 'kuaiqian');

                echo '<result>1</result><redirecturl>'.$ext1.'</redirecturl>';
            }
            else
            {
                //'支付结果失败';
            }

        }
        else
        {
            //'密钥校对错误';
        }
    }
    function auth() {
        $time = time();
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'kuaiqian')));
        $payment = unserialize($payment['config']);
        $input_charset      = 1;
        $page_url           = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/kuaiqian";
        $bg_url             = "http://{$_SERVER['HTTP_HOST']}/payment/sync/pay_type/kuaiqian";
        $version            = 'v2.0';
        $language           = 1;
        $sign_type          = 1;
        $merchant_acctid    = trim($payment['kq_account']).'01';
        $payer_name         = '';
        $payer_contact_type = '';
        $payer_contact      = '';
        $order_id           = $order['batch_sn'] . '-' . $time;
        $order_amount       = $_POST['amount'] * 100;
        $order_time         = date('YmdHis', $order['add_time']);
        $product_name       = '';
        $product_num        = '';
        $product_id         = '';
        $product_desc       = '';
        $ext1               = $page_url;
        $ext2               = '99bill';
        $pay_type           = '00';//10
        if($_POST['bank_id']){
           $bank_id            = $_POST['bank_id'];
           $pay_type = '10';
        }else{
           $bank_id            = '';
           $pay_type = '00';
        }
        $redo_flag          = '0';
        $pid                = '';
        $key                = trim($payment['kq_key']);
        $signmsgval = '';
        $signmsgval = $this->append_param($signmsgval, "inputCharset", $input_charset);
        $signmsgval = $this->append_param($signmsgval, "pageUrl", $page_url);
        $signmsgval = $this->append_param($signmsgval, "bgUrl", $bg_url);
        $signmsgval = $this->append_param($signmsgval, "version", $version);
        $signmsgval = $this->append_param($signmsgval, "language", $language);
        $signmsgval = $this->append_param($signmsgval, "signType", $sign_type);
        $signmsgval = $this->append_param($signmsgval, "merchantAcctId", $merchant_acctid);
        $signmsgval = $this->append_param($signmsgval, "payerName", $payer_name);
        $signmsgval = $this->append_param($signmsgval, "payerContactType", $payer_contact_type);
        $signmsgval = $this->append_param($signmsgval, "payerContact", $payer_contact);
        $signmsgval = $this->append_param($signmsgval, "orderId", $order_id);
        $signmsgval = $this->append_param($signmsgval, "orderAmount", $order_amount);
        $signmsgval = $this->append_param($signmsgval, "orderTime", $order_time);
        $signmsgval = $this->append_param($signmsgval, "productName", $product_name);
        $signmsgval = $this->append_param($signmsgval, "productNum", $product_num);
        $signmsgval = $this->append_param($signmsgval, "productId", $product_id);
        $signmsgval = $this->append_param($signmsgval, "productDesc", $product_desc);
        $signmsgval = $this->append_param($signmsgval, "ext1", $ext1);
        $signmsgval = $this->append_param($signmsgval, "ext2", $ext2);
        $signmsgval = $this->append_param($signmsgval, "payType", $pay_type);
        $signmsgval = $this->append_param($signmsgval, "bankId", $bank_id);
        $signmsgval = $this->append_param($signmsgval, "redoFlag", $redo_flag);
        $signmsgval = $this->append_param($signmsgval, "pid", $pid);
        $signmsgval = $this->append_param($signmsgval, "key", $key);
        $signmsg    = strtoupper(md5($signmsgval));

        $def_url = "<input type='hidden' name='inputCharset'       id='inputCharset' value='" . $input_charset . "' />";
        $def_url .= "<input type='hidden' name='bgUrl'              id='bgUrl' value='" . $bg_url . "' />";
        $def_url .= "<input type='hidden' name='pageUrl'            id='pageUrl' value='" . $page_url . "' />";
        $def_url .= "<input type='hidden' name='version'            id='version' value='" . $version . "' />";
        $def_url .= "<input type='hidden' name='language'           id='language' value='" . $language . "' />";
        $def_url .= "<input type='hidden' name='signType'           id='signType' value='" . $sign_type . "' />";
        $def_url .= "<input type='hidden' name='merchantAcctId'     id='merchantAcctId' value='" . $merchant_acctid . "' />";
        $def_url .= "<input type='hidden' name='payerName'          id='payerName' value='" . $payer_name . "' />";
        $def_url .= "<input type='hidden' name='payerContactType'   id='payerContactType' value='" . $payer_contact_type . "' />";
        $def_url .= "<input type='hidden' name='payerContact'       id='payerContact' value='" . $payer_contact . "' />";
        $def_url .= "<input type='hidden' name='orderId'            id='orderId' name='orderId' value='" . $order_id . "' />";
        $def_url .= "<input type='hidden' name='orderAmount'        id='orderAmount' value='" . $order_amount . "' />";
        $def_url .= "<input type='hidden' name='orderTime'          id='orderTime' value='" . $order_time . "' />";
        $def_url .= "<input type='hidden' name='productName'        id='productName' value='" . $product_name . "' />";
        $def_url .= "<input type='hidden' name='productNum'         id='productNum' value='" . $product_num . "' />";
        $def_url .= "<input type='hidden' name='productId'          id='productId' value='" . $product_id . "' />";
        $def_url .= "<input type='hidden' name='productDesc'        id='productDesc' value='" . $product_desc . "' />";
        $def_url .= "<input type='hidden' name='ext1'               id='ext1' value='" . $ext1 . "' />";
        $def_url .= "<input type='hidden' name='ext2'               id='ext2' value='" . $ext2 . "' />";
        $def_url .= "<input type='hidden' name='payType'            id='payType' value='" . $pay_type . "' />";
        $def_url .= "<input type='hidden' name='bankId'             id='BankId' value='" . $bank_id . "' />";
        $def_url .= "<input type='hidden' name='redoFlag'           id='redoFlag' value='" . $redo_flag ."' />";
        $def_url .= "<input type='hidden' name='pid'                id='pid' value='" . $pid . "' />";
        $def_url .= "<input type='hidden' name='signMsg'            id='signMsg' value='" . $signmsg . "' />";

        echo $def_url;

    }
    /**
    * 将变量值不为空的参数组成字符串
    * @param   string   $strs  参数字符串
    * @param   string   $key   参数键名
    * @param   string   $val   参数键对应值
    */
    function append_param($strs,$key,$val)
    {
        if($strs != "")
        {
            if($key != '' && $val != '')
            {
                $strs .= '&' . $key . '=' . $val;
            }
        }
        else
        {
            if($val != '')
            {
                $strs = $key . '=' . $val;
            }
        }
            return $strs;
    }


}