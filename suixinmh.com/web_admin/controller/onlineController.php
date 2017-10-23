<?php 
/** 
 * 系统管理->在线用户管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class onlineController extends Admin_controller {
		public $template_name = 'online';
		
		public function __construct() { 
              parent::__construct();
			  //管理参数设置 权限
			 // $this->checkpower('adminuser');
			  $this->checkpower('viewonline');
        }
		//public $theme_dir = false;
        public function main($params = array()) {
			 $dataObj = $this->model('online');
			 $dataModel = $dataObj->main($params);
			 $data = array(
				 'userrows'=>$dataModel['userrows'],
				 'rowcount'=>$dataModel['rowcount'],
				 'url_jumppage'=>$dataModel['url_jumppage'],
			 );
             $this->display($data);
        } 
} 

?>