<?php
class TextFilter extends JieqiObject
{
var $badwords=array();
var $hidewords=array();
var $replacewords=array();
function loadBadwords(&$badwords)
{
if(is_array($badwords)){
$this->badwords=$badwords;
}
}
function loadHidewords(&$hidewords)
{
if(is_array($hidewords)){
$this->hidewords=$hidewords;
}
}
function loadReplacewords(&$replacewords)
{
if(is_array($replacewords)){
$this->replacewords=$replacewords;
}
}
function checkBadwords(&$text)
{
$ret=true;
if(count($this->badwords)>0){
foreach($this->badwords as $v){
if($ret &&strlen($v)>0 &&!empty($v)){
if(strstr($text,$v)) $ret=false;
}
}
}
return $ret;
}
function doHidewords($text,$replace='***')
{
if(count($this->hidewords)>0){
$text = str_replace($this->hidewords,$replace,$text);
return $text;
}else{
return $text;
}
}
function doReplacewords($text)
{
if(count($this->replacewords)>0){
$from=array();
$to=array();
foreach($this->replacewords as $k=>$v){
$from[]=$k;
$to[]=$v;
}
return str_replace($from,$to,$text);
}else{
return $text;
}
}
function checkRubbish(&$text)
{
$ret=false;
$len=strlen($text);
$specialnum=0;
$tmpstr="";
$tmpstr1="";
$renum=0;
for($i=0;$i<$len;$i++){
if(ord($text[$i])>0x80){
$tmpstr=$text[$i].$text[$i+1];
$i++;
}else{
$tmpstr=$text[$i];
$tmpasc=ord($text[$i]);
if($tmpasc<0x41 ||($tmpasc>0x5a &&$tmpasc<0x61) ||$tmpasc>0x7a){
$specialnum++;
}
}
if($tmpstr==$tmpstr1){
$renum++;
if($renum>4){
return true;
}
}else{
$renum=0;
}
if($tmpstr != ' ') $tmpstr1=$tmpstr;
}
return $ret;
}
}
