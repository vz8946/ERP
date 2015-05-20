<?php
class CustomerController extends Zend_Controller_Action
{
    private $_api;
    private $_page_size = 20;
	/**
     * 对象初始化
     *
     * @return void
     */
	public function init()
	{
        Zend_Controller_Front::getInstance() -> unregisterPlugin(Custom_Controller_Plugin_Layout);
	    $this -> _helper -> viewRenderer -> setNoRender();
        $this->_api = new Api_Models_Customer();
        $this->_member_api = new Api_Models_Member();
	}
		
	/**
     * 根据条件获取客户信息
     *
     * @return void
     */
	public function getCustomerAction()
	{
        $page = (int)$this ->_request->getParam('page', 1);
        $params = $this->_request->getParams();
        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }
        if (false === $infos['count']  = $this->_api->getCount($params)) {
            exit(json_encode($this->_api->getError()));
        }
        !empty($params['page_size']) && $this->_page_size = $params['page_size'];
        if ($infos['count'] > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;

			$infos['data'] = $this->_api->browse($params, $limit);
		}
        exit(json_encode($infos));
	}

    /**
     * 根据条件获取客户信息
     *
     * @return void
     */
     public function updateCustomerStatusAction()
     {
        $params = $this->_request->getParams();
        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }
        if (!$this->_request->isPost()) {
            exit(json_encode(array('error' => '非法提交')));
        }
        if (false === $this->_api->updateCustomerInfosByCustomerId($params)) {
            exit(json_encode($this->_api->getError()));
        }
        exit(json_encode(array('status' => '1', 'message' => '更新成功')));
     }

     /**
     * 根据条件获取会员信息
     *
     * @return void
     */
	public function getMemberAction()
	{
        $page = (int)$this ->_request->getParam('page', 1);
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }
        if (false === $infos['count']  = $this->_member_api->getCount($params)) {
            exit(json_encode($this->_member_api->getError()));
        }
        !empty($params['page_size']) && $this->_page_size = $params['page_size'];
        if ($infos['count'] > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;

			if(false === $infos['data'] = $this->_member_api->browse($params, $limit)) {
                exit(json_encode($this->_member_api->getError()));
            }
		}
        exit(json_encode($infos));
	}

    /**
     * 根据条件更新会员同步状态
     *
     * @return void
     */
     public function updateMemberStatusAction()
     {
        $params = $this->_request->getParams();
        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }
        if (!$this->_request->isPost()) {
            exit(json_encode(array('error' => '非法提交')));
        }
        if (false === $this->_member_api->updateMemberInfosByMemberIds($params)) {
            exit(json_encode($this->_member_api->getError()));
        }
        exit(json_encode(array('status' => '1', 'message' => '更新成功')));
     }
    /**
     * 根据条件获取会员登陆信息
     *
     * @return void
     */
	public function getMemberLoginAction()
	{
        $this -> _helper -> viewRenderer -> setNoRender();
        $page = (int)$this ->_request->getParam('page', 1);
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }


        if (false === $infos['count']  = $this->_member_api->getLoginCount($params)) {
            exit(json_encode($this->_member_api->getError()));
        }

        !empty($params['page_size']) && $this->_page_size = $params['page_size'];
        if ($infos['count'] > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;

			$infos['data'] = $this->_member_api->browseLogin($params, $limit);
		}
        exit(json_encode($infos));
	}

    /**
     * 根据条件更新会员登陆状态
     *
     * @return void
     */
     public function updateMemberLoginStatusAction()
     {
        $this -> _helper -> viewRenderer -> setNoRender();
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }

        if (!$this->_request->isPost()) {
            exit(json_encode(array('error' => '非法提交')));
        }

        if (false === $this->_member_api->updateMemberLoginInfosByLogIds($params)) {
            exit(json_encode($this->_member_api->getError()));
        }

        exit(json_encode(array('status' => '1', 'message' => '更新成功')));
     }

     /**
     * 根据条件获取会员收藏
     *
     * @return void
     */
	public function getMemberFavoriteAction()
	{
        $this -> _helper -> viewRenderer -> setNoRender();
        $page = (int)$this ->_request->getParam('page', 1);
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }


        if (false === ($infos['count']  = $this->_member_api->getFavoriteCount($params))) {
            exit(json_encode($this->_member_api->getError()));
        }

        !empty($params['page_size']) && $this->_page_size = $params['page_size'];
        if ($infos['count'] > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;

			if (false === $infos['data'] = $this->_member_api->browseFavorite($params, $limit)) {
                exit(json_encode($this->_member_api->getError()));
            }
		}
        exit(json_encode($infos));
	}

    /**
     * 根据条件更新会员收藏状态
     *
     * @return void
     */
     public function updateMemberFavoriteStatusAction()
     {
        $this -> _helper -> viewRenderer -> setNoRender();
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }
        
        if (!$this->_request->isPost()) {
            exit(json_encode(array('error' => '非法提交')));
        }

        if (false === $this->_member_api->updateMemberFavoriteInfosByFavoriteIds($params)) {
            exit(json_encode($this->_member_api->getError()));
        }

        exit(json_encode(array('status' => '1', 'message' => '更新成功')));
     }

     /**
     * 根据条件获取会员积分
     *
     * @return void
     */
	public function getMemberPointAction()
	{
        $this -> _helper -> viewRenderer -> setNoRender();
        $page = (int)$this ->_request->getParam('page', 1);
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }


        if (false === ($infos['count']  = $this->_member_api->getPointCount($params))) {
            exit(json_encode($this->_member_api->getError()));
        }

        !empty($params['page_size']) && $this->_page_size = $params['page_size'];
        if ($infos['count'] > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;

			if (false === $infos['data'] = $this->_member_api->browsePoint($params, $limit)) {
                exit(json_encode($this->_member_api->getError()));
            }
		}
        exit(json_encode($infos));
	}

    /**
     * 根据条件更新会员积分状态
     *
     * @return void
     */
     public function updateMemberPointStatusAction()
     {
        $this -> _helper -> viewRenderer -> setNoRender();
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }
        
        if (!$this->_request->isPost()) {
            exit(json_encode(array('error' => '非法提交')));
        }

        if (false === $this->_member_api->updateMemberPointInfosByPointIds($params)) {
            exit(json_encode($this->_member_api->getError()));
        }

        exit(json_encode(array('status' => '1', 'message' => '更新成功')));
     }

    /**
    * 根据条件获取礼品卡
    *
    * @return void
    */
	public function getGiftAction()
	{
        $this -> _helper -> viewRenderer -> setNoRender();
        $page = (int)$this ->_request->getParam('page', 1);
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }


        if (false === ($infos['count']  = $this->_member_api->getGiftCount($params))) {
            exit(json_encode($this->_member_api->getError()));
        }

        !empty($params['page_size']) && $this->_page_size = $params['page_size'];
        if ($infos['count'] > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;

			if (false === $infos['data'] = $this->_member_api->browseGift($params, $limit)) {
                exit(json_encode($this->_member_api->getError()));
            }
		}
        exit(json_encode($infos));
	}

    /**
     * 根据条件更新礼品卡同步状态
     *
     * @return void
     */
     public function updateGiftStatusAction()
     {
        $this -> _helper -> viewRenderer -> setNoRender();
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }
        
        if (!$this->_request->isPost()) {
            exit(json_encode(array('error' => '非法提交')));
        }

        if (false === $this->_member_api->updateGiftInfosByCardIds($params)) {
            exit(json_encode($this->_member_api->getError()));
        }

        exit(json_encode(array('status' => '1', 'message' => '更新成功')));
     }

     /**
    * 根据条件获取会员登录记录
    *
    * @return void
    */
	public function getMemberRankAction()
	{
        $this -> _helper -> viewRenderer -> setNoRender();
        $page = (int)$this ->_request->getParam('page', 1);
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }


        if (false === ($infos['count']  = $this->_member_api->getMemberRankCount($params))) {
            exit(json_encode($this->_member_api->getError()));
        }

        !empty($params['page_size']) && $this->_page_size = $params['page_size'];
        if ($infos['count'] > 0) {
			$limit = ($page - 1) * $this->_page_size . ','. $this->_page_size;

			if (false === $infos['data'] = $this->_member_api->browseMemberRank($params, $limit)) {
                exit(json_encode($this->_member_api->getError()));
            }
		}
        exit(json_encode($infos));
	}

    /**
     * 根据条件更新会员等级记录同步状态
     *
     * @return void
     */
     public function updateMemberRankStatusAction()
     {
        $this -> _helper -> viewRenderer -> setNoRender();
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }
        
        if (!$this->_request->isPost()) {
            exit(json_encode(array('error' => '非法提交')));
        }

        if (false === $this->_member_api->updateMemberRankInfosByLogIds($params)) {
            exit(json_encode($this->_member_api->getError()));
        }

        exit(json_encode(array('status' => '1', 'message' => '更新成功')));
     }

     public function __call($params, $arg1)
     {
        exit(json_encode(array('error' => '没有该请求方法')));
     }
}