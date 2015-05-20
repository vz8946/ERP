<?php
require_once "global.php";
/**
 * 生成客户数据
 **/
class Generate_customer
{
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}

	/**
	 * 初始化信息
	 *
	 * @return   array
	 **/
	public function dealCustomerInfos($params)
	{
		$this->clearCustomerInfos();
	
		$order_infos = $this->getOrderInfosByCondition($params);

		foreach ($order_infos as $info) {
			if (empty($info['addr_tel']) && empty($info['addr_mobile'])) {
				continue;
			}
			$query_params = array(
				'telphone' => $info['addr_tel'],
				'mobile'   => $info['addr_mobile'],
			);
			$customer_info = $this->getCustomerInfoByCondition($query_params);

			if (empty($customer_info)) {
				$customer_params = array(
					'shop_id'          => $info['shop_id'],
					'real_name'        => $info['addr_consignee'],
					'email'            => $info['addr_email'],
					'telphone'         => $info['addr_tel'],
					'mobile'           => $info['addr_mobile'],
					'first_order_time' => date('Y-m-d H:i:s', $info['add_time']),
					'last_order_time'  => date('Y-m-d H:i:s', $info['add_time']),
					'province_id'      => $info['addr_province_id'],
					'city_id'          => $info['addr_city_id'],
					'area_id'          => $info['addr_area_id'],
					'created_by'       => 'system',
				);

				$customer_id = $this->insertCustomerInfo($customer_params);

			} else {
				$customer_params = array(
					'last_order_time' => date('Y-m-d H:i:s', $info['add_time']),
                    'crm_customer_id' => 0,
				);

				$this->updateCustomerInfoByCustomerId($customer_info['customer_id'], $customer_params);

				$customer_id = $customer_info['customer_id'];
			}

			$order_params = array(
				'customer_id' => $customer_id,	
			);

			$this->updateOrderInfoByOrderId($info['order_id'], $order_params);
		}
		
		return true;
	}

	/**
	 * 获取订单信息
	 *
	 * @return   array
	 **/
	public function getOrderInfosByCondition($params)
	{
		$_field = array(
			'`order_batch_id`',
			'b.`order_id`',
			'o.add_time',
			'addr_consignee',
			'addr_province_id',
			'addr_city_id',
			'addr_area_id',
			'addr_address',
			'addr_tel',
			'addr_mobile',
			'addr_email',
			'shop_id',

		);

		$_condition[] = "(addr_tel != '' or addr_mobile != '')";
		isset($params['customer_id']) && $_condition[] = "customer_id = '{$params['customer_id']}'";

		$_join[] = "LEFT JOIN `shop_order` o ON b.order_id = o.order_id";
		$sql = "SELECT ". implode(',', $_field) ." FROM shop_order_batch b ". implode(' ', $_join) ."  WHERE ". implode(' AND ', $_condition) ." order by o.add_time asc";

		return $this->_db->fetchAll($sql);
	}
	
	/**
	 * 清空客户信息
	 *
	 * @return   boolean
	 **/
	public function clearCustomerInfos()
	{
		/*$sql = "TRUNCATE table `shop_customer`";

		return (bool) $this->_db->execute($sql);*/
	}

	/**
	 * 根据条件获取客户信息
	 *
	 * @return   array
	 **/
	public function getCustomerInfoByCondition($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		$_condition = array();
		if (!empty($params['mobile'])) {
			$_condition[] = "mobile   = '{$params['mobile']}'";
		} else if (!empty($params['telphone'])) {
			$_condition[] = "telphone = '{$params['telphone']}'";
		}

		$sql = "SELECT `customer_id` FROM `shop_customer` WHERE ".implode(' AND ', $_condition) . " LIMIT 1";

		return $this->_db->fetchRow($sql);
	}

	/**
	 * 根据客户ID更新客户信息
	 *
	 * @param    int
	 * @param    array
	 *
	 * @return   boolean
	 **/
	public function updateCustomerInfoByCustomerId($customer_id, $param)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $param = Custom_Model_Filter::filterArray($param, $filterChain);

		$customer_id = intval($customer_id);

		return (bool) $this->_db->update('shop_customer', $param, "customer_id = '{$customer_id}'");

	}

	/**
	 * 插入客户信息
	 *
	 * @param    array
	 *
	 * @return   int
	 **/
	public function insertCustomerInfo($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		$this->_db->insert('shop_customer', $params);

		return $this->_db->lastInsertId();
	}

	/**
	 * 根据客户ID更新客户信息
	 *
	 * @param    int
	 * @param    array
	 *
	 * @return   boolean
	 **/
	public function updateOrderInfoByOrderId($order_id, $param)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $param = Custom_Model_Filter::filterArray($param, $filterChain);

		$order_id = intval($order_id);

		return (bool) $this->_db->update('shop_order', $param, "order_id = '{$order_id}'");

	}


	public function getError()
	{
		return $this->_error;
	}
	
}

$order_params = array(
	'customer_id' => '0',
);
$_api = new Generate_customer();
if (false === $_api->dealCustomerInfos($order_params)) {
	exit($order->getError());
}


exit('操作成功');