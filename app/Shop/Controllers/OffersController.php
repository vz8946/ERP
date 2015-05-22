<?php

class OffersController extends Zend_Controller_Action
{
	/**
     * 活动 API
     * 
     * @var Shop_Models_API_Offers
     */
	private $_offers = null;
	
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _offers = new Shop_Models_API_Offers();
	}
	
	/**
     * 选择商品
     *
     * @return void
     */
    public function selectGoodsAction()
    {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
    	$goodsMessage = $this -> _offers -> getGoods($page, $this -> _request -> getParams(), 2);
        $pageNav = new Custom_Model_PageNav($goodsMessage['total'], 2, 'ajax_search');
        $this -> view -> productid = (int)$this -> _request -> getParam('pid');
        $this -> view -> boxid = $this -> _request -> getParam('bid');
        $this -> view -> expire = ($goodsMessage['offers']['expire'] |$goodsMessage['offers']['expire'] == '0') ?  $goodsMessage['offers']['expire'] + 1 : 365;
        if ($this -> _request -> getParam('index') === '')  $this -> view -> index = '';
        else $this -> view -> index = $this -> _request -> getParam('index');
        $this -> view -> offer_id = $this -> _request -> getParam('offer_id');
        $this -> view -> goodsMessage = $goodsMessage['datas'];
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> param = $this -> _request -> getParams();
    }
    
    /**
     * 选择组合商品
     *
     * @return void
     */
    public function selectGroupGoodsAction()
    {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
    	$goodsMessage = $this -> _offers -> getGroupGoods($page, $this -> _request -> getParams(), 2);
        $pageNav = new Custom_Model_PageNav($goodsMessage['total'], 2, 'ajax_search');
        $this -> view -> productid = (int)$this -> _request -> getParam('pid');
        $this -> view -> boxid = $this -> _request -> getParam('bid');
        $this -> view -> expire = ($goodsMessage['offers']['expire'] |$goodsMessage['offers']['expire'] == '0') ?  $goodsMessage['offers']['expire'] + 1 : 365;
        if ($this -> _request -> getParam('index') === '')  $this -> view -> index = '';
        else $this -> view -> index = $this -> _request -> getParam('index');
        $this -> view -> offer_id = $this -> _request -> getParam('offer_id');
        $this -> view -> goodsMessage = $goodsMessage['datas'];
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> param = $this -> _request -> getParams();
    }
    
    /**
     * 获取商品
     *
     * @return void
     */
    public function getProductAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('product_id', null);
    	$sn = $this -> _request -> getParam('product_sn', null);
    	if ($id || $sn) {
    		$goodsApi = new Shop_Models_API_Goods();
    		if ($id) {
    	        $product = array_shift($goodsApi -> getProduct("a.product_id = '{$id}'", "a.product_id,b.goods_id,a.product_sn,b.goods_name,b.price,b.goods_img"));
    	    }
    	    else {
    	        $product = array_shift($goodsApi -> getProduct("a.product_sn = '{$sn}'", "a.product_id,b.goods_id,a.product_sn,b.goods_name,b.price,b.goods_img"));
    	    }
    	    if (!$product) {
    	        exit;
    	    }
    		$stockAPI = new Admin_Models_API_Stock();
    	    if (!$stockAPI -> checkPreSaleProductStock($product['product_id'], 1)) {
    	        exit('outofstock');
    	    }
    		
	        exit(Zend_Json::encode($product));
	    } else {
        	exit;
        }
    }
    
    /**
     * 获取组合商品
     *
     * @return void
     */
    public function getGroupGoodsAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
    	$group_id = (int)$this -> _request -> getParam('group_id', null);
    	
    	$groupGoodsAPI = new Shop_Models_API_GroupGoods();
    	$datas = $groupGoodsAPI -> fetchConfigGoods(array('group_id' => $group_id));
    	$stockAPI = new Admin_Models_API_Stock();
    	foreach ($datas as $data) {
    	    if (!$stockAPI -> checkPreSaleProductStock($data['product_id'], $data['number'])) {
    	        exit('outofstock');
    	    }
    	}
        
        $data = array_shift($groupGoodsAPI -> get("group_id = {$group_id}", '*', null, null, 0));
    	
    	$result['goods_name'] = $data['group_goods_name'];
    	$result['product_id'] = $data['group_id'];
    	$result['goods_img'] = $data['group_goods_img'];
    	
    	exit(Zend_Json::encode($result));
    }
    
    /**
     * 由指定分类获取商品
     *
     * @return void
     */
    function selectGoodsByCatAction()
    {
        $catId = 0;
        $expire = -1;
        if (!$catId) return '';

        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
    	$goodsMessage = $this -> _offers -> getGoodsByCat($catId, $page, $this -> _request -> getParams(), 2);
        $pageNav = new Custom_Model_PageNav($goodsMessage['total'], 2, 'ajax_search');
        $this -> view -> productid = (int)$this -> _request -> getParam('pid',0);
        $this -> view -> boxid = (int)$this -> _request -> getParam('bid',0);
        $this -> view -> expire = $expire;
        $this -> view -> goodsMessage = $goodsMessage['datas'];
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> param = $this -> _request -> getParams();
        $this -> render('select-goods');
    }
}