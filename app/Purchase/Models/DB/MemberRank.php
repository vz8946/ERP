<?php

class Purchase_Models_DB_MemberRank
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 会员等级表名
     * 
     * @var    string
     */
	private $_table = 'shop_member_rank';
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	
	/**
     * 取得会员等级信息
     *
     * @param    array    $where
     * @return   array
     */
	public function getRank($where = null)
	{
		if ($where != null && is_array($where)) {
			$whereSql = ' WHERE 1=1';
			foreach ($where as $key => $value)
			{
				$whereSql .= " AND $key='$value'";
			}
		}
		
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $whereSql;
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得最低非特殊会员等级
     *
     * @param    string $where
     * @return   array
     */
	public function getMinRank()
	{
		$sql = 'SELECT * FROM `' . $this -> _table . '` WHERE is_special=0 ORDER BY min_point ASC LIMIT 1';
		return $this -> _db -> fetchRow($sql);
	}
}