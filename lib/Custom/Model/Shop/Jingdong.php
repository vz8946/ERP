<?php
class Custom_Model_Shop_Jingdong extends Custom_Model_Shop_Base
{
	private $_pageSize = 10;
	private $_provinceData;
	private $_cityData;
	private $_areaData;
	private $_logistic;
	
	/**
     * 构造函数
     *
     * @param   void
     * @return  void
     */
	public function __construct($config)
	{
		parent::__construct($config);
        
        $this -> _api = new Custom_Model_JingDongNewAPI_JdClient();
        $this -> _api -> appKey = $this -> _config['key'];
        $this -> _api -> appSecret = $this -> _config['secret'];
        $this -> _api -> accessToken = $this -> _config['token'];
	}
	
	/**
     * 生成店铺类型特殊字段
     *
     * @param   int     $shopID
     * @return  void
     */
	public function getConfigField() {
	    return array('key' => 'key',
	                 'secret' => 'secret',
	                 'token' => 'token',
	                );
	}
	
	/**
     * 同步商品
     *
     * @return  void
     */
    public function syncGoods() {
        $this -> initSyncLog();
        
        $result1 = $this -> doSyncGoods('selling');
        $result2 = $this -> doSyncGoods('waiting');
        
        return array_merge($result1, $result2);
    }
    
    private function doSyncGoods($wareState) {
        if ($wareState == 'selling')  {
            $this -> _request = new Custom_Model_JingDongNewAPI_Request_Ware_WareListingGetRequest();
        }
        else {
            $this -> _request = new Custom_Model_JingDongNewAPI_Request_Ware_WareDelistingGetRequest();
        }
        
        $currentPage = 1;
        $this -> _request -> setPage($currentPage++);
        $this -> _request -> setPageSize($this -> _pageSize);
        $resp = $this -> _api -> execute($this -> _request);
        if ($wareState == 'selling')  {
            $resp = $resp -> ware_listing_get_response;
        }
        else {
            $resp = $resp -> ware_delisting_get_response;
        }
        
        if ( $resp -> code != '0' ) {
            $this -> _log .= 'errorCode:'.$resp -> code."\n";
            return false;
        }
        
        $count = $resp -> total;
        if ( $count == 0 ) {
            $this -> _log .= "没有商品\n";
            return false;
        }

        $totalPage = ceil($count / $this -> _pageSize);
        $goods_id_array = array();
        do {
            if ( !$resp -> ware_infos || count($resp -> ware_infos) == 0 )  break;
            
            foreach ( $resp -> ware_infos as $goods ) {
                unset($goodsRow);
                $goodsRow['shop_goods_id'] = $goods -> ware_id;
			    $goodsRow['shop_goods_name'] = $goods -> title;
			    $goodsRow['onsale'] = $wareState == 'selling' ? 0 : 1;
	            $goodsRow['goods_sn'] = $goods -> item_num;
				$goodsRow['shop_price'] = $goods -> jd_price;
				$goodsRow['stock_number'] = $goods -> stock_num;
	            
	            $goods_id_array[] = $goods -> ware_id;
	            
	            $result[] = $goodsRow;
            }
            
            $this -> _request -> setPage($currentPage);
            $resp = $this -> _api -> execute($this -> _request);
            if ($wareState == 'selling')  {
                $resp = $resp -> ware_listing_get_response;
            }
            else {
                $resp = $resp -> ware_delisting_get_response;
            }
            
            $currentPage++;
        }
        while ( $currentPage <= $totalPage + 1 );
        
        if (!$goods_id_array)   return false;
        $split_goods_id_array = array_chunk($goods_id_array, $this -> _pageSize);
        
        for ( $i = 0; $i < count($split_goods_id_array); $i++ ) {
            $this -> _request = new Custom_Model_JingDongNewAPI_Request_Ware_WareSkusGetRequest();
            $this -> _request -> setWareIds(implode(',', $split_goods_id_array[$i]));
            $resp = $this -> _api -> execute($this -> _request);
            $resp = $resp -> ware_skus_get_response;
            if ( $resp -> code != '0' ) {
                $this -> _log .= 'errorCode:'.$resp -> code."\n";
                return false;
            }
            
            if ( !$resp -> skus || count($resp -> skus) == 0 )  continue;
            
            foreach ( $resp -> skus as $goods ) {
                $skuInfo[$goods -> ware_id] = $goods -> sku_id;
            }
        }
        
        foreach ($result as $index => $goods) {
            $result[$index]['shop_sku_id'] = $skuInfo[$goods['shop_goods_id']];
        }
        
        return $result;
    }
    
    /**
     * 同步订单
     *
     * @return  void
     */
    public function syncOrder($area, $startDate, $endDate, $shopGoods = null) {
        parent::syncOrder($area, $startDate, $endDate, $shopGoods);
        
        $this -> initSyncLog();
        
        $this -> _request = new Custom_Model_JingDongNewAPI_Request_Order_OrderSearchRequest();
	    
	    $this -> _pageSize = 10;
	    $this -> _request -> setOrderState("WAIT_SELLER_STOCK_OUT,WAIT_GOODS_RECEIVE_CONFIRM,FINISHED_L,TRADE_CANCELED");
    	$this -> _request -> setPageSize($this -> _pageSize);
    	$this -> _request -> setOptionalFields("order_id,pay_type,order_total_price,freight_price,seller_discount,order_payment,delivery_type,order_state,order_state_remark,invoice_info,order_remark,order_start_time,order_end_time,consignee_info,item_info_list,vender_remark");
    	
    	$startDate = strtotime($this -> _orderStartTime);
    	$endDate = strtotime($this -> _orderEndTime);
    	
	    while ( $startDate <= $endDate ) {
	        $this -> _request -> setStartDate(date('Y-m-d H:i:s', $startDate));
	        if (($startDate + 3600 * 24 * 7 - 1) > $endDate) {
	            $this -> _request -> setEndDate(date('Y-m-d H:i:s', $endDate));
	        }
    	    else {
    	        $this -> _request -> setEndDate(date('Y-m-d H:i:s', $startDate + 3600 * 24 * 7 - 1));
    	    }
    	    
    	    $startDate += 3600 * 24 * 7;
    	    $page = 1;
    	    
    	    $this -> _request -> setPage($page);
            $resp = $this -> _api -> execute($this -> _request);
            $resp = $resp -> order_search_response;
            
            if ($resp -> code != '0') {
                $this -> _log .= "errorCode: ".$resp -> code ."\n";
                continue;
            }
            
            if ($resp -> order_search -> order_total == 0)   continue;

            $totalPage = ceil($resp -> order_search -> order_total / $this -> _pageSize);
            
	        while (1) {
                foreach ($resp -> order_search -> order_info_list as $order) {
                    $result[] = $this -> getOrderMain($order);
                }
                
                $page++;
                if ($page > $totalPage)   break;
                
                $this -> _request -> setPage($page);
                $resp = $this -> _api -> execute($this -> _request);
                $resp = $resp -> order_search_response;
                
                if ($resp -> code != '0') {
                    $this -> _log .= "errorCode: ".$resp -> code ."\n";
                    return false;
                }
	        }
	    }
        
        if ($result) {
            $this -> _request = new Custom_Model_JingDongNewAPI_Request_Order_OrderGetRequest();
            for ( $i = 0; $i < count($result); $i++ ) {
                $this -> _request -> setOrderId($result[$i]['external_order_sn']);
                $this -> _request -> setOptionalFields("item_info_list,coupon_detail_list");
                $resp = $this -> _api -> execute($this -> _request);
                $resp = $resp -> order_get_response;
                if ($resp -> code != '0') {
                    $this -> _log .= "errorCode: ".$resp -> code."\n";
                    return false;
                }
                
                $detail = $this -> getOrderDetail($resp -> order -> orderInfo, $shopGoods);
                $goodsInfo = $detail['goodsInfo'];
                $orderDiscount = $detail['orderDiscount'];
                
                for ($j = 0; $j < count($result[$i]['goods']); $j++) {
                    $result[$i]['goods'][$j]['discount_price'] = $goodsInfo[$result[$i]['goods'][$j]['shop_sku_id']]['discount_price'];
                    $result[$i]['goods'][$j]['goods_sn'] = $goodsInfo[$result[$i]['goods'][$j]['shop_sku_id']]['goods_sn'];
                }
                
                if ($orderDiscount > 0) {
                    $totalDiscount = 0;
                    for ($j = 0; $j < count($result[$i]['goods']); $j++) {
                        if ($j == (count($result[$i]['goods']) - 1)) {
                            $result[$i]['goods'][$j]['discount_price'] += $orderDiscount - $totalDiscount;
                        }
                        else {
                            $tempDiscount = round(($result[$i]['goods'][$j]['price'] * $result[$i]['goods'][$j]['number'] - $result[$i]['goods'][$j]['discount_price']) / $result[$i]['goods_amount'] * $orderDiscount, 2);
                            $result[$i]['goods'][$j]['discount_price'] += $tempDiscount;
                            $totalDiscount += $tempDiscount;
                        }
                    }
                }
            }
        }
        
        return $result;
    }
    
    /**
     * 同步单个订单
     *
     * @return  void
     */
    public function syncOneOrder($externalOrderSN, $area = null, $shopGoods = null) {
        $this -> _area = $area;
        $this -> _goods = $shopGoods;
        
        $this -> _request = new Custom_Model_JingDongNewAPI_Request_Order_OrderGetRequest();
        $this -> _request -> setOrderId($externalOrderSN);
        $this -> _request -> setOptionalFields("order_id,pay_type,order_total_price,freight_price,seller_discount,order_payment,delivery_type,order_state,order_state_remark,invoice_info,order_remark,order_start_time,order_end_time,consignee_info,item_info_list,vender_remark,item_info_list,coupon_detail_list");
    	$resp = $this -> _api -> execute($this -> _request);
        $resp = $resp -> order_get_response;
        if ($resp -> code != '0') {
            $this -> _log .= "errorCode: ".$resp -> code."\n";
            return false;
        }
        
        $result = $this -> getOrderMain($resp -> order -> orderInfo);
        $detail = $this -> getOrderDetail($resp -> order -> orderInfo, $shopGoods);
        $goodsInfo = $detail['goodsInfo'];
        $orderDiscount = $detail['orderDiscount'];
        $result['amount'] -= $detail['changeAmount'];
        $result['goods_amount'] -= $detail['changeAmount'];
        
        for ($j = 0; $j < count($result['goods']); $j++) {
            $result['goods'][$j]['discount_price'] = $goodsInfo[$result['goods'][$j]['shop_sku_id']]['discount_price'];
            $result['goods'][$j]['goods_sn'] = $goodsInfo[$result['goods'][$j]['shop_sku_id']]['goods_sn'];
        }
                
        if ($orderDiscount > 0) {
            $totalDiscount = 0;
            for ($j = 0; $j < count($result['goods']); $j++) {
                if ($j == (count($result['goods']) - 1)) {
                    $result['goods'][$j]['discount_price'] += $orderDiscount - $totalDiscount;
                }
                else {
                    $tempDiscount = round(($result['goods'][$j]['price'] * $result['goods'][$j]['number'] - $result['goods'][$j]['discount_price']) / $result['goods_amount'] * $orderDiscount, 2);
                    $result['goods'][$j]['discount_price'] += $tempDiscount;
                    $totalDiscount += $tempDiscount;
                }
            }
        }
        
        return $result;
    }
    
    private function getOrderMain($order) {
        $orderRow['external_order_sn'] = $order -> order_id;
        $orderRow['status'] = $this -> mapOrderStatus($order -> order_state);
        $orderRow['amount'] = $order -> order_total_price - $order -> seller_discount;
        $orderRow['discount_amount'] = $order -> seller_discount;
        $orderRow['goods_amount'] = $order -> order_total_price - $order -> seller_discount - $order -> freight_price;
        $orderRow['pay_amount'] = $order -> order_payment;
        $orderRow['freight'] = $order -> freight_price;
        $orderRow['order_time'] = strtotime($order -> order_start_time);
        $orderRow['pay_time'] = 0;
        $orderRow['pay_name'] = $order -> pay_type;
        $orderRow['addr_consignee'] = $order -> consignee_info -> fullname;
        $orderRow['addr_address'] = $order -> consignee_info -> full_address;
        $orderRow['addr_tel'] = $order -> consignee_info -> telephone;
        $orderRow['addr_mobile'] = trim($order -> consignee_info -> mobile);
        if ($order -> invoice_info != '不需要开具发票') {
            preg_match('/发票类型:(.*);发票抬头:(.*);发票内容:(.*)/', $order -> invoice_info, $match);
            $orderRow['invoice'] = $match[2];
            $orderRow['invoice_content'] = $match[3];
        }
                    
        $orderRow['memo'] = $order -> order_remark.' '.$order -> vender_remark;
        $orderRow['addr_org_address'] = $orderRow['addr_address'];
        if (in_array($orderRow['status'], array(1,2,3,10))) {
            $addressInfo = $this -> mapAddress($orderRow['addr_address']);
            if ($addressInfo) {
                $orderRow['addr_province'] = $addressInfo['province'];
                $orderRow['addr_city'] = $addressInfo['city'];
                $orderRow['addr_area'] = $addressInfo['area'];
                $orderRow['addr_province_id'] = $addressInfo['province_id'];
                $orderRow['addr_city_id'] = $addressInfo['city_id'];
                $orderRow['addr_area_id'] = $addressInfo['area_id'];
                $orderRow['addr_address'] = $addressInfo['address'];
            }
            else {
                $this -> _log .= "订单号{$orderRow['external_order_sn']}地址匹配错误: {$orderRow['addr_address']}\n";
                //continue;
            }
        }
                    
        foreach ( $order -> item_info_list as $goods ) {
            $orderRow['goods'][] = array('shop_goods_name' => $goods -> sku_name,
                                         'shop_goods_id' => $goods -> ware_id,
                                         'shop_sku_id' => $goods -> sku_id,
                                         'goods_sn' => '',
                                         'price' => $goods -> jd_price,
                                         'number' => $goods -> item_total,
                                         'discount_price' => 0,
                                        );
        }
        
        return $orderRow;
    }
    
    private function getOrderDetail($orderInfo, &$shopGoods) {
        if ($orderInfo -> item_info_list) {
            foreach ($orderInfo -> item_info_list as $item) {
                $goodsInfo[$item -> sku_id]['goods_sn'] = $item -> product_no ? $item -> product_no : $shopGoods[$item -> sku_id];
            }
        }
        $orderDiscount = 0;
        if ($orderInfo -> coupon_detail_list) {
            foreach ($orderInfo -> coupon_detail_list as $coupon) {
                if ($coupon -> order_id) {
                    if ($coupon -> sku_id) {
                        $goodsInfo[$coupon -> sku_id]['discount_price'] = $coupon -> coupon_price;
                    }
                    else {
                        if (!in_array(substr($coupon -> coupon_type, 0, 3), array('41-', '52-'))) {    //41-京东券优惠 52-礼品卡优惠
                            $orderDiscount += $coupon -> coupon_price;
                        }
                        if (substr($coupon -> coupon_type, 0, 3) == '39-') {
                            //$changeAmount += $coupon -> coupon_price;
                        }
                    }
                }
            }
        }

        return array('goodsInfo' => $goodsInfo, 'orderDiscount' => $orderDiscount, 'changeAmount' => $changeAmount);
    }
    
    /**
     * 同步库存
     *
     * @param   array   $stockData
     * @return  void
     */
    public function syncStock($stockData) {
        parent::syncStock($stockData);

        $this -> initSyncLog();
        
        if (!$stockData)    $this -> _log .= '无商品';
        
        $this -> _request = new Custom_Model_JingDongNewAPI_Request_Ware_WareSkuStockUpdateRequest();
        foreach ($stockData as $stock) {
            $this -> _request -> setSkuId($stock['sku_id']);
            $this -> _request -> setQuantity($stock['number'] > 0 ? $stock['number'] : '0');
            $this -> _request -> setTradeNo(date('Y-m-d H:i:s').substr($stock['sku_id'], -4));
            $resp = $this -> _api -> execute($this -> _request);
            $resp = $resp -> ware_sku_stock_update_response;
            if ( $resp -> code != '0' ) {
                $this -> _log .= "error: ".$resp -> zh_desc."\n";
                return false;
            }
        }
    }
    
    /**
     * 同步发货信息
     *
     * @param   string  $shopOrderSN
     * @param   array   $logistic
     * @return  void
     */
    public function syncShopLogistic($shopOrderSN, $logistic) {
        if (!$logistic['logistic_code'] || !$logistic['logistic_no'] || !$logistic['logistic_time']) {
            $this -> _log .= "订单{$shopOrderSN}物流信息不完全，不能同步店铺发货\n";
            return false;
        }
        
        $this -> getLogisticCompany();
        
        $logisticsID = $this -> _logistic[$logistic['logistic_code']];
        if ( !$logisticsID ) {
            $this -> _log .= "订单{$shopOrderSN}找不到对应的物流公司，不能同步店铺发货\n";
            return false;
        }
        
        $this -> _request = new Custom_Model_JingDongNewAPI_Request_Order_OrderSopOutstorageRequest();
        $this -> _request -> setOrderId($shopOrderSN);
        $this -> _request -> setLogisticsId($logisticsID);
        $this -> _request -> setWaybill($logistic['logistic_no']);
        $this -> _request -> setTradeNo(date('Y-m-d H:i:s').substr($shopOrderSN, -4));
        $resp = $this -> _api -> execute($this -> _request);
        $resp = $resp -> order_sop_outstorage_response;
        if ( $resp -> code != '0' ) {
            $this -> _log .= "error: ".$resp -> zh_desc."\n";
            return false;
        }
        
        return true;
    }
    
    /**
     * 获得所有物流公司
     *
     * @return  void
     */
    private function getLogisticCompany() {
        if ( $this -> _logistic )   return true;
        
        $this -> _logistic['yt'] = 463;
        $this -> _logistic['sf'] = 467;
        $this -> _logistic['ems'] = 465;
        return;
        
        $this -> _request = new Custom_Model_JingDongNewAPI_Request__Delivery_DeliveryLogisticsGetRequest();
        $resp = $this -> _api -> execute($this -> _request);
        $resp = $resp -> delivery_logistics_get_response;
        
        foreach ( $resp -> logistics_companies -> logistics_list as $logistic ) {
            $code = $this -> mapLogistic($logistic -> logistics_name);
            if ($code) {
                $this -> _logistic[$code] = $logistic -> logistics_id;
            }
        }
    }
    
    /**
     * 获得订单状态
     *
     * @return  void
     */
    public function getOrderStatus($externalOrderSN) {
        $this -> _request = new Custom_Model_JingDongNewAPI_Request_Order_OrderGetRequest();
	    
	    $this -> _request -> setOrderId($externalOrderSN);
	    $this -> _request -> setOptionalFields("order_state");
	    $resp = $this -> _api -> execute($this -> _request);
	    $resp = $resp -> order_get_response;
        if ( $resp -> code != '0' ) {
            $this -> _log .= "errorCode: ".$resp -> code."\n";
            return false;
        }

        return $this -> mapOrderStatus($resp -> order -> orderInfo -> order_state);
    }
    
    /**
     * 匹配订单状态
     *
     * @param   string  $status
     * @return  void
     */
    private function mapOrderStatus($status) {
        if ( $status == 'WAIT_SELLER_STOCK_OUT' )       return 2;
        if ( $status == 'WAIT_GOODS_RECEIVE_CONFIRM')   return 3;
        if ( $status == 'FINISHED_L' )                  return 10;
        if ( $status == 'TRADE_CANCELED' )              return 11;
        if ( $status == 'LOCKED' )                      return 12;
    }
    
    /**
     * 匹配订单状态
     *
     * @param   string  $status
     * @return  void
     */
    private function mapOrderStatus2($status) {
        if ( $status == '延迟付款确认' )  return 1;
        if ( $status == '等待出库' )      return 2;
        if ( $status == '等待发货' )      return 2;
        if ( $status == '等待确认收货' )  return 3;
        if ( $status == '完成' )          return 10;
        if ( $status == '(删除)暂停' )    return 11;
        if ( $status == '暂停' )          return 12;
        if ( $status == '锁定' )          return 12;
        if ( $status == '(删除)锁定' )    return 11;
        if ( $status == '等待付款确认' )  return 1;
    }
    
    /**
     * 匹配收货地址
     *
     * @param   string  $status
     * @return  void
     */
    private function mapAddress($address) {
        foreach ($this -> _area[1] as $key => $value) {
            if ($key == substr($address, 0, strlen($key))) {
                $result['province_id'] = $value;
                $result['province'] = $key;
            }
        }
        if (!$result['province_id'])  return false;
        
        $address = substr($address, strlen($result['province']), strlen($address));
        
        if (in_array($result['province_id'], $this -> getSpecialAreaID())) {
            $tempArr = $this -> getSpecialCity($result['province_id']);
            $result['city_id'] = $tempArr[0];
            $result['city'] = $tempArr[1];
        }
        else {
            foreach ($this -> _area[$result['province_id']] as $key => $value) {
                if ($key == substr($address, 0, strlen($key))) {
                    $result['city_id'] = $value;
                    $result['city'] = $key;
                }
            }
            if (!$result['city_id'])  return false;
            
            $address = substr($address, strlen($result['city']), strlen($address));
        }
        
        foreach ($this -> _area[$result['city_id']] as $key => $value) {
            if ($key == substr($address, 0, strlen($key))) {
                $result['area_id'] = $value;
                $result['area'] = $key;
            }
        }
        
        if (!$result['area_id']) {
            foreach ($this -> _area[$result['province_id']] as $key => $value) {
                if ( $key == substr($address, 0, strlen($key)) ) {
                    $result['city_id'] = $value;
                    $result['city'] = $key;
                    $result['area_id'] = $this -> _area[$result['city_id']][$key];
                    $result['area'] = $key;
                }
            }
            if ($result['area_id']) {
                $address = substr($address, strlen($result['area']), strlen($address));
            }
        }
        
        if (!$result['area_id']) {
            foreach ($this -> _area[$result['city_id']] as $key => $value) {
                if ($key == $result['city']) {
                    $result['area_id'] = $value;
                    $result['area'] = $key;
                }
            }
        }
        
        if (!$result['area_id']) {
            foreach ($this -> _area[$result['city_id']] as $key => $value) {
                $tag = substr($key, -3);
                $pos = strpos($key, $tag);
                
                $preArea = substr($address, 0, $pos).$tag;
                if ($preArea == $key) {
                    $result['area_id'] = $value;
                    $result['area'] = $key;
                    $address = substr($address, $pos, strlen($address));
                }
            }
        }
        
        if (!$result['area_id']) {
            return false;
        }
        
        $result['address'] = $address;
        
        return $result;
    }
    
    /**
     * 匹配物流公司
     *
     * @param   string  $status
     * @return  void
     */
    private function mapLogistic($logisticName) {
        if ( $logisticName == '申通快递' )  return 'st';
        if ( $logisticName == '厂家自送' )  return 'yt';
        if ( $logisticName == '汇通快递' )  return 'ht';
        if ( $logisticName == '汇通快运' )  return 'ht';
        if ( $logisticName == '邮政EMS' )   return 'ems';
        if ( $logisticName == '圆通快递' )  return 'yt';
        if ( $logisticName == '顺风' )  return 'sf';
        
        return false;
    }
    
    /**
     * 获得销售单打印模板
     *
     * @return  string
     */
	public static function getPrintTpl() {
	    return 'shop/print-jingdong';
	}
	
	/**
     * 获得发票信息
     *
     * @param   string  $status
     * @return  void
     */
    private function getInvoice($invoice) {
        if (!$invoice || $invoice == '不需要开具发票') {
            $result = '';
        } 
        else {
            preg_match('/发票类型:普通发票;发票抬头:(.*);发票内容:明细;/', $invoice, $match);
            $result = $match[1];
        }
        
        return $result;
    }
	
}