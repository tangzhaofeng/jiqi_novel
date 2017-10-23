<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>错误提示</title>
    <link href="'.$this->_tpl_vars['jieqi_themecss'].'" type="text/css" rel="stylesheet" />
    <!--[if IE 6]>
    <script src="'.$this->_tpl_vars['jieqi_themeurl'].'js/DD_belatedPNG.js" type="text/javascript"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix(\'div, ul, img, li, input,span,h3,h2,a\');
    </script>
    <![endif]-->
</head>

<body class="bg7">
<div class="pap">
    <em class="ico1"></em>
    <p class="tit">出现错误！</p>
    <p class="txt"><br />错误原因：'.$this->_tpl_vars['errorinfo'].'<br />'.$this->_tpl_vars['debuginfo'].'<br />请 <a href="javascript:history.back(1)">返 回</a> 并修正<br /><br /></p>
    <div class="dwn tc">
        <a href="javascript:window.close()" class="dbtn">关&nbsp;&nbsp;闭</a>
        <div class="orn"></div>
    </div>
</div>
</body>
</html>
';
?>