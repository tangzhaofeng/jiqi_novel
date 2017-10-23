<?php
/** 
 * ºóÌ¨µÇÂ½¿ØÖÆÆ÷ * @copyright   Copyright(c) 2014 
 * @author      chengyuan* @version     1.0 
 */ 
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); 
class loginController extends Controller{
        public $theme_dir = 'main';
		//public $template_name = 'login'; 
		public $caching = false;
		
		/** 
		 * goto login view
		 */ 
        public function main() {
			$this->theme_dir = false;
			$dataObj = $this->model('login');
			$data = $dataObj->main();
			$this->display($data,'login');
        }
		
		/** 
		 * login
		 */ 
        public function login() {
			$dataObj = $this->model('login');
			$dataObj->login();
			//$this->display($data,'login');
        }

}
?>