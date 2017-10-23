<?php
$jieqiUrl['3gwap']=Array
	(
	'article_main' => Array
		(
		'id' => 102,
		'modname' => '3gwap',
		'caption' => '小说管理',
		'controller' => 'article',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/article/$method',
		'params' => 'aid=$aid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'articleinfo_main' => Array
		(
		'id' => 103,
		'modname' => '3gwap',
		'caption' => '小说详情',
		'controller' => 'articleinfo',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/book/{$aid}.htm',
		'params' => 'aid=$aid',
		'ishtml' => 99,
		'system' => '0'
		),
	'catalog_main' => Array
		(
		'id' => 104,
		'modname' => '3gwap',
		'caption' => '目录',
		'controller' => 'catalog',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/read/$aid/index.html',
		'params' => 'aid=$aid&order=$order',
		'ishtml' => 99,
		'system' => '0'
		),
	'sort_main' => Array
		(
		'id' => 105,
		'modname' => '3gwap',
		'caption' => '浏览小说分类页面',
		'controller' => 'sort',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/sort/{$sort}_{$size}_{$fullflag}_{$operate}_{$free}_{$page}.html',
		'params' => 'sort=$sort&size=$size&fullflag=$fullflag&operate=$operate&free=$free&page=$page',
		'ishtml' => 99,
		'system' => '0'
		),
	'dispatcher_main' => Array
		(
		'id' => 106,
		'modname' => '3gwap',
		'caption' => '小说频道',
		'controller' => 'dispatcher',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/{$type}/',
		'params' => 'type=$type',
		'ishtml' => 99,
		'system' => '0'
		),
	'getpass_main' => Array
		(
		'id' => 107,
		'modname' => '3gwap',
		'caption' => '找回密码',
		'controller' => 'getpass',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/getpass',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'huodong_main' => Array
		(
		'id' => 108,
		'modname' => '3gwap',
		'caption' => '活动',
		'controller' => 'huodong',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/huodong/$method/$aid.html',
		'params' => 'aid=$aid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'huodong_showBookCase' => Array
		(
		'id' => 109,
		'modname' => '3gwap',
		'caption' => '显示书架',
		'controller' => 'huodong',
		'method' => 'showBookCase',
		'description' => '',
		'rule' => 'http://m.shuhai.com/bookcase/{$type}/',
		'params' => 'type=$type',
		'ishtml' => 99,
		'system' => '0'
		),
	'login_main' => Array
		(
		'id' => 110,
		'modname' => '3gwap',
		'caption' => '登录',
		'controller' => 'login',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/login',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'pay_main' => Array
		(
		'id' => 111,
		'modname' => '3gwap',
		'caption' => '充值',
		'controller' => 'pay',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/pay/$method',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'reader_main' => Array
		(
		'id' => 112,
		'modname' => '3gwap',
		'caption' => '阅读',
		'controller' => 'reader',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/read/$aid/$cid.html',
		'params' => 'aid=$aid&cid=$cid',
		'ishtml' => 99,
		'system' => '0'
		),
	'register_main' => Array
		(
		'id' => 113,
		'modname' => '3gwap',
		'caption' => '注册',
		'controller' => 'register',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/register',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'reviews_main' => Array
		(
		'id' => 114,
		'modname' => '3gwap',
		'caption' => '评论控制器',
		'controller' => 'reviews',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/reviews/$aid/',
		'params' => 'aid=$aid&rid=$rid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'reviews_showReplies' => Array
		(
		'id' => 115,
		'modname' => '3gwap',
		'caption' => '评论回复列表',
		'controller' => 'reviews',
		'method' => 'showReplies',
		'description' => '',
		'rule' => 'http://m.shuhai.com/showReplies/$aid/?rid=$rid&page=$page',
		'params' => 'aid=$aid&rid=$rid&method=$method',
		'ishtml' => 99,
		'system' => '0'
		),
	'search_main' => Array
		(
		'id' => 116,
		'modname' => '3gwap',
		'caption' => '搜索',
		'controller' => 'search',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/search/$searchkey/$page',
		'params' => 'searchkey=$searchkey',
		'ishtml' => 99,
		'system' => '0'
		),
	'setpass_main' => Array
		(
		'id' => 117,
		'modname' => '3gwap',
		'caption' => '设置密码',
		'controller' => 'setpass',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/setpass',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'shuku_main' => Array
		(
		'id' => 118,
		'modname' => '3gwap',
		'caption' => '书库',
		'controller' => 'shuku',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/shuku/{$sort}_{$size}_{$fullflag}_{$operate}_{$free}_{$page}.html',
		'params' => 'sort=$sort&size=$size&fullflag=$fullflag&operate=$operate&free=$free&page=$page',
		'ishtml' => 99,
		'system' => '0'
		),
	'top_toplist' => Array
		(
		'id' => 119,
		'modname' => '3gwap',
		'caption' => '排行榜列表',
		'controller' => 'top',
		'method' => 'toplist',
		'description' => '',
		'rule' => 'http://m.shuhai.com/top/{$type}_{$sortid}_{$page}.html',
		'params' => 'type=$type&sortid=$sortid',
		'ishtml' => 99,
		'system' => '0'
		),
	'main_main' => Array
		(
		'id' => 120,
		'modname' => '3gwap',
		'caption' => '免费阅读',
		'controller' => '',
		'method' => '',
		'description' => '',
		'rule' => '',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'pwd_main' => Array
		(
		'id' => 121,
		'modname' => '3gwap',
		'caption' => '重置密码成功',
		'controller' => 'pwd',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/pwd',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'searchin_main' => Array
		(
		'id' => 122,
		'modname' => '3gwap',
		'caption' => '搜索首页',
		'controller' => 'searchin',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/searchin',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'channel_main' => Array
		(
		'id' => 123,
		'modname' => '3gwap',
		'caption' => '分类',
		'controller' => 'channel',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/{$class}/',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'userhub_main' => Array
		(
		'id' => 124,
		'modname' => '3gwap',
		'caption' => '我的账户',
		'controller' => 'userhub',
		'method' => '',
		'description' => '',
		'rule' => 'http://m.shuhai.com/user/$method',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		),
	'help_main' => Array
		(
		'id' => 127,
		'modname' => '3gwap',
		'caption' => '帮助',
		'controller' => 'help',
		'method' => 'main',
		'description' => '',
		'rule' => 'http://m.shuhai.com/help',
		'params' => '',
		'ishtml' => 99,
		'system' => '0'
		)
	)
?>