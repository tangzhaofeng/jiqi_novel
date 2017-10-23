<?php
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>您查找的页面可能已经删除、更名或暂时不可用-'.$this->_tpl_vars['jieqi_sitename'].'</TITLE>
<META http-equiv=Content-Type content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'">
<META http-equiv=refresh content=3;url=\''.$this->_tpl_vars['jieqi_local_url'].'\'/>
<STYLE type=text/css>BODY {
	MARGIN: 0px; BACKGROUND-COLOR: #000000
}
BODY {
	COLOR: #ffffff; FONT-FAMILY: Comic Sans MS
}
TD {
	COLOR: #ffffff; FONT-FAMILY: Comic Sans MS
}
TH {
	COLOR: #ffffff; FONT-FAMILY: Comic Sans MS
}
.style6 {
	FONT-WEIGHT: bold; FONT-SIZE: 12px
}
.style7 {
	FONT-SIZE: 18px
}
.style8 {
	FONT-SIZE: 12px
}
.style9 {
	FONT-SIZE: 24px; FONT-FAMILY: "楷体_GB2312"
}
.style10 {
	COLOR: #ff0000
}
</STYLE>
<BODY><script>  
function jumpurl(){  
  location=\''.$this->_tpl_vars['jieqi_local_url'].'\';  
}  
setTimeout(jumpurl,3000);  </script> 
<DIV align=center>
<P align=left>　 
<P>　</P>
<P>　</P>
<P align=center>　<IMG height=220 
src="'.$this->_tpl_vars['jieqi_local_url'].'/images/xp.gif" width=176 
tppabs="/images/xp.gif"></P>
<P><b><font face="新宋体" size="4" color="#FF9900">您查找的页面可能已经删除、更名或暂时不可用。</font></b></P></DIV>
<DIV class=style6 align=center>
<P class=style9><span style="font-weight: 400">
<font size="5" face="华文楷体" color="#00FFFF">3秒钟后将自动返回网站首页</font></span></P>
<P>　</P></DIV>

</BODY></HTML>
';
?>