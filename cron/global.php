<?php
error_reporting(E_ALL ^ E_NOTICE);
@ini_set('memory_limit', '128M');
@ini_set('display_startup_errors', 1);
@ini_set('display_errors', 1);
set_time_limit(0);
@set_magic_quotes_runtime(0);
date_default_timezone_set('Asia/Shanghai');
$root = dirname(dirname($_SERVER['SCRIPT_FILENAME']));
set_include_path(dirname($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'lib' . PATH_SEPARATOR . dirname($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'app' .PATH_SEPARATOR . get_include_path());
/* 注册动态加载 */
require_once  $root . "/lib/Zend/Loader.php";
Zend_Loader::registerAutoload();
/* 取得配置信息 */
$config = new Zend_Config_Xml($root . "/config/config.xml", 'shop');
Zend_Registry::set('config', $config);
$xml = new Custom_Config_Xml();
Zend_Registry::set('memberConfig', $xml->loadXml($root. '/config/member.xml')->toArray());
/* 连接数据库 */
$db = new Custom_Model_Db($config -> database);
Zend_Registry::set('systemRoot', $root);
Zend_Registry::set('db', $db);
$systemRoot = Zend_Registry::get('systemRoot');
define('SYSROOT', $systemRoot);
define('SHOP_TPL_ROOT', $systemRoot.'/app/Shop/Views/scripts/');
