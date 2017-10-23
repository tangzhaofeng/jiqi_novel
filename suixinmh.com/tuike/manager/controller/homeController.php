<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class homeController extends Tuike_controller { 
  public $template_name = 'home'; 
  public $caching = false;
  public $theme_dir = false;
  public function __construct() { 
    parent::__construct();
    $this->assign('nav',1);
  }
  public function main($params = array()){
    $dataObj=$this->model('home');
    $data=$dataObj->userBase($params);
    $data['manTkList']=$this->model('qdlist')->getManTkList();
    $this->display($data,'home_index');
  }


  /**
   * 每日推客的收入统计
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function tuDayList($params = array()){
     $orderAr=array(
      'id'=>'id',
      'name'=>'name',
      'fee'=>'fee',
      'pdate'=>'pdate'
      );
    $params['date']=isset($params['date'])?trim($params['date']):0;
    if($params['date'] === 0)$this->printfail('不存在该日期！');
    $params['order']=isset($params['order']) && $orderAr[$params['order']]?$params['order']:'id';
    $params['orderS']=$orderAr[$params['order']];
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=method=tuDayList&sort='.$params['sort'];
    $SYS.='&order='.$params['order'].'&date='.$params['date'];
    $dataObj=$this->model('qdlist');
    $params['limit']=10;
    $params['pageShow']=true;
    $data['qdList']=$dataObj->tuDayList($params);
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'home','evalpage=0',$SYS));
    $data['date']=$params['date'];
    $this->display($data,'home_tudaylist');
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