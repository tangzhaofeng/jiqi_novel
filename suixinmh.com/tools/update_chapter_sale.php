<?php
include(dirname(__FILE__)."/../configs/define.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME) || die("select error\n");

include(dirname(__FILE__)."/../global.php");
include_once(dirname(__FILE__)."/../modules/article/lib/my_article.php");
$lib_article = new MyArticle();


$articleid=$argv[1];


$sql="select * from jieqi_article_article where display=0";
if ($articleid) {
    $sql.=" and articleid=$articleid";
}
$r1=mysql_query($sql);
while ($d1=mysql_fetch_array($r1)) {
    $articleid=$d1['articleid'];


    $sql = "select * from jieqi_article_chapter where articleid=$articleid  and isvip=1 and saleprice=0 and chaptertype=0  order by articleid";

    $r = mysql_query($sql);
    if (!mysql_num_rows($r)) {
        continue;
    }
    $aid = 0;
    while ($d = mysql_fetch_array($r)) {
        if (!$aid) {
            $aid = $d['articleid'];
        }
        $check_aid = $d['articleid'];

        $chapterid = $d['chapterid'];

        //$dir=floor($check_aid/1000);
        //$txt=dirname(__FILE__)."/../files/article/c_txt_www/$dir/$check_aid/$chapterid.txt";
        //$content=file_get_contents($txt);
        $size = round($d['size'] / 2);
        $saleprice = round($size * 5 / 1000);
        if (!$saleprice) {
            continue;
        }

        //if ($d['saleprice'] > $saleprice) {
            $sql = "update jieqi_article_chapter set saleprice=$saleprice where chapterid=$chapterid";
            mysql_query($sql);
            //echo $chapterid."\n";
            echo $sql . "\n";
        //}
    }

    $lib_article->article_repack($articleid, array('makeopf' => 1));
    echo $articleid."\n";
}