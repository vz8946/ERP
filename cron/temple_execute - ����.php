<?php
require_once "global.php";

function insert_consign()
{
    $sql = "INSERT INTO shop_consign_result(warehouse_id, product_id, number, deal_number, created_month, warehouse_product)
SELECT lid as warehouse_id, product_id, sum(number) as number, sum(number) as deal_number,
if(length(month(from_unixtime(add_time))) = 1, concat(year(from_unixtime(add_time)),0, month(from_unixtime(add_time))), concat(year(from_unixtime(add_time)), month(from_unixtime(add_time)))) 
as created_month,
 concat(lid,'_',product_id) as warehouse_product 
FROM shop_outstock o LEFT JOIN shop_outstock_detail d on o.outstock_id = d.outstock_id WHERE is_cancel = 0 and product_id > 0 GROUP BY (if(length(month(from_unixtime(add_time))) = 1, concat(year(from_unixtime(add_time)),0, month(from_unixtime(add_time)), '_', lid,'_',product_id), concat(year(from_unixtime(add_time)), month(from_unixtime(add_time)), '_', lid,'_',product_id)) 

)
";
	$db = Zend_Registry::get('db');
	print_r($db);
}

insert_consign();

INSERT INTO shop_consign_result(warehouse_id, product_id, number, deal_number, created_month, warehouse_product)
SELECT lid as warehouse_id, product_id, sum(number) as number, sum(number) as deal_number,
add_time, concat(lid,'_',product_id) as warehouse_product 
FROM shop_outstock o LEFT JOIN shop_outstock_detail d on o.outstock_id = d.outstock_id WHERE is_cancel = 0 and bill_type = 15 and product_id > 0 GROUP BY (concat(year(from_unixtime(add_time)), month(from_unixtime(add_time)), '_', lid,'_',product_id) 

);

INSERT INTO shop_consign_result_log( consign_result_id, product_id, number, created_id, created_by,created_ts) SELECT consign_result_id,product_id,number, 11 AS created_id, 'system' as created_by, FROM_UNIXTIME(created_month) as created_ts FROM shop_consign_result;
UPDATE shop_consign_result SET created_month = if(length(month(from_unixtime(created_month))) = 1, concat(year(from_unixtime(created_month)),0, month(from_unixtime(created_month))), concat(year(from_unixtime(created_month)), month(from_unixtime(created_month))));
ALTER TABLE `shop_consign_result`
	CHANGE COLUMN `created_month` `created_month` INT(6) NOT NULL DEFAULT '0' COMMENT '年月' AFTER `deal_number`;
