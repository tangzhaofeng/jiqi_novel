<?php
function get_cp_total_sale($cpid)
{
    $d=query("select * from jieqi_cp_sale where cpid=$cpid");
    if (!$d) {
        $article_list=queryall("select articleid from jieqi_article_article where firstflag=$cpid");
        foreach($article_list as $a) {
            $alist[]=$a['articleid'];
        }
        $article_id_list=implode(',',$alist);
        $saleprice=0;
        for ($i = 0; $i <= 99; $i++) {
            $table = sprintf("jieqi_article_sale_%02d", $i);

            $sql = "select sum(saleprice) as sale_total from $table where articleid in($article_id_list)";
            $data=query($sql);
            $saleprice += $data['sale_total'];
        }
        return array('sale_total'=>$saleprice,'pay_money'=>0);
    }
    else {
        return array('sale_total'=>$d['sale_total'],'pay_money'=>$d['pay_money']);
    }
}


include_once("db.php");
if (!$_SESSION['jieqiAdminLogin']) {
  $host=$_SERVER['HTTP_HOST'];
  $script=$_SERVER['SCRIPT_NAME'];
  $url=urlencode("http://$host/$script");
  header("location:/web_admin/?controller=login&jumpurl=$url");
  exit();
}





$tdate=$_REQUEST['tdate'];
if (date("Y-m-d",strtotime($tdate)) >= date("Y-m-d",time()-24*3600)) {
    $tdate=date("Y-m-d",time()-24*3600);
}
if (!$tdate) {
    $tdate=date("Y-m-d",time()-24*3600);
}
$from_year=2015;
$from_mon=6;
$y=$from_year;
$m=$from_mon;
$tmonlist=array();
while (true) {
    $tmon=sprintf("%04d%02d",$y,$m);
    if ($tmon > date("Ym")) break;
    $tmonlist[]=$tmon;
    if ($m==12) {
        $m=1;$y++;
    }
    else {
        $m++;
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
    <script language="JavaScript" src="laydate/laydate.js"></script>
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
    &nbsp&nbsp选择月份
    <select name="tmon" id="tmon">
        <? foreach($tmonlist as $mon) {?><option value="<?=$mon?>"><?=$mon?></option>
        <?}?>
    </select> <input type="submit" value="设置">
      <div class="am-u-sm-12">
<table width="95%" border="1" cellpadding="0" cellspacing="0">
  <tbody>
            <tr>
              <th height="29" scope="col"><strong>cpid</strong></th>
              <th scope="col">cp名称</th>
              <th scope="col">订阅总额</th>
              <th scope="col">收入金额</th>
              </tr>
            <?
            $sql="select  * from jieqi_cp_users";
			$cplist=queryall($sql);
			foreach($cplist as $d) {
                $cpid = $d['cpid'];

                $total_sale_price = get_cp_total_sale($cpid);


                ?>
                <tr>
                    <td height="28"><?= $d['cpid'] ?></td>
                    <td><?=$d['cpname']?>&nbsp;</td>
                    <td><?= $total_sale_price['sale_total'] ?>&nbsp;</td>
                    <td><?= $total_sale_price['pay_money'] ?></td>
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
