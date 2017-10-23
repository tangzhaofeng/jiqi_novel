<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
define('API_URL',$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url'].'/sinalogin/login/');
class sinaloginController extends Controller {
	public $caching = false;
    public $theme_dir = false;
	public $template_name = 'bindlogin';
	public $appid = '174919741';
	public $appkey = 'a3ba55d43a7cf0d2c852a93dec7208cd';
	public $callback = API_URL;//QQ_URL; //
	
	//登陆第三方平台
	public function main($params = array()){//echo 'agggggg';exit;
	   header('Content-Type: text/html; charset=UTF-8');
	   $params['appid'] = $this->appid;
	   $params['appkey'] = $this->appkey;
       $params['callback'] = $this->callback ;
	   $params['clienturl'] = 'https://api.weibo.com/oauth2/authorize?'; //登陆微博平台的地址
		$params['jumpurl'] = $params['jumpurl']?urldecode($params['jumpurl']):JIEQI_REFER_URL;
		$params['jumpurl'] = $params['jumpurl']?$params['jumpurl']:'/';
        $_SESSION['jumpurl'] = $params['jumpurl'];
	   $dataObj = $this->model('sinalogin', 'system', 'connect');
	   $dataObj->main($params);
	}
	
	//登陆成功返回的地址
	public function login($params = array()){
	   $dataObj = $this->model('sinalogin', 'system', 'connect');
	   $params['type'] = 'sina';
	   $params['appid'] = $this->appid;
	   $params['appkey'] = $this->appkey;
       $params['callback'] = $this->callback ;
	   $params['clienturl'] = 'https://api.weibo.com/oauth2/access_token'; //请求令牌的地址
//	  $params['clienturl'] = 'https://api.weibo.com/oauth2/get_token_info'; //请求令牌的地址
	   $params['jumpurl'] = $_SESSION['jumpurl'];
	   $data = $dataObj->login($params);
	   //$data['url_login'] = 'http://3g.shuhai.com/sinalogin/bindlogin';
	   $this->display($data);
	}
	//绑定已有账号并登陆
	function bindlogin($params)
	{
		$dataObj = $this->model('sinalogin', 'system', 'connect');
		$dataObj->bindlogin($params);
	}
	
	//注册绑定并登陆
	function register ($params)
	{
		$dataObj = $this->model('sinalogin', 'system', 'connect');
		$dataObj->register($params);
	}
	
}
?>