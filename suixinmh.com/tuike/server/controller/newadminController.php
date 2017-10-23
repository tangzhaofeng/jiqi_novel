<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class newadminController extends Tuike_controller { 
  public $template_name = 'home'; 
  public $caching = false;
  public $theme_dir = false;
  public function __construct() { 
    parent::__construct();
    $this->assign('nav',7);
  }


  /**
   * 订单列表
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function main($params = array()){
    $dataObj=$this->model('newadmin');
    $data['new_list']=$dataObj->getNewList();
    $data['List']=$dataObj->newadminOrderList($params);
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'newadmin','evalpage=0'));
    $data['uid']=isset($params['uid'])?intval($params['uid']):0;
    $this->display($data,'newadmin_payListAl');

  }
 
  /**
   * ajax 异步
   * @param  [type] $params [description]
   * @return [type]         [description]
   */
  public function ajax_action($params=array()){
    switch($params['ac']){
      case 'setPa':
        $this->model('newadmin')->setPa($params); // 设置提现状态
        break;
      case 'downxls':
        $this->model('newadmin')->downxls($params); // 设置提现状态
        break;
      default:
        $this->printfail('不存在该请求！');
    }
  }




/*--------not_run------------------------------------------------------------------------------------------------------------------*/
/*--------not_run------------------------------------------------------------------------------------------------------------------*/








}
?>