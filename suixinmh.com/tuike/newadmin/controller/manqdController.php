<?php
  /**
   * 下级推客明细
   * @author chengyuan  2014-8-6
   *
   */
  header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
  class manqdController extends Tuike_controller {
    public $theme_dir = false;
    public function __construct() { 
      parent::__construct();
      $this->assign('nav',2);
    } 
    /**
     * 下级推客列表
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function main($params = array()) {
      if($_SESSION['newWeiboUse']===1){
        $dataObj=$this->model('qdlist_wb');
        $data['qdList']=$dataObj->manQdList($params);
        if( !isset($params['t1'],$params['t2']) || strlen($params['t1']) !== 10 || strlen($params['t2']) !== 10 ){
          $params['t1']=$params['t2']=date('Y-m-d',JIEQI_NOW_TIME);
        }
        $data['t1']=$params['t1'];
        $data['t2']=$params['t2'];
        $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'manqd','evalpage=0'));
        $this->display($data,'manqd_qdlist_wb');
      }else{
        $dataObj=$this->model('qdlist');
        $data['qdList']=$dataObj->manQdList($params);
        if( !isset($params['t1'],$params['t2']) || strlen($params['t1']) !== 10 || strlen($params['t2']) !== 10 ){
          $params['t1']=$params['t2']=date('Y-m-d',JIEQI_NOW_TIME);
        }
        $data['t1']=$params['t1'];
        $data['t2']=$params['t2'];
        $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'manqd','evalpage=0'));
        $this->display($data,'manqd_qdlist');
      }
    }
    /**
     * 修改订单
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function viewOrder($params=array()){
      if($_SESSION['newWeiboUse']===1){
        $params['oid']=isset($params['oid'])?intval($params['oid']):0;
        if( $params['oid']===0 )$this->printfail('不存在该订单！');
        $dataObj=$this->model('qdlist_wb');
        if($this->submitcheck()){
          $dataObj->editOrderQdList($params);
        }           
        $html=$dataObj->getOrderQdList($params);
        if( $html === '' )$this->printfail('还没添加渠道！');
        die( $html);
      }else{
        $params['oid']=isset($params['oid'])?intval($params['oid']):0;
        if( $params['oid']===0 )$this->printfail('不存在该订单！');
        $dataObj=$this->model('qdlist');
        if($this->submitcheck()){
          $dataObj->editOrderQdList($params);
        }           
        $html=$dataObj->getOrderQdList($params);
        if( $html === '' )$this->printfail('还没添加渠道！');
        die( $html);
      }
    }

    /**
     * 渠道充值明细
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function qdpaylist($params = array()) {

      if($_SESSION['newWeiboUse']===1){
        if( !isset($params['qdId']) || $params['qdId'] <=0 )ecs_header('Location: '.$this->geturl(JIEQI_MODULE_NAME, 'manqd'));
        $dataObj=$this->model('pay_wb');
        $data['payList']=$dataObj->getPayList($params); 
        if( !isset($params['t1'],$params['t2']) || strlen($params['t1']) !== 10 || strlen($params['t2']) !== 10 ){
          $params['t1']=$params['t2']=date('Y-m-d',JIEQI_NOW_TIME);
        }
        $data['t1']=$params['t1'];
        $data['t2']=$params['t2'];
        $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'manqd','evalpage=0','SYS=method=qdpaylist'));
        $data['totalMoney']=$dataObj->getVar('totalMoney');
        $data['totalcount']=$dataObj->getVar('totalcount');
        $this->display($data,'manqd_qdpaylist');

      }else{
        if( !isset($params['qdId']) || $params['qdId'] <=0 )ecs_header('Location: '.$this->geturl(JIEQI_MODULE_NAME, 'manqd'));
        $dataObj=$this->model('pay');
        $data['payList']=$dataObj->getPayList($params); 
        if( !isset($params['t1'],$params['t2']) || strlen($params['t1']) !== 10 || strlen($params['t2']) !== 10 ){
          $params['t1']=$params['t2']=date('Y-m-d',JIEQI_NOW_TIME);
        }
        $data['t1']=$params['t1'];
        $data['t2']=$params['t2'];
        $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'manqd','evalpage=0','SYS=method=qdpaylist'));
        $data['totalMoney']=$dataObj->getVar('totalMoney');
        $data['totalcount']=$dataObj->getVar('totalcount');
        $this->display($data,'manqd_qdpaylist');
      }
    }



    /**
     * 渠道充值明细
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function viewSynQdpaylist($params = array()) {


      if($_SESSION['newWeiboUse']===1){
        $params['id']=isset($params['id'])?intval($params['id']):0;
        $params['qqd']=isset($params['qd'])?trim($params['qd']):'';
        $params['pageShow']=false;
        $params['limit']=1000;
        $params['page']=1;
        $params['orderS']='q.statime';
        $params['sort']='DESC';

        if( $params['id']===0 || $params['qqd']==='' )$this->printfail('信息不正确！');
        $dataObj=$this->model('qdlist_wb');
        $dataQdList=$dataObj->manQdList($params);


        $url=geturl('newadmin','manqd','SYS=method=qdpaylist');
        $url2=geturl('newadmin','order');
        $str='';
        foreach($dataQdList as $v){
          $str.='<tr class="qdp_'.$params['id'].'" style="background: #c0d8c0;">
                  <td>'.$v['id'].'</td>
                  <td>'.$v['qd'].'</td>
                  <td>'.$v['qdNameNum'].'</td>
                  <td>'.date('Y-m-d H:i:s',$v['statime']).'</td>
                  <td>'.($v['endtime']==4294967295?'到现在':date('Y-m-d H:i:s',$v['endtime'])).'</td>
                  <td><a target="_blank" href="'.$url2.'?oid='.$v['oid'].'">'.$v['ordersn'].'</a></td>
                  <td>'.$v['name'].'</td>
                  <td>'.$v['fans'].'</td>
                  <td title="'.$v['arInfo'].'">'.truncate($v['arInfo'],8,'…').'</td>
                  <td>'.$v['qdclick'].'</td>
                  <td>'.$v['qdreg'].'</td>
                  <td>'.$v['payusers'].'</td>
                  <td>'.$v['qdpay'].'</td>
                  <td>'.$v['fee'].'</td>
                  <td>'.$v['hb'].'%</td>
                  <td>
                    <a class="u-btn u-btn-primary" href="'.$url.'?qdId='.$v['id'].'">查看</a>
                  </td> 
                </tr>';
        }
        if( $str==='' )$this->printfail('信息不正确！');
        echo '{"status":"OK","msg":"","jumpurl":""}aj||ax'.$str;
        die();
      }else{
        $this->printfail('信息不正确！');
      }
    }



















/*------newRun---------------------------------------------------------------------------------------------------------------------------*/




























}

?>