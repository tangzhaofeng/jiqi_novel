<?php 
/** 
 * 测试控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class tagController extends Controller { 

        public $template_name = 'tag'; 
		public $caching = false;
		public $theme_dir = false;

        public function main($params = array()) { //print_r(Application::$_config);
			$id = intval($params['id']);
			if($id) echo('document.write("'.addslashes_array(str_replace(array("\r","\n"),'',jieqi_geturl('system', 'tags', $id))).'");');
			exit;
        } 
} 

//testController 继承我们的核心控制器，其实在以后的每个控制器中都要继承的，现在我们通过浏览器访问 http://localhost/myapp/index.php?controller=test ,哈哈，可以输出 test 字符串了

?>