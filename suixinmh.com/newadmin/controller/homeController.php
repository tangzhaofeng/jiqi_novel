<?php 
/** 
 * 后台首页控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */
class homeController extends Admin_controller { 
        public $template_name = 'index'; 
		public $theme_dir = false; 
        public function main() {	
		     $dataObj = $this->model('home');
			 $data=$dataObj->main();
             $auth=$this->getAuth();
             $data['groupid']=$auth['groupid'];
             $this->display($data);
        } 
}
?>