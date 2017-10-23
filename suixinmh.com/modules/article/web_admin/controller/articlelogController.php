<?php 
/** 
 * 小说连载->文章日志管理 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class articlelogController extends Admin_controller {
		public $template_name = 'articlelog';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallarticle');//权限验证
        } 
		
        public function main($params = array()) {
			 $dataObj = $this->model('articlelog');
             $this->display($dataObj->main($params));
        } 
}

?>