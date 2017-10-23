<?php
/*
	[Cms News] (C) 2009-2010 CMs Inc.
	$Id: function_news.php  2010-06-03 09:56:09Z huliming $
*/

/**
 * 传入文章实例对象，返回适合模板赋值的文章信息数组
 * 
 * @param      object      $news 文章实例
 * @access     public
 * @return     array
 */
/*function get_news_vars($news, $output = false){
    global $_SGLOBAL,$_OBJ;
	if(!is_object($_SGLOBAL['category'])) $_OBJ['category'] = new Category();
	if(!is_object($_SGLOBAL['model'])) $_OBJ['model'] =new Model();
	//加载content类，用与判断当前文章是否生成静态
	if(!is_object($_OBJ['content'])) $_OBJ['content'] = new Content();
    $i = 0;
	$ret = array();
	foreach($news->vars as $k=>$v){
		if($i && array_key_exists($k, $ret)) continue;
		$ret[$k]=$news->getVar($k,'n');
		$i++;
	}
	if($output && $ret['thumb'] && substr(strtolower($ret['thumb']),0,7)!='http://'){
	    $attachurl = $_OBJ['category']->getAttachurl($ret['catid']);
	    $ret['thumb'] = $attachurl.$ret['thumb'];
	}
	if($_OBJ['content']->isHtml($ret)) $ret['ishtml'] = true;
	else  $ret['ishtml'] = false;
	$ret['alltitle'] = style($ret['title'], $ret['style'], $ret['thumb']);
	$ret['catname'] = $_SGLOBAL['category'][$ret['catid']]['catname'];
	$ret['modelid'] = $_SGLOBAL['category'][$ret['catid']]['modelid'];
	$ret['modelname'] = $_SGLOBAL['model'][$_SGLOBAL['category'][$ret['catid']]['modelid']]['name'];
	$ret['url_articleinfo'] = jieqi_geturl('news', 'show', $ret, 1);
	$ret['url_catelist'] = $_OBJ['category']->getUrl($ret['catid']);
	return $ret;
}

function style($str, $style, $thumb = ''){
    if(!$style && !$thumb) return $str;
	$str = $style ?"<span class=\"{$style}\">{$str}</span>" :$str;
	return "$str".($thumb ?' <font color="red">图</font>' :'');
}*/

/*function out_content($id, $page = 1, $createhtml = false){
	global $_SGLOBAL, $_SCONFIG, $_SN, $_TPL, $jieqiTset, $jieqiTpl, $_PAGE, $_OBJ;
	//print_r($_SGLOBAL['category']);exit;
	//初始化栏目操作对像和加载栏目数据列表
	if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
	//加载模型
	if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
	//内容获取类
	if(!is_object($_OBJ['content'])) $_OBJ['content'] = new Content();
	//加载表单对象类
	include_once($_SGLOBAL['news']['path'].'/include/fields/formelements.class.php');
	
	//获取内容
	if(!is_array($_PAGE['data'] = $_OBJ['content']->get($id))) return false;
	
	//获取栏目内容
	$_SGLOBAL['cate'] = $_OBJ['category']->get($_PAGE['data']['catid']);
	
	//加载表单数据对象类
	$elements = new FormElements($_SGLOBAL['cate']['modelid'], $_PAGE['data']['catid']);
	if(!$_PAGE['data'] = $elements->show($_PAGE['data'])) return false;
	
	$_PAGE['data']['___content'] = $elements->getVar('___content');
	if($page>count($_PAGE['data']['___content']['content'])+1) return false; //当前页码大于总页数
	//如果是生成静态
	if($createhtml){
	    $ret = array();
		foreach($_PAGE['data']['___content']['content'] as $page=>$value){
		    $page++;//页码从1开始
			$_PAGE['data']['__content'] = $value;
			
			//内容分页
			//$_PAGE['url_jumppage'] = get_contentpage($page, $_PAGE['data']);
			$jumppage = new GlobalPage($_SCONFIG['articlepages'],count($_PAGE['data']['___content']['content']),1,$page);
			$linkurl = $_OBJ['content']->getUrl($_PAGE['data'], 1, false);
			$urlrule = $_OBJ['content']->getUrlrule($_PAGE['data'], 1, false);
			if(!substr_count($urlrule, '/')){
				$jumppage->emptyonepage = $id.'.'.fileext($linkurl);
			}else $jumppage->emptyonepage = '';
			$_PAGE['url_jumppage'] = $jumppage->getPage($linkurl);
			
			template($_PAGE['data']['_showtemplate']);
			$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template']));
			if(!is_file($jieqiTset['jieqi_page_template']) && strpos($jieqiTset['jieqi_page_template'], '/') == 0) $jieqiTset['jieqi_page_template']=_ROOT_.$jieqiTset['jieqi_page_template'];
			$ret[$page] = $jieqiTpl->fetch($jieqiTset['jieqi_page_template']);
			unset($jieqiTset['jieqi_contents_template']);
			unset($jumppage);
			unset($_PAGE['url_jumppage']);
		}
	}else{
	    $ret = '';
	    $_PAGE['data']['__content'] = $_PAGE['data']['___content']['content'][$page-1];
		//内容分页
		//$_PAGE['url_jumppage'] = get_contentpage($page, $_PAGE['data']);
		$jumppage = new GlobalPage($_SCONFIG['articlepages'],count($_PAGE['data']['___content']['content']),1,$page);
		$linkurl = $_OBJ['content']->getUrl($_PAGE['data'], 1, false);
		$urlrule = $_OBJ['content']->getUrlrule($_PAGE['data'], 1, false);
		if(!substr_count($urlrule, '/')){
		    $jumppage->emptyonepage = $id.'.'.fileext($linkurl);
		}else $jumppage->emptyonepage = '';
		$_PAGE['url_jumppage'] = $jumppage->getPage($linkurl);
		
		template($_PAGE['data']['_showtemplate']);global $jieqiTset;
		$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template']));
		if(!is_file($jieqiTset['jieqi_page_template']) && strpos($jieqiTset['jieqi_page_template'], '/') == 0) $jieqiTset['jieqi_page_template']=_ROOT_.$jieqiTset['jieqi_page_template'];
		$ret = $jieqiTpl->fetch($jieqiTset['jieqi_page_template']);
	}
	jieqi_freeresource();
//显示DEBUG信息
define('JIEQI_DEBUG_MODE',true);
if(defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0){
	$runtime = explode(' ', microtime());
	$debuginfo = 'Processed in '.round($runtime[1] + $runtime[0] - JIEQI_START_TIME, 6).' second(s), ';
	if(function_exists('memory_get_usage')) $debuginfo .= 'Memory usage '.round(memory_get_usage()/1024).'K, ';
	$sqllog = array();
	if(defined('JIEQI_DB_CONNECTED')){
		$instance =& JieqiDatabase::retInstance();
		if(!empty($instance)){
			foreach($instance as $db){
				$sqllog = array_merge($sqllog, $db->sqllog('ret'));
			}
		}
	}
	$queries = count($sqllog);
	$debuginfo .= $queries.' queries, ';
	if(defined('JIEQI_USE_GZIP') && JIEQI_USE_GZIP > 0) $debuginfo .= 'Gzip enabled.';
	else $debuginfo .= 'Gzip disabled.';
	if($queries > 0){
		foreach($sqllog as $sql) $debuginfo .= '<br />'.$sql;
	}
	echo '<div class="divbox">'.$debuginfo.'</div>';
}
	return $ret;
}*/

/*function out_category($catid, $page = 1){
	global $_SGLOBAL, $_SCONFIG, $_SN, $_TPL, $jieqiTset, $jieqiTpl, $_PAGE, $_OBJ;
	//初始化栏目操作对像和加载栏目数据列表
	if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
	//获取栏目内容
	if(!($_SGLOBAL['cate'] = $_OBJ['category']->get($catid, true))) return false;
	//加载模型
	if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
	if($_SGLOBAL['model'][$_SGLOBAL['cate']['modelid']]['disabled']) return false;
	
	if($_OBJ['category']->islist($catid)){
		//获取分类文章列表
		$content = & new Content();
		
		//构造查询表
		$tables = array();
		if($_SCONFIG['showarticlelists'] & 1) $tables[$content->tablepre.'c_'.$_SGLOBAL['model'][$_SGLOBAL['cate']['modelid']]['tablename']] = '*';  //文章附加表表名
		if($_SCONFIG['showarticlelists'] & 2) $tables[$content->table_count] = 'hits,hits_day,hits_week,hits_month,hits_time,comments,comments_checked'; //文章统计表表名
		if($_SCONFIG['showarticlelists'] & 4) $tables[$content->table_digg] = 'supports,againsts,supports_day,againsts_day,supports_week,againsts_week,supports_month,againsts_month'; //文章DIGG表表名
		
		$tag = $tables ? 'tables' : NULL;
		$tabletag = ($tag ?'news_content.' :'');
		$content->setHandler($tag);
		
		if($tables){
		    $tablestr = $fields = '';
		    foreach($tables as $table=>$field){
				$tablestr.= " LEFT JOIN ".jieqi_dbprefix($table)." AS {$table} ON {$tabletag}contentid={$table}.contentid ";
				$fields.= ",{$table}.{$field}";
			}
			$content->criteria->setFields("{$tabletag}*{$fields}");
			$content->criteria->setTables(jieqi_dbprefix('news_content')." AS news_content {$tablestr}");
		}
		
		$content->criteria->add(new Criteria('catid', $catid));
		$content->criteria->add(new Criteria('status', 99));
		$content->criteria->setSort("{$tabletag}contentid");
		$content->criteria->setOrder('DESC');
		$pagesize = $_SCONFIG['pagenum'] ?$_SCONFIG['pagenum'] :30;
		$page = $page<1 ? 1 : $page;
		
		$_PAGE['articlerows'] = $content->lists($pagesize, $page, $_SCONFIG['categorypages'], true);//print_r($_PAGE['articlerows']);
		//在生成静态的时候，如果设置了最大更新页数
		$totalcount = $content->getVar('totalcount');
		$totalpage = ceil($totalcount/$pagesize);
		if($_OBJ['category']->isHtml($catid) && $_SCONFIG['maxpage'] && $totalpage>=$_SCONFIG['maxpage']){
		   $content->jumppage->setVar('totalpage', $_SCONFIG['maxpage']);
		}
		$_PAGE['url_jumppage'] = $content->getPage($_OBJ['category']->getUrl($catid, 1, false));
		
		//$totalcount = $content->getVar('totalcount');
		//$totalpage = ceil($totalcount/$pagesize);
		//$_SCONFIG['maxpage'] = $_SCONFIG['maxpage'] ?$_SCONFIG['maxpage'] :100;
		//if($_OBJ['category']->isHtml($catid) && $totalpage>$_SCONFIG['maxpage']) $totalpage = $_SCONFIG['maxpage'];
		//$_PAGE['url_jumppage'] = get_categorypage($page, array('catid'=>$catid, 'totalcount'=>$totalcount, 'pagesize'=>$pagesize, 'totalpage'=>$totalpage) );
		//print_r($_PAGE['articlerows']);
	}
	template($_OBJ['category']->getTemplate($catid));
	$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template']));
	if(!is_file($jieqiTset['jieqi_page_template']) && strpos($jieqiTset['jieqi_page_template'], '/') == 0) $jieqiTset['jieqi_page_template']=_ROOT_.$jieqiTset['jieqi_page_template'];
	$str = $jieqiTpl->fetch($jieqiTset['jieqi_page_template']);
	unset($jieqiTset['jieqi_contents_template']);
	jieqi_freeresource();
	return $str;
}*/

/*function get_categorypage($page, $data = array()){
    global $_SGLOBAL, $_SCONFIG;
	extract($data);
	//第一页
	if(strpos($_SCONFIG['categorypages'], '$firstpage')){
		$firstpage = jieqi_geturl('news', 'lists', $catid, 1);
	}
	//上一页
	if(strpos($_SCONFIG['categorypages'], '$prepage')){
		if($page<2) $prepage = jieqi_geturl('news', 'lists', $catid, 1);
		else $prepage = jieqi_geturl('news', 'lists', $catid, $page-1);
	}
	//下一页
	if(strpos($_SCONFIG['categorypages'], '$nextpage')){
		if($page<$totalpage) $nextpage = jieqi_geturl('news', 'lists', $catid, $page+1);
		else $nextpage = jieqi_geturl('news', 'lists', $catid, $totalpage);
	}
	//最后一页
	if(strpos($_SCONFIG['categorypages'], '$lastpage')){
		$lastpage = jieqi_geturl('news', 'lists', $catid, $totalpage);
	}
		
	//数字分页
	$pages = '';
	if(strpos($_SCONFIG['categorypages'], '$pages')){
		for($i=1; $i<=$totalpage; $i++){
			if($i != $page) $pages.= "<a href='".jieqi_geturl('news', 'lists', $catid, $i)."'>{$i}</a>  ";
			else $pages.= '<b>'.$i.'</b> ';
		}
	}
	
	eval('$articlepages = "'.saddslashes($_SCONFIG['categorypages']).'";');
	return $articlepages;
}*/

/*function get_contentpage($page, $data = array()){
    global $_SGLOBAL, $_SCONFIG;
	//总页数/只有一页的时候退出
	if(($totalpage = count($data['___content']['content']))<2) return false;
	$pagestr = $_SCONFIG['articlepages'];

	//上一页
	if(strpos($pagestr, '$prepage')){
		if($page<2){
		   if($prestr = exechars('[prepage]****[/prepage]',$pagestr)){
		       $pagestr = str_replace("[prepage]{$prestr}[/prepage]", '', $pagestr);
		   }else $prepage = $data['url_articleinfo'];
		}else{
		   if($prestr = exechars('[prepage]****[/prepage]',$pagestr)) $pagestr = str_replace("[prepage]{$prestr}[/prepage]", $prestr, $pagestr);
		   $prepage = jieqi_geturl('news', 'show', $data, $page-1);
		}
	}
	
	//下一页
	if(strpos($pagestr, '$nextpage')){
		if($page<$totalpage){
		   if($prestr = exechars('[nextpage]****[/nextpage]',$pagestr)) $pagestr = str_replace("[nextpage]{$prestr}[/nextpage]", $prestr, $pagestr);
		   $nextpage = jieqi_geturl('news', 'show', $data, $page+1);
		}else{
		   if($nextstr = exechars('[nextpage]****[/nextpage]',$pagestr)){
		       $pagestr = str_replace("[nextpage]{$nextstr}[/nextpage]", '', $pagestr);
		   }else $nextpage = jieqi_geturl('news', 'show', $data, $totalpage);
		}
	}
	
	//最后一页
	if(strpos($pagestr, '$lastpage')){
		$lastpage = jieqi_geturl('news', 'show', $data, $totalpage);
	}
		
	//数字分页
	$pages = '';
	if(strpos($pagestr, '$pages')){
		for($i=1; $i<=$totalpage; $i++){
			if($i != $page) $pages.= "<a href='".jieqi_geturl('news', 'show', $data, $i)."'>{$i}</a>  ";
			else $pages.= '<b>'.$i.'</b> ';
		}
	}
	eval('$articlepages = "'.saddslashes($pagestr).'";');
	return $articlepages;
}*/
?>