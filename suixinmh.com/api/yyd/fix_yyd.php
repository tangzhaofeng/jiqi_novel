<?
include_once(dirname(__FILE__) . "/../../global.php");
include_once(dirname(__FILE__) . "/../../core/lib/lib_database.php");
include_once(dirname(__FILE__) . "/../../modules/article/lib/my_article.php");
$db = new Database();
$lib_article = new MyArticle();

mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS);
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");

if (!$db) {
    die("db error\n");
}

$sql="select * from jieqi_article_article where firstflag=7";
$r=mysql_query($sql);

while ($d=mysql_fetch_array($r)) {
    $sql="select * from jieqi_article_chapter where articleid=".$d['articleid']." and chaptertype=0  order by chapterorder  desc";
    $r1=mysql_query($sql);
    $up=false;
    while ($d1=mysql_fetch_array($r1)) {
        $chaptername = $d1['chaptername'];
        $chapterorder = $d1['chapterorder'];
        $chapterid = $d1['chapterid'];
        $sql = "select * from jieqi_article_chapter where articleid=" . $d['articleid'] . " and chaptertype=0 and chaptername='$chaptername' and chapterorder<$chapterorder  ";
        $r2 = mysql_query($sql);
        $d2 = mysql_fetch_array($r2);
        if ($d2) {
            echo iconv("GBK","utf-8",$d['articleid'] . ",$chapterorder,$chaptername\n");
            del_chapter($d['articleid'],$chapterid);
            $up=true;
        }
    }
    if ($up) {
        $lib_article->article_repack($d['articleid'], array('makeopf'=>1));
    }
}


function del_chapter($articleid,$chapterid) {
    $dir=floor($articleid/1000);
    $txt="/www/new.ishufun.net/files/article/c_txt_www/$dir/$articleid/$chapterid.txt";
    if (!file_exists($txt)) {
        die("$txt not found\n");
    }
    else {
        unlink($txt);
    }
    mysql_query("delete from jieqi_article_chapter where articleid=$articleid and chapterid=$chapterid");
}