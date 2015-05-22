<?php
class Admin_Models_API_OpLog
{
	protected $_db = null;
	protected $_table = 'shop_op_log';
	public function __construct()
	{
	    $this->_db = new Admin_Models_DB_OpLog();
        $this->_pageSize = Zend_Registry::get('config')->view->page_size;
    }
	/**
     * 添加
     *
     * @param    array $data
     * @return   int
     */
     public function addopt($admin_id,$opt_mod_url){
     	$data = array();
    	$order_sn = 'export';
    	$data['bill_sn'] = 'export';
    	$data['user_id'] = 0;
    	$data['admin_id'] = $admin_id;
    	$data['ip'] = $_SERVER["REMOTE_ADDR"];
    	$data['optdata'] = date("Y-m-d H:i:s");
    	$data['url'] = $opt_mod_url;
    	$data['bill_type'] = 3;
		$this->_db->add($data);
    }

	public function createWhere($where = array()){
		$str = ' where 1=1 ';
		if(!empty($where)){
    		if(!empty($where['bill_type'])){
    			$str .= " and bill_type =  ".$where['bill_type'];
    		}
    		if(!empty($where['ctime'])){
    			$str .= " and optdata > '".$where['ctime']."'";
    		}
    		if(!empty($where['ltime'])){
    			$str .= " and optdata < '".$where['ltime']."'";;
    		}
    	}
    	return $str;
	}
	/**
     * 取数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getCount($where=null){
    	if(!empty($where)){
    		$wherestr = $this->createWhere($where);
    	}
        return $this->_db->getCount($wherestr);
    }
	/**
     * 取列表
     *
     * @param    string $where
     * @param    string $fields
     * @param    string $orderBy
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		if(!empty($where)){
    		$wherestr = $this->createWhere($where);
    	}
		$datas= $this->_db->get($wherestr, $fields, $orderBy, $page, $pageSize);
		foreach ($datas as $num => $data)
        {
	        $datas[$num]['ginfo'] = Zend_Json::encode($datas[$num]);
        }
        return $datas;
	}
}