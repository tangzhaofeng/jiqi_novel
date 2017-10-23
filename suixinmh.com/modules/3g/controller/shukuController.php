<?php
/**
 * 书库控制器
 * @author chengyuan  2014-7-18
 *
 */

class shukuController extends chief_controller {

	//	public $theme_dir = false;
	public $caching = true;
	public function main($params = array()) {
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		$this->setCacheid(md5(serialize($params)));
		if(!$this->is_cached()){
			$params['siteid'] = isset($params['siteid']) ? $params['siteid'] : 0;
			// 设置每页显示文章数量
			$params['listnum'] = 10;
			$dataObj = $this -> model('shuku', 'article');
			$data = $dataObj -> query($params);
			// 过滤显示参数
			$filterModel = $this->model('filter3g', '3g');
			$data['operate'] = $filterModel->getOperate();
		}
		$this -> display($data);
	}

}
?>