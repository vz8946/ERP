<?php

class Admin_Models_API_PUnion
{
	/**
     * 推广联盟 DB
     * 
     * @var Admin_Models_DB_PUnion
     */
	private $_db = null;
	
	/**
     * 文件上传目录
     * 
     * @var string
     */
	private $_upPath = 'upload/admin/union';
	
	/**
     * 订单状态
     * 
     * @var array
     */
	private $_orderStatus = array('正常单', '取消单', '无效单');
	
	/**
     * 分成状态
     * 
     * @var array
     */
	private $_separateType = array('未分成', '已分成', '无效');
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_PUnion();
	}
	
	/**
     * 取得所有推广联盟信息
     *
	 * @param    array  $search
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getPUnionBySearch($search, $page = null, $pageSize = null)
	{
		if ($search != null ) {
			($search['start_date'])  ? $whereReg  = " AND A.add_time >=" . strtotime($search['start_date']) : "";
			($search['end_date'])    ? $whereReg .= " AND A.add_time <=" . strtotime($search['end_date']): "";
			($search['start_date'])  ? $whereOrder  = " AND F.add_time >=" . strtotime($search['start_date']) : "";
			($search['end_date'])    ? $whereOrder .= " AND F.add_time <=" . (strtotime($search['end_date'])+86400) : "";
			($search['start_date'])  ? $whereClick  = " AND `date` >='" . $search['start_date'] . "'"  : "";
			($search['end_date'])    ? $whereClick .= " AND `date` <='" . $search['end_date'] . "'"  : "";
			($search['user_name'])   ? $where .= " AND A.user_name LIKE '%" . $search['user_name'] . "%'" : "";
			($search['user_id'])     ? $where .= " and A.user_id ='" . $search['user_id'] . "'" : "";
			($search['calculate_type'])     ? $where .= " and B.calculate_type ='" . $search['calculate_type'] . "'" : "";
			($search['order_by'])    ? $orderBy = " ORDER BY " . $search['order_by']." DESC" : "";
		}
		
		$content = $this -> _db -> getPUnionList('WHERE 1=1 ' . $where, $whereReg, $whereClick, $whereOrder, $orderBy, $page, $pageSize);
		$total = $this -> _db -> getPUnionCount('WHERE 1=1 ' . $where);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 取得推广联盟数据汇总
     *
     * @param    array    $search
     * @return   array
     */
	public function countpUnionAllData($search)
	{
		if ($search != null ) {
			($search['start_date'])  ? $whereReg  = " AND A.add_time >=" . strtotime($search['start_date']) : "";
			($search['end_date'])    ? $whereReg .= " AND A.add_time <=" . strtotime($search['end_date']) : "";
			($search['start_date'])  ? $whereOrder  = " AND F.add_time >=" . strtotime($search['start_date']) : "";
			($search['end_date'])    ? $whereOrder .= " AND F.add_time <=" . (strtotime($search['end_date'])+ 86400) : "";
			($search['start_date'])  ? $whereClick  = " AND `date` >='" . $search['start_date'] . "'"  : "";
			($search['end_date'])    ? $whereClick .= " AND `date` <='" . $search['end_date'] . "'"  : "";
			($search['user_name'])   ? $where .= " AND A.user_name LIKE '%" . $search['user_name'] . "%'" : "";
			($search['user_id'])     ? $where .= " and A.user_id ='" . $search['user_id'] . "'" : "";
		}
		
		$cUnionAllData = $this -> _db -> countpUnionAllData('WHERE  1=1' . $where, $whereReg, $whereClick, $whereOrder);
		return $cUnionAllData;
	}
	
	/**
     * 取得指定ID的推广联盟信息
     *
     * @param    int    $id
     * @return   array
     */
	public function getPUnionById($id)
	{
		return  @array_shift($this -> _db -> getPUnion(array('B.user_id' => $id)));
	}
	
	/**
     * 取得指定用户名的用户信息
     *
     * @param    string    $username
     * @return   array
     */
	public function getUserByName($username)
	{
		return @array_shift($this -> _db -> getUser(array('user_name' => $username)));
	}
	
	/**
     * 取得指定用户ID的推广联盟信息
     *
     * @param    int    $grouId
     * @return   array
     */
	public function getPUnionByUid($uid)
	{
		return @array_shift($this -> _db -> getPUnion(array('A.user_id' => $uid)));
	}
	
	/**
     * 取得指定登录名的推广联盟信息
     *
     * @param    string    $name
     * @return   array
     */
	public function getPUnionByName($name)
	{
		return  @array_shift($this -> _db -> getPUnion(array('A.user_name' => $name)));
	}
	
	/**
     * 取得指定联盟名称的推广联盟信息
     *
     * @param    string    $name
     * @return   array
     */
	public function getPUnionByCName($name)
	{
		return  @array_shift($this -> _db -> getPUnion(array('B.cname' => $name)));
	}
	
	/**
     * 取得推广联盟状态显示代码
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
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
	
	/**
     * 删除指定ID的推广联盟
     *
     * @param    int    $id
     * @return   void
     */
	public function deletePUnionById($id)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> deletePUnionById((int)$id);
		    if (is_numeric($result) && $result > 0) {
		        return 'deletePUnionSucess';
		    } else {
			    return 'error';
		    }
		}
	}
	
	/**
     * 更改推广联盟状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeStatus($id, $status)
	{
		$id = (int)$id;
		if ($id > 0) {
		    if($this -> _db -> updateStatus($id, $status) <= 0) {
			    exit('failure');
		    }
		}
	}
	
	/**
     * 添加/编辑推广联盟
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function editPUnion($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if ($data['user_name'] == '') {
			return 'noUserName';
		}
		
		if ($data['password'] != '' && $data['confirm_password'] != '') {
			
		    if ($data['password'] != $data['confirm_password']) {
		        return 'noSamePassword';
			}
		} elseif ($id === null || ($data['password'] != '' || $data['confirm_password'] != '')) {
			return 'noPassword';
		}
		
		if ($data['password'] !='') {
		    $data['password'] = Custom_Model_Encryption::getInstance() -> encrypt($data['password']);
		}
		
		/*
		if ($data['pay_password'] != '' || $data['pay_confirm_password'] != '') {
			
		    if ($data['pay_password'] == $data['pay_confirm_password']) {
		        $data['pay_password'] = Custom_Model_Encryption::getInstance() -> encrypt($data['pay_password']);
			} else {
				return 'noSamePassword';
			}
		}
		*/
		
		$data['add_time'] = time();
		$pUnion = $this -> getUserByName($data['user_name']);
		
		if ($id == null) {
			
			if ($pUnion) {
			    return 'pUnionExists';
		    }
		    
			if(is_file($_FILES['id_card_img']['tmp_name'])) {
	    		$thumbs = '650,650|300,300';
				$upload = new Custom_Model_Upload('id_card_img', $this -> _upPath);
				$upload -> up(true, $thumbs);
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> _upPath.'/'.$upload->uploadedfiles[0]['filepath'];
				$data['id_card_img'] = $img_url;
	    	}else{
	    		$data['id_card_img'] = '';
	    	}
		    
		    $result = $this -> _db -> addPUnion($data);
		} else {
			$data['update_time'] = time();
			
			if ($pUnion && $pUnion['user_id'] != $id) {
			    return 'pUnionExists';
		    }
		    
			$exists = $this -> getPUnionByUId($id);
			
			if (!$exists) {
			    return 'pUnionNoExists';
		    }
		    
			if(is_file($_FILES['id_card_img']['tmp_name'])) {
	    		$thumbs = '650,650|300,300';
				$upload = new Custom_Model_Upload('id_card_img', $this -> _upPath);
				$upload -> up(true, $thumbs);
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> _upPath.'/'.$upload->uploadedfiles[0]['filepath'];
				$data['id_card_img'] = $img_url;
	    	}else{
	    		$data['id_card_img'] = '';
	    	}
			
			$result = $this -> _db -> updatePUnion($data, (int)$id);
		}
		
		if (is_numeric($result) && $result >= 0) {
		    return ($id === null) ? 'addPUnionSucess' : 'editPUnionSucess';
		} else {
			return 'error';
		}
	}


    /**
     * 随机生成密码
     *
     * @param    int       $len
	 * @param    string    $type 
     * @return   string
     */
	public function mKey($len = 12, $type = 'ALNUM') 
	{ 
		$alpha = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 
					   'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');           
		$ALPHA = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 
					   'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');        
		$num = array('1', '2', '3', '4', '5', '6', '7', '8', '9'); 
		$keyVals = array(); 
		$key = array();     
		switch ($type) 
		{ 
			case 'lower' : 
				$keyVals = $alpha; 
				break; 
			case 'upper' : 
				$keyVals = $ALPHA; 
				break; 
			case 'numeric' : 
				$keyVals = $num; 
				break; 
			case 'ALPHA' : 
				$keyVals = array_merge($alpha, $ALPHA); 
				break; 
			case 'ALNUM' : 
				$keyVals = array_merge($alpha, $ALPHA, $num); 
				break; 
		} 
		for($i = 0; $i <= $len-1; $i++) 
		{ 
			$r = rand(0,count($keyVals)-1); 
			$key[$i] = $keyVals[$r]; 
		} 
		return join("", $key); 
	}
	/**
     * 取得联盟点击量列表
     *
	 * @param    array  $search
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getClick($search, $page = null, $pageSize = null)
	{
		if ($search != null) {
 		    ($search['start_date'])  ? $where  = " AND date >='" . $search['start_date']."'"  : "";
			($search['end_date'])    ? $where .= " AND date <='" . $search['end_date']."'" : "";
			($search['id'])    ? $where .= " AND user_id = " . $search['id'] : "";
		}
		$content = $this -> _db -> getClick($where, $page, $pageSize);
		$total = $this -> _db -> getClickCount($where);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 搜索订单列表
     *
	 * @param    array  $search
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function searchOrder($search, $page = null, $pageSize = null)
	{
		if ($search != null) {
 		    ($search['start_date'])  ? $where  = " AND B.add_time >=" . strtotime($search['start_date']) : "";
			($search['end_date'])    ? $where .= " AND B.add_time <=" . (strtotime($search['end_date'])+86400) : "";
			($search['start_modify_date'])  ? $where .= " AND A.modify_time >=" . strtotime($search['start_modify_date']) : "";
			($search['end_modify_date'])    ? $where .= " AND A.modify_time <=" . (strtotime($search['end_modify_date'])+86400) : "";
			($search['separate_type'] != '')    ? $where .= " AND A.separate_type =" . $search['separate_type'] : "";
			($search['order_status'] != '')    ? $where .= " AND A.order_status =" . $search['order_status'] : "";
			($search['order_sn'])    ? $where .= " AND A.order_sn = '" . $search['order_sn']."'" : "";
			($search['order_user_name'])    ? $where .= " AND A.order_user_name = '" . $search['order_user_name']."'" : "";
			($search['rank_name'])    ? $where .= " AND A.rank_name = '" . $search['rank_name']."'" : "";
			($search['id'])    ? $where .= " AND A.user_id = '" . $search['id'] . "'" : "";
            ($search['user_param'])    ? $where .= " AND A.user_param  like '%" . $search['user_param'] . "%' " : "";
		}
        $where .= ' AND A.union_type=2 ';
		$content = $this -> _db -> searchOrder($where, $page, $pageSize);
		$total = $this -> _db -> searchOrderCount($where);
		return array('content' => $content, 'totaldata' => $total);
	}
	/**
     * 导出订单数据
     *
     * @param    array    $search
     * @return   void
     */
	public function getOrder($search)
	{
		if ($search != null) {
 		    ($search['start_date'])  ? $where  = " AND O.add_time >=" . strtotime($search['start_date']) : "";
			($search['end_date'])    ? $where .= " AND O.add_time <=" . (strtotime($search['end_date'])+86400) : "";
			($search['start_modify_date'])  ? $where .= " AND A.modify_time >=" . strtotime($search['start_modify_date']) : "";
			($search['end_modify_date'])    ? $where .= " AND A.modify_time <=" . (strtotime($search['end_modify_date'])+86400) : "";
			($search['separate_type'] != '')    ? $where .= " AND A.separate_type =" . $search['separate_type'] : "";
			($search['order_status'] != '')    ? $where .= " AND A.order_status =" . $search['order_status'] : "";
			($search['order_sn'])    ? $where .= " AND A.order_sn = '" . $search['order_sn']."'" : "";
			($search['order_user_name'])    ? $where .= " AND A.order_user_name = '" . $search['order_user_name']."'" : "";
            ($search['user_param'])    ? $where .= " AND A.user_param  like '%" . $search['user_param'] . "%' " : "";
			($search['id'])    ? $where .= " AND A.user_id = " . $search['id'] : "";
		}
		$datas = $this -> _db -> getOrder($where);
		$excel = new Custom_Model_Excel();
		$excel -> send_header('union.xls');
		$excel -> xls_BOF();
		
		$title = array('订单号', '下单用户', '下单时间', '最后修改时间', '订单商品', '订单商品金额', '可分成金额', '其他支付(账户余额,礼券,礼品卡..)', '订单状态', '分成状态', '分成金额', '所属联盟','分成比例', '下家站点', '备注');
        $col = count($title);
        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
		foreach ($datas as $k => $v)
        {
			/*Start::2012.08.03 导出订单时2012.7.10以前的分成比例都按16%*/
        	if($v['order_affiliate_amount'] == 0){ continue; }
        	$div = strtotime("2012-07-10 00:00:00");
        	if($v['add_time'] > $div){
				$affiliate_money = $v['affiliate_money'];
				$proportion = $v['proportion'];
			}else{
				$affiliate_money = $v['order_affiliate_amount']*0.16;
				$proportion = 16;
			}
			/*End::2012.08.03*/
			
        	$v['add_time'] = date('Y-m-d', $v['add_time']);
			$v['modify_time'] = date('Y-m-d', $v['modify_time']);
			$row = array($v['order_sn'], 
			             $v['order_user_name'], 
			             $v['add_time'], 
			             $v['modify_time'], 
                         $v['goods_name'], 
                         $v['order_price_goods'],
                         $v['order_affiliate_amount'],
			             $v['order_price_goods']-$v['order_affiliate_amount'], 
			             $this -> _orderStatus[$v['order_status']], 
			             $this -> _separateType[$v['separate_type']], 
			             $affiliate_money, 
			             $v['user_name'], 
                         ($proportion==99)?'按商品分成':$proportion, 
			             $v['user_param'], 
			             $v['edite_note']);
			$otherPay += $v['order_price_goods']-$v['order_affiliate_amount'];
			$goodsPrice += $v['order_price_goods'];
			$canAffiliatePrice += $v['order_affiliate_amount'];
			$affiliate += $affiliate_money;
			!$proportion && $proportion = $v['proportion'];
			for ($i = 0; $i < $col; $i++) {
			    $excel -> xls_write_label($k+1, $i, $row[$i]);
			}
			flush();
		    ob_flush();
			unset($row);
        }
        $excel -> xls_write_label($k+2, 5, $goodsPrice);
        $excel -> xls_write_label($k+2, 6, $canAffiliatePrice);
        $excel -> xls_write_label($k+2, 7, $otherPay);
        $excel -> xls_write_label($k+2, 10, $affiliate);
        unset($datas);
		$excel -> xls_EOF();
	}
	/**
     * 导出订单商品数据
     *
     * @param    array    $search
     * @return   void
     */
	public function getExportOrderGoods($search)
	{
		if ($search != null) {
 		    ($search['start_date'])  ? $where  = " AND O.add_time >=" . strtotime($search['start_date']) : "";
			($search['end_date'])    ? $where .= " AND O.add_time <=" . (strtotime($search['end_date'])+86400) : "";
			($search['start_modify_date'])  ? $where .= " AND A.modify_time >=" . strtotime($search['start_modify_date']) : "";
			($search['end_modify_date'])    ? $where .= " AND A.modify_time <=" . (strtotime($search['end_modify_date'])+86400) : "";
			($search['separate_type'] != '')    ? $where .= " AND A.separate_type =" . $search['separate_type'] : "";
			($search['order_status'] != '')    ? $where .= " AND A.order_status =" . $search['order_status'] : "";
			($search['order_sn'])    ? $where .= " AND A.order_sn = '" . $search['order_sn']."'" : "";
			($search['order_user_name'])    ? $where .= " AND A.order_user_name = '" . $search['order_user_name']."'" : "";
            ($search['user_param'])    ? $where .= " AND A.user_param  like '%" . $search['user_param'] . "%' " : "";
			($search['id'])    ? $where .= " AND A.user_id = " . $search['id'] : "";
		}
		$datas = $this -> _db -> getOrderGoods($where);
		$excel = new Custom_Model_Excel();
		$excel -> send_header('union.xls');
		$excel -> xls_BOF();
		
		$title = array('订单号', '下单用户', '下单时间', '最后修改时间', '订单商品', '订单商品总金额', '可分成总金额', '其他支付(账户余额,礼券,礼品卡..)', '订单状态', '分成状态', '所属联盟', '下家站点', '备注','商品编号','商品ID','数量','单价','商品金额','分成比例','该商品佣金');
        $col = count($title);
        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
        
        //$sns = array();保存order_sn
        $k = 0;
		foreach ($datas as $v)
        {
            $v['order_affiliate_amount'] = floatval($v['order_affiliate_amount']);
            if(!$v['order_affiliate_amount']){ continue; } //如果可用分成金额为0，则跳过
        	/*Start::2012.08.03 导出订单时2012.7.10以前的分成比例都按16%
        	if($v['order_affiliate_amount'] == 0){ continue; }
        	$div = strtotime("2012-07-11 00:00:00");
			if($v['add_time'] > $div){
				$affiliate_money = $v['affiliate_money'];
				$proportion = $v['$proportion'];
			}else{
				$affiliate_money = $v['order_affiliate_amount']*0.16;
				$proportion = 16;
			}
			//End::2012.08.03
			
			if(in_array($v['order_sn'], $sns)){
				$order_price_goods = 0;
				$order_affiliate_amount = 0;
				$else_price = 0;
			}else{
				$sns[] = $v['order_sn'];
				$order_price_goods = $v['order_price_goods'];
				$order_affiliate_amount = $v['order_affiliate_amount'];
				$else_price = $v['order_price_goods']-$v['order_affiliate_amount'];
			}*/
           /*if($v['proportion']==99){
               $temp = $this -> _db ->getUnionGoods(array('union_id'=>$v['user_id'], 'goods_id'=>$v['goods_id']), 'proportion');
               if($temp['tot']>0){
                   $temp = array_shift($temp['datas']);
                   $proportion = $temp['proportion'];
               }
           }*/
            $proportion = 0;
            $st = $this -> _db -> getGoodsCatBelong($v['goods_id']);
            if($st == 1){$proportion = 16;}//保健品分成比例 16%
            elseif($st == 2){$proportion = 6;}//保健食品分成比例 6%
            else{$proportion = 6;}
			
            $v['add_time'] = date('Y-m-d', $v['add_time']);
            $v['modify_time'] = date('Y-m-d', $v['modify_time']);
            $row = array($v['order_sn'], 
                         $v['order_user_name'], 
                         $v['add_time'], 
                         $v['modify_time'], 
                         $v['goods_name'], 
                         $v['order_price_goods'],
                         $v['order_affiliate_amount'],
                         ($v['order_price_goods']-$v['order_affiliate_amount']), 
                         $this -> _orderStatus[$v['order_status']], 
                         $this -> _separateType[$v['separate_type']], 
                         $v['user_name'], 
                         $v['user_param'], 
                         $v['edite_note'],
                        $v['product_sn'],
                        $v['product_id'],
                        $v['number'],
                        $v['eq_price'],
                        $v['eq_price']*$v['number'],
                        $proportion,
                        $proportion*$v['eq_price']*$v['number']*0.01
            );

            for ($i = 0; $i < $col; $i++) {
                $excel -> xls_write_label($k+1, $i, $row[$i]);
            }
            $k++;
            flush();
            ob_flush();
            unset($row);
        }

        unset($datas);
		$excel -> xls_EOF();
	}


	/**
     * 导出联盟数据
     *
     * @param    array    $search
     * @return   void
     */
	public function getUnion($search)
	{
		if ($search != null) {
 		    ($search['start_date'])  ? $where  = " AND A.add_time >=" . strtotime($search['start_date']) : "";
			($search['end_date'])    ? $where .= " AND A.add_time <=" . strtotime($search['end_date']) : "";
		}
		$datas = $this -> _db -> getUnion($where);
		$excel = new Custom_Model_Excel();
		$excel -> send_header('unioninfo.xls');
		$excel -> xls_BOF();
		
		$title = array('推广ID/U', '联盟', '用户名', '会员等级', '添加时间', '分成比例', '注册人数', '点击人数', '有效订单数', '订单金额');
        $col = count($title);
        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
		foreach ($datas as $k => $v)
        {
			$v['add_time'] = date('Y-m-d', $v['add_time']);
			$row = array($v['user_id'], 
			             $v['cname'], 
			             $v['user_name'], 
			             $v['rank_name'], 
			             $v['add_time'], 
                         $v['proportion'], 
                         $v['reg_num'],
                         $v['click_num'],
			             $v['order_num'], 
			             $v['order_amount']);
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
	 * 某联盟的商品分成比率列表
	 * 
	 * @param int $uid
	 * @param int $page
	 * 
	 * @return array
	 */
	public function getProductProportionList($uid, $search, $page = null, $pagesize = null ,$all = false) {
		$uid = (int)$uid;
		if($uid>0){
			$page = ((int)$page > 0) ? (int)$page : 1;
			$where .= "A.union_id = {$uid} ";
			if ($search != null ) {
				($search['goods_name'])  ? $where  .= " AND B.goods_name like '%" . $search['goods_name'] . "%' " : "";
				($search['goods_id'])  ? $where  .= " AND B.goods_id ='" . $search['goods_id'] . "'" : "";
				($search['goods_sn'])  ? $where  .= " AND B.goods_sn ='" . $search['goods_sn'] . "'" : "";
				($search['cat_id'])  ? $where  .= " AND C.cat_path like '%," . $search['cat_id'] . ",%'" : "";
				($search['price_from'])  ? $where  .= " AND B.price > '" . $search['price_from'] . "'" : "";
				($search['price_to'])  ? $where  .= " AND B.price < '" . $search['price_to'] . "'" : "";
				($search['order_by'])  ? $orderBy  = " order by B.goods_id " . $search['order_by'] : "";
			}
			return $this -> _db -> getProductProportionList($where, $page, $pagesize ,$orderBy, $all);
		}else{
			return false;
		}
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
    		$this -> _db -> setGoodsProportion($uid,$id,$val);
    	}else{
    		return false;
    	}
    }
    
    /**
     * 设置分类下的商品分成比例
     * 
     * @param int $uid
     * @param int $cat_id
     * @param int $cat_proportion
     * 
     * @return bool
     * 
     */
    public function setCatProductProportion($uid,$cat_id,$cat_proportion) {
    	$uid = (int)$uid;
    	$cat_id = (int)$cat_id;
    	$cat_proportion = (int)$cat_proportion;
    	if($uid>0 && $cat_id>0){
    		//$cat_id是否为末级分类
    		$catgories = $this -> _db -> getCategories($cat_id);
    		return $this->_db->setCatProductProportion($uid,$catgories,$cat_proportion);
    	}else{
    		return false;
    	}
    }
    
	/**
     * 导出某联盟商品分成比率数据
     *
     * @param    array    $search
     * @return   void
     */
	public function exportUnionGoodsProportion($uid,$search)
	{
		$unionName = $this -> getPUnionByUid($uid);
		$datas = $this -> getProductProportionList($uid,$search,null,null,true);
		$excel = new Custom_Model_Excel();
		$excel -> send_header($unionName['cname'].'商品分成比率表.xls');
		$excel -> xls_BOF();
		
		$title = array('商品ID', '商品名称', '本店价', '分类', '分成比率%');
        $col = count($title);
        for ($i = 0; $i < $col; $i++) {
        	$excel -> xls_write_label(0, $i, $title[$i]);
        }
		foreach ($datas['data'] as $k => $v)
        {
			$row = array($v['goods_id'], 
			             $v['goods_name'], 
			             $v['price'], 
			             $v['cat_name'], 
			             $v['proportion']);
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
			return $this -> _db -> insertGoodsToUnionGoods($uid,$goods_id);
		}
		return false;
	}
}