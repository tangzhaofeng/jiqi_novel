<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
class getpassController extends Controller { 
	public $template_name = 'getpass'; 
	public $caching = false;

	public function main($params = array()){
	    $data = array();
	    if($this->submitcheck()){
			$dataObj = $this->model('getpass');
			$data = $dataObj->main($params);
		}
		$this->display($data);
	}
}
?>