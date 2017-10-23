<?php 
/** 
 * 小说连载->订阅管理 * @copyright   Copyright(c) 2014 
 * @author      zhuyunlong* @version     1.0 
 */ 
class salelogController extends Admin_controller {
		public $template_name = 'salelog';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallarticle');//权限验证
        } 
		
        public function main($params = array()) {
			 $dataObj = $this->model('salelog');
             $this->display($dataObj->main($params));
        } 
}

?>