<?php
require_once "global.php";

class Initial_experience
{
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}

	public function getOrderInfosByCondition($params)
	{
		!empty($params['fav']) && $_condition[] = "is_fav = '{$params['fav']}'";

		$sql = "SELECT `order_batch_id`, b.`batch_sn`, `is_fav`, `price_payed`, `price_from_return`, `price_logistic`, o.user_id FROM `shop_order_batch` b 
			   LEFT JOIN `shop_order` o on  b.order_id = o.order_id
			   WHERE ".implode(' AND ', $_condition);
		return $this->_db->fetchAll($sql);
	}

	public function updateMemberExperience($params)
	{
		$order_infos = $this->getOrderInfosByCondition($params);
		if (empty($order_infos)) {
			$this->_error = '没有相关订单信息';
			return false;
		}

		

		$infos = array();
		foreach ($order_infos as $info) {
			$experience = intval($info['price_payed'] + $info['price_from_return'] - $info['price_logistic']);
			if (isset($infos[$info['user_id']])) {
				$infos[$info['user_id']] += $experience;
			} else {
				$infos[$info['user_id']] = $experience;
			}
		}

		if (false === $this->updateMemberInfosByCondition($infos)) {
			return false;
		}
	}

	public function updateMemberInfosByCondition($infos)
	{
		foreach ($infos as $key => $info) {
			$sql = "UPDATE `shop_member` SET experience = '{$info}' WHERE user_id = '{$key}'";
			if (false === $this->_db->execute($sql)) {
				$this->_error = "更新经验值数据失败";
				return false;
			}
		}

		return true;
	}

	public function getError()
	{
		return $this->_error;
	}
	
}

$order_param = array(
	'fav' => '1',
);
$experience = new Initial_experience();
if (false === $experience->updateMemberExperience($order_param)) {
	exit($experience->getError());
}

exit('操作成功');