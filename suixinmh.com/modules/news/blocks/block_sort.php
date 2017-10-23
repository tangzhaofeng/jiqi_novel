<?php
/**
 * 分类区块
 *
 * 可以根据参数不同显示分类
 * 
 * 调用模板：/modules/news/templates/blocks/block_sort.html
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) HULIMING QQ329222795
 * @author     $Author: huliming $
 * @version    $Id: block_content.php 332 2010-06-30 10:55:08Z HULIMING $
 */

class BlockNewsSort extends JieqiBlock
{
	var $module = 'news';
	var $template = 'block_sort.html';
	var $cachetime = -1;
	var $exevars=array('parentid'=>'0','target'=>'_blank');  //执行配置
	
	//parentid 上级目录ID
	function BlockNewsSort(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);

			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(is_numeric($varary[0])) $this->exevars['parentid']=$varary[0];
			}
			if($arynum>1){
				$varary[1]=trim($varary[1]);
				$this->exevars['target']=$varary[1];
			}
		}
		$this->blockvars['cacheid']=md5(serialize($this->blockvars).'|'.$this->blockvars['template']);	
	}


	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		global $jieqiConfigs;
		global $_SCONFIG, $_SGLOBAL,$_OBJ;
		@define('IN_JQNEWS', TRUE);
		//载入相关处理函数
		include_once($GLOBALS['jieqiModules']['news']['path'].'/include/loadclass.php');
		if(!isset($_SCONFIG)){
			jieqi_getconfigs('news', 'configs');
			//赋值配置文件
			$_SCONFIG = $jieqiConfigs['news'];
        }
		//初始化栏目操作对像和加载栏目数据列表
		$_OBJ['category'] = new Category();
		//格式化栏目，方便输出
		$_OBJ['category']->get_format($this->exevars['parentid']);
		$jieqiTpl->assign('_SGLOBAL', $_SGLOBAL);
        $jieqiTpl->assign('target', $this->exevars['target']);
		
	}
}

?>