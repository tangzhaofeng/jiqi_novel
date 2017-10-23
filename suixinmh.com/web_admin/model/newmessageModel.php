<?php 
/** 
 * ϵͳ����->���Ͷ��� * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class newmessageModel extends Model{
	public function main($params = array()){
		global $jieqiModules;

		$this->addLang('system', 'message');
		$jieqiLang['system'] = $this->getLang('system');
		jieqi_getconfigs('system', 'configs');

		$message=false;
		if(!empty($params['reid']) || !empty($params['fwid'])){
			$this->db->init('message','messageid','system');
			if(!empty($params['reid'])){
				$message=$this->db->get($params['reid']);
			}elseif(!empty($params['fwid'])){
				$message=$this->db->get($params['fwid']);
			}
		}
		if(is_array($message)) {
			$params['receiver']=$this->db->getFormat($message['fromname'], 'e');
			$params['title']=$this->db->getFormat($message['title'], 'e');
			if(!empty($params['reid'])){
				$params['title']='Re:'.$params['title'];
				$params['content']='';
			}elseif(!empty($params['fwid'])){
				$params['title']='Fw:'.$params['title'];
				$params['content']=$this->db->getFormat($message['content'], 'e');
			}
		}
		
		if(!isset($params['receiver'])) $params['receiver']='';
		if(!isset($params['title'])) $params['title']='';
		if(!isset($params['content'])) $params['content']='';
		$data = array();
		$data['url_newmessage'] = $this->getAdminurl('newmessage');
		$data['receiver'] = $params['receiver'];
		$data['title'] = $params['title'];
		$data['content'] = $params['content'];
		$data['action'] = 'newmessage';
		return $data;

	}





	public function newmsg($params = array()){
		global $jieqiPower, $jieqiModules;
		//���Ȩ��
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}
		$this->addLang('system', 'message');
		$jieqiLang['system'] = $this->getLang('system');
		$params['receiver']=trim($params['receiver']);
		$params['title']=trim($params['title']);
		$errtext='';
		if(strlen($params['receiver'])==0) $errtext.=$jieqiLang['system']['message_need_receiver'].'<br />';
		if(strlen($params['title'])==0) $errtext.=$jieqiLang['system']['message_need_title'].'<br />';
		if(empty($errtext)) {
			$this->db->init('users','uid','system');
			//�����û��Ƿ����
			if (!empty($params['receiver'])) {
				$flag = 3;
				$params['receiver']=jieqi_dbslashes($params['receiver']);
				if($flag==3) $sql = "SELECT * FROM jieqi_system_users WHERE uname='".$params['receiver']."' UNION ALL SELECT * FROM jieqi_system_users WHERE name='".$params['receiver']."'";// ORDER BY name DESC";
				elseif($flag==2) $sql = "SELECT * FROM jieqi_system_users WHERE name='".$params['receiver']."'";
				else $sql = "SELECT * FROM jieqi_system_users WHERE uname='".$params['receiver']."'";
				if (!$result = $this->db->query($sql)){
					$touser = false;
				}
				$numrows = $this->db->getRowsNum($result);
				if ($numrows >= 1) {
					$touser = $this->db->fetchArray($result);
				}
			}else{$touser = false;}
			
			if(!$touser) jieqi_printfail($jieqiLang['system']['message_no_receiver']);
			$data = array();
			$this->db->init('message','messageid','system');
			$data['siteid'] = JIEQI_SITE_ID;
			$data['postdate'] = JIEQI_NOW_TIME;
			$data['fromid'] = 0;
			$data['fromname'] = $_SESSION['jieqiUserName'];
			$data['toid'] = $this->db->getFormat($touser['uid'], 'n');

			if(strlen($this->db->getFormat($touser['name'], 'n')) > 0) $data['toname'] = $this->db->getFormat($touser['name'], 'n');
			else $data['toname'] = $this->db->getFormat($touser['uname'], 'n');

			$data['title'] = $params['title'];
			$data['content'] = $params['content'];
			$data['messagetype'] = 0;
			$data['isread'] = 0;
			$data['fromdel'] = 0;
			$data['todel'] = 0;
			$data['enablebbcode'] = 1;
			$data['enablehtml'] = 0;
			$data['enablesmilies'] = 1;
			$data['attachsig'] = 0;
			$data['attachment'] = 0;
			if(!$this->db->add($data)) jieqi_printfail($jieqiLang['system']['message_send_failure']);
			else{
				jieqi_jumppage('/web_admin/?controller=message&method=inbox', LANG_DO_SUCCESS, $jieqiLang['system']['message_send_seccess']);
			}
		}else{
			jieqi_printfail($errtext);
		}
	}
}