<?php
include_once("db.php");
if (!$_SESSION['jieqiAdminLogin']) {
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $url = urlencode("http://$host/$script");
    header("location:/web_admin/?controller=login&jumpurl=$url");
    exit();
}




$t=strtotime(date("Y-m-d"));
$sql="select count(*) as reg_user_count from jieqi_system_users where regdate>$t";
$sql=append_qd_info($sql);
//echo $sql."<br>";

$d=query($sql);
$reg_users=$d['reg_user_count'];


$sql="select * from jieqi_online_total where time>$t order by id desc ";
$d=query($sql);
$online=$d['online'];

$sql="SELECT count(distinct `payid`) as pay_user_count,count(*) as order_count,sum(money)/100 as pay_money FROM `jieqi_pay_paylog` WHERE `buytime`>$t and `payflag`=1";
$sql=append_qd_info($sql);
//echo $sql."<br>";
$d=query($sql);
$pay_users=$d['pay_user_count'];
$pay_money = $d['pay_money'];
$order_count=$d['order_count'];

$data=array();



$t1=$t-7*86400;
$t2=$t1+86400;
$sql="select count(*) as usercount,from_unixtime(regdate,'%Y-%m-%d') as reg_date from jieqi_system_users where regdate>=$t1 ";
$sql=append_qd_info($sql);
$sql.=' group by reg_date order by reg_date desc';
//echo $sql."<br>";
$d1=queryall($sql);
	
$sql="SELECT count(distinct `payid`) as pay_user_count,count(*) as order_count,sum(money)/100 as pay_money,from_unixtime(buytime,'%Y-%m-%d') as pay_date FROM `jieqi_pay_paylog` WHERE `buytime`>=$t1 and `payflag`=1 ";
$sql=append_qd_info($sql);
$sql.='group by pay_date order by pay_date desc';
//echo $sql."<br>";
$d2=queryall($sql);

for($i=0;$i<count($d1);$i++) {
    $data[]=array("date"=>$d1[$i]['reg_date'],"usercount"=>$d1[$i]['usercount'],"pay_users"=>$d2[$i]['pay_user_count'],"pay_money"=>$d2[$i]['pay_money'],"order_count"=>$$d2[$i]['order_count']);
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
    <script src="../js/Chart.js"></script>
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

$t1=strtotime(date("Y-m-d",time()-86400));
$t2=$t1+86400;
$t3=time();

for ($h=0;$h<=23;$h++) {
    $harr[]=$h;
    $ydata[$h]=0;
    if ($h<=date("H")) {
        $tdata[$h] = 0;
    }
}
$hstr='"'.implode('","',$harr).'"';

$sql="select sum(money)/100 as paymoney,from_unixtime(buytime,'%H') as pay_hour from jieqi_pay_paylog where buytime between $t1 and $t2 and payflag=1 group by pay_hour order by pay_hour";
//echo $sql;
$r1=mysql_query($sql);
while ($d1=mysql_fetch_array($r1)) {
    $h=intval($d1['pay_hour']);
    $ydata[$h]=floor($d1['paymoney']);
}
$ystr=implode(',',$ydata);

$sql="select sum(money)/100 as paymoney,from_unixtime(buytime,'%H') as pay_hour from jieqi_pay_paylog where buytime between $t2 and $t3 and payflag=1 group by pay_hour order by pay_hour";
$r1=mysql_query($sql);
while ($d1=mysql_fetch_array($r1)) {
    $h=intval($d1['pay_hour']);
    $tdata[$h]=floor($d1['paymoney']);
}
$tstr=implode(',',$tdata);

?>

  <!-- content start -->
  <div class="admin-content">

      <canvas id="myChart" width="600" height="200"></canvas>
      <script language="javascript">
          var data = {
              labels : [<?=$hstr?>],
              datasets : [
                  {
                      fillColor : "rgba(220,220,220,0.5)",
                      strokeColor : "rgba(220,220,220,1)",
                      pointColor : "rgba(220,220,220,1)",
                      pointStrokeColor : "#fff",
                      data : [<?=$ystr?>]
                  },
                  {
                      fillColor : "rgba(151,187,205,0.5)",
                      strokeColor : "rgba(151,187,205,1)",
                      pointColor : "rgba(151,187,205,1)",
                      pointStrokeColor : "#fff",
                      data : [<?=$tstr?>]
                  }
              ]
          }
          var ctx = document.getElementById("myChart").getContext("2d");
          //var myNewChart = new Chart(ctx).PolarArea(data);
          option =  {

              //Boolean - If we show the scale above the chart data
              scaleOverlay : false,

              //Boolean - If we want to override with a hard coded scale
              scaleOverride : false,

              //** Required if scaleOverride is true **
              //Number - The number of steps in a hard coded scale
              scaleSteps : null,
              //Number - The value jump in the hard coded scale
              scaleStepWidth : null,
              //Number - The scale starting value
              scaleStartValue : null,

              //String - Colour of the scale line
              scaleLineColor : "rgba(0,0,0,.1)",

              //Number - Pixel width of the scale line
              scaleLineWidth : 1,

              //Boolean - Whether to show labels on the scale
              scaleShowLabels : false,

              //Interpolated JS string - can access value
              scaleLabel : "<%=value%>",

              //String - Scale label font declaration for the scale label
              scaleFontFamily : "'Arial'",

              //Number - Scale label font size in pixels
              scaleFontSize : 12,

              //String - Scale label font weight style
              scaleFontStyle : "normal",

              //String - Scale label font colour
              scaleFontColor : "#666",

              ///Boolean - Whether grid lines are shown across the chart
              scaleShowGridLines : true,

              //String - Colour of the grid lines
              scaleGridLineColor : "rgba(0,0,0,.05)",

              //Number - Width of the grid lines
              scaleGridLineWidth : 1,

              //Boolean - Whether the line is curved between points
              bezierCurve : true,

              //Boolean - Whether to show a dot for each point
              pointDot : true,

              //Number - Radius of each point dot in pixels
              pointDotRadius : 3,

              //Number - Pixel width of point dot stroke
              pointDotStrokeWidth : 1,

              //Boolean - Whether to show a stroke for datasets
              datasetStroke : true,

              //Number - Pixel width of dataset stroke
              datasetStrokeWidth : 2,

              //Boolean - Whether to fill the dataset with a colour
              datasetFill : true,

              //Boolean - Whether to animate the chart
              animation : true,

              //Number - Number of animation steps
              animationSteps : 60,

              //String - Animation easing effect
              animationEasing : "easeOutQuart",

              //Function - Fires when the animation is complete
              onAnimationComplete : null

          };
          new Chart(ctx).Line(data,option);

      </script>
    <ul class="am-avg-sm-1 am-avg-md-4 am-margin am-padding am-text-center admin-content-list ">
      <li><a href="#" class="am-text-success">新增用户<br/><?=$reg_users?></a></li>
      <li><a href="#" class="am-text-warning">成功订单<br/><?=$order_count?></a></li>
      <li><a href="#" class="am-text-danger">付费用户<br/><?=$pay_users?></a></li>
      <li><a href="#" class="am-text-secondary">付费金额<br/><?=$pay_money?></a></li>
    </ul>

    <div class="am-g">
      <div class="am-u-sm-12">


        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th>日期</th><th>注册用户</th><th>付费用户</th><th>付费金额</th>
</tr>
          </thead>
          <tbody>
          <?php
          foreach($data as $d) {
		  ?>
          <tr><td><?=$d['date']?></td><td><?=$d['usercount']?></td><td><?=$d['pay_users']?></td><td><?=round($d['pay_money'])?></td></tr>
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


</body>
</html>
