<?php
 /**==============================
  * 公共配置文件
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 =================================*/

//数据库配置
$mysqlDb = array('host' =>'localhost','port' =>3306, 'username' =>'yun', 'password' =>'T6XWhCvdqpDKb5JK', 'dbname' =>'yun');
$mysqlDb2 = array('host' =>'localhost','port' =>3306, 'username' =>'yun', 'password' =>'T6XWhCvdqpDKb5JK', 'dbname' =>'yun');

//定义语言包
define('LANGUAGE', 'zh_cn');
//网站入口
define('URL_INDEX', 'http://test.m.ishufun.net/yun/index.php');
//图形表文件地址
define('URL_CFRAME', 'http://test.m.ishufun.net/yun/cframe/');
//公共模块、无需登录状态验证模块
$publicModule = array('public',);

define('EXCEL_URL','excel/');

//数据表定义
define('SYS_PRE', 'admin_');//系统表前缀
define('ADMIN', SYS_PRE . 'user'); //用户表
define('ADMINGROUP', SYS_PRE . 'group'); //用户权限表
define('ADMINLOGS', SYS_PRE . 'logs'); //用户日志表
define('ADMINLOGIN', SYS_PRE . 'login'); //用户登录表




