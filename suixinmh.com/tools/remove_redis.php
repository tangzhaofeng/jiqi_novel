<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/6/8
 * Time: ÏÂÎç6:12
 */
include("../global.php");
include("../lib/database/redis.php");
$redis=new MyRedis();

$keys = $redis->keyAll();
foreach($keys as $key) {
    $x=explode('_',$key);
    if ($x[0] == 'qd') {
        $qd=$x[1];
        $dt=$x[2];
        if ($dt == 'click' || $dt=='pv') {
            $time = $x[3];
            if ($time < date("YmdH", time() - 86400)) {
                $redis->del($key);
                echo $key . "\n";
            }
        }
        else {
            $time = $x[2];
            if ($time<date("Ymd",time()-86400)) {
                $redis->del($key);
                echo $key . "\n";
            }

        }
    }
}