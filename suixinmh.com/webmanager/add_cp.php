<?php
include_once("db.php");
include("../configs/article/option.php");
if (!$_SESSION['jieqiAdminLogin']) {
  $host=$_SERVER['HTTP_HOST'];
  $script=$_SERVER['SCRIPT_NAME'];
  $url=urlencode("http://$host/$script");
  header("location:/admin/?controller=login&jumpurl=$url");
  exit();
}





if ($_POST['action']) {
    $cpid=trim($_POST['cpid']);
    $username=trim($_POST['username']);
    $passwd=$_POST['password'];
    if (!$cpid) {
        $errmsg="请选择CP";
    }
    elseif (!$username) {
        $errmsg="请输入登陆账号";
    }
    elseif (!$passwd) {
        $errmsg = "请输入密码";
    }
    else {

        $password = md5($username . $passwd);
        $d = query("select id from jieqi_cp_users where username='$username'");
        if ($d) {
            $errmsg = $username . " 已经存在";
        }
        else {
            $cpname = $jieqiOption['article']['firstflag']['items'][$cpid];
            $sql = "insert into jieqi_cp_users (cpid,cpname,username,passwd) values('$cpid','$cpname','$username','$password')";
            query($sql);
            $errmsg="";
            $succmsg="添加账户成功";
        }
    }
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
<form action="<?=$_SERVER['PHP_SELF']?>" method="post" >
    <input type="hidden" name="action" value="1">
  <div class="am-u-sm-12">
      <? if ($errmsg) {
          echo "<font color=#FF0000>$errmsg</font>";
      }
      if ($succmsg) {
          echo $succmsg;
      }

      ?>
  <table width="95%" border="1" cellpadding="0" cellspacing="0">
  <tbody>
            <tr>
              <th width="20%" height="29" scope="col"><strong>cpid</strong></th>
              <th width="80%" scope="col">
                <select name="cpid" id="cpid">
                <? 
				foreach($jieqiOption['article']['firstflag']['items'] as $cpid=>$cpname) {
				?>
                  <option value="<?=$cpid?>"><?=$cpname?></option>
				<?
				}
				?>
                </select></th>
              </tr>

            <tr>
              <td height="28">cp登陆账号</td>
              <td>
                <input type="text" name="username" id="username"></td>
            </tr>
            <tr>
              <td height="28">cp登陆密码</td>
              <td>
                <input type="password" name="password" id="password"></td>
            </tr>
            <tr>
              <td height="28">&nbsp;</td>
              <td><input type="submit" name="button" id="button" value="添加"></td>
              </tr>

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
