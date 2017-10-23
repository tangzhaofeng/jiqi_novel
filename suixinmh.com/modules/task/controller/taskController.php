<?php
/**
 * 任务系统控制器：用户任务操作模块
 * @author by: liuxiangbin
 * @createtime : 2015-01-13
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class taskController extends Controller {
	public $template_name = 'task';
	public $caching = false;
	
	/**
	 * 主方法
	 */
	public function main($params = array()) {
		$this->checklogin();
		$params['methodName'] = 'main';
		$taskModel = $this->model('usertask', 'task');
		$data = $taskModel->getUserList($params);
		$this->display($data);
	}
	
	/**
	 * 用户完成任务列表
	 */
	public function userfinished($params = array()) {
		$this->checklogin();
		$params['methodName'] = 'userfinished';
		$taskModel = $this->model('usertask', 'task');
		$data = $taskModel->getUsreFinished($params);
		$this->display($data, 'userfinished');
	}
	
	/**
	 * 完成任务：异步方式返回
	 */
	public function taskComplete($params) {
		$taskModel = $this->model('usertask', 'task');
		$taskModel->setComplete($params);
	}
	
	/**
	 * 活动：答题抽奖，随机获取该本书的5道题
	 */
	public function getQuestion($params = array()){
	    	$dataObj = $this->model('questionbank','task');
	    	$dataObj->getRadomQuestion($params['aid']);
	}
	/**
	 * 
	 */
	public function luckydraw($params = array()){
	    	$dataObj = $this->model('specials','task');
	    	$dataObj->getLuckydraw($params);
	}
}
	