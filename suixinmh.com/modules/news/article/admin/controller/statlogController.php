<?php 
/**
 * 文章日志
 * @author chengyuan  2014-6-5
 *
 */
class statlogController extends Admin_controller {
		public $template_name = 'statlog';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallarticle');//权限验证
			  //检查审核作者权限
			  $this->checkpower('setwriter');
        } 
		/**
		 * 默认入口
		 * @param unknown $params
		 */
        public function main($params = array()) {
        		$apply = $this->model('statlog','article');
        		$data = $apply->statlog($params);
        		$this->display($data);
			
        } 
}

?>