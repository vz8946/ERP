<?php

class Shop_Models_DB_GroupGoods
{
	/**
     * Zend_Db
     * @var    Zend_Db
     */
	public $_db = null;

	/**
     * page size
     * @var    int
     */
	private $_pageSize = null;

	/**
     * table name
     * @var    string
     */
	public $_table_group_goods = 'shop_group_goods';
	private $_table_goods = 'shop_goods';
    private $_table_product = 'shop_product';
    private $_table_group_goods_msg = 'shop_group_goods_msg';
	private $_table_view_tag = 'shop_goods_view_tag';
	private $_table_product_cat = 'shop_product_cat';

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
     * 获取商品数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $page = null, $pageSize = null, $orderBy = null, $statusFlag = 1)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		if ($statusFlag) {
		    $whereSql = " WHERE status = 1  and is_shop_sale =1 and check_status =1 ";
		}
		else {
		    $whereSql = " WHERE 1  and is_shop_sale =1 and check_status =1 ";
		}
		if($where){
			$whereSql .= " and $where ";
		}

		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY group_id DESC";
		}

		$this -> total = $this -> _db -> fetchOne("SELECT count(*) FROM $this->_table_group_goods $whereSql");
		return $this -> _db -> fetchAll("SELECT $fields FROM $this->_table_group_goods $whereSql $orderBy $limit");
	}

	/**
	 * 取得config的商品
	 *
	 * @param array $search
	 * @return array $goods
	 */
	public function fetchConfigGoods($search) {
		$where = 'where 1=1';
		if($search){
			$search['group_id'] && $where .= ' and group_id='.$search['group_id'];
			$search['group_sn'] && $where .= ' and group_sn='.$search['group_sn'];
			$temp = $this -> _db -> fetchRow("select group_goods_config from $this->_table_group_goods $where limit 1");
			if(is_array($temp) && count($temp)){
				$configs = unserialize($temp['group_goods_config']);
				$goods = array();
                foreach ($configs as $v){
                    $sql = "SELECT g.goods_id,p.product_id,p.product_name as goods_name,p.product_sn,p.product_sn as goods_sn,p.goods_style,p.suggest_price as price,p.product_img as goods_img,p.cat_id,p.p_length,p.p_width,p.p_height,p.p_weight,c.cat_name, p.cost FROM `$this->_table_product` p left join shop_goods g on g.product_id=p.product_id left join {$this ->_table_product_cat} c on p.cat_id = c.cat_id where p.p_status = 0 and p.product_id=".$v['product_id'];
                    $tmp = $this -> _db -> fetchRow($sql);
                    $tmp['number'] = $v['number'];                    
                    $goods[$tmp['goods_id']] = $tmp;                    
                }
				return $goods;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	/**
	 * 检查组合商品是否过期
	 *
	 * @param int $group_id
	 * @return bool
	 */
	public function checkStatus($group_id) {
		if($group_id>0){
			$tmp = $this -> _db -> fetchRow("select status from $this->_table_group_goods where group_id = $group_id limit 1");
			return $tmp['status'];
		}
		return false;
	}

	/**
	 * 正常商品的详细页，关联组合商品->取得对应 的组合商品列表
	 *
	 * @param int $goods_id
	 *
	 * @return Array
	 */
	public function getRelateGroupGoods($goods_id) {
		if($goods_id>0){
			$goods_id = '|'.$goods_id.'|';
			return $this -> _db -> fetchAll("select * from $this->_table_group_goods where goods_ids like '%$goods_id%' and status=1  and is_shop_sale =1 ");
		}
		return false;
	}

	/**
	 * 得到goods资料
	 *
	 * @param array $where
	 * @param string $fields
	 *
	 * @return array;
	 */
	public function getGoods($search=null,$fields='*') {
		$where  = 'where t1.p_status = 0 ';
		if($search){
			$search['goods_id'] && $where.=" and t2.goods_id=".$search['goods_id'];
			$search['product_id'] && $where.=" and t1.product_id=".$search['product_id'];
		}

	    $sql = "SELECT $fields FROM `$this->_table_product` as t1 left join `$this->_table_goods` as t2 on t1.product_id = t2.product_id {$where}  ";
		return $this->_db->fetchAll($sql);
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
		//
		$rs['tot'] = $this -> _db -> fetchOne("select count(group_goods_msg_id) from {$this->_table_group_goods_msg} $where");
		$rs['datas'] = $this -> _db -> fetchAll("select $fields from {$this->_table_group_goods_msg} $where $orderby $limit");
		return $rs;
	}

	/**
     * 添加组合商品评论
     *
     * @param array $data
     * @return int
     */
	public function commentAdd($data){
		$this -> _db -> insert($this -> _table_group_goods_msg, $data);
		return $this -> _db -> lastInsertId();
	}
}
