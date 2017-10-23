<?php
/**
 * 文章列表区块
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

class BlockArticleArticlelist extends JieqiBlock
{
	var $module = 'article';
	var $template = 'block_articlelist.html';
	
	var $exevars=array('field'=>'totalvisit', 'listnum'=>15, 'sortid'=>'0', 'isauthor'=>0, 'isfull'=>0, 'asc'=>0, 'articletype'=>0, 'firstflag'=>'','siteid'=>JIEQI_SITE_ID);  //执行配置
	
	//listnum 显示行数
	//sortid 0表示所有类别，可以是多个类别 '1|2|3'
	//isauthor 0 表示所有, 1表示原创，2表示转载
	//isfull 0 表示所有, 1表示全本，2连载
	//asc  0表示从大往小排，1表示从小往大
	//permission 授权等级 空表示不检查 0-3 授权等级,可以多个等级 1|2|3
	//firstflag 是否首发 空表示不检查 0他站首发 1本站首发
	function BlockArticleArticlelist(&$vars){
		global $jieqiArticleuplog;
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if($varary[0]=='mouthvisit') $varary[0]='monthvisit';
				elseif($varary[0]=='mouthvote') $varary[0]='monthvote';
				if(!in_array($varary[0], array('lastupdate','totalvisit', 'monthvisit', 'weekvisit','dayvisit','totalsale', 'monthsale', 'weeksale','daysale','totalvipvote', 'monthvipvote', 'weekvipvote',  'totalvote', 'monthvote', 'weekvote', 'dayvote', 'postdate', 'size','monthsize','weeksize','daysize', 'signdate', 'vipdate','totalgoodnum', 'monthgoodnum', 'weekgoodnum'))) $this->exevars['field']='lastupdate';
				else $this->exevars['field']=$varary[0];
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1]) && $varary[1]>0) $this->exevars['listnum']=intval($varary[1]);
			}

			if($arynum>2){
				$varary[2]=trim($varary[2]);
				$tmpvar=str_replace('|', '', $varary[2]);
				if(is_numeric($tmpvar)) $this->exevars['sortid']=$varary[2];
				elseif($this->getRequest($tmpvar) && is_numeric($this->getRequest($tmpvar))){
					$this->exevars['sortid']=$this->getRequest($tmpvar);
				} 
			}
			if($arynum>3){
				//是否原创（默认 0 表示不判断），1 表示只显示原创作品，2 表示转载作品
				$varary[3]=trim($varary[3]);
				if(in_array($varary[3], array('0', '1', '2')) || $varary[3] > 2) $this->exevars['isauthor']=$varary[3];
			}
// 			if($arynum>3){
// 				//是否原创（默认 0 表示不判断），1 表示只显示articletype=1的作品，
// 				$varary[3]=trim($varary[3]);
// 				if(in_array($varary[3], array('0', '1', '2')) || $varary[3] > 1) $this->exevars['articletype']=$varary[3];
// 			}
			if($arynum>4){
				//是否全本（默认 0 表示不判断），1 表示只显示全本作品，2 表示连载作品
				$varary[4]=trim($varary[4]);
				if(in_array($varary[4], array('0', '1', '2'))) $this->exevars['isfull']=$varary[4];
			}
			if($arynum>5){
				//显示顺序（默认 0 表示按从大到小排序），1 表示从小到大排序。
				$varary[5]=trim($varary[5]);
				if(in_array($varary[5], array('0', '1'))) $this->exevars['asc']=$varary[5];
			}
			//articletype
			if($arynum>6){
				//是否vip文章（默认 0 表示不判断），1 表示vip文章，2 表示非vip文章
				$varary[6]=trim($varary[6]);
				if(in_array($varary[6], array('0', '1', '2')) || $varary[6] > 2) $this->exevars['articletype']=$varary[6];
			}
			
			if($arynum>7){
				$varary[7]=trim($varary[7]);
				if(in_array($varary[7], array('0', '1'))) $this->exevars['firstflag']=$varary[7];
			}
			
			if($arynum>8){
				$varary[8]=trim($varary[8]);
				if(is_numeric($varary[8])) $this->exevars['siteid']=$varary[8];
				// 设置main对应查询
				if(is_string($varary[8]) && 'main'==$varary[8]) $this->exevars['siteid']='main';
			}else $this->exevars['siteid']=JIEQI_SITE_ID;
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);	
		/*if($this->exevars['field']=='lastupdate' || $this->exevars['field']=='postdate'){
			jieqi_getcachevars('article', 'articleuplog');
			if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
			$this->blockvars['overtime'] = $jieqiArticleuplog['articleuptime'] > $jieqiArticleuplog['chapteruptime'] ? intval($jieqiArticleuplog['articleuptime']) : intval($jieqiArticleuplog['chapteruptime']);
		}*/
	}

	/**
	 * article 关联 article_stat 以 article为准查询
	 * @see JieqiBlock::setContent()
	 */
	function setContent($isreturn=false){
	    global $jieqiTpl;
		$package = $this->load('article','article');

		$this->db->init('article','articleid','article');

		if(!in_array($this->exevars['field'],array('monthsize','weeksize','daysize'))){//如果是字数榜
			// 获取全部列表（处文学以外-200）
			if('main'==$this->exevars['siteid']){
				$this->db->setCriteria(new Criteria ( 'siteid',200, '<' ));
			}else{
				$this->db->setCriteria(new Criteria ( 'siteid',$this->exevars['siteid'], '=' ));
			}
			$this->db->criteria->add ( new Criteria ( 'display',0 ));
			$this->db->criteria->add ( new Criteria ( 'firstflag',4, "<>" ));
			$statArray = $package->getStatArray();
			$statstr = $package->getStatStr();
			if(preg_match('/'.$statstr.'$/',$this->exevars['field'])){
				$this->db->criteria->setTables(jieqi_dbprefix('article_stat').' s RIGHT JOIN '.jieqi_dbprefix('article_article').' a ON s.articleid=a.articleid');
				$this->db->criteria->setFields('a.*,s.mid,s.total,s.month,s.week,s.day,s.totalnum,s.monthnum,s.weeknum,s.daynum,s.lasttime');
				$order = preg_replace("/$statstr/",'',$this->exevars['field']);
				$mid = str_replace($order,'',$this->exevars['field']);
				$midname = $statArray[$mid]['name'];
				$visitfield = $order;
				$this->db->criteria->add ( new Criteria ( 's.mid',$mid, '=' ));
				if($order!='total'){
					$this->db->criteria->add ( new Criteria ( 's.lasttime',$this->getTime($order), '>=' ));
				}
				$this->db->criteria->setSort( "s.$order" );
				$havastat = true;
			}else{
			    $this->db->criteria->add ( new Criteria ( 'chapters',0 ,'>')); 
				$visitfield = $this->exevars['field'];
				switch($this->exevars['field']){
					case 'size'://字数榜
						$this->db->criteria->setSort( "size" );
						$midname = "总字数";
					break;
					case 'signdate'://新书
						$this->db->criteria->setSort( "signdate" );
						$this->db->criteria->add ( new Criteria ( 'permission','3', '>' ));
						$midname = "最新签约";
					break;
					case 'vipdate'://新书
						$this->db->criteria->setSort( "vipdate" );
						$this->db->criteria->add ( new Criteria ( 'articletype',0, '>' ));
						$midname = "最新上架";
					break;
					case 'postdate'://新书
						$this->db->criteria->setSort( "postdate" );
						$midname = "最新新书";
					break;
					default:
						$this->db->criteria->setSort( "lastupdate" );
						$midname = "更新";
					break;
				}
				$havastat = false;
			}
		}else{
		     $visitfield = $this->exevars['field'];
		     // 获取全部列表（处文学以外-200）
			 if('main'==$this->exevars['siteid']){
				 $this->db->setCriteria(new Criteria ( 'a.siteid',200, '<' ));
			 }else{
				 $this->db->setCriteria(new Criteria ( 'a.siteid',$this->exevars['siteid'], '=' ));
			 }
		     $this->db->criteria->add ( new Criteria ( 'a.display',0 ));
		     $this->db->criteria->setTables($this->dbprefix('article_chapter ').' c RIGHT JOIN '.$this->dbprefix('article_article').' a ON c.articleid=a.articleid');
		     $this->db->criteria->setFields('a.*,sum( c.size ) AS '.$this->exevars['field']);
			 $this->db->criteria->add ( new Criteria ( 'a.articletype',0, '>' ));
			 $this->db->criteria->add ( new Criteria ( 'c.postdate',$this->getTime(str_replace('size','',$this->exevars['field'])), '>=' ));
			 $this->db->criteria->add ( new Criteria ( 'c.display',0, '=' ));
			 $this->db->criteria->setGroupby ('c.articleid');
			 $this->db->criteria->setSort( $this->exevars['field'] );
			 if($this->exevars['field']=='monthsize') $midname = "月更新字数";
			 elseif($this->exevars['field']=='weeksize') $midname = "周更新字数";
			 elseif($this->exevars['field']=='daysize') $midname = "日更新字数";
		}
		if(!empty($this->exevars['sortid'])){
			$sortstr='';
			$sortnum=0;
			$sortary=explode('|', $this->exevars['sortid']);
			foreach($sortary as $v){
				if(is_numeric($v)){
					if(!empty($sortstr)) $sortstr.=',';
					$sortstr.=intval($v);
					$sortnum++;
				}
			}
			if($sortnum==1) $this->db->criteria->add ( new Criteria ( 'sortid', $sortstr ));
			elseif($sortnum>1) $this->db->criteria->add ( new Criteria ( 'sortid', '('.$sortstr.')', 'IN' ));
		}
		
		if($this->exevars['isauthor']==1) $this->db->criteria->add ( new Criteria ( 'authorid', '0', '>' ));
		elseif($this->exevars['isauthor']==2) $this->db->criteria->add ( new Criteria ( 'authorid', '0', '>' ));
		if($this->exevars['isfull']==1) $this->db->criteria->add ( new Criteria ( 'fullflag', '1', '=' ));
		elseif($this->exevars['isfull']==2) $this->db->criteria->add ( new Criteria ( 'fullflag', '0', '=' ));
		if($this->exevars['articletype'] == 1) $this->db->criteria->add ( new Criteria ( 'articletype', '0', '>' ));//兼容老版本的数据有大于1的，新版本数据是1
		elseif($this->exevars['articletype']==2) $this->db->criteria->add ( new Criteria ( 'articletype', '0', '=' ));
		 
		//授权许可
		if(strlen($this->exevars['permission'])>0){
			$perstr='';
			$pernum=0;
			$perary=explode('|', $this->exevars['permission']);
			foreach($perary as $v){
				if(is_numeric($v)){
					if(!empty($perstr)) $perstr.=',';
					$perstr.=intval($v);
					$pernum++;
				}
			}
			if($pernum==1) $this->db->criteria->add ( new Criteria ( 'permission', $perstr ));
			elseif($pernum>1) $this->db->criteria->add ( new Criteria ( 'permission', '('.$perstr.')', 'IN' ));
		}
		//是否首发
		if(strlen($this->exevars['firstflag'])>0){
			$this->db->criteria->add ( new Criteria ( 'firstflag',intval($this->exevars['firstflag']), '=' ));
		}
		
		 if($this->exevars['asc']==1) $this->db->criteria->setOrder( 'ASC' );
		 else  $this->db->criteria->setOrder( 'DESC' );
		 $this->db->criteria->setLimit($this->exevars['listnum']);
		 $this->db->queryObjects($this->db->criteria);
		 
		 $k=0;
		 while($v = $this->db->getObject()){
		      $articlerows[$k] = $package->article_vars($v);
			  $articlerows[$k]['visitnum']=ceil($v->getVar($visitfield));
			  $articlerows[$k]['visitnum_w']=number_format($articlerows[$k]['visitnum']/10000,1,".","");//万字
			  if(in_array($visitfield,array('size','monthsize','weeksize','daysize'))){ 
			  	$articlerows[$k]['visitnum']=ceil($articlerows[$k]['visitnum']/2);
			  	$articlerows[$k]['visitnum_w']=number_format($articlerows[$k]['visitnum']/10000,1,".","");//万字
			  }
			  elseif($visitfield=='lastupdate' || $visitfield=='postdate' || $visitfield=='signdate' || $visitfield=='vipdate') {
			  	$articlerows[$k]['visitnum']=date('Y-m-d', $articlerows[$k]['visitnum']);
			  }
			  $k++;
		 }
		 $jieqiTpl->assign_by_ref('articlerows', $articlerows);
		 $jieqiTpl->assign('url_more', $this->geturl(JIEQI_MODULE_NAME, 'top',  'method=toplist', 'type='.$this->exevars['field'], 'SYS=sortid='.$this->exevars['sortid'].'&page=1'));
    }
}
?>