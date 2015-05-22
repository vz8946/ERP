<?php
$payments['alipaysdb'] = '深圳发展银行';
class Custom_Model_Payment_Alipaysdb extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipaysdb';
        $this -> defaultbank = 'sdb';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}