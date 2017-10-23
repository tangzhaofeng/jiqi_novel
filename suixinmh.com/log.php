<?php
//$a=str_replace('<','&lt;',file_get_contents('log.htm'));
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" /></head>';
//echo str_replace('&lt;br>','<br>',$a);
$f = file('files/stopattack_log.html');
if($f){
$f = array_reverse($f);
foreach($f as $k=>$v){
   echo str_replace(array('&lt;br>','&lt;hr>'),array('<br>','<hr>'),str_replace('<','&lt;',$v));
}
}
?>