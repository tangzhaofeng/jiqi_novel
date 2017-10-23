<?php
/**
 * 后台程序核心控制器 * @copyright   Copyright(c) 2014
 * @author      huliming* @version     1.0
 */
class Admin_controller extends Controller {
	public $theme_dir = '/templates/admin/main';
	public $caching = false;
	public function __construct() {
		static $int = 0;
		if($int < 1){
			header ( "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
			$int++;
		}
		parent::__construct ();
		$this->checkpower ( 'authorpanel' );
	}
	
	// 后台权限检查
	public function checkpower($pname, $isreturn = false) {
		// include_once('model/powerModel.php');
		if ($pname == 'admin') {
			if (! $this->checkisadmin ()) {
				if (! $isreturn)
					jieqi_printfail ( LANG_NEED_ADMIN );
				else
					return false;
			} else
				return true;
		} else {
			$powerObj = $this->model ();
			return parent::checkpower ( $powerObj->getDbPower ( JIEQI_MODULE_NAME, $pname ), $this->getUsersStatus (), $this->getUsersGroup (), $isreturn, true );
		}
	}
}
?>