<?php

class Shop_Models_DB_Information
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
    private $_pageSize = 20;
    
    /**
     * 会员基表名
     * 
     * @var    string
     */
    private $_userTable = 'shop_user';
    
    /**
     * 普通会员表名
     * 
     * @var    string
     */
    private $_memberTable = 'shop_member';
    
    
    /**
     * 推广联盟表名
     * @var    string
     */
    private $_pUnionTable = 'shop_union_normal';
    
    /**
     * 联盟点击记录表
     * @var    string
     */
    private $_clicktable = 'shop_union_date_log';

    /**
     * 会员账户表名
     * 
     * @var    string
     */
    private $_accountTable = 'shop_user_account';
    
    /**
     * 分成表名
     * 
     * @var    string
     */
    private $_affiliateTable = 'shop_union_affiliate';
    
    /**
     * 分成记录表名
     * 
     * @var    string
     */
    private $_affiliateLogTable = 'shop_union_affiliate_log';
    
    /**
     * 支付历史表名
     * 
     * @var    string
     */
    private $_affiliatePayTable = 'shop_union_affiliate_pay';
    
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
	
	/**
     * 分类表
     * 
     * @var    string
     */
	private $_goodsCat = 'shop_goods_cat';
    
    /**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
    public function __construct()
    {
        $this  ->  _db = Zend_Registry::get('db');
        $this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
    }
    
    /**
     * 取得推广用户信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
    public function getPromotionUser($where = null, $page = null, $pageSize = null)
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
        
        $sql = 'SELECT  A.*,  B.*  FROM `' . $this -> _userTable . '` AS A INNER JOIN `' . $this -> _memberTable . '` AS B ON A.user_id=B.user_id ' . 
                $whereSql .  $limit;
        return $this -> _db -> fetchAll($sql);
    }
    
    /**
     * 取得推广用户人数
     *
     * @param    string    $where
     * @return   int
     */
    public function getPromotionUserCount($where = null)
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
        
        $sql = 'SELECT count(A.user_id) as count FROM `' . $this -> _userTable . '` AS A INNER JOIN `' . $this -> _memberTable . '` AS B ON A.user_id=B.user_id ' . $whereSql;
        $count = $this -> _db -> fetchOne($sql);
        return $count;
    }
    

    /**
     * 取得推广用户点击总数
     *
     * @param    string    $where
     * @return   int
     */
    public function getClickCount($where = null)
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
        $sql = 'SELECT SUM(click_num) as count  FROM `' . $this -> _clicktable . '` ' . $whereSql ;
        $count = $this -> _db -> fetchOne($sql);
        return $count;
    }


    /**
     * 取得推广用户信息
     *
     * @param    string   $where
     * @return   array
     */
    public function getPromotionDayCount($where = null)
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
        $whereSql .= " GROUP BY FROM_UNIXTIME(A.add_time, '%d')";
        
        $sql = "SELECT  FROM_UNIXTIME(A.add_time, '%e') as day, count(A.user_id) as count FROM `" . $this -> _userTable . '` AS A INNER JOIN `' . $this -> _memberTable . "` AS B ON A.user_id=B.user_id " .  $whereSql;
        return $this -> _db -> fetchAll($sql);
    }

   /**
     * 取得推广点击信息
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
        $sql = 'SELECT  *  FROM `' . $this -> _userTable . '` AS A LEFT JOIN `' . $this -> _clicktable . '` AS B ON A.user_id=B.user_id ' . $whereSql . $limit;
        $countsql = 'SELECT count(*)  FROM `' . $this -> _userTable . '` AS A LEFT JOIN `' . $this -> _clicktable . '` AS B ON A.user_id=B.user_id ' . $whereSql;
        $content=$this -> _db -> fetchAll($sql);
        $total=$this -> _db -> fetchOne($countsql);
        return array('content' => $content, 'total' => $total);

    }

    /**
     * 取得分成记录信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
    public function getAffiliate($where = null, $page = null, $pageSize = null)
    {
        if ($page != null) {
            $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
        
            if ($page!=null) {
                $offset = ($page-1)*$pageSize;
                $limit = " ORDER BY add_time DESC LIMIT $pageSize OFFSET $offset";
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
        $sql = 'SELECT * FROM `' . $this -> _affiliateTable . '` ' . $whereSql . $limit;
        return $this -> _db -> fetchAll($sql);
    }
    
    /**
     * 取得分成记录数量
     *
     * @param    string   $where
     * @return   int
     */
    public function getAffiliateCount($where = null)
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
        $sql = 'SELECT COUNT(affiliate_id) FROM `' . $this -> _affiliateTable . '` ' . $whereSql;
        return $this -> _db -> fetchOne($sql);
    }
    
    /**
     * 取得成金额信息
     *
     * @param    string   $where
     * @return   array
     */
    public function getAffiliateMoney($where = null)
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
        $sql = 'SELECT SUM(order_price) AS all_order_price, SUM(affiliate_money) as all_affiliate_money, SUM(order_affiliate_amount) as all_order_affiliate_amount FROM `' . $this -> _affiliateTable . '` ' . $whereSql;
        return $this -> _db -> fetchRow($sql);
    }
    /**
     * 取得分成记录的详细商品信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
    public function getAffiliateInfo($where = null)
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

            $sql = 'SELECT ob.pay_name,ob.price_logistic,o.add_time as order_add_time,u.add_time as user_add_time, ua.order_status, ua.user_param, ua.user_name, ua.order_user_id, ua.order_user_name, ua.order_sn, ua.affiliate_money, ua.proportion,ua.code_param,
                           ua.order_price_goods, ob.status_logistic as order_status_logistic, ob.status_return as order_status_return,ua.separate_type,ob.pay_type,ob.is_send,ob.is_fav,ob.status_pay,
                           obg.product_id,obg.goods_id,obg.goods_name, obg.cat_name, obg.product_sn, obg.cat_id, (obg.number-obg.return_number) as number, obg.sale_price,obg.offers_type
                    FROM shop_order_batch_goods obg, shop_order_batch ob, shop_order o, shop_user u, shop_union_affiliate ua ' . 
                    $whereSql . ' AND obg.order_batch_id=ob.order_batch_id AND o.order_id=ob.order_id AND u.user_id=o.user_id AND ua.order_id=o.order_id
                    AND number > 0 ORDER BY ua.order_id';
            return $this -> _db -> fetchAll($sql);
        } else {
            return array();
        }
    }

    /**
     * new取得分成记录的详细商品信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
    public function getUnionOrderList($where = null)
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
            $table=" shop_union_affiliate as a inner join  shop_order_batch as o on   a.order_sn=o.order_sn";
            $sql ="SELECT  a.* ,o.pay_name,o.price_logistic,o.status,o.status_pay,o.price_goods FROM   $table  ".  $whereSql;
            return $this -> _db -> fetchAll($sql);
        } else {
            return array();
        }
    }
    /**
     * 取得支付历史数据
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
    public function getPay($where = null, $page = null, $pageSize = null)
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
        $sql = 'SELECT * FROM `' . $this -> _affiliatePayTable . '` ' . $whereSql . $limit;
        return $this -> _db -> fetchAll($sql);
    }
    
    /**
     * 取得订单明细
     *
     * @param    string    $orderSn
     * @return   array
     */
    public function getOrder($orderSn)
    {
        $sql = 'SELECT * FROM `' . $this -> _affiliateTable . "` WHERE order_sn = $orderSn";
        $order = $this -> _db -> fetchRow($sql);
        $sql = "SELECT * FROM `shop_order_batch_goods` WHERE order_sn = $orderSn";
        $order['goods'] = $this -> _db -> fetchAll($sql);
        return $order;
    }
    
    /**
     * 取得支付历史数量
     *
     * @param    string   $where
     * @return   int
     */
    public function getPayCount($where = null)
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
        $sql = 'SELECT COUNT(affiliate_pay_id) FROM `' . $this -> _affiliatePayTable . '` ' . $whereSql;
        return $this -> _db -> fetchOne($sql);
    }


	/**
     * 根据user_id获取联盟信息
     *
     * @param    int    $uid
     * @return   array
     */
	public function getUninfoType($uid)
	{
		$where = " where  user_id = '" . $uid . "'";
        $sql = 'SELECT un_type FROM `' . $this -> _pUnionTable . '` ' . $where;
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
     * 用户查看商品分成比例
     * 
     * @param int $uid
     * 
     * @return array
     */
    public function getGoodsProportion($where = null, $page = null, $pagesize = null, $orderBy =null ,$all = false) {
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
			from '.$this -> _unionGoods.' A left join '.$this -> _goods.' B on A.goods_id=B.goods_id left join '.$this -> _goodsCat.' C on A.cat_id=C.cat_id 
			where '.$whereSql;
		
		$sql = 'select A.id,A.union_id,A.proportion,B.goods_id,B.goods_name,B.price,B.onsale,C.cat_id,C.cat_name
			from '.$this -> _unionGoods.' A left join '.$this -> _goods.' B on A.goods_id=B.goods_id left join '.$this -> _goodsCat.' C on A.cat_id=C.cat_id 
			where '.$whereSql.$orderBy.$limit;
		$rs['total'] = $this -> _db -> fetchOne($sqlcount);
		$rs['data'] = $this -> _db -> fetchAll($sql);
		return $rs;
    }

}