<?php

class Admin_Models_API_GoodsCard
{
	private $_db = null;
	
	private $_table_card_type = 'shop_goods_card_type';
	private $_table_card_create_log = 'shop_goods_card_create_log';
	private $_table_card = 'shop_goods_card';
	private $_table_goods = 'shop_goods';
	private $_table_group_goods = 'shop_group_goods';
	private $_table_order_batch_goods = 'shop_order_batch_goods';
	
	
	/**
     * 对象初始化
     * 
     * @return void
     */
	public function __construct()
    {
        $this -> _db = Zend_Registry::get('db');
	}
	
    /**
     * 获取卡类型数据
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
		    $where['card_name'] && $wheresql .= "card_name like '%{$where['card_name']}%' and ";
		    $where['card_type_id'] && $wheresql .= "card_type_id = '{$where['card_type_id']}' and ";
		    $where['status'] !== null && $wheresql .= "status = '{$where['status']}' and ";
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
			$orderBy = "ORDER BY card_type_id";
		}
		
		return array('list' => $this -> _db -> fetchAll("SELECT $fields FROM {$this->_table_card_type} $whereSql $orderBy $limit"),
		             'total' => $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this->_table_card_type} $whereSql"));
	}
	
	/**
     * 获取卡发放数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getCardLog($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    if ($where['log_id']) {
		        $wheresql .= "t1.log_id = '{$where['log_id']}' and ";
		    }
		    if ($where['card_type_id']) {
		        $wheresql .= "t1.card_type_id = '{$where['card_type_id']}' and ";
		    }
		    $where['status'] !== null && $wheresql .= "t1.status = '{$where['status']}' and ";
		    if ($where['card_name']) {
		        $wheresql .= "t2.card_name like '%{$where['card_name']}%' and ";
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
			$orderBy = "ORDER BY t1.card_type_id";
		}
		
		return array('list' => $this -> _db -> fetchAll("SELECT $fields FROM {$this->_table_card_create_log} as t1 left join {$this -> _table_card_type} as t2 on t1.card_type_id = t2.card_type_id $whereSql $orderBy $limit"),
		             'total' => $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this->_table_card_create_log} as t1 left join {$this -> _table_card_type} as t2 on t1.card_type_id = t2.card_type_id $whereSql"));
	}
	
	/**
     * 获取卡数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getCard($where = null, $fields = 't1.*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    $where['card_id'] && $wheresql .= "t1.card_id = '{$where['card_id']}' and ";
		    $where['log_id'] && $wheresql .= "t1.log_id = '{$where['log_id']}' and ";
		    $where['card_name'] && $wheresql .= "t3.card_name like '%{$where['card_name']}%' and ";
		    $where['card_type_id'] && $wheresql .= "t2.card_type_id = '{$where['card_type_id']}' and ";
		    $where['card_sn'] && $wheresql .= "t1.card_sn = '{$where['card_sn']}' and ";
		    $where['user_name'] && $wheresql .= "t1.user_name like '%{$where['user_name ']}%' and ";
		    if ($where['status'] != null && $where['status'] != '') {
		        $wheresql .= "t1.status = '{$where['status']}' and ";
		    }
		    if ($where['sold'] != null && $where['sold'] != '') {
		        $wheresql .= "t1.sold = '{$where['sold']}' and ";
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
			$orderBy = "ORDER BY card_id";
		}

		return array('list' => $this -> _db -> fetchAll("SELECT $fields,t2.card_type_id,t2.status as log_status,t3.card_name,t3.goods_info,t3.status as type_status FROM {$this->_table_card} as t1 left join {$this->_table_card_create_log} as t2 on t1.log_id = t2.log_id left join {$this -> _table_card_type} as t3 on t2.card_type_id = t3.card_type_id $whereSql $orderBy $limit"),
		             'total' => $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this->_table_card} as t1 left join {$this->_table_card_create_log} as t2 on t1.log_id = t2.log_id left join {$this -> _table_card_type} as t3 on t2.card_type_id = t3.card_type_id $whereSql"));
	}
	
	/**
     * 添加或修改提货卡类型
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function editType($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        if (!$data['goods_info'] || ($data['goods_info']) == 0) {
            $this -> error = 'goods_sn_empty';
			return false;
        }
        foreach ($data['goods_info'] as $key => $goods_sn) {
            $goods_sn = trim($goods_sn);
            if ($goods_sn == '') {
                $this -> error = 'goods_sn_empty';
			    return false;
            }
            if (strlen($goods_sn) != 6 && strlen($goods_sn) != 7 && strlen($goods_sn) != 8) {
                $this -> error = 'goods_sn_invalid';
			    return false;
            }
            if (!$this -> getGoodsDetail($goods_sn)) {
                $this -> error = 'goods_sn_not_exists';
			    return false;
            }
            
            $data['goods_info'][$key] = $goods_sn;
        }
        
		if ( $id ) {
		    $temp = $this -> get("card_type_id <> {$id} and card_name = '{$data['card_name']}'");
		}
		else {
		    $temp = $this -> get("card_name = '{$data['card_name']}'");
		}
		if ( $temp['total'] > 0 ) {
		    $this -> error = 'same_type_name';
			return false;
		}
		$row = array('card_name' => $data['card_name'],
		             'goods_info' => serialize($data['goods_info']),
		             'cost' => $data['cost'],
		             'status' => $data['status'],
		            );
		$auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		if ($id === null) {
		    $row['add_time'] = time();
		    $row['history'] = "添加记录: {$auth['admin_name']}\n";
		    $this -> _db -> insert($this -> _table_card_type, $row);
		} else {
		    $temp = $this -> get("card_type_id = {$id}");
		    $goods_info = implode(',', unserialize($temp['list'][0]['goods_info']));
		    $new_goods_info = implode(',', $data['goods_info']);
		    if ($goods_info != $new_goods_info) {
		        $row['history'] = $temp['list'][0]['history']."修改商品: {$goods_info} => {$new_goods_info} {$auth['admin_name']} ".date('Y-m-d H:i:s')."\n";
		    }
		    
		    $this -> _db -> update($this -> _table_card_type, $row, "card_type_id = {$id}");
		}
		
		return true;
	}
	
	/**
     * 删除提货卡类型
     *
     * @param    int      $id
     * @return   string
     */
	public function delType($id)
	{
	    if ($this -> _db -> fetchRow("select 1 from {$this -> _table_card_create_log} where card_type_id = {$id}")) {
	        $this -> error = 'type_can_not_delete';
		    return false;
	    }
	    
	    return $this -> _db -> delete($this -> _table_card_type, "card_type_id = {$id}");
	}
	
	/**
     * 获取卡类型状态信息
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
     * 获取生成卡状态信息
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxLogStatus($url, $id, $status)
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
     * ajax更新提货卡类型
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxTypeUpdate($id, $field, $val)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$field = $filterChain -> filter($field);
		$val = $filterChain -> filter($val);
		
		if ( (int)$id > 0 ) {
		    $this -> _db -> update($this -> _table_card_type, array($field => $val), "card_type_id = {$id}");
		}
	}
	
	/**
     * ajax更新提货卡生成状态
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxLogUpdate($id, $field, $val)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$field = $filterChain -> filter($field);
		$val = $filterChain -> filter($val);
		
		if ( (int)$id > 0 ) {
		    $this -> _db -> update($this -> _table_card_create_log, array($field => $val), "log_id = {$id}");
		}
	}
	
	/**
     * 添加提货卡
     *
     * @param    void
     * @return   string
     */
	public function addCardLog($data)
	{
		$auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$data['admin_name'] = $auth['admin_name'];
		$data['add_time'] = time();
		$position = $this -> getCardPosition();
		$newPosition = ($position) ? intval($position) + 1 : 1;
		$data['range_from'] = $newPosition;
		$data['range_end'] = $newPosition + $data['number']-1;
		for ($i = $data['range_from']; $i < $data['range_from'] + $data['number']; $i++) {
			$string = date('y').sprintf('%07d', $i);
			$encode = Custom_Model_Encryption::getInstance() -> encrypt($string, 'goodsCard');
			$encode['sn'] = 's' . $encode['sn'];
			$data['card_sn'][] = $encode;
			unset($string, $encode);
		}
		$row = array ('card_type_id' => $data['card_type_id'],
		              'range_from' => $data['range_from'],
                      'range_end' => $data['range_end'],
                      'number' => $data['number'],
                      'admin_name' => $data['admin_name'],
                      'add_time' => $data['add_time'],
                      'status' => 0
                      );
        $data['note'] && $row['note'] = $data['note'];
        $insertLog = $this -> _db -> insert($this -> _table_card_create_log, $row);
        $lastInsertId = $this -> _db -> lastInsertId();
        
        if ($data['end_date']) {
            $field = ', `end_date`';
            $value = ", '".strtotime($data['end_date'].' 23:59:59')."'";
        }
        
        if ($lastInsertId && $data['card_sn']) {
			foreach ($data['card_sn'] as $card) {
				$cardMsg[] = "('" . $lastInsertId . "', '" . $card['sn'] . "', '" . $card['pwd'] . "', '" . $data['add_time'] . "'".$value.")";
			}
			$sql = 'INSERT INTO `'.$this -> _table_card.'`(`log_id`, `card_sn`, `card_pwd`, `add_time`'.$field.') VALUES '.implode(',', $cardMsg);
			$result = $this -> _db -> execute($sql, $this -> _table);
		}
		
		if ($result) {
			return 'addCardSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 取得提货卡最后发放记录ID
     *
     * @param    void
     * @return   int
     */
	public function getCardPosition()
	{
		$sql = 'SELECT IFNULL(MAX(range_end), 0) FROM `' . $this -> _table_card_create_log . '`';
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
     * 获得指定商品编码的信息
     *
     * @return void
     */
    public function getGoodsDetail($goods_sn)
    {
        if (strlen($goods_sn) == 6 || strlen($goods_sn) == 7) {
            $result = $this -> _db -> fetchRow("select * from {$this -> _table_goods} where goods_sn = '{$goods_sn}'");
        }
        else if (strlen($goods_sn) == 9) {
            $result = $this -> _db -> fetchRow("select * from {$this -> _table_group_goods} where group_sn = '{$goods_sn}'");
            if (!$result)  return false;
            
            $groupGoodsAPI = new Admin_Models_API_GroupGoods();
            $result['goods'] = $groupGoodsAPI -> fetchConfigGoods(array('group_goods_config' => $result['group_goods_config']));
        }
        
        return $result;
    }
    
    /**
     * 取得生成卡文件
     *
     * @param    int    $id
     * @return   void
     */
	public function getCardFile($id)
	{
		$log = $this -> _db -> fetchRow("select * from {$this -> _table_card_create_log} as t1 left join {$this -> _table_card_type} as t2 on t1.card_type_id = t2.card_type_id where t1.log_id = {$id}");
		$content .= '提货卡名称：'.$log['card_name'].chr(13).chr(10);
		$content .= '下载时间：'.date('Y-m-d H:i:s').chr(13).chr(10);
		$content .= '创建人：'.$log['admin_name'].chr(13).chr(10);
		$goodsInfo = unserialize($log['goods_info']);
		$content .= '商品选择范围：';
		foreach ($goodsInfo as $goods_sn) {
		    $goods = $this -> getGoodsDetail($goods_sn);
		    if ($goods['goods_id']) {
		        $content .= $goods['goods_name']."($goods_sn)　";
		    }
		    else if ($goods['group_id']) {
		        $content .= $goods['group_goods_name']."($goods_sn)　";
		    }
		}
		$content .= chr(13).chr(10).chr(13).chr(10);
		
		$cards = $this -> _db -> fetchAll("select card_sn,card_pwd from {$this -> _table_card} where log_id = {$id} and status = 0");
		
		if ($cards) {
			foreach ($cards as $card) {
				$content .= strtoupper($card['card_sn']).'	'.$card['card_pwd'].chr(13).chr(10);
			}
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=giftCard_" . $id . ".txt;");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".strlen($content));
			echo $content;
		} 
		else {
			echo 'empty';
		}
	}
	
	/**
     * 设置卡状态
     *
     * @param    array      $ids
     * @param    string     $todo
     * @param    array      $data
     * @return   void
     */
	public function setCard($ids, $todo, $data = null)
	{
	    if (!$ids || count($ids) == 0)  return false;
	    
	    if ($todo == 'active') {
	        $this -> _db -> update($this -> _table_card, array('status' => 1), "card_id in (".implode(',', $ids).")");
	    }
	    else if ($todo == 'deactive') {
	        $this -> _db -> update($this -> _table_card, array('status' => 3), "card_id in (".implode(',', $ids).")");
	    }
	    else if ($todo == 'set_end_date') {
	        $this -> _db -> update($this -> _table_card, array('end_date' => strtotime($data['end_date'].' 23:59:59')), "card_id in (".implode(',', $ids).")");
	    }
	    else if ($todo == 'sell') {
	        $this -> _db -> update($this -> _table_card, array('sold' => 1, 'price' => $data['price'], 'sale_time' => time()), "card_id in (".implode(',', $ids).")");
	    }
	    else if ($todo == 'settlement') {
	        $this -> _db -> update($this -> _table_card, array('status' => 4), "card_id in (".implode(',', $ids).")");
	    }
	}
	
	/**
     * 销售统计
     *
     * @return   void
     */
	public function getCardSale($search)
	{
	    $whereSql = "1";
	    if ( $search['fromdate'] ) {
            $saleTime = strtotime("{$search['fromdate']} 00:00:00");
            $whereSql .= " and t2.add_time >= {$saleTime}";
        }
        if ( $search['todate'] ) {
            $saleTime = strtotime("{$search['todate']} 23:59:59");
            $whereSql .= " and t2.add_time <= {$saleTime}";
        }
        if ( $search['card_name'] ) {
            $whereSql .= " and t3.card_name = '{$search['card_name']}'";
        }
        
	    $datas = $this -> _db -> fetchAll("select t3.card_type_id,t3.card_name,t1.price,t1.status,t1.card_sn,t1.sold from {$this -> _table_card} as t1 left join {$this -> _table_card_create_log} as t2 on t1.log_id = t2.log_id left join {$this -> _table_card_type} as t3 on t2.card_type_id = t3.card_type_id where {$whereSql}");
        if (!$datas)    return false;
        
        foreach ($datas as $data) {
            $result[$data['card_type_id']]['card_name'] = $data['card_name'];
            $result[$data['card_type_id']]['total_count']++;
            if ($data['sold']) {
                $result[$data['card_type_id']]['count']++;
                $result[$data['card_type_id']]['amount'] += $data['price'];
                if ($data['status'] == 2 || $data['status'] == 4) {
                    $result[$data['card_type_id']]['consume_count']++;
                    $cardSNInfo[$data['card_type_id']][] = "'{$data['card_sn']}'";
                }
            }
        }
        
        if ($cardSNInfo) {
            foreach ($cardSNInfo as $cardTypeID => $cardSnArray) {
                $result[$cardTypeID]['consume_amount'] = abs($this -> _db -> fetchOne("select sum(sale_price) as consume_amount from {$this -> _table_order_batch_goods} where number > 0 and card_sn in (".implode(',', $cardSnArray).")"));
            }
        }
        
        return $result;
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
			         'same_type_name' => '提货卡类型名称不能重复!',
			         'goods_sn_empty' => '商品编码必须填写!',
			         'goods_sn_invalid' => '商品编码格式错误!',
			         'goods_sn_not_exists' => '商品编码不存在!',
			         'type_can_not_delete' => '提货卡类型不能删除!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
    
}