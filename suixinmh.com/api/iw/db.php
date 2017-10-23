<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 15/11/9
 * Time: ÏÂÎç3:07
 */
include(dirname(__FILE__)."/../..//configs/define.php");
include(dirname(__FILE__)."/../../global.php");
include_once(dirname(__FILE__)."/../../lib/database/mysql/db.php");

$db=new JieqiMySQLDatabase;
$db->connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS);
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");
$ip=$_SERVER['REMOTE_ADDR'];
if (!in_array($ip,$allow_iplist)) {
    die("ip:$ip not allowed");
}