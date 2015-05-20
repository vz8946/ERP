<?php

class Custom_Model_Stock_AreaDistribution extends Custom_Model_Stock_Base
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
	    $keyArray = array(5,6,14,15,19);
        
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
	    $keyArray = array(2,11,14,15,16,19);
	    
	    return parent::getConfigOutType($type, $keyArray);
	}
	
	/**
     * 获得可手工新增入库类型
     *
     * @return  array
     */
	public function getConfigAddInType($type = null)
	{
	    $keyArray = array(5,14);

	    return parent::getConfigInType(null, $keyArray);
	}
	
	/**
     * 获得可手工新增出库类型
     *
     * @param   int     $type
     * @return  array
     */
	public function getConfigAddOutType($type = null) 
	{
	    $keyArray = array(14,16);

	    return parent::getConfigOutType(null, $keyArray);
	}
	
	/**
     * 获得库存状态
     *
     * @param   int     $logicStatus
     * @return  string/array
     */
	public function getConfigLogicStatus($logicStatus = null)
	{
	    return parent::getConfigLogicStatus($logicStatus, array(1,2,3,4));
	}
	
}
?>
