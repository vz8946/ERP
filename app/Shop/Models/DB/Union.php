<?php

class Shop_Models_DB_Union
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 推广联盟表名
     * 
     * @var    string
     */
	private $_uNormalTable = 'shop_union_normal';
	
	/**
     * 普通用户表名
     * 
     * @var    string
     */
	private $_memberTable = 'shop_member';
	
	/**
     * 会员基表名
     * 
     * @var    string
     */
	private $_userTable = 'shop_user';
	
	/**
     * 联盟访问记录表名
     * 
     * @var    string
     */
	private $_logTable = 'shop_union_log';
	
	/**
     * 联盟每日访问记录表名
     * 
     * @var    string
     */
	private $_dateLogTable = 'shop_union_date_log';
	
	/**
     * 联盟分成记录表
     * 
     * @var    string
     */
	private $_affiliateTable = 'shop_union_affiliate';
	
	/**
     * 联盟分成记录日志表
     * 
     * @var    string
     */
	private $_affiliateLogTable = 'shop_union_affiliate_log';
	
	/**
     * 联盟分成记录日志表
     * 
     * @var    string
     */
	private $_unionGoods = 'shop_union_goods';
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
     * 根据user_id获取联盟信息
     *
     * @param    int    $uid
     * @return   array
     */
	public function getUnionById($uid)
	{
		$where = "A.user_id = '" . $uid . "'";
		$sql = 'SELECT A.user_id, 
		        A.user_name, 
				B.member_id, 
				B.rank_id, 
				B.status as member_status, 
				D.union_normal_id, 
                D.un_type, 
				D.affiliate_type, 
				D.proportion as normal_proportion, 
				D.proportion_rule as normal_proportion_rule, 
				D.calculate_type,
				D.status as normal_status
				FROM `' . $this -> _userTable . '` AS A 
				LEFT JOIN `' . $this -> _memberTable . '` AS B ON A.user_id=B.user_id 
				LEFT JOIN `' . $this -> _uNormalTable . '` AS D ON A.user_id=D.user_id 
				WHERE ' . $where;
		return $this -> _db -> fetchRow($sql);
	}
	
	/**
     * 记录联盟访问记录
     *
     * @param    int    $uid
     * @return   array
     */
	public function addUnionLog($data)
	{
		$logRow = array (
                          'login_time' => $data['login_time'],
                          'login_ip' => $data['login_ip'],
                          'area' => $data['area'],
                          'other' => $data['other'],
                          'referer' => $data['referer'],
                          'user_id' => $data['user_id']
                          );
        //$this -> _db -> insert($this -> _logTable, $logRow);
        $dateLog = $this -> _db -> fetchRow("SELECT * FROM `" . $this -> _dateLogTable . "` WHERE date='" . $data['date'] . "' AND user_id='" . $data['user_id'] . "'");
        
        if ($dateLog) {
        	$where = "date='" . $data['date'] . "' AND user_id='" . $data['user_id'] . "'";
            $dateLogSet = array (
                                  'date' => $data['date'],
                                  'user_id' => $data['user_id'],
                                  'click_num' => $dateLog['click_num'] + 1,
                                  'union_type' => $data['union_type']
                                  );
            return $this -> _db -> update($this -> _dateLogTable, $dateLogSet, $where);
        } else {
        	$dateLogRow = array (
                                  'date' => $data['date'],
                                  'user_id' => $data['user_id'],
                                  'click_num' => 1,
                                  'union_type' => $data['union_type']
                                  );
            return $this -> _db -> insert($this -> _dateLogTable, $dateLogRow);
        }
	}
	
	/**
     * 根据order_id获取分成信息
     *
     * @param    int    $orderId
     * @return   array
     */
	public function getAffiliateByOrderId($orderId)
	{
		$where = "order_id = '" . $orderId . "'";
		$sql = 'SELECT *  FROM `' . $this -> _affiliateTable . '` WHERE ' . $where;
		return $this -> _db -> fetchRow($sql);
	}
	
	/**
     * 记录订单分成信息
     *
     * @param    array    $data
     * @return   bool
     */
    public function addAffiliate($data)
    {
        $affiliateLogRow = array (
                                  'order_id' => $data['order_id'],
                                  'order_sn' => $data['order_sn'],
                                  'order_user_id' => $data['user_id'],
                                  'order_user_name' => $data['user_name'],
                                  'order_status' => $data['status'],
                                  'order_status_logistic' => $data['status_logistic'],
                                  'order_status_return' => $data['status_return'],
                                  'order_affiliate_amount' => $data['affiliate_amount'],
                                  'order_price_goods' => $data['price_goods'],
                                  'order_price' => $data['price_order'],
                                  'proportion' => $data['proportion'],
                                  'affiliate_money' => $data['affiliate_money'],
                                  'add_time' => $data['add_time']
                                  );
        $affiliateLogId =  $this -> _db -> insert($this -> _affiliateLogTable, $affiliateLogRow);
        $affiliateLogId = $this -> _db -> lastInsertId();
        $affiliate = $this -> getAffiliateByOrderId($data['order_id']);

    	if ($affiliate) {
        	$where = "order_id='" . $data['order_id'] . "'";
            $affiliateSet = array (
                                   'order_affiliate_amount' => $data['affiliate_amount'],
                                   'order_price_goods' => $data['price_goods'],
                                   'order_price' => $data['price_order'],
                                   'cpa_goods_type' => $data['cpa_goods_type'],
								   'cpa_zero_type' => $data['cpa_zero_type'],
                                   'order_status' => $data['status'],
                                   'order_status_logistic' => $data['status_logistic'],
                                   'order_status_return' => $data['status_return'],
                                   'affiliate_money' => $data['affiliate_money'],
                                   'modify_time' => $data['modify_time'],
                                   'separate_type' => $data['separate_type'],
                                   'edite_note' => $affiliate['edite_note'] . '^' . $data['edite_note'],
                                   'last_affiliate_log_id' => $affiliateLogId
                                   );
            return $this -> _db -> update($this -> _affiliateTable, $affiliateSet, $where);
        } else {
        	$affiliateRow = array (
                                   'order_id' => $data['order_id'],
                                   'order_sn' => $data['order_sn'],
                                   'order_user_id' => $data['user_id'],
                                   'order_user_name' => $data['user_name'],
                                   'cpa_goods_type' => $data['cpa_goods_type'],
				                   'cpa_zero_type' => $data['cpa_zero_type'],
                                   'order_affiliate_amount' => $data['affiliate_amount'],
                                   'order_price_goods' => $data['price_goods'],
                                   'order_price' => $data['price_order'],
                                   'order_status' => $data['status'],
                                   'order_status_logistic' => $data['status_logistic'],
                                   'order_status_return' => $data['status_return'],
                                   'user_id' => $data['parent_id'],
                                   'user_name' => $data['parent_name'],
                                   'rank_name' => $data['rank_name'],
                                   'union_type' => $data['union_type'],
                                   'un_type' => $data['un_type'],
                                   'user_param' => $data['parent_param'],
                                   'proportion' => $data['proportion'],
                                   'affiliate_money' => $data['affiliate_money'],
                                   'add_time' => $data['add_time'],
                                   'code_param' => $data['code_param'],
                                   'edite_note' => $data['edite_note'],
                                   'last_affiliate_log_id' => $affiliateLogId
                                   );
            return $this -> _db -> insert($this -> _affiliateTable, $affiliateRow);
        }
    }
	/**
     * 根据用户ID取用户信息
     *
     * @param    int    $userID
     * @return   array
     */
    function getUser($userID)
    {
        $sql = "select * from {$this -> _userTable} where user_id={$userID} LIMIT 1";
		return $this -> _db -> fetchRow($sql);
    }
    /**
     * 根据用户名仅获取用户基本信息
     *
     * @param    string    $userName
     * @return   array
     */
    function onlyGetUser($userName)
    {
        $sql = "select * from {$this -> _userTable} where user_name='{$userName}' LIMIT 1";
		return $this -> _db -> fetchRow($sql);
    }
    /**
     * 根据联盟用户ID取某商品的分成比率
     *
     * @param    int    $userID
     * @param    int	$goods_id
     * @return   array
     */
    function getOneGoodsProportion($userID, $goods_id)
    {
        $userID=(int)$userID; $goods_id=(int)$goods_id;
    	if($userID>0 && $goods_id>0){
	    	$sql = "select proportion from {$this -> _unionGoods} where union_id='{$userID}' and goods_id='{$goods_id}' LIMIT 1";
	    	$rs = $this -> _db -> fetchRow($sql);
			return $rs['proportion'] ? $rs['proportion'] : 0;
    	}else{
    		return 0;
    	}
    }
    
	/**
     * 根据联盟用户ID取某“组合商品”的分成比率
     *
     * @param    int    $userID
     * @param    int	$group_id
     * @return   array
     */
    function getOneGroupGoodsProportion($userID, $group_id)
    {
        return 16;
    }


	/**
     * 取得会员信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getMember($where = null)
	{
		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='$value'";
			    }
			}
		}
		$sql = 'SELECT A.*, B.* FROM `' . $this -> _userTable . '` AS A INNER JOIN `' . $this -> _memberTable . '` as B ON A.user_id=B.user_id '. $whereSql . " ORDER BY A.user_id DESC LIMIT 1";
		return $this -> _db -> fetchRow($sql);
	}
}