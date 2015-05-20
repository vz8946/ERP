<?php

require_once("../lib/Bank/spdb/java/Java.inc");

$payments['bankboc'] = '直连-中国银行';
class Custom_Model_Payment_Bankboc extends Custom_Model_Payment_Abstract
{
    public $payType = 'bankboc';
    public $payTypeName = '中国银行';
    
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
        $html .= '商户证书文件路径';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][cert_path]" value="'.$config['cert_path'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '根证书文件路径';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][root_path]" value="'.$config['root_path'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '支付网关';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][pay_url]" value="'.$config['pay_url'].'" style="width:300px">';
        $html .= '</td></tr></table>';

        return $html;
    }
    
    function auth() {
        
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankboc')));
        $payment = unserialize($payment['config']);
        
        $seriesNo = $this -> _api -> createPaySeries($this->payType, $order['batch_sn'], $_POST['amount']);
        
        java_set_library_path($payment['jar_path']);
        
        $Signature = new java("custom");
        
        $orderNo = $_POST['batch_sn'].'-'.$seriesNo;
        $orderTime = date('YmdHis');
        $curCode = '001';
        $orderAmount = sprintf("%1\$.2f", $_POST['amount']);
        $merchantNo = $payment['yp_account'];
        $signData = $Signature -> sign("{$orderNo}|{$orderTime}|{$curCode}|{$orderAmount}|{$merchantNo}", $payment['cert_path'], '111111');
        
        $payType = 1;
        $orderNote = 'food';
        $orderUrl = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/bankboc";
        
        $def_url = "<input type=hidden name=merchantNo value=\"{$merchantNo}\">\n
                    <input type=hidden name=payType value=\"{$payType}\">\n
                    <input type=hidden name=orderNo value=\"{$orderNo}\">\n
                    <input type=hidden name=curCode value=\"{$curCode}\">\n
                    <input type=hidden name=orderAmount value=\"{$orderAmount}\">\n
                    <input type=hidden name=orderTime value=\"{$orderTime}\">\n
                    <input type=hidden name=orderNote value=\"{$orderNote}\">\n
                    <input type=hidden name=orderUrl value=\"{$orderUrl}\">\n
                    <input type=hidden name=signData value=\"{$signData}\">\n";
        
        echo $def_url;
    }
    
    function respond() {
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
    
    public function sync()
    {
        $this -> setOpenWriteLog();
        $this -> writeLog(null, 'bankboc');
        
        if ( is_array($this -> getRespond()) ) {
            echo 'success';
        }
        else    echo 'error';
        
        exit;
    }
    
    private function getRespond() {
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankboc')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            return 'error';
        }
        
        java_set_library_path($payment['jar_path']);
        
        $Signature = new java("custom");
        
        $merchantNo = $_REQUEST["merchantNo"];
        $orderNo = $_REQUEST["orderNo"];
        $orderSeq = $_REQUEST["orderSeq"];
        $cardTyp = $_REQUEST["cardTyp"];
        $payTime = $_REQUEST["payTime"];
        $orderStatus = $_REQUEST["orderStatus"];
        $payAmount = $_REQUEST["payAmount"];
        
        $result = $Signature -> verify($_REQUEST["signData"], "{$merchantNo}|{$orderNo}|{$orderSeq}|{$cardTyp}|{$payTime}|{$orderStatus}|{$payAmount}", $payment['root_path']);
        if ($result != '1') {
            return 'sign_fail';
        }
        if ($orderStatus != '1') {
            return 'pay_fail';
        }
        
        $temparr = explode('-', $orderNo);
        $batch_sn = $temparr[0];
        $pay_log_id = $batch_sn.'-'.$temparr[1];
        $amount = $payAmount;
        $apiReturnRes = $this -> _api -> update($pay_log_id, $amount, 'bankboc');
        
        return array('batch_sn' => $batch_sn, 'amount' => $amount, 'return' => $apiReturnRes);
    }
    
    public function query() {
        if ( !parent::query() ) return false;
        
        return true;
    }

}