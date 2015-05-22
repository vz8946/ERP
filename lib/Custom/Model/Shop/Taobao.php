<?php
class Custom_Model_Shop_Taobao extends Custom_Model_Shop_Base
{
	private $_pageSize = 20;
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
		
		$this -> _api = new Custom_Model_Taoapi();
        $this -> _api -> ApiConfig -> AppKey = array($this -> _config['id'] => $this -> _config['key']);
        $this -> _api -> session = $this -> _config['session'];
	}
	
	/**
     * 生成店铺类型特殊字段
     *
     * @param   int     $shopID
     * @return  void
     */
	public function getConfigField() {
	    return array('type' => '店铺类型',
	                 'session' => '会话ID',
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
        
        $result1 = $this -> doSyncGoods('taobao.items.onsale.get');
        $result2 = $this -> doSyncGoods('taobao.items.inventory.get');
        
        return array_merge($result1, $result2);
    }
	
    private function doSyncGoods($method) {
        $this -> _api -> method = $method;
        $this -> _api -> fields  = 'iid,num_iid,title,price,num,approve_status,sku';
        
        $currentPage = 1;
		$this -> _api -> page_no = $currentPage;
		$this -> _api -> page_size = $this -> _pageSize;
		$this -> _api -> setVersion(2);
		$goodsArray = $this -> _api -> Send('get','xml') -> getArrayData();
		$error = $this -> _api -> getErrorInfo();
		if ( $error ) {
		    $this -> _log .= $error;
		    return false;
		}
		
		$total = $goodsArray['total_results'];
		if ( $total == 0 ) {
		    $this -> _log .= "没有商品记录\n";
		    return true;
		}
		$totalPage = ceil($total / $this -> _pageSize);
        
        do {
            if (!$goodsArray['items']['item']) {
                break;
            }
            
            if ( $goodsArray['items']['item']['num_iid']) {
                $goodsArray['items']['item'] = array($goodsArray['items']['item']);
            }
            foreach( $goodsArray['items']['item'] as $goods ) {
                $this -> _api -> method = 'taobao.item.get';
				$this -> _api -> fields = 'iid,cid,detail_url,num_iid,title,num,property_alias,sku,approve_status,outer_id,cid';
				$this -> _api -> num_iid = $goods['num_iid'];
				$goodsInfo = $this -> _api -> Send('get','xml') -> getArrayData();
				unset($goodsRow);
				$goodsRow['shop_id'] = $this -> _shopID;
			    $goodsRow['shop_goods_id'] = $goods['num_iid'];
			    $goodsRow['shop_goods_name'] = $goods['title'];
			    $goodsRow['onsale'] = $goods['approve_status'] == 'onsale' ? 0 : 1;
				if ($goodsInfo['item']['skus']['sku']) {
				    if ( !$goodsInfo['item']['skus']['sku']['0'] ) {
				        $goodsInfo['item']['skus']['sku'] = array($goodsInfo['item']['skus']['sku']);
				    }
				    
				    foreach ( $goodsInfo['item']['skus']['sku'] as $sku ) {
				        $goodsRow['goods_sn'] = $sku['outer_id'];
				        $goodsRow['shop_price'] = $sku['price'];
				        $goodsRow['stock_number'] = $sku['quantity'];
				        $goodsRow['shop_sku_id'] = $sku['sku_id'];
				    }
				}
				else {
				    if ( is_array($goodsInfo['item']['outer_id']) ) {
				        $goodsSN = '';
				        foreach ($goodsInfo['item']['outer_id'] as $outer_id) {
				            $goodsSN .= $outer_id;
				        }
				    }
				    else    $goodsSN = $goodsInfo['item']['outer_id'];
				    $goodsRow['goods_sn'] = $goodsSN;
				    $goodsRow['shop_price'] = $goods['price'];
				    $goodsRow['stock_number'] = $goods['num'];
				    $goodsRow['shop_sku_id'] = 0;
				}
				$goodsRow['cid'] = $goodsInfo['item']['cid'];
				$result[] = $goodsRow;
            }
            
            $currentPage++;
            if ( $currentPage > $totalPage )    break;
            
            $this -> _api -> method = $method;
            $this -> _api -> fields  = 'iid,num_iid,title,price,num,approve_status';
            $this -> _api -> page_no = $currentPage;
		    $this -> _api -> page_size = $this -> _pageSize;
		    $this -> _api -> setVersion(2);
            $goodsArray = $this -> _api -> Send('get','xml') -> getArrayData();
            $error = $this -> _api -> getErrorInfo();
    		if ( $error ) {
    		    $this -> _log .= $error;
    		    return false;
    		}
        }
        while (1);
        
        if ($result) {
            $cidArray = array();
            foreach ($result as $goods) {
                if (!in_array($goods['cid'], $cidArray)) {
                    $cidArray[] = $goods['cid'];
                }
            }
            
            $categoryData = $this -> getCategory($cidArray);
            foreach ($result as $index => $goods) {
                $result[$index]['category'] = $categoryData[$goods['cid']];
                unset($result[$index]['cid']);
            } 
        }

        return $result;
    }
    
    /**
     * 获得商品分类
     *
     * @return  void
     */
    public function getCategory($cidArray) {
        $this -> _api -> method = 'taobao.itemcats.get';
        $this -> _api -> fields  = 'cid,parent_cid,name,is_parent';
        $this -> _api -> cids = implode(',', $cidArray);
        $datas = $this -> _api -> Send('get','xml') -> getArrayData();
        foreach ($datas['item_cats']['item_cat'] as $category) {
            $result[$category['cid']] = $category['name'];
        }
        
        return $result;
    }
    
    /**
     * 获得评论
     *
     * @return  void
     */
    public function getComment($goodsID) {
        $this -> _api -> method = 'taobao.traderates.get';
        $this -> _api -> fields = 'tid,oid,role,nick,result,created,rated_nick,item_title,item_price,content,reply,num_iid';
        $this -> _api -> rate_type = 'get';
        $this -> _api -> role = 'buyer';
        $this -> _api -> num_iid = $goodsID;
        $this -> _api -> page_no = 1;
        $this -> _api -> page_size = 150;
        $datas = $this -> _api -> Send('get','xml') -> getArrayData();
        if (!$datas['trade_rates']['trade_rate'])   return false;
        
        if (!$datas['trade_rates']['trade_rate'][0]) {
            $datas['trade_rates']['trade_rate'] = array($datas['trade_rates']['trade_rate']);
        }
        foreach ($datas['trade_rates']['trade_rate'] as $comment) {
            if ($omment['result'] == 'good' || $comment['content'] == '好评！')    continue;
            $result[] = array('content' => $comment['content'],
                              'add_time' => strtotime($comment['created']),
                              'user_name' => $comment['nick'],
                              'ip' => $comment['tid'],
                             );
        }
        
        return $result;
    }
    
    /**
     * 同步订单
     *
     * @return  void
     */
    public function syncOrder($area, $startDate, $endDate, $flag = 1) {
        parent::syncOrder($area, $startDate, $endDate);
        
        $this -> initSyncLog();
        //$flag = 2;
        if ($flag == 1) {
            $this -> _api -> method = 'taobao.trades.sold.get';
            $this -> _api -> start_created = $this -> _orderStartTime;
		    $this -> _api -> end_created = $this -> _orderEndTime;
        }
        else {
            $this -> _api -> method = 'taobao.trades.sold.increment.get';
            $this -> _api -> start_modified = date('Y-m-d H:i:s', time() - 1800);
		    $this -> _api -> end_modified = date('Y-m-d H:i:s', time());
        }
        
        $currentPage = 1;
		$this -> _api -> page_no = $currentPage;
		$this -> _api -> page_size = $this -> _pageSize;
		$this -> _api -> fields =  'seller_nick,buyer_nick,title,type,created,sid,tid,seller_rate,buyer_rate,status,payment,discount_fee,adjust_fee,post_fee,total_fee,pay_time,end_time,modified,consign_time,buyer_obtain_point_fee,point_fee,real_point_fee,received_payment,commission_fee,pic_path,num_iid,num_iid,num,price,cod_fee,cod_status,shipping_type,receiver_name,receiver_state,receiver_city,receiver_district,receiver_address,receiver_zip,receiver_mobile,receiver_phone,orders.title,orders.pic_path,orders.price,orders.num,orders.iid,orders.num_iid,orders.sku_id,orders.refund_status,orders.status,orders.oid,orders.total_fee,orders.payment,orders.discount_fee,orders.adjust_fee,orders.sku_properties_name,orders.item_meal_name,orders.buyer_rate,orders.seller_rate,orders.outer_iid,orders.outer_sku_id,orders.refund_id,orders.seller_type,alipay_no';
		
		$this -> _api -> setVersion(2);
		$orderArray = $this -> _api -> Send('get','xml') -> getArrayData();
		
		$error = $this -> _api -> getErrorInfo();
		if ( $error ) {
		    $this -> _log .= $error;
		    return false;
		}
		
		$total = $orderArray['total_results'];
		if ( $total == 0 ) {
		    $this -> _log .= "没有订单记录\n";
		    return true;
		}
		$totalPage = ceil($total / $this -> _pageSize);

        do {
            if ( !$orderArray['trades']['trade'][0] ) {
                $orderArray['trades']['trade'] = array($orderArray['trades']['trade']);
            }
            foreach( $orderArray['trades']['trade'] as $order ) {
                unset($orderRow);unset($orderGoods);
                $orderRow['shop_id'] = $this -> _shopID;
                $orderRow['external_order_sn'] = $order['tid'];
                $orderRow['status'] = $this -> mapOrderStatus($order['status']);
                $orderRow['pay_amount'] = $order['payment'];
                $orderRow['freight'] = $order['post_fee'];
                $orderRow['order_time'] = strtotime($order['created']);
                $orderRow['pay_time'] = strtotime($order['pay_time']);
                $orderRow['payment_no'] = $order['alipay_no'];
                $orderRow['invoice'] = $order['invoice_name'];
                $orderRow['addr_consignee'] = $order['receiver_name'];
                $orderRow['addr_address'] = $order['receiver_address'];
                $orderRow['addr_zip'] = $order['receiver_zip'];
                if (is_array($order['receiver_phone'])) {
                    $orderRow['addr_tel'] = implode(',', $order['receiver_phone']);
                }
                else    $orderRow['addr_tel'] = $order['receiver_phone'];
                if (is_array($order['receiver_mobile'])) {
                    $orderRow['addr_mobile'] = implode(',', $order['receiver_mobile']);
                }
                else    $orderRow['addr_mobile'] = $order['receiver_mobile'];
                $orderRow['addr_org_address'] = $order['receiver_state'].$order['receiver_city'].$order['receiver_district'].$order['receiver_address'];
                $addressInfo = $this -> mapAddress($order['receiver_state'], $order['receiver_city'], $order['receiver_district']);
                if ($addressInfo) {
                    $orderRow['addr_province'] = $addressInfo['province'];
                    $orderRow['addr_city'] = $addressInfo['city'];
                    $orderRow['addr_area'] = $addressInfo['area'];
                    $orderRow['addr_province_id'] = $addressInfo['province_id'] ? $addressInfo['province_id'] : 0;
                    $orderRow['addr_city_id'] = $addressInfo['city_id'] ? $addressInfo['city_id'] : 0;
                    $orderRow['addr_area_id'] = $addressInfo['area_id'] ? $addressInfo['area_id'] : 0;
                }
                else {
                    $this -> _log .= "订单号{$order['tid']}地址匹配错误: {$order['receiver_state']} {$order['receiver_city']} {$order['receiver_district']}\n";
                    //$orderRow['addr_address'] = $order['receiver_state'].$order['receiver_city'].$order['receiver_district'].$orderRow['addr_address'];
                    //continue;
                }
                if ( !$orderRow['addr_province_id'] || !$orderRow['addr_city_id'] || !$orderRow['addr_area_id'] ) {
                    $orderRow['addr_address'] = $order['receiver_state'].$order['receiver_city'].$order['receiver_district'].$orderRow['addr_address'];
                }
                
                if ( !$order['orders']['order'][0] ) {
                    $order['orders']['order'] = array($order['orders']['order']);
                }
                foreach( $order['orders']['order'] as $goods ) {
                    $orderRow['goods'][] = array('shop_id' => $this -> _shopID,
                                                 'shop_goods_name' => $goods['title'],
                                                 'shop_goods_id' => $goods['num_iid'],
                                                 'shop_sku_id' => $goods['sku_id'],
                                                 'goods_sn' => $goods['outer_iid'],
                                                 'price' => $goods['price'],
                                                 'number' => $goods['num'],
                                                 'discount_price' => $goods['discount_fee'] - $goods['adjust_fee'],
                                                );
                    $orderRow['goods_amount'] += $goods['price'] * $goods['num'] - $goods['discount_fee'] + $goods['adjust_fee'];
                    $orderRow['discount_amount'] += $goods['discount_fee'] - $goods['adjust_fee'];
                }
                $orderRow['amount'] = $orderRow['goods_amount'] + $order['post_fee'];
                
                if ($orderRow['pay_amount'] < $orderRow['amount']) {
                    $totalPrice = 0;
                    foreach ($orderRow['goods'] as $index => $goods) {
                        if (($index + 1) == count($orderRow['goods'])) {
                            $price = $orderRow['amount'] - $orderRow['pay_amount'] - $totalPrice;
                        }
                        else {
                            $price = round(($goods['price'] * $goods['number'] - $goods['discount_price']) / $orderRow['goods_amount'] * ($orderRow['amount'] - $orderRow['pay_amount']), 2);
                        }
                        $totalPrice += $price;
                        
                        $orderRow['goods'][$index]['discount_price'] += $price;
                    }
                    $orderRow['amount'] = $orderRow['pay_amount'];
                    $orderRow['goods_amount'] -= $totalPrice;
                }
                
                $result[] = $orderRow;
            }
            
            $currentPage++;
            if ( $currentPage > $totalPage )    break;
            
            $this -> _api -> page_no = $currentPage;
            $orderArray = $this -> _api -> Send('get','xml') -> getArrayData();
            
            $error = $this -> _api -> getErrorInfo();
    		if ( $error ) {
    		    $this -> _log .= $error;
    		    return false;
    		}
        }
        while (1);
        
        if ( $result ) {
            for ( $i = 0; $i < count($result); $i++ ) {
                //if ( $result[$i]['status'] == 1 || $result[$i]['status'] == 2) {
                if ($result[$i]['status'] == 2) {
                    $this -> _api -> method = 'taobao.trade.fullinfo.get';
                    $this -> _api -> fields = "seller_memo,buyer_memo,buyer_message";
                    $this -> _api -> tid = $result[$i]['external_order_sn'];
                    $memoArray = $this -> _api -> Send('get','xml') -> getArrayData();
                    
                    if ( is_array($memoArray['trade']['seller_memo']) ) {
                        foreach ( $memoArray['trade']['seller_memo'] as $seller_memo ) {
                            $result[$i]['memo'] .= $seller_memo.'　';
                        }
                    }
                    else    $result[$i]['memo'] .= $memoArray['trade']['seller_memo'].'　';
                    if ( is_array($memoArray['trade']['buyer_message']) ) {
                        foreach ( $memoArray['trade']['buyer_message'] as $buyer_message ) {
                            $result[$i]['memo'] .= $buyer_message.'　';
                        }
                    }
                    else    $result[$i]['memo'] .= $memoArray['trade']['buyer_message'];
                }
            }
        }
        
        return $result;
    }
    
    /**
     * 获得订单状态
     *
     * @return  void
     */
    public function getOrderStatus($externalOrderSN) {
        $this -> _api -> method = 'taobao.trade.get';
        $this -> _api -> fields = 'orders.status,orders.refund_status';
        $this -> _api -> tid = $externalOrderSN;
        $orderArray = $this -> _api -> Send('get','xml') -> getArrayData();
        $error = $this -> _api -> getErrorInfo();
        if ( $error ) {
    	    $this -> _log .= $error;
    	    return false;
        }
        
        if (!$orderArray['trade']['orders']['order'][0]) {
            $order = $orderArray['trade']['orders']['order'];
            unset($orderArray['trade']['orders']['order']);
            $orderArray['trade']['orders']['order'][] = $order;
        }
        foreach ($orderArray['trade']['orders']['order'] as $order) {
            if ($order['refund_status'] != 'NO_REFUND') {
                return 13;
            }
        }
        
        return $this -> mapOrderStatus($orderArray['trade']['orders']['order'][0]['status']);
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
        
        $this -> _api -> method = 'taobao.item.quantity.update';
        $this -> _api -> type = 1;
        foreach ($stockData as $stock) {
            $this -> _api -> num_iid = $stock['goods_id'];
            //$this -> _api -> outer_id = $stock['goods_sn'];
            $this -> _api -> quantity = $stock['number'] > 0 ? $stock['number'] : '0';
            $this -> _api -> Send('get','xml') -> getArrayData();
            $error = $this -> _api -> getErrorInfo();
            if ( $error ) {
        	    //$this -> _log .= $error;
        	    //return false;
        	    continue;
            }
        }
    }
    
    /**
     * 同步物流信息
     *
     * @param   array   $externalOrderSNArray
     * @return  void
     */
    public function syncOrderLogistic($externalOrderSNArray) {
        $result = array();
        
        if ( !$externalOrderSNArray || count($externalOrderSNArray) == 0 )  return $result;
        
        $this -> _api -> method = 'taobao.logistics.orders.get';
		$this -> _api -> fields =  'company_name,out_sid,created';
		$this -> _api -> setVersion(2);
		
		foreach ( $externalOrderSNArray as $externalOrderSN ) {
		    $this -> _api -> tid = $externalOrderSN;
		    
		    $logisticArray = $this -> _api -> Send('get','xml') -> getArrayData();
            $error = $this -> _api -> getErrorInfo();
    		if ( $error ) {
    		    //$this -> _log .= $error;
    		    //return false;
    		    $result[$externalOrderSN] = array('logistic_code' => 'external',
    		                                      'logistic_no' => '000000',
    		                                      'logistic_time' => 0,
    		                                     );
    		    continue;
    		}
    		
    		$this -> _log .= "订单{$externalOrderSN}同步物流信息成功\n";
    		
    		$logistic_code = $this -> mapLogistic($logisticArray['shippings']['shipping']['company_name']);
    		if ( !$logistic_code ) {
    		    $this -> _log .= "订单{$externalOrderSN}物流公司{$logisticArray['shippings']['shipping']['company_name']}匹配不到\n";
    		    
    		    $logistic_code = 'external';
    		    //continue;
    		}
    		
    		$result[$externalOrderSN] = array('logistic_code' => $logistic_code,
    		                                  'logistic_no' => $logisticArray['shippings']['shipping']['out_sid'],
    		                                  'logistic_time' => strtotime($logisticArray['shippings']['shipping']['created']),
    		                                 );
		}
        
        return $result;
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
        
        $code = $this -> _logistic[$logistic['logistic_code']];
        if ( !$code ) {
            $this -> _log .= "订单{$shopOrderSN}找不到对应的物流公司，不能同步店铺发货\n";
            return false;
        }
        
        $this -> _api -> method = 'taobao.logistics.offline.send';
		$this -> _api -> setVersion(2);
		$this -> _api -> tid = $shopOrderSN;
		$this -> _api -> out_sid = $logistic['logistic_no'];
		$this -> _api -> company_code = $code;
		
		$this -> _api -> Send('get','xml');
		$error = $this -> _api -> getErrorInfo();
    	if ( $error ) {
    	    $this -> _log .= $error;
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
        if ( $status == 'TRADE_NO_CREATE_PAY' )         return 1;
        if ( $status == 'WAIT_BUYER_PAY' )              return 1;
        if ( $status == 'WAIT_SELLER_SEND_GOODS' )      return 2;
        if ( $status == 'WAIT_BUYER_CONFIRM_GOODS' )    return 3;
        if ( $status == 'TRADE_BUYER_SIGNED' )          return 10;
        if ( $status == 'TRADE_FINISHED' )              return 10;
        if ( $status == 'TRADE_CLOSED' )                return 11;
        if ( $status == 'TRADE_CLOSED_BY_TAOBAO' )      return 11;
    }
    
    /**
     * 匹配收货地址
     *
     * @param   string  $status
     * @return  void
     */
    public function mapAddress($province, $city, $area) {
        if ($province == '广西壮族自治区') $province = '广西';
        if ($province == '新疆维吾尔自治区') $province = '新疆';
        if ($province == '宁夏回族自治区') $province = '宁夏';
        $province = str_replace('省', '', $province);
        $province = str_replace('自治区', '', $province);

        if ( $this -> _area[1][$province] ) {
            $result['province'] = $province;
            $result['province_id'] = $this -> _area[1][$province];
        }
        else    return false;
        
        if ( $this -> _area[$result['province_id']][$city] ) {
            $result['city'] = $city;
            $result['city_id'] = $this -> _area[$result['province_id']][$city];
        }
        else {
            $found = false;
            foreach ( $this -> _area[$result['province_id']] as $key1 => $value1 ) {
                foreach ( $this -> _area[$value1] as $key2 => $value2 ) {
                    if ( $city == $key2 ) {
                        $result['city'] = $key1;
                        $result['city_id'] = $value1;
                        $area = $city;
                        $found = true;
                    }
                }
            }
            if ( !$found )  return false;
        }
        
        if ( $this -> _area[$result['city_id']][$area] ) {
            $result['area'] = $area;
            $result['area_id'] = $this -> _area[$result['city_id']][$area];
        }
        else {
            if ($area == '') {
                foreach ( $this -> _area[$result['city_id']] as $key => $value ) {
                    $result['area'] = $key;
                    $result['area_id'] = $value;
                    break;
                }
            }
            else if ($this -> _area[$result['province_id']][$area]) {
                $result['city'] = $area;
                $result['city_id'] = $this -> _area[$result['province_id']][$area];
                $result['area'] = $area;
                $result['area_id'] = $this -> _area[$result['city_id']][$area];
            }
            else {
                //return false;
            }
        }
        
        return $result;
    }
    
    /**
     * 获得所有物流公司
     *
     * @return  void
     */
    private function getLogisticCompany() {
        if ( $this -> _logistic )   return true;
        
        $this -> _api -> method = 'taobao.logistics.companies.get';
        $this -> _api -> fields =  'id,code,name';
		$this -> _api -> setVersion(2);
        $logisticArray = $this -> _api -> Send('get','xml') -> getArrayData();
        foreach ( $logisticArray['logistics_companies']['logistics_company'] as $logistic ) {
            $code = $this -> mapLogistic($logistic['name']);
            if ($code) {
                $this -> _logistic[$code] = $logistic['code'];
            }
        }
    }
    
    
    /**
     * 匹配物流公司
     *
     * @param   string  $status
     * @return  void
     */
    private function mapLogistic($logisticName) {
        if ( $logisticName == '汇通快运' )      return 'ht';
        if ( $logisticName == '申通E物流' )     return 'st';
        if ( $logisticName == '顺丰速运' )      return 'sf';
        if ( $logisticName == 'EMS' )           return 'ems';
        if ( $logisticName == '宅急送' )        return 'zjs';
        if ( $logisticName == '圆通速递' )      return 'yt';
        
        return false;
    }
}