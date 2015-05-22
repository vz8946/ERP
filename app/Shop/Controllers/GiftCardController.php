<?php
class GiftcardController extends Zend_Controller_Action
{
	
	public function init()
	{
		$this -> _api = new Shop_Models_API_Goods();
		$this -> _auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
		$this -> view -> auth = $this -> _auth;
		$this -> view -> u = $this -> _request -> getParam('u', '');
	
		if (!$this -> _auth) { //是否登录
			$this -> view -> login = 1;
		}
	
	}
	
	/**
	 * 1健康电子卡
	 */
	public  function  indexAction()
	{
		$pagenav = '';
		$page = $this->_request->getParam('page') ? (int)$this->_request->getParam('page') : 1;
		
		$search['cat_id'] = 345;
		$search['sort']   = 'price_a';
		
		$datas = $this->_api->getGoodsByPage($pagenav,$page,$search,4);
		if(isset($_SESSION['tmp_giftcard']) && $_SESSION['tmp_giftcard']){
	     $this->view->giftcard = $_SESSION['tmp_giftcard'];
		}
		
		$this->view->datas = $datas;
		$this->view->js_more=',check.js';
		$this->view->css_more = ',giftcard.css';
	}
	
	public function buyAction(){
		$this -> _helper -> viewRenderer -> setNoRender();
		$giftcard = array_filter($_POST['giftcard'],'check_gift_num');	
	
		if(!$giftcard)
		{	
			unset($_SESSION['tmp_giftcard']);
			Custom_Model_Message::showAlert('请选择垦丰卡！',true,-1);
		    exit();
		}
				
		$_SESSION['tmp_giftcard'] = $giftcard;
		if($this->_auth)
		{
			$url = "{$this -> getFrontController() -> getBaseUrl()}/flow/gift-card";
			header('location:'.$url);
		}else{
		  $goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/flow/gift-card');
          $url = "{$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto;
          Custom_Model_Message::showAlert('请先登录！',true,$url);
		}	
		exit;
	}
	
}

function check_gift_num($v)
{
	if ($v>0)
	{
		return true;
	}
	return false;
}