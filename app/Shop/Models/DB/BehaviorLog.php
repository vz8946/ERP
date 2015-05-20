<?php
class Shop_Models_DB_BehaviorLog
{
	private $_db = null;	
	private $_login_log_table = 'shop_login_log';
	private $_favorite_log_table = 'shop_favorite_log';
	private $_comment_log_table = 'shop_comment_log';
	private $_coupon_card_log_table = 'shop_coupon_card_log';
	private $_gift_card_log_table = 'shop_gift_card_log';
	
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
	 * 写入日志
	 * @param 日志类型  $type
	 * @param 日志数据  $data
	 */
	public function addLog($type,$data)
	{
		switch ($type)
		{
			case 'login':
				$table = $this->_login_log_table;
				break;
			case 'favorite':
				$table = $this->_favorite_log_table;
				break;
			case 'comment':
				$table = $this->_comment_log_table;
				break;
			case 'coupon':
				$table = $this->_coupon_card_log_table;
				break;
			case 'gift':
				$table = $this->_gift_card_log_table;
				break;
		}		
	   return 	$this->_db->insert($table,$data);
	}
	
	
	
}