<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/30
 * Time: ÉÏÎç12:40
 */
define("CONSUMEKEY","24330805");
define("SECRETKEY","7xW7kTXhwne2MmD6");

function sign($method,$url,$param) {
    $s=strtoupper($method).$url;
    $keyarr=array();
    foreach($param as $key=>$val) {
        $keyarr[]=$key;
    }
    array_multisort($keyarr,SORT_ASC,$param);
    foreach($param as $k=>$v) {
        $s.="$k=$v";
    }
    $s.=SECRETKEY;
    echo "s=$s\n";
    $sign = MD5(urlencode($s));
    return $sign;
}


function booklist() {
    $testurl="http://testdistapi.yuedu.163.com/neptune/xml/bookList.xml";
    $url="http://distapi.yuedu.163.com/neptune/xml/bookList.xml";
    $param=array(
        "consumerKey"=>CONSUMEKEY,
        "timestamp"=>time()*1000,
        //"expires"=>time()+600
    );
    $qarr=array();
    foreach($param as $k=>$v) {
        $qarr[]="$k=$v";
    }
    $querystring=implode('&',$qarr);
    $sign=sign("GET",$url,$param);
    $request=$url."?$querystring&sign=$sign";
    //echo $request."\n";
    header("location:$request");
}

function bookinfo($bookid) {
    $testurl="http://testdistapi.yuedu.163.com/neptune/xml/bookInfo.xml";
    $url="http://distapi.yuedu.163.com/neptune/xml/bookInfo.xml";
    $param=array(
        "consumerKey"=>CONSUMEKEY,
        "timestamp"=>time()*1000,
        //"expires"=>time()+600,
        "bookId"=>$bookid
    );
    $qarr=array();
    foreach($param as $k=>$v) {
        $qarr[]="$k=$v";
    }
    $querystring=implode('&',$qarr);
    $sign=sign("GET",$url,$param);
    $request=$url."?$querystring&sign=$sign";
    //echo $request."\n";
    header("location:$request");
}

function catlog($bookid) {
    $testurl="http://testdistapi.yuedu.163.com/neptune/xml/sectionList.xml";
    $url="http://distapi.yuedu.163.com/neptune/xml/sectionList.xml";
    $param=array(
        "consumerKey"=>CONSUMEKEY,
        "timestamp"=>time()*1000,
        //"expires"=>time()+600,
        "bookId"=>$bookid
    );
    $qarr=array();
    foreach($param as $k=>$v) {
        $qarr[]="$k=$v";
    }
    $querystring=implode('&',$qarr);
    $sign=sign("GET",$url,$param);
    $request=$url."?$querystring&sign=$sign";
    //echo $request."\n";
    header("location:$request");
}

function chapter($bookid,$chapterid) {
    $testurl="http://testdistapi.yuedu.163.com/neptune/xml/sectionContent.xml";
    $url="http://distapi.yuedu.163.com/neptune/xml/sectionContent.xml";
    $param=array(
        "consumerKey"=>CONSUMEKEY,
        "timestamp"=>time()*1000,
        //"expires"=>time()*1000+600,
        "bookId"=>$bookid,
        "sectionId"=>$chapterid
    );
    $qarr=array();
    foreach($param as $k=>$v) {
        $qarr[]="$k=$v";
    }
    $querystring=implode('&',$qarr);
    $sign=sign("GET",$url,$param);
    $request=$url."?$querystring&sign=$sign";
    echo $request."\n";
    header("location:$request");
}
//booklist();
//exit();
switch ($_GET['action']) {
    case "booklist":booklist();break;
    case "bookinfo":bookinfo(trim($_GET['bookid']));break;
    case "catlog":catlog(trim($_GET['bookid']));break;
    case "chapter":chapter(trim($_GET['bookid']),trim($_GET['chapterid']));break;
    default:die("bad_action");
}