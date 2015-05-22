<?php

require_once("../lib/Bank/spdb/java/Java.inc");

$payments['bankcmb'] = '直连-招商银行';
class Custom_Model_Payment_Bankcmb extends Custom_Model_Payment_Abstract
{
    public $payType = 'bankcmb';
    public $payTypeName = '招商银行';
    
    function getFields($config=null){
        $html .= '<table><tr><th width="150">';
        $html .= '商户编号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][yp_account]" value="'.$config['yp_account'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '开户行分号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][bank_account]" value="'.$config['bank_account'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= 'jar文件路径';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][jar_path]" value="'.$config['jar_path'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= 'pubkey文件路径';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][key_path]" value="'.$config['key_path'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '密钥';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][key]" value="'.$config['key'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '支付网关';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][pay_url]" value="'.$config['pay_url'].'" style="width:300px">';
        $html .= '</td></tr></table>';

        return $html;
    }
    
    function auth() {
        
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankcmb')));
        $payment = unserialize($payment['config']);
        
        $seriesNo = $this -> _api -> createPaySeries($this->payType, $order['batch_sn'], $_POST['amount']);
        $paySeries = $this -> _api -> getPaySeries(array('seriesNo' => $seriesNo, 'batchSn' => $order['batch_sn']));
        $id = '000000000'.$paySeries['id'];
        
        java_set_library_path($payment['jar_path']);
        
        $MerchantCode = new Java('cmb.MerchantCode');
        
        $date = date('Ymd');
        $cono = $payment['yp_account'];
        $branchid = $payment['bank_account'];
        $billno = substr($id, -10);
        $amount = $_POST['amount'];
        $MerchantUrl = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/bankcmb";
        $MerchantPara = '';
        //$sign = $MerchantCode -> genMerchantCode($payment['key'], $date, $cono, $branchid, $billno, $amount, "", $MerchantUrl, "", "");
        
        $def_url = "<input type=hidden name=date value=\"{$date}\">\n
                    <input type=hidden name=cono value=\"{$cono}\">\n
                    <input type=hidden name=branchid value=\"{$branchid}\">\n
                    <input type=hidden name=billno value=\"{$billno}\">\n
                    <input type=hidden name=amount value=\"{$amount}\">\n
                    <input type=hidden name=MerchantUrl value=\"{$MerchantUrl}\">\n";
                    //<input type=hidden name=MerchantPara value=\"{$MerchantPara}\">\n
                    //<input type=hidden name=MerchantCode value=\"{$sign}\">\n";
        
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
        $this -> writeLog(null, 'bankcmb');
        
        if ( is_array($this -> getRespond()) ) {
            echo 'success';
        }
        else    echo 'error';
        
        exit;
    }
    
    private function getRespond() {
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankcmb')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            return 'error';
        }
        
        java_set_library_path($payment['jar_path']);
        
        $Security = new Java('cmb.netpayment.Security', $payment['key_path']);
        
        $retCode = "Succeed={$_REQUEST['Succeed']}&CoNo={$_REQUEST['CoNo']}&BillNo={$_REQUEST['BillNo']}&Amount={$_REQUEST['Amount']}&Date={$_REQUEST['Date']}&MerchantPara={$_REQUEST['MerchantPara']}&Msg={$_REQUEST['Msg']}&Signature={$_REQUEST['Signature']}";
        $retCode .= strlen($retCode);
        if ( $Security -> checkInfoFromBank($retCode) != 1 ) {
            return 'sign_fail';
        }
        
        if ( $_REQUEST['Succeed'] != 'Y' ){
            return 'pay_fail';
        }
        
        $id = (int)$_REQUEST['BillNo'];
        $paySeries = $this -> _api -> getPaySeries(array('id' => $id));
        if ( !$paySeries ) {
            return 'pay_fail';
        }
        
        $batch_sn = $paySeries['batch_sn'];
        $pay_log_id = $batch_sn.'-'.$paySeries['series_no'];
        $amount = $_REQUEST['Amount'];
        $apiReturnRes = $this -> _api -> update($pay_log_id, $amount, 'bankcmb');
        
        return array('batch_sn' => $batch_sn, 'amount' => $amount, 'return' => $apiReturnRes);
    }
    
    public function query() {
        if ( !parent::query() ) return false;
        
        return true;
    }

}