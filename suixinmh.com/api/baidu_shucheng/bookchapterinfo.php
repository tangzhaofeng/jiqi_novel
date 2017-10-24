<?php
/**
 * Created by PhpStorm.
 * User: tangzhaofeng
 * Date: 17/10/23
 * Time: 19:51
 */
include("config.php");
include("db.php");
$bookid    = intval($_REQUEST['bookid']);

$sql="select a.* from jieqi_article_chapter a,jieqi_article_article b where a.articleid=b.articleid and b.display=0 and  b.articleid=$bookid and a.chapterid=$chapterid and (b.firstflag in(1,2,3,5,6) or b.articleid in (10914,11315))";
$r=mysql_query($sql);
//$d=mysql_fetch_array($r);
//$dir=floor($bookid/1000);
//$content=addslashes(file_get_contents(dirname(__FILE__)."/../../files/article/c_txt_www/$dir/$bookid/$chapterid.txt"));
//$content = str_replace("\n","</p><p>",$content);
//$content = "<p>".$content."</p>";

$chapterid = iconv("GBK","UTF-8",$d['chapterid']);


$xml = '<?xml version="1.0" encoding="utf-8" ?>
        <data>';

while ($d=mysql_fetch_array($r)) {
    $xml .= " <vol>
              <volumename><![CDATA[".iconv("GBK","UTF-8","ÕýÎÄ")."]></volumename>
              <url><![CDATA[".iconv("GBK","UTF-8","http://www.wkxwx.cn/baidu_shucheng/chaptercontent.php?chapterid='.$chapterid.'&bookid='.$bookid.'")."]></url>
              <chaptername><![CDATA[".iconv("GBK","UTF-8",$d['chaptername'])."]></chaptername>
              <chapterid><![CDATA[".iconv("GBK","UTF-8",$d['chapterid'])."]></chapterid>
              <license><![CDATA[".$d['isvip']."]></license>      
              </vol>\n";     
};
                            
$xml .= '<data>';

echo iconv("GBK","UTF-8",$xml);