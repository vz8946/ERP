<?php

class Admin_Models_API_Brand extends Custom_Model_Dbadv
{
    /**
     * 
     * @var Admin_Models_DB_Brand
     */
	private $_db = null;

    /**
     * 错误信息
     */
	private $error;


    /**
     * 上传路径
     */
	private $upPath = 'upload/brand';

	private $tbl = 'shop_brand';


	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
	    parent::__construct();
		$this -> _db = new Admin_Models_DB_Brand();
	}


	/**
     * 根据品牌Id取商品数量
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getGoodsBrandNum($brand_id = null)
	{
        return $this -> _db -> getGoodsBrandNum($brand_id);
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
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
    	    $wheresql = '1<>2';
		    if ($where['brand_name']) {
		        $wheresql .= " and brand_name like '%{$where['brand_name']}%'";
		    }
		    if ($where['as_name']) {
		        $wheresql .= " and as_name like '%{$where['as_name']}%'";
		    }
		    if ($where['bluk']) {
		        $bluk = $where['bluk'] == 'Y' ? 1 : 0; 
		        $wheresql .= " and bluk=".$bluk;
		    }
		    if ($where['ispinpaicheng']) {
		        $ispinpaicheng = $where['ispinpaicheng'] == 'Y' ? 1 : 0;
		        $wheresql .= " and ispinpaicheng=".$ispinpaicheng;
		    }
		}
		else {
		    $wheresql = $where;
		}
		$datas = $this -> _db -> fetch($wheresql, $fields, $orderBy, $page, $pageSize);
		foreach ($datas as $num => $data)
        {
			$datas[$num]['goods_id'] = $data['brand_id'];
			$datas[$num]['goods_sn'] = $data['small_logo'];
			$datas[$num]['goods_name'] = $data['brand_name'];
			$datas[$num]['goods_status'] = $data['as_name'];
			$datas[$num]['ginfo'] = Zend_Json::encode($datas[$num]);
        }
        return $datas;

	}

	/**
     * 添加或修改数据
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

		//品牌图
		if(is_file($_FILES['big_logo']['tmp_name'])) {
			$upload = new Custom_Model_Upload('big_logo', $this -> upPath);
			$upload -> up();
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			$data['big_logo'] = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
		}
		
		if(is_file($_FILES['small_logo']['tmp_name'])) {
			$upload = new Custom_Model_Upload('small_logo', $this -> upPath);
			$upload -> up();
			if($upload -> error()){
				$this -> error = $upload -> error();
				return false;
			}
			$data['small_logo'] = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
		}

		if ( $data['brand_name'] == '') {
			$this -> error = 'no_name';
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
			if(!$result){
				$this -> error = 'error';
				return false;
		    }
		}
		return $result;
	}
	
	public function updateGoodsAsname($id){
	    
	    $r = $this->getRow('shop_brand',array('brand_id'=>$id));
	    
	    $as_name = $r['as_name'];
	    
	    $arr_product_id = array();
	    $list_product = parent::getAll('shop_product',array('brand_id'=>$id),'product_id');
	    foreach ($list_product as $k=>$v){
	        $arr_product_id[] = $v['product_id'];
	    }
	    
	    return $this->update('shop_goods', array('as_name'=>$as_name),array('product_id|in'=>$arr_product_id));
	}

	/**
     * 删除数据
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
     * 更改状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeStatus($id, $status)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateStatus((int)$id, $status) <= 0) {
				exit('failure');
			}
		}
	}

	/**
     * 
     * 切换品牌馆状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     * 
     */
	public function toggleBluk($id)
	{
	    $r = $this->getRow('shop_brand',array('brand_id'=>$id));
	    $status = $r['bluk'];
	    $status = $status == 1 ? 0 : 1;
	    $this->update('shop_brand', array('bluk'=>$status),array('brand_id'=>$id));
	    return $status;
	}

	/**
     * 
     * 切换品牌城状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     * 
     */
	public function toggleIspinpaicheng($id)
	{
	    $r = $this->getRow('shop_brand',array('brand_id'=>$id));
	    $status = $r['ispinpaicheng'];
	    $status = $status == 1 ? 0 : 1;
	    $this->update('shop_brand', array('ispinpaicheng'=>$status),array('brand_id'=>$id));
	    return $status;
	}

	/**
     * ajax更新数据
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

		if ((int)$id > 0) {
		    if ($this -> _db -> ajaxUpdate((int)$id, $field, $val) <= 0) {
		        exit('failure');
		    }
		}
	}

	/**
     * 错误集合
     *
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error'=>'操作失败!',
			         'exists'=>'该工厂已存在!',
			         'not_exists'=>'该工厂不存在!',
			         'forbidden'=>'禁止操作!',
			         'no_name'=>'请填写工厂名称!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	/**
	 * 获取checkTrue
	 */
	 public function getCheckTree(){
		return $this -> _db ->getCheckTree();
	 }

	 /**
	  * 根据品牌ID获取推荐产品
	  */
	 public function getGoodsByBrandTag($brandId){
	 	return $this -> _db -> getGoodsByBrandTag($brandId);
	 }
	 /**
     * 修改品牌推荐商品
     */
	 public function updateBrandTag($data, $brandId){
	 	if($brandId > 0){
			if (is_array($data['goods_id']) && count($data['goods_id']) > 0){
				$val = implode(',', $data['goods_id']);
			}else{
				$val = '';
			}
			$this -> _db -> updateBrandTag($val,$brandId);
			return true;
		}
	 }
	 
	 public function getAll(){
	     $objComm = new Admin_Models_DB_Comn();
	     return $objComm->getAll('shop_brand',array('status'=>0));
	 }
	 
	 //根据ids 取得数据
	 public function getByIds($ids){
	     return parent::getAll($this->tbl,array('brand_id|in'=>$ids));
	 }
	 
	 
}