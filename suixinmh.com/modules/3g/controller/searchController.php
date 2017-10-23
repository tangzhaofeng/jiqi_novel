<?php
/**
 * 测试控制器 * @copyright   Copyright(c) 2014
 * @author      liujilei* @version     1.0
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
class searchController extends chief_controller {
	public $caching = false;
	public $theme_dir = false;
	public function main($params = array()) {
		//$params['searchkey'] = iconv('utf-8','gbk',$params['searchkey']);
		$dataObj = $this->model('search', 'article');
		$data = $dataObj->search($params);
		if ($data['articlerows'] == 1) {
			$url_articleinfo = $this->geturl('3g', 'articleinfo', 'SYS=aid=' . $data['aid']);
			header('location:' . $url_articleinfo);
		}

		$this->display($data);
	}

	public function search($params = array()) {
		if (isset($params['searchkey']) && strlen($params['searchkey']) >= 2) {
			$dataObj = $this->model('search', 'article');
			$data = $dataObj->search($params);
			if ($data['articlerows'] == 1) {
				$url_articleinfo = $this->geturl('3g', 'articleinfo', 'SYS=aid=' . $data['aid']);
				header('location:' . $url_articleinfo);
			}
		} else {
			$data['searchnonum'] = '请输入2个以上的关键字进行搜索';
		}
		$this->display($data);
	}

}
?>