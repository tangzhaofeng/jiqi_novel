<?php 
/**
 * 批量采集
 * @author huliming  2014-9-12
 *
 */
class batchcollectController extends Admin_controller {
    public $theme_dir = false;
	public $template_name = '/templates/admin/main';
	public function __construct() { 
		  parent::__construct();
		  $this->checkpower('manageallarticle');//权限验证
	} 
	
	//单篇采集主视图
	public function main($params = array()) {
		$dataObj = $this->model('batchcollect');
		$this->display($dataObj->main($params));
	}
	
}

?>