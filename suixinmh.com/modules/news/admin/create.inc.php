<?php
/*
	[Cms news] (C) 2010-2012 Cms Inc.
	$Id: create.inc.php  2010-06-11 17:15:09Z huliming QQ329222795$
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
$op = !$op ? 'category' : $op;

//初始化栏目操作对像和加载栏目数据列表
$_OBJ['category'] = new Category();
$_PAGE['posturl'] = $_OBJ['category']->getPosturl($catid);//表单提交文章URL
$_PAGE['cateurl'] = $_OBJ['category']->getCateurl($catid);//表单提交栏目URL
//$_PAGE['attachurl'] = $_OBJ['category']->getAttachurl($catid);//附件URL服务器
//在多服务器环境下，表单地址要和附件服务器URL保持一致，否则附件上传会出错
//if($_PAGE['attachurl']!= 'http://'.$_SERVER['HTTP_HOST']) header("location:".$_PAGE['attachurl'].$_SERVER['REQUEST_URI']);
//内容获取类

$_OBJ['content'] = new Content();
//加载静态生成对象类
include_once($jieqiModules['news']['path'].'/include/html.class.php');
$_OBJ['html'] = new Html();
$_PAGE['pagesize'] = $_PAGE['_POST']['pagesize'] ?$_PAGE['_POST']['pagesize'] :$_PAGE['_GET']['pagesize'];//每轮更新
$_PAGE['pagesize'] = intval($_PAGE['pagesize']) ?intval($_PAGE['pagesize']) :100;
$_PAGE['page'] = intval($_PAGE['_GET']['page']) ?intval($_PAGE['_GET']['page']) :1;
if($op == 'category'){
    //更新数据
	if(isset($_PAGE['_GET']['n'])){
		$_PAGE['_GET']['n'] = intval($_PAGE['_GET']['n']) ?intval($_PAGE['_GET']['n']) :0;//运行位置
		$catids = explode(',', urldecode($_PAGE['_GET']['catids']));
		if($catids[$_PAGE['_GET']['n']]){//正常执行
			$cate = $_OBJ['category']->get($catids[$_PAGE['_GET']['n']]);
			$jumpurl = "?ac=create&op=category&pagesize={$_PAGE['_GET']['pagesize']}&catids=".urlencode($_PAGE['_GET']['catids']);
			if(!$_OBJ['category']->isHtml($cate['catid'])){
			    jieqi_jumppage($jumpurl."&n=".($_PAGE['_GET']['n']+1), lang_replace('message_notice'), lang_replace('start_upload_next',array($cate['catname'])));
			}
			if($_OBJ['html']->category($cate['catid'], $_PAGE['page'])){//生成成功，继续执行
				jieqi_jumppage($jumpurl."&n=".($_PAGE['_GET']['n']+1), lang_replace('message_notice'), lang_replace('category_upload_success',array($cate['catname'])));
			}else{//生成失败，依然继续执行
				jieqi_jumppage($jumpurl."&n=".($_PAGE['_GET']['n']+1), lang_replace('message_notice'), lang_replace('category_upload_failure',array($cate['catname'])));
			}
		}else{//更新完成
		    jieqi_jumppage('?ac=create&op=category', lang_replace('message_notice'), lang_replace('all_upload_success'));
		}
		
	}
    //提交数据
	if(submitcheck("dosubmit")){
	    $catids = array(); //定义栏目ID存储容器
		if(!$_PAGE['_POST']['catids']) $_PAGE['_POST']['catids'][0]='0';//如没有选择生成栏目则默认生成所有栏目
		if($_PAGE['_POST']['catids'][0]=='0'){
		    foreach($_SGLOBAL['category'] as $k=>$v){
			    if(!$_OBJ['category']->isHtml($v['catid'])) continue;
				$catids[] = $v['catid'];
			}
		}else{
		    $catids = $_PAGE['_POST']['catids'];
		}
		jieqi_jumppage("?ac=create&op=category&pagesize={$_PAGE['_POST']['pagesize']}&n=0&catids=".urlencode(implode(',',$catids)), lang_replace('message_notice'), lang_replace('start_upload_category'));
	}
	//获取栏目
	$_OBJ['category']->get_format();
}elseif($op == 'show'){
    $_PAGE['dosubmit'] = $_PAGE['_POST']['dosubmit'] ?$_PAGE['_POST']['dosubmit'] :$_PAGE['_GET']['dosubmit'];
	$_PAGE['type'] = $_PAGE['_POST']['type'] ?$_PAGE['_POST']['type'] :$_PAGE['_GET']['type'];
	if($_PAGE['dosubmit']){
		$_PAGE['number'] = $_PAGE['_POST']['number'] ?$_PAGE['_POST']['number'] :$_PAGE['_GET']['number'];//更新最后几条
		$_PAGE['number'] = intval($_PAGE['number']) ?intval($_PAGE['number']) :100;
		$_PAGE['num'] = intval($_PAGE['_GET']['num']) ?intval($_PAGE['_GET']['num']) :0;
		$_PAGE['error'] = intval($_PAGE['_GET']['error']) ?intval($_PAGE['_GET']['error']) :0;
		$_OBJ['content']->setHandler();//初始化查询对象
		//$_OBJ['content']->criteria->setFields("{$tabletag}*{$fields}");
		$link = '';
		//处理生成栏目
/*		if($_PAGE['_POST']['catids']){
		    $catids = array();
		    foreach($_PAGE['_POST']['catids'] as $k=>$v){
			    if(!$_OBJ['category']->isHtml($v) || $_SGLOBAL['category'][$v]['type']>0) continue;
				$catids[] = $v;
			}
			if(count($catids)<1) jieqi_jumppage('?ac=create&op=show', LANG_NOTICE, lang_replace('not_upload_content'));
		    $_PAGE['catids'] = implode(',',$catids);
		}elseif($_PAGE['_GET']['catids']){
			$_PAGE['catids'] = urldecode($_PAGE['_GET']['catids']);
		}
		if($_PAGE['catids']){
		     $_OBJ['content']->criteria->add(new Criteria('catid', '('.$_PAGE['catids'].')', 'in'));
			 $link.= "&catids=".urlencode($_PAGE['catids']);
		}*/
/*此段代码为对栏目是否静态严格检查，如果启用会影响生成静态的效率*/
		$_PAGE['catids'] = $_PAGE['_POST']['catids'] ?$_PAGE['_POST']['catids'] :$_PAGE['_GET']['catids'];
		if($_PAGE['catids'] && $_PAGE['catids']!=array(0)){
		    if(!is_array($_PAGE['catids'])) $_PAGE['catids'] = explode(',',urldecode($_PAGE['catids']));
			$catids = array();
			foreach($_PAGE['catids'] as $k=>$v){
			    if(!$v) continue;
			    if(!$_OBJ['content']->isHtml(array('catid'=>$v)) || $_SGLOBAL['category'][$v]['type']>0) continue;
				$catids[] = $v;
			}
			$_PAGE['catids'] = $catids;
		}else{
		    foreach($_SGLOBAL['category'] as $k=>$v){
			    if(!$_OBJ['content']->isHtml(array('catid'=>$v['catid'])) || $v['type']>0) continue;
				$_PAGE['catids'][] = $v['catid'];
			}
		}
		if(!$_PAGE['catids']) jieqi_jumppage('?ac=create&op=show', lang_replace('message_notice'), lang_replace('not_upload_content'));
		if(count($_PAGE['catids'])==1){
		    $_OBJ['content']->criteria->add(new Criteria('catid', $_PAGE['catids'][0], '='));
			$_PAGE['catids'] = $_PAGE['catids'][0];
		}else{
			$_PAGE['catids'] = implode(',',$_PAGE['catids']);
			$_OBJ['content']->criteria->add(new Criteria('catid', '('.$_PAGE['catids'].')', 'in'));
		}
		$link.= "&catids=".urlencode($_PAGE['catids']);
		$_OBJ['content']->criteria->add(new Criteria('status', 99));
		switch($_PAGE['type']){
			case 'lastinput':
			      $_OBJ['content']->criteria->setSort('contentid');
				  $_OBJ['content']->criteria->setOrder('DESC');
			      $pagesize = $_PAGE['pagesize'];
				  $_PAGE['pagesize'] = $_PAGE['number'];
				  $link.= "&type=lastinput";
			break;
			case 'date':
			      $_PAGE['fromdate'] = $_PAGE['_POST']['fromdate'] ?$_PAGE['_POST']['fromdate'] :$_PAGE['_GET']['fromdate'];//更新时间
				  $tmpvar=explode('-',$_PAGE['fromdate']);
				  $daystart=mktime(0,0,0,(int)$tmpvar[1],(int)$tmpvar[2],(int)$tmpvar[0]);
				  $_OBJ['content']->criteria->add(new Criteria('updatetime', $daystart, '>='));
				  $_OBJ['content']->criteria->setSort('contentid');
				  $_OBJ['content']->criteria->setOrder('ASC');
			break;
			case 'id':
			      $_PAGE['fromid'] = $_PAGE['_POST']['fromid'] ?$_PAGE['_POST']['fromid'] :$_PAGE['_GET']['fromid'];//开始ID
				  $_PAGE['fromid'] = intval($_PAGE['fromid']) ?intval($_PAGE['fromid']) :0;
				  $_PAGE['toid'] = $_PAGE['_POST']['toid'] ?$_PAGE['_POST']['toid'] :$_PAGE['_GET']['toid'];//结束ID
				  $_PAGE['toid'] = intval($_PAGE['toid']) ?intval($_PAGE['toid']) :0;
				  $_OBJ['content']->criteria->add(new Criteria('contentid BETWEEN '.$_PAGE['fromid'], $_PAGE['toid'], 'AND'));
				  $link.= "&type=id&fromid={$_PAGE['fromid']}&toid={$_PAGE['toid']}";
			break;
			case 'all':
			case 'default':
				  $_OBJ['content']->criteria->setSort('contentid');
				  $_OBJ['content']->criteria->setOrder('ASC');
				  $link.= "&type=all";
			break;
		}
		$_PAGE['rows'] = $_OBJ['content']->lists($_PAGE['pagesize'], $_PAGE['page']);//取得当前要生成的数据列表
		
		if($_PAGE['type']!='lastinput') $_PAGE['totalcount'] = $_OBJ['content']->getVar('totalcount');
		else $_PAGE['totalcount'] = count($_PAGE['rows']);
		if(!$_PAGE['totalcount']) jieqi_jumppage('?ac=create&op=show', lang_replace('message_notice'), lang_replace('not_upload_content'));
		$_PAGE['totalpage'] = ceil($_PAGE['totalcount']/$_PAGE['pagesize']);
		//开始生成
/*		foreach($_PAGE['rows'] as $k=>$v){
		    if(!$_OBJ['content']->isHtml($v)) continue;
			if(!$_OBJ['html']->content($v['contentid'])) $_PAGE['error']++;
		}*/
		
		$start = intval($_PAGE['_GET']['n']) ?intval($_PAGE['_GET']['n']) :0;
		$n = 0;
		for($i = $start;$i<$_PAGE['pagesize'];$i++){
		    if(($i)>=$_PAGE['totalcount']) jieqi_jumppage('?ac=create&op=show',lang_replace('message_notice'), lang_replace('all_upload_success'));
			if(!$_OBJ['content']->isHtml($_PAGE['rows'][$i])){
			   $n++;
			   continue;
			}
			if(!$_OBJ['html']->content($_PAGE['rows'][$i]['contentid'])) $_PAGE['error']++;
		    if($_PAGE['type']=='lastinput'){//如果选择的是按时间更新，则另外处理
				$n++;
				$_PAGE['num']++;
				if(($n+1)>$pagesize || ($n+1)>$_PAGE['totalcount']){
					if($_PAGE['num']>=$_PAGE['totalcount']) jieqi_jumppage('?ac=create&op=show', lang_replace('message_notice'), lang_replace('all_upload_success'));
				    jieqi_jumppage("?ac=create&op=show&pagesize={$pagesize}&n=".($i+1)."&num={$_PAGE['num']}&number={$_PAGE['number']}&error={$_PAGE['error']}&dosubmit=1{$link}", lang_replace('message_notice'), lang_replace('show_upload_success',array($_PAGE['totalcount'],$_PAGE['num'],(sprintf("%.4f",$_PAGE['num']/$_PAGE['totalcount'])*100).'%' ,$_PAGE['error'])));
				}
			}
		}
		
		if($_PAGE['page']>=$_PAGE['totalpage']){
		    jieqi_jumppage('?ac=create&op=show', lang_replace('message_notice'), lang_replace('all_upload_success'));
		}else{
		    $_PAGE['page']++;
			$_PAGE['num']+= count($_PAGE['rows']);
			jieqi_jumppage("?ac=create&op=show&pagesize={$_PAGE['pagesize']}&page={$_PAGE['page']}&num={$_PAGE['num']}&error={$_PAGE['error']}&dosubmit=1{$link}", lang_replace('message_notice'), lang_replace('show_upload_success',array($_PAGE['totalcount'],$_PAGE['num'],(sprintf("%.4f",$_PAGE['num']/$_PAGE['totalcount'])*100).'%' ,$_PAGE['error'])));
		}
	}
	//获取栏目
	$_OBJ['category']->get_format();
}elseif($op == 'index'){
    if(submitcheck("dosubmit")){
	    if(!$_PAGE['_POST']['url']) jieqi_printfail(LANG_ERROR_PARAMETER); 
		if($filesize = $_OBJ['html']->index($_PAGE['_POST']['url'])){
		   jieqi_jumppage('?ac=create&op=index', lang_replace('message_notice'), lang_replace('index_upload_success', array(formatsize($filesize)) ));
		} else jieqi_printfail(lang_replace('index_upload_failure', array('/'.$_PAGE['_POST']['url']))); 
	}
}

//$template = "create_{$op}";
//template('admin/'.$template);
?>
