<?php
class MyCreateexcel extends JieqiObject {
var $inEncode;
var $outEncode;
function __construct(){
}
function setEncode($incode,$outcode){
$this->inEncode=$incode;
$this->outEncode=$outcode;
}
function setTitle($titlearr){
$title="";
foreach($titlearr as $v){
if($this->inEncode!=$this->outEncode){
$title.=iconv($this->inEncode,$this->outEncode,$v)."\t";
}
else{
$title.=$v."\t";
}
}
$title.="\n";
return $title;
}
function setRow($array){
$content="";
foreach($array as $k =>$v){
foreach($v as $vs){
if($this->inEncode!=$this->outEncode){
$content.=iconv($this->inEncode,$this->outEncode,$vs)."\t";
}
else{
$content.=$vs."\t";
}
}
$content.="\n";
}
return $content;
}
function getExcel($titlearr,$array,$filename=''){
if($filename==''){
$filename=date("Y-m-d");
}
$title=$this->setTitle($titlearr);
$content=$this->setRow($array);
$p_new_lines = array("\r\n","\n","\r","\r\n","<pre>","</pre>","<br>","</br>","<br/>");
$p_change_line_in_excel_cell = '<br style="mso-data-placement:same-cell;" />';
header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:attachment;filename=".$filename.".xls");
echo $title;
echo $content;
}
}
