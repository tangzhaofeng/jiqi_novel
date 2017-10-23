<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/6/10
 * Time: ÉÏÎç9:42
 */
include_once("../global.php");
include_once("../lib/database/redis.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");
$redis=new MyRedis();

$sql="select * from jieqi_article_article where articleid>=10000";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    echo iconv("GB18030","UTF-8",$d['articlename'])."\n";
    $sql="select * from jieqi_article_chapter where articleid=".$d['articleid'].' order by chapterorder limit 0,100';
    $r1=mysql_query($sql);
    $chapter_data=array();
    while ($d1=mysql_fetch_array($r1)) {
        $chapterid=$d1['chapterid'];
        $visit=$redis->hget('ChapterVisit',$chapterid);
        $chapter_data[]=array('chapterid'=>$chapterid,'visit'=>$visit,'isvip'=>$d1['isvip']);
    }
    for($i=0;$i<count($chapter_data);$i++) {
        if ($chapter_data[$i]['isvip'])
            echo $chapter_data[$i]['chapterid']."[v]\t";
        else
            echo $chapter_data[$i]['chapterid']."\t";
    }
    echo "\n";
    for($i=0;$i<count($chapter_data);$i++) {
        echo $chapter_data[$i]['visit']."\t";
    }
    echo "\n\n\n";
}