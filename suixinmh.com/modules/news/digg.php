<?php
/**
 * 文章DIGG统计
 *
 * 文章DIGG统计
 * 
 * 调用模板：无
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) huliming Network Technology Co.,Ltd.
 * @author     $Author: huliming QQ329222795 $
 * @version    $Id: digg.php 332 2010-08-30 16:15:08Z huliming $
 */
 
@define('IN_JQNEWS', TRUE);
include_once('./common.php');
$contentid = intval($_PAGE['_GET']['id']);
$type = $_PAGE['_GET']['type'];
$flag = intval($_PAGE['_GET']['flag']);

if($contentid){
    header('Content-Type:text/html; Cache-Control: no-cache, no-store, max-age=0, must-revalidate; charset='.USER_CHARSET);
	include_once('./include/loadclass.php');
	$_OBJ['content'] = new Content();
	$data = array();
	if($type=='digg' && isset($flag)){
		$data = $_OBJ['content']->digg($contentid, $flag);
	}elseif($type=='show'){
	    if(!($data = $_OBJ['content']->getDigg($contentid))){
		    $data = array('contentid'=>$contentid,'supports'=>0,'supports_day'=>0,'supports_week'=>0,'supports_month'=>0);
		}
	}
	//exit(json_encode($data));
	if($_REQUEST['CALLBACK']){
	   include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
	   echo($_REQUEST['CALLBACK'].'('.jieqi_gb2utf8(json_encode($data)).');');
	}else echo(json_encode($data));
}
?>