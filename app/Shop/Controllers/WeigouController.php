<?php
class WeigouController extends Zend_Controller_Action
{
    private $_api = null;
    
	public function init()
    {
		$this -> _api = new Admin_Models_API_Order();
	}
	
    /**
     * 默认页
     *
     * @return   void
     */
    public function indexAction() 
    {
        exit;
    }
    
    /**
     * 验证商品是否可购买
     *
     * @return   void
     */
    public function goodsValidAction() 
    {
        $param = $this -> _request -> getParams();
        
        $this -> addLog(serialize($param), 'goods_valid');
        
        $result = array('header' => array('status' => 0,
                                          'desc' => 'success',
                                         ),
                        'body' => array('support_payment_type' => '10001,10002',
                                        'post_fee' => 10,
                                        'sub_total' => 120,
                                        'discount' => 20,
                                        'payment' => 110,
                                       ),
                       );
        
        echo Zend_Json::encode($result);
        
        exit;
    }
    
    /**
     * 创建订单
     *
     * @return   void
     */
    public function orderCreateAction() 
    {
        $param = $this -> _request -> getParams();
        
        $this -> addLog(serialize($param), 'order_create');
        
        $result = array('header' => array('status' => 0,
                                          'desc' => '',
                                         ),
                        'body' => '',
                       );
        
        echo Zend_Json::encode($result);
        
        exit;
    }
    
    /**
     * 取消订单
     *
     * @return   void
     */
    public function orderCancelAction() 
    {
        $param = $this -> _request -> getParams();
        
        $this -> addLog(serialize($param), 'order_cancel');
        
        $result = array('header' => array('status' => 0,
                                         ),
                        'body' => '',
                       );
        
        echo Zend_Json::encode($result);
        
        exit;
    }
    
    /**
     * 添加日志
     * @param   string  $data
     * @param   string  $type
     * @return   void
     */
    private function addLog($data, $type)
    {
        $filename = $type.'.log';
        
        if (@file_exists($filename)) {
            $content = @file_get_contents($filename);
        }
        $content .= $data.chr(13).chr(10);
        
        @file_put_contents($filename, $content);
    }
    
}