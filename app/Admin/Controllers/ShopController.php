<?php
class Admin_ShopController extends Zend_Controller_Action
{
	private $_api;
	
	const ADD_SUCCESS = '添加店铺成功!';
	const EDIT_SUCCESS = '编辑店铺成功!';
	const ADD_PROMOTION_SUCCESS = '添加店铺活动成功!';
	const EDIT_PROMOTION_SUCCESS = '编辑店铺活动成功!';
    /**
     * 允许操作的管理员列表
     * @var array
     */
     private $_allowDoList = array ('1'); 	
	/**
     * 初始化对象updateOrder
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Shop();
	}
	
	/**
     * 店铺列表
     *
     * @return   void
     */
	public function listAction() 
	{
	    $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> get($search, '*', null, $page, 25);
        if ( $datas['total'] > 0 ) {
            foreach ($datas['list'] as $num => $data) {
                $datas['list'][$num]['status'] = $this -> _api -> ajaxStatus('/admin/shop/status', $datas['list'][$num]['shop_id'], $datas['list'][$num]['status']);
                if ($datas['list'][$num]['sync_order_time']) {
                    $datas['list'][$num]['sync_order_time'] = date('Y-m-d H:i:s', $datas['list'][$num]['sync_order_time']);
                }
                else    $datas['list'][$num]['sync_order_time'] = '';
            }
        }
        
        $this -> view -> datas = $datas['list'];
        $this -> view -> param = $search;
	    $pageNav = new Custom_Model_PageNav($datas['total'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
	}
	
	/**
     * 商品列表
     *
     * @return void
     */
    public function goodsListAction() {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> getGoods($search, 't1.*,t2.shop_name,p.price_limit', null, $page, 25);
        
        $this -> view -> datas = $datas['list'];
        $this -> view -> param = $search;
	    $pageNav = new Custom_Model_PageNav($datas['total'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        
        $shopDatas = $this -> _api -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
	/**
     * 导出店铺商品信息
     *
     * @return   void
     */
	public function goodsExportAction()
	{
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> getExportGoods($search, 't1.*,t2.shop_name');
	    $excel = new Custom_Model_GenExcel();
        $title = array('店铺','商品编号','商品名称','产品分类','销售价','状态');
        $lineArray[] = $title;
        if ( $datas ) {
            foreach ($datas as $data)
            {
                if($data['onsale']=='0'){
                    $data['onsale_desc']='上架';
                }else{
                    $data['onsale_desc']='下架';  
                }
                $row = array($data['shop_name'],$data['goods_sn'],$data['shop_goods_name'],$data['category'],$data['shop_price'],$data['onsale_desc']);
                $lineArray[] = $row;
                unset($row);
            }
            unset($datas);
            $excel -> addArray ($lineArray);
            $excel -> generateXML ('external-goods');
        }
        exit;
	}


    /**
     * 查询订单列表
     *
     * @return   void
     */
	public function orderListAction()
	{
	    $params = $this -> _request -> getParams();
	    
	    if ( !isset($params['status']) ) {
		    $params['status'] = array('1', '2', '3', '10', '11', '12');
		}
	    
	    $page = (int)$params['page'];
	    $page = $page ? $page : 1;
	    $datas = $this -> _api -> getOrder($params, $page, 20);
	    
	    $this -> view -> datas = $datas['list'];
	    $this -> view -> amount = $datas['total']['amount'];
	    for ( $i = 0; $i < count($params['status']); $i++ ) {
            $status[$params['status'][$i]] = 1;
        }
        $params['status'] = $status;
        $this -> view -> param = $params;
        $pageNav = new Custom_Model_PageNav($datas['total']['count'], 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        
        $shopDatas = $this -> _api -> get();
        $this -> view -> shopDatas = $shopDatas['list'];

	    $this -> view -> auth =  Admin_Models_API_Auth :: getInstance() -> getAuth();


	}
    
    /**
     * 审核订单列表
     *
     * @return   void
     */
	public function orderCheckListAction()
	{
	    if ($this -> _request -> isPost()) {
	        $post = $this -> _request -> getPost();
	        if ( $post['ids'] ) {
	            $_auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	            foreach ( $post['ids'] as $shopOrderID ) {
	                $datas = $this -> _api -> getOrder(array('shop_order_id' => $shopOrderID));
	                if ( !$datas['list'] ) {
	                    Custom_Model_Message::showMessage("订单ID:{$shopOrderID}不存在", 'reload', -1);
	                }
	                
	                $order = $datas['list'][0];
	                if ( !in_array($order['status'], array(2,3,10)) && $post['todo'] != 'import') {
	                    Custom_Model_Message::showMessage("订单{$order['external_order_sn']}状态不是待发货/待确认收货/已完成", 'reload', -1);
    	            }
    	            
	                if ( $post['todo'] == 'import' ) {
	                    if ( $order['sync'] != 0 ) {
	                        Custom_Model_Message::showMessage("订单{$order['external_order_sn']}已经导入官网订单，不能再导入", 'reload', -1);
            	        }
            	        if ( in_array($order['status'], array(3,10)) ) {
            	            Custom_Model_Message::showMessage("订单{$order['external_order_sn']}状态不是待发货，不能通过该方式导入", 'reload', -1);
            	        }
            	        if ($order['is_fake']) {
            	            Custom_Model_Message::showMessage("订单{$order['external_order_sn']}是刷单，不能导入官网", 'reload', -1);
            	        }
	                }
	                if ( $post['todo'] == 'status1' || $post['todo'] == 'import' || $post['todo'] == 'logistics_status1' ) {
	                    if ( $order['status'] == 2 && ($post['todo'] == 'status1' || $post['todo'] == 'logistics_status1')) {
        	                $status = $this -> _api -> getOrderStatus($order['external_order_sn'], $order['shop_id']);
        	                if ($status) {
        	                    if ($status == 13) {
        	                        Custom_Model_Message::showMessage("订单{$order['external_order_sn']}发生退款，不能审核通过", 'reload', -1);
        	                    }
        	                    if ($status != 2) {
        	                        Custom_Model_Message::showMessage("订单{$order['external_order_sn']}状态改变，不能审核通过", 'reload', -1);
        	                    }
        	                }
        	            }
    	                if ($post['todo'] == 'status1' && $order['status'] == 2 && !$order['logistic_code'] && $order['shop_id'] != 9) {
    	                    Custom_Model_Message::showMessage("订单{$order['external_order_sn']}物流公司没有设置，不能审核通过", 'reload', -1);
    	                }
    	                if ( $order['status'] == 2 ) {
    	                    if ($order['addr_province_id'] == 0 || $order['addr_city_id'] == 0 || $order['addr_area_id'] == 0 ) {
    	                        Custom_Model_Message::showMessage("订单{$order['external_order_sn']}地址不匹配，不能审核通过", 'reload', -1);
    	                    }
    	                }
    	                
    	                if ( $post['todo'] == 'logistics_status1' ) {
    	                    if (!$post['order_logistic_code'][$shopOrderID]) {
    	                        Custom_Model_Message::showMessage("订单{$order['external_order_sn']}物流公司没有设置，不能审核通过", 'reload', -1);
    	                    }
    	                    if (!$this -> _api -> updateOrderLogistics($shopOrderID, $post['order_logistic_code'][$shopOrderID], $order)) {
            	                Custom_Model_Message::showMessage($this -> _api -> _error, 'reload', -1);
            	            }
    	                }
    	                if (false === $this->_api->judgePriceLimit($shopOrderID, 1)) {
                            Custom_Model_Message::showMessage($this->_api->getError(), 'reload', -1);
                        }
    	                if ($post['todo'] == 'import') {
    	                    $this -> _api -> checkProductStock($order, false);
    	                }
    	                else {
    	                    if ($order['is_fake']) {
    	                        $order['goods'] = $this -> _api -> updateFakeOrderGoods($order);
    	                    }
    	                    
    	                    $this -> _api -> checkProductStock($order);
    	                }
    	                
    	                $set = '';
    	                if ($post['todo'] == 'status1' || $post['todo'] == 'logistics_status1') {
                            if (!$this -> _transportAPI) {
                                $this -> _transportAPI = new Admin_Models_API_Transport();
                            }
                            $set = array('validate_sn' => $this -> _transportAPI -> getValidateSn());
                        }
    	                $this -> _api -> checkOrder($shopOrderID, 1, null, $set);

    	                if (!$this -> _replenishmentAPI) {
                            $this -> _replenishmentAPI = new Admin_Models_API_Replenishment();
                        }
                        $this -> _replenishmentAPI -> cancelOrderReplenish($order['shop_order_id']);
    	            }
    	            if ( $post['todo'] == 'status9' ) {
    	                if ( $order['status_business'] != 0 ) {
    	                    Custom_Model_Message::showMessage("订单{$order['external_order_sn']}业务状态不是未审核", 'reload', -1);
    	                }
    	                $this -> _api -> checkOrder($shopOrderID, 9);
    	                if (!$this -> _replenishmentAPI) {
                            $this -> _replenishmentAPI = new Admin_Models_API_Replenishment();
                        }
    	                $this -> _replenishmentAPI -> cancelOrderReplenish($order['shop_order_id']);
    	            }
    	            if ( $post['todo'] == 'import' ) {
            	        if ( !$this -> _api -> importToFormalOrder($order) ) {
            	            Custom_Model_Message::showMessage("订单{$order['external_order_sn']}导入官网订单失败，{$this -> _api -> _log}", 'reload', -1);
            	        }
            	    }
            	    if ( $post['todo'] == 'logistics' ) {
            	        if ( $order['status'] != 2 ) {
            	            Custom_Model_Message::showMessage("订单{$order['external_order_sn']}状态不是待发货，不能设置物流公司", 'reload', -1);
            	        }
            	        if (!$this -> _api -> updateOrderLogistics($shopOrderID, $post['order_logistic_code'][$shopOrderID], $order)) {
            	            Custom_Model_Message::showMessage($this -> _api -> _error, 'reload', -1);
            	        }
            	        if ($order['status_business'] != 0) {
            	            $this -> _api -> addOrderHistory(array('id' => $order['shop_order_id'], 'history' => "修改物流公司：{$order['logistic_code']} => {$post['set_logistic_code']}"));
            	        }
            	    }
            	    if ( $post['todo'] == 'status0' ) {
            	        if ( $order['status_business'] != 1 && $order['status_business'] != 2 ) {
            	            Custom_Model_Message::showMessage("订单{$order['external_order_sn']}状态不是已审核，不能反审核", 'reload', -1);
            	        }
            	        if ( $order['lock_admin_name'] && $order['lock_admin_name'] != $_auth['admin_name']) {
            	            Custom_Model_Message::showMessage("订单{$order['external_order_sn']}已被他人锁定，不能反审核", 'reload', -1);
            	        }
            	        
            	        $this -> _api -> releaseProductStock($order);
            	        
            	        if (!$this -> _transportAPI) {
                            $this -> _transportAPI = new Admin_Models_API_Transport();
                        }
                        $this -> _transportAPI -> deleteValidateSn($order['validate_sn']);
            	        
            	        $this -> _api -> checkOrder($shopOrderID, 0, null, array('validate_sn' => 0));
            	    }
	            }
	            
	            Custom_Model_Message::showMessage("操作成功", 'reload', 1000);
	        }
	    }
	    
	    $params = $this -> _request -> getParams();

	    if (!$params['status']) $params['status'] = 2;
	    if (!$params['status_business'])    $params['status_business'] = 0;
	    if (!isset($params['is_fake'])) $params['is_fake'] = 0;
	    $params['sync'] = 0;
	    $page = (int)$params['page'];
	    $page = $page ? $page : 1;
	    $datas = $this -> _api -> getOrder($params, $page, 100);
	    
	    $logisticAPI = new Admin_Models_DB_Logistic();
	    foreach ($logisticAPI -> getLogisticList() as $key => $value) {
    	    $logisticList[$value['logistic_code']] = $value['name'];
    	}
    	$this -> view -> logisticList = $logisticList;
    	
    	$codLogisticCode = $this -> _api -> getCodLogisticCode();
    	foreach ($codLogisticCode as $code) {
    	    $logisticListCod[$code] = $logisticList[$code];
    	}
	    $this -> view -> logisticListCod = $logisticListCod;
	    $shopOrderIDArray = array();
	    if ($datas['list']) {
	        $logisticAPI = new Admin_Models_API_Logistic();
	        $replenishmentAPI =  new Admin_Models_API_Replenishment();
	        foreach ($datas['list'] as $index => $data) {
	            $policy = $logisticAPI -> getLogisticPolicy($data['is_cod'], $data['addr_province_id']);
	            if ($data['shop_id'] == 51 && $data['is_cod']) {
	                $policy['code'][0] = 'externalself';
	            }
	            if (!$policy['code'])   continue;
	            $flag = 1;
	            $tempPolicy = '';
	            foreach ($policy['code'] as $code) {
	                $tempPolicy[$code] = $flag++;
	            }
	            $datas['list'][$index]['logisticPolicy'] = $tempPolicy;
	            $shopOrderIDArray[] = $data['shop_order_id'];
	        }
	        $this -> view -> replenishmentInfo = $replenishmentAPI -> getReplenishStatus($shopOrderIDArray);
	    }
	    
	    $this -> view -> provinceData = array_shift($this -> _api -> getAllArea(1));
        
	    if ( $params['province'] ) {
	        if (is_array($params['province'])) {
    	        for ( $i = 0; $i < count($params['province']); $i++ ) {
                    $province[$params['province'][$i]] = 1;
                }
            }
            else    $province[$params['province']] = 1;
        }
        else {
            foreach ($this -> view -> provinceData as $province_name => $province_id) {
                $province[$province_id] = 1;
            }
        }
        $params['province']  = $province;
	    
	    $this -> view -> datas = $datas['list'];
	    $this -> view -> amount = $datas['total']['amount'];
        $this -> view -> param = $params;
        $pageNav = new Custom_Model_PageNav($datas['total']['count'], 100, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        
        $shopDatas = $this -> _api -> get();
        $orderCountInfo = $this -> _api -> getCheckOrderCount();
	    foreach ($shopDatas['list'] as $index => $shop) {
	        $shopDatas['list'][$index]['orderCount'] = $orderCountInfo[$shop['shop_id']];
	    }
        $this -> view -> shopDatas = $shopDatas['list'];
        $this -> view -> currentDate = date('Y-m-d');
	}
	
	/**
     * 打印订单列表
     *
     * @return   void
     */
	public function orderPrintListAction()
	{
	    if ($this -> _request -> isPost()) {
	        $post = $this -> _request -> getPost();
	        if ( $post['ids'] ) {
	            if ( $post['todo'] == 'other-logistics' ) {
	                $content[] = array('店铺', '订单号', '订单金额', '运费', '订单商品', '收货人', '下单时间', '收货地址', 
	                                   '电话', '手机', '发票', '备注', '刷单');
	                $shopDatas = $this -> _api -> get("shop_id = {$post['print_shop_id']}");
	                $shop = $shopDatas['list'][0];
	            }
	            if ($post['todo'] == 'lock') {
	                $_auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	            }
	            
	            foreach ( $post['ids'] as $shopOrderID ) {
	                $datas = $this -> _api -> getOrder(array('shop_order_id' => $shopOrderID));
	                if ( !$datas['list'] ) {
	                    die("订单ID:{$shopOrderID}不存在");
	                }
	                $order = $datas['list'][0];
	                if ( $order['status'] != 2 ) {
	                    die("订单{$order['external_order_sn']}状态不是待发货");
	                }
	                
	                if ($post['todo'] == 'lock' || $post['todo'] == 'unlock') {
	                    if ($post['todo'] == 'lock') {
	                        if ($order['lock_admin_name'] != '' && $order['lock_admin_name'] != $_auth['admin_name']) {
	                            die("订单{$order['external_order_sn']}已被其他用户锁定，请先解锁");
	                        }
	                        $this -> _api -> lockOrder($shopOrderID, $_auth['admin_name']);
	                    }
	                    else if ($post['todo'] == 'unlock') {
	                        $this -> _api -> lockOrder($shopOrderID, '');
	                    }
	                    
	                    continue;
	                }
	                
	                if ( $post['todo'] != 'status2' ) {
            	        $status = $this -> _api -> getOrderStatus($order['external_order_sn'], $order['shop_id']);
            	        if ($status) {
            	            if ($status == 13) {
            	                die("订单{$order['external_order_sn']}发生退款，不能进行下一步操作");
            	            }
            	            if ($status != 2) {
            	                die("订单{$order['external_order_sn']}状态改变，不能进行下一步操作");
            	            }
            	        }
	                }
	                
	                if ( $post['todo'] == 'status2' ) {
    	                if ( $order['status_business'] != 1 ) {
    	                    die("订单{$order['external_order_sn']}业务状态不是审核通过");
    	                }
    	                
    	                $this -> _api -> checkOrder($shopOrderID, 2);
    	            }
    	            if ( $post['todo'] == 'other-logistics' ) {
    	                $goodsStr = '';
            	        foreach ( $order['goods'] as $goods ) {
            	            $goodsStr .= $goods['shop_goods_name'].' * '.$goods['number'].'　';
            	        }
    	                $content[] = array($shop['shop_name'], $order['external_order_sn'], $order['amount'],
            	                           $order['freight'], $goodsStr, $order['addr_consignee'], date('Y-m-d H:i:s', $order['order_time']), 
            	                           $order['addr_province'].$order['addr_city'].$order['addr_area'].$order['addr_address'], $order['addr_tel'],
            	                           $order['addr_mobile'], $order['invoice'], $order['memo'], $order['is_fake'] ? '是' : '否');
    	            }
    	            
    	            if ( $post['todo'] == 'other-logistics-send' ) {
    	                if ($order['logistic_code'] != 'yms') {
    	                    die("订单{$order['external_order_sn']}的物流公司不是yms");
    	                }
    	                
    	                $sendResult = $this -> _api -> otherLogisticsSend($order);
    	                if ($sendResult) {
    	                    $this -> _api -> checkOrder($shopOrderID, 4, 1);
    	                }
    	            }
    	            
    	            if ( $post['todo'] == 'logistics' ) {
    	                if ($order['logistic_code'] != $post['order_logistic_code'][$shopOrderID]) {
    	                    if (!$this -> _api -> updateOrderLogistics($shopOrderID, $post['order_logistic_code'][$shopOrderID], $order)) {
    	                        die($this -> _api -> _error);
    	                    }
            	            $this -> _api -> addOrderHistory(array('id' => $order['shop_order_id'], 'history' => "修改物流公司：{$order['logistic_code']} => {$post['order_logistic_code'][$shopOrderID]}"));
            	        }
    	            }
	            }
	            if ( $post['todo'] == 'other-logistics' ) {
	                foreach ( $post['ids'] as $shopOrderID ) {
	                    $this -> _api -> checkOrder($shopOrderID, 2, 1);
	                }
	                
	                $xls = new Custom_Model_GenExcel();
            		$xls -> addArray($content);
            	    $xls -> generateXML('shop_order');
            	    exit;
	            }
	        }
	        if ( $post['toFormalOrderID'] ) {
	            $datas = $this -> _api -> getOrder(array('shop_order_id' => $post['toFormalOrderID']));
	            if ( !$datas['list'] ) {
	                die("订单ID:{$post['toFormalOrderID']}不存在");
	            }
	            $order = $datas['list'][0];
	            if ( $order['status'] != 2 ) {
	                die("订单{$order['external_order_sn']}状态不是待发货");
	            }
	            if ( $order['status_business'] != 1 ) {
    	            die("订单{$order['external_order_sn']}业务状态不是审核通过");
    	        }
    	        if ( $order['sync'] != 0 ) {
    	            die("订单{$order['external_order_sn']}已经导入官网订单，不能再导入");
    	        }
    	        
    	        if ( !$this -> _api -> importToFormalOrder($order) ) {
    	            die("订单{$order['external_order_sn']}导入官网订单失败，{$this -> _api -> _log}");
    	        }
	        }
	    }
	    
	    $params = $this -> _request -> getParams();
	    $params['status'] = 2;
	    $params['status_business'] = 1;
	    $params['sync'] = 0;
	    $page = (int)$params['page'];
	    $page = $page ? $page : 1;
	    
	    $shopDatas = $this -> _api -> get("shop_type <> 'tuan'");
	    $orderCountInfo = $this -> _api -> getPrintOrderCount();
	    foreach ($shopDatas['list'] as $index => $shop) {
	        $shopDatas['list'][$index]['orderCount'] = $orderCountInfo[$shop['shop_id']];
	    }
        $this -> view -> shopDatas = $shopDatas['list'];
        //if ( !$params['shop_id'] )  $params['shop_id'] = $shopDatas['list'][0]['shop_id'];
	    
	    $datas = $this -> _api -> getOrder($params);
	    $datas = $this -> _api -> getOrderWithMerge($datas);
	    $datas['list'] = $this -> _api -> getDataWithPage($datas['list'], $page, 100);
        $datas['list'] = $this -> _api -> setMergeOrderFlag($datas['list']);
        
        $logisticAPI = new Admin_Models_DB_Logistic();
	    foreach ($logisticAPI -> getLogisticList() as $key => $value) {
    	    $logisticList[$value['logistic_code']] = $value['name'];
    	}
    	$this -> view -> logisticList = $logisticList;
	    
	    $codLogisticCode = $this -> _api -> getCodLogisticCode();
    	foreach ($codLogisticCode as $code) {
    	    $logisticListCod[$code] = $logisticList[$code];
    	}
	    $this -> view -> logisticListCod = $logisticListCod;
	    
	    $logisticAPI = new Admin_Models_API_Logistic();
	    if ($datas['list']) {
	        foreach ($datas['list'] as $index => $data) {
	            $policy = $logisticAPI -> getLogisticPolicy($data['is_cod'], $data['addr_province_id']);
	            if ($data['shop_id'] == 51 && $data['is_cod']) {
	                $policy['code'][0] = 'externalself';
	            }
	            $flag = 1;
	            $tempPolicy = '';
	            foreach ($policy['code'] as $code) {
	                $tempPolicy[$code] = $flag++;
	            }
	            $datas['list'][$index]['logisticPolicy'] = $tempPolicy;
	        }
	    }
        
	    $this -> view -> provinceData = array_shift($this -> _api -> getAllArea(1));
        
	    if ( $params['province'] ) {
	        if (is_array($params['province'])) {
    	        for ( $i = 0; $i < count($params['province']); $i++ ) {
                    $province[$params['province'][$i]] = 1;
                }
            }
            else    $province[$params['province']] = 1;
        }
        else {
            foreach ($this -> view -> provinceData as $province_name => $province_id) {
                $province[$province_id] = 1;
            }
        }
        $params['province']  = $province;
        
	    $this -> view -> datas = $datas['list'];
	    $this -> view -> amount = $datas['total']['amount'];
        $this -> view -> param = $params;
        $pageNav = new Custom_Model_PageNav($datas['total']['count'], 100, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
	}
	
	/**
     * 发货订单列表
     *
     * @return   void
     */
	public function orderSendListAction()
	{
	    $params = $this -> _request -> getParams();
	    $params['status'] = 2;
	    $params['status_business'] = 2;
	    $params['sync'] = 0;
	    $page = (int)$params['page'];
	    $page = $page ? $page : 1;
	    
	    $shopDatas = $this -> _api -> get("shop_type <> 'tuan'");
	    $orderCountInfo = $this -> _api -> getSendOrderCount();
	    foreach ($shopDatas['list'] as $index => $shop) {
	        $shopDatas['list'][$index]['orderCount'] = $orderCountInfo[$shop['shop_id']];
	    }
        $this -> view -> shopDatas = $shopDatas['list'];
        if ( !$params['shop_id'] )  $params['shop_id'] = $shopDatas['list'][0]['shop_id'];
	    
	    if ($this -> _request -> isPost()) {
	        $post = $this -> _request -> getPost();
	        
	        if ($post['todo'] == 'lock') {
	            $_auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	        }
	        
	        if ( $post['ids'] ) {
	            foreach ($post['ids'] as $shopOrderID) {
	                $datas = $this -> _api -> getOrder(array('shop_order_id' => $shopOrderID));
	                if ( !$datas['list'] ) {
	                    die("订单ID:{$shopOrderID}不存在");
	                }
	                $order = $datas['list'][0];
	                if ( $order['status'] != 2 ) {
	                    die("订单{$order['external_order_sn']}状态不是待发货");
	                }
	                if ( $order['status_business'] != 2 ) {
	                    die("订单{$order['external_order_sn']}状态不是已打印");
	                }
	                if ( $order['sync']) {
	                    die("订单{$order['external_order_sn']}已同步官网");
	                }
	                
	                if ($post['todo'] == 'lock') {
	                    if ($order['lock_admin_name'] != '' && $order['lock_admin_name'] != $_auth['admin_name']) {
	                        die("订单{$order['external_order_sn']}已被其他用户锁定，请先解锁");
	                    }
	                    $this -> _api -> lockOrder($shopOrderID, $_auth['admin_name']);
	                }
    	            else if ($post['todo'] == 'unlock') {
	                    $this -> _api -> lockOrder($shopOrderID, '');
    	            }
    	            else if ($post['todo'] == 'back') {
	                    $this -> _api -> checkOrder($shopOrderID, -1);
	                }
	            }
	        }
	    }
	    
	    $datas = $this -> _api -> getOrder($params);
	    $datas = $this -> _api -> getOrderWithMerge($datas);
	    $datas['list'] = $this -> _api -> getDataWithPage($datas['list'], $page, 100);
	    
	    $logisticAPI = new Admin_Models_DB_Logistic();
	    foreach ($logisticAPI -> getLogisticList() as $key => $value)
    	{
    	    $logisticList[$value['logistic_code']] = $value['name'];
    	}
    	$this -> view -> logisticList = $logisticList;
	    
	    $this -> view -> datas = $datas['list'];
	    $this -> view -> amount = $datas['total']['amount'];
        $this -> view -> param = $params;
        $pageNav = new Custom_Model_PageNav($datas['total']['count'], 100, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
	}
	
	/**
     * 活动列表
     *
     * @return void
     */
    public function promotionListAction() {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        if ( !isset($search['search_time_type']) ) {
		    $search['search_time_type'] = array('0', '1');
		}
        $datas = $this -> _api -> getPromotion($search, 't1.*,t2.shop_name', null, $page, 25);
        
        $this -> view -> datas = $datas['list'];
        
        for ( $i = 0; $i < count($search['search_time_type']); $i++ ) {
            $search_time_type[$search['search_time_type'][$i]] = 1;
        }
        $search['search_time_type']  = $search_time_type;
        
        $this -> view -> param = $search;
        
	    $pageNav = new Custom_Model_PageNav($datas['total'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        
        $shopDatas = $this -> _api -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
	
	/**
     * 添加店铺
     *
     * @return   void
     */
	public function addAction()
	{
	    if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> edit($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, '/admin/shop/list', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> render('edit');
        }
	}
	
	/**
     * 编辑店铺
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> edit($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, '/admin/shop/list/', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $data = $this -> _api -> get("shop_id = {$id}");
                $data = array_shift( $data['list'] );
                $data['config'] = unserialize($data['config']);
                
                $shopAPI = Custom_Model_Shop_Base::getInstance($data['shop_type'], $data['config']);
                
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
                $this -> view -> config = $shopAPI -> getConfigField();
            }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 添加店铺活动
     *
     * @return void
     */
    public function promotionAddAction()
    {
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> editPromotion($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_PROMOTION_SUCCESS, '/admin/shop/promotion-list/', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
            $shopDatas = $this -> _api -> get();
            $this -> view -> shopDatas = $shopDatas['list'];
            
        	$this -> view -> action = 'add';
        	$this -> render('promotion-edit');
        }
    }
    
    /**
     * 编辑店铺活动
     *
     * @return void
     */
    public function promotionEditAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editPromotion($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::EDIT_PROMOTION_SUCCESS, '/admin/shop/promotion-list/', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $data = $this -> _api -> getPromotion("promotion_id = {$id}", 't1.*');
                $data = array_shift( $data['list'] );
                $data['config'] = unserialize($data['config']);
                $data['start_time'] = date('Y-m-d H:i:s', $data['start_time']);
                $data['end_time'] = date('Y-m-d H:i:s', $data['end_time']);
                
                $shopDatas = $this -> _api -> get();
                $this -> view -> shopDatas = $shopDatas['list'];
                
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
            }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
	
	/**
     * ajax更新店铺状态
     *
     * @return void
     */
    public function statusAction() {
        $this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
            $this -> _api -> ajaxUpdate($id, 'status', $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        
        echo $this -> _api -> ajaxStatus('/admin/shop/status', $id, $status);
    }
	
	/**
     * 生成店铺特殊字段(ajax)
     *
     * @return void
     */
    public function showConfigAreaAction() {
        $shopType = $this -> _request -> getParam('shopType');
        $shopID = (int)$this -> _request -> getParam('shopID', 0);
        
        if ( !$shopType ) {
            die('error type');
        }
        
        if ( $shopID ) {
            $data = $this -> _api -> get("shop_id = {$shopID}");
            $data = array_shift( $data['list'] );
            $data['config'] = unserialize($data['config']);
        }
        
        $shopAPI = Custom_Model_Shop_Base::getInstance($shopType, $data['config']);
        
        $configField = $shopAPI -> getConfigField();
        foreach ( $configField as $key => $name ) {
            if ( $data ) {
                $value = $data['config'][$key];
            }
            $result .= '<tr>
                          <td width="15%"><strong>'.$name.'</strong></td>
                          <td><input type="text" name="config['.$key.']" value="'.$value.'" size="30"></td>
                        </tr>';
        }
        
        echo $result;
        
        exit;
    }
    
    /**
     * 店铺同步
     *
     * @return void
     */
    public function syncAction() {
        $shopID = (int)$this -> _request -> getParam('id', 0);
        $actionName = $this -> _request -> getParam('action_name', null);
        
        $shopData = $this -> _api -> get("shop_id = {$shopID}");
        $shop = $shopData['list'][0];
        $shop['config'] = unserialize($shop['config']);
        
        $shopAPI = Custom_Model_Shop_Base::getInstance($shop['shop_type'], $shop['config']);
        
        if ( in_array($actionName, array('goods', 'order', 'stock', 'comment')) ) {
            $shopAPI -> initSyncLog();
            $this -> _api -> initSyncLog();
            
            if ( $actionName == 'goods' ) {
                $this -> _api -> deleteGoods("shop_id = {$shopID}");
                
                $goodsData = $shopAPI -> syncGoods();
                if ( $goodsData ) {
                    foreach ( $goodsData as $goods ) {
                        $goods['shop_id'] = $shopID;
                        $this -> _api -> updateGoods($goods);
                    }
                }
            }
            
            if ( $actionName == 'order' ) {
                $this -> _api -> updateOrderSyncTime($shopID);
                $orderSN = $this -> _request -> getParam('orderSN', null);
                if ($orderSN) {
                    if ($shop['shop_type'] == 'jingdong') {
                        $goods = '';
                        $shopGoodsData = $this -> _api -> getGoods(array('shop_id' => $shopID), 'shop_sku_id,goods_sn');
                        if ($shopGoodsData['list']) {
                            $goods = '';
                            foreach ($shopGoodsData['list'] as $shopGoods) {
                                $goods[$shopGoods['shop_sku_id']] = $shopGoods['goods_sn'];
                            }
                        }
                        $orderData = $shopAPI -> syncOneOrder($orderSN, $this -> _api -> getAllArea(), $goods);
                    }
                    else {
                        $orderData = $shopAPI -> syncOneOrder($orderSN, $this -> _api -> getAllArea());
                    }
                    if ($orderData) {
                        $orderData = array($orderData);
                    }
                }
                else {
                    $startDate = $this -> _request -> getParam('fromdate', null);
                    $endDate = $this -> _request -> getParam('todate', null);
                    if ($shop['shop_type'] == 'jingdong') {
                        $goods = '';
                        $shopGoodsData = $this -> _api -> getGoods(array('shop_id' => $shopID), 'shop_sku_id,goods_sn');
                        if ($shopGoodsData['list']) {
                            foreach ($shopGoodsData['list'] as $shopGoods) {
                                $goods[$shopGoods['shop_sku_id']] = $shopGoods['goods_sn'];
                            }
                        }
                        $orderData = $shopAPI -> syncOrder($this -> _api -> getAllArea(), $startDate, $endDate, $goods);
                    }
                    else {
                        $orderData = $shopAPI -> syncOrder($this -> _api -> getAllArea(), $startDate, $endDate);
                    }
                }
                if ( $orderData ) {
                    $orderData = $this -> _api -> getOrderLogistic($shopAPI, $orderData);
                    if ( $orderData ) {
                        foreach ( $orderData as $order ) {
                            if ($order['status'] != 1) {
                                $order['shop_id'] = $shopID;
                                $this -> _api -> updateOrder($order);
                            }
                        }
                    }
                }
            }
            
            if ( $actionName == 'stock' ) {
                $stockAPI = new Admin_Models_API_Stock();
                $goodsData = $this -> _api -> getGoods(array('shop_id' => $shopID));
                foreach ($goodsData['list'] as $goods) {
                    if (!$goods['goods_sn'])    continue;
                    $productSNArray[] = $goods['goods_sn'];
                    $outGoodIDArray[$goods['goods_sn']]['goods_id'] = $goods['shop_goods_id'];
                    $outGoodIDArray[$goods['goods_sn']]['sku_id'] = $goods['shop_sku_id'];
                }
                $productInfo = $this -> _api -> getProductIDSByGoodsSNArray($productSNArray);
                if ($productInfo) {
                    $productIDArray = array_keys($productInfo);
                    $stockData = $stockAPI -> getSaleProductStock($productIDArray);
                    foreach ($productInfo as $productID => $productSN) {
                        $stockInfo[] = array('goods_sn' => $productSN,
                                             'goods_id' => $outGoodIDArray[$productSN]['goods_id'],
                                             'sku_id' => $outGoodIDArray[$productSN]['sku_id'],
                                             'number' => $stockData[$productID]['able_number'] ? $stockData[$productID]['able_number'] : 0,
                                            );
                    }
                    $shopAPI -> syncStock($stockInfo);
                }
            }
            
            if ( $actionName == 'comment' ) {
                $goodsData = $this -> _api -> getGoods(array('shop_id' => $shopID));
                $productSNArray = $commentList = array();
                $msgAPI = new Admin_Models_DB_Msg();
                foreach ($goodsData['list'] as $index => $goods) {
                    if (!$goods['goods_sn'] || !$goods['shop_goods_id'])    continue;
                    
                    $comments = $shopAPI -> getComment($goods['shop_goods_id']);
                    if ($comments) {
                        $ipArray = array();
                        foreach ($comments as $comment) {
                            $ipArray[] = "'{$comment['ip']}'";
                        }
                        $goodsCommentList = $msgAPI -> getGoods(" and ip in (".implode(',', $ipArray).")", 'ip');
                        if ($goodsCommentList) {
                            $goodsIPArray = array();
                            foreach ($goodsCommentList as $goodsComment) {
                                $goodsIPArray[] = "'{$goodsComment['ip']}'";
                            }
                            $ipArray = array_diff($ipArray, $goodsIPArray);
                        }
                        if (count($ipArray) > 0) {
                            foreach ($comments as $comment) {
                                if (in_array("'{$comment['ip']}'", $ipArray)) {
                                    $commentList[$goods['goods_sn']][] = $comment;
                                }
                            }
                            $productSNArray[] = $goods['goods_sn'];
                        }
                    }
                }
                
                if (count($productSNArray) > 0) {
                    foreach ($productSNArray as $productSN) {
                        $whereArray[] = "'{$productSN}'";
                    }
                    
                    $goodsAPI = new Admin_Models_DB_Goods();
                    $goodsList = $goodsAPI -> fetchGoods("a.goods_sn in (".implode(',', $whereArray).")", 'a.goods_sn,a.goods_id,a.goods_name');
                    foreach ($goodsList as $goods) {
                        $goodsInfo[$goods['goods_sn']] = $goods;
                    }
                    
                    foreach ($commentList as $productSN => $comments) {
                        if (!$goodsInfo[$productSN])    continue;
                        
                        foreach ($comments as $comment) {
                            $row = array('goods_id' => $goodsInfo[$productSN]['goods_id'],
                                         'goods_name' => $goodsInfo[$productSN]['goods_name'],
                                         'level' => 0,
                                         'content' => $comment['content'],
                                         'add_time' => $comment['add_time'],
                                         'user_name' => $comment['user_name'],
                                         'real_name' => $comment['user_name'],
                                         'ip' => $comment['ip'],
                                         'type' => 1,
                                         'status' => 0,
                                         'cnt1' => mt_rand(4, 5),
                                         'cnt2' => mt_rand(4, 5),
                                        );
                            $msgAPI -> goodsCommentAdd($row);
                        }
                    }
                }
            }
            
            $logID = $this -> _api -> addSyncLog($shopID, $actionName, $shopAPI -> _startTime, $shopAPI -> _log.$this -> _api -> _log);
            
            $this->_redirect("/admin/shop/sync-log-detail/id/{$logID}");
            exit;
        }
        
        $fromdate = date('Y-m'.'-01', time());
        $todate = date('Y-m-d', time());
        
        $this -> view -> data = $shop;
        $this -> view -> fromdate = $fromdate;
        $this -> view -> todate = $todate;
    }
    
	/**
     * 店铺同步日志
     *
     * @return void
     */
    public function syncLogAction() {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> getSyncLog($search, $page, 25);
        if ( $datas['total'] > 0 ) {
            foreach ($datas['list'] as $num => $data) {
                $datas['list'][$num]['second'] = $data['end_time'] - $data['start_time'];
            }
        }
        
        $shopDatas = $this -> _api -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
        
        $this -> view -> datas = $datas['list'];
        $this -> view -> param = $search;
	    $pageNav = new Custom_Model_PageNav($datas['total'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
	/**
     * 店铺同步日志
     *
     * @return void
     */
    public function syncLogDetailAction() {
        $id = (int)$this -> _request -> getParam('id', 0);
        
        $datas = $this -> _api -> getSyncLog("id = {$id}");
        
        echo str_replace("\n", '<br>', $datas['list'][0]['content']);
        
        exit;
    }
    
    /**
     * Ajax订单字段修改
     *
     * @return   void
     */
	public function orderAjaxChangeAction()
	{
	    $id = (int)$this -> _request -> getParam('id', 0);
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val');

        if ($id > 0) {
            echo $this -> _api -> ajaxOrderUpdate($id, $field, $val);
        } else {
            exit('error!');
        }
        
        exit;
	}
	
	/**
     * 交行地址匹配
     *
     * @return   void
     */
	public function commGetAreaAction()
	{
	    $area = $this -> _request -> getParam('area');
	    $areaID = $this -> _request -> getParam('areaID', 0);
	    
	    $commAPI = new Custom_Model_Shop_Comm(null);
	    
	    $result = $commAPI -> mapCommAddress($area, $areaID, $this -> _api -> getAllArea());
	    if ( is_array($result) ) {
	        echo "{$area}<font color=blue>匹配成功!</font>";
	    }
	    else {
	        echo "{$area}<font color=red>匹配错误!</font>";
	    }
	    
	    exit;
	}
	
	/**
     * 订单地址修改
     *
     * @return   void
     */
	public function orderAddressAction()
	{
	    $id = $this -> _request -> getParam('id', 0);
	    $url = $this -> _request -> getParam('url');
	    
	    if ($this -> _request -> isPost()) {
	        $this -> _api -> changeOrderAddress($this -> _request -> getParams());
	        
	        if ( $url ) {
                $url = base64_decode($url);
            }
            else    $url = '/admin/shop/order-check-list';
            
	        $this->_redirect($url);
	        exit;
	    }
	    
	    $data = $this -> _api -> getOrder(array('shop_order_id' => $id), 1);
	    $data = $data['list'][0];
	    
	    $areaData = $this -> _api -> getAllArea();
	    
	    foreach ( $areaData[1] as $province => $province_id) {
	        $provinceList[] = array('province' => $province, 'province_id' => $province_id);
	    }
	    
	    if (!$data['addr_province_id']) {
	        $data['addr_province_id'] = $provinceList[0]['province_id'];
	    }
	    
	    foreach ( $areaData[$data['addr_province_id']] as $city => $city_id) {
    	    $cityList[] = array('city' => $city, 'city_id' => $city_id);
    	}
    	
    	if (!$data['addr_city_id']) {
	        $data['addr_city_id'] = $cityList[0]['city_id'];
	    }
    	
        foreach ( $areaData[$data['addr_city_id']] as $area => $area_id) {
        	$areaList[] = array('area' => $area, 'area_id' => $area_id);
	    }
	    
	    $this -> view -> data = $data;
	    $this -> view -> provinceList = $provinceList;
	    $this -> view -> cityList = $cityList;
	    $this -> view -> areaList = $areaList;
	    $this -> view -> url = $url;
	}
	
	/**
     * 订单发票修改
     *
     * @return   void
     */
	public function orderInvoiceAction()
	{
	    $id = $this -> _request -> getParam('id', 0);
	    $url = $this -> _request -> getParam('url');
	    
	    if ($this -> _request -> isPost()) {
	        $this -> _api -> changeOrderInvoice($this -> _request -> getParams());
	        
	        if ( $url ) {
                $url = base64_decode($url);
            }
            else    $url = '/admin/shop/order-check-list';
            
	        $this->_redirect($url);
	        exit;
	    }
	    
	    $data = $this -> _api -> getOrder(array('shop_order_id' => $id), 1);
	    $data = $data['list'][0];
	    
	    $this -> view -> data = $data;
	    $this -> view -> url = $url;
	}
	
	/**
     * 订单客服备注修改
     *
     * @return   void
     */
	public function orderAdminMemoAction()
	{
	    $id = $this -> _request -> getParam('id', 0);
	    $url = $this -> _request -> getParam('url');
	    if ($this -> _request -> isPost()) {
	        $this -> _api -> addOrderAdminMemo($this -> _request -> getParams());
	        
	        if ( $url ) {
                $url = base64_decode($url);
            }
            else    $url = '/admin/shop/order-check-list';
            
	        $this->_redirect($url);
	        exit;
	    }
	    
	    $data = $this -> _api -> getOrder(array('shop_order_id' => $id), 1);
	    $data = $data['list'][0];
	    
	    $this -> view -> data = $data;
	    $this -> view -> url = $url;
	    $this -> view -> view = $this -> _request -> getParam('view');
	}
	
	/**
     * 订单商品修改
     *
     * @return   void
     */
	public function orderGoodsAction()
	{
	    $id = $this -> _request -> getParam('id', 0);
	    $url = $this -> _request -> getParam('url');
	    
	    $data = $this -> _api -> getOrder(array('shop_order_id' => $id), 1);
	    $order = $data['list'][0];
	    if ($order['status_business'] != 0 && $order['status_business'] != 9) {
	        die("该订单状态不能修改商品明细");
	    }
	    
	    if ($this -> _request -> isPost()) {
	        $post = $this -> _request -> getPost();
	        $this -> _api -> updateOrderGoods($order, $post);
	        
	        if ( $url ) {
                $url = base64_decode($url);
            }
            else    $url = '/admin/shop/order-check-list';
            
	        $this->_redirect($url);
	        exit;
	    }
	    
	    $this -> view -> url = $url;
	    $this -> view -> order = $order;
	}
	
	/**
     * 获得地区(Ajax)
     *
     * @return   void
     */
	public function getAreaAction()
	{
	    $id = $this -> _request -> getParam('id', 0);
	    $type = $this -> _request -> getParam('type');
	    
	    $areaData = $this -> _api -> getAllArea();
	    
	    foreach ( $areaData[$id] as $area => $area_id ) {
	        $result .= "<option value=\"{$area_id}\">{$area}</option>\n";
	    }
	    
	    echo $result;
	    
	    exit;
	}
	
	/**
     * 同步订单到官网
     *
     * @return   void
     */
	public function syncOrderAction()
	{
	    $shopID = $this -> _request -> getParam('id', 0);
	    $shopData = $this -> _api -> get("shop_id = {$shopID}");
        $shop = $shopData['list'][0];
        $shop['config'] = unserialize($shop['config']);
        $shopAPI = Custom_Model_Shop_Base::getInstance($shop['shop_type'], $shop['config']);
	    
	    $shopAPI -> initSyncLog();
	    $this -> _api -> _log = '';
	    
	    $this -> _api -> syncOrder($shopAPI, $shopID, $shopOrderID);
	    
	    $logID = $this -> _api -> addSyncLog($shopID, 'sync', $shopAPI -> _startTime, $shopAPI -> _log.$this -> _api -> _log);
	    $this->_redirect("/admin/shop/sync-log-detail/id/{$logID}");
	    exit;
	}
	
	/**
     * 打印拣货单
     *
     * @return   void
     */
	public function printSumAction()
	{
	    $params = $this -> _request -> getParams();
	    if (!$params['ids'])    exit;
	    
	    $params['shop_order_ids'] = $params['ids'];
	    $datas = $this -> _api -> getOrder($params);
	    $datas = $datas['list'];
	    
	    if ( !$datas )  die('无订单');
	    
	    $shopID = $datas[0]['shop_id'];
	    $shopData = $this -> _api -> get("shop_id = {$shopID}");
	    $shop = $shopData['list'][0];
	    
	    $printData['shopID'] = $shopID;
	    $printData['shopName'] = $shop['shop_name'];
	    $printData['startDate'] = $params['fromdate'];
	    $printData['endDate'] = $params['todate'];
	    $printData['totalGoodsNumber'] = 0;
	    $printData['totalOrderNumber'] = 0;

        $groupGoodsAPI = new Admin_Models_API_GroupGoods();
        $outStockAPI = new Admin_Models_API_OutStock();
        $stockAPI = new Admin_Models_API_Stock();
        $logisticAPI = new Admin_Models_DB_Logistic();
	    foreach ($logisticAPI -> getLogisticList() as $key => $value) {
    	    $logisticList[$value['logistic_code']] = $value['name'];
    	}
        
	    foreach ( $datas as $index => $order ) {
	        $printData['totalOrderNumber']++;
	        
	        $details = $outStockAPI -> getDetail("bill_no='".$this -> _api -> getOutStockSN($order)."'");
	        foreach ($details as $goods) {
	            if (!isset($positionInfo[$goods['product_id']][$goods['batch_id']])) {
    	            $positionData = $stockAPI -> getProductPosition(array('area' => $goods['lid'], 'product_id' => $goods['product_id'], 'batch_id' => $goods['batch_id']));
                    if ($positionData) {
                        foreach ($positionData as $position) {
                            $positionInfo[$goods['product_id']][$goods['batch_id']] .= $position['position_no'].'<br>';
                        }
                    }
                    else {
                        $positionInfo[$goods['product_id']][$goods['batch_id']] = '';
                    }
                }
	        }
	        
	        foreach ($details as $goods) {
	            $goodsInfo[$goods['product_sn']][$goods['batch_no']]['goodsName'] = $goods['product_name'];
	            $goodsInfo[$goods['product_sn']][$goods['batch_no']]['goodsSN'] = $goods['product_sn'];
	            $goodsInfo[$goods['product_sn']][$goods['batch_no']]['goodsNumber'] += $goods['number'];
	            $goodsInfo[$goods['product_sn']][$goods['batch_no']]['orderInfo'][$goods['number']]++;
	            $printData['totalGoodsNumber'] += $goods['number'];
	            $goodsInfo[$goods['product_sn']][$goods['batch_no']]['orderSNArray'][$order['external_order_sn']] += $goods['number'];
	            $goodsInfo[$goods['product_sn']][$goods['batch_no']]['localSN'] = $positionInfo[$goods['product_id']][$goods['batch_id']];
	            
	            $goodsArray[$order['external_order_sn']][] = array('goodsName' => $goods['product_name'],
	                                                               'goodsSN' => $goods['product_sn'],
	                                                               'goodsNumber' => $goods['number'],
	                                                              );
	        }
             
	        $datas[$index]['logistic_name'] = $logisticList[$order['logistic_code']];
	        
	        $datas[$index]['invoice'] = $order['invoice'];
	        if ($order['shop_type'] == 'jingdong') {
                if ($order['invoice'] != '' && $order['invoice'] != '不需要开具发票') {
                    if (strpos($order['invoice'], '个人') !== false) {
                        $datas[$index]['invoice'] = '个人';
                    }
                    else {
                        preg_match('/发票类型:(.*);发票抬头:(.*);发票内容:/', $order['invoice'], $match);
                         $datas[$index]['invoice'] = $match[1].': '.$match[2];
                    }
                }
            }
	    }
	    
	    if ( $goodsInfo ) {
	        uasort($goodsInfo, 'compareProductLocalSN');
	        
	        foreach ( $goodsInfo as $goodsSN => $goodsInfo1 ) {
	            foreach ($goodsInfo1 as $batchNo => $goods) {
	                $goodsInfo[$goodsSN][$batchNo]['info'] = $this -> _api -> getProductInfoByGoodsSN($goods['goodsSN']);
	                
	                foreach ( $goods['orderSNArray'] as $orderSN => $number ) {
    	                $goodsInfo[$goodsSN][$batchNo]['orderSN'][] = "{$orderSN}({$number})";
    	            }
    	            $goodsInfo[$goodsSN][$batchNo]['orderSN'] = implode('<br>', $goodsInfo[$goodsSN][$batchNo]['orderSN']);
    	        }
	        }
	    }
	    
        $this -> view -> datas = $datas;
	    $this -> view -> printData = $printData;
	    $this -> view -> goodsInfo = $goodsInfo;
	    $this -> view -> goodsArray = $goodsArray;
	    $this -> view -> auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}
	
	/**
     * 导出订单
     *
     * @return   void
     */
	public function exportAction()
	{
	    $params = $this -> _request -> getParams();
	    unset($params['province']);
	    $datas = $this -> _api -> getOrder($params);
	    
	    if (!$datas['list'])    die('没有符合条件的订单');
	    
	    $shopData = $this -> _api -> get();
	    foreach ( $shopData['list'] as $shop ) {
	        $shopInfo[$shop['shop_id']] = $shop['shop_name'];
	    }
	    
	    $content[] = array('ID', '店铺', '订单号', '订单金额', '优惠金额', '运费', '订单商品', '收货人', '下单时间', 
	                       '电话', '手机', '发票', '发票内容', '备注', '物流公司', '运单号', '发货时间', '订单状态', '刷单');
	    foreach ( $datas['list'] as $data ) {
	        $goodsStr = '';
	        foreach ( $data['goods'] as $goods ) {
	            $goodsStr .= $goods['shop_goods_name'].' * '.$goods['number'].'　';
	        }
	        $content[] = array($data['shop_order_id'], $shopInfo[$data['shop_id']], $data['external_order_sn'], $data['amount'], $data['discount_amount'], 
	                           $data['freight'], $goodsStr, $data['addr_consignee'], date('Y-m-d H:i:s', $data['order_time']), 
	                           //$data['addr_province'].$data['addr_city'].$data['addr_area'].$data['addr_address'],
	                           $data['addr_tel'],
	                           $data['addr_mobile'], $data['invoice'], $data['invoice_content'], $data['memo'], $data['logistic_code'], $data['logistic_no'], $data['logistic_time'] ? date('Y-m-d H:i:s', $data['logistic_time']) : '',
	                           $this -> getStatusStr($data['status']), $data['is_fake'] ? '是' : '否');
	    }
	    
	    $this -> _api -> exportLog($content, $params);
        
	    $xls = new Custom_Model_GenExcel();
		$xls -> addArray($content);
	    $xls -> generateXML('shop_order');
	    
		exit();
	}
	
	/**
     * 导出开票订单
     *
     * @return   void
     */
	public function exportInvoiceAction()
	{
	    $params = $this -> _request -> getParams();
	    if (!$params['ids'] || count($params['ids']) == 0) {
	        die('没有符合条件的订单');
	    }
	    
	    $params['shop_order_ids'] = $params['ids'];
	    $datas = $this -> _api -> getOrder($params);

	    if (!$datas['list'])    die('没有符合条件的订单');
	    
	    $shopData = $this -> _api -> get();
	    foreach ( $shopData['list'] as $shop ) {
	        $shopInfo[$shop['shop_id']] = $shop['shop_name'];
	    }
	    
	    $content[] = array('店铺', '订单号', '订单金额', '订单商品', '收货人', '下单时间', '发票抬头', '发票内容');
	    foreach ( $datas['list'] as $data ) {
	        $goodsStr = '';
	        foreach ( $data['goods'] as $goods ) {
	            $goodsStr .= $goods['shop_goods_name'].' * '.$goods['number'].'　';
	        }
	        $content[] = array($shopInfo[$data['shop_id']], $data['external_order_sn'], $data['amount'], $goodsStr, 
	                           $data['addr_consignee'], date('Y-m-d H:i:s', $data['order_time']), $data['invoice'], $data['invoice_content']);
	        $this -> _api -> updateOrderInvoiceStatus($data['shop_order_id'], 1);
	    }
	    
	    $xls = new Custom_Model_GenExcel();
		$xls -> addArray($content);
	    $xls -> generateXML('shop_order_invoice');
	    
		exit();
	}
	
	/**
     * 订单商品发货统计
     *
     * @return   void
     */
	public function sendGoodsReportAction() 
	{
	    $params = $this -> _request -> getParams();
	    $datas = $this -> _api -> getOrder($params);
	    $datas = $datas['list'];
	    
	    if ( !$datas )  die('无订单');
	    
	    $shopID = $datas[0]['shop_id'];
	    $shopData = $this -> _api -> get("shop_id = {$shopID}");
	    $shop = $shopData['list'][0];
	    
	    $printData['shopID'] = $shopID;
	    $printData['shopName'] = $shop['shop_name'];
	    $printData['startDate'] = $params['fromdate'];
	    $printData['endDate'] = $params['todate'];
	    $printData['totalGoodsNumber'] = 0;
	    $printData['totalOrderNumber'] = 0;
        
	    foreach ( $datas as $order ) {
	        if ( !in_array($order['status'], array(2,3,10)) )   continue;
	        
	        $printData['totalOrderNumber']++;
	        
	        foreach ( $order['goods'] as $goods ) {
	            if ( !$goods['goods_sn'] ) {
	                die("订单{$order['external_order_sn']}无商品编号");
	            }
	            $goodsInfo[$goods['goods_sn']]['goodsName'] = $goods['shop_goods_name'];
	            $goodsInfo[$goods['goods_sn']]['goodsSN'] = $goods['goods_sn'];
	            
	            if ( $order['status'] == 2 ) {
	                $goodsInfo[$goods['goods_sn']]['wait'] += $goods['number'];
	            }
	            else    $goodsInfo[$goods['goods_sn']]['sent'] += $goods['number'];
	            
	            $printData['totalGoodsNumber'] += $goods['number'];
	        }
	    }
        
	    if ( $goodsInfo ) {
	        foreach ( $goodsInfo as $goodsSN => $goods ) {
	            $goodsInfo[$goodsSN]['goodsName'] = $this -> _api -> getProductNameByGoodsSN($goods['goodsSN']);
	        }
	    }
	    
	    $this -> view -> printData = $printData;
	    $this -> view -> goodsInfo = $goodsInfo;
	    $this -> view -> auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}
	
	/**
     * 订单状态
     *
     * @return   void
     */
	public function getStatusStr($status) 
	{
	    $statusInfo[1] = '待收款';
	    $statusInfo[2] = '待发货';
	    $statusInfo[3] = '待确认收货';
	    $statusInfo[10] = '已完成';
	    $statusInfo[11] = '已取消';
	    $statusInfo[12] = '其它';
	    
	    return $statusInfo[$status];
	}
	
	/**
     * 打印运输单
     *
     * @return void
     */
    public function printLogisticsAction()
    {
    	$post = $this -> _request -> getParams();
    	
    	if ( $post['id'] ) {
    	    $post['ids'] = array($post['id']);
    	}
    	
    	if ( !$post['ids'] || count($post['ids']) == 0 ) {
    	    die('没有打印订单!');
    	}
    	
    	$datas = $this -> _api -> getOrder(array('shop_order_ids' => $post['ids']));
    	if ( !$datas['list'] ) {
    	    die('订单找不到!');
    	}
    	
    	$is_cod = '';
    	if ( !$post['id'] ) {
    	    $_auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        	foreach ( $datas['list'] as $data ) {
        	    if ($data['lock_admin_name'] != '' && $data['lock_admin_name'] != $_auth['admin_name']) {
        	        die("订单{$data['external_order_sn']}已被其他用户锁定，不能打印!");
        	    }
        	    if ($is_cod === '') {
        	        $is_cod = $data['is_cod'];
        	    }
        	    else {
        	        if ($is_cod != $data['is_cod']) {
        	            die("订单付款方式不一致，不能打印!");
        	        }
        	    }
        	    $status = $this -> _api -> getOrderStatus($data['external_order_sn'], $data['shop_id']);
        	    if ($status) {
        	        if ($status == 13) {
        	            die("订单{$data['external_order_sn']}发生退款，不能打印!");
        	        }
        	        if ($status != 2) {
        	            die("订单{$data['external_order_sn']}状态改变，不能打印!");
        	        }
        	    }
        	}
    	}
    	
    	$datas = $this -> _api -> getOrderWithMerge($datas);
    	$datas['list'] = $this -> _api -> mergeOrder($datas['list']);
    	
    	$logisticAPI = new Admin_Models_API_Logistic();
    	
    	$logisticCode = '';
    	foreach ( $datas['list'] as $order) {
    	    if (!$logisticCode) {
    	        $logisticCode = $order['logistic_code'];
    	        if ($is_cod) {
    	            $template = $logisticAPI -> getLogisticTemplate($logisticCode, 1);
    	        }
    	        else {
    	            $template = $logisticAPI -> getLogisticTemplate($logisticCode, 2);
    	        }
            	if ( !$template ) {
                    die('找不到对应模板!');
                }
    	    }
    	    else {
    	        if ( $order['logistic_code'] != $logisticCode ) {
    	            die('打印的运输单类型不一致!');
    	        }
    	    }
    	    
    	    $goodsNumber = 0;
    	    foreach ($order['goods'] as $goods ) {
    	        $goodsNumber += $goods['number'];
    	    }
    	    $data = array('consignee' => $order['addr_consignee'],
    	                  'tel' => $order['addr_tel'],
    	                  'mobile' => $order['addr_mobile'],
    	                  'validate_sn' => $order['validate_sn'],
    	                  'province' => $order['addr_province'],
    	                  'city' => $order['addr_city'],
    	                  'area' => $order['addr_area'],
    	                  'address' => $order['addr_address'],
    	                  'bill_no' => $order['external_order_sn'],
    	                  'print_remark' => $order['memo'],
    	                  'goods_number' => $goodsNumber,
    	                  'amount' => $order['amount'],
    	                  'brand' => $order['shop_name'],
    	                  'logistic_code' => $order['logistic_code'],
    	                 );
    	    $templates[] = $logisticAPI -> parseTemplate($template, $data);
    	}
    	
    	$this -> view -> templates = $templates;
        echo $this -> view -> render('transport/print2.tpl');
        exit;
    }
    
    /**
     * 打印销售单
     *
     * @return void
     */
    public function printSalesAction()
    {
    	$post = $this -> _request -> getParams();
    	
    	if ( $post['id'] ) {
    	    $post['ids'] = array($post['id']);
    	}
    	
    	if ( !$post['ids'] || count($post['ids']) == 0) {
    	    die('没有打印订单!');
    	}
    	
    	$orderDatas = $this -> _api -> getOrder(array('shop_order_ids' => $post['ids']));
    	if ( !$orderDatas['list'] ) {
    	    die('订单找不到!');
    	}
    	
    	$shopData = $this -> _api -> get();
	    foreach ( $shopData['list'] as $shop ) {
	        $shopInfo[$shop['shop_id']] = $shop['shop_name'];
	    }
	    
    	if (!$post['id']) {
        	$_auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        	foreach ( $orderDatas['list'] as $data ) {
        	    if ($data['lock_admin_name'] != '' && $data['lock_admin_name'] != $_auth['admin_name']) {
        	        die("订单{$data['external_order_sn']}已被其他用户锁定，不能打印!");
        	    }
        	    
        	    $status = $this -> _api -> getOrderStatus($data['external_order_sn'], $data['shop_id']);
        	    if ($status) {
        	        if ($status == 13) {
        	            die("订单{$data['external_order_sn']}发生退款，不能打印!");
        	        }
        	        if ($status != 2) {
        	            die("订单{$data['external_order_sn']}状态改变，不能打印!");
        	        }
        	    }
        	}
    	}
    	
    	$logisticAPI = new Admin_Models_DB_Logistic();
	    foreach ($logisticAPI -> getLogisticList() as $key => $value) {
    	    $logisticList[$value['logistic_code']] = $value['name'];
    	}
    	
    	$orderDatas = $this -> _api -> getOrderWithMerge($orderDatas);
    	$orderDatas['list'] = $this -> _api -> mergeOrder($orderDatas['list']);
        
        $outstockAPI = new Admin_Models_API_OutStock();
        
    	$datas = '';
    	foreach ( $orderDatas['list'] as $data ) {
    	    if ( !$this -> _areaZip[$data['addr_area_id']] ) {
    	        $this -> _areaZip[$data['addr_area_id']] = $this -> _api -> getArea($data['addr_area_id']);
    	    }
    	    if (!$data['logistic_no']) {
    	        die('请先设定订单的物流单号!');
    	    }
    	    $bill = array('bill_no' => $data['external_order_sn'],
    	                  'add_time' => $data['order_time'],
    	                  'transport' => array('zip' => $this -> _areaZip[$data['addr_area_id']]['zip'], 'amount' => $data['amount']),
    	                  'logistic_no' => $data['logistic_no']
    	                 );
            $orderSNArray = explode(' ', $data['external_order_sn']);
    	    if (count($orderSNArray) > 1) {
    	        $bill['bill_no_array'] = $orderSNArray;
    	    }
    	    $order = array('addr_consignee' => $data['addr_consignee'],
    	                   'addr_mobile' => $data['addr_mobile'],
    	                   'addr_province' => $data['addr_province'],
    	                   'addr_city' => $data['addr_city'],
    	                   'addr_area' => $data['addr_area'],
    	                   'addr_address' => $data['addr_address'],
    	                   'price_goods' => $data['goods_amount'],
    	                   'price_logistic' => $data['freight'],
    	                   'price_order' => $data['amount'],
    	                   'price_pay' => $data['amount'],
    	                   'note' => $data['memo'],
    	                   'note_logistic' => '',
    	                   'invoice_type' => $data['invoice'] ? 1 : 0,
    	                   'invoice' => $data['invoice'],
    	                   'invoice_content' => $data['invoice_content'],
    	                   'logistic_name' => $logisticList[$data['logistic_code']],
    	                   'shop_id' => $data['shop_id'],
    	                   'pay_type' => $data['is_cod'] ? 'cod' : 'external',
    	                   'note_logistic' => $data['admin_memo'],
    	                  );
    	    $details = '';
    	    $goodsNumber = $discount = $goodsAmount = 0;
    	    foreach ($data['goods'] as $goods) {
    	        if (strlen($goods['goods_sn']) == 9) {
    	            if (!$this -> _groupGoodsAPI) {
    	                $this -> _groupGoodsAPI = new Admin_Models_API_GroupGoods();
    	            }
                    $groupGoods = $this -> _groupGoodsAPI -> fetchConfigGoods(array('group_sn' => $goods['goods_sn']));

					if(count($groupGoods)){
						foreach ($groupGoods as $key => $detail) {
							$details[] = array('product_sn' => $detail['product_sn'],
											   'product_id' => $detail['product_id'],
											   'goods_name' => $detail['goods_name'],
											   'goods_style' => $detail['goods_style'],
											   'number' => $goods['number'] * $detail['number'],
											   'sum_price' => $goods['price'] * $goods['number'],
											   'discount' => $goods['discount_price'],
											   'group' => 1,
											  );
							$goodsNumber += $goods['number'] * $detail['number'];
							$discount += $goods['discount_price'];
							$goodsAmount += $goods['price'] * $goods['number'];
						}

					}else{
						die("编号{$goods['goods_sn']}商品不存在!");
					}


    	        }
    	        else {
    	            $goodsInfo = $this -> _api -> getProductInfoByGoodsSN($goods['goods_sn']);
    	            $details[] = array('product_sn' => $goods['goods_sn'],
    	                               'product_id' => $goodsInfo['product_id'],
            	                       'goods_name' => $goodsInfo['product_name'],
            	                       'goods_style' => $goodsInfo['goods_style'],
            	                       'number' => $goods['number'],
            	                       'shop_price' => $goods['price'],
            	                       'sale_price' => $goods['price'],
            	                       'discount' => $goods['discount_price'],
            	                      );
            	    $goodsNumber += $goods['number'];
            	    $discount += $goods['discount_price'];
            	    $goodsAmount += $goods['price'] * $goods['number'];
    	        }
    	    }
    	    
    	    //验证出库单和销售单的产品和数量
    	    $outStockSNArray = array();
    	    foreach ($orderSNArray as $OrderSN) {
    	        $tempData = $data;
    	        $tempData['external_order_sn'] = $OrderSN;
    	        $outStockSNArray[] = "'".$this -> _api -> getOutStockSN($tempData)."'";
    	    }
    	    $outstockDetailData = $outstockAPI -> getDetail("b.bill_no in (".implode(',', $outStockSNArray).")");
    	    if (!$outstockDetailData) {
    	        die("订单号{$data['external_order_sn']}找不到出库信息!");
    	    }
    	    $outstockMap = array();
    	    foreach ($outstockDetailData as $outstockDetail) {
    	        $outstockMap[$outstockDetail['product_id']] += $outstockDetail['number'];
    	    }
    	    $tempDetail = array();
    	    foreach ($details as $detail) {
    	        $tempDetail[$detail['product_id']] += $detail['number'];
    	    }
    	    if (count($outstockMap) != count($tempDetail)) {
    	        die("订单号{$data['external_order_sn']}销售产品和出库产品不匹配!");
    	    }
    	    foreach ($tempDetail as $productID => $number) {
    	        if (!$outstockMap[$productID] || $outstockMap[$productID] != $number) {
    	            die("订单号{$data['external_order_sn']}销售产品和出库产品不匹配!");
    	        }
    	    }
    	    
            $order['product'] = $details;
            $order['discount'] = $discount * -1;
            $order['price_goods'] = $goodsAmount;
    	    $datas[] = array('bill' => $bill,
    	                     'order' => $order,
    	                     'details' => $details,
    	                     'total_number' => $goodsNumber,
    	                    );
    	}

    	$this -> view -> datas = $datas;
    	$this -> view -> shopInfo = $shopInfo;
	    echo $this -> view -> render('logic-area-out-stock/print-orders.tpl');
    	exit;
    }
    
    /**
     * 订单打印状态，物流单更新
     *
     * @return void
     */
    public function orderPrintInputAction() 
    {
        $params = $this -> _request -> getParams();
        
        if ($params['id']) {
	        $datas = $this -> _api -> getOrder(array('shop_id' => $this -> _request -> getParam('shop_id'),
                                                     'shop_order_id' => $params['id'],
                                                     'status' => 2,
                                                     'status_business' => 2,
                                                     'sync' => 0,
                                                    )
                                              );
            if ( !$datas['list'][0] ) {
                die("订单ID{$params['id']}找不到或者订单状态不正确");
            }
	    }
	    else if ($params['ids']) {
	        $params['ids'] = explode(',', $params['ids']);
	        $where = array('shop_id' => $params['shop_id'],
                           'shop_order_ids' => $params['ids'],
                           'status' => 2,
                           'sync' => 0,
                          );
	        if ($params['direct'])  $where['status_business'] = 1;
	        else                    $where['status_business'] = 2;
	        $datas = $this -> _api -> getOrder($where);
            if ( !$datas['list'] ) {
                die("没有订单");
            }
            
            $orderIDArray = array();
            foreach ( $datas['list'] as $order ) {
                $orderIDArray[] = $order['shop_order_id'];
            }
            foreach ( $params['ids'] as $id ) {
                if ( !in_array($id, $orderIDArray) ) {
                    die("订单ID{$id}找不到或者订单状态不正确");
                }
            }
	    }
	    
	    if ($datas['list']) {
	        $allHaveLogisticNo = true;
	        $datas = $this -> _api -> getOrderWithMerge($datas);
    	    foreach ($datas['list'] as $order) {
    	        $status = $this -> _api -> getOrderStatus($order['external_order_sn'], $order['shop_id']);
                if ($status) {
                    if ($status == 13) {
                        die("订单{$order['external_order_sn']}发生退款，不能进行发货");
                    }
                    if ($status != 2) {
                        die("订单{$order['external_order_sn']}状态改变，不能进行发货");
                    }
                }
                
                if (!$order['logistic_no']) {
                    $allHaveLogisticNo = false;
                }
    	    }
	    }
	    
        $this -> view -> param = $params;
        $this -> view -> datas = $datas['list'];
        $this -> view -> allHaveLogisticNo = $allHaveLogisticNo;
    }
    
    /**
     * 订单发货交互
     *
     * @return void
     */
    public function orderSendInputAction() 
    {
        $params = $this -> _request -> getParams();

        if ($params['doimport']) {
	        if( is_file($_FILES['import_file']['tmp_name']) ) {
	            $xls = new Custom_Model_ExcelReader();
                $xls -> setOutputEncoding('utf-8');
			    $xls -> read($_FILES['import_file']['tmp_name']);
			    $datas = $xls -> sheets[0];
			    $lines = $datas['cells'];
			    
			    if ( is_array($lines) ) {
			        $orderSNArray = array();
                    for ( $i = 2; $i <= count($lines); $i++ ) {
                        $lines[$i][1] = trim($lines[$i][1]);
                        $lines[$i][2] = trim($lines[$i][2]);
                        $orderSNArray[] = "'{$lines[$i][1]}'";
                    }
                    if ( count($orderSNArray) == 0 )    exit;
                    
                    $datas = $this -> _api -> getOrder(array('shop_id' => $this -> _request -> getParam('shop_id'),
                                                             'external_order_sns' => implode(',', $orderSNArray),
                                                             'status' => 2,
                                                             'status_business' => 2,
                                                             'sync' => 0,
                                                            )
                                                      );
                    if ($datas['total'] > 0 ) {
                        foreach ( $datas['list'] as $order ) {
                            $status = $this -> _api -> getOrderStatus($order['external_order_sn'], $order['shop_id']);
                	        if ($status) {
                	            if ($status == 13) {
                	                echo '<script language="JavaScript" type="text/javascript">';
                                    echo "alert('订单{$order['external_order_sn']}发生退款，不能进行发货')";
                                    echo "</script>";
                                    exit;
                	            }
                	            if ($status != 2) {
                	                echo '<script language="JavaScript" type="text/javascript">';
                                    echo "alert('订单{$order['external_order_sn']}状态改变，不能进行发货')";
                                    echo "</script>";
                                    exit;
                	            }
                	        }
                            
                            $orderInfo[$order['external_order_sn']] = Zend_Json::encode(array('id' => $order['shop_order_id'],
                                                                                              'external_order_sn' => $order['external_order_sn'],
                                                                                              'amount' => $order['amount'],
                                                                                              'addr_consignee' => $order['addr_consignee'],
                                                                                              'order_time' => date('Y-m-d', $order['order_time']),
                                                                                              'logistic_code' => $order['logistic_code'],
                                                                                             )
                                                                                       );
                        }
                    }
                    
                    echo '<script language="JavaScript" type="text/javascript">';
                    echo 'parent.delAllRow();';
                    $index = 0;
                    $canSend = true;
                    for ( $i = 2; $i <= count($lines); $i++ ) {
                        if ( !$orderInfo[$lines[$i][1]] )   $canSend = false;
                        echo "parent.insertRow({$index}, '{$lines[$i][1]}', '{$lines[$i][2]}', '{$orderInfo[$lines[$i][1]]}');";
                        $index++;
                    }
                    if ($canSend) {
                        echo 'parent.showImportButton();';
                    }
                    else {
                        echo 'parent.hideImportButton();';
                    }
                    echo "</script>";
                }
	        }
	        
	        exit;
	    }
	    
	    if ($params['id']) {
	        $datas = $this -> _api -> getOrder(array('shop_id' => $this -> _request -> getParam('shop_id'),
                                                     'shop_order_id' => $params['id'],
                                                     'status' => 2,
                                                     'status_business' => 2,
                                                     'sync' => 0,
                                                    )
                                              );
            if ( !$datas['list'][0] ) {
                die("订单ID{$params['id']}找不到或者订单状态不正确");
            }
	    }
	    else if ($params['ids']) {
	        $params['ids'] = explode(',', $params['ids']);
	        $where = array('shop_id' => $params['shop_id'],
                           'shop_order_ids' => $params['ids'],
                           'status' => 2,
                           'sync' => 0,
                          );
	        if ($params['direct'])  $where['status_business'] = 1;
	        else                    $where['status_business'] = 2;
	        $datas = $this -> _api -> getOrder($where);
            if ( !$datas['list'] ) {
                die("没有订单");
            }
            
            $orderIDArray = array();
            foreach ( $datas['list'] as $order ) {
                $orderIDArray[] = $order['shop_order_id'];
            }
            foreach ( $params['ids'] as $id ) {
                if ( !in_array($id, $orderIDArray) ) {
                    die("订单ID{$id}找不到或者订单状态不正确");
                }
            }
	    }
	    
	    if ($datas['list']) {
	        $allHaveLogisticNo = true;
	        $datas = $this -> _api -> getOrderWithMerge($datas);
    	    foreach ($datas['list'] as $order) {
    	        $status = $this -> _api -> getOrderStatus($order['external_order_sn'], $order['shop_id']);
                if ($status) {
                    if ($status == 13) {
                        die("订单{$order['external_order_sn']}发生退款，不能进行发货");
                    }
                    if ($status != 2) {
                        die("订单{$order['external_order_sn']}状态改变，不能进行发货");
                    }
                }
                
                if (!$order['logistic_no']) {
                    $allHaveLogisticNo = false;
                }
    	    }
	    }
	    
        $this -> view -> param = $params;
        $this -> view -> datas = $datas['list'];
        $this -> view -> allHaveLogisticNo = $allHaveLogisticNo;
    }
    
    /**
     * 订单填充物流单
     *
     * @return void
     */
    public function orderFillAction() 
    {
        $params = $this -> _request -> getParams();
        
        if (!$params['order_sns'] || !$params['logistics']) {
            die('没有订单物流信息');
        }
        
        $orderSNArray = array();
        for ($i = 0; $i < count($params['order_sns']); $i++) {
            $orderSNArray[] = "'{$params['order_sns'][$i]}'";
        }
        
        $datas = $this -> _api -> getOrder(array('external_order_sns' => implode(',', $orderSNArray),
                                                )
                                          );
        if ( $datas['total'] == 0 ) {
            die('找不到对应的订单');
        }
        
        $orderSNArray = array();
        foreach ( $datas['list'] as $order ) {
            if ( $order['status'] != 2 || !in_array($order['status_business'], array(1)) || $order['sync'] || !$order['logistic_code'] ) {
                die("订单{$order['external_order_sn']}状态不正确，不能填充");
            }
            $orderSNArray[] = $order['external_order_sn'];
            $orderShopIDInfo[$order['external_order_sn']] = $order['shop_id'];
        }
        
        foreach ( $params['order_sns'] as $key => $order_sn ) {
            if ( !$params['logistics'][$key] ) {
                die("订单{$order_sn}运输单没有，不能填充");
            }
            if ( !in_array($order_sn, $orderSNArray) ) {
                die("订单{$order_sn}找不到，不能填充");
            }
        }
        
        foreach ( $params['order_sns'] as $key => $order_sn ) {
            $shopOrder['shop_id'] = $orderShopIDInfo[$order_sn];
            $shopOrder['external_order_sn'] = $order_sn;
            $shopOrder['logistic_no'] = $params['logistics'][$key];
            $this -> _api -> updateOrderLogisticNo($shopOrder);
        }
        
        $this->_redirect("/admin/shop/order-print-list/shop_id/{$shopID}");
    }
    
    /**
     * 订单发货
     *
     * @return void
     */
    public function orderSendAction() 
    {
        $params = $this -> _request -> getParams();
        
        $shopID  = $params['shop_id'];
        if ( !$shopID ) {
            die('没有店铺ID');
        }
        if ( !$params['order_sns'] || !$params['logistics'] ) {
            die('没有订单物流信息');
        }
        
        $orderSNArray = array();
        for ( $i = 0; $i < count($params['order_sns']); $i++ ) {
            $orderSNArray[] = "'{$params['order_sns'][$i]}'";
        }
        
        $datas = $this -> _api -> getOrder(array('shop_id' => $shopID,
                                                 'external_order_sns' => implode(',', $orderSNArray),
                                                )
                                          );
        if ( $datas['total'] == 0 ) {
            die('找不到对应的订单');
        }
        
        $logisticAPI = new Admin_Models_API_Logistic();
        $orderSNArray = array();
        foreach ( $datas['list'] as $order ) {
            if ( $order['status'] != 2 || !in_array($order['status_business'], array(1,2))  || $order['sync'] || !$order['logistic_code'] ) {
                die("订单{$order['external_order_sn']}状态不正确，不能发货");
            }
            $orderSNArray[] = $order['external_order_sn'];
            if (!$this -> logisticList[$order['logistic_code']]) {
                $logistic = $logisticAPI -> getLogisticByID($order['logistic_code']);
                $this -> logisticList[$order['logistic_code']] = $logistic['name'];
            }
            $orderLogistics[$order['external_order_sn']] = array('logistic_code' => $order['logistic_code'],
                                                                 'logistic_name' => $this -> logisticList[$order['logistic_code']],
                                                                 'addr_consignee' => $order['addr_consignee'],
                                                                 'addr_mobile' => $order['addr_mobile'],
                                                                 'addr_province' => $order['addr_province'],
                                                                 'addr_city' => $order['addr_city'],
                                                                );
        }
        
        foreach ( $params['order_sns'] as $key => $order_sn ) {
            if ( !$params['logistics'][$key] ) {
                die("订单{$order_sn}运输单没有，不能发货");
            }
            if ( !in_array($order_sn, $orderSNArray) ) {
                die("订单{$order_sn}找不到，不能发货");
            }
        }
        
        $shopDatas = $this -> _api -> get("shop_id = {$shopID}");
        if ( $shopDatas['total'] == 0 ) {
            die("找不到店铺");
        }
        $shop = $shopDatas['list'][0];
        $shopAPI = Custom_Model_Shop_Base::getInstance($shop['shop_type'], unserialize($shop['config']));
        
        foreach ( $params['order_sns'] as $key => $order_sn ) {
            $shopAPI -> _log = '';
            $result = $shopAPI -> syncShopLogistic($order_sn, array('logistic_code' => $orderLogistics[$order_sn]['logistic_code'], 'logistic_name' => $orderLogistics[$order_sn]['logistic_name'], 'logistic_no' => $params['logistics'][$key], 'logistic_time' => time()));
            if ($result) {
                $shopOrder['shop_id'] = $shopID;
                $shopOrder['external_order_sn'] = $order_sn;
                $shopOrder['logistic_code'] = $orderLogistics[$order_sn]['logistic_code'];
                $shopOrder['logistic_no'] = $params['logistics'][$key];
                $shopOrder['logistic_time'] = time();
                $shopOrder['status'] = 3;
                $shopOrder['shop_name'] = $shop['shop_name'];
                $shopOrder['logistic_name'] = $orderLogistics[$order_sn]['logistic_name'];
                $shopOrder['addr_consignee'] = $orderLogistics[$order_sn]['addr_consignee'];
                $shopOrder['addr_mobile'] = $orderLogistics[$order_sn]['addr_mobile'];
                $this -> _api -> updateOrderLogistic($shopOrder);
                
                $this -> _api -> sendMessage($shopOrder);
                
                $shopOrder['province'] = $orderLogistics[$order_sn]['addr_province'];
                $shopOrder['city'] = $orderLogistics[$order_sn]['addr_city'];
                $logisticAPI -> pushKuaidi100($shopOrder);
            }
            else {
                $shopOrder['shop_id'] = $shopID;
                $shopOrder['external_order_sn'] = $order_sn;
                $shopOrder['logistic_no'] = $params['logistics'][$key];
                $this -> _api -> updateOrderLogisticNo($shopOrder);
                $error .= $shopAPI -> _log;
            }
        }
        
        if ($error) die($error);
        
        if ($params['direct']) {
            $this->_redirect("/admin/shop/order-print-list/shop_id/{$shopID}");
        }
        else    $this->_redirect("/admin/shop/order-send-list/shop_id/{$shopID}");
    }
    
    /**
     * 订单详情
     *
     * @return   void
     */
	public function orderDetailAction()
	{
	    $id = $this -> _request -> getParam('id');
	    if ( !$id ) exit;
	    
	    $datas = $this -> _api -> getOrder(array('shop_order_id' => $id));
	    $this -> view -> order = $datas['list'][0];
	}
	
	/**
     * 商品销售列表
     *
     * @return   void
     */
	public function goodsSaleListAction()
	{
	    $params = $this -> _request -> getParams();
	    if ($params['dosearch']) {
	        $this -> view -> goodsList = $this -> _api -> listGoodsSale($params);
	        $this -> view -> param = $params;
	    }
        $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        if(in_array($auth['admin_id'],$this -> _allowDoList)){
           $this -> view -> viewcost = '1';
        }
	    $shopDatas = $this -> _api -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
	}
	
	/**
     * 导出商品销售统计
     *
     * @return   void
     */
	public function goodsSaleExportAction()
	{
	    $params = $this -> _request -> getParams();
	    $datas = $this -> _api -> listGoodsSale($params);
	    $excel = new Custom_Model_GenExcel();
        $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        if(in_array($auth['admin_id'],$this -> _allowDoList)){
            $title = array('店铺','商品编号','商品名称','商品单价','成本价','销售数量','平均售价','总金额');
        }else{
            $title = array('店铺','商品编号','商品名称','商品单价','销售数量','平均售价','总金额');
        }
        $lineArray[] = $title;
        if ( $datas ) {
            foreach ($datas as $data)
            {
                if(in_array($auth['admin_id'],$this -> _allowDoList)){
                    $row = array($data['shop_name'],$data['goods_sn'],$data['shop_goods_name'],$data['price'],$data['cost'],$data['number'],$data['avg_price'],$data['total_amount']);
                }else{
                     $row = array($data['shop_name'],$data['goods_sn'],$data['shop_goods_name'],$data['price'],$data['number'],$data['avg_price'],$data['total_amount']);
                }

                $lineArray[] = $row;
                unset($row);
            }
            unset($datas);
            $excel -> addArray ($lineArray);
            $excel -> generateXML ('external-goods-sale');
        }
        
        exit;
	}
	
	/**
     * 店铺授权(淘宝/京东)
     *
     * @return   void
     */
	public function oauthAction() 
	{
	    $shopID = $this -> _request -> getParam('shop_id');
	    $shop = $this -> _api -> get("shop_id = '{$shopID}'");
	    if ($shop['total'] < 0) {
	        exit;
	    }
	    
	    $shop = array_shift($shop['list']);
	    if ($shop['shop_type'] != 'jingdong' && $shop['shop_type'] != 'taobao' && $shop['shop_type'] != 'alibaba' && $shop['shop_type'] != 'dangdang') {
	        exit;
	    }
	    
	    $config = unserialize($shop['config']);
	    
	    if ($shop['shop_type'] == 'jingdong') {
	        if (!$config['key'] || !$config['secret']) {
    	        exit;
    	    }
	        header("location: http://auth.360buy.com/oauth/authorize?response_type=code&client_id={$config['key']}&redirect_uri=http://jkerp.1jiankang.com/external/jd&state={$shopID}&scope=read");
	    }
	    else if ($shop['shop_type'] == 'taobao') {
	        if (!$config['id'] || !$config['key']) {
    	        exit;
    	    }
	        header("location: https://oauth.taobao.com/authorize?client_id={$config['id']}&response_type=code&redirect_uri=http://jkerp.1jiankang.com/external/taobao&state={$shopID}");
	    }
	    else if ($shop['shop_type'] == 'alibaba') {
	        if (!$config['id'] || !$config['key']) {
    	        exit;
    	    }
    	    $sign = "client_id{$config['id']}redirect_urihttp://www.1jiankang.com/external/alibabasitechinastate{$shopID}";
    	    $sign = strtoupper(hash_hmac('sha1', $sign, $config['key']));
    	    header("location: http://gw.open.1688.com/auth/authorize.htm?client_id={$config['id']}&site=china&redirect_uri=http://www.1jiankang.com/external/alibaba&state={$shopID}&_aop_signature={$sign}");
	    }
	    else if ($shop['shop_type'] == 'dangdang') {
	        if (!$config['id'] || !$config['key']) {
    	        exit;
    	    }
    	    $url = "http://oauth.dangdang.com/authorize?appId={$config['id']}&responseType=code&redirectUrl=http://www.1jiankang.com/external/dangdang&state={$shopID}";
    	    header("location: {$url}");
	    }
	    
	    exit;
	}
	
	function matchOrderOutstockProductAction()
	{
	    $db = Zend_Registry::get('db');
	    
	    //$where = " and t1.external_order_sn = '223294389364497'";
	    
	    $sql = "select t3.order_sn,t1.external_order_sn,t2.goods_sn,t2.number from shop_order_shop as t1 
	            inner join shop_order_shop_goods as t2 on t1.shop_order_id = t2.shop_order_id 
	            inner join shop_order as t3 on t1.external_order_sn = t3.external_order_sn
	            where t1.sync = 1 {$where}";
	    $datas = $db -> fetchAll($sql);
	    if (!$datas)    return false;
	    foreach ($datas as $data) {
	        $orderInfo[$data['order_sn']][] = array('external_order_sn' => $data['external_order_sn'],
	                                                'product_sn' => $data['goods_sn'],
	                                                'number' => $data['number'],
	                                               );
	    }
	    
	    $sql = "select t4.order_sn,t3.product_id,t2.number from shop_order_shop as t1 
	            inner join shop_order_shop_goods as t2 on t1.shop_order_id = t2.shop_order_id 
	            inner join shop_product as t3 on t2.goods_sn = t3.product_sn
	            inner join shop_order as t4 on t1.external_order_sn = t4.external_order_sn
	            where t1.sync = 1 {$where}";
	    $datas = $db -> fetchAll($sql);
	    foreach ($datas as $data) {
	        $externalOrderGoods[$data['order_sn']][$data['product_id']] += $data['number'];
	    }
	    
	    $sql = "select t4.order_sn,t3.group_id,t2.number from shop_order_shop as t1 
	            inner join shop_order_shop_goods as t2 on t1.shop_order_id = t2.shop_order_id 
	            inner join shop_group_goods as t3 on t2.goods_sn = t3.group_sn
	            inner join shop_order as t4 on t1.external_order_sn = t4.external_order_sn
	            where t1.sync = 1 {$where}";
	    $datas = $db -> fetchAll($sql);
	    if ($datas) {
    	    foreach ($datas as $data) {
    	        $groupIDArray[] = $data['group_id'];
    	    }
    	    $groupGoodsData = $db -> fetchAll("select group_id,group_sn,group_goods_config from shop_group_goods where group_id in (".implode(',', array_unique($groupIDArray)).")");
    	    foreach ($groupGoodsData as $groupGoods) {
    	        $config = unserialize($groupGoods['group_goods_config']);
    	        if (!$config) {
    	            die($groupGoods['group_sn'].'没有子商品');
    	        }
    	        foreach ($config as $product) {
    	            $groupGoodsMap[$groupGoods['group_id']][] = array('product_id' => $product['product_id'],
    	                                                              'number' => $product['number'],
    	                                                             );
    	        }
    	    }
    	    foreach ($datas as $data) {
    	        $groupGoods = $groupGoodsMap[$data['group_id']];
    	        if (!$groupGoods) {
    	            die($data['order_sn'].'找不到组合商品');
    	        }
    	        foreach ($groupGoods as $product) {
    	            $externalOrderGoods[$data['order_sn']][$product['product_id']] += $product['number'] * $data['number'];
    	        }
    	    }
	    }

	    $sql = "select t2.order_sn,t4.product_id,t4.number from shop_order_shop as t1 
	            inner join shop_order as t2 on t1.external_order_sn = t2.external_order_sn
	            inner join shop_outstock as t3 on t2.order_sn = t3.bill_no
	            inner join shop_outstock_detail as t4 on t3.outstock_id = t4.outstock_id
	            where t1.sync = 1 and t3.bill_type = 1 {$where}";
	    $datas = $db -> fetchAll($sql);
	    foreach ($datas as $data) {
	        $outstockProduct[$data['order_sn']][$data['product_id']] += $data['number'];
	    }
	    
	    foreach ($outstockProduct as $orderSN => $detail) {
	        foreach ($detail as $productID => $number) {
	            if ($externalOrderGoods[$orderSN][$productID] != $number) {
	                echo $orderSN.'&nbsp;'.$orderInfo[$orderSN][0]['external_order_sn'].'&nbsp;';
	                foreach ($orderInfo[$orderSN] as $order) {
	                    echo $order['product_sn'].'*'.$order['number'].'&nbsp;';
	                }
	                
	                echo '<br>';
	                
	                break;
	            }
	        }
	    }
	    
	    exit;
	}
}

function compareProductLocalSN($a, $b)
{
    $a = array_shift($a);
    $b = array_shift($b);
    return $a['localSN'] > $b['localSN'];
}