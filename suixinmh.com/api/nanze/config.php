<?php
$allow_iplist=array('10.24.33.222','219.137.130.145');
if (!in_array($_SERVER['REMOTE_ADDR'],$allow_iplist)) {
    die("ip_not_allowed");
}
header("Content-type: text/html; charset=utf-8");

