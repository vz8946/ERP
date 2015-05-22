<?php

require_once("../lib/Bank/spdb/java/Java.inc");

$payments['baofoo'] = '宝付';
class Custom_Model_Payment_Baofoo extends Custom_Model_Payment_Abstract
{
    public $payType = 'baofoo';
    public $payTypeName = '宝付';
    
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
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'baofoo')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！';
            return $this -> returnRes;
        }
        
        if ( $_GET['Result'] == 0 ) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败';
            return $this -> returnRes;
        }
        $Md5Key = $payment['key'];
        $WaitSign = md5( $_GET['MerchantID'].$_GET['TransID'].$_GET['Result'].$_GET['resultDesc'].$_GET['factMoney'].$_GET['additionalInfo'].$_GET['SuccTime'].$Md5Key );
        if ( $_GET['Md5Sign'] != $WaitSign ) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！';
            return $this -> returnRes;
        }
        
        if ( $_GET['MerchantID'] != $payment['yp_account'] ) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！';
            return $this -> returnRes;
        }
        
        $pay_log_id = $_GET['TransID'];
        $amount = $_GET['factMoney'] / 100;
        
        $apiReturnRes = $this -> _api -> update($pay_log_id, $amount, 'baofoo');
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
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'baofoo')));
        $payment = unserialize($payment['config']);
        
        $seriesNo = $this -> _api -> createPaySeries($this->payType, $order['batch_sn'], $_POST['amount']);
        
        $PayID = 0;
        $MerchantID = $payment['yp_account'];
        $TransID = $order['batch_sn'].'-'.$seriesNo;
        $TradeDate = date('YmdHis', time());
        $OrderMoney = $_POST['amount'] * 100;
        $Merchant_url = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/baofoo";
        $Return_url = '';
        $NoticeType = 1;
        $Md5Key = $payment['key'];
        $Md5Sign = md5( $MerchantID.$PayID.$TradeDate.$TransID.$OrderMoney.$Merchant_url.$Return_url.$NoticeType.$Md5Key );
        
        $def_url = "<input type='hidden' name='MerchantID' value='{$MerchantID}' />
                    <input type='hidden' name='PayID' value='{$PayID}' />
                    <input type='hidden' name='TradeDate' value='{$TradeDate}' />
                    <input type='hidden' name='TransID' value='{$TransID}' />
                    <input type='hidden' name='OrderMoney' value='{$OrderMoney}' />
                    <input type='hidden' name='ProductName' value='' />
                    <input type='hidden' name='Amount' value='' />
                    <input type='hidden' name='ProductLogo' value='' />
                    <input type='hidden' name='Username' value='' />
                    <input type='hidden' name='Email' value='' />
                    <input type='hidden' name='Mobile' value='' />
                    <input type='hidden' name='AdditionalInfo' value='' />
                    <input type='hidden' name='Merchant_url' value='{$Merchant_url}' />
                    <input type='hidden' name='Return_url' value='{$Return_url}' />
                    <input type='hidden' name='NoticeType' value='{$NoticeType}' />
                    <input type='hidden' name='Md5Sign' value='{$Md5Sign}' />";
        
        echo $def_url;
    }
    
    function query() {
        if ( !parent::query() ) return false;
        
        $MerchantID = $this -> payment['yp_account'];
        $SeriesNo = $this -> _api -> getLastPaySeries($this -> order['batch_sn']);
        if ( !$SeriesNo )   return false;
        $TransID = $this -> order['batch_sn'].'-'.$SeriesNo;
        $Md5Key = $this -> payment['key'];
        
        $Md5Sign = md5( $MerchantID.$TransID.$Md5Key );
        $QueryURL = $this->payment['query_url'];
        $post_data = "MerchantID={$MerchantID}&TransID={$TransID}&Md5Sign={$Md5Sign}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $QueryURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        
        $temparr = explode( '|', $output );
        $MerchantID = $temparr[0];
        $TransID = $temparr[1];
        $CheckResult = $temparr[2];
        $factMoney = $temparr[3];
        $SuccTime = $temparr[4];
        $Md5Sign = $temparr[5];
        
        $WaitSign = md5( $MerchantID.$TransID.$CheckResult.$factMoney.$SuccTime.$Md5Key );
        if ( $WaitSign != $Md5Sign ) {
            return false;
        }
        if ( $MerchantID != $this -> payment['yp_account'] ) {
            return false;
        }
        $temparr = explode( '-', $TransID );
        if ( $this -> order['batch_sn'] != $temparr[0] ){
            return false;
        }
        if ( $CheckResult != 'Y' ) {
            return false;
        }
        
        $this -> _api -> update($TransID, $factMoney / 100, 'baofoo');
    }
    


}