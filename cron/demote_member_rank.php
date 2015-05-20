<?php
require_once "global.php";
/**
 * 降级会员等级
 *
 **/
class Demote_member_rank
{
	private $_glass = array(
		'1' => '1',
		'2' => '1001',
		'3' => '10001',
		'4' => '20001',
		'5' => '50001',
		'6' => '10000000',
	);
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}

	/**
	 * 获取所要降级的数据
	 *
	 * @return array
	 **/
	public function getMemberDemote()
	{
		$date = date('Y-m-d H:i:s', strtotime("-6 months"));
		$_condition[] = "last_experience_time < '{$date}'";
		$_condition[] = "last_experience_time != 0";
		$_condition[] = "experience >=1001";
		$_field = array(
			'member_id',
			'rank_id AS current_rank_id',
			'last_experience_time',
			'experience',
		);

		$sql = "SELECT ". implode(',', $_field) ." FROM `shop_member` WHERE ".implode(' AND ', $_condition);

		$infos = $this->_db->fetchAll($sql);
		if (empty($infos)) {
			$this->_error = '没有相关操作数据';
			return false;
		}

		

		return $infos;
	}

	/**
	 * 降级
	 *
	 * @return   array
	 **/
	public function dealDemoteRank()
	{
		$infos = $this->getMemberDemote();

		if (empty($infos)) {
			return array();
		}
		foreach ($infos as $info) {
			$glass = $this->getGlass($info['last_experience_time']);

			$rank_info = $this->getDemoteRank($info['experience'], $glass);

			if ($rank_info['rank_id'] != $info['current_rank_id']) {
				$this->updateMemberInfoByMemberId($info['member_id'], array('rank_id' => $rank_info['rank_id']));

				$log_params = array(
					'member_id'    => $info['member_id'],
					'last_rank_id' => $info['current_rank_id'],
					'rank_id'      => $rank_info['rank_id'],
					'remark'       => '会员等级降级',
				);

				$this->addMemberRankLog($log_params);
			}
		}
		return $infos;
	}

	/**
	 * 计算需要降几级
	 *
	 * @param    string
	 *
	 * @return   int
	 **/
	public function getGlass($experience_time)
	{
		$glass = -6;
		$glass_level = 0;
		$date = date('Y-m-d H:i:s', strtotime("{$glass} months"));
		while ($experience_time < $date) {
			$glass_level ++;
			$glass -= 6;
			$date = date('Y-m-d H:i:s', strtotime("{$glass} months"));
		}

		return $glass_level;
	}

	/**
	 * 获取所要降级的rank_id
	 *
	 * @param    string
	 * @param    int
	 *
	 * @return   int
	 **/
	public function getDemoteRank($experience, $glass)
	{
		$current_level = 0;
		foreach ($this->_glass as $key => $value) {
			if ($experience < $value) {
				$current_level = $key - 1;
				break;
			}
		}

		$glass_level = $current_level - $glass > 2 ? $current_level - $glass : 2;

		$rank_info = $this->getRankInfoByExperience($this->_glass[$glass_level]);

		return $rank_info;
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


$member = new Demote_member_rank();
if (false === $member->dealDemoteRank()) {
	exit($member->getError());
}


exit('操作成功');