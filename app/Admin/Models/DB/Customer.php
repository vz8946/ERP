<?php
class Admin_Models_DB_Customer
{
	private $_db = null;

	private $_table_customer = 'shop_customer';

	private $_error;

	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this->_db = Zend_Registry::get('db');
	}

	

	/**
     * 根据客户id获取客户数据
     *
     * @param    int
     *
     * @return   array
     */
	public function get($customer_id)
	{
		$customer_id = intval($customer_id);
		if ($customer_id < 1) {
			$this->_error = '客户ID不正确';
			return false;
		}
		$field = array(
			'customer_id',
			'shop_id',
			'real_name',
			'email',
			'sex',
			'telphone',
			'mobile',
			'first_order_time',
			'last_order_time',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_customer}` WHERE customer_id = '{$customer_id}' limit 1";

		return $this->_db->fetchRow($sql);
	}

	/**
     * 获取信息列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function browse($params, $limit)
	 {	
		$_condition = $this->getBrowseCondition($params);

		$field = array(
			'customer_id',
			'shop_id',
			'real_name',
			'telphone',
			'mobile',
			'first_order_time',
			'last_order_time',
			'province_id',
			'created_ts',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_customer}` WHERE ". implode(' AND ', $_condition) ." ORDER BY customer_id desc limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取信息总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getCount($params)
	 {	
		$_condition = $this->getBrowseCondition($params);

		$sql = "SELECT count(*) as count FROM `{$this->_table_customer}` WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getBrowseCondition($params)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		
		$_condition[] = "is_deleted = '0'";
		!empty($params['start_ts']) && $_condition[] = "created_ts >= '{$params['start_ts']} 00:00:00'";
		!empty($params['end_ts'])   && $_condition[] = "created_ts <= '{$params['end_ts']} 23:59:59'";
		!empty($params['shop_id'])  && $_condition[] = "shop_id = '{$params['shop_id']}'";

		return $_condition;
	 }


	/**
	* 返回错误信息
	*
	* @return   string
	*/
	public function getError()
	{
		return $this->_error;	
	}


}