<?php
/**
 * 充值分成渠道管理控制器
 * @author zhangxue  2015-1-26
 *
 */
 header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
class homeController extends Controller {
		
		public $theme_dir = '/templates/admin/main';
		public $caching = false;
		
        public function main($params = array()) {
        	$dataObj = $this->model('home');
			$data = $dataObj->main($params);
        	$this->display($data,'paylog');
        }
		public function login($params = array()){
			$dataObj = $this->model('home');
			$data = $dataObj->login($params);
        	$this->display($data,'login');
		}
		public function logout(){
			$dataObj = $this->model('home');
			$data = $dataObj->logout();
		}
}
?>
	