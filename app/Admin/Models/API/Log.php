<?php
class Admin_Models_API_Log {
	private $_db;
	private $_table = 'shop_action_log';
	private $_config;
	private $_auth;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		
		$this -> _db = Zend_Registry::get('db');
		
	    $this -> _config['offers']['edit'] = array('method' => 'post',
		                                           'key' => 'id'
		                                          );
	    $this -> _config['group-goods']['edit'] = array('method' => 'post',
		                                                'key' => 'group_id'
		                                               );
        $this -> _config['goods']['tag'] = array('method' => 'post',
		                                         'key' => 'id'
		                                        );
	}
	
	public function run($controller, $action, $method, $params)
	{
	    if (!$this -> _config[$controller][$action] )   return false;
	    if ($this -> _config[$controller][$action]['method'] != $method)    return false;
	    
	    if ($this -> _config[$controller][$action]['key']) {
	        $key = $params[$this -> _config[$controller][$action]['key']];
	    }
	    
	    unset($params['module']);
	    unset($params['controller']);
	    unset($params['action']);
	    
	    $row = array('controller' => $controller,
	                 'action' => $action,
	                 'method' => $method,
	                 'key' => $key,
	                 'params' => serialize($params),
	                 'admin_name' => $this -> _auth['admin_name'],
	                 'add_time' => time()
	                );
	    $this -> _db -> insert($this -> _table, $row);
	}
    
}
	
?>