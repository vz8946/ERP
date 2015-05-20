<?php
$payments['alipayshbank'] = '上海银行';
class Custom_Model_Payment_Alipayshbank extends Custom_Model_Payment_Alipay
{
    public function __construct($batchSN = null, $order_type = 2, $amount = 0.00, $business = '')
    {
        $this -> pay_type = 'alipayshbank';
        $this -> defaultbank = 'shbank';
        parent::__construct($batchSN, $order_type, $amount, $business);
    }
}