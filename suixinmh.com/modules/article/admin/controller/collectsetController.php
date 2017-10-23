<?php 
/**
 * 采集配置控制器
 * @author zhanngxue  2014-9-12
 *
 */
class collectsetController extends Admin_controller {
		//public $template_name = 'applylist';
		
		public function __construct() { 
              parent::__construct();
			  $this->checkpower('manageallarticle');//权限验证
        } 
		//采集配置主视图
        public function main($params = array()) {
			$dataObj = $this->model('collectset');
			$data = $dataObj->main($params);
            $this->display($data,'collectset');
        }
		//单篇采集规则 编辑
		public function collectedit($params = array()){
			$dataObj = $this->model('collectset');
			$dataObj->collectedit($params);
		}
		//单篇采集规则 编辑视图
		public function collecteditview($params = array()){
			$dataObj = $this->model('collectset');
			$data = $dataObj->collecteditview($params);
            $this->display($data,'empty');
		}
		//批量采集规则 视图
		public function collectpage($params = array()){
			$dataObj = $this->model('collectset');
			$data = $dataObj->collectpage($params);
            $this->display($data,'collectpage');
		}
		//批量采集规则 编辑
		public function collectpedit($params = array()){
			$dataObj = $this->model('collectset');
			$dataObj->collectpedit($params);
		}
		//批量采集规则 编辑视图
		public function collectpeditview($params = array()){
			$dataObj = $this->model('collectset');
			$data = $dataObj->collectpeditview($params);
            $this->display($data,'empty');
		}
		//新建规则
		public function collectnew($params = array()){
			$dataObj = $this->model('collectset');
			$dataObj->collectnew($params);
		}
		//新建规则视图
		public function collectnewview($params = array()){
			$dataObj = $this->model('collectset');
			$data = $dataObj->collectnewview($params);
            $this->display($data,'empty');
		}
}

?>