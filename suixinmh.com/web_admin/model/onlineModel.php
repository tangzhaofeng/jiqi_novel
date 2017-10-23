<?php 
/** 
 * 系统管理->在线用户管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class onlineModel extends Model{
	public function main($params = array()){
		global $jieqiModules,$jieqiGroups;

		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$jieqiConfigs['system']['useradminpnum'] = 30;
		if(empty($params['page']) || !is_numeric($params['page'])) $params['page']=1;
		$this->db->init('online','uid','system');
		if(isset($params['action']) && $params['action']=='del' && !empty($params['sid'])){
			$mysid=session_id();
			@session_id($params['sid']);
			@session_destroy();
			@session_id($mysid);
			$this->db->setCriteria(new Criteria('sid', $params['sid'], '='));
			$this->db->queryObjects();
			if($v = $this->db->getObject()){
				$uid = intval($v->getVar('uid','n'));
			}
			$this->db->delete($uid);
			unset($criteria);
		}

		$this->db->setCriteria(new Criteria('updatetime', JIEQI_NOW_TIME-$jieqiConfigs['system']['onlinetime'], '>'));
		$allnum=$this->db->getCount($this->db->criteria);
		$this->db->criteria->add(new Criteria('uid', '0'), '>');
		
		if(isset($params['username']) && !empty($params['username'])){
			$this->db->criteria->add(new Criteria('name', $params['username'], '='));
		}elseif(isset($params['groupid']) && !empty($params['groupid'])){
			$this->db->criteria->add(new Criteria('groupid', $params['groupid'], '='));	
		}
		$this->db->criteria->setSort('updatetime');
		$this->db->criteria->setOrder('DESC');
		$this->db->criteria->setLimit($jieqiConfigs['system']['useradminpnum']);
		$this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['system']['useradminpnum']);
		$this->db->queryObjects();
		$userrows=array();
		$k=0;
		while($v = $this->db->getObject()){
			$userrows[$k]['sid']=$v->getVar('sid');
			$userrows[$k]['userid']=$v->getVar('uid');
			$userrows[$k]['username']=$v->getVar('uname');
			$userrows[$k]['name']=$v->getVar('name');
			if(strlen($userrows[$k]['name']) == 0) $userrows[$k]['name'] = $userrows[$k]['username'];
			$userrows[$k]['group']=$jieqiGroups[$v->getVar('groupid')];
			$userrows[$k]['email']=$v->getVar('email');
			$userrows[$k]['logintime']=date(JIEQI_TIME_FORMAT, $v->getVar('logintime'));
			$userrows[$k]['updatetime']=date(JIEQI_TIME_FORMAT, $v->getVar('updatetime'));
			$userrows[$k]['operate']=$v->getVar('operate');
			$userrows[$k]['ip']=$v->getVar('ip');
			$userrows[$k]['browser']=$v->getVar('browser');
			$userrows[$k]['os']=$v->getVar('os');
			$userrows[$k]['location']=$v->getVar('location');
			//$userrows[$k]['action']='<a href="'.JIEQI_URL.'/admin/online.php?action=del&sid='.$v->getVar('sid').'">强制下线</a>';
			$k++;
		}
		$rowcount=$this->db->getCount($this->db->criteria);
		//处理页面跳转
		include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($rowcount,$jieqiConfigs['system']['useradminpnum'],$params['page']);
		$jumppage->setlink('', true, false);
		$url_jumppage = $jumppage->whole_bar();
		return array('userrows'=>$userrows,
			'rowcount'=>$rowcount,
			'url_jumppage'=>$url_jumppage,
		);

	}
} 
?>