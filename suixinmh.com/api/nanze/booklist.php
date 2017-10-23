<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/14
 * Time: ÏÂÎç10:26
 */
include("config.php");
include("../db.php");
$params=$_REQUEST;
$begin_time=intval($params['begin_time']);
$end_time=intval($params['end_time']);


$sql="select * from jieqi_article_article where display=0";

if ($begin_time) {
    $sql.=" and lastupdate>=".strtotime($begin_time);
}
if ($end_time) {
    $sql.=" and lastupdate<=".strtotime($end_time);
}
$sql.=" and firstflag=3";
//$sql .= " and articleid in(".implode(',',$booklist).")";
$sql.=" order by articleid";

$r=mysql_query($sql);
$xml='<?xml version="1.0" encoding="utf-8" ?> <document> <webName><![CDATA['.$webname.']]></webName> <pubDate><![CDATA['.$pubdate.']]></pubDate> <books>
';


while ($d=mysql_fetch_array($r)) {
    $xml.="<book><id><![CDATA[".$d['articleid']."]]></id> <name><![CDATA[".$d['articlename']."]]></name></book>\n";
}
$xml.='
</books> </document>
';

echo iconv("GBK","UTF-8",$xml);
