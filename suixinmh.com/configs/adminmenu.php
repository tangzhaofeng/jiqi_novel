<?php 
/**
 * 后台系统管理导航配置
 *
 * 后台系统管理导航配置
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: adminmenu.php 187 2008-11-24 09:30:03Z juny $
 */

/**
'layer'     - 菜单深度，默认 0
'caption'   - 菜单标题
'command'   - 链接的网址
'target'    - 点击链接是否打开新窗口(0-不新开；1-新开)
'publish'   - 是否显示（0-不显示；1-显示）
*/

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '系统定义', 'command'=>JIEQI_URL.'/web_admin/?controller=configs&mod=system&define=1', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '参数设置', 'command'=>JIEQI_URL.'/web_admin/?controller=configs&mod=system', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '权限设置', 'command'=>JIEQI_URL.'/web_admin/?controller=power&mod=system', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '权利设置', 'command'=>JIEQI_URL.'/web_admin/?controller=right&mod=system', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '用户组管理', 'command'=>JIEQI_URL.'/web_admin/?controller=groups', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '头衔管理', 'command'=>JIEQI_URL.'/web_admin/?controller=honors', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => 'VIP级别管理', 'command'=>JIEQI_URL.'/web_admin/?controller=vipgrade', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '区块管理', 'command'=>JIEQI_URL.'/web_admin/?controller=blocks', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '标签分类管理', 'command'=>JIEQI_URL.'/web_admin/?controller=positiontype', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '标签管理', 'command'=>JIEQI_URL.'/web_admin/?controller=position', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => 'URL路径管理', 'command'=>JIEQI_URL.'/web_admin/?controller=url', 'target' => 0, 'publish' => 1);

//$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '区块配置文件管理', 'command'=>JIEQI_URL.'/web_admin/?controller=manageblocks', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '模块配置管理', 'command'=>JIEQI_URL.'/web_admin/?controller=managemodules', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '用户管理', 'command'=>JIEQI_URL.'/web_admin/?controller=users', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '用户日志', 'command'=>JIEQI_URL.'/web_admin/?controller=userlog', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '用户报告', 'command'=>JIEQI_URL.'/web_admin/?controller=report', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '在线用户管理', 'command'=>JIEQI_URL.'/web_admin/?controller=online', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '系统收件箱', 'command'=>JIEQI_URL.'/web_admin/?controller=message&method=inbox', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '系统发件箱', 'command'=>JIEQI_URL.'/web_admin/?controller=message&method=outbox', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '发送短信', 'command'=>JIEQI_URL.'/web_admin/?controller=newmessage', 'target' => 0, 'publish' => 1);

//$jieqiAdminmenu['system'][] = array('layer' => 0, 'caption' => '生成静态首页', 'command'=>JIEQI_URL.'/?controller=indexs&refresh=1', 'target' => 0, 'publish' => 1);

//==========================================================
//数据库维护导航
//==========================================================

$jieqiAdminmenu['database'][] = array('layer' => 0, 'caption' => '数据库备份', 'command'=>JIEQI_URL.'/web_admin/?controller=dbmanage&method=export_view', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['database'][] = array('layer' => 0, 'caption' => '数据库恢复', 'command'=>JIEQI_URL.'/web_admin/?controller=dbmanage&method=import_view', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['database'][] = array('layer' => 0, 'caption' => '数据库优化', 'command'=>JIEQI_URL.'/web_admin/?controller=dbmanage&method=optimize_view', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['database'][] = array('layer' => 0, 'caption' => '数据库修复', 'command'=>JIEQI_URL.'/web_admin/?controller=dbmanage&method=repair_view', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['database'][] = array('layer' => 0, 'caption' => '数据库升级', 'command'=>JIEQI_URL.'/web_admin/?controller=dbmanage&method=upgrade_view', 'target' => 0, 'publish' => 1);

//==========================================================
//系统工具导航
//==========================================================

//$jieqiAdminmenu['tools'][] = array('layer' => 0, 'caption' => '刷新静态首页', 'command'=>JIEQI_URL.'/?controller=indexs&refresh=1', 'target' => 0, 'publish' => 1);

//$jieqiAdminmenu['tools'][] = array('layer' => 0, 'caption' => '清理区块缓存', 'command'=>JIEQI_URL.'/web_admin/?controller=syscleancache&target=blockcache', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['tools'][] = array('layer' => 0, 'caption' => '清理区块缓存', 'command'=>JIEQI_URL.'/web_admin/?controller=sysTools&method=cleancache_view&target=blockcache', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['tools'][] = array('layer' => 0, 'caption' => '清理网页缓存', 'command'=>JIEQI_URL.'/web_admin/?controller=sysTools&method=cleancache_view&target=cache', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['tools'][] = array('layer' => 0, 'caption' => '清理程序编译缓存', 'command'=>JIEQI_URL.'/web_admin/?controller=sysTools&method=cleancache_view&target=compiled', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['tools'][] = array('layer' => 0, 'caption' => '系统信息及优化建议', 'command'=>JIEQI_URL.'/web_admin/?controller=sysinfo', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['tools'][] = array('layer' => 0, 'caption' => '系统收件箱', 'command'=>JIEQI_URL.'/web_admin/?controller=message&method=inbox', 'target' => 0, 'publish' => 1);

?>