<?php
class Admin_Models_API_News extends Custom_Model_Dbadv
{
	private $upPath = 'upload/news/';
	private $upAdvPath = 'upload/adv/';

	public function __construct()
	{
		parent::__construct();
		$this->_db = new Admin_Models_DB_News();
		$this->_auth = Admin_Models_API_Auth :: getInstance()->getAuth();
		
	}
	/**
     * 构造分类树.
     *
     * @param    array    $deny
     * @param    array    $data
     * @param    int      $parentID
     * @return   array
     */
	public function catTree($deny=null,$data=null,$parentID=0)
	{
        static $tree,$step;
        if(!$data){
            $data = $this->_db->getAllCat();
        }
        foreach($data as $v){
            if($v['pid'] == $parentID){
                $step++;
                $tree[$v['id']] = array('cat_id'=>$v['id'],
                                            'cat_name'=>$v['name'],
                                            'parent_id'=>$v['parent_id'],
											'status'=>$v['status'],
                                            'sort'=>$v['level'],
                                            'asName'=>$v['asName'],
                                            'step'=>$step);
                if($parentID){
                    $tree[$parentID]['leaf'] = 0;
                }
                $this->catTree($deny,$data,$v['id']);
                $step--;
            }
        }
        if($tree[$parentID] && !isset($tree[$parentID]['leaf'])){
            $tree[$parentID]['leaf'] = 1;
            $tree[$parentID]['num'] = $this->_db->countNewsByCatID($parentID);
        }
        return $tree;
	}
	/**
     * 取某ID的文章分类信息
     *
     * @param    int    $catID
     * @return   array
     */
    public function getCatByID($catID){
        return $this->_db->getCatByID($catID);
    }
	/**
     * 添加文章分类信息
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function addCat($data, &$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if(is_null($data['name'])){
            $error = 'noName';
            return false;
        }

        if($this->_db->addCat($data)){
            return true;
        }else{
            $error = 'addFail';
            return false;
        }
    }
    /**
     * 添加数据字典信息
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function addData($data, &$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if(is_null($data['name'])){
            $error = 'noName';
            return false;
        }

        if($this->_db->addData($data)){
            return true;
        }else{
            $error = 'addFail';
            return false;
        }
    }
    /**
     * 添加友情链接信息
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function addLink($data, &$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if(is_null($data['text'])){
            $error = 'noName';
            return false;
        }

        if($this->_db->addLink($data)){
            return true;
        }else{
            $error = 'addFail';
            return false;
        }
    }
	/**
     * 编辑文章分类信息
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function editCat($data,&$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if(is_null($data['name'])){
            $error = 'noName';
            return false;
        }
        if($ct=$this->_db->editCat($data) || $ct==0){
            return true;
        }else{
            $error = 'editFail';
            return false;
        }
    }
	/**
     * 删除文章分类信息
     *
     * @param    int       $catID
     * @param    string    $error
     * @return   bool
     */
    public function delCat($catID,&$error){
        $catID = intval($catID);
        $cat = $this->_db->getCatByID($catID);
        if($this->_db->delCat($catID)){
            return true;
        }else{
            $error = 'delFail';
            return false;
        }
    }

	/**
     * 编辑分类列表可以编辑项目
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   string
     */
	public function ajaxupdatecatAction($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());

		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);

		if ((int)$id > 0) {
		    $result = $this->_db->ajaxupdatecatAction((int)$id, $field, $val);
		    if (is_numeric($result) && $result > 0) {
		        return 'ajaxUpdateSucess';
		    } else {
			    return 'error';
		    }
		}
	}
	/**
     * 添加文章
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function add($data, &$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);

        if(is_null($data['title'])){
            $error = 'noName';
            return false;
        }elseif(is_null($data['content'])){
            $error = 'noContent';
            return false;
        }elseif(is_null($data['newsClassNameAndId'])){
            $error = 'noCatID';
            return false;
        }
		$newsClassNameAndId=$data['newsClassNameAndId'];
		$arr=explode('_',$newsClassNameAndId);
		unset($data['newsClassNameAndId']);
		$data['ncId']=$arr[1];
		$data['ncName']=$arr[0];
		$data['asName']=$arr[2];
        $data['content'] = $data['content'];
        $data['lauthor'] = $this->_auth['admin_name'];
        if($insertID = $this->_db->add($data)){
        	//上传图片
        	if(is_file($_FILES['img_url']['tmp_name'])) {
				$upload = new Custom_Model_Upload('img_url', $this -> upPath);
				$upload -> up(false);
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> upPath.$upload->uploadedfiles[0]['filepath'];
				$this -> _db -> edit(array('ztpic'=>$img_url, 'id'=>$insertID));
	    	}
	    	//
            return true;
        }else{
            $error = 'addFail';
            return false;
        }
    }
    /**
     * 添加广告位
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function addAdv($data, &$error){

	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);

        if(is_null($data['name'])){
            $error = 'noName';
            return false;
        }
        if($insertID = $this->_db->addAdv($data)){
        	//上传图片
        	if(is_file($_FILES['img_url']['tmp_name'])) {
				$upload = new Custom_Model_Upload('img_url', $this -> upAdvPath);
				$upload -> up(false);
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> upAdvPath.$upload->uploadedfiles[0]['filepath'];
				$this -> _db ->editAdv(array('imgUrl'=>$img_url, 'id'=>$insertID));
	    	}
	    	//
            return true;
        }else{
            $error = 'addFail';
            return false;
        }
    }
	/**
     * 取文章数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getCount($where=null){
        return $this->_db->getCount($where);
    }
    /**
     * 取数据字典数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getDataCount($where=null){
        return $this->_db->getDataCount($where);
    }
    /**
     * 取广告位数量
     */
    public function getAdvCount($where=null){
        return $this->_db->getAdvCount($where);
    }
     /**
     * 取友情链接数量
     */
    public function getLinkCount($where=null){
        return $this->_db->getLinkCount($where);
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
	public function get($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		$datas= $this->_db->get($where, $fields, $orderBy, $page, $pageSize);
		foreach ($datas as $num => $data)
        {
	        $datas[$num]['ginfo'] = Zend_Json::encode($datas[$num]);
        }
        return $datas;
	}
	/**
     * 取数据字典列表
     *
     * @param    string $where
     * @param    string $fields
     * @param    string $orderBy
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getData($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		$datas= $this->_db->getData($where, $fields, $orderBy, $page, $pageSize);
		foreach ($datas as $num => $data)
        {
	        $datas[$num]['ginfo'] = Zend_Json::encode($datas[$num]);
        }
        return $datas;
	}
	/**
	 * 取广告位列表
	 */
	public function getAdv($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		$datas= $this->_db->getAdv($where, $fields, $orderBy, $page, $pageSize);
		foreach ($datas as $num => $data)
        {
	        $datas[$num]['ginfo'] = Zend_Json::encode($datas[$num]);
        }
        return $datas;
	}
	/**
	 * 取友情链接列表
	 */
	public function getLink($where = null, $fields = '*', $orderBy = null, $page=null, $pageSize = null)
	{
		$datas= $this->_db->getLink($where, $fields, $orderBy, $page, $pageSize);
		foreach ($datas as $num => $data)
        {
	        $datas[$num]['ginfo'] = Zend_Json::encode($datas[$num]);
        }
        return $datas;
	}
	/**
     * 编辑文章列表可以编辑项目
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   string
     */
	public function ajaxupdatearticle($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());

		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);

		if ((int)$id > 0) {
		    $result = $this->_db->ajaxupdatearticle((int)$id, $field, $val);
		    if (is_numeric($result) && $result > 0) {
		        return 'ajaxUpdateSucess';
		    } else {
			    return 'error';
		    }
		}
	}
	/**
     * 编辑数据字典列表可以编辑项目
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   string
     */
	public function ajaxupdatedata($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());

		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);

		if ((int)$id > 0) {
		    $result = $this->_db->ajaxupdatedata((int)$id, $field, $val);
		    if (is_numeric($result) && $result > 0) {
		        return 'ajaxUpdateSucess';
		    } else {
			    return 'error';
		    }
		}
	}
	/**
     * 删除文章
     *
     * @param    int       $articleID
     * @param    string    $error
     * @return   bool
     */
    public function del($articleID,&$error){
        if($this->_db->del($articleID)){
            return true;
        }else{
            $error = 'delFail';
            return false;
        }
    }
    /**
     * 删除数据字典
     *
     * @param    int       $articleID
     * @param    string    $error
     * @return   bool
     */
    public function deldata($articleID,&$error){
        if($this->_db->deldata($articleID)){
            return true;
        }else{
            $error = 'delFail';
            return false;
        }
    }
    /**
     * 删除广告位
     *
     * @param    int       $articleID
     * @param    string    $error
     * @return   bool
     */
    public function delAdv($articleID,&$error){
        if($this->_db->delAdv($articleID)){
            return true;
        }else{
            $error = 'delFail';
            return false;
        }
    }
     /**
     * 删除友情链接
     *
     * @param    int       $articleID
     * @param    string    $error
     * @return   bool
     */
    public function delLink($articleID,&$error){
        if($this->_db->delLink($articleID)){
            return true;
        }else{
            $error = 'delFail';
            return false;
        }
    }
	/**
     * 取某ID的文章信息
     *
     * @param    int    $articleID
     * @return   array
     */
    public function getArticleByID($articleID){
        $article =  $this->_db->getArticleByID($articleID);
	    $article['content']	= stripslashes($article['content']);
		return	$article;

    }
    /**
     * 取某ID的广告位信息
     *
     * @param    int    $articleID
     * @return   array
     */
    public function getAdvByID($aid){
        return $this->_db->getAdvByID($aid);
    }
     /**
     * 取某ID的友情链接信息
     *
     * @param    int    $articleID
     * @return   array
     */
    public function getLinkByID($aid){
        return $this->_db->getLinkByID($aid);
    }

	/**
     * 编辑文章信息
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function edit($data,&$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if(is_null($data['title'])){
            $error = 'noName';
            return false;
        }elseif(is_null($data['content'])){
            $error = 'noContent';
            return false;
        }elseif(is_null($data['newsClassNameAndId'])){
            $error = 'noCatID';
            return false;
        }
		$newsClassNameAndId=$data['newsClassNameAndId'];
		$arr=explode('_',$newsClassNameAndId);
		unset($data['newsClassNameAndId']);
		$data['ncId']=$arr[1];
		$data['ncName']=$arr[0];
		$data['asName']=$arr[2];
		$data['updateDate']=date("Y-m-d H:i:s");
        $data['content'] = $data['content'];
        if($ct=$this->_db->edit($data) || $ct==0){
        	//上传图片
        	if(is_file($_FILES['img_url']['tmp_name'])) {
				$upload = new Custom_Model_Upload('img_url', $this -> upPath);
				$upload -> up(false);
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> upPath.$upload->uploadedfiles[0]['filepath'];
				$this -> _db -> edit(array('ztpic'=>$img_url, 'id'=>$data['id']));
	    	}
            return true;
        }else{
            $error = 'editFail';
            return false;
        }
    }

    /**
     * 编辑广告位信息
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function editAdvContent($data,&$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        if(is_null($data['name'])){
            $error = 'noName';
            return false;
        }
		//$data['updateDate']=date("Y-m-d H:i:s");
        //$data['content'] = stripslashes($data['content']);
        if($ct=$this->_db->editAdv($data) || $ct==0){
        	//上传图片
        	if(is_file($_FILES['img_url']['tmp_name'])) {
				$upload = new Custom_Model_Upload('img_url', $this -> upAdvPath);
				$upload -> up(false);
				if($upload -> error()){
					$this -> error = $upload -> error();
					return 'imgErr';
				}
				$img_url = $this -> upAdvPath.$upload->uploadedfiles[0]['filepath'];
				$this -> _db -> editAdv(array('imgUrl'=>$img_url, 'id'=>$data['id']));
	    	}
            return true;
        }else{
            $error = 'editFail';
            return false;
        }
    }

	/**
     * 获取标签列表
     *
     * @param    string    $where
     * @return   array
     */
	public function getAllTag($where = null,$page=null, $pageSize = null)
	{
		if ($where['tag']) {
		    $wheresql .= " and  tag like '%{$where['tag']}%'";
		}
		if ($where['title']) {
		    $wheresql .= " and title like '%{$where['title']}%'";
		}
		return $this -> _db -> getAllTag($wheresql,$page, $pageSize);
	}

	/**
     * 添加新标签
     *
     * @param    string    $where
     * @return   array
     */
	public function addTag($data)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());

        $data = Custom_Model_Filter::filterArray($data, $filterChain);
		return $this -> _db -> addTag($data);
	}

	/**
     * 添加标签关联文章
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function updateTag($data, $tag_id)
	{

		if($tag_id > 0){
			if (is_array($data['article_id']) && count($data['article_id']) > 0){
				$val = implode(',', $data['article_id']);
			    $where = "article_id in(".implode(',', $data['article_id']).")";
			}else{
				$val = '';
				$where = "article_id>0";
			}
			$this -> _db -> updateTag($tag_id, $val);
			return true;
		}
	}

	/**
     * 获取标签
     *
     * @param    string    $where
     * @return   array
     */
	public function getTag($where)
	{
		$data = array_shift($this -> _db -> getTag($where));
		if ($data['config']){
			$ids = $data['config'];
			$result['details'] = $this -> get(" and a.article_id in($ids)", 'article_id,cat_name,title,author,source,is_view,add_time,a.sort', null, null, "find_in_set(a.article_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}

	/**
     * 检查输入
     *
     * @param    string    $where
     * @return   array
     */
	public function checkinput($where)
	{
		$data = array_shift($this -> _db -> getTag($where));
		return $data;
	}

	/**
     * 编辑分类列表可以编辑项目
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
     * @return   string
     */
	public function ajaxupdatetag($id, $field, $val)
	{
		$filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StripTags());

		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);

		if ((int)$id > 0) {
		    $result = $this->_db->ajaxupdatetag((int)$id, $field, $val);
		    if (is_numeric($result) && $result > 0) {
		        return 'ajaxUpdateSucess';
		    } else {
			    return 'error';
		    }
		}
	}

	/**
     * 获取文章关联商品
     *
     * @param    string    $where
     * @return   array
     */
	public function getArticleGoods($articleID)
	{
		$data = $this->_db->getArticleByID($articleID);
		if ($data['goods_ids']){
			$ids = $data['goods_ids'];
            $goods_api = new Admin_Models_API_Goods();
			$result['details'] = $goods_api -> get("a.goods_id in($ids)", 'a.goods_id,a.onsale,a.cat_id,b.cat_path,goods_name,goods_img,goods_sn,market_price,price', null, null, "find_in_set(a.goods_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}

	/**
     * 获取分类关联商品
     *
     * @param    string    $where
     * @return   array
     */
	public function getLinkCatGoods($cat_id)
	{
		$data = $this->_db->getCatByID($cat_id);
		if ($data['goods_config']){
			$ids = $data['goods_config'];
            $goods_api = new Admin_Models_API_Goods();
			$result['details'] = $goods_api -> get("a.goods_id in($ids)", 'a.goods_id,a.onsale,a.cat_id,b.cat_path,goods_name,goods_img,goods_sn,market_price,price', null, null, "find_in_set(a.goods_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}

	/**
     * 获取分类关联文章
     *
     * @param    string    $where
     * @return   array
     */
	public function getLinkCatArticle($cat_id)
	{
		$data = $this->_db->getCatByID($cat_id);
		if ($data['article_config']){
			$ids = $data['article_config'];
            $result['details'] = $this -> get(" and a.article_id in($ids)", 'article_id,cat_name,title,author,source,is_view,add_time,a.sort', null, null, "find_in_set(a.article_id, '$ids')");
		}
		$result['data'] = $data;
		return $result;
	}

	/**
     * 编辑关联商品
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function editlinkGoods($data, $article_id)
	{
		if($article_id > 0){
			if (is_array($data['goods_id']) && count($data['goods_id']) > 0){
				$val = implode(',', $data['goods_id']);
			    $where = "goods_id in(".implode(',', $data['goods_id']).")";
			}else{
				$val = '';
				$where = "goods_id>0";
			}
			$this -> _db -> updatelinkGoods($article_id, $val);
			return true;
		}
	}

	/**
     * 编辑分类关联商品
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function editlinkCatGoods($data, $cat_id)
	{
		if($cat_id > 0){
			if (is_array($data['goods_id']) && count($data['goods_id']) > 0){
				$val = implode(',', $data['goods_id']);
			    $where = "goods_id in(".implode(',', $data['goods_id']).")";
			}else{
				$val = '';
				$where = "goods_id>0";
			}
			$this -> _db -> updateLinkCat($cat_id, $val,'goods_config');
			return true;
		}
	}

	/**
     * 编辑分类关联文章
     *
     * @param    int    $data
     * @param    int    $tag_id
     * @return   void
     */
	public function editlinkCatArticle($data, $cat_id)
	{
		if($cat_id > 0){
			if (is_array($data['article_id']) && count($data['article_id']) > 0){
				$val = implode(',', $data['article_id']);
			    $where = "article_id in(".implode(',', $data['article_id']).")";
			}else{
				$val = '';
				$where = "article_id>0";
			}
			$this -> _db -> updateLinkCat($cat_id, $val,'article_config');
			return true;
		}
	}

	/**
	 *
	 *
	 */
	public function editArticle($data) {
		if($this -> _db -> edit($data)){
			return 'ok';
		}else{
			return 'err';
		}
	}
	/**
	 *更新广告位是否显示
	 *
	 */
	public function editAdv($data) {
		if($this -> _db -> editAdv($data)){
			return 'ok';
		}else{
			return 'err';
		}
	}
	/**
	 *更新广告位是否显示
	 *
	 */
	public function editLink($data) {
		if($this -> _db -> editLink($data)){
			return 'ok';
		}else{
			return 'err';
		}
	}
	/**
	 * 查询数据字典
	 */
	 public function getDataDictionary($where){
		return $this -> _db -> getDataDictionary($where);
	 }
	 /**
	 * 查询数据资讯分类表
	 */
	 public function getNewsClass($where){
		return $this -> _db -> getNewsClass($where);
	 }
	 /**
	 * 查询数据品牌
	 */
	 public function getBrand($where){
		return $this -> _db -> getBrand($where);
	 }
	  /**
	 * 查询分类数据
	 */
	 public function getCatgory($where){
		return $this -> _db -> getCatgory($where);
	 }
	  /**
	 * 查询品牌分类 无条件
	 */
	 public function getBrandCatgory(){
		return $this -> _db -> getBrandCatgory();
	 }
	  /**
	 * 查询品牌分类 有
	 */
	 public function getBrandCatgoryWhere($where){
		return $this -> _db -> getBrandCatgoryWhere($where);
	 }
	 /**
	 * 查询分类数据
	 */
	 public function getGoods($where){
		return $this -> _db -> getGoods($where);
	 }
	 
	/**
	 * 根据ids 取得数据
	 * @param unknown_type $ids
	 * @return multitype:
	 */
	public function getByIds($ids){
	    return parent::getAllWithLink('shop_news as n|n.*',array(),array('n.id|in'=>$ids));
	}
	
	public function getNewsAll(){
		return $this->getAll('shop_news',array(),'id,asName,title');
	}
	
	public function getNewsArticleAll(){
		
		$tbl = 'shop_seo_article as a|a.article_id,a.abstract,a.cat_id,a.title,a.author,a.sort,a.add_time,a.is_view,a.img_url';
		$links = array();
		$links['shop_seo_cat as c'] = 'c.cat_id=a.cat_id|c.as_name';
		
		$where = array();
		$where['a.status'] = 'AUDITED';
		$where['a.is_view'] = 'Y';
		
		return $this->getAllWithLink($tbl,$links,$where,0,'article_id desc');
	}	
    	
	
}
