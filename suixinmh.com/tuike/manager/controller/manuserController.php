<?php
  /**
   * 下级推客明细
   * @author chengyuan  2014-8-6
   *
   */
  header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
  class manuserController extends Tuike_controller {
    public $theme_dir = false;
    public function __construct() { 
    parent::__construct();
    $this->assign('nav',2);
  } 
  /**
   * 经理的所有推客列表
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function main($params = array()) {
    $dataObj=$this->model('manuser');
    $data['ktList']=$dataObj->maUserPay($params);
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'manuser','evalpage=0'));
    $data['urlTkShare']=str_replace('/manager','',JIEQI_URL).'/login?tku='.$_SESSION['jieqiUserId'];
    $this->display($data,'manuser_list');
  }
  /**
   *  某个推客的所有渠道
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function qdlist($params = array()) {
    $params['uid']=isset($params['uid'])?intval($params['uid']):0;
    if($params['uid'] === 0)$this->printfail('不存在该推客！');
    $dataObj=$this->model('qdlist');
    $data['qdList']=$dataObj->qdlist($params);
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'manuser','evalpage=0','SYS=method=qdlist'));
    $data['tuiInfo']=$this->model('manuser')->qdinfo($params);         
    $this->display($data,'manuser_qdlist');
  }
  /**
   * 某个推客下所有渠道的充值记录
   * @return [type] [description]
   */
  public function paylist($params = array()){ 
    $params['uid']=isset($params['uid'])?intval($params['uid']):0;
    if($params['uid'] === 0)$this->printfail('不存在该推客！');
    $dataObj = $this->model('pay');
    $data['payList']= $dataObj->getPayList($params);      
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'manuser','evalpage=0','SYS=method=paylist'));
    $data['tuiInfo']=$this->model('manuser')->qdinfo($params);
    $this->display($data,'manuser_paylist');
  }
  /**
   * 某个推客下所有下级推客
   * @return [type] [description]
   */
  public function tkulist($params = array()){ 
    $params['uid']=isset($params['uid'])?intval($params['uid']):0;
    $params['tkId']=isset($params['tkId'])?intval($params['tkId']):0;
    if($params['uid'] === 0 &&  $params['tkId']===0)$this->printfail('不存在该推客！');
    $dataObj=$this->model('manuser');
    $data['ktList']=$dataObj->maUserPay($params);
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'manuser','evalpage=0'));
    $data['tuiInfo']=$this->model('manuser')->qdinfo(array('uid'=>$params['tkId']));    
    $this->display($data,'manuser_tkulist');
 }
  /**
   * 某个推客下的每日结算
   * @return [type] [description]
   */
  public function paydaylist($params = array()){ 
    $params['uid']=isset($params['uid'])?intval($params['uid']):0;
    if($params['uid'] === 0)$this->printfail('不存在该推客！');
    $dataObj = $this->model('paylog');
    $data['paylogList']= $dataObj->payLogListDay($params); 
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'manuser','evalpage=0','SYS=method=paydaylist'));
    $data['tuiInfo']=$this->model('manuser')->qdinfo($params);
    $this->display($data,'manuser_paylistday');
  }


  /**
   * 查看结算明细（推广添加的） sql
   * @return [type] [description] payId
   */
  public function paysettle($params = array()){ 

    if( !isset($params['payId']) || $params['payId'] <=0 )$this->printfail('不存在该结算记录！');

    $dataObj=$this->model('pay');
    $data['paylog']=$dataObj->getPaylog($params['payId']); ;
    if( !$data['paylog'] || $data['paylog']['type'] == 3 )$this->printfail('结算没有详细页面！');
    $params['t1']=$data['paylog']['t1'];
    $params['t2']=$data['paylog']['t2'];
    $params['uid']=$data['paylog']['uid'];

    if( $data['paylog']['type'] ==1 ){
      $data['pay_syn_money_old']=PAY_SYN_MONEY_TK;
      $data['payList']=$dataObj->getPayListTk($params);
    }else{
      $data['pay_syn_money_old']=PAY_SYN_MONEY_QD;
      $data['payList']=$dataObj->getPayList($params);
    }
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'manuser','evalpage=0','SYS=method=paysettle'));
    $this->display($data,'manuser_paysettle');
  }



  /**
   * 某个推客下的详细信息
   * @return [type] [description]
   */
  public function tkuinfo($params = array()){ 
    $params['uid']=isset($params['uid'])?intval($params['uid']):0;
    if($params['uid'] === 0)$this->printfail('不存在该推客！');

    if($this->submitcheck()){
      $this->model('manuser')->tkInfoEdit($params);
    }
    $data['info']=$this->model('manuser')->tkInfo($params);
    $this->display($data,'manuser_tkuinfo');
 }



  /**
   * ajax 异步
   * @param  [type] $params [description]
   * @return [type]         [description]
   */
  public function ajax($params=array()){
    switch($params['ac']){
      case 'setPa':
        $this->model('manuser')->setPa($params); // 设置提现状态
        break;
      default:
        $this->printfail('不存在该请求！');
    }
  }







}

?>