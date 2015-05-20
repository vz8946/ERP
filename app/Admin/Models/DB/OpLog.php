<?php

class Admin_Models_DB_OpLog extends Admin_Models_DB_Base
{
	protected $_db = null;

	protected $_table = 'shop_op_log';

	public function __construct()
	{
				$this->_db = Zend_Registry::get('db');
        $this->_pageSize = Zend_Registry::get('config')->view->page_size;
    }


	/**
     * 添加专题
     *
     * @param    array $data
     * @return   int
     */
     public function add($data){
        $this->_db->insert($this->_table, $data);
				return $this->_db->lastInsertId;
    }
	/**
     * 取专题数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getCount($where=null){

		$sql = 'SELECT count(*) as count FROM '.$this->_table . $where;
		return $this->_db->fetchOne($sql);
    }

    /**
     * 取专题列表
     *
     * @param    string $where
     * @param    string $fields
     * @param    string $orderBy
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = ' LIMIT '.$pageSize.' OFFSET '.$offset;
		}


		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY log_id DESC";
		}
		//die('SELECT op.*,sd.admin_name  FROM '.$this->_table.' as op  inner join shop_admin as sd on sd.admin_id=op.admin_id  '. $where.'  '.$orderBy.' '.$limit);
		$sql = 'SELECT op.*,sd.admin_name  FROM '.$this->_table.' as op  inner join shop_admin as sd on sd.admin_id=op.admin_id  '. $where.'  '.$orderBy.' '.$limit;
		return $this->_db->fetchAll($sql);
	}



}