<?php

class Admin_Models_API_ConsignResult
{
	private $_db = null;
	private $_error;
	private $_search_option;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this->_db = new Admin_Models_DB_ConsignResult();
		$this->_auth   = Admin_Models_API_Auth::getInstance()->getAuth();
		$this->_config = new Custom_Model_Stock_Config();
		$this->_search_option = array(
			'year' => array(
				'2013',
				'2014',
			),
			'month' => array(
				'01',
				'02',
				'03',
				'04',
				'05',
				'06',
				'07',
				'08',
				'09',
				'10',
				'11',
				'12',
			),
			'warehouses' => $this->_config->_logicArea,
		);
	}

	/**
     * 返回搜索参数
	 *
     * @return   string
     */
	public function getSearchOption()
	{
		foreach ($this->_search_option['year'] as $year) {
			foreach ($this->_search_option['month'] as $month) {
				$this->_search_option['year_month'][$year.$month] = $year.'-'.$month;
			}
		}
		return $this->_search_option;
	}

	/**
     * 获取信息
     *  
     * @param    int
     *
     * @return   array
     */
	public function get($id)
	{
		if (intval($id) < 1) {
			$this->_error = 'ID不正确';
			return false;
		}

		if (false === ($info = $this->_db->get($id))) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $info;
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
		if ($params['collect']) {
			$infos = $this->_db->collectBrowse($params, $limit);
		} else {
			$infos = $this->_db->browse($params, $limit);
		}
		if (false === $infos) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

		foreach ($infos as &$info) {
			$info['warehouse_name'] = $this->_config->_logicArea[$info['warehouse_id']];
		}

		return $infos;
	 }

	/**
     * 获取记录总数
     *
     * @param    array  
     *
     * @return   int
     */
    public function getCount($params)
	{	
		if ($params['collect']) {
			$count = $this->_db->getCollectCount($params);
		} else {
			$count = $this->_db->getCount($params);
		}
		if (false === $count) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $count;
	}

	/**
     * 汇总记录总数
     *
     * @param    array  
     *
     * @return   int
     */
    public function getTotalCounts($params)
	{	
		$counts = $this->_db->getTotalCounts($params);
		if (false === $counts) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $counts;
	}

	/**
     * 插入代销结款数据
     *
     * @param    int
     *
     * @return   array
     */
	public function add($params)
	{
		if (false === $this->_db->add($params)) {
			$this->_error = '插入代销结款数据失败';
			return false;
		}

		return true;
	}

	/**
     * 处理结算产品
     *
     * @param    int
	 * @param    array
     *
     * @return   boolean
     */
	public function dealConsignByConsignId($id, $params)
	{
		$info = $this->_db->get($id);
		if (false === $info) {
			$this->_error = '没有找到相关产品结算数据';
			return false;
		}

		if (intval($params['number']) < 1) {
			$this->_error = '要结算的数量不能小于1';
			return false;
		}

		if ($info['number'] < 1) {
			$this->_error = '产品需结算数量不正确';
			return false;
		}

		$deal_number = $info['deal_number'] + $params['number'] > $info['number'] ? $info['number'] : $info['deal_number'] + $params['number'];

		if (false === $this->_db->update($id, array('deal_number' => $deal_number))) {
			$this->_error = $this->_db->getError();
			return false;
		}

		$log_params = array(
			'consign_result_id' => $id,
			'product_id'        => $info['product_id'],
			'number'            => $params['number'],
			'created_id'        => $this->_auth['admin_id'],
			'created_by'        => $this->_auth['admin_name'],
			'created_ts'        => date('Y-m-d H:i:s'),
		);

		$this->_db->addLog($log_params);

		return true;
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
		if (intval($result_id) < 1) {
			$this->_error = 'ID不正确';
			return false;
		}

		if (false === ($info = $this->_db->getLogByResultId($result_id))) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $info;
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