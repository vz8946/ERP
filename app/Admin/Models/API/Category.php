<?php

class Admin_Models_API_Category extends Custom_Model_Dbadv
{
	/**
     * DB对象
     */
	private $_db = null;
	
    /**
     * 错误信息
     */
	private $error;
	
	/**
     * 商品分类及其子类
     */
	private $_cacheCats = null;
	
	/**
     * 商品分类及其父类
     */
	private $_cacheParentCat = null;
	
	/**
     * 商品分类信息
     */
	private $_cacheCat = null;
	
	/**
     * 商品分类下拉列表选项
     */
	private $_catTreeSelectOption = null;
	
	
	/**
	 * 商品分类及其子类
	 */
	private $_cacheProductCats = null;
	
	/**
	 * 商品分类及其父类
	 */
	private $_cacheProductParentCat = null;
	
	/**
	 * 商品分类信息
	 */
	private $_cacheProductCat = null;
	
	/**
	 * 商品分类下拉列表选项
	 */
	private $_productCatTreeSelectOption = null;
	
	/**
     * 构造函数
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Category();
		parent::__construct();
	}
	
	/**
     * 获取数据
     *
     * @param    string    $where
     * @param    string    $fields
     * @param    string    $orderBy
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null)
	{
		return $this -> _db -> fetch($where, $fields, $orderBy);
	}
	/**
	 * 
	 * @param string $where
	 * @param string $fields
	 * @param string $orderBy
	 * @return multitype:
	 */
	public function getProductCat($where = null, $fields = '*', $orderBy = null)
	{
		return $this -> _db -> getProductCat($where, $fields, $orderBy);
	}
	/**
     * 构造分类树
     *
     * @param    array    $deny
     * @param    array    $data
     * @param    int      $parentID
     * @return   array
     */
	public function catTree($where = null, $deny=null,$data=null,$parentID=0)
	{
        static $tree, $step;
        if(!$data){
            $data = $this -> _db -> fetch($where);
        }
        foreach($data as $v){
            if($v['parent_id'] == $parentID){
                $step++;
                $tree[$v['cat_id']] = array('cat_id'=>$v['cat_id'],
                                            'cat_sn'=>$v['cat_sn'],
                                            'cat_name'=>$v['cat_name'],
                                            'parent_id'=>$v['parent_id'],
                                            'cat_path'=>$v['cat_path'],
                                            'cat_sort'=>$v['cat_sort'],
                                            'cat_status'=>$v['cat_status'],
                                            'display'=>$v['display'],
											'mandisplay'=>$v['mandisplay'],
											'womandisplay'=>$v['womandisplay'],
											'ageddisplay'=>$v['ageddisplay'],
											'catalogdisplay'=>$v['catalogdisplay'],
                							'proportion'=>$v['proportion'],
                							'url_alias'=>$v['url_alias'],
                                            'step'=>$step);
                if(is_array($deny)){
                    foreach($deny as $x){
                        if($x == $v['cat_id'] || strstr($v['cat_path'],','.$x.',')){
                            $tree[$v['cat_id']]['deny'] = 1;
                            break;
                        }
                    }
                }
                if($parentID){
                    $tree[$parentID]['leaf'] = 0;
                }
                $this -> catTree($where,$deny,$data,$v['cat_id']);
                $step--;
            }
        }
        if($tree[$parentID] && !isset($tree[$parentID]['leaf'])){
            $tree[$parentID]['leaf'] = 1;
        }
        return $tree;
	}
	
	public function productCatTree($deny=null,$data=null,$parentID=0)
	{
		static $tree, $step;
		if(!$data){
			$data = $this -> _db ->getProductCatALL();
		}		
		foreach($data as $v){
			if($v['parent_id'] == $parentID){
				$step++;
				$tree[$v['cat_id']] = array('cat_id'=>$v['cat_id'],
						'cat_sn'=>$v['cat_sn'],
						'cat_name'=>$v['cat_name'],
						'parent_id'=>$v['parent_id'],
						'cat_path'=>$v['cat_path'],
						'cat_sort'=>$v['cat_sort'],
						'cat_status'=>$v['cat_status'],
						'step'=>$step);
				if(is_array($deny)){
					foreach($deny as $x){
						if($x == $v['cat_id'] || strstr($v['cat_path'],','.$x.',')){
							$tree[$v['cat_id']]['deny'] = 1;
							break;
						}
					}
				}
				if($parentID){
					$tree[$parentID]['leaf'] = 0;
				}
				$this -> productCatTree($deny,$data,$v['cat_id']);
				$step--;
			}
		}
		if($tree[$parentID] && !isset($tree[$parentID]['leaf'])){
			$tree[$parentID]['leaf'] = 1;
		}
		return $tree;
	}
	
	/**
     * 获取路径导航
     *
     * @param    string    $where
     * @param    string    $orderBy
     * @return   array
     */
	public function getPath($where, $orderBy = null)
	{
		$datas = $this -> _db -> fetch('display=1 and '.$where, '*', $orderBy);
		foreach ($datas as $data) {
			$path[]= $data['cat_name'];
		}
		$path && $path = implode(' -&gt; ', $path);
		return $path;
	}
	
	/**
     * 编辑品牌属性
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function bandcat($data, $id = null)
	{
		$result = $this -> _db -> bandcat($data, (int)$id);
	
	}
	
	/**
     * 添加或修改数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function edit($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if ($data['cat_name'] == '') {
			$this -> error = 'no_name';
			return false;
		}
		
		if ($id === null) {
		    $result = $this -> _db -> insert($data);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		} else {
			if(!empty($data['parent_id'])){
				$pcat = $this->getRow('shop_goods_cat',array('cat_id'=>$data['parent_id']));
				$data['cat_path'] = ','.trim($pcat['cat_path'],',').','.$id.',';
			}else{
				$data['cat_path'] = ','.$id.',';
			}	
			$result = $this -> _db -> update($data, (int)$id);
		}
		
		return $result;
	}
	
	public function editProductCat($data, $id = null)
	{
		$filterChain = new Zend_Filter();
		$filterChain -> addFilter(new Zend_Filter_StringTrim())
		-> addFilter(new Zend_Filter_StripTags());
		 
		$data = Custom_Model_Filter::filterArray($data, $filterChain);
		 
		if ($data['cat_name'] == '') {
			$this -> error = 'no_name';
			return false;
		}
	
		if ($id === null) {
			$result = $this -> _db -> insertProduct($data);
			if(!$result){
				$this -> error = 'error';
				return false;
			}
		} else {
			if(!empty($data['parent_id'])){
				$pcat = $this->getRow('shop_product_cat',array('cat_id'=>$data['parent_id']));
				$data['cat_path'] = ','.trim($pcat['cat_path'],',').','.$id.',';
			}else{
				$data['cat_path'] = ','.$id.',';
			}
			$result = $this -> _db -> updateProductCat($data, (int)$id);
		}
	
		return $result;
	}
	/**
     * 删除数据
     *
     * @param    int    $id
     * @return   void
     */
	public function delete($id)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> delete((int)$id);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		    return $result;
		}
	}
	
	/**
     * 获取状态信息
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $status
     * @return   string
     */
	public function ajaxStatus($url, $id, $status)
	{
		switch($status){
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为冻结"><u>正常</u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为正常"><u><font color=red>冻结</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
	
	/**
     * 获取状态信息
     *
     * @param    string    $url
     * @param    int       $id
     * @param    int       $display
     * @return   string
     */
	public function ajaxDisplay($url, $id, $display)
	{
		switch($display){
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0, \'ajax_display\');" title="点击设为隐藏"><u>显示</u></a>';
		   break;
		   case 0:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1, \'ajax_display\');" title="点击设为显示"><u><font color=red>隐藏</font></u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}

	/**
     * 更改状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeStatus($id, $status)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateStatus((int)$id, $status) <= 0) {
				exit('failure');
			}
		}
	}
	
	public function changeProudctStatus($id, $status)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateProductStatus((int)$id, $status) <= 0) {
				exit('failure');
			}
		}
	}
	
	
	/**
     * 更改显示状态
     *
     * @param    int    $id
     * @param    int    $display
     * @return   void
     */
	public function changeDisplay($id, $display)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateDisplay((int)$id, $display) <= 0) {
				exit('failure');
			}
		}
	}
	
	/**
     * 更改显示状态
     *
     * @param    int    $id
     * @param    int    $display
     * @return   void
     */
	public function changeWomanDisplay($id, $display)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateWomanDisplay((int)$id, $display) <= 0) {
				exit('failure');
			}
		}
	}




	/**
     * 更改显示状态
     *
     * @param    int    $id
     * @param    int    $display
     * @return   void
     */
	public function changeManDisplay($id, $display)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateManDisplay((int)$id, $display) <= 0) {
				exit('failure');
			}
		}
	}


	/**
     * 更改显示状态
     *
     * @param    int    $id
     * @param    int    $display
     * @return   void
     */
	public function changeAgedDisplay($id, $display)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateAgedDisplay((int)$id, $display) <= 0) {
				exit('failure');
			}
		}
	}


	/**
     * 更改显示状态
     *
     * @param    int    $id
     * @param    int    $display
     * @return   void
     */
	public function changeCatalogDisplay($id, $display)
	{
		if ((int)$id > 0) {
			if($this -> _db -> updateCatalogDisplay((int)$id, $display) <= 0) {
				exit('failure');
			}
		}
	}


	/**
     * ajax更新数据
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   void
     */
	public function ajaxUpdate($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
		
		if ((int)$id > 0) {
		    if ($this -> _db -> ajaxUpdate((int)$id, $field, $val) <= 0) {
		        exit('failure');
		    }
		    else {
		        if ($field == 'url_alias') {
		            $this -> catAliasCache();
		        }
		    }
		}
	}
	
	public function ajaxProductUpdate($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
		$filterChain -> addFilter(new Zend_Filter_StringTrim())-> addFilter(new Zend_Filter_StripTags());
	
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
	
		if ((int)$id > 0) {
			if ($this -> _db -> ajaxProductUpdate((int)$id, $field, $val) <= 0) {
				exit('failure');
			}			
		}
	}	
	
	
	/**
     * 错误集合
     *
     * @return   void
     */
	public function error()
	{
		$errorMsg = array(
			         'error'=>'操作失败!',
			         'exists'=>'该分类已存在!',
			         'not_exists'=>'该分类不存在!',
			         'forbidden'=>'禁止操作!',
			         'no_name'=>'请填写分类名称!',
			         'no_sn'=>'分类编码错误!',
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	
	/**
     * 取得商品分类结构
     *
     * @return   void
     */
    public function cacheCats($where = null)
    {
	    $data = $this -> _db-> fetch($where,'cat_id,cat_name,parent_id,cat_path,cat_sort');
        foreach ($data as $key => $row)
        {
    	    $this -> _cacheCats[$row["parent_id"]][$row["cat_id"]] = $row;
		    $this -> _cacheParentCat[$row["cat_id"]][$row["parent_id"]] = $row;
		    $this -> _cacheCat[$row["cat_id"]] = $row;
	    }
    }

    /**
     * 取得商品分类结构
     *
     * @return   void
     */
    public function cacheProductCats($where = null)
    {
    	$data = $this -> _db-> getProductCatALL();
    	foreach ($data as $key => $row)
    	{
    		$this -> _cacheProductCats[$row["parent_id"]][$row["cat_id"]] = $row;
    		$this -> _cacheProductParent[$row["cat_id"]][$row["parent_id"]] = $row;
    		$this -> _cacheProductCat[$row["cat_id"]] = $row;
    	}
    }
    
    public function getCacheProductCats($catId)
    {
    	return $this -> _cacheProductCats[$catId];
    }
    
    
    /**
     * 取得商品分类信息
     *
     * @param    string    $catId    分类ID
     * @return   array
     */
    public function getCacheCat($catId)
    {
	    return $this -> _cacheCat[$catId];
    }
    
    /**
     * 取得商品分类所有一级子类
     *
     * @param    string    $catId    分类ID
     * @return   array
     */
    public function getCacheCats($catId)
    {
	    return $this -> _cacheCats[$catId];
    }
    /**
     * 取得商品分类所有一级父类
     *
     * @param    string    $catId    分类ID
     * @return   array
     */
    public function getCacheParentCat($catId)
    {
	    return $this -> _cacheParentCat[$catId];
    }
    
    /**
     * 取得给定商品分类的所有子类(包括自身)
     * 
     * @param   string	$catId	分类ID
     * @return  string	$catId	所有子类,以','分隔
     */
    function getSubCats($catId)
    {
	    $cacheCats = $this -> _cacheCats;
	    if (is_array($cacheCats[$catId])) {
		    foreach($cacheCats[$catId] as $subCatId => $cat)
		    {
			    $catId .= "," . $this -> getSubCats($subCatId);
		    }
	    }
	    return $catId;
    }
    
    /**
     * 取得给定分类的所有父类(包括自身)
     * 
     * @param   string	$catId	分类ID
     * @return  string	$catId	所有父类,以','分隔
     */
    function getParentCats($catId)
    {
	    $cacheParentCat = $this -> _cacheParentCat;
	    if (is_array($cacheParentCat[$catId])) {
    	    foreach ($cacheParentCat[$catId] AS $parentCatId => $cat)
    	    {
			    $catId .= "," . $this -> getParentCats($parentCatId);
		    }
	    }
	    return $catId;
    }
    
    /**
     * 创建商品分类下拉列表
     *
     * @param    array    $data
     * @return   string
     */
	public function buildSelect($data, $onchange = null)
	{

		/**
		 * 初始化设置
		 */
			$this->_cacheCats = null;
			$this->_cacheParentCat = null;
			$this->_cacheCat = null;
			$this->_catTreeSelectOption = null;
		/**
		 * 初始化设置
		 */

		$this -> cacheCats();
		$this -> catTreeSelect();
		$option = array();
		if ($this -> _catTreeSelectOption) {
    		foreach ($this -> _catTreeSelectOption as $key => $value)
    		{
    			$selected = ($data['selected'] == $key) ? "selected" : "";
    		    $option[] = "<option value=\"" . $key . "\" " . $selected . ">" . $value . "</option>";	
    		}
        }
		$id = $data['id'] ? $data['id'] : $data['name'];
		if ($onchange) {
		    $event = "onchange=\"{$onchange}\"";
		}
		$result = "<select name=\"" . $data['name'] . "\" id=\"" . $id . "\" {$event}><option value=\"\">请选择分类</option>" . implode('', $option) . "</select>";
		return $result;
	}
	
	public function buildProductSelect($data, $onchange = null)
	{
	
		/**
		 * 初始化设置
		 */
		$this->_cacheProductCats = null;
		$this->_cacheProductParentCat = null;
		$this->_cacheProductCat = null;
		$this->_productCatTreeSelectOption = null;
		/**
		 * 初始化设置
		 */
		
		$this -> cacheProductCats();
		$this -> productCatTreeSelect();
		$option = array();
		if ($this -> _productCatTreeSelectOption) {
			foreach ($this -> _productCatTreeSelectOption as $key => $value)
			{
				$selected = ($data['selected'] == $key) ? "selected" : "";
				$option[] = "<option value=\"" . $key . "\" " . $selected . ">" . $value . "</option>";
			}
		}
		$id = $data['id'] ? $data['id'] : $data['name'];
		if ($onchange) {
			$event = "onchange=\"{$onchange}\"";
		}
		$result = "<select name=\"" . $data['name'] . "\" id=\"" . $id . "\" {$event}><option value=\"\">请选择分类</option>" . implode('', $option) . "</select>";
		return $result;
	}
	
	/**
     * 创建商品分类复选列表
     *
     * @param    array   $data
     * @return   string
     */
	public function buildCheckbox($data)
	{
		$this -> cacheCats();
		$this -> catTreeSelect();
		$bgcounter = 0;
		foreach ($this -> _catTreeSelectOption as $key => $cat)
		{
			if ($bgcounter++%2 == 0) {
            	$bgColor = '#fff';
        	} else {
            	$bgColor = '#eee';
        	}
			is_array($data['value']) && array_key_exists($key, $data['value']) && $checked = "checked";
		    $option[] = "<ul><li style='text-align:left; background-color:" . $bgColor . "'>" . $cat . "</li><li style='text-align:right;background-color:" . $bgColor . "'><input type='checkbox' name='" . $data['name'] . "[" . $key . "]' value='1' " . $checked . " /></li></ul>";
		    unset($checked);
		}
		$result = "<div class='cat_select'>" . implode("\n", $option) . "</div>";
		return $result;
	}
	
	/**
     * 创建商品分类输入框列表
     *
     * @param    array   $data
     * @return   string
     */
	public function buildText($data)
	{
		$this -> cacheCats();
		$this -> catTreeSelect();
		$bgcounter = 0;
		foreach ($this -> _catTreeSelectOption as $key => $cat)
		{
			if ($bgcounter++%2 == 0) {
            	$bgColor = '#fff';
        	} else {
            	$bgColor = '#eee';
        	}
        	is_array($data['value']) && array_key_exists($key, $data['value']) && $text = $data['value'][$key];
		    $option[] = "<ul><li style='text-align:left; background-color:" . $bgColor . "'>" . $cat . "</li><li style='text-align:right;background-color:" . $bgColor . "'><input type='text' size='10' name='" . $data['name'] . "[" . $key . "]' value='" . $text . "' /></li></ul>";
		    unset($text);
		}
		$result = "<div class='cat_select'>" . implode("\n", $option) . "</div>";
		return $result;
	}
	
	/**
     * 初始化商品分类树
     *
     * @param    int    $startId
     * @return   void
     */
     public function catTreeSelect($startId = null){
     	$startId = ($startId) ? $startId :'0';
     	$data = $this -> getCacheCats($startId);
	    $num = count($data);
	    $index = 1;
	    
	    if ($data) {
    	    foreach ($data as $key => $cat)
    	    {
    	    	if ($index == $num) {
    			    $this -> catExpandBranch($cat["cat_id"], $cat["cat_name"], "0");
    	    	} else {
    			    $this -> catExpandBranch($cat["cat_id"], $cat["cat_name"], "1");
    	    	}
    		    $index++;
    	    }
    	}
    }
    
    
    public function productCatTreeSelect($startId = null){
    	$startId = ($startId) ? $startId :'0';
    	$data = $this -> getCacheProductCats($startId);
    	$num = count($data);
    	$index = 1;
    	 
    	if ($data) {
    		foreach ($data as $key => $cat)
    		{
    			if ($index == $num) {
    				$this -> productCatExpandBranch($cat["cat_id"], $cat["cat_name"], "0");
    			} else {
    				$this -> productCatExpandBranch($cat["cat_id"], $cat["cat_name"], "1");
    			}
    			$index++;
    		}
    	}
    }
    /**
     * 生成指定商品分类的分类树
     *
     * @param    int    $catId
     * @param    int    $classes
     * @param    int    $tab
     * @param    array  $discount
     * @return   void
     */
    private function catExpandBranch($catId, $catName, $tab)
    {
        $tabs = explode(",", $tab);
        $option_item = "";
	    $i=0;
	    
	    for ($i=0; $i < count($tabs); $i++)
	    {
		    if ($i == count($tabs)-1) {
			    if ($tabs[$i] == '0') {
				    $option_item .= "└";
			    } else {
				    $option_item .= "├";
			    }
		    } else {
			    if ($tabs[$i] == '0') {
				    $option_item .= "　";
			    } else {
				    $option_item .= "│";
			    }
		    }
	    }
	    
	    $option_item .= $catName;
	    $this -> _catTreeSelectOption[$catId] = $option_item;
	    $data = $this -> getCacheCats($catId);
	    $num = count($data);
	    $index = 1;
	    
	    if ($num > 0) {
	        foreach ($data as $key => $cat)
		    {
			    if ($index == $num) {
				    $this -> catExpandBranch($cat["cat_id"], $cat["cat_name"], $tab.",0");
			    } else {
				    $this -> catExpandBranch($cat["cat_id"], $cat["cat_name"], $tab.",1");
			    }
			    $index++;
		}
	    }
    }
    
    
    
    private function productCatExpandBranch($catId, $catName, $tab)
    {
    	$tabs = explode(",", $tab);
    	$option_item = "";
    	$i=0;
    	 
    	for ($i=0; $i < count($tabs); $i++)
    	{
    	if ($i == count($tabs)-1) {
    	if ($tabs[$i] == '0') {
    	$option_item .= "└";
    	} else {
    	$option_item .= "├";
    	}
    	} else {
    	if ($tabs[$i] == '0') {
    	$option_item .= "　";
    	} else {
    	$option_item .= "│";
    	}
    	}
    	}
    	 
    	$option_item .= $catName;
    		$this -> _productCatTreeSelectOption[$catId] = $option_item;
    		$data = $this -> getCacheProductCats($catId);
    		$num = count($data);
    		$index = 1;
    	  
    		if ($num > 0) {
    		foreach ($data as $key => $cat)
    		{
    		if ($index == $num) {
    		$this -> productCatExpandBranch($cat["cat_id"], $cat["cat_name"], $tab.",0");
    		} else {
    		$this -> productCatExpandBranch($cat["cat_id"], $cat["cat_name"], $tab.",1");
    		}
    				$index++;
		}
    		}
    }
    
    /**
     * 生成展示分类URL别名缓存
     * 
     **/
	public function catAliasCache() {
	    $data = $this -> _db -> fetch("(url_alias <> '' or url_alias <> null)", 'cat_id,url_alias');
	    
	    $content = '<?php'.chr(13).chr(10);
	    if ( $data ) {
	        for ( $i = 0; $i < count($data); $i++ ) {
	            $content .= '$categoryAlias["'.$data[$i]['url_alias'].'"] = '.$data[$i]['cat_id'].';'.chr(13).chr(10);
	        }
	    }
	    
	    file_put_contents("../data/shop/cache/url_alias/category.php", $content);
    }
    
    /**
     * 保存类别的商品关联
     * @param unknown_type $cat_id
     * @param unknown_type $type
     * @param unknown_type $arr_goods_id
     */
    public function saveRelation($id,$limit_type,$type,$arr_goods_id){
        return $this->_db->saveRelation($id,$limit_type,$type,$arr_goods_id);
    }
    
    /**
     * 取得分类关联数据
     * @param unknown_type $cat_id
     * @param unknown_type $type
     */
    public function getRelation($id,$limit_type,$type){
        
        $list_goods = $this->_db->getRelation($id,$limit_type,$type);
        
        $config_onsale = array('上架','下架');
        foreach ($list_goods as $k=>$v){
            $list_goods[$k]['goods_status'] = $config_onsale[$v['onsale']];
        }
        
        return $list_goods;
    }
    

    /**
     * 商品分类移动
     */
    public function movecat($from_cat_id,$to_cat_id){
        return   $this->_db-> movecat($from_cat_id,$to_cat_id);
    }


}