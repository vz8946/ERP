<?php

class Admin_NewsController extends Zend_Controller_Action
{
	const NO_NAME = '请填写名称!';
	const ADD_SUCCESS = '添加成功!';
    const ADD_FAIL = '添加失败！';
    const EDIT_SUCCESS = '修改成功！';
    const EDIT_FAIL = '修改失败！';
    const DEL_SUCCESS = '删除成功！';
    const DEL_FAIL = '删除失败！';
    const NO_TITLE = '请填写文章标题';
    const NO_CONTENT = '请填写文章内容';
    const NO_CAT = '请选择分类';
    const TAG_SUCCESS = '标签编辑成功!';
    const IMG_FAIL = '上传图片失败功!';

    public function init()
	{
        $this->_api = new Admin_Models_API_News();
	}
    /**
     * 添加分类表单
     *
     * @return void
     */
    public function addcatformAction(){
        $this->view->catTree=$this->_api->catTree();
        $this->view->catID=$this->_request->getParam('id', null);
        $this->view->action='addcat';
    }
    /**
     * 添加分类表单
     *
     * @return   void
     */
    public function addcatAction(){

        $post = $this->_request->getPost();
        if($this->_api->addCat($post['cat'],$error)){
            Custom_Model_Message::showMessage(self::ADD_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/news/listcat/');
        }else{
            switch ($error) {
                case 'noName':
                    Custom_Model_Message::showMessage(self::NO_NAME);
                    break;
                case 'add_fail':
                    Custom_Model_Message::showMessage(self::ADD_FAIL);
                    break;
            }
        }

    }
     /**
     * 添加数据字典表单
     *
     * @return   void
     */
    public function adddataAction(){

        $post = $this->_request->getPost();
        if($this->_api->addData($post['data'],$error)){
            Custom_Model_Message::showMessage(self::ADD_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/news/listdata/');
        }else{
            switch ($error) {
                case 'noName':
                    Custom_Model_Message::showMessage(self::NO_NAME);
                    break;
                case 'add_fail':
                    Custom_Model_Message::showMessage(self::ADD_FAIL);
                    break;
            }
        }

    }

    /**
     * 分类列表
     *
     * @return void
     */
    public function listcatAction(){
        $this->view->catTree=$this->_api->catTree();
    }
    /**
     * 更新 分类列表 中可修改的字段
     *
     * @return void
     */
    public function ajaxupdatecatAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $id = (int)$this->_request->getParam('id', 0);
        $field = $this->_request->getParam('field', null);
        $val = $this->_request->getParam('val', null);
        if ($id > 0) {
            $result = $this->_api->ajaxupdatecatAction($id, $field, $val);
            switch ($result) {
            	case 'forbidden':
            	    Custom_Model_Message::showMessage(self::FORBIDDEN, 'event', 1250, 'Gurl()');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
            }
        } else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    /**
     * 修改分类表单
     *
     * @return void
     */
    public function editcatformAction(){
        $id=$this->_request->getParam('id', null);
        $this->view->catTree=$this->_api->catTree(array($id));
        $this->view->cat=$this->_api->getCatByID($id);
        $this->view->id=$id;
        $this->view->action='editcat';
    }
    /**
     * 修改分类
     *
     * @return void
     */
    public function editcatAction(){
        $post = $this->_request->getPost();
        if($this->_api->editCat($post['cat'],$error)){
            Custom_Model_Message::showMessage(self::EDIT_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/news/listcat');
        }else{
            switch ($error) {
                case 'noName':
                    Custom_Model_Message::showMessage(self::NO_NAME);
                    break;
                case 'editFail':
                    Custom_Model_Message::showMessage(self::EDIT_FAIL);
                    break;
            }

        }
    }
    /**
     * 删除分类
     *
     * @return void
     */
    public function delcatAction(){
        $catID=$this->_request->getParam('id', null);
        if($this->_api->delCat($catID,$error)){
            exit;
        }else{
            switch ($error) {
                case 'delFail':
                    exit(self::DEL_FAIL);
                    break;
            }

        }
    }
    /**
     * 添加文章表单
     *
     * @return void
     */
    public function addformAction(){
        $this->view->catTree=$this->_api->catTree();
        $this->view->action='add';
    }
    /**
     * 添加文章表单
     *
     * @return void
     */
    public function addadvformAction(){
        $this->view->action='addadv';
    }
     /**
     * 添加友情链接表单
     *
     * @return void
     */
    public function addlinkformAction(){
        $this->view->action='addlink';
    }
    /**
     * 添加数据字典表单
     *
     * @return void
     */
    public function adddataformAction(){
        $this->view->action='adddata';
    }
    /**
     * 添加文章
     *
     * @return void
     */
    public function addAction(){
        $post = $this->_request->getPost();
        $post['article']['addTime'] = date("Y-m-d H:i:s");
        $post['article']['content'] = $this->_request->getParam('content', null);
        if($this->_api->add($post['article'],$error)){
            Custom_Model_Message::showMessage(self::ADD_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/news/list/');
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
                case 'addFail':
                    Custom_Model_Message::showMessage(self::ADD_FAIL);
                    break;
                case 'imgErr':
                    Custom_Model_Message::showMessage(self::IMG_FAIL);
                    break;
            }

        }
    }
    /**
     * 添加广告位
     *
     * @return void
     */
    public function addadvAction(){
        $post = $this->_request->getPost();
        if($this->_api->addAdv($post['adv'],$error)){
            Custom_Model_Message::showMessage(self::ADD_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/news/listadvertising/');
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
                case 'addFail':
                    Custom_Model_Message::showMessage(self::ADD_FAIL);
                    break;
                case 'imgErr':
                    Custom_Model_Message::showMessage(self::IMG_FAIL);
                    break;
            }

        }
    }
    /**
     * 添加友情链接
     *
     * @return void
     */
    public function addlinkAction(){
        $post = $this->_request->getPost();
        if($this->_api->addLink($post['link'],$error)){
            Custom_Model_Message::showMessage(self::ADD_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/news/listlink/');
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
                case 'addFail':
                    Custom_Model_Message::showMessage(self::ADD_FAIL);
                    break;
                case 'imgErr':
                    Custom_Model_Message::showMessage(self::IMG_FAIL);
                    break;
            }

        }
    }
    /**
     * 文章列表
     *
     * @return void
     */
     public function listAction(){
        $search = $this->_request->getParams();
        $this -> view -> param = $search;
        if(isset($search['isTop']) && $search['isTop']!='null'){
         $tmp['isTop'] = $search['isTop'];
        }
        if(isset($search['title']) && $search['title']!='null'){
         $tmp['title'] = $search['title'];
        }
        if(isset($search['ncId']) && $search['ncId']!='null'){
         $tmp['ncId'] = $search['ncId'];
        }
        $total = $this->_api->getCount($tmp);
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->get($tmp,'id,asName,title,author,position,ncName,addTime,lauthor,whois,isTop',null,$page);
        $this->view->catTree=$this->_api->catTree();
        $this->view->data = $data;
        $pageNav = new Custom_Model_PageNav($total);
        $this->view->pageNav=$pageNav->getNavigation();
     }
     /**
     * 友情链接列表
     *
     * @return void
     */
     public function listlinkAction(){
        $search = $this->_request->getParams();
        $this -> view -> param = $search;


        if(isset($search['position']) && $search['position']!='null'){
         $tmp['position'] = $search['position'];
        }
        if(isset($search['text']) && $search['text']!='null'){
         $tmp['text'] = $search['text'];
        }
        $total = $this->_api->getLinkCount($tmp);
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->getLink($tmp,'id,text,link,grade,position,display',null,$page);
        $this->view->data = $data;
        $pageNav = new Custom_Model_PageNav($total);
        $this->view->pageNav=$pageNav->getNavigation();
     }
     /**
     * 数据字典列表
     *
     * @return void
     */
     public function listdataAction(){
        $search = $this->_request->getParams();
        $this -> view -> param = $search;


        if(isset($search['name']) && $search['name']!='null'){
         $tmp['name'] = $search['name'];
        }
        if(isset($search['type']) && $search['type']!='null'){
         $tmp['type'] = $search['type'];
        }
        $total = $this->_api->getDataCount($tmp);
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->getData($tmp,'id,name,code,type',null,$page);
        $this->view->data = $data;
        $pageNav = new Custom_Model_PageNav($total);
        $this->view->pageNav=$pageNav->getNavigation();
     }
     /**
      * 广告位列表
      */
      public function listadvertisingAction(){
      	$search = $this->_request->getParams();
        $this -> view -> param = $search;


        if(isset($search['position']) && $search['position']!='null'){
         $tmp['position'] = $search['position'];
        }
        if(isset($search['name']) && $search['name']!='null'){
         $tmp['name'] = $search['name'];
        }

        $total = $this->_api->getAdvCount($tmp);
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->getAdv($tmp,'id,url,imgUrl,isDisplay,position,grade,name,price',null,$page);
        $this->view->data = $data;
        $pageNav = new Custom_Model_PageNav($total);
        $this->view->pageNav=$pageNav->getNavigation();
      }

    /**
     * 更新 文章列表 中可修改的字段
     *
     * @return void
     */
    public function ajaxupdatearticleAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $id = (int)$this->_request->getParam('id', 0);
        $field = $this->_request->getParam('field', null);
        $val = $this->_request->getParam('val', null);
        if ($id > 0) {
            $result = $this->_api->ajaxupdatearticle($id, $field, $val);
            switch ($result) {
            	case 'forbidden':
            	    Custom_Model_Message::showMessage(self::FORBIDDEN, 'event', 1250, 'Gurl()');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
            }
        } else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    /**
     * 更新 数据字典列表 中可修改的字段
     *
     * @return void
     */
    public function ajaxupdatedataAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $id = (int)$this->_request->getParam('id', 0);
        $field = $this->_request->getParam('field', null);
        $val = $this->_request->getParam('val', null);
        if ($id > 0) {
            $result = $this->_api->ajaxupdatedata($id, $field, $val);
            switch ($result) {
            	case 'forbidden':
            	    Custom_Model_Message::showMessage(self::FORBIDDEN, 'event', 1250, 'Gurl()');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
            }
        } else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    /**
     * 删除资讯
     *
     * @return void
     */
    public function delAction(){
        $articleID=$this->_request->getParam('id', null);
        if($this->_api->del($articleID,$error)){
            //exit(self::DEL_SUCCESS);
            exit;
        }else{
            switch ($error) {
                case 'delFail':
                    exit(self::DEL_FAIL);
                    break;
            }

        }
    }
    /**
     * 删除广告位
     *
     * @return void
     */
    public function deldataAction(){
        $articleID=$this->_request->getParam('id', null);
        if($this->_api->deldata($articleID,$error)){
            //exit(self::DEL_SUCCESS);
            exit;
        }else{
            switch ($error) {
                case 'delFail':
                    exit(self::DEL_FAIL);
                    break;
            }

        }
    }
     /**
     * 删除广告位
     *
     * @return void
     */
    public function delAdvAction(){
        $articleID=$this->_request->getParam('id', null);
        if($this->_api->delAdv($articleID,$error)){
            //exit(self::DEL_SUCCESS);
            exit;
        }else{
            switch ($error) {
                case 'delFail':
                    exit(self::DEL_FAIL);
                    break;
            }

        }
    }
     /**
     * 删除广告位
     *
     * @return void
     */
    public function delLinkAction(){
        $articleID=$this->_request->getParam('id', null);
        if($this->_api->delLink($articleID,$error)){
            //exit(self::DEL_SUCCESS);
            exit;
        }else{
            switch ($error) {
                case 'delFail':
                    exit(self::DEL_FAIL);
                    break;
            }

        }
    }
    /**
     * 修改文章表单
     *
     * @return void
     */
    public function editformAction(){
        $id=$this->_request->getParam('id', null);
        $this->view->catTree=$this->_api->catTree();
        $this->view->article=$this->_api->getArticleByID($id);
        $this->view->action='edit';
    }
	/**
     * 修改文章表单
     *
     * @return void
     */
    public function editadvformAction(){
        $id=$this->_request->getParam('id', null);
        $this->view->adv=$this->_api->getAdvByID($id);
        $this->view->action='editadv';
    }
	/**
     * 修改友情链接表单
     *
     * @return void
     */
    public function editlinkformAction(){
        $id=$this->_request->getParam('id', null);
        $this->view->link=$this->_api->getLinkByID($id);
        $this->view->action='editlink';
    }

    /**
     * 文章标签列表
     *
     * @return void
     */
    public function taglistAction(){
      $page = (int)$this -> _request -> getParam('page', 1);
      $search = $this -> _request -> getParams();
      $datas= $this -> _api -> getAllTag($search,$page, 15);
      $this -> view -> taglist =  $datas['list'];
	  $this -> view -> param = $this -> _request -> getParams();
      $pageNav = new Custom_Model_PageNav($datas['total'], 15, 'ajax_search');
      $this -> view -> pageNav = $pageNav -> getNavigation();

    }

	/**
	*添加新标签
	*
	*/
	public function addTagAction(){
		if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $title = $this -> _request -> getParam('title', null);
            $tag = $this -> _request -> getParam('tag', null);
            if($title && $tag){
                $chinput = $this -> _api -> checkinput(" title='$title' or tag = '$tag'");
                if($chinput)  {
                        Custom_Model_Message::showMessage('填写有重复！' , 'event', 1250);
                }
            } else{
               Custom_Model_Message::showMessage('信息填写不完整！', 'event', 1250);
            }
            if($this -> _api -> addTag($this -> _request -> getPost())){
                Custom_Model_Message::showMessage('标签添加成功！', 'event', 1250, 'Gurl()');
            } else {
                Custom_Model_Message::showMessage($this -> _api -> error());
            }
        }else{
             $this -> view -> tagtype = $this->_tagType;
             $this -> view -> action = 'add-tag';
             $this -> render('edit-tag');
        }
	}

    /**
     * 单项标签管理
     *
     * @return void
     */
    public function tagAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> updateTag($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250, 'Gurl()' );
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $tags = $this -> _api -> getTag("tag_id=$id");
                $this -> view -> data = $tags['data'];
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }

    /**
     * 更新 分类列表 中可修改的字段
     *
     * @return void
     */
    public function ajaxupdatetagAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $id = (int)$this->_request->getParam('id', 0);
        $field = $this->_request->getParam('field', null);
        $val = $this->_request->getParam('val', null);
        if ($id > 0) {
            $result = $this->_api->ajaxupdatetag($id, $field, $val);
            switch ($result) {
            	case 'forbidden':
            	    Custom_Model_Message::showMessage(self::FORBIDDEN, 'event', 1250, 'Gurl()');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
            }
        } else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }

   /**
     * 选择商品
     *
     * @return void
     */
    public function selAction()
    {
        $job = $this -> _request -> getParam('job', null);
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        if($job) {
	        $search['filter'] = "";
	        $data = $this->_api->get(array('b.cat_id' => $search['cat_id'], 'title' => $search['title']),'article_id,cat_name,title,author,source,is_view,add_time,a.sort',null,$page);
			foreach($data as $var){
				$articleId[]=$var['article_id'];
			}
            $num=count($articleId);
	        $total = $this->_api->getCount(array('b.cat_id' => $search['cat_id'], 'title' => $search['title']));
	        $this -> view -> datas = $data;

        }else{
	        $this->view->catTree=$this->_api->catTree();
        }
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search_goods');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }


    /**
     * 修改文章
     *
     * @return void
     */
    public function editAction(){
        $post = $this->_request->getPost();
        $post['article']['content'] = $this->_request->getParam('content', null);
        if($this->_api->edit($post['article'],$error)){
            Custom_Model_Message::showMessage(self::EDIT_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/news/list');
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
    }

    /**
     * 修改广告位
     *
     * @return void
     */
    public function editadvAction(){
        $post = $this->_request->getPost();
        if($this->_api->editAdvContent($post['adv'],$error)){
            Custom_Model_Message::showMessage(self::EDIT_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/news/listadvertising');
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
    }
	 /**
     * 修改友情链接
     *
     * @return void
     */
    public function editlinkAction(){
        $post = $this->_request->getPost();
        if($this->_api->editLink($post['link'],$error)){
            Custom_Model_Message::showMessage(self::EDIT_SUCCESS,$this->getFrontController()->getBaseUrl().'/admin/news/listlink');
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
    }
    /**
     * 关联商品
     *
     * @return void
     */
    public function linkgoodsAction(){
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editlinkGoods($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $tags = $this -> _api -> getArticleGoods($id);
                $this -> view -> data = $tags['data'];
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250);
        }
    }

    /**
     * 分类关联商品
     *
     * @return void
     */
    public function linkCatGoodsAction(){
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editlinkCatGoods($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $tags = $this -> _api -> getLinkCatGoods($id);
                $this -> view -> data = $tags['data'];
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250);
        }
    }

    /**
     * 分类关联文章
     *
     * @return void
     */
    public function linkCatArticleAction(){
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editlinkCatArticle($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $tags = $this -> _api -> getLinkCatArticle($id);
                $this -> view -> data = $tags['data'];
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250);
        }
    }

    /**
     * ajax 文章是否推荐
     *
     */
    public function isHotAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$article_id = (int)$this -> _request -> getParam('article_id', 0);
    	$st = (int)$this -> _request -> getParam('st', 0);
    	if($article_id < 0){ exit('参数错误'); }
    	if($st!=0 && $st!=1){ $st = 0; }
    	$rs = $this -> _api -> editArticle(array('id'=>$article_id, 'isTop'=>$st));
    	echo $rs;
    	exit;
    }
	/**
     * ajax 是否隐藏广告位
     *
     */
    public function isDisplayAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$article_id = (int)$this -> _request -> getParam('id', 0);
    	$st = (int)$this -> _request -> getParam('st', 0);
    	if($article_id < 0){ exit('参数错误'); }
    	if($st!=0 && $st!=1){ $st = 0; }
    	$rs = $this -> _api -> editAdv(array('id'=>$article_id, 'isDisplay'=>$st));
    	echo $rs;
    	exit;
    }
    /**
     * ajax 是否隐藏友情链接
     *
     */
    public function isDisplayLinkAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$article_id = (int)$this -> _request -> getParam('id', 0);
    	$st = (int)$this -> _request -> getParam('st', 0);
    	if($article_id < 0){ exit('参数错误'); }
    	if($st!=0 && $st!=1){ $st = 0; }
    	$rs = $this -> _api -> editLink(array('id'=>$article_id, 'display'=>$st));
    	echo $rs;
    	exit;
    }
    /**
     * ajax Position 资讯 设置
     *
     *
     */
	public function selectArrayAction(){
		$cho=$this -> _request -> getParam('cho', null);
		$keywords=$this -> _request -> getParam('keywords', null);
		$ret="[";
		$boo=0;
		if($cho=="ZD"){
			$where=" where type=1 and name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getDataDictionary($where);
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_".$val['code']."',";
				$boo=1;
			}
		}
		if($cho=="ZC"){
			$where=" where name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getNewsClass($where);
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_ZC".$val['id']."',";
				$boo=1;
			}
		}
		else if($cho=="B"){
			$where=" where brand_name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getBrand($where);
			foreach($arrayList as $val){
				$ret.="'".$val['brand_name']."_B".$val['brand_id']."',";
				$boo=1;
			}
		}
		else if($cho=="C"){
			$where=" where  cat_name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getCatgory($where);
			foreach($arrayList as $val){
				$ret.="'".$val['cat_name']."_C".$val['cat_id']."',";
				$boo=1;
			}
		}
		else if($cho=="BC"){
			$pos= strpos($keywords, '-');
			if($pos===false){
					$bkey=$keywords;
					$ckey="";
			}
			else{
				$bkey=substr($keywords,0,$pos);
				$ckey=substr($keywords,$pos+1,strlen($keywords));
			}
			if($keywords==''){
				$arrayList=$this -> _api ->getBrandCatgory();
			}
			else{
				$where=" where  b.brand_name like '%".$bkey."%' and c.cat_name like '%".$ckey."%'";
				$arrayList=$this -> _api ->getBrandCatgoryWhere($where);
			}
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_".$val['id']."',";
				$boo=1;
			}
		}
		else if($cho=="P"){
			$where=" where onsale=0 and is_del=0 and is_gift=0 and goods_name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getGoods($where);
			foreach($arrayList as $val){
				$ret.="'".$val['goods_name']."_P".$val['goods_id']."',";
				$boo=1;
			}
		}

		if($boo==1){
				$ret=substr($ret,0,-1);
			}
			$ret.="]";
		echo $ret;
		exit;
	}

	/**
     * ajax Position 广告位 设置
     *
     *
     */
	public function selectArrayAdvAction(){
		$cho=$this -> _request -> getParam('cho', null);
		$keywords=$this -> _request -> getParam('keywords', null);
		$ret="[";
		$boo=0;
		if($cho=="ZD"){
			$where=" where type=2 and name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getDataDictionary($where);
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_".$val['code']."',";
				$boo=1;
			}
		}
		if($cho=="ZC"){
			$where=" where name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getNewsClass($where);
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_ZC".$val['id']."',";
				$boo=1;
			}
		}else if($cho=="ZCB"){
			$where=" where name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getNewsClass($where);
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_ZCB".$val['id']."',";
				$boo=1;
			}
		}
		else if($cho=="ZCC"){
			$where=" where name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getNewsClass($where);
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_ZCC".$val['id']."',";
				$boo=1;
			}
		}else if($cho=="ZCPF"){
			$where=" where name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getNewsClass($where);
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_ZCPF".$val['id']."',";
				$boo=1;
			}
		}else if($cho=="ZCPS"){
			$where=" where name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getNewsClass($where);
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_ZCPS".$val['id']."',";
				$boo=1;
			}
		}
		else if($cho=="B"){
			$where=" where brand_name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getBrand($where);
			foreach($arrayList as $val){
				$ret.="'".$val['brand_name']."_B".$val['brand_id']."',";
				$boo=1;
			}
		}else if($cho=="BPPC"){
			//品牌城广告位
			$where=" where brand_name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getBrand($where);
			foreach($arrayList as $val){
				$ret.="'".$val['brand_name']."_BPPC".$val['brand_id']."',";
				$boo=1;
			}
		}
		else if($cho=="C"){
			$where=" where  cat_name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getCatgory($where);
			foreach($arrayList as $val){
				$ret.="'".$val['cat_name']."_C".$val['cat_id']."',";
				$boo=1;
			}
		}
		else if($cho=="BC"){
			$pos= strpos($keywords, '-');
			if($pos===false){
					$bkey=$keywords;
					$ckey="";
			}
			else{
				$bkey=substr($keywords,0,$pos);
				$ckey=substr($keywords,$pos+1,strlen($keywords));
			}
			if($keywords==''){
				$arrayList=$this -> _api ->getBrandCatgory();
			}
			else{
				$where=" where  b.brand_name like '%".$bkey."%' and c.cat_name like '%".$ckey."%'";
				$arrayList=$this -> _api ->getBrandCatgoryWhere($where);
			}
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_".$val['id']."',";
				$boo=1;
			}
		}
		else if($cho=="P"){
			$where=" where onsale=0 and is_del=0 and is_gift=0 and goods_name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getGoods($where);
			foreach($arrayList as $val){
				$ret.="'".$val['goods_name']."_P".$val['goods_id']."',";
				$boo=1;
			}
		}

		if($boo==1){
				$ret=substr($ret,0,-1);
			}
			$ret.="]";
		echo $ret;
		exit;
	}

	/**
     * ajax Position 友情链接 设置
     *
     *
     */
	public function selectArrayLinkAction(){
		$cho=$this -> _request -> getParam('cho', null);
		$keywords=$this -> _request -> getParam('keywords', null);
		$ret="[";
		$boo=0;
		if($cho=="ZD"){
			$where=" where type=3 and name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getDataDictionary($where);
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_".$val['code']."',";
				$boo=1;
			}
		}
		if($cho=="ZC"){
			$where=" where name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getNewsClass($where);
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_ZC".$val['id']."',";
				$boo=1;
			}
		}
		else if($cho=="B"){
			$where=" where brand_name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getBrand($where);
			foreach($arrayList as $val){
				$ret.="'".$val['brand_name']."_B".$val['brand_id']."',";
				$boo=1;
			}
		}
		else if($cho=="C"){
			$where=" where  cat_name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getCatgory($where);
			foreach($arrayList as $val){
				$ret.="'".$val['cat_name']."_C".$val['cat_id']."',";
				$boo=1;
			}
		}
		else if($cho=="BC"){
			$pos= strpos($keywords, '-');
			if($pos===false){
					$bkey=$keywords;
					$ckey="";
			}
			else{
				$bkey=substr($keywords,0,$pos);
				$ckey=substr($keywords,$pos+1,strlen($keywords));
			}
			if($keywords==''){
				$arrayList=$this -> _api ->getBrandCatgory();
			}
			else{
				$where=" where  b.brand_name like '%".$bkey."%' and c.cat_name like '%".$ckey."%'";
				$arrayList=$this -> _api ->getBrandCatgoryWhere($where);
			}
			foreach($arrayList as $val){
				$ret.="'".$val['name']."_".$val['id']."',";
				$boo=1;
			}
		}
		else if($cho=="P"){
			$where=" where onsale=0 and is_del=0 and is_gift=0 and goods_name like '%".$keywords."%'";
			$arrayList=$this -> _api ->getGoods($where);
			foreach($arrayList as $val){
				$ret.="'".$val['goods_name']."_P".$val['goods_id']."',";
				$boo=1;
			}
		}

		if($boo==1){
				$ret=substr($ret,0,-1);
			}
			$ret.="]";
		echo $ret;
		exit;
	}
}