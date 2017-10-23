<?php 
/**
 * 文章排行榜导航区块
 *
 * 文章排行榜导航区块
 * 
 * 调用模板：/modules/article/templates/blocks/block_toplist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_toplist.php 300 2008-12-26 04:36:06Z juny $
 */

class BlockArticleToplist extends JieqiBlock
{
	var $module = 'article';
	var $template = 'block_toplist.html';


	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		jieqi_getconfigs('article', 'configs');
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
		
		$sorts = array('lastupdate','totalvisit', 'monthvisit', 'weekvisit','dayvisit','totalsale', 'monthsale', 'weeksale','totalvipvote', 'monthvipvote', 'weekvipvote',  'totalvote', 'monthvote', 'weekvote', 'dayvote', 'postdate', 'size','monthsize','weeksize','daysize',  'signdate');
		foreach ($sorts as $sort){
			$jieqiTpl->assign('url_'.$sort, jieqi_geturl('article', 'top',  'method=toplist', 'type='.$sort, 'SYS=sortid=0&page=1'));
		}
	}
}
?>