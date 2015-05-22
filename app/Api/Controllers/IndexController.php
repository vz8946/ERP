<?php
class IndexController extends Zend_Controller_Action
{

	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
      Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	  $this -> _helper -> viewRenderer -> setNoRender();
	}
		
	/**
     * 首页
     *
     * @return void
     */
	public function indexAction()
	{
      exit('垦丰接口');


	}

}