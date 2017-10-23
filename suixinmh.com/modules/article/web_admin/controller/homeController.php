<?php 
/** 
 * ср╡Юsysinfo * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class homeController extends Admin_controller {
		public $template_name = 'articlelist';

        public function main() {
		     $data = array(
			     'sysinfos'=>$this->getAdminurl('sysinfo')
			 );
             $this->display($data);
        } 
} 

?>