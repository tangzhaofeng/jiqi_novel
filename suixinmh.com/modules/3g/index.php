<?php
$agent=$_SERVER['HTTP_USER_AGENT'];
//if ($agent=='Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; SV1)') {
//    exit();
//}

//if (strpos($agent,'Windows NT') !== false) {
//    exit();
//}
header("Content-type: text/html; charset=utf-8");
@define('JIEQI_WAP_PAGE', '1');
@define('JIEQI_CHARSET_CONVERT', '0');
@define('JIEQI_CHAR_SET', 'utf-8');
@define('JIEQI_SESSION_NAME', session_name());
define('JIEQI_MODULE_NAME', '3g');
@define('JIEQI_PAGE_TAG','<div class="but"><a href="$prepage">上一页</a>[option]<option value="$optionurl">$option</option>[/option]</div><div class="inbut"><select onchange="window.location.href=this.value;">{$select}</select></div><div class="but"><a href="$nextpage">下一页</a></div>');
require '../../index.php';
?>