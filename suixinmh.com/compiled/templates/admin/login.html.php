<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
<title>后台登陆-'.$this->_tpl_vars['jieqi_sitename'].'</title>
<link href="'.$this->_tpl_vars['jieqi_url'].'/templates/admin/style/login_adm.css" type="text/css" rel="stylesheet" />
<!--[if IE 6]>
<script src="'.$this->_tpl_vars['jieqi_themeurl'].'js/DD_belatedPNG.js" type="text/javascript"></script>
<script type="text/javascript">
DD_belatedPNG.fix(\'div, ul, img, li, input,span,h3,h2,a\');
</script>
<![endif]-->
</head>

<body class="bg1">
 <!--logn begin-->
<div class="logn">
 <form action="'.$this->_tpl_vars['url_login'].'" method="post" name="frmlogin">
  <div class="int"><label class="name">用户名</label><input id="username" name="username" type="text" value="'.$this->_tpl_vars['jieqi_username'].'" /></div>
  <div class="int"><label class="sn">密&nbsp;&nbsp;码</label><input name="password" type="password"/></div>
  ';
if($this->_tpl_vars['show_checkcode'] == 1){
echo '<div class="int2"><label>验证码</label><input name="checkcode" type="text" /><img src="'.$this->_tpl_vars['url_checkcode'].'" class="code" width="109" height="40" style="cursor:pointer;" onclick="this.src=\''.$this->_tpl_vars['url_checkcode'].'?rand=\'+Math.random();" /></div>';
}
echo '
  <p class="f_blue back"><a href="'.$this->_tpl_vars['jieqi_url'].'">返回首页</a>┆<a href="javascript:history.back(1);">返回上一页</a>┆<a href="'.$this->_tpl_vars['jieqi_url'].'/getpass.php">取回密码</a></p>
  <button name="submit" type="submit" class="btn_logn"></button><input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '">
 </form>
</div><!--logn end-->
</body>
</html>
';
?>