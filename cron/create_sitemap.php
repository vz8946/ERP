<?php
require_once "global.php";

$api_sitemap = new Admin_Models_API_Sitemap();
$api_sitemap->createSitemap(Zend_Registry::get('systemRoot'));    	

exit('操作成功');