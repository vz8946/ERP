<?php
class Shop_Models_DB_Tuan
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
    private $_table = 'shop_tuan';
	private $_table_tuan_goods = 'shop_tuan_goods';
	private $_table_goods = 'shop_goods';
	private $_table_order_goods = 'shop_order_batch_goods';
	private $_table_order = 'shop_order_batch';

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
     * ��ȡ�Ź���ݼ�
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetch($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}

		$whereSql = "WHERE  {$where}";

		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY tuan_id DESC";
		}
		return array('list' => $this -> _db -> fetchAll("SELECT $fields FROM {$this->_table} as t1 left join {$this->_table_tuan_goods} as t2 on t1.goods_id = t2.id left join {$this->_table_goods} as t3 on t2.goods_id = t3.goods_id $whereSql $orderBy $limit"),
		             'total' => $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this->_table} as t1 left join {$this->_table_tuan_goods} as t2 on t1.goods_id = t2.id left join {$this->_table_goods} as t3 on t2.goods_id = t3.goods_id $whereSql"));
	}

	/**
     * ����ID��ѯ
     *
     * @param    string    $id
     * @return   array
     */
	public function getTuanById($id = null)
	{
		if ( $id > 0 ) {
	        return $this-> _db -> fetchRow("select t1.*,
	                                               t2.goods_id as tuan_goods_id,t2.show_info,t2.description as tuan_goods_description,t2.img1,t2.img2,t2.img3,t2.img4,t2.img5,
	                                               t3.goods_sn,t3.product_id,t3.market_price,t3.brief,t3.description as goods_description,t3.spec,t3.goods_img
	                                        from {$this->_table} as t1
	                                        left join {$this->_table_tuan_goods} as t2 on t1.goods_id = t2.id
	                                        left join {$this->_table_goods} as t3 on t2.goods_id = t3.goods_id
	                                        where t1.status = 0  and tuan_id=".$id);
		}
	}

	/**
     * ��ö����Ź���Ʒ
     *
     * @param    int        $tuanID
     * @param    string     $field
     * @return   array
     */
	public function getOrderTuanGoodsByTuanID($tuanID, $field = '*')
	{
	    return $this -> _db -> fetchAll("select {$field} from {$this->_table_order_goods} as t1
	                                     left join {$this->_table_order} as t2 on t1.order_batch_id = t2. order_batch_id
	                                     where t1.type=6 and t1.offers_id = {$tuanID} and t2.status = 0");
	}

	public function getTuan2Index($num){
		$ontime = time();
		return $this -> _db -> fetchAll("SELECT tuan_id,t1.title,img1,t1.price,market_price,alt_count FROM {$this->_table} as t1 left join {$this->_table_tuan_goods} as t2 on t1.goods_id = t2.id left join {$this->_table_goods} as t3 on t2.goods_id = t3.goods_id where t1.start_time <= $ontime and t1.end_time > $ontime and t1.status = 0  limit $num ");
	}



}