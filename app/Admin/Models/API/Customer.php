<?php

class Admin_Models_API_Customer
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
		$this->_db = new Admin_Models_DB_Customer();
		$product_apply = new Admin_Models_DB_ProductApply();
		$this->_auth = Admin_Models_API_Auth::getInstance()->getAuth();
		$this->_search_option = array(
			'shop_info' => $product_apply->getShopOption(),
			'order_status' => array(
				'0' => '正常单',
			    '1' => '取消单',
				'2' => '无效单',
			),

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
     * 获取客户数据
     * 
     * @param    int
     *
     * @return   array
     */
	public function get($customer_id)
	{
		if (intval($customer_id) < 1) {
			$this->_error = '客户ID不正确';
			return false;
		}

		if (false === ($info = $this->_db->get($customer_id))) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($info) < 1) {
			return array();
		}

		return $info;
	}
	
	/**
     * 获取客户列表
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

		$order_db = new Admin_Models_DB_Order();

		foreach ($infos as &$info) {
			$order_info = $order_db->getCustomerOrderCountByCustomerId($info['customer_id']);
			if (!empty($order_info)) {
				$info['order_count'] = $order_info['order_count'];
				$info['price_order'] = $order_info['price_order'];
				$info['number']      = $order_info['number'];
			} else {
				$info['order_count'] = 0;
				$info['price_order'] = 0;
				$info['number']      = 0;
			}

			$info['province_name'] = $order_db->getAreaName($info['province_id']);

			$info['shop_name'] = $this->_search_option['shop_info'][$info['shop_id']];

            !empty($info['telphone']) && $info['telphone'] = substr($info['telphone'], 0, -4).'****';
            !empty($info['mobile'])   && $info['mobile'] = substr($info['mobile'], 0, -4).'****';
		}

		return $infos;
	 }

	 /**
     * 获取客户总数
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
     * 获取客户产品列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function browseCustomerProducts($params, $limit)
	 {
		$order_db = new Admin_Models_DB_Order();
		$infos = $order_db->browseCustomerProducts($params, $limit);

		if (false === $infos) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

		foreach ($infos as &$info) {
			$info['created_ts'] = date('Y-m-d H:i:s', $info['add_time']);
		}

		return $infos;
	 }

	 /**
     * 获取客户商品总数
     *
     * @param    array  
     *
     * @return   int
     */
    public function getCustomerProductCount($params)
	{
		$order_db = new Admin_Models_DB_Order();
		$count = $order_db->getCustomerProductCount($params);

		if (false === $count) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $count;
	}

	/**
     * 获取客户订单列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function BrowseCustomerOrderInfos($params, $limit)
	 {
		$order_db = new Admin_Models_DB_Order();
		$infos = $order_db->BrowseCustomerOrderInfos($params, $limit);

		if (false === $infos) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

		foreach ($infos as &$info) {
			$info['created_ts']   = date('Y-m-d H:i:s', $info['add_time']);
			$info['order_status'] = $this->_search_option['order_status'][$info['status']];
		}

		return $infos;
	 }

	/**
     * 获取客户订单总数
     *
     * @param    array  
     *
     * @return   int
     */
    public function getCustomerOrderCount($params)
	{
		$order_db = new Admin_Models_DB_Order();
		$count = $order_db->getCustomerOrderCount($params);

		if (false === $count) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $count;
	}

	/**
	 * 根据产品编码查客户信息
	 *
	 * @param    string
	 * @param    array
	 *
	 * @return   array
	 **/
	public function getProductCustomerByProductSn($product_sn, $params)
	{
		$product_sn = trim($product_sn);

		if (empty($product_sn)) {
			$this->_error = '产品编码不正确';
			return false;
		}

		$order_db = new Admin_Models_DB_Order();

		$infos = $order_db->getProductCustomerByProductSn($product_sn, $params);

		if (empty($infos)) {
			return array();
		}

		foreach ($infos as &$info) {
			$info['shop_name']     = $this->_search_option['shop_info'][$info['shop_id']];
			$info['buy_count']     = count(explode(',', $info['batch_sns']));
			$info['buy_number']    = $info['number'] - $info['return_number'];
            $info['province_name'] = $order_db->getAreaName($info['province_id']);
            !empty($info['telphone']) && $info['telphone'] = substr($info['telphone'], 0, -4).'****';
            !empty($info['mobile'])   && $info['mobile'] = substr($info['mobile'], 0, -4).'****';
        }

		return $infos;
	}

    /**
     * 根据条件获取客户购买的订单统计
     * 
     * @param    params
	 *
     * @return   array
     */
	public function getCustomerOrderCountByCondition($params)
    {
        $params['end_ts'] = !empty($params['end_ts']) ? $params['end_ts'] . ' 23:59:59' : '';
        $order_db = new Admin_Models_DB_Order();

        $infos = $order_db->getCustomerOrderCountByCondition($params);

        if (empty($infos)) {
			return array();
		}

        foreach ($infos as &$info) {
            $info['shop_name']     = $this->_search_option['shop_info'][$info['shop_id']];
            $info['province_name'] = $order_db->getAreaName($info['province_id']);
            $info['order_time']    = date('Y-m-d H:i:s', $info['add_time']);
            !empty($info['telphone']) && $info['telphone'] = substr($info['telphone'], 0, -4).'****';
            !empty($info['mobile'])   && $info['mobile'] = substr($info['mobile'], 0, -4).'****';
        }

        return $infos;
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