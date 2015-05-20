<?php

class Admin_Models_API_Tuan
{
	/**
     * DB对象
     */
	private $_db = null;
	
    /**
     * 错误信息
     */
	private $error;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Tuan();
	}
	
	/**
     * 获取团购数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    $where['title'] && $wheresql .= "t1.title like '%{$where['title']}%' and ";
		    $where['goods_title'] && $wheresql .= "t2.title like '%{$where['goods_title']}%' and ";
		    $where['goods_name'] && $wheresql .= "t3.goods_name like '%{$where['goods_name']}%' and ";
		    $where['status'] !== null && $wheresql .= "t1.status = '{$where['status']}' and ";
		
		    if ( $where['fromdate'] ) {
		        $wheresql .= "t1.start_time >= ".strtotime($where['fromdate'])." and ";
		    }
		    if ( $where['todate'] ) {
		        $wheresql .= "t1.end_time <= ".strtotime($where['todate'].' 23:59:59')." and ";
		    }
		    $wheresql .= '1';
		}
		else    $wheresql = $where;
		
		return $this -> _db -> fetch($wheresql, $fields, $orderBy, $page, $pageSize);
	}
	
	/**
     * 获取团购商品数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getGoods($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    $where['title'] && $wheresql .= "t1.title like '%{$where['title']}%' and ";
		    $where['goods_name'] && $wheresql .= "t2.goods_name like '%{$where['goods_name']}%' and ";
		    $where['status'] !== null && $wheresql .= "t1.status = '{$where['status']}' and ";
		    $wheresql .= '1';
		}
		else    $wheresql = $where;
		
		return $this -> _db -> fetchGoods($wheresql, $fields, $orderBy, $page, $pageSize);
	}
	
	/**
     * 取得商品列表
     *
     * @param    array  $where
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getGoodsInfo($where = null, $page, $pageSize = null)
	{
		$goods_api = new Admin_Models_API_Goods();
		
		$whereSql .= " onsale=0";
        $where['cat_id'] && $whereSql .= " and cat_path LIKE '%," . $where['cat_id'] . ",%'";
		$where['goods_sn'] && $whereSql .= " and (goods_sn='" . $where['goods_sn']. "')";
		$where['goods_name'] && $whereSql .= " and goods_name LIKE '%" . $where['goods_name'] . "%'";
	
        $datas = $goods_api -> get($whereSql, 'a.goods_id,a.goods_name,a.goods_sn,a.goods_sort,a.market_price,a.price,c.cost,a.onsale', $page, $pageSize);
        foreach ($datas as $num => $data)
        {
        	$datas[$num]['onsale'] = ($datas[$num]['onsale']) ? '下架' : '上架';
        }
        
        return array('content' => $datas, 'total' => $goods_api -> getCount());
	}
	
	/**
     * 添加或修改团购
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function edit($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if ($data['title'] == '') {
			$this -> error = 'no_title';
			return false;
		}
		if ($data['start_time'] == '') {
			$this -> error = 'no_start_time';
			return false;
		}
		if ($data['end_time'] == '') {
			$this -> error = 'no_end_time';
			return false;
		}
		if ($data['goods_id'] == '') {
			$this -> error = 'no_tuan_goods';
			return false;
		}
		if ( $id ) {
		    $temp = $this -> get(" tuan_id <> {$id} and t1.title = '{$data['title']}'");
		}
		else {
		    $temp = $this -> get(" t1.title = '{$data['title']}'");
		}
		if ( $temp['total'] > 0 ) {
		    $this -> error = 'same_title';
			return false;
		}
		$data['add_time'] = time();
		if ($id === null) {
		    $result = $this -> _db -> insert($data);
		    
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		} else {
			$result = $this -> _db -> update($data, (int)$id);
		}
		return $result;
	}
	
	/**
     * 添加或修改团购商品
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function editGoods($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if ($data['title'] =='') {
			$this -> error = 'no_goods_title';
			return false;
		}
		if ($data['goods_id'] =='') {
			$this -> error = 'no_goods_id';
			return false;
		}
		if ($data['price'] == '') {
			$this -> error = 'no_goods_price';
			return false;
		}
		
		if ( $id ) {
		    $temp = $this -> getGoods(" id <> {$id} and title = '{$data['title']}'");
		}
		else {
		    $temp = $this -> getGoods(" title = '{$data['title']}'");
		}
		if ( $temp['total'] > 0 ) {
		    $this -> error = 'same_goods_title';
			return false;
		}
		
		$data['add_time'] = time();
		
		$_path = strtolower( substr(md5($data['goods_id']), 0, 2) );
		$this -> upPath .= 'upload/tuan/'.$_path;
		
		for ( $i = 1; $i <= 5; $i++ ) {
		    $imgField = 'img'.$i;
		    
		    if( is_file($_FILES[$imgField]['tmp_name']) ) {
    		    $thumbs = '350,350|100,100';
    		    $upload = new Custom_Model_Upload( $imgField, $this -> upPath );
    		    $upload -> up( true, $thumbs );
    		    if( $upload -> error() ) {
        			$this -> error = $upload -> error();
        			return false;
    		    }
    	        $data[$imgField] = $this -> upPath.'/'.$upload -> uploadedfiles[0]['filepath'];
    		}
		}
		
		if ($id === null) {
		    $result = $this -> _db -> insertGoods($data);
		    
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		} else {
			$result = $this -> _db -> updateGoods($data, (int)$id);
		}
		return $result;
	}
	
	/**
     * 按照ID查询商品
     *
     * @param    string    $id
     * @return   array
     */
	public function getGoodsById($id = null)
	{
		return $this -> _db -> getGoodsById($id);
	}
	
	/**
     * ajax更新团购商品
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdateGoods($id, $field, $val)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
		
		if ( (int)$id > 0 ) {
		    if ( $field == 'title' ) {
		        $temp = $this -> getGoods("id <> {$id} and {$field} = '{$val}'");
		        if ( $temp['total'] > 0 )   exit('failure');
		    }
		    
		    if ($this -> _db -> ajaxUpdateGoods((int)$id, $field, $val) <= 0) {
		        exit('failure');
		    }
		}
	}
	
	/**
     * ajax更新团购
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
		
		if ( (int)$id > 0 ) {
		    if ( $field == 'title' ) {
		        $temp = $this -> get("tuan_id <> {$id} and t1.title = '{$val}'");
		        if ( $temp['total'] > 0 )   exit('failure');
		    }
		    
		    if ($this -> _db -> ajaxUpdate((int)$id, $field, $val) <= 0) {
		        exit('failure');
		    }
		}
	}
	
	/**
     * 获取团购状态信息
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
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
    }
	
	/**
     * 获取商品状态信息
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxStatusGoods($url, $id, $status)
	{
		switch($status){
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
    }
    
    /**
     * 删除团购
     *
     * @param    int    $id
     * @return   void
     */
	public function delete($id)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> delete((int)$id);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		    
		    return $result;
		}
	}
    
    /**
     * 删除商品
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteGoods($id)
	{
		if ((int)$id > 0) {
		    
		    $data = $this -> get("t1.goods_id = {$id}", '1');
		    if ( $data['total'] > 0 ) {
		        $this -> error = 'delete_goods_exist_tuan';
				return false;
		    }
		    
		    $result = $this -> _db -> deleteGoods((int)$id);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		    
		    return $result;
		}
	}
    
    /**
     * 获得团购订单的优惠信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getOrderGoods($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
	    if ( is_array($where) ) {
		    $where['title'] && $wheresql .= "t4.title like '%{$where['title']}%' and ";
		    $where['batch_sn'] && $wheresql .= "t1.batch_sn = '{$where['batch_sn']}' and ";
		    $where['addr_consignee'] && $wheresql .= "t2.addr_consignee = '{$where['addr_consignee']}' and ";
		    $where['user_name'] && $wheresql .= "t3.user_name = '{$where['user_name']}' and ";
		    $where['status'] !== null && $wheresql .= "t2.status = '{$where['status']}' and ";

		    if ( $where['fromdate'] ) {
		        $wheresql .= "t2.add_time >= ".strtotime($where['fromdate'])." and ";
		    }
		    if ( $where['todate'] ) {
		        $wheresql .= "t2.add_time <= ".strtotime($where['todate'].' 23:59:59')." and ";
		    }
		    $wheresql .= '1';
		}
		else    $wheresql = $where;
		
		return $this -> _db -> fetchOrderGoods($wheresql, $fields, $orderBy, $page, $pageSize);
	}
    
    /**
     * 获得团购订单正常商品
     *
     * @param    array    $parent_ids
     * @return   void
     */
	public function getOrderCommonGoods($parent_ids)
	{
	    if ( !$parent_ids || count($parent_ids) == 0 )  return false;
	    
	    return $this -> _db -> fetchOrderCommonGoods("order_batch_goods_id in (".implode(',', $parent_ids).")");
	}
    
    
	/**
     * 错误集合
     *
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error' => '操作失败!',
			         'no_goods_title' => '请填写团购商品标题!',
			         'no_goods_id' => '请选择团购商品!',
			         'no_goods_price' => '请填写团购价!',
			         'same_goods_title' => '商品标题不能重复!',
			         'no_start_time' => '请填写开始时间!',
			         'no_end_time' => '请填写结束时间!',
			         'no_tuan_goods' => '请选择团购商品!',
			         'same_title' => '团购标题不能重复!',
			         'delete_goods_exist_tuan' => '已存在该商品的团购，不能删除!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}

}