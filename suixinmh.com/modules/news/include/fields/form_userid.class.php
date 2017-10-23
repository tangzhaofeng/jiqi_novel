<?php
/*
    *用户ID类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_userid.class.php 12398 2010-07-19 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_hidden.class.php');

class Form_userid extends form_Hidden
{

    //设置内容
	function setValue($value){
	    global $_SGLOBAL;
	    $this->value = $_SGLOBAL['supe_uid'];
	}
	//获取字段设置表单项
	function getFieldSetting($data = array()){
	    //
	}
}
?>