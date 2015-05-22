<?php
$payments['alipaycmbc'] = '中国民生银行';
class Custom_Model_Payment_Alipaycmbc extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipaycmbc';
        $this -> defaultbank = 'cmbc';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}