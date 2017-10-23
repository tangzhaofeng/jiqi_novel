<?php 
/**
 * 排行榜模型
 * @author huliming  
 *
 */
class topModel extends Model{

	public function toplist($params = array())
	{
		global $jieqiModules;
		$package = $this->load('article','article');
		$this->addConfig ( 'article', 'configs' );
		$jieqiConfigs ['article'] = $this->getConfig ( 'article', 'configs' );	//文章配置
		if(array_key_exists(JIEQI_MODULE_NAME,$jieqiModules)){
			$siteid = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];	
		}else{
			$siteid = $jieqiModules['system']['siteid'];
		}
		if(!$params['page']) $params['page'] = 1;
		$this->db->init('article','articleid','article');
		if(in_array($params['type'],array('daysale','weeksale','monthsale','totalsale'))){	//如果是销售榜
			$this->db->setCriteria(new Criteria ( 'siteid',$siteid, '=' ));
			$this->db->criteria->add(new Criteria('display',0));
			$statArray = $package->getStatArray();
			$statstr = $package->getStatStr();
			if(preg_match('/'.$statstr.'$/',$params['type'])){
				$this->db->criteria->setTables($this->dbprefix('article_stat').' s RIGHT JOIN '.$this->dbprefix('article_article').' a ON s.articleid=a.articleid');
				$this->db->criteria->setFields('a.*,s.mid,s.total,s.month,s.week,s.day');
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
			}
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
			  $articlerows[$k]['day'] = sprintf("%1\$.2f",$articlerows[$k]['day']);
			  $articlerows[$k]['week'] = sprintf("%1\$.2f",$articlerows[$k]['week']);
			  $articlerows[$k]['month'] = sprintf("%1\$.2f",$articlerows[$k]['month']);
			  $articlerows[$k]['total'] = sprintf("%1\$.2f",$articlerows[$k]['total']);
			  $k++;
		 }
		 $jumppage = new GlobalPage(JIEQI_PAGE_TAG,$this->db->getCount($this->criteria),$jieqiConfigs['article']['toppagenum'],$params['page']);
		 $data = $package->getSources();
		 $url =  parse_url( $this->geturl('admin','top','method=toplist','evalpage=0','SYS=type='.$params['type'].'&sortid='.$params['sortid'].'&page='.$params['page']));
		 return array(
		 	 'topview'=>$params['topview'],
			 'articlerows'=>$articlerows,
			 'sortid'=>$params['sortid'],
			 'sort'=>$data['sortrows'],
			 'type'=>$params['type'],
			 'page'=>$params['page'],
			 'havastat'=>$havastat,
			 'order'=>$order,
			 'mid'=>$mid,
			 'midname'=>$midname,
			 'url_jumppage'=>$jumppage->getPage($url['path'])
		 );
	}
}
?>