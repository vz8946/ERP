<?php
class PageController extends Zend_Controller_Action
{

    /*api*/
    private $pageApi = null;

    /*auth*/
    private $_auth = null;

	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> pageApi = new Purchase_Models_API_Page();
		$this -> _auth = Purchase_Models_API_Auth :: getInstance() -> getAuth();
	}

	/**
     * 站内优惠活动专题列表
     *
     * @return void
     */
	public function actAction()
	{
        $this -> view -> page_title = '活动专区 ';
        $time=time();
        $datas = $this -> pageApi -> getActList(" and status=1 and '$time' >start_time and '$time' < end_time", intval($this -> _request -> getParam('page', 1)));
        $this -> view -> datas = $datas['data'];
        $pageNav = new Custom_Model_PageNav($datas['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
	}

	/**
     * 活动列表
     *
     * @return void
     */

 	public function topicsAction(){
 		
 		$this->view->lists = $this->pageApi->getTopics();
		$this->view->css_more = ",topics.css";
		
        $this -> view -> page_title = "垦丰电商-种子商城最新活动,全球采购,世界同步-垦丰商城";
        $this -> view -> page_keyword = "种子最新活动,进口种子最新信息";
        $this -> view -> page_description = '垦丰商城,可以帮您迅速的了解到种子的最新信息,品质保证。';
		
 	}
 	/*
 	 *
 	 * 专题详情
 	 * */
 	public function detailAction(){

		$id = intval($this->getRequest()->getParam("id"));
		$fb_id = array(74,75,76,79,80);
		if(in_array($id, $fb_id)) header('Location:/');

		$obj = $this->pageApi->getTopsById($id);
		$this -> view -> page_title =$obj['title'];
		$this -> view -> page_keyword =$obj['keyword'];
		$this -> view -> page_description =$obj['desc'];
		if(empty($obj)){
			die("error");
		}
		
		
		if(!empty($obj["flagUrl"])){
		    $tag_id=intval($obj["flagUrl"]);
			$gooslist = $this ->_api_goods->getTag(" tag_id = ".$tag_id);
			$this->view->gooslist = $gooslist['details'];
		}
		//添加地区联动
		$_addrlist = new Purchase_Models_API_Cart();
		$this -> view -> province = $_addrlist -> getArea(array('parent_id' => 1));
		//根据标签读取数据
		$this->view->top = 	$obj;
		$this->view->css_more = ",promotion.css";
 	}


	/**
     * 促销专题
     *
     * @return void
     */
	public function specialAction()
	{
		$this->view->is_login = 'N';
		if(!empty($_SESSION['User']['userCertification'])){
			$this->view->is_login = 'Y';			
		}
		
		$this -> view -> cur_place = 'special';
        $event =trim($this -> _request -> getParam('event', null));

		switch ($event) {
			case 'qixi':
				$this -> view -> page_title = '七夕专场 - 垦丰商城！';
			break;
			case 'qixixfg':
				$this -> view -> page_title = '促销专场 - 垦丰商城！';
			break;
			case 'free':
				$this -> view -> page_title = '谁说天下没有免费的午餐，全场0元！';
			break;
			case 'qg411':
				$this -> view -> page_title = '谁说天下没有免费的午餐，全场0元！';
			break;
			case '5':
				header('location:/');
			break;
			case '2':
				
				$rn = rand(0, 2);
				$arr_quan10 = array(
					array('sn'=>'C9RYVURA78','pwd'=>'53985kpq'),
					array('sn'=>'C9ZQXYXA79','pwd'=>'jf9z5ekc'),
					array('sn'=>'CY8TQZZA7A','pwd'=>'j398jkpj'),
				);
				
				$arr_quan30 = array(
					array('sn'=>'CXZRYRSA7B','pwd'=>'jvyzjkke'),
					array('sn'=>'CXRZRUVA7C','pwd'=>'j3xzjepv'),
					array('sn'=>'C9ZRYUUA7D','pwd'=>'jf9zjep8'),
				);
				
				$arr_quan50 = array(
					array('sn'=>'C9RWUURA7E','pwd'=>'j3y85k3d'),
					array('sn'=>'C9ZRSZSA7F','pwd'=>'j39r5evy'),
					array('sn'=>'CY8SSYUA7G','pwd'=>'jf9rjk36'),
				);
				
				$this->view->quan10 = $arr_quan10[$rn];
				$this->view->quan30 = $arr_quan30[$rn];
				$this->view->quan50 = $arr_quan50[$rn];
								
			break;
			default:
			    $this -> view -> page_title = '垦丰商城-活动专题';
		}
		
		$this -> render('special/'.$event);
	}


	/**
     * 信息
     *
     * @return void
     */
	public function infoAction()
	{
		$_api = new Purchase_Models_API_Page();
		$id = (int)$this -> _request -> getParam('id', 0);
		$info = array_shift($_api -> getInfo(" is_view = 1 and article_id='$id'"));
		!$info && exit('error');
        $info['cat_id'] && $this -> view -> menu = $_api -> getInfo("a.cat_id={$info['cat_id']}");
		$this -> view -> info = $info;
		$this -> view -> page_title = $info['title'];
		$this -> view -> ur_here = " <a href='/'>首页</a> <code>&gt;</code> ".$info['cat_name']." <code>&gt;</code>". $info['title'] ;
	}

}