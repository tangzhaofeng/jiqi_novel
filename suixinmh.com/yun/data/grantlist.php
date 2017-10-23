<?php
 /**==============================
  * 操作权限列表数据
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 =================================*/
global $allGrants;
$allGrants = array(
                //数据后台
				array('key' =>1000, 'title' =>'管理后台'),
                    array('key' =>1100, 'title' =>'玩家管理','module' =>'users','icon' =>'icon-user'),
                        array('key' =>1101, 'title' =>'玩家列表', 'option' =>'userlist'),
						array('key' =>1102, 'title' =>'登录列表', 'option' =>'loginlist'),
						array('key' =>1103, 'title' =>'区域统计', 'option' =>'arealist'),
						array('key' =>1104, 'title' =>'在线列表', 'option' =>'onlinelist'),
						array('key' =>1105, 'title' =>'留存统计', 'option' =>'retention'),
		
						array('key' =>1109, 'title' =>'玩家信息', 'option' =>'userinfo'),
						array('title' =>'封禁操作', 'option' =>'freeze'),
						array('title' =>'关联帐号查询', 'option' =>'ip_users'),
					array('key' =>1200, 'title' =>'游戏管理','module' =>'game','icon' =>'icon-game-controller'),
						array('key' =>1201, 'title' =>'游戏记录', 'option' =>'gamelogs'),
						array('key' =>1202, 'title' =>'游戏报表', 'option' =>'dayitv'),
						array('key' =>1203, 'title' =>'玩家报表', 'option' =>'usersitv'),
						array('key' =>1204, 'title' =>'实时库存', 'option' =>'stocklist'),
						array('key' =>1205, 'title' =>'库存偏移', 'option' =>'kval'),
		
						array('title' =>'单日游戏', 'option' =>'roomcount'),
						array('title' =>'中奖详情', 'option' =>'loginfo'),
					array('key' =>1300, 'title' =>'账务管理','module' =>'bet','icon' =>'icon-diamond'),
						array('key' =>1301, 'title' =>'账务记录', 'option' =>'dayDesc'),
						array('key' =>1302, 'title' =>'每日账务', 'option' =>'daycount'),
						array('key' =>1303, 'title' =>'充洗排行', 'option' =>'betorders'),
		
					array('key' =>2800, 'title' =>'系统管理', 'module' =>'admin','icon' =>'icon-settings',),
						array('key' =>2801, 'title' =>'管理列表', 'option' =>'userList'),
						array('key' =>2802, 'title' =>'权限列表', 'option' =>'groupList'),
						array('key' =>2803, 'title' =>'登录记录', 'option' =>'loginLogs'),
                               
            );