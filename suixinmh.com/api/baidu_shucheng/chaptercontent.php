<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/14
 * Time: ÏÂÎç10:26
 */
include("config.php");
include("db.php");
$bookid    = intval($_REQUEST['bookid']);
$chapterid = intval($_REQUEST['chapterid']);



$sql="select a.* from jieqi_article_chapter a,jieqi_article_article b where a.articleid=b.articleid and b.display=0 and  b.articleid=$bookid and a.chapterid=$chapterid and (b.firstflag in(1,2,3,5,6) or b.articleid in (10914,11315))";
$r=mysql_query($sql);
//$d=mysql_fetch_array($r);
$dir=floor($bookid/1000);
$content=addslashes(file_get_contents(dirname(__FILE__)."/../../files/article/c_txt_www/$dir/$bookid/$chapterid.txt"));


$xml = '<?xml version="1.0" encoding="utf-8" standalone="yes"  ?>
        <content><![CDATA['.$content.']></content>';

echo iconv("GBK","UTF-8",$xml);


