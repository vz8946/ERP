<?php
class ExternalController extends Zend_Controller_Action
{
    /**
     * @var Admin_Models_API_Transport
     */
    private $_api = null;

	public function init()
    {

	}

    public function indexAction()
    {
        exit;
    }


    //触发短信接口
    public function sendmsgAction()
    {
		//允许访问的IP列表
		$ip_allow = array('127.0.0.1','101.226.1.171','101.226.1.143');
		$real_ip = Custom_Model_Ip::_real_ip();
		$msg = urldecode(str_replace('/external/sendmsg/msg/','',$_SERVER['REQUEST_URI']));
		if (isset($msg) && in_array($real_ip,$ip_allow) ) {
			    $sms= new  Custom_Model_Sms();
				$mobile1='18621663105';
				$mobile2='13386216063';
				//$response =  $sms->send($mobile1,trim($msg));
				$response =  $sms->send($mobile2,trim($msg));
		}
		exit();
    }

    public function stockSoapAction()
    {
        $wsdl = $this -> _request -> getParam('wsdl');
        if (isset($wsdl)) {
            $wsdl = new Zend_Soap_AutoDiscover();
            $wsdl -> setClass('stockClass');
            $wsdl -> setUri('http://www.1jiankang.com/external/stock-soap');
            $wsdl -> handle();
        }
        else {
            $server = new Zend_Soap_Server(null, array('uri' => 'http://www.1jiankang.com/external/stock-soap'));
            $server -> setClass('stockClass');
            $server -> registerFaultException(array('myException'));
            $server -> handle();
        }

        exit;
    }
    public function validateSoapAction()
    {
        $wsdl = $this -> _request -> getParam('wsdl');
        if (isset($wsdl)) {
            $wsdl = new Zend_Soap_AutoDiscover();
            $wsdl -> setClass('ymsValidateClass');
            $wsdl -> setUri('http://www.1jiankang.com/external/validate-soap');
            $wsdl -> handle();
        }
        else {
            $server = new Zend_Soap_Server(null, array('uri' => 'http://www.1jiankang.com/external/validate-soap'));
            $server -> setClass('ymsValidateClass');
            $server -> registerFaultException(array('myException'));
            $server -> handle();
        }

        exit;
    }


    public function jdAction()
    {
        $code = $this -> _request -> getParam('code');
        $shopID = $this -> _request -> getParam('state');
        if (!$code || !$shopID) {
            exit;
        }

        $api = new Admin_Models_API_Shop();
        $shop = $api -> get("shop_id = '{$shopID}'");
        if ($shop['total'] < 0) {
	        exit;
	    }

	    $shop = array_shift($shop['list']);
	    if ($shop['shop_type'] != 'jingdong') {
	        exit;
	    }

	    $config = unserialize($shop['config']);
	    if (!$config['key'] || !$config['secret']) {
	        exit;
	    }

	    $content = file_get_contents("http://auth.360buy.com/oauth/token?grant_type=authorization_code&client_id={$config['key']}&redirect_uri=http://www.1jiankang.com/external/jd&code={$code}&state={$shopID}&client_secret={$config['secret']}");
	    $content = Zend_Json::decode(trim($content));
	    if (!$content['access_token']) {
	        exit;
	    }

	    $config['token'] = $content['access_token'];
	    $api -> ajaxUpdate($shopID, 'config', serialize($config));

        die('授权成功!');
    }

    public function taobaoAction()
    {
        $code = $this -> _request -> getParam('code');
        $shopID = $this -> _request -> getParam('state');
        if (!$code || !$shopID) {
            exit;
        }

        $api = new Admin_Models_API_Shop();
        $shop = $api -> get("shop_id = '{$shopID}'");
        if ($shop['total'] < 0) {
	        exit;
	    }

	    $shop = array_shift($shop['list']);
	    if ($shop['shop_type'] != 'taobao') {
	        exit;
	    }

	    $config = unserialize($shop['config']);
	    if (!$config['id'] || !$config['key']) {
	        exit;
	    }
        
	    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth.taobao.com/token');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id={$config['id']}&client_secret={$config['key']}&grant_type=authorization_code&code={$code}&redirect_uri=http://www.1jiankang.com/external/taobao&state={$shopID}");
        $output = curl_exec($ch);
        curl_close($ch);

	    $content = Zend_Json::decode(trim($output));
	    if (!$content['access_token']) {
	        exit;
	    }

	    $config['session'] = $content['access_token'];
	    $api -> ajaxUpdate($shopID, 'config', serialize($config));

        die('授权成功!');
    }
    
    public function alibabaAction()
    {
        $param = $this -> _request -> getParams();
        
        $shopID = $param['state'];
        $code = $param['code'];
        if (!$code || !$shopID) {
            exit;
        }
        
        $api = new Admin_Models_API_Shop();
        $shop = $api -> get("shop_id = '{$shopID}'");
        if ($shop['total'] < 0) {
	        exit;
	    }
        
	    $shop = array_shift($shop['list']);
	    if ($shop['shop_type'] != 'alibaba') {
	        exit;
	    }

	    $config = unserialize($shop['config']);
	    if (!$config['id'] || !$config['key']) {
	        exit;
	    }
	    
	    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://gw.open.1688.com/openapi/http/1/system.oauth2/getToken/'.$config['id']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=authorization_code&need_refresh_token=true&client_id={$config['id']}&client_secret={$config['key']}&redirect_uri=http://www.1jiankang.com/external/aiibaba&code={$code}");
        $output = curl_exec($ch);
        curl_close($ch);
        
        $content = Zend_Json::decode(trim($output));
	    if (!$content['access_token']) {
	        exit;
	    }
        
	    $config['session'] = $content['access_token'];
	    $api -> ajaxUpdate($shopID, 'config', serialize($config));
        
        die('授权成功!');
    }
    
    public function dangdangAction()
    {
        $code = $this -> _request -> getParam('code');
        $shopID = $this -> _request -> getParam('state');
        if (!$code || !$shopID) {
            exit;
        }

        $api = new Admin_Models_API_Shop();
        $shop = $api -> get("shop_id = '{$shopID}'");
        if ($shop['total'] < 0) {
	        exit;
	    }

	    $shop = array_shift($shop['list']);
	    if ($shop['shop_type'] != 'dangdang') {
	        exit;
	    }

	    $config = unserialize($shop['config']);
	    if (!$config['id'] || !$config['key']) {
	        exit;
	    }
        
	    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://oauth.dangdang.com/token');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "appId={$config['id']}&grantType=code&code={$code}&appSecret={$config['key']}&redirectUrl=http://www.1jiankang.com/external/dangdang&state={$shopID}");
        $output = curl_exec($ch);
        curl_close($ch);
	    $content = Zend_Json::decode(trim($output));
	    if (!$content['accessToken']) {
	        exit;
	    }

	    $config['session'] = $content['accessToken'];
	    $api -> ajaxUpdate($shopID, 'config', serialize($config));

        die('授权成功!');
    }

    public function kuaidiAction()
    {
        $param = $this -> _request -> getParam('param');
        $param = Zend_Json::decode(trim(str_replace('\"', '"', $param)));

        $state = $param['lastResult']['state'];
        $logistic_no = $param['lastResult']['nu'];
        
        if (!$logistic_no) {
            echo '{"result":"false","returnCode":"500","message":"没有运单号"}';
            exit;
        }
        
        $logisticAPI = new Admin_Models_DB_Logistic();
        $row = array('logistic_no' => $logistic_no,
                     'status' => $param['status'],
                     'state' => $state,
                     'message' => $param['message'],
                     'last_poll_time' => time(),
                    );
        $logisticAPI -> addKuaidi100($row);

        if ($state == 4) {
            $state = 6;
        }
        if (in_array($state, array(3,6))) {
            $transportAPI = new Admin_Models_API_Transport();
            $transport = array_shift($transportAPI -> get("logistic_no = '{$logistic_no}'"));
            if ($transport && $transport['logistic_status'] != 2) {
                if ($state == 3) {
                    $transport['logistic_status'] = 2;
                }
                else {
                    $transport['logistic_status'] = 3;
                }
                $transport['auto_track'] = 1;
                $transport['op_time'] = time();
                $transport['admin_name'] = 'system';
                $transport['remark'] = '快递100推送';
                $transportAPI -> track($transport);
            }
        }

        echo '{"result":"true","returnCode":"200","message":"成功"}';
        exit;
    }
    
    /**
     * 截取后四位
     */
    private function subLast($str){
        if(empty($str)){
            return "";
        }
        else{
            return substr($str,0,strlen($str)-4);
        }
    }
    //是否ip限制
    public $ip_limit = true;
    //允许访问的IP列表
    public $ip_allow = array('127.0.0.1','58.247.53.254','116.231.130.62','101.226.1.143');
    /**
     * 根据validate查询收货人电话信息
     */
    public function viewAction(){
        if($this->ip_limit){
            $remote_ip = Custom_Model_Ip::_real_ip();
            $ai=array();
            $ai[]=$remote_ip;
            $intersect = array_intersect($ai,$this->ip_allow);
            if(empty($intersect)){
               echo "对不起，您没有访问权限！";
               exit;
            }
        }
        $validate = $this -> _request -> getParam('validate');
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        if(empty($validate)){
            echo "验证码为空，查询无结果！";
            exit;
        }else{
            $_apitransport = new Admin_Models_API_Transport();
            $transport=array_shift($_apitransport->get("validate_sn=$validate"));
            if(empty($transport)){
                echo "验证码".$validate."查询无结果！";
                exit;
            }else{
                $transport['mobile']=$_apitransport->checkPhoneNo($transport['mobile']);
                $transport['s_tel']=$_apitransport->checkTelNo($transport['tel']);
                $this->view->validate=$validate;
                $this->view ->transport = $transport;
            }
        }
    }
    
    public function listAction(){
        if($this->ip_limit){
            $remote_ip = Custom_Model_Ip::_real_ip();
            $ai=array();
            $ai[]=$remote_ip;
            $intersect = array_intersect($ai,$this->ip_allow);
            if(empty($intersect)){
                echo "对不起，您没有访问权限！";
                exit;
            }
        }
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $validate = $this -> _request -> getParam('validate');
        $logistic_no = $this -> _request -> getParam('logistic_no');
        $consignee = $this -> _request -> getParam('consignee');
        if(empty($validate) && empty($logistic_no) && empty($consignee)){
            $this->view ->transport = null;
        }else{
            $where="";
            if(!empty($validate)){
                $where=" validate_sn like '%".$validate."%' ";
            }
            if(!empty($logistic_no)){
                if(empty($where)){
                    $where=" logistic_no like '%".$logistic_no."%' ";
                }else{
                    $where .=" or logistic_no like '%".$logistic_no."%' ";
                }
            }
            if(!empty($consignee)){
                if(empty($where)){
                    $where=" consignee like '%".$consignee."%' ";
                }else{
                    $where .=" or consignee like '%".$consignee."%' ";
                }
            }
            $_apitransport = new Admin_Models_API_Transport();
            $transport=$_apitransport->get($where);
            $this->view ->transport = $transport;
        }
    }
}

class ymsValidateClass{
	//是否ip限制
	public $ip_limit = true;
	//允许访问的IP列表
	public $ip_allow = array('127.0.0.1','58.247.53.254','101.226.1.143','116.231.130.62','101.226.1.169');
	public function __construct()
    {
		$this -> _api = new Admin_Models_API_Transport();
	}
	/**
	 * 根据5位数验证码获取手机号码和电话号码
	 * @param string $validate_sn
     * @return string
     * @throws myException
	 */
	public function getMobileByValidateSn($validate_sn){
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                  <result>
                    <code>%%code%%</code>
                    <message>%%message%%</message>
                    <mobile>%%mobile%%</mobile>
                    <tel>%%tel%%</tel>
                  </result>";
        $code="00";
        $message="";
        $mobile="";
        $tel="";
		//ip限制
		if($this->ip_limit){
		    $remote_ip = Custom_Model_Ip::_real_ip();
		    $aii=array();
		    $aii[]=$remote_ip;
			$intersect = array_intersect($aii,$this->ip_allow);
			if(empty($intersect)){
				$code="00";//验证失败 返回00
				$message="验证失败";
				$xml=str_replace('%%code%%', $code, $xml);
				$xml=str_replace('%%message%%', $message, $xml);
				$xml=str_replace('%%mobile%%', $mobile, $xml);
				$xml=str_replace('%%tel%%', $tel, $xml);
				return $xml;
			}
		}
		$datas=$this -> _api->getMobileByValidateSn($validate_sn);
		if(empty($datas)){
			$code="11";
			$message="查询结果为空";
		}else if(count($datas)>1){
			$code="22";
			$message="查询异常";
		}else{
			$code="88";
			$message="查询成功";
			$mobile=$this -> _api->checkPhoneNo($datas[0]['mobile']);
			$tel=$this -> _api->checkTelNo($datas[0]['tel']);
		}
		$xml=str_replace('%%code%%', $code, $xml);
		$xml=str_replace('%%message%%', $message, $xml);
		$xml=str_replace('%%mobile%%', $mobile, $xml);
		$xml=str_replace('%%tel%%', $tel, $xml);
		return $xml;
	}
}

class stockClass
{
    private $_api;

    public function __construct()
    {
		$this -> _api = new Admin_Models_API_Stock();
	}

	/**
     * 获得可用库存
     * @param string $logicArea
     * @param string $productSN
     * @return string
     * @throws myException
     */
	public function getAbleStock($logicArea, $productSN)
	{
	    $config = new Custom_Model_Stock_Config();
	    if (!$config -> _logicArea[$logicArea]) {
	        return 'error logic area';
	    }

	    $productIDArray = array();
	    $productSNArray = explode(':', $productSN);
	    foreach ($productSNArray as $productSN) {
	        $productID = $this -> getProductID($productSN);
    	    if (!$productID) {
    	        return 'error product sn';
    	    }
    	    $productIDArray[] = $productID;
	    }

	    $this -> _api -> setLogicArea($logicArea);
        foreach ($productIDArray as $index => $productID) {
            $stock = $this -> _api -> getSaleProductStock($productID);
            $result[] = $stock['able_number'] ? $stock['able_number'] : 0;
        }

	    return implode(':', $result);
	}

	/**
     * 检验库存
     * @param string $logicArea
     * @param string $productSN
     * @param integer $number
     * @return string
     * @throws myException
     */
	public function checkStock($logicArea, $productSN, $number)
	{
	    $config = new Custom_Model_Stock_Config();
	    if (!$config -> _logicArea[$logicArea]) {
	        return 'error logic area';
	    }

	    $productID = $this -> getProductID($productSN);
	    if (!$productID) {
	        return 'error product sn';
	    }

	    $this -> _api -> setLogicArea($logicArea);

	    return $this -> _api -> checkPreSaleProductStock($productID, (int)$number);
	}

	/**
     * 添加销售出库单
     * @param string $logicArea
     * @param string $productSN
     * @param string $number
     * @param string $orderSN
     * @return string
     * @throws myException
     */
	public function addOutStock($logicArea, $productSN, $number, $orderSN)
	{
	    $config = new Custom_Model_Stock_Config();
	    if (!$config -> _logicArea[$logicArea]) {
	        return 'error logic area';
	    }

	    $productIDArray = array();
	    $productSNArray = explode(':', $productSN);
	    foreach ($productSNArray as $productSN) {
	        $productID = $this -> getProductID($productSN);
    	    if (!$productID) {
    	        return 'error product sn';
    	    }
    	    $productIDArray[] = $productID;
	    }

	    $numberArray = explode(':', $number);
	    if (count($numberArray) != count($productSNArray)) {
	        return 'error number';
	    }

	    foreach ($numberArray as $number) {
	        if ((int)$number <= 0) {
	            return 'error number';
	        }
	    }

	    $details = array();
	    $this -> _api -> setLogicArea($logicArea);
	    foreach ($productIDArray as $index => $productID) {
	        if (!$this -> _api -> checkPreSaleProductStock($productID, $numberArray[$index])) {
	            return 'error stock';
	        }

	        $details[] = array('product_id' => $productID,
        	                   'status_id' => 2,
        	                   'number' => $numberArray[$index],
        	                   'shop_price' => 0,
        	                  );
	    }

	    if ($logicArea == 1) {
	        foreach ($productIDArray as $index => $productID) {
	            $this -> holdSaleProductStock($productID, $numberArray[$index]);
	        }
	    }

	    $outstockAPI = new Admin_Models_API_OutStock();
	    $billNo = Custom_Model_CreateSn::createSn();
		$row = array('lid' => $logicArea,
                     'bill_no' => $billNo,
                     'bill_type' => 13,
                     'bill_status' => 4,
                     'remark' => $orderSN,
                     'add_time' => time(),
                     'finish_time' => time(),
                     'admin_name' => 'system',
                    );
        $outstockAPI -> insertApi($row, $details, $logicArea, true);
	    $outstockAPI -> sendByBillNo($billNo);

	    return $billNo;
	}

	/**
     * 添加退货入库单
     * @param string $logicArea
     * @param string $productSN
     * @param string $number
     * @param string $orderSN
     * @return string
     * @throws myException
     */
	public function addInStock($logicArea, $productSN, $number, $orderSN)
	{
	    $config = new Custom_Model_Stock_Config();
	    if (!$config -> _logicArea[$logicArea]) {
	        return 'error logic area';
	    }

	    $productIDArray = array();
	    $productSNArray = explode(':', $productSN);
	    foreach ($productSNArray as $productSN) {
	        $productID = $this -> getProductID($productSN);
    	    if (!$productID) {
    	        return 'error product sn';
    	    }
    	    $productIDArray[] = $productID;
	    }

	    $numberArray = explode(':', $number);
	    if (count($numberArray) != count($productSNArray)) {
	        return 'error number';
	    }

	    foreach ($numberArray as $number) {
	        if ((int)$number <= 0) {
	            return 'error number';
	        }
	    }

	    $details = array();
	    $this -> _api -> setLogicArea($logicArea);
	    foreach ($productIDArray as $index => $productID) {
	        $details[] = array('product_id' => $productID,
	                           'batch_id' => 0,
        	                   'status_id' => 6,
        	                   'plan_number' => $numberArray[$index],
        	                   'shop_price' => 0,
        	                  );
	    }

	    $instockAPI = new Admin_Models_API_InStock();
	    $billNo = Custom_Model_CreateSn::createSn();
        $row = array('lid' => $logicArea,
                     'bill_no' => $billNo,
                     'bill_type' => 13,
                     'bill_status' => 6,
                     'remark' => $orderSN,
                     'add_time' => time(),
                     'admin_name' => 'system',
                    );
        $instockAPI -> insertApi($row, $details, $logicArea, true);

	    $instockAPI -> receiveByBillNo($billNo);

	    return $billNo;
	}

    private function getProductID($productSN)
    {
        $goodsAPI = new Shop_Models_DB_Goods();
        $productSN = (int)$productSN;
        $product = array_shift($goodsAPI -> getProductInfo(" and product_sn = '{$productSN}'"));

        return $product['product_id'];
    }
}

class myException extends Zend_Exception {}
