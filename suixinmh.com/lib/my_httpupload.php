<?php 
class HttpUpload{
var $upload_formname = 'file';
var $upload_savepath = './';
var $upload_mimetype = 'text,image,application,audio';
var $upload_fileextname = 'doc,docx,xls,ppt,wps,zip,rar,txt,jpg,jpeg,gif,bmp,swf,png';
var $upload_maxsize;
var $upload_filename;
var $upload_filerename = true;
var $upload_savedir;
var $upfile_file_error = 0;
var $upload_overwrite = 0;
var $upload_file;
var $upload_file_name;
var $upload_file_extname;
var $upload_file_tmpname;
var $upload_mime_types,$upload_mime_type;
var $upload_file_size;
function __set($sttribName,$value)
{
$this->$sttribName=$value;
}
function __get($sttribName)
{
return $this->$sttribName;
}
function HttpUpload($formname,$savepath,$mimetype,$fileextname,$maxsize,$filerename,$datedir,$overwrite){
if(isset($formname))  $this->__set("formname",$formname);
if(isset($savepath)) $this->__set("upload_savepath",$savepath);
if(isset($mimetype)) $this->__set("upload_mimetype",$mimetype);
if(isset($fileextname)) $this->__set("upload_fileextname",$fileextname);
if(isset($maxsize)) $this->__set("upload_maxsize",$maxsize);
else $this->__set("upload_maxsize",1024*1024*2);
if(isset($uploatype)) $this->__set("upload_filerename",$filerename);
$this->__set("upfile_file_error",0);
if(isset($datedir)) $this->__set("upload_savedir",$datedir);
else $this->__set("upload_savedir",date('y/m/d',time()));
if(isset($overwrite)) $this->__set("upload_overwrite",$overwrite);
$this ->upload_fileextname = strtolower($this ->upload_fileextname);
$this ->upload_fileextname = explode(",",$this ->upload_fileextname);
$this ->upload_fileextname = array_unique($this ->upload_fileextname);
$this ->upload_mimetype = strtolower($this ->upload_mimetype);
$this ->upload_mimetype = explode(",",$this ->upload_mimetype);
$this ->upload_mimetype = array_unique($this ->upload_mimetype);
$this->upload_file = $_FILES[$this ->formname];
$this->upload_file_name = $this->upload_file[name];
$this->upload_file_extname = strtolower(pathinfo($this->upload_file_name,PATHINFO_EXTENSION));
$this->upload_file_tmpname = $this->upload_file[tmp_name];
$this->upload_mime_types = $this->upload_file['type'];
$upload_mime_type = explode("/",$this->upload_mime_types);
$this->upload_mime_type = $upload_mime_type[0];
$this->upload_file_size = $this->upload_file[size];
}
function upfile(){
$upload_file_name =  $this->upload_file_name;
$upload_file_extname = $this->upload_file_extname;
$upload_file_tmpname = $this->upload_file_tmpname;
$upload_mime_type = $this->upload_mime_type;
$upload_file_size = $this->upload_file_size;
if($this->upload_file){
if($upload_file_size >$this ->upload_maxsize){
$this ->upfile_file_error = 1;
}
if(in_array($upload_mime_type ,$this ->upload_mimetype) == false){
$this ->upfile_file_error = 2;
}
if(in_array($upload_file_extname ,$this ->upload_fileextname) == false){
$this ->upfile_file_error = 3;
}
$upload_max_filesize = str_replace('M','',ini_get("upload_max_filesize"));
$post_max_size = str_replace('M','',ini_get("post_max_size"));
if($upload_max_filesize*1024*1024 <$this->upload_file_size ||$post_max_size*1024*1024 <$this->upload_file_size ){
$this ->upfile_file_error = 4;
}
if($this ->upfile_file_error == 0){
$upfile_file_newname = strtolower($upload_file_name);
if(!isset($this->upload_filename)){
$this ->createdir($this ->upload_savepath);
if($this ->upload_savedir){
$upfile_file_path = $this ->upload_savepath ."/".$this ->upload_savedir;
$upfile_file_path = $this ->createdir($upfile_file_path);
}
if($this ->upload_filerename == 1){
$upfile_file_newname = date("his") .$this->random().".".$upload_file_extname;
}
$upfile_file_path = $upfile_file_path ."/".$upfile_file_newname;
if($this ->upload_filerename){
$upload_file_overwrite = 0;
if(@move_uploaded_file($upload_file_tmpname,$upfile_file_path) == false){
$this ->upfile_file_error = 6;
}
}else{
if(@file_exists($upload_file_tmpname,$upfile_file_path) == true){
if($this ->upload_overwrite == 0){
$upload_file_overwrite = 2;
continue;
}
if($this ->upload_overwrite == 1){
$upload_file_overwrite = 3;
@unlink(realpath($upload_file_tmpname,$upfile_file_path));
if(@move_uploaded_file($upload_file_tmpname,$upfile_file_path) == false){
$this ->upfile_file_error = 6;
}
}
if($this ->upload_overwrite == 2){
$upload_file_overwrite = 4;
if(@move_uploaded_file($upload_file_tmpname,$upfile_file_path) == false){
$this ->upfile_file_error = 6;
}
}
}else{
$upload_file_overwrite = 1;
if(@move_uploaded_file($upload_file_tmpname,$upfile_file_path) == false){
$this ->upfile_file_error = 6;
}
}
}
}else {
$upfile_file_path = $this->upload_filename;
if(is_file(realpath($upfile_file_path))) @unlink(realpath($upfile_file_path));
if(@move_uploaded_file($upload_file_tmpname,$upfile_file_path) == false){
$this ->upfile_file_error = 6;
}
$upfile_file_newname = basename($this->upload_filename);
}
}
$up_file_return = array(
"upfile_file_error"=>$this ->upfile_file_error,
"upfile_file_newname"=>$upfile_file_newname,
"upfile_file_path"=>str_replace('\\','/',realpath($upfile_file_path)),
"upload_file_name"=>$upload_file_name,
"upload_mime_types"=>$this->upload_mime_types,
"upload_mime_type"=>$upload_mime_type,
"upload_file_extname"=>$upload_file_extname,
"upload_file_overwrite"=>$upload_file_overwrite,
"upload_file_size"=>$upload_file_size            
);
}
if(is_file($upfile_file_path)) @chmod($upfile_file_path,0777);
return $up_file_return;
}
function createdir($dir='')
{
if (!is_dir($dir))
{
$temp = explode('/',$dir);
$cur_dir = '';
for($i=0;$i<count($temp);$i++)
{
$cur_dir .= $temp[$i].'/';
if (!is_dir($cur_dir))
{
@mkdir($cur_dir,0777);
}
}
}
return $dir;
}
function random($chars='123456789',$length=4)
{
$hash = '';
$max = strlen($chars) -1;
mt_srand((double)microtime()*1000000);
for($i = 0;$i <$length;$i++)
{
$hash .= $chars[mt_rand(0,$max)];
}
return $hash;
}
}
