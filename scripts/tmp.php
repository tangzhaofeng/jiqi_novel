<?php
include("/www/new.ishufun.net/configs/define.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME) || die("select error\n");

mysql_query("set names gbk");
$sql="select b.* from user_tr a,jieqi_system_users b where a.uid=b.uid and a.uname=''";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    $sql="update user_tr set uname='".addslashes($d['uname'])."' where uid=".$d['uid'];
    echo $sql."\n";
    mysql_query($sql);
}