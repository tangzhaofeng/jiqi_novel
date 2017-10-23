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
        日期
          <input id="tdate1" type="date" name="tdate1" value="<?=$tdate1?>" />
          -
          <input id="tdate2" type="date" name="tdate2" value="<?=$tdate2?>" />
          <button id="Button1" type="submit">查看</button>
        <table width="95%" border="1" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <th height="29" scope="col"><strong>渠道编号</strong></th>
              <th scope="col">注册人数</th>
              <th scope="col"><strong>充值人数</strong></th>
              <th scope="col"><strong>充值笔数</strong></th>
              <th scope="col"><strong>充值金额</strong></th>
              <th scope="col"><strong>ARPU</strong></th>
            </tr>
            <?
            $sql="select count(*) as ordercount,count(distinct uid) as pay_user_count,round(sum(money)/100) as pay_money,b.source from jieqi_pay_paylog a,jieqi_system_users b where a.buyid=b.uid and buytime>=$t1 and buytime<=$t2 and payflag=1 ";
            if ($_SESSION['qd_admin_level']<99) {
              $sql.=" and a.source like '".$_SESSION['qd_data']."'";
            }
            $sql.='group by b.source order by pay_money desc';
            //echo $sql."<br>";
			$d=queryall($sql);
			foreach($d as $paydata) {
				if (!$paydata['source']) {
					$source="未知";
				}
				else {
					$source=$paydata['source'];
				}
				$total_pay_user_count+=$paydata['pay_user_count'];
				$total_ordercount+=$paydata['ordercount'];
				$total_pay_money+=$paydata['pay_money'];
				
				$sql="select count(*) as regcount from jieqi_system_users where regdate>='$t1' and regdate<='$t2' and source like '".$paydata['source']."'";
                $sql=append_qd_info($sql);
				$d2=query($sql);
				$regcount=$d2['regcount'];
				$total_regcount+=$regcount;
			?>
            <tr>
              <td height="28"><?=$source?></td>
              <td><?=$regcount?>&nbsp;</td>
              <td><?=$paydata['pay_user_count']?>&nbsp;</td>
              <td><?=$paydata['ordercount']?>&nbsp;</td>
              <td><?=$paydata['pay_money']?>&nbsp;</td>
              <td><?=round($paydata['pay_money']*100/$paydata['pay_user_count'])/100?></td>
            </tr>
             <?
			}
			?>
            <tr>
              <td height="28">合计</td>
              <td><?=$total_regcount?>&nbsp;</td>
              <td><?=$total_pay_user_count?>&nbsp;</td>
              <td><?=$total_ordercount?>&nbsp;</td>
              <td><?=$total_pay_money?>&nbsp;</td>
              <td><?=round($total_pay_money*100/$total_pay_user_count)/100?>&nbsp;</td>
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




<script type="text/javascript">
$(function() {
	$( "#tdate1" ).datepicker(); 
});
$(function() {
	$( "#Button1" ).button(); 
});
$(function() {
	$( "#tdate2" ).datepicker(
		$.extend(
		$.datepicker.regional['zh-CN'],
		{
		dateFormat:"yy-mm-dd"
		}
		)
		); 
});
</script>
</body>
</html>
