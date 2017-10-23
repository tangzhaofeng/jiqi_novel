<?php
include("/www/new.ishufun.net/configs/define.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME) || die("select error\n");
mysql_query("set names GBK");


//$sql="select * from jieqi_article_article where articleid<10000 and display=1";
//$r=mysql_query($sql);
//while ($d=mysql_fetch_array($r)) {
//    remove_article($d['articleid']);
//}
//
//function remove_article($articleid) {
//    $sql="delete from jieqi_article_article where articleid=$articleid and articleid<10000";
//    mysql_query($sql);
//    echo $sql."\n";
//
//    $sql="delete from jieqi_article_chapter where articleid=$articleid and articleid<10000";
//    mysql_query($sql);
//    echo $sql."\n";
//    $dir=floor($articleid/1000);
//
//    $cmd="rm /www/new.ishufun.net/files/article/c_txt_www/$dir/$articleid -R -f";
//    exec($cmd);
//    echo $cmd."\n";
//
//    $cmd="rm /www/new.ishufun.net/files/article/image/$dir/$articleid -R -f";
//    exec($cmd);
//    echo $cmd."\n";
//
//}


$chapterlist=array();

$articleid=$argv[1];
$sql="select * from jieqi_article_chapter where articleid=$articleid order by chapterorder";
$r=mysql_query($sql);
while ($d=mysql_fetch_array($r)) {
    //echo $d['chaptername']."\n";
    $x=explode('£º',$d['chaptername']);
    //print_r($x);
    $chaptername=trim($x[1]);
    $found=false;
    foreach($chapterlist as $c) {
        if ($c['chaptername']==$chaptername) {
            $found = true;
            echo iconv("gbk","utf-8",$chaptername)." exists\n";
            remove_chapter($articleid,$d['chapterid']);
            break;
        }
    }
    if (!$found) {
        $d['chaptername']=$chaptername;
        $chapterlist[]=$d;
    }
}


function remove_chapter($articleid,$chapterid) {
    $id=floor($articleid/1000);
    $txt="../files/article/c_txt_www/$id/$articleid/$chapterid.txt";
    unlink($txt);
    mysql_query("delete from jieqi_article_chapter where articleid=$articleid and chapterid=$chapterid");
}