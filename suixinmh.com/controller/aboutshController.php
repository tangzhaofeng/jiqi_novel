<?php 
class aboutshController extends Controller {
	public $caching = false; 
	public function main(){
		$dataObj = $this->model('aboutsh');
		$data = $dataObj->aboutsh();
		$this->display($data,'aboutsh');
	}
} 
?>
