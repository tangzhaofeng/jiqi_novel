<?php
/**
 * 文章心情统计
 *
 * 文章心情统计
 * 
 * 调用模板：无
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) huliming Network Technology Co.,Ltd.
 * @author     $Author: huliming QQ329222795 $
 * @version    $Id: digg.php 332 2010-09-10 16:15:08Z huliming $
 */
 
@define('IN_JQNEWS', TRUE);
include_once('./common.php');

$contentid = intval($_PAGE['_POST']['contentid']) ?intval($_PAGE['_POST']['contentid']) :intval($_PAGE['_GET']['contentid']);
$moodid = intval($_PAGE['_POST']['moodid']) ?intval($_PAGE['_POST']['moodid']) :intval($_PAGE['_GET']['moodid']);
$vote_id = intval($_PAGE['_POST']['vote_id']) ?intval($_PAGE['_POST']['vote_id']) :intval($_PAGE['_GET']['vote_id']);

if($contentid && $moodid && $vote_id){
    header('Content-Type:text/html; Cache-Control: no-cache, no-store, max-age=0, must-revalidate; charset='.USER_CHARSET);
	//载入统计处理函数
	include_once(JIEQI_ROOT_PATH.'/include/funstat.php');
	if(!jieqi_visit_valid($contentid, 'article_mood')) $data=array();
	else{
		include_once('./include/loadclass.php');
		$_OBJ['content'] = new Content();
		$data = array();
		$data = $_OBJ['content']->mood($moodid, $contentid, $vote_id);
	}
	if($_REQUEST['CALLBACK']){
	   include_once($_SGLOBAL['rootpath'].'/include/changecode.php');
	   echo($_REQUEST['CALLBACK'].'('.jieqi_gb2utf8(json_encode($data)).');');
	}else echo(json_encode($data));
}
?>