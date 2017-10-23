<?php 
/** 
 * 小说连载->生成HTML * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class batchrepackController extends Admin_controller {
		public $template_name = 'batchrepack';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallarticle');//权限验证
        } 
		
        public function main($params = array()) {
			 $dataObj = $this->model('batchrepack');//实例化自定义文章类
             $this->display($dataObj->main($params));
        } 
}

?>