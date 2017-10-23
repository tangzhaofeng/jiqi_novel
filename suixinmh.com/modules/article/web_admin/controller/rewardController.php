<?php 
/**
 * 稿费管理
 * @author chengyuan
 *
 */
class rewardController extends Admin_controller {
		public $template_name = 'copyrightlist';
		
		public function __construct() { 
              parent::__construct();
// 			  $this->checkpower('manageallarticle');//权限验证
        } 
        /**
         * 版权管理列表
         * @param unknown $params
         */
        public function main($params = array()) {
			$dataObj = $this->model('copyright');
            $this->display($dataObj->main($params));
        }
        /**
         * 新增版权信息
         * @param unknown $params
         */
        public function addContract($params = array()){
        	if($this->submitcheck()){
        		$dataObj = $this->model('copyright');
        		$dataObj->addContract($params);
        	}
        }
        /**
         * 修改版权信息
         * @param unknown $params
         */
        public function editContract($params = array()){
        	if($this->submitcheck()){
        		$dataObj = $this->model('copyright');
        		$dataObj->editContract($params);
        	}
        }
        /**
         * 删除版权信息
         * @param unknown $params
         */
        public function deleteContract($params = array()){
        	$dataObj = $this->model('copyright');
        	$dataObj->deleteContract($params['id']);
        }
        
        /**
         * ajax json版权详情
         * @param unknown $params
         */
        public function getCopyright($params = array()){
        	$dataObj = $this->model('copyright');
        	$dataObj->getCopyright($params['id']);
        }
        
        /**
         * ajax json文章信息
         * @param unknown $params
         */
        public function getArticle($params = array()){
        	$dataObj = $this->model('copyright');
        	$dataObj->getArticle($params['aid']);
        }
        
        
        
        /**
         * 稿费管理列表
         * @param unknown $params
         */
        public function reward($params = array()) {
        	$dataObj = $this->model('reward');
        	$this->display($dataObj->reward($params),'rewardlist');
        }
        /**
         * 版权管理-财务记录列表
         * @param unknown $params
         */
        public function finance($params = array()) {
        	$dataObj = $this->model('finance');
        	$this->display($dataObj->main($params),'financelist');
        }
        
        /**
         * 审核财务信息修改申请
         * @param unknown $params
         */
        public function audit($params = array()) {
        	$dataObj = $this->model('finance');
        	$dataObj->audit($params['ueaid'],$params['state']);
        }
        /**
         * 获取用户的财务信息
         * @param unknown $params
         */
        public function getFinance($params = array()) {
        	$dataObj = $this->model('finance');
        	$dataObj->getFinance($params['ueid']);
        }
}
?>