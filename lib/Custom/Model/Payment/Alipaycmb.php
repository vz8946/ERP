<?php
$payments['alipaycmb'] = '中国招商银行';
class Custom_Model_Payment_Alipaycmb extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipaycmb';
        $this -> defaultbank = 'cmb';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}