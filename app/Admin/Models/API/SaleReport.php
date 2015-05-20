<?php

class Admin_Models_API_SaleReport
{
	private $_db = null;
	private $_error;
	private $_search_option;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this->_outstock_db = new Admin_Models_DB_OutStock();
		$this->_instock_db  = new Admin_Models_DB_InStock();
		$this->_auth   = Admin_Models_API_Auth::getInstance()->getAuth();

		$this->_search_option = array(
			'supplier_info' => $this->getSupplierOption(),
		);
	}

	/**
     * 返回搜索参数
	 *
     * @return   string
     */
	public function getSearchOption()
	{
		return $this->_search_option;
	}


	/**
     * 获取信息列表
     *
     * @param    array  
     *
     * @return   array
     */
	 public function browse($params)
	 {
		$outstock_params = array(
			'start_ts'     => strtotime($params['start_ts']),
			'end_ts'       => strtotime($params['end_ts'].' 23:59:59'),
			'is_cancel'    => 0,
			'bill_types'   => array('1', '15'),
			'bill_status'  => '5',
			'product_sn'   => $params['product_sn'],
			'product_name' => $params['product_name'],
			'supplier_id'  => $params['supplier_id'],
		);
		$outstock_infos = $this->_outstock_db->getSaleReportDetailInfosByCondition($outstock_params);

		if (!empty($outstock_infos)) {
			foreach ($outstock_infos as $key => $val) {
				$supplier_ids = explode(',', $val['supplier_id']);
				foreach ($supplier_ids as $supplier_id) {
					$outstock_infos[$key]['supplier_names'][] = $this->_search_option['supplier_info'][$supplier_id];
				}
				$outstock_infos[$key]['supplier_name']      = implode(',', $outstock_infos[$key]['supplier_names']);

                $outstock_infos[$key]['return_number'] = 0;
			}
		}

		$instock_params = array(
			'start_ts'     => strtotime($params['start_ts']),
			'end_ts'       => strtotime($params['end_ts'].' 23:59:59'),
			'is_cancel'    => 0,
			'bill_type'    => '1',
			'bill_status'  => '7',
			'product_sn'   => $params['product_sn'],
			'product_name' => $params['product_name'],
			'supplier_id'  => $params['supplier_id'],
		);
		$instock_infos  = $this->_instock_db->getSaleReportDetailInfosByCondition($instock_params);

		if (!empty($instock_infos)) {
			foreach ($instock_infos as $key => $val) {
				$supplier_ids = explode(',', $val['supplier_id']);
				foreach ($supplier_ids as $supplier_id) {
					$instock_infos[$key]['supplier_names'][] = $this->_search_option['supplier_info'][$supplier_id];
				}
				$instock_infos[$key]['supplier_name']      = implode(',', $instock_infos[$key]['supplier_names']);
			}
		}
		$total_numbers = 0;
		if (count($outstock_infos) < 1) {
			if (count($instock_infos) > 0) {
				foreach ($instock_infos as &$info) {
					$info['number'] = - $info['number'];
				}

				return $instock_infos;
			}
			return array();
		}

		if (count($instock_infos) < 1) {
			return $outstock_infos;
		}


		$outstock_infos = $this->singleGroup($outstock_infos, 'product_id');
		$instock_infos  = $this->singleGroup($instock_infos, 'product_id');

		foreach ($instock_infos as $key => $info) {     
            if (isset($outstock_infos[$key])) {
                foreach ($info['supplier_names'] as $val) {
                    !in_array($val, $outstock_infos[$key]['supplier_names']) && $outstock_infos[$key]['supplier_name'] .= ",". $val;
                }
                $outstock_infos[$key]['number'] -= $info['number'];
                $outstock_infos[$key]['return_number'] += $info['number'];
            } else {
                $outstock_infos[$key] =  $info;
                $outstock_infos[$key]['return_number'] = !$info['number'];
            }
		}


		return $outstock_infos;
	 }

	/**
     * 按KEY组合数据
     *
     * @param    array
	 * @param    string
     *
     * @return   array
     */
	public function singleGroup($array, $key)
	{
		if (count($array) < 1) {
			return array();
		}
		$array2 = array();

		foreach ($array as $val) {
			isset($val[$key]) && $array2[$val[$key]] = $val;
		}

		return $array2;
	}

	/**
	 * 获取供应商选项
	 *
	 *
	 * @return   array
	 **/
	public function getSupplierOption()
	{
		$array = $this->_outstock_db->getSupplier();
		$infos = array();
		foreach ($array as $val) {
			$infos[$val['supplier_id']] = $val['supplier_name'];
		}

		return $infos;
	}
    
	/**
     * 返回错误信息
	 *
     * @return   string
     */
	public function getError()
	{
		return $this->_error;	
	}
	

}