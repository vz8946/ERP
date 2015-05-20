<?php
class Admin_SaleReportController extends Zend_Controller_Action
{

	/**
     * 对象初始化
     *
     * @return   void
     */

	public function init()
	{
		$this ->_sale_report = new Admin_Models_API_SaleReport();
	}

    /**
     * 信息列表
     *
     * @return void
     */
    public function indexAction()
    {
		$params = $this->_request->getParams();
	
        if($params['type'] == '2'){
		   $this->view->is_view  = 1;
		}

		if (empty($params['end_ts'])) {
			$params['start_ts'] = date('Y-m-d', strtotime(date('Y-m-d'). "- 2 months"));
			$params['end_ts']   = date('Y-m-d');
		}
		$infos = array();
		if (date('Ym',strtotime(date('Y-m-d', strtotime($params['end_ts']. "- 2 months")))) >  date('Ym', strtotime($params['start_ts']))) {
			Custom_Model_Message::showAlert('请选择两个月时间内的日期查询',false);
		} else {
            if ($params['query']) {
			    $infos = $this->_sale_report->browse($params);
            }
		}
        $this->view->infos         = $infos;
		$this -> view ->params     = $params;
		$this->view->search_option = $this->_sale_report->getSearchOption();

        
    }
}