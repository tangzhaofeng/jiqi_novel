<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 2016/11/21
 * Time: 下午2:39
 */


include("../global.php");
include("../configs/define.php");

mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("connect error\n");
mysql_select_db(JIEQI_DB_NAME) || die("select db error\n");
mysql_query("set names gbk");

$mon_arr=array(5,6,7,8,9,10);
$day_arr=array(5,15,25);
$daynum=array(1,2,3,4,5,6,7,15,30,60,0);

foreach($mon_arr as $m) {
    foreach($day_arr as $d) {
        $date="2016-$m-$d";
        echo $date."\t";
        $t1=strtotime($date);
        $t2=strtotime($date." 23:59:59");

        $sql="select count(*) from jieqi_system_users where regdate>=$t1 and regdate<=$t2";   //注册用户
        //echo $sql."\n";
        $r=mysql_query($sql);
        if (!$r) {
            echo $sql."\n".mysql_error()."\n";
        }
        $d=mysql_fetch_array($r);
        $regcount=$d[0];

        $sql="select count(*) from jieqi_system_users where regdate>=$t1 and regdate<=$t2 and isvip>0";     //付费用户总量
        $r=mysql_query($sql);
        if (!$r) {
            echo $sql."\n".mysql_error()."\n";
        }
        $d=mysql_fetch_array($r);
        $paycount=$d[0];

        echo $regcount."\t".$paycount."\t";
        //continue;

        $login_arr=array();
        $pay_login_arr=array();
        $pay_arr=array();
        foreach($daynum as $day) {
            if ($day) {
                $login_time=$t1+$day*86400;
                $sql1="select count(*) from jieqi_system_users where regdate>=$t1 and regdate<=$t2 and lastlogin>=$login_time";
                $sql2="select count(*) from jieqi_system_users where regdate>=$t1 and regdate<=$t2 and lastlogin>=$login_time and isvip>0";
                $sql3="select count(distinct buyid),sum(money),count(*) from jieqi_system_users a,jieqi_pay_paylog b where a.uid=b.buyid and regdate>=$t1 and regdate<=$t2  and b.buytime<=$login_time and b.payflag=1";
            }
            else {
                $sql1="select count(*) from jieqi_system_users where regdate>=$t1 and regdate<=$t2 ";
                $sql2="select count(*) from jieqi_system_users where regdate>=$t1 and regdate<=$t2 and isvip>0";
                $sql3="select count(distinct buyid),sum(money),count(*) from jieqi_system_users a,jieqi_pay_paylog b where a.uid=b.buyid and regdate>=$t1 and regdate<=$t2  and b.payflag=1";
            }

            $r=mysql_query($sql1);
            if (!$r) {
                echo $sql1."\n".mysql_error()."\n";
            }
            $d=mysql_fetch_array($r);
            $login_arr[$day] = $d[0];   //用户N天登陆统计

            $r=mysql_query($sql2);
            if (!$r) {
                echo $sql2."\n".mysql_error()."\n";
            }
            $d=mysql_fetch_array($r);
            $pay_login_arr[$day] = $d[0];  //付费用户N天登陆统计

            $r=mysql_query($sql3);
            if (!$r) {
                echo $sql3."\n".mysql_error()."\n";
            }
            $d=mysql_fetch_array($r);
            $pay_arr[$day] = array(
                'usercount' => $d[0],
                'money'=> round($d[1]/100),
                'count' => $d[2]
            );
        }
        foreach($login_arr as $l) {
            echo $l."\t";
        }
        echo "\n\t\t\t";
        foreach($pay_login_arr as $l) {
            echo $l."\t";
        }
        echo "\n\t\t\t";
        foreach($pay_arr as $l) {
            echo $l['usercount']."\t";
        }
        echo "\n\t\t\t";
        foreach($pay_arr as $l) {
            echo $l['money']."\t";
        }
        echo "\n\t\t\t";
        foreach($pay_arr as $l) {
            echo $l['count']."\t";
        }
        echo "\n\t\t\t";
        echo "\n\n\n\n";
    }
}