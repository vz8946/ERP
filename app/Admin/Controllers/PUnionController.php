<?php

class Admin_PUnionController extends Zend_Controller_Action 
{
	/**
     * 推广联盟 API
     * @var Admin_Models_API_PUnion
     */
    private $_pUnion = null;
    
    /**
     * 联盟配置文件
     * 
     * @var    string
     */
 	private $_unionConfigFile = 'config/union.xml';
 	
 	/**
     * 推广联盟配置信息
     * 
     * @var    Custom_Config_Xml
     */
 	private $_pUnionConfig = null;
    
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
    private $_affiliateType = array('1' => 'cps');
    
    /**
     * 领款方式
     * 
     * @var array
     */
    private $_getMoneyType = array('1' => '线上领款', '2' => '手工结算');
    
    /**
     * 未填写用户名
     */
	const NO_USERNAME = '请填写用户名!';
	
	/**
     * 密码不一致
     */
	const NO_SAME_PASSWORD = '输入的密码不一致!';
	
	/**
     * 密码为空
     */
	const NO_PASSWORD = '密码及重复密码不能为空!';
	
	/**
     * 添加推广联盟成功
     */
	const ADD_PUNION_SUCESS = '添加推广联盟成功!';
	
	/**
     * 编辑推广联盟信息成功
     */
	const EDIT_PUNION_SUCESS = '编辑推广联盟成功!';
	
	/**
     * 用户已存在
     */
	const USER_EXISTS = '该用户已存在!';
	
	/**
     * 推广联盟已存在
     */
	const PUNION_EXISTS = '该推广联盟已存在!';
	
	/**
     * 推广联盟不存在
     */
	const PUNION_NO_EXISTS = '该推广联盟不存在!';
	
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _pUnion = new Admin_Models_API_PUnion();
		$this -> _pUnionConfig = Custom_Config_Xml::loadXml(Zend_Registry::get('systemRoot') . '/' . $this -> _unionConfigFile, 'pUnion');
		$this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
	}
    
    /**
     * 推广联盟概况列表
     *
     * @return void
     */
    public function indexAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $pUnions = $this -> _pUnion -> getPUnionBySearch($this -> _request -> getParams(), $page);
        $pUnionAllData = $this -> _pUnion -> countpUnionAllData($this -> _request -> getParams());
  
        if ($pUnions['content']) {
        	foreach ($pUnions['content'] as $num => $pUnion)
            {
            	$pUnions['content'][$num]['add_time'] = ($pUnions['content'][$num]['add_time'] > 0) ? date('Y-m-d', $pUnions['content'][$num]['add_time']) : '';
        	    $pUnions['content'][$num]['status'] = $this -> _pUnion -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $pUnions['content'][$num]['union_normal_id'], $pUnions['content'][$num]['status']);
            }
        }
        $this -> view -> pUnionList = $pUnions['content'];
		$this -> view -> pUnionAllData = $pUnionAllData;
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($pUnions['total'], null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 添加推广联盟
     *
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$result = $this -> _pUnion -> editPUnion($this -> _request -> getPost());
        	switch ($result) {
        		case 'noUserName':
        		    Custom_Model_Message::showMessage(self::NO_USERNAME);
        		    break;
        		case 'noSamePassword':
        		    Custom_Model_Message::showMessage(self::NO_SAME_PASSWORD);
        		    break;
        		case 'noPassword':
        		    Custom_Model_Message::showMessage(self::NO_PASSWORD);
        		    break;
        		case 'pUnionExists':
        		    Custom_Model_Message::showMessage(self::PUNION_EXISTS);
        		    break;
        		case 'addPUnionSucess':
        		    Custom_Model_Message::showMessage(self::ADD_PUNION_SUCESS, 'event', 1250, "Gurl()");
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, "Gurl()");
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> view -> title = '添加推广联盟';
        	$this -> view -> sexRadios = $this -> _sex;
        	$this -> view -> affiliateTypeRadios = $this -> _affiliateType;
        	$this -> view -> getMoneyTypeRadios = $this -> _getMoneyType;
        	$this -> view -> pUnion = array('affiliate_type' => 1, 'get_money_type' => 1, 'proportion' => $this -> _pUnionConfig -> proportion);
        	$this -> render('edit');
        }
    }

    /**
     * 编辑推广联盟
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
        	
            if ($this -> _request -> isPost()) {
            	$this -> _helper -> viewRenderer -> setNoRender();
                $result = $this -> _pUnion -> editPUnion($this -> _request -> getPost(), $id);
                
                switch ($result) {
        		    case 'noUserName':
        		        Custom_Model_Message::showMessage(self::NO_USERNAME);
        		        break;
        		    case 'noSamePassword':
        		        Custom_Model_Message::showMessage(self::NO_SAME_PASSWORD);
        		        break;
        		    case 'noPassword':
        		        Custom_Model_Message::showMessage(self::NO_PASSWORD);
        		        break;
        		    case 'pUnionNoExists':
        		        Custom_Model_Message::showMessage(self::PUNION_NO_EXISTS);
        		        break;
        		    case 'pUnionExists':
        		        Custom_Model_Message::showMessage(self::PUNION_EXISTS);
        		        break;
        		    case 'editPUnionSucess':
        		        Custom_Model_Message::showMessage(self::EDIT_PUNION_SUCESS, 'event', 1250, 'Gurl()');
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        	    }
            } else {
                $this -> view -> action = 'edit';
                $this -> view -> title = '修改推广联盟';
                $this -> view -> changePassword = '不改请留空';
                $pUnion = $this -> _pUnion -> getPUnionByUid($id);
                $this -> view -> pUnion = $pUnion;
                $this -> view -> sexRadios = $this -> _sex;
                $this -> view -> affiliateTypeRadios = $this -> _affiliateType;
        	    $this -> view -> getMoneyTypeRadios = $this -> _getMoneyType;
            }
        } else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 查看推广联盟信息
     *
     * @return void
     */
    public function viewAction()
    {
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$pUnion = $this -> _pUnion -> getPUnionByUid($id);
    	$pUnion['sex'] = $this -> _sex[$pUnion['sex']];
    	$pUnion['affiliate_type'] = $this -> _affiliateType[$pUnion['affiliate_type']];
    	$pUnion['get_money_type'] = $this -> _getMoneyType[$pUnion['get_money_type']];
    	$this -> view -> pUnion = $pUnion;
    }
    
    /**
     * 删除推广联盟
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _pUnion -> deletePUnionById($id);
            switch ($result) {
            	case 'deletePUnionSucess':
        		    exit;
        		case 'error':
        		    exit('error!');
            }
        } else {
            exit('error!');
        }
    }
    
    /**
     * 更改状态
     *
     * @return void
     */
    public function statusAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
	        $this -> _pUnion -> changeStatus($id, $status);
        } else {
            Custom_Model_Message::showMessage('error!');
        }
        
        echo $this -> _pUnion -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $id, $status);
    }
    
    /**
     * 检查推广联盟是否存在
     * 
     * @return void
     */
    public function checkAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $value = $this -> _request -> getParam('val', null);
        
        if(!empty($value)){
        	$result = $this -> _pUnion -> getUserByName($value);
	        if (!empty($result)) {
	        	exit(self::USER_EXISTS);
	        }
	        exit;
        }
    }
 	
    /**
     * 查看推广联盟的点击量
     *
     * @return void
     */
    public function viewClickAction()
    {	
        $page = (int)$this -> _request -> getParam('page', 1);
        $data = $this-> _pUnion -> getClick($this -> _request -> getParams(),$page);
 
        $this -> view -> data = $data['content'];
        $pageNav = new Custom_Model_PageNav($data['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    } 
	
    /**
     * 推广联盟订单搜索
     *
     * @return void
     */
    public function searchOrderAction()
    {	
	    if($this -> _request -> getParam('dosearch') || $this -> _request -> getParam('id') > 0)
		{
			$page = (int)$this -> _request -> getParam('page', 1);
            $queryParams = $this -> _request -> getParams();
			$data = $this-> _pUnion -> searchOrder($queryParams,$page);
			foreach ($data['content'] as $list)
			{
				$list['order_time'] = date('Y-m-d', $list['order_time']);
				$order[] = $list;
			}
			$this -> view -> order = $order;
            $this -> view -> totalorder = $data['totaldata'];
			$pageNav = new Custom_Model_PageNav($data['totaldata']['total'], null, 'ajax_search');
			$this -> view -> pageNav = $pageNav -> getNavigation();
			$this -> view -> param = $queryParams;
			
		}
		$memberRanks = Custom_Config_Xml::getMemberRanks();
    	
    	foreach ($memberRanks as $key => $value)
    	{
    		$memberAllRanks[$value['name']] = $value['name'];
    	}
    	
    	$this -> view -> memberAllRanks = $memberAllRanks;
    }
	
	/**
     * 导出订单数据
     *
     * @return void
     */
    public function exportOrderAction()
    {
    	$opt_api = new Admin_Models_API_OpLog();
    	$opt_api->addopt($this ->_auth['admin_id'],"PUnion-exportOrder");
    	Zend_Controller_Front::getInstance() -> unRegisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$data = $this-> _pUnion -> getOrder($this -> _request -> getParams());
    	exit;
    }


	/**
     * 导出订单商品数据
     *
     * @return void
     */
    public function exportOrderGoodsAction()
    {
    	$opt_api = new Admin_Models_API_OpLog();
    	$opt_api->addopt($this ->_auth['admin_id'],"PUnion-exportOrderGoods");
    	Zend_Controller_Front::getInstance() -> unRegisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$data = $this-> _pUnion -> getExportOrderGoods($this -> _request -> getParams());
    	exit;
    }

	/**
     * 导出联盟数据
     *
     * @return void
     */
    public function exportUnionAction()
    {
    	Zend_Controller_Front::getInstance() -> unRegisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$data = $this-> _pUnion -> getUnion($this -> _request -> getParams());
    	exit;
    }
    
    /**
     * 某联盟的商品分成比率列表
     * 
     */
    public function productProportionListAction() {
    	$uid = (int)$this -> _request -> getParam('uid', 0);
    	if($uid<1)exit('参数错误');
    	$cname = $this -> _pUnion -> getPUnionByUid($uid);
    	$page = (int)$this -> _request -> getParam('page', 1);
    	$data = $this -> _pUnion -> getProductProportionList($uid, $this -> _request -> getParams(), $page);
    	$pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
    	$category_api = new Admin_Models_API_Category();
    	$cats = $category_api ->productCatTree();
    	
        $this -> view -> pageNav = $pageNav -> getNavigation();
    	$this -> view -> pUnionList = $data['data'];
    	$this -> view -> param = $this -> _request -> getParams();
    	$this -> view -> cats = $cats;
    	$this -> view -> uid = $uid;
    	$this -> view -> cname = $cname['cname'];
    }
    
	/**
     * ajax设置单个商品的分成比例
     * 
     * 
     */
    public function setproportionAction() {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    	$uid = $this -> _request -> getParam('uid', 0);
    	$val = $this -> _request -> getParam('val', null);
    	$goods_id = $this -> _request -> getParam('id', null);
    	if($goods_id>0 && $val<41 && $val>=0 && $uid>0){
    		//更新字段shop_goods表proportion
    		$this -> _pUnion -> setGoodsProportion($uid,$goods_id,$val);
    	}else{
    		echo '参数错误...';
    	}
    	exit;
    }
    
    /**
     * ajax同步分类下的商品分成比率
     * 
     */
    public function setCatProductProportionAction() {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    	$uid = $this -> _request -> getParam('uid', 0);
    	$cat_id = $this -> _request -> getParam('cat_id', null);
    	$cat_proportion = $this -> _request -> getParam('cat_proportion', null);
    	$cat_proportion = (int)$cat_proportion;
    	if($uid>0 && $cat_id>0){
    		$this -> _pUnion -> setCatProductProportion($uid,$cat_id,$cat_proportion);
    	}else{
    		echo 'error';
    	}
    	exit;
    }
    
	/**
     * 导出某联盟商品分成比率数据
     *
     * @return void
     */
    public function exportUnionGoodsProportionAction()
    {
    	Zend_Controller_Front::getInstance() -> unRegisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$uid = (int)$this -> _request -> getParam('uid', 0);
    	$data = $this-> _pUnion -> exportUnionGoodsProportion($uid,$this -> _request -> getParams());
    	exit;
    }


	/**
     * 补推联盟分成数据
     *
     * @return void
     */
    public function  unorderAction(){
        $start_time = $this -> _request -> getParam('start_date', null);//2012-03-10 00:00:00
        $end_time = $this -> _request -> getParam('end_date', null);//2012-03-12 23:59:59
        if(isset($start_time) && isset($end_time) && $start_time<= $end_time){
            // 允许访问的联盟id列表
            $allowUnionId = array(9096,13,21,3518,3519,3763);
            $curtime=strtotime($start_time);
            $enttime=strtotime($end_time)+86400;
            $this->_db = Zend_Registry::get('db');
            $sql = "SELECT a.order_id,a.order_sn,a.batch_sn,a.add_time,a.user_id,a.user_name,a.parent_id,a.parent_param,a.rank_id,a.proportion,";
            $sql .= "b.price_order,b.price_goods,b.status FROM shop_order a left join shop_order_batch b on a.order_sn = b.order_sn ";
            $sql .= " where a.parent_id>0 and a.add_time >'$curtime' and a.add_time < '$enttime' order by order_batch_id desc  ";
            $list =  $this -> _db -> fetchAll($sql);
            foreach($list as $key=>$order){
                    $html='';
                    $un = $this -> _db -> fetchRow("SELECT order_sn FROM `shop_union_affiliate`  where order_id={$order[order_id]}");
                    $sql = 'SELECT a.user_id,a.user_name,b.affiliate_type,b.get_money_type,b.proportion_rule,b.un_type,b.calculate_type FROM `shop_user` a left join `shop_union_normal` b on a.user_id = b.user_id WHERE a.user_id= ' . $order['parent_id'];
                    $uninfo= $this -> _db -> fetchRow($sql);
                    if($un){
                            //echo $order['order_sn'].'已经分成<br>';
                    }else{
                        echo $order['order_sn'].'没有分成<br>';

                        $data['parent_id'] = $order['parent_id'];
                        $data['parent_param'] = $order['parent_param'];
                        $data['proportion'] = $order['proportion'] ;
                        $data['order_id'] = $order['order_id'];
                        $data['order_sn'] = $order['order_sn'];
                        $data['status'] = $order['status']; 
                        $data['status_logistic'] = 0;
                        $data['status_return'] = 0;
                        $data['user_id'] = $order['user_id'];
                        $data['user_name'] = $order['user_name'];
                       
                        $data['price_goods'] = $order['price_goods'];
                        $data['price_order'] = $order['price_order'];
                        $data['affiliate_amount'] = $order['price_order'];
                        if ($data['affiliate_amount'] < 0) {
                            $data['affiliate_amount'] = 0;
                        }
                        $data['cpa_goods_type'] = 0;
                        $data['cpa_zero_type'] = 0;
                   
                        $affiliate_money=$data['affiliate_amount']*$data['proportion']*0.01;
                        $affiliateLogRow = array (
                                                  'order_id' => $data['order_id'],
                                                  'order_sn' => $data['order_sn'],
                                                  'order_user_id' => $data['user_id'],
                                                  'order_user_name' => $data['user_name'],
                                                  'order_status' => $data['status'],
                                                  'order_status_logistic' => $data['status_logistic'],
                                                  'order_status_return' => $data['status_return'],
                                                  'order_affiliate_amount' => $data['affiliate_amount'],
                                                  'order_price_goods' => $data['price_goods'],
                                                  'order_price' => $data['price_order'],
                                                  'proportion' => $data['proportion'],
                                                  'affiliate_money' => $affiliate_money,
                                                  'add_time' => $order['add_time']
                                                  );

                        $affiliateLogId =  $this -> _db -> insert('shop_union_affiliate_log', $affiliateLogRow);
                        $affiliateRow = array (
                                               'order_id' => $data['order_id'],
                                               'order_sn' => $data['order_sn'],
                                               'order_user_id' => $data['user_id'],
                                               'order_user_name' => $data['user_name'],
                                               'cpa_goods_type' => $data['cpa_goods_type'],
                                               'cpa_zero_type' => $data['cpa_zero_type'],
                                               'order_affiliate_amount' => $data['affiliate_amount'],
                                               'order_price_goods' => $data['price_goods'],
                                               'order_price' => $data['price_order'],
                                               'order_status' => $data['status'],
                                               'order_status_logistic' => $data['status_logistic'],
                                               'order_status_return' => $data['status_return'],
                                               'user_id' => $data['parent_id'],
                                               'user_name' => $uninfo['user_name'],
                                               'rank_name' => '普通会员',
                                               'union_type' => '2',
                                               'un_type' => $uninfo['un_type'],
                                               'user_param' => $data['parent_param'],
                                               'proportion' => $data['proportion'],
                                               'affiliate_money' => $affiliate_money,
                                               'add_time' => time(),
                                               'code_param' => $data['code_param'],           
                                               'last_affiliate_log_id' => $affiliateLogId
                                               );
                        $this -> _db -> insert('shop_union_affiliate', $affiliateRow);
                        $union = new Shop_Models_API_Union();
                        if(in_array($uninfo['user_id'],$allowUnionId)){
                             $unionHtml = $union -> orderApi($order['batch_sn'], $uninfo['user_id'],$uninfo['un_type']);
                             echo($unionHtml);
                             echo('<br>');
                        }
                     }
              }
            exit;


        }
    }


}

