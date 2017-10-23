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

    if($_SESSION['newWeiboUse']===1){
      $data=$this->model('home_wb')->userBase($params);
      $dataObjQd=$this->model('qdlist_wb');
      $data['weekdata']=$dataObjQd->getWeekDataList();
      $data['yesterdayqd']=$dataObjQd->getYesterdayQdList();
      $data['preweekdata']=$dataObjQd->getPreWeekDataList();
      $this->display($data,'home_index');
    }else{
      $dataObj=$this->model('home');
      $data=$dataObj->userBase($params);
      $dataObjQd=$this->model('qdlist');
      $data['weekdata']=$dataObjQd->getWeekDataList();
      $data['yesterdayqd']=$dataObjQd->getYesterdayQdList();
      $data['preweekdata']=$dataObjQd->getPreWeekDataList();
      $this->display($data,'home_index');
    }
  }

  /**
   * 刷新缓存
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function refreshPage($params=array()){
    $params['refresh']=isset($params['refresh'])?intval($params['refresh']):0;

    if( $params['refresh']===0 )$this->printfail('信息不正确！');
    $redisAr=array(
      '1'=>'weekdatalist_', // 一周数据
      '2'=>'yesterdayqdlist', // 昨日数据详情
      '3'=>'preweekdatalist', // 7日前派单数据
      '4'=>'userbase', // 基本数据统计
      );

    if( !isset($redisAr[$params['refresh']]) )$this->printfail('信息不正确！');

    global $cache_redis;
    $cache_redis->del(JIEQI_REDIS_FIX.$redisAr[$params['refresh']].$_SESSION['jieqiUserId']);
    $this->msgwin('成功');
  }



  /**
   * 修改推客信息
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function edit($params = array()) {
    if($this->submitcheck()){
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


/*-----newRun----------------------------------------------------------------------------------------------------------------------------*/


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



}
?>