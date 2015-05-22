<?php
require_once "global.php";

function getJingdongStatus($shopID)
{
    global $db;
    
    $api = new Admin_Models_API_Shop();
    
    $shop = $db -> fetchRow("select shop_type,config from shop_shop where shop_id = {$shopID}");
    if (!$shop) return false;
    
    $shopAPI = Custom_Model_Shop_Base::getInstance($shop['shop_type'], unserialize($shop['config']));
    
    $orders = $db -> fetchAll("select shop_order_id,external_order_sn,status from shop_order_shop where shop_id = {$shopID} and status = 2 and status_business <> 9");
    if (!$orders)   return true;
    
    $shopAPI -> initSyncLog();
    
    foreach ($orders as $order) {
        $status = $shopAPI -> getOrderStatus($order['external_order_sn']);
        if (!$status)   {
            continue;
        }
        if ($status != $order['status']) {
            $orders = $db -> update('shop_order_shop', array('status' => $status),  "shop_order_id = '{$order['shop_order_id']}'");
            $shopAPI -> _log .= "订单{$order['external_order_sn']}改变状态为{$status}\n";
        }
    }
    
    $logID = $api -> addSyncLog($shopID, 'order', $shopAPI -> _startTime, $shopAPI -> _log);
}

getJingdongStatus(31);
getJingdongStatus(32);