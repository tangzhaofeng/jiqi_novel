<?php
$jieqiUrl['system']=Array
	(
	'userhub_main' => Array
		(
		'id' => 10,
		'modname' => 'system',
		'caption' => '用户中心',
		'controller' => 'userhub',
		'method' => '',
		'description' => '用户中心',
		'rule' => '/user/$method',
		'params' => '',
		'ishtml' => '0',
		'system' => '0'
		),
	'login_main' => Array
		(
		'id' => 13,
		'modname' => 'system',
		'caption' => '登陆登陆',
		'controller' => 'login',
		'method' => '',
		'description' => '',
		'rule' => '/login',
		'params' => '',
		'ishtml' => '0',
		'system' => '0'
		),
	'register_main' => Array
		(
		'id' => 35,
		'modname' => 'system',
		'caption' => '用户注册',
		'controller' => 'register',
		'method' => '',
		'description' => '',
		'rule' => '/register',
		'params' => '',
		'ishtml' => '0',
		'system' => '0'
		),
	'userhub_userinfo' => Array
		(
		'id' => 65,
		'modname' => 'system',
		'caption' => '用户信息',
		'controller' => 'userhub',
		'method' => 'userinfo',
		'description' => '',
		'rule' => '/user/{$uid}.html',
		'params' => 'uid=$uid',
		'ishtml' => 99,
		'system' => '0'
		),
	'help_main' => Array
		(
		'id' => 68,
		'modname' => 'system',
		'caption' => '帮助',
		'controller' => 'help',
		'method' => 'main',
		'description' => '',
		'rule' => '/help',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'about_main' => Array
		(
		'id' => 69,
		'modname' => 'system',
		'caption' => '关于我们',
		'controller' => 'about',
		'method' => '',
		'description' => '',
		'rule' => '/about/$method',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'tag_main' => Array
		(
		'id' => 70,
		'modname' => 'system',
		'caption' => '标签路径',
		'controller' => 'tag',
		'method' => '',
		'description' => '',
		'rule' => '/postion/{$id}.html',
		'params' => 'id=$id',
		'ishtml' => 99,
		'system' => '0'
		),
	'userhub_zuozhe' => Array
		(
		'id' => 71,
		'modname' => 'system',
		'caption' => '作者信息',
		'controller' => 'userhub',
		'method' => 'zuozhe',
		'description' => '',
		'rule' => '/zuozhe/{$uid}.html',
		'params' => 'uid=$uid',
		'ishtml' => 99,
		'system' => '0'
		),
	'getpass_main' => Array
		(
		'id' => 98,
		'modname' => 'system',
		'caption' => '找回密码',
		'controller' => 'getpass',
		'method' => '',
		'description' => '',
		'rule' => '/getpass',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'setpass_main' => Array
		(
		'id' => 99,
		'modname' => 'system',
		'caption' => '重设密码',
		'controller' => 'setpass',
		'method' => '',
		'description' => '',
		'rule' => '/setpass',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'checkcode_main' => Array
		(
		'id' => 162,
		'modname' => 'system',
		'caption' => '验证码',
		'controller' => 'checkcode',
		'method' => '',
		'description' => '验证码',
		'rule' => '/checkcode',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		)
	)
?>