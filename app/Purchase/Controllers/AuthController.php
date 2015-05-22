<?php
class AuthController extends Zend_Controller_Action
{
   /**
    * 
    * @var Purchase_Models_API_Auth
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
		$this -> _auth = Purchase_Models_API_Auth :: getInstance();
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
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
		if ($this -> _request -> isPost()) {
		    $username = $this -> _request -> getPost('user_name');
            $password = $this -> _request -> getPost('password');
            $authImage = new Custom_Model_AuthImage('shopLogin');
            $verifyCode  = $this->_request->getPost('verifyCode');
            
            $filterChain = new Zend_Filter();
            $filterChain -> addFilter(new Zend_Filter_StripTags());
            
            $username = $filterChain -> filter($username);
            $password = $filterChain -> filter($password);

            $utype = 'member';
            if (!$authImage -> checkCode($verifyCode)){
                echo json_encode(array('status'=>'vc-error'));exit;
            }
            
            $authed = $this -> _auth -> certification($username, $password, array('utype' => $utype));
            if($authed === true){
            	// 记住用户名
	            if ($this -> _request -> getPost('remUserName')) {
	                setcookie('m_name', $username, time() + 365*24*3600);
	            } else {
	                setcookie('m_name','',time()-3600);
	            }
	            echo json_encode(array('status'=>'yes'));exit;
            }else{
            	echo json_encode(array('status'=>'no'));exit;
            }
		} else {
            echo json_encode(array('status'=>'no'));exit;
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
                echo Zend_Json::encode(array('status'=>0,'msg'=>'用户名或密码错误！'));
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
        $this -> view -> page_title = "垦丰商城 - 精品种子商城";
        $this -> view -> page_keyword = "会员注册,种子";
        $this -> view -> page_description = '垦丰电商 -专业的种子商城! ';
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
                    $this -> _member = new Purchase_Models_API_Member();
                    $this -> _member -> editAccount($auth['member_id'], 'point', $tmp);
                    $this -> _auth -> updateAuth();
                    //注册赠送积分 结束

                    $this -> view -> auth = $auth;

				    //注册成功发邮件
				//	$this -> _auth -> sendRegisterEmail($auth);
					$this->view->username = $username;
                    $goto = base64_decode($this -> _request -> getParam('goto'));
                    $refer = base64_decode($this -> _request -> getPost('refer'));
                    $this->view->goto = $goto;
                    $this->view->refer = $refer;

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
        $this -> view -> page_description = '垦丰电商 -专业的种子商城! ';
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
		$this -> _helper -> viewRenderer -> setNoRender();
        $space = $this -> _request -> getParam('space');
        $authImage = new Custom_Model_AuthImage($space);
        $authImage -> createImage();
        exit;
	}
}