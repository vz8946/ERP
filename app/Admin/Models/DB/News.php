<?php

class Admin_Models_DB_News
{
	private $_db = null;

	private $_table = array(
                           'cat'=>'shop_news_class',
                           'article'=>'shop_news',
                           'data'=>'shop_data_dictionary',
                           'brand'=>'shop_brand',
                           'category'=>'shop_goods_cat',
                           'adv'=>'shop_advertising',
                           'data'=>'shop_data_dictionary',
                           'link'=>'shop_link',
                           'goods'=>'shop_goods'
    );
	public function __construct()
	{
		$this->_db = Zend_Registry::get('db');
        $this->_pageSize = Zend_Registry::get('config')->view->page_size;
    }

	/**
     * 取所有文章分类
     *
     * @return   array
     */
    public function getAllCat()
	{
        return $this->_db->fetchAll('select * from ' .$this->_table['cat'].$where_str.' order by pid,level');
	}

	/**
     * 添加文章分类
     *
     * @param    array $data
     * @return   int
     */
    public function addCat($data)
    {
        if($data['parent_id']){
            $parentCat = $this->getCatByID($data['parent_id']);
            $parentArea['cat_path'] && $tmp = explode(',',substr($parentCat['cat_path'],1,-1));
            $tmp[] = $parentCat['cat_id'];
            $data['cat_path'] = ','.implode(',', $tmp).',';
        }

        $this->_db->insert($this->_table['cat'], $data);
		return $this->_db->lastInsertId();
    }
    /**
     * 添加数据字典
     *
     * @param    array $data
     * @return   int
     */
    public function addData($data)
    {
        $this->_db->insert($this->_table['data'], $data);
		return $this->_db->lastInsertId();
    }
    /**
     * 添加友情链接
     *
     * @param    array $data
     * @return   int
     */
    public function addLink($data)
    {
        $this->_db->insert($this->_table['link'], $data);
		return $this->_db->lastInsertId();
    }
	/**
     * 编辑文章分类
     *
     * @param    string $where
     * @return   int
     */
    public function editCat($data){
        $catID = $data['id'];
        unset($data['id']);
        $where = $this->_db->quoteInto('id = ?', $catID);
		if ($catID > 0) {
		    return $this->_db->update($this->_table['cat'], $data, $where);
		}
    }
	/**
     * 取某ID的文章分类
     *
     * @param    int $catID
     * @return   array
     */
    public function getCatByID($catID){
        return $this->_db->fetchRow('select * from ' .$this->_table['cat'].' where id='.$catID);
    }
	/**
     * 取父节点下的所有文章分类
     *
     * @param    string $catPath
     * @return   array
     */
    public function getChildCats($catPath){
        return $this->_db->fetchAll('select * from '.$this->_table['cat'].' where cat_path like"%'.$catPath.'%"');
    }
	/**
     * 统计某ID文章分类下的文章数量
     *
     * @param    int $catID
     * @return   int
     */
    public function countNewsByCatID($catID){
        return $this->_db->fetchOne('select count(*) from '.$this->_table['article'].' where ncId='.$catID);
    }
	/**
     * 删除文章分类
     *
     * @param    int $catID
     * @return   int
     */

    public function delCat($catID){
		$where = $this->_db->quoteInto('id = ?', $catID);
		if ($catID > 0) {
		    return $this->_db->delete($this->_table['cat'], $where);
		}
    }
	/**
     * 编辑文章分类列表可以编辑项目
     *
     * @param    int $id
     * @param    string $field
     * @param    string $val
     * @return   int
     */
	public function ajaxupdatecatAction($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this->_db->quoteInto('id = ?', $id);
		if ($id > 0) {
		    return $this->_db->update($this->_table['cat'], $set, $where);
		}
	}
	/**
     * 添加文章
     *
     * @param    array $data
     * @return   int
     */
     public function add($data){
        $this->_db->insert($this->_table['article'], $data);
		return $this->_db->lastInsertId();
    }
    /**
     * 添加广告位
     *
     * @param    array $data
     * @return   int
     */
     public function addAdv($data){
        $this->_db->insert($this->_table['adv'], $data);
		return $this->_db->lastInsertId();
    }
	/**
     * 取文章数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getCount($where=null){
		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'title') {
                            $whereSql .= " AND $key like '%$value%'";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		$sql = 'SELECT count(*) as count FROM `'.$this->_table['article'].'`'.$whereSql;
		return $this->_db->fetchOne($sql);
    }
    /**
     * 取数据字典数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getDataCount($where=null){
		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'name') {
                            $whereSql .= " AND $key like '%$value%'";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		$sql = 'SELECT count(*) as count FROM `'.$this->_table['data'].'`'.$whereSql;
		return $this->_db->fetchOne($sql);
    }
    /**
     * 取广告位数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getAdvCount($where=null){
		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'name') {
                            $whereSql .= " AND $key like '%$value%'";
                        } elseif($key == 'position'){
                        	$whereSql .= " AND MATCH(".$key.") AGAINST('".$value."' IN BOOLEAN MODE)";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		$sql = 'SELECT count(*) as count FROM `'.$this->_table['adv'].'`'.$whereSql;
		return $this->_db->fetchOne($sql);
    }
     /**
     * 取友情链接数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getLinkCount($where=null){
		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'text') {
                            $whereSql .= " AND $key like '%$value%'";
                        } elseif($key == 'position'){
                        	$whereSql .= " AND MATCH(".$key.") AGAINST('".$value."' IN BOOLEAN MODE)";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		$sql = 'SELECT count(*) as count FROM `'.$this->_table['link'].'`'.$whereSql;
		return $this->_db->fetchOne($sql);
    }
	/**
     * 取文章列表
     *
     * @param    string $where
     * @param    string $fields
     * @param    string $orderBy
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function get($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = ' LIMIT '.$pageSize.' OFFSET '.$offset;
		}

		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'title') {
                            $whereSql .= " AND $key like '%$value%'";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY id DESC";
		}
		$sql = 'SELECT '.$fields.' FROM `'.$this->_table['article'].'` '.$whereSql.' '.$orderBy.' '.$limit;
		return $this->_db->fetchAll($sql);
	}
	/**
	 * 取数据字典列表
	 */
	public function getData($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = ' LIMIT '.$pageSize.' OFFSET '.$offset;
		}

		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'name') {
                            $whereSql .= " AND $key like '%$value%'";
                        }else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY id desc";
		}
		$sql = 'SELECT '.$fields.' FROM `'.$this->_table['data'].'` '.$whereSql.' '.$orderBy.' '.$limit;
		return $this->_db->fetchAll($sql);
	}
	/**
	 * 取广告位列表
	 */
	public function getAdv($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = ' LIMIT '.$pageSize.' OFFSET '.$offset;
		}

		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'name') {
                            $whereSql .= " AND $key like '%$value%'";
                        }elseif($key == 'position'){
                        	$whereSql .= " AND MATCH(".$key.") AGAINST('".$value."' IN BOOLEAN MODE)";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY id desc";
		}
		$sql = 'SELECT '.$fields.' FROM `'.$this->_table['adv'].'` '.$whereSql.' '.$orderBy.' '.$limit;
		return $this->_db->fetchAll($sql);
	}

	/**
	 * 取友情链接列表
	 */
	public function getLink($where = null, $fields = '*', $orderBy = null, $page = null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this->_pageSize;

		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = ' LIMIT '.$pageSize.' OFFSET '.$offset;
		}

		if ($where != null) {
			$whereSql = " WHERE 1=1";
			if (is_string($where)) {
				$whereSql .= " $where";
			} elseif (is_array($where)) {
				foreach ($where as $key => $value)
			    {
				    if ($value) {
                        if ($key == 'text') {
                            $whereSql .= " AND $key like '%$value%'";
                        }elseif($key == 'position'){
                        	$whereSql .= " AND MATCH(".$key.") AGAINST('".$value."' IN BOOLEAN MODE)";
                        } else {
                            $whereSql .= " AND $key='$value'";
                        }
                    }
			    }
			}
        }
		if ($orderBy != null){
			$orderBy = " ORDER BY $orderBy";
		}else{
			$orderBy = " ORDER BY id desc";
		}
		$sql = 'SELECT '.$fields.' FROM `'.$this->_table['link'].'` '.$whereSql.' '.$orderBy.' '.$limit;
		return $this->_db->fetchAll($sql);
	}
	/**
     * 编辑文章列表可以编辑项目
     *
     * @param    int $id
     * @param    string $field
     * @param    string $val
     * @return   int
     */
	public function ajaxupdatearticle($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this->_db->quoteInto('article_id = ?', $id);
		if ($id > 0) {
		    return $this->_db->update($this->_table['article'], $set, $where);
		}
	}
	/**
     * 编辑数据字典列表可以编辑项目
     *
     * @param    int $id
     * @param    string $field
     * @param    string $val
     * @return   int
     */
	public function ajaxupdatedata($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this->_db->quoteInto('id = ?', $id);
		if ($id > 0) {
		    return $this->_db->update($this->_table['data'], $set, $where);
		}
	}
	/**
     * 删除文章
     *
     * @param    int $articleID
     * @return   int
     */

    public function del($articleID){
		$where = $this->_db->quoteInto('id = ?', $articleID);
		if ($articleID > 0) {
		    return $this->_db->delete($this->_table['article'], $where);
		}
    }
    /**
     * 删除数据字典
     *
     * @param    int $articleID
     * @return   int
     */

    public function deldata($articleID){
		$where = $this->_db->quoteInto('id = ?', $articleID);
		if ($articleID > 0) {
		    return $this->_db->delete($this->_table['data'], $where);
		}
    }
    /**
     * 删除广告位
     *
     * @param    int $articleID
     * @return   int
     */

    public function delAdv($articleID){
		$where = $this->_db->quoteInto('id = ?', $articleID);
		if ($articleID > 0) {
		    return $this->_db->delete($this->_table['adv'], $where);
		}
    }
    /**
     * 删除友情链接
     *
     * @param    int $articleID
     * @return   int
     */

    public function delLink($articleID){
		$where = $this->_db->quoteInto('id = ?', $articleID);
		if ($articleID > 0) {
		    return $this->_db->delete($this->_table['link'], $where);
		}
    }
	/**
     * 取某ID的文章
     *
     * @param    int $articleID
     * @return   array
     */
    public function getArticleByID($articleID){
        return $this->_db->fetchRow('select * from ' .$this->_table['article'].' where id='.$articleID);
    }
    /**
     * 取某ID的广告位
     *
     * @param    int $articleID
     * @return   array
     */
    public function getAdvByID($articleID){
        return $this->_db->fetchRow('select * from ' .$this->_table['adv'].' where id='.$articleID);
    }
    /**
     * 取某ID的友情链接
     *
     * @param    int $articleID
     * @return   array
     */
    public function getLinkByID($articleID){
        return $this->_db->fetchRow('select * from ' .$this->_table['link'].' where id='.$articleID);
    }
	/**
     * 编辑文章
     *
     * @param    string $where
     * @return   int
     */
    public function edit($data){
        $articleID = $data['id'];
        unset($data['id']);
        $where = $this->_db->quoteInto('id = ?', $articleID);
		if ($articleID > 0) {
		    return $this->_db->update($this->_table['article'], $data, $where);
		}
    }
	/**
     * 编辑广告位
     *
     * @param    string $where
     * @return   int
     */
    public function editAdv($data){
        $articleID = $data['id'];
        unset($data['id']);
        $where = $this->_db->quoteInto('id = ?', $articleID);
		if ($articleID > 0) {
		    return $this->_db->update($this->_table['adv'], $data, $where);
		}
    }
    /**
     * 编辑友情链接
     *
     * @param    string $where
     * @return   int
     */
    public function editLink($data){
        $articleID = $data['id'];
        unset($data['id']);
        $where = $this->_db->quoteInto('id = ?', $articleID);
		if ($articleID > 0) {
		    return $this->_db->update($this->_table['link'], $data, $where);
		}
    }
	/**
     * 添加新标签
     *
     * @param    string    $where
     * @return   array
     */
	public function addTag($data)
	{
			$row = array (
						  'tag' => $data['tag'],
						  'title' => $data['title']
						  );
			$this -> _db -> insert($this->_table['tag'], $row);
			$lastInsertId = $this -> _db -> lastInsertId();
			return $lastInsertId;
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getAllTag($where = null,$page=null, $pageSize = null)
	{
		$pageSize = ((int)$pageSize > 0) ? (int)$pageSize : $this -> _pageSize;
		if ($page != null) {
		    $offset = ($page-1)*$pageSize;
		    $limit = "LIMIT $pageSize OFFSET $offset";
		}
		if ($where != null) $where = " WHERE 1 $where";
        return array(
            'list'=>$this -> _db -> fetchAll('SELECT * FROM '.$this->_table['tag']. "$where order by tag_id $limit" ),
            'total'=> $this -> _db -> fetchOne('SELECT count(*) as count FROM '.$this->_table['tag'].$where )
            );
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where = null)
	{
		if ($where != null) $where = " WHERE $where";
		$sql = "SELECT * FROM ".$this->_table['tag'] . $where." order by tag_id ";
		return $this -> _db -> fetchAll($sql);
	}

	/**
     * 更新标签
     *
     * @param    int    $tag_id
     * @param    int    $val
     * @return   void
     */
	public function updateTag($tag_id, $val)
	{
		if ($tag_id > 0) {
			$set = array ('config' => $val);
			$where = $this -> _db -> quoteInto('tag_id = ?', $tag_id);
		    $this -> _db -> update($this->_table['tag'], $set, $where);
		    return true;
		}
	}

	/**
     * 编辑文章分类列表可以编辑项目
     *
     * @param    int $id
     * @param    string $field
     * @param    string $val
     * @return   int
     */
	public function ajaxupdatetag($id, $field, $val)
	{
		$set = array ($field => $val);
		$where = $this->_db->quoteInto('tag_id = ?', $id);
		if ($id > 0) {
		    return $this->_db->update($this->_table['tag'], $set, $where);
		}
	}

	/**
     * 更新关联商品
     *
     * @param    int    $tag_id
     * @param    int    $val
     * @return   void
     */
	public function updatelinkGoods($article_id, $val)
	{
		if ($article_id > 0) {
			$set = array ('goods_ids' => $val);
			$where = $this -> _db -> quoteInto('article_id = ?', $article_id);
		    $this -> _db -> update($this->_table['article'], $set, $where);
		    return true;
		}
	}




	/**
     * 更新关联商品
     *
     * @param    int    $tag_id
     * @param    int    $val
     * @return   void
     */
	public function updateLinkCat($cat_id, $val,$field ='goods_config')
	{
		if ($cat_id > 0) {
			$set = array ($field => $val);
			$where = $this -> _db -> quoteInto('cat_id = ?', $cat_id);
		    $this -> _db -> update($this->_table['cat'], $set, $where);
		    return true;
		}
	}
	/**
     *获得数据字典数据
     *
     * @param    String    $where
     * @return   list
     */
	public function getDataDictionary($where=null){
		$sql = "SELECT * FROM ".$this->_table['data'] . $where." order by id";
		return $this -> _db -> fetchAll($sql);
	}
	/**
     *获得资讯分类数据
     *
     * @param    String    $where
     * @return   list
     */
	 public function getNewsClass($where=null){
		$sql = "SELECT * FROM ".$this->_table['cat'] . $where." order by id";
		return $this -> _db -> fetchAll($sql);
	}
	/**
     *获得品牌数据
     *
     * @param    String    $where
     * @return   list
     */
	 public function getBrand($where=null){
		$sql = "SELECT * FROM ".$this->_table['brand'] . $where." order by brand_id";
		return $this -> _db -> fetchAll($sql);
	}
	/**
     *获得分类数据
     *
     * @param    String    $where
     * @return   list
     */
	 public function getCatgory($where=null){
		$sql = "SELECT * FROM ".$this->_table['category'] . $where." order by cat_id";
		return $this -> _db -> fetchAll($sql);
	}
	/**
	 * 获得品牌分类
	 * 无查询条件
	 */
	 public function getBrandCatgory(){
	 	$sql = "select CONCAT(CONCAT('B',b.brand_id),CONCAT('C',c.cat_id)) as id,CONCAT(b.brand_name,c.cat_name) as name from ".$this->_table['brand']." b,".$this->_table['category']." c  limit 0,50";
	 	return $this -> _db -> fetchAll($sql);
	 }
	 /**
	 * 获得品牌分类
	 * 有查询条件
	 */
	 public function getBrandCatgoryWhere($where){
	 	$sql = "select CONCAT(CONCAT('B',b.brand_id),CONCAT('C',c.cat_id)) as id,CONCAT(b.brand_name,c.cat_name) as name from ".$this->_table['brand']." b,".$this->_table['category']." c ".$where." limit 0,50";
	 	return $this -> _db -> fetchAll($sql);
	 }
	/**
     *获得产品数据
     *
     * @param    String    $where
     * @return   list
     */
	 public function getGoods($where=null){
		$sql = "SELECT goods_id,goods_name FROM ".$this->_table['goods'] . $where." order by goods_id limit 0,50";
		return $this -> _db -> fetchAll($sql);
	}



}