<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/14
 * Time: ÏÂÎç10:26
 * bookall neet var 
 * {code      | status          | 0=sccess ;other = fails }
 * {msg       | message status  | <![CDATA[]>             }
 * {total     | category total  | int                     }
 * {start     | category sort   |                         }
 * {count     | category count  | same total              }
 * {cate_id   |                 |                         }
 * {cate_name |                 |                         }
 */


include("config.php");
include("db.php");

Validation_IP($IP);

$xml='<?xml version="1.0" encoding="utf-8" ?>
      <result language="zh_CN" version="1.1">
<status>
<code><![CDATA[0]]></code>
<msg><![CDATA["³É¹¦"]]></msg>
</status>';


$sql="select * from jieqi_article_article where display=0 and (firstflag in(1,2,3,5,6) or articleid in (10914,11315))";


<updatetime><![CDATA['.$d['lastupdate'].']]></updatetime>
<words><![CDATA['.(round($d['size']/2)).']]></words>
<priceunit><![CDATA[5]]></priceunit>
<price><![CDATA['.$d['saleprice'].']]></price>
<vip><![CDATA['.$d['isvip'].']]></vip>
<content><![CDATA['.$content.']]></content>
</chapter>';
/*
$params=$_REQUEST;
$begin_time=intval($params['begin_time']);
$end_time=intval($params['end_time']);
$sortid=intval($params['sortid']);


$sql="select * from jieqi_article_article where display=0 and (firstflag in(1,2,3,5,6) or articleid in (10914,11315))";

if ($begin_time) {
    $sql.=" and lastupdate>=".strtotime($begin_time);
}
if ($end_time) {
    $sql.=" and lastupdate<=".strtotime($end_time);
}
if ($sortid) {
    $sql.=" and sortid=$sortid";
}
$sql.=" order by articleid";

$r=mysql_query($sql);


$booklist=array();

while ($d=mysql_fetch_array($r)) {
    $imgs="http://www.ishufun.net/files/article/image/$dir/".$d['articleid']."/".$d['articleid']."s.jpg";
    $imgl="http://www.ishufun.net/files/article/image/$dir/".$d['articleid']."/".$d['articleid']."l.jpg";
    $booklist[]=array(
        'bookid'=>$d['articleid'],
        'bookname'=>iconv("GBK","UTF-8",$d['articlename']),
        'BookPic'=>$imgl,
        'description'=>iconv("GBK","UTF-8",$d['intro']),
        'fullflag'=>$d['fullflag']
    );
}
$xml = new XMLWriter();
echo json_encode($booklist);
