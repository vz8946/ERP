<?php

class Admin_GoodsCardController extends Zend_Controller_Action 
{
	const ADD_TYPE_SUCCESS = '添加提货卡类型成功!';
	const EDIT_TYPE_SUCCESS = '编辑提货卡类型成功!';
	const DEL_TYPE_SUCCESS = '删除提货卡类型成功!';
	const ADD_CARD_SUCESS = '添加提货卡成功!';
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_GoodsCard();
	}
	
	/**
     * 提货卡类型列表
     *
     * @return void
     */
	public function typeAction()
	{
	    $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> get($search, '*', null, $page, 25);
        if ( $datas['total'] > 0 ) {
            foreach ($datas['list'] as $num => $data) {
                $datas['list'][$num]['status'] = $this -> _api -> ajaxStatus('/admin/goods-card/type-status', $data['card_type_id'], $data['status']);
                $datas['list'][$num]['goods_num'] = count(unserialize($data['goods_info']));
            }
        }
        
        $this -> view -> datas = $datas['list'];
        $this -> view -> param = $search;
	    $pageNav = new Custom_Model_PageNav($datas['total'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
	}
	
	/**
     * 添加提货卡类型
     *
     * @return   void
     */
	public function addTypeAction()
	{
	    if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> editType($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_TYPE_SUCCESS, '/admin/goods-card/type', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> render('edit-type');
        }
	}
	
	/**
     * 编辑提货卡类型
     *
     * @return void
     */
    public function editTypeAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editType($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::EDIT_TYPE_SUCCESS, '/admin/goods-card/type', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $data = $this -> _api -> get("card_type_id = {$id}");
                $data = array_shift( $data['list'] );
                $data['goods_info'] = unserialize($data['goods_info']);
                
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
            }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 删除提货卡类型
     *
     * @return void
     */
    public function delTypeAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            $result = $this -> _api -> delType($id);
            if ($result) {
                $this->_redirect("/admin/goods-card/type");
	        }
	        else {
	        	die($this -> _api -> error());
	        }
        }
        else {
            die('error');
        }
    }
	
	/**
     * ajax更新卡类型状态
     *
     * @return void
     */
    public function typeStatusAction() {
        $this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
            $this -> _api -> ajaxTypeUpdate($id, 'status', $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        
        echo $this -> _api -> ajaxStatus('/admin/goods-card/type-status', $id, $status);
    }
    
    /**
     * ajax更新生成卡状态
     *
     * @return void
     */
    public function logStatusAction() {
        $this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
            $this -> _api -> ajaxLogUpdate($id, 'status', $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        
        echo $this -> _api -> ajaxStatus('/admin/goods-card/log-status', $id, $status);
    }
    
    /**
     * 添加提货卡
     *
     * @return void
     */
    public function addCardAction()
    {
        if ($this -> _request -> isPost()) {
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$data = $this -> _request -> getPost();
            $result = $this -> _api -> addCardLog($data);
            switch ($result) {
        		case 'addCardSucess':
        		    Custom_Model_Message::showMessage(self::ADD_CARD_SUCESS, '/admin/goods-card/log-list', 1250);
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!');
        	}
        }
        
        $datas = $this -> _api -> get(array('status' => 0));
        if ($datas['list']) {
            foreach ($datas['list'] as $key => $data) {
                $datas['list'][$key]['goods_num'] = count(unserialize($data['goods_info']));
            }
        } 
        $this -> view -> cardTypeList = $datas['list'];
    }
    
    /**
     * 提货卡发放记录
     *
     * @return void
     */
    public function logListAction()
    {
    	$page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> getCardLog($search, '*', null, $page, 25);
        
        if ($datas['list']) {
        	foreach ($datas['list'] as $num => $data) {
                $datas['list'][$num]['goods_num'] = count(unserialize($data['goods_info']));
                $datas['list'][$num]['status'] = $this -> _api -> ajaxLogStatus('/admin/goods-card/log-status', $data['log_id'], $data['status']);
            }
        }
        
        $this -> view -> datas = $datas['list'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 提货卡列表
     *
     * @return void
     */
    public function cardListAction()
    {
    	if ($this -> _request -> isPost()) {
	        $post = $this -> _request -> getPost();
	        if ( $post['ids'] ) {
	            foreach ( $post['ids'] as $cardID ) {
	                $datas = $this -> _api -> getCard(array('card_id' => $cardID));
	                if (!$datas['list']) {
	                    Custom_Model_Message::showMessage("找不到ID为{$cardID}的卡!", 'event', 2500, "Gurl('refresh')");
	                }
	                $card = $datas['list'][0];
	                if ($post['todo'] == 'active') {
	                    if ($card['status'] != 0 && $card['status'] != 3) {
	                        Custom_Model_Message::showMessage("{$card['card_sn']}状态不是未激活或已作废，不能激活!", 'event', 2500, "Gurl('refresh')");
	                    }
	                }
	                if ($post['todo'] == 'deactive') {
	                    if ($card['status'] != 0 && $card['status'] != 1) {
	                        Custom_Model_Message::showMessage("{$card['card_sn']}状态不是未激活或可使用，不能作废!", 'event', 2500, "Gurl('refresh')");
	                    }
	                }
	                if ($post['todo'] == 'set_end_date') {
	                    if ($card['status'] == 2) {
	                        Custom_Model_Message::showMessage("{$card['card_sn']}状态已使用，不能设置有效期!", 'event', 2500, "Gurl('refresh')");
	                    }
	                }
	                if ($post['todo'] == 'sell') {
	                    if ($card['sold'] == 1) {
	                        Custom_Model_Message::showMessage("{$card['card_sn']}已销售，不能再设置!", 'event', 2500, "Gurl('refresh')");
	                    }
	                }
	                if ($post['todo'] == 'settlement') {
	                    if ($card['status'] != 2 || $card['sold'] != 1) {
	                        Custom_Model_Message::showMessage("{$card['card_sn']}不是已使用状态，不能销账!", 'event', 2500, "Gurl('refresh')");
	                    }
	                }
	            }
	            if ($post['todo'] == 'active' || $post['todo'] == 'deactive' || $post['todo'] == 'set_end_date' || $post['todo'] == 'sell' || $post['todo'] == 'settlement') {
	                $this -> _api -> setCard($post['ids'], $post['todo'], $post);
	            }
	        }
	        
	        Custom_Model_Message::showMessage('设置成功!', 'event', 1250, "Gurl('refresh')");
	    }
        
    	$page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> getCard($search, 't1.*', null, $page, 100);
        
        if ($datas['list']) {
        	foreach ($datas['list'] as $num => $data) {
        	    $datas['list'][$num]['goods_num'] = count(unserialize($data['goods_info']));
            }
        }
        
        $this -> view -> datas = $datas['list'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total'], 100);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 提货卡类型详情
     *
     * @return void
     */
    public function viewTypeAction()
    {
        $id = (int)$this -> _request -> getParam('id', 0);
        if (!$id)   die('error');
        
        $datas = $this -> _api -> get(array('card_type_id' => $id));
        if (!$datas['list'])    die('error');
        $data = $datas['list'][0];
        $data['goods_info'] = unserialize($data['goods_info']);
        foreach ($data['goods_info'] as $goods_sn) {
            $data['detail'][] = $this -> _api -> getGoodsDetail($goods_sn);
        }
        
        $this -> view -> data = $data;
    }
    
     /**
     * 取得已生成提货卡账号和密码
     *
     * @return void
     */
    public function getFileAction()
    {
    	Zend_Controller_Front::getInstance() -> unRegisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$this -> _api -> getCardFile($id);
    	exit;
    }
    
    /**
     *  销售统计
     *
     * @return void
     */
    public function saleAction()
    {
        $search = $this -> _request -> getParams();
        $this -> view -> datas = $this -> _api -> getCardSale($search);
        
        $this -> view -> param = $search;
    }
}