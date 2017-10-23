<?php 
/** 
 * 系统管理->用户报告 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class reportController extends Admin_controller {
		public $template_name = 'reportlist';
		//public $theme_dir = false;
		public function __construct() { 
              parent::__construct();
			  //用户报告 权限
			  $this->checkpower('adminreport');
        }
		
        public function main($params = array()) {
			 $dataObj = $this->model('report');
			 $dataModel = $dataObj->main($params);
			 $data = array(
				 'checkall'=>$dataModel['checkall'],
				 'reportrows'=>$dataModel['reportrows'],
				 'rsortrows'=>$dataModel['rsortrows'],
				 'url_jumppage'=>$dataModel['url_jumppage'],
			 );
             $this->display($data);
        }
		
		/*用户报告详情*/
        public function detail($params = array()) {
			 $dataObj = $this->model('report');
			 $data = $dataObj->detail($params);
             $this->display($data, 'reportdetail');
        }
} 

?>