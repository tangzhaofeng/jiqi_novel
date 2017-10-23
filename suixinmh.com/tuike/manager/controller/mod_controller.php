<?php
/** 
 * 后台程序核心控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); 
class Tuike_controller extends Controller{
  public $theme_dir = 'main';
  public $caching = false;
  
  public function __construct() { 
    parent::__construct();
    
    include_once(JIEQI_ROOT_PATH."/include/total_service_funcs.php");
    redis_conn();

    //判断推客的上级
    if(!defined('JIEQI_NEED_SOURCE')) define('JIEQI_NEED_SOURCE',true);
    if(defined('JIEQI_NEED_SOURCE') && $_SESSION['SOURCE_SITE']=='' && $_REQUEST['tku']){
      setcookie('SOURCE_SITE',$_REQUEST['tku'],time()+3600*360,'/');
      $_SESSION['SOURCE_SITE'] = $_REQUEST['tku'];
    }

    $this->assign(array(
      'youyuebook_url' => YOUYUEBOOK_URL,
      'youyuebook_url_m' => YOUYUEBOOK_URL_M,
      'jieqi_site_name' => JIEQI_SITE_NAME,
      'nav' => 1,
     ));

    if(application::$DU_CONTROLLER != 'login' && application::$DU_METHOD != '_runPayOurAuthCron'){
      $this->checkLogin(false);
    }

    if($this->checklogin(true)){
      $comm=$this->model('manuser')->common();
      $this->assign('comm',$comm);
    }

  } 

  /**
   * 检查登陆
   * @param  boolean $return [description]
   * @return [type]          [description]
   */
  public function checkLogin($return=false){


    if(isset($_SESSION['jieqiUserId']) && $_SESSION['jieqiUserId'] >0 ){
      if($return)return true;
      
    }else{
      if($return)return false;
      ecs_header('Location: '.$this->geturl(JIEQI_MODULE_NAME, 'login'));
    }
  }



  //后台权限检查
  public function checkpower($pname, $isreturn=false){

    //include_once('model/powerModel.php'); 
    if($pname=='admin'){
      if(!$this->checkisadmin()){
        if(!$isreturn) jieqi_printfail(LANG_NEED_ADMIN);
        else return false;
      }else return true;
    }else{
      $powerObj = $this->model();
      $mod = $this->getRequest('mod') && $pname != 'adminpanel' ? $this->getRequest('mod'):'system';
      return parent::checkpower($powerObj->getDbPower($mod,$pname), $this->getUsersStatus(), $this->getUsersGroup(), $isreturn, true);
    }
  }

}
?>