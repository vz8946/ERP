<?php

class PackageController extends Zend_Controller_Action
{
	/**
     * 礼包 API
     * 
     * @var Shop_Models_API_Package
     */
	private $_package = null;
	
	/**
     * 未选择商品
     */
	const NO_SELECT = '请选择商品!';
	
	/**
     * 未正确选择商品
     */
	const NO_SELECT_CORRECT = '请选择正确的商品!';
	
	/**
     * 系统错误
     */
	const SYSTEM_ERROR = '发生错误,请稍后重试!';
	
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
		$this -> _package = new Shop_Models_API_Package();
	}
	
	/**
     * 显示礼包内容
     *
     * @return void
     */
	public function chooseAction()
	{
		if ($this -> _request -> getPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
        	$result = $this -> _package -> addToCart($this -> _request -> getPost());
        	switch ($result) {
        		case 'noSelect':
        		    Custom_Model_Message::showAlert(self::NO_SELECT);
        		    break;
        		case 'noSelectCorrect':
        		    Custom_Model_Message::showAlert(self::NO_SELECT_CORRECT);
        		    break;
        		case 'addToCartSucess':
        		    $this -> _helper -> redirector -> gotoUrl(Zend_Controller_Front::getInstance() -> getBaseUrl() . '/cart');
        		    break;
        		case 'error':
        		    Custom_Model_Message::showAlert(self::SYSTEM_ERROR);
        	}
		} else {
			$offers = $this -> _package -> getPackageById((int)$this -> _request -> getParam('id'));
			
			if (!$offers || ($offers['offers_type'] != 'fixed-package' && $offers['offers_type'] != 'choose-package')) {
				$this -> _helper -> redirector -> gotoUrl(($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Zend_Controller_Front::getInstance() -> getBaseUrl());
			}
			$offersAPI = new Shop_Models_API_Offers();
			$param = is_array($this -> _request -> getParams()) ? array_merge(array('cname' => 'allGoods', 'bid' => 1), $this -> _request -> getParams()) : array('cname' => 'allGoods', 'bid' => 1);
			$param = Custom_Model_DeepTreat::filterArray($param, 'strip_tags');
            $page = (int)$this -> _request -> getParam('page', 1);
            $page = ($page <= 0) ? 1 : $page;
			$goodsMessage = $offersAPI -> getGoods($page, $param, 21);
			$offers['config']['intro'] = Custom_Model_DeepTreat::filterArray($offers['config']['intro'], 'stripslashes');
			$offset = $this -> _request -> getParam('offset', null);
			$offers['expire'] = ($offers['expire'] || $offers['expire'] == '0') ?  $offers['expire'] + 1 : 365;
		    
		    foreach ($goodsMessage['datas'] as $key => $value) {
		        $goodsMessage['datas'][$key]['brief'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $goodsMessage['datas'][$key]['brief']);
		    }
		    
		    $this -> view -> goodsMessage = $goodsMessage['datas'];
			$this -> view -> expire = ($goodsMessage['offers']['expire'] || $goodsMessage['offers']['expire'] == '0') ?  $goodsMessage['offers']['expire'] + 1 : 365;
			$this -> view -> param = $param;
            $this -> view -> id = (int)$this -> _request -> getParam('id');
			
			//if ($offers['offers_type'] == 'choose-package' && count($offers['config']['allGoods']['catDiscount']) == 1) {
			//	$this -> view -> catSelect = $offersAPI -> getGoodsSelect(array('name' => 'cat_id', 'catId' => @array_shift(array_keys($offers['config']['allGoods']['catDiscount'])), 'selected' => $param['cat_id']));
			//}
			$offers['config']['group'] = $offers['config']['group'] ? $offers['config']['group'] : 1;
			$bid = (int)$this -> _request -> getParam('bid', 1);
			for ($i=0; $i < $offers['config']['group']; $i++) {
				$group[] = $i;
			}
			$totalNum = 0;
			if ($offers['offers_type'] == 'fixed-package') {
			    $offers['config']['number'] = $offers['config']['allNums'][$bid-1];
			    $offers['config']['isRepeatGoods'] = $offers['config']['isRepeatGoods'][$bid-1];
			    for ($i = 0; $i < count($offers['config']['allNums']); $i++) {
			        $totalNum += $offers['config']['allNums'][$i];
			    }
			    for ( $i = 0; $i < count($offers['config']['allNums']); $i++) {
			        $number = array();
    			    for ( $j = 1; $j <= $offers['config']['allNums'][$i]; $j++ ) {
    			        $number[] = $j;
    			    }
    			    $allNums[$i+1] = $number;
    			}
			}
			else {
			    $totalNum = $offers['config']['number'];
			    for ( $i = 1; $i <= $offers['config']['number']; $i++) {
    			    $number[] = $i;
    			}
    			$allNums[1] = $number;
			}
            
            $datearr = explode(' ', $offers['to_date']);
            $timearr = explode(':', $datearr[1]);
            $datearr = explode('-', $datearr[0]);
            $to_date = mktime($timearr[0], $timearr[1], $timearr[2], $datearr[1], $datearr[2], $datearr[0]);
            $offers['to_date'] = date('m/d/Y H:i:s', $to_date);
            
			$this -> view -> group = $group;
			$this -> view -> number = $allNums;
			$this -> view -> totalNum = $totalNum;
			$this -> view -> package = $offers;
			$this -> view -> packageGoods = $this -> _package -> getPackageGoods($offers['offers_id'], $offset, $this -> _request -> getParam('bid'), $page);
			$this -> view -> offset = $offset;
			$pageNav = new Custom_Model_PageNav($goodsMessage['total'], 21);
			$this -> view -> pageNav = $pageNav -> getPageNavigation();
			$this -> view -> ur_here = "<a href='/'>首页</a> <code>&gt;</code> ".$offers['offers_name'] ;
			
			$this -> view -> page_title = " 垦丰电商 -快乐自助购 ";
		}
	}
    
    /**
     * 验证礼包信息并生成礼包cookie
     *
     * @return void
     */
    public function checkPackageAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $offersId = $this -> _request -> getParam('offers_id', null);
        $offset = $this -> _request -> getParam('offset', null);
        $mixId = $this -> _request -> getParam('mix_id', null);
        if ($_COOKIE['package_' . $offersId]) {
    		$error = $this -> _package -> ifGoodsByCookie($offersId, $mixId);
    		
    		if ($error) {
    			echo $error;
    		} else {
    			$this -> _package -> setPackageCartCookie($offersId, $offset);
    		}
    	}
        exit;
    }
    /**
     * 礼包选择商品
     *
     * @return void
     */
    public function goodsAction()
    {
    	Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
        $id = $this -> _request -> getParam('id', null);
        
        if ($id) {
    		$result = $this -> _package -> getGoodsById($id);
            $this -> view -> data = $result['data'];
    	}
    }
}