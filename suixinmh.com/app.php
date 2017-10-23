<?php
require 'E:/wwwroot/system/app.php';// echo $_REQUEST['controller'];
require_once('global.php');
$id = intval($_REQUEST['id']);
if($id) echo('document.write("'.addslashes_array(str_replace(array("\r","\n"),'',jieqi_geturl('system', 'tags', $id))).'");');
else{
	require_once('blockshow.php');
}
exit;
?>