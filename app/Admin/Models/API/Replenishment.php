<?php
class Admin_Models_API_Replenishment {
	private $_db;
	private $_table_replenishment_product = 'shop_replenishment_product';
	private $_table_replenishment_order = 'shop_replenishment_order';
	private $_table_replenishment_bill  = 'shop_replenishment_bill';
	private $_table_product  = 'shop_product';
	private $_table_order_shop = 'shop_order_shop';
	public $_error;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
     * 申请补货
     *
     * @param    int        $shopOrderID
     * @param    int        $productID
     * @param    int        $number
     * @return   boolean
     */
	public function applyReplenish($shopOrderID, $productID, $number, $type=0)
	{
	    $data = array_shift($this -> getOrderRecord("t1.shop_order_id = '{$shopOrderID}' and t2.product_id = '{$productID}'"));
	    if ($data) {
	        if ($data['status']) {
	            return false;
	        }
	        if ($number == $data['number']) {
	            return false;
	        }
	        
	        $this -> setOrderRecord(array('number' => $number), "id = {$data['id']}");
	        if ($number > $data['number']) {
	            $this -> incProductNumber($data['replenishment_id'], 'require_number', $number - $data['number']);
	        }
	        else {
	            $this -> decProductNumber($data['replenishment_id'], 'require_number', $data['number'] - $number);
	        }
	    }
	    else {
	        $data = array_shift($this -> getProductRecord(array('product_id' => $productID, 'status' => 0)));
    	    if ($data) {
    	        $id = $data['replenishment_id'];
    	        $this -> incProductNumber($id, 'require_number', $number);
    	    }
    	    else {
    	        $row = array('product_id' => $productID,
    	                     'require_number' => $number,
    	                     'add_time' => time(),
    	                     'update_time' => time(),
    	                    );
    	        $this -> addProductRecord($row);
    	        $id = $this -> _db -> lastInsertId();
    	    }
    	    $row = array('replenishment_id' => $id,
    	                 'shop_order_id' => $shopOrderID,
    	                 'number' => $number,
						 'type'   => $type,
    	                );
    	    $this -> addOrderRecord($row);
	    }

		return true;
	}
	
	/**
     * 取消产品补货
     *
     * @param    int        $shopOrderID
     * @param    int        $productID
     * @return   boolean
     */
	public function cancelProductReplenish($shopOrderID, $productID, $type = 0)
	{
	    $data = array_shift($this -> getOrderRecord("t1.shop_order_id = '{$shopOrderID}' and t2.product_id = '{$productID}' and t2.status = 0 AND t1.type = '{$type}'"));
	    if (!$data) return false;
	    
	    $this -> deleteOrderRecord("id = {$data['id']}");
	    $this -> decProductNumber($data['replenishment_id'], 'require_number', $data['number']);
	    
	    return true;
	}
	
	/**
     * 取消订单补货
     *
     * @param    int        $shopOrderID
     * @param    int        $productID
     * @return   boolean
     */
	public function cancelOrderReplenish($shopOrderID, $type=0)
	{
	    $datas = $this -> getOrderRecord("t1.shop_order_id = '{$shopOrderID}' and t2.status = 0 and t1.type='{$type}'");
	    if (!$datas)    return false;
	    
	    foreach ($datas as $data) {
	        $this -> deleteOrderRecord("id = {$data['id']}");
	        $this -> decProductNumber($data['replenishment_id'], 'require_number', $data['number']);
	    }
	    
	    return true;
	}
	
	/**
     * 获得订单商品补货状态
     *
     * @param    array      $shopOrderIDArray
     * @return   array
     */
	public function getReplenishStatus($shopOrderIDArray)
	{
	    if (!$shopOrderIDArray || count($shopOrderIDArray) == 0)    return false;
	    
	    $datas = $this -> getOrderRecord("t1.shop_order_id in (".implode(',', $shopOrderIDArray).")");
	    if ($datas) {
	        foreach ($datas as $data) {
	            if ($data['status'] == 3 || $data['status'] == 4) {
	                $result[$data['shop_order_id']][$data['product_sn']] = '#00BB00';
	            }
	            else if ($data['status'] == 1 || $data['status'] == 2) {
	                $result[$data['shop_order_id']][$data['product_sn']] = '#006030';
	            }
	            else {
	                $result[$data['shop_order_id']][$data['product_sn']] = '#FF5151';
	            }
	        }
	    }
	    
	    return $result;
	}
	
	/**
     * 设置补货单状态
     *
     * @param    int/array  $replenishmentID
     * @param    int        $status
     * @return   void
     */
	public function updateProductStatus($replenishmentID, $status)
	{
        if (!is_array($replenishmentID)) {
            $replenishmentID = array($replenishmentID);
        }
        foreach ($replenishmentID as $id) {
            $this -> setProductRecord(array('status' => $status), "replenishment_id = '{$id}'");
        }
	}
	
	/**
     * 增加补货单据
     *
     * @param    string     $billNo
     * @param    array      $ids
     * @param    array      $number
     * @return   boolean
     */
	public function addBill($billNo, $ids, $number)
	{
        if (!$ids || count($ids) == 0 || !$number || count($number) == 0) {
            return false;
        }
        
        foreach ($ids as $index => $id) {
            $row = array('replenishment_id' => $id,
                         'bill_no' => $billNo,
                         'number' => $number[$index],
                        );

            $this -> addBillRecord($row);                        
        }
        
        return true;
	}
	
	/**
     * 跟踪补货单状态
     *
     * @param    string     $billNo
     * @return   boolean
     */
	public function traceStatus($billNo, $status)
	{
	    $datas = $this -> getBillRecord("bill_no = '{$billNo}'");
	    if (!$datas)    return false;
	    
	    foreach ($datas as $data) {
	        $ids[] = $data['replenishment_id'];
	    }
	    $this -> updateProductStatus($ids, $status);
	}
	
	/**
     * 收货
     *
     * @param    array      $data
     * @param    string     $newbillNo
     * @return   boolean
     */
	public function receive($data, $newbillNo = null)
	{
	    $datas = $this -> getBillRecord("bill_no = '{$data['bill_no']}'");
	    if (!$datas)    return false;
	    
	    foreach ($datas as $temp) {
	        $bill[$temp['product_id']] = $temp;
	    }
	    
	    foreach ($data['plan_number'] as $k => $v) {
	        $productID = substr($k, 0, strpos($k, '_'));
	        $planNumber[$productID] = $v;
	    }
	    
	    foreach ($data['number'] as $k => $v) {
	        foreach ($v as $key => $num) {
	            $productID = substr($k, 0, strpos($k, '_'));
	            $realNumber[$productID] += $num;
	        }
	    }
	    
	    foreach ($planNumber as $productID => $number) {
	        if ($number > $realNumber[$productID]) {
	            $newNumber[$productID] = $number - $realNumber[$productID];
	        }
	    }
        
	    $ids = '';
	    foreach ($realNumber as $productID => $number) {
	        $replenishmentID = $bill[$productID]['replenishment_id'];
    	    if ($replenishmentID) {
    	        $ids[] = $replenishmentID;
    	        $this -> incProductNumber($replenishmentID, 'receive_number', $number);
    	        $this -> checkOrder($replenishmentID);
    	    }
	    }
	    
	    if ($ids) {
	        $datas = $this -> getProductRecord(array('ids' => $ids));
	        foreach ($datas as $temp) {
	            if ($temp['receive_number'] >= $temp['require_number']) {
	                $status = 4;
	            }
	            else {
	                $status = 3;
	            }
	            $this -> updateProductStatus($temp['replenishment_id'], $status);
	        }
	    }
	    
	    if ($newNumber && $newbillNo) {
	        foreach ($realNumber as $productID => $number) {
    	        $replenishmentID = $bill[$productID]['replenishment_id'];
    	        if ($replenishmentID) {
    	            $this -> setBillRecord(array('number' => $number), "replenishment_id = '{$replenishmentID}'");
    	        }
    	    }
    	    
    	    foreach ($newNumber as $productID => $number) {
    	        $replenishmentID = $bill[$productID]['replenishment_id'];
    	        if ($replenishmentID) {
    	            $row = array('replenishment_id' => $replenishmentID,
    	                         'bill_no' => $newbillNo,
    	                         'number' => $number,
    	                        );
    	            $this -> addBillRecord($row);
    	        }
    	    }
	    }


	    
	    return true;
	}
	
	/**
     * 自动审核订单
     *
     * @param    int        $replenishmentID
     * @return   boolean
     */
	public function checkOrder($replenishmentID, $data = array())
	{
		
	    $datas = $this -> getOrderRecord("t1.replenishment_id = '{$replenishmentID}'");
		
	    if (!$datas)    return false;
	    
	    if (!$this -> _shopAPI) {
	        $this -> _shopAPI = new Admin_Models_API_Shop();
	    }
		if (!$this->_orderAPI) {
			$this->_orderAPI = new Admin_Models_API_Order();
		}

	    
	    foreach ($datas as $data) {
			if ($data['type'] == '2') {
				$order_info = $this->_orderAPI->getOrderBatchInfoAndGoodsInfosByBatchId($data['shop_order_id']);
				if ($this->_orderAPI->checkOut($order_info['batch_sn'], 1)) {
					$logic_area = !empty($data['logic_area']) ? $data['logic_area'] : '1';
					$this->_orderAPI -> out(array($order_info['batch_sn']), null, $logic_area);
				}
			} else {
				$temp = $this -> _shopAPI -> getOrder(array('shop_order_id' => $data['shop_order_id']));
				$order = array_shift($temp['list']);
				if (!$order)    continue;
				if ($order['status'] != 2)  continue;
				if ($order['status_business'])  continue;
				if ($order['sync']) continue;

				if ($this -> _shopAPI -> checkProductStock($order, true, false)) {
					$this -> _shopAPI -> checkOrder($order['shop_order_id'], 1);
				}
			}
	    }
	}
	
	/**
     * 添加补货商品
     *
     * @param    array      $ids
     * @return   boolean
     */
	public function addProduct($ids)
	{
	    $result = false;
	    foreach ($ids as $productID) {
	        if ($this -> getProductRecord(array('product_id' => $productID))) {
	            continue;
	        }
	        $row = array('product_id' => $productID,
	                     'require_number' => 1,
	                     'status' => 0,
	                     'add_time' => time(),
	                     'update_time' => time(),
	                    );
	        $this -> addProductRecord($row);
	        $result = true;
	    }
	    
	    return $result;
	}
	
	/**
     * 调整补货商品数量
     *
     * @param    int      $id
     * @param    int      $number
     * @return   boolean
     */
	public function changeProductNumber($id, $number)
	{
	    $data = array_shift($this -> getProductRecord(array('replenishment_id' => $id)));
	    if (!$data)     return false;
	    if ($data['manual_number'] == $number)  return false;
	    
	    $diff = $data['manual_number'] - $number;
	    if ($diff > 0) {
	        $this -> decProductNumber($id, 'require_number', $diff);
	    }
	    else {
	        $this -> incProductNumber($id, 'require_number', abs($diff));
	    }
	    
	    return true;
	}
	
    public function getProductRecord($where = 1, $field = null, $page = null, $pageSize = null, $order = null)
    {
        if ($page) {
		    $offset = ($page - 1) * $pageSize;
		    $limit = " limit $pageSize offset $offset";
		}
		if ($order) {
		    $orderBy = "order by {$orderBy}";
		}
		!$field && $field = 't1.*,t2.product_sn,t2.product_name,t2.cost';
		
		$sql = "select {$field} from {$this -> _table_replenishment_product} as t1
                left join {$this -> _table_product} as t2 on t1.product_id = t2.product_id
                where ".$this -> getProductRecordSQL($where)." {$orderBy} {$limit}";

        $datas = $this -> _db -> fetchAll($sql);
        if ($datas) {
            foreach ($datas as $data) {
                $ids[] = $data['replenishment_id'];
            }
            $sql = "select replenishment_id, sum(number) as number from {$this -> _table_replenishment_order} where replenishment_id in (".implode(',', $ids).") group by replenishment_id";
            $tempData = $this -> _db -> fetchAll($sql);
            foreach ($tempData as $data) {
                $countInfo[$data['replenishment_id']] = $data['number'];
            }
            foreach ($datas as $index => $data) {
                $datas[$index]['auto_number'] = $countInfo[$data['replenishment_id']];
                $datas[$index]['manual_number'] = $datas[$index]['require_number'] - $datas[$index]['auto_number'];
            }
        }
        
        return $datas;
    }
    
    public function getProductRecordCount($where = 1)
    {
        $sql = "select count(*) as number from {$this -> _table_replenishment_product} as t1
                left join {$this -> _table_product} as t2 on t1.product_id = t2.product_id
                where ".$this -> getProductRecordSQL($where);
        return array_shift($this -> _db -> fetchCol($sql));
    }
    
    public function getOrderRecord($where = 1)
    {
        $sql = "select t1.*,t2.replenishment_id,t2.product_id,t2.status,t3.product_sn from {$this -> _table_replenishment_order} as t1 
                left join {$this -> _table_replenishment_product} as t2 on t1.replenishment_id = t2.replenishment_id
                left join {$this -> _table_product} as t3 on t2.product_id = t3.product_id
                where {$where}";

				// @去掉渠道关联
				// t4.external_order_sn,t4.status as shop_order_status,t4.status_business
				//left join {$this -> _table_order_shop} as t4 on t1.shop_order_id = t4.shop_order_id
        $datas =  $this -> _db -> fetchAll($sql);

		foreach ($datas as &$data) {
			
			if ($data['type'] == '2') {
				$sql = "SELECT b.order_sn, b.batch_sn, b.status, b.status_logistic, shop_name FROM shop_order_batch b
                        LEFT JOIN `shop_order` o ON b.order_id = o.order_id
                        LEFT JOIN `shop_shop` s ON  o.shop_id = s.shop_id WHERE order_batch_id = '{$data['shop_order_id']}'";
				$order_batch_info = $this->_db->fetchRow($sql);

				$data['status']            = $order_batch_info['status'];
				$data['batch_sn']          = $order_batch_info['batch_sn'];
                $data['shop_name']         = $order_batch_info['shop_name'];
			} else if (in_array($data['type'] , array(0,1))) {
				$sql = "SELECT o.external_order_sn, o.status as shop_order_status, o.status_business, shop_name, o.shop_id FROM {$this->_table_order_shop} o LEFT JOIN `shop_shop` s ON o.shop_id = s.shop_id WHERE shop_order_id = '{$data['shop_order_id']}'";
				$shop_order_info = $this->_db->fetchRow($sql);
					$data['external_order_sn'] = $shop_order_info['external_order_sn'];
					$data['shop_order_status'] = $shop_order_info['shop_order_status'];
					$data['status_business']   = $shop_order_info['status_business'];
                    $data['shop_name']         = $shop_order_info['shop_name'];
                    $data['shop_id']           = $shop_order_info['shop_id'];
			} 
		}
		return $datas;
    }
    
    public function getBillRecord($where = 1)
    {
        $sql = "select t1.*,t2.product_id from {$this -> _table_replenishment_bill} as t1 
                left join {$this -> _table_replenishment_product} as t2 on t1.replenishment_id = t2.replenishment_id 
                where {$where}";
        return $this -> _db -> fetchAll($sql);
    }
    
    private function getProductRecordSQL($where)
    {
        if (is_array($where)) {
		    $whereSql = 1;
		    if ($where['bill_no']) {
		        $where['ids'] = array(0);
		        $datas = $this -> getBillRecord("bill_no = '{$where['bill_no']}'");
		        if ($datas) {
		            foreach ($datas as $data) {
		                $where['ids'][] = $data['replenishment_id'];
		            }
                }
		    }
		    $where['replenishment_id'] && $whereSql .= " and t1.replenishment_id = '{$where['replenishment_id']}'";
		    $where['ids'] && $whereSql .= " and t1.replenishment_id in (".implode(',', $where['ids']).")";
		    $where['product_id'] && $whereSql .= " and t1.product_id = '{$where['product_id']}'";
		    $where['product_sn'] && $whereSql .= " and t2.product_sn = '{$where['product_sn']}'";
		    $where['product_name'] && $whereSql .= " and t2.product_name like '%{$where['product_name']}%'";
		    $where['status_array'] && $whereSql .= " and t1.status in (".implode(',', $where['status_array']).")";
		    if ($where['status'] !== null && $where['status'] !== '') {
		        $whereSql .= " and t1.status = '{$where['status']}'";
		    }
		    if ($where['supplier_id']) {
		        $supplierAPI = new Admin_Models_API_Supplier();
		        $supplier = array_shift($supplierAPI -> get("supplier_id = '{$where['supplier_id']}'", 'product_ids'));
		        if ($supplier[0]['product_ids']) {
		            $condition = $supplier[0]['product_ids'];
		        }
		        else {
		            $condition = 0;
		        }
		        $whereSql .= " and t1.product_id in ({$condition})";
		    }
		}
		else {
		    $whereSql = $where;
		}

		return $whereSql;
    }
    
    private function setProductRecord($data, $where)
    {
        $data['update_time'] = time();
        $this -> _db -> update($this -> _table_replenishment_product, $data, $where);
    }
    
    private function incProductNumber($id, $field, $number)
    {
        $this -> _db -> execute("update {$this -> _table_replenishment_product} set {$field} = {$field} + {$number}, update_time = ".time()." where replenishment_id = {$id}");
    }
    
    private function decProductNumber($id, $field, $number)
    {
        $this -> _db -> execute("update {$this -> _table_replenishment_product} set {$field} = {$field} - {$number}, update_time = ".time()." where replenishment_id = {$id}");
        $data = $this -> _db -> fetchRow("select require_number from {$this -> _table_replenishment_product} where replenishment_id = {$id}");
        if ($data['require_number'] <= 0) {
            $this -> _db -> delete($this -> _table_replenishment_product, "replenishment_id = {$id}");
        }
    }
    
    private function addProductRecord($data)
    {
        $this -> _db -> insert($this -> _table_replenishment_product, $data);
    }
    
    private function setOrderRecord($data, $where)
    {
        $this -> _db -> update($this -> _table_replenishment_order, $data, $where);
    }
    
    private function addOrderRecord($data)
    {
        $this -> _db -> insert($this -> _table_replenishment_order, $data);
    }
    
    private function deleteOrderRecord($where)
    {
        $this -> _db -> delete($this -> _table_replenishment_order, $where);
    }
    
    private function addBillRecord($data)
    {
        $this -> _db -> insert($this -> _table_replenishment_bill, $data);
    }
    
    private function setBillRecord($data, $where)
    {
        $this -> _db -> update($this -> _table_replenishment_bill, $data, $where);
    }
}
