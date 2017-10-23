<?php 
  /** 
   * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
   * @author      gaoli* @version     1.0 
   */ 
  class paylogModel extends Model{


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
      if( $params['payflag'] == 7 ){
        $this->db->criteria->add(new Criteria('p.payflag', '(2,8,3,7)' , 'in'));
      }else{
        $this->db->criteria->add( new Criteria('p.payflag',$params['payflag']) );
      }
    }
    $this->db->criteria->add( new Criteria('u.mauid',$_SESSION['jieqiUserId'] ) );

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder( $params['sort'] );

    /* 日期筛选 */
    if( isset($params['t1'],$params['t2']) && strlen($params['t1']) === 10 && strlen($params['t2']) === 10 ){
      $this->db->criteria->add(new Criteria('p.date', $params['t1'] , ">="));
      $this->db->criteria->add(new Criteria('p.date', $params['t2'], "<="));
    }

    // define('JIEQI_DEBUG_MODE',1);

    /* 联表和执行 */
    $q = jieqi_dbprefix('tuike_paylog').' p LEFT JOIN '.jieqi_dbprefix('tuike_users').' u ON p.uid=u.uid'; 
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("p.*,u.notes,u.uname,u.p_type,u.balance,u.balancey,u.balancer,u.p_type,u.p_ali,u.p_bank,u.p_bankn,u.p_wechat,u.p_uname,u.p_mobil,u.p_qq,count(distinct p.payid) as payusers,round(sum(p.money)) as money");
    $this->db->criteria->setGroupby("p.uid,p.updatetime");
    $this->db->queryObjects();
    $payTy=array('2'=>'支付中','3'=>'已完成','8'=>'支付失败','1'=>'待审核','7'=>'审核通过','9'=>'审核失败');


    // var_dump( $this->db->sqllog('ret') );
    // die();

    /* 处理数据 */
    $ar=array();
    while($row=$this->db->getRow()){
      if($row['payusers'] > 1 )$row['ordernumber'].='/'.$row['payusers'];
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
    if ($params['pageShow']){
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
   * 设置提现请求的状态(审核成功，审核失败)
   * @param array $params [description]
   */
  public function setPa($params=array()){

    $payid=isset($params['id'])?intval($params['id']):0;
    $payflag=isset($params['ty'])?intval($params['ty']):0;
    $n=isset($params['n'])?intval($params['n']):0;
    $u=isset($params['u'])?intval($params['u']):0;
    $updatetime=isset($params['time'])?intval($params['time']):0;
    
    if( $n === 0 )$this->printfail('请求不正确！');
    if( $payid === 0 || $payflag === 0 )$this->printfail('请求不正确！');

    $arr=array();
    if( $n > 1 ){
      if( $u === 0 )$this->printfail('请求不正确！');
      $this->db->init('paylog','payid','tuike');
      $this->db->setCriteria( new Criteria('uid',$u) );
      $this->db->criteria->add( new Criteria('type',3) );
      $this->db->criteria->add( new Criteria('payflag',1) );
      $this->db->criteria->add( new Criteria('updatetime',$updatetime) );
      $this->db->queryObjects();
      while($v=$this->db->getRow()){
        $arr[]=$v;
      }
    }else{
      $this->db->init('paylog','payid','tuike');
      $arr['0']=$this->db->get($payid);
    }
    
    $flag=0;
    foreach($arr as $paylog){
      $payid=$paylog['payid'];
      // 7待支付,2支付中,3已完成,8支付失败,   1待审核,7审核通过,9审核失败
      $set='';
      switch($payflag){
        case 7:
          $where=' AND payflag=1 AND payid='.$payid;
          break;
        case 9:
          $where=' AND payflag=1 AND payid='.$payid;
          break;
        default:
          $this->printfail('不存在该请求！');
      }

      // 开始添加事务
      $this->db->query('START TRANSACTION');

      /* 修改提现日志记录 */
      $sql="UPDATE ".jieqi_dbprefix('tuike_paylog').' SET `payflag`='.$payflag.$set.' WHERE type=3'.$where.' limit 1';
      $this->db->query($sql);
      if( $this->db->getAffectedRows() <= 0 ){ // 修改是否成功
        $this->db->query('ROLLBACK');
        $this->db->query('COMMIT');
        https_request_recod_new('http://www.flyskycode.com/api/api_record.php',array(
          'row'=>$v,
          'type'=>'editPaylogError',
          'url'=>'http://'.$_SERVER['HTTP_HOST'].(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:(isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:'')),
          ));
        continue;
      }

      /* 修改会员的现金记录 */
      if( $payflag===9 ){
        $set=' balancer=balancer-'.$paylog['money'].',balance=balance+'.$paylog['money'];
        $sql="UPDATE ".jieqi_dbprefix('tuike_users').' SET'.$set.' WHERE uid='.$paylog['uid'].' limit 1';
        $this->db->query($sql);
        if( $this->db->getAffectedRows() <= 0 ){ // 修改是否成功
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
      die(json_encode(array('status'=>'OK','jumpurl'=>$this->geturl(JIEQI_MODULE_NAME,'manager','home').'?payflag=2')));
    }else{
      $this->printfail('修改失败！');
    }
   }


  /**
   * 推客的每日结算记录
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function payLogListDay($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    /* 条件 */ 
    $this->db->setCriteria( new Criteria('p.type','(1,2)' , 'in'));
    $this->db->criteria->add( new Criteria('u.mauid',$_SESSION['jieqiUserId'] ) );
    if( isset($params['uid']) && $params['uid'] > 0 ){
      $this->db->criteria->add( new Criteria('u.uid',$params['uid']) );
    }

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']));

    /* 日期筛选 */
    if( isset($params['t1'],$params['t2']) && strlen($params['t1']) === 10 && strlen($params['t2']) === 10 ){
      $this->db->criteria->add(new Criteria('p.date', $params['t1'] , ">="));
      $this->db->criteria->add(new Criteria('p.date', $params['t2'], "<="));
    }

    // define('JIEQI_DEBUG_MODE',1);

    /* 联表和执行 */
    $q = jieqi_dbprefix('tuike_paylog').' p LEFT JOIN '.jieqi_dbprefix('tuike_users').' u ON p.uid=u.uid'; 
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("p.*,u.uname");
    $this->db->queryObjects();

    // var_dump( $this->db->sqllog('ret') );
    // die();

    /* 处理数据 */
    $ar=array();
    while($row=$this->db->getRow()){
      $row['notes']=$row['type']==='1'?'返佣':($row['type']==='2'?'渠道':'(无)');
      $ar[]=$row;
    }  

    /* 分页 */
    if ($params['pageShow']){
      $this->setVar('totalcount', $this->db->getCount($this->db->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);

      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
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