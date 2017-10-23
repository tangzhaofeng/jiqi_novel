<?php
/*
    *多文件上传类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_files.class.php 12398 2010-06-21 09:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_textarea.class.php');

class Form_files extends Form_textarea
{
	//设置表单内容
	function setForm(){
	    global $_SCONFIG;
		$this->element->setExtra($this->fieldinfo['formattribute']);
		$fieldtext = $this->element->render();
		return $fieldtext.$this->setFormHtml();

	}
	
/*	function getValue(){
	    return shtmlspecialchars($this->value);
	}*/
	
	//设置表单相关
	function setFormHtml(){
	    global $_OBJ,$_SCONFIG;
		$catid = $this->formobj->category['catid'];
		$modelid = $this->formobj->category['modelid'];
		if(!is_object($_OBJ['category'])) $_OBJ['category'] = &new Category();
		$attachurl = $_OBJ['category']->getAttachurl($catid);
		$CONTENT_POS = strpos($this->getValue(), '[page]');
		if($CONTENT_POS !== false) $this->setValue(str_replace('[page]', '', $this->getValue()));
		ob_start();
		include($GLOBALS['jieqiModules']['news']['path'].'/include/fields/files.inc.php');
		$str = ob_get_contents();
		ob_end_clean();
		return $str;
	}
	
	//获取数据，前台显示
	function getShow(){
	    global $_OBJ;
	    //return shtmlspecialchars($this->getValue());
		if($this->value){
		    $CONTENT_POS = strpos($this->getValue(), '[page]');
			if($CONTENT_POS !== false) $split = "[page]\n";
			else $split = "\n";
		    $data = explode($split, str_replace("\r\n","\n",$this->getValue()));
			$ret = array();
			if(!is_object($_OBJ['category'])) $_OBJ['category'] = &new Category();
			$attachurl = $_OBJ['category']->getAttachurl($this->formobj->category['catid']);
			foreach($data as $k=>$v){
			    if(strpos($v, '|')){
				    $fileurl = substr($v, strrpos($v,"|"));
					$description = str_replace($fileurl, '', $v);
					$fileurl = str_replace('|', '', $fileurl);
					if(strpos($fileurl, '://') === false){
					    if(strpos($fileurl, '@')){
						     $other = substr($fileurl, strrpos($fileurl,"@")+1);
							 $fileurl = str_replace('@'.$other, '', $fileurl);
						}
						$ret[] = array('description'=>$description,'url'=>$attachurl.$fileurl,'fileurl'=>$fileurl,'other'=>$other);
					}else{
					    $ret[] = array('description'=>$description,'url'=>$fileurl,'fileurl'=>$fileurl);
					}
				}
			}
		}
		if($this->pagefield) $this->formobj->setVar('___content', array("content"=>$ret));
		return $ret;
	}
	
	//删除数据时触发
	function getDelete(){
	    if($this->value){
			$CONTENT_POS = strpos($this->getValue(), '[page]');
			if($CONTENT_POS !== false) $split = "[page]\n";
			else $split = "\n";
		    $tempdata = explode($split, str_replace("\r\n","\n",$this->getValue()));
			foreach($tempdata as $v){
			    if(strpos($v, '|')!==false){
				    $fileurl = substr($v, strrpos($v,"|")+1);
					if(strpos($fileurl, '/') == 0){
						$this->formobj->vars['_delimgs'][] = $fileurl;
					}
				}
			}
			$this->formobj->addListenter('delimgs', $this->formobj->vars['_delimgs']);
		}
	}
	
}
?>