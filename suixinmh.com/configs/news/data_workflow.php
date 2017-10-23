<?php
if(!defined('IN_JQNEWS')) exit('Access Denied');
$_SGLOBAL['workflow']=Array
	(
	'1' => Array
		(
		'workflowid' => 1,
		'name' => '一级审核',
		'description' => '一级审核方案，需要经过1次审核才能正式发布'
		),
	'2' => Array
		(
		'workflowid' => 2,
		'name' => '二级审核',
		'description' => '二级审核方案，需要经过2次审核才能正式发布'
		),
	'3' => Array
		(
		'workflowid' => 3,
		'name' => '三级审核',
		'description' => '三级审核方案，需要经过3次审核才能正式发布'
		)
	)
?>