<?php 
/**
 *
 *
 *
 */
 class wxloginModel  extends Model{
	//wx授权api接口.按需调用
    private $scope = "get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo";
	private $connectLib = NULL;
    public function main($params){	
		if (! is_object ( $this->connectLib )) {
			$this->connectLib = $this->load( 'weixin', 'system','connect' );
		}
		//print_r($params);exit;
		//session_start();
		unset($_SESSION['openid']);
		$_SESSION['jumpurl'] = $params['jumpurl'];
		$this->connectLib->auth($params['appid'], $params['callback']);
    }
  
    //登陆成功返回的地址
    function login($params){
	    global $jieqiModules;
    	//login
    	//print_r("login");exit;
		$params['jumpurl'] = $_SESSION['jumpurl'];
		//exit(22);
//        echo "jumpurl=".$params['jumpurl'];
//        exit();
	   //print_r($params);exit;//Array ( [access_token] => E1701B97458F87A90736C466DE1828FC [expires_in] => 7776000 [refresh_token] => A01F15710E2EBFA60CF3C98F95BEE6D0 ) 
	    if (! is_object ( $this->connectLib )) {
			$this->connectLib = $this->load( 'weixin', 'system','connect');
		}
		
		//echo("dd");
		//print_r("login");exit;
        $openid = $this->connectLib->access_token( $params['appid'], $params['appkey'],$params['code']);





		
		
		//判断是否已经绑定账号否则直接登陆
		$users_handler =  $this->getUserObject();//查询用户是否存在
		//$jieqiUsers = $users_handler->get($params['uid']);
		$this->db->init('connect', 'connectid', 'system');
		$_openid = $openid->openid;
		if(!$_openid){
		    //header('location:'.$jieqiModules[JIEQI_MODULE_NAME]['url'].'/qqlogin/?jumpurl='.$params['jumpurl']);
			$this->jumppage($jieqiModules[JIEQI_MODULE_NAME]['url'].'/qqlogin/?jumpurl='.$params['jumpurl'], LANG_DO_SUCCESS,'获取账号信息失败。系统将自动重试。<br /> 或者 <a href="'.$params['jumpurl'].'">点击这里返回上一页</a><br />');
			exit;
		} 
		unset($_SESSION['jumpurl']);
		$this->db->setCriteria(new Criteria('openid',$_openid,'='));//$access_token['uid']
		$this->db->queryObjects($this->db->criteria);
		$contObj = $this->db->getObject();
		if (is_object($contObj)) {
			include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
			
			$jieqiUsers = $users_handler->get($contObj->getVar('uid','n'));
			//print_r($jieqiUsers->getVar('pass', 'n'));exit;
			$islogin = jieqi_loginprocess($jieqiUsers, 100000000);
			if($islogin==0){
				if($contObj->getVar('bindtime')+90*86400<=time()){
					$contObj->setVar('bindtime',time());
					$this->db->edit($contObj->getVar('connectid','n'),$contObj);
				}
				
				
				
				if (in_array(JIEQI_MODULE_NAME,array("3gwap","3g","wap"))){
					include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
					if (!$params['jumpurl'] || $params['jumpurl']==JIEQI_LOCAL_URL){
						$params['jumpurl'] = '/';
					}
					header('location:'.$params['jumpurl']);exit;//jieqi_logindo($params['jumpurl'],false); 
				}else{
					if ($params['jumpurl']){
						echo "<script>parent.adtest('".$params['jumpurl']."');</script>";exit;
					}else{
						echo "<script>parent.loadheader();parent.layer.closeAll();</script>";exit;//parent.layer.close(parent.layer.getFrameIndex(window.name));
					}
				}
				//include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
				//if(strpos($params['jumpurl'],'controller=sinalogin')>0 || strpos($params['jumpurl'],'controller=qqlogin')>0) $params['jumpurl'] = JIEQI_LOCAL_URL.'/';
				//print_r( $params['jumpurl']);exit;
				/*if ($params['jumpurl']=='11'){
				     //include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
					//jieqi_logindo( $params['jumpurl'],true);
					//header('location:'.JIEQI_LOCAL_URL);
					echo "<script>parent.location.href=".JIEQI_LOCAL_URL." parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";exit;
				}else{
					echo "<script>parent.loadheader();parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";exit;
				}*/
				//print_r($_SESSION);exit;
				//echo $params['jumpurl'];exit;
				
				//$params['jumpurl'] = JIEQI_LOCAL_URL.'/';
				//jieqi_logindo($params['jumpurl'],true);
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
		}else{
			
			//获取用户基本资料
			if (!isset($_SESSION['openid_weixin']))
			{
				
				$params['openid'] = $openid->openid;
				$params['access_token'] = $openid->access_token;
				$userinfo = $this->connectLib->user_info($openid->openid,$openid->access_token);
				$data['gender'] = $userinfo->sex;
				$data['nickname'] = iconv('utf-8','gb2312',$userinfo->nickname);
				$data['username'] = $data["nickname"];
//				$data['figureurl'] = $arr["figureurl"];
//				$data['figureurl_1'] = $arr["figureurl_1"];
//				$data['figureurl_2'] = $arr["figureurl_2"];
	
				/*if(!$users_handler->getByname($data['nickname'], 3)){
					//define('autoregister',true);
					$data['name'] = $data['nickname'];
				}*/
				$data['url_login'] = $jieqiModules[JIEQI_MODULE_NAME]['url'].'/wxlogin/bindlogin';
				$_params = array(
					'openid'=>$params['openid'],
					'username'=>$data['nickname'],
					'accesstoken' => $params['access_token'],
					'type'=>'qq'
				
				);
				$data['url_register'] = $jieqiModules[JIEQI_MODULE_NAME]['url'].'/wxlogin/registerlogin';//?'.http_build_query($_params);
				//print_r($data['url_register'] );
				$data['type'] = $params['type'];
				$data['openid'] = $openid->openid;
				$data['access_token'] = $data['accesstoken'] = $openid->access_token;
				$data['jumpurl'] = $params['jumpurl'];
				$_SESSION['openid_weixin'] = $data;
				//print_r($_SESSION);exit;
			}else{
				$data = $_SESSION['openid_weixin'];
			}
			if($data['username']) $this->register($data);
			return $data;
		}	
	}

	//注册绑定并登陆
	function register ($params)
	{
		//邮箱验证
		//2015-4-3 chengyuan add
		//$registerModel = $this->model('register','system');
		//$registerModel->checkEmail($params,true);//邮箱参数名：email
		
		if (! is_object ( $this->connectLib )) {
			$this->connectLib = $this->load( 'weixin', 'system','connect' );
		}
		$this->connectLib->userregister($params);
	}
	
	//绑定已有账号并登陆
	function bindlogin($params)
	{
		if (! is_object ( $this->connectLib )) {
			$this->connectLib = $this->load( 'weixin', 'system','connect' );
		}

		$this->connectLib->userlogin($params);
	}
 
}
 
 ?>