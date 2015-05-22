<?php
class Admin_Models_API_Goods extends Custom_Model_Dbadv
{
	private $_table_goods_img = 'shop_goods_img';
    /**
     * DB对象
     */
	private $_db = null;
	
    /**
     * 错误信息
     */
	private $error;
	
	/**
	 * 词库
	 */
	public $dicfile;
	public $dicfilebak;
	
	/**
     * 上传路径
     */
	private $upPath = 'upload';
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
	    parent::__construct();
		$this -> _config = Zend_Registry::get('config');
		$this -> _db = new Admin_Models_DB_Goods();
		$this -> _group=new Admin_Models_API_GroupGoods();
		$this -> _product = new Admin_Models_DB_Product();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> dicfile = dirname($_SERVER['DOCUMENT_ROOT'])."/data/dictjiankang.dat";
		$this -> dicfilebak = dirname($_SERVER['DOCUMENT_ROOT'])."/data/admin/dictjiankang.bak.dat";
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
		if (is_array($where)) {
			$whereSql = "1=1";
		    $where['filter'] && $whereSql .= $where['filter'];
		    $where['onsale'] == 'on' && $whereSql .= " and onsale=0";
		    $where['onsale'] == 'off' && $whereSql .= " and onsale=1";
		    $where['cat_id'] && $whereSql .= " and d.cat_path LIKE '%," . $where['cat_id'] . ",%'";
		    if ($where['view_cat_id']) {
		        $whereSql .= " and d.cat_path LIKE '%," . $where['view_cat_id'] . ",%'";
		    }
            if (!is_null($where['is_del']) && $where['is_del'] !== '') {
                $whereSql .= " and (a.is_del='" . $where['is_del']. "')";
            }
		    $where['goods_sn'] && $whereSql .= " and (goods_sn LIKE '" . trim($where['goods_sn']). "%')";
		    $where['goods_name'] && $whereSql .= " and goods_name LIKE '%" . trim($where['goods_name']) . "%'";
		    $where['cat_name'] && $whereSql .= " and (b.cat_name LIKE '%" . trim($where['cat_name']) . "%' or d.cat_name LIKE '%" . trim($where['cat_name']) . "%')";
		    $where['goods_img'] && $whereSql .= " and (goods_img = '' or goods_img is null)";
			$where['goods_arr_img'] && $whereSql .= " and (goods_arr_img = '' or goods_arr_img is null)";
			($where['fromdate']) ? $whereSql .= " and goods_add_time >=" . strtotime($where['fromdate']) : "";
			($where['todate']) ? $whereSql .= " and goods_add_time <" . (strtotime($where['todate'])+86400) : "";
            $where['sid'] && $whereSql .= " and (sid='" . $where['sid']. "')";
            $where['brand_id'] && $whereSql .= " and (c.brand_id='" . $where['brand_id']. "')";

		    if ($where['fromprice'] && $where['toprice']) {
			    $fromprice = intval($where['fromprice']);
			    $toprice = intval($where['toprice']);
			    if($fromprice <= $toprice) $whereSql .= " and (price between $fromprice and $toprice)";
	        }
	        if ($where['fromprice_market'] && $where['toprice_market']) {
			    $fromprice = intval($where['fromprice_market']);
			    $toprice = intval($where['toprice_market']);
			    if($fromprice <= $toprice) $whereSql .= " and (market_price between $fromprice and $toprice)";
	        }
	        if ($where['fromprice_staff'] && $where['toprice_staff']) {
			    $fromprice = intval($where['fromprice_staff']);
			    $toprice = intval($where['toprice_staff']);
			    if($fromprice <= $toprice) $whereSql .= " and (staff_price between $fromprice and $toprice)";
	        }
	        if ($where['brand_name']) {
	            $brand_data = $this->getBrand($where['brand_name']);
	            if ($brand_data) {
	                $whereSql .= " and c.brand_id={$brand_data[0]['brand_id']}";
	            }
	            else    $whereSql .= " and c.brand_id=0";
	        }

			$where['price_limit'] && $whereSql .= " AND a.price < c.price_limit ";
		}else{
			$whereSql = $where;
		}

		if ($where['sort']) {
			switch ($where['sort']){
				case 1:
					$orderBy = "goods_id desc";
					break;
				case 2:
					$orderBy = "sort_sale desc";
					break;
				case 3:
					$orderBy = "price asc";
					break;
				case 4:
					$orderBy = "price desc";
					break;
				default:
					break;
			}
		}
		$datas = $this -> _db -> fetch($whereSql, $fields, $page, $pageSize, $orderBy);
		foreach ($datas as $num => $data)
        {
	        $datas[$num]['goods_status'] = ($datas[$num]['onsale']) ? '<font color="red">下架</font>' : '上架';
	        $datas[$num]['ginfo'] = Zend_Json::encode($datas[$num]);
        }
        return $datas;
	}
	
	/**
     * 获取总数
     *
     * @return   int
     */
	public function getCount($type=null)
	{
			if($type&&$type==2){
				return $this->_group->getCount();
			}else{
				return $this -> _db -> total;
			}
	}
	//取组合商品
	public function getgroup($page=1,$where = null,$fields = '*' ){
		if(is_array($where)){
		 $wheresql=" 1=1 ";
		 if($where['goods_sn']){
			$wheresql.=" and group_sn='".$where['goods_sn']."'";
		 }
		 if($where['goods_name']){
			$wheresql.=" and group_goods_name like '%".$where['goods_name']."%'";
		 }
		}else{
			$wheresql=$where;
		}
        $data=$this->_group ->getgroupsgoods($wheresql,$fields,$page);

		// 如果商店ID参数不为空，获取产品保护价格申请表的数据
		if (!empty($where['shop_id'])) {
			//加上限价
			$product_apply_db = new Admin_Models_DB_ProductApply();
			foreach ($data as &$value) {
				$group_params = array(
					'shop_id'    => $where['shop_id'],
					'type'       => '2',
					'start_ts'   => date('Y-m-d H:i:s'),
					'product_sn' => $value['group_sn'],
				);

				$apply_info = $product_apply_db->getInfoByCondition($group_params);
				if (!empty($apply_info)) {
					$value['price_limit'] = $apply_info['price_limit'];
				}
			}
		}

        foreach($data as $k=>$v){
            $data2[$k]['goods_id']=$v['group_id'];
            $data2[$k]['goods_sn']=$v['group_sn'];
            $data2[$k]['goods_name']=$v['group_goods_name'];
            $data2[$k]['price']=$v['group_price'];
			$data2[$k]['price_limit'] = $v['price_limit'];
            $data2[$k]['goods_style']=$v['group_specification'];
            $data2[$k]['goods_status']=$v['status']==0?'<font color="red">下架</font>':'上架';
            $data2[$k]['store']=$v['group_stock_number'];
            $data2[$k]['onsale']=$v['status'];
            $data2[$k]['ginfo'] = Zend_Json::encode($data2[$k]);
        }
		
	    return $data2;
	}
	
	/**
     * 获取品牌列表
     *
     * @return   array
     */
	public function getBrand($brand_name = null)
	{
        if ($brand_name) {
            $where = "where brand_name like '%{$brand_name}%'";
        }
        return $this -> _db -> getBrand($where);
	}
	
	/**
     * 获取标签列表
     *
     * @param    string    $where
     * @return   array
     */
	public function getAllTag($where = null,$page=null, $pageSize = null)
	{
		if ($where['tag']) {
		    $wheresql .= " and  tag like '%{$where['tag']}%'";
		}
		if ($where['title']) {
		    $wheresql .= " and title like '%{$where['title']}%'";
		}
		return $this -> _db -> getAllTag($wheresql,$page, $pageSize);
	}


	/**
     * 添加商品新标签
     *
     * @param    string    $where
     * @return   array
     */
	public function addTag($data)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        $data['admin_name'] = $this -> _auth['admin_name'];
		return $this -> _db -> addTag($data);
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where,$type='goods')
	{  
		$data = array_shift($this -> _db -> getTag($where));
		if ($data['config']){
			$ids = $data['config'];
			if($type=='goods') {
			    $result['details'] = $this -> get("a.goods_id in($ids)", 'a.goods_id,a.onsale,c.cat_id,b.cat_path,goods_name,goods_img,goods_sn,market_price,price', null, null, "find_in_set(a.goods_id, '$ids')");
			}elseif($type=='groupgoods'){
				$res= $this ->_group->getgroupsgoods("group_id in($ids)", 'group_id,group_goods_name,group_sn,status');
				foreach($res as $k=>$v){
					$result['details'][$k]['goods_id']=$v['group_id'];
					$result['details'][$k]['goods_sn']=$v['group_sn'];
					$result['details'][$k]['goods_name']=$v['group_goods_name'];
					$result['details'][$k]['goods_status']=$v['status']==0?"<font color='red'>下架</font>":"上架";
				}
			}elseif($type=='brand'){

	            $apiBrand=new Admin_Models_API_Brand();
				$res= $apiBrand -> get("brand_id in($ids)", 'brand_id,brand_name,logo,as_name');
				foreach($res as $k=>$v){
					$result['details'][$k]['goods_id']=$v['brand_id'];
					$result['details'][$k]['goods_sn']=$v['logo'];
					$result['details'][$k]['goods_name']=$v['brand_name'];
					$result['details'][$k]['goods_status']=$v['as_name'];
				}
			}

		}
		$result['data'] = $data;
		return $result;
	}


	/**
     * 检查输入
     *
     * @param    string    $where
     * @return   array
     */
	public function checkinput($where)
	{
		$data = array_shift($this -> _db -> getTag($where));
		return $data;
	}

	/**
     * 添加关联商品
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function updateTag($data, $tag_id,$type=null)
	{
		if($tag_id > 0){
			if (is_array($data['goods_id']) && count($data['goods_id']) > 0){
				$val = implode(',', $data['goods_id']);
			    $where = "goods_id in(".implode(',', $data['goods_id']).")";
			}else{
				$val = '';
				$where = "goods_id > 0";
			}
			$this -> _db -> updateTag($tag_id, $val,$type);
			return true;
		}
	}
	
	/**
     * 添加商品
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function add($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());           
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
	    $add_time = time ();
		if ($data['goods_name'] == '') {
			$this -> error = 'no_name';
			return false;
		}
	    
		$result = $this -> _db -> fetch("goods_sn='{$data['goods_sn']}' ", "goods_name", null, null, null);
	    if($result){
	    	$this -> error = 'exists';
			return false;
	    }
	    
	    $product = array_shift($this -> _db -> getProduct("product_sn = '{$data['goods_sn']}'"));
	   if (!$product) {
	       $this -> error = 'not_exists_goods_sn';
		return false;
	   }
	    $data['product_id'] = $product['product_id'];//$product['product_id'];
        $data['brief'] = stripslashes($data['brief']);
        $data['act_notes'] = stripslashes($data['act_notes']);
		$data['description'] = stripslashes($data['description']);
		$data['spec'] = stripslashes($data['spec']);
        $data['introduction'] = stripslashes($data['introduction']);
        
	    $goods_id=$this -> _db -> insert($data);

		if(isset($data['srcList'])  && count($data['srcList']>=1)){
			foreach($data['srcList'] as $key => $var){
					if(is_array($var)){
						foreach($var as $k => $v){
								 $temp=explode(',',$v);
								 $arrData= array(
									'goods_id'=>$goods_id,
									'group_id'=>    $key,
									'attr_id'=>     $temp[0],
									'attr_value'=>  $temp[1],
									'attr_title'=>   $temp[2]
								);
								$this -> _db -> insertGoodsArr($arrData);
								unset($temp);					
						}
					}else{
						 $temp=explode(',',$var);
						 $arrData= array(
							'goods_id'=>$goods_id,
							'group_id'=>    $key,
							'attr_id'=>     $temp[0],
							'attr_value'=>  $temp[1],
							'attr_title'=>   $temp[2]
						);
						$this -> _db -> insertGoodsArr($arrData);
						unset($temp);
					}
			};
		}

		$product=array(
			'goods_id'=>$goods_id,
			'product_sn'=>$data['goods_sn']
		);
		
		//Start::商品 扩展分类  （对应表：shop_goods_in_cat）
		if(isset($data['other_cat_id']) && count($data['other_cat_id']) > 0){
			foreach ($data['other_cat_id'] as $val){
				if($val){
					$this -> _db -> insertGoodsInCat($goods_id, $val);
				}
			}
		}
		//End::商品 扩展分类
		/*
			Start::商品关键字  
			1.添加到表shop_goods_keywords
			2.添加到词库 dictjiankang.dat
		*/
		if(isset($data['goods_keywords'])){
			$keys = str_replace(' ', '', trim($data['goods_keywords']));
			if($keys){
				$tmpArr = explode('|', $keys);
				$str = '';
				foreach ($tmpArr as $kw){
					if($kw != ''){
						$arr[] = $kw;
					}
				}
				if(is_array($arr) && count($arr)){
					$str = implode('|', $arr);
					$str = $data['goods_sn'].'|'.$str;
					//$str写入shop_goods_keywords表
					$dat['goods_id'] = $goods_id;
					$dat['keywords'] = $str;
					$this -> _db -> addkeywords($dat);
					//$arr添加到 词库
					$this -> updateDict($str);
				}
			}
		}
		/*End::商品关键字*/
		
     	return $goods_id;
	}
	
	public function updateAsnameById($id){
	    $r_goods = $this->getRow('shop_gooods',array('goods_id'=>$id),'product_id');
	    $r_product = $this->getRow('shop_product',array('product_id'=>$r_goods['product_id']),'brand_id');
	    $r_brand = $this->getRow('shop_brand',array('brand_id'=>$r_product['brand_id']),'as_name');
	    $as_name = $r_brand['as_name'];
	    return $this->update('shop_goods', array('as_name'=>$as_name),array('goods_id'=>$id));
	}
	
	/**
     * 添加或修改数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function edit($data, $id)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());        
        $data = Custom_Model_Filter::filterArray($data, $filterChain);	
        $data['brief'] = stripslashes($data['brief']);
		$data['act_notes'] = stripslashes($data['act_notes']);
		$data['description'] = stripslashes($data['description']);
		$data['spec'] = stripslashes($data['spec']);
        $data['introduction'] = stripslashes($data['introduction']);

		if(count($data['srcList']>=1)){
			foreach($data['srcList'] as $key => $var){
					if(is_array($var)){
						foreach($var as $k => $v){
								 $temp=explode(',',$v);
								 $arrData= array(
									'goods_id'=>$id,
									'group_id'=>    $key,
									'attr_id'=>     $temp[0],
									'attr_value'=>  $temp[1],
									'attr_title'=>   $temp[2]
								);
								$this -> _db -> insertGoodsArr($arrData);
								unset($temp);					
						}
					}else{
						 $temp=explode(',',$var);
						 $arrData= array(
							'goods_id'=>$id,
							'group_id'=>    $key,
							'attr_id'=>     $temp[0],
							'attr_value'=>  $temp[1],
							'attr_title'=>   $temp[2]
						);
						$this -> _db -> insertGoodsArr($arrData);
						unset($temp);
					}
			};
		}

		$this -> _db -> update($data, (int)$id);
		
		//Start::商品 扩展分类  （对应表：shop_goods_in_cat）
		if(isset($data['other_cat_id']) && count($data['other_cat_id']) > 0){
			//先删除 此商品原来的扩展分类
			$this -> _db -> delGoodsInCat($id);
			//插入
			foreach ($data['other_cat_id'] as $val){
				if($val){
					$this -> _db -> insertGoodsInCat($id, $val);
				}
			}
		}
		//End::商品 扩展分类
		
		//日志记录开始
	    $row = array (
                      'goods_id' => $id,
                      'admin_name' => $this -> _auth['admin_name'],
                      'op_type' => 'goods',
                      'remark' => '商品资料修改',
                      'op_time' => time(),
                      );
	    $this -> _db -> insertOp($row);
		return true;
	}
	/**
     * 添加具体商品
     *
     * @return   void
     */
	public function addProduct($products)
	{
        $productData = $this -> _db -> getProduct("product_sn = '{$products['product_sn']}'");
        if ( $productData ) {
            $this -> _db -> updateProductGoodsID($productData[0]['product_id'], "goods_id", $products['goods_id']);
        }
        else {
    		$stock = new Custom_Model_Stock_Config();
    		$status = $stock -> _logicStatus;
    	    $areas = $stock -> _logicArea;
    	    $sys_sn = $this -> getRand(1);
    	    $products['sys_sn'] = $sys_sn[0];
    	    $lastId = $this -> _db -> addProduct($products);
    		$stockStatusFix = '';
    		foreach ($status as $k => $v) {
    			$stockStatusFix .= '('.$lastId.', '.$k.'),';
    		}
    		foreach ($areas as $key => $val) {
    			foreach ($status as $k => $v) {
    				$stockStatus .= '('.$key.', '.$lastId.', '.$k.'),';
    			}
    			$this -> _db -> insertStockStatus(substr($stockStatus, 0, -1));
    			unset($stockStatus);
    		}
    		$this -> _db -> insertStockStatusFix(substr($stockStatusFix, 0, -1));
        }
        
	    return 	true;
	}
	
	/**
     * 获取关联商品
     *
     * @param    int       $goods_id
     * @return   array
     */
	public function getLink($goods_id,$type)
	{
		$datas = $this -> _db -> getLink($goods_id,$type);
        foreach ($datas as $num => $data)
        {
	       if($type==null){ 
			   $datas[$num]['goods_status'] = $datas[$num]['onsale']==0 ? '上架' : '下架';
		   }else{
			   $datas[$num]['goods_status'] = $datas[$num]['onsale']==0 ? '下架' : '上架';	
		   }
        }
        return $datas;
	}
	
	/**
     * 添加关联商品
     *
     * @param    int    $data
     * @param    int    $goods_id
     * @return   void
     */
	public function addLink($data, $goods_id,$type=null)
	{
		if($goods_id> 0){
			if(is_array($data['goods_id'])){
				foreach($data['goods_id'] as $key => $val){
				    $this -> _db -> addLink($goods_id, $val,$type);
				}
				return true;
			}else{
				$this -> error = 'error';
				return false;
			}
		}
	}
	
	/**
     * 删除关联商品数据
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteLink($id,$type=null)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> deleteLink((int)$id,$type);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		    return $result;
		}
	}
	
	/**
     * 删除数据
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteGoods($id,$value)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> deleteGoods((int)$id,$value);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		    return $result;
		}
	}
	
	/**
     * 获取状态信息
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
		       return '<a href="javascript:fGo()" onclick="openDiv(\''.$url.'/id/'.$id.'/status/1\', \'ajax\', \'商品下架\',400,200);" title="点击设为下架"><u>上架</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为上架"><u><font color=red>下架</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
	/**
     * 更改状态
     *
     * @param    int       $id
     * @param    int       $status
     * @param    string    $remark
     * @return   void
     */
	public function changeStatus($id, $status, $remark = '')
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateStatus((int)$id, $status, $remark)) {
				//日志记录开始
			    $row = array (
		                      'goods_id' => $id,
		                      'old_value' => $status ? 0 : 1,
		                      'new_value' => $status,
		                      'admin_name' => $this -> _auth['admin_name'],
		                      'op_type' => 'onoff',
		                      'remark' => $remark,
		                      'op_time' => time(),
		                      );
			    $this -> _db -> insertOp($row);
		    	/*Start::插入联盟(2012.2.9添加)*/
		    	//$this -> insertToUnion($id);
		    	/*End::插入联盟(2012.2.9添加)*/
			}
		}
	}
	
	/**
     * ajax更新数据
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
	 * @param    string   $type
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val, $type)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
		$type = $filterChain->filter($type);
		
		if ((int)$id > 0) {
		    if ($this -> _db -> ajaxUpdate((int)$id, $field, $val, $type) <= 0) {
		        exit('failure');
		    }
		    else {
		        if ($field == 'url_alias') {
		            $this -> goodsAliasCache();
		        }
		    }
		}
	}
	
	/**
     * 更新商品价格
     *
     * @param    array    $set
     * @param    string   $where
     * @param    string   $type
     * @return   array
     */
    public function updatePrice($data, $id)
	{
	    $set = array(
	    	'market_price' => $data['market_price'],
	    	'price' => $data['price'],
	    	'staff_price' => $data['staff_price'],
	    	);
	    
	    $price_seg = array();
	    $max = 0;
	    $min = 10000;
	    for ( $i = 1; $i <= 5; $i++ ) {
	        $pricevar = 'price'.$i;
	        $quantityvar_from = "quantity{$i}_from";
	        $quantityvar_to = "quantity{$i}_to";
	        if ($data[$pricevar] && $data[$quantityvar_from]) {
	            if ($data[$quantityvar_from] == 1) {
	                $this -> error = 'price_seg_1';
	                return false;
	            }
	            if ($data[$quantityvar_to] == '')   $temparr[$data[$quantityvar_from]] = 1;
	            else {
	                for ( $j = $data[$quantityvar_from]; $j <= $data[$quantityvar_to]; $j++ ) {
	                    $temparr[$j] = 1;
	                } 
	            }
	            if ( $data[$quantityvar_from] > $max )  $max = $data[$quantityvar_from];
	            if ( $data[$quantityvar_to] > $max )    $max = $data[$quantityvar_to];
	            if ( $data[$quantityvar_from] < $min)   $min = $data[$quantityvar_from];
	            $price_seg[] = array($data[$pricevar], $data[$quantityvar_from], $data[$quantityvar_to] ? $data[$quantityvar_to] : '');
	        }
	    }
	    if ( $max > 0 ) {
	        for ( $i = $min; $i <= $max; $i++ ) {
	            if (!$temparr[$i]) {
	                $this -> error = 'price_seg';
	                return false;
	            }
	        }
	    }
	    $set['price_seg'] = serialize($price_seg);
	    $this -> _db -> updateGoods($set, "goods_id=$id");
	    //日志记录开始
	    $row = array (
                      'goods_id' => $id,
                      'old_value' => $data['old_value'],
                      'new_value' => Zend_Json::encode($data),
                      'admin_name' => $this -> _auth['admin_name'],
                      'op_type' => 'price',
                      'remark' => '商品价格修改',
                      'op_time' => time(),
                      );
	    $this -> _db -> insertOp($row);
	    return true;
	}
	
	/**
     * 导出商品资料
     *
     * @return void
     */
    public function export($where)
    {
        $excel = new Custom_Model_Excel();
		$excel -> send_header('goods.xls');
		$excel -> xls_BOF();
    	$title = array('商品ID', '商品编码', '商品名称', '前台链接', '系统商品分类', '市场价', '本店价', '员工价','状态', '商品分类路径','主图URL','品牌','规格','包装','国际码');
    	$col = count($title);
        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
		if (is_array($where)) {
			$whereSql = "1=1";
		    $where['filter'] && $whereSql .= $where['filter'];
		    $where['onsale'] == 'on' && $whereSql .= " and onsale=0";
		    $where['onsale'] == 'off' && $whereSql .= " and onsale=1";
		    $where['cat_id'] && $whereSql .= " and cat_path LIKE '%," . $where['cat_id'] . ",%'";
            $where['goods_sn'] && $whereSql .= " and (goods_sn LIKE '" . trim($where['goods_sn']). "%')";
		    $where['goods_name'] && $whereSql .= " and goods_name LIKE '%" . $where['goods_name'] . "%'";
		    $where['goods_img'] && $whereSql .= " and (goods_img = '' or goods_img is null)";
			$where['goods_arr_img'] && $whereSql .= " and (goods_arr_img = '' or goods_arr_img is null)";
			($where['fromdate']) ? $whereSql .= " and goods_add_time >=" . strtotime($where['fromdate']) : "";
			($where['todate']) ? $whereSql .= " and goods_add_time <" . (strtotime($where['todate'])+86400) : "";

            if ($where['is_del'] !== null && $where['is_del'] !== '') {
                $whereSql .=  " and is_del={$where['is_del']}";  
            }    

		    if ($where['fromprice'] && $where['toprice']) {
			    $fromprice = intval($where['fromprice']);
			    $toprice = intval($where['toprice']);
			    if($fromprice <= $toprice) $whereSql .= " and (price between $fromprice and $toprice)";
	        }
	        if ($where['fromprice_market'] && $where['toprice_market']) {
			    $fromprice = intval($where['fromprice_market']);
			    $toprice = intval($where['toprice_market']);
			    if($fromprice <= $toprice) $whereSql .= " and (market_price between $fromprice and $toprice)";
	        }
	        if ($where['fromprice_staff'] && $where['toprice_staff']) {
			    $fromprice = intval($where['fromprice_staff']);
			    $toprice = intval($where['toprice_staff']);
			    if($fromprice <= $toprice) $whereSql .= " and (staff_price between $fromprice and $toprice)";
	        }
	        if ($where['brand_name']) {
	            $brand_data = $this->getBrand($where['brand_name']);
	            if ($brand_data) {
	                $whereSql .= " and brand_id={$brand_data[0]['brand_id']}";
	            }
	            else    $whereSql .= " and brand_id=0";
	        }
		}else{
			$whereSql = $where;
		}
		$datas = $this -> _db -> fetchGoods($whereSql, "a.goods_id,goods_sn,goods_name,c.cat_id,cost,market_price,price,staff_price,onsale,goods_style,goods_units,a.brief,a.spec,a.description,b.cat_path,a.goods_img,a.goods_img,p.brand_name,ean_barcode ");
		foreach ($datas as $k => $v) {
                $nav = '';
                $path = substr($v['cat_path'], 1, -1);
                if ($path) {
                    $datas = $this -> _db ->getCat("  cat_id in($path) ", "  find_in_set(cat_id, '$path')");

                    foreach ($datas as $data) {
                        $nav .= $data['cat_name'].'/';
                    }
                } else {
                    $nav = '';
                }
			if($v['goods_img']){
				$goods_img='http://www.1jiankang.com/'.$v['goods_img'];
			}else{
				$goods_img='没有图片';
			}
            $v['goods_link']="/goods-".$v['goods_id'].".html";
            $v['brief']=preg_replace("/<(.*?)>/","",$v['brief']);
            $v['spec']=preg_replace("/<(.*?)>/","",$v['spec']);
            $v['description']=preg_replace("/<(.*?)>/","",$v['description']);
            //$description=  htmlspecialchars('<br>商品优势介绍<br>'.$v['brief'].'<br>适用人群<br>'.$v['tip'].'<br>规格说明<br>'.$v['spec'].'<br>商品说明<br>'.$v['description'].'<br>注意事项<br>') ; 
			$status = $v['onsale'] ? '下架' : '上架';
			//$row = array($v['goods_id'], $v['goods_sn'], $v['goods_name'],$v['goods_link'], $v['cat_name'], $v['market_price'], $v['price'], $v['staff_price'], $status, $nav ,$goods_img, $description, $v['brand_name'],$v['goods_style'],$v['goods_units'].'装',$v['ean_barcode']);
			$row = array($v['goods_id'], $v['goods_sn'], $v['goods_name'],$v['goods_link'], $v['cat_name'], $v['market_price'], $v['price'], $v['staff_price'], $status, $nav ,$goods_img, $v['brand_name'],$v['goods_style'],$v['goods_units'].'装',$v['ean_barcode']);
		    for ($i = 0; $i < $col; $i++) {
			    $excel -> xls_write_label($k+1, $i, $row[$i]);
			}
			flush();
		    ob_flush();
			unset($row);
        }
        unset($datas);
        $excel -> xls_EOF(); 
	}

	/**
     * 错误集合
     *
     * @param   array   $data
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error'=>'操作失败!',
			         'exists'=>'此款商品已存在!',
			         'not_exists'=>'该商品不存在!',
			         'forbidden'=>'禁止操作!',
			         'no_cat'=>'请选择分类!',
			         'no_child'=>'请选择子分类!',
			         'no_name'=>'请填写商品名称!',
			         'no_sn'=>'没有编码!',
			         'price_seg'=>'数量区间不连续!',
			         'price_seg_1'=>'起始数量必须大于等于2!',
			         'exists_goods_sn' => '商品编码已存在!',
			         'not_exists_goods_sn' => '商品编码不存在!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	
    /**
     * 得到某个商品所属扩展分类
     * 
     * @param int $goods_id
     * 
     * @return array
     */
    public function getGoodsInCat($goods_id) {
    	if($goods_id > 0){
    		return $this -> _db -> getGoodsInCat($goods_id);
    	}
    }
    
    /**
     * 取出某商品的关键字
     * 
     * @param int $goods_id
     * 
     * @return array
     */
    public function getKeywords($goods_id) {
    	if($goods_id){
    		return $this -> _db-> getKeywords($goods_id);
    	}else{
    		return array();
    	}
    }
    
    /**
     * 添加关键字
     * 
     * @param int $arr
     */
    public function addkeywords($data) {
    	$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
    	return $this -> _db -> addkeywords($data);
    }
    
    /**
     * 修改关键字
     * 
     * @param array
     */
    public function editkeywords($data) {
    	$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
    	return $this -> _db -> editkeywords($data);
    }
    
    /**
     * 由goods_id得到一个商品
     * 
     * @param int $goods_id
     * @return array
     */
    public function getOne($goods_id, $fields='*') {
    	if(intval($goods_id)){
    		return $this->_db->getOne($goods_id, $fields);
    	}
    }
    
    /**
     * 第一次使用一下，以后不再用
     * 
     */
    public function genkeywords() {
    	$this -> _db -> genkeywords();
    }
    
    /**词库词库词库词库
     * 读取词库 
     * 
     */
    public function readDict() {
    	//读取词库 到->$dic[]
    	$dicfile = $this -> dicfile;
	  	$fp = @fopen($dicfile,'r');
	  	if(!$fp)return false;
	  	while($line = fgets($fp,256)){
	  		  $ws = explode(' ',$line);
	  		  $val = iconv('gbk', 'utf-8', $ws[0]);
	  		  $dic[] = $val;
	  	}
	  	fclose($fp);
	  	return array_unique($dic);
    }
    
    /**
     * 生成词库
     * 
     * @param array $dict 词库数组
     * 
     * @return bool;
     */
    private function genDict($dict) {
    	if(!is_array($dict) && !count($dict))return false;
		if(is_file($this -> dicfilebak)){@unlink($this -> dicfilebak);}//如果有备份词库，删除
		if(copy($this -> dicfile,$this -> dicfilebak)){//备份
			@unlink($this -> dicfile);//删除原来词库
			file_put_contents($this -> dicfile, $dict);
			return true;
		}else{
			return false;//不能备份写入词库
		}
    }
    
	/**
     * 添加更新词库dictjiankang.dat
     * 
     * @param string $str
     */
    public function updateDict($str) {
    	$str = trim($str);
    	if($str == '') return false;
	  	$dic = $this -> readDict();
    	$tmp = explode('|', $str);
    	foreach($tmp as $v){
    		if(!in_array($v, $dic) && strlen($v)>3){
    			$dic[] = $v;
    		}
    	}
    	//保存文件
    	foreach ($dic as $v){
    		if($v){
    			$v=iconv('utf-8', 'gbk', $v);
				$dict.=$v.' '.chr(10);
    		}
		}
		return $this -> genDict($dict);
    }
    
    /**
     * 修改词库中的关键词
     * 
     * @param string $v 新值
     * @param string $ov 原值
     * 
     * @return bool
     */
    public function editKeywordDict($v,$ov) {
    	$v = trim($v); $ov = trim($ov);
    	if($v=='' || $ov==''){return false;}
    	$dict = $this -> readDict();
    	foreach ($dict as $val){
    		if($val == $ov){$val = $v;}
    		$val=iconv('utf-8', 'gbk', $val);
    		$tmp .= $val.' '.chr(10);
    	}
    	return $this -> genDict($tmp);
    }
    
    /**
     * 删除词库中的关键词
     * 
     * @param string $ov 原值
     * 
     * @return bool
     */
    public function delKeywordDict($ov) {
    	$ov = trim($ov);
    	if($ov==''){return false;}
    	$dict = $this -> readDict();
    	foreach ($dict as $val){
    		if($val == $ov){continue;}
    		$val = iconv('utf-8', 'gbk', $val);
    		$tmp .= $val.' '.chr(10);
    	}
    	return $this -> genDict($tmp);
    }
    
    /**
     * 生成商品URL别名缓存
     * 
     **/
	public function goodsAliasCache() {
	    $data = $this -> _db -> fetch("a.url_alias <> '' or a.url_alias <> null", "a.goods_id,a.url_alias", null, null, null);
	    $content = '<?php'.chr(13).chr(10);
	    if ( $data ) {
	        for ( $i = 0; $i < count($data); $i++ ) {
	            $content .= '$goodsAlias["'.$data[$i]['url_alias'].'"] = '.$data[$i]['goods_id'].';'.chr(13).chr(10);
	        }
	    }
	    
	    file_put_contents("../data/shop/cache/url_alias/goods.php", $content);
    }
	
    /**
     * 商品插入到联盟（按商品分成的联盟）
     * 
     * @param int $goods_id
     * 
     * @return bool
     */
    public function insertToUnion($id) {
    	//取出 按商品分成的联盟 ID
    	$pUnion_api = new Admin_Models_API_PUnion();
    	$unionList = $pUnion_api -> getPUnionBySearch(array('calculate_type' => 2));
    	if($unionList){
    		foreach ($unionList['content'] as $v){
    			// shop_union_goods 中是否已经存在此联盟的此商品记录
    			$rs = $pUnion_api -> getProductProportionList($v['user_id'], array('goods_id' => $id));
    			if(!is_array($rs['data']) || !count($rs['data'])){
    				//插入
    				$pUnion_api -> insertGoodsToUnionGoods($v['user_id'],$id);
    			}
	    	}
    	}
    	return;
    }



	/**
     * 获取关联商品文章
     *
     * @param    string    $where
     * @return   array
     */
	public function getLinkArticle($goods_id)
	{
		$data = $this->_db->getOne($goods_id,'goods_name,goods_sn,article_ids');
		if ($data['article_ids']){
			$ids = $data['article_ids'];
            $articleApi = new Admin_Models_API_Article();
			$result['details'] = $articleApi -> get(" and a.article_id in($ids)", 'article_id,cat_name,title,author,source,is_view,add_time,a.sort', null, null, "find_in_set(a.article_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}


	/**
     * 编辑关联商品文章
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function editlinkArticle($data, $goods_id )
	{
		if($goods_id > 0){
			if (is_array($data['article_id']) && count($data['article_id']) > 0){
				$val = implode(',', $data['article_id']);
			    $where = "article_id in(".implode(',', $data['article_id']).")";
			}else{
				$val = '';
				$where = "article_id>0";
			}
			$this -> _db -> updatelinkArticle($goods_id, $val);
			return true;
		}
	}

	/**
	 * 用户搜索统计列表
	 * 
	 * @param array $search
	 * @param int 4page
	 * 
	 * @return array
	 */
	public function getCustomerSearch($search=null, $page=null, $pageSize=null) {
		$orderBy = '';
		if(is_array($search) && count($search)){
			$wheresql = ' 1=1 ';
			$search['orderby'] && $orderBy = $search['orderby'];
			
			if(isset($search['searchcount'])){
				$ct = (int)$search['searchcount'];
				if($ct!=0){ $wheresql .= " and searchcount > ".abs($ct); }
			}
			$search['searchword'] && $wheresql .= " and searchword like '%".$search['searchword']."%' ";
			if($search['ctime'] && $search['ltime']){
				$t1 = strtotime($search['ctime']);
				$t2 = strtotime($search['ltime']);
				if($t1 > $t2){
					$tmp = $t1;$t1=$t2;$t2=$tmp;
				}
				$wheresql .= " and ctime > ".$t1;
				$wheresql .= " and ltime < ".$t2;
			}else{
				$search['ctime'] && $wheresql .= " and ctime > ".strtotime($search['ctime']);
				$search['ltime'] && $wheresql .= " and ltime < ".strtotime($search['ltime']);
			}
			if($search['status']!=''){
				if($search['status']==1 || $search['status']==2){
					$wheresql .= " and status = ".$search['status'];
				}
			}
		}else{
			$wheresql = ' 1=1 ';
		}
		return $this -> _db -> getCustomerSearch($wheresql,$page,$pageSize,$orderBy);
	}
	
	/**
	 * 删除用户搜索统计列表一条记录
	 * 
	 * @param int $id
	 **/
	public function delOneCustomerSearchword($id) {
		$id = (int)$id;
		if($id>0){
			$this -> _db -> delOneCustomerSearchword($id);
		}
	}
	/**
	 * 更新用户搜索
	 * 
	 * @param array $arr
	 * @param string $where
	 */
	public function updateCustomerSearchword($arr,$where) {
		$this -> _db -> updateCustomerSearchword($arr,$where);
	}

	/**
     * 获取标签列表
     *
     * @param    string    $where
     * @return   array
     */
	public function getAllViewTag($where = null,$page=null, $pageSize = null)
	{
		if ($where['type']) {
		    $wheresql .= " and  type = $where[type]";
		}
		if ($where['tag']) {
		    $wheresql .= " and  tag like '%{$where['tag']}%'";
		}
		if ($where['title']) {
		    $wheresql .= " and title like '%{$where['title']}%'";
		}
		return $this -> _db -> getAllViewTag($wheresql,$page, $pageSize);
	}
	/**
     * 添加或修改数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function editViewTag($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if(isset($data['goods_id'])){
			$data['goods_ids'] = implode(',', $data['goods_id']);
		}else{
			$data['goods_ids'] = null;
		}

		if ($id === null) {
		    $result = $this -> _db -> insertViewTag($data);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		} else {
			$result = $this -> _db -> updateViewTag($data, (int)$id);
		}
		return $result;
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getViewTag($where,$type=null)
	{
		$data = array_shift($this -> _db -> getViewTag($where));
		if($type&&$type==2){
			if ($data['goods_ids']){
				$ids = $data['goods_ids'];
				 $res= $this ->_group->getgroupsgoods("group_id in($ids)", 'group_id,group_goods_name,group_sn,status');
				foreach($res as $k=>$v){
					$result['details'][$k]['goods_id']=$v['group_id'];
					$result['details'][$k]['goods_sn']=$v['group_sn'];
					$result['details'][$k]['goods_name']=$v['group_goods_name'];
					$result['details'][$k]['onsale']=$v['status'];
				}
			}
		}else{
			if ($data['goods_ids']){
				$ids = $data['goods_ids'];
				$result['details'] = $this -> get("a.goods_id in($ids)", 'a.goods_id,goods_name,goods_sn,price', null, null, "find_in_set(a.goods_id, '$ids')");
			}
		}
		$result['data'] = $data;
		return $result;
	}
	
	/**
     * 获取组合商品标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getViewTag2($where)
	{
		$data = array_shift($this -> _db -> getViewTag($where));
     
		if ($data['goods_ids']){
			$ids = $data['goods_ids'];
             $res= $this ->_group->getgroupsgoods("group_id in($ids)", 'group_id,group_goods_name,group_sn,status');
			foreach($res as $k=>$v){
			$result['details'][$k]['goods_id']=$v['group_id'];
			$result['details'][$k]['goods_sn']=$v['group_sn'];
			$result['details'][$k]['goods_name']=$v['group_goods_name'];
			$result['details'][$k]['onsale']=$v['status'];
			}
		}
		$result['data'] = $data;
		return $result;
	}
	
	/**
	 * 删除一条记录
	 * 
	 * @param int $id
	 **/
	public function delViewTag($id) {
		$id = (int)$id;
		if($id>0){
			return $this -> _db -> delViewTag($id);
		}
	}
	
	/**
     * 得到百度数据以便生成xml文件
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getGoodsForBaidu($where = null, $fields = '*', $page=null, $pageSize = null, $orderBy = null)
	{
		if (is_array($where)) {
			$whereSql = "1=1";
		    $where['onsale'] == 'on' && $whereSql .= " and a.onsale=0";
		    $where['onsale'] == 'off' && $whereSql .= " and a.onsale=1";

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
			return $this -> _db -> getGoodsForBaidu($whereSql, $fields, $page, $pageSize, $orderBy);
		}
	}
	
	/**
     * 获取操作日志
     *
     * @param    string   $where
     * @return   array
     */
	public function getOp($where = null)
	{
		return $this -> _db -> getOp($where);
	}
	
	/**
     * 新增商品时检查商品编码是否合法
     *
     * @param    string   $goodsSN
     * @return   boolean
     */
	public function checkNewGoodsSN($goodsSN)
	{
	    if ($this -> _db -> fetchGoods("a.goods_sn = '{$goodsSN}' ")) {
	        $this -> error = 'exists_goods_sn';
	        return false;
	    }
	    if (!$this -> _db -> getProduct("product_sn = '{$goodsSN}' and p_status = 0")) {
	        $this -> error = 'not_exists_goods_sn';
	        return false;
	    }
	    return true;
	}
	
	/**
     * 新增商品时检查商品编码是否合法
     *
     * @param    string   $goodsSN
     * @return   boolean
     */
	public function updateGoodsImage($goods_id, $goods_img_ids)
	{
        if (is_array($goods_img_ids)) {
            $goods_img_ids = implode(',', $goods_img_ids);
        }
        else    $goods_img_ids = '';
        $this -> _db -> updateGoods(array('goods_img_ids' => $goods_img_ids), "goods_id = {$goods_id}");
	}
	
	/**
     * 上传图片  产品图片类型  0标准图  1色块图  2细节图  3展示图  4规格图
     *
     * @return   void
     */
	public function upimg($data, $goods_id, $goods_sn)
	{
		//echo "<script>alert(123);</script>";die;
        $_path = strtolower(substr(md5($goods_sn),0,2));
        $_path = $_path ? $_path : '00';
		$this -> upPath .= '/'.$_path.'/'.$goods_sn;
		$add_time = time();
		//添加修改产品标准图
		if(is_file($_FILES['goods_img']['tmp_name'])) {
		    $thumbs = '380,380|180,180|60,60';
			$upload = new Custom_Model_Upload('goods_img', $this -> upPath);
			$upload -> up(true, $thumbs);
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
			$this -> _db -> updateGoods(array('goods_img' => $img_url), "goods_id = {$goods_id}");
		}
		//添加修改规格图
		if(is_file($_FILES['goods_arr_img']['tmp_name'])) {
		    $thumbs = '380,380|180,180|60,60';
			$upload = new Custom_Model_Upload('goods_arr_img', $this -> upPath);
			$upload -> up(true, $thumbs);
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
			$this -> _db -> updateGoods(array('goods_arr_img' => $img_url), "goods_id = {$goods_id}");
		}
		
		//添加商品细节图片
		if(is_file($_FILES['img_ids']['tmp_name'][0])) {
			
			$thumbs = '380,380|180,180|60,60';
			$upload = new Custom_Model_Upload('img_ids', $this -> upPath);
			$upload -> up(true, $thumbs);
			
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			
			$goods = $this -> _db -> getOne($goods_id);
			
			$product_id = $goods['product_id'];
			
			foreach($upload->uploadedfiles as $k=>$v){
				
				$img_url = $this -> upPath.'/'.$upload->uploadedfiles[$k]['filepath'];
				$img_type = 2;
				$img_desc = $data['img_desc'][$k];
				$img_sort = $data['img_sort'][$k];
				$add_time = time();
				
				$row = array (
						'product_id' => $product_id,
						'img_url' => $img_url,
						'img_type' => $img_type,
						'add_time' => $add_time,
						'thumbs' => $thumbs,
						'img_desc' => $img_desc,
						'img_sort' => $img_sort,
				);
				$this -> _db -> insertImage($row);
			}
			
		}
		$goods = $this -> _db -> getOne($goods_id);
			
		$product_id = $goods['product_id'];
		//echo "<script>alert($goods_id);</script>";die;
		$ids = $this->_db->updateImgs($product_id,$goods_id);
		
	}
	/**
	 * 更新细节图片字段
	 * @param int $goods_id
	 * @param int $product_id
	 * @return multitype:
	 */
	public function updImg($product_id,$goods_id){
		$this->_db->updateImgs($product_id,$goods_id);
	}
	/**
	 * 根据ids 取得数据
	 * @param unknown_type $ids
	 * @return multitype:
	 */
	public function getByIds($ids){
	    
	    $links = array();
	    $links['shop_product as p'] = 'p.product_id=g.product_id|p.brand_id';
	    $links['shop_brand as b'] = 'b.brand_id=p.brand_id|b.as_name,b.brand_name';
	    
	    return parent::getAllWithLink('shop_goods as g|g.*',$links,array('g.goods_id|in'=>$ids));
	    
	}
	
}