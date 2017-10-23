<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
class setpassController extends Controller { 
	public $template_name = 'setpass'; 
	public $caching = false;

	public function main($params = array()){
		$dataObj = $this->model('setpass');
		$this->display($dataObj->main($params));
	}
}
?>