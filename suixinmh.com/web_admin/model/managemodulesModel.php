<?php
/**
 * 系统管理->模块配置文件 * @copyright   Copyright(c) 2014
 * @author      gaoli* @version     1.0
 */

class managemodulesModel extends Model{
	public function main($params = array()){
		global $jieqiModules;
//		$_REQUEST = $this->getRequest();

		$this->addLang('system', 'modules');
		$jieqiLang['system'] = $this->getLang('system');
		if(!empty($params['dosubmit'])){
			foreach($params['jieqiModules'] as $k=>$v) $jieqiModules[$k]=$v;
			$jieqiModules=$params['jieqiModules'];
			$this->jieqi_save_modconfig($jieqiModules);
			jieqi_jumppage($this->getAdminurl('managemodules'),LANG_DO_SUCCESS,$jieqiLang['system']['modules_config_saved']);
		}
		if(!isset($jieqiModules)) $jieqiModules=array();
		$fileroot=JIEQI_ROOT_PATH.'/modules';
		$handle = opendir($fileroot);
		$changeflag=false;
		//检测
		while (false !== ($file = readdir($handle))){
			if($file[0] != '.' && is_dir($fileroot.'/'.$file)){
				if(!isset($jieqiModules[$file])){
					$changeflag=true;
					$jieqiModules[$file]= array('caption'=>$file, 'dir'=>'', 'path'=>'', 'url'=>'', 'theme'=>'', 'publish'=>'1');
				}
			}
		}
		closedir($handle);
		if($changeflag){
			$this->jieqi_save_modconfig($jieqiModules);
		}


		$fileroot=JIEQI_ROOT_PATH.'/themes';
		$handle = opendir($fileroot);
		$themes=array();
		while (false !== ($file = readdir($handle))){
			if($file[0] !='.' && is_dir($fileroot.'/'.$file))
			{
				$themes[]=$file;
			}
		}
		closedir($handle);
		$themes = $themes;
		$jieqiModules = $jieqiModules;
		return array('blockconfigs'=>$blockconfigs,
			'themes'=>$themes,
			'jieqiModules'=>$jieqiModules
		);
	}

	//单独设置写配置文件规范
	public function jieqi_save_modconfig($jieqiModules){
		$file=JIEQI_ROOT_PATH.'/configs/modules.php';
		$data='<?php'."\r\n";
		foreach($jieqiModules as $k=>$v){
			$tmpvar = $k=='system' ? '' : '/modules/'.$k;
			if($v['dir']==$tmpvar) $v['dir']='';
			if($v['path']==JIEQI_ROOT_PATH.$tmpvar) $v['path']='';
			if($v['url']==JIEQI_LOCAL_URL.$tmpvar) $v['url']='';
			if($v['theme']==JIEQI_THEME_SET) $v['theme']='';

			$data.='$jieqiModules[\''.jieqi_setslashes($k, '"').'\'] = array(\'caption\'=>\''.jieqi_setslashes($v['caption'], '"').'\',\'siteid\'=>\''.jieqi_setslashes($v['siteid'], '"').'\',\'minsortid\'=>\''.jieqi_setslashes($v['minsortid'], '"').'\', \'maxsortid\'=>\''.jieqi_setslashes($v['maxsortid'], '"').'\',  \'dir\'=>\''.jieqi_setslashes($v['dir'], '"').'\', \'path\'=>\''.jieqi_setslashes($v['path'], '"').'\', \'url\'=>\''.jieqi_setslashes($v['url'], '"').'\', \'theme\'=>\''.jieqi_setslashes($v['theme'], '"').'\', \'publish\'=>\''.jieqi_setslashes($v['publish'], '"').'\');'."\r\n";
		}
		$data.='?>';
		jieqi_writefile($file, $data);
	}
}
?>