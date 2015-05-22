<?php

require_once("../lib/Bank/spdb/java/Java.inc");

$payments['bankcomm'] = '直连-交通银行';
class Custom_Model_Payment_Bankcomm extends Custom_Model_Payment_Abstract
{
    public $payType = 'bankcomm';
    public $payTypeName = '交通银行';
    
    function getFields($config=null){
        $html .= '<table><tr><th width="150">';
        $html .= '商户编号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][yp_account]" value="'.$config['yp_account'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= 'jar文件路径';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][jar_path]" value="'.$config['jar_path'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= 'xml路径+文件名';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][xml_path]" value="'.$config['xml_path'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '支付网关';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][pay_url]" value="'.$config['pay_url'].'" style="width:300px">';
        $html .= '</td></tr></table>';

        return $html;
    }
    
    function  getCode()
    {
    	$time = $this -> time;
    	$order = $this -> order;
    	$payment = $this -> payment;
    	
    	if ($arg['pay_hidden'] == false) {
    		$type = '';
    	} else {
    		$type = 'none';
    	}
    	 
    	$target = 'target="_blank"';
    	
    	$payAmount = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
    	$params = $this->check($order['batch_sn'], $payAmount, $payment);
    		
    	$button = '<form id="form_bankcomm"  action="https://pay.95559.com.cn/netpay/MerPayB2C" style="margin:0px;padding:0px" target="_blank"  method="post">';
    	$button .= "<div style='display:none;' id='htm_tenpay'>{$params}</div>";
    	$button .= '<div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="6" id="pay_tenpay" readonly value="'.$payAmount.'"></div> ';
    	$button .= '<input type="submit" class="buttons4" value="支付订单"/></form>';
    	return $button;
    }
    
    function check($order_sn,$amount,$payment)
    {    	
    	
    	$seriesNo = $this -> _api -> createPaySeries($this->payType, $order_sn, $amount);    	
    	java_set_library_path($payment['jar_path']);
    	java_set_file_encoding("GBK");
    	
    	$BOCOMSetting = new java("com.bocom.netpay.b2cAPI.BOCOMSetting");
    	$client = new java("com.bocom.netpay.b2cAPI.BOCOMB2CClient");
    	$client->initialize($payment['xml_path']);
    	
    	$interfaceVersion = '1.0.0.0';
    	$merID = $BOCOMSetting->MerchantID;
    	$orderid = $order_sn.'-'.$seriesNo;
    	$orderDate = date('Ymd', time());
    	$orderTime = date('His', time());
    	$tranType = 0;
    	$curType = 'CNY';
    	$orderContent = '';
    	$orderMono = '';
    	$phdFlag = '';
    	$notifyType = 1;
    	$merURL = "http://{$_SERVER['HTTP_HOST']}/payment/sync/pay_type/bankcomm";
    	$goodsURL = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/bankcomm";
    	$jumpSeconds = '5';
    	$payBatchNo = '';
    	$proxyMerName = '';
    	$proxyMerType = '';
    	$proxyMerCredentials = '';
    	$netType= 0;
    	
    	$source = $interfaceVersion . "|" . $merID . "|" . $orderid . "|" . $orderDate . "|" . $orderTime . "|"
    			. $tranType . "|" . $amount . "|" . $curType . "|" . $orderContent . "|" . $orderMono . "|"
    					. $phdFlag . "|" . $notifyType . "|" . $merURL . "|" . $goodsURL . "|" . $jumpSeconds . "|"
    							. $payBatchNo . "|" . $proxyMerName . "|" . $proxyMerType . "|" . $proxyMerCredentials . "|"
    									. $netType;
    	
    	$sourceMsg = new java("java.lang.String", $source);
    	$nss = new java("com.bocom.netpay.b2cAPI.NetSignServer");
    	
    	$merchantDN = $BOCOMSetting->MerchantCertDN;
    	$nss->NSSetPlainText( $sourceMsg->getBytes("GBK") );
    	
    	$bSignMsg = $nss->NSDetachedSign($merchantDN);
    	$signMsg = new java("java.lang.String", $bSignMsg, "GBK");
    	
    	$def_url = "<input type=hidden name=interfaceVersion value=\"{$interfaceVersion}\">\n
    	<input type=hidden name=merID value=\"{$merID}\">\n
    	<input type=hidden name=orderid value=\"{$orderid}\">\n
    	<input type=hidden name=orderDate value=\"{$orderDate}\">\n
    	<input type=hidden name=orderTime value=\"{$orderTime}\">\n
    	<input type=hidden name=tranType value=\"{$tranType}\">\n
    	<input type=hidden name=amount value=\"{$amount}\">\n
    	<input type=hidden name=curType value=\"{$curType}\">\n
    	<input type=hidden name=orderContent value=\"{$orderContent}\">\n
    	<input type=hidden name=orderMono value=\"{$orderMono}\">\n
    	<input type=hidden name=phdFlag value=\"{$phdFlag}\">\n
    	<input type=hidden name=notifyType value=\"{$notifyType}\">\n
    	<input type=hidden name=merURL value=\"{$merURL}\">\n
    	<input type=hidden name=goodsURL value=\"{$goodsURL}\">\n
    	<input type=hidden name=jumpSeconds value=\"{$jumpSeconds}\">\n
    	<input type=hidden name=payBatchNo value=\"{$payBatchNo}\">\n
    	<input type=hidden name=proxyMerName value=\"{$proxyMerName}\">\n
    	<input type=hidden name=proxyMerType value=\"{$proxyMerType}\">\n
    	<input type=hidden name=proxyMerCredentials value=\"{$proxyMerCredentials}\">\n
    	<input type=hidden name=netType value=\"{$netType}\">\n
    	<input type=hidden name=merSignMsg value=\"{$signMsg}\">\n";    	
    	return  $def_url;
    }
    
    
    function auth() {
        
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankcomm')));
        $payment = unserialize($payment['config']);
        
        $seriesNo = $this -> _api -> createPaySeries($this->payType, $order['batch_sn'], $_POST['amount']);
        
        java_set_library_path($payment['jar_path']);
        java_set_file_encoding("GBK");
        
        $BOCOMSetting = new java("com.bocom.netpay.b2cAPI.BOCOMSetting");
        $client = new java("com.bocom.netpay.b2cAPI.BOCOMB2CClient");
        $client->initialize($payment['xml_path']);

        $interfaceVersion = '1.0.0.0';
        $merID = $BOCOMSetting->MerchantID;
        $orderid = $_POST['batch_sn'].'-'.$seriesNo;
        $orderDate = date('Ymd', time());
        $orderTime = date('His', time());
        $tranType = 0;
        $amount = $_POST['amount'];
        $curType = 'CNY';
        $orderContent = '';
        $orderMono = '';
        $phdFlag = '';
        $notifyType = 1;
        $merURL = "http://{$_SERVER['HTTP_HOST']}/payment/sync/pay_type/bankcomm";
        $goodsURL = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/bankcomm";
        $jumpSeconds = '5';
        $payBatchNo = '';
        $proxyMerName = '';
        $proxyMerType = '';
        $proxyMerCredentials = '';
        $netType= 0;

        $source = $interfaceVersion . "|" . $merID . "|" . $orderid . "|" . $orderDate . "|" . $orderTime . "|"
                    . $tranType . "|" . $amount . "|" . $curType . "|" . $orderContent . "|" . $orderMono . "|"
                    . $phdFlag . "|" . $notifyType . "|" . $merURL . "|" . $goodsURL . "|" . $jumpSeconds . "|"
                    . $payBatchNo . "|" . $proxyMerName . "|" . $proxyMerType . "|" . $proxyMerCredentials . "|"
                    . $netType;
        
        $sourceMsg = new java("java.lang.String", $source);
        $nss = new java("com.bocom.netpay.b2cAPI.NetSignServer");
        
        $merchantDN = $BOCOMSetting->MerchantCertDN;
        $nss->NSSetPlainText( $sourceMsg->getBytes("GBK") );
        
        $bSignMsg = $nss->NSDetachedSign($merchantDN);
        $signMsg = new java("java.lang.String", $bSignMsg, "GBK");
        
        $def_url = "<input type=hidden name=interfaceVersion value=\"{$interfaceVersion}\">\n
                    <input type=hidden name=merID value=\"{$merID}\">\n
                    <input type=hidden name=orderid value=\"{$orderid}\">\n
                    <input type=hidden name=orderDate value=\"{$orderDate}\">\n
                    <input type=hidden name=orderTime value=\"{$orderTime}\">\n
                    <input type=hidden name=tranType value=\"{$tranType}\">\n
                    <input type=hidden name=amount value=\"{$amount}\">\n
                    <input type=hidden name=curType value=\"{$curType}\">\n
                    <input type=hidden name=orderContent value=\"{$orderContent}\">\n
                    <input type=hidden name=orderMono value=\"{$orderMono}\">\n
                    <input type=hidden name=phdFlag value=\"{$phdFlag}\">\n
                    <input type=hidden name=notifyType value=\"{$notifyType}\">\n
                    <input type=hidden name=merURL value=\"{$merURL}\">\n
                    <input type=hidden name=goodsURL value=\"{$goodsURL}\">\n
                    <input type=hidden name=jumpSeconds value=\"{$jumpSeconds}\">\n
                    <input type=hidden name=payBatchNo value=\"{$payBatchNo}\">\n
                    <input type=hidden name=proxyMerName value=\"{$proxyMerName}\">\n
                    <input type=hidden name=proxyMerType value=\"{$proxyMerType}\">\n
                    <input type=hidden name=proxyMerCredentials value=\"{$proxyMerCredentials}\">\n
                    <input type=hidden name=netType value=\"{$netType}\">\n
                    <input type=hidden name=merSignMsg value=\"{$signMsg}\">\n";
        
        echo $def_url;
    }
    
    
    
    function respond() {
        $respond = $this -> getRespond();
        
        $this->returnRes['order_sn'] = $respond['order_sn'];
        if ( is_array($respond) ) {
            if ($respond['return']['result']) {
                $this -> returnRes['stats'] = true;
                $this -> returnRes['thisPaid'] = $respond['amount'];
                $this -> returnRes['difference'] = $respond['return']['remainder'];  //差额
            } else {
                $this -> returnRes['stats'] = false;
                $this -> returnRes['msg'] = $respond['return']['msg'] != '' ? $respond['return']['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
            }
        }
        else {
            $this -> returnRes['stats'] = false;
            if ( $respond == 'error' ) {
                $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！';
            }
            else if ( $respond == 'sign_fail' ) {
                $this -> returnRes['msg'] = '支付失败[签名认证失败]';
            }
            else if ( $respond == 'pay_fail' ) {
                $this -> returnRes['msg'] = '支付失败[支付操作失败]';
            }
        }
        
        return $this -> returnRes;
    }
    
    public function sync()
    {
        $this -> setOpenWriteLog();
        $this -> writeLog(null, 'bankcomm');
        
        if ( is_array($this -> getRespond()) ) {
            echo 'success';
        }
        else    echo 'error';
        
        exit;
    }
    
    private function getRespond() {
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankcomm')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            return 'error';
        }
        
        java_set_library_path($payment['jar_path']);
        java_set_file_encoding("GBK");
        
        $notifyMsg = $_REQUEST["notifyMsg"];
        
        $lastIndex = strripos($notifyMsg, "|");
        $signMsg = substr($notifyMsg, $lastIndex + 1);
        $srcMsg = substr($notifyMsg, 0, $lastIndex + 1);
        $signMsg = new java("java.lang.String", $signMsg);
        $srcMsg = new java("java.lang.String", $srcMsg);
        $client = new java("com.bocom.netpay.b2cAPI.BOCOMB2CClient");
        $client->initialize($payment['xml_path']);
        
        $nss = new java("com.bocom.netpay.b2cAPI.NetSignServer");
        $nss->NSDetachedVerify($signMsg->getBytes("GBK"), $srcMsg->getBytes("GBK"));
        $veriyCode = java_values($nss->getLastErrnum());
        if ($veriyCode < 0) {
            return 'sign_fail';
        }
        
        $arr = preg_split("/\|{1,}/", $srcMsg);
        if ( $arr[9] != 1 ) {
            return 'pay_fail';
        }
        
        $temparr = explode('-', $arr[1]);
        $batch_sn = $temparr[0];
        $pay_log_id = $batch_sn.'-'.$temparr[1];
        $amount = $arr[2];
        $apiReturnRes = $this -> _api -> update($pay_log_id, $amount, 'bankcomm');
        
        return array('batch_sn' => $batch_sn,'order_sn'=>$pay_log_id, 'amount' => $amount, 'return' => $apiReturnRes);
    }
    
    public function query() {
        if ( !parent::query() ) return false;
        
        return true;
    }

}