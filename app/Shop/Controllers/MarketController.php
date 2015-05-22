<?php
class MarketController extends Zend_Controller_Action
{
	private $_offerApi;
	public function init()
	{
	  $this->view->css_more=",market.css";
	  $this->view->js_more=",check.js";
	  $this->_offerApi =  new Shop_Models_API_Offers();
	}
	public function nightAction()
	{
		$offerType = array('fixed'=>55,'buy_gift'=>20,'discount'=>28);
		
		$offers_ids = implode(',', array_values($offerType));		 
		$offers = $this->_offerApi->getAllOffers(" AND offers_id IN({$offers_ids}) ");	
	
		//特价秒杀
		$fixedOffer = $offers[$offerType['fixed']];
		$goodsDiscount = array();
	    parse_str($fixedOffer['config']['allDiscount']); //解析 $goodsDiscount
	    if($goodsDiscount){
	    	$pids = array_keys($goodsDiscount);	    	
	    	$products = $this->_offerApi->_goods->getGoodsByIds($pids);
	    	foreach($products as $key=>$val){
	    		if($goodsDiscount[$val['goods_id']])
	    		{
	    			$val['now_price'] = $goodsDiscount[$val['goods_id']];
	    			$products[$key] = $val;
	    		}
	    	}
	    	$this->view->fixedProducts = $products;
	    }	
	    		
	    //买就送
	    $products = array();
	    $this-> view->giftsProducts = $products;
	    
	    //第二件半价        
        $discount = $offers[$offerType['discount']];      
        $goodsDiscount = array();        
        if($discount['config']['allGoods']){
        	$goodsArr = array();
        	foreach ($discount['config']['allGoods'] as $val)
        	{
        		   parse_str($val);
        		   $pids =  array_keys($goodsDiscount);        		 
        		   $goodsArr =  array_merge($goodsArr,$pids);
        	}
        	$goodsArr = array_unique($goodsArr);
        	$products = $this->_offerApi->_goods->getGoodsByIds($goodsArr);
        	$this-> view->discountProducts = $products;
        }
        
   
       
		
	   //倒计时	
	   $start_time = strtotime(date('Y-m-d').' 18:00:00');
	   $end_time =  strtotime(date('Y-m-d',strtotime('+1 days')).'02:00:00');
	   if (time()<$start_time) {
	   	$time = $start_time;
	   	$isStart = false;
	   }elseif (time()>=$start_time)
	   {
	     $time = $end_time;
	     $isStart  = true;
	   }
	   $this->view->offerType = $offerType;
	   $this->view->isStart = $isStart;
	   $this->view->time = $time;
	}
	
	public function noticeAction()
	{
		$post =  $this->getRequest()->getParams();
		if (!$post['goods_id']) {
			echo Zend_Json::encode(array('status'=>0,'msg'=>'参数错误'));
			exit;
		}
		
		if(!Custom_Model_Check::isMobile($post['mobile']))
		{
			echo Zend_Json::encode(array('status'=>0,'msg'=>'手机号格式不正确'));
			exit;
		}
		
		$db = Zend_Registry::get('db');		
		$info = $db->fetchRow("SELECT * FROM shop_market_notice WHERE  mobile='{$post['mobile']}' AND goods_id='{$post['goods_id']}' AND status=0 ");
		if($info){
			echo Zend_Json::encode(array('status'=>0,'msg'=>'你已订阅了提醒信息，无需重复提交！'));
			exit;
		}
	
		
		$data = array();
		$data['mobile'] = trim($post['mobile']);
		$data['goods_id'] = $post['goods_id'];
		$data['goods_name'] = $post['goods_name'];
		$data['offer_id'] = $post['offer_id'];
		$data['add_time'] = time();		
		$last_id = $db->insert('shop_market_notice', $data);
		if($last_id)
		{
			echo Zend_Json::encode(array('status'=>1,'msg'=>'订阅提醒成功！'));
			exit;
		}else{
			echo Zend_Json::encode(array('status'=>0,'msg'=>'提交请求失败！'));
			exit;
		}
		
	}
	
}


