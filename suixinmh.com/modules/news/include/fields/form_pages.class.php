<?php
/*
    *分页类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_pages.class.php 12398 2010-07-19 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_radio.class.php');

class Form_pages extends form_Select
{
	
    //设置内容
	function setValue($value){
	    $this->value = $value ?$value :0;
	}
	
	//获取表单提交数据
	function getAdd($value){
	    global $_PAGE;
		if($value>1 && isset($_PAGE['_POST']['_'.$this->field])){
			$pages = intval($_PAGE['_POST']['_'.$this->field]);
			$pages = $pages<500 ? 500 :$pages;
		}
		
	    return $pages ? $pages : $value;
	}
	
	//设置列表项
	function setOptions(){
	    //global $_SGLOBAL, $_OBJ;
		$temp = array();
		$temp[0] = lang_replace('article_not_pages');
		$temp[1] = lang_replace('article_trigger_pages');
		$temp[2] = lang_replace('article_auto_pages');
		$this->addOptionArray($temp);
		
		$this->fieldinfo['formattribute'].='style="width:85px" onchange="if(this.value>1)$(\'#__paginationtype1\').css(\'display\',\'\');else $(\'#__paginationtype1\').css(\'display\',\'none\');"';
		
		$defaultvalue = $this->getValue()>1 ?$this->getValue() :500;
		
		ob_start();
		include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/pages.inc.php');
		$str = ob_get_contents();
		ob_end_clean();	
			
		$this->formobj->fields[$this->field]['forminfo'].= $str;
		
		return true;
	}	
	
	//获取字段设置表单项
	function getFieldSetting($data = array()){
	    //
	}
}
?>