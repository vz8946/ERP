<?php
class Admin_OperationController extends Zend_Controller_Action
{
	/**
     * 运营管理 API
     * @var Admin_Models_API_Operation
     */
     
    private $_api = null;

	const ADD_SUCCESS = '恭喜您，添加成功!';
	const ACT_SUCCESS = '恭喜您，审核成功!';
    const NO_MEMBER = '没有该用户! 请检查您输入的论坛用户名是否正确！';
    const ERRORNUM = '请填入1---3000的有效数字！';
    const HASEMPTY = '信息填写不完整，请核对填写项！';
	const EDIT_SUCCESS = '编辑试用活动成功!';
	const DEL_SUCCESS = '删除活动成功!';

	/**
	 * auth对象
	 */
	private $_auth = null;

	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init()
	{
		$this -> _api = new Admin_Models_API_Operation();
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}

    /**
     * 客服发送短信记录
     *
     * @return void
     */
    public function customermsgAction()
    {
        $search = $this->_request->getParams();
        $where = $search;
        $data = $this -> _api -> getCustomerMsg($where, intval($this -> _request -> getParam('page', 1)));
        $this -> view -> data = $data['data'];
        $pageNav = new Custom_Model_PageNav($data['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> param = $search;
    }
    /**
     * 优惠专区管理列表
     *
     * @return void
     */
    public function actAction()
    {
        $search = $this->_request->getParams();
        $datas = $this -> _api -> getActList($search, intval($this -> _request -> getParam('page', 1)));
        if(count($datas['data'])>1){
                foreach ($datas['data'] as $k => $v)
                {
                	$datas['data'][$k]['start_time'] = ($v['start_time'] > 0) ? date('Y-m-d H:i:s', $v['start_time']) : '';
                	$datas['data'][$k]['end_time'] = ($v['end_time'] > 0) ? date('Y-m-d H:i:s', $v['end_time']) : '';
                    $datas['data'][$k]['status'] = $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $v['act_id'], $v['status']);
                }
        }
        $this -> view -> datas = $datas['data'];
        $pageNav = new Custom_Model_PageNav($datas['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> param = $search;
    }

    /**
     * 添加优惠专区
     *
     * @return void
     */
    public function addActAction()
    {
        if ($this -> _request -> isPost()) {
        	$result = $this -> _api -> editAct($this -> _request -> getPost());
        	if ($result) {
        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS, '/admin/operation/act/', 1250);
        	}else{
        	    Custom_Model_Message::showMessage($this -> _api -> error());
        	}
        } else {
        	$this -> view -> action = 'add-act';
        	$this -> render('edit-act');
        }
    }
    /**
     * 编辑优惠专区
     *
     * @return void
     */
    public function editActAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
                $result = $this -> _api -> editAct($this -> _request -> getPost(), $id);
	        	if ($result) {
	        	    Custom_Model_Message::showMessage(self::EDIT_SUCCESS, '/admin/operation/act/', 1250);
	        	}else{
	        	    Custom_Model_Message::showMessage($this -> _api -> error());
	        	}
            } else {
                $this -> view -> action = 'edit-act';
                $data = $this -> _api -> getActById($id);
				$data['start_time'] = date('Y-m-d', $data['start_time']);
				$data['end_time'] = date('Y-m-d', $data['end_time']);
                $this -> view -> data = $data;
            }
        }else{
            Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
        }
    }

    /**
     * 删除优惠专区
     *
     * @return void
     */
    public function delActAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _api -> delete($id);
            if(!$result) {
        	    exit($this -> _api -> error());
            }else{
			   Custom_Model_Message::showMessage(self::DEL_SUCCESS, '/admin/operation/act/', 1250);
			}
        } else {
            exit('error!');
        }

    }

    /**
     * 更改状态动作
     *
     * @return void
     */
    public function statusAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);

    	if ($id > 0) {
	        $this -> _api -> changeStatus($id, $status);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _api -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $id, $status);
    }

    /**
     * ajax更新数据
     *
     * @return void
     */
    public function ajaxupdateAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        $field = $this -> _request -> getParam('field', null);
        $val = $this -> _request -> getParam('val', null);
        $type = $this -> _request -> getParam('type', null);

        if ($id > 0) {
            $this -> _api -> ajaxUpdate($id, $field, $val, $type);
        } else {
            exit('error!');
        }
    }

    /**
     * 简单 curl
     */
    public function simpleCurl($url) {
    	$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, $url);
		curl_setopt($ch2, CURLOPT_HEADER, false);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		$rs = curl_exec($ch2);
		return $rs;
    }

    /**
     * ajax得到ip地址
     */
    public function getIpLocationAction() {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$ip = $this -> _request -> getParam('ip', '');
    	if($ip == ''){ exit('...');}
    	$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip;
    	$rs = $this -> simpleCurl($url);
    	$rs = json_decode($rs, true);
    	echo $rs['country'].' '.$rs['province'].' '.$rs['city'];
    	exit();
    }

	/**
	*商品退货原因列表
	*
	*/
	public function reasonAction(){
        $datas= $this -> _api -> getAllReason();
        $this -> view -> reasonlist =  $datas;
	}
	
	/**
	 * 群发手机短信 调用模板
	 */
	public function sendBatchSmsAction(){
	    Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	}
	
	/**
	 * 执行发短信动作
	 */
	public function smsSendBatchDoAction(){
	    $mobiles = $this->_request->getParam('mobiles');
	    $msg = $this->_request->getParam('msg');
	    if(empty($msg)) Custom_Model_Tools::ejd('fail','消息不能为空！');
	    
	    if(mb_strlen($msg,'utf8')>280) Custom_Model_Tools::ejd('fail','不能超过280个字符！');
	    
	    $arr_mobiles = explode("\n", $mobiles);
	    
	    $arr_valid_mobile = array();
	    foreach ($arr_mobiles as $k=>$v){
	        $v = trim($v);
	        if(empty($v)) continue;
	        if(preg_match("/^((\(\d{3}\))|(\d{3}\-))?13\d{9}|15\d{9}|18\d{9}$/", $v) === 0) continue;
	        
	        $arr_valid_mobile[] = $v;
	        
	    }
	    
	    if(empty($arr_valid_mobile)) Custom_Model_Tools::ejd('fail','没有合法的手机号！');
	
	    $sms = new Custom_Model_Sms();
	    $batch_length = 99;
	    $count_mobile = count($arr_valid_mobile);
	    $count_batch = floor($count_mobile/$batch_length);

	    for($i=0;$i<($count_batch+1);$i++){
	        $tos = array_slice($arr_valid_mobile, $i*$batch_length,$batch_length);        
	        
    	    $rs = $sms->send($tos, $msg);
    	    $rs = abs($rs);
    	    
    	    if($rs>2000){
        		$sendmsglog = new Admin_Models_API_Operation();
        	    $sendmsglog->addCustomerMsg(array('mobile'=>implode(',', $tos),'msg'=>$msg));
    	    }
    	    
	    }
	    
	    Custom_Model_Tools::ejd('succ','操作成功！');
	    Custom_Model_Tools::ejd('fail','发送失败，请联系系统管理员！');
	    
	}

	public function smsSendStatsAction(){
			
		$fdate = $this->_request->getParam('fdate','');
		$tdate = $this->_request->getParam('tdate','');
		$sender = $this->_request->getParam('sender','');
		
		$num = $this->_api->getSmsSendNum($fdate,$tdate,$sender);
		
		echo '发送总数为 '.$num.' 条';
		exit;
		
	}

}
