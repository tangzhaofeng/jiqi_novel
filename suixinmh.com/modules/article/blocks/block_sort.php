<?php 
/**
 * 文章分类导航区块
 *
 * 文章分类导航区块
 * 
 * 调用模板：/modules/article/templates/blocks/block_sort.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_sort.php 300 2008-12-26 04:36:06Z juny $
 */

class BlockArticleSort extends JieqiBlock
{
	var $module = 'article';
	var $template = 'block_sort.html';

    function setContent($isreturn=false){
		global $jieqiSort;
		global $jieqiTpl;
		global $jieqiConfigs;

		//jieqi_getconfigs('article', 'sort');
		jieqi_getconfigs('article', 'configs');
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
		
		$articleLib = Application::newLib('article', 'article');
		$data = $articleLib->getSources();
		/*$type = 'main';
		if (JIEQI_MODULE_NAME == 'article'){
			$type = 'main';
		}else{
			$type = JIEQI_MODULE_NAME;
		}*/
		/*print_r($data['channel'][$type]['sort']);
		print_r($type);exit;*/
		$sortrows=array();
		$jieqiTpl->assign('url_articlelist',jieqi_geturl(JIEQI_MODULE_NAME, 'articlelist', 1, 0));
		foreach($data['sortrows'] as $k=>$v){
			$sortrows[$k]=array('sortid'=>$k, 'sortname'=>$v['caption'], 'url_sort'=>geturl($data['module'], 'channel', 'sortid='.$v['sortid'], 'class='.$v['class']), 'sortlayer'=>$v['layer'],'shortcaption'=>$v['shortcaption']);
		}
		
		$jieqiTpl->assign_by_ref('sortrows', $sortrows);
	}	
}

?>