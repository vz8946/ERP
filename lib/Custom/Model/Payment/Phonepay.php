<?php
$payments['phonepay'] = '手机支付';
class Custom_Model_Payment_Phonepay extends Custom_Model_Payment_Abstract
{
    private $partner;
    private $securityCode;
    private $signType;
    private $mysign;
    private $_input_charset;
    private $transport;

    public function getFields($config=null)
    {
        $html .= '<table><tr><th width="150">';
        $html .= '商家编号';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][merchant_id]" value="' . $config['merchant_id'] . '">';
        $html .= '</td></tr><tr><th>';
        $html .= '密钥';
        $html .= '</th><td>';
        $html .= '<input type="input" name="payment[config][sign_key]" value="' . $config['sign_key'] . '">';
        $html .= '</td></tr></table>';
        return $html;
    }

    public function getCode($arg=null)
    {
        $time = $this -> time;
        $order = $this -> order;
        $payment = $this -> payment;
         
      if ($arg['pay_hidden'] == false) {
            $type = '';
        } else {
            $type = 'none';
        }
         
        if ($arg['target'] == true) {
        	$target = 'target="_blank"';
        } else {
        	$target = '';
        }
        
        $payAmount = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
        $button ='<form action="/payment/auth/pay_type/phonepay/" method="post" '.$target.'>      
        <div style="display:'.$type.';">请确认您的支付金额 <input type="text" size="5" name="amount" readonly value="'.$payAmount.'"></div>    		
        <input type="hidden" value="'.$order['batch_sn'].'" name="batch_sn" />
       	<input type="submit" class="buttons4" value="支付订单"/>
        </form>';        

        return $button;
    }

    /**
     * 手机支付返回数据处理函数
     *
     *
     * @return mix
     */
    public function respond()
    {
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'phonepay')));
        $payment = unserialize($payment['config']);
		//接收手机支付平台页面通知传递的报文 start
		$merchantId 	  = $_POST["merchantId"];
	    $payNo 	  		  = $_POST["payNo"];
	    $returnCode 	  = $_POST["returnCode"];
	    $message	  	  = $_POST["message"];
	    $signType      	  = $_POST["signType"];
	    $type         	  = $_POST["type"];
	    $version          = $_POST["version"];
	    $amount           = $_POST["amount"];
		$amtItem		  = $_POST["amtItem"];
        $bankAbbr	  	  = $_POST["bankAbbr"];
        $mobile 		  = $_POST["mobile"];
        $orderId		  = $_POST["orderId"];
        $payDate		  = $_POST["payDate"];
		$accountDate      = $_POST["accountDate"];
        $reserved1	  	  = $_POST["reserved1"];
		$reserved2	  	  = $_POST["reserved2"];
        $status			  = $_POST["status"];
		$orderDate        = $_POST["orderDate"];
		$fee              = $_POST["fee"];
        $vhmac			  = $_POST["hmac"];
        $signKey          = $payment['sign_key'];
		//接收手机支付平台页面通知传递的报文 end
		
        $this->returnRes['order_sn'] = $orderId;
		//组装签字符串
		$signData = $merchantId .$payNo.$returnCode .$message
               .$signType   .$type        .$version    .$amount
               .$amtItem    .$bankAbbr    .$mobile     .$orderId
               .$payDate    .$accountDate .$reserved1  .$reserved2
               .$status     .$orderDate   .$fee;
		//MD5方式签名
    	$hmac=$this->MD5sign($signKey,$signData);

		if($returnCode!=000000 || $hmac != $vhmac)
		{
			$this -> returnRes['stats'] = false;
            $this -> returnRes['msg'] = '支付失败[签名认证失败]';
		}else{
			$apiReturnRes = $this -> _api -> update($orderId, $amount/100, 'phonepay');
			if ($apiReturnRes['result']) {
                $this -> returnRes['stats'] = true;
                $this -> returnRes['thisPaid'] = $amount/100;
                $this -> returnRes['difference'] = $apiReturnRes['remainder'];//差额
                $this -> returnRes['msg'] = $status;
            } else {
                $this -> returnRes['stats'] = false;
                $this -> returnRes['msg'] = $apiReturnRes['msg'] != '' ? $apiReturnRes['msg'] : '系统执行错误，请联系客服人员手工处理此单！';
            }
		}
        return $this -> returnRes;
    }

    public function sync()
    {
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'phonepay')));
        $payment = unserialize($payment['config']);
        $this->writeLog('phonepay');
        //接收手机支付平台后台通知数据start
	    $merchantId 	 = $_POST["merchantId"];
		$payNo 	  	 	 = $_POST["payNo"];
		$returnCode 	 = $_POST["returnCode"];
		$message	  	 = $_POST["message"];
		$signType    	 = $_POST["signType"];
		$type            = $_POST["type"];
		$version         = $_POST["version"];
		$amount          = $_POST["amount"];
		$amtItem		 = $_POST["amtItem"];
	    $bankAbbr	  	 = $_POST["bankAbbr"];
	    $mobile 		 = $_POST["mobile"];
	    $orderId		 = $_POST["orderId"];
	    $payDate		 = $_POST["payDate"];
		$accountDate     = $_POST["accountDate"];
	    $reserved1	     = $_POST["reserved1"];
		$reserved2	 	 = $_POST["reserved2"];
	    $status		 	 = $_POST["status"];
		$payType         = $_POST["payType"];
		$orderDate       = $_POST["orderDate"];
		$fee             = $_POST["fee"];
	    $vhmac		     = $_POST["hmac"];
		//接收手机支付平台后台通知数据end
    	$signKey        = $payment['sign_key'];
		if($returnCode!=000000)
		{
        //此处表示后台通知产生错误
			  echo $returnCode.$this->decodeUtf8($message);
			  exit();
		}
		$signData = $merchantId .$payNo       .$returnCode .$message
               .$signType   .$type        .$version    .$amount
               .$amtItem    .$bankAbbr    .$mobile     .$orderId
               .$payDate    .$accountDate .$reserved1  .$reserved2
               .$status     .$orderDate  .$fee;
		$hmac=$this->MD5sign($signKey,$signData);
		if($hmac != $vhmac)
		  //此处无法信息数据来自手机支付平台
			echo "验签失败";
		else{
			//商户在此处做业务处理，处理完毕必须响应SUCCESS
			$this -> _api -> update($orderId, $amount/100, 'phonepay');
			echo "SUCCESS";
			exit;
		}
    }

    public function auth()
    {
        $time = time();
        $order = array_shift($this -> _api -> getOrderBatch(array('batch_sn' => $_POST['batch_sn'])));
        $payment = array_shift($this -> _api -> getPayment(array('pay_type' => 'phonepay')));
        $payment = unserialize($payment['config']);

       	//组织提交参数
       	$type         = "DirectPayConfirm";
       	$reqUrl       = "https://ipos.10086.cn/ips/cmpayService"; //手机支付接口提交网关
       	$ipAddress    = $this -> getClientIP();
		$characterSet = "00";//00- GBK 01- GB2312 02- UTF-8 默讣00-GBK
		$callbackUrl  = "http://{$_SERVER['HTTP_HOST']}/payment/respond/pay_type/phonepay";//同步方法
		$notifyUrl    = "http://{$_SERVER['HTTP_HOST']}/payment/sync/pay_type/phonepay";//异步返回
		$merchantId	  = $payment['merchant_id'];//商户Id
		$requestId	  = strtotime("now");//商户请求的交易流水号，需要唯一
		$signType	  = "MD5";//签名方式 只能是MD5，RSA
		$version	  = "2.0.0";//版本
		$amount		  = $order['price_pay']*100;
		$bankAbbr	  = "";//可以为空 用户选择的银行所对应的英文代号，详细信息请见后面的银行代码对照表
		$currency	  = "00";//00- CNY—现金支付方式 默认为：00
		$orderDate    = date("Ymd");//商户发起请求的时间;年年年年月月日日
		$orderId	  = $order['batch_sn'];//商户系统订单号
		$merAcDate	  = date("Ymd"); //商户发起请求的会计日期;年年年年月月日日
		$period		  = "07";//数字，不订单有效期单位同时构成订单有效期
		$periodUnit	  = "02";//只能取以下枚举值00- 分 01- 小时 02- 日
		$merchantAbbr = "";//商户展示名称 可以为空
		$productDesc  = "";//可以为空，对商品的描述
		$productId	  = "";//可以为空，所购买商品的编号
		$productName  = iconv("UTF-8","GBK","垦丰商城订单");//所购买商品的名称
		$productNum	  = "";//可以为空，所购买商品数量
		$reserved1	  = "";//交易返回时原样返回给商家网站，给商户备用
		$reserved2	  = "";//交易返回时原样返回给商家网站，给商户备用
		$userToken	  = "";//待支付的手机号或者手机支付账户昵称
		$showUrl	  = "";//商品展示的URL
		$couponsFlag  = "";//00使用全部营销工具(默讣) 10丌支持使用电子券 20丌支持代金券 30-丌支持积分 40-丌支持所有营销工具
		//组织提交参数完成

		//组织签名数据
		$signData = $characterSet.$callbackUrl.$notifyUrl.$ipAddress
					  .$merchantId  .$requestId  .$signType .$type
					  .$version     .$amount     .$bankAbbr .$currency
					  .$orderDate   .$orderId    .$merAcDate .$period   .$periodUnit
					  .$merchantAbbr.$productDesc.$productId.$productName
					  .$productNum  .$reserved1  .$reserved2.$userToken
					  .$showUrl     .$couponsFlag;
		$signKey=$payment['sign_key'];
		//MD5方式签名
		$hmac=$this->MD5sign($signKey,$signData);
		$requestData = array();
		$requestData["characterSet"] = $characterSet;
        $requestData["callbackUrl"]  = $callbackUrl;
        $requestData["notifyUrl"]    = $notifyUrl;
        $requestData["ipAddress"]    = $ipAddress;
        $requestData["merchantId"]   = $merchantId;
        $requestData["requestId"]    = $requestId;
        $requestData["signType"]     = $signType;
		$requestData["type"]         = $type;
		$requestData["version"]      = $version;
		$requestData["hmac"]         = $hmac;
        $requestData["amount"]       = $amount;
        $requestData["bankAbbr"]     = $bankAbbr;
        $requestData["currency"]     = $currency;
        $requestData["orderDate"]    = $orderDate;
        $requestData["orderId"]      = $orderId;
        $requestData["merAcDate"]    = $merAcDate;
        $requestData["period"]       = $period;
        $requestData["periodUnit"]   = $periodUnit;
        $requestData["merchantAbbr"] = $merchantAbbr;
        $requestData["productDesc"]  = $productDesc;
        $requestData["productId"]    = $productId;
        $requestData["productName"]  = $productName;
        $requestData["productNum"]   = $productNum;
        $requestData["reserved1"]    = $reserved1;
        $requestData["reserved2"]    = $reserved2;
        $requestData["userToken"]    = $userToken;
        $requestData["showUrl"] 	   = $showUrl;
        $requestData["couponsFlag"]  = $couponsFlag;
		//http请求到手机支付平台
		$sTotalString = $this->POSTDATA($reqUrl,$requestData);
		$recv = $sTotalString["MSG"];
		$recvArray =$this->parseRecv($recv);

        $code=$recvArray["returnCode"];

		if ($code!="000000") {
			 echo "code:".$code."</br>msg:".$this->decodeUtf8($recvArray["message"]);
			 exit();
		}
		else
		{
			 $vfsign=$recvArray["merchantId"].$recvArray["requestId"]
					  .$recvArray["signType"]  .$recvArray["type"]
					  .$recvArray["version"]   .$recvArray["returnCode"]
					  .$recvArray["message"]   .$recvArray["payUrl"];
			 $hmac=$this->MD5sign($signKey,$vfsign);
			 $vhmac=$recvArray["hmac"];
			 if($hmac!=$vhmac)
			 {
				echo "验证签名失败!";
				exit();
			}
			else
			{
				 $payUrl = $recvArray["payUrl"];
			      //返回url处理
			      $rpayUrl= $this -> parseUrl($payUrl);
			}
	    }

	    $url =  $rpayUrl["url"];
	    header("Location:".$url);
    }

	/**
     * 异步验证函数
     *
     * @return boolean
     */
	protected function notifyVerify($partner, $securityCode, $signType = 'MD5', $_input_charset = 'GBK', $transport = 'http')
    {
        if($transport == 'https') {
			$gateway = 'https://www.alipay.com/cooperate/gateway.do?';
            $veryfyUrl = $gateway . 'service=notify_verify' . '&partner=' . $partner . '&notify_id='.$_POST['notify_id'];
		} else {
            $gateway = 'http://notify.alipay.com/trade/notify_query.do?';
            $veryfyUrl = $gateway . 'msg_id=' . $_POST['notify_id']. '&email=' . $partner . '&order_no=' . $_POST['out_trade_no'];
        }

		$veryfyResult  = $this->getVerify($veryfyUrl);
		$post          = $this->paraFilter($_POST);
		$sortPost      = $this->argSort($post);
        $arg = '';
		while ( list($key, $val) = each($sortPost) ) {
			$arg .= $key . '=' . $val .'&';
		}
		$prestr = rtrim($arg, '&');  //去掉最后一个&号
		$mysign = MD5($prestr.$securityCode);
        return true;

		//log_result("notify_url_log=".$_POST["sign"]."&".$this->mysign."&".$this->charset_decode(implode(",",$_POST),$this->_input_charset ));
/*
		if (preg_match("true$",$veryfyResult) && $mysign == $_POST['sign']) {
			return true;
		} else {
            return false;
        }
*/

	}
    /**
     * sock请求验证数据有效性
     *
     * @return string
     */
	protected function getVerify($url,$timeOut = '60') {
		$urlarr = parse_url($url);
		$errno = '';
		$errstr = '';
		$transports = '';
		if($urlarr['scheme'] == 'https') {
			$transports = 'ssl://';
			$urlarr['port'] = '443';
		} else {
			$transports = 'tcp://';
			$urlarr['port'] = '80';
		}
		$fp=@fsockopen($transports . $urlarr['host'], $urlarr['port'], $errno, $errstr, $timeOut);
		if(!$fp) {
            $this -> writeLog("ERROR: $errno - $errstr\n", 'alipaySync');
			exit;
		} else {
			fputs($fp, 'POST '.$urlarr['path']." HTTP/1.1\r\n");
			fputs($fp, 'Host: '.$urlarr['host']."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, 'Content-length: '.strlen($urlarr['query'])."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $urlarr['query'] . "\r\n\r\n");
			while( !feof($fp) ) {
				$info[]=@fgets($fp, 1024);
			}
			fclose($fp);
			$info = implode(',',$info);
            /*
			while ( list($key, $val) = each($_POST) ) {
				$arg .= $key . '=' . $val.'&';
			}
			log_result("notify_url_log=".$url.$this->charset_decode($info,$this->_input_charset));
			log_result("notify_url_log=".$this->charset_decode($arg,$this->_input_charset));
            */
			return $info;
		}
	}

	public function argSort($array) {
		ksort($array);
		reset($array);
		return $array;
	}

	protected function paraFilter($parameter) { //除去数组中的空值和签名模式
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == 'sign' || $key == 'sign_type' || $val == 'pay_type' ||$val == '') {
                continue;
            } else {
                $para[$key] = $parameter[$key];
            }
		}
		return $para;
	}
	/*获取用户IP地址*/
	private function getClientIP()
	{
		if(!empty($_SERVER["HTTP_CLIENT_IP"]))
		{
			$cip = $_SERVER["HTTP_CLIENT_IP"];
		}
		else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{
			$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		else if(!empty($_SERVER["REMOTE_ADDR"]))
		{
			$cip = $_SERVER["REMOTE_ADDR"];
		}
		else
		{
			$cip = "unknown";
		}
		return $cip;

	}
	//MD5方式签名
	private function MD5sign($okey,$odata)
	{
		 $signdata=$this->hmac("",$odata);
		 return $this->hmac($okey,$signdata);
	}
	private function hmac ($key, $data)
	{
	  $key = iconv('gb2312', 'utf-8', $key);
	  $data = iconv('gb2312', 'utf-8', $data);
	  $b = 64;
	  if (strlen($key) > $b) {
	  $key = pack("H*",md5($key));
	  }
	  $key = str_pad($key, $b, chr(0x00));
	  $ipad = str_pad('', $b, chr(0x36));
	  $opad = str_pad('', $b, chr(0x5c));
	  $k_ipad = $key ^ $ipad ;
	  $k_opad = $key ^ $opad;
	  return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	}
	/*
	 功能 发送HTTP请求
	  URL  请求地址
	  data 请求数据数组
	*/
	private function POSTDATA($url, $data)
	{
		$url = parse_url($url);
		if (!$url)
		{
			//RecordLog("couldn't parse url");
			return "couldn't parse url";
		}
		if (!isset($url['port'])) { $url['port'] = ""; }

		if (!isset($url['query'])) { $url['query'] = ""; }


		$encoded = "";

		while (list($k,$v) = each($data))
		{
			$encoded .= ($encoded ? "&" : "");
			$encoded .= rawurlencode($k)."=".rawurlencode($v);
		}
		$urlHead = null;
		$urlPort = $url['port'];
		if($url['scheme'] == "https")
		{
			$urlHead = "ssl://".$url['host'];
			if($url['port'] == null || $url['port'] == 0)
			{
				$urlPort = 443;
			}
		}
		else
		{
			$urlHead = $url['host'];
			if($url['port'] == null || $url['port'] == 0)
			{
				$urlPort = 80;
			}
		}
		//RecordLog("YGM",$urlHead);
		$fp = fsockopen($urlHead, $urlPort);

		if (!$fp) return "Failed to open socket to $url[host]";

		$tmp="";
		$tmp.=sprintf("POST %s%s%s HTTP/1.0\r\n", $url['path'], $url['query'] ? "?" : "", $url['query']);
		$tmp.="Host: $url[host]\r\n";
		$tmp.="Content-type: application/x-www-form-urlencoded\r\n";
		$tmp.="Content-Length: " . strlen($encoded) . "\r\n";
		$tmp.="Connection: close\r\n\r\n";
		$tmp.="$encoded\r\n";
		fputs($fp,$tmp);

		$line = fgets($fp,1024);

		if (!preg_match("#^HTTP/1\.. 200#i", $line))
		{
			$logstr = "MSG".$line;
			//RecordLog("YGM",$logstr);
			return array("FLAG"=>0,"MSG"=>$line);
		}

		$results = ""; $inheader = 1;
		while(!feof($fp))
		{
			$line = fgets($fp,1024);
			if ($inheader && ($line == "\n" || $line == "\r\n"))
			{
				$inheader = 0;
			}
			elseif (!$inheader)
			{
				$results .= $line;
			}
		}
		fclose($fp);
		return array("FLAG"=>1,"MSG"=>$results);
	}
	/*
	 功能 把http请求返回数组 格式化成数组
	*/
	private function parseRecv($source)
	{
		$ret = array();
		$temp = explode("&",$source);

		foreach ($temp as $value)
		{
			$index=strpos($value,"=");
			$_key=substr($value,0,$index);
			$_value=substr($value,$index+1);
			$ret[$_key] = $_value;
		}

		return $ret;
	}
	/*
		功能：把UTF-8 编号数据转换成 GB2312 忽略转换错误
	*/
	private function decodeUtf8($source)
	{
		$temp = urldecode($source);
		$ret = iconv("UTF-8","GB2312//IGNORE",$temp);
		return $ret;
	}
	
	//返回URL处理
	private function parseUrl($payUrl)
	{
		$temp =explode("<hi:$$>",$payUrl);
		$url_lst=explode("<hi:=>",$temp[0]);
		$url=$url_lst[1];
		$method_lst=explode("<hi:=>",$temp[1]);
		$method=$method_lst[1];
		$sessionid_lst=explode("<hi:=>",$temp[2]);
		$sessionid=$sessionid_lst[1];
		$url=$url."?SESSIONID=".$sessionid;
		$rpayUrl = array();
		$rpayUrl["url"]=$url;
		$rpayUrl["method"]=$method;
		return $rpayUrl;
		
	}
}