<?php
/*
	[Cms news] (C) 2010-2012 Cms Inc.
	$Id: gameonline.inc.php  2011-03-31 10:55:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

//初始化栏目操作对像和加载栏目数据列表
$_OBJ['category'] = new Category();
$_PAGE['posturl'] = $_OBJ['category']->getPosturl($catid);//表单提交URL
if($_PAGE['posturl']!= 'http://'.$_SERVER['HTTP_HOST']) header("location:".$_PAGE['posturl'].$_SERVER['REQUEST_URI']);
//初始化获得模型数据列表
$_OBJ['model'] = new Model();

//初始化数据视图
$_OBJ['gameonline'] = new View('gameonline', 'onlineid');

if($op == 'view') {//预览
	
}elseif($op == 'del') {//删除

} else {//浏览
    include($_SGLOBAL['news']['path'].'/include/fields/formelements.class.php');
    include_once($_SGLOBAL['news']['path'].'/include/fields/form_typeid.class.php');
	$elementObject = new Form_typeid(new FormElements(30), 'typeid');
	$elementObject->setSetting();
	$array = explode("\n", $elementObject->setting['items']);
	$_PAGE['types'] = array();
	foreach($array as $k=>$v){
		$items = explode("|", $v);
		$_PAGE['types'][trim($items[0])] = trim($items[1]);
	}
    getparameter('uid');
	getparameter('gameid');
	getparameter('page');
	$_OBJ['gameonline']->setHandler();
	//游戏名称
	if($_PAGE['gameid']) $_OBJ['gameonline']->criteria->add(new Criteria('gameid', $_PAGE['gameid']));
	//用户
	if($_PAGE['uid']) $_OBJ['gameonline']->criteria->add(new Criteria('uid', $_PAGE['uid']));

	$_OBJ['gameonline']->criteria->setSort('onlineid');
	$_OBJ['gameonline']->criteria->setOrder('DESC');
	$_PAGE['rows'] = $_OBJ['gameonline']->lists(30, $_PAGE['page']);
	$_PAGE['url_jumppage'] = $_OBJ['gameonline']->getPage();

}
?>
