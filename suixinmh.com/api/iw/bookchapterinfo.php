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
$chapterid=intval($_REQUEST['chapterid']);


$sql="select a.* from jieqi_article_chapter a,jieqi_article_article b where a.articleid=b.articleid and b.display=0 and  b.articleid=$bookid and a.chapterid=$chapterid ";
$r=mysql_query($sql);
$d=mysql_fetch_array($r,MYSQL_ASSOC);
$dir=floor($bookid/1000);
$content=addslashes(file_get_contents(dirname(__FILE__)."/../../files/article/c_txt_www/$dir/$bookid/$chapterid.txt"));
$content_list=array();
foreach($d as $field=>$val) {
    $content_list[$field]=iconv("gbk","utf-8",$val);
}
$content_list['content'] = iconv("gbk","utf-8",$content);
echo json_encode($content_list);