<?PHP

/*
	[QQ405214421阳春白雪!] (C)2007-2012 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms
    远程文件上传
	$RCSfile: url_upload.cs.php,v $
	$Revision: 1.0 $
	$Date: 2010/07/05 12:00:00 $
*/
class UrlUpload
{
    var $remote_file; # 待上传的远程文件
	var $local_dir;   # 上传文件保存的本地目录
	var $local_isdir = true; #是否建立分组文件夹
	var $local_dirFormat = 'Y/m/d';  # 自动分组文件夹的格式
	var $local_file;  # 上传文件保存的本地文件名[留空则为原始文件名]
	var $local_rename = true; #是否重命名文件
	var $file_expan = 'jpg,jpeg,gif,png,bmp'; #充许上传文件的扩展名
	var $upfile_file_error = 0;
	
	function UrlUpload($remote_file,$file_expan ='')
	{
	    $this->remote_file = $remote_file;
		$this->file_expan  = !empty($file_expan) ? $file_expan : $this->file_expan;
	}
	/*
	param $local_dir  图片存储的本地目录
	     $local_file 指定文件名保存，会覆盖重命名设置
		 getfile($local_dir='./',$local_file ='')
	*/
	function upfile($local_dir='.',$local_file ='')
	{
		$this->local_dir     = empty($local_dir) ? '' : $local_dir.'/';
		$this->local_file    = $local_file;
		include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
		$colary=array('repeat'=>2, 'referer'=>1,'charset'=>'image');
		$upfile_file_path = $this->getFilename();
		if(!jieqi_writefile($upfile_file_path, jieqi_urlcontents($this->remote_file,$colary))) $this -> upfile_file_error = 6;
		$fileinfo = @pathinfo($upfile_file_path);
        $up_file_return = array(
			"upfile_file_error"      => $this -> upfile_file_error,  //上传状态
			"upfile_file_newname"    => $fileinfo['basename'],        //上传后的新文件名
			"upfile_file_path"       => str_replace('\\', '/', realpath($upfile_file_path)),           //上传后在服务器的路径
			"upload_file_name"       => basename($this->remote_file),        //上传文件名
			"upload_file_extname"    => $fileinfo['extension'],        //上传文件扩展名
			"upload_file_size"       => filesize($upfile_file_path)            //上传文件大小
		);
		return $up_file_return;
	}
	
	function getFilename()
	{
	    if($this->local_file!=''){
		   $filename = basename($this->local_file);
		   $this->local_dir.=str_replace($filename,'',$this->local_file);
		} else {
		   $file_type = explode(",", $this->file_expan);
		   $upload_file_extname = strtolower(substr(strrchr($this->remote_file,'.'),1));
		   if(in_array($upload_file_extname , $file_type) == false){
                // 当上传文件扩展名不符合要求
                $this -> upfile_file_error = 3;
           }
		   if($this->local_isdir) $this->local_dir=$this->local_dir.date($this->local_dirFormat,time())."/";
		   
		   if($this->local_rename){
		     $filename = date('his',time()).rand(1000,4).".$upload_file_extname";
		   } else {
		     $filename = basename($this->remote_file);
		   }
		}
		jieqi_createdir($this->local_dir, 0777, true);
		return $this->local_dir.$filename;
	}
}
?>