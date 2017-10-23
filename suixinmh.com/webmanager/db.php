<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 15/11/9
 * Time: 下午3:07
 */
include("../configs/define.php");
include("../global.php");
include_once("../lib/database/mysql/db.php");

$db=new JieqiMySQLDatabase;
$db->connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS);
mysql_select_db(JIEQI_DB_NAME);

function query($sql) {
  global $db;
  $r=$db->query($sql);
  if (!$r) {
    die("mysql error:".$db->error());
  }
  return $db->fetchArray($r);
}

function queryall($sql) {
  global $db;
  $r=$db->query($sql);
  if (!$r) {
    die("mysql error:".$db->error());
  }
  $data=array();
  while ($d=$db->fetchArray($r)) {
	  $data[]=$d;
  }
  return $data;
}

function check_level($level) {
    if ($_SESSION['qd_admin_level'] < $level) {
        jieqi_printfail("您没有权限访问");
        exit();
    }
}

function append_qd_info($sql) {
    if ($_SESSION['qd_admin_level']<99) {
        $sql.=" and source like '".$_SESSION['qd_data']."'";
    }
    return $sql;
}

$userid=$_SESSION['jieqiUserId'];
if (!$nocheck && $userid && !$_SESSION['qd_admin_level']) {
    $sql="select * from jieqi_system_qdadmin where uid=$userid";
    $info=query($sql);
    if (!$info) {
        jieqi_printfail("您没有权限访问");
        exit();
    }
    else {
        $_SESSION['qd_admin_level']=$info['level'];
        $_SESSION['qd_data'] = $info['qd'];
    }
}

