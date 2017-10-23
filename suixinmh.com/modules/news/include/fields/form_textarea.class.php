<?php
/*
    *文本域类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_textarea.class.php 12398 2010-04-18 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_text.class.php');

class Form_textarea extends Form_text
{
	function setFormObject(){
	    $this->element = new JieqiFormTextArea('', "info[{$this->field}]", $this->getValue(), $this->setting['rows'] ?$this->setting['rows'] : 8, $this->setting['cols'] ?$this->setting['cols'] : 80);
	}
	
	
	//获取表单提交数据
	function getAdd($value){
	    $this->setSetting();
		if($this->setting['enablehtml']) return $this->getValue();
	    else return shtmlspecialchars($this->getValue());
	}

}
?>