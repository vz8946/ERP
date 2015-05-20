<?php

class Admin_WarehouseController extends Zend_Controller_Action 
{
	private $_page_size = 20;
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Warehouse();
		$this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
	}
	
	/**
     * 虚拟商品列表
     *
     * @return void
     */
    public function indexAction()
    {
    	$params = $this->_request->getParams();
    	$page = (int)$this->_request->getParam('page', 1);
        $count = $this->_api->getCount($params);
        
        if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size.",". $this->_page_size;
        	$infos = $this->_api->browse($params, $limit);
        }
        
        $this->view->infos = $infos;
        $pageNav = new Custom_Model_PageNav($count, $this->_page_size);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }

	/**
	 * 添加仓库
	 *
	 * @return void
	 */
	public function addAction() {
		if ($this->_request->isPost()) {
			$this->_helper->viewRenderer->setNoRender();
			if (false === $this->_api->add($this->_request->getPost())) {
				Custom_Model_Message::showAlert($this->_api->getError());
			}
			
			Custom_Model_Message::showAlert("添加成功",true, '/admin/warehouse/index', 0,0);
		}
echo '<pre>';
		print_r($this->_api->getSearchOption());

		$this->view->search_option = $this->_api->getSearchOption();
	}

	/**
	 * 编辑仓库
	 *
	 * @return void
	 */
	public function editAction() {
		if ($this->_request->isPost()) {
			$this->_helper->viewRenderer->setNoRender();
			$params  = $params = $this->_request->getPost();
			if (false === $this->_api->edit($params['warehouse_id'], $params)) {
				Custom_Model_Message::showAlert($this->_api->getError());
			}
			
			Custom_Model_Message::showAlert("编辑成功",true, '/admin/warehouse/index', 0,0);
		}

		$warehouse_id = $this->_request->getParam('warehouse_id');
		if (empty($warehouse_id)) {
			Custom_Model_Message::showAlert('仓库不正确', true, -1);
		}

		$info = $this->_api->get($warehouse_id);
		$this->view->info = $info;

		$this->view->address       = array(
			'province_option' => $this->_api->getAreaInfosByParentId(1),
			'city_option' => $this->_api->getAreaInfosByParentId($info['province_id']),
			'district_option' => $this->_api->getAreaInfosByParentId($info['city_id']),
		);
		$this->view->search_option = $this->_api->getSearchOption();
	}
}