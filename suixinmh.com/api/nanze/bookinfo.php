<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/14
 * Time: ÏÂÎç10:26
 */
include("config.php");
include("../db.php");
include("../../configs/article/sort.php");
$bookid=intval($_REQUEST['bookid']);


$sql="select * from jieqi_article_article where articleid=$bookid";
$sql.=" and firstflag=3";
$r=mysql_query($sql);
$d=mysql_fetch_array($r);
$dir=floor($d['articleid']/1000);
$imgs="http://www.ishufun.net/files/article/image/$dir/".$d['articleid']."/".$d['articleid']."s.jpg";
$imgl="http://www.ishufun.net/files/article/image/$dir/".$d['articleid']."/".$d['articleid']."l.jpg";

if ($d['lastchaptervip']) {
    $free=0;
}
else {
    $free=1;
}

if ($d['fullflag']) {
    $status=2;
}
else {
    $status=1;
}
$class=iconv("GBK","UTF-8",$jieqiSort['article'][$d['sortid']]['caption']);

$xml='<?xml version="1.0" encoding="utf-8" ?>
<book>
<id><![CDATA['.$d['articleid'].']]></id>
<name><![CDATA['.$d['articlename'].']]></name>
<class><![CDATA['.$jieqiSort['article'][$d['sortid']]['caption'].']]></class>
<author><![CDATA['.$d['author'].']]></author>
<bookintr><![CDATA['.addslashes($d['intro']).']]></bookintr>
<smallimg><![CDATA['.$imgs.']]></smallimg>
<bigimg><![CDATA['.$imgl.']]></bigimg>
<words><![CDATA['.$d['size'].']]></words>
<keywords><![CDATA['.$d['keywords'].']]></keywords>
<tag><![CDATA['.$d['tags'].']]></tag>
<free><![CDATA['.$free.']]></free>
<status><![CDATA['.$status.']]></status>
<updatetime><![CDATA['.$d['lastupdate'].']]></updatetime>
</book>';

echo iconv("GBK","UTF-8",$xml);