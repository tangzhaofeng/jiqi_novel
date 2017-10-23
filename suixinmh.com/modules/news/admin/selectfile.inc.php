<?php
/*
	[Cms news] (C) 2009-2010 Cms Inc.
	$Id: selectfile.inc.php  2010-04-26 10:30:09Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
$_PAGE['_GET']['filetype'] = empty($_PAGE['_GET']['filetype']) ? "image" : $_PAGE['_GET']['filetype'];
if(!in_array($_PAGE['_GET']['filetype'], array('image', 'attach', 'flash', 'media'))) $_PAGE['_GET']['filetype'] = 'image';

//定义根目录
$_PAGE['rootdir'] = JIEQI_ROOT_PATH."/{$_SCONFIG['attachdir']}/{$_PAGE['_GET']['filetype']}/";
$_PAGE['currentdir'] = $_PAGE['_GET']['currentdir']!='' ? str_replace('//','/',$_PAGE['_GET']['currentdir'].'/') : '';
$_PAGE['parentdir'] = $_PAGE['_GET']['parentdir']!='' ? $_PAGE['_GET']['parentdir'] : '';
$_PAGE['thisdir'] = $_PAGE['rootdir'].$_PAGE['currentdir'];
$_PAGE['backparentdir'] = dirname($_PAGE['parentdir']) == '\\' || dirname($_PAGE['parentdir']) == '.' ? '' : dirname($_PAGE['parentdir']);
//获得模型数据列表
$list = glob($_PAGE['thisdir']."*");
$files = glob($_PAGE['thisdir']."*.*");
if(!$list) $list = array();
if(!$files) $files = array();

$dirs = array_diff($list, $files);
//浏览
$_PAGE['listdirs'] = array();
$_PAGE['listfiles'] = array();

if(is_array($dirs))
{
	foreach($dirs as $k=>$v)
	{
		$ldir['name'] = basename($v);
		//$ldir['path'] = ($currentdir ? dir_path($currentdir) : '').$ldir['name'];
		$ldir['type'] = lang_replace('file_dir');
		$ldir['size'] = lang_replace('file_dirtype');
		$ldir['mtime'] = date("Y-m-d H:i:s",filemtime($v));
		$_PAGE['listdirs'][] = $ldir;
	}
}
require $jieqiModules['news']['path'].'/images/ext/ext.php';
if(is_array($files))
{
	foreach($files as $k=>$v)
	{
		$ext = fileext($v);
		$lfile['ext'] = array_key_exists($ext,$filetype) ? $ext : "other";
		$lfile['type'] = $filetype[$lfile['ext']];
		$lfile['isimage'] = in_array($lfile['ext'],array('gif','jpg','jpeg','png','bmp')) ? 1 : 0;
		if($_PAGE['_GET']['filetype']=='image' && !$lfile['isimage']) continue;
		$lfile['path'] = str_replace(JIEQI_ROOT_PATH, '', $v);
	    $lfile['name'] = "<img src='{$jieqiModules['news']['url']}/images/ext/".$lfile['ext'].".gif' width='24' height='24' border='0'>".basename($v).(($lfile['isimage']) ? "<br /><img src='".$_SCONFIG['attachurl'].$lfile['path']."' width='50' border='0'>" : '');
		$lfile['size'] = round(filesize($v)/1000);
		$imagesize = getimagesize($v);
		$lfile['imagesize'] = $imagesize[0].'*'.$imagesize[1];
		$lfile['mtime'] = date("Y-m-d H:i:s",filemtime($v));
		$_PAGE['listfiles'][] = $lfile;
	}
}
//template('admin/selectfile');
?>
