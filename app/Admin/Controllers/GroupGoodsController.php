<?php
class Admin_GroupGoodsController extends Zend_Controller_Action 
{
    /**
     * 
     * @var Admin_Models_API_GroupGoods
     */
    private $_api = null;
    
	const ADD_SUCCESS = '添加组合商品成功!';
	const EDIT_SUCCESS = '编辑组合商品成功!';
	const DEL_SUCCESS = '删除成功!';
	const NO_GROUPGOODS = '找不到组合商品!';
	const IMG_ERROR = '上传图片失败';
	
	private $_page_size = '20';
	/**
     * 初始化对象
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _api = new Admin_Models_API_GroupGoods();
        $this -> _adminCertification = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}
	
	/**
     * 默认动作组合商品列表
     *
     * @return   void
     */
    /*public function indexAction()
    {
        
		$page = (int)$this -> _request -> getParam('page', 1);
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        $search = $this -> _request -> getParams();
		if ($search['export'] == '1') {
			$this->_api->exportXlsGroupGoods($search);
			exit();
		} else {
			$datas = $this -> _api -> get($search,'*',$page,15);
		}
        $this -> view -> datas = $datas['data'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total'], 15, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this->view->opt_yn = array('1'=>'是','-1'=>'否');
    }*/

	/**
     * 组合商品审核
     *
     * @return   void
     */

    public function checkListAction()
    {
		$page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
		//$search['check_status'] = 'off';
	    $datas = $this -> _api -> get($search,'*',$page,15);
        $this -> view -> datas = $datas['data'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total'], 15, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this->view->opt_yn = array('1'=>'是','-1'=>'否');
    }

	/**
     * 组合商品信息管理
     *
     * @return   void
     */
    public function listAction()
    {
        
		$page = (int)$this -> _request -> getParam('page', 1);
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        $search = $this -> _request -> getParams();
		if ($search['export'] == '1') {
			$this->_api->exportXlsGroupGoods($search);
			exit();
		} else {
			$datas = $this -> _api -> get($search,'*',$page,15);
		}
        $this -> view -> datas = $datas['data'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total'], 15, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this->view->opt_yn = array('1'=>'是','-1'=>'否');
    }

	/**
     * 组合商品销售管理
     *
     * @return   void
     */
    public function salelistAction()
    {
        
		$page = (int)$this -> _request -> getParam('page', 1);
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        $search = $this -> _request -> getParams();
		$search['is_shop_sale'] = 1;
		if ($search['export'] == '1') {
			$this->_api->exportXlsGroupGoods($search);
			exit();
		} else {
			$datas = $this -> _api -> get($search,'*',$page,15);
		}
        $this -> view -> datas = $datas['data'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total'], 15, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this->view->opt_yn = array('1'=>'是','-1'=>'否');
    }
    
    /**
     * 添加组合商品动作
     *
     * @return void
     */
    public function addAction()
    {
		if ($this -> _request -> isPost()) {
			$result = $this -> _api -> add($this -> _request -> getPost());
			if ($result=='imgErr') {
				Custom_Model_Message::showMessage(self::IMG_ERROR, '/admin/group-goods/list', "Gurl()");
			}elseif($result){
				Custom_Model_Message::showMessage(self::ADD_SUCCESS, '/admin/group-goods/list', "Gurl()");
			}else{
				Custom_Model_Message::showMessage($this -> _api -> getError());
			}
		}
    }

    /**
     * 编辑组合商品动作
     *
     * @return void
     */
    public function editAction()
    {
    	$group_id = (int)$this -> _request -> getParam('group_id', null);
        if($group_id > 0){
            if ($this -> _request -> isPost()){
                $result = $this -> _api -> edit($this -> _request -> getPost(), $group_id);
                if ($result) {
                    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, '/admin/group-goods/list', 1250, "Gurl()");
                }else{
                    Custom_Model_Message::showMessage($this -> _api -> getError());
                }
            }else{
                $data = $this -> _api -> getGroupgoogs("group_id='$group_id'", "*");
                $goods = unserialize($data['group_goods_config']);
                unset($data['group_goods_config']);
                if($goods==''){$goods=0;}
                else{
                    foreach ($goods as $k=>$v){
                        if($this -> _api ->checkgoodsstatus($v['goods_id'])){
                            $goods[$k]['onsale']=0;
                        }else{
                            $goods[$k]['onsale']=1;
                        }
                    }
                }
                $this -> view -> data = $data;
                $this -> view -> goods = $goods;
            }
        }else{
        	Custom_Model_Message::showMessage('error!', '/admin/group-goods/list', 1250, 'Gurl()');
        }
    }

	/**
     * 编辑组合商品销售
     *
     * @return void
     */
    public function saleEditAction()
    {
    	$group_id = (int)$this -> _request -> getParam('group_id', null);
        if($group_id > 0){
            if ($this -> _request -> isPost()){
                $result = $this -> _api -> saleEdit($this -> _request -> getPost(), $group_id);
                if ($result) {
                    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, '/admin/group-goods/salelist', 1250, "Gurl()");
                }else{
                    Custom_Model_Message::showMessage($this -> _api -> getError());
                }
            }else{
                $data = $this -> _api -> getGroupgoogs("group_id='$group_id'", "*");
                $goods = unserialize($data['group_goods_config']);
                unset($data['group_goods_config']);
                if($goods==''){$goods=0;}
                else{
                    foreach ($goods as $k=>$v){
                        if($this -> _api ->checkgoodsstatus($v['goods_id'])){
                            $goods[$k]['onsale']=0;
                        }else{
                            $goods[$k]['onsale']=1;
                        }
                    }
                }
                $this -> view -> data = $data;
                $this -> view -> goods = $goods;
            }
        }else{
        	Custom_Model_Message::showMessage('error!', '/admin/group-goods/salelist', 1250, 'Gurl()');
        }
    }

	/**
     * 查看组合商品日志
     *
     * @return void
     */
	public function logListAction()
	{
		$page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
		if (empty($params['group_id'])) {
			exit('组合商品ID不正确');
		}
		
		$group_info = $this->_api->getGroupInfoByGroupId($params['group_id']);

		if (empty($group_info)) {
			exit('没有组合商品信息');
		}

		$count  = $this->_api->getLogCount($params);
		
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_api->getLogList($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav =$pageNav->getNavigation();
        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
		$this->view->group_info = $group_info;
	}


    /**
     * ajax 改变组合商品的状态
     * 
     * @return text
     */
    public function statusAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$s = (int)$this -> _request -> getParam('s', 0);
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$rs = array();
    	if($id<1){ $rs['st'] = 'noid'; }
    	$g = $this -> _api -> status($s,$id);
    	if($g === true){
    		$rs['st'] = 'ok';
    		$rs['html'] = ($s==0)?'<a href="javascript:fGo()" onclick="changeStatus(0,'.$id.')" style=" color:red;">下架</a>':'<a href="javascript:fGo()" onclick="changeStatus(1,'.$id.')" style=" color:blue;">上架</a>';
    	}else{
    		$rs['st'] = 'sub';
    		$rs['html'] = $g;
    	}
    	echo json_encode($rs);
    	exit;
    }
    


    /**
     * ajax套装审核
     * 
     * @return text
     */
    public function checkStatusAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$s = (int)$this -> _request -> getParam('s', 0);
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$rs = array();
    	if($this -> _api -> checkStatus($s,$id)){
    		$rs['st'] = 'ok';
    		$rs['html'] = ($s==0)?'<a href="javascript:fGo()" onclick="changeCheckStatus(0,'.$id.')" style=" color:red;">待审核</a>':'<a href="javascript:fGo()" onclick="changeCheckStatus(1,'.$id.')" style=" color:blue;">已经审核</a>';
    	}else{
    		$rs['st'] = 'sb';
    		$rs['html'] ='';
    	}
    	echo json_encode($rs);
    	exit;
    }
    


	
    /**
     * ajax 更改排序
     * 
     * @return text
     */
    public function groupsortAction() {
    	$s = (int)$this -> _request -> getParam('s', 0);
    	$id = (int)$this -> _request -> getParam('id', 0);
    	if($id<1){exit('noid');}
    	$g = $this -> _api -> groupsort($s,$id);
    	if($g){ exit('ok'); }
    	else{ exit('no'); }
    }
    
    
    /**
     * ajax 删除组合商品动作
     *
     * @return text
     */
    public function deleteAction()
    {
		$id = (int)$this -> _request -> getParam('id', 0);
    	if($id<1){exit('noid');}
    	$g = $this -> _api -> delete($id);
    	if($g){ exit('ok'); }
    	else{ exit('no'); }
    }
    
    /**
     * 检查商品是否上架 
     * 
     * @param $goods_id int
     * @return bool
     */
    public function checkgoodsstatusAction($goods_id)
    {
    	$goods_id=(int)$goods_id;
    	if($goods_id<1){return false;}
    	return $this -> _api -> checkgoodsstatus($goods_id);
    }
    
    /**
     * ajax触发，更新组合商品库存
     * 
     * @return string
     */
    public function refreshstockAction()
    {
    	$group_ids = $this -> _request -> getParam('group_ids', '');
    	if($group_ids != ''){
    		$group_ids = explode(',', $group_ids);
    		if(is_array($group_ids) && count($group_ids)){
    			$stock = new Admin_Models_API_Stock();
    			foreach ($group_ids as $k => $v){
    				$goodsConfig = $this -> _api -> fetchConfigGoods(array('group_id'=>$v));
    				//遍历group_goods_config
    				if(is_array($goodsConfig) && count($goodsConfig)){
    					$able_number = array();
    					foreach ($goodsConfig as $kk => $vv){
	    					$goodsStock = $stock -> getSaleProductStock($vv['product_id']);
	    					$able_number[$vv['product_id']] = @$goodsStock['able_number']/$vv['number'];
    					}
    					//更新表  shop_group_goods 的库存
	    				$this -> _api -> updateStock($v, (int)min($able_number));
    				}
    			}
    			exit('ok');
    		}else{
    			exit('no');
    		}
    	}else{
    		exit('no');
    	}
    }
    
    /*ajax 更新字段
     * 
     * 
     */
    public function gengxinAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	if($id<1){exit('参数错误');}
    	$ziduan = $this -> _request -> getParam('ziduan', null);
    	if($ziduan==''){exit('参数错误');}
    	$val = $this -> _request -> getParam('val', '');
    	if($val==''){$val = '';}
    	$this -> _api -> gengxin(array($ziduan=>$val),'group_id='.$id);
    	exit();
    }
    
    /*Begin::2012.5.15添加，用于更新组合商品的子商品售价*/
    /**
     * 整理组合商品中的子商品的sale_price
     * 
     */
    public function setSubGoodsSalePriceAction() {
    	exit('禁用');
    	$this -> _helper -> viewRenderer -> setNoRender();
    	//取组合商品（不包含子商品）
    	$search = array('type'=>5, 'product_id'=>0);
    	$groupOrder = $this -> _api -> getGroupOrderBatchGoods($search,"order_batch_goods_id,parent_id,type,product_id,goods_name,sale_price,number");
    	if($groupOrder['tot']>0){
    		//取组合商品子商品
    		foreach($groupOrder['datas'] as $k=>$v){
    			$subSearch = array('type'=>5, 'parent_id'=>$v['order_batch_goods_id']);
    			$groupOrderGoods = $this -> _api -> getGroupOrderBatchGoods($subSearch,"order_batch_goods_id,parent_id,type,product_id,goods_name,sale_price,number");
    			if($groupOrderGoods['tot']>0){
    				foreach ($groupOrderGoods['datas'] as $vv){
	    				//由product_id得到这个商品的价格
	    				$goodsPrice[$vv['product_id']] = $this ->_api -> getGoodsPriceByProductID($vv['product_id']);//每个product_id的价格
	    				$subTot += $vv['number']*$goodsPrice[$vv['product_id']];//子商品的总额
    				}
    				//计算price sale_price eq_price
    				foreach ($groupOrderGoods['datas'] as $vv){
    					$set['price'] = $goodsPrice[$vv['product_id']];
    					$set['sale_price'] = @(($v['sale_price']*$goodsPrice[$vv['product_id']])/$subTot);
    					$set['eq_price'] = @(($v['sale_price']*$goodsPrice[$vv['product_id']])/$subTot);
    					//更新order_batch_goods子商品
    					$this -> _api -> updateOrderBatchGoods($set, " order_batch_goods_id=".$vv['order_batch_goods_id']);
    				}
    				$subTot = 0;
    			}
    		}
    	}
    	exit('更新成功');
    }
    /*End::2012.5.15添加，用于更新组合商品的子商品售价*/

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
     * 组合商品评论
     * 
     * @retur array
     */
    public function msgAction() {
    	$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$where = array('type'=>1);
		$datas = $this -> _api -> getGroupGoodsMsg($where, '*', null, $page, 15);
		$this -> view -> data = $datas['datas'];
		$this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['tot'], 15, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * ajax 删除评论
     * 
     */
    public function delGroupGoodsMsgAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
        $msg_id = (int)$this -> _request -> getParam('msg_id', 0);
    	if($msg_id<1){exit('noid');}
    	$g = $this -> _api -> delGroupGoodsMsg($msg_id);
    	if($g){ exit('ok'); }
    	else{ exit('no'); }
    }
    
    /**
     * ajax 批量审核留言
     * 
     */
    public function checkGroupGoodsMsgAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
        $ids = $this -> _request -> getParam('ids', 0);
        $val = (int)$this -> _request -> getParam('val', 0);
        if(is_array($ids) && count($ids)){
        	foreach($ids as $id){
			    $data = array(
	                'status'=>$val,
	                'admin_id'=>$this->_adminCertification['admin_id'],
	                'admin'=>$this->_adminCertification['admin_name'],
	                'reply_time'=>time()
			    );
	    		$this -> _api -> msgCheck($id, $data);
			}
			Custom_Model_Message::showMessage('操作成功', 'event', 1250, "Gurl('refresh')");
        }else{
        	Custom_Model_Message::showMessage('操作失败', 'event', 1250, "Gurl('refresh')");
        }
    }
    
    /**
     * 回复
     * 
     */
    public function msgReplyAction() {
    	$msg_id = (int)$this -> _request -> getParam('id', 0);
    	if ($msg_id > 0){
        	if ($this -> _request -> isPost()){
            	$this -> _api -> msgReply($msg_id, $this -> _request -> getPost());
	        	Custom_Model_Message::showMessage('回复成功', '/admin/group-goods/msg', 1250, "Gurl()");
        	}else{
        		$detail = $this -> _api -> getGroupGoodsMsg(array('group_goods_msg_id'=>$msg_id), "*");
        		$this -> view -> msg = $detail['datas'][0];
        		$this -> view -> type = 1;
        	}
        }else{
        	Custom_Model_Message::showMessage('error!', '/admin/group-goods/msg', 1250, 'Gurl()');
        }
    }
    
    /**
     * 整理 group_goods_config 字段
     * 
     */
    public function tidyConfigAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$groups = $this -> _api -> get(null, 'group_id, group_goods_config');
    	if($groups['total'] > 0){
    		foreach ($groups['data'] as $k=>$v){
    			$config = unserialize($v['group_goods_config']);
    			if(is_array($config) && count($config)){
    				$cfg = array();
    				foreach ($config as $kk=>$vv){
	    				if($vv['goods_id']){
		    				$p = $this -> _api -> getGoods(array('goods_id'=>$vv['goods_id']), 't2.product_id,t2.product_sn,t2.product_name');
		    				$p = array_shift($p);
		    				if(is_array($p) && count($p)){
		    					$cfg[$kk]['product_id'] = $p['product_id'];
		    					$cfg[$kk]['product_sn'] = $p['product_sn'];
		    					$cfg[$kk]['product_name'] = $p['product_name'];
		    					$cfg[$kk]['number'] = $vv['number'];
		    				}else{
		    					$cfg[$kk]['product_id'] = isset($vv['product_id'])?$vv['product_id']:0;
		    					$cfg[$kk]['product_sn'] = isset($vv['product_sn'])?$vv['product_sn']:0;
		    					$cfg[$kk]['product_name'] = isset($vv['product_name'])?$vv['product_name']:0;
		    					$cfg[$kk]['number'] = $vv['number'];
		    				}
		    			}else{
		    				$cfg[$kk]['product_id'] = $vv['product_id'];
	    					$cfg[$kk]['product_sn'] = $vv['product_sn'];
	    					$cfg[$kk]['product_name'] = $vv['product_name'];
	    					$cfg[$kk]['number'] = $vv['number'];
		    			}
    				}
    				//更新
    				$this -> _api -> gengxin(array('group_goods_config'=>serialize($cfg)), "group_id=".$v['group_id']);
    			}
    		}
    	}
    	exit('操作成功！');
    }
    
    /**
     * 更新上下架状态
     * 
     */
    public function refreshStatusAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$rs = $this -> _api -> refreshStatus();
    	echo $rs;
    	exit;
    }
    
    /**
     * 列表中查看组合商品配置
     * 
     */
    public function viewConfigAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$groupid = (int)$this -> _request -> getParam('groupid', 0);
    	if($groupid < 0){exit('参数错误');}
    	$rs = $this -> _api -> fetchConfigGoods(array('group_id'=>$groupid));
    	if($rs === false){exit('组合商品的子商品没有录入');}
    	if(count($rs)<1){exit('此组合商品子商品不存在 或 录入错误');}
    	$html = '';
    	foreach ($rs as $v){
			$html .= $v['goods_sn']."   ".$v['number']."   ".$v['goods_name']."\n";
		}
    	exit($html);
    }
    
    /**
     * ajax 更新组合商品成本价
     * 
     */
    public function refreshCostAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$group_ids = $this -> _request -> getParam('group_ids', '');
    	$rs = $this -> _api -> refreshCost($group_ids);
    	exit($rs);
    }
    
    
    /**
     * 切换官网销售状态
     * @return void
     */
    public function toggleIsOfflSaleAction() {
        
        $this->_helper->viewRenderer->setNoRender ();
        $id = ( int ) $this->_request->getParam ( 'id', 0 );
        $status = ( int ) $this->_request->getParam ( 'status', 0 );
    
        if ($id <= 0) die('failure');
    
        $cs = $this->_api->toggleIsOfflSale($id);
        echo $cs == 0 ? '否' : '是';
        exit;
    }
		
	/**
	 * 组合限价管理
	 *
	 * @return    void
	 */
	public function priceListAction()
    {
        
		$page = (int)$this -> _request -> getParam('page', 1);
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> get($search,'*',$page,15);
        $this -> view -> datas = $datas['data'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total'], 15, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this->view->opt_yn = array('1'=>'是','-1'=>'否');
    }
	
	/**
	 * 组合限价修改
	 *
	 * @return    array
	 */
	public function changeAjaxGroupproductAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$group_id      = $this->_request->getParam('group_id', 0);
		$price_limit   = $this->_request->getParam('price_limit', 0);

		if (intval($group_id) == 0) {
			exit(json_encode(array('success' => 'false', 'message' => '组合ID不正确')));
		}

		if (ceil($price_limit) < 0) {
			exit(json_encode(array('success' => 'false', 'message' => '保护价不能小于0')));
		}

		if (false === $this->_api->updateGroupgoodsAmountByGroupid($group_id,$price_limit)) {
			exit(json_encode(array('success' => 'false', 'message' => $this->_api->getError())));
		}

		exit(json_encode(array('success' => 'true', 'message' => '操作成功')));
	}
    
}