<?php
/*
	[Cms news] (C) 2009-2010 Cms Inc.
	$Id: content.inc.php  2010-04-09 17:15:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
//分页参数
getparameter('page','int');


//初始化参数
$table = $file;
$idfield = 'id';
$listorder = 'listorder';

$_OBJ['view'] = new View($table, $idfield);
$_OBJ['view']->setHandler();

if($op == 'add'){
    //提交数据
	if(submitcheck("dosubmit")){
	   //写入数据
	   if($file=='keyword'){
	       $_PAGE['data'] = getparameter('info');
	       if(getparameter('id','int')){//修改
		       if(getparameter('parentids')=='') unset($_PAGE['data']['parentid']);
		       if($_OBJ['view']->edit($_PAGE['id'],$_PAGE['data'],false)) jieqi_jumppage(getparameter('jumpurl'), LANG_NOTICE, LANG_DO_SUCCESS);
			   else jieqi_jumppage(getparameter('jumpurl'), LANG_NOTICE, LANG_DO_FAILURE); 
		   }else{//关键词批量录入
			   $data = explode("\r\n", $_PAGE['data']['tag']);
			   foreach($data as $k=>$v){
				   $_PAGE['data']['tag'] = $v;
				   $_OBJ['view']->add($_PAGE['data']);
			   }
			   jieqi_jumppage($_SGLOBAL['refer'], LANG_NOTICE, LANG_DO_SUCCESS);
		   }
	   }else{
		   if($_OBJ['view']->add(getparameter('info'))) jieqi_jumppage($_SGLOBAL['refer'], LANG_NOTICE, LANG_DO_SUCCESS);
		   else jieqi_jumppage($_SGLOBAL['refer'], LANG_NOTICE, LANG_DO_FAILURE); 
	   }
	}
	if($file=='keyword'){
		$_OBJ['view']->criteria->add(new Criteria('issystem', 1));
		$_OBJ['view']->criteria->add(new Criteria('parentid', 0));
		if($listorder){
			$_OBJ['view']->criteria->setSort("{$listorder} DESC,{$idfield}");
			$_OBJ['view']->criteria->setOrder('DESC');
		}else{
			$_OBJ['view']->criteria->setSort($idfield);
			$_OBJ['view']->criteria->setOrder('DESC');
		}
		$_PAGE['rows'] = $_OBJ['view']->lists();
		getparameter('id','int');
		if($_PAGE['id']) $_PAGE['data'] = $_OBJ['view']->get($_PAGE['id']);
	}
}elseif($op == 'del'){
	if(!getparameter('id','int')) jieqi_printfail(LANG_ERROR_PARAMETER);
	$ids = array();//存放待删除的内容ID容器
	if(!is_array($_PAGE['id']))  $ids[] = $_PAGE['id'];
	else  $ids = $_PAGE['id'];
	foreach($ids as $k=>$id){
	    $_OBJ['view']->delete($id);
	}
	jieqi_jumppage("?ac=set&file={$_PAGE['file']}", LANG_NOTICE, LANG_DO_SUCCESS); 
}elseif($op == 'order'){
    //排序
	if(submitcheck("dosubmit")){
	     if($_OBJ['view']->order(getparameter('order'))) jieqi_jumppage($_SGLOBAL['refer'], lang_replace('message_notice'), LANG_DO_SUCCESS);
		 else jieqi_jumppage($_SGLOBAL['refer'], lang_replace('message_notice'), LANG_DO_FAILURE); 
	}
}else {
    if($file=='keyword'){
	    getparameter('keytype');
	    if($_PAGE['keytype']=='keyword') $_OBJ['view']->criteria->add(new Criteria('tag', '%'.getparameter('keyword').'%', 'like'));
		elseif($_PAGE['keytype']=='parentid') $_OBJ['view']->criteria->add(new Criteria('parentid', getparameter('keyword','int')));
		elseif($_PAGE['keytype']=='id') $_OBJ['view']->criteria->add(new Criteria('id', getparameter('keyword','id','int')));
		elseif($_PAGE['keytype']=='issystem') $_OBJ['view']->criteria->add(new Criteria('issystem', 1)); 
	}
	if($listorder){
		$_OBJ['view']->criteria->setSort("{$listorder} ASC,{$idfield}");
		$_OBJ['view']->criteria->setOrder('DESC');
	}else{
	    $_OBJ['view']->criteria->setSort($idfield);
		$_OBJ['view']->criteria->setOrder('DESC');
	}
	$_PAGE['rows'] = $_OBJ['view']->lists($_SCONFIG['pagenum'] ?$_SCONFIG['pagenum'] :40, $_PAGE['page']);
	$_PAGE['url_jumppage'] = $_OBJ['view']->getPage('');
	$_PAGE['totalcount'] = $_OBJ['view']->getVar('totalcount');
}
?>
