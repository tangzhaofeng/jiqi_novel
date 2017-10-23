<?php
/*
	[CMS] (C) 2009-2012 huliming QQ329222795 Inc.
	$Id: search.inc.php  2012-08-16 11:30:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
getparameter('page');
getparameter('tag');
$_PAGE['articlerows'] = array();
$_PAGE['tag'] = iconv('utf-8','gbk',$_PAGE['tag']);
if($_PAGE['tag']){
    $_PAGE['tag'] = urldecode($_PAGE['tag']);
    $keywords = dictwrod($_PAGE['tag']);
	if($keywords){
	//echo $_PAGE['tag'];
	$content = new Content();
	$content->setHandler();
	$content->criteria->add(new Criteria('catid', "(65,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88)", 'in'));
		if(count($keywords)>1){
			foreach($keywords as $k=>$v){
				$left = !$k ? '(' : '';
				$content->criteria->add(new Criteria($left.'title', '%'.$v.'%', 'like'), !$k ? 'AND' :'OR');
			}
		}elseif(count(keywords)==1){
			$content->criteria->add(new Criteria('title', '%'.$keywords[0].'%', 'like'));
		}
	$content->criteria->add(new Criteria('status', 99), count($keywords)>1 ? ') AND' : 'AND');
	$content->criteria->setSort('contentid');
	$content->criteria->setOrder('DESC');
	$_PAGE['articlerows'] = $content->lists(20, $_PAGE['page'], $_SCONFIG['categorypages']);
	$_PAGE['url_jumppage'] = $content->getPage("tag_".urlencode(iconv('gbk','utf-8',$_PAGE['tag'])).'_<{$page}>.html');
	}
}
template('search_flash');
?>
