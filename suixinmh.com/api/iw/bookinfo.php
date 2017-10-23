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


$sql="select * from jieqi_article_article where articleid=$bookid and display=0";
$r=mysql_query($sql);
$d=mysql_fetch_array($r,MYSQL_ASSOC);
$dir=floor($d['articleid']/1000);
$imgs="http://www.ishufun.net/files/article/image/$dir/".$d['articleid']."/".$d['articleid']."s.jpg";
$imgl="http://www.ishufun.net/files/article/image/$dir/".$d['articleid']."/".$d['articleid']."l.jpg";

if ($d['lastchaptervip']) {
    $license=1;
}
else {
    $license=0;
}

if ($d['fullflag']) {
    $status=1;
}
else {
    $status=0;
}

foreach($d as $field=>$val) {
    $article[$field]=iconv("GBK","UTF-8",$val);
}


echo json_encode($article);