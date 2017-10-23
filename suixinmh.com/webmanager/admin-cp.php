<?php
include_once("db.php");
if (!$_SESSION['jieqiAdminLogin']) {
  $host=$_SERVER['HTTP_HOST'];
  $script=$_SERVER['SCRIPT_NAME'];
  $url=urlencode("http://$host/$script");
  header("location:/web_admin/?controller=login&jumpurl=$url");
  exit();
}





if ($_POST['action']) {
	$tdate1=$_POST['tdate1'];
	$tdate2=$_POST['tdate2'];
}
else {
	$tdate1=date("Y-m-d");
	$tdate2=$tdate1;
}
$t1=strtotime($tdate1);
$t2=strtotime($tdate2." 23:59:59");

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
<form action="" method="post">
<input type="hidden" name="action" value="1"/>

      <div class="am-u-sm-12">
        <input type="text" value="" name="paydate" id="paydate">
<table width="95%" border="1" cellpadding="0" cellspacing="0">
  <tbody>
            <tr>
              <th height="29" scope="col"><strong>cpid</strong></th>
              <th scope="col">cp名称</th>
              <th scope="col">cp登陆账号</th>
              </tr>
            <?
            $sql="select  * from jieqi_cp_users";
			$cplist=queryall($sql);
			foreach($cplist as $d) {
	
			?>
            <tr>
              <td height="28"><?=$d['cpid']?></td>
              <td><?=$d['cpname']?>&nbsp;</td>
              <td><?=$d['username']?>&nbsp;</td>
              </tr>
             <?
			}
			?>
          </tbody>
      </table>
      </div>
      </form>
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
