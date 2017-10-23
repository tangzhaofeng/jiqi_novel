<?php 
/** 
 * 小说连载->书评管理 * @copyright   Copyright(c) 2014 
 * @author      liujilei* @version     1.0 
 */ 

class articlelogModel extends Model{
	public function main($params = array()){
	     global $jieqiModules;
		 $this->addconfig('article', 'power');
		 $this->addconfig('article', 'configs');
		 $jieqiConfigs['article'] = $this->getconfig('article', 'configs');
		 $jieqiPower['article'] = $this->getconfig('article', 'power');
		 if(empty($jieqiConfigs['article']['articlelogpnum'])) $jieqiConfigs['article']['articlelogpnum']=$jieqiConfigs['article']['pagenum'];
		 $this->db->init('articlelog ','logid','article');
		 $this->db->setCriteria();
		 if(empty($params['page']) || !is_numeric($params['page'])) $params['page']=1;
		 if(!empty($params['keyword'])){
			if($params['keytype']==1) $this->db->criteria->add(new Criteria('articlename', '%'.$params['keyword'].'%', 'like'));
			else $this->db->criteria->add(new Criteria('username', $params['keyword'], '='));
		 } 
		 $this->db->criteria->setSort('logid');
         $this->db->criteria->setOrder('DESC');
		 $this->db->criteria->setLimit($jieqiConfigs['article']['articlelogpnum']);
		 $this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['article']['articlelogpnum']);
		 $this->db->queryObjects();
		 $k=0;
		 $logrows=array();
		 while($v = $this->db->getObject()){
			$logrows[$k]['logtime']=$v->getVar('logtime');
			$logrows[$k]['date']=date(JIEQI_DATE_FORMAT, $v->getVar('logtime'));
			$logrows[$k]['time']=date(JIEQI_TIME_FORMAT, $v->getVar('logtime'));
			$logrows[$k]['userid']=$v->getVar('userid');
			$logrows[$k]['username']=$v->getVar('username');
			$logrows[$k]['articleid']=$v->getVar('articleid');
			$logrows[$k]['articlename']=$v->getVar('articlename');
			$logrows[$k]['chapterid']=$v->getVar('chapterid');
			$logrows[$k]['chaptername']=$v->getVar('chaptername');
			$logrows[$k]['reason']=$v->getVar('reason');
			$logrows[$k]['chginfo']=$v->getVar('chginfo');
			$logrows[$k]['chglog']=$v->getVar('chglog');
			$logrows[$k]['isdel']=$v->getVar('isdel');
			$logrows[$k]['ischapter']=$v->getVar('ischapter');
			$k++;
		 }
		 
		 
		// $data = $this->db->lists($jieqiConfigs['article']['articlelogpnum'],$params['page']);
		 include_once(HLM_ROOT_PATH.'/lib/html/page.php');
		 $jumppage = new JieqiPage($this->db->getCount($this->db->criteria),$jieqiConfigs['article']['articlelogpnum'],$params['page']);
		 $jumppage->setlink('', true, true);
		 return array(
		 'logrows'=>$logrows,
		 'article_static_url'=>(empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'],
		 'url_jumppage'=>$jumppage->whole_bar()
		 );
	}	
}
?>