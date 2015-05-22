<?php
$payments['alipayspabank'] = '平安银行';
class Custom_Model_Payment_Alipayspabank extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipayspabank';
        $this -> defaultbank = 'spabank';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}