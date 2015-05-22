<?php
 class Shop_Models_API_News extends Custom_Model_Dbadv
 {
 	/**
     * 对象初始化
     *
     * @return void
     */
    public function __construct()
    {
        $this -> _db = new Shop_Models_DB_News();
		parent::__construct();
    }
	
	public function getArticleListByTagName($name,$num=0){
		$r_tag = $this->getRow('shop_seo_tag',array('name'=>$name));
		$config = unserialize($r_tag['config']);
		$arr_article_id = $config['arr_article_id'];
		$arr_article_id[] = 0;
		
		$tbl = 'shop_seo_article as a|a.article_id,a.abstract,a.cat_id,a.title,a.author,a.sort,a.add_time,a.is_view,a.img_url';
		$links = array();
		$links['shop_seo_cat as c'] = 'c.cat_id=a.cat_id|c.as_name';
		
		$where = array();
		$where['a.article_id|in'] = $arr_article_id;
		$where['a.status'] = 'AUDITED';
		
		return $this->getAllWithLink($tbl,$links,$where,$num,'sort desc');
		
	}
	
	public function getCatListByTagName($name,$num=0){
		$r_tag = $this->getRow('shop_seo_tag',array('name'=>$name));
		$config = unserialize($r_tag['config']);
		$arr_cat_id = $config['arr_cat_id'];
		$list =  $this->getAll('shop_seo_cat',array('cat_id|in'=>$arr_cat_id),'*',$num);
		$arr_cat_id = Custom_Model_Tools::getListFiled('cat_id', $list);
		$list_tag = $this->getAll('shop_seo_tag',array('cat_id|in'=>$arr_cat_id));
		foreach ($list as $k => $v) {
			$list[$k]['tags'] = $this->getNormalTagsByCatId($list_tag,$v['cat_id']);
			$list[$k]['latest_article'] = $this->getLatetArticleByCatId($v['cat_id']);
		}
		
		return $list;
	}
	
	private function getNormalTagsByCatId($list_tag,$cat_id){
		$tags = array();
		foreach ($list_tag as $k => $v) {
			if($v['is_hot'] == 'Y') continue;
			if($v['cat_id'] == $cat_id){
				$tags[] = $v;
			}
		}
		return $tags;
	}
	
	private function getHotTagByCatId($list_tag,$cat_id){
		foreach ($list_tag as $k => $v) {
			if($v['cat_id'] == $cat_id && $v['is_hot'] == 'Y'){
				$v['articles'] = $this->getArticleListByTagName($v['name']);
				return $v;
				break;				
			}
		}
	}
	
	private function getLatetArticleByCatId($cat_id,$num=5){
		
		$tbl = 'shop_seo_article as a|a.article_id,a.abstract,a.cat_id,a.title,a.author,a.sort,a.add_time,a.is_view,a.img_url';
		$links = array();
		$links['shop_seo_cat as c'] = 'c.cat_id=a.cat_id|c.as_name';
		
		$where = array();
		$where['a.cat_id'] = $cat_id;
		$where['a.status'] = 'AUDITED';
		
		$list = $this->getAllWithLink($tbl,$links,$where,$num,'a.article_id desc',$num);
		
		return $list; 
		
	}
	
	public function getByName($name){
		$r_tag = $this->getRow('shop_seo_tag',array('name'=>$name));
		$config = unserialize($r_tag['config']);
		$arr_article_id = $config['arr_article_id'];
		
		$tbl = 'shop_seo_article as a|a.article_id,a.cat_id,a.title,a.author,a.sort,a.add_time,a.is_view,a.img_url';
		$links = array();
		$links['shop_seo_cat as c'] = 'c.cat_id=a.cat_id|c.as_name';
		
		$where = array();
		$where['a.status'] = 'AUDITED';
		$where['a.article_id|in'] = $arr_article_id; 
		
		$r_tag['articles'] = $this->getAllWithLink($tbl,$links,$where,0,'sort desc');
		
		return $r_tag;
	}
	
	public function getPrmtArticlesByCatId($cat_id){
		$r_cat = $this->getRow('shop_seo_cat',array('cat_id'=>$cat_id));
		$aritlce_ids = trim($r_cat['prmt_article_ids'],',');
		
		$tbl = 'shop_seo_article as a|a.article_id,a.cat_id,a.title,a.author,a.sort,a.add_time,a.is_view,a.img_url';
		$links = array();
		$links['shop_seo_cat as c'] = 'c.cat_id=a.cat_id|c.as_name';
		
		$where = array();
		$where['a.status'] = 'AUDITED';
		$where['a.article_id|in'] = $aritlce_ids; 
		
		$list = $this->getAllWithLink($tbl,$links,$where,0,'sort desc');
		
		return $list; 
	}
	
	public function getPrmtArticlesByTagId($tag_id,$num=0,$orderby='sort desc'){
		$r_tag = $this->getRow('shop_seo_tag',array('tag_id'=>$tag_id));
		$aritlce_ids = trim($r_tag['prmt_article_ids'],',');
		
		$tbl = 'shop_seo_article as a|a.article_id,a.cat_id,a.title,a.author,a.sort,a.add_time,a.is_view,a.img_url';
		$links = array();
		$links['shop_seo_cat as c'] = 'c.cat_id=a.cat_id|c.as_name';
		
		$where = array();
		$where['a.status'] = 'AUDITED';
		$where['a.article_id|in'] = $aritlce_ids; 	
			
		$list = $this->getAllWithLink($tbl,$links,$where,$num,$orderby);		
		return $list; 
	}
	
	public function getGoodsByCatId($cat_id){
		$r_cat = $this->getRow('shop_seo_cat',array('cat_id'=>$cat_id));
		$goods_ids = trim($r_cat['goods_ids'],',');
		$list = $this->getAll('shop_goods',array('goods_id|in'=>$goods_ids),'goods_id,goods_name,price,goods_img');
		return $list; 
	}

	public function getGoodsByTagId($tag_id,$num=0,$orderby=''){
		$r_tag = $this->getRow('shop_seo_tag',array('tag_id'=>$tag_id));
		$goods_ids = trim($r_tag['goods_ids'],',');
		
		$list = $this->getAll('shop_goods',array('goods_id|in'=>$goods_ids),'goods_id,goods_name,price,goods_img',$num,$orderby);
		return $list; 
	}
	
	public function getArticlesByNews($num=10)
	{		
		$list = $this->getAll('shop_seo_article',array(),'article_id,title,cat_id',$num,"add_time DESC");
		return $list;
	}
	
	public function getArticlesByTagIdWithPage(&$page,$tag_id){
		$r_tag = $this->getRow('shop_seo_tag',array('tag_id'=>$tag_id));
		$config = unserialize($r_tag['config']);
		$arr_article_id = $config['arr_article_id'];
		$arr_article_id[] = 0;

		$tbl = 'shop_seo_article as a|a.article_id,a.title,a.abstract,a.add_time';
        $links = array();
        $links['shop_seo_cat as c'] = 'c.cat_id=a.cat_id|c.as_name';

		$where = array();
		$where['a.status'] = 'AUDITED';
		$where['a.article_id|in'] = $arr_article_id; 

		$list = $this->getListByPage($page, $tbl,$links,$where,'a.sort desc');
		
		return $list;		
	}
	
	public function getArticlesByCatIdAndRand($cat_id,$num=8){
		
		$sql = "
			SELECT t1.article_id,t1.title,t1.add_time
			FROM `shop_seo_article` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(article_id) FROM `shop_seo_article` where cat_id=$cat_id)-(SELECT MIN(article_id) FROM `shop_seo_article` where cat_id=$cat_id))+(SELECT MIN(article_id) FROM `shop_seo_article` where cat_id=$cat_id)) AS article_id) AS t2 
			WHERE t1.article_id >= t2.article_id and t1.cat_id=$cat_id 
			ORDER BY t1.article_id LIMIT $num;
		";
		
		$list = $this->fetchBySql($sql);

		return $list; 
	}
	
	
	public function getPrevArticle($id,$cat_id){
		$tbl = 'shop_seo_article as a|a.article_id,a.title,a.abstract,a.add_time';
        $links = array();
        $links['shop_seo_cat as c'] = 'c.cat_id=a.cat_id|c.as_name';
		
		$where = array();
		$where['a.status'] = 'AUDITED';
		$where['a.article_id|gt'] = $id; 
		$where['a.cat_id'] = $cat_id; 

		$list_a = $this->getAllWithLink($tbl,$links,$where,1,'a.article_id asc');
		return $list_a[0];		
	}
	public function getNextArticle($id,$cat_id){
		$tbl = 'shop_seo_article as a|a.article_id,a.title,a.abstract,a.add_time';
        $links = array();
        $links['shop_seo_cat as c'] = 'c.cat_id=a.cat_id|c.as_name';
		
		$where = array();
		$where['a.status'] = 'AUDITED';
		$where['a.article_id|lt'] = $id; 
		$where['a.cat_id'] = $cat_id; 
		
		$list_a = $this->getAllWithLink($tbl,$links,$where,1,'a.article_id desc');
		return $list_a[0];		
	}

	
    /**
     * 根据展示位置获取友情链接
     * $position  展示位置值
     */
	public function getFriendLinkByPosition($position){
		return $this->_db->getFriendLinkByPosition($position);
	}
	/**
	 * 根据展示位置和过滤条件获得资讯
	 * $position 展示位置
	 * $num 结果数量
	 * $filter_str 过滤参数 例如查询有图片的News $filter_str=" and ztpic is not null and ztpic!=''"
	 */
	public function getNewsByPositionAndFilter($position,$num,$filter_str=''){
		return $this->_db->getNewsByPositionAndFilter($position,$num,$filter_str);
	}
	 /**
     * 根据展示位置获取广告
     * $position  展示位置值
     */
	public function getAdvyPosition($position,$num){
		return $this->_db->getAdvyPosition($position,$num);
	}


 }