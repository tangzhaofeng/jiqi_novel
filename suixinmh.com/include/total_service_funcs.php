<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/1/17
 * Time: 上午1:00
 */
function redis_conn() {
    global $cache_redis;
    if (!isset($cache_redis)) {
        include_once(JIEQI_ROOT_PATH.'/lib/database/redis.php');
        $cache_redis = new MyRedis;
    }
    return $cache_redis;
}

//统计渠道点击过来的数量，按小时计
function qd_stat($qd,$action) {
    global $cache_redis;
    redis_conn();
    $qd=strtolower($qd);
    $ip=jieqi_userip();
    $time=date("YmdH");
    $date=date("Ymd");
    $qd_key = "qd_{$qd}_{$action}_$time";
    $qd_list_key = "qdlist_{$date}";
    $qd_table = "qd_{$action}_{$time}";

    switch($action) {
        case "click":
            $cache_redis->hIncrBy($qd_table, "qd".$qd, 1);
            if (!$cache_redis->hget($qd_list_key,$qd)) {
                $cache_redis->hset($qd_list_key, $qd, 1);
            }
            break;
        case "pv":
            $cache_redis->hIncrBy($qd_table, "qd".$qd, 1);
            if (!$cache_redis->hget($qd_list_key,$qd)) {
                $cache_redis->hset($qd_list_key, $qd, 1);
            }
            break;
    }
}


function dump_qd_stat() {
    global $cache_redis;
    redis_conn();
    $atime=array(date("YmdH",time()-3600));
    $adate=array(date("Ymd",time()-3600));

    for($i=0;$i<count($atime);$i++) {
        $time = $atime[$i];
        $date = $adate[$i];

        $qd_table_click = "qd_click_$time";
        $qd_table_pv = "qd_pv_$time";

        $qdlist=array();

        $qd_click_list = $cache_redis->redis->hKeys($qd_table_click);
        foreach($qd_click_list as $qdclick) {
            $qd=substr($qdclick,2);
            $click=$cache_redis->redis->hget($qd_table_click,$qdclick);
            $qdlist[$qd]['click']=$click;
        }

        $qd_pv_list = $cache_redis->redis->hKeys($qd_table_pv);
        foreach($qd_pv_list as $qdpv) {
            $qd=substr($qdpv,2);
            $pv=$cache_redis->redis->hget($qd_table_pv,$qdpv);
            $qdlist[$qd]['pv']=$pv;
        }

        foreach ($qdlist as $qd=>$data) {
            $qd=addslashes($qd);
            $click = intval($qdlist[$qd]['click']);
            $pv = intval($qdlist[$qd]['pv']);


            $ip = 0;

            if ($click || $pv || $ip ) {
                //$cache_redis->del($qd_table_click);
                //$cache_redis->del($qd_table_pv);

                $t1 = strtotime($time."0000");
                $t2 = strtotime($time."5959");
                $sql="select count(*) from jieqi_system_users where regdate>=$t1 and regdate<=$t2 and source='$qd'";
                $r1=mysql_query($sql);
                if (!$r1) {
                    echo $sql."\n".mysql_error()."\n";
                }
                $d1=mysql_fetch_array($r1);
                $regcount=intval($d1[0]);

                $sql = "select * from jieqi_system_qddata where qd='$qd' and time='$time'";
                $r1 = mysql_query($sql);
                if (!$r1) {
                    echo $sql . "\n" . mysql_error() . "\n";
                }
                if (!mysql_num_rows($r1)) {
                    $sql = "insert into jieqi_system_qddata (qd,click,pv,ip,reg,`time`) values('$qd','$click','$pv','$ip','$regcount', '$time') ";
                    mysql_query($sql);
                } else {
                    $sql = "update jieqi_system_qddata set click='$click',pv='$pv',ip='$ip',reg='$regcount' where qd='$qd' and `time`='$time'";
                    mysql_query($sql);
                }
                echo $sql;
                echo mysql_error() . "\n";
            }
        }
    }
}

function dump_qd_stat_test() {
    global $cache_redis;
    redis_conn();
    $atime=array(date("YmdH",time()));
    $adate=array(date("Ymd",time()));

    for($i=0;$i<count($atime);$i++) {
        $time = $atime[$i];
        $date = $adate[$i];

        $qd_table_click = "qd_click_$time";
        $qd_table_pv = "qd_pv_$time";

        $qdlist=array();

        $qd_click_list = $cache_redis->redis->hKeys($qd_table_click);
        foreach($qd_click_list as $qdclick) {
            $qd=substr($qdclick,2);
            $click=$cache_redis->redis->hget($qd_table_click,$qdclick);
            $qdlist[$qd]['click']=$click;
        }

        $qd_pv_list = $cache_redis->redis->hKeys($qd_table_pv);
        //print_r($qd_pv_list);
        //continue;
        foreach($qd_pv_list as $qdpv) {
            $qd=substr($qdpv,2);
            $pv=$cache_redis->redis->hget($qd_table_pv,$qdpv);
            $qdlist[$qd]['pv']=$pv;
        }



        foreach ($qdlist as $qd=>$data) {
            $qd=addslashes($qd);
            $click = intval($qdlist[$qd]['click']);
            $pv = intval($qdlist[$qd]['pv']);


            $ip = 0;

            if ($click || $pv || $ip ) {
                //$cache_redis->del($qd_table_click);
                //$cache_redis->del($qd_table_pv);

                $t1 = strtotime($time."0000");
                $t2 = strtotime($time."5959");
                $sql="select count(*) from jieqi_system_users where regdate>=$t1 and regdate<=$t2 and source='$qd'";
                $r1=mysql_query($sql);
                if (!$r1) {
                    echo $sql."\n".mysql_error()."\n";
                }
                $d1=mysql_fetch_array($r1);
                $regcount=intval($d1[0]);

                $sql = "select * from jieqi_system_qddata where qd='$qd' and time='$time'";
                $r1 = mysql_query($sql);
                if (!$r1) {
                    echo $sql . "\n" . mysql_error() . "\n";
                }
                if (!mysql_num_rows($r1)) {
                    $sql = "insert into jieqi_system_qddata (qd,click,pv,ip,reg,`time`) values('$qd','$click','$pv','$ip','$regcount', '$time') ";
                    mysql_query($sql);
                } else {
                    $sql = "update jieqi_system_qddata set click='$click',pv='$pv',ip='$ip',reg='$regcount' where qd='$qd' and `time`='$time'";
                    mysql_query($sql);
                }
                echo $sql."\n";
                continue;
                echo mysql_error() . "\n";
            }
        }
    }
}

//获取当前这一小时的数据
function get_qd_stat($qd) {
    $time=date("YmdH");
    global $cache_redis;
    redis_conn();
    $qd_key_click = "qd_{$qd}_click_$time";
    $qd_key_pv = "qd_{$qd}_pv_$time";
    $ip_key = "qdip_{$qd}_{$time}";

    $qd_table_click = "qd_click_$time";
    $qd_table_pv = "qd_pv_$time";

    $click = intval($cache_redis->mget($qd_table_click,"qd".$qd));
    $pv = intval($cache_redis->mget($qd_table_pv,"qd".$qd));
    $ip = 0;

    $t1 = strtotime($time."0000");
    $t2 = strtotime($time."5959");
    $sql="select count(*) from jieqi_system_users where regdate>=$t1 and regdate<=$t2 and source='$qd'";
    $r1=mysql_query($sql);
    if (!$r1) {
        echo $sql."\n".mysql_error()."\n";
    }
    $d1=mysql_fetch_array($r1);
    $regcount=intval($d1[0]);

    return array('time'=>$time,"click"=>$click,'pv'=>$pv,'ip'=>$ip,'reg'=>$regcount);
}