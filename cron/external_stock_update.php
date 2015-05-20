<?php
require_once "global.php";

function updateStock($shopIDArray)
{
    $api = new Admin_Models_API_Shop();
    $stockAPI = new Admin_Models_API_Stock();
    
    $datas = $api -> get("status = 0 and shop_id in (".implode(',', $shopIDArray).")", 'shop_id,shop_type,config');
    
    foreach ( $datas['list'] as $shop ) {
        $shopID = $shop['shop_id'];
        
        $shopAPI = Custom_Model_Shop_Base::getInstance($shop['shop_type'], unserialize($shop['config']));
        $shopAPI -> initSyncLog();
        $api -> initSyncLog();
        
        $goodsData = $api -> getGoods(array('shop_id' => $shopID));
        foreach ($goodsData['list'] as $goods) {
            if (!$goods['goods_sn'])    continue;
            $productSNArray[] = $goods['goods_sn'];
            $outGoodIDArray[$goods['goods_sn']]['goods_id'] = $goods['shop_goods_id'];
            $outGoodIDArray[$goods['goods_sn']]['sku_id'] = $goods['shop_sku_id'];
        }
        $productInfo = $api -> getProductIDSByGoodsSNArray($productSNArray);
        if ($productInfo) {
            $productIDArray = array_keys($productInfo);
            $stockData = $stockAPI -> getSaleProductStock($productIDArray);
            foreach ($productInfo as $productID => $productSN) {
                $stockInfo[] = array('goods_sn' => $productSN,
                                     'goods_id' => $outGoodIDArray[$productSN]['goods_id'],
                                     'sku_id' => $outGoodIDArray[$productSN]['sku_id'],
                                     'number' => $stockData[$productID]['able_number'] ? $stockData[$productID]['able_number'] : 0,
                                    );
            }
            $shopAPI -> syncStock($stockInfo);
        }
        
        $api -> addSyncLog($shopID, 'stock', $shopAPI -> _startTime, $shopAPI -> _log.$api -> _log);
    }
}

updateStock(array(23, 24));
