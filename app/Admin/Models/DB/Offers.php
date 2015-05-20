<?php

class Admin_Models_DB_Offers
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 分页大小
     * 
     * @var    int
     */
	private $_pageSize = null;
	
	/**
     * 会员等级表名
     * 
     * @var    string
     */
	private $_table = 'shop_offers';
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
		$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}
	
	/**
     * 取得促销活动信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getOffersInfo($where = null){
  		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where) && count($where) ) {
                foreach ($where as $key => $value)
                {
                    $whereSql .= " AND $key='$value'";
                }
			}
		}  
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql . ' ORDER BY offers_id DESC ';
		return $this -> _db -> fetchRow($sql);

    
    }


	/**
     * 取得促销活动信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getOffers($where = null, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}

		$whereSql = 'WHERE 1=1';
		if ( $where ) {
		    if ( is_array($where) ) {
		        foreach ($where as $key => $value) {
    				$whereSql .= " AND $key='".$value."'";
    			}
		    }
		    else {
		        $whereSql .= " AND {$where}";
		    }
		}

		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql . ' ORDER BY offers_id DESC ' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得促销活动个数
     *
     * @param    string    $where
     * @return   int
     */
	public function getOffersCount($where = null)
	{
		$whereSql = 'WHERE 1=1';
		if ( $where ) {
		    if ( is_array($where) ) {
		        foreach ($where as $key => $value) {
    				$whereSql .= " AND $key='".$value."'";
    			}
		    }
		    else {
		        $whereSql .= " AND {$where}";
		    }
		}
        
		$count = $this -> _db -> fetchOne('SELECT count(*) as count FROM `' . $this -> _table . '` ' . $whereSql);
		return $count;
	}
	
	/**
     * 添加促销活动
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addOffers(array $data)
	{
		$row = array (
                      'offers_name' => $data['offers_name'],
			          'as_name' => $data['as_name'],
                      'offers_type' => $data['offers_type'],
                      'use_coupon' => $data['use_coupon'],
                      'offers_rank' => $data['offers_rank'],
                      'from_date' => $data['from_date'],
                      'config' => $data['config'],
                      'status' => 1,
                      'admin_name' => $data['admin_name'],
                      'add_time' => time()
                      );
        $data['to_date'] && $row['to_date'] = $data['to_date'];
        $this -> _db -> insert($this -> _table, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 更新促销活动
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updateOffers(array $data, $id)
	{
		$set = array (
                      'offers_name' => $data['offers_name'],
			          'as_name' => $data['as_name'],
                      'use_coupon' => $data['use_coupon'],
                      'offers_rank' => $data['offers_rank'],
                      'from_date' => $data['from_date'],
                      'config' => $data['config']
                      );
        $data['to_date'] && $set['to_date'] = $data['to_date'];
        $where = $this -> _db -> quoteInto('offers_id = ?', $id);
		return $this -> _db -> update($this -> _table, $set, $where);
	}
	
	/**
     * 删除促销活动
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deleteOffers($id)
	{
		$where = $this -> _db -> quoteInto('offers_id = ?', $id);
		return $this -> _db -> delete($this -> _table, $where);
	}
	
	/**
     * 更改活动状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   int    lastInsertId
     */
	public function updateStatus($id, $status)
	{
		$set = array ('status' => $status);
		$where = $this -> _db -> quoteInto('offers_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> update($this -> _table, $set, $where);
		}
	}
	
	/**
     * 更改是否可使用礼券
     *
     * @param    int    $id
     * @param    int    $status
     * @return   int    lastInsertId
     */
	public function updateCoupon($id, $status)
	{
		$set = array ('use_coupon' => $status);
		$where = $this -> _db -> quoteInto('offers_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> update($this -> _table, $set, $where);
		}
	}
	
	/**
     * 更改活动优先级
     *
     * @param    int    $id
     * @param    int    $order
     * @return   int    lastInsertId
     */
	public function updateOrder($id, $order)
	{
		$set = array ('order' => $order);
		$where = $this -> _db -> quoteInto('offers_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> update($this -> _table, $set, $where);
		}
	}
	
	/**
     * 取得有效的促销活动信息(copy from shop)
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
    public function getActiveOffers($where = null, $page = null, $pageSize = null) {
        if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}
		$whereSql = 'WHERE status=1 AND DATEDIFF(CURDATE(),from_date)>=0 AND DATEDIFF(to_date,CURDATE())>=0 ';
		
		if ($where != null) {
			if (is_array($where)) {
			    foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='".$value."'";
			    }
			} else {
				$whereSql .= $where;
			}
		}
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql . ' ORDER BY `order` DESC ' . $limit;
		return $this -> _db -> fetchAll($sql);
    }
    
    /**
     * 按条件获得每个offer的订单数
     *
     * @param    array   $offer_ids
     * @param    string  $where
     * @return   array
     */
    public function getOfferOrderCount($offer_ids, $where) {
        
        if (!$offer_ids)   return false;
        
        $sql = "select offers_id, count(*) as num from shop_order_batch_goods as t1 left join shop_order_batch 
                as t2 on t1.order_batch_id = t2.order_batch_id  where t1.offers_id in (".implode(',', $offer_ids).") {$where} group by offers_id order by offers_id";
        
        return $this -> _db -> fetchAll($sql);
        
    }
}