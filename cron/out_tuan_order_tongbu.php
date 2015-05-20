<?php
require_once "global.php";

header("Content-type:text/html;Charset=utf-8;");

function tongbu(){
	$api = new Admin_Models_API_OutTuan();
	$datas = $api->getBatch(array('logistics'=>2,'tongbu'=>'off','ts'=>0.25,'check_stock'=>2), 1, 25);
	if($datas['tot']<1){exit('没有要同步的批次');}
	$datas = $datas['datas'];
	$n = (count($datas)<=5) ? count($datas) : 5;
	$arrayRand = array_rand($datas, $n);
	if(!is_array($arrayRand)){$array_rand[] = $arrayRand;}else{$array_rand = $arrayRand;}
	$batch = array();
	foreach ($array_rand as $v){
		$stime = time();
		//取出店铺
		$shop = $api->getOutTuanOrder(array('batch'=>$datas[$v]['batch']),1,1,null,false);
		//同步
		$rs = $api->tongbuDo($datas[$v]['batch']);
		//日志
		$shopapi = new Admin_Models_API_Shop();
		$shopapi->addSyncLog($shop['datas'][0]['shop_id'], 'tuan', $stime, $datas[$v]['batch'].'_'.$rs);
		$batch[] = $datas[$v]['batch'];
		echo $datas[$v]['batch'].'_'.$rs.'<br>';
	}
	exit;
}

tongbu();