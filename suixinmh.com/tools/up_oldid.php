<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/6/22
 * Time: обнГ3:35
 */
include_once("../global.php");
include_once("../lib/database/redis.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");


$id1=10027;
$id2=10153;

$sql="select * from jieqi_article_chapter where articleid=$id1 order by chapterorder";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    $chaptername=$d['chaptername'];
    $x=explode("ё╨",$chaptername);
    $chapterid=$x[0];
    $sql="select * from jieqi_article_chapter where articleid=$id2 and chaptername like '$chaptername'";
    $r1=mysql_query($sql);
    //if (mysql_num_rows($r1) == 1) {
    //    $d1=mysql_fetch_array($r1);
    if ($d1=mysql_fetch_array($r1)) {

    }
    else {
        $chaptername = str_replace(array("ё║","ё©"),"",$chaptername);
        $sql="update jieqi_article_chapter set chaptername='$chaptername' where chapterid=".$d['chapterid'];
        mysql_query($sql);
        echo $sql."\n";
        echo iconv("gbk","utf-8",$chaptername)." not found\n";
    }
}