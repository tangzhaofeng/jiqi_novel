<?php
echo '<div class="footer">
<div class="footer_nav">
<p><a href="'.geturl('system','about','SYS=method=index').'" target="_blank">关于我们</a>|<a href="'.geturl('system','about','SYS=method=business').'" target="_blank">服务条款</a>|<a href="'.geturl('system','about','SYS=method=partner').'" target="_blank">版权声明</a>|<a href="'.geturl('system','userhub','SYS=method=uservip').'" target="_blank" class="f_red3">VIP会员申请</a>|<a href="'.geturl('system','userhub').'" target="_blank" class="f_red3">作者投稿</a>|<a href="'.geturl('pay','home').'" target="_blank" class="f_red3">支付中心</a>|<a href="'.geturl('system','about','SYS=method=contact').'" target="_blank">联系我们</a>|<a href="'.$this->_tpl_vars['jieqi_local_url'].'/link.html" target="_blank">友情链接</a></p></div>
Copyright(C) 2011-2014 ishufun.net All Rights Reserved 版权所有 品书网<br />
品书网版权所有，未经许可不得转载 浙ICP备15006371号<br />
请所有作者发布作品时务必遵守国家互联网信息管理办法规定，我们拒绝任何色情小说，一经发现，即作删除！<br />
本站所收录作品、社区话题、书库评论及本站所做之广告均属其个人行为，与本站立场无关<br />
<div class="website_logo">
 <em class="beianxinxi"><a target="_blank" title="网站备案信息" href="javascript:;">网站备案信息(网警)</a></em>
 <em class="yingyezhizhao"><a target="_blank" title="营业执照报备信息" href="javascript:;">营业执照报备信息(工商)</a></em>
 <em class="shwj_110"><a target="_blank" title="网警网络110" href="javascript:;">网警网络110</a></em>
 <em class="jbzx"><a target="_blank" title="不良信息举报中心" href="javascript:;">不良信息举报中心</a></em>
 <a  key ="53fee642efbfb03413888bae"  logo_size="124x47"  logo_type="realname"  href="http://www.anquan.org" ><script src="http://static.anquan.org/static/outer/js/aq_auth.js"></script></a>
 <!--可信网站图片LOGO安装开始-->
<span class="kxyz"><script src="http://kxlogo.knet.cn/seallogo.dll?sn=e14091661010053930hdkj000000&size=0"></script></span>
<!--可信网站图片LOGO安装结束-->
</div>
<!--书海小说正积极配合国家最新发布的《关于办理侵犯知识产权刑事案件适用法律若干问题的意见》，<br />
采用刑事手段进行严厉打击盗版，目前相关公安机关已经抓获犯罪嫌疑人15名！正告盗版网站立即停止侵权行为！--><div class="pt5 dn"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/js/tj.js"></script><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/scripts/gb.js"></script></div>
';
if($this->_tpl_vars['_REQUEST']['controller']=='channel'){
echo '<div class="ad4"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/2.js"></script></div>';
}
echo '
';
if($this->_tpl_vars['_REQUEST']['controller']=='index'){
echo '<div class="ad4"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/3.js"></script></div>';
}
echo '
';
if($this->_tpl_vars['_REQUEST']['controller']=='reader'){
echo '<div class="ad4"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/4.js"></script></div>';
}
echo '

</div>';
if($this->_tpl_vars['_REQUEST']['controller']!='articleinfo' && $this->_tpl_vars['_REQUEST']['controller']!='index' && $this->_tpl_vars['_REQUEST']['controller']!='reader'){
echo '<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"16","bdCustomStyle":"'.$this->_tpl_vars['jieqi_themeurl'].'style/slide_share.css"},"slide":{"type":"slide","bdImg":"2","bdPos":"right","bdTop":"250"}};with(document)0[(getElementsByTagName(\'head\')[0]||body).appendChild(createElement(\'script\')).src=\'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=\'+~(-new Date()/36e5)];</script>';
}
echo '
';
?>