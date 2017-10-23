<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class homeController extends Tuike_controller { 
  public $template_name = 'home'; 
  public $caching = false;
  public $theme_dir = false;
  public function __construct() { 
    parent::__construct();
    $this->assign('nav',6);
  }
  public function main($params = array()){
    $params['payflag']=isset($params['payflag'])?intval($params['payflag']):0;
    $params['order']=isset($params['order']) && $orderAr[$params['order']]?$params['order']:'id';
    $params['orderS']=$orderAr[$params['order']];
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=sort='.$params['sort'];
    $SYS.='&order='.$params['order'];
    if($params['payflag']>0)  $SYS.='&payflag='.$params['payflag'];

    $dataObj=$this->model('home');
    $params['limit']=10;
    $params['pageShow']=true;
    $data['payList']=$dataObj->payLogList($params);

    $data['order']=$params['order'];
    $data['sort']=$params['sort'];

    switch($params['payflag']){
      case 2:
        $data['smoney']=$dataObj->payLogBase();
        $data['totalcount']=$dataObj->getVar('totalcount');
        $display_html='home_payListRun';
        break;
      case 6:
        $display_html='home_payListPayWai';
        break;
      case 7:
        $display_html='home_payListWai';
        break;
      case 3:
        if( isset($params['t1'],$params['t2']) ){
          $data['t1']=$params['t1'];
          $data['t2']=$params['t2'];
          $SYS.='&t1='.$params['t1'].'&t2='.$params['t2'];
        };
        $display_html='home_payListFin';
        break;
      case 8:
        $display_html='home_payListFail';
        break;
      default:

        $display_html='home_payListAl';
        $this->display($data,'home_payListAl');
    }

    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'home','evalpage=0',$SYS));
    $this->display($data,$display_html);




  }
 


  /**
   * ajax 异步
   * @param  [type] $params [description]
   * @return [type]         [description]
   */
  public function ajax_action($params=array()){

    switch($params['ac']){
      case 'setPa':
        $this->model('home')->setPa($params); // 设置提现状态
        break;
      case 'addPayRun':
        $this->model('home')->addPayRun($params); // 生成处理列表
        break;
      case 'downloadList':
        $this->model('home')->downloadList($params); // 下载列表
        break;
      case 'completeList':
        $this->model('home')->completeList(); // 设置处理列表为已完成
        break;
      default:
        $this->printfail('不存在该请求！');
    }
  }

  /**
   * 退出登录
   * @return [type] [description]
   */
  public function logout(){ 
    $dataObj = $this->model('home');
    $dataObj->logout();
  }

}
?>