<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/14
 * Time: ÏÂÎç10:26
 */
include("config.php");
include("db.php");
$bookid=intval($_REQUEST['bookid']);


$sql="select * from jieqi_article_article where articleid=$bookid and display=0 and (firstflag in(1,2,3,5,6) or articleid in (10914,11315))";
$r=mysql_query($sql);
$article=mysql_fetch_array($r);
if (!$article) {
    die("404");
}

$sql="select * from jieqi_article_chapter where articleid=$bookid order by chapterorder";

$r=mysql_query($sql);


$chapterlist=array();
while ($c=mysql_fetch_array($r)) {
    $chapterid=$c['chapterid'];
    $chaptername=$c['chaptername'];
    $isvip=$c['isvip'];
    $updatetime=date("Y-m-d H:i:s",$c['lastupdate']);
    $chapterlist[]=array(
        'chapterid'=>$chapterid,
        'isvip'=>$isvip,
        'chaptername'=>iconv("GBK","utf-8",$chaptername)
    );
}


echo json_encode($chapterlist);