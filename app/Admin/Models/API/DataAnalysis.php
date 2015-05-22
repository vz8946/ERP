<?php

 
class Admin_Models_API_DataAnalysis
{
	public function __construct()
	{
		$this->_db = new Admin_Models_DB_DataAnalysis();
	}
	/**
     * 取得订单商品信息
     *
     * @param    void
     * @return   array
     */
	public function getOrderGoods($search,$page, $pageSize)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());      
        $search = Custom_Model_Filter::filterArray($search, $filterChain);
        if ($search != null ) {
			($search['fromdate']) ? $where = " AND g.add_time >=" . strtotime($search['fromdate']) : "";
			($search['todate']) ? $where .= " AND g.add_time <" . (strtotime($search['todate'])+86400) : "";
			($search['goods_name']) ? $where .= " AND (g.goods_name LIKE '%" . $search['goods_name'] . "%')" : "";
			($search['cat_name']) ? $where .= " AND g.cat_name = '" . $search['cat_name']."'"  : "";
			($search['order_sn']) ? $where .= " AND  g.order_sn = '" . $search['order_sn'] ."'": "";
			($search['logistic_name']) ? $where .= " AND  o.logistic_name = '" . $search['logistic_name'] ."'": "";
			($search['pay_name']) ? $where .= " AND  o.pay_name = '" . $search['pay_name'] ."'": "";
			($search['addr_province']) ? $where .= " AND  o.addr_province = '" . $search['addr_province'] ."'": "";
			($search['addr_city']) ? $where .= " AND  o.addr_city = '" . $search['addr_city'] ."'": "";
		}
		return $this -> _db -> getOrderGoods($where, $page, $pageSize);
	}

 	/**
     * 销售订单查询
     *
     * @param    void
     * @return   array
     */
	public function getSaleOrder($search,$page, $pageSize)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());      
        $search = Custom_Model_Filter::filterArray($search, $filterChain);
        if ($search != null ) {
			($search['fromdate']) ? $where = " AND add_time >=" . strtotime($search['fromdate']) : "";
			($search['todate']) ? $where .= " AND add_time <" . (strtotime($search['todate'])+86400) : "";
			($search['order_sn']) ? $where .= " AND  order_sn = '" . $search['order_sn'] ."'": "";
			($search['logistic_name']) ? $where .= " AND  logistic_name = '" . $search['logistic_name'] ."'": "";
			($search['pay_name']) ? $where .= " AND  pay_name = '" . $search['pay_name'] ."'": "";
			($search['addr_province']) ? $where .= " AND  addr_province = '" . $search['addr_province'] ."'": "";
			($search['addr_city']) ? $where .= " AND  addr_city = '" . $search['addr_city'] ."'": "";
			($search['status'] != '') ? $where .= " AND  status = '" . $search['status'] ."'": "";
			($search['type'] != '') ? $where .= " AND  type = '" . $search['type'] ."'": "";
			($search['status_logistic'] != '') ? $where .= " AND  status_logistic = '" . $search['status_logistic'] ."'": "";
		}
		return $this -> _db -> getSaleOrder($where, $page, $pageSize);
	}


     /**
     * 导出订单商品信息数据
     *
     * @param    array    $search
     * @return   void
     */
	public function getExportGoods($search)
	{

        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());      
        $search = Custom_Model_Filter::filterArray($search, $filterChain);

        if ($search != null ) {
			($search['fromdate']) ? $where = " AND g.add_time >=" . strtotime($search['fromdate']) : "";
			($search['todate']) ? $where .= " AND g.add_time <" . (strtotime($search['todate'])+86400) : "";
			($search['goods_name']) ? $where .= " AND (g.goods_name LIKE '%" . $search['goods_name'] . "%')" : "";
			($search['cat_name']) ? $where .= " AND g.cat_name = '" . $search['cat_name']."'"  : "";
			($search['order_sn']) ? $where .= " AND  g.order_sn = '" . $search['order_sn'] ."'": "";
			($search['logistic_name']) ? $where .= " AND  o.logistic_name = '" . $search['logistic_name'] ."'": "";
			($search['pay_name']) ? $where .= " AND  o.pay_name = '" . $search['pay_name'] ."'": "";
			($search['addr_province']) ? $where .= " AND  o.addr_province = '" . $search['addr_province'] ."'": "";
			($search['addr_city']) ? $where .= " AND  o.addr_city = '" . $search['addr_city'] ."'": "";
		}
		$datas = $this -> _db -> getExportGoods($where , 'xls');
		$excel = new Custom_Model_Excel();
		$excel -> send_header('goodsinfo.xls');
		$excel -> xls_BOF();

		$title = array('商品ID', '商品名', '数量', '单号', '省份', '城市', '支付方式', '配送物流', '分类', '销售价格', '下单时间' ,'联盟ID ');
        $col = count($title);
        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
		foreach ($datas as $k => $v)
        {
			$v['add_time'] = date('Y-m-d', $v['add_time']);
			$row = array($v['goods_id'], 
			             $v['goods_name'], 
                         $v['number'], 
                         $v['order_sn'],
                         $v['addr_province'],
			             $v['addr_city'],
						 $v['pay_name'],
						 $v['logistic_name'],
						 $v['cat_name'],
						 $v['sale_price'],
						 $v['add_time'],
				         $v['parent_id']
				);
			for ($i = 0; $i < $col; $i++) {
			    $excel -> xls_write_label($k+1, $i, $row[$i]);
			}
			flush();
		    ob_flush();
			unset($row);
        }
        unset($datas);
		$excel -> xls_EOF();
	}

 
	/**
	 * 生成 xls
	 */
	public function exportOrder($where){
        $status = array(0 => '有效单', 
                        1 => '取消单', 
                        2 => '无效单');
        $status_logistic = array(0 => '未确认',
                                 1 => '待收款', 
                                 2 => '待发货', 
                                 3 => '已发货在途', 
                                 4 => '已发货签收', 
                                 5 => '已发货拒收',
                                 6 => '部分签收');
        $type=array(
        	0=>'前台下单',
        	1=>'电话下单',
        	2=>'淘宝下单',
        	3=>'内部下单',
        	4=>'当当下单',
        	5=>'赠送下单',
        	6=>'非会员下单',
        	7=>'团购下单',
        	8=>'员工平台下单',
        );

        $excel= new Custom_Model_GenExcel();
		
    	$title = array('订单号', '下单日期时间', '发货日期时间', '下单类型', '订单类型', '订单状态', 
                       '客户id', '收货人', '联系方式', '付款方式', '配送方式',  
                       '省份', '城市', '订单金额', '订单商品金额', '物流费用', 
                       '调整金额', '需支付金额', '已支付金额', '换货单退货金额', '分成比例', '订单来源', '订单商品', '', '');
        $arr[]=$title;
    	$datas = $this -> _db -> getOrderBatch($where);
		foreach ($datas as $k => $v)
        {
    		//Start::取得每个订单的商品
			$goods = $this -> _db -> getOneOrderGoods($v['order_id']);
			$goodsStr = '';
			$SpecialChar = '&#10;';
			foreach ($goods as $val){
				$goodsStr .= $val['goods_name'].' '.$val['number'].$SpecialChar;
			}
			//End::取得每个订单的商品
			
            $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            if ($v['logistic_time']) {
                $v['logistic_time'] = date('Y-m-d H:i:s', $v['logistic_time']);
            }
            $row = array("'".$v['batch_sn']."'", $v['add_time'], $v['logistic_time'], $type[$v['type']], $status[$v['status']], $status_logistic[$v['status_logistic']],
                         $v['user_id'], $v['addr_consignee'], $v['addr_mobile'] . '/' . $v['addr_tel'], $v['pay_name'], $v['logistic_name'],
                         $v['addr_province'], $v['addr_city'], $v['price_order'], $v['price_goods'], $v['price_logistic'], 
                         $v['price_adjust'], $v['price_pay'], $v['price_payed'], $v['price_from_return'], $v['proportion'],
                         $v['u_name'], $goodsStr, '', '');
			$arr[]=$row;
			unset($row);
        }
        unset($datas);
        $excel->addArray ($arr);
		$excel->generateXML ($where['fromdate'] .'-'. $where['todate'].'-orderList.xls');
    }
    
    /**
	 * 按天获得新会员数
	 */
	public function getNewUserByDay($where)
	{
	    if ( $where['fromdate'] ) {
	        $fromdate = strtotime($where['fromdate']);
	        $sql .= " and t2.add_time >= {$fromdate}";
	    }
	    if ( $where['todate'] ) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $sql .= " and t2.add_time <= {$todate}";
	    }
	    $datas = $this -> _db -> getUserRegTime($sql);
	    if ( !$datas )  return array();
	    foreach ( $datas as $data ) {
	        $date = date($where['dateFormat'], $data['add_time']);
	        $result[$date]['RegUserCount']++;
	        if ( $data['parent_id'] ) {
	            $result[$date]['CPSRegUserCount']++;
	        }
	        else    $result[$date]['FrontRegUserCount']++;
	    }
	    
	    return $result;
	}
	
	/**
	 * 按天获得订单会员数
	 */
	public function getUserOrderCountByDay($where)
	{
	    if ( $where['fromdate'] ) {
	        $fromdate = strtotime($where['fromdate']);
	        $sql .= "t1.add_time >= {$fromdate} and ";
	    }
	    if ( $where['todate'] ) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $sql .= "t1.add_time <= {$todate} and ";
	    }
	    $datas = $this -> _db -> getOrder($sql);
	    if ( !$datas )  return array();
	    
	    $dateUserIDArray = array();
	    foreach ( $datas as $data ) {
	        $date = date($where['dateFormat'], $data['add_time']);
	        if ( !$dateUserIDArray[$date] ) {
	            $dateUserIDArray[$date] = array();
	        }
	        
	        $dateUserIDArray[$date][$data['user_id']]++;
	    }
	    
	    foreach ($dateUserIDArray as $date => $dateUserID) {
	        foreach ($dateUserID as $userID => $count) {
    	        if ($count > 1) {
    	            $result[$date]['UserMoreOrderCount']++;
    	            $result[$date]['MoreOrderCount']++;
    	        }
    	        $result[$date]['UserOrderCount']++;
    	        $result[$date]['OrderCount']++;
    	    }
	    }
	    
	    return $result;
	}
	
	/**
	 * 按会员获得订单信息
	 */
	public function getUserOrder($where)
	{
	    if ( $where['fromdate'] ) {
	        $fromdate = strtotime($where['fromdate']);
	        $sql .= "t2.add_time >= {$fromdate} and ";
	    }
	    if ( $where['todate'] ) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $sql .= "t2.add_time <= {$todate} and ";
	    }
	    if ( $where['user_name'] ) {
	        $sql .= "t2.user_name like '%{$where['user_name']}%' and ";
	    }
	    if ( $where['order_count'] || $where['real_amount'] ) {
	        $sql1 = $sql."t1.status = 0 and ";
	        if ( $where['order_count'] ) {
	            $haveSql = "order_count >= {$where['order_count']} and ";
	        }
	        if ( $where['real_amount'] ) {
	            $haveSql = "real_amount >= {$where['real_amount']} and ";
	        }
	        $haveSql = substr($haveSql, 0, -5);
	        $datas = $this -> _db -> getUserOrder($sql1, $haveSql);
	        if ( !$datas )  return false;
	        
	        foreach ( $datas as $data ) {
	            $userIDArray[] = $data['user_id'];
	        }
	        $sql .= "t2.user_id in (".implode(',', $userIDArray).") and ";
	    } 
        
	    $datas = $this -> _db -> getUserOrder($sql);
	    if ( !$datas )  return false;
	    
	    foreach ( $datas as $data ) {
	        $result[$data['user_id']]['user_name'] = $data['user_name'];
	        $result[$data['user_id']]['TotalOrderCount'] += $data['order_count'];
	        if ($data['status'] == 0) {
	            $result[$data['user_id']]['OrderCount'] += $data['order_count'];
	            $result[$data['user_id']]['TotalAmount'] += $data['total_amount'];
	            $result[$data['user_id']]['RealAmount'] += $data['real_amount'];
	        }
	        $result[$data['user_id']]['TotalOrderCount'] += $data['order_count'];
	    }
	    
	    return $result;
	}
	
	/**
	 * 获得用户订单商品
	 */
	public function getUserOrderGoods($where)
	{
	    if ( $where['fromdate'] ) {
	        $fromdate = strtotime($where['fromdate']);
	        $sql .= " and t2.add_time >= {$fromdate}";
	    }
	    if ( $where['todate'] ) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $sql .= " and t2.add_time <= {$todate}";
	    }
	    if ( $where['user_name'] ) {
	        $sql .= " and t2.user_name like '%{$where['user_name']}%'";
	    }
	    $datas = $this -> _db -> getUserOrderGoods($sql);
	    if ( !$datas )  return false;
	    
	    foreach ( $datas as $data ) {
	        $result[$data['user_id']][$data['goods_id']]['goods_name'] = $data['goods_name'];
	        $result[$data['user_id']][$data['goods_id']]['number'] += $data['number'];
	    }
	    return $result;
	}
	
	/**
	 * 按天获得订单汇总
	 */
	public function getOrderInfoByDay($where)
	{
	    $jiankangSQLArray['jiankang'] = "t2.shop_id = 1";
	    $jiankangSQLArray['gift'] = "t2.shop_id = 0 and t2.user_name = 'gift'";
	    $jiankangSQLArray['internal'] = "t2.shop_id = 0 and t2.user_name = 'internal'";
	    $jiankangSQLArray['other'] = "t2.shop_id = 0 and t2.user_name = 'other'";
	    
	    $callSQLArray['call_in'] = "t2.shop_id = 2 and t1.type = 10";
	    $callSQLArray['call_out'] = "t2.shop_id = 2 and t1.type = 11";
	    $callSQLArray['call_tq'] = "t2.shop_id = 2 and t1.type = 12";
	    
	    if ($where['type']) {
    	    if ($where['entry'] == 'self') {
    	        $whereSQL .= ' and '.$jiankangSQLArray[$where['type']];
    	    }
    	    else if ($where['entry'] == 'call') {
    	        $whereSQL .= ' and '.$callSQLArray[$where['type']];
    	    }
    	    else if ($where['entry'] == 'channel') {
    	        $whereSQL .= " and t2.shop_id = '{$where['type']}'";
    	    }
    	    else if ($where['entry'] == 'distribution') {
    	        $whereSQL .= " and t2.shop_id = 0 and t2.user_name like '{$where['type']}'";
    	    }
    	    else if ($where['entry'] == 'tuan') {
    	        $whereSQL .= " and t2.shop_id = '{$where['type']}'";
    	    }
	    }
	    else if ($where['entry']) {
	        $sqlArray['self'] = " and (";
	        foreach ($jiankangSQLArray as $sql) {
	            $sqlArray['self'] .= "({$sql}) or ";
	        }
	        $sqlArray['self'] .= "0)";
	        $sqlArray['call'] .= " and t2.shop_id = 2 and t1.type in (10,11,12)";
	        $sqlArray['channel'] = " and t2.shop_id > 0 and t3.shop_type <> 'jiankang' and t3.shop_type <> 'tuan' and t3.shop_type <> 'credit'";
	        $sqlArray['distribution'] = " and t2.shop_id = 0 and (t2.user_name like '%_distribution' or t2.user_name = 'batch_channel')";
	        $sqlArray['tuan'] = " and (t3.shop_type = 'tuan' or t3.shop_type = 'credit')";
	        $whereSQL .= " {$sqlArray[$where['entry']]}";
	    }

	    if ( $where['fromdate'] ) {
	        $fromdate = strtotime($where['fromdate']);
	        $whereSQL .= " and t1.add_time >= {$fromdate}";
	    }
	    if ( $where['todate'] ) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $whereSQL .= " and t1.add_time <= {$todate}";
	    }
	    if ( $where['pay_fromdate'] ) {
	        $fromdate = strtotime($where['pay_fromdate']);
	        $whereSQL .= " and t1.pay_time >= {$fromdate}";
	    }
	    if ( $where['pay_todate'] ) {
	        $todate = strtotime($where['pay_todate'].' 23:59:59');
	        $whereSQL .= " and t1.pay_time <= {$todate}";
	    }
	    if ( $where['shop_id'] !== null && $where['shop_id'] !== '' ) {
	        $whereSQL .= " and t2.shop_id = '{$where['shop_id']}' ";
	    }
	    if ( $where['shop_ids'] ) {
	        $whereSQL .= " and t2.shop_id in (".implode(',', $where['shop_ids']).") ";
	    }
	    if ( $where['user_name'] ) {
	        $whereSQL .= " and t2.user_name = '{$where['user_name']}'";
	    }
	    if ($where['province']){
            if ( is_array($where['province']) ) {
                if (in_array(3982, $where['province'])) {
                    $where['province'][] = 0;
                }
                $whereSQL .= " and t1.addr_province_id in (".implode(',', $where['province']).")";
            }
            else {
                if ($where['province'] == 3982) {
                    $whereSQL .= " and t1.addr_province_id in (0,3982)";
                }
                else    $whereSQL .= " and t1.addr_province_id = {$where['province']}";
            }
        }

	    $datas = $this -> _db -> getAllOrderBatch($whereSQL);
	    if ( !$datas )  return false;
	    
	    foreach ( $datas as $data ) {
	        $date = date($where['dateFormat'], $data['add_time']);
	        if ( $where['entry'] == 'internal' ) {
	            $key = $data['user_name'];
	        }
	        else {
	            $key = $data['shop_id'];
	        }
	        $result[$date][$key]['TotalCount']++;
	        $result[$date][$key]['shop_name'] = $data['shop_name'];
	        $result[$date][$key]['user_name'] = $data['user_name'];
	        if ( $data['status'] == 0 || $data['status'] == 4) {
	            $result[$date][$key]['ValidCount']++;
	            $result[$date][$key]['Amount'] += $data['price_order'];
	            $result[$date][$key]['PaidAmount'] += $data['price_payed'];
	            $result[$date][$key]['LogisticAmount'] += $data['price_logistic'];
	            if ( in_array($data['status_logistic'], array(3,4,5)) ) {
	                $result[$date][$key]['SentCount']++;
	            }
	        }
	        if ( $data['status_return'] == 1 ) {
	            $result[$date][$key]['ReturnCount']++;
	        }
	    }
	    
	    $datas = $this -> _db -> getOrderReturnAmount($whereSQL);
	    if ( $datas ) {
	        foreach ( $datas as $data ) {
	            $date = date($where['dateFormat'], $data['add_time']);
	            if ( $where['entry'] == 'internal' ) {
    	            $key = $data['user_name'];
    	        }
    	        else {
    	            $key = $data['shop_id'];
    	        }
	            $tempData[$date][$key] += abs($data['return_amount']);
	        }
	    }
	    
	    foreach ($result as $date => $data1) {
	        foreach ($data1 as $shopID => $data) {
	            $result[$date][$shopID]['ReturnAmount'] = $tempData[$date][$shopID];
	            $result[$date][$shopID]['TotalAmount'] = $result[$date][$shopID]['Amount'] + $tempData[$date][$shopID];
	        }
	    }
	    
	    return $result;
	}
	
	/**
	 * 产品销售统计
	 */
	public function getGoodsDaily($where)
	{
	    $whereSQL = "t2.status in (0,4) and t2.status_logistic in (3,4,5) and t5.is_gift_card = 0";
	    
	    if ($where['fromdate']) {
	        $whereSQL .= " and t1.finish_time >= ".strtotime($where['fromdate']);
	    }
	    if ($where['todate']) {
	        $whereSQL .= " and t1.finish_time <= ".strtotime($where['todate'].' 23:59:59');
	    }
	    if ($where['product_sn']) {
	        $whereSQL .= " and t5.product_sn = '{$where['product_sn']}'";
	    }
	    if ($where['product_name']) {
	        $whereSQL .= " and t5.product_name like '%{$where['product_name']}%'";
	    }
	    if ($where['supplier_id']) {
            $supplierAPI = new Admin_Models_API_Supplier();
            $supplier = array_shift($supplierAPI -> getSupplier("supplier_id = {$where['supplier_id']}"));
            if ($supplier['product_ids']) {
                $whereSQL .= " and t5.product_id in ({$supplier['product_ids']})";
            }
            else {
                return false;
            }
        }
	    
	    $jiankangSQLArray['jiankang'] = "t3.shop_id = 1";
	    $jiankangSQLArray['gift'] = "t3.shop_id = 0 and t3.user_name = 'gift'";
	    $jiankangSQLArray['internal'] = "t3.shop_id = 0 and t3.user_name = 'internal'";
	    $jiankangSQLArray['other'] = "t3.shop_id = 0 and t3.user_name = 'other'";
	    
	    if ($where['type']) {
    	    if ($where['entry'] == 'self') {
    	        $whereSQL .= ' and '.$jiankangSQLArray[$where['type']];
    	    }
    	    else if ($where['type'] == 'call_in') {
    	        $whereSQL .= " and t3.shop_id = 2 and t2.type = 10";
    	    }
    	    else if ($where['type'] == 'call_out') {
    	        $whereSQL .= " and t3.shop_id = 2 and t2.type = 11";
    	    }
    	    else if ($where['type'] == 'call_tq') {
    	        $whereSQL .= " and t3.shop_id = 2 and t2.type = 12";
    	    }
    	    else if ($where['entry'] == 'channel') {
    	        $whereSQL .= " and t3.shop_id = '{$where['type']}'";
    	    }
    	    else if ($where['entry'] == 'distribution') {
    	        $whereSQL .= " and t3.shop_id = 0 and t3.user_name like '{$where['type']}'";
    	    }
    	    else if ($where['entry'] == 'tuan') {
    	        $whereSQL .= " and t3.shop_id = '{$where['type']}'";
    	    }
	    }
	    else if ($where['entry']) {
	        $sqlArray['self'] = " and (";
	        foreach ($jiankangSQLArray as $sql) {
	            $sqlArray['self'] .= "({$sql}) or ";
	        }
	        $sqlArray['self'] .= "0)";
	        $sqlArray['call'] .= " and t3.shop_id = 2";
	        $sqlArray['channel'] = " and t3.shop_id > 0 and t6.shop_type <> 'jiankang' and t6.shop_type <> 'tuan' and t6.shop_type <> 'credit'";
	        $sqlArray['distribution'] = " and t3.shop_id = 0 and (t3.user_name like '%_distribution' or t3.user_name = 'batch_channel')";
	        $sqlArray['tuan'] = " and (t6.shop_type = 'tuan' or t6.shop_type = 'credit')";
	        $whereSQL .= " {$sqlArray[$where['entry']]}";
	    }
	    
	    $datas = $this -> _db -> getProductOutstock($whereSQL);
	    if ( !$datas )  return false;
	    
	    $tempDatas = $this -> _db -> getProductReturnAndEqPrice($whereSQL);
	    if ($tempDatas) {
	        foreach ($tempDatas as $temp) {
	            $productInfo[$temp['product_id']] = $temp;
	        }
	    }
	    
	    foreach ($datas as $index => $data) {
	        $productID = $data['product_id'];
	        if ($productInfo[$productID]['number']) {
	            $datas[$index]['AveragePrice'] = round($productInfo[$productID]['amount'] / $productInfo[$productID]['number'], 2);
	        }
	        else {
	            $datas[$index]['AveragePrice'] = 0;
	        }
	        $datas[$index]['ReturnCount'] = $productInfo[$productID]['return_number'];
	        $datas[$index]['RealOutStockCount'] = $data['OutStockCount'] - $datas[$index]['ReturnCount'];
	        $datas[$index]['TotalAmount'] = bcsub($productInfo[$productID]['amount'], round($datas[$index]['AveragePrice'] * $productInfo[$productID]['return_number'], 2), 2);
	        $datas[$index]['TotalCost'] = bcsub($data['cost_amount'], round($data['cost_amount'] / $data['OutStockCount'], 2) * $productInfo[$productID]['return_number'], 2);
	        $datas[$index]['TotalNoTaxCost'] = bcsub($data['no_tax_cost_amount'], round($data['no_tax_cost_amount'] / $data['OutStockCount'], 2) * $productInfo[$productID]['return_number'], 2);
	        $datas[$index]['BenefitAmount'] = bcsub($datas[$index]['TotalAmount'], $datas[$index]['TotalCost'], 2);
	        if ($datas[$index]['TotalAmount'] > 0) {
	            $datas[$index]['BenefitRate'] = round(($datas[$index]['TotalAmount'] - $datas[$index]['TotalCost']) / $datas[$index]['TotalAmount'] * 100, 2);
	        }
	        else {
	            $datas[$index]['BenefitRate']  = 0;
	        }
	    }
	    
	    return $datas;
	}
	
	/**
	 * 按快递公司获得运输单数量
	 */
	public function getLogisticTransport($where)
	{
	    if ( $where['fromdate'] ) {
	        $fromdate = strtotime($where['fromdate']);
	        $sql .= " and send_time >= {$fromdate}";
	    }
	    if ( $where['todate'] ) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $sql .= " and send_time <= {$todate}";
	    }
	    if ( $where['shop_id'] !== null && $where['shop_id'] !== '' ) {
	        $sql .= " and t3.shop_id = '{$where['shop_id']}' ";
	    }
	    if ($where['province']){
            if ( is_array($where['province']) ) {
                if (in_array(3982, $where['province'])) {
                    $where['province'][] = 0;
                }
                $sql .= " and t2.addr_province_id in (".implode(',', $where['province']).")";
            }
            else {
                if ($where['province'] == 3982) {
                    $sql .= " and t2.addr_province_id in (0,3982)";
                }
                else    $sql .= " and t2.addr_province_id = {$where['province']}";
            }
        }

	    $datas = $this -> _db -> getTransport($sql);
	    if ( !$datas )  return false;
	    
	    $matchDatas = $this -> _db -> getMatchTransportCount($sql);
	    if ($matchDatas) {
	        foreach ( $matchDatas as $data ) {
	            $matchCount[$data['logistic_code']] = $data['number'];
	        }
	    }
	    
	    foreach ( $datas as $data ) {
	        $result[$data['logistic_code']]['logistic_name'] = $data['logistic_name'];
	        $result[$data['logistic_code']]['TotalCount'] += $data['number'];
	        if ( $data['logistic_status'] == 2 || $data['logistic_status'] == 6 ) {
	            $result[$data['logistic_code']]['SignCount'] += $data['number'];
	        }
	        else if ( $data['logistic_status'] == 1 ) {
	            $result[$data['logistic_code']]['NotSignCount'] += $data['number'];
	        }
	        else {
	            $result[$data['logistic_code']]['RefuseCount'] += $data['number'];
	        }
	        $result[$data['logistic_code']]['TotalPrice'] += $data['logistic_price'];
	    }
	    
	    foreach ($result as $logistic_code => $data) {
	        $result[$logistic_code]['MatchCount'] = $matchCount[$logistic_code];
	    }
	    
	    return $result;
	}
	
	/**
	 * 按订单类型获得汇总订单信息
	 */
	public function getOrderByType($where)
	{
	    if ( $where['fromdate'] ) {
	        $fromdate = strtotime($where['fromdate']);
	        $sql .= " and t2.add_time >= {$fromdate}";
	    }
	    if ( $where['todate'] ) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $sql .= " and t2.add_time <= {$todate}";
	    }
	    $datas = $this -> _db -> getOrderByType($sql);
	    if ( !$datas )  return false;
	    
	    foreach ( $datas as $data ) {
	        $result[$data['type']]['type'] = $data['type'];
	        $result[$data['type']]['TotalCount'] += $data['number'];
	        if ( $data['status'] == 0 ) {
	            $result[$data['type']]['ValidCount'] += $data['number'];
	            $result[$data['type']]['TotalAmount'] += $data['price_pay'];
	            $result[$data['type']]['PaidAmount'] += $data['price_payed'];
	            if ( $data['status_return'] ) {
	                $result[$data['type']]['ReturnCount'] += $data['number'];
	                $result[$data['type']]['ReturnAmout'] += $data['price_before_return'] - $data['price_pay'];
	            }
	        }
	    }
	    
	    return $result;
	}
	
	/**
	 * 获得用户购买记录
	 */
	public function getUserGoods($where)
	{
	    $whereSQL = 1;
	    if ( $where['fromdate'] ) {
	        $fromdate = strtotime($where['fromdate']);
	        $whereSQL .= " and t3.add_time >= {$fromdate}";
	    }
	    if ( $where['todate'] ) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $whereSQL .= " and t3.add_time <= {$todate}";
	    }
	    if ($where['shop_id'] != null && $where['shop_id'] != '') {
	        $whereSQL .= " and t3.shop_id = {$where['shop_id']}";
	    }
	    if ($where['status']) {
	        if ($where['status'] == 1) {
	            $whereSQL .= " and t2.status = 0";
	        }
	        else if ($where['status'] == 2) {
	            $whereSQL .= " and t2.status in (1,2)";
	        }
	    }
	    if ($where['goods_name']) {
	        $whereSQL .= " and t1.goods_name like '%{$where['goods_name']}%'";
	    }
	    if ($where['goods_sn']) {
	        $whereSQL .= " and t1.product_sn = '{$where['goods_sn']}'";
	    }
	    
	    $datas = $this -> _db -> getUserGoods($whereSQL);
	    
	    return $datas;
	}
	
	/**
	 * 财务销售报表
	 */
	public function getFinanceSales($where)
	{
	    $whereSQL = "((t1.status = 0 and t1.status_logistic in (3,4,5)) or (t1.status = 3 and t1.fake_type = 0) or (t1.status = 4 and t1.status_logistic = 4 and t2.distribution_type = 0)) and t1.logistic_time > 0";
	    
	    if ($where['send_fromdate']) {
	        $fromdate = strtotime($where['send_fromdate']);
	        $whereSQL .= " and t1.logistic_time >= {$fromdate}";
	    }
	    if ($where['send_todate']) {
	        $todate = strtotime($where['send_todate'].' 23:59:59');
	        $whereSQL .= " and t1.logistic_time <= {$todate}";
	    }
	    if ($where['is_settle'] != null && $where['is_settle'] != '') {
	        $whereSQL .= " and t1.clear_pay = {$where['is_settle']}";
	    }
	    
	    $jiankangSQLArray['jiankang'] = "t2.shop_id = 1";
	    $jiankangSQLArray['gift'] = "t2.shop_id = 0 and t2.user_name = 'gift'";
	    $jiankangSQLArray['internal'] = "t2.shop_id = 0 and t2.user_name = 'internal'";
	    $jiankangSQLArray['other'] = "t2.shop_id = 0 and t2.user_name = 'other'";
	    
	    $callSQLArray['call_in'] = "t2.shop_id = 2 and t1.type = 10";
	    $callSQLArray['call_out'] = "t2.shop_id = 2 and t1.type = 11";
	    $callSQLArray['call_tq'] = "t2.shop_id = 2 and t1.type = 12";
	    
	    if ($where['type']) {
	        if ($where['entry'] == 'self') {
	            $whereSQL .= ' and '.$jiankangSQLArray[$where['type']];
	        }
	        else if ($where['entry'] == 'call') {
	            $whereSQL .= ' and '.$callSQLArray[$where['type']];
	        }
	        else if ($where['entry'] == 'channel') {
	            $whereSQL .= " and t2.shop_id = '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'distribution') {
	            $whereSQL .= " and t2.shop_id = 0 and t2.user_name like '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'new_distribution') {
	            $whereSQL .= " and t3.shop_type = 'distribution'";
	        }
	        else if ($where['entry'] == 'tuan') {
	            $whereSQL .= " and t2.shop_id = '{$where['type']}'";
	        }
	        
	        if ($where['type'] == 'batch_channel') {
	            $tempData = $this -> _db -> getFinanceSales($whereSQL, 't1.addr_consignee');
	            if ($tempData) {
	                foreach ($tempData as $data) {
	                    $temp = explode('/', $data['addr_consignee']);
	                    if ($temp[1]) {
	                        $data['addr_consignee'] = $temp[1];
	                    }
	                    $data['shop_name'] = $data['addr_consignee'];
        	            $datas[$data['addr_consignee']][] = $data;
        	        }
        	        $tempData = $this -> _db -> getFinanceSales(str_replace('t1.status = 3 or ', '', $whereSQL), 't1.addr_consignee', 'cost');
        	        if ($tempData) {
        	            foreach ($tempData as $data) {
        	                $temp = explode('/', $data['addr_consignee']);
        	                if ($temp[1]) {
    	                        $data['addr_consignee'] = $temp[1];
    	                    }
        	                $costData[$data['addr_consignee']]['cost_amount'] += $data['cost_amount'];
        	                $costData[$data['addr_consignee']]['no_tax_cost_amount'] += $data['no_tax_cost_amount'];
        	            }
        	        }
	            }
	        }
	        else {
	            $datas[$where['type']] = $this -> _db -> getFinanceSales($whereSQL, null);
	            $costData[$where['type']] = array_shift($this -> _db -> getFinanceSales(str_replace('t1.status = 3 or ', '', $whereSQL), null, 'cost'));
	        }
	    }
	    else {
	        $sqlArray['self'] = " and (";
	        foreach ($jiankangSQLArray as $sql) {
	            $sqlArray['self'] .= "({$sql}) or ";
	        }
	        $sqlArray['self'] .= "0)";
	        $sqlArray['call'] .= " and t2.shop_id = 2 and t1.type in (10,11,12)";
	        $sqlArray['channel'] = " and t2.shop_id > 0 and t3.shop_type <> 'jiankang' and t3.shop_type <> 'tuan' and t3.shop_type <> 'credit' and t3.shop_type <> 'distribution'";
	        $sqlArray['distribution'] = " and t2.shop_id = 0 and (t2.user_name like '%_distribution' or t2.user_name = 'batch_channel')";
	        $sqlArray['new_distribution'] = " and t3.shop_type = 'distribution'";
	        $sqlArray['tuan'] = " and (t3.shop_type = 'tuan' or t3.shop_type = 'credit')";
	        
	        if ($where['entry']) {
    	        if ($where['entry'] == 'self') {
    	            foreach ($jiankangSQLArray as $key => $sql) {
    	                $datas[$key] = $this -> _db -> getFinanceSales($whereSQL.' and '.$sql, null);
    	                $costData[$key] = array_shift($this -> _db -> getFinanceSales(str_replace('t1.status = 3 or ', '', $whereSQL).' and '.$sql, null, 'cost'));
    	            }
    	        }
    	        else if ($where['entry'] == 'call') {
    	            foreach ($callSQLArray as $key => $sql) {
    	                $datas[$key] = $this -> _db -> getFinanceSales($whereSQL.' and '.$sql, null);
    	                $costData[$key] = array_shift($this -> _db -> getFinanceSales(str_replace('t1.status = 3 or ', '', $whereSQL).' and '.$sql, null, 'cost'));
    	            }
    	        }
    	        else {
        	        if ($where['entry'] == 'channel' || $where['entry'] == 'tuan' || $where['entry'] == 'new_distribution') {
        	            $group = 't2.shop_id,t3.shop_name';
        	        }
        	        else if ($where['entry'] == 'distribution') {
        	            $group = 't2.user_name';
        	        }
        	        $tempData = $this -> _db -> getFinanceSales($whereSQL.$sqlArray[$where['entry']], $group);
        	        if ($tempData) {
        	            foreach ($tempData as $data) {
        	                if ($where['entry'] == 'channel' || $where['entry'] == 'tuan' || $where['entry'] == 'new_distribution') {
        	                    $datas[$data['shop_id']][] = $data;
        	                }
        	                else if ($where['entry'] == 'distribution') {
        	                    $datas[$data['user_name']][] = $data;
        	                }
        	            }
        	        }
        	        $costTempData = $this -> _db -> getFinanceSales(str_replace('t1.status = 3 or ', '', $whereSQL).$sqlArray[$where['entry']], $group, 'cost');
        	        if ($costTempData) {
        	            foreach ($costTempData as $data) {
        	                if ($where['entry'] == 'channel' || $where['entry'] == 'tuan' || $where['entry'] == 'new_distribution') {
        	                    $costData[$data['shop_id']] = $data;
        	                }
        	                else if ($where['entry'] == 'distribution') {
        	                    $costData[$data['user_name']] = $data;
        	                }
        	            }
        	        }
        	    }
    	    }
    	    else {
    	        foreach ($sqlArray as $entry => $sql) {
    	            $datas[$entry] = $this -> _db -> getFinanceSales($whereSQL.$sql, null);
    	            $costData[$entry] = array_shift($this -> _db -> getFinanceSales(str_replace('t1.status = 3 or ', '', $whereSQL).$sql, null, 'cost'));
    	        }
    	    }
	    }

	    if ($datas) {
	        foreach ($datas as $key => $data1) {
	            foreach ($data1 as $data) {
    	            $result[$key]['shop_name'] = $data['shop_name'];
    	            $result[$key]['amount'] += $data['amount'];
    	            $result[$key]['order_count'] += $data['order_count'];
    	            
    	            if ($data['status'] == 3) {
    	                $result[$key]['amount_fake'] += $data['amount'];
    	                $result[$key]['order_count_fake'] += $data['order_count'];
    	            }
    	            else {
    	                $result[$key]['amount_real'] += $data['amount'];
    	                $result[$key]['order_count_real'] += $data['order_count'];
    	            }
    	        }
    	        $result[$key]['cost_amount'] = $costData[$key]['cost_amount'];
    	        $result[$key]['no_tax_cost_amount'] = $costData[$key]['no_tax_cost_amount'];
    	        if ($result[$key]['amount_real']) {
    	            $result[$key]['benefit_rate'] = round(($result[$key]['amount_real'] - $costData[$key]['cost_amount']) / $result[$key]['amount_real'] * 100, 2);
    	        }
    	        else {
    	            $result[$key]['benefit_rate'] = 0;
    	        }
	        }
	    }
        
	    return $result;
	}
	
	/**
	 * 财务应收款
	 */
	public function getAccountReceivable($where)
	{
	    $whereSQL = "((t1.status = 0 and t1.status_logistic in (3,4,5)) or (t1.status = 3 and t1.fake_type = 0) or t1.status in (4,5)) and t1.logistic_time > 0";
	    
	    if ($where['send_fromdate']) {
	        $fromdate = strtotime($where['send_fromdate']);
	        $whereSQL .= " and t1.logistic_time >= {$fromdate}";
	    }
	    if ($where['send_todate']) {
	        $todate = strtotime($where['send_todate'].' 23:59:59');
	        $whereSQL .= " and t1.logistic_time <= {$todate}";
	    }
	    
	    $jiankangSQLArray['jiankang'] = "t2.shop_id = 1";
	    $jiankangSQLArray['gift'] = "t2.shop_id = 0 and t2.user_name = 'gift'";
	    $jiankangSQLArray['internal'] = "t2.shop_id = 0 and t2.user_name = 'internal'";
	    $jiankangSQLArray['other'] = "t2.shop_id = 0 and t2.user_name = 'other'";
	    
	    $callSQLArray['call_in'] = "t2.shop_id = 2 and t1.type = 10";
	    $callSQLArray['call_out'] = "t2.shop_id = 2 and t1.type = 11";
	    $callSQLArray['call_tq'] = "t2.shop_id = 2 and t1.type = 12";
	    
	    if ($where['type']) {
	        if ($where['entry'] == 'self') {
	            $whereSQL .= ' and '.$jiankangSQLArray[$where['type']];
	        }
	        else if ($where['entry'] == 'call') {
	            $whereSQL .= ' and '.$callSQLArray[$where['type']];
	        }
	        else if ($where['entry'] == 'channel') {
	            $whereSQL .= " and t2.shop_id = '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'distribution') {
	            $whereSQL .= " and t2.shop_id = 0 and t2.user_name like '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'new_distribution') {
	            $whereSQL .= " and t2.shop_id = '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'tuan') {
	            $whereSQL .= " and t2.shop_id = '{$where['type']}'";
	        }
	    }
	    else {
	        $sqlArray['self'] = " and (";
	        foreach ($jiankangSQLArray as $sql) {
	            $sqlArray['self'] .= "({$sql}) or ";
	        }
	        $sqlArray['self'] .= "0)";
	        $sqlArray['call'] .= " and t2.shop_id = 2 and t1.type in (10,11,12)";
	        $sqlArray['channel'] = " and t2.shop_id > 0 and t3.shop_type <> 'jiankang' and t3.shop_type <> 'tuan' and t3.shop_type <> 'credit'";
	        $sqlArray['distribution'] = " and t2.shop_id = 0 and (t2.user_name like '%_distribution' or t2.user_name = 'batch_channel')";
	        $sqlArray['new_distribution'] = " and t3.shop_type = 'distribution'";
	        $sqlArray['tuan'] = " and (t3.shop_type = 'tuan' or t3.shop_type = 'credit')";
	        
	        $whereSQL .= $sqlArray[$where['entry']];
	    }
	    
        $datas = $this -> _db -> getAccountReceivable($whereSQL);
        if ($datas) {
            $preGiftCardAmount = $this -> _db -> getAccountReceivablePreGiftCard($whereSQL);
            if ($preGiftCardAmount > 0) {
                foreach ($datas as $index => $data) {
                    if ($data['pay_type'] == 'gift') {
                        $datas[$index]['pre_amount'] = $preGiftCardAmount;
                        break;
                    }
                }
            }
        }

	    return $datas;
	}
	
	/**
	 * 财务应收款退款
	 */
	public function getAccountReceivableReturn($where)
	{
	    $whereSQL = "((t1.status = 0 and t1.status_logistic in (3,4,5)) or (t1.status = 3 and t1.fake_type = 0) or t1.status in (4,5)) and t1.logistic_time > 0";
	    
	    if ($where['send_fromdate']) {
	        $fromdate = strtotime($where['send_fromdate']);
	        $whereSQL .= " and t4.check_time >= {$fromdate}";
	    }
	    if ($where['send_todate']) {
	        $todate = strtotime($where['send_todate'].' 23:59:59');
	        $whereSQL .= " and t4.check_time <= {$todate}";
	    }
	    
	    $jiankangSQLArray['jiankang'] = "t2.shop_id = 1";
	    $jiankangSQLArray['gift'] = "t2.shop_id = 0 and t2.user_name = 'gift'";
	    $jiankangSQLArray['internal'] = "t2.shop_id = 0 and t2.user_name = 'internal'";
	    $jiankangSQLArray['other'] = "t2.shop_id = 0 and t2.user_name = 'other'";
	    
	    $callSQLArray['call_in'] = "t2.shop_id = 2 and t1.type = 10";
	    $callSQLArray['call_out'] = "t2.shop_id = 2 and t1.type = 11";
	    $callSQLArray['call_tq'] = "t2.shop_id = 2 and t1.type = 12";

	    if ($where['type']) {
	        if ($where['entry'] == 'self') {
	            $whereSQL .= ' and '.$jiankangSQLArray[$where['type']];
	        }
	        else if ($where['entry'] == 'call') {
	            $whereSQL .= ' and '.$callSQLArray[$where['type']];
	        }
	        else if ($where['entry'] == 'channel') {
	            $whereSQL .= " and t2.shop_id = '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'distribution') {
	            $whereSQL .= " and t2.shop_id = 0 and t2.user_name like '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'tuan') {
	            $whereSQL .= " and t2.shop_id = '{$where['type']}'";
	        }
	    }
	    else {
	        $sqlArray['self'] = " and (";
	        foreach ($jiankangSQLArray as $sql) {
	            $sqlArray['self'] .= "({$sql}) or ";
	        }
	        $sqlArray['self'] .= "0)";
	        $sqlArray['call'] .= " and t2.shop_id = 2 and t1.type in (10,11,12)";
	        $sqlArray['channel'] = " and t2.shop_id > 0 and t3.shop_type <> 'jiankang' and t3.shop_type <> 'tuan' and t3.shop_type <> 'credit'";
	        $sqlArray['distribution'] = " and t2.shop_id = 0 and (t2.user_name like '%_distribution' or t2.user_name = 'batch_channel')";
	        $sqlArray['tuan'] = " and (t3.shop_type = 'tuan' or t3.shop_type = 'credit')";
	        
	        $whereSQL .= $sqlArray[$where['entry']];
	    }

        $datas = $this -> _db -> getAccountReceivableReturn($whereSQL);
        if ($datas) {
            foreach ($datas as $data) {
                unset($data['point']);
                $data['pay'] = abs($data['pay']);
                $data['point'] = abs($data['point']);
                $data['account'] = abs($data['account']);
                $data['gift'] = abs($data['gift']);
                if ($data['way'] == 3) {
                    $result['换货'] += $data['pay'] + $data['point'] + $data['account'] + $data['gift'];
                }
                else if ($data['way'] == 4) {
                    $result['代收货款变更'] += $data['pay'] + $data['point'] + $data['account'] + $data['gift'];
                }
                else if ($data['way'] == 5) {
                    $result['直供单退货'] += $data['pay'] + $data['point'] + $data['account'] + $data['gift'];
                }
                else if ($data['way'] == 6) {
                    $result['物流公司变更'] += $data['pay'] + $data['point'] + $data['account'] + $data['gift'];
                }
                else {
                    if ($data['delivery'] == 1) {
                        if ($data['point'] > 0) {
                            $result['积分'] += $data['point'];
                        }
                        if ($data['account'] > 0) {
                            $result['账户余额'] += $data['account'];
                        }
                        if ($data['gift'] > 0) {
                            $result['礼品卡'] += $data['gift'];
                        }
                    }
                    $amount = $data['pay'];
                    if ($amount > 0) {
                        if ($data['type'] == 2 && $data['way'] == 1) {
                            $result['渠道中间平台'] += $amount;
                        }
                        else {
                            if ($data['bank_type'] == 1) {
                                $result['银行转账'] += $amount;
                            }
                            else if ($data['bank_type'] == 2) {
                                $result['邮局汇款'] += $amount;
                            }
                            else if ($data['bank_type'] == 5) {
                                $result['支付宝'] += $amount;
                            }
                            else {
                                $result['其它'] += $amount;
                            }
                        }
                    }
                }
            }
        }
        
        //货到付款拒收
        $datas = $this -> _db -> getAccountReceivableReject($whereSQL);
        if ($datas) {
            foreach ($datas as $data) {
                !$data['name'] && $data['name'] = '礼品卡';
                $result[$data['name'].'拒收'] = $data['amount'];
            }
        }
        
	    return $result;
	}
	
	/**
	 * 财务退款
	 */
	public function getFinanceReturn($where)
	{
	    $whereSQL = "t2.status in (0,4) and t2.status_logistic in (3,4,5)";
	    
	    $jiankangSQLArray['jiankang'] = "t3.shop_id = 1";
	    $jiankangSQLArray['gift'] = "t3.shop_id = 0 and t3.user_name = 'gift'";
	    $jiankangSQLArray['internal'] = "t3.shop_id = 0 and t3.user_name = 'internal'";
	    $jiankangSQLArray['other'] = "t3.shop_id = 0 and t3.user_name = 'other'";
	    
	    $callSQLArray['call_in'] = "t3.shop_id = 2 and t2.type = 10";
	    $callSQLArray['call_out'] = "t3.shop_id = 2 and t2.type = 11";
	    $callSQLArray['call_tq'] = "t3.shop_id = 2 and t2.type = 12";
	    
	    if ($where['type']) {
	        if ($where['entry'] == 'self') {
	            $whereSQL .= ' and '.$jiankangSQLArray[$where['type']];
	        }
	        else if ($where['entry'] == 'call') {
	            $whereSQL .= ' and '.$callSQLArray[$where['type']];
	        }
	        else if ($where['entry'] == 'channel') {
	            $whereSQL .= " and t3.shop_id = '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'distribution') {
	            $whereSQL .= " and t3.shop_id = 0 and t3.user_name like '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'new_distribution') {
	            $whereSQL .= " and t3.shop_id = '{$where['type']}'";
	        }
	        else if ($where['entry'] == 'tuan') {
	            $whereSQL .= " and t3.shop_id = '{$where['type']}'";
	        }
	    }
	    else {
	        $sqlArray['self'] = " and (";
	        foreach ($jiankangSQLArray as $sql) {
	            $sqlArray['self'] .= "({$sql}) or ";
	        }
	        $sqlArray['self'] .= "0)";
	        $sqlArray['call'] .= " and t3.shop_id = 2 and t2.type in (10,11,12)";
	        $sqlArray['channel'] = " and t3.shop_id > 0 and t4.shop_type <> 'jiankang' and t4.shop_type <> 'tuan' and t4.shop_type <> 'credit' and t4.shop_type <> 'distribution'";
	        $sqlArray['distribution'] = " and t3.shop_id = 0 and (t3.user_name like '%_distribution' or t3.user_name = 'batch_channel')";
	        $sqlArray['new_distribution'] = " and t4.shop_type = 'distribution'";
	        $sqlArray['tuan'] = " and (t4.shop_type = 'tuan' or t4.shop_type = 'credit')";
	        
	        $whereSQL .= $sqlArray[$where['entry']];
	    }
	    
	    if ($where['fromdate']) {
	        $fromdate = strtotime($where['fromdate']);
	        $whereSQL .= " and t1.finish_time >= {$fromdate}";
	    }
	    if ($where['todate']) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $whereSQL .= " and t1.finish_time <= {$todate}";
	    }
	    
	    if ($where['pay_type']) {
	        if ($where['pay_type'] == 'ems' || $where['pay_type'] == 'sf') {
	            $whereSQL .= " and t2.logistic_code = '{$where['pay_type']}'";
	        }
	        else if ($where['pay_type'] == 'tenpay' || $where['pay_type'] == 'phonepay') {
	            $whereSQL .= " and t2.pay_type = '{$where['pay_type']}'";
	        }
	        else {
	            $whereSQL .= " and t2.pay_type not in ('tenpay', 'phonepay') and t2.logistic_code not in ('ems', 'sf')";
	        }
	    }
        
	    $datas = $this -> _db -> getFinanceReturn1($whereSQL);
	    if ($datas) {
	        foreach ($datas as $data) {
	            $result[$data['shop_id']] = $data;
	        }
	    }
	    
	    $datas = $this -> _db -> getFinanceReturn2($whereSQL, 't3.shop_id');
	    if ($datas) {
	        foreach ($datas as $data) {
	            $result[$data['shop_id']]['cost_amount'] = $data['cost_amount'];
	            $result[$data['shop_id']]['no_tax_cost_amount'] = $data['no_tax_cost_amount'];
	            $result[$data['shop_id']]['shop_name'] = $data['shop_name'];
	        }
	    }
	    
	    $datas = $this -> _db -> getFinanceReturn3($whereSQL, 't3.shop_id,t1.item,t1.way');
	    if ($datas) {
	        foreach ($datas as $data) {
	            $result[$data['shop_id']]['amount'] += abs($data['amount']);
	            $result[$data['shop_id']]['shop_name'] = $data['shop_name'];
	            if ($data['item'] == 1) {
	                if ($data['way'] == 3 || $data['way'] == 4 || $data['way'] == 5 || $data['way'] == 6) {
    	                $result[$data['shop_id']]['auto_amount'] += abs($data['amount']);
    	            }
    	            else {
    	                $result[$data['shop_id']]['return_amount'] += abs($data['amount']);
    	            }
	            }
	            else if ($data['item'] == 2) {
	                $result[$data['shop_id']]['external_amount'] += abs($data['amount']);
	            }
	        }
	    }
	    
	    if ($where['fromdate']) {
	        $cols = array();
	        $date = strtotime(date('Y-m', strtotime($where['fromdate'])).'-01 00:00:00');
	        
	        //$datas = $this -> _db -> getFinanceReturn3($whereSQL." and t2.logistic_time < {$date}", "t3.shop_id,FROM_UNIXTIME(t2.logistic_time, '%Y-%m')");
	        $datas = '';    //暂时不显示
	        if ($datas) {
	            foreach ($datas as $data) {
	                $key = '退款金额('.$data["FROM_UNIXTIME(t2.logistic_time, '%Y-%m')"].')';
	                $result[$data['shop_id']][$key] = abs($data['amount']);
	                if (!in_array($key, $cols)) {
	                    $cols[] = $key;
	                }
	            }
	        }
	        
	        //$datas = $this -> _db -> getFinanceReturn2($whereSQL." and t2.logistic_time < {$date}", "t3.shop_id,FROM_UNIXTIME(t2.logistic_time, '%Y-%m')");
	        $datas = '';    //暂时不显示
    	    if ($datas) {
    	        foreach ($datas as $data) {
    	            $key = '退货成本('.$data["FROM_UNIXTIME(t2.logistic_time, '%Y-%m')"].')';
    	            $result[$data['shop_id']][$key] = abs($data['cost_amount']);
	                if (!in_array($key, $cols)) {
	                    $cols[] = $key;
	                }
    	        }
    	    }
    	    
    	    //$datas = $this -> _db -> getFinanceReturn4($whereSQL."and t2.pay_type = 'cod' and t2.status_logistic = 5", "t3.shop_id,FROM_UNIXTIME(t2.logistic_time, '%Y-%m')");
    	    $datas = $this -> _db -> getFinanceReturn4($whereSQL." and t2.pay_type = 'cod' and t2.status_logistic = 5", "t3.shop_id");
    	    if ($datas) {
    	        foreach ($datas as $data) {
    	            //$key = '拒收金额('.$data["FROM_UNIXTIME(t2.logistic_time, '%Y-%m')"].')';
    	            $key = '拒收金额';
    	            $result[$data['shop_id']][$key] = abs($data['amount']);
	                if (!in_array($key, $cols)) {
	                    $cols[] = $key;
	                }
    	        }
    	    }
	    }
	    
	    return array($result, $cols);
	}
	
	/**
	 * 产品出入库
	 */
	public function getProductInOutStock($where) 
	{
        if (!empty($where['logic_area'])) {
	        $whereSQL = "t1.lid = {$where['logic_area']}";
        } else {
            $whereSQL = "1=1";
        }
	    if ( $where['fromdate'] ) {
	        $fromdate = strtotime($where['fromdate']);
	        $whereSQL .= " and t1.finish_time >= {$fromdate}";
	    }
	    if ( $where['todate'] ) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $whereSQL .= " and t1.finish_time <= {$todate}";
	    }
	    if ( $where['bill_type'] ) {
	        $whereSQL .= " and t1.bill_type = {$where['bill_type']}";
	    }
	    if ( $where['bill_status'] !== null && $where['bill_status'] !== '') {
	        $whereSQL .= " and t1.bill_status = {$where['bill_status']}";
	    }
	    if ( $where['product_name'] ) {
	        $whereSQL .= " and t3.product_name like '%{$where['product_name']}%'";
	    }

	    if ( $where['admin_name'] ) {
	        $whereSQL .= " and t1.admin_name like '%{$where['admin_name']}%'";
	    }
        
	    if ( $where['product_sn'] ) {
	        $whereSQL .= " and t3.product_sn = '{$where['product_sn']}'";
	    }
	    
	    $datas = $this -> _db -> getProductInOutStock($whereSQL, $where['stock_type']);
	    
	    return $datas;
	}
	
	/**
	 * 产品滞销
	 */
	public function getGoodsUnsalable($where) 
	{
	    $whereSQL1 = 't2.bill_type in (1,5,6,7,10,13) and t2.bill_status = 5 and t1.status_id = 2';
	    $whereSQL2 = 1;
	    if ( $where['product_name'] ) {
	        $whereSQL1 .= " and t3.product_name like '%{$where['product_name']}%'";
	        $whereSQL2 .= " and product_name like '%{$where['product_name']}%'";
	    }
	    if ( $where['product_sn'] ) {
	        $whereSQL1 .= " and t3.product_sn = '{$where['product_sn']}'";
	        $whereSQL2 .= " and product_sn = '{$where['product_sn']}'";
	    }
	    
	    $datas = $this -> _db -> getGoodsSalable($whereSQL1);
	    $products = $this -> _db -> getAllProducts($whereSQL2);
	    
	    if ($datas) {
	        foreach ($datas as $data) {
	            $productInfo[$data['product_id']] = round((time() - $data['add_time']) / 24 / 3600);
	        }
	    }
	    
	    foreach ($products as $index => $product) {
	        if ($productInfo[$product['product_id']]) {
	            if ($productInfo[$product['product_id']] <= $where['days']) {
	                unset($products[$index]);
	                continue;
	            }
	            else {
	                $products[$index]['days'] = $productInfo[$product['product_id']];
	            }
	        }
	        else {
	            $products[$index]['days'] = '*';
	        }
	        
	        $productIDArray[] = $product['product_id'];
	    }
	    
	    if ($productIDArray) {
	        $stockNumberData = $this -> _db -> getProductStock("status_id = 2 and t1.product_id in (".implode(',', $productIDArray).")");
	        foreach ($stockNumberData as $data) {
	            $numberInfo[$data['product_id']] = $data['number'];
	        }
	        foreach ($products as $index => $product) {
	            if ($where['onlyShowHaveStockNumber'] && $numberInfo[$product['product_id']] <= 0) {
	                unset($products[$index]);
	            }
	            else {
	                $products[$index]['stock_number'] = $numberInfo[$product['product_id']] ? $numberInfo[$product['product_id']] : 0;
	            }
	        }
	    }
	    
	    return $products;
	}
	
	/**
	 * 库存周转率
	 */
	public function getStockTurnover($where) 
	{
	    $whereSQL1 = 't2.bill_type in (1,5,6,7,10,13) and t2.bill_status = 5 and t1.status_id = 2';
	    $whereSQL2 = 'status_id = 2 and lid = 1';
	    if ( $where['product_name'] ) {
	        $whereSQL1 .= " and t3.product_name like '%{$where['product_name']}%'";
	        $whereSQL2 .= " and t2.product_name like '%{$where['product_name']}%'";
	    }
	    if ( $where['product_sn'] ) {
	        $whereSQL1 .= " and t3.product_sn = '{$where['product_sn']}'";
	        $whereSQL2 .= " and t2.product_sn = '{$where['product_sn']}'";
	    }
	    if ( $where['days'] ) {
	        $whereSQL1 .= " and t2.add_time >= ".(time() - $where['days'] * 24 * 3600);
	    }
	    if ( $where['onlyShowHaveStockNumber'] ) {
	        $whereSQL2 .= " and (real_in_number - real_out_number) > 0";
	    }
	    
	    $datas = $this -> _db -> getProductStock($whereSQL2);
	    if ($datas) {
	        $outStockdatas = $this -> _db -> getStockTurnover($whereSQL1);
	        if ($outStockdatas) {
	            foreach ($outStockdatas as $data) {
	                $numberInfo[$data['product_id']] = $data['number'];
	            }
	        }
	        
	        foreach ($datas as $index => $data) {
	            $datas[$index]['stock_number'] = $numberInfo[$data['product_id']] ? $numberInfo[$data['product_id']] : 0;
	            if ($data['number'] > 0 && $datas[$index]['stock_number']) {
	                $datas[$index]['rate'] = round($data['number'] / $datas[$index]['stock_number'], 2);
	            }
	            else {
	                $datas[$index]['rate'] = '*';
	            }
	            
	            if ($where['type'] && $where['rate']) {
	                if ($where['type'] == '>=') {
	                    if ($datas[$index]['rate'] < $where['rate'] && $datas[$index]['rate'] != '*') {
	                        unset($datas[$index]);
	                    }
	                }
	                else {
	                    if ($datas[$index]['rate'] >= $where['rate'] || $datas[$index]['rate'] == '*') {
	                        unset($datas[$index]);
	                    }
	                }
	            }
	        }
	    }
	    
	    return $datas;
	}
	
	public function getUnionInfo() {
	    $datas = $this -> _db -> getUnionInfo();
	    foreach ($datas as $data) {
	        $result[$data['user_id']] = $data['cname'];
	    }
	    return $result;
	}
	
	/**
     * 产品购买统计
     *
     * @param    void
     * @return   array
     */
	public function getProductSales($search)
	{
	    $whereSQL1 = "t2.status = 0 and t3.shop_id <> 0 and t1.product_id > 0 and t3.user_name not like 'other_%' and t3.user_name not like 'gift_%'";
	    $whereSQL2 = "t2.status = 0 and t3.shop_id <> 0 and t1.product_id > 0 and t3.user_name not like 'other_%' and t3.user_name not like 'gift_%'";
	    if ( $search['shop_id'] ) {
	        $whereSQL1 .= " and t3.shop_id = '{$search['shop_id']}'";
	        $whereSQL2 .= " and t3.shop_id = '{$search['shop_id']}'";
	    }
	    if ( $search['fromdate'] ) {
	        $time = strtotime($search['fromdate']);
	        $whereSQL1 .= " and t2.add_time >= '{$time}'";
	    }
	    if ( $search['todate'] ) {
	        $time = strtotime($search['todate'].' 23:59:59');
	        $whereSQL1 .= " and t2.add_time <= '{$time}'";
	    }
	    if ( $search['cat_id'] ) {
	        $whereSQL1 .= " and t4.cat_path like '%,{$search['cat_id']},%' ";
	        $whereSQL2 .= " and t4.cat_path like '%,{$search['cat_id']},%' ";
	    }
	    if ( $search['product_name'] ) {
	        $whereSQL1 .= " and goods_name like '%{$search['product_name']}%'";
	        $whereSQL2 .= " and goods_name like '%{$search['product_name']}%'";
	    }
	    if ( $search['product_sn'] ) {
	        $whereSQL1 .= " and product_sn = '{$search['product_sn']}'";
	        $whereSQL2 .= " and product_sn = '{$search['product_sn']}'";
	    }
	    
	    $datas = $this -> _db -> getProductSales1($whereSQL1);

	    if ($datas) {
    	    $tempDatas = $this -> _db -> getProductSales2($whereSQL1);
    	    if ($tempDatas) {
    	        foreach ($tempDatas as $data) {
    	            if ($data['number'] == 1) {
    	                $countInfo[$data['product_sn']]['OrderCount2']++;
    	            }
    	            else {
    	                $countInfo[$data['product_sn']]['OrderCount3']++;
    	            }
    	        }
    	    }
    	    
    	    $tempDatas = $this -> _db -> getProductSales3($whereSQL1);
    	    if ($tempDatas) {
    	        foreach ($tempDatas as $data) {
    	            $countInfo[$data['product_sn']]['OrderCount4'] = $data['OrderCount4'];
    	        }
    	    }
	    }
	    
	    $tempDatas = $this -> _db -> getProductSales4($whereSQL2, strtotime($search['fromdate']), strtotime($search['todate']));
	    if ($tempDatas) {
	        foreach ($tempDatas as $data) {
	            if (!isset($countInfo[$data['product_sn']]))  continue;
	            
	            $countInfo[$data['product_sn']]['UserCount'] = $data['UserCount'];
	        }
	    }
	    
	    foreach ($datas as $index => $data) {
	        $datas[$index]['OrderCount2'] = $countInfo[$data['product_sn']]['OrderCount2'];
	        $datas[$index]['OrderCount3'] = $countInfo[$data['product_sn']]['OrderCount3'];
	        $datas[$index]['OrderCount4'] = $countInfo[$data['product_sn']]['OrderCount4'];
	        $datas[$index]['UserCount'] = $countInfo[$data['product_sn']]['UserCount'];
	    }
	    
	    if ($tempDatas) {
	        foreach ($tempDatas as $data) {
	            if (isset($countInfo[$data['product_sn']]))  continue;
	            
	            $datas[] = array('product_sn' => $data['product_sn'],
	                             'product_name' => $data['product_name'],
	                             'UserCount' => $data['UserCount'],
	                            );
	        }
	    }
	    
	    return $datas;
	}
	
	/**
	 * 产品发货区域
	 */
	public function getProductLogisticArea($where)
	{
	    $sql = 't2.product_id > 0';
	    if ($where['fromdate']) {
	        $fromdate = strtotime($where['fromdate']);
	        $sql .= " and logistic_time >= {$fromdate}";
	    }
	    if ($where['todate']) {
	        $todate = strtotime($where['todate'].' 23:59:59');
	        $sql .= " and logistic_time <= {$todate}";
	    }
	    if ($where['shop_id'] !== null && $where['shop_id'] !== '' ) {
	        $sql .= " and t3.shop_id = '{$where['shop_id']}' ";
	    }
	    if ($where['product_name']) {
	        $sql .= " and t2.goods_name like '%{$where['product_name']}%'";
	    }
	    if ($where['product_sn']) {
	        $sql .= " and t2.product_sn = '{$where['product_sn']}'";
	    }
	    if ($where['province_id']) {
	        $sql .= " and t1.addr_province_id = '{$where['province_id']}'";
	    }
	    
	    return $this -> _db -> getProductLogisticArea($sql);
	}
	
	/**
     * 用户购买信息
     *
     * @param    void
     * @return   array
     */
	public function getSupplierPayment($search)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());      
        $search = Custom_Model_Filter::filterArray($search, $filterChain);
        
        $where = "t1.type = 1";
        if ($search) {
            $search['fromdate'] && $where .= " and t2.finish_time >= ".strtotime($search['fromdate']);
            $search['todate'] && $where .= " and t2.finish_time <= ".strtotime($search['todate'].' 23:59:59');
            $search['supplier_id'] && $where .= " and t1.supplier_id = '{$search['supplier_id']}'";
            $search['purchase_type'] && $where .= " and t2.purchase_type = '{$search['purchase_type']}'";
            if ($search['type'] !== '' && $search['type'] !== null) {
                $where .= " and t1.type = '{$search['type']}'";
            }
            if ($search['status'] !== '' && $search['status'] !== null) {
                $where .= " and t1.status = '{$search['status']}'";
            }
            if ($search['product_sn'] || $search['product_name']) {
                $where1 = $where;
                $search['product_sn'] && $where1 .= " and t4.product_sn = '{$search['product_sn']}'";
                $search['product_name'] && $where1 .= " and t4.product_name like '%{$search['product_name']}%'";
                $ids = $this -> _db -> getPurchasePaymentByProduct($where1);
                if ($ids) {
                    $where .= " and t1.id in (".implode(',', $ids).")";
                }
                else {
                    return false;
                }
            }
		}
		
		if ($search['supplier_id']) {
		    $taxData = $this -> _db -> getSupplierPayment($where, 2);
		    if ($taxData) {
		        foreach ($taxData as $data) {
		            $taxInfo[$data['id']] = round($data['amount2'], 2);
		        }
		    }
		    $result = $this -> _db -> getSupplierPayment($where, 1);
		    if ($result) {
		        foreach ($result as $index => $data) {
		            $result[$index]['amount2'] = $taxInfo[$data['id']];
		        }
		    }
		}
		else {
		    $taxData = $this -> _db -> getSumSupplierPayment($where, 2);
		    if ($taxData) {
		        foreach ($taxData as $data) {
		            $taxInfo[$data['supplier_id']] = round($data['amount2'], 2);
		        }
		    }
		    $result = $this -> _db -> getSumSupplierPayment($where, 1);
		    if ($result) {
		        foreach ($result as $index => $data) {
		            $result[$index]['amount2'] = $taxInfo[$data['supplier_id']];
		        }
		    }
		}
		
		return $result;
	}
	
	/**
     * 订单产品毛利
     *
     * @param    array  search
     * @return   array
     */
	public function getOrderMargin($search)
	{
	    $where = "t1.status = 0";
	    $search['fromdate'] && $where .= " and t1.add_time >=".strtotime($search['fromdate']);
        $search['todate'] && $where .= " and t1.add_time <=".strtotime($search['todate'].' 23:59:59');
	    $search['send_fromdate'] && $where .= " and t1.logistic_time >=".strtotime($search['send_fromdate']);
        $search['send_todate'] && $where .= " and t1.logistic_time <=".strtotime($search['send_todate'].' 23:59:59');
	    $search['shop_id'] && $where .= " and t2.shop_id = '{$search['shop_id']}'";
	    $search['product_sn'] && $where .= " and t4.product_sn = '{$search['product_sn']}'";
	    $search['product_name'] && $where .= " and t4.product_name like '%{$search['product_name']}%'";
	    $search['batch_sn'] && $where .= " and t1.batch_sn = '{$search['batch_sn']}'";
	    if ($search['supplier_id']) {
            $supplierAPI = new Admin_Models_API_Supplier();
            $supplier = array_shift($supplierAPI -> getSupplier("supplier_id = {$search['supplier_id']}"));
            if ($supplier['product_ids']) {
                $where .= " and t4.product_id in ({$supplier['product_ids']})";
            }
            else {
                return false;
            }
        }
        
        $datas = $this -> _db -> getOrderMargin($where, $search['rate']);
	    
	    return $datas;
	}
	
	/**
     * 销售订单查询
     *
     * @param    array  search
     * @return   array
     */
	public function getFinanceProduct($search)
	{
        $productAPI = new Admin_Models_API_Product();
        
        if ($search['fromdate']) {
            $param['fromdate'] = strtotime($search['fromdate']);
        }
        else {
            $param['fromdate'] = 0;
        }
        if ($search['todate']) {
            $param['todate'] = strtotime($search['todate'].' 23:59:59');
        }
        else {
            $param['todate'] = time();
        }
        if ($search['product_sn']) {
            $product = array_shift($productAPI -> get(array('product_sn' => $search['product_sn'])));
            if ($product) {
                $productID = $product['product_id'];
            }
            else {
                return false;
            }
        }
        
        $datas = $productAPI -> calculateProductCostByInitCost($productID, false, false, $param);
        if ($datas) {
            foreach ($datas as $productID => $data) {
                $instockList = $outstockList = $instockData = $outstockData = '';
                $max = 0;
                if ($data['outstock']) {
                    foreach ($data['outstock'] as $outstock) {
                        $outstockList[$outstock['bill_type']]['number'] += $outstock['number'];
                        $outstockList[$outstock['bill_type']]['amount'] += $outstock['price'] * $outstock['number'];
                    }
                    foreach ($outstockList as $billType => $outstock) {
                        $outstock['amount'] = round($outstock['amount'], 3);
                        $outstock['cost'] = round($outstock['amount'] / $outstock['number'], 3);
                        $outstock['bill_type'] = $billType;
                        $outstockData[] = $outstock;
                        $max++;
                    }
                }
                $index = 0;
                if ($data['instock']) {
                    foreach ($data['instock'] as $instock) {
                        $instockList[$instock['bill_type']]['number'] += $instock['number'];
                        $instockList[$instock['bill_type']]['amount'] += $instock['price'] * $instock['number'];
                    }
                    
                    foreach ($instockList as $billType => $instock) {
                        $instock['amount'] = round($instock['amount'], 3);
                        $instock['cost'] = round($instock['amount'] / $instock['number'], 3);
                        $instock['bill_type'] = $billType;
                        $instock['index'] = $index++;
                        $instockData[] = $instock;
                    }
                }
                
                for ($i = $index; $i < $max; $i++) {
                    $instockData[] = array('index' => $i);
                }

                $datas[$productID]['instock'] = $instockData;
                $datas[$productID]['outstock'] = $outstockData;
            }
        }
        
        return $datas;
	}
    
    
    /**
     * 礼品卡汇总
     *
     * @param    array  search
     * @return   array
     */
	public function getfinanceGiftSum($search)
	{
	    $currentTime = time();
	    $fromdate = $search['fromdate'] ? strtotime($search['fromdate']) : strtotime('2013-10-25');
	    $todate = $search['todate'] ? strtotime($search['todate'].' 23:59:59') : $currentTime;
	    if ($todate > $currentTime) {
	        $todate = $currentTime;
	    }
	    
	    $where = 1;
        $search['card_sn'] && $where .= " and t1.card_sn = '{$search['card_sn']}'";
        if ($search['status'] !== '' && $search['status'] !== null) {
            $where .= " and t1.status = '{$search['status']}'";
        }
        $datas = $this -> _db -> getGiftCardUseLog($where);
        if (!$datas)    return false;

        $result['200'] = array();
        $result['300'] = array();
        $result['500'] = array();
        $result['1000'] = array();
        $result['3200'] = array();
        $result['5400'] = array();
        $result['10900'] = array();
        $result['22000'] = array();
        $result['other'] = array();
        
        foreach ($datas as $data) {
            if ($data['log_id']) {
                if ($data['add_time'] > $todate)    continue;
                
                if (!$cardInfo[$data['card_sn']]) {
                    $cardInfo[$data['card_sn']] = array('card_price' => $data['card_price'],
                                                        'current_price' => $data['card_real_price'],
                                                        'to_price' => $data['card_real_price'],
                                                       );
                }
            }
            else {
                $cardInfo[$data['card_sn']] = array('card_price' => $data['card_price']);
                if ($data['add_time'] < $fromdate) {
                    $cardInfo[$data['card_sn']]['from_price'] = $data['card_real_price'];
                    $cardInfo[$data['card_sn']]['to_price'] = $data['card_real_price'];
                }
                else if ($data['add_time'] > $todate) {
                    $cardInfo[$data['card_sn']]['from_price'] = 0;
                    $cardInfo[$data['card_sn']]['to_price'] = 0;
                }
                else {
                    $cardInfo[$data['card_sn']]['from_price'] = 0;
                    $cardInfo[$data['card_sn']]['to_price'] = $data['card_real_price'];
                }
                
                continue;
            }
            
            if ($data['use_time'] >= $fromdate) {
                $cardInfo[$data['card_sn']]['current_price'] += $data['price'];
            }
            if ($data['add_time'] >= $fromdate && $data['add_time'] <= $todate) {
                $cardInfo[$data['card_sn']]['from_price'] = 0;
            }
            else {
                $cardInfo[$data['card_sn']]['from_price'] = $cardInfo[$data['card_sn']]['current_price'];
            }

            if ($data['use_time'] >= $todate) {
                $cardInfo[$data['card_sn']]['to_price'] = $cardInfo[$data['card_sn']]['current_price'];
            }
            
            if ($data['use_time'] >= $fromdate && $data['use_time'] <= $todate) {
                if (!$data['logistic_time'] || $data['logistic_time'] > $todate) {
                    $field = 'use_amount2';
                }
                else {
                    $field = 'use_amount1';
                }
                if ($data['price'] > 0) {
                    $cardInfo[$data['card_sn']][$field] += $data['price'];
                }
                else {
                    if ($data['logistic_time'] && $data['use_time'] > $data['logistic_time']) {
                        $cardInfo[$data['card_sn']]['return_amount'] += $data['price'];
                    }
                    else {
                        $cardInfo[$data['card_sn']][$field] += $data['price'];
                    }
                }
            }
        }

        if (!$cardInfo) return $result;

        foreach ($cardInfo as $data) {
            if (round($data['card_price']) - $data['card_price'] == 0) {
                $key = round($data['card_price']);
                if (!is_array($result[$key])) {
                    $key = 'other';
                }
            }
            else {
                $key = 'other';
            }
            
            $result[$key]['from_price'] += $data['from_price'];
            $result[$key]['to_price'] += $data['to_price'];
            $result[$key]['use_amount1'] += $data['use_amount1'];
            $result[$key]['use_amount2'] += $data['use_amount2'];
            $result[$key]['return_amount'] += abs($data['return_amount']);
        }
        
        $datas = $this -> _db -> getGiftCardSale($fromdate, $todate, $where);
        if ($datas) {
            foreach ($datas as $data) {
                if (round($data['card_price']) - $data['card_price'] == 0) {
                    $key = round($data['card_price']);
                    if (!is_array($result[$key])) {
                        $key = 'other';
                    }
                }
                else {
                    $key = 'other';
                }
                
                $result[$key]['count'] += 1;
                $result[$key]['card_amount'] += $data['card_price'];
                if ($data['status_logistic'] == 3) {
                    $result[$key]['plan_amount'] += $data['sale_price'];
                }
                else if ($data['status_logistic'] == 4) {
                    $result[$key]['active_amount'] += $data['sale_price'];
                }
            }
        }
        
        return $result;
	}
	
	function getFinanceInOutStock($search)
	{
	    $where = 1;
	    if ($search['fromdate']) {
	        $where .= " and t1.finish_time >= ".strtotime($search['fromdate']);
	    }
	    if ($search['todate']) {
	        $where .= " and t1.finish_time <= ".strtotime($search['todate'].' 23:59:59');
	    }
	    $search['bill_type'] && $where .= " and t1.bill_type = '{$search['bill_type']}'";
	    
	    $datas = $this -> _db -> getFinanceInOutStock($where, $search['stock_type']);
	    if (!$datas)    return false;
	    
	    if ($search['show_bill']) {
	        $billData = $this -> _db -> getFinanceInOutStockBill($where, $search['stock_type']);
	        if ($billData) {
	            foreach ($datas as $index => $data) {
	                if (!$data['supplier_id']) {
	                    $data['supplier_id'] = 0;
	                }
	                if ($billData[$data['supplier_id']]) {
	                    $datas[$index]['bill'] = implode('<br>', $billData[$data['supplier_id']]);
	                }
	            }
	        }
	    }
	    
	    return $datas;
	}
}
