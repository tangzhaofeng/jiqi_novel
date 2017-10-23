<?php
/*
	[Cms news] (C) 2010-2012 Cms Inc.
	$Id: collectset.inc.php  2010-07-29 10:55:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

//初始化获得模型数据列表
$_OBJ['model'] = new Model();
//初始化栏目操作对像和加载栏目数据列表
$_OBJ['category'] = new Category();
$_PAGE['posturl'] = $_OBJ['category']->getPosturl($catid);//表单提交URL
$_PAGE['attachurl'] = $_OBJ['category']->getAttachurl($catid);//附件URL服务器
include_once($_SGLOBAL['news']['path'].'/include/collect.class.php');
//初始化标签对象
$_OBJ['collect'] = new Collect();
if($op == 'add'){
    getparameter('collectid');
	//提交数据
	if(submitcheck("dosubmit")){
	
		$data = getparameter('info');
	    //$data['modelid'] = $_PAGE['_POST']['modelid'] ?$_PAGE['_POST']['modelid'] :$_PAGE['_GET']['modelid'];
		$data['modelid'] = getparameter('modelid');
		if(!$data['modelid']) jieqi_printfail(LANG_ERROR_PARAMETER); 
		
		$data['setting'] = saddslashes(arrayeval($_REQUEST['setting']));
		//$data['task'] = saddslashes(arrayeval($_REQUEST['task']));
		$data['updatetime'] = $_SGLOBAL['timestamp'];
		$data['disabled'] = 0;
		 //更新数据
		 if($_PAGE['collectid']){
		     $statu = $_OBJ['collect']->edit($_PAGE['collectid'],$data); //修改
			 $collectid = $_PAGE['collectid'];
		 }else{
		     $data['inputtime'] = $_SGLOBAL['timestamp'];
			 $statu = $_OBJ['collect']->add($data);//增加 
			 $collectid = $statu;
		 }
		 //消息
		 if($statu){
		    $_OBJ['collect']->cacheOne($collectid);
		    jieqi_jumppage('?ac=collectset', lang_replace('message_notice'), LANG_DO_SUCCESS);
		 } else jieqi_printfail(LANG_DO_FAILURE); 
		
	}
	if($_PAGE['collectid']){
	    $_PAGE['collect'] = shtmlspecialchars($_OBJ['collect']->get($_PAGE['collectid']));
	}else $_PAGE['collect']['modelid'] = getparameter('modelid');
	
	if($_PAGE['collect']['modelid']){
		include_once($_SGLOBAL['news']['path'].'/include/fields/fields.inc.php');
		if($_SGLOBAL['modelfield'] = $_OBJ['model']->get($_PAGE['collect']['modelid'])){
			if($_SGLOBAL['modelfield']['disabled']) jieqi_printfail(lang_replace('model_not_exists')); 
		}else jieqi_printfail(lang_replace('model_not_exists'));
		//加载表单对象类
		include_once($_SGLOBAL['news']['path'].'/include/fields/formelements.class.php');
		$elements = new FormElements($_PAGE['collect']['modelid']);
		//$_PAGE['form'] = $elements->getElements(array());
		$_PAGE['form'] = $elements->getCollectElements($_PAGE['collect']['setting']['fields']);
	}
	
}elseif($op == 'task') {//采集任务
	getparameter('collectid');
	$_PAGE['collect'] = $_OBJ['collect']->get($_PAGE['collectid']);
	$indexs = getparameter('index');
	//删除数据
	if($step=='del'){
	    $data = $_PAGE['collect']['task'];
		if(!is_array($indexs)) $indexs = array($indexs);
		$statu = false;
		foreach($indexs as $k=>$index){
			if(!array_key_exists($index, $_PAGE['collect']['task'])) continue;
			unset($data[$index]);
		}
		$data = array_values($data);
		if($_OBJ['collect']->edit($_PAGE['collectid'],array('task'=>saddslashes(arrayeval($data))))){
			$_OBJ['collect']->cacheOne($_PAGE['collectid']);
		    jieqi_jumppage('?ac=collectset&op=task&collectid='.$_PAGE['collectid'], lang_replace('message_notice'), LANG_DO_SUCCESS);
		} else jieqi_printfail(LANG_DO_FAILURE); 
	}
	
    //提交数据
	if(submitcheck("dosubmit")){
	    $data = $temp = array();
		if($_PAGE['collect']['task']) $data = $_PAGE['collect']['task'];
		
		    $temp = $_REQUEST['setting'];
		    $temp['fields'] = $_PAGE['collect']['setting']['fields'];
		    if($_REQUEST['setting']['fields']!=$_PAGE['collect']['setting']['fields']){
			    $temp['fields'] = $_REQUEST['setting']['fields'];
			}else{
				$temp['fields'] = array();
			}

	    //更新数据
		if(isset($_PAGE['index']) && $_PAGE['index']!=''){
		    
			if(!array_key_exists($_PAGE['index'], $_PAGE['collect']['task'])) jieqi_printfail(LANG_ERROR_PARAMETER); 
			$data[$_PAGE['index']] = $temp;
			
		}else{
		
		    $data[] = $temp;
			
		}
		if($_OBJ['collect']->edit($_PAGE['collectid'],array('task'=>saddslashes(arrayeval($data))))){
			$_OBJ['collect']->cacheOne($_PAGE['collectid']);
		    jieqi_jumppage('?ac=collectset&op=task&collectid='.$_PAGE['collectid'], lang_replace('message_notice'), LANG_DO_SUCCESS);
		} else jieqi_printfail(LANG_DO_FAILURE); 
	}
	
	//构造任务添加修改表单
	if($_PAGE['collect']['modelid'] && $step=='add'){
		if(isset($_PAGE['index']) && $_PAGE['index']!=''){//修改任务时执行
		
		    if(!array_key_exists($_PAGE['index'], $_PAGE['collect']['task'])) jieqi_printfail(LANG_ERROR_PARAMETER); 
			$fields = $_PAGE['collect']['setting']['fields'];
			$_PAGE['collect']['setting'] = $_PAGE['collect']['task'][$_PAGE['index']];
		    if(!$_PAGE['collect']['task'][$_PAGE['index']]['fields']){
			    $_PAGE['collect']['setting']['fields'] = $fields;
			}
		}
		include_once($_SGLOBAL['news']['path'].'/include/fields/fields.inc.php');
		if($_SGLOBAL['modelfield'] = $_OBJ['model']->get($_PAGE['collect']['modelid'])){
			if($_SGLOBAL['modelfield']['disabled']) jieqi_printfail(lang_replace('model_not_exists')); 
		}else jieqi_printfail(lang_replace('model_not_exists'));
		//加载表单对象类
		include_once($_SGLOBAL['news']['path'].'/include/fields/formelements.class.php');
		$elements = new FormElements($_PAGE['collect']['modelid']);
		//$_PAGE['form'] = $elements->getElements(array());
		$_PAGE['collect'] = shtmlspecialchars($_PAGE['collect']);
		$_PAGE['form'] = $elements->getCollectElements($_PAGE['collect']['setting']['fields']);
		//获取栏目
		$_OBJ['category']->get_format();
	}else{//任务列表
	    $_PAGE['task'] = $_PAGE['collect']['task'];
	}
	
}elseif($op == 'order'){
    //排序
	if(submitcheck("dosubmit")){
	     if($_OBJ['collect']->order(getparameter('order'))) jieqi_jumppage('?ac=collectset', lang_replace('message_notice'), LANG_DO_SUCCESS);
		 else jieqi_jumppage('?ac=collectset', lang_replace('message_notice'), LANG_DO_FAILURE); 
	}
}elseif($op == 'del') {//删除
    $collect = getparameter('collectid');
	if(!is_array($collect)) $collect = array($collect);
	$statu = false;
	foreach($collect as $k=>$collectid){
	    if(!$collectid) continue;
		$statu = $_OBJ['collect']->delete($collectid);
	}
	if($statu) jieqi_jumppage('?ac=collectset', LANG_NOTICE, LANG_DO_SUCCESS);
	else jieqi_jumppage('?ac=collectset', LANG_NOTICE, LANG_DO_FAILURE);
}else {//浏览
	//在多服务器环境下，表单地址要和附件服务器URL保持一致，否则附件上传会出错
	if($_PAGE['posturl']!= 'http://'.$_SERVER['HTTP_HOST']) header("location:".$_PAGE['posturl'].$_SERVER['REQUEST_URI']);
	getparameter('page');;
	$_OBJ['collect']->setHandler();
	$_OBJ['collect']->criteria->setSort('listorder');
	$_OBJ['collect']->criteria->setOrder('ASC');
	$_PAGE['rows'] = $_OBJ['collect']->lists(30, $_PAGE['page']);
	$_PAGE['url_jumppage'] = $_OBJ['collect']->getPage();
}
//$template = !$op ?'collectset' :"collectset_{$op}{$step}";
//template('admin/'.$template);
?>
