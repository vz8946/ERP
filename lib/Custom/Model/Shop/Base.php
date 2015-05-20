<?php
class Custom_Model_Shop_Base 
{
	protected $_api;
	protected $_config;
	protected $_orderStartTime;
	protected $_orderEndTime;
	protected $_goods;
	public $_area;
	public $_log;
	public $_startTime;
	
	/**
     * 构造函数
     *
     * @param   array   $config
     * @return  void
     */
	public function __construct($config){
	    $this -> _config = $config;
	}
	
	/**
     * 获得对象实列
     *
     * @param   string  $shopType
     * @return  object
     */
	public static function getInstance($shopType, $config) {
	    $class = 'Custom_Model_Shop_'.ucfirst($shopType);
	    
	    if (class_exists($class)) {
	        return new $class($config);
	    }
	    else {
	        return new Custom_Model_Shop_Base($config);
	    }
	}
	
	/**
     * 生成店铺类型特殊字段
     *
     * @param   int     $shopID
     * @return  void
     */
	public function getConfigField() {
	    return array();
	}
	
	/**
     * 初始化同步日志
     *
     * @return  void
     */
    public function initSyncLog() {
        $this -> _startTime = time();
        $this -> _log = '';
    }
	
	/**
     * 同步商品
     *
     * @return  void
     */
    public function syncGoods() {
        return false;
    }
    
    /**
     * 同步订单
     *
     * @param   array   $area
     * @return  void
     */
    public function syncOrder($area, $startDate, $endDate, $goods = null) {
        $this -> _area = $area;
        $this -> _goods = $goods;

        $this -> _orderStartTime = $startDate ? $startDate : date('Y-m-d', time() - 3600 * 24 * 7);
        $this -> _orderEndTime = $endDate ? $endDate : date('Y-m-d', time());
        $this -> _orderStartTime .= ' 00:00:00';
        $this -> _orderEndTime .= ' 23:59:59';
        if ( strtotime($this -> _orderEndTime) > time() ) {
            $this -> _orderEndTime = date('Y-m-d H:i:s', time());
        }
    }
    
    /**
     * 同步库存
     *
     * @param   array   $area
     * @return  void
     */
    public function syncStock($stockData) {
        return false;
    }
    
    /**
     * 同步单个订单
     *
     * @return  void
     */
    public function syncOneOrder($externalOrderSN) {
        return false;
    }
    
    /**
     * 同步物流信息
     *
     * @param   array   $externalOrderSNArray
     * @return  array
     */
    public function syncOrderLogistic($externalOrderSNArray) {
        return array();
    }
    
    /**
     * 同步发货信息
     *
     * @param   string  $shopOrderSN
     * @param   array   $logistic
     * @return  boolean
     */
    public function syncShopLogistic($shopOrderSN, $logistic) {
        return false;
    }
    
    /**
     * 同步订单签收状态
     *
     * @param   string  $shopOrderSN
     * @return  boolean
     */
    public function syncSignStatus($shopOrderSN) {
        return true;
    }
    
    /**
     * 同步订单拒收状态
     *
     * @param   string  $shopOrderSN
     * @return  boolean
     */
    public function syncRejectStatus($shopOrderSN) {
        return true;
    }
    
    /**
     * 同步订单退货状态
     *
     * @param   string  $shopOrderSN
     * @param   float   $amount
     * @return  boolean
     */
    public function syncReturnStatus($shopOrderSN, $amount) {
        return true;
    }
    
    /**
     * 获得销售单打印模板
     *
     * @return  string
     */
	public static function getPrintTpl() {
	    return 'shop/print';
	}
    
	/**
     * 获得直辖市ID
     *
     * @return  void
     */
    protected function getSpecialAreaID() {
        return array(2, 3, 10, 23);
    }
    
    /**
     * 获得直辖市对应的city
     *
     * @return  void
     */
    protected function getSpecialCity($areaID) {
        $result[2] = array(33, '北京市');
        $result[3] = array(34, '天津市');
        $result[10] = array(105, '上海市');
        $result[23] = array(267, '重庆市');
        
        return $result[$areaID];
    }
    
    /**
     * 获得订单状态
     *
     * @return  void
     */
    public function getOrderStatus($externalOrderSN) {
        return false;
    }
    
    /**
     * 获得评论
     *
     * @return  void
     */
    public function getComment($goodsID) {
        return false;
    }
	
}