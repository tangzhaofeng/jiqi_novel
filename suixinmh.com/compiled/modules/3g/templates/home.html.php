<?php
$this->_tpl_vars['meta_keywords'] = '小说,好看的小说,小说网,小说推荐,免费小说';
echo '
';
$this->_tpl_vars['jieqi_pagetitle'] = "{$this->_tpl_vars['jieqi_sitename']}-免费小说,网络小说,最好看的小说推荐";
echo '
';
$this->_tpl_vars['meta_description'] = "{$this->_tpl_vars['jieqi_sitename']}为最具影响力的原创小说门户,也是最有潜力的网络小说网站,{$this->_tpl_vars['jieqi_sitename']}每天拥有海量免费小说更新,包括玄幻小说,穿越小说,都市小说,网游小说,武侠小说,修真小说,恐怖小说,军事小说,历史小说等各类热门小说在线阅读,我们致力于汇集八方作者,共享文学盛事,将好看的小说推荐给读者,做最好看的网络小说网站。";
echo '
<style>
    ul img{ 

            width:90px!important;
            height:120px!important;;
     }
</style>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/search.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
<div class="mt10 bgcfff">
            
    <div class="mlf10 ptb10 f16" style="color: #1fb3b6;">热门推荐</div>
    '.jieqi_geturl('system', 'tags', array('id'=>363, 'name'=>'%5B%D0%C2%B0%E6WAP%5D%5B%CA%D7%D2%B3%5D%5B%C8%C8%C3%C5%CD%C6%BC%F6%5D')).'
    <div class="ptb10 bt bcddd plf10">
        '.jieqi_geturl('system', 'tags', array('id'=>364, 'name'=>'%5B%D0%C2%B0%E6WAP%5D%5B%CA%D7%D2%B3%5D%5B%C8%C8%C3%C5%CD%C6%BC%F6%CE%C4%D7%D6%5D')).'
    </div>
</div>
<div class="mt10 bgcfff">
    <h1 class="mlf10 ptb10 f16" style="color: #1fb3b6;" >都市婚姻</h1>
    '.jieqi_geturl('system', 'tags', array('id'=>365, 'name'=>'%5B%D0%C2%B0%E6WAP%5D%5B%CA%D7%D2%B3%5D%5B%B6%BC%CA%D0%BB%E9%D2%F6%5D')).'
    <div class="f14">
        '.jieqi_geturl('system', 'tags', array('id'=>366, 'name'=>'%5B%D0%C2%B0%E6WAP%5D%5B%CA%D7%D2%B3%5D%5B%B6%BC%CA%D0%BB%E9%D2%F6%CE%C4%B1%BE%5D')).'
    </div>
</div>
<div class="mt10  bgcfff">
    <h1 class="mlf10 ptb10 f16" style="color: #1fb3b6;">豪门总裁</h1>
    '.jieqi_geturl('system', 'tags', array('id'=>367, 'name'=>'%5B%D0%C2%B0%E6WAP%5D%5B%CA%D7%D2%B3%5D%5B%BA%C0%C3%C5%D7%DC%B2%C3%5D')).'
    <div class="f14">
        '.jieqi_geturl('system', 'tags', array('id'=>368, 'name'=>'%5B%D0%C2%B0%E6WAP%5D%5B%CA%D7%D2%B3%5D%5B%BA%C0%C3%C5%D7%DC%B2%C3%CE%C4%B1%BE%5D')).'
    </div>
</div>
<div class="mt10  bgcfff">
    <div class="mlf10 ptb10 f16" style="color:#1fb3b6;">大神专区</div>
    '.jieqi_geturl('system', 'tags', array('id'=>369, 'name'=>'%5B%D0%C2%B0%E6WAP%5D%5B%CA%D7%D2%B3%5D%5B%B4%F3%C9%F1%D7%A8%C7%F8%5D')).'
</div>
<div class="mt10 bgcfff">
    <div class="mlf10 ptb10 f16" style="color:#1fb3b6;">完本专区</div>
    '.jieqi_geturl('system', 'tags', array('id'=>370, 'name'=>'%5B%D0%C2%B0%E6WAP%5D%5B%CA%D7%D2%B3%5D%5B%CD%EA%B1%BE%D7%A8%C7%F8%5D')).'
</div>

';
?>