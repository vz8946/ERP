<?php
class AuthController extends Zend_Controller_Action
{
   /**
    * 
    * @var Shop_Models_API_Auth
    */
	protected $_auth = null;

	/**
     * 退出提示
     */
	const LOGOUT_SUCESS = '成功退出';
	/**
     * 未填写用户名
     */
	const NO_USERNAME = '请填写用户名!';

	/**
     * 密码不一致
     */
	const NO_SAME_PASSWORD = '输入的密码不一致!';

	/**
     * 密码为空
     */
	const NO_PASSWORD = '密码及确认密码不能为空!';

	/**
     * 注册会员成功
     */
	const REGISTER_SUCESS = '注册成功!';

	/**
     * 用户名已存在
     */
	const USERNAME_EXISTS = '该用户名已存在!';

	/**
     * 用户不存在
     */
	const USERNAME_NO_EXISTS = '该用户不存在!';

	/**
     * 昵称已存在
     */
	const NICKNAME_EXISTS = '该昵称已存在!';

	/**
     * 系统错误
     */
	const ERROR = '系统错误，请稍后再次尝试!';

	/**
     * 发送邮件失败
     */
	const SEND_ERROR = '发送邮件失败,请稍候再次尝试!';

	/**
     * 验证失败
     */
	const CODE_ERROR = '验证失败!';

	/**
     * 输入的验证码错误
     */
	const ERROR_VERIFY_CODE = '验证码错误或已过期!';

	/**
     * 联盟参数名称
     *
     * @var    string
     */
 	protected $_uidName = 'u';
	/**
     * 站内活动参数名称
     *
     * @var    string
     */
 	protected $_actName = 'a';

	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _auth = Shop_Models_API_Auth :: getInstance();
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);


	/**
	 * 支付宝配置信息
	 */
	$this-> aliapy_config = array(	
						'partner'=>'2088301726693069',
						'key'=>'rby8r5xun5q3dilapolpuoa4xy0mnd7o',
						'return_url'=>"http://{$_SERVER['HTTP_HOST']}/auth/alipayloginreturn/",
						'sign_type'=>'MD5',
						'input_charset'=>'utf-8',
						'transport'=>'http',
						'email'=>'zhifubao201303@ejiankang.com'		
					  );
	}
	/**
     * Js用户状态
     *
     * @return void
     */
	public function jsAuthUserIdAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
        $_auth = $this -> _auth -> getAuth();
		exit($_auth['user_id']);
	}
	/**
     * 用户登出
     *
     * @return void
     */
	public function logoutAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$this -> _auth -> unsetAuth();
		!$this -> _request -> getParam('nojump') && $this -> _helper -> redirector -> gotoUrl(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Zend_Controller_Front::getInstance() -> getBaseUrl());
	}
	/**
     * Js登录状态显示
     *
     * @return void
     */
	public function jsAuthStateAction()
	{
		//---------- 自动登录 start ----------
		if (!$this -> _auth -> getAuth() && $_COOKIE['auto_login']) {
			$codstr = Custom_Model_Encryption::getInstance() ->decrypt($_COOKIE['auto_login'],'EncryptCode');
			$userInfo = unserialize($codstr);
		
			if($userInfo['user_name'] && $userInfo['password'])
			{
				$authed = $this -> _auth -> certification($userInfo['user_name'], $userInfo['password'], array('utype' => 'member'));
			}
		}
		//---------- 自动登录 end -----------
		
		$getUId = $this -> _request -> getParam($this -> _uidName);
		if ($getUId) {
			$union = new Shop_Models_API_Union($getUId);
			$union -> setUnionCookie();
			$union -> addUnionLog();
		}
		$getAId = $this -> _request -> getParam($this -> _actName);
		if ($getAId) {
			$activity = new Shop_Models_API_Activity();
			$activity -> setActivityCookie();
		}
		
        $loginUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/login.html';
        $regUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/reg.html';
        $logoutUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/logout.html';
		$login = $this -> _auth -> jsAuthState($loginUrl, $regUrl, $logoutUrl);
		exit($login);
	}

	/**
	 * 购物车快速认证
	 *
	 * @return json
	 * */
	public function fastLoginAction()
	{
		if ($this -> _request -> isPost()){
		    $username = $this -> _request -> getPost('user_name');
            $password = $this -> _request -> getPost('password');
            
                    
            $filterChain = new Zend_Filter();
            $filterChain -> addFilter(new Zend_Filter_StripTags());     
                   
            $username = $filterChain -> filter($username);
            $password = $filterChain -> filter($password);
            $utype = 'member';   
            $authed = $this -> _auth -> certification($username, $password, array('utype' => $utype));
            if($authed === true){            	
	            echo json_encode(array('status'=>'yes','msg'=>'登录成功！','user_name'=>$username));
	            exit;
            }else{
            	echo json_encode(array('status'=>'no','msg'=>'用户名或密码错误！'));exit;
            }
            
		}else{
            echo json_encode(array('status'=>'no','msg'=>'非法操作！'));exit;
		}
	}

	/**
     * 用户登录
     *
     * @return void
     */
	public function loginAction(){		
		
		if ($this -> _request -> isPost()) {
            $username = $this -> _request -> getPost('user_name');
            $password = $this -> _request -> getPost('password');

            $filterChain = new Zend_Filter();
            $filterChain -> addFilter(new Zend_Filter_StripTags());
            $username = $filterChain -> filter($username);
            $password = $filterChain -> filter($password);

            $utype ='member';
            $verifyCode = $this -> _request -> getPost('verifyCode');
            $authImage = new Custom_Model_AuthImage('shopLogin');
            if (!$authImage -> checkCode($verifyCode)){
            	echo Zend_Json::encode(array('status'=>0,'msg'=>'验证码错误！'));
            	exit();
            }
            $authed = $this -> _auth -> certification($username, $password, array('utype' => $utype));

            if ($authed === true) {
            	$auto_login = $this -> _request -> getPost('auto_login');
            	if ($auto_login) {
            		$userinfo = array();
            		$userinfo['user_name'] = $username;
            		$userinfo['password'] = $password;
            		$strinfo = Custom_Model_Encryption::getInstance() -> encrypt(serialize($userinfo),'EncryptCode');
            		setcookie('auto_login',$strinfo,time() + 86400*7,'/');
            	}
                $goto = base64_decode($this -> _request -> getParam('goto'));
                $goto = strpos($goto, 'payment/respond') ? '' : $goto;
                $refer = base64_decode($this -> _request -> getPost('refer'));
                $refer = strpos($refer, 'payment/respond') ? '' : $refer;
                $refer = strpos($refer, 'login.html') ? '' : $refer;
                $refer = strpos($refer, 'reg.html') ? '' : $refer;
                $url = ($goto) ? $goto : (($refer) ? $refer : Zend_Controller_Front::getInstance() -> getBaseUrl());
                echo Zend_Json::encode(array('status'=>1,'url'=>$url,'msg'=>'登录成功,正在跳转……'));
                exit();
            } else {
                echo Zend_Json::encode(array('status'=>0,'msg'=>'账号异常已被冻结！'));
                exit();
            }          
            
		} else {
			
			if ($this -> _auth -> getAuth()) {
				if (strpos($_SERVER['HTTP_REFERER'],'login.html')) {
					$_SERVER['HTTP_REFERER'] = Zend_Controller_Front::getInstance() -> getBaseUrl();
				}
				$this -> _helper -> redirector -> gotoUrl(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Zend_Controller_Front::getInstance() -> getBaseUrl());
			}
			
            if ($this->_request->getParam('goto', '')) {
                $this->view->goto = $this->_request->getParam('goto');
            }            
            $this -> view -> refer = base64_encode(addslashes(strip_tags(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '')));
			$referer = ($_SERVER['HTTP_REFERER']) ? preg_replace('/^[a-z]+:\/\/[^\/]+/i', '', $_SERVER['HTTP_REFERER']) : '';
			$this -> getResponse() -> setHeader('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT')
			                       -> setHeader('Cache-Control', 'no-cache, must-revalidate')
			                       -> setHeader('Pragma', 'no-cache');
		}
	}

	/**
     * 用户注册
     *
     * @return void
     */
	public function regAction()
	{
        $this -> view -> page_title = "垦丰商城 - 世界种子精品商城";
        $this -> view -> page_keyword = "会员注册,种子";
        $this -> view -> page_description = '垦丰电商 -专业的种子商城，品质保证! ';
        if ($this->_request->getParam('goto', '')) {
            $this->view->goto = $this->_request->getParam('goto');
        }
        $this -> view -> refer = base64_encode(addslashes(strip_tags(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '')));
		if ($this -> _auth -> getAuth()) {
            if (strpos($_SERVER['HTTP_REFERER'],'/login.html') || strpos($_SERVER['HTTP_REFERER'],'/reg.html')) {
                $_SERVER['HTTP_REFERER'] = Zend_Controller_Front::getInstance() -> getBaseUrl();
            }
			$this -> _helper -> redirector -> gotoUrl(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Zend_Controller_Front::getInstance() -> getBaseUrl());
		}
	}
	/**
     * 电话下单登录
     *
     * @return void
     */
	public function mixLoginAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$code = $this -> _request -> getParam('code');
        $operator_id =(int)$this -> _request -> getParam('operator_id');
		if ($code) {
            if($operator_id){
                setcookie('operator_id',$operator_id, time() + 86400, '/');
            }
            setcookie('u','', time() + 30*86400, '/');
			$this -> _auth -> mixLogin($code, 'phone');
		}
		$this -> _helper -> redirector('index', 'index');
	}
	/**
     * 用户注册
     *
     * @return void
     */
	public function registerAction()
	{
		if ($this -> _request -> isPost()) {
			$result = $this -> _auth -> register($this -> _request -> getPost());
            $message = '';  // 系统执行信息
            $status = false; // 用于前台显示样式
            $is_success = "NO";
			switch ($result) {
        		case 'noUserName':
        		    $message = self::NO_USERNAME;
        		    break;
        		case 'noSamePassword':
        		    $message = self::NO_SAME_PASSWORD;
        		    break;
        		case 'noPassword':
        		    $message = self::NO_PASSWORD;
        		    break;
        		case 'userNameExists':
        		    $message = self::USERNAME_EXISTS;
        		    break;
        		case 'nickNameExists':
        		    $message = self::NICKNAME_EXISTS;
        		    break;
                case 'ERROR_VERIFY_CODE':
                    $message = self::ERROR_VERIFY_CODE;
                    break;
        		case 'addUserSucess':
        		    $this->view->is_success = "YES";
        		    $message = self::REGISTER_SUCESS;
                    $status = true;
                    $username = $this -> _request -> getPost('user_name');
        		    $password = $this -> _request -> getPost('password');

                    $filterChain = new Zend_Filter();
                    $filterChain -> addFilter(new Zend_Filter_StripTags());
                    $username = $filterChain -> filter($username);
                    $password = $filterChain -> filter($password);

        		    $utype = 'member';
        		    $this -> _auth -> certification($username, $password, array('utype' => $utype));

                    //注册赠送积分 开始
                    $shopConfig = Zend_Registry::get('shopConfig');
                    if (!isset($shopConfig['reg_point'])) {
                        $shopConfig['reg_point'] = 100;
                    }
                    $point = $shopConfig['reg_point'];
                    $auth = $this -> _auth  -> getAuth();
                    $tmp = array('member_id' => $auth['member_id'],
								 'user_name' => $auth['user_name'],
                                 'accountType' => 1,
                                 'accountValue' => $point,
                                 'accountTotalValue' => $auth['point'],
                                 'note' => '注册赠送积分');
                    $this -> _member = new Shop_Models_API_Member();
                    $this -> _member -> editAccount($auth['member_id'], 'point', $tmp);
                    $this -> _auth -> updateAuth();
                    //注册赠送积分 结束

                    $this -> view -> auth = $auth;

				    //注册成功发邮件
				//	$this -> _auth -> sendRegisterEmail($auth);
					$this->view->username = $username;
                    $goto = base64_decode($this -> _request -> getParam('goto'));
                    $refer = base64_decode($this -> _request -> getPost('refer'));
                    $this->view->goto =  $goto;
                    $this->view->refer =  $refer;

        		    break;
        		case 'error':
        		    $message = self::ERROR;
                    break;
                default:
                    break;
        	}
            $this -> view -> status = $status;
            $this -> view -> message = $message;
            $this -> view -> refer = '/reg.html';
		} else {
			$this -> _helper -> redirector('index', 'index');
		}
	}
	
	
	
	
	/**
     * 检测注册用户信息
     *
     * @return void
     */
	public function checkAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		
		$data = $this -> _request -> getParams();
		$data['user_name'] = $data['param'];
		
		$user = $this -> _auth -> check($data);
		if (!$user) {
			echo  Zend_Json::encode(array('status'=>'y','info'=>'邮箱可以注册!'));
			exit();		 
		} else {
          echo  Zend_Json::encode(array('status'=>'n','info'=>'邮箱已被使用!'));
		  exit();	
        }
	}
	
	public function checkRegCodeAction()
	{
		$data = $this -> _request -> getParams();
		$data['verifyCode'] = $data['param'];			
		
		$authImage = new Custom_Model_AuthImage('shopRegister');
		if ($authImage -> checkCode($data['verifyCode'])) {		
			echo  Zend_Json::encode(array('status'=>'y','info'=>'验证通过!'));
			exit();
		} else {
			echo  Zend_Json::encode(array('status'=>'n','info'=>'验证码错误!'));
			exit();
		}
		
	}
	/**
     * 找回密码
     *
     * @return void
     */
	public function getPasswordAction()
	{
        $this -> view -> page_title = "找回密码  垦丰商城 - 世界种子精品商城";
        $this -> view -> page_keyword = "找回密码,密码找回,种子,会员注册";
        $this -> view -> page_description = '垦丰电商 -专业的种子商城，品质保证! ';
		if ($this -> _request -> isPost()) {//接收到申请密码找回请求
			$this -> _helper -> viewRenderer -> setNoRender();
			$email = $this -> _request -> getPost('email');
			$verifyCode = $this -> _request -> getParam('verifyCode');
			$result = $this -> _auth -> sendPassword($email);
		    echo "<script>parent.document.getElementById('dosubmit').disabled=false;</script>";
			$authImage = new Custom_Model_AuthImage('getPassword');
			if (!$authImage -> checkCode($verifyCode)) {
				 Custom_Model_Message::showAlert(self::ERROR_VERIFY_CODE , false);
				 exit;
			}
		    switch ($result) {
        	    case 'sendPasswordSucess':
        	        echo "<script>parent.document.getElementById('send_password_div').style.display='block';parent.document.getElementById('send_password_msg').innerHTML='<b>重置密码的邮件已经发到您的邮箱:".$email."</b>';</script>";
        	        break;
        	    case 'sendError':
        	        Custom_Model_Message::showAlert(self::SEND_ERROR);
        	        break;
        	    case 'noUser':
        	        Custom_Model_Message::showAlert(self::USERNAME_NO_EXISTS);
        	        break;
		    }
		    exit;
		} elseif($this -> _request -> getParam('code')) {//接收到重置密码的code

			$code = $this -> _request -> getParam('code');
			if ($code) {
				if ($this -> _auth -> setPassword($code)) {
					$this -> _helper -> redirector('password', 'member');
				} else {
					Custom_Model_Message::showAlert(self::CODE_ERROR, false, '/index');
				}
			}

		} else {//登录状态下，直接跳转
			if ($this -> _auth -> getAuth()) {
				$this -> _helper -> redirector -> gotoUrl(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Zend_Controller_Front::getInstance() -> getBaseUrl());
			}
		}
        		
	}

	/**
     * 创建认证图片
     *
     * @return void
     */
	public function authImageAction()
	{
		$this -> _helper -> viewRenderer ->setNoRender();
        $space = $this -> _request -> getParam('space');
        $authImage = new Custom_Model_AuthImage($space);
        $authImage -> createImage();
        exit();
	}
	/**
	 * QQ联合登录
	 */
	public function qqloginAction(){
	    $_newauth=new Shop_Models_API_Auth();
	    $qq_params = $_newauth->qq_params;
	    $appid = $qq_params['appid'];
	    $callback = $qq_params['callback'];
	    $scope = $qq_params['scope'];
	
	    //-------生成唯一随机串防CSRF攻击
	    $state = md5(uniqid(rand(), TRUE));
	    $_newauth->write('state',$state);
	
	    //-------构造请求参数列表
	    $keysArr = array(
	            "response_type" => "code",
	            "client_id" => $appid,
	            "redirect_uri" => $callback,
	            "state" => $state,
	            "scope" => $scope
	    );
	
	    $login_url =  $_newauth->combineURL(Shop_Models_API_Auth::GET_AUTH_CODE_URL, $keysArr);
	
	    header("Location:$login_url");
	}
	/**
	 * QQ 联合登录回调函数
	 */
	public function qqcallbackAction(){
	    $_newauth=new Shop_Models_API_Auth();
	    $state = $_newauth->read("state");
	    //--------验证state防止CSRF攻击
	    if($_GET['state'] != $state){
	        $_newauth->showError("30001");
	    }
	    $qq_params = $_newauth->qq_params;
	    //-------请求参数列表
	    $keysArr = array(
	            "grant_type" => "authorization_code",
	            "client_id" => $qq_params['appid'],
	            "redirect_uri" => urlencode($qq_params["callback"]),
	            "client_secret" => $qq_params["appkey"],
	            "code" => $_GET['code']
	    );
	    
	    //------构造请求access_token的url
	    $token_url = $_newauth->combineURL(Shop_Models_API_Auth::GET_ACCESS_TOKEN_URL, $keysArr);
	    $response = $_newauth->get_contents($token_url);
	    
	    if(strpos($response, "callback") !== false){
	    
	        $lpos = strpos($response, "(");
	        $rpos = strrpos($response, ")");
	        $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
	        $msg = json_decode($response);
	    
	        if(isset($msg->error)){
	            $_newauth->showError($msg->error, $msg->error_description);
	        }
	    }
	    
	    $params = array();
	    parse_str($response, $params);

	    $this->_auth->write("access_token", $params["access_token"]);
	    $access_token = $params["access_token"];
	    $open_id = $_newauth->get_openid();
	    
	    $utype ='member';
	    $authed = $this -> _auth -> certification($open_id, $open_id, array('utype' => $utype));
	    if($authed === true){
	        
	    }else{
	        $qq_info_url=Shop_Models_API_Auth::GET_USER_INFO."?access_token=".$access_token."&oauth_consumer_key=".$qq_params['appid']."&openid=".$open_id."&format=json";
	        $response=$_newauth->get_contents($qq_info_url);
	        $return = json_decode($response);
	        
	        if($return->gender=="男"){
	            $sex=1;
	        }else if($return->gender=="女"){
	            $sex=2;
	        }else{
	            $sex=0;
	        }
	        $reg_datas=array("user_name"=>$open_id,
	                "password"=>$open_id,
	                "confirm_password"=>$open_id,
	                "nick_name"=>$return->nickname,
	                "share_id"=>"8990",
	                "parent_id"=>"8990",
	                "parent_user_name"=>"QQ联合登录",
	                "sex"=>$sex
	                );
	        $authed = $this -> _auth ->register($reg_datas,true);
	        $utype ='member';
	        $authed = $this -> _auth -> certification($open_id, $open_id, array('utype' => $utype));
	    }
	    //获取QQ账号信息
	    
	    header("Location:http://www.1jiankang.com");
	    exit;
	}
	/**
	 * 新浪 联合登录回调函数
	 */
	public function weibocallbackAction(){
	    if (isset($_REQUEST['code'])) {
        	$keys = array();
        	$keys['code'] = $_REQUEST['code'];
        	$keys['redirect_uri'] = "http://www.1jiankang.com/auth/weibocallback";
	        try {
		        $token = $this -> _auth ->getAccessToken( 'code', $keys ) ;
	        } catch (Exception $e) {
	        }
        }

        if ($token) {
    	    $_SESSION['token'] = $token;
    	    setcookie( 'weibojs_'.$this -> _auth ->client_id, http_build_query($token) );
    	    
    	    $uid_get = $this -> _auth->get('account/get_uid');
    	    $uid = $uid_get['uid'];
    	    
    	    $open_id="weibo".$uid;
    	    $utype ='member';
    	    $authed = $this -> _auth -> certification($open_id, $open_id, array('utype' => $utype));
    	    if($authed === true){
    	         
    	    }else{
    	        $params=array();
    	        if ( $uid !== NULL ) {
    	            $this -> _auth-> id_format($uid);
    	            $params['uid'] = $uid;
    	        }
    	        $return = $this -> _auth ->get('users/show', $params );
    	        $reg_datas=array("user_name"=>$open_id,
    	                "password"=>$open_id,
    	                "confirm_password"=>$open_id,
    	                "nick_name"=>$return['name'],
    	                "parent_id"=>"8998",
    	                "parent_user_name"=>"新浪微博 联合登录",
    	                "share_id"=>"8998"
    	        );
    	        $authed = $this -> _auth ->register($reg_datas,true);
    	        $utype ='member';
    	        $authed = $this -> _auth -> certification($open_id, $open_id, array('utype' => $utype));
    	    }
        } else {
            echo "验证失败！！！";
        }
        header("Location:http://www.1jiankang.com");
	    exit;
	}
	/**
	 * 开心网 联合登录
	 */
	public function kaixinloginAction(){
	    $_SESSION['state'] = md5(uniqid(rand(), TRUE));
	    $_kaixinapi=new Shop_Models_API_Kaixin();
	    $code_url = $_kaixinapi->getAuthorizeURL( $_kaixinapi->kaixin_params['appkey'],$_kaixinapi->kaixin_params['callback'], 'code', $_SESSION['state']);
	    header("Location:$code_url");
	}
	/**
	 * 开心网 联合登录回调函数
	 */
	public function kaixincallbackAction(){
	    $_kaixinapi=new Shop_Models_API_Kaixin();
        if (isset($_REQUEST['code']) && $_REQUEST['state'] == $_SESSION['state']) {
        	$keys = array();
        	$keys['code'] = $_REQUEST['code'];
        	$keys['redirect_uri'] = $_kaixinapi->kaixin_params['callback'];
        	try {
        		$token = $_kaixinapi->getAccessToken( 'code', $keys ) ;
        	} catch (Exception $e) {
        	}
        }
        
        if ($token) {
        	$_SESSION['token'] = $token;
        	$fields = $_REQUEST['fields'];
        	$params = array();
        	$params['fields'] = $fields;
        	$return=$_kaixinapi->get('users/me', $params);
        	$open_id="kaixin".$return['uid'];
        	$utype ='member';
        	$authed = $this -> _auth -> certification($open_id, $open_id, array('utype' => $utype));
        	if($authed === true){
        	
        	}else{
        	    $reg_datas=array("user_name"=>$open_id,
        	            "password"=>$open_id,
        	            "confirm_password"=>$open_id,
        	            "nick_name"=>$return['name'],
        	            "parent_id"=>"9001",
        	            "parent_user_name"=>"开心网 联合登录",
        	            "share_id"=>"9001"
        	    );
        	    $authed = $this -> _auth ->register($reg_datas,true);
        	    $utype ='member';
        	    $authed = $this -> _auth -> certification($open_id, $open_id, array('utype' => $utype));
        	}
  
        } else {
            echo "验证登录失败";
        }
       header("Location:http://www.1jiankang.com");
	   exit;
	}
	/**
	 *淘宝联合登录 callback
	 */
    public function taobaocallbackAction(){
        $_taobao=new Shop_Models_API_TaoBao();
        $code = $_REQUEST['code'];   //通过访问https://oauth.taobao.com/authorize获取code
        $grant_type = 'authorization_code';
        $redirect_uri = 'http://www.1jiankang.com/auth/taobaocallback';  //此处回调url要和后台设置的回调url相同
        $client_id = '21573157';//自己的APPKEY
        $client_secret = '88f5916e1b19042f34ef50c8371a1e33';//自己的appsecret
        $postfields= array('grant_type'     => $grant_type,
                'client_id'     => $client_id,
                'client_secret' => $client_secret,
                'code'          => $code,
                'redirect_uri'  => $redirect_uri
        );
        $url = 'https://oauth.taobao.com/token';
        $token = json_decode($_taobao->curl($url,$postfields));
        $open_id="taobao".$token->taobao_user_id;
        $nick_name=urldecode($token->taobao_user_nick);
        $utype ='member';
    	$authed = $this -> _auth -> certification($open_id, $open_id, array('utype' => $utype));
    	if($authed === true){
    	         
        }else{
    	        $reg_datas=array("user_name"=>$open_id,
    	                "password"=>$open_id,
    	                "confirm_password"=>$open_id,
    	                "nick_name"=>$nick_name,
    	                "parent_id"=>"9024",
    	                "parent_user_name"=>"淘宝 联合登录",
    	                "share_id"=>"9024"
    	        );
    	        $authed = $this -> _auth ->register($reg_datas,true);
    	        $utype ='member';
    	        $authed = $this -> _auth -> certification($open_id, $open_id, array('utype' => $utype));
        }
        header("Location:http://www.1jiankang.com");
        exit;
    }
    /**
     *网易 联合登录 callback
     */
    public function wangyicallbackAction(){
        $_taobao=new Shop_Models_API_TaoBao();
        $code = $_REQUEST['code'];   //通过访问https://oauth.taobao.com/authorize获取code
        $grant_type = 'authorization_code';
        $redirect_uri = 'http://www.1jiankang.com/auth/wangyicallback';  //此处回调url要和后台设置的回调url相同
        $client_id = '7449660002';//自己的APPKEY
        $client_secret = '3f0db1325f0e622dfee85850f51e0ff7';//自己的appsecret
        $postfields= array('grant_type'     => $grant_type,
                'client_id'     => $client_id,
                'client_secret' => $client_secret,
                'code'          => $code,
                'redirect_uri'  => $redirect_uri
        );
        $url = 'http://reg.163.com/open/oauth2/token.do';
        $token = json_decode($_taobao->curl($url,$postfields));
        $access_token=$token->access_token;
        $postf= array('access_token'     => $access_token);
        $url="https://reg.163.com/open/oauth2/getUserInfo.do";
        $return=$this->_auth->get($url,$postf);
        $open_id="wangyi".$return['userId'];
        $nick_name=$return['username'];
        $utype ='member';
    	$authed = $this -> _auth -> certification($open_id, $open_id, array('utype' => $utype));
    	if($authed === true){
    	         
        }else{
    	        $reg_datas=array("user_name"=>$open_id,
    	                "password"=>$open_id,
    	                "confirm_password"=>$open_id,
    	                "nick_name"=>$nick_name,
    	                "parent_id"=>"9029",
    	                "parent_user_name"=>"网易 联合登录",
    	                "share_id"=>"9029"
    	        );
    	        $authed = $this -> _auth ->register($reg_datas,true);
    	        $utype ='member';
    	        $authed = $this -> _auth -> certification($open_id, $open_id, array('utype' => $utype));
        }
        header("Location:http://www.1jiankang.com");
        exit;
    }
    
    /**
     * 下载同步头像文件
     */
    public function downloadAction(){
        $imgUrl=$this -> _request -> getParam("imgUrl");
        $arrLit=explode("/", $imgUrl);
        if(strrpos($arrLit[2],"192.168.2")!=0){
            exit;
        }else{
            $filename=$arrLit[count($arrLit)-1];
            $cmi=new Custom_Model_Image();
            $cmi->grabImage($imgUrl, "upload/avatar/".$filename);
            exit;
        }
       
    }
    
    public function verifyemailAction()
    {
    	$code = $this -> _request ->getParam('code');
    	if(!$code)
    	{
    		Custom_Model_Message::showAlert('参数错误',true,'/');
    	}
    	
    	$str =  Custom_Model_Encryption::getInstance() -> decrypt($code,'EncryptCode');
    	$arr =  explode('#', $str);
    	$uid= $arr[0];
    	$email = $arr[1];
    	
    	
    	$memberSeverice =  new Shop_Models_API_Member();
    	$userInfo = $memberSeverice->getMemberByUserId($uid);
    	
    	if($userInfo['ischecked'])
    	{
    		Custom_Model_Message::showAlert('邮箱已验证无需重复验证',true,'/');
    	}
    	
    	if($userInfo['email'] == $email ){
    		$memberSeverice->editchecked($uid);
    		Custom_Model_Message::showAlert('邮箱验证成功',true,'/');    		
    	}else{
    		Custom_Model_Message::showAlert('邮箱验证失败',true,'/');
    	}    	
    	exit();
    }
    
    //手机号码验证
   public function sendCheckSmsAction()
   {   	
   	  if($_SESSION['mobile_code_num']>3)
   	  {
   	  	echo Zend_Json::encode(array('status'=>0,'msg'=>'发送次数超过3次，请30分钟后重试'));
   	  	exit();
   	  }
   	     	
   	  $mobile = trim($this -> _request ->getParam('mobile'));
   	  if(!Custom_Model_Check::isMobile($mobile))
   	  {
   	  	echo Zend_Json::encode(array('status'=>0,'msg'=>'手机格式不正确！'));
   	  	exit();
   	  }
   	  
   	  $authAPi = Shop_Models_API_Auth :: getInstance();
   	  $user = $authAPi -> getAuth();
   	  
   	  if ($user && $user['mobile'] == $mobile && $user['check_mobile'] == 1) {
   	  	echo Zend_Json::encode(array('status'=>0,'msg'=>'手机号没有更改','reflash'=>1));
   	  	exit();
   	  }
   	  
   	  $authMobile = new Custom_Model_AuthImage('mobile');
   	  $code =  $authMobile->createCode();
   	  
   	  $sms= new  Custom_Model_Sms();
   	  $response = $sms->send($mobile,"您的手机验证码：{$code}，请勿将验证码告知他人【垦丰】");
   	  if ($response) {
   	  	$_SESSION['mobile_code_num'] += 1; 
   	  	echo Zend_Json::encode(array('status'=>1,'msg'=>'验证码发送成功，请查收。','code'=>$code));
   	  	exit();
   	  }else{
   	  	echo Zend_Json::encode(array('status'=>0,'msg'=>'发送失败，请重试！'));
   	  	exit();
   	  }
   	  
   }  
   
	//支付宝共享登录返回、
	public function  alipayloginreturnAction(){
			$this -> _helper -> viewRenderer -> setNoRender();
			//计算得出通知验证结果
			$alipayNotify = new Custom_Model_Alipay_Notify($this->aliapy_config);
			$verify_result = $alipayNotify->verifyReturn();

			if($verify_result) {
				//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
				$user_id	= $_GET['user_id'];	//支付宝用户id
                $real_name	= $_GET['real_name'];	//支付宝用户姓名或者昵称
				$_SESSION['token'] = $_GET['token'];	//授权令牌

				//执行商户的业务程序
				# 支付宝登录处理
				$pwd = md5($user_id);
                $user=array_shift($this -> _auth->getUserByName($user_id));
				if($user){
                    //更新昵称
                   $_dbMember = new Shop_Models_DB_Member();
                   $_dbMember -> upNickname($real_name,$user['user_id']);
				   $this -> _auth->certification($user_id, $pwd , array('utype' => 'member'));
                    //etao专用跳转
                    if($_GET['target_url']!='') {
                       $this->_redirect(urldecode($_GET['target_url']));
                       exit;
                    }else{
                    	//跳转到业务页面
					    $this->_redirect("http://{$_SERVER['HTTP_HOST']}/member");
                    }
				}else{
                    $outReg = $this -> _auth -> outerRegister(array('user_name' => $user_id, 'nick_name' => $real_name, 'password' => $pwd, 'authType' => 'alipay'));
                    if($outReg =='addUserSucess'){
                        //etao专用跳转
                        if($_GET['target_url']!='') {
                           $this->_redirect(urldecode($_GET['target_url']));
                           exit;
                        }
                        //跳转到业务页面
                        $this->_redirect("http://{$_SERVER['HTTP_HOST']}/member");
                    }else {
                        exit('<script>alert("该用户名已经在注册过，不能通过支付宝登陆，请直接登陆！\n 如果您忘记了密码，请使用您登陆的邮箱地址将密码找回！");window.location="http://' . $_SERVER['HTTP_HOST'] . '/auth/login/goto/'.base64_encode(urlencode(strip_tags($this -> _request -> getParam('url')))).'";</script>');
                    }
                }
			}
			else {
				echo "验证失败";
			}
	}
	//支付宝共享登录
	public function alipayloginAction(){
			Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);  
			$this -> _helper -> viewRenderer -> setNoRender();
			//防钓鱼时间戳
			$anti_phishing_key  = '';
			$exter_invoke_ip = '';
			//构造要请求的参数数组
			$parameter = array(
					"service"			=> "alipay.auth.authorize",
					"target_service"	=> 'user.auth.quick.login',
					"partner"			=> trim($this->aliapy_config['partner']),
					"_input_charset"	=> trim(strtolower($this->aliapy_config['input_charset'])),
					"return_url"		=> trim($this->aliapy_config['return_url']),
					"anti_phishing_key"	=> $anti_phishing_key,
					"exter_invoke_ip"	=> $exter_invoke_ip,
                    "token"	=> ''
			);
			//构造快捷登录接口
			$alipayService = new Custom_Model_Alipay_Service($this->aliapy_config);
			$html_text = $alipayService->alipay_auth_authorize($parameter);
			echo $html_text;
	}

}