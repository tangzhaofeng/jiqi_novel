<?php
/**
 * 默认控制器
 * @author chengyuan  2014-8-6
 *
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class homeController extends chief_controller {

//         public $template_name = 'index';
		//public $theme_dir = false;
		//public $cacheid = 'fff';
		//public $cachetime=5;
		/**
		 * 控制器内缺省请求
		 * @param unknown $params
		 * 2014-8-6 上午9:38:19
		 */
        public function main($params = array()) {
        	$this->display();
        }
}

?>