<?php
/**
 * 后台渠道管理导航配置
 *
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

// $jieqiAdminmenu['pooling'][] = array('layer' => 0, 'caption' => '参数设置', 'command'=>JIEQI_URL.'/web_admin/?controller=configs&mod=pooling', 'target' => 0, 'publish' => 1);


// $jieqiAdminmenu['pooling'][] = array('layer' => 0, 'caption' => '权限管理', 'command'=>JIEQI_URL.'/web_admin/?controller=power&mod=pooling', 'target' => 0, 'publish' => 1);
// $jieqiAdminmenu['pooling'][] = array('layer' => 0, 'caption' => '渠道管理', 'command'=>$GLOBALS['jieqiModules']['pooling']['url'].'/admin/?controller=channel&mod=pooling', 'target' => 0, 'publish' => 1);

//$jieqiAdminmenu['pooling'][] = array('layer' => 0, 'caption' => '权利设置', 'command'=>JIEQI_URL.'/web_admin/?controller=right&mod=pooling', 'target' => 0, 'publish' => 1);


$jieqiAdminmenu['tingshu'][] = array('layer' => 0, 'caption' => '参数设置', 'command'=>JIEQI_URL.'/web_admin/?controller=configs&mod=tingshu', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['tingshu'][] = array('layer' => 0, 'caption' => '权限管理', 'command'=>JIEQI_URL.'/web_admin/?controller=power&mod=tingshu', 'target' => 0, 'publish' => 1);

// $jieqiAdminmenu['tingshu'][] = array('layer' => 0, 'caption' => '权利设置', 'command'=>JIEQI_URL.'/web_admin/?controller=right&mod=tingshu', 'target' => 0, 'publish' => 1);



$jieqiAdminmenu['tingshu'][] = array('layer' => 0, 'caption' => 'URL路径管理', 'command'=>JIEQI_URL.'/web_admin/?controller=url&mod=tingshu', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['tingshu'][] = array('layer' => 0, 'caption' => '分类管理', 'command'=>$GLOBALS['jieqiModules']['tingshu']['url'].'/admin/?controller=sortmanage', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['tingshu'][] = array('layer' => 0, 'caption' => '文章管理', 'command'=>$GLOBALS['jieqiModules']['tingshu']['url'].'/admin/?controller=article', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['tingshu'][] = array('layer' => 0, 'caption' => '更新记录', 'command'=>$GLOBALS['jieqiModules']['tingshu']['url'].'/admin/?controller=chapter', 'target' => 0, 'publish' => 1);


$jieqiAdminmenu['tingshu'][] = array('layer' => 0, 'caption' => '单篇采集', 'command'=>$GLOBALS['jieqiModules']['tingshu']['url'].'/admin/?controller=collect', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['tingshu'][] = array('layer' => 0, 'caption' => '批量采集', 'command'=>$GLOBALS['jieqiModules']['tingshu']['url'].'/admin/?controller=batchcollect', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['tingshu'][] = array('layer' => 0, 'caption' => '采集配置', 'command'=>$GLOBALS['jieqiModules']['tingshu']['url'].'/admin/?controller=collectset', 'target' => 0, 'publish' => 1);



?>