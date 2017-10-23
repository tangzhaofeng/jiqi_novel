<?PHP

/*
	[QQ405214421阳春白雪!] (C)2007-2012 Cms Inc.
	This is NOT a freeware, use is subject to license terms

	$RCSfile: ftp_upload.class.php,v$
	$Revision: 1.0 $
	$Date: 2010/04/23 16:00:00 $
*/
class ftp_upload
{
     /**
     *  FTP server port
     *  @var int
     */
     var $ftp_port = 21;
	 
     /**
     *  FTP host address
     *  @var char
     */
     var $ftp_host;	
	 
     /**
     *  FTP username
     *  @var char
     */
     var $ftp_user;
	 
     /**
     *  FTP password
     *  @var char
     */
     var $ftp_pass;	
     var $ftp_stream;      # the FTP to the server
	 var $ftp_mkDateDir = true;      # 上传到服务器的文件是否按时间自动建立文件夹归类
	 var $do_debug = true;       # the level of debug to perform
     var $error = array(); # error if any on the last call
	 var $config       = array();    //全局的参数数组
	 
	 function ftp_upload($ftp_host ,$ftp_user ,$ftp_pass ,$ftp_port =21)
	 {
	     @set_time_limit(0);
	     if($this->do_debug){
			 $this->error =
			 array('connect_error' => '连接到FTP服务器发生错误!',
				   'login_error' => '登陆FTP服务器发生错误,请检查用户名和密码',
				   'delDir_error' => '删除目录发生错误!',
				   'upfile_error' => '文件上传失败!',
				   'delFile_error' => '文件删除失败,请重试!',
				   'get_error' => '下载文件失败!',
				   'chdir_error' => '改变目录时发生求知错误!'); 
		 } 
		 $this->__set("ftp_host",$ftp_host);
		 $this->__set("ftp_user",$ftp_user);
		 $this->__set("ftp_pass",$ftp_pass);
		 $this->__set("ftp_port",$ftp_port);
		 $this->hftp_connect();      
	 }
	 
	 function hftp_connect()
	 {
	    $this->__set($this->config);
	    $this->ftp_stream = @ftp_connect($this->ftp_host, $this->ftp_port);
		if($this->do_debug && !$this->ftp_stream) exit($this->error['connect_error']);
		$this->hftp_login();
		return $this->ftp_stream;
	 }
	 
	 function hftp_login()
	 {
	    $result  = @ftp_login($this->ftp_stream, $this->ftp_user, $this->ftp_pass);
		if($this->do_debug && !$result) exit($this->error['login_error']);
	    return $result;
	 }
	 
	 function dftp_rmdir($directory) {
	    $result  = @ftp_rmdir($this->ftp_stream, $directory);
		if($this->do_debug && !$result) exit($this->error['delDir_error']);
		return $result;
     } 
	 //mode 只能为 FTP_ASCII (文本模式) 或 FTP_BINARY (二进制模式) 两种
	 function dftp_put($remote_file, $local_file, $mode =1, $startpos = 0 ) {
		$result = @ftp_put($this->ftp_stream, $remote_file, $local_file, $mode, $startpos);
		if($this->do_debug && !$result) exit($this->error['upfile_error']);
		return $result;
	 }

	function dftp_size($remote_file) {
		return @ftp_size($this->ftp_stream, $remote_file);
	}
	
	function dftp_close() {
		return @ftp_close($this->ftp_stream);
	}
	
	function dftp_delete($path) {
	    $result  = @ftp_delete($this->ftp_stream, $path);
		if($this->do_debug && !$result) exit($this->error['delFile_error']);
		return $result;
	}
	
	function dftp_get($local_file, $remote_file, $mode =1, $resumepos = 0) {
		$mode = intval($mode);
		$resumepos = intval($resumepos);
		$result  = @ftp_get($this->ftp_stream, $local_file, $remote_file, $mode, $resumepos);
		if($this->do_debug && !$result) exit($this->error['get_error']);
		return $result;	
	}
	 
	function dftp_rawlist($dir='./')
	{
	    
	}

	function dftp_pasv($pasv) {
		$pasv = intval($pasv);
		return @ftp_pasv($this->ftp_stream, $pasv);
	}
	
	function dftp_chdir($directory) {
		$result  = @ftp_chdir($this->ftp_stream, $directory);
		if($this->do_debug && !$result) exit($this->error['chdir_error']);
		return $result;	
	}
	
	 function hftp_mkdir($directory)
	 {
	    	if (!is_dir($directory))
			{
					$temp = explode('/',$directory);
					$cur_dir = '';
					for($i=0;$i<count($temp);$i++)
					{
						  $cur_dir .= $temp[$i].'/';
						  if (!is_dir($cur_dir))
						  {
								  @ftp_mkdir($this->ftp_stream, $cur_dir);
						  }
					}
			}
			return $directory;

	 }
	 
	 function __set($sttribName,$value='')
	 {
		 if(!is_array($sttribName)){
		   $this->$sttribName=$value;
		 } else {
		   foreach($sttribName as $key=>$val){
			 if(chop($key)!='') $this->$key=$val;
		   }
		 }
	 }
	 
	 function __get($sttribName)
	 {
		return $this->$sttribName;
	 }		
	 
}

//$ftp=new ftp_upload('61.183.11.199','hbjcw','hbjcw');
//$ftp->hftp_mkdir('aaaaaa/fdgfd/fdf');
//$ftp->dftp_put($ftp->hftp_mkdir('aaaa/bbbb/fff/').'aaa.php','html.cs.php');
?>