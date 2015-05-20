<?php

class Admin_Models_DB_Tuan
{
	/**
     * Zend_Db
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * page size
     * @var    int
     */
	private $_pageSize = 5000;
	
	/**
     * table name
     * @var    string
     */
    private $_table = 'shop_tuan';
	private $_table_tuan_goods = 'shop_tuan_goods';
	private $_table_goods = 'shop_goods';
	private $_table_goods_cat = 'shop_goods_cat';
    private $_table_order_goods = 'shop_order_batch_goods';
    private $_table_order_batch = 'shop_order_batch';
    private $_table_order = 'shop_order';
    
	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
     * ��ȡ�Ź���ݼ�
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetch($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY tuan_id";
		}
		
		$sql = "SELECT $fields FROM {$this->_table} as t1 left join {$this->_table_tuan_goods} as t2 on t1.goods_id = t2.id left join {$this->_table_goods} as t3 on t2.goods_id = t3.goods_id $whereSql $orderBy $limit";
		$sql_count = "SELECT count(*) as count FROM {$this->_table} as t1 left join {$this->_table_tuan_goods} as t2 on t1.goods_id = t2.id left join {$this->_table_goods} as t3 on t2.goods_id = t3.goods_id $whereSql";
		return array('list'=>$this -> _db -> fetchAll($sql),'total'=> $this -> _db -> fetchOne($sql_count));
	}
	
	/**
     * ��ȡ�Ź���Ʒ��ݼ�
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetchGoods($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY id";
		}
		
		return array('list'=>$this -> _db -> fetchAll("SELECT $fields FROM {$this->_table_tuan_goods} as t1 left join {$this->_table_goods} as t2 on t1.goods_id = t2.goods_id left join {$this->_table_goods_cat} as t3 on t2.view_cat_id = t3.cat_id $whereSql $orderBy $limit"),'total'=> $this -> _db -> fetchOne("SELECT count(*) as count FROM $this->_table_tuan_goods as t1 left join {$this->_table_goods} as t2 on t1.goods_id = t2.goods_id left join {$this->_table_goods_cat} as t3 on t2.view_cat_id = t3.cat_id $whereSql"));
	}
	
	/**
     * ����ID��ѯ
     *
     * @param    string    $id
     * @return   array
     */
	public function getTuanById($id = null)
	{
		if($id>0){
			 return $this->_db->fetchRow("select * from `$this->_table` where tuan_id=".$id);
		}
	}
	
	/**
     * ����Ź����
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert(array $data)
	{
		$_auth = Admin_Models_API_Auth :: getInstance()->getAuth();
		$row = array (
		              'title' => $data['title'],
                      'goods_id' => $data['goods_id'] ? $data['goods_id'] : 0,
                      'price' => $data['price'] ? $data['price'] : 0,
                      'max_count' => $data['max_count'],
                      'count_limit' => $data['count_limit'],
                      'description' => $data['description'],
                      'freight' => $data['freight'],
                      'alt_count' => $data['alt_count'],
		              'status' => $data['status'] ? $data['status'] : 0,
                      'admin_name' => $_auth['admin_name'],
                      'add_time' => time(),
                      );
        if($data['start_time']){
			$row['start_time'] = strtotime($data['start_time']);
		}
		if($data['end_time']){
			$row['end_time'] = strtotime($data['end_time']);
		}
        $this -> _db -> insert($this -> _table, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * �����Ź����
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function update($data, $id)
	{
		$set = array ('title' => $data['title'],
                      'goods_id' => $data['goods_id'],
                      'price' => $data['price'] ? $data['price'] : 0,
                      'max_count' => $data['max_count'],
                      'count_limit' => $data['count_limit'],
                      'description' => $data['description'],
                      'freight' => $data['freight'],
		              'alt_count' => $data['alt_count'],
                      'status'=> $data['status'] ? $data['status'] : 0,
                      );
                
		if($data['start_time']){
			$set['start_time'] = strtotime($data['start_time'].' '.$data['start_time_time']);
		}
		if($data['end_time']){
			$set['end_time'] = strtotime($data['end_time'].' '.$data['end_time_time']);
		}
		
        $where = $this -> _db -> quoteInto('tuan_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
    
    /**
     * ����Ź���Ʒ���
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertGoods(array $data)
	{
		$_auth = Admin_Models_API_Auth :: getInstance()->getAuth();
		$row = array (
		              'goods_id' => $data['goods_id'],
                      'title' => $data['title'],
                      'price' => $data['price'],
                      'show_info' => $data['show_info'] ? $data['show_info'] : 0,
                      'description' => $data['description'],
		              'status' => $data['status'] ? $data['status'] : 0,
                      'add_time' => time(),
                      );
        $data['img1'] && $row['img1'] = $data['img1'];
	    $data['img2'] && $row['img2'] = $data['img2'];
	    $data['img3'] && $row['img3'] = $data['img3'];
	    $data['img4'] && $row['img4'] = $data['img4'];
	    $data['img5'] && $row['img5'] = $data['img5'];
	    
        $this -> _db -> insert($this -> _table_tuan_goods, $row);
        
		return $this -> _db -> lastInsertId();
	}
    
    /**
     * �����Ź���Ʒ���
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function updateGoods($data, $id)
	{
		$set = array ('goods_id' => $data['goods_id'],
		              'title' => $data['title'],
                      'price' => $data['price'],
                      'show_info' => $data['show_info'] ? $data['show_info'] : 0,
                      'description' => $data['description'],
                      'status'=> $data['status'] ? $data['status'] : 0,
                      );
        
		$data['img1'] && $set['img1'] = $data['img1'];
	    $data['img2'] && $set['img2'] = $data['img2'];
	    $data['img3'] && $set['img3'] = $data['img3'];
	    $data['img4'] && $set['img4'] = $data['img4'];
	    $data['img5'] && $set['img5'] = $data['img5'];
		
        $where = $this -> _db -> quoteInto('id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table_tuan_goods, $set, $where);
		    return true;
		}
	}
    
    /**
     * ����ID��ѯ��Ʒ
     *
     * @param    string    $id
     * @return   array
     */
	public function getGoodsById($id = null)
	{
		if($id>0){
			 return $this->_db->fetchRow("select t1.*,t2.goods_name from {$this->_table_tuan_goods} as t1 left join {$this->_table_goods} as t2 on t1.goods_id = t2.goods_id where t1.id=".$id);
		}
	}
	
	/**
     * ajax�����Ź�
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this -> _db -> quoteInto('tuan_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
	
	/**
     * ajax�����Ź���Ʒ
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdateGoods($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this -> _db -> quoteInto('id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table_tuan_goods, $set, $where);
		    return true;
		}
	}
	
	/**
     * ɾ���Ź�
     *
     * @param    int      $id
     * @return   void
     */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('tuan_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> delete($this -> _table, $where);
		}
	}
	
	/**
     * ɾ����Ʒ
     *
     * @param    int      $id
     * @return   void
     */
	public function deleteGoods($id)
	{
		$where = $this -> _db -> quoteInto('id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> delete($this -> _table_tuan_goods, $where);
		}
	}
	
    /**
     * ��ȡ�Ź�����
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetchOrderGoods($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		$whereSql = 'WHERE t1.type = 6';
		if ($where != null) {
			$whereSql .= " and $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY tuan_id";
		}
		
		$sql1 = "select $fields from {$this->_table_order_goods} as t1
	                        left join {$this->_table_order_batch} as t2 on t1.order_batch_id = t2.order_batch_id	
	                        left join {$this->_table_order} as t3 on t1.order_id = t3.order_id 
	                        left join {$this->_table} as t4 on t1.offers_id = t4.tuan_id
	                        left join {$this->_table_tuan_goods} as t5 on t4.goods_id = t5.id
                            {$whereSql} {$orderBy} {$limit}";
		$sql2 = "select count(*) as count from {$this->_table_order_goods} as t1
	                        left join {$this->_table_order_batch} as t2 on t1.order_batch_id = t2.order_batch_id	
	                        left join {$this->_table_order} as t3 on t1.order_id = t3.order_id 
	                        left join {$this->_table} as t4 on t1.offers_id = t4.tuan_id
	                        left join {$this->_table_tuan_goods} as t5 on t4.goods_id = t5.id
                            {$whereSql}";
        
		return array('list' => $this -> _db -> fetchAll($sql1),
		             'total' => $this -> _db -> fetchOne($sql2)
		             );
	}
    
    /**
     * ����Ź���������Ʒ
     *
     * @param    string    $where
     * @return   void
     */
	public function fetchOrderCommonGoods($where)
	{
	    return $this -> _db -> fetchAll("select order_batch_goods_id,goods_id,goods_name,sale_price from {$this->_table_order_goods} where {$where}");
	}

}