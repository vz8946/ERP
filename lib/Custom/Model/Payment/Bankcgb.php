<?php

require_once("../lib/Bank/spdb/java/Java.inc");

$payments['bankcgb'] = '直连-广发银行';
class Custom_Model_Payment_Bankcgb extends Custom_Model_Payment_Abstract
{
    public $payType = 'bankcgb';
    public $payTypeName = '广发银行';
    
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
        $html .= '</td></tr></table>';

        return $html;
    }
    
    function auth() {
        
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankcgb')));
        $payment = unserialize($payment['config']);
        
        $seriesNo = $this -> _api -> createPaySeries($this->payType, $order['batch_sn'], $_POST['amount']);
        
        java_set_library_path($payment['jar_path']);
        
        $PayCfg = new Java('gfbank.payment.merchant.PayCfg');
        $SignVerify = new Java('gfbank.payment.merchant.SignAndVerify');

        $returl = $PayCfg -> getValue('merchantreturl');
        
        $merchid = $PayCfg -> getValue('merchid');
        $orderid = $_POST['batch_sn'].'-'.$seriesNo;
        $amount = $_POST['amount'];
        $sign =  $SignVerify -> sign_md( $merchid.$orderid.$amount, '' );
        
        $def_url = "<input type=hidden name=merchid value=\"{$merchid}\">\n
                    <input type=hidden name=orderid value=\"{$orderid}\">\n
                    <input type=hidden name=amount value=\"{$amount}\">\n
                    <input type=hidden name=sign value=\"{$sign}\">\n
                    <input type=hidden name=returl value=\"{$returl}\">\n";
        
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
        $this -> writeLog(null, 'bankcgb');
        
        if ( is_array($this -> getRespond()) ) {
            echo 'success';
        }
        else    echo 'error';
        
        exit;
    }
    
    private function getRespond() {
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankcgb')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            return 'error';
        }
        
        java_set_library_path($payment['jar_path']);
        
        $SignVerify = new Java('gfbank.payment.merchant.SignAndVerify');
        
        $amount = $_REQUEST["amount"];
        $orderid = $_REQUEST["orderid"];
        $sequence = $_REQUEST["sequence"];
        $orderdate = $_REQUEST["orderdate"];
        $retcode = $_REQUEST["success"];
        $merchid = $_REQUEST["merchid"];
        $crypt = $_REQUEST["crypt"];
        $signdata = $orderid.$amount.$orderdate.$retcode.$sequence.$merchid;

        $status = $SignVerify -> verify_md( $signdata, $crypt, '' );
        
        if ( $status != '0' ) {
            return 'sign_fail';
        }
        
        if ( $retcode != 'RC000' ) {
            return 'pay_fail';
        }
        
        /*
        $PayCfg = new Java('gfbank.payment.merchant.PayCfg');
        $lockFilename = $PayCfg -> getValue('orderid_path').'/'.$orderid;
        if ( file_exists($lockFilename) ) {
            @unlink($lockFilename);
        }
        */
        
        $temparr = explode('-', $orderid);
        $batch_sn = $temparr[0];
        $pay_log_id = $batch_sn.'-'.$temparr[1];
        $apiReturnRes = $this -> _api -> update($pay_log_id, $amount, 'bankcgb');
        
        return array('batch_sn' => $batch_sn, 'amount' => $amount, 'return' => $apiReturnRes);
    }
    
    public function query() {
        if ( !parent::query() ) return false;
        
        return true;
    }

}