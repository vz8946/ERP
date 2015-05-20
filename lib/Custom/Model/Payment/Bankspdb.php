<?php

require_once("../lib/Bank/spdb/java/Java.inc");

$payments['bankspdb'] = '直连-浦发银行';
class Custom_Model_Payment_Bankspdb extends Custom_Model_Payment_Abstract
{
    public $payType = 'bankspdb';
    public $payTypeName = '上海浦东发展银行';
    
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
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankspdb')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！';
            return $this -> returnRes;
        }
        
        java_set_library_path($payment['jar_path']);
        
        $merverify = new Java('com.csii.payment.client.core.MerchantSignVerify');
        
        $Signature = $_REQUEST['Signature'];
        $Plain = $_REQUEST['Plain'];
        
        $result = $merverify->merchantVerifyPayGate_ABA($Signature, $Plain);
        if ( $result != 1 ) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败[签名认证失败]';
            return $this -> returnRes;
        }
        
        $Parameter = explode( '|', $Plain );
        for ( $i = 0; $i < count($Parameter); $i++ ) {
            $temparr = explode( '=', $Parameter[$i] );
            $tempvar = $temparr[0];
            $$tempvar = $temparr[1];
        }
        
        if ( $RespCode != '00' ) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
            return $this -> returnRes;
        }
        
        $batch_sn = '21'.$TermSsn;
        $pay_log_id = $batch_sn.'-'.$AcqSsn;    //AcqSsn为流水号，用于确定每笔交易的唯一性
        $amount = $TranAmt;
        
        $apiReturnRes = $this -> _api -> update($pay_log_id, $amount, 'bankspdb');
        if ($apiReturnRes['result']) {
            $this -> returnRes['stats'] = true;
            $this -> returnRes['thisPaid'] = $amount;
            $this -> returnRes['difference'] = $apiReturnRes['remainder'];  //差额
        } else {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = $apiReturnRes['msg'] != '' ? $apiReturnRes['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
        }
        
        return $this -> returnRes;
    }

    function auth() {
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankspdb')));
        $payment = unserialize($payment['config']);
        
        java_set_library_path($payment['jar_path']);
        
        $TranAbbr = 'IPER';
        $MercDtTm = date('YmdHis', time());
        $TermSsn = substr( $_POST['batch_sn'], 2, 12 );
        $MercCode = $payment['yp_account'];
        $TermCode = '000';
        $TranAmt = $_POST['amount'];
        $MercUrl = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/bankspdb";
        $plain="TranAbbr={$TranAbbr}|MercDtTm={$MercDtTm}|TermSsn={$TermSsn}|OSttDate=|OAcqSsn=|MercCode={$MercCode}|TermCode={$TermCode}|TranAmt={$TranAmt}|Remark1=|Remark2=|MercUrl={$MercUrl}";
        
        $merverify = new Java('com.csii.payment.client.core.MerchantSignVerify');
        $signature = $merverify->merchantSignData_ABA($plain);
        
        $def_url = "<input type=hidden name=transName value=\"IPER\">\n
                    <input type=hidden name=Plain value=\"{$plain}\">\n
                    <input type=hidden name=Signature value=\"{$signature}\">\n";
        
        echo $def_url;
    }
    
    function query() {
        if ( !parent::query() ) return false;
        
        $MercCode = $this -> payment['yp_account'];
        $TermSsn = substr( $this -> order['batch_sn'], 2, 12 );
        
        java_set_library_path($this -> payment['jar_path']);
        
        $merverify = new Java('com.csii.payment.client.core.MerchantSignVerify');
        
        $plain = "MercCode={$MercCode}|OTranAbbr=IPER|TermSsn={$TermSsn}";
        $signature = $merverify->merchantSignData_ABA($plain);
        
        $post_data = "transName=IQSR&Plain={$plain}&Signature={$signature}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://ebank.spdb.com.cn/payment/main");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        
        $result = new Zend_Config_Xml($output);
        $result = $result->toArray();
        if ( $result['ErrorCode'] ) return false;
        
        if ( !$merverify->merchantVerifyPayGate_ABA($result['Signature'], $result['Plain']) ) {
            return false;
        }
        
        $Parameter = explode( '|', $result['Plain'] );
        for ( $i = 0; $i < count($Parameter); $i++ ) {
            $temparr = explode( '=', $Parameter[$i] );
            $tempvar = $temparr[0];
            $$tempvar = $temparr[1];
        }
        
        if ( $this -> order['batch_sn'] != '21'.$TermSsn )  return false;
        
        $this -> _api -> update($this -> order['batch_sn'].'-'.$AcqSsn, $TranAmt, 'bankspdb');
    }
    


}