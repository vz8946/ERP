<?php
class Admin_Models_DB_ProductApply
{
	private $_db = null;

	private $_table_product_apply = 'shop_product_apply';

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
		//$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}

	/**
     * 根据申请ID获取数据
     *
     * @param    int
     *
     * @return   array
     */
	public function get($product_apply_id)
	{
		$product_apply_id = intval($product_apply_id);
		if ($product_apply_id < 1) {
			$this->_error = '产品申请ID不正确';
			return false;
		}
		$field = array(
			'product_apply_id',
			'shop_id',
			'product_id',
			'product_sn',
			'product_name',
			'type',
			'price_limit',
			'start_ts',
			'end_ts',
			'remark',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_product_apply}` WHERE product_apply_id = '{$product_apply_id}' limit 1";

		return $this->_db->fetchRow($sql);
	}
	
	/**
     * 获取产品申请列表
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
			'product_apply_id',
			'shop_id',
			'product_id',
			'product_name',
			'product_sn',
			'type',
			'start_ts',
			'end_ts',
			'remark',
			'price_limit',
			'created_by',
			'created_ts',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `{$this->_table_product_apply}` WHERE ". implode(' AND ', $_condition) ." ORDER BY product_apply_id desc limit {$limit}";

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

		$sql = "SELECT count(*) as count FROM `{$this->_table_product_apply}` WHERE ". implode(' AND ', $_condition);

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
		!empty($params['start_ts'])     && $_condition[] = "start_ts >= '{$params['start_ts']} 00:00:00'";
		!empty($params['end_ts'])       && $_condition[] = "end_ts <= '{$params['end_ts']} 23:59:59'";
		!empty($params['type'])         && $_condition[] = "type = '{$params['type']}'";
		!empty($params['shop_id'])      && $_condition[] = "shop_id = '{$params['shop_id']}'";
		!empty($params['product_sn'])   && $_condition[] = "product_sn = '{$params['product_sn']}'";
		!empty($params['product_name']) && $_condition[] = "product_name like '%{$params['product_name']}%'";

		return $_condition;
	 }

	/**
     * 添加数据
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
			'shop_id'      => $param['shop_id'],
			'product_id'   => $param['product_id'],
			'product_sn'   => $param['product_sn'],
			'product_name' => $param['product_name'],
			'type'         => $param['type'],
			'price_limit'  => $param['price_limit'],
			'start_ts'     => $param['start_ts'],
			'end_ts'       => $param['end_ts'],
			'remark'       => $param['remark'],
			'created_id'   => $param['created_id'],
			'created_by'   => $param['created_by'],
		);

		if (false ===  $this->_db->insert($this->_table_product_apply, $param)) {
			$this->_error = '插入产品价格申请失败';
			return false;
		}

		return $this->_db->lastInsertId();
	 }

	/**
     * 获取店铺数据
     *
     *
     * @return   array
     */
	public function getShopOption()
	{
		$sql = "SELECT `shop_id`, `shop_name` FROM `shop_shop` WHERE status='0'";

		$infos = $this->_db->fetchAll($sql);

		if (count($infos) < 1) {
			return array();
		}

		$shop_infos = array();
		foreach ($infos as $info) {
			$shop_infos[$info['shop_id']] = $info['shop_name'];
		}

		return $shop_infos;
	}

	/**
     * 根据条件获取数据
     *
     * @param    array  
     *
     * @return   array
     */
	public function getInfoByCondition($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		$_condition[] = "is_deleted='0'";
		!empty($params['start_ts'])   && $_condition[] = "start_ts < '{$params['start_ts']}' AND end_ts > '{$params['start_ts']}'";
		!empty($params['shop_id'])    && $_condition[] = "shop_id = '{$params['shop_id']}'";
		!empty($params['product_id']) && $_condition[] = "product_id = '{$params['product_id']}'";
		!empty($params['product_sn']) && $_condition[] = "product_sn = '{$params['product_sn']}'";
		!empty($params['type'])       && $_condition[] = "type = '{$params['type']}'";
		$sql = "SELECT product_apply_id, shop_id, product_id, type, product_sn, price_limit FROM `shop_product_apply` WHERE ". implode(' AND ', $_condition) . " order by price_limit ASC limit 1";

		return $this->_db->fetchRow($sql);

	}

	/**
	 * 更新限价申请数据
	 *
	 * @return    array
	 **/
	public function edit($apply_id, $params)
	{
		$apply_id = intval($apply_id);
		if ($apply_id < 1) {
			$this->_error = '申请ID不正确';
			return false;
		}

		$params = array(
			'start_ts'    => substr($params['start_ts'], 0, 10) . ' 00:00:00',	
			'end_ts'      => substr($params['end_ts'], 0, 10) . ' 23:59:59',
			'price_limit' => floatval($params['price_limit']),
		);

		if (false === $this->_db->update($this->_table_product_apply, $params, "product_apply_id = '{$apply_id}'")) {
			$this->_error = '更新失败';
			return false;
		}

		return true;
	}

    /**
     * 获取订单超限价记录总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getPricelimitLogCount($params)
	 {	
		$_condition = $this->getPricelimitLogCondition($params);

		$sql = "SELECT count(*) as count FROM `shop_order_pricelimit_log` WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

     /**
     * 获取产品申请列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function getPricelimitLogs($params, $limit)
	 {	
		$_condition = $this->getPricelimitLogCondition($params);

		$field = array(
			'log_id',
			'shop_id',
			'channel_type',
			'product_id',
			'product_sn',
			'product_type',
			'batch_sn',
			'price_order',
			'price_limit',
			'audit_id',
			'audit_by',
			'audit_ts',
            'avg_price',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_order_pricelimit_log` WHERE ". implode(' AND ', $_condition) ."  limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getPricelimitLogCondition($params)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		
		$_condition[] = "1=1";
		!empty($params['shop_id'])      && $_condition[] = "shop_id = '{$params['shop_id']}'";
        !empty($params['channel_type']) && $_condition[] = "channel_type = '{$params['channel_type']}'";
        !empty($params['product_sn'])   && $_condition[] = "product_sn = '{$params['product_sn']}'";
        !empty($params['batch_sn'])     && $_condition[] = "batch_sn = '{$params['batch_sn']}'";

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