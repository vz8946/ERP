<?php

 class Admin_DecorationController extends Zend_Controller_Action {
     
    /**
     * 
     * @var Admin_Models_API_Decoration
     */
 	private $_api  = null;
 	//初始化对象
 	public function init()
	{
		$this ->_api = new Admin_Models_API_Decoration();
		$this->_pageSize = Zend_Registry::get('config')->view->page_size;
	}

	/*
	 *
	 * 爆款装修操作(type=baokuan)
	 * 促销中心（type=prom）
	*/
 	function listAction(){
		$pid = intval($this->getRequest()->getParam("pid"));
		$type = $this->getRequest()->getParam("type");
		if(!empty($pid)){
			$catelist = $this->_api->getAllCate($pid);
			$this->view->catelist = $catelist;
		}		
		$total = $this->_api->getCount();
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->get(array("type"=>$type),'*',null,$page);
        $this->view->type = $type;
         $this->view->pid = $pid;
        $this->view->data = $data;
        $this->view->opt_yn = array('1'=>'显示','0'=>'不显示');
 	}

 	/**
 	 * 更新显示情况
 	 * 
 	 */
 	public function ajaxupdatedisplayAction(){
 	    $this->_helper->viewRenderer->setNoRender ();
 	    $id = $this->_request->getParam('id',0);
 	    $val = $this->_request->getParam('val',0);
 	    if(empty($id)) die('failure');
 	    $val = (int)$val;
 	    $this->_api->updateDisplay($id,$val);
 	    exit;
 	}
 	
 	/**
 	 * 更新排序
 	 * 
 	 */
 	public function ajaxupdateordAction(){
 	    $this->_helper->viewRenderer->setNoRender ();
 	    $id = $this->_request->getParam('id',0);
 	    $val = $this->_request->getParam('val',0);
 	    if(empty($id)) die('failure');
 	    $val = (int)$val;
 	    $this->_api->updateOrd($id,$val);
 	    exit;
 	}
 	
 	/*
 	 *
 	 * 添加一条记录
 	 * */
 	 function addAction(){
		$type = $this->getRequest()->getParam("type");
		$pidcode = $this->getRequest()->getParam("pidcode");	
 	 	$this->_api->add($type,$pidcode);
 	 	$this->_redirect("admin/decoration/list/type/$type/pid/$pidcode");
 	 }
	/*保存数据*/
	function saveAction(){
		$type = $this->getRequest()->getParam("type");
		$pidcode = $this->getRequest()->getParam("pidcode");
		$parms = $this->_request->getParams();

		$this->_api->save($parms);
		$this->_redirect("admin/decoration/list/type/$type/pid/$pidcode");
	}
	/**
     * 删除一条记录
     *
     * @return void
     */
    public function delAction(){
        $id=$this->_request->getParam('id', null);
        $pkid=$this->_request->getParam('pkid', null);
        if($this->_api->del($id,$pkid)){
            exit;
        }
    }
 }