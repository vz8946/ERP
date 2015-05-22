<?php
class Admin_BoxController extends Zend_Controller_Action
{

	/**
     * 对象初始化
     *
     * @return   void
     */
	
	private $_page_size = '15';

	public function init()
	{
		$this ->_api = new Admin_Models_API_Box();
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
		if ($params['export']) {
			$infos = $this->_api->getAllBoxProducts($params);
			$title[] = array('箱号', '备注', '产品条码', '产品编码', '产品名称', '数量');
			$infos = array_merge($title, $infos);
			$xls = new Custom_Model_GenExcel();
			$xls -> addArray($infos);
			$xls -> generateXML('box-'.date('Y-m-d'));
			exit();
		}

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
     * 添加箱子
     *
     * @return void
     */
	public function addAction()
	{
		if ($this->_request->isPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$params = $this->_request->getPost();
			if (false === $this->_api->add($params)) {
				Custom_Model_Message::showAlert($this->_api->getError(), true, -1);
			}
			Custom_Model_Message::showAlert('操作成功', true, '/admin/box/index', 0, 0);
		}
		$this->view->search_option = $this->_api->getSearchOption();
	}

	/**
     * 添加箱子商品
     *
     * @return void
     */
	public function addProductAction()
	{
		if ($this->_request->isPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$params = $this->_request->getPost();
			if (intval($params['box_id']) < 1) {
				Custom_Model_Message::showAlert('箱子ID不正确', true, -1);
			}

			if (false === $this->_api->addProduct($params['box_id'], $params)) {
				Custom_Model_Message::showAlert($this->_api->getError(), true, -1);
			}
			Custom_Model_Message::showAlert('操作成功', true, -1);
		}

		$params = $this->_request->getParams();
		$info = $this->_api->get($params['box_id']);

		list($product_count, $sum_product) = $this->_api->getProductCountsByBoxid($params['box_id']);

		$products = array();
		if ($product_count > 0) {
			$products = $this->_api->getProductInfosByBoxid($params['box_id']);
		}
		$this->view->product_count = $product_count;
		$this->view->sum_product   = $sum_product;
		$this->view->info          = $info;
		$this->view->products      = $products;
		$this->view->search_option = $this->_api->getSearchOption();
	}

	public function delBoxAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$box_id = $this->_request->getParam('box_id', 0);
		if (intval($box_id) < 1) {
			Custom_Model_Message::showAlert('箱子ID不正确', true, -1);
		}

		if (false === $this->_api->deleteBox($box_id)) {
			Custom_Model_Message::showAlert($this->_api->getError(), true, -1);
		}

		Custom_Model_Message::showAlert('操作成功', true, '-1');
	}

	public function printProductAction()
	{
		$box_id = $this->_request->getParam('box_id', 0);
		if (intval($box_id) < 1) {
			Custom_Model_Message::showAlert('箱子ID不正确', true, -1);
		}

		$product_infos = $this->_api->getProductInfosByBoxid($box_id);
		$info          = $this->_api->get($box_id);

		list($product_count, $sum_product) = $this->_api->getProductCountsByBoxid($box_id);

		$this->view->product_infos  = $product_infos;
		$this->view->info           = $info;
		$this->view->product_count  = $product_count;
		$this->view->sum_product    = $sum_product;
	}
	public function getAjaxProductAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$barcode = $this->_request->getParam('barcode', '');
		$box_id  = $this->_request->getParam('box_id', 0);
		if (trim($barcode) == '') {
			exit(json_encode(array('success' => 'false', 'message' => '条形码不能为空')));
		}

		$product_db   = new Admin_Models_DB_Product();
		$product_info = $product_db->getProductInfoByBarcode($barcode);

		if (empty($product_info)) {
			//exit(json_encode(array('success' => 'false', 'message' => '没有找到相关商品')));
			$product_info = array('product_id' => 0, 'product_name' => '');
			exit(json_encode(array('success' => 'true', 'data' => $product_info)));
		}

		/*$box_product = $this->_api->getProductInfoByProductid($product_info['product_id']);
		if (!empty($box_product) && $box_id != $box_product['box_id']) {
			exit(json_encode(array('success' => 'false', 'message' => '扫描的商品已经在'. $box_product['box_sn'] .'箱子中')));
		}*/
		exit(json_encode(array('success' => 'true', 'data' => $product_info)));
	}
}