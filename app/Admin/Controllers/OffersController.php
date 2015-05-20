<?php
class Admin_OffersController extends Zend_Controller_Action
{
	/**
     * 会员等级 API
     *
     * @var Admin_Models_API_MemberRank
     */
	private $_rank = null;

	/**
     * 促销活动 API
     *
     * @var Admin_Models_API_Offers
     */
	private $_offers = null;

	/**
     * 是否可使用礼券
     *
     * @var array
     */
	private $_canUseCoupon = array('可以', '不可以');

	/**
     * 未填写促销活动名称
     */
	const NO_OFFERS_NAME = '请填写活动名称!';

	/**
     * 未选择活动类型
     */
	const NO_OFFERS_TYPE = '请选择活动类型';

	/**
     * 日期范围错误
     */
	const ERROR_DATE_RANGE = '请正确选择日期范围!';

	/**
     * 未选择开始日期
     */
	const NO_FROM_DATE = '请选择开始日期!';

	/**
     * 已经有此名称的促销活动
     */
	const OFFERS_EXISTS = '该活动名称已经存在!';

	/**
     * 添加促销活动成功
     */
	const ADD_OFFERS_SUCESS = '添加促销活动成功!';

	/**
     * 促销活动不存在
     */
	const OFFERS_NO_EXISTS = '该促销活动不存在!';

	/**
     * 编辑促销活动成功
     */
	const EDIT_OFFERS_SUCESS = '编辑促销活动成功!';

	/**
     * 商品不能重复
     */
	const SAME_GOODS = '商品不能重复!';

    /**
     * 对象初始化
     *
     * @return   void
     */
	public function init()
	{
		$this -> _offers = new Admin_Models_API_Offers();
	}

	/**
     * 促销活动列表
     *
     * @return   void
     */
	public function indexAction()
	{
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();

		$page = (int)$this -> _request -> getParam('page', 1);
		$search = $this -> _request -> getParams();
		/*
		if ( !isset($search['search_time_type']) ) {
		    $search['search_time_type'] = array('0', '1');
		}
		*/
        $offers = $this -> _offers -> getAllOffers($page, null, $search);
        $this -> _offers -> cacheOffersFile(); //刷新插件下拉列表
        $offersTypes = $this -> _offers -> getAllOffersName();

        if (is_array($offers['content'])) {
        	foreach ($offers['content'] as $num => $value)
            {
            	$offers['content'][$num]['offers_type'] = $offersTypes[$offers['content'][$num]['offers_type']];
            	$offers['content'][$num]['use_coupon'] = $this -> _offers -> ajaxCoupon($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('coupon'), $offers['content'][$num]['offers_id'], $offers['content'][$num]['use_coupon']);
        	    $offers['content'][$num]['status'] = $this -> _offers -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $offers['content'][$num]['offers_id'], $offers['content'][$num]['status']);
            }
        }
        $this -> view -> offersTypes = $offersTypes;
        $this -> view -> offersList = $offers['content'];
        for ( $i = 0; $i < count($search['search_time_type']); $i++ ) {
            $search_time_type[$search['search_time_type'][$i]] = 1;
        }
        $search['search_time_type']  = $search_time_type;
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($offers['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
	}

	/**
     * 添加促销活动
     *
     * @return   void
     */
	public function addAction()
	{
		$type = $this -> _request -> getParam('type', null);

		if ($this -> _request -> isPost()) {
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$result = $this -> _offers -> editOffers($this -> _request -> getPost(), null, $this -> view);
        	switch ($result) {
        		case 'noOffersName':
        		    Custom_Model_Message::showMessage(self::NO_OFFERS_NAME);
        		    break;
        		case 'noOffersType':
        		    Custom_Model_Message::showMessage(self::NO_OFFERS_TYPE);
        		    break;
        		case 'errorDateRange':
        		    Custom_Model_Message::showMessage(self::ERROR_DATE_RANGE);
        		    break;
        		case 'noFromDate':
        		    Custom_Model_Message::showMessage(self::NO_FROM_DATE);
        		    break;
        		case 'offersExists':
        		    Custom_Model_Message::showMessage(self::OFFERS_EXISTS);
        		    break;
        		case 'addOffersSucess':
        		    Custom_Model_Message::showMessage(self::ADD_OFFERS_SUCESS, '', 1250, "Gurl()");
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!');
        	}
        } else {
            /*
        	$ranks = $this -> _rank -> getAllRank();
		    foreach ($ranks as $key => $value)
    	    {
    		$memberRanks[$value['rank_id']] = $value['rank_name'];
    	    }
    	    $this -> view -> memberRanks = $memberRanks;
            */
        	$this -> view -> action = 'add';
        	$this -> view -> title = '添加活动';
		    $this -> view -> offersType = $type;
		    $this -> view -> canUseCoupon = $this -> _canUseCoupon;
		    $this -> view -> offers = array('use_coupon' => 0);
		    $this -> render('edit');
		}
	}

	/**
     * 修改促销活动
     *
     * @return   void
     */
	public function editAction()
	{
		$id = (int)$this -> _request -> getParam('id', null);

        if ($id > 0) {
        	
            if ($this -> _request -> isPost()) {
            	$this -> _helper -> viewRenderer -> setNoRender();
                $result = $this -> _offers -> editOffers($this -> _request -> getPost(), $id, $this -> view);
                switch ($result) {
        		    case 'noOffersName':
        		        Custom_Model_Message::showMessage(self::NO_OFFERS_NAME);
        		        break;
        		    case 'noOffersType':
        		        Custom_Model_Message::showMessage(self::NO_OFFERS_TYPE);
        		        break;
        		    case 'errorDateRange':
        		        Custom_Model_Message::showMessage(self::ERROR_DATE_RANGE);
        		        break;
        		    case 'noFromDate':
        		        Custom_Model_Message::showMessage(self::NO_FROM_DATE);
        		        break;
        		    case 'offersExists':
        		        Custom_Model_Message::showMessage(self::OFFERS_EXISTS);
        		        break;
        		    case 'offersNoExists':
        		        Custom_Model_Message::showMessage(self::OFFERS_NO_EXISTS);
        		        break;
        		    case 'sameGoods':
        		        Custom_Model_Message::showMessage(self::SAME_GOODS);
        		        break;
        		    case 'editOffersSucess':
        		        Custom_Model_Message::showMessage(self::EDIT_OFFERS_SUCESS, '', 1250, 'Gurl()');
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showMessage('error!');
        	    }
            } else {
                /*
            	$ranks = $this -> _rank -> getAllRank();
		        foreach ($ranks as $key => $value)
    	        {
    		        $memberRanks[$value['rank_id']] = $value['rank_name'];
    	        }
    	        $this -> view -> memberRanks = $memberRanks;
                */
            	$offers = $this -> _offers -> getOffersById($id);
            	$offers['offers_rank'] && $offers['offers_rank'] = explode(',', $offers['offers_rank']);
            	$offers['config'] = Custom_Model_DeepTreat::filterArray(unserialize($offers['config']), 'stripslashes');
            	
            	
            	$this -> view -> action = 'edit';
            	$this -> view -> offers = $offers;
            	$this -> view -> offersType = $offers['offers_type'];
            	$this -> view -> canUseCoupon = $this -> _canUseCoupon;
            	$this -> view -> title = '编辑活动';
            }
        }else{
            Custom_Model_Message::showMessage('error!');
        }
	}

	/**
     * 所有商品选择列表
     *
     * @return void
     */
    public function selectAllGoodsAction()
    {
    	$offers = $this -> _offers -> getOffersById($this -> _request -> getParam('id'));
    	$intype = $this -> _request -> getParam('intype');
    	$offersType = $this -> _request -> getParam('offersType');
    	$discountInput = $this -> _request -> getParam('discountinput');
    	$discountValue = $this -> _request -> getParam('discountvalue');

		$show_limit = 0;
		$offer_types = array('exclusive', 'fixed', 'price-exclusive');
		if (in_array($offersType, $offer_types)) {
			$show_limit = 1;
		}
    	$this -> view -> intype = $intype;
    	$this -> view -> offersType = $offersType;
    	$this -> view -> discountinput = $discountInput;
    	$this -> view -> discountvalue = $offers['config'][$discountInput];
    	$offers['config'] = unserialize($offers['config']);
        $offers['config'][$discountInput] = $this -> _offers -> parseConfig($this -> _request -> getParam('discountvalue'));
    	$this -> view -> discount = $offers['config'][$discountInput]['discount'];
    	/*
    	if ($offersType != 'fixed' && $offersType != 'exclusive' && $offersType != 'price-exclusive') {
            $_cat = new Admin_Models_API_Category();
    		$method = 'build' . ucfirst($intype);
        	$this -> view -> goodsCatInput = $_cat -> $method(array('name' => 'catDiscount', 'value' => $offers['config'][$discountInput]['catDiscount']));
    	}
    	*/
        if ($offers['config'][$discountInput]['goodsDiscount']) {
            $this -> view -> goodsDiscount = $this -> _offers -> getDiscountGoods($offers['config'][$discountInput]['goodsDiscount']);
        }

		$this->view->show_limit = $show_limit;
    }

    /**
     * 所有商品选择列表
     *
     * @return void
     */
    public function selectAllGroupGoodsAction()
    {
    	$offers = $this -> _offers -> getOffersById($this -> _request -> getParam('id'));
    	$intype = $this -> _request -> getParam('intype');
    	$offersType = $this -> _request -> getParam('offersType');
    	$discountInput = $this -> _request -> getParam('discountinput');
    	$discountValue = $this -> _request -> getParam('discountvalue');

		$show_limit = 0;
		$offer_types = array('exclusive', 'fixed', 'group-exclusive');
		if (in_array($offersType, $offer_types)) {
			$show_limit = 1;
		}

    	$this -> view -> intype = $intype;
    	$this -> view -> offersType = $offersType;
    	$this -> view -> discountinput = $discountInput;
    	$this -> view -> discountvalue = $offers['config'][$discountInput];
    	$offers['config'] = unserialize($offers['config']);
        $offers['config'][$discountInput] = $this -> _offers -> parseConfig($this -> _request -> getParam('discountvalue'));
    	$this -> view -> discount = $offers['config'][$discountInput]['discount'];

        if ($offers['config'][$discountInput]['goodsDiscount']) {
            $this -> view -> goodsDiscount = $this -> _offers -> getDiscountGroupGoods($offers['config'][$discountInput]['goodsDiscount']);
        }

		$this->view->show_limit = $show_limit;
    }

	/**
     * 删除促销活动
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _offers -> deleteOffersById($id);
            switch ($result) {
            	case 'deleteSucess':
        		    break;
        		case 'error':
        		    exit('error!');
            }
        } else {
            exit('error!');
        }
    }

	/**
     * 更改状态
     *
     * @return void
     */
    public function statusAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);

    	if ($id > 0) {
	        $this -> _offers -> changeStatus($id, $status);
        } else {
            Custom_Model_Message::showMessage('error!');
        }

        echo $this -> _offers -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $id, $status);
    }

    /**
     * 更改是否可使用礼券
     *
     * @return void
     */
    public function couponAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$status = (int)$this -> _request -> getParam('status', 0);

    	if ($id > 0) {
	        $this -> _offers -> changeCoupon($id, $status);
        } else {
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _offers -> ajaxCoupon($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('coupon'), $id, $status);
    }

    /**
     * 检查活动是否存在
     *
     * @return void
     */
    public function checkAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $name = $this -> _request -> getParam('val', null);

        if(!empty($name)){
	        $result = $this -> _offers -> getOffersByName($name);
	        if (!empty($result)) {
	        	exit(self::OFFERS_EXISTS);
	        }
        }
    }

    /**
     * 商品选择列表
     *
     * @return void
     */
    public function selectGoodsAction()
    {
    	$intype = $this -> _request -> getParam('intype');
    	$offersType = $this -> _request -> getParam('offersType');
        $page = (int)$this -> _request -> getParam('page', 1);
        $param = $this -> _request -> getParams();
        $param['offersType'] = $this -> _request -> getParam('offersType');
        $goodsMessage = $this -> _offers -> getGoods($page, 20, $param);
        $pageNav = new Custom_Model_PageNav($goodsMessage['total'], 20, 'ajax_search');
        $_cat = new Admin_Models_API_Category();

        $this -> view -> goodsMessage = $goodsMessage['content'];
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> catSelect = $_cat -> buildSelect(array('name' => 'view_cat_id'));
        $this -> view -> intype = $intype;
        $this -> view -> index = (int)$this -> _request -> getParam('index', 0);
		$this->view->offersType = $offersType;
    }

    /**
     * 组合商品选择列表
     *
     * @return void
     */
    public function selectGroupGoodsAction()
    {
    	$intype = $this -> _request -> getParam('intype');
    	$offersType = $this -> _request -> getParam('offersType');
        $page = (int)$this -> _request -> getParam('page', 1);
        $param = $this -> _request -> getParams();
        $param['offersType'] = $this -> _request -> getParam('offersType');
        $goodsMessage = $this -> _offers -> getGroupGoods($page, 20, $param);
        $pageNav = new Custom_Model_PageNav($goodsMessage['total'], 20, 'ajax_search');
        $this -> view -> goodsMessage = $goodsMessage['content'];
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> intype = $intype;
		$this->view->offersType = $offersType;
    }

    /**
     * 生成活动缓存文件
     *
     * @return void
     */
    public function cacheAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$this -> _offers -> cacheOffersFile();
    }

    /**
     * 修改优先级
     *
     * @return void
     */
    public function changeOrderAction()
    {
    	$id = (int)$this -> _request -> getParam('id');
    	$order = (int)$this -> _request -> getParam('order');
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$this -> _offers -> changeOrder($id, $order);
    }


    /**
     * 订单活动使用情况查询
     *
     * 活动插件订单查询
     * 筛选条件： 活动ID 活动插件类型 下单起止时间  订单状态
     * 列表显示  活动ID  活动名 活动类型  活动订单数
     *
     * @return void
     */
    public function analysisAction()
    {
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();

        $page = (int)$this -> _request -> getParam('page', 1);
		$search = $this -> _request -> getParams();
		if ($search['dosearch']) {
            $offers = $this -> _offers -> getAllOffers($page, null, $search);
            if ($offers['total'] > 0) {
                for ( $i = 0; $i < count($offers['content']); $i++ ) {
                    $offer_ids[] = $offers['content'][$i]['offers_id'];
                }
                $count_data = $this -> _offers -> getOfferOrderCount($offer_ids, $search);
                if ($count_data) {
                    for ( $i = 0; $i < count($count_data); $i++ ) {
                        $offer_order_count[$count_data[$i]['offers_id']] = $count_data[$i]['num'];
                    }
                }
            }
        }

        $offersTypes = $this -> _offers -> getAllOffersName();

        if (is_array($offers['content'])) {
        	foreach ($offers['content'] as $num => $value)
            {
            	$offers['content'][$num]['offers_type'] = $offersTypes[$offers['content'][$num]['offers_type']];
        	    $offers['content'][$num]['status'] = $offers['content'][$num]['status'] ? '正常' : '<font color=red>冻结</red>';
        	    $offers['content'][$num]['from_date'] = substr( $offers['content'][$num]['from_date'], 0, 10);
        	    $offers['content'][$num]['to_date'] = substr( $offers['content'][$num]['to_date'], 0, 10);
        	    $offers['content'][$num]['order_num'] = $offer_order_count[$offers['content'][$num]['offers_id']] ? $offer_order_count[$offers['content'][$num]['offers_id']] : 0;
            }
        }

        $this -> view -> offersTypes = $offersTypes;
        $this -> view -> offersList = $offers['content'];
        $this -> view -> param = $search;
        $pageNav = new Custom_Model_PageNav($offers['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }

}