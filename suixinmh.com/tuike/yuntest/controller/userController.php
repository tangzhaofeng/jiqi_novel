<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class userController extends Tuike_controller { 
    public $template_name = 'login'; 
    public $caching = false;
    public $theme_dir = false;
    public function __construct() { 
      parent::__construct();
      $this->assign('nav',6);
    }
    public function main($params = array()){
  
        if($this->checklogin(true)) {
            ecs_header('location:'.$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url']);exit;
        }
        $dataObj = $this->model('login');
        $data = $dataObj->main($params);
        $data['ujumpurl'] = urlencode($data['jumpurl']);
        $this->display($data);
    }

    /**
     * 退出登录
     * @return [type] [description]
     */
    public function logout(){ 
        $dataObj = $this->model('users');
        $dataObj->logout();
    }

    /**
     * 收款信息
     * @return [type] [description]
     */
    public function payreceive($params = array()){ 
        $dataObj = $this->model('users');
        if($this->submitcheck()) {
            $dataObj->payreceive($params);
        }else{
            $data['user']=$dataObj->getUser();
        }
        $this->display($data,'users_payreceive');
    }

    /**
     * 提现功能
     * @return [type] [description]
     */
    public function payout($params = array()){ 
        $dataObj = $this->model('users');
             
        if($this->submitcheck()) {
            $dataObj->payoutRun($params);
        }else{
            $params['limit']=10;
            $params['pageShow']=true;
            $data['payoutList']=$dataObj->payoutList($params);
            $SYS='SYS=method=payout';
            $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'user','evalpage=0',$SYS));
        }

        $data['minimum_money']=MINIMUM_MONEY;
        $this->display($data,'users_payout');
    }

    /**
     * 所有的会员充值记录（推广添加的）
     * @return [type] [description]
     */
    public function paydetail($params = array()){ 
        $dataObj = $this->model('users');
             
        if($this->submitcheck()) {
            $dataObj->payreceive($params);
        }else{
            $params['limit']=10;
            $params['pageShow']=true;
            $params['orderS']='p.rettime';
            $data['payList']= $dataObj->getPayList(0,0,$params);      
            $SYS='SYS=method=paydetail';
            $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'user','evalpage=0',$SYS));
        }
        $this->display($data,'users_paydetail');
    }

    /**
     * 查看结算明细（推广添加的） sql
     * @return [type] [description] payId
     */
    public function paysettle($params = array()){ 

        if( !isset($params['payId']) || $params['payId'] <=0 )ecs_header('Location: '.$this->geturl(JIEQI_MODULE_NAME, 'user','SYS=method=payout'));
 
        $SYS='SYS=method=paysettle&payId='.$params['payId'];
 
        $dataObj=$this->model('users');
        $data['paylog']=$dataObj->getPaylog($params['payId']); ;
        if( !$data['paylog'] || $data['paylog']['type'] == 3 )$this->printfail('提现没有详细页面！');
        $params['t1']=$data['paylog']['t1'];
        $params['t2']=$data['paylog']['t2'];
        $params['limit']=10;
        $params['pageShow']=true;
        if( $data['paylog']['type'] ==1 ){
            $data['pay_syn_money_old']=PAY_SYN_MONEY_TK;
            $data['payList']=$dataObj->getPayListTk($params);
        }else{
            $data['pay_syn_money_old']=PAY_SYN_MONEY_QD;
            $data['payList']=$dataObj->getPayList(0,0,$params);
        }
        $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'user','evalpage=0',$SYS));

        $data['payId']=$params['payId'];
        $this->display($data,'users_paysettle');
    }





}
?>