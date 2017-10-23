<?php
/**=====================================
  * 入口文件
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 ========================================*/
//error_reporting ( 0 );
define ( 'ACCESS_GRANT', TRUE );
include ("common/common.php");
$getData = array ();
$getData = checkRData ( $_GET );
session_start ();
if (isset ( $_SESSION ['manager'] )) {
	$mUser = $_SESSION ['manager'];
}
// 执行中转 action参数决定去向文件，opt参数决定调用模块函数
if(isset($getData['action'])){
	$actionModule = strtolower ( $getData ['action'] ) . 'Action';
}else{
	$actionModule = '';
}

if (is_action ( $actionModule )) {
	if($actionModule != 'publicAction' && $mUser['uid']< 1){
		header("Location: ".URL_INDEX."?action=public&opt=login");
	}
	$func = empty ( $getData ['opt'] ) ? 'index' : $getData ['opt'];
	include (ACTION_DIR . $actionModule . '.class.php');
	$act = new $actionModule ();
	if (! method_exists ( $act, $func )) {
		header ( "Location: " . URL_INDEX . "?action=public&opt=error404" );
	}	
	if (in_array ( $getData ['action'], $publicModule ) || checkgrant ( strtolower ( $getData ['action'] ), $getData ['opt'] )) { // 权限过滤
		$act->$func ();
		$smarty->display ( strtolower ( $getData ['action'] ) . "/" . $func . ".html" );
	} 
} else {
	if ($mUser ['uid'] < 1)
		header ( "Location: " . URL_INDEX . "?action=public&opt=login" );
	include (ACTION_DIR . 'sysmanageAction.class.php');
	$act = new sysmanageAction ();
	$act->index ();
	$act->leftMenu ();
	$smarty->assign ( 'mUser', $_SESSION ['manager'] );
	$smarty->display ( 'index.html' );
}
?>