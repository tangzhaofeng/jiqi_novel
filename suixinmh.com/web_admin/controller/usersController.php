<?php 
/** 
 * 系统管理->用户管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class usersController extends Admin_controller {
		public $template_name = 'users';
		//public $theme_dir = false;
		
		
		public function __construct() { 
              parent::__construct();
			  //管理用户 权限
			  //$this->checkpower('adminuser');
			  $this->checkpower('viewuser');
			  //$this->checkpower('viewonline');
        }
		
        public function main($params = array()) {
			 $dataObj = $this->model('users');
			 $data = $dataObj->main($params);
             $this->display($data);
        } 
} 

?>