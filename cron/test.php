<?php
$callback = $_GET['callback'];

$arr = json_encode(array('msg' => '测试'));

exit($callback.'('.$arr.')');