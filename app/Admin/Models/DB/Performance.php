<?php
class Admin_Models_DB_Performance
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
	private $_pageSize = 50;
	
	private $_table_order = 'shop_order';
    private $_table_order_batch = 'shop_order_batch';
    private $_table_admin = 'shop_admin';

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
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getTelOrder($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = "WHERE a.operator_id > 0 and b.type = '1' and  group_id = '2' $where";
		}else{
            $whereSql = "WHERE a.operator_id > 0 and b.type = '1' and  group_id = '2' ";
        }
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY order_id DESC ";
		}
        $table = " {$this -> _table_order} a left join  {$this -> _table_order_batch} b  on a.order_id = b.order_id  left join {$this -> _table_admin} d  on a.operator_id = d.admin_id";
		return array('list'=>$this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit"),'totaldata'=> $this -> _db -> fetchRow("SELECT count(*) as total,Sum(b.price_pay) as totalPrice FROM $table $whereSql"));
	}

	/**
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getExportTelOrder($where = null)
	{
		if ($where != null) {
			$whereSql = "WHERE a.operator_id > 0 and b.type = '1' $where";
		}else{
            $whereSql = "WHERE a.operator_id > 0 and b.type = '1' ";
        }
        $table = " {$this -> _table_order} a left join  {$this -> _table_order_batch} b  on a.order_id = b.order_id  left join {$this -> _table_admin} d  on a.operator_id = d.admin_id";
		return $this -> _db -> fetchAll("SELECT a.operator_id,a.order_id,a.batch_sn,a.add_time,b.status,b.status_logistic,b.status_pay,b.status_return,b.addr_consignee,b.price_order,b.price_goods,b.price_pay,b.price_logistic,b.pay_name,b.addr_tel,b.addr_mobile,d.admin_id,d.real_name,d.group_id FROM $table $whereSql") ;
	}
}