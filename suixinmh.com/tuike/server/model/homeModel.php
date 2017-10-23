<?php 
  /** 
   * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
   * @author      gaoli* @version     1.0 
   */ 
  class homeModel extends Model{
    //login form
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
   * 提现的记录
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function payLogList($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){

    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    
    /* 条件 */ 
    $this->db->setCriteria( new Criteria('p.type',3) );
    if( $params['payflag'] >0 ){
      $this->db->criteria->add( new Criteria('p.payflag',$params['payflag']) );
    }

    $params['orderS']='date';  
    $params['sort']=0;


    if( isset($params['t1'],$params['t2']) ){

      $t1=strtotime($params['t1']);
      $t2=strtotime($params['t2'].' 23:59:59');
      if( $t1<$t2 ){
        $this->db->criteria->add( new Criteria('p.updatetime',$t1,'>=') );
        $this->db->criteria->add( new Criteria('p.updatetime',$t2,'<=') );
      }else{
        unset( $params['t1'],$params['t2'] );
      }
    }


// var_dump( $params );
// die();

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));
    /* 联表和执行 */
    $q = jieqi_dbprefix('tuike_paylog').' p LEFT JOIN '.jieqi_dbprefix('tuike_users').' u ON p.uid=u.uid'; 
    $this->db->criteria->setTables($q);


    $this->db->criteria->setFields("p.*,u.uname,u.p_type,u.balance,u.balancey,u.balancer,u.p_type,u.p_ali,u.p_bank,u.p_bankn,u.p_wechat,u.p_uname,u.p_mobil,u.p_qq,count(distinct p.payid) as payusers,round(sum(money)) as money");
    $this->db->criteria->setGroupby("p.uid,p.updatetime");
    
    
    // define('JIEQI_DEBUG_MODE',1);

    $this->db->queryObjects();
    $payTy=$GLOBALS['GLO_PAYLOG_PAY_TYPE'];
 
    // var_dump( $this->db->sqllog('ret') );
    // die();

    /* 处理数据 */
    $ar=array();
    while($row=$this->db->getRow()){
      $row['state']=$payTy[$row['payflag']];

      if( $row['p_type']==1 ){
        $row['p_info']=$row['p_ali'];
        $row['type']='支付宝';
      }elseif($row['p_type']==3){
        $row['p_info']=$row['p_bank'].'<br />'.$row['p_bankn'];
        $row['type']='银行卡';
      }else{
        $row['type']='未设置';
        $row['p_info']='未设置';
      }

      $ar[]=$row;
    }

    /* 分页 */
    if ($params['pageShow']) {
      $sql='SELECT COUNT(*) FROM ' . $this->db->criteria->getTables() . ' ' . $this->db->criteria->renderWhere() . ' GROUP BY ' . $this->db->criteria->getGroupby();
      $nobuffer = false;
      $result = $this->db->query($sql, 0, 0, $nobuffer);
      if (!$result){
        $count=0;
      }else{
        $count = $this->db->getRowsNum($result);
      }
      $this->setVar('totalcount', $count);

      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }

  /**
   * 该处理列表的信息
   * @return [type] [description]
   */
  public function payLogBase(){

 // 查询是否有未处理的列表
    $this->db->init('paylog','payid','tuike');
    $this->db->setCriteria( new Criteria('type',3) );
    $this->db->criteria->add( new Criteria('payflag',2) );
    $this->db->criteria->setFields('sum(money) as smoney');
    $this->db->queryObjects();
    $result = $this->db->getRow();
    return $result['smoney']?$result['smoney']:0;
  }




  /**
   * 生成处理列表
   * @param array $params [description]
   */
  public function addPayRun(){
    // // 查询是否有未处理的列表
    // $this->db->init('paylog','payid','tuike');
    // $this->db->setCriteria( new Criteria('type',3) );
    // $this->db->criteria->add( new Criteria('payflag',2) );
    // if( $this->db->getCount($this->db->criteria) != '0' )$this->printfail('还有未处理的列表,请先处理！');

    $sql="UPDATE ".jieqi_dbprefix('tuike_paylog').' SET `payflag`=2 WHERE type=3 AND payflag=7';
    $this->db->query($sql);
    // 修改是否成功
    if( $this->db->getAffectedRows() === 0 ){
      $this->printfail('生成列表失败！');
    }else{
      die(json_encode(array('status'=>'OK','jumpurl'=>$this->geturl(JIEQI_MODULE_NAME,'server','home').'?payflag=2')));
    }
  }

  /**
   * 设置提现请求的状态
   * @param array $params [description]
   */
  public function setPa($params=array()){

    $payid=isset($params['id'])?intval($params['id']):0;
    $payflag=isset($params['ty'])?intval($params['ty']):0;
    $n=isset($params['n'])?intval($params['n']):0;
    $u=isset($params['u'])?intval($params['u']):0;
    
    $infoSt=!empty($params['infoSe'])?$params['infoSe']:(!empty($params['infoTex'])?$params['infoTex']:'');
    if( $n === 0 )$this->printfail('请求不正确！');
    if( $payid === 0 || $payflag === 0 )$this->printfail('请求不正确！');

    switch($payflag){
      case 3:
        break;
      case 6:
        break;
      case 8:
        if(empty($infoSt))$this->printfail('请设置失败的原因！');
        break;
      default:
        $this->printfail('不存在该请求！');
    }

    $arr=array();
    if( $n > 1 ){
      if( $u === 0 )$this->printfail('请求不正确！');
      $this->db->init('paylog','payid','tuike');
      $this->db->setCriteria( new Criteria('uid',$u) );
      $this->db->criteria->add( new Criteria('type',3) );

      switch($payflag){
        case 3:
          $this->db->criteria->add( new Criteria('payflag',6) );
          break;
        case 6:
          $this->db->criteria->add( new Criteria('payflag',2) );
          break;
        case 8:
          $this->db->criteria->add( new Criteria('payflag',2) );
          break;
        default:
      }

      $this->db->queryObjects();
      while($v=$this->db->getRow()){$arr[]=$v; }
    }else{
      $this->db->init('paylog','payid','tuike');
      $arr['0']=$this->db->get($payid);
    }


    $flag=0;

     // '1'=>'待审核','2'=>'支付中','3'=>'已完成','6'=>'待付款','7'=>'待支付','8'=>'支付失败','9'=>'审核失败'
    foreach($arr as $paylog){
      $payid=$paylog['payid'];
      $set='';
      switch($payflag){
        case 2:
          $where=' AND payflag=7 AND payid='.$payid;
          break;
        case 3:
          $where=' AND (payflag=2 OR payflag=6) AND payid='.$payid;
          $set=',updatetime='.JIEQI_NOW_TIME;
          // 若设置成功则从待提现中减去，并加到已提现中
          break;
        case 6:
          $where=' AND payflag=2 AND payid='.$payid;
          break;
        case 7:
          $where=' AND payflag=2 AND payid='.$payid;
          break;
        case 8:
          $where=' AND payflag=2 AND payid='.$payid;
          $set=',updatetime='.JIEQI_NOW_TIME.',erinfo="'.$infoSt.'"';
          break;
        case 9:
          $where=' AND payflag=7 AND payid='.$payid;
          $set=',updatetime='.JIEQI_NOW_TIME;
          // 若设置成功则从待提现中减去
          break;
        default:
          $this->printfail('不存在该请求！');
      }

      // 开始添加事务
      $this->db->query('START TRANSACTION');
      $sql="UPDATE ".jieqi_dbprefix('tuike_paylog').' SET `payflag`='.$payflag.$set.' WHERE type=3'.$where.' limit 1';
      $this->db->query($sql);

      // 修改是否成功
      if( $this->db->getAffectedRows() <= 0 ){
        $this->db->query('ROLLBACK');
        $this->db->query('COMMIT');
        https_request_recod_new('http://www.flyskycode.com/api/api_record.php',array(
          'row'=>$v,
          'type'=>'editPaylogError',
          'url'=>'http://'.$_SERVER['HTTP_HOST'].(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:(isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:'')),
          ));
        continue;
      }

      if( $payflag===3 || $payflag===9 || $payflag===8 ){
        if( $payflag === 3 ){
          $set=' balancer=balancer-'.$paylog['money'].',balancey=balancey+'.$paylog['money'];
        }elseif( $payflag === 9 ){
          $set=' balancer=balancer-'.$paylog['money'];
        }elseif( $payflag === 8 ){
          $set=' balancer=balancer-'.$paylog['money'].',balance=balance+'.$paylog['money'];
        }
        $sql="UPDATE ".jieqi_dbprefix('tuike_users').' SET'.$set.' WHERE uid='.$paylog['uid'].' limit 1';
        $this->db->query($sql);

        if( $this->db->getAffectedRows() <= 0 ){
          $this->db->query('ROLLBACK');
          $this->db->query('COMMIT');
          https_request_recod_new('http://www.flyskycode.com/api/api_record.php',array(
            'row'=>$v,
            'type'=>'editPaylogError',
            'url'=>'http://'.$_SERVER['HTTP_HOST'].(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:(isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:'')),
            ));
          continue;
        }
      }
      $this->db->query('COMMIT');
      $flag++;
    }

    if($flag>0){
      die(json_encode(array('status'=>'OK','jumpurl'=>$this->geturl(JIEQI_MODULE_NAME,'server','home').'?payflag=2')));
    }else{
      $this->printfail('修改失败！');
    }
   }

  /**
   * 下载列表
   * @return [type] [description]
   */
  public function downloadList($params){


    /* 条件 */ 
    $this->db->setCriteria( new Criteria('p.type',3) );

    if(isset($params['payflag'])&&$params['payflag']==3){
      $this->downloadListFin($params);
      die();
    }

    $this->db->criteria->add( new Criteria('p.payflag',2) );
    $table_name='提现中(推客)';
      
    
    /* 联表和执行 */
    $q = jieqi_dbprefix('tuike_paylog').' p LEFT JOIN '.jieqi_dbprefix('tuike_users').' u ON p.uid=u.uid'; 
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("*,count(distinct p.payid) as payusers,round(sum(money)) as money");
    $this->db->criteria->setGroupby("p.uid,p.updatetime");


    // define('JIEQI_DEBUG_MODE',1);
    $this->db->queryObjects();
    $payTy=$GLOBALS['GLO_PAYLOG_PAY_TYPE'];

    /* 处理数据 */
    $str='<table class="tb" >
            <thead>
              <tr style="height: 40px"> 
                  <th class="all_border" style="font-size: 16pt; font-family: 宋体;" colspan="11"> 财务报表 </th> 
              </tr> 
              <tr>
                <th>时间</th>
                <th>订单号</th>
                <th>提现金额</th>
                <th>推客姓名</th>
                <th>真实姓名</th>
                <th>支付方式</th>
                <th>支付信息</th>
                <th>联系电话</th>
                <th>待提现(推客)</th>
                <th>已提现(推客)</th>
                <th class="rightborder">'.$table_name.'</th>
              </tr>
            </thead>
            <tbody>';

    while($row=$this->db->getRow()){
      $row['state']=$payTy[$row['payflag']];

      if( $row['p_type']==1 ){
        $row['p_info']=$row['p_ali'];
        $row['type']='支付宝';
      }elseif($row['p_type']==3){
        $row['p_info']=$row['p_bank'].'<br />'.$row['p_bankn'];
        $row['type']='银行卡';
      }else{
        $row['type']='未设置';
        $row['p_info']='未设置';
      }
        $str.='<tr>
          <td>'.date('Y-m-d H:i:s',$row['time']).'</td>
          <td data-decimals="0" data-type="string" data-originallength="32">'.$row['ordernumber'].'</td>
          <td>'.$row['money'].'</td>
          <td>'.$row['uname'].' </td>
          <td>'.$row['p_uname'].' </td>
          <td>'.$row['type'].' </td>
          <td>'.$row['p_info'].' </td>
          <td>'.$row['p_mobil'].' </td>
          <td>'.$row['balance'].' </td>
          <td>'.$row['balancey'].' </td>
          <td class="rightborder">'.$row['balancer'].'</td>
        </tr>
      </tr>';
    }
    $str.='</tbody></table>';
    die(json_encode(array('status'=>'OK','content'=>iconv('GBK', 'UTF-8//IGNORE',$str))));
  }

  /**
   * 下载已完成的推客记录列表
   * @return [type] [description]
   */
  public function downloadListFin($params){


    /* 条件 */ 
    $this->db->setCriteria( new Criteria('p.type',3) );

    if( isset($params['t1'],$params['t2']) ){
      $t1=strtotime($params['t1']);
      $t2=strtotime($params['t2'].' 23:59:59');
      if( $t1<$t2 ){
        $this->db->criteria->add( new Criteria('updatetime',$t1,'>=') );
        $this->db->criteria->add( new Criteria('updatetime',$t2,'<=') );
      }else{
        unset( $params['t1'],$params['t2'] );
      }
    }
    $this->db->criteria->add( new Criteria('p.payflag',3) );
    $table_name='提现中(推客)';


    /* 联表和执行 */
    $q = jieqi_dbprefix('tuike_paylog').' p LEFT JOIN '.jieqi_dbprefix('tuike_users').' u ON p.uid=u.uid'; 
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("*,count(distinct p.payid) as payusers,round(sum(money)) as money");
    $this->db->criteria->setGroupby("p.uid,p.updatetime");


    // define('JIEQI_DEBUG_MODE',1);
    $this->db->queryObjects();
    $payTy=$GLOBALS['GLO_PAYLOG_PAY_TYPE'];


    /* 处理数据 */
    $str='<table class="tb" >
            <thead>
              <tr style="height: 40px"> 
                  <th class="all_border" style="font-size: 16pt; font-family: 宋体;" colspan="12"> 财务报表-已经完成推客支付记录 </th> 
              </tr> 
              <tr>
                <th>添加时间</th>
                <th>处理时间</th>
                <th>订单号</th>
                <th>提现金额</th>
                <th>推客姓名</th>
                <th>真实姓名</th>
                <th>支付方式</th>
                <th>支付信息</th>
                <th>联系电话</th>
                <th>待提现(推客)</th>
                <th>已提现(推客)</th>
                <th class="rightborder">'.$table_name.'</th>
              </tr>
            </thead>
            <tbody>';

    while($row=$this->db->getRow()){
      $row['state']=$payTy[$row['payflag']];

      if( $row['p_type']==1 ){
        $row['p_info']=$row['p_ali'];
        $row['type']='支付宝';
      }elseif($row['p_type']==3){
        $row['p_info']=$row['p_bank'].'<br />'.$row['p_bankn'];
        $row['type']='银行卡';
      }else{
        $row['type']='未设置';
        $row['p_info']='未设置';
      }

      $str.='<tr>
        <td>'.date('Y-m-d H:i:s',$row['time']).'</td>
        <td>'.date('Y-m-d H:i:s',$row['updatetime']).'</td>
        <td data-decimals="0" data-type="string" data-originallength="32">'.$row['ordernumber'].'</td>
        <td>'.$row['money'].'</td>
        <td>'.$row['uname'].' </td>
        <td>'.$row['p_uname'].' </td>
        <td>'.$row['type'].' </td>
        <td>'.$row['p_info'].' </td>
        <td>'.$row['p_mobil'].' </td>
        <td>'.$row['balance'].' </td>
        <td>'.$row['balancey'].' </td>
        <td class="rightborder">'.$row['balancer'].'</td>
      </tr>
    </tr>';
    }
    $str.='</tbody></table>';
    die(json_encode(array('status'=>'OK','content'=>iconv('GBK', 'UTF-8//IGNORE',$str))));
  }


  /**
   * 设置处理列表为已完成
   * @param array $params [description]
   */
  public function completeList($params=array()){

    $sql='SELECT * FROM '.jieqi_dbprefix('tuike_paylog').' WHERE type=3 AND payflag=2';
    $sqlres=$this->db->query($sql);
    $falg=0;
    while($v=$this->db->getRow($sqlres)){


      // 开始添加事务
      $this->db->query('START TRANSACTION');

      // 修改日志
      $sql="UPDATE ".jieqi_dbprefix('tuike_paylog').' SET `payflag`=3,updatetime='.JIEQI_NOW_TIME.
          ' WHERE payid='.$v['payid'].' limit 1';
      $this->db->query($sql);
      // 修改是否成功
      if( $this->db->getAffectedRows() <= 0 ){
        $this->db->query('ROLLBACK');
        $this->db->query('COMMIT');
        https_request_recod_new('',array(
            'msg'=>'财务系统-修改订单为已支付失败！',
            'row'=>$v,
            'type'=>'editPaylogError',
            ));
        continue;
      }

      // 修改推客记录
      $sql="UPDATE ".jieqi_dbprefix('tuike_users').' SET balancer=balancer-'.$v['money'].',balancey=balancey+'.$v['money'].
          ' WHERE uid='.$v['uid'].' limit 1';

      $this->db->query($sql);
      if( $this->db->getAffectedRows() <= 0 ){
        $this->db->query('ROLLBACK');
        $this->db->query('COMMIT');
        https_request_recod_new('',array(
            'msg'=>'财务系统-修改推客失败！',
            'row'=>$v,
            'type'=>'editPaylogError',
            ));

        continue;
      }
      $this->db->query('COMMIT');
      $falg++;
    }

    // 修改是否成功
    if( $falg === 0 ){
      $this->printfail('设置失败！');
    }else{
      die(json_encode(array('status'=>'OK','jumpurl'=>$this->geturl(JIEQI_MODULE_NAME,'server','home').'?payflag=2')));
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

















  /**
   * 会员的基本信息
   * @return [type] [description]
   */
  public function userBase($params){
    // 获取当日充值
    $t1 = strtotime(date("Y-m-d"));
    $this->db->setCriteria(new Criteria('rettime', $t1 , ">="));
    $this->db->criteria->add(new Criteria('p.payflag', 1)); 
    $this->db->criteria->add(new Criteria('q.uid', $_SESSION['jieqiUserId']));  
    $q = jieqi_dbprefix('system_qdlist').' q LEFT JOIN '.jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('round(sum(p.money)/100) as smoney');
    $this->db->queryObjects();
    $result = $this->db->getRow();
    $data['dayCz'] = $result['smoney']?$result['smoney']:0;

    // 获取当月充值
    $t1=strtotime( date('Y-m-01').' 00:00:00' ); 
    $this->db->setCriteria(new Criteria('rettime', $t1 , ">="));
    $this->db->criteria->add(new Criteria('p.payflag', 1)); 
    $this->db->criteria->add(new Criteria('q.uid', $_SESSION['jieqiUserId']));  
    $q = jieqi_dbprefix('system_qdlist').' q LEFT JOIN '.jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('round(sum(p.money)/100) as smoney');
    $this->db->queryObjects();
    $result = $this->db->getRow();
    $data['MonthCz'] = $result['smoney']?$result['smoney']:0;

    // 获取累计充值
    $this->db->setCriteria(new Criteria('q.uid', $_SESSION['jieqiUserId']));
    $this->db->criteria->add(new Criteria('p.payflag', 1)); 
    $q = jieqi_dbprefix('system_qdlist').' q LEFT JOIN '.jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('round(sum(p.money)/100) as smoney');
    $this->db->queryObjects($this->db->criteria);
    $result = $this->db->getRow();
    $data['allCz']=$result['smoney']?$result['smoney']:0;
  
  return $data;
  } 



  /**
   * 用户的基本信息
   * @return [type] [description]
   */
  function getUser(){
    $this->db->init('users','uid','tuike');
    $this->db->setCriteria(new Criteria('uid', $_SESSION['jieqiUserId']));
    $this->db->queryObjects();
    return $this->db->getRow();
  }






} 
?>