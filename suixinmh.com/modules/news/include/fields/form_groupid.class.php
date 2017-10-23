<?php
/*
    *阅读权限类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_groupid.class.php 12398 2010-05-26 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_checkbox.class.php');

class Form_groupid extends form_Checkbox
{

/*    function form_groupid($formobj, $field, $value = ''){
	     $this->__construct($formobj, $field, $value);
		 //$formobj->fields[$field]['forminfo'].= '&nbsp;';
	}*/
	
	//获取数据，前台显示
	function getShow(){
		if($this->getValue()){//如果发布时设置了文章权限
			$setting = array();
			if($this->formobj->fields[$this->field]['setting']!=''){
				eval('$setting = '.$this->formobj->fields[$this->field]['setting'].';');
			}else $setting['rolepriv'] = 0;
			$this->formobj->addListenter('rolepriv', array($setting['rolepriv']=>$this->getValue()));
		}
	    return shtmlspecialchars($this->getValue());
	}
			
	//设置列表项
	function setOptions(){
	    global $_SGLOBAL;
	    if($_SGLOBAL['_GROUPS']){
		    unset($_SGLOBAL['_GROUPS'][JIEQI_GROUP_ADMIN]);
			$this->addOptionArray($_SGLOBAL['_GROUPS']);
			return true;
		}else return false;
	}	

	//获取字段设置表单项
	function getFieldSetting($data = array()){
	     global $_SGLOBAL;
	     $this->setSetting();
		 
		 $rolepriv = isset($this->setting['rolepriv']) ?$this->setting['rolepriv'] :'view';
		 $defaultvalue = $this->setting['defaultvalue'] ?$this->setting['defaultvalue'] : array();
		 $group = '';
		 $checked = '';
		 $i = 0;
		 foreach($_SGLOBAL['_GROUPS'] as $k=>$v){
		     if($k == JIEQI_GROUP_ADMIN) continue;
			 $i++;
		     if(in_array($k, $defaultvalue)) $checked = 'checked';
			 $group.= "<span style=\"width:100px\"><input type=\"checkbox\" id='defaultvalue' boxid='defaultvalue' name=\"setting[defaultvalue][]\" value=\"{$k}\" {$checked}> {$v} </span>";
			 if($i % 5 ==0) $group.= "<p>";
			 $checked = '';
		 }
	     include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form/groupid.form.php');
	}
	
}
?>