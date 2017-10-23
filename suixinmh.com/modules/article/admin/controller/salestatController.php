<?php 
/** 
 * 小说连载->销售统计 * @copyright   Copyright(c) 2014 
 * @author      zhuyunlong* @version     1.0 
 */ 
class salestatController extends Admin_controller {
		public $template_name = 'salestat';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallarticle');//权限验证
        } 
		
        public function main($params = array()) {
			 $dataObj = $this->model('salestat');
             $this->display($dataObj->main($params));
        }
		
		public function chapterstat($params){
			$dataObj = $this->model('salestat');
            $this->display($dataObj->chapterstat($params),'chapterstat');
		}
		public function chapterbuylog($params){
			$dataObj = $this->model('salestat');
            $this->display($dataObj->chapterbuylog($params),'chapterbuylog');
		}
		public function toplist($params){
			$dataObj = $this->model('top');
            $this->display($dataObj->toplist($params),'top_list');
		}
}

?>