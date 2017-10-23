<?php
/**
 * 分类管理控制器
 * @author zhangxue  2014-9-24
 *
 */
class sortmanageController extends Admin_controller {

		public function __construct(){
			parent::__construct();
			$this->checkpower('managesort');//权限验证
		}
		
		//主视图
		public function main($params = array()){
			$dataObj = $this->model('sortmanage');
			$data = $dataObj->main($params);
			$this->display($data);
		}
		//增加分类
		public function addsort($params = array()){
			$dataObj = $this->model('sortmanage');
			$dataObj->addsort($params);
		}
		//修改分类视图
		public function editsortview($params = array()){
			$dataObj = $this->model('sortmanage');
			$data = $dataObj->editsortview($params);//print_r($data);
			$this->display($data,'editsort');
		}
		//修改分类
		public function editsort($params = array()){
			$dataObj = $this->model('sortmanage');
			$dataObj->editsort($params);
		}
		//删除分类
		public function delsort($params = array()){
			$dataObj = $this->model('sortmanage');
			$dataObj->delsort($params);
		}
}
?>