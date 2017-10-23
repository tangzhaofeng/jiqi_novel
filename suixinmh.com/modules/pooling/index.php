<?php
define('JIEQI_MODULE_NAME', 'pooling');
function getCategory_zuidie($category=0){//醉蝶的分类
	$sort = array(
			1=>'玄幻',
			2=>'都市',
			3=>'仙侠',
			4=>'网游',
			5=>'穿越',
			6=>'仙侠',
			7=>'网游',
			8=>'历史',
			9=>'豪门',
			10=>'恐怖',
			11=>'青春',
			12=>'古言'
	);
	if(isset($sort[$category])) return $sort[$category];
	else return '玄幻';
}
function getCategory_2345($category=0){//2345的分类
		 $sort = array(
		        1=>'玄幻',
				2=>'都市',
				3=>'仙侠',
				4=>'游戏',
				5=>'科幻',
				6=>'武侠',
				7=>'游戏',
				8=>'历史',
				9=>'军事',
				10=>'灵异',
				11=>'武侠',
				12=>'现代言情'
		 );
	     if(isset($sort[$category])) return $sort[$category];
		 else return '都市';
	}
function getCategory_360($category=0){//360的分类
		 $sort = array(
		        1=>'玄幻',
				2=>'都市',
				3=>'仙侠',
				4=>'游戏',
				5=>'科幻',
				6=>'武侠',
				7=>'奇幻',
				8=>'历史',
				9=>'军事',
				10=>'悬疑',
				11=>'耽美同人',
				12=>'现代言情'
		 );
	     if(isset($sort[$category])) return $sort[$category];
		 else return '都市';
	}
function getCategory_shenma($category=0){//神马的分类
		$sort = array(
				1=>'玄幻',
				2=>'都市',
				3=>'仙侠',
				4=>'游戏',
				5=>'科幻',
				6=>'武侠',
				7=>'奇幻',
				8=>'历史',
				9=>'军事',
				10=>'悬疑',
				11=>'耽美',
				12=>'其它'
		);
		if(isset($sort[$category])) return $sort[$category];
		else return '其他';
	}
require '../../index.php';
?>