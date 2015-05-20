<?php
require_once "global.php";

//header("Content-type:text/html;Charset=utf-8;");

function get_csv_file($file)
{
	global $db;
	$sql = "truncate table `shop_stock_position`";
	$db->execute($sql);

	$sql = "truncate table shop_stock_product_position";
	$db->execute($sql);

	if (!is_file($file)) {
		exit('没有相关文件');
	}

	$file = fopen($file, 'r');
	$infos = array();
	while ($info = fgetcsv($file)) { //每次读取CSV里面的一行内容
		$infos[] = $info;
	}
	if (count($infos) < 1) {
		exit('没有数据');
	}

	unset($infos[0]);
	echo '数据原总数'. count($infos);
	return $infos;
}

function insert_position_no($position_no)
{
	global $db;
	if (empty($position_no)) {
		return 0;
	}

	if (preg_match("/[\x7f-\xff]/", $position_no)) {
		return 0;
	}
	$position_params = array('position_no' => $position_no,'district_id' => 1, 'status' => '0', 'add_time' => time());
	$db->insert('shop_stock_position', $position_params);
	$id = $db->lastInsertId();
	return $id;
}

function initial_product_position($file)
{
	$infos = get_csv_file($file);
	global $db;
	$i = 0;
	foreach ($infos as $info) {
		

		$position_id = insert_position_no($info[1]);

		if (empty($position_id)) {
			$i++;
			continue;
		}

		if (empty($info[0]) && empty($info[2])) {
			$i++;
			continue;
		}

		$product_id = getProductIdBysn($info[0]);
		
		if (empty($product_id)) {
			$i++;
			continue;
		}

		$product_param = array(
			'product_id' => $product_id,
			//'product_sn' => $info[0],
			//'barcode'    => $info[2],
			'position_id' => $position_id,
			//'position_no' => $info[1],
		);
		$product_params[] = $product_param;

		$db->insert('shop_stock_product_position', $product_param);
	}
	echo '产品ID:库位为空的总数'.$i;
	return $product_params;
}

function getProductIdBysn($product_sn)
{
	global $db;
	if (empty($product_sn)) {
		return 0;
	}
	$_condition = "product_sn = '{$product_sn}'";

	$sql = "select product_id FROM `shop_product` WHERE ". $_condition;

	return $db->fetchOne($sql);
}

function getPositionByPositionCode($position_code) 
{
	global $db;
	if (empty($position_code)) {
		return 0;
	}

	$sql = "SELECT `position_id` FROM shop_stock_position WHERE position_no = '{$position_code}' limit 1";

	$position_id = $db->fetchOne($sql);

	return $position_id;
}

$file = './product_position.csv';

$infos = initial_product_position($file);
echo '<br>插入关联成功的数据总数',count($infos);