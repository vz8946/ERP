<?php
class Purchase_Models_API_Goods extends Custom_Model_Dbadv
{
    /**
     *
     * @var Purchase_Models_DB_Goods
     */
	public $_db = null;

    /**
     * 错误信息
     */
	protected $error;

	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
	    parent::__construct();
		$this -> _db = new Purchase_Models_DB_Goods();
	}

	/**
     * 获取商品基本信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */

	public function getGoodsInfo($where = null, $fields = '*'){
       return $this -> _db -> getGoodsInfo($where, $fields);
    }

	/**
     * 获取商品数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */

	public function getGoodsList($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null){
         return $this -> _db -> get($where, $fields, $page, $pageSize, $orderBy);
    }

	/**
     * 获取商品数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null, $dooffer = true)
	{
		$or=null;
	    if(isset($where['extend_cat']) && isset($where['cat_id'])){
	    	$ext_goods_ids = $this -> _db -> getExtendCatGoods($where['cat_id']);
	    	if(!$where['filter_attr'] && is_array($ext_goods_ids) && count($ext_goods_ids)){
	    		$or .= " or a.goods_id in (".implode(',', $ext_goods_ids).")";
	    	}
	    }
	    $extend_goods_ids=array();
	    if(is_array($where) && $where['filter_attr']){
	       	$extend_filter_attr = explode(',', trim($where['filter_attr']));
			foreach($extend_filter_attr as $k=>$v){
				   $extend_goods_arr[]=$v;
			}
			//每个>0的属性都循环一遍
			$extend_newSql='';
			$num=count($extend_goods_arr);
			for($i=0;$i<$num;$i++){
				if(is_array($ext_goods_ids) && count($ext_goods_ids) && $extend_goods_arr[$i]>0){
					$extend_Sql = "  AND attr_id = ".$extend_goods_arr[$i];
					$extend_newSql = " and t1.goods_id in (".implode(',',$ext_goods_ids) .")";
					unset($temp);
					$extend_goods_ids = $this -> _db -> getArrGoodsList($extend_Sql.$extend_newSql);
				}
			}
	    }
	    if(count($extend_goods_ids)){
	   		foreach ($extend_goods_ids as $vvv){
	   			$tmp[] = $vvv['goods_id'];
	   		}
	   		$or .= " or a.goods_id in (".implode(',',$tmp) .") ";
	    }

		$whereSql = "a.onsale=0 and b.cat_status=0";
		if (is_array($where)) {
		    $where['cat_id'] && $whereSql .= " and (b.cat_path LIKE '%," . $where['cat_id'] . ",%' ". $or .")";
            if($where['price_seg']) {
                $filter_price=explode('-',$where['price_seg']);
                $whereSql .= " and a.price  >= '" . $filter_price[0]."' ";
                if (count($filter_price) > 1) {
                    $whereSql .= " and a.price  < '" . $filter_price[1]."' ";
                }
            }

		    if ($where['sort']) {
	        	switch ($where['sort']){
	        	    case 1:
	        	        $orderBy = "a.goods_id desc";
	        	    	break;
	        	    case 2:
	        	        $orderBy = "a.sort_sale desc";
	        	    	break;
	        	    case 3:
	        	        $orderBy = "a.price asc";
	        	    	break;
	        	    case 4:
	        	        $orderBy = "a.price desc";
	        	    	break;
                    default:
                        break;
	        	}
	        }
		}else{
			$whereSql .= ' and '.$where;
		}

        if(is_array($where) && $where['filter_attr']){
            $filter_attr = explode(',', trim($where['filter_attr']));
			foreach($filter_attr as $k=>$v){
				if($v!= 0){
				   $goods_arr[]=$v;
				}
			}
			$has_goods='yes';
			$newSql='';
			$goods_ids=array();
			$num=count($goods_arr);
			for($i=0;$i<$num;$i++){
				$Sql = "  AND attr_id = ".$goods_arr[$i];
				if(count($goods_ids) > 0){
				   foreach($goods_ids as $key=>$var){
						$temp[]= $var['goods_id'];
				   }
				   $newSql = " and t1.goods_id in (".implode(',',$temp) .")";
				   unset($temp);
				}else{
					if($i>0){
					  $has_goods='no';
					  break;
					}
				}
                $goods_ids = $this -> _db -> getArrGoodsList($Sql.$newSql);
			}

		   if($has_goods=='yes' &&  count($goods_ids) > 0){
			   foreach($goods_ids as $key=>$var){
					$tempp[]= $var['goods_id'];
			   }
			   $whereSql .= " and a.goods_id in (".implode(',',$tempp) .")";
		   }else{
				$datas =array();
				 return $datas;
		   }
	   }
        $datas = $this -> _db -> get($whereSql, $fields, $page, $pageSize, $orderBy);

        if ($datas) {
            for ( $i = 0; $i < count($datas); $i++ ) {
                if ( $datas[$i]['url_alias'] )  $datas[$i]['goods_url'] = $datas[$i]['url_alias'];
                else    $datas[$i]['goods_url'] = 'goods-'.$datas[$i]['goods_id'].'.html';
            }
        }
        return $datas;
	}

	/**
     * 获取商品属性
     *
     */
	public function getArrGoodsList($where = null)
	{
		return  $this -> _db -> getArrGoodsList($where);
	}


	/**
     * 获取属性信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getattribute($where = null)
	{
        return $this -> _db -> getattribute($where);
	}


	/**
     * 获取库存信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getProduct($where = null, $fields = '*', $page = null, $pageSize=null)
	{
        $datas = $this -> _db -> getProduct($where, $fields, $page, $pageSize);
        return $datas;
	}
	/**
     * 获取商品可销售库存
     *
     * @param    string    $where
     * @return   array
     */
	public function getGoodsStock($where)
	{
		$datas = $this -> _db -> getSaleStock($where);
		foreach ($datas as $num => $data)
        {
         	$result[$data['goods_id']] += $data['able_number'];
        }
        return $result;
	}

	/**
     * 获取商品种类信息
     *
     * @param    string    $where
     * @return   array
     */
	public function getProductGoods($where = null, $page = null, $pageSize=null, $orderBy=null)
	{
        $datas = $this -> _db -> get($where, 'a.goods_id,a.goods_name,a.view_cat_id,a.market_price,a.price,a.staff_price,b.cat_path,a.goods_img', $page, $pageSize, $orderBy);
        $total = $this -> _db -> total;
        if ($datas) {
        	foreach ($datas as $key => $goods)
        	{
        		$goodsIds[]= $goods['goods_id'];
        	}
        	foreach ($datas as $key => $value)
        	{
        		$result[$value['goods_id']] = $value;
        	}
        	return array('data' => $result, 'total' => $total);
        }
	}

	/**
     * 获取商品种类信息
     *
     * @param    string    $where
     * @return   array
     */
	public function getCatProductGoods($where = null, $page = null, $pageSize=null, $orderBy=null)
	{
        $datas = $this -> _db -> getCatGoods($where, 'a.goods_id,a.goods_name,a.view_cat_id,a.market_price,a.price,b.cat_path,a.goods_img,a.goods_sn,a.brief,a.goods_alt,c.product_id', $page, $pageSize, $orderBy);
        $total = $this -> _db -> total;
        if ($datas) {
        	foreach ($datas as $key => $value)
        	{
        		$result[$value['product_id']] = $value;
        	}
        	return array('data' => $result, 'total' => $total);
        }
	}
	/**
     * 获取总数
     *
     * @return   int
     */
	public function getCount()
	{
		return $this -> _db -> total;
	}

	/**
     * 构造分类树.
     *
     * @param    array    $deny
     * @param    array    $data
     * @param    int      $parentID
     * @return   array
     */
	public function catTree($deny=null,$data=null,$parentID=1,$where=null)
	{
        static $tree, $step;
        if(!$data){
            $data = $this -> _db -> getCat($where);
        }

        foreach($data as $v){
            if($v['parent_id'] == $parentID){
                $step++;
                $tree[$v['cat_id']] = array('cat_id'=>$v['cat_id'],
                                            'cat_sn'=>$v['cat_sn'],
                                            'cat_name'=>$v['cat_name'],
                                            'parent_id'=>$v['parent_id'],
                                            'cat_path'=>$v['cat_path'],
                                            'cat_sort'=>$v['cat_sort'],
                                            'display'=>$v['display'],
                                            'step'=>$step);
                if(is_array($deny)){
                    foreach($deny as $x){
                        if($x == $v['cat_id'] || strstr($v['cat_path'],','.$x.',')){
                            $tree[$v['cat_id']]['deny'] = 1;
                            break;
                        }
                    }
                }
                if($parentID){
                    $tree[$parentID]['leaf'] = 0;
                }
                $this -> catTree($deny,$data,$v['cat_id']);
                $step--;
            }
        }
        if($tree[$parentID] && !isset($tree[$parentID]['leaf'])){
            $tree[$parentID]['leaf'] = 1;
        }
        return $tree;
	}

	/**
     * 获取导航
     *
     * @param    string    $where
     * @return   array
     */
	public function getCat($where, $orderby = null)
	{
		return $this -> _db -> getCat($where, $orderby);
	}

    /**
     * 获取导航
     *
     * @param    string    $where
     * @param    string    $orderBy
     * @return   array
     */
    public function getNav($cat_id, $top_attr_id)
    {
        global $tree;
        if ( count($tree) > 1 ) {
            foreach ($tree as $id => $value) {
                for ($i = 0; $i < count($value); $i++) {
                    if ($value[$i][0] == $cat_id) {
                        if ( $value[$i][5] ) {
                            $url = $value[$i][5];
                        }
                        else {
                            $url = 'gallery-'.$cat_id.'.html';
                        }
                        return $this -> getNav($id, $top_attr_id).$nav;
                    }
                }
            }
        }
    }

	/**
     * 获取多个推荐标签
     *
     * @param    string    $where
     * @return   array
     */

	public function getGoodsTag($where,$offers=true)
	{
		$goodsData = $this -> _db -> getTag($where);
        if(count($goodsData)>0){
             foreach($goodsData as $key=>$var){
                  if ($var['config']){
						if($var['type']=='groupgoods'){
							if($groupgoods_ids){
								 $groupgoods_ids.= ','.$var['config'];
							}else{
								 $groupgoods_ids.= $var['config'];
							}
						}elseif($var['type']=='goods'){
							if($goods_ids){
								 $goods_ids.= ','.$var['config'];
							}else{
								$goods_ids.= $var['config'];
							}
						}elseif($var['type']=='brand'){
							if($brand_ids){
								 $brand_ids.= ','.$var['config'];
							}else{
								 $brand_ids.= $var['config'];
							}
						}
                  }
             }

			if($goods_ids){
				 $goodsdetails = $this ->get("a.goods_id in($goods_ids)", 'a.goods_id,a.view_cat_id,b.cat_path,a.goods_name,a.goods_alt,a.market_price,a.staff_price,a.goods_sn,a.price,a.goods_img,goods_style,a.goods_alt,d.as_name', 1, 1000,'find_in_set(a.goods_id,"'.$goods_ids.'")',$offers);
			}
			if($brand_ids){
	            $apiBrand=new Purchase_Models_API_Brand();
				$branddetails = $apiBrand -> get("brand_id in($brand_ids)", 'brand_id,brand_name,small_logo,as_name');
			}
			if($groupgoods_ids){
				$res = $this -> getgroup("status=1 and group_id in($groupgoods_ids)", 'group_id,group_goods_name,group_market_price,group_price,group_goods_img');
				foreach($res as $k => $v){
					$groupdetails[$k]['group_id']=$v['group_id'];
					$groupdetails[$k]['market_price']=$v['group_market_price'];
					$groupdetails[$k]['goods_name']=$v['group_goods_name'];
					$groupdetails[$k]['price']=$v['group_price'];
					$groupdetails[$k]['price']=$v['group_price'];
					$groupdetails[$k]['goods_img']=$v['group_goods_img'];
				}
			}

		   foreach($goodsData as $key=>$var){
				if ($var['config'] && $var['type']=='groupgoods'){
					 foreach($groupdetails as $k=>$v){
						 if( in_array($v['group_id'],explode(',',$var['config'])) ){
							$result[$var[tag_id]]['details'][] = $v;
						 }
					 }
				 }
				if ($var['config'] && $var['type']=='brand'){
					 foreach($branddetails as $k=>$v){
						 if( in_array($v['brand_id'],explode(',',$var['config'])) ){
							$result[$var[tag_id]]['details'][] = $v;
						 }
					 }
				 }
				 if ($var['config'] && $var['type']=='goods'){
					 foreach($goodsdetails as $k=>$v){
						 if( in_array($v['goods_id'],explode(',',$var['config'])) ){
							$result[$var[tag_id]]['details'][] = $v;
						 }
					 }
				 }
				 $result[$var[tag_id]]['totle'] = count($result[$var[tag_id]]['details']);
			 }


        }
		return $result;
	}
	/**
     * 获取组合商品列表
     *
     * @param    string    $id
     * @return   array
     */
	public function getgroup($where,$fileds='*',$pageSize=null){
	    return $this->_db->fetchgroup(1,$where,$fileds,$pageSize);
	}

	/**
     * 获取单个标签列表
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where, $page=1, $pageSize=null,$offers=true)
	{
		$data = array_shift($this -> _db -> getTag($where));
		if ($data['config']){
			$ids = $data['config'];
			if($data['type'] == 'groupgoods') {
				$res = $this -> getgroup("status=1 and group_id in($ids)", 'group_id,group_goods_name,group_market_price,group_price,group_goods_img');
				foreach($res as $k => $v){
					$result['details'][$k]['goods_id']=$v['group_id'];
					$result['details'][$k]['group_id']=$v['group_id'];
					$result['details'][$k]['market_price']=$v['group_market_price'];
					$result['details'][$k]['goods_name']=$v['group_goods_name'];
					$result['details'][$k]['price']=$v['group_price'];
					$result['details'][$k]['group_price']=$v['group_price'];
					$result['details'][$k]['goods_img']=$v['group_goods_img'];
				}
			}elseif($data['type'] == 'brand'){
	            $apiBrand=new Purchase_Models_API_Brand();
				$result['details'] = $apiBrand -> get("brand_id in($ids)", 'brand_id,brand_name,small_logo,as_name');

			}else{
				$result['details'] = $this ->get("a.goods_id in($ids)", 'a.goods_id,a.view_cat_id,b.cat_path,a.goods_name,a.market_price,a.staff_price,a.goods_sn,a.price,a.goods_img,goods_style,a.goods_alt,d.as_name', $page, $pageSize,'find_in_set(a.goods_id,"'.$ids.'")',$offers);
			}

		}
		$result['totle'] = count($result['details']);
		$result['data'] = $data;
		return $result;
	}

	/**
	 * 取得标签 不包括商品
	 *
	 * @param array $where
	 */
	public function onlyGetTag($where=null) {
		return $this -> _db -> getTag($where);
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getViewTag($where)
	{
		return array_shift($this -> _db -> getViewTag($where));
	}

	/**
     * 获取关联商品
     *
     * @param    int       $goods_id
     * @return   array
     */
	public function getLink($goods_id)
	{
		return $this -> _db -> getLink($goods_id);
	}

	/**
     * 获取图片信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getImg($where)
	{
		return $this -> _db -> getImg($where);
	}

	/**
     * 获取商品分类信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getGoodsCatList($where)
	{
		return $this -> _db -> getGoodsCatList($where);
	}

	/**
     * 商品浏览
     *
     * @param    string   $where
     * @return   array
     */
	public function view($id, $top_attr_id = 0)
	{
	    $datas = $this -> _db -> fetch("a.goods_id='$id'", 'a.*,b.parent_id,b.cat_name,b.cat_path,c.goods_style,c.brand_id');	
        if ($datas) {
        
        	if ($datas[0]['goods_img'] =='' &&  $datas[0]['onsale']==1){ //下架 没有图片
        		header("Location: /");exit;
        	} 
			
            $data = $datas[0];
            
        }else{
            header("Location: /");exit;
        }
        
        $path = substr($data['cat_path'], 1, -1);
        $place = explode(',', $path);
        !$data['goods_package'] && $data['goods_package'] = $data['package'];

        $result['nav'] = $attr_nav.$this -> getNav( $data['view_cat_id'], $top_attr_id );
        $last_goods && $result['last_goods'] = $last_goods[0];
        $next_goods && $result['next_goods'] = $next_goods[0];

        //取得品牌数据
        $data['brand'] = $this->_db->getRow('shop_brand',array('brand_id'=>$data['brand_id']));

        $list_cat = $this->getAll('shop_goods_cat',array('cat_id|in'=>explode(',', $path)),'*',0,'find_in_set(cat_id,"'.$path.'")');
		
        $arr_cat_name = array();
        foreach ($list_cat as $k=>$v){
            $arr_cat_name[] = '<a href="/gallery-'.$v['cat_id'].'.html">'.$v['cat_name'].'</a>';
        }
        
        $data['cat_path_name'] = implode(' &gt; ', $arr_cat_name);

        $result['data'] = $data;
        $result['path'] = $path;
        $result['place'] = $place[1];
        
        return $result;
	}

	/**
     * 设置商品浏览历史
     *
     * @param    int       $id
     * @return   array
     */
	public function setHistory($id)
	{

        if ($_COOKIE['history']) {
        	$history = $_COOKIE['history'];

        	$r = explode(',', $history);
        	array_unshift($r, $id);
        	$r = array_slice(array_unique($r),0,5);
        	$str = implode(',', $r);
        }else{
            $str = $id;
        }
        setcookie('history', $str, time () + 86400 * 365, '/');
	}

	/**
     * 获取商品浏览历史
     *
     * @param    int       $id
     * @return   array
     */
	public function getHistory()
	{
        $datas = array();
		if ($_COOKIE['history']) {
            $ids = array_map('intval',explode(',',$_COOKIE['history'],5));
            $ids = implode(',',$ids);
            if ($ids != "0") {
                $datas = $this -> _db -> get("a.onsale=0 and a.goods_id in ($ids)", "a.goods_id,goods_name,goods_sn,market_price,price,staff_price,goods_img,d.as_name",null,null,'find_in_set(a.goods_id,"'.$ids.'")');
                return $datas;
            } else {
                exit;
            }
		}
        return $datas;
	}

	/**
     * 清空商品浏览历史
     *
     * @param    int       $id
     * @return   array
     */
	public function emptyHistory()
	{
	    if ($_COOKIE['history'])    $_COOKIE['history'] = '';
	}

	/**
     * 取得商品分类结构
     *
     * @return   void
     */
    public function cacheCats()
    {
	    $data = $this -> _db-> getAllCat();
        foreach ($data as $key => $row)
        {
    	    $this -> _cacheCats[$row["parent_id"]][$row["cat_id"]] = $row;
		    $this -> _cacheParentCat[$row["cat_id"]][$row["parent_id"]] = $row;
		    $this -> _cacheCat[$row["cat_id"]] = $row;
	    }
    }

    /**
     * 取得商品分类信息
     *
     * @param    string    $catId    分类ID
     * @return   array
     */
    public function getCacheCat($catId)
    {
	    return $this -> _cacheCat[$catId];
    }

    /**
     * 取得商品分类所有一级子类
     *
     * @param    string    $catId    分类ID
     * @return   array
     */
    public function getCacheCats($catId)
    {
	    return $this -> _cacheCats[$catId];
    }

    /**
     * 取得商品分类所有一级父类
     *
     * @param    string    $catId    分类ID
     * @return   array
     */
    public function getCacheParentCat($catId)
    {
	    return $this -> _cacheParentCat[$catId];
    }

    /**
     * 取得给定商品分类的所有子类(包括自身)
     *
     * @param   string	$catId	分类ID
     * @return  string	$catId	所有子类,以','分隔
     */
    function getSubCats($catId)
    {
	    $cacheCats = $this -> _cacheCats;
	    if (is_array($cacheCats[$catId])) {
		    foreach($cacheCats[$catId] as $subCatId => $cat)
		    {
			    $catId .= "," . $this -> getSubCats($subCatId);
		    }
	    }
	    return $catId;
    }

	function getSubId($cat_id){
		$list = $this->getAll('shop_goods_cat',array('parent_id'=>$cat_id));
		$arr_id = array();
		foreach ($list as $k => $v) {
			$arr_id[] = $v['cat_id'];
		}
		
		return array_merge(array($cat_id),$arr_id);
	}

    /**
     * 取得给定分类的所有父类(包括自身)
     *
     * @param   string	$catId	分类ID
     * @return  string	$catId	所有父类,以','分隔
     */
    function getParentCats($catId)
    {
	    $cacheParentCat = $this -> _cacheParentCat;
	    if (is_array($cacheParentCat[$catId])) {
    	    foreach ($cacheParentCat[$catId] AS $parentCatId => $cat)
    	    {
			    $catId .= "," . $this -> getParentCats($parentCatId);
		    }
	    }
	    return $catId;
    }

    /**
     * 创建商品分类下拉列表
     *
     * @param    array    $data
     * @return   string
     */
	public function buildSelect($data = null)
	{
		$this -> cacheCats();
		$this -> catTreeSelect($data['catId']);
		foreach ($this -> _catTreeSelectOption as $key => $value)
		{
			$selected = ($data['selected'] == $key) ? "selected" : "";
		    $option[] = "<option value=\"" . $key . "\" " . $selected . ">" . $value . "</option>";
		}
		$result = "<select name=\"" . $data['name'] . "\">" . implode('', $option) . "</select>";
		return $result;
	}

	/**
     * 初始化商品分类树
     *
     * @param    int    $startId
     * @return   void
     */
     public function catTreeSelect($startId = null){
     	$startId = ($startId) ? $startId :'0';
     	$data = $this -> getCacheCats($startId);
	    $num = count($data);
	    $index = 1;
	    foreach ($data as $key => $cat)
	    {
	    	if ($index == $num) {
			    $this -> catExpandBranch($cat["cat_id"], $cat["cat_name"], "0");
	    	} else {
			    $this -> catExpandBranch($cat["cat_id"], $cat["cat_name"], "1");
	    	}
		    $index++;
	    }
    }

    /**
     * 生成指定商品分类的分类树
     *
     * @param    int    $catId
     * @param    int    $classes
     * @param    int    $tab
     * @param    array  $discount
     * @return   void
     */
    private function catExpandBranch($catId, $catName, $tab)
    {
        $tabs = explode(",", $tab);
        $option_item = "";
	    $i=0;

	    for ($i=0; $i < count($tabs); $i++)
	    {
		    if ($i == count($tabs)-1) {
			    if ($tabs[$i] == '0') {
				    $option_item .= "└";
			    } else {
				    $option_item .= "├";
			    }
		    } else {
			    if ($tabs[$i] == '0') {
				    $option_item .= "　";
			    } else {
				    $option_item .= "│";
			    }
		    }
	    }
	    $option_item .= $catName;
	    $this -> _catTreeSelectOption[$catId] = $option_item;
	    $data = $this -> getCacheCats($catId);
	    $num = count($data);
	    $index = 1;

	    if ($num > 0) {
	        foreach ($data as $key => $cat)
		    {
			    if ($index == $num) {
				    $this -> catExpandBranch($cat["cat_id"], $cat["cat_name"], $tab.",0");
			    } else {
				    $this -> catExpandBranch($cat["cat_id"], $cat["cat_name"], $tab.",1");
			    }
			    $index++;
		}
	    }
    }

    /**
     * 检查商品有效性
     *
     * @return   array()
     */
    public function checkGoods($goodsId = 0)
    {
        return $this -> _db -> checkGoods($goodsId);
    }
    /**
     * 把商品放入暂存架
     *
     * @param    int    $goodsId
     * @return void
     */
    public function addFavorite($goodsId)
    {
        $user = Purchase_Models_API_Auth :: getInstance() -> getAuth();
        if (!$this -> _db -> checkFavorite($goodsId, $user['member_id'])) {
            $data = array('member_id' => $user['member_id'],
                          'user_id' => $user['user_id'],
                          'goods_id' => $goodsId,
                          'add_time' =>time());
             $res =  $this -> _db -> addFavorite($data);
             
             //------------收藏日志 start--------------
             $logs = array();
             $logs['member_id'] = $user['member_id'];
             $logs['product_id'] = $goodsId;
             $logs['operation_time'] = time();
             $logs['type'] = 'add';
             $logApi = Shop_Models_API_BehaviorLog::getInstance();
             $logApi->favoriteLog($logs);
             //--------收藏日志 end--------------
             
             return $res;
          
        } else {
            return false;
        }
    }
    /**
     * 删除暂存架的商品
     *
     * @param  int    $favoriteId
     * @return void
     */
    public function delFavorite($favoriteId)
    {
        $user = Purchase_Models_API_Auth :: getInstance() -> getAuth();
      
        $goods_id =  $this->_db->getFavoriteInfo($favoriteId);
        $res =  $this -> _db -> delFavorite($favoriteId, $user['user_id']);
        //------------收藏日志 start--------------
        $logs = array();
        $logs['member_id'] =  $user['member_id'];
        $logs['product_id'] = $goods_id;
        $logs['operation_time'] = time();
        $logs['type'] = 'remove';
        $logApi = Shop_Models_API_BehaviorLog::getInstance();
        $logApi->favoriteLog($logs);
        //--------收藏日志 end--------------
        
        return  $res;
    }

    /**
     * 获得商品基本价格
     *
     * @price_seg   array
     * @org_price   double
     * @number      int
     * @return      array
     */
	public function getPrice($price_seg, $org_price, $number) {
	    if ( $price_seg ) {
		    for ($i = 0; $i < count($price_seg); $i++) {
				if ($price_seg[$i][2]) {
				    if ( ($number >= $price_seg[$i][1]) && ($number <= $price_seg[$i][2]) ) {
				        return sprintf("%1\$.2f", $price_seg[$i][0]);
				    }
			    }
			    else {
				    if ( $number >= $price_seg[$i][1] ) {
				        return sprintf("%1\$.2f", $price_seg[$i][0]);
    			    }
    			}
	        }
	    }
	    return $org_price;
	}

	/**
     * 获得商品子分类缓存
     *
     * @top_cat_id  int
     * @attr_id     int
     * @return      array
     */
	public function getCatByCacheFile($cat_id, $top_attr_id = null) {
	    global $tree;

	    if ($tree[$cat_id]) {
	        if ( $top_attr_id ) {
	            $cat_id_array = $this -> getSubCatByAttr( $cat_id, $top_attr_id );
	        }
            if(count($tree[$cat_id])>0){
                foreach ( $tree[$cat_id] as $index => $value ) {
                    if (is_array($cat_id_array)  &&  !in_array($value[0], $cat_id_array) )  continue;
                    $result[$index]['cat_id'] = $value[0];
                    $result[$index]['cat_name'] = $value[1];
                    $result[$index]['cat_count'] = $value[2];
                    $result[$index]['price'] = $value[3];
                    $result[$index]['staff_price'] = $value[4];
                    $result[$index]['url_alias'] = $value[5];
                }

            }

	    }

	    return $result;
	}

	/**
     * 获得当前商品分类缓存
     *
     * @top_cat_id  int
     * @attr_id     int
     * @return      array
     */
	public function getCurrentCatByCacheFile($top_cat_id, $cat_id) {
	    global $tree;

	    if ($tree[$top_cat_id]) {
	        foreach ( $tree[$top_cat_id] as $index => $value ) {
	            if ( $value[0] == $cat_id ) {
    	            $result['cat_id'] = $value[0];
    	            $result['cat_name'] = $value[1];
    	            $result['cat_count'] = $value[2];
    	            $result['price'] = $value[3];
    	            $result['staff_price'] = $value[4];
    	            $result['url_alias'] = $value[5];
    	        }
	        }
	    }

	    return $result;
	}

	/**
     * 加载分类URL别名缓存文件
     *
     * @return      array
     */
	public function includeCatAliasCacheFile() {
	    global $categoryAlias;

	    $filename = "../data/shop/cache/url_alias/category.php";
	    if ( !file_exists($filename) )  return false;

	    include_once($filename);
	}

	/**
     * 加载商品URL别名缓存文件
     *
     * @return      array
     */
	public function includeGoodsAliasCacheFile() {
	    global $goodsAlias;

	    $filename = "../data/shop/cache/url_alias/goods.php";
	    if ( !file_exists($filename) )  return false;

	    include_once($filename);
	}


	/**
	 * 记录搜索词
	 *
	 * @param string $words
	 *
	 * @return string
	 */
	public function tjSearchWords($words) {
		$this -> _db -> tjSearchWords(trim($words));
	}

	/*Start::搜索*/
	/**
	 * 得到搜索的ids
	 *
	 * @param array $search
	 * @param string $fields
	 * @param int $page
	 * @param int $pageSize
	 * @param string $orderBy
	 *
	 * @return array
	 */
	public function getGoodsIds($where = null, $page=null, $pageSize = null, $orderBy = null){
		if(isset($where['keyword']) && $where['keyword']!=''){
			$words = trim($where['keyword']);
			if(!$words)return null;
			$sourceWords = $words;
			if(isset($_SESSION['searchkeywords']) && isset($_SESSION['splitsearchkeywords']) && ($words == $_SESSION['searchkeywords'])){
				$words = $_SESSION['splitsearchkeywords'];
			}else{
				//统计用户搜索词汇，放入数据库，定期筛选
	    		$this -> _db -> tjSearchWords($words);
				//存储原始搜索词
				$_SESSION['searchkeywords'] = $words;
				//过滤
				$search = array(",", "/", "\\", ".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】", "*");
		    	$words = trim(str_replace($search,' ',$words));
		    	if($words==''){return null;}
		    	//关键词长度>2个才执行分词程序
		    	if(mb_strlen($words,'utf8')>2){
			    	/*Start::分词*/
			    	$words = iconv('utf-8', 'gbk', $words);//字符串转换成gbk
			    	include 'Custom/Model/SplitWord.php';
			    	$sp = new SplitWord();
			    	$words = $sp->SplitRMM($words);
			    	$words = iconv('gbk', 'utf-8', $words);//结果转成utf-8
		    	}
		    	//分词存入session，方便程序第二次调用
		    	$_SESSION['splitsearchkeywords'] = $words;
		    	//分词存入cookie，方便js调用
		    	setcookie('searchkeywords', $words, time () + 86400 * 1, '/');
	    		/*End::分词*/
			}
	    	//分割
	    	$words = explode(' ', $words);
			//把原始的词加入
	    	$words[] = $sourceWords;
	    	//冒泡排序
	    	$wl = count($words);
	    	for($i=0;$i<$wl-1;$i++){
	    		for ($j=$i+1;$j<$wl;$j++){
	    			if(mb_strlen($words[$i]) < mb_strlen($words[$j])){
	    				$tmp = $words[$i];
	    				$words[$i] = $words[$j];
	    				$words[$j] = $tmp;
	    			}
	    		}
	    	}
	    	$whereGroupGoods .= '(';
			foreach ($words as $vv){
	    		if(strlen($vv)>3){
	    			//商品
		    		$whereSql = " a.keywords like '%{$vv}%' ";
		    		$whereS   = " goods_name like '%{$vv}%' ";
		    		$whereCat = " cat_name = '{$vv}' ";
	    			$rs[] = $this -> _db -> getGoodsIds($whereSql, $whereS, $whereCat, $page, $pageSize);
	    			$vv && $whereGroupGoods   .= " group_goods_name like '%{$vv}%' or";
	    		}
	    	}


	    	$whereGroupGoods = substr($whereGroupGoods, 0,-2);
	    	$whereGroupGoods .= ')';
    		//组合商品
    		$grs = $this -> _db -> getGroupGoodsIds($whereGroupGoods);
    		if($grs){
    			foreach ($grs as $vvv){
    				$gids[] = $vvv['group_id'];
    			}
    		}
    		//
	    	$ids = null;
	    	if(is_array($rs) && count($rs)){
	    		foreach ($rs as $k=>$v){
	    			if(is_array($v) && count($v)){
	    				foreach ($v as $index=>$val){
	    					$ids[] = $val['goods_id'];
	    				}
	    			}
	    		}
	    	}else{
	    		return null;
	    	}
	    	if($ids)$ids = array_unique($ids);
	    	if($ids)foreach ($ids as $k=>$v){$iids[] = $v;}
	    	return array('tot'=>count($iids),'goods'=>$iids,'gtot'=>count($gids),'groupgoods'=>$gids);
		}else{
			return null;
		}
	}

	/**
	 * 得到搜索结果
	 *
	 * @param array $ids
	 *
	 * @return array
	 */
	public function getGoods($ids) {
		if(is_array($ids) && count($ids)){
			return $this -> _db -> getGoods($ids);
		}else{
			return false;
		}
	}
	/**
	 * ajax 得到搜索数量
	 *
	 * @param string $where
	 *
	 * @return int
	 */
	public function doAjaxSearch($where = null){
		$words = trim($where);
		if(!$words)return null;
		$sourceWords = $words;
		//过滤
		$search = array(",", "/", "\\", ".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】", "*");
		$words = trim(str_replace($search,' ',$words));
    	if($words==''){return null;}
    	if(isset($_SESSION['searchkeywords']) && isset($_SESSION['splitsearchkeywords']) && ($words == $_SESSION['searchkeywords'])){
			$words = $_SESSION['splitsearchkeywords'];
		}else{
	    	/*Start::分词*/
	    	$words = iconv('utf-8', 'gbk', $words);//字符串转换成gbk
	    	include 'Custom/Model/SplitWord.php';
	    	$sp = new SplitWord();
	    	$words = $sp->SplitRMM($words);
	    	$words = iconv('gbk', 'utf-8', $words);//结果转成utf-8
    		/*End::分词*/
		}
    	//分割
    	$words = explode(' ', $words);
		//把原始的词加入
    	$words[] = $sourceWords;
    	//冒泡排序
    	$wl = count($words);
    	for($i=0;$i<$wl-1;$i++){
    		for ($j=$i+1;$j<$wl;$j++){
    			if(mb_strlen($words[$i]) < mb_strlen($words[$j])){
    				$tmp = $words[$i];
    				$words[$i] = $words[$j];
    				$words[$j] = $tmp;
    			}
    		}
    	}
    	foreach ($words as $vv){
    		if(strlen($vv)>0){
	    		$whereSql = " a.keywords like '%{$vv}%' ";
	    		$whereS   = " goods_name like '%{$vv}%' ";
	    		$whereCat = " cat_name = '{$vv}' ";
	    		$whereCatLike = " cat_name like '%{$vv}%' ";
    			$counts[$vv] = $this -> _db -> doAjaxSearch($whereSql, $whereS, $whereCat, $whereCatLike);
    		}
    	}

    	return $counts;
	}
	/**
	 * 得到组合商品
	 *
	 * @param array $arr
	 *
	 * @return array
	 */
	public function getGroupGoods($ids) {
		if(is_array($ids) && count($ids)){
			return $this -> _db -> getGroupGoods($ids);
		}else{
			return false;
		}
	}
	/*End::搜索*/

    private function createTree(&$tree, $cat_arr) {
        if ($cat_arr) {
            $cat_id = $cat_arr[0];
            if ( !is_array($tree[$cat_id]) ) {
                $tree[$cat_id] = array();
            }
            array_shift($cat_arr);
            $this->createTree($tree[$cat_id], $cat_arr);
        }
        else    $tree = 1;
    }
    private function createPriceSeg($min_price, $max_price, $count) {
        $segCount = 6;
        if ( $count > $segCount)    $count = $segCount;

        if ( ($count == 1) || (($max_price - $min_price) < 10) ) {
            $result[] = '0-'.ceil( $max_price / 10) * 10;
        }
        else {
            $seg = ceil( round(($max_price - $min_price) / $count) / 10) * 10;
            $price = 0;
            for ( $i = 1; $i <= $count; $i++ ) {
                $priceSeg = $price;
                if ( $i != $count ) {
                    if ( ($price + $seg) > $max_price ) {
                        $result[] = $priceSeg;
                        break;
                    }
                    $priceSeg .= '-';
                    $price += $seg;
                    if ( $price < $min_price ) {
                        $price = ceil( $min_price / 10) * 10 + $seg;
                    }
                }
                else    $price = '';

                $result[] = $priceSeg.$price;
            }
        }

        for ( $i = 0; $i < count($result); $i++ ) {
            $str .= "'".$result[$i]."',";
        }

        return substr( $str, 0, -1 );
    }

    /**
     * 生成分类导航栏缓存
     *
     * @return   void
     */
    public function navCache()
    {
        global $tree;

        $data = $this -> getCat( "parent_id=0  and cat_id in (36,37,508,588)" , 'parent_id,cat_sort desc');
        if ( !$data )   return 'no top cat';

        $content = '<h2>所有商品分类</h2><dl>';
        for ( $i = 0; $i < count($data); $i++ ) {
            $cat_id = $data[$i]['cat_id'];
			if ( $data[$i]['url_alias'] )   $data[$i]['cat_url'] = $data[$i]['url_alias'];
			else                            $data[$i]['cat_url'] = 'gallery-'.$cat_id.'.html';
			$content .= $this -> createSubCat($data[$i]);
        }

        $content .= '</dl>';

	    file_put_contents('../app/Shop/Views/scripts/_library/navigation.tpl', $content);
        return 'finished';
    }
    private function createSubCat($data, $top_attr_id = 0)
    {
        global $tree;
        $tree = '';

        $cat_id = $data['cat_id'];

        $catData = $this -> _db -> getCat("cat_path like '%,{$cat_id},%'  and cat_status = 0");
        foreach ( $catData as $cat ) {
            $cat_id_array[] = $cat['cat_id'];
        }
        $second_goods_id_array = $this -> _db -> getGoodsIDByExpandCat($cat_id_array, $top_attr_id);

        foreach ( $second_goods_id_array as $goods_id ) {
            if ( !in_array($goods_id, $first_goods_id_array) ) {
                $first_goods_id_array[] = $goods_id;
            }
        }

        $filename = "../data/shop/cache/category/cat-{$cat_id}-{$top_attr_id}.php";
        if ( !file_exists($filename) )  return 'no file: '.$cat_id;
        include_once( $filename );
        $areaID = "cat-{$cat_id}-{$top_attr_id}";
        $content .= "<dt {{if \$cat_id eq {$cat_id} && \$top_attr_id eq {$top_attr_id}}}class=\"selected\"{{/if}}><em><a href=\"/{$data['cat_url']}\">{$data['cat_name']}</a></em>(".count($first_goods_id_array).")<span onclick=\"document.getElementById('{$areaID}').style.display='none'\">-</span></dt>
                     <dd class=\"clear\" id=\"{$areaID}\">";

        if ( $tree[$cat_id] ) {
            foreach ( $tree[$cat_id] as $sub_tree1 ) {
                $sub_cat_id = $sub_tree1[0];
                if ($cat_id_array && !in_array($sub_cat_id, $cat_id_array)) continue;

                if ( $sub_tree1[5] )    $cat_url = $sub_tree1[5];
                else                    $cat_url = 'gallery-'.$sub_cat_id.'.html';
                if ( $top_attr_id ) {
                    $cat_url .= "?top_attr_id={$top_attr_id}";
                }
                $cat_name = $sub_tree1[1];
                $content .= '<a href="/'.$cat_url.'" {{if $cat_id eq '.$sub_cat_id.'}}class="selected"{{/if}}>'.$cat_name.'</a>';
            }
            $content .= '</dd>';
        }

        return $content;
    }
    private function getSubCatByAttr($top_cat_id, $attr_id)
    {
        global $tree;

        $result = array();

        if ( !$tree[$top_cat_id] )  return $result;

        $cat_id_arr = array();
        for ( $i = 0; $i < count($tree[$top_cat_id]); $i++ ) {
            $cat_id_arr[] = $tree[$top_cat_id][$i][0];
        }

        $allCatAttrList = $this -> _db ->getAllCatAttr("cat_id in (".implode(',', $cat_id_arr).")");
        if ( !allCatAttrList )  return $result;

        foreach ( $allCatAttrList as $catAttr ) {
            $temparr = explode(',', $catAttr['attrs_sub']);
            if ( in_array($attr_id, $temparr) ) {
                $result[] = $catAttr['cat_id'];
            }
        }

        return $result;
    }
    /**
     * 获得cat的attr
     *
     * @param   integer $attr_id
     * @return  string
     */
     public function getBrandById($brand_id){
          return  array_shift($this -> _db ->getBrand(" brand_id='$brand_id'"));
     }

     /**
     * 判断是否是保健品或食品
     *
     * @param   integer $productID
     * @param   integer $type   1:保健品 2:食品
     * @return  boolean
     */
    public function isRootCat($productID, $type = 1)
    {
        $where = "t1.product_id = {$productID} and t2.cat_path like '%,{$type},%'";
        if ($this -> _db -> getProductCat($where))  return true;
        return false;
    }

    public function getCatNavTree(){
        return $this->_db->getCatNavTree();
    }

    /**
     * 根据类别ID 取得类别 面包屑
     * @param unknown_type $cat_id
     */
    public function getCatCrumbs($cat_id){
        return $this->_db->getCatCrumbs($cat_id);
    }

    /**
     * 取得类别下的兄弟类别列表
     * @param unknown_type $cat_id
     *
     */
    public function getCatSiblings($cat_id){
        return $this->_db->getCatSiblings($cat_id);
    }

    /**
     * 根据类别ID取得品牌
     */
    public function getBrandByCatId($cat_id){
        return $this->_db->getBrandByCatId($cat_id);
    }

    /**
     * 根据商品取得品牌ID
     */
    public function getBrandByGoods($arr_goods_id){
        
        $tbl = 'shop_goods as g|g.goods_id';
        $links = array(
            'shop_product as p'=>'p.product_id=g.product_id|p.brand_id',
            'shop_brand as b'=>'b.brand_id=p.brand_id|b.brand_name'
        );
        $where = array('g.goods_id|in'=>$arr_goods_id);
        
        $list_goods = $this->getAllWithLink($tbl,$links,$where);
        
        //品牌ID
        $list_brand = array();
        $arr_brand_id = array();
        foreach ($list_goods as $k=>$v){
            if(in_array($v['brand_id'], $arr_brand_id)) continue;
            $arr_brand_id[] = $v['brand_id'];
            $t = array();
            $t['brand_id'] = $v['brand_id'];
            $t['brand_name'] = $v['brand_name'];
            $list_brand[] = $v;
        }
        
        return $list_brand;
        
    }

    /**
     * 根据类别ID取得品牌
     */
    public function getCatNameById($cat_id){
        return $this->_db->getCatNameById($cat_id);
    }

    /**
     * 根据类别ID取得品牌
     */
    public function getCatById($cat_id){
        $objDb = new Purchase_Models_DB_Comn();
        return $objDb->getRow('shop_goods_cat',array('cat_id'=>$cat_id));
    }

    /**
     * 分页取商品数据
     *
     */
    public function getGoodsByPage(&$pagenav,$pn=1,$where=array(),$pagesize=32){

        $str_sort = '';
        if(!empty($where['sort'])){
            $sort = explode('_', $where['sort']);
            $sortname = $sort[0];
            $sorttype = $sort[1];
            if($sortname == 'uptime'){
                $str_sort = 'goods_update_time';
            }elseif($sortname == 'price'){
                $str_sort = 'price';
            }

            if($sorttype == 'd'){
                $str_sort .= ' desc';
            }elseif($sorttype == 'a'){
                $str_sort .= ' asc';
            }
        }

        $t = $this->_db->getGoodsByPage($pagenav,$pn,$pagesize,$where,$str_sort);
        return $t;
    }


    /**
     * 取得关联
     * @param unknown_type $id
     * @param unknown_type $limit_type
     */
    public function getRelation($id,$limit_type='single',$type='view',$num=5){
        return $this->_db->getRelation($id,$limit_type,$type,$num);
    }

    public function getGoodsByIds($arr_id){
        if(empty($arr_id)) return array();
        return $this->_db->getGoodsById($arr_id);
    }

    public function search(&$page,$arr_goods_id,$params=array(),$fn=0,$ps=20){
        
        $tbl = 'shop_goods as g|g.goods_id,g.goods_name,g.goods_img,g.price,g.market_price';
        $links = array();
        $links['shop_product as p'] = 'p.product_id=g.product_id';
        $links['shop_brand as b'] = 'b.brand_id=p.brand_id|b.as_name';
        
        $ord = 'FIND_IN_SET(g.goods_id,"'.implode(',',$arr_goods_id).'")';
        
        $where = array('g.goods_id|in'=>$arr_goods_id);
        if(!empty($params['bid'])){
            $where['p.brand_id'] = $params['bid']; 
        }
        
        if(!empty($params['price'])){
            $arr_price = explode('_', $params['price']);
            if(count($arr_price)<=1){
                $where['g.price|egt'] = $arr_price[0];
            }else{
                $where['g.price|egt'] = $arr_price[0];
                $where['g.price|elt'] = $arr_price[1];
            }
        }
        
        return $this->getListByPage($page, $tbl,$links,$where,$ord);
		
    } 
	
	public function getByIds($ids){
		
		if(!is_array($ids)) $ids = explode(',', trim($ids,',')); 
		
		$list = array();
		
		$tbl = 'shop_goods|goods_id,goods_name,goods_sn,price,market_price,goods_alt,goods_img,goods_arr_img';
		
		$list = $this->getAllWithLink($tbl,array(),array('goods_id|in'=>$ids),2);
		
		return $list;
	}
	
	public function getGroupGoodsByIds($ids){
		
		if(!is_array($ids)) $ids = explode(',', trim($ids,',')); 
		
		$list = array();
		
		$tbl = 'shop_group_goods|group_id,group_goods_name,group_goods_img,group_price,group_market_price,group_goods_alt';
		
		$list = $this->getAllWithLink($tbl,array(),array('group_id|in'=>$ids),2);
		
		return $list;
	}
	
	public function getGoodsAll(){
		$list = array();
		
		$tbl = 'shop_goods as g|g.goods_id,g.goods_sn,g.goods_name';
		
		$map = array();
		$map['g.onsale'] = 0;
		$map['g.is_gift'] = 0;
		$map['g.is_del'] = 0;
		$map['p.p_status'] = 0;

		$links = array();
		$links['shop_product as p'] = 'p.product_id=g.product_id|p.p_status';
		$links['shop_brand as b'] = 'b.brand_id=p.brand_id|b.as_name';
		
		$list = $this->getAllWithLink($tbl,$links,$map);
		
		return $list;
	}

	public function getGroupGoodsAll(){
		
		$list = array();
		
		$tbl = 'shop_group_goods|group_id,group_goods_name';
		
		$list = $this->getAllWithLink($tbl,array(),array('status'=>1));
		
		return $list;
	}
      
	
	public function sendGoodsNotcie($data)
	{
	     $notice =  $this->_db->getNoticeByAccount($data['account'],$data['goods_id']);	
	     if(!$notice)
	     {
	        $res =  $this->_db->addGoodsNotice($data);
	        return array('isok'=>$res,'msg'=>$res?'订阅到货通知成功':'订阅到货通知失败');
	     }else if($notice['status'] == 0){ //处理中
	     	return array('isok'=>false,'msg'=>'您已经订阅到货通知成功，无需重复提交');
	     }elseif ($notice['status'] == 1) //重新订阅
	     {
	     	$data['num'] = $notice['num']+1;
	        $res = 	$this->_db->updateGoodsNotice($data);
	        return array('isok'=>$res,'msg'=>$res?'订阅到货通知成功':'订阅到货通知失败');
	     }
	     
	}

	public function getOfferPriceById($gid){
		
		$list_goods = $this->getAll('shop_goods',array('goods_id'=>$gid),'goods_id,goods_sn,price,market_price');
		return $list_goods[0]['price'];
		
	}
	
}