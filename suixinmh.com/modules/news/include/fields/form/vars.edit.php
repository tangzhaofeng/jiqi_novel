<?php 
$sql = "ALTER TABLE `$tablename` CHANGE `$field` `$field` MEDIUMTEXT NOT NULL";
$_SGLOBAL['db']->db->query($sql);
?>