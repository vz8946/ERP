<?php
$payments['alipayspdb'] = '浦发银行';
class Custom_Model_Payment_alipayspdb extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipayspdb';
        $this -> defaultbank = 'spdb';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}