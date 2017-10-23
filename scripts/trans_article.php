<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/5/4
 * Time: ÏÂÎç2:15
 */
//include("/www/new.ishufun.net/global.php");
include("/www/new.ishufun.net/configs/define.php");
$conn_tr=mysql_connect("10.47.50.58","trans_data","123456");
mysql_select_db("xiaoshuo");
mysql_query("set names gbk");

($conn = mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS)) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME,$conn) || die("select error\n");
mysql_query("set names gbk",$conn);


$sql="select * from jieqi_article_article where display=0";
$r=mysql_query($sql,$conn_tr);
while ($d=mysql_fetch_array($r,MYSQL_ASSOC)) {
    import_article($d);
}


function import_article($article) {
    $article['firstflag'] = 13;
    global $conn,$conn_tr;
    $sql="select * from jieqi_article_article where articleid=".$article['articleid'];
    $r=mysql_query($sql,$conn);
    $d=mysql_fetch_array($r);
    if ($d && $d['display']==0) {
        mysql_query("update jieqi_article set firstflag=13,display=0 where articleid=".$article['articleid'],$conn);
    }
    if (!mysql_num_rows($r)) {
        $sql="insert into jieqi_article_article set ";
        $data=array();
        foreach($article as $field=>$value) {
            $data[]="`$field`='".addslashes($value)."'";
        }
        $sql.=implode(',',$data);
        if (mysql_query($sql,$conn)) {
            $articleid = mysql_insert_id();
        }
        else {
            echo $sql."\n";
            echo mysql_error($conn);
            exit();
        }
    }
    import_chapter($article['articleid']);
}

function import_chapter($articleid) {
    global $conn,$conn_tr;
    $sql="select * from jieqi_article_chapter where articleid=$articleid";
    $r=mysql_query($sql,$conn_tr);
    while ($d=mysql_fetch_array($r,MYSQL_ASSOC)) {
        $cid_p=$d['chapterid'];
        $chapterorder = $d['chapterorder'];
        //echo $cid_p."\n";
        $sql="select * from jieqi_article_chapter where articleid=$articleid and chapterid=".$d['chapterid'];
        echo $sql."\n";
        $r1=mysql_query($sql,$conn);
        if (mysql_num_rows($r1)) {
            //echo ".";
            continue;
        }
        else {
            $sql="select * from chapter_tr a,jieqi_article_chapter b where a.cid_i=b.chapterid and a.aid=$articleid and a.cid_p=$cid_p";
            echo $sql."\n";
            $r1=mysql_query($sql,$conn);
            if (mysql_num_rows($r1)) {
                //echo "*";
                continue;
            }
        }
        $sql="select * from jieqi_article_chapter where chapterid=".$d['chapterid'];
        $r1=mysql_query($sql,$conn);
        if (mysql_num_rows($r1)) {
            unset($d['chapterid']);
        }
        $sql="insert into jieqi_article_chapter set ";
        $data=array();
        foreach($d as $field=>$value) {
            if (!$field) {
                continue;
            }
            $data[]="`$field`='".addslashes($value)."'";
        }
        $sql.=implode(',',$data);

        if (mysql_query($sql,$conn)) {
            echo "$articleid,$cid_p\n";
            $pinshu_txt = "http://www.pinshu.net/files/article/c_txt_files/".(floor($articleid/1000))."/".$articleid."/".$cid_p.".txt";
            $txtfile = "/www/new.ishufun.net/files/article/c_txt_www/".(floor($articleid/1000))."/$articleid/$chapterid.txt";
            if (!is_dir(dirname($txtfile))) {
                mkdir(dirname($txtfile),0755);
                chown(dirname($txtfile),"www");
            }
            $content = file_get_contents($pinshu_txt);
            file_put_contents($txtfile,$content);
            chown($txtfile,"www");
            if (!$d['chapterid']) {
                $chapterid = mysql_insert_id();
                $sql="insert into chapter_tr  (aid,cid_p,cid_i,corder) values('$articleid','$cid_p','$chapterid','$chapterorder')";
                mysql_query($sql,$conn);
            }
            else {
                $chapterid=$d['chapterid'];
            }
        }
        else {
            echo $sql."\n";
            echo mysql_error($conn)."\n";
        }
    }
}