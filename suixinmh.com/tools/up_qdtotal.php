<?php
include("/www/new.ishufun.net/configs/define.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME) || die("select error\n");

$sql="select qd,min(time) from jieqi_system_qddata group by qd";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    $qd=$d['qd'];
    $date=substr($d[1],0,8);
    $time=strtotime($date);

    $to_time=strtotime(date("Y-m-d 23:59:59"));
    while ($time <= $to_time) {
        $date=date("Y-m-d",$time);
        $t1=date("Ymd",$time)."00";
        $t2=date("Ymd",$time)."23";
        $sql="select sum(click),sum(pv),sum(reg),qd from jieqi_system_qddata where qd='$qd' and `time`>=$t1 and `time` <= $t2";
        $r1=mysql_query($sql);
        $d1=mysql_fetch_array($r1);
        $click=$d1[0]+0;
        $pv=$d1[1]+0;
        $reg=$d1[2]+0;
        if ($click || $pv || $reg) {
            $sql = "select * from jieqi_system_qdtotal where qd='$qd' and `date`='$date'";
            $r2 = mysql_query($sql);
            $d2 = mysql_fetch_array($r2);
            if ($d2) {
                $sql = "update jieqi_system_qdtotal set click=" . $d1['0'] . ",pv=" . $d1[1] . ",reg=" . $d1[2] . " where qd='$qd' and `date`='$date'";
            } else {
                $sql = "insert into jieqi_system_qdtotal set click=" . $d1['0'] . ",pv=" . $d1[1] . ",reg=" . $d1[2] . ",qd='$qd',`date`='$date'";
            }
            if (!mysql_query($sql)) {
                echo $sql . "\n" . mysql_error() . "\n";
            } else {
                echo $qd . ",$date\n";
            }
        }
        $time+=86400;
    }


}