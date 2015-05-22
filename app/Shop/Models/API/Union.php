<?php
 class Shop_Models_API_Union
 {
 	/**
     * 联盟 DB
     * 
     * @var Shop_Models_DB_Union
     */
	private $_db = null;
	
	/**
     * 联盟认证信息
     * 
     * @var array
     */
	private $_auth = null;
	
	/**
     * 联盟参数名称
     * 
     * @var    string
     */
 	private $_uidName = 'u';
	
	/**
     * 联盟配置文件
     * 
     * @var    string
     */
 	private $_unionConfigFile = 'config/union.xml';
 	
 	/**
     * 联盟配置信息
     * 
     * @var    Custom_Config_Xml
     */
 	private $_unionConfig = null;
	
 	/**
     * 对象初始化
     *
     * @return void
     */
    public function __construct($getUId = null)
    {
    	$this -> _db = new Shop_Models_DB_Union();
    	$this -> _uid = $getUId ? $getUId : (is_object(Zend_Controller_Front::getInstance() -> getRequest()) ? (int)Zend_Controller_Front::getInstance() -> getRequest() -> getParam($this -> _uidName) : 0);
    	$this -> _unionConfig = Custom_Config_Xml::loadXml(Zend_Registry::get('systemRoot') . '/' . $this -> _unionConfigFile);
    }
    
    /**
     * 保存推荐uid及其二级推荐
     *
     * @param	void
     * @return  void
     **/
    public function setUnionCookie()
    {
    	if ($this -> _uid && self::ifInDomain()) {
            $userInfo = $this -> getUnionById($this -> _uid);
            if ($userInfo) {
                if ($userInfo['affiliate_type'] == '1' || !$userInfo['affiliate_type']) {
                    //按照点击分成的cookies保留一个月
                    $time = time() + 84600 * (int)$this -> _unionConfig -> cookie -> expire -> click;
                } elseif ($userInfo['affiliate_type'] == '2') {
                    //按照注册用户绑定的 24小时
                    $time = time() + 84600 * (int)$this -> _unionConfig -> cookie -> expire -> register;
                }
                $nID = $this -> getNidFromUrl();
                $cookieValue = ($nID) ? $this -> _uid . '|' . $nID : $this -> _uid;
                setcookie($this -> _unionConfig -> cookie -> name, $cookieValue, $time, '/');
            }
            
            return true;
    	}
    	
    	return false;
    }
    
    /**
     * 从URL获取联盟二级推荐nid
     *
     * @param	void
     * @return	string
     **/
    private function getNidFromUrl()
    {
	    if ($this -> _uid) {
		    $i = 0;
		    foreach (Zend_Controller_Front::getInstance() -> getRequest() -> getParams() as $key => $value)
		    {
			    $getArray[] = $key;
			    if (($getArray[$i-1] == $this -> _uidName) && Zend_Controller_Front::getInstance() -> getRequest() -> getParam($key)) {
				    return  Zend_Controller_Front::getInstance() -> getRequest() -> getParam($key);
			    }
			    $i++;
		    }
	    }
    }

    /**
     * 从COOKIE获取推荐uid
     *
     * @param   void
     * @return int
     **/
    public function getUidFromCookie($returnArr = false)
    {
        if ($_COOKIE[$this -> _unionConfig  -> cookie -> name]) {
    	    $cookieValue = @explode('|', $_COOKIE[$this -> _unionConfig  -> cookie -> name], 2);
            $uid = $cookieValue[0];
            $userInfo = ($uid) ? $this -> getUnionById($uid) : '';
            if (isset($userInfo['union_type'])) {
                return $returnArr ? $userInfo : $uid;
            } else {
                setcookie($this -> _unionConfig  -> cookie -> name, '', -1, '/');
                return null;
            }
        }
    }
    
    /**
     * 从COOKIE获取二级推荐nid
     * 
     * @param   void
     * @return int
     **/
    public function getNidFromCookie()
    {
        if ($_COOKIE[$this -> _unionConfig -> cookie -> name]) {
    	    $cookieValue = @explode('|', $_COOKIE[$this -> _unionConfig -> cookie -> name], 2);
            $nID = $cookieValue[1];
            return $nID;
        }
    }
    
    /**
     * 获取购物车cookie中的普通商品及数量
     *
     * @return  array
     */
    public function getCartInfo()
    {
        if ($_COOKIE['cart']) {
            $tmp = explode('|', $_COOKIE['cart']);
            if (is_array($tmp) && count($tmp)) {
            	$goods = new Shop_Models_API_Goods();
                foreach ($tmp as $temp) {
                    $item = explode(',', $temp);
                    $productID = $item[0];
                    $productNumber = $item[1];
                    if ($productNumber > 0) {
                        $productInfo[$productID] = $productNumber;
                    }
                }
                $goodsIds = $goods -> getProduct("a.product_id in(" . implode(',', array_keys($productInfo)) . ")", 'b.goods_id,a.product_id');
                foreach ($goodsIds as $key => $value)
                {
                	$goodsInfo[$value['goods_id']] += $productInfo[$value['product_id']];
                }
            }
        }
        $goodsInfo = is_array($goodsInfo) ? $goodsInfo : array();
        $productInfo = is_array($productInfo) ? $productInfo : array();
        return array('productInfo' => $productInfo, 'goodsInfo' => $goodsInfo);
    }
    
    /**
     * 删除推荐COOKIE
     * 
     * @param   void
     * @return int
     **/
    public function deleteCookie()
    {
        if ($_COOKIE[$this -> _unionConfig -> cookie -> name]) {
    	    setcookie($this -> _unionConfig -> cookie -> name, '', 0, '/');
        }
    }
    
    /**
     * 根据user_id获取联盟信息
     *
     * @param    int    $uid
     * @return   array
     */
    public function getUnionById($uid)
    {
        $userInfo = $this -> _db -> getUnionById((int)$uid);
        if ($userInfo) {
			if ($userInfo['union_normal_id'] && $userInfo['normal_status'] == '1') {
				$userInfo['union_type'] = '2';
			}else{
			    $userInfo['union_type'] = '1';
			}
			return $userInfo;
		}
    }
    
    /**
     * 域名是否为官方域名
     *
     * @param    void
     * @return   boolean
     */
    public static function ifInDomain()
    {
    	$serverDomains = explode(',', Zend_Registry::get('config') -> domain);
    	foreach ($serverDomains as $domain)
    	{
    		if (substr($_SERVER['HTTP_HOST'], -strlen($domain)) == $domain) {
    			return true;
    		}
    	}
    }
    
    /**
     * 记录union访问记录
     *
     * @param    void
     * @return   array
     */
    public function addUnionLog()
    {
    	if ($this -> _uid) {
    		$union = $this -> getUnionById($this -> _uid);
            if($union){
                $union['date'] = date('Y-m-d');
                $union['login_time'] = time();
                $union['login_ip'] = $_SERVER['REMOTE_ADDR'];
                $union['referer'] = ($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
               // $ipLocation = new Custom_Model_IpLocation();
               //$location = $ipLocation -> getlocation();
               // $union['area'] = mb_convert_encoding($location['country'], 'UTF-8', 'GBK');
               // $union['other'] = mb_convert_encoding($location['area'], 'UTF-8', 'GBK');
                $this -> _db -> addUnionLog($union);
                return true;
            }
    	} 
    }
    
    /**
     * 设置订单所属关系
     *
     * @param    void
     * @return   array
     */
    public function setOrderParent()
    {
        $order['parent_id'] = 0;
        $order['parent_param'] = '';
        $order['proportion'] = 0;
    	$auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
        if (!$order['parent_id'] && $_SESSION['Card']['cardCertification']) { // 按券绑定(礼金券) 
            foreach ($_SESSION['Card']['cardCertification'] as $card) {
                if ($card['type'] == 'coupon' && $card['is_affiliate'] == 1 && $card['parent_id'] > 0) {
                    $unionInfo = $this -> getUnionById($card['parent_id']);
                    $order['parent_id'] = $card['parent_id'];
                    if($card['aid']!='0'){
                       $order['parent_param'] = $card['aid'];
                    }else{
                       $order['parent_param'] = '';
                    }
                    switch ($unionInfo['union_type']) {
                        case '2' :
                            $order['proportion'] = $unionInfo['normal_proportion'];
                            break;
                        default :
                            break;
                    }
                    
                }
            }
        }

        if (!$order['parent_id']) {
            if ($unionInfo = $this -> getUidFromCookie(true)) {//看cookie是否有值
                    if ($unionInfo['union_type'] == '2' && $unionInfo['affiliate_type'] == '1') { //推广联盟 cps
                        //===注意 这部分不能抽出来共用
                        $order['parent_id'] = $unionInfo['user_id'];
                        $order['parent_param'] = $this -> getNidFromCookie();
                        
                        //===注意
                        switch($unionInfo['normal_proportion_rule']){
                            case 1 :
                                //===需要扣除会员折扣
                                $discount = $auth['rank_id'] > 2 ? $auth['discount'] : 1;
                                $order['proportion'] = $unionInfo['normal_proportion']-((1-$discount)*100);
                                $order['proportion'] < 0 && $order['proportion'] = 0;
                                break;
                            default:
                                $order['proportion'] = $unionInfo['normal_proportion'];
                        }
                    } 
            }
        }
        $order['un_type'] = isset($unionInfo['un_type']) ? $unionInfo['un_type'] : 0;
        $order['calculate_type'] = isset($unionInfo['calculate_type']) ? $unionInfo['calculate_type'] : 1;
		return $order;
    }
    /**
     * 添加分成信息
     *
     * @param    array    $data
     * @param    array    $cart  购物车商品
     * @return   array
     */
    public function setAffiliate($data,$cart=null)
    {
    	/*
    	 * 当订单可分成金额改变时或订单状态改变时
    	 * $data['parent_id']  联盟用户ID
    	 * $data['parent_param'] 联盟二级参数
    	 * $data['proportion'] 分成比率
    	 * $data['order_id'] 订单ID
    	 * $data['order_sn'] 订单SN
    	 * $data['status'] 订单状态(0:正常单,1:取消单,2:无效单)
    	 * $data['status_logistic'] 配送状态(0:未确认,1:已确认待收款,2:待发货,3:已发货,4:客户已签收)
    	 * $data['status_return'] 订单类型(0:正常单,1:退货单,2:换货单,3:退换货单)
    	 * $data['user_id'] 订单用户ID
    	 * $data['user_name'] 订单用户名
    	 * $data['affiliate_amount'] 订单可分成金额
    	 * $data['price_goods'] 订单商品总价
    	 * $data['price_order'] 订单总价
         * 前台下单
         * 确认订单取消
         * 待发货订单取消
         * 订单无效
         * 修改订单商品
         * 退换货开单
         * 调整订单金额
    	 */
    	 if ($data) {
    	     $union = $this -> getUnionById($data['parent_id']);
    	     if ($union['union_type']) {
    	     	$data['parent_name'] = $union['user_name'];
    	        $data['union_type'] = $union['union_type'];
                $data['un_type'] = $union['un_type']?$union['un_type']:0; //CPA
    	 	    $data['add_time'] = time();
    	 	    $data['modify_time'] = time();
    	 	    $data['separate_type'] = ($data['status'] == 0) ? 0 : 2;
                $data['proportion'] = $union['normal_proportion'];
                
                if(in_array($data['parent_id'],array('9096','61075','70537','70554'))){
                    if (isset($_COOKIE['u'])) {
                        $tmpVar = @explode('|', $_COOKIE['u'], 2);
                        $u=  intval($tmpVar[0]);
                        $aid= substr($tmpVar[1], 0, 10);
                        if(in_array($u,array('9096','61075')) && ($aid == 'A100060164' || $aid == 'A100136514' )){
                            if ($_COOKIE['u_linkt']=='1' ){//只是通过QQ按钮登录记录分成信息
                                 $data['calculate_type']=1;
                                 $data['proportion']=1;
                            }
                           $data['code_param']=serialize(array(
                                'u_linkt' =>$_COOKIE['u_linkt']
                            ));
                        }
                        else if ($u == 70537 || $u == 70554) {
                            $data['code_param'] = serialize($_COOKIE['shunion']);
                        }
                    }
                }

                /* Start::计算是“固定分成比率” or “分类分成比率2012.2.9 */
    			$data['calculate_type'] = $union['calculate_type'];
    			//固定分成比率 表 shop_union_normal.calculate_type=1
    			if($data['calculate_type']==1)
    			{
                	$data['affiliate_amount'] && $data['proportion'] && $data['affiliate_money'] = round($data['affiliate_amount'] * $data['proportion']/100, 2);
    			}
    			//商品分成比率  表shop_union_normal.calculate_type=2
    			elseif($data['calculate_type']==2)
    			{
    				$minProportion = 100;
    				//普通商品
    				if($cart['data'] && $data['affiliate_amount']){
    					foreach ($cart['data'] as $v){
    						$price = $v['eq_price'] ? $v['eq_price'] : $v['price'];
    						$proportion = (int)$this -> _db -> getOneGoodsProportion($data['parent_id'], $v['goods_id']);
    						if ( $proportion < $minProportion ) $minProportion = $proportion;
    						$data['affiliate_money'] += round($price * ($v['number']-$v['return_number']) * $proportion/100, 2);
    					}
    				}
    				//组合商品
    				if($cart['group_goods_summary'] && $data['affiliate_amount']){
    					foreach ($cart['group_goods_summary'] as $v){
    						if(isset($v['group_price'])){//添加订单时候的价格名称
    							$price = $v['eq_price'] ? $v['eq_price'] : $v['group_price'];
    						}else{//修改订单时候的价格名称
    							$price = $v['eq_price'] ? $v['eq_price'] : $v['price'];
    						}
    						$proportion = 16;
    						if ( $proportion < $minProportion ) $minProportion = $proportion;
    						$data['affiliate_money'] += round($price * ($v['number']-$v['return_number']) * $proportion/100, 2);
    					}
    				}
    				//自选礼包
    				if($cart['offers'] && $data['affiliate_amount']){
    					foreach ($cart['offers'] as $v){
    						foreach ($v as $vv){
                                if(count($vv['product'])>0){
                                    foreach ($vv['product'] as $vvv){
                                        $price = $vvv['price'];
                                        if(isset($vvv['number'])){$number = $vvv['number'];}else{$number = 1;}
                                        if(isset($vvv['return_number'])){$return_number = $vvv['return_number'];}else{$return_number = 0;}
                                        $proportion = (int)$this -> _db -> getOneGoodsProportion($data['parent_id'], $vvv['goods_id']);
                                        if ( $proportion < $minProportion ) $minProportion = $proportion;
                                        $data['affiliate_money'] += round($price * ($number-$return_number) * $proportion/100, 2);
                                    }
                                }
    						}
    					}
    				}
    				
    				//扣除订单优惠金额的佣金
    				if ( ($data['price_goods'] - $data['affiliate_amount']) > 0 ) {
    				    $data['affiliate_money'] -= round(($data['price_goods'] - $data['affiliate_amount']) * $minProportion / 100, 2);
    				}
    				
    				$data['proportion'] = 99;
    			}
    			//其他情况
    			else{
    				return;
    			}
    	 	    /* End::计算是“固定分成比率” or “分类分成比率2012.2.9 */
    			
                if (!$data['affiliate_money']) {
                    $data['affiliate_money'] = 0;
                }
                //CPA
                if ($union['un_type'] != 1){
                     $data['cpa_goods_type'] = 0;
                } 
                $data['cpa_zero_type'] = ($data['cpa_zero_type']) ? $data['cpa_zero_type'] :0;
    	 	    return $this -> _db -> addAffiliate($data);
    	     }
    	 }
    }

    /**
     * 过滤分成数据里面的商品
     *
     * @param    string    $batchSN
     * @return   void
     */
    public function _fliterProductData(&$products, $unionId = 0)
    {
        $this -> _orderFavourablePrice = '';    //订单优惠金额
                 
        if (is_array($products)) {
            foreach ($products as $k => $v) {
                if ($v['product_id'] && ($v['offers_type'] == 'fixed-package' || $v['offers_type'] == 'choose-package')) {//剔除礼包内商品
                    unset($products[$k]);
                } 
                else if (!$v['product_id'] && $v['offers_type'] != 'fixed-package' && $v['offers_type'] != 'choose-package') {//剔除除礼包外的所有活动
                    if ( $v['goods_name'] == '礼券' || $v['offers_type'] == 'minus' ) {
                        $this -> _orderFavourablePrice[$v['order_sn']] += $v['sale_price'];
                    }
                    unset($products[$k]);
                }
            }
        }
    }
    /**
     * 主动给联盟官网传递分成数据
     *
     * @param    string    $batchSN
     * @return   void
     */
    public function orderApi($batchSN, $unionID,$un_type =0)
    {
        // 允许访问的id列表
        $allowUnionId = array(9096,9143,9146,9133,3519,3763,61075,61044,70537,70554);
        $auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
        $product = null;
        if (in_array($unionID,$allowUnionId)) {
            $db = new Shop_Models_DB_Order();
            $order = array_shift($db -> getOrderBatch(array('batch_sn' => $batchSN)));
            $product = $db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'number>' => 0));
            $this -> _fliterProductData($product);
            $user = $this -> _db -> getMember(' and A.user_id ='.$order['user_id']);
            $nID = $order['parent_param']; // 联盟子ID
            $userID = $user['user_id']; // 用户ID
            $rankID = $user['rank_id']; // 会员等级
            $discount = $user['discount']*100; // 会员折扣
            if($discount <= 0 || $discount > 100) $discount = 100;
            $userName = $user['user_name']; // 用户名 尽量不给第三方传 
            $regTime = $user['add_time']; // 用户注册时间
            $orderAddTime = $order['add_time']; //订单下单时间
            $batchSN = $order['batch_sn']; // 订单编号
            $pricePay = $order['price_pay']; // 订单金额
            $priceGoods = $order['price_goods']; // 订单商品金额
            $realGoodsPrice=$order['price_pay'] - $order['price_logistic'];
        }
		if($product){
			switch ($unionID){
				case 9096://linktechcps
					foreach($product as $value){
						$cps_pid[] = $value['product_id'];
						$cps_pnum[] = $value['number'];
						$cps_price[] = $value['sale_price'];
                        $cps_pcd[] = $this->getUnionGoodsProportion('9096',$value['goods_id']); 
                        $cps_qq_login[]='qq_login';
					}
					$cps_lkt_mid = '1jiankang';
					$cps_lkt_pcd = is_array($cps_pid)?implode('||',$cps_pid):$cps_pid;
					$cps_lkt_cnt = is_array($cps_pnum)?implode('||',$cps_pnum):$cps_pnum;
					$cps_lkt_price = is_array($cps_price)?implode('||',$cps_price):$cps_price;
                    $cps_lkt_cps_pcd = is_array($cps_pcd)?implode('||',$cps_pcd):$cps_pcd;

                    if($_COOKIE['u_linkt']=='1'){
                        $c_cd = is_array($cps_qq_login)?implode('||',$cps_qq_login):$cps_qq_login;
                        $userID=str_replace('Tencent:','',$user['user_name']);
                        $nID=$nID.str_replace('Tencent:','',$user['user_name']);
                        $cps_lkt_cps_pcd=$c_cd;
                    }
                    if($_COOKIE['u_linkt']=='2'){
                        $mbr_name='A100136514'.str_replace('Tencent:','',$auth['user_name']);
                    }
                    $html = "<img width='1' height='1' src='http://service.linktech.cn/purchase_cps.php?a_id={$nID}&m_id={$cps_lkt_mid}&mbr_id={$userID}&mbr_name={$mbr_name}&o_cd={$batchSN}&p_cd={$cps_lkt_pcd}&price={$cps_lkt_price}&it_cnt={$cps_lkt_cnt}&c_cd={$cps_lkt_cps_pcd}' />";
                    setcookie('u_linkt', '', time () + 86400 * 30, '/');
                    $html .=$cpahtml ;

					break;

		
				case 9146://51fanli
                    $orderTime = date('Y-m-d H:i:s',$order['add_time']) ; 
                    $total_qty=count($product);
                    $fanli_u_id = $_COOKIE['fanli_u_id'];
                    $channel_id = $_COOKIE['channel_id'];
                    $fanli_username = $_COOKIE['fanli_username'];
					$xml="";
					$xml .= "<fanli_data version='3.0'>\n";
					$xml .= "<order order_time='{$orderTime}' order_no='{$batchSN}' shop_no='1jiankang' total_price='{$pricePay}' total_qty='{$total_qty}' shop_key='51fanli1jiankang' u_id='{$fanli_u_id}' username='{$fanli_username}' is_pay='0' pay_type='2' order_status='1' deli_name='' deli_no ='' tracking_code='{$nID}' pass_code=''>\n";
				    $xml .= "<products_all>\n";
					foreach($product as $value){
						$xml .= "<product>\n";
						$xml .= "<product_id>{$value[product_id]}</product_id>\n";
						$xml .= "<product_url>http://www.1jiankang.com/goods-{$value[product_id]}.html</product_url>\n";
						$xml .= "<product_qty>{$value[number]}</product_qty>\n";
						$xml .= "<product_price>{$value[sale_price]}</product_price>\n";
						$xml .= "<product_comm>0</product_comm>\n";
						$xml .= "<comm_no>0</comm_no>\n";
						$xml .= "</product>\n";
					}
					$xml .= "</products_all>\n";
				    $xml .= "</order>\n";
					$xml .= "</fanli_data>\n";
					$url = "http://data2.51fanli.com/index.php/DataHandle/handlePostData";
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url); 
					$post_data = array(
						"content" => $xml
					);
					curl_setopt($curl, CURLOPT_POST, true); 
					curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
					$data = curl_exec($curl); 
					curl_close($curl); 
					break;

				case 9133://eqifa
					foreach($product as $value){
						$cps_pid[] = $value['product_id'];
                        $cps_psn[] = $value['product_sn'];
						$cps_pnum[] = $value['number'];
						$cps_price[] = $value['sale_price'];
                        $cps_goods[] = urlencode($value['goods_name']);
                        $cps_ct[] = '';
                    
					}
					$orderTime = date('Y-m-d H:i:s',$order['add_time']) ;
					$orderTime  = mb_convert_encoding($orderTime, 'GBK', 'UTF-8'); 
					$cps_eqifa_pcd = is_array($cps_pid)?implode('|',$cps_pid):$cps_pid;
					$cps_eqifa_cnt = is_array($cps_pnum)?implode('|',$cps_pnum):$cps_pnum;
					$cps_eqifa_price = is_array($cps_price)?implode('|',$cps_price):$cps_price;
	                $cps_eqifa_goodsname = is_array($cps_goods)?implode('|',$cps_goods):$cps_goods;
                    $cps_eqifa_ct = is_array($cps_ct)?implode('|',$cps_ct):$cps_ct;
                    $cps_eqifa_psn = is_array($cps_psn)?implode('|',$cps_psn):$cps_psn;
					$cid = $_COOKIE['cid'];
					$wi = $_COOKIE['wi'];
                    $html = "<img width='1' height='1' src='http://o.yiqifa.com/servlet/handleCpsInterIn?interId=5191fd5fe03bbcaa579e8b03&cid={$cid}&wi={$nID}&on={$batchSN}&pn={$cps_eqifa_psn}&pna={$cps_eqifa_goodsname}&ta={$cps_eqifa_cnt}&ct={$cps_eqifa_ct}&pp={$cps_eqifa_price}&sd={$orderTime}&encoding=utf-8' />";
					break;
                
                case 9143://成果CPS
                    $t = '7934';
					$key='k6IPPhuazjg8yQxE';
					$sign =md5('t=7934&id='.$nID.'&i='.$batchSN.'&key='.$key);
					$orderTime = date('Y-m-d H:i:s',$order['add_time']) ;
					$pay_type = substr($order['pay_type'], 0, 6);
					switch($pay_type){
						case 'cod' :
							$pm = 1;
							break;
						case 'alipay' :
							$pm = 2;
							break;
						case 'tenpay' :
							$pm = 3;
							break;
						default :
							$pm = 9;
							break;
					}
                    $o_arr = array();
                    foreach($product as $value){
                          $o_arr[] = "GOODS/{$value['sale_price']}/{$value['number']}/".urlencode($value['goods_name']);
					}
					$o = implode(':', $o_arr);
					$html = "<img width='1' height='1' src='http://count.chanet.com.cn/add_action_ec_2.cgi?t={$t}&id={$nID}&i={$batchSN}&sign={$sign}
&o={$o}&ot={$orderTime}&pm={$pm}&ps=0&st=0' />";
					break;
                case 3763:
                    $hash = "2039024f4188b9235f74f2122503fb21";
                    $orderTime = urlencode(date('Y-m-d H:i:s',$order['add_time']));
                    $cat_name_arr = array();
                    $number_arr = array();
                    $goods_sn_arr = array();
                    $goods_name_arr = array();
                    $sale_price_arr = array();
                    $rate_arr = array();
                    foreach($product as $value){
                        $cat_name_arr[] = $value['cat_name'];
                        $number_arr[] = $value['number'];
                        $goods_sn_arr[] = $value['product_sn'];
                        $goods_name_arr[] = $value['goods_name'];
                        $sale_price_arr[] = $value['sale_price'];
                        $rate_arr[] = '0';
                    }
                    $cat_name = urlencode(implode('|', $cat_name_arr));
                    $number = urlencode(implode('|', $number_arr));
                    $goods_sn = urlencode(implode('|', $goods_sn_arr));
                    $goods_name = urlencode(implode('|', $goods_name_arr));
                    $sale_price = urlencode(implode('|', $sale_price_arr));
                    $rate = urlencode(implode('|', $rate_arr));
                    
                    $param = "hash={$hash}&euid={$nID}&order_sn={$batchSN}&order_time={$orderTime}&goods_cate={$cat_name}&goods_ta={$number}&goods_id={$goods_sn}&goods_name={$goods_name}&goods_price={$sale_price}&rate={$rate}&orders_prices={$realGoodsPrice}&status=0";
                    $html = "<img width='1' height='1' src='http://www.duomai.com/api/order.php?{$param}' />";
                    break;
                    
                case 61044: 
                    $a_id = '53';
                    $subid = $nID;
                    $o_cd = $batchSN;
                    $p_cd = '';
                    $price = '';
                    $it_cnt = '';
                    $rate = '';
                    $goodsAPI = new Shop_Models_DB_Goods();
                    foreach($product as $value){
                        $p_cd .= $value['product_sn'].'||';
                        $price .= $value['sale_price'].'||';
                        $it_cnt .= $value['number'].'||';
                        $cat = $goodsAPI -> getGoodsCat($value['cat_id']);
                        if ($cat['cat_path']) {
                            $tempArr = explode(',', $cat['cat_path']);
                            if (in_array(2, $tempArr)) {    //保健食品
                                $rate .= '6'.'||';
                            }
                            else    $rate .= '16'.'||';
                        }
                        else    $rate .= '16'.'||';
                    }
                    $p_cd = substr($p_cd, 0, -2);
                    $price = substr($price, 0, -2);
                    $it_cnt = substr($it_cnt, 0, -2);
                    $o_date = date('YmdHis',$order['add_time']);
                    $rate = substr($rate, 0, -2);
                    $status = 1;
                    $html = "<img width='1' height='1' src='http://api.zhitui.com/recive.php?a_id={$a_id}&subid={$subid}&o_cd={$o_cd}&p_cd={$p_cd}&price={$price}&it_cnt={$it_cnt}&o_date={$o_date}&rate={$rate}&rate_memo=&status={$status}&note=' />";
                    break;
                    
                case 70537 :
                case 70554 :
                    if ($unionID == 70537) {
                        $shop_no = '1jiankang';
                        $shop_key = '0dcb2054fac4f29e';
                    }
                    else {
                        $shop_no = '99nc';
                        $shop_key = 'fbf4627a8784d6ec';
                    }
                    $total_qty = count($product);
                    $pay_type = $order['pay_type'] == 'cod' ? 2 : 1;
                    if ($_COOKIE['shunion']) {
                        $params = explode('|', $_COOKIE['shunion']);
                    }
                    else {
                        $params = '';
                    }
                    $pass_code = md5(strtolower($batchSN.$shop_no.$params[1].$shop_key));
                    $xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
                            <shunion_data version=\"1.0\" >
                            <order> 
                            <order_time>".date('Y-m-d H:i:s', $order['add_time'])."</order_time>
                            <order_no>{$batchSN}</order_no>
                            <shop_no>{$shop_no}</shop_no>
                            <total_price>{$order['price_goods']}</total_price>
                            <total_qty>{$total_qty}</total_qty>
                            <u_id>{$params[1]}</u_id>
                            <username>{$auth['user_name']}</username>
                            <is_pay>0</is_pay>
                            <pay_type>{$pay_type}</pay_type>
                            <order_status>1</order_status>
                            <deli_name></deli_name>
                            <deli_no></deli_no>
                            <tracking_code>{$params[2]}</tracking_code>
                            <pass_code>{$pass_code}</pass_code>
                    	    <commission>%commission%</commission>
                            <products_all>";
                    $goodsAPI = new Shop_Models_API_Goods();
                    $goodsDB = new Shop_Models_DB_Goods();
                    $commissionInfo[1] = 0.2;
                    $commissionInfo[2] = 0.06;
                    $commission = 0;
                    foreach ($product as $value) {
                        $goodsInfo = $goodsDB -> getGoodsProductInfo("and t2.product_id = {$value[product_id]}", 't1.goods_id');
                        if ($goodsAPI -> isRootCat($value['product_id'], 1)) {
                            $catType = 1;
                        }
                        else {
                            $catType = 2;
                        }
                        $product_comm = round($value['sale_price'] * $value['number'] * $commissionInfo[$catType], 2);
                        $commission += $product_comm;
						$xml .= "<product>
						         <product_id>{$value[product_id]}</product_id>
						         <product_name>{$value[goods_name]}</product_name>
						         <product_url>http://".$_SERVER['HTTP_HOST']."/goods-{$goodsInfo['goods_id']}.html</product_url>
						         <product_qty>{$value['number']}</product_qty>
						         <product_price>{$value['sale_price']}</product_price>
						         <product_comm>{$product_comm}</product_comm>
						         <comm_no>A</comm_no>
						         </product>";
					}
					$xml = str_replace('%commission%', $commission, $xml);
                    $xml .= "</products_all>
                             <coupons_all>
                             <coupon>
                             <coupon_no></coupon_no>
                             <coupon_qty></coupon_qty>
                             <coupon_price></coupon_price>
                             <comm_no></comm_no>
                             </coupon>
                             </coupons_all>
                             </order>
                             </shunion_data>";

					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, 'http://222.73.244.88/shunion/HandlePostOrder'); 
					curl_setopt($curl, CURLOPT_POST, true); 
					curl_setopt($curl, CURLOPT_POSTFIELDS, array('content' => $xml));
					$data = curl_exec($curl); 
					curl_close($curl); 
					
                    break;
                    
				default:
					break;
			}
		}
        return $html;
    }

    /**
     * 前台注册
     *
     * @param    string    $batchSN
     * @return   void
     */
    function regApi($unionID)
    {
        $user = Shop_Models_API_Auth :: getInstance() -> getAuth();
        switch ($unionID){
            case 9096:
                //领克特cps
                $LTINFO = $this -> getNidFromCookie();
                $lt_merchant_id = '1jiankang';
                $lt_user_id = $user['user_id'];
                $lt_user_name = $user['user_name'];
                $lt_o_cd = $user['user_id'];
                $html = "<script src='http://service.linktech.cn/purchase_cpa.php?a_id=".$LTINFO."&m_id=".$lt_merchant_id."&mbr_id=".$lt_user_id."(".$lt_user_name.")&o_cd=".$lt_o_cd."&p_cd=1jiankang_member'></script>";
                break;
            default:
                break;
        }
        return $html;
    }
    
    /**
     * 联盟异步读取时的错误log
     *
     * @param  string   $msg
     * @return void
     */
    public function writeSyncLog($msg)
    {
        $_realIp = $this -> _real_ip();
        $glog = date('Y-m-d H:i:s') . '-' . $_realIp. "\t" . $msg . $_SERVER['REQUEST_URI']."\n";
        error_log($glog, 3, Zend_Registry::get('systemRoot') . '/tmp/unget_data_log.php');
    }
    
    /**
     * 向浏览器统一输出错误信息
     *
     * @param  string   $msg
     * @return void
     */
    public function httpHeader($msg) 
    {
        header('Cache-control: private');
        header('Content-type: text/plain; charset=utf-8');
        echo $msg;
        exit;
    }
    /**
     * 统计联盟异步请求的频繁度
     *
     * @param string $realIp 请求IP
     * @return void
     */
    public function wirteUnionRequestInfo($uUserName = '', $realIp = '')
    {
        $fileName = Zend_Registry::get('systemRoot') . '/tmp/unget_request_log' . date('Ymd') . '.php';
        if (!is_file($fileName)) {
            file_put_contents($fileName,"<?php\n");
        }        
        $requestLog = date('Y-m-d H:i:s').'-'.$realIp."\t".$uUserName.':'.$_SERVER['REQUEST_URI']."\n";
        error_log($requestLog, 3, $fileName);
    }


   /**
     * 异步校验联盟官网分成数据
     *
     * @param    string    $batchSN
     * @return   void
     */
    public function unlist($user_name, $password, $starttime, $endtime)
    {
        $_real_ip = $this -> _real_ip();
        $pass_on = true;
        $glog_error = '';
        if($user_name && $password) {
            $tmp = $this -> _db -> onlyGetUser($user_name);
            if ($tmp['password'] != md5($password)) {
                $glog_error = "验证失败，服务器拒绝请求！";
            }
            $this -> getUnionOrderList($tmp['user_id'], $starttime, $endtime);
        }
    }
    /**
     * 异步校验联盟官网分成数据
     *
     * @param    string    $batchSN
     * @return   void
     */
    public function syncApi($user_name, $password, $starttime, $endtime)
    {
        $_real_ip = $this -> _real_ip();
        $pass_on = true;
        $glog_error = '';
        if(!$pass_on){
            $glog_error = "请求来源或参数非法，服务器拒绝请求！";
            $this -> writeSyncLog($glog_error);
            $this -> httpHeader($glog_error);
        }
        
        if($user_name && $password) {
            $tmp = $this -> _db -> onlyGetUser($user_name);
            if ($tmp['password'] != md5($password)) {
                $glog_error = "验证失败，服务器拒绝请求！";
                $this -> writeSyncLog($glog_error);
                $this -> httpHeader($glog_error);
            }
            $this -> _getUnionOrder($tmp['user_id'], $starttime, $endtime);
        }
    }
    /**
     * 根据用户名仅获取用户基本信息
     *
     * @param    int    $userID
     * @return   array
     */
    function onlyGetUser($userName)
    {
		return $this -> _db -> onlyGetUser($userName);
    }
 
    function getUnionOrderList($unionID, $fromDate, $toDate)
    {
        if (!$unionID) {
			exit('非法参数，获用户取信息失败！');
        }
        $fromDate = strtotime($fromDate);
        $toDate = strtotime($toDate) + 86400;
        
        if($toDate - $fromDate > 2592000){
			exit('查询时间段请不要超过30天！');
        } else if($toDate - $fromDate < 0) {
			exit('非法参数，日期错误！');
        }
        $information = new Shop_Models_API_Information();
        $where = array('from_date' => $fromDate, 'to_date' => $toDate);
        $temp = $information -> getUnionOrderList($where, $unionID);
        $result = '';
        if ($temp) {
            $k = 0;
            foreach ($temp as $info) {
                if($info){
                    switch($unionID){
                        case 9133: //eqifa.com
							$add_time = date('Y-m-d H:i:s', $info['add_time']);
							$add_time  = mb_convert_encoding($add_time, 'GBK', 'UTF-8'); 
							$price_logistic=  $info['order_price'] - $info['order_price_goods'];
                            $result = $info['user_param']."||"
									.  $add_time.'||'
									.  $info['order_sn'].'||'
									.  $info['order_status'].'||'
									. $info['status_pay'].'||'
									.  $info['price_logistic'].'||'
									. $info['pay_name'].'||'
									. $info['price_goods']." \n";
                            break;
                       default:
                            break;
                    }
                }
                echo $result;
                if ($k != 0 && $k%100 == 0) {
                    ob_flush();
                    flush();
                }
                $k++;
            }
        }
    }
    function _getUnionOrder($unionID, $fromDate, $toDate)
    {
        //注意：尽量不给联盟传递下单人的用户名
        if (!$unionID) {
            $msg = '非法参数，获用户取信息失败！';
            $this -> writeSyncLog($msg);
            $this -> httpHeader($msg);
        }
        $fromDate = strtotime($fromDate);
        $toDate = strtotime($toDate) + 86400;
        if($toDate - $fromDate > 2592000){
            $msg = "查询时间段请不要超过30天！";
            $this -> writeSyncLog($msg);
            $this -> httpHeader($msg);
        } else if($toDate - $fromDate < 0) {
            $msg = "非法参数，日期错误！";
            $this -> writeSyncLog($msg);
            $this -> httpHeader($msg);
        }
        
        //$_order = new Shop_Models_DB_Order();
        $information = new Shop_Models_API_Information();
        $where = array('from_date' => $fromDate, 'to_date' => $toDate);
        $temp = $information -> getProfitSync($where, $unionID);
        $result = '';
        if ($temp) {
            $this -> _fliterProductData($temp,$unionID);
            $k = 0;
            foreach ($temp as $info) {
                if($info){
                    switch($unionID){
                        case 9096: //www.linktech.cn  linktechCPA
                            $lkt_add_time = date('His', $info['order_add_time']);
							if($info['separate_type']=='0'){
								$lkt_ostat='100';
							}elseif($info['separate_type']=='1'){
								$lkt_ostat='200';
							}elseif($info['separate_type']=='2'){
								$lkt_ostat='300';
							}
							$cps_ccd = '0';
							$cpa_ccd = '1';

							if($info['proportion']=='1'){
                                $cps_ccd='qq_login';
                                $info['user_param']= $info['user_param'].str_replace('Tencent:','',$info['order_user_name']);
							}

							if(in_array($info['product_id'],array(595)) && $info['sale_price']=='0.00' ){
								 $result ='2'."\t". $lkt_add_time."\t".$info['user_param']."\t".$info['order_sn']."\t".$info['product_id']."\t"
                                    .$info['order_user_id']."\t".$info['number']."\t".$info['sale_price']."\t".$cpa_ccd."\t".$lkt_ostat."\t\n";
							}else{
							     $result ='2'."\t". $lkt_add_time."\t".$info['user_param']."\t".$info['order_sn']."\t".$info['product_id']."\t"
                                    .$info['order_user_id']."\t".$info['number']."\t".$info['sale_price']."\t".$cps_ccd."\t".$lkt_ostat."\t\n";
							
							}
                            unset($lkt_ostat);
                            break;
                      
                        case 9146: //51fanli
                            $order_add_time = date('Y-m-d H:i:s', $info['order_add_time']);
                            $lkt_ostat = ($info['order_status']==1 || $info['order_status']==2) ? 'false' : 'true';
                            $result = $lkt_add_time."\t".$info['user_param']."\t".$info['order_sn']."\t".$info['order_user_id']."\t"
                                    .$info['product_id']."\t".$info['goods_name']."\t".$info['user_name']."\t".$info['number']."\t"
                                    .$info['sale_price']."\t".$info['cat_id']."\t".$lkt_ostat."\t\n";
                       
                            unset($lkt_ostat);
                            break;
                        
                        case 9133: //一起发
                            $order_add_time = date('Y-m-d H:i:s', $info['order_add_time']);
							$order_add_time  = mb_convert_encoding($order_add_time, 'GBK', 'UTF-8'); 
                            $info['ct'] = '' ;

                            if ( $info['is_fav'] == 1 ) $info['status'] = 4;
                            else if ( in_array($info['order_status'], array(1,2,3)) ) $info['status'] = 3;
                            else if ( $info['is_send'] == 1)    $info['status'] = 2;
                            else if ( $info['status_pay'] == 2)    $info['status'] = 1;
                            else if ( $info['status_pay'] == 0 && $info['order_status'] == 0)   $info['status'] = 0;

                            $result = $info['user_param']."|"
									.  $order_add_time.'|'
									.  $info['order_sn'].'|'
									.  $info['product_sn'].'|'
									.  $info['goods_name'].'|'
									.  $info['ct'].'|'
                                    .  $info['number'].'|'
                                    .  $info['sale_price'].'|'
                                    .  $info['status'].'|'
                                    .  $info['status_pay'].'|'
                                    .  $info['price_logistic'].'|'
									.  $info['pay_name'].'|'
									.  $info['price_goods']." \n";
                            break;
                      
                        case 9143:  //chanet
                            $order_add_time = date('Y-m-d H:i:s', $info['order_add_time']);
                            $sid = $info['user_param'];
                            
                            $pay_type = substr($info['pay_type'], 0, 6);
                            switch($pay_type){
                                case 'cod' :
                                    $info['pay_type'] = 1;
                                    break;
                                case 'alipay' :
                                    $info['pay_type'] = 2;
                                    break;
                                case 'tenpay' :
                                    $info['pay_type'] = 3;
                                    break;
                                default :
                                    $info['pay_type'] = 9;
                                    break;
                            }
                            if ( $info['is_fav'] == 1 ) $info['status'] = 6;
                            else if ( in_array($info['order_status'], array(1,3)) ) $info['status'] = 5;
                            else if ( $info['is_send'] == 1)    $info['status'] = 2;
                            else if ( $info['status_pay'] == 2)    $info['status'] = 1;
                            else if ( $info['status_pay'] == 0)    $info['status'] = 0;
							
                            
                            if($info['status_pay'] == 0) $status_pay = 0;
                            else if ( $info['status_pay'] == 1)    $status_pay = 2;
                            else if ( $info['status_pay'] == 2)    $status_pay = 1;

                            $result = $order_add_time."\t".$sid."\t".$info['order_sn']."\t"."GOODS"."\t".$info['number']."\t"
                                    . $info['sale_price']."\t".$info['goods_name']."\t".$info['pay_type']."\t".$info['status']."\t".$status_pay."\n";
                            
                            break;
                        case 3519:  //nfanli
                            if ( $info['is_fav'] != 1 ) {   //只处理完成的订单
                                $result = '';
                                $order_sn = $info['order_sn'];
                                $order_info[$order_sn][] = array('order_add_time'   => $info['order_add_time'],
                                                                 'user_param'       => $info['user_param'],
                                                                 'product_sn'       => $info['product_sn'],
                                                                 'number'           => $info['number'],
                                                                 'sale_price'       => $info['sale_price'],
                                                                );
                            }
                            break;
                       case 3763:  //duomai
                            $order_add_time = date('Y-m-d H:i:s', $info['order_add_time']);
                            $sid = $info['user_param'];
                            $info['proportion'] = $this->getUnionGoodsProportion('3763',$info['goods_id']); 
                            if ( $info['is_fav'] == 1 ) $info['status'] = 6;
                            else if ( in_array($info['order_status'], array(1,3)) ) $info['status'] = 5;
                            else if ( $info['is_send'] == 1)    $info['status'] = 2;
                            else if ( $info['status_pay'] == 2)    $info['status'] = 1;
                            else if ( $info['status_pay'] == 0)    $info['status'] = 0;
                            $result = $order_add_time."\t".$sid."\t".$info['order_sn']."\t".$info['number']."\t"
                                    . $info['sale_price']."\t".$info['cat_name']."\t".$info['product_sn']."\t".$info['goods_name']."\t".$info['status']."\t".$info['proportion']."\n";
                            
                            break;
                       case 43213:  //360
                            $result = '';
                            $order_sn = $info['order_sn'];
                            $order_info[$order_sn][] = array('order_add_time'   => $info['order_add_time'],
                                                             'code_param'       => $info['code_param'],
                                                             'product_id'       => $info['product_id'],
                                                             'goods_id'       => $info['goods_id'],
                                                             'product_sn'       => $info['product_sn'],
                                                             'goods_name'       => $info['goods_name'],
                                                             'cat_id'           => $info['cat_id'],
                                                             'cat_name'         => $info['cat_name'],
                                                             'number'           => $info['number'],
                                                             'sale_price'       => $info['sale_price'],
                                                             'order_status'     => $info['order_status'],
                                                             'order_status_logistic' => $info['order_status_logistic'],
                                                            );
                            break;
                       case 61044:  //智推网
                            $result = '';
                            if ($info['order_status'] != 0)  continue;
                            
                            $order_sn = $info['order_sn'];
                            if (!$this -> goodsAPI) {
                                $this -> goodsAPI = new Shop_Models_DB_Goods();
                            }
                            $cat = $this -> goodsAPI -> getGoodsCat($info['cat_id']);
                            $info['rate'] = 16;
                            if ($cat['cat_path']) {
                                $tempArr = explode(',', $cat['cat_path']);
                                if (in_array(2, $tempArr)) {    //保健食品
                                    $info['rate'] = 6;
                                }
                            }
                            $result = $info['user_param'].'||'.$order_sn.'||'.$info['product_sn'].'||'.$info['sale_price'].'||'.$info['number'].'||'.date('YmdHis', $info['order_add_time']).'||'.$info['rate'].'||'.'1';
                            break;
                       
                       case 70537:  //上海导购(1jiankang)
                       case 70554:  //上海导购()
                            $result = '';
                            $order_info[$info['order_sn']][] = $info;
                            break;
                            
                       default:
                            break;
                    }
                }
                echo $result;
                if ($k != 0 && $k%100 == 0) {
                    ob_flush();
                    flush();
                }
                $k++;
            }
            if ( $unionID == 3519 ) {     //nfanli
                $m_id = '1jiankang';
                $k = 1;
                foreach ( $order_info as $ordersn => $info ) {
                    $product_sn = '';
                    $number = '';
                    $sale_price = '';
                    for ( $j = 0; $j < count($info); $j++ ) {
                        $product_sn .= $info[$j]['product_sn'].",";
                        $number .= $info[$j]['number'].",";
                        $sale_price .= $info[$j]['sale_price'].",";
                    }
                    $product_sn = substr( $product_sn, 0, -1 );
                    $number = substr( $number, 0, -1 );
                    $sale_price = substr( $sale_price, 0, -1 );
                    
                    $result = $info[0]['order_add_time'].'|'.$ordersn.'|'.$info[0]['user_param'].'|'
                            . $product_sn.'|'.'|'.$number.'|'.$sale_price.'|'.'0'.'|'.$m_id.'|'.'|_|';
                    echo $result;
                    
                    if ($k != 0 && $k%100 == 0) {
                        ob_flush();
                        flush();
                    }
                    $k++;
                }
            }
            if ( $unionID == 43213 ) {      //360
                $result = '<?xml version="1.0" encoding="utf-8"?>
                            <orders>';
                foreach ( $order_info as $ordersn => $info ) {
                    $info[0]['code_param'] = unserialize(str_replace('\"', '"', $info[0]['code_param']));
                    
                    $amount = 0;$total_proportion_amount = 0;
                    $p_info = '';$commission = '';$minProportion = 100;
                    foreach ( $info as $goods ) {
                        $amount += $goods['sale_price'] * $goods['number'];
                        $p_info .= $goods['cat_id'].','.$goods['goods_name'].','.$goods['product_sn'].','.$goods['sale_price'].','.$goods['number'].','.$goods['cat_name'].',http%3A%2F%2Fwww.1jiankang.com%2Fgoods-'.$goods['goods_id'].'.html'.'|';
                        $proportion = $this -> _db -> getOneGoodsProportion($unionID, $goods['goods_id']);
                        if ( !$proportion ) $proportion = 0;
                        if ($proportion < $minProportion)   $minProportion = $proportion;
                        $proportion_amount = round($goods['sale_price'] * $goods['number'] * $proportion / 100, 2);
                        $total_proportion_amount += $proportion_amount;
                        $commission .= $goods['cat_id'].','.$proportion.'%,'.$proportion_amount.','.$goods['sale_price'].','.$goods['number'].'|';
                    }
                    $p_info = substr( $p_info, 0, -1 );
                    
                    if ($info[0]['order_status'] == 1 || $info[0]['order_status'] == 2) $status = 6;
                    else {
                        if ( $info[0]['order_status_logistic'] == 0 )   $status = 1;
                        else if ($info[0]['order_status_logistic'] == 1 || $info[0]['order_status_logistic'] == 2)  $status = 2;
                        else if ($info[0]['order_status_logistic'] == 3 )   $status = 3;
                        else if ($info[0]['order_status_logistic'] == 4 )   $status = 5;
                        else if ($info[0]['order_status_logistic'] == 5 )   $status = 6;
                    }
                    
                    $coupon = $this -> _orderFavourablePrice[$ordersn];
                    if ( $coupon )  $coupon *= -1;
                    else    $coupon = 0;
                    $amount = $amount - $coupon;
                    
                    $total_proportion_amount -= round($coupon * $minProportion / 100, 2);
                    $commission .= round($coupon * $minProportion / 100, 2);
                    
                    $result .= "<order>
                                  <bid>43213</bid>
                                  <qid>{$info[0]['code_param']['qid']}</qid>
                                  <qihoo_id>{$info[0]['code_param']['qihoo_id']}</qihoo_id>
                                  <ext>{$info[0]['code_param']['ext']}</ext>
                                  <order_id>{$ordersn}</order_id>
                                  <order_time>".date('Y-m-d H:i:s', $info[0]['order_add_time'])."</order_time>
                                  <order_updtime>".date('Y-m-d H:i:s', $info[0]['order_add_time'])."</order_updtime>
                                  <server_price>0</server_price>
                                  <total_price>{$amount}</total_price>
                                  <coupon>{$coupon}</coupon>
                                  <total_comm>{$total_proportion_amount}</total_comm>
                                  <commission>{$commission}</commission>
                                  <p_info>{$p_info}</p_info>
                                  <status>{$status}</status>
                                </order>";
                }
                $result .= '</orders>';
                
                echo $result;
            }
            if ($unionID == 70537 || $unionID == 70554) {      //上海导购
                $goodsAPI = new Shop_Models_API_Goods();
                $goodsDB = new Shop_Models_DB_Goods();
                $commissionInfo[1] = 0.2;
                $commissionInfo[2] = 0.06;
                
                if ($unionID == 70537) {
                    $shop_no = '1jiankang';
                    $shop_key = '0dcb2054fac4f29e';
                }
                else {
                    $shop_no = '99nc';
                    $shop_key = 'fbf4627a8784d6ec';
                }
                $result = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
                           <shunion_data version=\"1.0\" >";
                if ($order_info) {
                    foreach ($order_info as $order_sn => $data) {
                        $order = $data[0];
                        $pay_type = $order['pay_type'] == 'cod' ? 2 : 1;
                        if ($pay_type == 1 && in_array($order['order_status_logistic'], array(2,3,4,6))) {
                            $is_pay = 1;
                        }
                        else if ($pay_type == 2 && in_array($order['order_status_logistic'], array(4))) {
                            $is_pay = 1;
                        }
                        else {
                            $is_pay = 0;
                        }
                        if ($order['order_status'] == 0) {
                            if ($order['order_status_logistic'] == 0)   $order_status = 1;
                            else if ($order['order_status_logistic'] == 1 || $order['order_status_logistic'] == 2)   $order_status = 3;
                            else if ($order['order_status_logistic'] == 3 || $order['order_status_logistic'] == 6)   $order_status = 4;
                            else if ($order['order_status_logistic'] == 4)   $order_status = 5;
                            else if ($order['order_status_logistic'] == 5)   $order_status = 7;
                        }
                        else {
                            $order_status = -1;
                        }
                        $params = explode('|', unserialize($order['code_param']));
                        $pass_code = md5(strtolower($order['order_sn'].$shop_no.$params[1].$shop_key));
                        $result .= "<order>
                                    <order_time>".date('Y-m-d H:i:s', $order['order_add_time'])."</order_time>
                                    <order_no>{$order['order_sn']}</order_no>
                                    <shop_no>{$shop_no}</shop_no>
                                    <total_price>{$order['order_price_goods']}</total_price>
                                    <total_qty>%total_qty%</total_qty>
                                    <u_id>{$params[1]}</u_id>
                                    <username>{$order['order_user_name']}</username>
                                    <is_pay>{$is_pay}</is_pay>
                                    <pay_type>{$pay_type}</pay_type>
                                    <order_status>{$order_status}</order_status>
                                    <deli_name></deli_name>
                                    <deli_no></deli_no>
                                    <tracking_code>{$params[2]}</tracking_code>
                                    <pass_code>{$pass_code}</pass_code>
                            	    <commission>%commission%</commission>
                                    <products_all>";
                        $total = 0;$commission = 0;
                        foreach ($data as $goods) {
                            $goodsInfo = $goodsDB -> getGoodsProductInfo("and t2.product_id = {$goods['product_id']}", 't1.goods_id');
                            if ($goodsAPI -> isRootCat($goods['product_id'], 1)) {
                                $catType = 1;
                            }
                            else {
                                $catType = 2;
                            }
                            $product_comm = round($goods['sale_price'] * $goods['number'] * $commissionInfo[$catType], 2);
                            $result .= "<product>
						                <product_id>{$goods['product_id']}</product_id>
						                <product_name>{$goods['goods_name']}</product_name>
						                <product_url>http://".$_SERVER['HTTP_HOST']."/goods-{$goodsInfo['goods_id']}.html</product_url>
						                <product_qty>{$goods['number']}</product_qty>
						                <product_price>{$goods['sale_price']}</product_price>
						                <product_comm>{$product_comm}</product_comm>
						                <comm_no>A</comm_no>
						                </product>";
						    $total += $goods['number'];
						    $commission += $product_comm;
                        }
                        $result = str_replace('%total_qty%', $total, str_replace('%commission%', $commission, $result));
                        $result .= "</products_all>
                                    <coupons_all>
                                    <coupon>
                                    <coupon_no></coupon_no>
                                    <coupon_qty></coupon_qty>
                                    <coupon_price></coupon_price>
                                    <comm_no></comm_no>
                                    </coupon>
                                    </coupons_all>
                                    </order>";
                    }
                }
                $result .= "</shunion_data>";
                
                echo $result;
            }
        }
    }
    /**
     * 获得用户的真实IP地址
     *
     * @access  public
     * @return  string
     */
    public function _real_ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    /**
     * 获得联盟商品分成比例
     *
     * @access  public
     * @return  string
     */
    public function getUnionGoodsProportion($union_id , $goods_id)
    {
        if($union_id > 0 && $goods_id > 0){
            return $this -> _db -> getOneGoodsProportion($union_id , $goods_id);
        }
    }
}