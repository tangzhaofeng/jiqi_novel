<?php 
/** 
 * 下级推客明细模块
 * @author      gaoli* @version     1.0 
 */ 
class manuserModel extends Model{
  //login form
  public function main($params){
    
    $data = array();
    return $data;
  }
  /**
   * 经理所属推客
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function maUserPay($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    $params['page']=isset($params['page'])?intval($params['page']):1;
    $params['start']=($params['page'] - 1) * $params['limit'];

    /* 条件 */ 
    if( isset($params['uid']) && $params['uid'] > 0 ){
      $where='(u.mauid="'.$_SESSION['jieqiUserId'].'" AND u.uid = "'.$params['uid'].'"';
    }elseif( isset($params['tkId']) && $params['tkId'] > 0 ){
      $where='( u.tkuid = "'.$params['tkId'].'"';
    }else{
      $where='((u.mauid = "'.$_SESSION['jieqiUserId'].'" OR u.uid = "'.$_SESSION['jieqiUserId'].'")';
    }

    if(isset($params['keyword']) && strlen($params['keyword']) > 0){// 搜索 
      $where.=' AND u.uname="'.trim($params['keyword']).'"';
    }
    $where.=')';
    /* 获取数量 */
    $limit=' LIMIT '.$params['start'].','.$params['limit'];
    /* 排序 */
    $order=' ORDER BY '.$params['orderS'].' '.$params['sort'];
    /* 分组 */
    $groupby=' GROUP BY u.uid';

    // define('JIEQI_DEBUG_MODE',1);

    $sql ='SELECT u.uid,u.uname,u.reg_time,u.notes,round(sum(p.money)/100,2) as money  FROM '.
      jieqi_dbprefix('tuike_users').' u LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON q.uid=u.uid LEFT JOIN '.
      jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd AND p.payflag=1 '.
      ' WHERE '.$where.$groupby.$order.$limit;

    $sqlres=$this->db->query($sql);;

    // var_dump( $this->db->sqllog('ret') );
    // die();

    $dayT1 = strtotime( date("Y-m-d") ); // 获取当日充值
    $monthT1=strtotime( date('Y-m-01').' 00:00:00' );  // 获取当月充值

    $ar=array();
    while($row=$this->db->getRow($sqlres)){
      if( !$row['money'] )$row['money']=0;
      $row['p_qq']=empty($row['p_qq'])?'(无)':$row['p_qq'];
      $row['payDay']=0;
      $row['payMonth']=0;

      //充值昨日
      $sql ='SELECT round(sum(p.money)/100,2) as money  FROM '.
      jieqi_dbprefix('tuike_users').' u LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON q.uid=u.uid LEFT JOIN '.
      jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd AND p.payflag=1 '.
      ' WHERE u.uid="'.$row['uid'].'" AND p.rettime > "'.$dayT1.'"';
      $ro=$ro=$this->db->getRow($this->db->query($sql));
      if( $ro && $ro['money'] > 0 )$row['payDay']=$ro['money'];

      //充值本月
      $sql ='SELECT round(sum(p.money)/100,2) as money  FROM '.
      jieqi_dbprefix('tuike_users').' u LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON q.uid=u.uid LEFT JOIN '.
      jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd AND p.payflag=1 '.
      ' WHERE u.uid="'.$row['uid'].'" AND p.rettime > "'.$monthT1.'"';
      $ro=$ro=$this->db->getRow($this->db->query($sql));
      if( $ro && $ro['money']>0 )$row['payMonth']=$ro['money'];


      //广告分成
      $row['payT']=0;
      $row['payTn']=0;
      $row['payQ']=0;
      $row['payQn']=0;
      $this->db->init('paylog','payid','tuike');
      $this->db->setCriteria(new Criteria('type',3,'!='));
      $this->db->criteria->add(new Criteria('uid',$row['uid'] ));
      $this->db->criteria->setFields("count(distinct payid) as payusers,round(sum(money)) as money,type");
      $this->db->criteria->setGroupby('type');
      $this->db->queryObjects();
      while( $ro=$this->db->getRow() ){
        if($ro['type'] === '1'){
          $row['payT']=$ro['money'];
          $row['payTn']=$ro['payusers'];
        }elseif($ro['type'] === '2'){
          $row['payQ']=$ro['money'];
          $row['payQn']=$ro['payusers'];
        }
      }
      $row['payAlDay']=$row['payT']+$row['payQ'];
      $row['payAlDayn']=$row['payTn']+$row['payQn'];

      //推广分成
      $this->db->init('users','uid','tuike');
      $this->db->setCriteria(new Criteria('tkuid',$row['uid']));
      $this->db->criteria->add(new Criteria('uid',$row['uid'],'!=')); 
      $row['payTNum']=$this->db->getCount($this->db->criteria);
           
      //渠道管理
      $this->db->init('qdlist','id','system');
      $this->db->setCriteria(new Criteria('uid',$row['uid']));
      $row['qdNum']=$this->db->getCount($this->db->criteria);
      $ar[]=$row;
    }

    if ($params['pageShow']){
      $sql ='SELECT count(*) cou FROM '.
        jieqi_dbprefix('tuike_users').' u '.
        ' WHERE '.$where;
      $row=$this->db->getRow($this->db->query($sql));
      $this->setVar('totalcount', $row['cou']);
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }
  /**
   * 推客总的充值记录
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function ktUserPay($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    $params['page']=isset($params['page'])?intval($params['page']):1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    /* 条件 */ 
    $this->db->setCriteria();
    $this->db->criteria->add(new Criteria('u.tkuid',$params['uid']));
    $this->db->criteria->add(new Criteria('u.uid',$params['uid'],'!='));
    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('u.uname', trim($params['keyword']))); 
    }
    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));
    $q = jieqi_dbprefix('tuike_users').' u LEFT JOIN '. jieqi_dbprefix('system_qdlist').' q ON u.uid=q.uid LEFT JOIN '.jieqi_dbprefix('pay_paylog').' p ON q.qd=p.source AND p.payflag=1';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("u.uid,u.name,u.reg_time,count(distinct p.buyid) as payusers,round(sum(p.money)/100) as qdpay");
    $this->db->criteria->setGroupby("u.uid");
    $this->db->queryObjects();
    $ar=array();
    while($row=$this->db->getRow()){
      $row['qdpay']=isset($row['qdpay'])?$row['qdpay']:0;
      if(strlen($row['head']) < 6 ){
        $row['head']=YOUYUEBOOK_URL.'/images/noavatarl.jpg';
      }elseif(strpos($row['head'],'http://') === false){
        $row['head']=YOUYUEBOOK_URL.'/'.$row['head'];
      }
      $ar[]=$row;
    }  
    if ($params['pageShow']){
      $this->db->setCriteria(new Criteria('u.tkuid',$params['uid']));
      $this->db->criteria->add(new Criteria('u.uid',$params['uid'],'!='));
      // 搜索
      if(isset($params['keyword']) && strlen($params['keyword']) > 0){
        $this->db->criteria->add(new Criteria('u.name', trim($params['keyword']))); 
      }
      $q = jieqi_dbprefix('tuike_users').' u';
      $this->db->criteria->setTables($q);
      $this->setVar('totalcount', $this->db->getCount($this->db->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }


  /**
   * 推客详细(更多信息)
   * @return [type] [description]
   */
  function tkInfo($params=array()){
    // 推客
    $info=$this->qdinfo($params);
    // 上级推客
    $info['tkuidname']='(无)';
    if( $info['tkuid'] >0 ){
      $row=$this->qdinfo(array('uid'=>$info['tkuid']));
      if( $row )$info['tkuidname']=$row['uname'];
    }
    // // 昨日（广告，推广）
    // $info['paylogG']='0';
    // $info['paylogT']='0';
    // $dateS=date('Y-m-d',strtotime(' -1 day',JIEQI_NOW_TIME));
    // $this->db->init('paylog','payid','tuike');
    // $this->db->setCriteria(new Criteria('uid',$info['uid'] ));
    // $this->db->criteria->add(new Criteria('date',$dateS ));
    // $this->db->criteria->setFields('type,money');
    // $this->db->queryObjects();
    // while($row=$this->db->getRow()){
    //   if($row['type'] === '1')$info['paylogT']=$row['money'];
    //   if($row['type'] === '2')$info['paylogG']=$row['money'];
    // };
    return $info;
  }

  /**
   * 编辑推客
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function tkInfoEdit($params=array()){
    $data=array();
    $params['uid']=isset($params['uid'])?intval($params['uid']):0;
    $data['name']=isset($params['name'])?trim($params['name']):'';
    $data['mobile']=isset($params['mobile'])?trim($params['mobile']):'';
    $data['p_qq']=isset($params['p_qq'])?trim($params['p_qq']):'';
    $data['p_wechat']=isset($params['p_wechat'])?trim($params['p_wechat']):'';
    $data['notes']=isset($params['notes'])?trim($params['notes']):'';
    if($params['uid'] === 0)$this->printfail('信息不正确！');
    $this->db->init('users','uid','tuike');
    $this->db->edit($params['uid'],$data);
    if( $this->db->getAffectedRows() > 0 ){
      $this->msgwin('修改成功！');
    }else{
      $this->printfail('修改失败！');
    }
  }


  /**
   * 推客详细
   * @return [type] [description]
   */
  function qdinfo($params=array()){
    $this->db->init('users','uid','tuike');
    $this->db->setCriteria(new Criteria('uid',$params['uid'] ));
    $this->db->queryObjects();
    return $this->db->getRow();
  }
  /**
   * 经理所属推客
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function maUserTkPay($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    $params['page']=isset($params['page'])?intval($params['page']):1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    /* 条件 */ 
    $this->db->setCriteria(new Criteria('u.tkuid',$params['uid']));
    $this->db->criteria->add(new Criteria('u.uid',$params['uid'],'!='));
    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('u.uname', trim($params['keyword']))); 
    }
    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));
    $q = jieqi_dbprefix('tuike_users').' u LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON q.uid=u.uid LEFT JOIN '.
      jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd AND p.payflag=1';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('u.uid,u.uname,u.reg_time,round(sum(p.money)/100) as money ');
    $this->db->criteria->setGroupby("u.uid");
    $sqlres=$this->db->queryObjects();
    $ar=array();
    while($row=$this->db->getRow($sqlres)){
      if( !$row['money'] )$row['money']=0;

      //广告分成
      $row['payT']=0;
      $row['payG']=0;
      $this->db->init('paylog','payid','tuike');
      $this->db->setCriteria(new Criteria('type',3,'!='));
      $this->db->criteria->add(new Criteria('uid',$row['uid'] ));
      $this->db->criteria->setFields("count(distinct payid) as payusers,round(sum(money)) as money,type");
      $this->db->criteria->setGroupby('type');
      $this->db->queryObjects();
      while( $ro=$this->db->getRow() ){
        if($ro['type'] === '1'){
          $row['payT']=$ro['money'];
        }elseif($ro['type'] === '2'){
          $row['payG']=$ro['money'];
        }
      }

      //推广分成
      $this->db->init('users','uid','tuike');
      $this->db->setCriteria(new Criteria('tkuid',$row['uid']));
      $this->db->criteria->add(new Criteria('uid',$row['uid'],'!='));
      $row['payTNum']=$this->db->getCount($this->db->criteria);

      //渠道管理
      $this->db->init('qdlist','id','system');
      $this->db->setCriteria(new Criteria('uid',$row['uid']));
      $row['qdNum']=$this->db->getCount($this->db->criteria);
      $ar[]=$row;
    }

    if ($params['pageShow']){
      $this->db->init('users','uid','tuike');
      $this->db->setCriteria(new Criteria('tkuid',$params['uid']));
      $this->db->criteria->add(new Criteria('uid',$params['uid'],'!='));
      $this->setVar('totalcount', $this->db->getCount($this->db->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }



  /**
   * 每次的公共调用
   * @return [type] [description]
   */
  function common(){
    $data=array();
    // 查询推客数
    $this->db->init('users','uid','tuike');
    $this->db->setCriteria(new Criteria('mauid',$_SESSION['jieqiUserId'] ));
    $this->db->criteria->add(new Criteria('uid',$_SESSION['jieqiUserId'] ),'OR');
    $data['tkNu']=$this->db->getCount($this->db->criteria);
    // 查询待审核金额
    $data['payusers']=0;
    $data['money']=0;

    // 总的充值金额
    $q = jieqi_dbprefix('tuike_paylog').' p LEFT JOIN '.jieqi_dbprefix('tuike_users').' u ON u.uid=p.uid'; 
    $this->db->setCriteria(new Criteria('u.mauid',$_SESSION['jieqiUserId']));
    $this->db->criteria->add(new Criteria('type',3 ));
    $this->db->criteria->add(new Criteria('payflag',1 ));

    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("count(distinct payid) as payusers,round(sum(money)) as money");
    $this->db->queryObjects();

    $row=$this->db->getRow();
    if($row && $row['money']){
      $data['payusers']=$row['payusers'];
      $data['money']=$row['money'];
    }
    return $data;
  }

 /**
   * 设置提现请求的状态(审核成功，审核失败)
   * @param array $params [description]
   */
  public function setPa($params=array()){
    $params['field_i']=isset($params['field_i'])?intval($params['field_i']):0;
    $parasm['field_v']=isset($params['field_v'])?trim($params['field_v']):'';
    $params['field_y']=isset($params['field_y'])?trim($params['field_y']):'';     
    if( $params['field_i']===0 || 
        $params['field_v']==='' ||
        $params['field_y']==='' )$this->printfail('信息不正确！');
    switch($params['field_y']){
      case 'note':
        $sql="UPDATE ".jieqi_dbprefix('tuike_users').' SET `notes`="'.$params['field_v'].'" WHERE uid="'.$params['field_i'].'"';
        break;
      default:
        $this->printfail('不存在该设置！');
    }
    $this->db->query($sql);
    // 修改是否成功
    if( $this->db->getAffectedRows() > 0 ){

      $this->msgwin('修改成功！');
    }else{
      $this->printfail('设置失败！');
    }
   }
































} 
?>