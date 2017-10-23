<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/1/19
 */
include("/www/suixinmh.com/global.php");
include("/www/suixinmh.com/include/total_service_funcs.php");
mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) || die("conn error\n");
mysql_select_db(JIEQI_DB_NAME);
mysql_query("set names gbk");
dump_qd_stat();
