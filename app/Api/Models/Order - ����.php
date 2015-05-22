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
			'b.batch_sn',
            'b.order_batch_id',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_order_batch` b  ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." limit {$limit}";

		return $this->_db->fetchAll($sql);
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

		$sql = "SELECT count(*) as count FROM `shop_order_batch` b  ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);

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
		
		!empty($params['shop_id']) && $_condition[] = "shop_id = '{$params['shop_id']}'";

        if (empty($_condition)) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

		$_join[] = "LEFT JOIN `shop_order` o on o.order_id = b.order_id";
		return array($_condition, $_join);
	 }

    /**
     * 根据条件查找订单
     *
     * @param    array
     *
     * @return   array
     **/
    public function getOrderInfosByCondition($params)
    {
        !empty($params['shop_id']) && $_condition[] = "shop_id = '{$params['shop_id']}'";

        if (empty($_condition)) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        $sql = "SELECT o.order_sn FROM `shop_order` o LEFT JOIN shop_order_batch b ON o.order_id = b.order_id WHERE ". implode(' AND ', $_condition);

        $infos = $this->_db->fetchAll($sql);

        return $infos;
    }

    public function getOrderGoodsByBatchId($batch_id)
    {
        $batch_id = intval($batch_id);

        if (empty($batch_id)) {
            $this->_error['error'] = '订单ID为空';
            return false;
        }

        $sql = "SELECT order_batch_id, order_batch_goods_id , product_sn FROM `shop_order_batch_goods` WHERE `order_batch_id` = '{$batch_id}'";
 
        return $this->_db->fetchAll($sql);
    }

    public function getError()
    {
        return $this->_error;
    }
	
}