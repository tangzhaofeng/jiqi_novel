<?php
/**
 * 任务管理控制器
 * @author chengyuan  2014-6-11
 *
 */
class taskController extends Admin_controller {
	public $template_name = 'task_list';

	/**
	 * 默认入口，任务列表
	 * @param unknown $params
	 */
	public function main($params = array()) {
		$data = array();
		$types = array();
		$taskModel = $this->model('task');
		$data = $taskModel->main($params);
		$data['taskTypes'] = $taskModel->getTypesLists();
//		$this->dump($data);
		$this->display($data);
	}
	
	/**
	 * 获得任务类型的规则(异步返回数据)
	 */
	public function getTaskTypeRule($params = array()) {
		$taskModel = $this->model('task');
		$taskModel->getTaskRule($params);
	}
	
	/**
	 * 添加一个新的任务
	 */
	public function addNewTask($params) {
		$taskModel = $this->model('task');
		$taskModel->addTask($params);
	}
	
	/**
	 * 修改一个任务
	 */
	public function editOneTask($params) {
		$taskModel = $this->model('task');
		$taskModel->editTask($params);
	}
	
	/**
	 * 删除一个任务
	 */
	public function delOneTask($params) {
		$taskModel = $this->model('task');
		$taskModel->delTask($params);
	}
	
	/**
	 * 显示一个可编辑的任务数据
	 */
	public function showOneTask($params) {
		$data = array();
		$taskModel = $this->model('task');
		$data = $taskModel->getOneTask($params);
	}

}












