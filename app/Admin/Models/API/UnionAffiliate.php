<?php

class Admin_Models_API_UnionAffiliate
{
	/**
     * 联盟打款 DB
     * 
     * @var Admin_Models_DB_UnionAffiliate
     */
	private $_db = null;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_UnionAffiliate();
	}
	
	/**
     * 取得可打款联盟信息
     *
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getPayList($search,$page = null, $pageSize = null, $unType)
	{
        if ($search != null ) {
			($search['user_id']) ? $where .= "  and user_id ='$search[user_id]'" : "";
			($search['user_name']) ? $where .="  and user_name ='$search[user_name]'": "";
            ($search['union_type']) ? $where .="  and union_type ='$search[union_type]'": "";
		}
		if ($search['affiliate_money_from'] || $search['affiliate_money_to'] ) {
            if($search['affiliate_money_from']){
                    $exwhere .="  and affiliate_money >='$search[affiliate_money_from]'   ";
            }elseif($search['affiliate_money_to']){
                    $exwhere .="  and affiliate_money <='$search[affiliate_money_to]' " ;
            }
		} 
		$content = $this -> _db -> getPayList($where,$page, $pageSize, $unType, $exwhere);
		$total = $this -> _db -> getPayListCount($where, $unType,$exwhere);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 取得可打款联盟订单分成信息
     *
     * @param    int    $id
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getAffiliateByUid($id, $page = null, $pageSize = null)
	{
		$content = $this -> _db -> getAffiliate($id, $page, $pageSize);
		$total = $this -> _db -> getAffiliateCount($id);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 取得CPA可打款联盟订单分成信息
     *
     * @param    int    $id
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getCpaAffiliateByUid($id, $page = null, $pageSize = null)
	{
		$content = $this -> _db -> getCpaAffiliate($id, $page, $pageSize);
		$total = $this -> _db -> getCpaAffiliateCount($id);
		return array('content' => $content, 'total' => $total);
	}



	/**
     * 取得可打款联盟订单分成历史信息
     *
     * @param    int    $id
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getAffiliateLogById($orderId, $page = null, $pageSize = null)
	{
		$content = $this -> _db -> getAffiliateLog($orderId, $page, $pageSize);
		$total = $this -> _db -> getAffiliateLogCount($orderId);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 取得联盟信息
     *
     * @param    int    $id
     * @param    int    $unionType
     * @return   array
     */
	public function getUnionById($id)
	{
		if ($id) {
			$unionObject = new Admin_Models_API_PUnion();
		    return $unionObject -> getPUnionById($id);
		}
	}
	
	/**
     * 设置订单分成状态
     * 
     * @return void
     */
    public function setOrderAffiliate($affiliateId, $value, $msg = null)
    {
        if (!empty($affiliateId) && !is_null($value)) {
        	return $this -> _db -> setOrderAffiliate($affiliateId, $value, $msg);
		}
    }
    
    /**
     * 取得取消分成订单信息
     *
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getNoSeparateOrder($page = null, $pageSize = null)
	{
		$content = $this -> _db -> getNoSeparateOrder($page, $pageSize);
		$total = $this -> _db -> getNoSeparateOrderCount();
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 分成记录
     *
     * @param    array    $data
     * @return void
     */
	public function affiliate($data)
	{
		$adminCertification = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$data['admin_id'] = $adminCertification['admin_id'];
		$data['admin_name'] = $adminCertification['admin_name'];
		$data['add_time'] = time();
		
		if ($data['aff_type'] == '2') {
			$memberApi = new Admin_Models_API_Member();
            $member = $memberApi -> getMemberByUserName($data['account_user_name']);
            
            if ($member) {
            	$result = $this -> _db -> addAffiliate($data);
            	$money = array('member_id' => $member['member_id'],
                             'accountType' => 1,
                             'accountValue' => $data['amount'],
                             'accountTotalValue' => $member['money'],
                             'note' => '联盟打款');
                $memberApi -> editAccount($member['member_id'], 'money', $money);
            } else {
            	return 'noUser';
            }
		} else {
			$result = $this -> _db -> addAffiliate($data);
		}
		
		if (is_numeric($result) && $result > 0) {
		    return 'addAffiliateSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 取得已分成列表
     *
     * @param    array   $where
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getSeparate($where, $page = null, $pageSize = null)
	{
		$content = $this -> _db -> getSeparate($where, $page, $pageSize);
		$total = $this -> _db -> getSeparateCount($where);
		return array('content' => $content, 'total' => $total);
	}
	
	/**
     * 取得分成详细信息
     *
     * @param    int    $id
     * @return   array
     */
	public function getSeparateById($id)
	{
		if ($id) {
		    return @array_shift($this -> _db -> getSeparate(array('affiliate_pay_id' => $id)));
		}
	}
}