<?php
echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
    <title>'.$this->_tpl_vars['chapter']['title'].'-'.$this->_tpl_vars['article']['articlename'].'-'.$this->_tpl_vars['jieqi_sitename'].'首发</title>
    <meta name="keywords" content="'.$this->_tpl_vars['article']['articlename'].','.$this->_tpl_vars['article']['articlename'].'在线阅读">
    <meta name="description" content="'.$this->_tpl_vars['chapter']['title'].'由'.$this->_tpl_vars['jieqi_sitename'].'独家抢先发布了,欢迎大家阅读'.$this->_tpl_vars['chapter']['title'].'这一章,'.$this->_tpl_vars['article']['articlename'].'首发'.$this->_tpl_vars['jieqi_sitename'].'www.shuhai.com。">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
    <meta http-equiv="Cache-Control" content="no-transform " />
    <meta name="copyright" content="" />
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'fonts/iconfont.css">
    <style>
        .content{
        width: 95%;
        margin: 10px auto;
        height: auto;
        background: #fff;
        overflow: hidden;
        }
        .content p.title {
        width: 95%;
        margin: 0 2.5%;
        height: 40px;
        border-bottom: 1px solid #c5c5c5;
        line-height: 40px;
        font-size: 18px;
        color: #2e2e2e;
        }
        .content p.chapter {
            line-height: 20px;
            font-size: 17px;
            color: #df3048;
            margin-top: 10px;
            margin-left: 2.5%;
        }
        .content p.name {
            line-height: 15px;
            font-size: 14px;
            margin-left: 2.5%;
            margin-top: 5px;
            color: #414141;
        }
        .content .details {
            width: 89%;
            height: auto;
            margin: 10px 2.5%;
            border: 1px solid #cecece;
            background: #f7f7f7;
            padding: 3%;
        }
        .content .details p.info {
            font-size: 14px;
            color: #3e3e3e;
            line-height: 30px;
        }
        .content .details p.yue {
             clear: both;
        }
        .content .details span.vip_icon {
            display: block;
            width: 53px;
            height: 15px;
            margin-top: 7px;
            margin-left: 2.5%;
        }
        .content a.pay{width: 46%;height: auto;background: #ff6666;display: inline-block;margin-left: 2.5%;line-height: 40px;color: #fff;text-align: center;border-radius: 5px;margin-bottom: 10px;float: left;}
        .content a.pay1{width: 92%;height: auto;background: #ff6666;display: inline-block;margin-left: 2.5%;line-height: 40px;color: #fff;text-align: center;border-radius: 5px;margin-bottom: 10px;float: left;}
        .content a.recharge{width: 46%;height: auto;background: #df3048;display: inline-block;margin-right: 2.5%;line-height: 40px;color: #fff;text-align: center;border-radius: 5px;float: right;}
        </style>
</head>

<body>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '

<div class="content">
    <p class="title">章节订阅信息</p>
    <p class="chapter"><img src="'.$this->_tpl_vars['jieqi_themeurl'].'images/vip.jpg"/>'.$this->_tpl_vars['chapter']['chaptername'].'</p>
    <p class="name">《'.$this->_tpl_vars['article']['articlename'].'》</p>
    <div class="details">
        <p class="info fl">账户：<a href="'.geturl('3g','userhub').'">'.$this->_tpl_vars['_USER']['username'].'</a></p><span class="vip_icon fl v '.$this->_tpl_vars['_USER']['vipphoto'].'"></span>
        <p class="info yue">当前余额：<span class="red">'.$this->_tpl_vars['_USER']['egolds'].'</span>'.$this->_tpl_vars['egoldname'].'/'.$this->_tpl_vars['_USER']['esilvers'].'书券</p>
        <p class="info">章节字数：';
echo (ceil($this->_tpl_vars['chapter']['size']/2)); 
echo '</p>
        <p class="info">本章原价：<span class="red">'.$this->_tpl_vars['chapter']['saleprice'].'</span>';
echo JIEQI_EGOLD_NAME; 
echo '</p>
        <p class="info">折后价格：';
if($this->_tpl_vars['vipgrade']['setting']['dingyuezhekou']>0){
echo $this->_tpl_vars['chapter']['saleprice']*$this->_tpl_vars['vipgrade']['setting']['dingyuezhekou']; 
echo JIEQI_EGOLD_NAME; 
}else{
echo '无折扣';
}
echo '</p>
        <p class="info">（';
if($this->_tpl_vars['vipgrade']['setting']['dingyuezhekou']>0){
echo $this->_tpl_vars['vipgrade']['caption'].'享受';
echo $this->_tpl_vars['vipgrade']['setting']['dingyuezhekou']*10; 
echo '折章节订阅优惠';
}else{
echo $this->_tpl_vars['vipgrade']['caption'].'级不能享受订阅折扣';
}
echo '）</p>
        <!--<p class="info">本次消费使用';
if($this->_tpl_vars['chapter']['shuquan'] == ''){
echo '0';
}else{
echo $this->_tpl_vars['chapter']['shuquan'];
}
echo '书券抵扣支付</p>-->
        ';
if($this->_tpl_vars['_USER']['egolds'] < $this->_tpl_vars['chapter']['saleprice'] && $this->_tpl_vars['_USER']['esilvers'] < $this->_tpl_vars['chapter']['saleprice']){
echo '
        <p class="info">亲，您的账户余额不足订阅哦，充点零钱支持一下吧</p>
        ';
}else{
echo '
        <p class="info">批量订阅：从当前章节起订阅所有后续VIP章节</p>
        ';
}
echo '
    </div>
    ';
if($this->_tpl_vars['_USER']['egolds'] > $this->_tpl_vars['chapter']['saleprice'] || $this->_tpl_vars['_USER']['esilvers'] > $this->_tpl_vars['chapter']['saleprice']){
echo '
    <a href="'.geturl('3g','reader','SYS=method=buychapter&aid='.$this->_tpl_vars['article']['articleid'].'&cid='.$this->_tpl_vars['chapter']['chapterid'].'').'" class="pay">订阅本章</a>
    ';
if($this->_tpl_vars['_USER']['egolds'] > $this->_tpl_vars['chapter']['saleprice']){
echo '
    <a href="'.geturl('3g','reader','SYS=method=xbuychapter&aid='.$this->_tpl_vars['article']['articleid'].'&cid='.$this->_tpl_vars['chapter']['chapterid'].'').'" class="pay">批量订阅</a>
    ';
}
echo '
    ';
}else{
echo '
    <a href="'.geturl('3g','pay').'" class="pay1">充值</a>
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