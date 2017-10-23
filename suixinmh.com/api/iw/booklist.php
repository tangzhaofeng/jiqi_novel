<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/14
 * Time: ÏÂÎç10:26
 */
include("config.php");
include("db.php");
$params=$_REQUEST;
$begin_time=intval($params['begin_time']);
$end_time=intval($params['end_time']);
$sortid=intval($params['sortid']);


$sql="select * from jieqi_article_article where display=0 ";

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

while ($d=mysql_fetch_array($r,MYSQL_ASSOC)) {
    $dir=floor($d['articleid']/1000);
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

echo json_encode($booklist);
