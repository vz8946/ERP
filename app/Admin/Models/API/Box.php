<?php

class Admin_Models_API_Box
{
	private $_db = null;
	private $_error;
	private $_search_option;
	private $_box_sn = 'BX00000';
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this->_db = new Admin_Models_DB_Box();
		$this->_auth = Admin_Models_API_Auth::getInstance()->getAuth();
		$this->_search_option = array(
		);
	}

	/**
     * 返回搜索参数
	 *
     * @return   string
     */
	public function getSearchOption()
	{
		return $this->_search_option;
	}

	/**
     * 根据箱子ID获取消息
     *
     * @param    int
     *
     * @return   array
     */
	public function get($box_id)
	{
		if (intval($box_id) < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		if (false === ($info = $this->_db->get($box_id))) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($info) < 1) {
			return array();
		}

		return $info;
		
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
		$infos = $this->_db->browse($params, $limit);

		if (false === $infos) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

		foreach ($infos as &$info) {
			$info['product_count'] = $this->_db->getProductCountByBoxid($info['box_id']);
			$info['product_sum']   = intval($this->_db->getSumProductByBoxid($info['box_id']));
		}

		return $infos;
	 }


	 public function add($params)
	 {
		if (intval($params['number']) < 1) {
			$this->_error = '箱子数量不正确';
			return false;
		}

		$box_info = $this->_db->getLastInfo();

		$box_sn = !empty($box_info['box_sn']) ? $box_info['box_sn'] : $this->_box_sn;

		for ($i =0; $i < intval($params['number']); $i++) {
			$box_sn = substr($box_sn, 0, 2) . substr('00000'. (intval(substr($box_sn, 2)) + 1), -5);
			$box_param = array(
				'box_sn'     => $box_sn,
				'remark'     => strval($params['remark']),
				'created_id' => $this->_auth['admin_id'],
			);
			$this->_db->add($box_param);
		}

		return true;
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
		$count = $this->_db->getCount($params);

		if (false === $count) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $count;
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
		return $this->_db->getAllBoxProducts($params);
	 }

	/**
     * 获取箱子相关统计
     *
     * @param    array  
     *
     * @return   int
     */
	public function getProductCountsByBoxid($box_id)
	{
		if (intval($box_id) < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		$product_sku_counts = $this->_db->getProductCountByBoxid($box_id);
		if ($product_sku_counts < 1) {
			return array(0,0);
		}

		$sum_skus = $this->_db->getSumProductByBoxid($box_id);

		return array($product_sku_counts, $sum_skus);
	}

	/**
     * 添加产品
     *
	 * @param    int
     * @param    array  
     *
     * @return   boolean
     */
	public function addProduct($box_id, $params)
	{
		if (intval($box_id) < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		$this->_db->deleteProductsByBoxid($box_id);

		if (empty($params['product'])) {
			return true;
		}


		foreach ($params['product'] as $product) {
			$ids = explode('_', $product);
			$product_params = array(
				'box_id'     => $box_id,
				'product_id' => $ids[1],
				'number'     => $ids[2],
				'barcode'    => $ids[0],
			);
			$this->_db->addProduct($product_params);
		}

		return true;
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
		if (intval($box_id) < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		return $this->_db->getProductInfosByBoxid($box_id);
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
		if (intval($product_id) < 1) {
			$this->_error = '产品ID不正确';
			return false;
		}

		return $this->_db->getProductInfoByProductid($product_id);
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
		if (intval($box_id) < 1) {
			$this->_error = '箱子ID不正确';
			return false;
		}

		return $this->_db->deleteBox($box_id);
	}

	/**
	 * 取得键的值
     *
	 * @param    array
	 * @param    string
	 *
	 * @return   array
	 */
	public function getSingleKey($array , $key)
	{
		$array2 = array();
		foreach ($array as $val) {
			isset($val[$key]) && $array2[$val[$key]] = $val[$key];
		}

		return $array2;
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