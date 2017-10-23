<?php
echo '<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8"/>
<title>管理后台</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css">
<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE STYLES -->
<!-- BEGIN THEME STYLES -->
<!-- DOC: To use \'rounded corners\' style just load \'components-rounded.css\' stylesheet instead of \'components.css\' in the below style tag -->
<link href="assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout4/css/themes/light.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="assets/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
	<script src="assets/echarts.min.js"></script>
</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo">
<!-- BEGIN HEADER -->
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'templates/newadmin/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'templates/newadmin/menu.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
	</div>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE HEAD -->
			<div class="page-head">
				<!-- BEGIN PAGE TITLE -->
				<div class="page-title">
					<h1>数据总览 <small>汇总报表</small></h1>
				</div>
				<!-- END PAGE TOOLBAR -->
			</div>
			<!-- END PAGE HEAD -->
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb hide">
				<li>
					<a href="javascript:;">Home</a><i class="fa fa-circle"></i>
				</li>
				<li class="active">
					 Dashboard
				</li>
			</ul>
			<!-- END PAGE BREADCRUMB -->
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="row margin-top-10">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat2">
						<div class="display">
							<div class="number">
								<h3 class="font-green-sharp">'.$this->_tpl_vars['total_pay'].'<small class="font-green-sharp">￥</small></h3>
								<small>合计收入</small>
							</div>
							<div class="icon">
								<i class="icon-pie-chart"></i>
							</div>
						</div>
						<div class="progress-info">
							<div class="progress">
								<span style="width: '.$this->_tpl_vars['money_percent'].'%;" class="progress-bar progress-bar-success green-sharp">
								<span class="sr-only">100% progress</span>
								</span>
							</div>
							<div class="status">
								<div class="status-title">
									对比昨日
								</div>
								<div class="status-number">
									 '.$this->_tpl_vars['money_percent'].'%
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat2">
						<div class="display">
							<div class="number">
								<h3 class="font-red-haze">'.$this->_tpl_vars['total_reg'].'</h3>
								<small>注册用户数</small>
							</div>
							<div class="icon">
								<i class="icon-like"></i>
							</div>
						</div>
						<div class="progress-info">
							<div class="progress">
								<span style="width: '.$this->_tpl_vars['reg_percent'].'%;" class="progress-bar progress-bar-success red-haze">
								<span class="sr-only">'.$this->_tpl_vars['reg_percent'].'% change</span>
								</span>
							</div>
							<div class="status">
								<div class="status-title">
									对比昨日
								</div>
								<div class="status-number">
									'.$this->_tpl_vars['reg_percent'].'%
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat2">
						<div class="display">
							<div class="number">
								<h3 class="font-blue-sharp">'.$this->_tpl_vars['click'].'</h3>
								<small>推广点击量</small>
							</div>
							<div class="icon">
								<i class="icon-basket"></i>
							</div>
						</div>
						<div class="progress-info">
							<div class="progress">
								<span style="width: '.$this->_tpl_vars['click_percent'].'%;" class="progress-bar progress-bar-success blue-sharp">
								<span class="sr-only">'.$this->_tpl_vars['click_percent'].'% grow</span>
								</span>
							</div>
							<div class="status">
								<div class="status-title">
									对比昨日
								</div>
								<div class="status-number">
									'.$this->_tpl_vars['click_percent'].'%
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat2">
						<div class="display">
							<div class="number">
								<h3 class="font-purple-soft">'.$this->_tpl_vars['pv'].'</h3>
								<small>PV</small>
							</div>
							<div class="icon">
								<i class="icon-user"></i>
							</div>
						</div>
						<div class="progress-info">
							<div class="progress">
								<span style="width: '.$this->_tpl_vars['pv_percent'].'%;" class="progress-bar progress-bar-success purple-soft">
								<span class="sr-only">'.$this->_tpl_vars['pv_percent'].'% change</span>
								</span>
							</div>
							<div class="status">
								<div class="status-title">
									对比昨日
								</div>
								<div class="status-number">
									'.$this->_tpl_vars['pv_percent'].'%
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet light ">
						<div class="portlet-title">
							<div class="caption caption-md">
								<i class="icon-bar-chart theme-font-color hide"></i>
								<span class="caption-subject theme-font-color bold uppercase">充值数据</span>
								<span class="caption-helper hide">weekly stats...</span>
							</div>
						</div>
                        <div class="row list-separated">
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <div class="font-grey-mint font-sm">
                                    充值用户
                                </div>
                                <div class="uppercase font-hg font-red-flamingo">
                                    '.$this->_tpl_vars['total_pay_users'].'<span class="font-lg font-grey-mint">人</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <div class="font-grey-mint font-sm">
                                    人均充值
                                </div>
                                <div class="uppercase font-hg theme-font-color">
                                    '.$this->_tpl_vars['arpu'].'<span class="font-lg font-grey-mint">￥</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <div class="font-grey-mint font-sm">
                                    点击价值
                                </div>
                                <div class="uppercase font-hg font-purple">
                                    '.$this->_tpl_vars['click_money'].'<span class="font-lg font-grey-mint">￥</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <div class="font-grey-mint font-sm">
                                    PV价值
                                </div>
                                <div class="uppercase font-hg font-blue-sharp">
                                    '.$this->_tpl_vars['pv_money'].'<span class="font-lg font-grey-mint">￥</span>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body" style="height: 280px;">
                            <div id="sales_statistics" class="portlet-body-morris-fit morris-chart" style="height: 260px"></div>
                        </div>
					</div>
					<!-- END PORTLET-->
			    </div>
                <div class="col-md-6 col-sm-12">
                    <!-- BEGIN PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption caption-md">
                                <i class="icon-bar-chart theme-font-color hide"></i>
                                <span class="caption-subject theme-font-color bold uppercase">一周数据</span>
                                <span class="caption-helper hide">weekly stats...</span>
                            </div>
                        </div>
                        <div class="portlet-body" >
                            <div class="table-scrollable table-scrollable-borderless" style="height: 320px;">
                                <table class="table table-hover table-light">
                                    <thead>
                                    <tr class="uppercase">
                                        <th>
                                            日期
                                        </th>
                                        <th>
                                            充值
                                        </th>
                                        <th>
                                            注册人数
                                        </th>
                                        <th>
                                            充值人数
                                        </th>
                                        <th>
                                            pv
                                        </th>
                                    </tr>
                                    </thead>
                                    ';
if (empty($this->_tpl_vars['weekly_data_rows'])) $this->_tpl_vars['weekly_data_rows'] = array();
elseif (!is_array($this->_tpl_vars['weekly_data_rows'])) $this->_tpl_vars['weekly_data_rows'] = (array)$this->_tpl_vars['weekly_data_rows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['weekly_data_rows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['weekly_data_rows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['weekly_data_rows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['weekly_data_rows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['weekly_data_rows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
                                    <tr>
                                        <td>
                                            '.$this->_tpl_vars['weekly_data_rows'][$this->_tpl_vars['i']['key']]['date'].'
                                        </td>
                                        <td>
                                            ￥'.$this->_tpl_vars['weekly_data_rows'][$this->_tpl_vars['i']['key']]['money'].'
                                        </td>
                                        <td>
                                            '.$this->_tpl_vars['weekly_data_rows'][$this->_tpl_vars['i']['key']]['regcount'].'
                                        </td>
                                        <td>
                                            '.$this->_tpl_vars['weekly_data_rows'][$this->_tpl_vars['i']['key']]['payusers'].'
                                        </td>
                                        <td>
                                            '.$this->_tpl_vars['weekly_data_rows'][$this->_tpl_vars['i']['key']]['pv'].'
                                        </td>
                                    </tr>
                                    ';
}
echo '
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- END PORTLET-->
                </div>

			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		 2014 &copy; Metronic by keenthemes.
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
<script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="assets/admin/layout4/scripts/layout.js" type="text/javascript"></script>
<script src="assets/admin/layout4/scripts/demo.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/index3.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById(\'sales_statistics\'));

        option = {
            tooltip : {
                trigger: \'axis\'
            },
            legend: {
                data:[\'今日支付\',\'昨日支付\']
            },
            toolbox: {
                show : false,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: [\'line\', \'bar\']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            xAxis : [
                {
                    type : \'category\',
                    boundaryGap : false,
                    data : [\'00\',\'01\',\'02\',\'03\',\'04\',\'05\',\'06\',\'07\',\'08\',\'09\',\'10\',\'11\',\'12\',\'13\',\'14\',\'15\',\'16\',\'17\',\'18\',\'19\',\'20\',\'21\',\'22\',\'23\']
                }
            ],
            yAxis : [
                {
                    type : \'value\',
                    axisLabel : {
                        formatter: \'{value}\'
                    }
                }
            ],
            series : [
                {
                    name:\'今日支付\',
                    type:\'line\',
                    smooth:true,
                    itemStyle:{
                        normal: {
                            lineStyle: {
                                color: \'rgba(92,155,209,255)\',
                                width: \'2\'
                            },
                            areaStyle:{
                                color:\'rgba(92,155,209,255)\',
                                type:\'defualt\'
                            }
                        }
                    },
                    data:['.$this->_tpl_vars['today_paylist'].']
                },
                {
                    name:\'昨日支付\',
                    type:\'line\',
                    smooth:true,
                    itemStyle:{
                        normal: {
                            lineStyle: {
                                color: \'rgba(200,200,200,128)\',
                                width: \'2\'
                            },
                            areaStyle:{
                                color:\'rgb(200,200,200)\',
                                type:\'defualt\'
                            }
                        }
                    },
                    data:['.$this->_tpl_vars['yesterday_paylist'].']
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
   Demo.init(); // init demo features 
    Index.init(); // init index page
 Tasks.initDashboardWidget(); // init tash dashboard widget  
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>';
?>