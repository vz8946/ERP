<?php

class Custom_Model_Offers_PriceExclusive extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '商品站内特价专享';

    /**
     * 专享价格的对应名称
     * @var   string
     */
    protected $_excluName = '';
    
    /**
     * 对象初始化
     *
     * @return void
     */
    public function __construct()
    {
    	$activity = new Shop_Models_API_Activity();
    	$this -> _actId = $activity -> getActivityCookie();
        $this -> _actId = $this -> _actId ? $this -> _actId : (isset($_GET['a']) ? intval($_GET['a']) : null);
    	$this -> _cart = $activity -> getCartInfo();
    }
    
    /**
     * 处理并输出内容
     *
     * @param    array    $datas
     * @param    array    $config
     * @return   string
     */
    public function responseToView($config, $datas = null)
    {
        if ($this -> _actId && $this -> _actId == $config['config']['aid'] && $config['config']['allGoods'] && $datas) {
            
    		foreach ($config['config']['allGoods'] as $value)
    		{
				$goodsconfig=$this ->parseGoodsConfig($value);
				
				$has_goods='0';
				/*
				if ($goodsconfig['goodsDiscount']) {
    				foreach ($goodsconfig['goodsDiscount'] as $key => $data){
    				     if ( $this -> _cart['goodsInfo'][$key] >= $config['config']['limit_num'] ) {
    						$has_goods='1';
    					 }
    				}
				}
				*/
				
    			foreach ($datas as $key => $data)
    			{
    				$discountConfig = $this -> getSelectGoodsConfig($datas[$key]['goods_id'], $datas[$key]['cat_path'], $value);
    		    	if (($discountConfig > 0 || $discountConfig =='0.00') && $has_goods=='0') {
    		    	    
    		    	    if ( $discountConfig >= $data['price'] )    break;
    		    	    
    		    	    $datas[$key]['org_price'] = $datas[$key]['org_price'] ? $datas[$key]['org_price'] : $data['price'];
    		    		$datas[$key]['offers_type'] = $config['offers_type'];
    		    		$datas[$key]['price'] = sprintf('%.2f', $discountConfig);
                        $datas[$key]['excluisv_name'] = $this -> _excluName;
    		    	} else if (($discountConfig > 0 || $discountConfig =='0.00') && $has_goods=='1') {
                        $datas[$key]['offers_type'] = $config['offers_type'];
                    }
    			}
    		}
    	}
        return $datas;
    }
    
    /**
     * 处理并输出内容
     *
     * @param    mixed    $datas
     * @param    array    $config
     * @return   string
     */
    public function responseToCart($config, $datas = null)
    {
    	if ($this -> _actId && $this -> _actId == $config['config']['aid'] && $config['config']['allGoods'] && $datas['data']) {
    	    
    	    if ($config['config']['order_num']) {
                //必须登录后才能判断用户订单数量
                $auth_api = Shop_Models_API_Auth :: getInstance();
                $user = $auth_api -> getAuth();
                if ($user) {
                    $cart_api = new Shop_Models_API_Cart();
                    $count = $cart_api -> getOrderCountByUserOffer($user['user_id'], $config['offers_id']);
                    if ($count >= $config['config']['order_num'])   return $datas;
                }
                else {
                    $needLogin = 1;
                }
            }
    		$datas['org_amount'] = $datas['amount'];
    		
    		foreach ($config['config']['allGoods'] as $value) {
    			$hitNumber = 0;
    			foreach ($datas['data'] as $key => $data) {
    				$discountConfig = $this -> getSelectGoodsConfig($datas['data'][$key]['goods_id'], $datas['data'][$key]['cat_path'], $value);
    		        if (($discountConfig > 0 || $discountConfig =='0.00')) {
    		            if ( $discountConfig >= $data['price'] )    break;
    		            
    		            if ($needLogin) {
    		    	        $datas['data'][$key]['needLogin'] = $needLogin;
    		    	        break;
    		    	    }
    		    	    
    		            if ( $this -> _cart['productInfo'][$datas['data'][$key]['product_id']] > 0 ) {
        		            $goods_id = $datas['data'][$key]['goods_id'];
        		            if ($config['config']['by_group'] && $hitNumber >= $config['config']['limit_num']) {
        		                break;
        		            }
        		            
        		            if ( $this -> _cart['goodsInfo'][$goods_id] > $config['config']['limit_num'] ) {
                                $discount_num = $config['config']['limit_num'];
                                $common_num = $this -> _cart['goodsInfo'][$goods_id] - $config['config']['limit_num'];
                            }
                            else {
                                $discount_num = $this -> _cart['goodsInfo'][$goods_id];
                                $common_num = 0;
                            }
                            
                            $datas['data'][$key]['number'] -= $discount_num;
                            $datas['data'][$key]['org_price'] = $data['org_price'];
                            $datas['data'][$key]['amount'] -= $datas['data'][$key]['price'] * $datas['data'][$key]['number'];
                            $datas['data'][$key]['suffix'] = '0';
    		        		$exclusive[$key] = $datas['data'][$key];
    		        		$exclusive[$key]['number'] = $discount_num;
    		    	    	$exclusive[$key]['amount'] = sprintf('%.2f', $discountConfig * $discount_num);
    		    	    	$exclusive[$key]['org_price'] = $data['org_price'];
    		    	    	$exclusive[$key]['price'] = sprintf('%.2f', $discountConfig);
    		    	    	$exclusive[$key]['suffix'] = '1';
                        }
                        else {
                            $datas['data'][$key]['org_price'] = $data['org_price'];
    		    	    	$datas['data'][$key]['price'] = sprintf('%.2f', $discountConfig);
                        }
    		        	
    		    	    $datas['amount'] -= ($datas['data'][$key]['price'] - $discountConfig) * $discount_num;
                        $datas['goods_amount'] -= ($datas['data'][$key]['price'] - $discountConfig) * $discount_num;
                        if ( $datas['data'][$key]['number'] == 0 ) {
    		    	        unset($datas['data'][$key]);
    		    	    }
    		    	    $offerProduct['offers_name'] = $config['offers_name'];
    		    	    $offerProduct['offers_type'] = $config['offers_type'];
						$offerProduct['offers_id'] = $config['offers_id'];
    		    	    $offerProduct['use_coupon'] = $config['use_coupon'];
    		    	    $offerProduct['price'] = sprintf('%.2f', $discountConfig - $data['price']);
	                    $offerProduct['parent_pid'] = $datas['data'][$key]['product_id'];
	                    $offerProduct['number'] = $discount_num;
	                    $offersData[] = $offerProduct;
	                    unset($offerProduct);
	                    
	                    $hitNumber++;
	                    
	                    continue;
    		        }
    			}
    		}
    		
    	    if (is_array($exclusive)) {
    	    	$datas['data'] = array_merge($datas['data'], $exclusive);
    	    }
    	    
    	    if (is_array($datas['offers'])) {
    	    	foreach ($datas['offers'] as $key => $value)
    	    	{
    	    		if ($value['offers_type'] == 'fixed') {
    	    			unset($datas['offers'][$key]);
    	    		}
    	    	}
    	    }
    	    $offersData && $datas['offers'][$config['offers_id']] = $offersData;
    	}
        return $datas;
    }
}
