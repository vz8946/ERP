<?php
$payments['alipaygdb'] = '广东发展银行';
class Custom_Model_Payment_Alipaygdb extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipaygdb';
        $this -> defaultbank = 'gdb';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}