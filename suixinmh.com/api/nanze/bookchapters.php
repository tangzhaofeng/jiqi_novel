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


$sql="select * from jieqi_article_article where articleid=$bookid";
$sql.=" and firstflag=3 and display=0";
$r=mysql_query($sql);
$article=mysql_fetch_array($r);

$sql="select * from jieqi_article_chapter where articleid=$bookid and display=0 order by chapterorder";

$r=mysql_query($sql);

$xml='<?xml version="1.0" encoding="utf-8" ?> <chaplist>
<volume>
<volumeid><![CDATA[0]]></volumeid> <volumename><![CDATA[]]></volumename> <chapters>
';
while ($c=mysql_fetch_array($r)) {
    $xml.='<chap>
<id><![CDATA[ '.$c['chapterid'].']]></id>
<title><![CDATA[ '.$c['chaptername'].']]></title>
<isvip>'.$c['isvip'].'</isvip>
</chap>
';
}

$xml.='
</chapters> </volume>
</chaplist>';

echo iconv("GBK","UTF-8",$xml);