<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 2017/1/4
 * Time: ÏÂÎç2:45
 */
include_once("../global.php");
include_once("../lib/database/redis.php");
include_once("../configs/article/option.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");


$sql="select * from jieqi_article_article where firstflag<>7 and articleid>=10000";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    $size=round($d['size']/10000).'Íò';
    $s=$d['articlename']."\t".$d['author']."\t";
    $fg=$d['firstflag'];
    $firstflag=$jieqiOption['article']['firstflag']['items'][$fg];
    $s.="\t".$firstflag."\t$size\n";
    echo iconv("gbk","utf-8",$s);
}