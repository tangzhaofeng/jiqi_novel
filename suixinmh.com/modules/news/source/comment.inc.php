<?php
/*
	[CMS] (C) 2009-2010 huliming QQ329222795 Inc.
	$Id: comment.inc.php  2010-09-08 11:30:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

$op = $_PAGE['_GET']['op'] ?$_PAGE['_GET']['op'] :$_PAGE['_POST']['op'];

$_PAGE['contentid'] = intval($_PAGE['_GET']['contentid']) ?intval($_PAGE['_GET']['contentid']) :intval($_PAGE['_POST']['contentid']);
$_PAGE['id'] = intval($_PAGE['_POST']['id']) ?intval($_PAGE['_POST']['id']) :intval($_PAGE['_GET']['id']);
$_PAGE['contentid'] = $_PAGE['contentid'] ?$_PAGE['contentid'] : $_PAGE['id'];

$_PAGE['page'] = intval($_PAGE['_GET']['page']) ?intval($_PAGE['_GET']['page']) : intval($_PAGE['_POST']['page']);
$_PAGE['page'] = $_PAGE['page'] ? $_PAGE['page'] :1;

if(!$_PAGE['contentid']) jieqi_printfail(lang_replace('data_not_exists'));

//数据视图类
$_OBJ['comment'] = new View('comment', 'commentid');
	 
if($op == 'add') {
     $data['contentid'] = $_PAGE['contentid'];
     $data['content'] = $_PAGE['_GET']['content'] ? $_PAGE['_GET']['content'] : $_PAGE['_POST']['content'];
	 $hidename = $_PAGE['_GET']['hidename'] ? $_PAGE['_GET']['hidename'] : $_PAGE['_POST']['hidename'];
	 //检查最少字数
     if($_SCONFIG['minreviewsize'] && strlen($data['content'])<$_SCONFIG['minreviewsize']){
	    jieqi_printfail(lang_replace('review_minsize_limit',array($_SCONFIG['minreviewsize'])));
	 }
	 //检查最大字数
     if($_SCONFIG['maxreviewsize'] && strlen($data['content'])>$_SCONFIG['maxreviewsize']){
	    jieqi_printfail(lang_replace('review_maxsize_limit',array($_SCONFIG['maxreviewsize'])));
	 }  
	 $data['ip'] = jieqi_userip();   
	 if($_SGLOBAL['supe_uid'] && !$hidename){
		 $data['userid'] = $_SGLOBAL['supe_uid'];
		 $data['username'] = $_SN[$_SGLOBAL['supe_uid']];
	 }else{
		 include_once($_SGLOBAL['rootpath'].'/include/ip2location.php');
		 $ip = new Ip2Location();
		 $ip->qqwry($data['ip']);
		 $_SGLOBAL['ipaddress'] = $ip->Country.$ip->Local;
		 
	     $data['userid'] = 0;
		 $data['username'] = $_SGLOBAL['ipaddress'];
	 }
	 $data['addtime'] = $_SGLOBAL['timestamp'];
	 $data['status'] = $_SCONFIG['reviewcheck'];
     if($_OBJ['comment']->add($data,false)){
	     //内容获取类
		 $_OBJ['content'] = new Content();
		 $_OBJ['content']->hits($data['contentid'], $_SCONFIG['visitstatnum'], array('comments'=>1,'comments_checked'=>$_SCONFIG['reviewcheck']));
	     showmessage($_SCONFIG['reviewcheck'] ? 'review_post_success' : 'review_post_check');
	 } else  showmessage('review_post_failure');
	 
} else {
     $_PAGE['_GET']['field'] = 'comment';
     include_once($jieqiModules['news']['path'].'/load.php');
	 exit;
}
?>
