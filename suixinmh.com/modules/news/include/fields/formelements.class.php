<?php
/*
    *通用的字段类型处理类[获取表单元素]
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: form_element.class.php 12398 2010-04-18 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
include_once($GLOBALS['jieqiModules']['news']['path'].'/include/power.class.php');

///获取表单元素列表
class FormElements extends JieqiObject
{
    var $modelid;         //表单所属模型ID
	var $model = array(); //表单所属数据模型详细配置
    var $fields = array(); //表单所属数据模型字段数组
	var $category  = array(); //表单所属栏目详细配置
	var $vars  = array();    //所有数据存储容器
	
	function FormElements($modelid, $catid = ''){
	    global $_OBJ;
		
		$this->modelid = $modelid;
		
		//初始化模型列表
		if(!is_object($_OBJ['model'])) $_OBJ['model'] = &new Model();
		$this->model = $_OBJ['model']->get($this->modelid);
		//该项模型是否开启全文搜索
        $this->setVar('issearch' ,$_OBJ['model']->isSearch($this->modelid));
		//前台发布信息是否需要审核
		$this->setVar('ischeck' ,$_OBJ['model']->isCheck($this->modelid));
		//初始化栏目数据对象
		if($catid){
		    if(!is_object($_OBJ['category']))  $_OBJ['category'] = &new Category();
			$this->category = $_OBJ['category']->get($catid);
		}
		
		//加载模型字段列表
		$this->setFields($_OBJ['model']->getfields($this->modelid));
	}
	
	//设置数据模型字段列表
	function setFields($fields = array())
	{
		$this->fields = $fields;
	}
	//获取数据模型字段列表
	function getFields()
	{
		return $this->fields;
	}

	//添加数据
	function add($data, $ischeck = true, $errormode = JIEQI_ERROR_DIE){
		global $_SGLOBAL, $_SN, $_OBJ, $_SCONFIG;
	    if(!is_array($data)) return false;
		//表单处于修改状态
		if($ischeck){
			if($data['contentid']){
				$this->setVar('contentid' ,$data['contentid']);
				if(defined('IN_ADMIN') && IN_ADMIN) $this->checkPower('edit',false);//检查是否有修改文章的权限
			}else{
				if(defined('IN_ADMIN') && IN_ADMIN) $this->checkPower('add',false);//检查是否有增加文章的权限
			}
		}
		$this->vars['data'] = $data;
		//定义全文搜索字段的存储容器
		$fulltextArray = array();
		foreach($this->getFields() as $field => $v){
		    if(!$field || $v['disabled'] || !array_key_exists($field,$this->fields)) continue;
			if(defined('IN_ADMIN')){
				if($v['iscore']) continue;//核心字段不充许编辑
			}else{
				if($v['iscore'] || !$v['isadd']) continue;
			}
			//检查内容合法性
			if($ischeck){
				if(!$this->checkValue($field, $data[$field], $errormode)) return false;
			}
			$incFile = $GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_'.$v['formtype'].'.class.php';	
			if(!is_file($incFile)) continue;
			include_once($incFile);
			$className = "Form_{$v['formtype']}";
			$elementObject = new $className($this, $field, $data[$field]);
			if($v['issystem']) {
			    $this->vars['__issystem'][$field] = $elementObject->getAdd($data[$field]);
			}else{
			    $this->vars['__nosystem'][$field] = $elementObject->getAdd($data[$field]);
			}
			//如果该字段加入全文搜索
			if($this->getVar('issearch')){
				if($data['status']==99 && $v['isfulltext']) $fulltextArray[$field] = $elementObject->getFullText();
			}
		}
		
		$this->vars['__issystem']['catid'] = $data['catid'];
		$this->vars['__issystem']['status'] = $data['status'];
		$this->vars['__issystem']['updatetime'] = time();
		
		if(!is_object($_OBJ['content'])) $_OBJ['content'] = &new Content();
		
		if(!$this->getVar('contentid')){//增加数据
			$this->vars['__issystem']['userid'] = $_SGLOBAL['supe_uid'];
			$this->vars['__issystem']['username'] = $_SN[$_SGLOBAL['supe_uid']];
		    $ac = 'add';
			if($contentid = $_OBJ['content']->add($this->vars['__issystem'], $this->vars['__nosystem']) ){
			    $this->setVar('contentid', $contentid);
				//生成文件
			    if($data['status']==99 && $_OBJ['content']->isHtml(array('catid'=>$data['catid']))){
				   if(!is_object($_OBJ['html'])) $_OBJ['html'] = &new Html();
				   $_OBJ['html']->content($contentid);
			    }
			}else return false;
		}else{
		    $ac = 'edit';
		    $this->vars['__issystem']['contentid'] = $data['contentid'];
			if(!$_OBJ['content']->edit($this->vars['__issystem'], $this->vars['__nosystem']) ) return false;
			else{
			    //生成文件
			    if($data['status']==99 && $_OBJ['content']->isHtml(array('catid'=>$data['catid']))){
				   if(!is_object($_OBJ['html'])) $_OBJ['html'] = new Html();
				   $_OBJ['html']->content($data['contentid']);
			    }elseif($_OBJ['content']->isHtml(array('catid'=>$data['catid']))){
				    jieqi_delfile($_OBJ['content']->getDir($data).$_OBJ['content']->getUrlrule($data, 1));
				}
			}
		}
		if($this->getVar('issearch') && $data['status']==99) $this->addListenter('_fulltext', $fulltextArray);
		$this->listener($ac);//监听事件
		if($this->getVar('issearch') && $ac == 'edit' && $data['status']!=99){
		    $this->unListenter();
		    $this->addListenter('_fulltext', $this->getVar('contentid'));
			$this->listener('delete');//监听事件
		}
		return true;
	}
	
	//删除一条数据
	function delete($data){
	    global $_OBJ, $_SCONFIG;
	    if(!is_array($data)) return false;
		if(defined('IN_ADMIN') && IN_ADMIN) $this->checkPower('delete',false);//检查是否有删除文章的权限
		//表单处于删除状态
		if(isset($data['contentid'])) $this->setVar('contentid' ,$data['contentid']);
		else return false;
		//检索所以字段，设定触发事件
		foreach($this->getFields() as $field => $v){
		    if(!$field || $v['disabled'] || !array_key_exists($field,$this->fields)) continue;
			if(defined('IN_ADMIN') && IN_ADMIN){
				if($v['iscore']) continue;//核心字段不充许编辑
			}else{
				if($v['iscore'] || !$v['isadd']) continue;
			}
			$incFile = $GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_'.$v['formtype'].'.class.php';
			if(!is_file($incFile)) continue;
			include_once($incFile);
			$className = "Form_{$v['formtype']}";
			$elementObject = new $className($this, $field, $data[$field]);
			$elementObject->getDelete();
		}
		if(!is_object($_OBJ['content'])) $_OBJ['content'] = &new Content();
		if($_OBJ['content']->delete($data)){
		    if($_OBJ['content']->isHtml($data)) jieqi_delfile($_OBJ['content']->getDir($data).$_OBJ['content']->getUrlrule($data, 1));
		    if($this->getVar('issearch')) $this->addListenter('_fulltext', $data['contentid']);
		    $this->listener('delete');//监听事件
			return true;
		}else {
		    return false;
		}
	}
	
	//显示数据
	function show($data){
	    global $_OBJ, $jieqiGroups;
	    if(!is_array($data) || $data['status']!=99 || $this->model['disabled']) return false; //未审核内容返回FALSE
		if(defined('IN_ADMIN') && IN_ADMIN) $this->checkPower('view',false);//检查后台用户是否有浏览权限
		//print_r($this->getPower('show'));
		if($data['contentid']) $this->setVar('contentid' ,$data['contentid']);
		else return false;
		$info = array();
	    foreach($this->getFields() as $field=>$v){
		    if(!$field || $v['disabled']) continue;
			$incFile = $GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_'.$v['formtype'].'.class.php';
			if(!is_file($incFile)) continue;
			include_once($incFile);
			//$value = isset($data[$field]) ? $data[$field] : '';//shtmlspecialchars
			$className = "Form_{$v['formtype']}";
			$elementObject = new $className($this, $field, $data[$field]);
            $info[$field] = $elementObject->getShow($data);
			if($this->getVar('__'.$field)) $info['_'.$field] = $this->getVar('__'.$field);//处理过的字段内容
		}
		$this->listener();//监听事件
		
		////=====文章自定义权限覆盖栏目定义权限=====
		if($this->getVar('__rolearray')) $this->category['setting']['show'] = $this->getVar('__rolearray');
		$ret = array_merge($data, $info);
		$ret['_rolearray'] = $this->category['setting']['show'];
		$ret['_rolepriv'] = $this->getVar('__rolepriv');
		$ret['_showrole'] = $this->checkPower('show', true);
		if($this->getVar('__showtemplate')) $ret['_showtemplate'] = $this->getVar('__showtemplate');
		else $ret['_showtemplate'] = $this->category['setting']['template_show'] ?$this->category['setting']['template_show'] :$this->model['template_show'];
		return $ret;
	}
	
	//获取表单对象数组
	function getElements($data = array()){
		//模型处于禁止状态
		if($this->model['disabled']) jieqi_printfail(lang_replace('model_not_exists')); 
		if($data['contentid']){
			$this->setVar('contentid' ,$data['contentid']);
			if(defined('IN_ADMIN') && IN_ADMIN) $this->checkPower('edit',false);//检查是否有修改文章的权限
		}else{
		    if(defined('IN_ADMIN') && IN_ADMIN) $this->checkPower('add',false);//检查是否有发布文章的权限
		}
		$info = array();
	    foreach($this->getFields() as $field=>$v){
		    if(!$field || $v['disabled']) continue;
			if(defined('IN_ADMIN') && IN_ADMIN){
				if($v['iscore']) continue;//核心字段不充许编辑
			}else{
				if($v['iscore'] || !$v['isadd']) continue;
			}
			$incFile = $GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_'.$v['formtype'].'.class.php';
			if(!is_file($incFile)) continue;
			include_once($incFile);
			//$value = isset($data[$field]) ? $data[$field] : '';//shtmlspecialchars
			$className = "Form_{$v['formtype']}";
			$elementObject = new $className($this, $field, $data[$field]);
			$form = $elementObject->getContent();
			if($form !== false)
			{
				if(defined('IN_ADMIN') && IN_ADMIN)
				{
					if($v['isbase'])
					{
						$star = $v['minlength'] ? 1 : 0;
						$info['base'][$field] = array('name'=>$v['name'], 'tips'=>$v['tips'], 'form'=>$form, 'star'=>$star, 'forminfo'=>$this->fields[$field]['forminfo'], 'formtype'=>$v['formtype'], 'isselect'=>$v['isselect']);
					}
					else
					{
						$star = $v['minlength'] ? 1 : 0;
						$info['senior'][$field] = array('name'=>$v['name'], 'tips'=>$v['tips'], 'form'=>$form, 'star'=>$star, 'forminfo'=>$this->fields[$field]['forminfo'], 'formtype'=>$v['formtype'], 'isselect'=>$v['isselect']);
					}
				}else{
					$star = $v['minlength'] ? 1 : 0;
					$info[$field] = array('name'=>$v['name'], 'tips'=>$v['tips'], 'form'=>$form, 'star'=>$star, 'forminfo'=>$this->fields[$field]['forminfo'], 'formtype'=>$v['formtype'], 'isselect'=>$v['isselect']);
				}
			}
		}
		//$this->listener();//监听事件
		return $info;
	}
	
	//获取采集表单字段
	function getCollectElements($data = array()){
		//模型处于禁止状态
		if($this->model['disabled']) jieqi_printfail(lang_replace('model_not_exists'));
		$info = array();
	    foreach($this->getFields() as $field=>$v){
		    if(!$v['isselect'] || in_array($field, array('contentid','template','url','prefix','posid','pages','userid','groupid') ) ) continue;
			$incFile = $GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_'.$v['formtype'].'.class.php';
			if(!is_file($incFile)) continue;
			include_once($incFile);
			$className = "Form_{$v['formtype']}";
			$elementObject = new $className($this, $field, '');
			$form = $elementObject->getCollectForm($data[$field]);
			if($v['isbase']) $info['base'][$field] = $form;
			else $info['senior'][$field] = $form;
		}
		return $info;
	}
	
	//索引数据
	function fulltext($data){
		global $_SGLOBAL, $_SN, $_OBJ;
	    if(!is_array($data) || !$this->getVar('issearch')) return false;
        $this->setVar('contentid' ,$data['contentid']);
		//定义全文搜索字段的存储容器
		$fulltextArray = array();
		foreach($this->getFields() as $field => $v){
		    if(!$field || $v['disabled'] || !array_key_exists($field,$this->fields)) continue;
			$incFile = $GLOBALS['jieqiModules']['news']['path'].'/include/fields/form_'.$v['formtype'].'.class.php';	
			if(!is_file($incFile)) continue;
			include_once($incFile);
			$className = "Form_{$v['formtype']}";
			$elementObject = new $className($this, $field, $data[$field]);
			if($v['issystem']) {
			    $this->vars['__issystem'][$field] = $data[$field];
			}else{
			    $this->vars['__nosystem'][$field] = $data[$field];
			}
			//如果该字段加入全文搜索
			if($this->getVar('issearch')){
			    if($v['isfulltext']) $fulltextArray[$field] = $elementObject->getFullText();
			}
		}
		$this->vars['__issystem']['updatetime'] = $data['updatetime'];
		$this->addListenter('_fulltext', $fulltextArray);
		$this->listener('edit');//监听事件
		return true;
	}
		
	//检查表单数据的合法性
	function checkValue($field, $value, $mode = JIEQI_ERROR_DIE){
	    $statu = true;
	    if($this->fields[$field]['minlength']){
		    if(strlen($value) < $this->fields[$field]['minlength']) $statu = false;
		}
		if($statu && $this->fields[$field]['maxlength']){
		    if(strlen($value) > $this->fields[$field]['maxlength']) $statu = false;
		}
		if($statu && $value && $this->fields[$field]['pattern']){
		    if(!preg_match($this->fields[$field]['pattern'], $value))  $statu = false;
		}
		if(!$statu){
			$this->raiseError($this->fields[$field]['errortips'] ?$this->fields[$field]['errortips'] :lang_replace('form_data_error'), $mode);
			return false;
		}else{
		    return true;
		}
	}
	
	//监听器
	function listener($ac = 'add'){
	    if($listener = $this->getListenter()){ //如果字段有触发事件
		$ret = array();
		    foreach($listener as $k=>$v){
			     $temp = '__listenter_'.$k;
			     $ret[] = $this->$temp($v, $ac);
			}
		}
		return $ret;
	}
	//获取监听事件
	function getListenter($action = ''){
	    if($action) return $this->vars['__listenter'][$action];
		else  return $this->vars['__listenter'];
	}	
	//注销事件
	function unListenter(){
	    unset($this->vars['__listenter']);
	}
	//添加监听器
	function addListenter($action, $value){
	    if(!$action) return false;
	    $this->vars['__listenter'][$action] = $value;
	}
	//监听事件---删除文章触发事情
	function __listenter_delimgs($value, $ac = ''){
	    if($value){
			$value = array_values(array_unique($value));
			foreach($value as $filename){
			    if(is_file(_ROOT_.$filename)) jieqi_delfile(_ROOT_.$filename);
			}
		}
	}
	
	//监听事件---动态列表生成列表
	function __listenter_find($value, $ac = ''){
	     global $_SGLOBAL, $_OBJ, $_PAGE, $_SCONFIG;
	     if($ac!='delete'){
			 //开始按条件生成列表
			 if(!is_object($_OBJ['content'])) $_OBJ['content'] = new Content();	
			 $filename = $_OBJ['content']->getDir($this->vars['data']).$_OBJ['content']->getUrlrule($_PAGE['data'], 1);
			 foreach($this->vars['_find'] as $list){
			     if(!$list['pagesize']) continue;
			     $param = array();
			     foreach($list['fields'] as $field=>$v){
				     if(!$field || !$v['where']) continue;
					 $param[$field] = $v['where'];
				 }	
				 $param['pagesize'] = $list['pagesize'];
				 $param['pagestr'] = $list['pagestr'];
				 //if(!$_OBJ['content']->getData($param, true, true)) continue;
				 //$totalpage = @ceil($_OBJ['content']->getVar('totalcount')/$param['pagesize']);
				 if($list['maxpage']){
					 $fileurl = dirname($filename).'/'.$list['urlrule'];
					 $page = '<{$page}>';
					 eval('$linkurl = "'.saddslashes($list['urlrule']).'";');
					 $url = $_OBJ['content']->getUrl($this->vars['data'],1);
					 $linkurl = substr($url, 0, strrpos($url, '/')+1).$linkurl;
					 //echo $linkurl;exit;
					 $dir = dirname($fileurl).'/';
					 
					 if(!is_dir($dir)) if(!jieqi_createdir($dir, 0777, true)) return false;
					 
					 //if($list['maxpage'] && $totalpage>$list['maxpage']){
						 //$totalpage=$list['maxpage'];
						 //$_OBJ['content']->jumppage->setVar('totalpage', $totalpage);
					 //}
				     for($i=1;$i<=$list['maxpage'];$i++){
					     $page = $i<2 ? 'index' : $i;
					     eval('$file = "'.saddslashes($fileurl).'";');
							$file = str_replace('//', '/', $file);
							$tmpcot = str_repeat('../', substr_count(str_replace($_SGLOBAL['rootpath'],'',$file), '/')-1);
$content='<?php
//加载页面预处理文件
include_once(\''.$tmpcot.'global.php\');
include_once(\''.$tmpcot.'modules/news/common.php\');
include_once(\''.$tmpcot.'modules/news/include/loadclass.php\');
$_PAGE[\'_GET\'][\'contentid\'] = '.$this->getVar('contentid').';
$_PAGE[\'_GET\'][\'mid\'] = \''.$param['modelid'].'\';
$_PAGE[\'_GET\'][\'catid\'] = \''.$param['catid'].'\';
$_PAGE[\'_GET\'][\'tag\'] = \''.$param['keywords'].'\';
$_PAGE[\'_GET\'][\'page\'] = '.$i.';
$_PAGE[\'title\'] = \''.$this->vars['data']['title'].'\';
$_PAGE[\'pagesize\'] = '.$list['pagesize'].';
$_PAGE[\'totalpage\'] = '.$list['maxpage'].';
$_PAGE[\'template\'] = \''.$list['listtemplate'].'\';
$_PAGE[\'linkurl\'] = \''.$linkurl.'\';
$_PAGE[\'emptyonepage\'] = true;
$_PAGE[\'cacheid\'] = md5(serialize($_PAGE).md5(serialize($_PAGE[\'_GET\'])));
//设定缓存文件路径
$cachefile = CACHE_PATH."/modules/"._MODULE_."/top/".$_PAGE[\'cacheid\'].".html";
if(USE_CACHE && is_file($cachefile) && _NOW_ - filemtime($cachefile) < CACHE_LIFETIME){
    include_once($cachefile);exit;
}else{
	if(!is_object($_OBJ[\'content\'])) $_OBJ[\'content\'] = new Content();
	$_PAGE[\'data\'] = $_OBJ[\'content\']->getContent($_PAGE[\'_GET\'][\'contentid\'], 1);
	//处理模板
	include_once($_SGLOBAL[\'news\'][\'path\'].\'/source/top.inc.php\');
	include_once($_SGLOBAL[\'rootpath\'].\'/footer.php\');
}
?>';
						swritefile($file, $content);
					 }
				 }
			 }
		     //cache_write('aa.txt','aa',$this->vars['_find']);
			 //echo $_OBJ['content']->getDir($this->vars['data']);
			 //echo $_OBJ['content']->getUrlrule($this->vars['data'],1);
			 //echo $this->formobj->vars['data']['contentid'];
			 //exit($this->vars['_find'][0]['pagesize']);
		 }
	}
	//监听事件---写入全文搜索索引
	function __listenter__fulltext($value, $ac = ''){
	    global $_SGLOBAL, $_OBJ;
	    include_once($_SGLOBAL['search']['path'].'/include/search.class.php');
		if(!is_object($_OBJ['search'])) $_OBJ['search'] = new Search();
		if($ac=='delete') $_OBJ['search']->delete(array('type'=>$this->model['tablename'], 'tid'=>$value));
		else{
			$data = array();
			$data['tid'] = $this->getVar('contentid');
			$data['title'] = $this->vars['__issystem']['title'];
			$data['keywords'] = $this->vars['__issystem']['keywords'];
			$data['data'] = $value ?implode(' ', $value) :'';
			$data['type'] = $this->model['tablename'];
			$data['module'] = 'news';
			$data['time'] = $this->vars['__issystem']['updatetime'];
			$info = array_merge($this->vars['__issystem'], $this->vars['__nosystem']);
			$info['contentid'] = $data['tid'];
			$data['url'] = jieqi_geturl('news', 'show', $info, 1);
			if($ac=='add') $_OBJ['search']->add($data);
			elseif($ac=='edit'){
			  $_OBJ['search']->edit(array('type'=>$this->model['tablename'], 'tid'=>$data['tid']), $data);
			}
		}
	}
	//监听事件---文章自定义模板
	function __listenter_template($value, $ac = ''){
	    if($value) $this->setVar('__showtemplate', $value);
	}
	//监听事件---文章自定义权限
	function __listenter_rolepriv($value, $ac = ''){
	     if(is_array($value)){
		     foreach($value as $k=>$v){
				$this->setVar('__rolepriv', $k);
				$this->setVar('__rolearray', $v);
			 }
		 }
	}
    //监听事件---处理推荐位
	function __listenter_posid($value, $ac = 'add'){
	    global $_OBJ;
		if(!is_object($_OBJ['position'])) $_OBJ['position'] = &new Position();
		$_OBJ['position']->updatePosid($this->getVar('contentid'), $value, $ac);
	}
	
	//检查权限
	function checkPower($key, $isreturn=true){
		//初始化权限对象
		$power = new Power($key, $this->category['setting'][$key], $isreturn);
		$this->setVar('__'.$key, $power->power[$key]);
		if($isreturn) return $this->getPower($key);
	}	
	//获取权限数据
	function getPower($key){
	    return $this->getVar('__'.$key);
	}

}
?>