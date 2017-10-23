<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
define('API_URL',$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url'].'/qqlogin/login/');
class qqloginController extends Controller { 

	public $theme_dir = false;
	public $template_name = 'bindlogin';
	public $appid = '101230128';
	public $appkey = 'bf8b0f7b4e3867b83af63f6174ae0821';
	public $callback = API_URL;
	public $caching = false;
	
	public function main($params = array()){
		$dataObj = $this->model('qqlogin');
		$params['appid'] = $this->appid;
	    $params['appkey'] = $this->appkey;
		$params['jumpurl'] = urldecode($params['jumpurl']);
        $params['callback'] = $this->callback;
		$dataObj->main($params);
	}
	
	//登陆成功返回的地址
	public function login($params = array()){
	   $dataObj = $this->model('qqlogin');
	   $params['type'] = 'qq';
	   $params['appid'] = $this->appid;
	   $params['appkey'] = $this->appkey;
       $params['callback'] = $this->callback ;
	   //提交数据
	   $data = $dataObj->login($params);
	   //print_r($data);exit; 
	   $this->display($data);
	}

	//绑定已有账号并登陆
	function bindlogin($params)
	{
		$dataObj = $this->model('sinalogin');
		if($this->submitcheck()) $dataObj->bindlogin($params);
		else $this->printfail();
	}
	
	/**
	 * 注册绑定并登陆
	 * <p>
	 * 前端要录入新用户的email信息，来完成qq信息的新用户注册
	 * @param unknown $params 
	 */
	function registerlogin ($params)
	{
		$dataObj = $this->model('qqlogin');
		if($this->submitcheck()) $dataObj->register($params);
		else $this->printfail();
	}
	
	function jumpurl($params){
	    //print_r("isset:".isset($params['param'])." empty:".empty($params['param']));exit;
		if (!empty($params['param'])){
// 			echo "<script>parent.adtest('".$params['param']."');<\/script>";exit;
			echo "<script>location.href='".$params['param']."';</script>";exit;
		}else{
			echo "<script>parent.loadheader();parent.layer.closeAll();</script>";exit;//parent.layer.close(parent.layer.getFrameIndex(window.name));
		}
			/*echo "<script>parent.loadheader();parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";exit;*/
	}
}
?>