<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/16
 * Time: ÏÂÎç4:30
 */
include("/www/new.ishufun.net/global.php");

mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS);
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");

$articleid=intval($_GET['articleid']);

$chapterlist=array();
$sql="select * from jieqi_article_chapter where articleid=$articleid order by chapterorder";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    $chaptername=iconv("GBK","UTF-8",$d['chaptername']);
    //echo $chaptername."\n";
    $chapterlist[]=array(
        "chapterid"=>$d['chapterid'],
        'chaptername'=>$chaptername
    );
}
$j=json_encode($chapterlist);
echo $j;
//echo iconv("gbk","utf-8",$j);