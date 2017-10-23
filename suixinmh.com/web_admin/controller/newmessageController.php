<?php 
/** 
 * 系统管理->发送短信 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class newmessageController extends Admin_controller {
		public $template_name = 'newmessage';
		//public $theme_dir = false;
		public function __construct() { 
              parent::__construct();
			  //管理参数设置 权限
			  $this->checkpower('adminmessage');
        }
		
        public function main($params = array()) {
			 $dataObj = $this->model('newmessage');
			 $data = $dataObj->main($params);
             $this->display($data);
        } 
		
        public function newmsg($params = array()) {
			 $dataObj = $this->model('newmessage');
			 $data = $dataObj->newmsg($params);
             $this->display($data);
        } 
} 

?>