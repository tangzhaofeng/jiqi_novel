<?php 
/** 
 * 文章活动 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
class huodongController extends Controller { 

        public $template_name = 'huodong'; 
		public $caching = false;
		public $theme_dir = false;
		public function __construct() {
			parent::__construct ();
			//检查登陆
			$this->checklogin();
		} 	
        public function main($params = array()) {
			 $dataObj = $this->model('huodong');
             $this->display($dataObj->main($params)); 
        }
		//推荐
        public function vote($params = array()) {
			 $dataObj = $this->model('huodong');
             $this->display($dataObj->vote($params)); 
        } 
		//月票
        public function vipvote($params = array()) {
			 $dataObj = $this->model('huodong');
             $this->display($dataObj->vipvote($params)); 
        } 
		//打赏
        public function reward($params = array()) {
			 $dataObj = $this->model('huodong');
             $this->display($dataObj->reward($params)); 
        } 
		//催更
        public function cuigeng($params = array()) {
			 $dataObj = $this->model('huodong');
             $this->display($dataObj->cuigeng($params)); 
        } 
		//评论
        public function reviews($params = array()) {
			 $dataObj = $this->model('huodong');
             $this->display($dataObj->reviews($params)); 
        } 
		
		//加入书架
		function addBookCase($params = array()){
		   $dataObj = $this->model('huodong');
		   
           $dataObj->addBookCase($params); 
		}
		/**
		 * 自动添加书签
		 * @author chengyuan 2015-6-3 下午2:43:15
		 * @param unknown $params
		 */
		function autoAddBookCase($params = array()){
			$dataObj = $this->model('huodong');
			$dataObj->autoAddBookCase($params['aid'],$params['cid']);
		}
		/**
		 * 登陆用户的书籍是否放入书架
		 * @author chengyuan 2015-6-4 下午4:11:56
		 * @param unknown $params
		 */
		function asyncBookcaseState($params = array()){
			$dataObj = $this->model('huodong');
			$dataObj->asyncBookcaseState($params['aid']);
		}
} 
?>