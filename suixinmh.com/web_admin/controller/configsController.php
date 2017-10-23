<?php 
/** 
 * 后台系统管理->系统定义控制器  * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class configsController extends Admin_controller {
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('adminconfig');
        } 
		
		public $template_name = 'main';
		public $theme_dir = false;
        public function main($params = array()) {
			 $dataObj = $this->model('configs');
		     $data = array(
			     'jieqi_contents'=>$dataObj->main($params)
			 );
             $this->display($data);
        } 
} 

?>