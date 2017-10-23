<?php
/*
	[CMS] (C) 2010-2012 huliming QQ329222795 Inc.
	$Id: top.inc.php  2011-03-11 15:55:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
getparameter('catid');
if(!$_PAGE['catid']) $_PAGE['catid'] = getparameter('id');
getparameter('page');
getparameter('mid');
getparameter('m');
getparameter('order');
getparameter('tag');

if($_PAGE['cacheid']) $cacheid = $_PAGE['cacheid'];
else $cacheid = md5(serialize($_PAGE));
//设定缓存文件路径
$cachefile = CACHE_PATH."/modules/"._MODULE_."/templates/top/".$cacheid.".html";
if(USE_CACHE && is_file($cachefile) && _NOW_ - filemtime($cachefile) < CACHE_LIFETIME){
    include_once($cachefile);exit;
}else{
	if(!is_object($_OBJ['content'])) $_OBJ['content'] = new Content();
	if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
	if(!$_PAGE['pagesize']) $_PAGE['pagesize'] = $_OBJ['category']->getPagenum($_PAGE['catid']);
	if(!$_PAGE['pagestr']) $_PAGE['pagestr'] = $_SCONFIG['categorypages'];
	if(!isset($_PAGE['emptyonepage'])) $_PAGE['emptyonepage'] = false;
	$_OBJ['content']->getData($_PAGE, false, $_PAGE['emptyonepage']);
	if($_PAGE['totalpage']) $_OBJ['content']->jumppage->setVar('totalpage', $_PAGE['totalpage']);
	
	$param = array();
	$param['catid'] = $_PAGE['catid'];
	$param['m'] = $_PAGE['m'];
	if($_PAGE['tag']) $param['tag'] = $_PAGE['tag'];
	if(!$_PAGE['linkurl']) $_PAGE['linkurl'] = jieqi_geturl('news', 'top', $param, $_PAGE['order'], $_PAGE['page'], false);
	$_PAGE['url_jumppage'] = $_OBJ['content']->getPage($_PAGE['linkurl']);
	if(!$_PAGE['template']) $_PAGE['template'] = $_PAGE['tag'] ? 'taglist' : "top";
	
	template($_PAGE['template']);
	
	if(USE_CACHE){
	    ob_start();
		include_once($_SGLOBAL['rootpath'].'/footer.php');
		$str = ob_get_contents();
		ob_end_clean();
		swritefile($cachefile, $str);
		exit($str);
	}
}
?>