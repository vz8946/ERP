<?php

class Admin_Models_API_Performance
{
	/**
     * DB对象
     */
	private $_db = null;
	
    /**
     * 错误信息
     */
	private $error;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Performance();
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getTelOrder($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{

        if ($where['fromdate'] && $where['todate']) {
            $fromDate = strtotime($where['fromdate']);
            $toDate = strtotime($where['todate']) + 86400;
            if ($fromDate <= $toDate) {
                $condition[] = "(b.add_time between {$fromDate} and {$toDate})";
            }
        }
        if (!is_null($where['status']) && $where['status'] !== '') {
            $condition[] = "b.status={$where['status']}";  
        }
        if (!is_null($where['status_logistic']) && $where['status_logistic'] !== '') {
            $condition[] = "status_logistic={$where['status_logistic']}";  
        }
        if (!is_null($where['status_pay']) && $where['status_pay'] !== '') {
            $condition[] = "status_pay={$where['status_pay']}";  
        }
        if ($where['operator_id']) {
            $condition[] = "operator_id='{$where['operator_id']}'";  
        }
        if (!is_null($where['status_return']) && $where['status_return'] !== '') {
            $condition[] = "status_return={$where['status_return']}";  
        }
        if (is_array($condition) && count($condition)) {
            $wheresql = 'AND ' . implode(' AND ', $condition);
        }
		return $this -> _db -> getTelOrder($wheresql, $fields, $orderBy, $page, $pageSize);
	}


	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function getExportTelOrder($where = null)
	{
        if ($where['fromdate'] && $where['todate']) {
            $fromDate = strtotime($where['fromdate']);
            $toDate = strtotime($where['todate']) + 86400;
            if ($fromDate <= $toDate) {
                $condition[] = "(b.add_time between {$fromDate} and {$toDate})";
            }
        }
        if (!is_null($where['status']) && $where['status'] !== '') {
            $condition[] = "b.status={$where['status']}";  
        }
        if (!is_null($where['status_logistic']) && $where['status_logistic'] !== '') {
            $condition[] = "status_logistic={$where['status_logistic']}";  
        }
        if (!is_null($where['status_pay']) && $where['status_pay'] !== '') {
            $condition[] = "status_pay={$where['status_pay']}";  
        }
        if ($where['operator_id']) {
            $condition[] = "operator_id='{$where['operator_id']}'";  
        }
        if (!is_null($where['status_return']) && $where['status_return'] !== '') {
            $condition[] = "status_return={$where['status_return']}";  
        }
        if (is_array($condition) && count($condition)) {
            $wheresql = 'AND ' . implode(' AND ', $condition);
        }
		return $this -> _db -> getExportTelOrder($wheresql);
	}



}