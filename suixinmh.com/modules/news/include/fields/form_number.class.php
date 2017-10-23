<?php
/*
    *数字类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_number.class.php 12398 2010-07-19 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_text.class.php');

class Form_number extends form_Text
{

	//获取表单提交数据
	function getAdd($value){
		if(!is_numeric($value)) return $this->setting['defaultvalue'];
		$this->setSetting();
		if($this->setting['minnumber'] || $this->setting['maxnumber']){
		    if(strlen($value)<$this->setting['minnumber']) $value = $this->setting['defaultvalue'];
			if(strlen($value)>$this->setting['maxnumber']) $value = $this->setting['defaultvalue'];
		}
	    return $value;
	}
	
    //获取数据，前台显示
	function getShow(){
	     $this->setSetting();
		 if($this->setting['decimaldigits']=='-1') return $this->value;
		 elseif($this->setting['decimaldigits']>0) return sprintf("%.{$this->setting['decimaldigits']}F", $this->value);
		 else return intval($this->value);
	}
}
?>