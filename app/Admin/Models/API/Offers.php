<?php

class Admin_Models_API_Offers
{
	/**
     * 促销活动 DB
     *
     * @var Admin_Models_DB_Offers
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

    /**
     * Cache文件
     *
     * @var string
     */
    private $_cacheFile = 'cache.offers';

    /**
     * 原始数据段
     *
     * @var string
     */
    private $_orgInput = array('offers_name', 'offers_type', 'offers_rank', 'from_date', 'to_date');

    /**
     * 会员等级 API
     *
     * @var Admin_Models_API_MemberRank
     */
	private $_rank = null;

	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _cacheFile = realpath(Zend_Registry::get('config') -> sytem_cache -> dir) . '/' . $this -> _cacheFile;
		$this -> _db = new Admin_Models_DB_Offers();
	}

	/**
     * 编辑促销活动
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function editOffers($data, $id = null, $view = null)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());

        $data = Custom_Model_Filter::filterArray($data, $filterChain);
		if ($data['offers_name'] == '') {
			return 'noOffersName';
		}
		
		if ($data['offers_type'] == '') {
			return 'noOffersType';
		}
		$data['use_coupon'] = ($data['use_coupon']) ? 1 : 0;

		if ($data['offers_rank'] != '') {
			$data['offers_rank'] = implode(',', $data['offers_rank']);
		}

		if ($data['from_date'] != '' && $data['to_date'] != '') {

		    if ($data['from_date'] > $data['to_date']) {
		        return 'errorDateRange';
			}
		} elseif ($data['from_date'] == '') {
			return 'noFromDate';
		}

		//判断是否有重复商品
	    if ( $data['allGoods'] ) {
	        if ( is_array($data['allGoods']) ) {
	            $allGoodsArray = $data['allGoods'];
	        }
	        else    $allGoodsArray[] = $data['allGoods'];
	        $goodsNumber = 0;
	        $goodsDiscount = array();
		    for ( $i = 0; $i < count($allGoodsArray); $i++ ) {
			    if ( !$allGoodsArray[$i] ) continue;

			    $temparr = explode( '&', $allGoodsArray[$i] );
			    $goodsNumber += count($temparr);
			    for ( $j = 0; $j < count($temparr); $j++ ) {
			        parse_str($temparr[$j]);
			    }
			}
        }
        if ( count($goodsDiscount) < $goodsNumber ) {
		    return 'sameGoods';
		}

		foreach ($data as $key => $value)
		{
			if (!in_array($key, $this -> _orgInput)) {
				$data['config'][$key] = $value;
			}
		}

		$data['config'] = serialize($data['config']);
		$offers = $this -> getOffersByName($data['offers_name']);
		if ($id == null) {
		    if ($offers) {
			    return 'offersExists';
		    }
		    $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		    $data['admin_name'] = $auth['admin_name'];
		    $result = $this -> _db -> addOffers($data);
		} else {

			if ($offers && $offers['offers_id'] != $id) {
			    return 'offersExists';
		    }

			if (!$this -> getOffersById($id)) {
			    return 'offersNoExists';
		    }

			$result = $this -> _db -> updateOffers($data, (int)$id);
		}

		if (is_numeric($result) && $result >= 0) {
		    /*
		    //如果是商品特价，可能需要生成缓存模板
		    if ( $data['offers_type'] == 'fixed' ) {
		        $config = unserialize( $data['config'] );
		        if ( $config['sale_weekly'] && $config['allDiscount'] ) {
		            $goods_array = array();
		            parse_str( $config['allDiscount'], $goods_array );
		            if ( $goods_array['goodsDiscount'] ) {
		                $from_date = str_replace( '-', '', substr($data['from_date'], 0, 10) );
		                $filename = '../data/shop/cache/sale_weekly/sale-'.$from_date.'.tpl';

		                $goods_api = new Admin_Models_API_Goods();
    		            foreach ( $goods_array['goodsDiscount'] as $goods_id => $value ) {
    		                $goods = array_shift( $goods_api -> get("a.goods_id={$goods_id}", 'a.goods_id,a.goods_name,a.market_price,a.goods_img') );
    		                $goods['price'] = $value;
    		                $goods['discount'] = round ($goods['price'] / $goods['market_price'] * 10, 1 );
    		                $temparr = explode( ' ', $data['to_date'] );
    		                $datearr = explode( '-', $temparr[0] );
    		                $goods['to_date'] = $datearr[1].'/'.$datearr[2].'/'.$datearr[0].' '.$temparr[1];
    		                $view -> goods = $goods;
    		                break;
    		            }
    		        }

    		        $content = $view->render('offers/sale-weekly.tpl');

		            file_put_contents($filename, $content);
		        }
		    }
		    */
		    return ($id === null) ? 'addOffersSucess' : 'editOffersSucess';
		} else {
			return 'error';
		}
	}

	/**
     * 删除促销活动
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteOffersById($id)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> deleteOffers((int)$id);

		    if (is_numeric($result) && $result > 0) {
		        return 'deleteSucess';
		    } else {
			    return 'error';
		    }
		}
	}

	/**
     * 取得活动状态显示代码
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxStatus($url, $id, $status)
	{
		switch($status){
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}

	/**
     * 取得礼券使用设置状态显示代码
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxCoupon($url, $id, $status)
	{
		switch($status){
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1, \'ajax_coupon\');" title="点击设为不可使用"><u>可以</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0, \'ajax_coupon\');" title="点击设为可使用"><u><font color=red>不可</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}

	/**
     * 更改活动状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeStatus($id, $status)
	{
		if ($id > 0) {
		    if($this -> _db -> updateStatus($id, $status) <= 0) {
			    exit('failure');
		    }
		}
	}

	/**
     * 更改是否可使用礼券
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeCoupon($id, $status)
	{
		if ($id > 0) {
		    if($this -> _db -> updateCoupon($id, $status) <= 0) {
			    exit('failure');
		    }
		}
	}

	/**
     * 取得所有活动信息
     *
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getAllOffers($page = null, $pageSize = null, $where = null)
	{
		if ($where) {
		    $wheresql = '';
		    if ($where['search_offers_type']) {
		        $wheresql .= "offers_type = '{$where['search_offers_type']}' and ";
		    }
		    if ($where['offers_name']) {
		        $wheresql .= "offers_name like '%{$where['offers_name']}%' and ";
		    }
		    if ($where['as_name']) {
		        $wheresql .= "as_name like '%{$where['as_name']}%' and ";
		    }
		    if (($where['status'] !== '') && ($where['status'] !== null)) {
		        $wheresql .= "status = '{$where['status']}' and ";
		    }
		    if (($where['offers_id'] !== '') && ($where['offers_id'] !== null)) {
		        $wheresql .= "offers_id = '{$where['offers_id']}' and ";
		    }
		    if ( is_array($where['search_time_type']) ) {
		        $orsql = array();
		        $currentTime = date( 'Y-m-d H:i:s', time() );
		        for ( $i = 0; $i < count($where['search_time_type']); $i++ ) {
		            if ( $where['search_time_type'][$i] == '0' ) {
		                $orsql[]= "(from_date <= '{$currentTime}' and to_date > '{$currentTime}')";
		            }
		            if ( $where['search_time_type'][$i] == '1' ) {
		                $orsql[]= "from_date > '{$currentTime}'";
		            }
		            if ( $where['search_time_type'][$i] == '2' ) {
		                $orsql[]= "to_date <= '{$currentTime}'";
		            }
		        }
		        $orsql = implode( ' or ', $orsql );
		        $wheresql .= '('.$orsql.') and ';
		    }
		    $wheresql = substr( $wheresql, 0, -5 );
		}

		if ( $where['search_uid'] ) {
		    $datas = $this -> _db -> getOffers($wheresql);
		    if ($datas) {
		        $temp_arr = array();
		        foreach ( $datas as $data ) {
		            $config = unserialize( $data['config'] );
		            if ( $config['uid'] != $where['search_uid'] )   continue;
		            $temp_arr[] = $data;
		        }
		        if ( $page ) {
		            !$pageSize && $pageSize = Zend_Registry::get('config') -> view -> page_size;
		            for ( $i = ($page - 1) * $pageSize; $i < $page * $pageSize; $i++ ) {
		                if ($i >= count($temp_arr) )    break;
		                $content[] = $temp_arr[$i];
		            }
		        }
		        else    $content = $temp_arr;

		        $total = count($temp_arr);
		    }
		    else    $total = 0;
		}
		else {
		    $content = $this -> _db -> getOffers($wheresql, $page, $pageSize);
		    $total = $this -> _db -> getOffersCount($wheresql);
		}

		return array('content' => $content, 'total' => $total);
	}

	/**
     * 取得指定名称的活动信息
     *
     * @param    string    $name
     * @return   array
     */
	public function getOffersByName($name)
	{
		return  $this -> _db -> getOffersInfo(array('offers_name' => $name));
	}

	/**
     * 取得指定ID的活动信息
     *
     * @param    int    $id
     * @return   array
     */
	public function getOffersById($id)
	{
		return  $this -> _db -> getOffersInfo(array('offers_id' => $id));
	}

	/**
     * 取得所有活动插件名称
     *
     * @param    void
     * @return   array
     */
	public function getAllOffersName()
	{
		if (!file_exists($this -> _cacheFile)) {
			$this -> cacheOffersFile();
		}
		return Zend_Json::decode(file_get_contents($this -> _cacheFile));
	}

	/**
     * 生成活动文件缓存文件
     *
     * @param    void
     * @return   void
     */
	public function cacheOffersFile()
	{
		$offersPath = Zend_Registry::get('systemRoot') . '/lib/' . self::PATH;
		
		$dir = new DirectoryIterator($offersPath);

		foreach ($dir as $file)
        {
            if ($file -> isFile() && strpos($file -> getFilename(), 'Abstract') === false) {
            	$handle = @fopen($offersPath  . '/' . $file -> getFilename(), "r");
            	if ($handle) {
            		while (!feof($handle)) {
            		    if (preg_match('/protected\s+\$_title\s*=\s*\'([^\']+)\'/is', fgets($handle, 4096), $titleMatch)) {
            		        $name = substr($file -> getFilename(), 0, strrpos($file -> getFilename(), '.'));
            		        $name = strtolower(ltrim(preg_replace('/([A-Z])/', "-\${1}", $name), '-'));
            		        $offersFiles[$name] = $titleMatch[1];
            		        break;
            	        }
            		}
            		fclose($handle);
            	}
            }
        }
    	file_put_contents($this -> _cacheFile, Zend_Json::encode($offersFiles));
	}

	/**
     * 修改优先级
     *
     * @param    int    $id
     * @param    int    $order
     * @return   array
     */
	public function changeOrder($id, $order)
	{
		if ($id > 0) {
		    if($this -> _db -> updateOrder($id, $order) < 0) {
			    exit('failure');
		    }
		}
	}

	/**
     * 转换配置
     *
     * @param    string    $config
     * @return   array
     */
	public function parseConfig($config)
	{
		if ($config) {
			$result = array();
		    parse_str(Zend_Json::decode($config), $result);
		    return $result;
		}
	}

	/**
     * 取得商品列表
     *
     * @param    int    $page
     * @param    array  $where
     * @return   array
     */
	public function getGoods($page, $pageSize = null, $where = null)
	{
		$goods = new Admin_Models_API_Goods();
		$whereSql .= " (onsale=0 or is_gift = 1)";
        $where['cat_id'] && $whereSql .= " and b.cat_path LIKE '%," . $where['cat_id'] . ",%'";
		$where['goods_sn'] && $whereSql .= " and (goods_sn='" . $where['goods_sn']. "')";
		$where['goods_name'] && $whereSql .= " and goods_name LIKE '%" . $where['goods_name'] . "%'";
        $where['view_cat_id'] && $whereSql .= " and d.cat_path LIKE '%," . $where['view_cat_id'] . ",%'";

        /*
		if ($where['offersType'] == 'discount') {

        	$allFixed = $this -> _db -> getOffers(array('offers_type' => 'fixed', 'status' => 1));
        	foreach ($allFixed as $fixed)
			{
				if ($fixed['from_date'] <= date('Y-m-d') && (!$fixed['to_date'] || $fixed['to_date'] >= date('Y-m-d'))) {
			    	$offers['config'] = unserialize($fixed['config']);
			    	$config = unserialize($fixed['config']);
        	    	$config = $this -> parseConfig($config['allDiscount']);
        	    	if ($config['goodsDiscount']) {
        		    	$ids = implode(',', array_keys($config['goodsDiscount']));
        		    	$whereSql .= " and goods_id NOT IN($ids)";
        	    	}
		    	}
			}
        }*/
        $datas = $goods -> get($whereSql, 'a.goods_id,a.goods_name,a.goods_sn,a.goods_sort,a.market_price,a.price,c.cost,a.onsale,c.price_limit', $page, $pageSize);
        foreach ($datas as $num => $data)
        {
        	$datas[$num]['onsale'] = ($datas[$num]['onsale']) ? '下架' : '上架';
        }
        return array('content' => $datas, 'total' => $goods -> getCount());
	}

	/**
     * 取得组合商品列表
     *
     * @param    int    $page
     * @param    array  $where
     * @return   array
     */
	public function getGroupGoods($page, $pageSize = null, $where = null)
	{
		$goods = new Admin_Models_API_GroupGoods();
		$whereSql .= " status=1";
		$where['goods_name'] && $whereSql .= " and group_goods_name LIKE '%" . $where['goods_name'] . "%'";
		$where['goods_sn'] && $whereSql .= " and group_sn ='" . $where['goods_sn'] . "'";
        $groupGoods = $goods -> get($whereSql, 'group_id,group_goods_name,group_sn,group_price,status,price_limit ', $page, $pageSize);
        $datas = $groupGoods['data'];
        foreach ($datas as $num => $data)
        {
        	$datas[$num]['status'] = ($datas[$num]['status']) ? '上架' : '下架';
        }
        return array('content' => $datas, 'total' => $groupGoods['total']);
	}

	/**
     * 取得特殊折扣商品信息
     *
     * @param    array    $discountGoods
     * @return   array
     */
	public function getDiscountGoods($discountGoods)
	{
		$goods = new Admin_Models_API_Goods();
		$discountGoodsIds = array_keys($discountGoods);
		$whereSql['goods_id'] = implode(',', $discountGoodsIds);
		$datas = $goods -> get($whereSql, 'a.goods_id,a.goods_name,a.goods_sn,a.price,c.price_limit');
        foreach ($datas as $num => $data)
        {
        	$datas[$num]['discount'] = $discountGoods[$datas[$num]['goods_id']];
        }
        return $datas;
	}

	/**
     * 取得特殊折扣组合商品信息
     *
     * @param    array    $discountGoods
     * @return   array
     */
	public function getDiscountGroupGoods($discountGoods)
	{
		$goods = new Admin_Models_API_GroupGoods();
		$discountGoodsIds = array_keys($discountGoods);
		$whereSql = 'group_id in ('.implode(',', $discountGoodsIds).')';
		$groupGoods = $goods -> get($whereSql, 'group_id,group_goods_name,group_price, price_limit');
        $datas = $groupGoods['data'];
        foreach ($datas as $num => $data)
        {
        	$datas[$num]['discount'] = $discountGoods[$datas[$num]['group_id']];
        }
        return $datas;
	}

	/**
     * 处理有效活动信息并显示(copy from shop)
     *
     * @param    array  $datas    # 商品列表
     * @return   array
     */
	public function responseToView($datas)
	{
		$offersList = $this -> getAllActiveOffers();

		if ($offersList) {
			foreach ($offersList as $key => $offers) {
				$offersPlugin = $this -> getClass($offers['offers_type']);
			    $datas = $offersPlugin -> responseToView($offers, $datas);
			}
		}
		return $datas;
	}

	/**
     * 取得当前有效的促销活动(copy from shop)
     *
     * @param    void
     * @return   array
     */
	public function getAllActiveOffers()
	{
		$offersList = array();
		if ($allOffers = $this -> _db -> getActiveOffers()) {
            $today = date('Y-m-d H:i:s');
            foreach ($allOffers as $offers)
            {
                if (!$offers['to_date'] || $offers['to_date'] >= $today) {
                    $offers['config'] = unserialize($offers['config']);
                    $offersList[] = $offers;
                }
            }
            return $offersList;
        }
	}

	/**
     * 取得活动插件类(copy from shop)
     *
     * @param    string    $pre
     * @return   string
     */
	private function getClass($pre)
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
     * 按条件获得每个offer的订单数
     *
     * @param    array    $offer_ids
     * @param    array    $search
     * @return   array
     */
    public function getOfferOrderCount($offer_ids, $search) {

        if (!$offer_ids)   return false;

        if (($search['order_status'] !== '') && ($search['order_status'] !== null)) {
            $where .= ' and t2.status = '.$search['order_status'];
        }
        if ($search['from_date']) {
            $datearr = explode('-', $search['from_date']);
            $where .= ' and t2.add_time >= '.mktime(0, 0, 0, $datearr[1], $datearr[2], $datearr[0]);
        }
        if ($search['to_date']) {
            $datearr = explode('-', $search['to_date']);
            $where .= ' and t2.add_time <='.mktime(0, 0, 0, $datearr[1], $datearr[2], $datearr[0]);
        }

        return $this -> _db -> getOfferOrderCount($offer_ids, $where);

    }
}