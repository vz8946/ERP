<?php

class Custom_Model_Offers_BuyGift extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '商品买赠(多对多)';
    
    /**
     * 处理并输出内容
     *
     * @param    array    $datas
     * @param    array    $config
     * @return   string
     */
    public function responseToCart($config, $datas = null)
    {
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
	    
	    $this -> imgBaseUrl = Zend_Registry::get('config') -> view -> imgBaseUrl;
		$this -> _goods = new Shop_Models_API_Goods();
        $this -> _weight = $datas['weight'];
        $this -> _volume = $datas['volume'];
        $this -> _number = $datas['number'];
        
        $configFieldArray = array('allGoods', 'allGroupGoods');
        $dataFieldArray = array('data', 'group_goods_summary');
        for ($fieldIndex = 0; $fieldIndex <= 1; $fieldIndex++) {
            $configField = $configFieldArray[$fieldIndex];
            $dataField   = $dataFieldArray[$fieldIndex];
            
        	if ($config['config'][$configField] && $datas[$dataField]) {
        		foreach ($datas[$dataField] as $key => $data) {
        		    if ($configField == 'allGoods') {
        	    	    $discountConfig = $this -> getSelectGoodsConfig($datas[$dataField][$key]['goods_id'], $datas[$dataField][$key]['cat_path'], $config['config'][$configField]);
        	    	}
        	    	else {
        	    	    $discountConfig = $this -> getSelectGroupGoodsConfig($datas[$dataField][$key]['group_id'], $config['config'][$configField]);
        	    	}
        		    if ($discountConfig > 0) {
        		        
        		        if ($configField == 'allGoods') {
        		            $keyID = $datas[$dataField][$key]['product_id'];
        		        }
        		        else {
        		            $keyID = $datas[$dataField][$key]['group_id'];
        		        }
        		        
        		    	$expire = ($config['to_date']) ? strtotime($config['to_date']) + 86400 : time() + 365 * 86400;
        		    	if ($_COOKIE['gift']) {
        		    		$num = 0;
        		            $this -> cookie = explode(',', $_COOKIE['gift']);
        		            foreach ($this -> cookie as $key1 => $value1) {
        		            	$num++;
        		            	$vvalue = explode(':', $value1);
        		            	
        		            	if ($vvalue[2] == $keyID) {
        		            		$pcookie[$vvalue[0]] = $value1;
        		            	} else {
        		            		$opcookie[$vvalue[0]] = $value1;
        		            	}
        		            }
        		            
        		            if (count($pcookie) > $datas[$dataField][$key]['number']) {
        		            	ksort($pcookie);
        		            	array_pop($pcookie);
        		            	$this -> cookie = is_array($opcookie) ? array_merge($pcookie, $opcookie) : $pcookie;
        		            	$cookie = implode(',', array_unique($this -> cookie));
        		    		    setcookie('gift', $cookie, $expire, '/');
        		            }
        		            //$num += count($pcookie);
        		            
        	            }
        		    	$number = $config['config']['number'] ? $config['config']['number'] : 1;
        		    	
        		    	if ( $datas[$dataField][$key]['number'] < $number ) continue;
        		    	
        		    	if ( $config['config']['loop_gift'] ) {
        		    	    $gift_number = floor($datas[$dataField][$key]['number'] / $number);
        		    	}
        		    	else    $gift_number = 1;
                        
        		    	$this -> offers = '';
        		    	$i = 0;
        		    	for ($j = 1; $j <= $gift_number; $j++) {
        		    		$i++;
        		    		if ($configField == 'allGoods') {
        		    		    $datas[$dataField][$key]['other']['buyGift'] .= $this -> buildGiftSelect($config['offers_id'],$keyID, $keyID.'_'.$i, $expire, $config['offers_name'], $config['offers_type'], $config['use_coupon'], $i, $datas, $config);
        		    		}
        		    		else {
        		    		    $datas['other']['group_goods'] .= $this -> buildGiftSelect($config['offers_id'],$keyID, $keyID.'_'.$i, $expire, $config['offers_name'], $config['offers_type'], $config['use_coupon'], $i, $datas, $config);
        		    		}
        		    		
        		    		//$datas['other_index']['buyGift'] .= $this -> buildIndexCart($config['offers_id'],$keyID, $keyID.'_'.$i, $expire, $config['offers_name'], $config['offers_type'], $config['use_coupon'], $i, $config);
                        }
                        
                        if ($this -> offers) {
                            if ($datas['offers'][$config['offers_id']]) {
                                $datas['offers'][$config['offers_id']] = array_merge($datas['offers'][$config['offers_id']], $this -> offers);
                            }
                            else {
                                $datas['offers'][$config['offers_id']] = $this -> offers;
                            }
                        }
        		    }
        	    }
        	}
        }
        
        $datas['weight'] = $this -> _weight;//add
        $datas['volume'] = $this -> _volume;//add
        $datas['number'] = $this -> _number;//add
        
        return $datas;
    }
    
    private function buildGiftSelect($id, $pid, $bid, $expire, $offersName, $offersType, $useCoupon, $i, &$datas, $config)
    {
    	$price = 0;
    	if ($this -> cookie) {
    		foreach ($this -> cookie as $key => $vproduct) {
				$product = explode(':', $vproduct);				
				if ($bid == $product[0]) {
				    if ($config['config']['allGift']) {
					    $productMsg = @array_shift($this -> _goods -> getProduct("a.product_id='" . $product[1] . "'"));
					    parse_str($config['config']['allGift']);
					}
					else {
					    $groupGoodsAPI = new Shop_Models_API_GroupGoods();
					    $data = array_shift($groupGoodsAPI -> get("group_id = {$product[1]}", '*', null, null, 0));
					    $productMsg = '';
					    $productMsg['product_id'] = $data['group_id'];
					    $productMsg['price'] = $data['group_price'];
					    $productMsg['goods_name'] = $data['group_goods_name'];
					    $productMsg['goods_img'] = $data['group_goods_img'];
					    parse_str($config['config']['allGiftGroup']);
					}
					$price = $goodsDiscount[$productMsg['product_id']] == 0.00 ? 0 : $goodsDiscount[$productMsg['product_id']];
                    
	                $offerProduct['offers_name'] = $offersName;
	                $offerProduct['offers_type'] = $offersType;
	                $offerProduct['org_price'] = $productMsg['price'];
	                $offerProduct['use_coupon'] = $useCoupon;
	                $offerProduct['price'] = $price;
	                $offerProduct['weight'] = $productMsg['p_weight'];
	                $offerProduct['is_group'] = $config['config']['allGiftGroup'] ? 1 : 0;
                    $this -> _number++;
                    $this -> _weight += $productMsg['p_weight'];
                    $this -> _volume += round($productMsg['p_length'] * $productMsg['p_width'] * $productMsg['p_height'] * 1 * 0.001, 3);
	                $offerProduct['parent_pid'] = $pid;
	                $offerProduct['product'][] = $productMsg;
	                $this -> offers[$key] = $offerProduct;
	                unset($offerProduct);
	                
	                $datas['org_amount'] += $price;
                    $datas['amount'] += $price;
                    $datas['goods_amount'] += $price;
	                
	                break;
				}
			}
    	}
    	$product_id = ($productMsg['product_id']) ? $productMsg['product_id'] : '';
	    $goods_name = ($productMsg['goods_name']) ? $productMsg['goods_name'] : '<font color="#FF3333">未选择赠品('.$offersName .')</font>';
	    $img_url = ($productMsg['goods_img']) ? $this -> imgBaseUrl . '/' . str_replace('.', '_60_60.', $productMsg['goods_img']) : $this -> imgBaseUrl . '/images/package_no_product.gif';
	    $goods_number = ($productMsg['product_id']) ? 1 : 0;
	    $goods_price = $price;
	    $change_price = $price;
	    $total_price = $price;
    	$action = Zend_Controller_Front::getInstance() -> getRequest() -> getActionName();
    	if ($productMsg['onsale'] && !$productMsg['is_gift']) {
            $note = '<br><font color=red>（此商品已经下架）</font>';
        }
    	if ($action == 'index') {
    	    if ($config['config']['allGift']) {
    	        $click ='openWin';
    	    }
    	    else {
    	        $click ='openGroupWin';
    	    }
    		$html = '<tr id="gift_' . $bid . '">
    		<td ><b class="c2" title="'.$offersName.'">赠送商品</b></td>
    		<td  class="t_pro" id="gift_name_' . $bid . '">		
    		<input type="hidden" name="ids[]" id="gift_' . $bid . '" value="' . $product_id . '">
    		<input type="hidden" id="buy_number_gift_' . $bid . '" value="' . $goods_number . '" />
			<img id="gift_img_' . $bid . '" src="' . $img_url . '" border="0" width="60" height="60">
			 ' . $goods_name . $note .'</td>
			<td  id="goods_price_tmp'.$id.$i.'">'.$goods_price.'</td>
			<td  id="gift_number_' . $bid . '" align="center">' . $goods_number . '</td>
			<td  id="change_price_tmp'.$id.$i.'">'.$change_price.'</td>
			<td  id="total_price_tmp'.$id.$i.'">'.$total_price.'</td>
			<td  colspan="2"><a href="javascript:void(0);" onclick="'.$click.'(' . $id . ', ' . $pid . ', \'' . $bid . '\')">选择</a> | <a href="javascript:void(0);" onclick="delPackageGoods(\'' . $bid . '\', ' . $expire . ')">删除</a></td>
		    </tr>';
    	} else {
    		if ($product_id) {
    			$html = '<tr  id="gift_' . $bid . '">
    			<td ><b class="c2" title="'.$offersName.'">赠送商品</b></td>
                <td  class="t_pro"  >		
    		    <input type="hidden" name="ids[]" id="gift_' . $bid . '" value="' . $product_id . '">
    		    <input type="hidden" id="buy_number_gift_' . $bid . '" value="' . $goods_number . '" />
    		    <img id="gift_img_' . $bid . '" src="' . $img_url . '" border="0" width="60" height="60">
    		    <span id="gift_name_' . $bid . '">' . $goods_name . '</span></td>
			    <td align="center">'.$price.'</td>
			    <td id="gift_number_' . $bid . '">' . $goods_number . '</td>
			    <td colspan="2">&nbsp;</td>
		        </tr>';
    		} else {
    			$html = '';
    		}
    	}
		return $html;
    }
    
    private function buildIndexCart($id, $pid, $bid, $expire, $offersName, $offersType, $useCoupon, $i, $config)
    {
        if ($this -> cookie) {
    		foreach ($this -> cookie as $key => $vproduct)
			{
				$product = explode(':', $vproduct);
				if ($bid == $product[0]) {
					$productMsg = @array_shift($this -> _goods -> getProduct("a.product_id='" . $product[1] . "'"));
					parse_str($config['config']['allGift']);
					$price = $goodsDiscount[$productMsg['goods_id']] ? $goodsDiscount[$productMsg['goods_id']] : 0;
					$img_url = ($productMsg['goods_img']) ? $this -> imgBaseUrl . '/' . str_replace('.', '_60_60.', $productMsg['goods_img']) : $this -> imgBaseUrl . '/images/package_no_product.gif';
					$html = "<li class=\"clear\">
                               <a href=\"/goods-{$productMsg['product_id']}.html\" target=_blank><img src=\"{$img_url}\"/><a>
                               <span><a href=\"goods-{$productMsg['product_id']}.html\" target=_blank>{$productMsg['goods_name']}</a></span><strong>￥{$price}<em>*</em>1
                               <a>赠品</a></strong>
                             </li>";
					
					return $html;
			    }
			}
        }
    }
}