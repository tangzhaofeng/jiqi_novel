<?php
/*
    *时间类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_datetime.class.php 12398 2010-05-24 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_text.class.php');

class Form_datetime extends Form_text
{
    function Form_datetime($formobj, $field, $value = ''){
	     $this->__construct($formobj, $field, $value);
	     $this->fieldinfo['formattribute'].= ' readonly onfocus="showCalendar(this,event)" onclick="showCalendar(this,event)"';
		 $formobj->fields[$field]['forminfo'].= '&nbsp;<script src="/scripts/calendar.js"></script> ';
	}
    //设置内容
	function getValue(){
	    if($this->value){
		    return date($this->setting['format'], $this->value);
		}else {
		    switch($this->setting['defaulttype']){
			    case '1':
				   return date($this->setting['format'], time());
				break;
				case '2':
				   return $this->setting['defaultvalue'];
				break;
				default:
				    return '';
				break;
			}
		}
	}
	
	//获取表单提交数据
	function getAdd($value){
	    return $value ?strtotime($value) :time();
	}
	
	//获取数据，前台显示
	function getShow(){
	    $this->setSetting();
	    $this->formobj->setVar('__'.$this->field, @date($this->setting['format'], $this->value));
	    return $this->value;
	}

}
?>