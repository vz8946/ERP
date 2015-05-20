<?php

class Admin_UnionAffiliateController extends Zend_Controller_Action 
{
	/**
     * 联盟 API
     * @var Admin_Models_API_UnionAffiliate
     */
    private $_union = null;
    
    /**
     * 联盟配置文件
     * 
     * @var    string
     */
 	private $_unionConfigFile = 'config/union.xml';
    
    /**
     * 性别
     * 
     * @var array
     */
    private $_sex = array(1 => '男', 2 => '女');
    
    /**
     * 分成类型
     * 
     * @var array
     */
    private $_affiliateType = array('1' => '点击分成', '2' => '注册分成');
    
    /**
     * 领款方式
     * 
     * @var array
     */
    private $_getMoneyType = array('1' => '线上领款', '2' => '手工结算');
    
    /**
     * 订单状态
     * 
     * @var array
     */
	private $_orderStatus = array('正常单', '取消单', '无效单');
	
	/**
     * 发货状态
     * 
     * @var array
     */
	private $_orderStatusLogistic = array('未确认', '已确认待收款', '待发货', '已发货', '已签收', '待退货', '退货已签收', '等待父单退货签收', '缺货挂起');
	
	/**
     * 订单类型
     * 
     * @var array
     */
	private $_orderStatusReturn = array('正常单', '退货单', '换货单', '退换货单');
	
	/**
     * 分成记录成功
     * 
     * @var string
     */
	const AFFILIATE_SUCESS = '分成记录成功!';
	
	/**
     * 找不到用户
     * 
     * @var string
     */
	const NO_USER = '无此用户!';
	
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _union = new Admin_Models_API_UnionAffiliate();
	}
    
    /**
     * 打款联盟列表
     *
     * @return void
     */
    public function indexAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $unions = $this -> _union -> getPayList($this -> _request -> getParams(),$page,20,0);
        $this -> view -> param = $this -> _request -> getParams();
        if ($unions['content']) {
        	$unionConfig = Custom_Config_Xml::loadXml(Zend_Registry::get('systemRoot') . '/' . $this -> _unionConfigFile);
        	foreach ($unions['content'] as $key => $union)
        	{
        		$unions['content'][$key]['payLimit'] = $unionConfig -> $unionType -> payLimit;
        	}
        }
        
        $this -> view -> payList = $unions['content'];
        $pageNav = new Custom_Model_PageNav($unions['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * CPA打款列表
     *
     * @return void
     */
    public function clistAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $unions = $this -> _union -> getPayList($this -> _request -> getParams(),$page,20,1);
        $this -> view -> param = $this -> _request -> getParams();
        if ($unions['content']) {
        	$unionConfig = new Zend_Config_Xml(Zend_Registry::get('systemRoot') . '/' . $this -> _unionConfigFile);
        	foreach ($unions['content'] as $key => $union)
        	{
        		$unions['content'][$key]['payLimit'] = $unionConfig -> $unionType -> payLimit;
        	}
        }
        $this -> view -> payList = $unions['content'];
        $pageNav = new Custom_Model_PageNav($unions['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    

    /**
     * 查看联盟信息
     *
     * @return void
     */
    public function viewUnionAction()
    {
    	$id = (int)$this -> _request -> getParam('user_id', 0);
    	$union = $this -> _union -> getUnionById($id);
    	if ($union) {
    		$union['sex'] = $this -> _sex[$union['sex']];
    		$union['affiliate_type'] = $this -> _affiliateType[$union['affiliate_type']];
    		$union['get_money_type'] = $this -> _getMoneyType[$union['get_money_type']];
    	}
    	$this -> view -> union = $union;
    }
    
    /**
     * 查看订单分成信息
     *
     * @return void
     */
    public function viewAffiliateAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
    	$userId = (int)$this -> _request -> getParam('user_id', 0);
    	$affiliateList = $this -> _union -> getAffiliateByUid($userId, $page);
    	
    	if ($affiliateList['content']) {
    		foreach ($affiliateList['content'] as $key => $affiliate)
    		{
    			$affiliateList['content'][$key]['add_time'] = date('Y-m-d H:i', $affiliate['add_time']);
    		}
    	}
    	$this -> view -> affiliateList = $affiliateList['content'];
    	$pageNav = new Custom_Model_PageNav($affiliateList['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> userName = $this -> _request -> getParam('user_name');
    }
    
    /**
     * 查看CPA订单分成信息
     *
     * @return void
     */
    public function cpaViewAffiliateAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
    	$userId = (int)$this -> _request -> getParam('user_id', 0);
    	$affiliateList = $this -> _union -> getCpaAffiliateByUid($userId, $page);
    	
    	if ($affiliateList['content']) {
    		foreach ($affiliateList['content'] as $key => $affiliate)
    		{
    			$affiliateList['content'][$key]['add_time'] = date('Y-m-d H:i', $affiliate['add_time']);
    		}
    	}
    	$this -> view -> affiliateList = $affiliateList['content'];
    	$pageNav = new Custom_Model_PageNav($affiliateList['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> userName = $this -> _request -> getParam('user_name');
    }

    /**
     * 查看订单分成历史信息
     *
     * @return void
     */
    public function viewAffiliateLogAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
    	$orderId = (int)$this -> _request -> getParam('order_id', 0);
    	$affiliateLogList = $this -> _union -> getAffiliateLogById($orderId, $page);
    	
    	if ($affiliateLogList['content']) {
    		foreach ($affiliateLogList['content'] as $key => $affiliateLog)
    		{
    			$affiliateLogList['content'][$key]['order_status'] = $this -> _orderStatus[$affiliateLog['order_status']];
    			$affiliateLogList['content'][$key]['order_status_logistic'] = $this -> _orderStatusLogistic[$affiliateLog['order_status_logistic']];
    			$affiliateLogList['content'][$key]['order_status_return'] = $this -> _orderStatusReturn[$affiliateLog['order_status_return']];
    			$affiliateLogList['content'][$key]['add_time'] = date('Y-m-d H:i', $affiliateLog['add_time']);
    		}
    	}
    	$this -> view -> affiliateLogList = $affiliateLogList['content'];
    	$pageNav = new Custom_Model_PageNav($affiliateLogList['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> orderSn = $this -> _request -> getParam('order_sn');
        $this -> view -> userId = $this -> _request -> getParam('user_id');
        $this -> view -> userName = $this -> _request -> getParam('user_name');
    }
    
    /**
     * 设置订单分成状态
     * 
     * @return void
     */
    public function separateOrderAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $affiliateId = $this -> _request -> getParam('affiliate_id', null);
        $value = $this -> _request -> getParam('value', null);
        $msg = $this -> _request -> getParam('msg', null);
        if(!empty($affiliateId) && !is_null($value)){
	        $result = $this -> _union -> setOrderAffiliate($affiliateId, $value, $msg);
	        if ($result > 0) {
	        	exit('ok');
	        }
        }
    }
    
    /**
     * 已取消分成订单列表
     * 
     * @return void
     */
    public function noSeparateAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
    	$orderList = $this -> _union -> getNoSeparateOrder($page);
    	if ($orderList['content']) {
    		foreach ($orderList['content'] as $key => $affiliate)
    		{
    			$orderList['content'][$key]['add_time'] = date('Y-m-d H:i', $affiliate['add_time']);
    		}
    	}
    	$this -> view -> orderList = $orderList['content'];
    	$pageNav = new Custom_Model_PageNav($orderList['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 分成记录
     *
     * @return void
     */
    public function affiliateAction()
    {
    	 if ($this -> _request -> isPost()) {
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$result = $this -> _union -> affiliate($this -> _request -> getPost(), '0');
        	switch ($result) {
        		case 'addAffiliateSucess':
        		    Custom_Model_Message::showMessage(self::AFFILIATE_SUCESS, '', 1250, "Gurl()");
        		    break;
        		case 'noUser':
        		    Custom_Model_Message::showMessage(self::NO_USER);
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!');
        	}
        } else {
    	    $id = (int)$this -> _request -> getParam('user_id', 0);
    	    $unionType = (int)$this -> _request -> getParam('type', '0');
    	    $union = $this -> _union -> getUnionById($id);
    	    
    	    if ($union) {
    	    	$this -> view -> getMoneyType = ($union['get_money_type']) ? $union['get_money_type'] : 1;
    		    $union['sex'] = $this -> _sex[$union['sex']];
    		    $union['affiliate_type'] = $this -> _affiliateType[$union['affiliate_type']];
    		    $union['get_money_type'] = $this -> _getMoneyType[$union['get_money_type']];
    	    }
    	    $this -> view -> union = $union;
    	    $this -> view -> orderNum = $this -> _request -> getParam('order_num');
    	    $this -> view -> orderPrice = $this -> _request -> getParam('order_price');
    	    $this -> view -> orderPriceGoods = $this -> _request -> getParam('order_price_goods');
    	    $this -> view -> orderAffiliateAmount = $this -> _request -> getParam('order_affiliate_amount');
    	    $this -> view -> affiliateMoney = $this -> _request -> getParam('affiliate_money');
    	    $unionConfig = new Zend_Config_Xml(Zend_Registry::get('systemRoot') . '/' . $this -> _unionConfigFile);
    	    $this -> view -> payLimit = $unionConfig -> $unionType -> payLimit;
        }
    }
    /**
     * CPA分成记录
     *
     * @return void
     */
    public function cpaAffiliateAction()
    {
    	 if ($this -> _request -> isPost()) {
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$result = $this -> _union -> affiliate($this -> _request -> getPost(), '1');
        	switch ($result) {
        		case 'addAffiliateSucess':
        		    Custom_Model_Message::showMessage(self::AFFILIATE_SUCESS, '', 1250, "Gurl()");
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!');
        	}
        } else {
    	    $id = (int)$this -> _request -> getParam('user_id', 0);
    	    $unionType = (int)$this -> _request -> getParam('type', 0);
    	    $union = $this -> _union -> getUnionById($id);
    	    
    	    if ($union) {
    	    	$this -> view -> getMoneyType = ($union['get_money_type']) ? $union['get_money_type'] : 1;
    		    $union['sex'] = $this -> _sex[$union['sex']];
    		    $union['affiliate_type'] = $this -> _affiliateType[$union['affiliate_type']];
    		    $union['get_money_type'] = $this -> _getMoneyType[$union['get_money_type']];
    	    }
    	    $this -> view -> union = $union;
    	    $this -> view -> orderNum = $this -> _request -> getParam('order_num');
    	    $this -> view -> orderPrice = $this -> _request -> getParam('order_price');
    	    $this -> view -> orderPriceGoods = $this -> _request -> getParam('order_price_goods');
    	    $this -> view -> orderAffiliateAmount = $this -> _request -> getParam('order_affiliate_amount');
    	    $this -> view -> affiliateMoney = $this -> _request -> getParam('affiliate_money');
    	    $unionConfig = Custom_Config_Xml::loadXml(Zend_Registry::get('systemRoot') . '/' . $this -> _unionConfigFile);
    	    $this -> view -> payLimit = $unionConfig -> $unionType -> payLimit;
        }
    }
    

    /**
     * 已分成列表
     *
     * @return void
     */
    public function separateAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
    	$payList = $this -> _union -> getSeparate(null, $page);
    	
    	if ($payList['content']) {
    		foreach ($payList['content'] as $key => $pay)
    		{
    			$payList['content'][$key]['get_money_type'] = $this -> _getMoneyType[$pay['get_money_type']];
    			$payList['content'][$key]['add_time'] = date('Y-m-d H:i', $pay['add_time']);
    		}
    	}
    	$this -> view -> payList = $payList['content'];
    	$pageNav = new Custom_Model_PageNav($payList['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 查看已分成详细信息
     *
     * @return void
     */
    public function viewSeparateAction()
    {
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$pay = $this -> _union -> getSeparateById($id);
    	
    	if ($pay) {
    		$pay['add_time'] = date('Y-m-d H:i', $pay['add_time']);
    		$pay['get_money_type'] = $this -> _getMoneyType[$pay['get_money_type']];
    	}
    	$this -> view -> pay = $pay;
    }
}