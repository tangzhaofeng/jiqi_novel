<?php
echo '<div class="p10 f14 c888 tc">
    ';
if($this->_tpl_vars['_USER']['uid']>0){
echo '
     <div class="f16 mt10 plf10">账户余额：<span class="cOrange1">'.$this->_tpl_vars['_USER']['egolds'].'</span>'.$this->_tpl_vars['egoldname'].'</div>
    ';
}else{
echo '
    <i class="iconfont f20 cPink mr5 vam">&#xe614;</i>充值请先 <a class="cRed b bcRed br2 pl5 pr5" href="'.geturl('3g','login').'">登陆</a> ，如没有账号 <a class="cBlue b bcBlue br2 pl5 pr5" href="'.geturl('3g','register').'">点击注册</a>
    ';
}
echo '
</div>
<!--余额-->
 ';
?>