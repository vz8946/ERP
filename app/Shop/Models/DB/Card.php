<?php

class Shop_Models_DB_Card
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 礼品卡表名
     * 
     * @var    string
     */
	private $_giftTable = 'shop_gift_card';
	
	/**
     * 礼品卡使用记录表名
     * 
     * @var    string
     */
	private $_giftUseTable = 'shop_gift_card_use_log';
	
	/**
     * 礼券表名
     * 
     * @var    string
     */
	private $_couponTable = 'shop_coupon_card';
	
	/**
     * 提货卡表名
     * 
     * @var    string
     */
	private $_goodsTable = 'shop_goods_card';
	
	/**
     * 礼券生成记录表名
     * 
     * @var    string
     */
	private $_couponLogTable = 'shop_coupon_card_create_log';
	private $_table_goods_card = 'shop_goods_card';
	private $_table_goods_card_create_log = 'shop_goods_card_create_log';
	private $_table_goods_card_type = 'shop_goods_card_type';
	private $_table_goods = 'shop_goods';
	private $_table_group_goods = 'shop_group_goods';
	private $_table_vitual_goods = 'shop_vitual_goods';
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	
	/**
     * 取得礼品卡信息
     *
     * @param    array    $where
     * @return   array
     */
	public function getGift($where = null)
	{
		if ($where) {
			$whereSql = ' WHERE 1=1';
			
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='$value'";
				}
			} else {
				$whereSql .= $where;
			}
		}
		
		$sql = 'SELECT * FROM `' . $this -> _giftTable . '` ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得已使用礼券信息
     *
     * @param    array    $where
     * @return   array
     */
	public function getCoupon($where = null)
	{
		if ($where) {
		    $whereSql = ' WHERE 1=1';
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='$value'";
				}
			} else {
				$whereSql .= $where;
			}
		} else {
            $whereSql = ' WHERE 0';
        }
		
		$sql = "SELECT t1.*,t2.start_date FROM {$this -> _couponTable} as t1 left join {$this -> _couponLogTable} as t2 on t1.log_id = t2.log_id {$whereSql}";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得礼券生成记录信息
     *
     * @param    array    $where
     * @return   array
     */
	public function getCouponLog($where = null)
	{
		if ($where) {
			$whereSql = ' WHERE 1=1';
			if (is_array($where)) {
				foreach ($where as $key => $value)
				{
					$whereSql .= " AND $key='$value'";
				}
			} else {
				$whereSql .= $where;
			}
		} else {
            $whereSql = ' WHERE 0';
        }
		
		$sql = 'SELECT * FROM `' . $this -> _couponLogTable . '` ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 使用礼品卡
     *
     * @param    array    $data
     * @return   int      affected rows
     */
	public function useGift($data)
	{
		$gift = @array_shift($this -> getGift(array('card_sn' => $data['card_sn'], 'card_pwd' => $data['card_pwd'], 'status' => 0)));
		if ($gift && $gift['card_price'] >= $data['card_price']) {
			$price = $gift['card_real_price'] - $data['card_price'];
			$set = array ('using_time' => $data['add_time'],
        	              'card_real_price' => $price
        	              );
        	if ($data['user_id']) {
        	    $set['user_id'] = $data['user_id'];
        	}
        	else {
        	    $data['user_id'] = $data['admin_id'];
        	}
        	if ($data['user_name']) {
        	    $set['user_name'] = $data['user_name'];
        	}
        	else {
        	    $data['user_name'] = $data['admin_name'];
        	}
        	$price <= 0 && $set['status'] = 1;
        	$where = $this -> _db -> quoteInto('card_sn = ?', $data['card_sn']);
			$useCard = $this -> _db -> update($this -> _giftTable, $set, $where);
			
			if ($useCard) {
				$row = array ('card_type' => $data['card_type'],
        	                  'card_sn' => $data['card_sn'],
        	                  'price' => $data['card_price'],
        	                  'user_id' => $data['user_id'],
        	                  'user_name' => $data['user_name'],
        	                  'batch_sn' => $data['batch_sn'],
        	                  'add_time' => time()
        	                  );
        	    return $this -> _db -> insert($this -> _giftUseTable, $row);
			}
		}
	}
	
	/**
     * 使用礼券
     *
     * @param    array    $data
     * @return   int      affected rows
     */
	public function useCoupon($data)
	{
		$condition = array('card_sn' => $data['card_sn'], 'card_pwd' => $data['card_pwd']);
		if ($data['is_repeat']) {
		    $condition['user_id'] = $data['user_id'];
		}
		$coupon = @array_shift($this -> getCoupon($condition));
		if ($coupon) {
			$set = array (
			              'add_time' => $data['add_time'],
			              'user_id' => $data['user_id'],
        	              'user_name' => $data['user_name'],
        	              'card_price' => $data['card_price'],
        	              'parent_id' => $data['parent_id'],
        	              'parent_param' => $data['parent_param'],
        	              'status' => $data['status']
        	              );
        	$where = $this -> _db -> quoteInto('card_sn = ?', $data['card_sn']);
			return $this -> _db -> update($this -> _couponTable, $set, $where);
		} else {
			$row = array (
        	              'log_id' => $data['log_id'],
        	              'card_type' => $data['card_type'],
        	              'is_repeat' => $data['is_repeat'],
        	              'card_price' => $data['card_price'],
        	              'card_sn' => $data['card_sn'],
        	              'card_pwd' => $data['card_pwd'],
        	              'user_id' => $data['user_id'],
        	              'user_name' => $data['user_name'],
        	              'add_time' => $data['add_time'],
        	              'status' => $data['status']
        	              );
        	$data['parent_id'] && $row['parent_id'] = $data['parent_id'];
        	$data['parent_param'] && $row['parent_param'] = $data['parent_param'];
        	return $this -> _db -> insert($this -> _couponTable, $row);
		}
	}
	
	/**
     * 使用提货卡
     *
     * @param    array    $data
     * @return   int      affected rows
     */
	public function useGoods($data)
	{
	    $this -> _db -> update($this -> _goodsTable, array('status' => 2, 'user_id' => $data['user_id'], 'user_name' => $data['user_name'], 'using_time' => time()), "card_sn = '{$data['card_sn']}'");
	}
	
	/**
     * 返还礼品卡
     *
     * @param    array    $data
     * @return   boolean
     */
	public function unUseGift($data)
	{
		$gift = @array_shift($this -> getGift(array('card_sn' => $data['card_sn'])));
		
		if ($gift) {
			$price = $gift['card_real_price'] + $data['card_price'];
			$set = array ('using_time' => $data['add_time'],
        	              'card_real_price' => $price
        	              );
        	$price >= 0 && $set['status'] = 0;
        	$where = $this -> _db -> quoteInto('card_sn = ?', $data['card_sn']);
			$useCard = $this -> _db -> update($this -> _giftTable, $set, $where);
			
			if ($useCard) {
				$row = array ('card_type' => $gift['card_type'],
        	                  'card_sn' => $data['card_sn'],
        	                  'price' => -$data['card_price'],
        	                  'user_id' => $data['admin_id'] ? $data['admin_id'] : $gift['user_id'],
        	                  'user_name' => $data['admin_name'] ? $data['admin_name'] : $gift['user_name'],
        	                  'batch_sn' => $data['batch_sn'],
        	                  'add_time' => time()
        	                  );
        	    return $this -> _db -> insert($this -> _giftUseTable, $row);
			}
		}
	}
	
	/**
     * 返还礼券
     *
     * @param    array    $data
     * @return   boolean
     */
	public function unUseCoupon($data)
	{
		$set = array (
                      'status' => 0
                      );
        $where = $this -> _db -> quoteInto('card_sn = ?', $data['card_sn']);
		return $this -> _db -> update($this -> _couponTable, $set, $where);
	}
	
	/**
     * 返还提货卡
     *
     * @param    array    $data
     * @return   boolean
     */
	public function unUseGoods($data)
	{
		$set = array ('status' => 1,
		              'user_id' => 0,
		              'user_name' => '',
		              'using_time' => NULL,
                      );
        $where = $this -> _db -> quoteInto('card_sn = ?', $data['card_sn']);
		return $this -> _db -> update($this -> _goodsTable, $set, $where);
	}
	
	/**
     * 添加礼券发放记录
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addCouponLog(array $data)
	{
		$row = array (
                      'card_type' => $data['card_type'],
                      'is_repeat' => $data['is_repeat'],
                      'card_price' => $data['card_price'],
                      'range_from' => $data['range_from'],
                      'range_end' => $data['range_end'],
                      'number' => $data['number'],
                      'admin_name' => $data['admin_name'],
                      'add_time' => $data['add_time'],
			          'start_date' => $data['start_date'],
                      'end_date' => $data['end_date'],
                      'status' => 0
                      );
        $data['min_amount'] && $row['min_amount'] = $data['min_amount'];
        $data['parent_id'] && $row['parent_id'] = $data['parent_id'];
        $data['note'] && $row['note'] = $data['note'];
        $data['is_system'] && $row['is_system'] = $data['is_system'];
        $data['goods_info'] && $row['goods_info'] = $data['goods_info'];
        if ($data['freight'] != null) {
            $row['freight'] = $data['freight'];
        }
        if ($this -> _db -> insert($this -> _couponLogTable, $row)) {
        	return $this -> _db -> lastInsertId();
        }
	}
	
	/**
     * 系统添加礼券
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addSysCoupon(array $data)
	{
		$row = array (
                      'log_id' => $data['log_id'],
                      'card_type' => $data['card_type'],
                      'is_repeat' => $data['is_repeat'],
                      'card_price' => $data['card_price'],
                      'card_sn' => $data['card_sn'],
                      'card_pwd' => $data['card_pwd'],
                      'user_id' => $data['user_id'],
                      'user_name' => $data['user_name'],
                      'add_time' => $data['add_time'],
                      'status' => 0
                      );
        return $this -> _db -> insert($this -> _couponTable, $row);
	}
	
	/**
     * 取得礼券发放记录
     *
     * @param    void
     * @return   int
     */
	public function getPosition()
	{
		$sql = 'SELECT IFNULL(MAX(range_end), 0) FROM `' . $this -> _couponLogTable . '`';
		return $this -> _db -> fetchOne($sql);
	}
	

	/**
     * 取得卡的使用记录
     *
     * @param   array     $data
     * @return  int
     */
    public function getCouponCard($cardSN)
    {   
        if ($cardSN) {
            $sql = "select * from {$this->_couponTable} where card_sn='{$cardSN}'";
            return $this -> _db -> fetchRow($sql);
        } else {
            return false;  
        }
    }
	/**
     * 添加卡的使用记录
     *
     * @param   array     $data
     * @return  int
     */
    public function addCouponCard($data)
    {
        $this->_db->insert($this->_couponTable, $data);
		return $this->_db->lastInsertId();
    }
	/**
     * 更新卡的使用记录
     *
     * @param   string     $cardSN
     * @param   array     $data
     * @return  int
     */
    public function updateCouponCard($cardSN, $data)
    {
        if ($cardSN) {
            return $this -> _db -> update($this->_couponTable, $data, "card_sn='{$cardSN}'");
        } else {
            return false;
        }
    }
    
    /**
     * 获得提货卡
     *
     * @param   string      $cardSn
     * @param   string      $cardPwd
     * @return  int
     */
    public function getGoodsCard($where)
    {
        $whereSQL = "where 1 ";
        if ($where['card_sn']) {
            $whereSQL .= " and t1.card_sn = '{$where['card_sn']}'";
        }
        if ($where['card_pwd']) {
            $whereSQL .= " and t1.card_pwd = '{$where['card_pwd']}'";
        }
        
        return $this -> _db -> fetchRow("select t1.*,t2.card_type_id,t2.status as log_status,t3.card_name,t3.goods_info from {$this -> _goodsTable} as t1 left join {$this -> _table_goods_card_create_log} as t2 on t1.log_id = t2.log_id left join {$this -> _table_goods_card_type} as t3 on t2.card_type_id = t3.card_type_id {$whereSQL}");
    }
    
    /**
     * 获得商品
     *
     * @param   string      $where
     * @return  int
     */
    public function getGoods($where)
    {
        return $this -> _db -> fetchAll("select * from {$this -> _table_goods} where {$where}");
    }
    
    /**
     * 获得组合商品
     *
     * @param   string      $where
     * @return  int
     */
    public function getGroupGoods($where)
    {
        return $this -> _db -> fetchAll("select * from {$this -> _table_group_goods} where {$where}");
    }
    /*
     * 
     * 判断卡券是否已经绑定到到$_couponTable
     */
    public function checkCouponTable($card)
    {
        $sql = 'select * from '.$this->_couponTable.' where card_sn = "'.$card.'"';
        return $this -> _db -> fetchRow($sql);
    }
    
    /**
     * 根据卡号，密码查询礼品卡信息
     *
     * @param   string      $card_sn
     * @param   string      $card_pwd
     * @return  array
     */
    public function checkGiftCard($card_sn, $card_pwd)
    {
        return $this -> _db -> fetchRow("select * from {$this -> _giftTable} where card_sn = '{$card_sn}' and card_pwd = '{$card_pwd}'");
    }
    
    /**
     * 绑定礼品卡
     *
     * @param   array       $data
     * @param   string      $where
     * @return  void
     */
    public function updateGiftCard($data, $where)
    {
        return  $this -> _db -> update($this -> _giftTable, $data, $where);
    }
    
    /**
     * 根据卡号，密码查询优惠券信息
     *
     * @param   string      $card_sn
     * @param   string      $card_pwd
     * @return  array
     */
    public function checkCouponCard($card_sn, $user_id = 0)
    {
        $sql = "select * from {$this -> _couponTable} where card_sn = '{$card_sn}'";
        $user_id && $sql .= " and user_id = '{$user_id}'";
        return $this -> _db -> fetchRow($sql);
    }
    
    /**
     * 添加体检卡
     *
     * @param   array      $data
     * @return  void
     */
    public function addBodyCard($data)
    {
        $this -> _db -> insert($this -> _table_vitual_goods, $data);
    }

	/**
     * 根据用户ID获取优惠券
     *
     * @param   int
	 *
     * @return  array
     */
	public function getCouponInfosByUserId($user_id)
	{
		$user_id = intval($user_id);
		if ($user_id < 1) {
			$this->_error = '用户ID不正确';
			return false;
		}
		$_condition[] = "coupon.user_id = '{$user_id}'";
		$_condition[] = "coupon.status = '0'";
		$_condition[] = "log.end_date >= '".date('Y-m-d')."'";

		$sql = "SELECT coupon.`card_id`, coupon.`card_type`, coupon.`card_price`, coupon.`card_sn`, coupon.`card_pwd`, coupon.`user_id`, coupon.`user_name`, coupon.`add_time`, coupon.`status`,log.start_date, log.end_date, log.min_amount,log.goods_info
				FROM {$this->_couponTable} coupon LEFT JOIN `shop_coupon_card_create_log` log ON coupon.log_id = log.log_id
				WHERE " . implode(' AND ', $_condition);

		$infos = $this->_db->fetchAll($sql);
		if (count($infos) < 1) {
			return array();
		}

		foreach ($infos as &$info) {
			$info['goods_info'] = unserialize($info['goods_info']);
		}

		return $infos;
	}

	/**
     * 根据用户ID获取礼品卡
     *
     * @param   int
	 *
     * @return  array
     */
	public function getGiftInfosByUserId($user_id)
	{
		$user_id = intval($user_id);
		if ($user_id < 1) {
			$this->_error = '用户ID不正确';
			return false;
		}
		$_condition[] = "user_id = '{$user_id}'";
		$_condition[] = "status = '0'";
		$_condition[] = "end_date >= '".date('Y-m-d')."'";

		$sql = "SELECT `card_id`, `card_type`, `card_price`, `card_real_price`, `card_sn`, `card_pwd`, `user_id`, `user_name`, `add_time`, `status`, `end_date` FROM {$this->_giftTable}
				WHERE " . implode(' AND ', $_condition);

		return $this->_db->fetchAll($sql);
	}
    
}