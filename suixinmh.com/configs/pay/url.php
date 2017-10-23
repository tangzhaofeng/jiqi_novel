<?php
$jieqiUrl['pay']=Array
	(
	'home_main' => Array
		(
		'id' => 60,
		'modname' => 'pay',
		'caption' => '默认充值路径',
		'controller' => 'home',
		'method' => '',
		'description' => '',
		'rule' => '/pay/$method',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'home33_pay33' => Array
		(
		'id' => 67,
		'modname' => 'pay',
		'caption' => '充值方法',
		'controller' => 'home33',
		'method' => 'pay33',
		'description' => '',
		'rule' => '/pay/$method',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		)
	)
?>