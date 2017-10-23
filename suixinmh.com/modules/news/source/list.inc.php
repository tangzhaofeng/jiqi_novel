<?php
/*
	[CMS] (C) 2009-2010 huliming QQ329222795 Inc.
	$Id: list.inc.php  2010-06-10 11:30:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
//$catid = intval($_PAGE['_GET']['catid']) ?intval($_PAGE['_GET']['catid']) :intval($_PAGE['_POST']['info']['catid']);
//if(!$catid) $catid = intval($_PAGE['_GET']['id']) ?intval($_PAGE['_GET']['id']) :intval($_PAGE['_POST']['info']['id']);
getparameter('catid');
if(!$_PAGE['catid']) $_PAGE['catid'] = getparameter('id');
getparameter('page');
if(!isset($_PAGE['ac'])) $_PAGE['ac'] = 'list';
//设定缓存文件路径
$cachefile = CACHE_PATH."/modules/"._MODULE_."/templates/list/inputtime_c{$_PAGE['catid']}_p{$_PAGE['page']}.html";
if(USE_CACHE && is_file($cachefile) && _NOW_ - filemtime($cachefile) < CACHE_LIFETIME){
    include_once($cachefile);exit;
}else{
	if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
	if(!($str = $_OBJ['category']->fetch($_PAGE['catid'],$_PAGE['page']))) jieqi_printfail(lang_replace('category_not_exists'));
	$host = $_SERVER['HTTP_HOST'];
	$articleurl = $_OBJ['category']->getUrl($_PAGE['catid'], $_PAGE['page']);//jieqi_geturl('news', 'lists', $catid, $page);
	if($_SGLOBAL['currenturl']!=$articleurl){//!eregi("/".$host."/",$articleurl)){
	   header( "HTTP/1.1 301 Moved Permanently" );
	   header( "Location: {$articleurl}" );
	   exit;
	}
	if(USE_CACHE) swritefile($cachefile, $str);
	exit($str);
}
?>
