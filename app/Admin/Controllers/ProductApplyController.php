<?php
class Admin_ProductApplyController extends Zend_Controller_Action
{

	/**
     * 对象初始化
     *
     * @return   void
     */
	
	private $_page_size = '20';

	public function init()
	{
		$this ->_api = new Admin_Models_API_ProductApply();
	}

    /**
     * 信息列表
     *
     * @return void
     */
    public function indexAction()
    {
        $page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
        $count  = $this->_api->getCount($params);
		
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_api->browse($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav =$pageNav->getNavigation();
        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
		$this->view->search_option = $this->_api->getSearchOption();   
    }

	/**
     * 添加价格申请数据
     *
     * @return void
     */
	public function addAction()
	{
		if ($this->_request->isPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$params = $this->_request->getPost();
			if (count($params['product_id']) < 1 && count($params['group_id']) < 1) {
				Custom_Model_Message::showMessage('没有添加的商品', '/admin/product-apply/add');
			}
			if (false === $this->_api->add($params)) {
				Custom_Model_Message::showMessage($this->_api->getError(), '/admin/product-apply/add');
			}

			Custom_Model_Message::showMessage('操作成功', '/admin/product-apply/add');
		}

		$this->view->search_option = $this->_api->getSearchOption();
	}

	/**
     * 修改价格申请数据
     *
     * @return void
     */
	public function editAction()
	{
		$params = $this->_request->getParams();
		if ($this->_request->isPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$params = $this->_request->getPost();
			if (intval($params['apply_id']) < 1) {
				Custom_Model_Message::showAlert('申请ID不正确', true, -1);
			}
			if (false === $this->_api->edit($params['apply_id'], $params)) {
				Custom_Model_Message::showAlert($this->_api->getError(), true, -1);
			}

			Custom_Model_Message::showAlert('操作成功', true, -1);
		}

		$info = $this->_api->get($params['apply_id']);

		$this->view->info = $info;
	}

	/**
	 *  产品限价汇总查询
	 *
	 * @return    void
	 **/
	public function collectPriceListAction()
	{
		$info = $this->_api->getProductLimitPriceTotal();

		$this->view->info = $info;
	}

	/**
	 *  订单限价汇总查询
	 *
	 * @return    void
	 **/
	public function collectOrderLimitpriceAction()
	{
		$info = $this->_api->getOrderLimitPriceTotal();

		$this->view->info = $info;
	}

    /**
	 *  超限价订单审核记录
	 *
	 * @return    void
	 **/
    public function orderPricelimitLogAction()
    {
        $page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
        $count  = $this->_api->getPricelimitLogCount($params);
		
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_api->getPricelimitLogs($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav =$pageNav->getNavigation();
        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
		$this->view->search_option = $this->_api->getSearchOption();
    }

}