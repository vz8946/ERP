<?php
class Admin_Models_API_Shop {
	private $_db;
	private $_auth;
	private $_pageSize = 20;
	private $_orderAPI;
	private $_promotionList;
	
	private $_table = 'shop_shop';
	private $_table_goods = 'shop_goods_shop';
	private $_table_order = 'shop_order_shop';
	private $_table_order_goods = 'shop_order_shop_goods';
	private $_table_sync_log = 'shop_shop_sync_log';
	private $_table_area = 'shop_area';
	private $_table_main_order = 'shop_order';
	private $_table_order_batch = 'shop_order_batch';
	private $_table_order_batch_goods = 'shop_order_batch_goods'; 
    private $_table_order_batch_adjust = 'shop_order_batch_adjust';
	private $_table_product = 'shop_product';
	private $_table_promotion = 'shop_shop_promotion';
	private $_table_transport = 'shop_transport';
	private $_table_group_goods = 'shop_group_goods';
	private $_table_order_logistics = 'shop_order_shop_logistics';
	
	public $_error;
	public $_log;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		set_time_limit(0);
		
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		
		$this -> _db = Zend_Registry::get('db');
		$this -> _db -> execute('SET SESSION wait_timeout=1200');
	}
	
    /**
     * 限价操作人名单
     *
     */

    private $_allowJudgeList = array ('panshangqing');

	/**
     * 添加或修改店铺
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function edit($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
		if ( $id ) {
		    $temp = $this -> get("shop_id <> {$id} and shop_name = '{$data['shop_name']}'");
		}
		else {
		    $temp = $this -> get("shop_name = '{$data['shop_name']}'");
		}
		if ( $temp['total'] > 0 ) {
		    $this -> error = 'same_name';
			return false;
		}
		
		$shopAPI = Custom_Model_Shop_Base::getInstance($data['shop_type']);
		$configField = $shopAPI -> getConfigField();
		if ( $configField && count($configField) > 0 ) {
		    foreach ( $configField as $key => $name ) {
		        $config[$key] = $data['config'][$key];
		    }
		}
		$config = serialize($config);
		
		$row = array('shop_name' => $data['shop_name'],
		             'shop_type' => $data['shop_type'],
		             'company' => $data['company'],
		             'commission_type' => $data['commission_type'],
		             'commission_rate' => $data['commission_rate'],
		             'shop_url' => $data['shop_url'],
		             'sync_order_interval' => $data['sync_order_interval'],
		             'status' => $data['status'],
		             'config' => $config,
		            );
		
		if ($id === null) {
		    $row['add_time'] = time();
		    $this -> _db -> insert($this -> _table, $row);
		} else {
		    $this -> _db -> update($this -> _table, $row, "shop_id = {$id}");
		}
		
		return true;
	}
	
	/**
     * 获取店铺数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    $where['shop_id'] && $wheresql .= "shop_id = '{$where['shop_id']}' and ";
		    $where['shop_name'] && $wheresql .= "shop_name like '%{$where['shop_name']}%' and ";
		    $where['shop_type'] && $wheresql .= "shop_type = '{$where['shop_type']}' and ";
		    $where['commission_type'] && $wheresql .= "commission_type = '{$where['commission_type']}' and ";
		    $where['status'] !== null && $wheresql .= "status = '{$where['status']}' and ";
		    $wheresql .= '1';
		}
		else    $wheresql = $where;
		
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($wheresql != null) {
			$whereSql = "WHERE $wheresql";
		}
		
		if ($orderBy != null) {
			$orderBy = "ORDER BY $orderBy";
		}
		else {
			$orderBy = "ORDER BY shop_id";
		}
		
		return array('list' => $this -> _db -> fetchAll("SELECT $fields FROM {$this->_table} $whereSql $orderBy $limit"),
		             'total' => $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this->_table} $whereSql"));
	}
	
	/**
     * 获取商品数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getGoods($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    $where['shop_id'] && $wheresql .= "t1.shop_id = '{$where['shop_id']}' and ";
		    $where['goods_sn'] && $wheresql .= "t1.goods_sn like '%{$where['goods_sn']}%' and ";
		    $where['shop_goods_name'] && $wheresql .= "t1.shop_goods_name like '%{$where['shop_goods_name']}%' and ";
		    $where['shop_sku_id'] && $wheresql .= "t1.shop_sku_id = '{$where['shop_sku_id']}' and ";
		    if ( $where['onsale'] !== null && $where['onsale'] !== '' ) {
		        $wheresql .= "t1.onsale = '{$where['onsale']}' and ";
		    }

			$where['price_limit'] && $wheresql .= "t1.shop_price < p.price_limit and ";
		    $wheresql .= '1';
		}
		else    $wheresql = $where;
		
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($wheresql != null) {
			$whereSql = "WHERE $wheresql";
		}
		
		if ($orderBy != null) {
			$orderBy = "ORDER BY $orderBy";
		}
		else {
			$orderBy = "ORDER BY t1.id";
		}
		

		$sql = "SELECT $fields FROM {$this->_table_goods} as t1 left join {$this->_table} as t2 on t1.shop_id = t2.shop_id LEFT JOIN `shop_product` p ON t1.goods_sn = p.product_sn $whereSql $orderBy $limit";

		return array('list' => $this -> _db -> fetchAll($sql),
		             'total' => $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this->_table_goods} as t1 left join {$this->_table} as t2 on t1.shop_id = t2.shop_id LEFT JOIN `shop_product` p ON t1.goods_sn = p.product_sn $whereSql"));
	}

	/**
     * 获取商品数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getExportGoods($where = null, $fields = '*')
	{
		if ( is_array($where) ) {
		    $where['shop_id'] && $wheresql .= "t1.shop_id = '{$where['shop_id']}' and ";
		    $where['goods_sn'] && $wheresql .= "t1.goods_sn like '%{$where['goods_sn']}%' and ";
		    $where['shop_goods_name'] && $wheresql .= "t1.shop_goods_name like '%{$where['shop_goods_name']}%' and ";
		    $where['shop_sku_id'] && $wheresql .= "t1.shop_sku_id = '{$where['shop_sku_id']}' and ";
		    if ( $where['onsale'] !== null && $where['onsale'] !== '' ) {
		        $wheresql .= "t1.onsale = '{$where['onsale']}' and ";
		    }
		    $wheresql .= '1';
		}
		else    $wheresql = $where;

		if ($wheresql != null) {
			$whereSql = "WHERE $wheresql";
		}
		
	    $orderBy = "ORDER BY t1.id";
		return $this -> _db -> fetchAll("SELECT $fields FROM {$this->_table_goods} as t1 left join {$this->_table} as t2 on t1.shop_id = t2.shop_id $whereSql $orderBy ");
	}

	/**
     * 获得订单列表
     *
     * @return   array
     */
    public function getOrder($search, $page = null, $pageSize = null)
    {
        $pageSize = $pageSize ? $pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
        
        if ( $search['shop_id'] ) {
            $whereSql .= " and t1.shop_id = '{$search['shop_id']}'";
        }
        if ( $search['shop_order_id'] ) {
            $whereSql .= " and t1.shop_order_id = '{$search['shop_order_id']}'";
        }
        if ( $search['shop_order_ids'] ) {
            $whereSql .= " and t1.shop_order_id in (".implode(',', $search['shop_order_ids']).")";
        }
        if ( $search['status'] ) {
            if (is_array($search['status'])) {
                $whereSql .= " and t1.status in (".implode(',', $search['status']).")";
            }
            else {
                $whereSql .= " and t1.status = '{$search['status']}'";
            }
        }
        if ( $search['external_order_sn'] ) {
            $whereSql .= " and t1.external_order_sn = '{$search['external_order_sn']}'";
        }
        if ( $search['external_order_sns'] ) {
            $whereSql .= " and t1.external_order_sn in ({$search['external_order_sns']})";
        }
        if ( $search['addr_consignee'] ) {
            $whereSql .= " and t1.addr_consignee like '%{$search['addr_consignee']}%'";
        }
        if ( $search['addr_mobile'] ) {
            $whereSql .= " and t1.addr_mobile like '%{$search['addr_mobile']}%'";
        }
        if ( $search['logistic_code'] ) {
            $whereSql .= " and t1.logistic_code = '{$search['logistic_code']}'";
        }
        if ( $search['logistic_no'] ) {
            $whereSql .= " and t1.logistic_no = '{$search['logistic_no']}'";
        }
        if ( $search['lock_admin_name'] ) {
            $whereSql .= " and t1.lock_admin_name = '{$search['lock_admin_name']}'";
        }
        if ( $search['memo'] ) {
            $whereSql .= " and t1.memo like '%{$search['memo']}%'";
        }
        if ( $search['admin_memo'] ) {
            if ($search['admin_memo'] == 1) {
                $whereSql .= " and t1.admin_memo <> '' and t1.admin_memo is not null";
            }
            else if ($search['admin_memo'] == 2) {
                $whereSql .= " and (t1.admin_memo = '' or t1.admin_memo is null)";
            }
        }
        if ( $search['lock'] ) {
            $_auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
            $whereSql .= " and t1.lock_admin_name = '{$_auth['admin_name']}'";
        }
        if ( $search['fromdate'] ) {
            $orderTime = strtotime("{$search['fromdate']} 00:00:00");
            $whereSql .= " and t1.order_time >= {$orderTime}";
        }
        if ( $search['todate'] ) {
            $orderTime = strtotime("{$search['todate']} 23:59:59");
            $whereSql .= " and t1.order_time <= {$orderTime}";
        }
        if ( $search['sync_fromdate'] ) {
            $syncTime = strtotime("{$search['sync_fromdate']} 00:00:00");
            $whereSql .= " and t1.sync_time >= {$syncTime}";
        }
        if ( $search['sync_todate'] ) {
            $syncTime = strtotime("{$search['sync_todate']} 23:59:59");
            $whereSql .= " and t1.sync_time <= {$syncTime}";
        }
        if ( ($search['status_business'] !== null) && ($search['status_business'] !== '') ) {
            $whereSql .= " and t1.status_business = '{$search['status_business']}'";
        }
        if ( ($search['sync'] !== null) && ($search['sync'] !== '') ) {
            $whereSql .= " and t1.sync = '{$search['sync']}'";
        }
        if ( ($search['is_fake'] !== null) && ($search['is_fake'] !== '') ) {
            if ($search['is_fake'] == 1) {
                $whereSql .= " and t1.is_fake in (1,2)";
            }
            else    $whereSql .= " and t1.is_fake = '{$search['is_fake']}'";
        }
        if ( ($search['fake_type'] !== null) && ($search['fake_type'] !== '') ) {
            $whereSql .= " and t1.fake_type = '{$search['fake_type']}'";
        }
        if ( ($search['is_cod'] !== null) && ($search['is_cod'] !== '') ) {
            $whereSql .= " and t1.is_cod = '{$search['is_cod']}'";
        }
        if ( ($search['is_settle'] !== null) && ($search['is_settle'] !== '') ) {
            $whereSql .= " and t1.is_settle = '{$search['is_settle']}'";
        }
        if ( ($search['write_off'] !== null) && ($search['write_off'] !== '') ) {
            $whereSql .= " and t1.is_fake = '{$search['write_off']}'";
        }
        if ( ($search['other_logistics'] !== null) && ($search['other_logistics'] !== '') ) {
            $whereSql .= " and t1.other_logistics = '{$search['other_logistics']}'";
        }
        if ( $search['check_address'] == 1) {
            $whereSql .= " and (t1.addr_province_id <> 0 and t1.addr_city_id <> 0 and t1.addr_area_id <> 0)";
        }
        if ( $search['check_address'] == 2) {
            $whereSql .= " and (t1.addr_province_id = 0 or t1.addr_city_id = 0 or t1.addr_area_id = 0)";
        }
        if ( ($search['invoice'] !== null) && ($search['invoice'] !== '') ) {
            if ( $search['invoice'] == 0 ) {
                 $whereSql .= " and (t1.invoice = '' or t1.invoice is null)";
            }
            else if ( $search['invoice'] == 1 ) {
                 $whereSql .= " and (t1.invoice like '%个人%')";
            }
            else if ( $search['invoice'] == 2 ) {
                 $whereSql .= " and (t1.invoice <> '' and t1.invoice is not null and t1.invoice not like '%个人%')";
            }
            else if ( $search['invoice'] == 3 ) {
                 $whereSql .= " and (t1.invoice <> '' or t1.invoice is not null)";
            }
        }
        if ( ($search['done_invoice'] !== null) && ($search['done_invoice'] !== '') ) {
            $whereSql .= " and t1.done_invoice = '{$search['done_invoice']}'";
        }
        if ($search['province']){
            if ( is_array($search['province']) ) {
                $search['province'][] = 0;
                $whereSql .= " and t1.addr_province_id in (".implode(',', $search['province']).")";
            }
            else    $whereSql .= " and t1.addr_province_id = {$search['province']}";
        }
		if (!empty($search['audit_status'])) {
			$whereSql .=" AND t1.audit_status = '{$search['audit_status']}'";
		}
        
        if ($search['goods_sn']){
            $search['goods_number'] = $search['goods_number'] ? $search['goods_number'] : 1;
            $whereSql .= " and t3.goods_sn = '{$search['goods_sn']}' and t3.number >= {$search['goods_number']}";
            
            $result['total'] = $this -> _db -> fetchRow("select count(*) as count,sum(amount / count) as amount from (select count(*) as count,sum(t1.amount) as amount from {$this -> _table_order_goods} as t3 left join {$this -> _table_order} as t1 on t1.shop_order_id = t3.shop_order_id left join {$this -> _table} as t2 on t1.shop_id = t2.shop_id where 1 {$whereSql} group by t1.shop_order_id) as a");
            $result['total']['amount'] = round($result['total']['amount'], 2);
            $result['list'] = $this -> _db -> fetchAll("select t1.*,t2.shop_name,t2.shop_type from {$this -> _table_order_goods} as t3 left join {$this -> _table_order} as t1 on t1.shop_order_id = t3.shop_order_id left join {$this -> _table} as t2 on t1.shop_id = t2.shop_id where 1 {$whereSql} group by t1.shop_order_id order by order_time desc {$limit}");
        }
        else {
            $result['total'] = $this -> _db -> fetchRow("select count(*) as count,sum(t1.amount) as amount from {$this -> _table_order} as t1 left join {$this -> _table} as t2 on t1.shop_id = t2.shop_id where 1 {$whereSql}");
            $result['list'] = $this -> _db -> fetchAll("select t1.*,t2.shop_name,t2.shop_type from {$this -> _table_order} as t1 left join {$this -> _table} as t2 on t1.shop_id = t2.shop_id where 1 {$whereSql} order by order_time desc {$limit}");
        }

        if ( $result['list'] ) {
            $logisticColor = $this -> getLogisticColor();
            foreach ( $result['list'] as $index => $order ) {
                $order_id_array[] = $order['shop_order_id'];
                if ($order['shop_type'] == 'yihaodian') {
                    if ($order['invoice'] != '') {
                        $tempArr = explode(' ', $order['invoice']);
                        $result['list'][$index]['invoice_title'] = $tempArr[0];
                    }
                }
                else {
                    $result['list'][$index]['invoice_title'] = $order['invoice'];
                }
                if ($order['logistic_code']) {
                    $result['list'][$index]['logistic_code_color'] = $logisticColor[$order['logistic_code']];
                }
            }

            $datas = $this -> _db -> fetchAll("select * from {$this -> _table_order_goods} where shop_order_id in (".implode(',', $order_id_array).")");
            foreach ( $datas as $goods ) {
                $orderGoodsInfo[$goods['shop_order_id']][] = $goods;
            }
            foreach ( $result['list'] as $key => $order ) {
                $result['list'][$key]['goods'] = $orderGoodsInfo[$order['shop_order_id']];
                if ($order['memo'] == '　')  $result['list'][$key]['memo'] = '';
            }
        }
        
        return $result;
    }
    
    /**
     * 获取活动数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getPromotion($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    $where['shop_id'] && $wheresql .= "t1.shop_id = '{$where['shop_id']}' and ";
		    $where['promotion_name'] && $wheresql .= "t1.promotion_name like '%{$where['promotion_name']}%' and ";
		    $where['type'] && $wheresql .= "t1.type like '%{$where['type']}%' and ";
		    if ( is_array($where['search_time_type']) ) {
		        $orsql = array();
		        $currentTime = time();
		        for ( $i = 0; $i < count($where['search_time_type']); $i++ ) {
		            if ( $where['search_time_type'][$i] == '0' ) {
		                $orsql[] = "(t1.start_time <= '{$currentTime}' and end_time > '{$currentTime}')";
		            }
		            if ( $where['search_time_type'][$i] == '1' ) {
		                $orsql[] = "t1.start_time > '{$currentTime}'";
		            }
		            if ( $where['search_time_type'][$i] == '2' ) {
		                $orsql[] = "end_time <= '{$currentTime}'";
		            }
		        }
		        $orsql = implode( ' or ', $orsql );
		        $wheresql .= '('.$orsql.') and ';
		    }
		    $wheresql .= '1';
		}
		else    $wheresql = $where;
        
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($wheresql != null) {
			$whereSql = "WHERE $wheresql";
		}
		
		if ($orderBy != null) {
			$orderBy = "ORDER BY $orderBy";
		}
		else {
			$orderBy = "ORDER BY t1.promotion_id";
		}
		
		return array('list' => $this -> _db -> fetchAll("SELECT $fields FROM {$this->_table_promotion} as t1 left join {$this->_table} as t2 on t1.shop_id = t2.shop_id $whereSql $orderBy $limit"),
		             'total' => $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this->_table_promotion} as t1 left join {$this->_table} as t2 on t1.shop_id = t2.shop_id $whereSql"));
	}
	
	/**
     * 错误集合
     *
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error' => '操作失败!',
			         'same_name' => '店铺名称不能重复!',
			         'no_priviledge' => '没有权限!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	
	/**
     * 获取店铺状态信息
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxStatus($url, $id, $status)
	{
		switch($status){
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
    }
    
    /**
     * ajax更新店铺
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$field = $filterChain -> filter($field);
		$val = $filterChain -> filter($val);
		
		if ( (int)$id > 0 ) {
		    $this -> _db -> update($this -> _table, array($field => $val), "shop_id = {$id}");
		}
	}
	
	/**
     * 获取同步日志
     *
     * @param    string    $where
     * @return   array
     */
	public function getSyncLog($where, $page = null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    $where['shop_id'] && $wheresql .= "t1.shop_id = '{$where['shop_id']}' and ";
		    $where['shop_name'] && $wheresql .= "t2.shop_name like '%{$where['shop_name']}%' and ";
		    $where['shop_type'] && $wheresql .= "t2.shop_type = '{$where['shop_type']}' and ";
		    $where['action_name'] && $wheresql .= "t1.action_name = '{$where['action_name']}' and ";
		    $where['fromdate'] && $wheresql .= "t1.start_time >= ".strtotime($where['fromdate'])." and ";
		    $where['todate'] && $wheresql .= "t1.start_time <= ".strtotime($where['todate'].' 23:59:59')." and ";
		    $wheresql .= '1';
		}
		else    $wheresql = $where;
		
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($wheresql != null) {
			$whereSql = "WHERE $wheresql";
		}
        
		return array('list' => $this -> _db -> fetchAll("SELECT t1.*,t2.shop_name FROM {$this -> _table_sync_log} as t1 left join {$this->_table} as t2 on t1.shop_id = t2.shop_id $whereSql order by t1.id desc $limit"),
		             'total' => $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this -> _table_sync_log} as t1 left join {$this->_table} as t2 on t1.shop_id = t2.shop_id $whereSql"));
	}
	
	/**
     * ajax更新订单
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxOrderUpdate($id, $field, $val)
	{
		if ( $id > 0 ) {
		    if ( $field == 'is_fake' ) {
		        if ( $val == 'true')        $set['is_fake'] = 1;
		        else if ( $val == 'false')  $set['is_fake'] = 0;
		        $set['fake_admin_name'] = $this -> _auth['admin_name'].' '.date('Y-m-d H:i:s');
		        $this -> _db -> update($this -> _table_order, $set, "shop_order_id = {$id}");
		    }
		    else if ( $field == 'memo' ) {
		        $row = $this -> _db -> fetchRow("select memo from {$this -> _table_order} where shop_order_id = {$id}");
		        if (trim($row['memo']) == trim($val))   return 'same';
		        $set['memo'] = $val;
		        $this -> _db -> update($this -> _table_order, $set, "shop_order_id = {$id}");
		        $this -> addOrderHistory(array('id' => $id, 'history' => "更新备注"));
		    }
		    else    exit('failure');
		}
	}
	
	/**
     * 新增或修改商品记录
     *
     * @return  void
     */
    public function updateGoods($goods) {
        if ( $goods['shop_sku_id'] ) {
            $where = "shop_sku_id = '{$goods['shop_sku_id']}'";
        }
        else {
            $where = "shop_goods_id = '{$goods['shop_goods_id']}'";
        }
        
        $row = $this -> _db -> fetchRow("select 1 from {$this -> _table_goods} where shop_id = {$goods['shop_id']} and {$where}");
        if ( $row ) {
            $goods['update_time'] = time();
            $this -> _db -> update($this -> _table_goods, $goods, "shop_id = {$goods['shop_id']} and {$where}");
            $this -> _log .= "更新商品：{$goods['shop_goods_name']}\n";
        }
        else {
            $goods['add_time'] = time();
            $goods['update_time'] = $goods['add_time'];
            $this -> _db -> insert($this -> _table_goods, $goods);
            $this -> _log .= "新增商品：{$goods['shop_goods_name']}\n";
        }
    }
    
    /**
     * 删除商品记录
     *
     * @return  void
     */
    public function deleteGoods($where) {
        $this -> _db -> delete($this -> _table_goods, $where);
    }
    
    /**
     * 新增或修改订单记录
     *
     * @return  void
     */
    public function updateOrder($order) {
        $orderGoods = $order['goods'];
        unset($order['goods']);
        
        if ($order['status'] == 1)  return false;
        
        $row = $this -> _db -> fetchRow("select shop_order_id,status,status_business,sync,amount,discount_amount,goods_amount,freight,pay_amount,pay_time,logistic_code,logistic_no,logistic_time,memo,is_cod,invoice,validate_sn from {$this -> _table_order} where shop_id = {$order['shop_id']} and external_order_sn = '{$order['external_order_sn']}'");
        if ($row) {
            if ( ($row['status'] == 10) && ($order['status'] == 10) )   return true;
            if ( ($row['status'] == 10) && ($order['status'] == 3) )     return true;
            if ( ($row['status'] == 10) && ($order['status'] == 2) )     return true;
            if ( ($row['status'] == 11) && ($order['status'] == 11) )   return true;
            if ( ($row['status'] == 3) && ($order['status'] == 2) )     return true;
            
            $shop_order_id = $row['shop_order_id'];
            
            //店铺订单发生取消，没有同步到官网的未发货单需要释放占用库存
            if ($order['status'] == 11 && in_array($row['status'], array(2,12)) && !$row['sync']) {
                if (in_array($row['status_business'], array(1,2,4))) {
                    $datas = $this -> getOrder(array('shop_order_id' => $shop_order_id));
                    $this -> releaseProductStock($datas['list'][0], true);
                    if (!$this -> _transportAPI) {
                        $this -> _transportAPI = new Admin_Models_API_Transport();
                    }
                    $this -> _transportAPI -> deleteValidateSn($datas['list'][0]['validate_sn']);
                }
                else if (in_array($row['status_business'], array(0,9))) {
                    if (!$this -> _replenishmentAPI) {
                        $this -> _replenishmentAPI = new Admin_Models_API_Replenishment();
                    }
                    $this -> _replenishmentAPI -> cancelOrderReplenish($row['shop_order_id']);
                }
            }
            
            $this -> autoSetFake($order);
            
            $set = array('status' => $order['status']);
            if (!$row['sync']) {
                $set['amount'] = $order['amount'];
                $set['discount_amount'] = $order['discount_amount'];
                $set['goods_amount'] = $order['goods_amount'];
                $set['freight'] = $order['freight'];
                $set['is_cod'] = $order['is_cod'] ? $order['is_cod'] : 0;
            }
            if ($row['status'] == 1 && in_array($order['status'], array(2,3,10))) {
                $set['pay_time'] = $order['pay_time'];
                $set['pay_amount'] = $order['pay_amount'] ? $order['pay_amount'] : '0.00';
            }
            if ($order['status'] == 3 || $order['status'] == 10) {
                if ($order['logistic_code'])    $set['logistic_code'] = $order['logistic_code'];
                if ($order['logistic_no'])      $set['logistic_no'] = $order['logistic_no'];
                if ($order['logistic_time'])    $set['logistic_time'] = $order['logistic_time'];
            }
            if (isset($order['memo']))      $set['memo'] = $order['memo'];
            $needUpdate = false;
            foreach ( $set as $key => $value ) {
                if ( $set[$key] != $row[$key] ) {
                    $needUpdate = true;
                    break;
                }
            }
            
            if ($needUpdate) {
                if (isset($order['memo']) && $order['memo'] != $row['memo']) {
                    $this -> addOrderHistory(array('id' => $row['shop_order_id'], 'history' => "店铺备注发生变化 {$row['memo']} -> {$order['memo']}"));
                }
                
                $set['update_time'] = time();
                $this -> _db -> update($this -> _table_order, $set, "shop_id = {$order['shop_id']} and external_order_sn = '{$order['external_order_sn']}'");
                /*
                foreach ( $orderGoods as $goods ) {
                    if (!$goods['discount_price'])  $goods['discount_price'] = 0;
                    $goods['goods_sn'] = trim($goods['goods_sn']);
                    $this -> _db -> update($this -> _table_order_goods, $goods, "shop_order_id = {$shop_order_id} and goods_sn = '{$goods['goods_sn']}' and number = {$goods['number']} and price > 0");
                }
                */
                $this -> _log .= "更新订单：{$order['external_order_sn']}\n";
            }
        }
        else {
            $order['add_time'] = time();
            $order['update_time'] = $order['add_time'];
            $order['addr_address'] = str_replace("'", '', $order['addr_address']);
            
            $this -> autoSetFake($order);
            $this -> autoSetInternal($order);
            
            $this -> _db -> insert($this -> _table_order, $order);
            $this -> _log .= "新增订单：{$order['external_order_sn']}\n";
            $shop_order_id = $this -> _db -> lastInsertId();
            
            foreach ( $orderGoods as $index => $goods ) {
                $orderGoods[$index]['goods_sn'] = trim($goods['goods_sn']);
            }
            $orderGoods = $this -> orderPromotion($order, $orderGoods);
            
            foreach ( $orderGoods as $goods ) {
                $goods['shop_order_id'] = $shop_order_id;
                $goods['shop_id'] = $order['shop_id'];
                $goods['add_time'] = time();
                $goods['update_time'] = $goods['add_time'];
                if (!$goods['discount_price'])  $goods['discount_price'] = 0;
                $goods['goods_sn'] = trim($goods['goods_sn']);
                $this -> _db -> insert($this -> _table_order_goods, $goods);
            }
        }
    }
    
    /**
     * 更新店铺订单物流信息
     *
     * @return  void
     */
    public function updateOrderLogistic($order) {
        $set = array('logistic_code' => $order['logistic_code'],
                     'logistic_no' => $order['logistic_no'],
                     'logistic_time' => $order['logistic_time'],
                     'status' => $order['status'],
                     'status_business' => 2,
                     'send_admin_name' => $this -> _auth['admin_name'].' '.date('Y-m-d H:i:s'),
                     'update_time' => time(),
                    );
        $this -> _db -> update($this -> _table_order, $set, "shop_id = {$order['shop_id']} and external_order_sn = '{$order['external_order_sn']}'");
    }
    
    /**
     * 更新店铺订单物流单号
     *
     * @return  void
     */
    public function updateOrderLogisticNo($order) {
        $set = array('logistic_no' => $order['logistic_no'],
                     'update_time' => time(),
                    );
        $this -> _db -> update($this -> _table_order, $set, "shop_id = {$order['shop_id']} and external_order_sn = '{$order['external_order_sn']}'");
    }
    
    /**
     * 更新店铺订单第3方物流单号
     *
     * @return  void
     */
    public function updateOrderOtherLogisticNo($order) {
        $set = array('status_business' => 2,
                     'logistic_no' => $order['logistic_no'],
                     'logistic_time' => $order['logistic_time'],
                     'update_time' => time(),
                    );
        $this -> _db -> update($this -> _table_order, $set, "shop_id = {$order['shop_id']} and external_order_sn = '{$order['external_order_sn']}'");
    }
    
    /**
     * 更新店铺订单状态为已签收(第3方物流发货用)
     *
     * @return  void
     */
    public function updateOrderStatusToSigned($order) {
        $set = array('status' => 10,
                     'update_time' => time(),
                    );
        $this -> _db -> update($this -> _table_order, $set, "shop_id = {$order['shop_id']} and external_order_sn = '{$order['external_order_sn']}'");
    }
    
    /**
     * 新增同步日志
     *
     * @return  void
     */
    public function addSyncLog($shopID, $actionName, $startTime, $log) {
        if ( !$log )    $log = '无';
        
        $row = array('shop_id' => $shopID,
                     'action_name' => $actionName,
                     'start_time' => $startTime,
                     'end_time' => time(),
                     'content' => $log,
                     'admin_name' => $this -> _auth['admin_name'] ? $this -> _auth['admin_name'] : 'system',
                    );
        $this -> _db -> insert($this -> _table_sync_log, $row);
        
        return $this -> _db -> lastInsertId();
    }
    
    /**
     * 初始化同步日志
     *
     * @return  void
     */
    public function initSyncLog() {
        $this -> _log = '';
    }
    
    /**
     * 获得所有区域
     *
     * @return  void
     */
    public function getAllArea($parentID = null) {
        if ( $parentID !== null ) {
            $where = " where parent_id = $parentID";
        }
        $datas = $this -> _db -> fetchAll("select area_id,parent_id,area_name from {$this -> _table_area} {$where}");
        foreach ( $datas as $data ) {
            $result[$data['parent_id']][$data['area_name']] = $data['area_id'];
        }
        
        return $result;
    }
    
    /**
     * 获得一个区域
     *
     * @return  void
     */
    public function getArea($areaID) {
        return $this -> _db -> fetchRow("select * from {$this -> _table_area} where area_id = {$areaID}");
    }
    
    /**
     * 修改订单地址
     *
     * @return  void
     */
    public function changeOrderAddress($address) {
        $set = array('addr_province_id' => $address['province_id'],
                     'addr_city_id' => $address['city_id'],
                     'addr_area_id' => $address['area_id'],
                     'addr_address' => $address['addr_address'],
                     'addr_consignee' => $address['addr_consignee'],
                     'addr_tel' => $address['addr_tel'],
                     'addr_mobile' => $address['addr_mobile'],
                    );
        
        $data = $this -> _db -> fetchRow("select area_name from {$this -> _table_area} where area_id = {$address['province_id']}");
        $set['addr_province'] = $data['area_name'];
        
        $data = $this -> _db -> fetchRow("select area_name from {$this -> _table_area} where area_id = {$address['city_id']}");
        $set['addr_city'] = $data['area_name'];
        
        $data = $this -> _db -> fetchRow("select area_name from {$this -> _table_area} where area_id = {$address['area_id']}");
        $set['addr_area'] = $data['area_name'];
        
        $this -> _db -> update($this -> _table_order, $set, "shop_order_id = {$address['id']}");
    }
    
    /**
     * 修改订单发票
     *
     * @return  void
     */
    public function changeOrderInvoice($invoice) {
        $set = array('invoice' => $invoice['invoice'],
                     'invoice_content' => $invoice['invoice_content'],
                    );
        $this -> _db -> update($this -> _table_order, $set, "shop_order_id = {$invoice['id']}");
    }
    
    /**
     * 添加客服备注
     *
     * @return  void
     */
    public function addOrderAdminMemo($order) {
        $data = $this -> _db -> fetchRow("select admin_memo from {$this -> _table_order} where shop_order_id = {$order['id']}");
        if (!$data) return false;
        
        $data['admin_memo'] .= $order['new_admin_memo'].' ';
        $this -> _db -> update($this -> _table_order, array('admin_memo' => $data['admin_memo']), "shop_order_id = {$order['id']}");
    }
    
    /**
     * 添加操作记录
     *
     * @return  void
     */
    public function addOrderHistory($order) {
        $data = $this -> _db -> fetchRow("select history from {$this -> _table_order} where shop_order_id = {$order['id']}");
        if (!$data) return false;
        !$this -> _auth['admin_name'] && $this -> _auth['admin_name'] = 'system';
        $data['history'] .= $order['history'].' - '.$this -> _auth['admin_name'].' '.date('Y-m-d H:i:s').'<br>';
        $this -> _db -> update($this -> _table_order, array('history' => $data['history']), "shop_order_id = {$order['id']}");
    }
    
    /**
     * 同步官网订单
     *
     * @return  void
     */
    public function syncOrder($shopAPI, $shopID, $shopOrderID = null, $isCron = false) {
        if ( !$shopOrderID ) {
            $sql = "select shop_order_id,status,external_order_sn from {$this -> _table_order} where shop_id = {$shopID} and (status_business in (1,2) or (is_fake in(1,2) and status_business = 9))";
            if ($isCron) {
                $sql .= " and order_time >= ".(time() - 3600 * 24 * 60);
            }

            $shopOrderData = $this -> _db -> fetchAll($sql);
            
            if ( !$shopOrderData ) {
                $this -> _log .= "无订单\n";
                return false;
            }
            
            foreach ( $shopOrderData as $shopOrder) {
                $shopOrderSNArray[] = "'{$shopOrder['external_order_sn']}'";
                $shopOrderMap[$shopOrder['external_order_sn']]['status'] = $shopOrder['status'];
                $shopOrderMap[$shopOrder['external_order_sn']]['shop_order_id'] = $shopOrder['shop_order_id'];
            }
            $orderData = $this -> _db -> fetchAll("select t1.external_order_sn,t2.status_logistic,t2.status_return from {$this -> _table_main_order} as t1 left join {$this -> _table_order_batch} as t2 on t1.order_id = t2.order_id where t1.shop_id = {$shopID} and t2.status = 0 and t1.external_order_sn in (".implode(',', $shopOrderSNArray).")");
            if ($orderData) {
                foreach ( $orderData as $order ) {
                    if ( ($order['status_logistic'] == 2 && $shopOrderMap[$order['external_order_sn']]['status'] == 2) ||
                         ($order['status_logistic'] == 3 && $shopOrderMap[$order['external_order_sn']]['status'] == 3) ||
                         ($order['status_logistic'] == 6 && $shopOrderMap[$order['external_order_sn']]['status'] == 3) ||
                         ($order['status_logistic'] == 4 && !$order['status_return'] && $shopOrderMap[$order['external_order_sn']]['status'] == 10) ||
                         ($order['status_logistic'] == 5 && !$order['status_return'] && $shopOrderMap[$order['external_order_sn']]['status'] == 10) ) {
                        unset($shopOrderMap[$order['external_order_sn']]);
                    }
                }
            }
            
            foreach ( $shopOrderMap as $shopOrderSN => $shopOrder ) {
                $this -> syncOrder($shopAPI, $shopID, $shopOrder['shop_order_id']);
            }
            
            return true;
        }
        
        $shopOrder = $this -> _db -> fetchRow("select t1.*,t2.shop_type from {$this -> _table_order} as t1 left join {$this -> _table} as t2 on t1.shop_id = t2.shop_id where shop_order_id = {$shopOrderID}");
        if ( !$shopOrder ) {
            $this -> _log .= "找不到对应的订单\n";
            return false;
        }
        $shopOrderGoods = $this -> _db -> fetchAll("select * from {$this -> _table_order_goods} where shop_order_id = {$shopOrderID}");
        if ( !$shopOrderGoods ) {
            $this -> _log .= "找不到对应的订单商品\n";
            return false;
        }
        
        $shopOrderSN = $shopOrder['external_order_sn'];
        
        $this -> _orderAPI = $this -> _orderAPI ? $this -> _orderAPI : new Admin_Models_API_Order();
        $order = $this -> _db -> fetchRow("select batch_sn from {$this -> _table_main_order} where shop_id = {$shopID} and external_order_sn = '{$shopOrderSN}'");
        if ( $order ) {
            $batchSN = $order['batch_sn'];
            $order = $this -> _db -> fetchRow("select * from {$this -> _table_order_batch} where batch_sn = '{$batchSN}'");    
            if ( !$order ) {
                $this -> _log .= "订单{$batchSN}找不到对应的order_batch\n";
                return false;
            }
            
            if ($shopOrder['is_fake'] && $shopOrder['status_business'] == 9)    return $batchSN;
            
            //店铺订单状态：1待收款 2待发货 3待确认收货 10已完成 11已取消 12其它
            //官网订单状态(status)：0正常单 1取消单 2无效单 3刷单 4不发货单
            //官网物流状态(status_logistic)：0未确认 1已确认待收款 2待发货 3已发货 4客户已签收 5客户已拒收 6部分签收
            
            if ( $order['status'] == 1 ) {
                if ( $shopOrder['status'] != 11 ) {
                    $this -> _log .= "订单状态异常：订单{$batchSN}已取消，店铺订单未取消\n";
                    return false;
                }
            }
            if ( $order['status'] == 2 ) {
                if ( $shopOrder['status'] != 11 ) {
                    $this -> _log .= "订单{$batchSN}已无效，需手工更新店铺订单状态为取消\n";
                }
            }
            
            if ( $order['status'] == 0 ) {
                if ( $shopOrder['status'] == 11 ) {
                    if (in_array($order['status_logistic'], array(0,1,2))) {
                        $this -> orderBatch5($batchSN);
                        $this -> _log .= "订单{$batchSN}已取消，同步官网订单状态为取消，需处理店铺退款\n";
                    }
                    else {
                        $this -> _log .= "订单{$batchSN}已取消，需处理店铺退款\n";
                    }
                    return $batchSN;
                }
                
                if ( $order['status_logistic'] == 3 ) {
                    if ( $shopOrder['status'] == 2 ) {
                        if ($shopAPI -> syncShopLogistic($shopOrderSN, array('logistic_code' => $order['logistic_code'], 'logistic_no' => $order['logistic_no'],'logistic_time' => $order['logistic_time']))) {
                            
                            $shopOrder['logistic_code'] = $order['logistic_code'];
                            $shopOrder['logistic_no'] = $order['logistic_no'];
                            $shopOrder['logistic_time'] = $order['logistic_time'];
                            $shopOrder['status'] = 3;
                            $this -> updateOrderLogistic($shopOrder);
                            
                            $this -> _log .= "订单{$batchSN}已发货，同步店铺订单状态为待确认收货\n";
                            return $batchSN;
                        }
                        else    return false;
                    }
                }
                if ( $order['status_logistic'] == 4 || $order['status_logistic'] == 5 ) {
                    if ( $shopOrder['status'] == 2 ) {
                        if ($shopAPI -> syncShopLogistic($shopOrderSN, array('logistic_code' => $order['logistic_code'], 'logistic_no' => $order['logistic_no'],'logistic_time' => $order['logistic_time']))) {
                            $shopOrder['logistic_code'] = $order['logistic_code'];
                            $shopOrder['logistic_no'] = $order['logistic_no'];
                            $shopOrder['logistic_time'] = $order['logistic_time'];
                            $shopOrder['status'] = 3;
                            $this -> updateOrderLogistic($shopOrder);
                            
                            $this -> _log .= "订单{$batchSN}客户已发货，同步店铺订单状态为待确认收货\n";
                            return $this -> syncOrder($shopAPI, $shopID, $shopOrderID);
                        }
                        else    return false;
                    }
                }
                
                if ( $order['status_logistic'] == 4 && $shopOrder['status'] == 3 ) {
                    if ($shopAPI -> syncSignStatus($shopOrderSN)) {
                        $this -> updateOrderLogisticStatus($shopOrderID, 1);
                        $this -> _log .= "订单{$batchSN}客户已签收，同步店铺订单物流状态为已签收\n";
                        return $batchSN;
                    }
                    else    return false;
                }
                
                if ( $order['status_logistic'] == 5 && $shopOrder['status'] == 3 ) {
                    if ($shopAPI -> syncRejectStatus($shopOrderSN)) {
                        $this -> updateOrderLogisticStatus($shopOrderID, 2);
                        $this -> _log .= "订单{$batchSN}客户已拒收，同步店铺订单物流状态为已拒收\n";
                        return $this -> syncOrder($shopAPI, $shopID, $shopOrderID);
                    }
                    else    return false;
                }
                if ( $order['status_return'] == 1 && $order['status_pay'] == 2 && $shopOrder['logistic_status'] != 3 ) {
                    if ($shopAPI -> syncReturnStatus($shopOrderSN, $order['price_order'])) {
                        $this -> updateOrderLogisticStatus($shopOrderID, 3);
                        $this -> _log .= "订单{$batchSN}发生退货，同步店铺订单退货状态\n";
                        return $batchSN;
                    }
                    else    return false;
                }
                
                /*
                if ( $shopOrder['status'] == 10 ) {
                    if ( $order['status_logistic'] == 3  ) {
                        $this -> orderBatch4($batchSN, $order['logistic_code'], $order['logistic_no']);
                        $this -> _log .= "订单{$batchSN}客户已签收，同步官网订单状态为客户已签收\n";
                        return $batchSN;
                    }
                }
                */
                if ( $shopOrder['status'] == 3 || $shopOrder['status'] == 10 ) {
                    if ( $order['status_logistic'] == 2 ) {
                        if (!$shopOrder['logistic_code'] || !$shopOrder['logistic_no']) {
                            $shopOrder['logistic_code'] = 'external';
                            $shopOrder['logistic_no'] = '000000';
                        }

                        if ($this -> orderBatch3($batchSN, $shopOrder)) {
                            $this -> _log .= "订单{$batchSN}已发货，同步官网订单状态为已发货\n";
                            
                            if ($shopOrder['is_fake'] && $shopOrder['status_business'] != 9) {
                                $this -> updateOrderBatch4($batchSN);
                            }
                            
                            return $this -> syncOrder($shopAPI, $shopID, $shopOrderID);
                        }
                        else    return false;
                    }
                }
            }
        }
        else {
            //只同步 待确认收货/已完成/审核不通过的刷单 订单
            if (!in_array($shopOrder['status'], array(3,10))) {
                if (!$shopOrder['is_fake'] || ($shopOrder['is_fake'] && $shopOrder['status_business'] != 9)) {
                    return $batchSN;
                }
            }
            
            if (!$shopOrder['addr_province_id'] || !$shopOrder['addr_city_id'] || !$shopOrder['addr_area_id']) {
                if (!$shopOrder['addr_province_id'])    $shopOrder['addr_province_id'] = 3982;
                if (!$shopOrder['addr_city_id'])        $shopOrder['addr_city_id'] = 3983;
                if (!$shopOrder['addr_area_id'])        $shopOrder['addr_area_id'] = 3984;
                $this -> _log .= "订单{$shopOrderSN}地址信息不全，设置为未知地区\n";
            }
            
            $batchSN = $this -> orderBatch1($shopOrder, $shopOrderGoods);
            if ($batchSN) {
                if ($shopOrder['is_fake'] && $shopOrder['status_business'] == 9) {
                    $this -> orderBatch6($batchSN);
                    $this -> _orderAPI -> orderDetail($batchSN);
                    $this -> _db -> update($this -> _table_order, array('sync' => 1, 'sync_time' => time()), "external_order_sn = '{$shopOrderSN}'");
                    return $batchSN;
                }
                if (!$this -> orderBatch2($batchSN, $shopOrder))  return false;
            }
            else    return false;
            
            //锁定运输单，防止物流误操作
            $this -> updateTransportLock($batchSN, $this -> _auth['admin_name'] ? $this -> _auth['admin_name'] : 'system');
            
            $this -> _db -> update($this -> _table_order, array('sync' => 1, 'sync_time' => time()), "external_order_sn = '{$shopOrderSN}'");
            
            return $this -> syncOrder($shopAPI, $shopID, $shopOrderID);
        }
        
        return $batchSN;
    }
    
    /**
     * 导入官网订单，状态为未确认
     *
     * @return  void
     */
    public function importToFormalOrder($order) 
    {
        $data = $this -> _db -> fetchRow("select batch_sn from {$this -> _table_main_order} where shop_id = {$order['shop_id']} and external_order_sn = '{$order['external_order_sn']}'");
        if ($data)  return false;
        
        $batchSN = $this -> orderBatch1($order, $order['goods'], true);
        if (!$batchSN)  return false;
        
        $this -> updateOrderBatch3($batchSN);
        
        $this -> _db -> update($this -> _table_order, array('sync' => 1, 'sync_time' => time()), "shop_order_id = '{$order['shop_order_id']}'");
        
        return $batchSN;
    }
    
    /**
     * 获得productID
     *
     * @return  void
     */
    public function getProductIDByGoodsSN($goodsSN) {
        $goodsSN = trim($goodsSN);
        $data = $this -> _db -> fetchRow("select product_id from {$this -> _table_product} where product_sn = '{$goodsSN}'");
        if ( !$data )   return false;
        
        return $data['product_id'];
    }
    
    /**
     * 获得productIDS
     *
     * @return  void
     */
    public function getProductIDSByGoodsSNArray($goodsSNArray) {
        if (!$goodsSNArray) return false;
        foreach ($goodsSNArray as $goodsSN) {
            $array[] = "'{$goodsSN}'";
        }
        $datas = $this -> _db -> fetchAll("select product_id, product_sn from {$this -> _table_product} where product_sn in (".implode(',', $array).")");
        if ( !$datas )   return false;
        
        foreach ($datas as $data) {
            $result[$data['product_id']] = $data['product_sn'];
        }
        
        return $result;
    }
    
    /**
     * 新建官网订单，状态为未确认，未收款
     *
     * @return  void
     */
    private function orderBatch1($shopOrder, $shopOrderGoods, $holdStock = false)
    {
        $orderGoods = '';
        foreach ( $shopOrderGoods as $goods ) {
            if ( !$goods['goods_sn'] ) {
                $goods['goods_sn'] = $this -> getShopGoodsSNByShopGoodsID($shopOrder['shop_id'], $goods['shop_goods_id']);
                if ( $goods['goods_sn'] ) {
                    $this -> updateOrderGoodsSN($goods['id'], $goods['goods_sn']);
                }
                else {
                    $this -> _log .= "{$shopOrder['external_order_sn']}订单的商品{$goods['shop_goods_id']}没有商品编码\n";
                    return false;
                }
            }
            
            if (strlen($goods['goods_sn']) == 9) {
                $groupGoodsAPI = new Admin_Models_API_GroupGoods();
                $groupGoods = $groupGoodsAPI -> fetchConfigGoods(array('group_sn' => $goods['goods_sn'],'check_status' => '1'));
                if (!$groupGoods) {
                    $this -> _log .= "{$shopOrder['external_order_sn']}订单的商品编码{$goods['goods_sn']}找不到记录,或者不存在\n";
                    return false;
                }
                $amount = 0;
                foreach ($groupGoods as $key => $detail) {
                    $config[$key]['product_id'] = $detail['product_id'];
                    $config[$key]['price'] = $detail['price'];
                    $config[$key]['number'] = $detail['number'];
                    $config[$key]['amount'] = $detail['price'] * $detail['number'];
                    $amount += $config[$key]['amount'];
                }
                
                foreach ($config as $key => $detail) {
                    if ($amount == 0) {
                        $config[$key]['price'] = round(1 / count($config) * $goods['price'] / $config[$key]['number'], 2);
                        $config[$key]['discount_price'] = round(1 / count($config) * $goods['discount_price'] / $goods['number'] / $config[$key]['number'], 2);
                    }
                    else {
                        $config[$key]['price'] = round($config[$key]['amount'] / $amount * $goods['price'] / $config[$key]['number'], 2);
                        $config[$key]['discount_price'] = round($config[$key]['amount'] / $amount * $goods['discount_price'] / $goods['number'] / $config[$key]['number'], 2);
                    }
                }
                
                foreach ($config as $detail) {
                    $salePrice = $detail['price'] - $detail['discount_price'];
                    $number = $goods['number'] * $detail['number'];
                    if ($orderGoods[$detail['product_id']]) {
                        $amount = $orderGoods[$detail['product_id']]['sale_price'] * $orderGoods[$detail['product_id']]['number'] + $salePrice * $number;
                        $orderGoods[$detail['product_id']] = array('sale_price' => round($amount / ($orderGoods[$detail['product_id']]['number'] + $number), 2),
                                                                   'number' => $orderGoods[$detail['product_id']]['number'] + $number,
                                                                  );
                    }
                    else {
                        $orderGoods[$detail['product_id']] = array('sale_price' => $salePrice,
                                                                   'number' => $number,
                                                                  );
                    }
                }
            }
            else {
                $goods['product_id'] = $this -> getProductIDByGoodsSN($goods['goods_sn']);
                if ( !$goods['product_id'] ) {
                    $this -> _log .= "{$shopOrder['external_order_sn']}订单的商品编码{$goods['goods_sn']}找不到Product ID\n";
                    return false;
                }
                
                if ($orderGoods[$goods['product_id']]) {
                    $amount = $orderGoods[$goods['product_id']]['sale_price'] * $orderGoods[$goods['product_id']]['number'] + $goods['price'] * $goods['number'] - $goods['discount_price'];
                    $orderGoods[$goods['product_id']] = array('sale_price' => round($amount / ($orderGoods[$goods['product_id']]['number'] + $goods['number']), 2),
                                                              'number' => $orderGoods[$goods['product_id']]['number'] + $goods['number'],
                                                             );
                }
                else {
                    $orderGoods[$goods['product_id']] = array('sale_price' => round(($goods['price'] * $goods['number'] - $goods['discount_price']) / $goods['number'], 2),
                                                              'number' => $goods['number'],
                                                             );
                }
            }
        }
        
        $this -> _orderAPI = $this -> _orderAPI ? $this -> _orderAPI : new Admin_Models_API_Order();
        
        if ($holdStock) {
            if (!$this -> _stockAPI) {
                $this -> _stockAPI = new Admin_Models_API_Stock();
            }
            foreach ($orderGoods as $productID => $goods) {
                $this -> _stockAPI -> holdSaleProductStock($productID, $goods['number']);
            }
        }
        
        $error = '';
        if ($shopOrder['is_cod']) {
            $payment = 'cod|货到付款';
        }
        else {
            $payment = 'external|渠道支付';
        }

        $batchSN = $this -> _orderAPI -> add('external', $orderGoods, null, $shopOrder['addr_province_id'], $shopOrder['addr_city_id'], $shopOrder['addr_area_id'], $error, '', $shopOrder['addr_consignee'], $shopOrder['addr_address'], $shopOrder['addr_tel'], $shopOrder['addr_mobile'], $payment, $shopOrder['freight'], $shopOrder['shop_id'], $shopOrder['external_order_sn'], date('Y-m-d H:i:s', $shopOrder['order_time']), $shopOrder['invoice'].$shopOrder['memo']);
        if ($batchSN) {
            //检查商品单价*数量一定等于订单商品金额，如果不是，则增加调整金额
            $goodsData = $this -> _db -> fetchAll("select sale_price,number from {$this -> _table_order_batch_goods} where batch_sn = '{$batchSN}'");
            $amount = 0;
            foreach ( $goodsData as $goods ) {
                $amount += $goods['sale_price'] * $goods['number'];
            }
            if ( abs($amount - $shopOrder['goods_amount']) > 0 ) {
                $this -> updateOrderBatch2($batchSN, $shopOrder['goods_amount'] - $amount);
                $this -> _db -> execute("update {$this -> _table_order_batch} set price_pay = {$shopOrder['goods_amount']} + price_logistic where batch_sn = '{$batchSN}'");
                $this -> _log .= "{$batchSN}订单调整金额\n";
            }
            
            $this -> _log .= "{$shopOrder['external_order_sn']}订单新增成功，订单号为{$batchSN}\n";
            return $batchSN;
        }
        else {
            $this -> _log .= "{$shopOrder['external_order_sn']}订单{$error}\n";
            return false;
        }
    }
    
    /**
     * 更新官网订单，状态为(已收款)，待发货
     *
     * @return  void
     */
    private function orderBatch2($batchSN, $order)
    {
        $this -> _orderAPI -> _updateOrderBatchLogistic($batchSN);
        
        $this -> _orderAPI -> orderDetail($batchSN);
        
        if ( $this -> _orderAPI -> out($batchSN, null, $order['logic_area'] ? $order['logic_area'] : 1, $this -> getOutStockSN($order)) ) {
            if ($order['is_cod']) {
                $this -> updateOrderBatch5($batchSN);
            }
            else {
                $this -> updateOrderBatch1($batchSN);
            }
        }
        else {
            $this -> _log .= "{$batchSN}订单出库错误\n";
            return false;
        }
        
        $this -> updateTransportValidateSN($batchSN, $order['validate_sn']);
        
        $this -> _orderAPI -> orderDetail($batchSN);
        
        return true;
    }
    
    /**
     * 更新官网订单，状态为已确认，已收款，已发货
     *
     * @return  void
     */
    private function orderBatch3($batchSN, $shopOrder)
    {
        $logisticCode = $shopOrder['logistic_code'];
        $logisticNo = $shopOrder['logistic_no'];
        $logisticTime = $shopOrder['logistic_time'];
        $transportAPI = new Admin_Models_API_Transport();
        
        $data = array_shift($transportAPI -> get("bill_no = '{$batchSN}'"));
        
        if (!$data['logistic_list']) {
            $data['logistic_list'] = '{"list":{"ems":{"logistic_code":"ems","zip":"","code":"","country_id":"1","province_id":"10","city_id":"105","area_id":"1177","country":"","open":"1","delivery":"1","province":"","city":"","area":"","cod":"0","delivery_keyword":"","non_delivery_keyword":"","price":"0.00","logistic_name":"ems","cod_rate":"0.00","cod_min":"0.00","fee_service":"0.00","cod_price":0,"is_send":"OK","search_mod":"\u6392\u9664\u6cd5"}},"other":{"name":"\u666e\u901a\u5feb\u9012","cod":0,"zip":null,"province":"","city":"","area":"","area_id":"1177","address":"","amount":0,"volume":0,"number":1,"weight":1,"default":"ems","default_cod":null}}';
        }
        $r = Zend_Json::decode($data['logistic_list']);
        foreach ($r['list'] as $k => $v) {
            if ( $v['logistic_code'] == $logisticCode ) {
                $logistic = $v;
                break;
            }
        }
        if ( !$logistic ) {
            $this -> _log .= "{$batchSN}订单没有找到物流公司{$logisticCode}，分配为渠道物流\n";
            foreach ($r['list'] as $k => $v) {
                $logistic = $v;
                if (!$this -> _logisticData[$logisticCode]) {
                    $logisticAPI = new Admin_Models_API_Logistic();
                    $this -> _logisticData[$logisticCode] = $logisticAPI -> getLogisticByID($logisticCode);
                }
                $logistic['logistic_code'] = $logisticCode;
                $logistic['logistic_name'] = $this -> _logisticData[$logisticCode]['name'];
                
                break;
            }
        }
        
        $data['logistic'] = addslashes(Zend_Json::encode($logistic));
        $data['number'] = 1;
        if ( !$transportAPI -> _auth['admin_name'] ) {
            $transportAPI -> _auth['admin_name'] = 'system';
        }
        
        $transportAPI -> assign($data, $data['tid']);
        
        //锁定运输单
        $this -> updateTransportLock($batchSN, $this -> _auth['admin_name'] ? $this -> _auth['admin_name'] : 'system');
        
        $transportAPI -> confirm($data, $data['tid']);
        
        $outStockAPI = new Admin_Models_API_OutStock();
        $datas = $outStockAPI -> getDetail("bill_no='{$batchSN}' and is_cancel=0 and bill_status=4");
        if (!$datas) {
            $this -> _log .= "{$batchSN}订单没有找到出库信息，无法发货\n";
            return false;
        }
        
        $data = array('bill_type' => $datas[0]['bill_type'],
                      'bill_no' => $datas[0]['bill_no'],
                      'logistic_no' => $logisticNo,
                      'logistic_code' => $logisticCode,
                      'logic_area' => 1,
                      'outstock_id' => $datas[0]['outstock_id'],
                      'total_number' => 0,
                      'logistic_time' => $logisticTime,
                      'shop_id' => $data['shop_id'],
                     );
        foreach ($datas as $num => $v) {
		    $data['total_number'] += $v['number'];
		    $data['product_id'][] = $v['product_id'];
		    $data['batch_id'][] = $v['batch_id'];
		    $data['number'][] = $v['number'];
		    $data['status'][] = $v['status_id'];
	    }
	    
	    if ( !$outStockAPI -> _auth['admin_name'] ) {
            $outStockAPI -> _auth['admin_name'] = 'system';
        }
        $outStockAPI -> send($data, $data['outstock_id']);
        
        //添加应收款记录
        if (!$shopOrder['is_cod']) {
            if (!$this -> financeAPI) {
                $this -> financeAPI = new Admin_Models_API_Finance();
            }
            $receiveData = array('batch_sn' => $batchSN,
                                 'type' => 3,
                                 'pay_type' => 'external',
                                 'amount' => $shopOrder['amount'],
                                 'add_time' => $logisticTime,
                                );
            $this -> financeAPI -> addFinanceReceivable($receiveData);
        }
        
        return true;
    }
    
    /**
     * 更新官网订单，状态为客户已签收
     *
     * @return  void
     */
    private function orderBatch4($batchSN, $logisticCode, $logisticNo)
    {
        $transportAPI = new Admin_Models_API_Transport();
        
        $data = array('logistic_status' => 2,
                      'bill_no' => $batchSN,
                      'bill_type' => 1,
                      'admin_name' => 'system',
                      'logistic_no' => $logisticNo,
                      'logistic_code' => $logisticCode ? $logisticCode : 'external',
                      'remark' => '渠道订单同步'
                     );
        
        $transportAPI -> track($data);
        
        return true;
    }
    
    /**
     * 更新官网订单，状态为已取消
     *
     * @return  void
     */
    private function orderBatch5($batchSN)
    {
        $this -> _orderAPI -> confirmCancel($batchSN);
        
        return true;
    }
    
    /**
     * 新建官网订单，状态为渠道刷单
     *
     * @return  void
     */
    private function orderBatch6($batchSN)
    {
        $this -> updateOrderBatch1($batchSN);
        $this -> updateOrderBatch4($batchSN);
    }
    
    /**
     * 更新order_batch表，状态变为已确认，已收款，待发货
     *
     * @return  void
     */
    private function updateOrderBatch1($batchSN)
    {
        $set = array('batch_sn' => $batchSN,
                     'status_logistic' => 2,
                     'status_pay' => 2,
                     'lock_name' => '',
                    );
        $data = $this -> _db -> fetchRow("select add_time,price_pay from {$this -> _table_order_batch} where batch_sn = '{$set['batch_sn']}'");
        $set['price_payed'] = $data['price_pay'];
        $set['pay_time'] = $data['add_time'];
        $this -> _db -> update($this -> _table_order_batch, $set, "batch_sn = '{$set['batch_sn']}'");
    }
    
    /**
     * 更新order_batch表，调整金额
     *
     * @return  void
     */
    public function updateOrderBatch2($batchSN, $adjustAmount, $note = '')
    {
        $row = array('order_sn' => $batchSN,
                     'batch_sn' => $batchSN,
                     'type' => 1,
                     'money' => $adjustAmount,
                     'note' => $note ? $note : '渠道订单金额小数点误差调整',
                     'add_time' => time(),
                    );
        $this -> _db -> insert($this -> _table_order_batch_adjust, $row);
    }
    
    /**
     * 更新order_batch表，状态变为未确认，未收款
     *
     * @return  void
     */
    private function updateOrderBatch3($batchSN)
    {
        $set = array('batch_sn' => $batchSN,
                     'lock_name' => '',
                    );
        $this -> _db -> update($this -> _table_order_batch, $set, "batch_sn = '{$set['batch_sn']}'");
    }
    
    /**
     * 更新order_batch表，状态变为渠道刷单
     *
     * @return  void
     */
    private function updateOrderBatch4($batchSN)
    {
        $data = $this -> _db -> fetchRow("select add_time,price_order,fake_type from {$this -> _table_order_batch} where batch_sn = '{$batchSN}'");
        $set = array('status' => 3,
                     'balance_amount' => $data['price_order'],
                     //'logistic_time' => $data['add_time'],
                     'logistic_time' => time(),
                    );
        $this -> _db -> update($this -> _table_order_batch, $set, "batch_sn = '{$batchSN}'");
        
        if ($data['fake_type'] == 0) {  //有款刷单
            //添加应收款记录
            if (!$this -> financeAPI) {
                $this -> financeAPI = new Admin_Models_API_Finance();
            }
            $receiveData = array('batch_sn' => $batchSN,
                                 'type' => 3,
                                 'pay_type' => 'external',
                                 'amount' => $data['price_order'],
                                 'add_time' => $data['add_time'],
                                );
            $this -> financeAPI -> addFinanceReceivable($receiveData);
        }
    }
    
    /**
     * 更新order_batch表，状态变为待发货
     *
     * @return  void
     */
    private function updateOrderBatch5($batchSN)
    {
        $set = array('status_logistic' => 2,
                     'lock_name' => '',
                    );
        $this -> _db -> update($this -> _table_order_batch, $set, "batch_sn = '{$batchSN}'");
    }
    
    /**
     * 更新transport表，锁定和解锁
     *
     * @return  void
     */
    private function updateTransportLock($batchSN, $lockName)
    {
        $this -> _db -> update($this -> _table_transport, array('lock_name' => $lockName), "bill_no = '{$batchSN}'");
    }
    
    /**
     * 更新transport表，ValidateSN
     *
     * @return  void
     */
    private function updateTransportValidateSN($batchSN, $validateSN)
    {
        $this -> _db -> update($this -> _table_transport, array('validate_sn' => $validateSN ? $validateSN : 0), "bill_no = '{$batchSN}'");
    }
    
    /**
     * 获得订单物流信息
     *
     * @return  void
     */
    public function getOrderLogistic($shopAPI, $orderData)
    {
        if ( !$orderData )  return $orderData;
        
        $externalOrderSNArray = array();
        $externalOrderSNMap = array();
        foreach ( $orderData as $order ) {
            if ( in_array($order['status'], array(3, 10)) && !$order['logistic_time'] ) {
                $externalOrderSNArray[] = "'{$order['external_order_sn']}'";
                $externalOrderSNMap[$order['external_order_sn']] = 1;
            }
        }
        
        if ( count($externalOrderSNArray) == 0 )    return $orderData;
        
        $data = $this -> getOrder(array('external_order_sns' => implode(',', $externalOrderSNArray)));
        if ( $data['total'] > 0 ) {
            $externalOrderSNArray = array();
            foreach ( $data['list'] as $order ) {
                if ( in_array($order['status'], array(3, 10)) && $order['logistic_no'] ) {
                    $externalOrderSNMap[$order['external_order_sn']] = 0;
                }
            }
            $externalOrderSNArray = array();
            foreach ( $externalOrderSNMap as $externalOrderSN => $value ) {
                if ( $value ) {
                    $externalOrderSNArray[] = $externalOrderSN;
                }
            }
            
            $logisticData = $shopAPI -> syncOrderLogistic($externalOrderSNArray);
            if ( !is_array($logisticData) ) return false;
            
            if ( count($logisticData) > 0 ) {
                foreach ( $orderData as $key => $order ) {
                    if ( $logisticData[$order['external_order_sn']] ) {
                        $orderData[$key]['logistic_code'] = $logisticData[$order['external_order_sn']]['logistic_code'];
                        $orderData[$key]['logistic_no'] = $logisticData[$order['external_order_sn']]['logistic_no'];
                        $orderData[$key]['logistic_time'] = $logisticData[$order['external_order_sn']]['logistic_time'];
                    }
                }
            }
        }
        
        return $orderData;
    }
    
    /**
     * 获得官网商品名称，通过产品编号
     *
     * @return  void
     */
    public function getProductNameByGoodsSN($productSN)
    {
        $row = $this -> _db -> fetchRow("select product_name from {$this -> _table_product} where product_sn = '{$productSN}'");
        return $row['product_name'];
    }
    
    /**
     * 获得官网商品信息，通过商品编号
     *
     * @return  void
     */
    public function getProductInfoByGoodsSN($productSN)
    {
        return $this -> _db -> fetchRow("select * from {$this -> _table_product} where product_sn = '{$productSN}'");
    }
    
    /**
     * 更新店铺最后下载订单时间
     *
     * @param    int      $shopID
     * @return   string
     */
	public function updateOrderSyncTime($shopID)
	{
	    $this -> _db -> update($this -> _table, array('sync_order_time' => time()), "shop_id = {$shopID}");
	}
	
	/**
     * 编辑店铺活动
     *
     * @return  void
     */
    public function editPromotion($data, $id = null)
    {
      
	/*
		if (in_array($data['shop_id'], array(21,22,23,24))) {
            if (!in_array($this -> _auth['admin_id'], array(50,69,71,79))) {
                $this -> error = 'no_priviledge';
                return false;
            }
        }
        else if (in_array($data['shop_id'], array(31,32))) {
            if (!in_array($this -> _auth['admin_id'], array(42,68))) {
                $this -> error = 'no_priviledge';
                return false;
            }
        }
        else if (in_array($data['shop_id'], array(41))) {
            if (!in_array($this -> _auth['admin_id'], array(47,61))) {
                $this -> error = 'no_priviledge';
                return false;
            }
        }
       */

        $set = array('shop_id' => $data['shop_id'],
                     'promotion_name' => $data['promotion_name'],
                     'type' => $data['type'],
                     'start_time' => strtotime($data['start_time']),
                     'end_time' => strtotime($data['end_time']),
                     'config' => serialize($data['config']),
                     'sort' => $data['sort'],
                     'status' => $data['status'],
                    );
        
        if ( $id ) {
            $promotion = $this -> _db -> fetchRow("select history from {$this -> _table_promotion} where promotion_id = {$id}");
            $set['history'] = $promotion['history']."修改活动 {$this -> _auth['admin_name']} ".date('Y-m-d H:i:s')."\n";
            $this -> _db -> update($this -> _table_promotion, $set, "promotion_id = {$id}");
        }
        else {
            $set['add_time'] = time();
            $set['history'] = "添加活动 {$this -> _auth['admin_name']} ".date('Y-m-d H:i:s')."\n";
            $this -> _db -> insert($this -> _table_promotion, $set);
            $id = $this -> _db -> lastInsertId();
        }
        
        return $id;
    }
    
    /**
     * 获得订单促销
     *
     * @param    array  $goodsArray
     * @param    array  $order
     * @return   string
     */
    function orderPromotion($order, $goodsArray)
    {
        if ( !$this -> _promotionList ) {
            $this -> _promotionList = $this -> _db -> fetchAll("select * from {$this -> _table_promotion} where status = 0 order by shop_id,sort desc");
            if ($this -> _promotionList) {
                for ( $i = 0; $i < count($this -> _promotionList); $i++ ) {
                    $this -> _promotionList[$i]['config'] = unserialize($this -> _promotionList[$i]['config']);
                }
            }
            else    $this -> _promotionList = array();
        }
        
        foreach ( $this -> _promotionList as $promotion ) {
            if ( $promotion['shop_id'] != $order['shop_id'] )   continue;
            if ( $order['order_time'] < $promotion['start_time'] || $order['order_time'] > $promotion['end_time'] ) continue;
            if ( $breakType[$promotion['type']] )   continue;
            
            if ( $promotion['type'] == 1 ) {
                if ( $order['amount'] < $promotion['config']['condition1']['from_amount'] )    continue;
                if ( $promotion['config']['promotion']['cycle'] ) {
                    $number = floor($order['amount'] / $promotion['config']['condition1']['from_amount']);
                }
                else    $number = 1;
            }
            else if ( $promotion['type'] == 2 && is_array($promotion['config']['condition2']['goods_sn'])) {
                foreach ( $goodsArray as $goods ) {
                    $goodsMap[$goods['goods_sn']] = $goods['number'];
                }
                
                $flag = true;
                for ($i = 0; $i < count($promotion['config']['condition2']['goods_sn']); $i++) {
                    if (!$promotion['config']['condition2']['goods_sn'][$i])    continue;
                    
                    if (!$goodsMap[$promotion['config']['condition2']['goods_sn'][$i]]) {
                        $flag = false;
                        break;
                    }
                    
                    if ( $goodsMap[$promotion['config']['condition2']['goods_sn'][$i]] < $promotion['config']['condition2']['number'][$i] ) {
                        $flag = false;
                        break;
                    }
                }
                
                if ( !$flag )   continue;
                
                if ( $promotion['config']['promotion']['cycle'] ) {
                    $number = 9999;
                    for ($i = 0; $i < count($promotion['config']['condition2']['goods_sn']); $i++) {
                        if (!$promotion['config']['condition2']['goods_sn'][$i])    continue;
                        
                        $tempNumber = floor($goodsMap[$promotion['config']['condition2']['goods_sn'][$i]] / $promotion['config']['condition2']['number'][$i]);
                        if ($tempNumber  < $number) {
                            $number = $tempNumber;
                        }
                    }
                }
                else    $number = 1;
            }
            else    continue;
            
            for ($i = 0; $i < count($promotion['config']['promotion']['goods_sn']); $i++) {
                $goods_sn = $promotion['config']['promotion']['goods_sn'][$i];
                $goods_number = $promotion['config']['promotion']['number'][$i];
                
                if (!$goods_sn || !$goods_number)   continue;
                
                if ( !$this -> _goodsList[$goods_sn] ) {
                    $this -> _goodsList[$goods_sn] = $this -> _db -> fetchRow("select * from {$this -> _table_product} where product_sn = '{$goods_sn}'");
                }
                
                $goodsArray[] = array('shop_order_id' => $order['shop_order_id'],
                                      'shop_id' => $order['shop_id'],
                                      'goods_sn' => $goods_sn,
                                      'shop_goods_name' => $this -> _goodsList[$goods_sn]['product_name'],
                                      'price' => 0,
                                      'number' => $number * $goods_number,
                                      'discount_price' => 0,
                                     );
                                     
                $this -> _log .= "订单{$order['external_order_sn']}符合\"{$promotion['promotion_name']}\"活动，添加赠品{$goods_sn} * ".$number * $goods_number."\n";
            }
            
            if ($promotion['sort'] > 0) {
                $breakType[$promotion['type']] = 1;
            }
        }
        
        return $goodsArray;
    }
    
    /**
     * 审核店铺订单
     *
     * @param    int    $shopOrderID
     * @param    int    $status
     * @param    int    $otherLogistics
     * @param    array  $set
     * @return   void
     */
    function checkOrder($shopOrderID, $status, $otherLogistics = null, $set = null)
    {
        if ($status == 1 || $status == 9) {
            $set['check_admin_name'] = $this -> _auth['admin_name'].' '.date('Y-m-d H:i:s');
        }
        if ($status == 2) {
            $set['print_admin_name'] = $this -> _auth['admin_name'].' '.date('Y-m-d H:i:s');
        }
        if ($status == -1) {
            $status = 1;
        }
        $set['status_business'] = $status;
        if ( $otherLogistics ) {
            $set['other_logistics'] = 1;
        }
        if ($status === 0) {
            $set['other_logistics'] = 0;
        }

        $this -> _db -> update($this -> _table_order, $set, "shop_order_id = {$shopOrderID}");
    }

    /**
     * 渠道商品限价判断
     *
     * @param    int
     *
     * @return   boolean
     **/
    function judgePriceLimit($shopOrderID, $status)
    {
        $order_info = array();
		// 产品限价判断
		$this->_error = '';
		if ($status == '1') {
            $order_info    = $this->getOrderInfoByShopId($shopOrderID);
            $product_infos = $this->getShopGoodsByShopId($shopOrderID);

            //加上限价
            $product_apply_db = new Admin_Models_DB_ProductApply();
            $group_goods_db   = new Admin_Models_DB_GroupGoods();
            $product_db       = new Admin_Models_DB_Product();

            if ($product_infos) {
                $str = "订单编号为：{$order_info['external_order_sn']},<br>";
                foreach ($product_infos as $info) {
                    $audit_log = array(
                        'shop_id'      => $order_info['shop_id'],
                        'channel_type' => 2,
                        'batch_sn'     => $order_info['external_order_sn'],
                        'price_order'  => $order_info['amount'],
                        'audit_id'     => $this -> _auth['admin_id'],
                        'audit_by'     => $this -> _auth['admin_name'],
                        'audit_ts'     => date('Y-m-d H:i:s'),
                    );
                    if (substr($info['goods_sn'], 0, 1) == 'N') {
                        $product_params = array(
                            'shop_id'    => $order_info['shop_id'],
                            'type'       => '1',
                            'start_ts'   => date('Y-m-d H:i:s', $order_info['add_time']),
                            'product_sn' => $info['goods_sn'],
                        );

                        $apply_info = $product_apply_db->getInfoByCondition($product_params);

                        if (!empty($apply_info)) {
                            if (floatval($apply_info['price_limit']) > floatval($info['final_price'])) {
                                if ($this -> _auth['group_id'] != 3 && $this -> _auth['group_id'] != 1   && !in_array($this->_auth['admin_name'],$this -> _allowJudgeList)) {
                                    $this->_error .= "产品编码为:{$info['goods_sn']}超过产品限价<br>";
                                } else {
                                    $audit_log  += array(
                                        'product_id'   => $apply_info['product_id'],
                                        'price_limit'  => $apply_info['price_limit'],
                                        'product_sn'   => $apply_info['product_sn'],
                                        'product_type' => 1,
                                        'avg_price'    => $info['final_price'],
                                    );
                                    $this->addPricelimitLog($audit_log);
                                }
                            }
                        } else {
                            $product_info = $product_db->getProductInfoByProductSn($info['goods_sn']);

                            if ($product_info['price_limit'] > 0 && floatval($product_info['price_limit']) > floatval($info['final_price'])) {
                                if ($this -> _auth['group_id'] != '3' && $this -> _auth['group_id'] != 1  && !in_array($this->_auth['admin_name'],$this -> _allowJudgeList)) {
                                    $this->_error .= "产品编码为:{$info['goods_sn']}超过产品限价<br>";
                                } else {
                                    $audit_log  += array(
                                        'product_id'   => $product_info['product_id'],
                                        'price_limit'  => $product_info['price_limit'],
                                        'product_sn'   => $product_info['product_sn'],
                                        'product_type' => 1,
                                        'avg_price'    => $info['final_price'],
                                    ); 
                                    $this->addPricelimitLog($audit_log);
                                }
                            }
                        }

                    } else if (substr($info['goods_sn'], 0, 1) == 'G') {
                        $group_params = array(
                            'shop_id'    => $order_info['shop_id'],
                            'type'       => '2',
                            'start_ts'   => date('Y-m-d H:i:s', $order_info['add_time']),
                            'product_sn' => $info['goods_sn'],
                        );

                        $apply_info = $product_apply_db->getInfoByCondition($group_params);

                        if (!empty($apply_info)) {
                            if (floatval($apply_info['price_limit']) > floatval($info['final_price'])) {
                                if ($this -> _auth['group_id'] != '3'  && $this -> _auth['group_id'] != 1 && !in_array($this->_auth['admin_name'],$this -> _allowJudgeList)) {
                                    $this->_error = "组合产品编码为:{$info['goods_sn']}超过产品限价<br>";
                                } else {
                                    $audit_log  += array(
                                        'product_id'   => $apply_info['product_id'],
                                        'price_limit'  => $apply_info['price_limit'],
                                        'product_sn'   => $apply_info['product_sn'],
                                        'product_type' => 2,
                                        'avg_price'    => $info['final_price'],
                                    ); 
                                    $this->addPricelimitLog($audit_log);
                                }
                            }
                        } else {
                            $group_info = $group_goods_db->getGroupInfoByGroupSn($info['goods_sn']);
                            if ($group_info['price_limit'] > 0 && floatval($group_info['price_limit']) > floatval($info['final_price'])) {
                                if ($this -> _auth['group_id'] != '3'  && $this -> _auth['group_id'] != 1  && !in_array($this->_auth['admin_name'],$this -> _allowJudgeList)) {
                                    $this->_error = "组合产品编码为:{$info['goods_sn']}超过产品限价<br>";
                                } else {
                                    $audit_log  += array(
                                        'product_id'   => $group_info['group_id'],
                                        'price_limit'  => $group_info['price_limit'],
                                        'product_sn'   => $group_info['group_sn'],
                                        'product_type' => 2,
                                        'avg_price'    => $info['final_price'],
                                    ); 
                                    $this->addPricelimitLog($audit_log);
                                }
                            }
                        }
                    }
                }
            }
		}

        if (!empty($this->_error)) {
			$this->updateOrderInfoByOrderId($shopOrderID, array('audit_status' => '1'));
			$this->_error = $str . $this->_error;
			return false;
		}

		if (!empty($order_info['audit_status'])) {
			$this->updateOrderInfoByOrderId($shopOrderID, array('audit_status' => '0'));
		}

        return true;
    }

	/**
	 * 根据渠道单ID获取商品数据
	 *
	 * @param    int
	 *
	 * @return   array
	 */
	public function getShopGoodsByShopId($shop_order_id)
	{
		$shop_order_id = intval($shop_order_id);

		if ($shop_order_id < 1) {
			$this->_error = '渠道单号不正确';
			return false;
		}
        
		$datas =  $this->_db->fetchAll("SELECT goods_sn,number,price,discount_price FROM `shop_order_shop_goods` WHERE shop_order_id = '{$shop_order_id}'");
		if (!$datas) {
			$this->_error = '渠道订单不存在';
			return false;
		}
		
		$costAmount = $goodsAmount = 0;
		foreach ($datas as $index => $data) {
		    $goodsAmount += $data['price'] * $data['number'] - $data['discount_price'];
		    
		    if (strlen($data['goods_sn']) == 9) {
                if (!$this -> groupGoodsAPI) {
                    $this -> groupGoodsAPI = new Admin_Models_API_GroupGoods();
                }
                $groupGoods = $this -> groupGoodsAPI -> fetchConfigGoods(array('group_sn' => $data['goods_sn'],'check_status' => '1'));
                if (!$groupGoods) {
        			$this->_error = "组合商品{$data['goods_sn']}不存在";
        			return false;
        		}
        		foreach ($groupGoods as $goods) {
        		    $detail = $this -> getProductInfoByGoodsSN($goods['product_sn']);
        		    $datas[$index]['cost'] += $detail['cost'] * $data['number'] * $goods['number'];
                    $costAmount += $detail['cost'] * $data['number'] * $goods['number'];
        		}
            }
            else {
                $detail = $this -> getProductInfoByGoodsSN($data['goods_sn']);
                $datas[$index]['cost'] = $detail['cost'] * $data['number'];
                $costAmount += $detail['cost'] * $data['number'];
            }
		}

		foreach ($datas as $index => $data) {
		    if ($costAmount > 0) {
		        $datas[$index]['final_price'] = round($data['cost'] / $costAmount * $goodsAmount / $data['number'], 2);
		    }
		    else {
		        $datas[$index]['final_price'] = round($goodsAmount / count($datas) / $data['number'], 2);
		    }
		}
        
		return $datas;
	}

	/**
	 * 根据渠道单ID获取数据
	 *
	 * @param    int
	 *
	 * @return   array
	 */
	public function getOrderInfoByShopId($shop_order_id)
	{
		$shop_order_id = intval($shop_order_id);

		if ($shop_order_id < 1) {
			$this->_error = '渠道单号不正确';
			return false;
		}

		$sql = "SELECT shop_order_id, shop_id, external_order_sn, add_time, audit_status, amount FROM `shop_order_shop` WHERE shop_order_id = '{$shop_order_id}' LIMIT 1";

		return $this->_db->fetchRow($sql);
	}

	/**
	 * 更新渠道订单数据
	 *
	 * @param    int
	 *
	 * @return   array
	 */
	public function updateOrderInfoByOrderId($shop_order_id, $params)
	{
		$shop_order_id = intval($shop_order_id);
		if ($shop_order_id < 1) {
			$this->_error = '渠道单号不正确';
			return false;
		}

		if (count($params) < 1) {
			$this->_error = '没有需要更新的数据';
			return false;
		}

		return $this->_db->update('shop_order_shop', $params, "shop_order_id ='{$shop_order_id}'");

	}
    
    /**
     * 锁定/解锁打印订单
     *
     * @param    int        $shopOrderID
     * @param    string     $adminName
     * @return   void
     */
    function lockOrder($shopOrderID, $adminName)
    {
        $set['lock_admin_name'] = $adminName;
        $this -> _db -> update($this -> _table_order, $set, "shop_order_id = {$shopOrderID}");
    }
    
    /**
     * 更新店铺订单已开票状态
     *
     * @param    int    $shopOrderID
     * @param    int    $status
     * @return   void
     */
    function updateOrderInvoiceStatus($shopOrderID, $status) 
    {
        $this -> _db -> update($this -> _table_order, array('done_invoice' => $status), "shop_order_id = {$shopOrderID}");
    }
    
    /**
     * 更新店铺订单物流公司(只针对待发货)
     *
     * @param    int        $shopOrderID
     * @param    string     $logisticCode
     * @param    array      $order
     * @return   void
     */
    function updateOrderLogistics($shopOrderID, $logisticCode, $order) 
    {
        if (!$this -> logisticAPI) {
            $this -> logisticAPI = new Admin_Models_API_Logistic();
        }
        if ($this -> logisticAPI -> isInDangerousArea($order['addr_province_id'])) {
            $isDangerous = 0;
            foreach ($order['goods'] as $goods) {
                if (strlen($goods['goods_sn']) == 9) {
                    if (!$this -> groupGoodsAPI) {
                        $this -> groupGoodsAPI = new Admin_Models_API_GroupGoods();
                    }
                    $groupGoods = $this -> groupGoodsAPI -> fetchConfigGoods(array('group_sn' => $goods['goods_sn'],'check_status' => '1'));
                }
                else {
                    $detail = $this -> getProductInfoByGoodsSN($goods['goods_sn']);
                }
            }
            
            if ($isDangerous) {
                $logisticCodeArray = $this -> logisticAPI -> getDangerousLogisticCode();
                if (!in_array($logisticCode, $logisticCodeArray)) {
                    $this -> _error = "订单{$order['external_order_sn']}有危险品，不能设置当前物流公司";
                    return false;
                }
            }
        }
        
        if ($order['is_cod'] && !in_array($logisticCode, $this -> getCodLogisticCode())) {
            $this -> _error = "订单{$order['external_order_sn']}是货到付款，不能设置当前物流公司";
            return false;
        }
        
        $this -> _db -> update($this -> _table_order, array('logistic_code' => $logisticCode), "shop_order_id = {$shopOrderID}");
        return true;
    }
    
     /**
     * 更新店铺订单物流状态
     *
     * @param    int    $shopOrderID
     * @param    int    $status
     * @return   void
     */
    function updateOrderLogisticStatus($shopOrderID, $status) 
    {
        $this -> _db -> update('shop_order_shop', array('status' => 10, 'logistic_status' => $status), "shop_order_id = {$shopOrderID}");
    }
    
    /**
     * 设置订单为已结算
     *
     * @return   array
     */
	public function clearOrder($sns, $commissions)
	{
	    if ( $sns && is_array($sns) ) {
	        foreach ($sns as $index => $sn) {
	            $this -> _db -> update($this -> _table_main_order, array('commission' => $commissions[$index] ? $commissions[$index] : 0), "external_order_sn = '{$sn}'");
	            $temp[] = "'{$sn}'";
	        }
	        $this -> _db -> update($this -> _table_order, array('is_settle' => 1), "external_order_sn in (".implode(',', $temp).")");
	        $this -> _db -> execute("update {$this -> _table_order_batch} as t1,{$this -> _table_main_order} as t2 set t1.clear_pay = 1 where t1.order_id = t2.order_id and t2.external_order_sn in (".implode(',', $temp).")");
	    }
	}
	
	/**
     * 获得商品销售统计
     *
     * @return   array
     */
    public function listGoodsSale($search)
    {
        $whereSql = 1;
        if ( $search['shop_id'] ) {
            $whereSql .= " and t1.shop_id = '{$search['shop_id']}'";
        }
        if ( $search['goods_sn'] ) {
            $whereSql .= " and t1.goods_sn = '{$search['goods_sn']}'";
        }
        if ( $search['shop_goods_name'] ) {
            $whereSql .= " and t1.shop_goods_name like '%{$search['shop_goods_name']}%'";
        }
        if ( $search['fromdate'] ) {
            $time = strtotime($search['fromdate']);
            $whereSql .= " and t2.order_time >= $time";
        }
        if ( $search['todate'] ) {
            $time = strtotime($search['todate'].' 23:59:59');
            $whereSql .= " and t2.order_time <= $time";
        }
        
        $sql = "select t1.price,t1.shop_goods_name,sum(t1.number) as number,sum(t1.discount_price ) as discount_total,t1.goods_sn,t2.shop_id,t3.shop_name,t4.product_name as goods_name ,t4.cost from {$this -> _table_order_goods} as t1
                left join {$this -> _table_order} as t2 on t1.shop_order_id = t2.shop_order_id
                left join {$this -> _table} as t3 on t2.shop_id = t3.shop_id
                left join {$this -> _table_product} as t4 on t1.goods_sn = t4.product_sn
                where {$whereSql }
                group by goods_sn
                order by number desc";
        $datas = $this -> _db -> fetchAll($sql);
        if ( $datas ) {
	        foreach ( $datas as $key => $data ) {
	            $datas[$key]['total_amount'] = $data['price'] * $data['number'] - $data['discount_total'];
	            $datas[$key]['avg_price'] = round( $datas[$key]['total_amount'] / $data['number'], 2);
	        }
	    }
	    
        return $datas;
    }
    
    /**
     * 通过shopGoodsID获得goodsSN
     *
     * @return   string
     */
    public function getShopGoodsSNByShopGoodsID($shopID, $shopGoodsID)
    {
        $data = $this -> _db ->fetchRow("select goods_sn from {$this -> _table_goods} where shop_id = {$shopID} and shop_goods_id = {$shopGoodsID}");
        return $data['goods_sn'];
    }
    
    /**
     * 更新订单商品的商品编码
     *
     * @return   array
     */
    public function updateOrderGoodsSN($id, $goodsSN)
    {
        $this -> _db -> update($this -> _table_order_goods, array('goods_sn' => $goodsSN), "id = {$id}");
    }
    
    /**
     * 财务销账
     *
     * @return   array
     */
    public function writeOffOrder($shopOrderIDArray)
    {
        if (is_array($shopOrderIDArray) && (count($shopOrderIDArray) > 0)) {
            $this -> _db -> update($this -> _table_order, array('is_fake' => 2, 'fake_time' => time()), "shop_order_id in (".implode(',', $shopOrderIDArray).")");
        }
    }
    
    /**
     * 获得渠道订单状态
     *
     * @return   array
     */
    public function getOrderStatus($externalOrderSN, $shopID)
    {
        $shop = $this -> _db -> fetchRow("select shop_type,config from {$this -> _table} where shop_id = {$shopID}");
        $shopAPI = Custom_Model_Shop_Base::getInstance($shop['shop_type'], unserialize($shop['config']));
        
        return $shopAPI -> getOrderStatus($externalOrderSN);
    }
    
    public function getGroupGoods($groupSN) 
    {
        return $this -> _db -> fetchRow("select *,group_specification as goods_style,group_goods_name as product_name from {$this -> _table_group_goods} where group_sn = '{$groupSN}'");
    }
    
    /**
     * 合并订单
     *
     * @return   array
     */
    public function mergeOrder($datas) {
        if (!$datas)    return false;
        
        foreach ( $datas as $order) {
    	    $key = $order['addr_province_id'].'-'.$order['addr_city_id'].'-'.$order['addr_area_id'].'-'.$order['addr_address'].'-'.$order['addr_consignee'];
    	    $orderInfo[$key][]  = $order;
    	}
    	
    	foreach ( $orderInfo as $orders) {
    	    unset($order);
    	    $order['goods'] = array();
    	    for ($i = 0; $i< count($orders); $i++) {
    	        $order['addr_consignee'] = $orders[$i]['addr_consignee'];
    	        $order['addr_tel'] = $orders[$i]['addr_tel'];
    	        $order['addr_mobile'] = $orders[$i]['addr_mobile'];
    	        $order['addr_province'] = $orders[$i]['addr_province'];
    	        $order['addr_city'] = $orders[$i]['addr_city'];
    	        $order['addr_area'] = $orders[$i]['addr_area'];
    	        $order['addr_address'] = $orders[$i]['addr_address'];
    	        $order['external_order_sn'][] = $orders[$i]['external_order_sn'];
    	        $order['memo'] .= ' '.$orders[$i]['memo'];
    	        $order['admin_memo'] .= ' '.$orders[$i]['admin_memo'];
    	        $order['amount'] += $orders[$i]['amount'];
    	        $order['goods_amount'] += $orders[$i]['goods_amount'];
    	        $order['shop_id'] = $orders[$i]['shop_id'];
    	        $order['shop_name'] = $orders[$i]['shop_name'];
    	        $order['logistic_code'] = $orders[$i]['logistic_code'];
    	        $order['logistic_no'] = $orders[$i]['logistic_no'];
    	        $order['goods'] = array_merge($order['goods'], $orders[$i]['goods']);
    	        $order['addr_area_id'] = $orders[$i]['addr_area_id'];
    	        $order['order_time'] = $orders[$i]['order_time'];
    	        $order['freight'] += $orders[$i]['freight'];
    	        $order['invoice'] = $orders[$i]['invoice'];
    	        $order['invoice_content'] = $orders[$i]['invoice_content'];
    	        $order['is_cod'] = $orders[$i]['is_cod'];
    	        $order['validate_sn'] = $orders[$i]['validate_sn'];
    	    }
    	    
    	    $order['external_order_sn'] = implode(' ', $order['external_order_sn']);
    	    $result[] = $order;
    	}
    	
    	return $result;
    }
    
    /**
     * 整理订单，将可以合并的订单放在最上面
     *
     * @return   array
     */
    public function getOrderWithMerge($datas) {
        if (!$datas['list'] || count($datas['list']) == 0)  return $datas;
        
        foreach ($datas['list'] as $order) {
            $key = $order['addr_province_id'].'-'.$order['addr_city_id'].'-'.$order['addr_area_id'].'-'.$order['addr_address'].'-'.$order['addr_consignee'];
    	    $orderInfo[$key][]  = $order;
        }
        
        foreach ($orderInfo as $orders) {
            if (count($orders) > 1) {
                for ($i = 0; $i < count($orders); $i++) {
                    if ($i == 0) {
                        $orderIDInfo[$orders[$i]['shop_order_id']] = $orders[$i]['order_time'];
                    }
                    else {
                        $sonOrderInfo[$orders[0]['shop_order_id']][] = $orders[$i]['shop_order_id'];
                    }
                    
                }
            }
        }
        if ($orderIDInfo) {
            arsort($orderIDInfo);
            foreach ($orderIDInfo as $orderID => $orderTime) {
                $orderIDArray[] = $orderID;
                foreach ($sonOrderInfo[$orderID] as $sonOrderID) {
                    $orderIDArray[] = (int)$sonOrderID;
                }
            }
            
            foreach ($orderIDArray as $orderID) {
                foreach ($datas['list'] as $index => $order) {
                    if ($order['shop_order_id'] == $orderID) {
                        $order['repeat'] = '1';
                        if ($sonOrderInfo[$orderID]) {
                            $order['increment'] = '1';
                        }
                        else    $order['increment'] = '0';
                        $result['list'][] = $order;
                        unset($datas['list'][$index]);
                        break;
                    }
                }
            }
        }
        
        if ($datas['list']) {
            foreach ($datas['list'] as $order) {
                $order['increment'] = '1';
                $result['list'][] = $order;
            }
        }
        
        $result['total'] = $datas['total'];

        return $result;
    }
    
    /**
     * 获得分页数据
     *
     * @return   array
     */
    public function getDataWithPage($datas, $page, $pageSize) {
        if (!$datas || count($datas) == 0)  return $datas;
        
        if (!$page) $page = 1;
        
        $startPos = ($page - 1) * $pageSize + 1;
        $endPos = $startPos + $pageSize - 1;
        
        if (count($datas) < $endPos)    $endPos = count($datas);
        
        for ($i = $startPos - 1; $i < $endPos; $i++) {
            $result[] = $datas[$i];
        }
        return $result;
    }
    
    /**
     * 设置订单可能的重复记录标志
     *
     * @return   array
     */
    public function setMergeOrderFlag($datas) {
        if (!$datas || count($datas) == 0)  return $datas;
        
        $shopID = $datas[0]['shop_id'];
        
        foreach ( $datas as $order) {
    	    $key = $order['addr_province_id'].'-'.$order['addr_city_id'].'-'.$order['addr_area_id'].'-'.$order['addr_address'].'-'.$order['addr_consignee'];
    	    $orderInfo[$key][]  = $order['external_order_sn'];
    	    $keyArray[] = "'{$key}'";
    	}
    	
    	$orders = $this -> _db -> fetchAll("select external_order_sn,concat(addr_province_id,'-',addr_city_id,'-',addr_area_id,'-',addr_address,'-',addr_consignee) as keyword from {$this -> _table_order} where shop_id = {$shopID} and status = 2 and status_business = 0 and concat(addr_province_id,'-',addr_city_id,'-',addr_area_id,'-',addr_address,'-',addr_consignee) in (".implode(',', $keyArray).")");
    	if (!$orders)   return $datas;
        
    	foreach ($orders as $order) {
    	    foreach ($orderInfo[$order['keyword']] as $orderSN) {
    	        $flagInfo[$orderSN][] = $order['external_order_sn'];
    	    }
    	}
    	
    	foreach ( $datas as $index => $order) {
    	    if ($flagInfo[$order['external_order_sn']]) {
    	        $datas[$index]['repeat_order_sn'] = implode('<br>', $flagInfo[$order['external_order_sn']]);
    	    }
    	}
    	
    	return $datas;
    }
    
    /**
     * 修改商品，并重新计算商品价格
     *
     * @return   array
     */
    public function updateOrderGoods($order, $post) {
        if (!$order['goods'])   return false;
        
        if ($post['goods_sn_0'] && $post['number_0']) {
	        if (strlen($post['goods_sn_0']) == 9) {
	            $row = $this -> _db -> fetchRow("select group_goods_name as goods_name from {$this -> _table_group_goods} where group_sn = '{$post['goods_sn_0']}'");
	        }
	        else {
	            $row = $this -> _db -> fetchRow("select product_name as goods_name from {$this -> _table_product} where product_sn = '{$post['goods_sn_0']}'");
	        }
	        if (!$row) {
	            die("找不到商品编码!");
	        }
	        
	        $set = array('shop_order_id' => $order['shop_order_id'],
	                     'shop_id' => $order['shop_id'],
	                     'shop_goods_name' => $row['goods_name'],
	                     'shop_goods_id' => 0,
	                     'shop_sku_id' => 0,
	                     'goods_sn' => $post['goods_sn_0'],
	                     'price' => 0,
	                     'number' => $post['number_0'],
	                     'discount_price' => 0,
	                     'add_time' => time(),
	                     'update_time' => time()
	                    );
	        $this -> _db -> insert($this -> _table_order_goods, $set);
	        $this -> addOrderHistory(array('id' => $order['shop_order_id'], 'history' => "添加 {$set['goods_sn']} 商品 * {$set['number']}"));
	    }
        
        $emptyFlag = 1;
        foreach ($order['goods'] as $goods) {
            $tempVar = "number_{$goods['id']}";
            if ($post[$tempVar]) {
                $emptyFlag = 0;
                break;
            }
        }
        if ($emptyFlag && (!$post['goods_sn_0'] || !$post['number_0'])) {
            die("不能删除所有商品!");
        }
        
        $diff = 0;$totalAmount = 0;
        foreach ($order['goods'] as $index => $goods) {
	        $tempVar = "number_{$goods['id']}";
	        if ($post[$tempVar]) {
	            if ($post[$tempVar] != $goods['number']) {
	                $goodsAmount = $goods['price'] * $goods['number'];
	                $salePrice = round( $goodsAmount / $post[$tempVar], 2);
	                if ($salePrice * $post[$tempVar] != $goodsAmount) {
	                    $salePrice = ceil($goodsAmount / $post[$tempVar]);
	                    $goods['discount_price'] += $salePrice * $post[$tempVar] - $goodsAmount;
	                }
	                $set = array('number' => $post[$tempVar],
	                             'price' => $salePrice,
	                             'discount_price' => $goods['discount_price']
	                            );
	                $this -> _db -> update($this -> _table_order_goods, $set, "id = {$goods['id']}");
	                $this -> addOrderHistory(array('id' => $order['shop_order_id'], 'history' => "修改 {$goods['goods_sn']} 商品数量：{$goods['number']} => {$set['number']}"));
	                $order['goods'][$index]['number'] = $set['number'];
	                $order['goods'][$index]['price'] = $set['price'];
	                $order['goods'][$index]['discount_price'] = $set['discount_price'];
	            }
	        }
	        else {
	            $diff += $goods['price'] * $goods['number'] - $goods['discount_price'];
	            $this -> _db -> delete($this -> _table_order_goods, "id = {$goods['id']}");
	            $this -> addOrderHistory(array('id' => $order['shop_order_id'], 'history' => "删除 {$goods['goods_sn']} 商品 * {$goods['number']}"));
	            unset($order['goods'][$index]);
	            continue;
	        }
	        
	        $order['goods'][$index]['amount'] = $order['goods'][$index]['price'] * $order['goods'][$index]['number'] - $order['goods'][$index]['discount_price'];
	        $totalAmount += $order['goods'][$index]['amount'];
	    }
	    
	    if ($diff > 0) {
	        if ($emptyFlag && $post['goods_sn_0'] && $post['number_0']) {
	            $price = ceil($diff / $post['number_0']);
	            if ($diff < $price * $post['number_0']) {
	                $discountPrice = $price * $post['number_0'] - $diff;
	            }
	            $this -> _db -> update($this -> _table_order_goods, array('price' => $price, 'discount_price' => $discountPrice), "shop_order_id = {$order['shop_order_id']}");
	        }
	        else {
    	        $amount = 0;
    	        foreach ($order['goods'] as $index => $goods) {
    	            if ($index == count($order['goods']) - 1) {
    	                $goodsAmount = $diff - $amount;
    	            }
    	            else {
        	            if ($totalAmount == 0) {
        	                $goodsAmount = round($diff / count($order['goods']), 2);
        	            }
        	            else {
        	                $goodsAmount = round($order['goods'][$index]['amount'] / $totalAmount * $diff, 2);
        	                
        	            }
        	            $amount += $goodsAmount;
        	        }
        	        
        	        $salePrice = round(($goodsAmount + $goods['price'] * $goods['number']) / $goods['number'], 2);
        	        if ($salePrice * $goods['number'] != $goods['price'] * $goods['number'] + $goodsAmount) {
        	            $goods['discount_price'] += $salePrice * $goods['number'] - $goods['price'] * $goods['number'] - $goodsAmount;
        	        }
        	        
        	        $set = array('price' => $salePrice,
    	                         'discount_price' => $goods['discount_price']
    	                        );
    	            $this -> _db -> update($this -> _table_order_goods, $set, "id = {$goods['id']}");
    	        }
    	    }
	    }
    }
    
    /**
     * 获得审核订单数量
     *
     * @return   array
     */
    public function getCheckOrderCount() {
        $datas = $this -> _db -> fetchAll("select count(*) as num,shop_id from {$this -> _table_order} where status = 2 and status_business = 0 and sync = 0 group by shop_id");
        if (!$datas)    return false;
        
        foreach ($datas as $order) {
            $result[$order['shop_id']] = $order['num'];
        }
        
        return $result;
   }

    /**
     * 获得打印订单数量
     *
     * @return   array
     */
    public function getPrintOrderCount() {
        $datas = $this -> _db -> fetchAll("select count(*) as num,shop_id from {$this -> _table_order} where status = 2 and status_business = 1 and sync = 0 group by shop_id");
        if (!$datas)    return false;
        
        foreach ($datas as $order) {
            $result[$order['shop_id']] = $order['num'];
        }
        
        return $result;
    }
    
    /**
     * 获得发货订单数量
     *
     * @return   array
     */
    public function getSendOrderCount() {
        $datas = $this -> _db -> fetchAll("select count(*) as num,shop_id from {$this -> _table_order} where status = 2 and status_business = 2 and sync = 0 group by shop_id");
        if (!$datas)    return false;
        
        foreach ($datas as $order) {
            $result[$order['shop_id']] = $order['num'];
        }
        
        return $result;
    }
    
    /**
     * 直接第3方物流发货
     *
     * @return   array
     */
    public function otherLogisticsSend($order) {

        return false;
    }
    
    /**
     * 添加第3方物流异常信息
     *
     * @return   array
     */
    public function addOtherLogisticsMessage($shopOrderID, $errorCode, $message, $adminName = null) {
        if (!$adminName) {
            $adminName = $this -> _auth['admin_name'] ? $this -> _auth['admin_name'] : 'system';
        }
        
        $row = array('shop_order_id' => $shopOrderID,
                     'error_code' => $errorCode,
                     'message' => $message,
                     'admin_name' => $adminName,
                     'add_time' => time(),
                    );
        $this -> _db -> insert($this -> _table_order_logistics, $row);
    }
    
    /**
     * 导出日志
     *
     * @return   array
     */
    public function exportLog($content, $params) {
        $row = array('admin_name' => $this -> _auth['admin_name'],
                     'content' => serialize($content),
                     'params' => serialize($params),
                     'add_time' => time()
                    );
        $this -> _db -> insert('shop_order_shop_log', $row);
    }
    
    /**
     * 检查订单实际库存并添加占用库存
     *
     * @return   array
     */
    public function checkProductStock($order, $holdStock = true, $showMessage = true) {
        if (!$order['goods'])   return false;
        
        $groupGoodsAPI = new Admin_Models_API_GroupGoods();
        
        foreach ($order['goods'] as $goods) {
            if (strlen($goods['goods_sn']) == 9) {
                $groupGoods = $groupGoodsAPI -> fetchConfigGoods(array('group_sn' => $goods['goods_sn'],'check_status' => '1'));
                if (!$groupGoods) {
                    if ($showMessage) {
                        Custom_Model_Message::showMessage("{$order['external_order_sn']}订单的组合商品编码{$goods['goods_sn']}找不到记录", 'reload', -1);
                    }
                    else {
                        return false;
                    }
                }
                
                foreach ($groupGoods as $key => $detail) {
                    $productID = $detail['product_id'];
                    $products[$productID]['number'] += $goods['number'] * $detail['number'];
                    $products[$productID]['goods_sn'] = $detail['goods_sn'];
                }
            }
            else {
                $productID= $this -> getProductIDByGoodsSN($goods['goods_sn']);
                if ( !$productID ) {
                    if ($showMessage) {
                        Custom_Model_Message::showMessage("{$order['external_order_sn']}订单的商品编码{$goods['goods_sn']}找不到Product ID", 'reload', -1);
                    }
                    else {
                        return false;
                    }
                }
                $products[$productID]['number'] += $goods['number'];
                $products[$productID]['goods_sn'] = $goods['goods_sn'];
                $products[$productID]['price'] = $goods['price'];
            }
        }
        
        if (!$this -> _stockAPI) {
            $this -> _stockAPI = new Admin_Models_API_Stock();
        }
        if (!$this -> _outStockAPI) {
            $this -> _outStockAPI = new Admin_Models_API_OutStock();
        }
        if (!$this -> _replenishmentAPI) {
            $this -> _replenishmentAPI = new Admin_Models_API_Replenishment();
        }
        
        $message = '';
        foreach ($products as $productID => $product) {
            if ($this -> _stockAPI -> checkPreSaleProductStock($productID, $product['number'])) {
                $this -> _replenishmentAPI -> cancelProductReplenish($order['shop_order_id'], $productID);
            }
            else {
                $this -> _replenishmentAPI -> applyReplenish($order['shop_order_id'], $productID, $product['number']);
                $message .= "{$order['external_order_sn']}订单的商品编码{$product['goods_sn']}库存不足<br>";
            }
        }
        if ($message) {
            if ($showMessage) {
                Custom_Model_Message::showMessage($message, 'reload', -1);
            }
            else {
                return false;
            }
        }
        
        if ($holdStock) {
            foreach ($products as $productID => $product) {
                //$this -> _stockAPI -> holdSaleProductStock($productID, $product['number']);
                
                $details[] = array('product_id' => $productID,
    	                           'status_id' => 2,
    	                           'number' => $product['number'],
    	                           'shop_price' => $product['price'] ? $product['price'] : 0,
    	                          );
            }
            
            $billNo = $this -> getOutStockSN($order);
            $bill = array('lid' => $order['logic_area'] ? $order['logic_area'] : 1,
    		              'bill_no' => $billNo,
                          'bill_type' => 1,
                          'bill_status' => 0,
                          'supplier_id' => 0,
    			          'remark' => '渠道销售订单出库',
                          'add_time' => time(),
                          'admin_name' => $this -> _auth['admin_name'],
                         );
            //return $this -> _outStockAPI -> insertApi($bill, $details, $order['logic_area'] ? $order['logic_area'] : 1, true));
            if ($this -> _outStockAPI -> insertApi($bill, $details, $order['logic_area'] ? $order['logic_area'] : 1, false)) {
                $this -> _outStockAPI -> holdOutStock($billNo);
            }
            else {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * 释放订单占用库存
     *
     * @return   array
     */
    public function releaseProductStock($order, $background = false) {
        if (!$order['goods'])   return true;
        
        if (!$this -> _outStockAPI) {
            $this -> _outStockAPI = new Admin_Models_API_OutStock();
        }
        
        $this -> _outStockAPI -> deleteExternal($this -> getOutStockSN($order));
        
        return true;
    }
    
    /**
     * 发货后短信通过
     *
     * @return   array
     */
    public function sendMessage($order) {
        if ($order['addr_mobile']) {
		    if ($order['shop_id'] != 24) {
                $smsAPI = new  Custom_Model_Sms();
                $smsAPI -> send($order['addr_mobile'], "{$order['addr_consignee']}，您在{$order['shop_name']}的订单{$order['external_order_sn']}已发出，快递为{$order['logistic_name']}，运单号为{$order['logistic_no']}，请注意查收");
		    }
        }
    }
    
    /**
     * 获得物流公司标识颜色
     *
     * @return   array
     */
    public function getLogisticColor() {
        $result['externalself'] = '#000080';
        $result['ht'] = '#800080';
        $result['yt'] = '#800000';
        $result['sf'] = '#808000';
        $result['ems'] = '#00808';
        $result['zjs'] = '#00FF00';
        
        return $result;  
    }
    
    /**
     * 获得货到付款物流公司
     *
     * @return   array
     */
    public function getCodLogisticCode() {
        return array('sf', 'ems', 'externalself');
    }

    /**
     * 获得出库单号
     *
     * @return   string
     */
    public function getOutStockSN($order) {
        return $order['shop_id'].':'.$order['external_order_sn'];
    }
    
    /**
     * 获得出库单号对应的订单号
     *
     * @return   string
     */
    public function getOrderSNByOutStockSN($billNo) {
        return substr($billNo, strpos($billNo, ':') + 1, strlen($billNo));
    }
    
    /**
     * 更新刷单商品
     *
     * @return   array
     */
    public function updateFakeOrderGoods($order) {
        $this -> _db -> delete('shop_order_shop_goods', "shop_order_id = {$order['shop_order_id']}");
        $row = array('shop_order_id' => $order['shop_order_id'],
                     'shop_id' => $order['shop_id'],
                     'shop_goods_name' => '虚拟刷单商品',
                     'goods_sn' => 'N9901001',
                     'price' => $order['goods_amount'],
                     'number' => 1,
                     'discount_price' => 0,
                     'add_time' => time(), 
                     'update_time' => time(),
                    );
        $this -> _db -> insert('shop_order_shop_goods', $row);
        
        return array($row);
    }
    
     /**
     * 自动设置刷单
     *
     * @return   array
     */
    public function autoSetFake(&$order) {
        if (!isset($this -> _fakeInfo[$order['shop_id']])) {
            if ($order['shop_id'] == 21) {
                $this -> _fakeInfo[$order['shop_id']][] = '陈婷';
				$this -> _fakeInfo[$order['shop_id']][] = '孙超';
                $this -> _fakeInfo[$order['shop_id']][] = '李伟';
                $this -> _fakeInfo[$order['shop_id']][] = '蓝晶晶';
            }
            else if ($order['shop_id'] == 22) {
                $this -> _fakeInfo[$order['shop_id']][] = '吴昊';
            }
            else if ($order['shop_id'] == 23) {
                $this -> _fakeInfo[$order['shop_id']][] = '韩杨';
            }
            else if ($order['shop_id'] == 24) {
                $this -> _fakeInfo[$order['shop_id']][] = '吴昊';
            }
            else if ($order['shop_id'] == 31) {
                $this -> _fakeInfo[$order['shop_id']][] = '01055053111';
            }
            else if ($order['shop_id'] == 41) {
                $this -> _fakeInfo[$order['shop_id']][] = '罗菊';
				$this -> _fakeInfo[$order['shop_id']][] = '陈婷';
            }
            else if ($order['shop_id'] == 51) {
                $this -> _fakeInfo[$order['shop_id']][] = '蓝晶晶';
            }
        }
        
        foreach ($this -> _fakeInfo[$order['shop_id']] as $name) {
            if ($order['shop_id'] == 31) {
                if ($order['addr_tel'] == $name) {
                    $order['is_fake'] = 1;
                    return true;
                }
            }
            else {
                if (strpos($order['memo'], $name) !== false) {
                    $order['is_fake'] = 1;
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * 自动设置内购审核不通过
     *
     * @return   array
     */
    public function autoSetInternal(&$order) {
        if ($order['shop_id'] == 24 && $order['status'] == 2) {
            if (strpos($order['memo'], '黄园园') !== false) {
                $order['status_business'] = 9;
            }
        }
    }

	/**
     * 统计渠道产品限价
     * 
     *
     * @return   int
     */
	public function getShopGoodsLimitPriceTotal()
	{
		$sql = "SELECT COUNT(*) AS count FROM `{$this->_table_goods}` g 
				LEFT JOIN `shop_product` p ON g.goods_sn = p.product_sn
				WHERE g.shop_price < p.price_limit";

		return $this->_db->fetchOne($sql);
	}

	/**
     * 统计渠道订单限价
     * 
     *
     * @return   int
     */
	public function getShopOrderLimitPriceTotal()
	{
		$sql = "SELECT COUNT(*) AS count FROM `{$this->_table_order}`  WHERE  audit_status=1";

		return $this->_db->fetchOne($sql);
	}

    /**
	 * 添加限价日志
	 *
	 * @param    array
	 *
	 * @return   boolean
	 **/
    public function addPricelimitLog($params)
    {
        $audit_log = array(
            'shop_id'      => $params['shop_id'],
            'channel_type' => $params['channel_type'],
            'batch_sn'     => $params['batch_sn'],
            'price_order'  => $params['price_order'],
            'audit_id'     => $params['audit_id'],
            'audit_by'     => $params['audit_by'],
            'audit_ts'     => $params['audit_ts'],
            'product_id'   => $params['product_id'],
            'price_limit'  => $params['price_limit'],
            'product_type' => $params['product_type'],
            'product_sn'   => $params['product_sn'],
            'avg_price'    => $params['avg_price'],
        );

        return (bool) $this->_db->insert('shop_order_pricelimit_log', $audit_log);
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
	
?>