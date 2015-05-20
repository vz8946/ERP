<?php
class Custom_Model_Shop_Yihaodian extends Custom_Model_Shop_Base
{
	private $_pageSize = 10;
	private $_url = 'http://openapi.yihaodian.com/forward/api/rest/router';
	
	/**
     * 构造函数
     *
     * @param   void
     * @return  void
     */
	public function __construct($config)
	{
		parent::__construct($config);
		
		$this -> _api = new Custom_Model_Yihaodianapi_Client();
	}
	
	/**
     * 生成店铺类型特殊字段
     *
     * @param   int     $shopID
     * @return  void
     */
	public function getConfigField() {
	    return array('check_code' => '店铺代码',
	                 'merchant_id' => '商户ID',
	                 'secret' => '密钥',
	                );
	}
	
	private function getParams()
	{
	    $paramArray['checkCode'] = $this -> _config['check_code'];
    	$paramArray['merchantId'] = $this -> _config['merchant_id'];
    	$paramArray['erp'] = "self";
    	$paramArray['erpVer'] = "1.0";
    	$paramArray['format'] = "xml";
    	$paramArray['ver'] = "1.0";
    	
    	return $paramArray;
	}
	
	/**
     * 同步商品
     *
     * @return  void
     */
    public function syncGoods() {
        $this -> initSyncLog();
        
        $paramArray = $this -> getParams();
    	$paramArray['method'] = "yhd.general.products.search";
	    $paramArray['pageRows'] = 100;
        $curPage = 1;
    	$paramArray['curPage'] = $curPage;
    	
    	$result = new Zend_Config_Xml($this -> _api -> sendByPost($this -> _url, $paramArray, array(), $this -> _config['secret']));
	    $result = $result -> toArray();
    	if ($result['errorCount']) {
    	    var_dump($result);exit;
    	}
        
        $productIDArray = array();
    	$totalPage = ceil($result['totalCount'] / 100);
	    while ( $curPage <= $totalPage ) {
            if (!$result['productList']['product'][0]) {
	            $result['productList']['product'] = array($result['productList']['product']);
	        }

	        foreach ( $result['productList']['product'] as $goods ) {
	            if (!$goods['outerId']) continue;
	            
                unset($goodsRow);
	            
	            $goodsRow['shop_id'] = $this -> _shopID;
	            $goodsRow['shop_goods_id'] = $goods['productId'];
			    $goodsRow['shop_goods_name'] = $goods['productCname'];
			    $goodsRow['onsale'] = $goods['canSale'] == '1' ? 0 : 1;
	            $goodsRow['goods_sn'] = $goods['outerId'];
				$goodsRow['shop_sku_id'] = $goods['productId'];
			    $goodsRow['shop_price'] = 0;
				$goodsRow['shop_supply_price'] = 0;
				$goodsRow['stock_number'] = 0;
	            
	            $goodsArray[] = $goodsRow;
	            $productIDArray[] = $goods['productId'];
            }
        
            $curPage++;
	        if ($curPage <= $totalPage) {
    	        $paramArray['curPage'] = $curPage;
                $result = new Zend_Config_Xml($this -> _api -> sendByPost($this -> _url, $paramArray, array(), $this -> _config['secret']));
        	    $result = $result -> toArray();
                if ($result['errorCount']) {
        	        break;
        	    }
        	}
        	else    break;
	    }
	    
	    if (count($productIDArray) > 0 ) {
	        $curPage = 1;
	        $totalPage = ceil(count($productIDArray) / 100);
	        while ( $curPage <= $totalPage ) {
	            $startPos = ($curPage - 1) * 100;
	            $endPos = $startPos + 100 - 1;
	            $ids = array();
	            for ($i = $startPos; $i < $endPos; $i++) {
	                $ids[] = $productIDArray[$i];
	                if ($i >= count($productIDArray) - 1) {
	                    break;
	                }
	            }
	            
	            $paramArray = $this -> getParams();
            	$paramArray['method'] = "yhd.products.price.get";
            	$paramArray['productIdList'] = implode(',', $ids);
            	$result = new Zend_Config_Xml($this -> _api -> sendByPost($this -> _url, $paramArray, array(), $this -> _config['secret']));
        	    $result = $result -> toArray();
	            if ($result['pmPriceList']['pmPrice']) {
	                foreach ($result['pmPriceList']['pmPrice'] as $product) {
	                    $priceInfo[$product['productId']] = $product['nonMemberPrice'];
	                }
	            }
	            
	            $curPage++;
	        }
	        
	        foreach ($goodsArray as $index => $goods) {
	            $goodsArray[$index]['shop_price'] = $priceInfo[$goods['shop_goods_id']] ? $priceInfo[$goods['shop_goods_id']] : 0;
	        }
	    }
        
	    return $goodsArray;
    }
    
    /**
     * 同步订单
     *
     * @return  void
     */
    public function syncOrder($area, $startDate, $endDate, $flag = 1) {
        parent::syncOrder($area, $startDate, $endDate);
        
        $this -> initSyncLog();
        
        $paramArray = $this -> getParams();
    	$paramArray['method'] = "yhd.orders.get";
    	if ($flag == 1) {
    	    $paramArray['dateType'] = 1;
    	    $startDate = strtotime($this -> _orderStartTime);
    	    $endDate = strtotime($this -> _orderEndTime);
    	}
    	else {
    	    $paramArray['dateType'] = 5;
    	    $startDate = time() - 3600;
    	    $endDate = time();
    	}
    	$paramArray['orderStatusList'] = 'ORDER_WAIT_SEND,ORDER_ON_SENDING,ORDER_RECEIVED,ORDER_FINISH,ORDER_GRT,ORDER_CANCEL';
	    $paramArray['pageRows'] = 100;
        
	    while ( $startDate <= $endDate ) {
	        $paramArray['startTime'] = date('Y-m-d H:i:s', $startDate);
	        if ( ($startDate + 3600 * 24 * 7 - 1) > $endDate ) {
	            $paramArray['endTime'] = date('Y-m-d H:i:s', $endDate);
	        }
    	    else {
    	        $paramArray['endTime'] = date('Y-m-d H:i:s', $startDate + 3600 * 24 * 7 - 1);
    	    }
    	    
    	    $startDate += 3600 * 24 * 7;
    	    
    	    $curPage = 1;
    	    $paramArray['curPage'] = $curPage;
    	    $result = new Zend_Config_Xml($this -> _api -> sendByPost($this -> _url, $paramArray, array(), $this -> _config['secret']));
	        $result = $result -> toArray();
    	    if ($result['errorCount'] || $result['totalCount'] == 0) {
	            continue;
	        }
	        
    	    $totalPage = ceil($result['totalCount'] / 100);
	        while ( $curPage <= $totalPage ) {
	            if (!$result['orderList']['order'][0]) {
	                $result['orderList']['order'] = array($result['orderList']['order']);
	            }
	            foreach ( $result['orderList']['order'] as $order ) {
	                $orderArray[] = $this -> getOrderDetail($order);
	            }
	            
	            $curPage++;
	            if ($curPage <= $totalPage) {
    	            $paramArray['curPage'] = $curPage;
            	    $result = new Zend_Config_Xml($this -> _api -> sendByPost($this -> _url, $paramArray, array(), $this -> _config['secret']));
        	        $result = $result -> toArray();
            	    if ($result['errorCount'] || $result['totalCount'] == 0) {
        	            break;
        	        }
        	    }
        	    else    break;
	        }
	    }
        
        return $orderArray;
    }
    
    /**
     * 同步单个订单
     *
     * @return  void
     */
    public function syncOneOrder($externalOrderSN, $area = null) {
        $this -> _area = $area;
        return $this -> getOrderDetail(array('orderCode' => $externalOrderSN));
    }
    
    /**
     * 获得一个订单明细
     *
     * @return  void
     */
    private function getOrderDetail($order) {
        $tempParamArray = $this -> getParams();
	    $tempParamArray['method'] = "yhd.order.detail.get";
	    $tempParamArray['orderCode'] = $order['orderCode'];
	    $detailResult = new Zend_Config_Xml($this -> _api -> sendByPost($this -> _url, $tempParamArray, array(), $this -> _config['secret']));
	    $detailResult = $detailResult -> toArray();
	    if ($detailResult['errorCount']) {
	        var_dump($detailResult);exit;
	    }
	    $detail = $detailResult['orderInfo']['orderDetail'];
        
	    $orderRow['external_order_sn'] = $order['orderCode'];
	    $orderRow['status'] = $this -> mapOrderStatus($detail['orderStatus']);
	    $orderRow['amount'] = $detail['orderAmount'] - $detail['orderCouponDiscount'];
	    $orderRow['pay_amount'] = $detail['orderAmount'];
	    $orderRow['freight'] = $detail['orderDeliveryFee'];
	    $orderRow['order_time'] = strtotime($detail['orderCreateTime']);
        
	    if ($detail['orderNeedInvoice']) {
	        $tempParamArray = $this -> getParams();
    	    $tempParamArray['method'] = "yhd.invoices.get";
    	    $tempParamArray['orderCodeList'] = $order['orderCode'];
    	    $invoiceResult = new Zend_Config_Xml($this -> _api -> sendByPost($this -> _url, $tempParamArray, array(), $this -> _config['secret']));
    	    $invoiceResult = $invoiceResult -> toArray();
    	    if (!$invoiceResult['errorCount']) {
    	        $orderRow['invoice'] = $invoiceResult['invoiceInfoList']['invoiceInfo']['invoiceTitle'];
    	        $orderRow['invoice_content'] = $invoiceResult['invoiceInfoList']['invoiceInfo']['invoiceContents']['invoiceContent'];
    	    }
	    }
        
	    $orderRow['discount_amount'] = $detail['orderPromotionDiscount'] + $detail['orderCouponDiscount'];
	    $orderRow['goods_amount'] = $detail['productAmount'] - $detail['orderPromotionDiscount'] - $detail['orderCouponDiscount'];
	    $orderRow['pay_time'] = strtotime($detail['orderCreatePayTime']);
	    $orderRow['addr_consignee'] = $detail['goodReceiverName'];
	    $orderRow['addr_address'] = $detail['goodReceiverAddress'];
	    $orderRow['addr_zip'] = $detail['goodReceiverPostCode'];
	    $orderRow['addr_tel'] = $detail['goodReceiverPhone'];
	    $orderRow['addr_mobile'] = $detail['goodReceiverMoblie'];
	    $orderRow['memo'] = $detail['deliveryRemark'].' '.$detail['merchantRemark'];
	    if ($detail['deliverySupplierId']) {
	        $logistic_code = $this -> mapLogistic($detail['deliverySupplierId']);
    	    if (!$logistic_code) {
                $this -> _log .= "订单{$orderRow['external_order_sn']}物流公司{$detail['deliverySupplierId']}匹配不到\n";
            }
	        $orderRow['logistic_code'] = $logistic_code;
            $orderRow['logistic_time'] = strtotime($detail['deliveryDate']);
            $orderRow['logistic_no'] = $detail['merchantExpressNbr'];
        }
        $orderRow['addr_org_address'] = $detail['goodReceiverProvince'].$detail['goodReceiverCity'].$detail['goodReceiverCounty'].$detail['goodReceiverAddress'];
        $addressInfo = $this -> mapAddress($detail['goodReceiverProvince'],$detail['goodReceiverCity'], $detail['goodReceiverCounty']);
        if ($addressInfo) {
            $orderRow['addr_province'] = $addressInfo['province'];
            $orderRow['addr_city'] = $addressInfo['city'];
            $orderRow['addr_area'] = $addressInfo['area'];
            $orderRow['addr_province_id'] = $addressInfo['province_id'] ? $addressInfo['province_id'] : 0;
            $orderRow['addr_city_id'] = $addressInfo['city_id'] ? $addressInfo['city_id'] : 0;
            $orderRow['addr_area_id'] = $addressInfo['area_id'] ? $addressInfo['area_id'] : 0;
        }
        else {
            $this -> _log .= "订单号{$orderRow['external_order_sn']}地址匹配错误: {$detail['goodReceiverProvince']} {$detail['goodReceiverCity']} {$detail['goodReceiverCounty']}\n";
        }
        if (!$orderRow['addr_province_id'] || !$orderRow['addr_city_id'] || !$orderRow['addr_area_id']) {
            $orderRow['addr_address'] = $orderRow['addr_org_address'];
        }
        
        $goodsAmount = 0;
        if (!$detailResult['orderInfo']['orderItemList']['orderItem'][0]) {
            $detailResult['orderInfo']['orderItemList']['orderItem'] = array($detailResult['orderInfo']['orderItemList']['orderItem']);
        }
        foreach ($detailResult['orderInfo']['orderItemList']['orderItem'] as $goods) {
            $orderRow['goods'][] = array('shop_goods_name' => $goods['productCName'],
                                         'shop_goods_id' => $goods['productId'],
                                         'shop_sku_id' => $goods['productId'],
                                         'goods_sn' => $goods['outerId'],
                                         'price' => $goods['orderItemPrice'],
                                         'number' => $goods['orderItemNum'],
                                         'discount_price' => 0,
                                        );
            $goodsAmount +=  $goods['orderItemPrice'] * $goods['orderItemNum'];
        }
        
        if ($orderRow['discount_amount'] > 0) {
            $totalDiscount = 0;
            for ($i = 0; $i < count($orderRow['goods']); $i++) {
                if ($i == (count($orderRow['goods']) - 1)) {
                    $orderRow['goods'][$i]['discount_price'] = $orderRow['discount_amount'] - $totalDiscount;
                }
                else {
                    $orderRow['goods'][$i]['discount_price'] = round($orderRow['goods'][$i]['price'] * $orderRow['goods'][$i]['number'] / $goodsAmount * $orderRow['discount_amount'], 2);
                    $totalDiscount += $orderRow['goods'][$i]['discount_price'];
                }
            }
        }
        else    $orderRow['discount_amount'] = 0;
        
        return $orderRow;
    }
    
    /**
     * 获得订单状态
     *
     * @return  void
     */
    public function getOrderStatus($externalOrderSN) {
        $paramArray = $this -> getParams();
	    $paramArray['method'] = "yhd.order.detail.get";
	    $paramArray['orderCode'] = $externalOrderSN;
	    $result = new Zend_Config_Xml($this -> _api -> sendByPost($this -> _url, $paramArray, array(), $this -> _config['secret']));
	    $result = $result -> toArray();
	    if ($result['errorCount']) {
	        var_dump($result);exit;
	    }
        
	    return $this -> mapOrderStatus($result['orderInfo']['orderDetail']['orderStatus']);
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
        
        $paramArray = $this -> getParams();
        $paramArray['method'] = "yhd.products.stock.update";
        foreach ($stockData as $stock) {
            $number = $stock['number'] > 0 ? $stock['number'] : 0;
            $paramArray['productStockList'] = $stock['goods_id'].'::'.$number;
    	    $detailResult = new Zend_Config_Xml($this -> _api -> sendByPost($this -> _url, $paramArray, array(), $this -> _config['secret']));
    	    $detailResult = $detailResult -> toArray();
    	    if ($detailResult['errorCount']) {
    	        var_dump($detailResult);exit;
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
        
        $paramArray = $this -> getParams();
	    $paramArray['method'] = "yhd.logistics.order.shipments.update";
	    $paramArray['orderCode'] = $shopOrderSN;
	    $paramArray['deliverySupplierId'] = $this -> mapShopLogistic($logistic['logistic_code']);
	    $paramArray['expressNbr'] = $logistic['logistic_no'];
	    $result = new Zend_Config_Xml($this -> _api -> sendByPost($this -> _url, $paramArray, array(), $this -> _config['secret']));
	    $result = $result -> toArray();
	    if ($result['errorCount']) {
	        var_dump($result);exit;
	    }
        
        return true;
    }
    
    /**
     * 匹配订单状态
     *
     * @param   string  $status
     * @return  void
     */
    private function mapOrderStatus($status) {
        if ( $status == 'ORDER_WAIT_PAY' )              return 1;
        if ( $status == 'ORDER_PAYED' )                 return 2;
        if ( $status == 'ORDER_TRUNED_TO_DO' )          return 2;
        if ( $status == 'ORDER_CAN_OUT_OF_WH' )         return 2;
        if ( $status == 'ORDER_OUT_OF_WH' )             return 3;
        if ( $status == 'ORDER_SENDED_TO_LOGITSIC' )    return 3;
        if ( $status == 'ORDER_RECEIVED' )              return 10;
        if ( $status == 'ORDER_FINISH' )                return 10;
        if ( $status == 'ORDER_CUSTOM_CALLTO_RETURN' )  return 12;
        if ( $status == 'ORDER_CUSTOM_CALLTO_CHANGE' )  return 12;
        if ( $status == 'ORDER_RETURNED' )              return 10;
        if ( $status == 'ORDER_CHANGE_FINISHED' )       return 10;
        if ( $status == 'ORDER_CANCEL' )                return 11;
    }
    
    /**
     * 匹配收货地址
     *
     * @param   string  $status
     * @return  void
     */
    private function mapAddress($province, $city, $area) {
        if ( $this -> _area[1][$province] ) {
            $result['province'] = $province;
            $result['province_id'] = $this -> _area[1][$province];
        }
        else    return false;
        
        $city = str_replace('市区', '市', $city);
        if ( $result['province_id'] == 2 || $result['province_id'] == 10 ) {
            $temp = $this -> getSpecialCity($result['province_id']);
            $result['city'] = $temp[1];
            $result['city_id'] = $temp[0];
            //$area = $city;
        }
        else {
            if ( $this -> _area[$result['province_id']][$city] ) {
                $result['city'] = $city;
                $result['city_id'] = $this -> _area[$result['province_id']][$city];
            }
            else {
                if ( strpos($city, '市') === false ) {
                    $city .= '市';
                    if ( $this -> _area[$result['province_id']][$city] ) {
                        $result['city'] = $city;
                        $result['city_id'] = $this -> _area[$result['province_id']][$city];
                    }
                    else return false;
                }
                else    return false;
            }
        }

        if (strpos($area, '(') !== false) {
            $area = substr($area, 0, strpos($area, '('));
        }
        if ( $this -> _area[$result['city_id']][$area] ) {
            $result['area'] = $area;
            $result['area_id'] = $this -> _area[$result['city_id']][$area];
        }
        else {
            if ( $this -> _area[$result['province_id']][$area] ) {
                $result['city'] = $area;
                $result['city_id'] = $this -> _area[$result['province_id']][$area];
                if ( $this -> _area[$result['city_id']][$area] ) {
                    $result['area'] = $area;
                    $result['area_id'] = $this -> _area[$result['city_id']][$area];
                }
                //else    return false;
            }
            else {
                $found = false;
                foreach ( $this -> _area[$result['city_id']] as $key => $value ) {
                    if ( $key == $city ) {
                        $result['area'] = $key;
                        $result['area_id'] = $value;
                        $found = true;
                        break;
                    }
                }
                //if ( !$found )  return false;
            }
        }
        
        return $result;
    }
    
    /**
     * 匹配物流公司
     *
     * @param   string  $logisticID
     * @return  void
     */
    private function mapLogistic($logisticID) {
        if ( $logisticID == '1757' )    return 'st';
        if ( $logisticID == '7594' )    return 'ht';
        if ( $logisticID == '8084' )    return 'yms';
        if ( $logisticID == '1759' )    return 'ems';
        if ( $logisticID == '1755' )    return 'yt';
        if ( $logisticID == '1751' )    return 'yt';
        if ( $logisticID == '1756' )    return 'sf';
        
        return false;
    }
    
    /**
     * 匹配店铺物流公司
     *
     * @param   string  $logisticCode
     * @return  void
     */
    private function mapShopLogistic($logisticCode) {
        if ( $logisticCode == 'st' )      return '1757';
        if ( $logisticCode == 'ht' )      return '7594';
        if ( $logisticCode == 'yms' )     return '8084';
        if ( $logisticCode == 'ems' )     return '1759';
        if ( $logisticCode == 'yt' )      return '1755';
        if ( $logisticCode == 'sf' )      return '1756';
        
        return false;
    }
}