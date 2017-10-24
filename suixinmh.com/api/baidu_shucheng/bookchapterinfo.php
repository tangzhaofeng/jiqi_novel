<?php
/**
 * Created by PhpStorm.
 * User: tangzhaofeng
 * Date: 17/10/23
 * Time: 19:51
 */
include("config.php");
include("db.php");
$bookid=intval($_REQUEST['bookid']);
$chapterid=intval($_REQUEST['chapterid']);


$sql="select a.* from jieqi_article_chapter a,jieqi_article_article b where a.articleid=b.articleid and b.display=0 and  b.articleid=$bookid and a.chapterid=$chapterid and (b.firstflag in(1,2,3,5,6) or b.articleid in (10914,11315))";
$r=mysql_query($sql);
$d=mysql_fetch_array($r);
$dir=floor($bookid/1000);
$content=addslashes(file_get_contents(dirname(__FILE__)."/../../files/article/c_txt_www/$dir/$bookid/$chapterid.txt"));
//$content = str_replace("\n","</p><p>",$content);
//$content = "<p>".$content."</p>";
$content_list=array(
    'bookid'=>$bookid,          # 书籍id
    'volumeid'=>1,        # 卷id
    'volumename'=>iconv("GBK","UTF-8","正文"),      # 卷名
    'chapterid'=> iconv("GBK","UTF-8",$d['chapterid']),      # 章节序号
    'chaptername'=>iconv("GBK","UTF-8",$d['chaptername']),     # 章节名
    'chaptersize'=>round($d['size']/2),     # 字数（个）
    'chaptercontent'=>iconv("GBK","utf-8",$content),  # 章节内容
    'isvip' => $d['isvip'],
    'price'=>$d['saleprice']>0 ? $d['saleprice'] : round($d['size']*5/2000),
    'createtime'=>$d['postdate'],      # 入库时间
    'lastupdatetime'=>$d['lastupdate']  # 最后更新时间
);

echo json_encode($content_list);