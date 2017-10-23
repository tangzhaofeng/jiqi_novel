<?php
/**
 * 文章上下页区块
 *
 * 可以根据参数不同显示文章上下页
 * 
 * 调用模板：/modules/news/templates/blocks/block_afterpage.html
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) HULIMING QQ329222795
 * @author     $Author: huliming $
 * @version    $Id: block_afterpage.php 332 2010-09-06 10:55:08Z HULIMING $
 */

class BlockNewsAfterpage extends JieqiBlock
{
	var $module = 'news';
	var $template = 'block_afterpage.html';
	var $exevars=array('contentid'=>0, 'catid'=>'0');  //执行配置
	
	//contentid 当前指定文章ID的上页页
	//catid 在指定栏目内提取上下页
	function BlockNewsAfterpage(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);

			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(is_numeric($varary[0]) && $varary[0]>0) $this->exevars['contentid']=intval($varary[0]);
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1])) $this->exevars['catid']=$varary[1];
			}
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);	
	}


	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		global $_SCONFIG, $_SGLOBAL;
		@define('IN_JQNEWS', TRUE);
		//载入相关处理函数
		include_once($GLOBALS['jieqiModules']['news']['path'].'/include/loadclass.php');
		//加载content类
		$content = & new Content();
		if($this->exevars['catid']){
		    //初始化栏目操作对像和加载栏目数据列表
			if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
			//获取栏目内容
			if(!($_SGLOBAL['cate'] = $_OBJ['category']->get($this->exevars['catid'], true))) return false;
			//加载模型
			if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
			//构造查询表
			$tables = array();
			$tables[$content->tablepre.'c_'.$_SGLOBAL['model'][$_SGLOBAL['cate']['modelid']]['tablename']] = '*';  //文章附加表表名
			$tag = $tables ? 'tables' : NULL;
			$tabletag = ($tag ?'news_content.' :'');
			$content->setHandler($tag);
			if($tables){
				$tablestr = $fields = '';
				foreach($tables as $table=>$field){
					$tablestr.= " LEFT JOIN ".jieqi_dbprefix($table)." AS {$table} ON {$tabletag}contentid={$table}.contentid ";
					$fields.= ",{$table}.{$field}";
				}
				$content->criteria->setFields("{$tabletag}*{$fields}");
				$content->criteria->setTables(jieqi_dbprefix('news_content')." AS news_content {$tablestr}");
			}
			
		} else return false;
		
		//取得上一个ID号
		$where = $this->exevars['catid'] ? 'catid='.$this->exevars['catid'].' and ' : '';
		$diff = "( SELECT contentid FROM ".jieqi_dbprefix("news_content")." WHERE {$where} contentid < ".$this->exevars['contentid']."  ORDER BY contentid DESC LIMIT 0 , 1 )";
		//if(dbversion() < '4.1') {
			if($temp = selectsql($diff)){
			    $diff = $temp[0]['contentid'];
			}else $diff = 0;
		//}
		
		if($this->exevars['catid']) $content->criteria->add(new Criteria('catid', $this->exevars['catid']));
		$content->criteria->add(new Criteria('status', 99));
		$content->criteria->add(new Criteria("{$tabletag}contentid", $diff, '>='));
		$content->criteria->add(new Criteria("{$tabletag}contentid", $this->exevars['contentid'], '<>'));
		//$where = $this->exevars['catid'] ? 'catid='.$this->exevars['catid'].' and ' : '';
		//$sql = 'SELECT contentid,catid,title FROM '.jieqi_dbprefix("news_content")." WHERE {$where} status=99 and contentid >= {$diff} AND contentid <> ".$this->exevars['contentid']." LIMIT 0,2";
		//$content->criteria->setSort("{$tabletag}contentid");
		//$content->criteria->setOrder('ASC');
		$_PAGE['rows'] = $content->lists(2);
		if($_PAGE['rows']){//加载表单数据对象类
			$elements = new FormElements($_SGLOBAL['cate']['modelid'], $this->exevars['catid']);
			foreach($_PAGE['rows'] as $k=>$v){
			   $_PAGE['rows'][$k] = $elements->show($v);
			}
		}
		$jieqiTpl->assign('articlerows', $_PAGE['rows']);
		//if($c = selectsql($sql)){
		//     $jieqiTpl->assign('articlerows', $c);
		//}else return false;
	}
}

?>