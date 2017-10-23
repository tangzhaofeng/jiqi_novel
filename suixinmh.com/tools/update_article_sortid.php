<?php
include(dirname(__FILE__)."/../configs/define.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME) || die("select error\n");

include(dirname(__FILE__)."/../global.php");
include_once(dirname(__FILE__)."/../modules/article/lib/my_article.php");
$lib_article = new MyArticle();


$sql="select * from jieqi_article_chapter where saleprice=0 and isvip=1 and chaptertype=0  order by articleid";

$r=mysql_query($sql);
$aid=0;
while ($d=mysql_fetch_array($r)) {
    if (!$aid) {
        $aid=$d['articleid'];
    }
    $check_aid=$d['articleid'];
    if ($aid && $aid!=$check_aid) {
        echo "repack article:$aid\n";
        $lib_article->article_repack($aid, array('makeopf'=>1));
        $aid=$check_aid;
    }

    $chapterid=$d['chapterid'];

    $dir=floor($check_aid/1000);
    $txt=dirname(__FILE__)."/../files/article/c_txt_www/$dir/$check_aid/$chapterid.txt";
    $content=file_get_contents($txt);
    $size=iconv_strlen($content,"GBK");
    $saleprice=round($size*5/1000);

    $sql="update jieqi_article_chapter set saleprice=$saleprice where chapterid=$chapterid";
    mysql_query($sql);
    //echo $chapterid."\n";
    echo $sql."\n";


}
if ($check_aid) {
    echo "repack article:$check_aid\n";
    $lib_article->article_repack($check_aid, array('makeopf' => 1));
}
