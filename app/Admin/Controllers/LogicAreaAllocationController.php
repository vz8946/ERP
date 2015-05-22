<?php

class Admin_LogicAreaAllocationController extends Zend_Controller_Action
{
	private $_api = null;
    
    private $_logicArea = null;
    
	private $_auth = null;
	
	private $_stock = null;
    
	const ADD_SUCCESS = '申请成功!';
	const CANCEL_SUCCESS = '申请取消成功!';
	const CHECK_SUCCESS = '审核成功!';
	const CONFIRM_SUCCESS = '确认成功!';
	const SEND_SUCCESS = '发货成功!';
	const RECEIVE_SUCCESS = '收货成功!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _cat = new Admin_Models_API_Category();
		$this -> _api = new Admin_Models_API_Allocation();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> view -> auth = $this -> _auth;
		
		$this -> _logicArea = (int)$this -> _request -> getParam('logic_area', 1);
		$this -> view -> logic_area = $this -> _logicArea;
		$this -> _stock = Custom_Model_Stock_Base::getInstance($this -> _logicArea);

		$this -> _actions = $this -> _stock -> getConfigAllocationAction();
		$this -> view -> actions = $this -> _stock -> getConfigAllocationAction();
		$this -> view -> operates = $this -> _stock -> getConfigOperate();
		$this -> view -> status = $this -> _stock -> getConfigLogicStatus();
		$this -> view -> billStatus = $this -> _stock -> getConfigAllocationStatus();
		$this -> view -> areas = $this -> _stock -> getConfigLogicArea();
		$this -> view -> area_name = $this -> _stock -> getAreaName();
	}
	
	/**
     * 预处理
     *
     * @return   void
     */
	public function postDispatch()
    {
    	$action = $this -> _request -> getActionName();
        if (array_key_exists($action, $this -> _actions)) {
			$search = $this -> _request -> getParams();
		    $page = (int)$this -> _request -> getParam('page', 1);
	        $data = $this -> _api -> search($search, $action, $page, $this -> _logicArea);
		    $pageNav = new Custom_Model_PageNav($data['total'], null, 'ajax_search');
		    
		    $this -> view -> datas = $data['datas'];
	        $this -> view -> action = $action;
	        $this -> view -> param = $this -> _request -> getParams();
	        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id')); 
	        $this -> view -> pageNav = $pageNav -> getNavigation();
	        $this -> render('list');
        }
    }
    
    /**
     * 调拨单查询
     *
     * @return void
     */
    public function searchListAction()
    {
    }
    
    /**
     * 调拨单取消
     *
     * @return void
     */
    public function cancelListAction()
    {
    }
    
    /**
     * 调拨单确认
     *
     * @return void
     */
    public function confirmListAction()
    {
    }
    
    /**
     * 调拨单发货
     *
     * @return void
     */
    public function sendListAction()
    {
    }
    
    
    /**
     * 调拨单收货
     *
     * @return void
     */
    public function receiveListAction()
    {
    }
    
    /**
     * 调拨单明细
     *
     * @return void
     */
    public function detailAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
    	$search = $this -> _request -> getParams();
        $datas = $this -> _api -> getDetail($search, $page);
        $total = $this -> _api -> getCount();
        $this -> view -> datas = $datas;
        $this -> view -> catSelect = $this -> _cat ->buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 调拨单申请
     *
     * @return void
     */
    public function addAction()
    {
    	if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> add($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
        	}
        }
    }
    
    /**
     * 调拨单取消
     *
     * @return void
     */
    public function cancelAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> cancel($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CANCEL_SUCCESS, 'event', 1250, "Gurl()");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $this -> view -> action = 'cancel';
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 调拨单取消审核
     *
     * @return void
     */
    public function cancelCheckAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> cancelCheck($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CHECK_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        		$check = $p['is_check'];
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed($check)");
	        	}
            } else {
                $this -> view -> action = 'cancel-check';
                $datas = $this -> _api -> getDetail("a.aid=$id");
                $data = $datas[0];
                foreach ($datas as $num => $v)
		        {
					$data['total_number'] += $v['number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
                $this -> view -> op_cancel = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
                $this -> render('check');
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 调拨单确认
     *
     * @return void
     */
    public function confirmAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> confirm($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::CONFIRM_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $this -> view -> action = 'confirm';
                $datas = $this -> _api -> getDetail("a.aid=$id");
                $data = $datas[0];
                foreach ($datas as $num => $v)
		        {
					$data['total_number'] += $v['number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 调拨单发货
     *
     * @return void
     */
    public function sendAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> send($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::SEND_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
	        	}
            } else {
                $this -> view -> action = 'send';
                $datas = $this -> _api -> getDetail("a.aid=$id");
                $data = $datas[0];
                foreach ($datas as $num => $v)
		        {
					$data['total_number'] += $v['number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 调拨单收货
     *
     * @return void
     */
    public function receiveAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> receive($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::RECEIVE_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error(), 'event', 1250, "failed()");
	        	}
            } else {
                $this -> view -> action = 'receive';
                $datas = $this -> _api -> getDetail("a.aid=$id");
                $data = $datas[0];
                foreach ($datas as $num => $v)
		        {
					$data['total_number'] += $v['number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 查看动作
     *
     * @return void
     */
    public function viewAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
                $this -> view -> action = 'view';
                $datas = $this -> _api -> getDetail("a.aid=$id");
                $data = $datas[0];
                foreach ($datas as $num => $v)
		        {
					$data['total_number'] += $v['number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
                $this -> view -> op_cancel = array_shift($this -> _api -> getOp("item_id=$id and op_type='cancel'"));
                $this -> view -> op_check = array_shift($this -> _api -> getOp("item_id=$id and op_type='check'"));
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 打印动作
     *
     * @return void
     */
    public function printAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
                $this -> view -> action = 'print';
                $datas = $this -> _api -> getDetail("a.aid=$id");
                $data = $datas[0];
                foreach ($datas as $num => $v)
		        {
					$data['total_number'] += $v['number'];
		        }
                $this -> view -> data = $data;
                $this -> view -> details = $datas;
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
	/**
     * 锁定/解锁动作
     *
     * @return   void
     */
    public function lockAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = (int)$this -> _request -> getParam('val', 0);
    	$this -> _api -> lock($this -> _request -> getPost(), $val);
    }
    
}
