<?php
/*
 * Created on 2013-5-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Admin_Models_DB_Decoration  extends Admin_Models_DB_Base{


	protected $_db = null;

	protected $_table = 'shop_decoration';

	private $_table_arr = array(
       'decoration'=>'shop_decoration',
       'goods'=>'shop_goods',
       'cate'=>'shop_data_dictionary'
    );

 	public function __construct()
	{
		$this->_db = Zend_Registry::get('db');
        $this->_pageSize = Zend_Registry::get('config')->view->page_size;
    }

    /**
     * 根据分类id取所有分类
     *
     * @return   array
     */
    public function getAllCate($pid)
	{
		return $this->_db->fetchAll('select * from ' .$this->_table_arr['cate'] .' where type ='.$pid );

	}
	/*获取列表*/
	public function get($where = null, $fields = ' aa.goodsnum,aa.goodsid,aa.id,aa.isdisplay,aa.sort,aa.imgurl,bb.goods_name ,bb.goods_img ', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;
		$pageSize = 50;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = ' LIMIT '.$pageSize.' OFFSET '.$offset;
		}

		if (empty($where)) {
			$whereSql = " WHERE 1=1 ";

        }else{
        	$whereSql = " WHERE type= '".$where['type']."' ";
        }
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY sort DESC";
		}
		$sql = 'SELECT aa.goodsnum,aa.goodsid,aa.id,aa.isdisplay,aa.pid,aa.sort,aa.imgurl,bb.goods_name ,bb.goods_img  FROM `'.$this->_table.'` as aa left join shop_goods as bb on aa.goodsid=bb.goods_id  '.$whereSql.' '.$orderBy.' '.$limit;
		//die($sql);
		return $this->_db->fetchAll($sql);
	}
	/*根据类别,pidcode 查询产品*/
	public function getByType($type,$pidcode=""){
		if(!empty($pidcode)) $pidwhere = " and pid = '".$pidcode."'";
		$sql = "SELECT aa.goodsnum,aa.goodsid,aa.id,aa.isdisplay,aa.pid,aa.sort,aa.imgurl,bb.act_notes ,bb.goods_name ,bb.goods_img,bb.market_price,bb.price,dd.as_name  FROM `".$this->_table."` as aa inner join shop_goods as bb on aa.goodsid=bb.goods_id inner join  shop_product as cc on bb.product_id   = cc.product_id inner join shop_brand as dd on cc.brand_id = dd.brand_id  where isdisplay=0 and bb.onsale=0 and bb.is_del =0 and bb.is_gift=0 and type= '".$type."' $pidwhere order by sort desc" ;
		return $this->_db->fetchAll($sql);

	}

	/*获得总行数*/
	public function getCount(){
		$sql = 'SELECT count(*) as count FROM '.$this->_table;
		return $this->_db->fetchOne($sql);
	}
	/*添加记录*/
	public function add($type,$pidcode){
		 $this->_db->insert($this->_table, array("type"=>$type));
		return $this->_db->lastInsertId();
	}
	/*根据产品编码获取产品信息*/
	public function getGoodsByNum($goodsnum){
		$sql = 'SELECT goods_id,goods_name,goods_sn  FROM  '.$this->_table_arr['goods'] .' where goods_sn = "'.$goodsnum.'"';
		return $this->_db->fetchRow($sql);
	}

	/*保存信息*/
	public function save($pkid,$data){
	 	$where = $this->_db->quoteInto("id = ?", $pkid);
	 	if ($pkid > 0) {
		    return $this->_db->update($this->_table, $data, $where);
		}
	}

 }
?>
