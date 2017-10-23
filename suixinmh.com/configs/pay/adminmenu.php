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

$jieqiAdminmenu['pay'][] = array('layer' => 0, 'caption' => '参数设置', 'command'=>JIEQI_URL.'/web_admin/?controller=configs&mod=pay', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['pay'][] = array('layer' => 0, 'caption' => '权限管理', 'command'=>JIEQI_URL.'/web_admin/?controller=power&mod=pay', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['pay'][] = array('layer' => 0, 'caption' => '权利设置', 'command'=>JIEQI_URL.'/web_admin/?controller=right&mod=pay', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['pay'][] = array('layer' => 0, 'caption' => 'URL路径管理', 'command'=>JIEQI_URL.'/web_admin/?controller=url&mod=pay', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['pay'][] = array('layer' => 0, 'caption' => '用户充值记录', 'command'=>$GLOBALS['jieqiModules']['pay']['url'].'/admin/', 'target' => 0, 'publish' => 1);

$jieqiAdminmenu['pay'][] = array('layer' => 0, 'caption' => '充值订单查询', 'command'=>$GLOBALS['jieqiModules']['pay']['url'].'/admin/?method=pay', 'target' => 0, 'publish' => 1);

?>