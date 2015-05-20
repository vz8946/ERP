<?php
class Admin_FinanceController extends Custom_Controller_Action_Grid
{
    private $_api = null;

  	const ADD_SUCCESS = '添加成功!';
	const CANCEL_SUCCESS = '申请取消成功!';
	const CHECK_SUCCESS = '审核成功!';
	const CHANGE_SUCCESS = '申请变更成功!';
	const CLEAR_SUCCESS = '结算成功!';

	public $mdl_name = 'finance';
	
	public function init()
    {
		$this -> _api = new Admin_Models_API_Finance();
		$this -> _apiOrder = new Admin_Models_API_Order();
        $this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
		
		$this->finder = new Custom_Finder_Refund();
		$this->mdl_name = 'finance';
		
	}

    /**
     * 新增应退款申请
     *
     * @return void
     */
    public function applyFinanceAction()
    {
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> applyFinance($this -> _request -> getPost());
        	if ($result) {
        	    $url = $this -> getFrontController() -> getBaseUrl() ."/admin/finance/pay";
        	    Custom_Model_Message::showMessage('成功', $url, 1250);
        	}
        	else {
        	    Custom_Model_Message::showMessage('错误', 'event', 1250, "failed()");
        	}
        }
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }

    /**
     * 应收账款
     *
     * @return void
     */
    public function incomeAction()
    {
        $search = $this->_request->getParams();
        $where = $search;
        $where['status'] = 0;
        $where['status_logistic'] = 1;
        $this -> view -> payment = $this -> _api -> getPayment(array('status' => 0));
        $data = $this -> _apiOrder -> getOrderBathWithPage($where, intval($this -> _request -> getParam('page', 1)));
        $this -> view -> data = $data['data'];
        $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> auth = $this -> _auth['admin_name'];
        $this -> view -> param = $search;
    }
    /**
     * 订单详细信息
     *
     * @return void
     */
    public function infoAction()
    {
        $financeID = $this -> _request -> getParam('finance_id', null);
        $finance = array_shift($this -> _api -> getFinance(array('finance_id' => $financeID)));
        $orderData = unserialize($finance['order_data']);
        $this -> view -> order = $order = $orderData['order'];
        $this -> view -> product = $orderData['product'];
        $finance = @array_shift($this -> _api -> getFinance(array('finance_id' => $financeID)));
        $this -> view -> bank = unserialize($finance['bank_data']);
        $this -> view -> blance = floatval($order['price_pay']) - (floatval($order['price_payed']) + floatval($order['price_from_return']));

    }
    /**
     * 订单详细信息
     *
     * @return void
     */
    public function orderAction()
    {
        $batchSN = $this -> _request -> getParam('batch_sn', null);
        $financeID = $this -> _request -> getParam('finance_id', null);
        $where = array('batch_sn' => $batchSN);
        $order = array_shift($this ->_apiOrder -> getOrderBatch($where));
        if (is_array($order) && count($order)) {
            $this -> view -> batchSN = $batchSN;
            $this -> view -> order = $order;
            $this -> view -> blance = floatval($order['price_pay']) - (floatval($order['price_payed']) + floatval($order['price_from_return']));
            $data = $this -> _apiOrder -> orderDetail($batchSN);
            $this -> view -> product = $data['product_all'];
            $this -> view -> noteStaff = $order['note_staff'];
            $finance = @array_shift($this -> _api -> getFinance(array('finance_id' => $financeID)));
            $this -> view -> bank = unserialize($finance['bank_data']);
        }
    }

    /**
     * 打印
     *
     * @return void
     */
    public function printAction()
    {
        $financeID = $this -> _request -> getParam('finance_id', null);
        $finance = array_shift($this -> _api -> getFinance(array('finance_id' => $financeID)));
        $orderData = unserialize($finance['order_data']);
        $this -> view -> order = $order = $orderData['order'];
        $this -> view -> product = $orderData['product'];
        $finance = @array_shift($this -> _api -> getFinance(array('finance_id' => $financeID)));
        $this -> view -> bank = unserialize($finance['bank_data']);
        $this -> view -> blance = floatval($order['price_pay']) - (floatval($order['price_payed']) + floatval($order['price_from_return']));
    }
    /**
     * 应付账款
     *
     * @return void
     */
    public function payAction()
    {
        $search = $this->_request->getParams();
        $search['status!='] = 5;
        
        if ($search['todo'] == 'export') {
            $content[] = array('店铺', '系统单号', '渠道单号', '退款金额', '退积分', '退账户余额', '退礼品卡', '发生时间', '退款类型', '退款方式', '退款状态');
            $datas = $this -> _api -> getFinanceWithPage($search);
            $status[0] = '待退货入库';
            $status[1] = '财务未审核';
            $status[2] = '财务已审核';
            $status[3] = '无效';
            $status[4] = '无效';
            foreach ($datas['data'] as $data) {
                $content[] = array($data['shop_name'], $data['item_no'], $data['external_order_sn'], $data['pay'], $data['point'], $data['account'], $data['gift'], $data['add_time'], $data['way'] == 4 ? '代收货款变更' : ($data['way'] == 5 ? '直供结算金额变更' : ($data['way'] == 6 ? '物流公司变更' : ($data['item'] == 1 ? '系统订单' : '外部店铺'))), $data['type'] == 1 ? '线下' : ($data['way'] == 1 ? '中间平台' : '我方账户'), $status[$data['status']]);
            }
            $xls = new Custom_Model_GenExcel();
            $xls -> addArray($content);
            $xls -> generateXML('finance-return');
            exit;
        }
        
        
        $data = $this -> _api -> getFinanceWithPage($search, intval($this -> _request -> getParam('page', 1)));
        $this -> view -> data = $data['data'];
        $this -> view -> total = $data['total'];
        $pageNav = new Custom_Model_PageNav($data['total']['count'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> param = $search;
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }

    /**
     * 审核通过
     *
     * @return void
     */
    public function passAction()
    {
        $financeID =  $this -> _request -> getParam('finance_id', null);
        $mod =  $this -> _request -> getParam('mod', null);
        $this -> _api -> updateFinance($financeID, array('status' => 2, 'admin_name' => $this -> _auth['admin_name'], 'check_time' => time()));
        $url = $this -> getFrontController() -> getBaseUrl() . "/admin/finance/{$mod}/";
        Custom_Model_Message :: showMessage('审核成功', $url);
    }
    /**
     * 审核无效
     *
     * @return void
     */
    public function invalidAction()
    {
        $financeID =  $this -> _request -> getParam('finance_id', null);
        $mod =  $this -> _request -> getParam('mod', null);
        $this -> _api -> updateFinance($financeID, array('status' => 3, 'admin_name' => $this -> _auth['admin_name'], 'check_time' => time()));
        $url = $this -> getFrontController() -> getBaseUrl() . "/admin/finance/{$mod}/";
        Custom_Model_Message :: showMessage('审核无效成功', $url);
    }
    /**
     * 退款帐户信息
     *
     * @return void
     */
    public function bankAction()
    {
        $financeID =  $this -> _request -> getParam('finance_id', null);
        $data = array_shift($this -> _api -> getFinance(array('finance_id' => $financeID)));
        $this -> view -> bank = unserialize($data['bank_data']);

    }
    /**
     * 备注信息
     *
     * @return void
     */
    public function noteAction()
    {
        $financeID =  $this -> _request -> getParam('finance_id', null);
        $data = array_shift($this -> _api -> getFinance(array('finance_id' => $financeID)));
        $this -> view -> note = $data['note'];
    }
  
    /**
     * 已付款订单列表
     *
     * @return   void
     */
    public function payedOrderListAction(){
        $search = $this->_request->getParams();
        $this -> view -> payment = $this -> _api -> getPayment(array('status' => 0));
        $data = $this -> _apiOrder -> fetchOrderBatch($search, '*', intval($this -> _request -> getParam('page', 1)));
        $this -> view -> data = $data['data'];
        $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> auth = $this -> _auth['admin_name'];
        $this -> view -> param = $search;
    }


    /**
     * 订单结款
     *
     * @return   void
     */
    public function clearAction(){
    	if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> clear($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::CLEAR_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
        	}
        }else{
            $this -> view -> payment_list = $this -> _api -> getPayment(array('status' => 0,'is_bank!=' => 1));
        }
    }

    /**
     * 结算单查询
     *
     * @return void
     */
    public function clearListAction()
    {
      	$search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
        $datas = $this -> _api -> getClear($search, $page);
        $payment_list=$this -> _api -> getPayment(array('status' => 0,'is_bank!=' =>1,'pay_type!=' =>'cod'));        
        $this -> view -> payment_list = $payment_list;
	    $pageNav = new Custom_Model_PageNav($datas['total'], null, 'ajax_search');
	    $this -> view -> datas = $datas['data'];
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 结算详情
     *
     * @return void
     */
    public function viewClearAction()
    {
    	$id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
	        $datas = $this -> _api -> viewClear($id);
            $payment_list=$this -> _api -> getPayment(array('status' => 0,'is_bank!=' => 1,'pay_type!=' =>'cod'));
            $this -> view -> payment_list = $payment_list;

	    	$this -> view -> bill = $datas['data'];
	    	$this -> view -> datas = $datas['details']['list'];
    	}
   }

    /**
     * 结算详情打印
     *
     * @return void
     */
    public function printClearAction()
    {
    	$id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
	        $datas = $this -> _api -> viewClear($id);
            $payment_list=$this -> _api -> getPayment(array('status' => 0,'is_bank!=' => 1,'pay_type!=' =>'cod'));
            $this -> view -> payment_list = $payment_list;
	    	$this -> view -> bill = $datas['data'];
	    	$this -> view -> datas = $datas['details']['list'];

    	}
   }

    /**
     * 支付LOG列表
     *
     * @return void
     */

   public function payLogListAction(){
        $search = $this -> _request -> getParams();
        $this -> view -> param = $search;
		$page = (int)$this -> _request -> getParam('page', 1);
        $datas = $this -> _api -> payLogList($search,'*',null,$page, 25);
        $this -> view -> datas = $datas['list'];
	    $pageNav = new Custom_Model_PageNav($datas['total'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> payment_list = $this -> _api -> getPayment(array('status' => 0,'pay_type!=' =>'cod'));
    }

    /**
     * 在线支付订单查询
     */
    public function onLineOrderAction() {
          
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $search['not_pay_type'] = "'cod','external'";
        $this -> view -> param =$search;
        $this -> view -> payment_list = $this -> _api -> getPayment(array('status' => 0,'is_bank!=' => 1));
        
        if ($search['todo'] == 'export') {
            $content[] = array('订单号', '支付方式', '下单时间', '发货时间', '金额', '佣金', '结算状态');
            $datas = $this -> _api -> fetchOrder($search);
    	    if ($datas['list']) {
    	        foreach ($datas['list'] as $data) {
    	            $content[] = array($data['batch_sn'], $data['pay_name'], $data['add_time'], date('Y-m-d H:i:s', $data['logistic_time']), $data['price_payed'], $data['commission'], $data['clear_pay'] ? '已结算' : '未结算');
    	        }
    	    }
    	    
    	    $xls = new Custom_Model_GenExcel();
            $xls -> addArray($content);
            $xls -> generateXML('finance_order');
            
            exit();
        }
        
        $datas = $this -> _api -> fetchOrder($search, $page, 20);
        $pageNav = new Custom_Model_PageNav($datas['total'], null, 'ajax_search_order');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> datas = $datas['list'];
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
        
        $stockConfig = Custom_Model_Stock_Base::getInstance();
        $this -> view -> areas = $stockConfig -> getConfigLogicArea();
        $this -> view -> distributionArea = array_flip($stockConfig -> getDistributionArea());
    }

    /**
     * 外部支付订单结款
     *
     * @return void
     */
    public function clearExternalAction()
    {
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> clearExternal($this -> _request -> getPost());
        	if ($result) {
        	    $url = $this -> getFrontController() -> getBaseUrl() ."/admin/finance/clear-external";
        	    Custom_Model_Message::showMessage(self::CLEAR_SUCCESS, $url, 1250);
        	}
        	else {
        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
        	}
        }
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }

    /**
     * 外部结算单查询
     *
     * @return void
     */
    public function clearExternalListAction()
    {
      	$search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
        $datas = $this -> _api -> getExternalClear($search, $page);
        $this -> view -> payment_list = $payment_list;
	    $pageNav = new Custom_Model_PageNav($datas['total'], null, 'ajax_search');
	    $this -> view -> datas = $datas['data'];
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    /**
     * 外部结算详情
     *
     * @return void
     */
    public function viewClearExternalAction()
    {
    	$id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
	        $datas = $this -> _api -> viewExternalClear($id);
            
	    	$this -> view -> bill = $datas['data'];
	    	$this -> view -> datas = $datas['details']['list'];
    	}
   }
   
   /**
     * 渠道订单销账
     *
     * @return void
     */
    public function writeOffExternalAction()
    {
        $shopAPI = new Admin_Models_API_Shop();
        
        if ($this -> _request -> isPost()) {
            $post = $this -> _request -> getPost();
            if ($post['ids']) {
                $shopAPI -> writeOffOrder($post['ids']);
            }
        }
        
        $params = $this -> _request -> getParams();
        if (!isset($params['status'])) {
		    $params['status'] = array('2', '3', '10');
		}
        
        $params['is_fake'] = 1;
        $params['fake_type'] = 0;
        $params['sync'] = 1;
	    $page = (int)$params['page'];
	    $page = $page ? $page : 1;
	    $datas = $shopAPI -> getOrder($params, $page, 25);
        
	    for ( $i = 0; $i < count($params['status']); $i++ ) {
            $status[$params['status'][$i]] = 1;
        }
        $params['status'] = $status;
	    
	    $this -> view -> datas = $datas['list'];
	    $this -> view -> amount = $datas['total']['amount'];
	    $this -> view -> param = $params;
        $pageNav = new Custom_Model_PageNav($datas['total']['count'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
	    
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    /**
     * 渠道订单列表
     *
     * @return void
     */
    public function externalOrderListAction()
    {
        $orderAPI = new Admin_Models_API_Order();
        
        $search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
	    if ($search['clear_no']) {
	        $clearData = $this -> _api -> getExternalClear("clear_no = '{$search['clear_no']}'");
	        if ($clearData['data']) {
	            $search['ids'] = $clearData['data'][0]['tids'];
	        }
	        else {
	            $search['ids'] = 0;
	        }
	    }
	    if ($search['todo'] == 'export') {
	        $datas = $orderAPI -> fetchExternalOrderBatch($search);
	        $content[] = array('店铺名称', '渠道订单号', '官网订单号','下单日期', '订单金额', '佣金', '结算状态');
	        if ($datas['list']) {
                foreach ($datas['list'] as $data) {
                    $content[] = array($data['shop_name'], $data['order_sn'], $data['batch_sn'], $data['order_time'], $data['amount'], $data['commission'], $data['is_settle'] ? '已结款' : '未结款');
                }
               
	        }
	        $xls = new Custom_Model_GenExcel();
            $xls -> addArray($content);
            $xls -> generateXML('external-order');

            exit;
	    }
	    
        $datas = $orderAPI -> fetchExternalOrderBatch($search, $page, 25);
	    $pageNav = new Custom_Model_PageNav($datas['total']['num'], 25, 'ajax_search');
	    $this -> view -> datas = $datas['list'];
	    $this -> view -> total = $datas['total'];
        $this -> view -> param = $search;
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    /**
     * 结款统计
     *
     * @return void
     */
    public function settlementSumAction()
    {
        $search = $this -> _request -> getParams();
        if ($search['type']) {
            $datas = $this -> _api -> settlementSum($search);
            if ($datas) {
                foreach ($datas as $data) {
                    $total['count']+= $data['count'];
                    $total['count1']+= $data['count1'];
                    $total['count2']+= $data['count2'];
                    $total['amount']+= $data['amount'];
                    $total['done_amount']+= $data['done_amount'];
                }    
            }
            
            $this -> view -> total = $total;
            $this -> view -> datas = $datas;
            $this -> view -> param = $search;
        }
    }
    
    /**
     * 采购入库付款列表
     *
     * @return void
     */
    public function purchasePaymentListAction()
    {
        $inStockAPI = new Admin_Models_API_InStock();
        
        $search = $this -> _request -> getParams();
        $page = (int)$this -> _request -> getParam('page', 1);
        
        $search['type'] = 1;
        $datas = $this -> _api -> getPurchaseData($search, $page, 20);
        if ($datas['data']) {
            foreach ($datas['data'] as $key => $data) {
                if ($data['paper_no']) {
                    $datas['data'][$key]['paper_no'] = str_replace(' ', '<br>', $data['paper_no']);
                }
                $datas['data'][$key]['left_amount'] = $data['amount'] - $data['real_amount'];
                $details = $inStockAPI -> getDetail("b.bill_no = '{$data['bill_no']}'", "a.shop_price,a.number,p.invoice_tax_rate");
                foreach ($details as $detail) {
                    $datas['data'][$key]['no_tax_amount'] += round($detail['shop_price'] / (1 + $detail['invoice_tax_rate'] / 100) * $detail['number'], 2);
                }
                //$datas['data'][$key]['no_tax_amount'] = round($datas['data'][$key]['no_tax_amount'], 2);
            }
        }
        
        $this -> view -> supplier = $inStockAPI -> getSupplier();
        
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total']['count'], 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> datas = $datas['data'];
        $this -> view -> total = $datas['total'];
        $this -> view -> no_tax_sum = round($datas['no_tax_sum'], 2);
        $stock = Custom_Model_Stock_Base::getInstance();
        $this -> view -> billType = $stock -> getConfigInType();
    }
    
    /**
     * 采购入库付款
     *
     * @return void
     */
    public function purchasePaymentAction()
    {
        $id = (int)$this -> _request -> getParam('id', 0);
        if (!$id)   exit;

        $datas = $this -> _api -> getPurchaseData("t1.id = '{$id}'");
        if (!$datas['data'])    exit;

        $payment = $datas['data'][0];
        $inStockAPI = new Admin_Models_API_InStock();
        $invoice_tmp_str = "";
        if ($this -> _request -> isPost()) {          
            $invoice_amount = 0 + $this -> _request -> getParam('invoice_amount');
            if ($invoice_amount > 0) {
                $payment['memo'] = $this -> _request -> getParam('memo');
                if ($payment['memo']) {
                    $memo = "　备注:{$payment['memo']}";
                }
                $history = "发票时间:".date('Y-m-d H:i:s')."　总发票金额:{$invoice_amount}　操作:".$this -> _auth['admin_name'].$memo;
                $invoice_tmp_str = $history;
                $row = array('invoice_amount' =>  $invoice_amount,
                             'history' => $payment['history']."\n".$history
                            );
                $this -> _api -> purchasePayment($payment['id'], $row);
            }
            $amount = 0 + $this -> _request -> getParam('amount');
           if ($amount > 0) {
                if ($payment['status'] == 2) {
                    Custom_Model_Message::showMessage('已付款');
                }
                //if ($payment['amount'] - $payment['real_amount'] < $amount) {
                //    Custom_Model_Message::showMessage('付款金额不能大于应付金额');
                //}
                $payment['memo'] = $this -> _request -> getParam('memo');
                 $payment['invinfo'] =$invoice_tmp_str;
                 $payment['mixamount'] =$this -> _request -> getParam('mixamount');
                $this -> _api -> purchaseAction($payment, $amount);
                
            }
            //更新入库表付款及发票信息
           
            $inStockAPI->updateInvAndNum($this -> _request -> getPost());
            Custom_Model_Message::showMessage('操作成功！', 'event', 1250, "Gurl('refresh')");
        }
        
        if ($payment['history']) {
            $payment['history'] = str_replace("\n", '<br>', $payment['history']);
        }
        $this -> view -> payment = $payment;
        
        $config = Custom_Model_Stock_Base::getInstance();
        $this -> view -> statusConfig = $config -> getConfigLogicStatus();
        
        $datas_detail =  $inStockAPI -> getDetail("bill_no = '{$payment['bill_no']}'");
        $flag = 1;
        foreach ($datas_detail as $detail){
            if($detail['number'] != $detail['invoice_num'] || $detail['number'] != $detail['prod_pay_num']){
                $flag = 2;
                break;
            }
        }
        $this -> view -> datas = $inStockAPI -> getDetail("bill_no = '{$payment['bill_no']}'");
        $this -> view -> amount = $payment['amount'] - $payment['real_amount'];
        $this -> view -> invoice_flag = $flag;
        
        
    }
    
    /**
     * 采购出库收款列表
     *
     * @return void
     */
    public function purchaseReceiveListAction()
    {
        $search = $this -> _request -> getParams();
        $page = (int)$this -> _request -> getParam('page', 1);
        
        $search['type'] = 2;
        $datas = $this -> _api -> getPurchaseData($search, $page, 20);
        
        $inStockAPI = new Admin_Models_API_InStock();
        $this -> view -> supplier = $inStockAPI -> getSupplier();

        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total']['count'], 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> datas = $datas['data'];
    }
    
    /**
     * 采购出库付收款
     *
     * @return void
     */
    public function purchaseReceiveAction()
    {
        $id = (int)$this -> _request -> getParam('id', 0);
        if (!$id)   exit;
        
        $datas = $this -> _api -> getPurchaseData("t1.id = '{$id}'");
        if (!$datas['data'])    exit;
        $payment = $datas['data'][0];
        if ($this -> _request -> isPost()) {
            $invoice_amount = 0 + $this -> _request -> getParam('invoice_amount');
            if ($invoice_amount > 0) {
                $payment['memo'] = $this -> _request -> getParam('memo');
                if ($payment['memo']) {
                    $memo = "　备注:{$payment['memo']}";
                }
                $history = "发票时间:".date('Y-m-d H:i:s')."　发票金额:{$invoice_amount}　操作:".$this -> _auth['admin_name'].$memo;
                $row = array('history' => $payment['history']."\n".$history);
                $this -> _api -> purchasePayment($payment['id'], $row);
                Custom_Model_Message::showMessage('添加发票成功');
            }
            else {
                if ($payment['status'] == 2) {
                    Custom_Model_Message::showMessage('已收款');
                }
                $amount = 0 + $this -> _request -> getParam('amount');
                if ($payment['amount'] - $payment['real_amount'] < $amount) {
                    Custom_Model_Message::showMessage('收款金额不能大于应收金额');
                }
                $payment['memo'] = $this -> _request -> getParam('memo');
                $payment['invinfo'] = 
                $this -> _api -> purchaseAction($payment, $amount);
                Custom_Model_Message::showMessage('收款完成', 'event', 1250, "Gurl('refresh')");
            }
        }
        
        if ($payment['history']) {
            $payment['history'] = str_replace("\n", '<br>', $payment['history']);
        }
        $this -> view -> payment = $payment;
        
        $outStockAPI = new Admin_Models_API_OutStock();
        $this -> view -> datas = $outStockAPI -> getDetail("bill_no = '{$payment['bill_no']}'");
        $this -> view -> amount = $payment['amount'] - $payment['real_amount'];
    }
    
    /**
     * 修改采购发票状态
     *
     * @return void
     */
    public function purchaseChangeInvoiceAction()
    {
        $bill_no = $this -> _request -> getParam('bill_no');
        $datas = $this -> _api -> getPurchaseData("t1.bill_no = '{$bill_no}'");
        if (!$datas['data'])    exit;
        
        $row = array('invoice' => 1);
        $this -> _api -> purchasePayment($datas['data'][0]['id'], $row);
        
        if ($datas['data'][0]['type'] == 1) {
            die('已收');
        }
        else if ($datas['data'][0]['type'] == 1) {
            die('已寄');
        }
    }
    
    /**
     *不发货单列表
     *
     * @return void
     */
    public function specialOrderListAction()
    {
        $orderAPI = new Admin_Models_DB_Order();
        $orderAPI -> _pageSize = 25;
        
        $search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
	    $search['status'] = 4;
        $datas = $orderAPI -> getOrderBathWithPage($search, $page);
	    $pageNav = new Custom_Model_PageNav($datas['total'], $orderAPI -> _pageSize, 'ajax_search');
	    $this -> view -> datas = $datas['data'];
        $this -> view -> param = $search;
        $this -> view -> pageNav = $pageNav -> getNavigation();
        
        $stockConfig = Custom_Model_Stock_Base::getInstance();
        $this -> view -> areas = $stockConfig -> getConfigLogicArea();
        $this -> view -> distributionAreaUsername = $stockConfig -> getDistributionArea();
        $this -> view -> distributionArea = array_flip($stockConfig -> getDistributionArea());
    }
    
    /**
     *不发货单结款
     *
     * @return void
     */
    public function clearSpecialAction()
    {
        $batch_sn = $this -> _request -> getParam('batch_sn');
        
        $orderAPI = new Admin_Models_API_Order();
        $order = array_shift($orderAPI -> getOrderBatch(array('batch_sn' => $batch_sn)));
        if (!$order)    exit;
        
        if ($this -> _request -> isPost()) {
            $amount = 0 + $this -> _request -> getParam('amount');
            if ($amount >= 0) {
                if (($order['price_payed'] + $amount) > $order['price_order']) {
                    Custom_Model_Message::showMessage('结款金额不能大于应收金额');
                }
                $set = array('price_payed' => $order['price_payed'] + $amount);
                if (($order['price_payed'] + $amount) >= $order['price_order']) {
                    $set['clear_pay'] = 1;
                    $set['status_pay'] = 2;
                    if ($order['user_name'] == 'batch_channel') {
                        $set['status_logistic'] = 2;
                    }
                }
                $set['pay_time'] = time();
                
                $orderDB = new Admin_Models_DB_Order();
                $orderDB -> updateOrderBatch(array('batch_sn' => $batch_sn), $set);
                
                $log = array('order_sn' => $batch_sn,
                             'batch_sn' => $batch_sn,
                             'add_time' => time(),
                             'title' => '确认收款 确认金额 : '.$amount,
                             'admin_name' => $this -> _auth['admin_name']);
                $orderDB -> addOrderBatchLog($log);
                
                //添加应收款记录
                if ($order['user_name'] == 'batch_channel') {
                    $receiveData = array('batch_sn' => $batch_sn,
                                         'type' => 2,
                                         'pay_type' => $order['pay_type'],
                                         'amount' => $order['price_order'],
                                        );
                    $this -> _api -> addFinanceReceivable($receiveData);
                }
                
                Custom_Model_Message::showMessage('结款完成', 'event', 1250, "Gurl('refresh')");
            }
            
        }
        
        $order['need_pay'] = $order['price_order'] - $order['price_payed'];
        
        $this -> view -> order = $order;
    }
    
    /**
     * 直供订单列表
     *
     * @return void
     */
    public function distributionOrderListAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $total = $this -> _api -> getDistributionSettlementSum($search);
        if ($total['count'] > 0) {
            $datas = $this -> _api -> getDistributionSettlement($search, $page, 20);
        }
        
        $this -> view -> datas = $datas;
        $this -> view -> param = $search;
        $this -> view -> total = $total;
	    $pageNav = new Custom_Model_PageNav($total['count'], 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get("shop_type = 'distribution'");
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    /**
     * 直供订单结款
     *
     * @return void
     */
    public function clearDistributionAction()
    {
        $batchSN = $this -> _request -> getParam('batch_sn');
        $data = array_shift($this -> _api -> getDistributionSettlement(array('batch_sn' => $batchSN)));
        if (!$data) die('找不到结款订单!');
        
        if ($this -> _request -> isPost()) {
            $amount = $this -> _api -> distributionSettlement($batchSN, $this -> _request -> getPost());
            if ($amount) {
                $orderAPI = new Admin_Models_API_Order();
                $order = array_shift($orderAPI -> getOrderBatch(array('batch_sn' => $batchSN)));
                    
                $set = array('price_payed' => $order['price_payed'] + $amount);
                if (($data['settle_amount'] + $amount) >= $data['amount']) {
                    $set['clear_pay'] = 1;
                    $set['status_pay'] = 2;
                }
                else {
                    $set['clear_pay'] = 0;
                    $set['status_pay'] = 0;
                }
                $set['pay_time'] = time();
                
                $orderDB = new Admin_Models_DB_Order();
                $orderDB -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
                
                if ($amount > 0) {
                    $title = '确认收款';
                }
                else {
                    $title = '确认退款';
                }
                $log = array('order_sn' => $batchSN,
                             'batch_sn' => $batchSN,
                             'add_time' => time(),
                             'title' => $title.' 确认金额 : '.$amount,
                             'admin_name' => $this -> _auth['admin_name']);
                $orderDB -> addOrderBatchLog($log);
                    
                Custom_Model_Message::showMessage('结款完成', 'event', 1250, "Gurl('refresh')");
            }
            else {
                Custom_Model_Message::showMessage($this -> _api -> error);
            }
        }
        
        $orderDB = new Admin_Models_DB_Order();
        $productData = $orderDB -> getOrderBatchGoods(array('batch_sn' => $batchSN));
        foreach ($productData as $product) {
            $productInfo[$product['product_id']] = $product['product_sn'];
        }
        $details = $this -> _api -> getDistributionSettlementDetail($batchSN);
        if ($details) {
            foreach ($details as $index => $detail) {
                if ($detail['type'] == 1 ) {
                    foreach ($detail['detail'] as $productID => $number) {
                        if ($detail['amount'] > 0) {
                            $productNumber[$productID] += $number;
                        }
                        else {
                            $productNumber[$productID] -= $number;
                        }
                    }
                }
                else {
                    foreach ($detail['detail'] as $productID => $number) {
                        $reduceProductNumber[$productID] += $number;
                    }
                }
            }
        }
        
        $this -> view -> details = $details;
        $this -> view -> data = $data;
        $this -> view -> productData = $productData;
        $this -> view -> productNumber = $productNumber;
        $this -> view -> reduceProductNumber = $reduceProductNumber;
        $this -> view -> productInfo = $productInfo;
    }
    
     /**
     * 赊销订单列表
     *
     * @return void
     */
    public function creditOrderListAction()
    {
        $orderAPI = new Admin_Models_DB_Order();
        $orderAPI -> _pageSize = 25;
        
        $search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
	    $search['status'] = 0;
	    $search['user_name'] = 'credit_channel';
        $datas = $orderAPI -> getOrderBathWithPage($search, $page);
	    $pageNav = new Custom_Model_PageNav($datas['total'], $orderAPI -> _pageSize, 'ajax_search');
	    $this -> view -> datas = $datas['data'];
        $this -> view -> param = $search;
        $this -> view -> pageNav = $pageNav -> getNavigation();
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get("shop_type = 'credit'");
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    /**
     *购销订单列表
     *
     * @return void
     */
    public function batchOrderListAction()
    {
        $orderAPI = new Admin_Models_DB_Order();
        $orderAPI -> _pageSize = 25;
        $search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
	    $search['status'] = 0;
	    $search['status_logistic>'] = 0;
	    $search['user_name'] = 'batch_channel';
        $datas = $orderAPI -> getOrderBathWithPage($search, $page);
	    $pageNav = new Custom_Model_PageNav($datas['total'], $orderAPI -> _pageSize, 'ajax_search');
	    $this -> view -> datas = $datas['data'];
        $this -> view -> param = $search;
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 运费单结算
     *
     * @return void
     */
    public function clearLogisticAction()
    {
        if ($this -> _request -> isPost()) {
            $post = $this -> _request -> getPost();
            
            if ($post['logistic_price']) {
                $api = new Admin_Models_API_Transport();
                foreach ($post['logistic_price'] as $tid => $logistic_price) {
                    $api -> update(array('logistic_price' => $logistic_price), "tid = {$tid}");
                }
                Custom_Model_Message::showMessage('结算完成', 'event', 1250, "Gurl('refresh')");
            }
        }
        
        $api = new Admin_Models_DB_Logistic();
        foreach ($api -> getLogisticList() as $key => $value) {
    	    $logisticList[$value['logistic_code']] = $value['name'];
    	}
    	$this -> view -> logisticList = $logisticList;
    }
    
    /**
     * 客情订单核销
     *
     * @return void
     */
    public function giftOrderListAction()
    {
        $orderAPI = new Admin_Models_DB_Order();
        $orderAPI -> _pageSize = 25;
        
        if ($this -> _request -> isPost()) {
            $batch_sns = $this -> _request -> getParam('batch_sns');
            if ($batch_sns) {
                foreach ($batch_sns as $batch_sn) {
                    $orderAPI -> updateOrderBatch(array('batch_sn' => $batch_sn), array('clear_pay' => 1));
                }
                Custom_Model_Message::showMessage('核销完成', 'event', 1250, "Gurl('refresh')");
            }
        }
        
        $search = $this -> _request -> getParams();
	    $page = (int)$this -> _request -> getParam('page', 1);
	    $search['status'] = 0;
	    $search['type'] = 5;
        $datas = $orderAPI -> getOrderBathWithPage($search, $page);
	    $pageNav = new Custom_Model_PageNav($datas['total'], $orderAPI -> _pageSize, 'ajax_search');
	    $this -> view -> datas = $datas['data'];
        $this -> view -> param = $search;
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 分销刷单列表
     *
     * @return void
     */
    public function distributionFakeOrderListAction()
    {
        $search = $this -> _request -> getParams();
        $page = (int)$this -> _request -> getParam('page', 1);
        
        $datas = $this -> _api -> getDistributionOrder($search, $page, 20);
        
        $pageNav = new Custom_Model_PageNav($datas['sum']['total'], 20, 'ajax_search');
	    $this -> view -> datas = $datas['data'];
        $this -> view -> param = $search;
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> sum = $datas['sum'];
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    /**
     * 分销刷单销账
     *
     * @return void
     */
    public function distributionWriteOffAction()
    {
        $batchSN = $this -> _request -> getParam('batch_sn');
        $amount = $this -> _request -> getParam('amount');
        if (!$batchSN || $amount <= 0)  exit;
        
        echo $this -> _api -> distributionWriteOff($batchSN, $amount);
        
        exit;
    }
	
	public function refundAction(){
	    Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		parent::gridListAction();
	}
	
	public function refundViewAction(){
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$id = $this->_request->getParam('id');	
		$refund = $this->_api->getRow('shop_refund',array('refund_id'=>$id));
		if(empty($id)) die;
		$refund['bank_config'] = unserialize($refund['bank_config']);
		$refund['member'] = $this->_api->getRow('shop_member',array('member_id'=>$refund['member_id']));
		$config_status = array('0'=>'待审核','1'=>'已退款','2'=>'已废除');
		$config_status_color = array('#ff6600','green','red');
		
		$refund['status_name'] = $config_status[$refund['status']];
		$refund['status_color'] = $config_status_color[$refund['status']];
		
		$this->view->r = $refund;
		
		
	}
    
	public function refundDoAction(){
		
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$id = $this->_request->getParam('id');	
		$refund = $this->_api->getRow('shop_refund',array('refund_id'=>$id));
		if(empty($refund)) die;
		
		$status = $this->_request->getParam('status');	
		if(empty($status)) Custom_Model_Tools::ejd('fail','请选择操作类型！');
		
		//更改状态
		$this->_api->update('shop_refund', $this->_request->getParams(),array('refund_id'=>$id));
		
		$member = $this->_api->getRow('shop_member',array('member_id'=>$refund['member_id']));
		$frost_money = floatval($member['frost_money']) - floatval($refund['money']);
		$money = floatval($member['money']) + floatval($refund['money']);
		
		//通过
		if($status == '1'){
			
			//在冻结余额的基础上减去指定金额
			$this->_api->update('shop_member', array('frost_money'=>$frost_money),array('member_id'=>$refund['member_id']));
			
		//作废	
		}elseif($status == '2'){
			//在冻结余额的基础上减去，在账户余额的基础上加
			$this->_api->update('shop_member', array('frost_money'=>$frost_money,'money'=>$money),array('member_id'=>$refund['member_id']));
		}

		Custom_Model_Tools::ejd('succ','操作成功！');		
	}
	
	public function createReceiveRecordAction()
	{
	    $db = Zend_Registry::get('db');
	    
	    $datas = $db -> fetchAll("select batch_sn from shop_finance_receivable");
	    foreach ($datas as $data) {
	        $batchSNArray[] = $data['batch_sn'];
	    }
	    
	    /*
	    //渠道订单(原始应收，不包含换货单)
	    $datas = $db -> fetchAll("select t2.batch_sn,t1.amount,t1.logistic_code,t1.sync_time from shop_order_shop as t1 inner join shop_order as t2 on t1.external_order_sn = t2.external_order_sn inner join shop_order_batch as t3 on t2.order_id = t3.order_id where t1.sync = 1 and t3.parent_batch_sn is null");
	    foreach ($datas as $data) {
	        if (in_array($data['batch_sn'], $batchSNArray)) continue;
	        
	        $row = array('batch_sn' => $data['batch_sn'],
	                     'type' => 3,
	                     'pay_type' => $data['logistic_code'] == 'externalself' ? 'externalself' : 'external',
	                     'amount' => $data['amount'],
	                     'add_time' => $data['sync_time'] ? $data['sync_time'] : $data['order_time'],
	                     'auto' => 1,
	                    );
	        $db -> insert('shop_finance_receivable', $row);
	        
	        $changed++;
	    }
	    */
	    
	    
	    
	    
	    echo $changed;
	    
	    exit;
	}
	
}

