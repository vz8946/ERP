<?php

class Shop_Models_DB_Offers
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
		$whereSql = "WHERE status=1 ";
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
		$sql = 'SELECT *,datediff(to_date,CURDATE()) as expire FROM `' . $this -> _table . '` ' . $whereSql . ' ORDER BY `order` DESC ' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
    
    /**
     * 取得有效的促销活动信息
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
		$whereSql = "WHERE status=1 AND DATEDIFF(CURDATE(),from_date)>=0 AND DATEDIFF(to_date,CURDATE())>=0 ";
		
		if ($where != null) {
			if (is_array($where)) {
			    foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='".$value."'";
			    }
			} else {
				$whereSql .= 'and '.$where;
			}
		}
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql . ' ORDER BY `order` DESC ' . $limit;
		return $this -> _db -> fetchAll($sql);
    }
    
    /**
     * 取得促销活动信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getOffersNoLimit($where)
	{
		$sql = 'SELECT *,datediff(to_date,CURDATE()) as expire FROM `' . $this -> _table . '` where ' . $where . " and status = 1  ORDER BY `order` DESC ";
		return $this -> _db -> fetchAll($sql);
	}
}