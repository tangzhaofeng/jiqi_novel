<?php
/*
    *省份类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_areaid.class.php 12398 2010-07-19 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_radio.class.php');

class Form_areaid extends Form_select
{
	
	//设置列表项
	function setOptions(){
	    //global $_SGLOBAL, $_OBJ;
		$temp = array();
		$temp[0] = lang_replace('please_select_area');
		$this->addOptionArray($temp);
		return true;
	}	
}
?>