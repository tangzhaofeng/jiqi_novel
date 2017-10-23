<?php
 /**==============================
  * 公共函数库文件
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 ==================================*/
//*************************************字符串处理函数集***********************************
/*utf8中文字串截取函数*/
function strcut($str,$start,$len){
 if($start < 0)
  $start = strlen($str)+$start;
 $retstart = $start+getOfFirstIndex($str,$start);
 $retend = $start + $len -1 + getOfFirstIndex($str,$start + $len);
 return substr($str,$retstart,$retend-$retstart+1);
}
//判断字符开始的位置
function getOfFirstIndex($str,$start){
 $char_aci = ord(substr($str,$start-1,1));
 if(223<$char_aci && $char_aci<240)
  return -1;
 $char_aci = ord(substr($str,$start-2,1));
 if(223<$char_aci && $char_aci<240)
  return -2;
 return 0;
}
/*$char必须为英文字符,全是返回1true，有中文返回0false*/
function getChar($char) {
    return (ctype_alpha($char));
}
/*匹配$QQ(5-12)位,是返回1,否返回0*/
function getQQ($QQ) {
   return preg_match("/^\b[0-9]{5,12}\b/",$QQ);
}
/*检查$str是否为数字,是返回true,否返回false*/
function CheckIsNum($str){
      return ereg("^[0-9]+$",$str) ? true : false;
}
/*若标题$str过长，显示前$len个字符，剩余字符用...代替*/
function showShort($str,$len=0)
{
if(!$len)return $str;
$tempstr = csubstr($str,0,$len);
if ($str<>$tempstr)$tempstr .= "..."; //要以什么结尾,修改这里就可以.
return $tempstr;
}
/*计算$str字符数并按$len数截取,两个中文或一个英文等于一字节*/
function csubstr($str,$start=0,$len=0)
{
if(!$len)return $str;
$tmpstr="";
$start=0;
$strlen=strlen($str);
$clen=0;
for($i=0;$i<$strlen;$i++,$clen++)
{
if ($clen>=$start+$len)
break;
if(ord(substr($str,$i,1))>0xa0)
{
if ($clen>=$start) $tmpstr.=substr($str,$i,2);
$i++;
} else {
if ($clen>=$start) $tmpstr.=substr($str,$i,1);
}
}
return $tmpstr;
}
/*用户输入内容过滤函数*/
function getStr($str) {
    $tmpstr = trim($str);
    $tmpstr = strip_tags($tmpstr);
    $tmpstr = htmlspecialchars($tmpstr);
    $tmpstr = addslashes($tmpstr);
    return $tmpstr;
}
/*容量大小计算函数*/
function sizecount($filesize) {
        if($filesize >= 1073741824) {
                $filesize = round($filesize / 1073741824 * 100) / 100 . ' G';
        } elseif($filesize >= 1048576) {
                $filesize = round($filesize / 1048576 * 100) / 100 . ' M';
        } elseif($filesize >= 1024) {
                $filesize = round($filesize / 1024 * 100) / 100 . ' K';
        } else {
                $filesize = $filesize . ' bytes';
        }
        return $filesize;
}
/*简单防SQL注入函数*/
function getSQL($feild) {
    $tmpfeild = mysql_escape_string($feild);
    return $tmpfeild;
}
/*$num必须为英文字符或数字0-9*/
function getNums($num) {
    return (ctype_alnum($num));
}
/*匹配电子邮件地址*/
function getEmail($email) {
    return strlen($email)>6 && preg_match("/^\w+@(\w+\.)+[com]|[cn]$/" , $email);
}
/*生成email连接*/
function emailconv($email,$tolink=1) {
        $email=str_replace(array('@','.'),array('@','.'),$email);
        return $tolink ? '<a href="mailto: '.$email.'">'.$email.'</a>':$email;
}
/*检查ip是否被允许访问,$ip是正在访问的IP
$accesslist是设定禁止访问的IP,多个设定用"|"分隔,如:127.0.0.1|245.235.12.23|125.14.23.1
能访问返true,不能访问返false*/
function CheckIPOk($ip,$accesslist) {
$ip=CheckIsIP($ip) ? $ip : "127.0.0.1";//防假IP登陆
$CheckIP=0;
$Checknum=0;
$list = explode( "|",$accesslist);//取得分割字符串值
$listnum = substr_count($accesslist,"|");//取得分割切入点次数
for($i=0;$i<$listnum+1;$i++){
$CheckIP += preg_match("/^(".str_replace(array("\r\n",' '),array('|',''),preg_quote($accesslist[$i],'/')).")/",$ip);
}
return $ip ? false : true;
}
/*验证$ip地址函数,真IP返回true,假IP返回false*/
function CheckIsIP($ip){
return !strcmp(long2ip(sprintf('%u',ip2long($ip))),$ip) ? true : false;
}
/*获得客户端ip地址,调用方法getIP()*/
function getIP() {
        if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown")) {
                $ip = getenv("HTTP_CLIENT_IP");
        }
        else if(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"),"unknown")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
        }
        else if(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"),"unknown")) {
                $ip = getenv("REMOTE_ADDR");
        }
        else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],"unknown")) {
                $ip = $_SERVER['REMOTE_ADDR'];
        }
        else {
                $ip = "unknown";
        }
        return CheckIsIP($ip) ? $ip : "unknown" ;
}
/* 判断action文件存在*/
function is_action($actionModule){
    if($actionModule !=''){
        if(!file_exists(ACTION_DIR . $actionModule . '.class.php')){
            if(isAjax())
                ajaxReturn($lang['error']['404'], 300);
            header("Location: ".URL_INDEX."?action=public&opt=error404");
        }
        return true;
    }
    return false;
}
/* 判断是否ajax请求*/
function isAjax() {
    //jquery设定ajax请求的$_SERVER['HTTP_X_REQUESTED_WITH'] = XMLHttpRequest
    if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
        return true;
    return false;
}
/* ajax异步返回*/
function ajaxReturn($info='', $status=200){
    $result=array();
    $result['statusCode']  =  $status;
    $result['message'] =  $info;
    $result['navTabId']  =  isset($_REQUEST['navTabId'])?$_REQUEST['navTabId']:'';
    $result['rel'] =  $info;
    $result['callbackType']  =  $_REQUEST['callbackType'];	// closeCurrent
    $result['forwardUrl']  =  isset($_REQUEST['forwardUrl'])?$_REQUEST['forwardUrl']:'';
    // 返回JSON数据格式到客户端 包含状态信息
    header("Content-Type:text/html; charset=utf-8");
    
    exit(json_encode($result));
}
/*获取提示信息*/
function C($var){
    global $_GLOBALLANG;
    return $_GLOBALLANG[$var];
 }
//数据检查过滤
 function checkRData($data){
        if(is_array($data)){
            foreach($data as $key => $v){
                $data[$key] = getStr($v);
            }
        }else{
            $data = getStr($data);
        }
        return $data;
    }
 //检查权限
 function checkgrant($module, $option){
     return true;
 }
 //页面跳转
 function toUrl($url){
    switch ($url) {
        case 'LOGIN':
            header("Location: ".URL_INDEX."?action=public&opt=login");
            break;
        case 'INDEX':
            header("Location: ".URL_INDEX);
            break;
        case 'ERROR:404':
            header("Location: ".URL_INDEX."?action=public&opt=error404");
            break;
        default:
            header("Location: ".$url);
            break;
    }
 }
 //获取post返回
 function getPostRes($url,$params){ //获取请求信息
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$getRes = curl_exec($ch);
	curl_close($ch);
	return $getRes;
}
