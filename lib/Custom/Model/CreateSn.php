<?php

class Custom_Model_CreateSn
{
    /**
     * 创建SN
     *
     * @return   void
     */
    public static function createSn()
    {
        $sn = '2'.date('ymdHis', time()).mt_rand(10,99);
        return $sn;
    }
	
    /**
     * 创建SN
     *
     * @return   void
     */
    public static function createMoneyOrderSn()
    {
        $sn = '4'.date('ymdHis', time()).mt_rand(10,99);
        return $sn;
    }
    
    /**
     * 创建试用订单SN
     *
     * @return   void
     */
    public static function createTrySn($try_id)
    {
        $sn = '3'.date('ymdHis', time()).$try_id.mt_rand(10,99);
        return $sn;
    }
    
    /**
     * 创建试用订单SN
     *
     * @return   void
     */
    public static function createRefundSn()
    {
        $sn = '1'.date('ymdHis', time()).mt_rand(1,9);
        return $sn;
    }
    
    /**
     * 创建批量SN
     *
     * @return   void
     */
    public static function createExternalSn()
    {
        $number = '0000'.mt_rand(1,9999);
        $sn = '2'.date('ymdHis', time()).substr($number,-4);
        return $sn;
    }
}