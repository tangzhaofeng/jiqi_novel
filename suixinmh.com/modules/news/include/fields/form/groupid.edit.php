<?php 
if(!$maxlength) $maxlength = 10;
$fieldtype = $setting[fieldtype];
$defaultvalue = is_array($setting[defaultvalue]) ?implode(',', $setting[defaultvalue]) :$setting[defaultvalue];
$fieldtype = $issystem ? 'CHAR' : 'VARCHAR';
$sql = "ALTER TABLE `$tablename` CHANGE `$field` `$field` $fieldtype( $maxlength ) NOT NULL DEFAULT '$defaultvalue'";
$_SGLOBAL['db']->db->query($sql);
?>