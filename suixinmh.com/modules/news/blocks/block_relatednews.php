<?php
/**
 * 相关文章区块
 *
 * 可以根据参数不同相关文章
 * 
 * 调用模板：/modules/news/templates/blocks/block_relatednews.html
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) HULIMING QQ329222795
 * @author     $Author: huliming $
 * @version    $Id: block_relatednews.php 332 2010-09-09 10:55:08Z HULIMING $
 */

class BlockNewsRelatednews extends JieqiBlock
{
	var $module = 'news';
	var $template = 'block_relatednews.html';
	var $exevars=array('keywords'=>'', 'catid'=>'0', 'listnum'=>5, 'condition'=>'OR', 'field'=>'title','title'=>'');  //执行配置
	
	//contentid 当前指定文章ID的上页页
	//catid 在指定栏目内提取上下页
	function BlockNewsRelatednews(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);

			if($arynum>0){
				if($varary[0]){
					$keywords = explode(' ',$varary[0]);
					foreach($keywords as $k=>$v){
						 if(trim($v)) $temparr[] = trim($v);
					}
					$this->exevars['keywords'] = array_unique($temparr);
				}
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				$temparr = array();
				if($varary[1]){
					if(substr_count($varary[1],'|')) $catids = explode('|',$varary[1]);
					else $catids = explode(',',$varary[1]);
					foreach($catids as $k=>$v){
					    if($v && is_numeric($v)) $temparr[] = $v;
					}
					$this->exevars['catid'] = array_unique($temparr);
				}
			}
			
			if($arynum>2){
				$varary[2]=trim($varary[2]);
				if(is_numeric($varary[2]) && $varary[2]>0) $this->exevars['listnum']=intval($varary[2]);
			}
			
			if($arynum>3){
				$varary[3]=trim($varary[3]);
				$this->exevars['condition']=$varary[3];
			}
			
			if($arynum>4){
				$varary[4]=trim($varary[4]);
				$this->exevars['field']=$varary[4];
			}
			if($arynum>5){
				$varary[5]=trim($varary[5]);
				$this->exevars['title']=$varary[5];
			}
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);	
	}


	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		global $_SCONFIG;
		@define('IN_JQNEWS', TRUE);
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
			if($this->exevars['catid']){
				if(count($this->exevars['catid'])>1){
					$catids = implode(',',$this->exevars['catid']);
					$content->criteria->add(new Criteria('catid', "($catids)", 'in'));
				}elseif(count($this->exevars['catid'])==1){
				    if($SGLOBAL['cate']['child']){
					    $catids = $SGLOBAL['cate']['arrchildid'];
					    $content->criteria->add(new Criteria('catid', "($catids)", 'in'));
						
					} else $content->criteria->add(new Criteria('catid', $this->exevars['catid'][0]));
				}else{
					//$content->criteria->add(new Criteria('catid', '', '<>'));
				}
		    }
		if(!$this->exevars['keywords']){
			$word = dictwrod($this->exevars['title']);
			$this->exevars['keywords'] = $word;
		}
		
		if($this->exevars['keywords']){
			if(count($this->exevars['keywords'])>1){
				foreach($this->exevars['keywords'] as $k=>$v){
				   $left = !$k ? '(' : '';
				   $content->criteria->add(new Criteria($left.$this->exevars['field'], '%'.$v.'%', 'like'), !$k ? 'AND' :$this->exevars['condition']);
				}
			}elseif(count($this->exevars['keywords'])==1){
				$content->criteria->add(new Criteria($this->exevars['field'], '%'.$this->exevars['keywords'][0].'%', 'like'));
			}
		}
		$content->criteria->add(new Criteria('status', 99), count($this->exevars['keywords'])>1 ? ') AND' : 'AND');
		$content->criteria->setSort('contentid');
		$content->criteria->setOrder('DESC');
		$_PAGE['rows'] = $content->lists($this->exevars['listnum']);
        $jieqiTpl->assign('articlerows', $_PAGE['rows']);
		if($this->exevars['keywords']) $jieqiTpl->assign('url_more', jieqi_geturl('news', 'top', array('tag'=>implode(' ',$this->exevars['keywords']),'catid'=>$this->exevars['catid']), '', 1));
	}
}

?>