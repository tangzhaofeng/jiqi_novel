<?php 
/** 
 * ср╡Юsysinfo * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class sysinfoController extends Admin_controller {
		public $template_name = 'sysinfo';
		
        public function main() {
			 $dataObj = $this->model('sysinfo');
		     $data = array(
			     'sysinfos'=>$dataObj->getData()
			 );
             $this->display($data);
        } 
} 

?>