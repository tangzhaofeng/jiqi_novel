<?php 
/**
 * 排行榜模型
 * @author huliming  
 *
 */
class topModel extends Model{

	/*public $package = NULL;
	private function container(&$data){
	
		$this->package = $this->load('article','article');
	}
*/
	public function toplist($params = array()){
		global $jieqiModules;
		$package = $this->load('article','article');
		$data = $package->getSources();
		$this->addConfig ( 'article', 'configs' );
		$jieqiConfigs ['article'] = $this->getConfig ( 'article', 'configs' );	//文章配置
		//print_r($package->jieqiConfigs);exit;
		if (isset($params['siteid'])){
			$siteid = $params['siteid'];
		}else{
			//控制器所属的模块siteid
			$siteid = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];
		}
		$sitename  = $data['channel'][$siteid]['name'];
		if(!$params['page']) $params['page'] = 1;
		$this->db->init('article','articleid','article');
		if(!in_array($params['type'],array('monthsize','weeksize','daysize'))){//如果是字数榜
			$this->db->setCriteria(new Criteria('display',0));
			$this->db->criteria->add(new Criteria('firstflag',13,'<>'));
			$statArray = $package->getStatArray();
			$statstr = $package->getStatStr();
			if(preg_match('/'.$statstr.'$/',$params['type'])){
				$this->db->criteria->setTables($this->dbprefix('article_stat').' s RIGHT JOIN '.$this->dbprefix('article_article').' a ON s.articleid=a.articleid');
				$this->db->criteria->setFields('a.*,s.mid,s.total,s.month,s.week,s.day,s.totalnum,s.monthnum,s.weeknum,s.daynum,s.lasttime');
				$order = preg_replace("/$statstr/",'',$params['type']);
				$mid = str_replace($order,'',$params['type']);
				$midname = $statArray[$mid]['name'];
				$visitfield = $order;
				$this->db->criteria->add(new Criteria('s.mid',$mid,'='));
				if($order!='total'){
					$this->db->criteria->add (new Criteria('s.lasttime',$this->getTime($order),'>='));
				}
				$this->db->criteria->setSort( "s.$order" );
				$havastat = true;
			}else{
				$this->db->criteria->setTables($this->dbprefix('article_article').' a');//单独表，重命名
			    $this->db->criteria->add ( new Criteria ( 'chapters',0 ,'>'));
				$visitfield = $params['type'];
				switch($params['type']){
					case 'size'://字数榜
						$this->db->criteria->setSort( "size" );
						$midname = "总字数";
					break;
					case 'signdate'://新书
						$this->db->criteria->setSort( "signdate" );
						$this->db->criteria->add ( new Criteria ( 'permission','3', '>' ));
						$midname = "最新签约";
					break;
					case 'vipdate'://最新上架
						$this->db->criteria->setSort( "vipdate" );
						$this->db->criteria->add ( new Criteria ( 'articletype',0, '>' ));
						$midname = "最新上架";
					break;
					case 'free':
						$this->db->criteria->setSort( "visit" );
						$this->db->criteria->add ( new Criteria ( 'articletype',0, '=' ));
						$midname = "免费人气";
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
		     $visitfield = $params['type'];
		     $this->db->setCriteria(new Criteria ( 'a.display',0 ));
		     //$this->db->criteria->add ( new Criteria ( 'a.display',0 ));
		     $this->db->criteria->setTables($this->dbprefix('article_chapter ').' c RIGHT JOIN '.$this->dbprefix('article_article').' a ON c.articleid=a.articleid');
		     $this->db->criteria->setFields('a.*,sum( c.size ) AS '.$params['type']);
			 $this->db->criteria->add ( new Criteria ( 'a.articletype',0, '>' ));
			 $this->db->criteria->add ( new Criteria ( 'c.postdate',$this->getTime(str_replace('size','',$params['type'])), '>=' ));
			 $this->db->criteria->add ( new Criteria ( 'c.display',0, '=' ));
			 $this->db->criteria->setGroupby ('c.articleid');
			 $this->db->criteria->setSort( $params['type'] );
			 if($params['type']=='monthsize') $midname = "月更新字数";
			 elseif($params['type']=='weeksize') $midname = "周更新字数";
			 elseif($params['type']=='daysize') $midname = "日更新字数";
		}
		 if($params['sortid']) $this->db->criteria->add(new Criteria('sortid',$params['sortid'], '='));
		 if($siteid==0||$siteid==100||$siteid==200){
			 //指定分站
			 $this->db->criteria->add ( new Criteria ( 'a.siteid',$siteid, '=' ));
		 }else{
		 	$sitename = '';
		 }

		 
		 $this->db->criteria->setOrder('DESC');
		 if($params['listnum']) $jieqiConfigs['article']['toppagenum'] = $params['listnum'];
		 $this->db->criteria->setLimit($jieqiConfigs['article']['toppagenum']);
		 $this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['article']['toppagenum']);
		 $this->db->queryObjects($this->db->criteria);
		 
		 $k=0;
		 while($v = $this->db->getObject()){
		      $articlerows[$k] = $package->article_vars($v);
			  $articlerows[$k]['visitnum']=$v->getVar($visitfield);
			  if(in_array($visitfield,array('size','monthsize','weeksize','daysize'))) $articlerows[$k]['visitnum']=ceil($articlerows[$k]['visitnum']/2);
			  elseif($visitfield=='lastupdate' || $visitfield=='postdate' || $visitfield=='signdate' || $visitfield=='vipdate') $articlerows[$k]['visitnum']=date('Y-m-d', $articlerows[$k]['visitnum']);
			  $k++;
		 }
		 $jumppage = new GlobalPage(JIEQI_PAGE_TAG,$this->db->getCount($this->criteria),$jieqiConfigs['article']['toppagenum'],$params['page']);
		//print_r($data['channel'] );exit;
		 if (isset($params['siteid'])){
		 	/*if (!isset($data['channel'][$params['siteid']]['module'])){
// 		 		$module = $data['channel'][$params['siteid']]['module'];
		 	}else{
		 		$module = JIEQI_MODULE_NAME;
		 	}*/
// 			$module = $package->getModule($params['siteid'], 'siteid');
			$pageurl =  $this->geturl(JIEQI_MODULE_NAME,'top','method=toplist','evalpage=0','SYS=type='.$params['type'].'&sortid='.$params['sortid'].'&page='.$params['page'].'&siteid='.$siteid);			
		 }else{
		 	$wappageurl =  $this->geturl(JIEQI_MODULE_NAME,'top','method=toplist','SYS=type='.$params['type'].'&sortid='.$params['sortid'].'&page='.$params['page']);
		 	$pageurl =  $this->geturl(JIEQI_MODULE_NAME,'top','method=toplist','evalpage=0','SYS=type='.$params['type'].'&sortid='.$params['sortid'].'&page='.$params['page']);
		 }
		 if(!$params['topview'])$params['topview'] = 'on_list';
		 $datatop = array(
		 	 'topview'=>$params['topview'],
			 'articlerows'=>$articlerows,
			 'sortid'=>$params['sortid'],
			 'siteid'=>$params['siteid'],
		 	  'sitename'=>$sitename,
			 'sort'=>$data['sortrows'],//$package->jieqiConfigs['sort'],
			 'type'=>$params['type'],
			 'page'=>$params['page'],
			 'havastat'=>$havastat,
			 'order'=>$order,
			 'mid'=>$mid,
			 'midname'=>$midname,
		 	  'pageurl'=>$wappageurl,
		 	  'listnum'=>$jieqiConfigs['article']['toppagenum'],
			 'url_jumppage'=>$jumppage->getPage($pageurl)
		 );
		 if($params['sortid'] != 0){
		 	$datatop['sel_sort_name'] =$data['sortrows'][$params['sortid']]['shortcaption'];
		 	$datatop['sort_name'] =$data['sortrows'][$params['sortid']]['caption'];
		 }
		 return $datatop;
	} 
	public function ranklist($params){
	}
}
?>