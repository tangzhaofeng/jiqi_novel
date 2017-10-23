<?php 
/** 
 * ×ó²à²Ëµ¥¿ØÖÆÆ÷ * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class leftController extends Admin_controller { 
        public $template_name = 'left'; 
        public $theme_dir = false; 
		
        public function main() {
		     $dataObj = $this->model('left');
			 $C = $dataObj->getMenu();
		     $data = array(
			      'jieqi_adminleft'=>$this->getAdminurl('left'),
				  'jieqi_adminurl'=>$this->getAdminurl('sysinfo'),
				  'jieqimodules'=>$C['jieqimodules'],
				  'adminmenus'=>$C['adminmenus']
			 );
			 
             $this->display($data); 
        }
}
?>