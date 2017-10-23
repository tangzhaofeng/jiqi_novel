<?php
/**
 * 允许模板使用的函数
 *
 * 允许模板使用的函数，函数名必须 jieqi_tpl_ 开头
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $aid: repack.php 230 2008-11-27 08:46:07Z juny $
 */

//需要载入参数设置
if(!isset($jieqiConfigs['article'])) jieqi_getconfigs('article', 'configs');
if(!isset($article_static_url)) $article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
if(!isset($article_dynamic_url)) $article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
//echo $article_static_url;
/**
 * 根据文章ID获得文章封面图片url
 * 
 * @param      int        $aid 文章id
 * @param      string     $type 显示类型 s - 小图， l - 大图
 * @param      int        $flag 图片类型标志 -1 则自动判断
 * @access     public
 * @return     string
 */
function jieqi_url_article_cover($aid, $type='s', $flag=-1){
	global $jieqiConfigs;
	global $article_dynamic_url;
	global $article_static_url;
/*	if($flag < 0){
		global $article;
		if(!is_a($article, 'JieqiArticle')){
			include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
			$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
			$article=$article_handler->get($aid);
			if(is_object($article)) $flag = $article->getVar('imgflag','n');
		}
	}*/
	$flag = intval($flag);
	if($flag <= 0) return $GLOBALS['jieqiModules']['article']['url'].'/images/nophoto.jpg';
	
	$imageinfo = array('stype'=>'', 'ltype'=>'');
	if(($flag & 1)>0) $imageinfo['stype']=$jieqiConfigs['article']['imagetype'];
	if(($flag & 2)>0) $imageinfo['ltype']=$jieqiConfigs['article']['imagetype'];
	$imgtype=$flag >> 2;
	if($imgtype > 0){
		$imgtary=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
		$tmpvar = round($imgtype & 7);
		if(isset($imgtary[$tmpvar])) $imageinfo['stype']=$imgtary[$tmpvar];
		$tmpvar = round($imgtype >> 3);
		if(isset($imgtary[$tmpvar])) $imageinfo['ltype']=$imgtary[$tmpvar];
	}
	switch($type){
		case 'l':
			if(!empty($imageinfo['ltype'])){
				return jieqi_uploadurl($jieqiConfigs['article']['imagedir'], $jieqiConfigs['article']['imageurl'], 'article', $article_static_url).jieqi_getsubdir($aid).'/'.$aid.'/'.$aid.'l'.$imageinfo['ltype'];
			}elseif(!empty($imageinfo['stype'])){
				return jieqi_uploadurl($jieqiConfigs['article']['imagedir'], $jieqiConfigs['article']['imageurl'], 'article', $article_static_url).jieqi_getsubdir($aid).'/'.$aid.'/'.$aid.'s'.$imageinfo['stype'];
			}else{
				return $GLOBALS['jieqiModules']['article']['url'].'/images/nophoto.jpg';
			}
			break;
		case 's':
		default:
			if(!empty($imageinfo['stype'])){
				return jieqi_uploadurl($jieqiConfigs['article']['imagedir'], $jieqiConfigs['article']['imageurl'], 'article', $article_static_url).jieqi_getsubdir($aid).'/'.$aid.'/'.$aid.'s'.$imageinfo['stype'];
			}else{
				return $GLOBALS['jieqiModules']['article']['url'].'/images/nophoto.jpg';
			}
			break;
	}
}

?>