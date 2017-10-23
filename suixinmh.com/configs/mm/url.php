<?php
$jieqiUrl['mm']=Array
	(
	'articleinfo_main' => Array
		(
		'id' => 130,
		'modname' => 'mm',
		'caption' => '小说详情',
		'controller' => 'articleinfo',
		'method' => '',
		'description' => '',
		'rule' => '/book/{$aid}.htm',
		'params' => 'aid=$aid',
		'ishtml' => 99,
		'system' => '0'
		),
	'index_main' => Array
		(
		'id' => 131,
		'modname' => 'mm',
		'caption' => '小说目录',
		'controller' => 'index',
		'method' => '',
		'description' => '目录',
		'rule' => '/book/$aid/index.html',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'reader_main' => Array
		(
		'id' => 132,
		'modname' => 'mm',
		'caption' => '小说免费阅读',
		'controller' => 'reader',
		'method' => '',
		'description' => '',
		'rule' => '/book/$aid/$cid.html',
		'params' => 'aid=$aid&cid=$cid',
		'ishtml' => 99,
		'system' => '0'
		),
	'reader_readvip' => Array
		(
		'id' => 133,
		'modname' => 'mm',
		'caption' => '小说vip阅读',
		'controller' => 'reader',
		'method' => 'readvip',
		'description' => '',
		'rule' => '/readvip/$aid/$cid',
		'params' => 'aid=$aid&cid=$cid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'reviews_main' => Array
		(
		'id' => 134,
		'modname' => 'mm',
		'caption' => '评论控制器',
		'controller' => 'reviews',
		'method' => '',
		'description' => '',
		'rule' => '/reviews/$aid/',
		'params' => 'aid=$aid&rid=$rid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'reviews_showReplies' => Array
		(
		'id' => 135,
		'modname' => 'mm',
		'caption' => '评论回复列表',
		'controller' => 'reviews',
		'method' => 'showReplies',
		'description' => '',
		'rule' => '/showReplies/$aid/?rid=$rid&page=$page',
		'params' => 'aid=$aid&rid=$rid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'shuku_main' => Array
		(
		'id' => 136,
		'modname' => 'mm',
		'caption' => '书库',
		'controller' => 'shuku',
		'method' => '',
		'description' => '书库，新增了tag参数',
		'rule' => '/shuku/{$sort}_{$tag}_{$size}_{$fullflag}_{$operate}_{$free}_{$page}.html',
		'params' => 'sort=$sort&tag=$tag&size=$size&fullflag=$fullflag&operate=$operate&free=$free&page=$page',
		'ishtml' => 99,
		'system' => '0'
		),
	'top_toplist' => Array
		(
		'id' => 137,
		'modname' => 'mm',
		'caption' => '排行榜列表',
		'controller' => 'top',
		'method' => 'toplist',
		'description' => '',
		'rule' => '/top/{$type}_{$sortid}_{$page}.html',
		'params' => 'type=$type&sortid=$sortid',
		'ishtml' => 99,
		'system' => '0'
		),
	'huodong_main' => Array
		(
		'id' => 139,
		'modname' => 'mm',
		'caption' => '活动',
		'controller' => 'huodong',
		'method' => '',
		'description' => '',
		'rule' => '/huodong/$method/$aid.html',
		'params' => 'aid=$aid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'channel_main' => Array
		(
		'id' => 162,
		'modname' => 'mm',
		'caption' => '浏览小说频道页面',
		'controller' => 'channel',
		'method' => '',
		'description' => '',
		'rule' => '/{$class}/',
		'params' => 'class=$class',
		'ishtml' => 99,
		'system' => '0'
		)
	)
?>