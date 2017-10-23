<?php
/**
 * 文章日志列表区块
 *
 * 可以根据参数不同显示，最新文章排行榜等
 * 
 * 调用模板：/modules/article/templates/blocks/block_articlelist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_articlelist.php 332 2009-02-23 09:15:08Z juny $
 * @version    2014-5-12 修订版
 */

class Blockstatlog extends JieqiBlock
{
	var $module = 'article';
	var $template = 'block_statlog.html';
	var $cachetime = -1;
	var $exevars=array('field'=>'all', 'listnum'=>15,'articleid'=>0,'uid'=>0, 'asc'=>0);  //执行配置
	
	function Blockstatlog(&$vars){
		global $jieqiArticleuplog;
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				$this->exevars['field']=$varary[0];
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1]) && $varary[1]>0) $this->exevars['listnum']=intval($varary[1]);
			}
			if($arynum>2){
				$varary[2]=trim($varary[2]);
				if(is_numeric($varary[2]) && $varary[2] > 0) $this->exevars['articleid']=$varary[2];
			}
			if($arynum>3){
				$varary[3]=trim($varary[3]);
				if(is_numeric($varary[3]) && $varary[3] > 0) $this->exevars['uid']=$varary[3];
			}
			if($arynum>4){
				//显示顺序（默认 0 表示按从大到小排序），1 表示从小到大排序。
				$varary[4]=trim($varary[4]);
				if(in_array($varary[4], array('0', '1'))) $this->exevars['asc']=$varary[4];
			}
		}
 		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);	
	}

	/**
	 * article 关联 article_stat 以 article为准查询
	 * @see JieqiBlock::setContent()
	 */
	function setContent($isreturn=false){
		global $jieqiTpl;
		return false;

//		$this->db->init ( 'statlog', 'statlogid', 'article' );
//		$this->db->setCriteria();
//		if($this->exevars['articleid']>0){
//			$this->db->criteria->add ( new Criteria ( 'articleid', $this->exevars['articleid'], '=' ));
//		}
//		switch($this->exevars['field']){
//			case 'all':
//				//$this->db->criteria->setSort('addtime');
//				break;
//			default:
//				$this->db->criteria->add ( new Criteria ( 'mid',$this->exevars['field'], '=' ));
//				break;
//		}
//		$this->db->criteria->setSort('addtime');
//		if($this->exevars['asc']==1) $this->db->criteria->setOrder( 'ASC' );
//		else  $this->db->criteria->setOrder( 'DESC' );
//		$logrows = $this->db->lists($this->exevars['listnum']);
//		$jieqiTpl->assign_by_ref('logrows', $logrows);
	}
}

?>