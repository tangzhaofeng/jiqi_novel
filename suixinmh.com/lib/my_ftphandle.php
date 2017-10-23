<?php
class MyFtpHandle {
private $ftp_port = 21;
private $ftp_host;
private $ftp_user;
private $ftp_pass;
private $ftp_stream;
private $ftp_mkDateDir = true;
private $do_debug = true;
private $error = array(
'connect_error'=>'连接到FTP服务器发生错误!',
'login_error'=>'登陆FTP服务器发生错误,请检查用户名和密码',
'delDir_error'=>'删除目录发生错误!',
'upfile_error'=>'文件上传失败!',
'delFile_error'=>'文件删除失败,请重试!',
'get_error'=>'下载文件失败!',
'chdir_error'=>'改变目录时发生求知错误!'
);
protected $config = array();
public function init($config) {
@set_time_limit(0);
if (!is_array($config)) throw new Exception('参数只能传递数组');
$this->ftp_host = $config['host'];
$this->ftp_user = $config['username'];
$this->ftp_pass = $config['password'];
$this->ftp_port = isset($config['port']) ?$config['port'] : '21';
$this->hftp_connect();
}
private function hftp_connect() {
$this->__set($this->config);
$this->ftp_stream = @ftp_connect($this->ftp_host,$this->ftp_port);
if ($this->do_debug &&!$this->ftp_stream)
exit($this->error['connect_error']);
$this->hftp_login();
return $this->ftp_stream;
}
private function hftp_login() {
$result = @ftp_login($this->ftp_stream,$this->ftp_user,$this->ftp_pass);
if ($this->do_debug &&!$result)
exit($this->error['login_error']);
return $result;
}
public function delDir($directory) {
$result = @ftp_rmdir($this->ftp_stream,$directory);
if ($this->do_debug &&!$result)
exit($this->error['delDir_error']);
return $result;
}
public function putFile($from_file,$to_file,$mode = 1,$startpos = 0) {
$result = @ftp_put($this->ftp_stream,$to_file,$from_file,$mode,$startpos);
if ($this->do_debug &&!$result)
exit($this->error['upfile_error']);
return $result;
}
public function getSize($remote_file) {
return @ftp_size($this->ftp_stream,$remote_file);
}
private function dftp_close() {
return @ftp_close($this->ftp_stream);
}
public function delFile($path) {
$result = @ftp_delete($this->ftp_stream,$path);
if ($this->do_debug &&!$result)
exit($this->error['delFile_error']);
return $result;
}
public function getFile($local_file,$remote_file,$mode = 1,$resumepos = 0) {
$mode = intval($mode);
$resumepos = intval($resumepos);
$result = @ftp_get($this->ftp_stream,$local_file,$remote_file,$mode,$resumepos);
if ($this->do_debug &&!$result)
exit($this->error['get_error']);
return $result;
}
protected function dftp_rawlist($dir = './') {
}
public function isPasv($pasv = 1) {
$pasv = (0===$pasv) ?FALSE : TRUE;
return @ftp_pasv($this->ftp_stream,$pasv);
}
public function changeDir($directory) {
$result = @ftp_chdir($this->ftp_stream,$directory);
if ($this->do_debug &&!$result)
exit($this->error['chdir_error']);
return $result;
}
public function createDir($directory) {
if (!is_dir($directory)) {
$temp = explode('/',$directory);
$cur_dir = '';
for ($i = 0;$i <count($temp);$i++) {
$cur_dir .= $temp[$i] .'/';
if (!is_dir($cur_dir)) {
@ftp_mkdir($this->ftp_stream,$cur_dir);
}
}
}
return $directory;
}
public function __set($sttribName,$value = '') {
if (!is_array($sttribName)) {
$this->$sttribName = $value;
}else {
foreach ($sttribName as $key =>$val) {
if (chop($key) != '')
$this->$key = $val;
}
}
}
public function __get($sttribName) {
return $this->$sttribName;
}
}
