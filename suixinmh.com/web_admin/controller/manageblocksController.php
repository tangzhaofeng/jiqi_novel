<?php 
/** 
 * 系统管理->区块配置文件管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class manageblocksController extends Admin_controller {
		public $template_name = 'manageblocks';
		//public $theme_dir = false;
        public function main() {
			 $dataObj = $this->model('manageblocks');
			 $dataModel = $dataObj->main();
			 $data = array(
				 'blockconfigs'=>$dataModel['blockconfigs'],
				 'modules'=>$dataModel['modules'],
				'modname'=>$dataModel['modname'],
			 );
             $this->display($data);
        }
		
        public function listblock() {
			 $dataObj = $this->model('manageblocks');
			 $data = $dataObj->listblock();
             $this->display($data, 'blocklist');
        }		
		
        public function editlist() {
			 $dataObj = $this->model('manageblocks');
			 $data = $dataObj->editlist();
             $this->display($data);
        }


        public function delete() {
			 $dataObj = $this->model('manageblocks');
			 $data = $dataObj->delete();
             $this->display($data);
        }	
		
        public function edit() {
			 $dataObj = $this->model('manageblocks');
			 $data = $dataObj->edit();
             $this->display($data, 'main');
        }
		
        public function edited() {
			 $dataObj = $this->model('manageblocks');
			 $data = $dataObj->edited();
             $this->display($data, 'main');
        }
} 

?>