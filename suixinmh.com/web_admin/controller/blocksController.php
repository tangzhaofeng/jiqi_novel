<?php 
/** 
 * 系统管理->区块管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class blocksController extends Admin_controller {
		public $template_name = 'blocks';
		
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('adminblock');
        } 
		
        public function main($params = array()) {
			 $dataObj = $this->model('blocks');
			 $dataModel = $dataObj->main($params);
		     $data = array(
				 'blocks'=>$dataModel['blocks'],
				 'modules'=>$dataModel['modules'],			 
				 'form_addblock'=>$dataModel['form_addblock'],
			 );
             $this->display($data);
        }

		/*编辑区块*/
		public function blockedit($params = array()){
		    $this->theme_dir = false;
		    $data = array();
			$dataObj = $this->model('blocks');
		    $this->display(array('jieqi_contents'=>$dataObj->blockedit($params)),'main');
		}

		/*删除区块*/
		public function blockdel($params = array()){
		    $data = array();
			$dataObj = $this->model('blocks');
			$data = $dataObj->blockdel($params);
		    $this->display($data);
		}

		/*区块编辑提交*/
		public function update($params = array()){
			 $dataObj = $this->model('blocks');
			 $data = $dataObj->update($params);
		     $this->display($data);
		}
		
		/*区块强制更新缓存*/
		public function blockupdate($params = array()){
			 $dataObj = $this->model('blocks');
			 $data = $dataObj->blockupdate($params);
		     //$this->display($data);
		}
} 

?>