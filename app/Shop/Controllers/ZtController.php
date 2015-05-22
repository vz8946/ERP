<?php
/*
 * Created on 2013-5-9
 *专题模块
 *
 */
 class ZtController extends Zend_Controller_Action {

	protected $_api = null;
	protected $_api_goods = null;
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this ->_api_goods = new Shop_Models_API_Goods();
		$this->_api = new Shop_Models_DB_Topics();
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	}
	/**
     * 专题列表
     *
     * @return void
     */
 	public function indexAction(){

 		$this->view->lists = $this->_api->getZt();
 	}
 	/*
 	 *
 	 * 专题详情
 	 * */
 	public function detailAction(){
		$id = intval($this->getRequest()->getParam("id"));
		$obj = $this->_api->getTopsById($id);
		if(empty($obj)){
			die("error");
		}
		if(!empty($obj["flagUrl"])){
		    $tag_id=intval($obj["flagUrl"]);
			$gooslist = $this ->_api_goods->getTag(" tag_id = ".$tag_id);
			$this->view->gooslist = $gooslist['details'];
		}
		//添加地区联动
		$_addrlist = new Shop_Models_API_Cart();
		$this -> view -> province = $_addrlist -> getArea(array('parent_id' => 1));
		//根据标签读取数据
		$this->view->top = 	$obj;
 	}
 	
 	/**
 	 * 纳普乐专题
 	 */
 	public function nappleAction()
 	{

 		$submitted = $this->getRequest()->getParam('submitted');
 		if($submitted)
 		{
 			
 			$post =  $this->getRequest()->getParams();
 			$data = array();

 			$data['gender'] = $post['gender'];
 			$data['age'] = $post['age'];
 			$data['use_medicine'] = $post['use_medicine'];
 			$data['mobile'] = trim($post['mobile']);
 			$data['name'] = $post['name'];
 			$data['qq'] = $post['qq'];
 			$data['symptom'] = implode('|', $post['symptom']);
 			$data['habit'] = implode('|', $post['habit']);
 			$data['other'] = implode('|', $post['other']); 			
 			
 			$db = Zend_Registry::get('db');
 			
 			$info = $db->fetchRow("SELECT * FROM shop_napple_survey WHERE  mobile={$data['mobile']}"); 			
 			if($info){
 				echo Zend_Json::encode(array('status'=>'y','msg'=>'您已经提交过了，无需重复提交！'));
 				exit;
 			}
 			
 			$last_id = $db->insert('shop_napple_survey', $data);  			
 			if($last_id)
 			{
 				echo Zend_Json::encode(array('status'=>'y','msg'=>'提交成功，谢谢参与调查！'));
 				exit;
 			}else{
 				echo Zend_Json::encode(array('status'=>'n','msg'=>'提交失败'));
 				exit;
 			}
 			
 		} 	

 		//纳普了订单
 		$orderApi = new Admin_Models_DB_Order();
 		 	
 		$orderList = $orderApi->getOrderBathWithPage(array('product_sn'=>'N0119004'),1);
 		if($orderList)
 		{
	 		foreach ($orderList['data'] as $k => $v) {
	 			$inBatchSN[] = $v['batch_sn']; 	
	 			$rand = array_rand(array(1,2,4,6,8),1);
	 			$orderList['data'][$k]['goods_name'] = "纳普乐片剂{$rand}盒";		
	 			$mobile = $v['addr_mobile']?$v['addr_mobile']:$v['addr_tel'];
	 			$orderList['data'][$k]['addr_mobile'] =preg_replace( "/(1\d{1,2})\d\d\d\d/", "\$1****\$3",$mobile);
	 		}	
 		}
 		
 		
 		$this -> view -> orderList = $orderList;
 		//添加地区联动
 		$_addrlist = new Shop_Models_API_Cart();
 		$this -> view -> province = $_addrlist -> getArea(array('parent_id' => 1));
 		
 		$this->view->page_title = '垦丰商城';
 		$this->view->page_keyword = '垦丰商城';
 		$this->view->page_description = '垦丰商城';
 			
 		
 	}
 	
 	
 	/**
 	 * 周年庆
 	 */
 	public function anniversaryAction()
 	{
 		$this->view->page_title = '垦丰商城';
 		$this->view->page_keyword = '垦丰商城';
 		$this->view->page_description = '垦丰商城';
 	}
 	
 	
 }
?>
