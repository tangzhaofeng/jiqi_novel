<?php 
/** 
 * 系统管理->用户日志 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class userlogModel extends Model{
	public function main($params = array()){
//		global $jieqiModules;
//		$_REQUEST = $this->getRequest();
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		if(empty($params['page']) || !is_numeric($params['page'])) $params['page']=1;
		$this->db->init('userlog','logid','system');
		$this->db->setCriteria();
		
		if(!empty($params['keyword'])){
			if($params['keytype']==1) {$this->db->criteria->add(new Criteria('toname', $params['keyword'],'='));
			}else{ $this->db->criteria->add(new Criteria('fromname', $params['keyword'], '='));}
		}
		$this->db->criteria->setSort('logid');
		$this->db->criteria->setOrder('DESC');
		$this->db->criteria->setLimit($jieqiConfigs['system']['userlogpnum']);
		$this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['system']['userlogpnum']);
//		$this->db->queryObjects();
//		$logrows=array();
//		$k=0;
//		while($v = $this->db->getObject()){
//			$logrows[$k]['date']=date(JIEQI_DATE_FORMAT, $v->getVar('logtime'));
//			$logrows[$k]['time']=date(JIEQI_TIME_FORMAT, $v->getVar('logtime'));
//			$logrows[$k]['fromid']=$v->getVar('fromid');
//			$logrows[$k]['fromname']=$v->getVar('fromname');
//			$logrows[$k]['toid']=$v->getVar('toid');
//			$logrows[$k]['toname']=$v->getVar('toname');
//			$logrows[$k]['reason']=$v->getVar('reason');
//			$logrows[$k]['chginfo']=$v->getVar('chginfo');
//			$k++;
//		}
		//处理页面跳转
//		include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
//		$jumppage = new JieqiPage($this->db->getCount($criteria),$jieqiConfigs['system']['userlogpnum'],$params['page']);
//		$jumppage->setlink('', true, true);
//		return array('logrows'=>$logrows,
//			'url_jumppage'=>$jumppage->whole_bar(),
//		);
		 return array(
			 'logrows'=>$this->db->lists($jieqiConfigs['system']['userlogpnum'],$params['page']),
			 'url_jumppage'=>$this->db->getPage()
		 );
	}
} 
?>