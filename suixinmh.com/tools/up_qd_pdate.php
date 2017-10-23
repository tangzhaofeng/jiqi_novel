<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/11
 * Time: ионГ12:27
 */
include_once("/www/new.ishufun.net/global.php");
$conn=mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS);
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names GBK");


$sql="select * from jieqi_system_qdlist";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    $qd=trim($d['qd']);
    if (!$d['pdate'] || $d['pdate']=='0000-00-00') {
        $sql="select * from jieqi_pay_paylog where `source`='$qd' order by payid limit 1";
        $r1=mysql_query($sql);
        if (!$r1) {
            die($sql."\n".mysql_error()."\n");
        }
        $d1=mysql_fetch_array($r1);
        $pdate=date("Y-m-d",$d1['buytime']);
        $sql="update jieqi_system_qdlist set pdate='$pdate' where id=".$d['id'];
        mysql_query($sql);
    }
}
