<?php

class Shop_Models_DB_Goods extends Shop_Models_DB_Comn
{
	/**
     * page size
     * @var    int
     */
	private $_pageSize = null;

	/**
     * table name
     * @var    string
     */
	public $_table_goods = 'shop_goods';
	private $_table_goods_tag = 'shop_goods_tag';
	private $_table_product = 'shop_product';
	private $_table_cat_attr = 'shop_cat_attr';
	public  $_table_goods_cat = 'shop_goods_cat';
	public  $_table_product_cat = 'shop_product_cat';
	private $_table_goods_img = 'shop_goods_img';
	private $_table_goods_link = 'shop_goods_link';
	private $_table_group_goods_link = 'shop_group_goods_link';
	private $_table_stock_status = 'shop_stock_status';
	private $_table_renew = 'shop_renew';
	private $_table_renew_detail = 'shop_renew_detail';
	private $_table_goods_op = 'shop_goods_op';
	private $_table_group_goods = 'shop_group_goods';
	private $_table_member_favorite = 'shop_member_favorite';
    private $_table_brand = 'shop_brand';
    private $_table_goods_extend_cat = 'shop_goods_extend_cat';
	public $_table_goods_keywords = 'shop_goods_keywords';
	private $_table_shop_group_goods = 'shop_group_goods';
	private $_table_shop_tj_searchwords = 'shop_tj_searchwords';//客户搜索词汇统计
    private $_table_view_tag = 'shop_goods_view_tag';
    private $_table_goods_relation = 'shop_goods_relation';
    private $_table_goods_notice = 'shop_goods_notice';

	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
	    parent::__construct();
		$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}

	/**
     * 获取商品基本信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getGoodsInfo($where = null, $fields = '*'){

		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where) && count($where) ) {
                foreach ($where as $key => $value)
                {
                    $whereSql .= " AND $key='$value'";
                }
			}
		}
		$sql = "SELECT $fields FROM `$this->_table_goods` {$whereSql} ";
		return $this -> _db -> fetchRow($sql);
    }

	//获取组合商品
	public function fetchgroup($page,$where,$fileds,$pageSize=null){
		if ($page != null&&$pageSize!=null) {
				$offset = ($page-1)*$pageSize;
				$limit = " limit  $offset, $pageSize";
			}
		if ($where != null) {
			$whereSql = " where $where";
		}
		$sqlfetch="select $fileds from ".$this->_table_group_goods." $whereSql $limit";
		return $this->_db->fetchAll($sqlfetch);

		}

	/**
     * 获取商品基本信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getProductInfo($where = null, $fields = '*'){
        $whereSql = "where p_status = 0";
		if ($where != null) {
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where) && count($where) ) {
                 $whereSql .= 'AND ' . implode(' and ', $where);
			}
		}
		$sql = "SELECT $fields FROM `$this->_table_product` {$whereSql}  ";
		return $this -> _db -> fetchRow($sql);
    }

    /**
     * 获取商品和产品基本信息
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getGoodsProductInfo($where = null, $fields = '*'){
        $whereSql = "where t2.p_status = 0";
		if ($where != null) {
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where) && count($where) ) {
                foreach ($where as $key => $value) {
                    $whereSql .= " AND $key='$value'";
                }
			}
		}
		$sql = "SELECT $fields FROM `$this->_table_goods` as t1 left join `$this->_table_product` as t2 on t1.product_id = t2.product_id {$whereSql} ";
		return $this -> _db -> fetchRow($sql);
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
		else    $whereSql = " WHERE 1=1";
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY  goods_sort DESC, a.goods_id DESC";
		}
		$whereSql .= " and c.p_status = 0";
		$table = "`$this->_table_goods` a  INNER JOIN `$this->_table_goods_cat` b ON a.view_cat_id=b.cat_id INNER JOIN `$this->_table_product` c on a.product_id = c.product_id";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) FROM $table $whereSql");
		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
		
		
	}

	/**
     * 获取品牌列表
     *
     * @return   array
     */
	public function getBrand($where=null)
	{
		$sql = "SELECT brand_id,brand_name FROM `$this->_table_brand` where $where  ORDER BY brand_id limit 10 ";
		return $this -> _db -> fetchAll($sql);
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
	public function get($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}

		$whereSql = " WHERE p.p_status = 0";
		if ($where != null) {
			$whereSql .= " and $where";
		}
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY  a.goods_sort ASC, a.goods_id DESC";
		}

		$table = "`$this->_table_goods` a
		          INNER JOIN `$this->_table_goods_cat` b ON a.view_cat_id=b.cat_id
		          INNER JOIN `$this->_table_product` p ON a.product_id=p.product_id
		          INNER JOIN `$this->_table_brand` d ON d.brand_id=p.brand_id
				  ";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) FROM $table $whereSql");

		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
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
	public function getCatGoods($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}

		$whereSql = "where c.p_status = 0";
		if ($where != null) {
			$whereSql .= " and $where";
		}
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY  goods_sort ASC, a.goods_id DESC";
		}
		$table = "`$this->_table_goods` a
		          INNER JOIN `$this->_table_goods_cat` b ON a.view_cat_id=b.cat_id
		          INNER JOIN `$this->_table_product` c ON a.product_id = c.product_id
		          INNER JOIN `$this->_table_brand` d ON d.brand_id=c.brand_id
			  ";
		$this -> total = $this -> _db -> fetchOne("SELECT count(a.goods_id) FROM $table $whereSql");
		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
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
	public function getList($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = " WHERE $where";
		}
		else {
		    $whereSql = " WHERE 1=1";
		}
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY  goods_sort ASC, a.goods_id DESC";
		}
		$table = "`$this->_table_goods` a
		          INNER JOIN `$this->_table_goods_cat` b ON a.view_cat_id=b.cat_id ";
		$this -> total = $this -> _db -> fetchOne("SELECT count(*) FROM $table $whereSql");
		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql $orderBy $limit");
	}

	/**
     * 获取商品分类信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getGoodsCatList($where)
	{
		if ($where != null) {
			$whereSql = " WHERE  1 $where";
		}else{
			$whereSql = " WHERE 1 ";
		}
		$table = "`$this->_table_goods` a  INNER JOIN `$this->_table_goods_cat` b ON a.view_cat_id=b.cat_id";
		return $this -> _db -> fetchAll("SELECT DISTINCT(b.cat_id),cat_name,cat_path,parent_id FROM $table  $whereSql ");
	}

	/**
     * 获取实体商品
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getProduct($where = null, $fields = '*', $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}

		$whereSql = "where a.p_status = 0";
		if ($where != null) $whereSql .= " and $where";
		$table = "`$this->_table_product` a LEFT JOIN `$this->_table_goods` b ON a.product_id = b.product_id  LEFT JOIN `$this->_table_product_cat` c ON a.cat_id = c.cat_id";
		return $this -> _db -> fetchAll("SELECT $fields FROM $table $whereSql ORDER BY b.goods_id ASC $limit ");
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where)
	{
		if ($where != null) $where = "WHERE $where";
		else    $where = "WHERE 1=1";
		$sql = "SELECT * FROM `$this->_table_goods_tag` $where";
		return $this -> _db -> fetchAll($sql);
	}


	/**
     * 获取商品单页标签
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
     * 获取商品状态列表
     *
     * @param    string    $where
     * @param    string    $fix
     * @return   array
     */
	public function getStockStatus($where, $fix = '')
	{
		$whereSql = "where b.p_status = 0";
		if ($where != null) $whereSql .= " and $where";
		$table = $this->_table_stock_status.$fix;
		$sql = "SELECT a.*,b.product_sn,b.renew_number,c.goods_id,c.goods_name,onsale FROM `$table` a INNER JOIN `$this->_table_product` b ON a.product_id=b.product_id INNER JOIN `$this->_table_goods` c ON b.product_id=c.product_id $whereSql";
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 获取关联商品
     *
     * @param    int      $goods_id
     * @return   array
     */
	public function getLink($goods_id)
	{   $sql_count="select count(goods_id)as countall from {$this->_table_group_goods_link} where goods_id=$goods_id";
		$cc=$this->_db->fetchRow($sql_count);
		$sql = "SELECT b.* FROM `$this->_table_goods_link` a INNER JOIN `$this->_table_goods` b ON b.goods_id=a.goods_link_id  WHERE a.goods_id=$goods_id and  onsale=0 ";
		if($cc['countall']>0){
		$sql2="SELECT group_id as goods_id,group_goods_name as goods_name,group_goods_img as goods_img,group_goods_alt as goods_alt,group_price as price,group_market_price as market_price FROM `$this->_table_group_goods_link` a INNER JOIN `$this->_table_group_goods` b ON b.group_id=a.groupgoods_link_id  WHERE a.goods_id=$goods_id and b.status=1";
		$res=$this->_db->fetchAll($sql2);
			foreach($res as $k=>$v){
				$res[$k]['g_type']=2;
			}
		}
		$res2=$this -> _db -> fetchAll($sql);
		if($cc['countall']>0){
			return array_merge($res2,$res);
		}else{
			return $res2;
		}
	}

	/**
     * 获取商品图片
     *
     * @param    int      $id
     * @param    string   $where
     * @return   array
     */
	public function getImg($where)
	{
		$sql = "SELECT * FROM `$this->_table_goods_img` WHERE $where ORDER BY img_sort,img_id DESC";
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 获取分类列表
     *
     * @param    string   $where
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
		$sql = "SELECT cat_id,cat_name,cat_path,cat_sort,cat_status,parent_id,display,url_alias,meta_title,meta_keywords,meta_description,cat_band FROM `$this->_table_goods_cat` WHERE cat_status=0 and display=1 $whereSql $orderBy";
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 获取有效分类列表
     *
     * @param    string   $where
     * @return   array
     */
	public function getAllCat($where = null, $orderBy = null)
	{
		$where && $whereSql = " AND $where";
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY parent_id, cat_sort";
		}
		$sql = "SELECT * FROM `$this->_table_goods_cat` WHERE cat_status=0  $whereSql $orderBy";
		return $this -> _db -> fetchAll($sql);
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
	    $this -> _db -> update($this -> _table_goods, $set, $where);
	    return true;
	}

	/**
     * 更新数据
     *
     * @param    int       $status
     * @param    string    $product_sn
     * @param    int       $goods_id
     * @return   array
     */
    public function updateStatus($status, $product_sn, $goods_id)
	{
        $this -> _db -> update($this -> _table_goods, array('onsale' => 1, 'onoff_remark' => '商品缺货系统自动下架'), "goods_sn='$product_sn'");
        //日志记录开始
        $row = array (
                      'goods_id' => $goods_id,
                      'old_value' => 0,
                      'new_value' => 1,
                      'admin_name' => 'system',
                      'op_type' => 'onoff',
                      'remark' => '商品缺货系统自动下架',
                      'op_time' => time(),
                      );
      return  $this -> insertOp($row);
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
     * 添加应急补货数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert($data)
	{
        $this -> _db -> insert($this -> _table_renew, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}

	/**
     * 添加应急补货详细数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insertDetail($data)
	{
        $this -> _db -> insert($this -> _table_renew_detail, $data);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}

    /**
     * 检查商品有效性
     *
     * @return   array()
     */
    public function checkGoods($goodsId = 0)
    {
        return $this -> _db -> fetchRow ("SELECT goods_id,onsale FROM `$this->_table_goods` WHERE goods_id=$goodsId LIMIT 1");
    }
    /**
     * 获取及时可销售库存
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getSaleStock($where)
	{
		$whereSql = "WHERE status_id=2 and p.p_status = 0";
		if ($where != null) {
			$whereSql .= " and $where";
		}

		$stockStatus = $this->_table_stock_status.'_12';
		$table = "`$stockStatus` a INNER JOIN `$this->_table_product` p ON p.product_id=a.product_id";

		return $this -> _db -> fetchAll("SELECT p.*,(real_in_number-out_number) as able_number FROM $table $whereSql");
	}
    /**
     * 检查该商品是否已经被该用户放入暂存架
     *
     * @param    int    $goodsId
     * @param    int    $memberId
     * @return void
     */
    public function checkFavorite($goodsId, $memberId)
    {
        return $this -> _db -> fetchOne ("SELECT favorite_id FROM `$this->_table_member_favorite` WHERE goods_id=$goodsId and member_id=$memberId LIMIT 1");
    }
    
    
    public function  getFavoriteInfo($favorite_id)
    {
    	return $this -> _db -> fetchOne ("SELECT goods_id FROM `$this->_table_member_favorite` WHERE favorite_id={$favorite_id} LIMIT 1");
    	 
    }
    
    /**
     * 放入暂存架
     *
     * @param    array    $data
     * @return void
     */
    public function addFavorite($data)
    {
        $this -> _db -> insert($this -> _table_member_favorite, $data);
		return $this -> _db -> lastInsertId();
    }
    /**
     * 删除暂存架中的商品
     *
     * @param    array    $data
     * @return void
     */
    public function delFavorite($favoriteId, $userID)
    {
        return $this -> _db -> delete($this -> _table_member_favorite, "favorite_id={$favoriteId} and user_id={$userID}");
    }


    /**
     * 取得某分类下 的扩展商品 （表 shop_goods_extend_cat）
     *
     * @param int $cat_id
     *
     * @return array
     */
    public function getExtendCatGoods($cat_id) {
    	if($cat_id > 0){
    		$sql = "select t1.goods_id from {$this -> _table_goods_extend_cat} as t1 left join {$this -> _table_goods_cat} as t2 on t1.cat_id = t2.cat_id  left join {$this -> _table_goods} as t3 on t1.goods_id = t3.goods_id where t3.onsale = 0 and t2.cat_path like '%,{$cat_id},%'";
    		$rs = $this -> _db -> fetchAll($sql);
    		if(count($rs)){
    			foreach ($rs as $v){
	    			$tmp[] = $v['goods_id'];
	    		}
	    		return $tmp;
    		}else{
    			return false;
    		}
    	}else{
    		return false;
    	}
    }

    /*Start::搜索*/
	/**
	 * 得到搜索的ids
	 *
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $orderBy
	 *
	 * @return array
	 */
	public function getGoodsIds($where = null, $wwhere=null, $whereCat=null, $page=null, $pageSize = null, $orderBy = null) {
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		if ($where != null) {
			$whereSql = " AND $where ";
		}
		if ($wwhere != null) {
			$whereS = " AND $wwhere ";
		}
		/*Start::查询分类表*/
		if ($whereCat != null) {
			$whereCat = " AND $whereCat ";
		}
		if($whereCat){
			$rsCat = $this -> _db -> fetchAll("select cat_id from {$this->_table_goods_cat} where display=1  {$whereCat}");
			if(is_array($rsCat) && count($rsCat)){
				$catA = '(';
				foreach ($rsCat as $v){
					if($v['cat_id']){
						$catA .= " cat_path like '%,".$v['cat_id'].",%' or";
					}
				}
				$catA = substr($catA, 0, -2);
				$catA .= ')';
			}
		}
		$tmp=array();
		if(isset($catA) && strlen($catA)>3){
			$rsCat = $this -> _db -> fetchAll("select cat_path from {$this->_table_goods_cat} where  display=1 and {$catA} ");
			if(is_array($rsCat) && count($rsCat)){
				$tmpStr = '';
				foreach ($rsCat as $v){
					$tmpStr .= $v['cat_path'];
				}
				$tmp = explode(',', $tmpStr);
				$tmp = array_unique(array_filter($tmp));
			}
		}
		//shop_goods_cat.cat_name某记录完全匹配情况
		if(is_array($tmp) && count($tmp))
		{
			return $this -> _db -> fetchAll("
	select a.goods_id,a.num from (
	select goods_id, 1 as num from {$this -> _table_goods} where onsale=0 {$whereS}
	union
	select a.goods_id, 2 as num from {$this->_table_goods_keywords} a left join {$this -> _table_goods} b on a.goods_id=b.goods_id where b.onsale=0 {$whereSql}
	union
	select goods_id, 3 as num from {$this -> _table_goods} where onsale=0 and view_cat_id in (".implode(',', $tmp).")
	) a group by a.goods_id
	order by a.num asc $limit
	");
		}
		//shop_goods_cat.cat_name没有完全匹配记录情况
		else
		{
			if($whereS){
				return $this -> _db -> fetchAll("
	select a.goods_id,a.num from (
	select goods_id, 1 as num from {$this -> _table_goods} where onsale=0 {$whereS}
	union
	select a.goods_id, 2 as num from {$this->_table_goods_keywords} a left join {$this -> _table_goods} b on a.goods_id=b.goods_id where b.onsale=0 {$whereSql}
	) a group by a.goods_id
	order by a.num asc $limit
	");
			}else{
				$rs = null;
				return $rs;
			}
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
			$sql = "SELECT goods_id,goods_name,goods_sn,region,market_price,price,staff_price,t1.goods_img,t2.goods_style FROM `shop_goods` as t1 left join `shop_product` as t2 on t1.product_id = t2.product_id WHERE  onsale=0 and goods_id in (".implode(',', $ids).") order by field(goods_id,".implode(',', $ids).")";
			return $this -> _db -> fetchAll($sql);
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
	public function doAjaxSearch($where = null, $wwhere=null, $whereCat=null, $whereCatLike=null) {
		if ($where != null) {
			$whereSql = " AND $where ";
		}
		if ($wwhere != null) {
			$whereS = " AND $wwhere ";
		}
		/*Start::查询分类表*/
		if ($whereCat != null) {
			$whereCat = " AND $whereCat ";
		}
		$rsCat = $this -> _db -> fetchAll("select cat_id from {$this->_table_goods_cat} where   display=1 {$whereCat}");
		if(is_array($rsCat) && count($rsCat)){
			$catA = '(';
			foreach ($rsCat as $v){
				if($v['cat_id']){
					$catA .= " cat_path like '%,".$v['cat_id'].",%' or";
				}
			}
			$catA = substr($catA, 0, -2);
			$catA .= ')';
		}
		$tmp=array();
		if(isset($catA) && strlen($catA)>3){
			$rsCat = $this -> _db -> fetchAll("select cat_path from {$this->_table_goods_cat} where  display=1 and {$catA}");
			$rsCat = $this -> _db -> fetchAll("select cat_path from {$this->_table_goods_cat} where  display=1 and {$catA}");
			if(is_array($rsCat) && count($rsCat)){
				$tmpStr = '';
				foreach ($rsCat as $v){
					$tmpStr .= $v['cat_path'];
				}
				$tmp = explode(',', $tmpStr);
				$tmp = array_unique(array_filter($tmp));
			}
		}
		//shop_goods_cat.cat_name某记录完全匹配情况
		if(is_array($tmp) && count($tmp))
		{
			$allcount = count($this -> _db -> fetchAll("select count(rs.goods_id) as tot from (
	select goods_id from {$this -> _table_goods} where onsale=0 {$whereS} '
	union
	select a.goods_id from {$this->_table_goods_keywords} a left join {$this -> _table_goods} b on a.goods_id=b.goods_id where b.onsale=0 {$whereSql} '
	union
	select goods_id from {$this -> _table_goods} where onsale=0 and view_cat_id in (".implode(',', $tmp).")
	) rs group by rs.goods_id"));
			$rs[0] = $allcount;
			//
			$goodsLike = $this -> _db -> fetchAll("select goods_name from {$this -> _table_goods} where onsale=0 {$whereS}  limit 6");
			foreach ($goodsLike as $vv){
				$rs[1][] = $vv['goods_name'];
			}
			//
			$catLike = $this -> _db -> fetchAll("select cat_id,cat_name from {$this->_table_goods_cat} where $whereCatLike and display=1  limit 6");
			foreach ($catLike as $vv){
				$ct = $this -> _db -> fetchOne("select count(goods_id) as tot from {$this -> _table_goods} where onsale=0 and view_cat_id = {$vv['cat_id']} ");
				if($ct){
					$rs[2][] = array('cat_id'=>$vv['cat_id'],'cat_name'=>$vv['cat_name'],'ct'=>$ct);
				}
			}
			//
			return $rs;
		}
		//shop_goods_cat.cat_name没有完全匹配记录情况
		else
		{
			$allcount = count($this -> _db -> fetchAll("select count(rs.goods_id) from (
		select goods_id from {$this -> _table_goods} where onsale=0 {$whereS}  '
		union
		select a.goods_id from {$this->_table_goods_keywords} a left join {$this -> _table_goods} b on a.goods_id=b.goods_id where b.onsale=0 {$whereSql}
		) rs group by rs.goods_id"));
			$rs[0] = $allcount;
			//
			$goodsLike = $this -> _db -> fetchAll("select goods_name from {$this -> _table_goods} where onsale=0 {$whereS}  limit 6");
			foreach ($goodsLike as $vv){
				$rs[1][] = $vv['goods_name'];
			}
			//
			$catLike = $this -> _db -> fetchAll("select cat_id,cat_name from {$this->_table_goods_cat} where $whereCatLike and display=1  limit 6");
			foreach ($catLike as $vv){
				$ct = $this -> _db -> fetchOne("select count(goods_id) as tot from {$this -> _table_goods} where onsale=0 and view_cat_id = {$vv['cat_id']} ");
				if($ct){
					$rs[2][] = array('cat_id'=>$vv['cat_id'],'cat_name'=>$vv['cat_name'],'ct'=>$ct);
				}
			}
			//
			return $rs;
		}

	}
	/**
	 * 得到组合商品的id
	 *
	 * @param string $where
	 *
	 * @return array
	 */
	public function getGroupGoodsIds($where) {
		if(strlen($where) > 4){
			return $this -> _db -> fetchAll("select group_id from {$this->_table_shop_group_goods} where $where and status=1 order by group_id desc limit 4");
		}else{
			return null;
		}
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
			$sql = "SELECT group_id,group_goods_name,group_specification,group_goods_img,group_price FROM {$this->_table_shop_group_goods} WHERE status=1 and group_id in (".implode(',', $ids).")";
			return $this -> _db -> fetchAll($sql);
		}else{
			return false;
		}
	}
	/**
	 * 统计用户搜索词汇，定期筛选，shop_tj_searchwords
	 *
	 * @param string $words
	 */
	public function tjSearchWords($words) {
		if(!$words) return  false;		
		$rs = $this->_db->fetchOne("select id from {$this -> _table_shop_tj_searchwords} where searchword='$words'");//检查是否有重复
		if($rs){
			$this -> _db -> execute("update {$this -> _table_shop_tj_searchwords} set searchcount=searchcount+1,ltime=".time()." where searchword='$words'");
		}
		elseif((mb_strlen($words,'utf8')>1)){
			$this -> _db -> insert($this -> _table_shop_tj_searchwords, array('searchword' => $words, 'searchcount' => 1, 'ctime' => time(), 'ltime' => time(), 'status' =>1));
		}
	}
	/*End::搜索*/

    /**
     * 获得expand cat下商品ID(onsale)
     *
     * @param array     $cat_id_array
     * @param int       $attr_id
     */
    public function getGoodsIDByExpandCat($cat_id_array, $attr_id = 0) {
    	if ( is_array($cat_id_array) && (count($cat_id_array) > 0) ) {
    	    if ($attr_id) {
    	        $sql = "select t1.goods_id from `{$this -> _table_attribute}` as t1 left join `{$this -> _table_goods}` as t2 on t1.goods_id = t2.goods_id left join `{$this -> _table_goods_extend_cat}` as t3 on t3.goods_id = t1.goods_id where   onsale = 0 and t3.cat_id in (".implode(',', $cat_id_array).") and attr_id = {$attr_id}";
    	    }
    	    else {
    	        $sql = "SELECT t1.goods_id FROM `{$this -> _table_goods_extend_cat}` as t1 left join `{$this -> _table_goods}` as t2 on t1.goods_id = t2.goods_id where  onsale = 0 and t1.cat_id in(".implode(',', $cat_id_array).")";
    	    }
    	    $data = $this -> _db -> fetchAll($sql);

    	    if ($data) {
                foreach ( $data as $goodsID ) {
                    $result[] = $goodsID['goods_id'];
                }
                return $result;
    	    }
    	    else    return array();
    	}
    }

    /**
     * 获得分类
     *
     * @param int     $cat_id
     */
    public function getGoodsCat($cat_id) {
        return $this -> _db -> fetchRow("select * from {$this -> _table_goods_cat} where cat_id = {$cat_id}");
    }

    /**
     * 获取产品系统分类
     *
     * @param    string    $where
     * @return   array
     */
	public function getProductCat($where)
	{
	    return $this -> _db -> fetchAll("select * from {$this -> _table_product} as t1 inner join {$this -> _table_goods_cat} as t2 on t1.cat_id = t2.cat_id where {$where}");
	}

	/**
	 * 取得前端类别数据
	 * @return unknown
	 */
	public function getSiteCat(){
	    $list = $this->getAll($this->_table_goods_cat,array('display'=>1),"*",0,"cat_sort desc");
	    return $list;
	}

	/**
	 * 取得前端分类树组装三级
	 * @return Ambigous <unknown, multitype:unknown >
	 */
	public function getCatNavTree(){

	    //取得所有前端类别
	    $list = $this->getAll($this->_table_goods_cat,array('display'=>1,'cat_status'=>0),'*',0,'cat_sort desc');
	    //组装三级分类
        $tree = array();
        foreach ($list as $k=>$v){
            if($v['parent_id'] == 0){
                $tree[] = $v;
            }
        }

        foreach ($tree as $k=>$v){
            foreach ($list as $kk=>$vv){
                if($vv['parent_id'] == $v['cat_id']){
                    $tree[$k]['sub'][] = $vv;
                }
            }
        }

        foreach ($tree as $k=>$v){
            if(empty($v['sub'])) continue;
            foreach ($v['sub'] as $kk=>$vv){
                foreach ($list as $kkk=>$vvv){
                    if($vvv['parent_id'] == $vv['cat_id']){
                        $tree[$k]['sub'][$kk]['sub'][] = $vvv;
                    }
                }
            }
        }

        //处理品牌关联
        //取得所有品牌
        $list_brand = $this->getAll('shop_brand',array('status'=>0),'brand_id,brand_name,as_name,small_logo');

        foreach ($tree as $k=>$v){
            $exp_brand_link_ids = explode(',', $v['brand_link_ids']);
            $arr_brand_link = array();
            foreach ($list_brand as $kk=>$vv){
                if(in_array($vv['brand_id'], $exp_brand_link_ids)) $arr_brand_link[] = $vv;
            }
            $tree[$k]['brand_link'] = $arr_brand_link;
        }

        return $tree;

	}

	/**
	 * 根据类别ID 取得类别 面包屑
	 * @param unknown_type $cat_id
	 */
	public function getCatCrumbs($cat_id){

	    $r_cat = $this->getRow($this->_table_goods_cat,array('cat_id'=>$cat_id));	 
	    $crumbs = '';
	    if($r_cat['parent_id']>0)
	    {	    	
	    	$path = trim($r_cat['cat_path'],',');	
	        $pathArr =  array_unique(explode(',',$path));  
	        $path = implode(',', $pathArr);
		    $list_cat = $this->getAll($this->_table_goods_cat,array('cat_id|in'=>$path));			    
		    $catData =  Custom_Model_Tools::list_fkey($list_cat, 'cat_id');		   
		    foreach ($pathArr as $ck=>$cid)
		    {
		    	$cat = $catData[$cid];
		    	$crumbs .= '&nbsp;&gt; <a href="/gallery-'.$cat['cat_id'].'.html">'.$cat['cat_name'].'</a>';
		    }
		   
	    }else{
	    	 $crumbs = '&nbsp;&gt; <a href="/gallery-'.$r_cat['cat_id'].'.html">'.$r_cat['cat_name'].'</a>';
	    }
	    return $crumbs;
	}

	/**
	 * 根据类别ID 取得类别名称
	 * @param unknown_type $cat_id
	 */
	public function getCatNameById($cat_id){
	    $r_cat = $this->getRow($this->_table_goods_cat,array('cat_id'=>$cat_id));
	    return $r_cat['cat_name'];
	}

	/**
     * 取得类别下的兄弟类别列表
     * @param unknown_type $cat_id
     *
     */
	public function getCatSiblings($cat_id){
	    $r_cat = $this->getRow($this->_table_goods_cat,array('cat_id'=>$cat_id));
	    if($r_cat['parent_id'] == 0)
	    {
	     $parent_id = $r_cat['cat_id'];
	    }else{
	      $parent_id = $r_cat['parent_id'];
	    }

	    $list = $this->getSiteCat();
	    $siblings = array();
	    foreach ($list as $k=>$v){
	        if($v['parent_id'] == $parent_id) $siblings[] = $v;
	    }
	    return $siblings;
	}


	public function getBrandByCatId($cat_id){
	    //取得所有了类别
	    $list_cat = $this->getAll($this->_table_goods_cat,array('cat_path|l'=>','.$cat_id.','));
	    $arr_cat_id = array();
	    foreach ($list_cat as $k=>$v){
	        $arr_cat_id[] = $v['cat_id'];
	    }

        //关联分类商品ID
        $list_gec = $this->getAll('shop_goods_extend_cat',array('cat_id|in'=>$arr_cat_id));
        $arr_goods_id = array();
        foreach ($list_gec as $k=>$v){
            $arr_goods_id[] = $v['goods_id'];
        }

	    //取得类别下商品
	    $db_select = $this->_db->select();
	    $db_select->from($this->_table_goods.' as g','g.goods_id')
	        ->joinLeft($this->_table_product.' as p','p.product_id=g.product_id','p.brand_id')
	        ->joinLeft('shop_brand as b','b.brand_id=p.brand_id','b.brand_name')
	    ;

	    $where = array();
	    $arr_cat_id[] = 0;
	    if(!empty($arr_goods_id)){
	        $where['_sql'] = 'g.view_cat_id in('.implode(',',$arr_cat_id).') or g.goods_id in ('.implode(',',$arr_goods_id).')';
	    }else{
	        $where['g.view_cat_id|in'] = $arr_cat_id;
	    }

	    $where['g.onsale'] = 0;
	    $where['g.is_del'] = 0;
	    $where['g.is_gift'] = 0;

	    $this->where($db_select,$where);
	    $list_goods = $this->_db->fetchAll($db_select);

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

	public function getGoodsByPage(&$pagenav,$pn,$ps=32,$where=array(),$sort=''){
	    $map = array();
		    if(!empty($where['bid'])){
	        $map['p.brand_id'] = (int)$where['bid'];
	    }
		if(!empty($where['cat_id'])){
		    $list_sub_cat = $this->getAll($this->_table_goods_cat,array('cat_path|l'=>','.$where['cat_id'].',','cat_status'=>0,'display'=>1));
		    $arr_cat_id = array();
		    $arr_cat_id[] = 0;
		    foreach ($list_sub_cat as $k=>$v){
		        $arr_cat_id[] = $v['cat_id'];
		    }

		    //关联分类商品ID
		    $list_gec = $this->getAll('shop_goods_extend_cat',array('cat_id|in'=>$arr_cat_id));
		    $arr_goods_id = array();
		    $arr_goods_id[] = 0;
		    foreach ($list_gec as $k=>$v){
		        $arr_goods_id[] = $v['goods_id'];
		    }
		    if(!empty($arr_goods_id)){
		        $map['_sql'] = 'g.view_cat_id in('.implode(',',$arr_cat_id).') or g.goods_id in ('.implode(',',$arr_goods_id).')';
		    }else{
		        $map['g.view_cat_id|in'] = $arr_cat_id;
		    }

	    }

	    if(!empty($where['price'])){
	        $arr_price = explode('_', $where['price']);
	        if(count($arr_price)<=1){
	            $map['g.price|egt'] = $arr_price[0];
	        }else{
	            $map['g.price|egt'] = $arr_price[0];
	            $map['g.price|elt'] = $arr_price[1];
	        }
	    }

	    $map['g.onsale'] = 0;
	    $map['g.is_del'] = 0;
	    $map['g.is_gift'] = 0;

	    //得到总记录数，分页连接
	    $db_select = $this->_db->select();
	    $db_select->from($this->_table_goods.' as g','count(*) as total')
	        ->joinLeft($this->_table_product.' as p','p.product_id=g.product_id');
	    $this->where($db_select,$map);

	    $r = $this->_db->fetchRow($db_select);
	    $total = $r['total'];
	    $page = new Custom_Model_Page($total, $ps,12);
	    $pagenav = $page -> showPage(5);
	    //数据列表
	    $db_select = $this->_db->select();
	    $db_select->from($this->_table_goods.' as g','g.*')
	        ->joinLeft($this->_table_product.' as p','p.product_id=g.product_id','p.brand_id')
	        ->joinLeft($this->_table_brand.' as b','b.brand_id=p.brand_id','b.as_name')
	        ->order($sort)
	        ->limit($ps,($pn-1)*$ps);

	    $this->where($db_select,$map);
	    
	    $list_goods = $this->_db->fetchAll($db_select);

	    return $list_goods;


	}

	/**
	 * 取得关联
	 * @param unknown_type $id
	 * @param unknown_type $limit_type
	 */
	public function getRelation($id,$limit_type,$type,$num){
	    $arr_goods_id = array();

	    //取得商品关联
	    if($limit_type == 'single'){
	        $r = $this->getRow($this->_table_goods_relation,array('id'=>$id,'limit_type'=>'similar','type'=>$type));
	        $arr_goods_id = explode(',', $r['goods_ids']);

    	    //商品的分类ID
    	    $r_goods = $this->getRow($this->_table_goods,array('goods_id'=>$id));
    	    $view_cat_id = $r_goods['view_cat_id'];
	    }else{
	        $view_cat_id = $id;
	    }

	    //根据分类ID取得分类路径
	    $r_cat = $this->getRow($this->_table_goods_cat,array('cat_id'=>$view_cat_id));
	    $arr_cat_id = explode(',',trim($r_cat['cat_path'],','));

	    //取得类别关联
	    $list_relation = $this->getAll($this->_table_goods_relation,array('id|in'=>$arr_cat_id,'type'=>$type,'limit_type'=>'cat'));
	    foreach ($arr_cat_id as $k=>$v){
	        foreach ($list_relation as $kk=>$vv){
	            if($v == $vv['id']) $arr_goods_id = array_merge(explode(',',$vv['goods_ids']),$arr_goods_id);
	        }
	    }

	    //取得全局关联
	    $r = $this->getRow($this->_table_goods_relation,array('id'=>0,'type'=>$type,'limit_type'=>'global'));
	    if(!empty($r['goods_ids'])) $arr_goods_id = array_merge($arr_goods_id,explode(',', $r['goods_ids']));

	    $arr_goods_id = array_slice(array_unique($arr_goods_id), 0,$num);

	    $list_goods = $this->getAllWithLink(
	            'shop_goods as g|g.*',
	            array(
                    'shop_product as p'=>'p.product_id=g.product_id',
                    'shop_brand as b'=>'b.brand_id=p.brand_id|b.as_name'
	            ),
	            array('g.goods_id|in'=>$arr_goods_id,'g.is_del'=>0,'g.is_gift'=>0,'g.onsale'=>0));
	    $list = array();
	    foreach ($arr_goods_id as $k=>$v){
	        foreach ($list_goods as $kk=>$vv){
	            if($v == $vv['goods_id']) $list[] = $vv;
	        }
	    }
	    return $list;

	}

	public function getGoodsById($arr_id){
	    $tbl = 'shop_goods as g|g.goods_id,g.goods_alt,g.goods_sn,g.goods_name,g.goods_img,g.price,g.market_price';
	    $links = array();
	    $links['shop_product as p'] = 'p.product_id=g.product_id';
	    $links['shop_brand as b'] = 'b.brand_id=p.brand_id|b.as_name';
	    $ord = 'FIND_IN_SET(g.goods_id,"'.implode(',',$arr_id).'")';

	    return $this->getAllWithLink($tbl,$links,array('g.goods_id|in'=>$arr_id),0,$ord);

	}
	
	public function addGoodsNotice($data){
		$this->_db->insert($this->_table_goods_notice, $data);
		return $this->_db->lastInsertId();
	} 
	
	public  function  updateGoodsNotice($data)
	{
		$account= $data['account'];
		unset($data['account'],$data['ctreated']);
		$where = $this->_db->quoteInto('account = ?', $account);
		if ($account) {
			return $this->_db->update($this->_table_goods_notice, $data, $where);
		}
	}
	
	public  function getNoticeByAccount($account,$goods_id)
	{
		return $this->_db->fetchRow("select *  from {$this->_table_goods_notice} where account='{$account}' AND goods_id=$goods_id");
	}
}