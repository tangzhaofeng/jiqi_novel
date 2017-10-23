<?php
echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
<title>帮助中心</title>
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
<meta http-equiv="Cache-Control" content="no-transform " /> 
<meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
<meta name="description" content="'.$this->_tpl_vars['meta_description'].'" />
<meta name="author" content="'.$this->_tpl_vars['meta_author'].'" />
<meta name="copyright" content="'.$this->_tpl_vars['meta_copyright'].'" />
<link href="'.$this->_tpl_vars['jieqi_themeurl'].'style/help_center.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/extend.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'fonts/iconfont.css">
</head>

<body>
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
<div class="content1">
	<h1 id="tabs1"><a href="'.geturl('3g','help','SYS=method=main&helpno=1001').'" ';
if(!isset($this->_tpl_vars['_REQUEST']['helpno'])==1 ||$this->_tpl_vars['_REQUEST']['helpno']<='2000'){
echo ' class="thistab"';
}
echo '>常见问题</a><a href="'.geturl('3g','help','SYS=method=main&helpno=2101').'" ';
if($this->_tpl_vars['_REQUEST']['helpno']>='2001'){
echo ' class="thistab"';
}
echo '>读者帮助</a></h1>
	<div class="main" id="tab_conbox1">
    	<ul class="cjwt tab1" ';
if($this->_tpl_vars['helpno']>=2001){
echo ' style="display:none;"';
}
echo $this->_tpl_vars['helpno'].'>
        	<li>
            	<div class="maintit ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1001){
echo ' maintithover';
}
echo '"><span>1、'.$this->_tpl_vars['jieqi_sitename'].'手机站账号可以通用吗？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=1001').'" ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1001){
echo ' class="ahover"';
}
echo '></a></div>
                <div class="maincon" ';
if($this->_tpl_vars['helpno']!=1001){
echo ' style=" display:none;"';
}
echo '>
                	可以。'.$this->_tpl_vars['jieqi_sitename'].'主站和手机站已经实现了账户互通。如果您已在主站注册账号，则无需在手机站再次注册，充值、订阅、收藏完全同步。
                </div>
            </li>
            <li>
            	<div class="maintit ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1002){
echo ' maintithover';
}
echo '"><span>2、忘记密码怎么办？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=1002').'" ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1002){
echo ' class="ahover"';
}
echo '></a></div>
                <div class="maincon" ';
if($this->_tpl_vars['helpno']!=1002){
echo ' style=" display:none;"';
}
echo '>
				    请联系本站客服修改密码。客服QQ：1964668087。
                	<!--在登录界面点击【忘记密码？】，之后将跳转至找回密码页面。目前提供通过注册邮箱找回密码的服务。-->
                </div>
            </li>
            <li>
            	<div class="maintit ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1003){
echo ' maintithover';
}
echo '"><span>3、如何修改密码？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=1003').'" ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1003){
echo ' class="ahover"';
}
echo '></a></div>
                <div class="maincon" ';
if($this->_tpl_vars['helpno']!=1003){
echo ' style=" display:none;"';
}
echo '>
                	登录后，在网站首页点击您的昵称进入个人中心，之后进入账户页面，便会看到修改密码的选项。
                </div>
            </li>
            <li>
            	<div class="maintit ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1004){
echo ' maintithover';
}
echo '"><span>4、如何查看我收藏的书籍？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=1004').'" ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1004){
echo ' class="ahover"';
}
echo '></a></div>
                <div class="maincon" ';
if($this->_tpl_vars['helpno']!=1004){
echo ' style=" display:none;"';
}
echo '>
                	登录后，点击网站首页右上角的阅读历史图标，点击【进入书架】按钮。或者进入个人中心页面，点击【书架】，查看您的收藏。
                </div>
            </li>
            <li>
            	<div class="maintit ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1005){
echo ' maintithover';
}
echo '"><span>5、在手机上如何充值？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=1005').'" ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1005){
echo ' class="ahover"';
}
echo '></a></div>
                <div class="maincon" ';
if($this->_tpl_vars['helpno']!=1005){
echo ' style=" display:none;"';
}
echo '>
                	'.$this->_tpl_vars['jieqi_sitename'].'手机站目前开通了手机短信充值功能，步骤如下：<br />（1）在首页导航中点击“充值”；<br />（2）选择充值手机类型；<br />（3）选择充值金额；<br />（4）输入充值手机号，点击“提交”。<br />更多快捷充值服务敬请期待。
                </div>
            </li>
            <li>
            	<div class="maintit ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1006){
echo ' maintithover';
}
echo '"><span>6、充值后账户余额数量没变？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=1006').'" ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1006){
echo ' class="ahover"';
}
echo '></a></div>
                <div class="maincon" ';
if(helpno!=1006){
echo ' style=" display:none;"';
}
echo '>
                	解决办法：<br />（1）刷新当前页面；<br />（2）退出后重新登录；<br />（3）如果以上2种方法无效，那么可能没有充值成功，请联系本站客服。客服QQ：1964668087。
                </div>
            </li>
            <li>
            	<div class="maintit ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1007){
echo ' maintithover';
}
echo '"><span>7、如何查看财务记录？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=1007').'" ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1007){
echo ' class="ahover"';
}
echo '></a></div>
                <div class="maincon" ';
if($this->_tpl_vars['helpno']!=1007){
echo ' style=" display:none;"';
}
echo '>
                	登录后，进入个人中心页面，点击【财务记录】，查看您的充值记录和消费记录。
                </div>
            </li>
            <li>
            	<div class="maintit ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1008){
echo ' maintithover';
}
echo '"><span>8、为什么我无法阅读VIP章节？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=1008').'" ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1008){
echo ' class="ahover"';
}
echo '></a></div>
                <div class="maincon" ';
if($this->_tpl_vars['helpno']!=1008){
echo ' style=" display:none;"';
}
echo '>
                	VIP章节需要订阅后才能阅读。充值后，选择您要看的VIP章节并按提示支付'.$this->_tpl_vars['egoldname'].'，随后即可阅读。
                </div>
            </li>
            <li>
            	<div class="maintit ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1009){
echo ' maintithover';
}
echo '"><span>9、为什么要升级成VIP会员？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=1009').'" ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1009){
echo ' class="ahover"';
}
echo '></a></div>
                <div class="maincon" ';
if($this->_tpl_vars['helpno']!=1009){
echo ' style=" display:none;"';
}
echo '>
                	成为VIP会员后，您将享有如下特权：<br />（1）订阅VIP章节；<br />（2）给您喜欢的作品投月票；<br />（3）拥有VIP会员专属图标；<br />（4）其他特权：会员积分加速；订阅折扣；每日推荐票赠送。
                </div>
            </li>
            <li>
            	<div class="maintit ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1010){
echo ' maintithover';
}
echo '"><span>10、如何成为VIP会员？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=1010').'" ';
if(isset($this->_tpl_vars['_REQUEST']['helpno'])==1 && $this->_tpl_vars['helpno']==1010){
echo ' class="ahover"';
}
echo '></a></div>
                <div class="maincon" ';
if($this->_tpl_vars['helpno']!=1010){
echo ' style=" display:none;"';
}
echo '>
                	通过累积VIP成长值来提升VIP用户等级，每充值1元可获得100点VIP成长值。成长值满100点，即可升级为V??IP1级会员。
                </div>
            </li>
        </ul>
        <ul class="dzhelp tab1" ';
if($this->_tpl_vars['helpno']<=2000){
echo ' style="display:none;"';
}
echo '>
        	<li>
            	<a href="'.geturl('3g','help','SYS=method=main&helpno=2101').'" class="dzhelptit"><span>';
if($this->_tpl_vars['helpno']>=2101 && $this->_tpl_vars['helpno']<2200){
echo '-';
}else{
echo '+';
}
echo '</span>充值</a>
            	<ul ';
if($this->_tpl_vars['helpno']<2101 || $this->_tpl_vars['helpno']>=2200){
echo ' style="display: none"';
}
echo '>
                	<li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2101){
echo ' maintithover';
}
echo '"><span>1.如何给账户充值？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2101').'" ';
if($this->_tpl_vars['helpno']==2101){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2101){
echo ' style=" display:none;"';
}
echo '>
                            登录之后，在首页导航栏点击【充值】按钮，或者进入个人中心页面点击【充值】。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2102){
echo ' maintithover';
}
echo '"><span>2.充值后不到账怎么办？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2102').'" ';
if($this->_tpl_vars['helpno']==2102){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2102){
echo ' style=" display:none;"';
}
echo '>
                            '.$this->_tpl_vars['egoldname'].'暂未到账可能是以下原因造成的：<br />（1）由于充值人数较多，所以充值通道拥挤，充值成功速度就会稍慢；<br />（2）数据提交上去之后，系统要对数据进行核对才可以给出成功或者失败的结果。<br />如果30分钟内没有到账，请立即联系本站客服。<br />客服QQ：1964668087
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2103){
echo ' maintithover';
}
echo '"><span>3.充值后账户余额数量没变？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2103').'" ';
if($this->_tpl_vars['helpno']==2103){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2103){
echo ' style=" display:none;"';
}
echo '>
                            解决办法：<br />（1）刷新当前页面；<br />（2）退出后重新登录；<br />（3）如果以上2种方法无效，那么可能没有充值成功，请联系本站客服。<br />客服QQ：1964668087。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2104){
echo ' maintithover';
}
echo '"><span>4.如何查看我的充值记录？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2104').'" ';
if($this->_tpl_vars['helpno']==2104){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2104){
echo ' style=" display:none;"';
}
echo '>
                           登录之后，进入个人中心页面，点击【财务记录】，便会看到您的充值记录。
                        </div>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="'.geturl('3g','help','SYS=method=main&helpno=2201').'" class="dzhelptit"><span>';
if($this->_tpl_vars['helpno']>=2201 && $this->_tpl_vars['helpno']<2300){
echo '-';
}else{
echo '+';
}
echo '</span>订阅</a>
            	<ul ';
if($this->_tpl_vars['helpno']<2201 || $this->_tpl_vars['helpno']>=2300){
echo ' style="display: none"';
}
echo '>
                	<li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2201){
echo ' maintithover';
}
echo '"><span>1.为什么我无法阅读VIP章节？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2201').'" ';
if($this->_tpl_vars['helpno']==2201){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2201){
echo ' style=" display:none;"';
}
echo '>
                            VIP章节需要订阅后才能阅读。充值后，选择您要看的VIP章节并按提示支付'.$this->_tpl_vars['egoldname'].'，随后即可阅读。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2202){
echo ' maintithover';
}
echo '"><span>2.VIP书籍怎么收费的？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2202').'" ';
if($this->_tpl_vars['helpno']==2202){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2202){
echo ' style=" display:none;"';
}
echo '>
                            本站VIP内容按照200字1个'.$this->_tpl_vars['egoldname'].'进行收费,即每200字您需要支付0.01元,越高等级的VIP订阅折扣越大。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2203){
echo ' maintithover';
}
echo '"><span>3.充值后就能免费阅读VIP章节？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2203').'" ';
if($this->_tpl_vars['helpno']==2203){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2203){
echo ' style=" display:none;"';
}
echo '>
                            不是。VIP章节需要您订阅后才能阅读，且按照200字1'.$this->_tpl_vars['egoldname'].'进行计费。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2204){
echo ' maintithover';
}
echo '"><span>4.购买的VIP章节无法阅读？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2204').'" ';
if($this->_tpl_vars['helpno']==2204){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2204){
echo ' style=" display:none;"';
}
echo '>
                            您可能遇到了系统问题，请联系本站客服解决。客服QQ：1964668087。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2205){
echo ' maintithover';
}
echo '"><span>5.VIP阅读需要重新付费吗？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2205').'" ';
if($this->_tpl_vars['helpno']==2205){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2205){
echo ' style=" display:none;"';
}
echo '>
                            不需要。已经购买的VIP章节您可以随时免费阅读。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2206){
echo ' maintithover';
}
echo '"><span>6.如何查看我的订阅记录？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2206').'" ';
if($this->_tpl_vars['helpno']==2206){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2206){
echo ' style=" display:none;"';
}
echo '>
                            登录之后，进入个人中心页面，点击【财务记录】，便会看到您的消费记录。
                        </div>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="'.geturl('3g','help','SYS=method=main&helpno=2301').'" class="dzhelptit"><span>';
if($this->_tpl_vars['helpno']>=2301 && $this->_tpl_vars['helpno']<2400){
echo '-';
}else{
echo '+';
}
echo '</span>月票</a>
            	<ul ';
if($this->_tpl_vars['helpno']<2301 || $this->_tpl_vars['helpno']>=2400){
echo ' style="display: none"';
}
echo '>
                	<li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2301){
echo ' maintithover';
}
echo '"><span>1.什么是月票？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2301').'" ';
if($this->_tpl_vars['helpno']==2301){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2301){
echo ' style=" display:none;"';
}
echo '>
                            月票是VIP会员用户专有的票种，用来评选'.$this->_tpl_vars['jieqi_sitename'].'签约作品。月票的数量可以在个人中心页面查看。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2302){
echo ' maintithover';
}
echo '"><span>2.如何获得月票？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2302').'" ';
if($this->_tpl_vars['helpno']==2302){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2302){
echo ' style=" display:none;"';
}
echo '>
                            目前获得月票的方式有以下两种：<br />（1）VIP会员通过订阅章节消费，达到指定标准后根据VIP等级赠送月票；<br />（2）VIP会员单次打赏作品1000'.$this->_tpl_vars['egoldname'].'，即获得1张消费月票，无上限。<br />注：本月获得的消费月票将于下一个月增加到用户账户中。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2303){
echo ' maintithover';
}
echo '"><span>3.月票使用规则</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2303').'" ';
if($this->_tpl_vars['helpno']==2303){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2303){
echo ' style=" display:none;"';
}
echo '>
                            （1）只能对'.$this->_tpl_vars['jieqi_sitename'].'已签约的作品投月票；<br />（2）投月票时可选择需要投的票数进行投票，目前VIP会员对单本作品每月最多可投2张月票；<br />（3）所有月票（包含保底和消费月票）一律当月有效，过期作废，不可以延期使用。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2304){
echo ' maintithover';
}
echo '"><span>4.如何投月票？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2304').'" ';
if($this->_tpl_vars['helpno']==2304){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2304){
echo ' style=" display:none;"';
}
echo '>
                            进入书籍详情页面，在作品动态栏点击【投月票】按钮，为您喜欢的作品投上一票。
                        </div>
                    </li>
                </ul>
            </li>
            <li>
            	<a href="'.geturl('3g','help','SYS=method=main&helpno=2401').'" class="dzhelptit"><span>';
if($this->_tpl_vars['helpno']>=2401 && $this->_tpl_vars['helpno']<2500){
echo '-';
}else{
echo '+';
}
echo '</span>书券</a>
            	<ul ';
if($this->_tpl_vars['helpno']<2401 || $this->_tpl_vars['helpno']>=2500){
echo ' style="display: none"';
}
echo '>
                	<li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2401){
echo ' maintithover';
}
echo '"><span>1、什么是书券？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2401').'" ';
if($this->_tpl_vars['helpno']==2401){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2401){
echo ' style=" display:none;"';
}
echo '>
                            答：书券是'.$this->_tpl_vars['jieqi_sitename'].'新增的一种虚拟币，与'.$this->_tpl_vars['egoldname'].'等值，可用于VIP章节订阅。
                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2402){
echo ' maintithover';
}
echo '"><span>2、书券的使用范围</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2402').'" ';
if($this->_tpl_vars['helpno']==2402){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2402){
echo ' style=" display:none;"';
}
echo '>
                            答：书券在'.$this->_tpl_vars['jieqi_sitename'].'全站通用（包括'.$this->_tpl_vars['jieqi_sitename'].'、Wap站和手机客户端），但受以下使用限制：<br />（1）书券仅能用于章节订阅，不支持作品打赏等消费功能；<br />（2）使用书券订阅将无法获得订阅月票；<br />（3）使用书券购买VIP章节，将不会获得会员积分。

                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2403){
echo ' maintithover';
}
echo '"><span>3、如何获得书券？</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2403').'" ';
if($this->_tpl_vars['helpno']==2403){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2403){
echo ' style=" display:none;"';
}
echo '>
                            答：目前，'.$this->_tpl_vars['jieqi_sitename'].'用户可以通过以下途径来获得书券：<br />（1）VIP会员升级赠送书券
<br />（2）根据上月的'.$this->_tpl_vars['egoldname'].'消费额度，下一月返还书券
<br />计算公式：本月返还书券数额s=上月'.$this->_tpl_vars['egoldname'].'消费额度m*5%
<br />注：消费包括订阅和打赏。
<br />（3）每月签到奖励书券
<br />累计签到满7天，奖励20书券；累计签到满15天，奖励30书券；累计签到一个月，奖励50书券。
<br />（4）成为'.$this->_tpl_vars['jieqi_sitename'].'签约作者，赠送5000书券
<br />（5）参加特定活动获得书券
<br />'.$this->_tpl_vars['jieqi_sitename'].'会定期举行各种内容丰富的活动，只要参加这些活动，就有机会获得丰厚的书券奖励。

                        </div>
                    </li>
                    <li>
                        <div class="maintit ';
if($this->_tpl_vars['helpno']==2404){
echo ' maintithover';
}
echo '"><span>4、书券的使用方法</span><a href="'.geturl('3g','help','SYS=method=main&helpno=2404').'" ';
if($this->_tpl_vars['helpno']==2404){
echo ' class="ahover"';
}
echo '></a></div>
                        <div class="maincon" ';
if($this->_tpl_vars['helpno']!=2404){
echo ' style=" display:none;"';
}
echo '>
                            答：书券仅可用于VIP章节订阅，规则如下：
<br />（1）订阅前10章VIP章节，默认优先使用书券抵扣支付，若书券余额不足，则差额用'.$this->_tpl_vars['egoldname'].'补齐；
<br />（2）前10章以外的VIP章节，在订阅时，'.$this->_tpl_vars['egoldname'].'和书券按照一定比例扣除；若书券余额不足，则用'.$this->_tpl_vars['egoldname'].'购买。

                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
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
</html>

';
?>