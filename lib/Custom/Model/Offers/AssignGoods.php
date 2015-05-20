<?php
class Custom_Model_Offers_AssignGoods extends Custom_Model_Offers_Abstract
{
	/**
	 * 插件title
	 *
	 * @var    string
	 */
	protected $_title = '指定商品数量送赠品';
	
	
	public function __construct()
	{
		$this -> _goods = new Shop_Models_API_Goods();
		$this -> _offers = new Shop_Models_API_offers();
		$this -> imgBaseUrl = Zend_Registry::get('config') -> view -> imgBaseUrl;
		parent::__construct();
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
		
		$this -> imgBaseUrl = Zend_Registry::get('config') -> view -> imgBaseUrl;	   
        
		$this -> _weight = $datas['weight'];
		$this -> _volume = $datas['volume'];
		$this -> _number = $datas['number'];		
	
		$gift_price = 0;
		$expire = ($config['to_date']) ? strtotime($config['to_date']) + 86400 : time() + 365 * 86400;
		if ($datas['data'])
		{
			foreach ( $datas['data'] as $pk=> $productInfo)
			{
								
				if($productInfo['product_sn'] == $config['config']['product_sn'])
				{
					
					foreach($config['config']['goods'] as $spec) // 数量区间
					{
						
						if($productInfo['number']>=$spec['start_num']  &&  $productInfo['number']<=$spec['end_num'])
						{
							//更改商品单价  指定商品优惠						
							$old_price = $productInfo['price']*$productInfo['number']; //旧的商品总价
							$now_price = $spec['price'] * $productInfo['number']; //活动商品价格							
							
						    $datas['data'][$pk] ['price'] =  $spec['price'];
                            $datas['data'][$pk] ['amount'] =  $now_price;                         
                         
                            //重新计算价格，价格不变不处理                           
                            $diff_price =  $old_price - $now_price;                           
	                        $datas['goods_amount'] -=$diff_price;
	                        $datas['amount'] -= $diff_price;
                                                                                 
                            $gifts =  $spec['gift'];   
                            $this -> offers = '';
                            $datas['other']['buyGift'] = '';
                            $datas['other_index']['assignGift'] = '';                            	
                           
                            $datas['data'][$pk]['goods_gift'].= $this -> buildGiftSelect($config['offers_id'], $expire, $config['offers_name'], $config['offers_type'], $config['use_coupon'],$gifts, $datas);
                            $this -> offers && $datas['offers'][$config['offers_id']] = $this -> offers;   
                            break;
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
	
	private function buildGiftSelect($id, $expire, $offersName, $offersType, $useCoupon, $gifts, &$datas)
	{			
	
		if (count($gifts) == 0)   return false;	   
		$bid = 'assign-goods';
		
		$action = Zend_Controller_Front::getInstance() -> getRequest() -> getActionName();
		$i = 0;
		$len = count($gifts);
		$rowspan = '<td rowspan="2"><b class="c2">赠送商品</b>  </td>';
		foreach ($gifts as $productID=>$gnum) {
			
		    $productMsg = @array_shift($this -> _goods -> getProduct("b.goods_id='" . $productID. "'"));				    
	
			$offerProduct['offers_name'] = $offersName;
			$offerProduct['offers_type'] = $offersType;
			$offerProduct['org_price'] = $productMsg['price'];
			$offerProduct['use_coupon'] = $useCoupon;
			$offerProduct['price'] = 0;
			$offerProduct['weight'] = $productMsg['p_weight'];
			$offerProduct['number']  =  $gnum;
			$productMsg['number'] =  $gnum;
			
			$this -> _number += $gnum;
			$this -> _weight += $productMsg['p_weight'];
			$this -> _volume += round($productMsg['p_length'] * $productMsg['p_width'] * $productMsg['p_height'] * 1 * 0.001, 3);
			$offerProduct['product'][] = $productMsg;
			$this -> offers[] = $offerProduct;
			$datas['number']+= $gnum;
			
			unset($offerProduct);
			
				
			$goods_name = $productMsg['goods_name'] ? $productMsg['goods_name'] : "<font color='#FF3333'>未选择赠品(".$offersName.")</font> ";
			$img_url = ($productMsg['goods_img']) ? $this -> imgBaseUrl . '/' . str_replace('.', '_60_60.', $productMsg['goods_img']) : $this -> imgBaseUrl . '/images/package_no_product.gif';
			$goods_number = $gnum;
			$show_goods_number = $productID ? $goods_number : 0;
			$gift_Price = 0;
			if ($productMsg['onsale'] && !$productMsg['is_gift']) {
				$note = '<br><font color=red>（此商品已经下架）</font>';
			}
	
			$rowSpan = '';
			if($len>0 && $i==0) $rowSpan = '<td rowspan="'.$len.'"><b class="c2" title="'.$offersName.'">赠送商品</b></td>';
			
			if ($action == 'index') {
				$html .= '<tr id="order_gift'.$i.'" >
				'.$rowSpan.'		
        	    <td   class="t_pro" id="gift_name_' . $bid.$id.$i.'">
        		<input type="hidden" name="ids[]" id="gift_' . $bid.$i . '" value="' . $productID . '">
        		<input type="hidden" id="buy_number_gift_' . $bid .$id.$i. '" value="' . $show_goods_number . '" />
        		<input type="hidden" id="gift_price'.$id.$i.'" value="0" />  
        		<img id="gift_img_' . $bid.$id.$i.'" src="'.$img_url . '" border="0" width="60" height="60" />
    			 ' . $goods_name . $note .'</td>    					
    			<td  id="goods_price_tmp'.$id.$i.'">0</td>
    			<td id="gift_number_' . $bid.$id.$i.'" >' . $goods_number . '</td>    					
    			<td id="change_price_tmp'.$id.$i.'">'.$gift_Price.'</td>
    			<td id="total_price_tmp'.$id.$i.'">'.$gift_Price.'</td>
    			<td>'.$gift_Price.' </td>
    		   </tr>';
			} else {
					$html .= '<tr>
					'.$rowSpan.'	
        			<td class="t_pro"  align="center">
        		    <input type="hidden" name="ids[]" value="' . $productID . '">        		
        		    <input type="hidden" value="' . $goods_number . '" />
        		    <img id="gift_img_' . $bid . '" src="'.$img_url . '" border="0" width="60" height="60">' . $goods_name . '</td>
    			    <td>'.$gift_Price.'</td>
    			    <td>'. $goods_number . '</td>
    			    <td>'.$gift_Price.'</td>
    			    <td>'.$gift_Price.'</td>
    		        </tr>';				
			}
			$i++;
		}
		
		return $html;
	}	

}