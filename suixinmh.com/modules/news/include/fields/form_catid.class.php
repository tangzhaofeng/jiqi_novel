<?php
/*
    *栏目ID类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_catid.class.php 12398 2010-07-19 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_select.class.php');

class Form_catid extends Form_select
{
	//设置内容
	function setValue($value){
	    global $_PAGE;
	    if(!$value) $this->value = $_PAGE['_GET']['catid'];
		else $this->value = $value;
	}
	
	//设置列表项
	function setOptions(){
	    global $_SGLOBAL, $_OBJ;
		//$catid = !$this->formobj->category['parentid'] ?$this->formobj->category['catid'] :$this->formobj->category['parentid'];
		if(!is_object($_OBJ['category']))  $_OBJ['category'] = &new Category();
		$_OBJ['category']->get_format();
		$modelid = $this->formobj->category['modelid'];
	    //if($catid && $this->formobj->category['parentid'] && $cate = $_OBJ['category']->get_format($catid)){
		if($modelid && $cate = $_SGLOBAL['catelist']){
		    foreach($cate as $k=>$v){
			    if($v['type']>0 || $modelid!=$v['modelid']) continue;
				if($v['child']>0) $this->addOption($v['catid'], $v['layer'].$v['catname'], 'optgroup');
			    else $this->addOption($v['catid'], $v['layer'].$v['catname']);
			}
			//$this->addOptionArray($temp);
			return true;
		}else{
		    $this->addOptionArray(array($catid=>$this->formobj->category['catname']));
			return true;
	    }
		return false;
	}	
	
}
?>