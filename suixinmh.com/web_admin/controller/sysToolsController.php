<?php 
/** 
 * 系统管理->系统工具 * @copyright   Copyright(c) 2014 
 * @author      chengyuan* @version     1.0 
 */ 
class sysToolsController extends Admin_controller {
		//public $template_name = 'main';
		//public $theme_dir = false;
		
		public function __construct() { 
              parent::__construct();
			  //admin
			  $this->checkpower('admin');
        }
		
		/** 
		 * goto cleancache view
		 */ 
        public function cleancache_view() {
			$dataObj = $this->model('sysTools');
			$dataObj->cleancacheForm();
        } 
		
		/** 
		 * do cleancache
		 */
		public function cleancache() {
			$dataObj = $this->model('sysTools');
			$dataObj->cleancache();
        }
}
?>