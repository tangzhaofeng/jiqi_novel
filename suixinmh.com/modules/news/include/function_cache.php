<?php
/*
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: function_cache.php 12398 2010-03-25 10:26:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

//获取缓存文件
function get_cache_data($name, $isupdate = false){//[缓存项目名称，是否强制更新]
    global $_SGLOBAL;
    $file = _ROOT_.'/configs/'._MODULE_.'/data_'.$name.'.php';
    if(is_file($file) && !$isupdate){
	    include_once($file);
	}else{
	    $function = "{$name}_cache";
	    $function();
		if(is_file($file)) include_once($file);
	}
}

//写入
function cache_write($name, $var, $values, $field = 0, $cachefile='') {
    global $_SGLOBAL;
	$cachefile = $cachefile!='' ? $cachefile : _ROOT_.'/configs/'._MODULE_.'/data_'.$name.'.php';
	$cachetext = "<?php\r\n".
		"if(!defined('IN_JQNEWS')) exit('Access Denied');\r\n".
		'$'.$var.'='.arrayeval($values, 0, $field).
		"\r\n?>";
	if(!swritefile($cachefile, $cachetext)) {
		exit("File: $cachefile write error.");
	}
}

//数组转换成字串
function arrayeval($array, $level = 0, $field = 0) {
	$space = '';
	for($i = 0; $i <= $level; $i++) {
		$space .= "\t";
	}
	$evaluate = "Array\n$space(\n";
	$comma = $space;
	if($array){
		foreach($array as $key => $val) {
			if(empty($field)){
				$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
			}else{
				$key = '\''.addcslashes($val[$field], '\'\\').'\'';
			}
			$val = !is_array($val) && (!preg_match("/^\-?\d+$/", $val) || strlen($val) > 12 || substr($val, 0, 1)=='0') ? '\''.addcslashes($val, '\'\\').'\'' : $val;
			if(is_array($val)) {
				$evaluate .= "$comma$key => ".arrayeval($val, $level + 1);
			} else {
				$evaluate .= "$comma$key => $val";
			}
			$comma = ",\n$space";
		}
	}
	$evaluate .= "\n$space)";
	return $evaluate;
}

?>
