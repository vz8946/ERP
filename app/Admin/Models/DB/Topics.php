<?php

class Admin_Models_DB_Topics extends Admin_Models_DB_Base
{
	protected $_db = null;

	protected $_table = 'shop_topics';

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
		return $this->_db->lastInsertId();
    }
	/**
     * 取专题数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getCount($where=null){
		if ($where != null) {
			$whereSql = "  WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {

				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'name') {
                            $whereSql .= " AND $key like '%$value%'";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		$sql = 'SELECT count(*) as count FROM '.$this->_table.$whereSql;
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

		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                         if ($key == 'name') {
                            $whereSql .= " AND $key like '%$value%'";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY id DESC";
		}
		$sql = 'SELECT '.$fields.' FROM '.$this->_table.$whereSql.' '.$orderBy.' '.$limit;
		return $this->_db->fetchAll($sql);
	}

	/**
     * 编辑专题
     *
     * @param    string $where
     * @return   int
     */
    public function edit($data){
        $articleID = $data['id'];
        unset($data['id']);
        $where = $this->_db->quoteInto('id = ?', $articleID);
		if ($articleID > 0) {
		    return $this->_db->update($this->_table, $data, $where);
		}
    }
	/**
     * 编辑文章列表可以编辑项目
     *
     * @param    int $id
     * @param    string $field
     * @param    string $val
     * @return   int
     */
	public function ajaxUpdate($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this->_db->quoteInto('id = ?', $id);
		if ($id > 0) {
		    return $this->_db->update($this->_table, $set, $where);
		}
	}
}