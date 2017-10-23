<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class registerController extends chief_controller { 
	public $template_name = 'register'; 
	public $caching = false;
	public $theme_dir = false;
	
	public function main($params = array()){
		$dataObj = $this->model('register','system');
		$data = $dataObj->main($params);
		include_once(JIEQI_ROOT_PATH."/include/funsystem.php");
		if (is_weixin()) {
			header("location:".$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url']."/wxlogin/?jumpurl=".$params['jumpurl']);
			exit();
		}
		$this->display($data);
	}

	public function checkUser($params = array()){
		 $dataObj = $this->model('register','system');
		 $dataObj->checkUser($params);
	}
	
	public function checkEmail($params = array()){
		 $dataObj = $this->model('register','system');
		 $dataObj->checkEmail($params);
	}
	
}
 
?>