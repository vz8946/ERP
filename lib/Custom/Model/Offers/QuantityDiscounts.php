<?php

class Custom_Model_Offers_QuantityDiscounts extends Custom_Model_Offers_Abstract
{
    /**
     * 插件title
     *
     * @var    string
     */
    protected $_title = '单商品不同数量区间赠送不同赠品';
   /**
     * 处理并输出内容
     *
     * @param    array    $datas
     * @param    array    $config
     * @return   string
     */
    public function responseToCart($config, $datas = null)
    {

		$hdname = $config['offers_name']; //折扣
		$produs = $config['allGoods'];  //所有产品字符串，goodsDiscount[1184]=1&goodsDiscount[1186]=1&goodsDiscount[1191]=1
		$discount = $config['config']['discount']; //折扣
		$buynum = $config['config']['buynum']; //条件数量
		$buynum2 = $config['config']['buynum2']; //条件数量2
		$status = intval($config['status']); //状态  1 启用
		$from_date = $config['from_date']; //状态  1 启用
		$to_date = $config['to_date']; //状态  1 启用
		$on_date = date("Y-m-d H:i:s");
		$overlay = $config['config']['overlay']; //同一商品是否叠加数量 1，叠加，0不叠加

		if($status == 1 && $on_date > $from_date && $on_date < $to_date && $discount<=10){
			$prodstr = $config['config']['allGoods'];
			$prod_list = $this->compStr2ProdLList($prodstr);
			$cart_prod = array();
			$cart_datas = $datas['data'];
			$cart_count = count($cart_datas);

			$find_count = 0;
			$find_prodid_arr = array(); //判断产品id数组
			$find_prod = array(); //购物车符合条件产品
			for($i=0;$i<$cart_count;$i++){
				$cart_prod_id = $cart_datas[$i]['product_id'];
				$cart_prod_number = $cart_datas[$i]['number'];
				if(in_array($cart_prod_id,$prod_list)){
					if($overlay == 1){
						$find_count += $cart_prod_number;
					}else{
						$find_count++;
					}
					array_push($find_prodid_arr,$cart_prod_id);
					array_push($find_prod,$cart_datas[$i]);
				}
			}
			if($find_count >= $buynum){  //判断是否符合要求
				$str_info = '';
				$str_money = 0;
				//根据购买数量判断折扣
				if(!empty($buynum2)){
					if($find_count >= $buynum && $find_count<$buynum2){
					  $discount = $config['config']['discount'];
					}else{
					  $discount = $config['config']['discount2'];
					}
				}else{
					$discount = $config['config']['discount'];
				}


				foreach($find_prod as $g){
					$coup_mon = round($g['price']*(1-$discount/10)*$g['number'],2);
					$str_money+= $coup_mon;
					$str_info.= $g['product_name']." <br/>活动价".round($g['price'])." X ".(1- round($discount/10,2))." X ".$g['number']."=".$coup_mon."元<br/>";

				}
				//遍历购物车修改，购物车单价及标题
				for($i=0;$i<$cart_count;$i++){
					$cart_prod_id = $cart_datas[$i]['product_id'];
					if(in_array($cart_prod_id,$find_prodid_arr)){
					$datas['data'][$i]['price'] = $datas['data'][$i]['price']*round($discount/10,2);
				}
			}

			//计算商品总金额
			$datas['amount'] = $datas['amount'] - $str_money;
			$datas['true_amount'] = $datas['amount'];
			$card = $certificationNamespace -> cardCertification;
    		$amount = $datas['amount'] - $card['card_price'];


			$datas['org_amount'] = $datas['amount'];
			$org_amount = $amount;
			$offerProduct['price'] = $str_money*-1;
    		$offerProduct['offers_name'] = $config['offers_name'];
    		$offerProduct['offers_type'] = $config['offers_type'];
    		$offerProduct['use_coupon'] = $config['use_coupon'];
	        $datas['offers'][$config['offers_id']][] = $offerProduct;
				//输出结果
				$datas['other'] = '<tr> <td align="center">&nbsp; </td><td align="center"> 活动： </td>  <td colspan="1">'.$hdname.' </td><td colspan="3">优惠信息：<br/>'.$str_info.'</td>  <td align="center"  colspan="2">优惠金额： -'.$str_money.' 元</td> <td align="left" colspan="3">&nbsp;</td></tr>';
			}
		}


        return $datas;
    }
    //处理产品列表字符串
    private function compStr2ProdLList($str){

    	$arr_prods = array();
    	$arr = explode("&",$str);
    	foreach($arr as $key=>$value){
			$value= str_replace("goodsDiscount[","",$value);
			$value= str_replace("]","",$value);
			array_push($arr_prods,intval($value));
		}
    	return $arr_prods;

    }



}