<?php
class NoticeController extends Zend_Controller_Action {
	
  public function init()
  {
  	$this->_pageApi = new Shop_Models_API_Page();
  	$this->view->css_more=",notice.css";
  	$this->guessYouLove();
  }	  
  public function  indexAction()
  {
  	$page = $this->_request->getParam('page') ? (int)$this->_request->getParam('page') : 1;
  	$data = $this->_pageApi->getArtList(array('cat_id'=>34),$page);
  	$objPage = new Custom_Model_PageNav($data['total'],10);
  	$pagenav = $objPage ->getNavigation();
  	$this->view ->list = $data['data'];
  	$this->view->pagenav = $pagenav;
  	
  	$cat = array_shift($this->_pageApi->_db->getCat(" cat_id=34 "));
  	$this->view->page_title = !empty($cat['meta_title']) ? $cat['meta_title'] :$cat['cat_name'].'-垦丰商城';
  	$this->view->page_keywords = !empty($cat['meta_keywords']) ? $cat['meta_keywords'] : $this->view->page_keywords;
  	$this->view->page_description = !empty($cat['meta_description']) ? $cat['meta_description'] : $this->view->page_description;
  	
  }	
	
 
 public function detailAction()
 {
 	$id = $this->_request->getParam('id'); 
 	if($id>0){
 		$data = $this->_pageApi->getInfo("article_id ={$id}");
 		$article =  array_shift($data);
 		$this->view->info =$article;
 		$this->view->page_title = !empty($article['meta_title']) ? $article['meta_title'] : $article['title'].'-垦丰商城';
 		$this->view->page_keywords = !empty($article['meta_keywords']) ? $article['meta_keywords'] : $this->view->page_keywords;
 		$this->view->page_description = !empty($article['meta_description']) ? $article['meta_description'] : mb_substr(strip_tags($article['content']),0,200,'utf-8');
 	}else{
 	   header("Location:/notice"); exit();
 	}
 
 } 
 
 private  function guessYouLove()
 {

 	$cartApi = new Shop_Models_API_Cart();
 	$cat_products= $cartApi -> getCartProduct();
 	$goodIds = array();
 	if($cat_products['data']){
 		foreach ($cat_products['data'] as $val)
 		{
 			if (intval($val['goods_id'])>0) $goodIds[] = $val['goods_id'];
 		}
 	}
 	$apiGoods = new Shop_Models_API_Goods();
 	$tree_nav_cat = $apiGoods -> getCatNavTree();
 	$this -> view -> tree_nav_cat = $tree_nav_cat;
 	//猜你喜欢
 	if($goodIds && count($goodIds)>0)
 	{
 		$goodsApi  =  new Shop_Models_API_Goods();
 		$gkey =rand(0, count($goodIds)-1);
 		$links = $goodsApi->guessGoods($goodIds[$gkey],$goodIds);
 	}
 	
 	$this -> view -> links = $links;
 }
 
}
