<?php
class Admin_Models_API_Topics extends Admin_Models_API_Base
{
	private $upPath = 'upload/topics/';

	public function __construct()
	{
	    parent::__construct();
		$this->_db = new Admin_Models_DB_Topics();
	}

	/**
     * 添加专题
     *
     * @param    array     $data
     * @param    string    $error
     * @return   bool
     */
    public function add($data, &$error){
	    $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim());
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        $data['content'] = stripslashes($data['content']);
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
				$this -> _db -> edit(array('imgUrl'=>$img_url, 'id'=>$insertID));
	    	}
            return true;
        }else{
            $error = 'addFail';
            return false;
        }
    }
	/**
     * 取专题数量
     *
     * @param    string    $where
     * @return   int
     */
    public function getCount($where=null){
        return $this->_db->getCount($where);
    }
	/**
     * 取专题列表
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
     * 编辑专题信息
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
        }
        $data['content'] = stripslashes($data['content']);
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
				$this -> _db -> edit(array('imgUrl'=>$img_url, 'id'=>$data['id']));
	    	}
            return true;
        }else{
            $error = 'editFail';
            return false;
        }
    }
    
    /**
     *
     * 切换主题可见状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     *
     */
    public function toggleIsdis($id)
    {
        $r = $this->getRow('shop_topics',array('id'=>$id));
        $status = $r['isDisplay'];
        $status = $status == 1 ? 0 : 1;
        $this->update('shop_topics', array('isDisplay'=>$status),array('id'=>$id));
        return $status;
    }
    
    
	/**
     * ajax更新数据
     *
     * @param    int      $id
	 * @param    string   $field
	 * @param    string   $val
	 * @param    string   $type
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
		}
	}
}
