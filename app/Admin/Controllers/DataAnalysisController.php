<?php

class Admin_DataAnalysisController extends Zend_Controller_Action
{
	/**
     * api对象
     */
    private $_api = null;
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init()
	{
		$this -> _api = new Admin_Models_API_DataAnalysis();
		$this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
	}
	/**
     * 默认动作
     *
     * @return   void
     */
    public function indexAction()
    {

    }


	/**
     * 导出订单商品信息数据
     *
     * @return void
     */
    public function exportGoodsAction()
    {
    	$opt_api = new Admin_Models_API_OpLog();
    	$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-exportGoods");
    	Zend_Controller_Front::getInstance() -> unRegisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$data = $this-> _api -> getExportGoods($this -> _request -> getParams());
    	exit;
    }


    /**
     * 生成 xls
     */
    public function exportOrderAction() {
    	$opt_api = new Admin_Models_API_OpLog();
    	$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-exportOrder");
    	if ($this -> _request -> isPost()) {
				$fromdate = $this -> _request -> getParam('fromdate', '');if($fromdate==''){$fromdate=null;}
				$todate = $this -> _request -> getParam('todate', '');if($todate==''){$todate=null;}
				$pay_name = $this -> _request -> getParam('pay_name', '');if($pay_name==''){$pay_name=null;}
				$logistic_name = $this -> _request -> getParam('logistic_name', '');if($logistic_name==''){$logistic_name=null;}
				$order_sn = $this -> _request -> getParam('order_sn', '');if($order_sn==''){$order_sn=null;}
				$status = $this -> _request -> getParam('status', '');if($status==''){$status=null;}
				$statusLogistic = $this -> _request -> getParam('status_logistic', '');if($statusLogistic==''){$statusLogistic=null;}
				$addr_province = $this -> _request -> getParam('addr_province', '');if($addr_province==''){$addr_province=null;}
				$addr_city = $this -> _request -> getParam('addr_city', '');if($addr_city==''){$addr_city=null;}
				$type = $this -> _request -> getParam('type', '');if($type==''){$type=null;}
				if ($fromdate && $todate) {
					$this  ->  _api  ->  exportOrder(array('fromdate' => $fromdate,
													   'todate' => $todate,
													   'pay_name' => $pay_name,
													   'logistic_name' => $logistic_name,
													   'order_sn' => $order_sn,
													   'status' => $status,
													   'status_logistic' => $statusLogistic,
													   'addr_province' => $addr_province,
													   'type' => $type,
													   'addr_city' => $addr_city));
					exit;
			}
		}
    }

    /**
     * 每日会员统计
     *
     * @return void
     */
    public function memberDailyAction()
    {
        $param = $this -> _request -> getParams();
        $param['dateFormat'] = $param['dateFormat'] ? $param['dateFormat'] : 'Y-m-d';
        if ( $param['todo'] == 'search' ) {
            $newUserData = $this -> _api -> getNewUserByDay($param);
            $orderUserData = $this -> _api -> getUserOrderCountByDay($param);

            $datas = array_merge_recursive($newUserData, $orderUserData);

            if ( $datas ) {
                foreach ( $datas as $index => $data ) {
                    $totalData['RegUserCount'] += $data['RegUserCount'];
                    $totalData['UserOrderCount'] += $data['UserOrderCount'];
                    $totalData['UserMoreOrderCount'] += $data['UserMoreOrderCount'];
                    $totalData['FrontRegUserCount'] += $data['FrontRegUserCount'];
                    $totalData['CPSRegUserCount'] += $data['CPSRegUserCount'];
                    if ($data['OrderCount']) {
                        $datas[$index]['MoreUserOrderRate'] = round($data['MoreOrderCount'] / $data['OrderCount'] * 100, 2);
                    }
                }

                foreach ( $datas as $date => $data ) {
                    $data['date'] = $date;
                    $result[] = $data;
                }
                global $fieldName, $sortType;
                $fieldName = $param['sortField'];
                $sortType = $param['sortType'];
                if ( $fieldName && $sortType ) {
                    uasort($result, 'compareRecord');
                }
            }

            $this -> view -> datas = $result;
            $this -> view -> totalData = $totalData;
            if ( $sortType == 2 )   $this -> view -> sortType = 1;
            else    $this -> view -> sortType = 2;
        }
        $this -> view -> param = $param;
    }

    /**
     * 会员购买商品统计
     *
     * @return void
     */
    public function memberGoodsAction()
    {
        $param = $this -> _request -> getParams();
        if ( $param['todo'] == 'search' ) {
            $datas = $this -> _api -> getUserOrder($param);

            $tempDatas = $this -> _api -> getUserOrderGoods($param);
            if ($tempDatas) {
                foreach ( $datas as $user_id => $data ) {
                    if ( !$tempDatas[$user_id] ) {
                        $datas[$user_id]['GoodsCount'] = 0;
                    }
                    else {
                        foreach ( $tempDatas[$user_id] as $goods ) {
                            $datas[$user_id]['GoodsCount'] += $goods['number'];
                        }
                        $datas[$user_id]['Goods'] = $tempDatas[$user_id];
                    }
                }
            }

            if ( $datas ) {
                foreach ( $datas as $data ) {
                    $totalData['GoodsCount'] += $data['GoodsCount'];
                    $totalData['TotalOrderCount'] += $data['TotalOrderCount'];
                    $totalData['OrderCount'] += $data['OrderCount'];
                    $totalData['TotalAmount'] += $data['TotalAmount'];
                    $totalData['RealAmount'] += $data['RealAmount'];
                }

                foreach ( $datas as $user_id => $data ) {
                    $data['user_id'] = $user_id;
                    $result[] = $data;
                }
                global $fieldName, $sortType;
                $fieldName = $param['sortField'];
                $sortType = $param['sortType'];
                if ( $fieldName && $sortType ) {
                    uasort($result, 'compareRecord');
                }
            }

            $this -> view -> datas = $result;
            $this -> view -> totalData = $totalData;
            $this -> view -> param = $param;
            if ( $sortType == 2 )   $this -> view -> sortType = 1;
            else    $this -> view -> sortType = 2;
        }
    }

    /**
     * 每日订单统计
     *
     * @return void
     */
    public function orderDailyAction()
    {

        $param = $this -> _request -> getParams();
        $param['dateFormat'] = $param['dateFormat'] ? $param['dateFormat'] : 'Y-m-d';
        if ( $param['todo'] == 'search' || $param['todo'] == 'export') {
            $datas = $this -> _api -> getOrderInfoByDay($param);

            if ( $datas ) {
                foreach ( $datas as $date => $data1 ) {
                    foreach ( $data1 as $shopID => $data ) {
                        $totalData['TotalCount'] += $data['TotalCount'];
                        $totalData['TotalAmount'] += $data['TotalAmount'];
                        $totalData['ValidCount'] += $data['ValidCount'];
                        $totalData['Amount'] += $data['Amount'];
                        $totalData['PaidAmount'] += $data['PaidAmount'];
                        $totalData['SentCount'] += $data['SentCount'];
                        $totalData['ReturnCount'] += $data['ReturnCount'];
                        $totalData['ReturnAmount'] += $data['ReturnAmount'];
                        $totalData['LogisticAmount'] += $data['LogisticAmount'];
                        if ($data['ValidCount']) {
                            $datas[$date][$shopID]['AvgAmount'] = round($data['Amount'] / $data['ValidCount'], 2);
                        }
                    }
                }

                if ($totalData['ValidCount']) {
                    $totalData['AvgAmount'] = round($totalData['Amount'] / $totalData['ValidCount'], 2);
                }

                foreach ( $datas as $date => $data1 ) {
                    foreach ( $data1 as $shopID => $data ) {
                        $data['date'] = $date;
                        $data['shop_id'] = $shopID;
                        $result[] = $data;
                    }
                }

                global $fieldName, $sortType;
                $fieldName = $param['sortField'];
                $sortType = $param['sortType'];
                if (!$fieldName) {
                    $fieldName = 'shop_id';
                    $sortType = 1;
                }
                if ( $fieldName && $sortType ) {
                    uasort($result, 'compareRecord');
                }
            }

            if ($param['todo'] == 'export') {
            	$opt_api = new Admin_Models_API_OpLog();
				$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-orderDaily");
                $content[] = array('日期', '店铺', '总单数','订单总金额', '有效单数', '有效订单总金额', '运费金额', '每单平均金额', '发货单数', '退货单数', '退款金额');
                if ($result) {
                    foreach ($result as $data) {
                        $content[] = array($data['date'], $data['shop_name'], $data['TotalCount'], $data['TotalAmount'], $data['ValidCount'], $data['Amount'], $data['LogisticAmount'], $data['AvgAmount'], $data['SentCount'], $data['ReturnCount'], $data['ReturnAmount']);
                    }
                }
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('order-daily');

            	exit();
            }

            $this -> view -> datas = $result;
            $this -> view -> totalData = $totalData;
            if ( $sortType == 2 )   $this -> view -> sortType = 1;
            else    $this -> view -> sortType = 2;
        }

        $shopAPI = new Admin_Models_API_Shop();
        $this -> view -> provinceData = array_shift($shopAPI ->  getAllArea(1));

        if ( $param['province'] ) {
    	    if (is_array($param['province'])) {
        	    for ( $i = 0; $i < count($param['province']); $i++ ) {
                    $province[$param['province'][$i]] = 1;
                }
            }
            else    $province[$param['province']] = 1;
        }
        else {
            foreach ($this -> view -> provinceData as $province_name => $province_id) {
                $province[$province_id] = 1;
            }
        }
        $param['province']  = $province;

        $this -> view -> param = $param;

        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
        
        $stockConfig = Custom_Model_Stock_Base::getInstance();
        $this -> view -> areas = $stockConfig -> getConfigLogicArea();
        $this -> view -> distributionArea = array_flip($stockConfig -> getDistributionArea());
    }

    /**
     * 每日产品销售统计
     *
     * @return void
     */
    public function goodsDailyAction()
    {
        $param = $this -> _request -> getParams();
        if (!$param['sortField']) {
            $param['sortField'] = 'date';
            $param['sortType'] = '1';
        }
        if ( $param['todo'] == 'search' ) {
            $datas = $this -> _api -> getGoodsDaily($param);
            if ($datas ) {
                foreach ( $datas as $data ) {
                    $totalData['GoodsCount'] += $data['GoodsCount'];
                    $totalData['OutStockCount'] += $data['OutStockCount'];
                    $totalData['ReturnCount'] += $data['ReturnCount'];
                    $totalData['RealOutStockCount'] += $data['RealOutStockCount'];
                    $totalData['TotalAmount'] += $data['TotalAmount'];
                    $totalData['TotalCost'] += $data['TotalCost'];
                    $totalData['TotalNoTaxCost'] += $data['TotalNoTaxCost'];
                    $totalData['BenefitAmount'] += $data['BenefitAmount'];
                }
                if ($totalData['TotalAmount']) {
                    $totalData['BenefitRate'] = round(($totalData['TotalAmount'] - $totalData['TotalCost']) / $totalData['TotalAmount'] * 100, 2);
                }
                
                global $fieldName, $sortType;
                $fieldName = $param['sortField'];
                $sortType = $param['sortType'];
                if ( $fieldName && $sortType ) {
                    uasort($datas, 'compareRecord');
                }
            }
            
            $this -> view -> datas = $datas;
            $this -> view -> totalData = $totalData;
            if ( $sortType == 2 )   $this -> view -> sortType = 1;
            else    $this -> view -> sortType = 2;
        }
        $this -> view -> param = $param;

        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
        
        $supplierAPI = new Admin_Models_API_Supplier();
        $this -> view -> supplierData = $supplierAPI -> getSupplier('status = 0');
        
        $stockConfig = Custom_Model_Stock_Base::getInstance();
        $this -> view -> areas = $stockConfig -> getConfigLogicArea();
        $this -> view -> distributionArea = array_flip($stockConfig -> getDistributionArea());
    }

    /**
     * 每日产品销售统计(导出)
     *
     * @return void
     */
    public function goodsDailyExportAction()
    {
        $param = $this -> _request -> getParams();
        if (!$param['sortField']) {
            $param['sortField'] = 'GoodsCount';
            $param['sortType'] = '2';
        }
        
        $datas = $this -> _api -> getGoodsDaily($param);

        if ($datas ) {
            global $fieldName, $sortType;
            $fieldName = $param['sortField'];
            $sortType = $param['sortType'];
            if ( $fieldName && $sortType ) {
                uasort($datas, 'compareRecord');
            }

            $excel = new Custom_Model_GenExcel();
            $lineArray[] = array('产品编号','产品名称','平均售价','出库数量','退货数量','实际出库数量','毛利','毛利率','销售总金额');
            foreach ($datas as $date => $data) {
                $lineArray[] = array($data['product_sn'],$data['product_name'],$data['AveragePrice'],$data['OutStockCount'],$data['ReturnCount'],$data['RealOutStockCount'],$data['BenefitAmount'],$data['BenefitRate'].'%',$data['TotalAmount']);
             }
            $excel -> addArray ($lineArray);
            $excel -> generateXML ('goods-sale');
            exit;
        }
        else    die('无数据');
    }

    /**
     * 仓库发货统计
     *
     * @return void
     */
    public function logisticDeliveryAction()
    {
        $param = $this -> _request -> getParams();

        $shopAPI = new Admin_Models_API_Shop();

        if ( $param['todo'] == 'search' ) {
            $datas = $this -> _api -> getLogisticTransport($param);

            if ($datas ) {
                foreach ( $datas as $data ) {
                    $totalData['TotalCount'] += $data['TotalCount'];
                    $totalData['SignCount'] += $data['SignCount'];
                    $totalData['NotSignCount'] += $data['NotSignCount'];
                    $totalData['RefuseCount'] += $data['RefuseCount'];
                    $totalData['MatchCount'] += $data['MatchCount'];
                    $totalData['TotalPrice'] += $data['TotalPrice'];
                }

                foreach ( $datas as $data ) {
                    $result[] = $data;
                }

                global $fieldName, $sortType;
                $fieldName = $param['sortField'];
                $sortType = $param['sortType'];
                if ( $fieldName && $sortType ) {
                    uasort($result, 'compareRecord');
                }
            }

            $this -> view -> datas = $result;
            $this -> view -> totalData = $totalData;
        }

        $this -> view -> provinceData = array_shift($shopAPI ->  getAllArea(1));

        if ( $param['province'] ) {
    	    if (is_array($param['province'])) {
        	    for ( $i = 0; $i < count($param['province']); $i++ ) {
                    $province[$param['province'][$i]] = 1;
                }
            }
            else    $province[$param['province']] = 1;
        }
        else {
            foreach ($this -> view -> provinceData as $province_name => $province_id) {
                $province[$province_id] = 1;
            }
        }
        $param['province']  = $province;

        $this -> view -> param = $param;
        if ( $sortType == 2 )   $this -> view -> sortType = 1;
        else    $this -> view -> sortType = 2;

        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }

    /**
     * 订单类型分布统计
     *
     * @return void
     */
    public function orderDistributionAction()
    {
        $param = $this -> _request -> getParams();
        if ( $param['todo'] == 'search' ) {
            $datas = $this -> _api -> getOrderByType($param);

            if ($datas ) {
                foreach ( $datas as $data ) {
                    $totalData['TotalCount'] += $data['TotalCount'];
                    $totalData['ValidCount'] += $data['ValidCount'];
                    $totalData['TotalAmount'] += $data['TotalAmount'];
                    $totalData['PaidAmount'] += $data['PaidAmount'];
                    $totalData['ReturnCount'] += $data['ReturnCount'];
                    $totalData['ReturnAmout'] += $data['ReturnAmout'];
                }

                foreach ( $datas as $data ) {
                    $result[] = $data;
                }

                global $fieldName, $sortType;
                $fieldName = $param['sortField'];
                $sortType = $param['sortType'];
                if ( $fieldName && $sortType ) {
                    uasort($result, 'compareRecord');
                }
            }

            $this -> view -> datas = $result;
            $this -> view -> totalData = $totalData;
            $this -> view -> param = $param;
            if ( $sortType == 2 )   $this -> view -> sortType = 1;
            else    $this -> view -> sortType = 2;
        }
    }

    public function userGoodsAction()
    {

        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];

        $param = $this -> _request -> getParams();
        if (!$param['status'])  $param['status'] = 1;
        if ( $param['todo'] == 'search' || $param['todo'] == 'export' ) {
            $datas = $this -> _api -> getUserGoods($param);
            if ($datas) {
                foreach ($shopDatas['list'] as $shop) {
                    $shopInfo[$shop['shop_id']] = $shop['shop_name'];
                }
                $count = count($datas);
                if ($param['todo'] == 'export') {
                    $content[] = array('姓名',
						//'手机','固话',
						'渠道站点来源','联盟ID','购买产品','购买次数','购买数量','购买总金额','首次购买时间','最近购买时间','省','市','区县','地址','省ID','市ID','区县ID');

                    $unionInfo = $this -> _api -> getUnionInfo();

                }

                for ($i = 0; $i < $count; $i++) {
                    $datas[$i]['shop_name'] = $shopInfo[$datas[$i]['shop_id']];
                    $tempArray = explode('||', $datas[$i]['goods_name']);
                    if ($param['todo'] == 'export') {
                        $datas[$i]['goods_name'] = implode(', ', array_unique($tempArray));
                        $content[] = array($datas[$i]['addr_consignee'],
							//$datas[$i]['addr_mobile'],$datas[$i]['addr_tel'],
							$datas[$i]['shop_name'],$unionInfo[$datas[$i]['parent_id']],$datas[$i]['goods_name'],$datas[$i]['order_count'],$datas[$i]['goods_number'],$datas[$i]['amount'],date('Y-m-d H:i:s', $datas[$i]['add_time']),date('Y-m-d H:i:s', $datas[$i]['last_time']),$datas[$i]['addr_province'],$datas[$i]['addr_city'],$datas[$i]['addr_area'],str_replace(array('<','>'),array('',''),$datas[$i]['addr_address']),$datas[$i]['addr_province_id'],$datas[$i]['addr_city_id'],$datas[$i]['addr_area_id']);
                    }
                    else {
                        $datas[$i]['goods_name'] = implode('<br>', array_unique($tempArray));
                    }

                }

                if ($param['todo'] == 'export') {
                $opt_api = new Admin_Models_API_OpLog();
				$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-userGoods");
                    $xls = new Custom_Model_GenExcel();
            		$xls -> addArray($content);
            	    $xls -> generateXML('user_goods');

            		exit();
                }
            }

            $this -> view -> userGoodslist = $datas;
            $this -> view -> param = $param;
        }
    }
    
    public function financeSalesAction()
    {
        $param = $this -> _request -> getParams();
        
        if ($param['todo'] == 'search' || $param['todo'] == 'export') {
            $datas = $this -> _api -> getFinanceSales($param);
            if ($param['todo'] == 'export') {
                $content[] = array('类型', '总订单数', '总销售额', '刷单数', '刷单金额', '实际销售单数', '实际销售额', '销售成本', '销售成本(未税)', '毛利率');
            }
            if ($datas) {
                foreach ($datas as $key => $data) {
                    if ($param['todo'] == 'export') {
                        $content[] = array($key, $data['order_count'], $data['amount'], $data['order_count_fake'], $data['amount_fake'], $data['order_count_real'], $data['amount_real'], $data['cost_amount'], $data['no_tax_cost_amount'], $data['benefit_rate']);
                    }
                    else {
                        $total['order_count'] += $data['order_count'];
                        $total['amount'] += $data['amount'];
                        $total['order_count_fake'] += $data['order_count_fake'];
                        $total['amount_fake'] += $data['amount_fake'];
                        $total['order_count_real'] += $data['order_count_real'];
                        $total['amount_real'] += $data['amount_real'];
                        $total['cost_amount'] += $data['cost_amount'];
                        $total['no_tax_cost_amount'] += $data['no_tax_cost_amount'];
                    }
                }
                $total['amount_real'] && $total['benefit_rate'] = round(($total['amount_real'] - $total['cost_amount']) / $total['amount_real'] * 100, 2);
            }
            
            if ($param['todo'] == 'export') {
                $opt_api = new Admin_Models_API_OpLog();
				$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-financeSales");
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('finance-sales');

            	exit();
            }
        }
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
        $this -> view -> datas = $datas;
        $this -> view -> total = $total;
        $this -> view -> param = $param;
        
        $stockConfig = Custom_Model_Stock_Base::getInstance();
        $this -> view -> areas = $stockConfig -> getConfigLogicArea();
        $this -> view -> distributionArea = array_flip($stockConfig -> getDistributionArea());
        $this -> view -> distributionUsername = $stockConfig -> getDistributionArea();
    }

    public function accountReceivableAction()
    {
        $param = $this -> _request -> getParams();

        if ($param['todo'] == 'search' || $param['todo'] == 'export') {
            $financeAPI = new Admin_Models_API_Finance();
            $payTypeList = $financeAPI -> getAccountReceivablePayType();
            $datas = $this -> _api -> getAccountReceivable($param);
            if ($param['todo'] == 'export') {
                $content[] = array('支付方式', '应收金额', '结算金额', '佣金');
            }
            if ($datas) {
                foreach ($datas as $data) {
                    $total['amount'] += $data['amount'];
                    $total['settle_amount'] += $data['settle_amount'];
                    $total['commission'] += $data['commission'];
                    if ($param['todo'] == 'export') {
                        $content[] = array($payTypeList[$data['pay_type']], $data['amount'], $data['settle_amount'], $data['commission']);
                    }
                }
            }
            
            $returnDatas = $this -> _api -> getAccountReceivableReturn($param);
            if ($returnDatas) {
                foreach ($returnDatas as $data) {
                    $returnTotal += $data;
                }
            }
            if ($param['todo'] == 'export') {
                $opt_api = new Admin_Models_API_OpLog();
				$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-accountReceivable");
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('account-receivable');
                
            	exit();
            }
        }
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
        $this -> view -> datas = $datas;
        $this -> view -> total = $total;
        $this -> view -> param = $param;
        $this -> view -> payTypeList = $payTypeList;
        $this -> view -> returnDatas = $returnDatas;
        $this -> view -> returnTotal = $returnTotal;
        
        $stockConfig = Custom_Model_Stock_Base::getInstance();
        $this -> view -> areas = $stockConfig -> getConfigLogicArea();
        $this -> view -> distributionArea = array_flip($stockConfig -> getDistributionArea());
        $this -> view -> distributionUsername = $stockConfig -> getDistributionArea();
    }
    
    public function financeReturnAction()
    {
        $param = $this -> _request -> getParams();

        if ( $param['todo'] == 'search' || $param['todo'] == 'export') {
            $datas = $this -> _api -> getFinanceReturn($param);
            $cols = $datas[1];
            $datas = $datas[0];
            if ($param['todo'] == 'export') {
                $content[] = array('渠道', '退货单数', '总退款金额', '换货系统退款', '优惠补偿退款', '退货退款', '退货成本', '退货成本(未税)');
            }
            if ($datas) {
                foreach ($datas as $key => $data) {
                    $total['count'] += $data['count'];
                    $total['amount'] += $data['amount'];
                    $total['cost_amount'] += $data['cost_amount'];
                    $total['no_tax_cost_amount'] += $data['no_tax_cost_amount'];
                    $total['external_amount'] += $data['external_amount'];
                    $total['auto_amount'] += $data['auto_amount'];
                    $total['return_amount'] += $data['return_amount'];
                    
                    if ($cols) {
                        foreach ($cols as $col) {
                            $total[$col] += $data[$col];
                        }
                    }
                    
                    if ($param['todo'] == 'export') {
                        $content[] = array($data['shop_name'] ? $data['shop_name'] : '呼叫中心', $data['count'], $data['amount'], $data['auto_amount'], $data['external_amount'], $data['return_amount'], $data['cost_amount'], $data['no_tax_cost_amount']);
                    }
                }
            }

            if ($param['todo'] == 'export') {
                $opt_api = new Admin_Models_API_OpLog();
				$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-financeReturn");
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('finance-return');
                
            	exit();
            }
        }
        
        $this -> view -> datas = $datas;
        $this -> view -> total = $total;
        $this -> view -> cols = $cols;
        $this -> view -> param = $param;
        
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
        
        $stockConfig = Custom_Model_Stock_Base::getInstance();
        $this -> view -> areas = $stockConfig -> getConfigLogicArea();
        $this -> view -> distributionArea = array_flip($stockConfig -> getDistributionArea());
    }

    public function inOutStockAction()
    {
        $stockConfig = new Custom_Model_Stock_Config();

        $param = $this -> _request -> getParams();
        if (!$param['stock_type']) {
            $param['stock_type'] = 'instock';
        }

        if ( $param['todo'] == 'search' || $param['todo'] == 'export') {
            $datas = $this -> _api -> getProductInOutStock($param);

            if ($datas) {
                foreach ($datas as $data) {
                    if (!$data['product_name']) continue;

                    if ($param['stock_type'] == 'instock') {
                        $data['bill_type'] = $stockConfig -> _inTypes[$data['bill_type']];
                    }
                    else if ($param['stock_type'] == 'outstock') {
                        $data['bill_type'] = $stockConfig -> _outTypes[$data['bill_type']];
                    }

                    $result[] = $data;
                    $total['Number'] += $data['number'];
                }
            }

            if ($param['todo'] == 'export') {
                $opt_api = new Admin_Models_API_OpLog();
                $opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-inOutStock");
                $content[] = array('入库单据类型', '产品名称', '产品规格','产品编码', '入库数量');
                if ($result) {
                    foreach ($result as $data) {
                        $content[] = array($data['bill_type'], $data['product_name'], $data['goods_style'], $data['product_sn'], $data['number']);
                    }
                }
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('in-out-stock');

            	exit();
            }
        }

        $this -> view -> in_stock_type = $stockConfig -> _inTypes;
        $this -> view -> out_stock_type = $stockConfig -> _outTypes;

        $this -> view -> in_stock_status = array('0' => '未审核',
                                                 '1' => '已审核',
                                                 '2' => '已拒绝',
                                                 '3' => '未确认',
                                                 '6' => '待收货',
                                                 '7' => '已收货',
                                                );
        $this -> view -> out_stock_status = array('0' => '未审核',
                                                  '1' => '已审核',
                                                  '2' => '已拒绝',
                                                  '3' => '未确认',
                                                  '4' => '待发货',
                                                  '5' => '已发货',
                                                 );

        $config = new Custom_Model_Stock_Config();
        $this -> view -> areas = $config -> _logicArea;
        $this -> view -> datas = $result;
        $this -> view -> total = $total;
        $this -> view -> param = $param;
    }

    public function goodsUnsalableAction()
    {

        $param = $this -> _request -> getParams();
        if (!$param['days'])    $param['days'] = 30;

        if ( $param['todo'] == 'search' || $param['todo'] == 'export') {
            $datas = $this -> _api -> getGoodsUnsalable($param);

            if ($param['todo'] == 'export') {
            $opt_api = new Admin_Models_API_OpLog();
			$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-goodsUnsalable");
                $content[] = array('产品编码', '产品名称', '规格', '滞销天数', '当前库存');
                foreach ($datas as $data) {
                    $content[] = array($data['product_sn'], $data['product_name'], $data['goods_style'], $data['days'], $data['stock_number']);
                }
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('goods-unsalable');

            	exit();
            }
        }

        $this -> view -> datas = $datas;
        $this -> view -> param = $param;
    }

    public function stockTurnoverAction()
    {

        $param = $this -> _request -> getParams();
        if (!$param['days'])    $param['days'] = 30;

        if ( $param['todo'] == 'search' || $param['todo'] == 'export') {
            $datas = $this -> _api -> getStockTurnover($param);
            if ($datas) {
                $total['Number'] = 0;
                $total['StockNumber'] = 0;
                foreach ($datas as $index => $data) {
                    $total['Number'] += $data['number'];
                    $total['StockNumber'] += $data['stock_number'];
                }
                if ($total['Number'] && $total['StockNumber']) {
                    $total['Rate'] = round($total['Number'] / $total['StockNumber'], 2);
                }
                else {
                    $total['Rate'] = '*';
                }
            }

            if ($param['todo'] == 'export') {
				$opt_api = new Admin_Models_API_OpLog();
				$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-stockTurnover");
                $content[] = array('产品编码', '产品名称', '规格', '当前库存', '出库数量', '周转率');
                foreach ($datas as $data) {
                    $content[] = array($data['product_sn'], $data['product_name'], $data['goods_style'], $data['number'], $data['stock_number'], $data['rate']);
                }
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('stock-turnover');

            	exit();
            }
        }

        $this -> view -> datas = $datas;
        $this -> view -> total = $total;
        $this -> view -> param = $param;
    }

    public function productSalesAction()
    {
        $param = $this -> _request -> getParams();
        if ( $param['todo'] == 'search' || $param['todo'] == 'export') {
            if (!$param['todate']) {
                $param['todate'] = date('Y-m-d');
            }
            $datas = $this -> _api -> getProductSales($param);

            if ($param['todo'] == 'export') {
            $opt_api = new Admin_Models_API_OpLog();
				$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-productSales");
                $content[] = array('产品编号', '产品名称', '订单总金额', '产品销售总金额', '产品总单数', '单一购买数', '连带购买总单数', '复购单数', '流失会员数');
                foreach ($datas as $data) {
                    $content[] = array($data['product_sn'], $data['product_name'], $data['Amount'], $data['ProductAmount'], $data['OrderCount1'], $data['OrderCount2'], $data['OrderCount3'], $data['OrderCount4'], $data['UserCount']);
                }
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('product-sales');

            	exit();
            }

            $this -> view -> datas = $datas;
        }
        $this -> view -> param = $param;

        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }

    public function productLogisticAreaAction()
    {
        $param = $this -> _request -> getParams();

        if ( $param['todo'] == 'search' ) {
            $datas = $this -> _api -> getProductLogisticArea($param);

            if ($datas ) {
                foreach ( $datas as $data ) {
                    $totalData['count'] += $data['count'];
                }
            }

            $this -> view -> datas = $datas;
            $this -> view -> totalData = $totalData;
        }

        $shopAPI = new Admin_Models_API_Shop();

        $this -> view -> param = $param;
        $this -> view -> provinceData = array_shift($shopAPI ->  getAllArea(1));

        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
    }
    
    public function supplierPaymentAction()
    {
        $param = $this -> _request -> getParams();
        
        if ($param['todo'] == 'search') {
            $datas = $this -> _api -> getSupplierPayment($param);
            if ($datas ) {
                foreach ( $datas as $data ) {
                    $totalData['amount1'] += $data['amount1'];
                    $totalData['amount2'] += $data['amount2'];
                }
            }
        }
        
        $stockAPI = new Admin_Models_API_InStock();
        $supplierData = $stockAPI -> getSupplier('status = 0');
        $this -> view -> supplier = $supplierData;
        $this -> view -> param = $param;
        $this -> view -> datas = $datas;
        $this -> view -> totalData = $totalData;
    }
    
    public function orderMarginAction()
    {
        $param = $this -> _request -> getParams();
        
        if ($param['todo'] == 'search') {
            $datas = $this -> _api -> getOrderMargin($param);
            if ($datas ) {
                foreach ( $datas as $index => $data ) {
                    $datas[$index]['benefit_rate'] = round($data['benefit_rate'], 2);
                }
            }
        }
        
        $supplierAPI = new Admin_Models_API_Supplier();
        $this -> view -> supplierData = $supplierAPI -> getSupplier('status = 0');
        $shopAPI = new Admin_Models_API_Shop();
        $shopDatas = $shopAPI -> get();
        $this -> view -> shopDatas = $shopDatas['list'];
        $this -> view -> param = $param;
        $this -> view -> datas = $datas;
    }
    
    public function financeProductAction()
    {
        $param = $this -> _request -> getParams();
        
        $stockConfig = Custom_Model_Stock_Base::getInstance(0);
        $instockType = $stockConfig -> getConfigInType();
        $outstockType = $stockConfig -> getConfigOutType();
        
        if ($param['todo'] == 'search' || $param['todo'] == 'export') {
            $datas = $this -> _api -> getFinanceProduct($param);
            if ($param['todo'] == 'export') {
                $content[] = array('产品编号', '产品名称', '成本', '数量', '总金额', '总金额(未税)', '入库方式', '成本', '数量', '总金额', '总金额(未税)', '出库方式', '成本', '数量', '总金额', '总金额(未税)', '成本', '数量', '总金额', '总金额(未税)');
            }
            if ($datas) {
                foreach ($datas as $productID => $data) {
                    $total['from_number'] += $data['from_number'];
                    $total['from_amount'] += $data['from_cost'] * $data['from_number'];
                    $total['to_number'] += $data['to_number'];
                    $total['to_amount'] += $data['to_cost'] * $data['to_number'];
                    if ($data['instock']) {
                        foreach ($data['instock'] as $instock) {
                            $total['instock_number'] += $instock['number'];
                            $total['instock_amount'] += $instock['amount'];
                        }
                    }
                    if ($data['outstock']) {
                        foreach ($data['outstock'] as $outstock) {
                            $total['outstock_number'] += $outstock['number'];
                            $total['outstock_amount'] += $outstock['amount'];
                        }
                    }
                    if ($param['todo'] == 'export') {
                        if ($data['instock']) {
                            foreach ($data['instock'] as $index => $instock) {
                                if ($data['outstock'][$index]) {
                                    $outstock = $data['outstock'][$index];
                                }
                                else {
                                    $outstock = array();
                                }
                                if ($index == 0) {
                                    $array1 = array($data['product_sn'], $data['product_name'], $data['from_cost'], $data['from_number'], $data['from_cost'] * $data['from_number'], round($data['from_cost'] * $data['from_number'] / (1 + $data['invoice_tax_rate'] / 100)));
                                    $array3 = array($data['to_cost'], $data['to_number'], $data['to_cost'] * $data['to_number'], round($data['to_cost'] * $data['to_number'] / (1 + $data['invoice_tax_rate'] / 100)));
                                }
                                else {
                                    $array1 = array('', '', '', '', '', '');
                                    $array3 = array('', '', '', '');
                                }
                                $array2 = array($instockType[$instock['bill_type']], $instock['cost'], $instock['number'], $instock['amount'], round($instock['amount'] / (1 + $data['invoice_tax_rate'] / 100)),
                                                $outstockType[$outstock['bill_type']], $outstock['cost'], $outstock['number'], $outstock['amount'], round($outstock['amount'] / (1 + $data['invoice_tax_rate'] / 100)));
                                $content[] = array_merge($array1, $array2, $array3);
                            }
                        }
                        else {
                            $content[] = array($data['product_sn'], $data['product_name'], $data['from_cost'], $data['from_number'], $data['from_cost'] * $data['from_number'],
                                               '', '', '', '', '', '', '', '',
                                               $data['to_cost'], $data['to_number'], $data['to_cost'] * $data['to_number']);
                        }
                    }
                }
                
                $total['from_number'] && $total['from_cost'] = round($total['from_amount'] / $total['from_number'], 3);
                $total['to_number'] && $total['to_cost'] = round($total['to_amount'] / $total['to_number'], 3);
                $total['instock_number'] && $total['instock_cost'] = round($total['instock_amount'] / $total['instock_number'], 3);
                $total['outstock_number'] && $total['outstock_cost'] = round($total['outstock_amount'] / $total['outstock_number'], 3);
            }
            
            if ($param['todo'] == 'export') {
                $opt_api = new Admin_Models_API_OpLog();
				$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-financeProduct");
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('finance-product');
                
            	exit();
            }
        }
        
        $this -> view -> instockType = $instockType;
        $this -> view -> outstockType = $outstockType;
        $this -> view -> datas = $datas;
        $this -> view -> total = $total;
        $this -> view -> diff = bcsub(bcsub(bcadd($total['from_amount'],$total['instock_amount']),$total['outstock_amount']), $total['to_amount'], 3);
        $this -> view -> param = $param;
    }
    
    public function financeGiftSumAction()
    {
        $param = $this -> _request -> getParams();
        if (!$param['fromdate']) {
            $param['fromdate'] = '2013-10-25';
        }
        
        if ($param['todo'] == 'search' || $param['todo'] == 'export') {
            $datas = $this -> _api -> getfinanceGiftSum($param);
            
            if ($param['todo'] == 'export') {
                $content[] = array('面值', '期初卡内余额', '本期售卡数', '预售面值', '预售总金额(激活)', '预售总金额(在途)', '本期卡内消费1', '本期卡内消费2', '卡内退货退回金额', '期末卡内余额');
                if ($datas) {
                    foreach ($datas as $key => $data) {
                        $content[] = array($key, $data['from_price'], $data['count'], $data['card_amount'], $data['active_amount'], $data['plan_amount'], $data['use_amount1'], $data['use_amount2'], $data['return_amount'], $data['to_price']);
                    }
                }
                $opt_api = new Admin_Models_API_OpLog();
				$opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-financeGiftSum");
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('finance-gift-sum');
                
            	exit;
            }
            
            if ($datas) {
                foreach ($datas as $data) {
                    $total['from_price'] += $data['from_price'];
                    $total['count'] += $data['count'];
                    $total['card_amount'] += $data['card_amount'];
                    $total['active_amount'] += $data['active_amount'];
                    $total['plan_amount'] += $data['plan_amount'];
                    $total['use_amount1'] += $data['use_amount1'];
                    $total['use_amount2'] += $data['use_amount2'];
                    $total['return_amount'] += $data['return_amount'];
                    $total['to_price'] += $data['to_price'];
                }
            }
        }
        
        $this -> view -> param = $param;
        $this -> view -> datas = $datas;
        $this -> view -> total = $total;
    }
    
    public function financeInOutStockAction()
    {
        $stockConfig = new Custom_Model_Stock_Config();

        $param = $this -> _request -> getParams();
        if (!$param['stock_type']) {
            $param['stock_type'] = 'instock';
        }
        
        if ($param['todo'] == 'search' || $param['todo'] == 'export') {
            $datas = $this -> _api -> getFinanceInOutStock($param);

            if ($datas) {
                foreach ($datas as $data) {
                    $total['count'] += $data['count'];
                    $total['cost'] += $data['cost'];
                    $total['no_tax_cost'] += $data['no_tax_cost'];
                }
            }

            if ($param['todo'] == 'export') {
                $opt_api = new Admin_Models_API_OpLog();
                $opt_api->addopt($this ->_auth['admin_id'],"DataAnalysis-financeInOutStock");
                $content[] = array('供应商', '总数量', '总成本','总成本(不含税)', '单据编号');
                if ($datas) {
                    foreach ($datas as $data) {
                        $content[] = array($data['supplier_name'], $data['count'], $data['cost'], $data['no_tax_cost'], str_replace('<br>', ' ', $data['bill']));
                    }
                }
                $xls = new Custom_Model_GenExcel();
            	$xls -> addArray($content);
            	$xls -> generateXML('finance-in-out-stock');
                
            	exit();
            }
        }

        $this -> view -> in_stock_type = $stockConfig -> _inTypes;
        $this -> view -> out_stock_type = $stockConfig -> _outTypes;
        $this -> view -> datas = $datas;
        $this -> view -> total = $total;
        $this -> view -> param = $param;
    }
}


function compareRecord($a, $b) {
    global $sortType, $fieldName;

	if ( $sortType == 1 )       return $a[$fieldName] > $b[$fieldName];
	else if ( $sortType == 2 )  return $a[$fieldName] < $b[$fieldName];
	else    return 0;
}
