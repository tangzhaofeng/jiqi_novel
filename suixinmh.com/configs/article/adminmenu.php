<?php
/**
 * 后台小说连载导航配置
 *
 * 后台小说连载导航配置
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
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

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '参数设置', 'command'=>JIEQI_URL.'/admin/?controller=configs&mod=article', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '权限管理', 'command'=>JIEQI_URL.'/admin/?controller=power&mod=article', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '权利设置', 'command'=>JIEQI_URL.'/admin/?controller=right&mod=article', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => 'URL路径管理', 'command'=>JIEQI_URL.'/admin/?controller=url&mod=article', 'target' => 0, 'publish' => 1);
//$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '分类管理', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=sortmanage', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '文章管理', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=article', 'target' => 0, 'publish' => 1);

//$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '文章导入', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=inbook', 'target' => 0, 'publish' => 1);

//$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '文章批量清理', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=batchclean', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '更新记录', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=chapter', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '更新统计', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=chapter&method=chapterStatistics', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '书评管理', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=reviews', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '回帖管理', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=replies', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '文章批量生成', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=batchrepack', 'target' => 0, 'publish' => 1);

//$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '伪静态页面生成', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=makefake', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '单篇采集', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=collect', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '批量采集', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=batchcollect', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '采集配置', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=collectset', 'target' => 0, 'publish' => 1);

//$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '批量替换', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=batchreplace', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '搜索关键词', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=search', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '文章删除日志', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=articlelog', 'target' => 0, 'publish' => 1);

//$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '文章黑名单', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=articleblacklist', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '作家申请记录', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=apply', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '文章订阅记录', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=salelog', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '文章销售统计', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=salestat', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '稿费管理', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=reward', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '分类管理', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=sortmanage', 'target' => 0, 'publish' => 1);
$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '包月书包管理', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=bookpackagemanage', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '书包销售统计', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=bookpackagemanage&method=bpsalecount', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '书包阅读统计', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=bookpackagemanage&method=bpclick', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['article'][] = array('layer' => 0, 'caption' => '标签管理', 'command'=>$GLOBALS['jieqiModules']['article']['url'].'/admin/?controller=tag', 'target' => 0, 'publish' => 1);
?>