<?php
$payments['alipaycib'] = '兴业银行';
class Custom_Model_Payment_Alipaycib extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipaycib';
        $this -> defaultbank = 'cib';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}