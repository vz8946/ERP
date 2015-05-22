<?php
/**
 * 全局设置参数设置
 *
 * @category Taoapi
 * @package Taoapi_Config
 * @copyright Copyright (c) 2008-2010 Taoapi (http://www.Taoapi.com)
 * @license    http://www.Taoapi.com
 * @version    Id: Taoapi_Config  2010-02-22 15:36:47 Arvin 
 */
class Custom_Model_Taoapi_Config
{
    //存放全局参数
    private $_Config;

    /**
     * @var  Taoapi_Config
     */
    private static $_init;
    
    private function __construct()
    {
		$this->_Config['Charset'] = 'UTF-8';

		//设置数据环境
		//true 测试环境 false 正式环境
		$this->_Config['TestMode'] = false;
		//您的appKey和appSecret 支持多个appKey
		//填写的格式是：
		/**
		$this->_Config['AppKey'] = array(
		"12004444"=>"adfoa3adfa3adfadfldsf"
		);
		如果是多个APPKEY的话：
		$this->_Config['AppKey'] = array(
		"12004444"=>"adfoa3adfa3adfadfldsf",
		"12004445"=>"adfoa3adfa3adfadfldsf",
		"12004446"=>"adfoa3adfa3adfadfldsf" //最后一个APPKEY的后面不要加逗号
		);
		**/
		$this->_Config['AppKey'] = array(
					'12267329'=>'sandboxa8862ded147fdf375ac580426',
		);

		//当appKey不只一个时,API次数超限后自动启用下一个APPKEY
		//false:关闭 true:开启
		$this->_Config['AppKeyAuto'] = false;

		//设置API版本,1 表示1.0 2表示2.0
		$this->_Config['Version'] = 2;

		//设置sign加密方式,支持 md5 和 hmac 
		//版本2.0时才可以使用 hmac
		$this->_Config['SignMode'] = 'md5';

		//显示或关闭错语提示,
		//true:关闭 false:开启
		$this->_Config['CloseError'] = false;

		//开启或关闭API调用日志功能,开启后可以查看到每天APPKEY调用的次数以及调用的API
		//false:关闭 true:开启
		$this->_Config['ApiLog'] = false;

		//开启或关闭错误日志功能
		//false:关闭 true:开启
		$this->_Config['Errorlog'] = false;

		//设置API读取失败时重试的次数,可以提高API的稳定性,默认为3次
		$this->_Config['RestNumberic'] = 0;

		//设置数据缓存的时间,单位:小时;0表示不缓存
		$this->_Config['Cache'] = 0;

		//设置缓存保存的目录
		$this->_Config['CachePath'] = dirname(__FILE__).'/Apicache';

		//自动清除过期缓存的时间间隔，
		//格式是：* * * *
		//其中第一个数表示分钟，第二个数表示小时，第三个数表示日期，第四个数表示月份
		//多个之间可以用半角分号隔开
		//示例：
		//要求每天早上的8点1分清除缓存，格式是：1 8 * *
		//要求每个月的1号晚上12点5分清除缓存，格式是：5 12 1 *
		//要求每隔5天就在上午10点3分清除缓存，格式是：3 10 1,5,10,15,20,25 *
		//如果设为0或格式不对将不开启此功能
		//缓存清除操作每天只会执行一次
		$this->_Config['ClearCache'] = "* 9 1,5,10,15,20,25 *"; //默认为每隔5天在上午9点-10点之间进行自动缓存清除

		//每次调用API后自动清除原有传入参数
		//false:关闭 true:开启
		$this->_Config['AutoRestParam'] = false;
		$this->setTestMode($this->_Config['TestMode']);
		$this->_Config['PostMode'] = array('GET' => 'getSend' , 'POST' => 'postSend' , 'POSTIMG' => 'postImageSend');
    }

    /**
     * @return Taoapi_Config
     */
    public static function Init ()
    {
        if (! self::$_init) {
            self::$_init = new Custom_Model_Taoapi_Config();
        }
        return self::$_init;
    }

    /**
	 * 设置数据环境: true 测试环境 false 正式环境
	 * @param bool $test
     * @return Taoapi_Config
     */
    public function setTestMode ($test = true)
    {
 		if($test)
 		{
 		    $this->_Config['Container'] = 'http://container.api.tbsandbox.com/container';
 			$this->_Config['Url'] = 'http://gw.api.tbsandbox.com/router/rest';
 		}else{
 		    $this->_Config['Url'] = 'http://gw.api.taobao.com/router/rest';
            $this->_Config['Container'] = 'http://container.api.taobao.com/container';
 			//$this->_Config['Url'] = 'http://110.75.24.82/router/rest';
 		    //$this->_Config['Container'] = 'http://110.75.26.47/container';
 		}
        return $this;
    }

    
    /**
     * 设置获取数据的编码. 支持UTF-8 GBK GB2312 
     * 需要 iconv或mb_convert_encoding 函数支持
     * UTF-8 不可写成UTF8
     * @param string $Charset
     * @return Taoapi_Config
     */
	public function setCharset($Charset)
	{
 		$this->_Config['Charset'] = $Charset;

        return $this;
	}
    
    /**
     * 设置appKey
     * 
     * @param int $key
     * @return Taoapi_Config
     */
    public function setAppKey ($key)
    {
        if(is_array($key))
        {
            $this->_Config['AppKey'] = $key;
        }else{
            $this->_Config['AppKey'][$key] = 0;
        }

        return $this;
    }

    /**
     * 设置appSecret
     * 
     * @param string $Secret
     * @return Taoapi_Config
     */
    public function setAppSecret ($Secret)
    {
		$key = array_search('0',$this->_Config['AppKey']);

		if($key)
		{
			$this->_Config['AppKey'][$key] = $Secret;
		}

        return $this;
    }
    
    /**
     * 当appKey不只一个时,API次数超限后自动启用下一个APPKEY
     * 
     * @param bool $Secret
     * @return Taoapi_Config
     */
    public function setAppKeyAuto ($AppKeyAuto)
    {
        $this->_Config['AppKeyAuto'] = (bool)$AppKeyAuto;

        return $this;
    }    
	
    /**
     * 设置API版本,1 表示1.0 2表示2.0 
     * 设置sign加密方式,支持 md5 和 hmac
     * 
     * @param int $version
     * @param string $signmode
     * @return Taoapi_Config
     */
    public function setVersion ($version,$signmode = 'md5')
    {
        $this->_Config['Version'] = intval($version);
        $this->_Config['SignMode'] = $signmode;

        return $this;
    }
    
    /**
     * 设置sign加密方式,支持 md5 和 hmac
     * 
     * @param string $signmode
     * @return Taoapi_Config
     */
    public function setSignMode ($signmode = 'md5')
    {
        $this->_Config['SignMode'] = $signmode;

        return $this;
    }

    /**
     * 显示或关闭错语提示
     * 
     * @param bool $CloseError
     * @return Taoapi_Config
     */
    public function setCloseError($CloseError = true)
    {
        $this->_Config['CloseError'] = (bool)$CloseError;

        return $this;
    }

    /**
     * 开启或关闭API调用日志功能,
     * 开启后可以查看到每天APPKEY调用的次数以及调用的API
     * 
     * @param bool $Log
     * @return Taoapi_Config
     */
    public function setApiLog($Log)
    {
        $this->_Config['ApiLog'] = (bool)$Log;

        return $this;
    }

    /**
     * 开启或关闭错误日志功能
     * 
     * @param bool $Errorlog
     * @return Taoapi_Config
     */
    public function setErrorlog($Errorlog)
    {
        $this->_Config['Errorlog'] = $Errorlog;

        return $this;
    }

    /**
     * 设置API读取失败时重试的次数,
     * 可以提高API的稳定性,推荐为3次
     * 
     * @param int $RestNumberic
     * @return Taoapi_Config
     */
    public function setRestNumberic($RestNumberic)
    {
        $this->_Config['RestNumberic'] = intval($RestNumberic);;

        return $this;
    }

    /**
     * 设置数据缓存的时间,
     * 单位:小时;0表示不缓存
     * 
     * @param int $cache
     * @return Taoapi_Config
     */
    public function setCache($cache = 0)
    {
        $this->_Config['Cache'] = intval($cache);

        return $this;
    }

    /**
     * 设置缓存保存的目录
     * 
     * @param string $CachePath
     * @return Taoapi_Config
     */
    public function setCachePath($CachePath)
    {
 		  $this->_Config['CachePath'] = $CachePath;

        return $this;
    }    

    /**
     * 返回全局配置参数
     * 
     * @return object
     */
    public function getConfig()
    {
        return (object)$this->_Config;
    }
}