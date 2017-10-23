<?php 
/** 
 * 系统管理->数据库维护 * @copyright   Copyright(c) 2014 
 * @author      chengyuan* @version     1.0 
 */ 

class dbmanageModel extends Model{
	//export view form
	public function exportForm(){
		global $jieqiLang;
		$data = array();
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		jieqi_loadlang('database', JIEQI_MODULE_NAME);
		$data['option']=1;
		//判断是否允许dump方式备份
		$shelldisabled=function_exists('shell_exec')?'':'disabled';
		/*anchor:28
		//判断是否支持zip压缩
		$zipdisabled=function_exists('gzcompress')?'':'disabled';
		*/
		//定义默认导出文件名
		$defaultfilename=date('ymd').'_'.jieqi_random(8);
		//处理表格列表表单
		$num=0;
		$tablestring='<div id="tablelist" style="display:none;"><table border="0"><tr>';
		foreach($this->jieqi_fetchtablelist(JIEQI_DB_PREFIX) as $table){
			$tablestring.=($num%3==0)?'</tr><tr><td style="text-align:left;font-size:12px;font-weight:normal;"><input type="checkbox" name="tablearray[]" id="tablearray[]" value="'.$table['Name'].'" />'.$table['Name'].'</td>':'<td style="text-align:left;font-size:12px;font-weight:normal;"><input type="checkbox" name="tablearray[]" id="tablearray[]" value="'.$table['Name'].'" />'.$table['Name'].'</td>';
			$num++;
		}
		$tablestring.='</tr></table></div>';
		//表单开始
		$export_form=new JieqiThemeForm($jieqiLang[JIEQI_MODULE_NAME]['db_export'], 'dbexport',$this->getAdminurl('dbmanage'));
		//数据备份类型
		$export_type=new JieqiFormRadio($jieqiLang[JIEQI_MODULE_NAME]['export_type'], 'exporttype', 'all');
		$export_type->setExtra("onClick='javascript:if(this.value==\"select\"){document.getElementById(\"tablelist\").style.display=\"block\";}else{document.getElementById(\"tablelist\").style.display=\"none\";}'");
		$export_type->addOption('all', $jieqiLang[JIEQI_MODULE_NAME]['export_all_table']);
		$export_type->addOption('select', $jieqiLang[JIEQI_MODULE_NAME]['export_select_table']);
		$export_form->addElement($export_type);
		//选择要备份的表格
		$export_form->addElement(new JieqiFormLabel($jieqiLang[JIEQI_MODULE_NAME]['export_talbe_list'], $tablestring));
		//数据备份方式
		$export_mode=new JieqiFormRadio($jieqiLang[JIEQI_MODULE_NAME]['export_mode'], 'exportmode', 'multivol');
		$export_mode->setExtra($shelldisabled);
		$export_mode->addOption('multivol', $jieqiLang[JIEQI_MODULE_NAME]['export_partition']);
		$export_mode->addOption('mysqldump', $jieqiLang[JIEQI_MODULE_NAME]['export_dump']);
		$export_form->addElement($export_mode);
		//分卷文件长度限制
		$export_size=new JieqiFormText($jieqiLang[JIEQI_MODULE_NAME]['export_size_limit'], 'exportsize', 6, 4, '2048');
		$export_size->setDescription($jieqiLang[JIEQI_MODULE_NAME]['export_file_unit']);
		$export_form->addElement($export_size, TRUE);
		//扩展插入
		$export_extend=new JieqiFormRadio($jieqiLang[JIEQI_MODULE_NAME]['export_extend_insert'], 'exportinsert', '');
		$export_extend->setExtra("onClick=''");
		$export_extend->addOption('1', $jieqiLang[JIEQI_MODULE_NAME]['radio_checked_yes']);
		$export_extend->addOption('0', $jieqiLang[JIEQI_MODULE_NAME]['radio_checked_no']);
		$export_form->addElement($export_extend);
		//建表语句格式
		$export_version=new JieqiFormRadio($jieqiLang[JIEQI_MODULE_NAME]['export_version'], 'exportversion', '');
		$export_version->setExtra("onClick=''");
		$export_version->addOption('', $jieqiLang[JIEQI_MODULE_NAME]['export_mysql_default']);
		$export_version->addOption('MYSQL40', $jieqiLang[JIEQI_MODULE_NAME]['export_mysql_low']);
		$export_version->addOption('MYSQL41', $jieqiLang[JIEQI_MODULE_NAME]['export_mysql_high']);
		$export_form->addElement($export_version);
		//强制字符集
		$export_charset=new JieqiFormRadio($jieqiLang[JIEQI_MODULE_NAME]['export_charset'], 'exportcharset', '');
		$export_charset->setExtra("onClick=''");
		$export_charset->addOption('', $jieqiLang[JIEQI_MODULE_NAME]['export_charset_default']);
		JIEQI_DB_CHARSET && MYSQL_SERVER_INFO>'4.1'?$export_charset->addOption(JIEQI_DB_CHARSET, strtoupper(JIEQI_DB_CHARSET)):'';
		JIEQI_DB_CHARSET!='utf8' && MYSQL_SERVER_INFO>'4.1'?$export_charset->addOption('utf8', 'UTF-8'):'';
		$export_form->addElement($export_charset);
		//十六进制方式
		$export_hexcode=new JieqiFormRadio($jieqiLang[JIEQI_MODULE_NAME]['export_hexcode'], 'exporthexcode', '1');
		$export_hexcode->setExtra("onClick=''");
		$export_hexcode->addOption('1', $jieqiLang[JIEQI_MODULE_NAME]['radio_checked_yes']);
		$export_hexcode->addOption('', $jieqiLang[JIEQI_MODULE_NAME]['radio_checked_no']);
		$export_form->addElement($export_hexcode);
		//压缩备份文件
		/*anchor:28
		$export_compress=new JieqiFormRadio($jieqiLang[JIEQI_MODULE_NAME]['export_compress'], 'exportcompress', '0');
		$export_compress->setExtra($zipdisabled);
		$export_compress->addOption('1', $jieqiLang[JIEQI_MODULE_NAME]['export_zip_one']);
		$export_compress->addOption('2', $jieqiLang[JIEQI_MODULE_NAME]['export_zip_all']);
		$export_compress->addOption('0', $jieqiLang[JIEQI_MODULE_NAME]['export_zip_none']);
		$export_form->addElement($export_compress);
		*/
		//备份文件名
		$export_file=new JieqiFormText($jieqiLang[JIEQI_MODULE_NAME]['export_file'], 'exportfile', 20, 250, $defaultfilename);
		$export_file->setDescription($jieqiLang[JIEQI_MODULE_NAME]['export_file_format']);
		$export_form->addElement($export_file, TRUE);
		$export_form->addElement(new JieqiFormHidden('method', 'export'));
		//$export_form->addElement(new JieqiFormHidden('option', 'export'));
		$on_submit=new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit');
		$on_submit->setExtra('onclick=""');
		$export_form->addElement($on_submit);
		//$jieqiTpl->assign('dbmanage_form', $export_form->render(JIEQI_FORM_MAX));
		$data['dbmanage_form']=$export_form->render(JIEQI_FORM_MAX);
		return $data;
	}	
	//import view form
	public function importForm(){
		global $jieqiLang,$jieqiBackuplog;
		if(!defined('MYSQL_BACKUP_PATH')) define('MYSQL_BACKUP_PATH', JIEQI_ROOT_PATH.'/files/system/dbbackup');
		if(!jieqi_checkdir(MYSQL_BACKUP_PATH, true)) jieqi_createdir(MYSQL_BACKUP_PATH, 0777, TRUE);
		
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		jieqi_loadlang('database', JIEQI_MODULE_NAME);
		$data = array();
		$data['option']=2;
		$import_form=new JieqiThemeForm($jieqiLang[JIEQI_MODULE_NAME]['db_import'], 'dbimport', $this->getAdminurl('dbmanage'));
		$import_file=new JieqiFormText($jieqiLang[JIEQI_MODULE_NAME]['import_file'], 'importfile', 20, 250);
		$import_file->setDescription($jieqiLang[JIEQI_MODULE_NAME]['import_file_format']);
		$import_form->addElement($import_file, TRUE);
		$import_form->addElement(new JieqiFormHidden('method', 'import'));
		//$import_form->addElement(new JieqiFormHidden('option', 'import'));
		$on_submit=new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit');
		$on_submit->setExtra('onclick=""');
		$import_form->addElement($on_submit);
		//$jieqiTpl->assign('dbmanage_form', $import_form->render(JIEQI_FORM_MAX));
		$data['dbmanage_form']=$import_form->render(JIEQI_FORM_MAX);
		//处理批量删除记录
		if(isset($_POST['checkaction']) && $_POST['checkaction'] == 1 && is_array($_POST['checkid']) && count($_POST['checkid'])>0){
			foreach($_POST['checkid'] as $v){
				jieqi_getconfigs(JIEQI_MODULE_NAME, 'backuplog');
				//删除原始文件
				$backfile=MYSQL_BACKUP_PATH.'/'.$jieqiBackuplog[$v]['name'];
				if(@file_exists($backfile)) jieqi_delfile($backfile);
				//删除数据纪录
				unset($jieqiBackuplog[$v]);
				jieqi_setconfigs('backuplog', 'jieqiBackuplog', $jieqiBackuplog, JIEQI_MODULE_NAME);
			}
			jieqi_jumppage($this->getAdminurl('dbmanage','method=import_view'), LANG_DO_SUCCESS, $jieqiLang[JIEQI_MODULE_NAME]['log_del_success']);
		}
		//列表显示备份纪录
		$logfileisarray=FALSE;
		jieqi_getconfigs(JIEQI_MODULE_NAME, 'backuplog');
		if(is_array($jieqiBackuplog) && count($jieqiBackuplog)>0){
			foreach($jieqiBackuplog as $k=>$v){
				if(!@file_exists(MYSQL_BACKUP_PATH.'/'.$v['name'])) unset($jieqiBackuplog[$k]);
			}
			$logfileisarray=TRUE;
		}
		jieqi_setconfigs('backuplog', 'jieqiBackuplog', $jieqiBackuplog, JIEQI_MODULE_NAME);
		if($logfileisarray){
			$log_array=array();
			$i=0;
			foreach($jieqiBackuplog as $k=>$v){
				$log_array[$i]['id']=$k;
				$log_array[$i]['name']=$v['name'];
				$log_array[$i]['version']=$v['version'];
				$log_array[$i]['time']=date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $v['time']);
				$log_array[$i]['mode']=$v['mode'];
				$log_array[$i]['size']=round($v['size']/1024, 2).'K';
				$log_array[$i]['type']=$v['type'];
				$log_array[$i]['volume']=$v['volume'];
				$log_array[$i]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$k.'" />';
				$log_array[$i]['importurl']=substr($v['name'], strpos($v['name'], '.'))=='.sql' ? $this->getAdminurl('dbmanage','method=import&importfile='.substr(basename($v['name']), 0, strpos(basename($v['name']), '-'))):'#';
				//$log_array[$i]['downloadurl']=MYSQL_BACKUP_PATH.'/'.$v['name'];
				$i++;
			}
			//$jieqiTpl->assign('log_list', $log_array);
			$data['log_list']=$log_array;
		}
		return $data;
	}
	//optimize/repair view form
	public function optimizeView(){
		$data = array();
		$sql="SHOW TABLE STATUS LIKE '".JIEQI_DB_PREFIX."%'";
		$res=$this->db->execute($sql);
		$tablerows=array();
		$k=0;
		$totaltable=0; //表数
		$totalsize=0;  //数据长度
		$totalrows=0; //记录数
		$totalindex=0; //索引程度
		$totalfree=0; //碎片长度
		while($row=$this->db->getRow($res)){
			$tablerows[$k]=jieqi_funtoarray('jieqi_htmlstr', $row);
			$tablerows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.jieqi_htmlstr($row['Name']).'">';
			$totaltable++;
			$totalrows+=$row['Rows'];
			$totalsize+=$row['Data_length'];
			$totalindex+=$row['Index_length'];
			$totalfree+=$row['Data_free'];
			$k++;
		}
		$data['checkall'] = '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].type == \'checkbox\' && this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">';
		$data['tablerows'] = $tablerows;
		if($totalsize)
		$data['totaltable'] = $totaltable;
		$data['totalrows'] = $totalrows;
		if($totalsize > 1048576) $totalsize = sprintf('%0.1fM', $totalsize / 1048576);
		elseif($totalsize > 1024) $totalsize = sprintf('%0.1fK', $totalsize / 1024);
		$data['totalsize'] = $totalsize;
		if($totalindex > 1048576) $totalindex = sprintf('%0.1fM', $totalindex / 1048576);
		elseif($totalindex > 1024) $totalindex = sprintf('%0.1fK', $totalindex / 1024);
		$data['totalindex'] = $totalindex;
		if($totalfree > 1048576) $totalfree = sprintf('%0.1fM', $totalfree / 1048576);
		elseif($totalfree > 1024) $totalfree = sprintf('%0.1fK', $totalfree / 1024);
		$data['totalfree'] = $totalfree;
		$data['url_action'] = $this->getAdminurl('dbmanage','method=optimize');
		return $data;
	}
	//do import
	public function import(){
		$_REQUEST = $this->getRequest();
		if(!defined('MYSQL_BACKUP_PATH')) define('MYSQL_BACKUP_PATH', JIEQI_ROOT_PATH.'/files/system/dbbackup');
		if(!jieqi_checkdir(MYSQL_BACKUP_PATH, true)) jieqi_createdir(MYSQL_BACKUP_PATH, 0777, TRUE);
		$filename=$_REQUEST['importfile'];
		$errtext='';
		//判断文件名合法性
		if(!empty($filename)){
			$filename=trim($filename);
			$filename=strpos($filename, '.')?substr($filename, 0, strpos($filename, '.')):$filename;
			$filename=strpos($filename, '-')?substr($filename, 0, strpos($filename, '-')):$filename;
			if(!preg_match("/[A-Za-z0-9_]+$/", $filename)) $errtext.=$jieqiLang[JIEQI_MODULE_NAME]['need_file_name'].'<br />';
		}else{
			$errtext.=$jieqiLang[JIEQI_MODULE_NAME]['need_file_name'].'<br />';
		}
		if(empty($errtext)){
			//$db_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
			$sqlfilearray=$this->jieqi_getfilesarray($filename);
			if(is_array($sqlfilearray) && count($sqlfilearray)>0){
				//处理压缩SQL的情况
				/*anchor:28
				if('.zip'===substr($sqlfilearray[0], strpos($sqlfilearray[0], '.'))){
					//exit;
				}
				*/
				//开始导入SQL
				foreach($sqlfilearray as $v){
					$sqlfilecontent=jieqi_readfile(MYSQL_BACKUP_PATH.'/'.$v);
					$sqlary=array();
					$sqlerr=array();
					$this->jieqi_splitsqlfile($sqlary, str_replace(' jieqi', ' '.JIEQI_DB_PREFIX, $sqlfilecontent));
					foreach($sqlary as $s){
						$s=trim($s);
						if(!empty($s) and strlen($s)>5){
							$retflag=$this->db->execute($this->jieqi_syntablestruct($s, MYSQL_SERVER_INFO>'4.1', JIEQI_DB_CHARSET));
							if(!$retflag){
								$sqlerr[]=array('sql'=>$s, 'error'=>$this->db->error());
								//显示错误
								jieqi_printfail(sprintf($jieqiLang[JIEQI_MODULE_NAME]['print_sql_error'], jieqi_htmlstr($s), jieqi_htmlstr($this->db->error())));
								break;
							}
						}
					}
				}
				//处理成功跳转
				jieqi_jumppage($this->getAdminurl('dbmanage','method=import_view'), LANG_DO_SUCCESS, $jieqiLang[JIEQI_MODULE_NAME]['import_mysql_success']);
			}else{
				jieqi_printfail($jieqiLang[JIEQI_MODULE_NAME]['import_file_error']);
			}
		}else{
			jieqi_printfail($errtext);
		}
	}
	/** 
	 * do backup
	 */
	public function backup(){
		global $sizelimit, $startrow, $extendins, $sqlcompat, $sqlcharset, $dumpcharset, $usehex, $complete;
		$data = array();
		global $jieqiLang;
		jieqi_loadlang('database', JIEQI_MODULE_NAME);
		//定义常量，备份路径
		if(!defined('MYSQL_BACKUP_PATH')) define('MYSQL_BACKUP_PATH', JIEQI_ROOT_PATH.'/files/system/dbbackup');
		if(!jieqi_checkdir(MYSQL_BACKUP_PATH, true)) jieqi_createdir(MYSQL_BACKUP_PATH, 0777, TRUE);
		define('MYSQL_SERVER_INFO', mysql_get_server_info());
		$_REQUEST = $this->getRequest();
		//表单数据合法性
		$exporttype=$_REQUEST['exporttype']=='select'?'select':'all';
		$exporttables=$_REQUEST['tablearray'];
		$exportmode=$_REQUEST['exportmode']=='multivol'?'multivol':'mysqldump';
		$sqlcompat=$_REQUEST['exportversion']?($_REQUEST['exportversion']=='MYSQL40'?'MYSQL40':'MYSQL41'):'';
		$sqlcharset=in_array($_REQUEST['exportcharset'], array('gbk', 'big5', 'utf8'))?$_REQUEST['exportcharset']:'';
		$dumpcharset=$sqlcharset?$sqlcharset:str_replace('-', '', JIEQI_CHAR_SET);
		$extendins=$_REQUEST['exportinsert']==1?1:0;
		$sizelimit=intval(trim($_REQUEST['exportsize']));
		if($sizelimit < 100) $sizelimit=100;
		$usehex=$_REQUEST['exporthexcode']==1?1:'';
		/*anchor:28
		$usezip=$_REQUEST['exportcompress']?($_REQUEST['exportcompress']==1?1:2):0;
		*/
		$filename=trim($_REQUEST['exportfile']);
		
		$errtext='';
		//检验分卷数值
		if(empty($sizelimit) || intval($sizelimit)<100) $errtext.=$jieqiLang[JIEQI_MODULE_NAME]['need_size_limit'].'<br />';
		//检验文件名合法性
		if(empty($filename) || !preg_match("/[A-Za-z0-9_]+$/", $filename)) $errtext.=$jieqiLang[JIEQI_MODULE_NAME]['need_file_name'].'<br />';
		//获取表格数组
		$tables=array();
		if($exporttype=='all'){
			$tables=$this->jieqi_arraykeys2($this->jieqi_fetchtablelist(JIEQI_DB_PREFIX), 'Name');
		}elseif($exporttype=='select'){
			if(is_array($exporttables) && count($exporttables)>0){
				foreach($exporttables as $value){
					$tables[]=$value;
				}
			}
		}
		if(!is_array($tables) || empty($tables)){
			$errtext.=$jieqiLang[JIEQI_MODULE_NAME]['need_export_table'].'<br />';
		}
		//建立时间戳
		$exporttime=gmdate(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, JIEQI_NOW_TIME);
		if(empty($errtext)){
			$idstring='# Identify: '.base64_encode("$exporttime, $exporttype, $exportmode")."\n";
			$setnames=($sqlcharset && MYSQL_SERVER_INFO> '4.1' && (!$sqlcompat || $sqlcompat=='MYSQL41'))?"SET NAMES '$dumpcharset';\n\n":'';

			if(MYSQL_SERVER_INFO>'4.1'){
				if($sqlcharset){
					$this->db->query("SET NAMES '".$sqlcharset."';\n\n");
				}
				if($sqlcompat=='MYSQL40'){
					$this->db->query("SET SQL_MODE='MYSQL40'");
				}elseif($sqlcompat=='MYSQL41'){
					$this->db->query("SET SQL_MODE=''");
				}
			}
			$backupfilename=MYSQL_BACKUP_PATH.'/'.str_replace(array('/', '\\', '.'), '', $filename);

			if($exportmode=='multivol'){
				header("Content-type: text/html; charset=".JIEQI_SYSTEM_CHARSET); 
				echo '                                                                                                                                                                                                                                                                                                            ';
				echo $jieqiLang['system']['export_file_start'].'<br />';
				ob_flush();
				flush();
				while(1){
					$sqldump='';
					$complete=TRUE;
					$volume=intval($volume)+1;
					$tableid=intval($tableid);
					$startfrom=intval($startrow);
					for(;$complete && $tableid<count($tables) && strlen($sqldump)<$sizelimit*1000;$tableid++){
						$sqldump.=$this->jieqi_sqldumptable($tables[$tableid], $startfrom, strlen($sqldump));
						if($complete){
							$startfrom=0;
						}
					}
					$dumpfile=$backupfilename."-%s".'.sql';
					!$complete && $tableid--;
					if(trim($sqldump)){
						$sqldump="$idstring".
							"# <?php exit();?>\n".
							"# JIEQI CMS Multi-Volume Data Dump Vol.$volume\n".
							"# Version: JIEQI CMS ".JIEQI_VERSION." ".JIEQI_VERSION_TYPE."\n".
							"# Time: $exporttime\n".
							"# Type: $exportmode\n".
							"# Table Prefix: ".JIEQI_DB_PREFIX."\n".
							"#\n".
							"# JIEQI CMS Homepage: http://www.jieqi.com\n".
							"# Please visit our website for newest infomation about JIEQI CMS\n".
							"# --------------------------------------------------------\n\n\n".
							"$setnames".
							$sqldump;	
						$dumpfilename=sprintf($dumpfile, $volume);
						$fp=@fopen($dumpfilename, 'wb');
						@flock($fp, 2);
						if(@!fwrite($fp, $sqldump)){
							@fclose($fp);
							jieqi_printfail($jieqiLang[JIEQI_MODULE_NAME]['write_file_failure']);
						}else{
							@fclose($fp);
							unset($sqldump);
							echo sprintf($jieqiLang[JIEQI_MODULE_NAME]['export_file_name'], basename($dumpfilename)).'<br />';
							ob_flush();
							flush();
						}
					}else{
						break;
					}
				}
				
				//处理结果赋值到模板
				$data['option'] = 3;
				$data['backup_info'] = $jieqiLang['system']['export_mysql_success'];
				//$jieqiTpl->assign('option', 3);
				//$jieqiTpl->assign('backup_info', $jieqiLang['system']['export_mysql_success']);
				//读取备份纪录文件
				jieqi_getconfigs(JIEQI_MODULE_NAME, 'backuplog');
				//保存备份日志到文件
				if(@file_exists(MYSQL_BACKUP_PATH)){
					$dh=@opendir(MYSQL_BACKUP_PATH);
					while($files=@readdir($dh)){
						if(strpos($files, $filename)===0){
							$jieqiBackuplog[]=array(
								'name'=>$files, 
								'version'=>$sqlcompat?$sqlcompat:MYSQL_SERVER_INFO, 
								'time'=>filemtime(MYSQL_BACKUP_PATH.'/'.$files), 
								'mode'=>$jieqiLang[JIEQI_MODULE_NAME]['export_multivol'],
								'size'=>filesize(MYSQL_BACKUP_PATH.'/'.$files), 
								'type'=>$exporttype=='all'?$jieqiLang[JIEQI_MODULE_NAME]['export_all_data']:$jieqiLang[JIEQI_MODULE_NAME]['export_custom_data'], 
								'volume'=>intval(substr(basename($files), strrpos(basename($files), '-')+1)));
						}
					}
					@closedir($dh);
				}
				//写入备份配置文件
				jieqi_setconfigs('backuplog', 'jieqiBackuplog', $jieqiBackuplog, JIEQI_MODULE_NAME);

			}elseif($exportmode=='mysqldump'){
				$volume=1;
				$tablesstr='';
				$filestring='<li>'.$jieqiLang[JIEQI_MODULE_NAME]['export_status_title'].'</li>';

				foreach($tables as $t){$tablesstr.='"'.$t.'" ';}
				list($dbhost, $dbport)=explode(':', JIEQI_DB_HOST);

				$result=$this->db->query("SHOW VARIABLES LIKE 'basedir'");
				list(, $mysql_base)=@mysql_fetch_array($result, MYSQL_NUM);
				$dumpfile=$backupfilename.'-'.$volume.'.sql';
				jieqi_delfile($dumpfile);
				$mysqlbin=$mysql_base=='/'?'':jieqi_setslashes($mysql_base).'bin/';
				
				@shell_exec($mysqlbin.'mysqldump --force --quick '.(MYSQL_SERVER_INFO>'4.1'?'--skip-opt --create-options':'-all').' --add-drop-table'.(JIEQI_DB_CHARSET?' --default-character-set="'.JIEQI_DB_CHARSET.'"':'').($extendins==1?' --extended-insert':'').''.(MYSQL_SERVER_INFO>'4.1' && $sqlcompat=='MYSQL40'?' --compatible=mysql40':'').' --host="'.$dbhost.'"'.($dbport?(is_numeric($dbport)?' --port="'.$dbport.'"':' --socket="'.$dbport.'"'):'').' --user="'.JIEQI_DB_USER.'" --password="'.JIEQI_DB_PASS.'" "'.JIEQI_DB_NAME.'" '.$tablesstr.' > '.$dumpfile);

				if(@file_exists($dumpfile)){
					if(@is_writeable($dumpfile)){
						$fp=@fopen($dumpfile, 'rb+');
						@fwrite($fp, $idstring."# <?php exit();?>\n ".$setnames."\n #");
						@fclose($fp);
					}
					/*anchor:28
					//压缩SQL文件
					if($usezip){
						if(!jieqi_makezip($filename, $volume+1, $usezip)) jieqi_printfail($jieqiLang[JIEQI_MODULE_NAME]['create_zip_failure']);
					}
					*/
					//处理结果赋值到模板
					$data['option'] = 3;
					//$jieqiTpl->assign('option', 3);
					$filestring.= '<li>-'.sprintf($jieqiLang[JIEQI_MODULE_NAME]['export_file_name'], basename($dumpfile)).'</li>';
					$filestring.='<li>'.$jieqiLang['system']['export_mysql_success'].'</li>';
					//$jieqiTpl->assign('backup_info', $filestring);
					$data['backup_info'] = $filestring;
					unset($filestring);
					//读取备份纪录文件
					jieqi_getconfigs(JIEQI_MODULE_NAME, 'backuplog');
					//保存备份日志到文件
					if(@file_exists(MYSQL_BACKUP_PATH)){
						$dh=@opendir(MYSQL_BACKUP_PATH);
						while($files=@readdir($dh)){
							if(strpos($files, $filename)===0){
								$jieqiBackuplog[]=array(
									'name'=>$files, 
									'version'=>$sqlcompat?$sqlcompat:MYSQL_SERVER_INFO, 
									'time'=>filemtime(MYSQL_BACKUP_PATH.'/'.$files), 
									'mode'=>$jieqiLang[JIEQI_MODULE_NAME]['export_mysqldump'],
									'size'=>filesize(MYSQL_BACKUP_PATH.'/'.$files), 
									'type'=>$exporttype=='all'?$jieqiLang[JIEQI_MODULE_NAME]['export_all_data']:$jieqiLang[JIEQI_MODULE_NAME]['export_custom_data'], 
									'volume'=>0);
							}
						}
						@closedir($dh);
					}
					//写入备份配置文件
					jieqi_setconfigs('backuplog', 'jieqiBackuplog', $jieqiBackuplog, JIEQI_MODULE_NAME);
				}else{
					jieqi_printfail($jieqiLang[JIEQI_MODULE_NAME]['create_file_failure']);
				}
			}
		}else{
			jieqi_printfail($errtext);
		}
		return $data;
	}
	/** 
	 * do optimize or repair
	 */
	function optimize(){

		global $jieqiLang;
		jieqi_loadlang('database', JIEQI_MODULE_NAME);
		$_REQUEST = $this->getRequest();
		if(empty($_REQUEST['checkid'])) jieqi_printfail($jieqiLang['system']['need_select_table']);
		$sql="SHOW TABLE STATUS LIKE '".JIEQI_DB_PREFIX."%'";
		$res=$this->db->execute($sql);
		$alltables=array();
		while($row=$this->db->getRow($res)){
			$alltables[]=$row['Name'];
		}
		$doaction='';
		foreach ($_REQUEST['checkid'] as $v){
			if(in_array($v, $alltables)){
				if($_REQUEST['action']=='optimize'){
					$this->db->execute("OPTIMIZE TABLE ".$v);
					$doaction = $jieqiLang['system']['optimize_table_action'];
					echo "<br>OPTIMIZE TABLE ".$v;
				}else{
					$this->db->execute("REPAIR TABLE ".$v);
					$doaction = $jieqiLang['system']['repair_table_action'];
					echo "<br>REPAIR TABLE ".$v;
				}
			}
		}
		$url = $_REQUEST['action']=='optimize' ? $this->getAdminurl('dbmanage','method=optimize_view') : $this->getAdminurl('dbmanage','method=repair_view');
		if(!empty($doaction)) jieqi_jumppage($url, LANG_DO_SUCCESS, sprintf($jieqiLang['system']['optrep_table_success'], $doaction));
		else jieqi_printfail(sprintf($jieqiLang['system']['optrep_table_success'], $doaction));
	}
	
	//数据库升级
	function upgrade(){
		global $jieqiLang;
		jieqi_loadlang('database', JIEQI_MODULE_NAME);
		$_REQUEST = $this->getRequest();
		if(empty($_REQUEST['sqldata'])) jieqi_printfail($jieqiLang['system']['need_sql_data']);
		elseif(preg_match('/(into\s+outfile|load_file\s*\([^\)]*\)|load\s+data)/is', $_REQUEST['sqldata'])) jieqi_printfail($jieqiLang['system']['deny_sql_data']);
		$this->jieqi_splitsqlfile($sqlary, str_replace(' jieqi_', ' '.JIEQI_DB_PREFIX.'_', $_REQUEST['sqldata']));
		$sqlerr=array();
		foreach($sqlary as $v){
			$v=trim($v);
			if(!empty($v) and strlen($v)>5){
				$retflag=$this->db->execute($v);
				if(!$retflag){
					$sqlerr[]=array('sql'=>$v, 'error'=>$this->db->error());
					if($_REQUEST['errorstop']){
						jieqi_printfail(sprintf($jieqiLang['system']['print_sql_error'], jieqi_htmlstr($v), jieqi_htmlstr($this->db->error())));
						break;
					}
				}
			}
		}
		if(!empty($sqlerr) && $_REQUEST['showerror']){
			$errorinfo='';
			foreach($sqlerr as $v){
				$errorinfo.=sprintf($jieqiLang['system']['show_error_format'], jieqi_htmlstr($v['sql']), jieqi_htmlstr($v['error']));
			}
			jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['system']['sql_some_error'], $errorinfo));
		}else{
			jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['execute_sql_success']);
		}
	}
	//SQL导出处理函数
	function jieqi_sqldumptable($table, $startfrom=0, $currsize=0) {
		global $sizelimit, $startrow, $extendins, $sqlcompat, $sqlcharset, $dumpcharset, $usehex, $complete;
		$offset=300;
		$tabledump='';
		$tablefields=array();
		$query=$this->db->query("SHOW FULL COLUMNS FROM $table");
		if(!$query){
			$usehex=FALSE;
		}else{
			while($fieldrow=$this->db->fetchArray($query)){
				$tablefields[]=$fieldrow;
			}
		}
		if(!$startfrom){
			$createtable=$this->db->query("SHOW CREATE TABLE $table");
			if($createtable){
				$tabledump="DROP TABLE IF EXISTS `$table`;\n";
			}else{
				return '';
			}
			$create=$this->db->fetchArray($createtable);
			if(strpos($table, '.')!==FALSE){
				$tablename=substr($table, strpos($table, '.')+1);
				$create['Create Table']=str_replace("CREATE TABLE `$tablename`", "CREATE TABLE `$table`", $create['Create Table']);
			}
			$tabledump.= $create['Create Table'];
	
			if(MYSQL_SERVER_INFO<'4.1' && $sqlcompat=='MYSQL41'){
				$tabledump = preg_replace("/TYPE\=(.+)/", "ENGINE=\\1 DEFAULT CHARSET=".$dumpcharset, $tabledump);
			}
			if(MYSQL_SERVER_INFO>'4.1' && $sqlcharset) {
				$tabledump = preg_replace("/(DEFAULT)*\s*CHARSET=.+/", "DEFAULT CHARSET=".$sqlcharset, $tabledump);
			}
	
			$tablestatus=$this->db->query("SHOW TABLE STATUS LIKE '$table'");
			$tablestatus=$this->db->fetchArray($tablestatus);
			//$tabledump.=($tablestatus['Auto_increment']?" AUTO_INCREMENT=$tablestatus[Auto_increment]":'').";\n\n";
			$tabledump.=";\n\n";
	
			if($sqlcompat=='MYSQL40' && MYSQL_SERVER_INFO>='4.1' && MYSQL_SERVER_INFO<'5.1'){
				/*
				if($tablestatus['Auto_increment']<>''){
					$temppos=strpos($tabledump, ',');
					$tabledump=substr($tabledump, 0, $temppos).' auto_increment'.substr($tabledump, $temppos);
				}
				*/
				if($tablestatus['Engine']=='MEMORY'){
					$tabledump=str_replace('TYPE=MEMORY', 'TYPE=HEAP', $tabledump);
				}
			}
		}
		
		$tabledumped=0;
		$numrows=$offset;
		$firstfield=$tablefields[0];
	
		
		if($extendins=='0'){
			while($currsize+strlen($tabledump)<$sizelimit*1000 && $numrows==$offset){
				if($firstfield['Extra']=='auto_increment'){
					$selectsql="SELECT * FROM $table WHERE $firstfield[Field]>$startfrom LIMIT $offset";
				}else{
					$selectsql="SELECT * FROM $table LIMIT $startfrom, $offset";
				}
				$tabledumped=1;
				$rows=$this->db->query($selectsql);
				$numfields=mysql_num_fields($rows);
	
				$numrows=$this->db->getRowsNum($rows);
				while($row=$this->db->fetchRow($rows)){
					$comma=$t='';
					for($i=0;$i<$numfields;$i++){
						$t.=$comma.($usehex && !empty($row[$i]) && (jieqi_strexists($tablefields[$i]['Type'], 'char') || jieqi_strexists($tablefields[$i]['Type'], 'text'))?'0x'.bin2hex($row[$i]):'\''.mysql_escape_string($row[$i]).'\'');
						$comma = ',';
					}
					if(strlen($t)+$currsize+strlen($tabledump)<$sizelimit*1000){
						if($firstfield['Extra']=='auto_increment'){
							$startfrom=$row[0];
						}else{
							$startfrom++;
						}
						$tabledump.="INSERT INTO $table VALUES ($t);\n";
					}else{
						$complete=FALSE;
						break 2;
					}
				}
			}
	
		
		}else{
		
			while($currsize+strlen($tabledump)<$sizelimit*1000 && $numrows==$offset){
				if($firstfield['Extra']=='auto_increment'){
					$selectsql = "SELECT * FROM $table WHERE $firstfield[Field]>$startfrom LIMIT $offset";
				}else{
					$selectsql = "SELECT * FROM $table LIMIT $startfrom, $offset";
				}
				$tabledumped=1;
				$rows=$this->db->query($selectsql);
				$numfields=mysql_num_fields($rows);
	
				if($numrows=$this->db->getRowsNum($rows)){
					$t1=$comma1='';
					while($row=$this->db->fetchRow($rows)){
						$t2=$comma2='';
						for($i=0;$i<$numfields;$i++){
							$t2.=$comma2.($usehex && !empty($row[$i]) && (jieqi_strexists($tablefields[$i]['Type'], 'char') || jieqi_strexists($tablefields[$i]['Type'], 'text'))?'0x'.bin2hex($row[$i]):'\''.mysql_escape_string($row[$i]).'\'');
							$comma2=',';
						}
						if(strlen($t1)+$currsize+strlen($tabledump)<$sizelimit*1000){
							if($firstfield['Extra']=='auto_increment'){
								$startfrom=$row[0];
							}else{
								$startfrom++;
							}
							$t1.="$comma1 ($t2)";
							$comma1=',';
						}else{
							$tabledump.="INSERT INTO $table VALUES $t1;\n";
							$complete=FALSE;
							break 2;
						}
					}
					$tabledump.="INSERT INTO $table VALUES $t1;\n";
				}
			}
		}
		$startrow=$startfrom;
		$tabledump.="\n";
	
		return $tabledump;
	
	}
	//返回定义下标数组
	function jieqi_arraykeys2($array, $key2){
		$return=array();
		foreach($array as $val){
			$return[]=$val[$key2];
		}
		return $return;
	}
	//获取表集合数组
	function jieqi_fetchtablelist($tablepre=''){
		//global $query_handler;
		$arr=explode('.', $tablepre);
		$dbname=!empty($arr[1])?$arr[0]:'';
		$sqladd=$dbname?" FROM $dbname LIKE '$arr[1]%'":"LIKE '$tablepre%'";
		!$tablepre && $tablepre='*';
		$tables=$table=array();
		$query=$this->db->query("SHOW TABLE STATUS $sqladd");
		while($table=$this->db->fetchArray($query)){
			$table['Name']=($dbname?"$dbname.":'').$table['Name'];
			$tables[]=$table;
		}
		return $tables;
	}
	
	//根据文件名返回分卷数组
	function jieqi_getfilesarray($basename){
		if(!defined('MYSQL_BACKUP_PATH')) define('MYSQL_BACKUP_PATH', JIEQI_ROOT_PATH.'/files/system/dbbackup');
		if(!jieqi_checkdir(MYSQL_BACKUP_PATH, true)) jieqi_createdir(MYSQL_BACKUP_PATH, 0777, TRUE);
		$filearray=array();
		$dh=dir(MYSQL_BACKUP_PATH);
		while(FALSE!==($file=$dh->read())){
			$subfile=substr(basename($file), 0, strpos(basename($file), '.'));
			$subfile=substr($subfile, 0, strpos($subfile, '-'));
			if($basename==$subfile){
				$filearray[]=$file;
			}
		}
		$dh->close();
		//判断分卷文件是否完整
		if(is_array($filearray) && count($filearray)>0){
			for($i=0;$i<count($filearray);$i++){
				if(!file_exists(MYSQL_BACKUP_PATH.'/'.$basename.'-'.($i+1).substr($filearray[$i], strrpos($filearray[$i], '.')))) return FALSE;
			}
			return $filearray; 
		}else{
			return FALSE;
		}
	}
	
	//SQL分割函数
	function jieqi_splitsqlfile(&$ret, $sql, $release=32270){
		//$sql          = trim($sql);
		$sql          = rtrim($sql, "\n\r");
		$sql_len      = strlen($sql);
		$char         = '';
		$string_start = '';
		$in_string    = FALSE;
	
		for ($i = 0; $i < $sql_len; ++$i) {
			$char = $sql[$i];
	
			// We are in a string, check for not escaped end of strings except for
			// backquotes that can't be escaped
			if ($in_string) {
				for (;;) {
					$i         = strpos($sql, $string_start, $i);
					// No end of string found -> add the current substring to the
					// returned array
					if (!$i) {
						$ret[] = $sql;
						return TRUE;
					}
					// Backquotes or no backslashes before quotes: it's indeed the
					// end of the string -> exit the loop
					else if ($string_start == '`' || $sql[$i-1] != '\\') {
						$string_start      = '';
						$in_string         = FALSE;
						break;
					}
					// one or more Backslashes before the presumed end of string...
					else {
						// ... first checks for escaped backslashes
						$j                     = 2;
						$escaped_backslash     = FALSE;
						while ($i-$j > 0 && $sql[$i-$j] == '\\') {
							$escaped_backslash = !$escaped_backslash;
							$j++;
						}
						// ... if escaped backslashes: it's really the end of the
						// string -> exit the loop
						if ($escaped_backslash) {
							$string_start  = '';
							$in_string     = FALSE;
							break;
						}
						// ... else loop
						else {
							$i++;
						}
					} // end if...elseif...else
				} // end for
			} // end if (in string)
	
			// We are not in a string, first check for delimiter...
			else if ($char == ';') {
				// if delimiter found, add the parsed part to the returned array
				$ret[]      = substr($sql, 0, $i);
				$sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
				$sql_len    = strlen($sql);
				if ($sql_len) {
					$i      = -1;
				} else {
					// The submited statement(s) end(s) here
					return TRUE;
				}
			} // end else if (is delimiter)
	
			// ... then check for start of a string,...
			else if (($char == '"') || ($char == '\'') || ($char == '`')) {
				$in_string    = TRUE;
				$string_start = $char;
			} // end else if (is start of string)
	
			// ... for start of a comment (and remove this comment if found)...
			else if ($char == '#' || ($char == '-' && $i > 0 && $sql[$i-1] == '-')) {
				// starting position of the comment depends on the comment type
				$start_of_comment = (($sql[$i] == '#') ? $i : $i-1);
				// if no "\n" exits in the remaining string, checks for "\r"
				// (Mac eol style)
				$end_of_comment   = (strpos(' ' . $sql, "\012", $i+1))
								  ? strpos(' ' . $sql, "\012", $i+1)
								  : strpos(' ' . $sql, "\015", $i+1);
				if (!$end_of_comment) {
					// no eol found after '#', add the parsed part to the returned
					// array if required and exit
					if ($start_of_comment > 0) {
						$ret[]    = trim(substr($sql, 0, $start_of_comment));
					}
					return TRUE;
				} else {
					$sql          = substr($sql, 0, $start_of_comment)
								  . ltrim(substr($sql, $end_of_comment));
					$sql_len      = strlen($sql);
					$i--;
				} // end if...else
			} // end else if (is comment)
	
			// ... and finally disactivate the "/*!...*/" syntax if MySQL < 3.22.07
			else if ($release < 32270
					 && ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*')) {
				$sql[$i] = ' ';
			} // end else if
		} // end for
	
		// add any rest to the returned array
		if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
			$ret[] = $sql;
		}
	
		return TRUE;
	}
	
	
	//替换不同版本SQL文件建表语句
	function jieqi_syntablestruct($sql, $version, $dbcharset){
		if(strpos(trim(substr($sql, 0, 18)), 'CREATE TABLE')===FALSE){
			return $sql;
		}
		$sqlversion=strpos($sql, 'ENGINE=')===FALSE?FALSE:TRUE;
		if($sqlversion===$version){
			return $sqlversion && $dbcharset?preg_replace(array('/ character set \w+/i', '/ collate \w+/i', "/DEFAULT CHARSET=\w+/is"), array('', '', "DEFAULT CHARSET=$dbcharset"), $sql):$sql;
		}
		if($version){
			return preg_replace(array('/TYPE=HEAP/i', '/TYPE=(\w+)/is'), array("ENGINE=MEMORY DEFAULT CHARSET=$dbcharset", "ENGINE=\\1 DEFAULT CHARSET=$dbcharset"), $sql);
		}else{
			return preg_replace(array('/character set \w+/i', '/collate \w+/i', '/ENGINE=MEMORY/i', '/\s*DEFAULT CHARSET=\w+/is', '/\s*COLLATE=\w+/is', '/ENGINE=(\w+)(.*)/is'), array('', '', 'ENGINE=HEAP', '', '', 'TYPE=\\1\\2'), $sql);
		}
	}
} 
?>