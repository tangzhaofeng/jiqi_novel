<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 2016/9/26
 * Time: 下午3:26
 */

include("../global.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS);
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");

$qd=intval($_REQUEST['qd']);

if ($qd<99005 || $qd>99010) {
    die("bad qd");
}

$sql="select * from jieqi_system_qddata where qd='$qd'";
$r=mysql_query($sql);
$data=array();
while ($d=mysql_fetch_array($r)) {
    $data[$d['time']]=$d;
}

$sql="select count(*) as bcount,from_unixtime(buytime,'%Y%m%d%h') as btime from jieqi_pay_paylog where source='$qd' group by btime";
$r1=mysql_query($sql);
if (!$r1) {
    echo $sql."\n";
    echo mysql_error();
}
while ($d1=mysql_fetch_array($r1)) {
    $data[$d1['btime']]['bcount'] = $d1['bcount'];
}

$sql="select count(*) as succ_count,sum(money) as bmoney,from_unixtime(buytime,'%Y%m%d%h') as btime from jieqi_pay_paylog where source='$qd' and payflag=1 group by btime";

$r1=mysql_query($sql);
if (!$r1) {
    echo $sql."\n";
    echo mysql_error();
}
while ($d1=mysql_fetch_array($r1)) {
    $data[$d1['btime']]['pay_num'] = $d1['succ_count'];
    $data[$d1['btime']]['pay_money'] = $d1['bmoney'];
    $data[$d1['btime']]['btime'] = $d1['btime'];
}

?>
<table border="1">
    <tr>
        <td>渠道号</td>
        <td>时间</td>
        <td>点击</td>
        <td>pv</td>
        <td>注册数</td>
        <td>订单数</td>
        <td>成功订单数</td>
        <td>充值金额</td>
    </tr>
<?
foreach($data as $time=>$info) {
    ?>
    <tr>
        <td><?= $qd ?></td>
        <td><?=$time?></td>
        <td><?=$info['click']?></td>
        <td><?=$info['pv']?></td>
        <td><?=$info['reg']?></td>
        <td><?=$info['bcount']?></td>
        <td><?=$info['pay_num']?></td>
        <td><?=round($info['pay_money']/100)?></td>
    </tr>
    <?
}
?>
</table>