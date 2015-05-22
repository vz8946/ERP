<?php

class Purchase_Models_API_MemberRank
{
	/**
     * 会员等级 DB
     * 
     * @var Purchase_Models_DB_MemberRank
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
		$this -> _db = new Purchase_Models_DB_MemberRank();
	}
	
	/**
     * 取得所有会员等级
     *
     * @return   array
     */
	public function getAllRank()
	{
		return $this -> _db -> getRank();
	}
	
	/**
     * 取得指定ID的会员等级列表
     *
     * @param    int    $id
     * @return   array
     */
	public function getRankById($id)
	{
		$rank = $this -> _db -> getRank(array('rank_id' => $id));
		
		if (is_array($rank)) {
			return array_shift($rank);
		}
	}
	
	/**
     * 取得最低非特殊会员等级
     *
     * @return   array
     */
	public function getMinRank()
	{
		return $this -> _db -> getMinRank();
	}
}