<?php

class Shop_Models_DB_Topics
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
	private $_table = 'shop_topics';

	/**
     * table name
     * @var    string
     */
	private $_goods_table = 'shop_goods';

	private $_product_table = 'shop_product';

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
     * 专题根据id获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getTopsById($id = null)
	{
        if($id>=1){
          return  $this -> _db -> fetchRow("SELECT * FROM $this->_table where  id= $id");
        }
	}
	/*读出符合要求的专题*/
	public function getZt(){
		 return  $this -> _db -> fetchAll("SELECT id,name,imgUrl FROM $this->_table where isDisplay = 1 order by id desc,grade desc,sort desc ");
	}
	
	
}