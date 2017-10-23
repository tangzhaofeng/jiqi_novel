<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class loginController extends chief_controller { 
	public $template_name = 'login'; 
	public $caching = false;
	public $theme_dir = false;

	public function main($params = array()){
		//header('Content-Type:text/html;charset=utf-8');
		if($this->checklogin(true)) {
		     header('location:'.$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url']);exit;
		}
		include_once(JIEQI_ROOT_PATH."/include/funsystem.php");
		if (is_weixin()) {
			header("location:".$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url']."/wxlogin/?jumpurl=".$params['jumpurl']);
			exit();
		}
		$dataObj = $this->model('login','system');
		$data = $dataObj->main($params);
        $data['ujumpurl'] = urlencode($data['jumpurl']);
		$this->display($data);
	}
	public function logined($params = array()){
	    $this->theme_dir = false;
		$auth = $this->getAuth();
		$dataObj = $this->model('login','system');
		if (empty($auth['uid'])){
			$this->display('', 'loginheader');
		}else{
		    $params['uid'] = $auth['uid'];
		    if($data = $dataObj->msgHead($params)){
	           $this->display($data, 'logined');
			}else{
			   $this->display($data, 'loginheader');
			}
		}	
	}
	//vipµÇÂ¼Ìõ
	public function viplogined($params = array()){
	    $this->theme_dir = false;
		$auth = $this->getAuth();
		if (empty($auth['uid'])){
			$this->display('', 'loginvip');
		}else{
			$this->display($data, 'viplogined');
		}
	}
}
?>