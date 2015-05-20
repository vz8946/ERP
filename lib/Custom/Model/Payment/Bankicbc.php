<?php

require_once("../lib/Bank/spdb/java/Java.inc");

$payments['bankicbc'] = '直连-工商银行';
class Custom_Model_Payment_Bankicbc extends Custom_Model_Payment_Abstract
{
    public $payType = 'bankicbc';
    public $payTypeName = '工商银行';
    
    function getFields($config=null){
        $html .= '<table><tr><th width="150">';
        $html .= '商户ID';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][yp_account]" value="'.$config['yp_account'].'">';
        $html .= '</td></tr><tr><th>';
         $html .= '商户账号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][mer_acct]" value="'.$config['mer_acct'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '证书公钥文件路径';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][cer_crt_path]" value="'.$config['cer_crt_path'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '证书密钥文件路径';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][cer_key_path]" value="'.$config['cer_key_path'].'">';
        $html .= '</td></tr><tr><th>';
        $html .= '证书密钥密码';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][cer_password]" value="'.$config['cer_password'].'">';
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
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankicbc')));
        $payment = unserialize($payment['config']);
        
        $seriesNo = $this -> _api -> createPaySeries($this->payType, $order['batch_sn'], $_POST['amount']);
        
        java_set_library_path($payment['jar_path']);
        $Signature = new Java('Signature');
        
        $interfaceName = 'ICBC_PERBANK_B2C';
        $interfaceVersion = '1.0.0.11';
        $orderDate = date('YmdHis');
        $orderid = $_POST['batch_sn'].'-'.$seriesNo;
        $amount = $_POST['amount'] * 100;
        $installmentTimes = 1;
        $merAcct = $payment['mer_acct'];
        $goodsID = $_POST['batch_sn'];
        $goodsName = 'Food';
        $goodsNum = '1';
        $carriageAmt = '0';
        $verifyJoinFlag = 0;
        $Language = 'ZH_CN';
        $curType = '001';
        $merID = $payment['yp_account'];
        $creditType = 2;
        $notifyType = 'HS';
        $resultType = 1;
        $merReference = '';
        $goodsType = '1';
        $merCustomID = '';
        $merCustomPhone = '';
        $goodsAddress = '';
        $merOrderRemark = '';
        $merHint = '';
        $remark1 = '';
        $remark2 = '';
        $merURL = 'http://www.1jiankang.com/payment/sync/pay_type/bankicbc';
        $merVAR = '';
        if (getenv("HTTP_CLIENT_IP"))               $merCustomIp = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR"))    $merCustomIp = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR"))             $merCustomIp = getenv("REMOTE_ADDR");
        if ($merCustomIp == '127.0.0.1')            $merCustomIp = '27.115.98.171'; //本地测试
        
        $tranData = "<?xml version=\"1.0\" encoding=\"GBK\" standalone=\"no\"?><B2CReq><interfaceName>{$interfaceName}</interfaceName><interfaceVersion>{$interfaceVersion}</interfaceVersion><orderInfo><orderDate>{$orderDate}</orderDate><curType>{$curType}</curType><merID>{$merID}</merID><subOrderInfoList><subOrderInfo><orderid>{$orderid}</orderid><amount>{$amount}</amount><installmentTimes>{$installmentTimes}</installmentTimes><merAcct>{$merAcct}</merAcct><goodsID>{$goodsID}</goodsID><goodsName>{$goodsName}</goodsName><goodsNum>{$goodsNum}</goodsNum><carriageAmt>{$carriageAmt}</carriageAmt></subOrderInfo></subOrderInfoList></orderInfo><custom><verifyJoinFlag>{$verifyJoinFlag}</verifyJoinFlag><Language>{$Language}</Language></custom><message><creditType>{$creditType}</creditType><notifyType>{$notifyType}</notifyType><resultType>{$resultType}</resultType><merReference>{$merReference}</merReference><merCustomIp>{$merCustomIp}</merCustomIp><goodsType>{$goodsType}</goodsType><merCustomID>{$merCustomID}</merCustomID><merCustomPhone>{$merCustomPhone}</merCustomPhone><goodsAddress>{$goodsAddress}</goodsAddress><merOrderRemark>{$merOrderRemark}</merOrderRemark><merHint>{$merHint}</merHint><remark1>{$remark1}</remark1><remark2>{$remark2}</remark2><merURL>{$merURL}</merURL><merVAR>{$merVAR}</merVAR></message></B2CReq>";
        
        $merSignMsg = $Signature -> sign($tranData, $payment['cer_key_path'], $payment['cer_password']);
        
        $tranData = base64_encode($tranData);
        
        $merCert = base64_encode(file_get_contents($payment['cer_crt_path']));
        
        $def_url = "<input type=\"hidden\" name=\"interfaceName\" type=\"text\" value=\"{$interfaceName}\" >
                    <input type=\"hidden\" name=\"interfaceVersion\" type=\"text\" value=\"{$interfaceVersion}\" >
                    <input type=\"hidden\" name=\"tranData\" type=\"text\" value=\"{$tranData}\" >
                    <input type=\"hidden\" name=\"merSignMsg\" type=\"text\" value=\"{$merSignMsg}\" >
                    <input type=\"hidden\" name=\"merCert\" type=\"text\" value=\"{$merCert}\" >";
        
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
        $this -> writeLog(null, 'bankicbc');
        
        if ( is_array($this -> getRespond()) ) {
            echo 'http://www.1jiankang.com/member/order';
        }
        else    echo 'error';
        
        exit;
    }
    
    private function getRespond() {
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'bankicbc')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            return 'error';
        }
        
        java_set_library_path($payment['jar_path']);
        $Signature = new Java('Signature');
        
        $notifyData = base64_decode($_REQUEST["notifyData"]);
        //$veriyCode = $Signature -> verifySign($_REQUEST["signMsg"], $notifyData, $payment['cer_crt_path']);
        //if ($veriyCode != 1) {
            //return 'sign_fail';
        //}
        
        $xml = new Zend_Config_Xml($notifyData);
        $notifyData = $xml -> toArray();
        
        if ( $notifyData['bank']['tranStat'] != 1 ) {
            return 'pay_fail';
        }
        
        $orderID = $notifyData['orderInfo']['subOrderInfoList']['subOrderInfo']['orderid'];
        $amount = $notifyData['orderInfo']['subOrderInfoList']['subOrderInfo']['amount'] / 100;
        
        $temparr = explode('-', $orderID);
        $batch_sn = $temparr[0];
        $pay_log_id = $batch_sn.'-'.$temparr[1];
        $apiReturnRes = $this -> _api -> update($pay_log_id, $amount, 'bankicbc');
        
        return array('batch_sn' => $batch_sn, 'amount' => $amount, 'return' => $apiReturnRes);
    }
    
    public function query() {
        if ( !parent::query() ) return false;
        
        return true;
    }

}