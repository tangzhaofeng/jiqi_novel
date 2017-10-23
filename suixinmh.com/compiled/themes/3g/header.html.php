<?php
echo '
<div class="clearfix h55 bgcPink">
       <div class="dib fl wp30 pl10 bsi h55" style="display:flex;justify-content:center;flex-direction:column;"><a href="/" target="_self"><img src="'.$this->_tpl_vars['jieqi_themeurl'].'images/logo.png" alt="" style="width:100px" /></a></div>
    <div class="fr wp60">
        <ul class="clearfix lh55 h55 tc">
            <li class="wp20 fl">
                <a href="/" class="';
if($this->_tpl_vars['pageid']=='home'){
echo 'cYellow';
}else{
echo 'cfff';
}
echo ' f16 db">首页</a>
            </li>
            <li class="wp20 fl">
                <a href="'.geturl('3g','shuku','SYS=siteid=100&sort=0&size=0&fullflag=0&operate=0&free=0&page=1').'" class="';
if($this->_tpl_vars['pageid']=='shuku'){
echo 'cYellow';
}else{
echo 'cfff';
}
echo ' f16 db">书库</a>
            </li>
            <li class="wp20 fl">
                <a href="'.geturl('3g','top').'" class="';
if($this->_tpl_vars['pageid']=='top'){
echo 'cYellow';
}else{
echo 'cfff';
}
echo ' f16 db">排行</a>
            </li>
            <li class="wp20 fl">
                <a href="'.geturl('3g','pay').'" class="';
if($this->_tpl_vars['pageid']=='pay'){
echo 'cYellow';
}else{
echo 'cfff';
}
echo ' f16 db">充值</a>
            </li>
        </ul>
    </div>
</div>
<div class="tr lh40 bgcWhiteSmoke bb bcddd pr15">
    ';
if($this->_tpl_vars['jieqi_username'] != ''){
echo '
    <a href="'.geturl('3g','userhub').'" class="pr mr15">个人中心【'.$this->_tpl_vars['_USER']['username'].'】</a>
    <a href="'.geturl('3g','article','SYS=method=bcView').'">阅读记录</a>
    ';
}else{
echo '
    <a href="'.geturl('3g','login').'" class="pr mr15">登陆</a> <a href="'.geturl('3g','register').'" class="pr mr15">注册</a>
    ';
}
echo '
</div>
<!-- <b class="fwn pa top0 f16 right-6 cRed lh0">●</b> -->';
?>