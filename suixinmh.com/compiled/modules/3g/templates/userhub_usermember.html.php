<?php
echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=" />
<title>会员专区</title>
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
<meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
<meta name="description" content="'.$this->_tpl_vars['meta_description'].'" />
<meta name="author" content="'.$this->_tpl_vars['meta_author'].'" />
<meta name="copyright" content="'.$this->_tpl_vars['meta_copyright'].'" />
<link href="'.$this->_tpl_vars['jieqi_themeurl'].'style/member.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/extend.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'fonts/iconfont.css">
<script src="'.$this->_tpl_vars['jieqi_themeurl'].'js/jquery.min.js"></script>
</head>

<body class="padbar">
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
<div class="wrap2 clearfix">
  <ul class="tab2 clearfix" id="tabs">
    <li class="col-x-6';
if($this->_tpl_vars['_REQUEST']['method']=='uservip'){
echo ' thistab';
}
echo '"><a href="javascript:;">VIP等级</a></li>
    <li class="col-x-6';
if($this->_tpl_vars['_REQUEST']['method']=='usermember'){
echo ' thistab';
}
echo '"><a href="javascript:;">会员积分</a></li>
  </ul>
  <div id="tab_conbox">
   <div';
if($this->_tpl_vars['_REQUEST']['method']=='usermember'){
echo ' style="display:none;"';
}
echo '>
    <p class="mylev"><span class="iv iv0"></span> 您是'.$this->_tpl_vars['_USER']['vipgrade'].'级会员，当前VIP成长值'.$this->_tpl_vars['_USER']['vip'].'分</p>    
    
    <span class="txtabox m12">
     <h3>VIP成长体系</h3>
     <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablbg">
  <tr>
    <th width="40%" align="left" valign="middle" scope="col">VIP等级</th>
    <th width="60%" align="left" valign="middle" scope="col">成长值要求</th>
  </tr>
  <tr>
    <td align="left" valign="middle">VIP0</td>
    <td align="left" valign="middle">0</td>
  </tr>
  <tr>
    <td align="left" valign="middle">VIP1</td>
    <td align="left" valign="middle">100</td>
  </tr>
  <tr>
    <td align="left" valign="middle">VIP2</td>
    <td align="left" valign="middle">5000</td>
  </tr>
  <tr>
    <td align="left" valign="middle">VIP3</td>
    <td align="left" valign="middle">20000</td>
  </tr>
  <tr>
    <td align="left" valign="middle">VIP4</td>
    <td align="left" valign="middle">40000</td>
  </tr>
  <tr>
    <td align="left" valign="middle">VIP5</td>
    <td align="left" valign="middle">70000</td>
  </tr>
  <tr>
    <td align="left" valign="middle">VIP6</td>
    <td align="left" valign="middle">100000</td>
  </tr>
</table>
     <p class="dwntxt clearfix">每充值1元获得100点VIP成长值<a href="/pay/" class="btn0 btnorg">充值</a></p>
    </span><!--txtbox end-->
    
    <span class="txtabox m12">
     <h3>等级特权</h3>
     <ul>
      <li>(1)付费章节订阅折扣，等级越高折扣越多，最高可享7.5折；</li>
      <li>(2)会员积分加速，等级越高加速倍数越高；</li>
      <li>(3)专属图标，不同等级VIP会员，拥有各自专属炫酷图标；</li>
      <li>(4)每月月票赠送，等级越高获赠月票越多；</li>
      <li>(5)每日额外赠送推荐票，等级越高获赠推荐票越多；</li>
      <li>(6)VIP1级及以上等级会员可以参与作品月票评选。</li>
     </ul>
     <p class="dwntxt clearfix"></p>
    </span><!--txtbox end-->
   </div><!--firstdiv end-->
   
   <div';
if($this->_tpl_vars['_REQUEST']['method']=='uservip'){
echo ' style="display:none;"';
}
echo '>    
    <p class="mylev"><span class="is is1"></span> 您当前用户级别为'.$this->_tpl_vars['_USER']['honor'].'级，会员积分'.$this->_tpl_vars['_USER']['score'].'分</p>   
    
    <span class="txtabox m12">
     <h3>会员积分体系</h3>
     <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablbg">
  <tr>
    <th width="30%" scope="col">用户等级</th>
    <th width="30%" scope="col">身份</th>
    <th width="40%" scope="col">积分要求</th>
  </tr>
  <tr>
    <td>1级</td>
    <td>列兵</td>
    <td>0</td>
  </tr>
  <tr>
    <td>2级</td>
    <td>上等兵</td>
    <td>200</td>
  </tr>
  <tr>
    <td>3级</td>
    <td>下士</td>
    <td>500</td>
  </tr>
  <tr>
    <td>4级</td>
    <td>中士</td>
    <td>1000</td>
  </tr>
  <tr>
    <td>5级</td>
    <td>上士</td>
    <td>2000</td>
  </tr>
  <tr>
    <td>6级</td>
    <td>准尉</td>
    <td>3000</td>
  </tr>
  <tr>
    <td>7级</td>
    <td>少尉</td>
    <td>5000</td>
  </tr>
  <tr>
    <td>8级</td>
    <td>中厨</td>
    <td>7000</td>
  </tr>
  <tr>
    <td>9级</td>
    <td>上尉</td>
    <td>9000</td>
  </tr>
  <tr>
    <td>10级</td>
    <td>大尉</td>
    <td>11000</td>
  </tr>
  <tr>
    <td>11级</td>
    <td>少校</td>
    <td>15000</td>
  </tr>
  <tr>
    <td>12级</td>
    <td>中校</td>
    <td>20000</td>
  </tr>
  <tr>
    <td>13级</td>
    <td>上校</td>
    <td>25000</td>
  </tr>
  <tr>
    <td>14级</td>
    <td>大校</td>
    <td>30000</td>
  </tr>
  <tr>
    <td>15级</td>
    <td>少将</td>
    <td>40000</td>
  </tr>
  <tr>
    <td>16级</td>
    <td>中将</td>
    <td>50000</td>
  </tr>
  <tr>
    <td>17级</td>
    <td>上将</td>
    <td>60000</td>
  </tr>
  <tr>
    <td>18级</td>
    <td>大将</td>
    <td>70000</td>
  </tr>
  <tr>
    <td>19级</td>
    <td>元帅</td>
    <td>80000</td>
  </tr>
  <tr>
    <td>20级</td>
    <td>大元帅</td>
    <td>100000</td>
  </tr>
</table>
    
    <span class="txtabox m12 mx0">
     <h3>积分获得途径</h3>
     <ul>
      <li>(1)注册：新用户注册，赠送10分;</li>
      <li>(2)登录：5分/天；连续登录3日以上，可获得更多积分;</li>
      <li>(3)收藏：2分/天，单日次数不限;</li>
      <li>(4)打赏：打赏书海币*20%，单日次数不限;</li>
      <li>(5)投推荐票：2分/张，不设上限；</li>
      <li>(6)发表书评：主题书评5分/次，书评回复2分/次，精华书评20分/次，不设上限</li>
      <li>(7)充值：充值金额*50，不设上限</li>
      <li>(8)购买VIP章节：章节数*2，不设上限</li>
      <li>(9)投月票：5分/张，不设上限</li>
     </ul>
    </span><!--txtbox end-->

   </div><!--seconddiv end-->
  
  
  </div> <!--tab_conbox end--> 
</div>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/bottom.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
<script>
$(function(){
	$("#tabs li").click(function(){
		var activeindex = $("#tabs").find("li").index(this);//alert(activeindex);
		$(this).addClass("thistab").siblings("li").removeClass("thistab");
		$("#tab_conbox").children().eq(activeindex).show().siblings().hide();
	});
});
</script>
</body>
</html>
';
?>