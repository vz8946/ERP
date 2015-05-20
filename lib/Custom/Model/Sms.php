<?php
define('SELF_PATH', str_replace('Sms.php', '', str_replace('\\', '/', __FILE__)).'Sms/');
require_once SELF_PATH.'config.php';
require_once SELF_PATH."function.php";
require_once SELF_PATH."Client.php";

class Custom_Model_Sms
{
    private $clt;
    private $smsInfo = array();
    
	public function __construct() {
	    
	    set_time_limit(0);
        global $username,$password;
        $this->smsInfo['userId'] = $username;
        $this->smsInfo['password'] = $password;
        
        global $soapurl;
        $this->clt = new Client($soapurl, 0);
	}
	
	/**
	 * 发送
	 * @param String(Array) $tos
	 * @param String $msg
	 */
	public function send($tos,$msg){
	    if(is_string($tos)) $tos = explode(',', $tos);
	    $this->smsInfo['pszMsg'] = $msg;
	    $this->smsInfo['pszSubPort'] = '*';
	    
	    return $this->clt->sendSMS($this->smsInfo, $tos);
	}
	
	/**
	 * 群发不同内容的短信
	 * @param array $group
	 */
	public function sendGroupDiff($group=array()){
	    $multixmt = '';
	    $arr_sms = array();
	    foreach ($group as $k=>$v){
	        $arr_sms[] = '0|*|'.$k.'|'.base64_encode(iconv('UTF-8', 'GB2312', $v));
	    }
	    $multixmt = implode(',', $arr_sms);
	    $this->smsInfo['multixmt'] = $multixmt;
	    global $defhandle;
	    $defhandle=4;
	    
	    return $this->clt->sendMultDiff($this->smsInfo);
	     
	}
	
    	
    
	
	
}