<?php
class BackendController extends Zend_Controller_Action
{
	private $_username = '国药阳光';
	private $_password = 'gyyg2013';
	private $_cookieName = 'backend_user';
	
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this->view->css_more=',backend.css';

	}
	
	public function indexAction()
	{
	    $this->_redirect("/backend/medical-card");
	}
	
	public function loginAction()
	{
	    setcookie($this -> _cookieName, $this -> _username, time() - 1800);
	    
	    if ($this -> _request -> isPost()) {
	        $result = $this -> auth();
	        if ($result) {
	            $this -> view -> error = $result;
	        }
	        else {
	            $this->_redirect("/backend/medical-card");
	        }
	    }
	}
	
	public function medicalCardAction()
	{
	    if ($this -> auth()) {
	        $this->_redirect("/backend/login");
	    }
	    
	    if ($this -> _request -> isPost()) {
    	    $vitualGoodsAPI = new Admin_Models_API_VitualGoods();
    	    $search = $this -> _request -> getParams();
    	    $search['status'] = array(1, 2, 9);
    	    $search['type'] = 1;
            $datas = $vitualGoodsAPI -> getVitualGoods($search);
            $this -> view -> datas = $datas ? $datas : 'empty';
        }
        
        $this -> view -> name = $this -> _username;
        $this -> view -> param = $search;
	}
	
	public function medicalCardListAction()
	{
	    if ($this -> auth()) {
	        $this->_redirect("/backend/login");
	    }
	    
	    $vitualGoodsAPI = new Admin_Models_API_VitualGoods();
	    
	    if ($this -> _request -> isPost()) {
    	    $search = $this -> _request -> getParams();
    	    $search['type'] = 1;
    	    !isset($search['status']) && $search['status'] = array(1, 2, 9);
    	    if ($search['sum']) {
    	        $datas = $vitualGoodsAPI -> sumVitualGoods($search);
    	    }
    	    else {
                $datas = $vitualGoodsAPI -> getVitualGoods($search);
            }
        }
        
        $this -> view -> productData = $vitualGoodsAPI -> getVitualProduct("t1.type = 1");
        $this -> view -> name = $this -> _username;
        $this -> view -> param = $search;
        $this -> view -> datas = $datas;
	}
	
	public function verifyAction()
	{
	    if ($this -> auth()) {
	        exit;
	    }
	    
	    $vitualGoodsAPI = new Admin_Models_API_VitualGoods();
	    if ($vitualGoodsAPI -> consume($this -> _request -> getParam('sn'))) {
	        die('ok');
	    }
	    
	    exit;
	}
	
	private function auth()
	{
	    if ($_COOKIE['backend_user'] == $this -> _cookieName) {
	        
	    }
	    else {
	        session_start();
    	    $params = $this -> _request -> getParams();
    	    $authImage = new Custom_Model_AuthImage('backendLogin');
    	    if (!$params['username']) {
    	        return 'empty username';
    	    }
    	    if (!$params['password']) {
    	        return 'empty password';
    	    }
    	    if (!$params['verify_code']) {
    	        return 'empty verify code';
    	    }
    	    if (!$authImage -> checkCode($params['verify_code'])) {
    	        return 'verify code invalid';
    	    }
    	    if ($params['username'] != $this -> _username || $params['password'] != $this -> _password) {
    	        return 'username or password invalid';
    	    }
    	}
    	
	    setcookie("backend_user", $this -> _cookieName, time() + 1800);
	}
	
}