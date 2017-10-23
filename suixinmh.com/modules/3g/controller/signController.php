<?php
/**
 * ÔÄ¶Á¿ØÖÆÆ÷
 * @author chengyuan  2014-8-8
 *
 */
include_once(JIEQI_ROOT_PATH.'/include/funsystem.php');
class signController extends chief_controller {
	public $theme_dir = false;

	public function main($params) {
		return $this->display($params,"mysign");
	}

	public function mysign($params = array()) {
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		$this->check_login();
		$dataObj = $this->model('sign', '3g');
		$data = $dataObj->main($params);
		return $this->display($data,"sign");
	}

	public function sign($params = array()){
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		$this->check_login();

		$dataObj = $this->model('sign','3g');
		$dataObj->sign($params);
		$this->main($params);
	}

    private function check_login() {
        $user = $this->getAuth();

        $url = urlencode("http://".JIEQI_HTTP_HOST."/sign/mysign");
        if (!$user['uid']) {
            header("location:".$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url']."/login/?jumpurl=".$url);
            exit();
        }
    }
}
?>