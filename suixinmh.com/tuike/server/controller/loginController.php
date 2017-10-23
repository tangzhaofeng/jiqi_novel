<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class loginController extends Tuike_controller { 
  public $template_name = 'login'; 
  public $caching = false;
  public $theme_dir = false;

  public function main($params = array()){
    if($this->checklogin(true)) {
      ecs_header('location:'.$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url']);exit;
    }
    $dataObj = $this->model('login');
    $data = $dataObj->main($params);
    $data['ujumpurl'] = urlencode($data['jumpurl']);
    $this->display($data);
  }


}  

?>