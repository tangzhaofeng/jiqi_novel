<?php
/**
 * 文章列表区块
 *
 * 可以根据参数不同显示，最新文章排行榜等
 * 
 * 调用模板：/modules/news/templates/blocks/block_content.html
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) HULIMING QQ329222795
 * @author     $Author: huliming $
 * @version    $Id: block_content.php 332 2010-06-30 10:55:08Z HULIMING $
 */

class BlockNewsContent extends JieqiBlock
{
	var $module = 'news';
	var $template = 'block_content.html';
	var $cachetime = -1;
	var $exevars=array();  //执行配置
	
	//listnum 显示行数
	//sortid 0表示所有类别，可以是多个类别 '1|2|3'
	//asc  0表示从大往小排，1表示从小往大
	function BlockNewsContent(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$this->blockvars['vars']=trim($this->blockvars['vars']);
		}
		$this->blockvars['cacheid']=md5(serialize($this->blockvars).'|'.$this->blockvars['template']);	
	}


	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		global $_SCONFIG;
		@define('IN_JQNEWS', TRUE);
		if(!isset($_SCONFIG)){
			jieqi_getconfigs('news', 'configs');
			//赋值配置文件
			$_SCONFIG = $jieqiConfigs['news'];
        }
        $jieqiTpl->assign('content', $this->blockvars['vars']);
	}
}

?>