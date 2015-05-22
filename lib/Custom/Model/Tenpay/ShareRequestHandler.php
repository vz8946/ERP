<?php

/**
 * 共享登录请求类
 * ============================================================================
 * api说明：
 * init(),初始化函数，默认给一些参数赋值，如cmdno,date等。
 * getGateURL()/setGateURL(),获取/设置入口地址,不包含参数值
 * getKey()/setKey(),获取/设置密钥
 * getParameter()/setParameter(),获取/设置参数值
 * getAllParameters(),获取所有参数
 * getRequestURL(),获取带参数的请求URL
 * doSend(),重定向到财付通支付
 * getDebugInfo(),获取debug信息
 * 
 * ============================================================================
 *
 */

include_once 'RequestHandler.php';

class Custom_Model_Tenpay_ShareRequestHandler extends Custom_Model_Tenpay_RequestHandler {
	
	function __construct() {
		$this->MediPayRequestHandler();
	}
	
	function MediPayRequestHandler() {
		//默认支付网关地址
		$this->setGateURL("https://www.tenpay.com/cgi-bin/v1.0/service_gate.cgi");	
	}
	
	/**
	*@Override
	*初始化函数，默认给一些参数赋值。
	*/
	function init() {
		//签名类型
		$this->setParameter("sign_type", "md5");
		
		//密钥索引
		$this->setParameter("sign_encrypt_keyid",  "0");
		
		//编码类型，GBK, UTF-8
		$this->setParameter("input_charset", "GBK");
		
		//服务名称
		$this->setParameter("service", "login");
		
		//财付通合作账户，可以是商户号或财付通账号
		$this->setParameter("chnid", "");
		
		//合作账号类型，0为商户号，1为财付通账号
		$this->setParameter("chtype", "0");
		
		//登录成功后回调url
		$this->setParameter("redirect_url",  "");
		
		//附加参数，回调时原样返回，可以保存业务的url
		$this->setParameter("attach",  "");
		
		//发起请求时的时间戳
		$this->setParameter("tmstamp",  (string)time());
		
	}
}

?>