<?php

class Admin_DataController extends Zend_Controller_Action 
{
	/**
     * 数据维护 API
     * 
     * @var Admin_Models_API_Data
     */
    private $_data = null;
    
    /**
     * 备份数据文件大小
     * 
     * @var array
     */
    private $_size = array('0' => '无限制', '1048576' => '1M', '2097152' => '2M', '4194304' => '4M', '10485760' => '10M');
	
	/**
     * 数据表未选择
     */
	const NO_TABLE_SELECT = '请选择数据表!';
	
	/**
     * 修复数据表成功
     */
	const REPAIR_TABLE_SUCESS = '修复数据表成功!';
	
	/**
     * 优化数据表成功
     */
	const OPTIMIZE_TABLE_SUCESS = '优化数据表成功!';
    
    /**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _data = new Admin_Models_API_Data();
	}
	
	/**
     * 数据修复及优化
     *
     * @return void
     */
    public function fixAction()
    {
    	if ($this -> _request -> isPost()) {
    		$type = $this -> _request -> getPost('type');
    		
    		if (!$this -> _request -> getPost('tables')) {
    			Custom_Model_Message::showMessage(self::NO_TABLE_SELECT, "Gurl()", 1250);
    		} else {
    			if ($type == 'repair') {
    			    $this -> _data -> repairTable($this -> _request -> getPost('tables'));
    			    Custom_Model_Message::showMessage(self::REPAIR_TABLE_SUCESS, "Gurl()", 1250);
    		    } elseif ($type == 'optimize') {
    			    $this -> _data -> optimizeTable($this -> _request -> getPost('tables'));
    			    Custom_Model_Message::showMessage(self::OPTIMIZE_TABLE_SUCESS, "Gurl()", 1250);
    		    }
    		}
    	}
    	
        $tables = $this -> _data -> showTableStatus();
        $this -> view -> action = 'fix';
        $this -> view -> title = "数据维护";
        $this -> view -> tables = $tables;
    }
}