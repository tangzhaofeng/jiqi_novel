<?php
echo '<div class="logn">»¶Ó­·ÃÎÊ<!--<span class="b"> '.$this->_tpl_vars['jieqi_sitename'].' </span>-->£¬<span class="g9">Çë<a href="'.geturl('system','login').'" class="f_blue5">µÇÂ¼</a>»ò<a href="'.geturl('system','register').'" class="f_blue5">×¢²á</a></span>';
$this->_tpl_vars['url']= JIEQI_LOCAL_URL; 
echo '<a href="javascript:;"  id="qqheadlogin" class="qq f_gray3r">QQµÇÂ¼</a><!---<a href="javascript:void(0)"  class="sina f_gray3r">Î¢²©µÇÂ¼</a>--></div>
<script type="text/javascript">
layer.ready(function(){
	var jumpurl = $("#jumpurl").val();
	var url = "'.$this->_tpl_vars['url'].'/qqlogin/";
	if (jumpurl && jumpurl!="undefined"){
		url = url + "?jumpurl=" + jumpurl;
	}
	
   $("#qqheadlogin").bind("click",function(){
		otherlogin(url);
	});
	//onclick="otherlogin(\''.$this->_tpl_vars['url'].'/qqlogin\')"
});
</script>';
?>