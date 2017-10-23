<?php
/*
    *多选按纽类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_checkbox.class.php 12398 2010-04-29 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_select.class.php');

class Form_checkbox extends Form_select
{

	function setFormObject(){
	    $this->fieldinfo['formattribute'].= ' boxid="'.$this->field.'" id="'.$this->field.'"';
	    $this->element = new JieqiFormCheckBox('', "{$this->fieldpre}[{$this->field}][]", $this->getValue());
	}

}
?>