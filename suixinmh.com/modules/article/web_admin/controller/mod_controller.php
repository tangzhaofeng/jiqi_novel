<?php
/** 
 * 后台程序核心控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class Admin_controller extends Controller{
        public $theme_dir = '/templates/admin/main';
		public $caching = false;
		
        public function __construct() { 
		      header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
              parent::__construct();
			  $this->checkpower('adminpanel', false, 'system');
        } 
		
		//后台权限检查
		public function checkpower($pname, $isreturn=false, $module = JIEQI_MODULE_NAME){
		     //include_once('model/powerModel.php'); 
			 if($pname=='admin'){
			      if(!$this->checkisadmin()){
				      if(!$isreturn) jieqi_printfail(LANG_NEED_ADMIN);
					  else return false;
				  }else return true;
			 }else{
				 $powerObj = $this->model();
				 return parent::checkpower($powerObj->getDbPower($module,$pname), $this->getUsersStatus(), $this->getUsersGroup(), $isreturn, true);
			 }
		}
}
?>