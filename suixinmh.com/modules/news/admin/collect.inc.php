<?php
/*
	[Cms news] (C) 2010-2012 Cms Inc.
	$Id: collect.inc.php  2010-08-04 10:55:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
$index = getparameter('index');
$collectid = getparameter('collectid');
$type = getparameter('type');

include_once($_SGLOBAL['news']['path'].'/include/collect.class.php');
//初始化采集对象
$_OBJ['collect'] = new Collect();

if($op == 'collectpage'){
	$_PAGE['collect'] = $_OBJ['collect']->get($collectid);
	//页面超时时间
	$timeout = $_PAGE['collect']['setting']['senior']['timeout'] ? $_PAGE['collect']['setting']['senior']['timeout'] :0;
	@set_time_limit($timeout);
	@session_write_close();
	$_OBJ['collectPage'] = new CollectPage($_PAGE['collect'], $index);
	header('Content-Type:text/html; charset='.USER_CHARSET); 
	getparameter('articleurl');
	if(!($data = $_OBJ['collectPage']->getFields($_PAGE['articleurl']))){
	     exit(lang_replace('collect_ajaxurl_failure',array($_PAGE['_POST']['key'], $_PAGE['articleurl'], $_PAGE['articleurl'])));
	}else{
	     //内容获取类
		 $_OBJ['content'] = new Content();
		 //是否开启重复验证
		 if(!$_OBJ['collectPage']->task['isrepeat'] || (!$_SCONFIG['samearticlename'] && $index=='')){//如果不允许重复
		     if($_OBJ['content']->checkTitle($data['title'],array('modelid'=>$_PAGE['collect']['modelid']))) exit(lang_replace('collect_title_exists',array($data['title'])));
		 }
		 getparameter('catid');
		 getparameter('status');
		 $data['catid'] = isset($_PAGE['catid']) ?$_PAGE['catid'] :$_OBJ['collectPage']->task['catid'];
		 $data['status'] = isset($_PAGE['status']) ?$_PAGE['status'] :$_OBJ['collectPage']->task['status'];
		 //初始化栏目操作对像和加载栏目数据列表
		 //$_OBJ['category'] = new Category();
		 
		 //设定是否更新栏目缓存
		 if(getparameter('key')+1>=getparameter('tnum')){
		     if(!defined('UPDATE_CATEGORY_CACHE')) define('UPDATE_CATEGORY_CACHE', true);
		 }else{
		     if(!defined('UPDATE_CATEGORY_CACHE')) define('UPDATE_CATEGORY_CACHE', false);
		 }
		 
		 //加载表单对象类
		 include_once($_SGLOBAL['news']['path'].'/include/fields/formelements.class.php');
		 $_OBJ['elements'] = new FormElements($_PAGE['collect']['modelid'], $data['catid']);
		 //验证字段值是否合法
		 $fields = array();
		 foreach($_OBJ['elements'] ->fields as $field=>$v){
		    if(!$field || $v['disabled']) continue;
			if(defined('IN_ADMIN') && IN_ADMIN){
				if($v['iscore']) continue;//核心字段不允许编辑
			}else{
				if($v['iscore'] || !$v['isadd']) continue;
			}
			if(!$v['isselect']) continue;
			//if(!in_array($v['formtype'], array('text', 'textarea', 'select', 'checkbox', 'radio', 'editor', 'image', 'images', 'files'))) continue;
			$star = $v['minlength'] ? 1 : 0;
			
			$incFile = $_SGLOBAL['news']['path'].'/include/fields/form_'.$v['formtype'].'.class.php';	
			if(!is_file($incFile)) continue;
			include_once($incFile);
			$className = "Form_{$v['formtype']}";
			$elementObject = new $className($_OBJ['elements'], $field, $data[$field]);
			$data[$field] = $elementObject->formatCollect($_OBJ['collectPage']);
			//print_r($data);exit;
			if($star && !$data[$field]) exit(lang_replace('collect_fields_error', array($v['name'],$_PAGE['articleurl'],$_PAGE['articleurl'])));
		 }
		 if($_OBJ['elements']->add($data, false)){ //添加
			  exit(lang_replace('collect_article_success',array($data['title'])));
		 }else{
		      exit(lang_replace('collect_article_failure',array($data['title'])));
		 }
	}
}elseif($op == 'collectone'){
	getparameter('urls');
	$_PAGE['urls'] = explode("\n", str_replace("\r\n","\n",$_PAGE['urls']));
    if(!$_PAGE['urls']) jieqi_printfail(LANG_ERROR_PARAMETER); 
    $_PAGE['collect'] = $_OBJ['collect']->get($collectid);
	$op = 'start';//定义模板
	$_PAGE['key'] = md5($_SCONFIG['sitekey'].$collectid.implode('|',$_PAGE['urls']));
	//构造AJAX提交URL
	$catid = getparameter('catid');
	$status = getparameter('status');
	$_PAGE['geturl'] = "?ac=collect&op=collectpage&ajax_request=1&catid={$catid}&status={$status}";
	//$_PAGE['nextjumpurl'] = "?ac=collect";
	
}elseif($op == 'ok'){
    
	$_PAGE['collect'] = $_OBJ['collect']->get($collectid);
	if(!array_key_exists($index, $_PAGE['collect']['task'])) jieqi_printfail(LANG_ERROR_PARAMETER); 
	if($type=='all' && ($index+1)<count($_PAGE['collect']['task'])){
	    $index++;
	    header("location:?ac=collect&op=start&type={$type}&collectid=".$collectid.'&index='.$index);
	} else $_PAGE['task'] = $_PAGE['collect']['task'][$index];
	
}elseif($op == 'start'){//开始采集
    //采集页数
    getparameter('page');
	$_PAGE['page']++;
	$_PAGE['collect'] = $_OBJ['collect']->get($collectid);
	$_PAGE['collect']['task'] = $_PAGE['collect']['task'] ? $_PAGE['collect']['task'] :array();
	//采集全部任务
	if($index=='all') $type  = 'all';
	$index = intval($index) ?intval($index) :0;
	if(!array_key_exists($index, $_PAGE['collect']['task'])) jieqi_printfail(LANG_ERROR_PARAMETER); 
	$_PAGE['task'] = $_PAGE['collect']['task'][$index];
	//页面超时时间
	$timeout = $_PAGE['collect']['setting']['senior']['timeout'] ? $_PAGE['collect']['setting']['senior']['timeout'] :0;
	@set_time_limit($timeout);
	@session_write_close();

	//开始页面ID
	getparameter('startpageid');
	if(empty($_PAGE['startpageid'])) $_PAGE['startpageid']=trim($_PAGE['task']['startpageid']);
	
	//检查是否超出最大页码限制
	getparameter('maxpagenum');
	if(empty($maxpagenum)) $_PAGE['maxpagenum']=trim($_PAGE['task']['maxpagenum']);
	if($_PAGE['maxpagenum'] && $_PAGE['page']>$_PAGE['maxpagenum']){
	    header('location:?ac=collect&op=ok&collectid='.$collectid.'&index='.$index); 
	}
	
	//实例化采集对象
	$_OBJ['collectPage'] = new CollectPage($_PAGE['collect'], $index);
	
	//正在分析的地址页
	if(strpos(strtolower($_PAGE['startpageid']), 'http://') !== false) $_PAGE['pageurl'] = $_PAGE['startpageid'];
	else $_PAGE['pageurl'] = str_replace('<{pageid}>', $_PAGE['startpageid'], $_PAGE['task']['urlpage']);
	
	//获取文章序号
	$error = false;
	if(!($_PAGE['urls'] = $_OBJ['collectPage']->getArticleUrls($_PAGE['pageurl'], false))) $error = true;
	
	//下一页参数
	$_PAGE['nextpageid']='';
	if($_PAGE['maxpagenum'] && $_PAGE['page']>=$_PAGE['maxpagenum']){
		    $_PAGE['nextpageid'] = '';
	}else{
		if($_OBJ['collectPage']->task['nextpageid'] == '++'){
			
			$_PAGE['nextpageid'] = $_PAGE['startpageid'];
			$_PAGE['nextpageid'] = intval($_PAGE['nextpageid'])+1;
			
		}elseif($_OBJ['collectPage']->task['nextpageid']){
			$pregstr = $_OBJ['collectPage']->collectstoe($_OBJ['collectPage']->task['nextpageid']);
			$matchvar = $_OBJ['collectPage']->cmatchone($pregstr, $_OBJ['collectPage']->source);
			if(!empty($matchvar)) $_PAGE['nextpageid'] = relative_to_absolute(trim(jieqi_textstr($matchvar)), $_OBJ['collectPage']->task['urlpage'], true);
			
		}
	}
	//下一页地址页
	$_PAGE['nextpageurl'] = '';
	if(strpos(strtolower($_PAGE['nextpageid']), 'http://') !== false) $_PAGE['nextpageurl'] = $_PAGE['nextpageid'];
	elseif($_PAGE['nextpageid']!='') $_PAGE['nextpageurl'] = str_replace('<{pageid}>', $_PAGE['nextpageid'], $_PAGE['task']['urlpage']);
	if($error){
		if($_OBJ['collectPage']->task['nextpageid'] == '++'){
		    $msg = lang_replace('pageid_collect_next',array($_PAGE['startpageid'],$_PAGE['maxpagenum']));
		}elseif($_PAGE['nextpageid']){
		    $msg = lang_replace('pageurl_collect_next',array($_PAGE['nextpageurl'],$_PAGE['task']['nextpageurl']));
		}else{
		    jieqi_printfail(lang_replace('parse_articleid_failure',array($_OBJ['collectPage']->task['urlpage'], $_OBJ['collectPage']->task['urlpage'])));
		}
		jieqi_jumppage("?ac=collect&collectid={$collectid}&op=start&index={$index}&startpageid={$_PAGE['nextpageid']}&page={$_PAGE['page']}&maxpagenum={$_PAGE['maxpagenum']}&type={$type}", lang_replace('message_notice'), $msg);
	}
	//下一页跳转采集地址
	if($_PAGE['nextpageid']!=''){
	    $_PAGE['nextjumpurl'] = "?ac=collect&collectid={$collectid}&op=start&index={$index}&startpageid={$_PAGE['nextpageid']}&page={$_PAGE['page']}&maxpagenum={$_PAGE['maxpagenum']}&type={$type}";
	}else $_PAGE['nextjumpurl'] = "?ac=collect&op=ok&type={$type}&collectid=".$collectid.'&index='.$index;
	$_PAGE['key'] = md5($_SCONFIG['sitekey'].$_PAGE['startpageid'].$collectid.$index);
	$_PAGE['index'] = $index;
	$_PAGE['geturl'] = "?ac=collect&op=collectpage&ajax_request=1&index={$_PAGE['index']}";
}else{
    //print_r($_SGLOBAL['collect']);
}

//$template = !$op ?'collect' :"collect_{$op}";
//template('admin/'.$template);
?>