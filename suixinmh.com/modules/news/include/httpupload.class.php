<?php
        //文件上传类，使用方法及参数要求
/*      if($_POST[Submit]){
     // require_once "upload_class.php";
      // 表单file对象名称 默认为file
      $formname = "up_file";
      // 上传保存位置，默认为当前文件夹“./”
      $savepath = "../../../attachment";
      // 上传文件要求的mime类型，默认为text,image
      $mimetype = "text,image,application,audio";
      // 文件扩展名要求，默认为“jpg,bmp,png,gif,jpeg”
      $fileextname = "doc,docx,xls,ppt,wps,zip,rar,txt,jpg,jpeg,gif,bmp,swf,png";
      // 文件大小要求，默认为512000 （500K）
      $maxsize = 1024000;
      // 是否重命名，0为不重命名，1为重命名，默认为1
      $filerename = 1;
      // 建立年月日文件夹
      $savedir = date('Y/md', time());
      // 当设置不重命名并且文件存在时是否覆盖 0为不覆盖，1为覆盖并上传，2为重命名并上传
      $overwrite = 1;
      $upload = new http_upload($formname, $savepath, $mimetype , $fileextname, $maxsize, $filerename, $savedir, $overwrite);
	  //$upload->__set("upload_filename",$savepath."/2010/0423/aa.jpg");
      $up = $upload -> upfile();
      // 上传结果
      echo("上传错误值：" . $up[upfile_file_error] . "<br>");
      echo("原文件名为：" . $up[upload_file_name] . "<br>");
      echo("新文件名为：" . $up[upfile_file_newname] . "<br>");
      echo("上传后所在路径：" . realpath($up[upfile_file_path]) . "<br>");
      echo("文件mime类型为：" . $up[upload_mime_types] . "<br>");
      echo("文件扩展名：" . $up[upload_file_extname] . "<br>");
      echo("当文件重命名时覆盖结果：" . $up[upload_file_overwrite] . "<br>");
      echo("文件大小：" . $up[upload_file_size] . "<br>");
      }
	  //echo basename('/2010/0423/201004230401539482.doc');
      if($up[upload_file_size] == ""){
      echo("<form id=\"form\" name=\"form\" method=\"post\" action=\"\" enctype=\"multipart/form-data\">");
      echo("<input name=\"up_file\" type=\"file\" id=\"up_file\" />");
      echo("<input type=\"submit\" name=\"Submit\" value=\"提交\" />");
      echo("</form>");
      }   */
class HttpUpload{
  
    // 表单对象名称
    var $upload_formname = 'file';
    
    // 上传路径
    var $upload_savepath = './';
    
    // 上传mime类型
    var $upload_mimetype = 'text,image,application,audio';
    
    // 上传类型
    var $upload_fileextname = 'doc,docx,xls,ppt,wps,zip,rar,txt,jpg,jpeg,gif,bmp,swf,png';
    
    // 限制文件大小/2MB
    var $upload_maxsize;
    
    // 指定文件名 为空的时候以年月日为文件名
    var $upload_filename;
    
    // 上传文件是否重命名 0为否 1为是
    var $upload_filerename = true;
    
    // 文件存储目录
    var $upload_savedir;
    
    // 错误参数
    var $upfile_file_error = 0;
	
    // 当设置不重命名并且文件存在时是否覆盖 0为不覆盖，1为覆盖并上传，2为重命名并上传
    var $upload_overwrite = 0;

///////////////////内部运行参数///////////////////////
	//上传件对像
	var $upload_file;
   
    // 上传文件名称
    var $upload_file_name;	 

    // 上传文件扩展名
    var $upload_file_extname;
	
	// 上传文件临时文件所在位置
    var $upload_file_tmpname;

	// 获取上传文件mime类别
    var $upload_mime_types,$upload_mime_type;

	// 获取上传文件大小
    var $upload_file_size;
			
	function __set($sttribName,$value)
	{
	   $this->$sttribName=$value;
	}
	
	function __get($sttribName)
	{
	   return $this->$sttribName;
	}
	
    function HttpUpload($formname, $savepath, $mimetype, $fileextname, $maxsize, $filerename, $datedir, $overwrite){
        // 默认表单file对象名称为file
        if(isset($formname))  $this->__set("formname",$formname);
        // 默认上传路径为当前目录
        if(isset($savepath)) $this->__set("upload_savepath",$savepath);

        // 默认上传文件mime类型为text和image
        if(isset($mimetype)) $this->__set("upload_mimetype",$mimetype);

        // 默认扩展名为jpg,gif,bmp,png,jpeg
        if(isset($fileextname)) $this->__set("upload_fileextname",$fileextname);

        // 默认上传文件大小为500K
        if(isset($maxsize)) $this->__set("upload_maxsize",$maxsize);
		else $this->__set("upload_maxsize",1024*1024*2);

        // 默认为重命名上传文件名
        if(isset($uploatype)) $this->__set("upload_filerename",$filerename);

        // 初始错误为0
		$this->__set("upfile_file_error",0);
        // 默认是文件上传新建年月日文件夹
        if(isset($datedir)) $this->__set("upload_savedir",$datedir);
		else $this->__set("upload_savedir",date('y/m/d', time()));

        // 默认是文件上传中使用不重命名文件名时，覆盖设置为0
        if(isset($overwrite)) $this->__set("upload_overwrite",$overwrite);
        
        // 将允许上传扩展名类型文本字符串转为数组
        $this -> upload_fileextname = strtolower($this -> upload_fileextname);
        $this -> upload_fileextname = explode(",", $this -> upload_fileextname);
        $this -> upload_fileextname = array_unique($this -> upload_fileextname);
        // 将允许上传文件mime类别文本字符串转为数组
        $this -> upload_mimetype = strtolower($this -> upload_mimetype);
        $this -> upload_mimetype = explode(",", $this -> upload_mimetype);
        $this -> upload_mimetype = array_unique($this -> upload_mimetype);

        // 读取上传文件信息
        $this-> upload_file = $_FILES[$this -> formname];

        // 获取上传文件名称
        $this-> upload_file_name = $this-> upload_file[name];
        // 获取上传文件扩展名
        $this-> upload_file_extname = strtolower(pathinfo($this-> upload_file_name, PATHINFO_EXTENSION));
        // 获取上传文件临时文件所在位置
        $this-> upload_file_tmpname = $this-> upload_file[tmp_name];
        // 获取上传文件mime类别
        $this-> upload_mime_types = $this-> upload_file['type'];
        $upload_mime_type = explode("/", $this-> upload_mime_types);
        $this-> upload_mime_type = $upload_mime_type[0];
        // 获取上传文件大小
        $this-> upload_file_size = $this-> upload_file[size];

    }
	
    function upfile(){

        // 获取上传文件名称
        $upload_file_name =  $this-> upload_file_name;
        // 获取上传文件扩展名
        $upload_file_extname = $this-> upload_file_extname;
        // 获取上传文件临时文件所在位置
        $upload_file_tmpname = $this-> upload_file_tmpname;
        // 获取上传文件mime类别
        $upload_mime_type = $this-> upload_mime_type;
        // 获取上传文件大小
        $upload_file_size = $this-> upload_file_size;

        // 当有文件上传开始操作
        if($this-> upload_file){
		
            if($upload_file_size > $this -> upload_maxsize){
                // 当上传文件大小超过最大值
                $this -> upfile_file_error = 1;
            }
            
            if(in_array($upload_mime_type , $this -> upload_mimetype) == false){
                // 当上传文件mime类型错误
                $this -> upfile_file_error = 2;
             }
            
            if(in_array($upload_file_extname , $this -> upload_fileextname) == false){
                // 当上传文件扩展名不符合要求
                $this -> upfile_file_error = 3;
            }
			
			$upload_max_filesize = str_replace('M', '', ini_get("upload_max_filesize"));
			$post_max_size = str_replace('M', '', ini_get("post_max_size"));
			if($upload_max_filesize*1024*1024 < $this-> upload_file_size || $post_max_size*1024*1024 < $this-> upload_file_size ){
			    // 上传的文件超过了   php.ini   中   upload_max_filesize and post_max_size  选项限制的值
                $this -> upfile_file_error = 4;
			}
			
            // 当上传错误为0的时候开始以下操作
            if($this -> upfile_file_error == 0){
			   $upfile_file_newname = strtolower($upload_file_name);
			   if(!isset($this->upload_filename)){
						// 开始判断上传文件夹存在，不存在则建立
						$this -> createdir($this -> upload_savepath);
						// 建立存储文件夹
						if($this -> upload_savedir){
							$upfile_file_path = $this -> upload_savepath . "/" . $this -> upload_savedir;
							$upfile_file_path = $this -> createdir($upfile_file_path);
						}
						// 判断是否重命名文件并操作
						if($this -> upload_filerename == 1){
 							$upfile_file_newname = date("his") .$this->random(). "." . $upload_file_extname;
						}
						
						//上传文件路径
						$upfile_file_path = $upfile_file_path . "/" . $upfile_file_newname;
						// 上传文件至指定位置
						if($this -> upload_filerename){
							// 当设置为重命名文件名时直接上传至指定位置
							$upload_file_overwrite = 0;
								if(@move_uploaded_file($upload_file_tmpname, $upfile_file_path) == false){
								   $this -> upfile_file_error = 6;
								}
						}else{
							// 当设置为不重命名文件名判断文件是否存在
							   if(@file_exists($upload_file_tmpname, $upfile_file_path) == true){
								// 当文件存在
								   if($this -> upload_overwrite == 0){
										// 当不允许覆盖
										$upload_file_overwrite = 2;
										continue;
									}
									if($this -> upload_overwrite == 1){
										// 当允许覆盖
										$upload_file_overwrite = 3;
										// 先删除原有文件
										@unlink(realpath($upload_file_tmpname, $upfile_file_path));
										if(@move_uploaded_file($upload_file_tmpname, $upfile_file_path) == false){
											$this -> upfile_file_error = 6;
										}
									}
									if($this -> upload_overwrite == 2){
										// 当设置为重命名并上传
										$upload_file_overwrite = 4;
										if(@move_uploaded_file($upload_file_tmpname, $upfile_file_path) == false){
											$this -> upfile_file_error = 6;
										}
									}
							  }else{
								// 当文件不存在
								$upload_file_overwrite = 1;
								if(@move_uploaded_file($upload_file_tmpname, $upfile_file_path) == false){
									$this -> upfile_file_error = 6;
								}
							 }
						 }
				   } else {
				        $upfile_file_path = $this->upload_filename;
				        if(is_file(realpath($upfile_file_path))) @unlink(realpath($upfile_file_path));
						if(@move_uploaded_file($upload_file_tmpname, $upfile_file_path) == false){
							$this -> upfile_file_error = 6;
						}
						$upfile_file_newname = basename($this->upload_filename);
				   }
                }
                $up_file_return = array(
					"upfile_file_error"      => $this -> upfile_file_error,  //上传状态
					"upfile_file_newname"    => $upfile_file_newname,        //上传后的新文件名
					"upfile_file_path"       => str_replace('\\', '/', realpath($upfile_file_path)),           //上传后在服务器的路径
					"upload_file_name"       => $upload_file_name,           //上传文件名
					//"upload_file_url"      => $upload_file_name,
					"upload_mime_types"      => $this->upload_mime_types,    //上传文件完整类型
					"upload_mime_type"       => $upload_mime_type,           //上传文件类型
					"upload_file_extname"    => $upload_file_extname,        //上传文件扩展名
					"upload_file_overwrite"  => $upload_file_overwrite,      //文件重写状态
					"upload_file_size"       => $upload_file_size            //上传文件大小
				);
				
            }
			if(is_file($upfile_file_path)) @chmod($upfile_file_path, 0777);
			return $up_file_return; // 返回值
        }
		//创建目录
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
        //产生随机数
		function random($chars='123456789',$length=4)
		{
			$hash = '';
			//$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
			$max = strlen($chars) - 1;
			mt_srand((double)microtime()*1000000);
			for($i = 0; $i < $length; $i++)
			{
				$hash .= $chars[mt_rand(0, $max)];
			}
			return $hash;
		 }

    }
?>
