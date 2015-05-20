<?php

class Admin_TopicsController extends Zend_Controller_Action
{
	const NO_NAME = '请填写专题名称!';
	const ADD_SUCCESS = '添加成功!';
    const ADD_FAIL = '添加失败！';
    const EDIT_SUCCESS = '修改成功！';
    const EDIT_FAIL = '修改失败！';
    const DEL_SUCCESS = '删除成功！';
    const DEL_FAIL = '删除失败！';
    const NO_TITLE = '请填写专题标题';
    const NO_CONTENT = '请填写专题内容';
    const NO_CAT = '请选择分类';
    const TAG_SUCCESS = '标签编辑成功!';
    const IMG_FAIL = '上传图片失败功!';

    public function init()
	{
        $this->_api = new Admin_Models_API_Topics();
	}

    /**
     * 添加专题
     *
     * @return void
     */
    public function addAction(){
	   if ($this -> _request -> isPost()) {
			$post = $this->_request->getPost();
	        if($this->_api->add($post['params'],$error)){
	            Custom_Model_Message::showMessage(self::ADD_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/topics/list/');
	        }else{
	            switch ($error) {
	                case 'imgErr':
	                    Custom_Model_Message::showMessage(self::IMG_FAIL);
	                    break;
	            }
	        }
	    }else{
	    	$this->view->action='add';
	    	$this -> render('addform');
	    }
    }
    /**
     * 专题列表
     *
     * @return void
     */
     public function listAction(){
        $search = $this->_request->getParams();
        $this -> view -> param = $search;
        if(isset($search['title']) && $search['title']!='null'){
			$tmp['name'] = $search['title'];
        }
        $total = $this->_api->getCount($tmp);
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->get($tmp,'id,name,title,flagUrl,isDisplay,sort',null,$page);
        $this->view->data = $data;
        $pageNav = new Custom_Model_PageNav($total);
        $this->view->pageNav=$pageNav->getNavigation();
     }

    /**
     * 删除分类
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

    /**
     * 修改专题
     *
     * @return void
     */
    public function editAction(){
       if( $post = $this->_request->getPost()){
	      if($this->_api->edit($post['article'],$error)){
				 Custom_Model_Message::showMessage(self::EDIT_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/topics/list');
	        }else{
				switch ($error) {
					case 'noName':
						Custom_Model_Message::showMessage(self::NO_TITLE);
						break;
					case 'noContent':
						Custom_Model_Message::showMessage(self::NO_CONTENT);
						break;
					case 'noCatID':
						Custom_Model_Message::showMessage(self::NO_CAT);
						break;
					case 'editFail':
						Custom_Model_Message::showMessage(self::EDIT_FAIL);
						break;
				}
	        }
	    }else{
			$id=$this->_request->getParam('id', null);
			$pkid=$this->_request->getParam('pkid', "id");
			$this->view->article=$this->_api->getByID($id,$pkid);
			$this->view->action='edit';
			$this -> render('editform');
	    }
    }

    /**
     * 切换主题可见性
     *
     * @return void
     */
    public function toggleIsdisAction() {
        
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        $status = ( int ) $this->_request->getParam ( 'status', 0 );
    
        if ($id <= 0) die('failure');
    
        $cs = $this->_api->toggleIsdis($id);
        echo $cs == 0 ? '不显示' : '显示';
        exit;
    }
    
    /**
     * ajax更新数据
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
    
}