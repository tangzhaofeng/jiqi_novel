<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/5/4
 * Time: ÏÂÎç2:15
 */
//include("/www/new.ishufun.net/global.php");
include("/www/new.ishufun.net/configs/define.php");
$conn_tr=mysql_connect("10.47.50.58","trans_data","123456");
mysql_select_db("xiaoshuo");
mysql_query("set names gbk");

($conn = mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS)) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME,$conn) || die("select error\n");
mysql_query("set names gbk",$conn);


$sql="select * from jieqi_system_users where lastlogin>=".strtotime('2016-4-1')." and groupid=3 ";
$r=mysql_query($sql,$conn_tr);
while ($d=mysql_fetch_array($r,MYSQL_ASSOC)) {
    echo $d['uid']."\n";
    import_user($d);
}


function import_user($d) {
    $puid=$d['uid'];
    unset($d['uid']);
    global $conn,$conn_tr;
    $sql="select * from jieqi_system_users where uname='".addslashes($d['uname'])."'";
    //echo $sql."\n";
    $r1=mysql_query($sql,$conn);
    $d1=mysql_fetch_array($r1,MYSQL_ASSOC);
    if (!$d1) {
        $sql="insert into jieqi_system_users set ";
        $data=array();
        foreach($d as $field=>$value) {
            $data[]="`$field`='".addslashes($value)."'";
        }
        $sql.=implode(',',$data);
        //echo $sql."\n";
        if (mysql_query($sql,$conn)) {
            $uid = mysql_insert_id($conn);

            $sql="insert into user_tr(uid_p,uid) values('$puid','$uid')";
            if (mysql_query($sql,$conn)) {
                import_bookcase($puid, $uid);
                import_openid($puid,$uid);
            }
            else {
                echo $sql."\n".mysql_error($conn)."\n";
            }
        }
        else {
            echo $sql."\n".mysql_error($conn)."\n";
        }
    }
    else {
        echo $d['uname']." exists\n";
    }
}

function import_bookcase($puid,$uid) {
    global $conn,$conn_tr;
    $sql="select * from jieqi_article_bookcase where userid=$puid";
    $r=mysql_query($sql,$conn_tr);
    while ($d=mysql_fetch_array($r,MYSQL_ASSOC)) {
        $d['userid'] = $uid;
        unset($d['caseid']);
        $sql="insert into jieqi_article_bookcase set ";
        $data=array();
        foreach($d as $field=>$value) {
            $data[]="`$field`='".addslashes($value)."'";
        }
        $sql.=implode(',',$data);
        if (!mysql_query($sql,$conn)) {
            echo $sql."\n".mysql_error($conn)."\n";
        }
    }
}

function import_openid($puid,$uid) {
    global $conn,$conn_tr;
    $sql="select * from jieqi_system_connect where uid=$puid";
    $r=mysql_query($sql,$conn_tr);
    while ($d=mysql_fetch_array($r,MYSQL_ASSOC)) {
        $d['uid'] = $uid;
        unset($d['connectid']);
        $sql="insert into jieqi_system_connect set ";
        $data=array();
        foreach($d as $field=>$value) {
            $data[]="`$field`='".addslashes($value)."'";
        }
        $sql.=implode(',',$data);
        if (!mysql_query($sql,$conn)) {
            echo $sql."\n".mysql_error($conn)."\n";
        }
    }
}