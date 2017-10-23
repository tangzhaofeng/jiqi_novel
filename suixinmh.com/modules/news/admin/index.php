<?php
/**
 * 后台通用的页面调用文件
 *
 * 页面调用，加载功能模块
 * 
 * 调用模板：无
 * 
 * @category   cms
 * @package    news
 * @copyright  Copyright (c) huliming QQ329222795.
 * @author     $Author: huliming QQ329222795 $
 * @version    $Id: index.php 332 2010-04-09 09:15:08Z huliming $
 */
header ( "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
//if(!defined('ADMIN_DIR')) define('ADMIN_DIR', 'admin/');//定义后台处理头文件
define('IN_ADMIN', true);//定义后台处理头文件
include_once('../common.php');
include_once('../include/loadclass.php');
//jieqi_loadlang('showmessage', 'news');
//允许的方法
$acs = array('content','model','category','selectfile','cutimage','position','collect','collectset','create','comment','set');
$ac = getparameter('ac');
$ac = (empty($ac) || !in_array($ac, $acs))?'content':$ac;
//动作
$op = getparameter('op');
//步骤
$step = getparameter('step');
//文件[为空默认为$ac]
$file = getparameter('file');
//构造权限标识
$powerkey = 'admin'.$ac.($op ?'_'.$op :'');

if(in_array($ac, array('model','category')) && $op=='add' && ($_PAGE['_GET']['catid'] || $_PAGE['_GET']['modelid'])){//修改栏目或者模型

	new Power('admin'.$ac.'_edit', $_SCONFIG['_POWER']['admin'.$ac.'_edit'], false);
	
}elseif($ac=='content' && $op=='check' && $_PAGE['_GET']['catid']){//文章单个栏目内容审核权限
    
	$power = new Power();
	$power->addPower($powerkey, $_SCONFIG['_POWER'][$powerkey]);
	if($power->checkPower($powerkey, true)){
	    //取得栏目的权限设置
		$_OBJ['category'] = new Category();
		$_SGLOBAL['cate'] = $_OBJ['category']->get($_PAGE['_GET']['catid'], false);
		$power->addPower($powerkey, $_SGLOBAL['cate']['setting']['check']);
		$power->checkPower($powerkey, false);
	}
	
}elseif(isset($_SCONFIG['_POWER'][$powerkey])){//如果存在权限标识相关的权限设定

    new Power($powerkey, $_SCONFIG['_POWER'][$powerkey], false);
	
}elseif($ac=='model' && in_array($op, array('model_field', 'deletefield', 'copyfield'))){

    new Power('adminmodel_fields', $_SCONFIG['_POWER']['adminmodel_fields'], false);
	
}elseif(($ac=='content' && (!isset($op) || $op=='manage')) || in_array($ac,array('selectfile','cutimage'))){//内容管理特殊情况

    new Power('admincontent', $_SCONFIG['_POWER']['admincontent'], false);
	
}else{ new Power('admin'.$ac, $_SCONFIG['_POWER']['admin'.$ac], false);}
//处理模板
include_once($_SGLOBAL[_MODULE_]['path'].'/admin/'.$ac.'.inc.php');
$temp = "{$file}{$op}{$step}";
template("admin/{$ac}".($temp ? "_{$temp}" : ""));
include_once(_ROOT_.'/admin/footer.php');
?>