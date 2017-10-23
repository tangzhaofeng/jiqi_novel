<?php 
/** 
 * 测试控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class shuhaihController extends Controller { 

        public $template_name = 'cy';
		public $theme_dir = false;
		///public $caching = true;
		//public $cacheid = 'fff';
		//public $cachetime=3;
		   
        public function __construct() { 
              parent::__construct(); 
        } 

        public function main() { print_r(Application::$_config);
		    $M = $this->model('shuhaih');
			//print_r($M->shuhaiDatabases());
			$rst = $M->shuhaiDatabases();
			$this->assign('cy',$rst);
            $this->display($data); 
        } 
		/*public function login(){
		    $this->template_name = 'login2';
		    $data['title'] = '登陆页';
			$data['test'] = "这里是登陆5555555555555555555555";
			$this->display($data); 
		}*/
}
?>