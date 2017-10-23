<?php
/**
 * article业务类继承了老版本的JieqiPackage类
 * 
 * @copyright Copyright(c) 2014
 * @author chengyuan
 * @version 1.0
 */
class MyConnect extends JieqiObject {

    /**
	 * 默认构造器，实例$this->db
	 */
	function __construct() {
		
		if (! is_object ( $this->db )) {
			$this->db = Application::$_lib ['database'];
		}
	}
	
	function access_token($callback_url,$appid,$appkey,$code='',$url='https://graph.qq.com/oauth2.0/token?',$method='GET'){
		$_params=array(
			'grant_type'=>'authorization_code',
			'client_id'=>$appid,
			'client_secret'=>$appkey,
			'code'=>$code,
			'state'=>'',
			'redirect_uri'=>$callback_url
		);
// 		if (in_array(JIEQI_MODULE_NAME,array("3gwap"))) $url = 'https://graph.z.qq.com/moc2/token?';
		if ($method == 'GET'){
			$url = $url.http_build_query($_params);
			$result_str=$this->http($url);
			$json_r=array();
		    if($result_str!='') parse_str($result_str, $json_r);
		}else{
			$json_r = json_decode($this->http($url, http_build_query($_params), $method),true);
		}
		
		return $json_r;
	}
	
	function login_url($callback_url, $appid, $scope ,$url){
		$_params=array(
			'client_id'=>$appid,
			'state'=>JIEQI_SITE_KEY,
			'redirect_uri'=>$callback_url,
			'response_type'=>'code',
			'scope'=>$scope
		);
		if (in_array(JIEQI_MODULE_NAME,array("3gwap","3g","wap"))){
			$_params['display']='mobile';
			$_params['g_ut']=1;
		}
/*		echo $url.http_build_query($_params);
		exit;*/
		return $url.http_build_query($_params);
	}
	
	function loginClient($callback, $appid, $scope='', $url='https://graph.qq.com/oauth2.0/authorize?'){
	
		$login_url = $this->login_url($callback,$appid,$scope ,$url);
		header("Location:".$login_url);
  }
  function get_openid($access_token){
	$_params=array(
		'access_token'=>$access_token
	);
	$url='https://graph.qq.com/oauth2.0/me?'.http_build_query($_params);
	$result_str=$this->http($url);
	$json_r=array();
	if($result_str!=''){
		preg_match('/callback\(\s+(.*?)\s+\)/i', $result_str, $result_a);
		$json_r=json_decode($result_a[1], true);
	}
	return $json_r;
  }
  
  function http($url, $postfields='', $method='GET', $headers=array()){
		/*$ci=curl_init();
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
		if($method=='POST'){
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if($postfields!='')curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
		}
		$headers[]="User-Agent: qqPHP(piscdong.com)";
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $url);
		$response=curl_exec($ci);*/
        $response=file_get_contents($url);
		//curl_close($ci);
		return $response;
	}
	
	function api($url, $params, $method='GET'){
		
		
		
		//$_params['uid']=$params['openid'];
		//$_params['access_token']=$params['access_token'];
		//$_params['oauth_consumer_key']=$params['openid'];
		$params['format']='json';
		if($method=='GET'){
//		print_r($url.'?'.http_build_query($params));exit;
			$result_str=$this->http($url.'?'.http_build_query($params));
		}else{
			$result_str=$this->http($url, http_build_query($params), 'POST');
		}
		$result=array();
		if($result_str!='')$result=json_decode($result_str, true);
		return $result;
	}
	
	/***
	绑定账号并登陆
	*/
	function userlogin($params){
	global   $jieqiModules;
	//载入语言
	$this->addLang('system', 'users');
	$this->addConfig('system','configs');
	$jieqiLang['system'] = $this->getLang('system');
	$jieqiConfigs['system'] = $this->getConfig('system','configs');
	$params['username']=trim($params['username']);
	$users_handler =  $this->getUserObject();//查询用户是否存在
   
	//$jieqiConfigs['system']['checkcodelogin'] = 0;
	$this->db->init('connect', 'connectid', 'system');
	$this->db->setCriteria(new Criteria('openid', $params['openid']));
	$this->db->queryObjects($this->db->criteria);
	$connObj = $this->db->getObject();
	if ($connObj) $this->printfail('该账号已经被绑定，请换一个重试');
	include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
	$islogin=jieqi_logincheck($params['username'], $params['password'], $params['checkcode'], 1,false,false);
	switch($islogin){
	        case '0':
			if($params['username']==$params['password']){
				$this->printfail($jieqiLang['system']['password_easy']);
			} 

			if(!$userobj=$users_handler->get($_SESSION['jieqiUserId'])) {
				$this->printfail($jieqiLang['system']['no_this_user']);
			}
			else{
				 //绑定用户
			     $conctobj['type'] = $params['type'];
				 $conctobj['accesstoken']=$params['accesstoken'];
				 $conctobj['openid']=$params['openid'];
				 $conctobj['bindtime'] = JIEQI_NOW_TIME;
				 $conctobj['uid'] = $_SESSION['jieqiUserId'];
				 $this->db->add($conctobj);
			}
			
		/*	echo "<script>parent.loadheader();parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";exit;*/
			/*if (isset($params['jumpurl'])){
					echo "<script>parent.adtest('".$params['jumpurl']."');parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";exit;
				}else{
					echo "<script>parent.loadheader();parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";exit;
			}*/
				
		    include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
		    
			if (isset($params['jumpurl'])){
				$params['url'] = $jieqiModules[JIEQI_MODULE_NAME]['url'].'/qqlogin/jumpurl?param='.$params['jumpurl'];
			}else{
			
				$params['url'] = $jieqiModules[JIEQI_MODULE_NAME]['url'].'/qqlogin/jumpurl';
			}
			unset($_SESSION['openid_'.$params['type']]);
			jieqi_logindo($params['url']);
			//jieqi_logindo($params['jumpurl'])
			
			break;
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

function userregister($params){
	//$this->printfail($_SESSION['openid']['figureurl_2']);
	//$params['username'] = urldecode($params['username']);
	global   $jieqiModules;
	//载入语言
	$this->addLang('system', 'users');
	$this->addConfig('system','configs');
	$jieqiLang['system'] = $this->getLang('system');
	$jieqiConfigs['system'] = $this->getConfig('system','configs');

	//include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
	include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
    $errtext='';
	$users_handler = $this->getUserObject();

    $this->db->init('connect', 'connectid', 'system');
	$this->db->setCriteria(new Criteria('openid', $params['openid']));
	$this->db->queryObjects($this->db->criteria);
	$connObj = $this->db->getObject();
    if (is_object($contObj)) {
		include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
		$jieqiUsers = $users_handler->get($contObj->getVar('uid','n'));
		$islogin = jieqi_loginprocess($jieqiUsers, 100000000);
		 include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
		 if (isset($params['jumpurl'])){
			$params['url'] = JIEQI_LOCAL_URL.'/qqlogin/jumpurl?param='.$params['jumpurl'];
		 }else{
		
			$params['url'] = JIEQI_LOCAL_URL.'/qqlogin/jumpurl';
		 }
		 unset($_SESSION['openid_'.$params['type']]);
		 if(in_array(JIEQI_MODULE_NAME,array('3gwap','wap','3g'))){
		    header('Location: '.$params['url']);exit;
		 }else jieqi_logindo($params['url']);
	}

	//$params['username'] = urldecode($params['username']);
	$params['password'] = trim($params['password']);
	//$this->printfail($jieqiLang['system']['register_failure'].'gg');
    if($params['username'] && $users_handler->getByname($params['username'], 3) != false) $params['username'] = $params['username'].jieqi_random(6);
    if(!$params['username']) $params['username'] = jieqi_random(8);
	$newUser['siteid'] = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];
	$newUser['uname'] = $params['username'];
	$newUser['name'] =$params['username'];
	//$newUser['pass'] =$users_handler->encryptPass($params['password']);
	$newUser['groupid'] =JIEQI_GROUP_USER;
	$newUser['regdate'] =JIEQI_NOW_TIME;
	$newUser['initial'] =jieqi_getinitial($params['username']);
	$newUser['sex'] = $params['sex'];
	$newUser['email'] = $params['email'];
	$newUser['url'] =$params['url'];
	$newUser['avatar'] =0;
	$newUser['workid'] =$params['workid'];
	$newUser['qq'] = $params['qq'];
	$newUser['icq']= '';
	$newUser['msn'] =$params['msn'];
	$newUser['mobile'] ='';
	$newUser['sign'] =$params['sign'];
	$newUser['intro'] =$params['intro'];
	$userset['logindate'] = date('Y-m-d H:i:s',JIEQI_NOW_TIME);
	$userset['lastip'] = $this->getIp();
	$userset['source'] = $_SESSION['SOURCE_SITE'] ? $_SESSION['SOURCE_SITE'] : $_COOKIE['SOURCE_SITE'];
	$newUser['setting']= serialize($userset);
	$newUser['badges']= '';
	$newUser['lastlogin']= JIEQI_NOW_TIME;
	$newUser['showsign'] =0;
	$newUser['viewemail']= 0;
	$newUser['notifymode']= 0;
	$newUser['adminemail']= 0;
	$newUser['monthscore'] =0;
	$newUser['experience'] =$jieqiConfigs['system']['scoreregister'];
	$newUser['score'] =$jieqiConfigs['system']['scoreregister'];
	$newUser['egold'] =0;
	$newUser['esilver']= 0;
	$newUser['credit']=0;
	$newUser['goodnum'] =0;
	$newUser['badnum']= 0;
	$newUser['isvip'] =0;
	$newUser['overtime'] =0;
	$newUser['state']=0;
	$newUser['source'] =$_SESSION['SOURCE_SITE'] ? $_SESSION['SOURCE_SITE'] : $_COOKIE['SOURCE_SITE'];

	$this->db->init('users','uid','system');
	if (!$uid = $this->db->add($newUser)) $this->printfail($jieqiLang['system']['register_failure']);
	else {
		/* $basedir=jieqi_uploadpath($jieqiConfigs['system']['avatardir'], 'system').jieqi_getsubdir($uid);
		 if(jieqi_checkdir($basedir,true)){
			//$this->printfail($basedir);
			$file=$basedir.'/'.$uid.'.jpg';
			$file1=$basedir.'/'.$uid.'l.jpg';
			$file2=$basedir.'/'.$uid.'m.jpg';
			$file3=$basedir.'/'.$uid.'s.jpg';
			
			jieqi_downfile($_SESSION['openid']['figureurl']);
			$src=file_get_contents($_SESSION['openid']['figureurl_2']);
			
			//$pic1=base64_decode($param['pic1']);
			//$pic2=base64_decode($param['pic2']);
			//$pic3=base64_decode($param['pic3']);
			if($src) {
				jieqi_writefile($file,$src);
				jieqi_writefile($file1,$src);
				jieqi_writefile($file2,file_get_contents($_SESSION['openid']['figureurl_1']));
				jieqi_writefile($file3,file_get_contents($_SESSION['openid']['figureurl']));
				
			}
			$jieqiUsers = $users_handler->get($uid);
			$jieqiUsers->unsetNew();
			$jieqiUsers->setVar('avatar',2);
			if (!$users_handler->insert($jieqiUsers)){
				$this->printfail($jieqiLang['system']['avatar_set_failure']);
			}
			$_SESSION['jieqiUserAvatar'] = 2;
		 }*/
		 $this->db->init('connect','connectid','system');
		 $userobj['type'] = $params['type'];
		 $userobj['accesstoken']=$params['accesstoken'];
		 $userobj['openid']=$params['openid'];
		 $userobj['bindtime'] = JIEQI_NOW_TIME;
		 $userobj['uid'] = $uid;
		 $this->db->add($userobj);
		//自动登录
		//记录注册?奔銲P
		if($jieqiConfigs['system']['regtimelimit']>0){
			$this->db->init('ip','registerip','system');
			$sql="DELETE FROM ".jieqi_dbprefix('system_registerip')." WHERE regtime<".(JIEQI_NOW_TIME - ($jieqiConfigs['system']['regtimelimit'] > 72 ? $jieqiConfigs['system']['regtimelimit'] : 72) * 3600);
			$this->db->execute($sql);
			$sql="INSERT INTO ".jieqi_dbprefix('system_registerip')." (ip, regtime, count) VALUES ('".jieqi_dbslashes($this->getIp())."', '".JIEQI_NOW_TIME."', '0')";
			$this->db->execute($sql);
		}

		//更新在线用户表
		include_once(JIEQI_ROOT_PATH.'/class/online.php');
		$online_handler =& JieqiOnlineHandler::getInstance('JieqiOnlineHandler');
		include_once(JIEQI_ROOT_PATH.'/include/visitorinfo.php');
		$online = $online_handler->create();
		$this->db->init('users','uid','system');
		$this->db->setCriteria(new Criteria('uid', $uid));
		$newUser = $this->db->get($this->db->criteria);
		$online->setVar('uid', $newUser->getVar('uid', 'n'));
		$online->setVar('siteid', JIEQI_SITE_ID);
		$online->setVar('sid', session_id());
		$online->setVar('uname', $newUser->getVar('uname', 'n'));
		$tmpvar = strlen($newUser->getVar('name', 'n')) > 0 ? $newUser->getVar('name', 'n') : $newUser->getVar('uname', 'n');
		$online->setVar('name', $tmpvar);
		$online->setVar('pass', $newUser->getVar('pass', 'n'));
		$online->setVar('email', $newUser->getVar('email', 'n'));
		$online->setVar('groupid', $newUser->getVar('groupid', 'n'));
		$tmpvar=JIEQI_NOW_TIME;
		$online->setVar('logintime', $tmpvar);
		$online->setVar('updatetime', $tmpvar);
		$online->setVar('operate', '');
		$tmpvar=VisitorInfo::getIp();
		$online->setVar('ip', $tmpvar);
		$online->setVar('browser', VisitorInfo::getBrowser());
		$online->setVar('os', VisitorInfo::getOS());
		$location=VisitorInfo::getIpLocation($tmpvar);
		if(JIEQI_SYSTEM_CHARSET == 'big5'){
			include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
			$location=jieqi_gb2big5($location);
		}
		$online->setVar('location', $location);
		$online->setVar('state', '0');
		$online->setVar('flag', '0');
		$online_handler->insert($online);

		//设置SESSION
		jieqi_setusersession($newUser);

		//设置COOKIE
		$jieqi_user_info['jieqiUserId']=$_SESSION['jieqiUserId'];
		$jieqi_user_info['jieqiUserName']=$_SESSION['jieqiUserName'];
		$jieqi_user_info['jieqiUserGroup']=$_SESSION['jieqiUserGroup'];
		///
		include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
		$jieqi_user_info['jieqiUserVip']=$_SESSION['jieqiUserVip'];
		if(JIEQI_SYSTEM_CHARSET == 'gbk'){
			$jieqi_user_info['jieqiUserHonor_un']=jieqi_gb2unicode($_SESSION['jieqiUserHonor']);
			$jieqi_user_info['jieqiUserGroupName_un']=jieqi_gb2unicode($jieqiGroups[$_SESSION['jieqiUserGroup']]);
		}else{
			$jieqi_user_info['jieqiUserHonor_un']=jieqi_big52unicode($_SESSION['jieqiUserHonor']);
			$jieqi_user_info['jieqiUserGroupName_un']=jieqi_gb2unicode($jieqiGroups[$_SESSION['jieqiUserGroup']]);
		}
		///
		include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
		if(JIEQI_SYSTEM_CHARSET == 'gbk') $jieqi_user_info['jieqiUserName_un']=jieqi_gb2unicode($_SESSION['jieqiUserName']);
		else $jieqi_user_info['jieqiUserName_un']=jieqi_big52unicode($_SESSION['jieqiUserName']);
		$jieqi_user_info['jieqiUserLogin']=JIEQI_NOW_TIME;
		$cookietime=JIEQI_NOW_TIME+3600;
		@setcookie('jieqiUserInfo', jieqi_sarytostr($jieqi_user_info), $cookietime, '/',  JIEQI_COOKIE_DOMAIN, 0);
		$jieqi_visit_info['jieqiUserLogin']=$jieqi_user_info['jieqiUserLogin'];
		$jieqi_visit_info['jieqiUserId']=$jieqi_user_info['jieqiUserId'];
		@setcookie('jieqiVisitInfo', jieqi_sarytostr($jieqi_visit_info), JIEQI_NOW_TIME+99999999, '/',  JIEQI_COOKIE_DOMAIN, 0);

		//推广积分
		if(JIEQI_PROMOTION_REGISTER > 0 && !empty($_COOKIE['jieqiPromotion'])){
			$users_handler->changeCredit(intval($_COOKIE['jieqiPromotion']), intval(JIEQI_PROMOTION_REGISTER), true);
			setcookie('jieqiPromotion', '', 0, '/', JIEQI_COOKIE_DOMAIN, 0);
		}

		 include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
		 if (isset($params['jumpurl'])){
			$params['url'] = JIEQI_LOCAL_URL.'/qqlogin/jumpurl?param='.$params['jumpurl'];
		 }else{
		
			$params['url'] = JIEQI_LOCAL_URL.'/qqlogin/jumpurl';
		 }
		 unset($_SESSION['openid_'.$params['type']]);
		 if(in_array(JIEQI_MODULE_NAME,array('3gwap','wap','3g'))){
		    header('Location: '.$params['url']);exit;
		 }else jieqi_logindo($params['url']);
	}
  }
}
?>