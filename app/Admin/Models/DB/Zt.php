<?php

class Admin_Models_DB_Zt
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
	private $_table = 'shop_zt';

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
     * ���Ʒ��Idȡ��Ʒ��
     *
     * @param    string    $where
     * @param    string    $fields
     * @return   array
     */
	public function getGoodsBrandNum($brand_id = null)
	{
        if($brand_id>=1){
          return  $this -> _db -> fetchOne("SELECT count(*) as count FROM $this->_product_table where  brand_id='$brand_id'");
        }
	}

	/**
     * ��ȡ��ݼ�
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
			$orderBy = "ORDER BY brand_id";
		}

		$sql = "SELECT $fields FROM `$this->_table` $whereSql $orderBy $limit";
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * ������
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function insert(array $data)
	{
		if ( $this -> fetch("brand_name = '{$data['brand_name']}'") ) {
		    return false;
		}

		$row = array ('attr_id' => 0,
                      'brand_name' => $data['brand_name'],
					  'brand_type' => $data['brand_type'],
                      'tel' => $data['tel'],
                      'contact' => $data['contact'],
                      'brand_desc' => $data['brand_desc'],
			          'status' => $data['status'],
                      'add_time' => $data['add_time'],
                      );
		if($data['logo']){
			$row['logo']=$data['logo'];
		}
        $this -> _db -> insert($this -> _table, $row);
		$lastInsertId = $this -> _db -> lastInsertId();
		return $lastInsertId;
	}

	/**
     * �������
     *
     * @param    array    $data
     * @param    int      $id
     * @return   void
     */
	public function update($data, $id)
	{
		if ( $this -> fetch("brand_name = '{$data['brand_name']}' and brand_id <> {$id}") ) {
		    return false;
		}
		$set = array ('brand_name' => $data['brand_name'],
					  'brand_type' => $data['brand_type'],
                      'tel' => $data['tel'],
                      'contact' => $data['contact'],
                      'brand_desc' => $data['brand_desc'],
                      'status' => $data['status'],
                      );

		if($data['logo']){
			$set['logo'] = $data['logo'];
		}
		if ($data['attr_id']) {
		    $set['attr_id'] = $data['attr_id'];
		}
        $where = $this -> _db -> quoteInto('brand_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}

	/**
     * ɾ�����
     *
     * @param    int      $id
     * @return   void
     */
	public function delete($id)
	{
		$where = $this -> _db -> quoteInto('brand_id = ?', $id);
		if ($id > 0) {
		    return $this -> _db -> delete($this -> _table, $where);
		}
	}

	/**
     * ����״̬
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function updateStatus($id, $status)
	{
		$set = array ('status' => $status);
		$where = $this -> _db -> quoteInto('brand_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}

	/**
     * ajax�������
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this -> _db -> quoteInto('brand_id = ?', $id);
		if ($id > 0) {
		    $this -> _db -> update($this -> _table, $set, $where);
		    return true;
		}
	}

}