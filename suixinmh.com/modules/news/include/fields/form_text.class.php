<?php
/*
    *文本类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_text.class.php 12398 2010-04-18 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
class Form_text extends JieqiObject
{
    var $formobj;             //表单对象
    var $field;               //字段名
	var $value;               //字段值
	var $pagefield = false;   //分页字段
	var $fieldinfo = array(); //字段属性
	var $setting = array();   //字段设置信息
	var $element;             //生成表单数据对象
	var $fieldpre = 'info';   //字段数组命名名称

//构造函数初始化对象
	function __construct($formobj, $field, $value = ''){
	    $this->formobj = $formobj;
	    $this->field = $field;
		$this->setValue($value);
		$this->fieldinfo = $this->formobj->fields[$this->field];
		if($this->formobj->model['pagefield']==$this->field) $this->pagefield = true;
		elseif(!$this->formobj->model['pagefield'] &&  $this->fieldinfo['formtype']=='editor') $this->pagefield = true;
	}

    //设置内容
	function setValue($value){
	    $this->value = $value;
	}

    //获取内容
	function getValue(){
	    return $this->value;
	}
	
    //设置表单对象
    function setFormObject(){
	    $this->fieldinfo['maxlength'] = !$this->fieldinfo['maxlength'] ? 100 :$this->fieldinfo['maxlength'];
	    $this->element = new JieqiFormText('', "{$this->fieldpre}[{$this->field}]", $this->setting['size'], $this->fieldinfo['maxlength'], $this->getValue());
	}
	
	//设置表单内容
	function setForm(){
		$this->element->setExtra($this->fieldinfo['formattribute']);
		return $this->element->render();
	}
	
    //获取表单内容
	function getForm(){
	    return $this->setForm();
	}
	
	//获取表单提交数据
	function getAdd($value){
	    return shtmlspecialchars($this->getValue());
	}
	
	//获取数据，前台显示
	function getShow(){
	    //return shtmlspecialchars($this->getValue());
		return trim($this->getValue());
	}
		
	//删除数据时触发
	function getDelete(){
	    //
	}	
	
	//设置全文搜索的处理数据
	function setFullText(){
	    return $this->value;
	}	
	
	// 全文搜索的处理数据
	function getFullText(){
	    return $this->setFullText();
	}

    //处理采集数据
	function formatCollect($collectObj){
	    return $this->value;
	}

    //处理采集数据
	function getCollectForm($data){
	     global $_SGLOBAL;
	     extract($this->fieldinfo);
	     $star = $this->fieldinfo['minlength'] ? 1 : 0;
		 //$this->setSetting();
		 if($data) $page_action = 'edit';
		 else $page_action = 'add';
		 $tempform = $GLOBALS['jieqiModules']['news']['path'].'/include/fields/form/'.$formtype.'.collect.php';
		 $tempform = is_file($tempform) ? $tempform : $GLOBALS['jieqiModules']['news']['path'].'/include/fields/form/text.collect.php';
		 ob_start();
	     include($tempform);
		 $formstr = ob_get_contents();
		 ob_end_clean();
		 return $formstr;
	}
		
	//设置字段setting
	function setSetting(){
		if($this->fieldinfo['setting']!=''){
			eval('$setting['.$this->field.'] = '.$this->fieldinfo['setting'].';');
			$this->setting = $setting[$this->field];
			if(!$this->getValue() && !$this->formobj->getVar('contentid')) $this->setValue($this->setting['defaultvalue']);
		}
	}
	
    //获取表单内容
	function getContent(){
		$this->setSetting();
		$errortips = $this->fieldinfo['errortips'];
		$this->fieldinfo['errortips'] = $errortips ?$errortips :lang_replace('form_data_error');
		if($this->fieldinfo['minlength']) $this->fieldinfo['formattribute'].='require="true" datatype="limit" msg="'.$this->fieldinfo['errortips'].'" ';
		elseif($errortips) $this->fieldinfo['formattribute'].='require="false" datatype="limit" msg="'.$this->fieldinfo['errortips'].'" ';
		
	  	//设置表单对象
		if(!is_object($this->element)) $this->setFormObject();
		return $this->formatContent($this->getForm());
	}
	
	//获取字段设置表单项
	function getFieldSetting($formtype){
	     global $_OBJ,$_SCONFIG,$_SGLOBAL;
	     $this->setSetting();
		 if($this->fieldinfo['formtype']) $classname = $this->fieldinfo['formtype'];
		 else  $classname = $formtype;
		 //初始化参数
		 $thumb_width = $thumb_height = $attachwimage = $defaultvalue = '';
		 $width = '100%';//编辑器的宽和高
		 $height = 250;
		 $enabledescription = 0; //图片批量上传插件是否开启用图片名自动补充备注
		 $maxnumber = '1';
		 $thumb_enable = $attachwater = '-1';
		 $enablesaveimage = $enablesaveflash = $enablehtml = $multiple = $decimaldigits = $enablesavefile = 0;
		 $maxsize = '1024';
		 $fieldtype = 'CHAR';
		 if(!in_array($classname,array('file','files','video'))) $fileextname = 'jpg,jpeg,gif,png,bmp';
		 elseif($classname=='video') $fileextname = 'rm,wma,wmv,mp4,flv';
		 else $fileextname = '';
		 $savefileext = 'mp3|rar|zip';
		 $isselectimage = $minnumber = $defaulttype = 1;
		 $toolbar = 'basic';
		 $items = '';//lang_replace('model_default_items');
		 if(!in_array($classname,array('select', 'template'))) $size = 50; else $size = 1;
		 $rows = 10;
		 $cols = 60;
		 $format = 'Y-m-d H:i:s';

		 if($this->setting){
		    $page_action = 'edit';
			extract($this->setting);
		 }else $page_action = 'add';
		 $tempform = $GLOBALS['jieqiModules']['news']['path'].'/include/fields/form/'.$classname.'.form.php';
		 $tempform = is_file($tempform) ? $tempform : $GLOBALS['jieqiModules']['news']['path'].'/include/fields/form/text.form.php';
	     include_once($tempform);
	}
	
	function formatContent($content){
		//附加属性
		$farray = array();
		$rarray = array();
		
		$farray[] = "/id=\"{$this->fieldpre}\[.*?\]\"/i";
		$rarray[] = "id=\"{$this->field}\"";
		//if($this->fieldinfo['css']){
		$farray[] = "/class=\"(textarea|text)\"/i";
		$rarray[]=$this->fieldinfo['css'] ?"class=\"{$this->fieldinfo['css']}\"" :'';
		//}
		if(!$this->setting['size']){
			$farray[] = "/size=\".*?\"/i";
			$rarray[]='';
		}
/*		if(!$this->setting['maxlength']){
			$farray[] = "/maxlength=\".*?\"/i";
			$rarray[]='';
		}*/
		return preg_replace($farray, $rarray, $content);
	}
}
?>