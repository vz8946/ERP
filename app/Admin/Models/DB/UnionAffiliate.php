<?php

class Admin_Models_DB_UnionAffiliate
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 分页大小
     * 
     * @var    int
     */
	private $_pageSize = null;
	
	/**
     * 订单分成表名
     * 
     * @var    string
     */
	private $_affiliateTable = 'shop_union_affiliate';
	
	/**
     * 订单分成记录表名
     * 
     * @var    string
     */
	private $_affiliateLogTable = 'shop_union_affiliate_log';
	
	/**
     * 打款记录表名
     * 
     * @var    string
     */
	private $_affiliatePayTable = 'shop_union_affiliate_pay';
	
	/**
     * 联盟类型对应表名
     * 
     * @var    string
     */
	private $_unionTable = array('2' => 'shop_union_normal');
	
	/**
     * 对象初始化
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
     * 取得可打款联盟列表信息
     *
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getPayList($where,$page = null, $pageSize = null, $unType, $exwhere = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
        if ($unType== 1 ) { 
             $unWhere= " AND un_type=0  AND  ( (cpa_goods_type=2 and (order_status_logistic=3 or order_status_logistic=4) ) or (cpa_goods_type=1 and  add_time < " .  mktime(0, 0, 0, date('m')-1, 1, date('Y')) . ") )";
        } else {
             $unWhere="  AND add_time < ". mktime(0, 0, 0, date('m')-1, 1, date('Y')) ."  AND un_type=0 ";
        }
		$ssql= 'SELECT user_id, user_name, union_type, COUNT(order_id) as order_num, SUM(order_price) as order_price, SUM(order_price_goods) as order_price_goods, 
				SUM(order_affiliate_amount) as order_affiliate_amount, SUM(affiliate_money) as affiliate_money 
				FROM `' . $this -> _affiliateTable . '` WHERE separate_type=0 ' .$where. $unWhere .' GROUP BY user_id';

		if($exwhere){
			$sql="select * from (".$ssql. ") as temp  where 1" .$exwhere . $limit ;
		}else{
			$sql=$ssql. $limit;
		}
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得可打款联盟个数
     *
     * @param    void
     * @return   int
     */
	public function getPayListCount($where, $unType, $exwhere = null)
	{
        if ($unType== 1 ) {
                 $unWhere= " AND un_type=1  AND  ( (cpa_goods_type=2 and (order_status_logistic=3 or order_status_logistic=4) ) or (cpa_goods_type=1 and  add_time < " .  mktime(0, 0, 0, date('m')-1, 1, date('Y')) . ") )";
        } else {
                 $unWhere="  AND add_time < ". mktime(0, 0, 0, date('m')-1, 1, date('Y')) ."  AND un_type=0 ";
        }

		$sqls = 'SELECT COUNT(DISTINCT(user_id)) as count FROM `' . $this -> _affiliateTable . '` WHERE separate_type=0  '.$where . $unWhere;

		$ssql= 'SELECT user_id, user_name, union_type, COUNT(order_id) as order_num, SUM(order_price) as order_price, SUM(order_price_goods) as order_price_goods, 
				SUM(order_affiliate_amount) as order_affiliate_amount, SUM(affiliate_money) as affiliate_money 
				FROM `' . $this -> _affiliateTable . '` WHERE separate_type=0 ' .$where. $unWhere .' GROUP BY user_id';
		if($exwhere){
			$sql="select COUNT(DISTINCT(user_id)) as count from (".$ssql. ") as temp  where 1" .$exwhere . $limit ;
		}else{
			$sql=$sqls;
		}

		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}
	
	/**
     * 取得可打款联盟订单分成信息
     *
     * @param    int      $id
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getAffiliate($id, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		
		$sql = 'SELECT * FROM `' . $this -> _affiliateTable . '` WHERE user_id=' . $id . ' AND separate_type=0 AND add_time < '. mktime(0, 0, 0, date('m')-1, 1, date('Y')) . ' ORDER BY affiliate_id DESC ' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得可打款联盟订单个数
     *
     * @param    int    $id
     * @return   int
     */
	public function getAffiliateCount($id)
	{
		$sql = 'SELECT COUNT(order_id) as count FROM `' . $this -> _affiliateTable . '` WHERE user_id=' . $id . ' AND separate_type=0 AND add_time < '. mktime(0, 0, 0, date('m')-1, 1, date('Y'));
		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}
	
	/**
     * 取得CPA可打款联盟订单分成信息
     *
     * @param    int      $id
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getCpaAffiliate($id, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		
        $unWhere= " AND un_type=0  AND  ( (cpa_goods_type=2 and (order_status_logistic=3 or order_status_logistic=4) ) or (cpa_goods_type=1 and  add_time < " .  mktime(0, 0, 0, date('m')-1, 1, date('Y')) . ") )";

		$sql = 'SELECT * FROM `' . $this -> _affiliateTable . '` WHERE user_id=' . $id . ' AND separate_type=0 ' . $unWhere . '  ORDER BY affiliate_id DESC ' . $limit;

		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得CPA可打款联盟订单个数
     *
     * @param    int    $id
     * @return   int
     */
	public function getCpaAffiliateCount($id)
	{
        $unWhere= " AND un_type=0  AND  ( (cpa_goods_type=2 and (order_status_logistic=3 or order_status_logistic=4) ) or (cpa_goods_type=1 and  add_time < " .  mktime(0, 0, 0, date('m')-1, 1, date('Y')) . ") )";

		$sql = 'SELECT COUNT(order_id) as count FROM `' . $this -> _affiliateTable . '` WHERE user_id=' . $id . ' AND separate_type=0 ' . $unWhere ;

		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}

	/**
     * 取得可打款联盟订单分成历史信息
     *
     * @param    int      $orderId
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getAffiliateLog($orderId, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		
		$sql = 'SELECT A.order_id, A.order_sn, A.user_id, A.user_name, B.* FROM `' . $this -> _affiliateTable . '` AS A LEFT JOIN `' . $this -> _affiliateLogTable . '` AS B ON A.order_id=B.order_id WHERE A.order_id=' . $orderId  . ' ORDER BY add_time ASC ' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得可打款联盟订单分成历史个数
     *
     * @param    int    $orderId
     * @return   int
     */
	public function getAffiliateLogCount($orderId)
	{
		$sql = 'SELECT COUNT(affiliate_log_id) as count FROM `' . $this -> _affiliateLogTable . '` WHERE order_id=' . $orderId;
		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}
	
	/**
     * 设置订单分成状态
     * 
     * @param    int     $affiliateId
     * @param    int     $value
     * @param    string  $msg
     * @return void
     */
    public function setOrderAffiliate($affiliateId, $value, $msg = null)
    {
        $set = array ('separate_type' => $value);
        $msg && $set['edite_note'] = $msg;
		$where = $this -> _db -> quoteInto('affiliate_id = ?', $affiliateId);
		return $this -> _db -> update($this -> _affiliateTable, $set, $where);
    }
    
    /**
     * 取得取消分成订单信息
     *
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getNoSeparateOrder($page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		
		$sql = 'SELECT * FROM `' . $this -> _affiliateTable . '` WHERE separate_type=2 ORDER BY affiliate_id DESC ' . $limit;;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得取消分成订单个数
     *
     * @param    void
     * @return   int
     */
	public function getNoSeparateOrderCount()
	{
		$sql = 'SELECT COUNT(order_id) as count FROM `' . $this -> _affiliateTable . '` WHERE separate_type=2';
		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}
	
	/**
     * 添加分成记录
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addAffiliate(array $data, $untype = '0')
	{
		$set = array('separate_type' => 1);
		$where = 'user_id=' . $data['user_id'] . ' AND separate_type=0 AND add_time < '. mktime(0, 0, 0, date('m')-1, 1, date('Y'));
        if($untype == '1'){
              $where = " user_id=" . $data['user_id'] . " AND un_type=1  AND  ( (cpa_goods_type=2 and (order_status_logistic=3 or order_status_logistic=4) ) or (cpa_goods_type=1 and  add_time < " .  mktime(0, 0, 0, date('m')-1, 1, date('Y')) . ") )";
        }
		$update = $this -> _db -> update($this -> _affiliateTable, $set, $where);
		
		if ($update) {
			$row = array (
                          'admin_id' => $data['admin_id'],
                          'admin_name' => $data['admin_name'],
                          'admin_note' => $data['admin_note'],
                          'user_id' => $data['user_id'],
                          'user_name' => $data['user_name'],
                          'payee' => $data['payee'],
                          'telephone' => $data['telephone'],
                          'id_card_no' => $data['id_card_no'],
                          'id_card_img' => $data['id_card_img'],
                          'bank_name' => $data['bank_name'],
                          'bank_full_name' => $data['bank_full_name'],
                          'bank_account' => $data['bank_account'],
                          'account_user_name' => $data['account_user_name'],
                          'amount' => $data['amount'],
                          'get_money_type' => $data['get_money_type'],
                          'add_time' => $data['add_time']
                          );
            $this -> _db -> insert($this -> _affiliatePayTable, $row);
            return $this -> _db -> lastInsertId();
		}
	}
	
	/**
     * 取得已分成列表
     *
     * @param    array   $where
     * @param    int     $page
     * @param    int     $pageSize
     * @return   array
     */
	public function getSeparate($where, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT $pageSize OFFSET $offset";
		}
		
		if (is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		} else {
			$whereSql = $where;
		}
		
		$sql = 'SELECT * FROM `' . $this -> _affiliatePayTable . '` ' . $whereSql . ' ORDER BY affiliate_pay_id DESC ' . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得已分成个数
     *
     * @param    array    $where
     * @return   int
     */
	public function getSeparateCount($where)
	{
		if (is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		} else {
			$whereSql = $where;
		}
		$sql = 'SELECT COUNT(affiliate_pay_id) as count FROM `' . $this -> _affiliatePayTable . $whereSql;
		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}
}