<?php
$jieqiUrl['wap']=Array
	(
	'articleinfo_main' => Array
		(
		'id' => 82,
		'modname' => 'wap',
		'caption' => '小说详情',
		'controller' => 'articleinfo',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/book/{$aid}.htm',
		'params' => 'aid=$aid',
		'ishtml' => 99,
		'system' => '0'
		),
	'reviews_main' => Array
		(
		'id' => 83,
		'modname' => 'wap',
		'caption' => '评论控制器',
		'controller' => 'reviews',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/reviews/$aid/',
		'params' => 'aid=$aid&rid=$rid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'catalog_main' => Array
		(
		'id' => 84,
		'modname' => 'wap',
		'caption' => '目录',
		'controller' => 'catalog',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/chapter/$aid/',
		'params' => 'aid=$aid&order=$order',
		'ishtml' => 99,
		'system' => '0'
		),
	'login_main' => Array
		(
		'id' => 85,
		'modname' => 'wap',
		'caption' => '登录',
		'controller' => 'login',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/login',
		'params' => '',
		'ishtml' => '0',
		'system' => '0'
		),
	'huodong_main' => Array
		(
		'id' => 86,
		'modname' => 'wap',
		'caption' => '活动',
		'controller' => 'huodong',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/huodong/$method/$aid.html',
		'params' => 'aid=$aid&method=$method',
		'ishtml' => '0',
		'system' => '0'
		),
	'dispatcher_main' => Array
		(
		'id' => 87,
		'modname' => 'wap',
		'caption' => '频道分站',
		'controller' => 'dispatcher',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/{$type}/',
		'params' => 'type=$type',
		'ishtml' => 99,
		'system' => '0'
		),
	'reader_main' => Array
		(
		'id' => 88,
		'modname' => 'wap',
		'caption' => '阅读',
		'controller' => 'reader',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/read/$aid/$cid.html',
		'params' => 'aid=$aid&cid=$cid',
		'ishtml' => 99,
		'system' => '0'
		),
	'register_main' => Array
		(
		'id' => 89,
		'modname' => 'wap',
		'caption' => '注册',
		'controller' => 'register',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/register',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'shuku_main' => Array
		(
		'id' => 90,
		'modname' => 'wap',
		'caption' => '书库',
		'controller' => 'shuku',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/shuku/{$sort}_{$size}_{$fullflag}_{$operate}_{$free}_{$page}.html',
		'params' => 'sort=$sort&size=$size&fullflag=$fullflag&operate=$operate&free=$free&page=$page&siteid=$siteid',
		'ishtml' => 99,
		'system' => '0'
		),
	'search_main' => Array
		(
		'id' => 91,
		'modname' => 'wap',
		'caption' => '搜索',
		'controller' => 'search',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/search/$searchkey/$page',
		'params' => 'searchkey=$searchkey',
		'ishtml' => 99,
		'system' => '0'
		),
	'huodong_showBookCase' => Array
		(
		'id' => 92,
		'modname' => 'wap',
		'caption' => '显示书架',
		'controller' => 'huodong',
		'method' => 'showBookCase',
		'description' => '',
		'rule' => 'http://m.ishufun.net/bookcase/{$type}/',
		'params' => 'type=$type',
		'ishtml' => 99,
		'system' => '0'
		),
	'reviews_showReplies' => Array
		(
		'id' => 93,
		'modname' => 'wap',
		'caption' => '评论回复列表',
		'controller' => 'reviews',
		'method' => 'showReplies',
		'description' => '',
		'rule' => 'http://m.ishufun.net/showReplies/$aid/?rid=$rid&page=$page',
		'params' => 'aid=$aid&rid=$rid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'article_main' => Array
		(
		'id' => 94,
		'modname' => 'wap',
		'caption' => '小说管理',
		'controller' => 'article',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/article/$method',
		'params' => 'aid=$aid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'top_toplist' => Array
		(
		'id' => 95,
		'modname' => 'wap',
		'caption' => '排行榜列表',
		'controller' => 'top',
		'method' => 'toplist',
		'description' => '',
		'rule' => 'http://m.ishufun.net/top/{$type}_{$sortid}_{$page}.html',
		'params' => 'type=$type&sortid=$sortid',
		'ishtml' => 99,
		'system' => '0'
		),
	'channel_main' => Array
		(
		'id' => 96,
		'modname' => 'wap',
		'caption' => '浏览小说分类页面',
		'controller' => 'channel',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/{$class}/',
		'params' => 'class=$class',
		'ishtml' => 99,
		'system' => '0'
		),
	'pay_main' => Array
		(
		'id' => 97,
		'modname' => 'wap',
		'caption' => '默认充值路径',
		'controller' => 'pay',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/pay/$method',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'getpass_main' => Array
		(
		'id' => 100,
		'modname' => 'wap',
		'caption' => '找回密码',
		'controller' => 'getpass',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/getpass',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'setpass_main' => Array
		(
		'id' => 101,
		'modname' => 'wap',
		'caption' => '设置密码',
		'controller' => 'setpass',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/setpass',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'userhub_main' => Array
		(
		'id' => 140,
		'modname' => 'wap',
		'caption' => '个人中心',
		'controller' => 'userhub',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.ishufun.net/user/$method',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		)
	)
?>