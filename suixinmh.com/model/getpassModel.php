<?php
/** 
 * 找回密码模型 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class getpassModel extends Model{
	function main($params = array()){
		//载入语言
		$this->addLang('system', 'users');
		$jieqiLang['system'] = $this->getLang('system');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		//检查验证码
		if(!empty($jieqiConfigs['system']['checkcodelogin']) && $params['checkcode'] != $_SESSION['jieqiCheckCode']) $this->printfail($jieqiLang['system']['error_checkcode']);
		if(!isset($params['nouser'])){
			if($params['uname']=='' || $params['email']=='') $this->printfail($jieqiLang['system']['need_user_email']);
			$users_handler =  $this->getUserObject();
			$user=$users_handler->getByname($params['uname']);
		}else{
			$this->db->init('users','uid','system');
			$this->db->setCriteria(new Criteria('email', $params['email']));
			$this->db->queryObjects();
			if($user=$this->db->getObject()){
				$params['uname'] = $user->getVar('uname');
			}
		}
		if(is_object($user)){
			if($user->getVar('email', 'n')==$params['email']){
				//include_once(JIEQI_ROOT_PATH.'/lib/mail/mail.php');
				
				$to = $_POST['email'];
				$title = strpos($jieqiLang['system']['reset_password'], '%s') ? sprintf($jieqiLang['system']['reset_password'], JIEQI_SITE_NAME) :  JIEQI_SITE_NAME.$jieqiLang['system']['reset_password'];
				$seturl = $this->geturl(JIEQI_MODULE_NAME,'setpass','SYS=id='.$user->getVar('uid').'&checkcode='.md5($user->getVar('pass').JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME.JIEQI_SITE_KEY));
				$content = strpos($jieqiLang['system']['get_password_link'], '%s') ? sprintf($jieqiLang['system']['get_password_link'],$params['uname'],$seturl) : $jieqiLang['system']['get_password_link'].$seturl;
				
				/*$params=array();
				if(isset($jieqiConfigs['system']['mailtype'])) $params['mailtype'] = $jieqiConfigs['system']['mailtype'];
				if(isset($jieqiConfigs['system']['maildelimiter'])) $params['maildelimiter'] = $jieqiConfigs['system']['maildelimiter'];
				if(isset($jieqiConfigs['system']['mailfrom'])) $params['mailfrom'] = $jieqiConfigs['system']['mailfrom'];
				if(isset($jieqiConfigs['system']['mailserver'])) $params['mailserver'] = $jieqiConfigs['system']['mailserver'];
				if(isset($jieqiConfigs['system']['mailport'])) $params['mailport'] = $jieqiConfigs['system']['mailport'];
				if(isset($jieqiConfigs['system']['mailauth'])) $params['mailauth'] = $jieqiConfigs['system']['mailauth'];
				if(isset($jieqiConfigs['system']['mailuser'])) $params['mailuser'] = $jieqiConfigs['system']['mailuser'];
				if(isset($jieqiConfigs['system']['mailpassword'])) $params['mailpassword'] = $jieqiConfigs['system']['mailpassword'];
				$jieqimail = new JieqiMail($to, $title, $content, $params);
				$jieqimail->sendmail();*/
				$mail = $this->load('mail', 'system');
				$mail->sendmail($to, $title, $content);
				if($mail->isError(JIEQI_ERROR_RETURN)){
					$this->printfail(sprintf($jieqiLang['system']['email_send_failure'], implode('<br />', $mail->getErrors(JIEQI_ERROR_RETURN))));
				}else{
					$this->msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['send_password_success']);
				}
			}else{
				$this->printfail($jieqiLang['system']['email_not_users']);
			}
		}else{
			$this->printfail(LANG_NO_USER);
		}
	}
	//客户端 找回密码
	function getpass($params = array()){
		$this->addLang('system', 'users');
		$jieqiLang['system'] = $this->getLang('system');
//		$this->addConfig('system','configs');
//		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		if($params['email']=='') $this->printfail($jieqiLang['system']['need_user_email']);
		
		$this->db->init('users','uid','system');
		$this->db->setCriteria(new Criteria('email', $params['email']));
		$this->db->queryObjects();
		if($user=$this->db->getObject()){
			$params['uname'] = $user->getVar('uname');
			$to = $params['email'];
			$title = strpos($jieqiLang['system']['reset_password'], '%s') ? sprintf($jieqiLang['system']['reset_password'], JIEQI_SITE_NAME) :  JIEQI_SITE_NAME.$jieqiLang['system']['reset_password'];
			//$seturl = $this->geturl(JIEQI_MODULE_NAME,'setpass','SYS=id='.$user->getVar('uid').'&checkcode='.md5($user->getVar('pass')));
			$rand = rand(100000,999999);
			$content = strpos($jieqiLang['system']['get_password_code'], '%s') ? sprintf($jieqiLang['system']['get_password_code'],$params['uname'],$rand) : $jieqiLang['system']['get_password_code'].$rand;
			$mail = $this->load('mail','article');
			//$jieqimail = new MyMail($to, $title, $content);
			$mail->sendmail($to, $title, $content);
			if($mail->isError(JIEQI_ERROR_RETURN)){
				$this->printfail(sprintf($jieqiLang['system']['email_send_failure'], implode('<br />', $mail->getErrors(JIEQI_ERROR_RETURN))));
			}else{
				//$this->setMessage('0000','邮件发送成功');
				$this->db->init('verify','vid','system');
				$data = array(
					'uid'=>$user->getVar('uid'),
					'uname'=>$params['uname'],
					'email'=>$params['email'],
					'addtime'=>JIEQI_NOW_TIME,
					'checkcode'=>$rand,
					'flag'=>0
				);
				if($this->db->add($data,false)){
					$this->msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['send_password_success']);
				}/*else{
					$this->setMessage('0500','服务器内部错误!');
				}*/
			}
		}else{
			$this->printfail(LANG_NO_USER);
		}
	}
}
?>