<?php
require_once "global.php";

$apiDrt = new Admin_Models_API_Drt();
$list_drt = $apiDrt->getAll();
$arr_drt = array('index_baokuan','index_new','index_prmt','index_dapei',
	'index_floor1','index_floor2','index_floor3','index_floor4','index_floor5',
	'index_floor6','index_floor7');
	
foreach ($list_drt as $k=>$v){
    $apiDrt->refresh($v['name'],'file',Zend_Registry::get('systemRoot'));
}

$memcachedapi = new Custom_Model_Memcached('index');
$memcachedapi -> clean('index');

exit('操作成功');