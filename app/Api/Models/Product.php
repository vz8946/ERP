<?php
class Api_Models_Product
{
	protected $_db = null;

    private $_error = array();

	public function __construct(){
        $this -> _db = Zend_Registry::get('db');
	}


    /**
     * 获取产品列表
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
			'product_id',
            'product_sn',
            'product_name',
            'cat_id',
            'goods_style',
            'brand_id',
            'suggest_price',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_product`  WHERE ". implode(' AND ', $_condition) ." limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取产品总数
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

		$sql = "SELECT count(*) as count FROM `shop_product`  WHERE ". implode(' AND ', $_condition);

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

        isset($params['sync_status']) && $_condition[] = "sync_status = '{$params['sync_status']}'";

        if (empty($_condition)) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        return $_condition;
    }

    /**
     * 根据产品 IDs更新数据
     *
     * @param    array  
     *
     * @return   array
     */
    public function updateProductInfosByProductIds($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        foreach ($params['product_ids'] as $param) {
            if (empty($param['product_id'])) {
                continue;
            }
            $set = array(
                'sync_status' => 1,    
            );

            $this->_db->update('shop_product', $set, "product_id = '{$param['product_id']}'");
        }

        return true;
    }

    /**
     * 获取产品品类列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function browseProductCat($params, $limit)
	 {	
		$_condition = $this->getBrowseProductCatCondition($params);

		$field = array(
			'cat_id',
            'cat_sn',
            'cat_name',
            'cat_path',
            'parent_id',
            'cat_sort',
            'cat_status',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_product_cat`  WHERE ". implode(' AND ', $_condition) ." limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取产品品类总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getProductCatCount($params)
	 {	
		$_condition = $this->getBrowseProductCatCondition($params);

        if (false === $_condition) {
            return false;
        }

		$sql = "SELECT count(*) as count FROM `shop_product_cat`  WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
    public function getBrowseProductCatCondition($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        isset($params['sync_status']) && $_condition[] = "sync_status = '{$params['sync_status']}'";

        if (empty($_condition)) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        return $_condition;
    }

    /**
     * 根据品类IDs更新品类数据
     *
     * @param    array  
     *
     * @return   array
     */
    public function updateProductcatInfosByCatIds($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        foreach ($params['cat_ids'] as $param) {
            if (empty($param['category_id'])) {
                continue;
            }
            $set = array(
                'sync_status' => 1,    
            );

            $this->_db->update('shop_product_cat', $set, "cat_id = '{$param['category_id']}'");
        }

        return true;
    }



    public function getError()
    {
        return $this->_error;
    }


	
}