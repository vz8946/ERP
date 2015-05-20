<?php

$payments['shengpay'] = '盛付通';
class Custom_Model_Payment_Shengpay extends Custom_Model_Payment_Abstract
{
    public $payType = 'shengpay';
    public $payTypeName = '盛付通';
    
    function getFields($config=null){
        $html .= '<table><tr><th width="150">';
        $html .= '商户号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][yp_account]" value="'.$config['yp_account'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '密钥';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][key]" value="'.$config['key'].'" style="width:130px">';
        $html .= '</td></tr><tr><th>';
        $html .= '支付网关';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][pay_url]" value="'.$config['pay_url'].'" style="width:300px">';
        $html .= '</td></tr><tr><th>';
        $html .= '查询网关';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][query_url]" value="'.$config['query_url'].'" style="width:300px">';
        $html .= '</td></tr></table>';
        
        return $html;
    }
    
    function respond(){
        $respond = $this -> getRespond();
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
    
    function auth() {
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'shengpay')));
        $payment = unserialize($payment['config']);
        
        $seriesNo = $this -> _api -> createPaySeries($this->payType, $order['batch_sn'], $_POST['amount']);
        
        $params = array( 'Name' => 'B2CPayment',
                	     'Version' => 'V4.1.1.1.1',
                	     'Charset' => 'UTF-8',
                	     'MsgSender' => $payment['yp_account'],
                	     'SendTime' => date('YmdHis', time()),
                	     'OrderNo'=> $order['batch_sn'].'-'.$seriesNo,
                	     'OrderAmount' => $_POST['amount'],
                	     'OrderTime' => date('YmdHis', time()),
                	     'PayType' => '',
                	     'InstCode'=> '',
                	     'PageUrl' => "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/shengpay",
                	     'NotifyUrl'=>"http://{$_SERVER['HTTP_HOST']}/payment/sync/pay_type/shengpay",
                	     'ProductName' => '',
                	     'BuyerContact'=> '' ,
                	     'BuyerIp'=> '',
                	     'Ext1' => '',
                	     'Ext2' => '',
                	     'SignType' => 'MD5',
                	  );
        $origin = '';
		foreach( $params as $key => $value ) {
			if( !empty($value) ) {
				$origin .= $value;
	        }
		}
		$params['SignMsg'] = strtoupper( md5($origin.$payment['key']) );
        foreach( $params as $key => $value ) {
		    $def_url .= '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
	    }
        
        echo $def_url;
    }
    
    public function sync()
    {
        $this -> setOpenWriteLog();
        $this -> writeLog(null, 'shengpay');
        
        if ( is_array($this -> getRespond()) ) {
            echo 'OK';
        }
        else    echo 'Error';
        
        exit;
    }
    
    private function getRespond() {
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'shengpay')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            return 'error';
        }
        
        $params = array('Name' => '',
            			'Version' => '',
            			'Charset' => '',
            			'TraceNo' => '',
            			'MsgSender' => '',
            			'SendTime' => '',
            			'InstCode' => '',
            			'OrderNo' => '',
            			'OrderAmount' => '',
            			'TransNo' => '',
            			'TransAmount' => '',
            			'TransStatus' => '',
            			'TransType' => '',
            			'TransTime' => '',
            			'MerchantNo' => '',
            			'ErrorCode' => '',
            			'ErrorMsg' => '',
            			'Ext1' => '',
            			'Ext2' => '',
            			'SignType' => 'MD5',
            		   );
		foreach($_POST as $key => $value){
			if(isset($params[$key])) {
				$params[$key] = $value;
			}
		}
		$TransStatus=(int)$_POST['TransStatus'];
		$origin = '';
		foreach($params as $key=>$value) {
			if(!empty($value))
				$origin .= $value;
		}
		$SignMsg = strtoupper(md5($origin.$payment['key']));
		if ( $SignMsg != $_POST['SignMsg'] ) {
            return 'sign_fail';
        }
        if ( $TransStatus != 1 ) {
		    return 'pay_fail';
	    }
        
        $temparr = explode('-', $_POST['OrderNo']);
        $batch_sn = $temparr[0];
        $pay_log_id = $batch_sn.'-'.$temparr[1];
        $apiReturnRes = $this -> _api -> update($pay_log_id, $_POST['TransAmount'], 'shengpay');
        
        return array('batch_sn' => $batch_sn, 'amount' => $_POST['TransAmount'], 'return' => $apiReturnRes);
    }
    
    public function query() {
        if ( !parent::query() ) return false;
        
        return true;
    }
    


}