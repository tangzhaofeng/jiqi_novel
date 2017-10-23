<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class getpassController extends chief_controller { 
	public $template_name = 'getpass'; 
	public $caching = false;
	public $theme_dir = false;

	public function main($params = array()){
	    $data = array();
	    if($this->submitcheck()){
			$dataObj = $this->model('getpass','system');
			$data = $dataObj->main($params);
		}
		$this->display($data);
	}
}
?>