<?php
/*
	[Cms news] (C) 2009-2010 Cms Inc.
	$Id: category.inc.php  2010-04-14 10:55:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
//$op = empty($_PAGE['_GET']['op']) ? "" : $_PAGE['_GET']['op'];
//$step = empty($_PAGE['_GET']['step']) ? "" : $_PAGE['_GET']['step'];

//初始化栏目操作对像和加载栏目数据列表
$_OBJ['category'] = new Category();
$_PAGE['posturl'] = $_OBJ['category']->getPosturl($catid);//表单提交URL
$_PAGE['attachurl'] = $_OBJ['category']->getAttachurl($catid);//附件URL服务器

if($op == 'del') {//删除

	if($_OBJ['category']->delete($_PAGE['_GET']['catid'])) jieqi_jumppage('?ac=category', LANG_NOTICE, LANG_DO_SUCCESS);
	else jieqi_jumppage('?ac=category', LANG_NOTICE, LANG_DO_FAILURE);
	
}elseif($op == 'order'){//排序
    //栏目排序
	if(submitcheck("dosubmit")){
	     if($_OBJ['category']->order($_PAGE['_POST']['order'])) jieqi_jumppage('?ac=category', LANG_NOTICE, LANG_DO_SUCCESS);
		 else jieqi_jumppage('?ac=category', LANG_NOTICE, LANG_DO_FAILURE); 
	}
}elseif($op == 'recycle'){//清空
	if($_OBJ['category']->recycle($_PAGE['_GET']['catid'])) jieqi_jumppage('?ac=category', LANG_NOTICE, LANG_DO_SUCCESS);
	else jieqi_jumppage('?ac=category', LANG_NOTICE, LANG_DO_FAILURE);
}elseif($op == 'add'){
	//提交数据
	if(submitcheck("dosubmit")){
		 $data = $_PAGE['_POST']['category'];//print_r($data);exit;
		 $data['catdir'] = trim($data['catdir']);
		 $data['setting'] = saddslashes(arrayeval($_REQUEST['setting']));
		 //更新数据
		 if($_PAGE['_GET']['catid']){ 
		     $statu = $_OBJ['category']->edit($_PAGE['_GET']['catid'],$data); //修改
			 //应用到子目录
			 if($statu && $_PAGE['_POST']['createtype_application']){
			     $_SGLOBAL['cate'] = $_OBJ['category']->get($_PAGE['_GET']['catid'], false);
				 $arrchildids = explode(',', $_SGLOBAL['cate']['arrchildid']);
				 $temparr = array();
				 $tempdata = array();
				 foreach($arrchildids as $catid){
				     if($catid && $catid!=$_PAGE['_GET']['catid']){
					     $temparr = $_OBJ['category']->get($catid);
						 $_REQUEST['setting']['meta_title'] = $temparr['setting']['meta_title'];
						 $_REQUEST['setting']['meta_keywords'] = $temparr['setting']['meta_keywords'];
						 $_REQUEST['setting']['meta_description'] = $temparr['setting']['meta_description'];
						 $tempdata['parentid'] = $temparr['parentid'];
						 $tempdata['catdir'] = $temparr['catdir'];
						 $tempdata['url'] = $temparr['url'];
						 $tempdata['setting'] = saddslashes(arrayeval($_REQUEST['setting']));
						 $_OBJ['category']->edit($catid,$tempdata); //修改
						 unset($tempdata);
					 }
				 }
			 }
		 }else $statu = $_OBJ['category']->add($data);//增加 
		 //消息
		 if($statu)  jieqi_jumppage('?ac=category', LANG_NOTICE, LANG_DO_SUCCESS);
		 else jieqi_printfail(LANG_DO_FAILURE); 
	}
	//在多服务器环境下，表单地址要和附件服务器URL保持一致，否则附件上传会出错
	//if($_PAGE['attachurl']!= 'http://'.$_SERVER['HTTP_HOST']) header("location:".$_PAGE['attachurl'].$_SERVER['REQUEST_URI']);
	////////////////////////////构造表单//////////////////////////////
	//格式化栏目，方便输出
	$_OBJ['category']->get_format();
	//初始化获得模型数据列表
	$_OBJ['model'] = new Model();
	//加载工作流方案
	//get_cache_data('workflow');
	new GlobalData('workflow', 'workflowid');
	//如果修改状态
	if($_PAGE['_GET']['catid']){
		 //获取修改栏目内容
		 $_SGLOBAL['cate'] = $_OBJ['category']->get($_PAGE['_GET']['catid'], false);
	}else{
	    //添加数据表单
		if(!$step && (!isset($_PAGE['_POST']['parentid']) || !isset($_PAGE['_POST']['modelid']) || !isset($_PAGE['_POST']['type']))) jieqi_printfail(LANG_ERROR_PARAMETER);
		$_SGLOBAL['cate']['parentid'] = $_PAGE['_POST']['parentid'];
		$_SGLOBAL['cate']['modelid'] = $_PAGE['_POST']['modelid'];
		$_SGLOBAL['cate']['type'] = $_PAGE['_POST']['type'];
	}
	$_SGLOBAL['cate'] = shtmlspecialchars($_SGLOBAL['cate']);
	$_PAGE['ltag'] = str_replace('\\','',$jieqiTpl->left_delimiter);
	$_PAGE['rtag'] = str_replace('\\','',$jieqiTpl->right_delimiter);
} else {//浏览
	//在多服务器环境下，表单地址要和附件服务器URL保持一致，否则附件上传会出错
	if($_PAGE['posturl']!= 'http://'.$_SERVER['HTTP_HOST']) header("location:".$_PAGE['posturl'].$_SERVER['REQUEST_URI']);
	//格式化栏目，方便输出
	$_OBJ['category']->get_format();
	//初始化获得模型数据列表
    $_OBJ['model'] = new Model();
}
//$template = !$op ?'category' :"{$ac}_{$op}{$step}";
//template('admin/'.$template);
?>
