<?php
/** 
 * 后台程序核心控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */
define("JIEQI_GROUP_CPS",5);
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); 
class Admin_controller extends Controller{
        public $theme_dir = 'main';
		public $caching = false;
		
        public function __construct() { 
              parent::__construct();
			  $this->checkpower();
        } 
		
		//后台权限检查
		public function checkpower(){
			$auth=$this->getAuth();
            if (defined("ADMIN_ONLY") && ADMIN_ONLY) {
                $grouplist=array(JIEQI_GROUP_ADMIN);
            }
            else {
                $grouplist=array(JIEQI_GROUP_ADMIN,JIEQI_GROUP_CPS);
            }
			if (!in_array($auth['groupid'],$grouplist)) {
				header("location:index.php?controller=login");
				exit();
			}
		}
}
?>