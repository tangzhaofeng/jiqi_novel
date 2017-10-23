<?php
class qixiController extends Controller {
	
	public function qixi($param){
		$dataObj = $this->model('article','article');
		$dataObj->qixi($param);
	}
	/**
	 * 七夕注册统计,增加77树海币
	 *
	 * 2014-8-13 上午11:12:09
	 */
	public function qixireg($param){
		$dataObj = $this->model('article','article');
// 		$dataObj->qixireg($param);
	}
	public function qixilogin($param){
		$dataObj = $this->model('article','article');
// 		$dataObj->qixilogin($param);
	}
	
	public function qixisale($param){
		$dataObj = $this->model('article','article');
// 		$dataObj->qixisale($param);
	}
	
	public function handleStatTable($param){
		$dataObj = $this->model('article','article');
		$dataObj->handleStatTable($param);
	}
}
?>