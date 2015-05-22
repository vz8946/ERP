<?php
require_once "global.php";

function downloadGoods()
{
    $api = new Admin_Models_API_Shop();
    $currentTime = time();
    $datas = $api -> get("status = 0 and shop_id >= 21 and shop_id <= 51", 'shop_id,shop_type,config');
    
    if (!$datas['list'])    exit;
    
    $api -> deleteGoods("shop_id >= 21 and shop_id <= 51");
    
    foreach ($datas['list'] as $shop) {
        $shopID = $shop['shop_id'];
        
        $shopAPI = Custom_Model_Shop_Base::getInstance($shop['shop_type'], unserialize($shop['config']));
        $shopAPI -> initSyncLog();
        $api -> initSyncLog();
        
        $goodsData = $shopAPI -> syncGoods();
        if ($goodsData) {
            foreach ($goodsData as $goods) {
                $goods['shop_id'] = $shopID;
                $api -> updateGoods($goods);
            }
            
            $logID = $api -> addSyncLog($shopID, 'goods', $shopAPI -> _startTime, $shopAPI -> _log.$api -> _log);
        }
    }
}

downloadGoods();
