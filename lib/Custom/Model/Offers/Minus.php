<?php

class Custom_Model_Offers_Minus extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '订单价格立减';
    
    /**
     * 用户认证名字空间名称
     * 
     * @var string
     */
	private $_userCertificationName = 'Card';
    
    /**
     * 处理并输出内容
     *
     * @param    array    $datas
     * @param    array    $config
     * @return   string
     */
    public function responseToCart($config, $datas = null)
    {

    	$amount = $number = 0;
    	if ($config['config']['allGoods'] && $datas['data']) {//指定商品价格减  2013-09-03 
    		foreach ($datas['data'] as $key => $data)
    		{
    			$discountConfig = $this -> getSelectGoodsConfig($datas['data'][$key]['goods_id'], $datas['data'][$key]['cat_path'], $config['config']['allGoods']);
    			if ($discountConfig > 0) {
    				$amount+=$data['price']*$data['number'];
    				$number+=$data['number'];
    			}
    		}
    		    			
    		if ($config['config']['price'] && $amount >= $config['config']['price'] && $amount < $config['config']['max_num'] ) {
    			$datas['org_amount'] = $datas['amount'];
    			$org_amount = $amount;
    			$overlay = $config['config']['overlay'];
    	
    			if($overlay == 1){
    				$countNum = floor($amount / $config['config']['price']);
    				for ($i = 0; $i < $countNum; $i++){
    					$datas['amount'] -= $config['config']['minus'];
    					$amount -= $config['config']['minus'];
    				}
    			}else{
    				$datas['amount'] -= $config['config']['minus'];
    				$amount -= $config['config']['minus'];
    			}
    				
    			$offerProduct['price'] = $amount - $org_amount;
    			$datas['true_amount'] = $amount;    			
    			
    			$offerProduct['number'] = $number; //用于运费判断
    			
    			$offerProduct['offers_name'] = $config['offers_name'];
    			$offerProduct['offers_type'] = $config['offers_type'];
    			$offerProduct['use_coupon'] =  $config['use_coupon'];
    			$datas['offers'][$config['offers_id']][] = $offerProduct;
    		}
    	}else{	//订单价格满多少减    	
	    	$certificationNamespace = new Zend_Session_Namespace($this -> _userCertificationName);
	    	$card = $certificationNamespace -> cardCertification;
			$amount = $datas['amount'] - $card['card_price'];

           //订单满减排除指定商品  	
			if ($config['config']['noallGoods'] && $datas['data']){			  	
				foreach ($datas['data'] as $key => $data)
				{
					$discountConfig = $this -> getSelectGoodsConfig($datas['data'][$key]['goods_id'], $datas['data'][$key]['cat_path'], $config['config']['noallGoods']);
					if ($discountConfig > 0) {
						$amount-=$data['price']*$data['number'];
					}
				}				
			}

           //去掉组合商品价格
		   if ($config['config']['minus_package'] == 1 && $datas['package_price']) {
		  	  $amount -= $datas['package_price'];
		   }		  
		  
	    	if ($config['config']['price'] && $amount >= $config['config']['price']  && $amount < $config['config']['max_num'] ) {
	    		$datas['org_amount'] = $datas['amount'];
	    		$org_amount = $amount;
				$overlay = $config['config']['overlay'];
				if($overlay == 1){
					for ($i = 0; $i < floor($amount / $config['config']['price']); $i++){
	    				$datas['amount'] -= $config['config']['minus'];
	    				$amount -= $config['config']['minus'];
	    			}
				}else{
					$datas['amount'] -= $config['config']['minus'];
	    			$amount -= $config['config']['minus'];
				} 		
	    		$offerProduct['price'] = $amount - $org_amount;
	    		$datas['true_amount'] = $amount;
	    		$offerProduct['offers_name'] = $config['offers_name'];
	    		$offerProduct['offers_type'] = $config['offers_type'];
	    		$offerProduct['use_coupon'] = $config['use_coupon'];
		        $datas['offers'][$config['offers_id']][] = $offerProduct;
	    	}
    	}
        return $datas;
    }
}
