<?php
define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
if(empty($_REQUEST['k'])) exit(LANG_ERROR_PARAMETER);
jieqi_getConfigs(JIEQI_MODULE_NAME, 'replace');
$str = $jieqiReplace['system']['keyimgwords'][$_REQUEST['k']];
if($str=='') exit();
// Set the content-type
header("Content-type: image/png");
$keyimgdir = JIEQI_ROOT_PATH.'/files/keyimg/';
if(!is_dir($keyimgdir)) jieqi_createdir($keyimgdir);
$keyimgdir = $keyimgdir.'/'.$_REQUEST['k'].'.png';
if(!file_exists($keyimgdir)){
	// The text to draw
	$text = $str ?$str:' ';
	$text = iconv("gb2312","utf-8",$text);
	//$text = mb_detect_encoding($text);
	
	// Create the image
	///$im = imagecreatetruecolor(mb_strlen($text)*6, 20);
	$im = imagecreate(mb_strlen($text)*5.6, 22);
	
	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);
	imagefilledrectangle($im, 0, 0, 399, 22, $white);
	imagecolortransparent($im,$white); // 设置为透明色，若注释掉该行则输出绿色的图 
	// Replace path by your own font path
	$font = 'fangzhenjt.ttf';
	// Add some shadow to the text
	//imagettftext($im, 12, 1, 1, 16, $grey, $font, $text);
	// Add the text
	imagettftext($im, 12, 0, 0, 16, $black, $font, $text);
	// Using imagepng() results in clearer text compared with imagejpeg()
	imagepng($im,$keyimgdir);
	imagepng($im);
	imagedestroy($im);

}else{
    echo jieqi_readfile($keyimgdir);
}

?>