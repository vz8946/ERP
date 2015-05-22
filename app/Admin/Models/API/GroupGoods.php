<?php

class Admin_Models_API_GroupGoods extends Custom_Model_Dbadv
{
	/**
     * DB对象
     */
	private $_db = null;
	
	/**
     * auth
     */
	private $_auth = null;
	
	/**
     * 上传图片根路径
     */
	private $upPath = 'upload';
	
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
	    parent::__construct();
		$this -> _db = new Admin_Models_DB_GroupGoods();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}

	/**
     * 获取组合商品列表
     *
     * @param    string    $id
     * @return   array
     */
	public function getgroupsgoods($where,$fileds='*',$page=1){
		return $this->_db->fetchgroup($page,$where,$fileds);
	}
	//获取总数
	public function getCount(){
		return $this -> _db -> total;
	}
	
	/**
	 * 添加组合套装
	 * 
	 * @param $data array()
	 * @return lastid int
	 */
	public function add($data) {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
		$data['group_goods_img'] = '';
		$count = count($data['product_id']);
		$data['group_goods_config'] = '';
		
		$arr_product_id = array();
		for($i=0; $i<$count; $i++){
		    $arr_product_id[] = $data['product_id'][$i];
			$data['group_goods_config'][] = array('product_id'=>$data['product_id'][$i], 'product_sn'=>$data['product_sn'][$i], 'number'=>$data['number'][$i], 'product_name'=>$data['product_name'][$i]);
		}

		$list_goods = $this->getAll('shop_goods',array('product_id|in'=>$arr_product_id),'goods_id');
		$arr_goods_id = Custom_Model_Tools::getListFiled('goods_id', $list_goods);
		$data['goods_ids'] = '|'.implode('|', $arr_goods_id).'|';
		
		$data['group_goods_config']   = serialize($data['group_goods_config']);
		$data['suggest_market_price'] = floatval($data['suggest_market_price']);
		$data['price_limit']          = floatval($data['price_limit']);
		$data['status'] = 0;
		$data['add_time'] = time();
		$data['add_name'] = $this -> _auth['admin_name'];
		
	    $group_id = $this -> _db -> insertGroupGoods($data);
	    
	    //生成 更新 sn
	    $sn = 'G8'.substr('0000000'.$group_id,-7);
	    $this -> _db -> newUpdate(array('group_sn'=>$sn), " group_id  = ".$group_id);  

		return $group_id;
	}
	
	/**
	 * 得到商品的图片路径
	 * 
	 * @param int $goods_id
	 * @return str
	 */
	public function getGoodsImg($goods_id) {
		return $this -> _db -> getGoodsImg($goods_id);
	}
	
	/**
	 * 修改
	 * 
	 * @param $post $group_id
	 * @return bool
	 */
	/*public function edit($data,$group_id) {
		if($group_id>0){
			$filterChain = new Zend_Filter();
	        $filterChain -> addFilter(new Zend_Filter_StringTrim());
	        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	        $count = count($data['product_id']);
			$data['group_goods_config'] = '';
			$arr_product_id = array();
			for($i=0; $i<$count; $i++){
				$data['group_goods_config'][] = array('product_id'=>$data['product_id'][$i], 'product_sn'=>$data['product_sn'][$i], 'number'=>$data['number'][$i], 'product_name'=>$data['product_name'][$i]);
				$arr_product_id[] = $data['product_id'][$i];
			}
			
			$list_goods = $this->getAll('shop_goods',array('product_id|in'=>$arr_product_id),'goods_id');
			$arr_goods_id = Custom_Model_Tools::getListFiled('goods_id', $list_goods);
			$data['goods_ids'] = '|'.implode('|', $arr_goods_id).'|';
			
			$data['group_goods_config'] = serialize($data['group_goods_config']);
			$data['group_goods_desc'] = stripslashes($data['group_goods_desc']);
			
	        $st = $this -> _db -> update($data, (int)$group_id);
	        
	        //图片
			if(is_file($_FILES['group_goods_img']['tmp_name'])) {
	    		$this -> upPath .= '/groupgoods/'.$group_id;
	    		$thumbs = '380,380|180,180|60,60';
				$upload = new Custom_Model_Upload('group_goods_img', $this -> upPath);
				$upload -> up(true, $thumbs);
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
				$this -> _db -> updateImg(array('group_goods_img' => $img_url), "group_id=$group_id");
	    	}
			if(is_file($_FILES['alt_img']['tmp_name'])){
	    		$this -> upPath = 'upload/groupgoods/'.$group_id;
				$upload = new Custom_Model_Upload('alt_img', $this -> upPath);
				$upload -> up();
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
				$this -> _db -> updateImg(array('alt_img' => $img_url), "group_id=$group_id");
	    	}

	    	if(is_file($_FILES['bevel_img']['tmp_name'])){
	    		$this -> upPath = 'upload/groupgoods/'.$group_id;
				$upload = new Custom_Model_Upload('bevel_img', $this -> upPath);
				$upload -> up();
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
				$this -> _db -> updateImg(array('bevel_img' => $img_url), "group_id=$group_id");
	    	}


	    	return $st;
		}else{
			return false;
		}
	}*/

	/**
	 * 修改组合商品信息
	 * 
	 * @param    array
	 * @param    int
	 *
	 * @return   bool
	 */
	public function edit($data, $group_id)
	{
		$group_id = intval($group_id);
		if ($group_id < 1) {
			return false;
		}

		$info = $this->_db->getGroupInfoByGroupId($group_id);

		if (empty($info)) {
			$this->_error = '没有相关信息';
			return false;
		}

		$filterChain = new Zend_Filter();
		$filterChain -> addFilter(new Zend_Filter_StringTrim());
		$data = Custom_Model_Filter::filterArray($data, $filterChain);

		$count = count($data['product_id']);
		$data['group_goods_config'] = '';
		$arr_product_id = array();
		for($i=0; $i<$count; $i++){
			$data['group_goods_config'][] = array('product_id'=>$data['product_id'][$i], 'product_sn'=>$data['product_sn'][$i], 'number'=>$data['number'][$i], 'product_name'=>$data['product_name'][$i]);
			$arr_product_id[] = $data['product_id'][$i];
		}
		
		$list_goods = $this->getAll('shop_goods',array('product_id|in'=>$arr_product_id),'goods_id');
		$arr_goods_id = Custom_Model_Tools::getListFiled('goods_id', $list_goods);
		$data['goods_ids'] = '|'.implode('|', $arr_goods_id).'|';
		
		$data['group_goods_config'] = serialize($data['group_goods_config']);
		

		$group_params = array(
			'group_id'             => $data['group_id'],
			'group_goods_name'     => $data['group_goods_name'],
			'suggest_market_price' => $data['suggest_market_price'],
			'group_specification'  => $data['group_specification'],
			'group_goods_alt'      => $data['group_goods_alt'],
			'group_goods_config'   => $data['group_goods_config'],
			'goods_ids'            => $data['goods_ids'],
			'is_shop_sale'         => $data['is_shop_sale'],
			'price_limit'          => $data['price_limit'],
		);
		$st = (bool) $this->_db->updateGroupGoods($group_id, $group_params);

		if (!$st) {
			return false;
		}

		if ($info['group_goods_config'] == $data['group_goods_config']) {
			return $st;
		}

		$log_params = array(
			'group_id'           => $group_id,
			'group_goods_config' => $info['group_goods_config'],
			'last_goods_config'  => $data['group_goods_config'],
			'created_id'         => $this->_auth['admin_id'],
			'created_by'         => $this->_auth['admin_name'],
		);

		$this->_db->insertGroupGoodsLog($log_params);

		return $st;
	}

	/**
	 * 修改组合商品销售信息
	 * 
	 * @param    array
	 * @param    int
	 *
	 * @return   bool
	 */
	public function saleEdit($data, $group_id)
	{
		$group_id = intval($group_id);
		if ($group_id < 1) {
			return false;
		}

		$info = $this->_db->getGroupInfoByGroupId($group_id);

		if (empty($info)) {
			$this->_error = '没有相关信息';
			return false;
		}

		$filterChain = new Zend_Filter();
		$filterChain -> addFilter(new Zend_Filter_StringTrim());
		$data = Custom_Model_Filter::filterArray($data, $filterChain);

		$group_params = array(
			'group_goods_alt'      => $data['roup_goods_alt'],
			'group_market_price'   => floatval($data['group_market_price']),
			'group_price'          => $data['group_price'],
			'type'                 => $data['type'],
			'group_goods_desc'     => stripslashes($data['group_goods_desc']),
			'group_goods_alt'      => $data['group_goods_alt'],
			'group_sale_name'      => $data['group_sale_name'],
		);
		$st = (bool) $this->_db->updateGroupGoods($group_id, $group_params);

		//图片
		if(is_file($_FILES['group_goods_img']['tmp_name'])) {
			$this -> upPath .= '/groupgoods/'.$group_id;
			$thumbs = '380,380|180,180|60,60';
			$upload = new Custom_Model_Upload('group_goods_img', $this -> upPath);
			$upload -> up(true, $thumbs);
			if($upload -> error()){
				$this -> error = $upload -> error();
				return 'imgErr';
			}
			$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
			$this -> _db -> updateImg(array('group_goods_img' => $img_url), "group_id=$group_id");
		}
		if(is_file($_FILES['alt_img']['tmp_name'])){
			$this -> upPath = 'upload/groupgoods/'.$group_id;
			$upload = new Custom_Model_Upload('alt_img', $this -> upPath);
			$upload -> up();
			if($upload -> error()){
				$this -> error = $upload -> error();
				return 'imgErr';
			}
			$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
			$this -> _db -> updateImg(array('alt_img' => $img_url), "group_id=$group_id");
		}

		if(is_file($_FILES['bevel_img']['tmp_name'])){
			$this -> upPath = 'upload/groupgoods/'.$group_id;
			$upload = new Custom_Model_Upload('bevel_img', $this -> upPath);
			$upload -> up();
			if($upload -> error()){
				$this -> error = $upload -> error();
				return 'imgErr';
			}
			$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
			$this -> _db -> updateImg(array('bevel_img' => $img_url), "group_id=$group_id");
		}
		if (!$st) {
			return false;
		}

		return $st;
	}

	/**
     * 获取日志列表
     *
     * @param    array  
     * @param    int
     *
     * @return   array
     */
	 public function getLogList($params, $limit)
	 {	
		$infos = $this->_db->getLogList($params, $limit);

		if (false === $infos) {
			$this->_error = $this->_db->getError();
			return false;
		}

		if (count($infos) < 1) {
			return array();
		}

		foreach ($infos as &$info) {
			$info['group_goods_config'] = unserialize($info['group_goods_config']);
			$info['last_goods_config']  = unserialize($info['last_goods_config']);
		}

		return $infos;
	 }

	/**
	 * 根据组合ID获取组合数据
	 *
	 * @param    int
	 *
	 * @return   array
	 **/
	 public function getGroupInfoByGroupId($group_id)
	 {
		if (false === ($infos = $this->_db->getGroupInfoByGroupId($group_id))) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $infos;
	 }

	 /**
     * 获取日志总数
     *
     * @param    array  
     *
     * @return   int
     */
    public function getLogCount($params)
	{	
		$count = $this->_db->getLogCount($params);

		if (false === $count) {
			$this->_error = $this->_db->getError();
			return false;
		}

		return $count;
	}
	
	/**
     * 获取组合商品信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getGroupgoogs($where = null, $fields = '*')
	{
		if (is_array($where)) {
			$whereSql = "1=1";
		    $where['onsale'] == 'on' && $whereSql .= " and status=1";
		    $where['onsale'] == 'off' && $whereSql .= " and status=0";
		    $where['group_goods_img'] && $whereSql .= " and group_goods_img=''";
		    $where['group_goods_name'] && $whereSql .= " and group_goods_name LIKE '%" . $where['group_goods_name'] . "%'";
			($where['fromdate']) ? $whereSql .= " and add_time >=" . strtotime($where['fromdate']) : "";
			($where['todate']) ? $whereSql .= " and add_time <" . (strtotime($where['todate'])+86400) : "";
		}else{
			$whereSql = $where;
		}
        return $this -> _db -> getGroupgoogs($whereSql, $fields);
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
		    $where['onsale'] == 'on' && $whereSql .= " and status=1";
		    $where['onsale'] == 'off' && $whereSql .= " and status=0";
			$where['check_status'] == 'off' && $whereSql .= " and check_status = 0";
			$where['check_status'] == 'on' && $whereSql .= " and check_status = 1";
		    $where['group_goods_img'] && $whereSql .= " and group_goods_img=''";
		    $where['group_goods_name'] && $whereSql .= " and group_goods_name LIKE '%" . $where['group_goods_name'] . "%'";
		    $where['group_sn'] && $whereSql .= " and group_sn='".$where['group_sn']."'";
			($where['fromdate']) ? $whereSql .= " and add_time >=" . strtotime($where['fromdate']) : "";
			($where['todate']) ? $whereSql .= " and add_time <" . (strtotime($where['todate'])+86400) : "";

			!empty($where['is_shop_sale']) && $whereSql .= " and is_shop_sale=".(int)$where['is_shop_sale'];
			$where['price_limit'] && $whereSql .= " and group_price < price_limit";
		}else{
			$whereSql = $where;
		}
        return $this -> _db -> fetch($whereSql, $fields, $page, $pageSize, $orderBy);
	}

	/**
	 * 更改状态
	 * 
	 * @param $s int
	 * @return bool
	 */
	public function status($s,$id)
	{
		if($id > 0){
			//直接下架
			if($s == 0){
				$r = $this -> _db -> status($s,$id);
				return $r;
			}
			//判读上架时候是否有库存
			$flag = $this -> checkOnsaleStatus(array('group_id'=>$id));
			if($flag){
				$r = $this -> _db -> status($s,$id);
				return $r;
			}else{
				$this -> gengxin(array('status'=>0), "group_id=".$id);
				$off = $this -> getOffsaleGoods(array('group_id'=>$id));
				$html = '';
				if($off)foreach ($off as $v){
					$html .= $v['goods_sn']."  ".$v['goods_name']."\n";
				}
				return $html;
			}
		}else{
			return false;
		}
	}
	

	/**
	 * 套装状态审核
	 * 
	 * @param $s int
	 * @return bool
	 */
	public function checkStatus($s,$id)
	{
		if($id > 0){
			 $this -> gengxin(array('check_status'=>$s), "group_id=".$id);

			 return true;
		}else{
			return false;
		}
	}

	/**
	 * 更改排序
	 * 
	 * @param $s $id int
	 * @return bool
	 */
	public function groupsort($s,$id) {
		if($id > 0){
			$r = $this -> _db -> groupsort($s,$id);
			if($r){ return true;}
			else{ return false;}
		}else{
			return false;
		}
	}
	
	/**
	 * 删除
	 * 
	 * @return bool
	 */
	public function delete($id) {
		if($id > 0){
			$r = $this -> _db -> delete($id);
			if($r){ return true;}
			else{ return false;}
		}else{
			return false;
		}
	}
	
	/**
	 * 检查商品是否下架
	 * 
	 * @param $goods_id int
	 * @return bool
	 */
	public function checkgoodsstatus($goods_id) {
		if($goods_id>0){
			return $this -> _db -> checkgoodsstatus((int)$goods_id);
		}else{
			return false;
		}
	}
	
	/**
	 * 得到config中的商品
	 * 
	 * @param array $search
	 * @return $goods array
	 */
	public function fetchConfigGoods($search=array())
	{
		if($search){
			return $this -> _db -> fetchConfigGoods($search);
		}else{
			return false;
		}
	}
	
	/**
	 * 更新库存
	 * 
	 * @param int $group_id
	 * @param int $stock
	 * 
	 * @return void
	 */
	public function updateStock($group_id,$stock) {
		if($group_id < 0){
			return false;
		}
		if($stock < 1){
			$this -> status(0,$group_id);
		}
		$this -> _db -> updateStock($group_id,$stock);
	}
	
	/**
	 * 更新字段
	 * 
	 * @param array $set
	 * @param int $id
	 */
	public function gengxin($set,$val) {
		$this -> _db -> gengxin($set,$val);
	}
	
	/*Begin::2012.5.15添加，用于更新组合商品的子商品售价*/
	/**
	 * 从shop_order_batch_goods表得到组合商品
	 * 
	 * @param array $search
	 * 
	 * @return array
	 */
	public function getGroupOrderBatchGoods($search, $fields='*', $page=null, $pageSize = null, $orderBy = null) {
		return $this -> _db -> getGroupOrderBatchGoods($search, $fields, $page, $pageSize, $orderBy);
	}
	
	/**
	 * 由product_id得到这个商品的价格
	 * 
	 * @param int $product_id
	 * 
	 * @return int decimal
	 */
	public function getGoodsPriceByProductID($product_id) {
		return $this -> _db -> getGoodsPriceByProductID($product_id);
	}
	
	/**
	 * 更新表order_batch_goods
	 * 
	 * 
	 * @param array $set
	 * @param string $where
	 */
	public function updateOrderBatchGoods($set, $where) {
		$this -> _db -> updateOrderBatchGoods($set, $where);
	}
	/*End::2012.5.15添加，用于更新组合商品的子商品售价*/


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
	 * 得到组合商品评论
	 * 
	 * @param $search array
	 * @param string $fields
	 * @param string $orderby
	 * @param int $page
	 * @param in $pageSize
	 * 
	 * @return array
	 */
	public function getGroupGoodsMsg($search=null, $fields='*', $orderby=null, $page=null, $pageSize=5) {
		$where = ' where 1=1';
		if($search){
			$search['status'] && $where .= ' and status='.$search['status'];
			$search['type'] && $where .= ' and type='.$search['type'];
			$search['group_goods_id'] && $where .= ' and group_goods_id='.$search['group_goods_id'];
			$search['group_goods_msg_id'] && $where .= ' and group_goods_msg_id='.$search['group_goods_msg_id'];
		}
		return $this -> _db -> getGroupGoodsMsg($where, $fields, $orderby, $page, $pageSize);
	}
	
	/**
	 * 删除评论
	 * 
	 * @param int $msg_id
	 */
	public function delGroupGoodsMsg($msg_id) {
		return $this -> _db -> delGroupGoodsMsg($msg_id);
	}
	
	/**
	 * 更新组合商品评论
	 *
	 */
	public function msgCheck($id, $data){
        return $this -> _db -> msgCheck($id, $data);
    }
    
    /**
     * 回复留言
     * 
     * @param int $msg_id
     * @param array $post
     */
    public function msgReply($msg_id, $post) {
    	$filterChain = new Zend_Filter();
		$filterChain -> addFilter(new Zend_Filter_StringTrim());
		$post = Custom_Model_Filter::filterArray($post, $filterChain);
		$data = array(
                'user_name'=>$post['user_name'],
                'content'=>$post['content'],
                'reply'=>$post['reply'],
                'status'=>$post['status'],
                'cnt1'=>$post['cnt1'],
                'cnt2'=>$post['cnt2'],
				'is_hot'=>$post['is_hot'],
                'dietitian'=>$post['dietitian']?$post['dietitian']:0,
                'admin_id'=>$this->_auth['admin_id'],
                'admin'=>$this->_auth['admin_name'],
                'reply_time'=>time()
		);
		$this -> _db -> msgReply($msg_id, $data);
    }
	
	/**
	 * 得到product资料
	 * 
	 * @param array $where
	 * @param string $fields
	 * 
	 * @return array;
	 */
	public function getGoods($search=null, $fields='*') {
		return $this -> _db -> getGoods($search, $fields);
	}
	
	/**
	 * 更新上下架状态
	 * 
	 */
	public function refreshStatus() {
		$datas = $this -> getgroupsgoods(null, 'group_id', null);
		if(is_array($datas) && count($datas)){
			foreach ($datas as $v){
					$subgoods = $this -> fetchConfigGoods(array('group_id'=>$v['group_id']));
					if(is_array($subgoods) && count($subgoods)){
						$flag = $this -> checkOnsaleStatus(array('configs'=>$subgoods));
						$st = $flag?1:0;
						$this -> gengxin(array('status'=>$st), "group_id=".$v['group_id']);
					}else{
						$this -> gengxin(array('status'=>0), "group_id=".$v['group_id']);
					}
				}
			return 'ok';
		}else{
			return '没有组合商品';
		}
	}
	
	/**
	 * 判断组合商品的上下架
	 * 
	 * @param array $dat = array('configs', 'group_id')
	 * 
	 * @return bool
	 */
	public function checkOnsaleStatus($dat = array()) {
		if(is_array($dat) && count($dat)){
			$configs = null;
			if($dat['configs']){
				$configs = $dat['configs'];
			}elseif($dat['group_id']){
				$configs = $this -> fetchConfigGoods(array('group_id'=>$dat['group_id']));
			}else{
				return false;
			}
			if(is_array($configs) && count($configs)){
				$stock = new Admin_Models_API_Stock();
				$onsaleFlag = true;
		        foreach ($configs as $v){
		        	if(isset($v['product_id'])){
		        	    if ($v['p_status'] == 1) {
		        	        return false;
		        	    }
		        		$goodsStock = $stock -> getSaleProductStock($v['product_id']);
		        		if($goodsStock['able_number']<1){
		        			$onsaleFlag = false;
		        			break;
		        		}
		        	}else{
		        		return false;
		        	}
		        }
		        return $onsaleFlag;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 得到下架的子商品
	 * 
	 * @param array $dat = array('configs', 'group_id')
	 * 
	 * return array
	 */
	public function getOffsaleGoods($dat = array()) {
		if(is_array($dat) && count($dat)){
			$configs = null;
			if($dat['configs']){
				$configs = $dat['configs'];
			}elseif($dat['group_id']){
				$configs = $this -> fetchConfigGoods(array('group_id'=>$dat['group_id']));
			}else{
				return false;
			}
			if(is_array($configs) && count($configs)){
				$stock = new Admin_Models_API_Stock();
				$offsaleGoods = array();
		        foreach ($configs as $v){
		        	$goodsStock = $stock -> getSaleProductStock($v['product_id']);
		        	if(isset($v['onsale'])){
			        	if($goodsStock['able_number'] < 1){
			        		$offsaleGoods[] = $v;
			        	}
		        	}
		        }
		        return $offsaleGoods;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * ajax 更新组合商品成本价
	 * 
	 */
	public function refreshCost($group_ids=null) {
		$group_ids = trim($group_ids);
    	if($group_ids != ''){
    		$group_ids = explode(',', $group_ids);
    		if(is_array($group_ids) && count($group_ids)){
    			foreach ($group_ids as $k => $v){
    				$v = (int)$v;
    				$goodsConfig = $this -> fetchConfigGoods(array('group_id'=>$v, 'cost'=>true));
    				//遍历group_goods_config
    				if(is_array($goodsConfig) && count($goodsConfig)){
    					$cost = 0;
    					foreach ($goodsConfig as $kk => $vv){
	    					$cost += $vv['number']*$vv['cost'];
    					}
    					//更新表  shop_group_goods 的库存
	    				$this -> _db -> updateImg(array('group_cost'=>$cost), " group_id=$v ");
    				}
    			}
    			return 'ok';
    		}else{
    			return 'no';
    		}
    	}else{
    		return 'no';
    	}
	}
	
	
	/**
	 *
	 * 切换官网销售状态
	 *
	 * @param    int    $id
	 * @param    int    $status
	 * @return   void
	 *
	 */
	public function toggleIsOfflSale($id)
	{
	    $r = $this->getRow('shop_group_goods',array('group_id'=>$id));
	    $status = $r['is_shop_sale'];
	    $status = $status == 1 ? 0 : 1;
	    $this->update('shop_group_goods', array('is_shop_sale'=>$status),array('group_id'=>$id));
	    return $status;
	}
	 
	 
	/**
     * 更新组合套餐保护价
     *
     * @param    int
	 * @param    decimal
     *
     * @return   boolean
     */
	public function updateGroupgoodsAmountByGroupid($group_id, $price_limit)
	{
		$group_info = $this->_db->getGroupInfoByGroupId($group_id);

		if (false === $group_info) {
			$this->_error = $this->_db->getError();
			return false;
		}

		$group_params = array(
			'price_limit'      => $price_limit,
		);
		if (false  === $this->_db->updateGroupInfoByGroupid($group_id, $group_params)) {
			$this->_error = '操作失败，请重试';
			return false;
		}

		return true;
	}
	
	/**
	 * 根据条件导出相应的组合数据
	 *
	 * @param    array
	 *
	 * @return   boolean
	 **/
	public function exportXlsGroupGoods($params)
	{
		
		$title[] = array('套组商品编码', '套组商品名称', '套组市场价', '套组销售价', '套组子商品信息', '商品名', '商品编码', '数量', '市场价', '本店价');

		$infos = $this->get($params,'*');

		$group_infos = array();
		if (!empty($infos)) {
			$goods_db = new Admin_Models_DB_Goods();
			$i = 0;
			foreach ($infos['data'] as $key => $info) {
				$group_infos[$i] = array(
					'group_sn'           => $info['group_sn'],
					'group_goods_name'   => $info['group_goods_name'],
					'group_market_price' => $info['group_market_price'],
					'group_price'        => $info['group_price'],
					'',
					'',
					'',
					'',
					'',
					'',
				);
				$group_infos[++$i] = array();
				$infos['data'][$key]['group_goods'] = unserialize($info['group_goods_config']);
				foreach ($infos['data'][$key]['group_goods'] as $ke => $product) {
					$goods_info = $goods_db->getGoodsInfoByGoodsSn($product['product_sn']);
					$i ++;
					$group_infos[$i] = array(
						'',
						'',
						'',
						'',
						'',
						'product_name' => $product['product_name'],
						'product_sn'   => $product['product_sn'],
						'number'       => $product['number'],
						'market_price' => sprintf("%.2f",$goods_info['market_price']),
						'price'        =>  sprintf("%.2f",$goods_info['price']),
					);
					$infos['data'][$key]['group_goods'][$ke]['market_price'] =  $goods_info['market_price'];
					$infos['data'][$key]['group_goods'][$ke]['price']        =  $goods_info['price'];
				}
				$i ++;
			}

			$group_infos = array_merge($title, $group_infos);
			
		} else {
			$group_infos = $title;
		}

		$xls = new Custom_Model_GenExcel();
		$xls -> addArray($group_infos);
		$xls -> generateXML('group_goods-'.date('Y-m-d'));

	}
	
	
	/**
	* 返回错误信息
	*
	* @return   string
	*/
	public function getError()
	{
		return $this->_error;	
	}
}
