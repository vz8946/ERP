<?php
class Admin_Models_DB_Operation
{
	private $_db = null;
	private $_table = array(
           'sendmsg_log'          =>'shop_sendmsg_log',
           'reason'               =>'shop_reason',
           'act'                  =>'shop_activity'
	);
	public function __construct()
	{
		$this->_db = Zend_Registry::get('db');
        $this->_pageSize = Zend_Registry::get('config')->view->page_size;
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
    }
	/**
     * 客服发送短信记录
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addCustomerMsg($data)
	{
		$row = array (
                      'admin_name' => $this -> _auth['admin_name'],
                      'add_time' => time(),
                      'mobile' => $data['mobile'],
                      'msg' => $data['msg']
                     );
        
        $this -> _db -> insert($this -> _table['sendmsg_log'], $row);
		return $this -> _db -> lastInsertId();
	}
    /**
     * 取得客服发送短信记录的列表
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getCustomerMsg($where, $page)
    {
        if ($where['fromdate'] && $where['todate']) {
            $fromDate = strtotime($where['fromdate']);
            $toDate = strtotime($where['todate']);
            if ($fromDate <= $toDate) {
                $condition[] = "( add_time between {$fromDate} and {$toDate})";
            }
        }
        if ($where['admin_name']) {
            $condition[] = "admin_name='{$where[admin_name]}'";  
        }
		if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' and ', $condition);
        }

        $sql = " FROM ".$this -> _table['sendmsg_log']." WHERE 1=1  {$condition} ";
        $sqlOfList = "SELECT * {$sql} order by id desc LIMIT {$this -> _pageSize} OFFSET " . (($page - 1) * $this -> _pageSize);
        $sqlOfTotal = "SELECT count(*) {$sql}";
        $data['data'] = $this -> _db -> fetchAll($sqlOfList);
        $data['total'] = $this -> _db -> fetchOne($sqlOfTotal);
        return $data;
    }

    /**
     * 取得优惠活动记录
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getActList($where = null, $page)
    {
		if ($where != null) {
			$whereSql = ($whereSql) ? $whereSql : " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			}
		}
        $sql = " FROM ".$this -> _table['act']."  {$whereSql} ";
        $sqlOfList = "SELECT * {$sql} order by act_id desc LIMIT {$this -> _pageSize} OFFSET " . (($page - 1) * $this -> _pageSize);
        $sqlOfTotal = "SELECT count(*) {$sql}";
        $data['data'] = $this -> _db -> fetchAll($sqlOfList);
        $data['total'] = $this -> _db -> fetchOne($sqlOfTotal);
        return $data;
    }
	/**
     * 按照ID查询
     *
     * @param    string    $id
     * @return   array
     */
	public function getActById($id = null)
	{
		if($id>0){
			 return $this->_db->fetchRow("select * from ".$this -> _table['act']." where act_id=".$id);
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
		$_auth = Admin_Models_API_Auth :: getInstance()->getAuth();
		$row = array (
                      'act_name' => $data['act_name'],
                      'act_url' => $data['act_url'],
                      'add_time' =>time(),
					  'add_name' => $_auth['admin_name'],
                      'introduction' => $data['introduction'],
                      'start_time' => $data['start_time'],
                      'end_time' =>$data['end_time'],
		              'status' =>$data['status']
                      );
                if ( $data['act_img'] ) {
                    $row['act_img'] = $data['act_img'];
                }
        
        $this -> _db -> insert($this -> _table['act'], $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
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
		$set = array (
                      'act_name' => $data['act_name'],
                      'act_url' => $data['act_url'],
                      'start_time' => $data['start_time'],
                      'end_time' =>$data['end_time'],
                      'introduction' => $data['introduction'],
		              'status' =>$data['status']
                      );
                if ( $data['act_img'] ) {
                    $set['act_img'] = $data['act_img'];
                }
        $where = $this -> _db -> quoteInto('act_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table['act'], $set, $where);
		    return true;
		}
	}
	/**
     * ajax更新数据
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
	 * @param    string   $type
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val, $type)
	{
		$set = array ($field => $val);
		if ($id > 0) {
                switch($type){
                    case 'act':
                            $where = $this -> _db -> quoteInto('act_id = ?', $id);
                            $this -> _db -> update($this -> _table['act'], $set, $where);
                        break;
                    case 'reason':
                            $fields = array('label', 'sort');
                            if(in_array($field, $fields)){
                                $where = $this -> _db -> quoteInto('reason_id = ?', $id);
                                $this -> _db -> update($this -> _table['reason'], $set, $where);
                            }
                           
                       break;
                  
                }
                 return true;
        }
	}

	/**
     * 更改活动状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   int    lastInsertId
     */
	public function updateStatus($id, $status)
	{
		$set = array ('status' => $status);
		$where = $this -> _db -> quoteInto('act_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> update($this -> _table['act'], $set, $where);
		}
	}
	/**
     * 删除数据
     *
     * @param    int      $id
     * @return   void
     */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('act_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> delete($this -> _table['act'], $where);
		}
	}
	
	/**
	 * 退货原因
	 * 
	 */
	public function getAllReason() {
		$sql = "select * from {$this -> _table['reason']}";
		return $this -> _db -> fetchAll($sql);
	}

}