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


$sql="select * from jieqi_article_article where articleid=$bookid and display=0 ";
$r=mysql_query($sql);
if (!$r) {
    echo $sql."\n".mysql_error()."\n";
}
$article=mysql_fetch_array($r);
if (!$article) {
    die("404");
}

$sql="select * from jieqi_article_chapter where articleid=$bookid  and display=0 and chaptertype=0 order by chapterorder";

$r=mysql_query($sql);

if (!$r) {
    echo $sql."\n".mysql_error()."\n";
}
$chapterlist=array();
$chaplist=array();
while ($c=mysql_fetch_array($r,MYSQL_ASSOC)) {
    foreach($c as $field=>$val) {
        $chapterlist[$field]=iconv("gbk","utf-8",$val);
    }
    $chaplist[]=$chapterlist;

}


echo json_encode($chaplist);