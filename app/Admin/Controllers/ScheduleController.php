<?php

class Admin_ScheduleController extends Zend_Controller_Action 
{
	/**
     * api对象
     */
    private $_api = null;
    
	const ADD_SUCCESS = '添加计划成功!';
	const EDIT_SUCCESS = '编辑计划成功!';
	const DEL_SUCCESS = '删除计划成功!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Schedule();
	}
	
	/**
     * 计划列表
     */
	public function listAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this->_request->getParams();
        $where = null;
        if ($search['dosearch'])    $where = $search;
        $datas = $this -> _api -> get($where, '*', null, $page, 20);
        if ( $datas['total'] > 0 ) {
            $tuan_ids = array();
            foreach ($datas['list'] as $num => $data) {
            	$datas['list'][$num]['add_time'] = date('Y-m-d', $datas['list'][$num]['add_time']);
            	$datas['list'][$num]['last_time'] = ($datas['list'][$num]['last_time'] > 0) ? date('Y-m-d H:i:s', $datas['list'][$num]['last_time']) : '';
            	$datas['list'][$num]['status'] = $this -> _api -> ajaxStatus('/admin/schedule/status', $datas['list'][$num]['id'], $datas['list'][$num]['status']);
            }
        }

        $this -> view -> datas = $datas['list'];
        $this -> view -> param = $search;
	    $pageNav = new Custom_Model_PageNav($datas['total'], 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
	
    /**
     * 添加计划
     *
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> edit($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, '/admin/schedule/list', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> render('edit');
        }
    }
    
    /**
     * 编辑计划
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> edit($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, '/admin/schedule/list/', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $data = $this -> _api -> get("id = {$id}");
                $data = array_shift( $data['list'] );
                
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * ajax更新计划
     *
     * @return void
     */
    public function ajaxupdateAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val', null);
        if ($id > 0) {
            $this -> _api -> ajaxUpdate($id, $field, $val);
        } else {
            exit('error!');
        }
    }
    
    /**
     * ajax更新计划状态
     *
     * @return void
     */
    public function statusAction() {
        $this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
            $this -> _api -> ajaxUpdate($id, 'status', $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _api -> ajaxStatus('/admin/schedule/status', $id, $status);
    }
    
    /**
     * 删除计划
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _api -> delete($id);
            if(!$result) {
        	    exit($this -> _api -> error());
            }else{
                $this->_redirect('/admin/schedule/list/');
			}
        } else {
            exit('error!');
        }
    }
    
    /**
     * 运行计划
     *
     * @return void
     */
    public function runAction()
    {
        $id = (int)$this -> _request -> getParam('id', 0);
        $do = (int)$this -> _request -> getParam('do', null);
        
        $data = $this -> _api -> getScheduleById($id);
        if ( !$data )   exit('error');
        
        if ($do) {
            $_auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
            $this -> _api -> updateRun($id, $_auth['admin_name'], ++$data['run_count'], $data['log']);
            
            $this->_redirect($data['action_url'].'/do/schedule');
            exit;
        }
        
        $this -> view -> id = $id;
    }
    
    /**
     * 自动运行计划(ajax调用)
     *
     * @return void
     */
    public function runAutoAction()
    {
        //$where = 'type = 2 and status = 0 and (last_time = 0 or (last_time + `interval` * 60) <= '.time().')';
        //$datas = $this -> _api -> get($where, 'id');
        $datas = $this -> _api -> getValidSchedule();
        $idArray = array();
        if ( $datas ) {
            foreach ( $datas as $data ) {
                $idArray[] = $data['id'];
            }
        }
        
        echo implode(',', $idArray);
        exit;
    }
    
    /**
     * 任务日志
     *
     * @return void
     */
    public function logAction()
    {
        $id = (int)$this -> _request -> getParam('id', 0);
        
        $data = $this -> _api -> getScheduleById($id);
        if ( !$data )   exit('error');
        
        $this -> view -> log = $data['log'];
    }
}
