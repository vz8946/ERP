<?php
class Admin_GoodsController extends Zend_Controller_Action
{
    /**
     * 
     * @var Admin_Models_API_Goods
     */
    private $_api = null;
    private $_p_api = null;
	const ADD_SUCCESS = '商品添加成功!';
	const EDIT_SUCCESS = '商品编辑成功!';
	const LINK_SUCCESS = '关联商品添加成功!';
    const LINK_ARTICLE_SUCCESS = '关联文章编辑成功!';
	const TAG_SUCCESS = '标签编辑成功!';
	const ATTR_SUCCESS = '添加属性成功!';
	const IMPORT_SUCCESS = '商品资料导入成功!';
	const IMG_SUCCESS = '商品图片保存成功!';
    /**
     * 允许操作的管理员列表
     * @var array
     */
     private $_allowDoList = array ('1');   

	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _cat = new Admin_Models_API_Category();
		$this -> _api = new Admin_Models_API_Goods();
		$this -> _p_api = new Admin_Models_API_Product();
        $this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
	}
	/**
     * 预处理
     *
     * @return   void
     */
	public function postDispatch()
    {
	    $search = $this -> _request -> getParams();	
        if(!isset($search['is_del'])){
              $search['is_del']='0';
        }
        
    	$action = $this -> _request -> getActionName();
        if (in_array($action, array('index', 'img-list', 'price-list', 'link-list', 'goods-url-alias'))) {
	        $page = (int)$this -> _request -> getParam('page', 1);
	        $datas = $this -> _api -> get($search,'a.goods_id,goods_name,goods_style,goods_sn,goods_sort,goods_img,market_price,price,staff_price,cost,cost_tax,invoice_tax_rate,onsale,onoff_remark,c.cat_id,a.url_alias,a.limit_number,is_del,price_limit',$page,null,$search['orderby']);
	        $total = $this -> _api -> getCount();
	        $this -> view -> datas = $datas;
	    
	        $this -> view -> catSelect = $this -> _cat ->buildSelect(array('name' => 'cat_id','selected'=>$search['cat_id']));     
	        $this -> view -> param = $this -> _request -> getParams();
	        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
	        $this -> view -> pageNav = $pageNav -> getNavigation();
	        $this -> view -> message = $message;
        }
    }
	/**
     * 商品资料管理
     *
     * @return   void
     */
    public function indexAction()
    {
       
    }
    
	/**
     * 商品图片管理
     *
     * @return   void
     */
    public function imgListAction()
    {

    }
    
    
    /**
     * 获取淘宝图片 临时方法
     */
    public  function fetchTaobaoAction()
    {
    	set_time_limit(0);
    	$db =  Zend_Registry::get('db');    
    	$goods_id = (int) $this->_request->getParam('goods_id',0);
    	
    	$sqlwhere = "  description LIKE  '%taobaocdn.com%' AND goods_id>$goods_id";
    	$total =  $db-> fetchOne("SELECT count(*) as count FROM shop_goods WHERE $sqlwhere");
        $pagesize = 100;
        $pageNum  = ceil($total/$pagesize);
      
       
        if($total == 0) die("采集已完成");        
        
        $offset = 0;
        $limit = "LIMIT $offset,$pagesize";
        $goodsData = $db->fetchAll("SELECT  goods_id, description  FROM shop_goods WHERE $sqlwhere ORDER BY goods_id ASC $limit");
        foreach($goodsData as $val){
        		$imgs = array();
        		preg_match_all("/src=\"([^\"]+)/isu", $val['description'], $imgs);
        		$arr  = $this->saveTaobaoImages($imgs[1],$val['goods_id']);
        		if ($arr) {
        		    $set = array ('description' =>str_replace(array_keys($arr),array_values($arr),$val['description']));
        		    $where = $db->quoteInto('goods_id = ?', $val['goods_id']);
			        $res =  $db->update('shop_goods', $set, $where);
        		}    
        		$goods_id = $val['goods_id'];
        		//echo  '第'.$goods_id.'/'.$pageNum.'页执行商品【goods_id：'.$val['goods_id'].'】->> \r\n   ';
        		echo $val['goods_id']." \r\n ";
        		ob_end_flush();ob_implicit_flush(1); 
        }  
       
        $jumpurl = "/admin/goods/fetch-taobao/goods_id/".$goods_id;
        echo '<meta http-equiv="refresh" content=3;url='.$jumpurl.'>';exit('等待进入下一页');      	 
    }
    
    function saveTaobaoImages($imgs,$good_id)
    {    
    	$arr = array();
    	foreach ($imgs as $url)
    	{
    		$res =  $this->saveImageToLocal($url);
    		if(!$res)
    		{
    		   error_log("产品id:{$good_id}\r\n", 3, SYSROOT.'/www/taobao_img_error.log');
    		}else{
    			$arr[$url] = $res;
    			echo  $res.'<br/>'; ob_flush();
    		}    		
    		
    	}
    	return $arr;
    }
    
    //保存远程图片
    function saveImageToLocal($url){
    	
    	if(strncasecmp($url,'http',4)!=0){
    		return false;
    	}
    	
    	$opts = array(
    			'http'=>array(
    					'method' => "GET",
    					'timeout' => 30, //超时30秒
    					'user_agent'=>"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)"
    			)
    	);
    	
    	$context = stream_context_create($opts);
    	$file_content = @file_get_contents($url, false, $context);
    	
    	$i = pathinfo($url);
    	$dirname = preg_replace('~http://(.*)\.taobaocdn\.com~','',$i['dirname']);
    	$replace = array('http://tu.taobaocdn.com','http://img.taobaocdn.com','http://img01.taobaocdn.com','http://img02.taobaocdn.com','http://img03.taobaocdn.com','http://img04.taobaocdn.com','http://img05.taobaocdn.com');
    	$dirname =  str_replace($replace,'',$dirname);
    	$savePath = "/upload".date('/Y/md/H/');
    	$file_path = SYSROOT."/www".$savePath;
    	@mkdir($file_path,0777,true);
    	if(!in_array($i['extension'],array('jpg','jpeg','gif','png'))){
    		$i['extension'] = 'jpg';
    	}
    	$file_name = uniqid().'.'.$i['extension'];
    	//本地存储
    	$res = false;
    	if($file_content){
    		$res = file_put_contents($file_path.$file_name, $file_content);
    	}    		 
    	if($res){
    		return $savePath.$file_name;
    	}else{
    		return false;
    	}
    }
    
	/**
     * 商品价格管理
     *
     * @return   void
     */
    public function priceListAction()
    {
        $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        if(in_array($auth['admin_id'],$this -> _allowDoList)){
           $this -> view -> viewcost = '1';
        }
    }
    
	/**
     * 商品关联管理
     *
     * @return   void
     */
    public function linkListAction()
    {

    }
    
    /**
     * 商品URL别名管理
     *
     * @return   void
     */
    public function goodsUrlAliasAction()
    {
        
    }
   
	/**
     * 商品状态管理
     *
     * @return   void
     */
    public function goodsStatusAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $params = $this -> _request -> getParams();
        $datas = $this -> _api -> get($params,'goods_id,goods_name,goods_style,goods_sn,goods_sort,goods_img,market_price,price,staff_price,cost,onsale,onoff_remark,c.cat_id,a.product_id',$page);
        $stockAPI = new Admin_Models_API_Stock();
        foreach ($datas as $num => $data)
        {
        	$datas[$num]['status'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $datas[$num]['goods_id'], $datas[$num]['onsale']);
	        $datas[$num]['first_char'] = substr($data['goods_sn'], 0, 1);
        	$goodsArray[$data['goods_id']] = $datas[$num];
        	$productIDArray[] = $data['product_id'];
        }
        if ($goodsArray){
            $productStock = $stockAPI -> getSaleProductOutStock(array('product_id' => $productIDArray));
            if ($productStock) {
                foreach ($productStock as $stock) {
                    $stockData[$stock['product_id']] = $stock;
                }
            }
            foreach ($goodsArray as $goods_id => $data) {
                $goodsArray[$goods_id]['able_number'] = $stockData[$data['product_id']]['able_number'];
                $goodsArray[$goods_id]['real_number'] = $stockData[$data['product_id']]['real_number'];
                $goodsArray[$goods_id]['hold_number'] = $stockData[$data['product_id']]['hold_number'];
                $goodsArray[$goods_id]['cost_amount'] = $goodsArray[$goods_id]['real_number']* $goodsArray[$goods_id]['cost'];
            }
        }
        $total = $this -> _api -> getCount();
        $this -> view -> datas = $goodsArray;
        $this -> view -> catSelect = $this -> _cat ->buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> param = $params;
        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
	/**
     * 商品分类
     *
     * @return   void
     */
    public function goodsCatAction()
    {
        if ($this -> _request -> isPost()) {
			$view_cat_id = (int)$this -> _request -> getParam('view_cat_id', null);
			$goods_sn = $this -> _request -> getParam('goods_sn', null);
			if(!$view_cat_id){
				Custom_Model_Message::showMessage('请选择你要加商品的前台展示分类');
			}
			if(!$goods_sn){
				Custom_Model_Message::showMessage('请选择你要加商品的商品编码');
			}
			if (!$this -> _api -> checkNewGoodsSN($goods_sn)) {
			    Custom_Model_Message::showMessage($this -> _api -> error());
			}
			Custom_Model_Message::showMessage('选择完成，请添加商品信息', "/admin/goods/add/view_cat_id/{$view_cat_id}/goods_sn/{$goods_sn}", 1250);
        } else {
			$this -> view -> viewcatSelect = $this -> _cat -> buildSelect(array('name' => 'view_cat_id'));
		}
    }
    /**
     * 添加动作
     *
     * @return void
     */
    public function addAction()
    {
        $goods_sn = $this -> _request -> getParam('goods_sn', null);
		$view_cat_id = (int)$this -> _request -> getParam('view_cat_id', null);
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> add($this -> _request -> getPost());
        	if ($result) {
        	    
        	    //更新商品as_name
        	    $this->_api->updateAsnameById($result);
        	    
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, 'event', 1250, "Gurl()");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
			$view_cat = array_shift($this -> _cat -> get("cat_id=$view_cat_id"));

        	$this -> view -> action = 'add';
			$this -> view -> view_cat = $view_cat;
			$this -> view -> goods_sn = $goods_sn;
        	//多重分类选择
        	$this -> view -> viewcatSelect = $this -> _cat -> buildSelect( array('name' => 'other_cat_id[]' ,'id' => 'other_cat_id'));
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
                $post = $this -> _request -> getPost();
                $result = $this -> _api -> edit($post, $id);
	        	if ($result) {
	        	    $this -> _api -> goodsAliasCache();
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl()");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {

                $data = array_shift($this -> _api -> get("a.goods_id='$id'", "a.*,c.cat_id"));
                $this -> view -> action = 'edit';
                $cat_row= array_shift($this -> _cat -> get(" cat_id =  $data[cat_id]"));
				$data['cat_name'] =$cat_row['cat_name'];

                $view_cat_row= array_shift($this -> _cat -> get(" cat_id =  $data[view_cat_id]"));
				$data['view_cat_name'] =$view_cat_row['cat_name'];

                $this -> view -> data = $data;
                $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
                $this -> view -> catViewSelect = $this -> _cat -> buildSelect(array('name' => 'view_cat_id'));
            	//多重分类
            	$other_cat_ids = $this -> _api -> getGoodsInCat($id);
            	$tmp = '';
            	foreach ($other_cat_ids as $val){
            		$tmp .= $this -> _cat -> buildSelect(array('name' => 'other_cat_id[]' ,'id' => 'other', 'selected' => $val['cat_id']));
            	}
            	$this -> view -> selectedOtherCat = $tmp;
        		$this -> view -> viewcatSelect = $this -> _cat -> buildSelect(array('name' => 'other_cat_id[]' ,'id' => 'other_cat_id'));
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 编辑动作
     *
     * @return void
     */
    public function priceAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $postdata = $this -> _request -> getPost();
                $result = $this -> _api -> updatePrice($postdata, $id);
                if ($result) {
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
                if(in_array($auth['admin_id'],$this -> _allowDoList)){
                   $this -> view -> viewcost = '1';
                }
                $data = array_shift($this -> _api -> get("a.goods_id='$id'", "a.*,c.price_limit"));
                $data['price_seg'] = unserialize($data['price_seg']);
                $old_value = array(
							    	'cost' => $data['cost'],
							    	'cost_tax' => $data['cost_tax'],
							    	'invoice_tax_rate' => $data['invoice_tax_rate'],
							    	'market_price' => $data['market_price'],
							    	'price' => $data['price'],
							    	'price_seg' => $data['price_seg'],
							    	);
                $this -> view -> data = $data;
                $this -> view -> old_value = Zend_Json::encode($old_value);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
	/**
	*商品标签管理
	*
	*/
	public function goodsTagAction(){
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas= $this -> _api -> getAllTag($search,$page, 20);
        $this -> view -> taglist =  $datas['list'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total'], 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
	}
	/**
	*添加商品新标签
	*
	*/
	public function addTagAction(){
		if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $title = $this -> _request -> getParam('title', null);
            $tag = $this -> _request -> getParam('tag', null);
            if($title && $tag){
                $chinput = $this -> _api -> checkinput(" title='$title'");
                if($chinput)  {
                     Custom_Model_Message::showMessage('填写有重复！' , 'event', 1250);
                }
            } else{
               Custom_Model_Message::showMessage('信息填写不完整！', 'event', 1250);
            }
            if($this -> _api -> addTag($this -> _request -> getPost())){
                Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250, 'Gurl()');
            } else {
                Custom_Model_Message::showMessage($this -> _api -> error());
            }
        }else{
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
        $id = (int)$this -> _request -> getParam('id', 0);
		$type = $this -> _request -> getParam('type', 'goods');
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
				$result = $this -> _api -> updateTag($this -> _request -> getPost(),$id,$type);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250, 'Gurl()' );
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
				$tags = $this -> _api -> getTag("tag_id=$id",$type);
                $this -> view -> data = $tags['data'];
				$this->view->type = $type;
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
	/**
     * 商品导出动作
     *
     * @return   void
     */
    public function exportAction()
    {
    	$opt_api = new Admin_Models_API_OpLog();
    	$opt_api->addopt($this ->_auth['admin_id'],"goods-export");
    	$this -> _helper -> viewRenderer -> setNoRender();
        $this -> _api -> export($this -> _request -> getParams());
        exit;
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
        $value = (int)$this -> _request -> getParam('value', 0);
        if ($id > 0) {
            $result = $this -> _api -> deleteGoods($id,$value);
            if(!$result) {
        	    exit($this -> _api -> error());
            }
            exit($result);
        } else {
            exit('error!');
        }
    }
    
    /**
     * 删除关联商品
     *
     * @return void
     */
    public function deletelinkAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = $this -> _request -> getParam('id', null);
		$type = $this -> _request -> getParam('type', null);
        if ((int)$id > 0) {
            $result = $this -> _api -> deleteLink((int)$id,$type);
			exit($result);
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
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	$remark = $this -> _request -> getParam('remark', '');
    	if ($id > 0) {
    		if ($status == 0) {
		        $this -> _helper -> viewRenderer -> setNoRender();
		        $this -> _api -> changeStatus($id, $status, $remark);
		        exit('refresh');
	        }else{
	            if ($this -> _request -> isPost()) {
	            	$this -> _helper -> viewRenderer -> setNoRender();
	            	$this -> _api -> changeStatus($id, $status, $remark);
	            	Custom_Model_Message::showMessage('操作成功', 'event', 1250, "Gurl('refresh')");
	            }
	        }
        }else{
            Custom_Model_Message::showMessage('error!');
        }
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
        $type = $this -> _request -> getParam('type', null);
        if ($id > 0) {
            $this -> _api -> ajaxUpdate($id, $field, $val, $type);
        } else {
            exit('error!');
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
		$tp = $this -> _request -> getParam('type', null);
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
		$t=null;
		//如果$tp==2则为组合商品查询	
			if(($tp&&$tp==2)&&($job&&$job=="group")){
			$datas=$this->_api->getgroup($page,$search,'group_id,group_sn,group_goods_name,group_price,price_limit,group_stock_number,status,group_specification');
			$t=2;
			$this->view->datas=$datas;
			}else{
                if ($job&&$job=="search") {
                    $search['filter'] = "";
                    $datas = $this -> _api -> get($search,'a.goods_id,goods_units,goods_name,goods_style,goods_sn,goods_sort,market_price,price,cost,onsale,a.sort_sale,onoff_remark,c.cat_id,c.product_id,c.product_sn,c.goods_style',$page);
                    foreach($datas as $var){
                        $productIDArray[] = $var['product_id'];
                        $goodsProductMap[$var['product_id']] = $var['goods_id'];
                    }
                    if (is_array($productIDArray)){
                        $stockAPI = new Admin_Models_API_Stock();
                        $stocks = $stockAPI -> getSaleProductStock($productIDArray);
                        if ($stocks) {
                            foreach($stocks as $productID => $stock){
                                $goodsArray[$goodsProductMap[$productID]]['able_number'] = $stock['able_number'];
                            }
                        }
                    }
                    foreach ($datas as $key => $val) {
                        $datas[$key]['store']=$goodsArray[$val['goods_id']]['able_number'];
                    }
                    $this -> view -> datas = $datas;
                }else{
                    $this -> view -> catSelect = $this -> _cat -> buildSelect(array('name' => 'view_cat_id'));
                }
	    }

		$total = $this -> _api -> getCount($t);
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total,null, 'ajax_search_goods');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }

    /**
     * 关联商品
     *
     * @return void
     */
    public function linkAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
		$type=(int)$this -> _request -> getParam('type', null);
        if ($this -> _request -> isPost()) {
            $result = $this -> _api -> addLink($this -> _request -> getPost(), $id,$type);
            if($result){
                Custom_Model_Message::showMessage(self::LINK_SUCCESS, 'event', 1250);
            }else{
            	Custom_Model_Message::showMessage($this -> _api -> error());
            }
        }else{
			$this->view->type=$type;
            $this -> view -> links = $this -> _api -> getLink($id,$type);
        }
    }
    
    /**
     * 关联文章
     *
     * @return void
     */
    public function linkarticleAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editlinkArticle($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::LINK_ARTICLE_SUCCESS, 'event', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $tags = $this -> _api -> getLinkArticle($id);
                $this -> view -> data = $tags['data'];
                $this -> view -> tags = $tags['details'];
                $this -> view -> num = count($tags['details']);
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250);
        }
    }

    /**
     * 商品关键字管理 
     * 
     * 列表
     */
    public function goodsKeywordsAction() {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> get($search,'a.goods_id,goods_name,goods_style,goods_sn,goods_sort,goods_img,market_price,price,staff_price,cost,onsale,onoff_remark,c.cat_id',$page,null,$search['orderby']);
        $total = $this -> _api -> getCount();
        $this -> view -> datas = $datas;
        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> message = $message;
    }
    
    /**
     * ajax商品关键字查看
     * 
     */
    public function viewkeywordsAction() {
    	$goods_id = $this -> _request -> getParam('goods_id', null);
    	if($goods_id>0){
    		$data = $this -> _api -> getKeywords($goods_id);
    		if($data){//修改
    			$this -> view -> data = $data;
    		}else{//添加
        		$data = $this -> _api -> getOne($goods_id, 'goods_id,goods_sn');
        		$data['goods_id'] =trim($data['goods_id']);
        		$data['goods_sn'] =trim($data['goods_sn']);
        		//先插入一遍数据库
        		$arr = array('goods_id' => $data['goods_id'], 'keywords' => addslashes($data['goods_sn']));
        		$msg = $this -> _api -> addkeywords($arr);
        		$this -> _api -> updateDict($keywords);//更新词库
        		$this -> view -> data = $this -> _api -> getKeywords($goods_id);
    		}
    	}else{
    		return false;
    	}
    }
    
    /**
     * ajax添加或修改商品关键字
     * 
     */
    public function editkeywordsAction() {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    	$goods_id = $this -> _request -> getParam('goods_id', null);
    	$keywords = $this -> _request -> getParam('keywords', null);
    	if($goods_id && $keywords){
    		$arr = array('goods_id' => $goods_id, 'keywords' => addslashes(trim($keywords)));
    		$this -> _api -> editkeywords($arr);
    		$this -> _api -> updateDict($keywords);//更新词库
    		Custom_Model_Message::showMessage('修改关键字成功');
    	}else{
    		exit('参数错误');
    	}
    	exit;
    }
    
    /**
     * 词库查看
     * 
     */
    public function dictAction() {
    	$dic = $this -> _api -> readDict();
    	if(is_array($dic) && count($dic)){
    		$pagesize = 20*7;//20行，7列
	    	$p = $this -> _request -> getParam('p', 1);//页码
	    	$count = count($dic);//总数
	    	$pagenum = (int)(($count/$pagesize) + 1);//页数
	    	for($i=0;$i<$pagenum;$i++){$pagenav[] = $i+1;}
	    	$start=($p-1)*$pagesize;//开始记录行
			$end=$start+$pagesize;//结束记录行
	    	//分页
	    	foreach ($dic as $k => $v){
	    		if($k>=$start && $k<$end){
	    			$rs[] = $v;
	    		}
	    	}
    	}else{
    		$count = null;
    		$rs = null;
    	}
	  	$this -> view -> total = $count;
	  	$this -> view -> p = $p;
	  	$this -> view -> pagenum = $pagenum;
	  	$this -> view -> pagenav = $pagenav;
	  	$this -> view -> dic = $rs;
    }
    /**
     * 添加关键词到词库
     * 
     */
    public function dictaddwordsAction() {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    	$keywords = $this -> _request -> getParam('keys', null);
    	if($keywords){
    		$this -> _api -> updateDict($keywords);//更新词库
			Custom_Model_Message::showMessage('添加成功', '/admin/goods/dict', 1250);
		}else{
			Custom_Model_Message::showMessage('请填写关键词', '/admin/goods/dict', 1250);
		}
    }
    /**
     * ajax修改词条
     * 
     */
    public function dicteditAction() {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    	$v = $this -> _request -> getParam('v', null);
    	$ov = $this -> _request -> getParam('ov', null);
    	$v = trim($v); $ov = trim($ov);
    	if($v && $v!=$ov){//更新
    		$rs = $this -> _api -> editKeywordDict($v,$ov);
    		if($rs) echo 'ok';
    		else echo 'error';
    	}else{//为空，则删除
    		$rs = $this -> _api -> delKeywordDict($ov);
    		if($rs) echo 'refresh';
    		else echo 'error';
    	}
    	exit;
    }
    /**
     * 用户搜索统计列表
     * 
     */
    public function customerSearchTjAction() {
    	$page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> getCustomerSearch($search,$page,50);
        $this -> view -> datas = $datas['datas'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['tot'], 50, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
	/**
	 * ajax 添加客户搜索词词到词库
	 * 
	 */
    public function addCustomerSearchwordToDictAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = $this -> _request -> getParam('val', '');
    	$id = (int)$this -> _request -> getParam('id', 0);
    	if($val!='' && $id>0){
    		$this -> _api -> updateDict($val);
    		$this -> _api -> updateCustomerSearchword(array('status'=>2),"id=$id");
    		exit();
    	}else{
    		exit('参数错误');
    	}
    }
	/**
	 * 批量添加到词库
	 * 
	 */
    public function batchAddDictAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
        $params = $this -> _request -> getParams();
    	if (count($params['ids']) > 0) {
    		foreach($params['ids'] as $k => $v){
                $temp = explode('_',$v);
                $this -> _api -> updateDict($temp['1']);
                $this -> _api -> updateCustomerSearchword(array('status'=>2),"id=$temp[0]");
    		}
    	} else {
    		exit;
    	}
    }
	/**
	 * 批量删除搜索词
	 * 
	 */
    public function batchDelSearchwordAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
        $params = $this -> _request -> getParams();
    	if (count($params['ids']) > 0) {
    		foreach($params['ids'] as $k => $v){
                $temp = explode('_',$v);
                $this -> _api -> delOneCustomerSearchword($temp[0]);
    		}
    	} else {
    		exit;
    	}
    }
    /**
     * ajax 删除客户搜索词
     * 
     */
    public function delCustomerSearchwordAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	if($id>0){
    		$this -> _api -> delOneCustomerSearchword($id);
    		exit();
    	}else{
    		exit('参数错误');
    	}
    }

    /**
     * 商品单页标签
     * 
     */
    public function viewTagAction(){
          $page = (int)$this -> _request -> getParam('page', 1);
          $search = $this -> _request -> getParams();
          $datas= $this -> _api -> getAllViewTag($search,$page, 20);
          $this -> view -> taglist =  $datas['list'];
          $this -> view -> param = $search;
          $pageNav = new Custom_Model_PageNav($datas['total'], 20, 'ajax_search');
          $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    /**
     * 商品单页标签添加
     * 
     */
    public function addViewTagAction(){
		if ($this -> _request -> isPost()) {
            $title = $this -> _request -> getParam('title', null);
            $tag = $this -> _request -> getParam('tag', null);
            if($this -> _api -> editViewTag($this -> _request -> getPost())){
                Custom_Model_Message::showMessage(self::ATTR_SUCCESS, 'event', 1250, 'Gurl()');
            } else {
                Custom_Model_Message::showMessage($this -> _api -> error());
            }
        }else{
             $this -> view -> action = 'add-view-tag';  
             $this -> render('edit-view-tag');
        }
    }

    /**
     * 商品单页标签编辑
     * 
     */
    public function editViewTagAction(){

        $id = (int)$this -> _request -> getParam('id', null);
		$tp = (int)$this -> _request -> getParam('type', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editViewTag($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::TAG_SUCCESS, 'event', 1250, 'Gurl()' );
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
				if($tp&&$tp==1){
                 $result = $this -> _api -> getViewTag("id=$id ");
				}
				if($tp&&$tp==2){
				 $result = $this -> _api -> getViewTag("id=$id ",2);
				}
                 $this -> view -> data =  $result['data'];
                 $this -> view -> goods = $result['details'];
                 $this -> view -> action = 'edit-view-tag';  
                 $this -> render('edit-view-tag');
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    /**
     * 商品单页标签删除
     * 
     */
    public function delViewTagAction(){
		$id = (int)$this -> _request -> getParam('id', 0);
    	if($id<1){exit('noid');}
    	$g = $this -> _api -> delViewTag($id);
    	if($g){ exit('ok'); }
    	else{ exit('no'); }
    }
    
    /**
     * 生成baidu商品xml
     * 放在www文件夹下，名称为：baidu.xml
     */
    public function createBaiduXmlAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$datas = $this -> _api -> getGoodsForBaidu(array('onsale'=>'on'),'goods_id,goods_sn,goods_name,view_cat_id,goods_style,price,goods_img,goods_units,brief,goods_update_time,goods_alt');
    	$xml = '<?xml version="1.0" encoding="UTF-8"?><urlset>';
    	foreach ($datas['datas'] as $v){
    		$xml .= '<url>';
    		$xml .= '<loc>http://www.1jiankang.com/goods-'.$v['goods_id'].'.html</loc>';
    		$xml .= '<lastmod>'.date('Y-m-d',$v['goods_update_time']).'</lastmod>';
    		$xml .= '<changefreq>yearly</changefreq>';
    		$xml .= '<priority>1.0</priority>';
    		$xml .= '<data><display>';
    		$xml .= '<title>'.strip_tags(str_replace('&', '', $v['goods_name'])).'</title>';
    		$xml .= '<price>'.$v['price'].'</price>';
    		$xml .= '<brand>'.str_replace('&', '', $v['brand_name']).'</brand>';
    		$tags = ($v['tags'])?$v['tags']:'垦丰\\种子';
    		$xml .= '<tags>'.str_replace('\人群大类', '', $tags).'</tags>';
    		$xml .= '<services>100%正品保证\满百包邮\30天退换货\支持货到付款</services>';
    		$xml .= '<image>http://jiankang/'.str_replace('.', '_180_180.', $v['goods_img']).'</image>';
    		$xml .= '<store>垦丰电商</store>';
    		$xml .= '<stock>0</stock>';
    		$xml .= '<description>'.strip_tags(str_replace(' ', '', str_replace('\n', '', str_replace('&', '',str_replace('&nbsp;', '',  $v['brief']))))).'</description>';
    		$xml .= '</display></data>';
    		$xml .= '</url>';
    	}
    	$xml .= '</urlset>';
    	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/baidu.xml', $xml);
    	echo '生成百度xml成功';exit;
    }
 
    /**
     * 商品状态修改记录
     * 
     */
    public function statusHistoryAction(){
		$goods_id = (int)$this -> _request -> getParam('goods_id', 0);
    	if (!$goods_id) exit;
    	$this -> view -> goods = $this -> _api -> getOne($goods_id);
    	$history = $this -> _api -> getOp("goods_id = {$goods_id} and op_type ='onoff'");
    	if ($history) {
    	    foreach ($history as $key => $item) {
    	        if ($item['old_value'] == '0') {
    	            $history[$key]['status'] = '下架';
    	        }
    	        else {
    	            $history[$key]['status'] = '上架';
    	        }
    	    }
    	}
    	$this -> view -> history = $history;
    }
    
    

    /**
     * 商品图片管理
     * 
     */
    public function imgAction(){
    	
        $goods_id = (int)$this -> _request -> getParam('id', 0);
        if (!$goods_id) exit;
        $goods = $this -> _api -> getOne($goods_id);
        if ($this -> _request -> isPost()) {
            $result = $this -> _api -> upimg($this -> _request -> getPost(), $goods_id, $goods['goods_sn']);
//             if(!$result){
//             	Custom_Model_Message::showMessage('shibai', 'event', 1250, "Gurl('refresh')");
//             }
            //$this -> _api -> updateGoodsImage($goods_id, $this -> _request -> getPost('img_ids'));
            Custom_Model_Message::showMessage(self::IMG_SUCCESS, 'event', 1250, "Gurl('refresh')");
        }
        $this -> view -> data = $goods;
        $product_id = $goods['product_id'];
        
        if ($goods['goods_img_ids']) {
            $tempArr = explode(',', $goods['goods_img_ids']);
            foreach ($tempArr as $img_id) {
                $img_ids[$img_id] = 1;
            }
        }
        $productAPI = new Admin_Models_API_Product();
        $this -> view -> img_url = $productAPI -> getImg("product_id='$product_id' and img_type=2");
        $this -> view -> img_ext_url = $productAPI -> getImg("product_id='$product_id' and img_type=3");
        $this -> view -> img_ids = $img_ids;
        $this -> view -> goods_sn = $goods['goods_sn'];
    }
    /**
     * 删除产品细节/展示图片
     *
     * @return void
     */
    public function deleteimgAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = $this -> _request -> getParam('id', null);
    	$goods_id = $this -> _request -> getParam('goods_id', null);
    	$product_id = $this -> _request -> getParam('product_id', null);
    	if ((int)$id > 0) {
    		$result = $this -> _p_api -> deleteImg((int)$id);
    		$this->_api->updImg($product_id,$goods_id);
    		exit;
    		if(!$result) {
    			exit($this -> _p_api -> error());
    		}
    	} else {
    		exit('error!');
    	}
    }


    /**
     * 编辑器上传图片管理
     * 
     */

    public function uploadImageAction()
    {
		$this -> _helper -> viewRenderer -> setNoRender();
        $save_path = 'upload/kindeditor/';
        $save_url = '/upload/kindeditor/';
        $ext_arr = array(
        	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
        	'flash' => array('swf', 'flv'),
        	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
        	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        
        $max_size = 1000000;
        
        $save_path = realpath($save_path) . '/';
        
        if (!empty($_FILES['imgFile']['error'])) {
        	switch($_FILES['imgFile']['error']){
        		case '1':
        			$error = '超过php.ini允许的大小。';
        			break;
        		case '2':
        			$error = '超过表单允许的大小。';
        			break;
        		case '3':
        			$error = '图片只有部分被上传。';
        			break;
        		case '4':
        			$error = '请选择图片。';
        			break;
        		case '6':
        			$error = '找不到临时目录。';
        			break;
        		case '7':
        			$error = '写文件到硬盘出错。';
        			break;
        		case '8':
        			$error = 'File upload stopped by extension。';
        			break;
        		case '999':
        		default:
        			$error = '未知错误。';
        	}
        	
        	kindEditorAlert($error);
        }
        
        if (empty($_FILES) === false) {
        	$file_name = $_FILES['imgFile']['name'];
        	$tmp_name = $_FILES['imgFile']['tmp_name'];
        	$file_size = $_FILES['imgFile']['size'];
        	if (!$file_name) {
        		kindEditorAlert("请选择文件。");
        	}
        	if (@is_dir($save_path) === false) {
        		kindEditorAlert("上传目录不存在。");
        	}
        	if (@is_writable($save_path) === false) {
        		kindEditorAlert("上传目录没有写权限。");
        	}
        	if (@is_uploaded_file($tmp_name) === false) {
        		kindEditorAlert("上传失败。");
        	}
        	if ($file_size > $max_size) {
        		kindEditorAlert("上传文件大小超过限制。");
        	}
        	$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
        	if (empty($ext_arr[$dir_name])) {
        		kindEditorAlert("目录名不正确。");
        	}
        	$temp_arr = explode(".", $file_name);
        	$file_ext = array_pop($temp_arr);
        	$file_ext = trim($file_ext);
        	$file_ext = strtolower($file_ext);
        	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
        		kindEditorAlert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
        	}
        	if ($dir_name !== '') {
        		$save_path .= $dir_name . "/";
        		$save_url .= $dir_name . "/";
        		if (!file_exists($save_path)) {
        			mkdir($save_path);
        		}
        	}
        	$ymd = date("Ymd");
        	$save_path .= $ymd . "/";
        	$save_url .= $ymd . "/";
        	if (!file_exists($save_path)) {
        		mkdir($save_path);
        	}
        	$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
        	$file_path = $save_path . $new_file_name;
        	if (move_uploaded_file($tmp_name, $file_path) === false) {
        		kindEditorAlert("上传文件失败。");
        	}
        	@chmod($file_path, 0644);
        	$file_url = $save_url . $new_file_name;
            
        	header('Content-type: text/html; charset=UTF-8');
        	echo Zend_Json::encode(array('error' => 0, 'url' => $file_url));
        	exit;
        }
    }
    
    public function fileManagerAction()
    {
		$this -> _helper -> viewRenderer -> setNoRender();
        $root_path = 'upload/kindeditor/';
        $root_url = '/upload/kindeditor/';
        $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
        $dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
        if (!in_array($dir_name, array('', 'image', 'flash', 'media', 'file'))) {
        	echo "Invalid Directory name.";
        	exit;
        }
        if ($dir_name !== '') {
        	$root_path .= $dir_name . "/";
        	$root_url .= $dir_name . "/";
        	if (!file_exists($root_path)) {
        		mkdir($root_path);
        	}
        }
        if (empty($_GET['path'])) {
        	$current_path = realpath($root_path) . '/';
        	$current_url = $root_url;
        	$current_dir_path = '';
        	$moveup_dir_path = '';
        } 
        else {
        	$current_path = realpath($root_path) . '/' . $_GET['path'];
        	$current_url = $root_url . $_GET['path'];
        	$current_dir_path = $_GET['path'];
        	$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }
        echo realpath($root_path);
        $order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);
        
        if (preg_match('/\.\./', $current_path)) {
        	echo 'Access is not allowed.';
        	exit;
        }
        if (!preg_match('/\/$/', $current_path)) {
        	echo 'Parameter is not valid.';
        	exit;
        }
        if (!file_exists($current_path) || !is_dir($current_path)) {
        	echo 'Directory does not exist.';
        	exit;
        }
        
        $file_list = array();
        if ($handle = opendir($current_path)) {
        	$i = 0;
        	while (false !== ($filename = readdir($handle))) {
        		if ($filename{0} == '.') continue;
        		$file = $current_path . $filename;
        		if (is_dir($file)) {
        			$file_list[$i]['is_dir'] = true;
        			$file_list[$i]['has_file'] = (count(scandir($file)) > 2);
        			$file_list[$i]['filesize'] = 0;
        			$file_list[$i]['is_photo'] = false;
        			$file_list[$i]['filetype'] = '';
        		} else {
        			$file_list[$i]['is_dir'] = false;
        			$file_list[$i]['has_file'] = false;
        			$file_list[$i]['filesize'] = filesize($file);
        			$file_list[$i]['dir_path'] = '';
        			$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        			$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
        			$file_list[$i]['filetype'] = $file_ext;
        		}
        		$file_list[$i]['filename'] = $filename;
        		$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file));
        		$i++;
        	}
        	closedir($handle);
        }
        
        usort($file_list, 'kindEditorCmpFunc');
        
        $result = array();
        $result['moveup_dir_path'] = $moveup_dir_path;
        $result['current_dir_path'] = $current_dir_path;
        $result['current_url'] = $current_url;
        $result['total_count'] = count($file_list);
        $result['file_list'] = $file_list;
        
        header('Content-type: application/json; charset=UTF-8');
        echo Zend_Json::encode($result);
        exit;
    }
}

function kindEditorAlert($msg) 
{
	header('Content-type: text/html; charset=UTF-8');
	echo Zend_Json::encode(array('error' => 1, 'message' => $msg));
	
	exit;
}

function kindEditorCmpFunc($a, $b) 
{
    global $order;
    if ($a['is_dir'] && !$b['is_dir']) {
        return -1;
    } 
    else if (!$a['is_dir'] && $b['is_dir']) {
    	return 1;
    } 
    else {
        if ($order == 'size') {
            if ($a['filesize'] > $b['filesize']) {
        	    return 1;
            }
            else if ($a['filesize'] < $b['filesize']) {
        		return -1;
            } 
            else {
        	    return 0;
            }
        } 
        else if ($order == 'type') {
            return strcmp($a['filetype'], $b['filetype']);
        } 
        else {
            return strcmp($a['filename'], $b['filename']);
        }
    }












}

class XMLmap
{
    public $header = "<\x3Fxml version=\"1.0\" encoding=\"gb2312\"\x3F>\n\t<provider id=\"1\">";
    public $charset = "gb2312";
    public $footer = "\t</provider>\n";
    public $items = array();
    public function __construct() {
        
    }
    function addItem($newItem) {
        if(!is_a($newItem, "XMLmapItem")){
          trigger_error("Can't add a non-XMLmapItem object to the sitemap items array");
        }
        $this->items[] = $newItem;
    }
    function build( $fileName = null ) {
        $map = $this->header . "\n";
        foreach($this->items as $item) {
            $item->id = htmlentities($item->id, ENT_QUOTES);
            $map .= "\t\t<goods>\n\t\t\t<id><![CDATA[$item->id]]></id>\n";
            // cate
            if ( !empty( $item->cate ) ) {
                $map .= "\t\t\t<cate><![CDATA[$item->cate]]></cate>\n";
            }
            // brand
            if ( !empty( $item->brand ) ) {
                $map .= "\t\t\t<brand><![CDATA[$item->brand]]></brand>\n";
            }
            // name
            if ( !empty( $item->name ) ) {
                $map .= "\t\t\t<name><![CDATA[$item->name]]></name>\n";
            }
            // img
            if ( !empty( $item->img ) ) {
                $map .= "\t\t\t<images>\n\t\t\t\t<img><![CDATA[$item->img]]></img>\n\t\t\t</images>\n";
            }
            // marketprice
            if ( !empty( $item->marketprice ) ) {
                $map .= "\t\t\t<marketprice><![CDATA[$item->marketprice]]></marketprice>\n";
            }
            // price
            if ( !empty( $item->price ) ) {
                $map .= "\t\t\t<price><![CDATA[$item->price]]></price>\n";
            }
            // store
            if ( !empty( $item->store ) ) {
                $map .= "\t\t\t<store><![CDATA[$item->store]]></store>\n";
            }
            // url
            if ( !empty( $item->url ) ) {
                $map .= "\t\t\t<url><![CDATA[$item->url]]></url>\n";
            }

            $map .= "\t\t</goods>\n\n";
        }
        $map .= $this->footer . "\n";
		$map  = mb_convert_encoding($map, 'GBK', 'UTF-8'); 
        if (!is_null($fileName)) {
            return file_put_contents($fileName, $map);
        } else {
            return $map;
        }
    }
}
class XMLmapItem
{
    public $id = '';
    public $cate = '';
    public $brand = '';
	public $name = '';
    public $changefreg = '';
    public $marketprice = '';
	public $price = '';
	public $store = '';
	public $url = '';
    public function __construct( $id, $cate = '', $brand = '',$name = '', $img = '', $marketprice = '' ,$price = '' ,$store = '',$url = '') {
        $this->id = $id;
        $this->cate = $cate;
		$this->brand = $brand;
		$this->name = $name;
        $this->img = $img;
        $this->marketprice = $marketprice;
		$this->price = $price;
		$this->store = $store;
		$this->url = $url;
    }
}