<?php 
/** 
 * 系统管理->区块管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class blocksModel extends Model{
	public function main($params = array()){
//		global $jieqiPower;
//		$_REQUEST = $this->getRequest();
		//载入语言
		$this->addLang('system', 'blocks');
		$jieqiLang['system'] = $this->getLang('system');
		//取得设置
		//处理增加、删除
		$updatefile=false;
		if(isset($params['action']) && !empty($params['action'])){
			switch($params['action']){
				case 'new':
					$params['blockname']=trim($params['blockname']);
					$params['modname']=trim($params['modname']);
					$errtext='';
					if (strlen($params['blockname'])==0) $errtext .= $jieqiLang['system']['need_block_name'].'<br />';
					if (strlen($params['modname'])==0) $errtext .= $jieqiLang['system']['need_block_modname'].'<br />';
					if(empty($errtext)) {
						$this->db->init('blocks','bid','system');
						$data = array('blockname'=>$params['blockname'],
								'modname'=>$params['modname'],
								'filename'=>'',
								'classname'=>'BlockSystemCustom',
								'side'=>$params['side'],
								'title'=>$params['title'],
								'description'=>'',
								'content'=>$params['content'],
								'vars'=>'',
								'template'=>'',
								'cachetime'=>0,
								'contenttype'=>'JIEQI_CONTENT_HTML',
								'weight'=>'BlockSystemCustom',
								'showstatus'=>0,
								'custom'=>1,
								'canedit'=>1,
								'publish'=>$params['publish'],
								'hasvars'=>0,);
						if(!$this->db->add($data)) jieqi_printfail($jieqiLang['system']['block_add_failure']);
					}else{
						jieqi_printfail($errtext);
					}
					break;
				case 'delete':
					if(isset($params['id']) && !empty($params['id'])){
						$this->db->init('blocks','bid','system');
						$dataDb = $this->db->get($params['id']);
						if(is_array($dataDb)){
							if($dataDb['custom'] == 1){
								//用户自定义区块直接删除
								if($this->db->delete($params['id'], $cache = true)) $updatefile=false;
							}elseif($dataDb['hasvars'] > 0){
								//有参数的系统区块，至少保留一个
								$this->db->setCriteria(new Criteria('modname', $dataDb['modname']));
								$this->db->criteria->add(new Criteria('classname', $params['classname']));
								if($this->db->getCount($this->db->criteria)> 1){
									if($blocks_handler->delete($params['id'])) $updatefile=true;
								}else{
									jieqi_printfail($jieqiLang['system']['block_less_one']);
								}
								unset($criteria);
							}
						}
					}
					break;
			}
		}
		$data = $this->blkDisplay();	
		return $data;
}

	/**--------------------------------------------------------------------------------------                                                           
	 * 区块编辑
	 * 
	 * @param      void
	 * @access     public
	 * @return     array
	 */
    public function blockedit($params = array()){
//	    global $jieqiLang;
//	    $_REQUEST = $this->getRequest();
		//载入语言
		$this->addLang('system', 'blocks');
		$jieqiLang['system'] = $this->getLang('system');
		//取得设置
		if(empty($params['id'])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
		
		include_once(JIEQI_ROOT_PATH.'/class/blocks.php');
		$blocks_handler =& JieqiBlocksHandler::getInstance('JieqiBlocksHandler');
		$block= $blocks_handler->get($params['id']);
		//if(!is_object($block)) jieqi_printfail($jieqiLang['system']['block_not_exists']);
		
		$this->db->init('blocks','bid','system');
		if(!$blocks= $this->getFormat($this->db->get($params['id']), 'e')){
		    jieqi_printfail($jieqiLang['system']['block_not_exists']);
		}

		//区块名称
		$this->db->init('modules','mid','system');
		$this->db->setCriteria(new Criteria('publish', 1));
		$this->db->criteria->setSort('weight');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		$modules=array();
		while($v = $this->db->getObject()){
		    $modules[$v->getVar('name','n')]=$v->getVar('caption', 'n');
		}
		$modules['system'] = LANG_MODULE_SYSTEM;
		
		//编辑区块
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		if($blocks['custom']==1){
			$blocks_form = new JieqiThemeForm($jieqiLang['system']['edit_custom_block'], 'blockedit', $this->getAdminurl('blocks', 'method=update'));
			$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['table_blocks_blockname'], 'blockname', 30, 50, $blocks['blockname']), true);
			//模块选择
			$modselect=new JieqiFormSelect($jieqiLang['system']['table_blocks_modname'],'modname', $blocks['modname']);
			$modselect->addOptionArray($modules);
			$blocks_form->addElement($modselect);
		}else{
			$blocks_form = new JieqiThemeForm($jieqiLang['system']['edit_system_block'], 'blockedit', $this->getAdminurl('blocks', 'method=update'));
			$blockfile=$blocks['filename'].'.php';
			$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_filename'], $blockfile));
			if(isset($modules[$blocks['modname']]))	$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_modname'], $modules[$blocks['modname']]));
			else $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_modname'], LANG_UNKNOWN));
			$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['table_blocks_blockname'], 'blockname', 30, 50, $blocks['blockname']), true);
		}
		//显示位置
		$sideary=$blocks_handler->getSideary();
		$sideselect=new JieqiFormSelect($jieqiLang['system']['table_blocks_side'],'side', $blocks['side']);
		$sideselect->addOptionArray($sideary);
		$blocks_form->addElement($sideselect);
		//排列序号
		$eleweight=new JieqiFormText($jieqiLang['system']['table_blocks_weight'], 'weight', 8, 8, $blocks['weight']);
		$eleweight->setDescription($jieqiLang['system']['note_block_weight']);
		$blocks_form->addElement($eleweight);
		//是否显示
		$showradio=new JieqiFormRadio($jieqiLang['system']['table_blocks_publish'], 'publish', $blocks['publish']);
		$showradio->addOption(0, $jieqiLang['system']['block_show_no']);
		$showradio->addOption(1, $jieqiLang['system']['block_show_logout']);
		$showradio->addOption(2, $jieqiLang['system']['block_show_login']);
		$showradio->addOption(3, $jieqiLang['system']['block_show_both']);
		$blocks_form->addElement($showradio);
		//区块标题
		$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_title'], 'title', $blocks['title'], 3, 60));
		//内容类型
		if($blocks['custom']==1){
			$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], 'HTML'));	
		}else{
			$tmpary=$blocks_handler->getContentary();
			if(isset($tmpary[$blocks['contenttype']]))	$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], $tmpary[$blocks['contenttype']]));
			else $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], LANG_UNKNOWN));	
		}
		//区块内容
		if($blocks['canedit']==1){
			$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_content'], 'content', $blocks['content'], 10, 60));
		}else{
			//区块描述
			$blockdesc=trim($blocks['description']);
			if(!empty($blockdesc)) $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_description'], $blockdesc));
		}
		
		//参数设置
		if($blocks['hasvars']){
			$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_blockvars'], 'blockvars', $blocks['vars'], 3, 60));
			$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['block_template_file'], 'blocktemplate', 30, 50, $blocks['template']));
			$saveradio=new JieqiFormRadio($jieqiLang['system']['block_save_type'], 'savetype', 0);
			$saveradio->addOptionArray(array('0'=>$jieqiLang['system']['block_save_self'], '1'=>$jieqiLang['system']['block_save_another']));
			$blocks_form->addElement($saveradio);
			if($blocks['hasvars']==2) $blocks_form->addElement(new JieqiFormHidden('cacheupdate', '1'));
		}
		
		$blocks_form->addElement(new JieqiFormHidden('action', 'update'));
		$blocks_form->addElement(new JieqiFormHidden('id', $blocks['bid']));
		$blocks_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['save_block'], 'submit'));
		
		return '<br />'.$blocks_form->render(JIEQI_FORM_MIDDLE).'<br />';
	}


	/**--------------------------------------------------------------------------------------                                                           
	 * 区块编辑提交
	 * 
	 * @param      void
	 * @access     public
	 * @return     array
	 */
	public function update($params = array()){
//		global $jieqiPower;
//		$_REQUEST = $this->getRequest();
//		//检查权限
//		$this->db->init('power','pid','system');
//		$this->db->setCriteria(new Criteria('modname', 'system', "="));
//		$this->db->criteria->setSort('pid');
//		$this->db->criteria->setOrder('ASC');
//		$this->db->queryObjects();
//		while($v = $this->db->getObject()){
//			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
//		}
		
		//载入语言
		$this->addLang('system', 'blocks');
		$jieqiLang['system'] = $this->getLang('system');
		//取得设置
		//处理增加、修改、删除
		$updatefile=false;
		if(empty($params['id'])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
		$newBlock = 2;//数据块编辑
		$data = array();
		$this->db->init('blocks','bid','system');
		$block= $this->getFormat($this->db->get($params['id']), 'e');
		if(is_array($block)){
			$data['side'] = $params['side'];
			$data['title'] = $params['title'];
			$stype=0;
			$data['weight'] = $params['weight'];
			$data['publish'] = $params['publish'];
			$params['blockname']=trim($params['blockname']);
			if(!empty($params['blockname'])) $data['blockname'] = $params['blockname'];
			//自定义内容
			if($block['custom']==1){
				$modename=trim($params['modname']);
				if(!empty($params['modname'])) $data['modname'] = $params['modname'];
				$data['contenttype'] = JIEQI_CONTENT_HTML;
			}
			//可编辑内容
			if($block['canedit']==1){
				$data['content'] = $params['content'];
			}
			//可设置参数的区块
			if($block['hasvars'] > 0){
				$data['vars'] = trim($params['blockvars']);
				$data['template'] = trim($params['blocktemplate']);
				//增加区块
				if($params['savetype']==1){
					$newBlock = 1;
					$data['showstatus'] = 0;
					$data['bid'] = 0;
				}
			}
			//增加记录日志
			include_once(JIEQI_ROOT_PATH.'/include/funlogs.php');
			$ary['reason'] = $jieqiLang['system']['edit_system_block'];
			$ary['chginfo'] = sprintf($jieqiLang['system']['block_edit_success'], $block['blockname']);
			$ary['chglog'] = $params['content'];
			jieqi_logs_set($ary);
			if($newBlock == 1){
				if(!($block['bid'] = $this->db->add($data))) jieqi_printfail($jieqiLang['system']['block_edit_failure']);
			}else{
				if(!$this->db->edit($params['id'], $data)) jieqi_printfail($jieqiLang['system']['block_edit_failure']);
			}
			//更新自定义区块的缓存
			if($block['custom'] == 1) {
				global $jieqiCache;
				$bid = $block['bid'];
				$modname = $block['modname'];
				if(!empty($bid) && !empty($modname)){
					$val=$params['content'];
					$fname='.html';
					if(!empty($fname)){
						$cache_file = JIEQI_CACHE_PATH;
						if(!empty($modname) && $modname != 'system') $cache_file.='/modules/'.$modname;
						if(is_numeric($bid)) $cache_file .= '/templates/blocks/block_custom'.$bid.$fname;
						else $cache_file .= '/templates/blocks/'.$bid.'.html';
						if($fname != '.php') $jieqiCache->set($cache_file, $val);
						else{//exit($cache_file);
							jieqi_checkdir(dirname($cache_file), true);
							jieqi_writefile($cache_file, $val);
						}
					}
				}
			}
			//更新参数设置区块的缓存
			if($_POST['cacheupdate']==1){
				include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
				$jieqiTpl =& JieqiTpl::getInstance();
				$vars=array('bid'=>$block['bid'], 'blockname'=>$block['blockname'], 'module'=>$block['modname'], 'filename'=>$this->db->getFormat($block['filename'], 'n'), 'classname'=>$this->db->getFormat($block['classname'], 'n'), 'side'=>$this->db->getFormat($block['side'], 'n'), 'title'=>$this->db->getFormat($block['title'], 'n'), 'vars'=>$this->db->getFormat($block['vars'], 'n'), 'template'=>$this->db->getFormat($block['template'], 'n'), 'contenttype'=>$this->db->getFormat($block['contenttype'], 'n'), 'custom'=>$this->db->getFormat($block['custom'], 'n'), 'publish'=>$this->db->getFormat($block['publish'], 'n'), 'hasvars'=>$this->db->getFormat($block['hasvars'], 'n'));
				$this->updateContent($vars);
				//unset($jieqiTpl);
				unset($vars);
			}
			//$updatefile=true;
		}else{
			jieqi_printfail($jieqiLang['system']['block_not_exists']);
		}
		$data = $this->blkDisplay();	
		return $data;
	}


	/**--------------------------------------------------------------------------------------                                                           
	 * 区块删除
	 * 
	 * @param      void
	 * @access     public
	 * @return     array
	 */
	public function blockdel($params = array()){
//		global $jieqiPower;
//		$_REQUEST = $this->getRequest();
//		//检查权限
//		$this->db->init('power','pid','system');
//		$this->db->setCriteria(new Criteria('modname', 'system', "="));
//		$this->db->criteria->setSort('pid');
//		$this->db->criteria->setOrder('ASC');
//		$this->db->queryObjects();
//		while($v = $this->db->getObject()){
//			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
//		}
		
		//载入语言
		$this->addLang('system', 'blocks');
		$jieqiLang['system'] = $this->getLang('system');
		//取得设置
		//处理删除
		$updatefile=false;
		if(isset($params['id']) && !empty($params['id'])){
			$this->db->init('blocks','bid','system');
			$block= $this->getFormat($this->db->get($params['id']), 'e');
			if(is_array($block)){
				if($block['custom'] == 1){
					//用户自定义区块直接删除
					if($this->db->delete($params['id'])) $updatefile=false;
				}elseif($block['hasvars'] > 0){
					//有参数的系统区块，至少保留一个
					$this->db->setCriteria(new Criteria('modname', $this->db->getFormat($block['modname'],'n')));
					$this->db->criteria->add(new Criteria('classname', $this->db->getFormat($block['classname'],'n')));
					if($this->db->getCount($this->db->criteria) > 1){
						if($this->db->delete($params['id'])) $updatefile=true;
					}else{
						jieqi_printfail($jieqiLang['system']['block_less_one']);
					}
					unset($criteria);
				}
			}
		}

		$data = $this->blkDisplay();	
		return $data;
	}
	
	/**--------------------------------------------------------------------------------------                                                           
	 * 区块管理显示
	 * 
	 * @param      void
	 * @access     public
	 * @return     array
	 */
	public function blkDisplay(){
		//显示参数
		//区块名称
		$this->db->init('modules','mid','system');
		$this->db->setCriteria(new Criteria('publish', 1, "="));
		$this->db->criteria->setSort('weight');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		$modules=array();
		while($v = $this->db->getObject()){
			$modules[$v->getVar('name','n')]=$v->getVar('caption', 'n');
		}
		$modules['system'] = LANG_MODULE_SYSTEM;
		
		//显示位置
		$sideary=array(JIEQI_SIDEBLOCK_LEFT=>'左边', JIEQI_SIDEBLOCK_RIGHT=>'右边', JIEQI_CENTERBLOCK_LEFT=>'中左', JIEQI_CENTERBLOCK_RIGHT=>'中右', JIEQI_CENTERBLOCK_TOP=>'中上', JIEQI_CENTERBLOCK_MIDDLE=>'中中', JIEQI_CENTERBLOCK_BOTTOM=>'中下', JIEQI_TOPBLOCK_ALL=>'顶部', JIEQI_BOTTOMBLOCK_ALL=>'底部');

		//显示区块列表
		$mod = $_REQUEST['modules'];
		$this->db->init('blocks','bid','system');
		$this->db->setCriteria();
		if(isset($mod) && !empty($mod)) {
			$this->db->criteria->add(new Criteria('modname', $mod,'='));
		}else{
			$this->db->criteria->add(new Criteria('custom',1,'='));
		}
		$this->db->criteria->setSort('modname ASC, weight');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		$blockary=array();
		$jieqinewBlocks=array();
		$sortary=array();
		$k=0;
		$point=0;
		if(JIEQI_URL == '') $site_url='http://'.$_SERVER['HTTP_HOST'];
		else $site_url=JIEQI_URL;	
		while($v = $this->db->getObject()){
			$blockary[$k]['bid']=$v->getVar('bid');
			$blockary[$k]['blockname']=$v->getVar('blockname');
			$blockary[$k]['modname']=$modules[$v->getVar('modname', 'n')];
			if(isset($sideary[$v->getVar('side', 'n')])) $blockary[$k]['side']= $sideary[$v->getVar('side', 'n')];
	    	else $blockary[$k]['side']= '隐藏';	

			$blockary[$k]['weight']=$v->getVar('weight');
			$publishType = $v->getVar('publish', 'n');
			if($publishType==3) $blockary[$k]['publish']= '都显示';
			elseif($publishType==1) $blockary[$k]['publish']= '登陆前显示';
			elseif($publishType==2) $blockary[$k]['publish']= '登陆后显示';
			else $blockary[$k]['publish']= '不显示';
			
			$blockary[$k]['action']='<a href="'.$this->getAdminurl('blocks', 'method=blockedit&id='.$v->getVar('bid')).'" target="_self">'.$this->getLang('system', 'block_action_edit').'</a>';
			if($v->getVar('custom')==1){
				$blockary[$k]['action'].=' <a href="javascript:if(confirm(\''.$this->getLang('system', 'block_delete_cofirm').'\')) document.location=\''.$this->getAdminurl('blocks', 'method=blockdel&id='.$v->getVar('bid')).'\';" target="_self">'.$this->getLang('system', 'block_action_delete').'</a>';
			}else{
				$blockary[$k]['action'].=' <a href="'.$this->getAdminurl('blocks', 'method=blockupdate&id='.$v->getVar('bid')).'" target="_blank">'.$this->getLang('system', 'block_action_refresh').'</a>';
				if($v->getVar('custom')) $blockary[$k]['action'].=' <a href="javascript:if(confirm(\''.$this->getLang('system', 'block_delete_cofirm').'\')) document.location=\''.$this->getAdminurl('blocks', 'method=blockdel&id='.$v->getVar('bid')).'\';" target="_self">'.$this->getLang('system', 'block_action_delete').'</a>';
			}
		
			$blockary[$k]['configtext']=htmlspecialchars('$jieqiBlocks[]=array(\'bid\'=>'.$v->getVar('bid').', \'blockname\'=>\''.$v->getVar('blockname').'\', \'module\'=>\''.$v->getVar('modname','n').'\', \'filename\'=>\''.$v->getVar('filename', 'n').'\', \'classname\'=>\''.$v->getVar('classname', 'n').'\', \'side\'=>'.$v->getVar('side', 'n').', \'title\'=>\''.$v->getVar('title', 'n').'\', \'vars\'=>\''.$v->getVar('vars', 'n').'\', \'template\'=>\''.$v->getVar('template', 'n').'\', \'contenttype\'=>'.$v->getVar('contenttype', 'n').', \'custom\'=>'.$v->getVar('custom', 'n').', \'publish\'=>3, \'hasvars\'=>'.$v->getVar('hasvars', 'n').');');
			
			$blockary[$k]['jstext']=htmlspecialchars('<script language="javascript" type="text/javascript" src="'.$site_url.'/blockshow.php?bid='.urlencode($v->getVar('bid')).'&module='.urlencode($v->getVar('modname','n')).'&filename='.urlencode($v->getVar('filename', 'n')).'&classname='.urlencode($v->getVar('classname', 'n')).'&vars='.urlencode($v->getVar('vars', 'n')).'&template='.urlencode($v->getVar('template', 'n')).'&contenttype='.urlencode($v->getVar('contenttype', 'n')).'&custom='.$v->getVar('custom', 'n').'&publish=3&hasvars='.urlencode($v->getVar('hasvars', 'n')).'"></script>');
			
			if($updatefile && $v->getVar('publish')>0){
				$jieqinewBlocks[$point]=array('bid'=>$v->getVar('bid'), 'blockname'=>$v->getVar('blockname'), 'module'=>$v->getVar('modname'), 'filename'=>$v->getVar('filename', 'n'), 'classname'=>$v->getVar('classname', 'n'), 'side'=>$v->getVar('side', 'n'), 'title'=>$v->getVar('title', 'n'), 'vars'=>$v->getVar('vars', 'n'), 'template'=>$v->getVar('template', 'n'), 'contenttype'=>$v->getVar('contenttype', 'n'), 'custom'=>$v->getVar('custom', 'n'), 'publish'=>$v->getVar('publish', 'n'), 'hasvars'=>$v->getVar('hasvars', 'n'));
				$sortary[$point]=$v->getVar('weight','n');
				$point++;
			}
			$k++;
		}
		
		$blocks = $blockary;
		//保存的配置文件
		if($updatefile){
			asort($sortary);
			$jieqisaveBlocks=array();
			$i=0;
			foreach($sortary as $k=>$v){
				$jieqisaveBlocks[$i]=&$jieqinewBlocks[$k];
				$i++;
			}
			jieqi_setconfigs('blocks', 'jieqiBlocks', $jieqisaveBlocks, 'system');
		}
		//增加自定义区块
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$blocks_form = new JieqiThemeForm($jieqiLang['system']['add_custom_block'], 'blocksnew', $this->getAdminurl('blocks'));
		$blocks_form->addElement(new JieqiFormText($this->getLang('system', 'table_blocks_blockname'), 'blockname', 30, 50, ''), true);
		//显示位置
		$sideselect=new JieqiFormSelect($this->getLang('system', 'table_blocks_side'),'side');
		$sideselect->addOptionArray($sideary);
		$blocks_form->addElement($sideselect);
		//排列序号
		$blocks_form->addElement(new JieqiFormText($this->getLang('system', 'table_blocks_weight'), 'weight', 8, 8, '0'));
		//是否显示
		$showradio=new JieqiFormRadio($this->getLang('system', 'table_blocks_publish'), 'publish', 3);
		$showradio->addOption(0, $this->getLang('system', 'block_show_no'));
		$showradio->addOption(1, $this->getLang('system', 'block_show_logout'));
		$showradio->addOption(2, $this->getLang('system', 'block_show_login'));
		$showradio->addOption(3, $this->getLang('system', 'block_show_both'));
		$blocks_form->addElement($showradio);
		//区块标题
		$blocks_form->addElement(new JieqiFormTextArea($this->getLang('system', 'table_blocks_title').'(HTML)', 'title', '', 3, 60));
		//区块内容
		$blocks_form->addElement(new JieqiFormTextArea($this->getLang('system', 'table_blocks_content').'(HTML格式)', 'content', '', 10, 60));
		
		$blocks_form->addElement(new JieqiFormHidden('action', 'new'));
		$blocks_form->addElement(new JieqiFormHidden('modname', 'system'));
		$blocks_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $this->getLang('system', 'add_block'), 'submit'));
		$form_addblock = '<br />'.$blocks_form->render(JIEQI_FORM_MIDDLE).'<br />';
		return array('blocks'=>$blocks,
					'modules'=>$mod,
					'form_addblock'=>$form_addblock
		);
	}
	
	/**
	 * 刷新区块缓存
	 *
	 * 强制更新区块的缓存
	 * 
	 * 调用模板：无
	 * 
	 * @category   jieqicms
	 * @package    system
	 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
	 * @author     $Author: juny $
	 * @version    $Id: blockupdate.php 176 2008-11-24 08:04:58Z juny $
	 */
	public function blockupdate($params = array()){
		global $jieqiPower, $jieqiBlocks;
//		$_REQUEST = $this->getRequest();
//		//检查权限
//		$this->db->init('power','pid','system');
//		$this->db->setCriteria(new Criteria('modname', 'system', "="));
//		$this->db->criteria->setSort('pid');
//		$this->db->criteria->setOrder('ASC');
//		$this->db->queryObjects();
//		while($v = $this->db->getObject()){
//			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
//		}
		//载入语言
		$this->addLang('system', 'blocks');
		$jieqiLang['system'] = $this->getLang('system');
		//取得设置
		if(!empty($params['id'])){
			$this->db->init('blocks','bid','system');
			$block = $this->db->get($params['id']);
			if(!is_array($block)) jieqi_printfail($jieqiLang['system']['block_not_exists']);
			$blockSet=array('bid'=>$block['bid'], 'blockname'=>$block['blockname'], 'module'=>$block['modname'], 'filename'=>$this->getFormat($block['filename'], 'n'), 'classname'=>$this->getFormat($block['classname'], 'n'), 'side'=>$this->getFormat($block['side'], 'n'), 'title'=>$this->getFormat($block['title'], 'n'), 'vars'=>$this->getFormat($block['vars'], 'n'), 'template'=>$this->getFormat($block['template'], 'n'), 'contenttype'=>$this->getFormat($block['contenttype'], 'n'), 'custom'=>$this->getFormat($block['custom'], 'n'), 'publish'=>$this->getFormat($block['publish'], 'n'), 'hasvars'=>$this->getFormat($block['hasvars'], 'n'));
		}elseif(!empty($params['configid'])){
			$params['configid']=intval($params['configid']);
			$this->db->init('blockconfigs','id','system');
			$this->db->setCriteria(new Criteria('id', jieqi_dbslashes($params['configid'])));
			$this->db->queryObjects();
			$modconfig = $this->db->getObject();
			if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
			unset($jieqiBlocks);
			jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
			if(!isset($jieqiBlocks[$params['key']])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
			$blockSet=$jieqiBlocks[$params['key']];
		}else{
			jieqi_printfail(LANG_ERROR_PARAMETER);
		}

		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$this->updateContent($blockSet);
		jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['system']['block_edit_success'], jieqi_htmlstr($blockSet['blockname'])));
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