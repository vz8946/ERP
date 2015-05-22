<?php
class Admin_CustomerController extends Zend_Controller_Action
{

	/**
     * 对象初始化
     *
     * @return   void
     */
	
	private $_page_size = '20';

	public function init()
	{
		$this ->_api = new Admin_Models_API_Customer();
	}

    /**
     * 客户列表
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
     * 客户订购产品详情
     *
     * @return void
     */
	public function customerProductAction()
	{
		$customer_id = $this->_request->getParam('customer_id', 0);
		if (empty($customer_id)) {
			exit('客户ID不正确');
		}
		
		$customer_info = $this->_api->get($customer_id);

		if (empty($customer_info)) {
			exit('没有客户信息信息');
		}

		$page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
        $count  = $this->_api->getCustomerProductCount($params);
		
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_api->browseCustomerProducts($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav =$pageNav->getNavigation();
        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
		$this->view->search_option = $this->_api->getSearchOption();

		$this->view->customer_info = $customer_info;
	}

	/**
     * 客户订购订单详情
     *
     * @return void
     */
	public function customerOrderAction()
	{
		$customer_id = $this->_request->getParam('customer_id', 0);
		if (empty($customer_id)) {
			exit('客户ID不正确');
		}
		
		$customer_info = $this->_api->get($customer_id);

		if (empty($customer_info)) {
			exit('没有客户信息信息');
		}

		$page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
        $count  = $this->_api->getCustomerOrderCount($params);
		
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_api->BrowseCustomerOrderInfos($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav =$pageNav->getNavigation();
        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
		$this->view->search_option = $this->_api->getSearchOption();

		$this->view->customer_info = $customer_info;
	}

	/**
     * 根据产品编码查客户信息
     *
     * @return void
     */

	public function productCustomerAction()
	{
		$infos = array();

		$params = $this->_request->getParams();
		if (!empty($params['product_sn'])) {
            !empty($params['start_ts']) && $params['start_time'] = strtotime($params['start_ts']);
            !empty($params['end_ts'])   && $params['end_time']   = strtotime($params['end_ts']. ' 23:59:59');
			$infos = $this->_api->getProductCustomerByProductSn($params['product_sn'], $params);
			if ($params['export']) {
				$title[] = array('客户ID', '客户姓名', '手机', '电话', '省份', '店铺', '产品编码', '产品名称', '购买次数(单数)', '购买数量(产品数)');
                $customer_infos = array();
                foreach ($infos as $info) {
                    $customer_infos[] = array(
                        'customer_id'   => $info['customer_id'],
                        'real_name'     => $info['real_name'],
                        'mobile'        => $info['mobile'],
                        'telphone'      => $info['telphone'],
                        'province_name' => $info['province_name'],
                        'shop_name'     => $info['shop_name'],
                        'product_sn'    => $info['product_sn'],
                        'goods_name'    => $info['goods_name'],
                        'buy_count'     => $info['buy_count'],
                        'number'        => $info['buy_number'],
                    );
                }
				$infos = array_merge($title, $customer_infos);
				$xls = new Custom_Model_GenExcel();
				$xls -> addArray($infos);
				$xls -> generateXML('product-customer-'.date('Y-m-d'));
				exit();
			}
		
		}

		$this->view->search_option = $this->_api->getSearchOption();
		$this->view->infos = $infos;

		$this->view->params = $params;
	}

    /**
     * 客户购买统计
     *
     * @return void
     */
    public function customerBuyAction()
    {
        $infos = array();

		$params = $this->_request->getParams();
		if (!empty($params['times_start']) || !empty($params['times_end'])) {
			    $infos = $this->_api->getCustomerOrderCountByCondition($params);
		}
		$this->view->search_option = $this->_api->getSearchOption();
		$this->view->infos = $infos;

		$this->view->params = $params;
    }

}