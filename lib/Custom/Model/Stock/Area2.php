<?php

class Custom_Model_Stock_Area2 extends Custom_Model_Stock_Base
{
	public function __construct($logicArea, $stockConfig)
	{
		parent::__construct($logicArea, $stockConfig);
	}
	
	/**
     * 获得入库类型
     *
     * @param   int     $type
     * @return  string/array
     */
	public function getConfigInType($type = null)
	{
	    $keyArray = array(13);
	    
	    return parent::getConfigInType($type, $keyArray);
	}
	
	/**
     * 获得出库类型
     *
     * @param   int     $type
     * @return  string/array
     */
	public function getConfigOutType($type = null)
	{
	    $keyArray = array(13);
	    
	    return parent::getConfigOutType($type, $keyArray);
	}
	
	/**
     * 获得可手工新增入库类型
     *
     * @return  array
     */
	public function getConfigAddInType($type = null)
	{
	    
	}
	
	/**
     * 获得可手工新增出库类型
     *
     * @param   int     $type
     * @return  array
     */
	public function getConfigAddOutType($type = null) 
	{
	    
	}
	
}
?>
