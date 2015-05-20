<?php

class Custom_Model_Offers_BuyMinus extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '商品买A立减B';
    
    /**
     * 处理并输出内容
     *
     * @param    array    $datas
     * @param    array    $config
     * @return   string
     */
    public function responseToCart($config, $datas = null)
    {
    	if ($config['config']['allGoods'] && $config['config']['allMinusGoods'] && $datas['data']) {
    		foreach ($datas['data'] as $key => $data)
    	    {
    	    	$goodsConfig = $this -> getSelectGoodsConfig($datas['data'][$key]['goods_id'], $datas['data'][$key]['cat_path'], $config['config']['allGoods']);
    		    if ($goodsConfig > 0) {
    		    	$goods[$key] = $datas['data'][$key];
    		    	for ($j=1; $j <= $datas['data'][$key]['number']; $j++)
    		    	{
    		    		$this -> i++;
                    }
    		    }
    	    }
    	    
    	    if ($this -> i) {
    	    	foreach ($datas['data'] as $key => $data)
    	    	{
    	    		if (!$goods[$key]) {
    	    			$minusConfig = $this -> getSelectGoodsConfig($datas['data'][$key]['goods_id'], $datas['data'][$key]['cat_path'], $config['config']['allMinusGoods']);
    	    	
    	    	    	if ($minusConfig > 0 && !$data['org_price']) {
    		    		    $datas['data'][$key]['org_price'] = $data['org_price'];
    		    		    $datas['data'][$key]['show_org_price'] = true;
    		    		    $datas['amount'] -= $config['config']['minus'] * $data['number'];
                    	    $datas['goods_amount'] -= $config['config']['minus'] * $data['number'];
    		    		    $datas['data'][$key]['price'] = sprintf('%.2f', $data['price'] - $config['config']['minus']);
    		    		    $datas['data'][$key]['other']['buyMinus'] .= $this -> buildMinus($key+1, $config['offers_name'], $data['number']*$config['config']['minus']);
    		    		    $offerProduct['offers_name'] = $config['offers_name'];
    		    		    $offerProduct['offers_type'] = $config['offers_type'];
    		    		    $offerProduct['use_coupon'] = $config['use_coupon'];
    		    		    $offerProduct['price'] = -$config['config']['minus'] * $data['number'];
	                	    $offerProduct['parent_pid'] = $data['product_id'];
	                	    $offers[$key] = $offerProduct;
	                	    unset($offerProduct);
    	    	    	}
    	    		}
    	        }
    	        $offers && $datas['offers'][$config['offers_id']] = $offers;
    	    }
    	}
        return $datas;
    }
    
    /**
     * 创建显示内容
     *
     * @param    int     $id
     * @param    string  $offersName
     * @param    float   $offersMinus
     * @return   string
     */
    private function buildMinus($id, $offersName, $offersMinus)
    {
    	$action = Zend_Controller_Front::getInstance() -> getRequest() -> getActionName();
    	
    	if ($action == 'index') {
    	    $html = '<tr align="left" id="but_minus_' . $id . '">		   
		    <td class="s_title name">' . $offersName . '</td>
		    <td class="bk_blue" align="left">&nbsp;</td>
		    <td class="bk_blue" align="left">&nbsp;</td>
		    <td class="bk_blue" align="left">&nbsp;</td>
		    <td class="bk_blue" align="center" id="change_price_minus_' . $id . '">-' . $offersMinus . '</td>
		    <td class="td_right_top_border" align="center">&nbsp;</td>
		    </tr>';
    	} else {
    		$html = '<tr align="left" id="but_minus_' . $id . '">	
    	    <td>&nbsp;</td>			   
		    <td  align="center">' . $offersName . '</td>	
		    <td  align="center">-' . $offersMinus . '</td>	
		    <td  colspan="2">&nbsp;</td>
		    </tr>';
    	}
		return $html;
    }
}