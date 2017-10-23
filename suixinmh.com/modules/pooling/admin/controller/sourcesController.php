<?php
/**
 * 任务管理控制器
 * @author liuxiangbin  2015-1-26
 *
 */
class sourcesController extends Admin_controller {
	
	/**
	 * 构造函数控制权限
	 */
	public function __construct() {
		parent::__construct();
		$user = $this->getAuth();
		// 权限验证，上线前开启
		if ($user['groupid']!=2) {
			$this->printfail('对不起，你无权访问该页面');
		}
	}
		
	/**
	 * 后台管理首页
	 */
	public function main($params = array()) {
		$sourceModel = $this->model('sourcemanage');
		$data = $sourceModel->getDataList($params);
		$this->display($data, 'sources_list');
	}
	
	/**
	 * 添加一个渠道
	 */
	public function addSource($params = array()) {
		$this->display($data, 'sources_add');
	}
	
	/**
	 * 异步添加渠道数据
	 */
	public function addData($params = array()) {
		$sourceModel = $this->model('sourcemanage');
		$data = $sourceModel->setData($params);
	}
	
	/**
	 * 编辑一个渠道
	 */
	public function editSource($params = array()) {
		$sourceModel = $this->model('sourcemanage');
		$data = $sourceModel->getOneData($params);
		$this->display($data, 'sources_edit');
	}
	
	/**
	 * 异步修改渠道数据
	 */
	public function editData($params = array()) {
		$sourceModel = $this->model('sourcemanage');
		$data = $sourceModel->setData($params, 'edit');
	}
	
	/**
	 * 异步删除一个渠道
	 */
	public function delData($params = array()) {
		$sourceModel = $this->model('sourcemanage');
		$data = $sourceModel->delData($params);
	}
	
	/**
	 * 异步修改排序
	 */
	public function ordorData($params = array()) {
		$sourceModel = $this->model('sourcemanage');
		$data = $sourceModel->setOrder($params);
	}
}















