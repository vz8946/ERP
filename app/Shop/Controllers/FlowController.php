<?php
class FlowController extends Zend_Controller_Action
{
    /**
     * 
     * @var Shop_Models_API_Cart
     */
	protected $_api = null;
	protected $_session = 'cart';
    protected $user = null;
    protected $_auth = null;
	public function init()
    {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	    $this ->_auth = Shop_Models_API_Auth :: getInstance();
	    $this -> user = $this ->_auth -> getAuth();
		$this -> _api = new Shop_Models_API_Cart();
        $this -> _session = new Zend_Session_Namespace($this -> _userCertificationName);	  
        $this -> view -> auth = $this -> user;
        $this -> view->css_more=',cart.css';
	}

    /**
     * 指定条件的地区列表JSON数据
     *
     * @return void
     */
    public function listAreaByJsonAction()
    {
        $areaId = intval($this -> _request -> getParam('area_id', null));
        if ($areaId) {
            echo $this -> _api -> getAreaJsonData(array('parent_id' => $areaId));
        }
        exit;
    }

    /**
     * 放入购物车
     *
     * @return void
     */
    public function buyAction()
    {
        $productSN = $this -> _request -> getParam('product_sn', null);
        $number = $this -> _request -> getParam('number', 1);
        $this -> _api -> buy($productSN, $number);
        header('Location: ' . $this -> getFrontController() -> getBaseUrl() . '/flow/index/');
    }

    /**
     * 活动页面商品快速放入购物车
     *
     * @return void
     */
    public function actbuyAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $productSN = $this -> _request -> getParam('product_sn', null);
        $number = $this -> _request -> getParam('number', 1);
        echo $this -> _api -> buy($productSN, $number);
        exit;
    }

    
    /**
     * 批量检查库存和限购数量
     *
     * @return void
     */
    public function actbuyBatchAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $arr_goods_id = $this -> _request -> getParam('arr_goods_id', null);
        if(empty($arr_goods_id)) die;
        
        $list_goods = $this->_api->getAll('shop_goods',array('goods_id|in'=>(array)$arr_goods_id),'goods_id,product_id');
        $arr_product_id = Custom_Model_Tools::getListFiled('product_id', $list_goods);
        $stockAPI = new Admin_Models_API_Stock();
        $list_product = $this->_api->getAll('shop_product',array('product_id|in'=>$arr_product_id));
        if (!$list_product) die;
         
        $arr_unstock_product = array();
        $stockAPI = new Admin_Models_API_Stock();
        $arr_psn = array();
        foreach ($list_product as $k=>$v){            	
            $id = $v['product_id'];
            $goodsID = $v['goods_id'];
            $productSN = $v['product_sn'];
            $arr_psn[] = $productSN;
            if ($stockAPI -> checkPreSaleProductStock($id, 1)) continue;
            
            $stock = $stockAPI -> getSaleProductStock($id,true);
            if ($stock['able_number'] <= 0) {
                $goodsDB = new Shop_Models_DB_Goods();
                $goodsDB -> updateStatus(1, $productSN, $goodsID);
            }             
            $arr_unstock_product[] = $v['product_name'];             
        }
         
        if(!empty($arr_unstock_product)) Custom_Model_Tools::ejd('fail',implode(',', $arr_unstock_product).' 库存不足！');
        
        $cart = array();
        foreach ($arr_psn as $v){
            $this -> _api -> buy($v, 1, $cart);
        }
        
        Custom_Model_Tools::ejd('succ','批量加入购物车成功！');
         
    }    
    
    /**
     * 修改购物车中的指定商品的数量
     *
     * @return void
     */
    public function changeAction()
    {
        $productID = intval($this -> _request -> getParam('product_id', 0));
        $number = intval($this -> _request -> getParam('number', 0));
        $number = $number > 0 ? $number : abs($number);
        if ($productID && $number) {
            echo $this -> _api -> change($productID, $number);
        }
        exit;
    }

    /**
     * 显示购物车商品数量
     *
     * @return void
     */
    public function countAction()
    {
        $product = $this -> _api -> getCartProduct();
        echo "document.write('购物车:<b>" . intval($product['number']) . "</b>件商品');";
        exit();
    }

    /**
     * 用户地址删除
     *
     * @return void
     */
    public function delAddrAction() {    	
        if (!$this -> user) {
            $goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/flow/index/');
            $url = $this -> getFrontController() -> getBaseUrl()."/auth/login/goto/" . $goto;            
            echo Zend_Json::encode(array('status'=>0,'msg'=>'请先登录！','url'=>$url));
            exit;
            
        }
        
        $addrID = intval($this -> _request -> getParam('addr_id', null));
        $this -> _api -> setSetting('addr',0);
        $this -> _api -> delAddr(array('address_id' => $addrID, 'member_id' => $this -> user['member_id']));
        echo Zend_Json::encode(array('status'=>1,'msg'=>'删除地址成功'));
        exit;
    }

    /**
     * 用户地址编辑，新增
     *
     * @return void
     */
    public function editAddAddrAction() {
        if (!$this -> user) {
        	echo  Zend_Json::encode(array('status'=>0,'msg'=>'请先登录'));
        	exit;
        }
        $addrID = intval($this -> _request -> getParam('address_id', null));
        
        $phone = '';
        if($this -> _request -> getParam('phone', ''))
        {
          $phone = 	$this -> _request -> getParam('phone_code', '') . '-' . $this -> _request -> getParam('phone', '');
          if($this -> _request -> getParam('phone_ext', ''))
          {
          	$phone .='-'.$this -> _request -> getParam('phone_ext', '');
          }
        }
        
        $data = array('member_id' => $this->user['member_id'],
                      'consignee' => trim($this -> _request -> getParam('consignee', '')),
                      'province_id' => intval($this -> _request -> getParam('province_id', null)),
                      'city_id' => intval($this -> _request -> getParam('city_id', null)),
                      'area_id' => intval($this -> _request -> getParam('area_id', null)),
                      'address' => $this -> _request -> getParam('address', ''),
                      'phone' => $phone,
                      'mobile' => $this -> _request -> getParam('mobile', ''),
        			  'zip' => $this -> _api -> getAreaZip(intval($this -> _request -> getParam('area_id', null))),
                      'add_time' => time());
        if (!$addrID) { //会员添加地址
            if ($this -> _api -> countAddr() >= 5) {
                echo  Zend_Json::encode(array('status'=>0,'msg'=>'填写地址不能超过5个'));
                exit;                
            }
            $value = $this -> _api -> addAddr($data);
        }else{ //会员修改地址
            $this -> _api -> editAddr(array('address_id' => $addrID, 'member_id' => $this -> user['member_id']), $data);
            $value = $addrID;
        }
        
        $this->view ->address_id = $value;
        $this -> _api -> setSetting('addr', $value);
        $address = array_shift($this -> _api -> getAddress(array('address_id' => $this -> _api -> getSetting('addr'),
                                                                 'member_id' => $this -> user['member_id'])));
        $this -> _api -> setSetting('logistic', $this -> _api -> getLogistic($address['area_id']));
        
        
        $tips = ' <div class="arrive_addr arrive_addr_complete "><h2><b>收货人信息</b>  <span class="step-action" id="consignee_edit_action"><a onclick="editAddressInfo('.$address['address_id'].',\'show\');" href="javascript:;">修改</a></span></h2>';
        $tips .= "<div class='addr_selected'><b>{$address['consignee']}</b>{$address['province_name']} {$address['city_name']} {$address['area_name']} {$address['address']}   {$address['phone']}  {$address['mobile']}</div></div>";
               
        $cartGoods = Shop_Models_API_Cart :: makeCartGoodsToArray();
        $product = $this -> _api -> getCartProduct($cartGoods);
        $logistic = $this -> _api -> getSetting('logistic');
        $priceLogistic=$this ->_api->getLogisticPrice($product);
        $shippingTips = "{$logistic['label']} ({$priceLogistic}元)";
        
        $tpl = $this -> _request -> getParam('tpl', 'addr');
        if($tpl == 'addr_gift')
        {
         $addressList=$this -> _api -> getAddress(array('member_id' => $this -> user['member_id']));
         $this -> view -> addressList = $addressList;
         $tips =   $this->view->render('flow/addr_gift.tpl');
        }
        
        echo  Zend_Json::encode(array('status'=>1,'msg'=>'地址提交成功','tips'=>$tips,'shippingTips'=>$shippingTips));
        exit;
        
    }

    /**
     * 写入用户地址
     *
     * @return void
     */
    public function setAddrAction()
    {
        if (!$this -> user) {
            echo  Zend_Json::encode(array('status'=>0,'msg'=>'请先登录'));
        	exit;
        }
        $addrID = intval($this -> _request -> getParam('addr_id', null));
        if ($addrID) {
            $this -> _api -> setSetting('addr', $addrID);
            $address = array_shift($this -> _api -> getAddress(array('address_id' => $this -> _api -> getSetting('addr'),
                                                                     'member_id' => $this -> user['member_id'])));
            if (!$address['province_id'] || !$address['city_id'] || !$address['area_id']) {
                echo  Zend_Json::encode(array('status'=>0,'msg'=>'地址信息错误'));
        	    exit;
            }
          $this -> _api -> setSetting('logistic', $this -> _api -> getLogistic($address['area_id']));  
          
          $tips = '<div class="arrive_addr arrive_addr_complete "><h2><b>收货人信息 </b>  <span class="step-action" id="consignee_edit_action"><a onclick="editAddressInfo('.$addrID.',\'show\');" href="javascript:;">修改</a></span></h2>';         
          $tips .= "<div class='addr_selected'><b>{$address['consignee']}</b>{$address['province_name']} {$address['city_name']} {$address['area_name']} {$address['address']}   {$address['phone']}  {$address['mobile']}</div></div>";
          
          $cartGoods = Shop_Models_API_Cart :: makeCartGoodsToArray();
          $product = $this -> _api -> getCartProduct($cartGoods);
          $logistic = $this -> _api -> getSetting('logistic');
          $priceLogistic=$this ->_api->getLogisticPrice($product);          
          $shippingTips = "{$logistic['label']} ({$priceLogistic}元)";      
              
          echo  Zend_Json::encode(array('status'=>1,'msg'=>'地址设置成功','tips'=>$tips,'shippingTips'=>$shippingTips));
          exit;

        }else{
            echo  Zend_Json::encode(array('status'=>0,'msg'=>'参数错误'));
        	exit;
        }
    }

	/**
	 * ajax检查 是否登录   (在flow/index.tpl模板中用到)
	 *
	 * @return json
	 */
	public function checkLoginAction() {
		if($this -> user){echo json_encode(array('status'=>'yes'));}
		else {echo json_encode(array('status'=>'no'));}
		exit;
	}

	/**
	 * ajax 写入 session 地址
	 *
	 * @return json
	 */
	public function setAddrToSessionAction() {
		if(isset($_POST)){
			$_SESSION['fastBuyAddr']=$_POST;
			echo json_encode(array('status'=>'yes'));
		}else{
			echo json_encode(array('status'=>'no'));
		}
		exit;
	}

  

    /**
     * 删除购物车中的抵用券
     *
     * @return void
     */
    public function delNormalCard()
    {
       // 删除session中的抵用券
       if ($_SESSION['Card']) {
           unset($_SESSION['Card']);
       }
    }

    /**
     * 删除购物车中的指定商品
     *
     * @return void
     */
    public function delAction()
    {
        $productID = intval($this -> _request -> getParam('product_id', null));
        $number = intval($this -> _request -> getParam('number', 0));
        $this -> _api -> del($productID, $number);
        $this -> delNormalCard();
        //start:删除gift中的赠品
        $tmp = $_COOKIE['gift'];
        if($tmp!==NULL){
	        $tmp = explode(',', $tmp);
	        foreach ($tmp as $k => $v){
	        	if(strpos($v, $productID."_")>-1){
	        		unset($tmp[$k]);
	        	}
	        }
	        setcookie('gift', implode(',', $tmp), time () + 86400 * 365, '/');
        }
        //end:删除gift中的赠品
        header('Location: ' . $this->getFrontController() -> getBaseUrl() . '/flow/index/');
    }

    /**
     * 清空购物车
     *
     * @return void
     */
    public function clearAction()
    {
        $this -> _api -> setCartCookie('');
        setcookie('p', '', time () + 86400 * 2, '/');
        setcookie('gift', '', time () + 86400 * 2, '/');
        setcookie('groupgoods', '', time () + 86400 * 2, '/');
        setcookie('goods_gift','',-1,'/');        
        setcookie('order_gift','',-1,'/');
        $this -> delNormalCard();
        header('Location: '.$this -> getFrontController() -> getBaseUrl().'/flow/index/');
    }

	/**
     * ajax清空购物车
     *
     * @return void
     */
    public function ajaxclearAction()
    {
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
    	$this -> _api -> setCartCookie('');
        setcookie('p', '', time () + 86400 * 2, '/');
        setcookie('gift', '', time () + 86400 * 2, '/');
        setcookie('groupgoods', '', time () + 86400 * 2, '/');
        $this -> delNormalCard();
        exit;
    }

    /**
     * 购物车商品列表
     *
     * @return void
     */
    public function indexAction()
    {
       $cat_products=$this -> _api -> getCartProduct();
       if($cat_products['number']<1){
       		header('Location: '.$this -> getFrontController() -> getBaseUrl().'/');
       }
       $this -> view -> products = $cat_products;
       $this -> view -> amount = $cat_products['amount'];
	   if($cat_products['goods_amount']>10 && $cat_products['goods_amount']<199){
		    $priceLogistic = $this -> _api -> getLogisticPrice($cat_products);
		    if ( $priceLogistic > 0 ) {
			    $this -> view -> show_msg_freight = 1;
			    $this -> view -> goods_freight_amount =(199-$cat_products['goods_amount']);
			}
		}
        if (!isset($this -> view -> products['data'])) {
            $goOn = '/';
        } else {
            $this -> view -> cur_place = 'cart';
            $goOn = $this -> getFrontController() -> getBaseUrl();
            if(isset($this -> view -> products['data'])){
                $lastGoodsCatId = $this -> view -> products['data'][count($this -> view -> products['data'])-1]['view_cat_id'];
                if ($lastGoodsCatId) {
                    $goOn = $this -> getFrontController() -> getBaseUrl().'/gallery-'.$lastGoodsCatId.'-0-0-0-1.html';
                }
            }
        }

        $goodIds = array();
        if($cat_products['data']){
	        foreach ($cat_products['data'] as $val)
	        {
	       	   if (intval($val['goods_id'])>0) $goodIds[] = $val['goods_id'];
	        }
        }
        
        //猜你喜欢
        if($goodIds && count($goodIds)>0)
        {
	        $goodsApi  =  new Shop_Models_API_Goods();
	        $gkey =rand(0, count($goodIds)-1);
	        $links = $goodsApi->guessGoods($goodIds[$gkey],$goodIds);
        }
        
        $this -> view -> links = $links;
        $this ->view->js_more=",cart.js"; //加载购物车js       
        $this -> view -> goOn = $goOn;
        $this -> view -> page_title = "垦丰电商在线购买  垦丰电商 -专业的种子商城";
        $this -> view -> page_keyword = "在线购买,种子,垦丰";
        $this -> view -> page_description = '垦丰电商 -专业的种子商城，品质保障! ';
    }

    /**
     * 选择会员配送地址
     *
     * @return void
     */
    public function addrAction()
    {            
            //---------收货地址 添加  编辑-------
            if (!$this -> user) {
            	$goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/flow/index/');
            	$url = "{$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto;
            	echo Zend_Json::encode(array('status'=>0,'url'=>$url,'msg'=>'请先登录'));
            	exit();
            }
            
            if ($addrID = intval($this -> _request -> getParam('addr_id', null))) {//修改页面
                $address = array_shift($this -> _api -> getAddress(array('address_id' => $addrID, 'member_id' => $this -> user['member_id'])));
                $tmp = explode('-', $address['phone']);
                $address['phone_code'] = $tmp[0];
                $address['phone'] = $tmp[1];
                $address['phone_ext'] = $tmp[2];
                $this->view->address_id = $addrID;
            }
            
            $this -> view -> province = $this -> _api -> getArea(array('parent_id' => 1));
            if ($address['province_id']) {
                $this -> view -> city = $this -> _api -> getArea(array('parent_id' => $address['province_id']));
            }
            if ($address['city_id']) {
                $this -> view -> area = $this -> _api -> getArea(array('parent_id' => $address['city_id']));
            }
            $this -> view -> address = $address;
            $addressList=$this -> _api -> getAddress(array('member_id' => $this -> user['member_id']));
            $this -> view -> addressList = $addressList;
            $this -> view -> error = $this -> _request -> getParam('error', null);
            $this -> view -> type = $this -> _request -> getParam('type', 'edit');
            $tpl = $this -> _request -> getParam('tpl', 'addr');
            if ($tpl == 'addr') {
            	$html = $this->view->render('flow/addr.tpl');
            }else{
            	$html = $this->view->render('flow/addr_gift.tpl');
            }          
            echo Zend_Json::encode(array('status'=>1,'html'=>$html,'msg'=>''));
            exit();       
        
    }

    /**
     * 显示支付方式
     *
     * @return void
     */
    public function paymentAction() {
        if (!$this -> user) {
            	$goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/flow/index/');
            	$url = "{$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto;
            	echo Zend_Json::encode(array('status'=>0,'url'=>$url,'msg'=>'请先登录'));
            	exit();
        }
        
        $address = array_shift($this -> _api -> getAddress(array('address_id' => $this -> _api -> getSetting('addr'),
                                                                 'member_id' => $this -> user['member_id'])));
        $logistic = $this -> _api -> getSetting('logistic');       
        $showPayment = $this -> _api -> showPayment($logistic);
        
        $payType = $this ->_api-> getSetting('payment');        
        $this -> view -> payType = $payType;
        $this -> view -> payment = $showPayment['payment'];
        $this -> view -> address = $address;
        $this -> view -> logistic = $logistic['label'];
        
        $html =  $this->view->render('flow/payment.tpl');        
        echo Zend_Json::encode(array('status'=>1,'html'=>$html,'msg'=>''));
        exit();
        
    }

    /**
     * 设置支付方式  
     *
     * @return void
     */
    public function setPaymentAction() {
    	if (!$this -> user) {
    		$goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/flow/index/');
            $url = "{$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto;
            echo Zend_Json::encode(array('status'=>0,'url'=>$url,'msg'=>'请先登录!'));
            exit();
    	}
    	
    	$cart = $cart ? $cart : $this->_api -> getCartProduct();
    	if($cart['number'] <= 0){//购物车没有商品
    		echo Zend_Json::encode(array('status'=>0,'url'=>'/flow/index/','msg'=>'提示：您的购物车内没有商品！'));
    		exit();
    	}
    	
    	
    	$payType = $this -> _request -> getParam('pay_type', null);
    	$this -> _api -> setSetting('payment', $payType);  
    
    	//验证支付方式
        $payType = $this ->_api-> getSetting('payment'); 
        if (!$payType) { //没有支付方式
            echo Zend_Json::encode(array('status'=>0,'msg'=>'提示：请选择支付方式！'));
            exit();
        } else if ($payType == 'cod' && $cart['number'] == 1 && $cart['data'][0]['product_id'] == 595) {
            $this -> setSetting('payment', null);
            echo Zend_Json::encode(array('status'=>0,'msg'=>'提示：只领取免费商品不能使用货到付款，请更换支付方式！'));
            exit();
        }
       
        
       //这里重新读取是为了防止有人恶意的攻击，修改支付方式，特别是货到付款
        /* $logistic = $this ->_api->getSetting('logistic');        
        if ($logistic['cod']) {
           $payment = array_shift($this ->_api -> getPayment(array('status' => 0, 'pay_type' => $payType)));
        } else {
           $payment = array_shift($this -> _api -> getPayment(array('status' => 0, 'pay_type' => $payType,'pay_type!=' => 'cod')));
        }
       */
        
        $payment = array_shift($this -> _api -> getPayment(array('status' => 0, 'pay_type' => $payType)));
        if(!$payment){  //没有支付方式        	
        	echo Zend_Json::encode(array('status'=>0,'msg'=>"提示：当前的支付方式不正确。可能是以下原因：\n\n 1.您还未选择支付方式；\n\n 2.您之前选择的货到付款不支持现在的配送地址；\n\n请重新选择支付方式！"));
        	exit();        	
        } 
        
        $tips = '<h2><b>支付方式</b>  <span class="step-action" id="payment_edit_action"><a onclick="editPayment();" href="javascript:;">修改</a></span></h2>';
        if($payment['img_url'])
        {
        	$tips .= '<div class="pay_method_selected"><b>在线支付</b>  <img  alt="'.$payment['name'].'" src="'.$payment['img_url'].'" /></div>';
        }else
        {
        	$tips .= '<div class="pay_method_selected"><b>线下支付</b> '.$payment['name'].'</div>';
        } 
        echo Zend_Json::encode(array('status'=>1,'msg'=>"设置成功",'tips'=>$tips));
        exit();
    }
    
	/*
	*  快速购买  （非注册会员/没登陆会员）
	*
	*  @return void
	*/
	public function fastTrackBuyAction()
	{
		//如果登录了，跳转到 /flow/order
		if($this -> user){
			$this->_redirect('/flow/order');
		}
		//session['fastBuyAddr']中是否存储了地址信息（只有没登陆下才会有session['fastBuyAddr']）
		if(isset($_SESSION['fastBuyAddr']) && count($_SESSION['fastBuyAddr'])){
			$this -> view -> msg = $_SESSION['fastBuyAddr'];
			if ($this -> view -> msg['province_id']) {
                $this -> view -> city = $this -> _api -> getArea(array('parent_id' => $this -> view -> msg['province_id']));
            }
            if ($this -> view -> msg['city_id']) {
                $this -> view -> area = $this -> _api -> getArea(array('parent_id' => $this -> view -> msg['city_id']));
            }
            //支付方式
            $this -> view -> cod = $_SESSION['fastBuyAddr']['pay_type'];
		}
		//送货地址
		$this -> view -> province = $this -> _api -> getArea(array('parent_id' => 1));
		//快速购买用户ID
		$this -> _shopConfig = Zend_Registry::get('shopConfig');
		$fast_track_id = $this -> _shopConfig['fast_track_id'];

		//可选支付方式
        $logistic = $this -> _api -> getSetting('logistic');
        $showPayment = $this -> _api -> showPayment($logistic);
       // $this -> view -> payType = $showPayment['defaultPayment'];
        $this -> view -> payment = $showPayment['payment'];
        $this -> view -> mobile = $this -> _api -> getSetting('mobile');
        
		//商品列表
		$cartGoods = Shop_Models_API_Cart :: makeCartGoodsToArray();
        $product = $this -> _api -> getCartProduct($cartGoods);
        if($product['goods_amount']<0){
        	Custom_Model_Message::showAlert('您的购物车内没有商品！', false, '/');
            exit;
        }
        
        $this -> view -> product = $product;
        $priceLogistic=$this ->_api->getLogisticPrice($product);
        $this -> view -> priceLogistic = $priceLogistic;

        $pricePay=$product['amount']+$priceLogistic-$_SESSION['price_account']-$_SESSION['price_point'];

        //总费用
        $this -> view -> pricePay =$pricePay;
        
        $this->view->js_more=",check.js,order.js";

	}
   
	/**
	 * 配送设置
	 */
	public function setDeliveryAction()
	{
		$invoice_type = $this->_request->getParam('invoice_type',0);
		$invoice = $this->_request->getParam('invoice',null);
		$warehouse_id = $this->_request->getParam('warehouse_id', '');
		$warehouse_type = $this->_request->getParam('warehouse_type', '');
		$licence = $this->_request->getParam('licence', '');
		$Tariff = $this->_request->getParam('Tariff', '');
		if($warehouse_type == 0) $warehouse_id = 0;
		$delivery['invoice_type'] = $invoice_type;
		$delivery['invoice_name'] = $invoice_name = $invoice[$invoice_type]?$invoice[$invoice_type]:'';
		$delivery['invoice_content']  = $this->_request->getParam('invoice_content',null);
		$delivery['warehouse_id'] = $warehouse_id;
		if($invoice_type=='1') $delivery['licence'] = $licence;
		if($invoice_type=='2') $delivery['Tariff'] = $Tariff;
		$this ->_api->setSetting('delivery', $delivery);
		$tips = '<h2><b>配送与发票</b> <span class="step-action" id="invoice_edit_action"><a href="javascript:;" onclick="editDelivery()">修改</a></span></h2>  <div class="invoiceInfo"> <!--<em class="c2">提示：由于公司搬迁要更换税务号，元旦前暂无法开具发票。即日起所有需开票的订单，将先安排发货，发票元旦后统一以挂号信形式寄出。</em>--> ';
		if($warehouse_id == '')$tips.='<p><b>配送方式</b>&nbsp;快递配送</p>';
		if ($delivery['warehouse_id'] != '') {
			$warehouse_infos = $this->_api->getWarehouseInfos();
			$tips .='<p><b>上门自提</b>&nbsp;'.$warehouse_infos[$delivery['warehouse_id']].'</p>';
		}
		if($invoice_type == 1)
		{
		  $tips.='<p><b>发票信息</b>&nbsp;个人：'.$invoice_name.'</p><p><b>证件号码</b>：'.$licence.'</p><p><b>发票内容</b>&nbsp;'.$delivery['invoice_content'] .'</p>';
		}elseif ($invoice_type == 2){
		  $tips.='<p><b>发票信息</b>&nbsp;单位：'.$invoice_name.'</p><p><b>发票内容</b>&nbsp;'.$delivery['invoice_content'].'</p><p><b>税号</b>&nbsp;'.$Tariff.'</p>';
		}else{
		  $tips.='<p><b>发票信息</b>&nbsp;不开发票</p>';
		}
		
		$tips.='</div>';		
		echo Zend_Json::encode(array('status'=>1,'msg'=>"设置成功",'tips'=>$tips));
		exit();
	}
	
	public function deliveryAction()
	{
		$delivery = $this->_api->getSetting('delivery');
		$this->view->delivery = $delivery;
		$this->view->warehouse = $this->_api->getWarehouseInfos();
		$html =  $this->view->render('flow/delivery.tpl');
		echo Zend_Json::encode(array('status'=>1,'msg'=>"请求成功",'html'=>$html));
		exit();
	}
	
	
    /**
     * 订单详细页
     *
     * @return void
     */
    public function orderAction()
    {
        if (!$this -> user) {
        	$this -> _redirect('/flow');
            $goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/flow/index/');
            header("Location: {$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto);
            exit;
        }
        
    	$union = new Shop_Models_API_Union();
    	$this -> _uid = $union -> getUidFromCookie();
        $cartGoods = Shop_Models_API_Cart :: makeCartGoodsToArray();
       
        $product = $this -> _api -> getCartProduct($cartGoods);

		if($this -> _uid =='8966'){//交通银行 
			if ($product['goods_amount']>0 && $this -> _request -> isPost() && $paymentType = $this -> _request -> getParam('pay_type', null)  ) {
				if($paymentType =='cod' || $paymentType =='bankcomm' ){
					$this -> _api -> setSetting('payment', $paymentType);
				}else{
                    $this -> _api -> setSetting('payment', 'bankcomm');
                }
			} else{
				$this -> _api -> setSetting('payment', 'bankcomm');
			}
		}
        else{
            if($product['goods_amount']>0){
                //没有设置支付方式时就默认设置为用户的历史信息
                if(!$this -> _api -> getSetting('payment') ) { 
                	if( $this -> user['last_pay_type']){
                      $this -> _api -> setSetting('payment', $this -> user['last_pay_type']);
                	}else{
                	  $this -> _api -> setSetting('payment','alipay');
                	}                	
                }
             
                if (!$this -> _api -> getSetting('mobile') && $this -> user['mobile'] && $this -> user['check_mobile']) {
                	$this -> _api -> setSetting('mobile', $this -> user['mobile']);
                }
                
                if (!$this -> _api -> getSetting('delivery')) {
                	if($this -> user['last_invoice'])
                	{
                		$this -> _api -> setSetting('delivery', unserialize($this -> user['last_invoice']));
                	}else{
                		$delivery = array();
                		$delivery['invoice_type'] = 0;
                		$delivery['invoice_name'] = '';
                		$this -> _api -> setSetting('delivery', $delivery);
                	}                	
                }             
                
            }else{
                 $this -> _api -> setSetting('payment', 'alipay');
            } 
		}
		
        $address = array_shift($this -> _api -> getAddress(array('address_id' => $this -> _api -> getSetting('addr'),
                                                          'member_id' => $this -> user['member_id'])));
        // 检查订单
        $this -> _api -> checkOrder($product, $address);
        //设置顾客最后一次购物习惯
        $this -> _api -> habit();
        $logistic = $this -> _api -> getSetting('logistic');      
        $priceLogistic=$this ->_api->getLogisticPrice($product); //运费

        $memberApi = new Shop_Models_API_Member();
        $this -> view -> member = array_shift($memberApi -> getMemberByUserName($this -> user['user_name']));
        $this -> view -> pointInSession = $_SESSION['price_point']*100;
        $this -> view -> accountInSession = $_SESSION['price_account'];
        $this ->view -> mobile = $this -> _api -> getSetting('mobile');
        $this -> view -> product = $product;
        $this -> view -> address = $address;
        $this -> view -> logistic = $logistic['label'];
        
        $delivery = $this->_api->getSetting('delivery');
        $this->view->delivery = $delivery;
        
        //判断订单中是否只有团购商品，或者只有虚拟商品，如果是，则不能货到付款
        $payment_type = $this->_api->getSetting('payment');
        if ($payment_type == 'cod') {
            $onlyCod = true;
            if ($product['data']) {
                if ($product['only_vitual']) {
                }
                else {
                    foreach ( $product['data'] as $data ) {
                        if ( !$data['tuan_id'] ) {
                            $onlyCod = false;
                            break;
                        }
                    }
                }
            }
            else    $onlyCod = false;
            if ( $onlyCod ) {
                $payment_type = 'alipay';
            }
        }
        
		$coupon_infos = array();
		$gift_infos   = array();
		if ($this->user['user_id']) {
			$card_db = new Shop_Models_DB_Card();
			$coupon_infos  = $card_db->getCouponInfosByUserId($this ->user['user_id']);			
			$gift_infos    = $card_db->getGiftInfosByUserId($this ->user['user_id']);
		}
		
		if($payment_type){
		   $payment = array_shift($this->_api-> getPayment(array('pay_type' => $payment_type)));
           $this -> view -> payment = $payment;
		}
		
        $this -> view -> priceLogistic = $priceLogistic;
        $this -> view -> priceAccount = $_SESSION['price_account'];
        $this -> view -> pricePoint = $_SESSION['price_point'];

        //总费用
        $pricePay=$product['amount']+$priceLogistic-$_SESSION['price_account']-$_SESSION['price_point'];        
        $this -> view -> pricePay =$pricePay;

        $shopConfig = Zend_Registry::get('shopConfig');
        if (!isset($shopConfig['min_point_to_price'])) {
            $shopConfig['min_point_to_price'] = 100;
        }
        $this -> view -> minPointToPrice = $shopConfig['min_point_to_price'];
        $warehouse_infos = $this->_api->getWarehouseInfos();
		$this->view->warehouse_name = $warehouse_infos[$delivery['warehouse_id']];
        //加载订单js
        $this->view->js_more=",check.js,order.js";        
		$this->view->coupon_infos = $coupon_infos;
		$this->view->gift_infos   = $gift_infos;
        $this -> view -> page_title = "垦丰电商订单  垦丰电商 -专业的种子商城";
        $this -> view -> page_keyword = "垦丰电商订单,种子,收货地址";
        $this -> view -> page_description = '垦丰电商 -专业的种子商城，品质保障! ';
    }

    /**
     * 检查账户余额支付
     *
     * @return void
     */
    public function checkPriceAccountAction()
    {
        $price = $this -> _request -> getParam('price_account', 0);
        $memberApi = new Shop_Models_API_Member();
        $member = array_shift($memberApi -> getMemberByUserName($this -> user['user_name']));

        $cartGoods = $this ->_api -> makeCartGoodsToArray();
        $product = $this -> _api -> getCartProduct($cartGoods);
        $address = array_shift($this -> _api -> getAddress(array('address_id' => $this -> _api -> getSetting('addr'),
                                                                 'member_id' => $this -> user['member_id'])));

        $priceLogistic=$this ->_api->getLogisticPrice($product);
        $payed = $product['amount'] + $priceLogistic - $_SESSION['price_point'] - $priceCoupon;
        if ($price < 0) {
            echo 'isNegative';
        } else if ($price > $member['money']) {
            echo 'bigThanAccount';
        } else if ($price > $payed) {
            echo 'bigThanPayed';
        } else {
           $_SESSION['price_account'] = $price;
           echo 'success';
        }
        exit;
    }

    /**
     * 检查积分支付
     *
     * @return void
    */
    public function checkPricePointAction()
    {
        $point = $this -> _request -> getParam('price_point', 0);
        $price = $point / 100;
        $memberApi = new Shop_Models_API_Member();
        $member = array_shift($memberApi -> getMemberByUserName($this -> user['user_name']));
        $product = $this -> _api -> getCartProduct();

        $shopConfig = Zend_Registry::get('shopConfig');
        if (!isset($shopConfig['min_point_to_price'])) {
            $shopConfig['min_point_to_price'] = 100;
        }
        $payed = $product['amount'] - $_SESSION['price_account'];//可以抵扣金额 注意：+ $priceLogistic 运费不参与积分抵扣

        $cards = $_SESSION['Card']['cardCertification'];//取抵用券信息

        if ($point && $cards) {//积分，礼金券，礼品卡只能存在一种
            echo 'useCard';
        } else if ($point < 0) {
            echo 'isNegative';
        } else if ($point > 0 && $point < $shopConfig['min_point_to_price']) {//积分兑换最低为多少积分起竞
            echo 'bigThan500';
        } else if ($point % 100 != 0) {//积分兑换最低为多少积分起竞 并却累加要是其的整数倍
            echo 'divisionWith100';
        } else if ($point > $member['point']) {
            echo 'bigThanPoint';
        } else if ($price > $payed) {
            echo 'bigThanPayed';
        } else {
           $_SESSION['price_point'] = $price;
           echo 'success';
        }
        exit;
    }

    
    public function checkOrderStepAction()
    {
    	$addressID = $this ->_api-> getSetting('addr');
    	$payment  = $this ->_api-> getSetting('payment');
    	$delivery  = $this->_api-> getSetting('delivery');
    	$addressFlag = $paymentFlag = $deliveryFlag = true;
    	if (!$addressID)
    	{
    		$addressFlag = false;
    		$msg = '如需修改，请先保存收货人信息';
    	}   	
       
        if (!$payment)
        {
        	$paymentFlag = false;
        	$msg = '如需修改，请先保存支付方式';
        }
       
        if (!$delivery)
        {
          $deliveryFlag = false;
          $msg = '如需修改，请先保存配送与发票信息';
        }
        
        echo Zend_Json::encode(array('status'=>1,'msg'=>$msg,'address'=>$addressFlag,'payment'=>$paymentFlag,'delivery'=>$deliveryFlag));
        exit();
    }
    
    /**
     * 提交订单
     *
     * @return void
     */
    public function addOrderAction()
    {
    	//传递$fastTackBuy参数，判断是否为快速购买
        $fastTackBuy = $this -> _request -> getParam('fastTackBuy', 0);
        if($fastTackBuy){
        	//支付方式
        	$payment = $this -> _request -> getParam('pay_type', '');
        	if($payment == ''){
        		Custom_Model_Message::showAlert('请选择支付方式' , true, '/flow/fast-track-buy/');
        	}else{
        		$this -> _api -> setSetting('payment', $payment);
        		$this -> _api -> setSetting('logistic', $payment);
        	}
        	
        	$cartGoods = Shop_Models_API_Cart :: makeCartGoodsToArray();
            $product = $this -> _api -> getCartProduct($cartGoods); 
            if ($product['has_vitual'] && !$this -> _request -> getParam('sms_no', '') ){
                Custom_Model_Message::showAlert('请填写短信接收号码！', false, '/flow/fast-track-buy');exit;
            }
            
        	//接收addres参数   构造$address
        	if ($product['only_vitual']) {
        	    $address = array('mobile' =>  $this -> _request -> getParam('sms_no', ''));
        	    if($address['mobile'] == ''){
            		Custom_Model_Message::showAlert('请填写短信接收号码！', false, '/flow/fast-track-buy');exit;
            	}
        	}
        	else {
            	$address = array();
            	$address['consignee'] = $this -> _request -> getParam('consignee','');
            	if($address['consignee'] == ''){
            		Custom_Model_Message::showAlert('请填写收货人姓名！', false, '/flow/fast-track-buy');exit;
            	}
            	$address['province_id'] =  $this -> _request -> getParam('province_id',0);
            	if($address['province_id'] == 0){
            		Custom_Model_Message::showAlert('请选择省份！', false, '/flow/fast-track-buy');exit;
            	}
           		$address['city_id'] =  $this -> _request -> getParam('city_id',0);
            	if($address['city_id'] == 0){
            		Custom_Model_Message::showAlert('请选择城市！', false, '/flow/fast-track-buy');exit;
            	}
            	$address['area_id'] =  $this -> _request -> getParam('area_id',0);
            	if($address['area_id'] == 0){
            		Custom_Model_Message::showAlert('请选择地区！', false, '/flow/fast-track-buy');exit;
            	}
            	$address['address'] =  $this -> _request -> getParam('address','');
            	if($address['address'] == ''){
            		Custom_Model_Message::showAlert('请填写详细地址！', false, '/flow/fast-track-buy');exit;
            	}            	
            	
            	$phone = 0;
            	if($this -> _request -> getParam('phone', ''))
            	{
            		$phone = $this -> _request -> getParam('phone_code', '') . '-' . $this -> _request -> getParam('phone', '');
            		if($this -> _request -> getParam('phone_ext', ''))
            		{
            			$phone .='-'.$this -> _request -> getParam('phone_ext', '');
            		}
            	}
            	$address['phone']  = $phone;
            	$address['mobile'] =  $this -> _request -> getParam('mobile',0);
            	
            	if($address['phone'] == 0 && $address['mobile'] == 0){
            		Custom_Model_Message::showAlert('手机和电话至少必填一项!', false, '/flow/fast-track-buy');exit;
            	}
            	
            	if($address['mobile'] && !Custom_Model_Check::isMobile($address['mobile'])){
            		Custom_Model_Message::showAlert('手机格式错误!', false, '/flow/fast-track-buy');exit;
            	}
            	
            	if($address['phone'] && !Custom_Model_Check::isTel($address['phone'])){
            		Custom_Model_Message::showAlert('电话格式错误!', false, '/flow/fast-track-buy');exit;
            	}
            	
            	$this -> _shopConfig = Zend_Registry::get('shopConfig');
            	$address['member_id']=$this -> _shopConfig['fast_track_menber_id'];
            	$address['email']=null;
            	$address['zip']='';
            	$address['fax']='';
            }
            
            $invoice_type = $this->_request->getParam('invoice_type',0);
            $invoice = $this->_request->getParam('invoice',null);
            $invoice_name = isset($invoice[$invoice_type])?$invoice[$invoice_type]:'';
            $invoice_content =  $this->_request->getParam('invoice_content',null);			
            $licence =  $this->_request->getParam('licence','');			
            $Tariff =  $this->_request->getParam('Tariff','');
            
        }else{
        	
        	//登录判断  登录购买流程
	        if (!$this -> user) {
	            $goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/flow/index/');
	            header("Location: {$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto);
	            exit;
	        }
	        
	        $delivery = $this->_api->getSetting('delivery');
			$invoice_type = $delivery['invoice_type'];
	        $invoice_name = $delivery['invoice_name'];
	        $invoice_content =  $delivery['invoice_content'];
			$warehouse_id  = $delivery['warehouse_id'];
			$licence  = $delivery['licence'];
			$Tariff  = $delivery['Tariff'];
       }
        
      
      $data = array('note' => $this -> _request -> getParam('note', ''),
					  'invoice_type'=>$invoice_type,
					  'invoice'=>$invoice_name,
      		          'invoice_content'=>$invoice_content,
			          'warehouse_id' => $warehouse_id,
                      'is_visit' => $this -> _request -> getParam('is_visit', 0),
                      'price_account' => $this -> _request -> getParam('price_account', 0),
                      'price_point' => $this -> _request -> getParam('price_point', 0),
                      'licence' => $licence,
                      'Tariff' => $Tariff,
                      'sms_no' => $this -> _request -> getParam('sms_no', null),);
        // api 向联盟传递数据
        $union = new Shop_Models_API_Union();
        $unionInfo = $union -> setOrderParent();
       
        // 新增订单
       $order = $this -> _api -> addOrder($data, $unionInfo ,$address);//如果有$address参数，则是快速通道
       
        //模拟提交后的订单
    /*    $order =  array(
        		'order_id'=>'' ,
        		'batch_sn'=>'213120215320985  ',
        		'price_order'=>'1172',
        		'price_goods'=>'1172',
        		'pay_name'=>'广发银行',
        		'pay_type'=>'alipaygdb',
        		'orderPay'=>'1172',
        		'invoice_type' => $data['invoice_type'],
        		'invoice' => $data['invoice']
        );  */
        
        if ($order['batch_sn']) {
            $logistic = $this -> _api -> getSetting('logistic');
            $this -> view -> order = $order;
            $this -> view -> pay_info = $this -> _api -> getPayInfo($order);
            $this -> view -> logistic = $logistic['label'];
            $orderinfo = array_shift($this -> _api ->getOrderBatch(array('batch_sn'=>$order['batch_sn'])));
            $product = $this -> _api -> getOrderBatchGoods(array('batch_sn' =>$order['batch_sn']));
            $payment =  array_shift($this->_api-> getPayment(array('pay_type' => $orderinfo['pay_type'])));

            $dspgoods ='';
			foreach($product as $k => $v){
				$dspgoods.=$v['goods_name'].',';
			}
			$dspgoods = substr($dspgoods, 0, -1); 
			$this -> view -> dspgoods = $dspgoods;

            $this -> view -> payment = $payment;
            $this -> view -> product_num = count($product);            
            $this -> view -> orderinfo = $orderinfo;
            $this -> view -> product = $product;
            $this -> view -> dsp = '1';

			$_SERVER['SERVER_NAME'] = strtolower($_SERVER['SERVER_NAME']);
			//if($_SERVER['SERVER_NAME'] == "www.1jiankang.com"){
                //CPS订单跟踪传输
			$this -> view -> unionHtml = $union -> orderApi($order['batch_sn'], $unionInfo['parent_id'],$unionInfo['un_type']);
			//}
            $this -> render('submit-success');

			//登录状态下单成功发邮件
            if ($this -> user) {
				$this -> _api -> sendOrderEmail($product ,$orderinfo);
            }else{
	            if($address['mobile'] && Custom_Model_Check::isMobile($address['mobile']) && $_SERVER['SERVER_NAME']== "www.1jiankang.com" ){
	            	//发送手机短信				
					$sms= new  Custom_Model_Sms();
					$response = $sms->send($address['mobile'],"您的订单已经提交请记住您的订单号：".$order['batch_sn']."  www.1jiankang.com");				
				}
            }
        }else{        
            Custom_Model_Message::showAlert('提示：订单提交失败！' , true, '/flow/index/');        	 
        }
    }
    
    public  function addGiftOrderAction()
    {    	
    	//登录判断  登录购买流程
    	if (!$this -> user) {
    		$goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/flow/gift-card/');
    		header("Location: {$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto);
    		exit;
    	}
    	
    	//礼品卡验证
    	$giftCard = $_SESSION['tmp_giftcard'];
    	if(!$giftCard)
    	{
    		unset($_SESSION['tmp_giftcard']);
    		Custom_Model_Message::showAlert('请选择垦丰卡！',true,'/giftcard/');
    		exit();
    	}
    	
    	$payment = $this -> _request -> getParam('pay_type', '');
    	if($payment == ''){
    		Custom_Model_Message::showAlert('请选择支付方式' , true, '/flow/gift-card/');
    	}else{
    		$this -> _api -> setSetting('payment', $payment);
    		$this -> _api -> setSetting('logistic', $payment);
    	}
    	 
    	
    	if (!$this -> _request -> getParam('sms_no', '') ) {
    		Custom_Model_Message::showAlert('请填写短信接收号码！', false, '/flow/gift-card');exit;
    	}    	
    	
    	$address = array_shift($this -> _api -> getAddress(array('address_id' => $this -> _api -> getSetting('addr'),'member_id' => $this -> user['member_id'])));
    	if(!$address)
    	{
    		Custom_Model_Message::showAlert('请填写或选择发票配送地址！', false, '/flow/gift-card');exit;
    	}
    	
    	//接收addres参数   构造$address    	
    	$address = array('mobile' => $this -> _request -> getParam('sms_no', '') );
    	
    
    	
    	$invoice_type = $this->_request->getParam('invoice_type',0);
    	$invoice = $this->_request->getParam('invoice',null);
    	$invoice_content =  $this->_request->getParam('invoice_content',null);
    	
    	$data = array('note' => $this -> _request -> getParam('note', ''),
    			'invoice_type'=>$invoice_type,
    			'invoice'=>isset($invoice[$invoice_type])?$invoice[$invoice_type]:'',
    			'invoice_content'=>$invoice_content,
    			'is_visit' => 1,
    			'payment'  => $payment,
    			'price_account' => 0,
    			'price_point' => 0,
    			'sms_no' =>$address['mobile'],);
    	
    	$order = $this->_api->addOrderGiftCard($data); 
    	if ($order['batch_sn']) {
    		$logistic = $this -> _api -> getSetting('logistic');
    		$this -> view -> order = $order;
    		$this -> view -> pay_info = $this -> _api -> getPayInfo($order);
    		$this -> view -> logistic = $logistic['label'];
    		$orderinfo = array_shift($this -> _api ->getOrderBatch(array('batch_sn'=>$order['batch_sn'])));
    		$product = $this -> _api -> getOrderBatchGoods(array('batch_sn' =>$order['batch_sn']));
    		$payment =  array_shift($this->_api-> getPayment(array('pay_type' => $orderinfo['pay_type'])));
    	
    		$dspgoods ='';
    		foreach($product as $k => $v){
    			$dspgoods.=$v['goods_name'].',';
    		}
    		$dspgoods = substr($dspgoods, 0, -1);
    		$this -> view -> dspgoods = $dspgoods;
    	
    		$this -> view -> payment = $payment;
    		$this -> view -> product_num = count($product);
    		$this -> view -> orderinfo = $orderinfo;
    		$this -> view -> product = $product;    	
    		$_SERVER['SERVER_NAME'] = strtolower($_SERVER['SERVER_NAME']);    		
    		$this -> render('submit-success');    	
    		//登录状态下单成功发邮件
    		if ($this -> user) {
    			$this -> _api -> sendOrderEmail($product ,$orderinfo);
    		}else{
    			if($address['mobile'] && Custom_Model_Check::isMobile($address['mobile']) && $_SERVER['SERVER_NAME']== "www.1jiankang.com" ){
    				//发送手机短信
    				$sms= new  Custom_Model_Sms();
    				$response = $sms->send($address['mobile'],"您的订单已经提交请记住您的订单号：".$order['batch_sn']."  www.1jiankang.com");    	
    			}
    		}
    	}else{
    		Custom_Model_Message::showAlert('提示：订单提交失败！' , true, '/flow/gift-card/');
    	}
    	 
    }
   
    /**
     * 取消积分抵扣
     *
     * @return void
     */
    public function delPointAction()
    {
        unset($_SESSION['price_point']);
        header('Location: '.$this -> getFrontController() -> getBaseUrl().'/flow/order/');
    }

    /**
     * 取消帐户余额抵扣
     *
     * @return void
     */
    public function delAccountAction()
    {
        unset($_SESSION['price_account']);
        header('Location: '.$this -> getFrontController() -> getBaseUrl().'/flow/order/');
    }
    
    /**
     * 礼品卡购买
     */
    public function giftCardAction()
    {
    	//登录验证
    	 if (!$this -> user) {
    		$goto = base64_encode($this -> getFrontController() -> getBaseUrl().'/flow/gift-card/');
    		header("Location: {$this -> getFrontController() -> getBaseUrl()}/auth/login/goto/" . $goto);
    		exit;
    	}
    	
    	//礼品卡验证
    	$giftCard = $_SESSION['tmp_giftcard'];
    	if(!$giftCard)
    	{
    		unset($_SESSION['tmp_giftcard']);
    		Custom_Model_Message::showAlert('请选择垦丰卡！',true,'/giftcard');
    		exit();
    	}
    	
      
    	if(!$this -> _api -> getSetting('payment') && $this -> user['last_pay_type']) {
    		$this -> _api -> setSetting('payment', $this -> user['last_pay_type']);
    	}
    	 
    	if (!$this -> _api -> getSetting('mobile') && $this -> user['mobile'] && $this -> user['check_mobile']) {
    		$this -> _api -> setSetting('mobile', $this -> user['mobile']);
    	}
    	
    	if (!$this -> _api -> getSetting('delivery')) {
    		if($this -> user['last_invoice'])
    		{
    			$this -> _api -> setSetting('delivery', unserialize($this -> user['last_invoice']));
    		}else{
    			$delivery = array();
    			$delivery['invoice_type'] = 0;
    			$delivery['invoice_name'] = '';
    			$this -> _api -> setSetting('delivery', $delivery);
    		}
    	}
    	
        //可选支付方式
    	$logistic = $this -> _api -> getSetting('logistic');
    	$showPayment = $this -> _api -> showPayment($logistic);
    	$this -> view -> payType = $showPayment['defaultPayment'];
    	$this -> view -> payment = $showPayment['payment'];
    	
    	
    	$delivery  = $this->_api-> getSetting('delivery');
    	$this -> view -> delivery = $delivery;
    	
        //收货地址
    	$addressList=$this -> _api -> getAddress(array('member_id' => $this -> user['member_id']));
    	$this -> view -> addressList = $addressList;
   
    	//商品信息
    	$products  = $this->_api->getCartGiftCard($giftCard);
    	$this->view->products = $products;
    	
    	if(!$this -> _api -> getSetting('addr') && $this->user['last_address_id'])
    	{
    		$this -> _api -> setSetting('addr',$this->user['last_address_id']);
    	}
    	
    	$address = array_shift($this -> _api -> getAddress(array('address_id' => $this -> _api -> getSetting('addr'),
    			'member_id' => $this -> user['member_id'])));
    	
        $this->view->address_id  = $address['address_id'];
    	$this ->view -> mobile = $this -> _api -> getSetting('mobile');
    	$this->view->css_more=',giftcard.css';
    	$this->view->js_more=',check.js,order.js';
    }
    
    public  function  checkMobileAction()
    {
    	$mobile = trim($this -> _request ->getParam('mobile'));
    	if(!Custom_Model_Check::isMobile($mobile))
    	{
    		echo Zend_Json::encode(array('status'=>0,'msg'=>'手机格式不正确！'));
    		exit();
    	}
    	
    	$code = $this -> _request ->getParam('code');
    	$authMobile = new Custom_Model_AuthImage('mobile');
    	if($authMobile->checkCode($code))
    	{    	
            $this->_api->setSetting('mobile', $mobile);    		
    		if($this -> user)
    		{
    			$memberSeverice =  new Shop_Models_API_Member();
    			$res = $memberSeverice->editMember(array('mobile'=>$mobile,'check_mobile'=>1));
    			if($res) $this->_auth->updateAuth(); //更新缓存
    		}
    		
    		$tips = '<h2><b>手机信息</b></h2>
    				 <div class="mobile_check_form"><p>我们将把虚拟商品卡号、密码发送至已验证手机号码 <em>'.$mobile.' <input type="hidden" id="sms_no" value="'.$mobile.'"/></em>  中。<br>
             	             如需更改接收手机请点击 <a href="javascript:;" onclick="editMobile();">修改</a>
                     </p></div>';
    		echo Zend_Json::encode(array('status'=>1,'msg'=>'验证成功。','tips'=>$tips));
    		exit();
    	}else{
    		echo Zend_Json::encode(array('status'=>0,'msg'=>'验证失败！'));
    		exit();
    	}
    
    }
    
    public function editMobileAction()
    {
    	$this-> view->mobile = $this->_api->getSetting('mobile');
    	$html =  $this->view->render('flow/mobile.tpl');
    	echo Zend_Json::encode(array('status'=>1,'msg'=>"请求成功",'html'=>$html));
    	exit();
    }
    
    public function  getMobileAction()
    {
    	$mobile = $this->_api->getSetting('mobile');
    	$tips = '<h2><b>手机信息</b></h2>
    				 <div class="mobile_check_form"><p>我们将把虚拟商品卡号、密码发送至已验证手机号码 <em>'.$mobile.' <input type="hidden" id="sms_no" name="sms_no" value="'.$mobile.'"/></em>  中。<br>
             	             如需更改接收手机请点击 <a href="javascript:;" onclick="editMobile();">修改</a>
                     </p></div>';
    	echo Zend_Json::encode(array('status'=>1,'msg'=>'验证成功。','tips'=>$tips));
    	exit();
    }
     
}