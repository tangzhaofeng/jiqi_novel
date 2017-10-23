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
		     if(isset($_SESSION['adminurl']) && !empty($_SESSION['adminurl'])){
		     	$jieqi_adminmain = $_SESSION['adminurl'];
		     }else{
		     	$jieqi_adminmain = $this->getAdminurl('sysinfo');
		     }
			 $data = $this->getAuth();
		     $data['jieqi_adminleft']=$this->getAdminurl('left');
			 $data['jieqimodules']=$dataObj->getMenu();
			 $data['jieqi_adminmain']=$jieqi_adminmain;
			 $data['jieqi_licenseurl']=$this->getAdminurl('license');
             $this->display($data);
        } 
}
?>