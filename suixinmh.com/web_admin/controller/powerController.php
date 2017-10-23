<?php 
/** 
 * 系统管理->权限设置 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class powerController extends Admin_controller {
	    public function __construct() { 
              parent::__construct();
			  $this->checkpower('adminpower');
        } 
		
		public $template_name = 'main';
		public $theme_dir = false;
        public function main($params = array()) {
			 $dataObj = $this->model('power');
		     $data = array(
			     'jieqi_contents'=>$dataObj->main($params)
			 );
             $this->display($data);
        } 
} 

?>