<?php 
if(!$maxlength) $maxlength = 10;
$fieldtype = $setting[fieldtype];
$defaultvalue = $setting[defaultvalue];
$fieldtype = $issystem ? 'CHAR' : 'VARCHAR';
$sql = "ALTER TABLE `$tablename` CHANGE `$field` `$field` $fieldtype( $maxlength ) NOT NULL DEFAULT '$defaultvalue'";
$_SGLOBAL['db']->db->query($sql);
?>