<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/6/21
 * Time: обнГ2:29
 */
include_once("../global.php");
include_once("../lib/database/redis.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");

$sql="select a.payid,b.source from jieqi_pay_paylog a,jieqi_system_users b where a.buyid=b.uid and a.source='11027'";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    if ($d['source']) {
        $sql = "update jieqi_pay_paylog set source='" . $d['source'] . "' where payid=" . $d['payid'];
        echo $sql . "\n";
        mysql_query($sql);
    }
}