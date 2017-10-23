<?php 
/** 
 * 系统管理->发送短信->详细信息 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class messagedetailController extends Admin_controller {
		public $template_name = 'messagedetail';
		//public $theme_dir = false;
        public function main() {
			 $dataObj = $this->model('messagedetail');
			 $data = $dataObj->main();
			 /*$data = array(
				 'themes'=>$dataModel['themes'],
				 'jieqiModules'=>$dataModel['jieqiModules'],
			 );*/
             $this->display($data);
        } 
} 

?>