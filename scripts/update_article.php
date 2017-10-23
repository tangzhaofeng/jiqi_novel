<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/5/1
 * Time: 上午1:01
 */
$articleid=$argv[1];
$filename=$argv[2];
if (!$articleid || !$filename) {
    die("articleid/filename needed\n");
}


include("/www/new.ishufun.net/global.php");
include("/www/new.ishufun.net/configs/define.php");

mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("connect error\n");
mysql_select_db(JIEQI_DB_NAME) || die("select db error\n");
mysql_query("set names gbk");

$f=fopen($filename,'r');
if (!$f) {
    die("open file error\n");
}


$sql="select * from jieqi_article_article where articleid=$articleid";
$r=mysql_query($sql);
$d=mysql_fetch_array($r,MYSQL_ASSOC);
if (!$d) {
    die("article not found\n");
}
$articlename=$d['articlename'];

$order = 0;

//$content=file_get_contents($filename);
//$x=explode("\n",$content);
//echo count($x);
//
//
//exit();
$isvip=0;
$chapter="";
$chapter_content="";

while (!feof($f)) {
    $s=fgets($f);
    $s1=trim($s);
    if (!$s1) {
        continue;
    }
    $s1=str_replace("　","",$s1);
    $x=explode('、',$s1);
    //if (is_numeric($x[0])){
    if (iconv_strlen($s,"utf-8")<=30 &&  strpos($s,'第') !==false && strpos($s,'章') !==false) {
    //if (count($x) == 2 && iconv_substr($x[0],0,1,'UTF-8')=='第' && iconv_substr($x[0],iconv_strlen($x[0],"UTF-8")-1,1,'UTF-8')=="章") {
        if ($order) {
            //echo $chapter."\n";
            if ($order > 200 && $chapter) {
                $result = insert_chapter($articleid, $articlename, $chapter, $chapter_content, $order, $isvip);
                if ($result['chapterorder']) {
                    $order = $result['chapterorder'];
                    $isvip=$result['isvip'];
                }
            }

            $chapter_content="";
        }
        $chapter=$s1;
        $order++;
    }
    else {
        $chapter_content.=$s;
    }


    //exit();
}
insert_chapter($articleid,$articlename,$chapter,$chapter_content,$order,$isvip);

update_article_lastchapter($articleid);



function insert_chapter($articleid,$articlename,$chapter,$chapter_content,$chapterorder,$isvip) {
    echo "chapter=$chapter\n";
    $id1=floor($articleid/1000);
    $bash_path="/www/new.ishufun.net/files/article/c_txt_www/$id1";
    if (!is_dir($bash_path)) {
        mkdir($bash_path);
    }
    if (!is_dir($bash_path."/$articleid")) {
        mkdir($bash_path."/$articleid");
    }

    $time=time();
    $size=iconv_strlen($chapter_content,'utf-8');
//    echo $chapter.",$size\n";
//    return;
    $c=$chapter;
    $chapter=iconv("UTF-8","gb18030",trim($chapter));
    $chapter_content=iconv("UTF-8","gb18030",$chapter_content);


    $sql="select * from jieqi_article_chapter where articleid=$articleid and chaptername='$chapter'";
    //echo iconv("gb18030","UTF-8",$sql)."\n";
    //exit();
    $r1=mysql_query($sql);
    if (!$r1) {
        echo $sql."\n".mysql_error()."\n";
        exit();
    }
    $d1=mysql_fetch_array($r1);
    if ($d1) {
        return array('chapterorder'=>$d1['chapterorder'],'isvip'=>$d1['isvip']);
    }


    $size=strlen($chapter_content);

    $sql="insert into jieqi_article_chapter(articleid,articlename,posterid,poster,postdate,lastupdate,chaptername,chapterorder,size,manual,attachment,comment,commentdate,isvip) values($articleid,'$articlename',2,'admin',$time,$time,'$chapter',$chapterorder,$size,'','','','0','$isvip')";
    echo iconv("gbk","utf-8",$sql)."\n";

    if (mysql_query($sql)) {
        $chapterid=mysql_insert_id();
        echo $c.",".$chapterid."\n";
        $txt_filename=$bash_path."/$articleid/$chapterid.txt";
        file_put_contents($txt_filename,$chapter_content);
        chown($txt_filename,"www");
    }
    else {
        echo mysql_error()."\n";
    }

}



function update_article_lastchapter($articleid) {

    $sql="select * from jieqi_article_chapter where articleid=$articleid order by chapterorder desc limit 1";
    $r=mysql_query($sql);
    $d=mysql_fetch_array($r);
    if ($d) {
        $chaptername=addslashes($d['chaptername']);
        $chapterid=$d['chapterid'];
        $isvip=$d['isvip'];
    }
    $t=time();
    $sql="update jieqi_article_article set lastchapterid='$chapterid',lastchapter='$chaptername',lastchaptervip='$isvip',lastupdate=$t where articleid=$articleid";
    mysql_query($sql);
}

































