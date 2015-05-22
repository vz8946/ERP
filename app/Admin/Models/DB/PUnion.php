<?php

class Admin_Models_DB_PUnion
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
     * 推广联盟表名
     * 
     * @var    string
     */
	private $_table = 'shop_union_normal';
	
	/**
     * 联盟点击统计表
     * 
     * @var    string
     */
	private $_dateLogTable = 'shop_union_date_log';
	
	/**
     * 分成记录表
     * 
     * @var    string
     */
	private $_affiliateTable = 'shop_union_affiliate';
	
	/**
     * 用户表名
     * 
     * @var    string
     */
	private $_userTable = 'shop_user';
	
	/**
     * 会员信息表名
     * 
     * @var    string
     */
	private $_memberTable = 'shop_member';
	
	/**
     * 会员账户表名
     * 
     * @var    string
     */
	private $_accountTable = 'shop_user_account';
	
	/**
     * 联盟会员分类分成表
     * 
     * @var    string
     */
	private $_unionCat = 'shop_union_cat';
	
	/**
     * 联盟会员商品分成表
     * 
     * @var    string
     */
	private $_unionGoods = 'shop_union_goods';
	
	/**
     * 商品表
     * 
     * @var    string
     */
	private $_goods = 'shop_goods';
	
	private $_product = 'shop_product';
	
	/**
     * 分类表
     * 
     * @var    string
     */
	private $_goodsCat = 'shop_goods_cat';


	private $_table_order = 'shop_order';
    
    private $_table_goods = 'shop_goods';
    
    private $_table_goods_cat = 'shop_goods_cat';
	
	private $total = null;
	
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
     * 取得推广联盟信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getPUnion($where = null, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}
		
		if ($where != null) {
			if (is_array($where)) {
				$whereSql = " WHERE 1=1";
			    foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='".$value."'";
			    }
			} else {
				$whereSql = $where;
			}
		}
		
		$sql = 'SELECT A.*, 
				       B.*, 
				       C.payee, 
				       C.telephone, 
				       C.id_card_no, 
				       C.id_card_img, 
				       C.bank_name, 
				       C.bank_full_name, 
				       C.bank_account 
				       FROM `' . $this -> _userTable . '` AS A INNER JOIN `' . $this -> _table . '` AS B ON A.user_id=B.user_id LEFT JOIN `' . $this -> _accountTable . '` AS C ON A.user_id=C.user_id ' . 
				       $whereSql . 
				       $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得推广联盟列表信息
     *
     * @param    string   $where
     * @param    string   $whereReg
	 * @param    string   $whereClick
	 * @param    string   $whereOrder
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getPUnionList($where = null, $whereReg = null, $whereClick = null, $whereOrder = null, $orderBy = null, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}
		
		if ($where != null) {
			if (is_array($where)) {
				$whereSql = " WHERE 1=1";
			    foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='".$value."'";
			    }
			} else {
				$whereSql = $where;
			}
		}

        if(!$orderBy){
             $orderBy =' ORDER BY A.user_id  DESC ';
        }

		$sql = 'SELECT 
				A.user_id, 
				A.user_name, 
				A.add_time,  
				B.proportion, 
				B.status, 
				B.cname,
				B.union_normal_id,  
				B.calculate_type,
				(SELECT COUNT(M.user_id) FROM `' . $this -> _memberTable . '` AS M WHERE M.parent_id=A.user_id '.$whereReg.')  AS reg_num, 
				(SELECT SUM(click_num) FROM `' . $this -> _dateLogTable . '` WHERE user_id=A.user_id ' . $whereClick . ' ) AS click_num, 
				(SELECT COUNT(F.order_id) FROM `' . $this -> _affiliateTable. '` AS F WHERE F.user_id=A.user_id AND F.order_status = 0 '. $whereOrder . ') AS order_num,  
				(SELECT SUM(F.order_price_goods) FROM `' . $this -> _affiliateTable. '` AS F WHERE F.user_id=A.user_id AND F.order_status = 0 '. $whereOrder . ') AS order_amount, 
				(SELECT SUM(F.order_affiliate_amount) FROM `' . $this -> _affiliateTable. '` AS F WHERE F.user_id=A.user_id AND F.order_status = 0 '. $whereOrder . ') AS order_affiliate_amount 
				FROM `' . $this -> _userTable . '` AS A INNER JOIN `' 
				        . $this -> _table 
				        . '` AS B ON A.user_id=B.user_id ' . 
				$whereSql . 
				$orderBy .
				$limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得内容联盟数据汇总
     *
     * @param    string   $where
     * @param    string   $whereReg
	 * @param    string   $whereClick
	 * @param    string   $whereOrder
     * @return   array
     */
	public function countpUnionAllData($where = null, $whereReg = null, $whereClick = null, $whereOrder = null)
	{
		if ($where != null) {
			if (is_array($where)) {
				$whereSql = " WHERE 1=1";
			    foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='".$value."'";
			    }
			} else {
				$whereSql = $where;
			}
		}
 
		$sql = 'SELECT 
				sum((SELECT COUNT(M.user_id) FROM `' . $this -> _memberTable . '` AS M WHERE M.parent_id=A.user_id '.$whereReg.'))  AS reg_num, 
				sum((SELECT SUM(click_num) FROM `' . $this -> _dateLogTable . '` WHERE user_id=A.user_id ' . $whereClick . ' )) AS click_num, 
				sum((SELECT COUNT(F.order_id) FROM `' . $this -> _affiliateTable. '` AS F WHERE F.user_id=A.user_id AND F.order_status = 0 '. $whereOrder . ')) AS order_num,  
				sum((SELECT SUM(F.order_price_goods) FROM `' . $this -> _affiliateTable. '` AS F WHERE F.user_id=A.user_id AND F.order_status = 0 '. $whereOrder . ')) AS order_amount 
				FROM `' . $this -> _userTable . '` AS A INNER JOIN `' 
				        . $this -> _table . '` AS B ON A.user_id=B.user_id ' . 
				$whereSql;
 
		return $this -> _db -> fetchRow($sql);
	}
	
	/**
     * 取得用户基表信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getUser($where = null)
	{
		if (is_array($where)) {
			$whereSql = " WHERE 1=1";
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='".$value."'";
			}
		}
		
		$sql = 'SELECT * FROM `' . $this -> _userTable . '` ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得推广联盟人数
     *
     * @param    string    $where
     * @return   int
     */
	public function getPUnionCount($where = null)
	{
		if ($where != null) {
			if (is_array($where)) {
				$whereSql = " WHERE 1=1";
			    foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='".$value."'";
			    }
			} else {
				$whereSql = $where;
			}
		}
		
		$sql = 'SELECT count(A.user_id) as count FROM `' . $this -> _userTable . '` AS A INNER JOIN `' . $this -> _table . '` AS B ON A.user_id=B.user_id ' . $whereSql;
		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}
	
	/**
     * 添加推广联盟
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addPUnion(array $data)
	{
		$userRow = array (
                          'user_name' => $data['user_name'],
                          'password' => $data['password'],
                          'add_time' => $data['add_time']
                          );
        $this -> _db -> insert($this -> _userTable, $userRow);
        $userId =  $this -> _db -> lastInsertId();
        
        $pUnionRow = array (
                            'user_id' => $userId,
                            'affiliate_type' => $data['affiliate_type'],
                            'get_money_type' => $data['get_money_type'],
                            'proportion' => $data['proportion'],
                            'proportion_rule' => (int)$data['proportion_rule'],
                            'cname' => $data['cname'],
                            'real_name' => $data['real_name'],
                            'sex' => $data['sex']||0,
                            'email' => $data['email'],
                            'msn' => $data['msn'],
                            'qq' => $data['qq'],
                            'phone' => $data['phone'],
                            'mobile' => $data['mobile'],
                            'un_type' => $data['un_type'],
                            'status' => 1,
        					'calculate_type' => $data['calculate_type']
                            );
        $this -> _db -> insert($this -> _table, $pUnionRow);
        
        $accountRow = array (
                            'user_id' => $userId,
                            'payee' => $data['payee'],
                            'telephone' => $data['telephone'],
                            'id_card_no' => $data['id_card_no'],
                            'id_card_img' => $data['id_card_img'],
                            'bank_name' => $data['bank_name'],
                            'bank_full_name' => $data['bank_full_name'],
                            'bank_account' => $data['bank_account'] 
                            );
        $this -> _db -> insert($this -> _accountTable, $accountRow);
        
        //分成比率是否为商品分成
        if($data['calculate_type']==2) $this -> byProductProportion($userId);//所属联盟不同,则分成商品也不同
        
		return $userId;
	}
	
	/**
     * 更新推广联盟信息
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function updatePUnion(array $data, $id)
	{
		$userSet = array (
                          'user_name' => $data['user_name'],
                          'update_time' => $data['update_time']
                          );
        $data['password'] && $userSet['password'] = $data['password'];
        
        $where = $this -> _db -> quoteInto('user_id = ?', $id);
		$this -> _db -> update($this -> _userTable, $userSet, $where);
		
		$pUnionSet = array (
                            'affiliate_type' => $data['affiliate_type'],
                            'get_money_type' => $data['get_money_type'],
                            'proportion' => $data['proportion'],
                            'proportion_rule' => (int)$data['proportion_rule'],
                            'cname' => $data['cname'],
                            'real_name' => $data['real_name'],
                            'sex' => $data['sex']||0,
                            'email' => $data['email'],
                            'msn' => $data['msn'],
                            'qq' => $data['qq'],
                            'phone' => $data['phone'],
                            'un_type' => $data['un_type'],
                            'mobile' => $data['mobile'],
							'calculate_type' => $data['calculate_type']
                            );
        $this -> _db -> update($this -> _table, $pUnionSet, $where);
        
        $accountSet = array (
                            'payee' => $data['payee'],
                            'telephone' => $data['telephone'],
                            'id_card_no' => $data['id_card_no'],
                            'bank_name' => $data['bank_name'],
                            'bank_full_name' => $data['bank_full_name'],
                            'bank_account' => $data['bank_account']
                            );
        $data['id_card_img'] && $accountSet['id_card_img'] = $data['id_card_img'];
        $this -> _db -> update($this -> _accountTable, $accountSet, $where);
        if( ($data['calculate_type'] != $data['ori_calculate_type']) && ($data['calculate_type']==2) ){
        	$this -> byProductProportion($id);//所属联盟改变,则分成商品也要随之改变
        }
		if( ($data['calculate_type'] != $data['ori_calculate_type']) && ($data['calculate_type']==1) ){
        	$this -> byProductProportionDel($id);
        }
        return $id;
	}
	
	/**
     * 删除指定推广联盟
     *
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function deletePUnionById($id)
	{
		$where = $this -> _db -> quoteInto('user_id = ?', $id);
		$this -> _db -> delete($this -> _userTable, $where);
		$this -> _db -> delete($this -> _table, $where);
		$this -> byProductProportionDel($id);
		return $this -> _db -> delete($this -> _accountTable, $where);
	}
	
	/**
     * 更新推广联盟状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   int    lastInsertId
     */
	public function updateStatus($id, $status)
	{
		$set = array ('status' => $status);
		$where = $this -> _db -> quoteInto('union_normal_id = ?', $id);
		return $this -> _db -> update($this -> _table, $set, $where);
	}
 
	/**
     * 取某内容联盟点击天数
     *
     * @param    string    $where
     * @return   int
     */
	public function getClickCount($where = null)
	{
		if ($where != null) {
			$whereSql = " WHERE 1=1 ";
			$whereSql .= $where;
		}
		
		$sql = 'SELECT count(union_date_log_id) AS count FROM `' . $this -> _dateLogTable . '` ' . $whereSql;
		$count = $this -> _db -> fetchOne($sql);
		return $count;
	}
	
	/**
     * 取某内容联盟点击量
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getClick($where = null, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}
		
		if ($where != null) {
			$whereSql = " WHERE 1=1 ";
			$whereSql .= $where;
		}
		
		$sql = 'SELECT  date,click_num FROM `' . $this -> _dateLogTable . '` ' .  $whereSql . $limit;
		 
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 搜索订单数量
     *
     * @param    string    $where
     * @return   int
     */
	public function searchOrderCount($where = null)
	{
		$whereSql = " WHERE A.union_type = 2 ";			
		$whereSql .= $where;
		$sql = 'SELECT count(A.affiliate_id) as total,SUM(A.order_affiliate_amount) as total_order_affiliate_amount,SUM(A.affiliate_money) as total_affiliate_money,
        SUM(A.order_price_goods) as total_order_price_goods  FROM `' . $this -> _affiliateTable. '` AS A LEFT JOIN `'.$this ->_table_order. '` AS B ON A.order_sn = B.order_sn ' .  $whereSql;
		return $this -> _db -> fetchRow($sql);
	}
	
	/**
     * 搜索订单订单
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function searchOrder($where = null, $page = null, $pageSize = null)
	{
		if ($page != null) {
		    $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		    if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " ORDER BY B.add_time DESC LIMIT $pageSize OFFSET $offset";
		    }
		}
		$whereSql = " WHERE A.union_type = 2 ";
		$whereSql .= $where;

		$sql = 'SELECT  A.*,B.add_time as order_time  FROM `' . $this -> _affiliateTable. '` AS A  LEFT JOIN `'.$this ->_table_order. '` AS B ON A.order_sn = B.order_sn ' . $whereSql . $limit;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 导出订单数据
     *
     * @param    string   $where
     * @return   array
     */
	public function getOrder($where = null)
	{
		$whereSql = " WHERE A.union_type = 2";
		$whereSql .= $where;
 	
		$sql = 'SELECT 
		       A.*, GROUP_CONCAT( if( B.number - B.return_number >0 && B.product_id >0, B.goods_name, NULL ) SEPARATOR \' | \' ) AS goods_name 
			   FROM `' . $this -> _affiliateTable. '` AS A LEFT JOIN shop_order AS O ON A.order_sn = O.order_sn  LEFT JOIN shop_order_batch_goods AS B ON A.order_sn = B.order_sn'
			   .  $whereSql . ' GROUP BY A.order_sn ORDER BY A.modify_time ASC';
		return $this -> _db -> fetchAll($sql);
	}


	/**
     * 导出订单商品数据
     *
     * @param    string   $where
     * @return   array
     */
	public function getOrderGoods($where = null)
	{
		$whereSql = " WHERE A.union_type = 2  AND  B.goods_id >0 ";
		$whereSql .= $where;
 	
		$sql = 'SELECT  A.*,B.* FROM `' . $this -> _affiliateTable. '` AS A LEFT JOIN shop_order AS O ON A.order_sn = O.order_sn LEFT JOIN shop_order_batch_goods AS B ON A.order_sn = B.order_sn'.  $whereSql . '  ORDER BY A.modify_time ASC';
		return $this -> _db -> fetchAll($sql);
	}


	/**
     * 导出推广联盟信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getUnion($where = null)
	{
		if ($where != null) {
			if (is_array($where)) {
				$whereSql = " WHERE 1=1";
			    foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='".$value."'";
			    }
			} else {
				$whereSql = $where;
			}
		}
		$sql = 'SELECT 
				A.user_id, 
				A.user_name, 
				A.add_time,  
				B.proportion, 
				B.status, 
				B.cname, 
				B.union_normal_id,  
				(SELECT COUNT(M.user_id) FROM `' . $this -> _memberTable . '` AS M WHERE M.parent_id=A.user_id '.$whereReg.')  AS reg_num, 
				(SELECT SUM(click_num) FROM `' . $this -> _dateLogTable . '` WHERE user_id=A.user_id ' . $whereClick . ' ) AS click_num, 
				(SELECT COUNT(F.order_id) FROM `' . $this -> _affiliateTable. '` AS F WHERE F.user_id=A.user_id AND F.order_status = 0 '. $whereOrder . ') AS order_num,  
				(SELECT SUM(F.order_price_goods) FROM `' . $this -> _affiliateTable. '` AS F WHERE F.user_id=A.user_id AND F.order_status = 0 '. $whereOrder . ') AS order_amount 
				FROM `' . $this -> _userTable . '` AS A INNER JOIN `' 
				        . $this -> _table 
				        . '` AS B ON A.user_id=B.user_id ' . 
				$whereSql ;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 联盟会员按所属站点商品比例分成
	 * 
	 * @param int $uid
	 * 
	 * @return bool
	 */
	public function byProductProportion($uid) {
		
		$uid = (int)$uid;
		if($uid>0){
			//如果有则先删除此联盟会员对应的记录
			$this -> byProductProportionDel($uid);
			//插入
			$sql = "insert into {$this -> _unionGoods} (union_id,goods_id,cat_id,proportion) (select {$uid},goods_id,cat_id,16 from {$this->_goods} where onsale=0 )";
			return $this -> _db -> execute($sql);
		}else{
			return false;
		}
	}
	
	/**
	 * 删除联盟会员按商品比例分成
	 * 
	 * @param int $uid
	 * 
	 * @return bool
	 */
	public function byProductProportionDel($uid) {
		$uid = (int)$uid;
		if($uid>0){
			$where = $this -> _db -> quoteInto('union_id = ?', $uid);
			return $this -> _db -> delete($this -> _unionGoods, $where);
		}else{
			return false;
		}
	}
	
	/**
	 * 某联盟的商品分成比率列表
	 * 
	 * @param int $uid
	 * @param int $page
	 * 
	 * @return array
	 */
	public function getProductProportionList($where = null, $page = null, $pageSize = null, $orderBy =null ,$all = false) {
		if($where == null){return null;}
		//分页
		$limit = null;
		if(!$all){
			$page = (int)$page ? $page : 1;
			$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
			if ($page!=null) {
		        $offset = ($page-1)*$pageSize;
		        $limit = " LIMIT $pageSize OFFSET $offset";
		    }
		}
	    //条件
		if($where != null) {
			if (is_array($where)) {
				$whereSql = " WHERE 1=1";
			    foreach ($where as $key => $value)
			    {
				    $whereSql .= " AND $key='".$value."'";
			    }
			} else {
				$whereSql = $where;
			}
		}
		
		$sqlcount = 'select 
			count(A.id)
			from '.$this -> _unionGoods.' A left join '.$this -> _goods.' B on A.goods_id=B.goods_id left join '.$this -> _product.' as D on B.product_id = D.product_id left join '.$this -> _goodsCat.' C on D.cat_id=C.cat_id 
			where '.$whereSql;
		
		$sql = 'select 
			A.id,
			A.union_id,
			A.proportion,
			B.goods_id,
			B.goods_name,
			B.price,
			B.onsale,
			C.cat_id,
			C.cat_name
			from '.$this -> _unionGoods.' A left join '.$this -> _goods.' B on A.goods_id=B.goods_id left join '.$this -> _product.' as D on B.product_id = D.product_id left join '.$this -> _goodsCat.' C on D.cat_id=C.cat_id 
			where '.$whereSql.$orderBy.$limit;
        $rs = array();
		$rs['total'] = $this -> _db -> fetchOne($sqlcount);
		$rs['data'] = $this -> _db -> fetchAll($sql);
		return $rs;
	}
	
	/**
     * 设置单个商品的分成比例
     * 
     * @param int $id
     * @param int $val
     * @param int $uid
     * 
     * @return bool
     */
    public function setGoodsProportion($uid,$id,$val) {
    	if($id>0 && $val<41 && $val>=0 && $uid>0){
    		$set = array('proportion' => $val);
    		$where = $this -> _db -> quoteInto('goods_id = ?', $id).' and '.$this -> _db -> quoteInto('union_id = ?', $uid);
    		$this -> _db -> update($this -> _unionGoods,$set,$where);
    	}else{
    		return false;
    	}
    }
    
	/**
     * 设置分类下的商品分成比例
     * 
     * @param int $uid
     * @param array $catgories
     * @param int $cat_proportion
     * 
     * @return bool
     * 
     */
    public function setCatProductProportion($uid,$catgories,$cat_proportion) {
    	$uid = (int)$uid;
    	$cat_proportion = (int)$cat_proportion;
    	if($uid>0){
    		$set = array('proportion' => $cat_proportion, 'cat_proportion' => $cat_proportion);
    		foreach ($catgories as $v){
    			$whereArr[] = 'cat_id = '.$v;
    		}
    		$where = implode(' or ', $whereArr);
    		$where = ' ('.$where.') and union_id = '.$uid;
    		$this -> _db -> update($this -> _unionGoods,$set,$where);
    	}else{
    		return false;
    	}
    }
    
    /**
     * 得到商品分类id
     * 
     * @param int $cat_id
     * 
     * @return array();
     */
    public function getCategories($cat_id) {
    	$cat_id = (int)$cat_id;
    	if($cat_id <1){ return false;}
    	$sql = "select cat_path from " . $this->_goodsCat . " where cat_path like '%,".$cat_id.",%' ";
    	$rs = $this -> _db -> fetchAll($sql);
    	if(is_array($rs) && count($rs)){
    		foreach ($rs as $v){
    			$tmp = array_filter(explode(',', $v['cat_path']));
    			foreach ($tmp as $kk => $vv){
    				$back[] = $vv;
    			}
    		}
    		return array_unique($back);
    	}else{
    		return null;
    	}
    }
    
	/**
	 * 插入单条记录到 shop_union_goods 中
	 * 
	 * @param int $uid
	 * @param int $goods_id
	 * 
	 * @return bool
	 */
	public function insertGoodsToUnionGoods($uid,$goods_id) {
		$uid = (int)$uid;  $goods_id = (int)$goods_id;
		if($uid>0 && $goods_id>0){
			$sql = "insert into {$this -> _unionGoods} (union_id,goods_id,cat_id,proportion) (select {$uid},goods_id,cat_id,16 from {$this->_goods} where onsale=0 and goods_id = $goods_id)";
			$this -> _db -> execute($sql);
			$insertID = $this -> _db -> lastInsertId();
			//取出刚才插入的记录
			$thisRs = $this -> _db -> fetchRow("select * from {$this -> _unionGoods} where id=".$insertID);
			$cat_id = $thisRs['cat_id'];//确定cat_id
			//查看同类商品的cat_id
			$excludeThisRs = $this -> _db -> fetchRow("select * from {$this -> _unionGoods} where cat_id=".$cat_id." and union_id=".$uid." and id!=".$insertID." limit 1");
			//更新
			$this -> _db -> update($this -> _unionGoods, array('proportion'=>$excludeThisRs['cat_proportion'],'cat_proportion'=>$excludeThisRs['cat_proportion']), " id=".$insertID);
			return $insertID;
		}
		return false;
	}
    
    /**
     * 取得商品的分成比例
     * 
     * @param array $search
     * @param string $fields
     * @param int $page
     * @param int $pageSize
     * @param string $orderby
     */
    public function getUnionGoods($search=null, $fields='*', $page=null, $pageSize=null, $orderby=null){
        $limit = null;
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		
		$where = ' where 1';
		if($search){
			$search['union_id'] && $where.=" and union_id='".$search['union_id']."'";
		}
		$orderby = " order by id desc ";
		$sqlct = "select count(id) from {$this->_unionGoods} $where";
		$sql   = "select $fields from {$this->_unionGoods} $where $orderby $limit";
		$rs['tot'] = $this -> _db -> fetchOne($sqlct);
		$rs['datas'] = $this -> _db -> fetchAll($sql);
		return $rs;
    }
    
    /**
     * 得到商品的分类所属 （1：保健品   2：保健食品）
     * 
     * @param int $goods_id
     * 
     * @return int
     */
    public function getGoodsCatBelong($goods_id){
        $sql = "select gc.cat_path from {$this->_table_goods} as g left join {$this -> _product} as p on g.product_id = p.product_id left join {$this->_table_goods_cat} as gc on p.cat_id=gc.cat_id where g.goods_id=".$goods_id;
        $rs = $this -> _db -> fetchOne($sql);
        if(strstr($rs, ',1,')){
            return 1;
        }elseif(strstr($rs, ',2,')){
            return 2;
        }else{
            return 0;
        }
    }
}
