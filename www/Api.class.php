<?php
header("Content-Type:text/html;charset=utf-8");
date_default_timezone_set('PRC');
class Api{
	private $conn;
	private $host = 'localhost';
	private $root = 'root';
	private $password = '';
	private $dbname = 'jiankang';
	private $prefix = 'shop_';
	public function __construct(){
		$this->connect($this->host,$this->root,$this->password);
	}
	private function connect($host,$root,$password){
		$this->conn = mysql_connect($host,$root,$password);
		mysql_select_db($this->dbname,$this->conn);
		$this->query('set names utf8');
	}
	public function getSql($tbname,$field = '*',$where = '1',$orderBy = '',$limit = ''){
		return "select $field from ".$this->prefix.$tbname." where $where $orderBy $limit";
	}
	public function numRows($sql){
		$numRows = @mysql_num_rows($this->query($sql));
		return intval($numRows);
	}
	public function getAll($sql){
		$rows = array();
		$res = $this->query($sql);
		if($res){
			while($row = mysql_fetch_assoc($res)){
				$rows[] = $row;
			}
		}
		return $rows;
	}
	public function _post(){
		$post = isset($_REQUEST) ? $_REQUEST : "";
		$params = array();
		if($post){
			foreach($post as $key=>$val){
				$params[$key] = $val;
			}
		}
		return $params;
	}
	public function json($array,$msg = ""){
		if(!$msg)$msg = array('成功！','失败');
		if($array){
			$data   = $array;
			$msg	= $msg[0];
			$status = '0';
		}else{
			$data   = array();
			$msg    = $msg[1];
			$status = '1';
		}
		echo json_encode(array('Code'=>$status,'Desc'=>$msg,'Response'=>$data));
		die;
	}
	public function getOne($sql){
		return @mysql_fetch_assoc($this->query($sql));
	}
	public function query($sql){
		return mysql_query($sql);	
	}
	public function page($page = 1 ,$pagesize = '20'){
		if($page && intval($page) < 1)	$page = 1;
		if($pagesize && intval($pagesize) < 1) $pagesieze = 20;
		$pagenum = ($page-1)*$pagesize;
		if($pagenum < 0) $pagenum = 0;
		$limit = $pagenum.','.$pagesize;
		return $limit;
	}
	public function __destruct(){
		mysql_close($this->conn);
	}
}

$act = isset($_REQUEST['act'])? $_REQUEST['act'] : "";
$api = new Api();
/*
 *获取订单列表
 *参数：status : 0 正常单; 1 取消单; 2 无效单; 3:渠道刷单 4:不发货订单 5:预售订单
 *      status_logistic : 0 未确认 1 已确认待收款 2 待发货 3 已发货 4 客户已签收 5 客户已拒收 6 部分签收
        status_pay : 0 未收款; 1 未退款; 2 已付款
 *      status_return : 0 正常单;1 退货单;2.换货单
 *	    page 页码
 *      pagesize 每页记录条数，默认为20条
*/
if($act == 'getOrderInfos'){
	$params = $api->_post();
	$params['status_pay'] = '2';
	$where = "1";
	$where .= isset($params['status']) ? " and b.status = '{$params['status']}'" : '';
	$where .= isset($params['status_logistic']) ? " and b.status_logistic = '{$params['status_logistic']}'" : '';
	$where .= isset($params['status_pay']) ? " and b.status_pay = '{$params['status_pay']}'" : '';
	$where .= isset($params['status_return']) ? " and b.status_return = '{$params['status_return']}'" : '';
	$where .= isset($params['start_time']) ? " and o.update_time >= '{$params['start_time']}'" : '';
	$where .= isset($params['end_time']) ? " and o.update_time <= '{$params['end_time']}'" : '';
	$page = isset($params['page']) ? intval($params['page']) : '1';
	$pagesize = isset($params['pagesize']) ? intval($params['pagesize']) : '20';
	$rows['Count'] = $api->numRows("select o.order_id from shop_order as o left join shop_order_batch as b on o.order_id = b.order_id where $where");
	$rows['data'] = $api->getAll("select o.order_id,o.order_sn,o.add_time,o.update_time,o.invoice,o.invoice_type,o.invoice_content,o.warehouse_id as IsDelivery,o.licence,o.Tariff,b.price_order,b.price_goods,b.price_pay,b.price_payed as PayAccount,b.addr_consignee as DtDeliveryName,b.addr_province as DtDeliveryProvince,b.addr_city as DtDeliveryCity,b.addr_area as DtDeliveryDistrict,addr_address as DtDeliveryAddress,b.addr_mobile as DtDeliveryPhone1,b.addr_tel as DtDeliveryPhone2,b.addr_zip as DtDeliveryPostcode,b.status,b.status_logistic,b.status_pay,b.pay_type,b.pay_name,b.note,b.note_staff from shop_order as o left join shop_order_batch as b on o.order_id = b.order_id where $where order by o.order_id desc limit ".$api->page($page,$pagesize));
	if($rows['data']){
		foreach($rows['data'] as $key=>$val){
			$rows['data'][$key]['DtExternalCode'] = $val['order_sn'];
			if($val['IsDelivery'] > 0){
				$warehouse_sn = $api->getOne("select warehouse_sn from shop_warehouse where warehouse_id = '{$val['IsDelivery']}'");
				$rows['data'][$key]['IsDelivery'] = 1;
				$rows['data'][$key]['DeliveryShop'] = $warehouse_sn['warehouse_sn'];
			}else{
				$rows['data'][$key]['DeliveryShop'] = '';
			}
			$tmp_id = $api->getOne("select MdIdentityCode from shop_product where product_id = (select product_id from shop_order_batch_goods where order_id = '{$val['order_id']}')");
			$rows['data'][$key]['MdIdentityCode'] = $tmp_id['MdIdentityCode'];
			$rows['data'][$key]['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
			$rows['data'][$key]['ShopType'] = 'B2C';	
			$rows['data'][$key]['IsContract'] = '0';//是否合同
			$rows['data'][$key]['ContractNumber'] = '';//合同号
			if($val['pay_type'] == 'cash')$rows['data'][$key]['pay_type'] = 'C';
				
		}
	}
	$api->json($rows);
}
/*
 *修改订单状态
 *参数：status : 0 正常单; 1 取消单; 2 无效单; 3:渠道刷单 4:不发货订单 5:预售订单
 *      status_logistic : 0 未确认 1 已确认待收款 2 待发货 3 已发货 4 客户已签收 5 客户已拒收 6 部分签收
        status_pay : 0 未收款; 1 未退款; 2 已付款
 *      status_return : 0 正常单;1 退货单;2.换货单
*/
if($act == 'updateOrderByOrderSn'){
	$params = $api->_post();
	$data = array();
	if(isset($params['status']))	$data[] = "status = '{$params['status']}'";
	if(isset($params['status_logistic']))	$data[] = "status_logistic = '{$params['status_logistic']}'";
	if(isset($params['status_pay']))	$data[] = "status_pay = '{$params['status_pay']}'";
	if(isset($params['status_return']))	$data[] = "status_return = '{$params['status_return']}'";
	$data = implode(',',$data);
	if(!isset($params['order_sn']) or !$data){
		echo json_encode(array('Code'=>'1','Desc'=>'参数错误!','Response'=>''));
		die;
	}
	$api->query("update shop_order set update_time = '".date('Y-m-d H:i:s')."' where order_sn = '{$params['order_sn']}'");
	$res = $api->query("update shop_order_batch set $data where order_sn = '{$params['order_sn']}'");
	$api->json($res);
}
if($act =='getOrderInfoByOrderCode'){	
	$params = $api->_post();	
	$res = $api->getOne("select o.order_id,o.order_sn,o.user_id,o.add_time,o.update_time,o.invoice,o.invoice_type,o.invoice_content,o.warehouse_id as IsDelivery,o.licence,o.Tariff,b.price_order,b.price_goods,b.price_pay,b.price_payed as PayAccount,b.addr_consignee as DtDeliveryName,b.addr_province as DtDeliveryProvince,b.addr_city as DtDeliveryCity,b.addr_area as DtDeliveryDistrict,addr_address as DtDeliveryAddress,b.addr_mobile as DtDeliveryPhone1,b.addr_tel as DtDeliveryPhone2,b.addr_zip as DtDeliveryPostcode,b.pay_type,b.pay_name,b.status,b.status_logistic,b.status_pay,b.note,b.note_staff from shop_order as o  left join shop_order_batch as b on o.order_id = b.order_id where o.order_sn = '{$params['order_sn']}'");
	if($res){
		if($res['IsDelivery'] > 0){
			$warehouse_sn = $api->getOne("select warehouse_sn from shop_warehouse where warehouse_id = '{$res['IsDelivery']}'");
			$res['IsDelivery'] = 1;
			$res['DeliveryShop'] = $warehouse_sn['warehouse_sn'];
		}
		$res['DtExternalCode'] = $res['order_sn'];
		$res['ShopType'] = 'B2C';
		$res['IsContract'] = '0';//是否合同
		$res['ContractNumber'] = '';//合同号
		if($res['pay_type'] == 'cash')$res['pay_type'] = 'C';
		$res['add_time'] = date('Y-m-d H:i:s',$res['add_time']);
		$res['order_goods_info'] = $api->getAll("select p.MdIdentityCode,g.goods_name as DrDesc
,g.number as DrQty
,g.sale_price as DrPriceAftDiscount1
,g.price as DrOriginPrice
 from shop_order_batch_goods g left join shop_product p on g.product_id = p.product_id where g.order_id = '{$res['order_id']}' order by g.add_time desc");
		/*if($res['order_goods_info']){
			foreach($res['order_goods_info'] as $key=>$val){
				$res['order_goods_info'][$key]['DrOriginPrice'] = '10000';
			}
		}*/
		unset($res['order_id']);
		unset($res['order_sn']);
	}
	$api->json($res);
}
if($act == 'getCustomerInfoByCustomerId'){
	$params = $api->_post();
	$res = $api->getOne($api->getSql('member','*',"member_id = '{$params['member_id']}'"));
	$res['ShopType'] = 'B2C';
	$api->json($res);
}
?>