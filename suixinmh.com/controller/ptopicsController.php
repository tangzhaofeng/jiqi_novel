<?php 
class ptopicsController extends Controller { 
		public $template_name = 'ptopics'; 
		public $caching = false;
        public function main() {
                $this->display(); 
        } 
        public function login() {
		        $this->template_name = 'login'; 
				$this->assign('b','huliming');
                $this->display(array('a'=>'nnnnnnn')); 
        }
} 
?>
