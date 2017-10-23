<?php
/*
    *图片上传/选择类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_image.class.php 12398 2010-04-26 09:47:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_text.class.php');

class Form_image extends Form_text
{
	//设置表单内容
	function setForm(){
		$this->element->setExtra($this->fieldinfo['formattribute']);
		$fieldtext = $this->element->render();
		return $fieldtext.$this->setFormHtml();
	}
	
	//获取数据，前台显示
	function getShow(){
	    global $_OBJ;
		if($this->value && substr(strtolower($this->value),0,7)!='http://'){
		    if(!is_object($_OBJ['category'])) $_OBJ['category'] = &new Category();
			$attachurl = $_OBJ['category']->getAttachurl($this->formobj->category['catid']);
			$this->value = $attachurl.$this->value;
		}
		return $this->value;
	}
	
	//获取表单提交数据
	function getAdd($value){
	    //return shtmlspecialchars($this->getValue());
		global $_SGLOBAL,$_SCONFIG;
		if(!$this->value) return '';
		$this->setSetting();	
		$URL = gethost($_SERVER['HTTP_HOST']);//echo 'aaa';
		//保存外站图片
		if(substr($this->value,0,7)=='http://' && !ereg("\.$URL|http://$URL/i",$this->value) && $this->setting['enablesaveimage']){
		    if(eregi("gif|jpg|jpeg|png|bmp",fileext($this->value))){
			    //include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
				$param = "module={$this->formobj->model['tablename']}&catid={$this->formobj->category['catid']}&uploadtext={$this->field}";
				$filepath = down_remotefile($this->value,$param);
				if(strpos($filepath, '/'.$_SCONFIG['attachdir']) == 0){
					 $this->value = $filepath;
				}
				/*$attachurl = $_SCONFIG['attachurl'] ?$_SCONFIG['attachurl'] :JIEQI_URL;//附件URL服务器
				$gofile=$attachurl."/modules/news/attachment.php?action=upload&remotefile={$this->value}&from=remotefile&uid={$_SGLOBAL['supe_uid']}&dosubmit=1&hash=".formhash();
				if($filepath = jieqi_urlcontents($gofile.($param ? "&{$param}" :''), array('repeat'=>2, 'referer'=>1))){
				    if(strpos($filepath, '/'.$_SCONFIG['attachdir']) == 0){
				       $this->value = $filepath;
					}
				}*/
			}
		}//exit;
		return shtmlspecialchars($this->value);
	}
	
	//删除数据时触发
	function getDelete(){
	    if(strpos($this->value, '/') == 0 && $this->value){
			$this->formobj->vars['_delimgs'][] = $this->value;
			$fdir = dirname(_ROOT_.$this->value).'/'.str_replace( '.', '_*.', basename($this->value) );
			$farr = glob($fdir) ;
			if($farr){
			    $this->formobj->vars['_delimgs'] = array_merge($this->formobj->vars['_delimgs'],$farr);
				$this->formobj->vars['_delimgs'] = str_replace(_ROOT_,'',$this->formobj->vars['_delimgs']);
			}
			$this->formobj->addListenter('delimgs', '');
		}
	}
			
	//设置表单相关
	function setFormHtml(){
	    global $_PAGE,$_SCONFIG;
		$catid = $this->formobj->category['catid'];
		if(!is_object($_OBJ['category'])) $_OBJ['category'] = &new Category();
		$attachurl = $_OBJ['category']->getAttachurl($catid);
		ob_start();
		include($GLOBALS['jieqiModules']['news']['path'].'/include/fields/image.inc.php');
		$str = ob_get_contents();
		ob_end_clean();
		return $str;
	}


}
?>