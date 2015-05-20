<?php

class Admin_Models_API_ProductApply
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
		$this->_db = new Admin_Models_DB_ProductApply();
		$this->_auth = Admin_Models_API_Auth::getInstance()->getAuth();
		$this->_search_option = array(
			'type' => array(
				'1' => '产品',
				'2' => '组合商品',
			),
			'shop_info' => $this->_db->getShopOption(),
            'channel_type' => array(
                '1' => '官网',
                '2' => '渠道',
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
     * 根据申请获取数据
     *
     * @param    int
     *
     * @return   array
     */
	public function get($apply_id)
	{
		if (intval($apply_id) < 1) {
			$this->_error = '申请ID不正确';
			return false;
		}

		if (false === ($info = $this->_db->get($apply_id))) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($info) < 1) {
			return array();
		}

		foreach ($info as $key => $inf) {
			if ($key == 'shop_id') {
				$info['shop_name'] = $this->_search_option['shop_info'][$inf];
			}

		}

		return $info;
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
		$infos = $this->_db->browse($params, $limit);

		if (false === $infos) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

		/*$product_ids = array();
		$group_ids   = array();

		foreach ($infos as $info) {
			if ($info['type'] == '1') {
				$product_ids[] = $info['product_id'];
			} else if ($info['type'] == '2') {
				$group_ids[]   = $info['product_id'];	
			}
		}

		$product_infos = array();
		$group_infos   = array();
		if (count($product_ids) > 0) {
			$product_db    = new Admin_Models_DB_Product();
			$product_infos = $product_db->getProductInfosByProductIds($product_ids);
		}

		if (count($group_ids) > 0) {
			$group_db    = new Admin_Models_DB_GroupGoods();
			$group_infos = $group_db->getGroupInfosByGroupIds($group_ids);
		}*/

		foreach ($infos as &$info) {
			$info['shop_name'] = $this->_search_option['shop_info'][$info['shop_id']];

			/*if ($info['type'] == '1') {
				$info['product_sn']   = $product_infos[$info['product_id']]['product_sn'];
				$info['product_name'] = $product_infos[$info['product_id']]['product_name'];
			} else if ($info['type'] == '2') {
				$info['product_sn']   = $group_infos[$info['product_id']]['group_sn'];
				$info['product_name'] = $group_infos[$info['product_id']]['group_goods_name'];
			}*/

			$info['type'] = $this->_search_option['type'][$info['type']];
		}

		return $infos;
	 }

	 /**
     * 获取总数
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

	public function add($params)
	{

		if ($params['shop_id'] < 1) {
			$this->_error = '请选择店铺';
			return false;
		}
		if (count($params['product_id']) < 1 && count($params['group_id']) < 1) {
			$this->_error = '没有添加的商品';
			return false;
		}

		if ($params['end_ts'] < $params['start_ts']) {
			$this->_error = '活动结束时间不能小于开始时间';
			return false;
		}

		if ($params['end_ts'] < date('Y-m-d')) {
			$this->_error = '活动结束时间不能小于今天';
			return false;
		}

		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		// 插入商品组数据

		if (count($params['group_id']) > 0) {
			$group_db    = new Admin_Models_DB_GroupGoods();
			$group_infos = $group_db->getGroupInfosByGroupIds($params['group_id']);
			foreach ($params['group_id'] as $group_id) {
				/*$group_params = array(
					'product_id' => $group_id,
					'shop_id'    => $shop_id,
					'type'       => 2,
					'time'       => date('Y-m-d H:i:s'),
				);
				$info = $this->_db->getInfoByCondition($group_params);
				if (!empty($info)) {
				} else {*/
				$price_limit = floatval($params['addg'][$group_id]['price_limit']);
				if (empty($price_limit)) {
					continue;
				}
				$insert_params = array(
					'shop_id'      => $params['shop_id'],
					'product_id'   => $group_id,
					'product_sn'   => $group_infos[$group_id]['group_sn'],
					'product_name' => $group_infos[$group_id]['group_goods_name'],
					'type'         => '2',
					'price_limit'  => $params['addg'][$group_id]['price_limit'],
					'start_ts'     => $params['start_ts'] . ' 00:00:00',
					'end_ts'       => $params['end_ts'] . ' 23:59:59',
					'remark'       => $params['remark'],
					'created_id'   => $this->_auth['admin_id'],
					'created_by'   => $this->_auth['admin_name'],
				);
				if ($this->_db->add($insert_params) < 1) {
					$this->_error = '插入数据失败!';
					return false;
				}

				//}
			}
		}

		// 插入商品数据
		if (count($params['product_id']) > 0) {
			$product_db    = new Admin_Models_DB_Product();
			$product_infos = $product_db->getProductInfosByProductIds($params['product_id']);
			foreach ($params['product_id'] as $product_id) {
				/*$product_params = array(
					'product_id' => $product_id,
					'shop_id'    => $shop_id,
					'type'       => 1,
					'time'       => date('Y-m-d H:i:s'),
				);

				$info = $this->_db->getInfoByCondition($product_params);
				if (!empty($info)) {
				} else {*/
				/*if (empty($params['add'][$product_id]['price_limit'])) {
					continue;
				}*/
				$insert_params = array(
					'shop_id'      => $params['shop_id'],
					'product_id'   => $product_id,
					'product_sn'   => $product_infos[$product_id]['product_sn'],
					'product_name' => $product_infos[$product_id]['product_name'],
					'type'         => '1',
					'price_limit'  => floatval($params['add'][$product_id]['price_limit']),
					'start_ts'     => $params['start_ts'] . ' 00:00:00',
					'end_ts'       => $params['end_ts'] . ' 23:59:59',
					'remark'       => $params['remark'],
					'created_id'   => $this->_auth['admin_id'],
					'created_by'   => $this->_auth['admin_name'],
				);
				if ($this->_db->add($insert_params) < 1) {
					$this->_error = '插入数据失败!';
					return false;
				}

				//}
			}
		}

		return true;
	}

	/**
	 * 产品限价汇总
	 *
	 * @return    array
	 **/
	public function getProductLimitPriceTotal()
	{

		$collects = array();
		$goods_db       = new Admin_Models_DB_Goods();
		$group_goods_db = new Admin_Models_DB_GroupGoods();
		$outtuan_db     = new Admin_Models_DB_OutTuan();
		$shop_db        = new Admin_Models_API_Shop();
		$collects['product_total']     = $goods_db->getProductLimitPriceTotal();
		$collects['group_goods_total'] = $group_goods_db->getGroupGoodsLimitPriceTotal();
		$collects['outtuan_total']     = $outtuan_db->getOuttuanGoodsLimitPriceTotal();
		$collects['shop_total']        = $shop_db->getShopGoodsLimitPriceTotal();
		$collects['total']             = $collects['product_total'] + $collects['group_goods_total'] + $collects['outtuan_total'] + $collects['shop_total'];
		return $collects;
	}

    /**
	 * 订单限价汇总
	 *
	 * @return    array
	 **/
	public function getOrderLimitPriceTotal()
	{
		$collects = array();
		$order_db = new Admin_Models_DB_Order();
		$shop_db  = new Admin_Models_API_Shop();
		$collects['order_total'] = $order_db->getOrderLimitPriceTotal();
		$collects['shop_total']  = $shop_db->getShopOrderLimitPriceTotal();
		$collects['total']       = $collects['order_total'] + $collects['shop_total'];
		return $collects;
	}

	/**
	 * 更新限价申请数据
	 *
	 * @return    array
	 **/
	public function edit($apply_id, $params)
	{
		if (intval($apply_id) < 1) {
			$this->_error = '申请ID不正确';
			return false;
		}
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		if (false === $this->_db->edit($apply_id, $params)) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return true;
	}

    /**
     * 获取限价LOG总数
     *
     * @param    array  
     *
     * @return   int
     */
    public function getPricelimitLogCount($params)
	{	
		$count = $this->_db->getPricelimitLogCount($params);

		if (false === $count) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $count;
	}

    /**
     * 获取订单限价log
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function getPricelimitLogs($params, $limit)
	 {	
		$infos = $this->_db->getPricelimitLogs($params, $limit);

		if (false === $infos) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

        foreach ($infos as &$info) {
            $info['channel_type'] = $this->_search_option['channel_type'][$info['channel_type']];
            $info['product_type'] = $this->_search_option['type'][$info['product_type']];
            $info['shop_name']    = $this->_search_option['shop_info'][$info['shop_id']];
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