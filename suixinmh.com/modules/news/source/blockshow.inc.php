<?php
/*
	[CMS] (C) 2009-2010 huliming QQ329222795 Inc.
	$Id: show.inc.php  2010-06-10 11:30:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
$id = intval($_PAGE['_GET']['id']) ?intval($_PAGE['_GET']['id']) :intval($_PAGE['_POST']['info']['id']);
if($id) echo('document.write("'.saddslashes(str_replace(array("\r","\n"),'',jieqi_geturl('news', 'tags', $_PAGE['_GET']['id']))).'");');
else{
    require_once($_SGLOBAL['rootpath'].'/global.php');
	require_once($_SGLOBAL['rootpath'].'/blockshow.php');
}
exit;
?>
