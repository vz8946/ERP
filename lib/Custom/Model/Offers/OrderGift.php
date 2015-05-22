<?php

class Custom_Model_Offers_OrderGift extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '订单买赠';
    protected $offers = null;
    /**
     * 对象初始化
     *
     * @return void
     */
    public function __construct()
    {
    	parent::__construct();
    	$this -> offers = null;
    }
    
    /**
     * 处理并输出内容
     *
     * @param    array    $datas
     * @param    array    $config
     * @return   string
     */
    public function responseToCart($config, $datas = null)
    {
        //setcookie('order_gift', '');
        if ( $config['config']['uid'] ) {
            if ( $this -> _uid != $config['config']['uid'] ) {
                return $datas;
            }
        }
        if ( $config['config']['aid'] ) {
            if ( $this -> _aid != $config['config']['aid'] ) {
                return $datas;   
            }
        }
        if ($config['config']['only_new_member']) {
    	    $auth_api = Shop_Models_API_Auth :: getInstance();
    	    $user = $auth_api -> getAuth();
    	    if (!$user['is_new_member'] ) {
    	        return $datas;
    	    }
    	}
    	
        $this -> imgBaseUrl = Zend_Registry::get('config') -> view -> imgBaseUrl;
        
        $this -> _goods = new Shop_Models_API_Goods();
        $this -> _offers = new Shop_Models_API_offers();      
        
    	if (!$this -> amount) {
    	    $cards = $_SESSION['Card']['cardCertification']; //取抵用券信息    	    
    	    if ($cards) {
    	        foreach ( $cards as $card ) {
    	            $card_price = $card['card_price'];
    	            break;
    	        }
    	    }
            $this -> amount = $datas['amount'] - $card_price;
        }
   
        $expire = ($config['to_date']) ? strtotime($config['to_date']) + 86400 : time() + 365 * 86400;
    	if ( ($this -> amount >= $config['config']['min_price']) && (!$config['config']['max_price'] || $this -> amount <= $config['config']['max_price']) && $this -> inSpecialScope($config['config'], $datas) ) {
            $this -> offers = '';            
            $datas['other']['buyGift'] = '';                                 
            if ($config['config']['give_type'] == 'group') {            	
               $datas['other']['buyGift'] .= $this -> buildGroupGiftSelect($config['offers_id'], $expire, $config['offers_name'], $config['offers_type'], $config['use_coupon'], $config['config'], $datas);
            }else{            	
               $datas['other']['buyGift'] .= $this -> buildGiftSelect($config['offers_id'], $expire, $config['offers_name'], $config['offers_type'], $config['use_coupon'], $config['config'], $datas);
            }   
                            
            $this -> offers && $datas['offers'][$config['offers_id']] = $this -> offers;
    	}
    	else {
    	    if ($_COOKIE['order_gift']) {
        	    $temparr = explode(']::[', $_COOKIE['order_gift']);
        	    $newarr = array();
        	    for ( $i = 0; $i < count($temparr); $i++ ) {
        	        $temparr2 = explode('-', $temparr[$i]);
        	        if ($config['offers_id'] != $temparr2[0]) {
        	            $newarr[] = $temparr[$i];
        	        }
        	    }
                setcookie('order_gift', implode(']::[', $newarr), time() + 86400 * 365, '/');
                $_COOKIE['order_gift'] = implode(']::[', $newarr);
            }
        }
        return $datas;
    }

    private function buildGiftSelect($id, $expire, $offersName, $offersType, $useCoupon, $config, &$datas)
    {
        if (count($config['allGift']) == 0)   return false;
    	if ($_COOKIE['order_gift']) {
    	    $productid_arr = array();
    	    $temparr = explode(']::[', $_COOKIE['order_gift']);
    	    for ( $i = 0; $i < count($temparr); $i++ ) {
    	        $temparr2 = explode('-', $temparr[$i]);
    	        if ($id == $temparr2[0]) {
    	            $productid_arr = explode(':', $temparr2[1]);
    	            break;
    	        }
    	    }
    	    
    	    $productid_sql = '';
    	    for ($i = 0; $i < count($productid_arr); $i++) {
    	        if ($productid_arr[$i]) {
    	            $productid_sql .= $productid_arr[$i].',';
    	        }
    	    }
    	    if ($productid_sql) {
    	        $productid_sql = substr($productid_sql, 0, -1);
    	        $product_data = $this -> _goods -> getProduct("a.product_id in ({$productid_sql})");
    	        for ( $i = 0; $i < count($product_data); $i++ ) {
    	            $productMsg[$product_data[$i]['product_id']] = $product_data[$i];
    	        }
    	    }
    	}
    	else {
    	    for ( $i = 0; $i < count($config['allGift']); $i++ ) {
    	        $productid_arr[] = '';
    	    }
    	    $productMsg = array();
    	}
    	
    	$action = Zend_Controller_Front::getInstance() -> getRequest() -> getActionName();
    	for ( $i = 0; $i < count($config['allGift']); $i++ ) {            
    	    $productID = $productid_arr[$i];
    	    $bid = 'order_gift';
    	    if ($productID) {    	        
                    $offerProduct['offers_name'] = $offersName;
                    $offerProduct['offers_type'] = $offersType;
                    $offerProduct['org_price'] = $productMsg[$productID]['price'];
                    $offerProduct['use_coupon'] = $useCoupon;
                    $offerProduct['price'] = $config['allPrice'][$i];
                    $offerProduct['weight'] = $productMsg[$productID]['p_weight'];
                    $this -> _number++;
                    $this -> _weight += $productMsg[$productID]['p_weight'];
                    $this -> _volume += round($productMsg[$productID]['p_length'] * $productMsg[$productID]['p_width'] * $productMsg[$productID]['p_height'] * 1 * 0.001, 3);
                    $offerProduct['product'][] = $productMsg[$productID];
                    $this -> offers[] = $offerProduct;
                    $datas['number']++;
                    $datas['org_amount'] += $config['allPrice'][$i];
                    $datas['amount'] += $config['allPrice'][$i];
                    $datas['goods_amount'] += $config['allPrice'][$i];
                    unset($offerProduct);                
    	    }
    	    
    	    $goods_name = $productMsg[$productID]['goods_name'] ? $productMsg[$productID]['goods_name'] : "<font color='#FF3333'>未选择赠品(".$offersName.")</font> ";
    	    $img_url = ($productMsg[$productID]['goods_img']) ? $this -> imgBaseUrl . '/' . str_replace('.', '_60_60.', $productMsg[$productID]['goods_img']) : $this -> imgBaseUrl . '/images/package_no_product.gif';
    	    $goods_number = 1;
    	    $show_goods_number = $productID ? $goods_number : 0;
    	    $price =  $config['allPrice'][$i]? $config['allPrice'][$i]:0;
    	    $gift_Price = $productID ? $config['allPrice'][$i] : 0;
    	    if ($productMsg['onsale'] && !$productMsg['is_gift']) {
                $note = '<br><font color=red>（此商品已经下架）</font>';
            }
            
            if ($action == 'index') {
        		$html .= '<tr align="left" id="order_gift'.$i.'">
        		<td ><b class="c2" title="'.$offersName.'">赠送商品</b></td>
        	    <td class="t_pro">			
        		<input type="hidden" name="ids[]" id="gift_' . $bid.$i . '" value="' . $productID . '">
        		<input type="hidden" id="buy_number_gift_' . $bid .$id.$i. '" value="' . $show_goods_number . '" />
        		<input type="hidden" id="gift_price'.$id.$i.'" value="' . $price. '" />
        		<img id="gift_img_' . $bid.$id.$i.'" src="'.$img_url . '" width="60" height="60">    			
    			<span id="gift_name_' . $bid.$id.$i.'">' . $goods_name . $note .'</span></td>    					
    			<td id="goods_price_tmp'.$id.$i.'">'.$price.'</td>
    			<td  id="gift_number_'.$bid.$id.$i.'" >'.$goods_number.'</td>
    			<td  id="change_price_tmp'.$id.$i.'">'.$gift_Price.'</td>
    			<td  id="total_price_tmp'.$id.$i.'">'.$gift_Price.'</td>
    			<td  colspan="2"><a href="javascript:void(0);" onclick="openWinForOrderGift(' . $id . ', 0,\'order_gift\','.$i.','.$id.')">选择</a> | <a href="javascript:void(0);" onclick="delPackageGoodsOrderGift('.$i.',\''.$offersName.'\','.$id.')">删除</a></td>
    		    </tr>';
        	} else {
        		if ($productID) {
        			$html .= '<tr align="left">
        		 	 <td ><b class="c2" title="'.$offersName.'">赠送商品</b></td>
        		     <td  class="t_pro">		
        		    <input type="hidden" name="ids[]" value="' . $productID . '">
        		    <input type="hidden" value="' . $goods_number . '" />  
        		    <img src="'.$img_url . '" border="0" width="60" height="60">
        		   ' . $goods_name . '</td>
    			    <td>'.$gift_Price.'</td>
    			    <td>' . $goods_number . '</td>
    			    <td>'.$gift_Price.'</td>
    			    <td>'.$gift_Price.'</td>
    		        </tr>';
        		}
        	}
    	}
		return $html;
    }
    
    
    private function buildGroupGiftSelect($id, $expire, $offersName, $offersType, $useCoupon, $config, &$datas)
    {
    	if (count($config['allGroup']) == 0)   return false;
    	
    	$groupGoodsAPI = new Shop_Models_API_GroupGoods();
    	if ($_COOKIE['order_gift']) {
    		$productid_arr = array();
    		$temparr = explode(']::[', $_COOKIE['order_gift']);
    	
    		for ( $i = 0; $i < count($temparr); $i++ ) {
    			$temparr2 = explode('-', $temparr[$i]);
    			if ($id == $temparr2[0]) {
    				$productid_arr = explode(':', $temparr2[1]);
    				break;
    			}
    		}   
    		 			
    		$productid_arr = array_filter($productid_arr);
    		if ($productid_arr) {
    			$productid_sql = implode(',', $productid_arr);       		
    			$product_data =  $groupGoodsAPI->get("group_id in ({$productid_sql})", '*', null, null, 0);    			
    			foreach  ( $product_data as $group ) {
    				$group['goods_name'] = $group['group_goods_name'];
    				$group['goods_img']  = $group['group_goods_img'];
    				$group['price']      = $group['group_price'];
    				$group['product_id'] = $group['group_id']; 
    				$productMsg[$group['group_id']] = $group;    				
    			}
    		}
    	}
    	else {
    		for ( $i = 0; $i < count($config['allGroup']); $i++ ) {
    			$productid_arr[] = '';
    		}
    		$productMsg = array();
    	}
 
    	$action = Zend_Controller_Front::getInstance() -> getRequest() -> getActionName();
    	$html = '';    
    	for ( $i = 0; $i < count($config['allGroup']); $i++ ) {    
    		$productID = $productid_arr[$i];    	
    		$bid = 'order_gift';
    		if ($productID) {  
    			    $offerProduct = array();
    				$offerProduct['offers_name'] = $offersName;
    				$offerProduct['offers_type'] = $offersType;
    				$offerProduct['org_price'] = $productMsg[$productID]['group_price'];
    				$offerProduct['use_coupon'] = $useCoupon;
    				$offerProduct['price'] = $config['allGroupPrice'][$i];
    				$offerProduct['weight'] = $productMsg[$productID]['p_weight'];
    				$offerProduct['is_group'] = 1;
    				$this -> _number++;
    				$this -> _weight += $productMsg[$productID]['p_weight'];
    				$this -> _volume += round($productMsg[$productID]['p_length'] * $productMsg[$productID]['p_width'] * $productMsg[$productID]['p_height'] * 1 * 0.001, 3);
    				
    				$offerProduct['product'][] = $productMsg[$productID];
    				$this -> offers[] = $offerProduct;
    				$datas['number']++;
    				$datas['org_amount'] += $config['allGroupPrice'][$i];
    				$datas['amount'] += $config['allGroupPrice'][$i];
    				$datas['goods_amount'] += $config['allGroupPrice'][$i];
    				unset($offerProduct);    			
    		}
    		    			
    		$goods_name = $productMsg[$productID]['group_goods_name'] ? $productMsg[$productID]['group_goods_name'] : "<font color='#FF3333'>未选择赠品(".$offersName.")</font> ";
    		$img_url = ($productMsg[$productID]['group_goods_img']) ? $this -> imgBaseUrl . '/' . str_replace('.', '_60_60.', $productMsg[$productID]['group_goods_img']) : $this -> imgBaseUrl . '/images/package_no_product.gif';
    		$goods_number = 1;
    		$show_goods_number = $productID ? $goods_number : 0;
    		$price = $config['allGroupPrice'][$i]?$config['allGroupPrice'][$i]:0;
    		$gift_Price = $productID ? $price: 0;
    		if ($productMsg['onsale'] && !$productMsg['is_gift']) {
    			$note = '<br><font color=red>（此商品已经下架）</font>';
    		}
    
    		if ($action == 'index') {
    			$html .= '<tr align="left" id="order_gift'.$i.'">
        		<td ><b class="c2" title="'.$offersName.'">赠送商品</b></td>
        	    <td  class="t_pro" >
        		<input type="hidden" name="ids[]" id="gift_' . $bid.$i . '" value="' . $productID . '">
        		<input type="hidden" id="buy_number_gift_' . $bid .$id.$i. '" value="' . $show_goods_number . '" />
        		<input type="hidden" id="gift_price'.$id.$i.'" value="' . $price . '" />
        		<img id="gift_img_' . $bid.$id.$i.'" src="'.$img_url . '" border="0" width="60" height="60">
    			<span id="gift_name_' . $bid.$id.$i.'">' . $goods_name . $note .'</span></td>    	
    			<td id="goods_price_tmp'.$id.$i.'">'.$price.'</td>
    			<td  id="gift_number_' . $bid.$id.$i.'" >' . $goods_number . '</td>
    			<td  id="change_price_tmp'.$id.$i.'">'.$gift_Price.'</td>
    			<td  id="total_price_tmp'.$id.$i.'">'.$gift_Price.'</td>
    			<td ><a href="javascript:void(0);" onclick="openGroupWinForOrderGift(' . $id . ', 0,\'order_gift\','.$i.','.$id.')">选择</a> | <a href="javascript:void(0);" onclick="delPackageGoodsOrderGift('.$i.',\''.$offersName.'\','.$id.')">删除</a></td>
    		    </tr>';
    		} else {
    			if ($productID) {
    				$html .= '<tr align="left">
        		 	 <td ><b class="c2" title="'.$offersName.'">赠送商品</b></td>
        		     <td  class="t_pro">
        		    <input type="hidden" name="ids[]" value="' . $productID . '">
        		    <input type="hidden" value="' . $goods_number . '" />
        		    <img id="gift_img_' . $bid . '" src="'.$img_url . '" width="60" height="60">
        		   ' . $goods_name . '</td>
    			    <td>'.$gift_Price.'</td>
    			    <td>' . $goods_number . '</td>
    			    <td>'.$gift_Price.'</td>
    			    <td>'.$gift_Price.'</td>
    		        </tr>';
    			}
    		}
    	}
    	return $html;
    }
    
    
    private function buildIndexCart($id, $expire, $offersName, $offersType, $useCoupon, $config, &$datas)
    {
        if (count($config['allGift']) == 0)   return false;
        
        if ($_COOKIE['order_gift']) {
            $productid_arr = array();
    	    $temparr = explode(']::[', $_COOKIE['order_gift']);
    	    for ( $i = 0; $i < count($temparr); $i++ ) {
    	        $temparr2 = explode('-', $temparr[$i]);
    	        if ($id == $temparr2[0]) {
    	            $productid_arr = explode(':', $temparr2[1]);
    	            break;
    	        }
    	    }
    	    $productid_sql = '';
    	    for ($i = 0; $i < count($productid_arr); $i++) {
    	        if ($productid_arr[$i]) {
    	            $productid_sql .= $productid_arr[$i].',';
    	        }
    	    }
    	    if ($productid_sql) {
    	        $productid_sql = substr($productid_sql, 0, -1);
    	        $product_data = $this -> _goods -> getProduct("a.product_id in ({$productid_sql})");
    	        for ( $i = 0; $i < count($product_data); $i++ ) {
    	            $productMsg[$product_data[$i]['product_id']] = $product_data[$i];
    	        }
    	        for ( $i = 0; $i < count($config['allGift']); $i++ ) {
    	            $productID = $productid_arr[$i];
    	            $gift_Price = $config['allPrice'][$i] ? $config['allPrice'][$i] : 0;
    	            if ($productID) {
                        $img_url = ($productMsg[$productID]['goods_img']) ? $this -> imgBaseUrl . '/' . str_replace('.', '_60_60.', $productMsg[$productID]['goods_img']) : $this -> imgBaseUrl . '/images/package_no_product.gif';
						/*
            			$html .= "<li class=\"clear\">
                                    <a href=\"/goods-{$productMsg[$productID]['product_id']}.html\" target=_blank><img src=\"{$img_url}\"/><a>
                                    <span><a href=\"goods-{$productMsg[$productID]['product_id']}.html\" target=_blank>{$productMsg[$productID]['goods_name']}</a></span><strong>￥{$gift_Price}<em>*</em>1
                                    <a>赠品</a></strong>
                                  </li>";
						*/
                	}
    	        }
    	    }
        }
        return $html;
    }
    
    //是否不在指定商品范围内
    private function inSpecialScope($config, $datas)
    {
        if ( !$config['allGoods'] && !$config['allGroupGoods'] ) return true;
      
        $total = 0;
        if ( $config['allGoods'] && $datas['data'] ) {
            parse_str($config['allGoods']);
                
            foreach ( $datas['data'] as $data ) {
                if ( $goodsDiscount[$data['goods_id']] ) {
                    $total += $data['price'] * $data['number'];
                }
            }
        }
        else if ( $config['allGroupGoods'] && $datas['group_goods_summary'] ) {
            parse_str($config['allGroupGoods']);
            
            foreach ( $datas['group_goods_summary'] as $data ) {
                if ( $goodsDiscount[$data['group_id']] ) {
                    $total += $data['group_price'] * $data['number'];
                }
            }
        }
        
        if ( $total >= $config['min_price'] )  return true;
        
        return false;
    }
   
}