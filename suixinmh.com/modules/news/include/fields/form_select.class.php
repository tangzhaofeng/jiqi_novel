<?php
/*
    *下拉列表类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_select.class.php 12398 2010-04-18 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_text.class.php');

class Form_select extends Form_text
{
    var $items = false;
	
	function setFormObject(){
	    $this->element = new JieqiFormSelect('', "{$this->fieldpre}[{$this->field}]", $this->getValue(), $this->setting['size'], $this->setting['multiple']);
	}	
	
    //设置内容
	function setValue($value){
		if($value){
			if(substr_count($value, ',')) $this->value = explode(',', $value);
			else $this->value = $value;
		}
	}	
		
	//设置列表项
	function addOption($value, $name="" , $type = 'option'){
	    if(!is_object($this->element)) $this->setFormObject();
		if(!$name && !$value) return false;
		if ( $name != "" ) {
			$this->element->addOption($value, $name, $type);
		} else {
			$this->element->addOption($value, $value, $type);
		}
		//$this->items = true;
	}

	//批量设置列表项
	function addOptionArray($options){
		if ( is_array($options) ) {
			foreach ( $options as $k=>$v ) {
			    if(!$v && !$k) continue;
				$this->addOption($k, $v);
			}
		}
	}	
	
	function setForm(){
		if(!$this->setOptions()){
		    $array = explode("\n", $this->setting['items']);
			foreach($array as $k=>$v){
			    $items = explode("|", $v);
			    $this->element->addOption(trim($items[0]), trim($items[1]));
			}
		}
		$this->element->setExtra($this->fieldinfo['formattribute']);
		return $this->element->render();
	}
	
	//设置列表项开关
	function setOptions(){
		return $this->items;
	}
	
	//获取表单提交数据
	function getAdd($value){
	    if($value){
		   if(is_array($value)){
		       return implode(',', $value);
		   }else return $value;
		}else return '';
	}

}
?>