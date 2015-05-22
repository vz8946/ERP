<?php

class Custom_Model_Offers_Fixed extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '商品特价';
    
    /**
     * 处理并输出内容
     *
     * @param    array    $datas
     * @param    array    $config
     * @return   string
     */
    public function responseToView($config, $datas = null)
    {
    	if ($config['config']['only_new_member']) {
    	    $auth_api = Shop_Models_API_Auth :: getInstance();
    	    $user = $auth_api -> getAuth();
    	    if (!$user['is_new_member'] ) {
    	        return $datas;
    	    }
    	}

    	if ($config['config']['allDiscount'] && $datas) {
    		foreach ($datas as $key => $data) {
    	    	$discountConfig = $this -> getSelectGoodsConfig($datas[$key]['goods_id'], $datas[$key]['cat_path'], $config['config']['allDiscount']);
    		    
    		    if ($discountConfig > 0) {
    		        if ( $discountConfig >= $data['price'] )    break;
    		        
    		    	$datas[$key]['org_price'] = $datas[$key]['org_price'] ? $datas[$key]['org_price'] : $data['price'];
    		    	$datas[$key]['offers_type'] = $config['offers_type'];
    		    	$datas[$key]['price'] = sprintf('%.2f', $discountConfig);
                    $config['to_date'] && $datas[$key]['fixed_to_date'] = date('m/d/Y H:i:s',strtotime($config['to_date'])); 
                    if ($config['config']['only_new_member']) {
                        $datas[$key]['offers_message'] = 'only_new_member';
                    }
    		    }
    	    }
    	}
    	
    	if ($config['config']['allGroupGoods'] && $datas) {
    	    foreach ($datas as $key => $data) {
    	    	$discountConfig = $this -> getSelectGroupGoodsConfig($data['group_id'], $config['config']['allGroupGoods']);
    	    	if ($discountConfig > 0) {
    		        if ( $discountConfig >= $data['group_price'] )    break;
    		        
    		        $datas[$key]['org_price'] = $datas[$key]['org_price'] ? $datas[$key]['org_price'] : $data['group_price'];
    		    	$datas[$key]['offers_type'] = $config['offers_type'];
    		    	$datas[$key]['group_price'] = sprintf('%.2f', $discountConfig);
                    $config['to_date'] && $datas[$key]['fixed_to_date'] = date('m/d/Y H:i:s',strtotime($config['to_date'])); 
                    if ($config['config']['only_new_member']) {
                        $datas[$key]['offers_message'] = 'only_new_member';
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
    	if ($config['config']['only_new_member']) {
    	    $auth_api = Shop_Models_API_Auth :: getInstance();
    	    $user = $auth_api -> getAuth();
    	    if (!$user['is_new_member'] ) {
    	        return $datas;
    	    }
    	}
    	
    	$offers = '';$alt_data = '';
    	if ($config['config']['allDiscount'] && $datas['data']) {
    		$datas['org_amount'] = $datas['amount'];
    		foreach ($datas['data'] as $key => $data) {
    	    	$discountConfig = $this -> getSelectGoodsConfig($datas['data'][$key]['goods_id'], $datas['data'][$key]['cat_path'], $config['config']['allDiscount']);
    		    
    		    $alt_number = 0;
    		    if ($discountConfig > 0) {
    		        
    		        if ( $discountConfig >= $data['price'] )    break;
    		        
    		        if ($config['config']['number']) {
    		            if ($datas['data'][$key]['number'] > $config['config']['number']) {
    		                $alt_number = $datas['data'][$key]['number'] - $config['config']['number'];
    		                $datas['data'][$key]['number'] = $config['config']['number'];
    		            }
    		        }
    		        if ($alt_number > 0) {
    		            $tmp_data = $datas['data'][$key];
    		            $tmp_data['number'] = $alt_number;
    		            $tmp_data['amount'] = $tmp_data['price'] * $alt_number;
    		            $tmp_data['suffix'] = 0;
    		            $alt_data[] = $tmp_data;
    		        }
    		    	$datas['data'][$key]['org_price'] = $datas['data'][$key]['org_price'] ? $datas['data'][$key]['org_price'] : $data['price'];
    		    	$datas['amount'] -= ($datas['data'][$key]['price'] - $discountConfig) * $datas['data'][$key]['number'];
                    $datas['goods_amount'] -= ($datas['data'][$key]['price'] - $discountConfig) * $datas['data'][$key]['number'];
                    $datas['data'][$key]['amount'] = $discountConfig * $datas['data'][$key]['number'];
    		    	$datas['data'][$key]['price'] = sprintf('%.2f', $discountConfig);
    		    	$datas['data'][$key]['suffix'] = 1;
    		    	$datas['data'][$key]['price_before_discount'] = ''; //特价商品不会参与之前的折扣
    		    	$offerProduct['offers_name'] = $config['offers_name'];
    		    	$offerProduct['offers_id'] = $config['offers_id'];
    		    	$offerProduct['offers_type'] = $config['offers_type'];
    		    	$offerProduct['use_coupon'] = $config['use_coupon'];
    		    	$offerProduct['price'] = $datas['data'][$key]['price'] - $data['price'];
    		    	$offerProduct['number'] = $datas['data'][$key]['number'];
	                $offerProduct['parent_pid'] = $datas['data'][$key]['product_id'];
	                $offers[] = $offerProduct;
	                unset($offerProduct);
    		    }
    	    }
    	    
    	    if ($alt_data) {
    	        $datas['data'] = array_merge($datas['data'], $alt_data);
    	    }
    	    
    	    $offers && $datas['offers'][$config['offers_id']] = $offers;
    	}
    	
    	$offers = '';$alt_data = '';
    	if ($config['config']['allGroupGoods'] && $datas['group_goods_summary']) {
            $datas['org_amount'] = $datas['amount'];
    		foreach ($datas['group_goods_summary'] as $key => $data) {
    	    	$discountConfig = $this -> getSelectGroupGoodsConfig($data['group_id'], $config['config']['allGroupGoods']);
    	    	$alt_number = 0;
    		    if ($discountConfig > 0) {
    	    	    if ( $discountConfig >= $data['group_price'] )    break;
    	    	    if ($config['config']['number']) {
    		            if ($data['number'] > $config['config']['number']) {
    		                $alt_number = $data['number'] - $config['config']['number'];
    		                $datas['group_goods_summary'][$key]['number'] = $config['config']['number'];
    		            }
    		        }
    		        if ($alt_number > 0) {
    		            $tmp_data = $datas['group_goods_summary'][$key];
    		            $tmp_data['number'] = $alt_number;
    		            $tmp_data['amount'] = $tmp_data['group_price'] * $alt_number;
    		            $tmp_data['suffix'] = 0;
    		            $alt_data[] = $tmp_data;
    		        }
    		    	$datas['group_goods_summary'][$key]['org_price'] = $datas['group_goods_summary'][$key]['org_price'] ? $datas['group_goods_summary'][$key]['org_price'] : $data['group_price'];
    		    	$datas['amount'] -= ($datas['group_goods_summary'][$key]['group_price'] - $discountConfig) * $datas['group_goods_summary'][$key]['number'];
                    $datas['goods_amount'] -= ($datas['group_goods_summary'][$key]['group_price'] - $discountConfig) * $datas['group_goods_summary'][$key]['number'];
    		    	$datas['group_goods_summary'][$key]['group_price'] = sprintf('%.2f', $discountConfig);
    		    	$datas['group_goods_summary'][$key]['suffix'] = 1;
    		    	$datas['group_goods_summary'][$key]['price_before_discount'] = ''; //特价商品不会参与之前的折扣
    		    	$offerProduct['offers_name'] = $config['offers_name'];
    		    	$offerProduct['offers_id'] = $config['offers_id'];
    		    	$offerProduct['offers_type'] = $config['offers_type'];
    		    	$offerProduct['use_coupon'] = $config['use_coupon'];
    		    	$offerProduct['price'] = $datas['group_goods_summary'][$key]['group_price'] - $data['group_price'];
    		    	$offerProduct['number'] = $datas['group_goods_summary'][$key]['number'];
	                $offerProduct['parent_pid'] = $datas['group_goods_summary'][$key]['group_id'];
	                $offerProduct['is_group'] = 1;
	                $offers[] = $offerProduct;
	                unset($offerProduct);
	                
	                $pattern = array('/<span id="groupbox_'.$data['group_id'].'_1">(.*)<\/span>/',
	                                '/<span id="groupbox_'.$data['group_id'].'_2">(.*)<\/span>/',
	                                '/<span id="groupbox_'.$data['group_id'].'_3">(.*)<\/span>/',
	                               );
	                $replace = array($datas['group_goods_summary'][$key]['group_price'],
	                                 $datas['group_goods_summary'][$key]['group_price'] * $datas['group_goods_summary'][$key]['number'],
	                                 $datas['group_goods_summary'][$key]['group_price'] * $datas['group_goods_summary'][$key]['number'],
	                                );
	                $datas['other']['group_goods'] = preg_replace($pattern, $replace, $datas['other']['group_goods']);
    	    	}
    	    }
    	    
    	    if ($alt_data) {
    	        $datas['group_goods_summary'] = array_merge($datas['group_goods_summary'], $alt_data);
    	    }
    	    
    	    $offers && $datas['offers'][$config['offers_id']] = $offers;
    	}
        
        return $datas;
    }
}
