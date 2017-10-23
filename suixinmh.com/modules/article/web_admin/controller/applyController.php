<?php 
/**
 * 作家申请记录控制器
 * @author chengyuan  2014-4-24
 *
 */
class applyController extends Admin_controller {
		public $template_name = 'applylist';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallarticle');//权限验证
			  //检查审核作者权限
			  $this->checkpower('setwriter');
        } 
		/**
		 * 申请列表视图
		 * @param unknown $params
		 */
        public function main($params = array()) {
			 $apply = $this->model('apply','article');
			$data = $apply->applyList($params);
             $this->display($data);
        } 
}

?>