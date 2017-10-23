<?php
  /**
   * 下级推客明细
   * @author chengyuan  2014-8-6
   *
   */
  header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
  class reportController extends Tuike_controller {
    public $theme_dir = false;
    public function __construct() { 
      parent::__construct();
      $this->assign('nav',4);
    } 
    /**
     * 订单列表
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function main($params = array()) {

      if($_SESSION['newWeiboUse']===1){
        $dataObj=$this->model('report_wb');
        $data['List']=$dataObj->mainList($params);
        $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'report','evalpage=0'));
        $data['count']=$dataObj->getVar('totalcount');
        if( !isset($params['t1'],$params['t2']) || strlen($params['t1']) !== 10 || strlen($params['t2']) !== 10 ){
          $params['t1']=$params['t2']=date('Y-m-d',JIEQI_NOW_TIME);
        }
        $data['t1']=$params['t1'];
        $data['t2']=$params['t2'];
        $this->display($data,'report_list_wb');

      }else{
        $dataObj=$this->model('report');
        $data['List']=$dataObj->mainList($params);
        $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'report','evalpage=0'));
        $data['count']=$dataObj->getVar('totalcount');
        if( !isset($params['t1'],$params['t2']) || strlen($params['t1']) !== 10 || strlen($params['t2']) !== 10 ){
          $params['t1']=$params['t2']=date('Y-m-d',JIEQI_NOW_TIME);
        }
        $data['t1']=$params['t1'];
        $data['t2']=$params['t2'];
        $this->display($data,'report_list');
      }
    }

    /**
     * 下载渠道列表
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function downQdloadList($params=array()){

      if($_SESSION['newWeiboUse']===1){
        if($this->submitcheck()){
          $html=$this->model('report_wb')->downQdloadList($params);
        }
        $this->printfail('信息不正确！');

      }else{
        if($this->submitcheck()){
          $html=$this->model('report')->downQdloadList($params);
        }
        $this->printfail('信息不正确！'); 
               
      }
    }



/*------newRun---------------------------------------------------------------------------------------------------------------------------*/





























}

?>