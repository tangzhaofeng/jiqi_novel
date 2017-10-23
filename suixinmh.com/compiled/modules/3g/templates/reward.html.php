<?php
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="'.$this->_tpl_vars['jieqi_charset'].'">
    <title>打赏</title>
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
';
if($this->_tpl_vars['egolds']<100){
echo '
<div class="lh25 f14 m10 p10" style="background: #ecc4c4;border: 1px solid #e1b4b4;color: #3e2400;">您的余额不足！ </div>
';
}
echo '
<div class="m10 br5 bgcfff">
    <h1 class="mlf10 ptb10 f16 clearfix" style="color: #a44b3b;border-bottom:#E2B4AC 1px solid;">当前余额:<span class="cRed">'.$this->_tpl_vars['egolds'].'</span>'.$this->_tpl_vars['egoldname'].'<a href="'.geturl('3g','pay').'" class="f12 cRed fr">立即充值</a></h1>
    <div class="m10 pb15">
        <form  name="rewardform" id="rewardform"  action="'.geturl('3g','huodong','SYS=method=reward&aid='.$this->_tpl_vars['articleid'].'').'" method="post">
        <ul class="clearfix supportlinks">
            ';
if (empty($this->_tpl_vars['reward_item'])) $this->_tpl_vars['reward_item'] = array();
elseif (!is_array($this->_tpl_vars['reward_item'])) $this->_tpl_vars['reward_item'] = (array)$this->_tpl_vars['reward_item'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['reward_item']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['reward_item']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['reward_item']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['reward_item']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['reward_item']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
            <li><a href="'.geturl('3g','huodong','SYS=method=reward&aid='.$this->_tpl_vars['articleid'].'&item='.$this->_tpl_vars['reward_item'][$this->_tpl_vars['i']['key']]['item'].'').'"><img src="'.$this->_tpl_vars['jieqi_themeurl'].'/reward/img/'.$this->_tpl_vars['reward_item'][$this->_tpl_vars['i']['key']]['pic'].'" alt="" /></a>'.$this->_tpl_vars['reward_item'][$this->_tpl_vars['i']['key']]['name'].'</li>
            ';
}
echo '
        </ul>
        <div class="mtb10 f14 c333">点击上面图标选择打赏类型</div>
        <div class="h30 lh30 pr pl30"><span class="dib w30 h30 oh pa left0 top0"><img src="'.$this->_tpl_vars['jieqi_themeurl'].'/reward/img/'.$this->_tpl_vars['reward_pic'].'" class="wp100" alt="" /></span>（一个'.$this->_tpl_vars['reward_name'].'需要消耗'.$this->_tpl_vars['reward_price'].$this->_tpl_vars['egoldname'].'）</div>
        <div class="mtb10 f14 c333">打赏数量：<input type="number" name="num" class="b bcddd p10 br0" value="1" /></div>
        <div class="mtb10 f14 c333">打赏赠言：</div>
        <div><textarea name="" id="" cols="30" rows="5" class="b bcddd p10 wp100 bsi f14" placeholder="支持作者文思泉涌，妙笔生花。"></textarea></div>
        <div class="mt20">
            <a href="javascript:" class="db lh35 h35 cfff f14 tc br3" style="background: #d2a05f;" onclick="document.getElementById(\'rewardform\').submit()">立即打赏</a>
        </div>
            <input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '">
            <input type="hidden" name="item" id="current3" value="'.$this->_tpl_vars['reward_id'].'"/>
        </form>
    </div>
</div>';
?>