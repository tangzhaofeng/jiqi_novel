<?php
/*
    *静态处理类
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: html.class.php 12398 2010-06-13 09:55:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

class Html extends JieqiObject{
	
	function Html(){
	    global $jieqiTpl;
		include_once(_ROOT_.'/header.php');
		$jieqiTpl->assign(array('jieqi_themeurl' => JIEQI_URL.'/themes/'.JIEQI_THEME_NAME.'/'));
	}
	
	//处理栏目HTML
	function category($catid, $page = 1){
	    global $_SGLOBAL,$_SCONFIG,$_OBJ,$_PAGE;
		//初始化栏目操作对像和加载栏目数据列表
		if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		if($_OBJ['category']->islist($catid)){
			//获取分类文章列表
			$content = & new Content();
			$content->setHandler();
			$content->criteria->add(new Criteria('catid', $catid));
			$content->criteria->add(new Criteria('status', 99));
			$content->criteria->setSort('contentid');
			$content->criteria->setOrder('DESC');
			$pagesize = $_OBJ['category']->getPagenum($catid);;
			$_PAGE['articlerows'] = $content->lists($pagesize, $page);
			$totalcount = $content->getVar('totalcount');
			$totalpage = ceil($totalcount/$pagesize);
			$_SCONFIG['maxpage'] = $_SCONFIG['maxpage'] ?$_SCONFIG['maxpage'] :100;
			if($_OBJ['category']->isHtml($catid) && $totalpage>$_SCONFIG['maxpage']) $totalpage = $_SCONFIG['maxpage'];
			if($totalpage>1){
			    $n = 0;//位置标识
				for($i=$page; $i<=$totalpage; $i++){
				    $n++;
				    $this->createCate($catid, $i);
					if($i==$totalpage){
					    $jumpurl = "?ac=create&op=category&pagesize={$_PAGE['pagesize']}&n=".($_PAGE['_GET']['n']+1)."&catids=".urlencode($_PAGE['_GET']['catids']);
						$temparr = array($_SGLOBAL['category'][$catid]['catname'], $totalpage);
					    jieqi_jumppage($jumpurl."&n=".($_PAGE['_GET']['n']+1), lang_replace('message_notice'), lang_replace('category_page_upload_success',$temparr));
					}elseif($n>=$_PAGE['pagesize']){
					    $next = $i+1;
					    $jumpurl = "?ac=create&op=category&pagesize={$_PAGE['pagesize']}&n={$_PAGE['_GET']['n']}&page={$next}&catids=".urlencode($_PAGE['_GET']['catids']);
						$step = '1 - '.$i;
						$temparr = array($_SGLOBAL['category'][$catid]['catname'], $step);
					    jieqi_jumppage($jumpurl, lang_replace('message_notice'), lang_replace('category_page_upload_success', $temparr));
					}
				}
				return true;
			}
		}
		return $this->createCate($catid, $page);
	}
	
	//生成一个栏目
	function createCate($catid, $page = 1){
	    global $_SGLOBAL,$_SCONFIG,$_OBJ;
		//初始化栏目操作对像和加载栏目数据列表
		if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		//判断是否需要生成文件
		if(!$catid || ($showtyle = $_OBJ['category']->showType($catid))<2) return false;
		$htmldir = $_OBJ['category']->getDir($catid);
		//if(!is_dir($htmldir)){
		   //if(!jieqi_createdir($htmldir, 0777, true)) return false;
		//}
		$filename = $htmldir.$_OBJ['category']->getUrlrule($catid, $page);
		$dir = dirname($filename).'/';
		if(!is_dir($dir)) if(!jieqi_createdir($dir, 0777, true)) return false;
		if($showtyle>2){
		    if(!($pagestr = $_OBJ['category']->fetch($catid,$page))) return false;
			return swritefile($filename,$pagestr);//生成静态
		}else return $this->createFake($catid, $page, $filename, 'list');
	}
	 
	//处理文章内容HTML
	function content($id){
	    global $_SGLOBAL,$_SCONFIG,$_OBJ,$_PAGE;
		//初始化栏目操作对像和加载栏目数据列表
		if(!is_object($_OBJ['content'])) $_OBJ['content'] = new Content();		
		if(!$id || !($pagearr = $_OBJ['content']->fetch($id,1,true))) return false;
		//判断是否需要生成静态
		if(($showtyle = $_OBJ['content']->showType($_PAGE['data']))<2) return false;
		
		$htmldir = $_OBJ['content']->getDir($_PAGE['data']);
		$statu = false;
		$filename = '';
		foreach($pagearr as $k=>$v){
		    $filename = $htmldir.$_OBJ['content']->getUrlrule($_PAGE['data'], $k);
		    $dir = dirname($filename).'/';
			if(!is_dir($dir)) if(!jieqi_createdir($dir, 0777, true)) return false;
			if($showtyle>2) $statu = swritefile($filename,$pagearr[$k]);//生成静态
			else $statu = $this->createFake($id, $k, $filename, 'show');
		}
		return $statu;
	}
	
	//处理首页的HTML
	function index($url){
	    global $_SGLOBAL, $_SCONFIG, $_SN, $_TPL, $jieqiTset, $jieqiTpl, $_PAGE, $_OBJ;
		$htmldir = $_SGLOBAL['rootpath']."/{$url}";
		if(!is_dir($htmldir)) if(!jieqi_createdir(dirname($htmldir), 0777, true)) return false;
		template('index');
		$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template']));
		$pagearr = $jieqiTpl->fetch($jieqiTset['jieqi_page_template']);
		jieqi_freeresource();
		if(swritefile($htmldir, $pagearr)) return filesize($htmldir);
		else return false;
	}
	
	//生成阅伪静态
	function createFake($id, $page, $filename, $type){
	    global $_SGLOBAL;
	    if(!$id || !$filename) return false;
		$filename = str_replace('//', '/', $filename);
		$tmpcot = str_repeat('../', substr_count(str_replace($_SGLOBAL['rootpath'],'',$filename), '/')-1);
		$content='<?php
//加载页面预处理文件
include_once(\''.$tmpcot.'global.php\');
include_once(\''.$tmpcot.'modules/news/common.php\');
include_once(\''.$tmpcot.'modules/news/include/loadclass.php\');
$_PAGE[\'_GET\'][\'id\'] = '.$id.';
$_PAGE[\'_GET\'][\'page\'] = '.$page.';
//处理模板
include_once($_SGLOBAL[\'news\'][\'path\'].\'/source/'.$type.'.inc.php\');
include_once($_SGLOBAL[\'rootpath\'].\'/footer.php\');
?>';
		return swritefile($filename, $content);
	}
}
?>