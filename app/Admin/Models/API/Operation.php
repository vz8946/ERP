<?php

class Admin_Models_API_Operation extends Custom_Model_Dbadv
{
	/**
     * 系统配置 DB
     * 
     * @var Admin_Models_DB_Operation
     */
	private $_db = null;

    private $_member = null;
    
	public function __construct()
	{
        $this->_auth=Admin_Models_API_Auth :: getInstance()->getAuth();
		$this->_db = new Admin_Models_DB_Operation();
		parent::__construct();
	}

	/**
     * 客服发送短信记录
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function addCustomerMsg($data)
    {
         return $this -> _db -> addCustomerMsg($data);
    }

    /**
     * 取得客服发送短信记录的列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getCustomerMsg($where=NULL, $page=1)
    {
        return $this -> _db -> getCustomerMsg($where, $page);
    }

    /**
     * 取得优惠活动列表分页
     *
     * @param   array   $where
     * @param   int     $page
     * @return  array
     */
    public function getActList($where=NULL, $page=1)
    {
        return $this -> _db -> getActList($where, $page);
    }

	/**
     * 按照ID查询
     *
     * @param    string    $act_id
     * @return   array
     */
	public function getActById($act_id = null)
	{
		return $this -> _db -> getActById($act_id);
	}

	/**
     * 添加或修改数据
     *
     * @param    array    $data
     * @param    int      $id
     * @return   string
     */
	public function editAct($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
		if ($data['act_name'] =='') {
			$this -> error = 'no_name';
			return false;
		}
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = strtotime($data['end_time']);
		$this -> upPath .= 'upload/act/';
		if(is_file($_FILES['act_img']['tmp_name'])) {
		    $thumbs = '350,350|100,100';
		    $upload = new Custom_Model_Upload('act_img', $this -> upPath);
		    $upload -> up(true, $thumbs);
		    if($upload -> error()){
			$this -> error = $upload -> error();
			return false;
		    }
			$img_url = $this -> upPath.'/'.$upload->uploadedfiles[0]['filepath'];
	            $data['act_img'] = $img_url;
		}
		if ($id === null) {
		    $result = $this -> _db -> insert($data);
		    if(!$result){
				$this -> error = 'error';
				return false;
		    }
		} else {
			$result = $this -> _db -> update($data, (int)$id);
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
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 1);" title="点击设为冻结"><u><font color=red>冻结</font></u></a>';
		   break;
		   case 1:
		       return '<a href="javascript:fGo()" onclick="ajax_status(\''.$url.'\', '.$id.', 0);" title="点击设为正常"><u>正常</u></a>';
		   break;
		   default:
		   	   return '<font color="#D4D4D4">删除</font>';
		}
	}
	/**
     * 更改活动状态
     *
     * @param    int    $id
     * @param    int    $status
     * @return   void
     */
	public function changeStatus($id, $status)
	{
		if ($id > 0) {
		    if($this -> _db -> updateStatus($id, $status) <= 0) {
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
	public function ajaxUpdate($id, $field, $val, $type)
	{
        $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
		$field = $filterChain->filter($field);
		$val = $filterChain->filter($val);
		
		if ((int)$id > 0) {
		    if ($this -> _db -> ajaxUpdate((int)$id, $field, $val, $type) <= 0) {
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
			         'exists'=>'该活动名已存在!',
			         'not_exists'=>'该活动名不存在!',
			         'forbidden'=>'禁止操作!',
			         'no_name'=>'请填写活动名称!',
			         'num_error'=>'数量错误!'
			        );
		if(array_key_exists($this -> error, $errorMsg)){
			return $errorMsg[$this -> error];
		}else{
			return $this -> error;
		}
	}
	
	/**
	 * 健康测试  （special-102.html）
	 * 
	 * @param array $search
	 * @param int $page
	 * @param int $pageSize
	 * 
	 * @return array
	 */
	public function getHealthAsk($search=null,$page=1,$pageSize=15) {
		return $this -> _db -> getHealthAsk($search,$page,$pageSize);
	}
	
	/**
	 * 删除健康测试  （special-102.html）
	 * 
	 * @param int $id
	 */
	public function healthAskDel($id) {
		$this -> _db -> healthAskEdit(array('del'=>1)," id=$id ");//不是真正删除
		//$this -> _db -> healthAskDel($id);//先不删除
	}
	
	/**
	 * 更改健康测试  （special-102.html）
	 * 
	 * @param array $set
	 * @param int $where
	 */
	public function healthAskEdit($set,$where) {
		$this -> _db -> healthAskEdit($set,$where);
	}
	
	/**
	 * 导出健康问卷 xls
	 * 
	 * @param array $datas
	 * @param int $forOut  导出订单格式为 “至宝导入专用格式”
	 */
	public function healthAskExport($datas, $forOut) {
		if($forOut){
			$doc[0] = array('姓名', '电话', '资料');
			$k = 1;
			foreach ($datas as $v){
				$doc[$k][] = $v['customer'];
				$doc[$k][] = $v['mobile'];
				$remarks = str_replace('@', "   ", $v['symptom']).'   ';
				($v['height']) && $remarks .= '身高：'.$v['height'].'   ';
				($v['weight']) && $remarks .= '体重：'.$v['weight'].'   ';
				($v['age']) && $remarks .= '年龄：'.$v['age'].'   ';
				($v['sex']) && $remarks .= '性别：'.(($v['sex'] == 1)?'男':'女').'   ';
				($v['ip']) && $remarks .= 'IP:'.$v['ip'].'   ';
				($v['ip_location']) && $remarks .= 'IP归属地:'.$v['ip_location'].'   ';
				($v['mobile_location']) && $remarks .= '手机归属地:'.$v['mobile_location'].'   ';
				$doc[$k][] = $remarks;
				$k++;
			}
		}else{
			$doc[0] = array('姓名', '年龄', '性别', '身高', '体重', '电话','资料', '时间', '联盟');
			$k = 1;
			foreach ($datas as $v){
				$doc[$k][] = $v['customer'];
				$doc[$k][] = $v['age'];
				$doc[$k][] = ($v['sex'] == 1)?'男':'女';
				$doc[$k][] = $v['height'];
				$doc[$k][] = $v['weight'];
				$doc[$k][] = $v['mobile'];
				$doc[$k][] = str_replace('@', "   ", $v['symptom']);
				$doc[$k][] = date("y-m-d H:i:s", $v['add_time']);
				$doc[$k][] = $v['u'].' ('.$v['u_name'].')';
				$k++;
			}
		}
		$xls = new Custom_Model_GenExcel();
		$xls->addArray($doc);
		$showname = '种子问卷资料';
		if (preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"])){
			$showname = urlencode($showname);
		}
		$xls->generateXML($showname);
		exit();
	}
	
	/**
	 * 专题s
	 * 
	 */
	public function getAskBelongs() {
		return $this -> _db -> getAskBelongs();
	}

	/**
	 * 退货原因
	 * 
	 */
	public function getAllReason() {
		return $this -> _db -> getAllReason();
	}

	public function getSmsSendNum($fdate='',$tdate='',$sender=''){

		$cond = array();

		if(!empty($fdate)){
			$cond['add_time|egt'] = strtotime($fdate);
		}	
	
		if(!empty($tdate)){
			$ttime = strtotime($tdate);
			$cond['add_time|elt'] = $ttime+24*3600; 
		}	
	
		if(!empty($sender)){
			$cond['admin_name'] = trim($sender);
		}	
	
		$list = $this->getAll('shop_sendmsg_log',$cond);
		$num = 0;
		foreach ($list as $k => $v) {
			$num += count(explode(',', $v['mobile']));
		}
		return $num;		
	}	
	
}
