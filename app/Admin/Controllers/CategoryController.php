<?php
class Admin_CategoryController extends Zend_Controller_Action 
{
	/**
     * api对象
     */
    private $_api = null;
	const EXISTS = '该分类已存在';
	const ADD_SUCCESS = '添加分类成功!';
	const EDIT_SUCCESS = '编辑分类成功!';
	
	/**
     * 初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Category();
	}
	
	/**
     * 默认动作
     *
     * @return   void
     */
    public function indexAction()
    {      
      
        $datas = $this -> _api -> catTree();    
        if ($datas) {
            foreach ($datas as $num => $data)
            {
            	$datas[$num]['add_time'] = ($datas[$num]['add_time'] > 0) ? date('Y-m-d', $datas[$num]['add_time']) : '';
            	$datas[$num]['update_time'] = ($datas[$num]['update_time'] > 0) ? date('Y-m-d', $datas[$num]['update_time']) : '';
            	$datas[$num]['status'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $datas[$num]['cat_id'], $datas[$num]['cat_status']);
            	$datas[$num]['display'] = $this -> _api -> ajaxDisplay($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('display'), $datas[$num]['cat_id'], $datas[$num]['display']);
            }
        }
        $this -> view -> datas = $datas;
    }
    
    public function productCatAction()
    {
    	$datas = $this -> _api -> productCatTree();    	
    	if ($datas) {
    		foreach ($datas as $num => $data)
    		{
    			$datas[$num]['status'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('statusproduct'), $datas[$num]['cat_id'], $datas[$num]['cat_status']);
    		}
    	}
    	$this -> view -> datas = $datas;
    	
    }
    
	/**
     * 商品展示分类移动
     *
     * @return   void
     */
    public function movecatAction()
    {
        if ($this -> _request -> isPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$from_cat_id = (int)$this -> _request -> getParam('from_cat_id', 0);
			$to_cat_id = (int)$this -> _request -> getParam('to_cat_id', 0);
			if($from_cat_id >0 && $to_cat_id >0 ){
				$this -> _api -> movecat($from_cat_id,$to_cat_id);
				Custom_Model_Message::showMessage('分类移动成功', 'event', 1250,  "Gurl()");
			}else{
				Custom_Model_Message::showMessage('请正确选择要移动的分类和目标分类', 'event', 1250,  "Gurl()");
			}
        } else {
		   //商品分类移动
		   $this -> view -> fromcatSelect = $this -> _api -> buildSelect( array('name' => 'from_cat_id' ,'id' => 'from_cat_id'));
		   $this -> view -> tocatSelect = $this -> _api -> buildSelect(array('name' => 'to_cat_id' ,'id' => 'to_cat_id'));
		}
    }
   
    /**
     * 添加动作
     *      
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> edit($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$pid = (int)$this -> _request -> getParam('pid', null);
        	$this -> view -> action = 'add';
            if (!$pid) {
	            $pid = 0;
	            $data['cat_path'] = ',';
            }else{
	            $pcat = array_shift($this -> _api -> get("cat_id=$pid","cat_name,cat_path"));
	            $data['parent_name'] = $pcat['cat_name'];
	            $data['cat_path'] =  $pcat['cat_path'];
        	}
        	$data['parent_id'] = $pid;
        	$this -> view -> data = $data;
        	$this -> render('edit');
        }
    }
    public function   addproductcatAction()
    {
    	if ($this -> _request -> isPost()) {
    		$result = $this -> _api -> editProductCat($this -> _request -> getPost());
    		if ($result) {
    			Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
    		}else{
    			Custom_Model_Message::showMessage($this -> _api -> error());
    		}
    	} else {
    		$pid = (int)$this -> _request -> getParam('pid', null);
    		$this -> view -> action = 'addproductcat';
    		if (!$pid) {
    			$pid = 0;
    			$data['cat_path'] = ',';
    		}else{
    			$pcat = array_shift($this -> _api -> getProductCat("cat_id=$pid","cat_name,cat_path"));
    			$data['parent_name'] = $pcat['cat_name'];
    			$data['cat_path'] =  $pcat['cat_path'];
    		}
    		$data['parent_id'] = $pid;
    		$this -> view -> data = $data;
    		$this -> render('editproductcat');
    	}
    }
    /**
     * 编辑动作
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
	        	    $this -> _api -> catAliasCache();
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl()");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {              
               
                $this -> view -> action = 'edit';
                $data = array_shift($this -> _api -> get("cat_id=$id"));
                if($data['parent_id'] == 0){
	                $data['cat_path'] =  ',';
                }else{
	                $where = "cat_id=".$data['parent_id'];
	                $pcat = array_shift($this -> _api -> get($where,"cat_name,cat_path"));
	                $data['parent_name'] = $pcat['cat_name'];
                }
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    
    public function editproductcatAction()
    {
    	$id = (int)$this -> _request -> getParam('id', null);
    	if ($id > 0) {
    		if ($this -> _request -> isPost()) {
    			$result = $this -> _api -> editProductCat($this -> _request -> getPost(), $id);
    			if ($result) {
    				$this -> _api -> catAliasCache();
    				Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl()");
    			}else{
    				Custom_Model_Message::showMessage($this -> _api -> error());
    			}
    		} else {    			 
    			$data = array_shift($this -> _api -> getProductCat("cat_id=$id"));
    			if($data['parent_id'] == 0){
    				$data['cat_path'] =  ',';
    			}else{
    				$where = "cat_id=".$data['parent_id'];
    				$pcat = array_shift($this -> _api -> getProductCat($where,"cat_name,cat_path"));
    				$data['parent_name'] = $pcat['cat_name'];
    			}
    			$this -> view -> action = 'editproductcat';
    			$this -> view -> data = $data;
    		}
    	}else{
    		Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
    	}
    }
    /**
     * 分类品牌
     *
     * @return void
     */
    public function brandAction()
    {
	  if ($this -> _request -> isPost()) {
		$post=$this -> _request -> getPost();

		if(count($post['brand_ids'])>0){
			$cat_band=implode(',',$post['brand_ids']);
			$data=array(
				'cat_band'=>$cat_band,
			);
			$this -> _api -> bandcat($data,$post['cat_id']);
		    Custom_Model_Message::showMessage('编辑成功', 'event', 1250, "Gurl()");
		}
	  }else{
			$cat_id = (int)$this -> _request -> getParam('cat_id', 0);
			if($cat_id > 0){
				$catInfo = array_shift($this -> _api -> get("cat_id=$cat_id"));
				$oldBand=explode(',',$catInfo['cat_band']);
				$this->view->cat = $catInfo;
				$this -> _brand = new Admin_Models_API_Brand();
				$datas = $this -> _brand -> get(null,'brand_id,brand_type,brand_name,status');
				foreach($datas as $key=>$var){
					if(in_array($var['brand_id'],$oldBand)){
						$datas[$key]['is_check']=1;
					}
				}
				$this -> view -> brandDatas = $datas;
				$this -> view -> action = 'brand';
			}else{
				exit('未错误');
			}	  
	  }
    }
    /**
     * 删除动作
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
            }
        } else {
            exit('error!');
        }
    }
    /**
     * 更改状态动作
     *
     * @return void
     */
    public function statusAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
	        $this -> _api -> changeStatus($id, $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $id, $status);
    }
    
    public function statusproductAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	 
    	if ($id > 0) {
    		$this -> _api -> changeProudctStatus($id, $status);
    	}else{
    		Custom_Model_Message::showMessage('error!');
    	}
    	echo $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('statusproduct'), $id, $status);
    }
    /**
     * 更改显示状态动作
     *
     * @return void
     */
    public function displayAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
	        $this -> _api -> changeDisplay($id, $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _api -> ajaxDisplay($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('display'), $id, $status);
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
    
    /**
     * ajax更新数据
     *
     * @return void
     */
    public function ajaxproductupdateAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$field = $this -> _request -> getParam('field', null);
    	$val = $this -> _request -> getParam('val', null);
    	if ($id > 0) {
    		$this -> _api -> ajaxProductUpdate($id, $field, $val);
    	} else {
    		exit('error!');
    	}
    }
    
    /**
     * 检查重复值
     *
     * @return void
     */
    public function checkAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val', null);
        
        if(!empty($val)){
	        $result = $this -> _api -> get("$field='$val'",$field);
	        if (!empty($result)){
	        	exit(self::EXISTS);
	        }
        }
    }
    /**
     * 获取下级分类列表
     *
     * @return void
     */
    public function getcatAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        $pid = (int)$this -> _request -> getParam('pid', null);
        $this -> view -> cat_id = $id;
        $this -> view -> pid = $pid;
        $this -> view -> cats = $this -> _api -> get("parent_id=$pid");
    }
    
    /**
     * 获取下级分类列表
     *
     * @return void
     */
    public function getprductcatAction()
    {
    	$id = (int)$this -> _request -> getParam('id', null);
    	$pid = (int)$this -> _request -> getParam('pid', null);
    	$this -> view -> cat_id = $id;
    	$this -> view -> pid = $pid;
    	$this -> view -> cats = $this -> _api -> getProductCat("parent_id=$pid");
    	$this->render('getcat');
    }
    
    
    public function relationAction(){
        
        $id = $this->_request->get('id');
        $type = $this->_request->get('ttype');
        $this->view->id = $id;
        $this->view->type = $type;
        $limit_type = $this->view->limit_type = $this->_request->get('limit_type');
        
        //取出关联数据
        $this->view->list_relation = $this->_api->getRelation($id,$limit_type,$type);
        
    }

    public function relationSaveAction(){
        
        $id = $this->_request->getParam('id');
        $type = $this->_request->getParam('type');
        $limit_type = $this->_request->getParam('limit_type');
        $arr_goods_id = $this->_request->getParam('goods_id');
        $this->_api->saveRelation($id,$limit_type,$type,$arr_goods_id);    
        
        Custom_Model_Message::showMessage('促成成功！', 'event', 1250, 'Gurl()' );
        
    }
    
    public function reflashCacheAction(){
 
        $apiGoods = new Shop_Models_API_Goods();
        $tree_nav_cat = $apiGoods -> getCatNavTree();
        $imgBaseUrl = Zend_Registry::get('config') -> view -> imgBaseUrl;
		/**
		 *以后再加， 
		 */
		$conf_icon = array(
			'女性保健系列'=>'icon_female','男性保健系列'=>'icon_male','老年保健系列'=>'icon_slow','儿童营养补充'=>'icon_child',
			'基础保健系列'=>'icon_basic','亚健康系列'=>'icon_weeker','健康食品系列'=>'icon_food','传统滋补系列'=>'icon_zibu',
			'家用医疗器械'=>'icon_qixie','日化个护'=>'icon_rihua',
		);
		
        $content = '';
        $content1 = '<!--所有商品分类-->';
        foreach ($tree_nav_cat as $k=>$v){

			$icon = $conf_icon[$v['cat_name']];
			
            $content .= '<div class="item">';
			$content .= '<i class="cat-icon '.$icon.'"></i>';
            $content .= '<h3><a href="/gallery-'.$v['cat_id'].'.html" title="'.$v['cat_name'].'" >'.$v['cat_name'].'</a></h3>';
            $content .= '</div>';
            
            $top = '';
            $top = ($k*35-35*2+60).'px';
            $content .= '<div class="sortLayer" style=" display:none;top:'.$top.';">';
            $content .= '<div class="subMain">';
            if(!empty($v['sub'])){
                $content .= '<dl><dd>';
                foreach ($v['sub'] as $kk=>$vv){
                    $content .= '<a href="/gallery-'.$vv['cat_id'].'.html"  title="'.$vv['cat_name'].'" >'.$vv['cat_name'].'</a>';
                }
                $content .= '</dd></dl>';
            }
            $content .= '<div class="sub"><h4>品牌推荐</h4><ul>';
            if(!empty($v['brand_link'])){
                foreach ($v['brand_link'] as $kk=>$vv){
                    $content .= '<li><a target="_blank" href="/b-'.$vv['as_name'].'" title="'.$vv['brand_name'].'">'.$vv['brand_name'].'</a></li>';
                }
            }
            $content .= '</ul></div>';
            $content .= '</div></div>';
        }
        $content .= '<!--end 所有商品分类-->';
        $objFile = new Custom_Model_File();
        $objFile->writefile(SHOP_TPL_ROOT.'_library/catnav.tpl', $content1.$content);        
        Custom_Model_Message::showAlert("更新缓存成功！",true,0);
        
    }
    
    
}