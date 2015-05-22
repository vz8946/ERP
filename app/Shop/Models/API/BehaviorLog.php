<?php
/**
 * 用户行为日志
 * @author gaozongbao
 *
 */
class Shop_Models_API_BehaviorLog
{
	protected static $_instance = null;	
	private $_db = null;
	
	public function __construct()
	{
		$this -> _db = new Shop_Models_DB_BehaviorLog(); 
		
	}
	/**
	 * 取得对象实例
	 *
	 * @return Shop_Models_API_Auth
	 */
	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * 登录日志
	 */
	public function loginLog($data)
	{
		return  $this->_db->addLog('login', $data);
	} 
	/**
	 * 收藏日志
	 */
	public  function  favoriteLog($data)
	{
		return  $this->_db->addLog('favorite', $data);
	}
	
	/**
	 * 商品评论日志
	 */
	public  function  productCommentLog($data)
	{
		return  $this->_db->addLog('comment', $data);
	}
	/**
	 * 优惠券日志
	 */
	public  function couponCardLog($data)
	{
		return  $this->_db->addLog('coupon', $data);
	}
	
	/**
	 * 礼品卡日志
	 */
	public  function giftCardLog($data)
	{
		return  $this->_db->addLog('gift', $data);
	}
}