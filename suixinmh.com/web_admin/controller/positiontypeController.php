<?php
/**
 * 标签分类管理
 * @author liuxiangbin
 * @create 2015-03-24 11:38:15
 */
class positiontypeController extends Admin_Controller {
	
	/**
	 * 验证是否有管理标签的权限
	 */
	public function __construct() { 
      	parent::__construct();
	  	$this->checkpower('adminblock');
    }
	
	/**
	 * 标签列表
	 */
	public function main($params = array()) {
		$ptModel = $this->model('positiontype');
		$data = $ptModel->getAllData($params);
		$this->display($data);
	}
	
	/**
	 * 添加一个标签
	 */
	public function add($params = array()) {
		$this->display();
	}
	
	/**
	 * 无模板：添加分类数据
	 */
	public function addData($params = array()) {
//		echo 111;die;
		$ptModel = $this->model('positiontype');
		$ptModel->setData($params, 'add');
	}
	
	/**
	 * 编辑一个标签
	 */
	public function edit($params = array()) {
		$ptModel = $this->model('positiontype');
		$data = $ptModel->getOne($params);
		$this->display($data);
	}
	
	/**
	 * 无模板：编辑一个标签
	 */
	public function editData($params = array()) {
		$ptModel = $this->model('positiontype');
		$ptModel->setData($params);
	}
	
	/**
	 * 异步方法：删除一个标签，需要验证是否在使用
	 */
	public function del($params = array()) {
		$ptModel = $this->model('positiontype');
		$ptModel->delData($params);
	}
	
	
	
}
 
 
 
 
