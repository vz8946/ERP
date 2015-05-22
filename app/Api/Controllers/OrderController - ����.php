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
		
	/**
     * 根据条件获取订单信息
     *
     * @return void
     */
	public function getOrderAction()
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

			$infos['data'] = $this->_api->browse($params, $limit);
		}
        exit(json_encode($infos));
	}

    /**
     * 根据订单ID获取商品数据
     *
     * @return void
     */
    public function getOrderGoodsAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $params = $this->_request->getParams();

        if (false === Custom_Model_Certify::certify_valid($params)) {
            exit(json_encode(Custom_Model_Certify::getError()));
        }
 
        $order_batch_id = $params['order_batch_id'];
        $infos =  $this->_api->getOrderGoodsByBatchId($order_batch_id);

        if (false === $infos) {
            exit(json_encode($this->_api->getError()));
        }
        exit(json_encode($infos));
    }

    

}