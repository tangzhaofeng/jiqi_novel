<?php
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class loginController extends Controller { 
	public $template_name = 'login'; 
	public $caching = false;

	public function main($params = array()){
		//header('Content-Type:text/html;charset=gbk');
		$dataObj = $this->model('login');
		$data = $dataObj->main($params);
		$this->display($data);
	}
	public function logined($params = array()){
	    $this->theme_dir = false;
		$auth = $this->getAuth();
		$dataObj = $this->model('login');
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