<?php 
$sql = "ALTER TABLE `$tablename` ADD `$field` TEXT NOT NULL";
$_SGLOBAL['db']->db->query($sql);
?>