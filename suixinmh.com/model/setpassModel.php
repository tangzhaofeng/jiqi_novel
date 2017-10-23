<?php
/** 
 * 重设密码模型 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class setpassModel extends Model{
	function main($params = array()){
		//载入语言
		$this->addLang('system', 'users');
		$jieqiLang['system'] = $this->getLang('system');
		if(empty($params['id']) || empty($params['checkcode'])) $this->printfail($jieqiLang['system']['no_checkcode_id']);
		$users_handler =  $this->getUserObject();
		$user=$users_handler->get($params['id']);
		if(!is_object($user)) $this->printfail(LANG_NO_USER);
		if(md5($user->getVar('pass').JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME.JIEQI_SITE_KEY) != $params['checkcode']) $this->printfail($jieqiLang['system']['error_checkcode']);
		if($this->submitcheck()){
			$params['pass'] = trim($params['pass']);
			$params['repass'] = trim($params['repass']);
			//检查密码
			if (strlen($params['pass'])==0 || strlen($params['repass'])==0) $this->printfail($jieqiLang['system']['need_pass_repass']);
			elseif ($params['pass'] != $params['repass']) $this->printfail($jieqiLang['system']['password_not_equal']);
			$user->setVar('pass', $users_handler->encryptPass($params['pass']));
			$users_handler->insert($user);
			$this->jumppage($this->geturl(JIEQI_MODULE_NAME,'login'), LANG_DO_SUCCESS, $jieqiLang['system']['set_password_success']);
		}
		return array(
		    'id'=>$user->getVar('uid'),
			'useruname'=>$user->getVar('uname'),
			'avatar'=>$user->getVar('avatar'),
			'checkcode'=>$params['checkcode'],
			'url_setpass'=>$this->geturl(JIEQI_MODULE_NAME,'setpass'),
		);
	}
	//客户端 修改密码
	function setpass($params = array()){
		if(!$this->P['email'] || !$this->P['username'] || !$this->P['passwd'] || !$this->P['seckey']) $this->setMessage('0001','参数错误!');
		else{
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
			$this->P['username'] = jieqi_utf82gb($this->P['username']);
			if($query = $users_handler->getByname($this->P['username'], 3)){//print_r($query);
				$uid = $query->getVar('uid');
				if($query->getVar('email')==$this->P['email']){
					$this->db->init('verify','vid','system');
					$this->db->setCriteria(new Criteria('uid', $uid));
					$this->db->criteria->setSort('vid');
					$this->db->criteria->setOrder('DESC');
					$this->db->criteria->setLimit(1);
					$this->db->queryObjects();
					if( $record = $this->db->getObject () ) {//print_r($chapter);
						$overtime = $record->getVar ( 'addtime' )+1800;
						if(JIEQI_NOW_TIME>$overtime||$record->getVar ( 'flag' )==1){
							$this->setMessage('0011','验证码已过期!');
						}else{
							if($this->P['seckey']==$record->getVar ( 'checkcode' )){
								if($this->db->edit($record->getVar ( 'vid' ),array('flag'=>1))){
									$this->db->init('users','uid','system');
									$this->P['info']['pass'] = $this->P['passwd'];
									if($this->db->edit($uid,$this->P['info'])){
										$this->setMessage('0000','修改成功');
									}else{
										$this->setMessage('0500','服务器内部错误!');
									}
								}else{
									$this->setMessage('0500','服务器内部错误!');
								}
							}else{
								$this->setMessage('0009','验证码错误!');
							}
						}
					}
				}else{
					$this->setMessage('0010','用户名和邮箱不匹配!');
				}
			}else{
				$this->setMessage('0002','用户不存在!');
			}
		}
	}
}
?>