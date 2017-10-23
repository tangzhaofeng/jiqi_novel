<?php
class JieqiUmd
{
var $bookinfo=array('id'=>0,'title'=>'umd book','author'=>'author','year'=>'0','month'=>'0','date'=>'0','sort'=>'default','publisher'=>'','seller'=>'DIY_GENERATED','corver'=>'');
var $chapters = array();
var $charset = 'GBK';
var $umd_fd;
var $chaptercount=0;
var $articlelen=0;
var $chaptitlelen=0;
function JieqiUmd(){
$this->bookinfo['year']=date('Y');
$this->bookinfo['month']=date('n');
$this->bookinfo['date']=date('j');
}
function setcharset($charset){
$this->charset = $charset;
}
function setinfo($infoary = array()){
foreach($this->bookinfo as $k=>$v){
if(isset($infoary[$k])) $this->bookinfo[$k] = $infoary[$k];
if($k != 'id'&&$this->charset != 'UCS') $this->bookinfo[$k]=iconv($this->charset,'UCS-2LE//IGNORE',$this->bookinfo[$k]);
}
}
function addchapter($title,$content){
if($this->charset != 'UCS'){
$title=iconv($this->charset,'UCS-2LE//IGNORE',$title);
$content=iconv($this->charset,'UCS-2LE//IGNORE',str_replace("\r","",$content));
}
$this->chapters[$this->chaptercount] = array('title'=>$title,'content'=>$content);
$this->chaptercount++;
$this->chaptitlelen+=strlen($title);
$this->articlelen+=strlen($content);
}
function makeumd($umdfile = ''){
$this->umd_fd = @fopen($umdfile,'wb');
if(!$this->umd_fd) return false;
@flock($this->umd_fd,LOCK_EX);
$data='';
$data.=chr(0x89).chr(0x9B).chr(0x9A).chr(0xDE);
$data.=chr(0x23).chr(0x01).chr(0x00);
$data.=chr(0x00).chr(0x08);
$data.=chr(0x01);
$pgkeed=rand(0x401,0x7FFF);
$data.=$this->umddechex($pgkeed,2);
$data.=$this->umdmakeinfo($this->bookinfo['title'],2);
$data.=$this->umdmakeinfo($this->bookinfo['author'],3);
$data.=$this->umdmakeinfo($this->bookinfo['year'],4);
$data.=$this->umdmakeinfo($this->bookinfo['month'],5);
$data.=$this->umdmakeinfo($this->bookinfo['date'],6);
$data.=$this->umdmakeinfo($this->bookinfo['sort'],7);
$data.=$this->umdmakeinfo($this->bookinfo['publisher'],8);
$data.=$this->umdmakeinfo($this->bookinfo['seller'],9);
fputs($this->umd_fd,$data,strlen($data));
$data='';
$data.=chr(0x23).chr(0x0B).chr(0x00).chr(0x00).chr(0x09);
$data.=$this->umddechex($this->articlelen +($this->chaptercount * 2),4);
$data.=chr(0x23).chr(0x83).chr(0x00).chr(0x01).chr(0x09);
$tmpnum=rand(0x3000,0x3FFF);
$data.=$this->umddechex($tmpnum,4);
$data.=chr(0x24);
$data.=$this->umddechex($tmpnum,4);
$tmpnum=$this->chaptercount * 4 +9;
$data.=$this->umddechex($tmpnum,4);
$spoint=0;
foreach($this->chapters as $i=>$chapter){
$data.=$this->umddechex($spoint,4);
$spoint+=strlen($chapter['content']) +2;
}
$data.=chr(0x23).chr(0x84).chr(0x00).chr(0x01).chr(0x09);
$tmpnum=rand(0x4000,0x4FFF);
$data.=$this->umddechex($tmpnum,4);
$data.=chr(0x24);
$data.=$this->umddechex($tmpnum,4);
$tmpnum=9+$this->chaptitlelen+$this->chaptercount;
$data.=$this->umddechex($tmpnum,4);
foreach($this->chapters as $i=>$chapter){
$tmpnum=strlen($chapter['title']);
$data.=$this->umddechex($tmpnum,1);
$data.=$chapter['title'];
}
fputs($this->umd_fd,$data,strlen($data));
$point=0;
$psize=32768;
$content='';
foreach($this->chapters as $i=>$chapter){
$content.=$chapter['content'].chr(0x29).chr(0x20);
}
$clen=strlen($content);
$packnum=ceil($clen / $psize);
$rnd1=rand(0,$packnum-1);
$rand2=rand(0,$packnum-1);
$rndary=array();
for($i=0;$i<$packnum;$i++){
$data='';
$data.=chr(0x24);
$rnd_content=rand(0xF0000001,0xFFFFFFFE);
$rndary[$i]=$rnd_content;
$data.=$this->umddechex($rnd_content,4);
$tmpdata = substr($content,$point,$psize);
$point+=$psize;
$tmpgz=gzcompress($tmpdata);
$tmpnum=9+strlen($tmpgz);
$data.=$this->umddechex($tmpnum,4);
$data.=$tmpgz;
if($i==$rnd1){
$data.=chr(0x23).chr(0xF1).chr(0x00).chr(0x00).chr(0x15).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00).chr(0x00);
}
if($i==$rnd2){
$data.=chr(0x23).chr(0x0A).chr(0x00).chr(0x00).chr(0x09);
$data.=$this->umddechex($this->bookinfo['id']+0x10000000,4);
}
fputs($this->umd_fd,$data,strlen($data));
}
$data='';
$data.=chr(0x23).chr(0x81).chr(0x00).chr(0x01).chr(0x09);
$tmpnum=rand(0x2000,0x2FFF);
$data.=$this->umddechex($tmpnum,4);
$data.=chr(0x24);
$data.=$this->umddechex($tmpnum,4);
$tmpnum = 9 +($packnum * 4);
$data.=$this->umddechex($tmpnum,4);
for($i=0;$i<$packnum;$i++){
$data.=$this->umddechex($rndary[$i],4);
}
fputs($this->umd_fd,$data,strlen($data));
$data='';
if(!empty($this->bookinfo['corver']) &&is_file($this->bookinfo['corver'])){
$data.=chr(0x23).chr(0x82).chr(0x00).chr(0x01).chr(0x0A).chr(0x01);
$tmpnum=rand(0x1000,0x1FFF);
$data.=$this->umddechex($tmpnum,4);
$data.=chr(0x24);
$data.=$this->umddechex($tmpnum,4);
$corver_content=file_get_contents($this->bookinfo['corver']);
$tmpnum=strlen($corver_content)+9;
$data.=$this->umddechex($tmpnum,4);
$data.=$corver_content;
fputs($this->umd_fd,$data,strlen($data));
$data='';
}
$tmpnum1=$this->articlelen +($this->chaptercount * 2);
$tmpnum2=rand(0x7000,0x7FFF);
$data.=chr(0x23).chr(0x87).chr(0x00).chr(0x01).chr(0x0B).chr(0x10).chr(0xD0);
$data.=$this->umddechex($tmpnum2,4);
$data.=chr(0x24);
$data.=$this->umddechex($tmpnum2,4);
$tmpnum=0x11;
$data.=$this->umddechex($tmpnum,4);
$tmpnum=0x00;
$data.=$this->umddechex($tmpnum,4);
$data.=$this->umddechex($tmpnum1,4);
$data.=chr(0x23).chr(0x87).chr(0x00).chr(0x01).chr(0x0B).chr(0x10).chr(0xB0);
$data.=$this->umddechex($tmpnum2,4);
$data.=chr(0x24);
$data.=$this->umddechex($tmpnum2,4);
$tmpnum=0x11;
$data.=$this->umddechex($tmpnum,4);
$tmpnum=0x00;
$data.=$this->umddechex($tmpnum,4);
$data.=$this->umddechex($tmpnum1,4);
$data.=chr(0x23).chr(0x87).chr(0x00).chr(0x01).chr(0x0B).chr(0x0C).chr(0xD0);
$data.=$this->umddechex($tmpnum2,4);
$data.=chr(0x24);
$data.=$this->umddechex($tmpnum2,4);
$tmpnum=0x11;
$data.=$this->umddechex($tmpnum,4);
$tmpnum=0x00;
$data.=$this->umddechex($tmpnum,4);
$data.=$this->umddechex($tmpnum1,4);
$data.=chr(0x23).chr(0x87).chr(0x00).chr(0x01).chr(0x0B).chr(0x0C).chr(0xB0);
$data.=$this->umddechex($tmpnum2,4);
$data.=chr(0x24);
$data.=$this->umddechex($tmpnum2,4);
$tmpnum=0x11;
$data.=$this->umddechex($tmpnum,4);
$tmpnum=0x00;
$data.=$this->umddechex($tmpnum,4);
$data.=$this->umddechex($tmpnum1,4);
$data.=chr(0x23).chr(0x87).chr(0x00).chr(0x05).chr(0x0B).chr(0x0A).chr(0xA6);
$data.=$this->umddechex($tmpnum2,4);
$data.=chr(0x24);
$data.=$this->umddechex($tmpnum2,4);
$tmpnum=0x11;
$data.=$this->umddechex($tmpnum,4);
$tmpnum=0x00;
$data.=$this->umddechex($tmpnum,4);
$data.=$this->umddechex(floor($tmpnum1/2),4);
$data.=chr(0x23).chr(0x0C).chr(0x00).chr(0x01).chr(0x09);
$tmpnum=4+strlen($data)+ftell($this->umd_fd);
$data.=$this->umddechex($tmpnum,4);
fputs($this->umd_fd,$data,strlen($data));
$data='';
flock($this->umd_fd,LOCK_UN);
fclose($this->umd_fd);
chmod($umdfile,0777);
}
function umdmakeinfo($instr,$order){
$retstr=chr(0x23).chr($order).chr(0x00).chr(0x00);
$retstr.=$this->umddechex(strlen($instr)+5,1);
$retstr.=$instr;
return $retstr;
}
function umddechex($num,$bytes){
$retstr = '';
$bytes = $bytes * 2;
$tmpvar=substr(sprintf('%0'.$bytes.'s',dechex($num)),0 -$bytes);
for($i=0;$i<$bytes;$i+=2){
$retstr=chr(hexdec(substr($tmpvar,$i,2))).$retstr;
}
return $retstr;
}
}
