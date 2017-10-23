<?php
/** 
 * 冲值后台程序核心控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class Admin_controller extends Controller{
		public $theme_dir = '/templates/admindemo/main';
		public $caching = false;
		
        public function __construct() { 
              parent::__construct();
			  $this->checkpower('adminpaylog');
        } 
		
		//后台权限检查
		public function checkpower($pname, $isreturn=false){
//			$this->dump($GLOBALS);
		     //include_once('model/powerModel.php'); 
			 if($pname=='admin'){
			      if(!$this->checkisadmin()){
				      if(!$isreturn) jieqi_printfail(LANG_NEED_ADMIN);
					  else return false;
				  }else return true;
			 }else{
				 $powerObj = $this->model();
				 return parent::checkpower($powerObj->getDbPower(JIEQI_MODULE_NAME,$pname), $this->getUsersStatus(), $this->getUsersGroup(), $isreturn, true);
			 }
		}
		
		/**
		 * 重写方法：重写global内方法
		 * 正式上线后直接删除此方法即可
		 */
		public function getAdminurl($controller='', $p = '', $module = JIEQI_MODULE_NAME){
		    if($module == 'system' || $controller=='login'){
	//	    	echo $controller;die;
				if($controller) return JIEQI_URL.'/admindemo/?controller='.$controller.($p ? '&'.$p : '');
				else return JIEQI_URL.'/admindemo/';
			}else{
			    global $jieqiModules;
				$controller = $controller ? $controller : $_REQUEST['controller'];
			    if($controller) return $jieqiModules[$module]['url'].'/admindemo/?controller='.$controller.($p ? '&'.$p : '');
				else return $jieqiModules[$module]['url'].'/admindemo/';
			}
		}
}
?>