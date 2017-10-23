<?php
/**
 * 下级推客明细
 * @author chengyuan  2014-8-6
 *
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class tkuserController extends Tuike_controller {
  public $theme_dir = false;
  public function __construct() { 
    parent::__construct();
    $this->assign('nav',5);
  } 
  /**
   * 下级推客列表
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function main($params = array()) {
    $orderAr=array(
      'id'=>'u.uid',
      'name'=>'u.name',
      'reg_time'=>'u.reg_time',
      );
    $params['order']=isset($params['order']) && $orderAr[$params['order']]?$params['order']:'id';
    $params['orderS']=$orderAr[$params['order']];
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=sort='.$params['sort'];
    $SYS.='&order='.$params['order'];


    $dataObj=$this->model('tkusers');
    $params['limit']=10;
    $params['pageShow']=true;
    $data['ktList']=$dataObj->ktUserPay($params);
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'qduser','evalpage=0',$SYS));
    $data['order']=$params['order'];
    $data['sort']=$params['sort'];
    $data['urlTkShare']=JIEQI_URL.'/login?tku='.$_SESSION['jieqiUserId'];

    $this->display($data,'tk_user');
  }

  /**
   *  下级推客的详细信息
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function tkuserI($params = array()) {
         
    if( !isset($params['tkId']) || $params['tkId'] <=0 )ecs_header('Location: '.$this->geturl(JIEQI_MODULE_NAME, 'qduser'));
    $orderAr=array(
        'payid'=>'p.payid',
        'money'=>'p.money',
        'paytype'=>'p.paytype',
        'rettime'=>'p.rettime'
        );
    $params['order']=isset($params['order']) && $orderAr[$params['order']]?$params['order']:'payid';
    $params['orderS']=$orderAr[$params['order']];
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=method=tkuserI&tkId='.$params['tkId'];
    $SYS.='&sort='.$params['sort'];
    $SYS.='&order='.$params['order'];


    $params['limit']=10;
    $params['pageShow']=true;
    $dataObj=$this->model('tkusers');
    $data['tkInfo']=$dataObj->getTkInfo($params['tkId']); 
    if(!$data['tkInfo'])$this->printfail('不存在下级推客！');
    $data['ktList']=$dataObj->ktUserPayOne($params); 
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'tkuser','evalpage=0',$SYS));


    $data['order']=$params['order'];
    $data['sort']=$params['sort'];
    $data['tkId']=$params['tkId'];

    $this->display($data,'tk_userI');
  }




}

?>