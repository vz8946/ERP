<?php
/**
 * 夜总惠订阅提醒功能
 */
require_once "global.php";

$db = Zend_Registry::get('db');
$datas = $db->fetchAll("SELECT * FROM shop_market_notice WHERE status=0");


//发送定时任务
foreach($datas as $val)
{
	$sms= new  Custom_Model_Sms();
	$start_date = date('Y-m-d').' 18:00';
	$response = $sms->send($val['mobile'],"您关注的“垦丰商城-夜总惠”商品“{$val['goods_name']}”将在“{$start_date}”准时开抢哦，切勿错过，谢谢！  http://jiankang");
	if($response)
	{
		$db->update('shop_market_notice',array('status'=>1),"id={$val['id']}");
	}else{
		$db->update('shop_market_notice',array('status'=>2),"id={$val['id']}");
	}
}