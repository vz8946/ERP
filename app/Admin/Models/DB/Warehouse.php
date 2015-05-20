<?php

class Admin_Models_DB_Warehouse
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 仓库表
     * 
     * @var    string
     */
	private $_warehouse = 'shop_warehouse';
	private $_area      = 'shop_area';
	
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
     * 获取仓库单条信息
     *
     * @param    int
	 *
     * @return   array
     */
	public function get($warehouse_id)
	{
		$warehouse_id = intval($warehouse_id);
		if ($warehouse_id < 1) {
			$this->_error = '仓库ID不正确';
			return false;
		}

		$sql ="select * from ".$this->_warehouse." WHERE warehouse_id = '{$warehouse_id}'";

		return $this->_db->fetchRow($sql);
	}

	/**
     * 获取仓库总数
     *
     * @param    array
	 *
     * @return   int
     */
	public function getCount($params)
	{
		$_condition = $this->getBrwoseCondition($params);
		$sql = "SELECT COUNT(1) FROM ".$this->_warehouse." WHERE ".implode(" AND ", $_condition). " limit 1";

		return $this->_db->fetchOne($sql);
	}

	/**
     * 获取仓库列表
     *
     * @param    array
	 *
     * @return   int
     */
	public function browse($params, $limit)
	{
		$_condition = $this->getBrwoseCondition($params);
		$field = array(
			'warehouse_id',
			'warehouse_sn',
			'warehouse_name',
			'province_id',
			'city_id',
			'district_id',
			'created_ts',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM ". $this->_warehouse." WHERE ". implode(' AND ', $_condition). " limit {$limit}";

		return $this->_db->fetchAll($sql);
	}

	/**
     * 处理条件
     *
     * @param    array
	 *
     * @return   int
     */
	public function getBrwoseCondition($params)
	{
		$_condition[] = "1 = 1";
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		!empty($params['warehouse_sn']) && $_condition[] = "warehouse_sn = '{$warehouse_sn}'";

		return $_condition;

	}

	/**
     * 添加仓库信息
     *
     * @param    array
	 *
     * @return   int
     */
	public function add($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		if (empty($params)) {
			$this->_error = '参数为空';
			return false;
		}

		return (bool) $this->_db->insert($this->_warehouse, $params);
	}

	/**
     * 编辑仓库信息
     *
     * @param    array
	 *
     * @return   int
     */
	public function edit($warehouse_id, $params)
	{
		$warehouse_id = intval($warehouse_id);
		if ($warehouse_id < 1) {
			$this->_error = '仓库ID不正确';
			return false;
		}
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		if (empty($params)) {
			$this->_error = '参数为空';
			return false;
		}

		return (bool) $this->_db->update($this->_warehouse, $params, "warehouse_id = '{$warehouse_id}'");
	}

	/**
     * 根据parent_id获取地址信息
     *
	 * @param    int
	 *
     * @return   array
     */
	public function getAreaInfosByParentId($parent_id)
	{
		$sql ="select * from ".$this->_area." WHERE parent_id = '{$parent_id}'";
		return $this->_db->fetchAll($sql);
	}

	public function getError()
	{
		return $this->_error;
	}
}