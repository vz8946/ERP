<?php
class Api_Models_Order
{
	protected $_db = null;

    private $_error = array();

	public function __construct(){
        $this -> _db = Zend_Registry::get('db');
	}


    /**
     * 获取订单列表
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
			'order_batch_id',
            'b.batch_sn',
            'b.add_time',
            'status_return',
            'logistic_time',
            'price_order',
            'c.crm_customer_id as customer_id',
            'rank_id',
            'o.shop_id',
            'is_fav',
            'status',
            'status_logistic',
            'pay_type',
            'status_pay',
            'is_send',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_order_batch` b ". implode(' ', $_join) ."  WHERE ". implode(' AND ', $_condition) ." limit {$limit}";
        try {
		    $infos = $this->_db->fetchAll($sql);
        } catch(Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }
        if (empty($infos)) {
            return array();
        }

        foreach ($infos as &$info) {
            $info['details'] = $this->getOrderGoodsByBatchId($info['order_batch_id']);
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
		list($_condition, $_join) = $this->getBrowseCondition($params);

        if (false === $_condition) {
            return false;
        }

		$sql = "SELECT count(*) as count FROM `shop_order_batch` b ". implode(' ', $_join) ."  WHERE ". implode(' AND ', $_condition);
        try {
		    $count =$this->_db->fetchOne($sql);
        } catch(Exception $e) {
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

        $_condition[] = "is_send = '1'";
        $_condition[] = "c.crm_customer_id >0";
        isset($params['sync_status']) && $_condition[] = "sync_status = '{$params['sync_status']}'";
        
        $_join[] = "LEFT JOIN `shop_order` o ON b.order_id = o.order_id";
        $_join[] = "LEFT JOIN `shop_customer` c ON c.customer_id = o.customer_id";

        if (empty($_condition)) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        return array($_condition, $_join);
    }

    /**
     * 根据订单批次ID获取订单商品
     *
     * @param    string
     *
     * @return   array
     */
    public function getOrderGoodsByBatchId($order_batch_id)
    {
        $field = array(
            'order_batch_goods_id',
            'order_batch_id',
            'product_id',
            'number',
            'return_number',
            'sale_price',
        );
        $sql = "SELECT ". implode(', ', $field) ." FROM `shop_order_batch_goods` WHERE order_batch_id = '{$order_batch_id}'";
        try {
        $infos = $this->_db->fetchAll($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $infos;
    }

    /**
     * 根据产品 IDs更新数据
     *
     * @param    array  
     *
     * @return   array
     */
    public function updateOrderListsByBatchIds($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        foreach ($params['order_batch_ids'] as $param) {
            if (empty($param['order_batch_id'])) {
                continue;
            }
            $set = array(
                'sync_status' => 1,    
            );
            try {
                $this->_db->update('shop_order_batch', $set, "order_batch_id = '{$param['order_batch_id']}'");
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