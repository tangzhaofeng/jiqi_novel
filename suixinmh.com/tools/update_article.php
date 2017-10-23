<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/5/1
 * Time: 上午1:01
 */
$articleid=$argv[1];
$filename=$argv[2];
$up_date=$argv[3];
if (!$articleid || !$filename) {
    die("articleid/filename needed\n");
}


include("/www/new.ishufun.net/global.php");
include("/www/new.ishufun.net/configs/define.php");
include_once("/www/new.ishufun.net/modules/article/lib/my_article.php");
$lib_article = new MyArticle();

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


$sql="select * from jieqi_article_chapter where articleid=$articleid order by chapterorder desc limit 1";
$r1=mysql_query($sql);
$d1=mysql_fetch_array($r1);
$isvip=$d1['isvip'];
$lastorder=$d1['chapterorder'];
$last_chaptername = $d1['chaptername'];
$order=0;

$chapterlist=array();

while (!feof($f)) {
    $s=fgets($f);
    $s1=trim($s);
    $s1=str_replace("　","",$s1);
    $x=explode('、',$s1);
    if (iconv_strlen($s,"utf-8")<=30 &&  strpos($s,'第') !==false && strpos($s,'章') !==false) {
    //if (count($x) == 2 && iconv_substr($x[0],0,1,'UTF-8')=='第' && iconv_substr($x[0],iconv_strlen($x[0],"UTF-8")-1,1,'UTF-8')=="章") {

        //echo $chapter."\n";
        if ($order) {
            $chapter_data = array(
                "articleid" => $articleid,
                'articlename' => $articlename,
                'chaptername' => iconv("utf-8","GBK",$chapter),
                'chapter_content' => iconv("utf-8","GBK",$chapter_content),
                'chapterorder' => $order,
            );
            $chapterlist[] = $chapter_data;
            //insert_chapter($articleid,$articlename,$chapter,$chapter_content,$order);
            $chapter_content = "";
        }
        $chapter=$s1;

        $order++;
    }
    else {
        $chapter_content.=$s;
    }


    //exit();
}
$chapter_data = array(
    "articleid" => $articleid,
    'articlename' => $articlename,
    'chaptername' => iconv("utf-8","GBK",$chapter),
    'chapter_content' => iconv("utf-8","GBK",rtrim($chapter_content)),
    'chapterorder' => $order
);
$chapterlist[]=$chapter_data;
//print_r($chapterlist);


for($i=0;$i<count($chapterlist);$i++) {
    if ($chapterlist[$i]['chaptername'] == $last_chaptername) {
        break;
    }
}
//echo '$last_chaptername='.$last_chaptername."\n";
//echo "i=$i\n";
//exit();
$chapter_count=count($chapterlist)-$i;
if ($chapter_count>21) {
    $upnum=3;
}
else {
    $upnum=2;
}
$num=0;
if ($up_date) {
    $up_time=strtotime("$up_date 12:00:00");
}
else {
    $up_time = strtotime(date("Y-m-d 12:00:00"));
}
for ($ii=$i+1;$ii<count($chapterlist);$ii++) {
    $num++;
    if ($num>=$upnum) {
        $up_time+=86400+rand(0,300);
        $num=0;
    }
    if ($up_time>time()) {
        $chapterlist[$ii]['display'] = 2;
    }
    else {
        $chapterlist[$ii]['display'] = 0;
    }
    $chapterlist[$ii]['isvip'] = $isvip;
    if ($isvip) {
        $len=strlen($chapterlist[$ii]['chapter_content']);
        $price=round($len*5/2000);
        $chapterlist[$ii]['saleprice'] = $price;
    }
    else {
        $chapterlist[$ii]['saleprice'] = 0;
    }
    $chapterlist[$ii]['lastupdate'] = $up_time;
    $chapterlist[$ii]['postdate'] = $up_time;
    $chapterlist[$ii]['size'] = strlen($chapterlist[$ii]['chapter_content']);
    $chapterlist[$ii]['posterid'] = 1;
    $chapterlist[$ii]['poster'] = "admin";
    $chapterlist[$ii]['siteid'] = "100";
    $chapterlist[$ii]['manual'] = "";
    $chapterlist[$ii]['attachment'] = "";
    $chapterlist[$ii]['comment'] = "";
    $chapterlist[$ii]['commentdate'] = 0;







    insert_chapter($chapterlist[$ii]);

    echo iconv("gbk","utf-8",$chapterlist[$ii]['chaptername'])."\n";
    echo "len=$len.price=$price\n";
}

$lib_article->article_repack($articleid, array('makeopf'=>1));

exit();







function insert_chapter($chapterinfo) {
   // print_r($chapterinfo);
    $articleid = $chapterinfo['articleid'];
    $articlename = $chapterinfo['articlename'];
    $chapter = $chapterinfo['chaptername'];
    $chapter_content = $chapterinfo['chapter_content'];
    $chapterorder = $chapterinfo['chapterorder'];

    $id1=floor($articleid/1000);
    $bash_path="/www/new.ishufun.net/files/article/c_txt_www/$id1";
    if (!is_dir($bash_path)) {
        mkdir($bash_path);
    }
    if (!is_dir($bash_path."/$articleid")) {
        mkdir($bash_path."/$articleid");
    }


    $size=iconv_strlen($chapter_content,'utf-8');
//    echo $chapter.",$size\n";

    foreach($chapterinfo as $field=>$val) {
        if ($field=='chapter_content')
            continue;
        $fieldlist[]=$field;
        $vallist[]=addslashes($val);
    }
    if (!$fieldlist || !$vallist) {
        return false;
    }

    echo "------------------------begin-----------------------\n";
    //print_r($fieldlist);
    //print_r($vallist);

    $flist=implode(",",$fieldlist);
    $vlist="'".implode("','",$vallist)."'";


    $sql="insert into jieqi_article_chapter($flist) values($vlist)";
    //echo $sql."\n";
    //return true;
    if (mysql_query($sql)) {
        $chapterid=mysql_insert_id();
        echo $chapterid."\n";
        $txt_filename=$bash_path."/$articleid/$chapterid.txt";
        file_put_contents($txt_filename,$chapter_content);
        chown($txt_filename,"www");
    }
    else {
        echo mysql_error()."\n";
    }

}





































