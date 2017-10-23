<?php
/**
 * 通用的页面预处理文件
 *
 * 页面预处理，加载功能模块
 * 
 * 调用模板：无
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) Hangzhou Network Technology Co.,Ltd.
 * @author     $Author: huliming QQ329222795 $
 * @version    $Id: index.php 332 2010-03-24 09:15:08Z huliming $
 */
define('IN_ADMIN', false);//定义后台处理头文件
include_once('./common.php');
include_once('./include/loadclass.php');//echo exechars('<p>!!!!</p>','aa');exit;
//jieqi_loadlang('showmessage', 'news');
//允许的方法
$acs = array('list', 'show', 'comment', 'blockshow', 'top', 'history','qidian');
$ac = getparameter('ac');
$ac = $_PAGE['ac'] = (empty($ac) || !in_array($ac, $acs))?'index':$ac;

//动作
$op = getparameter('op');

//处理模板
include_once($_SGLOBAL['news']['path'].'/source/'.$ac.'.inc.php');
include_once($_SGLOBAL['rootpath'].'/footer.php');
?>