<?php
class NewsController extends Zend_Controller_Action
{
	/**
     * @var Custom_Model_Dbadv
     */	
	private $db;

	/**
     * @var Shop_Models_API_News
     */	
     private $api;

	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);		
		$this->db = new Custom_Model_Dbadv();
		$this->api = new Shop_Models_API_News();		
		$this->view->list_cat_nav = $this->api->getCatListByTagName('cat_nav');
		
		$this ->_auth = Shop_Models_API_Auth :: getInstance();
		$user = $this ->_auth -> getAuth();
		$this->user = $user;
		$this->view-> user = $user;
		
		$this->view->seo_title = "种子知识,常识,种子资讯,行业动态-垦丰商城资讯中心";
		$this->view->seo_keywords = "种子知识,种子资讯";
		$this->view->seo_description = "垦丰商城,品质保证";
		
	}
	
	/**
     * 资讯中心
     *
     * @return void
     */
	public function indexAction() {
		$this->view->list_index_tag1 = $this->api->getArticleListByTagName('index_tag1',4);
		$this->view->index_tag2 = $this->api->getByName('index_tag2');
		$this->view->index_tag3 = $this->api->getByName('index_tag3');
		$this->view->list_index_cat = $this->api->getCatListByTagName('index_cat');
		$this->view->conf_cat_icon = array('tonic'=>'t_health.jpg','ecomm'=>'t_ecomm.jpg',
			'women'=>'t_lady.jpg','man'=>'t_men.jpg','old'=>'t_older.jpg','thiner'=>'t_subhealth.jpg');
		
	}

	public function chanelAction(){

		$name = $this->_request->getParam('name');
		if(empty($name)) header('Location:/news');
		
		$this->view->tag = $tag = $this->db->getRow('shop_seo_tag',array('name'=>$name));
		$id = $tag['tag_id'];		
		
		$page = array();
		$page['pn'] = $this->_request->getParam('page',1);
		$page['ps'] = 10;
		$list = $this->api->getArticlesByTagIdWithPage($page,$id);
		
		foreach ($list as $k => $v) {
			$list[$k]['add_time'] = date('Y-m-d H:i',$v['add_time']);
		}
		
		$this->view->pagenav = $page['pagenav'];
		
		$this->view->list = $list;
		//取得分类下推荐文章
		$this->view->list_prmt_article = $t = $this->api->getPrmtArticlesByTagId($id);
		
		//取得分类下推荐商品
		$this->view->list_goods = $t = $this->api->getGoodsByTagId($id);
		
		$this->view->seo_title = !empty($tag['meta_title']) ? $tag['meta_title'] : $this->view->seo_title;
		$this->view->seo_keywords = !empty($tag['meta_keywords']) ? $tag['meta_keywords'] : $this->view->seo_keywords;
		$this->view->seo_description = !empty($tag['meta_description']) ? $tag['meta_description'] : $this->view->seo_description;
		
	}

	public function catAction(){
			
		$as_name = $this->_request->getParam('as_name');

		if(empty($as_name)) header('Location:/news');
		$this->view->cat = $cat = $this->db->getRow('shop_seo_cat',array('as_name'=>$as_name));
		if(empty($cat['cat_id'])) header('Location:/news');
		
		$id = $cat['cat_id'];
				
		$page = array();
		$page['pn'] = $this->_request->getParam('page',1);
		$page['ps'] = 10;
		
		$tbl = 'shop_seo_article as a|a.article_id,a.cat_id,a.title,a.author,a.sort,a.add_time,a.is_view,a.img_url,a.abstract';
		$links = array();
		$links['shop_seo_cat as c'] = 'c.cat_id=a.cat_id|c.as_name';
		
		$list = $this->db->getListByPage($page, $tbl,$links,array('a.status'=>'AUDITED','a.cat_id'=>$id),'a.article_id desc');
		
		foreach ($list as $k => $v) {
			$list[$k]['add_time'] = date('Y-m-d H:i',$v['add_time']);
		}
		
		$this->view->pagenav = $page['pagenav'];
		
		$this->view->list = $list;
		
		//取得分类下推荐文章
		$this->view->list_prmt_article = $t = $this->api->getPrmtArticlesByCatId($id);
		
		//取得分类下推荐商品
		$this->view->list_goods = $t = $this->api->getGoodsByCatId($id);
		
		$this->view->nav_c = $id;
		
		$this->view->seo_title = !empty($cat['meta_title']) ? $cat['meta_title'] : $this->view->seo_title;
		$this->view->seo_keywords = !empty($cat['meta_keywords']) ? $cat['meta_keywords'] : $this->view->seo_keywords;
		$this->view->seo_description = !empty($cat['meta_description']) ? $cat['meta_description'] : $this->view->seo_description;
		
	}

	public function detailAction(){
		
		$id = $this->_request->getParam('id');
		$article = $this->db->getRow('shop_seo_article',array('article_id'=>$id));
		if(empty($article['article_id'])) header('Location:/news');
		
		$article['add_time'] = date('Y-m-d H:i',$article['add_time']);
		$article['content'] = stripslashes($article['content']);
		$cat = $this->db->getRow('shop_seo_cat',array('cat_id'=>$article['cat_id']));
		if(empty($cat['cat_id'])) header('Location:/news');
		
		$this->view->cat = $cat;
		$this->view->article = $article;
		$this->view->nav_c = $cat['cat_id'];
		
		//取得关联商品
		$list_goods = $this->db->getAll('shop_goods',array('goods_id|in'=>$article['goods_ids']),'goods_id,price,goods_name,goods_img');
		if(empty($list_goods)){
			$list_goods = $this->api->getGoodsByCatId($cat['cat_id']);
		}
		$this->view->list_goods = $list_goods; 
		
		//文章分类下随即取文章
		$list_link_article = $this->api->getArticlesByCatIdAndRand($cat['cat_id']);
		
		foreach ($list_link_article as $k => $v) {
			$list_link_article[$k]['add_time'] = date('Y-m-d H:i',$v['add_time']);
		}
		
		$this->view->list_link_article = $list_link_article; 
		
		//上一篇 下一篇
		$this->view->prev_article = $this->api->getPrevArticle($id,$cat['cat_id']);
		$this->view->next_article = $this->api->getNextArticle($id,$cat['cat_id']);
		
		$this->view->seo_title = !empty($article['meta_title']) ? $article['meta_title'] : $this->view->seo_title;
		$this->view->seo_keywords = !empty($article['meta_keywords']) ? $article['meta_keywords'] : $this->view->seo_keywords;
		$this->view->seo_description = !empty($article['meta_description']) ? $article['meta_description'] : $this->view->seo_description;		
	}
	/**
	 * 
	 */
	public  function  ertaiAction()
	{
	    $data = array();	
	    
		//29=>二胎动态 
		$data['ertaizhengce']=$this->api->getArticleListByTagName('ertaizhengce',4);
		//30=>宝爸备孕必知	
		$data['baobabeiyunbizhi']=$this->api->getArticleListByTagName('baobabeiyunbizhi',4);
		//31=>宝妈备孕必知	
		$data['baomabeiyunbizhi']=$this->api->getArticleListByTagName('baomabeiyunbizhi',4);
		//32=>增强免疫
		$data['zengqiangmianyi']=$this->api->getArticleListByTagName('zengqiangmianyi',4);
		//33=>补充叶酸
		$data['buchongyesuan']=$this->api->getArticleListByTagName('buchongyesuan',4);
		
		//46=>孕前调理
		$data['yunqiantiaoli']=$this->api->getArticleListByTagName('yunqiantiaoli',4);
		//47=>孕前营养补充
		$data['yunqianyingyangbucho']=$this->api->getArticleListByTagName('yunqianyingyangbucho',4);
	
		//37=>平衡饮食
		$data['pinghenyinshi']=$this->api->getArticleListByTagName('pinghenyinshi',8);
		//38=>孕期补钙
		$data['yunqibugai']=$this->api->getArticleListByTagName('yunqibugai',8);		
		
		//45=>孕期护肤品
		$data['yunqihufupin']=$this->api->getArticleListByTagName('yunqihufupin',8);
		//39=>安胎保胎
		$data['antaibaotai']=$this->api->getArticleListByTagName('antaibaotai',8);
		//40=>产后恢复
		$data['chanhouhuifu']=$this->api->getArticleListByTagName('chanhouhuifu',4);
		//41=>产后减肥
		$data['chanhoujianfei']=$this->api->getArticleListByTagName('chanhoujianfei',4);
		//42=>产后抑郁症
		$data['chanhouyiyuzheng']=$this->api->getArticleListByTagName('chanhouyiyuzheng',4);
		//43=>产后护理
		$data['chanhouhuli']=$this->api->getArticleListByTagName('chanhouhuli',4);
		
		$this->view->data = $data;
		
	}
	
	/**
	 * 评论
	 */
	public function ztCommentAction() 
	{
		if(!$this->user)
		{
			echo Zend_Json::encode(array('status'=>0,'unlogin'=>1,'msg'=>'请先登录后评论！'));
			exit;
		}		
		
		$db = Zend_Registry::get('db');
		$data = array();
		$data['type'] = 'ertaizhengce';
		$data['user_name'] = $this->user['user_name'];
		$data['user_id'] = $this->user['user_id'];
		$data['content'] = trim($this->_request->getParam('content'));
		$data['add_time'] = time();
		
		$res = $db->insert('shop_seo_comment',$data);

		if($res){
			echo Zend_Json::encode(array('status'=>1,'msg'=>'评论成功！'));
			exit;
		}else{
			echo Zend_Json::encode(array('status'=>0,'msg'=>'评论失败！'));
			exit;
		}
		
	}
	
	public  function  getCommentAction()
	{
		$page = $this->_request->getParam('page');
		$page = $page>0?$page:1;
		$pageSize = 3 ;
		$offset = ($page-1)*$pageSize;
		
		$sqlWhere = " WHERE type='ertaizhengce' ";
		$orderBy = " ORDER BY add_time DESC ";
		$limit = "  LIMIT  $pageSize  OFFSET $offset";
		
		$db = Zend_Registry::get('db');
		$row =$db -> fetchOne("SELECT count(*) as count FROM shop_seo_comment $sqlWhere");
		$data = $db -> fetchAll("SELECT * FROM shop_seo_comment $sqlWhere $orderBy $limit");
		$this->view->data = $data;
		
		$pageNav = new Custom_Model_PageNavJS($row['count'], $pageSize);
		$this -> view -> pageNav = $pageNav -> getPageNavigation('get_zt_comment(%%page%%)');
		$html = $this->view->render('news/inc-comment.tpl');
		
		echo Zend_Json::encode(array('status'=>1,'html'=>$html));
		exit;
	}	

}