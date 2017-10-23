<?php 
/**
 * 传媒公司文章控制器继承文章管理控制器
 * @author chengyuan 2015-5-12 上午10:43:34
 */
include_once ($GLOBALS ['jieqiModules'] ['article'] ['path'] . '/admin/controller/articleController.php');
class articleMediaController extends articleController {
	public $template_name = 'articlemedialist';
	
	public function main($params = array()) {
		$dataObj = $this->model('articleMedia');
		$this->display($dataObj->main($params));
	}
}
?>