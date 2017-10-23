<?php
echo '<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.2
Version: 3.7.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>eshuku分成合作后台-账号修改</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="assets/admin/layout4/css/themes/light.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo ">
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
            <!-- END PAGE BREADCRUMB -->
            <!-- BEGIN PAGE CONTENT INNER -->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-edit"></i>账号注册
                            </div>
                        </div>
                        <div class="portlet-body">
                            <form action="index.php?controller=profile&method=update" method="post">
                                <input type="hidden" name="action" value="'.$this->_tpl_vars['action'].'"/>

                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>
                                                <small>账号:</small>
                                            </h3>
                                            <div>
                                                <input class="form-control form-control-inline input-large date-picker"
                                                       size="10" name="username" value="'.$this->_tpl_vars['username'].'" type="text" disabled/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <h3>
                                                <small>密码(不改密码请留空)</small>
                                            </h3>
                                            <div>
                                                <input class="form-control form-control-inline input-large date-picker"
                                                       size="10" name="password" value="" type="password"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <h3>
                                                <small>再次输入密码(不改密码请留空)</small>
                                            </h3>
                                            <div>
                                                <input class="form-control form-control-inline input-large date-picker"
                                                       size="10" type="password" name="repassword" value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <h3>
                                                <small>联系QQ</small>
                                            </h3>
                                            <div>
                                                <input class="form-control form-control-inline input-large date-picker"
                                                       size="10" type="text" name="qq" value="'.$this->_tpl_vars['qq'].'"/>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <h3>
                                                <small>联系微信</small>
                                            </h3>
                                            <div>
                                                <input class="form-control form-control-inline input-large date-picker"
                                                       size="10" type="text" name="wechat" value="'.$this->_tpl_vars['wechat'].'"/>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <h3>
                                                <small>联系电话</small>
                                            </h3>
                                            <div>
                                                <input class="form-control form-control-inline input-large date-picker"
                                                       size="10" type="text" name="mobile" value="'.$this->_tpl_vars['mobile'].'" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <h3>
                                                <small>结算银行卡开户行(填写后不可修改,如需再次修改请联系商务)</small>
                                            </h3>
                                            <div>
                                                <input class="form-control form-control-inline input-large date-picker"
                                                       size="20" type="text" name="bankaddress"
                                                       value="'.$this->_tpl_vars['bankaddress'].'" ';
if($this->_tpl_vars['bankaddress']!=""){
echo 'disabled';
}
echo ' />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <h3>
                                                <small>结算银行卡号(填写后不可修改,如需再次修改请联系商务)</small>
                                            </h3>
                                            <div>
                                                <input data-type="number"
                                                       class="form-control form-control-inline input-large date-picker"
                                                       size="10" type="text" name="banknumber" value="'.$this->_tpl_vars['banknumber'].'" ';
if($this->_tpl_vars['banknumber']!=""){
echo 'disabled';
}
echo '/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <h3>
                                                <small>银行卡开户人姓名(填写后不可修改,如需再次修改请联系商务)</small>
                                            </h3>
                                            <div>
                                                <input class="form-control form-control-inline input-large date-picker"
                                                       size="10" type="text" name="payee" value="'.$this->_tpl_vars['payee'].'" ';
if($this->_tpl_vars['payee']!=""){
echo 'disabled';
}
echo ' />
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-actions noborder">
                                                <button type="submit" class="btn blue">确认修改</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            <!-- END EXAMPLE TABLE PORTLET-->
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
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="assets/admin/layout4/scripts/layout.js" type="text/javascript"></script>
<script src="assets/admin/layout4/scripts/demo.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/table-editable.js"></script>
<script>
jQuery(document).ready(function() {       
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
   TableEditable.init();
});
</script>
</body>
<!-- END BODY -->
</html>';
?>