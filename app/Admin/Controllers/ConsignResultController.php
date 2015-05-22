<?php
class Admin_ConsignResultController extends Zend_Controller_Action
{

	/**
     * 对象初始化
     *
     * @return   void
     */
	
	private $_page_size = '20';

	public function init()
	{
		$this ->_consign_result = new Admin_Models_API_ConsignResult();
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

        $count  = $this->_consign_result->getCount($params);
		$infos = array();
		$total_counts = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_consign_result->browse($params, $limit);
			if ($params['collect']) {
				$total_counts = $this->_consign_result->getTotalCounts($params);
			}
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this->view->pageNav       =$pageNav->getNavigation();
        $this->view->infos         = $infos;
		$this->view->total_counts  = $total_counts;
		$this -> view ->params     = $params;
		$this->view->search_option = $this->_consign_result->getSearchOption();

        
    }

	/**
     * 数量结算
     *
     * @return void
     */
	public function clearConsignAction()
	{
		if ($this->_request->isPost()) {
			$params = $this->_request->getPost();

			if (false === $this->_consign_result->dealConsignByConsignId($params['consign_result_id'], $params)) {
				Custom_Model_Message::showMessage($this->_consign_result->getError());
			}

			Custom_Model_Message::showMessage('结算完成', 'event', 1250, "Gurl('refresh')");
		}

		$id = $this->_request->getParam('consign_result_id', 0);

		if ($id < 1) {
			Custom_Model_Message::showMessage('ID不正确');
		}

		$info = $this->_consign_result->get($id);

		$info['need_deal_number'] = $info['number'] - $info['deal_number'];

		$log  = $this->_consign_result->getLogByResultId($info['consign_result_id']);
		$this->view->info    = $info;
		$this->view->log_inf = $log;
	}
}