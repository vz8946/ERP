<?php

class Admin_Models_API_Base extends Custom_Model_Dbadv
{
	protected $_db = null;
	/**
     * 编辑专题列表可以编辑项目
     *
     * @param    int $id
     * @param    string $field
     * @param    string $val
     * @return   int
     */
	
	public function __construct()
	{
	    parent::__construct();
	}
	
	public function ajaxupdate($id, $field, $val,$pkid)
	{
		if ($id > 0) {
		    return $this->_db->ajaxupdate($id, $field, $val,$pkid);
		}
	}

	/**
     * 取某ID的专题信息
     *
     * @param    int    $articleID
     * @return   array
     */
    public function getByID($id,$pkid){
        return $this->_db->getByID($id,$pkid);
    }

    /**
     * 删除
     *
     * @param    int       $articleID
     * @param    string    $error
     * @return   bool
     */
    public function del($id,$pkid){
        if($this->_db->del($id,$pkid)){
            return true;
        }else{
            $error = 'delFail';
            return false;
        }
    }
}