<?php
/**
 * 默认控制器
 * @author chengyuan  2014-8-6
 *
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class qdlistController extends Tuike_controller {
  public $theme_dir = false;
  public function __construct() { 
    parent::__construct();
    $this->assign('nav',4);
  } 
  public function main($params = array()) {
    $orderAr=array(
      'id'=>'id',
      'name'=>'name',
      'fee'=>'fee',
      'pdate'=>'pdate'
      );
    $params['order']=isset($params['order']) && $orderAr[$params['order']]?$params['order']:'id';
    $params['orderS']=$orderAr[$params['order']];
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=sort='.$params['sort'];
    $SYS.='&order='.$params['order'];


    $dataObj=$this->model('qdlist');
    $params['limit']=10;
    $params['pageShow']=true;
    $data['qdList']=$dataObj->qdlist($params);

    $data['order']=$params['order'];
    $data['sort']=$params['sort'];

    if( count( $data['qdList'])>0 ){
      $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'qdlist','evalpage=0',$SYS));
    }else{
      $data['page']='';
    }

    $this->display($data,'qdlist');
  }

  /**
   * 渠道的支付记录
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function qdI($params=array()){

    if( !isset($params['qdId']) || $params['qdId'] <=0 )ecs_header('Location: '.$this->geturl(JIEQI_MODULE_NAME, 'qdlist'));
    $orderAr=array(
        'payid'=>'p.payid',
        'money'=>'p.money',
        'paytype'=>'p.paytype',
        'rettime'=>'p.rettime'
        );
    $params['order']=isset($params['order']) && $orderAr[$params['order']]?$params['order']:'payid';
    $params['orderS']=$orderAr[$params['order']];
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=method=qdI&qdId='.$params['qdId'];
    $SYS.='&sort='.$params['sort'];
    $SYS.='&order='.$params['order'];

    $dataObj=$this->model('users');

    $params['limit']=10;
    $params['pageShow']=true;
    $data['payList']=$dataObj->getPayList(0,0,$params); 

    $data['order']=$params['order'];
    $data['sort']=$params['sort'];
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'qdlist','evalpage=0',$SYS));
    $data['qdId']=$params['qdId'];
    $this->display($data,'qdI');
  }

  /**
   * 添加渠道
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function qdAdd($params=array()){
    $data=array();
    $params['aid']=isset($params['aid'])?intval($params['aid']):0;
    if($params['aid'] === 0 )$this->printfail('不存在该文章！');
    $dataObj=$this->model('qdlist');
    if($this->submitcheck()){
      $dataObj->qdAdd($params);
    }else{
      $data['chapterL']=$dataObj->freeChapter($params['aid']);
      if(!$data['chapterL'])$this->printfail('不存在该文章！');
      $data['aid']=$params['aid'];
    }
    $this->display($data,'qdAdd');
  }

  /**
   * 渠道-修改渠道名
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function ajax_qd_n($params=array()){
    $data=array();
    $params['field_i']=isset($params['field_i'])?intval($params['field_i']):0;
    $params['name']=isset($params['field_v'])?trim($params['field_v']):'';
    if( $params['field_i']===0 || $params['name']==='' )$this->printfail('信息不正确！');
    $this->model('qdlist')->ajax_qd_n_m($params);
  }


  /**
   * 渠道-删除
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function delete_qd($params=array()){
    $data=array();
    $params['id']=isset($params['id'])?intval($params['id']):0;
    if( $params['id']===0 )$this->printfail('信息不正确！');
    $this->model('qdlist')->delete_qd_m($params);
  }


  /**
   * 添加渠道
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function ajax($params=array()){

    switch($params['type']){
      // case 'qdCheck':
      //   $this->model('qdlist')->qdCheck($params);
      //   break;
      default:
        $this->printfail('不存在该类型');
    }
  }





}

?>