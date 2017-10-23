<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 15/12/24
 * Time: 下午1:47
 */
header("location:http://m.ishufun.net/index.php?controller=userhuodong&method=sliver");
exit();
define('JIEQI_CHAR_SET','utf-8');
include("../../../global.php");
$source=intval($_GET['source']);
if (!$_SESSION['jieqiUserId']) {
    header("location:/login?jumpurl=".urlencode("/huodong/silver.php?source=$source"));
    exit();
}
else {
    $uid=$_SESSION['jieqiUserId'];
}
$hid=1;

$conn=mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS);
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");

$sql="select * from jieqi_system_huodong where uid=$uid and hid=$hid";
$r=mysql_query($sql);
if (mysql_num_rows($r)) {
    jieqi_printfail("很抱歉，您已经领取过了，不能重复领取哦");
}
insert_huodong($hid,$uid);
add_esilver($uid,100);
jieqi_msgwin1("领取成功","恭喜！您已经成功领取了100书券 <br\> <a href='http://".JIEQI_HTTP_HOST."'>点击这里继续阅读</a> ");


function add_esilver($uid,$sliver) {
    global $_SESSION;
    $_SESSION['jieqiUserEsilvers'] = $_SESSION['jieqiUserEsilvers']+100;
    $sql="update jieqi_system_users set esilver=".$_SESSION['jieqiUserEsilvers']." where uid=$uid";
    mysql_query($sql);
}

function insert_huodong($hid,$uid) {
    global $source;
    $time=time();
    $sql="insert into jieqi_system_huodong (hid,uid,hdate,source) values('$hid','$uid',$time,'$source')";
    mysql_query($sql);
}

function jieqi_msgwin1($title = '', $content = '', $ext = array())
{
    if (!$title) $title = LANG_NOTICE;
    if (!$content) $content = LANG_DO_SUCCESS;
    if (defined('JIEQI_NOCONVERT_CHAR') && !empty($GLOBALS['charset_convert_out'])) @ob_start($GLOBALS['charset_convert_out']);
    if (empty($_REQUEST['ajax_request'])) {
        include_once(JIEQI_ROOT_PATH . '/lib/template/template.php');
        $title = jieqi_htmlstr($title);
        $jieqiTpl =& JieqiTpl::getInstance();
        $jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_themeurl' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/', 'jieqi_themecss' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/style/style.css', 'title' => $title, 'content' => $content, 'jieqi_sitename' => JIEQI_SITE_NAME, 'ext' => $ext));
        $jieqiTpl->setCaching(0);
        $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/msgwin.html');
    } else {
        header('Content-Type:text/html; charset='.JIEQI_CHAR_SET);
        include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
        $data = array('status' => 'OK', 'msg' => jieqi_gb2utf8($content), 'jumpurl' => 'http://m.ishufun.net');
        if ($_REQUEST['CALLBACK']) {
            echo($_REQUEST['CALLBACK'] . '(' . json_encode($data) . ');');
        } else echo(json_encode($data));
    }
    jieqi_freeresource();
    exit;
}
