<?php
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="'.$this->_tpl_vars['jieqi_charset'].'">
    <title>评论</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
    <meta name="description" content="'.$this->_tpl_vars['meta_description'].'" />
    <meta name="author" content="'.$this->_tpl_vars['meta_author'].'" />
    <meta name="copyright" content="'.$this->_tpl_vars['meta_copyright'].'" />
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'fonts/iconfont.css">
    <style>
        .supportlinks li {
            width: 20%;
            text-align: center;
            float: left;
        }

        .supportlinks li a {
            display: block;
            width: 90%;
            max-width: 100px;
            min-width: 50px;
            margin: 0 auto;
        }

        .supportlinks li a img {
            width: 100%;
        }

        .bbdar {
            border-bottom: 1px dashed #CCC;
        }

        .commTitle {
            color: #3361a7;
        }

        .commTitle span {
            width: 18px;
            height: 18px;
            line-height: 18px;
            color: #fff;
            display: inline-block;
            border-radius: 1px;
            font-size: 10px;
            text-align: center;
        }

        .commTitle span.span1 {
            background: #5acde6;
        }

        .commTitle span.span2 {
            background: #37a5f0
        }

        .commTitle span.span3 {
            background: #dc147d
        }

        .commTitle span.span4 {
            background: #cd8c14
        }
        .section {
            margin-top: 10px;
            padding: 0 10px;
        }
        .commentAdd{
            margin-top: 0;
        }
        .commentAdd .module{
            padding-top: 5px;
        }
        .commentAdd .mt20{
            margin-top: 9px;
        }

        .commentAdd .commenttext  {
            padding-top: 7px;
        }
        .commentAdd .commenttext .text {
            width: 100%;
            height: 80px;
            border: 1px solid #c1bebe;
            padding: 0;
            margin: 0 0 10px 0;
            float: left;
            padding: 4px;
            align-items: center;
        }

        .commentAdd .commenttext .fabiao {
            width: 100%;
            height: 40px;
            color: #fff;
            font-size: 18px;
            line-height: 40px;
            text-align: center;
            display: block;
            border-radius: 5px;
            background-color: #d50d56;
            margin: 0 auto;
            border: none;
        }

    </style>
</head>

<body oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
<!--nav-->
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
<!--title-->
<div class="p10 f16"><a href="/"> <i class="iconfont f18 vam mr5">&#xe600;</i></a><a href="'.geturl('3g','articleinfo','SYS=aid='.$this->_tpl_vars['article']['articleid'].'').'">《'.$this->_tpl_vars['article']['articlename'].'》的评论</a></div>
<!--评论-->
<div class="section commentAdd">
    <div class="mt20 mb10">
        <div class="module">
            <form action="'.geturl('3g','reviews','SYS=method=add&aid='.$this->_tpl_vars['article']['articleid'].'').'" method="post">
                <div class="commenttext">
                    <textarea name="pcontent" class="text" placeholder="请注意您的评论用语哦，文明看书理性发言！"></textarea>
                    <input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '" />
                    <input type="hidden" name="checkflag" value="1" />
                    <input class="fabiao" type="submit" value="发表评论" />
                </div>
            </form>
        </div>
    </div>
</div>
<div class="m10 mt0 br5 bgcfff p10">
    <div class="pb10 f16 clearfix" style="border-bottom: #E2B4AC 1px solid;color: #a44b3b;">评论</div>
    ';
if($this->_tpl_vars['count']>0){
echo '
    ';
if (empty($this->_tpl_vars['reviewrows'])) $this->_tpl_vars['reviewrows'] = array();
elseif (!is_array($this->_tpl_vars['reviewrows'])) $this->_tpl_vars['reviewrows'] = (array)$this->_tpl_vars['reviewrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['reviewrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['reviewrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['reviewrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['reviewrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['reviewrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	$this->_tpl_vars['mid']=$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posterid']; 
echo '
    <div class="pl35 ptb10 pr bb bcddd" style="min-height:30px;">
        <!--<div class="pa left0 top10 w30 h30 oh br3"><img src="images/pic6.png" class="wp100" alt="" /></div>-->
        <div class="commTitle">
            '.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['poster'].'
        </div>
        <div class="f12 clearfix lh25">'.date('Y-m-d H:i:s',$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posttime']).'
        </div>
        <div class="lh20">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posttext'].'</div>
    </div>
    ';
}
echo '
    <!--page-->
    <div class="mt10 bgceee tc ptb5">
        <form action="'.geturl('3g','reviews','SYS=aid='.$this->_tpl_vars['article']['articleid'].'').'" id="jumppage">
            ';
if($this->_tpl_vars['page']>1){
echo '
            <a href="'.$this->_tpl_vars['url_3g_prev'].'" class="dib">&lt;上一页</a>
            ';
}
echo '
        <input type="text" class="bgcfff b bcddd w40 lh25 tc" name="page" value="'.$this->_tpl_vars['page'].'" />
        <a href="javascript:" onclick="document.getElementById(\'jumppage\').submit()" class="w40 lh25 bgcfff dib ml5 b bcddd">跳转</a>
        <span>'.$this->_tpl_vars['page'].'/'.$this->_tpl_vars['pagecount'].'</span>
            ';
if($this->_tpl_vars['has_next_page']==1){
echo '
        <a href="'.$this->_tpl_vars['url_3g_next'].'" class="dib">下一页&gt;</a>
            ';
}
echo '
        </form>
    </div>

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
</body>
</html>';
?>