<?php 
$minnumber = intval($minnumber);
$defaultvalue = $setting[decimaldigits] == 0 ? intval($setting[defaultvalue]) : floatval($setting[defaultvalue]);
$sql = "ALTER TABLE `$tablename` ADD `$field` ".($setting[decimaldigits] == 0 ? 'INT' : 'FLOAT')." ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'";
$_SGLOBAL['db']->db->query($sql);
?>