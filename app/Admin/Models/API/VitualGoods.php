<?php
class Admin_Models_API_VitualGoods {
	private $_db;
	private $_authUser;
	private $_table_vitual_goods = 'shop_vitual_goods';
	private $_table_product = 'shop_product';
	private $_table_goods = 'shop_goods';
	private $_table_order_batch = 'shop_order_batch';
	private $_table_order_batch_goods = 'shop_order_batch_goods';
	public $_error;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$auth = Admin_Models_API_Auth::getInstance() -> getAuth();
		if ($auth['admin_name']) {
		    $this -> _authUser['name'] = $auth['admin_name'];
		    $this -> _authUser['id'] = $auth['admin_id'];
		}
		else {
		    $auth = Shop_Models_API_Auth::getInstance() -> getAuth();
		    $this -> _authUser['name'] = $auth['user_name'];
		    $this -> _authUser['id'] = $auth['user_id'];
		}
		
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
     * 是否包含虚拟商品
     *
     * @param    array    $orderGoodsList
     * @return   array
     */
	public function has($orderGoodsList)
	{
	    if (!$orderGoodsList)   return false;
	    
	    foreach ($orderGoodsList as $goods) {
	        if ($goods['type'] == 7 || $goods['type'] == 8) {
	            $result[] = $goods;
	        }
	    }
	    
	    return $result;
	}
	
	/**
     * 虚拟商品分配
     *
     * @param    int    $vitualID
     * @param    int    $userID
     * @return   boolean
     */
	public function assign($vitualID, $userID)
	{
	    
	}
	
	/**
     * 虚拟商品发货
     *
     * @param    array      $order
     * @param    array      $orderGoodsList
     * @param    boolean    $sms
     * @return   boolean
     */
	public function send($order, $orderGoodsList, $sms = true)
	{
	    if (!$orderGoodsList)   return false;
	    if ($order['status'])   return false;
	    if ($order['status_pay'] != 1 && $order['status_pay'] != 2) return false;
	    $vitualInfo = $this -> getVitualInfo($orderGoodsList);

	    if (!$vitualInfo['hasVitual']) {
	        return false;
	    }
	    
	     $gifcardApi  = new  Admin_Models_API_GiftCard();
	     $smsAPI = new  Custom_Model_Sms();
         foreach ($orderGoodsList as $orderGoods) {
    	    	 if ($orderGoods['type'] == 7 && $sms) {   
	    	        $where = array('order_batch_goods_id' => $orderGoods['order_batch_goods_id'], 'status' => 1);
	    	        $vitualGoodsList = $this -> getVitualGoods($where);
	    	        if (!$vitualGoodsList)  return false;
	    	        foreach ($vitualGoodsList as $vitualGoods) {
	    	              $this -> sms($order, $vitualGoods);
	    	        }
    	    	}elseif ($orderGoods['type'] == 8)//激活健康卡
    	    	{
    	    	     $gifcardApi->updateCard("order_batch_goods_id=".$orderGoods['order_batch_goods_id'], array('status'=>0));
    	    	     if ($sms){ //发送健康卡
    	    	     	 $vitualGoods = $gifcardApi->getCardlist(array('order_batch_goods_id'=>$orderGoods['order_batch_goods_id']));
    	    	     	 if($vitualGoods)
    	    	     	 {
	    	    	         foreach ($vitualGoodsList as $vitualGoods) {    	    	         		    	         	
	    	    	         	$content = "您在垦丰网站购买的{$orderGoods['goods_name']}，卡号为{$vitualGoods['card_sn']}，密码为{$vitualGoods['card_pwd']}，请妥善保存。";    	    	         	
	    	    	         	$smsAPI -> send($order['sms_no'], $content);
		    	              }
    	    	     	 }
    	    	     }
    	    	}
    	   }
	    

	    if (in_array($order['status_logistic'], array(0, 2)) && !$order['invoice_type'] && $vitualInfo['onlyVitual']) {
	        $this -> vitualSend($order['batch_sn']);
	    }
	    
	    return true;
	}
	
	/**
     * 虚拟商品发货
     *
     * @param    string     $batchSN
     * @param    string     $sms
     * @return   boolean
     */
	public function sendByBatchSN($batchSN, $sms = true)
	{
	    $orderAPI = new Admin_Models_DB_Order();
	    
	    return $this -> send(array_shift($orderAPI -> getOrderBatch(array('batch_sn' => $batchSN))), $orderAPI -> getOrderBatchGoods(array('batch_sn' => $batchSN)), $sms);
	}
	
	/**
     * 获得虚拟商品
     *
     * @param    array  $where
     * @param    int    $page
     * @param    int    $pageSize
     * @return   boolean
     */
    public function getVitualGoods($where = 1, $page = null, $pageSize = null, $order = null)
    {
        $whereSQL = $this -> getVitualGoodsSql($where);
        
        if ($page != null) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($order != null) {
		    $orderBy = "ORDER BY {$order}";
		}
        
        $sql = "select t1.*,t2.product_name,t2.product_sn,t4.batch_sn,t4.sms_no from {$this -> _table_vitual_goods} as t1
                inner join {$this -> _table_product} as t2 on t1.product_id = t2.product_id
                left join {$this -> _table_order_batch_goods} as t3 on t1.order_batch_goods_id = t3.order_batch_goods_id
                left join {$this -> _table_order_batch} as t4 on t3.batch_sn = t4.batch_sn
                where {$whereSQL} {$orderBy} {$limit}";
        return $this -> _db -> fetchAll($sql);
    }
    
    /**
     * 获得虚拟商品数量
     *
     * @param    array  $where
     * @return   int
     */
    public function getVitualGoodsCount($where = 1)
    {
        $whereSQL = $this -> getVitualGoodsSql($where);
        
        $sql = "select count(*) as number from {$this -> _table_vitual_goods} as t1
                inner join {$this -> _table_product} as t2 on t1.product_id = t2.product_id
                left join {$this -> _table_order_batch_goods} as t3 on t1.order_batch_goods_id = t3.order_batch_goods_id
                left join {$this -> _table_order_batch} as t4 on t3.batch_sn = t4.batch_sn
                where {$whereSQL}";
        return $this -> _db -> fetchOne($sql);
    }
    
    /**
     * 获得虚拟商品
     *
     * @param    array  $where
     * @return   array
     */
    public function sumVitualGoods($where = 1)
    {
        if (is_array($where)) {
            $whereSQL = 1;
            if ($where['using_time_from']) {
                $time = strtotime("{$where['using_time_from']} 00:00:00");
                $whereSQL .= " and t1.using_time >= {$time}";
            }
            if ($where['using_time_end']) {
                $time = strtotime("{$where['using_time_end']} 23:59:59");
                $whereSQL .= " and t1.using_time <= {$time}";
            }
            $where['product_id'] && $whereSQL .= " and t1.product_id = '{$where['product_id']}'";
            $where['type'] && $whereSQL .= " and t1.type = '{$where['type']}'";
        }
        else {
            $whereSQL =$where;
        }
        
        $sql = "select t1.status,t1.product_id,t2.product_name,t2.product_sn,t3.price,count(*) as number from {$this -> _table_vitual_goods} as t1
                inner join {$this -> _table_product} as t2 on t1.product_id = t2.product_id
                inner join {$this -> _table_goods} as t3 on t2.product_id = t3.product_id
                where {$whereSQL}
                group by t1.product_id,t1.status";
        $datas = $this -> _db -> fetchAll($sql);
        if (!$datas)    return false;
        
        foreach ($datas as $data) {
            $result[$data['product_id']]['product_name'] = $data['product_name'];
            $result[$data['product_id']]['product_sn'] = $data['product_sn'];
            $result[$data['product_id']]['price'] = $data['price'];
            $result[$data['product_id']]['total'] += $data['number'];
            if ($data['status'] == 1) {
                $result[$data['product_id']]['status1'] += $data['number'];
            }
            else if ($data['status'] == 2) {
                $result[$data['product_id']]['status2'] += $data['number'];
            }
            else if ($data['status'] == 9) {
                $result[$data['product_id']]['status9'] += $data['number'];
            }
        }
        
        return $result;
    }
    
    /**
     * 获得虚拟商品的产品信息
     *
     * @param    array  $where
     * @return   array
     */
    public function getVitualProduct($where = 1)
    {
        $sql = "select t1.product_id,t2.product_sn,t2.product_name from {$this -> _table_vitual_goods} as t1
                inner join {$this -> _table_product} as t2 on t1.product_id = t2.product_id
                where {$where}
                group by t1.product_id";
        return $this -> _db -> fetchAll($sql);
    }
    
    /**
     * 获得虚拟商品查询条件
     *
     * @param    array  $where
     * @return   string
     */
    private function getVitualGoodsSql($where = 1)
    {
        if (is_array($where)) {
            $whereSQL = 1;
            if ($where['order_batch_goods_id']) {
                if (is_array($where['order_batch_goods_id'])) {
                    $whereSQL .= " and t1.order_batch_goods_id in (".implode(',', $where['order_batch_goods_id']).")";
                }
                else {
                    $whereSQL .= " and t1.order_batch_goods_id = '{$where['order_batch_goods_id']}'";
                }
            }
            $where['product_id'] && $whereSQL .= " and t1.product_id = '{$where['product_id']}'";
            $where['product_name'] && $whereSQL .= " and t2.product_name like '%{$where['product_name']}%'";
            $where['sn'] && $whereSQL .= " and t1.sn = '{$where['sn']}'";
            $where['sms_no'] && $whereSQL .= " and t4.sms_no = '{$where['sms_no']}'";
            $where['user_id'] && $whereSQL .= " and t1.user_id = '{$where['user_id']}'";
            $where['user_name'] && $whereSQL .= " and t1.user_name = '{$where['user_name']}'";
            $where['type'] && $whereSQL .= " and t1.type = '{$where['type']}'";
            if ($where['deliver_time_from']) {
                $time = strtotime("{$where['deliver_time_from']} 00:00:00");
                $whereSQL .= " and t1.deliver_time >= {$time}";
            }
            if ($where['deliver_time_end']) {
                $time = strtotime("{$where['deliver_time_end']} 23:59:59");
                $whereSQL .= " and t1.deliver_time <= {$time}";
            }
            if ($where['using_time_from']) {
                $time = strtotime("{$where['using_time_from']} 00:00:00");
                $whereSQL .= " and t1.using_time >= {$time}";
            }
            if ($where['using_time_end']) {
                $time = strtotime("{$where['using_time_end']} 23:59:59");
                $whereSQL .= " and t1.using_time <= {$time}";
            }
            if ($where['status'] !== '' && $where['status'] !== null) {
                if (is_array($where['status'])) {
                    $whereSQL .= " and t1.status in (".implode(',', $where['status']).")";
                }
                else {
                    $whereSQL .= " and t1.status = '{$where['status']}'";
                }
            }
            if ($where['type'] !== '' && $where['type'] !== null) {
                $whereSQL .= " and t1.type = '{$where['type']}'";
            }
            if ($where['sn_sms']) {
                if ($this -> getVitualGoodsCount(array('sn' => $where['sn_sms']))) {
                    $whereSQL .= " and t1.sn = '{$where['sn_sms']}'";
                }
                else {
                    $whereSQL .= " and t4.sms_no = '{$where['sn_sms']}'";
                }
            }
        }
        else {
            $whereSQL = $where;
        }
        
        return $whereSQL;
    }
    
    /**
     * 获得是否存在虚拟商品
     *
     * @param    array      $orderGoodsList
     * @return   array
     */
    public function getVitualInfo($orderGoodsList)
    {
        if (!$orderGoodsList)   return false;
        
        foreach ($orderGoodsList as $orderGoods) {
            if ($orderGoods['number'] == 0) continue;
            if ($orderGoods['type'] == 7 || $orderGoods['type'] == 8) {
                $result['hasVitual'] = 1;
                if (!isset($result['onlyVitual'])) {
                    $result['onlyVitual'] = 1;
                }
            }
            else {
                $result['onlyVitual'] = 0;
            }
        }
        
        return $result;
    }
    
    /**
     * 虚拟发货
     *
     * @param    string     $batchSN
     * @return   boolean
     */
    public function vitualSend($batchSN)
    {
        if (!$this -> _orderAPI) {
            $this -> _orderAPI = new Admin_Models_API_Order();
        }
        if (!$this -> _outstockAPI) {
            $this -> _outstockAPI = new Admin_Models_API_OutStock();
            $this -> _outstockAPI -> _auth['admin_name'] = $this -> _authUser['name'];
        }
        
        //$this -> _orderAPI -> orderDetail($batchSN);
        
        $datas = $this -> _orderAPI -> getOrderBathWithPage(array('batch_sn' => $batchSN));
        if (!$datas['data'])    return false;
        $order = array_shift($datas['data']);
        $productList = $datas['product'];
        if (!$order || !$productList)   return false;
        if ($order['status_value'])     return false;
        if ($order['status_pay_value'] != 1 && $order['status_pay_value'] != 2) return false;
        if (!in_array($order['status_logistic'], array(0, 2)))  return false;
        if ($this -> _outstockAPI -> get("b.bill_no = '{$batchSN}'")) return false;
        $lid = 1;
        $outStock = array('lid' => $lid,
                          'bill_no' => $batchSN,
                          'bill_type' => 1,
                          'bill_status' => 4,
                          'remark' => '虚拟商品出库',
                          'add_time' => time(),
                          'admin_name' => $this -> _authUser['name'],
                         );
        foreach ($productList as $product) {
            $details[] = array('product_id' => $product['product_id'],
                               'status_id' => 2,
                               'number' => $product['number'],
                               'shop_price' => $product['price'],
                              );
        }
        
        if ($this -> _outstockAPI -> insertApi($outStock, $details, $lid, true)) {
            $this -> _outstockAPI -> sendByBillNo($batchSN);
            
            if (!$this -> _orderDB) {
                $this -> _orderDB = new Admin_Models_DB_Order();
            }
            $set = array('status_logistic' => 4,
                         'logistic_time' => time(),
                         'balance_amount' => $order['price_order'],
                        );
            $this -> _orderDB -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
            
            $log = array('order_sn' => $order['order_sn'],
                         'batch_sn' => $batchSN,
                         'add_time' => time(),
                         'title' => '订单虚拟发货',
                         'admin_name' => $this -> _authUser['name'],
                        );
            $this -> _orderDB -> addOrderBatchLog($log);
        }
        
        return true;
    }
    
    /**
     * 作废虚拟商品
     *
     * @param    string     $batchSN
     * @param    int        $productID
     * @param    int        $number
     * @return   boolean
     */
    public function cancelByBatchSN($batchSN, $productID, $number)
    {
        $orderAPI = new Admin_Models_DB_Order();
        $orderGoods = array_shift($orderAPI -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'type' => 7, 'product_id' => $productID)));
        if (!$orderGoods)   return false;
        
        $vitualGoodsList = $this -> getVitualGoods(array('order_batch_goods_id' => $orderGoods['order_batch_goods_id'], 'status' => 1));
        if (!$vitualGoodsList || count($vitualGoodsList) < $number) return false;
        
        for ($i = 0; $i < $number; $i++) {
            $this -> _db -> update($this -> _table_vitual_goods, array('status' => 9), "vitual_id = {$vitualGoodsList[$i]['vitual_id']}");
        }
        
        return true;
    }
    
    /**
     * 恢复虚拟商品
     *
     * @param    string     $batchSN
     * @param    int        $productID
     * @param    int        $number
     * @return   boolean
     */
    public function undoByBatchSN($batchSN, $productID, $number)
    {
        $orderAPI = new Admin_Models_DB_Order();
        $orderGoods = array_shift($orderAPI -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'type' => 7, 'product_id' => $productID)));
        if (!$orderGoods)   return false;
        
        $vitualGoodsList = $this -> getVitualGoods(array('order_batch_goods_id' => $orderGoods['order_batch_goods_id'], 'status' => 9));
        if (!$vitualGoodsList || count($vitualGoodsList) < $number) return false;
        
        for ($i = 0; $i < $number; $i++) {
            $this -> _db -> update($this -> _table_vitual_goods, array('status' => 1), "vitual_id = {$vitualGoodsList[$i]['vitual_id']}");
        }
        
        return true;
    }
    
    /**
     * 使用虚拟商品
     *
     * @param    string     $sn
     * @return   boolean
     */
    public function consume($sn)
    {
        $vitualGoods = array_shift($this -> getVitualGoods(array('sn' => $sn, 'status' => 1)));
        if (!$vitualGoods)  return false;
        
        $orderAPI = new Admin_Models_DB_Order();
        $orderGoods = array_shift($orderAPI -> getOrderBatchGoods(array('order_batch_goods_id' => $vitualGoods['order_batch_goods_id'])));
        if (!$orderGoods)   return false;
        $order = array_shift($orderAPI -> getOrderBatchInfo(array('batch_sn' => $orderGoods['batch_sn'])));
        if (!$order)    return false;

        $this -> _db -> update($this -> _table_vitual_goods, array('status' => 2, 'using_time' => time()), "sn = '{$vitualGoods['sn']}'");
        
        $smsAPI = new  Custom_Model_Sms();
        $smsAPI -> send($order['sms_no'], "您在垦丰网站购买的{$vitualGoods['product_name']}，验证码{$vitualGoods['sn']}，于".date('Y-m-d H:i:s')."验证通过，请提前三天预约，预约电话010-56227682，高小姐。");
        
        return true;
    }
    
    /**
     * 添加虚拟商品
     *
     * @param    array      $data
     * @return   void
     */
	public function add($data)
	{
	    $this -> _db -> insert($this -> _table_vitual_goods, $data);
	}
	
	/**
     * 更改虚拟商品
     *
     * @param    string     $sn
     * @return   boolean
     */
    public function update($data, $where)
    {
        $this -> _db -> update($this -> _table_vitual_goods, $data, $where);
    }
	
	/**
     * 删除虚拟商品
     *
     * @param    string     $sn
     * @return   boolean
     */
    public function remove($sn)
    {
        $this -> _db -> delete($this -> _table_vitual_goods, "sn = '{$sn}'");
    }
    
    /**
     * 发送短信
     *
     * @param    array      $userInfo
     * @param    array      $vitualGoods
     * @return   void
     */
	private function sms($userInfo, $vitualGoods)
	{
        $smsAPI = new  Custom_Model_Sms();
        if ($vitualGoods['type'] == 1) {
            $content = "您在垦丰网站购买的{$vitualGoods['product_name']}，验证码为{$vitualGoods['sn']}，请妥善保存。";
        }
        else if ($vitualGoods['type'] == 2) {
            $content = "您在垦丰网站购买的{$vitualGoods['product_name']}，卡号为{$vitualGoods['sn']}，密码为{$vitualGoods['pwd']}，请妥善保存。";
        }
        $smsAPI -> send($userInfo['sms_no'], $content);
        
        if ($vitualGoods['send_content']) {
            $sendContent = unserialize($vitualGoods['send_content']);
        }
        $sendContent[] = array('sendUser' => $this -> _authUser['name'],
                               'sendTime' => time(),
                              );
        
        $this -> _db -> update($this -> _table_vitual_goods, array('send_content' => serialize($sendContent)), "vitual_id = '{$vitualGoods['vitual_id']}'");
	}
    
}
