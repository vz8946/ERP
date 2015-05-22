<?php
class Custom_Model_Shop_Jiankang extends Custom_Model_Shop_Base
{
	
	/**
     * 构造函数
     *
     * @param   void
     * @return  void
     */
	public function __construct($config)
	{
		parent::__construct($config);
	}
	
	/**
     * 获得销售单打印模板
     *
     * @return  string
     */
	public static function getPrintTpl() {
	    return 'print-orders';
	}
	
}