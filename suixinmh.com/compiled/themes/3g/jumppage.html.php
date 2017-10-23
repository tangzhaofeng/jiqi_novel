<?php
echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
	<title>成功提示</title>
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
	<meta http-equiv="Cache-Control" content="no-transform " /> 
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/promptPage.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'fonts/iconfont.css">
    <meta http-equiv="refresh" content=\'2; url='.$this->_tpl_vars['url'].'\'> 
</head>
<body>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
<div class="shibaitit">
温馨提示
</div>
<div class="img"><img src="'.$this->_tpl_vars['jieqi_themeurl'].'images/success.gif"></div>
<p class="shibai">'.$this->_tpl_vars['title'].'</p>
<p class="yuanyin">'.$this->_tpl_vars['content'].'</p>
<p class="jishi"><span>2</span>秒后，页面将自动返回上一个页面</p>
<a href="'.$this->_tpl_vars['url'].'" class="fhbut">返回</a>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/bottom.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
</body>
</html>
';
?>