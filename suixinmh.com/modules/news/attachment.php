<?php
/**
 * 文件上传
 *
 * 页面预处理，加载功能模块
 * 
 * 调用模板：无
 * 
 * @category   Cms
 * @package    news
 * @copyright  Copyright (c) huliming Network Technology Co.,Ltd.
 * @author     $Author: huliming QQ329222795 $
 * @version    $Id: attachment.php 332 2010-04-23 16:15:08Z huliming $
 */
@set_time_limit(0);
@session_write_close();
@define('IN_JQNEWS', TRUE);
session_start();
include_once('./common.php');
$ac = empty($_PAGE['_GET']['ac']) ? "attachment" : $_PAGE['_GET']['ac'];
if(!$_SGLOBAL['supe_uid'])
{   
    if($_PAGE['_GET']['hash'] != formhash($_PAGE['_GET']['uid'])){
		if($_PAGE['_GET']['from'] == 'fckeditor')
		{
			exit(SendUploadMsg('', '', 1, lang_replace('not_upload_admin')));
		}
		else
		{
			showmessage('not_upload_admin');
		}
	}
}

$catid = $_PAGE['_GET']['catid'];
//构造上传表单
include_once('./include/loadclass.php');
//初始化栏目操作对像和加载栏目数据列表
$_OBJ['category'] = new Category();
//$_PAGE['posturl'] = $_OBJ['category']->getPosturl($catid);//表单提交URL
$_PAGE['attachurl'] = $_OBJ['category']->getAttachurl($catid);//附件URL服务器
//在多服务器环境下，表单地址要和附件服务器URL保持一致，否则附件上传会出错
//if($_PAGE['attachurl']!= 'http://'.$_SERVER['HTTP_HOST']) header("location:".$_PAGE['attachurl'].$_SERVER['REQUEST_URI']);
$fileinfo = array();
if(($cate = $_OBJ['category']->get($catid,true))){
    //上传文件时加载附加配置
	$fileinfo = $_OBJ['category']->getCateSet($catid);
	
	//图片上传模型字段
	if( in_array( $_PAGE['_GET']['from'], array('imagefield','fckeditor','uploadfiles','uploadimages','remotefile','') ) ){
		//加载表单对象类
		include('./include/fields/form_image.class.php');
		include('./include/fields/formelements.class.php');
		$form_image = new Form_image(new FormElements($cate['modelid'],$catid), $_PAGE['_GET']['uploadtext']);
		$form_image->getContent();
		$_PAGE['setting'] = $form_image->setting;
	}else exit();
	if($_SGLOBAL['supe_uid']){
		//验证用户
		$power = new Power();
		$power->addPower('add', $form_image->formobj->category['setting']['add']);
		if(!$power->checkPower('add', true)){
			$power->addPower('edit', $form_image->formobj->category['setting']['edit']);
			$power->checkPower('edit', false);
		}
	}
}else exit();
switch($_PAGE['_GET']['action'])
{
      case 'upload':
		   
		   if($_PAGE['_GET']['dosubmit'] || $_PAGE['_POST']['dosubmit']){

                    //如果是图片字段组件上传
					switch($_PAGE['_GET']['from']){
					     case 'imagefield':
							 if($_PAGE['_POST']['upload_filename']) $fileinfo['filename'] = $_PAGE['_POST']['upload_filename'];
							 if($_PAGE['setting']['maxsize']) $fileinfo['maxsize'] = $_PAGE['setting']['maxsize'];
							 if($_PAGE['setting']['fileextname']) $fileinfo['fileextname'] = $_PAGE['setting']['fileextname'];
							 if($_PAGE['setting']['thumb_enable']>=0) $fileinfo['thumb_enable'] = $_PAGE['setting']['thumb_enable'];
							 if($_PAGE['setting']['thumb_width']) $fileinfo['thumb_width'] = $_PAGE['_POST']['thumb_width'];
							 if($_PAGE['setting']['thumb_height']) $fileinfo['thumb_height'] = $_PAGE['_POST']['thumb_height'];
							 if($_PAGE['setting']['attachwater']>=0) $fileinfo['attachwater'] = $_PAGE['setting']['attachwater'];
							 if($_PAGE['setting']['attachwimage']) $fileinfo['attachwimage'] = $_PAGE['setting']['attachwimage'];
						 break;
						 case 'remotefile':
							 if($_PAGE['setting']['thumb_enable']>=0) $fileinfo['thumb_enable'] = $_PAGE['setting']['thumb_enable'];
							 if($_PAGE['setting']['thumb_width']) $fileinfo['thumb_width'] = $_PAGE['setting']['thumb_width'];
							 if($_PAGE['setting']['thumb_height']) $fileinfo['thumb_height'] = $_PAGE['setting']['thumb_height'];
							 if($_PAGE['setting']['attachwater']>=0) $fileinfo['attachwater'] = $_PAGE['setting']['attachwater'];
							 if($_PAGE['setting']['attachwimage']) $fileinfo['attachwimage'] = $_PAGE['setting']['attachwimage'];
							 if($_PAGE['setting']['thumbs']) $fileinfo['thumbs'] = $_PAGE['setting']['thumbs'];
						 break;
						 default:
							 if($_PAGE['setting']['maxsize']) $fileinfo['maxsize'] = $_PAGE['setting']['maxsize'];
							 if($_PAGE['setting']['fileextname']) $fileinfo['fileextname'] = $_PAGE['setting']['fileextname'];
							 if($_PAGE['setting']['thumb_enable']>=0) $fileinfo['thumb_enable'] = $_PAGE['setting']['thumb_enable'];
							 if($_PAGE['setting']['thumb_width']) $fileinfo['thumb_width'] = $_PAGE['setting']['thumb_width'];
							 if($_PAGE['setting']['thumb_height']) $fileinfo['thumb_height'] = $_PAGE['setting']['thumb_height'];
							 if($_PAGE['setting']['thumbs']) $fileinfo['thumbs'] = $_PAGE['setting']['thumbs'];
							 if($_PAGE['setting']['attachwater']>=0) $fileinfo['attachwater'] = $_PAGE['setting']['attachwater'];
							 if($_PAGE['setting']['attachwimage']) $fileinfo['attachwimage'] = $_PAGE['setting']['attachwimage'];
						 break;
					}
					
                    if($_PAGE['_GET']['from'] == 'fckeditor' && $xmldata=file_get_contents('php://input')){//编辑器远程插件上传
					    preg_match_all("/(href|src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $xmldata, $matches);
						//file_put_contents(date('YmdHis',time()).'.txt',$c);exit;
						$filelist = array_values(array_unique($matches[3]));
						if($matches[3]){
							$xmldata = save_remotefile($xmldata, array('gif','jpg','jpeg','bmp','png'), 'content', $fileinfo);
							$curr = count($filelist);
							$currfile = $filelist[count($filelist)-1];
							$find = array("curr=\"\"", "currfile=\"\"");
							$replace = array("curr=\"{$curr}\"", "currfile=\"{$currfile}\"");
							//file_put_contents(date('YmdHis',time()).'.txt',$xmldata);
						}else{
						    $find = array("curr=\"\"");
							$replace = array("curr=\"0\"");
						}
						$xmldata = str_replace($find, $replace, $xmldata);
						preg_match_all("/(href|src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $xmldata, $matches);
						$find = $replace = array();
						foreach($matches[0] as $k=>$attr){
						    $url = $filelist[$k];
						    $find[] = $attr;
							$replace[] = "oldsrc=\"{$url}\" ".$attr;
						}
						$xmldata = str_replace($find, $replace, $xmldata);
						//file_put_contents(date('YmdHis',time()).'.txt',$xmldata);
						header("Content-type:text/xml");
						exit($xmldata);
					}
					
					$fileinfo['filetype'] = $_PAGE['_GET']['filetype'];
					
					if($_PAGE['_GET']['from'] == 'remotefile') $fileobj = $_PAGE['_GET']['remotefile'];
					elseif($_PAGE['_GET']['from'] == 'uploadimages') $fileobj = $_PAGE['_GET']['file'];
					else $fileobj = 'uploadfile';
					
                    if($_PAGE['_GET']['from'] == 'remotefile'){
					    if(strpos($fileobj, "[page]_")!==false){
						    $fileurl = array();
						    $fileobj = explode("[page]_", $fileobj);
							foreach($fileobj as $k=>$imgfile){
							    $up = @upfile($imgfile, $fileinfo ); //上传文件
								if(!$up[upfile_file_error]){
									$upfile_file_path = $up[upfile_file_path];
									$fileurl[] = str_replace(_ROOT_, '', $upfile_file_path);
								} else $fileurl[] = $imgfile;
							}
							$fileurl = implode("[page]\n",$fileurl);
						}else{
						    $up = @upfile($fileobj, $fileinfo ); //上传文件
							$upfile_file_path = $up[upfile_file_path];
							$fileurl = str_replace(JIEQI_ROOT_PATH, '', $upfile_file_path);
							//$filename = $up[upfile_file_newname];
						}
					}else{
						$up = @upfile($fileobj, $fileinfo ); //上传文件
						$upfile_file_path = $up[upfile_file_path];
						$fileurl = str_replace(JIEQI_ROOT_PATH, '', $upfile_file_path);
						$filename = $up[upfile_file_newname];
					}
					
					$error = false;
					$returnmsg = ''; // 定义返回错误信息
					
					if( $up[upfile_file_error] ){
					    if( $up[upfile_file_error]==1 ){ //文件大小超过最大值
						    if( in_array($up[upload_file_extname], array( 'jpg', 'jpeg', 'gif', 'bmp', 'png' ) ) ){
							    $returnmsg = lang_replace('image_size_failure', array( $_SCONFIG['maximagesize'] ) );
							}else{
							    $returnmsg = lang_replace('attach_size_failure', array( $_SCONFIG['maximagesize'] ) );
							}
						}elseif( $up[upfile_file_error]==2 ){ //文件mime类型错误
						    $returnmsg = lang_replace('file_mime_failure', array( $up[upload_mime_types] ) );
						}elseif( $up[upfile_file_error]==3 ){ //文件扩展名不符合要求
						    $returnmsg = lang_replace('file_extname_failure', array( $up[upload_file_extname] ) );
						}elseif( $up[upfile_file_error]==4 ){ //上传的文件超过了   php.ini   中   upload_max_filesize   选项限制的值
						    $returnmsg = lang_replace('upload_max_filesize');
						}else{
						    $returnmsg = lang_replace('file_other_failure'); //未知
						}
						$error = true;
					}
					
		       //通过编辑器上传后的操作
		       if($_PAGE['_GET']['from'] == 'fckeditor'){
					//如果没有上传错误
					if( !$up[upfile_file_error] ){
					    $returnmsg = isset( $_PAGE['_GET']['id'] ) ? "{$fileurl} {$filename}" : '';
					}
					//上传成功后返回消息
					exit(SendUploadMsg($fileurl, $filename, $error, $returnmsg));
			   }elseif( in_array( $_PAGE['_GET']['from'], array('imagefield') ) ){
					//如果没有上传错误
					if( !$up[upfile_file_error] ){
					    $returnmsg = lang_replace('file_upload_success');
					    $returnmsg .= "<script type='text/javaScript' src='{$_PAGE['attachurl']}/lib/html/fckeditor/jquery.min.js'></script><script language='javascript'>	try{ $(window.opener.document).find(\"form[@name='myform'] #{$form_image->field}\").val(\"{$fileurl}\");}catch(e){} window.close();</script>";					
					}
			        jieqi_jumppage($_SGLOBAL['refer'], LANG_DO_SUCCESS, $returnmsg);
			   }elseif($_PAGE['_GET']['from'] == 'uploadfiles'){
			        //如果没有上传错误
					if( !$up[upfile_file_error] ){
					    $returnmsg = lang_replace('file_upload_success');
					    $uploadtext = $_PAGE['_GET']['uploadtext'];
						$filename = $_PAGE['_POST']['file_description'] ?$_PAGE['_POST']['file_description'] :$up['upload_file_name'];
						//单位换算
						$filesize = formatsize($up['upload_file_size']);
					    echo '<script>var s = parent.document.getElementById("'.$uploadtext.'").value == "" ? "" : "\n";parent.document.getElementById("'.$uploadtext.'").value += s+"'.$filename.'|'.$fileurl.'";if(parent.document.getElementById("filesizebak")) parent.document.getElementById("filesizebak").innerHTML="'.$filesize.'";</script>';
					}
					echo '<table width="100%" cellpadding="0" cellspacing="0"  height="100%" bgcolor="#F1F3F5">';
					echo '<tr><td style="font-size:12px;color:blue;">';
					echo '<a href="'.$_SGLOBAL['refer'].'">'.$returnmsg.' Click To Back</a>';
					echo '</td></tr></table>';
					echo '<script>setTimeout("window.location=\''.$_SGLOBAL['refer'].'\'", 3000);</script>';
					exit;
			   }elseif($_PAGE['_GET']['from'] == 'uploadimages'){
					echo "{";
					echo				"filesize: '" . formatsize($up['upload_file_size']) . "',\n";
					echo				"fileurl: '" . $fileurl . "',\n";
					echo				"filename: '" . $filename . "',\n";
					echo				"error: '" . $returnmsg . "',\n";
					echo				"msg: '" . lang_replace('file_upload_success') . "'\n";
					echo "}";exit;
			   }elseif($_PAGE['_GET']['from'] == 'remotefile'){
                    exit($fileurl);
		       }
		   }else{
                    //
		   }
	  break;
}

/////////////////////////////////////////////////////////////////
//上传成功后返回消息
function SendUploadMsg($fileurl, $filename, $error = '0' ,$returnmsg = '') {
        global $_PAGE;
		//上传成功后返回消息
		if(isset($_PAGE['_GET']['id'])) {
					
			$message = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".CHARSET."\"><script language='javascript'>";
			$message .= "window.parent.show_ok('{$error}-{$_PAGE['_GET']['id']}','{$returnmsg}');";
			$message .= "</script>";
						
		}else{
			if(!$error){
				$message = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".CHARSET."\"><script language='javascript'>";
				$message .= "window.parent.SetUrl('{$fileurl}', '', '', '{$filename}');";
				$message .= "</script>";
			}else{
			    $message = SendUploadResults(1, $fileurl, $filename, $returnmsg);
			}
						
		}
		return $message;
}

//返回附件弹窗式消息
function SendUploadResults( $errorNumber, $fileUrl = '', $fileName = '', $customMsg = '' )
{
	$c = "<script type='text/javascript'>";
	$rpl = array( '\\' => '\\\\', '"' => '\\"' ) ;
	$c .= 'window.parent.OnUploadCompleted(' . $errorNumber . ',"' . strtr( $fileUrl, $rpl ) . '","' . strtr( $fileName, $rpl ) . '", "' . strtr( $customMsg, $rpl ) . '") ;' ;
	$c .= '</script>' ;
	return $c;
}

if($_PAGE['setting']['maxsize']) $fileinfo['maxsize'] = $_PAGE['setting']['maxsize'];
else $fileinfo['maxsize'] = $_SCONFIG['maximagesize'];
if($_PAGE['setting']['fileextname']) $fileinfo['fileextname'] = $_PAGE['setting']['fileextname'];
if($_PAGE['setting']['thumb_enable']>=0) $fileinfo['thumb_enable'] = $_PAGE['setting']['thumb_enable'];
if($_PAGE['setting']['thumb_width']) $fileinfo['thumb_width'] = $_PAGE['setting']['thumb_width'];
if($_PAGE['setting']['thumb_height']) $fileinfo['thumb_height'] = $_PAGE['setting']['thumb_height'];
if($_PAGE['setting']['attachwater']>=0) $fileinfo['attachwater'] = $_PAGE['setting']['attachwater'];
if($_PAGE['setting']['attachwimage']) $fileinfo['attachwimage'] = $_PAGE['setting']['attachwimage'];
$_PAGE['setting'] = $fileinfo;
//处理模板
template("admin/{$ac}");
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
/*ob_start();
print_r($_PAGE);
print_r($up);
$ff = ob_get_contents();
ob_end_clean();
$f = fopen('a.txt','a+');
fputs($f,$ff."\n");
fclose($f);*/

?>