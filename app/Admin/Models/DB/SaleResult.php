<?php
class Admin_Models_DB_SaleResult
{
	private $_db = null;

	private $_table_result     = 'shop_sale_result';
	private $_table_result_log = 'shop_sale_result_log';

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
	public function get($sale_result_id)
	{
		$sale_result_id = intval($sale_result_id);
		if ($sale_result_id < 1) {
			$this->_error = '代销结款ID不正确';
			return false;
		}
		$field = array(
			'sale_result_id',
			'r.product_id',
			'number',
			'deal_number',
			'created_month',
			'product_sn',
			'product_name',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_result}` r LEFT JOIN `shop_product` p on r.product_id = p.product_id WHERE sale_result_id = '{$sale_result_id}' limit 1";

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
			'sale_result_id',
			'r.product_id',
			'number',
			'deal_number',
			'created_month',
			'product_sn',
			'product_name',
			'GROUP_CONCAT(distinct(sp.`supplier_id`)) AS supplier_id',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_result}` r ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." GROUP BY created_month,r.product_id ORDER BY sale_result_id desc  limit {$limit}";

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
			'sale_result_id',
			'r.product_id',
			'GROUP_CONCAT(number) as numbers',
			'GROUP_CONCAT(deal_number) as deal_numbers',
			'GROUP_CONCAT(created_month) AS created_month',
			'product_sn',
			'product_name',
			'GROUP_CONCAT(distinct(sp.`supplier_id`)) AS supplier_id',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_result}` r ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) .
			   " GROUP BY {$_group_by} ORDER BY sale_result_id desc limit {$limit}";
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

		$sql = "SELECT count(distinct(r.product_id)) as count FROM `{$this->_table_result}` r ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition).
			   " GROUP BY created_month,r.product_id";

		$infos = $this->_db->fetchAll($sql);

		if (empty($infos)) {
			return 0;
		}
		$total = 0;
		foreach ($infos as $info) {
			$total += $info['count'];
		}

		return $total;
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

		$sql = "SELECT count(distinct($_group_by)) as count FROM `{$this->_table_result}` r ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);

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

		$sql = "SELECT GROUP_CONCAT(number) as numbers, GROUP_CONCAT(deal_number) as deal_numbers, GROUP_CONCAT(created_month) AS created_month FROM `{$this->_table_result}` r ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) . " GROUP BY r.product_id";

		$infos = $this->_db->fetchAll($sql);
		if (empty($infos)) {
			return 0;
		}


		$number       = 0;
		$deal_number  = 0;
		foreach ($infos as $key => $info) {
			$months       = explode(',', $info['created_month']);
			$numbers      = explode(',', $info['numbers']);
			$deal_numbers = explode(',', $info['deal_numbers']);
			$months_infos = array();
			
			foreach ($months as  $ke => $month) {
				if (!isset($months_infos[$month])) {
					$months_infos[$month] = $month;
					$number      += $numbers[$ke];
					$deal_number += $deal_numbers[$ke];
				}
			}
		}
		return array('number' => $number, 'deal_number' => $deal_number);
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
		!empty($params['product_sn'])      && $_condition[] = "p.product_sn = '{$params['product_sn']}'";
		!empty($params['product_name'])    && $_condition[] = "p.product_name like '%{$params['product_name']}%'";
		!empty($params['supplier_id'])     && $_condition[] = "sp.supplier_id = '{$params['supplier_id']}'";

		//$_condition[] = "sp.is_deleted = 0";
		$_join[] = " LEFT JOIN `shop_product` p on r.product_id = p.product_id";
		$_join[] = " LEFT JOIN `shop_supplier_product` sp on r.product_id = sp.product_id";

		$_group_by = "r.product_id";

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

		$sql = "INSERT INTO {$this->_table_result}(`product_id`, `number`, `created_month`) 
				VALUES('{$params['product_id']}', '{$params['number']}', '{$params['created_month']}')
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

		$_condition[] = "sale_result_id = '{$id}'";

		return (bool)$this->_db->update($this->_table_result, $params, $_condition);
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
		return (bool) $this ->_db->insert($this->_table_result_log, $params);
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

		$sql = "SELECT `log_id`, `sale_result_id`, `product_id`, `number`, `created_id`, `created_by`, `created_ts` 
				FROM {$this->_table_result_log} WHERE `sale_result_id` = '{$result_id}'";
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