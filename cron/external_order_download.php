<?php
require_once "global.php";

function downloadOrder()
{
    $api = new Admin_Models_API_Shop();
    $currentTime = time();
    $datas = $api -> get("status = 0 and sync_order_interval > 0 and (sync_order_time = 0 or (sync_order_time + sync_order_interval * 60) < {$currentTime})", 'shop_id,shop_type,config');
    
    if ( !$datas['list'] )  exit;
    
    $allArea = $api -> getAllArea();
    foreach ( $datas['list'] as $shop ) {
        $shopID = $shop['shop_id'];
        $api -> updateOrderSyncTime($shopID);
        
        $shopAPI = Custom_Model_Shop_Base::getInstance($shop['shop_type'], unserialize($shop['config']));
        $shopAPI -> initSyncLog();
        $api -> initSyncLog();
        
        if ($shop['shop_type'] == 'jingdong') {
            $shopGoodsData = $api -> getGoods(array('shop_id' => $shopID), 'shop_sku_id,goods_sn');
            if ($shopGoodsData['list']) {
                $goods = '';
                foreach ($shopGoodsData['list'] as $shopGoods) {
                    $goods[$shopGoods['shop_sku_id']] = $shopGoods['goods_sn'];
                }
            }
            
            $orderData = $shopAPI -> syncOrder($allArea, '', '', $goods);
        }
        else if ($shop['shop_type'] == 'yihaodian') {
            $orderData = $shopAPI -> syncOrder($allArea, '', '', 2);
        }
        else {
            $orderData = $shopAPI -> syncOrder($allArea, '', '');
        }
        
        if ( $orderData ) {
            $orderData = $api -> getOrderLogistic($shopAPI, $orderData);
            if ( $orderData ) {
                foreach ( $orderData as $order ) {
                    $order['shop_id'] = $shopID;
                    $api -> updateOrder($order);
                }
            }
        }
        
        $logID = $api -> addSyncLog($shopID, 'order', $shopAPI -> _startTime, $shopAPI -> _log.$api -> _log);
    }
}

downloadOrder();
