<?php 
$sql = "ALTER TABLE `$tablename` CHANGE `$field` `$field` TEXT NOT NULL";
$_SGLOBAL['db']->db->query($sql);
?>