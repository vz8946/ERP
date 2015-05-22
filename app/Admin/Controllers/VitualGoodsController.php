<?php

class Admin_VitualGoodsController extends Zend_Controller_Action 
{
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_VitualGoods();
		$this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
	}
	
	/**
     * 虚拟商品列表
     *
     * @return void
     */
    public function listAction()
    {
    	$search = $this -> _request -> getParams();
    	$page = (int)$this -> _request -> getParam('page', 1);
        $datas = $this -> _api -> getVitualGoods($search, $page, 25);
        
        if ($datas) {
        	
        }
        
        $this -> view -> datas = $datas;
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($this -> _api -> getVitualGoodsCount($search), 25);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    
}