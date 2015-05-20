<?php

class Admin_Models_DB_GroupGoods
{
	/**
     * Zend_Db
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * page size
     * @var    int
     */
	private $_pageSize = 25;
	
	/**
     * table name
     * @var    string
     */
    private $_table = 'shop_group_goods';
	private $_table_goods = 'shop_goods';
	private $_table_group_goods = 'shop_group_goods';
	private $_table_product = 'shop_product';
    private $_table_order_batch_goods = 'shop_order_batch_goods';
    private $_table_group_goods_msg = 'shop_group_goods_msg';
    
	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	//获取组合商品
	public function fetchgroup($page,$where,$fileds,$pageSize=20){
		if ($page != null) {
			$offset = ($page-1)*$pageSize;
			$limit = " limit  $offset, $pageSize";
		}
		if ($where != null) {
			$whereSql = " where $where";
		}
		$sqlcount="select count(*) as count from ".$this->_table_group_goods ." $whereSql ";
		$sqlfetch="select $fileds from ".$this->_table_group_goods." $whereSql $limit";
		$this -> total = $this -> _db -> fetchOne($sqlcount);
		
		return $this->_db->fetchAll($sqlfetch);
	}

	/**
	 * 添加
	 * 
	 * @param $data array()
	 * @return $insertid int
	 */
	public function insert($data) {
		$row = array(
			'group_goods_name' => $data['group_goods_name'],
			'type' => $data['type'],
            'group_specification' => $data['group_specification'],
            'group_goods_alt' => $data['group_goods_alt'],
			'group_goods_desc' => $data['group_goods_desc'],
			'group_goods_img' => $data['group_goods_img'],
			'group_price' => $data['group_price'],
			'group_market_price' => $data['group_market_price'],
			'group_goods_config' => $data['group_goods_config'],
			'status' => $data['status'],
			'add_time' => $data['add_time'],
			'add_name' => $data['add_name'],
			'group_sort' => 0,
            'is_shop_sale' => 0,
			'goods_ids' => $data['goods_ids']
		);
		
		$this -> _db -> insert($this -> _table, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 添加组合商品信息
	 * 
	 * @param    array()
	 *
	 * @return   int
	 */
	public function insertGroupGoods($data) {
		$row = array(
			'group_goods_name'     => $data['group_goods_name'],
            'group_specification'  => $data['group_specification'],
            'group_goods_alt'      => $data['group_goods_alt'],
			'group_goods_config'   => $data['group_goods_config'],
			'status'               => $data['status'],
			'add_time'             => $data['add_time'],
			'add_name'             => $data['add_name'],
			'group_sort'           => 0,
			'suggest_market_price' => $data['suggest_market_price'],
			'goods_ids'            => $data['goods_ids'],
			'price_limit'          => $data['price_limit'],
		);

		!empty($data['is_shop_sale']) && $row['is_shop_sale'] = $data['is_shop_sale'];
		
		$this -> _db -> insert($this -> _table, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 修改
	 * 
	 * @param array $data
	 * @param int $group_id
	 */
	public function update($data,$group_id) {
		$set = array(
			'group_goods_name' => $data['group_goods_name'],
			'type' => $data['type'],
            'group_specification' => $data['group_specification'],
            'group_goods_alt' => $data['group_goods_alt'],
			'group_goods_desc' => $data['group_goods_desc'],
			'group_price' => $data['group_price'],
			'group_market_price' => $data['group_market_price'],
			'group_goods_config' => $data['group_goods_config'],
			'status' => 0 ,
            'is_shop_sale' => 0,
			'goods_ids' => $data['goods_ids']
		);
		$where = "group_id = $group_id";
		if ($group_id > 0) {
		    $this -> _db -> update($this->_table, $set, $where);
		    return true;
		}
	}

	/**
	 * 更新组合商品数据
	 * 
	 * @param    int
	 * @param    array
	 *
	 * @return   boolean
	 */
	public function updateGroupGoods($group_id, $params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		$group_id = intval($group_id);

		if ($group_id < 1) {
			$this->_error = '组合ID不正确';
			return false;
		}

		$this->_db->update($this->_table, $params, "group_id = '{$group_id}'");
		return true;
	}
	
	/**
	 * 更改图片
	 * 
	 * @param $data array
	 * @param $group_id int
	 */
	public function updateImg($set,$where) {
	    $this -> _db -> update($this -> _table, $set, $where);
	    return true;
	}
	
	/**
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getGroupgoogs($where = null, $fields = '*')
	{
		if ($where != null) {
			$whereSql = " WHERE $where";
		}
		$orderBy = " ORDER BY group_id DESC";
       return $this -> _db -> fetchRow("SELECT $fields FROM ".$this->_table." $whereSql $orderBy ");
	}

	/**
     * 获取数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetch($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = " WHERE $where";
		}else{
            $whereSql = " WHERE 1 ";
        }
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY group_id DESC";
		}
       return array('total'=> $this -> _db -> fetchOne("SELECT count(*) as count FROM ".$this->_table." $whereSql") ,'data'=> $this -> _db -> fetchAll("SELECT $fields FROM ".$this->_table." $whereSql $orderBy $limit"));
	}
	
	/**
	 * 更改状态
	 * 
	 * @param $s $id int
	 * @return bool
	 */
	public function status($s, $id)
	{
		$set = array ('status' => $s);
        $where = "group_id = $id";
		if ($id > 0) {
		    $this -> _db -> update($this->_table, $set, $where);
		    return true;
		}
	}
	
	/**
	 * 更改排序
	 * 
	 * @param $s $id int
	 * @return bool
	 */
	public function groupsort($s,$id)
	{
		$set = array ('group_sort' => $s);
        $where = "group_id = $id";
		if ($id > 0) {
		    $this -> _db -> update($this->_table, $set, $where);
		    return true;
		}
	}
	
	/**
	 * 删除
	 * 
	 * @param $id int
	 * @return bool
	 */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('group_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> delete($this->_table, $where);
		    return true;
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
			$row = $this -> _db -> fetchRow("SELECT onsale FROM ".$this -> _table_goods." where goods_id=".$goods_id);
			if($row){
				if($row['onsale'] == 0){return true;}
				else{return false;}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 得到商品的图片路径
	 * 
	 * @param int $goods_id
	 * @return str
	 */
	public function getGoodsImg($goods_id) {
		$img = $this -> _db -> fetchRow("select goods_img from ".$this -> _table_goods." where goods_id=".$goods_id);
		if($img){
			return $img;
		}else{
			return '';
		}
	}
	
	/**
	 * 取得config的商品
	 * 
	 * @param array $search
	 * @return array
	 */
	public function fetchConfigGoods($search) {
		$where = 'where 1=1';
		if ($search && is_array($search)) {
		    if ($search['group_goods_config']) {
			    $configs = unserialize($search['group_goods_config']);
			}
			else {
			    $search['group_id'] && $where .= ' and group_id='.$search['group_id'];
			    $search['group_sn'] && $where .= " and group_sn= '{$search['group_sn']}'";
				$search['check_status'] && $where .= " and check_status= '{$search['check_status']}'";
			    $temp = $this -> _db -> fetchRow("select group_goods_config from $this->_table $where limit 1");
			    if (!$temp) return false;
			    
			    $configs = unserialize($temp['group_goods_config']);
			}
		    $goods = array();
            $sql = "SELECT product_id,product_name as goods_name,product_sn,product_sn as goods_sn,goods_style,suggest_price as price,product_img as goods_img,cat_id,p_status, cost FROM `$this->_table_product` where product_id=";
		    if(is_array($configs) && count($configs)){
			    foreach ($configs as $k=>$v) {
			        $tmp = $this -> _db -> fetchRow($sql.$v['product_id']);
			        if(!$tmp){continue;}
				    $tmp['number'] = $v['number'];
				    $goods[] = $tmp; 
				}
		        return $goods;
		    }else{
		    	return false;
		    }
		}
		else {
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
		$set = array('group_stock_number' => $stock);
		$where = "group_id = $group_id";
		$this -> _db -> update($this->_table, $set, $where);
		return true;
	}
	
	/**
	 * 更新字段
	 * 
	 * @param array $set
	 * @param int $id
	 */
	public function gengxin($set,$id) {
		$this -> _db -> update($this->_table, $set, $id);
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
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		$whereSql = " WHERE 1 ";
		if($search){
			$search['type'] && $whereSql .= " and type=".$search['type'];
			if(isset($search['product_id']) && $search['product_id'] >= 0){$whereSql .= " and product_id=".$search['product_id'];}
			$search['parent_id'] && $whereSql .= " and parent_id=".$search['parent_id'];
		}
		
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY order_batch_goods_id DESC";
		}
		
		$sqltot = "select count(order_batch_goods_id) from {$this->_table_order_batch_goods} $whereSql";
		$datas['tot'] = $this -> _db -> fetchOne($sqltot);
		$sql = "select $fields from {$this->_table_order_batch_goods} $whereSql $orderBy $limit";
		$datas['datas'] = $this -> _db -> fetchAll($sql);
		return $datas;
	}
	
	/**
	 * 由product_id得到这个商品的价格
	 * 
	 * @param int $product_id
	 * 
	 * @return int decimal
	 */
	public function getGoodsPriceByProductID($product_id) {
		$product_id = (int)$product_id;
		if($product_id < 1){return false;}
		$sql = "select b.price from {$this->_table_product} a left join {$this->_table_goods} b on a.goods_id=b.goods_id where a.product_id=".$product_id." limit 1";
		$price = $this -> _db -> fetchOne($sql);
		return $price;
	}
	
	
	/**
	 * 更新表order_batch_goods
	 * 
	 * 
	 * @param array $set
	 * @param string $where
	 */
	public function updateOrderBatchGoods($set, $where) {
		$this -> _db -> update($this->_table_order_batch_goods, $set, $where);
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
		$set = array ($field => $val);
		if ($id > 0) {
			    $fields = array('group_sn', 'group_goods_name');
			    if(in_array($field, $fields)){
				    $where = $this -> _db -> quoteInto('group_id = ?', $id);
				    $this -> _db -> update($this -> _table, $set, $where);
			    }
		    return true;
		}
	}

	/**
	 * 新更新
	 * 
	 */
	public function newUpdate($set, $where) {
		$this -> _db -> update($this->_table, $set, $where);
	}
	
	/**
	 * 得到组合商品评论
	 * 
	 * @param $where string
	 * @param string $fields
	 * @param string $orderby
	 * @param int $page
	 * @param in $pageSize
	 * 
	 * @return array
	 */
	public function getGroupGoodsMsg($where=null, $fields='*', $orderby=null, $page=null, $pageSize=5) {
		if($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		$orderby = ($orderby)?(' order by '.$orderby):(' order by group_goods_msg_id desc');
		$rs['tot'] = $this -> _db -> fetchOne("select count(group_goods_msg_id) from {$this->_table_group_goods_msg} $where");
		$rs['datas'] = $this -> _db -> fetchAll("select $fields from {$this->_table_group_goods_msg} $where $orderby $limit");
		return $rs;
	}
	
	/**
	 * 删除评论
	 * 
	 * @param int $msg_id
	 */
	public function delGroupGoodsMsg($msg_id) {
		$where = $this -> _db -> quoteInto('group_goods_msg_id = ?', $msg_id);
		if ($msg_id > 0) {
		    return $this -> _db -> delete($this->_table_group_goods_msg, $where);
		}
	}
	
	/**
	 * 更新组合商品评论
	 * 
	 */
	public function msgCheck($id, $data){
        $where = $this -> _db -> quoteInto('group_goods_msg_id = ?', $id);
		if($id > 0) return $this->_db->update($this->_table_group_goods_msg, $data, $where);
    }
    
    /**
     * 回复留言
     * 
     * @param int $msg_id
     * @param array $post
     */
    public function msgReply($msg_id, $post) {
    	$where = $this -> _db -> quoteInto('group_goods_msg_id = ?', $msg_id);
		if($msg_id > 0) return $this->_db->update($this->_table_group_goods_msg, $post, $where);
    }
	
	/**
	 * 得到product资料
	 * 
	 * @param array $where
	 * @param string $fields
	 * 
	 * @return array;
	 */
	public function getGoods($search=null,$fields='*') {
		$where  = 'where 1=1 ';
		if($search){
			$search['goods_id'] && $where.=' and t1.goods_id='.$search['goods_id'];
			$search['goods_sn'] && $where.=' and t1.goods_sn='.$search['goods_sn'];
		}
	    $sql = "SELECT $fields FROM `$this->_table_goods` as t1 left join `$this->_table_product` as t2 on t1.product_id = t2.product_id {$where} ";
	    return $this -> _db -> fetchAll($sql);
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
		$group_id = intval($group_id);
		if ($group_id < 1) {
			$this->_error = '组合ID不正确';
			return false;
		}

		$sql = "SELECT `group_id`, `group_sn`, `group_goods_name`, `group_price`, `price_limit`, `group_goods_config` FROM `shop_group_goods` WHERE `group_id` = '{$group_id}' LIMIT 1";

		return $this->_db->fetchRow($sql);
 
	}

	/**
     * 根据组合Id更新组合商品信息
     *
     * @param    params  
     *
     * @return   boolean
     */
	public function updateGroupInfoByGroupid($group_id, $params)
	{
		$group_id = intval($group_id);
		if ($group_id < 1) {
			$this->_error = '组合ID不正确';
			return false;
		}

		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		return (bool) $this->_db->update($this->_table_group_goods, $params, "group_id = '{$group_id}'");
	}

	/**
     * 根据产品IDS获取产品信息
     *
     * @param    array  
     *
     * @return   array
     */
	public function getGroupInfosByGroupIds($group_ids)
	{
		if (empty($group_ids)) {
			$this->_error = '组合IDS为空';
			return false;
		}

		$sql = "SELECT `group_id`, `group_sn`, `group_goods_name` FROM `{$this->_table_group_goods}` WHERE `group_id` IN('". implode("','", $group_ids) ."') ";
		$infos = $this->_db->fetchAll($sql);
		if (count($infos) < 1) {
			return array();
		}

		$group_infos = array();
		foreach ($infos as $info) {
			$group_infos[$info['group_id']] = $info;
		}

		return $group_infos;
	}

	/**
     * 根据产品SN获取产品信息
     *
     * @param    array  
     *
     * @return   array
     */
	public function getGroupInfoByGroupSn($group_sn)
	{
		$group_sn = trim($group_sn);
		if (empty($group_sn)) {
			$this->_error = '组合sn为空';
			return false;
		}

		$sql = "SELECT `group_id`, `group_sn`, `group_goods_name`, `price_limit` FROM `{$this->_table_group_goods}` WHERE `group_sn` = '{$group_sn}' limit 1";

		return  $this->_db->fetchRow($sql);
	}

	/**
     * 统计组合产品限价
     * 
     *
     * @return   int
     */
	public function getGroupGoodsLimitPriceTotal()
	{
		$sql = "SELECT COUNT(*) AS count FROM `{$this->_table_group_goods}` WHERE group_price < price_limit";

		return $this->_db->fetchOne($sql);
	}

	/**
     * 插入组合商品日志
     * 
	 * @param    array
     *
     * @return   boolean
     */
	public function insertGroupGoodsLog($params)
	{
		return (bool) $this->_db->insert('shop_group_goods_log', $params);
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
		$_condition = $this->getLogCondition($params);

		$field = array(
			'log_id',
			'group_goods_config',
			'last_goods_config',
			'created_by',
			'created_ts',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_group_goods_log` WHERE ". implode(' AND ', $_condition) ." ORDER BY log_id desc limit {$limit}";

		return $this->_db->fetchAll($sql);
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
		$_condition = $this->getLogCondition($params);

		$sql = "SELECT count(*) as count FROM `shop_group_goods_log` WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
	 public function getLogCondition($params)
	 {
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
		
		!empty($params['group_id'])     && $_condition[] = "group_id = '{$params['group_id']}'";

		return $_condition;
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