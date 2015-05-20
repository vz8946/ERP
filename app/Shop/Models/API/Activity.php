<?php
 class Shop_Models_API_Activity
 {
	/**
     * 活动参数名称
     * 
     * @var    string
     */
 	private $_actName = 'a';

 	/**
     * 对象初始化
     *
     * @return void
     */
    public function __construct($actId = null)
    {
    	$this -> _actId= $actId ? $actId : (int)Zend_Controller_Front::getInstance() -> getRequest() -> getParam($this -> _actName);
    }
    
    /**
     * 保存活动参数
     *
     * @param	void
     * @return  void
     **/
    public function setActivityCookie()
    {
    	if ($this -> _actId) {
			$time = time() + 84600 * 10;//保存10天
		    setcookie($this -> _actName,$this -> _actId, $time, '/');
    	}
    }

    /**
     * 从COOKIE获取活动参数
     *
     * @param   void
     * @return int
     **/
    public function getActivityCookie($returnArr = false)
    {
        if ($_COOKIE[$this -> _actName]) {
            $_actId = $_COOKIE[$this -> _actName];
            if (isset($_actId)) {
                return $_actId ;
            } else {
                setcookie($this -> _actName, '', -1, '/');
                return null;
            }
        }
    }
    
    /**
     * 获取购物车cookie中的普通商品及数量
     *
     * @return  array
     */
    public function getCartInfo()
    {
        if ($_COOKIE['cart']) {
            $tmp = explode('|', $_COOKIE['cart']);
            if (is_array($tmp) && count($tmp)) {
            	$goods = new Shop_Models_API_Goods();
                foreach ($tmp as $temp) {
                    $item = explode(',', $temp);
                    $productID = $item[0];
                    $productNumber = $item[1];
                    if ($productNumber > 0) {
                        $productInfo[$productID] = $productNumber;
                    }
                }
                $goodsIds = $goods -> getProduct("a.product_id in(" . implode(',', array_keys($productInfo)) . ")", 'b.goods_id,a.product_id');
                foreach ($goodsIds as $key => $value)
                {
                	$goodsInfo[$value['goods_id']] += $productInfo[$value['product_id']];
                }
            }
        }
        $goodsInfo = is_array($goodsInfo) ? $goodsInfo : array();
        $productInfo = is_array($productInfo) ? $productInfo : array();
        return array('productInfo' => $productInfo, 'goodsInfo' => $goodsInfo);
    }
    
    /**
     * 删除推荐COOKIE
     * 
     * @param   void
     * @return int
     **/
    public function deleteCookie()
    {
        if ($_COOKIE[$this -> _actName]) {
    	    setcookie($this -> _actName, '', 0, '/');
        }
    }
}