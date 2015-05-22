<?php
class Admin_Models_DB_Goods
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
	private $_pageSize = null;
	
	/**
     * table name
     * @var    string
     */
	private $_table_goods = 'shop_goods';
	private $_table_group_goods = 'shop_group_goods';
	private $_table_goods_tag = 'shop_goods_tag';
	private $_table_product = 'shop_product';
	private $_table_goods_cat = 'shop_goods_cat';
	private $_table_product_cat = 'shop_product_cat';
	private $_table_goods_img = 'shop_goods_img';
	private $_table_goods_link = 'shop_goods_link';
	private $_table_group_goods_link = 'shop_group_goods_link';
	private $_table_stock_status = 'shop_stock_status';
	private $_table_supplier = 'shop_supplier';
	private $_table_brand = 'shop_brand';
	private $_table_goods_op = 'shop_goods_op';
	private $_table_goods_in_cat = 'shop_goods_extend_cat';
	private $_table_goods_keywords ='shop_goods_keywords';
	private $_table_tj_searchwords ='shop_tj_searchwords';
	private $_table_view_tag ='shop_goods_view_tag';

	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
		$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}
	/**
     * 添加数据
     *
     * @param    array    $row
     * @return   boolean
     */
    public function add($row)
	{
	    $row['goods_add_time'] = time();
	    $row['goods_update_time'] = time();
	    
	    $this -> _db -> insert($this -> _table_goods, $row);
	    return $this -> _db -> lastInsertId();
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
		}
		
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY a.goods_id DESC";
		}
		$table = "`$this->_table_goods` a LEFT JOIN `$this->_table_product` c ON a.product_id=c.product_id LEFT JOIN `$this->_table_product_cat` b ON c.cat_id=b.cat_id LEFT JOIN `$this->_table_goods_cat` d ON a.view_cat_id=d.cat_id LEFT JOIN `$this->_table_brand` p ON c.brand_id=p.brand_id  ";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) as count FROM $table $whereSql");
		return $this -> _db -> fetchAll("SELECT $fields,b.cat_name,d.cat_name as view_cat_name,b.cat_sn,b.cat_path FROM $table $whereSql $orderBy $limit");
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
	public function fetchGoods($where = null, $fields = '*')
	{
		if ($where != null) {
			$whereSql = " WHERE $where";
		}
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY a.goods_id DESC";
		}
		$table = "`$this->_table_goods` a INNER JOIN `$this->_table_goods_cat` b ON a.view_cat_id=b.cat_id INNER JOIN `$this->_table_product` c ON a.product_id=c.product_id INNER JOIN `$this->_table_brand` p ON c.brand_id=p.brand_id  ";
		return $this -> _db -> fetchAll("SELECT $fields,cat_name,cat_sn,cat_path FROM $table $whereSql $orderBy");
	}

	/**
     * 添加关联商品
     *
     * @param    int      $goods_id
     * @param    int      $goods_link_id
     * @return   int      lastInsertId
     */
	public function addLink($goods_id, $goods_link_id,$type)
	{
	
			if($type&&$type==2){
					$row = array (
                      'goods_id' => $goods_id,
                      'groupgoods_link_id' => $goods_link_id,
                      );
			}else{
				$row = array (
                      'goods_id' => $goods_id,
                      'goods_link_id' => $goods_link_id,
                      );
			}
		$table=$type==null?$this -> _table_goods_link:$this -> _table_group_goods_link;
		$this -> _db -> insert($table, $row);
		return $this -> _db -> lastInsertId();
		
		
	}
	
	/**
     * 删除关联商品
     *
     * @param    int      $id
     * @return   void
     */
	public function deleteLink($id = null,$type)
	{
		if ($id >0 ) {
			$where = $this -> _db -> quoteInto('link_id = ?', (int)$id);
			$table=$type==null?$this -> _table_goods_link:$this -> _table_group_goods_link;
			return $this -> _db -> delete($table, $where);
		}
	}

	/**
     * 添加商品实体
     *
     * @param    array    $row
     * @return   int      lastInsertId
     */
	public function addProduct($row)
	{
        $row['p_add_time'] = time();
        $row["goods_id"] = $row['goods_id'];
        $this -> _db -> insert($this -> _table_product, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 获得商品实体
     *
     * @param    array    $row
     * @return   int      lastInsertId
     */
	public function getProduct($where)
	{
        return $this -> _db -> fetchAll("select * from {$this -> _table_product} where {$where}");
	}
	
	/**
     * 修改商品实体goods_id
     *
     * @param    array    $row
     * @return   int      lastInsertId
     */
	public function updateProductGoodsID($productID, $field, $goodsID)
	{
		$this -> _db -> update($this->_table_product, array($field => $goodsID), "product_id = $productID");
	}
	
	/**
     * 获取关联商品
     *
     * @param    int      $goods_id
     * @return   array
     */
	public function getLink($goods_id,$type)
	{
		if($type==null){
			$sql = "SELECT * FROM `$this->_table_goods_link` a INNER JOIN `$this->_table_goods` b ON b.goods_id=a.goods_link_id  WHERE a.goods_id=$goods_id";
		}else{
			$sql = "SELECT link_id,goods_id,groupgoods_link_id as goods_link_id,group_sn as goods_sn,group_goods_name as goods_name,`status`as onsale  FROM `$this->_table_group_goods_link` a INNER JOIN `$this->_table_group_goods` b ON b.group_id=a.groupgoods_link_id  WHERE a.goods_id=$goods_id";
		}
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加商品新标签
     *
     * @param    string    $where
     * @return   array
     */
	public function addTag($data)
	{
			$row = array (
						  'tag' => $data['tag'],
						  'title' => $data['title'],
						  'type'  => $data['type'],
						  'admin_name' => $data['admin_name'],
						  'add_time' => time()
						  );
			$this -> _db -> insert($this->_table_goods_tag, $row);
			$lastInsertId = $this -> _db -> lastInsertId();
			return $lastInsertId;
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getAllTag($where = null,$page=null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) $where = "WHERE 1 $where";
        return array('list'=>$this -> _db -> fetchAll("SELECT * FROM `$this->_table_goods_tag` $where order by tag_id  DESC $limit "),'total'=> $this -> _db -> fetchOne("SELECT count(*) as count FROM `$this->_table_goods_tag` $where "));
	}
	

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where = null)
	{
		if ($where != null) $where = "WHERE $where";
		$sql = "SELECT * FROM `$this->_table_goods_tag` $where order by tag_id  DESC ";
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 获取供货商列表
     *
     * @return   array
     */
	public function getSupplier()
	{
		$sql = "SELECT supplier_id,supplier_name,status FROM `$this->_table_supplier` ORDER BY supplier_id";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 获取品牌列表
     *
     * @return   array
     */
	public function getBrand($where='')
	{
		$sql = "SELECT * FROM `$this->_table_brand` {$where} ORDER BY brand_id";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 获取分类列表
     *
     * @return   array
     */
	public function getCat($where = null, $orderBy = null)
	{
        $where && $whereSql = " AND $where";
        if ($orderBy != null){
            $orderBy = "ORDER BY $orderBy";
        }else{
            $orderBy = "ORDER BY parent_id, cat_sort";
        }
		$sql = "SELECT cat_id,cat_name,parent_id,cat_path,cat_sort FROM `$this->_table_goods_cat`  WHERE cat_status=0 and display=1 $whereSql $orderBy ";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert(array $data)
	{
		$row = array (
		              'product_id' => $data['product_id'],
                      'goods_name' => $data['goods_name'],
                      'goods_sn' => $data['goods_sn'],
			          'view_cat_id' => $data['view_cat_id'],
                      'onsale' => '1',
                      'limit_number' => $data['limit_number'],
                      'market_price' => $data['market_price'],
                      'price' => $data['price'],
                      'staff_price' => $data['staff_price'],
			          'point' => $data['point'],
					  'act_notes' => $data['act_notes'],
                      'is_gift' => $data['is_gift'] ? $data['is_gift'] : 0,
                      'goods_alt' => $data['goods_alt'],
                      'url_alias' => $data['url_alias'],
			          'brief' => $data['brief'],
			          'description' => $data['description'],
					  'spec' => $data['spec'],
                      'region' => $data['region'],
                      'introduction' => $data['introduction'],
			          'meta_title' => $data['meta_title'],
			          'meta_keywords' => $data['meta_keywords'],
			          'meta_description' => $data['meta_description'],
			          'goods_add_time' => time()
                      );
        $this -> _db -> insert($this->_table_goods, $row);
		return $this -> _db -> lastInsertId();
	}
	/**
	 * 添加细节图片
	 *
	 * @param    array    $data
	 * @return   int      lastInsertId
	 */
	public function insertImage(array $data)
	{
		$row = array (
				'product_id' => $data['product_id'],
				'img_url' => $data['img_url'],
				'img_type' => $data['img_type'],
				'add_time' => $data['add_time'],
				'thumbs' => $data['thumbs'],
				'img_desc' => $data['img_desc'],
				'img_sort' => $data['img_sort'],
		);
		$this -> _db -> insert($this->_table_goods_img, $row);
		return $this -> _db -> lastInsertId();
	}
	
	
	/**
     * 更新数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function update($data, $id)
	{
		$set = array (
					  'goods_name' => $data['goods_name'],
                      'limit_number' => $data['limit_number'],
                      'goods_alt' => $data['goods_alt'],
                      'url_alias' => $data['url_alias'],
			          'brief' => $data['brief'],
			          'characters' => $data['characters'],
                      'region' => $data['region'],
					  'act_notes' => $data['act_notes'],
			          'description' => $data['description'],
                      'introduction' => $data['introduction'],
					  'spec' => $data['spec'],
                      'is_gift' => $data['is_gift'] ? $data['is_gift'] : 0,
			          'meta_title' => $data['meta_title'],
			          'meta_keywords' => $data['meta_keywords'],
			          'meta_description' => $data['meta_description'],
			          'goods_update_time' => time()
                      );
                      
        $where = "goods_id = $id";
		if($data['editcat']){
			$set['cat_id'] = $data['cat_id'];
		}
		if($data['editviewcat']){
			$set['view_cat_id'] = $data['view_cat_id'];
			
		}
		if ($id > 0) {
		    $this -> _db -> update($this->_table_goods, $set, $where);
		    return true;
		}
	}
	
	/**
     * 删除数据
     *
     * @param    int      $id
     * @return   void
     */
	public function deleteGoods($id,$value)
	{
		$where = $this -> _db -> quoteInto('goods_id = ?', $id);
        if($value=='1'){
            $set['is_del'] = '1';
        }else{
            $set['is_del'] = '0';
        }

		if ($id > 0 && $this -> _db -> update($this->_table_goods, $set, $where)) {
            return 'ok';
		}
	}

	/**
     * 更新标签
     *
     * @param    int    $tag_id
     * @param    int    $val
     * @return   void
     */
	public function updateTag($tag_id, $val,$type=null)
	{
		if ($tag_id > 0) {
			$set = array ('config' => $val);
			$where = $this -> _db -> quoteInto('tag_id = ?', $tag_id);
		    $this -> _db -> update($this->_table_goods_tag, $set, $where);
		    return true;
		}
	}
	
	/**
     * 更新状态
     *
     * @param    int       $id
     * @param    int       $status
     * @param    string    $remark
     * @return   void
     */
	public function updateStatus($id, $status, $remark = '')
	{
		if ($id > 0) {
			$set = array ('onsale' => $status, 'onoff_remark' => $remark);
			$where = $this -> _db -> quoteInto('goods_id = ?', $id);
		    return $this -> _db -> update($this->_table_goods, $set, $where);
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
		$set = array ($field => $val);
		if ($id > 0) {
		switch($type){
			case 'img':
			    $fields = array('img_sort', 'img_desc');
			    if(in_array($field, $fields)){
				    $where = $this -> _db -> quoteInto('img_id = ?', $id);
				    $this -> _db -> update($this -> _table_goods_img, $set, $where);
			    }
			    break;

			case 'tag':
			    $fields = array('title', 'tag');
			    if(in_array($field, $fields)){
				    $where = $this -> _db -> quoteInto('tag_id = ?', $id);
				    $this -> _db -> update($this -> _table_goods_tag, $set, $where);
			    }
			    break;
			case 'viewtag':
			    $fields = array('title','union_id','union_param','mark','tag');
			    if(in_array($field, $fields)){
				    $where = $this -> _db -> quoteInto('id = ?', $id);
				    $this -> _db -> update($this -> _table_view_tag, $set, $where);
			    }
			    break;
			default:
			    $fields = array('goods_name','goods_sort', 'cost_price', 'market_price', 'price', 'url_alias', 'limit_number');
			    if(in_array($field, $fields)){
				    $where = $this -> _db -> quoteInto('goods_id = ?', $id);
				    $this -> _db -> update($this->_table_goods, $set, $where);
			    }
		}
		    return true;
		}
	}
	
	/**
     * 更新数据
     *
     * @param    array    $set
     * @param    string   $where
     * @return   array
     */
    public function updateGoods($set, $where)
	{
	    $set['goods_update_time'] = time();
	    $this -> _db -> update($this -> _table_goods, $set, $where);
	    return true;
	}
	
	/**
     * 获取操作日志
     *
     * @param    string   $where
     * @return   array
     */
	public function getOp($where = null)
	{
		if ($where != null) $where = "WHERE $where";
		$sql = "SELECT * FROM `$this->_table_goods_op` $where ORDER BY op_id DESC";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 添加操作日志记录
     *
     * @return   int      lastInsertId
     */
	public function insertOp($row)
	{
        $this -> _db -> insert($this -> _table_goods_op, $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	
	/**
	 * 商品扩展分类添加
	 * 
	 * @param int $goods_id
	 * @param int $cat_id
	 */
	public function insertGoodsInCat($goods_id, $cat_id) {
		$row = array (
                      'goods_id' => $goods_id,
                      'cat_id' => $cat_id,
                      );
        $this -> _db -> insert($this -> _table_goods_in_cat, $row);
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
    		$sql = "SELECT * FROM `{$this -> _table_goods_in_cat}` where goods_id = $goods_id";
			return $this -> _db -> fetchAll($sql);
    	}
    }
    
    /**
     * 删除 某商品扩展分类
     * 
     * @param int $goods_id
     */
    public function delGoodsInCat($goods_id) {
    	if($goods_id > 0){
    		$condition = "goods_id = $goods_id ";
			return $this -> _db -> delete($this -> _table_goods_in_cat, $condition);
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
    		$sql = "select a.*,b.goods_name from {$this -> _table_goods_keywords} a  join {$this -> _table_goods} b on a.goods_id=b.goods_id where a.goods_id = {$goods_id} limit 1";
    		return $this -> _db -> fetchRow($sql);
    	}else{
    		return array();
    	}
    }
    
    /**
     * 添加关键字
     * 
     * @param arr
     */
    public function addkeywords($data) {
    	$row = array (
                  'goods_id' => $data['goods_id'],
				  'keywords' => $data['keywords'],
				  );
		return $this -> _db -> insert($this -> _table_goods_keywords, $row);
    }
    
    /**
     * 修改关键字
     * 
     * @param array
     */
    public function editkeywords($data) {
    	if($data['keywords'] && $data['goods_id']){
    		$set = array ('keywords' => $data['keywords']);
	    	$where = $this -> _db -> quoteInto('goods_id = ?', (int)$data['goods_id']);
	    	return $this -> _db -> update($this -> _table_goods_keywords, $set, $where);
    	}
    }
    
	/**
     * 由goods_id得到一个商品
     * 
     * @param int $goods_id
     * @return array
     */
    public function getOne($goods_id, $fields='*') {
    	if(intval($goods_id)){
    		return $this -> _db-> fetchRow("select {$fields} from {$this -> _table_goods} where goods_id = {$goods_id} limit 1");
    	}
    }
    
    /**
     * 第一次使用一下，以后不再用
     * 
     */
	public function genkeywords() {
    	$rs=$this->_db->fetchAll("SELECT goods_id,goods_sn from {$this->_table_goods} where onsale=0");
    	if(count($rs)){
    		foreach ($rs as $v){
    			$row = array (
                  'goods_id' => $v['goods_id'],
				  'keywords' => $v['goods_sn']
				);
    			$this->_db->insert($this -> _table_goods_keywords,$row);
    		}
    		echo 'ok';
    	}
    }

	/**
     * 更新商品关联文章
     *
     * @param    int    $tag_id
     * @param    int    $val
     * @return   void
     */
	public function updatelinkArticle($goods_id, $val)
	{
		if ($goods_id > 0) {
			$set = array ('article_ids' => $val);
			$where = $this -> _db -> quoteInto('goods_id = ?', $goods_id);
		    $this -> _db -> update($this->_table_goods, $set, $where);
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
	public function getCustomerSearch($where=null, $page=null, $pageSize=null, $orderBy=null) {
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : 50;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		if($where!=null){
			$wheresql = " where $where ";
		}else{
			$wheresql = null;
		}
		if($orderBy=='desc' || $orderBy=='asc'){
			$orderBy = " order by searchcount $orderBy ";
		}else{
			$orderBy = ' order by id desc ';
		}
		$sqlcount = "select count(id) from {$this->_table_tj_searchwords} $wheresql";
		$rs['tot'] = $this -> _db -> fetchOne($sqlcount);
		$sql = "select * from {$this->_table_tj_searchwords} $wheresql $orderBy $limit";
		$rs['datas'] = $this -> _db -> fetchAll($sql);
		return $rs;
	}
	/**
	 * 删除用户搜索统计列表一条记录
	 * 
	 * @param int $id
	 **/
	public function delOneCustomerSearchword($id) {
		$id = (int)$id;
		if($id>0){
			$where = $this -> _db -> quoteInto('id = ?', (int)$id);
			$this -> _db -> delete($this->_table_tj_searchwords, $where);
		}
	}
	/**
	 * 更新用户搜索统
	 * 
	 * @param array $arr
	 * @param string $where
	 */
	public function updateCustomerSearchword($arr,$where) {
		$this -> _db -> update($this->_table_tj_searchwords, $arr, $where);
	}
	/**
	 * 更新图片细节图片字段
	 * @param int $goods_id
	 * @param int $product_id
	 */
	public function updateImgs($product_id,$goods_id) {
		//echo "<script>alert($ids);</script>";die;
		
		$a = "select img_id from shop_goods_img where product_id = ".$product_id." and img_type = 2 ";
		
		$ids = $this -> _db -> fetchAll($a);
		$array = array();
		foreach($ids as $k=>$v){
			$array[] = $v['img_id'];
		}
		$ids = implode(",",$array);
		//echo "<script>alert('$ids');</script>";die;
		//return $ids;
		$arr = array(
				'goods_img_ids'=>$ids
				);
		//$where = array(" goods_id = $goods_id ");
		$where = "goods_id=$goods_id";
		
		//$b = 'update shop_goods set goods_img_ids = "'.$ids.'" where goods_id = '.$goods_id;
		//$this -> _db ->($b);
		//echo "<script>alert('".$b."');</script>";die;
		$this -> _db -> update('shop_goods', $arr, $where);
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getAllViewTag($where = null,$page=null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) $where = "WHERE 1 $where";
        return array('list'=>$this -> _db -> fetchAll("SELECT * FROM `$this->_table_view_tag` $where order by id  DESC $limit "),'total'=> $this -> _db -> fetchOne("SELECT count(*) as count FROM `$this->_table_view_tag` $where "));
	}

	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertViewTag(array $data)
	{
		$row = array (
						'title' => $data['title'],
						'union_id' => $data['union_id'],
						'union_param' => $data['union_param'],
						'mark' => $data['mark'],
						'tag' => $data['tag'],
						'type'=>$data['type'],
						'tips' => stripslashes($data['tips']),
						'goods_ids' => $data['goods_ids']
                      );
        
        $this -> _db -> insert($this->_table_view_tag, $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}
	/**
     * 更新数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function updateViewTag($data, $id)
	{
		$set = array (
						'title' => $data['title'],
						'union_id' => $data['union_id'],
						'union_param' => $data['union_param'],
						'mark' => $data['mark'],
						'tag' => $data['tag'],
					    'tips' => stripslashes($data['tips']),
						'goods_ids' => $data['goods_ids']
                      );
        $where = $this -> _db -> quoteInto('id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this->_table_view_tag, $set, $where);
		    return true;
		}
	}


	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getViewTag($where = null)
	{
		if ($where != null) $where = "WHERE $where";
		$sql = "SELECT * FROM `$this->_table_view_tag` $where order by id  DESC ";
		return $this -> _db -> fetchAll($sql);
	}


	/**
	 * 删除一条记录
	 * 
	 * @param int $id
	 **/
	public function delViewTag($id) {
		$id = (int)$id;
		if($id>0){
			$where = $this -> _db -> quoteInto('id = ?', (int)$id);
			return $this -> _db -> delete($this->_table_view_tag, $where);
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
	public function getGoodsForBaidu($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = " WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY goods_id DESC";
		}
		
		$datas['tot'] = $this -> _db -> fetchOne("SELECT count(a.goods_id) as count FROM {$this->_table_goods} a $whereSql");
		$datas['datas'] = $this -> _db -> fetchAll("SELECT $fields,b.brand_name FROM {$this->_table_goods} a left join {$this->_table_product} p on a.product_id = p.product_id left join {$this->_table_brand} b on p.brand_id=b.brand_id $whereSql $orderBy $limit");
		
		/*取出上级分类*/
		foreach ($datas['datas'] as $k=>$v) {
			if($v['view_cat_id']){
				$sqlCat = "select cat_path from {$this->_table_goods_cat} where cat_id=".$v['view_cat_id'];
				$rsa = $this -> _db -> fetchOne($sqlCat);
				if($rsa){
					$tmp = explode(',', $rsa);
					foreach ($tmp as $vv){
						if($vv){$tmpp[] = $vv;}
					}
					$tmp = @implode(',', $tmpp);
					if($tmp){
						$sqlCatName = "select cat_name from {$this->_table_goods_cat} where cat_id in ($tmp)";
						$catRs = $this -> _db -> fetchAll($sqlCatName);
						unset($tmpp);
					}
				}
				foreach ($catRs as $vvv){
					$tmppp[] = $vvv['cat_name'];
				}
				$datas['datas'][$k]['tags'] .= implode("\\", $tmppp);
				unset($tmppp);
			}
		}
		
		return $datas;
	}

	/**
     * 统计产品限价
     * 
     *
     * @return   int
     */
	public function getProductLimitPriceTotal()
	{
		$sql = "SELECT COUNT(*) AS count FROM `{$this->_table_goods}` g left join `shop_product` p ON g.product_id = p.product_id WHERE g.price < p.price_limit";

		return $this->_db->fetchOne($sql);
	}

	/**
     * 根据商品编码获取商品数据
     * 
     *
     * @return   array
     */
	public function getGoodsInfoByGoodsSn($goods_sn)
	{
		$goods_sn = trim($goods_sn);
		if (empty($goods_sn)) {
			$this->_error = '产品编码为空';
			return false;
		}

		$sql = "SELECT `goods_id`, `goods_sn`, `market_price`, `price` FROM `shop_goods` WHERE `goods_sn` = '{$goods_sn}' limit 1";

		return $this->_db->fetchRow($sql);
	}
}