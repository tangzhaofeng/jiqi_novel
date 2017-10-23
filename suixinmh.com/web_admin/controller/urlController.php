<?php 
/** 
 * 后台系统管理->路径管理控制器  * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class urlController extends Admin_controller {
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('admin');
        } 
		
		public $template_name = 'url';
		//public $theme_dir = false;
		//加载url列表
        public function main($params = array()) {
        	if(empty($params['mod']))$params['mod']='system';
			 $dataObj = $this->model('url');
			 $map = $dataObj->main($params['mod']);
			 $map['addForm'] =$dataObj->addForm($params['mod']);
			 $map['url_mod'] =  $this->getAdminurl('url');
			 $map['mod'] =  $params['mod'];
			 $this->display($map);
        } 
		
		//mofify
        public function modify($params = array()) {
			 $dataObj = $this->model('url');
			 if(!$params['mod'])$params['mod']='system';
			 $dataObj->modify($params);
        } 
		//add
		public function add($params = array()){
			$dataObj = $this->model('url');
			if(!$params['mod'])$params['mod']='system';
			$dataObj->add($params);
		}
} 

?>