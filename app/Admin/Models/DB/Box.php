<?php
class Admin_Models_DB_Box
{
	private $_db = null;

	private $_table_box = 'shop_box';
	private $_table_box_product = 'shop_box_product';

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
     * 根据箱子ID获取信息
     *
     * @param    int
     *
     * @return   array
     */
	public function get($box_id)
	{
		$box_id = intval($box_id);
		if ($box_id < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}
		$field = array(
			'box_id',
			'box_sn',
			'remark',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_box}` WHERE box_id = '{$box_id}' AND is_deleted = 0 limit 1";
		return $this->_db->fetchRow($sql);
	}

	/**
     * 获取最后一条数据
     *
     * @param    int
     *
     * @return   array
     */
	public function getLastInfo()
	{
		$field = array(
			'box_id',
			'box_sn',
			'remark',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_box}` ORDER BY box_id DESC limit 1";

		return $this->_db->fetchRow($sql);
	}
	
	/**
     * 获取箱子列表
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
			'box_id',
			'box_sn',
			'remark',
			'created_ts',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_box}` WHERE ". implode(' AND ', $_condition) ." ORDER BY box_id desc limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取箱子总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getCount($params)
	 {	
		$_condition = $this->getBrowseCondition($params);

		$sql = "SELECT count(*) as count FROM `{$this->_table_box}` WHERE ". implode(' AND ', $_condition);

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
		!empty($params['box_sn'])   && $_condition[] = "box_sn = '{$params['box_sn']}'";

		return $_condition;
	 }

	 /**
     * 获取所有箱子产品
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getAllBoxProducts($params)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		
		$_condition[] = "detail.is_deleted = '0'";
		!empty($params['start_ts']) && $_condition[] = "created_ts >= '{$params['start_ts']} 00:00:00'";
		!empty($params['end_ts'])   && $_condition[] = "created_ts <= '{$params['end_ts']} 23:59:59'";
		!empty($params['box_sn'])   && $_condition[] = "box_sn = '{$params['box_sn']}'";

		$_join[] = "LEFT JOIN `{$this->_table_box}` b ON detail.box_id = b.box_id";
		$_join[] = "LEFT JOIN `shop_product` p ON detail.barcode = p.ean_barcode";

		$sql = "SELECT `box_sn`, `remark`, `barcode`, `product_sn`, `product_name`, `number` FROM `{$this->_table_box_product}` detail ". implode(' ', $_join) . 
			   " WHERE ". implode(' AND ', $_condition);
		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取箱子SKU种类总数
     *
     * @param    int  
     *
     * @return   int
     */
	 public function getProductCountByBoxid($box_id)
	 {
		$box_id = intval($box_id);
		if ($box_id < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		$sql = "SELECT COUNT(*) as count FROM `{$this->_table_box_product}` WHERE `box_id` = '{$box_id}' AND is_deleted = 0 ORDER BY box_id asc";
		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 获取箱子SKU总数
     *
     * @param    int  
     *
     * @return   int
     */
	 public function getSumProductByBoxid($box_id)
	 {
		$box_id = intval($box_id);
		if ($box_id < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		$sql = "SELECT SUM(number) as number FROM `{$this->_table_box_product}` WHERE `box_id` = '{$box_id}' AND is_deleted = 0";

		return $this->_db->fetchOne($sql);
	 }

	/**
     * 添加信息数据
     *
     * @param    array  
     *
     * @return   array
     */
	 public function add($param)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $param = Custom_Model_Filter::filterArray($param, $filterChain);

		$param = array(
			'box_sn'  => $param['box_sn'],
			'remark'  => $param['remark'],
		);

		if (false ===  $this->_db->insert($this->_table_box, $param)) {
			$this->_error = '插入站内信息失败';
			return false;
		}

		return $this->_db->lastInsertId();
	 }

	/**
     * 批量插入箱子明细数据
     *
     * @param    array  
     *
     * @return   array
     */
	 public function addBoxProductsByBoxId($box_id, $params)
	 {
		$box_id = intval($box_id);
		if ($box_id < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		if (count($params) < 1) {
			$this->_error = '没有要插入的数据';
			return false;
		}


		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		$product_params = array();
		$sql = "INSERT INTO `{$this->_table_box_product}` (`box_id`, `product_id`, `number`) VALUES";
		foreach ($params as $key => $param) {
			$product_params[] = "('{$box_id}', '{$param['product_id']}', '{$param['number']}')";
			if ($key > 0 && $key % 100 == 0) {
				
				$this->_db->execute($sql . implode(',', $product_params));

				$product_params = array();
			}
		}

		if (count($product_params) > 0) {
			$this->_db->execute($sql . implode(',', $product_params));
		}

		return true;
	 }

	/**
     * 根据箱子ID删除产品数据
     *
     * @param    array  
     *
     * @return   array
     */
	 public function deleteProductsByBoxid($box_id)
	 {
		$box_id = intval($box_id);
		if ($box_id < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		$sql = "update `{$this->_table_box_product}` SET `is_deleted` = '1' WHERE box_id = '{$box_id}'";

		return (bool) $this->_db->execute($sql);
	 }

	 /**
     * 添加箱子产品
     *
     * @param    array  
     *
     * @return   array
     */
	 public function addProduct($param)
	 {
		if (count($param) < 1) {
			$this->_error = '没有相关参数';
			return false;
		}

		$sql = "INSERT INTO `{$this->_table_box_product}` (`box_id`, `product_id`, `number`, `barcode`) VALUES('{$param['box_id']}', '{$param['product_id']}', '{$param['number']}', '{$param['barcode']}') ON DUPLICATE KEY UPDATE number = '{$param['number']}', is_deleted = 0";

		return (bool) $this->_db->execute($sql);
	 }

	 /**
     * 根据箱子获取箱子的产品信息
     *
	 * @param    int
     *
     * @return   array
     */
	public function getProductInfosByBoxid($box_id)
	{
		$box_id = intval($box_id);
		if ($box_id < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		$sql = "SELECT `box_id`, detail.`product_id`, `number`, p.product_name,detail.barcode, p.product_sn
				FROM `{$this->_table_box_product}` detail LEFT JOIN `shop_product` p ON detail.barcode = p.ean_barcode
				WHERE detail.is_deleted = 0 AND detail.box_id = '{$box_id}'";
		return $this->_db->fetchAll($sql);
	}

	/**
     * 根据产品ID获取产品信息
     *
	 * @param    int
     *
     * @return   array
     */
	public function getProductInfoByProductid($product_id)
	{
		$product_id = intval($product_id);
		if ($product_id < 1) {
			$this->_error = '产品ID不正确';
			return false;
		}

		$sql = "SELECT d.`box_id`, `product_id`, `number`, b.box_sn FROM `{$this->_table_box_product}` d LEFT JOIN `{$this->_table_box}` b ON d.box_id = b.box_id 
				WHERE d.is_deleted = 0 AND product_id = '{$product_id}' limit 1";
		return $this->_db->fetchRow($sql);
	}

	/**
     * 删除箱子
     *
	 * @param    int
     *
     * @return   boolean
     */
	public function deleteBox($box_id)
	{
		$box_id = intval($box_id);
		if ($box_id < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		$sql = "UPDATE `{$this->_table_box}` SET is_deleted = 1 WHERE box_id = '{$box_id}'";

		return (bool) $this->_db->execute($sql);
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