<?php
require_once "global.php";


function initial_transport_source()
{
	global $db;

	$db->execute("truncate table `shop_transport_source`");
	$sql = "SELECT t.bill_no, t.tid, o.outstock_id FROM shop_transport t LEFT JOIN `shop_outstock` o ON t.bill_no = o.bill_no";

	$infos = $db->fetchAll($sql);
	if (count($infos) < 1) {
		return array();
	}

	$transport_sources = array();
	foreach ($infos as $info) {
		if (empty($info['outstock_id']) || empty($info['tid'])) {
			continue;
		}
		if (strpos($info['bill_no'], ',') !== false) {
			$bill_nos = explode(',', $info['bill_no']);
			foreach ($bill_nos as $bill_no) {
				$transport_sources[] = array(
				'bill_no'      => $bill_no,
				'transport_id' => $info['tid'],
				'outstock_id'  => $info['outstock_id'],
			);
			}
		} else {
			$transport_sources[] = array(
				'bill_no'      => $info['bill_no'],
				'transport_id' => $info['tid'],
				'outstock_id'  => $info['outstock_id'],
			);
		}
	}

	foreach ($transport_sources as $info) {
		$db->insert('shop_transport_source', $info);
	}
	return true;
}


$bill_nos = initial_transport_source();
if (is_array($bill_nos)) {
	exit('没有相关数据');
}
if (true === $bill_nos) {
	exit('操作成功');
}