<?php

class Shop_Models_API_Package
{
	/**
     * 促销活动 API
     * 
     * @var Shop_Models_API_Offers
     */
	private $_offers = null;
	
	/**
     * 礼包 DB
     * 
     * @var Shop_Models_DB_Package
     */
	private $_db = null;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
        $this -> _goods = new Shop_Models_API_Goods();
		$this -> _offers = new Shop_Models_API_Offers();	
	}
	
	/**
     * 根据ID取得礼包信息
     *
     * @param    int    $id
     * @return   void
     */
	public function getPackageById($id)
	{
		$package = $this -> _offers -> getOffersById($id);
		
		if (is_array($package['config']['allGoods'])) {
			foreach ($package['config']['allGoods'] as $key => $value)
			{
				$value && parse_str($value, $package['config']['allGoods'][$key]);
			}
		} elseif ($package['config']['allGoods']) {
			parse_str($package['config']['allGoods'], $package['config']['allGoods']);
		}
		return $package;
	}
	
	/**
     * 根据产品ID取得商品
     *
     * @param    int    $id
     * @return   void
     */
	public function getGoodsByProductId($id)
	{
		$where = "a.product_id='$id'";
	    return @array_shift($this -> _goods -> getProduct($where,"a.product_id,b.goods_id,a.product_sn,b.goods_name,b.price,goods_img"));
	}
	
	/**
     * 根据goods ID取得商品
     *
     * @param    int    $id
     * @return   void
     */
	public function getGoodsById($id)
	{
		return $this -> _goods -> view($id);
	}
	
	/**
     * 判断Cookie中的商品是否在允许范围内
     *
     * @param    int    $id
     * @return   void
     */
	public function ifGoodsByCookie($id, $mixId = null)
	{
		if ($_COOKIE['package_' . $id]) {
			$cookie = explode(',', $_COOKIE['package_' . $id]);
			$package = $this -> getPackageById($id);
			
			//?????
			if ($package['offers_name'] == '商品搭配') {
    			$MixmatchApi = new Shop_Models_API_Page();
    			$Mixmatch = @array_shift($MixmatchApi -> getMixGoodsInfo('match_id=' . $mixId, '*'));
    			$goods = explode(',', $Mixmatch['match_goods']);
    			sort($goods);
    			$number = count($goods);
    			$goods = implode(',', $goods);
    			$package['config']['number'] = $number;
    		}
            
            $package['config']['group'] = $package['config']['group'] ? $package['config']['group'] : 1;
            $totalNum = 0;
            if ($package['offers_type'] == 'fixed-package') {
                for ( $i = 0; $i < count($package['config']['allNums']); $i++ ) {
                    $totalNum += $package['config']['allNums'][$i];
                }
            }
            else if ($package['offers_type'] == 'choose-package') {
                $totalNum = $package['config']['group'] * $package['config']['number'];
            }
			if (count($cookie) != $totalNum) {
    		    return '您选择的商品不够,请选择完整后提交!';
    	    }
    			
			foreach ($cookie as $vproduct)
			{
				$product = explode(':', $vproduct);
				$GroupArr = explode('_', $product[0]);
	            if(!$this -> _goods -> getProduct("a.product_id='" . $product[1] . "'","goods_name")) {
	            	return '对不起，您选择的第' . $GroupArr[0] . '组第'.$GroupArr[1].'件商品暂时库存不足';
	            }
				
				if ($package['config']['allGoods'][$GroupArr[0]-1]['catDiscount']) {
					foreach ($package['config']['allGoods'][$GroupArr[0]-1]['catDiscount'] as $dkey => $dvalue)
				    {
					    $dvalue && $where1[] = "cat_path like '%," . $dkey . ",%'";
				    }
				} elseif ($package['config']['allGoods'][$GroupArr[0]-1]['goodsDiscount']) {
				    foreach ($package['config']['allGoods'][$GroupArr[0]-1]['goodsDiscount'] as $dkey => $dvalue)
				    {
					    $dvalue && $where2[] = "b.goods_id =" . $dkey;
				    }
			    }
			    $where1 && $where1 = implode(' OR ', $where1);
			    $where2 && $where2 = implode(' OR ', $where2);
			    
			    if ($where1 && $where2) {
			        $where .= " AND ($where1 OR $where2)";
			    } elseif ($where1) {
			        $where .= " AND ($where1)";
			    } elseif ($where2) {
			        $where .= " AND ($where2)";
			    }
			    $where .= " AND a.product_id='" . $product[1] . "'";
			    if (!$this -> _goods -> getProduct("1=1 $where","a.product_id,b.goods_id,a.product_sn,b.goods_name,b.market_price,goods_img")) {
			    	return '请选择正确的商品!';
			    }
			    unset($where1, $where2, $where);
			}
		} else {
			return '您选择的商品不够,请选择完整后提交!';
		}
	}
	
	/**
     * 取得商品列表
     *
     * @param    int      $page
     * @param    array    $data
     * @return   void
     */
	public function getGoods($page, $data, $pageSize = null)
	{
		return $this -> _offers -> getGoods($page, $data, $pageSize);
	}
	
	/**
     * 生成礼包cookie
     *
     * @param   int     $offersId
     * @param   int     $offset
     * @return  array
     */
    public function setPackageCartCookie($offersId, $offset = null)
    {
    	if ($_COOKIE['package_' . $offersId]) {
    		$packageInfo = $this -> getPackageById($offersId);
    		if ($packageInfo) {
    			$expire = ($packageInfo['to_date']) ? strtotime($packageInfo['to_date']) + 86400 : time() + 365 * 86400;
    			
    			if ($offset && $_COOKIE['p']) {
    				$p = explode('|', $_COOKIE['p']);
    				$p[$offset-1] = $offersId . '@' . $_COOKIE['package_' . $offersId];
    				$p = implode('|', $p);
    			} elseif ($_COOKIE['p']) {
    				$p = $_COOKIE['p'] . '|' . $offersId . '@' . $_COOKIE['package_' . $offersId];
    			} else {
    				$p = $offersId . '@' . $_COOKIE['package_' . $offersId];
    			}
    			setcookie('p', $p, $expire, '/');
    			setcookie('package_' . $offersId, '', 0, '/');
    		}
    	}
    }
    
    /**
     * 取得选择的礼包商品
     *
     * @param   int     $offersId
     * @param   int     $offset
     * @return  array
     */
    public function getPackageGoods($offersId, $offset = null, $bid = null, $page = null)
    {
    	if ($offset > 0 && !$bid && !$page && $_COOKIE['p']) {
    		$p = explode('|', $_COOKIE['p']);
    		$package = preg_replace('/^[0-9]{1,}@/', '', $p[$offset-1]);
    	} elseif ($_COOKIE['package_' . $offersId]) {
    		$package = $_COOKIE['package_' . $offersId];
    	}
    	
    	if ($package) {
    		$offersIds = explode(',', $package);
    		foreach ($offersIds as $bid)
			{
				$bid = explode(':', $bid);
				$packageGoods[$bid[0]] = $this -> getGoodsByProductId($bid[1]);
			}
			
			if (!$_COOKIE['package_' . $offersId]) {
				$packageInfo = $this -> getPackageById($offersId);
			    $expire = ($packageInfo['to_date']) ? strtotime($packageInfo['to_date']) + 86400 : time() + 365 * 86400;
			    setcookie('package_' . $offersId, $package, $expire, '/');
			}
    	}
    	return $packageGoods;
    }
}