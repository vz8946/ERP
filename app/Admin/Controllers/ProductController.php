<?php
session_start();
class Admin_ProductController extends Zend_Controller_Action 
{
	/**
     * api对象
     */
    private $_api = null;
    
    private $_units = array('盒','瓶','片','袋','斤','个','罐','箱');
	private $url = 'http://125.211.217.178:8001/Api/ApiResult.aspx';
	private $appkey = 'MGVjMTdhMDctNmVmMS00ODg2LWE2OWQtMWRmNGNkOWNjOTY2';
	private $appsecret = 'ODI3YTA4YjAtZTg5NS00N2IxLTg3OGYtNWVjZmNmZjc2NDM0';
	private $v = '1.0';
	private $username = 'B2BB2C';
	private $pwd = '111111';    
	private $PageSize = '9999999';
    const ADD_SUCCESS = '产品资料添加成功!';
    const EDIT_SUCCESS = '产品资料编辑成功!';
    const EDIT_FAIL = '产品资料编辑失败!';
    const IMG_SUCCESS = '商品图片添加成功!';

	private $_page_size = '15';
    
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
		$this -> _api = new Admin_Models_API_Product();
		$this -> goods_api = new Admin_Models_API_Goods();
		$this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
		$this -> view -> auth = $this -> _auth;
		$config = Custom_Model_Stock_Base::getInstance($this -> _request -> getParam('logic_area', null));
		$this -> view -> status = $config -> getConfigLogicStatus();
	}
	
	/**
     * 默认动作
     *
     * @return   void
     */
    public function indexAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> get($search, '*', $page,20);
        if ($datas) {
            foreach ($datas as $num => $data) {
                $datas[$num]['status'] = $this -> _api -> ajaxStatus('/admin/product/status', $datas[$num]['product_id'], $datas[$num]['p_status']);
                $productIDArray[] = $data['product_id'];
            }
            $supplierAPI = new Admin_Models_API_Supplier();
            $supplierData = $supplierAPI -> getProductSupplier($productIDArray);
            if ($supplierData) {
                foreach ($supplierData as $supplier) {
                    $ids = explode(',', $supplier['product_ids']);
                    foreach ($ids as $productID) {
                        $productSupplierInfo[$productID][] = $supplier['supplier_name'];
                    }
                }
                foreach ($datas as $num => $data) {
                    $productSupplierInfo[$data['product_id']] && $datas[$num]['supplier'] = implode(',', $productSupplierInfo[$data['product_id']]);
                }
            }
        }

		$product_ids = $this->_api->getSingleKey($datas, 'product_id');

		$stockAPI = new Admin_Models_API_Stock();
		$stock_infos = $stockAPI->getStockInfosByProductIds($product_ids, array('status_id' => 2, 'lid' => 1));
      
		if  (false !== $stock_infos) {
			$stock_infos = $this->_api->singleGroup($stock_infos, 'product_id');

			foreach ($datas as &$data) {
				$data['stock_able_number'] = 
                    ($stock_infos[$data['product_id']]['real_in_number'] - $stock_infos[$data['product_id']]['real_out_number']) 
                    - $data['hold_stock_number'] 
                    - ($stock_infos[$data['product_id']]['out_number'] - $stock_infos[$data['product_id']]['real_out_number']);
                
			}
		}

        $total = $this -> _api -> getCount();
        $this -> view -> datas = $datas;
		$this->view->stock_infos = $stock_infos;
        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id','selected'=>$search['cat_id']));
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 产品成本列表
     *
     * @return   void
     */
    public function priceListAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> get($search, '*', $page, 20);
        $total = $this -> _api -> getCount();
        if ($datas) {
            foreach ($datas as $num => $data) {
                $productIDArray[] = $data['product_id'];
            }
            
            $stockAPI = new Admin_Models_API_Stock();
            $productStock = $stockAPI -> getSaleProductOutStock(array('product_id' => $productIDArray));
            if ($productStock) {
                foreach ($productStock as $stock) {
                    $stockData[$stock['product_id']] = $stock;
                }
            }
            foreach ($datas as $num => $data) {
                $datas[$num]['real_number'] = $stockData[$data['product_id']]['real_number'];
            }
        }
        $this -> view -> datas = $datas;
        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 导出产品成本
     *
     * @return   void
     */
    public function exportPriceAction()
    {
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> get($search, '*');
        
        $content[] = array('产品ID', '产品编码', '产品名称','建议销售价', '采购成本价', '移动成本价', '发票税率', '状态');
        
        if ($datas) {
            foreach ($datas as $data) {
                $content[] = array($data['product_id'], $data['product_sn'], $data['product_name'], $data['suggest_price'], $data['purchase_cost'], $data['cost'], $data['invoice_tax_rate'], $data['p_status'] ? '冻结' : '正常');
            }
        }
        
        $xls = new Custom_Model_GenExcel();
        $xls -> addArray($content);
        $xls -> generateXML('product-price');
                
        exit();
    }
    /**
	 *同步新产品
	*/
	public function synchroAction(){
		$rows = $this->getProductInfos();
		if($rows){
			foreach($rows as $key=>$val){
				$rows[$key]['Inventory'] = $this->getInventoryByOrderCode($val['MdIdentityCode']);
			}
		}
		$id = $this -> _api -> synchro($rows);
		echo "<script>alert('数据同步完成！');history.back();</script>";
		die;
	}
	private function sort_str($params){
		ksort($params);
		$str = '';
		if($params){
			foreach($params as $key => $val){
				$str .= $key.$val;
			}
		}
		return $str;
	}
	/**
	 *提交json接口	
	 *url是提交地址
	 *data是提交的json参数
	**/
	private function curl_post($url,$data,$action = 'POST'){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);	  
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		$result = curl_exec($ch);
		if($result === false){
			return 'Curl error:'.curl_error($ch);
		}
		return $result;
	}
	private function SessionKey($str){		
		$data = json_decode(base64_decode($str),true);
		$params = array(
			'DomainId' => $data['DomainId'],
			'UserId' => $data['UserId'],
			'TimeStamp' => date('Y-m-d H:i:s'),
			'TimeOut' => (string)$data['TimeOut'],
			'WebSession' => $data['WebSession'],
			'TokenKey' => $data['TokenKey']
		);
		$_SESSION['Response'] = $params;
		return $params;
	}
	public function Sign($params){
		return strtoupper(md5($this->appsecret.$this->sort_str($params).$this->appsecret));
	}

	public function login(){
		if(!empty($_SESSION['Response']))	return $_SESSION['Response'];
		$method = 'shanhuyun.system.login';
		$data = array(
			'pwd' 	   => $this->pwd,
			'username' => $this->username
		);
		$params = array(			
			'appkey'=>$this->appkey,
			'method'=>$method,
			'v'=>$this->v,
			'sign'=>md5($this->sort_str($data).$this->appsecret),
			'paramjson'=>json_encode($data)
		);
		$res = $this->curl_post($this->url,$params);
		$res = json_decode($res,true);

		if($res['Code'] == '00'){
			return $this->SessionKey($res['Response']);
		}
		return false;
	}
	public function getProductInfos(){
		$method = 'shanhuyun.merchandise.search';		
		$data = array(
			'PageSize' => $this->PageSize
		);
		$response = $this->login();
		$params = array(
			'DomainId' => $response['DomainId'],
			'UserId' => $response['UserId'],
			'TimeStamp' => date('Y-m-d H:i:s'),
			'TimeOut' => $response['TimeOut'],
			'WebSession' => $response['WebSession'],
			'TokenKey' => $response['TokenKey'],
			'AppSecret'=>$this->appsecret
		);
		$str = '';
		foreach($params as $val){
			$str.=$val;
		}
		$Sign = md5($str);
		unset($params['AppSecret']);
		$params['Sign'] = $Sign;
		$json = json_encode($params);
		$session = base64_encode($json);
		$sign = $this->Sign($data);
		
		$param = array(
			'appkey'=>$this->appkey,
			'method'=>$method,
			'v'=>$this->v,
			'sign'=>$sign,
			'paramjson'=>json_encode($data),
			'session'=>$session
		);
		$res = $this->curl_post($this->url,$param);
		$res = json_decode($res,true);
		if($res['Code'] == '00'){
			return $res['Response']['MerchList'];
		}
		echo '数据连接失败！';die;
	}
	public function getInventoryByOrderCode($MdIdentityCode){
		$method = 'shanhuyun.merchinventory.get';		
		$data = array(
			'MdIdentityCode' => $MdIdentityCode,
			'ShopType' => 'B2B'
		);
		$response = $this->login();
		$params = array(
			'DomainId' => $response['DomainId'],
			'UserId' => $response['UserId'],
			'TimeStamp' => date('Y-m-d H:i:s'),
			'TimeOut' => $response['TimeOut'],
			'WebSession' => $response['WebSession'],
			'TokenKey' => $response['TokenKey'],
			'AppSecret'=>$this->appsecret
		);
		$str = '';
		foreach($params as $val){
			$str.=$val;
		}
		$Sign = md5($str);
		unset($params['AppSecret']);
		$params['Sign'] = $Sign;
		$json = json_encode($params);
		$session = base64_encode($json);
		$sign = $this->Sign($data);
		
		$param = array(
			'appkey'=>$this->appkey,
			'method'=>$method,
			'v'=>$this->v,
			'sign'=>$sign,
			'paramjson'=>json_encode($data),
			'session'=>$session
		);
		$res = $this->curl_post($this->url,$param);
		$res = json_decode($res,true);
		if($res['Code'] == '00'){
			return $res['Response'][0]['Inventory'];
		}
		return 0;
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
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, '/admin/product/index', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
            $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'), 'changeCat(this.value)');
            $goodsAPI = new Admin_Models_API_Goods();
            $this -> view -> characters = $this -> _api -> getCharacters('status = 0');
            $this -> view -> brand = $goodsAPI -> getBrand();
            $this -> view -> units = $this -> _units;
        	$this -> view -> action = 'add';
        	$this -> render('edit');
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
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl('refresh')");
	        	} else {
	        		Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } 
            else {
                $data = array_shift($this -> _api -> get(array('product_id' => $id)));
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
                $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
                $goodsAPI = new Admin_Models_API_Goods();
                $this -> view -> brand = $goodsAPI -> getBrand();
                $this -> view -> characters = $this -> _api -> getCharacters('status = 0');
                $this -> view -> character = $this -> _character;
                $this -> view -> units = $this -> _units;
                
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
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
    	
    	if ($id > 0) {
    		$this -> _api -> ajaxUpdate($id, 'p_status', $status);
        }
        
        echo $this -> _api -> ajaxStatus('/admin/product/status', $id, $status);
        
        exit;
    }
    
	/**
     * 导出动作
     *
     * @return   void
     */
    public function exportAction()
    {
		$opt_api = new Admin_Models_API_OpLog();
		$opt_api->addopt($this ->_auth['admin_id'],"product-export");
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this -> _helper -> viewRenderer -> setNoRender();
        $this -> _api -> export($this -> _request -> getParams());
        exit;
    }
    
	/**
     * 导出动作
     *
     * @return   void
     */
    public function saleExportAction()
    {
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this -> _helper -> viewRenderer -> setNoRender();
        $this -> _api -> saleExport($this -> _request -> getParams());
    }
	/**
     * 更新前台销售排行
     *
     * @return   void
     */
    public function updateSaleAction()
    {
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $this -> _api -> updateSale($this -> _request -> getParams());
        exit('ok');
    }
    
    /**
     * 选择商品
     *
     * @return void
     */
    public function selAction()
    {
        $job = $this -> _request -> getParam('job', null);
        $type = $this -> _request -> getParam('type', null);
        $justOne = $this -> _request -> getParam('justOne', null);
        $hidePrice = $this -> _request -> getParam('hidePrice', null);
        $logicArea = (int)$this -> _request -> getParam('logic_area', 1);
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        
        switch ($type) {
        	case 'sel':
        		$logicArea <= 10 && $search['filter'] = " and p_status = 0";
        	    $showStatus = 'false';
        	    $showNumber = 'false';
        	    break;
        	case 'sel_status':
        	    $logicArea <= 10 && $search['p_status'] = "0";
        	    break;
        	case 'sel_stock':
        		$logicArea <= 10 && $search['p_status'] = "0";
        	    break;
    	    case 'sel_cost':
    	        $logicArea <= 10 ;
    	        break;
            default:
                $showStatus = 'false';
                $logicArea <= 10 && $search['filter'] = " and p_status = 0 ";
        }
     
        if ($job) {
            if ($type == 'sel_status') {        //不包含销售产品占用库存
        	    $stockAPI = new Admin_Models_API_Stock();
        	    $datas = $stockAPI -> getProductOutStock($search, $page, 15);
        	    $total = $stockAPI -> getCount();
        	}
        	else if ($type == 'sel_stock') {    //包含销售产品占用库存
        	    $stockAPI = new Admin_Models_API_Stock();
        	    $stockAPI -> setLogicArea($search['logic_area']);
				if($search['logic_area'] == '1'){
                   $datas = $stockAPI -> getSaleProductOutStock($search, $page, 15,true);
				}else{
				   $datas = $stockAPI -> getSaleProductOutStock($search, $page, 15);
				}
        	    $total = $stockAPI -> getCount();
        	} 
        	else if ($type == 'sel') {          //无库存信息
        	    $datas = $this -> _api -> getProductWithBatch($search, '*', $page,15);
        	    $total = $this -> _api -> getCount();
        	}
        	else {  //无库存信息，无批次信息
        	    $datas = $this -> _api -> get($search, '*', $page,15);
        		$total = $this -> _api -> getCount();
        	}
       	    $this -> view -> datas = $datas;
        }
        else {
            $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
        }
        
        $this -> view -> justOne = $justOne;
        $this -> view -> showStatus = $showStatus;
        $this -> view -> showNumber = $showNumber;
        $this -> view -> hidePrice = $hidePrice;
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total, 15, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
	/**
     * 锁定/解锁动作
     *
     * @return   void
     */
    public function lockAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = (int)$this -> _request -> getParam('val', 0);
    	$this -> _api -> lock($this -> _request -> getPost(), $val);
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
     * 商品图片管理
     *
     * @return void
     */
    public function imageAction()
    {
        $product_id = (int)$this -> _request -> getParam('id', 0);
        $product_sn = $this -> _request -> getParam('product_sn', null);
        if ($product_id > 0) {
            if ($this -> _request -> isPost()) {
            	$result = $this -> _api -> upimg($this -> _request -> getPost(), $product_id, $product_sn);
            	Custom_Model_Message::showMessage(self::IMG_SUCCESS, 'event', 1250, "Gurl('refresh')");
            } else {
            	$this -> view -> data = array_shift($this -> _api -> get(array('product_id' => $product_id)));
                $this -> view -> img_url = $this -> _api -> getImg("product_id='$product_id' and img_type=2");
                $this -> view -> img_ext_url = $this -> _api -> getImg("product_id='$product_id' and img_type=3");
            }
        } else {
            exit('error!');
        }
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
        if ((int)$id > 0) {
            $result = $this -> _api -> deleteImg((int)$id);
            exit;
            if(!$result) {
        	    exit($this -> _api -> error());
            }
        } else {
            exit('error!');
        }
    }
    
    /**
     * 成本修改
     *
     * @return void
     */
    public function costEditAction()
    {
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id < 0)    exit;
        
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> editPrice($id, $this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, 'event', 1250, "Gurl('refresh')");
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
            $data = array_shift($this -> _api -> get(array('product_id' => $id)));
            
            $this -> view -> data = $data;
            
        }
    }
    
    /**
     * 产品批次列表
     *
     * @return   void
     */
    public function batchListAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $search = $this -> _request -> getParams();
        $datas = $this -> _api -> getBatch($search, 't1.*,t2.product_name,t2.goods_style,t3.cat_name,t4.supplier_name', $page, 20);
        if ($datas['data']) {
            foreach ($datas['data'] as $num => $data) {
                
            }
        }
        
        $supplierAPI = new Admin_Models_API_Supplier();
        $this -> view -> supplierData = $supplierAPI -> getSupplier("status = 0", "supplier_id,supplier_name");
        $this -> view -> datas = $datas['data'];
        $this -> view -> catSelect = $this -> _cat ->buildProductSelect(array('name' => 'cat_id'));
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($datas['total'], 20, 'ajax_search');
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    
    /**
     * 添加批次动作
     *
     * @return void
     */
    public function addBatchAction()
    {
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> editBatch($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage('添加批次成功', '/admin/product/batch-list', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> render('edit-batch');
        }
    }
    
    /**
     * 编辑批次动作
     *
     * @return void
     */
    public function editBatchAction()
    {
        $batch_id = (int)$this -> _request -> getParam('batch_id', null);
        if ($batch_id> 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editBatch($this -> _request -> getPost(), $batch_id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage('编辑批次成功', '/admin/product/batch-list', 1250);
	        	} else {
	        		Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } 
            else {
                $datas = $this -> _api -> getBatch(array('batch_id' => $batch_id));
                $data = array_shift($datas['data']);
                $this -> view -> action = 'edit';
                $this -> view -> data = $data;
            }
        }
        else {
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }
    
    /**
     * 生成供应商下拉框(ajax调用)
     *
     * @return void
     */
    public function supplierBoxAction()
    {
        $product_id = (int)$this -> _request -> getParam('product_id', 0);
        if (!$product_id)   exit;
        
        $supplier_id = (int)$this -> _request -> getParam('supplier_id', 0);
        
        $supplierAPI = new Admin_Models_API_Supplier();
        $datas = $supplierAPI -> getProductSupplier($product_id);
        if ($datas) {
            foreach ($datas as $data) {
                if ($data['supplier_id'] == $supplier_id) {
                    $selected = 'selected';
                }
                else    $selected = '';
                $result .= "<option value=\"{$data['supplier_id']}\" {$selected}>{$data['supplier_name']}</option>";
            }
        }
        else {
            $result .= "<option value=\"\">无供应商</option>";
        }
        
        echo '<select name="supplier_id" id="supplier_id">'.$result.'</select>';
        
        exit;
    }
    
    /**
     * 修改产品批次排序(ajax调用)
     *
     * @return void
     */
    public function batchChangeSortAction()
    {
        $batch_id = (int)$this -> _request -> getParam('batch_id', 0);
        $sort = (int)$this -> _request -> getParam('sort', 0);
        
        if (!$batch_id || !$sort) {
            exit;
        }
        
        $this -> _api -> updateBatch(array('sort' => $sort), "batch_id = {$batch_id}");
        
        exit;
    }
    
    /**
     * 获得产品编码前缀(ajax调用)
     *
     * @return void
     */
    function getProductPrefixSnAction()
    {
        $catID = (int)$this -> _request -> getParam('catID', 0);
        if (!$catID) {
            exit;
        }
        
        $productSN = $this -> _api -> getProductPrefixSn($catID);
        if (!$productSN) {
            die('error');
        }
        
        die($productSN);
    }



    /**
     * 产品国际码列表
     *
     * @return void
     */
    function barcodeAction()
    {
        $type = (int)$this -> _request -> getParam('type', 1);
		if($type == '1'){
			$page = (int)$this -> _request -> getParam('page', 1);
			$search = $this -> _request -> getParams();
			$datas = $this -> _api -> get($search, '*', $page);
			$total = $this -> _api -> getCount();
			$this -> view -> datas = $datas;
			$this -> view -> param = $this -> _request -> getParams();
			$pageNav = new Custom_Model_PageNav($total, 20, 'ajax_search');
			$this -> view -> pageNav = $pageNav -> getNavigation();
		}else{
			$page = (int)$this -> _request -> getParam('page', 1);
			$search = $this -> _request -> getParams();
			$search['p_status']='0';
			$search['status_id']='2';
			$search['logic_area']='1';
			$datas = $this -> _api -> getStockStatus($search, '*', $page);
			$total = $this -> _api -> getCount();
			$this -> view -> datas = $datas;
			$this -> view -> param = $this -> _request -> getParams();
			$pageNav = new Custom_Model_PageNav($total, 20, 'ajax_search');
			$this -> view -> pageNav = $pageNav -> getNavigation();
		}
    }

	/**
	 * 礼品卡金额列表
	 *
	 */
	public function giftcardPricelistAction()
	{
		$page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
        $count  = $this->_api->getGiftcardCount($params);
		
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_api->getGiftcardList($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
        $this -> view -> pageNav =$pageNav->getNavigation();
        $this -> view ->infos = $infos;
		$this -> view ->params = $params;
	}

	public function changeAjaxGiftproductAction()
	{
		$this -> _helper -> viewRenderer -> setNoRender();
		$product_id = $this->_request->getParam('product_id', 0);
		$amount  = $this->_request->getParam('amount', 0);

		if (intval($product_id) == 0) {
			exit(json_encode(array('success' => 'false', 'message' => '产品ID不正确')));
		}

		if (ceil($amount) <= 0) {
			exit(json_encode(array('success' => 'false', 'message' => '金额不能小于等于0')));
		}

		if (false === $this->_api->updateGiftcardAmountByProductid($product_id,$amount)) {
			exit(json_encode(array('success' => 'false', 'message' => $this->_api->getError())));
		}

		$gift_info = $this->_api->getGiftcardInfoByProductid($product_id);
		exit(json_encode(array('success' => 'true', 'message' => '操作成功', 'data' => $gift_info)));
	}
    
    public function productCostAction()
    {
        $param = $this -> _request -> getParams();
        if ($param['detail']) {
            $reportParam['fromdate'] = strtotime('2013-05-27');
            $reportParam['todate'] = time();
        }
        $result = $this -> _api -> calculateProductCostByInitCost($param['product_id'], false, false, $reportParam);
        
        if ($param['detail']) {
            var_dump($result);
        }
        else {
            echo date('Y-m-d H:i:s');
        }
        
        exit;
    }
}