<?php
$payments['tenpay'] = '财付通';
class Custom_Model_Payment_Tenpay2 extends Custom_Model_Payment_Abstract
{
	public function __construct($batchSN = null,$order_type = 2,$amount=0.00,$business='',$config = array()) {
		parent::__construct($batchSN,$order_type,$amount,$business,$config);
	}	

    function getButton($arg=null,$config) {
    	
		$spname="财付通双接口测试";
		
		$partner = $config['tenpay_account'];                                  	//财付通商户号
		$key = $config['tenpay_key'];											//财付通密钥
		
		$return_url = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/tenpay2/business/money_order/";			//显示支付结果页面,*替换成payReturnUrl.php所在路径
		$notify_url = "http://{$_SERVER['HTTP_HOST']}/payment/sync/pay_type/tenpay2/business/money_order/";			//支付完成后的回调处理页面,*替换成payNotifyUrl.php所在路径
		
		/* 获取提交的订单号 */
		$out_trade_no = $this->order_sn;
		
		/* 获取提交的商品名称 */
		$product_name = $this->order_sn;
		
		/* 商品价格（包含运费），以分为单位 */
		$total_fee = ($this->amount)*100;

		/* 创建支付请求对象 */
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($key);
		$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

		$reqHandler->setParameter("body", $product_name);
		$reqHandler->setParameter("subject",$product_name);          //商品名称，（中介交易时必填）
		$reqHandler->setParameter("return_url", $return_url);
		$reqHandler->setParameter("notify_url", $notify_url);
		$reqHandler->setParameter("partner", $partner); //财付通统一分配的10位正整数(12XXXXXXXX)号
		$reqHandler->setParameter("out_trade_no", $out_trade_no);
		$reqHandler->setParameter("total_fee", $total_fee);  //总金额
		$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//客户端IP
		
		$url = $reqHandler->getRequestURL();
		
		$rs = '<form action="'.$reqHandler->getGateUrl().'" method="post" target="_blank">';
		$params = $reqHandler->getAllParameters();
		
		foreach($params as $k => $v) {
			$rs .= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
		}
		$rs .= '<input type="submit" value="财付通支付">'; 
		$rs .= '</form>';
		
		return $rs;
		
    }

    function respond($business=''){

        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'tenpay')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '系统错误，请联系客服人员手工处理此单！';
            return $this -> returnRes;
        }
		
		/* 创建支付应答对象 */
		$resHandler = new ResponseHandler();
		$resHandler->setKey($payment['tenpay_key']);
		//判断签名
		if($resHandler->isTenpaySign()) {
			
			//通知id
			$notify_id = $resHandler->getParameter("notify_id");
			
			//商户订单号
			$order_sn = $resHandler->getParameter("out_trade_no");
			
			//财付通订单号
			$transaction_id = $resHandler->getParameter("transaction_id");
			
			//金额,以分为单位
			$total_fee = $resHandler->getParameter("total_fee");
			
			//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
			$discount = $resHandler->getParameter("discount");
			
			//支付结果
			$trade_state = $resHandler->getParameter("trade_state");
			
			//交易模式,1即时到账
			$trade_mode = $resHandler->getParameter("trade_mode");
			
			if("1" == $trade_mode ) {
				if( "0" == $trade_state){
					if($this->dopay($business, $order_sn, $total_fee)){
					    $this -> returnRes['stats'] = true;
					    $this -> returnRes['thisPaid'] = $total_fee;
			            $this -> returnRes['msg'] = '即时到帐支付成功！';
					}else{
					    $this -> returnRes['stats'] = true;
			            $this -> returnRes['msg'] = '支付异常！';
					}
				} else {
				    $this -> returnRes['stats'] = false;
		            $this -> returnRes['msg'] = '即时到帐支付失败！';
				}
			}elseif( "2" == $trade_mode  ) {
				if( "0" == $trade_state) {
					if($this->dopay($business, $order_sn, $total_fee)){
					    $this -> returnRes['stats'] = true;
			            $this -> returnRes['msg'] = '中介担保支付成功！';
					}else{
					    $this -> returnRes['stats'] = true;
			            $this -> returnRes['msg'] = '支付异常！';
					}
				} else {
				    $this -> returnRes['stats'] = false;
		            $this -> returnRes['msg'] = '中介担保支付失败！';
				}
			}
		} else {
            $this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = $resHandler->getDebugInfo();
		}
		
        return $this -> returnRes;
    }

	function sync($business=''){

        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'tenpay')));
        $payment = unserialize($payment['config']);
        if (!$payment) {
        	die('fail');
        }
		
		/* 创建支付应答对象 */
		$resHandler = new ResponseHandler();
		$resHandler -> setKey($key);
		
		//判断签名
		if ($resHandler -> isTenpaySign()) {
		
			//通知id
			$notify_id = $resHandler -> getParameter("notify_id");
		
			//通过通知ID查询，确保通知来至财付通
			//创建查询请求
			$queryReq = new RequestHandler();
			$queryReq -> init();
			$queryReq -> setKey($payment['tenpay_key']);
			$queryReq -> setGateUrl("https://gw.tenpay.com/gateway/simpleverifynotifyid.xml");
			$queryReq -> setParameter("partner", $payment['tenpay_account']);
			$queryReq -> setParameter("notify_id", $notify_id);
		
			//通信对象
			$httpClient = new TenpayHttpClient();
			$httpClient -> setTimeOut(5);
			
			//设置请求内容
			$httpClient -> setReqContent($queryReq -> getRequestURL());
		
			//后台调用
			if ($httpClient -> call()) {
				//设置结果参数
				$queryRes = new ClientResponseHandler();
				$queryRes -> setContent($httpClient -> getResContent());
				$queryRes -> setKey($payment['tenpay_key']);
		
				if ($resHandler -> getParameter("trade_mode") == "1") {
					
					//判断签名及结果（即时到帐）
					//只有签名正确,retcode为0，trade_state为0才是支付成功
					if ($queryRes -> isTenpaySign() && $queryRes -> getParameter("retcode") == "0" && $resHandler -> getParameter("trade_state") == "0") {
						
						//取结果参数做业务处理
						$order_sn = $resHandler -> getParameter("out_trade_no");
						
						//财付通订单号
						$transaction_id = $resHandler -> getParameter("transaction_id");
						
						//金额,以分为单位
						$total_fee = $resHandler -> getParameter("total_fee");
						
						//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
						$discount = $resHandler -> getParameter("discount");
						
						//------------------------------
						//处理业务开始
						//------------------------------
						if($this->dopay($business, $order_sn, $total_fee)){
							die('success');
						}
						//------------------------------
						//处理业务完毕
						//------------------------------
						
						echo "fail";
						exit;
		
					} else {
						//错误时，返回结果可能没有签名，写日志trade_state、retcode、retmsg看失败详情。
						//echo "验证签名失败 或 业务错误信息:trade_state=" . $resHandler->getParameter("trade_state") . ",retcode=" . $queryRes->                         getParameter("retcode"). ",retmsg=" . $queryRes->getParameter("retmsg") . "<br/>" ;
						echo "fail";
						exit;
					}
				}
		
			} else {
				//通信失败
				echo "fail";
				exit;
			}
		
		} else {
			echo 'fail';
			exit;
		}
		
		exit;
				
	}

	private function dopay($business,$order_sn,$total_fee,&$rs = array()){
			
		$total_fee = intval($total_fee) / 100;
		
		if($business == 'money_order'){
			//更改订单状态
			$obj_db = new Custom_Model_Dbadv();
			$r_money_order = $obj_db->getRow('shop_money_order',array('order_sn'=>$order_sn));
			
			//判断返回金额
			if($r_money_order['amount'] != $total_fee) return false;
			//交易单不要重复处理
			if($r_money_order['status'] == -1){

				$obj_db->update('shop_money_order', array('status'=>1),array('order_sn'=>$order_sn));
				
				//余额变更				
				$r_money_order = $obj_db->getRow('shop_money_order',array('order_sn'=>$order_sn));
				$r_member = $obj_db->getRow('shop_member',array('member_id'=>$r_money_order['member_id']));
				
				$api_member = new Shop_Models_API_Member();
				$api_member->editAccount($r_money_order['member_id'], 'money', array(
					'accountValue'=>$total_fee,
					'accountType'=>'1',
					'note'=>'用户通过财付通充值'.$total_fee
				));
                $rs['stats'] = true;
                $rs['thisPaid'] = $total_fee;
				return true;		
			}else{
				return false;
			}
			
		}else{
            /* 改变订单状态 */
            $apiReturnRes = $this -> _api -> update($order_sn, $total_fee, 'tenpay');
            if ($apiReturnRes['result']) {
                $rs['stats'] = true;
                $rs['thisPaid'] = $total_fee;
                $rs['difference'] = $apiReturnRes['remainder'];//差额
                return true;
            } else {
            	return false;
            }
		}		
		
	}
}

class ResponseHandler  {
	
	/** 密钥 */
	var $key;
	
	/** 应答的参数 */
	var $parameters;
	
	/** debug信息 */
	var $debugInfo;
	
	function __construct() {
		$this->ResponseHandler();
	}
	
	function ResponseHandler() {
		$this->key = "";
		$this->parameters = array();
		$this->debugInfo = "";
		
		/* GET */
		foreach($_GET as $k => $v) {
			$this->setParameter($k, $v);
		}
		/* POST */
		foreach($_POST as $k => $v) {
			$this->setParameter($k, $v);
		}
	}
		
	/**
	*获取密钥
	*/
	function getKey() {
		return $this->key;
	}
	
	/**
	*设置密钥
	*/	
	function setKey($key) {
		$this->key = $key;
	}
	
	/**
	*获取参数值
	*/	
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	
	/**
	*设置参数值
	*/	
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
	}
	
	/**
	*获取所有请求的参数
	*@return array
	*/
	function getAllParameters() {
		return $this->parameters;
	}	
	
	/**
	*是否财付通签名,规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
	*true:是
	*false:否
	*/	
	function isTenpaySign() {
		$signPars = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v) {
			if("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->getKey();
		
		$sign = strtolower(md5($signPars));
		
		$tenpaySign = strtolower($this->getParameter("sign"));
				
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;
		
	}
	
	/**
	*获取debug信息
	*/	
	function getDebugInfo() {
		return $this->debugInfo;
	}
	
	/**
	*显示处理结果。
	*@param $show_url 显示处理结果的url地址,绝对url地址的形式(http://www.xxx.com/xxx.php)。
	*/	
	function doShow($show_url) {
		$strHtml = "<html><head>\r\n" .
			"<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">" .
			"<script language=\"javascript\">\r\n" .
				"window.location.href='" . $show_url . "';\r\n" .
			"</script>\r\n" .
			"</head><body></body></html>";
			
		echo $strHtml;
		
		exit;
	}
	
	/**
	 * 是否财付通签名
	 * @param signParameterArray 签名的参数数组
	 * @return boolean
	 */	
	function _isTenpaySign($signParameterArray) {
	
		$signPars = "";
		foreach($signParameterArray as $k) {
			$v = $this->getParameter($k);
			if("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}			
		}
		$signPars .= "key=" . $this->getKey();
		
		$sign = strtolower(md5($signPars));
		
		$tenpaySign = strtolower($this->getParameter("sign"));
				
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;		
		
	
	}
	
	/**
	*设置debug信息
	*/	
	function _setDebugInfo($debugInfo) {
		$this->debugInfo = $debugInfo;
	}
	
}

// 请注意服务器是否开通fopen配置
function  log_result($word) {
    $fp = fopen("log.txt","a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}


/**
 * 请求类
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
class RequestHandler {
	
	/** 网关url地址 */
	var $gateUrl;
	
	/** 密钥 */
	var $key;
	
	/** 请求的参数 */
	var $parameters;
	
	/** debug信息 */
	var $debugInfo;
	
	function __construct() {
		$this->RequestHandler();
	}
	
	function RequestHandler() {
		$this->gateUrl = "https://www.tenpay.com/cgi-bin/v1.0/service_gate.cgi";
		$this->key = "";
		$this->parameters = array();
		$this->debugInfo = "";
	}
	
	/**
	*初始化函数。
	*/
	function init() {
		//nothing to do
	}
	
	/**
	*获取入口地址,不包含参数值
	*/
	function getGateURL() {
		return $this->gateUrl;
	}
	
	/**
	*设置入口地址,不包含参数值
	*/
	function setGateURL($gateUrl) {
		$this->gateUrl = $gateUrl;
	}
	
	/**
	*获取密钥
	*/
	function getKey() {
		return $this->key;
	}
	
	/**
	*设置密钥
	*/
	function setKey($key) {
		$this->key = $key;
	}
	
	/**
	*获取参数值
	*/
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	
	/**
	*设置参数值
	*/
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
	}
	
	/**
	*获取所有请求的参数
	*@return array
	*/
	function getAllParameters() {
		return $this->parameters;
	}
	
	/**
	*获取带参数的请求URL
	*/
	function getRequestURL() {
	
		$this->createSign();
		
		$reqPar = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v) {
			$reqPar .= $k . "=" . urlencode($v) . "&";
		}
		
		//去掉最后一个&
		$reqPar = substr($reqPar, 0, strlen($reqPar)-1);
		
		$requestURL = $this->getGateURL() . "?" . $reqPar;
		
		return $requestURL;
		
	}
		
	/**
	*获取debug信息
	*/
	function getDebugInfo() {
		return $this->debugInfo;
	}
	
	/**
	*重定向到财付通支付
	*/
	function doSend() {
		header("Location:" . $this->getRequestURL());
		exit;
	}
	
	/**
	*创建md5摘要,规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
	*/
	function createSign() {
		$signPars = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v) {
			if("" != $v && "sign" != $k) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->getKey();
		$sign = strtolower(md5($signPars));
		$this->setParameter("sign", $sign);
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign);
	}	
	
	/**
	*设置debug信息
	*/
	function _setDebugInfo($debugInfo) {
		$this->debugInfo = $debugInfo;
	}
}


/**
 * 后台应答类
 * ============================================================================
 * api说明：
 * getKey()/setKey(),获取/设置密钥
 * getContent() / setContent(), 获取/设置原始内容
 * getParameter()/setParameter(),获取/设置参数值
 * getAllParameters(),获取所有参数
 * isTenpaySign(),是否财付通签名,true:是 false:否
 * getDebugInfo(),获取debug信息
 * 
 * ============================================================================
 *
 */

class ClientResponseHandler  {
	
	/** 密钥 */
	var $key;
	
	/** 应答的参数 */
	var $parameters;
	
	/** debug信息 */
	var $debugInfo;
	
	//原始内容
	var $content;
	
	function __construct() {
		$this->ClientResponseHandler();
	}
	
	function ClientResponseHandler() {
		$this->key = "";
		$this->parameters = array();
		$this->debugInfo = "";
		$this->content = "";
	}
		
	/**
	*获取密钥
	*/
	function getKey() {
		return $this->key;
	}
	
	/**
	*设置密钥
	*/	
	function setKey($key) {
		$this->key = $key;
	}
	
	//设置原始内容，确保PHP环境支持simplexml_load_string以及iconv这两个函数才可以
	//一般PHP5环境下没问题，PHP4需要检测一下环境是否安装了iconv以及simplexml模块
	function setContent($content) {
		$this->content = $content;
		
		$xml = simplexml_load_string($this->content);
		$encode = $this->getXmlEncode($this->content);
		
		if($xml && $xml->children()) {
			foreach ($xml->children() as $node){
				//有子节点
				if($node->children()) {
					$k = $node->getName();
					$nodeXml = $node->asXML();
					$v = substr($nodeXml, strlen($k)+2, strlen($nodeXml)-2*strlen($k)-5);
					
				} else {
					$k = $node->getName();
					$v = (string)$node;
				}
				
				if($encode!="" && $encode != "UTF-8") {
					$k = iconv("UTF-8", $encode, $k);
					$v = iconv("UTF-8", $encode, $v);
				}
				
				$this->setParameter($k, $v);			
			}
		}	
	}
	
	//设置原始内容
	//解决PHP4老环境下不支持simplexml以及iconv功能的函数
	function setContent_backup($content) {
		$this->content = $content;
		$encode = $this->getXmlEncode($this->content);
		$xml = new SofeeXmlParser(); 
		$xml->parseFile($this->content); 
		$tree = $xml->getTree(); 
		unset($xml); 
		foreach ($tree['root'] as $key => $value) {
			if($encode!="" && $encode != "UTF-8") {
				$k = mb_convert_encoding($key, $encode, "UTF-8");
				$v = mb_convert_encoding($value[value], $encode, "UTF-8");								
			}
			else 
			{
				$k = $key;
				$v = $value[value];
			}
			$this->setParameter($k, $v);
		}
	}
	
	
	
	//获取原始内容
	function getContent() {
		return $this->content;
	}
	
	/**
	*获取参数值
	*/	
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	
	/**
	*设置参数值
	*/	
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
	}
	
	/**
	*获取所有请求的参数
	*@return array
	*/
	function getAllParameters() {
		return $this->parameters;
	}	
	
	/**
	*是否财付通签名,规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
	*true:是
	*false:否
	*/	
	function isTenpaySign() {
		$signPars = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v) {
			if("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->getKey();
		
		$sign = strtolower(md5($signPars));
		
		$tenpaySign = strtolower($this->getParameter("sign"));
				
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;
		
	}
	
	/**
	*获取debug信息
	*/	
	function getDebugInfo() {
		return $this->debugInfo;
	}
	
	//获取xml编码
	function getXmlEncode($xml) {
		$ret = preg_match ("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
		if($ret) {
			return strtoupper ( $arr[1] );
		} else {
			return "";
		}
	}
	
	/**
	*设置debug信息
	*/	
	function _setDebugInfo($debugInfo) {
		$this->debugInfo = $debugInfo;
	}
	
	/**
	 * 是否财付通签名
	 * @param signParameterArray 签名的参数数组
	 * @return boolean
	 */	
	function _isTenpaySign($signParameterArray) {
	
		$signPars = "";
		foreach($signParameterArray as $k) {
			$v = $this->getParameter($k);
			if("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}			
		}
		$signPars .= "key=" . $this->getKey();
		
		$sign = strtolower(md5($signPars));
		
		$tenpaySign = strtolower($this->getParameter("sign"));
				
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;		
		
	
	}
	
}

/**
 * http、https通信类
 * ============================================================================
 * api说明：
 * setReqContent($reqContent),设置请求内容，无论post和get，都用get方式提供
 * getResContent(), 获取应答内容
 * setMethod($method),设置请求方法,post或者get
 * getErrInfo(),获取错误信息
 * setCertInfo($certFile, $certPasswd, $certType="PEM"),设置证书，双向https时需要使用
 * setCaInfo($caFile), 设置CA，格式未pem，不设置则不检查
 * setTimeOut($timeOut)， 设置超时时间，单位秒
 * getResponseCode(), 取返回的http状态码
 * call(),真正调用接口
 * 
 * ============================================================================
 *
 */

class TenpayHttpClient {
	//请求内容，无论post和get，都用get方式提供
	var $reqContent;
	//应答内容
	var $resContent;
	//请求方法
	var $method;
	
	//证书文件
	var $certFile;
	//证书密码
	var $certPasswd;
	//证书类型PEM
	var	$certType;
	
	//CA文件
	var $caFile;
	
	//错误信息
	var $errInfo;
	
	//超时时间
	var $timeOut;
	
	//http状态码
	var $responseCode;
	
	function __construct() {
		$this->TenpayHttpClient();
	}
	
	function TenpayHttpClient() {
		$this->reqContent = "";
		$this->resContent = "";
		$this->method = "post";

		$this->certFile = "";
		$this->certPasswd = "";
		$this->certType = "PEM";
		
		$this->caFile = "";
		
		$this->errInfo = "";
		
		$this->timeOut = 120;
		
		$this->responseCode = 0;
		
	}
	
	
	//设置请求内容
	function setReqContent($reqContent) {
		$this->reqContent = $reqContent;
	}
	
	//获取结果内容
	function getResContent() {
		return $this->resContent;
	}
	
	//设置请求方法post或者get	
	function setMethod($method) {
		$this->method = $method;
	}
	
	//获取错误信息
	function getErrInfo() {
		return $this->errInfo;
	}
	
	//设置证书信息
	function setCertInfo($certFile, $certPasswd, $certType="PEM") {
		$this->certFile = $certFile;
		$this->certPasswd = $certPasswd;
		$this->certType = $certType;
	}
	
	//设置Ca
	function setCaInfo($caFile) {
		$this->caFile = $caFile;
	}
	
	//设置超时时间,单位秒
	function setTimeOut($timeOut) {
		$this->timeOut = $timeOut;
	}
	
	//执行http调用
	function call() {
		//启动一个CURL会话
		$ch = curl_init();

		// 设置curl允许执行的最长秒数
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeOut);

		// 获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

		// 从证书中检查SSL加密算法是否存在
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
				
		
		$arr = explode("?", $this->reqContent);
		if(count($arr) >= 2 && $this->method == "post") {
			//发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_URL, $arr[0]);
			//要传送的所有数据
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arr[1]);
		
		}else{
			curl_setopt($ch, CURLOPT_URL, $this->reqContent);
		}
		
		//设置证书信息
		if($this->certFile != "") {
			curl_setopt($ch, CURLOPT_SSLCERT, $this->certFile);
			curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->certPasswd);
			curl_setopt($ch, CURLOPT_SSLCERTTYPE, $this->certType);
		}
		
		//设置CA
		if($this->caFile != "") {
			// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_CAINFO, $this->caFile);
		} else {
			// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		
		// 执行操作
		$res = curl_exec($ch);
		$this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if ($res == NULL) { 
		   $this->errInfo = "call http err :" . curl_errno($ch) . " - " . curl_error($ch) ;
		   curl_close($ch);
		   return false;
		} else if($this->responseCode  != "200") {
			$this->errInfo = "call http err httpcode=" . $this->responseCode  ;
			curl_close($ch);
			return false;
		}
		
		curl_close($ch);
		$this->resContent = $res;

		
		return true;
	}
	
	function getResponseCode() {
		return $this->responseCode;
	}
	
}
