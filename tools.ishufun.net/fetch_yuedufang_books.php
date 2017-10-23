<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/4/28
 * Time: ÏÂÎç5:56
 */

$config=array('sid'=>31,
    'key'=> 'h49s734bca43htoc62gd'
);
function sign($params,$config) {
    $x=explode('&',$params);
    sort($x);
    $s=implode('&',$x)."&key=".$config['key'];
    $sign=md5($s);
    return $sign;
}

function get_books_list($config) {
    $param="sid=".$config['sid']."&uptime=20141106083000&endtime=20161206083000&page=1";
    $sign=sign($param,$config);
    $url = "http://www.yuedufang.com/apis/jieqi/articlelist.php?$param&sign=$sign";
    $content= file_get_contents($url);
    $articlelist=json_decode($content);

    $result= "<books>\n";
    foreach($articlelist as $article) {
        foreach($article as $key=>$val) {
            $result.="<$key>$val</$key>\n";
        }
    }
    $result.="</books>\n";

    return $result;
}


function get_book_info($articleid,$config) {
    $param="sid=".$config['sid']."&aid=$articleid";
    $sign=sign($param,$config);
    $url = "http://www.yuedufang.com/apis/jieqi/articleinfo.php?$param&sign=$sign";
    $content= file_get_contents($url);
    $articleinfo=json_decode($content);
    $result = "<data>\n";
    foreach ($articleinfo as $key => $val) {
        $result .= "<$key>$val</$key>\n";
    }
    $result.="</data>\n";
    return $result;
}

function get_chapter_list($articleid,$config) {
    $param="sid=".$config['sid']."&aid=$articleid";
    $sign=sign($param,$config);
    $url="http://www.yuedufang.com/apis/jieqi/articlechapter.php?$param&sign=$sign";
    $content= file_get_contents($url);
    $chapterlist=json_decode($content);
    $result = "<data>\n";
    foreach($chapterlist as $article) {
        $result.="<chapter>\n";
        foreach($article as $key=>$val) {
            $result.="<$key>$val</$key>\n";
        }
        $result.="</chapter>\n";
    }
    $result.="</data>\n";
    return $result;
}

function get_chapter_data($articleid,$chapterid,$config) {
    $param="sid=".$config['sid']."&aid=$articleid&cid=$chapterid";
    $sign=sign($param,$config);
    $url="http://www.yuedufang.com/apis/jieqi/chaptercontent.php?$param&sign=$sign";
    $content= file_get_contents($url);
    $chapter=json_decode($content);
    $result = "<data>\n";
    foreach ($chapter as $key => $val) {
        $result .= "<$key>$val</$key>\n";
    }
    $result.="</data>\n";
    return $result;
}

$action=$_GET['action'];
switch($action) {
    case "articlelist":echo get_books_list($config);break;
    case "articleinfo":
        $aid=$_GET['aid'];
        echo get_book_info($aid,$config);
        break;
    case "chapterlist":
        $aid=$_GET['aid'];
        echo get_chapter_list($aid,$config);
        break;
    case "chapter":
        $aid=$_GET['aid'];
        $cid=$_GET['cid'];
        echo get_chapter_data($aid,$cid,$config);
        break;
}