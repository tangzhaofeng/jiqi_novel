<?php 
/** 
 * 系统管理->系统工具 * @copyright   Copyright(c) 2014 
 * @author      chengyuan* @version     1.0 
 */ 

class sysToolsModel extends Model{
	//cleancache form
	public function cleancacheForm(){
		global $jieqiLang;
		jieqi_loadlang('cache', JIEQI_MODULE_NAME);
		$_REQUEST = $this->getRequest();
		if(empty($_REQUEST['target'])) jieqi_printfail(LANG_ERROR_PARAMETER);
		elseif($_REQUEST['confirm'] != 1) {
			$url = $this->getAdminurl('sysTools','method=cleancache&target='.$_REQUEST['target'].'&confirm=1');
			jieqi_msgwin(LANG_NOTICE, sprintf($jieqiLang['system']['cache_clean_notice'], $url));
		}
	}
	//cleancache
	public function cleancache(){
		global $jieqiLang;
		jieqi_loadlang('cache', JIEQI_MODULE_NAME);
		$_REQUEST = $this->getRequest();
		if(empty($_REQUEST['target'])) jieqi_printfail(LANG_ERROR_PARAMETER);
		if($_REQUEST['target']=='all'){
			echo '                                                                                                                                                                                                                                                                ';
			echo $jieqiLang['system']['start_clean_cache'];
			ob_flush();
			flush();
			$this->jieqi_clean_pagecache();
			echo $jieqiLang['system']['start_clean_blockcache'];
			ob_flush();
			flush();
			$this->jieqi_clean_blockcache();
			echo $jieqiLang['system']['start_clean_compiled'];
			ob_flush();
			flush();
			$this->jieqi_clean_compiled();
			jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['cache_clean_success']);
		}elseif($_REQUEST['target']=='cache'){
			echo '                                                                                                                                                                                                                                                                ';
			echo $jieqiLang['system']['start_clean_cache'];
			ob_flush();
			flush();
			$this->jieqi_clean_pagecache();
			
			jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['cache_clean_success']);
		}elseif($_REQUEST['target']=='blockcache'){
			echo '                                                                                                                                                                                                                                                                ';
			echo $jieqiLang['system']['start_clean_blockcache'];
			ob_flush();
			flush();
			$this->jieqi_clean_blockcache();
			jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['cache_clean_success']);
		}elseif($_REQUEST['target']=='compiled'){
			echo '                                                                                                                                                                                                                                                                ';
			echo $jieqiLang['system']['start_clean_compiled'];
			ob_flush();
			flush();
			$this->jieqi_clean_compiled();
			jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['cache_clean_success']);
		}else{
			jieqi_printfail(LANG_ERROR_PARAMETER);
		}
	}
	
	function jieqi_clean_blockcache(){
		global $jieqiCache;
		global $jieqiModules;
		if(is_a($jieqiCache, 'JieqiCacheMemcached')){
			$jieqiCache->clear(JIEQI_CACHE_PATH);
		}else{
			foreach ($jieqiModules as $mod){
				$dirname = JIEQI_CACHE_PATH.$mod['dir'].'/templates';
				$handle = @opendir($dirname);
				while ($file = @readdir($handle)) {
					if($file == 'blocks' && is_dir($dirname . DIRECTORY_SEPARATOR . $file)) jieqi_delfolder($dirname . DIRECTORY_SEPARATOR . $file, true);
				}
				@closedir($handle);
			}
		}
	}
	
	function jieqi_clean_pagecache(){
		global $jieqiCache;
		global $jieqiModules;
		if(is_a($jieqiCache, 'JieqiCacheMemcached')){
			$jieqiCache->clear(JIEQI_CACHE_PATH);
		}else{
			foreach ($jieqiModules as $mod){
				$dirname = JIEQI_CACHE_PATH.$mod['dir'].'/templates';
				$handle = @opendir($dirname);
				while ($file = @readdir($handle)) {
					if($file != '.' && $file != '..' && $file != 'blocks'){
						if (is_dir($dirname . DIRECTORY_SEPARATOR . $file)){
							jieqi_delfolder($dirname . DIRECTORY_SEPARATOR . $file, true);
						}else{
							@unlink($dirname . DIRECTORY_SEPARATOR . $file);
						}
					}
				}
				@closedir($handle);
			}
		}
	}
	
	function jieqi_clean_compiled(){
		jieqi_delfolder(JIEQI_COMPILED_PATH, false);
	}
} 
?>