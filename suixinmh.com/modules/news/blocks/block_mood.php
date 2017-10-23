<?php
/**
 * 心情区块
 *
 * 可以根据参数不同显示心情
 * 
 * 调用模板：/modules/news/templates/blocks/block_mood.html
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) HULIMING QQ329222795
 * @author     $Author: huliming $
 * @version    $Id: block_mood.php 332 2010-09-10 10:55:08Z HULIMING $
 */

class BlockNewsMood extends JieqiBlock
{
	var $module = 'news';
	var $template = 'block_mood.html';
	var $cachetime = -1;
	var $exevars=array('moodid'=>'', 'contentid'=>'0');  //执行配置
	
	function BlockNewsMood(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);

			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(is_numeric($varary[0])) $this->exevars['moodid']=$varary[0];
			}
			
			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1]) && $varary[1]>0) $this->exevars['contentid']=intval($varary[1]);
			}
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);	
	}


	function setContent($isreturn=false){
		global $_SGLOBAL;
		global $jieqiTpl;
		global $jieqiConfigs;
		global $_SCONFIG;
		@define('IN_JQNEWS', TRUE);
		//载入相关处理文件
		include_once($GLOBALS['jieqiModules']['news']['path'].'/include/loadclass.php');
		include_once($GLOBALS['jieqiModules']['news']['path'].'/class/content.php');
		if(!isset($_SCONFIG)){
			jieqi_getconfigs('news', 'configs');
			//赋值配置文件
			$_SCONFIG = $jieqiConfigs['news'];
        }
		//加载content类
		$content = & new Content();
		$data = $content->getMood($this->exevars['moodid'], $this->exevars['contentid']);
        //加载配置参数
		$mood = new GlobalData('mood', 'moodid');
		$fields = $mood->get($this->exevars['moodid']);
		
		$i = $j = 0;//echo $this->blockvars['cacheid'];//$this->blockvars['tlpfile'];
		$items = array();
		foreach($fields as $k=>$v){
		    $j++;
			if($j<5 || !$v) continue;
			$i++;
		    $temp = explode('|', $v);
			$value = $data['n'.$i] ? $data['n'.$i] :0;
			if(!$data['total']) $height = 0;
			else {
			    $height = ceil($value/$data['total'] * 100);
			}
			$items[$k] = array('name'=>$temp[0], 'img'=>$temp[1], 'value'=>$value, 'height'=>$height);
		}
		$jieqiTpl->assign('items', $items);
		$data['name'] = $fields['name'];
		$data['number'] = $fields['number'];
		$data['isavg'] = $fields['isavg'];
		$data['moodid'] = $this->exevars['moodid'];
		$data['contentid'] = $this->exevars['contentid'];
		$jieqiTpl->assign('mood', $data);
		include(JIEQI_ROOT_PATH.'/include/funstat.php');
		if(!jieqi_visit_valid($this->exevars['contentid'], 'article_mood', false)) $jieqiTpl->assign('ispost', 0);
		else  $jieqiTpl->assign('ispost', 1);
		
	}
}

?>