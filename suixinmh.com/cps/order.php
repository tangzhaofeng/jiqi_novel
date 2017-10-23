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


$sql="select * from jieqi_pay_paylog where source='$qd'";

$r1=mysql_query($sql);
if (!$r1) {
    echo $sql."\n";
    echo mysql_error();
}
while ($d1=mysql_fetch_array($r1)) {
    $data[] = $d1;
}


?>
<table border="1">
    <tr>
        <td>渠道号</td>
        <td>账号</td>
        <td>时间</td>
        <td>是否成功</td>
        <td>充值金额</td>
    </tr>
<?
foreach($data as $p) {
    ?>
    <tr>
        <td><?= $qd ?></td>
        <td><?=$p['buyname']?></td>
        <td><?=date("Y-m-d H:i:s",$p['buytime'])?></td>
        <td><?=$p['payflag']?></td>
        <td><?=round($p['money']/100)?></td>
    </tr>
    <?
}
?>
</table>