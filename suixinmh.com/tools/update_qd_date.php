<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/6/16
 * Time: ÏÂÎç4:59
 */
include("/www/new.ishufun.net/global.php");

mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");

//$sql="select source,min( from_unixtime(regdate,'%Y-%m-%d')) as qdate from jieqi_system_users group by source";
//
//$qarr=array();
//
//$r=mysql_query($sql);
//while ($d=mysql_fetch_array($r)) {
//    $qd=$d['source'];
//    $qdate=$d['qdate'];
//    if (!is_numeric($qd)) {
//        continue;
//    }
//
//    $qarr[$qdate][]=$qd;
//
//}
//
//foreach($qarr as $qdate=>$qdlist) {
//    $qlist=implode(',',$qdlist);
//    $sql="insert into jieqi_system_qdfee (qdlist,qdate) values('$qlist','$qdate')";
//    if (!mysql_query($sql)) {
//        echo $sql."\n".mysql_error()."\n";
//    }
//}

$sql="select * from jieqi_system_qdfee";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    $qdlist=$d['qdlist'];
    $id=$d['id'];
//    $sql="select sum(money)/100 from jieqi_pay_paylog where source  in ($qdlist) and payflag=1 ";
//    $r1=mysql_query($sql);
//    if (!$r1) {
//        echo $sql."\n";
//        echo mysql_error()."\n";
//        exit();
//    }
//    $d1=mysql_fetch_array($r1);
//    $money=round($d1[0]);
//    $sql="update jieqi_system_qdfee set total_pay=$money where id=$id";
//    if (!mysql_query($sql)) {
//        die($sql."\n".mysql_error());
//    }

    $sql="select count(*) from jieqi_system_users where source in($qdlist)";
    $r1=mysql_query($sql);
    if (!$r1) {
        echo $sql."\n";
        echo mysql_error()."\n";
        exit();
    }
    $d1=mysql_fetch_array($r1);
    $regcount=round($d1[0]);
    $sql="update jieqi_system_qdfee set regcount=$regcount where id=$id";
    echo $sql."\n";
    if (!mysql_query($sql)) {
        die($sql."\n".mysql_error());
    }
}