<?php
require_once "global.php";

class Initial_supplier_product
{
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}

	public function getAllSupplierInfos()
	{
		$sql = "SELECT supplier_id, product_ids FROM `shop_supplier`";

		return $this->_db->fetchAll($sql);
	}

	public function insertSupplierProduct()
	{
		$this->clearAllSupplierProducts();
		$infos = $this->getAllSupplierInfos();
		if (empty($infos)) {
			return array();
		}

		foreach ($infos as $info) {
			if (empty($info['product_ids'])) {
				continue;
			}

			$product_ids = explode(',', $info['product_ids']);

			foreach ($product_ids as $product_id) {
				$product_param = array(
					'supplier_id' => $info['supplier_id'],
					'product_id'  => $product_id,
				);
				$this->insertSupplierInfo($product_param);
			}
		}

	}

	public function insertSupplierInfo($info)
	{
		return (bool) $this->_db->insert('shop_supplier_product', $info);
	}

	public function clearAllSupplierProducts()
	{
		$sql = "TRUNCATE table `shop_supplier_product`";
		$this->_db->execute($sql);
	}

	public function getError()
	{
		return $this->_error;
	}
	
}


$supplier = new Initial_supplier_product();
if (false === $supplier->insertSupplierProduct()) {
	exit($experience->getError());
}

exit('操作成功');