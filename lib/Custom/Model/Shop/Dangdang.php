<?php
class Custom_Model_Shop_Dangdang extends Custom_Model_Shop_Base
{
	private $_pageSize = 20;
	private $_baseURL = 'http://api.open.dangdang.com/openapi/rest?v=1.0';
	private $_goodsMethod = 'dangdang.items.list.get';
	private $_orderMethod = 'dangdang.orders.list.get';
	private $_orderDetailMethod = 'dangdang.order.details.get';
	private $_stockMethod = 'dangdang.item.stock.update';
	
	/**
     * 构造函数
     *
     * @param   void
     * @return  void
     */
	public function __construct($config)
	{
		parent::__construct($config);
	}
	
	/**
     * 生成店铺类型特殊字段
     *
     * @param   int     $shopID
     * @return  void
     */
	public function getConfigField() {
	    return array('session' => '会话ID',
	                 'id' => '账号',
	                 'key' => '密钥',
	                );
	}
	
	/**
     * 同步商品
     *
     * @return  void
     */
    public function syncGoods() {
        $this -> initSyncLog();

        $output = new Zend_Config_Xml($this -> curl_post($this -> _goodsMethod, "p=1"));
        $array = $output -> toArray();
        $count = $array['totalInfo']['itemsCount'];
        $totalPage = $array['totalInfo']['pageTotal'];
        if ( $count == 0 ) {
            $this -> _log .= "没有商品\n";
            return false;
        }
        
        $currentPage = 1;
        do {
            foreach ($array['ItemsList']['ItemInfo'] as $goods) {
                unset($goodsRow);
                $goodsRow['shop_goods_id'] = $goods['itemID'];
			    $goodsRow['shop_goods_name'] = $goods['itemName'];
			    $goodsRow['onsale'] = $goods['itemState'] == '可销售' ? 0 : 1;
	            $goodsRow['goods_sn'] = $goods['outerItemID'];
				$goodsRow['shop_price'] = $goods['unitPrice'];
				$goodsRow['stock_number'] = $goods['stockCount'];
	            
	            $result[] = $goodsRow;
            }

            if ($currentPage >= $totalPage) {
                break;
            }
            $currentPage++;
            
            $content = $this -> curl_post($this -> _goodsMethod, "p={$currentPage}");
            if ($content) {
                $output = new Zend_Config_Xml($content);
                $array = $output -> toArray();
            }
            else {
                $array['ItemsList']['ItemInfo'] = array();
            }
        }
        while (1);

        return $result;
    }
    
    /**
     * 同步订单
     *
     * @return  void
     */
    public function syncOrder($area, $startDate, $endDate, $shopGoods = null) {
        parent::syncOrder($area, $startDate, $endDate, $goods);
        
        $this -> initSyncLog();
        
        $currentPage = 1;
        $output = new Zend_Config_Xml($this -> curl_post($this -> _orderMethod, "os=9999&osd={$this -> _orderStartTime}&oed={$this -> _orderEndTime}&p={$currentPage}"));
        $array = $output -> toArray();
        if ($array['totalInfo']['orderCount'] <= 0) {
            return false;
        }
        
        $totalPage = $array['totalInfo']['pageTotal'];
        do {
            if (!$array['OrdersList']['OrderInfo'][0]) {
                $array['OrdersList']['OrderInfo'] = array($array['OrdersList']['OrderInfo']);
            }
            foreach ($array['OrdersList']['OrderInfo'] as $order) {
                unset($orderRow);
                $orderRow['order_time'] = strtotime($order['orderTimeStart']);
                $orderRow['amount'] = $order['orderMoney'];
                $output = new Zend_Config_Xml($this -> curl_post($this -> _orderDetailMethod, "o={$order['orderID']}"));
                $order = $output -> toArray();
                $orderRow['external_order_sn'] = $order['orderID'];
                $orderRow['status'] = $this -> mapOrderStatus($order['orderState']);
                $orderRow['is_cod'] = $order['buyerInfo']['buyerPayMode'] == '货到付款' ? 1 : 0;
                if ($orderRow['status'] == 1 && !$orderRow['is_cod'])   continue;
                $orderRow['discount_amount'] = $order['buyerInfo']['promoDeductAmount'];
                $orderRow['goods_amount'] = $orderRow['amount'] - $order['buyerInfo']['postage'];
                $orderRow['pay_amount'] = $orderRow['amount'];
                $orderRow['freight'] = $order['buyerInfo']['postage'];
                $orderRow['pay_time'] = $order['paymentDate'] ? strtotime($order['paymentDate']) : 0;
                $orderRow['pay_name'] = '';
                $orderRow['addr_consignee'] = $order['sendGoodsInfo']['consigneeName'];
                $orderRow['addr_address'] = $order['sendGoodsInfo']['consigneeAddr'];
                $orderRow['addr_tel'] = $order['sendGoodsInfo']['consigneeTel'];
                $orderRow['addr_mobile'] = $order['sendGoodsInfo']['consigneeMobileTel'];
                $orderRow['memo'] = $order['remark'].' '.$order['message'];
                $orderRow['addr_org_address'] = $order['sendGoodsInfo']['consigneeAddr'];
                $addressInfo = $this -> mapAddress($orderRow['addr_address']);
                if ( $addressInfo ) {
                    $orderRow['addr_province'] = $addressInfo['province'];
                    $orderRow['addr_city'] = $addressInfo['city'];
                    $orderRow['addr_area'] = $addressInfo['area'];
                    $orderRow['addr_province_id'] = $addressInfo['province_id'] ? $addressInfo['province_id'] : 0;
                    $orderRow['addr_city_id'] = $addressInfo['city_id'] ? $addressInfo['city_id'] : 0;
                    $orderRow['addr_area_id'] = $addressInfo['area_id'] ? $addressInfo['area_id'] : 0;
                    $orderRow['addr_address'] = $addressInfo['address'];
                }
                else {
                    $this -> _log .= "订单号{$orderRow['external_order_sn']}地址匹配错误: {$orderRow['addr_address']}\n";
                    //continue;
                }
                $orderRow['invoice'] = $order['receiptInfo']['receiptName'];
                $orderRow['invoice_content'] = $order['receiptInfo']['receiptDetails'];
                
                $goodsAmount = 0;
                if (!$order['ItemsList']['ItemInfo'][0]) {
                    $order['ItemsList']['ItemInfo'] = array($order['ItemsList']['ItemInfo']);
                }
                foreach ($order['ItemsList']['ItemInfo'] as $goods) {
                    $orderRow['goods'][] = array('shop_goods_name' => $goods['itemName'],
                                                 'shop_goods_id' => $goods['itemID'],
                                                 'shop_sku_id' => $goods['itemID'],
                                                 'goods_sn' => $goods['outerItemID'],
                                                 'price' => $goods['unitPrice'],
                                                 'number' => $goods['orderCount'],
                                                 'discount_price' => 0,
                                                );
                    $goodsAmount +=  $goods['unitPrice'] * $goods['orderCount'];
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
                
                $result[] = $orderRow;
            }
            
            if ($currentPage >= $totalPage) {
                break;
            }
            $currentPage++;
            $output = new Zend_Config_Xml($this -> curl_post($this -> _orderDetailMethod, "p={$currentPage}"));
            $array = $output -> toArray();
        }
        while (1);
        
        return $result;
    }
    
    /**
     * 获得订单状态
     *
     * @return  void
     */
    public function getOrderStatus($externalOrderSN) {
        $output = new Zend_Config_Xml($this -> curl_post($this -> _orderDetailMethod, "o={$order['externalOrderSN']}"));
        $order = $output -> toArray();
        
	    return $this -> mapOrderStatus($order['orderState']);
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
        
        foreach ($stockData as $stock) {
            $number = $stock['number'] > 0 ? $stock['number'] : 0;
            $output = new Zend_Config_Xml($this -> curl_post($this -> _stockMethod, "oit={$stock['goods_sn']}&stk={$number}"));
            $array = $output -> toArray();
            if ($array['Result']['operCode'] != '0') {
        	    $this -> _log .= $array['Result']['operation'];
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
        
        return true;
    }
    
    /**
     * 匹配订单状态
     *
     * @param   string  $status
     * @return  void
     */
    private function mapOrderStatus($status) {
        if ( $status == 100 )   return 1;
        if ( $status == 101 )   return 2;
        if ( $status == 300 )   return 3;
        if ( $status == 400 )   return 10;
        if ( $status == 1000 )  return 10;
        if ( $status == -100 )  return 11;
        if ( $status == 1100 )  return 11;
        if ( $status == -200 )  return 12;
        if ( $status == 50 )    return 12;
        if ( $status == 102 )   return 2;
    }
    
    /**
     * 匹配收货地址
     *
     * @param   string  $status
     * @return  void
     */
    private function mapAddress($address) 
    {
        $addressArray = explode('，', $address);
        $province = $addressArray[1];
        $city = $addressArray[2];
        $area = preg_replace('/\((.*)\)/', '', $addressArray[3]);
        $result['address'] = $addressArray[4];
        if ($this -> _area[1][$province]) {
            $result['province'] = $province;
            $result['province_id'] = $this -> _area[1][$province];
        }
        else    return false;
        
        if ($this -> _area[$result['province_id']][$city]) {
            $result['city'] = $city;
            $result['city_id'] = $this -> _area[$result['province_id']][$city];
        }
        
        if ($this -> _area[$result['city_id']][$area]) {
            $result['area'] = $area;
            $result['area_id'] = $this -> _area[$result['city_id']][$area];
            if (substr($result['address'], 0, strlen($area)) == $area) {
                $result['address'] = substr($result['address'], strlen($area), strlen($result['address']));
            }
        }
        else {
            $result['area_id'] = 0;
        }
        
        return $result;
    }
    
    private function get_md5_string($url, $params) {
        $arr = explode('?', $url);
        $param = $arr[1];
        $arr = explode('&', $param);
        foreach($arr as $v) { 
            $tmp = explode('=',$v);
            $request_arr[$tmp[0]] = $tmp[1];
        }
        ksort($request_arr);
        $validateString_new = '';
        foreach($request_arr as $k=>$v) {
            $validateString_new .= $k.mb_convert_encoding(trim($v),'gbk','gbk');
        }
        $api_key = mb_convert_encoding($this -> _config['key'], 'gbk', 'gbk');
        $sign = strtoupper(md5($api_key.$validateString_new.$api_key));
        $url .= '&sign='.$sign."&{$params}";
        
        return $url;
    }
    
    private function curl_get($method, $params) {
        $url = $this -> get_md5_string($this -> _baseURL."&method={$method}&timestamp=".date('Y-m-d H:i:s')."&format=xml&app_key={$this -> _config['id']}&sign_method=md5&session={$this -> _config['session']}", $params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
	
	private function curl_post($method, $params, $file = null) {
	    $url = $this -> get_md5_string($this -> _baseURL."&method={$method}&timestamp=".date('Y-m-d H:i:s')."&format=xml&app_key={$this -> _config['id']}&sign_method=md5&session={$this -> _config['session']}", $params);
	    $urls = explode('?' ,$url);
	    $url = $urls[0];
	    $params = $urls[1];
	    $params = explode('&', $params);
	    $data = array();
	    foreach($params as $value) {
	        $tmp = explode('=', $value);
	        $data[$tmp[0]] = $tmp[1];
	    }
	    if ($file) {
	        $file_name = substr($url ,(strrpos($url ,'/') + 1), (strpos($urls,'\.php') - 4));
	        $data[$file_name] = '@'.realpath($file).';type=text/xml';
	    }
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_POST, 1 );
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    return mb_convert_encoding($data,'gbk','utf8');
	}
}