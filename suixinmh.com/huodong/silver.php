<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 15/12/24
 * Time: 下午1:47
 */
include("../global.php");
if (!$_SESSION['jieqiUserId']) {
    header("location:/login?jumpurl=/huodong/silver.php");
    exit();
}
else {
    $uid=$_SESSION['jieqiUserId'];
}
$hid=1;

$sql="select * from jieqi_system_huodong where uid=$uid and hid=$hid";
$r=mysql_query($sql);
if (mysql_num_rows($r)) {
    jieqi_printfail("很抱歉，您已经领取过了，不能重复领取哦");
}

add_esilver($uid,100);


function add_esilver($uid,$sliver) {
    global $_SESSION;
    $_SESSION['jieqiUserEsilvers'] = $_SESSION['jieqiUserEsilvers']+100;
    $sql="update system_users set esilver=".$_SESSION['jieqiUserEsilvers']." where uid=$uid";
    mysql_query($sql);
}


jieqi_msgbox("恭喜！您已经成功领取了100银币");