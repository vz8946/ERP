<?php
class Admin_PerformanceController extends Zend_Controller_Action 
{
    /**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Performance();
	}

	/**
     * 电话下单查询
     *
     * @return   void
     */
    public function telOrderAction()
    {
        $this -> view -> param = $this -> _request -> getParams();
		$_adminApi = new Admin_Models_API_Admin();
        $this -> view -> adminMessages = $_adminApi -> getAllAdmin(array('group_id'=>'2'));

        $page = (int)$this -> _request -> getParam('page', 1);
        $datas = $this -> _api -> getTelOrder($this -> _request -> getParams(),'a.operator_id,a.order_id,a.batch_sn,a.add_time,b.status,b.status_logistic,b.status_pay,b.status_return,b.addr_consignee,b.price_order,b.price_goods,b.price_pay,b.price_logistic,b.pay_name,b.addr_tel,b.addr_mobile,d.admin_id,d.real_name,d.group_id',null,$page, 25);
        $this -> _apiOrder = new Admin_Models_API_Order();
        foreach ($datas['list'] as $num => $data)
        {
            $datas['list'][$num]['add_time'] = ($datas['list'][$num]['add_time'] > 0) ? date('Y-m-d H:i:s', $datas['list'][$num]['add_time']) : '';
            $datas['list'][$num]['status'] = $this ->_apiOrder -> status('status', $data['status']);
            $datas['list'][$num]['status_return'] = $this ->_apiOrder -> status('status_return', $data['status_return']);
            $datas['list'][$num]['status_logistic'] = $this ->_apiOrder -> status('status_logistic', $data['status_logistic']);
            $datas['list'][$num]['status_pay'] = $this ->_apiOrder -> status('status_pay', $data['status_pay']);
        }
        $this -> view -> datas = $datas['list'];
        $this -> view -> totalPrice = $datas['totaldata']['totalPrice'];
        $pageNav = new Custom_Model_PageNav($datas['totaldata']['total'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }

	/**
     * 导出电话下单统计
     *
     * @return   void
     */
	public function exportTelOrderAction()
	{
	    $params = $this -> _request -> getParams();
	    $datas = $this -> _api -> getExportTelOrder($params);
	    $excel = new Custom_Model_GenExcel();
        $title = array('订单号','订单状态','下单时间','下单客服','收货人','金额','运费','支付方式');
        $lineArray[] = $title;
        if ( $datas ) {
            $this -> _apiOrder = new Admin_Models_API_Order();
            foreach ($datas as $num => $data)
            {
                $data['add_time'] = ($data['add_time'] > 0) ? date('Y-m-d H:i:s', $data['add_time']) : '';
                $data['status'] = $this ->_apiOrder -> status('status', $data['status']);
                $data['status_return'] = $this ->_apiOrder -> status('status_return', $data['status_return']);
                $data['status_logistic'] = $this ->_apiOrder -> status('status_logistic', $data['status_logistic']);
                $data['status_pay'] = $this ->_apiOrder -> status('status_pay', $data['status_pay']);

                $row = array($data['batch_sn'],
                    $data['status'].'-'.$data['status_pay'].'-'.$data['status_logistic'].'-'.$data['status_return'],
                    $data['add_time'],
                    $data['real_name'],
                    $data['addr_consignee'],
                    $data['price_pay'],
                    $data['price_logistic'],
                    $data['pay_name']
                    );
                $lineArray[] = $row;
                unset($row);
            }
            unset($datas);
            $excel -> addArray ($lineArray);
            $excel -> generateXML ('external-tel-order');
        }
        exit;
	}


}
