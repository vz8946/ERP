<?php
class Custom_Model_Shop_Alibaba extends Custom_Model_Shop_Base
{
	/**
     * 构造函数
     *
     * @param   void
     * @return  void
     */
	public function __construct($config)
	{
		parent::__construct($config);
	}
	
	/**
     * 生成店铺类型特殊字段
     *
     * @param   int     $shopID
     * @return  void
     */
	public function getConfigField() {
	    return array('session' => '会话ID',
	                 'id' => '账号',
	                 'key' => '密钥',
	                );
	}
	
	/**
     * 同步商品
     *
     * @return  void
     */
    public function syncGoods() 
    {
        $params = array('type' => 'Sale',
                        'returnFields' => '');
        $this -> createURL('offer.getAllOfferList', $params);
    }
    
    /**
     * 生成请求URL
     *
     * @return  string
     */
	private function createURL($method, $params)
	{
	    $sign = $this -> createSign($method, $params);
	    $url = "http://gw.api.alibaba.com/openapi/param2/1/cn.alibaba.open/{$method}/{$this -> _config['id']}?access_token={$this -> _config['session']}&_aop_signature={$sign}";
	    if ($params) {
	        foreach ($params as $key => $value) {
	            $url .= "&{$key}={$value}";
	        }
	    }
	    die($url);
	}
	
	/**
     * 生成签名
     *
     * @return  string
     */
	private function createSign($method, $params)
	{
        $sign = "param2/1/{$method}/currentTime/{$this -> _config['id']}";
        if ($params) {
            ksort($params);
            foreach ($params as $key => $value) {
                $sign .= $key.$value;
            }
        }
        
        return strtoupper(bin2hex(hash_hmac("sha1", $sign, $this -> _config['key'], true)));
	}
    
}
