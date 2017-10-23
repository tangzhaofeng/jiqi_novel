<?php
/**
 * 分类等文件加载
 *
 * 分类等文件加载
 * 
 * 调用模板：无
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) Hangzhou Network Technology Co.,Ltd.
 * @author     $Author: huliming QQ329222795 $
 * @version    $Id: load.php 332 2010-03-24 09:15:08Z huliming $
 */
define('IN_ADMIN', false);//定义后台处理头文件
include_once('./common.php');
include_once('./include/loadclass.php');
$field = getparameter('field');
$collectid = getparameter('collectid');
header('Content-Type:text/html; charset='.USER_CHARSET); 
//header("Cache-Control:no-cache");
if($field == 'collectindex' && $collectid)
{
    include_once($_SGLOBAL['news']['path'].'/include/collect.class.php');
	//初始化标签对象
	$_OBJ['collect'] = new Collect();
    if(!($_PAGE['collect'] = $_OBJ['collect']->get($collectid, true))) exit();
	$_PAGE['task'] = $_PAGE['collect']['task'];
	$str = '<select name="index" id="index"><option value="all">'.lang_replace('all_collect_task').'</option>';
	$options = '';
	if(count($_PAGE['task'])>0){
		foreach($_PAGE['task'] as $i=>$v)
		{
			$options .= '<option value="'.$i.'">'.$v['taskname'].'</option>';
		}
	}
	if(empty($options)) exit;
	$str .= $options.'</select>';
	echo $str;
	
}elseif($field == 'modelcatid' && ($collectid || $modelid = getparameter('modelid'))){
    if(!$modelid){
		include_once($_SGLOBAL['news']['path'].'/include/collect.class.php');
		//初始化标签对象
		$_OBJ['collect'] = new Collect();
		if(!($_PAGE['collect'] = $_OBJ['collect']->get($collectid, true))) exit();
		$modelid = $_PAGE['collect']['modelid'];
	}
	$catid = getparameter('catid');
    //初始化栏目操作对像和加载栏目数据列表
	$_OBJ['category'] = new Category();
	//获取栏目
	$_OBJ['category']->get_format();
	$str = '<select name="catid" id="catid" style="width:110px;">';
	$options = '<option value="">'.lang_replace('please_select_catid').'</option>';
	$optgroup = getparameter('optgroup');
	foreach($_SGLOBAL['catelist'] as $i=>$v)
	{
	    if($v['type']>0 || $modelid!=$v['modelid']) continue;
		$selected = '';
		if($catid==$v['catid']) $selected = 'selected = \'selected\'';
		if($v['child']>0 && !$optgroup) $options .= '<optgroup label= "'.$v['layer'].$v['catname'].'"></optgroup>';
		else $options .= '<option value="'.$v['catid'].'" '.$selected.'>'.$v['layer'].$v['catname'].'</option>';
	}
	if(empty($options)) exit;
	$str .= $options.'</select>';
	echo $str;
	
}elseif($field == 'keyword'){

    $parentid = $_PAGE['_POST']['parentid'] ?$_PAGE['_POST']['parentid'] :$_PAGE['_GET']['parentid'];
	$_OBJ['view'] = new View('keyword', 'id');
	$_OBJ['view']->setHandler();
	if(!$parentid){
	   $_OBJ['view']->criteria->add(new Criteria('issytem', 1));
	   $_OBJ['view']->criteria->add(new Criteria('parentid', 0));
	}else $_OBJ['view']->criteria->add(new Criteria('parentid', $parentid));
	$_OBJ['view']->criteria->setSort("listorder DESC,id");
	$_OBJ['view']->criteria->setOrder('DESC');
	$_PAGE['rows'] = $_OBJ['view']->lists();
	include_once($_SGLOBAL['rootpath'].'/include/changecode.php');
	$data = array();
	foreach($_PAGE['rows'] as $k => $v){
	    $v['tag'] = call_user_func('jieqi_gb2utf8', $v['tag']);
		$data[] = $v;
	}
	if($_REQUEST['CALLBACK']) echo $_REQUEST['CALLBACK'].'('.json_encode($data).');';
	else echo json_encode($data);
}elseif($field == 'tree'){ //目录树

	//初始化栏目操作对像和加载栏目数据列表
	$_OBJ['category'] = new Category();
	$_PAGE['tree'] = '';
    function getTree($catid = 0){
		global $_SGLOBAL, $_PAGE;
	    foreach($_SGLOBAL['category'] as $k=>$v){
		    if($v['type']>0 || $v['parentid']!=$catid) continue;

			if($v['child']) {
			    $_PAGE['tree'].= "<li><a href='load.php?field=tree&".$v['url']."'>".$v['catname']."</a></li>";
			    $_PAGE['tree'].= "<ul>";
				getTree($v['catid']);
				$_PAGE['tree'].= "</ul>";
			}else{
			    $url = $GLOBALS['_SGLOBAL']['news']['url'].'/admin/?ac=content&op=manage&catid='.$v['catid'];
			    $_PAGE['tree'].= "<li><a href='".$url."' target='mainframe' class=black><img src='/modules/news/images/js/tree/images/file.gif' border=0>".$v['catname']."</a></li>";
			    
			}
		}
	}
	getTree();
    //处理模板
	$jieqiTset['jieqi_page_template'] = $_SGLOBAL['news']['path'].'/themes/empty.html';
	template('admin/tree');
	include_once(JIEQI_ROOT_PATH.'/footer.php');
	
}elseif($field == 'comment'){ //留言
	$_PAGE['template'] = $_PAGE['_POST']['t'] ?$_PAGE['_POST']['t'] :$_PAGE['_GET']['t'];
	$_PAGE['contentid'] = intval($_PAGE['_GET']['id']) ?intval($_PAGE['_GET']['id']) : intval($_PAGE['_GET']['contentid']);
	if(!$_PAGE['contentid']) jieqi_printfail(lang_replace('data_not_exists'));
	//获取内容
	$_OBJ['content'] = new Content();
	if(!is_array($_PAGE['data'] = $_OBJ['content']->get($_PAGE['contentid']))) jieqi_printfail(lang_replace('data_not_exists'));	
	
	//数据视图类
	if(!is_object($_OBJ['comment'])) $_OBJ['comment'] = new View('comment', 'commentid');
	$_OBJ['comment']->setHandler();
	$_OBJ['comment']->criteria->add(new Criteria('contentid', $_PAGE['contentid']));
	$_OBJ['comment']->criteria->add(new Criteria('status', 1));
	$_OBJ['comment']->criteria->setSort('commentid');
	$_OBJ['comment']->criteria->setOrder('DESC');
	$_PAGE['rows'] = $_OBJ['comment']->lists($_SCONFIG['reviewnew'] ?$_SCONFIG['reviewnew'] :10, $_PAGE['page']);
	$_PAGE['url_jumppage'] = $_OBJ['comment']->getPage("/comments/{$_PAGE['contentid']}/");
	$_PAGE['totalcount'] = $_OBJ['comment']->getVar('totalcount');
    //处理模板
	//$jieqiTset['jieqi_page_template'] = $_SGLOBAL['news']['path'].'/themes/empty.html';
	if(!$_PAGE['template']){
	    if($_REQUEST['CALLBACK']) $_PAGE['template'] = 'comment';
		else $_PAGE['template'] = 'comments';
	}
	template($_PAGE['template']);
	//if($_REQUEST['CALLBACK']) ob_start();
	include_once(_ROOT_.'/footer.php');
/*	if($_REQUEST['CALLBACK']){
	    include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
		$data = jieqi_gb2utf8(ob_get_contents());
		ob_end_clean();
		echo $_REQUEST['CALLBACK'].'('.json_encode($data).');';
	}*/
}
?>