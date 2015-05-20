<?php
exit;
/**
 * 修正：
 * order_batch_goods的product_id、goods_id
 * shop_outstock_detail的product_id
 */
require_once "global.php";

header("Content-type:text/html;Charset=utf-8;");

function xz(){
	$api = new Admin_Models_API_OutTuan();
	$datas = $api->getBatch(array('logistics'=>'on','tongbu'=>'on','xz'=>1), 1, 25);
	//
	if($datas['tot']<1){
		//日志
		error_log(date("Y-m-d H:i:s")." | 修正完成\r\n", 3, $_SERVER['DOCUMENT_ROOT'].'/xz.txt');
		exit;
	}
	//
	$rand = rand(0, (count($datas['datas'])-1));
	$rs = $api->modifyGidPid($datas['datas'][$rand]['batch']);
	//日志
	$rs = $datas['datas'][$rand]['batch'].'_'.$rs;
	error_log(date("Y-m-d H:i:s")." | $rs\r\n", 3, $_SERVER['DOCUMENT_ROOT'].'/xz.txt');
	exit($rs);
}

xz();