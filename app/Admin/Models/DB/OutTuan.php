<?php
class Admin_Models_DB_OutTuan
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
	private $_pageSize = 25;
	
	/**
     * table name
     * @var    string
     */
	private $table_out_tuan_shop = 'shop_out_tuan_shop';
	private $table_out_tuan_goods = 'shop_out_tuan_goods';
	private $table_out_tuan_order = 'shop_out_tuan_order';
	private $table_out_tuan_xls = 'shop_out_tuan_xls';
	private $table_out_tuan_term = 'shop_out_tuan_term';
	private $table_out_tuan_finance = 'shop_out_tuan_finance';
	private $table_out_tuan_finance_log = 'shop_out_tuan_finance_log';
	private $table_out_tuan_refund = 'shop_out_tuan_refund';
	private $table_out_tuan_batch = 'shop_out_tuan_batch';
	private $table_logistic = 'shop_logistic';
	private $table_admin = 'shop_admin';
	private $table_product = 'shop_product';
	private $table_goods = 'shop_goods';
	private $table_instock = 'shop_instock';
	private $table_instock_detail = 'shop_instock_detail';
	private $table_instock_plan = 'shop_instock_plan';
	private $table_order = 'shop_order';
	private $table_area = 'shop_area';
	private $table_shop_shop = 'shop_shop';
	
	private $table_shop_order = 'shop_order';
	private $table_shop_order_batch = 'shop_order_batch';
	private $table_shop_order_batch_adjust = 'shop_order_batch_adjust';
	private $table_shop_order_batch_goods = 'shop_order_batch_goods';
	private $table_shop_order_pay_log = 'shop_order_pay_log';
	private $table_shop_outstock = 'shop_outstock';
	private $table_shop_outstock_detail = 'shop_outstock_detail';
	private $table_shop_transport = 'shop_transport';
	private $table_shop_transport_track = 'shop_transport_track';
	private $table_group_goods = 'shop_group_goods';

	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
	 * 得到外部团购合作网站名称
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * 
	 * @return array
	 */
	public function getOutTuanShop($search=null, $page=1, $pageSize=25, $exclude=null, $all=false) {
		if(!$all){
			$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
			if ($page != null) {
			    $offset = ($page-1)*$pageSize;
			    $limit = " LIMIT  $pageSize  OFFSET $offset";
			}
		}
		$where = "where shop_type='tuan'";
		if(is_array($search) &&  count($search) > 0){
			$search['shop_id'] && $where.= ' and shop_id='.$search['shop_id'];
			$search['shop_name'] && $where.= " and shop_name='".$search['shop_name']."'";
			$search['status'] && $where.= " and status=".$search['status'];
		}
		//排除
		if($exclude){
			$where.= " and shop_id not in (".$exclude.")";
		}
		
		$sqlcount = "select count(shop_id) from {$this->table_shop_shop} $where";
		$rs['tot'] = $this -> _db -> fetchOne($sqlcount);
		$sql = "select * from {$this->table_shop_shop} $where order by shop_id desc $limit";
		$rs['datas'] = $this -> _db -> fetchAll($sql);
		return $rs;
	}
	
	/**
	 * 得到外部团购商品
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * 
	 * @return array
	 */
	public function getOutTuanGoods($search=null, $page=1, $pageSize=25, $exclude=null, $all=false) {
		if(!$all){
			$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
			if ($page != null) {
			    $offset = ($page-1)*$pageSize;
			    $limit = " LIMIT  $pageSize  OFFSET $offset";
			}
		}
		$where = 'where 1=1';
		if(is_array($search) && count($search)>0){
			$search['goods_id'] && $where.= ' and a.goods_id='.$search['goods_id'];
			$search['shop_id'] && $where.= ' and a.shop_id='.$search['shop_id'];
			$search['goods_name'] && $where.= " and a.goods_name='".$search['goods_name']."'";
			$search['goods_name_like'] && $where.= " and a.goods_name like '%".$search['goods_name_like']."%'";
			if($search['status']){
				$where.= ($search['status']=='on')?" and a.status=1":" and a.status=0";
			}
			if($search['goods_fid']){
				$where .= ' and a.goods_fid='.$search['goods_fid'];
			}elseif($search['quan']){
				;
			}else{
				$where .= ' and a.goods_fid=0';
			}

			$search['price_limit'] && $where .=" AND p.price_limit*a.goods_number > a.supply_price";
		}elseif(is_string($search)){
            $where .= $search;
        }
		
		//排除
		if($exclude){
			$where.= " and a.goods_id not in (".$exclude.")";
		}
		$sqlcount = "select count(a.shop_id) from {$this->table_out_tuan_goods} a LEFT JOIN `shop_product` p on a.goods_sn = p.product_sn $where";
		$rs['tot'] = $this -> _db -> fetchOne($sqlcount);

		$sql = "select a.*,b.shop_name,p.price_limit*a.goods_number as final_price_limit, p.price_limit from {$this->table_out_tuan_goods} a 
				left join {$this->table_shop_shop} b on a.shop_id=b.shop_id 
				LEFT JOIN `shop_product` p on a.goods_sn = p.product_sn $where order by shop_id desc $limit";
		$rs['datas'] = $this -> _db -> fetchAll($sql);
        if(count($rs['datas'])>0){
            foreach ($rs['datas'] as $key=>$var){
                 $goodsId[]=$var['goods_id'];
            }
            $sql = "select goods_id, sum(amount) as totalAmount from {$this->table_out_tuan_order} where isback ='0' and  ischeat='0' and goods_id in (".implode(',',$goodsId).")   GROUP BY goods_id " ;
            $goods['amount']= $this -> _db -> fetchAll($sql);
            foreach ($rs['datas'] as $key=>$var){
                foreach ($goods['amount'] as $k=>$v){
                    if($var['goods_id']== $v['goods_id'] ){
                        $rs['datas'][$key]['amount']=$v['totalAmount'];
                    }else{
                        $rs['datas'][$key]['amount']='0';
                    }
                }
            }
            return $rs;
        }
	}
	
	/**
	 * 添加外部团购商品
	 * 
	 * @param array $arr
	 */
	public function goodsAdd($arr) {
		$row = array('goods_fid'=>$arr['goods_fid'],'goods_sn'=>trim($arr['goods_sn']),'goods_type'=>$arr['goods_type'],'shop_id'=>$arr['shopid'],'goods_name'=>trim($arr['goods_name']),'goods_desc'=>$arr['goods_desc'],'goods_price'=>$arr['goods_price'],'supply_price'=>$arr['supply_price'],'rate'=>$arr['rate'],'status'=>1,'add_time'=>time(),'goods_number'=>$arr['goods_number']);
		$this -> _db -> insert($this -> table_out_tuan_goods, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 修改外部团购商品
	 * 
	 */
	public function goodsEdit($set,$where) {
	    $this -> _db -> update($this -> table_out_tuan_goods, $set, $where);
	    return true;
	}
	
	/**
	 * 删除
	 * 
	 * @param int $id
	 */
	public function goodsDel($id) {
		if($id < 1){exit('参数错误');}
		//检查此团购网站下是否有商品
		$check = $this -> _db -> fetchRow("select id from {$this->table_out_tuan_order} where goods_id=".$id);
		if($check){return 'hasgoods';}
		$where = $this -> _db -> quoteInto('goods_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> delete($this->table_out_tuan_goods, $where);
		    return 'ok';
		}
	}
	

	/**
	 * 获取订单信息
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * 
	 * @return array
	 */

	public function getshopOutTuanOrder($search=null, $page=1, $pageSize=25, $exclude=null, $all=false, $idasc=false) {
		if(!$all){
			$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
			if ($page != null) {
			    $offset = ($page-1)*$pageSize;
			    $limit = " LIMIT  $pageSize  OFFSET $offset";
			}
		}
		$where = 'where 1=1 and t.status = 1';
		if($search){
			$search['order_sn'] && $where.= " and t.order_sn = '".$search['order_sn']."'";
			$search['shop_id'] && $where.= ' and t.shop_id='.$search['shop_id'];
			
			if($search['isclear']){
				$where.= ($search['isclear']=='on')?" and ob.clear_pay=1":" and ob.clear_pay=0";
			}
			if($search['ischeatclear']){
				$where.= " and t.ischeat=".$search['ischeatclear'];
			}else{
				if($search['ischeat']){
					$where.= ($search['ischeat']=='on')?" and t.ischeat >0":" and t.ischeat=0";
				}
			}

		    //下单时间
            !empty($search['ordertime_start']) && $where .= " AND t.order_time >= '". strtotime($search['ordertime_start']) ."'";
            !empty($search['ordertime_end'])   && $where .= " AND t.order_time <= '". strtotime($search['ordertime_end']. ' 23:59:59') ."'";
			
			//销账时间
			if($search['cheatfromdate']){$cheat_stime = strtotime($search['cheatfromdate']);}
			if($search['cheattodate']){$cheat_etime = strtotime($search['cheattodate']. ' 23:59:59');}

			if(isset($cheat_stime) && isset($cheat_etime)){
				if($cheat_stime <= $cheat_etime){
					$where.= " and t.cheat_time > $cheat_stime and t.cheat_time < $cheat_etime";
			    }
			}
            // 结算时间
            !empty($search['cleartime_start']) && $where .= " AND ob.clear_time >= '". strtotime($search['cleartime_start']) ."'";
            !empty($search['cleartime_end'])   && $where .= " AND ob.clear_time <= '". strtotime($search['cleartime_end']. ' 23:59:59') ."'";
		}
		
        $table =" shop_out_tuan_order as t left join {$this->table_shop_shop} b on t.shop_id=b.shop_id left join shop_order as o on t.order_sn = o.external_order_sn left join shop_order_batch as ob on o.order_sn = ob.order_sn ";
       
        /*
         汇总计数
        */
        $sql = "select count(t.id) as tot, sum(t.amount) as totalAmount, sum(t.order_price) as totalPrice, sum(t.supply_price) as totalSupplyPrice, sum(t.fee) as totalFee from {$table} {$where}";
        $rs=$this -> _db -> fetchRow($sql);


		//数据
	    $sql = "select b.shop_name,t.id,t.shop_id,t.order_price,t.supply_price,t.fee,t.order_time,t.order_sn,t.ischeat,t.cheat_time,ob.clear_time,t.isclear,ob.clear_pay from   {$table} {$where} order by t.shop_id  $limit";
		$rs['datas'] = $this -> _db -> fetchAll($sql);
		return $rs;
	}



	/**
	 * 得到外部团购商品
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * @param bool $all  是否全选
	 * @param bool $idasc id升序排列
	 * 
	 * @return array
	 */
	public function getOutTuanOrder($search=null, $page=1, $pageSize=25, $exclude=null, $all=false, $idasc=false) {
		if(!$all){
			$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
			if ($page != null) {
			    $offset = ($page-1)*$pageSize;
			    $limit = " LIMIT  $pageSize  OFFSET $offset";
			}
		}
		$where = 'where 1=1';
		if($search){
	        $search['term'] && $datawhere.= " and d.term ='".$search['term']."'";
	        $search['term_not_in'] && $where.= " and a.term_id not in (".$search['term_not_in'].")";

			$search['id'] && $where.= ' and a.id in ('.$search['id'].')';
			$search['order_sn'] && $where.= " and a.order_sn = '".$search['order_sn']."'";
			$search['shop_id'] && $where.= ' and a.shop_id='.$search['shop_id'];
			$search['goods_name_like'] && $where.= " and c.goods_name like '%".$search['goods_name_like']."%'";
			$search['batch'] && $where.= " and a.batch='".$search['batch']."'";
			//$search['status'] && $where.= " and a.status='".$search['status']."'";
			$search['remark'] && $where.= " and a.remark!=''";
			//($search['print']==0) && $where.= " and a.print='".$search['print']."'";
			if($search['logistics']){
				$where.= ($search['logistics']=='on')?" and a.logistics=1":" and a.logistics=0";
			}
			if($search['print']){
				$where.= ($search['print']=='on')?" and a.print=1":" and a.print=0";
			}
			if($search['status']){
				$where.= ($search['status']=='on')?" and a.status=1":" and a.status=0";
			}else{
				$where.= " and a.status=1";
			}
			//3状态
			if($search['isclear']){
				$where.= ($search['isclear']=='on')?" and a.isclear=1":" and a.isclear=0";
			}
			if($search['isback']){
				//$where.= ($search['isback']=='on')?" and a.isback=1":" and a.isback=0";
				if($search['isback']=='on'){$where.=" and a.isback=1";}
				elseif($search['isback']=='off'){$where.=" and a.isback=0";}
				elseif($search['isback']=='ing'){$where.=" and a.isback=2";}
			}
			if($search['ischeatclear']){
				$where.= " and a.ischeat=".$search['ischeatclear'];
			}else{
				if($search['ischeat']){
					$where.= ($search['ischeat']=='on')?" and a.ischeat>0":" and a.ischeat=0";
				}
			}
			//导入人
			$search['add_user'] && $where.= " and add_user=".$search['add_user'];
			//收件人
			$search['name'] && $where.= " and name like '%".$search['name']."%'";
			//手机
			$search['mobile'] && $where.= " and mobile=".$search['mobile'];
			//快递单号
			$search['logistics_no'] && $where.= " and logistics_no='".$search['logistics_no']."'";
			//物流公司
			$search['logistics_com'] && $where.= " and logistics_com='".$search['logistics_com']."'";

			//销账时间
			if($search['cheatfromdate']){$cheat_stime = strtotime($search['cheatfromdate']);}
			if($search['cheattodate']){$cheat_etime = strtotime($search['cheattodate']. ' 23:59:59');}

			if(isset($cheat_stime) && isset($cheat_etime)){
				if($cheat_stime <= $cheat_etime){
					$where.= " and a.cheat_time > $cheat_stime and a.cheat_time < $cheat_etime";
			    }
			}

            //下单时间
            !empty($search['ordertime_start']) && $where .= " AND a.order_time >= '". strtotime($search['ordertime_start']) ."'";
            !empty($search['ordertime_end'])   && $where .= " AND a.order_time <= '". strtotime($search['ordertime_end']. ' 23:59:59') ."'";

            // 结算时间
            !empty($search['cleartime_start']) && $where .= " AND a.clear_time >= '". strtotime($search['cleartime_start']) ."'";
            !empty($search['cleartime_end'])   && $where .= " AND a.clear_time <= '". strtotime($search['cleartime_end']. ' 23:59:59') ."'";



			//导入时间
			if($search['fromdate']){$stime = strtotime($search['fromdate']);}
			if($search['todate']){$etime = strtotime($search['todate']);}
			if(isset($stime) && isset($etime)){
				if($stime > $etime){$tmp = $stime; $stime = $etime; $etime = $tmp;}
				$where.= " and a.ctime>$stime and a.ctime<$etime";
			}elseif(isset($stime)){
				$where.= " and a.ctime>$stime";
			}elseif(isset($etime)){
				$where.= " and a.ctime<$etime";
			}
			//是否验证库存
			!is_null($search['check_stock']) && $where.=" and a.check_stock=".$search['check_stock'];
		}
		
		$px = $idasc?'asc':'desc';
		
		//排除
		/*if($exclude){
			$where.= " and a.goods_id not in (".$exclude.")";
		}*/

        /*
         汇总计数
        */
        $sql = "select count(a.goods_id) as tot, sum(a.amount) as totalAmount, sum(a.order_price) as totalPrice, sum(a.supply_price) as totalSupplyPrice, sum(a.fee) as totalFee from {$this->table_out_tuan_order} a left join {$this->table_out_tuan_goods} c on a.goods_id=c.goods_id left join {$this->table_out_tuan_batch} as ba on a.batch=ba.batch $where";
        $rs=$this -> _db -> fetchRow($sql);
		//数据
		$sql = "select a.*,b.shop_name,b.config,c.goods_name,c.goods_sn,c.goods_number,c.g_id,c.p_id,c.g_name,c.g_style,d.term from {$this->table_out_tuan_order} a left join {$this->table_shop_shop} b on a.shop_id=b.shop_id left join {$this->table_out_tuan_goods} c on a.goods_id=c.goods_id left join {$this->table_out_tuan_term} d on a.term_id=d.id left join {$this->table_out_tuan_batch} as ba on a.batch=ba.batch $where $datawhere order by a.id $px $limit";
		$rs['datas'] = $this -> _db -> fetchAll($sql);
		return $rs;
	}
	
	/**
	 * 添加订单
	 *
	 * @param int $row
	 */
	public function orderAdd($row) {
		$this -> _db -> insert($this->table_out_tuan_order,$row);
	}
	
	/**
	 * 修改订单
	 * 
	 * @param string $where
	 * @param array $set
	 */
	public function orderEdit($set,$where) {
		$this -> _db -> update($this -> table_out_tuan_order, $set, $where);
	    return true;
	}
	
	/**
	 * 真正删除点的批次
	 * 
	 * @param float $batch
	 */
	public function deleteBatchOrder($batch) {
		$where = $this -> _db -> quoteInto('batch = ?', $batch);
		if ($batch > 0) {
		    $this -> _db -> delete($this->table_out_tuan_order, $where);
		    $this -> _db -> delete($this->table_out_tuan_batch, $where);
		}
	}
	
	/**
	 * 真正 删除单条订单
	 * 
	 * @param sring $order_id
	 */
	public function orderDelOne($order_id) {
		$where = $this -> _db -> quoteInto('order_sn = ?', $order_id);
		$this -> _db -> delete($this->table_out_tuan_order, $where);
	}
	
	/**
	 * 添加客服备注
	 * 
	 * @param int $id
	 * @param string $content
	 */
	public function commentAdd($id, $content) {
		$sql = "update {$this->table_out_tuan_order} set comments=concat(comments,'$content') where id=".$id." limit 1";
		$this -> _db -> execute($sql);
	}
	
	/**
	 * 得到xls记录
	 * 
	 * @param int $id
	 * 
	 * @return array
	 */
	public function getOutTuanXls($id) {
		$sql = "select * from {$this -> table_out_tuan_xls} where id=".$id;
		return $this -> _db -> fetchRow($sql);
	}
	
	/**
	 * 添加xls记录
	 * 
	 * @param int $shop_id
	 * @param int $goods_id
	 * @param string $xlspath
	 * 
	 * @return int $insertID
	 */
	public function xlsAdd($shop_id,$goods_id,$xlspath) {
		$row = array('shop_id'=>$shop_id,'goods_id'=>$goods_id,'savepath'=>$xlspath,'ctime'=>time());
		$this -> _db -> insert($this -> table_out_tuan_xls, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 得到物流公司
	 * 
	 * @return array
	 */
	public function getLogistic() {
		return $this -> _db -> fetchAll("select logistic_id,name,logistic_code from {$this->table_logistic} where open=1");
	}
	
	/**
	 * 得到admin用户资料
	 * 
	 * @param int $admin_id
	 */
	public function getAdminUser($admin_id) {
		return $this -> _db -> fetchRow("select admin_id,group_id,admin_name,real_name from {$this->table_admin} where admin_id=".$admin_id);
	}
	
	/**
	 * 
	 * 
	 */
	public function getAddUserId() {
		return $this -> _db -> fetchAll("select distinct add_user from {$this->table_out_tuan_order}");
	}
	
	/**
	 * 得到期数
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $orderby
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * @param bool $all 取全部结果
	 * 
	 * @return array
	 */
	public function getTerm($search=null, $page=1, $pageSize=25, $orderby=null, $exclude=null, $all=false) {
		if(!$all){
			$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
			if ($page != null) {
			    $offset = ($page-1)*$pageSize;
			    $limit = " LIMIT  $pageSize  OFFSET $offset";
			}
		}
		
		$where = 'where 1=1';
		if($search){
			$search['id'] && $where.= ' and a.id='.$search['id'];
			$search['shop_id'] && $where.= ' and a.shop_id='.$search['shop_id'];
			$search['goods_id'] && $where.= ' and a.goods_id='.$search['goods_id'];
			$search['goods_name'] && $where.= " and c.goods_name like '%".$search['goods_name']."%'";
			if($search['status']){
				$where.= ($search['status']=='on')?" and a.status=1":" and a.status=0";
			}
			//
			$search['term'] && $where.= " and a.term='".$search['term']."'";
			$search['stime'] && $where.= ' and a.stime='.$search['stime'];
			$search['etime'] && $where.= ' and a.etime='.$search['etime'];
			if($search['clearstatus']){
				if($search['clearstatus'] == 'zero'){
					$where.= ' and a.clearstatus=0';
				}elseif($search['clearstatus'] == 'one'){
					$where.= ' and a.clearstatus=1';
				}elseif($search['clearstatus'] == 'two'){
					$where.= ' and a.clearstatus=2';
				}elseif($search['clearstatus'] == 'notclear'){
					$where.= ' and (a.clearstatus=0 or a.clearstatus=1)';
				}
			}
		}
		
		//排除
		if($exclude){
			$where.= " and a.id not in (".$exclude.")";
		}
		
		//排序
		if(!$orderby){ $orderby = ' id desc';}
		
		$sqlcount = "select count(a.id) from {$this->table_out_tuan_term} a left join {$this->table_out_tuan_goods} c on a.goods_id=c.goods_id $where";
		$rs['tot'] = $this -> _db -> fetchOne($sqlcount);
		$sql = "select a.*,b.shop_name,c.goods_name from {$this->table_out_tuan_term} a left join {$this->table_shop_shop} b on a.shop_id=b.shop_id left join {$this->table_out_tuan_goods} c on a.goods_id=c.goods_id $where order by $orderby $limit";
		$rs['datas'] = $this -> _db -> fetchAll($sql);
		
		return $rs;
	}
	
	/**
	 * 添加期数
	 * 
	 * @param array $arr
	 */
	public function termAdd($arr) {
		$row = array('shop_id'=>$arr['shopid'],'goods_id'=>$arr['goods_id'],'term'=>$arr['term'],'stime'=>$arr['fromdate'],'etime'=>$arr['todate'],'remark'=>$arr['remark'],'clearstatus'=>0,'amount'=>$arr['amount'],'status'=>1);
		$this -> _db -> insert($this -> table_out_tuan_term, $row);
		$this -> _db -> lastInsertId();
	}
	
	/**
	 * 删除期数
	 * 
	 * @param int $id
	 */
	public function termDelete($id) {
		if($id < 1){exit('参数错误');}
		//检查此团购期数下是否有商品
		$check = $this -> _db -> fetchRow("select id from {$this->table_out_tuan_order} where term_id=".$id);
		if($check){return 'hasgoods';}
		$where = $this -> _db -> quoteInto('id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> delete($this->table_out_tuan_term, $where);
		    return 'ok';
		}
	}
	
	/**
	 * 修改期数
	 * 
	 * @param array $set
	 * @param string $where
	 */
	public function termEdit($set,$where) {
	    $this -> _db -> update($this -> table_out_tuan_term, $set, $where);
	    return true;
	}
	
	/**
	 * 结款单申请
	 * 
	 * $arr
	 */
	public function billAskAdd($arr) {
		$row = array(
			'bill_sn'=>Custom_Model_CreateSn::createSn(),
			'term_ids'=>$arr['termids'],
			'shop_id'=>$arr['shopid'],
			'clear_amount'=>$arr['clear_amount'],
			'clear_time'=>strtotime($arr['clear_time']),
			'add_time'=>time(),
			'add_name'=>$arr['add_name'],
			'remarks'=>'申请人：  '.$arr['remark']
		);
		$this -> _db -> insert($this -> table_out_tuan_finance, $row);
	}
	
	/**
	 * 得到结款单申请
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * @param string $orderby
	 * @param string $exclude 排除shop_id->(1,2,3,4)
	 * @param bool $all 取全部结果
	 * 
	 * @return array
	 */
	public function getBill($search=null, $page=1, $pageSize=25, $orderby=null, $exclude=null, $all=false) {
		if(!$all){
			$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
			if ($page != null) {
			    $offset = ($page-1)*$pageSize;
			    $limit = " LIMIT  $pageSize  OFFSET $offset";
			}
		}
		
		$where = 'where 1=1';
		if($search){
			$search['id'] && $where.= ' and a.id='.$search['id'];
			$search['shop_id'] && $where.= ' and a.shop_id='.$search['shop_id'];
			$search['bill_sn'] && $where.= ' and a.bill_sn='.$search['bill_sn'];
			$search['add_name'] && $where.= " and a.add_name like '%".$search['add_name']."%'";
			if($search['clear_status']){
				if($search['clear_status'] == 'zero'){//未结款
					$where.= ' and a.clear_status=0';
				}elseif($search['clear_status'] == 'one'){//部分结款
					$where.= ' and a.clear_status=1';
				}elseif($search['clear_status'] == 'two'){//已经结清
					$where.= ' and a.clear_status=2';
				}
			}
			if($search['check_status']){
				if($search['check_status'] == 'zero'){
					$where.= ' and a.check_status=0';
				}elseif($search['check_status'] == 'one'){
					$where.= ' and a.check_status=1';
				}elseif($search['check_status'] == 'two'){
					$where.= ' and a.check_status=2';
				}
			}
		}
		
		//排除
		if($exclude){
			$where.= " and a.id not in (".$exclude.")";
		}
		
		//排序
		if(!$orderby){ $orderby = ' a.id desc';}
		
		$sqlcount = "select count(a.id) from {$this->table_out_tuan_finance} a $where";
		$rs['tot'] = $this -> _db -> fetchOne($sqlcount);
		$sql = "select a.*,b.shop_name from {$this->table_out_tuan_finance} a left join {$this->table_shop_shop} b on a.shop_id=b.shop_id $where order by $orderby $limit";
		$rs['datas'] = $this -> _db -> fetchAll($sql);
		
		return $rs;
	}
	
	/**
	 * 审核结款申请单
	 * 
	 * @param array $arr
	 */
	public function billVerify($arr) {
		$sql = "update {$this->table_out_tuan_finance} set clear_amount={$arr['clear_amount']},check_status={$arr['check_status']},check_time={$arr['check_time']},check_name='{$arr['check_name']}',remarks=concat(remarks,'".'审核人：'.$arr['remarks']."') where id = ".$arr['id'];
		$this -> _db -> execute($sql);
	}
	
	/**
	 * 结算do
	 * 
	 * @param array $arr
	 */
	public function billClearDo($arr) {
		$arr['adjust_amount'] = ($arr['adjust_amount'])?$arr['adjust_amount']:0;
		$sql = "update {$this->table_out_tuan_finance} set clear_status={$arr['clearstatus']},real_back_amount=real_back_amount+{$arr['receive']},adjust_amount={$arr['adjust_amount']} where id = ".$arr['id'];
		$this -> _db -> execute($sql);
	}
	
	/**
	 * 收款纪录
	 * 
	 * @param array $arr
	 */
	public function addFinanceLog($arr) {
		$row = array(
			'finance_id'=>$arr['id'],
			'add_name'=>$arr['add_name'],
			'receipt'=>$arr['receive'],
			'add_time'=>time(),
			'remark'=>$arr['remarks']
		);
		$this -> _db -> insert($this -> table_out_tuan_finance_log, $row);
	}
	
	/**
	 * 得到收款纪录
	 * 
	 * @param int $id
	 */
	public function getFinanceLog($id) {
		$sql = "select * from {$this->table_out_tuan_finance_log} where finance_id=".$id;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 锁定
	 * 
	 * @param array $set
	 * @param array $ids
	 */
	public function lockFinance($set,$ids) {
		$this -> _db -> update($this -> table_out_tuan_finance, $set, $ids);
	}
	
	/**
	 * 解锁
	 * 
	 * @param array $set
	 * @param array $ids
	 */
	public function unlockFinance($set,$ids) {
		$this -> _db -> update($this -> table_out_tuan_finance, $set, $ids);
	}
	
	/**
	 * 重置lock
	 * 
	 * @param array $set
	 * @param string $where
	 */
	public function resetlock($set,$where) {
		$this -> _db -> update($this -> table_out_tuan_finance, $set, $where);
	}
	
	/**
	 * 更改期数订单的状态为已结款
	 * 
	 * @param array $set
	 * @param string $where 
	 */
	public function termOrderClear($set,$where) {
		$this -> _db -> update($this -> table_out_tuan_order, $set, $where);
	}
	
	/**
	 * 得到商品的product_id
	 * 
	 * @param int $goods_sn
	 */
	public function getProductID($goods_sn) {
		return $this -> _db -> fetchOne("select product_id from {$this->table_product} where product_sn=".$goods_sn);
	}
	
	/**
	 * 插入 shop_instock
	 * 
	 * @param array $row
	 */
	public function instockAdd($row) {
		$this -> _db -> insert($this -> table_instock, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 插入 shop_instock_detail
	 * 
	 * @param array $row
	 */
	public function instockDetailAdd($row) {
		$this -> _db -> insert($this -> table_instock_detail, $row);
	}
	
	/**
	 * 插入 shop_instock_plan
	 * 
	 * @param array $row
	 */
	public function instockPlanAdd($row) {
		$this -> _db -> insert($this -> table_instock_plan, $row);
	}
	
	/**
	 * 得到instock记录
	 * 
	 * @param int $item_no
	 */
	public function getInstock($item_no) {
		return $this -> _db -> fetchRow("select * from {$this->table_instock} where item_no='".$item_no."'");
	}
	
	/**
	 * 删除instock 记录
	 * 
	 * @param int $instock_id
	 */
	public function instockDelete($instock_id) {
		$where = $this -> _db -> quoteInto('instock_id = ?', $instock_id);
		$this -> _db -> delete($this->table_instock, $where);
	}
	
	/**
	 * 删除instock_detail 记录
	 * 
	 * @param int $instock_id
	 */
	public function instockDetailDelete($instock_id) {
		$where = $this -> _db -> quoteInto('instock_id = ?', $instock_id);
		$this -> _db -> delete($this->table_instock_detail, $where);
	}
	
	/**
	 * 删除instock_detail 记录
	 * 
	 * @param int $instock_id
	 */
	public function instockPlanDelete($instock_id) {
		$where = $this -> _db -> quoteInto('instock_id = ?', $instock_id);
		$this -> _db -> delete($this->table_instock_plan, $where);
	}
	
	/**
	 * 设置为取消单
	 * 
	 * @param int $instock_id
	 */
	public function instockCancel($instock_id) {
		$this -> _db -> update($this -> table_instock, array('is_cancel'=>1), " instock_id=".$instock_id);
	}
	
	/**
	 * 由团购订单ID得到退货的状态
	 * 
	 * @param int $order_id
	 */
	public function getReturnBillStatus($order_id) {
		$sql = "select b.bill_status from {$this->table_out_tuan_order} a left join {$this->table_instock} b on a.order_sn=b.item_no where a.id=".$order_id;
		return $status = $this -> _db -> fetchOne($sql);
	}
	
	/**
	 * 得到选中期数的总额
	 * 
	 * @param string $terms  (1,2,3...)
	 * 
	 * @return decimal
	 */
	public function getTermsAmount($terms) {
		$sql = "select sum(supply_price) from {$this->table_out_tuan_order} where term_id in ($terms) and ischeat=0 and isback=0";
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
	 * 插入shop_out_tuan_batch
	 * 
	 * @param float $batch
	 */
	public function batchAdd($batch) {
		$this -> _db -> insert($this -> table_out_tuan_batch, array('batch'=>$batch,'ctime'=>time()));
	}
	
	/**
	 * 更新shop_out_tuan_batch
	 * 
	 * @param array $set
	 * @param string $where
	 */
	public function batchUpdate($set, $where) {
		$this -> _db -> update($this -> table_out_tuan_batch, $set, $where);
	}
	
	/**
	 * 得到shop_out_tuan_batch列表
	 * 
	 */
	public function getBatch($search=null, $page=1, $pageSize=25, $orderby=null, $exclude=null, $all=false) {
		if(!$all){
			$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
			if ($page != null) {
			    $offset = ($page-1)*$pageSize;
			    $limit = " LIMIT  $pageSize  OFFSET $offset";
			}
		}
		
		$where = 'where 1=1';
		if($search){
			$search['id'] && $where.= ' and a.id='.$search['id'];
			$search['batch'] && $where.= ' and a.batch='.$search['batch'];
			if(!is_null($search['logistics']) && $search['check_stock']!=''){
				if($search['logistics']=='on'){$where .= " and a.logistics=1";}
				elseif($search['logistics']=='off'){$where .= " and a.logistics=0";}
				elseif($search['logistics']=='lt2'){$where .= " and a.logistics<2";}
				else{$where .= " and a.logistics='".$search['logistics']."'";}
			}
			if($search['tongbu']){
				$where.= ($search['tongbu']=='on')?" and a.tongbu=1":" and a.tongbu=0";
			}
			//日期 (订单同步时使用)
			if($search['ts']){
				$ts = time() - 86400*$search['ts']; //5天以前的批次
				$where .= " and a.ctime<$ts";
			}
			//xz字段
			$search['xz'] && $where.= ' and a.xz=0';
			//是否已经检测过库存
			if(!is_null($search['check_stock']) && $search['check_stock']!=''){
				if($search['check_stock'] == 'lt2'){
					$where.= ' and a.check_stock<2';
				}elseif($search['check_stock'] == 'gt0'){
					$where.= ' and a.check_stock>0';
				}else{
					$where.= ' and a.check_stock='.$search['check_stock'];
				}
			}
		}
		
		//排除
		if($exclude){
			$where.= " and a.id not in (".$exclude.")";
		}
		
		//排序
		if(!$orderby){ $orderby = ' id desc';}
		
		$sqlcount = "select count(a.id) from {$this->table_out_tuan_batch} a $where";
		$rs['tot'] = $this -> _db -> fetchOne($sqlcount);
		$sql = "select a.* from {$this->table_out_tuan_batch} a $where order by $orderby $limit";
		$rs['datas'] = $this -> _db -> fetchAll($sql);
		
		return $rs;
	}
	
	/**
	 * 删除shop_out_tuan_batch记录
	 * 
	 * @param int $batch
	 */
	public function batchDel($batch) {
		$batch = trim($batch);
		if($batch===''){return '请输入批次号';}
		//检查此批次下是否有订单
		/*$check = $this -> _db -> fetchRow("select id from {$this->table_out_tuan_order} where batch='".$batch."'");
		if($check){return '此批次下有订单，不能删除';}*/
		$where = $this -> _db -> quoteInto('batch = ?', $batch);
	    $this -> _db -> delete($this->table_out_tuan_batch, $where);
	    return '删除批次号成功（表 shop_out_tuan_batch 的记录）';
	}
	
	/* Start::外部团购订单同步到官网订单 */
	
	/**
	 * 得到 shop_order 表的记录
	 * 
	 * @param array $extendOrderSn
	 * 
	 * @return array
	 */
	public function getOrderByExtentOrderSn($extendOrderSn) {
		$sql = "select order_sn from {$this->table_order} where external_order_sn='".$extendOrderSn."'";
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
	 * 同步订单到官网DO
	 * 
	 * @param string $batch
	 */
	public function tongbuDo($batch) {
		
	}
	
	/**
	 * 判断地址是否存在于 shop_area
	 * 
	 * @param string $area
	 * @param string $parent_id
	 */
	public function isAreaInDB($area, $parent_id=null) {
		if($parent_id != null){$parent_id = " and parent_id ='".$parent_id."'";}
		$sql = "select area_id from {$this->table_area} where area_name='".$area."' $parent_id";
		return $this -> _db -> fetchOne($sql);
	}
	
	/**
	 * 得到地区下的子地区
	 * 
	 * @param int $pid
	 * 
	 * @return array
	 */
	public function getSubArea($pid) {
		$sql = "select area_id,area_name from {$this->table_area} where parent_id=".$pid;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 插入shop_order表
	 * 
	 * @param array $row
	 */
	public function insertIntoOrder($row) {
		$this -> _db -> insert($this -> table_shop_order, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 插入shop_order_batch表
	 * 
	 * @param array $row
	 */
	public function insertIntoOrderBatch($row) {
		$this -> _db -> insert($this -> table_shop_order_batch, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 插入shop_order_batch_goods表
	 * 
	 * @param array $row
	 */
	public function insertIntoOrderBatchGoods($row) {
		$this -> _db -> insert($this -> table_shop_order_batch_goods, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 由SN得到 product_id 和 goods_id
	 * 
	 * @param string $sn
	 */
	public function getProductIDGoodsIDbySN($sn) {
		$sql = "select a.product_id,b.goods_id,a.product_name,a.goods_style from {$this->table_product} a left join {$this->table_goods} b on a.product_id=b.product_id where a.product_sn='".$sn."'";
		return $this -> _db -> fetchRow($sql);
	}
	
	/**
	 * 由SN得到groupgoods的信息
	 * 
	 * @param string $sn
	 */
	public function getGroupGoodsBySN($sn) {
		$sql = "select * from {$this->table_group_goods} where group_sn='$sn'";
		return $this -> _db ->fetchRow($sql);
	}
	
	/**
	 * 插入shop_order_pay_log表
	 * 
	 * @param array $row
	 */
	public function insertIntoOrderPayLog($row) {
		$this -> _db -> insert($this -> table_shop_order_pay_log, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 插入shop_order_stock表
	 * 
	 * @param array $row
	 */
	public function insertIntoOutstock($row) {
		$this -> _db -> insert($this -> table_shop_outstock, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 插入shop_order_stock_detail表
	 * 
	 * @param array $row
	 */
	public function insertIntoOutstockDetail($row) {
		$this -> _db -> insert($this -> table_shop_outstock_detail, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 插入shop_transport表
	 * 
	 * @param array $row
	 */
	public function insertIntoTransport($row) {
		$this -> _db -> insert($this -> table_shop_transport, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 插入shop_transport_track表
	 * 
	 * @param array $row
	 */
	public function insertIntoTransportTrack($row) {
		$this -> _db -> insert($this -> table_shop_transport_track, $row);
		return $this -> _db -> lastInsertId();
	}
	
	/**
	 * 插入shop_batch_adjust表
	 * 
	 * @param array $row
	 */
	public function insertIntoOrderBatchAdjust($row) {
		$this -> _db -> insert($this -> table_shop_order_batch_adjust, $row);
		return $this -> _db -> lastInsertId();
	}
	/* End::外部团购订单同步到官网订单 */
	

	/**
	 * 得到goods资料
	 * 
	 * @param array $where
	 * @param string $fields
	 * 
	 * @return array;
	 */
	public function getGoods($search=null,$fields='*') {
		$where  = 'where 1=1 ';
		if($search){
			$search['goods_id'] && $where.=' and t1.goods_id='.$search['goods_id'];
			$search['goods_sn'] && $where.=' and t1.goods_sn='.$search['goods_sn'];
		}
	    $sql = "SELECT $fields FROM `$this->table_goods` as t1 left join `$this->table_product` as t2 on t1.product_id = t2.product_id {$where}  ";
	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 得到product资料
	 * 
	 * @param array $where
	 * @param string $fields
	 * 
	 * @return array;
	 */
	public function getProduct($search=null, $fields='*') {
		$where  = 'where 1=1 ';
		if($search){
			$search['product_id'] && $where.=' and t1.product_id='.$search['product_id'];
			$search['product_sn'] && $where.=' and t1.product_sn='.$search['product_sn'];
		}
	    $sql = "SELECT $fields FROM `$this->table_product` as t1 left join `$this->table_goods` as t2 on t1.product_id=t2.product_id {$where}";
	    return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 得到goods资料
	 * 
	 * @param array $where
	 * @param string $fields
	 * 
	 * @return array;
	 */
	public function getGroupGoods($search=null,$fields='*') {
		$where  = 'where 1=1 ';
		if($search){
			$search['group_sn'] && $where.=' and group_sn='.$search['group_sn'];
		}
	    $sql = "SELECT $fields FROM `$this->table_group_goods` {$where}  ";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 单个删除同步订单
	 * 
	 * shop_order
	 * shop_order_batch
	 * shop_order_batch_goods
	 * shop_order_batch_adjust
	 * shop_outstock
	 * shop_outstock_detail  这个没有 $sn 需要取出 shop_outstock 中的 $outstock_id
	 * shop_transport
	 * shop_transport_track
	 * 
	 * @param string $sn
	 */
	public function deleteTongbuOrder($sn) {
		$sn = trim($sn);
		if ($sn != '') {
			$where = $this -> _db -> quoteInto('order_sn = ?', $sn);
			//删除shop_order
			$this -> _db -> delete($this->table_shop_order, $where);
			//删除shop_order_batch
			$this -> _db -> delete($this->table_shop_order_batch, $where);
			//删除shop_order_batch_goods
			$this -> _db -> delete($this->table_shop_order_batch_goods, $where);
			//删除shop_order_batch_adjust
			$this -> _db -> delete($this->table_shop_order_batch_adjust, $where);
			
			/*//先取出outstock记录
			$ot = $this -> _db -> fetchOne("select outstock_id from {$this->table_shop_outstock} where $whereoutstock");
			if($ot){
				$whereoutstockdetail = $this -> _db -> quoteInto('outstock_id = ?', $ot);
				//删除shop_outstock_detail
				$this -> _db -> delete($this->table_shop_outstock_detail, $whereoutstockdetail);
			}
			//删除shop_outstock
			$this -> _db -> delete($this->table_shop_outstock, $whereoutstock);*/
			
			//删除shop_transport
			$whereoutstock = $this -> _db -> quoteInto('bill_no = ?', $sn);
			$this -> _db -> delete($this->table_shop_transport, $whereoutstock);
			//删除shop_transport_track
			$whereitem = $this -> _db -> quoteInto('item_no = ?', $sn);
			$this -> _db -> delete($this->table_shop_transport_track, $whereitem);
			
			return 'ok';
		}
	}
	
	/**
	 * 得到官网订单
	 * 
	 * @param string $fields
	 * @param string $where
	 */
	public function getGWOrder($fields, $where) {
		$sql = "select $fields from {$this->table_shop_order} $where";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 更新官网订单
	 * 
	 * @param string $order_sn
	 * @param int $ctime
	 */
	public function updateGWOrder($order_sn, $ctime) {
		if($order_sn){
			//shop_order
			$this -> _db -> update($this -> table_shop_order, array('add_time'=>$ctime), " order_sn='$order_sn'");
			//shop_order_batch
			$this -> _db -> update($this -> table_shop_order_batch, array('add_time'=>$ctime,'logistic_time'=>$ctime), " order_sn='$order_sn'");
			//shop_order_batch _goods
			$this -> _db -> update($this -> table_shop_order_batch_goods, array('add_time'=>$ctime), " order_sn='$order_sn'");
			//shop_order_batch_adjust
			$this -> _db -> update($this -> table_shop_order_batch_adjust, array('add_time'=>$ctime), " order_sn='$order_sn'");
			//shop_outstock
			$this -> _db -> update($this -> table_shop_outstock, array('add_time'=>$ctime), " bill_no='$order_sn'");
			//shop_transport
			$this -> _db -> update($this -> table_shop_transport, array('add_time'=>$ctime,'send_time'=>$ctime), " bill_no='$order_sn'");
			//shop_transport_track
			$this -> _db -> update($this -> table_shop_transport_track, array('op_time'=>$ctime), " item_no='$order_sn'");
		}
	}
	
	/**
	 * 修正 表
	 * shop_order_batch_goods字段：product_id、goods_id
	 * shop_outstock_detail字段：product_id
	 * 
	 * @param string $sn
	 * @param int $g_id
	 * @param int $p_id
	 */
	public function modifyGidPid($sn, $g_id, $p_id) {
		$this -> _db -> update($this -> table_shop_order_batch_goods, array('product_id'=>$p_id, 'goods_id'=>$g_id), " order_sn='$sn'");
		$sql = "update {$this -> table_shop_outstock} a left join {$this -> table_shop_outstock_detail} b on a.outstock_id=b.outstock_id set b.product_id=$p_id where a.bill_no='$sn'";
		$this -> _db -> execute($sql);
	}
	
	/**
	 * 插入shop_out_tuan_batch
	 * 
	 * @param array $batch
	 */
	public function addBatch($batch) {
		$this -> _db -> insert($this -> table_out_tuan_batch, $batch);
	}
	
	/**
	 * 得到 shop_order_batch_goods 记录
	 * 
	 * @param string $where
	 * @param string $fields
	 * 
	 * @return array
	 */
	public function getOrderBatchGoods($where=null, $fields='*', $page=1, $pageSize=5000) {
		$pageSize = ((int)$pageSize > 0) ? 5000 : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		$wheresql = ' where 1=1 ';
		if($where){
			$wheresql .= $where;
		}
		$sql = "select $fields from {$this->table_shop_order_batch_goods} $wheresql order by order_batch_goods_id desc $limit";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 更新 shop_order_batch_goods 表
	 * 
	 * @param array $set
	 * @param string $where
	 */
	public function updateOrderBatchGoods($set, $where) {
		$this -> _db -> update($this->table_shop_order_batch_goods, $set, $where);
	    return true;
	}
	
	/**
	 * 更新批次订单的下单时间
	 * 
	 * @param string $batch
	 * @param int    $ts
	 */
	public function updateOrderTime($batch, $ts) {
		$orders = $this -> getOutTuanOrder(array('batch'=>$batch), 1, 1, null, true);
		if($orders['tot']<1){return '不存在此批次订单';}
		//更新 shop_order和shop_order_batch 表 的 add_time 字段
		foreach ($orders['datas'] as $v){
			$sql = "update {$this->table_shop_order} as a left join {$this->table_shop_order_batch} as b on a.order_sn=b.order_sn set a.add_time='$ts', b.add_time='$ts' where a.external_order_sn='".$v['order_sn']."'";
			$this -> _db -> execute($sql);
		}
		//更新此批次的order_time
		$this -> _db -> update($this -> table_out_tuan_order, array('order_time'=>$ts), "batch=$batch");
		//
		return 'ok';
	}
	
	/**
	 * 得到批次商品统计
	 * 
	 * @param sting $batch
	 */
	public function getOutTuanOrderGoodsStat($batch) {
		$sql = "SELECT count(a.amount) as amt,b.goods_sn,b.p_id,b.g_id FROM `{$this->table_out_tuan_order}` as a left join `{$this->table_out_tuan_goods}` as b on a.goods_id=b.goods_id where a.batch='$batch'";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
	 * 只得到订单计数
	 * 
	 * @param array $search
	 * 
	 * @return int
	 */
	public function getOutTuanOrderCount($search=null) {
		if($search){
			$where = ' where 1';
			$search['batch'] && $where.=" and batch='".$search['batch']."'";
			!is_null($search['check_stock']) && $where.=" and check_stock='".$search['check_stock']."'";
			!is_null($search['logistics']) && $where.=" and logistics='".$search['logistics']."'";
			$sql = "select count(id) from {$this->table_out_tuan_order} $where";
			return $this -> _db -> fetchOne($sql);
		}else{
			return 0;
		}
	}
	
	/**
	 * 处理官网重复订单：
	 * 1.取得官网重复订单
	 * 2.更新
	 * 
	 * group by  是"升序"
	 * 比如
	 * 2484   21206011609223734
	 * 2485   21206011609223734
	 * 这时只会取得  2484   21206011609223734
	 * 
	 */
	public function modifyRepeatOrder() {
		$sql = 'select order_id,order_sn,count(order_sn) as ct from `shop_order` group by order_sn having ct>1';
		$orders = $this -> _db -> fetchAll($sql);
		if(is_array($orders) && count($orders)){
			foreach ($orders as $v){
				$sn = $v['order_sn'].'1';
				if(!$sn){echo '重新生成单号错误'; exit;}
				//更新shop_order
				$sql_update = "update shop_order set order_sn='".$sn."',batch_sn='".$sn."' where order_sn='".$v['order_sn']."' limit 1";//这样也是按照"升序"来的，正好对应
				$this -> _db -> execute($sql_update);
				//更新shop_order_batch
				$sql_update = "update shop_order_batch set order_sn='".$sn."',batch_sn='".$sn."' where order_sn='".$v['order_sn']."' limit 1";
				$this -> _db -> execute($sql_update);
				//更新shop_order_batch_goods
				$sql_update = "update shop_order_batch_goods set order_sn='".$sn."',batch_sn='".$sn."' where order_sn='".$v['order_sn']."' and order_id='".$v['order_id']."'";
				$this -> _db -> execute($sql_update);
				//更新shop_order_batch_adjust
				$sql_update = "update shop_order_batch_adjust set order_sn='".$sn."',batch_sn='".$sn."' where order_sn='".$v['order_sn']."' limit 1";
				$this -> _db -> execute($sql_update);
				//更新shop_outstock
				$sql_update = "update shop_outstock set bill_no='".$sn."' where bill_no='".$v['order_sn']."' limit 1";
				$this -> _db -> execute($sql_update);
				//更新shop_transport
				$sql_update = "update shop_transport set bill_no='".$sn."' where bill_no='".$v['order_sn']."' limit 1";
				$this -> _db -> execute($sql_update);
				//更新shop_transport_track
				$sql_update = "update shop_transport_track set item_no='".$sn."' where item_no='".$v['order_sn']."' limit 1";
				$this -> _db -> execute($sql_update);
			}
			echo '<pre>';
			echo '本次处理'.count($orders).'条记录';
			print_r($orders);
			echo '</pre>';
		}else{
			echo '没有重复';
		}
	}
	
    /**
     * 得到某张表
     * 
     * @param string $table
     * @param string $fields
     * @param string/array $search
     * @param int $page
     * @param int $pageSize
     * @param string $orderBy
     * 
     * @return array
     */
    public function getTable($table=null, $fields='*', $search=null, $page=null, $pageSize=50, $orderBy=null) {
    	if($table == null){return null;} $table = trim($table); if($table == ''){return null;}
    	
    	$limit = null;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = " LIMIT  $pageSize  OFFSET $offset";
		}
		
		$where = ' where 1 ';
		if(is_string($search) && trim($search)){
			$where .= 'and '.$search;
		}elseif(is_array($search) && count($search)){
			foreach ($search as $k=>$v){
				$where .= " and ".$k."='".$v."'";
			}
		}
		
		$rs = array();
		$rs['tot'] = $this -> _db -> fetchOne("select count(*) from $table $where");
		$rs['datas'] = $this -> _db -> fetchAll("select $fields from $table $where $orderBy $limit");
		return $rs;
    }
    
	/**
     * 更新某张表
     * 
     * @param string $table
     * @param array $set
     * @param string $where
     */
    public function updateTable($table=null, $set=array(), $where=null) {
    	if($table == null){return null;} $table = trim($table); if($table == ''){return false;}
    	if($where == null){return null;} $where = trim($where); if($where == ''){return false;}
    	if(!is_array($set)){return false;}
    	if(empty($set)){return false;}
    	$this -> _db -> update($table, $set, $where);
    }
    
    /**
     * 执行select sql
     * 
     */
    public function execSql($sql){
    	$flag = strtolower(substr($sql, 0, 6));
    	if($flag == 'select'){
    		return $this -> _db -> fetchAll($sql);
    	}
    	return false;
    }

	/**
     * 统计团购产品限价
     * 
     *
     * @return   int
     */
	public function getOuttuanGoodsLimitPriceTotal()
	{
		$sql = "SELECT COUNT(*) AS count FROM `{$this->table_out_tuan_goods}` o 
				LEFT JOIN `shop_product` p ON o.goods_sn = p.product_sn
				WHERE p.price_limit*o.goods_number > o.supply_price";

		return $this->_db->fetchOne($sql);
	}
}
