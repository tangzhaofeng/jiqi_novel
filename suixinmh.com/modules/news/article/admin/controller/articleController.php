<?php 
/** 
 * 小说连载->文章管理 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class articleController extends Admin_controller {
		public $template_name = 'articlelist';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallarticle');//权限验证
        } 
		
        public function main($params = array()) {
			 $dataObj = $this->model('article');//实例化自定义文章类
             $this->display($dataObj->main($params));
        } 
		public function doauthor($params = array()) {
			 $dataObj = $this->model('article');//实例化自定义文章类
             $this->display($dataObj->doauthor($params));
        } 
        /*public function doarticle($params = array()) {
			 $dataObj = $this->model('article');//实例化自定义文章类
             $this->display($dataObj->doarticle($params));
        } */		
		
		//批量删除文章
		public function batchdel($params = array()) {exit();
			//提交数据
			if($this->submitcheck()){
				 $dataObj = $this->model('article');
				 $dataObj->batchdel($params);
			}
        } 
}

?>