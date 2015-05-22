<?php
class IndexController extends Zend_Controller_Action
{
	protected $_apiGoods;
	protected $_authUser;
	private $_adv_api;

	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
        $auth = Shop_Models_API_Auth :: getInstance();
		$this -> _authUser = $auth -> getAuth();
        $this -> _apiGoods = new Shop_Models_API_Goods();
        $this->_adv_api = new Shop_Models_API_News();
	}
		
	/**
     * 浏览记录
     *
     * @return void
     */
	function historyGoodsAction()
	{
	    $data = $this -> _apiGoods -> getHistory();
	    if ( $data ) {
	        for ( $i = 0; $i < count($data); $i++ ) {
	            if ($i>2)   break;
	            $alldata[] = $data[$i];
	        }
	        $this -> view -> historydatas = $alldata;
	    }
	    else    $this -> view -> isEmpty = 1;
	    echo $this->view->render('_library/history-goods.tpl');
        exit;
	}

	/**
     * 清空浏览记录
     *
     * @return void
     */
	function emptyHistoryGoodsAction()
	{
	    $this -> _apiGoods -> emptyHistory();
	    $this -> historyGoodsAction();
	}

	/**
     * 首页
     *
     * @return void
     */
	public function indexAction()
	{
		//商城首页评论
		$msgAPI = new Shop_Models_API_Msg();
		$comlist = $msgAPI->getCommByIndex(10);
		$this -> view -> comlist = $comlist;
		$newsApi = new Shop_Models_API_News();		
		$pageApi = new Shop_Models_API_Page();
		//网站公告
		$noticeInfo = $pageApi->getArtByCat(34,8);
		//促销信息		
		$saleInfo = $pageApi->getArtByCat(35,8);
		
		$this->view->noticeInfo = $noticeInfo;
		$this->view->saleInfo  = $saleInfo;
		
		$articlesList = $newsApi->getArticlesByNews(14);				
		$this->view->articlesList= $articlesList;		
		$this->view->is_index_page = true;		
        $this->view->css_more=',index.css,drt.css';
        $this -> view -> page_title = "垦丰种业商城-种子，种业商城";
        $this -> view -> page_keyword = "买种子，种子商城，种业";
        $this -> view -> page_description = '垦丰种业商城-种子，种业商城';

	}

	/**
     * 我的收藏
     *
     * @return void
     */
    public function favoriteAction()
    {
		$this -> _apiMember = new Shop_Models_API_Member();
		$this -> view -> user = $this -> _authUser;
		if ( $this -> _authUser ) {
    		$favorite = $this -> _apiMember -> getFavorite(1, 3);
    		$this -> view -> datas = $favorite['info'];
		}
		echo $this->view->render('_library/favorite.tpl');
        exit;
    }
    /**
     * 我的购物车
     *
     * @return void
     */
    public function cartAction()
    {
        $this -> _apiCart = new Shop_Models_API_Cart();
        $data = $this -> _apiCart -> getCartProduct();

		$this -> view -> data = $data['data'];
        $this-> view -> offers =  $data['offers'];
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
        $type =  $this -> _request -> getParam('type', 'top');
		if ($type == 'top') {

        	$html =  $this->view->render('_library/cart.tpl');
        }else{
        	$html =  $this->view->render('_library/cart_tips.tpl');
        }

        echo Zend_Json::encode(array('status'=>1,'html'=>$html,'number'=>$data['number']));
        exit;        
    }
    /**
     * 获得购物车商品数量
     *
     * @return void
     */
    public function getCartGoodsNumberAction()
    {
    	$this -> _apiCart = new Shop_Models_API_Cart();
    	$cart = $this -> _apiCart -> makeCartGoodsToArray();
	    if ( count($cart) > 0 ) {
            $_COOKIE['cart'] = $this -> _apiCart -> makeCartGoodsToString($cart);
            $data = $this -> _apiCart -> getCartProduct($cart);
	    }
	    echo $data['number'] ? $data['number'] : 0;
	    exit;
    }
    /**
     * 删除购物车商品
     *
     * @return void
     */
    public function delCartGoodsAction()
    {
        $this -> _apiCart = new Shop_Models_API_Cart();
        $cart = $this -> _apiCart -> makeCartGoodsToArray();
        $productID = intval($this -> _request -> getParam('product_id', null));
        $number = intval($this -> _request -> getParam('number', 0));
        $this -> _apiCart -> del($productID, $number);
        if ($_SESSION['Card']) {
           unset($_SESSION['Card']);
        }
        if ( $cart ) {
            foreach ( $cart as $product_id => $produce_number) {
                if ( ($product_id == $productID) ) {
                    $cart[$product_id] -= $number;
                    if ( $cart[$product_id] <= 0 ) {
                        unset($cart[$product_id]);
                    }
                }
            }
        }
        $data = $this -> _apiCart -> getCartProduct($cart);

        //start:删除gift中的赠品
        $tmp = $_COOKIE['gift'];
        if($tmp!==NULL){
	        $tmp = explode(',', $tmp);
	        foreach ($tmp as $k => $v){
	        	if(strpos($v, $productID."_")>-1){
	        		unset($tmp[$k]);
	        	}
	        }
	        setcookie('gift', implode(',', $tmp), time () + 86400 * 365, '/');
        }
        //end:删除gift中的赠品
        $this -> view -> data = $data['data'];
        $this -> view -> number = $data['number'] ? $data['number'] : 0;
        $this -> view -> amount = $data['amount'] ? $data['amount'] : 0;
        echo $this->view->render('_library/cart.tpl');
        echo ']::[';
        echo $data['number'] ? $data['number'] : 0;
        exit;
    }

    /*
     * 爆款处理
     *
     *
     */
     public function baokuanAction(){
     	$pidcode = $this->getRequest()->getParam("pidcode");
     	if(empty($pidcode)) $pidcode = "";
     	$this -> view -> pidcode = $pidcode;
     	$decapi = new Admin_Models_API_Decoration();
     	$catelist = $decapi->getAllCate(4);
		$this -> view -> catelist = $catelist;
		$baokuanlist = $decapi->getByType("baokuan",$pidcode);
		$this->view->baokuanlist = $baokuanlist;
		
		$this->view->css_more = ',baokuan.css';
     	$this -> view -> cur_place = 'baokuan';

        $this -> view -> page_title = "热卖产品-网上种子商城,种子商城-垦丰商城";
        $this -> view -> page_keyword = "垦丰电商,种子商城,网上买种子";
        $this -> view -> page_description = '垦丰电商专业的种子商城,热卖产品全部有货,品质保证！赶紧抢购吧。 ';


     }
     /*
      * 促销中心
      */
     public function promAction(){
        
     	$pidcode = $this->getRequest()->getParam("pidcode");
     	if(empty($pidcode)) $pidcode = "";
     	$this -> view -> pidcode = $pidcode;
     	$decapi = new Admin_Models_API_Decoration();
     	$catelist = $decapi->getAllCate(5);
		$this -> view -> catelist = $catelist;
		$baokuanlist = $decapi->getByType("prom",$pidcode);
		$this->view->baokuanlist = $baokuanlist;

     	//获取广告位
     	$advlist = $this->_adv_api->getAdvyPosition("PROMBANNER",6);
     	$advRight = $this->_adv_api->getAdvyPosition("PROMBANNERRIGHT",1);
     	$this -> view -> advlist = $advlist;
     	$this -> view -> advRight = $advRight[0];
     	$this -> view -> cur_place = 'prom';


        $this -> view -> page_title = "垦丰电商-种子商城最新活动,全球采购,世界同步-垦丰商城";
        $this -> view -> page_keyword = "种子最新活动,进口种子最新信息";
        $this -> view -> page_description = '垦丰商城,可以帮您迅速的了解到种子的最新信息,品质保证,欢迎购买。 ';


     }

      /*
      *巨便宜
      */
     public function jpyAction(){
     	
		//巨便宜
		$datas= $this -> _apiGoods -> getTag("tag_id=14",1,15);
		
		
		$this->view->css_more = ',giantCheap.css';
        
        /* foreach ($datas['details'] as $k => $v) {
        	
            if(empty($v['goods_id'])) continue;
			$datas['details'][$k]['disprice'] = sprintf("%.2f",(floatval($v['market_price']) - floatval($v['price'])));
			
        } */
        $this->view->jpy= $datas['details'];
		
        $this -> view -> page_title = "垦丰电商,种子-垦丰商城";
        $this -> view -> page_keyword = "种子";
        $this -> view -> page_description = '垦丰商城,可品质保证,欢迎选购。 ';




     }

    public function brandAction () {
        $this->view->list_banner = $this->_adv_api->getAdvyPosition('BRANDINDBAR', 5);
        $objBrand = new Shop_Models_API_Brand();
        $this->view->list_brand = $objBrand->getBrandCityList();
        $objNew = new Shop_Models_API_News();
        $banner_left = $objNew->getAdvyPosition('ADVBRANDLEFT', 1);
        $this->view->banner_left = $banner_left[0];
        $this->view->list_banner_right = $objNew->getAdvyPosition('ADVBRANDRIGHT', 12);
        $this->view->css_more = ',brand-index.css'; 


        $this -> view -> page_title = "垦丰电商-品牌荟,种子品牌荟-最值得信赖的种子商城-垦丰商城";
        $this -> view -> page_keyword = "种子品牌荟,进口种子品牌荟";
        $this -> view -> page_description = '国内最具人气的种子品牌荟,众多知名品牌进口种子精品供您挑选,在垦丰商城你可以获得种子达人分享的购物心得以及购物乐趣,解决您的困惑与烦恼。垦丰商城，品质保证';


    }

     /*正品保证*/
     public function zpAction(){
     	$this -> view -> cur_place = 'zp';
     	$this -> view -> page_title = "垦丰商城";
     	$this -> view -> page_keyword = "垦丰商城";
     	$this -> view -> page_description = '垦丰商城,种子';
     	
     	
     }
	 
     /*正品保证*/
     public function zpbzAction(){
     	$this -> view -> cur_place = 'zpbz';
     	$this -> view -> page_title = "垦丰商城";
     	$this -> view -> page_keyword = "垦丰商城";
     	$this -> view -> page_description = '垦丰商城';
     }
	 
	 public function tuanAction(){
	    Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	 	$api_offers = new Shop_Models_API_Offers();	
		$this->view->qg = $t = $api_offers->getThisWeek();
		$this->view->tqg = $api_offers->getNextWeek();
		$this->view->totime = $totime = (strtotime($t['to_date'])-mktime());
		$this->view->is_clock = ($totime<(3*24*3600)) ? true : false;
		
		$ct = mktime();
		$t1 = 7-date('w',$ct)-1;
		$t1 = $t1 == 0 ? 7 : $t1;
		$cweek_last_date = date('Y-m-d',$ct+($t1*24*3600));
		$cweek_last_datetime = strtotime($cweek_last_date.' 00:00:00');
		
		$tmw_date = $cweek_last_datetime;
		$tmw_tmw_date = $tmw_date+(7*24*3600);
		
		$this->view->tip_tmw_date = date('m月d日',$tmw_date).' - '.date('m月d日',$tmw_tmw_date);
		
	 }
	 
	 public function comnBannerAction(){
		if(empty($_SESSION['index_banner_showed'])){
			$_SESSION['index_banner_showed'] = true;				
		}else{
			$this->view->index_banner_show = $_SESSION['index_banner_showed'];
		}
	 }
	 
	 public function dingyueAction(){
			
	 	$email = $this->_request->getParam('send_mail');
		if(empty($email)) Custom_Model_Tools::ejd('fail','Email不能为空！');
		if(!Custom_Model_Check::isEmail($email)) Custom_Model_Tools::ejd('fail','请填入正确的Email格式！');
		
		$api = new Shop_Models_API_Send();
		$flag = $api->bookQGMail($email);
		if($flag == 'error-repeat-book')  Custom_Model_Tools::ejd('fail','请不要重复订阅！');
		Custom_Model_Tools::ejd('succ','订阅成功，请注意查收您 所订阅的邮件！');
	 }
}