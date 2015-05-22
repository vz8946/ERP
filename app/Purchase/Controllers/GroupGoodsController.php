<?php

class GroupGoodsController extends Zend_Controller_Action
{
    /**
     * 
     * @var Purchase_Models_API_GroupGoods
     */
    private $_api = null;
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init()
	{
		$this -> _api = new Purchase_Models_API_GroupGoods();
	}

	/**
     * 组合商品列表页面
     *
     * @return   void
     */
    public function indexAction()
    {
		$search = Custom_Model_DeepTreat::filterArray($this -> _request -> getParams(), 'strip_tags');
        $search = Custom_Model_DeepTreat::filterArray($search, 'htmlspecialchars');

    	$page = (int)$this -> _request -> getParam('page', 1);
		$page = ($page <= 0) ? 1 : $page;
		$datas = $this -> _api -> get($search, '*', $page, 20);
		$this -> view -> datas = $datas;
		$total = $this -> _api -> getCount();
		$pageNav = new Custom_Model_PageNav($total, 20);
		$this -> view -> pageNav = $pageNav -> getPageNavigation();

	    $this -> view -> page_title = "垦丰电商 -专业种子产品商城";
		$this -> view -> page_keyword = "垦丰电商 ,种子,玉米,水稻,大豆,小麦,大麦,甜菜,其他";
	    $this -> view -> page_description ="垦丰电商 -专业的种子产品商城，品质保证!";
    }

    /**
     * 商品详情页面
     *
     * @return void
     */
    public function showAction()
    {
        $id = intval($this -> _request -> getParam('group_id', 0));
        $this -> view -> data = $this -> _api -> getOne($id);
		
        if(!$this -> view -> data){
        	header("Location:/group-goods/index");
        }

        //小导航
        $this -> view -> navi = '<a href="/">首页</a> > <a href="/group-goods">组合套装</a> > '.$this -> view -> data['group_goods_name'];
        //links

        //组合套装内的商品
        $group_goods = $this -> _api -> fetchConfigGoods(array('group_id'=>$id));
        foreach ($group_goods as $k=>$v){
            if(empty($v['goods_id'])){
                unset($group_goods[$k]);
            }else{
                $group_goods[$k]['product_id'] = $v['goods_id'];
            } 
        }
        
        $chkOnsale = $this -> _api -> checkOnsaleStatus(array('configs'=>$group_goods, 'group_id'=>$id));
        if($chkOnsale != true){
        	//header("Location:/group-goods/index");exit;
        }

        if(count($group_goods)>1){
            $this -> view -> showtype = 1;
            $this -> view -> gg = $group_goods;
        }else{
            $this -> view -> showtype = 2;
            $_goodsApi = new Purchase_Models_API_Goods();
            $bfdshowgoods = $_goodsApi -> getTag(" tag_id = 22");
            $this -> view -> linkgoods = $bfdshowgoods['details'];
        }

	    $this -> view -> page_title = "垦丰商城 - 世界种子精品商城";
		$this -> view -> page_keyword = "垦丰电商,种子,玉米,水稻,大豆,小麦,大麦,甜菜,其他";
	    $this -> view -> page_description ="垦丰商城 -专业的种子产品商城，品质保证！";
    }
    /**
     * 检查库存  &&  生成组合商品 cookie:groupgoods
     *
     * @return text
     */
    public function checkAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$group_id = $this -> _request -> getParam('group_id', 0);

    	//$number和$sign是从/flow/index 发送过来的
    	$number = $this -> _request -> getParam('number', 1);
    	if($number<1 || $number>20){exit('请输入1-20间的整数');}

    	if($group_id>0){
    		$status = $this -> _api -> checkStatus($group_id);
    		if(!$status){
    			exit('此组合商品已经下降！');
    		}
    		//写入cookie:groupgoods
    		$st = $this -> _api -> writeToCookie($group_id,$number);
    		if($st){
    			exit;
    		}else{
    			exit('加入购物车失败');
    		}
    	}else{
        	exit('Error!');
        }
    }

    /**
     * 删除购物车中的指定组合商品
     *
     * @param int $gorup_id
     *
     * @return string
     */
    public function delAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$group_id = (int)$this -> _request -> getParam('group_id', 0);
    	if($group_id < 1){
    		exit('删除失败');
    	}
    	$st = $this ->_api -> delOneFromCookie($group_id);
    	exit($st);
    }

    /**
     * 组合商品评论
     *
     */
    public function commentAction() {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    	$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
        $id = (int)$this -> _request -> getParam('group_id', null);
        if($id){
			$where = array('status'=>1, 'type'=>1, 'group_goods_id'=>$id);
			$datas = $this -> _api -> getGroupGoodsMsg($where, '*', null, $page, 5);
			$this -> view -> datas = $datas['datas'];
			$this -> view -> total = $datas['tot'];
			if ($this -> _api -> checkIp()) {
				$this -> view -> office = true;
			}
			$pageNav = new Custom_Model_PageNavJS($datas['tot'], 5);
            $this -> view -> pageNav = $pageNav -> getPageNavigation('getGroupGoodsCommentList(%%page%%)');
        }
    }

    /**
     * 添加评论
     *
     */
    public function commentAddAction() {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _helper -> viewRenderer -> setNoRender();
		if ($this -> _request -> isPost()){
        	$post = $this -> _request -> getPost();
        	$result = $this -> _api -> commentAdd($post);
        	if($result){
        	    exit($result);
        	}
        }else{
        	exit('error');
        }
    }
}
