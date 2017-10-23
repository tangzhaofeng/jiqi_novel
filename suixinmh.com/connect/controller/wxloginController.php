<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
define('API_URL',$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url'].'/wxlogin/login/');
class wxloginController extends Controller {

	public $theme_dir = false;
	public $template_name = 'bindlogin';
	public $appid = 'wx73a2e606e8fae039';
	public $appkey = '9d1a5cda72e66b811d84711418ac32fb';
	public $callback = API_URL;
	public $caching = false;
	
	public function main($params = array()){
		$dataObj = $this->model('wxlogin','system','connect');
		//$dataObj = $this->model('qqlogin');
		$params['appid'] = $this->appid;
	    $params['appkey'] = $this->appkey;
		$params['jumpurl'] = $params['jumpurl']?urldecode($params['jumpurl']):JIEQI_REFER_URL;
		$params['jumpurl'] = $params['jumpurl']?$params['jumpurl']:'/';
        $params['callback'] = $this->callback;
        $_SESSION['jumpurl'] = $params['jumpurl'];
        
		$dataObj->main($params);
	}
	
	//登陆第三方平台
	/*public function main($params = array()){
	   header('Content-Type: text/html; charset=UTF-8');
	   $params['appid'] = $this->appid;
	   $params['appkey'] = $this->appkey;
       $params['callback'] = $this->callback ;
	   $params['clienturl'] = 'https://api.weibo.com/oauth2/authorize?'; //登陆微博平台的地址
	   $dataObj = $this->model('sinalogin');
	   $dataObj->main($params);
	}*/
	
	//登陆成功返回的地址
	public function login($params = array()){

	   $dataObj = $this->model('wxlogin','system','connect');
	   //$dataObj = $this->model('qqlogin');
	   $params['type'] = 'weixin';
	   $params['appid'] = $this->appid;
	   $params['appkey'] = $this->appkey;
	   $params['jumpurl'] = $params['jumpurl']?urldecode($params['jumpurl']):JIEQI_REFER_URL;
       $params['callback'] = $this->callback ;
	   //提交数据
	   $data = $dataObj->login($params);
	   //print_r($data);exit; 
	   
	   $this->display($data);
	}

	//绑定已有账号并登陆
	function bindlogin($params)
	{
		$dataObj = $this->model('wxlogin','system','connect');
		//$dataObj = $this->model('qqlogin');
		if($this->submitcheck()) $dataObj->bindlogin($params);
		else $this->printfail();
	}
	
	//注册绑定并登陆
	function registerlogin ($params)
	{
		$dataObj = $this->model('wxlogin','system','connect');
		//$dataObj = $this->model('qqlogin');
		if($this->submitcheck()) $dataObj->register($params);
		else $this->printfail();
	}
	
	function jumpurl($params){
	    //print_r("isset:".isset($params['param'])." empty:".empty($params['param']));exit;
		if (!empty($params['param'])){
			//location.href='{$params['param']}'
			echo "<script>location.href='".$params['param']."';</script>";exit;
		}else{
			echo "<script>parent.loadheader();parent.layer.closeAll();</script>";exit;//parent.layer.close(parent.layer.getFrameIndex(window.name));
		}
			/*echo "<script>parent.loadheader();parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";exit;*/
	}
}
?>