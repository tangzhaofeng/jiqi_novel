<?php
/**
 * 任务完成查询列表
 * @author liuxiangbin  2015-02-02
 *
 */
class completeController extends Admin_controller {
	public $template_name = 'complete_list';
	
	/**
	 * 主列表
	 */
	public function main($params = array()) {
		// 实例模型
		$completeModel = $this->model('complete');
		$taskModel = $this->model('task');
		// 获取任务类型列表
		$data = $completeModel->getAllData($params);
		$data['types'] = $taskModel->getTypesLists();
//		$this->dump($data);
		$this->display($data);
	}
	
}
