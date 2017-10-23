<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/16
 * Time: ÏÂÎç4:30
 */
include("/www/mmd6666.com/global.php");

mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS);
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");

$articleid=intval($argv[1]);

$localaid=intval($argv[2]);


$url="http://tools.ishufun.net/sync_books.php?articleid=$articleid";
$content=file_get_contents($url);
$chapterlist=json_decode($content);
foreach($chapterlist as $chapter) {
    $sql="select * from jieqi_article_chapter where articleid=$localaid and chaptername = '".iconv("UTF-8","GBK",$chapter->chaptername)."'";
    //echo $sql."\n";
    $r=mysql_query($sql);
    $d=mysql_fetch_array($r);
    echo $chapter->chaptername.",".$d['chapterid']."\n";

    $dir1=floor($articleid/1000);
    $dir2=floor($localaid/1000);
    $txturl="http://www.ishufun.net/files/article/c_txt_www/$dir1/$articleid/".$chapter->chapterid.".txt";
    $localtxt="/www/mmd6666.com/files/article/c_txt_www/$dir2/$localaid/".$d['chapterid'].".txt";

    echo $txturl."\n";
    echo $localtxt."\n\n";

    $txtcontent=file_get_contents($txturl);
    if ($txtcontent) {
        file_put_contents($localtxt,$txtcontent);
    }
}