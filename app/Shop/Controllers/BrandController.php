<?php
class BrandController extends Zend_Controller_Action
{
	/**
     * 品牌 API
     *
     * @var Shop_Models_API_Brand
     */
	private $_api = null;
	/**
     * 产品 API
     *
     * @var Shop_Models_API_Brand
     */
	private $_goods_api = null;
     /**
      * 登陆状态
      */
    private $_auth=null;
	/**
	 * 排序字段映射
	 */
	private $_orderby = array(
                           'time'=>'goods_add_time',
                           'id'=>'goods_id',
                           'price'=>'price'
    );
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _auth = Shop_Models_API_Auth :: getInstance();
        $this -> _api = new Shop_Models_API_Brand();
        $this -> _goods_api = new Shop_Models_API_Goods();
	}
	
	public function indexAction(){
		$asName = $this -> _request -> getParam('asname', null);
		if($asName){
			//查询当前品牌
			$brandVo=$this->_api->getBrandByAsName($asName);
			if($brandVo){
				//渲染品牌
				$this->view->brand=$brandVo;
	       		//分类列表
	       		$cateList=$this->_api->getCateListByBrandId($brandVo['brand_id']);
				$this->view->cateList=$cateList;
				//SEO描述
				$this -> view -> page_title = $brandVo['title'] ? $brandVo['title'] :$brandVo['brand_name'].  ' - ' ."   垦丰商城 - 世界种子精品商城 ";
	        	$this -> view -> page_keyword = $brandVo['keywords'] ? $brandVo['keywords'] : $brandVo['brand_name']."种子,玉米,水稻,大豆,小麦,大麦,甜菜,其他";
	        	$this -> view -> page_description = $brandVo['description'] ? $brandVo['description'] : '垦丰商城 -专业的种子商城提供['.$brandVo['brand_name'].']在线销售，品质保证!';
				if($brandVo['bluk']==1){
					//是否登陆
					$this -> _helper -> viewRenderer -> setNoRender();
			        $loginUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/login.html';
			        $regUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/reg.html';
			        $logoutUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/logout.html';
					$login = $this -> _auth -> checkAuthState($loginUrl, $regUrl, $logoutUrl);
					$this->view->loginstatus=$login;
					//购物车
					$this -> _apiCart = new Shop_Models_API_Cart();
			        $data = $this -> _apiCart -> getCartProduct();
			        $goods_ids=array();
			        if($data['data']){
				        foreach($data['data'] as $vo){
				        	$goods_ids[]=$vo['goods_id'];
				        }
			        }
			        $cartGoods=$this->_api->getGoodsByGoodsIds($goods_ids);

					$kv_gid_asname = array();
					if($cartGoods){
						foreach($cartGoods as $k=>$v){
							$kv_gid_asname[$v['goods_id']] = $v['as_name'];
						}
					}
					if($data['data']){
				        foreach($data['data'] as $k=>$v){
				        	$data['data'][$k]['as_name'] = $kv_gid_asname[$v['goods_id']];
				        }
					}
			        $this -> view -> data = $data['data'];
			        $this -> view -> number = $data['number'];
			        $this -> view -> amount = $data['amount'];
			        if (is_array($data['group_goods_summary']) && isset($data['other']['group_goods'])){
			        	$this -> view -> other = $data['group_goods_summary'];
			        }
			        if ($data['other_index']['fixedPackage']){
			        	$this -> view -> package .= $data['other_index']['fixedPackage'];
			        }
			        if ($data['other_index']['choosePackage']){
			            $this -> view -> package .= $data['other_index']['choosePackage'];
			        }
			        if ($data['other_index']['buyGift']){
			            $this -> view -> package .= $data['other_index']['buyGift'];
			        }
			        if ($data['other_index']['orderBuyGift']){
			            $this -> view -> package .= $data['other_index']['orderBuyGift'];
			        }
			        //热销产品
			        $page['pn'] = 1;
					$page['ps'] = 10;
			        $goodsHotList=$this->_api->getGoodsListByBrandIdPage($brandVo['brand_id'],$page,"asc",$this->_orderby["time"]);
			        $this -> view ->goodsHotList=$goodsHotList;
			        $goodsCommandList=$this->_api->getGoodsListByBrandIdPage($brandVo['brand_id'],$page,"asc",$this->_orderby["price"]);
			        $this -> view ->goodsCommandList=$goodsCommandList;
			        //广告位
					$apiNews = new Shop_Models_API_News();
			        $advList=$apiNews->getAdvyPosition("B".$brandVo['brand_id'],5);
			        $this -> view -> advList =$advList;
			        //顶部Cate Ids
			        $topCateList=$this->_api->getCateListByIds($brandVo['topcateids'],7);
			        $this -> view -> topCateList =$topCateList;
			        //友情链接
			        $friendLinkList=$apiNews->getFriendLinkByPosition("B".$brandVo['brand_id']);
			        $this->view->friendLinkList=$friendLinkList;
			        //中部 Cate Ids
			        $centerCateList=$this->_api->getCateListByIds($brandVo['centercateids'],6);
			        $this -> view -> centerCateList =$centerCateList;
			        //底部 Cate Ids
			        $bottomCateList=$this->_api->getCateListByIds($brandVo['bottomcateids'],6);
			        $this -> view -> bottomCateList =$bottomCateList;
			        //资讯
					$news = $apiNews->getNewsByPositionAndFilter('B'.$brandVo['brand_id'], 27);
					$this->view->news=$news;
					//推荐商品
					$page['pn'] = 1;
					$page['ps'] = 12;
			        $goodsRecommendsList=$this->_api->getGoodsListByBrandIdPage($brandVo['brand_id'],$page,"desc",$this->_orderby["id"]);
			        $this->view->goodsRecommendsList=$goodsRecommendsList;
			        //品牌馆推荐品牌
					$advanceBrand = $this -> _goods_api -> getTag("tag_id=18",1,6);
					$this->view->advanceBrand=$advanceBrand;
					$this->render("brandindex");
				}else{

					//资讯
					$apiNews = new Shop_Models_API_News();
					$news = $apiNews->getNewsByPositionAndFilter('B'.$brandVo['brand_id'], 12);
					$this->view->news=$news;
					//获取品牌下的所有商品 分页
					$page['pn'] = $this->_request->getParam('page',1);
					$page['ps'] = 12;
					$asc=$this->_request->getParam('asc','asc');
					$orderby=$this->_request->getParam('orderby','time');
					$this->view->asc=$asc;
					$this->view->orderby=$orderby;
					$goodsList=$this->_api->getGoodsListByBrandIdPage($brandVo['brand_id'],$page,$asc,$this->_orderby[$orderby]);
					//分页标签
					$this->view->pagenav = $page['pagenav'];
					$this->view->goodsList=$goodsList;
					//小品牌馆热销排行榜
					$hotGoods = $this -> _goods_api -> getTag("tag_id=12",1,10);
					$this->view->hotGoods=$hotGoods;
					//小品牌馆最受关注的商品
					$focusGoods = $this -> _goods_api -> getTag("tag_id=13",1,10);
					$this->view->focusGoods=$focusGoods;
					//浏览历史
					$history = $this->_goods_api->getHistory();
	       			$this->view->history = $history;

				}
			}else{
				$this -> pageerror();
			}
		}else{
			$this -> pageerror();
		}

	}
	public function brandlistAction(){
		$asName = $this -> _request -> getParam('asname', null);
		$cid = $this -> _request -> getParam('cid', null);
		if($asName && $cid){
			//查询当前品牌
			$brandVo=$this->_api->getBrandByAsName($asName);
			$cateVo=$this->_api->getCateById($cid);
			if($brandVo && $cateVo){
				//渲染品牌
				$this->view->brand=$brandVo;
				//渲染分类
				$this->view->cate=$cateVo;
				//获取品牌下的所有商品 分页
				$page['pn'] = $this->_request->getParam('page',1);
				$page['ps'] = 12;
				$asc=$this->_request->getParam('asc','asc');
				$orderby=$this->_request->getParam('orderby','time');
				$this->view->asc=$asc;
				$this->view->orderby=$orderby;
				$goodsList=$this->_api->getGoodsListByBrandIdCateIdPage($brandVo['brand_id'],$page,$asc,$this->_orderby[$orderby],$cateVo['cat_id']);
				$this->view->countGoods=$page['tcount'];
				//分页标签
				$this->view->pagenav = $page['pagenav'];
				$this->view->goodsList=$goodsList;
				//分类列表
	       		$cateList=$this->_api->getCateListByBrandId($brandVo['brand_id']);
				$this->view->cateList=$cateList;
				//资讯
				$apiNews = new Shop_Models_API_News();
				$news = $apiNews->getNewsByPositionAndFilter('B'.$brandVo['brand_id'].'C'.$cateVo['cat_id'], 15);
				$this->view->news=$news;
				$page_title = $cateVo['cat_name'].'-'.$brandVo['brand_name'].$cateVo['cat_name'].'品质【价格,报价,怎么样,好不好】-垦丰商城';
				$page_keyword = $cateVo['cat_name'].','.$brandVo['brand_name'].$cateVo['cat_name'].'价格,'.$brandVo['brand_name'].$cateVo['cat_name'].'报价,'.$brandVo['brand_name'].$cateVo['cat_name'].'怎么样,'.$brandVo['brand_name'].$cateVo['cat_name'].'好不好';
				$page_description = $cateVo['cat_name'].'-'.$brandVo['brand_name'].$cateVo['cat_name'].'品质,'.$brandVo['brand_name'].$cateVo['cat_name'].'价格,报价,'.$brandVo['brand_name'].$cateVo['cat_name'].'怎么样,好不好-垦丰电商-垦丰商城提供'.$brandVo['brand_name'].$cateVo['cat_name'].'在线销售，品质保障!';
				$this -> view -> page_title = $page_title;
	        	$this -> view -> page_keyword = $page_keyword;
	        	$this -> view -> page_description = $page_description;
				if($brandVo['bluk']==1){
					//是否登陆
					$this -> _helper -> viewRenderer -> setNoRender();
			        $loginUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/login.html';
			        $regUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/reg.html';
			        $logoutUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . '/logout.html';
					$login = $this -> _auth -> checkAuthState($loginUrl, $regUrl, $logoutUrl);
					$this->view->loginstatus=$login;
					//购物车
					$this -> _apiCart = new Shop_Models_API_Cart();
			        $data = $this -> _apiCart -> getCartProduct();
			        $goods_ids=array();
			        if($data['data']){
				        foreach($data['data'] as $vo){
				        	$goods_ids[]=$vo['goods_id'];
				        }
			        }
			        $cartGoods=$this->_api->getGoodsByGoodsIds($goods_ids);

					$kv_gid_asname = array();
					if($cartGoods){
						foreach($cartGoods as $k=>$v){
							$kv_gid_asname[$v['goods_id']] = $v['as_name'];
						}
					}
					if($data['data']){
				        foreach($data['data'] as $k=>$v){
				        	$data['data'][$k]['as_name'] = $kv_gid_asname[$v['goods_id']];
				        }
					}
			        $this -> view -> data = $data['data'];
			        $this -> view -> number = $data['number'];
			        $this -> view -> amount = $data['amount'];
			        if (is_array($data['group_goods_summary']) && isset($data['other']['group_goods'])){
			        	$this -> view -> other = $data['group_goods_summary'];
			        }
			        if ($data['other_index']['fixedPackage']){
			        	$this -> view -> package .= $data['other_index']['fixedPackage'];
			        }
			        if ($data['other_index']['choosePackage']){
			            $this -> view -> package .= $data['other_index']['choosePackage'];
			        }
			        if ($data['other_index']['buyGift']){
			            $this -> view -> package .= $data['other_index']['buyGift'];
			        }
			        if ($data['other_index']['orderBuyGift']){
			            $this -> view -> package .= $data['other_index']['orderBuyGift'];
			        }
					//顶部Cate Ids
			        $topCateList=$this->_api->getCateListByIds($brandVo['topcateids'],7);
			        $this -> view -> topCateList =$topCateList;
			        //友情链接
			        $friendLinkList=$apiNews->getFriendLinkByPosition('B'.$brandVo['brand_id'].'C'.$cateVo['cat_id']);
			        $this->view->friendLinkList=$friendLinkList;
					//推荐商品 config
					$recommandGoodsList=$this -> _api -> getRecommandByConfig($brandVo['config'],6);
					$this -> view -> recommandGoodsList =  $recommandGoodsList;
					//品牌馆推荐品牌
					$advanceBrand = $this -> _goods_api -> getTag("tag_id=18",1,6);
					$this->view->advanceBrand=$advanceBrand;
			        $this->render("brandlisttemp");
				}else{
					//小品牌馆热销排行榜
					$hotGoods = $this -> _goods_api -> getTag("tag_id=12",1,10);
					$this->view->hotGoods=$hotGoods;
					//小品牌馆最受关注的商品
					$focusGoods = $this -> _goods_api -> getTag("tag_id=13",1,10);
					$this->view->focusGoods=$focusGoods;
					//浏览历史
					$history = $this->_goods_api->getHistory();
	       			$this->view->history = $history;
				}
			}else{
				$this -> pageerror();
			}
		}else{
			$this -> pageerror();
		}
	}



	/**
	 * 不存在页面处理
	 */
	public function pageerror(){
			$this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
			$content ='您要找的页面不存在';
			$this->getResponse()->clearBody(); //清除掉别的信息
			$this->view->content = $content;  //404页面模板的信息
			$this -> render('error');
	}


	/**
	 * 清空流量记录
	 */
	public function clearHistoryAction(){
		setcookie('history', '', time () + 86400 * 365, '/');
		exit;
	}


	/**
	 * 新品牌频道页
	 */
	public function pinpaiAction(){
		$this -> view->css_more=',brand.css';
	    $this -> view -> indextag = $this -> _goods_api ->getGoodsTag('tag_id in ("48","49")');





	}

}