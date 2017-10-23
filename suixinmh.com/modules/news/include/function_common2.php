<?php
/*
	[Cms News] (C) 2009-2010 Jieqi Inc.
	$Id: function_common.php  2010-03-24 09:56:09Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
//SQL ADDSLASHES
function saddslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = saddslashes($val);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

//数据库连接
function dbconnect() {
	global $_SGLOBAL;
	//数据库模块
	jieqi_includedb();
	
	if(empty($_SGLOBAL['db'])) {
		$_SGLOBAL['db'] = JieqiQueryHandler::getInstance('JieqiQueryHandler');
	}
}

//获取数据库版本
function dbversion(){
    global $_SGLOBAL;
    dbconnect();
	$version = @mysql_get_server_info();
	$version = explode('-', $version);
	return $version[0];
}

//判断当前用户登录状态
function checkauth() {
    global $_SGLOBAL, $_SN;
    if($_SESSION['jieqiUserId']){
	   $_SGLOBAL['supe_uid'] = intval($_SESSION['jieqiUserId']);
	   $_SGLOBAL['supe_username'] = addslashes($_SESSION['jieqiUserUname']);
	   $_SGLOBAL['username'] = addslashes($_SESSION['jieqiUserUname']);
	   $_SGLOBAL['realname'] = addslashes($_SESSION['jieqiUserName']);
	   $_SGLOBAL['avatar'] = intval($_SESSION['jieqiUserAvatar']);
	   $_SGLOBAL['vip'] = intval($_SESSION['jieqiUserVip']);
	   $_SGLOBAL['groupid'] =intval($_SESSION['jieqiUserGroup']);
	   $_SGLOBAL['egolds'] =intval($_SESSION['jieqiUserEgolds']);
	   $_SGLOBAL['score'] =intval($_SESSION['jieqiUserScore']);
	   $_SN[$_SGLOBAL['supe_uid']] = $_SGLOBAL['realname'] ?$_SGLOBAL['realname']:$_SGLOBAL['username'];
	}
}

//产生form防伪码
function formhash($uid = 0) {
	global $_SGLOBAL, $_SCONFIG;
	$uid = $uid ?$uid :$_SGLOBAL['supe_uid'];
    if(!$uid) return false;
	if(empty($_SGLOBAL['formhash'])) {
		$hashadd = defined('IN_ADMINCP') ? 'Only For JQHOME AdminCP' : '';
		$_SGLOBAL['formhash'] = substr(md5(substr($_SGLOBAL['timestamp'], 0, -7).'|'.$uid .'|'.md5($_SCONFIG['sitekey']).'|'.$hashadd), 8, 8);
	}
	return $_SGLOBAL['formhash'];
}

//取消HTML代码
function shtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

//格式化字节
function formatsize($filesize){
	$units = 'Byte';
	if($filesize>=1024){
		$filesize = number_format($filesize/1024,2, '.', '');
		$units = 'KB';
	}
	if($filesize>=1024){
		$filesize = number_format($filesize/1024,2, '.', '');
		$units = 'MB';
	}
	return $filesize.' '.$units;
}

//获取URL域名
function gethost($host){
    if(!$host) return false;
	$fcount = substr_count($host,'.');
	switch($fcount){
	    case '0':
		     return false;
		break;
		case '1':
		     return $host;
		break;
		default:
		     $arr = explode('.', $host);
			 return $arr[$fcount-1].'.'.$arr[$fcount];
		break;
	}
}

//判断提交是否正确
function submitcheck($var) {
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
	    $REFERER = preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']);
	    $HOST = preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']);
		//if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST['formhash'] == formhash()) {
		if(gethost($REFERER)==gethost($HOST) && $_POST['formhash'] == formhash()) {
			return true;
		} else {
			showmessage('submit_invalid');
		}
	} else {
		return false;
	}
}

//内容分词
function dictwrod($str){
    if(!$str) return false;
    include_once($GLOBALS['jieqiModules']['news']['path'].'/include/dict.class.php');
	$Dict = new Dict();
	$ret = array();
	if($word = $Dict->mmSegWord($str)){
		foreach($word as $k=>$v){
		   if($Dict->IsWord($v)){
			   $reg[]=$v;
		   }
		}
	}
	return $reg;
}

//相对路径转换成绝对路径
function relative_to_absolute($content, $feed_url, $isurl = false) { 
    preg_match('/(http|https|ftp):\/\//', $feed_url, $protocol); 
    $server_url = preg_replace("/(http|https|ftp|news):\/\//", "", $feed_url);
    $server_url = preg_replace("/\/.*/", "", $server_url); 

    if ($server_url == '') { 
        return $content; 
    } 
    if($isurl){//单个链接
	    if(substr($content, 0, 7)=='http://') return $content;
	    if(substr($content,0,1)=='/'){
		    $new_content = $protocol[0].$server_url.$content;
		}elseif(strpos($content, '../')===0){
		    $tmpdir=dirname($feed_url);
		    while(strpos($content, '../')===0){
				$tmpdir=dirname($tmpdir);
				$content=substr($content, 3);
			}
			$new_content = $tmpdir.'/'.$content;
		}else{
		    $tmpdir=dirname($feed_url);
			$new_content = $tmpdir.'/'.basename($content);
		}
	}else{
		if (isset($protocol[0])) { 
			$new_content = preg_replace('/href="\//', 'href="'.$protocol[0].$server_url.'/', $content); 
			$new_content = preg_replace('/src="\//', 'src="'.$protocol[0].$server_url.'/', $new_content); 
		} else { 
			$new_content = $content; 
		}
		//内容中的图片相对路径改成绝对路径
		preg_match_all("/(href|src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $new_content, $matches);
		if($matches[3]){
			$matches[3] = array_values(array_unique($matches[3]));
			$repfrom = $repto = array();
			foreach($matches[3] as $k=>$path){
				 if(strpos($path, 'http://')===false){
					 $repfrom[] = $path;
					 $repto[] = relative_to_absolute($path, $feed_url, true);
				 }
			}
			if(count($repfrom) > 0) $new_content=str_replace($repfrom, $repto, $new_content);
		}
	}
    return $new_content; 
} 

/*//内容编码转换
function convertstr($str, $convert = 'utf8'){
    if(!$str) return false;
	$charsetary=array('gb2312'=>'gb', 'gbk'=>'gb', 'gb'=>'gb', 'big5'=>'big5', 'utf-8'=>'utf8', 'utf8'=>'utf8');
	$charset= USER_CHARSET ?USER_CHARSET :CHARSET;//当前系统文字编码
	if($charsetary[$charset] != $charsetary[$convert]){//如果当前系统文字编码与需要转换编码不同，开始转换
		$changefun='';
		if(isset($charsetary[$charset])) $changefun='jieqi_'.$charsetary[$charset].'2'.$charsetary[$convert];
		//exit($changefun);
		//return call_user_func('jieqi_big52utf8', $str);
	}
	$str = call_user_func('jieqi_big52gb', $str);
	return call_user_func('jieqi_gb2big5', $str);
}*/

//设置VIP等级
function setvip(){
    return array('vip1','vip2','vip3','vip4','vip5');
}

//添加数据
function inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $nobuffer=0) {
	global $_SGLOBAL;
    //链接数据库
	dbconnect();
	$insertkeysql = $insertvaluesql = $comma = '';
	foreach ($insertsqlarr as $insert_key => $insert_value) {
		$insertkeysql .= $comma.'`'.$insert_key.'`';
		$insertvaluesql .= $comma.'\''.$insert_value.'\'';
		$comma = ', ';
	}
	$method = $replace?'REPLACE':'INSERT';
	$query = $_SGLOBAL['db']->db->query($method.' INTO '.jieqi_dbprefix($tablename).' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')', 0, 0, $nobuffer);
	if($returnid && !$replace) {
		return $_SGLOBAL['db']->db->getInsertId();
	}else return $query;
}

//更新数据
function updatetable($tablename, $setsqlarr, $wheresqlarr) {
	global $_SGLOBAL;
    //链接数据库
	dbconnect();
	$setsql = $comma = '';
	foreach ($setsqlarr as $set_key => $set_value) {
	    if($set_value === '++'){
			$setsql .= $comma.'`'.$set_key.'`'.'='.$set_key.'+1';
		}elseif($set_value === '--'){
			$setsql .= $comma.'`'.$set_key.'`'.'='.$set_key.'-1';
		}else{
			$setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
		}
		$comma = ', ';
	}
	$where = $comma = '';
	if(empty($wheresqlarr)) {
		$where = '1';
	} elseif(is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	return $_SGLOBAL['db']->db->query('UPDATE '.jieqi_dbprefix($tablename).' SET '.$setsql.' WHERE '.$where);
}

//查询数据
function selectsql($sql, $limit=0, $start=0, $nobuffer=false){
    global $_SGLOBAL;
    //链接数据库
	dbconnect();
	$query = $_SGLOBAL['db']->db->query($sql, $limit, $start, $nobuffer);
	while($v = $_SGLOBAL['db']->db->fetchArray($query)){
		$rows[] = $v;
	}
	return $rows;
}

//删除数据
function deletesql($tablename, $wheresqlarr){
    global $_SGLOBAL;
    //链接数据库
	dbconnect();
	$where = $comma = '';
	if(empty($wheresqlarr)) {
		$where = '1';
	} elseif(is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	return $_SGLOBAL['db']->db->query('DELETE FROM '.jieqi_dbprefix($tablename).' WHERE '.$where);
}

//获取文件名后缀
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1)));
}

//写入文件
function swritefile($filename, $writetext, $openmod='w') {
	if(@$fp = fopen($filename, $openmod)) {
		flock($fp, 2);
		fwrite($fp, $writetext);
		fclose($fp);
		return true;
	} else {
		//增加记录日志
		include_once(JIEQI_ROOT_PATH.'/include/funlogs.php');
		$ary['reason'] = 'error';
		$ary['chginfo'] = "File: $filename write error.";
		jieqi_logs_set($ary);
		return false;
	}
}

//获取目录
function sreaddir($dir, $extarr=array()) {
	$dirs = array();
	if($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if(!empty($extarr) && is_array($extarr)) {
				if(in_array(strtolower(fileext($file)), $extarr)) {
					$dirs[] = $file;
				}
			} else if($file != '.' && $file != '..') {
				$dirs[] = $file;
			}
		}
		closedir($dh);
	}
	return $dirs;
}

//保存内容中的远程文件
function save_remotefile($content, $fileext, $param = '', $colary=array('repeat'=>2, 'referer'=>1)){
	global $_SGLOBAL,$_SCONFIG,$_OBJ;
		if($fileext){
			$attachurl = $_SCONFIG['attachurl'] ?$_SCONFIG['attachurl'] :JIEQI_URL;//附件URL服务器
			preg_match_all("/(href|src)=([\"|']?)([^ \"'>]+\.(".implode('|', $fileext)."))\\2/i", $content, $matches);
			
		    if($matches[3]){
			    $matches[3] = array_values(array_unique($matches[3]));
				$find = array();
				$replace = array();
				$haveimage = false;
				$URL = gethost($_SERVER['HTTP_HOST']);
				include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
				foreach($matches[3] as $k=>$path){
					if(strpos($path, '://') === false) continue;
					if(!ereg("\.$URL|http://$URL/i",$path)){
					  $gofile=$attachurl."/modules/news/attachment.php?action=upload&remotefile={$path}&from=remotefile&uid={$_SGLOBAL['supe_uid']}&dosubmit=1&hash=".formhash();
					  $filepath = jieqi_urlcontents($gofile.($param ? "&{$param}" :''),$colary);
						if($filepath){
						     if(strpos($filepath, '/'.$_SCONFIG['attachdir']) === false) continue;
							 $find[] = $path;
							 $replace[] = $filepath;
							 $haveimage = true;
							 //$matches[3][$k] = $filepath;//更新原始图片地址，方便下面取标题图片使用
						}
					}
				}
				if($haveimage) $content = str_replace($find, $replace, $content);
			}
		}
		return $content;
}

//文件上传函数
function upfile($fileobj, $fileinfo = array( 'filetype' => 'attach' ) ){
     global $jieqiModules, $_SCONFIG, $_PAGE;
	 
	 if(!isset($fileinfo['savepath'])) $savepath = JIEQI_ROOT_PATH."/{$_SCONFIG['attachdir']}";
	 else $savepath = $fileinfo['savepath'];
	 
	 if(substr_count($fileobj,'http://')){//如果是远程地址上传
	 
	     $fileext = fileext($fileobj);
		 if(eregi("gif|jpg|jpeg|png|bmp",$fileext)) $filetype = 'image';
		 elseif(eregi("swf|flv",$fileext)) $filetype = 'flash';
		 elseif(eregi("asf|avi|wmv|mid|mov|mp3|mp4|mpc|mpeg|mpg|rm|rmi|rmvb",$fileext)) $filetype = 'media';
		 else{
			 $filetype = 'attach';
		 }
		 if(!in_array($fileext, explode(',', $_SCONFIG["{$filetype}_extname"]))) exit();
		 include_once($jieqiModules['news']['path'].'/include/urlupload.class.php');
		 $url = new UrlUpload($fileobj);
		 $up = $url->upfile($savepath.'/'.$filetype.'/');
		 
	 }else{//本地上传

		 if(!in_array($fileinfo['filetype'], array('image', 'attach', 'flash', 'media'))) $filetype = 'attach';
		 else  $filetype = $fileinfo['filetype'];
		 
		 if(!isset($fileinfo['attachmime'])) $mimetype = $_SCONFIG['attachmime'];
		 else $mimetype = $fileinfo['attachmime'];
	
		 if(!isset($fileinfo['fileextname'])) $fileextname = $_SCONFIG["{$filetype}_extname"];
		 else $fileextname = $fileinfo['fileextname'];
	
		 if(!isset($fileinfo['maxsize'])){
			 if($filetype=='image') $maxsize = $_SCONFIG['maximagesize']*1024;
			 else  $maxsize = $_SCONFIG['maxfilesize']*1024;
		 }else{
			 $maxsize = $fileinfo['maxsize']*1024;
		 }
		 
		 $savedir = '/'.$filetype.'/'.date('Y/m/d', time());
	
		 if(isset($fileinfo['filerename'])) $filerename = $fileinfo['filerename'];
	
		 if(isset($fileinfo['overwrite'])) $overwrite = $fileinfo['overwrite'];
		 
		 include_once($jieqiModules['news']['path'].'/include/httpupload.class.php');
		 $upload = new HttpUpload($fileobj, $savepath, $mimetype , $fileextname, $maxsize, $filerename, $savedir, $overwrite);
		
		 if(isset($fileinfo['filename'])) $upload->__set("upload_filename",JIEQI_ROOT_PATH.$fileinfo['filename']);
		 //图片上传
		 $up = $upload -> upfile();
	 }
	 //上传成功后操作
	 if( !$up[upfile_file_error] && ($filetype=='image' || $_PAGE['_GET']['from']=='uploadfiles') && in_array($up[upload_file_extname], array('gif','jpg','jpeg','png','bmp'))){
	   
		  //如果开启了GD库并且不是免费版
		  if(function_exists('gd_info') && JIEQI_MODULE_VTYPE != '' && JIEQI_MODULE_VTYPE != 'Free'){
			  ///////////判断是否生成缩略图/////////////
			  $make_thumb_image = false;
			  //判断是否开启缩略图功能
		      if(isset($fileinfo['thumb_enable'])){
			      $make_thumb_image = $fileinfo['thumb_enable'];
				  $make_thumb_width = $fileinfo['thumb_width'];
				  $make_thumb_height = $fileinfo['thumb_height'];
			  }else{
			      $make_thumb_image = $_SCONFIG['thumb_enable'];
				  $thumb_size = $_SCONFIG['thumb_size'];
				  $thumb_size = @explode('*', $thumb_size );
				  $make_thumb_width = $thumb_size[0];
				  $make_thumb_height = $thumb_size[1];
			  }
			  //如果生成缩略图
			  if($make_thumb_image && $make_thumb_width && $make_thumb_height){
			      $image_file = getimagesize($up[upfile_file_path]);
				  if($image_file[0]>$make_thumb_width  || $image_file[1]>$make_thumb_height){
					  //加载图片压缩处理类
					  include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
					  $imgresize = new ImageResize();
					  $imgresize->load($up[upfile_file_path]);	
					  $imgresize->resize($make_thumb_width, $make_thumb_height);
					  $imgresize->save($up[upfile_file_path]);	     
				  } 
			  }
			  
			  ///////////判断是否加水印////////////////
			  $make_image_water = false;
			  //判断是否开启水印功能
		      if(isset($fileinfo['attachwater'])){
			      $make_image_water = $fileinfo['attachwater'];
				  $water_image_file = $fileinfo['attachwimage']!='' ?$fileinfo['attachwimage'] :$_SCONFIG['attachwimage'];
			  }else{
			      $make_image_water = $_SCONFIG['attachwater'];
				  $water_image_file = $_SCONFIG['attachwimage'];
			  }
			  //判断水印图片是否存在
		     if(strpos($water_image_file, '/')===false && strpos($water_image_file, '\\')===false) $water_image_file = $jieqiModules['news']['path'].'/images/'.$water_image_file;
		      else $water_image_file = $water_image_file;
			  if(is_file($water_image_file) && $make_image_water){
			  
			      $image_file = getimagesize($up[upfile_file_path]);//取得上传图片信息
				  $water_file = getimagesize($water_image_file);//取得水印图片信息
				  if($water_file[0]<$image_file[0] && $water_file[1]<$image_file[1] && $image_file[0]>=150 && $image_file[1]>=150){
					  include_once(JIEQI_ROOT_PATH.'/lib/image/imagewater.php');
						//图片加水印
						if( in_array($up[upload_file_extname], array('gif','jpg','jpeg','png') ) ){
							$img = new ImageWater();
							$img->save_image_file = $up[upfile_file_path];
							$img->codepage = JIEQI_SYSTEM_CHARSET;
							$img->wm_image_pos = $make_image_water;
							$img->wm_image_name = $water_image_file;
							$img->wm_image_transition  = $_SCONFIG['attachwtrans'];
							$img->jpeg_quality = $_SCONFIG['attachwquality'];
							//$img->wm_text = '胡黎明';
							//$img->wm_text_pos = 1;
							$img->create($up[upfile_file_path]);
							unset($img);
						}
				  }
			  }
		  }
	 }
	 return $up;
}

//对话框
function showmessage($msgkey, $url_forward='', $second=1, $values=array()) {
		global $_SGLOBAL, $jieqiTpl,$_PAGE;
		
		if(empty($_SGLOBAL['inajax']) && $url_forward && empty($second)) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url_forward");
		} else {
			$message = lang_replace($msgkey, $values);
		}
		if($_SGLOBAL['inajax']) {
			/*if($url_forward) {
				$message = "<a href=\"$url_forward\">$message</a><ajaxok>";
			}
			xml_out($message);*/
			header('Content-Type:text/html; charset='.USER_CHARSET); 
			header("Cache-Control:no-cache");
			exit($message);
		} else {
			if($url_forward) {
				$message = "<a href=\"$url_forward\">$message</a><script>setTimeout(\"window.location.href ='$url_forward';\", ".($second*1000).");</script>";
			}
			template('showmessage');
			global $jieqiTset;
			$jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_pagetitle' => JIEQI_SITE_NAME, 'message' => $message, 'url_forward' => $url_forward));
			$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template']));
			$jieqiTpl->setCaching(0);
			$jieqiTpl->display($jieqiTset['jieqi_page_template']);
			jieqi_freeresource();
		}
	exit();	
}

//语言替换
function lang_replace($text, $vars = array()) {
    jieqi_loadlang('showmessage', 'news');
	global $jieqiLang;
	if(isset($jieqiLang['news'][$text])) {
		$text = $jieqiLang['news'][$text];
	} else {
		return $msgkey;
	}	
	if($vars) {
		foreach ($vars as $k => $v) {
			$rk = $k + 1;
			$text = str_replace('\\'.$rk, $v, $text);
		}
	}
	return $text;
}

//获取个性模板列表
function gettemplate(){
    global $jieqiModules, $_SCONFIG, $_TPL;
	//获得个性模板
	$templates = $default_template = array();
	$tpl_dir = sreaddir($jieqiModules['news']['path'].'/templates');
	foreach ($tpl_dir as $dir) {
		if(file_exists($jieqiModules['news']['path'].'/templates/'.$dir.'/style.css')) {
			$tplicon = file_exists($jieqiModules['news']['path'].'/templates/'.$dir.'/image/template.gif')?'templates/'.$dir.'/image/template.gif':'image/tlpicon.gif';
			$tplvalue = array('name'=> $dir, 'icon'=>$tplicon);
			if($dir == $_SCONFIG['template']) {
				$default_template = $tplvalue;
			} else {
				$templates[$dir] = $tplvalue;
			}
		}
	}
	$_TPL['templates'] = $templates;
	$_TPL['default_template'] = $default_template;
}

function xml_out($content) {
	@header("Expires: -1");
	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	@header("Content-type: application/xml; charset=".JIEQI_CHAR_SET);
	echo '<'."?xml version=\"1.0\" encoding=\"".JIEQI_CHAR_SET."\"?>\n";
	echo "<root><![CDATA[".trim($content)."]]></root>";
	exit();
}

//模板调用
function template($name, $cache = 0) {
	global $_SGLOBAL, $_SCONFIG, $_SN, $_TPL, $jieqiTset, $jieqiTpl, $_PAGE;

    if(!isset($jieqiTset['jieqi_contents_template'])){
		if((substr(0, 1, $name))== '/'){
			$tpl = dirname($name);
			//赋值内容模板
			$jieqiTset['jieqi_contents_template'] = $name;
		} else {
		    //$tpldir = !ADMIN_DIR ?$_SCONFIG['template'] :'admin';
			//$tpldir = $_SCONFIG['template'];
			$tpl = $GLOBALS['jieqiModules']['news']['path']."/templates/".$_SCONFIG['template']."/";
			$fiex = !substr_count($name,'.') ? '.html' : '';//模板扩展
			//赋值内容模板
			$jieqiTset['jieqi_contents_template'] = $tpl.$name.$fiex;
		}
		
		if(!file_exists($jieqiTset['jieqi_contents_template'])) {
			$jieqiTset['jieqi_contents_template'] = str_replace('/'.$_SCONFIG['template'].'/', '/default/', $jieqiTset['jieqi_contents_template']);
		}
	}
	$_SGLOBAL['TPL_DIR'] = dirname(str_replace(array(JIEQI_ROOT_PATH),array($_SGLOBAL['localurl']),$jieqiTset['jieqi_contents_template']));
	//echo $_SCONFIG['template'];
	//定义风格模板
	if(!isset($jieqiTset['jieqi_page_template'])){
		$jieqiTset['jieqi_page_template'] = $GLOBALS['jieqiModules']['news']['path'].'/themes/'.$_SCONFIG['theme'].'/theme.html';
		if(!file_exists($jieqiTset['jieqi_page_template'])){
			//$jieqiTset['jieqi_page_template'] = $GLOBALS['jieqiModules']['news']['path'].'/themes/'.'/theme.html';
			if(!file_exists($jieqiTset['jieqi_page_template'])) {
				$jieqiTset['jieqi_page_template'] = str_replace('/'.$_SCONFIG['template'].'/', '/default/', $jieqiTset['jieqi_page_template']);
			}	
		}
	}
	if(!file_exists($jieqiTset['jieqi_page_template'])) $jieqiTset['jieqi_page_template']=JIEQI_ROOT_PATH.'/themes/'.JIEQI_THEME_NAME.'/theme.html';
	//赋值全局相关变量
	$jieqiTpl->assign('_SGLOBAL',$_SGLOBAL);
	//赋值全局/页面参数
	$jieqiTpl->assign('_SCONFIG',$_SCONFIG);
	//赋值页面浮动变量
	$jieqiTpl->assign('_SN',$_SN);
	//赋值页面相关变量
	$jieqiTpl->assign('_PAGE',$_PAGE);
	$jieqiTpl->setCaching($cache);
	//ajax变量直接输出
	if($_SGLOBAL['inajax']) {
	   $_REQUEST['ajax_gets'] = $_REQUEST['ajax_gets'] ?$_REQUEST['ajax_gets'] :'jieqi_contents';
	   $jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_cacheid'], $jieqiTset['jieqi_contents_compileid']));//echo $jieqiTpl->_tpl_vars[$_REQUEST['ajax_gets']];exit;
	   xml_out($jieqiTpl->_tpl_vars[$_REQUEST['ajax_gets']]);
	}
}

?>