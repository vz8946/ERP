<?php

class Shop_Models_API_Offers extends Custom_Model_Dbadv
{
	/**
     * 促销活动 DB
     * 
     * @var Shop_Models_DB_Offers
     */
	private $_db = null;
	
	/**
     * 活动插件路径
     * 
     * @var string
     */
    const PATH = 'Custom/Model/Offers';
    
       /**
     * 对象
     *
     * @var Custom_Model_Offers_*
     */
    protected static $_loaded = array();
    public $_goods;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct(Shop_Models_API_Goods &$_goods = null)
	{
		$this -> _goods = is_object($_goods) ? $_goods : new Shop_Models_API_Goods();
		$this -> _db = new Shop_Models_DB_Offers();
		parent::__construct();
	}
	
	/**
     * 取得活动插件类
     *
     * @param    string    $pre
     * @return   string
     */
	public function getClass($pre)
	{
		$pre = implode('', array_map('ucfirst', explode('-', $pre)));
		$class = str_replace('/', '_', self::PATH) . '_' . $pre;

		if (array_key_exists($class, self::$_loaded)) {
			return self::$_loaded[$class];
		} else {
		    self::$_loaded[$class] = new $class();
		    return self::$_loaded[$class];
		}
	}
	
	/**
     * 取得当前有效的促销活动
     *
     * @param    array
     * @return   array
     */
	public function getAllActiveOffers($offers_types = array())
	{
		if ( count($offers_types) > 0 ) {
		    $whereSql = "offers_type in ('".implode("','", $offers_types)."')";
		}
		$allOffers = $this -> _db -> getActiveOffers($whereSql);
		if ($allOffers) {
            $today = date('Y-m-d H:i:s');
            $offersList = array();
            foreach ($allOffers as $offers)
            {
                if (!$offers['to_date'] || $offers['to_date'] >= $today) {
                    $offers['config'] = unserialize($offers['config']);
                    $offersList[] = $offers;
                }
            }
            
            uasort($offersList, 'compareOffers');
            $newList = array();
            foreach ( $offersList as $offer ) {
                $newList[] = $offer;
            }
            
            return $newList;
        }
	}
	
	/**
     * 取得指定ID的促销活动
     *
     * @param    int    $id
     * @return   array
     */
	public function getOffersById($id)
	{
		$offers =  @array_shift($this -> _db -> getOffers(array('offers_id' => $id)));
		if ($offers && $offers['from_date'] <= date('Y-m-d H:i:s') && (!$offers['to_date'] || $offers['to_date'] >= date('Y-m-d H:i:s'))) {
			$offers['config'] = unserialize($offers['config']);
			return $offers;
		}
	}
	

	/**
     * 取得指定ID的促销活动的商品配置
     *
     * @param    string    $config
     * @return   array
     */
	public function getOffers($where)
	{
		$offers =  $this -> _db -> getOffers($where);
		if($offers && count($offers)>=1){
			$result = array();
			foreach($offers as $off){
				if ($off && $off['from_date'] <= date('Y-m-d H:i:s') && (!$off['to_date'] || $off['to_date'] >= date('Y-m-d H:i:s'))) {
					$off['config'] = unserialize($off['config']);
					$result[$off['offers_id']] =$off;
				}
			}
		   return $result;
		}
	}
	
	public function getAllOffers($where)
	{
		$offers =  $this -> _db -> getOffers($where);
		$result = array();
		$startDate =date('Y-m-d').' 18:00:00';
		$endDate = date('Y-m-d',strtotime('+1 days')).' 02:00:00'; 
		
		if($offers && count($offers)>=1){			
			foreach($offers as $off){
				if ($off && $off['from_date'] == $startDate && (!$off['to_date'] || $off['to_date'] == $endDate)) {
					$off['config'] = unserialize($off['config']);
					$result[$off['offers_id']] =$off;
				}
			}	
		}
		return $result;
	}	

    /**
     * 取得指定ID的促销活动的商品配置
     *
     * @param    string    $config
     * @return   array
     */
	public function getOffersNoLimit($where)
	{
		$offers =  $this -> _db -> getOffersNoLimit($where);
		if($offers && count($offers)>=1){
			$result = array();
			foreach($offers as $off){
			    $off['config'] = unserialize($off['config']);
				$result[] =$off;
			}
		   return $result;
		}
	}


	/**
     * 取得指定ID的促销活动的商品配置
     *
     * @param    string    $config
     * @return   array
     */
	public function getLastOffersConfig($where)
	{
		$offers =  @array_shift($this -> _db -> getOffers($where));
		
		if ($offers && $offers['from_date'] <= date('Y-m-d H:i:s') && (!$offers['to_date'] || $offers['to_date'] >= date('Y-m-d H:i:s'))) {
			$offers['config'] = unserialize($offers['config']);
			if ($offers['config']) {
				$result = array();
		    	parse_str($offers['config']['allDiscount'], $result);
		    	return $result;
			}
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
		$offers = $this -> getOffersById($data['id']);
		
		if (is_array($offers['config'][$data['cname']])) {
		    if ($offers['offers_type'] == 'order-gift') {
		        parse_str($offers['config'][$data['cname']][$data['index']], $offers['config'][$data['cname']][$data['index']]);
			    $cname = $offers['config'][$data['cname']][$data['index']];
		    }
			else {
			    parse_str($offers['config'][$data['cname']][$data['bid']-1], $offers['config'][$data['cname']][$data['bid']-1]);
			    $cname = $offers['config'][$data['cname']][$data['bid']-1];
			}
		} else {
		    parse_str($offers['config'][$data['cname']], $offers['config'][$data['cname']]);
			$cname = $offers['config'][$data['cname']];
		}
		if ($cname) {
			if ($cname['catDiscount']) {
				foreach ($cname['catDiscount'] as $dkey => $dvalue)
				{
					isset($dvalue) && $where1[] = "b.cat_path like '%," . $dkey . ",%'";
				}
			} elseif ($cname['goodsDiscount']) {
				foreach ($cname['goodsDiscount'] as $dkey => $dvalue)
				{
					isset($dvalue) && $where2[] = "a.goods_id =" . $dkey;
				}
			}
			$where1 = ($where1) ? '(' .implode(' or ', $where1) . ')' : '1=0';
			$where2 = ($where2) ? '(' .implode(' or ', $where2) . ')' : '1=0';
			$where .= " AND ($where1 OR $where2)";
		}
		
    	$data['cat_id'] && $where .= " AND b.cat_path LIKE '%," . $data['cat_id'] . ",%'";
    	$data['goods_name'] && $where .= " AND a.goods_name LIKE '%" . $data['goods_name'] . "%'";
    	$data['goods_sn'] && $where .= " AND a.goods_sn LIKE '%" . $data['goods_sn'] . "%'";
    	
        $datas = $this -> _goods -> getCatProductGoods('(a.onsale=0 or a.is_gift = 1)'.$where, $page, $pageSize, "goods_sort asc");
        return array('datas' => $datas['data'], 'total' => $datas['total'], 'offers' => $offers);
	}
	
	/**
     * 取得商品列表
     *
     * @param    int      $page
     * @param    array    $data
     * @return   void
     */
	public function getGroupGoods($page, $data, $pageSize = null)
	{
		$offers = $this -> getOffersById($data['id']);
		if (is_array($offers['config'][$data['cname']])) {
		    if ($offers['offers_type'] == 'order-gift') {
		        parse_str($offers['config'][$data['cname']][$data['index']], $offers['config'][$data['cname']][$data['index']]);
			    $cname = $offers['config'][$data['cname']][$data['index']];
		    }
			else {
			    parse_str($offers['config'][$data['cname']][$data['bid']-1], $offers['config'][$data['cname']][$data['bid']-1]);
			    $cname = $offers['config'][$data['cname']][$data['bid']-1];
			}
		} else {
		    parse_str($offers['config'][$data['cname']], $offers['config'][$data['cname']]);
			$cname = $offers['config'][$data['cname']];
		}
		if ($cname) {
			if ($cname['goodsDiscount']) {
				foreach ($cname['goodsDiscount'] as $dkey => $dvalue) {
					isset($dvalue) && $group_ids[] = $dkey;
				}
			}
			$group_ids = implode(',', $group_ids);
			$where =" group_id IN({$group_ids})";   
		}
    	$data['goods_name'] && $where .= " AND group_goods_name LIKE '%" . $data['goods_name'] . "%'";
    	$data['goods_sn'] && $where .= " AND group_sn LIKE '%" . $data['goods_sn'] . "%'";
    	$groupGoodsAPI = new Shop_Models_API_GroupGoods();    
        $datas = $groupGoodsAPI -> get($where, '*', $page, $pageSize, 0);    
        return array('datas' => $datas, 'total' => count($datas), 'offers' => $offers);
	}
	
	/**
     * 取得商品分类树
     *
     * @param    string    $name
     * @return   void
     */
	public function getGoodsSelect($data)
	{
		return $this -> _goods -> buildProductSelect($data);
	}
	
	/**
     * 处理有效活动信息并显示
     *
     * @param    array  $datas    # 商品列表
     * @return   array
     */
	public function responseToView($datas)
	{
		$offersList = $this -> getAllActiveOffers();
		if ($offersList) {
			foreach ($offersList as $key => $offers) {
			    if ($offers['offers_type'] == 'group-exclusive')    continue;
			    
				$offersPlugin = $this -> getClass($offers['offers_type']);
			    $datas = $offersPlugin -> responseToView($offers, $datas);
			}
		}
		return $datas;
	}
	
	/**
     * 处理有效活动信息并显示(组合商品)
     *
     * @param    array  $datas    # 商品列表
     * @return   array
     */
	public function responseToViewForGroup($datas)
	{
		$offersList = $this -> getAllActiveOffers(array('group-exclusive', 'fixed'));
		if ($offersList) {
			foreach ($offersList as $key => $offers) {
				$offersPlugin = $this -> getClass($offers['offers_type']);
			    $datas = $offersPlugin -> responseToView($offers, $datas);
			}
		}
		return $datas;
	}
	
	/**
     * 购物车处理有效活动信息
     *
     * @param    array  $datas    # 购物车信息
     * @return   mixed
     */
	public function responseToCart($datas)
	{
	    
		$offersList = $this -> getAllActiveOffers();
		if ($offersList) {
			foreach ($offersList as $key => $offers) {
			    if ($offers['offers_type'] == 'group-exclusive')    continue;

				$offersPlugin = $this -> getClass($offers['offers_type']);
                
			    $datas = $offersPlugin -> responseToCart($offers, $datas);
			}
		}
		return $datas;
	}

    /**
     * 取得商品列表
     *
     * @param    int      $page
     * @param    array    $data
     * @return   void
     */
	public function getGoodsByCat($catId,$page, $data, $pageSize = null)
	{
        if(!$catId) return '';
    	$where .= ' AND a.cat_id=' . $catId;
    	$data['goods_name'] && $where .= " AND a.goods_name LIKE '%" . $data['goods_name'] . "%'";
    	$data['goods_sn'] && $where .= " AND a.goods_sn LIKE '%" . $data['goods_sn'] . "%'";
        $datas = $this -> _goods -> getProductGoods('a.onsale=0'.$where, $page, $pageSize);
        return array('datas' => $datas['data'], 'total' => $datas['total'], 'offers' => '');
	}
	
	/**
	 * 今日抢购
	 */
	public function getToday(){
		$ctime = mktime();
		$t_date = date('Y-m-d',$ctime).' 00:00:00';
		$tmw_date = date('Y-m-d',$ctime+3600*24).' 00:00:00';
		
     	$tbl = 'shop_offers as t|t.*';
     	$map = array();
     	$map['t.offers_type'] = 'fixed';
     	$map['t.from_date|egt'] = $t_date;
     	$map['t.from_date|lt'] = $tmw_date;
		
		$list = $this->getAllWithLink($tbl, array(),$map,1);
		
		$api_goods = new Shop_Models_API_Goods();
		
		foreach ($list as $k => $v) {
			
			$config = unserialize($v['config']);
			parse_str($config['allDiscount'],$rs);			
			$goods_discount = $rs['goodsDiscount'];
			
			$goods = $api_goods->getByIds(array_keys($goods_discount));
			
			foreach ($goods as $kk => $vv) {
				$goods[$kk]['qg_price'] = $goods_discount[$vv['goods_id']];
			}
			
			$list[$k]['goods'] = $goods; 
		}
		
		return $list[0]; 
	}
	
	/**
	 * 本周团购
	 */
	public function getThisWeek(){

		$ct = time();
		$t1 = 7-date('w',$ct)-1;
		$t1 = $t1 == 0 ? 7 : $t1;
		$cweek_last_date = date('Y-m-d',$ct+($t1*24*3600));
		$cweek_last_datetime = strtotime($cweek_last_date.' 00:00:00');
		
		$t_date = date('Y-m-d',$ct).' 00:00:00';
		$tmw_date = date('Y-m-d',$cweek_last_datetime).' 00:00:00';

     	$tbl = 'shop_offers as t|t.*';
     	$map = array();
     	$map['t.offers_type'] = 'fixed';
     	$map['t.to_date'] = $tmw_date;
		
		$list = $this->getAllWithLink($tbl, array(),$map,2);
		
		$api_goods = new Shop_Models_API_Goods();
		
		foreach ($list as $k => $v) {
			
			$config = unserialize($v['config']);
			parse_str($config['allDiscount'],$rs);			
			$goods = array();
			if(!empty($rs['goodsDiscount'])){
				$goods_discount = $rs['goodsDiscount'];
				$goods = $api_goods->getByIds(array_keys($goods_discount));
				foreach ($goods as $kk => $vv) {
					$goods[$kk]['qg_price'] = $goods_discount[$vv['goods_id']];
				}
			}
			
			$group_goods = array();
			parse_str($config['allGroupGoods'],$rsg);			
			if(!empty($rsg)){
				$arr_gg_id = array_keys($rsg['goodsDiscount']);
				$group_goods = $api_goods->getGroupGoodsByIds($arr_gg_id);
				foreach ($group_goods as $kk => $vv) {
					$group_goods[$kk]['gt'] = 'group';
					$group_goods[$kk]['qg_price'] = $rsg['goodsDiscount'][$vv['group_id']];
				}
			}

			$goods = array_merge($goods,$group_goods);
			$list[$k]['goods'] = $goods; 
		}
		
		return $list[0]; 
	}
	
	/**
	 * 下周团购
	 */
	public function getNextWeek(){

		$ct = mktime();
		$t1 = 7-date('w',$ct)-1;
		$t1 = $t1 == 0 ? 7 : $t1;
		$cweek_last_date = date('Y-m-d',$ct+($t1*24*3600));
		$cweek_last_datetime = strtotime($cweek_last_date.' 00:00:00');
		
		$tmw_date = $cweek_last_datetime;
		$tmw_tmw_date = $tmw_date+(7*24*3600);
		
     	$tbl = 'shop_offers as t|t.*';
     	$map = array();
     	$map['t.offers_type'] = 'fixed';
     	$map['t.to_date'] = date('Y-m-d',$tmw_tmw_date).' 00:00:00';     	
		
		$list = $this->getAllWithLink($tbl, array(),$map,1);
		
		$api_goods = new Shop_Models_API_Goods();
		
		foreach ($list as $k => $v) {
			
			$config = unserialize($v['config']);
			parse_str($config['allDiscount'],$rs);			
			$goods = array();
			if(!empty($rs['goodsDiscount'])){
				$goods_discount = $rs['goodsDiscount'];
				$goods = $api_goods->getByIds(array_keys($goods_discount));
				foreach ($goods as $kk => $vv) {
					$goods[$kk]['qg_price'] = $goods_discount[$vv['goods_id']];
				}
			}

			$group_goods = array();
			parse_str($config['allGroupGoods'],$rsg);			
			if(!empty($rsg)){
				$arr_gg_id = array_keys($rsg['goodsDiscount']);
				$group_goods = $api_goods->getGroupGoodsByIds($arr_gg_id);
				foreach ($group_goods as $kk => $vv) {
					$group_goods[$kk]['gt'] = 'group';
					$group_goods[$kk]['qg_price'] = $rsg['goodsDiscount'][$vv['group_id']];
				}
			}
			$goods = array_merge($goods,$group_goods);
			
			$list[$k]['goods'] = $goods;
		}
		
		return $list[0]; 	
	}
	
	/**
	 * 明日抢购
	 * 
	 */
	public function getTmw(){
		
		$ctime = mktime();
		
		$tmw_date = date('Y-m-d',$ctime+3600*24).' 00:00:00';
		$tmw_tmw_date = date('Y-m-d',$ctime+3600*24*2).' 00:00:00';
		
     	$tbl = 'shop_offers as t|t.*';
     	$map = array();
     	$map['t.offers_type'] = 'fixed';
     	$map['t.from_date|egt'] = $tmw_date;
     	$map['t.from_date|lt'] = $tmw_tmw_date;     	
		
		$list = $this->getAllWithLink($tbl, array(),$map,1);
		
		$api_goods = new Shop_Models_API_Goods();
		
		foreach ($list as $k => $v) {
			
			$config = unserialize($v['config']);
			parse_str($config['allDiscount'],$rs);			
			$goods_discount = $rs['goodsDiscount'];
			
			$goods = $api_goods->getByIds(array_keys($goods_discount));
			
			foreach ($goods as $kk => $vv) {
				$goods[$kk]['qg_price'] = $goods_discount[$vv['goods_id']];
			}
			
			$list[$k]['goods'] = $goods;
		}
		
		return $list[0]; 
	}
	
	
	
}

    /**
     * 排序活动(按活动类型和权重)
     *
     * @param    array      $a
     * @param    array      $b
     * @return   boolean
     */
	function compareOffers($a, $b) {
    
        $SortArr['choose-package'] = 1;
        $SortArr['fixed-package'] = 2;
        $SortArr['buy-gift'] = 3;
        $SortArr['order-buy-gift'] = 4;
        $SortArr['discount'] = 5;
        $SortArr['discount-adv'] = 6;
        $SortArr['exclusive'] = 7;
        $SortArr['price-exclusive'] = 8;
        $SortArr['buy-minus'] = 9;
        $SortArr['fixed'] = 10;
        $SortArr['minus'] = 11;
        $SortArr['order-gift'] = 12;
        
        if ( $SortArr[$a['offers_type']] > $SortArr[$b['offers_type']] )        return 1;
        else if ( $SortArr[$a['offers_type']] < $SortArr[$b['offers_type']] )   return -1;
        else    return $a['order'] < $b['order'];
    }