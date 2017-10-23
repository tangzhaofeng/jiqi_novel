<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/1/19
 */
include("/www/new.ishufun.net/global.php");
include("/www/new.ishufun.net/include/total_service_funcs.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");
//dump_qd_stat();

$sql = "select * from jieqi_system_qdlist ";
$r = mysql_query($sql);
while ($d = mysql_fetch_array($r)) {
    $qd = trim($d['qd']);
    $sql = "select sum(pv) as tpv,sum(click) as tclick,floor(time/100) as pdate from jieqi_system_qddata where `qd`='$qd' group by pdate order by pdate ";
    echo $sql."\n";
    $r1 = mysql_query($sql);
    if (!$r1) {
        die($sql . "\n" . mysql_error() . "\n");
    }
    while ($d1 = mysql_fetch_array($r1)) {
        if ($d1['tclick'] > 50) {
            $pdate = $d1['pdate'];
            $sql = "update jieqi_system_qdlist set pdate='$pdate' where id=" . $d['id'];
            echo $sql."\n";
            mysql_query($sql);
            break;
        }
        else {
            echo "*\n";
        }
    }
}
