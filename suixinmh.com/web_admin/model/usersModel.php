<?php 
/** 
 * 系统管理->用户管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class usersModel extends Model{
	public function main($params = array()){
//		global $jieqiConfigs;
//		$_REQUEST = $this->getRequest();
		//检查权限
//		$this->db->init('power','pid','system');
//		$this->db->setCriteria(new Criteria('modname', 'system', "="));
//		$this->db->criteria->setSort('pid');
//		$this->db->criteria->setOrder('ASC');
//		$this->db->queryObjects();
//		while($v = $this->db->getObject()){
//			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
//		}
		if($params['action']=='login'){
		    $users_handler =  $this->getUserObject();
			$jieqiUsers = $users_handler->get($params['id']);
			include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
				jieqi_loginprocess($jieqiUsers, 1);
				$_SESSION['onelogin'] = true;
				header('location:'.geturl('system','userhub'));exit;
		}
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		if(empty($params['page']) || !is_numeric($params['page'])) $params['page']=1;
		$checkall = '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">';
		
		$this->db->init('users','uid','system');
		$this->db->setCriteria();
		
		if(isset($params['keyword']) && !empty($params['keyword'])){
			if($params['keytype']=='name')
				$this->db->criteria->add(new Criteria('name', $params['keyword'], '='));
			elseif($params['keytype']=='uname')
				$this->db->criteria->add(new Criteria('uname', $params['keyword'], '='));
			elseif($params['keytype']=='uid')
				$this->db->criteria->add(new Criteria('uid', $params['keyword'], '='));
		}elseif(isset($params['groupid']) && !empty($params['groupid'])){
			$this->db->criteria->add(new Criteria('groupid', $params['groupid'], '='));
		}
		if($params['start']){
			$params['start'] = urldecode($params['start']);
			$start = strtotime($params['start']);
			$this->db->criteria->add(new Criteria('regdate', $start,'>='));
		}
		if($params['end']){
			$params['end'] = urldecode($params['end']);
			$end = strtotime($params['end']);
			$this->db->criteria->add(new Criteria('regdate', $end,'<='));
		}
		//todo 添加来源查询 2015-3-16 chengyuan
		if(isset($params['sel_site']) && $params['sel_site'] != -1){
			if(is_numeric($params['sel_site'])){
				$this->db->criteria->add(new Criteria('siteid', $params['sel_site']));
			}else{
				$this->db->criteria->add(new Criteria('siteid', '','in('.$params['sel_site'].')'));
			}
		}
		$this->db->criteria->setSort('uid');
		$this->db->criteria->setOrder('DESC');
		$this->db->criteria->setLimit($jieqiConfigs['system']['useradminpnum']);
		$this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['system']['useradminpnum']);
		$this->db->queryObjects();
		$userrows=array();
		global $jieqiGroups;
		$k=0;
		$articleLib =  $this->load('article','article');
		while($v = $this->db->getObject()){
//			$userrows[$k]['checkbox']='<input type="checkbox" id="checkid'.$k.'" name="checkid'.$k.'" value="'.$v->getVar('uid').'">';
			$userrows[$k]['userid']=$v->getVar('uid');
			$userrows[$k]['username']=$v->getVar('uname');
			$userrows[$k]['name']=$v->getVar('name');
			if($userrows[$k]['name']=='') $userrows[$k]['name']=$userrows[$k]['username'];
			$userrows[$k]['group']=$jieqiGroups[$v->getVar('groupid')];
//			$userrows[$k]['email']=$v->getVar('email');
			$userrows[$k]['regdate']=date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $v->getVar('regdate'));
			$userrows[$k]['experience']=$v->getVar('experience');
			$userrows[$k]['score']=$v->getVar('score');
			$userrows[$k]['egold']=$v->getVar('egold');
			$userrows[$k]['esilver']=$v->getVar('esilver');
			$userrows[$k]['isvip']=$v->getVar('isvip');
                        $userrows[$k]['source']=$v->getVar('source','n');
			$userrows[$k]['setting']=unserialize($v->getVar('setting','n'));
			$userrows[$k]['from'] = $articleLib->getFromBySiteid($v->getVar('siteid'));
//			$userrows[$k]['emoney']=$userrows[$k]['egold']+$userrows[$k]['esilver'];
			$k++;
		}
		$grouprows=array();
		$i=0;
		foreach($jieqiGroups as $k=>$v){
			if($k>1){
				$grouprows[$i]['groupid']=$k;
				$grouprows[$i]['groupname']=$v;
				$i++;
			}
		}
		$rowcount=$this->db->getCount($this->db->criteria);
		//处理页面跳转
		include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($rowcount,$jieqiConfigs['system']['useradminpnum'],$params['page']);
		$jumppage->setlink('', true, true);
		
		return array(
			'sel_site'=>$params['sel_site'],
			'sites'=>$articleLib->getSitesCombine(),
//			'checkall'=>$checkall,
			'userrows'=>$userrows,
			'grouprows'=>$grouprows,
			'rowcount'=>$rowcount,
			'url_jumppage'=>$jumppage->whole_bar(),
			'start'=>$params['start'],
			'end'=>$params['end'],
		);						
	}
} 
?>