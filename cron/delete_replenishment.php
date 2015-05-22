<?php
require_once "global.php";
/**
 * 删除无效的补货单
 *
 **/
class delete_replenishment
{
	
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}

	/**
	 * 根据条件获取订单信息
	 *
	 * @return array
	 **/
	public function getOrderInfosByOrderBatchCondition()
    {
        $_condition[] = " (b.status IN (1,2) OR b.status_logistic > 0 )";
        $_condition[] = " r.type = 2";
        $sql = "SELECT r.id, b.order_batch_id, b.`batch_sn` FROM `shop_order_batch` b LEFT JOIN `shop_replenishment_order` r ON r.shop_order_id = b.order_batch_id WHERE ". implode(' AND ', $_condition);

        $infos = $this->_db->fetchAll($sql);

        if (empty($infos)) {
            return array();
        }

        return $infos;
    }

    /**
	 * 根据条件获取渠道订单信息
	 *
	 * @return array
	 **/
	public function getShopOrderInfosByOrderBatchCondition()
    {
        $_condition[] = "  (s.status_business > 0 OR s.status = 11) ";
        $_condition[] = " r.type = 0";
        $sql = "SELECT s.shop_order_id, s.external_order_sn FROM `shop_order_shop` s LEFT JOIN `shop_replenishment_order` r ON r.shop_order_id = s.shop_order_id WHERE ". implode(' AND ', $_condition);

        $infos = $this->_db->fetchAll($sql);

        if (empty($infos)) {
            return array();
        }

        return $infos;
    }

    public function deleteReplenishment()
    {
        $replenish_db   = new Admin_Models_API_Replenishment();
        $infos = $this->getOrderInfosByOrderBatchCondition();
        
        //删除官网订单对应的补货单
        if (!empty($infos)) {
            foreach ($infos as $info) {
                $replenish_info = $replenish_db->cancelOrderReplenish($info['order_batch_id'], 2);
            }
        }

        //删除渠道订单对应的补货单
        $shop_infos = $this->getShopOrderInfosByOrderBatchCondition();
        if (!empty($shop_infos)) {
            foreach ($shop_infos as $info) {
                $replenish_info = $replenish_db->cancelOrderReplenish($info['shop_order_id']);
            }
        }

        return true;
    }


	public function getError()
	{
		return $this->_error;
	}
	
}


$replenish = new delete_replenishment();
if (false === $replenish->deleteReplenishment()) {
	exit($member->getError());
}


exit('操作成功');