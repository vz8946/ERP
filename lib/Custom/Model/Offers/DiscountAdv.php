<?php

class Custom_Model_Offers_DiscountAdv extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '商品第N件折扣';

    /**
     * 专享加个的对应名称
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
    	if ( !$datas['data'] )  return $datas;
    	
    	foreach ($config['config']['allGoods'] as $index => $value)
        {
    	    if ( !$config['config']['allGoods'][$index] || !$config['config']['allIndex'][$index] || !$config['config']['allDiscount'][$index] ) {
    	        continue;   
    	    }
            
    	    foreach ($datas['data'] as $key => $data)
    	    {
    		    $discountConfig = $this -> getSelectGoodsConfig($data['goods_id'], $data['cat_path'], $value);
    		    if ( !$discountConfig ) continue;

    		    $currentIndex = $config['config']['allIndex'][$index];
    		    $currentDiscount = $config['config']['allDiscount'][$index];
                
    		    if ( $data['number'] < $currentIndex )  continue;
    		    
    		    if ( $config['config']['loop_discount'] ) {
                    $discountNumber = floor( $data['number'] / $currentIndex );
    		    }
    		    else    $discountNumber = 1;
    		    
    		    $DiffPrice = 0;
    		    if ( $data['price_before_discount'] ) {     //之前已经打过折时需要比较总金额来判断启用哪个折扣
    		        if ( ($data['org_price'] * ($data['number'] - $discountNumber)) + ($data['org_price'] * $currentDiscount / 10 * $discountNumber) >= $data['amount'] ) {
    		            continue;
    		        }
    		        $DiffPrice = ($data['org_price'] - $data['price']) * $data['number'];
    		        $datas['data'][$key]['price'] = $data['org_price'];
    		        $data['price'] = $data['org_price'];
    		        $datas['data'][$key]['price_before_discount'] = 1;
    		        
    		        unset($datas['data'][$key]['remark']);
    		        unset($data['remark']);
    		        unset($datas['data'][$key]['member_discount']);
    		        unset($data['member_discount']);
    		        unset($datas['data'][$key]['price_before_discount']);
    		        unset($data['price_before_discount']);
    		    }
    		    
    		    if ( !isset($datas['data'][$key]['org_price']) ) {
    		        $datas['data'][$key]['org_price'] = $data['price'];
    		        $data['org_price'] = $data['price'];
    		    }
                
    		    $datas['data'][$key]['number'] -= $discountNumber;
                $datas['data'][$key]['amount'] -= $data['price'] * $discountNumber - $DiffPrice;
                $datas['data'][$key]['suffix'] = '0';
    		    $discount[$key] = $data;
    		    $discount[$key]['number'] = $discountNumber;
    		    $discount[$key]['price'] = sprintf( '%.2f', round($data['price'] * $currentDiscount / 10, 2) );
    		    $discount[$key]['member_discount'] = $currentDiscount;
    		    $discount[$key]['price_before_discount'] = $data['org_price'];
    		    $discount[$key]['amount'] = sprintf( '%.2f', $discount[$key]['price'] * $discountNumber );
    		    $discount[$key]['suffix'] = '1';
    		    
    		    $datas['amount'] -= ($datas['data'][$key]['price'] - $discount[$key]['price'] ) * $discountNumber - $DiffPrice;
                $datas['goods_amount'] -= ($datas['data'][$key]['price'] - $discount[$key]['price'] ) * $discountNumber - $DiffPrice;
                
                if ( $datas['data'][$key]['number'] == 0 ) {    //只可能是第1件折扣
    		        unset($datas['data'][$key]);
    		    }
                
    		    $offerProduct['offers_name'] = $config['offers_name'];
    		    $offerProduct['offers_type'] = $config['offers_type'];
			    $offerProduct['offers_id'] = $config['offers_id'];
    		    $offerProduct['use_coupon'] = $config['use_coupon'];
    		    $offerProduct['price'] = sprintf('%.2f', $discount[$key]['price'] - $discount[$key]['org_price']);
	            $offerProduct['parent_pid'] = $data['product_id'];
	            $offerProduct['number'] = $discountNumber;
	            $offersData[] = $offerProduct;
	            unset($offerProduct);
                
    		}
        }
        
        if ( is_array($discount) ) {
    	    $datas['data'] = array_merge($datas['data'], $discount);
        }

        $offersData && $datas['offers'][$config['offers_id']] = $offersData;
        return $datas;
    }
}
