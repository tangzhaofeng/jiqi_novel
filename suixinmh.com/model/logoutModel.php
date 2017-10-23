<?php 
/** 
 * 退出登录 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class logoutModel extends Model{ 
	/**
	 * 退出登录
	 * <p>
	 * 如果jumpurl空，则解析refer_url的domain进行退出跳转
	 */
	public function logout(){
		global $jieqiConfigs, $jieqiLang, $jieqi_image_type;
		$_REQUEST = $this->getRequest();
		define('JIEQI_ADMIN_LOGIN', 1);
		$this->addLang('system', 'users');
		include_once(JIEQI_ROOT_PATH.'/include/dologout.php');
		jieqi_dologout();
		//if (empty($_REQUEST['jumpurl'])) $_REQUEST['jumpurl']=empty($_REQUEST['forward']) ? JIEQI_LOCAL_URL.'/' : $_REQUEST['forward'];
		
		if(empty($_REQUEST['jumpurl'])){
		    $url = parse_url(JIEQI_REFER_URL);
		    $_REQUEST['jumpurl'] = 'http://'.$url['host'];
		}//echo $_REQUEST['jumpurl'];exit;
		header('location:'.$_REQUEST['jumpurl']);
	} 
} 
?>