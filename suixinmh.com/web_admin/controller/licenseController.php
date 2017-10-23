<?php 
/** 
 * йзх╗пео╒license * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class licenseController extends Admin_controller {
		public $template_name = 'license';

        public function main() {
		     $dataObj = $this->model('license');
             $this->display($dataObj->main());
        } 
} 

?>