<?php
/**
 * Ä¬ÈÏ¿ØÖÆÆ÷
 * @author chengyuan  2014-8-6
 *
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class qduserController extends Tuike_controller {
  public $theme_dir = false;

  public function __construct() { 
    parent::__construct();
    $this->assign('nav',2);
  } 
  public function main($params = array()) {
    $orderAr=array(
      'uid'=>'u.uid',
      'uname'=>'u.uname',
      'name'=>'u.name',
      'regdate'=>'u.regdate'
      );
    $params['order']=isset($params['order']) && $orderAr[$params['order']]?$params['order']:'uname';
    $params['orderS']=$orderAr[$params['order']];
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=sort='.$params['sort'];
    $SYS.='&order='.$params['order'];


    $dataObj=$this->model('qdusers');
    $params['limit']=10;
    $params['pageShow']=true;
    $data['userPayList']=$dataObj->userPay($params);

    $data['order']=$params['order'];
    $data['sort']=$params['sort'];
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'qduser','evalpage=0',$SYS));

    $this->display($data,'qduser');
  }
  /**
   *  
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function qduserI($params = array()) {
    if( !isset($params['qdUid']) || $params['qdUid'] <=0 )ecs_header('Location: '.$this->geturl(JIEQI_MODULE_NAME, 'qduser'));
    $orderAr=array(
        'payid'=>'p.payid',
        'money'=>'p.money',
        'paytype'=>'p.paytype',
        'rettime'=>'p.rettime'
        );
    $params['order']=isset($params['order']) && $orderAr[$params['order']]?$params['order']:'payid';
    $params['orderS']=$orderAr[$params['order']];
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=method=qduserI&qdUid='.$params['qdUid'];
    $SYS.='&sort='.$params['sort'];
    $SYS.='&order='.$params['order'];

    $dataObj=$this->model('users');

    $params['limit']=10;
    $params['pageShow']=true;
    $data['payList']=$dataObj->getPayList(0,0,$params); 

    $data['order']=$params['order'];
    $data['sort']=$params['sort'];
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'qduser','evalpage=0',$SYS));
    $data['qdUid']=$params['qdUid'];
    $this->display($data,'qduserI');
  }




}

?>