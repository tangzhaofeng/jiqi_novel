<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
define('API_URL',$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url'].'/sinalogin/login/');
class sinaloginController extends Controller { 
    public $theme_dir = false;
	public $template_name = 'bindlogin';
	public $appid = '3964779229';
	public $appkey = '294291997e3f3aeca779d72341681627';
	public $callback = API_URL;
	
	//登陆第三方平台
	public function main($params = array()){
	   header('Content-Type: text/html; charset=UTF-8');
	   $params['appid'] = $this->appid;
	   $params['appkey'] = $this->appkey;
       $params['callback'] = $this->callback ;
	   $params['clienturl'] = 'https://api.weibo.com/oauth2/authorize?'; //登陆微博平台的地址
	   $dataObj = $this->model('sinalogin');
	   $dataObj->main($params);
	}
	
	//登陆成功返回的地址
	public function login($params = array()){
	   $dataObj = $this->model('sinalogin');
	   $params['type'] = 'sina';
	   $params['appid'] = $this->appid;
	   $params['appkey'] = $this->appkey;
       $params['callback'] = $this->callback ;
	   $params['clienturl'] = 'https://api.weibo.com/oauth2/access_token'; //请求令牌的地址
	   
	   $data = $dataObj->login($params);
	   
	   $this->display($data);
	}

	//绑定已有账号并登陆
	function bindlogin($params)
	{
		$dataObj = $this->model('sinalogin');
		$dataObj->bindlogin($params);
	}
	
	//注册绑定并登陆
	function register ($params)
	{
		$dataObj = $this->model('sinalogin');
		$dataObj->register($params);
	}
	
	function jumpurl($params){
	    //print_r("isset:".isset($params['param'])." empty:".empty($params['param']));exit;
		if (!empty($params['param'])){
					echo "<script>parent.adtest('".$params['param']."');</script>";exit;
		}else{
			echo "<script>parent.loadheader();parent.layer.closeAll();</script>";exit;//parent.layer.close(parent.layer.getFrameIndex(window.name));
		}
			/*echo "<script>parent.loadheader();parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";exit;*/
	}
}
?>