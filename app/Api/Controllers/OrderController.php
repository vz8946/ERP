<?php
class OrderController extends Zend_Controller_Action
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
        $this->_api = new Api_Models_Order();
	}
	public function ttttAction(){
		echo 12123;die;
	}	
	/**
     * 根据条件获取产品信息
     *
     * @return void
     */
	public function getOrderListAction()
	{
        $this -> _helper -> viewRenderer -> setNoRender();
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

			if (false === $infos['data'] = $this->_api->browse($params, $limit)) {
                exit(json_encode($this->_api->getError()));
            }
		}
        exit(json_encode($infos));
	}

    /**
     * 根据条件更新产品信息
     *
     * @return void
     */
     public function updateOrderStatusAction()
     {
        $this -> _helper -> viewRenderer -> setNoRender();
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }

        if (!$this->_request->isPost()) {
            exit(json_encode(array('error' => '非法提交')));
        }


        if (false === $this->_api->updateOrderListsByBatchIds($params)) {
            exit(json_encode($this->_api->getError()));
        }

        exit(json_encode(array('status' => '1', 'message' => '更新成功')));
     }

     public function __call($params, $arg1)
     {
        exit(json_encode(array('error' => '没有该请求方法')));
     }
}