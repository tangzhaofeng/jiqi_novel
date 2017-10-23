<?php 
/** 
 * 系统管理->头衔管理 * @copyright   Copyright(c) 2014 
 * @author      liujilei* @version     1.0 
 */ 
class vipgradeController extends Admin_controller {
		public $template_name = 'vipgrade';
		public function __construct() { 
              parent::__construct();
			  //管理参数设置 权限
			  $this->checkpower('adminconfig');
        }
		
		 public function main($params = array()) {
		 	echo 'wwwwwwwwwwwwwwwwww';
			exit;
			 $dataObj = $this->model('vipgrade');
			 $data  = $dataObj->main($params);
			 echo 'qqqq';
			 print_r($data);
             $this->display($data);
        }
		
		public function edit($params = array()){
			$dataObj = $this->model('vipgrade');
			$data = $dataObj->edit($params['id']);
			$this->display(array('jieqi_contents'=>$data),'main');
		} 
		
		public function modify($params = array()){
			$dataObj = $this->model('vipgrade');
			$dataObj->modify($params);
		}
		
		public function del($params = array()){
			$dataObj = $this->model('vipgrade');
			$dataObj->del($params['id']);
		}
		
		public function add($params = array()){
			 $dataObj = $this->model('vipgrade');
			 $dataObj->add($params);
		}
}
?>