<?php 
/** 
 * 系统管理->模块配置文件 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class managemodulesController extends Admin_controller {
		public $template_name = 'managemodules';
		//public $theme_dir = false;
		
		public function __construct() { 
              parent::__construct();
			  //admin
			  $this->checkpower('admin');
        }
		
        public function main($params = array()) {
			 $dataObj = $this->model('managemodules');
			 $dataModel = $dataObj->main($params);
			 $data = array(
				 'themes'=>$dataModel['themes'],
				 'jieqiModules'=>$dataModel['jieqiModules'],
			 );
             $this->display($data);
        } 
} 

?>