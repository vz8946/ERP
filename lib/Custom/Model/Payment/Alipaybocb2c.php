<?php
$payments['alipaybocb2c'] = '中国银行';
class Custom_Model_Payment_Alipaybocb2c extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipaybocb2c';
        $this -> defaultbank = 'bocb2c';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}