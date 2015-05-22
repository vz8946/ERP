<?php

class Admin_Models_API_Data
{
	/**
     * 邮件模板管理 DB
     * 
     * @var Admin_Models_DB_Data
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
		$this -> _db = new Admin_Models_DB_Data();
	}
	
	/**
     * 获取所有数据表名
     *
     * @return   array
     */
	public function listTables()
	{
		return $this -> _db -> listTables();
	}
	
	/**
     * 获取所有数据表状态
     *
     * @return   array
     */
	public function showTableStatus()
	{
		$tables = $this -> _db -> showTableStatus();
		foreach ($tables as $key => $value)
		{
			$tables[$key]['Data_length'] = ($tables[$key]['Data_length'] > 0) ? Custom_Model_File::sizeFormat($tables[$key]['Data_length']) : $tables[$key]['Data_length'];
			$tables[$key]['Index_length'] = ($tables[$key]['Index_length'] > 0) ? Custom_Model_File::sizeFormat($tables[$key]['Index_length']) : $tables[$key]['Index_length'];
			$tables[$key]['Data_free'] = ($tables[$key]['Data_free'] > 0) ? Custom_Model_File::sizeFormat($tables[$key]['Data_free']) : $tables[$key]['Data_free'];
		}
		return $tables;
	}
	
	/**
     * 修复表
     * 
     * @param    array    $tables
     * @return   void
     */
	public function repairTable($tables)
	{
		if (is_array($tables)) {
			foreach ($tables as $table)
			{
				$this -> _db -> repairTable($table);
			}
		}
	}
	
	/**
     * 优化表
     * 
     * @param    array    $tables
     * @return   void
     */
	public function optimizeTable($tables)
	{
		if (is_array($tables)) {
			foreach ($tables as $table)
			{
				$this -> _db -> optimizeTable($table);
			}
		}
	}
}