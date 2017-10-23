<?php
/** 
 * 后台程序核心控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); 
class Admin_controller extends Controller{
        public $theme_dir = 'main';
		public $caching = false;
		
        public function __construct() { 
              parent::__construct();
			  $this->checkpower('adminpanel');
        } 
		
		//后台权限检查
		public function checkpower($pname, $isreturn=false){
		     //include_once('model/powerModel.php'); 
			 if($pname=='admin'){
			      if(!$this->checkisadmin()){
				      if(!$isreturn) jieqi_printfail(LANG_NEED_ADMIN);
					  else return false;
				  }else return true;
			 }else{
				 $powerObj = $this->model();
				 $mod = $this->getRequest('mod') && $pname != 'adminpanel' ? $this->getRequest('mod'):'system';
				 return parent::checkpower($powerObj->getDbPower($mod,$pname), $this->getUsersStatus(), $this->getUsersGroup(), $isreturn, true);
			 }
		}
/*		
		public function getAdminurl($controller='', $p = ''){
		     if($controller) return JIEQI_URL.'/admin/?controller='.$controller.($p ? '&'.$p : '');
			 else return JIEQI_URL.'/admin/';
		}*/
}
?>