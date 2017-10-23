<?php
/*
    *隐藏域类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_hidden.class.php 12398 2010-04-25 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_text.class.php');

class Form_hidden extends Form_text
{

	function setFormObject(){
	    $this->element = new JieqiFormHidden("info[{$this->field}]", $this->getValue());
	}

}
?>