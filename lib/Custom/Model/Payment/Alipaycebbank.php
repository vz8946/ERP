<?php
$payments['alipaycebbank'] = '光大银行';
class Custom_Model_Payment_Alipaycebbank extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipaycebbank';
        $this -> defaultbank = 'cebbank';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}