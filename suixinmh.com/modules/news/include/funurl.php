<?php
/**
 * 允许模板使用的函数
 *
 * 允许模板使用的函数，函数名必须 jieqi_tpl_ 开头
 * 
 * 调用模板：无
 * 
 * @category   Cms
 * @package    News
 * @copyright  Copyright (c) huliming Cms News Network Technology Co.,Ltd
 * @author     $Author: huliming $
 * @version    $mid: funurl.php 230 2010-06-09 13:02:07Z huliming $
 */
 /**
 * 根据栏目ID获得栏目页面url
 * 
 * @param      int        $catid 栏目id
 * @param      string     $page 分页
 * @access     public
 * @return     string
 */
if(!defined('IN_JQNEWS')){
	include_once($jieqiModules['news']['path'].'/common.php');
	include_once($jieqiModules['news']['path'].'/include/loadclass.php');
}
function jieqi_url_news_lists($catid,$page = 1, $evalpage = true){
    global $_SGLOBAL,$_SCONFIG,$_OBJ,$jieqiModules;
    if(!$catid) return false;
	//检查信息是否完整
	if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
	$cate = $_OBJ['category']->get($catid, true);
	if(!is_array($cate)) return false;
	
	//需要载入参数设置
	//if(!isset($_SCONFIG['static_url'])) $_SCONFIG['static_url'] = (empty($_SCONFIG['staticurl'])) ? JIEQI_URL : $_SCONFIG['staticurl'];
	$static_url = $_OBJ['category']->getCateurl($catid);
    
	if($cate['type']>1){//外链
	    if(substr_count($cate['url'], 'http://')){
		    return $cate['url'];
		}elseif(substr($cate['url'],0,1)==='/'){
		    return $static_url.$cate['url'];
		}else return $static_url.'/'.$cate['url'];
	}

	if($ishtml = $_OBJ['category']->showType($catid)){
	    $urlrule = $_OBJ['category']->getUrlrule($catid, $page, $evalpage);
		if(!substr_count($cate['url'], 'http://')){
			$returl = $static_url.'/'.$cate['url'];
			if($ishtml==1){
				$dirs = explode('/', $cate['parentdir'] ?$cate['parentdir'] :$cate['catdir']);
				$dir = $dirs[0];
				$returl =  $static_url.'/'.$dir.'/';
			}//echo $ishtml.$cate['url'].'<br>';
		}else $returl = $cate['url'];
		if($evalpage) return str_replace(array('index.html','index.htm'), '', $returl.$urlrule);
		else return $returl.$urlrule;
		//$urlrule = str_replace(array('index.html','index.htm'),'',$urlrule);
	}else{
	    return $jieqiModules['news']['url']."/?ac=list&catid={$catid}".(($page>1) ?"&page={$page}" :'');
	}
}

function jieqi_url_news_top($param, $order, $page = 1, $evalpage = true){
    global $_SGLOBAL,$_SCONFIG,$_OBJ;
	if(!$evalpage) $page = '<{$page}>';
	if($param['tag']){
	    $param['catid'] = $param['catid']=='' ? 0 : $param['catid'];
		if($param['url']){
			$param['tag'] = urlencode($param['tag']);
			return $param['url']."/{$param['catid']}_{$page}/{$param['tag']}.html";
		}else{
		    $param['tag'] = urlencode($param['tag']);
			return $param['url']."/tag_{$param['catid']}_{$page}/{$param['tag']}.html";
		}
	}elseif($param['catid']){
	    return _LOCAL_."/top/{$param['catid']}/{$order}/{$page}.html";
	}elseif($param['m']){
	    return _LOCAL_."/top/{$param['m']}/{$order}/{$page}.html";
	}
}

/*function jieqi_url_news_tag($param, $page = 1, $evalpage = true){
    global $_SGLOBAL,$_SCONFIG,$_OBJ;
	if(!$evalpage) $page = '<{$page}>';
	$param['catid'] = $param['catid']=='' ? 0 : $param['catid'];
	if($param['url']){
	    $param['tag'] = urlencode($param['tag']);
	    return $param['url']."/{$param['catid']}_{$page}/{$param['tag']}.html";
	}else{
	    return $param['url']."/tag_{$param['catid']}_{$page}/{$param['tag']}.html";
	}
}*/

 /**
 * 根据文章ID获得文章页面url
 * 
 * @param      int        $contentid 文章id
 * @param      int        $page 分页
 * @access     public
 * @return     string
 */
 
 function jieqi_url_news_show($data, $page = 1, $evalpage = true){
     global $_SGLOBAL,$_SCONFIG,$_OBJ,$jieqiModules;
	 //extract($data);
	 if(!is_object($_OBJ['content'])) $_OBJ['content'] = new Content();
	 if(!is_array($data) && $data>0) $data = $_OBJ['content']->get($data);
     if(!$data['contentid'] || !$data['catid']) return false;
	 if($data['url']) return $data['url'];//定义转向链接
	 if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
	 $cate = $_OBJ['category']->get($data['catid'], true);
	 if(!is_array($cate)) return false;
     
	 //需要载入参数设置
	 //if(!isset($_SCONFIG['static_url'])) $_SCONFIG['static_url'] = (empty($_SCONFIG['staticurl'])) ? JIEQI_URL : $_SCONFIG['staticurl'];
	 $static_url = $_OBJ['category']->getPosturl($data['catid']);
	 
	 if($_OBJ['content']->showType($data)>0){
	     $urlrule = $_OBJ['content']->getUrlrule($data, $page, $evalpage);
		 if(!substr_count($cate['url'], 'http://')){//http://domestic.news.news.com/
		     $dirs = explode('/', $cate['url']);
			 $dir = $dirs[0];
		     $returl =  $static_url.'/'.$dir.'/';
			 ///$returl =  $static_url.'/'.$cate['url'];
		 }else{
		     if($cate['arrparentid']){
				 $arrparentids = explode(',', $cate['arrparentid']);
				 $returl = $_SGLOBAL['category'][$arrparentids[1]]['url'];
				 if(!substr_count($returl, 'http://')) $returl =  $static_url.'/'.$returl;
			 } else $returl = $cate['url'];
		 }
		 //if($returl.$urlrule=='http://www.2100book.com/sucai/'){
		     //echo $returl.$urlrule.'<br>';
		 //}
/*		 $dirs = explode('/', $cate['parentdir'] ?$cate['parentdir'] :$cate['catdir']);
		 $dir = $dirs[0];
		 $returl =  $static_url.'/'.($dir ? $dir.'/' :'');*/
		 if($evalpage) return str_replace('index.html', '', $returl.$urlrule);
		 else return $returl.$urlrule;
	 }else{
		 return $jieqiModules['news']['url']."/?ac=show&id={$data['contentid']}".(($page>1) ?"&page={$page}" :'');
		 /*$urlrule = $_OBJ['content']->getUrlrule($data, $page);
		 $dirs = explode('/', $cate['parentdir'] ?$cate['parentdir'] :$cate['catdir']);
		 $dir = $dirs[0];
		 $returl =  $static_url.'/'.($dir ? $dir.'/' :'');
		 return $returl.$urlrule.$_SCONFIG['htmlfile'];*/
	 }
 }
 
 /**
 * 根据文章ID获得文章评论url
 * 
 * @param      int        $contentid 文章id
 * @param      int        $page 分页
 * @access     public
 * @return     string
 */
 
 function jieqi_url_news_comment($contentid, $page = 1){
     global $_SGLOBAL,$_SCONFIG,$_OBJ,$jieqiModules;
	 //return $jieqiModules['news']['url']."/?ac=comment&id={$contentid}".(($page>1) ?"&page={$page}" :'');
	 return $_SGLOBAL['localurl']."/comments/{$contentid}/{$page}";
 }
 
 /**
 * 处理模板标签
 * 
 * @param      string        $tag 标签标识
 * @access     public
 * @return     string
 */
 function jieqi_url_news_tags($tag, $return = 'html'){
     global $_SGLOBAL,$_SCONFIG,$_OBJ,$jieqiModules;
	 if(!is_array($tag)) $id = $tag;
	 else $id = $tag['id'];
	 //print_r($tag);
	 if(!is_object($_OBJ['position'])) $_OBJ['position'] = new Position();
	 if($data = $_OBJ['position']->get($id,true)){
	     if($param = exechars("<{!!!!}>", urldecode($tag['name']), true)){
		     if($param[0]) $data['setting']['vars'] = $param[0];
			 //echo $param[0];
			 if($param[1]) $data['setting']['template'] = $param[1];
		 }//print_r($data['setting']);exit;
	     $data['setting']['title'] = $data['name'];
		 switch($data['type']){
			 case '0':
				 $data['setting']['vars'] = $data['data'];
				 $data['setting']['side'] = 1;
				 $data['setting']['bid'] = $tag['id'];
				 $data['setting']['module'] = 'news';
				 $data['setting']['filename'] = 'block_commend';
				 $data['setting']['classname'] = 'BlockNewsCommend';
				 $data['setting']['contenttype'] = 1;
				 $data['setting']['custom'] = 0;
				 $data['setting']['publish'] = 3;
				 $data['setting']['hasvars'] = 2;
			 break;
			 case '2':
				 $data['setting']['vars'] = $data['setting']['content'];
				 $data['setting']['side'] = 1;
				 $data['setting']['bid'] = $id;
				 $data['setting']['module'] = 'news';
				 $data['setting']['filename'] = 'block_content';
				 $data['setting']['classname'] = 'BlockNewsContent';
				 $data['setting']['contenttype'] = 1;
				 $data['setting']['custom'] = 0;
				 $data['setting']['publish'] = 3;
				 $data['setting']['hasvars'] = 2;
			 break;
			 default :
				 $data['setting']['side'] = 1;
				 $data['setting']['publish'] = 3;
			 break;
		 }
		 if($return == 'html'){
		     include_once(_ROOT_.'/header.php');
		     return jieqi_get_block($data['setting'],1);
		 } else {
		     $temp = '';
		     foreach($data['setting'] as $k=>$v){
			      if($temp) $temp.='&'.$k.'='.urlencode($v);
				  else $temp.=$k.'='.urlencode($v);
			 }
			 return shtmlspecialchars('<script language="javascript" type="text/javascript" src="'.$_SGLOBAL['news']['url'].'/?ac=blockshow&id='.$id.'"></script>');
		 }
	 }else{
	     return "Data is error[".@urldecode($tag['name'])."]!";
	 }
 }
?>