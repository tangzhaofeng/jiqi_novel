<?php
/** 
 * 测试模型 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class loginModel extends Model{


	function main($params = array()){
	    //载入语言
		$this->addLang('system', 'users');
		$this->addConfig('system','configs');
		$jieqiLang['system'] = $this->getLang('system');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		
		if (empty($params['jumpurl'])){
		   $params['jumpurl'] = empty($_SERVER['HTTP_REFERER'])? JIEQI_LOCAL_URL :$_SERVER['HTTP_REFERER'];
		}
	    //提交数据
		if(!$params['formjs']){
			if($this->submitcheck()){
				 $this->loginDo($params);
			}
		}else{
		    if($params['formjs'] && get_host(JIEQI_REFER_URL)==get_host(JIEQI_HTTP_HOST)){
			     $this->loginDo($params);
			}else  $this->printfail(LANG_ERROR_PARAMETER);
		}
		
		$data = array();
		
		if (!empty($params['jumpurl'])) {
			$data['url_login'] = $this->geturl('system', 'login', 'method=main', 'do=submit&jumpurl='.($params['jumpurl']));
		}elseif (!empty($params['forward'])) {
			$data['url_login'] = $this->geturl('system', 'login', 'method=main', 'do=submit&jumpurl='.urlencode($params['forward']));
		}else{
			$data['url_login'] = $this->geturl('system', 'login', 'method=main', 'do=submit');
		}
		$data['jumpurl'] = $params['jumpurl'];
		
		if(!empty($jieqiConfigs['system']['checkcodelogin'])) $data['show_checkcode'] = 1;
		else $data['show_checkcode'] = 0;
		$data['url_checkcode'] = JIEQI_USER_URL.'/checkcode.php';
		return $data;	
	}
 

	function loginDo($params = array()){
		global $jieqiLang, $jieqiConfigs;
		$params['username'] = urldecode($params['username']);
		$params['password'] = urldecode($params['password']);
		$params['host'] = urldecode($params['host']);
		if(strstr($params['host'],'wen') != false){
			$encode = mb_detect_encoding($params['username'],array('UTF-8'));
			if($encode == "UTF-8"){
				$params['username'] = iconv("UTF-8","GBK",trim($params['username']));
			} 
		}
		include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
		if(isset($params['usecookie']) && is_numeric($params['usecookie'])) $params['usecookie']=intval($params['usecookie']);
		else $params['usecookie']=0;
		if(empty($params['checkcode'])) $params['checkcode']='';
		$islogin=jieqi_logincheck($params['username'],$params['password'],$params['checkcode'],$params['usecookie']);
		if($islogin==0){
			include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
			if(strpos($params['jumpurl'],'controller=login')>0 || strpos($params['jumpurl'],'controller=register')>0) $params['jumpurl'] = JIEQI_LOCAL_URL.'/';
			//if($params['wap_co']) exit(urldecode(urldecode('http%253A%252F%252Fwap2.shuhai.com%252Fhuodong%252Fvote%252F1162.html')));
			jieqi_logindo(urldecode($params['jumpurl']));
		}else{
	//返回 0 正常, -1 用户名为空 -2 密码为空 -3 用户名或者密码为空 
	//-4 用户名不存在 -5 密码错误 -6 用户名或密码错误 -7 校验码错误 -8 帐号已经有人登陆
			switch($islogin){
				case -1:
				$this->printfail($jieqiLang['system']['need_username']);
				break;
				case -2:
				$this->printfail($jieqiLang['system']['need_password']);
				break;
				case -3:
				$this->printfail($jieqiLang['system']['need_userpass']);
				break;
				case -4:
				$this->printfail($jieqiLang['system']['no_this_user']);
				break;
				case -5:
				$this->printfail($jieqiLang['system']['error_password']);
				break;
				case -6:
				$this->printfail($jieqiLang['system']['error_userpass']);
				break;
				case -7:
				$this->printfail($jieqiLang['system']['error_checkcode']);
				break;
				case -8:
				$this->printfail($jieqiLang['system']['other_has_login']);
				break;
				case -9:
				$this->printfail($jieqiLang['system']['user_has_denied']);
				break;
				default:
				$this->printfail($jieqiLang['system']['login_failure']);
				break;
			}
		}
	}
	
	function msgHead($params = array()){
		if (!empty($params['uid'])){
			$users_handler =  $this->getUserObject();//查询用户是否存在
	   		if($jieqiUsers = $users_handler->get($params['uid'])){
				$userset=unserialize($jieqiUsers->getVar('setting','n'));
				$data['jieqi_messagenum'] = intval($userset['jieqiNewFriend']) + intval($_SESSION['jieqiNewMessage']) + intval($userset['jieqiNewTongZhi']);
				$data['jieqiNewFriend'] = $userset['jieqiNewFriend'];
				$data['jieqiNewMessage'] = $_SESSION['jieqiNewMessage'];
				$data['jieqiNewTongZhi'] = $userset['jieqiNewTongZhi'];
				$data['uid'] = $params['uid'];
				$users_handler->saveToSession($jieqiUsers);
				setcookie('refresh', JIEQI_NOW_TIME, $cookietime, '/',  JIEQI_COOKIE_DOMAIN, 0);
				return $data;
			}else{
			    return false;
			}
		}
	}
}
?>