<?php

class Admin_OpLogController extends Zend_Controller_Action
{

    public function init()
	{
        $this->_api = new Admin_Models_API_OpLog();
	}


    /**
     * 专题列表
     *
     * @return void
     */
     public function listAction(){
        $search = $this->_request->getParams();
        $this -> view -> param = $search;
        if(!empty($search['bill_type']) || !empty($search['ctime']) || !empty($search['ltime'])){
        	 $tmp = $search;
        }
        $total = $this->_api->getCount($tmp);
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->get($tmp,'id,name,title,flagUrl,isDisplay',null,$page);
        $this->view->data = $data;
        $pageNav = new Custom_Model_PageNav($total);
        $this->view->pageNav=$pageNav->getNavigation();
     }
}