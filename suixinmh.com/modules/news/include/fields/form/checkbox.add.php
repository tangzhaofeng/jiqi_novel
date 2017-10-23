<?php 
if(!$maxlength) $maxlength = 10;
$fieldtype = $setting[fieldtype];
$defaultvalue = $setting[defaultvalue];
$sql = "ALTER TABLE `$tablename` ADD `$field` $fieldtype( $maxlength ) NOT NULL DEFAULT '$defaultvalue'";
$_SGLOBAL['db']->db->query($sql);
?>