<?php
/**
 * @see sphnix search
 */

class Custom_Model_Certify
{
    static private $_error = array();
    static private $_secret_key = 'abc';
	

    static public function certify_valid($params)
    {
        unset($params['action'], $params['module'], $params['controller']);
        ksort($params);
        $secret_key = $params['secret_key'];
        unset($params['secret_key']);

        $params_str = http_build_query($params).self::$_secret_key;

        if (md5($params_str) != $secret_key) {
            self::$_error['error'] = '提交验证没有通过';
            return false;
        }

        return true;
    }

    static public function getError()
    {
        return self::$_error;
    }
	
}