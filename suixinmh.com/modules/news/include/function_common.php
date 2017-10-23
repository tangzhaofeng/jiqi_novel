<?php
/*
	[Cms News] (C) 2009-2010 Jieqi Inc.
	$Id: function_common.php  2010-03-24 09:56:09Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
//获取参数
function getparameter($name, $type = 'string', $method = ''){
    global $_PAGE;
	if($method == 'get') $_PAGE[$name] = $_PAGE['_GET'][$name];
	elseif($method == 'post') $_PAGE[$name] = $_PAGE['_POST'][$name];
	else $_PAGE[$name] = isset($_PAGE['_GET'][$name]) ?$_PAGE['_GET'][$name] :$_PAGE['_POST'][$name];
/*	switch($type){AJAX传递参数报错，先注释掉
	    case 'int':
		    if(!is_array($_PAGE[$name])) $_PAGE[$name] = intval((int)$_PAGE[$name]);
		break;
	}*/
	return $_PAGE[$name];
}

//页面跳转
function jumppage($param = array()){
    global $_SGLOBAL, $_PAGE;
	
    if(!$url = $param['url']){
	    if($jumpurl = getparameter('jumpurl')) $url = $jumpurl;
		else $url = $_SGLOBAL['refer'];
	}
	
	if(!$title = $param['title']) $title = lang_replace('message_notice');
	
	if(!$content = $param['content']) $content = LANG_DO_SUCCESS;
	
	jieqi_jumppage($url, $title, $content);
}

function printfail($content = ''){
   if(!$content) $content = LANG_DO_FAILURE;
   jieqi_printfail($content); 
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
		//$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			//str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
		$string = htmlspecialchars($string);
	}
	return $string;
}

//屏蔽html
function checkhtml($html, $forbidwords = '') {
	$html = stripslashes($html);
	///if(!checkperm('allowhtml')) {
		
		preg_match_all("/\<([^\<]+)\>/is", $html, $ms);

		$searchs[] = '<';
		$replaces[] = '&lt;';
		$searchs[] = '>';
		$replaces[] = '&gt;';
		
		if($ms[1]) {
			$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|object|param|embed';//允许的标签
			$ms[1] = array_unique($ms[1]);
			foreach ($ms[1] as $value) {
				$searchs[] = "&lt;".$value."&gt;";
				$value = shtmlspecialchars($value);
				$value = str_replace(array('\\',''), array('.','/.'), $value);
				$value = preg_replace(array("/(javascript|script|eval|behaviour|expression)/i", "/(\s+|&quot;|')on/i"), array('.', ' .'), $value);
				if($forbidwords){
				    if(preg_match("/^[\/|\s]?($forbidwords)(\s+|$)/is", $value)) {
						$value = '';
					}
				}else{
					if(!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
						$value = '';
					}
				}
				$replaces[] = empty($value)?'':"<".str_replace('&quot;', '"', $value).">";
			}
		}
/*		ob_start();
		print_r($searchs);
		$aa = ob_get_contents();
		ob_end_clean();
		swritefile(_ROOT_.'/aa.txt',$aa);
		
		ob_start();
		print_r($replaces);
		$aa = ob_get_contents();
		ob_end_clean();
		swritefile(_ROOT_.'/bb.txt',$aa);
		
		swritefile(_ROOT_.'/a.txt',$html);*/
		$html = str_ireplace($searchs, $replaces, $html);
/*		swritefile(_ROOT_.'/b.txt',$html);
		unset($searchs[0]);unset($replaces[0]);
		swritefile(_ROOT_.'/ccc.txt',str_ireplace($searchs[5], $replaces[5], $html));
		exit;
*/	//}
	//$html = addslashes($html);
	
	return $html;
}

//过滤HTML代码
function sstrip_tags($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = sstrip_tags($val);
		}
	} else {
		$string = strip_tags($string);
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

//取两字符串中间的内容
function exechars($preg, $str='', $getall = false){
    global $_SGLOBAL;
    include_once(_ROOT_.'/lib/text/textfunction.php');
	include_once(_ROOT_.'/modules/news/include/collectfunction.php');
	$preg = jieqi_collectstoe(jieqi_collectptos($preg));
	if($getall) return jieqi_cmatchall($preg, $str);
	else return jieqi_cmatchone($preg, $str);
}
//preg_match('/ab(.*)ef/isU', $str, $match);include_once(_ROOT_.'/lib/text/textfunction.php');
//jieqi_collectstoe(jieqi_collectptos($str))

//获取URL域名
function gethost($host = ''){
    if(!$host) $host = $_SERVER['HTTP_HOST'];
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

//功能：倒计时计算
function count_down($timestamp)
{ 
	$date = $timestamp-time(); 
	$day = $date/86400; 
	$days = intval($day); 
	$hour = $date/3600 - $days*24; 
	$hours = intval($hour); 
	$minute = $date/60 - $days*1440 - $hours*60; 
	$minutes = intval($minute); 
	$second = $date - $days*86400 - $hours*3600 - $minutes*60; 
	$seconds = intval($second); 
	$result = array($days,$hours,$minutes,$seconds); 
	return $result; 
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
		    //$tmpdir=dirname($feed_url);
			$tmpdir=substr($feed_url,0,strrpos($feed_url,'/'));
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

//保存内容中的远程文件
function save_remotefile($content, $fileext, $param = '', $colary=array('repeat'=>2, 'referer'=>1)){
	global $_SGLOBAL,$_SCONFIG,$_OBJ;
		if($fileext){
		    //获取附件服务器地址
			if(!is_object($_OBJ['category'])) $_OBJ['category'] = &new Category();
			$attachurl = $_OBJ['category']->getAttachurl();
			preg_match_all("/(href|src)=([\"|']?)([^ \"'>]+\.(".implode('|', $fileext)."))\\2/i", $content, $matches);
			
		    if($matches[3]){
			    $matches[3] = array_values(array_unique($matches[3]));
				$find = array();
				$replace = array();
				$haveimage = false;
				$URL = gethost();
				include_once(_ROOT_.'/lib/text/textfunction.php');
				foreach($matches[3] as $k=>$path){
					if(strpos($path, '://') === false) continue;
					if(!ereg("\.$URL|http://$URL/i",$path)){
					
					  //修补远程图片链接在有跳转情况下的BUG
					  $order = substr_count(strtolower($path), 'http://');
					  if(!$order) continue;
					  elseif($order<2) $imgfile = $path;
					  else $imgfile = substr($path, strripos($path, 'http://'));
					  
					  if($param=='content'){
					      $up = upfile($imgfile, $colary ); //上传文件
						  $upfile_file_path = $up[upfile_file_path];
						  $fileurl = str_replace(_ROOT_, '', $upfile_file_path);
						  unset($up);
					  }else{
						  $uid = $_SGLOBAL['supe_uid'];
						  $gofile=$attachurl."/modules/news/attachment.php?action=upload&remotefile={$imgfile}&from=remotefile&uid={$uid}&dosubmit=1&hash=".formhash();
						  $fileurl = jieqi_urlcontents($gofile.($param ? "&{$param}" :''),$colary);
					  }
					  
					  if($fileurl){
						  if(strpos($fileurl, '/'.$_SCONFIG['attachdir']) != 0) continue;
						  $find[] = $path;
						  $replace[] = $fileurl;
						  $haveimage = true;
					  }
					}
				}
				if($haveimage) $content = str_replace($find, $replace, $content);
			}
		}
		return $content;
}

//下载远程文件
function down_remotefile($path, $param = '', $colary=array('repeat'=>2, 'referer'=>1)){
    global $_SGLOBAL,$_SCONFIG,$_OBJ;
	
	if(!$path) return false;
	if(is_array($path)) $filestr = implode("[page]\n",$path);
	else $filestr = $path;
	
	//获取附件服务器地址
	if(!is_object($_OBJ['category'])) $_OBJ['category'] = &new Category();
	$attachurl = $_OBJ['category']->getAttachurl();
	$uid = $_SGLOBAL['supe_uid'];
	
	include_once(_ROOT_.'/lib/text/textfunction.php');
	
	if(strpos($path, "[page]\n")===false){//单文件上传
	    //修补远程图片链接在有跳转情况下的BUG
		$order = substr_count(strtolower($filestr), 'http://');
		if(!$filestr) return false;
		elseif($order>1) $filestr = substr($filestr, strripos($filestr, 'http://'));
	}
	$gofile=$attachurl."/modules/news/attachment.php?action=upload&remotefile={$filestr}&from=remotefile&uid={$uid}&dosubmit=1&hash=".formhash();
	//echo $gofile.($param ? "&{$param}" :'');exit;
	$fileurl = jieqi_urlcontents($gofile.($param ? "&{$param}" :''));//echo $fileurl;
	if($fileurl){
		if(strpos($fileurl, '/'.$_SCONFIG['attachdir']) != 0) return false;
		return $fileurl;
	}
	return false;
}

function match_links($document) {    

    preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$document,$links);                        

    while(list($key,$val) = each($links[2])) {

        if(!empty($val))

            $match['link'][] = $val;

    }

    while(list($key,$val) = each($links[3])) {

        if(!empty($val))

            $match['link'][] = $val;

    }        

    while(list($key,$val) = each($links[4])) {

        //if(!empty($val))

            $match['content'][] = $val;

    }

    while(list($key,$val) = each($links[0])) {

        if(!empty($val))

            $match['all'][] = $val;

    }                

    return $match;

}


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
	    $returnid = $_SGLOBAL['db']->db->getInsertId();
	    $ret = $returnid ? $returnid : true;
		return $ret;
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
			$where .= $comma.'`'.$key.'`'.'='.$value.'';
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
    $dir = dirname($filename).'/';
	if(!is_dir($dir)) if(!jieqi_createdir($dir, 0777, true)) return false;
	if(@$fp = fopen($filename, $openmod)) {
		flock($fp, 2);
		fwrite($fp, $writetext);
		fclose($fp);
		@chmod($filename, 0777);
		return true;
	} else {
		//增加记录日志
		include_once(_ROOT_.'/include/funlogs.php');
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


//文件上传函数
function upfile($fileobj, $fileinfo = array( 'filetype' => 'attach' ) ){
     global $_SGLOBAL, $_SCONFIG;
	 
	 if(!isset($fileinfo['savepath'])) $savepath = _ROOT_."/{$_SCONFIG['attachdir']}";
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
		 include_once($_SGLOBAL['news']['path'].'/include/urlupload.class.php');
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
		 
		 include_once($_SGLOBAL['news']['path'].'/include/httpupload.class.php');
		 $upload = new HttpUpload($fileobj, $savepath, $mimetype , $fileextname, $maxsize, $filerename, $savedir, $overwrite);
		
		 if(isset($fileinfo['filename'])) $upload->__set("upload_filename",_ROOT_.$fileinfo['filename']);
		 //图片上传
		 $up = $upload -> upfile();
	 }
	 //上传成功后操作
	 if( !$up[upfile_file_error] && in_array($up[upload_file_extname], array('gif','jpg','jpeg','png','bmp'))){
	   
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
					  include_once(_ROOT_.'/lib/image/imageresize.php');
					  $imgresize = new ImageResize();
					  $imgresize->load($up[upfile_file_path]);	
					  $imgresize->resize($make_thumb_width, $make_thumb_height);
					  $imgresize->save($up[upfile_file_path]);	     
				  } 
			  }
			  
			  ///////////判断是否生成缩略图文件组/////////////
			  $make_thumbs = false;
			  if($fileinfo['thumbs']){
			      $thumbsarr = explode("\n",str_replace("\r\n","\n",$fileinfo['thumbs']));
				  if(count($thumbsarr)>0){
					  //加载图片压缩处理类
					  include_once(_ROOT_.'/lib/image/imageresize.php');
					  $imgresize = new ImageResize();
					  $imgresize->load($up[upfile_file_path]);
				      foreach($thumbsarr as $thumb){
					      $thumb_size_tmp = @explode('*', $thumb );
						  if($thumb_size_tmp[0]>0 && $thumb_size_tmp[1]>0){
							  $imgresize->resize($thumb_size_tmp[0], $thumb_size_tmp[1]);
							  //取得图片信息
							  $ext = fileext($up[upfile_file_path]);
							  $filename = basename($up[upfile_file_path]);
							  $filesavedir = str_replace($filename, '', $up[upfile_file_path]);
							  $tmpfile = $filesavedir.str_replace(".{$ext}", '', $filename)."_{$thumb_size_tmp[0]}_{$thumb_size_tmp[1]}.{$ext}";
							  $imgresize->save($tmpfile);
						  }
					  }
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
		     if(strpos($water_image_file, '/')===false && strpos($water_image_file, '\\')===false) $water_image_file = $_SGLOBAL['news']['path'].'/images/'.$water_image_file;
		      else $water_image_file = $water_image_file;
			  if(is_file($water_image_file) && $make_image_water){
			  
			      $image_file = getimagesize($up[upfile_file_path]);//取得上传图片信息
				  $water_file = getimagesize($water_image_file);//取得水印图片信息
				  if($water_file[0]<$image_file[0] && $water_file[1]<$image_file[1] && $image_file[0]>=150 && $image_file[1]>=150){
					  include_once(_ROOT_.'/lib/image/imagewater.php');
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
							@$img->create($up[upfile_file_path]);
							unset($img);
						}
				  }
			  }
		  }
	 }
	 return $up;
}

//对话框
function showmessage($msgkey, $url_forward='', $second=3, $values=array()) {
		global $_SGLOBAL, $jieqiTpl,$jieqiTset;
		
		if(empty($_SGLOBAL['inajax']) && $url_forward && empty($second)) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: $url_forward");
		} else {
			$message = lang_replace($msgkey, $values);
		}
		if($_SGLOBAL['inajax']) {
			header('Content-Type:text/html; charset='.USER_CHARSET); 
			header("Cache-Control:no-cache");
			if($_REQUEST['CALLBACK']){
				include_once(_ROOT_.'/include/changecode.php');
				echo $_REQUEST['CALLBACK'].'('.json_encode(jieqi_gb2utf8($message)).');';
			}else echo $message;
		} else {
			if($url_forward) {
				$message = "<a href=\"$url_forward\">$message</a><script>setTimeout(\"window.location.href ='$url_forward';\", ".($second*1000).");<//script>";
			}
			$jieqiTset['jieqi_page_template'] = $GLOBALS['jieqiModules']['news']['path'].'/themes/empty.html';
			template('showmessage');
			//global $jieqiTset;
			$jieqiTpl->assign(array('charset' => CHARSET, 'pagetitle' => _SITE_, 'message' => $message, 'url_forward' => $url_forward, 'second'=>$second));
			$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template']));
			$jieqiTpl->setCaching(0);
			$jieqiTpl->display($jieqiTset['jieqi_page_template']);
			jieqi_freeresource();
		}
	exit();	
}

//语言替换
function lang_replace($text, $vars = array()) {
    jieqi_loadlang('showmessage', _MODULE_);
	global $jieqiLang;
	if(isset($jieqiLang[_MODULE_][$text])) {
		$text = $jieqiLang[_MODULE_][$text];
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


function xml_out($content) {
	@header("Expires: -1");
	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	@header("Content-type: application/xml; charset=".USER_CHARSET);
	echo '<'."?xml version=\"1.0\" encoding=\"".USER_CHARSET."\"?>\n";
	echo "<root><![CDATA[".trim($content)."]]></root>";
	exit();
}

//获取个性模板列表
function gettemplate($filetype = 'dir|file',$pre = ''){
    global $_SGLOBAL, $_SCONFIG, $_TPL;
	//获得个性模板
	$defaultdir = $_SCONFIG['template'] ? $_SCONFIG['template'] : 'default';
	$tpl_dir = $_SGLOBAL['news']['path']."/templates/{$defaultdir}/";
	$tpl_files = sreaddir($tpl_dir);
	$files = array();
	foreach ($tpl_files as $file) {
	    if($filetype=='dir' && is_file($tpl_dir.$file)) continue;
		if($filetype=='file' && is_dir($tpl_dir.$file)) continue;
		if($pre){
		    if(eregi("{$pre}",$file)) $tempfile = $file;
			else continue;
		}else $tempfile = $file;
		if($tempfile){
		    $farr = explode('.', $tempfile);
			if(is_file($tpl_dir.'__'.$farr[0])){
			   $name = shtmlspecialchars(file_get_contents($tpl_dir.'__'.$farr[0]));
			   $files[$tempfile] = $name;
			}else $files[$tempfile] = $tempfile;
		}
	}
	return $files;
}

//模板调用
function template($name, $cache = 0) {
	global $_SGLOBAL, $_SCONFIG, $_SN, $_TPL, $jieqiTset, $jieqiModules, $jieqiTpl, $_PAGE, $_OBJ,$jieqi_pagetitle;
    if(!isset($jieqiTset['jieqi_contents_template'])){
		if((substr($name, 0, 1))== '/' || substr_count($name,':')){
			$tpl = dirname($name);
			//赋值内容模板
			//$jieqiTset['jieqi_contents_template'] = $name;
			$fiex = !substr_count(basename($name),'.') ? '.html' : '';//模板扩展
			$jieqiTset['jieqi_contents_template'] = $name.$fiex;
		} else {
			$tpl = $GLOBALS['jieqiModules'][_MODULE_]['path']."/templates/".$_SCONFIG['template']."/";
			$fiex = !substr_count(basename($name),'.') ? '.html' : '';//模板扩展
			//赋值内容模板
			$jieqiTset['jieqi_contents_template'] = $tpl.$name.$fiex;
		}
		
		if(!file_exists($jieqiTset['jieqi_contents_template'])) {
			$jieqiTset['jieqi_contents_template'] = str_replace('/'.$_SCONFIG['template'].'/', '/default/', $jieqiTset['jieqi_contents_template']);
		}
	}
	$_SGLOBAL['TPL_DIR'] = dirname(str_replace(array(_ROOT_),array(_LOCAL_),$jieqiTset['jieqi_contents_template']));
	//定义风格模板
	//if(strpos($jieqiTset['jieqi_page_template'], '/') == 0) $jieqiTset['jieqi_page_template']=_ROOT_.$jieqiTset['jieqi_page_template'];
	if(!isset($jieqiTset['jieqi_page_template'])){
		$jieqiTset['jieqi_page_template'] = $GLOBALS['jieqiModules'][_MODULE_]['path'].'/themes/'.$_SCONFIG['template'].'/theme.html';
		if(!file_exists($jieqiTset['jieqi_page_template'])){
			if(!file_exists($jieqiTset['jieqi_page_template'])) {
				$jieqiTset['jieqi_page_template'] = str_replace('/'.$_SCONFIG['template'].'/', '/default/', $jieqiTset['jieqi_page_template']);
			}	
		}
	}
	if(!file_exists($jieqiTset['jieqi_page_template'])) $jieqiTset['jieqi_page_template']=_ROOT_.'/themes/'.JIEQI_THEME_NAME.'/theme.html';
	//赋值全局相关变量
	$jieqiTpl->assign('_SGLOBAL',$_SGLOBAL);
	//赋值全局/页面参数
	$jieqiTpl->assign('_SCONFIG',$_SCONFIG);
	//赋值页面浮动变量
	$jieqiTpl->assign('_SN',$_SN);
	//赋值页面相关变量
	$jieqiTpl->assign('_PAGE',$_PAGE);
	$jieqiTpl->assign('_OBJ',$_OBJ);
	$jieqiTpl->setCaching($cache);
}

?>