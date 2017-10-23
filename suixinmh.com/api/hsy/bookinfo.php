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

$keywords=str_replace(' ','&',$d['keywords']);

$bookinfo=array(
    'volumecount'=>1,
    'createtime'=>$d['postdate'],
    'ClassId'=>$d['sortid'],
    'title'=>iconv("GBK","UTF-8",$d['articlename']),
    'BookID'=>$d['articleid'],
    'BookPic'=>$imgl,
    'description'=>iconv("GBK","UTF-8",$d['intro']),
    'author'=>iconv("GBK","UTF-8",$d['author']),
    'free'=>1,
    'CreateTime'=>$d['postdate'],
    'WordNum'=>$d['size'],
    'ChapterURL'=>$chapter_url,
    'keyword'=>$keywords,
    'bookstatus'=>$status,
    'chaptercount'=>$chaptercount,
    'price'=>$price,
    'total_words'=>round($d['size']/2),
    'bookid'=>$bookid,
    'cover_url'=>$imgl,
    'cpname'=>'jinsebook.com',
    'class'=>iconv("GBK","UTF-8",$config['sort_c'][$d['sortid']]),
    'lastupdatetime'=>$d['lastupdate'],
    'free_chapter'=>$free_chapter
);

echo json_encode($bookinfo);