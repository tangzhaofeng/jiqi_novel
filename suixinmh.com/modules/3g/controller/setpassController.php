<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class setpassController extends chief_controller { 
//	public $template_name = 'setpass'; 
	public $caching = false;
	public $theme_dir = false;

	public function main($params = array()){
		$dataObj = $this->model('setpass','system');
		$data = $dataObj->main($params);//print_r($data);
		$this->display($data);
	}
}
?>