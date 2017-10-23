<?php
/*
	[CMS] (C) 2009-2010 huliming QQ329222795 Inc.
	$Id: list.inc.php  2010-06-10 11:30:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
getparameter('uid');
$_OBJ['history'] = new View('gameonline', 'onlineid');
$_OBJ['history']->setHandler();
$_OBJ['history']->criteria->add(new Criteria('uid', $_PAGE['uid'] ? $_PAGE['uid'] :$_SGLOBAL['supe_uid']));
$_OBJ['history']->criteria->setSort('lasttime');
$_OBJ['history']->criteria->setOrder('DESC');
$_PAGE['rows'] = $_OBJ['history']->lists(3);
template("history");
?>
