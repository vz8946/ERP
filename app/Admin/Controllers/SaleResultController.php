<?php
class Admin_SaleResultController extends Zend_Controller_Action
{

	/**
     * 对象初始化
     *
     * @return   void
     */
	
	private $_page_size = '20';

	public function init()
	{
		$this ->_sale_result = new Admin_Models_API_SaleResult();
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

        $count  = $this->_sale_result->getCount($params);
		$infos = array();
		$total_counts = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_sale_result->browse($params, $limit);
			if ($params['collect']) {
				$total_counts = $this->_sale_result->getTotalCounts($params);
			}
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this->view->pageNav       =$pageNav->getNavigation();
        $this->view->infos         = $infos;
		$this->view->total_counts  = $total_counts;
		$this -> view ->params     = $params;
		$this->view->search_option = $this->_sale_result->getSearchOption();

        
    }

	/**
     * 销售数量结算
     *
     * @return void
     */
	public function clearSaleAction()
	{
		if ($this->_request->isPost()) {
			$params = $this->_request->getPost();

			if (false === $this->_sale_result->dealSaleBySaleId($params['sale_result_id'], $params)) {
				Custom_Model_Message::showMessage($this->_sale_result->getError());
			}

			Custom_Model_Message::showMessage('结算完成', 'event', 1250, "Gurl('refresh')");
		}

		$id = $this->_request->getParam('sale_result_id', 0);

		if ($id < 1) {
			Custom_Model_Message::showMessage('ID不正确');
		}

		$info = $this->_sale_result->get($id);

		$info['need_deal_number'] = $info['number'] - $info['deal_number'];

		$log  = $this->_sale_result->getLogByResultId($info['sale_result_id']);
		$this->view->info    = $info;
		$this->view->log_inf = $log;
	}
}