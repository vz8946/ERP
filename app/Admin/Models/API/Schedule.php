<?php

class Admin_Models_API_Schedule
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
     * 缓存文件名
     */
	private $filename = '../tmp/sytemCache/admin/system.schedule';
	
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Schedule();
	}
	
	/**
     * 获取计划任务
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @param    int       $page
     * @param    int       $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if ( is_array($where) ) {
		    $where['name'] && $wheresql .= "name like '%{$where['name']}%' and ";
		    $where['type'] && $wheresql .= "type = '{$where['type']}' and ";
		    if ( ($where['status'] !== null) && ($where['status'] !== '') ) {
		        $wheresql .= "status = '{$where['status']}' and ";
		    }
		    $wheresql .= '1';
		}
		else    $wheresql = $where;
		
		return $this -> _db -> fetch($wheresql, $fields, $orderBy, $page, $pageSize);
	}
	
	/**
     * 添加或修改计划
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function edit($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        if ($data['name'] == '') {
			$this -> error = 'no_name';
			return false;
		}
		if ($data['action_url'] == '') {
			$this -> error = 'no_action_url';
			return false;
		}
		if ( ($data['type'] == '2') && ((int)$data['interval'] <= 0) ) {
			$this -> error = 'no_interval';
			return false;
		}
		
		if ( $id ) {
		    $temp = $this -> get("id <> {$id} and name = '{$data['name']}'");
		}
		else {
		    $temp = $this -> get("name = '{$data['name']}'");
		}
		if ( $temp['total'] > 0 ) {
		    $this -> error = 'same_name';
			return false;
		}
		
		if ($id === null) {
		    $result = $this -> _db -> insert($data);
		    
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		} else {
			$result = $this -> _db -> update($data, (int)$id);
		}
		
		$this -> createScheduleFile();
		
		return $result;
	}
	
	/**
     * 更新任务运行状态
     *
     * @param    int      $id
     * @return   string
     */
	public function updateRun($id, $admin_name, $run_count, $log)
	{
	    $log = date('Y-m-d H:i:s', time()).' -> '.$admin_name.chr(13).chr(10).$log;
	    $set = array( 'admin_name' => $admin_name,
	                  'run_count' => $run_count,
	                  'last_time' => time(),
	                  'log' => $log,
	                );
	    $this -> _db -> updateRun($set, (int)$id);
	    $this -> createScheduleFile();
	}
	
	/**
     * 按照ID查询
     *
     * @param    string    $id
     * @return   array
     */
	public function getScheduleById($id = null)
	{
		return $this -> _db -> getScheduleById($id);
	}
	
	/**
     * ajax更新团购
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
		
		if ( (int)$id > 0 ) {
		    if ($this -> _db -> ajaxUpdate((int)$id, $field, $val) <= 0) {
		        exit('failure');
		    }
		    $this -> createScheduleFile();
		}
	}
	
	/**
     * 获取团购状态信息
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxStatus($url, $id, $status)
	{
		switch($status){
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
    }
	
    /**
     * 删除计划
     *
     * @param    int    $id
     * @return   void
     */
	public function delete($id)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> delete((int)$id);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		    
		    $this -> createScheduleFile();
		    
		    return $result;
		}
	}
	
	/**
     * 生成计划任务缓存文件
     *
     * @return   void
     */
	public function createScheduleFile()
	{
	    $datas = $this -> get('type = 2 and status = 0', 'id,action_url,`interval`,last_time');
	    $content = serialize($datas['list']);
	    
	    file_put_contents($this -> filename, $content);
	}
	
	/**
     * 获得可运行的计划任务(通过缓存)
     *
     * @return   void
     */
	public function getValidSchedule()
	{
	    if ( !file_exists($this -> filename) ) {
	        $this -> createScheduleFile();
	    }
	    
	    $datas = unserialize(file_get_contents($this -> filename));
	    if ( $datas && count($datas) > 0 ) {
	        foreach ( $datas as $data ) {
	            if ( ($data['last_time'] == 0) || (($data['last_time'] + $data['interval'] * 60) <= time()) ) {
	                $result[] = $data;
	            }
	        }
	    }
        
	    return $result;
	}
    
	/**
     * 错误集合
     *
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error' => '操作失败!',
			         'no_name' => '请填写名称!',
			         'no_action_url' => '请填写action!',
			         'no_interval' => '请填写运行时间间隔!',
			         'same_name' => '名称不能重复!',
			        );
		if(array_key_exists($this -> error, $errorMsg)) {
			return $errorMsg[$this -> error];
		}
		else {
			return $this -> error;
		}
	}
	
}