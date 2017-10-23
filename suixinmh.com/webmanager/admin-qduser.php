<?php
include_once("db.php");
check_level(99);
if ($_REQUEST['action']=='del') {
	$delid=$_REQUEST['delid'];
	$sql="delete from jieqi_system_qdadmin where uid=$delid";
	query($sql);	
}
?>
<!doctype html>
<html class="no-js">
<head>
  <meta charset="GBK">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ishufun.net后台管理系统</title>
  <meta name="description" content="这是一个 index 页面">
  <meta name="keywords" content="index">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="assets/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="Amaze UI" />
  <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
  <link rel="stylesheet" href="assets/css/admin.css">
  <style type="text/css">
  body,td,th {
	font-size: 10px;
}
  </style>
  <link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<script src="jQueryAssets/jquery-1.11.1.min.js" type="text/javascript"></script>
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->

<header class="am-topbar admin-header">
  <div class="am-topbar-brand"><strong>Pinshu</strong><small>后台管理</small></div>

  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

    <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
      <li></li>
      <li class="am-dropdown" data-am-dropdown>
      </li>
      <li></li>
    </ul>
  </div>
</header>

<div class="am-cf admin-main">
<?
include("menu.php");
?>

  <!-- content start -->
  <div class="admin-content">
<div class="am-g">


      <div class="am-u-sm-12">
<input type="button" name="button" id="button" value="添加账号" onClick="document.location.href='add_qduser.php'">
<br>
<table width="95%" border="1" cellpadding="0" cellspacing="0">
  <tbody>
            <tr>
              <th height="29" scope="col"><strong>账号</strong></th>
              <th scope="col">权限级别</th>
              <th scope="col">渠道范围</th>
              <th scope="col">&nbsp;</th>
              </tr>
            <?
            $sql="select a.*,b.uname from jieqi_system_qdadmin a ,jieqi_system_users b where a.uid=b.uid";
			$cplist=queryall($sql);
			foreach($cplist as $d) {
	
			?>
            <tr>
              <td height="28"><?=$d['uname']?></td>
              <td><?=$d['level']?>&nbsp;</td>
              <td><?=$d['qd']?>&nbsp;</td>
              <td><a href="admin-qduser.php?action=del&delid=<?=$d['uid']?>" onClick="return confirm('确定删除吗')">删除</a></td>
              </tr>
             <?
			}
			?>
          </tbody>
      </table>
      </div>
    </div>

    <div class="am-g"></div>
  </div>
  <!-- content end -->

</div>

<footer>
  <hr>
  <p class="am-padding-left">&copy; 2014 AllMobilize, Inc. Licensed under MIT license.</p>
</footer>

<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/polyfill/rem.min.js"></script>
<script src="assets/js/polyfill/respond.min.js"></script>
<script src="assets/js/amazeui.legacy.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/amazeui.min.js"></script>
<!--<![endif]-->
<script src="assets/js/app.js"></script>
</body>
</html>
