<?php
require_once "global.php";
/**
 * 降级会员等级
 *
 **/
class insert_cost
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
        $_condition[] = " (status = 1 OR is_send = 1)";
        $sql = "SELECT order_batch_id, `batch_sn` FROM `shop_order_batch` WHERE ". implode(' AND ', $_condition);

        $infos = $this->_db->fetchAll($sql);

        if (empty($infos)) {
            return array();
        }

        return $infos;
    }

    //public function 


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