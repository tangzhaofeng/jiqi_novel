<?php 
/**
 * 后台新闻系统导航配置
 *
 * 后台新闻系统导航配置
 * 
 * 调用模板：无
 * 
 * @category   cms
 * @package    news
 * @copyright  Copyright (c) Hangzhou Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: huliming $
 * @version    $Id: adminmenu.php 187 2010-04-09 11:30:03Z huliming $
 */

/**
'layer'     - 菜单深度，默认 0
'caption'   - 菜单标题
'command'   - 链接的网址
'target'    - 点击链接是否打开新窗口(0-不新开；1-新开)
'publish'   - 是否显示（0-不显示；1-显示）
*/

$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '参数设置', 'command'=>JIEQI_URL.'/web_admin/?controller=configs&mod=news', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '权限设置', 'command'=>JIEQI_URL.'/web_admin/?controller=power&mod=news', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '权利设置', 'command'=>JIEQI_URL.'/web_admin/?controller=right&mod=news', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '栏目管理', 'command'=>$GLOBALS['jieqiModules']['news']['url'].'/admin/?ac=category', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '模型管理', 'command'=>$GLOBALS['jieqiModules']['news']['url'].'/admin/?ac=model', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '内容管理', 'command'=>$GLOBALS['jieqiModules']['news']['url'].'/admin/?ac=content', 'target' => 0, 'publish' => 1, 'ajaxurl'=>$GLOBALS['jieqiModules']['news']['url'].'/load.php?field=tree');

$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '评论管理', 'command'=>$GLOBALS['jieqiModules']['news']['url'].'/admin/?ac=comment', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '关键词管理', 'command'=>$GLOBALS['jieqiModules']['news']['url'].'/admin/?ac=set&file=keyword', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '标签管理', 'command'=>$GLOBALS['jieqiModules']['news']['url'].'/admin/?ac=position', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '文章采集', 'command'=>$GLOBALS['jieqiModules']['news']['url'].'/admin/?ac=collect', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '采集配置', 'command'=>$GLOBALS['jieqiModules']['news']['url'].'/admin/?ac=collectset', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['news'][] = array('layer' => 0, 'caption' => '生成HTML', 'command'=>$GLOBALS['jieqiModules']['news']['url'].'/admin/?ac=create', 'target' => 0, 'publish' => 1);
?>