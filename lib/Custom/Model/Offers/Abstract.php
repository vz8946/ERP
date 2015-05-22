<?php

abstract class Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var string
     */
    protected $_title = null;
    
    public function __construct()
    {
        $union = new Shop_Models_API_Union();
        
        $this -> _uid = $_GET['u'];
    	$this -> _uid = $this -> _uid ? $this -> _uid : $union -> getUidFromCookie();
    	$this -> _aid = $_GET['a'];
    	$this -> _aid = $this -> _aid ? $this -> _aid : $union -> getNidFromCookie();
        if ( $this -> _uid == 11 ) {
            $this -> _aid = substr( $this -> _aid, 0, 10 );
        }
        if ( $this -> _uid == '21' ) {
            $wiNid=explode('|',base64_decode($this -> _aid));
            $this -> _aid = $wiNid['0'];
        }
        
    	$this -> _cart = $union -> getCartInfo();
    }
    
    /**
     * 取得插件title
     *
     * @param    void
     * @return   string
     */
    public static function getTitle()
    {
        return $this -> _title;
    }
    
    /**
     * 取得活动插件名称
     *
     * @param    void
     * @return string
     */
    public function getName()
    {
        $class_name = get_class($this);

        if (strpos($class_name, '_') !== false) {
            return ltrim(strrchr($class_name, '_'), '_');
        } else {
            return $class_name;
        }
    }
    
    /**
     * 转换配置
     *
     * @param    string    $config
     * @return   array
     */
	public function parseGoodsConfig($config)
	{
		if ($config) {
			$result = array();
		    parse_str($config, $result);
		    return $result;
		}
	}
    
    /**
     * 取得商品的设置值
     *
     * @param    int     $goodsId      # 商品Id
     * @param    string  $goodsCats    # 商品分类路径
     * @param    array   $goodsConfig  # 商品配置
     * @return   array
     */
    public function getSelectGoodsConfig($goodsId, $goodsCats, $goodsConfig)
    {
    	$goodsConfig = $this -> parseGoodsConfig($goodsConfig);
    	
    	if ($goodsConfig['goodsDiscount'][$goodsId]) {
    		return $goodsConfig['goodsDiscount'][$goodsId];
    	}
    	
    	if ($goodsConfig['catDiscount']) {
    		$goodsCats = explode(',', trim($goodsCats, ','));
    		rsort($goodsCats);
    		foreach ($goodsCats as $cat)
    		{
    			if (array_key_exists($cat, $goodsConfig['catDiscount'])) {
    				return $goodsConfig['catDiscount'][$cat];
    			}
    		}
    	}
    	
    	if ($goodsConfig['discount']) {
    		return $goodsConfig['discount'];
    	}
    }
    
    /**
     * 取得组合商品的设置值
     *
     * @param    int     $goodsId      # 商品Id
     * @param    array   $goodsConfig  # 商品配置
     * @return   array
     */
    public function getSelectGroupGoodsConfig($groupId, $goodsConfig)
    {
    	$goodsConfig = $this -> parseGoodsConfig($goodsConfig);
    	
    	if ($goodsConfig['goodsDiscount'][$groupId]) {
    		return $goodsConfig['goodsDiscount'][$groupId];
    	}
    	if ($goodsConfig['discount']) {
    		return $goodsConfig['discount'];
    	}
    }
    
    /**
     * 处理并输出显示内容
     *
     * @param    array    $datas    # 商品信息
     * @param    array    $config  # 活动配置
     * @return   string
     */
    public function responseToView($config, $datas = null)
    {
        return $datas;
    }
    
    /**
     * 处理并输出内容至购物车
     *
     * @param    array    $datas    # 购物车信息
     * @param    array    $config   # 活动配置
     * @return   string
     */
    public function responseToCart($config, $datas = null)
    {
        return $datas;
    }
}
