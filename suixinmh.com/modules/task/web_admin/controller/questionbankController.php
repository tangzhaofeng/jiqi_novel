<?php
/**
 * 题库后台控制器
 * @author liuxiangbin  2015-02-04
 *
 */
class questionbankController extends Controller {
	public $theme_dir = '/templates/admin/main';
	public $caching = false;
	
	/**
	 * 权限验证
	 */
	public function __construct() {
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		 parent::__construct();
		 $this->checkpower('questioneditor', false, 'task');
	}
	
	/**
	 * 权限验证方法
	 */
	public function checkpower($pname, $isreturn=false, $module = JIEQI_MODULE_NAME){
	     //include_once('model/powerModel.php'); 
		 if($pname=='admin'){
		      if(!$this->checkisadmin()){
			      if(!$isreturn) jieqi_printfail(LANG_NEED_ADMIN);
				  else return false;
			  }else return true;
		 }else{
			 $powerObj = $this->model();
			 return parent::checkpower($powerObj->getDbPower($module,$pname), $this->getUsersStatus(), $this->getUsersGroup(), $isreturn, true);
		 }
	}
	
	/**
	 * 题库列表
	 */
	public function main($params = array()) {
		$questionbankModel = $this->model('questionbank', 'task');
		$data = $questionbankModel->getAllData($params);
		$this->display($data, 'questionbank_list');
	}
	
	/**
	 * 异步：添加一个问题
	 */
	public function addQuestion($params = array()) {
		$questionbankModel = $this->model('questionbank', 'task');
		$data = $questionbankModel->setOneData($params, 'add');
	}
	
	/**
	 * 异步：编辑一个问题
	 */
	public function editQuestion($params = array()) {
		$questionbankModel = $this->model('questionbank', 'task');
		$data = $questionbankModel->setOneData($params, 'edit');
	}
	
	/**
	 * 异步：删除一个问题
	 */
	public function delOneQuestion($params = array()) {
		$questionbankModel = $this->model('questionbank', 'task');
		$data = $questionbankModel->delData($params);
	}
	
	/**
	 * 异步：展示一个问题详情
	 */
	public function showOneQuestion($params = array()) {
		$questionbankModel = $this->model('questionbank', 'task');
		$data = $questionbankModel->viewOneData($params);
	}
	
	public function getArticleName($params = array()) {
		$questionbankModel = $this->model('questionbank', 'task');
		$data = $questionbankModel->getArticleName($params);
	}
	
	
}















