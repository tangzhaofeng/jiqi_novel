<?php 
/** 
 * 系统管理->头衔管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class honorsController extends Admin_controller {
		public $template_name = 'honors';
		
		
		public function __construct() { 
              parent::__construct();
			  //管理参数设置 权限
			  $this->checkpower('adminconfig');
        }
		
		//public $theme_dir = false;
        public function main($params = array()) {
			 $dataObj = $this->model('honors');
			 $dataModel = $dataObj->main($params);
			 $data = array(
				 'honors'=>$dataModel['honors'],
				 'form_addhonor'=>$dataModel['form_addhonor'],
			 );
             $this->display($data);
        } 
		
		
		
		
		//edit view
		public function edit_view($params = array()){
			$dataObj = $this->model('honors');
			$jieqi_contents = $dataObj->editForm($params['id']);
			//exit;
			$this->display(array('jieqi_contents'=>$jieqi_contents),'main');
		}
		
		
		
		
		
		
		//do modify
		public function modify($params = array()){
			$dataObj = $this->model('honors');
//			$_REQUEST = $this->getRequest();
			$obj = array('id'=>$params['id'],'caption'=>$params['caption'],'minscore'=>$params['minscore'],'maxscore'=>$params['maxscore'],'photo'=>$params['photo']);
			$dataObj->modify($obj);
		}
		
		
		
		
		//do del
		public function del($params = array()){
			$dataObj = $this->model('honors');
			$dataObj->del($params['id']);
		}
		
		
		//do del
		public function newHonor($params = array()){
			 $dataObj = $this->model('honors');
			 $data = $dataObj->newHonor($params);
             $this->display($data);
		}
} 

?>