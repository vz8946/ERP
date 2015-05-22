<?php
class Api_Models_Customer
{
	protected $_db = null;

    private $_error = array();

	public function __construct(){
        $this -> _db = Zend_Registry::get('db');
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
		$_condition = $this->getBrowseCondition($params);

		$field = array(
			'customer_id',
            'shop_id',
            'real_name',
            'email',
            'sex',
            'telphone',
            'mobile',
            'first_order_time',
            'last_order_time',
            'province_id',
            'city_id',
            'area_id',
            'created_id',
            'created_by',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_customer`  WHERE ". implode(' AND ', $_condition) ." limit {$limit}";
        try {
		    $infos = $this->_db->fetchAll($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $infos;
	 }

	 /**
     * 获取订单总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getCount($params)
	 {	
		$_condition = $this->getBrowseCondition($params);

        if (false === $_condition) {
            return false;
        }

		$sql = "SELECT count(*) as count FROM `shop_customer`  WHERE ". implode(' AND ', $_condition);

        try {
		    $count = $this->_db->fetchOne($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $count;
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

        isset($params['crm_customer_id']) && $_condition[] = "crm_customer_id = '{$params['crm_customer_id']}'";
        isset($params['gt_mobile'])       && $_condition[] = "(mobile != '' OR telphone != '')";

        if (empty($_condition)) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        return $_condition;
    }

    /**
     * 根据客户ID更新客户信息
     *
     * @param    array  
     *
     * @return   array
     */
    public function updateCustomerInfosByCustomerId($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        foreach ($params['customer_ids'] as $param) {

            if (empty($param['origin_customer_id'])) {
                continue;
            }
            $set = array(
                'crm_customer_id' => $param['crm_customer_id'],    
            );

            try {
                $this->_db->update('shop_customer', $set, "customer_id = '{$param['origin_customer_id']}'");
            } catch (Exception $e) {
                $this->_error['error'] = $e->getMessage();
                return false;
            }
        }

        return true;
    }



    public function getError()
    {
        return $this->_error;
    }
	
}