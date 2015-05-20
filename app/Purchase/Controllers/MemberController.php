<?php
class MemberController extends Zend_Controller_Action
{
	/**
     * 认证 API
     * 
     * @var Admin_Models_API_Auth
     */
	protected $_auth = null;
	
	/**
	 * 
	 * @var Purchase_Models_API_Member
	 */
	protected $_member = null;
	private $_page_size = '20';
	/**
     * 站点留言类型
     * 
     * @var array
     */
	protected $_shopMsgType = array('留言', '投诉', '询问', '售后', '求购');
	
	/**
     * 未选择地区
     */
	const NO_AREA = '请选择地区!';
	
	/**
     * 未填写收货人
     */
	const NO_CONSIGNEE = '请填写收货人!';
	
	/**
     * 未填写详细地址
     */
	const NO_ADDRESS = '请填写详细地址!';
	
	/**
     * 邮政编码格式错误
     */
	const ERROR_ZIP = '请填写正确的邮政编码!';
	
	/**
     * 电话格式错误
     */
	const ERROR_PHONE = '请填写正确的电话号码!';
	
	/**
     * 传真格式错误
     */
	const ERROR_FAX = '请填写正确的传真格式!';
	
	/**
     * 收货地址多于限制个数
     */
	const TOO_MANY_ADDRESS = '您最多只能有规定个数的收货地址!';
	
	/**
     * 编辑地址成功
     */
	const EDIT_ADDRESS_SUCESS = '编辑收货地址成功!';
	
	/**
     * 编辑会员信息成功
     */
	const EDIT_MEMBER_SUCESS = '修改个人信息成功!';
	/**
	 * 编辑会员头像成功
	 */
	const EDIT_AVATAER_SUCESS = '修改头像成功!';
	
	/**
     * Email地址不正确
     */
	const ERROR_EMAIL = '请输入正确的Email地址!';
	
	/**
     * 手机号码不正确
     */
	const ERROR_MOBILE = '请输入正确的手机号码!';
	
	/**
     * MSN不正确
     */
	const ERROR_MSN = '请输入正确的MSN!';
	
	/**
     * QQ码不正确
     */
	const ERROR_QQ = '请输入正确的QQ号码!';
	
	/**
     * 办公室电话不正确
     */
	const ERROR_OFFICE_PHONE = '请输入正确的办公室电话!';
	
	/**
     * 住宅电话不正确
     */
	const ERROR_HOME_PHONE = '请输入正确的住宅电话!';
	
	/**
     * 密码输入不一致
     */
	const NO_SAME_PASSWORD = '密码输入不一致!';
	
	/**
     * 密码格式不正确
     */
	const ERROR_PASSWORD = '密码必须为6-20位的字母和数字的组合!';
	
	/**
     * 密码不正确
     */
	const ERROR_OLD_PASSWORD = '您输入的原密码不正确!';
	
	/**
     * 没有输入密码
     */
	const NO_PASSWORD = '请输入密码!';
	
	/**
     * 密码修改成功
     */
	const EDIT_PASSWORD_SUCESS = '修改密码成功!';
	
	/**
     * 密码修改成功
     */
	const NO_MESSAGE = '请输入留言内容!';
	
	/**
     * 密码修改成功
     */
	const TO_LONG_MESSAGE = '留言内容必须在255个字以内!';
	
	/**
     * 密码修改成功
     */
	const ADD_MESSAGE_SUCESS = '添加留言成功!';
	
	/**
     * 未选择支付方式
     */
	const NO_PAYMENT = '请选择支付方式!';

    const HAS_CONFIRM = '已确认订单不能修改支付方式'; 
    const IS_CANCEL = '被取消的订单不能修改支付方式'; 
    const HAS_PAY = '该订单已经完成支付，不能修改支付方式'; 
    const NO_EDIT = '该订单已经被锁定，如果需要修改，请联系客服';

	const UNABLE_BIRTHDAY  = '对不起,你不能对生日再做修改! 如需更改请与管理员联系.';

	/**
     * 修改支付方式成功
     */
	const SET_ORDERPAYMENT_SUCESS = '修改支付方式成功!';
	
	/**
     * 修改收货地址成功
     */
	const SET_ORDERADDRESS_SUCESS = '修改收货地址成功!';
	/**
     * 该订单不能取消
     */
    const NOT_CANCEL = '不能取消';
	/**
     * 订单取消成功
     */
    const SET_ORDERCANCEL_SUCESS = '订单取消成功';
	/**
     * 订单设置满意不退货成功
     */
    const SET_FAV_SUCESS = '订单设置满意不退货成功';
    
    /**
     * 卡号或密码不正确
     */
    const CARD_ERROR = '卡号或密码错误,无法充值';
    
    /**
     * 会员不存在
     */
    const NO_USER = '该会员不存在,请提供正确的充值会员名称';
    
    /**
     * 该卡已经过期
     */
    const CARD_EXPIRED = '该卡已经过期,无法充值';
    
    /**
     * 卡已经被使用
     */
    const CARD_USED = '该卡已经被使用,无法充值';
    
    /**
     * 礼品卡充值成功
     */
    const FILL_IN_SUCESS = '恭喜,礼品卡充值成功';
	const SET_ORDERSMSNO_SUCESS = '修改手机短信号码成功!';
	
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _auth = Purchase_Models_API_Auth :: getInstance();
		$this -> _member = new Purchase_Models_API_Member();
		$this -> _memberConfig = Zend_Registry::get('memberConfig');
		$auth = $this -> _auth -> getAuth();
    	$this -> _memberRanks = Custom_Config_Xml::getMemberRanks();
		if (!$auth || $auth['ltype']) {
			$this -> _helper -> redirector -> gotoUrl(Zend_Controller_Front::getInstance() -> getBaseUrl(). '/login.html');
		} else {
            $this -> _memberInfo = $this -> _member -> getUser();
            $this -> _memberInfo = $this -> _memberInfo ? $this -> _memberInfo : array();
		    $this -> view -> member = array_merge($auth, $this -> _memberInfo);
		    $this -> view -> action = $this -> _request -> getActionName();
		    $this -> view -> type = $this -> _request -> getParam('type', '');
			$this -> view -> rank = $this -> _memberRanks[$this -> _memberInfo['rank_id']];
			$this -> view -> isView = 1;
		}
		$this->view->css_more = ',user.css';
		$this->view->cur_position = 'member';
	}
	
	/**
     * 编辑会员基本资料
     *
     * @return void
     */
    public function profileAction()
    {
        if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $result = $this -> _member -> editMember($this -> _request -> getPost());
            echo "<script>parent.document.getElementById('dosubmit').value=' 确定 ';parent.document.getElementById('dosubmit').disabled=false;</script>";
            switch ($result) {
        		case 'errorEmail':
        		    Custom_Model_Message::showAlert(self::ERROR_EMAIL);
        		    break;
        		case 'unable':
        		    Custom_Model_Message::showAlert(self::UNABLE_BIRTHDAY);
        		    break;
        		case 'errorMobile':
        		    Custom_Model_Message::showAlert(self::ERROR_MOBILE);
        		    break;
        		case 'errorMsn':
        		    Custom_Model_Message::showAlert(self::ERROR_MSN);
        		    break;
        		case 'errorQq':
        		    Custom_Model_Message::showAlert(self::ERROR_QQ);
        		    break;
        		case 'errorOfficePhone':
        		    Custom_Model_Message::showAlert(self::ERROR_OFFICE_PHONE);
        		    break;
        		case 'errorHomePhone':
        		    Custom_Model_Message::showAlert(self::ERROR_HOME_PHONE);
        		    break;
        		case 'editMemberSucess':
        		    $this -> _auth -> updateAuth();
        		    Custom_Model_Message::showAlert(self::EDIT_MEMBER_SUCESS,false, '/member/profile/');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert('error!');
        	}
        } else {
            $this -> view -> action = 'profile';
            $this -> view -> title = '修改个人信息';
            $this -> view -> sexRadios = array(1 => '男', 2 => '女');
			$profileMember=$this -> _memberInfo;
			if( $profileMember['birthday']=='0000-00-00' || !$profileMember['birthday']){
				$birthdayAble=1;
				$this -> view -> birthdayAble = $birthdayAble;
			}
        }
        
        $this->view->nav_1_personal = ' on ';
        $this->view->nav_2_personal_info = ' c ';
                
    }
    
    public function verifyemailAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$email = $this -> _request ->getParam('email'); 
    	
    	if($this -> _memberInfo['ischecked'])
    	{
    		echo  Zend_Json::encode(array('status'=>0,'msg'=>'邮箱已验证'));
    		exit();
    	}
    	
    	if ($email && !Custom_Model_Check::isEmail($email)){
    		echo  Zend_Json::encode(array('status'=>0,'msg'=>'邮件格式错误'));
    		exit();
    	} 
    	
    	$code = Custom_Model_Encryption::getInstance() -> encrypt($this->_memberInfo['user_id'].'#'.$this->_memberInfo['email'],'EncryptCode');
    	$verifyUrl = "http://{$_SERVER['HTTP_HOST']}/auth/verifyemail/code/{$code}";
    	$templateValue = array();    	
    	$templateValue['user_name'] = $this -> _memberInfo['user_name'];
    	$templateValue['shop_name'] = Zend_Registry::get('config') -> name;
    	$templateValue['validate_email'] = $verifyUrl;
    	$templateValue['send_date'] = date("Y年m月d日 H:i:s");  
    	
    	//邮件发送 
    	$template = new Purchase_Models_API_EmailTemplate();
    	$template = $template -> getTemplateByName('register_validate', $templateValue);
    	$mail = new Custom_Model_Mail();
    	if ($mail -> send($email, $template['title'], $template['value'])) {    	  
    	   echo  Zend_Json::encode(array('status'=>1,'msg'=>'邮件发送失败'));
    	   exit();
    	} else {
    	   $this->_member->editEmail($email);
    	   echo  Zend_Json::encode(array('status'=>1,'msg'=>'邮件发送成功，请到收件箱查看验证邮件！'));
    	   exit();
    	}  	
    	
    }
    
    /**
     * 会员修改密码
     *
     * @return void
     */
    public function passwordAction()
    {
        if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $result = $this -> _member -> editPassword($this -> _request -> getPost());
            echo "<script>parent.document.getElementById('dosubmit').value=' 确定 ';parent.document.getElementById('dosubmit').disabled=false;if(parent.document.myForm.old_password) parent.document.myForm.old_password.value='';parent.document.myForm.password.value=parent.document.myForm.confirm_password.value='';</script>";
            switch ($result) {
        		case 'noSamePassword':
        		    Custom_Model_Message::showAlert(self::NO_SAME_PASSWORD);
        		    break;
        		case 'errorPassword':
        		    Custom_Model_Message::showAlert(self::ERROR_PASSWORD);
        		    break;
        		case 'errorOldPassword':
        		    Custom_Model_Message::showAlert(self::ERROR_OLD_PASSWORD);
        		    break;
        		case 'noPassword':
        		    Custom_Model_Message::showAlert(self::NO_PASSWORD);
        		    break;
        		case 'editPasswordSucess':
        		    $this -> _auth -> updateAuth();
        		    Custom_Model_Message::showAlert(self::EDIT_PASSWORD_SUCESS , '/member/password/');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert('error!');
        	}
        } else {
			$member = $this -> _memberInfo;
			$auth = $this -> _auth -> getAuth();
			$this -> view -> member = array_merge($auth, $member);
            $this -> view -> action = 'password';
            $this -> view -> title = '修改密码';
        }
        
        $this->view->nav_1_personal = ' on ';
        $this->view->nav_2_personal_pass = ' c ';
                
    }
	
	/**
     * 会员订单列表
     *
     * @return void
     */
	public function orderAction()
	{
		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$order = $this -> _member -> getAllOrder($this -> _request -> getParams(), $page, 20);
		$pageNav = new Custom_Model_PageNav($order['total']);
		$this -> view -> total = $order['total'];
		$this -> view -> orderInfo = $order['order'];
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        $this -> view -> showTip = 'allow';
        $this -> view -> params = $this -> _request -> getParams();
        
        $this->view->nav_1_order = ' on ';
        $this->view->nav_2_order_index = ' c ';
	}

	/**
     * 会员订单详细
     *
     * @return void
     */
	public function orderDetailAction()
	{
		$batchSN = $this -> _request -> getParam('batch_sn');
		if ($this -> _request -> getPost()) {
			if ($this -> _request -> getPost('change') == 'payment') {
				$result = $this -> _member -> setOrderPayment($this -> _request -> getPost());
			    switch ($result) {
        		    case 'noPayment':
        		        Custom_Model_Message::showAlert(self::NO_PAYMENT, false);
        		        break;
        		    case 'hasConfirm':
        		        Custom_Model_Message::showAlert(self::HAS_CONFIRM, false);
        		        break;
        		    case 'isCancel':
        		        Custom_Model_Message::showAlert(self::IS_CANCEL, false);
        		        break;
        		    case 'hasPay':
        		        Custom_Model_Message::showAlert(self::HAS_PAY, false);
        		        break;
        		    case 'setOrderPaymentSucess':
        		        Custom_Model_Message::showAlert(self::SET_ORDERPAYMENT_SUCESS, false);
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showAlert('error', false);
			    }
			} elseif ($this -> _request -> getPost('change') == 'address') {
                echo "<script>parent.document.getElementById('dosubmit').value=' 确定 ';parent.document.getElementById('dosubmit').disabled=false;</script>";
				$result = $this -> _member -> setOrderAddress($this -> _request -> getPost());
			        switch ($result) {
                    case 'noEdit':
        		        Custom_Model_Message::showAlert(self::NO_EDIT);
                        break;
        		    case 'noArea':
        		        Custom_Model_Message::showAlert(self::NO_AREA);
        		        break;
        		    case 'noConsignee':
        		        Custom_Model_Message::showAlert(self::NO_CONSIGNEE);
        		        break;
        		    case 'noAddress':
        		        Custom_Model_Message::showAlert(self::NO_ADDRESS);
        		        break;
        		    case 'errorPhone':
        		        Custom_Model_Message::showAlert(self::ERROR_PHONE);
        		        break;
        		    case 'errorMobile':
        		        Custom_Model_Message::showAlert(self::ERROR_MOBILE);
        		        break;
        		    case 'setOrderAddressSucess':
        		        Custom_Model_Message::showAlert(self::SET_ORDERADDRESS_SUCESS, false);
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showAlert('error');
			    }
			} elseif ($this -> _request -> getPost('change') == 'sms_no') {
			    $this -> _member -> setOrderSmsNo($this -> _request -> getPost());
			    Custom_Model_Message::showAlert(self::SET_ORDERSMSNO_SUCESS, false);
			}
     	}
     	
		if ($batchSN) {
            //更新订单数据 开始
            $order = new Admin_Models_API_Order();
            $order -> orderDetail($batchSN);
            //更新订单数据 结束
            
			$this -> view -> action = 'order-detail';
            $order = @array_shift($this -> _member -> getOrderBatch($batchSN));
			if(!$order){
				$this -> _redirect('/member/order');
				}
            $order['price_logistic'] = floatval($order['price_logistic']);
            $order['price_pay'] = floatval($order['price_pay']);
            $order['price_adjust'] = floatval($order['price_adjust']);
            $order['price_from_return'] = floatval($order['price_from_return']);
			$this -> view -> order = $order;
            $this -> view -> payed = $order['price_payed'] + $order['price_from_return'];
            $this -> view -> blance = bcsub($order['price_pay'], bcadd(bcadd(bcadd(bcadd($order['price_payed'], $order['account_payed'], 2), $order['point_payed'], 2), $order['gift_card_payed'], 2), $order['price_from_return'], 2), 2);
            $data = $this -> _member -> getOrderBatchGoods($batchSN);
            $vitualGoodsAPI = new Admin_Models_API_VitualGoods();
            $this -> view -> vitualInfo = $vitualGoodsAPI -> getVitualInfo($data['product_all']);
            $this -> view -> data = $data;
			$this -> view -> product = $product = $data['product_all'];
			$this -> view -> paymentButton = $this -> _member -> getPayInfo($this -> _member -> getOrderByBatchSN($batchSN));
			$this -> view -> payment = $this -> _member -> getOtherPaymentList($order);
			$this -> view -> logistic = $this -> _member -> getLogistic(array('logistic_code' => $order['logistic_code']));
			$this -> view -> province = $this -> _member -> getChildAreaById(1);
			
			$this->view->css_more = ',user.css,order.css';
			$order['addr_province_id'] ? $this -> view -> city = $this -> _member -> getChildAreaById($order['addr_province_id']) : '';
			$order['addr_city_id'] ? $this -> view -> area = $this -> _member -> getChildAreaById($order['addr_city_id']) : '';
            $auth = $this -> _auth -> getAuth();
            $this -> view -> member = array_shift($this -> _member -> getMemberByUserName($auth['user_name']));
		} else {
			exit;
		}
	}

	/**
     * 会员留言列表
     *
     * @return void
     */
	public function messageAction()
	{
		if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $result = $this -> _member -> addMessage($this -> _request -> getPost());
            echo "<script>parent.document.getElementById('dosubmit').value='提交留言';parent.document.getElementById('dosubmit').disabled=false;parent.document.formMsg.msg_type[0].checked=true;parent.document.formMsg.msg_content.value='';</script>";
            switch ($result) {
        		case 'noMessage':
        		    Custom_Model_Message::showAlert(self::NO_MESSAGE);
        		    break;
        		case 'toLongMesssage':
        		    Custom_Model_Message::showAlert(self::TO_LONG_MESSAGE);
        		    break;
        		case 'addMessageSucess':
        		    Custom_Model_Message::showAlert(self::ADD_MESSAGE_SUCESS);
        			$this -> _redirect('/member/message/type/'.$this -> _request -> getParam('msg_type',0).'/page/1');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert('error!');
        	}
        } else {
		    $page = (int)$this -> _request -> getParam('page', 1);
            $page = ($page <= 0) ? 1 : $page;
		    $message = $this -> _member -> getAllMessage($type, $page, 5);
		    
		    if ($message['message']) {
		    	foreach ($message['message'] as $key => $value)
		    	{
		    		$message['message'][$key]['type'] = $this -> _shopMsgType[$message['message'][$key]['type']];
		    	}
		    }
		    $pageNav = new Custom_Model_PageNav($message['total'], 5, 'message');
		    $this -> view -> type = $type;
		    if ($type == 'order' && $this -> _request -> getParam('id')) {
			    $this -> view -> order = $this -> _member -> getOrderById($this -> _request -> getParam('id'));
		    }
		    $this -> view -> action = 'message';
		    $this -> view -> msgType = $this -> _shopMsgType;
		    $this -> view -> messageInfo = $message['message'];
            $this -> view -> pageNav = $pageNav -> getPageNavigation();
		}
		
        $this->view->nav_1_note = ' on ';
        $this->view->nav_2_note_list = ' c ';
				
	}
	
	/**
     * 会员收货地址列表
     *
     * @return void
     */
	public function addressAction()
	{
		if ($this -> _request -> getPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$result = $this -> _member -> editAddress($this -> _request -> getPost());
			echo "<script>parent.document.getElementById('dosubmit').value=' 确定 ';parent.document.getElementById('dosubmit').disabled=false;</script>";
			switch ($result) {
        		case 'noArea':
        		    Custom_Model_Message::showAlert(self::NO_AREA);
        		    break;
        		case 'noConsignee':
        		    Custom_Model_Message::showAlert(self::NO_CONSIGNEE);
        		    break;
        		case 'noAddress':
        		    Custom_Model_Message::showAlert(self::NO_ADDRESS);
        		    break;
        		case 'errorZip':
        		    Custom_Model_Message::showAlert(self::ERROR_ZIP);
        		    break;
        		case 'errorPhone':
        		    Custom_Model_Message::showAlert(self::ERROR_PHONE);
        		    break;
        		case 'errorMobile':
        		    Custom_Model_Message::showAlert(self::ERROR_MOBILE);
        		    break;
        		case 'errorEmail':
        		    Custom_Model_Message::showAlert(self::ERROR_EMAIL);
        		    break;
        		case 'errorFax':
        		    Custom_Model_Message::showAlert(self::ERROR_FAX);
        		    break;
        		case 'tooManyAddress':
        		    Custom_Model_Message::showAlert(self::TOO_MANY_ADDRESS);
        		    break;
        		case 'editSucess':
        		    Custom_Model_Message::showAlert(self::EDIT_ADDRESS_SUCESS);
			}
		} else {
		    $this -> view -> action = 'address';
		    $this -> view -> memberAddress = $this -> _member -> getAddress();
		    $this -> view -> province = $this -> _member -> getChildAreaById(1);
		}
		
		
        $this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_addr = ' c ';
				
	}
	
	/**
     * 取得配送区域
     *
     * @return void
     */
     public function areaAction()
     {
     	$this -> _helper -> viewRenderer -> setNoRender();
     	$id = $this -> _request -> getParam('id', null);
     	$area = $this -> _member -> getChildAreaById($id);
     	if ($area) {
     	    exit(Zend_Json :: encode($area));
     	}
     }
     
     /**
     * 更新会员订单支付方式
     *
     * @return void
     */
     public function setOrderPaymentAction()
     {
     	if ($this -> _request -> getPost()) {
     		$result = $this -> _member -> setOrderPayment($this -> _request -> getPost());
			switch ($result) {
        		case 'noPayment':
        		    Custom_Model_Message::showAlert(self::NO_PAYMENT);
        		    break;
        		case 'setOrderPaymentSucess':
        		    Custom_Model_Message::showAlert(self::SET_ORDERPAYMENT_SUCESS);
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert('error');
			}
     	}
     }
     
     /**
     * 取消订单
     *
     * @return void
     */
     public function cancelOrderAction()
     {
        $this -> _helper -> viewRenderer -> setNoRender();
     	$batchSN = $this -> _request -> getParam('batch_sn', null);
     	if ($batchSN) {
     	    $result = $this -> _member -> cancelOrder($batchSN);
     	    exit($result);
			switch ($result) {
        		case 'noCancel':
        		    Custom_Model_Message::showAlert(self::NOT_CANCEL, false, '/member/order/');
        		    break;
        		case 'setOrderCancelSucess':
        		    Custom_Model_Message::showAlert(self::SET_ORDERCANCEL_SUCESS, false, '/member/order/');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert('error', false, '/member/order/');
			}
     	}
     	exit('参数错误');
     }
     
	/**
     * 满意不退货
     *
     * @return void
     */
    public function favAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
     	$batchSN = $this -> _request -> getParam('batch_sn', null);
        $result = $this -> _member -> fav($batchSN);
        switch ($result) {
            case 'setOrderFavSucess' :
                Custom_Model_Message::showAlert(self::SET_FAV_SUCESS, false, '/member/order/');
            case 'error':
                Custom_Model_Message::showAlert('错误，操作失败！', false, '/member/order/');
        }
    }
  
    /**
     * 暂存架
     *
     * @return void
     */

    public function favoriteAction()
    {
  		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$favorite = $this -> _member -> getFavorite($page, 20);
		$pageNav = new Custom_Model_PageNav($favorite['total'],20);
		$this -> view -> info = $favorite['info'];
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        
        $this->view->nav_1_goods = ' on ';
        $this->view->nav_2_goods_favo = ' c ';
                
    }
	
	/**
     * 会员帐户首页
     *
     * @return void
     */
	public function indexAction()
	{
		//我的信息
		$this -> view -> coupons = $this -> _member -> getValidCoupon();
		//订单统计
		$order = $this -> _member -> getAllOrder(array('timesection'=>1, 'ordertype'=>7), 1, 1);//成功订单
		$this -> view -> okOrder = $order['total'];
		$order = $this -> _member -> getAllOrder(array('timesection'=>1, 'ordertype'=>4), 1, 1);//需付款订单
		$this -> view -> feeOrder = $order['total'];
		$order = $this -> _member -> getAllOrder(array('timesection'=>1, 'ordertype'=>3), 1, 1);//取消订单
		$this -> view -> cancelOrder = $order['total'];
		//浏览历史
		$goods_api = new Purchase_Models_API_Goods();
		$goods = $goods_api -> getHistory();
		$this -> view -> history = array_slice($goods, 0, 3);
		//收藏
		$favorite = $this -> _member -> getFavorite(1, 3);
		$this -> view -> fav = $favorite['info'];
	}


	/**
     * 会员账户余额支付
     *
     * @return void
     */
    public function priceAccountPayedAction()
    {
		$batchSN = $this -> _request -> getParam('batch_sn');
		$priceAccount = (float)$this -> _request -> getParam('price_account', null);
                
        $result = $this -> _member -> priceAccountPayed($batchSN, $priceAccount);
        switch ($result) {
            case 1 :
                Custom_Model_Message::showAlert("帐户余额不足");
            case 2 :
                Custom_Model_Message::showAlert("取消单和无效单无需再支付");
            case 3 :
                Custom_Model_Message::showAlert("货到付款，无需再支付");
            case 4 :
                Custom_Model_Message::showAlert("该单已经付清，无需再支付");
            case 5 :
                Custom_Model_Message::showAlert("支付金额不能小于0");
            case 6 :
                Custom_Model_Message::showAlert("支付金额不能大于需支付金额");
            case 7 :
                Custom_Model_Message::showAlert('error');
            default :
                Custom_Model_Message::showAlert("帐户余额支付成功",false, '/member/order-detail/batch_sn/'.$batchSN);
        }
        exit;
    }
	
	/**
     * 会员账户余额列表
     *
     * @return void
     */
	public function moneyAction()
	{
		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$money = $this -> _member -> getAllMoney($page, 20);
		$pageNav = new Custom_Model_PageNav($money['total']);
		$this -> view -> money = $this -> _memberInfo['money'];
		$this -> view -> moneyInfo = $money['money'];
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
		
        $this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_blance = ' c ';
		$this->view->cn_amount_tab_money = ' c ';
	}
	
	
	
	/**
     * 会员积分列表
     *
     * @return void
     */
	public function pointAction()
	{
		$rank = $this -> _memberRanks[$this -> _memberInfo['rank_id']];
		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$point = $this -> _member -> getAllPoint($page, 20);
		$pageNav = new Custom_Model_PageNav($point['total']);
		$this -> view -> point = $this -> _memberInfo['point'];
		$this -> view -> pointInfo = $point['point'];
        if($this -> _memberInfo['point']>500){
             $this -> view -> actexchange =1;
             $this -> view -> ableact =floor($this -> _memberInfo['point']/500)*500;
        }
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        
        $this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_point = ' c ';
				  
        
	}

	/**
     * 经验值变动历史
     *
     * @return void
     */
    public function experienceAction()
    {
        $page = (int)$this ->_request->getParam('page', 1);

		$params = array(
			'member_id' => $this->_memberInfo['member_id'],
		);

        $count  = $this->_member->getExperienceCount($params);

		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $this->_member->getExperienceList($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
		
        $this->view->pageNav       = $pageNav->getPageNavigation();
        $this->view->infos         = $infos;
		$this->view->params        = $params;
		$this->view->experience    = $this->_memberInfo['experience'];
		$this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_experience = ' c ';
    }

	/**
     * 站内信列表
     *
     * @return void
     */
	public function insideMessageAction()
	{
		$message_db = new Purchase_Models_API_Message();
		$page = (int)$this ->_request->getParam('page', 1);

		$params = $this->_request->getParams();
		$params['member_id'] = $this->_memberInfo['member_id'];
		$params['read']      = isset($params['read']) ? intval($params['read']) : '0';
        $count  = $message_db->getCount($params);
		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;
			$infos = $message_db->browse($params, $limit);
		}

        $this->view->infos  = $infos;
		$this->view->params = $params;
		$this->view->params = $params;
		$pageNav = new Custom_Model_PageNav($count, $this->_page_size, 'ajax_search');
		
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        
        $this->view->nav_1_note = ' on ';
        $this->view->nav_2_note_msg = ' c ';
                
	}

	/**
     * 查看站内消息
     *
     * @return void
     */
	public function viewMessageAction()
	{
		$this ->_helper->viewRenderer->setNoRender();
		$message_db = new Purchase_Models_API_Message();
		$message_id = intval($this->_request->getParam('message_id', 0));
		if ($message_id < 1) {
			exit(json_encode(array('success' => 'false', 'message' => 'ID不正确')));
		}

		$info = $message_db->get($message_id);
		if (count($info) < 1) {
			exit(json_encode(array('success' => 'false', 'message' => '没有找到相关记录')));
		}

		$read = $this->_request->getParam('read');
		if ($read != '1') {
			$params['member_id'] = $this->_memberInfo['member_id'];
			$params['message_id'] = $message_id;
			$message_db->updateMessageMemberInfo($params, array('is_read' => 1));
		}
		exit(json_encode(array('success' => 'true', 'data' => $info)));
	}


	/**
     * 用户礼品卡
     *
     * @return void
     */
    public function giftCardAction()
    {
  		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$card = $this -> _member -> getGiftCard($page, 20);
		$pageNav = new Custom_Model_PageNav($card['total']);
		$this -> view -> info = $card['info'];
		$this -> view -> curtime = date('Y-m-d');
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        
        $this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_xianjinquan = ' c ';
                
    }

	/**
     * 用户礼品卡历史记录
     *
     * @return void
     */
    public function giftCardLogAction()
    {
  		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
		$card = $this -> _member -> getGiftCardLog($page, 20);
		$pageNavLog = new Custom_Model_PageNav($card['logTotal']);
		$this -> view -> logInfo = $card['logInfo'];
        $this -> view -> pageNavLog = $pageNavLog -> getPageNavigation();
    }
    
    /**
     * 用户礼金券信息
     *
     * @return void
     */
    public function couponAction()
    {
        $auth = $this -> _auth -> getAuth();
  		$page = (int)$this -> _request -> getParam('page', 1);
        $page = ($page <= 0) ? 1 : $page;
        $type = (int)$this -> _request -> getParam('type', 1);
  		$auth = $this -> _auth -> getAuth();
		$coupons = $this -> _member -> getCoupon($page, 20, $type);
		if ($coupons['content']) {
			$i = 0;
			$date = date('Y-m-d');
			foreach ($coupons['content'] as $key => $coupon)
			{
				$coupons['content'][$key]['card_sn'] = strtoupper($coupons['content'][$key]['card_sn']);
                
				if ( ($coupon['card_type'] == 0) || ($coupon['card_type'] == 1) ||($coupon['card_type'] == 4) ) {
				    if ($coupon['card_type'] == 4) {
				        $coupons['content'][$key]['coupon_price'] = $coupons['content'][$key]['coupon_price'] / 10;
				    }
				    $coupons['content'][$key]['goods_info'] = unserialize($coupons['content'][$key]['goods_info']);
				}
				else if ($coupon['card_type'] == 3) {
                    $goods_api = new Purchase_Models_API_Goods();
				    $goods_info = unserialize($coupons['content'][$key]['goods_info']);
				    if (count($goods_info) == 1) {
				        foreach ($goods_info as $goods_sn => $value) {
				            $goods_data = $goods_api->getGoodsInfo(" and goods_sn='$goods_sn'");
				            $coupons['content'][$key]['goods_name'] = $goods_data['goods_name'];
				        }
				    }
				}
				else if ($coupon['card_type'] == 5) {
                    $group_api = new Purchase_Models_API_GroupGoods();
				    $group_info = unserialize($coupons['content'][$key]['goods_info']);
				    if (count($group_info) == 1) {
				        foreach ($group_info as $group_id => $value) {
				            $group_data = array_shift($group_api->get(array('select' => "group_id='$group_id'")));
				            $coupons['content'][$key]['goods_name'] = $group_data['group_goods_name'];
				        }
				    }
				}
			}
		}
		$pageNav = new Custom_Model_PageNav($coupons['total']);
		$this -> view -> coupons = $coupons['content'];
		$this -> view -> curtime = date('Y-m-d');
		$this -> view -> total = $coupons['total'];
		$this -> view -> type = $type;
        $this -> view -> pageNav = $pageNav -> getPageNavigation();
        
        $this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_youhuiquan = ' c ';
                
    }
    
    /**
     * 激活优惠券
     *
     * @return void
     */
    public function activeCouponAction()
    {
       
    }
    

    /**
     * 上传图片类
     */
    public function uploadifyAction ()
    {
        if(empty($_FILES['Filedata']['name'])) die;
        $path = '';
        if( is_file($_FILES['Filedata']['tmp_name']) ) {
            $upload_path = 'upload/avatar';
            $upload = new Custom_Model_Upload('Filedata', $upload_path,$savename = '', $alowexts = 'jpg|jpeg|gif|png', $maxsize = 1024000 );
            $list_upfile = $upload -> up( false );
            $status = true;
            $path = $list_upfile[0]['saveto'];
            $arr=getimagesize($path);
            $strarr=explode("\"",$arr[3]);
            $width=$strarr[1];
            $height=$strarr[3];
            $msg = '';
            if($upload->error()){
                $status = false;
                $path = '';
                $msg = $upload->error();
            }
            echo json_encode(array(
                    'status'=>$status,
                    'msg'=>$msg,
                    'width'=>$width,
                    'height'=>$height,
                    'path'=>$path
            ));
    
            exit;
        }
    
        exit;
    
    }
    /**
     * 修改头像
     */
    public function avatarAction(){
        if ($this -> _request -> isPost()) {
            $this -> _helper -> viewRenderer -> setNoRender();
            $x=floatval($this -> _request -> getParam("x"));
            $y=floatval($this -> _request -> getParam("y"));
            $w=floatval($this -> _request -> getParam("w"));
            $h=floatval($this -> _request -> getParam("h"));
            $bilv=floatval($this -> _request -> getParam("bilv"));
            $pic=$this -> _request -> getParam("pichidden");
            $cmi=new Custom_Model_Image();
            $photo=$cmi->thumbsub($pic, $w*$bilv, $h*$bilv, $type = 2, $pos = 5, $start_x = $x*$bilv, $y*$bilv);
            $result = $this -> _member -> upPhoto($photo);
            if($result=='editMemberSucess'){
                 $this -> _auth -> updateAuth();
                 $actionUrl="http://".$hostList['mainhost']."/auth/download?imgUrl=".$imgUrl;
                 Custom_Model_Message::showAlert(self::EDIT_AVATAER_SUCESS,false, '/member/avatar/');
            }else{
                 Custom_Model_Message::showAlert('error!');
             } 
        } else {
            $this -> view -> ssid = Zend_Session::getId();
            $this -> view -> action = 'avatar';
            $this -> view -> title = '修改头像';
        }
        
        $this->view->nav_1_personal = ' on ';
        $this->view->nav_2_personal_avtar = ' c ';
                
    }

	public function chargeAction(){
		$api_pay  = new Purchase_Models_API_Payment();
		$list = $api_pay->getPays();
		$pays = array();
		foreach ($list as $k => $v) {
			if($v['id'] == 1) continue;
			if($v['is_bank'] == 2) continue;
			$pays[$v['is_bank']][] = $v;
		}
		
        $this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_blance = ' c ';
		$this->view->pays = $pays; 
		
	}
	
	public function chargeOrderDoAction(){
		
		Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);		
		
		$api_pay  = new Purchase_Models_API_Payment();
		$data = $this->_request->getParams();
		
		if(empty($data['pay_id'])) Custom_Model_Tools::ejd('fail','请选择充值方式！');
		$data['amount'] = sprintf("%.2f", floatval($data['amount']));

		$member_info = $this->_memberInfo;
		$data['member_id'] = $member_info['member_id']; 
		
		if(!$id = $api_pay->createMoneyOrder($data)){
			Custom_Model_Tools::ejd('fail','操作异常！');
		}
		
		Custom_Model_Tools::ejd('succ-href','充值单保存成功！','/member/charge-order-confirm/id/'.$id);
		
	}
	
	public function chargeOrderConfirmAction(){
		
		//非空查测
		$order_id = $this->_request->getParam('id');
		if(!$order_id) die('error!');

		$api_pay  = new Purchase_Models_API_Payment();
		
		$order = $api_pay->getMoneyOrder($order_id);
		if(!$order['id']) die('error!');
		
		//只能看自己的确认单
		if($order['member_id'] != $this->_memberInfo['member_id']) die('error!'); 
		$config_status = array('-1'=>'未支付','1'=>'已支付');
		$order['status_name'] = $config_status[$order['status']];
		
		$this->view->order = $order;		
		
		$this -> view -> paymentButton = $this -> _member -> createPayButton($order['pay_id'],$order['order_sn'],$order['amount'],'money_order');		
				
	}
	
	public function amountChargeOrderListAction(){

		$this -> view -> money = $this -> _memberInfo['money'];

		$page = array();
		$page['pn'] = $this->getParam('page',1);
		
		$api_member = new Purchase_Models_API_Member();
		$this->view->list_charge_order = $api_member->getChargeOrdersByPage($page);
		
		$this->view->pagenav = $page['pagenav'];
		
		//导航		
		
        $this->view->nav_1_account = ' on ';
        $this->view->nav_2_account_blance = ' c ';
				
		$this->view->cn_amount_tab_charge_order = ' c ';
		$this->view->cn_first_menu_acount = ' two_on ';
		$this->view->cn_money = ' c ';
		
	}
	
	/**
     * 虚拟商品发货
     *
     * @return void
     */
	public function sendVitualGoodsAction()
	{
	    $batchSN = $this -> getParam('batch_sn', null);
	    if (!$batchSN) {
	        die('no order');
	    }
	    $order = @array_shift($this -> _member -> getOrderBatch($batchSN));
	    if (!$order) {
	        die('no order');
	    }
	    $auth = $this -> _auth -> getAuth();
	    if ($auth['user_id'] != $order['user_id']) {
	        die('no order');
	    }
	    if ($order['status']) {
	        die('order canceled');
	    }
	    if ($order['status_pay'] != 1 && $order['status_pay'] != 2) {
	        die('no payment');
	    }
	    $goodsDatas = @array_shift($this -> _member -> getOrderBatchGoods($batchSN));
        if ($goodsDatas) {
            $vitualGoodsAPI = new Admin_Models_API_VitualGoods();
            if ($vitualGoods = $vitualGoodsAPI -> has($goodsDatas)) {
                if (!$vitualGoodsAPI -> send($order, $vitualGoods)) {
                    die('error');
                }
            }
        }
	    die('ok');
	}
	
}