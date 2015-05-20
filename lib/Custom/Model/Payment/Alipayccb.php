<?php
$payments['alipayccb'] = '中国建设银行 ';
class Custom_Model_Payment_Alipayccb extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipayccb';
        $this -> defaultbank = 'ccb';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}