<?php
/*
    *视频类型字段模型
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_video.class.php 12398 2010-06-21 09:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

include_once($GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_images.class.php');

class Form_video extends Form_images
{
	
	//获取表单提交数据
	function getAdd($value){
	    global $_OBJ,$_SCONFIG,$_SGLOBAL,$_PAGE;
	    $this->setSetting();
		if(is_array($value)){//采集的情况下
			$URL = gethost();
			$param = "module={$this->formobj->model['tablename']}&catid={$this->formobj->category['catid']}&uploadtext={$this->field}";
			$currentarr = $array = array();
			//保存外站文件
			foreach($value as $k=>$fileurl){
			    //修补远程文件链接在有跳转情况下的BUG
				$order = substr_count(strtolower($fileurl), 'http://');
				if($order>1) $fileurl = substr($fileurl, strripos($fileurl, 'http://'));
				$URL = gethost();
				//保存外站文件
				if(substr($fileurl,0,7)=='http://' && !ereg("\.$URL|http://$URL/i",$fileurl) && $this->setting['enablesaveimage']){
					 $remotearr[$k] = $fileurl;
				}else{
				     $currentarr[$k] = basename($fileurl).'|'.$fileurl;
				}
			}
			if($remotearr){
				$retstr = down_remotefile(implode("[page]\n",$remotearr),$param);
				$data = explode("[page]\n", str_replace("\r\n","\n",$retstr));
			    foreach($data as $fileurl){
				    $array[] = basename($fileurl).'|'.$fileurl;
				}
			}
			if($currentarr) $array = array_merge($currentarr, $array);
			$array = implode("[page]\n",$array);
		}else{
			$tempfileurls = getparameter($this->field.'_fileurl');
			if(count($tempfileurls)>0){
				$tempdescriptions = getparameter($this->field.'_description');
				$templistorders = getparameter($this->field.'_listorder');
				$tempdels = getparameter($this->field.'_delete');
				foreach($tempfileurls as $k=>$fileurl){
					if(count($tempdels)>0){//如果有选择删除图片
					   if(in_array($k,$tempdels)){
						   @unlink(_ROOT_.$fileurl);
						   continue;//如果删除
					   }
					}
					$array[$templistorders[$k]]=$tempdescriptions[$k].'|'.$fileurl;
				}
				ksort($array);
				$array = implode("[page]\n",$array);
			}
		}
		if(!$this->setting['enablehtml'] && $array) $array = shtmlspecialchars($array);
		$this->setValue($array);
	    return $this->getValue();
	}
}
?>