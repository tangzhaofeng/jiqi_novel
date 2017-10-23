<?php 
/** 
 * 系统管理->用户管理->管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class usermanageModel extends Model{
	public function main($adminlevel){
		global $jieqiPower, $jieqiModules, $jieqiLang, $jieqiConfigs, $jieqiGroups;
		$_REQUEST = $this->getRequest();
		//检查权限
		/*$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}*/
		if(empty($_REQUEST['id'])) jieqi_printfail(LANG_NO_USER);
		$this->addLang('system', 'users');
		$_REQUEST['id'] = intval($_REQUEST['id']);
		
		$this->db->init('users','uid','system');
		$user = $this->db->get($_REQUEST['id']);
		if(!is_array($user)) jieqi_printfail(LANG_NO_USER);
		if($user['groupid'] == JIEQI_GROUP_ADMIN && $this->getUsersGroup() != JIEQI_GROUP_ADMIN) jieqi_printfail($jieqiLang['system']['cant_manage_admin']);
		
		/*if(jieqi_checkpower($jieqiPower['system']['deluser'], $jieqiUsersStatus, $jieqiUsersGroup, true, true)) $adminlevel=4;
		elseif(jieqi_checkpower($jieqiPower['system']['adminvip'], $jieqiUsersStatus, $jieqiUsersGroup, true, true)) $adminlevel=3;
		elseif(jieqi_checkpower($jieqiPower['system']['changegroup'], $jieqiUsersStatus, $jieqiUsersGroup, true, true)) $adminlevel=2;
		else $adminlevel=1;*/

		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$edit_form = new JieqiThemeForm($jieqiLang['system']['user_manage'], 'usermanage', JIEQI_URL.'/web_admin/?controller=usermanage&method=update');
		$edit_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_users_uname'], $user['uname']));
		$pass=new JieqiFormPassword($jieqiLang['system']['table_users_pass'], 'pass', 25, 20);
		$pass->setDescription($jieqiLang['system']['not_change_password']);
		$edit_form->addElement($pass);
		$edit_form->addElement(new JieqiFormPassword($jieqiLang['system']['confirm_password'], 'repass', 25, 20));
		$edit_form->addElement(new JieqiFormText('真实姓名', 'realName', 25, 25, $this->db->getFormat($user['realName'],'e')));
		$edit_form->addElement(new JieqiFormText('身份证号', 'IDNumber', 25, 25, $this->db->getFormat($user['IDNumber'],'e')));
		if($adminlevel >= 2){
			$group_select = new JieqiFormSelect($jieqiLang['system']['table_users_groupid'],'groupid', $this->db->getFormat($user['groupid'], 'e'));
			foreach($jieqiGroups as $key => $val){
				$group_select->addOption($key, $val);
			}
			$edit_form->addElement($group_select, true);
		}
		$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_experience'], 'experience', 25, 11, $this->db->getFormat($user['experience'],'e')));
		$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_score'], 'score', 25, 11, $this->db->getFormat($user['score'],'e')));
			
		if($adminlevel>=3){
			$edit_form->addElement(new JieqiFormText(JIEQI_EGOLD_NAME, 'egold', 25, 11, $this->db->getFormat($user['egold'],'e')));
			$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_esilver'], 'esilver', 25, 11, $this->db->getFormat($user['esilver'],'e')));
			$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_isvip'], 'isvip', 25, 11, $this->db->getFormat($user['isvip'],'e')));
			//$isvip=new JieqiFormRadio($jieqiLang['system']['table_users_isvip'], 'isvip', $this->db->getFormat($user['isvip'], 'e'));
			//$isvip->addOption(0, $jieqiLang['system']['user_no_vip']);
			//$isvip->addOption(1, $jieqiLang['system']['user_is_vip']);
			//$isvip->addOption(2, $jieqiLang['system']['user_super_vip']);
			//$edit_form->addElement($isvip);
		}
		if($adminlevel>=4){
			$yesno=new JieqiFormRadio($jieqiLang['system']['delete_user'], 'deluser', 0);
			$yesno->addOption(0, LANG_NO);
			$yesno->addOption(1, LANG_YES);
			$edit_form->addElement($yesno);
		}
		$edit_form->addElement(new JieqiFormTextArea($jieqiLang['system']['user_change_reason'], 'reason', '', 6, 60), true);
		$edit_form->addElement(new JieqiFormHidden('action', 'update'));
		$edit_form->addElement(new JieqiFormHidden('id',$_REQUEST['id']));
		$edit_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['user_save_change'], 'submit'));
		return array('jieqi_contents'=> '<br />'.$edit_form->render(JIEQI_FORM_MIDDLE).'<br />');
	}
	
	
	//$_REQUEST['action']=update
	public function update($adminlevel){
		global $jieqiPower, $jieqiModules, $jieqiLang, $jieqiConfigs, $jieqiGroups, $jieqiUsersGroup;
		$_REQUEST = $this->getRequest();
		//检查权限
//		$this->db->init('power','pid','system');
//		$this->db->setCriteria(new Criteria('modname', 'system', "="));
//		$this->db->criteria->setSort('pid');
//		$this->db->criteria->setOrder('ASC');
//		$this->db->queryObjects();
//		while($v = $this->db->getObject()){
//			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
//		}
		if(empty($_REQUEST['id'])) jieqi_printfail(LANG_NO_USER);
		$this->addLang('system', 'users');
		$_REQUEST['id'] = intval($_REQUEST['id']);
		
		$this->db->init('users','uid','system');
		$user = $this->db->get($_REQUEST['id']);
		if(!is_array($user)) jieqi_printfail(LANG_NO_USER);
		if($user['groupid'] == JIEQI_GROUP_ADMIN && $this->getUsersGroup() != JIEQI_GROUP_ADMIN) jieqi_printfail($jieqiLang['system']['cant_manage_admin']);
		
		/********************/
		$_POST['reason'] = trim($_POST['reason']);
		$_POST['pass'] = trim($_POST['pass']);
		$_POST['repass'] = trim($_POST['repass']);
		$_POST['realName'] = trim($_POST['realName']);
		$_POST['IDNumber'] = trim($_POST['IDNumber']);
		if (strlen($_POST['reason'])==0) $errtext .= $jieqiLang['system']['change_user_reason'].'<br />';
		//检查密码
		if ($_POST['pass'] != $_POST['repass']) $errtext .= $jieqiLang['system']['password_not_equal'].'<br />';
		//记录注册信息
		if(empty($errtext)) {
			//处理删除
			if($adminlevel>=4 && isset($_POST['deluser']) && $_POST['deluser']==1){
				if(!$this->db->delete($user['uid'])) jieqi_printfail($jieqiLang['system']['delete_user_failure']);
				else{
					//删除某用户的贴子
					$this->db->init('reviews','topicid','article');
					$this->db->setCriteria(new Criteria('posterid', $this->db->getFormat($user->getVar['uid'], 'n'), "="));
					$this->db->queryObjects();
					$k=0;$where='';
					while($v = $this->db->getObject()){
						 if(!empty($where)) $where.=', ';
						 $where.= $v->getVar('topicid','n');
						 $k++;
					}
					if(!empty($where)){
						$sql='DELETE FROM '.jieqi_dbprefix('article_reviews').' WHERE topicid IN ('.$where.')';
						$this->db->query($sql);
						$sql='DELETE FROM '.jieqi_dbprefix('article_replies').' WHERE topicid IN ('.$where.')';
						$this->db->query($sql);
					}
					
					$this->db->init('userlog','logid','system');
					//记录日志
					$data = array();
					//$userlog_handler = JieqiUserlogHandler::getInstance('JieqiUserlogHandler');
					//$newlog=$userlog_handler->create();
					$data['siteid'] = JIEQI_SITE_ID;
					$data['logtime'] = JIEQI_NOW_TIME;
					$data['fromid'] = $_SESSION['jieqiUserId'];
					$data['fromname'] = $_SESSION['jieqiUserName'];
					$data['toid'] = $this->db->getFormat($user['uid'], 'n');
					$data['toname'] = $this->db->getFormat($user['uname'], 'n');
					$data['reason'] = $_POST['reason'];
					$data['chginfo'] = $jieqiLang['system']['delete_user'];
					$data['chglog'] = '';
					$data['isdel'] = '1';
					$data['userlog'] = serialize($user);
					$this->db->add($data);
					jieqi_jumppage(JIEQI_URL.'/web_admin/?controller=users', LANG_DO_SUCCESS, $jieqiLang['system']['delete_user_success']);
					exit;
				}
			}
			
			$chglog=array();
			$chginfo='';
			//修改密码
			if(strlen($_POST['pass'])>0){
				$user['pass'] = md5($_POST['pass']);
				$chginfo.=$jieqiLang['system']['userlog_change_password'];
			}
			$user['realName'] = $_POST['realName'];
			$user['IDNumber'] = $_POST['IDNumber'];
			//经验值
			if(is_numeric($_POST['experience']) && $_POST['experience'] != $user['experience']){
				$chglog['experience']['from']=$user['experience'];
				$chglog['experience']['to']=$_POST['experience'];
				$user['experience'] = $_POST['experience'];
				if($chglog['experience']['from'] > $chglog['experience']['to']){
					$chginfo.=sprintf($jieqiLang['system']['userlog_less_experience'], $chglog['experience']['from'] - $chglog['experience']['to']);
				}else{
					$chginfo.=sprintf($jieqiLang['system']['userlog_add_experience'], $chglog['experience']['to'] - $chglog['experience']['from']);
				}
			}
			//积分
			if(is_numeric($_POST['score']) && $_POST['score'] != $user['score']){
				$chglog['score']['from']=$user['score'];
				$chglog['score']['to']=$_POST['score'];
				$user['score'] = $_POST['score'];
				if($chglog['score']['from'] > $chglog['score']['to']){
					$chginfo.=sprintf($jieqiLang['system']['userlog_less_score'], $chglog['score']['from'] - $chglog['score']['to']);
				}else{
					$chginfo.=sprintf($jieqiLang['system']['userlog_add_score'], $chglog['score']['to'] - $chglog['score']['from']);
				}
			}
			
			if($adminlevel>=2){
				//会员等级
				if(is_numeric($_POST['groupid']) && $_POST['groupid'] != $user['groupid']){
					if($_POST['groupid'] == JIEQI_GROUP_ADMIN && $jieqiUsersGroup != JIEQI_GROUP_ADMIN) jieqi_printfail($jieqiLang['system']['cant_set_admin']);
					$chglog['groupid']['from']=$user['groupid'];
					$chglog['groupid']['to']=$_POST['groupid'];
					$user['groupid'] = $_POST['groupid'];
					$chginfo.=sprintf($jieqiLang['system']['userlog_change_group'], $jieqiGroups[$chglog['groupid']['from']], $jieqiGroups[$chglog['groupid']['to']]);
				}
			}
			
			if($adminlevel>=3){
				//虚拟货币
				if(is_numeric($_POST['egold']) && $_POST['egold'] != $user['egold']){
					$chglog['egold']['from']=$user['egold'];
					$chglog['egold']['to']=$_POST['egold'];
					$user['egold'] = $_POST['egold'];
					if($chglog['egold']['from'] > $chglog['egold']['to']){
						$chginfo.=sprintf($jieqiLang['system']['userlog_less_egold'], JIEQI_EGOLD_NAME, $chglog['egold']['from'] - $chglog['egold']['to']);
					}else{
						$chginfo.=sprintf($jieqiLang['system']['userlog_add_egold'], JIEQI_EGOLD_NAME, $chglog['egold']['to'] - $chglog['egold']['from']);
					}
				}
				//银币
				if(is_numeric($_POST['esilver']) && $_POST['esilver'] != $user['esilver']){
					$chglog['esilver']['from']=$user['esilver'];
					$chglog['esilver']['to']=$peyment;
					$user['esilver'] = $_POST['esilver'];
					if($chglog['esilver']['from'] > $chglog['esilver']['to']){
						$chginfo.=sprintf($jieqiLang['system']['userlog_less_esilver'], $chglog['esilver']['from'] - $chglog['esilver']['to']);
					}else{
						$chginfo.=sprintf($jieqiLang['system']['userlog_add_esilver'], $chglog['esilver']['to'] - $chglog['esilver']['from']);
					}
				}
				
				//VIP状态
				if(is_numeric($_POST['isvip']) && $_POST['isvip'] != $user['isvip']){
					jieqi_loadlang('users', 'system');
/*					$vipflag = $user['isvip'];
					if($vipflag == 0) $tmpstr = $jieqiLang['system']['user_no_vip'];
					elseif($vipflag == 1) $tmpstr = $jieqiLang['system']['user_is_vip'];
					elseif($vipflag > 1) $tmpstr = $jieqiLang['system']['user_super_vip'];
					
					$chglog['isvip']['from']=$user['isvip'];
					$chglog['isvip']['to']=$_POST['groupid'];
					$user['isvip'] = $_POST['isvip'];
					$chginfo.=sprintf($jieqiLang['system']['userlog_change_vip'], $tmpstr, $tmpstr);*/
					$chglog['score']['from']=$user['isvip'];
					$chglog['score']['to']=$_POST['isvip'];
					$user['isvip'] = $_POST['isvip'];
					if($chglog['score']['from'] > $chglog['score']['to']){
						$chginfo.=sprintf($jieqiLang['system']['userlog_less_vipscore'], $chglog['score']['from'] - $chglog['score']['to']);
					}else{
						$chginfo.=sprintf($jieqiLang['system']['userlog_add_vipscore'], $chglog['score']['to'] - $chglog['score']['from']);
					}
				}
				
			}
			if (!$this->db->edit($_REQUEST['id'], $user)) jieqi_printfail($jieqiLang['system']['change_user_failure']);
			else {
				$this->db->init('userlog','logid','system');
				//记录日志
				$data = array();
				$data['siteid'] = JIEQI_SITE_ID;
				$data['logtime'] = JIEQI_NOW_TIME;
				$data['fromid'] = $_SESSION['jieqiUserId'];
				$data['fromname'] = $_SESSION['jieqiUserName'];
				$data['toid'] = $this->db->getFormat($user['uid'], 'n');
				$data['toname'] = $this->db->getFormat($user['uname'], 'n');
				$data['reason'] = $_POST['reason'];
				$data['chginfo'] = $chginfo;
				$data['chglog'] = serialize($chglog);
				$data['isdel'] = '0';
				$data['userlog'] = '';
				$this->db->add($data);
				jieqi_jumppage(JIEQI_URL.'/web_admin/?controller=users', LANG_DO_SUCCESS, $jieqiLang['system']['change_user_success']);
			}
		} else {
			jieqi_printfail($errtext);
		}

	}
}