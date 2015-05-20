<?php
class GoodsController extends Zend_Controller_Action
{
    /**
     *
     * @var Shop_Models_API_Goods
     */
    protected $_api = null;
    protected $_auth = null;
   

		private $_filter_price = array(
        array(
            'price_value'=>'0',
            'price_name'=>'全部'
        ),array(
            'price_value' => '1',
			'code'        => '0_50',
            'price_name'  => '0-50元'
        ),array(
            'price_value' => '2',
			'code'        => '50_100',
            'price_name'  => '50-100元'
        ),array(
            'price_value' => '3',
			'code'        => '100_300',
            'price_name'  => '100-300元'
        ),array(
            'price_value' => '4',
			'code'        => '300_500',
            'price_name'  => '300-500元'
        ),array(
            'price_value' => '5',
			'code'        => '500_1000',
            'price_name'  => '500-1000元'
        ),array(
            'price_value' => '6',
			'code'        => '1000_2000',
            'price_name'  => '1000-2000元'
        ),array(
            'price_value' => '7',
			'code'        => '2000',
            'price_name'  => '2000元以上'
        )
    );//价格过滤器配置

    private $_filter_sort = array(
        array(
			'value'=> 1,
            'sorttype'=>array('1', '2'),
            'sortname'=>'更新时间'
        ),
		array(
			'value'=> 3,
            'sorttype'=>array('3', '4'),
            'sortname'=>'价格'
        ),
    );//排序

	private $_sort_desc = array(
		'1' => 'uptime_a',
		'2' => 'uptime_a',
		'3' => 'price_a',
		'4' => 'price_d',
	);


    /**
     * 初始化对象
     *
     * @return   void
     */
	public function init()
	{
		$this -> _api = new Shop_Models_API_Goods();
        $this -> _auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
        $this -> view -> auth = $this -> _auth;
        $this -> view -> u = $this -> _request -> getParam('u', '');
		
        if (!$this -> _auth) {
             $this -> view -> login = 1;
        }
		
	}

	/**
     * 商品标签单页
     *
     * @return void
     */
	public function listAction()
	{
	    $pageSize =10;
        $page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$list = $this -> _api -> getGoodsList("  a.price >1 and is_gift = 0 and onsale= 0 and a.description IS NOT NULL", 'a.goods_id,a.goods_name,a.goods_sn,a.goods_img,goods_style,a.description',$page,$pageSize);
		$this -> view -> goodslist = $list;
        $pageNav = new Custom_Model_PageNav($this -> _api -> getCount(), $pageSize);
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
	}

	/**
     * 商品详情页面
     *
     * @return void
     */
    public function showAction()
    {
        $this -> _groupGoodsAPI = new Shop_Models_API_GroupGoods();
        $this -> _msgAPI = new Shop_Models_API_Msg();
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
                $union = new Shop_Models_API_Union();
				//$this -> _api->setHistory($id);// 新加的，加入历史列表
                $uid = $union -> getUidFromCookie();
                $aid = $union -> getNidFromCookie();
                if ($uid == '9096' ) {
                    $aid = substr($aid, 0, 10 );
                }
                if ($uid == '21' ) {
                    $wiNid=explode('|',base64_decode($aid));
                    $aid = $wiNid['0'];
                }
                $uid = $uid ? $uid : (isset($_GET['u']) ? intval($_GET['u']) : null);
                if($uid>0){
                    if($aid){
                         $viewTag = $this -> _api->getViewTag("type=1 and union_id='$uid' and union_param='$aid' and  goods_ids like '%$id%' " );
                    }else{
                         $viewTag = $this -> _api->getViewTag("type=1 and union_id='$uid' and goods_ids like '%$id%' " );
                    }
                    if($viewTag &&  in_array($id,  explode(',',$viewTag['goods_ids'])  )){
                        $this -> view -> showTag = '1';
                        $this -> view -> viewTag = $viewTag;
                    }
                }
        }
        $id = (int)$this -> _request -> getParam('id', null);
        $top_attr_id = (int)$this -> _request -> getParam('top_attr_id', 0);
        if ($id > 0) {

            $this -> view -> id = $id;
            $result = $this -> _api -> view($id, $top_attr_id);
            $data = $result['data'];
            $data['price_seg'] = unserialize($data['price_seg']);   //多数量价格
            $this -> view -> data = $data;
			
			$this -> view -> goods_sn = $data['goods_sn'];
            //取品牌
            $this -> view -> brand = $this -> _api -> getBrandById($data['brand_id']);
            //推荐商品
			$links=$this -> _api ->getLink($id);
			$this -> view -> links = $links;
			//组合商品
            $groupGoodsData = $this -> _groupGoodsAPI -> getRelateGroupGoods($id);
            
            $groupGoodsAData = '';$groupGoodsBData = '';
            if ( $groupGoodsData ) {
                foreach ( $groupGoodsData as $groupGoods ) {
                    if ( $groupGoods['type'] == 'A' ) {
                        $groupGoodsAData[] = $groupGoods;
                    }
                    else if ( $groupGoods['type'] == 'B' ) {
                        $groupGoodsBData[] = $groupGoods;
                    }
                    else if ( $groupGoods['type'] == 'C' ) {
                        $groupGoodsCData[] = $groupGoods;
                    }
                }
                $this -> view -> groupGoodsAData = $groupGoodsAData;
                $this -> view -> groupGoodsBData = $groupGoodsBData;
                $this -> view -> groupGoodsCData = $groupGoodsCData;
            }
            
        	for ($i = 1; $i <= $result['data']['limit_number']; $i++) {
        	    $buy_number[$i] = $i;
            }
            $tempArr = explode(',',trim($result['data']['cat_path'],','));
            if($tempArr && count($tempArr) >= 5){
                $this -> view -> cat_id = $tempArr[4];
            } else {
                $this -> view -> cat_id = $result['data']['view_cat_id'];
            }
            $this -> view -> user = $this->user;
	        $this -> view -> nav = $result['nav'];
			
			$this -> view -> ur_here = " <a href='/'>首页</a> &gt; ".$data['cat_path_name'].$result['nav']." <code>&gt;</code> ".$data['goods_name'];
			
            $this -> view -> buy_number = $buy_number;
			if ($data['goods_img_ids']) {
                $this -> view -> img_url = $this -> _api -> getImg("img_id in ({$data['goods_img_ids']}) and img_type=2");
                $this -> view -> img_ext_url = $this -> _api -> getImg("img_id in ({$data['goods_img_ids']}) and img_type=3");
            }
            $this -> view -> goto = base64_encode("/goods-{$id}.html");
        }else{
            $this -> _helper -> redirector -> gotoUrl(Zend_Controller_Front::getInstance() -> getBaseUrl());
        }

       //库存
        $stockAPI = new Admin_Models_API_Stock(); 
        $stock = $stockAPI -> getSaleProductStock($data['product_id'],true);
        $this->view ->stock_number  = $stock['able_number'];
        
        $buylog = $this -> _msgAPI -> getBuyLog($id, 10);
		$this -> view -> buylog = $buylog;

        $this -> view -> page_title = $data['meta_title'] ? $data['meta_title'] : $data['goods_name'].$data['goods_style'].'-'.$data['cat_name']."种子-【作用,价格,品质,怎么样】 -垦丰商城";
        $this -> view -> page_keyword = $data['meta_keywords'] ? $data['meta_keywords'] : $data['goods_name'].','.$data['cat_name'].",".$data['goods_name'].'作用,'.$data['goods_name'].'价格';
        $this -> view -> page_description = $data['meta_description'] ? $data['meta_description'] : $data['goods_name'].'作用,价格,功效-'.$data['cat_name'].'垦丰商城提供'.$data['goods_name'].'在线销售，品质保障!';

        //关联商品
        $this->view->view_relation = $this->_api->getRelation($id,'single','view',3);
        $this->view->buy_relation = $this->_api->getRelation($id,'single','buy',3);
        $this->view->similar_relation = $this->_api->getRelation($id,'single','similar',3);
        //浏览历史
       // $this->view->history = $t = $this->_api->getHistory();
        
        $this->view->js_more=',jquery.jqzoom.js,goods.js,check.js';
        $this->view->css_more = ',list_v2.css,detail.css';
    }
    /**
     * 设置商品历史记录
     */
    public function setHistoryGoodsAction()
    {
      $this->view->history = $t = $this->_api->getHistory();
      $html =  $this->view->render('goods/history.tpl');
          	
      $goods_id =  $this->_request->getParam('goods_id');
      if($goods_id)
      {
      	$this->_api->setHistory($goods_id);	      
      }
      
      echo Zend_Json::encode(array('status'=>1,'msg'=>"请求成功",'html'=>$html));
      exit();
    }    
   

    public function groupBuyAction(){
        $gid = $this->_request->getParam('gid');
        $this -> _helper -> viewRenderer -> setNoRender();
        $number = (int)$this -> _request -> getParam('number', 0);
        $id = (int)$this -> _request -> getParam('id', null);
        $product_sn = $this -> _request -> getParam('product_sn', null);
        if ($number && ($product_sn || $id)) {
            $stockAPI = new Admin_Models_API_Stock();
            if ($id) {
                $product = array_shift($this -> _api -> getProduct("a.product_id = '{$id}'"));
            }
            else {
                $product = array_shift($this -> _api -> getProduct("a.product_sn = '{$product_sn}'"));
            }
        
            if (!$product) {
                exit;
            }
            $id = $product['product_id'];
            $goodsID = $product['goods_id'];
            $productSN = $product['product_sn'];
            $stockAPI = new Admin_Models_API_Stock();
            if (!$stockAPI -> checkPreSaleProductStock($id, $number, true)) {
                $stock = $stockAPI -> getSaleProductStock($id,true);
                if ($stock['able_number'] <= 0) {
                    $goodsDB = new Shop_Models_DB_Goods();
                    $goodsDB -> updateStatus(1, $productSN, $goodsID);
                }
                exit('对不起，库存不足！');
            }
        }
        else {
            exit('Error!');
        }
        exit;        
    }
    
	/**
     * 商品列表页面
     *
     * @return   void
     */
    public function galleryAction()
    {
    	$requst_url = $_SERVER['REQUEST_URI'];
    	$cut_url = substr($requst_url,-13,8);    	
    	if($cut_url == '-0-0-0-1')
    	{
    		header("HTTP/1.1 301 Moved Permanently");
    		$jumpurl = str_replace($cut_url, '', $requst_url);
    		header("Location: ".$jumpurl);
    		exit();
    	}
    	
        $search = $this->_request->getParams();
        $cat_id = $search['cat_id'] ? (int)$search['cat_id'] : 0;
        if(!$cat_id) die('error!');
        $this -> view -> cat_id = $cat_id;
		
        //分类名称
        $this -> view -> cat_name = $cat_name = $this -> _api -> getCatNameById($cat_id);
        $this -> view -> cat_crumbs = $this->_api->getCatCrumbs($cat_id);
		
        //左则分类菜单
        $this-> view -> cat_menu = $this -> _api -> getCatSiblings($cat_id);

        //根据类别ID取得品牌
        $filter_brand = $this -> _api -> getBrandByCatId($cat_id);
        array_unshift($filter_brand, array('brand_id'=>0,'brand_name'=>'全部'));
		
		$search['bid']   = !empty($search['bid']) ? $search['bid'] : '0';
		$search['price'] = !empty($search['price']) ? $search['price'] : '0';
		$search['sort']  = !empty($search['sort']) ? $search['sort'] : '0';
		$search['page']  = !empty($search['page']) ? $search['page'] : '1';
        
        //品牌过滤器
		$current_url = 'gallery-'.$cat_id.'-'. $search['bid'].'-'.$search['price'].'-'.$search['sort'].'-'.$search['page'] .'.html';
        $this -> _modifyFilter($current_url,$filter_brand,'bid','brand_id');
        $this -> view -> filter_brand = $filter_brand;
		$seo_brand_name = '';
		if(!empty($search['bid'])){
			$arr_kv_brand = Custom_Model_Tools::list_fkey($filter_brand, 'brand_id');
			$seo_brand_name = $arr_kv_brand[$search['bid']]['brand_name'];			
		}
		
        //价格过虑器
        $this -> _modifyFilter($current_url,$this->_filter_price,'price','price_value');
        $this -> view -> filter_price = $this->_filter_price;
		$seo_price_area = '';
		if(!empty($search['price'])){
			$arr_kv_price = Custom_Model_Tools::list_fkey($this->_filter_price, 'price_value');
			$seo_price_area = $arr_kv_price[$search['price']]['price_name'];			
		}

        //商品列表
        $pn = $this->_request->getParam('page') ? (int)$this->_request->getParam('page') : 1;

		$search['price'] = $this->_filter_price[$search['price']]['code'];
		$search['sort'] && $search['sort']  = $this->_sort_desc[$search['sort']];
        $datas = $this->_api->getGoodsByPage($pagenav,$pn,$search);
		$offers = new Shop_Models_API_Offers();
		$datas && $datas = $offers -> responseToView($datas);
		
		$this->view->list_goods=   $datas;
        $this->view->pagenav = $pagenav;
        //排序
        $this -> _modifySort($current_url,$this->_filter_sort);
        $this->view->filter_sort = $this->_filter_sort;

        //关联商品
        $this->view->view_relation = $this->_api->getRelation($cat_id,'cat','view',3);
        $this->view->buy_relation = $this->_api->getRelation($cat_id,'cat','buy',3);
        $this->view->similar_relation = $this->_api->getRelation($cat_id,'cat','similar',3);

        //浏览历史
        $this->view->history = $t = $this->_api->getHistory();

        //资讯
        $apiNews = new Shop_Models_API_News();
        $t = $apiNews->getNewsByPositionAndFilter('C2', 12);

        //SEO
        $r_cat = $this->_api->getCatById($cat_id);
		$sub_cat_ids_222 = $this->_api->getSubId(222);
		$sub_cat_ids_165 = $this->_api->getSubId(165);
		$conf_scid = array_merge($sub_cat_ids_222,$sub_cat_ids_165);        
		
		$meta_title = '';
		if(!empty($search['bid']) && empty($search['price'])){
			if(in_array($cat_id, $conf_scid)){
				$meta_title = $seo_brand_name.$cat_name.',0-2000元以上'.$cat_name.'产品,怎么样,好不好-垦丰商城';
			}else{
				$meta_title = $seo_brand_name.'恳丰种子,0-2000元以上'.$cat_name.'种子价格,报价-垦丰商城';
			}
		}elseif(!empty($search['bid']) && !empty($search['price'])){
			if(in_array($cat_id, $conf_scid)){
				$meta_title = $seo_brand_name.$cat_name.','.$seo_price_area.$cat_name.'产品,怎么样,好不好-垦丰商城';
			}else{
				$meta_title = $seo_brand_name.'种子,'.$seo_price_area.$cat_name.'种子价格,报价-垦丰商城';
			}
		}elseif(empty($search['bid']) && !empty($search['price'])){
			if(in_array($cat_id, $conf_scid)){
				$meta_title = '品牌'.$cat_name.','.$seo_price_area.$cat_name.'产品,怎么样,好不好-垦丰商城';
			}else{
				$meta_title = '品牌种子,'.$seo_price_area.$cat_name.'种子价格,报价-垦丰商城';
			}
		}else{
			$meta_title = $r_cat['meta_title'];
		}
		
		$meta_keywords = '';
		if(!empty($search['bid']) && empty($search['price'])){
			if(in_array($cat_id, $conf_scid)){
				$meta_keywords = $seo_brand_name.$cat_name.',0-2000元以上'.$cat_name.'产品,'.$seo_brand_name.'怎么样,'.$seo_brand_name.'好不好';
			}else{
				$meta_keywords = $seo_brand_name.'种子,0-2000元以上'.$cat_name.'种子,'.$seo_brand_name.'种子价格,'.$seo_brand_name.'种子报价';
			}
		}elseif(!empty($search['bid']) && !empty($search['price'])){
			if(in_array($cat_id, $conf_scid)){
				$meta_keywords = $seo_brand_name.$cat_name.','.$seo_price_area.$cat_name.'产品,'.$seo_brand_name.'怎么样,'.$seo_brand_name.'好不好';
			}else{
				$meta_keywords = $seo_brand_name.'种子,'.$seo_price_area.$cat_name.'种子,'.$seo_brand_name.'种子价格,'.$seo_brand_name.'种子报价';
			}
			
		}elseif(empty($search['bid']) && !empty($search['price'])){
			if(in_array($cat_id, $conf_scid)){
				$meta_keywords = '品牌'.$cat_name.','.$seo_price_area.$cat_name.'产品,品牌怎么样,品牌好不好';
			}else{
				$meta_keywords = '品牌保种子,'.$seo_price_area.$cat_name.'种子,品牌种子价格,品牌种子报价';
			}
		}else{
			$meta_keywords = $r_cat['meta_keywords'];
		}
		
		$meta_description = '';
		if(!empty($search['bid']) && empty($search['price'])){
			if(in_array($cat_id, $conf_scid)){
				$meta_description = $seo_brand_name.$cat_name.',0-2000元以上'.$seo_brand_name.$cat_name.'产品，垦丰商城提供'.$seo_brand_name.$cat_name.','.$seo_brand_name.$cat_name.'产品在线销售,价格,报价,怎么样,好不好，绝对正品保证，支持货到付款，30天退换货保障!';
			}else{
				$meta_description = $seo_brand_name.'种子,0-2000元以上'.$seo_brand_name.'种子，垦丰商城提供'.$seo_brand_name.'种子,'.$seo_brand_name.'种子在线销售,价格,报价，品牌保证！';
			}
		}elseif(!empty($search['bid']) && !empty($search['price'])){
			if(in_array($cat_id, $conf_scid)){
				$meta_description = $seo_brand_name.$cat_name.','.$seo_price_area.$seo_brand_name.$cat_name.'产品，垦丰商城提供'.$seo_brand_name.$cat_name.','.$seo_brand_name.$cat_name.'产品在线销售,价格,报价,怎么样,好不好，品质保证！';
			}else{
				$meta_description = $seo_brand_name.'种子,'.$seo_price_area.$seo_brand_name.'种子，垦丰商城提供'.$seo_brand_name.'种子,'.$seo_brand_name.'种子在线销售,价格,报价，绝对正品保证，支持货到付款，30天退换货保障!';
			}
		}elseif(empty($search['bid']) && !empty($search['price'])){
			if(in_array($cat_id, $conf_scid)){
				$meta_description = '品牌'.$cat_name.','.$seo_price_area.'品牌'.$cat_name.'产品，垦丰商城提供'.'品牌'.$cat_name.',品牌'.$cat_name.'产品在线销售,价格,报价,怎么样,好不好，绝对正品保证，支持货到付款，30天退换货保障!';
			}else{
				$meta_description = '品牌种子,'.$seo_price_area.'品牌种子，垦丰商城提供品牌种子,品牌种子在线销售,价格,报价，绝对正品保证，支持货到付款，30天退换货保障!';
			}
		}else{
			$meta_description = $r_cat['meta_description'];
		}
		
        $this -> view->css_more = ',list.css';
        $this -> view -> page_title = $meta_title;
        $this -> view -> page_keyword = $meta_keywords;
        $this -> view -> page_description = $meta_description;

    }

    private function _modifySort($url,&$sort){
        $params = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
        $params = Custom_Model_DeepTreat::filterArray($params, 'htmlspecialchars');
        unset($params['cat_id']);
        unset($params['controller']);
        unset($params['action']);
        unset($params['page']);
        foreach ($sort as $k=>$v){
            //排序类标识
			$param_sort = $this->_request->getParam('sort');
            $sortclass = 'all_screen_off';
            if(isset($param_sort)){
                if(in_array($param_sort, $v['sorttype'])){
                    $sortclass = 'all_screen';
                    if($param_sort % 2 == 0){
                        $v['value'] = $param_sort -1;
                        $sortclass = 'all_screen_d';
                    }else{
                        $v['value'] = $param_sort +1;
                        $sortclass = 'all_screen';
                    }
                }


            }
            $sort[$k]['sortclass'] = $sortclass;
            $params['sort'] = $v['value'];
			$urls = explode('-', $url);
			$urls[4] = $params['sort'];
			$sort[$k]['url'] = implode('-', $urls);
        }
    }

    private function _modifyFilter($pre_url,&$filter,$fname,$fkey){
        $params = $this->_request->getParams();
        unset($params['cat_id']);
        unset($params['controller']);
        unset($params['action']);
		unset($params['module']);
        foreach ($filter as $k=>$v){

            $is_c = false;//当前标识
			$pre_params = explode('-', $pre_url);
			if (isset($v['brand_id'])) {
				$pre_params[2] = $v['brand_id'];
				$pre_params[5] = '1.html';
			} else if (isset($v['price_value'])) {
				$pre_params[3] = $v['price_value'];
				$pre_params[5] = '1.html';
			}
			
			$filter[$k]['url'] = implode('-', $pre_params);
            //当前状态标识
            if($this->_request->getParam($fname)){
                if($v[$fkey] == $this->_request->getParam($fname)) $is_c = true;
            }else{
                if($k == 0) $is_c = true;
            }
            $filter[$k]['is_c'] = $is_c;
        }
    }

	private function _searchFilter($pre_url,&$filter,$fname,$fkey){
        $params = $this->_request->getParams();
        unset($params['cat_id']);
        unset($params['controller']);
        unset($params['action']);
        unset($params['page']);
		unset($params['module']);
        foreach ($filter as $k=>$v){
            $is_c = false;//当前标识
            if(empty($v[$fkey])){
                unset($params[$fname]);
            }else{
                $params[$fname] = $v[$fkey];
            }
            $str_params = '';
            foreach ($params as $kk=>$vv){
                $str_params .= $kk.'='.$vv.'&';
            }
            $str_params = trim($str_params,'&');
            $url_param = empty($str_params) ? '' : '?'.$str_params;
            $filter[$k]['url'] = $pre_url.$url_param;
            //当前状态标识
            if($this->_request->getParam($fname)){
                if($v[$fkey] == $this->_request->getParam($fname)) $is_c = true;
            }else{
                if($k == 0) $is_c = true;
            }
            $filter[$k]['is_c'] = $is_c;
        }
    }


	/**
     * 商品标签单页
     *
     * @return void
     */
	public function labelAction()
	{
	    $pageSize =10;
        $page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$tag_id = (int)$this -> _request -> getParam('id', 1);
		$this -> view -> tag_id = $tag_id ;
		$datas = $this -> _api -> getTag("tag_id=".$tag_id);
		$this -> view -> page_title = $datas['data']['title'];
		$this -> view -> datas = (is_array($datas['details']) && count($datas['details'])) ? array_slice($datas['details'],($page-1)*$pageSize,$pageSize) : $datas['details'];
        $pageNav = new Custom_Model_PageNav($datas['totle'], $pageSize);
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        $this -> view -> cur_place = 'new';
         $this -> view -> css_more = ',list.css';
	}

	/**
     * 商品评论
     *
     * @return void
     */
	public function msgAction()
	{
		$this -> _msgAPI = new Shop_Models_API_Msg();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
		$this -> _helper -> viewRenderer -> setNoRender();
		if ($this -> _request -> isPost()) {
        	$post = $this -> _request -> getPost();
        	$result = $this -> _msgAPI  -> goodsMsg($post);
        	if ($result) {
        	    exit($result);
        	}
        } else {
        	exit('error');
        }
	}

	/**
     * 解析邮件模板
     *
     * @param    string    $content
     * $param    array     $values
     * @return   array
     */
	private function parseTemplate($content, $values)
	{
		is_array($values) && extract($values);
		$content = preg_replace('/\{\{\s*\$([^}]*)\s*\}\}/', '${' . "\${1}" . '}', $content);
		$content = preg_replace('/"/', '\"', $content);
		eval("\$content = \"$content\";");
		return $content;
	}

	/**
     * 获得商品原始价格(商品详情页面Ajax调用)
     *
     * @return   array
     */
	public function getPriceAction()
	{
	    $id = (int)$this -> _request -> getParam('id', null);
	    $number = (int)$this -> _request -> getParam('number', null);
	    if ($id && $number) {
	        $goods = $this -> _api -> getGoodsInfo(" and goods_id = {$id}");
	        if ($goods) {
	            echo $this -> _api -> getPrice(unserialize($goods['price_seg']), $goods['price'], $number);
	        }
	    }
	    exit;
	}
    /**
     * 商品评论
     *
     * @return void
     */
	public function commentAction()
	{
	    $conf = $this -> _request -> getParam('conf',0);
	    $this -> _msgAPI = new Shop_Models_API_Msg();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
        $id = (int)$this -> _request -> getParam('id', null);
        if($id){
            if(empty($conf)){
                $where = "status=1 and type= 1 and goods_id=$id ";
            }else if($conf == 1){
                $where = "status=1 and type= 1 and  goods_id=$id and (cnt2=4 or cnt2=5) ";
            }elseif($conf == 2){
                $where = "status=1 and type= 1 and goods_id=$id and (cnt2=2 or cnt2=3)  ";
            }elseif($conf == 3){
                $where = "status=1 and type= 1 and goods_id=$id and cnt2=1 ";
            }
			
			$datas = $this -> _msgAPI -> getGoodsMsg($where, '*', $page, 5);
			$cnt = $this -> _msgAPI -> getGoodsCnt($id);
			$total = $this -> _msgAPI -> getCount();
			$this -> view -> cnt = $cnt;
			$this -> view -> id = $id;
			$this -> view -> datas = $datas;
			$this -> view -> total = $total;
			if ($this -> _msgAPI -> checkIp()) {
				$this -> view -> office = true;
			}
			$goods = new Shop_Models_DB_Goods();
			$this -> view -> goods = array_shift($goods -> fetch("goods_id=$id"));
			$pageNav = new Custom_Model_PageNavJS($total, 5);
            $this -> view -> pageNav = $pageNav -> getPageNavigation('getCommentList('.$id.',%%page%%)');
        }
        //取评论信息  getCommByLevel
        $level_data['levelall']= $this->_msgAPI->getCommByLevel("goods_id = $id  ");
        $level_data['levelall']= empty($level_data['levelall'])?1: $level_data['levelall'];
        $level_data['level1']= $this->_msgAPI->getCommByLevel("goods_id = $id AND (cnt2=4 or cnt2=5) "); //好评
        $level_data['level2']= $this->_msgAPI->getCommByLevel("goods_id = $id AND (cnt2=3 or cnt2=2) ");//中评
        $level_data['level3']= $this->_msgAPI->getCommByLevel("goods_id = $id AND cnt2=1 ");//差评

        $level_data_bf['level1']= round( $level_data['level1']/ $level_data['levelall']*100); //好评
        $level_data_bf['level2']= round( $level_data['level2']/ $level_data['levelall']*100);//中评
        $level_data_bf['level3']= round( $level_data['level3']/ $level_data['levelall']*100);//差评
        $this->view->level_data=$level_data;
        $this->view->level_data_bf=$level_data_bf;
	}


    /**
     * 商品咨询
     *
     * @return void
     */
	public function consultationAction()
	{
	    $this -> _msgAPI = new Shop_Models_API_Msg();
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
        $id = (int)$this -> _request -> getParam('id', null);
        if($id){
			$where = "status=1 and type= 2 and goods_id=$id ";
			$datas = $this -> _msgAPI -> getGoodsMsg($where,'*',$page,5);
			$this -> view -> id = $id;
			$this -> view -> datas = $datas;

			if ($this -> _msgAPI -> checkIp()) {
				$this -> view -> office = true;
			}
            $id = (int)$this -> _request -> getParam('id', null);
            $this -> view -> csl = $this -> _msgAPI -> getCslCount($id);

			$goods = new Shop_Models_DB_Goods();
			$this -> view -> goods = array_shift($goods -> fetch("goods_id=$id"));
        }
        if (!$this -> _auth) {
             $this -> view -> login = 1;
        }

	}

	/**
     * 搜索动作
     *
     * @return   void
     */
	public function searchAction()
    {
        $ps = 20;
        $search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
        $search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');
        $keywords = $search['keyword'];
        $this->view->keywords = $keywords;
        $this -> _api -> tjSearchWords($keywords);
        $page = empty($search['page']) ? 1 : (int)$search['page'];
        $sphinx = new Custom_Model_Sphinx();
        $rs = $sphinx->getProductResultFromSphinx($keywords, 0, $ps);
        $total = $rs['total'];
        $arr_word = array();
        if(!empty($rs['words'])){
            foreach ($rs['words'] as $k=>$v){
                $arr_word[] = $k;
            }
        }
        //所有与关键词有关的商品
        $arr_all_goods_id = array();
        $arr_all_goods_id[] = 0;
        $rs = $sphinx->getProductResultFromSphinx($keywords, 0, intval($total));
        if(!empty($rs['matches'])){
            foreach ($rs['matches'] as $k=>$v){
                $arr_all_goods_id[] = $v['id'];
            }
        }
        $filter_brand = $this -> _api -> getBrandByGoods($arr_all_goods_id);
        array_unshift($filter_brand, array('brand_id'=>0,'brand_name'=>'全部'));

        //品牌过滤器
        $this -> _searchFilter('search.html',$filter_brand,'bid','brand_id');
        $this -> view -> filter_brand = $filter_brand;
        //价格过虑器
        $this -> _searchFilter('search.html',$this->_filter_price,'price','price_value');
        $this -> view -> filter_price = $this->_filter_price;

        $objpage['ps'] = $ps;
        $objpage['pn'] = $page;
        
        $list = $this->_api->search($objpage,$arr_all_goods_id,$this->_request->getParams(),($page-1)*$ps, $ps);
        $this->view->pagenav = $objpage['pagenav'];
        
        //标题反红
        foreach ($list as $k=>$v){
            $list[$k]['goods_title'] = $this->redWord($v['goods_name'],$arr_word);
        }
		$offers = new Shop_Models_API_Offers();
		$list && $datas = $offers -> responseToView($list);

        $this->view->list = $list;

        //SEO
        $this -> view -> page_title = $cat_name ? $cat_name ." 垦丰商城 " : (isset($search['keyword']) ? $search['keyword'] : '种子,玉米,大豆,小麦,大麦,甜菜,恳丰,恳丰电商');
        $this -> view -> page_keyword = $cat['meta_keywords'] ? $cat['meta_keywords'] : $cat_name."种子,玉米,大豆,小麦,大麦,甜菜,恳丰,恳丰电商";
        $this -> view -> page_description = $cat['meta_description'] ? $cat['meta_description'] : '提供'.$cat_name.'在线销售，货到付款，7天无条件免费退换货保证! ';
       
        $this->view->css_more=",list.css,list_v2.css";
        //浏览历史
        $this->view->history = $this->_api->getHistory();
		
        //左则分类菜单
        $this-> view -> cat_menu = $this -> _api -> getCatSiblings(69);
		
    }

    private function redWord($str,$arr_word){
        if(empty($arr_word)) return $str;
        foreach ($arr_word as $k=>$v){
            $str = str_replace($v, '<font color="red">'.$v.'</font>', $str);
        }
        return $str;
    }

    /**
     * 检查库存和限购数量
     *
     * @return void
     */
    public function checkAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$number = (int)$this -> _request -> getParam('number', 0);
    	$id = (int)$this -> _request -> getParam('id', null);
    	$product_sn = $this -> _request -> getParam('product_sn', null);
    	if ($number && ($product_sn || $id)) {
    	    $stockAPI = new Admin_Models_API_Stock(); 
    	    if ($id) {
    	        $product = array_shift($this -> _api -> getProduct("a.product_id = '{$id}'"));
    	    }
    	    else {
    	        $product = array_shift($this -> _api -> getProduct("a.product_sn = '{$product_sn}'"));
    	    }

    	    if (!$product) {
    	        exit;
    	    }
    	    $id = $product['product_id'];
    	    $goodsID = $product['goods_id'];
    	    $productSN = $product['product_sn'];
    	    $stockAPI = new Admin_Models_API_Stock();
    	    if (!$stockAPI -> checkPreSaleProductStock($id, $number, true)) {
    	        $stock = $stockAPI -> getSaleProductStock($id,true);
    	        if ($stock['able_number'] <= 0) {
    	            $goodsDB = new Shop_Models_DB_Goods();
    	            $goodsDB -> updateStatus(1, $productSN, $goodsID);
    	        }
    	        exit('对不起，库存不足！');
    	    }
    	   // exit('添加成功');
	    }
	    else {
        	exit('Error!');
        }
        exit;
    }


    /**
     * 获取商品
     *
     * @return void
     */
    public function getProductAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', null);
    	$product_sn = $this -> _request -> getParam('product_sn', null);
    	if ($product_sn || $id) {
	        $where = $id ? "product_id='$id'" : "product_sn='$product_sn'";
	        $r = array_shift($this -> _api -> getProduct($where,"product_id,product_sn,goods_name,market_price,goods_img"));
	        if ($r){
	        	exit(Zend_Json::encode($r));
	        }else{
	        	exit;
	        }
	    }else{
        	exit;
        }
    }

    /**
     * 放商品入暂存架
     *
     * @return void
     */
    public function favoriteAction()
    {
        $goodsId = intval($this -> _request -> getParam('goodsid',0));
        if (!$this -> _auth) {
            $goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/goods/show/id/'.$goodsId);
            $url = "{$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto;
            echo Zend_Json::encode(array('status'=>0,'url'=>$url,'msg'=>'请先登录!'));
            exit();
        } else {
            if ($goodsId) {
                $data = $this -> _api -> checkGoods($goodsId);
                if ($data['goods_id'] && $data['onsale'] == 0) {
                    $this -> _api -> addFavorite($goodsId);
                    echo Zend_Json::encode(array('status'=>1,'msg'=>'收藏成功!'));
                    exit();
                }else{
                	echo Zend_Json::encode(array('status'=>1,'msg'=>'已收藏，无需重复收藏!'));
                	exit();
                }
            }else{
            	echo Zend_Json::encode(array('status'=>0,'msg'=>'参数错误!'));
            	exit();
            } 
        }
        exit;
    }
    /**
     * 删除暂存架中的商品
     *
     * @return void
     */
    public function delFavoriteAction()
    {
        $favoriteId = intval($this -> _request -> getParam('favorite_id',0));
        if ($favoriteId) {
            $this -> _api -> delFavorite($favoriteId);
        }
        header("Location: {$this -> getFrontController() -> getBaseUrl()}/member/favorite/");
        exit;
    }

	/**
     * ajax 搜索
     *
     */
	public function doAjaxSearchAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$keywords = $this -> _request -> getParam('keywords', null);
    	if($keywords!=''){
    		$list = $this -> _api -> doAjaxSearch($keywords);
			if(is_array($list) && count($list)){
	        	$outStr='<div class="ajaxsearchlist" id="ajaxsearchlist"><ul>';
	        	$str='';
	        	$x=1;
    			foreach ($list as $k=>$v){
    				//下拉>13列表太长了
    				if($x>13){
    					break;
    				}
    				//如果此词没搜索到则不显示
    				if($v[0]>0){
			            $str.='<li onmouseover="this.style.backgroundColor=\'#ffffcc\'" onmouseout="this.style.backgroundColor=\'#ffffff\'" onclick="goSearch('.$x.')" class="clear"><span class="ajaxsearchlistright">'.$v[0].'&nbsp;&nbsp;</span>&nbsp;<span class="ajaxsearchlistleft" id="key'.$x.'">'.$k.'</span></li>';
			            $x++;
			            //goods
	    				if(is_array($v[1]) && count($v[1])){
			            	foreach ($v[1] as $vv){
			            		$str.='<li onmouseover="this.style.backgroundColor=\'#ffffcc\'" onmouseout="this.style.backgroundColor=\'#ffffff\'" onclick="goSearch('.$x.')" class="clear"><span class="ajaxsearchlistright"></span>&nbsp;&nbsp;&nbsp;<span class="ajaxsearchlistleft" style="color:#666666;" id="key'.$x.'">'.$vv.'</span></li>';
			            		$x++;
			            	}
			            }
			            //cat
			            if(is_array($v[2]) && count($v[2])){
			            	foreach ($v[2] as $vvv){
			            		$str.='<li onmouseover="this.style.backgroundColor=\'#ffffcc\'" onmouseout="this.style.backgroundColor=\'#ffffff\'" onclick="goGallery('.$vvv['cat_id'].')" class="clear"><span class="ajaxsearchlistright">'.$vvv['ct'].'&nbsp;&nbsp;</span>&nbsp;<span class="ajaxsearchlistleft" id="key'.$x.'">'.$vvv['cat_name'].'</span></li>';
			            		$x++;
			            	}
			            }
    				}
			    }
			    //如果所有词都没搜索到
			    if($str==''){
			    	$str='<li onmouseover="this.style.backgroundColor=\'#ffffcc\'" onmouseout="this.style.backgroundColor=\'#ffffff\'" onclick="goSearch(1)" class="clear"><span class="ajaxsearchlistright">0&nbsp;&nbsp;</span>&nbsp;<span class="ajaxsearchlistleft" id="key1">'.$keywords.'</span></li>';
			    }
			    $str=$outStr.$str.'</ul></div>';
			    echo $str;
		    }else{
		    	echo '';
		    }
    	}else{
    		echo '';
    	}
    	exit;
    }
    
    
    public function sendNoticeAction()
    {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $post = $this->_request->getPost();
        if(!$post['goods_id'])
        {
        	echo Zend_Json::encode(array('status'=>0,'msg'=>'参数错误！'));
        	exit();
        }
        
        if(!Custom_Model_Check::isEmail($post['account']) && !Custom_Model_Check::isMobile($post['account']))
        {
        	echo Zend_Json::encode(array('status'=>0,'msg'=>'请输入正确格式的邮箱或手机！')); 
        	exit();
        }
        $data = array();
        $data['goods_id'] = $post['goods_id'];
        $data['account'] =  $post['account'];
        $data['ctreated'] =  time();
        $data['modified'] =  time();
        $res = $this->_api->sendGoodsNotcie($data);
        echo Zend_Json::encode(array('status'=>$res['isok'],'msg'=>$res['msg']));
        exit();
        
    }
    



	/**
     * 所有分类
     *
     */
    public function allAction()
    {
    $this -> view->css_more=',goodsall.css';



        
    }


    /**
     * 新版判断是否为utf-8
     *
     */
    public function isutf8($word){
    	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true){
    		return true;
    	}else{
    		return false;
    	}
    }
}
