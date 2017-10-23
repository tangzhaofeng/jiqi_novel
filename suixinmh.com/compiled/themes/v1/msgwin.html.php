<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
<title>成功提示</title>
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
   <em class="ico2"></em>
   <p class="tit">'.$this->_tpl_vars['title'].'</p>
  <p class="txt">'.$this->_tpl_vars['content'].'</p>
  <div class="dwn tc">
    <a href="javascript:history.back()" class="dbtn">返&nbsp;&nbsp;回</a>
    <div class="orn"></div>
  </div>
</div>
</body>
</html>
';
?>