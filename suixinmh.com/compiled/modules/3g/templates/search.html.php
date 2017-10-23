<?php
echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
    <title>搜索</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
    <meta http-equiv="Cache-Control" content="no-transform " />
    <meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
    <meta name="description" content="'.$this->_tpl_vars['meta_description'].'" />
    <meta name="author" content="'.$this->_tpl_vars['meta_author'].'" />
    <meta name="copyright" content="'.$this->_tpl_vars['meta_copyright'].'" />
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/extend.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'fonts/iconfont.css">

    ';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/js.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
</head>
<body oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/nav.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/search.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '

';
if(isset($this->_tpl_vars['searchnonum'])==1){
echo '
    <div class="ss_tit"><span class="keyword">-</span>&nbsp;&nbsp;搜索结果（<span class="shu">-</span>）</div>
    <div class="rank">
        <p class="name" style="color: #a31000;">'.$this->_tpl_vars['searchnonum'].'</p>
    </div>
    ';
}else{
echo '
    <div class="ss_tit"><span class="keyword">'.$this->_tpl_vars['searchkey'].'</span>&nbsp;&nbsp;搜索结果（<span class="shu">'.$this->_tpl_vars['allresults'].'</span>）</div>
    ';
if (empty($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = array();
elseif (!is_array($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = (array)$this->_tpl_vars['articlerows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['articlerows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['articlerows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['articlerows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '

    <div class="pr bb bcddd bgcfff addList1">
        <a href="'.geturl('3g','articleinfo','SYS=aid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articleid'].'').'" class="db p10" style="height:85px">
            <img class="pa w60 b bcddd h85" src="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_image'].'" alt="">
            <div class="pl75 mt10">
                <h2 class="f14 c333 mb10 clearfix">';
echo str_replace($this->_tpl_vars['searchkey'],'<span>'.$this->_tpl_vars['searchkey'].'</span>' ,$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename']);  
echo '</h2>
                <p class="c999 f12 tEllipsis">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['intro'].'</p>
            </div>
        </a>
    </div>
';
}
echo '
';
}
echo '
<div class="fanye">
    ';
if(count($this->_tpl_vars['articlerows']) > 0){
echo '
    '.$this->_tpl_vars['url_jumppage'].'
    ';
}
echo '
</div>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/bottom.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/js.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
</body>
<!--<script type="text/javascript">-->
    <!--$(function(){-->
        <!--$("[data-act=search_sub]").live("click", function(event){-->
            <!--event.preventDefault();-->
            <!--var _val=$("#searchkey").val();-->
            <!--if(_val.length<2){-->
                <!--layer.open({-->
                    <!--type:0,-->
                    <!--content:"搜索关键字必须大于2个字符，" + _val,-->
                    <!--time:2-->
<!--//				btn:["OK"]-->
                <!--})-->
                <!--return false;-->
            <!--}else{-->
                <!--$("[data-name=search_form]").submit();-->
            <!--}-->
        <!--})-->
    <!--})-->
<!--</script>-->
</html>';
?>