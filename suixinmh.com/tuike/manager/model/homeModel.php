<?php 
  /** 
   * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
   * @author      gaoli* @version     1.0 
   */ 
  class homeModel extends Model{
    public function main($params){
      global $jieqiConfigs;
      jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
      if($this->submitcheck()){
        $this->loginDo($params);
      }
      $data = array();
      return $data;
    }

  /**
   * 经理的基本信息
   * @return [type] [description]
   */
  public function userBase($params){
    global $cache_redis;
    if( $cache_redis->isExists(JIEQI_REDIS_FIX.'userbase'.$_SESSION['jieqiUserId']) ){
      return unserialize( $cache_redis->get(JIEQI_REDIS_FIX.'userbase'.$_SESSION['jieqiUserId']) );
    }
    // 获取当日充值
    $t1 = strtotime(date("Y-m-d"));
    $this->db->setCriteria(new Criteria('rettime', $t1 , ">="));
    $this->db->criteria->add(new Criteria('p.payflag', 1)); 
    $this->db->criteria->add(new Criteria('u.mauid', $_SESSION['jieqiUserId']));  
    $q = jieqi_dbprefix('pay_paylog').' p LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON p.source=q.qd LEFT JOIN '.
      jieqi_dbprefix('tuike_users').' u ON u.uid=q.uid ';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('count(distinct p.buyid) as payusers,p.buyid,round(sum(p.money)/100) as smoney');
    $this->db->queryObjects();
    $result = $this->db->getRow();
    if($result['payusers'] <= 0){
      $data['dayCzN'] = 0;
      $data['dayCz'] = 0;
    }else{
      $data['dayCzN'] = $result['payusers'];
      $data['dayCz'] = $result['smoney'];
    }


    // 获取当月充值
    $t1=strtotime( date('Y-m-01').' 00:00:00' ); 
    $this->db->setCriteria(new Criteria('rettime', $t1 , ">="));
    $this->db->criteria->add(new Criteria('p.payflag', 1)); 
    $this->db->criteria->add(new Criteria('u.mauid', $_SESSION['jieqiUserId']));  
    $q = jieqi_dbprefix('pay_paylog').' p LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON p.source=q.qd LEFT JOIN '.
      jieqi_dbprefix('tuike_users').' u ON u.uid=q.uid ';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('count(distinct p.buyid) as payusers,round(sum(p.money)/100) as smoney');
    $this->db->queryObjects();
    $result = $this->db->getRow();
    $data['MonthCz'] = $result['smoney']?$result['smoney']:0;
    if($result['payusers'] <= 0){
      $data['MonthCzN'] = 0;
      $data['MonthCz'] = 0;
    }else{
      $data['MonthCzN'] = $result['payusers'];
      $data['MonthCz'] = $result['smoney'];
    }


    // 获取累计充值
    $this->db->setCriteria(new Criteria('u.mauid', $_SESSION['jieqiUserId']));
    $this->db->criteria->add(new Criteria('p.payflag', 1)); 
    $q = jieqi_dbprefix('pay_paylog').' p LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON p.source=q.qd LEFT JOIN '.
      jieqi_dbprefix('tuike_users').' u ON u.uid=q.uid ';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('count(distinct p.buyid) as payusers,round(sum(p.money)/100) as smoney');
    $this->db->queryObjects($this->db->criteria);
    $result = $this->db->getRow();
    if($result['payusers'] <= 0){
      $data['allCzN'] = 0;
      $data['allCz'] = 0;
    }else{
      $data['allCzN'] = $result['payusers'];
      $data['allCz'] = $result['smoney'];
    }


    // 累计渠道点击量、PV量
    $this->db->setCriteria(new Criteria('u.mauid', $_SESSION['jieqiUserId']));
    $q = jieqi_dbprefix('system_qddata').' qd LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON qd.qd=q.qd LEFT JOIN '.
      jieqi_dbprefix('tuike_users').' u ON u.uid=q.uid ';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('sum(qd.click) as allclick,sum(qd.pv) as allpv');
    $this->db->queryObjects($this->db->criteria);
    $result = $this->db->getRow();
    if($result['allpv'] <= 0){
      $data['allclick'] = 0;
      $data['allpv'] = 0;
    }else{
      $data['allclick'] = $result['allclick'];
      $data['allpv'] = $result['allpv'];
    }
    
    $time=180;
    $cache_redis->set(JIEQI_REDIS_FIX.'userbase'.$_SESSION['jieqiUserId'],serialize($data),$time);
    return $data;
  } 


    /**
     * 编辑推客信息
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    function editPass($params=array()){
        $params['oldpass']=isset($params['oldpass'])?trim($params['oldpass']):'';
        $params['oldpass1']=isset($params['oldpass1'])?trim($params['oldpass1']):'';
        $params['oldpass2']=isset($params['oldpass2'])?trim($params['oldpass2']):'';
        if( $params['oldpass'] === '' ||
            $params['oldpass1'] === '' ||
            $params['oldpass2'] === '' 
         )$this->printfail('信息不正确！');
        if( $params['oldpass1'] !== $params['oldpass2'] )$this->printfail('再个新密码不一样！');


        $this->db->init('users','uid','tuike');
        $users=$this->db->get($_SESSION['jieqiUserId']);
        if( md5($params['oldpass']) !== $users['pass'] )$this->printfail('旧密码错误，请联系您的客户经理！');
        if( md5($params['oldpass1']) === $users['pass'] )$this->printfail('新密码和旧密码相同，不用修改！');

        $data=array('pass'=>md5($params['oldpass2']));
        $this->db->edit($_SESSION['jieqiUserId'],$data);

        if( $this->db->getAffectedRows() > 0 ){
            $this->msgwin('修改成功！');
          die(json_encode(array('status'=>'OK','jumpurl'=>$this->geturl(JIEQI_MODULE_NAME,'server','home').'?payflag=2')));
        }else{
          $this->printfail('修改失败！');
        }
    }




  /**
   * 退出登陆
   * @return [type] [description]
   */
  public function logout(){
    $_REQUEST = $this->getRequest();
    
    header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
    if (!empty($_COOKIE['jieqiUserInfoTk'])){
      @setcookie('jieqiUserInfoTk', '', 0, '/', JIEQI_COOKIE_DOMAIN, 0);
    }

    if (!empty($_COOKIE[session_name()])){
      @setcookie(session_name(), '', 0, '/', JIEQI_COOKIE_DOMAIN, 0);
    }
    setcookie($_SESSION['jieqiUserId'],JIEQI_NOW_TIME,JIEQI_NOW_TIME+99999999,'/',JIEQI_COOKIE_DOMAIN,0);
    $_SESSION = array();
    @session_destroy();
    
    if(empty($_REQUEST['jumpurl']))$this->geturl(JIEQI_MODULE_NAME, 'login');
    ecs_header('location:'.$_REQUEST['jumpurl']);
  } 

































} 
?>