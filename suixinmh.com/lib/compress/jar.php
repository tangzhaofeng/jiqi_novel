<?php
include_once(JIEQI_ROOT_PATH.'/lib/compress/zip.php');
class JieqiJar
{
var $bookinfo=array('id'=>0,'title'=>'jar book','author'=>'author','sort'=>'default','publisher'=>'','seller'=>'DIY_GENERATED','corver'=>'',copyright=>'CopyRight(C) jieqi.com');
var $chapters = array();
var $charset = 'GBK';
var $jar_fd;
var $chaptercount=0;
var $articlelen=0;
var $chaptitlelen=0;
function JieqiJar(){
}
function setcharset($charset){
$this->charset = $charset;
}
function setinfo($infoary = array()){
foreach($this->bookinfo as $k=>$v){
if(isset($infoary[$k])) $this->bookinfo[$k] = $infoary[$k];
if($k != 'id'&&$this->charset != 'UTF-8') $this->bookinfo[$k]=iconv($this->charset,'UTF-8//IGNORE',$this->bookinfo[$k]);
}
}
function addchapter($title,$content){
if($this->charset != 'UTF-8'){
$title=iconv($this->charset,'UTF-8//IGNORE',$title);
}
if($this->charset != 'UCS'){
$content=iconv($this->charset,'UCS-2LE//IGNORE',$content);
}
$this->chapters[$this->chaptercount] = array('title'=>$title,'content'=>$content);
$this->chaptercount++;
$this->chaptitlelen+=strlen($title);
$this->articlelen+=strlen($content);
}
function makejar($jarfile = '',$jadfile = ''){
$zip=new JieqiZip();
$zip->zipstart($jarfile);
$zip->zipadd('a.class',jieqi_readfile(JIEQI_ROOT_PATH.'/lib/compress/jar/a.class'));
$zip->zipadd('b.class',jieqi_readfile(JIEQI_ROOT_PATH.'/lib/compress/jar/b.class'));
$zip->zipadd('c.class',jieqi_readfile(JIEQI_ROOT_PATH.'/lib/compress/jar/c.class'));
$zip->zipadd('d.class',jieqi_readfile(JIEQI_ROOT_PATH.'/lib/compress/jar/d.class'));
$zip->zipadd('e.class',jieqi_readfile(JIEQI_ROOT_PATH.'/lib/compress/jar/e.class'));
$zip->zipadd('f.class',jieqi_readfile(JIEQI_ROOT_PATH.'/lib/compress/jar/f.class'));
$zip->zipadd('g.class',jieqi_readfile(JIEQI_ROOT_PATH.'/lib/compress/jar/g.class'));
$zip->zipadd('FormBook.class',jieqi_readfile(JIEQI_ROOT_PATH.'/lib/compress/jar/FormBook.class'));
$zip->zipadd('icon.png',jieqi_readfile(JIEQI_ROOT_PATH.'/lib/compress/jar/icon.png'));
$zip->zipadd('Java.png',jieqi_readfile(JIEQI_ROOT_PATH.'/lib/compress/jar/Java.png'));
$maininest='Manifest-Version: 1.0'."\r\n".'MicroEdition-Configuration: CLDC-1.0'."\r\n".'MIDlet-Name: '.$this->bookinfo['title']."\r\n".'MIDlet-Version: 1.0'."\r\n".'MIDlet-Vendor: JIEQI CMS (www.jieqi.com)'."\r\n".'MIDlet-1: '.$this->bookinfo['title'].', /icon.png, FormBook'."\r\n".'MicroEdition-Profile: MIDP-1.0'."\r\n".'Created-By: 1.4.2 (Sun Microsystems Inc.)'."\r\n";
$zip->zipadd('META-INF/MANIFEST.MF',$maininest);
$index='';
$index.=chr(0).chr(1).'0';
$index.=chr(0).chr(strlen($this->bookinfo['title'])).$this->bookinfo['title'];
$chaptercount=strval(count($this->chapters));
$index.=chr(0).chr(strlen($chaptercount)).$chaptercount;
$i=1;
foreach($this->chapters as $k=>$v){
$zip->zipadd($i.'.txt',chr(0xFF).chr(0xFE).$v['content']);
$tmpstr=$i.'.txt,'.(strlen($v['content'])+2).','.$v['title'];
$index.=chr(0).chr(strlen($tmpstr)).$tmpstr;
$i++;
}
$index.=chr(0).chr(strlen($this->bookinfo['copyright'])).$this->bookinfo['copyright'];
$zip->zipadd('index',$index);
$zip->setComment('create by jieqi cms (www.jieqi.com)');
$zip->zipend();
$filesize = filesize($jarfile);
$jadstr='Manifest-Version: 1.0'."\r\n".'MIDlet-1: '.$this->bookinfo['title'].', /icon.png, FormBook'."\r\n".'MIDlet-Jar-Size: '.$filesize."\r\n".'MIDlet-Jar-URL: '.basename($jarfile)."\r\n".'MIDlet-Name: '.$this->bookinfo['title']."\r\n".'MIDlet-Vendor: JIEQI CMS (www.jieqi.com)'."\r\n".'MIDlet-Version: 1.0'."\r\n".'MicroEdition-Configuration: CLDC-1.0'."\r\n".'MicroEdition-Profile: MIDP-1.0'."\r\n";
if(empty($jadfile)){
$i=strrpos($jarfile,'.');
if($i>0) $jadfile=substr($jarfile,0,$i);
$jadfile .= '.jad';
}
jieqi_writefile($jadfile,$jadstr);
}
}
