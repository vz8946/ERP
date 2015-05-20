<?php
require_once "global.php";
class Deal_complete_order
{
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}

	/**
	 * 获取符合条件的订单
	 *
	 * return   array
	 **/
	public function getCompleteOrder() {
		$field = array(
			'o.user_id',
			'o.user_name',
			'b.order_id',
			'b.price_payed',
			'b.price_from_return',
			'b.price_logistic',
			'b.batch_sn',
			'b.order_batch_id',
			'b.is_fav',
		);

		$_condition[] = "b.status = '0'";
		$_condition[] = "(b.status_logistic = '3' or b.status_logistic = '4')";
		$_condition[] = "b.status_pay = '2'";
		$_condition[] = "b.add_time < ".(time()-86400*40);
		$_condition[] = "(b.is_fav is null or b.is_fav < 1)";
		$_condition[] = "o.user_id > '10' AND shop_id = 1";

		$_join[] = "LEFT JOIN `shop_order` o ON b.order_id = o.order_id";

		$sql = "SELECT ". implode(', ', $field) ." FROM `shop_order_batch` b ". implode(' ', $_join) ." WHERE ".implode(' AND ', $_condition);

    	//$sql = "select a.user_id,a.user_name,b.*,c.member_id from {$this->_table_order} a left join {$this->_table_order_batch} b on a.batch_sn=b.batch_sn left join {$this->_table_member} c on a.user_id=c.user_id where b.status=0 and (b.status_logistic=3 or b.status_logistic=4 )and b.status_pay=2 and b.add_time<".(time()-86400*40)." and (b.is_fav is null or b.is_fav<1) and  a.user_id > '10'  and shop_id = 1 limit 1000";
    	return $this->_db->fetchAll($sql);
    }

	/**
	 * 操作相关订单数据
	 *
	 * @return   boolean
	 **/
	public function dealCompleteOrder()
	{
		$order_infos = $this->getCompleteOrder();
		if (empty($order_infos)) {
			$this->_error = "没有相关订单信息";
			return false;
		}

		$params = array(
			'is_fav' => '1',
			'logistic_list' => '',

		);

		$time = time();
		foreach ($order_infos as $info) {
			if (empty($info['batch_sn'])) {
				continue;
			}

			$this->updateOrderInfoByBatchSn($info['batch_sn'],$params);

			$shopConfig['fav_point'] = 1;
			$priceFromReturn = 0;
			if ($info['is_fav'] != -1) {
				$priceFromReturn = $info['price_from_return'];
			}

			$point = intval(($info['price_payed'] + $priceFromReturn - $info['price_logistic']) * $shopConfig['fav_point']);

			if ($point < 0) {
				$point = 0;
			}

			$member = $this->getMemberInfoByUserId($info['user_id']);
			$point_param = array(
				 'member_id'   => $member['member_id'],
				 'user_name'   => $info['user_name'],
				 'order_id'    => $info['order_id'],
				 'point'       => $point,
				 'point_total' => $member['point'] + $point,
				 'batch_sn'    => $info['batch_sn'],
				 'add_time'    => $time,
				 'admin_name'  => 'system',
				 'note'        => '满意不退货赠送积分'.$info['batch_sn'],
			);
			
			if (empty($point)) {
				continue;
			}
			$this->editAccountByMemberId($member['member_id'], 'point', $point_param);

			$experience_param = array(
				'member_id'        => $member['member_id'],
				'batch_sn'         => $info['batch_sn'],
				'experience_total' => $member['experience'] + $point,
				'experience'       => $point,
				'remark'           => '满意不退货赠送经验值'.$info['batch_sn'],
				'created_by'       => 'system',
			);


			$this->editAccountByMemberId($member['member_id'], 'experience', $experience_param);

			$this->updateMemberInfoByMemberId($member['member_id'], array('last_experience_time' => date('Y-m-d H:i:s')));
			$this->changeMemberRank($member['member_id']);
		}

		return true;
	}


	/**
	 * 根据会员ID更新会员相关信息
	 *
	 * @return   boolean
	 **/
	public function editAccountByMemberId($member_id, $type, $params)
	{
		switch ($type) {
			case 'point':
				$this->updatePointByMemberId($member_id, $params['point']);
				$this->addPointLog($params);
				return true;
			break;
			case 'experience';
				$this->updateExperienceByMemberId($member_id, $params['experience']);
				$this->addExperienceLog($params);
				return true;
			break;
		}

		return true;
	}

	/**
	 * 根据用户ID获取会员信息
	 *
	 * @return   array
	 **/
	public function getMemberInfoByUserId($user_id)
	{
		$user_id = intval($user_id);

		$sql = "SELECT * FROM `shop_member` WHERE user_id = '{$user_id}' LIMIT 1";

		return $this->_db->fetchRow($sql);
	}

	/**
	 * 根据batch_sn更新订单信息
	 *
	 * @param   string
	 *
	 * @return  boolean
	 **/
	public function updateOrderInfoByBatchSn($batch_sn, $params)
	{
		$params = array(
			'is_fav' => $params['is_fav'],
			'logistic_list' => $params['logistic_list'],

		);
		return $this->_db->update('shop_order_batch', $params, "batch_sn = '{$batch_sn}'");
	}

	/**
	 * 根据会员ID更新积分
	 *
	 * @param   int
	 * @param   int
	 *
	 * @return  boolean
	 **/
	public function updatePointByMemberId($member_id, $point)
	{
		$sql = "UPDATE `shop_member` SET point = point + '{$point}' WHERE member_id = '{$member_id}' limit 1";

		return (bool) $this->_db->execute($sql);
	}

	/**
	 * 根据会员ID更新经验值
	 *
	 * @param   int
	 * @param   int
	 *
	 * @return  boolean
	 **/
	public function updateExperienceByMemberId($member_id, $experience)
	{
		$sql = "UPDATE `shop_member` SET experience = experience + '{$experience}' WHERE member_id = '{$member_id}' limit 1";

		return (bool) $this->_db->execute($sql);
	}

	/**
	 * 增加积分操作记录
	 *
	 * @param   array
	 *
	 * @return  boolean
	 **/
	public function addPointLog($params)
	{
		$params = array(
			'member_id' => $params['member_id'],
			'order_id'  => $params['order_id'],
			'point_total' => $params['point_total'],
			'point'       => $params['point'],
			'batch_sn'    => $params['batch_sn'],
			'note'        => $params['note'],
			'add_time'    => $params['add_time'],
			'admin_name'  => $params['admin_name'],
			'user_name'   => $params['user_name'],
		);

		return (bool) $this->_db->insert('shop_member_point', $params);
	}

	/**
	 * 增加经验值操作记录
	 *
	 * @param   array
	 *
	 * @return  boolean
	 **/
	public function addExperienceLog($params)
	{
		$params = array(
			'member_id'        => $params['member_id'],
			'batch_sn'         => $params['batch_sn'],
			'experience_total' => $params['experience_total'],
			'experience'       => $params['experience'],
			'remark'           => $params['remark'],
			'created_by'       => $params['created_by'],
		);

		return (bool) $this->_db->insert('shop_member_experience', $params);
	}

	/**
	 * 更改会员等级
	 *
	 * @param    int
	 *
	 * @return   boolean
	 **/
	public function changeMemberRank($member_id)
	{
		if (intval($member_id) < 1) {
			$this->_error = '会员ID不正确';
			return false;
		}

		$member_info = $this->getMemberInfoByMemberId($member_id);

		if (empty($member_info)) {
			$this->_error = '没有相关会员信息';
			return false;
		}

		$rank_info  = $this->getRankInfoByExperience($member_info['experience']);

		if (empty($rank_info)) {
			return true;
		}

		if ($member_info['rank_id'] != $rank_info['rank_id']) {
			$this->updateMemberInfoByMemberId($member_info['member_id'], array('rank_id' => $rank_info['rank_id']));
			$log_params = array(
				'member_id'    => $member_info['member_id'],
				'last_rank_id' => $member_info['rank_id'],
				'rank_id'      => $rank_info['rank_id'],
				'remark'       => '会员等级升级',

			);
			$this->addMemberRankLog($log_params);
		}

		return true;
	}

	/**
	 * 根据会员ID获取会员信息
	 *
	 * @param    int
	 *
	 * @return   array
	 **/
	public function getMemberInfoByMemberId($member_id)
	{
		$member_id = intval($member_id);
		if ($member_id < 1) {
			$this->_error = '会员ID不正确';
			return false;
		}

		$sql = "SELECT `member_id`, `rank_id`, `experience` FROM `shop_member` WHERE `member_id` = '{$member_id}'";

		return $this->_db->fetchRow($sql);
	}

	/**
	 * 获取经验值对应的等级
	 *
	 * @param    array
	 *
	 * @return   array
	 **/
	public function getRankInfoByExperience($experience)
	{
		$experience = intval($experience);

		$sql = "SELECT `rank_id`, `rank_name` FROM `shop_member_rank` WHERE min_point <= '{$experience}' AND max_point > '{$experience}'";

		return $this->_db->fetchRow($sql);
	}

	/**
	 * 更新会员信息
	 *
	 * @param    int
	 * @param    array
	 *
	 * @return   array
	 **/
	public function updateMemberInfoByMemberId($member_id, $params)
	{
		$member_id = intval($member_id);
		if ($member_id < 1) {
			$this->_error = '会员ID不正确';
			return false;
		}
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		if (count($params) < 1) {
			$this->_error = '没有需要更新的数据';
			return false;
		}

		return (bool) $this->_db->update('shop_member', $params, "member_id = '{$member_id}'");
	}

	/**
	 * 添加等级操作日志
	 *
	 * @param    array
	 *
	 * @return   boolean
	 **/
	public function addMemberRankLog($params)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $params = Custom_Model_Filter::filterArray($params, $filterChain);

		if (count($params) < 1) {
			$this->_error = '没有需要更新的数据';
			return false;
		}

		return (bool) $this->_db->insert('shop_member_rank_log', $params);
	}
	

	public function getError()
	{
		return $this->_error;
	}
	
}


$order = new Deal_complete_order();
if (false === $order->dealCompleteOrder()) {
	exit($order->getError());
}


exit('操作成功');