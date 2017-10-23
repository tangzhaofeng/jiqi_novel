<?php 
/** 
 * 系统管理->区块配置文件管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class manageblocksModel extends Model{
	public function main(){
		global $jieqiPower, $jieqiModules;
		$_REQUEST = $this->getRequest();
		//检查权限
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}
		$this->addLang('system', 'blocks');
		
		//include_once(JIEQI_ROOT_PATH.'/web_admin/header.php');
		if(empty($_REQUEST['action'])) $_REQUEST['action']='listconfig';
		if($_REQUEST['action']!=''){
			switch ($_REQUEST['action']){
				case 'listconfig'://配置文件分类类表
					$this->db->init('blockconfigs','id','system');
					$this->db->setCriteria();
					if(!empty($_REQUEST['modules'])){
						$this->db->criteria->add(new Criteria('modules', jieqi_dbslashes($_REQUEST['modules']),'='));
						$this->db->criteria->setSort('id');
						$this->db->criteria->setOrder('ASC');
					}else{ 
						$this->db->criteria->setSort('modules ASC, id');
					}
					$this->db->queryObjects();
					$blockconfigs=array();
					$k=0;
					while($resz = $this->db->getObject()){
						$blockconfigs[$k]['id']=$resz->getVar('id');
						$blockconfigs[$k]['modules']=$resz->getVar('modules');
						$blockconfigs[$k]['modname']= isset($jieqiModules[$blockconfigs[$k]['modules']]['caption']) ? $jieqiModules[$blockconfigs[$k]['modules']]['caption'] : $blockconfigs[$k]['modules'];
						$blockconfigs[$k]['name']=$resz->getVar('name');
						$blockconfigs[$k]['file']=$resz->getVar('file');
						$k++;
					}
					$blockconfigs = $blockconfigs;
					$modules = $_REQUEST['modules'];
					$modname = $jieqiModules[$_REQUEST['modules']]['caption'];
					return array('blockconfigs'=>$blockconfigs,
						'modules'=>$modules,
						'modname'=>$modname,
					);
					break;
				case 'addbconfig';
				if(!$_REQUEST['dosubmit']){
					$this->db->init('blocks','bid','system');
					$this->db->setCriteria();
					$this->db->criteria->setFields('select bid,blockname,modname,filename,side,template');
					$this->db->queryObjects();
					$modconfig = $this->db->getObject();
					$modules=array();
					$k=0;
					while($resz=$this->db->getObject()){
						$modules[]=$resz->getVars();
					}
					jieqi_getconfigs('system', 'adminmenu');
					$jieqiModules = $jieqiModules;
					$name = $_REQUEST['name'];
					$mod = $_REQUEST['modules'];
					$modules = $modules;
					//$jieqiTset['jieqi_contents_template'] = $jieqiModules['system']['path'].'/templates/web_admin/addmodules.html';
					//include_once(JIEQI_ROOT_PATH.'/web_admin/footer.php');
					exit;
				}
				$this->db->init('blockconfigs','id','system');
				$this->db->setCriteria(new Criteria('id', jieqi_dbslashes($_REQUEST['modules']), "="));
				$this->db->queryObjects();
				$res = $this->db->getObject();				
				$res=$res->getvars($res);
				jieqi_getconfigs($res['modules']['value'], $res['file']['value'],'jieqiBlocks');
				
				$this->db->init('blocks','bid','system');
				$this->db->setCriteria(new Criteria('bid', jieqi_dbslashes($_REQUEST['id'], "=")));
				$this->db->criteria->setFields('select bid,blockname,modname,filename,classname,side,title,vars,template,contenttype,custom,publish,hasvars');
				$this->db->queryObjects();
				$resz = $this->db->getObject();
				$resz = $res->getvars($res);
				foreach ($resz as $i => $value){
					$resz[$i]=$value['value'];
				}
				$resz['module']=$resz['modname'];
				unset($resz['modname']);
				if($jieqiBlocks==""){
					$jieqiBlocks[]=$resz;
				}else{
					array_push($jieqiBlocks,$resz);
				}
				jieqi_setconfigs($res['file']['value'],'jieqiBlocks',$jieqiBlocks,$res['modules']['value']);
				jieqi_jumppage('?action=listconfig&modules='.$res['modules']['value'],LANG_DO_SUCCESS,$jieqiLang['system']['block_add_success']);
				exit;
				case 'addblockdo':
					if(!$dosubmit){
						$uname = $_REQUEST['name'];
						$module = $_REQUEST['module'];
						exit;
					}
					$root=$_REQUEST['moduleas']=='system'?JIEQI_ROOT_PATH.'/configs':JIEQI_ROOT_PATH.'/configs/'.$_REQUEST['modules'];
					$root.='/'.$_REQUEST['file'].'.php';
					if(file_exists($root)){
						jieqi_printfail($jieqiLang['system']['block_newconfig_failure']);
					}
					$date='';
					if(!jieqi_writefile($root,$date)){
						$this->db->init('blockconfigs','id','system');
						$data = array('modules'=>jieqi_dbslashes($_REQUEST['modules']), 'name'=>jieqi_dbslashes($_REQUEST['title']), 'file'=>jieqi_dbslashes($_REQUEST['file']));
						$this->db->add($data);
						jieqi_jumppage('?action=listconfig&modules='.$_REQUEST['modules'],LANG_DO_SUCCESS,$jieqiLang['system']['block_newconfig_success']);
					}else{
						jieqi_jumppage('?action=listconfig&modules='.$_REQUEST['modules'],LANG_DO_SUCCESS,$jieqiLang['system']['block_newconfig_failure']);
					}
					exit;
			}
		}
	}
	
	
	
	
	/*区块列表
	* para void
	* return array
	*/
	public function listblock(){
		unset($jieqiBlocks);
		global $jieqiPower, $jieqiModules, $jieqiBlocks;
		$_REQUEST = $this->getRequest();
		//检查权限
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}
		$this->addLang('system', 'blocks');

		$_REQUEST['configid']=intval($_REQUEST['configid']);
		$this->db->init('blockconfigs','id','system');
		$this->db->setCriteria(new Criteria('id', jieqi_dbslashes($_REQUEST['configid']), "="));
		$this->db->queryObjects();
		$modconfig = $this->db->getObject();
		if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
		jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
		
		if(is_array($jieqiBlocks)){
			foreach($jieqiBlocks as $i => $value){
				$jieqiBlocks[$i]['modname']=$jieqiModules[$value['module']]['caption'];
				$jieqiBlocks[$i]['side']=intval($jieqiBlocks[$i]['side']);
				$jieqiBlocks[$i]['contenttype']=intval($jieqiBlocks[$i]['contenttype']);
				$jieqiBlocks[$i]['showtype']=intval($jieqiBlocks[$i]['showtype']);
				$jieqiBlocks[$i]['custom']=intval($jieqiBlocks[$i]['custom']);
				$jieqiBlocks[$i]['publish']=intval($jieqiBlocks[$i]['publish']);
				$jieqiBlocks[$i]['hasvars']=intval($jieqiBlocks[$i]['hasvars']);
				$jieqiBlocks[$i]['bid']=intval($jieqiBlocks[$i]['bid']);
				if(empty($jieqiBlocks[$i]['bid'])) $jieqiBlocks[$i]['bid']=$_REQUEST['configid'] * 10000 + $i;
			}
		}
		$data = array();
		$data['configid'] = $modconfig->getVar('id');
		$data['configname'] = $modconfig->getVar('name');
		$data['filename'] = $modconfig->getVar('file');
		$data['modules'] = $modconfig->getVar('modules');

		$modname = isset($jieqiModules[$modconfig->getVar('modules','n')]['caption']) ? $jieqiModules[$modconfig->getVar('modules','n')]['caption'] : $modconfig->getVar('modules','n');
		$data['modname'] = jieqi_htmlstr($modname);
		$data['blocks'] = $jieqiBlocks;
		return $data;
	}
	
/*
	public function listconfig(&$blockvars, $isreturn=false){
		unset($jieqiBlocks);
		global $jieqiPower, $jieqiModules, $jieqiBlocks;
		$_REQUEST = $this->getRequest();
		//检查权限
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}
		$this->addLang('system', 'blocks');

			case 'listconfig'://配置文件分类类表
					$this->db->init('blockconfigs','id','system');
					$this->db->setCriteria();
					if(!empty($_REQUEST['modules'])){
						$this->db->criteria->add(new Criteria('modules', jieqi_dbslashes($_REQUEST['modules']),'='));
						$this->db->criteria->setSort('id');
						$this->db->criteria->setOrder('ASC');
					}else{ 
						$this->db->criteria->setSort('modules ASC, id');
					}
					$this->db->queryObjects();
					$blockconfigs=array();
					$k=0;
					while($resz = $this->db->getObject()){
						$blockconfigs[$k]['id']=$resz->getVar('id');
						$blockconfigs[$k]['modules']=$resz->getVar('modules');
						$blockconfigs[$k]['modname']= isset($jieqiModules[$blockconfigs[$k]['modules']]['caption']) ? $jieqiModules[$blockconfigs[$k]['modules']]['caption'] : $blockconfigs[$k]['modules'];
						$blockconfigs[$k]['name']=$resz->getVar('name');
						$blockconfigs[$k]['file']=$resz->getVar('file');
						$k++;
					}
					$blockconfigs = $blockconfigs;
					$modules = $_REQUEST['modules'];
					$modname = $jieqiModules[$_REQUEST['modules']]['caption'];
					return array('blockconfigs'=>$blockconfigs,
						'modules'=>$modules,
						'modname'=>$modname,
					);
					break;
	*/
	
	/*区块重新排序
	* 
	*/
	public function editlist(){
		unset($jieqiBlocks);
		global $jieqiPower, $jieqiModules, $jieqiBlocks;
		$_REQUEST = $this->getRequest();
		//检查权限
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}
		$this->addLang('system', 'blocks');
		$_REQUEST['configid']=intval($_REQUEST['configid']);
		$this->db->init('blockconfigs','id','system');
		$this->db->setCriteria(new Criteria('id', jieqi_dbslashes($_REQUEST['configid']), "="));
		$this->db->queryObjects();
		$modconfig = $this->db->getObject();
		if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
		jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
		asort($_REQUEST['key']);
		$newBlocks=array();
		foreach($_REQUEST['key'] as $a => $value)
		{
			$newBlocks[intval($value)]=$jieqiBlocks[$a];
		}
		foreach($newBlocks as $i => $value)
		{
			$newBlocks[$i]['side']=intval($newBlocks[$i]['side']);
			$newBlocks[$i]['contenttype']=intval($newBlocks[$i]['contenttype']);
			$newBlocks[$i]['showtype']=intval($newBlocks[$i]['showtype']);
			$newBlocks[$i]['custom']=intval($newBlocks[$i]['custom']);
			$newBlocks[$i]['publish']=intval($newBlocks[$i]['publish']);
			$newBlocks[$i]['hasvars']=intval($newBlocks[$i]['hasvars']);
			$newBlocks[$i]['bid']=intval($newBlocks[$i]['bid']);
			if(empty($newBlocks[$i]['bid'])) $newBlocks[$i]['bid']=$_REQUEST['configid'] * 1000 + $i;
		}

		jieqi_setconfigs($modconfig->getVar('file', 'n'),'jieqiBlocks',$newBlocks,$modconfig->getVar('modules', 'n'));
		jieqi_jumppage('/web_admin/?controller=manageblocks&method=listblock&configid='.$_REQUEST['configid'],LANG_DO_SUCCESS,$jieqiLang['system']['block_update_success']);
	}


	/*区块删除
	* 
	*/
	public function delete(){
		unset($jieqiBlocks);
		global $jieqiPower, $jieqiModules, $jieqiBlocks;
		$_REQUEST = $this->getRequest();
		//检查权限
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}
		$this->addLang('system', 'blocks');

		$_REQUEST['configid']=intval($_REQUEST['configid']);
		$this->db->init('blockconfigs','id','system');
		$this->db->setCriteria(new Criteria('id', jieqi_dbslashes($_REQUEST['configid']), "="));
		$this->db->queryObjects();
		$modconfig = $this->db->getObject();
		if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
		jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
		unset($jieqiBlocks[$_REQUEST['key']]);
		jieqi_setconfigs($modconfig->getVar('file', 'n'),'jieqiBlocks',$jieqiBlocks,$modconfig->getVar('modules', 'n'));
		jieqi_jumppage('/web_admin/?controller=manageblocks&method=listblock&configid='.$_REQUEST['configid'],LANG_DO_SUCCESS,$jieqiLang['system']['block_delete_success']);
	}
	
	
	/*区块编辑
	* 
	*/
	public function edit(){
		unset($jieqiBlocks);
		global $jieqiPower, $jieqiModules, $jieqiBlocks, $jieqiLang;
		$_REQUEST = $this->getRequest();
		//检查权限
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}
		$this->addLang('system', 'blocks');
		$_REQUEST['configid']=intval($_REQUEST['configid']);
		$this->db->init('blockconfigs','id','system');
		$this->db->setCriteria(new Criteria('id', jieqi_dbslashes($_REQUEST['configid']), "="));
		$this->db->queryObjects();
		$modconfig = $this->db->getObject();
		if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
		jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
		if(!isset($jieqiBlocks[$_REQUEST['key']])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
		$blockSet=$jieqiBlocks[$_REQUEST['key']];

		//编辑区块
		$this->db->init('blocks','bid','system');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		if($blockSet['custom']==1){
			$dataDb = $this->db->get(intval($blockSet['bid']));
			if(is_array($dataDb)){
				$blockSet['content']=$this->getFormat($dataDb['content'], 'n');
			}
			$blocks_form = new JieqiThemeForm($jieqiLang['system']['edit_custom_block'], 'blockedit', $this->getAdminurl('manageblocks', 'method=edited&configid='.$_REQUEST['configid'].'&key='.$_REQUEST['key']));
			$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['table_blocks_blockname'], 'blockname', 30, 50, htmlspecialchars($blockSet['blockname'], ENT_QUOTES)), true);
			//模块选择
			$modselect=new JieqiFormSelect($jieqiLang['system']['table_blocks_modname'],'modname', htmlspecialchars($blockSet['module'], ENT_QUOTES));
			foreach($jieqiModules as $k=>$v){
				$modselect->addOption($k, htmlspecialchars($v['caption'], ENT_QUOTES));
			}
			$blocks_form->addElement($modselect);
		}else{
			$this->db->setCriteria(new Criteria('modname', $blockSet['module'], "="));
			$this->db->criteria->add(new Criteria('classname', $blockSet['classname']));
			$this->db->queryObjects();
			$block = $this->db->getObject();
			if(is_object($block)){
				$blockSet['description']=$block->getVar('description','n');
			}
			$blocks_form = new JieqiThemeForm($jieqiLang['system']['edit_system_block'], 'blockedit', $this->getAdminurl('manageblocks', 'method=edited&configid='.$_REQUEST['configid'].'&key='.$_REQUEST['key']));
			$blockfile=$blockSet['filename'].'.php';
			$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_filename'], jieqi_htmlstr($blockfile)));
			if(isset($jieqiModules[$blockSet['module']])) $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_modname'], jieqi_htmlstr($jieqiModules[$blockSet['module']]['caption'])));
			else $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_modname'], LANG_UNKNOWN));
			$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['table_blocks_blockname'], 'blockname', 30, 50, htmlspecialchars($blockSet['blockname'], ENT_QUOTES)), true);
		}
		//显示位置
		$sideary=array(JIEQI_SIDEBLOCK_LEFT=>'左边', JIEQI_SIDEBLOCK_RIGHT=>'右边', JIEQI_CENTERBLOCK_LEFT=>'中左', JIEQI_CENTERBLOCK_RIGHT=>'中右', JIEQI_CENTERBLOCK_TOP=>'中上', JIEQI_CENTERBLOCK_MIDDLE=>'中中', JIEQI_CENTERBLOCK_BOTTOM=>'中下', JIEQI_TOPBLOCK_ALL=>'顶部', JIEQI_BOTTOMBLOCK_ALL=>'底部');
		
		$sideselect=new JieqiFormSelect($jieqiLang['system']['table_blocks_side'],'side',  htmlspecialchars($blockSet['side'], ENT_QUOTES));
		$sideselect->addOptionArray($sideary);
		$blocks_form->addElement($sideselect);
		//排列序号
		/*
		$eleweight=new JieqiFormText($jieqiLang['system']['table_blocks_weight'], 'weight', 8, 8,  htmlspecialchars($_REQUEST['key'], ENT_QUOTES));
		$eleweight->setDescription($jieqiLang['system']['note_block_weight']);
		$blocks_form->addElement($eleweight);
		*/
		//是否显示
		$showradio=new JieqiFormRadio($jieqiLang['system']['table_blocks_publish'], 'publish',  htmlspecialchars($blockSet['publish'], ENT_QUOTES));
		$showradio->addOption(0, $jieqiLang['system']['block_show_no']);
		$showradio->addOption(1, $jieqiLang['system']['block_show_logout']);
		$showradio->addOption(2, $jieqiLang['system']['block_show_login']);
		$showradio->addOption(3, $jieqiLang['system']['block_show_both']);
		$blocks_form->addElement($showradio);
		//区块标题
		$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_title'], 'title',  htmlspecialchars($blockSet['title'], ENT_QUOTES), 3, 60));
		//内容类型
		if($blockSet['custom']==1){
			$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], 'HTML'));
		}else{
			$tmpary=array(JIEQI_CONTENT_TXT=>'纯文本', JIEQI_CONTENT_HTML=>'纯HTML', JIEQI_CONTENT_JS=>'纯JAVASCRIPT', JIEQI_CONTENT_MIX=>'HTML和SCRIPT混合', JIEQI_CONTENT_PHP=>'PHP代码');
			if(isset($tmpary[$blockSet['contenttype']]))	$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], $tmpary[$blockSet['contenttype']]));
			else $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], LANG_UNKNOWN));
		}
		//区块内容
		if($blockSet['custom']==1){
			$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_content'], 'content', htmlspecialchars($blockSet['content'], ENT_QUOTES), 10, 60));
		}else{
			//区块描述
			$blockdesc=trim($blockSet['description']);
			if(!empty($blockdesc)) $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_description'], $blockdesc));
		}

		//参数设置
		if($blockSet['hasvars']){
			$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_blockvars'], 'blockvars', htmlspecialchars($blockSet['vars'], ENT_QUOTES), 3, 60));
			$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['block_template_file'], 'blocktemplate', 30, 50, htmlspecialchars($blockSet['template'], ENT_QUOTES)), true);
			if($blockSet['hasvars']==2) $blocks_form->addElement(new JieqiFormHidden('cacheupdate', '1'));
		}

		$blocks_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['save_block'], 'submit'));
		$jieqi_contents = '<br />'.$blocks_form->render(JIEQI_FORM_MIDDLE).'<br />';
		return $data = array('jieqi_contents' => $jieqi_contents);
	}
	
	
	/*区块编辑提交处理
	* 
	*/
	public function edited(){
		unset($jieqiBlocks);
		global $jieqiPower, $jieqiModules, $jieqiBlocks, $jieqiLang;//print_r($_REQUEST);exit;
		$_REQUEST = $this->getRequest();
		//检查权限
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}
		$this->addLang('system', 'blocks');

		$_REQUEST['configid']=intval($_REQUEST['configid']);
		$this->db->init('blockconfigs','id','system');
		$this->db->setCriteria(new Criteria('id', jieqi_dbslashes($_REQUEST['configid']), "="));
		$this->db->queryObjects();
		$modconfig = $this->db->getObject();
		if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
		jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
		if(!isset($jieqiBlocks[$_REQUEST['key']])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
		$blockSet=$jieqiBlocks[$_REQUEST['key']];
		$blockSet['blockname']=$_REQUEST['blockname'];
		$blockSet['side']=$_REQUEST['side'];
		$blockSet['publish']=$_REQUEST['publish'];
		$blockSet['title']=$_REQUEST['title'];
		if($blockSet['hasvars']){
			$blockSet['vars']=$_REQUEST['blockvars'];
			$blockSet['template']=$_REQUEST['blocktemplate'];
		}
		if($blockSet['custom']==1 && isset($_REQUEST['content'])){
			$this->db->init('blocks','bid','system');
			$this->db->edit(intval($blockSet['bid']), array('content'=>jieqi_dbslashes($_REQUEST['content'])), $ishtml = true);
			
			global $jieqiCache;
			$bid = $blockSet['bid'];
			$modname = $blockSet['module'];
			$val = $_REQUEST['content'];
			$fname='.html';
			$cache_file = JIEQI_CACHE_PATH;
			if(!empty($modname) && $modname != 'system') $cache_file.='/modules/'.$modname;
			if(is_numeric($bid)) $cache_file .= '/templates/blocks/block_custom'.$bid.$fname;
			else $cache_file .= '/templates/blocks/'.$bid.'.html';
			if($fname != '.php') $jieqiCache->set($cache_file, $val);
			else{
				jieqi_checkdir(dirname($cache_file), true);
				jieqi_writefile($cache_file, $val);
			}
		}

		$jieqiBlocks[$_REQUEST['key']]=$blockSet;
		//更新配置文件
		jieqi_setconfigs($modconfig->getVar('file', 'n'),'jieqiBlocks',$jieqiBlocks,$modconfig->getVar('modules', 'n'));

		//更新参数设置区块的缓存
		if($_REQUEST['cacheupdate']==1){
			include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
			$jieqiTpl =& JieqiTpl::getInstance();
			$this->updateContent($blockSet);
			unset($jieqiTpl);
			unset($vars);
		}
		jieqi_jumppage('?controller=manageblocks&method=listblock&configid='.$_REQUEST['configid'],LANG_DO_SUCCESS,$jieqiLang['system']['block_update_success']);
	}



	/**--------------------------------------------------------------------------------------                                                           
	 * 更新区块缓存
	 * 
	 * @param      void
	 * @access     private
	 * @return     string
	 */
	function updateContent(&$blockvars, $isreturn=false){
		global $jieqiCache, $jieqiTpl;
		$ret='';
		if(!empty($blockvars['bid'])){
			$this->db->init('blocks','bid','system');
			$block = $this->db->get(intval($blockvars['bid']));
			if(is_array($block)){
				switch($block['contenttype']){
					case JIEQI_CONTENT_TXT:
						$ret=$this->db->getFormat($block['content'],'s');
						break;
					case JIEQI_CONTENT_HTML:
						$ret=$this->db->getFormat($block['content'],'n');
						break;
					case JIEQI_CONTENT_JS:
						$ret='<script language="javascript" type="text/javascript">'.$this->db->getFormat($block['content'],'n').'</script>';
						break;
					case JIEQI_CONTENT_MIX:
						$ret=$this->db->getFormat($block['content'],'n');
						break;
					case JIEQI_CONTENT_PHP:
						break;
				}
				$this->saveContent($block['bid'], $block['modname'], $block['contenttype'], $ret);
			}else{
				$ret='block not exists! (id:'.$blockvars['bid'].')';
			}
		}elseif(!empty($blockvars['filename']) && preg_match('/^\w+$/', $blockvars['filename'])){
			$blockpath = ($blockvars['module'] == 'system') ? JIEQI_ROOT_PATH : $GLOBALS['jieqiModules'][$blockvars['module']]['path'];
			$blockpath .= '/templates/blocks/'.$blockvars['filename'].'.html';
			$ret=jieqi_readfile($blockpath);
			$this->saveContent($blockvars['filename'], $blockvars['module'], JIEQI_CONTENT_HTML, $ret);
		}else{
			$ret='empty block id!';
		}
		if($isreturn) return $ret;
	}
	
	

	/**--------------------------------------------------------------------------------------                                                           
	 * 保存区块缓存文件
	 * 
	 * @param      区块id, 模块名称, 内容类型, ,区块内容
	 * @access     public
	 * @return     boolean
	 */
	public function saveContent($bid, $modname, $contenttype, &$content){
		global $jieqiCache, $jieqiTpl;
		$ret=false;
		if(!empty($bid) && !empty($modname)){
			$val='';
			$fname='';
			switch($contenttype){
				case JIEQI_CONTENT_TXT:
				$val=jieqi_htmlstr($content);
				$fname='.html';
				break;	
				case JIEQI_CONTENT_HTML:
				$val=$content;
				$fname='.html';
				break;
				case JIEQI_CONTENT_JS:
				$val=$content;
				$fname='.html';
				break;
				case JIEQI_CONTENT_MIX:
				$val=$content;
				$fname='.html';
				break;
			}
			if(!empty($fname)){
				$cache_file = JIEQI_CACHE_PATH;
				if(!empty($modname) && $modname != 'system') $cache_file.='/modules/'.$modname;
				if(is_numeric($bid)) $cache_file .= '/templates/blocks/block_custom'.$bid.$fname;
				else $cache_file .= '/templates/blocks/'.$bid.'.html';
				if($fname != '.php') $jieqiCache->set($cache_file, $val);
				else{
					jieqi_checkdir(dirname($cache_file), true);
					jieqi_writefile($cache_file, $val);
				}
				$ret=true;
			}
		}
		return $ret;
	}
} 
?>