<?php
/*
 * Created on 2013-5-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Admin_SjtController extends Custom_Controller_Action_Grid {
     
    /**
     * 
     * @var Admin_Models_API_Sjt
     */ 
 	private $api  = null;
 	
 	//初始化对象
 	public function init()
	{
	    Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	    
		$this ->api = new Admin_Models_API_Sjt();
		
		$this->finder = new Custom_Finder_Sjt();

		$this->view->params = $this->_request->getParams();
		
	}
	
	public function addAction(){
		
	}

 }

