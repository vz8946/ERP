<?php

class Shop_Models_API_Information
{
    /**
     * DB对象
     */
    private $_db = null;
    
    /**
     * 构造函数
     *
     * @param  void
     * @return void
     */
    public function __construct()
    {
        $this  ->  _db = new Shop_Models_DB_Information();
    }
    
    /**
     * 取得推广用户数据
     *
     * @param    array  $search
     * @param    int    $uid
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
    public function getPromotion($search, $uid, $page = null, $pageSize = null)
    {
        $where = "WHERE B.parent_id='" . $uid . "'";
        if ($search['regYear'] && $search['regMonth']) {
            $where .= " AND (A.add_time >=" . mktime(0, 0, 0, $search['regMonth'], 1, $search['regYear']) . " AND A.add_time <= " . mktime(0, 0, 0, $search['regMonth']+1, 0, $search['regYear']) . ")";
        } else {
            $now = time();
            $where .= " AND A.add_time >=" . mktime(0, 0, 0, date('m', $now), 1, date('Y', $now));
        }
        $content = $this -> _db -> getPromotionUser($where, $page, $pageSize);
        $total = $this -> _db -> getPromotionUserCount($where);
        return array('content' => $content, 'total' => $total);
    }
    
    /**
     * 取得推广用户每日总数
     *
     * @param    array  $search
     * @param    int    $uid
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
    public function getPromotionDayCount($search, $uid)
    {
        $where = "WHERE B.parent_id='" . $uid . "'";
        
        if ($search['regYear'] && $search['regMonth']) {
            $where .= " AND (A.add_time >=" . mktime(0, 0, 0, $search['regMonth'], 1, $search['regYear']) . " AND A.add_time <= " . mktime(0, 0, 0, $search['regMonth']+1, 0, $search['regYear']) . ")";
            $monthDays = date('d', mktime(0, 0, 0, $search['regMonth']+1, 0, $search['regYear']));
        } else {
            $now = time();
            $where .= " AND A.add_time >=" . mktime(0, 0, 0, date('m', $now), 1, date('Y', $now));
            $monthDays = date('d', mktime(0, 0, 0, date('m', $now)+1, 0, date('Y', $now)));
        }
        $promotionDayCount = $this -> _db -> getPromotionDayCount($where);
        if ($promotionDayCount) {
            
            for ($day = 1; $day <= $monthDays;  $day++)
            {
                $promotions[$day] = 0;
            }
            foreach ($promotionDayCount as $key => $promotion)
            {
                $promotions[$promotion['day']] = $promotion['count'];
            }
            return $promotions;
        }
    }
    
    /**
     * 取得推广用户总数
     *
     * @param    array  $search
     * @param    int    $uid
     * @return   array
     */
    public function getPromotionAllCount($uid)
    {
        $where = "WHERE B.parent_id='" . $uid . "'";
        return$this -> _db -> getPromotionUserCount($where);
    }
    
    /**
     * 取得推广用户点击总数
     *
     * @param    array  $search
     * @param    int    $uid
     * @return   array
     */
    public function getClickCount($uid)
    {
        $where = "WHERE user_id='" . $uid . "'";
        return$this -> _db -> getClickCount($where);
    }


    /**
     * 取得推广点击数据
     *
     * @param    array  $search
     * @param    int    $uid
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
    public function getClick($search, $uid, $page = null, $pageSize = null)
    {
        $where = "WHERE B.user_id='" . $uid . "'";
        return $this -> _db -> getClick($where, $page, $pageSize);
    }


    /**
     * 生成柱状图
     *
     * @param    array  $data
     * @return   array
     */
    public function makeGrapBar($data)
    {
        $num  =  explode(',', $data['num']);
        $date  =  explode(',', $data['date']);
        $bar = new Custom_Model_GrapBar_Bar(710, 400, $num, $date);
        $bar -> setTitle($data['title']);
        $bar -> stroke();
    }
    
    /**
     * 取得分成状态
     *
     * @param    int   $type
     * @return   int
     */
    private function getSeparateType($type)
    {
        switch ($type) {
                case '0':
                    $result = null;
                    break;
                case '1':
                    $result = '0';
                    break;
                case '2':
                    $result = '1';
                    break;
                case '3':
                    $result = '2';
                    break;
                default:
                    $result = null;
                    break;
        }
        return $result;
    }
    
    /**
     * 取得订单状态
     *
     * @param    int   $type
     * @return   int
     */
    private function getOrderCheckStatus($type)
    {
        switch ($type) {
                case '0':
                    $result = null;
                    break;
                case '1':
                    $result = '0';
                    break;
                case '2':
                    $result = '1,2';
                    break;
                default:
                    $result = null;
                    break;
        }
        return $result;
    }
    
    /**
     * 生成收益报表
     *
     * @param    array  $data
     * @param    int    $uid
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
    public function getProfit($data, $uid, $page = null, $pageSize = null ,$unionType=0)
    {
        $where = "WHERE user_id='" . $uid . "'";
 
	    if ($data['from_date']) {
		    $where .= " AND add_time >=" . strtotime($data['from_date']);
		}
	    if ($data['order_sn']) {
		    $where .= " AND order_sn ='" .$data['order_sn']."'";
		}
        if ($unionType == 1 ) {
            $where .= " AND cpa_goods_type!=0 ";
             //  $where .= " AND un_type=1  AND  ( (cpa_goods_type=2 and (order_status_logistic=3 or order_status_logistic=4) ) or (cpa_goods_type=1 and  add_time < " .  mktime(0, 0, 0, date('m')-1, 1, date('Y')) . ") )";
            if($data['cpa_goods_type']){
                 $where .= " AND cpa_goods_type ='" .$data['cpa_goods_type']."'";
            }
        }
        $toDate = ($data['to_date']) ? $data['to_date'] : '';
        $toDate && $where .= " AND add_time <" . (strtotime($toDate) + 86400);
        $data['separate_type'] = $this -> getSeparateType($data['separate_type']);
        !is_null($data['separate_type']) && $where .= " AND separate_type IN(" . $data['separate_type'] . ")";
        $data['order_status'] = $this -> getOrderCheckStatus($data['order_status']);
        !is_null($data['order_status']) && $where .= " AND order_status IN(" . $data['order_status'] . ")";
       
        $content = $this -> _db -> getAffiliate($where, $page, $pageSize);
        $total = $this -> _db -> getAffiliateCount($where);
        $money = $this -> _db -> getAffiliateMoney($where);
        return array('content' => $content, 'total' => $total, 'money' => $money);
    }

    /**
     * 联盟异步读取校验数据
     *
     * @param    array  $data
     * @param    int    $uid
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
    public function getProfitSync($data, $uid)
    {
        $where = "WHERE ua.user_id='" . $uid . "'";
        $data['from_date'] && $where .= ' AND o.add_time >= ' . $data['from_date'];
        $data['to_date'] && $where .= ' AND o.add_time < ' . $data['to_date'];
        //$data['separate_type'] = $this -> getSeparateType($data['separate_type']);
        //!is_null($data['separate_type']) && $where .= " AND separate_type IN(" . $data['separate_type'] . ")";
        //$data['order_status'] = $this -> getOrderCheckStatus($data['order_status']);
        //!is_null($data['order_status']) && $where .= " AND order_status IN(" . $data['order_status'] . ")";
        
        $content = $this -> _db -> getAffiliateInfo($where);
        return $content;
    }
    /**
     * new联盟异步读取校验数据
     *
     * @param    array  $data
     * @param    int    $uid
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
    public function getUnionOrderList($data, $uid)
    {
        $where = "WHERE user_id='" . $uid . "'";
        $data['from_date'] && $where .= ' AND a.add_time >= ' . $data['from_date'];
        $data['to_date'] && $where .= ' AND  a.add_time < ' . $data['to_date'];
        $content = $this -> _db -> getUnionOrderList($where);
        return $content;
    }
    /**
     * 取得订单明细
     *
     * @param    string    $orderSn
     * @return   array
     */
    public function getOrder($orderSn)
    {
        $order = $this -> _db -> getOrder($orderSn);
		$data = $order['goods'];

        if ($data) {//此处如有修改请同步修改shop member api 订单商品数据处理
            foreach ($data as $k => $v) {
                if ($v['product_id']) {
                    $product[$v['order_batch_goods_id']] = $v;
                    $priceGoods += $v['sale_price'] * ($v['number'] - $v['return_number']);
                    $priceGoodsEq += $v['eq_price'] * ($v['number'] - $v['return_number']);
                    if (($v['number'] - $v['return_number']) > 0) {
                        $priceGoodsEq += $v['eq_price_blance'];
                    }

                    $priceGoodsAll += $v['sale_price'] * $v['number'];
                }
                $productAll[$v['order_batch_goods_id']] = $v;
            }
            foreach ($productAll as $id => $v) {
                if ($v['parent_id']) {
                    if ($productAll[$v['parent_id']]) {
                        $productAll[$v['parent_id']]['child'][] = $v;
                    }
                    unset($productAll[$id]);
                }
            }
            foreach ($productAll as $id => $v) {
                $productAll[$id]['amount'] = $v['amount'] = $v['sale_price'] * ($v['number'] - $v['return_number']);
                if ($v['child']) {
                    foreach ($v['child'] as $x => $y) {
                       $productAll[$id]['child'][$x]['amount'] = $y['amount'] = $y['sale_price'] * ($y['number'] - $y['return_number']);
                       if ($y['offers_type'] == 'buy-gift') {//商品赠品
                            unset($productAll[$id]['child'][$x]['sale_price'], $productAll[$id]['child'][$x]['amount']);
                       } else if ($y['offers_type'] == 'fixed') {//商品特价
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name']);
                       } else if ($y['offers_type'] == 'discount') {//商品折扣
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name']);
                       } else if ($y['offers_type'] == 'exclusive') {//专享
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name']);
                       } else if ($y['offers_type'] == 'buy-minus') {//商品立减
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name']);
                       }
                    }
                }
                if ($v['offers_type'] == 'order-gift') {//订单赠品
                    $productAll[$id] = array('goods_name' => '<font color=red>[订单赠品]</font>' . $v['goods_name'],
                                              'product_sn' =>  $v['product_sn'],
                                              'number' =>  $v['number'],
                                              'amount' => $v['amount']);
                }
                if ($v['child'] &&
                    ($v['offers_type'] == 'fixed-package' ||
                    $v['offers_type'] == 'choose-package')) {//定额礼包、自选礼包
                    $productAll[$id] = array('goods_name' => $v['goods_name'] . "￥{$v['amount']}",
                                          'child' => $productAll[$id]['child']);
                }
                if ($v['offers_type'] == 'minus') {//订单立减
                    //$productAll[$id] = array('goods_name' => $v['goods_name'], 'amount' => $v['amount']);
                    unset($productAll[$id]);
                    $priceMinus += abs($v['amount']);
                }
                if ($v['card_sn']) {//卡
                    //$productAll[$id] = array('goods_name' => $v['goods_name'], 'amount' => $v['amount']);
                    unset($productAll[$id]);
                    if ($v['card_type'] == 'old') {//礼券
                        $priceOld += abs($v['amount']);
                    } else if ($v['card_type'] == 'coupon') {//礼券
                        $priceCoupon += abs($v['amount']);
                    } else if ($v['card_type'] == 'gift') {//礼品卡
                        $priceGift += abs($v['amount']);
                        $giftSN = $v['card_sn'];
                        $giftName = $v['card_name'];
                        $giftType = $v['card_type'];
                        $giftGoodsID = $v['order_batch_goods_id'];
                    }
                }
                if ($v['type'] == 3) {//帐户余额
                    //$productAll[$id] = array('goods_name' => $v['goods_name'] . "￥{$v['amount']}");
                    unset($productAll[$id]);
                    $priceAccount += abs($v['amount']);
                }
                if ($v['type'] == 4) {//积分
                    //$productAll[$id] = array('goods_name' => $v['goods_name'], 'amount' => $v['amount']);
                    unset($productAll[$id]);
                    $pricePoint += abs($v['amount']);
                    $point += $v['point'];
                    $pointGoodsID = $v['order_batch_goods_id'];
                }
            }
        }
        return array('product_all' => $productAll,
                     'product' => $product,
			         'order' => $order,
                     'price_minus' => -abs($priceMinus),//订单立减
                     'price_old' => -abs($priceOld),//礼券
                     'price_coupon' => -abs($priceCoupon),//礼券
                     'price_gift' => -abs($priceGift),//礼品卡
                     'gift_goods_id' => $giftGoodsID,
                     'gift_sn' => $giftSN,//礼品卡
                     'gift_type' => $giftType,//礼品卡
                     'gift_name' => $giftName,//礼品卡
                     'price_point' => -abs($pricePoint),//积分价格
                     'point_goods_id' => $pointGoodsID,
                     'point' => $point,//积分价格
                     'price_account' => $priceAccount,//帐户余额
                     'other' => array('price_goods_all' => $priceGoodsAll,//商品总金额(包含退货商品)
                                      'price_goods' => $priceGoods,//商品总金额(不包含退货商品)
                                      'price_goods_eq' => $priceGoodsEq));//均摊了订单立减，调整金额，礼金券后的商品总金额
   
    }
    
    /**
     * 取得支付历史数据
     *
     * @param    array  $data
     * @param    int    $uid
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
    public function getPay($data, $uid, $page = null, $pageSize = null)
    {
        $where = "WHERE user_id='" . $uid . "'";
        $content = $this -> _db -> getPay($where, $page, $pageSize);
        $total = $this -> _db -> getPayCount($where);
        return array('content' => $content, 'total' => $total);
    }
    /**
     * 根据user_id获取联盟信息
     *
     * @param    int    $uid
     * @return   array
     */
    public function getUninfoType($uid)
    {
        $unionType = $this -> _db -> getUninfoType((int)$uid);
        return $unionType;
    }
    
    /**
     * 用户查看商品分成比例
     * 
     * @param int $uid
     * 
     * @return array
     */
	public function getGoodsProportion($uid, $search, $page = null, $pagesize = null ,$all = false) {
		$uid = (int)$uid;
		if($uid>0){
			$page = ((int)$page > 0) ? (int)$page : 1;
			$where .= "A.union_id = {$uid} ";
			if ($search != null ) {
				($search['goods_name'])  ? $where  .= " AND B.goods_name like '%" . $search['goods_name'] . "%' " : "";
				($search['goods_id'])  ? $where  .= " AND B.goods_id ='" . $search['goods_id'] . "'" : "";
				($search['goods_sn'])  ? $where  .= " AND B.goods_sn ='" . $search['goods_sn'] . "'" : "";
				($search['cat_id'])  ? $where  .= " AND C.cat_path like '%," . $search['cat_id'] . ",%'" : "";
				($search['price_from'])  ? $where  .= " AND B.price > '" . $search['price_from'] . "'" : "";
				($search['price_to'])  ? $where  .= " AND B.price < '" . $search['price_to'] . "'" : "";
				($search['order_by'])  ? $orderBy  = " order by B.goods_id " . $search['order_by'] : "";
			}
			return $this -> _db -> getGoodsProportion($where, $page, $pagesize ,$orderBy, $all);
		}else{
			return false;
		}
	}
}