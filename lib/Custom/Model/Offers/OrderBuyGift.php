<?php

class Custom_Model_Offers_OrderBuyGift extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '商品买赠(多对一)';
    
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
        $this -> _goods = new Shop_Models_API_Goods();
        setcookie('order_buy_gift', '', time () + 86400 * 365, '/');
        $_COOKIE['order_buy_gift'] = '';
    	if ($config['config']['allGoods'] && $datas['data']) {
    		foreach ($datas['data'] as $key => $data)
    	    {
    	    	$discountConfig = $this -> getSelectGoodsConfig($datas['data'][$key]['goods_id'], $datas['data'][$key]['cat_path'], $config['config']['allGoods']);
    		    if ($discountConfig > 0) {
                    if ($_COOKIE['order_buy_gift'] != $config['config']['product_id']) {
                        setcookie('order_buy_gift', $config['config']['product_id'], time () + 86400 * 365, '/');
                        $_COOKIE['order_buy_gift'] = $config['config']['product_id'];
                    }
    		    	$expire = ($config['to_date']) ? strtotime($config['to_date']) + 86400 : time() + 365 * 86400;
    		    	$this -> offers = '';
                    $datas['other']['orderBuyGift'] = $this -> buildGiftSelect($config['offers_id'], $expire, $config['offers_name'], $config['offers_type'], $config['use_coupon'], $data['product_id'], $datas);
                    $datas['other_index']['orderBuyGift'] = $this -> buildIndexCart($config['offers_id'], $expire, $config['offers_name'], $config['offers_type'], $config['use_coupon'], $data['product_id'], $datas);
                    $this -> offers && $datas['offers'][$config['offers_id']] = $this -> offers;
    		    }
    	    }
    	}
        return $datas;
    }

    private function buildGiftSelect($id, $expire, $offersName, $offersType, $useCoupon, $pid, &$datas)
    {
        $productID = intval($_COOKIE['order_buy_gift']);
    	if ($productID) {
            $productMsg = @array_shift($this -> _goods -> getProduct("a.product_id='" . $productID . "'"));
            $offerProduct['offers_name'] = $offersName;
            $offerProduct['offers_type'] = $offersType;
            $offerProduct['org_price'] = $productMsg['price'];
            $offerProduct['use_coupon'] = $useCoupon;
            $offerProduct['price'] = 0;
            $offerProduct['weight'] = $productMsg['p_weight'];
            $offerProduct['parent_pid'] = $pid;
            $this -> _number++;
            $this -> _weight += $productMsg['p_weight'];
            $this -> _volume += round($productMsg['p_length'] * $productMsg['p_width'] * $productMsg['p_height'] * 1 * 0.001, 3);
            $offerProduct['product'][] = $productMsg;
            $this -> offers[] = $offerProduct;

            $datas['org_amount'] += 0;
            $datas['amount'] += 0;
            $datas['goods_amount'] += 0;

            unset($offerProduct);
    	}
    	$product_id = ($productMsg['product_id']) ? $productMsg['product_id'] : '';
	    $goods_name = ($productMsg['goods_name']) ? $productMsg['goods_name'] : "<font color='#FF3333'>未选择赠品</font> ";
	    $img_url = ($productMsg['goods_img']) ? $this -> imgBaseUrl . '/' . str_replace('.', '_60_60.', $productMsg['goods_img']) : $this -> imgBaseUrl . '/images/package_no_product.gif';
	    $goods_number = ($productMsg['product_id']) ? 1 : 0;
        $goods_Price = 0;
    	$action = Zend_Controller_Front::getInstance() -> getRequest() -> getActionName();
    	if ($productMsg['onsale'] && !$productMsg['is_gift']) {
            $note = '<br><font color=red>（此商品已经下架）</font>';
        }
    	if ($action == 'index') {
            $bid = 'order_buy_gift';
    		$html = '<tr align="left" id="order_buy_gift">
    		<td ><b class="c2" title="'.$offersName.'">赠送商品</b></td>
    	    <td  class="t_pro" >			
    		<input type="hidden" name="ids[]" id="gift_' . $bid . '" value="' . $product_id . '">
    		<input type="hidden" id="buy_number_gift_' . $bid . '" value="' . $goods_number . '" />
    		<input type="hidden" id="gift_price" value="0" />	
			<img id="gift_img_' . $bid . '" src="' . $img_url . '" border="0" width="60" height="60">			
		    <span id="gift_name_' . $bid . '">' . $goods_name . $note .'</span></td>
			<td>0</td>
			<td id="gift_number_' . $bid . '" align="center">' . $goods_number . '</td>
			<td>0</td>
			<td>0</td>
			<td>&nbsp;</td>
		    </tr>';
    	} else {
    		if ($product_id) {
    			$html = '<tr align="left">
    			<td ><b class="c2" title="'.$offersName.'">赠送商品</b></td>
    		    <td  class="t_pro">	
    		    <input type="hidden" name="ids[]" value="' . $product_id . '">
    		    <input type="hidden" value="' . $goods_number . '" />	    		    
    		    <img id="gift_img_' . $bid . '" src="' . $img_url . '" border="0" width="60" height="60">
    		    ' . $goods_name . '</td>
			    <td>0</td>
			    <td>' . $goods_number . '</td>
			    <td colsapn="2">&nbsp;</td>
		        </tr>';
    		} else {
    			$html = '';
    		}
    	}
		return $html;
    }
    
    private function buildIndexCart($id, $expire, $offersName, $offersType, $useCoupon, $pid, &$datas)
    {
        $productID = intval($_COOKIE['order_buy_gift']);
    	if ($productID) {
            $productMsg = @array_shift($this -> _goods -> getProduct("a.product_id='" . $productID . "'"));
            $img_url = ($productMsg['goods_img']) ? $this -> imgBaseUrl . '/' . str_replace('.', '_60_60.', $productMsg['goods_img']) : $this -> imgBaseUrl . '/images/package_no_product.gif';
			    $html = "<li class=\"clear\">
                           <a href=\"/goods-{$productMsg['product_id']}.html\" target=_blank><img src=\"{$img_url}\"/><a>
                           <span><a href=\"goods-{$productMsg['product_id']}.html\" target=_blank>{$productMsg['goods_name']}</a></span><strong>￥0<em>*</em>1
                           <a>赠品</a></strong>
                         </li>";
					
		        return $html;
        }
    }
}