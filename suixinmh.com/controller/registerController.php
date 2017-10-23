<?php 
class registerController extends Controller { 
	public $template_name = 'register'; 
	public $caching = false;
	public function main($params = array()){
		$dataObj = $this->model('register');
		/*echo $params['ac']; 
		if($params['ac']){
			$dataObj->register();
		}*/
		//$dataObj->register();
		$data = $dataObj->main($params);
		$this->display($data);
	}

	public function checkUser($params = array()){
	     /*ob_start();
		 print_r($params);
		 $c = ob_get_contents();
		 ob_end_clean();
		 jieqi_writefile('check.txt',$c);*/
		 $dataObj = $this->model('register');
		 $dataObj->checkUser($params);
	}
	
	public function checkEmail($params = array()){
		 $dataObj = $this->model('register');
		 $dataObj->checkEmail($params);
	}
	
}
 
?>