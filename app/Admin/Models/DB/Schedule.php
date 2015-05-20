<?php

class Admin_Models_DB_Schedule
{
	/**
     * Zend_Db
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * page size
     * @var    int
     */
	private $_pageSize = 20;
	
	/**
     * table name
     * @var    string
     */
    private $_table = 'shop_system_schedule';
    
	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
     * 获取团购数据集
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function fetch($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		
		if ($where != null) {
			$whereSql = "WHERE $where";
		}
		
		if ($orderBy != null){
			$orderBy = "ORDER BY $orderBy";
		}else{
			$orderBy = "ORDER BY id";
		}
		
		return array('list'=>$this -> _db -> fetchAll("SELECT $fields FROM {$this->_table} $whereSql $orderBy $limit"),
		             'total'=> $this -> _db -> fetchOne("SELECT count(*) as count FROM {$this->_table} $whereSql"));
	}
	
	/**
     * 按照ID查询
     *
     * @param    string    $id
     * @return   array
     */
	public function getScheduleById($id = null)
	{
		if ($id > 0) {
			 return $this->_db->fetchRow("select * from `$this->_table` where id=".$id);
		}
	}
	
	/**
     * 添加数据
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert(array $data)
	{
		$row = array ('name' => $data['name'],
                      'type' => $data['type'],
                      'action_url' => $data['action_url'],
                      'interval' => $data['interval'],
                      'memo' => $data['memo'],
                      'add_time' => time(),
                      'status' => $data['status'],
                     );
        
        $this -> _db -> insert($this -> _table, $row);
        
		return $this -> _db -> lastInsertId();
	}
	
	/**
     * 更新数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function update($data, $id)
	{
		if ( !$id )    return false;
		
		$set = array ('name' => $data['name'],
                      'type' => $data['type'],
                      'action_url' => $data['action_url'],
                      'interval' => $data['interval'],
                      'memo' => $data['memo'],
                      'status' => $data['status'],
                     );
        
		$where = $this -> _db -> quoteInto('id = ?', $id);
		$this -> _db -> update($this -> _table, $set, $where);
		return true;
	}
	
	/**
     * 更新任务运行状态
     *
     * @param    int      $id
     * @return   void
     */
	public function updateRun($data, $id)
	{
		if ( !$id )    return false;
		
		$where = $this -> _db -> quoteInto('id = ?', $id);
		$this -> _db -> update($this -> _table, $data, $where);
		return true;
	}
    
	/**
     * ajax更新计划
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this -> _db -> quoteInto('id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}
	
	/**
     * 删除计划
     *
     * @param    int      $id
     * @return   void
     */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> delete($this -> _table, $where);
		}
	}
	
    
}