<?php

class Custom_Model_Offers_Discount extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '商品折扣';
    
    /**
     * 处理并输出显示内容
     *
     * @param    array    $datas
     * @param    array    $config
     * @return   string
     */
    public function responseToView($config, $datas = null)
    {
    	$array = array(
				    	'1' => '一',
				    	'2' => '二',
				    	'3' => '三',
				    	'4' => '四',
				    	'5' => '五',
				    	'6' => '六',
				    	'7' => '七',
				    	'8' => '八',
				    	'9' => '九',
				    	);
    	if ($config['config']['allDiscount'] && $datas) {
    		foreach ($datas as $key => $data)
    	    {
    	    	$discountConfig = $this -> getSelectGoodsConfig($datas[$key]['goods_id'], $datas[$key]['cat_path'], $config['config']['allDiscount']);
    		    
    		    if ($discountConfig > 0) {
    		    	$datas[$key]['org_price'] = $data['price'];
    		    	$datas[$key]['offers_type'] = $config['offers_type'];
    		    	$discount = $config['config']['discount'];
    		    	if ($config['config']['to_market_price']) {
    		            $datas[$key]['price'] = $datas[$key]['market_price'];
    		        }
    		    	$datas[$key]['price'] = sprintf('%.2f', round($datas[$key]['price'] * ($discount / 10), 2));
    		    	if (strstr($config['config']['discount'], '.')) {
    		    	    $r = explode('.', $discount);
    		    	    $discount_title = $array[$r[0]].$array[$r[1]];
    		        }else{
    		        	$discount_title = $array[$discount];
    		        }
    		    	$datas[$key]['discount_title'] = $discount_title;
    		    }
    	    }
    	}
        return $datas;
    }
    
    /**
     * 处理并输出购物车内容
     *
     * @param    array    $datas
     * @param    array    $config
     * @return   string
     */
    public function responseToCart($config, $datas = null)
    {
        
        if ($config['config']['allDiscount'] && $datas['data']) {
        	$datas['org_amount'] = $datas['amount'];
    		foreach ($datas['data'] as $key => $data)
    	    {
    	    	$discountConfig = $this -> getSelectGoodsConfig($datas['data'][$key]['goods_id'], $datas['data'][$key]['cat_path'], $config['config']['allDiscount']);
    		    
    		    if ($discountConfig > 0) {
    		        if ($config['config']['to_market_price']) {
    		            $data['price'] = $data['market_price'];
    		        }
    		    	$datas['data'][$key]['org_price'] = $data['org_price'];
    		    	$datas['amount'] -= ($datas['data'][$key]['price'] - round($data['price'] * ($config['config']['discount'] / 10), 2)) * $datas['data'][$key]['number'];
                    $datas['goods_amount'] -= ($datas['data'][$key]['price'] - round($data['price'] * ($config['config']['discount'] / 10), 2)) * $datas['data'][$key]['number'];
    		    	$datas['data'][$key]['price'] = round($data['price'] * ($config['config']['discount'] / 10), 2);
    		    	$offerProduct['offers_name'] = $config['offers_name'];
    		    	$offerProduct['offers_type'] = $config['offers_type'];
    		    	$offerProduct['use_coupon'] = $config['use_coupon'];
    		    	$offerProduct['price'] = ($datas['data'][$key]['price'] - $data['org_price']) * $datas['data'][$key]['number'];
	                $offerProduct['parent_pid'] = $datas['data'][$key]['product_id'];
	                $offers[$key] = $offerProduct;
	                unset($offerProduct);
    		    }
    	    }
    	    $offers && $datas['offers'][$config['offers_id']] = $offers;
    	}
        return $datas;
    }
}
