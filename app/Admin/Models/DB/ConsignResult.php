<?php
class Admin_Models_DB_ConsignResult
{
	private $_db = null;

	private $_table_consign     = 'shop_consign_result';
	private $_table_consign_log = 'shop_consign_result_log';

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
     * 根据信息ID获取信息
     *
     * @param    int
     *
     * @return   array
     */
	public function get($consign_result_id)
	{
		$consign_result_id = intval($consign_result_id);
		if ($consign_result_id < 1) {
			$this->_error = '代销结款ID不正确';
			return false;
		}
		$field = array(
			'consign_result_id',
			'warehouse_id',
			'r.product_id',
			'number',
			'deal_number',
			'created_month',
			'product_sn',
			'product_name',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_consign}` r LEFT JOIN `shop_product` p on r.product_id = p.product_id WHERE consign_result_id = '{$consign_result_id}' limit 1";

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
		list($_condition, $_join) = $this->getBrowseCondition($params);

		$field = array(
			'consign_result_id',
			'warehouse_id',
			'r.product_id',
			'number',
			'deal_number',
			'created_month',
			'product_sn',
			'product_name',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_consign}` r ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." ORDER BY consign_result_id desc limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取汇总信息列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function collectBrowse($params, $limit)
	 {	
		list($_condition, $_join, $_group_by) = $this->getBrowseCondition($params);

		$field = array(
			'consign_result_id',
			'warehouse_id',
			'r.product_id',
			'SUM(number) AS number',
			'SUM(deal_number) as deal_number',
			'created_month',
			'product_sn',
			'product_name',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_consign}` r ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) .
			   " GROUP BY {$_group_by} ORDER BY consign_result_id desc limit {$limit}";

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
		list($_condition, $_join) = $this->getBrowseCondition($params);

		$sql = "SELECT count(*) as count FROM `{$this->_table_consign}` r ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 获取汇总信息总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getCollectCount($params)
	 {
		list($_condition, $_join, $_group_by) = $this->getBrowseCondition($params);

		$sql = "SELECT count(distinct($_group_by)) as count FROM `{$this->_table_consign}` r ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 汇总信息总数
     *
     * @param    array
     *
     * @return   array
     */
	 public function getTotalCounts($params)
	 {
		list($_condition, $_join) = $this->getBrowseCondition($params);

		$sql = "SELECT SUM(number) as number, SUM(deal_number) as deal_number FROM `{$this->_table_consign}` r ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchRow($sql);
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
		$_condition[] = "1 = 1";
		!empty($params['start_month'])     && $_condition[] = "created_month >= '{$params['start_month']}'";
		!empty($params['end_month'])       && $_condition[] = "created_month <= '{$params['end_month']}'";
		!empty($params['warehouse_id'])    && $_condition[] = "warehouse_id = '{$params['warehouse_id']}'";
		!empty($params['product_sn'])      && $_condition[] = "p.product_sn = '{$params['product_sn']}'";
		!empty($params['product_name'])    && $_condition[] = "p.product_name like '%{$params['product_name']}%'";

		$_join[] = " LEFT JOIN `shop_product` p on r.product_id = p.product_id";

		$_group_by = "warehouse_product";

		return array($_condition, $_join, $_group_by);
	}
	
	/**
     * 添加结算数据
     *
     * @param    array  
     *
     * @return   boolean
     */
	public function add($params)
	{
		if (count($params) < 1) {
			$this->_error = '参数为空';
			return false;
		}

		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());             
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		$sql = "INSERT INTO {$this->_table_consign}(`warehouse_id`, `product_id`, `number`, `created_month`, `warehouse_product`) 
				VALUES('{$params['warehouse_id']}', '{$params['product_id']}', '{$params['number']}', '{$params['created_month']}', '{$params['warehouse_product']}')
				ON DUPLICATE KEY UPDATE  number=number+'{$params['number']}'";
		return (bool) $this->_db->execute($sql);
	}

	/**
     * 添加结算数据
     *
     * @param    array  
     *
     * @return   boolean
     */
	public function update($id, $params)
	{
		$id = intval($id);
		if ($id < 1) {
			$this->_error = '结算ID不正确';
			return false;
		}
		if (count($params) < 1) {
			$this->_error = '参数为空';
			return false;
		}

		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());             
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		$_condition[] = "consign_result_id = '{$id}'";

		return (bool)$this->_db->update($this->_table_consign, $params, $_condition);
	}

	/**
     * 插入结算日志
     *
     * @param    array  
     *
     * @return   boolean
     */
	public function addLog($params)
	{
		return (bool) $this ->_db->insert($this->_table_consign_log, $params);
	}

	/**
     * 获取LOG
     *
     * @param    int
     *
     * @return   array
     */
	public function getLogByResultId($result_id)
	{
		$result_id = intval($result_id);
		if ($result_id < 1) {
			$this->_error = '结算ID不正确';
			return false;
		}

		$sql = "SELECT `log_id`, `consign_result_id`, `product_id`, `number`, `created_id`, `created_by`, `created_ts` 
				FROM {$this->_table_consign_log} WHERE `consign_result_id` = '{$result_id}'";
		return $this->_db->fetchAll($sql);
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