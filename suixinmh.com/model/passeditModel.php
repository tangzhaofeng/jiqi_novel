<?php 
/** 
 * 密码修改
 * @copyright   Copyright(c) 2014 
 * @author      gaoli
 * @version     1.0 
 */ 
class passeditModel extends Model{
	/**
	 * 保存修改
	 * @param unknown $param
	 */
	public function passedit($param){
		$auth = $this->getAuth ();
		$users_handler = $this->getUserObject ();
		$jieqiUsers = $users_handler->get ( $auth ['uid'] );
		global $jieqiConfigs, $jieqiLang;
		jieqi_loadlang ( 'users', JIEQI_MODULE_NAME );
		$param ['oldpass'] = trim ( $param ['oldpass'] );
		$param ['newpass'] = trim ( $param ['newpass'] );
		$param ['repass'] = trim ( $param ['repass'] );
		$errtext = '';
		//exit;
		// 检查密码
		if ($param ['newpass'] != $param ['repass'])
			$errtext .= $jieqiLang ['system'] ['password_not_equal'] . '<br />';
		elseif (strlen ( $param ['newpass'] ) == 0)
			$errtext .= $jieqiLang ['system'] ['need_pass_repass'] . '<br />';
		elseif ($jieqiUsers->getVar('pass') && $jieqiUsers->getVar ( 'pass', 'n' ) != $users_handler->encryptPass ( $param ['oldpass'] ))
			$errtext .= $jieqiLang ['system'] ['error_old_pass'] . '<br />';
			
			// 更新密码
		if (empty ( $errtext )) {
			$jieqiUsers->unsetNew (); // echo $users_handler->encryptPass($param['newpass']);exit;
			$jieqiUsers->setVar ( 'pass', $users_handler->encryptPass ( $param ['newpass'] ) );
			if (! $users_handler->insert ( $jieqiUsers ))
				$this->printfail ( $jieqiLang ['system'] ['pass_edit_failure'] );
			else {
				// 修改成功，跳转至用户信息
				// include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
				// jieqi_passeditdo(JIEQI_URL.'/userdetail.php');
				$this->jumppage ( $this->geturl ( JIEQI_MODULE_NAME, 'userhub'), LANG_DO_SUCCESS, $jieqiLang ['system'] ['pass_edit_success'] );
			}
		} else {
			$this->printfail ( $errtext );
		}
	}
	
	public function passeditView($param){
		global $jieqiConfigs, $jieqiLang;
		jieqi_loadlang('users', JIEQI_MODULE_NAME);
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
		if(!$jieqiUsers) $this->printfail(LANG_NO_USER);
		return array('url_passedit' => $this->geturl('system','passedit'),'pass'=>$jieqiUsers->getVar('pass'));
	}
} 
?>