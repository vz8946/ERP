<?php
class Custom_Model_Shop_Tuan extends Custom_Model_Shop_Base
{
	/**
     * 生成店铺类型特殊字段
     *
     * @param   int     $shopID
     * @return  void
     */
	public function getConfigField() {
	    return array('code' => '团购代码',
	                 'memo' => '简介',
	                );
	}
    
}
