<?
$nocheck=true;
include_once("db.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=GB18030">
    <title>Untitled Document</title>
</head>

<body>
<form name="form1" method="post" action="">
    订单信息：
    <input name="orderid" type="text" id="orderid" size="40">
    （51CP开头或2016开头一长串编码）<br>
    网站流水号：
    <input name="webid" type="text" id="webid" size="30">
    (5-7位的数字串)
    <input type="submit" name="submit" id="submit" value="查询">
</form>
<?
$orderid=trim($_POST['orderid']);
$webid=trim($_POST['webid']);
if ($orderid || $webid) {
    if ($orderid)
        $sql="select * from jieqi_pay_paylog where retserialno='$orderid'";
    else
        $sql="select * from jieqi_pay_paylog where payid='$webid'";

    $r=mysql_query($sql);
    $d=mysql_fetch_array($r);
    if ($d) {
        $sql="select * from jieqi_system_users where uid=".$d['buyid'];
        $r1=mysql_query($sql);
        $userinfo=mysql_fetch_array($r1);
        $sql="select * from jieqi_system_connect where uid=".$userinfo['uid'];
        $r2=mysql_query($sql);
        $d2=mysql_fetch_array($r2);
        if ($d2) {
            $usertype=$d2['type'];
        }
        else {
            $usertype="网站注册";
        }
        ?>
        <table width="95%" border="1">
            <tbody>
            <tr>
                <th scope="col">用户名</th>
                <th scope="col">订单号</th>
                <th scope="col">充值金额</th>
                <th scope="col">充值时间</th>
            </tr>
            <tr>
                <td><?=$d['buyname']?>&nbsp;</td>
                <td><?=$d['retserialno']?>&nbsp;</td>
                <td><?=$d['money']/100?>&nbsp;</td>
                <td><?=date("Y-m-d H:i:s",$d['buytime'])?>&nbsp;</td>
            </tr>
            </tbody>
        </table>
        <br>
        <br>
        <table width="51%" height="180" border="1" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td width="32%">&nbsp;用户名</th>
                <td width="68%"><?=$userinfo['uname']?>&nbsp;</th>
            </tr>
            <tr>
                <td>账号类型</td>
                <td><?=$usertype?></td>
            </tr>
            <tr>
                <td>账户余额</td>
                <td><?=$userinfo['egold']."书币,".$userinfo['esilver']."书券"?></td>
            </tr>
            </tbody>
        </table>
        <p>&nbsp;</p>
        <?
    }
    else {
        echo "没查到";
    }
}
?>
</body>
</html>