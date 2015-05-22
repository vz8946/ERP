<?php
$payments['alipayabc'] = '中国农业银行';
class Custom_Model_Payment_Alipayabc extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipayabc';
        $this -> defaultbank = 'abc';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}