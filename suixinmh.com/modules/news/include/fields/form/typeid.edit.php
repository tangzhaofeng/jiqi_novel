<?php 
$defaultvalue = intval($setting[defaultvalue]);
$_SGLOBAL['db']->db->query("ALTER TABLE `$tablename` CHANGE `$field` `$field` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '$defaultvalue'");
?>