<?php
class Custom_Model_Shop_Credit extends Custom_Model_Shop_Base
{
	/**
     * 生成店铺类型特殊字段
     *
     * @param   int     $shopID
     * @return  void
     */
	public function getConfigField() {
	    return array('memo' => '简介');
	}
    
}
