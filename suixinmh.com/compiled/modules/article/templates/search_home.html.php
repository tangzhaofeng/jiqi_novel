<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
<title>搜书,搜小说,找小说,小说搜索-书海搜书网</title>
<meta name="keywords" content="小说,小说网,玄幻小说,穿越小说,都市小说,网游小说,武侠小说">
<meta name="description" content="书海小说网为最具影响力的原创小说门户,也是最有潜力的网络小说网站,书海小说网每天拥有海量小说更新,包括玄幻小说,穿越小说,都市小说,网游小说,武侠小说,修真小说,恐怖小说,军事小说,历史小说等各类热门小说在线阅读,我们致力于汇集八方作者,共享文学盛事,做最好看的网络小说网站。">
<link href="'.$this->_tpl_vars['jieqi_themeurl'].'style/search.css" type="text/css" rel="stylesheet" />
<SCRIPT src="'.$this->_tpl_vars['jieqi_themeurl'].'js/ScrollPic.js" type=text/javascript></SCRIPT>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/scripts/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/scripts/page.js"></script>
</head>

<body>
<div class="se_top"><!--顶部开始-->
 <div class="topr_txt">';
if($this->_tpl_vars['jieqi_userid'] == 0){
echo '<a href="'.geturl('system','login').'">登录</a>|<a href="'.geturl('system','register').'">注册</a>|<a href="'.geturl('system','getpass').'">找回密码</a>|';
}else{
echo '
                欢迎您<B>'.$this->_tpl_vars['jieqi_username'].'</B>&nbsp;<a href="'.geturl('system','userhub','SYS=method=usereditView').'" target="_top">查看资料</a><a href="'.geturl('article','article','SYS=method=bcView').'" target="_top">我的书架</a>';
if($this->_tpl_vars['jieqi_newmessage'] == 0){
echo '<a href="'.geturl('system','userhub','SYS=method=inbox').'"  target="_top">查看短信</a>';
}else{
echo ' | <a href="'.geturl('system','userhub','SYS=method=inbox').'" target="_top" class="mue1  hottext">您有短信</a>';
}
echo '<a href="'.geturl('system','userhub','SYS=method=logout').'" target="_self">退出登陆</a>';
}
echo '<a href="/help" target="_blank">帮助中心</a></div>
</div><!--顶部结束-->

<div class="se_content"><!--中间开始-->
 <div class="logo_mid"><span>logo</span></div>
 <div class="se_cont"><!--se_cont begin-->
  <ul class="se_list"><!--se_list begin-->
   <li class="se_nav">
    <a href="'.$this->_tpl_vars['jieqi_local_url'].'/" target="_blank" title="书海首页">书海首页</a>
    <a href="/shuku" target="_blank" title="书库">书库</a>
    <a href="'.geturl('article','top').'" target="_blank" title="排行榜">排行榜</a>
    <a href="/fuli/" target="_blank" title="作者福利">作者福利</a>
    <a href="'.$this->_tpl_vars['jieqi_local_url'].'/news/" target="_blank" title="资讯">资讯</a>
    <a href="'.geturl('pay','home').'" target="_blank" title="充值">充值</a>
   </li>
   <form name="articlesearch" method="get" action="/search" ajaxform="true" onsubmit="return false;">
   <li class="se_search">
    <input type="text" class="input_search"  id="J_search_suggest" data-placeholder="键入书名、作者名开始搜索" name=\'searchkey\' /><input name="dosubmit" type="submit" class="btn_search" value="快捷搜索" />
   </li>
</form>

   <li class="sort_hotw">
    <div class="tt_l">小说分类：</div>
    <div class="word_r">
     <a href="'.geturl('article','channel','class=xuanhuan').'" target="_blank" class="font_green">玄幻魔法</a>
     <a href="'.geturl('article','channel','class=dushi').'" target="_blank" class="font_green">都市言情</a>
     <a href="'.geturl('article','channel','class=xiuzhen').'" target="_blank" class="font_green">修真修仙</a>
     <a href="'.geturl('article','channel','class=wangyou').'" target="_blank" class="font_green">网游虚拟</a>
     <a href="'.geturl('article','channel','class=wuxia').'" target="_blank" class="font_green">武侠仙侠</a>
     <a href="'.geturl('article','channel','class=lishi').'" target="_blank" class="font_green">历史军事</a>
     <a href="'.geturl('article','channel','class=kongbu').'" target="_blank" class="font_green">恐怖悬疑</a>
     <a href="/shuku" target="_blank" class="font_green">更多&gt;&gt;</a>
    </div>
   </li>
   <li class="sort_hotw">
    <div class="tt_l">一周热词：</div>
    <div class="word_r">
     <a href="/book/24668.htm" target="_blank" class="font_green" title="超级嬴家">超级嬴家</a>
     <a href="/book/4033.htm" target="_blank" class="font_green" title="混世贴身高手">混世贴身高手</a>
     <a href="/book/12622.htm" target="_blank" class="font_green" title="极品邪医">极品邪医</a>
     <a href="/book/13689.htm" target="_blank" class="font_green" title="极品邪医">玩转娱乐圈</a>
     <a href="/book/15718.htm" target="_blank" class="font_green" title="超级手套">超级手套</a>
     <a href="/book/9531.htm" target="_blank" class="font_green" title="超牛都市兵神">超牛都市兵神</a>
    </div>
   </li>
  </ul><!--se_list end-->
<!--滚动图片 start-->
<div class=combook><!--combook begin-->
 <div class=blk_29>
  <div class=LeftBotton id=LeftArr></div>
  <div class=Cont id=ISL_Cont_1><!-- 图片列表 begin -->
   <div class=box>
    <a href="/book/24655.htm" target="_blank"><img alt="至尊和尚" title="至尊和尚" src="http://www.shuhai.com/files/article/image/24/24655/24655s.jpg" border=0></a>
    <p><a href="/book/24655.htm" target="_blank" title="至尊和尚">至尊和尚</a></p>
   </div>
   <div class=box>
    <a href="/book/1162.htm" target="_blank"><img alt="黑道学生6:王者重临" title="黑道学生6:王者重临" src="http://www.shuhai.com/files/article/image/1/1162/1162s.jpg" border=0></a>
    <p><a href="/book/1162.htm" target="_blank" title="黑道学生6:王者重临">黑道学生6:王者重</a></p>
   </div>
   <div class=box>
    <a href="/book/9815.htm" target="_blank"><img alt="锦绣洛神" title="锦绣洛神" src="http://www.shuhai.com/files/article/image/9/9815/9815s.jpg" border=0></a> 
    <p><a href="/book/9815.htm" target="_blank" title="锦绣洛神">锦绣洛神</a></p>
   </div>
   <div class=box>
    <a href="/book/1361.htm" target="_blank"><img alt="腹黑王爷的绝色弃妃" title="腹黑王爷的绝色弃妃" src="http://www.shuhai.com/files/article/image/1/1361/1361s.jpg" border=0></a>
    <p><a href="/book/1361.htm" target="_blank" title="腹黑王爷的绝色弃妃">腹黑王爷的绝色弃</a></p>
   </div>
   <div class=box>
    <a  href="/book/24660.htm" target="_blank"><img alt="杉杉来吃" title="杉杉来吃" src="http://www.shuhai.com/files/article/image/24/24660/24660s.jpg" border=0></a> 
    <p><a href="/book/24660.htm" target="_blank" title="杉杉来吃">杉杉来吃</a></p>
   </div>
   <div class=box>
    <a  href="/book/2120.htm" target="_blank"><img alt="女法医:骨头收藏家" title="女法医:骨头收藏家" src="http://www.shuhai.com/files/article/image/2/2120/2120s.jpg" border=0></a> 
    <p><a href="/book/2120.htm" target="_blank" title="女法医:骨头收藏家">女法医:骨头收藏</a></p>
   </div>
   <div class=box>
    <a  href="/book/8690.htm" target="_blank"><img alt="重生都市做医圣" title="重生都市做医圣" src="http://www.shuhai.com/files/article/image/8/8690/8690s.jpg" border=0></a> 
    <p><a href="/book/8690.htm" target="_blank" title="重生都市做医圣">重生都市做医圣</a></p>
   </div>
   <div class=box>
    <a  href="/book/24597.htm" target="_blank"><img alt="红颜倾城:景瑜皇后传" title="红颜倾城:景瑜皇后传" src="http://www.shuhai.com/files/article/image/24/24597/24597s.jpg" border=0></a> 
    <p><a href="/book/24597.htm" target="_blank" title="红颜倾城:景瑜皇后传">红颜倾城:景瑜皇</a></p>
   </div>
  </div><!-- 图片列表 end -->
  
  <div class=RightBotton id=RightArr></div>
 </div>


<SCRIPT language=javascript type=text/javascript>
		<!--//--><![CDATA[//><!--
		var scrollPic_02 = new ScrollPic();
		scrollPic_02.scrollContId   = "ISL_Cont_1"; //内容容器ID
		scrollPic_02.arrLeftId      = "LeftArr";//左箭头ID
		scrollPic_02.arrRightId     = "RightArr"; //右箭头ID

		scrollPic_02.frameWidth     = 730;//显示框宽度
		scrollPic_02.pageWidth      = 152; //翻页宽度

		scrollPic_02.speed          = 10; //移动速度(单位毫秒，越小越快)
		scrollPic_02.space          = 10; //每次移动像素(单位px，越大越快)
		scrollPic_02.autoPlay       = false; //自动播放
		scrollPic_02.autoPlayTime   = 3; //自动播放间隔时间(秒)

		scrollPic_02.initialize(); //初始化
							
		//--><!]]>
</SCRIPT>
  </div><!--combook end--><!--滚动图片 end-->
 </div><!--se_cont end-->
</div><!--中间结束-->

<div class="se_footer"><!--se_foot begin-->
 <div class="sefoot_nav">
  <a href="'.geturl('system','about','SYS=method=index').'" target="_blank">关于我们</a>|<a href="'.geturl('system','about','SYS=method=business').'" target="_blank">服务条款</a>|<a href="'.geturl('system','about','SYS=method=partner').'" target="_blank">版权声明</a>|<a href="'.geturl('system','userhub','SYS=method=uservip').'" target="_blank" class="f_red3">VIP会员申请</a>|<a href="'.geturl('system','userhub').'" target="_blank" class="f_red3">作者投稿</a>|<a href="'.geturl('pay','home').'" target="_blank" class="f_red3">支付中心</a>|<a href="'.geturl('system','about','SYS=method=accession').'" target="_blank">加入书海</a>|<a href="'.geturl('system','about','SYS=method=contact').'" target="_blank">联系我们</a>|<a href="'.geturl('system','about','SYS=method=friendly').'" target="_blank">友情链接</a>
 </div>
 <p class="footer_info">书海小说网为国内最具潜力的新锐原创小说网，致力于构建全球最大原创网络文学平台<br />
All Right Reserved Copryright 2011 @ www.shuhai.com 书海小说网版权所有 陕ICP备10012047号-1</p>
</div><!--se_foot end-->

</body>
</html>
';
?>