<?php
class Shop_Models_DB_News
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
	private $_pageSize = null;

	/**
     * table name
     * @var    string
     */
   private $_table = array(
                           'adv'=>'shop_advertising',
                           'news'=>'shop_news',
                           'link'=>'shop_link'
    );

	/**
     * Creates a db instance.
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
		$this -> _pageSize = Zend_Registry::get('config') -> view -> page_size;
	}
	 /**
     * 根据展示位置获取友情链接
     * $position  展示位置值
     */
	public function getFriendLinkByPosition($position){
		$sql = "select * from ".$this->_table['link']." where display=1 and MATCH(position) AGAINST('".$position."' IN BOOLEAN MODE) order by grade asc";
		return $this -> _db -> fetchAll($sql);
	}
	/**
	 * 根据展示位置和过滤条件获得资讯
	 * $position 展示位置
	 * $num 结果数量
	 * $filter_str 过滤参数 例如查询有图片的News $filter_str=" and ztpic is not null and ztpic!=''"
	 */
	public function getNewsByPositionAndFilter($position,$num,$filter_str){
		$sql = "select ztpic,id,whois,asName,title,seoDescription,ncId,ncName,ztpic from ".$this->_table['news']." where MATCH(position) AGAINST('".$position."' IN BOOLEAN MODE) ".$filter_str." order by updateDate desc limit 0,$num";
		return $this -> _db -> fetchAll($sql);
	}
	/**
     * 根据展示位置获取广告
     * $position  展示位置值
     */
	public function getAdvyPosition($position,$num){
		$sql = "select * from ".$this->_table['adv']." where isDisplay=1 and MATCH(position) AGAINST('".$position."' IN BOOLEAN MODE) order by grade asc limit 0,$num";
		return $this -> _db -> fetchAll($sql);
	}

}