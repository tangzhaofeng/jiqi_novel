<?php
/**
 * 默认控制器
 * @author chengyuan  2014-8-6
 *
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class homeController extends Tuike_controller {
  public $theme_dir = false;
  public function __construct() { 
    parent::__construct();
    $this->assign('nav',1);
  } 
  public function main($params = array()) {


    $dataObj=$this->model('users');
    $data=$dataObj->userBase($params);
    $data['payList']=$dataObj->getPayList(0,0,array('limit'=>20,'orderS'=>'p.rettime'));                  
    $data['weekPayList']=$dataObj->getWeekPay();                  
    $data['balance']=$_SESSION['jieqiUserBalance'];

    $this->display($data,'home');
  }

  /**
   * 修改推客信息
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function edit($params = array()) {
 
    if($this->submitcheck()) {
      $dataObj = $this->model('home');
      $dataObj->editPass($params);
    }
    $this->display($data,'home_edit');
  }
}

?>