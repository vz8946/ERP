<?php
/**
 * PhpThumb Library Example File
 * 
 * This file contains example usage for the PHP Thumb Library
 * 
 * PHP Version 5 with GD 2.0+
 * PhpThumb : PHP Thumb Library <http://phpthumb.gxdlabs.com>
 * Copyright (c) 2009, Ian Selby/Gen X Design
 * 
 * Author(s): Ian Selby <ian@gen-x-design.com>
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author Ian Selby <ian@gen-x-design.com>
 * @copyright Copyright (c) 2009 Gen X Design
 * @link http://phpthumb.gxdlabs.com
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 3.0
 * @package PhpThumb
 * @subpackage Examples
 * @filesource
 */

require_once 'ThumbLib.inc.php';

$url = urldecode($_REQUEST['url']);

$_REQUEST['w'] = intval($_REQUEST['w']);
$_REQUEST['h'] = intval($_REQUEST['h']);

$thumb = PhpThumbFactory::create($url);

if(!empty($_REQUEST['w']) && !empty($_REQUEST['h'])){
	
	$thumb->adaptiveResize($_REQUEST['w'], $_REQUEST['h']);
	
}elseif(empty($_REQUEST['w']) && !empty($_REQUEST['h'])){
	
	$thumb->adaptiveResize(1000, $_REQUEST['h']);
	
}elseif(!empty($_REQUEST['w']) && empty($_REQUEST['h'])){
	
	$thumb->adaptiveResize($_REQUEST['w'], 1000);
	
}

$thumb->show();



?>
