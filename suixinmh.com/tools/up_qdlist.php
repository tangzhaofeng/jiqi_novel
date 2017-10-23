<?php
include("/www/new.ishufun.net/configs/define.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME) || die("select error\n");

mysql_query("set names GBK");

$sql="select * from jieqi_system_qdlist";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    $wxh=iconv("utf-8","gbk",$d['wxh']);
    $sql="update jieqi_system_qdlist set wxh='".addslashes($wxh)."' where id=".$d['id'];
    echo iconv("GBK","UTF-8",$sql)."\n";
    mysql_query($sql);
}