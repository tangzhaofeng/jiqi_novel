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
    平台订单号：
    <input name="transaction_id" type="text" id="transaction_id" size="40">
    （15开头的一长串数字)）<br><br>
    微信订单号:
    <input name="third_trans_id" type="text" id="third_trans_id" size="40">
    （400开头的一长串数字)）<br><br>
    网站流水号：
    <input name="webid" type="text" id="webid" size="40">
    (5-7位的数字串)<br><br>
    支付宝订单号:
    <input name="alipayid" type="text" id="alipayid" size="40">
    (2016开头的数字串)<br><br>
    多宝通订单号:
    <input name="dbtid" type="text" id="dbtid" size="40">
    (51CP开头的一长串)<br><br>

    <input type="submit" name="submit" id="submit" value="查询">
</form>
<?
$transaction_id=trim($_POST['transaction_id']);
$third_trans_id=trim($_POST['third_trans_id']);
$alipayid=trim($_POST['alipayid']);
$dbtid=trim($_POST['dbtid']);
$webid=trim($_POST['webid']);
if ($alipayid || $webid || $transaction_id || $third_trans_id) {
    if ($alipayid)
        $sql="select * from jieqi_pay_paylog where retserialno='$alipayid'";
    if ($dbtid)
        $sql="select * from jieqi_pay_paylog where retserialno='$dbtid'";
    elseif ($webid)
        $sql="select * from jieqi_pay_paylog where payid='$webid'";
    elseif ($transaction_id)
        $sql="select a.* from jieqi_pay_paylog a,jieqi_pay_wechat b where a.payid=b.payid and b.transaction_id='$transaction_id'";
    elseif ($third_trans_id)
        $sql="select a.* from jieqi_pay_paylog a,jieqi_pay_wechat b where a.payid=b.payid and b.third_trans_id='$third_trans_id'";
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