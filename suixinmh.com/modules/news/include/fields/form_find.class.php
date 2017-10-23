<?php
/*
    *动态列表类型字段模型
	[Cms News] (C) 2010-2012 Cms Inc.
	$Id: form_find.class.php 12398 2011-03-21 09:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_textarea.class.php');

class Form_find extends Form_textarea
{
	
	//设置表单内容
	function setForm(){
	    global $_SCONFIG;
		$this->fieldinfo['formattribute'] .=' style="display:none"';
		$this->element->setExtra($this->fieldinfo['formattribute']);
		$fieldtext = $this->element->render();
		return $fieldtext.$this->setFormHtml();

	}
	
	//获取表单提交数据
	function getAdd($value){
		$listenter = false;
		foreach($value['fields'] as $v){
		    if($v['where']){
			    $listenter = true;
				break;
			}
		}
		if($listenter){
		    $value = jieqi_funtoarray('stripslashes',$value);
		    $envalue = is_array($value) ? serialize($value) : '';
			$this->setValue($envalue);
			$this->formobj->vars['_find'][] = $value;
			$this->formobj->addListenter('find', '');
			return saddslashes($envalue);
		}else return '';
	}
		
	//设置表单相关
	function setFormHtml(){
	    global $_OBJ,$_SCONFIG,$_SGLOBAL;
		$vars = array();
		if($this->formobj->getVar('contentid')){
			if(!$set = unserialize($this->getValue())){
			    $set = $this->setting;
				unset($set['field']);
			}
		}else{
		    $set = $this->setting;
		}
		$set = shtmlspecialchars($set);
		ob_start();
		include($_SGLOBAL['news']['path'].'/include/fields/'.$this->fieldinfo['formtype'].'.inc.php');
		$str = ob_get_contents();
		ob_end_clean();
		return $str;
	}
	
	//获取数据，前台显示
	function getShow($data = array()){
	    global $_OBJ,$_PAGE;
		
		$cachefile = CACHE_PATH."/modules/"._MODULE_."/templates/blocks/field/".$this->formobj->getVar('contentid').'/'.$this->field.".php";
		if(USE_CACHE && is_file($cachefile) && _NOW_ - filemtime($cachefile) < CACHE_LIFETIME){
		    include_once($cachefile);
			$ret = $_SGLOBAL[$this->field];
		}else{
			if($this->getValue()) $tmp = unserialize($this->getValue());
			else return array();
			$param = array();
			foreach($tmp['fields'] as $field=>$v){
				if(!$field || !$v['where']) continue;
				if($field=='keywords') $field = 'tag';
				if($field=='modelid') $field = 'mid';
				$param[$field] = $v['where'];
			}
			$ret = array();
			if($tmp['blocknum']){
				$param['pagesize'] = $tmp['blocknum'];
				$param['pagestr'] = $tmp['pagestr'];
				$_OBJ['content']->getData($param, true);
				$ret['rows'] = $_PAGE['articlerows'];
			}
			$page = 'index';
			eval('$linkurl = "'.saddslashes($tmp['urlrule']).'";');
			$url = $_OBJ['content']->getUrl($data,1);
			$url = substr($url, 0, strrpos($url, '/')+1).$linkurl;
			$url = dirname($url).'/';
			$ret['url_more'] = $url;
			if(USE_CACHE) cache_write("{$this->field}", "_SGLOBAL['{$this->field}']", $ret, 0, $cachefile);
		}
		if($this->formobj->model['pagefield']==$this->field) $this->formobj->setVar('___content', array("content"=>$ret));
		return $ret;
	}
		
	//获取全文搜索的处理数据
	function setFullText(){
		return '';
	}
}
?>