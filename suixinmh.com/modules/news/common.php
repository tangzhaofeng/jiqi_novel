<?php
/**
 * 后台通用的页面预处理文件
 *
 * 后台页面预处理，包括加载变量，页面预处理工作
 * 
 * 调用模板：无
 * 
 * @category   cms
 * @package    news
 * @copyright  Copyright (c) Hangzhou Network Technology Co.,Ltd.
 * @author     $Author: huliming QQ329222795 $
 * @version    $Id: common.php 2010-04-09 09:15:08Z huliming $
 */
//define('JIEQI_DEBUG_MODE',true);
@define('IN_JQNEWS', TRUE);
define('X_JQNEWS', '1.0');
if(!defined('IN_ADMIN') ) define('IN_ADMIN', false);
//if(!isset($_SESSION)) @session_start();

//加载页面预处理文件
if(!defined('JIEQI_MODULE_NAME')) define('JIEQI_MODULE_NAME','news');

if(!defined('JIEQI_GLOBAL_INCLUDE')){
	$repeat = IN_ADMIN;
	if(JIEQI_MODULE_NAME!='system') $repeat = $repeat +1;
	include_once(str_repeat('../', $repeat).'../global.php');
	if(!defined('JIEQI_GLOBAL_INCLUDE') || !JIEQI_GLOBAL_INCLUDE) exit('System files are not complete!');
}

define('_MODULE_', JIEQI_MODULE_NAME);
define('_ROOT_', JIEQI_ROOT_PATH);
define('_NOW_', JIEQI_NOW_TIME);
define('_SITE_', JIEQI_SITE_NAME);
define('SITE_ID', JIEQI_SITE_ID);
define('_LOCAL_', JIEQI_LOCAL_URL);
define('CHARSET', JIEQI_SYSTEM_CHARSET);
define('USE_CACHE', JIEQI_USE_CACHE);
define('CACHE_PATH', JIEQI_CACHE_PATH);
define('CACHE_LIFETIME', JIEQI_CACHE_LIFETIME);
define('USER_CHARSET', JIEQI_CHAR_SET);
//加载系统变量
jieqi_getconfigs(_MODULE_,'configs');
//加载权限设置
jieqi_getconfigs(_MODULE_, 'power');unset($jieqiGroups);
jieqi_getconfigs('system','groups');
if(_MODULE_!='system') jieqi_getconfigs('system', 'configs');
require_once(_ROOT_.'/'.(IN_ADMIN ? 'admin/' : '').'header.php');
//初始化配置文件
$_SGLOBAL = $_SCONFIG = $_PAGE = $_SCOOKIE = $_SN = array();
//赋值配置文件
$_SCONFIG = $GLOBALS['jieqiConfigs'][_MODULE_];
$_SCONFIG['_SYSTEM'] = $GLOBALS['jieqiConfigs']['system'];
$_SCONFIG['_POWER'] = $jieqiPower[_MODULE_];
//公用变量
$_SGLOBAL = $GLOBALS['jieqiModules'];
$_SGLOBAL['timestamp'] = _NOW_;
$_SGLOBAL['rootpath'] = _ROOT_;
$_SGLOBAL['sitename'] = _SITE_;
$_SGLOBAL['localurl'] = _LOCAL_;
$_SGLOBAL['modelname'] = _MODULE_;
//$_SGLOBAL['emptytheme'] = JIEQI_ROOT_PATH.'/themes/empty.html';
$_SGLOBAL['siteid'] = SITE_ID;
$_SGLOBAL['_GROUPS'] = $GLOBALS['jieqiGroups'];
//初始化
$_SGLOBAL['supe_uid'] = 0;
$_SGLOBAL['supe_username'] = '';
$_SGLOBAL['refer'] = empty($_SERVER['HTTP_REFERER'])?'':$_SERVER['HTTP_REFERER'];
$_SGLOBAL['currenturl'] = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
$_SGLOBAL['host'] = $_SERVER['HTTP_HOST'];
//通用函数
include_once($_SGLOBAL['news']['path'].'/include/function_common.php');
//获取参数
$_PAGE['_GET'] = saddslashes($_GET);
$_PAGE['_POST'] = saddslashes($_POST);
$_PAGE['_GET']['inajax'] = $_PAGE['_GET']['inajax'] ?$_PAGE['_GET']['inajax'] :($_PAGE['_GET']['ajax_request'] ?$_PAGE['_GET']['ajax_request'] :$_PAGE['_POST']['ajax_request']);
$_SGLOBAL['inajax'] = $_PAGE['_GET']['inajax'];
$_SGLOBAL['ajaxmenuid'] = empty($_PAGE['_GET']['ajaxmenuid'])?'':$_PAGE['_GET']['ajaxmenuid'];
//判断用户登录状态
checkauth();
?>