<?php
/*
	[CMS] (C) 2009-2010 huliming QQ329222795 Inc.
	$Id: show.inc.php  2010-06-10 11:30:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
getparameter('id');
if(!$_PAGE['id']) $_PAGE['id'] = getparameter('contentid');
getparameter('page');
//if(!$_PAGE['id']) jieqi_printfail(lang_replace('data_not_exists'));
if(!isset($_PAGE['ac'])) $_PAGE['ac'] = 'show';
//内容获取类
if(!is_object($_OBJ['content'])) $_OBJ['content'] = new Content();

if(!($str = $_OBJ['content']->fetch($_PAGE['id'], $_PAGE['page'] ?$_PAGE['page'] :1))) showmessage('article_is_errors');
$host = $_SERVER['HTTP_HOST'];
$articleurl = $_PAGE['data']['url_articleinfo'];
/*if(!eregi("/".$host."/",$articleurl)){
   header( "HTTP/1.1 301 Moved Permanently" );
   header( "Location: {$articleurl}" );
   exit;
}*/
exit($str);
?>
