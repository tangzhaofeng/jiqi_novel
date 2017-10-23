<?php 
$minnumber = intval($minnumber);
$defaultvalue = intval($setting[defaultvalue]);
$sql = "ALTER TABLE `$tablename` ADD `$field` INT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'";
$_SGLOBAL['db']->db->query($sql);
?>