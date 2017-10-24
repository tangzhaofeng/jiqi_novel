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


$sql="select * from jieqi_article_article where articleid=$bookid and (firstflag in(1,2,3,5,6) or articleid in (10914,11315))";
$r=mysql_query($sql);
$d=mysql_fetch_array($r);
$dir=floor($d['articleid']/1000);
$imgs="http://".$_SERVER['SERVER_NAME']."/files/article/image/$dir/".$d['articleid']."/".$d['articleid']."s.jpg";
$imgl="http://".$_SERVER['SERVER_NAME']."/files/article/image/$dir/".$d['articleid']."/".$d['articleid']."l.jpg";

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

$sql="select count(*) from jieqi_article_chapter where articleid=$bookid and display=0";
$r1=mysql_query($sql);
$d1=mysql_fetch_array($r1);
$chaptercount=$d1[0];

$sql="select count(*) from jieqi_article_chapter where articleid=$bookid and isvip=0 and display=0";
$r1=mysql_query($sql);
$d1=mysql_fetch_array($r1);
$free_chapter=$d1[0];

$sql="select sum(saleprice) from jieqi_article_chapter where articleid=$bookid and isvip=1 and display=0";
$r1=mysql_query($sql);
$d1=mysql_fetch_array($r1);
$price=$d1[0];



$r=mysql_query($sql);
$xml = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
        <data>';

$xml .= "<cname><![CDATA[]></cname>
         <bookname><![CDATA[".iconv("GBK","UTF-8",$d['articlename'])."]></bookname>
         <bookid><![CDATA[".$d['articleid']."]></bookid>
         <bookpic><![CDATA[".$imgl."]></bookpic>
         <zzjs><![CDATA[".iconv("GBK","UTF-8",$d['intro'])."]></zzjs>
         <authorname><![CDATA[".iconv("GBK","UTF-8",$d['author'])."]></authorname>
         <createtime><![CDATA[".$d['postdate']."]></createtime>
         <bksize><![CDATA[".$d['size']."]></bksize>
         <weekvisit><![CDATA[]></weekvisit>
         <monthvisit><![CDATA[]></monthvisit> 
         <allvisit><![CDATA[]></allvisit>
         <writestatus><![CDATA[".$status."]></writestatus>
         <license><![CDATA[".$license."]></license>
         </data>\n";


echo iconv("GBK","UTF-8",$xml);