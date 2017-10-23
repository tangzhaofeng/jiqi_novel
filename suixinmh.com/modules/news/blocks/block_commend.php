<?php
/**
 * 文章列表区块
 *
 * 可以根据参数不同显示，最新文章排行榜等
 * 
 * 调用模板：/modules/news/templates/blocks/block_commend.html
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) HULIMING QQ329222795
 * @author     $Author: huliming $
 * @version    $Id: block_commend.php 332 2010-06-30 10:55:08Z HULIMING $
 */

class BlockNewsCommend extends JieqiBlock
{
	var $module = 'news';
	var $template = 'block_commend.html';
	
	var $exevars=array();  //执行配置
	
	//listnum 显示行数
	//sortid 0表示所有类别，可以是多个类别 '1|2|3'
	//asc  0表示从大往小排，1表示从小往大
	function BlockNewsCommend(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$this->blockvars['vars']=trim($this->blockvars['vars']);
			$temparr = array();
			if(substr_count($this->blockvars['vars'],'|')) $ids = explode('|',$this->blockvars['vars']);
			else $ids = explode(',',$this->blockvars['vars']);
			foreach($ids as $k=>$v){
				if($v && is_numeric($v)) $temparr[] = $v;
			}
			$this->exevars['ids'] = $temparr;
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);	
	}


	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		global $_SCONFIG;
		@define('IN_JQNEWS', TRUE);
		if(!$this->exevars['ids']) return false;
		//载入相关处理函数
		include_once($GLOBALS['jieqiModules']['news']['path'].'/include/loadclass.php');
		include_once($GLOBALS['jieqiModules']['news']['path'].'/class/content.php');
		if(!isset($_SCONFIG)){
			jieqi_getconfigs('news', 'configs');
			//赋值配置文件
			$_SCONFIG = $jieqiConfigs['news'];
        }
		//加载content类
		$content = & new Content();
		$content->setHandler();
		$content->criteria->add(new Criteria('status', 99));
		$content->criteria->add(new Criteria('contentid', '('.implode(',', $this->exevars['ids']).')', 'in'));
		$_PAGE['rows'] = $content->lists();
        $jieqiTpl->assign('articlerows', $_PAGE['rows']);
	}
}

?>