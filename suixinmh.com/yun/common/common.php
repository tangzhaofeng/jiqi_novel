<?php
 /**==============================
  * 公共路径文件包
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 =================================*/
define('ROOT', $_SERVER["DOCUMENT_ROOT"].'/yun/');
define('SMARTY_DIR', ROOT . 'Smarty-3.1.7/');
define('CLASS_DIR', ROOT . 'class/');
define('ACTION_DIR', ROOT.'action/');
define('DATA_DIR', ROOT.'data/');
define('LOG_DIR', ROOT.'data/log/');

include(ROOT . 'configs/config.php');//加载配置
include(ROOT . 'lang/'.LANGUAGE.'.php');//语言包
include(ROOT . 'common/functions.php');//公共函数
require_once(SMARTY_DIR . 'Smarty.class.php');//模板插件
require_once(CLASS_DIR . 'class.php');//类库

$smarty = new Smarty;   //New a smarty模板
//$smarty->force_compile = true;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 1;

$db = new Mysql($mysqlDb,$mysqlDb2);  //New a db