<?php 
/** 
 * 用户信息修改模型 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class usereditModel extends Model{ 
	
	/**
	 * 修改保存
	 * @param unknown $param
	 */
	function useredit($param){
		global $jieqiPower, $jieqiLang, $jieqiConfigs;
		$this->addLang('system', 'users');
		$auth = $this->getAuth();
		$users_handler = $this->getUserObject();
		$jieqiUsers = $users_handler->get($auth['uid']);

		$param['email'] = trim($param['email']);
		$errtext='';
		//修改昵称
		$changenick=false;
		if($jieqiUsers->getVar('name', 'n') != $param['nickname']){
			if($param['nickname'] != ''){
				if($users_handler->getByname($param['nickname'], 3) != false) $errtext .= $jieqiLang['system']['user_name_exists'].'<br />';
			}
			$changenick=true;
		}
		//记录注册信息
		if(empty($errtext)) {
			$hobbys = $param['hobby'];
			$setting = unserialize($jieqiUsers->getVar('setting','n'));
			$setting['hobby'] = $this->arrayToStr($hobbys); 
			$jieqiUsers->unsetNew();
			$jieqiUsers->setVar('name', $param['nickname']);
			$jieqiUsers->setVar('setting',serialize($setting));
			$jieqiUsers->setVar('realname', $param['realname']);
			$jieqiUsers->setVar('sex', $param['sex']);
			$jieqiUsers->setVar('url', $param['url']);
			$jieqiUsers->setVar('qq', $param['qq']);
			$jieqiUsers->setVar('msn', $param['msn']);
			if($param['idnumber']){
				//已经填写的无法更改
				$jieqiUsers->setVar('idnumber', $param['idnumber']);
			}
			if($param['viewemail'] != 1) $param['viewemail']=0;
			$jieqiUsers->setVar('viewemail', $param['viewemail']);
			$jieqiUsers->setVar('adminemail', $param['adminemail']);
			if(isset($param['workid']) && intval($jieqiUsers->getVar('workid', 'n')) != intval($param['workid'])){
				$jieqiUsers->setVar('workid', $param['workid']);
				$changework=true;
			}else{
				$changework=false;
			}
			$jieqiUsers->setVar('sign', $param['sign']);
			$jieqiUsers->setVar('intro', $param['intro']);
			if (!$users_handler->insert($jieqiUsers)) $this->printfail($jieqiLang['system']['user_edit_failure']);
			else {
				if($changework && $auth['uid'] == $jieqiUsers->getVar('uid')){
					jieqi_getconfigs('system', 'honors');
					$honorid=jieqi_gethonorid($jieqiUsers->getVar('score'), $jieqiHonors);
					$auth['honor'] = $jieqiHonors[$honorid]['name'][intval($jieqiUsers->getVar('workid', 'n'))];
				}
				if($changenick && $auth['uid'] == $jieqiUsers->getVar('uid')){
					$auth['username']=(strlen($jieqiUsers->getVar('name', 'n')) > 0) ? $jieqiUsers->getVar('name', 'n') : $jieqiUsers->getVar('uname', 'n');
				}
				$users_handler->saveToSession($jieqiUsers);
				$this->jumppage($this->geturl('system','userhub','SYS=method=usereditView'), LANG_DO_SUCCESS, $jieqiLang['system']['user_edit_success']);
			}
		} else {
			$this->printfail($errtext);
		}
	}
	/**
	 * 修改信息表单
	 * @param unknown $param
	 * @return multitype:NULL
	 */
	function usereditView($param){
		$data = array();
		$auth = $this->getAuth();
		$users_handler = $this->getUserObject();
		$jieqiUsers = $users_handler->get($auth['uid']);
		$articleLib = $this->load('article','article');
		$data['user'] = $articleLib->article_vars($jieqiUsers);
		//分类
		$sour = $articleLib->getSources();
		$data['sort'] = $sour['sortrows'];
		$setting = unserialize($jieqiUsers->getVar('setting','n'));
		$data['hobby'] = explode(',',$setting['hobby']);
		return 	$data;	
	}

}
?>