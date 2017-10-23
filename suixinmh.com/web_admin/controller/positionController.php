<?php 
/** 
 * 后台系统管理->模板标签控制器  * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class positionController extends Admin_controller {
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('adminblock');
        } 
		public $template_name = 'position';
		//加载列表
        public function main($params = array()) {
			 $data = array();
			 $dataObj = $this->model('position');
			 $data = $dataObj->main($params);
			 $ptModel = $this->model('positiontype');
			 $data['ptypes'] = $ptModel->getSort();
			 $this->display($data);
        } 
        public function view($params = array()) {
			 $_PAGE['content'] = jieqi_geturl('system','tags',array('id'=>$params['posid']));
			 $this->display(array('_PAGE'=>$_PAGE), 'position_view');
        } 
		public function add($params = array()){
		     $dataObj = $this->model('position');
			 // liuxiangbin 添加分类表
			 $ptModel = $this->model('positiontype');
			 $data['ptypes'] = $ptModel->getSort();
			 $this->assign('ptypes', $data['ptypes']);
			 $this->display($dataObj->add($params), 'position_add'.$params['step']);
		}
		public function del($params = array()){
		     $dataObj = $this->model('position');
			 $dataObj->del($params);
		}
		public function order($params = array()){
		     $dataObj = $this->model('position');
			 $dataObj->order($params);
		}
/*		//mofify
        public function modify() {
			 $dataObj = $this->model('url');
			 $dataObj->modify($this->default_mod());
        } 
		//add
		public function add(){
			$dataObj = $this->model('url');
			$dataObj->add($this->default_mod());
		}
		public function default_mod(){
			$mod = $this->getRequest('mod');
			if(empty($mod))$mod='system';
			return  $mod;
		}*/
} 

?>