<?php
/**
 * 文章点击统计
 *
 * 文章点击统计
 * 
 * 调用模板：无
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) huliming Network Technology Co.,Ltd.
 * @author     $Author: huliming QQ329222795 $
 * @version    $Id: count.php 332 2010-08-29 16:15:08Z huliming $
 */
 
@define('IN_JQNEWS', TRUE);
include_once('./common.php');
$_PAGE['contentid'] = intval($_PAGE['_GET']['id']) ?intval($_PAGE['_GET']['id']) : intval($_PAGE['_GET']['contentid']);
if(!$_PAGE['contentid']) jieqi_printfail(lang_replace('data_not_exists'));
	
if($_SCONFIG['visitstatnum'] && $_PAGE['contentid']){
    header('Content-Type:text/html; Cache-Control: no-cache; charset='.USER_CHARSET);
	include_once('./include/loadclass.php');
	$_OBJ['content'] = new Content();
	if($data = $_OBJ['content']->hits($_PAGE['contentid'], $_SCONFIG['visitstatnum'])){
		if($_REQUEST['CALLBACK']) echo $_REQUEST['CALLBACK'].'('.json_encode($data).');';
		else echo json_encode($data);
	}
}
?>