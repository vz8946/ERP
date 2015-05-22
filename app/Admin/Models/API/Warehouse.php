<?php
class Admin_Models_API_Warehouse {
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this->_db     = new Admin_Models_DB_Warehouse();
		$this->_member_api = new Admin_Models_API_Member();
		$this->_search_option = array(
			'province' => $this ->_member_api->getChildAreaById(1),	
		);
	}

	/**
     * 获取搜索项
	 *
     * @return   array
     */
	public function getSearchOption()
	{
		return $this->_search_option;
	}

	/**
     * 获取仓库单条信息
     *
     * @param    int
	 *
     * @return   array
     */
	public function get($warehouse_id)
	{
		return $this->_db->get($warehouse_id);
	}
	
	/**
     * 获取仓库总数
     *
     * @param    array
	 *
     * @return   int
     */
	public function getCount($params)
	{
	    return $this->_db->getCount($params);
	}

	/**
     * 获取仓库列表
     *
     * @param    array
	 *
     * @return   int
     */
	public function browse($params, $limit)
	{
		$infos = $this->_db->browse($params, $limit);
		if (empty($infos)) {
			return array();
		}

		return $infos;
	
	}

	/**
     * 添加仓库
     *
     * @param    array
	 *
     * @return   boolean
     */
	public function add($params)
	{
		if (strlen($params['warehouse_sn']) != '9') {
			$this->_error = '仓库编码不正确';
			return false;
		}
		if (empty($params['warehouse_name'])) {
			$this->_error = '仓库名称不能为空';
			return false;
		}

		$warehouse_params = array(
			'warehouse_sn'   => $params['warehouse_sn'],
			'warehouse_name' => $params['warehouse_name'],
			'province_id'    => $params['province_id'],
			'city_id'        => $params['city_id'],
			'district_id'    => $params['district_id'],
			'address'        => $params['address'],
			'created_id'     => $_SESSION['Admin']['adminCertification']['admin_id'],
			'created_by'     => $_SESSION['Admin']['adminCertification']['admin_name'],
			'created_ts'     => date('Y-m-d H:i:s'),
		);

		return $this->_db->add($warehouse_params);
	}

	/**
     * 编辑仓库
     *
	 * @param    int
     * @param    array
	 *
     * @return   boolean
     */
	public function edit($warehouse_id, $params)
	{
		if (intval($warehouse_id) < 1) {
			$this->_error = '仓库ID不正确';
			return false;
		}
		if (strlen($params['warehouse_sn']) != '9') {
			$this->_error = '仓库编码不正确';
			return false;
		}
		if (empty($params['warehouse_name'])) {
			$this->_error = '仓库名称不能为空';
			return false;
		}

		$warehouse_params = array(
			'warehouse_sn'   => $params['warehouse_sn'],
			'warehouse_name' => $params['warehouse_name'],
			'province_id'    => $params['province_id'],
			'city_id'        => $params['city_id'],
			'district_id'    => $params['district_id'],
			'address'        => $params['address'],
		);

		return $this->_db->edit($warehouse_id, $warehouse_params);
	}

	/**
     * 根据parent_id获取地址信息
     *
	 * @param    int
	 *
     * @return   array
     */
	public function getAreaInfosByParentId($parent_id)
	{
		$infos = $this->_db->getAreaInfosByParentId($parent_id);
		if (empty($infos)) {
			return array();
		}

		$area_infos = array();
		foreach ($infos as $val) {
			$area_infos[$val['area_id']] = $val['area_name'];
		}

		return $area_infos;
	}

	public function getError()
	{
		return $this->_error;
	}
}