<?php 
/**
 * 文章属性统计控制器
 * @author chengyuan  2014-4-24
 *
 */
class statisticsController extends Admin_controller {
		public $template_name = 'statistics';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallarticle');//权限验证
			  //检查审核作者权限
			  $this->checkpower('setwriter');
        } 
		/**
		 * 文章统计默认入口
		 * @param unknown $params
		 */
        public function main($params = array()) {
        		$apply = $this->model('statistics','article');
        		$data = $apply->statistics($params);
        		$this->display($data);
			
        } 
}

?>