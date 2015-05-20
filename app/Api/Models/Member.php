<?php
class Api_Models_Member
{
	protected $_db = null;

    private $_error = array();

	public function __construct(){
        $this -> _db = Zend_Registry::get('db');
	}


    /**
     * 获取会员列表
     *
     * @param    array
     * @param    int
     *
     * @return   array
     */
	 public function browse($params, $limit)
	 {	
		$_condition = $this->getBrowseCondition($params);

		$field = array(
			'member_id',
            'rank_id',
            'nick_name',
            'real_name',
            'sex',
            'email',
            'mobile',
            'home_phone',
            'money',
            'point',
            'experience',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_member`  WHERE ". implode(' AND ', $_condition) ." limit {$limit}";
        try {
		    $infos = $this->_db->fetchAll($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $infos;
	 }

	 /**
     * 获取订单总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getCount($params)
	 {	
		$_condition = $this->getBrowseCondition($params);

        if (false === $_condition) {
            return false;
        }

		$sql = "SELECT count(*) as count FROM `shop_member`  WHERE ". implode(' AND ', $_condition);
        try {
		    $count = $this->_db->fetchOne($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $count;
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
    public function getBrowseCondition($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        isset($params['crm_customer_id']) && $_condition[] = "crm_customer_id = '{$params['crm_customer_id']}'";
        isset($params['gt_mobile'])       && $_condition[] = "mobile != ''";

        if (empty($_condition)) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        return $_condition;
    }

    /**
     * 根据会员ID数组更新对应的CRM客户ID
     *
     * @param    array  
     *
     * @return   boolean
     */
    public function updateMemberInfosByMemberIds($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        foreach ($params['customer_ids'] as $param) {

            if (empty($param['origin_member_id'])) {
                continue;
            }
            $set = array(
                'crm_customer_id' => $param['crm_customer_id'],    
            );

            $this->_db->update('shop_member', $set, "member_id = '{$param['origin_member_id']}'");
        }

        return true;
    }

    /**
     * 获取登录列表
     *
     * @param    array
     * @param    int
     *
     * @return   array
     */
	 public function browseLogin($params, $limit)
	 {	
		list($_condition, $_join) = $this->getBrowseLoginCondition($params);

		$field = array(
            'log_id',
			'crm_customer_id',
            'login_ip',
            'login_time',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_member_login_log` l ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." limit {$limit}";

		return $this->_db->fetchAll($sql);
	 }

	 /**
     * 获取登录总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getLoginCount($params)
	 {	
		list($_condition, $_join) = $this->getBrowseLoginCondition($params);

        if (false === $_condition) {
            return false;
        }

		$sql = "SELECT count(*) as count FROM `shop_member_login_log` l ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);

		return $this->_db->fetchOne($sql);
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
    public function getBrowseLoginCondition($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        isset($params['sync_status']) && $_condition[] = "sync_status = '{$params['sync_status']}'";

        $_condition[] = "m.crm_customer_id > 0";
        if (count($_condition) < 1) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        $_join[] = "LEFT JOIN `shop_member` m ON l.member_id = m.member_id";

        return array($_condition, $_join);
    }

    /**
     * 根据登陆日志ID数组更新对应数据同步状态
     *
     * @param    array  
     *
     * @return   boolean
     */
    public function updateMemberLoginInfosByLogIds($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());                   
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        foreach ($params['log_ids'] as $param) {

            if (empty($param['log_id'])) {
                continue;
            }
            $set = array(
                'sync_status' => 1,    
            );

            $this->_db->update('shop_member_login_log', $set, "log_id = '{$param['log_id']}'");
        }

        return true;
    }

    /**
     * 获取收藏列表
     *
     * @param    array
     * @param    int
     *
     * @return   array
     */
	 public function browseFavorite($params, $limit)
	 {	
		list($_condition, $_join) = $this->_getBrowseFavoriteCondition($params);

		$field = array(
            'favorite_id',
			'p.product_id',
            'f.add_time',
            'm.crm_customer_id',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_member_favorite` f ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." limit {$limit}";

        try {
		    $infos = $this->_db->fetchAll($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $infos;
	 }

	 /**
     * 获取收藏总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getFavoriteCount($params)
	 {	
		list($_condition, $_join) = $this->_getBrowseFavoriteCondition($params);

        if (false === $_condition) {
            return false;
        }

		$sql = "SELECT count(*) as count FROM `shop_member_favorite` f ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);
        try {
		    $count = $this->_db->fetchOne($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $count;
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
    private function _getBrowseFavoriteCondition($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        isset($params['sync_status']) && $_condition[] = "f.sync_status = '{$params['sync_status']}'";

        $_condition[] = "m.crm_customer_id > 0";
        if (count($_condition) < 1) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        $_join[] = "LEFT JOIN `shop_member`   m ON f.member_id = m.member_id";
        $_join[] = "LEFT JOIN `shop_goods`    g ON f.goods_id  = g.goods_id";
        $_join[] = "LEFT JOIN `shop_product`  p ON g.product_id  = p.product_id";

        return array($_condition, $_join);
    }

    /**
     * 根据收藏ID数组更新对应数据同步状态
     *
     * @param    array  
     *
     * @return   boolean
     */
    public function updateMemberFavoriteInfosByFavoriteIds($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());                   
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        foreach ($params['favorite_ids'] as $param) {

            if (empty($param['favorite_id'])) {
                continue;
            }
            $set = array(
                'sync_status' => 1,    
            );

            $this->_db->update('shop_member_favorite', $set, "favorite_id = '{$param['favorite_id']}'");
        }

        return true;
    }

    /**
     * 获取积分列表
     *
     * @param    array
     * @param    int
     *
     * @return   array
     */
	 public function browsePoint($params, $limit)
	 {	
		list($_condition, $_join) = $this->_getBrowsePointCondition($params);

		$field = array(
            'point_id',
			'm.crm_customer_id',
            'point_total',
            'p.point',
            'batch_sn',
            'note',
            'add_time',
            'admin_name',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_member_point` p ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." limit {$limit}";

        try {
		    $infos = $this->_db->fetchAll($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $infos;
	 }

	 /**
     * 获取积分总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getPointCount($params)
	 {	
		list($_condition, $_join) = $this->_getBrowsePointCondition($params);

        if (false === $_condition) {
            return false;
        }

		$sql = "SELECT count(*) as count FROM `shop_member_point` p ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);
        try {
		    $count = $this->_db->fetchOne($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $count;
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
    private function _getBrowsePointCondition($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        isset($params['sync_status']) && $_condition[] = "p.sync_status = '{$params['sync_status']}'";

        $_condition[] = "m.crm_customer_id > 0";
        if (count($_condition) < 1) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        $_join[] = "LEFT JOIN `shop_member`   m ON p.member_id = m.member_id";

        return array($_condition, $_join);
    }

    /**
     * 根据积分ID数组更新对应数据同步状态
     *
     * @param    array  
     *
     * @return   boolean
     */
    public function updateMemberPointInfosByPointIds($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());                   
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        foreach ($params['point_ids'] as $param) {

            if (empty($param['point_id'])) {
                continue;
            }
            $set = array(
                'sync_status' => 1,    
            );

            $this->_db->update('shop_member_point', $set, "point_id = '{$param['point_id']}'");
        }

        return true;
    }

    /**
     * 获取礼品卡列表
     *
     * @param    array
     * @param    int
     *
     * @return   array
     */
	 public function browseGift($params, $limit)
	 {	
		list($_condition, $_join) = $this->_getBrowseGiftCondition($params);

		$field = array(
            'card_id',
			'card_type',
            'card_price',
            'card_real_price',
            'card_sn',
            'card_pwd',
            'order_batch_goods_id',
            'add_time',
            'buyer_id',
            'end_date',
            'crm_customer_id',
            'using_time',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_gift_card` g ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." limit {$limit}";

        try {
		    $infos = $this->_db->fetchAll($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $infos;
	 }

	 /**
     * 获取礼品卡总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getGiftCount($params)
	 {	
		list($_condition, $_join) = $this->_getBrowseGiftCondition($params);

        if (false === $_condition) {
            return false;
        }

		$sql = "SELECT count(*) as count FROM `shop_gift_card` g ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);
        try {
		    $count = $this->_db->fetchOne($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $count;
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
    private function _getBrowseGiftCondition($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        isset($params['sync_status']) && $_condition[] = "g.sync_status = '{$params['sync_status']}'";

        $_condition[] = "m.crm_customer_id > 0";
        if (count($_condition) < 1) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        $_join[] = "LEFT JOIN `shop_member`   m ON m.user_id = g.buyer_id";

        return array($_condition, $_join);
    }

    /**
     * 根据礼品卡ID数组更新对应数据同步状态
     *
     * @param    array  
     *
     * @return   boolean
     */
    public function updateGiftInfosByCardIds($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());                   
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        foreach ($params['card_ids'] as $param) {

            if (empty($param['card_id'])) {
                continue;
            }
            $set = array(
                'sync_status' => 1,    
            );

            $this->_db->update('shop_gift_card', $set, "card_id = '{$param['card_id']}'");
        }

        return true;
    }

    /**
     * 获取会员等级记录列表
     *
     * @param    array
     * @param    int
     *
     * @return   array
     */
	 public function browseMemberRank($params, $limit)
	 {	
		list($_condition, $_join) = $this->_getBrowseMemberRankCondition($params);

		$field = array(
            'rank_log_id',
			'm.crm_customer_id',
            'last_rank_id',
            'l.rank_id',
            'remark',
            'created_ts',
		);
		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_member_rank_log` l ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition) ." limit {$limit}";

        try {
		    $infos = $this->_db->fetchAll($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $infos;
	 }

	 /**
     * 获取会员等级记录总数
     *
     * @param    array
     *
     * @return   int
     */
	 public function getMemberRankCount($params)
	 {	
		list($_condition, $_join) = $this->_getBrowseMemberRankCondition($params);

        if (false === $_condition) {
            return false;
        }

		$sql = "SELECT count(*) as count FROM `shop_member_rank_log` l ". implode(' ', $_join) ." WHERE ". implode(' AND ', $_condition);
        try {
		    $count = $this->_db->fetchOne($sql);
        } catch (Exception $e) {
            $this->_error['error'] = $e->getMessage();
            return false;
        }

        return $count;
	 }

	 /**
     * 处理列表条件
     *
     * @param    array  
     *
     * @return   array
     */
    private function _getBrowseMemberRankCondition($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

        isset($params['sync_status']) && $_condition[] = "l.sync_status = '{$params['sync_status']}'";

        $_condition[] = "m.crm_customer_id > 0";
        if (count($_condition) < 1) {
            $this->_error['error'] = '缺少查询的参数';
            return false;
        }

        $_join[] = "LEFT JOIN `shop_member`   m ON m.member_id = l.member_id";

        return array($_condition, $_join);
    }

    /**
     * 根据礼品卡ID数组更新对应数据同步状态
     *
     * @param    array  
     *
     * @return   boolean
     */
    public function updateMemberRankInfosByLogIds($params)
    {
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());                   
        $params = Custom_Model_Filter::filterArray($params, $filterChain);
        foreach ($params['rank_log_ids'] as $param) {

            if (empty($param['rank_log_id'])) {
                continue;
            }
            $set = array(
                'sync_status' => 1,    
            );

            $this->_db->update('shop_member_rank_log', $set, "rank_log_id = '{$param['rank_log_id']}'");
        }

        return true;
    }

    public function getError()
    {
        return $this->_error;
    }
	
}