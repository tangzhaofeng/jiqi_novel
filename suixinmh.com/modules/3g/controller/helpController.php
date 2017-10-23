<?php
/*帮助控制器*/ 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class helpController extends chief_controller {
	public $theme_dir = false;
	public function main(){
		// 初始化问题
		$helpno = isset($_GET['helpno']) ? intval($_GET['helpno']) : 1000;
		$data['helpno'] = $helpno;
		$this->display($data);
	}
} 
?>
