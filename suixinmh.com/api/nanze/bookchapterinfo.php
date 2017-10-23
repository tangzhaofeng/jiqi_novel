<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/14
 * Time: ÏÂÎç10:26
 */
include("config.php");
include("../db.php");
$bookid=intval($_REQUEST['bookid']);
$chapid=intval($_REQUEST['chapid']);


$sql="select * from jieqi_article_chapter a,jieqi_article_article b where a.articleid=b.articleid and b.display=0 and  b.articleid=$bookid and a.chapterid=$chapid";
$sql.=" and firstflag=3";
$r=mysql_query($sql);
$d=mysql_fetch_array($r);
$dir=floor($bookid/1000);
$content=addslashes(file_get_contents("/www/new.ishufun.net/files/article/c_txt_www/$dir/$bookid/$chapid.txt"));
$content = str_replace("\n","</p><p>",$content);
$content = "<p>".$content."</p>";

$xml='<?xml version="1.0" encoding="utf-8" ?>
<chapter>
<id><![CDATA['.$d['chapterid'].']]></id>
<title><![CDATA['.$d['chaptername'].']]></title>
<updatetime><![CDATA['.$d['lastupdate'].']]></updatetime>
<words><![CDATA['.(round($d['size']/2)).']]></words>
<priceunit><![CDATA[5]]></priceunit>
<price><![CDATA['.$d['saleprice'].']]></price>
<vip><![CDATA['.$d['isvip'].']]></vip>
<content><![CDATA['.$content.']]></content>
</chapter>';

echo iconv("GBK","UTF-8",$xml);