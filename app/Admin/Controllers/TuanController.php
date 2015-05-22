<?php

class Admin_TuanController extends Zend_Controller_Action 
{
	/**
     * api对象
     */
    private $_api = null;
    
	const ADD_GOODS_SUCCESS = '添加团购商品成功!';
	const EDIT_GOODS_SUCCESS = '编辑团购商品成功!';
	const DEL_GOODS_SUCCESS = '删除团购商品成功!';
	const ADD_SUCCESS = '添加团购成功!';
	const EDIT_SUCCESS = '编辑团购成功!';
	const DEL_SUCCESS = '删除团购成功!';
	
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_Tuan();
	}
	
	/**
     * 团购列表
     */
	public function indexAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this->_request->getParams();
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();

        $where = null;
        if ($search['dosearch'])    $where = $search;
        $datas = $this -> _api -> get($where, 't1.*,t2.title as goods_title,t3.goods_name,t3.goods_sn,t3.product_id ', null, $page, 25);
		
        if ( $datas['total'] > 0 ) {
            $stockAPI = new Admin_Models_API_Stock();
            $tuan_ids = array();
            foreach ($datas['list'] as $num => $data) {
            	if ( $data['start_time'] <= time() ) {
            	    $tuan_ids[] = $data['tuan_id'];
            	}
            	$datas['list'][$num]['start_time'] = ($datas['list'][$num]['start_time'] > 0) ? date('Y-m-d', $datas['list'][$num]['start_time']) : '';
            	$datas['list'][$num]['end_time'] = ($datas['list'][$num]['end_time'] > 0) ? date('Y-m-d', $datas['list'][$num]['end_time']) : '';
            	$datas['list'][$num]['status'] = $this -> _api -> ajaxStatusGoods('/admin/tuan/status', $datas['list'][$num]['tuan_id'], $datas['list'][$num]['status']);
                $productIDArray[] = $data['product_id'];
            }
			
            if ( $tuan_ids ) {
                $goods_data = $this -> _api -> getOrderGoods("t2.status = 0 and t1.type = 6 and t1.offers_id in (".implode(',', $tuan_ids).")", 't1.offers_id,t1.number');
                if ( $goods_data['total'] > 0 ) {
                    foreach ( $goods_data['list'] as $data ) {
                        $number_data[$data['offers_id']] += $data['number'];
                    }
                    foreach ($datas['list'] as $num => $data) {
                        $datas['list'][$num]['number'] = $number_data[$data['tuan_id']] ? $number_data[$data['tuan_id']] : 0;
                    }
                }
            }

            $productStock = $stockAPI -> getSaleProductOutStock(array('product_id' => $productIDArray));
			
            if ($productStock) {
                foreach ($productStock as $stock) {
                    $stockData[$stock['product_id']] = $stock;
                }
               
            }
            foreach ($datas['list'] as $goods_id => $data) {
                $datas['list'][$goods_id]['able_number'] = $stockData[$data['product_id']]['able_number'];
                $datas['list'][$goods_id]['real_number'] = $stockData[$data['product_id']]['real_number'];
                $datas['list'][$goods_id]['hold_number'] = $stockData[$data['product_id']]['hold_number'];
                $datas['list'][$goods_id]['cost_amount'] = $goodsArray[$goods_id]['real_number']* $goodsArray[$goods_id]['cost'];
            }
        }

        $this -> view -> datas = $datas['list'];
        $this -> view -> param = $search;
	    $pageNav = new Custom_Model_PageNav($datas['total'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
	
	/**
     * 团购商品列表
     */
	public function goodsAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this->_request->getParams();
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        $where = null;
        if ($search['dosearch'])    $where = $search;
        $datas = $this -> _api -> getGoods($where, 't1.*,t2.goods_name', null, $page, 25);
        if ( $datas['total'] > 0 ) {
            $goods_ids = array();
            foreach ($datas['list'] as $num => $data)
            {
            	$datas['list'][$num]['add_time'] = ($datas['list'][$num]['add_time'] > 0) ? date('Y-m-d', $datas['list'][$num]['add_time']) : '';
            	$datas['list'][$num]['status'] = $this -> _api -> ajaxStatusGoods('/admin/tuan/status-goods', $datas['list'][$num]['id'], $datas['list'][$num]['status']);
            	$goods_ids[] = $data['id'];
            }
            
            if ( $goods_ids ) {
                $goods_data = $this -> _api -> getOrderGoods("t2.status = 0 and t1.type = 6 and t5.id in (".implode(',', $goods_ids).")", 't5.id,t1.number');
                if ( $goods_data['total'] > 0 ) {
                    foreach ( $goods_data['list'] as $data ) {
                        $number_data[$data['id']] += $data['number'];
                    }
                    foreach ($datas['list'] as $num => $data) {
                        $datas['list'][$num]['number'] = $number_data[$data['id']] ? $number_data[$data['id']] : 0;
                    }
                }
            }
        }
        $this -> view -> datas = $datas['list'];
        $this -> view -> param = $search;
	    $pageNav = new Custom_Model_PageNav($datas['total'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 添加团购
     *
     * @return void
     */
    public function addAction()
    {
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> edit($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, "/admin/tuan/index", 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> render('edit');
        }
    }
    
    /**
     * 添加团购商品
     *
     * @return void
     */
    public function addGoodsAction()
    {
        
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> editGoods($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_GOODS_SUCCESS, "/admin/tuan/goods", 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$this -> view -> action = 'add-goods';
            $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        	$this -> render('edit-goods');
        }
    }
    
    /**
     * 编辑团购
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
	        	    $data = $this -> _api -> get("tuan_id = {$id}", 't1.*,t2.title as goods_title');
	        	    $data = array_shift( $data['list'] );
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, "/admin/tuan/index/", 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $data = $this -> _api -> get("tuan_id = {$id}", 't1.*,t2.title as goods_title');
                $data = array_shift( $data['list'] );
                $data['start_time'] = date('Y-m-d H:i:s', $data['start_time']);
                $data['end_time'] = date('Y-m-d H:i:s', $data['end_time']);
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 编辑团购商品
     *
     * @return void
     */
    public function editGoodsAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editGoods($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    $data = $this -> _api -> getGoodsById($id);
	        	    Custom_Model_Message::showMessage(self::EDIT_GOODS_SUCCESS, "/admin/tuan/goods/", 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $data = $this -> _api -> getGoodsById($id);
                $this -> view -> action = 'edit-goods';
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 选择团购商品
     *
     * @return void
     */
    public function selAction()
    {
        $job = $this -> _request -> getParam('job', null);
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();

        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
      
        if($job) {
            $where = 't1.status = 0';
            if ( $search['title'] ) {
                $where .= " and t1.title like '%{$search['title']}%'";
            }
        	$datas = $this -> _api -> getGoods( $where, 't1.id,t1.title,t1.goods_id,t2.goods_name,t2.goods_sn,t2.price,t1.price as price_tuan,t2.onsale,t3.cat_name', null, $page, 25 );
        	$total = $datas['total'];
        	$datas = $datas['list'];
        	if ( $datas ) {
            	//获得库存
            	$product_api = new Admin_Models_API_Product();
            	$search['filter'] = " and real_in_number-out_number>0 and lid=1 and status_id=2";
            	$stock_datas = $product_api -> getStockStatus($search, '*');
            	if ( $stock_datas ) {
            	    foreach ($stock_datas as $num => $data) {
            		    $stock[$data['goods_id']] = $data['able_number'];
            	    }
            	}
            	foreach ($datas as $num => $data) {
            	    $datas[$num]['able_number'] = $stock[$data['goods_id']];
            	}
        	}
        	
        	$this -> view -> datas = $datas;
        }
        
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 选择商品
     *
     * @return void
     */
    public function selGoodsAction()
    {
        $cat_api = new Admin_Models_API_Category();
        
        $job = $this -> _request -> getParam('job', null);
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();

        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
    
        if($job) {
        	$datas = $this -> _api -> getGoodsInfo( $search, $page, 25 );
        	$total = $datas['total'];
        	$datas = $datas['content'];
        	
        	$this -> view -> datas = $datas;
        }else{
	        $this -> view -> catSelect = $cat_api -> buildSelect(array('name' => 'cat_id'));
        }
        
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * ajax更新商品
     *
     * @return void
     */
    public function ajaxupdateGoodsAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val', null);
        if ($id > 0) {
            $this -> _api -> ajaxUpdateGoods($id, $field, $val);
        } else {
            exit('error!');
        }
    }
    
    /**
     * ajax更新团购
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
     * ajax更新团购状态
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
        echo $this -> _api -> ajaxStatus('/admin/tuan/status', $id, $status);
    }
    
    /**
     * ajax更新商品状态
     *
     * @return void
     */
    public function statusGoodsAction() {
        $this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
            $this -> _api -> ajaxUpdateGoods($id, 'status', $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _api -> ajaxStatusGoods('/admin/tuan/status-goods', $id, $status);
    }
    
    /**
     * 删除团购
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
                $this->_redirect('/admin/tuan/index/');
			}
        } else {
            exit('error!');
        }
    }
    
    /**
     * 删除商品
     *
     * @return void
     */
    public function deleteGoodsAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _api -> deleteGoods($id);
            if(!$result) {
        	    exit($this -> _api -> error());
            }else{
                $this->_redirect('/admin/tuan/goods/');
			}
        } else {
            exit('error!');
        }
    }
	
	/**
     * 团购订单列表
     *
     * @return void
     */
    public function orderAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this->_request->getParams();

        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
     
        if (!$search['dosearch']) {
            $search['fromdate'] = date('Y-m-d', time());
            $search['todate'] = date('Y-m-d', time());
        }
        $where = $search;
        $datas = $this -> _api -> getOrderGoods($where, 't1.batch_sn,t1.offers_id,t1.parent_id,t1.number,t2.add_time,t2.addr_consignee,t2.status,t4.title,t5.id as tuan_goods_id', 't2.add_time desc', $page, 25);
        $totalPriceOrder = 0;
        if ( $datas['total'] > 0 ) {
            $parent_ids = array();
            foreach ($datas['list'] as $num => $data)
            {
                $parent_ids[] = $data['parent_id'];
            }
            
            $temp_data = $this -> _api -> getOrderCommonGoods($parent_ids);
            if ( $temp_data ) {
                foreach ($temp_data as $num => $data) {
                    $goods_data[$data['order_batch_goods_id']] = $data;
                }
                foreach ($datas['list'] as $num => $data)
                {
                    $datas['list'][$num]['goods_name'] = $goods_data[$data['parent_id']]['goods_name'];
                    $datas['list'][$num]['price'] = $goods_data[$data['parent_id']]['sale_price'];
                    $datas['list'][$num]['amount'] = $datas['list'][$num]['price'] * $datas['list'][$num]['number'];
                    $totalPriceOrder += $datas['list'][$num]['amount'];
                }
            }
        }
        
        $this -> view -> datas = $datas['list'];
        $this -> view -> totalPriceOrder = $totalPriceOrder;
        $this -> view -> param = $search;
	    $pageNav = new Custom_Model_PageNav($datas['total'], 25, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        
    }

}
