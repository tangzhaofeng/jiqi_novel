<?php 
if(!$issystem){
	if(!$maxlength) $maxlength = 255;
	$maxlength = max($maxlength, 255);
	$fieldtype = $issystem ? 'CHAR' : 'VARCHAR';
	//$sql = "ALTER TABLE `$tablename` CHANGE `$field` `$field` $fieldtype( $maxlength ) NOT NULL DEFAULT '{$setting[defaultvalue]}'";
	$sql = "ALTER TABLE `$tablename` CHANGE `$field` `$field` ".(($maxlength && $maxlength <= 1000) ? "$fieldtype( $maxlength ) NOT NULL DEFAULT '{$setting[defaultvalue]}'" :  "MEDIUMTEXT NOT NULL DEFAULT '{$setting[defaultvalue]}'");
	$_SGLOBAL['db']->db->query($sql);
}
?>