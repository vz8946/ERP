<?php

class Custom_Model_Offers_GroupExclusive extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '站外渠道价格专享(组合商品)';

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
    	parent::__construct();
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
        //if (!$_GET['f'] && !$_GET['u']) return $datas;
        if ($this -> _uid && $this -> _uid == $config['config']['uid'] && $config['config']['allGoods'] && $datas) {
            if ( $config['config']['aid'] ) {
                if ( $this -> _aid != $config['config']['aid'] ) {
                    return $datas;   
                }
            }
            
    		foreach ($config['config']['allGoods'] as $value)
    		{
    			foreach ($datas as $key => $data)
    			{
    				if (!$data['group_id']) continue;
                    
    				$discountConfig = $this -> getSelectGroupGoodsConfig($data['group_id'], $value);
    				
    		    	if (($discountConfig > 0 || $discountConfig =='0.00')) {
    		    	    
    		    	    if ( $discountConfig >= $data['group_price'] )    continue;
    		    	    
    		    	    /*
    		    	    if ( $this -> _cart['goodsInfo'][$data['goods_id']] )  {
    		    	        if ( $config['config']['limit_num'] ) {
    		    	            if ( $config['config']['limit_num'] <= $this -> _cart['goodsInfo'][$data['goods_id']] ) {
    		    	                return $datas;
    		    	            }
    		    	        }
    		    	    }
    		    	    */
    		    	    
    		    	    $datas[$key]['org_price'] = $datas[$key]['org_price'] ? $datas[$key]['org_price'] : $data['group_price'];
    		    		$datas[$key]['offers_type'] = $config['offers_type'];
    		    		$datas[$key]['group_price'] = sprintf('%.2f', $discountConfig);
    		    		$datas[$key]['price'] = sprintf('%.2f', $discountConfig);
                        $datas[$key]['excluisv_name'] = $this -> _excluName;
                        $datas[$key]['show_name'] = $config['config']['show_name'];
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
    public function responseToCart($config, $data = null)
    {
    	if ($this -> _uid && $this -> _uid == $config['config']['uid'] && $config['config']['allGoods'] && $data) {
    	    if ( $config['config']['aid'] ) {
                if ( $this -> _aid != $config['config']['aid'] ) {
                    return $data;   
                }
            }
            
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
    		$data['org_amount'] = $data['amount'];
    		foreach ($config['config']['allGoods'] as $value) {
    		    $discountConfig = $this -> getSelectGroupGoodsConfig($data['group_id'], $value);
    		    if (($discountConfig > 0 || $discountConfig =='0.00')) {
    		        if ( $discountConfig >= $data['group_price'] )    continue;
    		            
    		        if ($needLogin) {
    		    	    $data['needLogin'] = $needLogin;
    		    	    break;
    		        }
    		        
                    $data['org_price'] = $data['group_price'];
                    $data['group_price'] = $discountConfig;
                    $data['amount'] = $data['group_price'] * $data['number'];
    		            
    		        $offerProduct['offers_name'] = $config['offers_name'];
    		    	$offerProduct['offers_type'] = $config['offers_type'];
					$offerProduct['offers_id'] = $config['offers_id'];
    		    	$offerProduct['use_coupon'] = $config['use_coupon'];
    		    	$offerProduct['price'] = sprintf('%.2f', $discountConfig - $data['org_price']);
	                $offerProduct['parent_pid'] = $data['group_id'];
	                $offerProduct['number'] = $data['number'];
	                break;
    		    }
    		}
    	    
    	    $offerProduct && $data['offers'][$config['offers_id']] = $offerProduct;
    	}
        
        return $data;
    }
}
