<?php

require_once("../lib/Bank/spdb/java/Java.inc");

$payments['bankccb'] = '直连-建设银行';
class Custom_Model_Payment_Bankccb extends Custom_Model_Payment_Abstract
{
    public $payType = 'bankccb';
    public $payTypeName = '建设银行';
    
    function getFields($config=null){
        $html .= '<table><tr><th width="150">';
        $html .= '商户编号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][yp_account]" value="'.$config['yp_account'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '分行代码';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][branchid]" value="'.$config['branchid'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '柜台号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][posid]" value="'.$config['posid'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= 'jar文件路径';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][jar_path]" value="'.$config['jar_path'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '支付网关';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][pay_url]" value="'.$config['pay_url'].'" style="width:300px">';
        $html .= '</td></tr></table>';
        
        return $html;
    }
    
    function auth() {
        
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankccb')));
        $payment = unserialize($payment['config']);
        
        $seriesNo = $this -> _api -> createPaySeries($this->payType, $order['batch_sn'], $_POST['amount']);
        
        $MERCHANTID = $payment['yp_account'];
        $POSID = $payment['posid'];
        $BRANCHID = $payment['branchid'];
        $ORDERID = $_POST['batch_sn'].'-'.$seriesNo;
        $PAYMENT = $_POST['amount'];
        $CURCODE = '01';
        $TXCODE = '520100';
        $REMARK1 = '';
        $REMARK2 = '';
        
        $src = "MERCHANTID={$MERCHANTID}&POSID={$POSID}&BRANCHID={$BRANCHID}&ORDERID={$ORDERID}&PAYMENT={$PAYMENT}&CURCODE={$CURCODE}&TXCODE={$TXCODE}&REMARK1={$REMARK1}&REMARK2={$REMARK2}";
        $sign = md5($src);
        
        $def_url = "<input type=hidden name=MERCHANTID value=\"{$MERCHANTID}\">\n
                    <input type=hidden name=POSID value=\"{$POSID}\">\n
                    <input type=hidden name=BRANCHID value=\"{$BRANCHID}\">\n
                    <input type=hidden name=ORDERID value=\"{$ORDERID}\">\n
                    <input type=hidden name=PAYMENT value=\"{$PAYMENT}\">\n
                    <input type=hidden name=CURCODE value=\"{$CURCODE}\">\n
                    <input type=hidden name=TXCODE value=\"{$TXCODE}\">\n
                    <input type=hidden name=MAC value=\"{$sign}\">\n
                    <input type=hidden name=REMARK1 value=\"{$REMARK1}\">\n
                    <input type=hidden name=REMARK2 value=\"{$REMARK2}\">\n";
        
        echo $def_url;
    }
    
    function respond() {
        $respond = $this -> getRespond(true);
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
        $this -> writeLog(null, 'bankccb');
        
        if ( is_array($this -> getRespond(false)) ) {
            echo 'success';
        }
        else    echo 'error';
        
        exit;
    }
    
    private function getRespond($isPageRedirect = true) {
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankccb')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            return 'error';
        }
        
        java_set_library_path($payment['jar_path']);
        
        $RSASig = new Java('CCBSign.RSASig');
        $RSASig -> setPublicKey('30819d300d06092a864886f70d010101050003818b00308187028181008e3ccc6cd9d66f6f45dc995f0515c1df19bfc4196a4dfc0bc0ff833d33836abc12b4071ea264c8ec885a8334852c6185bd4ee3771801ad9bddecbcbe4742f79f89d0e3267014c50e5a877f1bc8f9aa5a2b94c5d5cf3d1618084ed04946ce67777e9482bd037bc0c4c35b791c0159a5ffeec94e48b466285d94de593df19dd403020111');
        
        $POSID = $_REQUEST["POSID"];
        $BRANCHID = $_REQUEST["BRANCHID"];
        $ORDERID = $_REQUEST["ORDERID"];
        $PAYMENT = $_REQUEST["PAYMENT"];
        $CURCODE = $_REQUEST["CURCODE"];
        $REMARK1 = $_REQUEST["REMARK1"];
        $REMARK2 = $_REQUEST["REMARK2"];
        $SUCCESS = $_REQUEST["SUCCESS"];
        if ( $isPageRedirect ) {
            $src = "POSID={$POSID}&BRANCHID={$BRANCHID}&ORDERID={$ORDERID}&PAYMENT={$PAYMENT}&CURCODE={$CURCODE}&REMARK1={$REMARK1}&REMARK2={$REMARK2}&SUCCESS={$SUCCESS}";
        }
        else {
            $ACC_TYPE = $_REQUEST["ACC_TYPE"];
            $src = "POSID={$POSID}&BRANCHID={$BRANCHID}&ORDERID={$ORDERID}&PAYMENT={$PAYMENT}&CURCODE={$CURCODE}&REMARK1={$REMARK1}&REMARK2={$REMARK2}&ACC_TYPE={$ACC_TYPE}&SUCCESS={$SUCCESS}";
        }
        
        $veriyCode = $RSASig -> verifySigature($_REQUEST["SIGN"], $src);
        if ($veriyCode != 1) {
            return 'sign_fail';
        }
        
        if ( $SUCCESS != 'Y' ) {
            return 'pay_fail';
        }
        
        $temparr = explode('-', $ORDERID);
        $batch_sn = $temparr[0];
        $pay_log_id = $batch_sn.'-'.$temparr[1];
        $amount = $PAYMENT;
        $apiReturnRes = $this -> _api -> update($pay_log_id, $amount, 'bankccb');
        
        return array('batch_sn' => $batch_sn, 'amount' => $amount, 'return' => $apiReturnRes);
    }
    
    public function query() {
        if ( !parent::query() ) return false;
        
        return true;
    }

}