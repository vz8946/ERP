<?php
class Purchase_Models_API_Cart extends Custom_Model_Dbadv
{
    /**
     * 
     * @var Purchase_Models_DB_Cart
     */
	protected $_db = null;
	protected $_session = 'cart';
	protected $user;
	protected $_goodsAPI;

	public function __construct(){
	    parent::__construct();
		$this -> _db = new Purchase_Models_DB_Cart();
        $this -> _session = new Zend_Session_Namespace($this -> _userCertificationName);
        $this -> user = Purchase_Models_API_Auth :: getInstance() -> getAuth();
        $this -> _goodsAPI = new Purchase_Models_API_Goods();
	}
	
	/**
     * 放入购物车
     *
     * @param   string     $productSN
     * @param   int     $number
     * @return  array
     */
    public function buy($productSN, $number,&$fcart=array())
    {
        $_goods = new Purchase_Models_DB_Goods();
        $data = $_goods -> getProductInfo(" and product_sn='{$productSN}'");
        $productID = $data['product_id'];
        if ($productID) {
            
            if(!empty($fcart)){
                $cart = $fcart;
            }else{
                $cart = self :: makeCartGoodsToArray();
            }
            
            $goodsInfo = $_goods -> getGoodsProductInfo(" and product_sn='{$productSN}'", 't1.product_id,limit_number');
            if ( ($cart[$goodsInfo['product_id']] + $number) > $goodsInfo['limit_number'] ) {
    	        return "该商品只能购买{$goodsInfo['limit_number']}件！";
    	    }
            //继续购物中如果购买的商品已经存在，那么就直接累加商品数量,否则添加一条记录
            if (isset($cart[$productID])) {
                $cart[$productID] += $number;
            } else {
                $cart[$productID] = $number;
            }
            
            $this -> setCartCookie($cart);
            $fcart = $cart;
            
            unset($_SESSION['price_point'],$_SESSION['price_account']);//商品改动删除积分抵扣，帐户余额
        }
    }

    /**
     * 删除购物车中的指定数量的商品
     *
     * @param   int     $productID
     * @param   int     $number
     * @return  void
     */
    public function del($productID, $number, $usePlugin = true)
    {
        $cart = self :: makeCartGoodsToArray();
        if (isset($cart[$productID])) {
            $cart[$productID] -= $number;
            if ($cart[$productID] < 1) {
                unset($cart[$productID]);
            }
            unset($_SESSION['price_point'],$_SESSION['price_account']);//商品改动删除积分抵扣，帐户余额
        }
        $this -> setCartCookie($cart);
    }
    /**
     * 修改购物车中的指定商品的数量
     *
     * @param   int     $productID
     * @return void
     */
    public function change($productID, $number)
    {
        $_goods = new Purchase_Models_DB_Goods();
        $goodsInfo = $_goods -> getGoodsProductInfo(" and t1.product_id='{$productID}'", 't1.product_id,limit_number');
        if ( $number > $goodsInfo['limit_number'] ) {
    	    return "该商品只能购买{$goodsInfo['limit_number']}件！";
    	}
        $cart = self :: makeCartGoodsToArray();
        if (isset($cart[$productID])) {
            $cart[$productID] = $number;
            unset($_SESSION['price_point'],$_SESSION['price_account']);//商品改动删除积分抵扣，帐户余额
        }
        $this -> setCartCookie($cart);
    }
	/**
     * 设置COOKIE
     *
     * @param   array   $cart
     * @return  void
     */
    public function setCartCookie($cart = null)
    {
        setcookie('cart', $this -> makeCartGoodsToString($cart), time () + 86400 * 365, '/');
    }
	/**
     * 变形购物车cookie中的商品ID串至数组
     *
     * @return  array
     */
    public static function makeCartGoodsToArray()
    {
        $cart = array();
        if ($_COOKIE['cart']) {
            $tmp = explode('|', $_COOKIE['cart']);
            if (is_array($tmp) && count($tmp)) {
                foreach ($tmp as $temp) {
                    $item = explode(',', $temp);
                    $productID = intval($item[0]);
                    $productNumber = intval($item[1]);
                    if ($productNumber > 0) {
                        $cart[$productID] = $productNumber;
                    }
                }
            }
        }
        return $cart;
    }

	/**
     * 变形商品数组至字符串
     *
     * @param   array   $cart
     * @return  string
     */
    public function makeCartGoodsToString($cart)
    {
        if (is_array($cart) && count($cart)) {
            foreach ($cart as $productID => $number) {
                $tmp[] = $productID . ',' . $number;
            }
            $cartStr = implode('|', $tmp);
        }
        return $cartStr;
    }
	/**
     * 取购物车商品列表
     *
     * @param   int     $productID
     * @return  array
     */
    public function getCartProduct($cart = null)
    {
        $_goods = new Purchase_Models_API_Goods();
        $number = $amount = $weight = $volume = 0;
        $cart = $cart !== null ? $cart : self :: makeCartGoodsToArray();
        if (is_array($cart) && count($cart)) {
            $tmpProducts = $_goods -> getProduct("a.product_id in(" . implode(',', array_keys($cart)) . ")", 'a.product_id,product_sn, product_name,a.cat_id,goods_style,p_weight,p_length,p_height,p_width,produce_cycle, product_img,product_arr_img,goods_id,goods_sn,goods_name, view_cat_id, market_price,price, staff_price, goods_img, price_seg,cat_sn,c.cat_name,limit_number,is_vitual, a.cost');
            if ($tmpProducts) {
                foreach ($tmpProducts as $k => $v) {
                    $tmpProduct[$v['product_id']] = $v;
                }
                foreach ($cart as $k => $v) {
                    $products['data'][] = $tmpProduct[$k];
                }
            }
            if (is_array($products['data']) && count($products['data'])) {
                foreach ($products['data'] as $k => $product) {

					$products['data'][$k]['allow_modify'] = 1;
					$products['data'][$k]['number'] = $cart[$product['product_id']];
					$products['data'][$k]['org_price'] = $products['data'][$k]['staff_price'];

					//处理数量价格区间
					$products['data'][$k]['price_seg'] = unserialize($products['data'][$k]['price_seg']);
                    $products['data'][$k]['staff_price'] =  $products['data'][$k]['staff_price'];

   
                	$number += $cart[$product['product_id']];
                    $amount += $products['data'][$k]['staff_price'] * $cart[$product['product_id']];
                    $products['data'][$k]['amount'] += $amount;
                    $weight += $product['p_weight'] * $cart[$product['product_id']];
                    $volume += round($product['p_length'] * $product['p_width'] * $product['p_height'] * $cart[$product['product_id']] * 0.001, 3);
                    $onlyVitual = 0;
                   
                }
            }
        }
         
        $products['number'] = $number;
        $products['goods_amount'] = $amount;
        $products['amount'] = $amount;
        $products['weight'] = $weight;
        $products['volume'] = $volume;
        $products['has_vitual'] = $hasVitual;
        $products['only_vitual'] = $onlyVitual;

        //组合商品
        $groupGoods = new Purchase_Models_API_GroupGoods();
        $products = $groupGoods -> responseToCart($products);

        return $products;
    }

    //购物卡 购买
    public function getCartGiftCard($cart)
    {
    	$_goods = new Purchase_Models_API_Goods();
    	$amount = $number = 0;
    	if (is_array($cart) && count($cart)) {
    		$tmpProducts = $_goods -> getProduct("a.product_id in(" . implode(',', array_keys($cart)) . ")", 'a.product_id,product_sn, product_name,a.cat_id,goods_style,p_weight,p_length,p_height,p_width,produce_cycle, product_img,product_arr_img,goods_id,goods_sn,goods_name, view_cat_id, market_price,price, staff_price, goods_img, price_seg,cat_sn,c.cat_name,limit_number,is_vitual, a.cost');
    		if ($tmpProducts) {
    			foreach ($tmpProducts as $k => $v) {
    				$tmpProduct[$v['product_id']] = $v;
    			}
    			foreach ($cart as $k => $v) {
    				$products['data'][] = $tmpProduct[$k];
    			}
    		}
    		if (is_array($products['data']) && count($products['data'])) {
    			foreach ($products['data'] as $k => $product) {
    	
    				$products['data'][$k]['allow_modify'] = 1;
    				$products['data'][$k]['number'] = $cart[$product['product_id']];
    				$products['data'][$k]['org_price'] = $products['data'][$k]['price'];
    				$amount += $products['data'][$k]['price'] * $cart[$product['product_id']];
    				$number += $cart[$product['product_id']];
    			}
    		}
    	}
    	
    	$products['number'] = $number;
    	$products['amount'] = $amount;
    	$products['goods_amount'] = $amount;
    	return $products;
    }
    
    /**
     * 取购物车商品数量
     *
     * @param   void
     * @return  array
     */
    public static function getCartProductCount()
    {
    	$productNumber = 0;
    	if ($_COOKIE['cart']) {
            $tmp = explode('|', $_COOKIE['cart']);

            if (is_array($tmp)) {
                foreach ($tmp as $temp) {
                    $item = explode(',', $temp);

                    if (intval($item[1]) > 0) {
                    	$productNumber += $item[1];
                    }
                }
            }
        }
        if ($_COOKIE['p']) {
        	$packages = explode('|', $_COOKIE['p']);

        	if (is_array($packages)) {
        		foreach ($packages as $key => $package)
        		{
					$productNumber += count(explode(',', $package));
        		}
        	}
        }

        if ($_COOKIE['gift']) {
        	$productNumber += count(explode(',', $_COOKIE['gift']));
        }
        return $productNumber;
    }

	/**
     * 新增地址
     *
     * @param   array     $data
     * @return  int
     */
    public function addAddr($data)
    {
        return $this -> _db -> addAddr($data);
    }
	/**
     * 统计某个用户的地址数量
     *
     * @return  int
     */
    public function countAddr()
    {
        return $this -> _db -> countAddr($this -> user['member_id']);
    }
	/**
     * 更新指定条件的地址
     *
     * @param   array     $where
     * @return  bool
     */
    public function editAddr($where, $data)
    {
        return $this -> _db -> editAddr($where, $data);
    }
	/**
     * 删除指定条件的地址
     *
     * @param   array     $where
     * @return  bool
     */
    public function delAddr($where)
    {
        return $this -> _db -> delAddr($where);
    }
    public function setSetting($key, $value)
    {
        $this -> _session-> cart[$key] = $value;
    }
    public function getSetting($key)
    {
    	return $this -> _session-> cart[$key];
    }
	/**
     * 取指定条件的地址列表
     *
     * @param   array     $where
     * @param   int     $id
     * @return  array
     */
    public function getAddress($where, $id = null)
    {
        return $this -> _db -> getAddress($where);
    }
	/**
     * 匹配物流公司
     *
     * @param    int      $areaID
     * @return   string
     */
    public function getLogistic($areaID)
    {
        $areaID = intval($areaID);
        if (!$areaID) {
            return false;
        }
        $data = $this -> _db -> getLogistic(array('area_id' => $areaID));
        if ($data) {
            foreach ($data as $k => $v) {
                $tmp[$v['logistic_code']] = $v['cod'];
            }
        }
        if (count($tmp) == 1 && $tmp['ems']) {
            $logistic = array('label' => 'EMS', 'cod' => $tmp['ems']);
        } else if (count($tmp) > 1) {
            unset($tmp['ems']);
            $logistic = array('label' => '普通快递', 'cod' => 0);
            foreach ($tmp as $logisticCode => $cod) {
                if ($cod) {
                    $logistic['cod'] = 1;
                }
            }
        }
        return $logistic;
    }

	/**
     * 支付方式显示判定
     *
     * @param    array    $where
     * @return   array
     */
    public function showPayment($logistic= array())
    {
        $cat_products = $this  -> getCartProduct();
        
        $onlyCod = true;
        if ($cat_products['data']) {
            //判断是否只有虚拟商品
            if ($cat_products['only_vitual']) {
            }
            else {
                //判断订单中是否只有团购商品，如果是，则不能货到付款
                foreach ($cat_products['data'] as $data) {
                    if (!$data['tuan_id']) {
                        $onlyCod = false;
                        break;
                    }
                }
            }
        }
        else    $onlyCod = false;

        if ($onlyCod && ($this -> user['last_pay_type'] == 'cod')) {
            $defaultPayment = 'alipay';
        }
        else    $defaultPayment = $this -> user['last_pay_type'] ? $this -> user['last_pay_type'] : 'alipay';

		

	    $payment = $this -> getPayment(array('status' => 0));
	

        if($onlyCod|| $cat_products['goods_amount']=='0' ){
                foreach($payment as $k=>$v){
                    if($v['pay_type'] =='cod'){
                        unset($payment[$k]);
                        break;
                    }
                }
        }
        foreach ($payment as $key => $var) {
            if ($var['pay_type'] != 'cod') {
                if( $var['is_bank'] =='1' || $var['is_bank'] =='2'){
                    $payment['bank_list'][]=$var;
                }else{
                    $payment['list'][]=$var;
                }
            } else {
                $payment['cod']=$var;
            }
            unset($payment[$key]);
        }
        
        return array('defaultPayment'=>$defaultPayment ,'payment'=>$payment);
    }

	/**
     * 取支付方式
     *
     * @param    array    $where
     * @return   array
     */
    public function getPayment($where = null)
    {
        return $this -> _db -> getPayment($where);
    }

	/**
     * 取专享支付方式
     *
     * @param    array    $where
     * @return   array
     */
    public function getOnlyPayment($pay_type = null)
    {
        return $this -> _db -> getOnlyPayment($pay_type);
    }

	/**
     * 提交订单
     *
     * @param    array    $data
     * @return   bool
     */
    public function addOrder($data, $unionInfo = array() ,$address = array())
    {
        $shopConfig = Zend_Registry::get('shopConfig');
        $this -> habit();//顾客最后一次购物习惯
        
        //是否为快速购买
        if(count($address)){
            $memberApi =new  Purchase_Models_API_Member();
            $user = $memberApi-> getMemberByUserId($shopConfig['fast_track_id']);
            if($user){
                        $this -> user['user_id']=$shopConfig['fast_track_id'];
                        $this -> user['user_name']=$user['user_name'];
                        $this -> user['rank_name']='普通用户';
                        $this -> user['member_id']=$user['member_id'];
                        $this -> user['rank_id']=1;
                        $this -> user['ltype']='anonym';

            }else{
                 Custom_Model_Message::showAlert('提示：匿名购买用户不存在，请登录后购买！' , true, '/flow/index/');
            }
        } else {
        	$address = array_shift($this -> _db -> getAddress(array('address_id' => $this -> getSetting('addr'),
                                                                'member_id' => $this -> user['member_id'])));
        }
        
        $cartGoodsArr = self :: makeCartGoodsToArray();
        //取购物车商品信息
        $cart = $this -> getCartProduct($cartGoodsArr);
        //检查订单( $cart['data']和$cart['offers']和$cart['group_goods_summary'] )
        $this -> checkOrder($cart,$address,true);

        //取支付信息
        $payment = array_shift($this -> _db -> getPayment(array('pay_type' => $this -> getSetting('payment'))));
        $product = $cart['data'];//取商品信息
        $offers = $cart['offers'];//取活动信息
        $cards = $_SESSION['Card']['cardCertification'];//取抵用券信息
        $priceAccount = round($_SESSION['price_account'], 2);//账户余额支付
        $pricePoint = round($_SESSION['price_point']);//积分支付

        if (!isset($shopConfig['point_to_price'])) {
            $shopConfig['point_to_price'] = 100;
        }
        $point = $pricePoint * $shopConfig['point_to_price'];//计算使用了多少积分
        $priceGoods = $cart['goods_amount'];
        
        $priceLogistic=$this ->getLogisticPrice($cart);
        
        if ($cards) {
            $priceCouponCard = $priceGiftCard = 0;
        	foreach ($cards as $card) {
        	    if ($card['type'] == 'coupon') {
                    $priceCouponCard += abs($card['card_price']);
                }
                else if ($card['type'] == 'gift') {
                    $priceGiftCard += abs($card['card_price']);
                } 
        	}
        }
        
        $pricePay = $priceOrder = $cart['amount'] + $priceLogistic - $priceCouponCard;  //订单金额
        $orderPay  = $cart['amount'] + $priceLogistic - ($priceCouponCard + $priceGiftCard + $pricePoint + $priceAccount);  //需支付金额
        $pricePayed = 0; //已支付金额
        
        $time = time();
        if ($this -> user['ltype'] == 'phone' &&  $_COOKIE['operator_id'] ) {
            $operator_id = $_COOKIE['operator_id'];
            setcookie('operator_id','', time() + 86400, '/');
        }else{
            $operator_id='0';
        }
        
        //新增订单
        $orderSN = Custom_Model_CreateSn::createSn();
        
        $orderID = $this -> _db -> addOrder(array('order_sn' => $orderSN,
                                       'batch_sn' => $orderSN,
                                       'add_time' => $time,
									   'invoice_type'=>$data['invoice_type'],
									   'invoice'=>$data['invoice'],
                                       'operator_id' => $operator_id,
                                       'user_id' => $this -> user['user_id'],
                                       'user_name' => $this -> user['user_name'],
                                       'rank_id' => $this -> user['rank_id'],
                                       'shop_id' => '3',
                                       'source' => 0,
                                       ));
        //新增订单批次
        $orderBatchID = $this -> _db -> addOrderBatch(array('order_id' => $orderID,
                                                            'order_sn' => $orderSN,
                                                            'batch_sn' => $orderSN,
                                                            'add_time' => $time,
                                                            'is_visit' => intval($data['is_visit']),
                                                            'type' => 0,
                                                            'note' => $data['note'],
                                                            'price_order' => $priceOrder,
                                                            'price_goods' => $priceGoods,
                                                            'price_logistic' => floatval($priceLogistic),
                                                            'price_pay' => $pricePay+ $payPromotion,
                                                            'price_payed' => $pricePayed,
                                                            'account_payed' => $priceAccount ? $priceAccount : 0,
                                                            'point_payed' => $pricePoint ? $pricePoint : 0,
                                                            'gift_card_payed' => $priceGiftCard ? $priceGiftCard : 0,
                                                            'status_pay' => ($orderPay==0) ? 2 : 0,
                                                            'pay_type' => $payment['pay_type'],
                                                            'pay_name' => $payment['name'],
                                                            'addr_consignee' => $address['consignee'] ? $address['consignee'] : '',
                                                            'addr_province' => $this -> _db -> getAreaName($address['province_id']),
                                                            'addr_city' => $this -> _db -> getAreaName($address['city_id']),
                                                            'addr_area' => $this -> _db -> getAreaName($address['area_id']),
                                                            'addr_province_id' => $address['province_id'] ? $address['province_id'] : 0,
                                                            'addr_city_id' => $address['city_id'] ? $address['city_id'] : 0,
                                                            'addr_area_id' => $address['area_id'] ? $address['area_id'] : 0,
                                                            'addr_address' => $address['address'] ? $address['address'] : '',
                                                            'addr_zip' => trim($address['zip']) ? trim($address['zip']) : $this -> _db -> getAreaZip($address['area_id']),
                                                            'addr_tel' => $address['phone'] ? $address['phone'] : '',
                                                            'addr_mobile' => $address['mobile'] ? $address['mobile'] : '',
                                                            'addr_email' => $address['email'] ? $address['email'] : '',
                                                            'addr_fax' => $address['fax'] ?$address['fax'] : '',
                                                            'sms_no' => $data['sms_no'] ? $data['sms_no'] : '',
                                                           ));
        $_msg = new Purchase_Models_API_Msg();
        //新增订单商品
        $hasCupGoods = false;
		$hasZeroGoods = false;
        $stockAPI = new Admin_Models_API_Stock();

        if ($product) {                                                     //普通商品
            foreach($product as $data){
                $tmp[$data['product_id']] = $this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
                                                                                     'order_batch_id' => $orderBatchID,
                                                                                     'order_sn' => $orderSN,
                                                                                     'batch_sn' => $orderSN,
                                                                                     'type' => $data['is_vitual'] ? 7 : 0,
                                                                                     'add_time' => $time,
                                                                                     'product_id' => $data['product_id'],
                                                                                     'product_sn' => $data['product_sn'],
                                                                                     'goods_id' => $data['goods_id'],
                                                                                     'goods_name' => $data['goods_name'],
                                                                                     'goods_style' => $data['goods_style'],
                                                                                     'cat_id' => $data['cat_id'],
                                                                                     'cat_name' => $data['cat_name'],
                                                                                     'weight' => $data['p_weight'],
                                                                                     'length' => $data['p_length'],
                                                                                     'width' => $data['p_width'],
                                                                                     'height' => $data['p_height'],
                                                                                     'number' => $data['number'],
                                                                                     'price' => $data['org_price'],
                                                                                     'sale_price' => $data['staff_price'],
                                                                                     'cost'       => $data['cost'],
                                                                                     'remark' => $data['remark']?$data['remark']:''));

                $stockAPI -> holdSaleProductStock($data['product_id'], $data['number']);
                //新增购买记录
		        if (intval($data['goods_id'])) $_msg -> insertBuyLog($data['goods_id'], $this -> user['user_name'], $this -> user['rank_name'], $time);
            
            }
        }
        
        //start:组合商品 
        //变量$cart['group_goods_summary']来自shop/api/GroupGoods.responseToCart()
        if(isset($cart['group_goods_summary']) && count($cart['group_goods_summary'])){
        	foreach ($cart['group_goods_summary'] as $kk => $vv){
        		//先插入组合商品名称、价格
                $total_cost = 0;
                if (!empty($vv['config'])) {
                    foreach ($vv['config'] as $config_goods) {
                        $total_cost += $config_goods['cost'] * $config_goods['number'];
                    }
                }
        		$orderGoodsID = $this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
                                                     'order_batch_id' => $orderBatchID,
                                                     'order_sn' => $orderSN,
                                                     'batch_sn' => $orderSN,
                                                     'type' => 5,
                                                     'add_time' => $time,
                                                     'product_id' => 0,
                                                     'product_sn' => 0,
                                                     'goods_id' => 0,
                                                     'goods_name' => $vv['group_goods_name'],
                                                     'goods_style' => $vv['group_specification'],
                                                     'cat_id' => 0,
                                                     'cat_name' => 0,
                                                     'weight' => 0,
                                                     'length' => 0,
                                                     'width' => 0,
                                                     'height' => 0,
                                                     'number' => $vv['number'],
                                                     'price' => $vv['group_price'],
                                                     'sale_price' => $vv['group_price'],
                                                     'eq_price' => 0,
                                                     'cost'     => $total_cost,
                                                     'remark' => ''));
                //新增活动商品
                if ($offers) {
                    foreach($offers as $offersID => $item) {
                        foreach ($item as $i) {
                            if ($i['offers_type'] != 'group-exclusive' && !$i['is_group'])  continue;
                            if ($i['parent_pid'] != $vv['group_id'])    continue;
                            $this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
                                                                     'order_batch_id' => $orderBatchID,
                                                                     'order_sn' => $orderSN,
                                                                     'batch_sn' => $orderSN,
                                                                     'type' => 1,
                                                                     'parent_id' => $orderGoodsID,
                                                                     'offers_id' => $offersID,
                                                                     'offers_name' => $i['offers_name'],
                                                                     'offers_type' => $i['offers_type'],
                                                                     'add_time' => $time,
                                                                     'goods_name' => $i['offers_name'],
                                                                     'number' => $i['number'] ? $i['number'] : 1,
                                                                     'price' => $i['price'],
                                                                     'sale_price' => $i['price']));
                        }
                    }
                }

        		/*遍历config中的商品*/
        		//先找出 子商品分别有多少，便于统计eq_price
        		$subGoodsTotalPrice = 0;
        		foreach ($vv['config'] as $kkk => $vvv){
        			$subGoodsTotalPrice += $vvv['number']*$vv['number']*$vvv['price'];
        		}
        		//遍历插入子商品
        		foreach ($vv['config'] as $kkk => $vvv){
        			$this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
	                                                     'order_batch_id' => $orderBatchID,
	                                                     'order_sn' => $orderSN,
	                                                     'batch_sn' => $orderSN,
                                                         'parent_id' => $orderGoodsID,
	                                                     'type' => 5,
	                                                     'add_time' => $time,
	                                                     'product_id' => $vvv['product_id'],
	                                                     'product_sn' => $vvv['product_sn'],
	                                                     'goods_id' => $vvv['goods_id'],
	                                                     'goods_name' => $vvv['goods_name'],
                                                     	 'goods_style' => $vvv['goods_style'],
	                                                     'cat_id' => 0,
	                                                     'cat_name' => 0,
	                                                     'weight' => 0,
	                                                     'length' => 0,
	                                                     'width' => 0,
	                                                     'height' => 0,
	                                                     'number' => $vvv['number']*$vv['number'],
	                                                     'price' => $vvv['price'],
	                                                     'sale_price' => ($vv['group_price']*$vv['number']*$vvv['price'])/$subGoodsTotalPrice,
	                                                     'eq_price' => ($vv['group_price']*$vv['number']*$vvv['price'])/$subGoodsTotalPrice,
                                                         'cost'     => $vvv['cost'],
	                                                     'remark' => ''));
	                $stockAPI -> holdSaleProductStock($vvv['product_id'], $vvv['number'] * $vv['number']);
        		}
        	}
        }
        //end:组合商品 
        

        if ($cards){
        	foreach ($cards as $card) {
        	    if ($card['type'] == 'coupon') {    //礼券
            		$this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
                                                             'order_batch_id' => $orderBatchID,
                                                             'order_sn' => $orderSN,
                                                             'batch_sn' => $orderSN,
                                                             'type' => 2,
                                                             'add_time' => $time,
                                                             'card_sn' => $card['card_sn'],
                                                             'card_type' => $card['type'],
                                                             'goods_name' => $card['card_name'],
                                                             'number' => 1,
                                                             'price' => -$card['card_price'],
                                                             'sale_price' => -$card['card_price']));
                }
        	}
        	
        }
        if ($priceAccount) { //帐户余额
            //api 帐户余额接口 开始
            $memberApi = new Purchase_Models_API_Member();
            $member = array_shift($memberApi -> getMemberByUserName($this -> user['user_name']));
            $tmp = array('member_id' => $this -> user['member_id'],
                         'user_name' => $this -> user['user_name'],
                         'order_id' => $orderBatchID,
                         'accountValue' => $priceAccount,
                         'accountTotalValue' => $member['money'],
                         'note' => '客户下单帐户余额抵扣',
                         'batch_sn' => $orderSN);
            $memberApi -> editAccount($this -> user['member_id'], 'money', $tmp);
            Purchase_Models_API_Auth :: getInstance() -> updateAuth();
            //api 帐户余额接口 结束
        }
        
        if ($point) {                                                   //积分
            //api 积分接口 开始
            $memberApi = new Purchase_Models_API_Member();
            $member = array_shift($memberApi -> getMemberByUserName($this -> user['user_name']));
            $tmp = array('member_id' => $this -> user['member_id'],
				         'user_name' => $this -> user['user_name'],
                         'order_id' => $orderBatchID,
                         'accountValue' => $point,
                         'accountTotalValue' => $member['point'],
                         'note' => '客户下单积分抵扣',
                         'batch_sn' => $orderSN);
            $memberApi -> editAccount($this -> user['member_id'], 'point', $tmp);
            Purchase_Models_API_Auth :: getInstance() -> updateAuth();
            //api 积分接口 结束
        }

        unset($data);

        //更新auth状态
        $auth_api = Purchase_Models_API_Auth :: getInstance();
        $auth_api -> updateAuth();

        //清空购物车
        $this -> setCartCookie();
        setcookie('p', '', time () + 86400 * 365, '/');
        setcookie('gift', '', time () + 86400 * 365, '/');
        setcookie('order_gift', '', time () + 86400 * 365, '/');
        setcookie('order_buy_gift', '', time () + 86400 * 365, '/');
        setcookie('groupgoods', '', time () + 86400 * 2, '/');//清空组合商品
        unset($_SESSION['price_point'], $_SESSION['price_account'], $_SESSION['offersID']);//$_SESSION['offersID']在订单买赠的时候记录下该活动ID，如果活动ID改变了，则该活动就赠品就要从新选择

        return array(
                     'order_id'=>$orderID,
                     'batch_sn'=>$orderSN,
                     'price_order'=>$priceOrder,
			         'price_goods'=>$priceGoods,
                     'pay_name'=>$payment['name'],
                     'pay_type'=>$payment['pay_type'],
                     'orderPay'=>$orderPay,
        		     'invoice_type' => $data['invoice_type'],
        		     'invoice' => $data['invoice']
                  );
    }
    
   /**
     * 礼品卡 购买
     * @param array $data
     * @param array $unionInfo
     * @return multitype:unknown mixed Ambigous <void, string> number
    */
   public function addOrderGiftCard($data,$unionInfo=Array())
   {
     $address = array_shift($this -> _db -> getAddress(array('address_id' => $this -> getSetting('addr'),
   			'member_id' => $this -> user['member_id'])));   	  
   	  
   	  $giftCard = $_SESSION['tmp_giftcard'];
   	  $cart = $this -> getCartGiftCard($giftCard);
   	
   	  //取支付信息
   	  $payment = array_shift($this -> _db -> getPayment(array('pay_type' => $data['payment'])));
   	  $product = $cart['data'];//取商品信息 
   	  $priceGoods = $cart['goods_amount'];   	  
   	  $priceLogistic=0;
   	  
   	  $priceCouponCard = $priceGiftCard = 0;

   	  $pricePay = $priceOrder = $cart['amount']; //订单金额
   	  $orderPay  = $cart['amount'];//需支付金额
   	  $pricePayed = 0; //已支付金额
   	  
   	  $time = time();
   	  if ($this -> user['ltype'] == 'phone' &&  $_COOKIE['operator_id'] ) {
   	  	$operator_id = $_COOKIE['operator_id'];
   	  	setcookie('operator_id','', time() + 86400, '/');
   	  }else{
   	  	$operator_id='0';
   	  }
   	  
   	  //新增订单
   	  $orderSN = Custom_Model_CreateSn::createSn();
   	  $source = 1;
   	  
   	  $orderID = $this -> _db -> addOrder(array('order_sn' => $orderSN,
   	  		'batch_sn' => $orderSN,
   	  		'add_time' => $time,
   	  		'invoice_type'=>$data['invoice_type'],
   	  		'invoice'=>$data['invoice'],
   	  		'operator_id' => $operator_id,
   	  		'user_id' => $this -> user['user_id'],
   	  		'user_name' => $this -> user['user_name'],
   	  		'rank_id' => $this -> user['rank_id'],
   	  		'shop_id' => '3',
   	  		'source' => $source ? $source : 0,
   	  ));
   	  
   	  //新增订单批次
   	  $orderBatchID = $this -> _db -> addOrderBatch(array('order_id' => $orderID,
   	  		'order_sn' => $orderSN,
   	  		'batch_sn' => $orderSN,
   	  		'add_time' => $time,
   	  		'is_visit' => intval($data['is_visit']),
   	  		'type' => 0,
   	  		'note' => $data['note'],
   	  		'price_order' => $priceOrder,
   	  		'price_goods' => $priceGoods,
   	  		'price_logistic' => floatval($priceLogistic),
   	  		'price_pay' => $pricePay,
   	  		'price_payed' => $pricePayed,
   	  		'account_payed' =>  0,
   	  		'point_payed' => 0,
   	  		'gift_card_payed' =>0,
   	  		'status_pay' => ($orderPay==0) ? 2 : 0,
   	  		'pay_type' => $payment['pay_type'],
   	  		'pay_name' => $payment['name'],
   	  		'addr_consignee' => $address['consignee'] ? $address['consignee'] : '',
   	  		'addr_province' => $this -> _db -> getAreaName($address['province_id']),
   	  		'addr_city' => $this -> _db -> getAreaName($address['city_id']),
   	  		'addr_area' => $this -> _db -> getAreaName($address['area_id']),
   	  		'addr_province_id' => $address['province_id'] ? $address['province_id'] : 0,
   	  		'addr_city_id' => $address['city_id'] ? $address['city_id'] : 0,
   	  		'addr_area_id' => $address['area_id'] ? $address['area_id'] : 0,
   	  		'addr_address' => $address['address'] ? $address['address'] : '',
   	  		'addr_zip' => trim($address['zip']) ? trim($address['zip']) : $this -> _db -> getAreaZip($address['area_id']),
   	  		'addr_tel' => $address['phone'] ? $address['phone'] : '',
   	  		'addr_mobile' => $address['mobile'] ? $address['mobile'] : '',
   	  		'addr_email' => $address['email'] ? $address['email'] : '',
   	  		'addr_fax' => $address['fax'] ?$address['fax'] : '',
   	  		'sms_no' => $data['sms_no'] ? $data['sms_no'] : '',
   	  ));
   	  
   	  $_msg = new Purchase_Models_API_Msg();
   	  //新增订单商品
   	  $hasCupGoods = false;
   	  $hasZeroGoods = false;
   	  $stockAPI = new Admin_Models_API_Stock();
   	  $productApi =  new Admin_Models_API_Product();
   	  $gifcardApi  = new  Admin_Models_API_GiftCard();
   	  
   	  if ($product) { 
   	  	foreach($product as $data){
   	  		$tmp[$data['product_id']] = $this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
   	  				'order_batch_id' => $orderBatchID,
   	  				'order_sn' => $orderSN,
   	  				'batch_sn' => $orderSN,
   	  				'type' => 8,
   	  				'add_time' => $time,
   	  				'product_id' => $data['product_id'],
   	  				'product_sn' => $data['product_sn'],
   	  				'goods_id' => $data['goods_id'],
   	  				'goods_name' => $data['goods_name'],
   	  				'goods_style' => $data['goods_style'],
   	  				'cat_id' => $data['cat_id'],
   	  				'cat_name' => $data['cat_name'],
   	  				'weight' => $data['p_weight'],
   	  				'length' => $data['p_length'],
   	  				'width' => $data['p_width'],
   	  				'height' => $data['p_height'],
   	  				'number' => $data['number'],
   	  				'price' => $data['org_price'],
   	  				'sale_price' => $data['price'],
   	  				'cost'       => $data['cost'],
   	  				'remark' => $data['remark']?$data['remark']:''));
   	  
   	  		$stockAPI -> holdSaleProductStock($data['product_id'], $data['number']);
   	  		//新增购买记录
   	  		if (intval($data['goods_id'])) $_msg -> insertBuyLog($data['goods_id'], $this -> user['user_name'], $this -> user['rank_name'], $time);
   	  		
   	  		//-------------礼品卡生成  start-----------------
   	  		$info = $productApi->getGiftcardInfoByProductid($data['product_id']);
   	  		$info['amount'];
   	  		  
   	  		$bodyCard = array('card_type' => 1,
   	  					'number' => $data['number'],
   	  				    'admin_name' => '前台购买',
   	  					'order_batch_goods_id' => $tmp[$data['product_id']],
   	  					'card_price' =>$info['amount'],   	  				
   	  					'user_id' => $this -> user['user_id'],
   	  					'user_name' => $this -> user['user_name'],
   	  					'status' => 3,//购买未付款，付款后状态改为0 
   	  					'end_date' => date('Y-m-d', strtotime("+3 year")),
   	  					'add_time' => time(),);   	  			
   	  		$gifcardApi->addLog($bodyCard);   	  		
   	  	   //-------------礼品卡生成  end-----------------
   	  	}
   	  }
   	  
   	  unset($_SESSION['tmp_giftcard']);
   	  
   	  return array(
   	  		'order_id'=>$orderID,
   	  		'batch_sn'=>$orderSN,
   	  		'price_order'=>$priceOrder,
   	  		'price_goods'=>$priceGoods,
   	  		'pay_name'=>$payment['name'],
   	  		'pay_type'=>$payment['pay_type'],
   	  		'orderPay'=>$orderPay,
   	  		'invoice_type' => $data['invoice_type'],
   	  		'invoice' => $data['invoice']
   	  );
   	  
   }
    
    
    
	/**
     * 设置 客服购物习惯 保存 购物地址 和 支付方式
     *
     * @return   void
     */
    public function habit()
    {
        $addressID = $this -> getSetting('addr');
        $payment  = $this -> getSetting('payment');
        $delivery  = $this-> getSetting('delivery'); 
        if ($addressID) {        	
        	$data = array();
            $memberApi = new Purchase_Models_API_Member();
            $addressID && $memberApi -> editAddressUseTime($addressID, time());
            $data['last_address_id'] = $addressID;
            if ($payment)    $data['last_pay_type']  = $payment;  
            if ($delivery)   $data['last_invoice']  = serialize($delivery); 
           
            $memberApi -> editMemberCartInfo($data);
            Purchase_Models_API_Auth :: getInstance() -> updateAuth();
            
        }
    }
	/**
     * 检查订单
     *
     * @return   bool
     */
    public function checkOrder($cart = null, $address = null,$checkAll=false)
    {
        $cart = $cart ? $cart : $this -> getCartProduct();
        if($cart['number'] <= 0){//购物车没有商品
            Custom_Model_Message::showAlert('提示：您的购物车内没有商品！' , true, '/flow/index/');
        }

        /*
            Start::检测库存
        */
        //1.检测正常商品是否有库存
        $product = $cart['data'];

        $stockAPI = new Admin_Models_API_Stock();

        if ($product) {
            $productOutSale = '';
            foreach ($product as $v) {
                if ($v['onsale'] == 1) {    //该产品已经下架
                    Custom_Model_Message::showAlert("{$v['goods_name']} 该商品已经下架", true, '/flow/index/');
                    exit;
                }
                if (!$stockAPI -> checkPreSaleProductStock($v['product_id'], $v['number'], true)) {
                    Custom_Model_Message::showAlert("{$v['goods_name']} 库存已不足！", true, '/flow/index/');
                    exit;
                }
            }

        }
        //2.检测offers是否有库存

        if(isset($cart['offers'])){
        	foreach ($cart['offers'] as $kk => $vv){
        		foreach ($vv as $kkk => $vvv){
				   if($vvv['offers_id'] == '30'){
						if(isset($_COOKIE['offers_limit'])){
							Custom_Model_Message::showAlert("您之前已经免费领取过该活动不能再次领取,请删除0元商品后再提交订单", true, '/flow/index/');
							exit;
						}
				   }
                    if(count($vvv['product'])>0){
                        foreach ($vvv['product'] as $kkkk => $vvvv){
                            if (!$vvvv['product_id']) {
                                $product = array_shift($this -> _goodsAPI -> getProduct("a.product_sn = '{$vvvv['product_sn']}'"));
                                $vvvv['product_id'] = $product['product_id'];
                            }
                            if (!$stockAPI -> checkPreSaleProductStock($vvvv['product_id'], $vvvv['number'], true)) {
                                Custom_Model_Message::showAlert("{$vvvv['goods_name']} 库存已不足！", true, '/flow/index/');
                                exit;
                            }
                        }
                    }
        		}
        	}
        }


        //3.检测group_goods是否有库存
        if(isset($cart['group_goods_summary'])){
            $adminGroupGoods_api = new Admin_Models_API_GroupGoods();
        	//遍历每个组合
        	foreach ($cart['group_goods_summary'] as $kk => $vv){
        		//遍历判断config商品库存
        		foreach ($vv['config'] as $kkk => $vvv){
        		    if (!$vvv['product_id']) {
                        $product = array_shift($this -> _goodsAPI -> getProduct("a.product_sn = '{$vvv['product_sn']}'"));
                        $vvv['product_id'] = $product['product_id'];
                    }
                    if (!$stockAPI -> checkPreSaleProductStock($vvv['product_id'], $vv['number'] * $vvv['number'], true)) {
                        $adminGroupGoods_api -> status(0, $vv['group_id']);
                        Custom_Model_Message::showAlert('组合商品 '.$vv['group_goods_name'].' 中的 '.$vvv['goods_name'].' 库存已不足', true, '/flow/index/');
                        exit;
                    }
        		}
        	}
        }
        
        
        if($checkAll)
        {
        	if (!$cart['only_vitual']) {
        		if(!$address){   //没有收货地址
        			$this -> setSetting('addr', null);
        			Custom_Model_Message::showAlert('提示：填写收货地址信息！' , true, '/flow/order/');
        		} else {
        			$this -> setSetting('addr', $address['address_id']);
        		}
        	}        	
        	 
        	$payType = $this -> getSetting('payment');
        	if (!$payType) {    //没有支付方式
        		Custom_Model_Message::showAlert('提示：请选择支付方式！' , true, '/flow/order/');
        	} else if ($payType == 'cod' && $cart['number'] == 1 && $cart['data'][0]['product_id'] == 595) {
        		$this -> setSetting('payment', null);
        		Custom_Model_Message::showAlert('提示：只领取免费商品不能使用货到付款，请更换支付方式！' , true, '/flow/order/');
        	}
        	
        	//这里重新读取是为了防止有人恶意的攻击，修改支付方式，特别是货到付款
        	$logistic = $this -> getSetting('logistic');
        	$payment = array_shift($this -> _db -> getPayment(array('status' => 0, 'pay_type' => $payType)));        	 
        	if(!$payment){  //没有支付方式
        		Custom_Model_Message::showAlert("提示：当前的支付方式不正确。可能是以下原因：\\n\\n 1.您还未选择支付方式；\\n \\n 2.您之前选择的货到付款不支持现在的配送地址；\\n\\n 请重新选择支付方式！" , true, '/flow/order/');
        	}
        }         
        
        
    }
	/**
     * 产生支付代码
     *
     * @return   string
     */
    public function getPayInfo($order)
    {
        if (!$order['pay_type']) {
            $order = array_shift($this -> _db -> getOrderBatch(array('user_id' => $this -> user['user_id'])));
        }
        $code = ucfirst($order['pay_type']);
        $class = 'Custom_Model_Payment_' . $code;
        $pay = new $class($order['batch_sn']);
        return $pay -> getCode(array('bank' => false, 'pay_hidden' => true,'target'=>true));

    }
	/**
     * 取指定条件的订单信息
     *
     * @return   string
     */
    public function getOrder($where)
    {
        return $this -> _db -> getOrder($where);
    }
	/**
     * 取得指定条件的地区列表
     *
     * @param   array     $where
     * @return  array
     */
    public function getArea($where)
    {
        return $this -> _db -> getArea($where);
    }
    /**
     * 指定条件地区列表JSON数据
     *
     * @param   array     $where
     * @return  string
     */
    public function getAreaJsonData($where)
    {
        return Zend_Json::encode($this -> _db -> getArea($where));
    }

	/**
     * 取得指定条件的订单批次
     *
     * @param   array   $where
     * @return  array
     */
    public function getOrderBatch($where=null)
    {
        $where['user_id'] = $this -> user['user_id'];
        return $this -> _db -> getOrderBatch($where);
    }
	/**
     * 取地区物流价格
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaPrice($areaID)
    {
        return $this -> _db -> getAreaPrice($areaID);
    }
	/**
     * 取订单商品信息
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getOrderBatchGoods($where)
    {
        return $this -> _db -> getOrderBatchGoods($where);
    }
    /**
     * 下单成功发邮件
     *
     * @return void
     */
	public function sendOrderEmail($goodsList,$order)
	{
        $templateValue['user_name'] = $order['user_name'];
		$templateValue['order_sn'] = $order['order_sn'];
		$templateValue['addr_consignee'] = $order['addr_consignee'];
		$templateValue['order_add_time'] = date('Y-m-d  h:i:s',$order['add_time']);
		$templateValue['price_order'] = $order['price_order'];
		$templateValue['price_goods'] = $order['price_goods'];
		$templateValue['price_pay'] = $order['price_pay'];
		$templateValue['pay_name'] = $order['pay_name'];
        $templateValue['shop_name'] = Zend_Registry::get('config') -> name;
        $templateValue['send_date'] = date('Y-m-d',time());
        $templateValue['addr_province']=$order['addr_province'];
        $templateValue['addr_city']=$order['addr_city'];
        $templateValue['addr_area']=$order['addr_area'];
        $templateValue['addr_address']=$order['addr_address'];
        $templateValue['addr_zip']=$order['addr_zip'];
        $templateValue['addr_mobile']=$order['addr_mobile'];
        $templateValue['addr_tel']=$order['addr_tel'];
        $templateValue['logistic_name']=$order['logistic_name'];
        if(!$templateValue['logistic_name']){$templateValue['logistic_name']='普通快递';}
        $templateValue['logistic_no']=$order['logistic_no'];
        $templateValue['logistic_time']=$order['logistic_time'];
        $templateValue['logistic_fee_service']=$order['price_logistic'];
        $tmp='';
        $siteurl = 'http://www.1jiankang.com';
        
        foreach ($goodsList as $v)
        {
        	if($v['type']==0)
        	{
			 $tmp.='<tr>
			<td><a href="'.$siteurl.'/b-'.$v['as_name'].'/detail'.$v['goods_id'].'.html">'.$v['goods_name'].'</a></td>
			<td>'.$v['product_sn'].'</td>
			<td>￥'.$v['price'].'</td>
			<td>'.$v['number'].'</td>
			<td>￥'.$v['price']*$v['number'].'</td>
	        </tr>';
        	}
        }
        unset($goodsList);

        $templateValue['goodsList']=$tmp;
	    $template = new Purchase_Models_API_EmailTemplate();
	    $template = $template -> getTemplateByName('add_order', $templateValue);
	    $mail = new Custom_Model_Mail();
	    if ($mail -> send($order['user_name'], $template['title'], $template['value'])) {
		    return 'sendError';
	    } else {
	    	return 'sendPasswordSucess';
	    }
	}
	/**
	 * 得到邮编
	 *
	 * @param $area_id int
	 *
	 * @return 邮编
	 * */
	public function getAreaZip($area_id) {
		return $this -> _db -> getAreaZip($area_id);
	}

    /**
	 * 按用户和活动ID获得订单数量
	 *
	 * @param $user_id int
	 * @param $offer_id int
	 *
	 * @return int
	 * */
    public function getOrderCountByUserOffer($user_id, $offer_id) {
        if ( $user_id && $offer_id ) {
            $data = $this -> _db -> getOrderCountByUserOffer($user_id, $offer_id);
            if ($data)  return count($data);
            return 0;
        }
    }
    /**
	 * 计算运费
	 *
	 * @param $user_id int
	 * @param $offer_id int
	 *
	 * @return int
	 * */
     public function getLogisticPrice($product) {
        $shopConfig = Zend_Registry::get('shopConfig');
        if (!isset($shopConfig['price_logistic'])) {
            $shopConfig['price_logistic'] = 10;
        }
        if (!isset($shopConfig['free_logistic'])) {
            $shopConfig['free_logistic'] = 199;
        }
        $cards = $_SESSION['Card']['cardCertification'];//取抵用券信息
        if ($cards) {
            foreach ($cards as $v) {
                $priceCoupon += $v['card_price'];
            }
        }
		if (isset($shopConfig['free_logistic']) && ($product['goods_amount']) >= $shopConfig['free_logistic']) {
			$priceLogistic = 0;
            return $priceLogistic;
		} else {
			$priceLogistic = $shopConfig['price_logistic'];//默认物流费用 系统设置里面
		}

    	if ( $priceLogistic < 0 )   $priceLogistic = 0;
        return $priceLogistic;
     }
 
}