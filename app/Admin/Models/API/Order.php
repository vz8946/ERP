<?php
class Admin_Models_API_Order
{
	private $_db = null;
	private $_product = null;

	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Order();
        $this -> _auth = Admin_Models_API_Auth :: getInstance()->getAuth();
        $this -> _product = new Admin_Models_API_Product();
        $this -> _finance = new Admin_Models_API_Finance();
        $this -> _union = new Shop_Models_API_Union();
	}

    /**
     * 限价操作人名单
     *
     */

    private $_allowJudgeList = array ('panshangqing');


    /**
     * 返回订单各个状态标签
     *
     * @param   string   $type
     * @param   int     $id
     * @return  string
     */
    public function status($type, $id)
    {
        $status = array(0 => '有效单',
                        1 => '取消单',
                        2 => '无效单',
                        3 => '渠道刷单',
                        4 => '分销单',
                        5 => '预售单');

        $status_return = array(0 => '正常单',
                               1 => '退货单');

        $status_logistic = array(0 => '未确认',
                                 1 => '已确认',
                                 2 => '待发货',
                                 3 => '已发货在途',
                                 4 => '已发货签收',
                                 5 => '已发货拒收',
                                 6 => '部分签收');

        $status_pay = array(0 => '未收款',
                            1 => '未退款',
                            2 => '已收款');
        $tmp = $$type;
        return $tmp[$id];
    }

	/**
     * 取得指定条件的地区列表
     *
     * @param   array       $where
     * @param   boolean     $includeOtherArea
     * @return  array
     */
    public function getArea($where, $includeOtherArea = false)
    {
        $area = $this -> _db -> getArea($where);
        if (is_array($area) && count($area)) {
            foreach ($area as $k => $v) {
                $data[$v['area_id']] = $v['area_name'];
            }
            $includeOtherArea && $data['-1'] = '其它区';
        }
        return $data;
    }
	/**
     * 取地区名
     *
     * @param   int     $areaID
     * @return  array
     */
    public function getAreaName($areaID)
    {
        return $this -> _db -> getAreaName($areaID);
    }
	/**
     * 取得指定条件的支付方式
     *
     * @param   array   $where
     * @return  array
     */
    public function getPayment($where = null)
    {
        $payment = $this -> _db -> getPayment($where);
        if (is_array($payment) && count($payment)) {
            foreach ($payment as $k => $v) {
                $v['config'] = unserialize($v['config']);
                $data[$v['pay_type']] = $v;
            }
        }
        return $data;
    }
	/**
     * 保存未确认订单信息
     *
     * @param   array   $data
     * @return  bool
     */
    public function saveNotConfirmInfo($batchSN, $data)
    {
		$order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
		$row = array('invoice_type' => $data['invoice_type'],
                     'invoice' => $data['invoice'],
                     'invoice_content' => $data['invoice_content'],
                    );
        $this -> _db -> updateOrder(array('order_sn' => $order['order_sn']), $row);

        if ($data['note_staff']) {
            $time = time();
            $tmp['note_staff'] = $order['note_staff'] .
                                  $this -> _auth['admin_name'] .
                                  '^' . $time .
                                  '^' . $data['note_staff'] . "\n";
        }
        if ($data['pay_type']) {
            $payment = $this -> getPayment(array('pay_type' => $data['pay_type']));
            if ($payment) {
                $payment = array_shift($payment);
                $tmp['pay_type'] = $payment['pay_type'];
                $tmp['pay_name'] = $payment['name'];
            }elseif($data['pay_type']=='cash'){
                $tmp['pay_type'] = 'cash';
                $tmp['pay_name'] = '现金支付';
            }elseif($data['pay_type']=='bank'){
                $tmp['pay_type'] = 'bank';
                $tmp['pay_name'] = '银行打款';
           }
        }
        if ($data['note_logistic']) {
            $tmp['note_logistic'] = $data['note_logistic'];
        }
        if ($data['note_print']) {
            $tmp['note_print'] = $data['note_print'];
        }
        if (isset($data['price_logistic'])) {
            $tmp['price_logistic'] = $data['price_logistic'] > 0 ? $data['price_logistic'] : 0;
        }
        if (!$order['price_order'] || $data['price_payed'] > 0) {
            $tmp['price_payed'] = $data['price_payed'] - $order['price_from_return'];
            $tmp['pay_time'] = time();
        }
        $data['unlock'] && $tmp['lock_name'] = '';

		
        return $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $tmp);
    }

    /**
     * 判断限价
     *
     * @param   string
     *
     * @return  bool
     */
    public function judgePriceLimit($batchSN)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
		$this->_error = '';
        //限价判断
        $product_apply_db = new Admin_Models_DB_ProductApply();
        $group_goods_db   = new Admin_Models_DB_GroupGoods();
        $product_db       = new Admin_Models_DB_Product();
        $product_infos = $this->_db->getOrderBatchGoodsByOrderBatchSn($batchSN);

        if (!empty($product_infos)) {
            foreach ($product_infos as $product) {
                if (!empty($product['parent_id']) || empty($product['number']) || ($product['number'] - $product['return_number']) < 1) {
                    continue;
                }

                $audit_log = array(
                    'shop_id'      => $order['shop_id'],
                    'channel_type' => 1,
                    'batch_sn'     => $order['batch_sn'],
                    'price_order'  => $order['price_order'],
                    'audit_id'     => $this -> _auth['admin_id'],
                    'audit_by'     => $this -> _auth['admin_name'],
                    'audit_ts'     => date('Y-m-d H:i:s'),
                );
                if (substr($product['product_sn'], 0, 1) == 'N') {
                    $product_params = array(
                        'shop_id'    => $order['shop_id'],
                        'type'       => '1',
                        'start_ts'   => date('Y-m-d H:i:s', $order['add_time']),
                        'product_sn' => $product['product_sn'],
                    );

                    $apply_info = $product_apply_db->getInfoByCondition($product_params);

                    if (!empty($apply_info)) {
                        if (floatval($apply_info['price_limit']) > floatval($product['avg_price'])) {     
                            if ( $this->_auth['group_id'] != '3' && $this->_auth['group_id'] != '1'  && !in_array($this->_auth['admin_name'],$this -> _allowJudgeList)) {
                                $this->_error .= "产品编码为:{$product['product_sn']}超过产品限价<br>";
                            } else {
                                $audit_log  += array(
                                    'product_id'   => $product['product_id'],
                                    'price_limit'  => $apply_info['price_limit'],
                                    'product_sn'   => $product['product_sn'],
                                    'product_type' => 1,
                                    'avg_price'    => $product['avg_price'],
                                );
                                $this->_db->addPricelimitLog($audit_log);
                            }
                        }
                    } else {
                        $product_info = $product_db->getProductInfoByProductSn($product['product_sn']);
                        if (!empty($product_info)) {
                            if ($product_info['price_limit'] > 0 && floatval($product_info['price_limit']) > floatval($product['avg_price'])) {
                                if ($this->_auth['group_id'] != '3' && $this->_auth['group_id'] != '1' && !in_array($this->_auth['admin_name'],$this -> _allowJudgeList)) {
                                    $this->_error .= "产品编码为:{$product['product_sn']}超过产品限价<br>";
                                } else {
                                    $audit_log  += array(
                                        'product_id'   => $product_info['product_id'],
                                        'price_limit'  => $product_info['price_limit'],
                                        'product_sn'   => $product_info['product_sn'],
                                        'product_type' => 1,
                                        'avg_price'    => $product['avg_price'],
                                    ); 

                                    $this->_db->addPricelimitLog($audit_log);
                                }
                            }
                        }
                    }
                } else if (substr($product['product_sn'], 0, 1) == 'G') {
                    $group_params = array(
                        'shop_id'    => $order['shop_id'],
                        'type'       => '2',
                        'start_ts'   => date('Y-m-d H:i:s', $order['add_time']),
                        'product_sn' => $product['product_sn'],
                    );

                    $apply_info = $product_apply_db->getInfoByCondition($group_params);

                    if (!empty($apply_info)) {
                        if (floatval($apply_info['price_limit']) > floatval($product['avg_price'])) {
                            if ($this->_auth['group_id'] != '3' && $this->_auth['group_id'] != '1' && !in_array($this->_auth['admin_name'],$this -> _allowJudgeList)) {
                                $this->_error .= "产品编码为:{$product['product_sn']}超过产品限价<br>";
                            } else {
                                $audit_log  += array(
                                    'product_id'   => $apply_info['product_id'],
                                    'price_limit'  => $apply_info['price_limit'],
                                    'product_sn'   => $apply_info['product_sn'],
                                    'product_type' => 2,
                                    'avg_price'    => $product['avg_price'],
                                ); 
                                $this->_db->addPricelimitLog($audit_log);
                            }
                        }
                    } else {
                        $group_info = $group_goods_db->getGroupInfoByGroupSn($product['product_sn']);
                        if ($group_info['price_limit'] > 0 && floatval($group_info['price_limit']) > floatval($product['avg_price'])) {
                            if ($this->_auth['group_id'] != '3'  && $this->_auth['group_id'] != '1' && !in_array($this->_auth['admin_name'],$this -> _allowJudgeList) ) {
                                $this->_error = "组合产品编码为:{$product['product_sn']}超过产品限价<br>";
                            } else {
                                $audit_log  += array(
                                    'product_id'   => $group_info['group_id'],
                                    'price_limit'  => $group_info['price_limit'],
                                    'product_sn'   => $group_info['group_sn'],
                                    'product_type' => 2,
                                    'avg_price'    => $product['avg_price'],
                                ); 
                                $this->_db->addPricelimitLog($audit_log);
                            }
                        }
                    }
                }
            }
        }

		if (!empty($this->_error)) {
			if (empty($order['audit_status'])) {
				$this->_db->updateOrderInfoByOrderId($order['order_batch_id'], array('audit_status' => '1'));
			}
			return false;
		}
		if (!empty($order['audit_status'])) {
			$this->_db->updateOrderInfoByOrderId($order['order_batch_id'], array('audit_status' => '0'));
		}

        return true;
    }
    /**
     * 投诉
     * @param   string      $batchSN
     * @param   string      $remark
     *
     * @return  bool
     */
    public function complain($batchSN, $remark)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $transport = new Admin_Models_DB_Transport();
        $transport -> update(array('is_complain' => 1, 'complain_time' => time()), "logistic_code='{$order['logistic_code']}' and logistic_no='{$order['logistic_no']}' and is_complain=0");
        $row = array(
                    'item_no' => $batchSN,
                    'logistic_no' => $order['logistic_no'],
                    'logistic_code' => $order['logistic_code'],
                    'logistic_status' => 5,
                    'op_time' => time(),
                    'admin_name' => $this -> _auth['admin_name'],
                    'remark' => $remark);
		    $transport -> insertTrack($row);
    }
	/**
     * 锁定订单
     *
     * @param    array    $datas
     * @param    int      $val
     * @return   array
     */
	public function lock($datas, $val)
	{
		if (is_array($datas['ids'])) {
			foreach($datas['ids'] as $batchSN){
			    $admin_name = $this -> _auth['admin_name'];
			    if ($val) {
			    	$data = array('lock_name' => $admin_name, 'hang' => 0);
			    } else {
			    	$data = array('lock_name' => '');
			    }
	    		$this -> _db -> lock($this -> _auth['admin_name'], $batchSN, $data);
			}
		}
        return true;
	}
	/**
     * 锁定订单超级权限
     *
     * @param    array    $datas
     * @param    int      $val
     * @return   array
     */
	public function superLock($datas, $val)
	{
		if (is_array($datas['ids'])) {
			foreach($datas['ids'] as $batchSN){
			    $admin_name = $this -> _auth['admin_name'];
			    if ($val) {
			    	$data = array('lock_name' => $admin_name, 'hang' => 0);
			    } else {
			    	$data = array('lock_name' => '');
			    }
	    		$this -> _db -> superLock($batchSN, $data);
			}
		}
        return true;
	}
    /**
     * 取得指定条件的订单列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getOrderBathWithPage($where=NULL, $page=1)
    {
        $data = $this -> _db -> getOrderBathWithPage($where, $page);
        if(is_array($data['data']) && count($data['data'])){
            foreach ($data['data'] as $k => $v) {
                $inBatchSN[] = $v['batch_sn'];
                $data['data'][$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $data['data'][$k]['status_value'] = $v['status'];
                $data['data'][$k]['status'] = $this -> status('status', $v['status']);
                $data['data'][$k]['status_return'] = $this -> status('status_return', $v['status_return']);
                $data['data'][$k]['status_logistic'] = $this -> status('status_logistic', $v['status_logistic']);
                $data['data'][$k]['status_pay_value'] = $v['status_pay'];
                $data['data'][$k]['status_pay'] = $this -> status('status_pay', $v['status_pay']);
                $data['data'][$k]['blance'] = round($v['price_pay'] - ($v['price_payed']+$v['account_payed']+$v['point_payed']+$v['gift_card_payed']+$v['price_from_return']), 2);
                if (strstr($v['user_name'], 'tv') !== false) {
                    $data['data'][$k]['is_tv'] = true;
                }
                if (strstr($v['user_name'], 'in_stock') !== false) {
                    $data['data'][$k]['is_in_stock'] = true;
                }
            }
            
            $data['product'] = $this -> _db -> getOrderBatchGoodsInBatchSN($inBatchSN, $where['includeOffer'], $where['includeCoupon']);
        }
        return $data;
    }
	/**
     * 根据指定的条件取批次
     *
     * @param    array    $where
     * @return   array
     */
    public function getOrderBatch($where)
    {
        $order = $this -> _db -> getOrderBatch($where);
		
        if (is_array($order) && count($order)) {
            foreach ($order as $k => $v) {
                $v['note_staff'] = trim($v['note_staff']);
                if ($v['note_staff']) {
                    $tmp = explode("\n", $v['note_staff']);
                    if (is_array($tmp) && count($tmp)) {
                        unset($noteStaff);
                        foreach ($tmp as $y) {
                            if ($y) {
                                $temp = explode('^', $y);
                                $noteStaff[$temp[1]] = array('admin_name'=>$temp[0],
                                                             'time'=>$temp[1],
                                                             'date'=>date('Y-m-d H:i:s', $temp[1]), 'content'=>$temp[2]);
                            }
                        }
                    }
                }
                $memberRanks = Custom_Config_Xml::getMemberRanks();
                foreach ($memberRanks as $key => $value) {
                    $memberAllRanks[$value['id']] = $value['name'];
                }
                $order[$k]['rank_id'] = $memberAllRanks[$v['rank_id']];
                $order[$k]['note_staff'] = $noteStaff;
                $order[$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $order[$k]['add_time_unix'] = $v['add_time'];
                $order[$k]['hang_time'] = date('Y-m-d H:i:s', $v['hang_time']);
                $order[$k]['logistic_list'] = Zend_Json::decode($v['logistic_list']);
            }
        }
        return $order;
    }
    /**
     * 添加客服备注
     *
     * @param   string      $batchSN
     * @param   string      $noteStaff
     * @return  bool
     */
    public function addNoteStaff($batchSN, $noteStaff)
    {
        $time = time();
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $data['note_staff'] = $order['note_staff'] .
                              $this -> _auth['admin_name'] .
                              '^' . $time .
                              '^' . $noteStaff . "\n";
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => $time,
                     'title' => '添加客服备注',
                     'note' => $noteStaff,
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }

	/**
     * 修改配送地址
     *
     * @param   string      $batchSN
     * @param   array      $data
     * @return  void
     */
    public function editAddress($batchSN, $data)
    {
        $data['addr_zip'] = $this -> _db -> getAreaZip($data['addr_area_id']);
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '修改配送地址',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
     }
	/**
     * 修改支付方式
     *
     * @param   string      $batchSN
     * @param   array      $data
     * @return  void
     */
    public function editPayment($batchSN, $data)
    {
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '修改支付方式',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
	/**
     * 修改订单商品
     *
     * @param    string     $batchSN
     * @param    array      $data
     * @param    string     $note
     * @param    string     $error
     * @return   bool
     */
    public function editOrderBatchGoods($batchSN, $data, $note, &$error=null)
    {
        $productAPI = new Admin_Models_API_Product();
        
        $time = time();
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $logicArea = $order['status'] == 4 ? Custom_Model_Stock_Base::getDistributionArea($order['user_name']) : 1;
        
        //api 检测是否有库存 开始
        $stockAPI = new Admin_Models_API_Stock();
        $stockAPI -> setLogicArea($logicArea);
        if ($data['old']) {
            foreach ($data['old'] as $orderBatchGoodsID => $v) {
                $product = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchGoodsID)));
                $blance = $v['number'] - $product['number'];
                if ($blance > 0 && $product['product_id']) {
                    if (!$stockAPI -> checkPreSaleProductStock($product['product_id'], $blance, true)) {
                        $error = "产品ID{$product['product_id']}库存不足<br>";
                        return false;
                    }
                }
            }
        }
        if ($data['new']) {
            foreach ($data['new'] as $productID => $v) {
                if (!$stockAPI -> checkPreSaleProductStock($productID, $v['number'], true)) {
                    $error = "产品ID{$product['product_id']}库存不足<br>";
                    return false;
                }
                
                $product = array_shift($this -> _product -> get(array('product_id' => $productID)));
                if ($product['is_vitual'] || $product['is_gift_card']) {
                    $error = "新增的产品不能包含虚拟商品或礼品卡<br>";
                    return false;
                }
            }
        }
        //api 检测是否有库存 结束
        
        if ($data['old']) {
            foreach ($data['old'] as $orderBatchGoodsID => $v) {
                $product = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchGoodsID)));
                $blance = $v['number'] - $product['number'];
                //礼包禁止修改价格 开始
                if ($product['offers_type'] == 'fixed-package' || $product['offers_type'] == 'choose-package') {
                    $v['sale_price'] = $product['sale_price'];
                }
                //礼包禁止修改价格 结束
                
                //客服判断礼券是否应该使用 开始
                if ($product['card_type'] == 'coupon') {
                    if ($v['number'] > 1) {
                        $v['number'] = 1;
                    }
                    $tmp = array('number' => $v['number']);
                    if (!$v['number']) {//释放礼券
                        if ($product['card_type'] == 'coupon') {
                            $this -> unUseCardCoupon($batchSN);
                        }
                    }
                } else {
                    $tmp = array('number' => $v['number'], 'sale_price' => $v['sale_price']);
                }
                //客服判断礼券是否应该使用 结束
                
                $where = array('batch_sn' => $batchSN, 'order_batch_goods_id' => $orderBatchGoodsID);
                $this -> _db -> updateOrderBatchGoods($where, $tmp);
                
                /*Start::更新组合商品内包含的商品数量 2012.5.9*/
                if($v['number'] != $v['former_number']){
	                if($product['type']==5){
	                	$rs = $this -> _db -> getOrderBatchGoods(array('batch_sn'=>$batchSN,'type'=>5,'parent_id'=>$orderBatchGoodsID));
	                	if(is_array($rs) && count($rs)){
	                		foreach ($rs as $index => $val){
	                			$where = array('batch_sn' => $batchSN, 'order_batch_goods_id' => $val['order_batch_goods_id']);
	                			$num = $val['number'] / $v['former_number'] * $v['number'];
	                			$this -> _db -> updateOrderBatchGoods($where, array('number' => $num));
	                		}
	                	}
	                }
                }
                /*End::更新组合商品内包含的商品数量*/
                
                if ($logicArea != 4) {
                    if ($blance > 0) {
                        //api 占有库存
                        $stockAPI -> holdSaleProductStock($product['product_id'], $blance);
                        
                        //处理礼品卡
                        if ($product['is_gift_card']) {
                            $giftCardAPI = new Admin_Models_API_GiftCard();
                            $giftCardInfo = $productAPI -> getGiftcardInfoByProductid($product['product_id']);
                            if ($giftCardInfo) {
                                $giftCardRow = array('number' => $blance,
                                                     'card_price' => $giftCardInfo['amount'],
                                                     'card_type' => 1,
                                                     'end_date' => date('Y-m-d', time() + 3600 * 24 * 365 * 3),
                                                     'order_batch_goods_id' => $orderBatchGoodsID,
                                                     'status' => 2,
                                                    );
                                $giftCardAPI -> addLog($giftCardRow);
                            }
                        }
                        
                        //处理虚拟商品
                        if ($product['is_vitual']) {
                            $vitualGooodsAPI = new Admin_Models_API_VitualGoods();
                            $vitualCardData = array();
                            if ($product['is_gift_card']) {
                                $vitualCardData = $giftCardAPI -> _cardData;
                            }
                            else {
                                for ($cardIndex = $v['former_number'] + 1; $cardIndex <= $v['number']; $cardIndex++) {
                                    $vitualCardData[] = Custom_Model_Encryption::getInstance() -> encrypt($product['product_id'].$cardIndex, 'vitualCard');
                                }
                            }
                            foreach ($vitualCardData as $card) {
                                $vitualGiftCard = array('type' => $product['is_gift_card'] ? 2 : 1,
                                                        'product_id' => $product['product_id'],
                                                        'order_batch_goods_id' => $orderBatchGoodsID,
                                                        'sn' => $card['sn'],
                                                        'pwd' => $card['pwd'],
                                                        'user_id' => $this -> _auth['admin_id'],
                                                        'user_name' => $this -> _auth['admin_name'],
                                                        'status' => 1,
                                                        'deliver_time' => time(),
                                                        'add_time' => time(), 
                                                       );
                                $vitualGooodsAPI -> add($vitualGiftCard);
                            }
                        }
                    }
                    else if ($blance < 0) {
                        //api 释放库存
                        $stockAPI -> releaseSaleProductStock($product['product_id'], abs($blance));
                        
                        //处理虚拟商品
                        $delCardSnArray = array();
                        if ($product['is_vitual']) {
                            $vitualGooodsAPI = new Admin_Models_API_VitualGoods();
                            $vitualGoodsList = $vitualGooodsAPI -> getVitualGoods(array('order_batch_goods_id' => $orderBatchGoodsID, 'status' => 1), null, null, 'vitual_id desc');
                            if ($vitualGoodsList) {
                                for ($i = 0; $i < abs($blance); $i++) {
                                    $vitualGoods = $vitualGoodsList[count($vitualGoodsList) - $i - 1];
                                    if ($vitualGoods) {
                                        $vitualGooodsAPI -> remove($vitualGoods['sn']);
                                        $delCardSnArray[] = $vitualGoods['sn'];
                                    }
                                    else {
                                        break;
                                    }
                                }
                            }
                        }
                        
                        //处理礼品卡
                        if ($product['is_gift_card']) {
                            $giftCardAPI = new Admin_Models_API_GiftCard();
                            if (count($delCardSnArray) == 0) {
                                $giftCardList = array_shift($giftCardAPI -> getCardlist(array('order_batch_goods_id' => $orderBatchGoodsID, 'status' => 2)));
                                if ($giftCardList) {
                                    for ($i = 0; $i < abs($blance); $i++) {
                                        $card = $giftCardList[count($giftCardList) - $i - 1];
                                        if ($card) {
                                            //$giftCardAPI -> updateCard("card_sn = '{$card['card_sn']}'", array('status' => 1));
                                            $giftCardAPI -> deleteCard("card_sn = '{$card['card_sn']}'");
                                        }
                                        else {
                                            break;
                                        }
                                    }
                                }
                            }
                            else {
                                foreach ($delCardSnArray as $cardSN) {
                                    //$giftCardAPI -> updateCard("card_sn = '{$cardSN}'", array('status' => 1));
                                    $giftCardAPI -> deleteCard("card_sn = '{$cardSN}'");
                                }
                            }
                        }
                    }
                }
                if ($product['sale_price'] != $v['sale_price']) {//商品价格修改日志
                    $log = array('order_sn' => $order['order_sn'],
                                 'type' => 1,//1未确认订单修改商品、2订单恢复修改商品、3换货修改商品
                                 'batch_sn' => $order['batch_sn'],
                                 'order_batch_goods_id' => $product['order_batch_goods_id'],
                                 'product_sn' => $product['product_sn'],
                                 'number' => intval($v['number']),
                                 'sale_price' => floatval($product['sale_price']),
                                 'edit_price' => floatval($v['sale_price']),
                                 'admin_name' => $this -> _auth['admin_name'],
                                 'note' => '[修改已经存在的商品价格]' . $note,
                                 'add_time' => $time);
                    $this -> _db -> addOrderBatchGoodsLog($log);
                }
            }
        }

        if ($data['new']) {
            foreach ($data['new'] as $productID => $v) {
                $product = array_shift($this -> _product -> get(array('product_id' => $productID)));
                $tmp = array('order_id' => $order['order_id'],
                             'order_batch_id' => $order['order_batch_id'],
                             'order_sn' => $order['order_sn'],
                             'batch_sn' => $order['batch_sn'],
                             'add_time' => $order['add_time'],
                             'product_id' => $product['product_id'],
                             'product_sn' => $product['product_sn'],
                             'goods_name' => $product['product_name'],
                             'goods_style' => $product['goods_style'],
                             'cat_id' => $product['cat_id'],
                             'cat_name' => $product['cat_name'],
                             'weight' => $product['p_weight'],
                             'length' => $product['p_length'],
                             'width' => $product['p_width'],
                             'height' => $product['p_height'],
                             //'price' => $product['price'],
                             'sale_price' => $v['sale_price'],
                             'cost'       => $product['cost'],
                             'number' => $v['number']);
                $orderBatchGoodsID = $this -> _db -> addOrderBatchGoods($tmp);
                //api 占有库存
                if ($logicArea != 4) {
                    $stockAPI -> holdSaleProductStock($productID, $v['number']);
                }
                
                if ($product['price'] != $v['sale_price']) {//商品价格修改日志
                    $log = array('order_sn' => $order['order_sn'],
                                 'type' => 1,//1未确认订单修改商品、2订单恢复修改商品、3换货修改商品
                                 'batch_sn' => $order['batch_sn'],
                                 'order_batch_goods_id' => $orderBatchGoodsID,
                                 'product_sn' => $product['product_sn'],
                                 'number' => intval($v['number']),
                                 'sale_price' => floatval($product['price']),
                                 'edit_price' => floatval($v['sale_price']),
                                 'admin_name' => $this -> _auth['admin_name'],
                                 'note' => '[修改新增加的商品价格]' . $note,
                                 'add_time' => $time);
                    $this -> _db -> addOrderBatchGoodsLog($log);
                }

            }
        }

        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '修改订单商品',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        //更新支付状态
        $this -> orderDetail($batchSN);
        //插入联盟数据
        $this -> union($batchSN);
    }

    /**
     * 订单挂起
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function hang($batchSN)
    {
        $data = array('lock_name' => '',
                      'hang' => 1,
                      'hang_admin_name' => $this -> _auth['admin_name'],
                      'hang_time' => time());
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '订单挂起',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
    /**
     * 订单无效
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function invalid($batchSN)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if ($order['status'] != 0) {//防止重复提交 0 正常单; 1 取消单; 2 无效单;
            return false;
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN),
                                         array('status' => 2,'lock_name' => ''));
	    $product = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0));
        if (is_array($product) && count($product)) {
            $stockAPI = new Admin_Models_API_Stock();
            foreach($product as $k => $v) {
                //api 释放库存
                $stockAPI -> releaseSaleProductStock($v['product_id'], $v['number']);
            }
        }

        $replenish_db   = new Admin_Models_API_Replenishment();
        $replenish_info = $replenish_db->cancelOrderReplenish($order['order_batch_id'], 2);

        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '无效订单',
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);

        //api 插入联盟数据
        $this -> union($batchSN);
        //api 抵用券接口（整退）
        $this -> unUseCardCoupon($batchSN);
    }
    /**
     * 订单确认
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function confirm($batchSN)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if ($order['status_logistic'] != 0) {//防止重复确认订单
            return 'repeat';
        }
        //更新支付状态
        $orderDetail = $this -> orderDetail($batchSN);
        
        //换货单不能有应付金额
        if ($order['parent_batch_sn'] && $orderDetail['other']['price_must_pay'] > 0) {
            return 'exchangeOrdereError';
        }
        
        //包启礼品卡和正常商品不能选择在线支付
        $hasGiftCard = false;
        $onlyGiftCard = true;
        if ($orderDetail['order']['pay_type'] != 'cod') {
            foreach ($orderDetail['product_all'] as $product) {
                if ($product['product_id'] > 0) {
                    if ($product['is_gift_card']) {
                        $hasGiftCard = true;
                    }
                    else {
                        $onlyGiftCard = false;
                    }
                }
            }
            if ($hasGiftCard && !$onlyGiftCard) {
                return 'giftCardError';
            }
        }
        
        if ($order['pay_type'] == 'cod' || $order['status_pay'] > 0  || $order['user_name']=='credit_channel' || $order['user_name']=='distribution_channel') {//货到付款 或者 已结清、未退款 赊销 直供
            $statusLogistic = 2;//待发货
            $title = '订单确认 待发货';
        } else {
            $statusLogistic = 1;//已确认待收款
            $title = '已确认 待收款';
        }
        
        $set = array('status_logistic' => $statusLogistic, 'lock_name' => '');
        
        if ($order['status'] == 4) {
            $title = '订单确认 已签收';
            //api 申请渠道销售出库单出库
            if (!$this -> virtualOut($batchSN)) {//出库失败
                return 'outFail';
            }
            
            $set['status_logistic'] = 4;//已签收
            //$set['logistic_time'] = $order['add_time'];
            $set['logistic_time'] = time();
            
            //添加应收款记录
            $financeAPI = new Admin_Models_API_Finance();
            $receiveData = array('batch_sn' => $batchSN,
                                 'type' => 2,
                                 'pay_type' => $order['pay_type'],
                                 'amount' => $order['price_order'],
                                );
            $financeAPI -> addFinanceReceivable($receiveData);
        }
        
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
        
        if ($order['status'] == 0) {
            //虚拟商品发货
            $vitualGoodsAPI = new Admin_Models_API_VitualGoods();
            $vitualGoodsAPI -> sendByBatchSN($batchSN, false);
        }
        
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => $title,
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);

		/*
		$_SERVER['SERVER_NAME'] = strtolower($_SERVER['SERVER_NAME']);

		if($_SERVER['SERVER_NAME']== "jkerp.1jiankang.com" && $order['addr_mobile']){
			$msg ='尊敬的'.$order['addr_consignee'].',您的订单'.$order['order_sn'].'已成功确认。如有问题，请咨询400603883';
			//发送确认短信
			$sms= new  Custom_Model_Sms();
			$response =   $sms->send($order['addr_mobile'],$msg);
		}
		*/
    }
    /**
     * 确认收款
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function hasPay($batchSN,$pay_money='0.00')
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        //网关支付不允许手动收款
        substr($order['pay_type'], 0 ,6) == 'alipay' && $order['pay_type'] = 'alipay';
        if (in_array($order['pay_type'], array('alipay', 'tenpay', 'phonepay', 'bankcomm'))) {
            return 'can_not_pay_manually';
        }
        
        if($order['part_pay']=='1'){
            if($pay_money > 1 ){
                $pricePayed = floatval($order['price_payed'] + $pay_money);//更改收款金额
                $pay = $pay_money;//支付log记录本次付款金额
                if($order['status_logistic']< 2 ){
                    $status_logistic = '2';
                }else{
                    $status_logistic = $order['status_logistic'];
                }
            }else{
                return 'no_pay_money';
            }
        }else{
            if ($order['status_logistic'] != 1) {//防止重复确认收款
                return 'repeat';
            }

            $pricePayed = floatval($order['price_pay']) - floatval($order['account_payed']) - floatval($order['point_payed']) - floatval($order['gift_card_payed']) - floatval($order['price_from_return']);//更改收款金额
            $pay = $order['price_pay'] - $order['price_from_return'] - $order['price_payed'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'];//支付log记录本次付款金额
            $status_logistic='2';
        }
        
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN),
                                         array('status_logistic' => $status_logistic,
                                               'price_payed' => $pricePayed,
                                               'pay_time' => time(),
                                               'lock_name' => ''));
        //添加支付log记录
        $this -> addOrderPayLog(array('batch_sn' => $batchSN, 'pay_type' =>'service', 'pay' => $pay));
        
        //添加应收款记录
        $financeAPI = new Admin_Models_API_Finance();
        $receiveData = array('batch_sn' => $batchSN,
                             'pay_type' => $order['pay_type'],
                             'amount' => floatval($order['price_pay']) - floatval($order['account_payed']) - floatval($order['point_payed']) - floatval($order['gift_card_payed']) - floatval($order['price_from_return']),
                            );
        if (in_array($order['pay_type'], array('bank', 'cash'))) {
            $receiveData['type'] = 2;
        }
        else if ($order['pay_type'] == 'external' || $order['pay_type'] == 'externalself') {
            $receiveData['type'] = 3;
        }
        $financeAPI -> addFinanceReceivable($receiveData);
        
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '确认收款 待发货 确认金额 : '.$pay ,
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        //更新订单金额状态
        $this -> orderDetail($batchSN);
        
        //虚拟商品发货
        $vitualGoodsAPI = new Admin_Models_API_VitualGoods();
        $vitualGoodsAPI -> sendByBatchSN($batchSN);
    }
    /**
     * 确认收款订单申请返回
     *
     * @param   string      $batchSN
     * @param   array       $data
     * @return  void
     */
    public function confirmBack($batchSN, $data)
    {
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '代收款订单 申请返回',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
    /**
     * 确认收款订单取消
     *
     * @param   string      $batchSN
     * @param   string      $note
     * @return  void
     */
    public function confirmCancel($batchSN, $note=null)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
		if ($order['status'] != 0 && $order['status'] != 4) {//防止重复提交
            return false;
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN),
                                         array('status' => 1, 'lock_name' => ''));

        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '订单取消' . $note,
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        

        // @zhouyong 先作废补货单
        $replenish_db   = new Admin_Models_API_Replenishment();
        $replenish_info = $replenish_db->cancelOrderReplenish($order['order_batch_id'], 2);
        if ($order['status'] == 4)  return true;

	    $product = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0));
        if (is_array($product) && count($product)) {
            $stockAPI = new Admin_Models_API_Stock();
            foreach($product as $k => $v) {
                //api 释放库存
                $stockAPI -> releaseSaleProductStock($v['product_id'], $v['number']);
                
                //作废虚拟商品
                if ($v['is_vitual']) {
                    if (!$this -> vitualGoodsAPI) {
                        $this -> vitualGoodsAPI = new Admin_Models_API_VitualGoods();
                    }
                    $this -> vitualGoodsAPI -> cancelByBatchSN($batchSN, $v['product_id'], $v['number']);
                }
            }
        }
        
        //api 插入联盟数据
        $this -> union($batchSN);
        
        //没有现金退款，直接退接口
        if ($order['price_payed'] + $order['price_from_return'] == 0) {
            //api 抵用券接口(整退)
            $this -> unUseCardCoupon($batchSN);
            //api 积分接口(整退)
            $this -> unPointPrice($batchSN);
            //api 余额接口(整退)
            $this -> unAccountPrice($batchSN);
        }
        
        $shopConfig = Zend_Registry::get('shopConfig');

	    /*
        if($order['user_id']!=$shopConfig['fast_track_id']){
		   	$this -> orderCancelEmail($order['user_name'], $batchSN);
        }
        else{
        	if($order['addr_mobile'] && Custom_Model_Check::isMobile($order['addr_mobile']) && $_SERVER['SERVER_NAME']== "jkerp.1jiankang.com"){
				$sms = new  Custom_Model_Sms();
                $response = $sms -> sendSms($order['addr_mobile'],"您的订单已经取消，订单号：".$batchSN."  www.1jiankang.com");
			}
        }
	    */

        return true;
    }
    /**
     * 确认收款订单批量取消
     *
     * @param   array      $data
     * @return  void
     */
    public function batchCancel($data)
    {
        if ($data) {
            foreach ($data as $batchSN) {
                $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
                if ($order['lock_name'] !== $this -> _auth['admin_name']) {//需要先锁定才能 取消
                    return false;
                } else if ($order['price_payed'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed']> 0) {//需要退款的订单 禁止批量取消
                    return false;
                }
                $this -> confirmCancel($batchSN);
            }
        }
    }
    /**
     * 待发货订单申请返回
     *
     * @param   string      $batchSN
     * @param   string      $noteStaff
     * @return  void
     */
    public function toBeShippingBack($batchSN, $noteStaff = null)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if ($order['status_back'] != 0) {//防止重复提交 status_back 0 默认，1 申请取消，2 申请返回
            return false;
        }
        if ($noteStaff) {
            $time = time();
            $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
            $data['note_staff'] = $order['note_staff'] .
                                  $this -> _auth['admin_name'] .
                                  '^' . $time .
                                  '^' . $noteStaff . "\n";
        }
        $data['status_back'] = 2;
        $data['lock_name'] = '';

        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);

        //api 申请出库返回
        $outStock = new Admin_Models_API_OutStock();
        $result = $outStock -> cancelApi($batchSN, $noteStaff, 'back');
        if (!$result) {//解决物流验证不通过，订单主动返回 异常情况下特殊处理
            $this -> back($batchSN, array('is_check' => 1));
        }
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '待发货订单 申请返回',
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
    /**
     * 待发货订单申请取消
     *
     * @param   string      $batchSN
     * @param   string      $noteStaff
     * @return  void
     */
    public function toBeShippingCancel($batchSN, $noteStaff = null)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if ($order['status_back'] != 0) {//防止重复提交 status_back 0 默认，1 申请取消，2 申请返回
            return false;
        }
        if ($noteStaff) {
            $time = time();
            $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
            $data['note_staff'] = $order['note_staff'] .
                                  $this -> _auth['admin_name'] .
                                  '^' . $time .
                                  '^' . $noteStaff . "\n";
        }
        $data['status_back'] = 1;
        $data['lock_name'] = '';
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
        //api 申请出库取消
        $outStock = new Admin_Models_API_OutStock();
        $result = $outStock -> cancelApi($batchSN, $noteStaff);
        if (!$result) {
            $this -> back($batchSN, array('is_check' => 1));
        }
        
        //没有现金退款，直接退接口
        if ($order['price_payed'] + $order['price_from_return'] == 0) {
            //api 抵用券接口(整退)
            $this -> unUseCardCoupon($batchSN);
            //api 积分接口(整退)
            $this -> unPointPrice($batchSN);
            //api 余额接口(整退)
            $this -> unAccountPrice($batchSN);
        }
        
        //api 插入联盟数据
        $this -> union($batchSN);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '待发货订单 申请取消',
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        return true;
    }

    /**
     * 客服微调价格
     * @param   string      $batchSN
     * @param   array      $data
     * @return  void
     */
    public function addPriceAdjust($batchSN, $data)
    {
        $time = time();
        //添加订单调整金额日志
        $adjust = array('order_sn' => array_shift(explode('_', $batchSN)),
                        'batch_sn' => $batchSN,
                        'type' => $data['type'],
                        'money' => $data['money'],
                        'note' => $data['note'],
                        'add_time' => $time);
        $this -> _db -> addOrderBatchAdjust($adjust);
        //添加订单日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => $time,
                     'title' => '客服调整金额[￥' . $data['money'] . ']',
                     'note' => $data['note'],
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        //更新支付状态
        $this -> orderDetail($batchSN);
        //插入联盟数据
        $this -> union($batchSN);
    }
    /**
     * 恢复订单
     * @param   string      $batchSN
     * @param   array       $data
     * @param   string      $note
     * @param   string      $error
     * @return  void
     */
    public function undo($batchSN, $data, $note, &$error = null)
    {
        $time = time();
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        //api 检测是否有库存 开始
        $stockAPI = new Admin_Models_API_Stock();
        if ($data['old']) {
            foreach ($data['old'] as $orderBatchGoodsID => $v) {
                $product = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchGoodsID)));
                if (!$stockAPI -> checkPreSaleProductStock($product['product_id'], $v['number'])) {
                    $error = "{$v['goods_name']}[{$v['product_sn']}]库存不足<br>";
                    return false;
                }
            }
        }
        if ($data['new']) {
            foreach ($data['new'] as $productID => $v) {
                if (!$stockAPI -> checkPreSaleProductStock($productID, $v['number'])) {
                    $error = "产品ID{$productID}]库存不足<br>";
                    return false;
                }
            }
        }
        //api 检测是否有库存 结束

        if ($data['old']) {
            foreach ($data['old'] as $orderBatchGoodsID => $v) {
                $product = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchGoodsID)));
                $blance = $v['number'] - $product['number'];
                $tmp = array('number' => $v['number'], 'sale_price' => $v['sale_price']);
                $where = array('batch_sn' => $batchSN, 'order_batch_goods_id' => $orderBatchGoodsID);
                $this -> _db -> updateOrderBatchGoods($where, $tmp);
                //api 占有库存
                $stockAPI -> holdSaleProductStock($product['product_id'], $v['number']);
                
                //处理虚拟商品
                if ($product['is_vitual']) {
                    if (!$this -> vitualGoodsAPI) {
                        $this -> vitualGoodsAPI = new Admin_Models_API_VitualGoods();
                    }
                    $this -> vitualGoodsAPI -> undoByBatchSN($batchSN, $product['product_id'], $product['number']);
                }
                
                if ($product['sale_price'] != $v['sale_price']) {//商品价格修改日志
                    $log = array('order_sn' => $order['order_sn'],
                                 'type' => 2,//1未确认订单修改商品、2订单恢复修改商品、3换货修改商品
                                 'batch_sn' => $order['batch_sn'],
                                 'order_batch_goods_id' => $product['order_batch_goods_id'],
                                 'product_sn' => $product['product_sn'],
                                 'number' => $v['number'],
                                 'sale_price' => $product['sale_price'],
                                 'edit_price' => $v['sale_price'],
                                 'admin_name' => $this -> _auth['admin_name'],
                                 'note' => '[修改已经存在的商品价格]' . $note,
                                 'add_time' => $time);
                    $this -> _db -> addOrderBatchGoodsLog($log);
                }
            }
        }

        if ($data['new']) {
            foreach ($data['new'] as $productID => $v) {
                $product = array_shift($this -> _product -> get(array('product_id' => $productID)));
                $tmp = array('order_id' => $order['order_id'],
                             'order_batch_id' => $order['order_batch_id'],
                             'order_sn' => $order['order_sn'],
                             'batch_sn' => $order['batch_sn'],
                             'add_time' => $order['add_time'],
                             'product_id' => $product['product_id'],
                             'product_sn' => $product['product_sn'],
                             'goods_id' => $product['goods_id'],
                             'goods_name' => $product['goods_name'],
                             'cat_id' => $product['cat_id'],
                             'cat_name' => $product['cat_name'],
                             'weight' => $product['p_weight'],
                             'length' => $product['p_length'],
                             'width' => $product['p_width'],
                             'height' => $product['p_height'],
                             'price' => $product['price'],
                             'sale_price' => $v['sale_price'],
                             'number' => $v['number']);
                $orderBatchGoodsID = $this -> _db -> addOrderBatchGoods($tmp);
                //api 占有库存
                $stockAPI -> holdSaleProductStock($productID, $v['number']);

                if ($product['price'] != $v['sale_price']) {//商品价格修改日志
                    $log = array('order_sn' => $order['order_sn'],
                                 'type' => 2,//1未确认订单修改商品、2订单恢复修改商品、3换货修改商品
                                 'batch_sn' => $order['batch_sn'],
                                 'order_batch_goods_id' => $orderBatchGoodsID,
                                 'product_sn' => $product['product_sn'],
                                 'number' => $v['number'],
                                 'sale_price' => $product['price'],
                                 'edit_price' => $v['sale_price'],
                                 'admin_name' => $this -> _auth['admin_name'],
                                 'note' => '[修改新增加的商品价格]' . $note,
                                 'add_time' => $time);
                    $this -> _db -> addOrderBatchGoodsLog($log);
                }
            }
        }
        $tmp = array('status' => 0, 'status_logistic' =>0, 'lock_name' => '');
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $tmp);
        //如果有优惠券，先判断优惠券的状态，如果是已使用，则将优惠券订单商品的数量设为0，否则修改优惠券的状态为1
        $coupon_goods = array_shift($this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'card_sn_is_not_null' => '1')));
        if ( $coupon_goods ) {
            if($coupon_goods['card_type']== 'coupon'){
                $coupon_info = $this -> _db -> getCouponInfo( $coupon_goods['card_sn'] );
                if ( ($coupon_info['is_repeat'] == 0) && ($coupon_info['status'] == 1) ) {
                    $this -> _db -> updateOrderBatchGoods(array('order_batch_goods_id' => $coupon_goods['order_batch_goods_id']), array('number' => 0));
                    //如果该优惠券有运费减免，则重新计算运费(如果活动中也有运费减免，结果会不准确，需要客服手工调整运费)
                    if ( $coupon_info['freight'] && (($order['price_logistic'] + $coupon_info['freight']) == 10) ) {
                        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('price_logistic' => $order['price_logistic'] + $coupon_info['freight']));
                    }
                }
                else{
                    $this -> _db -> setCouponStatus( $coupon_goods['card_sn'], 1 );
                    $this -> _db -> updateOrderBatchGoods(array('order_batch_goods_id' => $coupon_goods['order_batch_goods_id']), array('number' => 1));
                }
            }elseif($coupon_goods['card_type']== 'gift'){
                    $cardObj = new Admin_Models_API_GiftCard();
                    $gift = $cardObj -> getGiftInfo(array('card_sn' => $coupon_goods['card_sn']));
                    if($gift['card_real_price']>abs($coupon_goods['sale_price']) &&  $gift['end_date'] > date('Y-m-d') ){
                        $temp= $cardObj -> useGiftCard(array('card_sn' => $coupon_goods['card_sn'],'card_pwd' => $gift['card_pwd'], 'card_type' => $gift['card_type'], 'user_id' => $gift['user_id'],  'user_name' => $gift['user_name'], 'card_price' => abs($coupon_goods['sale_price']), 'add_time' => time()));
                        if($temp){
                             $data = array('number' => 1);
                        }else{
                            $data = array('number' => 0);
                        }
                    }else{
                        $data = array('number' => 0);
                    }
                    $where = array('order_batch_goods_id' => $v['order_batch_goods_id']);
                    $data = array('number' => 1);
                    $this -> _db -> updateOrderBatchGoods($where, $data);
            }
        }

        //还原订单冻结财务信息
        $this -> undoFinance($batchSN);
        //添加日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '恢复订单',
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        //更新支付状态
        $this -> orderDetail($batchSN);
        //插入联盟数据
        $this -> union($batchSN);
    }
    /**
     * 下单
     *
     * @param   string      $type
     * @param   array      $add
     * @return  void
     */
    public function add($type, $add, $addg, $provinceID, $cityID, $areaID, &$error=null ,$giftbywho=null ,$addr_consignee=null,$addr_address=null,$addr_tel=null,$addr_mobile=null,$order_payment=null,$priceLogistic=null,$shopID=null,$externalOrderSN=null,$orderTime=null,$note=null,$logistics_type=null,$part_pay='0',$try_order_id=0,$user_id=0,$user_name='',$note_print=null,$note_logistic=null,$distribution_type=null,$distribution_shop_id=null)
    {
        if (!$addr_consignee) {
            $error = '请填写收货人';
            return false;
        }
        if (!$addr_tel && !$addr_mobile) {
            $error = '请填写联系电话或手机';
            return false;
        }
        if ($logistics_type != 'self' && $logistics_type != 'externalself') {
            if (!$provinceID) {
                $error = '请选择省份';
                return false;
            } else if (!$cityID) {
                $error = '请选择城市';
                return false;
            } else if (!$areaID) {
                $error = '请选择地区';
                return false;
            }
            else if (!$addr_address) {
                $error = '请填写地址';
                return false;
            }
        }
        if (!$order_payment) {
            $error = '请选择付款方式';
            return false;
        }
        if (!$type) {
            $error = '请选择下单类型';
            return false;
        }
        if (!$add && !$addg) {
            $error = '请添加商品';
            return false;
        }
        if ($shopID && $externalOrderSN) {
            if ($this -> _db -> getOrderMain("shop_id = '{$shopID}' and external_order_sn = '{$externalOrderSN}'", 1)) {
                $error = '渠道订单号已存在';
                return false;
            }
        }
        
        if ($type == 'distribution_channel' && !$distribution_shop_id) {
            $error = '请选择直供渠道';
            return false;
        }
        
        if ($addg) {
            $groupGoodsAPI = new Admin_Models_API_GroupGoods();
        }
        if($$type == 'gift' && $giftbywho == null){
            $error = '请填写赠送人姓名';
            return false;
        }
        if (in_array($type, array('b2c', 'in_call', 'out_call', 'tq_call'))) {
            if ($user_name) {
                $memberAPI = new Admin_Models_API_Member();
                if ($member = $memberAPI -> getMemberByUserName($user_name)) {
                    $userName = $user_name;
                    $userID = $member['user_id'];
                }
                else {
                    $error = '前台账号不存在';
                    return false;
                }
            }
        }
        
        if ($type == 'gift') {
            $priceLogistic = 0;
            $userID = 1;
            $userName = $type;
            $type = 5;
            $title = '赠送下单';
        }
        else if ($type == 'other') {
            $userID = 2;
            $userName = $type;
            $type = 15;
            $title = '其它下单';
        }
        else if ($type == 'internal') {
            $userID = 8;
            $userName = $type;
            $type = 7;
            $title = '内购下单';
        }
        else if ($type == 'b2c') {
            if (!$userID) {
                $userID = 9;
                $userName = 'jiankang';
            }
            $type = 0;
            $title = '官网下单';
            $shopID = 1;
        }
        else if ($type == 'yumi_jiankang') {
            $userID = 6;
            $userName = $type;
            $title = '玉米网下单';
            $type = 0;
			$shopID = 1;
        }
        else if ($type == 'xinjing_jiankang') {
            $userID = 7;
            $userName = $type;
            $title = '信景下单';
            $type = 0;
			$shopID = 1;
        }
        else if ($type == 'tq_call' || $type == 'in_call' || $type == 'out_call') {
            if (!$userID) {
                $userID = 4;
                $userName = 'call';
            }
            if ($type == 'in_call') {
                $type = 10;
            }
            else if ($type == 'out_call') {
                $type = 11;
            }
            else if ($type == 'tq_call') {
                $type = 12;
            }
            $title = '呼叫中心下单';
            $shopID = 2;
        }
        else if ($type == 'external') {
            $type = 13;
            $userID = 3;
            $userName = 'channel';
            $title = '渠道下单';
        }
        else if ($type == 'external_renew') {
            $type = 14;
            $userID = 3;
            $userName = 'channel';
            $title = '渠道补单';
        }
        else if ($type == 'batch_channel') {
            $type = 14;
            $userID = 3;
            $userName = 'batch_channel';
            $title = '购销下单';
        }
        else if ($type == 'credit_channel') {
            $type = 14;
            $userID = 3;
            $userName = 'credit_channel';
            $title = '赊销下单';
        }
        else if ($type == 'try') {
            $type = 0;
            $userID = $user_id;
            $userName = $user_name;
            $title = '试用订单';
            $shopID = 1;
            $source = 4;
        }
        else if ($type == 'distribution_channel') {
            $userID = 10;
            $userName = $type;
            $type = 16;
            $shopID = $distribution_shop_id;
            $title = '渠道直供';
        }
        else if (substr($type, -13) == '_distribution') {
            $userID = 10;
            $userName = $type;
            $type = 18;
            $title = '渠道分销';
            $distributionType = $distribution_type;
        }
        else {
            return false;//todo 异常处理
        }
        
        $stockAPI = new Admin_Models_API_Stock();
        //api 检测是否有库存 开始
        if ($userID != 10 && $type != 13) {
        //if ($type != 13) {
            if ($add) {
                foreach ($add as $productID => $v) {
                    if (!$stockAPI -> checkPreSaleProductStock($productID, $v['number'], true)) {
                        $error .= "产品ID{$productID}库存不足<br>";
                        return false;
                    }
                    $priceGoods += $v['sale_price']* $v['number'];
                    $numberGoods += $v['number'];
                }
            }
            if ($addg) {
                foreach($addg as $goodsID => $v) {
                    $data = $groupGoodsAPI -> getGroupgoogs("group_id = '{$goodsID}'");
                    $groupGoods = $groupGoodsAPI -> fetchConfigGoods(array('group_goods_config' => $data['group_goods_config']));
                    $tempProduct = '';
                    $priceGoods += $v['sale_price'] * $v['number'];
                    foreach ($groupGoods as $goods) {
                        $tempProduct[$goods['product_id']] += $goods['number'] * $v['number'];
                        $groupnum+=$v['number'];
                    }
                    foreach ($tempProduct as $productID => $number) {
                        if (!$stockAPI -> checkPreSaleProductStock($productID, $number, true)) {
                            $error .= "产品ID{$productID}库存不足<br>";
                            return false;
                        }
                    }
                }
            }
            if (($numberGoods + $groupnum) < 1){
                $error .= "下单产品数量必须大于1<br>";
                return false;
            }
        }
        else {
            if ($add) {
                foreach ($add as $productID => $v) {
                    $priceGoods += $v['sale_price'] * $v['number'];
                    $add[$productID]['sale_price'] = round($add[$productID]['sale_price'], 2);
                }
                $priceGoods = round($priceGoods, 2);
            }
            if ($addg) {
                foreach ($addg as $goodsID => $v) {
                    $priceGoods += $v['sale_price'] * $v['number'];
                    $addg[$goodsID]['sale_price'] = round($addg[$goodsID]['sale_price'], 2);
                }
                $priceGoods = round($priceGoods, 2);
            }
        }
        //api 检测是否有库存 结束
        
        $pricePay = $priceOrder = $priceGoods + $priceLogistic;
        
        $pay_type = '';
        $pay_name = '';
        if ($order_payment) {
            $payment = explode('|', $order_payment);
            $pay_type = $payment['0'];
            $pay_name = $payment['1'];
        }
        
        if ($orderTime) {
            $time = strtotime($orderTime);
        }
        else    $time = time();
        
        $orderSN = Custom_Model_CreateSn::createSn();
        
        if ($this -> _db -> getOrderMain("order_sn = {$orderSN}", '1')) {
            return false;
        }

        $vitualCardData = '';
        
        //礼品卡/虚拟商品
        if ($add) {
            $giftCardArray = '';
            $hasVitual = false;
            $onlyGiftCard = true;
            foreach ($add as $productID => $v) {
                $productData[$productID] = array_shift($this -> _product -> get(array('product_id' => $productID)));
                if ($productData[$productID]['is_vitual']) {
                    $hasVitual = true;
                }
                
                if ($productData[$productID]['is_gift_card']) {
                    $giftCardInfo = $this -> _product -> getGiftcardInfoByProductid($productID);
                    if ($giftCardInfo) {
                        $giftCardArray[] = array('info' => $giftCardInfo,
                                                 'number' => $v['number']
                                                );
                    }
                    else {
                        $error .= "商品编码{$giftCardInfo['product_sn']}的礼品卡没有设定面值<br>";
                        return false;
                    }
                }
                else {
                    $onlyGiftCard = false;
                }
            }
            
            if ($hasVitual) {
                if (!$addr_mobile) {
                    $error .= "包含虚拟商品，手机号码必须填写<br>";
                    return false;
                }
                $sms_no = $addr_mobile;
            }
            
            if ($giftCardArray) {
                if ($pay_type != 'cod' && !$onlyGiftCard) {
                    $error .= "在线支付的礼品卡中不能包含其它商品<br>";
                    return false;
                }
                
                $giftCardAPI = new Admin_Models_API_GiftCard();
                //生成礼品卡，状态未激活
                foreach ($giftCardArray as $giftCard) {
                    $giftCardRow[$giftCard['info']['product_id']] = array('number' => $giftCard['number'],
                                                     'card_price' => $giftCard['info']['amount'],
                                                     'card_type' => 1,
                                                     'end_date' => date('Y-m-d', time() + 3600 * 24 * 365 * 3),
                                                     'order_batch_goods_id' => 0,
                                                     'status' => 2,
                                                    );
                }
            }
        }

        $orderID = $this -> _db -> addOrder(array('order_sn' => $orderSN,
                                                  'batch_sn' => $orderSN,
                                                  'add_time' => $time,
                                                  'user_id' => $userID,
                                                  'user_name' => $userName,
                                                  'giftbywho' => $giftbywho,
                                                  'part_pay' => $part_pay,
                                                  'try_order_id' => $try_id,
        					                      'shop_id' => $shopID ? $shopID : 0,
        				                          'external_order_sn' => $externalOrderSN ? $externalOrderSN : '',
        				                          'distribution_type' => $distributionType ? $distributionType : 0,
        				                          'source' => $source ? $source : 0,
        				                         )
        				                   );

        $row = array('order_id' => $orderID,
                     'order_sn' => $orderSN,
                     'batch_sn' => $orderSN,
                     'add_time' => $time,
                     'lock_name' => $this -> _auth['admin_name'],
                     'type' => $type,
                     'price_order' => $priceOrder,
                     'price_goods' => $priceGoods,
                     'price_logistic' => floatval($priceLogistic),
                     'price_pay' => $pricePay,
                     'addr_consignee' => $addr_consignee,
                     'addr_tel' => $addr_tel,
                     'addr_mobile' => $addr_mobile,
                     'pay_type' => $pay_type,
                     'pay_name' => $pay_name,
                     'note' => $note ? $note : null,
                     'note_print' => $note_print ? $note_print : null,
                     'note_logistic' => $note_logistic ? $note_logistic : null,
                     'sms_no' => $sms_no ? $sms_no : null,
                    );
        if ($logistics_type == 'self' || $logistics_type == 'externalself') {
            if ($logistics_type == 'self') {
                $row['logistic_code'] = 'self';
                $row['logistic_name'] = '客户自提';
                $row['addr_address'] = $addr_address ? $addr_address : '客户自提';
            }
            else {
                $row['logistic_code'] = 'externalself';
                $row['logistic_name'] = '渠道代发货自提';
                $row['addr_address'] = $addr_address ? $addr_address : '渠道代发货自提';
            }
            if ($provinceID) {
                $row['addr_province'] = $this -> _db -> getAreaName($provinceID);
                $row['addr_province_id'] = $provinceID;
            }
            if ($cityID) {
                $row['addr_city'] = $this -> _db -> getAreaName($cityID);
                $row['addr_city_id'] = $cityID;
            }
            if ($areaID) {
                $row['addr_area'] = $areaID == -1 ? '其它区' : $this -> _db -> getAreaName($areaID);
                $row['addr_area_id'] = $areaID;
            }
        }
        else {
            $row['addr_province'] = $this -> _db -> getAreaName($provinceID);
            $row['addr_city'] = $this -> _db -> getAreaName($cityID);
            $row['addr_area'] = $areaID == -1 ? '其它区' : $this -> _db -> getAreaName($areaID);
            $row['addr_province_id'] = $provinceID;
            $row['addr_city_id'] = $cityID;
            $row['addr_area_id'] = $areaID;
            $row['addr_address'] = $addr_address;
        }
        
        //分销单
        if (substr($userName, -13) == '_distribution') {
            $row['status'] = 4;
            $row['status_logistic'] = 0;
            $row['status_pay'] = 0;
        }

        //新增订单批次
        $orderBatchID = $this -> _db -> addOrderBatch($row);
        
        //新增订单商品
        if ($add) {
            foreach($add as $productID => $v){
                $data = $productData[$productID];
                $tmp[$data['product_id']] = $this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
                                                                                     'order_batch_id' => $orderBatchID,
                                                                                     'order_sn' => $orderSN,
                                                                                     'batch_sn' => $orderSN,
                                                                                     'type' => $data['is_vitual'] ? 7 : 0,
                                                                                     'add_time' => $time,
                                                                                     'product_id' => $data['product_id'],
                                                                                     'product_sn' => $data['product_sn'],
                                                                                     //'goods_id' => $data['goods_id'],
                                                                                     'goods_name' => $data['product_name'],
                                                                                     'goods_style' => $data['goods_style'],
                                                                                     'cat_id' => $data['cat_id'] ? $data['cat_id'] : 0,
                                                                                     'cat_name' => $data['cat_name'],
                                                                                     'weight' => $data['p_weight'],
                                                                                     'length' => $data['p_length'],
                                                                                     'width' => $data['p_width'],
                                                                                     'height' => $data['p_height'],
                                                                                     'number' => $v['number'],
                                                                                     //'price' => $data['price'],
                                                                                     'cost'    => $data['cost'],
                                                                                     'sale_price' => $v['sale_price'])
                                                                                     );
                if (!in_array($userID, array(3,10)) || ($userID == 3 && $type == 14)) {   //添加销售产品占有库存
                    $stockAPI -> holdSaleProductStock($productID, $v['number']);
                }
                
                //处理礼品卡
                if ($giftCardRow[$productID]) {
                    $giftCardRow[$productID]['order_batch_goods_id'] = $tmp[$productID];
                    if ($giftCardAPI -> addLog($giftCardRow[$productID]) != 'addGiftCardSucess') {
                        $error .= "生成礼品卡失败<br>";
                        return false;
                    }
                    $vitualCardData[$productID] = $giftCardAPI -> _cardData;
                }

                //处理虚拟商品
                if ($data['is_vitual']) {
                    $vitualGooodsAPI = new Admin_Models_API_VitualGoods();
                    if (!$vitualCardData[$productID]) {
                        for ($cardIndex = 1; $cardIndex <= $v['number']; $cardIndex++) {
                            $vitualCardData[$productID][] = Custom_Model_Encryption::getInstance() -> encrypt($productID.$cardIndex, 'vitualCard');
                        }
                    }
                    
                    foreach ($vitualCardData[$productID] as $card) {
                        $vitualGiftCard = array('type' => $data['is_gift_card'] ? 2 : 1,
                                                'product_id' => $productID,
                                                'order_batch_goods_id' => $tmp[$productID],
                                                'sn' => $card['sn'],
                                                'pwd' => $card['pwd'],
                                                'user_id' => $this -> _auth['admin_id'],
                                                'user_name' => $this -> _auth['admin_name'],
                                                'status' => 1,
                                                'deliver_time' => time(),
                                                'add_time' => time(), 
                                               );
                                               var_dump($vitualGiftCard);
                        $vitualGooodsAPI -> add($vitualGiftCard);
                    }
                }
            }
        }

        //新增订单组合商品
        if ($addg) {
            foreach($addg as $goodsID => $v) {
                $data = $groupGoodsAPI -> getGroupgoogs("group_id = '{$goodsID}'");

                $groupGoods = $groupGoodsAPI -> fetchConfigGoods(array('group_goods_config' => $data['group_goods_config']));

                $total_cost = 0;
                foreach ($groupGoods as $goods) {
                    $total_cost += $goods['cost'] * $goods['number'];
                }
                $parent_id = $this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
                                                                      'order_batch_id' => $orderBatchID,
                                                                      'order_sn' => $orderSN,
                                                                      'batch_sn' => $orderSN,
                                                                      'type' => 5,
                                                                      'add_time' => $time,
                                                                      'product_id' => 0,
                                                                      'product_sn' => $data['group_sn'],
                                                                      'goods_id' => 0,
                                                                      'goods_name' => $data['group_goods_name'],
                                                                      'goods_style' => $data['group_specification'],
                                                                      'cat_id' => 0,
                                                                      'cat_name' => 0,
                                                                      'weight' => 0,
                                                                      'length' => 0,
                                                                      'width' => 0,
                                                                      'height' => 0,
                                                                      'number' => $v['number'],
                                                                      'price' => $data['group_price'],
                                                                      'cost'       => $total_cost,
                                                                      'sale_price' => $v['sale_price']));

                
                foreach ($groupGoods as $goods) {
                    $data = array_shift($this -> _product -> get(array('product_sn' => $goods['goods_sn'])));
                    $number = $v['number'] * $goods['number'];
                    $this -> _db -> addOrderBatchGoods(array('order_id' => $orderID,
                                                             'order_batch_id' => $orderBatchID,
                                                             'order_sn' => $orderSN,
                                                             'batch_sn' => $orderSN,
                                                             'parent_id' => $parent_id,
                                                             'type' => 5,
                                                             'add_time' => $time,
                                                             'product_id' => $data['product_id'],
                                                             'product_sn' => $data['product_sn'],
                                                             'goods_name' => $data['product_name'],
                                                             'goods_style' => $data['goods_style'],
                                                             'cat_id' => $data['cat_id'] ? $data['cat_id'] : 0,
                                                             'cat_name' => $data['cat_name'] ? $data['cat_name'] : '',
                                                             'weight' => $data['p_weight'],
                                                             'length' => $data['p_length'],
                                                             'width' => $data['p_width'],
                                                             'height' => $data['p_height'],
                                                             'number' => $number,
                                                             'price' => $data['suggest_price'] ? $data['suggest_price'] : $data['cost'],
                                                             'cost'  => $goods['cost'],
                                                             //'sale_price' => $data['price']
                                                             ));

                    if (!in_array($userID, array(3,10)) || ($userID == 3 && $type == 14)) {   //添加销售产品占有库存
                        $stockAPI -> holdSaleProductStock($data['product_id'], $number);
                    }
                }
            }
        }

        //添加日志
        $log = array('order_sn' => $orderSN,
                     'batch_sn' => $orderSN,
                     'add_time' => time(),
                     'title' => $title,
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        return $orderSN;
    }

    /**
     * 退换货开单
     *
     * @param   string      $batchSN
     * @param   array      $post
     * @param   string      $error
     * @return  void
     */
    public function runReturn($batchSN, $post, &$error=null)
    {
        $time = time();
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        
        $return = $post['return'];
        if (!$return) {//不产生退货 终止
            return false;
        }
        
        $note = $post['note_staff'] . '。' . $post['note'];//修改换货商品价格理由
        if ($post['note_staff']) {//客服备注
            $noteStaff = $order['note_staff'] . $this -> _auth['admin_name'] . '^' . $time . '^' . $post['note_staff'] . "\n";
        } else {
            $noteStaff = $order['note_staff'];
        }
        
        //代收款/直供单/拒收
        if ($order['status_logistic'] == 5 && ($order['pay_type'] == 'cod' || $order['type'] == 16 || $order['user_name'] == 'credit_channel')) {
           $initAmount = $order['price_order'] - $order['price_payed'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'];
        }
        //代收款拒收 退货开单 结束
        
        $change = $post['change'];
        $priceAdjustReturn = $post['price_adjust_return'];
        $noteAdjustReturn = $post['note_adjust_return'];
        
        //初始应退款
        $returnAmount = $order['price_payed'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'] + $order['price_from_return'] - $order['price_pay'];
        
        if ($return) {
            $allReturn = true;
            $returnGoodsAmount = 0;
            foreach ($return as $orderBatchOrderID => $v) {
                $product = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchOrderID)));
                
                //总退货金额
                $returnAmount += $v['number'] * $product['eq_price'];
                $returnGoodsAmount += $v['number'] * $product['eq_price'];
                
                //总退货数量
                $updateReturnNumber[$orderBatchOrderID] += $v['number'];
                $returnNumber += $v['number'];
                
                //原来数量2012.5.9，用于更新组合商品的子商品数量，1399行
                $formerNumer[$orderBatchOrderID] = $v['former_number'];
                
                if ($v['former_number'] > $v['number']) {
                    $allReturn = false;
                }
                
                //出库记录
                if ($v['number']) {
	                if($product['type']==5){
	                	//组合商品
	                	$rs = $this -> _db -> getOrderBatchGoods(array('batch_sn'=>$batchSN,'type'=>5,'parent_id'=>$orderBatchOrderID));
	                	if(is_array($rs) && count($rs)){
	                		foreach ($rs as $index => $val){
	                			$addIn[$val['product_id']]['number'] += $v['number']*$val['number']/$formerNumer[$orderBatchOrderID];
                    			$addIn[$val['product_id']]['price'] = $val['sale_price'];
	                		}
	                	}
	                }else{
	                	//普通商品
	                	$addIn[$product['product_id']]['number'] += $v['number'];
	                	$addIn[$product['product_id']]['former_number'] += $v['former_number']; //仅用于直供
                    	$addIn[$product['product_id']]['price'] = $product['price'];
                    	$addIn[$product['product_id']]['order_batch_goods_id'] = $product['order_batch_goods_id'];
                    	$addIn[$product['product_id']]['type'] = $product['type'];
	                }
                }
                //退货理由
                $reason = $v['reason'];
                if ($reason) {
                    $other = trim($reason['other']);
                    unset($reason['other']);
                    $addReason[] = array('public' => array('order_sn' => $product['order_sn'],
                                                           'batch_sn' => $product['batch_sn'],
                                                           'order_batch_goods_id' => $product['order_batch_goods_id'],
                                                           'product_id' => $product['product_id'],
                                                           'add_time' => $time,
                                                           'reason' => $other),
                                                           'private' => $reason);
                }
            }
            
            //全退时是否退运费
            if ($order['price_logistic'] > 0 && $allReturn && ($order['price_payed'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed']) > 0) {
                $returnPriceLogistic = $post['return_price_logistic'] ? $post['return_price_logistic'] : 2;
            }
            else {
                $returnPriceLogistic = 0;
            }
            
            $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('return_price_logistic' => $returnPriceLogistic));
        }
        
        //退货数量为0同时没有换货，终止
        if (!$returnNumber && !$change) {
            return false;
        }
        
        if (!$returnNumber && $post['is_lost']) {
            $error = "没有退货商品，不能作丢件处理<br>";
            return false;
        }
        
        //在没有完成现有的退货时，不允许再开退货
        if ($returnNumber) {
            $instockAPI  = new Admin_Models_API_InStock();
            if ($instockAPI -> getMain("item_no = '{$batchSN}' and bill_type in (1,13) and bill_status in (3,6)")) {
                $error = "请先处理完现有的退货单<br>";
                return false;
            }
        }
        
        //直供订单可能要修改结算单并生成虚拟退款单
        if ($order['type'] == 16 && $order['status_logistic'] == 4 && $post['not_to_settlement'] && $returnGoodsAmount > 0) {
            if ($order['price_order'] - $order['price_payed'] < $returnGoodsAmount) {
                $error = "退货款大于需支付金额，不能勾选“退货款不作结算”<br>";
                return false;
            }
            
            $financeAPI = new Admin_Models_API_Finance();
            $settlementDetails = $financeAPI -> getDistributionSettlementDetail($batchSN);
            if ($settlementDetails) {
                foreach ($settlementDetails as $settlementDetail) {
                    if ($settlementDetail['type'] = 1) {
                        foreach ($settlementDetail['detail'] as $productID => $number) {
                            if ($settlementDetail['amounbt'] > 0) {
                                $settlementData[$productID] += $number;
                            }
                            else {
                                $settlementData[$productID] -= $number;
                            }
                        }
                    }
                }
                foreach ($addIn as $productID => $data) {
                    if ($settlementData[$productID] && $data['former_number'] - $settlementData[$productID] < $data['number']) {
                        $error = "退货数量大于已结算数量，不能勾选“退货款不作结算”<br>";
                        return false;
                    }
                }
            }
            
            $settlement = array_shift($financeAPI -> getDistributionSettlement(array('batch_sn' => $batchSN)));
            if ($settlement) {
                if ($settlement['amount'] - $returnGoodsAmount < 0 || $settlement['amount'] - $returnGoodsAmount < $settlement['settle_amount']) {
                    $error = "扣减结款金额出错<br>";
                    return false;
                }
                $financeAPI -> updateDistributionSettlement($batchSN, array('amount' => $settlement['amount'] - $returnGoodsAmount));
                
                $data = array('shop_id' => $order['shop_id'] ? $order['shop_id'] : 0,
    						  'type' => 0,
    						  'way' => 5,//直供单虚拟退款
    					      'item' => 1,
    						  'item_no' => $batchSN,
    						  'pay' => -$returnGoodsAmount,
    						  'logistic' => 0,
    						  'point' => 0,
    						  'account' => 0,
    						  'gift' => 0,
    						  'status' => 2,
    						  'bank_type' => 4,
    						  'bank_data' =>'',
    						  'order_data' =>'',
    						  'note' => '直供单系统退款',
    						  'callback' => '',
    						  'add_time' => time(),
    						  'check_time' => 0,
    						 );
    		    $financeAPI -> addFrinance($data);
    		    
    		    $returnDetail = array();
    		    foreach ($addIn as $productID => $addInProduct) {
    		        $returnDetail[$productID] = $addInProduct['number'];
    		    }
    		    $data = array('distribution_id' => $settlement['distribution_id'],
    		                  'type' => 2,
    		                  'amount' => $returnGoodsAmount * -1,
    		                  'detail' => serialize($returnDetail),
    		                  'admin_name' => $this -> _auth['admin_name'],
    		                  'add_time' => time(),
    		                 );
    		    $financeAPI -> addDistributionSettlementDetail($data);
            }
            else {
                $error = "财务结款单找不到<br>";
                return false;
            }
        }

        if ($change) {//新增新品
            foreach ($change as $productID => $item) {
                if ($item['number'] > 0) {
                    $product = array_shift($this -> _product -> get(array('product_id' => $productID)));
                    $item['sale_price'] = floatval($item['sale_price']);
                    //总新品应付金额
                    $addChangeMoney += $item['sale_price'] * $item['number'];

                    $product['change_number'] = $item['number'];
                    $product['sale_price'] = $item['sale_price'];
                    $addProduct[] = $product;

                    $changeNumber += $item['number'];
                }
            }
        }
        $stockAPI = new Admin_Models_API_Stock();
        
        //api 检测是否有库存 开始
        if ($addProduct) {
            foreach ($addProduct as $k => $v) {
                if (!$stockAPI -> checkPreSaleProductStock($v['product_id'], $v['change_number'])) {
                    $error = "产品ID{$v['product_id']}库存不足<br>";
                    return false;
                }
            }
        }
        //api 检测是否有库存 结束
        
        //已使用虚拟商品不允许退货
        if ($addIn) {
            $vitualGoods = new Admin_Models_API_VitualGoods();
            $onlyVitual = true;
            foreach ($addIn as $productID => $addInProduct) {
                if ($addInProduct['type'] == 7) {
                    $vitualGoodsData = $vitualGoods -> getVitualGoods(array('order_batch_goods_id' => $addInProduct['order_batch_goods_id'], 'product_id' => $productID));
                    if (!$vitualGoodsData) {
                        $error = "找不到对应的虚拟商品<br>";
                        return false;
                    }
                    if (count($vitualGoodsData) < $addInProduct['number']) {
                        $error = "虚拟商品应退数量大于实际数量<br>";
                        return false;
                    }
                    $vitualGoodsReturnNumber = 0;
                    foreach ($vitualGoodsData as $vitualGoods) {
                        if ($vitualGoods['status'] == 1) {
                            $vitualGoodsReturnNumber++;
                        }
                    }
                    if ($vitualGoodsReturnNumber < $addInProduct['number']) {
                        $error = "虚拟商品状态不是已交付，不能退货<br>";
                        return false;
                    }
                }
                else {
                    $onlyVitual = false;
                }
            }
        }
        
        //更新 退货数量 / 换货数量 开始
        if ($updateReturnNumber) {
            foreach ($updateReturnNumber as $orderBatchGoodsID => $number) {
                $where = array('order_batch_goods_id' => $orderBatchGoodsID);
                $temp = array_shift($this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $orderBatchGoodsID)));
                $data = array('return_number' => $temp['return_number'] + $number,
                              'returning_number' => $number);
                $this -> _db -> updateOrderBatchGoods($where, $data);

                /*Start::更新组合商品内包含的商品数量 2012.5.9*/
                if($temp['type']==5){
                	$rs = $this -> _db -> getOrderBatchGoods(array('batch_sn'=>$batchSN,'type'=>5,'parent_id'=>$orderBatchGoodsID));
                	if(is_array($rs) && count($rs)){
                		foreach ($rs as $index => $val){
                			$where = array('batch_sn' => $batchSN, 'order_batch_goods_id' => $val['order_batch_goods_id']);
                			$num = ($val['number']/$formerNumer[$orderBatchGoodsID])*$number;
                			$this -> _db -> updateOrderBatchGoods($where, array('return_number' => ($val['return_number']+$num), 'returning_number' => $num));
                		}
                	}
                }
                /*End::更新组合商品内包含的商品数量*/
            }
        }

        //更新 退货数量 / 换货数量 结束

        //新增 退货 理由 开始
        if ($addReason) {
            foreach ($addReason as $v) {
                if (!$v['reason'])  continue;
                $id = $this -> _db -> addOrderBatchGoodsReturn($v['public']);
                if ($v['private']) {
                    foreach ($v['private'] as $reasonID => $tmp) {
                        $data = array('id' => $id, 'reason_id' => $reasonID);
                        $this -> _db -> addOrderBatchGoodsReturnReason($data);
                    }
                }
            }
        }
        //新增 退货 理由 结束
        
        //api 申请入库 开始
        if ($addIn) {
            if ($post['is_lost']) {
                //生成退款单后再做
            }
            else {
                $remark = "退货入库({$order['addr_consignee']} {$order['external_order_sn']})";
                $inType = 1;
                $instockBillNo = $this -> in($batchSN, $addIn, $remark, $inType);
            }
        }
        //api 申请入库 结束
        
        //拒收时增加退货日志
        if (isset($initAmount)) {
            $this -> _db -> addOrderReturn(array('order_sn' => $order['order_sn'],
                                                 'batch_sn' => $order['batch_sn'],
                                                 'amount' => $initAmount ? $initAmount : 0,
                                                 'add_time' => time(),
                                                 'finish_time' => 0,
                                                )
                                          );
        }
        
        //新增 商品
        if ($addProduct) {
            $tmp = array_shift($this -> _db -> getOrderBatch(array('order_sn' => $order['order_sn']), 'add_time desc'));
            $tmp = explode('_', $tmp['batch_sn']);
            $newBatchSN = $order['order_sn'].'_'.(intval($tmp['1']) + 1);
            
            $returnAmount -= $priceAdjustReturn;
            $returnMoney = $returnPoint = $returnAccount = $returnGiftCard = 0;
            if ($returnAmount > 0) {
                $orderDetail = $this -> orderDetail($batchSN);
                $newAmount = 0 + $post['price_logistic'];
                foreach ($addProduct as $k => $v) {
                    $newAmount += $v['sale_price'] * $v['change_number'];
                }
                
                //计算老单已支付金额转到新单的金额
                if ($newAmount >= $returnAmount ) {
                    $returnMoney = $orderDetail['finance']['price_return_money'];
                    $returnPoint = $orderDetail['finance']['price_return_point'];
                    $returnAccount = $orderDetail['finance']['price_return_account'];
                    $returnGiftCard = $orderDetail['finance']['price_return_gift'];
                    $returnAmount = 0;
                }
                else {
                    //优先顺序 账户余额 -> 积分 -> 礼品卡 -> 现金
                    if ($orderDetail['finance']['price_return_account'] > 0) {
                        if ($newAmount >= $orderDetail['finance']['price_return_account']) {
                            $returnAccount = $orderDetail['finance']['price_return_account'];
                            $newAmount -= $orderDetail['finance']['price_return_account'];
                        }
                        else {
                            $returnAccount = $newAmount;
                            $newAmount = 0;
                        }
                    }
                    if ($newAmount > 0 && $orderDetail['finance']['price_return_point'] > 0) {
                        if ($newAmount >= $orderDetail['finance']['price_return_point']) {
                            $returnPoint = $orderDetail['finance']['price_return_point'];
                            $newAmount -= $orderDetail['finance']['price_return_point'];
                        }
                        else {
                            $returnPoint = $newAmount;
                            $newAmount = 0;
                        }
                    }
                    if ($newAmount > 0 && $orderDetail['finance']['price_return_gift'] > 0) {
                        if ($newAmount >= $orderDetail['finance']['price_return_gift']) {
                            $returnGiftCard = $orderDetail['finance']['price_return_gift'];
                            $newAmount -= $orderDetail['finance']['price_return_gift'];
                        }
                        else {
                            $returnGiftCard = $newAmount;
                            $newAmount = 0;
                        }
                    }
                    if ($newAmount > 0 && $orderDetail['finance']['price_return_money'] > 0) {
                        if ($newAmount >= $orderDetail['finance']['price_return_money']) {
                            $returnMoney = $orderDetail['finance']['price_return_money'];
                            $newAmount -= $orderDetail['finance']['price_return_money'];
                        }
                        else {
                            $returnMoney = $newAmount;
                            $newAmount = 0;
                        }
                    }
                }
            }
            
            //新增 批次
            $data = array('order_id' => $order['order_id'],
                          'order_sn' => $order['order_sn'],
                          'batch_sn' => $newBatchSN,
                          'parent_batch_sn' => $batchSN,
                          'type' => $order['type'] == 13 ? 14 : $order['type'],
                          'add_time' => $time,
                          'is_freeze' => $returnNumber ? ($post['is_lost'] ? 0 : 1) : 0,//新开的换货单 是被冻结住的，等退货入库后，才解冻，解冻后方可进行新单的订单确认(取消)
                          'is_fav' => is_null($order['is_fav']) ? null : -1,//-1指老单未满意不退货，在新单中要给流转金额的积分
                          'lock_name' => $this -> _auth['admin_name'],
                          'price_logistic' => $post['price_logistic'],
						  'price_payed' => $returnMoney ? $returnMoney : 0,//退款转已付款
						  'clear_pay' => $order['clear_pay'],
						  'pay_type' => $order['pay_type'],
						  'pay_name' => $order['pay_name'],
						  'pay_time' => $order['pay_time'],
                          'addr_consignee' => $order['addr_consignee'],
                          'addr_province_id' => $order['addr_province_id'],
                          'addr_city_id' => $order['addr_city_id'],
                          'addr_area_id' => $order['addr_area_id'],
                          'addr_province' => $order['addr_province'],
                          'addr_city' => $order['addr_city'],
                          'addr_area' => $order['addr_area'],
                          'addr_address' => $order['addr_address'],
                          'addr_zip' => $order['addr_zip'],
                          'addr_tel' => $order['addr_tel'],
                          'addr_mobile' => $order['addr_mobile'],
                          'addr_email' => $order['addr_email'],
                          'addr_fax' => $order['addr_fax']);
            $orderBatchID = $this -> _db -> addOrderBatch($data);
            
            //设置 最新批次号
            $this -> _db -> updateOrder(array('order_sn' => $orderSN), array('batch_sn' => $newBatchSN));
            
            //新增 批次商品
            foreach ($addProduct as $k => $v) {
                $data = array('order_id' => $order['order_id'],
                              'order_batch_id' => $orderBatchID,
                              'order_sn' => $order['order_sn'],
                              'batch_sn' => $newBatchSN,
                              'add_time' => $time,
                              'product_id' => $v['product_id'],
					          'goods_name' => $v['product_name'],
					          'goods_style' => $v['goods_style'],
                              'product_sn' => $v['product_sn'],
                              'cat_id' => $v['cat_id'],
                              'cat_name' => $v['cat_name'],
                              'weight' => $v['p_weight'],
                              'length' => $v['p_length'],
                              'width' => $v['p_width'],
                              'height' => $v['p_height'],
                              'number' => $v['change_number'],
                              'price' => $v['price'] ? $v['price'] : 0,
                              'cost'  => $v['cost'],
                              'sale_price' => $v['sale_price']);
                $orderBatchGoodsID = $this -> _db -> addOrderBatchGoods($data);
                //api 占有库存
                $stockAPI -> holdSaleProductStock($v['product_id'], $v['change_number']);
                if ($v['sale_price'] != $v['price']) {//商品价格修改日志
                    $log = array('order_sn' => $order['order_sn'],
                                 'type' => 3,//1未确认订单修改商品、2订单恢复修改商品、3修改换货商品
                                 'batch_sn' => $newBatchSN,
                                 'order_batch_goods_id' => $orderBatchGoodsID,
                                 'product_sn' => $v['product_sn'],
                                 'number' => $v['change_number'],
                                 'sale_price' => $v['price'] ? $v['price'] : 0,
                                 'edit_price' => $v['sale_price'],
                                 'admin_name' => $this -> _auth['admin_name'],
                                 'note' => '[修改换货商品价格]' . $note,
                                 'add_time' => $time);
                    $this -> _db -> addOrderBatchGoodsLog($log);
                }
            }
            
            //账户余额 老单退回 新单转入
            if ($returnAccount) {
                if ($this -> unAccountPrice($batchSN, $returnAccount)) {
                    $memberApi = new Shop_Models_API_Member();
                    $member = array_shift($memberApi -> getMemberByUserName($order['user_name']));
                    $tmp = array('member_id' => $member['member_id'],
                                 'user_name' => $member['user_name'],
                                 'order_id' => $orderBatchID,
                                 'accountValue' => $returnAccount,
                                 'accountTotalValue' => $member['money'],
                                 'note' => '换货单帐户余额抵扣转入',
                                 'batch_sn' => $newBatchSN);
                    $memberApi -> editAccount($member['member_id'], 'money', $tmp);
                    
                    $this -> _db -> updateOrderPayed($newBatchSN, $returnAccount, 'account');
                }
            }
            
            //积分 老单退回 新单转入
            if ($returnPoint) {
                $point = $this -> unPointPrice($batchSN, $returnPoint);
                if ($point) {
                    $memberApi = new Shop_Models_API_Member();
                    $member = array_shift($memberApi -> getMemberByUserName($order['user_name']));
                    $tmp = array('member_id' => $member['member_id'],
                                 'user_name' => $member['user_name'],
                                 'order_id' => $orderBatchID,
                                 'accountValue' => $point,
                                 'accountTotalValue' => $member['point'],
                                 'note' => '换货单积分抵扣转入',
                                 'batch_sn' => $newBatchSN);
                    $memberApi -> editAccount($member['member_id'], 'point', $tmp);
                    
                    $this -> _db -> updateOrderPayed($newBatchSN, $returnPoint, 'point');
                }
            }
            
            //礼品卡 老单退回 新单转入
            if ($returnGiftCard) {
                $cardList = $this -> unUseCardGift($batchSN, $returnGiftCard);
                if ($cardList) {
                    $cardAPI = new Shop_Models_DB_Card();
                    foreach ($cardList as $card) {
                        $card = array('card_type' => $card['card_type'],
                                      'card_price' => $card['card_price'],
                                      'card_sn' => $card['card_sn'],
                                      'card_pwd' => $card['card_pwd'],
                                      'add_time' => time(),
                                      'admin_id' => $this -> _auth['admin_id'],
                                      'admin_name' => $this -> _auth['admin_name'],
                                      'batch_sn' => $newBatchSN,
                                     );
                        if ($cardAPI -> useGift($card)) {
                            $this -> _db -> updateOrderPayed($newBatchSN, $card['card_price'], 'gift_card');
                        }
                    }
                }
            }
            
            //现金 老单扣减
            if ($returnMoney) {
				$this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN),
												 array('price_payed' => $order['price_payed'] - $returnMoney));
            }
            
            //自动添加退款记录
            if ($returnMoney || $returnAccount || $returnPoint || $returnGiftCard) {
				$data = array('shop_id' => $order['shop_id'] ? $order['shop_id'] : 0,
							  'type' => 0,//系统
							  'way' => 3,//退货系统退到新换货单上
							  'item' => 1,
							  'item_no' => $batchSN,
							  'pay' => $returnMoney ? -$returnMoney : 0,
							  'logistic' => 0,
							  'point' => $returnPoint ? -$returnPoint : 0,
							  'account' => $returnAccount ? -$returnAccount : 0,
							  'gift' => $returnGiftCard ? -$returnGiftCard : 0,
							  'status' => 2,
							  'bank_type' => 4,
							  'bank_data' =>'',
							  'order_data' =>'',
							  'note' => '退货系统退款',
							  'callback' => '',
							  'add_time' => time());
				$instockAPI  = new Admin_Models_API_InStock();
                if ($instockAPI -> getMain("item_no = '{$batchSN}' and bill_type in (1,13) and bill_status in (3,6)")) {
                    $data['check_time'] = 0;
                }
                else {
                    $data['check_time'] = time();
                    //添加应收款记录
                    $financeAPI = new Admin_Models_API_Finance();
                    $receiveData = array('batch_sn' => $newBatchSN,
                                         'type' => 6,
                                         'pay_type' => 'exchange',
                                         'amount' => abs($returnMoney + $returnPoint + $returnAccount + $returnGiftCard),
                                        );
                    $financeAPI -> addFinanceReceivable($receiveData);
                }
                
				$this -> _finance -> addFrinance($data);
            }
            
            if ($priceAdjustReturn) {//退换货开单调整金额
                $adjust = array('order_sn' => $order['order_sn'],
                                'batch_sn' => $newBatchSN,
                                'type' => 20,
                                'money' => $priceAdjustReturn,
                                'note' => $noteAdjustReturn,
                                'add_time' => $time);
                $this -> _db -> addOrderBatchAdjust($adjust);
            }
            
            //更新支付状态
            $this -> orderDetail($newBatchSN);
        }
        
        //丢件退货虚拟入库
        if ($addIn) {
            if ($post['is_lost']) {
                $remark = "虚拟退货入库({$order['addr_consignee']} {$order['external_order_sn']})";
                $inType = 13;
                $instockBillNo = $this -> in($batchSN, $addIn, $remark, $inType);
            }
        }
        
        //设置退换货状态
        if (!$changeNumber) {//退货
            if ($priceAdjustReturn) {//退货开单调整金额
                $adjust = array('order_sn' => $order['order_sn'],
                                'batch_sn' => $batchSN,
                                'type' => 10,
                                'money' => $priceAdjustReturn,
                                'note' => $noteAdjustReturn,
                                'add_time' => $time);
                $this -> _db -> addOrderBatchAdjust($adjust);
            }
        }
        
        $set = array('lock_name' => '',
                     'note_staff' => $noteStaff);
        if ($returnNumber) {
            $set['returning_time'] = $time;
            $set['status_return'] = 1;
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
        
        //插入联盟数据
        $this -> union($batchSN);
        
        $orderDetail = $this -> orderDetail($batchSN);
        
        $title = '退货开单';
        if ($orderDetail['finance']['price_return_money'] > 0 || $orderDetail['finance']['price_return_point'] > 0 || $orderDetail['finance']['price_return_account'] > 0 || $orderDetail['finance']['price_return_gift'] > 0) {
            if ($orderDetail['finance']['price_return_money'] > 0) {
                $title .= ' [退款：￥'.$orderDetail['finance']['price_return_money'].']';
            }
            if ($orderDetail['finance']['price_return_point'] > 0) {
                $title .= ' [退积分：￥'.$orderDetail['finance']['price_return_point'].']';
            }
            if ($orderDetail['finance']['price_return_account'] > 0) {
                $title .= ' [退账户余额：￥'.$orderDetail['finance']['price_return_account'].']';
            }
            if ($orderDetail['finance']['price_return_gift'] > 0) {
                $title .= ' [礼品卡：￥'.$orderDetail['finance']['price_return_gift'].']';
            }
            
            $newBatchSN = false;
        }
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => $title,
                     'data' => Zend_Json::encode($post),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        
        //如果只有虚拟商品，作收货处理
        if ($instockBillNo && $onlyVitual) {
            $inStockAPI = new Admin_Models_API_InStock();
            $inStockAPI -> receiveByBillNo($instockBillNo);
        }
        
        return $newBatchSN;
    }

	/**
     * 统计当前订单商品 总重量 体积 数量 计算物流配送方式需要用到
     *
     * @param   string      $batchSN
     * @return  array
     */
    public function _orderBatchProductStatus($batchSN)
    {
        $where = array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0);
	    $product = $this -> _db -> getOrderBatchGoods($where);
        if (is_array($product) && count($product)) {
            foreach($product as $k => $v) {
                $productNumber += $v['number'];
                $productWeight += $v['weight'] * $v['number'];
                $productVolume += $v['length'] * $v['width'] * $v['height'] * $v['number'] * 0.001;
            }
        }
        $data = array('product_number' => $productNumber, 'product_weight' => $productWeight,'product_volume' => $productVolume);
        return $data;
    }

    /**
     * 取得指定条件的订单
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function orderDetail($batchSN)
    {
        
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));//订单资料
        $productDetail = $this -> _productDetail($batchSN, $order['status'] == 4 ? Custom_Model_Stock_Base::getDistributionArea($order['user_name']) : 1);//商品资料

		$adjust = $this -> _adjustDetail($batchSN);//调整金额资料
        $batchLog = $this -> _batchLogDetail($batchSN);//订单操作记录
        $payLog = $this -> _batchPayLogDetail($batchSN);//订单支付历史记录
        $financeReturning = $this -> _financeReturningDetail($batchSN);//订单退款记录

        $this -> _avgDetail($batchSN,$order);//处理订单商品销售均值

        //从新计算产品均值 开始
        if ($productDetail['product']) {
            $priceForEqual = $productDetail['other']['price_goods_all'] +
                             (-abs($productDetail['price_minus'])) +
                             (-abs($productDetail['price_old'])) +
                             (-abs($productDetail['price_coupon'])) +
                             (-abs($productDetail['price_virtual'])) +
                             $adjust['price_adjust'];
            $used = array();
            foreach ($productDetail['product'] as $k => $v) {
            	if($v['type'] == 5 && $productDetail['other']['price_goods_all'] != 0)//如果是组合商品
            	{
            		if(!isset($used[$v['parent_id']])){
	            		$used[$v['parent_id']] = $v['parent_id'];
		                $rs = $this -> _db -> getOrderBatchGoods(array('order_batch_goods_id' => $v['parent_id']));
		                $eqPrice = round($rs[0]['sale_price'] / $productDetail['other']['price_goods_all'] * $priceForEqual, 2);
		                //先更新组合商品"总商品"的均价
		                $equalAmount += $eqPrice * $productDetail['product_all'][$v['parent_id']]['number'];
		                $this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN, 'order_batch_goods_id' => $v['parent_id']), array('eq_price' => $eqPrice, 'eq_price_blance' => 0));
		                /*begin::组合商品计算子商品均价*/
		                $subgoods = array();
		                $subgoods = $this -> _db -> getOrderBatchGoods(array('parent_id'=>$v['parent_id'], 'type'=>5, 'batch_sn'=>$batchSN));//取出这个组合商品的所有子商品
		                //计算子商品原价的总价
		                $subgoodsPriceTotal = 0;
		                $subGoodsNum = 0;
                        $costs = array();//存储porduct_id的cost
		                foreach ($subgoods as $sval)
                        {
                            /*组合商品子商品几个统计按 shop_product.cost 价格计算*/
                            $pinfo = array_shift( $this -> _product -> get(array('product_id'=>$sval['product_id']), 'cost') );
                            if(is_array($pinfo) && count($pinfo)){
                                $costs[$sval['product_id']] = $pinfo['cost'];
                                $sval['price'] = $pinfo['cost'];
                            }
                            $costs[$sval['product_id']] = $pinfo['cost'];
		                	$subgoodsPriceTotal += $pinfo['cost']*$sval['number'];
		                	$subGoodsNum += $sval['number'];
		                }

		                //计算并更新子商品的均价
		                if($subgoodsPriceTotal == 0){//如果所有子商品价格全部为0，则把总价格平分到每个商品
		                	$equal = $eqPrice * $productDetail['product_all'][$v['parent_id']]['number']/$subGoodsNum;
		                	foreach ($subgoods as $sval){
		                		$this -> _db -> updateOrderBatchGoods(array('batch_sn'=>$batchSN, 'order_batch_goods_id' => $sval['order_batch_goods_id']), array('sale_price'=>$equal, 'eq_price'=>$equal, 'eq_price_blance'=>0));
		                	}
		                }else{
	            			foreach ($subgoods as $sval)
                            {
                                /*组合商品子商品几个统计按 shop_product.cost 价格计算*/
                                if(isset($costs[$sval['product_id']])){
                                    $sval['price'] = $costs[$sval['product_id']];
                                }
			                	$subgoodsEqPrice = ($sval['price']*$rs[0]['number']*$rs[0]['sale_price'])/$subgoodsPriceTotal;
			                	$this -> _db -> updateOrderBatchGoods(array('batch_sn'=>$batchSN, 'order_batch_goods_id' => $sval['order_batch_goods_id']), array('price'=>$sval['price'], 'sale_price'=>$subgoodsEqPrice, 'eq_price'=>$subgoodsEqPrice, 'eq_price_blance'=>0));
			                }
		                }
		                /*end::组合商品计算子商品均价*/
            		}
            		$tmpID = $v['parent_id'];
            	}
            	else {
	                if ($productDetail['other']['price_goods_all'] != 0) {
	                    $eqPrice = round($v['sale_price'] / $productDetail['other']['price_goods_all'] * $priceForEqual, 2);
	                } else {
	                    if ($productDetail['goods_number'] > 0) {
	                        $eqPrice = round($priceForEqual / $productDetail['goods_number'], 2);
	                    }
	                    else {
	                        $eqPrice = 0;
	                    }
	                }
	                $this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,
	                                                            'order_batch_goods_id' => $v['order_batch_goods_id']),
	                                                      array('eq_price' => $eqPrice, 'eq_price_blance' => 0));
	                $equalAmount += $eqPrice * $v['number'];
	                $tmpID = $v['order_batch_goods_id'];
            	}
            }
            $eqPriceBlance = round($priceForEqual - $equalAmount, 2);
            if ($eqPriceBlance) {
                $this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,
                                                            'order_batch_goods_id' => $tmpID),
                                                      array('eq_price_blance' => $eqPriceBlance));
            }
        }
        $productDetail = $this -> _productDetail($batchSN, $order['status'] == 4 ? Custom_Model_Stock_Base::getDistributionArea($order['user_name']) : 1);

		//加上限价
		$product_apply_db = new Admin_Models_DB_ProductApply();
		$group_goods_db   = new Admin_Models_DB_GroupGoods();
		$product_db       = new Admin_Models_DB_Product();
		if (count($productDetail['product_all']) > 0) {
			foreach ($productDetail['product_all'] as $key => $product) {
				$limit_price = 0;
				if (isset($product['child']) && substr($product['product_sn'], 0, 1) == 'G') {
					$group_params = array(
						'shop_id'    => $order['shop_id'],
						'type'       => '2',
						'start_ts'   => date('Y-m-d H:i:s', $product['add_time']),
						'product_sn' => $product['product_sn'],
					);

					$apply_info = $product_apply_db->getInfoByCondition($group_params);

					if (!empty($apply_info)) {
						$productDetail['product_all'][$key]['price_limit'] = $apply_info['price_limit'];
					} else {
						$group_info = $group_goods_db->getGroupInfoByGroupSn($product['product_sn']);
						if (!empty($group_info)) {
							$productDetail['product_all'][$key]['price_limit'] = $group_info['price_limit'];
						}
					}
				} else if (substr($product['product_sn'], 0, 1) == 'N') {
					$product_params = array(
						'shop_id'    => $order['shop_id'],
						'type'       => '1',
						'start_ts'   => date('Y-m-d H:i:s', $product['add_time']),
						'product_sn' => $product['product_sn'],
					);

					$apply_info = $product_apply_db->getInfoByCondition($product_params);
					
					if (!empty($apply_info)) {
						$productDetail['product_all'][$key]['price_limit'] = $apply_info['price_limit'];
					} else {
						$product_info = $product_db->getProductInfoByProductSn($product['product_sn']);

						if (!empty($product_info)) {
							$productDetail['product_all'][$key]['price_limit'] = $product_info['price_limit'];
						}
					}
				}
			}
		}

        //从新计算产品均值 结束
        //从新计算订单各个金额，订单支付状态 开始

        //货到付款的拒收订单，统计订单金额时运费置为0   或者商品全退
        if ($order['status'] == 0 && $order['status_logistic'] == 5 && $order['status_return'] == 1 && $order['pay_type'] == 'cod') {
            $order['price_logistic'] = 0;
        }
		if($productDetail['other']['price_goods'] == 0 && $order['status_return'] == 1){
		    if ($order['return_price_logistic'] == 1) {
			    $order['price_logistic'] = 0;
			}
		}
        $priceOrder = round($productDetail['other']['price_goods_eq'], 2) +
                      $order['price_logistic'] +
                      $adjust['price_adjust_return'] +              //退货调整金额
                      $adjust['price_adjust_return_logistic_to'] +  //退货 退运费
                      $adjust['price_adjust_return_logistic_back']; //退货 退运费
        $pricePay = $priceOrder;

        //获得礼品卡信息
        $onlyGiftCard = true;
        $orderBatchGoodsIDArray = $giftCardList = '';
		if (count($productDetail['product'])>0){
			foreach ($productDetail['product'] as $product) {
				if ($product['product_id'] > 0) {
					if ($product['is_gift_card']) {
						$orderBatchGoodsIDArray[] = $product['order_batch_goods_id'];
						$orderBatchGoodsData[$product['order_batch_goods_id']] = $product;
					}
					else {
						$onlyGiftCard = false;
					}
				}
			}
		}

        if ($orderBatchGoodsIDArray) {
            $giftCardAPI = new Admin_Models_API_GiftCard();
            $giftCardList = array_shift($giftCardAPI -> getCardlist(array('order_batch_goods_id' => $orderBatchGoodsIDArray, 'status' => array(0,2))));
            
            //包含普通商品的订单，重新计算订单金额
            if (!$onlyGiftCard) {
                $goodsAmount = $giftCardPrice = $giftCardAmount = 0;
                foreach ($productDetail['product'] as $product) {
                    if ($product['is_gift_card']) {
                        $giftCardPrice += $product['sale_price'] * ($product['number'] - $product['return_number']);
                        $amountInfo = $this -> _product -> getGiftcardInfoByProductid($product['product_id']);
                        $giftCardAmount += $amountInfo['amount'];
                    }
                    else {
                        $goodsAmount += $product['sale_price'] * ($product['number'] - $product['return_number']);
                    }
                }
                
                $productDetail['other']['price_goods'] = $productDetail['other']['price_goods'] - $giftCardPrice;
                $priceOrder = $productDetail['other']['price_goods'] + $order['price_logistic'] + $order['price_adjust'] + $adjust['price_adjust_return'];
                if ($giftCardAmount >= $goodsAmount + $order['price_adjust'] + $adjust['price_adjust_return']) {
                    $pricePay = $giftCardPrice + $order['price_logistic'];
                }
                else {
                    $pricePay = $productDetail['other']['price_goods'] + $order['price_logistic'] + $order['price_adjust'] + $adjust['price_adjust_return'] - $giftCardAmount + $giftCardPrice;
                }
            }
        }
        
        if (round($order['price_payed'] + $order['price_from_return'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'], 2) < round($pricePay, 2)) {
            $statusPay = 0;//未收款
        } else if (round($order['price_payed'] + $order['price_from_return'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'], 2) == round($pricePay, 2)) {
            //提货卡只要不是已签收，都是未收款
            if ($this -> getOrderGoodsCard($productDetail['product_all']) && $order['status_logistic'] != 4) {
                $statusPay = 0;
            }
            else    $statusPay = 2;//已结清
        } else if (round($order['price_payed'] + $order['price_from_return'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'], 2) > round($pricePay, 2)) {
            $statusPay = 1;//未退款
        }
        
        $set = array('price_order' => $priceOrder,
                     'price_goods' => $productDetail['other']['price_goods'],
                     'price_adjust' => $adjust['price_adjust'],
                     'price_pay' => $pricePay,
                     'status_pay' => $statusPay);
        
        //发货前，如果订单金额是0，或者由积分、余额、礼品卡全额支付，支付方式设置为无需支付
        if (in_array($order['status_logistic'], array(0,1,2))) {
            if ($pricePay <= 0 || ($order['account_payed'] + $order['point_payed'] + $order['gift_card_payed']) >= $pricePay || $order['parent_batch_sn']) {
                $set['pay_type'] = 'no_pay';
                $set['pay_name'] = '无需支付';
            }
            else {
                if ($order['pay_type'] == 'no_pay') {
                    $set['pay_type'] = '';
                    $set['pay_name'] = '';
                }
            }
        }
        
        //根据支付状态修改礼品卡状态
        if ($giftCardList) {
            if ($statusPay == 1 || $statusPay == 2) {
                foreach ($giftCardList as $giftCard) {
                    $tempCardList[$giftCard['order_batch_goods_id']][] = $giftCard;
                }
                foreach ($tempCardList as $orderBatchGoodsID => $giftCardList) {
                    $orderBatchGoods = $orderBatchGoodsData[$orderBatchGoodsID];
                    if ($orderBatchGoods['return_number'] > 0) {
                        $returnNumber = $orderBatchGoods['return_number'];
                        foreach ($giftCardList as $giftCard) {
                            if ($giftCard['status'] == 0) {
                              $giftCardAPI -> updateCard("card_sn = '{$giftCard['card_sn']}'", array('status' => 2));
                            }
                            $returnNumber--;
                            if ($returnNumber <= 0) {
                                break;
                            }
                        }
                    }
                    else {
                        foreach ($giftCardList as $giftCard) {
                            if ($giftCard['status'] == 2) {
                                $giftCardAPI -> updateCard("card_sn = '{$giftCard['card_sn']}'", array('status' => 0));
                            }
                        }
                    }
                }
            }
            else {
                foreach ($giftCardList as $giftCard) {
                    if ($giftCard['status'] == 0) {
                        $giftCardAPI -> updateCard("card_sn = '{$giftCard['card_sn']}'", array('status' => 2));
                    }
                }
            }
            
            //只有礼品卡的已签收，修改订单类型
            if ($onlyGiftCard && $order['status_logistic'] == 4) {
                if ($order['status'] != 5) {
                    $set['status'] = 5;
                }
            }
        }
        
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $set);
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
        $adjust['price_adjust_return'] && $order['price_adjust_return'] = $adjust['price_adjust_return'];
        //从新计算订单各个金额，订单支付状态 结束
        
        $data = array_merge($productDetail,
                            array('order' => $order),
                            array('adjust' => $adjust),
                            array('batch_log' => $batchLog),
                            array('pay_log' => $payLog));
        $data['other']['price_adjust'] = $order['price_adjust'];
        $data['other']['price_logistic'] = $order['price_logistic'];
        $data['other']['price_order'] = $order['price_order'];
        $data['other']['price_pay'] = $order['price_pay'];
        $data['other']['price_payed'] = $order['price_payed'];
        $data['other']['account_payed'] = $order['account_payed'];
        $data['other']['point_payed'] = $order['point_payed'];
        $data['other']['gift_card_payed'] = $order['gift_card_payed'];
        $data['other']['price_from_return'] = $order['price_from_return'];
        $data['other']['price_before_return'] = $order['price_before_return'];
        $data['other']['price_blance'] = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
        if ($data['other']['price_blance'] > 0) {
            $data['other']['price_must_pay'] = $data['other']['price_blance'];
        }
        //处理退货时的退款，优先顺序：账户余额 -> 积分 -> 礼品卡 -> 现金
        $blanceMoney = $data['other']['price_blance'];
        if ($blanceMoney < 0) {
            $blanceMoney = abs($blanceMoney);
            $returnAccount = $returnPoint = $returnGift = $returnMoney = 0;
            if ($order['account_payed'] > 0) {
                if ($order['account_payed'] >= $blanceMoney) {
                    $returnAccount = $blanceMoney;
                    $blanceMoney = 0;
                }
                else {
                    $returnAccount = $order['account_payed'];
                    $blanceMoney -= $order['account_payed'];
                }
            }
            if ($blanceMoney > 0 && $order['point_payed'] > 0) {
                if ($order['point_payed'] >= $blanceMoney) {
                    $returnPoint = $blanceMoney;
                    $blanceMoney = 0;
                }
                else {
                    $returnPoint = $order['point_payed'];
                    $blanceMoney -= $order['point_payed'];
                }
            }
            if ($blanceMoney > 0 && $order['gift_card_payed'] > 0) {
                if ($order['gift_card_payed'] >= $blanceMoney) {
                    $returnGift = $blanceMoney;
                    $blanceMoney = 0;
                }
                else {
                    $returnGift = $order['gift_card_payed'];
                    $blanceMoney -= $order['gift_card_payed'];
                }
            }
            if ($blanceMoney > 0) {
                $returnMoney = $blanceMoney;
            }
        }
            
        $data['finance']['price_return_logistic'] = -$adjust['price_adjust_return_logistic_back'];  //退货 退运费
        $data['finance']['price_return_money'] = $returnMoney + $adjust['price_adjust_return_logistic_back'];
        $data['finance']['price_return_point'] = $returnPoint;
        $data['finance']['price_return_gift'] = $returnGift;
        $data['finance']['price_return_account'] = $returnAccount;
        $data['finance']['price_return_all_money'] = $order['price_payed'] + $order['price_from_return'];
        $data['finance']['price_return_all_point'] = abs($order['point_payed']);
        $data['finance']['price_return_all_gift'] = abs($order['gift_card_payed']);
        $data['finance']['price_return_all_account'] = abs($order['account_payed']);
        $data['finance']['price_return_all'] = $data['finance']['price_return_all_money'] + $data['finance']['price_return_all_point'] + $data['finance']['price_return_all_account'] + $data['finance']['price_return_all_gift'];
        $data['finance']['price_return'] = $data['finance']['price_return_money'] + $data['finance']['price_return_point'] + $data['finance']['price_return_account'] + $data['finance']['price_return_gift'];
        
        $status_return = $status_return_all = 0;
        if ($data['finance']['price_return'] > $financeReturning['amount']) {
            $status_return = 1;
        }
        if ($order['price_payed'] > 0) {
            $status_return_all = 1;
        }
        $data['finance']['status_return'] = $status_return;
        $data['finance']['status_return_all'] = $status_return_all;
        
        return $data;
    }




    /**
     * @param   string   $batchSN
     * @return  array
     */
    public function _avgDetail($batchSN,$order)
    {
        $data = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, '(number-return_number)>' => 0 ));

        // 临时解决老订单cost为空的问题。修改日期:2013/11/20，一周后删除
        $groupGoodsAPI = new Admin_Models_API_GroupGoods();

        foreach($data as $key => $val) {
            if ($val['product_id'] && in_array($val['type'],array('0','1','6','7'))) {
                $product = array_shift($this -> _product -> get(array('product_id' => $val['product_id'])));
                $data[$key]['cost'] = $product['cost'];
            } else if (empty($val['parent_id']) && $val['type'] == '5') {
                $info = $groupGoodsAPI -> getGroupgoogs("group_sn = '{$val['product_sn']}'");
                $groupGoods = $groupGoodsAPI -> fetchConfigGoods(array('group_goods_config' => $info['group_goods_config']));
                $total_cost = 0;
                if ($groupGoods) {
                    foreach ($groupGoods as $group_goods) {
                        $total_cost += $group_goods['cost'] * $group_goods['number'];
                    }
                }
                $data[$key]['cost'] = $total_cost;
            }
        }
        //////////

		foreach ($data as $k => $v) {
			if (($v['product_id'] && in_array($val['type'],array('0','1','6','7'))) || (empty($v['parent_id']) && $v['type'] == '5')) {
                $a+= $v['cost']* ($v['number']-$v['return_number']);
			}
		}
		foreach ($data as $k => $v) {
			if (($v['product_id'] && in_array($val['type'],array('0','1','6','7'))) || (empty($v['parent_id']) && $v['type'] == '5')) {
                @$avg_price =  $v['cost']* ($v['number']-$v['return_number'])/$a * ($order['price_goods']+$order['price_adjust']) / ($v['number']-$v['return_number']);
				$this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,'order_batch_goods_id' => $v['order_batch_goods_id']),
				array('avg_price' => $avg_price));
			}else if ($v['product_id'] && $v['parent_id'] && $v['type'] == '5') {
                $avg_price =  $v['cost']* ($v['number']-$v['return_number'])/$a * ($order['price_goods']+$order['price_adjust']) / ($v['number']-$v['return_number']);
				$this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,'order_batch_goods_id' => $v['order_batch_goods_id']),
				array('avg_price' => $avg_price));
			}

		}
	}


    /**
     * 取订单商品 并且处理相对应的活动
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function _productDetail($batchSN, $logicArea = 1)
    {
        $data = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'number>' => 0));
        if ($data) {
            $stockAPI = new Admin_Models_API_Stock();
            $stockAPI -> setLogicArea($logicArea);
            foreach ($data as $k => $v) {
                if ($v['product_id']) {
                    //读取库存资料 开始
                    $stock = $stockAPI -> getSaleProductStock($v['product_id'], true);
                    $v['able_number'] = $stock['able_number'];
                    //读取库存资料 结束
                    $product[$v['order_batch_goods_id']] = $v;//实体商品
                }
                $productAll[$v['order_batch_goods_id']] = $v;//包括实体商品，立减等等非实体商品
            }
            
            //设置 商品的从属关系（礼包，商品买赠，商品折扣等等，可以归属到某一个商品上[具有parent_id]）开始
            foreach ($productAll as $id => $v) {
                if ($v['parent_id']) {
                    if ($productAll[$v['parent_id']]) {
                        $productAll[$v['parent_id']]['child'][] = $v;
                    }
                    unset($productAll[$id]);
                }
            }
            //设置 商品的从属关系 结束
            foreach ($productAll as $id => $v) {
                $productAll[$id]['blance'] = $v['blance'] = $v['number'] - $v['return_number'];//该商品可用数量
                $productAll[$id]['amount'] = $v['amount'] = $v['sale_price'] * ($v['number'] - $v['return_number']);//该商品总金额

                //转换活动信息输出资料 开始
                if ($v['child']) {
                    foreach ($v['child'] as $x => $y) {
                       $productAll[$id]['child'][$x]['blance'] = $y['blance'] = $y['number'] - $y['return_number'];
                       $productAll[$id]['child'][$x]['amount'] = $y['amount'] = $y['sale_price'] * $y['number'];
                       if ($y['offers_type'] == 'buy-gift') {//商品赠品
                            unset($productAll[$id]['child'][$x]['sale_price'], $productAll[$id]['child'][$x]['amount']);
                       }  else if ($y['offers_type'] == 'assign-goods') {//指定 商品
                         unset($productAll[$id]['child'][$x]['sale_price'], $productAll[$id]['child'][$x]['amount']);
                       }  else if ($y['offers_type'] == 'fixed') {//商品特价
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name'], 'sale_price' => $y['sale_price'], 'number' => $y['number'], 'return_number' => 0, 'amount' => $y['amount']);
                       } else if ($y['offers_type'] == 'discount') {//商品折扣
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name']);
                       } else if ($y['offers_type'] == 'discount-adv') {//商品第N件折扣
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name']);
                       } else if ($y['offers_type'] == 'exclusive') {//站外专享
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name'], 'sale_price' => $y['sale_price'], 'number' => $y['number'], 'return_number' => 0, 'amount' => $y['amount']);
                       } else if ($y['offers_type'] == 'group-exclusive') {//站外专享(组合商品)
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name'], 'sale_price' => $y['sale_price'], 'number' => $y['number'], 'return_number' => 0, 'amount' => $y['amount']);
                       } else if ($y['offers_type'] == 'price-exclusive') {//站内专享
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name'], 'sale_price' => $y['sale_price'], 'number' => $y['number'], 'return_number' => 0, 'amount' => $y['amount']);
                       } else if ($y['offers_type'] == 'buy-minus') {//商品立减
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name']);
                       } else if ($y['offers_type'] == 'quantity-discounts') {//多件商品折扣
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name']);
                       } else if ($y['offers_type'] == 'tuan') {//团购
                            $productAll[$id]['child'][$x] = array('goods_name' => $y['offers_name'], 'sale_price' => $y['sale_price'], 'number' => $y['number'], 'return_number' => 0, 'amount' => $y['amount']);
                       }
                    }
                }
                if ($v['offers_type'] == 'order-gift') {//订单赠品 满300 + n元
                    $productAll[$id] = array('goods_name' => '<font color=red>[订单赠品]</font>' . $v['goods_name'],
                                              'product_sn' =>  $v['product_sn'],
                                              'order_batch_goods_id' => $v['order_batch_goods_id'],
                                              'product_id' => $v['product_id'],
                                              'blance' => $v['blance'],
                                              'eq_price' => $v['eq_price'],
                                              'number' =>  $v['number'],
                                              'amount' => $v['amount']);
                }
                if ($v['offers_type'] == 'order-buy-gift') {//订单赠品 赠送购物袋
                    $productAll[$id] = array('goods_name' => '<font color=red>['.$v['offers_name'].']</font>' . $v['goods_name'],
                                              'product_sn' =>  $v['product_sn'],
                                              'order_batch_goods_id' => $v['order_batch_goods_id'],
                                              'product_id' => $v['product_id'],
                                              'blance' => $v['blance'],
                                              'eq_price' => $v['eq_price'],
                                              'number' =>  $v['number'],
                                              'amount' => $v['amount']);
                }

                if ($v['child'] &&
                    ($v['offers_type'] == 'fixed-package' ||
                    $v['offers_type'] == 'choose-package'))
                {//定额礼包、自选礼包
                    unset($orgAmount);
                    foreach ($productAll[$id]['child'] as $kk => $vv) {
                        $orgAmount += $vv['price'];
                    }
                    $productAll[$id] = array('goods_name' => $v['goods_name'] . "￥{$v['amount']}",
                                             'offers_type' => $v['offers_type'],
                                             'price' => $v['price'],
                                             'remark' => $v['remark'],
                                             'org_amount' => $orgAmount,//礼包内商品原始总价 用于礼包均价计算用
                                             'child' => $productAll[$id]['child']);
                }
                else if (!$v['child'] && !$v['product_id'] &&
                           ($v['offers_type'] == 'fixed-package' || $v['offers_type'] == 'choose-package'))
                {//礼包没有商品 删除礼包
                    unset($productAll[$id],$product[$v['order_batch_goods_id']]);
                }
                if ($v['offers_type'] == 'minus') {//订单立减
                    $productAll[$id] = array('goods_name' => $v['goods_name'], 'amount' => $v['amount']);
                    $priceMinus += abs($v['amount']);
                }
                if ($v['card_sn']) {//卡
                    $productAll[$id] = array('order_batch_goods_id' => $v['order_batch_goods_id'],
                                             'goods_name' => $v['goods_name'],
                                             'number' => $v['number'],
                                             'amount' => $v['amount'],
											 'sale_price' => $v['sale_price'],
                                             'card_sn' => $v['card_sn'],
                                             'card_type' => $v['card_type']);
                    if ($v['card_type'] == 'coupon') {//礼券
                        $priceCoupon += abs($v['amount']);
                    } else if ($v['card_type'] == 'goods') {//提货卡
                        $priceCoupon += abs($v['amount']);
                    }
                }
                //转换活动信息输出资料 结束
            }
            
            //把礼包价格 按商品原价权重 均摊到各个商品上 开始
            if ($productAll) {
                foreach ($productAll as $k => $v) {
                    if ($v['offers_type'] == 'choose-package' || $v['offers_type'] == 'fixed-package') {
                        unset($amount);
                        foreach ($v['child'] as $kk => $vv) {
                            if ($v['org_amount'] == 0) {
                                $productAll[$k]['child'][$kk]['sale_price'] = floor($v['price']/count($v['child']));
                            } else {
                                $productAll[$k]['child'][$kk]['sale_price'] = floor($vv['price']/$v['org_amount']*$v['price']);
                            }
                            $product[$vv['order_batch_goods_id']]['sale_price'] = $productAll[$k]['child'][$kk]['sale_price'];
                            $amount += $productAll[$k]['child'][$kk]['sale_price'];
                            $this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,
                                                                        'order_batch_goods_id' => $vv['order_batch_goods_id']),
                                                                  array('sale_price' => $productAll[$k]['child'][$kk]['sale_price']));
                        }
                        $blance = $v['price'] - $amount;
                        if ($blance) {
                            $productAll[$k]['child'][$kk]['sale_price'] += $blance;
                            $product[$vv['order_batch_goods_id']]['sale_price'] += $blance;
                            $this -> _db -> updateOrderBatchGoods(array('batch_sn' => $batchSN,
                                                                        'order_batch_goods_id' => $vv['order_batch_goods_id']),
                                                                  array('sale_price' => $productAll[$k]['child'][$kk]['sale_price']));
                        }

                    }
                }
            }
            //把礼包价格 按商品原价权重 均摊到各个商品上 结束
            
            //计算所有商品的各类总价格 开始
            
            $ggArr = array();//组合商品group_id存储用
            $goodsNumber = 0;
            foreach ($product as $k => $v) {
            	//start::记录组合商品group_id
            	if($v['parent_id'] && $v['type']==5 && !in_array($v['parent_id'], $ggArr)){
            		array_push($ggArr, $v['parent_id']);
            	}
            	//end::记录组合商品group_id
            	
            	if ($v['type'] != 5){
	                $priceGoods += $v['sale_price'] * ($v['number'] - $v['return_number']);//商品总金额(不包含退货商品)
	                $priceGoodsEq += $v['eq_price'] * ($v['number'] - $v['return_number']);//均摊了订单立减，调整金额，礼金券后商品总金额
	                if (($v['number'] - $v['return_number']) > 0) {
	                    $priceGoodsEq += $v['eq_price_blance'];
	                }
                    
	                $priceGoodsAll += $v['sale_price'] * $v['number'];//商品总金额(包含退货商品)
            	}
            	$goodsNumber += $v['number'] - $v['return_number'];
            }
            
            //start::组合商品价格计算
            if(count($ggArr)){
            	foreach ($ggArr as $k => $v){
            		$priceGoods += $productAll[$v]['sale_price'] * ($productAll[$v]['number'] - $productAll[$v]['return_number']);
            		$priceGoodsEq += $productAll[$v]['eq_price'] * ($productAll[$v]['number'] - $productAll[$v]['return_number']);

	                if (($productAll[$v]['number'] - $productAll[$v]['return_number']) > 0) {
	                    $priceGoodsEq += $productAll[$v]['eq_price_blance'];
	                }

            		$priceGoodsAll += $productAll[$v]['sale_price'] * ($productAll[$v]['number'] - $productAll[$v]['return_number']);
            	}
            }
            //end::组合商品价格计算
            
            //计算所有商品的各类总价格 结束
            /* Start::计算组合商品的库存 */
            foreach ($productAll as $index => $val){
            	if($val['type']==5 && $val['child']){
            		foreach ($val['child'] as $value){
            		    if ( !$value['number'] )    continue;
            			$count[] = (int)round($value['able_number']/$value['number']);
            		}
            		$productAll[$index]['able_number'] = min($count);
            	}
            }
            /* End::计算组合商品的库存 */
        }
        
        return array('product_all' => $productAll,//只要是 order_goods表的都读进来
                     'product' => $product,//实体商品
                     'price_minus' => -abs($priceMinus),//订单立减
                     'price_coupon' => -abs($priceCoupon),//礼券 新版
                     'goods_number' => $goodsNumber,
                     'other' => array('price_goods_all' => $priceGoodsAll,//商品总金额(包含退货商品)
                                      'price_goods' => $priceGoods,//商品总金额(不包含退货商品)
                                      'price_goods_eq' => $priceGoodsEq));//均摊了订单立减，调整金额，礼金券后的商品总金额[均摊价的累加]
    }
    /**
     * 处理订单调整金额
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function _adjustDetail($batchSN)
    {
        $adjust = $this -> _db -> getOrderBatchAdjust(array('batch_sn' => $batchSN));
        if ($adjust) {
            $priceAdjustReturn = $priceAdjustReturnLogisticTo = $priceAdjustReturnLogisticBack = $priceAdjustChange = $priceAdjustChangeLogisticTo = $priceAdjustChangeLogisticBack = $priceAdjust = 0;
            foreach ($adjust as $tmp) {
                if ($tmp['type'] == 10) {//退货调整金额
                    $priceAdjustReturn += $tmp['money'];
                } else if ($tmp['type'] == 11) {//退货退邮寄给顾客的运费
                    $priceAdjustReturnLogisticTo += $tmp['money'];
                } else if ($tmp['type'] == 12) {//退货退顾客邮寄回的运费
                    $priceAdjustReturnLogisticBack += $tmp['money'];
                } else if ($tmp['type'] == 20) {//换货调整金额
                    $priceAdjustChange += $tmp['money'];
                } else if ($tmp['type'] == 21) {//换货退邮寄给顾客的运费
                    $priceAdjustChangeLogisticTo += $tmp['money'];
                } else if ($tmp['type'] == 22) {//换货退顾客邮寄回的运费
                    $priceAdjustChangeLogisticBack += $tmp['money'];
                } else {//未确认订单调整金额
                    $priceAdjust += $tmp['money'];
                }
            }
        }
        return array('adjust' => $adjust,
                     'price_adjust' => $priceAdjust + $priceAdjustChange,//这个调整金额是要均摊到各个商品上的
                     'price_adjust_return' => $priceAdjustReturn,//退货亏掉的调整金额 不参与联盟分成计算
                     'price_adjust_return_logistic_to' => $priceAdjustReturnLogisticTo,//退货亏掉的运费 不参与联盟分成计算
                     'price_adjust_return_logistic_back' => $priceAdjustReturnLogisticBack,//退货亏掉的运费 不参与联盟分成计算
                     'price_adjust_change' => $priceAdjustChange,//换货调整金额和未确认单的调整金额概念一致所以合并到 price_adjust
                     'price_adjust_change_logistic_to' => $priceAdjustChangeLogisticTo,//换货时，已加入流转金额到新单
                     'price_adjust_change_logistic_back' => $priceAdjustChangeLogisticBack);//换货时，加入流转金额到新单
    }
	/**
     * 添加一条支付记录
     *
     * @param   array     $data
     * @return  int
     */
    public function addOrderPayLog($data)
    {
        $time = time();
        $data['pay_log_id'] = $data['batch_sn'] . '-' . $time;
        $data['add_time'] = $time;
        $this -> _db -> addOrderPayLog($data);
    }

	/**
     * 删除指定订单的支付记录
     *
     * @param   array     $data
     * @return  int
     */
    public function delOrderPayLog($batch_sn)
    {
        $this -> _db -> delOrderPayLog($batch_sn);
    }

	/**
     * 取得指定条件的订单日志
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function _batchLogDetail($batchSN)
    {
        $data = $this->_db->getOrderBatchLog(array('batch_sn' => $batchSN));
        if (is_array($data) && count($data)) {
            foreach ($data as $k => $v) {
                $data[$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            }
        }
        return $data;

    }
	/**
     * 取得指定条件的订单支付日志
     *
     * @param   string   $batchSN
     * @return  array
     */
    public function _batchPayLogDetail($batchSN)
    {
        $data = $this->_db->getOrderBatchPayLog($batchSN);
        if (is_array($data) && count($data)) {
            foreach ($data as $k => $v) {
                $data[$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            }
        }
        return $data;

    }
    /**
     * 统计订单金额信息
     *
     * @param   string      $orderSN
     * @return  array
     */
    public function orderPriceStatus($orderSN)
    {
        $order = $this -> _db -> getOrderBatch(array('order_sn' => $orderSN, 'status' => 0));

        if ($order) {
            foreach ($order as $batch) {
                if ($batch['status_logistic'] == 5) {
                    continue;//该批次的订单为拒收状态，不参与联盟分成
                }
                $batchSN = $batch['batch_sn'];
                $data = $this -> orderDetail($batchSN);
                $priceGoods += $data['other']['price_goods'];
                $priceOrder += $data['other']['price_order'];
                $priceLogistic += $data['other']['price_logistic'];//运费不给分成
                //退来回运费 和 退货调整金额产生的亏损 这个要补给联盟
                $priceAdjustForReturn += $data['adjust']['price_adjust_return'];
                $priceAdjustForReturn += abs($data['adjust']['price_adjust_return_logistic_to']);
                $priceAdjustForReturn += abs($data['adjust']['price_adjust_return_logistic_back']);

            }
        }
        //注意：$priceAdjustForReturn 是我们损失的费用，已经计算到订单金额里了，所以给联盟分成的时候，需要补上。
        $affiliateAmount = $priceOrder - abs($priceLogistic) + $priceAdjustForReturn;// - abs($priceAccount);帐户余额允许分成
        if ($affiliateAmount < 0) {
            $affiliateAmount = 0;
        }
        $data = array('price_goods' => $priceGoods,
                      'price_order' => $priceOrder,
                      'price_pay' => $priceOrder,
                      'affiliate_amount' => $affiliateAmount);//可分成金额
        return $data;
    }
    /**
     * 申请出库
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function out($batchSN, $splitData = null, $logicArea = 1, $outStockSN = null)
    {
        if (is_array($batchSN)) {   //合并订单
            $batchSNArray = $batchSN;
        }
        else {
            $batchSNArray = array($batchSN);
        }

        $time = time();
        $fields = "a.price_order,a.point_payed,a.batch_sn,a.addr_consignee,a.addr_province,a.addr_city,a.addr_area,a.addr_province_id,a.addr_city_id,a.type,
	        	   a.addr_area_id,a.addr_address,a.addr_zip,a.addr_tel,a.addr_mobile,a.note_print,a.note_logistic,a.logistic_name,a.logistic_code,a.logistic_price,a.logistic_price_cod,a.logistic_list,a.pay_type,b.shop_id,b.invoice,b.invoice_type,b.invoice_content";
	    $bill_no = '';
	    $remark = '';
	    $logistic_price = 0;
	    $logistic_price_cod = 0;
	    $logistic_fee_service = 0;
	    $print_remark = '';
	    $amount = 0;
	    $weight = 0;
	    $volume = 0;
	    $goods_number = 0;
	    $is_assign = 0;
	    $logistic = '';

	    $details = array();

        foreach ($batchSNArray as $batchSN) {
            if (!$batchSN)  continue;

            $this -> _updateOrderBatchLogistic($batchSN);
            $r = array_shift($this -> _db -> getOrderBatchInfo(array('batch_sn' => $batchSN), $fields));
            $bill_no .= $batchSN.',';
            $remark .= $r['note_logistic'] ? $r['note_logistic'].',' : '';
            $logistic_price += $r['logistic_price'];
            $logistic_price_cod += $r['logistic_price_cod'];
            $logistic_fee_service += $r['logistic_fee_service'];
            $print_remark .= $r['note_print'] ? $r['note_print'].',' : '';

            $info = Zend_Json::decode($r['logistic_list']);
            $amount += $info['other']['amount'];
            $weight += empty($info['other']['weight']) ? 1 : $info['other']['weight'];//todo
            $volume += empty($info['other']['volume']) ? 1 : $info['other']['volume'];//todo
            $goods_number += $info['other']['number'];
            $logistic = $info['other']['cod'] ? $info['list'][$info['other']['default_cod']]: $info['list'][$info['other']['default']];

            //自提不用物流派单
            if ($r['logistic_code'] == 'self' || $r['logistic_code'] == 'externalself') {
                $is_assign = 1;
            }
            $bill_type = 1;
            $outstock_bill_type = 1;

            //设置财务结款金额
            $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('balance_amount' => $r['price_order'], 'balance_point_amount' => $r['point_payed']));
            
            $where = array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0, '(number-return_number)>' => 0);
            $product = $this -> _db -> getOrderBatchGoods($where);
            if ($product) {
                foreach($product as $k => $v) {
                    if ($details[$v['product_id']]) {
                        $details[$v['product_id']]['number'] += $v['number'] - $v['return_number'];
                        $details[$v['product_id']]['shop_price'] = $v['eq_price'];
                    } else {
                        $details[$v['product_id']] = array ('product_id' => $v['product_id'],
                                                            'number' => $v['number'] - $v['return_number'],
                                                            'shop_price' => $v['eq_price'],
                                                            'status_id' => 2);
                    }
                }
            }
        }

        $bill_no = substr($bill_no, 0, -1);

        $rowOutStock = array ('lid' => $logicArea,
                              'bill_no' => $bill_no,
                              'bill_type' => $outstock_bill_type,
                              'bill_status' => 3,
                              'remark' => substr($remark, 0, -1),
                              'add_time' => $time,
                              'admin_name' => $this -> _auth['admin_name']
                             );

        $row['add_time'] = $time;
        $row['bill_no'] = $bill_no;
        $row['admin_name'] = $this -> _auth['admin_name'];
        $row['logistic_name'] = $r['logistic_name'];
        $row['logistic_code'] = $r['logistic_code'];
        $row['logistic_price'] = $logistic_price;
        $row['logistic_price_cod'] = $logistic_price_cod;
        $row['logistic_list'] = $r['logistic_list'];
        $row['logistic_fee_service'] = $logistic_fee_service;
        $row['consignee'] = $r['addr_consignee'];
        $row['province'] = $r['addr_province'];
        $row['city'] = $r['addr_city'];
        $row['area'] = $r['addr_area'];
        $row['province_id'] = intval($r['addr_province_id']);
        $row['city_id'] = intval($r['addr_city_id']);
        $row['area_id'] = intval($r['addr_area_id']);
        $row['address'] = $r['addr_address'];
        $row['zip'] = $r['addr_zip'] ? $r['addr_zip'] : $this -> _db -> getAreaZip($r['addr_area_id']);
        $row['tel'] = $r['addr_tel'];
        $row['mobile'] = $r['addr_mobile'];
        $row['print_remark'] = substr($print_remark, 0, -1);
        $row['remark'] = substr($remark, 0, -1);
        $row['bill_type'] = $bill_type;
        $row['shop_id'] = intval($r['shop_id']);
        $row['invoice'] = $r['invoice'];
        $row['invoice_type'] = $r['invoice_type'];
        $row['invoice_content'] = $r['invoice_content'];
        $row['amount'] = $amount;
        $row['weight'] = $weight;
        $row['volume'] = $volume;
        $row['goods_number'] = $goods_number;
        $row['is_cod'] = $r['pay_type'] == 'cod' ? 1 : 0;
        $row['search_mod'] = $logistic['search_mod'] ? $logistic['search_mod'] : '';
        $row['is_assign'] = $is_assign; //自提

        $transport = new Admin_Models_DB_Transport();
        $transportAPI = new Admin_Models_API_Transport();
        $outStock = new Admin_Models_API_OutStock();

        if (!$transport -> get("bill_no='{$bill_no}' and is_cancel=0")) {
            if ($splitData) {   //拆单
                $index = 1;
                foreach ($splitData as $split) {
                    $row['bill_no'] = $bill_no.'-'.$index++;
                    $rowOutStock['bill_no'] = $row['bill_no'];
                    $splitDetail = '';
                    $goods_number = 0;
                    foreach ($split as $product) {
                        $splitDetail[$product['product_id']] = array('product_id' => $product['product_id'],
                                                                     'number' => $product['number'],
                                                                     'shop_price' => $details[$product['product_id']]['shop_price'] ? $details[$product['product_id']]['shop_price'] : 0 ,
                                                                     'status_id' => $details[$product['product_id']]['status_id'],
                                                                    );
                        $goods_number += $product['number'];
                    }
                    $row['goods_number'] = $goods_number;
                    $tid = $transport -> insert($row);
					
                    $outstock_id = $outStock -> insertApi($rowOutStock, $splitDetail, $logicArea, true);
                    
					if ($tid > 0 && $outstock_id > 0) {
						$source_params = array(
							'bill_no'      => $row['bill_no'],
							'transport_id' => $tid,
							'outstock_id'  => $outstock_id,
						);
						$transport->insertRelationOrder($source_params);
					}

                    $transportAPI -> addOp($tid, $this -> _auth['admin_name'], 'prepare', '');
                }
                
                return true;
            }
            else {
				$bill_nos = explode(',', $bill_no);
                $row['validate_sn'] = $transportAPI -> getValidateSn();
                $tid = $transport -> insert($row);
                $transportAPI -> addOp($tid, $this -> _auth['admin_name'], 'prepare', '');
                if ($tid) {
                    if ($outStockSN) {
                        $outStockData = array_shift($outStock -> get("b.bill_no = '{$outStockSN}'"));
                        $outStock -> update(array('bill_no' => $rowOutStock['bill_no'], 'bill_status' => 3), "bill_no = '{$outStockSN}'");
                        
                        $source_params = array('bill_no' => $rowOutStock['bill_no'],
						        	           'transport_id' => $tid,
									           'outstock_id'  => $outStockData['outstock_id'],
								              );
						$transport -> insertRelationOrder($source_params);
						    
                        return true;
                    }
                    else {
                        $outstock_id =  $outStock -> insertApi($rowOutStock, $details, $logicArea, true);
						if ($tid > 0 && $outstock_id > 0) {
							foreach ($bill_nos as $no) {
								$source_params = array(
									'bill_no'      => $no,
									'transport_id' => $tid,
									'outstock_id'  => $outstock_id,
								);
								$transport->insertRelationOrder($source_params);
							}
						}

						return $outstock_id;
                    }
                }
            }
        }
    }

    /**
     * 申请退货入库
     *
     * @param   string      $batchSN
     * @param   array       $product
     * @param   string      $remark
     * @param   int         $type
     * @return  void
     */
    public function in($batchSN, $product, $remark, $type = 1)
    {
		if (!$product)  return false;

		$time = time ();
		$billNo = Custom_Model_CreateSn::createSn();
	    $row = array ('lid' => 1,
		              'bill_no' => $billNo,
		              'item_no' => $batchSN,
                      'bill_type' => $type,
                      'bill_status' => 3,
			          'remark' => $remark,
                      'add_time' => $time,
                      'admin_name' => $this -> _auth['admin_name']);
        
	    $outStockAPI = new Admin_Models_API_OutStock();
	    $details = $outStockAPI -> getDetail("b.bill_no like '%{$batchSN}%'");
        
	    if (!$details)  return false;
        
	    foreach ($details as $detail) {
	        $outStockInfo[$detail['product_id']][] = array('batch_id' => $detail['batch_id'],
	                                                       'number' => $detail['number'],
	                                                      );
            $productCostInfo[$detail['product_id']] = $detail['cost'];
	    }
        
	    $details = array();
        foreach($product as $productID => $v) {
            if (!$outStockInfo[$productID])  return false;

            $number = $v['number'];
            foreach ($outStockInfo[$productID] as $stock) {
                if ($number <= $stock['number']) {
                    $number = 0;
                    break;
                }
                else {
                    $number -= $stock['number'];
                }
            }
            if ($number > 0)    return false;
            
            $details[] = array ('product_id' => $productID,
                                'batch_id' => 0,
                                'status_id' => 6,
                                'plan_number' => $v['number'],
                                'shop_price' => $productCostInfo[$productID] ? $productCostInfo[$productID] : 0);
        }
        
        //如果只有虚拟商品，状态设为待收货
        foreach($product as $productID => $v) {
            if ($v['type'] == 7) {
                $hasVitual = true;
                if (!isset($onlyVitual)) {
                    $onlyVitual = true;
                }
            }
            else {
                $onlyVitual = false;
            }
        }
        if ($onlyVitual) {
            $row['bill_status'] = 6;
        }
        
        $inStock = new Admin_Models_API_InStock();
		$inStock -> insertApi($row, $details, 1, true);
		
		//虚拟商品设为作废
		if ($hasVitual) {
		    $vitualGoods = new Admin_Models_API_VitualGoods();
		    foreach($product as $productID => $v) {
		        if ($v['type'] == 7) {
		            $vitualGoods -> cancelByBatchSN($batchSN, $productID, $v['number']);
		        }
		    }
		}
		
		//丢件处理
		if ($type == 13) {
		    $inStock -> receiveByBillNo($billNo);
		}
		
		return $row['bill_no'];
    }
    
    /**
     * 更新物流信息
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function _updateOrderBatchLogistic($batchSN)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $logistic = $this -> _getLogistic($order);

        if ($order['pay_type'] == 'cod') {//代收款
            $tmp = $logistic['list'][$logistic['other']['default_cod']];
        } else {
            $tmp = $logistic['list'][$logistic['other']['default']];
        }
        $data = array(
                      'logistic_list' => Zend_Json::encode($logistic),
					  );
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
    }
	/**
     * 匹配物流公司
     *
     * @param    array    $order
     * @return   string
     */

    public function _getLogistic($order)
    {
        if (!$order['addr_area_id']) {
            return false;
        }
        $productStatus = $this -> _orderBatchProductStatus($order['batch_sn']);
        $data = $this -> _db -> getLogistic(array('area_id' => $order['addr_area_id'],'pay_type'=> $order['pay_type']));
        $pay = $order['price_pay'] - ($order['price_payed'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'] + $order['price_from_return']);
        
        if ($data) {
            foreach ($data as $k => $v) {
                if ($v['cod']) {
                    if (!$tmp['cod'] || $v['is_send'] == 'OK' ) {
                        $tmp['cod'] = array('logistic_code' => $v['logistic_code'],
                                            'price' => $v['price'],
                                            'cod_price' => $v['cod_price']);
                    }
                }
                if (!$tmp['all'] || $v['is_send'] == 'OK' ) {
                    $tmp['all'] = array('logistic_code' => $v['logistic_code'], 'price' => $v['price']);
                }
                $logistic['list'][$v['logistic_code']] = $v;
            }
            $areaID = $where['area_id'];
        }
        
        if ($logistic['list']) {
            $logistic['other'] = array('name' => $name,
                                       'cod' => intval($cod),
                                       'zip' => $order['addr_zip'],
                                       'province' => $order['addr_province'],
                                       'city' => $order['addr_city'],
                                       'area' => $order['addr_area'],
                                       'area_id' => $order['addr_area_id'],
                                       'address' => $order['addr_address'],
                                       'amount' => $pay,
                                       'volume' => $productStatus['product_volume'],
                                       'number' => $productStatus['product_number'],
                                       'weight' => empty($productStatus['product_weight']) ? 1 : $productStatus['product_weight'],
                                       'default' => $logistic['list'][$tmp['all']['logistic_code']]['logistic_code'],
                                       'default_cod' => $logistic['list'][$tmp['cod']['logistic_code']]['logistic_code']);
        }
        return $logistic;
    }
    /**
     * 订单恢复 冻结在财务中0 和 1 状态的记录
     *
     * @param   string      $batchSN
     * @return  array
     */
    function undoFinance($batchSN)
    {
        $tmp = $this -> _finance -> getLastFinanceByItemNO(1, $batchSN);
        if ($tmp['status'] <= 1) {//0:未通过其他部门审核 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效
            $this -> _finance -> updateFinance($tmp['finance_id'], array('status' => 4));
        }
    }
	/**
     * 更新当前订单金额信息
     *
     * @param   string      $batchSN
     * @param   array      $bank
     * @return  array
     */
    function addFinance($batchSN, $bank, $pay, $logistic, $point, $account, $gift, $status, $shop_id, $type = 1, $way = 1, $delivery = 1)
    {
        $tmp = $this -> _finance -> getLastFinanceByItemNO(1, $batchSN);
        if ($tmp['status'] <= 1) {//0:未通过其他部门审核 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效
            $this -> _finance -> updateFinance($tmp['finance_id'], array('status' => 4));
        }
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
        unset($order['logistic_list'],$order['addr_province_option'],$order['addr_city_option'],$order['addr_area_option']);
        $product = $this -> orderDetail($batchSN);
        $orderData = array('order' => $order, 'product' => $product['product_all']);
        $data = array('shop_id' => $shop_id ? $shop_id : 0,
                      'type' => $type ? $type : 1,
                      'way' => $way ? $way : 1,
                      'item' => 1,
                      'item_no' => $batchSN,
                      'pay' => -$pay,
                      'logistic' => -$logistic,
                      'point' => -$point,
                      'account' => -$account,
                      'gift' => -$gift,
                      'status' => $status,
                      'bank_type' => $bank['type'],
                      'bank_data' => serialize($bank),
                      'order_data' => serialize($orderData),
                      'note' => $bank['note'],
                      'delivery' => $delivery,
                      'callback' => 'updateOrderBatchPayed',
                      'add_time' => time());
        $this -> _finance -> addFrinance($data);//api 申请财务退款

        if ($pay) {
            $title .= '退款金额：￥' . $pay . ';';
        }
        if ($logistic) {
            $title .= '退运费：￥' . $logistic . ';';
        }
        if ($point) {
            $title .= '退积分：￥' . $point . ';';
        }
        if ($account) {
            $title .= '退账户余额：￥' . $account . ';';
        }
        if ($gift) {
            $title .= '退礼品卡：￥' . $gift . ';';
        }

        //添加日志 todo 财务待测
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => $title,
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }

	/**
     * 库存处理接口
     *
     * @param    array    $data
     * @return   void
     */
    public function toStock($orderBatchSN, $callback, $arg=null)
    {
        $batchSNArray = explode(',', $orderBatchSN);
        foreach ($batchSNArray as $index => $batchSN) {
            if ($callback != 'change' || $index == 0) {
                $this -> $callback($batchSN, $arg);
            }
        }
    }

	/**
     * 备货返回 或者 取消
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function back($batchSN, $arg=null)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));

        if ($order['status_back'] == 1) {//取消
            if ($arg['is_check'] == 1) {//同意
                $data = array('status' => 1,
                              'status_logistic' => 0,
                              'status_back' => 0,
                              'lock_name' => '');
                //api 抵用券接口(整退)
                $this -> unUseCardCoupon($batchSN);
                //api 财务接口
                $finance = $this -> _finance -> getLastFinanceByItemNO(1, $batchSN);
                if ($finance['status'] == 0) {//0:未通过其他部门审核 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效
                    $this -> _finance -> updateFinance($finance['finance_id'], array('status' => 1));
                }
                //api 插入联盟数据
                $this -> union($batchSN);
                if (!$arg['prepared']) {
                    $this -> releaseSaleOutStock($batchSN);
                }
            } else if ($arg['is_check'] == 2) {//拒绝
                $data = array('status_back' => 0,
                              'lock_name' => '');
                $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '申请取消被拒绝',
                     'note' => ''.$arg['remark'],
                     'admin_name' => $this -> _auth['admin_name']);
                $this -> _db -> addOrderBatchLog($log);
                //api 财务接口
                $finance = $this -> _finance -> getLastFinanceByItemNO(1, $batchSN);
                if ($finance['status'] == 0) {//0:未通过其他部门审核 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效
                    $this -> _finance -> updateFinance($finance['finance_id'], array('status' => 4));
                }
            }
        } else if ($order['status_back'] == 2) {//返回
            if ($arg['is_check'] == 1) {
                $data = array('status_logistic' => 0, 'status_back' => 0, 'lock_name' => '');
            } else if ($arg['is_check'] == 2) {
                $data = array('status_back' => 0, 'lock_name' => '');
                $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => time(),
                     'title' => '申请返回被拒绝',
                     'note' => ''.$arg['remark'],
                     'admin_name' => $this -> _auth['admin_name']);
                $this -> _db -> addOrderBatchLog($log);
            }
        }

        if ($data) {    //拆单订单可能已经更新过状态
            $where = array('batch_sn' => $batchSN);
            $this -> _db -> updateOrderBatch($where, $data);
        }

    }
	/**
     * 已发货
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function shipped($batchSN, $arg=null)
    {
		$order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
		
		$data = array('status_logistic' => 3,
                      'is_send' => 1,
                      'logistic_no' => $arg['logistic_no'],
                      'logistic_time' => $arg['logistic_time'] ? $arg['logistic_time'] : time(),
                      'lock_name' => '');
        $transportAPI = new Admin_Models_API_Transport();
        $isSplit = $transportAPI -> getSplitOrder($batchSN);
		if ($isSplit) { //拆单
		    $batchSN = $isSplit['batch_sn'];
		    $order = array_shift($this -> _db -> getOrderBatchInfo(array('batch_sn' => $batchSN)));
		    $data['logistic_no'] = $order['logistic_no'];
		    if ($data['logistic_no']) {
		        $data['logistic_no'] .= ',';
		    }
		    $data['logistic_no'] .= $arg['logistic_no'];

		    if ($isSplit['hasSign']) {
		        $data['status_logistic'] = 6;
		    }
		    else if (!$isSplit['allSent']) {
		        $data['status_logistic'] = 2;
		    }
		}

        $where = array('batch_sn' => $batchSN);
        $this -> _db -> updateOrderBatch($where, $data);

        $data = array('is_send' => 1);
        $this -> _db -> updateOrderBatchGoods($where, $data);
        
        //添加应收款记录
        $financeAPI = new Admin_Models_API_Finance();
        $isCredit = $isDistribution = false;
        if ($order['shop_id'] > 0) {
            $shopAPI = new Admin_Models_API_Shop();
            $shopData = $shopAPI -> get(array('shop_id' => $order['shop_id']), 'shop_type');
            if ($shopData['list'][0]['shop_type'] == 'credit') {
                $isCredit = true;
            }
            else if ($shopData['list'][0]['shop_type'] == 'distribution') {
                $isDistribution = true;
            }
        }
        if ($order['pay_type'] == 'cod' || $isCredit || $isDistribution) {
            $receiveData = array('batch_sn' => $batchSN,
                                 'amount' => $order['price_pay'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'],
                                );
            if ($isCredit) {
                $receiveData['pay_type'] = 'credit';
                $receiveData['type'] = 2;
            }
            else if ($isDistribution) {
                $receiveData['pay_type'] = 'distribution';
                $receiveData['type'] = 2;
            }
            else {
                $receiveData['pay_type'] = $order['logistic_code'];
                if ($order['logistic_code'] == 'externalself') {
                    $receiveData['type'] = 3;
                }
                else {
                    $receiveData['type'] = 4;
                }
            }
            $financeAPI -> addFinanceReceivable($receiveData);
        }
        if (!$order['parent_batch_sn'] && ($order['account_payed'] > 0 || $order['point_payed'] > 0 || $order['gift_card_payed'] > 0)) {
            if ($order['account_payed'] > 0) {
                $receiveData = array('batch_sn' => $batchSN,
                                     'type' => 5,
                                     'pay_type' => 'account',
                                     'amount' => $order['account_payed'],
                                    );
                $financeAPI -> addFinanceReceivable($receiveData);
            }
            if ($order['point_payed'] > 0) {
                $receiveData = array('batch_sn' => $batchSN,
                                     'type' => 5,
                                     'pay_type' => 'point',
                                     'amount' => $order['point_payed'],
                                    );
                $financeAPI -> addFinanceReceivable($receiveData);
            }
            if ($order['gift_card_payed'] > 0) {
                $receiveData = array('batch_sn' => $batchSN,
                                     'type' => 5,
                                     'pay_type' => 'gift',
                                     'amount' => $order['gift_card_payed'],
                                    );
                $financeAPI -> addFinanceReceivable($receiveData);
            }
        }
        $this -> addFinanceReceivableForPreGiftCard($this -> orderDetail($batchSN));
        
        //直供单添加结算记录
        if ($order['type'] == 16 && $order['price_order'] > 0) {
            $data = array('batch_sn' => $batchSN,
                          'amount' => $order['price_order'],
                         );
            $financeAPI -> addDistributionSettlement($data);
        }
        
        $_SERVER['SERVER_NAME'] = strtolower($_SERVER['SERVER_NAME']);
        if ($_SERVER['SERVER_NAME']== "jkerp.1jiankang.com") {
			if($order['addr_mobile'] && Custom_Model_Check::isMobile($order['addr_mobile']) && $_SERVER['SERVER_NAME']== "jkerp.1jiankang.com"){
			  $sms = new  Custom_Model_Sms();
				$response = $sms -> send($order['addr_mobile'],"{$order['addr_consignee']}，非常感谢您的订购！您的订单 ".$batchSN." 已从我仓发出，物流公司为".$order['logistic_name']." 运单号".$arg['logistic_no']."  包裹将在近期内送达，请留意。 www.1jiankang.com");
			}
        }

        //插入联盟数据
        //$this -> union($batchSN);
    }
    
	/**
     * 代收货款变更
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function change($batchSN, $arg=null)
    {
        //添加调整金额 $arg['change_amount']
        $time = time();
        $adjust = array('order_sn' => array_shift(explode('_', $batchSN)),
                        'batch_sn' => $batchSN,
                        'type' => 1,
                        'money' => $arg['change_amount'],
                        'note' => '代收款变更金额',
                        'add_time' => $time);
        $this -> _db -> addOrderBatchAdjust($adjust);
        //更新支付状态
        $orderDetail = $this -> orderDetail($batchSN);
        
        //添加系统虚拟退款
        $financeAPI = new Admin_Models_API_Finance();
	    $data = array('shop_id' => $orderDetail['order']['shop_id'],
					  'type' => 0,//系统
					  'way' => 4,
					  'item' => 1,
					  'item_no' => $batchSN,
					  'pay' => $arg['change_amount'],
					  'logistic' => 0,
					  'point' => 0,
					  'account' => 0,
					  'gift' => 0,
					  'status' => 2,
					  'bank_type' => 4,
					  'bank_data' => '',
					  'order_data' => '',
					  'note' => '代收货款变更虚拟退款',
					  'callback' => '',
					  'add_time' => time(),
					  'check_time' => time());
		$financeAPI -> addFrinance($data);
        
        //修改应收款
        $financeAPI -> updateFinanceReceivable(array('amount' => $orderDetail['other']['price_must_pay']), "batch_sn = '{$batchSN}' and type = 4");
        
        //添加订单日志
        $log = array('order_sn' => array_shift(explode('_', $batchSN)),
                     'batch_sn' => $batchSN,
                     'add_time' => $time,
                     'title' => '代收款变更金额[￥' . $arg['change_amount'] . ']',
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }
	/**
     * 已签收
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function signed($batchSN, $arg=null)
    {
        $transportAPI = new Admin_Models_API_Transport();
        $isSplit = $transportAPI -> getSplitOrder($batchSN);
        if ($isSplit) {
            $batchSN = $isSplit['batch_sn'];
        }

        $where = array('batch_sn' => $batchSN);
        $order = array_shift($this -> _db -> getOrderBatch($where));
        if ($order['pay_type'] == 'cod') {//代收款
            $data = array('status_logistic' => 4,
                          'status_pay' => 2,
                          'price_payed' => $order['price_pay'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'] - $order['price_from_return'],
                          'price_before_return' => $order['price_pay'] - $order['price_from_return'],
                          'logistic_signed_time' => time(),
                          'pay_time' => time(),
                          'lock_name' => '');
            //添加支付log记录
            $pay = $order['price_pay'] - $order['price_from_return'] - $order['price_payed'] - $order['account_payed'] - $order['point_payed'] - $order['gift_card_payed'];
            $this -> addOrderPayLog(array('batch_sn' => $batchSN, 'pay_type' =>'cod', 'pay' => $pay));

        } else {
            $data = array('status_logistic' => 4,
                          'price_before_return' => $order['price_payed'] + $order['account_payed'] + $order['point_payed'] + $order['gift_card_payed'],
                          'lock_name' => '');
        }

        if ($isSplit) {
            if (!$isSplit['allSign']) {
                $data['status_logistic'] = 6;
            }
        }
        
        $this -> _db -> updateOrderBatch($where, $data);
        
        $orderDetail = $this -> orderDetail($batchSN);
        
        //虚拟商品发货
        $vitualGoodsAPI = new Admin_Models_API_VitualGoods();
        $vitualGoodsAPI -> sendByBatchSN($batchSN);
        
        //插入联盟数据
        $this -> union($batchSN);
        
        //礼品卡处理
        $this -> splitOrderForgiftCard($orderDetail);
    }

	/**
     * 已拒收
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function reject($batchSN, $arg=null)
    {
        $transportAPI = new Admin_Models_API_Transport();
        $isSplit = $transportAPI -> getSplitOrder($batchSN);
        if ($isSplit) {
            $batchSN = $isSplit['batch_sn'];
        }
        $where = array('batch_sn' => $batchSN);
        $order = array_shift($this -> _db -> getOrderBatch($where));
        if ($order['pay_type'] == 'cod') {//代收款
            $this -> delOrderPayLog($batchSN);
            $data = array('status_logistic' => 5,
                          'status_pay' => 0,
                          'price_payed' => 0,
                          'price_before_return' => 0,
                          'pay_time' => 0,
                          'lock_name' => '');
        }else{
          $data = array('status_logistic' => 5, 'lock_name' => '');
        }
        $this -> _db -> updateOrderBatch($where, $data);
        
        //插入联盟数据
        $this -> union($batchSN);
    }
    /**
     * 客户拒收商品 退货已签收
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function returnSigned($batchSN, $arg=null)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        $newOrder = array_shift($this -> _db -> getOrderBatch(array('parent_batch_sn' => $batchSN, 'status' => 0)));
        $newBatchSN = $newOrder['batch_sn'];
        $oldBatchSN = $batchSN;
        if ($arg['is_check'] == 1) {//同意
            if ($newBatchSN) {
                $this -> _db -> updateOrderBatch(array('batch_sn' => $newBatchSN),
                                                 array('is_freeze' => 0,'status_logistic' => 0, 'lock_name' => ''));
            }
            
            $financeData = $this -> _finance -> getLastFinanceByItemNO(1, $oldBatchSN, true);
            if ($financeData) {
                foreach ($financeData as $finance) {
                    if ($finance['status'] == 0) {
                        $this -> _finance -> updateFinance($finance['finance_id'], array('status' => 1));
                    }
                    else if ($finance['status'] == 2 && in_array($finance['way'], array(3,5))) {
                        $this -> _finance -> updateFinance($finance['finance_id'], array('check_time' => time()));
                        
                        if ($finance['way'] == 3) {
                            //添加应收款记录
                            $financeAPI = new Admin_Models_API_Finance();
                            $receiveData = array('batch_sn' => $newBatchSN,
                                                 'type' => 6,
                                                 'pay_type' => 'exchange',
                                                 'amount' => abs($finance['pay'] + $finance['point'] + $finance['account'] + $finance['gift']),
                                                );
                            $financeAPI -> addFinanceReceivable($receiveData);
                        }
                    }
                }
            }
            
            //如果是货到付款/直供单/赊销的拒收单，更新退货拒收日志
            if ($order['status_logistic'] == 5 && ($order['pay_type'] == 'cod' || $order['type'] == 16 || $order['user_name'] == 'credit_channel')) {
                $this -> _db -> updateOrderReturnDate($oldBatchSN);
            }
            
            //设置商品正在退货数
            $batchGoods = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $oldBatchSN));
            if ($batchGoods) {
                foreach ($batchGoods as $k => $v) {
                    $where = array('order_batch_goods_id' => $v['order_batch_goods_id']);
                    $data = array('returning_number' => 0);
                    $this -> _db -> updateOrderBatchGoods($where, $data);
                }
            }
            
            $log = array('order_sn' => $order['order_sn'],
                         'batch_sn' => $batchSN,
                         'add_time' => time(),
                         'title' => '退货已入库',
                         'admin_name' => $this -> _auth['admin_name']);
            $this -> _db -> addOrderBatchLog($log);
        } else {//拒绝
            /*
             * 新单 垃圾订单 invalid($batchSN);
             * 财务 冻结订单 -> 系统设置无效[财务不可见]
             * 老单 returning_number
             */
             $time = $order['returning_time'];
             if ($newBatchSN = $newOrder['batch_sn']) {
                $tmpOrder = $this -> orderDetail($newBatchSN);
                $priceAdjustForReturnLogistic = 0;//该变量是 退顾客运费，被物流拒绝后，需要把流转金额反给老单，但不能包含该部分金额
                $priceAdjustForReturnLogistic += abs($tmpOrder['adjust']['price_adjust_change_logistic_to']);
                $priceAdjustForReturnLogistic += abs($tmpOrder['adjust']['price_adjust_change_logistic_back']);

                 $time = $newOrder['add_time'];
                //设置新单无效
                $this -> invalid($newBatchSN);
                //删除新单微调金额
                $this -> _db -> delOrderBatchAdjust(array('batch_sn' => $newBatchSN));
             } else {
                //删除老单微调金额
                $this -> _db -> delOrderBatchAdjust(array('batch_sn' => $oldBatchSN, 'add_time' => $time));
             }
            //api 财务接口
            $finance = $this -> _finance -> getLastFinanceByItemNO(1, $oldBatchSN);
            if ($finance['status'] == 0) {
                //0:未通过其他部门审核[对财务不可见] 1:财务未审核 2:财务已审核 3:财务设置无效 4:系统设置无效 5:系统设置无效[对财务不可见]
                $this -> _finance -> updateFinance($finance['finance_id'], array('status' => 5));
            }
            //如果有积分 或者 礼品卡 要返回到老单
            $newBatchGoods = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $newBatchSN));
            if ($newBatchGoods) {
                foreach ($newBatchGoods as $k => $v) {
                    if ($v['card_sn'] && $v['card_type'] == 'gift') {//礼券
                        $priceGift = abs($v['sale_price']);
                    } else if ($v['type'] == 4) {//积分
                        $pricePoint = abs($v['sale_price']);
                    }
                }
            }
            //恢复退货数量 积分 礼品卡
            $batchGoods = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $oldBatchSN));
            if ($batchGoods) {
                foreach ($batchGoods as $k => $v) {
                    $where = array('order_batch_goods_id' => $v['order_batch_goods_id']);
                    if ($v['card_sn'] && $v['card_type'] == 'gift' && $priceGift) {//退还礼券
                        $data = array('sale_price' => $v['sale_price'] + (-$priceGift));
                    } else if ($v['type'] == 4 && $pricePoint) {//退还积分
                        $data = array('sale_price' => $v['sale_price'] + (-$pricePoint));
                    } else {
                        $data = array('returning_number' => 0, 'return_number' => $v['return_number']-$v['returning_number']);
                    }
                    $this -> _db -> updateOrderBatchGoods($where, $data);
                }
            }

            //恢复老单status_return,price_payed
            if ($newBatchSN) { //换货
                $oldOrder = $this -> orderDetail($oldBatchSN);
                $newOrder = $this -> orderDetail($newBatchSN);
                $this -> _db -> updateOrderBatch(array('batch_sn' => $oldBatchSN), array('status_return' => 0,'price_payed' => $oldOrder['order']['price_payed'] + ($newOrder['order']['price_from_return'] - $priceAdjustForReturnLogistic)));
            } else { //退货
                $this -> _db -> updateOrderBatch(array('batch_sn' => $oldBatchSN), array('status_return' => 0));
            }
            //删除退换货理由
            $reason = $this -> _db -> getOrderBatchGoodsReturn(array('batch_sn' =>$oldBatchSN, 'add_time' => $time));
            if ($reason) {
                foreach ($reason as $k => $v) {
                    $reasonArr[] = $v['id'];
                }
            }
            $this -> _db -> delOrderBatchGoodsReturn(array('batch_sn' => $oldBatchSN, 'add_time' => $time));
            $this -> _db -> delOrderBatchGoodsReturnReasonByIDArr($reasonArr);
            //添加日志
            $log = array('order_sn' => array_shift(explode('_', $oldBatchSN)),
                         'batch_sn' => $oldBatchSN,
                         'add_time' => time(),
                         'title' => '取消退换货',
                         'admin_name' => $this -> _auth['admin_name']);
            $this -> _db -> addOrderBatchLog($log);
            //更新订单
            $this -> orderDetail($oldBatchSN);
        }
    }
    /**
     * 派单
     *
     * @param    string    $batchSN
     * @param    array    $arg
     * @return   void
     */
    public function assign($batchSN, $arg=null)
    {
        $data = array('logistic_name' => $arg['logistic_name'],
                      'logistic_code' => $arg['logistic_code'],
                      'logistic_price' => $arg['price'],
                      'logistic_price_cod' => $arg['cod_price'],
                      'logistic_fee_service' => $arg['fee_service'],
                      'logistic_no' => $arg['logistic_no']);

        $transportAPI = new Admin_Models_API_Transport();
        $isSplit = $transportAPI -> getSplitOrder($batchSN, null, false);

        if ($isSplit) { //拆单
            $batchSN = $isSplit['batch_sn'];
            if ($isSplit['hasAssign']) {
                $order = array_shift($this -> _db -> getOrderBatchInfo(array('batch_sn' => $batchSN)));

                $logisticNameArray = explode(',', $order['logistic_name']);
                if (!in_array($arg['logistic_name'], $logisticNameArray)) {
                    $data['logistic_name'] = $order['logistic_name'].','.$arg['logistic_name'];
                }
                $logisticCodeArray = explode(',', $order['logistic_code']);
                if (!in_array($arg['logistic_code'], $logisticCodeArray)) {
                    $data['logistic_code'] = $order['logistic_code'].','.$arg['logistic_code'];
                }

                $data['logistic_price'] = $order['logistic_price'] + $arg['price'];
                $data['logistic_price_cod'] = $order['logistic_price_cod'] + $arg['cod_price'];
                $data['logistic_fee_service'] = $order['logistic_fee_service'] + $arg['fee_service'];
            }

            unset($data['logistic_no']);
        }

        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), $data);
    }

	/**
     * 财务处理接口
     *
     * @param    array    $data
     * @return   void
     */
    public function toFinance($orderBatchSN, $callback, $arg=null)
    {
        $this -> $callback($orderBatchSN, $arg);
    }
    
    public function updateOrderBatchPayed($batchSN, $arg)
    {
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order)    return false;
        
        if ($arg['pay'] < 0) {
            $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN),
                                             array('price_payed' => $order['price_payed'] + $arg['pay']));
            $title .= "金额修改{$arg['pay']}&nbsp;";
        }
        if ($arg['gift'] < 0) {
            $this -> unUseCardGift($batchSN, abs($arg['gift']));
            $title .= "礼品卡修改{$arg['gift']}&nbsp;";
        }
        if ($arg['point'] < 0) {
            $this -> unPointPrice($batchSN, abs($arg['point']));
            $title .= "积分修改{$arg['point']}&nbsp;";
        }
        if ($arg['account'] < 0) {
            $this -> unAccountPrice($batchSN, abs($arg['account']));
            $title .= "账户余额修改{$arg['account']}&nbsp;";
        }
        
        $log = array('order_sn' => $order['order_sn'],
                     'batch_sn' => $order['batch_sn'],
                     'add_time' => time(),
                     'title' => '财务退款 '.$title,
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
        
        $this -> orderDetail($batchSN);
    }

	/**
     * 联盟接口
     *
     * @param    string    $orderBatchSN
     * @param    array    $arg
     * @return   void
     */
    public function union($batchSN)
    {
        //插入联盟数据
        $orderSN = array_shift(explode('_', $batchSN));

        $priceData = $this -> orderPriceStatus($orderSN);
        $data['cpa_goods_type'] = 0;
        $order = array_shift($this -> _db -> getOrderBatch(array('batch_sn' => $batchSN)));//取第一条订单记录
        $union = $this -> _union -> getUnionById($order['parent_id']);
		$data['calculate_type'] = $union['calculate_type'];//取分成类型（固定 or 商品）

        $data['parent_id'] = $order['parent_id'];
        $data['parent_param'] = $order['parent_param'];
        $data['proportion'] = $order['proportion'];
        $data['order_id'] = $order['order_id'];
        $data['order_sn'] = $order['order_sn'];
        $data['user_id'] = $order['user_id'];
        $data['user_name'] = $order['user_name'];
        $data['status'] = intval($order['status']);
        $data['status_logistic'] = intval($order['status_logistic']);
        $data['status_return'] = intval($order['status_return']);
        $data['price_goods'] = !$priceData['price_goods']?0:$priceData['price_goods'];
        $data['price_order'] = !$priceData['price_order']?0:$priceData['price_order'];
        $data['affiliate_amount'] = floatval($priceData['affiliate_amount']);
        if ($data['affiliate_amount'] < 0) {
            $data['affiliate_amount'] = 0;
        }

        //取得订单商品
        if($union['calculate_type']==1){
	        $batchGoods = null;
        }else{
        	$goods = $this -> _db -> getOrderBatchGoods(array('order_sn' => $batchSN));
	        foreach ($goods as $v){
	        	if($v['type'] != 5){//普通订单
	        		$batchGoods['data'][] = $v;
	        	}
	        	if( ($v['parent_id'] == null) && ($v['type'] ==5) ){//组合订单
	        		$batchGoods['group_goods_summary'][] = $v;
	        	}
	        }

        }
        $this -> _union -> setAffiliate($data, $batchGoods);
    }

	/**
     * 退礼券 coupon(整退)
     *
     * @param    string    $batchSN
     * @return   void
     */
    public function unUseCardCoupon($batchSN)
    {
        $product = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN,
                                                            'card_sn_is_not_null' => true,
                                                            'number>' => 0));
        if ($product) {
            $cardObj = new Shop_Models_API_Card();
            foreach ($product as $k => $v) {
                if ($v['card_type'] == 'coupon' || $v['card_type'] == 'goods') {
                    $cardObj -> unUseCard(array('card_sn' => $v['card_sn'], 'card_price' => abs($v['sale_price']), 'add_time' => $v['add_time']));
                    $where = array('order_batch_goods_id' => $v['order_batch_goods_id']);
                    $data = array('number' => 0);
                    $this -> _db -> updateOrderBatchGoods($where, $data);
                }
            }
        }
        
        $this -> unUseCardGift($batchSN);
    }
    
	/**
     * 退礼品卡
     *
     * @param    string     $batchSN
     * @param    float      $money
     * @return   boolean
     */
    public function unUseCardGift($batchSN, $money = 0)
    {
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order || $order['gift_card_payed'] <= 0)  return false;
        
        $giftCardAPI = new Admin_Models_API_GiftCard();
        $giftCardLog = $giftCardAPI -> getUseLog(array('batch_sn' => $batchSN));
        $giftCardLog = $giftCardAPI -> setCanReturnCard($giftCardLog['content']);
        if ($giftCardLog) {
            $giftCardAPI = new Shop_Models_API_Card();
            if ($money > 0) {
                $total = $money;
            }
            $result = false;
            $sum = 0;
            foreach ($giftCardLog as $giftCard) {
                if ($giftCard['can_return']) {
                    if ($money > 0) {
                        if ($total == 0)    break;
                        if ($total >= $giftCard['price']) {
                            $price = $giftCard['price'];
                            $total -= $giftCard['price'];
                        }
                        else {
                            $price = $total;;
                            $total = 0;
                        }
                    }
                    else {
                        $price = $giftCard['price'];
                    }
                    $card = array('card_sn' => $giftCard['card_sn'],
                                  'card_pwd' => $giftCard['card_pwd'],
                                  'card_type' => $giftCard['card_type'],
                                  'card_price' => $price,
                                  'add_time' => time(),
                                  'admin_id' => $this -> _auth['admin_id'],
                                  'admin_name' => $this -> _auth['admin_name'],
                                  'batch_sn' => $batchSN,
                                 );
                    $giftCardAPI -> unUseCard($card);
                    $result[] = $card;
                    $sum += $price;
                }
            }
            
            $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('gift_card_payed' => $order['gift_card_payed'] - $sum));
            
            return $result;
        }
        else {
            return false;
        }
    }
	/**
     * 退积分
     *
     * @param    string    $batchSN
     * @param    float     $pricePoint
     * @return   int
     */
    public function unPointPrice($batchSN, $pricePoint = 0)
    {
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order || $order['point_payed'] <= 0)  return false;
        if ($pricePoint < 0)    return false;
        
        if ($pricePoint) {
            if ($pricePoint > $order['point_payed'])    return false;
        }
        else {
            $pricePoint = $order['point_payed'];
        }
        
        $pointAPI = new Admin_Models_DB_MemberPoint();
        $pointList = $pointAPI -> getPoint(" and A.batch_sn = '{$batchSN}'");
        if (!$pointList)    return false;
        
        $totalPoint = 0;
        foreach ($pointList as $point) {
            $totalPoint += $point['point'];
        }

        if ($totalPoint > 0)    return false;
        $totalPoint = abs($totalPoint);
        $point = floor($pricePoint * ($totalPoint / $order['point_payed']));
        
        $memberAPI = new Admin_Models_API_Member();
        $member = $memberAPI -> getMemberByUserName($order['user_name']);
        if (!$member)   return false;
        
        $tmp = array('member_id' => $member['member_id'],
                     'user_name' => $order['user_name'],
                     'accountType' => 1,
                     'accountValue' => $point,
                     'accountTotalValue' => $member['point'],
                     'batch_sn' => $batchSN,
                     'note' => '退积分'
                    );
        $memberAPI -> editAccount($member['member_id'], 'point', $tmp);
        
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('point_payed' => $order['point_payed'] - $pricePoint));
        
        return $point;
    }
    
    /**
     * 退账户余额
     *
     * @param    string    $batchSN
     * @param    float     $accountPoint
     * @return   boolean
     */
    public function unAccountPrice($batchSN, $priceAccount = 0)
    {
        $order = array_shift($this -> getOrderBatch(array('batch_sn' => $batchSN)));
        if (!$order || $order['account_payed'] <= 0)  return false;
        if ($priceAccount < 0)  return false;
        
        if ($priceAccount) {
            if ($priceAccount > $order['account_payed'])    return false;
        }
        else {
            $priceAccount = $order['account_payed'];
        }
        
        $moneyAPI = new Admin_Models_DB_MemberMoney();
        $moneyList = $moneyAPI -> getMoney(" and A.batch_sn = '{$batchSN}'");
        if (!$moneyList)    return false;
        
        $memberAPI = new Admin_Models_API_Member();
        $member = $memberAPI -> getMemberByUserName($order['user_name']);
        if (!$member)   return false;
        
        $tmp = array('member_id' => $member['member_id'],
                     'user_name' => $order['user_name'],
                     'accountType' => 1,
                     'accountValue' => $priceAccount,
                     'accountTotalValue' => $member['money'],
                     'batch_sn' => $batchSN,
                     'note' => '退账户余额'
                    );
        $memberAPI -> editAccount($member['member_id'], 'money', $tmp);
        
        $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('account_payed' => $order['account_payed'] - $priceAccount));
        
        return $priceAccount;
    }


	/**
	*  取消订单发送邮件
	*
	*  @param  array  $email
	*  @param  int    $batchSN
	*  @return string
	*/
	public function orderCancelEmail($email,$batchSN)
	{
		$templateValue['user_name']=$email;
		$templateValue['batchSN']=$batchSN;
		$template = new Admin_Models_API_EmailTemplate();
		$template = $template -> getEmailTemplateByName('order_cancel',$templateValue);
		$mail = new Custom_Model_Mail();
		if ($mail -> send($email, $template['title'], $template['value'])) {
			return 'error';
		} else {
			return 'setOrderCancelSucess';
		}
	}

	/**
	*  发货邮件通知
	*
	*  @param  array  $product
	*  @param  array  $order
	*  @param  string
	*/
	public function shippedOrderEmail($product, $order)
	{
		$templateValue['user_name'] = $order['user_name'];
		$templateValue['order_sn'] = $order['order_sn'];
		$templateValue['addr_consignee'] = $order['addr_consignee'];
		$templateValue['order_add_time'] = date('Y-m-d  h:i:s',$order['add_time']);
		$templateValue['price_order'] = $order['price_order'];
		$templateValue['price_goods'] = $order['price_goods'];
		$templateValue['price_pay'] = $order['price_pay'];
		$templateValue['pay_name'] = $order['pay_name'];
        $templateValue['shop_name'] = Zend_Registry::get('config') -> name;
        $templateValue['send_date'] = date('Y-m-d',time());

        $templateValue['addr_province']=$order['addr_province'];
        $templateValue['addr_city']=$order['addr_city'];
        $templateValue['addr_area']=$order['addr_area'];
        $templateValue['addr_address']=$order['addr_address'];
        $templateValue['addr_zip']=$order['addr_zip'];
        $templateValue['addr_mobile']=$order['addr_mobile'];
        $templateValue['addr_tel']=$order['addr_tel'];

        $templateValue['logistic_name']=$order['logistic_name'];
        if(!$templateValue['logistic_name']){$templateValue['logistic_name']='普通快递';}
        $templateValue['logistic_no']=$order['logistic_no'];
        $templateValue['logistic_time']=date('Y-m-d H:i:s',$order['logistic_time']);
        $templateValue['logistic_fee_service']=$order['price_logistic'];

        $tmp='';
        
        $siteurl = 'http://www.1jiankang.com';

        foreach ($product as $v)
        {
        	if($v['type']==0){
	        	$tmp.='<tr>
	    <td><a href="'.$siteurl.'/goods-'.$v['goods_id'].'.html">'.$v['goods_name'].'</a></td>
	    <td>'.$v['product_sn'].'</td>
	    <td>￥'.$v['price'].'</td>
	    <td>'.$v['number'].'</td>
	    <td>￥'.$v['price']*$v['number'].'</td>
	  </tr>';
        	}
        }
        unset($product);
        $templateValue['product']=$tmp;

	    $template = new Admin_Models_API_EmailTemplate();
	    $template = $template -> getEmailTemplateByName('deliver_notice', $templateValue);
	    $mail = new Custom_Model_Mail();
	    if ($mail -> send($order['user_name'], $template['title'], $template['value'])) {
		    return 'sendError';
	    } else {
	    	return 'sendPasswordSucess';
	    }
	}

    /**
     * 处理满意无需退货（超过40天）
     *
     * 状态，积分
     */
    public function dealCompleteOrder() {
    	$list = $this -> _db -> getCompleteOrder();
    	if(is_array($list) && count($list)){
    		$shopConfig = Zend_Registry::get('shopConfig');
            //会员api
            $member_api = new Admin_Models_API_Member();
    		foreach ($list as $order){
                //更新shop_order_batch
                $where = array('batch_sn' => $order['batch_sn']);
                $set = array('is_fav' => 1,'logistic_list' => '');
                $result = $this -> _db -> updateOrderBatch($where, $set);
                if (!isset($shopConfig['fav_point'])) {
                    $shopConfig['fav_point'] = 1;
                }
                $priceFromReturn = 0;
                if ($order['is_fav'] != -1) {
                    $priceFromReturn = $order['price_from_return'];
                }
                $point = intval(($order['price_payed'] + $priceFromReturn - $order['price_logistic']) * $shopConfig['fav_point']);
                if ($point < 0) {
                    $point = 0;
                }
                $member = $member_api -> getMemberByUserId($order['user_id']);
                $tmp = array('member_id' => $order['member_id'],
                         'user_name' => $order['user_name'],
                         'order_id' => $order['order_id'],
                         'accountType' => 1,
                         'accountValue' => $point,
                         'accountTotalValue' => $member['point'],
                         'note' => '满意不退货赠送积分'.$order['batch_sn']);
                 $member_api -> editAccount($order['member_id'], 'point', $tmp);
    		}
            return 'ok';
    	}else{
    		return 'ok';
    	}
    }

    /**
     * 取得指定条件的订单列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function fetchOrderBatch($where = null, $fields = '*',  $page = null, $pageSize = null)
    {
       if ($where['fromdate'] && $where['todate']) {
            $fromDate = strtotime($where['fromdate']);
            $toDate = strtotime($where['todate']) + 86400;
            if ($fromDate <= $toDate) {
                $condition[] = "(b.add_time between {$fromDate} and {$toDate})";
            }
        }
        if ($where['batch_sn']) {
            $condition[] = "b.batch_sn like '{$where['batch_sn']}%'";
        }
        if ($where['pay_type']) {
            $condition[] = "b.pay_type like '{$where['pay_type']}%'";
        }
        if ($where['sub_pay_type']) {
            if ($where['sub_pay_type'] == 'call') {
                $condition[] = "a.shop_id = 2 and a.user_name like '%_call'";
            }
            else {
                $condition[] = "a.shop_id = 1";
            }
        }
        if (!is_null($where['status']) && $where['status'] !== '') {
            $condition[] = "status={$where['status']}";
        }
        if (!is_null($where['status_logistic']) && $where['status_logistic'] !== '') {
            $condition[] = "status_logistic={$where['status_logistic']}";
        }
        if (!is_null($where['status_logistic>'])) {
            $condition[] = "status_logistic>{$where['status_logistic>']}";
        }

        if (!is_null($where['status_return']) && $where['status_return'] !== '') {
            $condition[] = "status_return={$where['status_return']}";
        }
        if ($where['logistic_no']) {
            $condition[] = "logistic_no='{$where['logistic_no']}'";
        }
        if ($where['batch_sns']) {
            $condition[] = "b.batch_sn in (".implode(',', $where['batch_sns']).")";
        }
        if ($where['not_pay_type']) {
            $condition[] = "b.pay_type not in ({$where['not_pay_type']})";
        }
        if ($where['clear_pay'] !== null && $where['clear_pay'] !== '') {
            $condition[] = "clear_pay = '{$where['clear_pay']}'";
        }
        if (is_array($condition) && count($condition)) {
            $condition = 'AND ' . implode(' AND ', $condition);
        }
        $data =  $this -> _db ->fetchOrderBatch($condition,$fields, $page,$pageSize);
          if(is_array($data['list']) && count($data['list'])){
                    foreach ($data['list'] as $k => $v) {
                        $data['list'][$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                        $data['list'][$k]['status'] = $this -> status('status', $v['status']);
                        $data['list'][$k]['status_return'] = $this -> status('status_return', $v['status_return']);
                        $data['list'][$k]['status_logistic'] = $this -> status('status_logistic', $v['status_logistic']);
                        $data['list'][$k]['status_pay'] = $this -> status('status_pay', $v['status_pay']);
                        $data['list'][$k]['blance'] = round($v['price_pay'] - ($v['price_payed']+$v['price_from_return']), 2);
                        $data['list'][$k]['oinfo'] = Zend_Json::encode($data['list'][$k]);
                    }

                }
        return $data;
    }

    /**
     * 取得指定条件的外部订单列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function fetchExternalOrderBatch($where = null, $page = null, $pageSize = null)
    {
        $db = Zend_Registry::get('db');

        $pageSize = ((int)$pageSize > 0) ? (int)$pageSize : 10;
		if ($page != null) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
        if (!$where['type']) {
            $shopData = $db -> fetchAll("select shop_id,shop_type,shop_name from shop_shop where shop_type in ('taobao', 'jingdong', 'yihaodian', 'dangdang','qq', 'tuan')");
        }
        else {
            $shopData = $db -> fetchAll("select shop_id,shop_type,shop_name from shop_shop where shop_type = '{$where['type']}'");
        }

        if ( !$shopData ) {
            return array('list' => null, 'total' => 0);
        }
        foreach ( $shopData as $shop ) {
            $shopIDArray[] = $shop['shop_id'];
            $shopInfo[$shop['shop_id']]['type'] = $shop['shop_type'];
            $shopInfo[$shop['shop_id']]['shop_name'] = $shop['shop_name'];
        }

		$whereSql = "t1.status in (0,3) and t1.type in (13,14) and t1.status_logistic in (2,3,4,5) and t1.fake_type <> 1 and t2.external_order_sn";
		if ($shopIDArray) {
		    $whereSql .= " and t2.shop_id in (".implode(',', $shopIDArray).")";
		}
		if ( $where['is_settle'] != null && $where['is_settle'] != '' ) {
            $whereSql .= " and t1.clear_pay = ({$where['is_settle']})";
        }
		if ( $where['fromdate'] ) {
		    $time = strtotime($where['fromdate']);
            $whereSql .= " and (t2.add_time >= {$time} or t3.order_time >= {$time})";
        }
        if ( $where['todate'] ) {
            $time = strtotime($where['todate'].' 23:59:59');
            $whereSql .= " and (t2.add_time <= {$time} or t3.order_time <= {$time})";
        }
        if ( $where['order_sn'] ) {
            $whereSql .= " and t2.external_order_sn like '%{$where['order_sn']}%'";
        }
        if ( $where['batch_sn'] ) {
            $whereSql .= " and t1.batch_sn = '{$where['batch_sn']}'";
        }
        if ( $where['order_sns'] ) {
            $whereSql .= " and t2.external_order_sn in (".implode(',', $where['order_sns']).")";
        }
        if ( $where['payment_nos'] ) {
            $whereSql .= " and t3.payment_no in (".implode(',', $where['payment_nos']).")";
        }
        if ( $where['ids'] ) {
            $whereSql .= " and t1.order_id in ({$where['ids']})";
        }
        if ( $where['shop_id'] ) {
            $whereSql .= " and t2.shop_id = '{$where['shop_id']}'";
        }
        if ( $where['payment_no'] ) {
            $whereSql .= " and t3.payment_no = '{$where['payment_no']}'";
        }
        if ( $where['status'] !== '' && $where['status'] !== null) {
            $whereSql .= " and t1.status = '{$where['status']}'";
        }
        if ( $where['is_fake'] ) {
            $whereSql .= " and t3.is_fake = '{$where['is_fake']}'";
        }
        
        if ($where['clear_fromdate'] || $where['clear_todate']) {
            $subCondition = 1;
            $where['clear_fromdate'] && $subCondition .= " and clear_time >= ".strtotime($where['clear_fromdate']);
            $where['clear_todate'] && $subCondition .= " and clear_time <= ".strtotime($where['clear_todate'].' 23:59:59');
            $financeAPI = new Admin_Models_DB_Finance();
            $clearData = $financeAPI -> getExternalClear($subCondition);
            if ($clearData['data']) {
                $tids = '';
                foreach ($clearData['data'] as $data) {
                    $tids .= $data['tids'].',';
                }
                $tids = substr($tids, 0, -1);
                if ($tids) {
                    $whereSql .= " and t1.order_id in ({$tids})";
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }

        $list = $db -> fetchAll("select t1.order_id,sum(t1.price_payed+t1.price_from_return) as amount,t1.order_sn as batch_sn,t1.clear_pay as is_settle,t2.add_time,t2.shop_id,t2.external_order_sn as order_sn,t3.payment_no,t2.commission,'渠道支付' as pay_name,t3.is_fake,t3.order_time,t1.logistic_time from shop_order_batch as t1 inner join shop_order as t2 on t1.order_id = t2.order_id left join shop_order_shop as t3 on t2.external_order_sn = t3.external_order_sn where {$whereSql} group by t1.order_id {$limit}");
        if ( $list ) {
            foreach ( $list as $key => $data) {
                if ($data['batch_sn'] == '') {
                    unset($list[$key]);
                    continue;
                }
                $list[$key]['order_time'] = date('Y-m-d H:i:s', $data['order_time'] ? $data['order_time'] : $data['add_time']);
                $list[$key]['logistic_time'] && $list[$key]['logistic_time'] = date('Y-m-d H:i:s', $list[$key]['logistic_time']);
                $list[$key]['type'] = $shopInfo[$data['shop_id']]['type'];
                $list[$key]['shop_name'] = $shopInfo[$data['shop_id']]['shop_name'];
                $list[$key]['oinfo'] = Zend_Json::encode($list[$key]);
            }
        }
        $total = $db -> fetchRow("select count(*) as num,sum(price_payed+price_from_return) as amount,sum(commission) as commission from (select sum(t1.price_payed) as price_payed,sum(t1.price_from_return) as price_from_return,sum(t2.commission) as commission from shop_order_batch as t1 inner join shop_order as t2 on t1.order_id = t2.order_id left join shop_order_shop as t3 on t2.external_order_sn = t3.external_order_sn where {$whereSql} group by t1.order_id) as a");
        return array( 'list' => $list,
                      'total' => $total,
                    );
    }

    /**
     * 重新更新订单金额和订单商品金额，只对渠道订单的财务退款
     *
     * @param   array   $order
     * @param   float   $pay
     * @param   float   $point
     * @param   float   $account
     * @param   float   $gift
     */
    public function updateOrderAmountByFinanceReturn($order, $pay, $point, $account, $gift)
    {
        /*
        if ($order['price_order'] == $returnAmount && in_array($order['status_logistic'], array(0,1,2))) {
            $this -> _db -> updateOrderBatch(array('batch_sn' => $order['batch_sn']), array('status' => 1));

            $log = array('order_sn' => $order['batch_sn'],
                         'batch_sn' => $order['batch_sn'],
                         'add_time' => time(),
                         'title' => '财务全额退款 订单取消',
                         'data' => Zend_Json::encode($data),
                         'admin_name' => $this -> _auth['admin_name']);
            $this -> _db -> addOrderBatchLog($log);

            return true;
        }*/
        
        if ($pay > 0) {
            $type = 'pay';
            $returnAmount = $pay;
        }
        else if ($point > 0) {
            $type = 'point';
            $returnAmount = $point;
        }
        else if ($account > 0) {
            $type = 'account';
            $returnAmount = $account;
        }
        else if ($gift > 0) {
            $type = 'gift';
            $returnAmount = $gift;
        }
        
        $goodsData = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $order['batch_sn']));
        $goodsAmount = 0;
        foreach ($goodsData as $key => $goods) {
            $goodsData[$key]['amount'] = $goods['sale_price'] * ($goods['number'] - $goods['return_number']);
            $goodsAmount += $goodsData[$key]['amount'];
        }
        $totalAmount = 0;
        $realAmount = 0;
        for ($i = 0; $i < count($goodsData); $i++) {
            if ($i == count($goodsData) - 1) {
                $tempAmount = $returnAmount - $totalAmount;
            }
            else {
                $tempAmount = round($goodsData[$i]['amount'] / $goodsAmount * $returnAmount, 2);
                $totalAmount += $tempAmount;
            }
            $salePrice = $goodsData[$i]['sale_price'] - round($tempAmount / ($goodsData[$i]['number'] - $goodsData[$i]['return_number']), 2);

            $set = array('sale_price' => $salePrice);
            $this -> _db -> updateOrderBatchGoods(array('order_batch_goods_id' => $goodsData[$i]['order_batch_goods_id']), $set);

            $realAmount += $salePrice * ($goodsData[$i]['number'] - $goodsData[$i]['return_number']);
        }

        $priceOrder = $order['price_order'] - $returnAmount;
        $priceGoods = $order['price_goods'] - $returnAmount;
        $set = array('price_order' => $priceOrder,
                     'price_goods' => $priceGoods,
                     'price_pay' => $priceOrder,
                    );
        if ($type == 'pay') {
            if ($order['price_payed'] > 0) {
                $set['price_payed'] = $order['price_payed'] - $returnAmount;
            }
        }
        else if ($type == 'point') {
            if ($order['point_payed'] > 0) {
                $this -> unPointPrice($order['batch_sn'], $returnAmount);
            }
        }
        else if ($type == 'account') {
            if ($order['account_payed'] > 0) {
                $this -> unAccountPrice($order['batch_sn'], $returnAmount);
            }
        }
        else if ($type == 'gift') {
            if ($order['gift_card_payed'] > 0) {
                $this -> unUseCardGift($order['batch_sn'], $returnAmount);
            }
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $order['batch_sn']), $set);
        
        if (abs($realAmount - $priceGoods) > 0 ) {
            $shopAPI = new Admin_Models_API_Shop();
            $shopAPI -> updateOrderBatch2($order['batch_sn'], $priceGoods - $realAmount, '优惠补偿退款');
        }
        
        $data['price_order'] = $order['price_order'];
        $log = array('order_sn' => $order['batch_sn'],
                     'batch_sn' => $order['batch_sn'],
                     'add_time' => time(),
                     'title' => '财务退款 金额修改-'.$returnAmount,
                     'data' => Zend_Json::encode($data),
                     'admin_name' => $this -> _auth['admin_name']);
        $this -> _db -> addOrderBatchLog($log);
    }

    /**
     * 获得订单是否包含提货券
     *
     * @param    string/array $data
     *
     * @return   void
     */
    public function getOrderGoodsCard($data)
    {
        if (is_array($data)) {
            $goodsData = $data;
        }
        else {
            $goodsData = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $data));
        }
        if ($goodsData) {
            foreach ($goodsData as $goods) {
                if ($goods['card_type'] == 'goods')  return $goods['card_sn'];
            }
        }
        return false;
    }

	/**
	 * 修改赠送人
	 *
	 */
    public function giftbywho($order_sn, $val) {
    	$order_sn = trim($order_sn); if($order_sn == ''){return '参数错误';}
    	$val = trim($val); if($val == ''){return '赠送人必须';}
    	$this -> _db -> updateOrder(array('order_sn'=>$order_sn), array('giftbywho' => $val));
    	return 'ok';
    }

    /**
     * 申请渠道销售出库单出库
     *
     * @param   string      $batchSN
     * @return  void
     */
    public function virtualOut($batchSN)
    {
        $time = time();
        $fields = "a.batch_sn,a.type,a.note_print,a.note_logistic,a.logistic_name,a.logistic_price,a.pay_type,a.price_order,b.shop_id,b.user_name,b.distribution_type";
	    $details = array();
		$r = array_shift($this -> _db -> getOrderBatchInfo(array('batch_sn' => $batchSN), $fields));
		$bill_no = $batchSN;
		$remark = $r['note_logistic'] ? $r['note_logistic'].',' : '';
		$where = array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0, '(number-return_number)>' => 0);
		$product = $this -> _db -> getOrderBatchGoods($where);
		if ($product) {
			foreach($product as $k => $v) {
				if ($details[$v['product_id']]) {
					$details[$v['product_id']]['number'] += $v['number'] - $v['return_number'];
					$details[$v['product_id']]['shop_price'] = $v['price'];
				} else {
					$details[$v['product_id']] = array ('product_id' => $v['product_id'],
														'number' => $v['number'] - $v['return_number'],
														'shop_price' => $v['price'],
														'status_id' => 2);
				}
			}
		}
		$logicArea = Custom_Model_Stock_Base::getDistributionArea($r['user_name']);
		if (!$logicArea)    die('error');
		if ($r['distribution_type']) {
		    $bill_type = 19;
		    $stockConfig = new Custom_Model_Stock_Config();
		    $remark = $stockConfig -> _logicArea[Custom_Model_Stock_Base::getDistributionArea($r['user_name'])];
		}
		else {
		    $bill_type = 15;
		}
        $rowOutStock = array ('lid' => $logicArea,
                              'bill_no' => $bill_no,
                              'bill_type' => $bill_type,
                              'bill_status' => 5,
                              'remark' => $remark,
                              'add_time' => $time,
                              'finish_time' => $time,
                              'admin_name' => $this -> _auth['admin_name']
                             );
        
        $outStock = new Admin_Models_API_OutStock();
        $outstockID = $outStock -> insertApi($rowOutStock, $details, $logicArea, true);
        if ($outstockID) {
           //生成出库单关系记录
           $transportAPI  = new Admin_Models_DB_Transport();
           $row = array('bill_no' => $bill_no,
                         'transport_id' => 0,
                         'outstock_id' => $outstockID,
                        );
           $transportAPI -> insertRelationOrder($row);

			//插入代销结款数据
			$consign_result_params = array();
			if (count($product) > 0) {
				$consign_result_db = new Admin_Models_API_ConsignResult();
				foreach ($product as $info) {
					$consign_result_params = array(
						'warehouse_id'      => $logicArea,
						'product_id'        => $info['product_id'],
						'number'            => $info['number'],
						'created_month'     => date('Ym'),
						'warehouse_product' => $logicArea.'_'.$info['product_id'],
					);
					$result = $consign_result_db->add($consign_result_params);
				}
			}
			
			//插入分销销售结款数据到销售结款单
			$sale_result_params = array();
			if (count($product) > 0) {
				$sale_result_db = new Admin_Models_API_SaleResult();
				foreach ($product as $info) {
					$sale_result_params = array(
						'product_id'        => $info['product_id'],
						'number'            => $info['number'],
						'created_month'     => date('Ym'),
					);
					$result = $sale_result_db->add($sale_result_params);
				}
			}

			if ($r['distribution_type']) {  //生成分销刷单入库单
			    $details = array();
    		    $outstockDetail = $outStock -> getDetail("a.outstock_id = '{$outstockID}'");
    		    $row = array ('lid' => 1,
    			              'item_no' => $bill_no,
    			              'bill_no' => Custom_Model_CreateSn::createSn(),
    	                      'bill_type' => 19,
    	                      'bill_status' => 3,
    				          'remark' => $remark,
    	                      'add_time' => time(),
    	                      'admin_name' => $this -> _auth['admin_name'],
    	                     );
    	        foreach ($outstockDetail as $v) {
    				$details[] = array('product_id' => $v['product_id'],
    				                   'batch_id' => $v['batch_id'],
    		                           'plan_number' => $v['number'],
    		                           'shop_price' => $v['cost'],
    		                           'status_id' => 6,
    		                          );
    	        }
    	        $inStock = new Admin_Models_API_InStock();
    	    	$inStock -> insertApi($row, $details, 1, true);
			}
			
			//设置财务金额
    	    $this -> _db -> updateOrderBatch(array('batch_sn' => $batchSN), array('balance_amount' => $r['price_order']));

            return true;
        }
    }

    /**
     * 订单出库前再次检验库存
     *
     * @param   string      $batchSN
     * @return  boolean
     */
    public function checkOut($batchSN, $only_check = 0)
    {
        $where = array('batch_sn' => $batchSN, 'product_id>' => 0, 'number>' => 0, '(number-return_number)>' => 0);
		$product = $this -> _db -> getOrderBatchGoods($where);
		if (!$product)  return false;
		$stockAPI = new Admin_Models_API_Stock();

		if (!$this -> _replenishmentAPI) {
            $this -> _replenishmentAPI = new Admin_Models_API_Replenishment();
        }

		$is_error = 0;
		foreach($product as $k => $v) {
			if ($only_check == '1') {
				if (!$stockAPI -> checkPrepareProductStock($v['product_id'], $v['number'] - $v['return_number'])) {
					return false;
				}
			} else {

                // @zhouyong 先作废补货单
                    $replenish_db   = new Admin_Models_API_Replenishment();
                    $replenish_info = $replenish_db->cancelOrderReplenish($v['order_batch_id'], 2);
				if (!$stockAPI -> checkPrepareProductStock($v['product_id'], $v['number'] - $v['return_number'])) {
					// @zhoyong产品缺货时补货
					$this -> _replenishmentAPI -> applyReplenish($v['order_batch_id'], $v['product_id'], $v['number'], 2);
					$is_error = 1;
				} else {
					$this -> _replenishmentAPI -> cancelProductReplenish($v['order_batch_id'], $v['product_id'], 2);
				}
			}
		}

		if ($is_error) {
			return false;
		}
		return true;
    }

    /**
     * 释放销售产品占用库存
     *
     * @param    string     $batchSN
     * @return   boolean
     */
    function releaseSaleOutStock($batchSN)
    {
        $datas = $this -> _db -> getOrderBatchGoods(array('batch_sn' => $batchSN, 'number>' => 0));
        if (!$datas) return false;
        $stockAPI = new Admin_Models_API_Stock();
        foreach ($datas as $data) {
            if (!$data['product_id'])  continue;

            $stockAPI -> releaseSaleProductStock($data['product_id'], $data['number']);
        }
        return true;
    }

	/**
	*
	*添加退货理由
	*/
	public function addreturnres($res){
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$data = Custom_Model_Filter::filterArray(array('res'=>$res), $filterChain);
		$reasons=$this->_db->addreason($data);
			if($reasons==='isExists'){
				return $reasons;
			}else{
				return $this->_db->getReason(array('reason_id'=>$reasons));
			}
	}
    /**
     * 退换货理由列表
     *
     * @param   array   $where
     * @return  array
     */
    public function getReason($where = null)
    {
        return $this -> _db -> getReason($where);
    }
	 /**
     * 取得退货订单列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
	 public function getreturnorder($where,$page=1){
		$data=$this->_db->getreturnorder($where,$page);
		$fileds='t1.order_batch_goods_id as batch_id,t1.product_sn,t1.batch_sn,t1.goods_name,t1.goods_style';
		if(is_array($data)&&count($data)>0){
			foreach($data['details'] as $k=>$v){
				$inBatchSN[]=$v['batch_sn'];
				$data['details'][$k]['return_time']=date("Y-m-d H:i",$v['return_time']);
			}
			$data['product'] = $this -> _db -> getOrderBatchGoodsInBatchSN($inBatchSN,$fileds);
		}
			return $data;
	 }

	 /**
     * 判断是否已配货
     *
     * @param    string      $bill_no
     * @return   void
     */
	public function checkPrepared($bill_no)
	{
	    $transportAPI = new Admin_Models_API_Transport();
	    $isSplit = $transportAPI -> isSplitOrder($bill_no);
	    if ($isSplit) {
	        $where = "bill_no like '{$bill_no}-%'";
	    }
	    else {
	        $isMerge = $transportAPI -> isMergeOrder($bill_no);
	        if ($isMerge) {
	            $where = "bill_no like '%{$bill_no}%'";
	        }
	        else {
	            $where = "bill_no = '{$bill_no}'";
	        }
	    }

	    return $transportAPI -> get($where);
	}

	public function getOrderBatchInfoAndGoodsInfosByBatchId($order_batch_id)
	{
		if (false === ($order_info = $this->_db->getOrderBatchInfoByOrderBatchId($order_batch_id))) {
			$this->_error = $this->_db->get_error();
			return false;
		}


		$order_info['goods'] = $this->_db->getOrderBatchGoodsByOrderBatchId($order_batch_id);

		return $order_info;

	}
	
	/**
     * 更改订单已支付金额
     *
     * @param   string      $batchSN
     * @param   float       $amount
     * @param   string      $type
     * @return  void
     */
    public function updateOrderPayed($batchSN, $amount, $type)
    {
        $this -> _db -> updateOrderPayed($batchSN, $amount, $type);
    }
    
    /**
     * 退回礼品卡
     *
     * @param   string      $batchSN
     * @param   int         $logID
     * @return  void
     */
    public function returnGiftCard($batchSN, $logID)
    {
        $giftCardAPI = new Admin_Models_API_GiftCard();
        $giftCardLog = $giftCardAPI -> getUseLog(array('batch_sn' => $batchSN));
        $giftCardLog = $giftCardAPI -> setCanReturnCard($giftCardLog['content']);
        if (!$giftCardLog) {
           return false;
        }
        
        foreach ($giftCardLog as $temp) {
            if ($temp['log_id'] == $logID && $temp['can_return']) {
                $giftCard = $temp;
                break;
            }
        }
        if (!$giftCard) {
            return false;
        }
        
        $cardAPI = new Shop_Models_DB_Card();
        $card = array('card_type' => $giftCard['card_type'],
                      'card_price' => $giftCard['price'],
                      'card_sn' => $giftCard['card_sn'],
                      'batch_sn' => $giftCard['batch_sn'],
                      'admin_id' => $this -> _auth['admin_id'],
                      'admin_name' => $this -> _auth['admin_name'],
                      'add_time' => time(),
                     );
        if (!$cardAPI -> unUseGift($card)) {
            return false;
        }
        
        $this -> _db -> updateOrderPayed($batchSN, $giftCard['price'] * -1, 'gift_card');
        
        return true;
    }
    
    /**
     * 获取订单礼品卡
     *
     * @param   array   $productData
     * @return  void
     */
    public function setOrderGiftCard(&$productData)
    {
        if (!$productData)  return false;
        
        $giftCardAPI = new Admin_Models_API_GiftCard();
        
        foreach ($productData as $data) {
            $data['order_batch_goods_id'] > 0 && $idArray[] = $data['order_batch_goods_id'];
        }
        
        $this -> _auth = Admin_Models_API_Auth  ::  getInstance() -> getAuth();
        $datas = $giftCardAPI -> getCardlist(array('order_batch_goods_id' => $idArray, 'status' => array(0,1,2)));
        $datas = $datas['content'];
        if (!$datas)    return false;
        
        foreach ($datas as $data) {
            $cardInfo[$data['order_batch_goods_id']][] = $data;
        }
        
        foreach ($productData as $index => $data) {
            $cardInfo[$data['order_batch_goods_id']] && $productData[$index]['gift_card'] = $cardInfo[$data['order_batch_goods_id']];
        }
    }
    
    /**
     * 获取订单虚拟商品
     *
     * @param   array   $productData
     * @return  void
     */
    public function setOrderVitualGoods(&$productData)
    {
        if (!$productData)  return false;
        
        $vitualGoodsAPI = new Admin_Models_API_VitualGoods();
        
        foreach ($productData as $data) {
            $data['order_batch_goods_id'] > 0 && $idArray[] = $data['order_batch_goods_id'];
        }
        
        $datas = $vitualGoodsAPI -> getVitualGoods(array('order_batch_goods_id' => $idArray, 'status' => array(1,2)));
        if (!$datas)    return false;
        
        foreach ($datas as $data) {
            $vitualInfo[$data['order_batch_goods_id']][] = $data;
        }
        
        foreach ($productData as $index => $data) {
            $vitualInfo[$data['order_batch_goods_id']] && $productData[$index]['vitual_goods'] = $vitualInfo[$data['order_batch_goods_id']];
        }
    }
    
    /**
     * 发货后包含礼品卡的订单增加抵扣应收款
     *
     * @param   array   $orderDetail
     * @return  boolean
     */
    public function addFinanceReceivableForPreGiftCard($orderDetail)
    {
        $hasGiftCard = false;
        $onlyGiftCard = true;
        $productList = $orderDetail['product_all'];
        foreach ($productList as $product) {
            if (!$product['product_id'])    continue;
            if ($product['is_gift_card']) {
                $hasGiftCard = true;
            }
            else {
                $onlyGiftCard = false;
            }
        }
        
        if (!$hasGiftCard || $onlyGiftCard) return false;
        
        $order = $orderDetail['order'];
        
        $giftCardPrice = 0;
        foreach ($productList as $product) {
            if ($product['is_gift_card']) {
                $giftCardPrice += $product['sale_price'] * ($product['number'] - $product['return_number']);
            }
        }
        $pricePayed = $order['price_pay'] - $giftCardPrice;
        $giftCardPayed = $order['price_goods'] + $order['price_logistic'] + $order['price_adjust'] + $order['price_from_return'] - $pricePayed;
        
        $financeAPI = new Admin_Models_API_Finance();
        $receiveData = array('batch_sn' => $order['batch_sn'],
                             'type' => 5,
                             'pay_type' => 'gift',
                             'amount' => $giftCardPayed,
                            );
        $financeAPI -> addFinanceReceivable($receiveData);
        
        return true;
    }
    
    /**
     * 签收后包含礼品卡的订单拆分
     *
     * @param   array   $orderDetail
     * @return  boolean
     */
    public function splitOrderForgiftCard($orderDetail)
    {
        $hasGiftCard = false;
        $onlyGiftCard = true;
        $productList = $orderDetail['product_all'];
        foreach ($productList as $product) {
            if (!$product['product_id'] && $product['type'] != 5)   continue;
            if ($product['is_gift_card']) {
                $hasGiftCard = true;
            }
            else {
                $onlyGiftCard = false;
            }
        }

        if (!$hasGiftCard || $onlyGiftCard) return false;
        
        $order = $orderDetail['order'];
        
        $giftCardPrice = 0;
        foreach ($productList as $product) {
            if ($product['is_gift_card']) {
                $giftCardPrice += $product['sale_price'] * ($product['number'] - $product['return_number']);
                $orderBatchGoodsIDArray[] = $product['order_batch_goods_id'];
            }
        }
        
        //新增 批次
        $temp = array_shift($this -> _db -> getOrderBatch(array('order_sn' => $order['order_sn']), 'add_time desc'));
        $temp = explode('_', $temp['batch_sn']);
        $newBatchSN = $order['order_sn'].'_'.(intval($temp['1']) + 1);
        $data = array('order_id' => $order['order_id'],
                      'order_sn' => $order['order_sn'],
                      'batch_sn' => $newBatchSN,
                      'parent_batch_sn' => $order['batch_sn'],
                      'type' => $order['type'],
                      'add_time' => strtotime($order['add_time']),
                      'is_visit' => $order['is_visit'],
                      'is_fav' => $order['is_fav'],
                      'is_send' => $order['is_send'],
                      'status' => 5,
                      'status_logistic' => 4,
                      'status_pay' => 2,
                      'price_order' => $giftCardPrice,
                      'price_goods' => $giftCardPrice,
                      'price_pay' => $giftCardPrice,
                      'price_payed' => $giftCardPrice,
                      'balance_amount' => $giftCardPrice,
                      'pay_type' => $order['pay_type'],
                      'pay_name' => $order['pay_name'],
                      'pay_time' => $order['pay_time'],
                      'logistic_name' => $order['logistic_name'],
                      'logistic_code' => $order['logistic_code'],
                      'logistic_no' => $order['logistic_no'],
                      'logistic_time' => $order['logistic_time'],
                      'logistic_signed_time' => $order['logistic_signed_time'],
                      'addr_consignee' => $order['addr_consignee'],
                      'addr_province_id' => $order['addr_province_id'],
                      'addr_city_id' => $order['addr_city_id'],
                      'addr_area_id' => $order['addr_area_id'],
                      'addr_province' => $order['addr_province'],
                      'addr_city' => $order['addr_city'],
                      'addr_area' => $order['addr_area'],
                      'addr_address' => $order['addr_address'],
                      'addr_zip' => $order['addr_zip'],
                      'addr_tel' => $order['addr_tel'],
                      'addr_mobile' => $order['addr_mobile'],
                      'addr_email' => $order['addr_email'],
                      'addr_fax' => $order['addr_fax'],
                      'sms_no' => $order['sms_no'],
                     );
        $orderBatchID = $this -> _db -> addOrderBatch($data);
        
        //设置 最新批次号
        $this -> _db -> updateOrder(array('order_sn' => $order['order_sn']), array('batch_sn' => $newBatchSN));
        
        //新增礼品卡订单商品
        foreach ($productList as $product) {
            if (!$product['is_gift_card'])  continue;
            
            $data = array('order_id' => $order['order_id'],
                          'order_batch_id' => $orderBatchID,
                          'order_sn' => $order['order_sn'],
                          'batch_sn' => $newBatchSN,
                          'type' => $product['type'],
                          'is_send' => $product['is_send'],
                          'add_time' => strtotime($order['add_time']),
                          'product_id' => $product['product_id'],
                          'product_sn' => $product['product_sn'],
                          'goods_id' => $product['goods_id'],
                          'goods_name' => $product['goods_name'],
                          'cat_id' => $product['cat_id'],
                          'cat_name' => $product['cat_name'],
                          'weight' => $product['weight'],
                          'length' => $product['length'],
                          'width' => $product['width'],
                          'height' => $product['height'],
                          'goods_style' => $product['goods_style'],
                          'sale_price' => $product['sale_price'],
                          'eq_price' => $product['sale_price'],
                          'number' => $product['number'] - $product['return_number'],
                          'remark' => $product['remark'],
                         );
            $orderBatchGoodsIDInfo[] = array('oldOrderBatchGoodsID' => $product['order_batch_goods_id'],
                                             'newOrderBatchGoodsID' => $this -> _db -> addOrderBatchGoods($data),
                                             'is_vitual' => $product['is_vitual'],
                                            );
        }
        
        //运输单和出库单合单
        $transportAPI  = new Admin_Models_DB_Transport();
        $outstockAPI = new Admin_Models_DB_OutStock();
        $relationData = array_shift($transportAPI -> getRelationOrder("bill_no = '{$order['batch_sn']}'"));
        if ($relationData) {
            $transport = array_shift($transportAPI -> get("tid = '{$relationData['transport_id']}'", 'tid,bill_no'));
            $outstock = array_shift($outstockAPI -> get("b.outstock_id = '{$relationData['outstock_id']}'", 'b.outstock_id,bill_no'));
            if ($transport && $outstock) {
                $data = array('bill_no' => $newBatchSN,
                              'transport_id' => $relationData['transport_id'],
                              'outstock_id' => $relationData['outstock_id'],
                             );
                $transportAPI -> insertRelationOrder($data);
                $transportAPI -> update(array('bill_no' => $transport['bill_no'].",{$newBatchSN}"), "tid = '{$transport['tid']}'");
                $outstockAPI -> update(array('bill_no' => $outstock['bill_no'].",{$newBatchSN}"), "outstock_id = '{$outstock['outstock_id']}'");
            }
        }
        
        //修改原订单支付金额，礼品卡抵扣
        $pricePayed = $order['price_payed'] - $giftCardPrice;
        $giftCardPayed = $order['price_goods'] + $order['price_logistic'] + $order['price_adjust'] + $order['price_from_return'] - $pricePayed;
        
        $giftCardAPI = new Admin_Models_API_GiftCard();
        $cardAPI = new Shop_Models_DB_Card();
        $giftCardList = array_shift($giftCardAPI -> getCardlist(array('order_batch_goods_id' => $orderBatchGoodsIDArray, 'status' => 0)));
        if ($giftCardList) {
            $realGiftCardPayed = 0;
            foreach ($giftCardList as $card) {
                if ($giftCardPayed > 0) {
                    if ($card['card_real_price'] >= $giftCardPayed) {
                        $cardPrice = $giftCardPayed;
                        $giftCardPayed = 0;
                    }
                    else {
                        $cardPrice = $card['card_real_price'];
                        $giftCardPayed -= $cardPirce;
                    }
                }
                else {
                    break;
                }
                
                $card = array('card_type' => $card['card_type'],
                              'card_price' => $cardPrice,
                              'card_sn' => $card['card_sn'],
                              'card_pwd' => $card['card_pwd'],
                              'add_time' => time(),
                              'admin_id' => $this -> _auth['admin_id'] ? $this -> _auth['admin_id'] : 0,
                              'admin_name' => $this -> _auth['admin_name'] ? $this -> _auth['admin_name'] : 'system',
                              'batch_sn' => $order['batch_sn'],
                             );
                if ($cardAPI -> useGift($card)) {
                    $realGiftCardPayed += $cardPrice;
                }
            }
        }
        $set = array('price_payed' => $pricePayed, 'gift_card_payed' => $realGiftCardPayed, 'price_before_return' => $order['price_order']);
        if ($pricePayed <= 0) {
            $set['pay_type'] = 'no_pay';
            $set['pay_name'] = '无需支付';
        }
        $this -> _db -> updateOrderBatch(array('batch_sn' => $order['batch_sn']), $set);
        
        //删除原订单的礼品卡订单商品
        $this -> _db -> delOrderBatchGoods("order_batch_goods_id in (".implode(',', $orderBatchGoodsIDArray).")");
        
        //更改礼品卡和虚拟商品的order_batch_goods_id
        $vitualGoodsAPI = new Admin_Models_API_VitualGoods();
        foreach ($orderBatchGoodsIDInfo as $orderBatchGoods) {
            $giftCardAPI -> updateCard("order_batch_goods_id = '{$orderBatchGoods['oldOrderBatchGoodsID']}'", array('order_batch_goods_id' => $orderBatchGoods['newOrderBatchGoodsID']));
            if ($orderBatchGoods['is_vitual']) {
                $vitualGoodsAPI -> update(array('order_batch_goods_id' => $orderBatchGoods['newOrderBatchGoodsID']), "order_batch_goods_id = '{$orderBatchGoods['oldOrderBatchGoodsID']}'");
            }
        }
        
        $this -> orderDetail($order['batch_sn']);
        
        return true;
    }
    
    /**
     * 订单正在退款记录与总金额
     *
     * @param   string  $batchSN
     * @return  float
     */
    private function _financeReturningDetail($batchSN)
    {
        $financeList = $this -> _finance -> getFinance(array('item_no' => $batchSN, 'status' => array(0,1), 'item' => 1));
        if (!$financeList)  return false;
        
        $amount = 0;
        if ($financeList) {
            foreach ($financeList as $finance) {
                $amount += abs($finance['pay'] + $finance['point'] + $finance['account'] + $finance['gift']);
            }
        }
        
        return array('data' => $financeList, 'amount' => $amount);
    }
    
	public function getError()
	{
		return $this->_error;
	}
}
