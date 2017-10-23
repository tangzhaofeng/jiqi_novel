<?php 
/** 
 * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class groupsModel extends Model{
	public function main($params = array()){
//		$_REQUEST = $this->getRequest();
		//载入语言
		$this->addLang('system', 'groups');
		$jieqiLang['system'] = $this->getLang('system');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');

		if(empty($params['action'])) $params['action']='show';
		switch($params['action']){
			case 'new':
				if(empty($params['groupname'])) jieqi_printfail($jieqiLang['system']['need_group_name']);
				else{
					$this->db->init('groups','groupid','system');
					$data = array('name'=>$params['groupname'],
								'description'=>$params['description'],
								'grouptype'=>'0');
					if(!$this->db->add($data)) jieqi_printfail($jieqiLang['system']['add_group_failure']);
				}
				break;
			case 'update':
				if(!empty($params['id']) && !empty($params['groupname'])){
					$this->db->init('groups','groupid','system');
					$data = array('name'=>$params['groupname'],
								'description'=>$params['description']);
					if(!$this->db->edit($params['id'], $data)) jieqi_printfail($jieqiLang['system']['edit_group_failure']);
				}
				break;
			case 'edit';
				if(!empty($params['id'])){
					$this->db->init('groups','groupid','system');
					$dataDb = $this->db->get($params['id']);
					if(is_array($dataDb)){
						//include_once(JIEQI_ROOT_PATH.'/admin/header.php');
						$groups_form = new JieqiThemeForm($jieqiLang['system']['edit_group'], 'groupsedit', $this->getAdminurl('groups'));
						$groups_form->addElement(new JieqiFormText($jieqiLang['system']['table_groups_groupname'], 'groupname', 30, 50, $dataDb['name']), true);
						$groups_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_groups_description'], 'description', $dataDb['description'], 5, 50));
						$groups_form->addElement(new JieqiFormHidden('action', 'update'));
						$groups_form->addElement(new JieqiFormHidden('id', $params['id']));
						$groups_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SAVE, 'submit'));
						$jieqi_contents = '<br />'.$groups_form->render(JIEQI_FORM_MIDDLE).'<br />';
						return jieqi_contents;
						exit;
					}
				}
				break;
		}
        $this->db->init('groups','groupid','system');
		$this->db->setCriteria();
		$this->db->criteria->setSort('groupid');
	    $this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		$groups=array();
		$groupary=array();
		$i=0;
		while($v = $this->db->getObject()){
			$groupary[$v->getVar('groupid')]=$v->getVar('name');
			$groups[$i]['groupid']=$v->getVar('groupid');
			$groups[$i]['name']=$v->getVar('name');
			$groups[$i]['description']=$v->getVar('description');
			$groups[$i]['grouptype']=$v->getVar('grouptype');
			
			$groups[$i]['url_edit']=$this->getAdminurl('groups','method=edit_view&groupid='.$v->getVar('groupid'));
			$groups[$i]['url_del']=$this->getAdminurl('groups','method=del&id='.$v->getVar('groupid'));
			$i++;
		}

		$groups_form = new JieqiThemeForm($jieqiLang['system']['add_group'], 'groupsnew', $this->getAdminurl('groups'));
		$groups_form->addElement(new JieqiFormText($jieqiLang['system']['table_groups_groupname'], 'groupname', 30, 50, ''), true);
		$groups_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_groups_description'], 'description', '', 5, 50));
		$groups_form->addElement(new JieqiFormHidden("action", "new"));
		$groups_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['add_group'], 'submit'));
		$form_addgroup = "<br />".$groups_form->render(JIEQI_FORM_MIDDLE)."<br />";	
		$this->synchronization_groups_config();
		return array('groups'=>$groups,
					 'form_addgroup'=>$form_addgroup
		);	
	} 
	
	//edit form
	function editForm($id){
		$form = '';
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		//载入语言
		$this->addLang('system', 'groups');
		$jieqiLang['system'] = $this->getLang('system');
		if(!empty($id)){
			$this->db->init('groups','groupid','system');
			$dataDb = $this->db->get($id);
			$dataDb = $this->getFormat($dataDb,'e');
			if(is_array($dataDb)){
				$groups_form = new JieqiThemeForm($jieqiLang['system']['edit_group'], 'groupsedit', $this->getAdminurl('groups','method=modify&groupid='.$id));
				$groups_form->addElement(new JieqiFormText($jieqiLang['system']['table_groups_groupname'], 'groupname', 30, 50, $dataDb['name']), true);
				$groups_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_groups_description'], 'description', $dataDb['description'], 5, 50));
				$groups_form->addElement(new JieqiFormHidden('action', 'update'));
				$groups_form->addElement(new JieqiFormHidden('id', $id));
				$groups_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SAVE, 'submit'));
				$form =  '<br />'.$groups_form->render(JIEQI_FORM_MIDDLE).'<br />';
			}
		}
		return $form;
	}
	/** modify
	 * @param	$obj	用户组对象
	 */ 
	function modify($obj){
		//载入语言
		$this->addLang('system', 'groups');
		$jieqiLang['system'] = $this->getLang('system');
		if(!empty($obj['id']) && !empty($obj['name'])){
			$this->db->init('groups','groupid','system');
			//edit第二个参数不能有id
			if(!$this->db->edit($obj['id'], array('name'=>$obj['name'],'description'=>$obj['description']))){
				jieqi_printfail($jieqiLang['system']['edit_group_failure']);
			}else{
				$this->synchronization_groups_config();
			}
		}
		header("Location:".$this->getAdminurl('groups')); 
	}
	//del
	function del($id){
//		$_REQUEST = $this->getRequest();
		//载入语言
		$this->addLang('system', 'groups');
		$jieqiLang['system'] = $this->getLang('system');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		if(!empty($id)){
			$this->db->init('groups','groupid','system');
        	$this->db->delete($id);
   	 	}
		$this->db->init('groups','groupid','system');
		$this->db->setCriteria();
		$this->db->criteria->setSort('groupid');
	    $this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		$groups=array();
		$groupary=array();
		$i=0;
		while($v = $this->db->getObject()){
			$groupary[$v->getVar('groupid')]=$v->getVar('name');
			$groups[$i]['groupid']=$v->getVar('groupid');
			$groups[$i]['name']=$v->getVar('name');
			$groups[$i]['description']=$v->getVar('description');
			$groups[$i]['grouptype']=$v->getVar('grouptype');
			
			$groups[$i]['url_edit']=$this->getAdminurl('groups','method=edit_view&groupid='.$v->getVar('groupid'));
			$groups[$i]['url_del']=$this->getAdminurl('groups','method=del&id='.$v->getVar('groupid'));
			$i++;
		}

		$groups_form = new JieqiThemeForm($jieqiLang['system']['add_group'], 'groupsnew', $this->getAdminurl('groups'));
		$groups_form->addElement(new JieqiFormText($jieqiLang['system']['table_groups_groupname'], 'groupname', 30, 50, ''), true);
		$groups_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_groups_description'], 'description', '', 5, 50));
		$groups_form->addElement(new JieqiFormHidden("action", "new"));
		$groups_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['add_group'], 'submit'));
		$form_addgroup = "<br />".$groups_form->render(JIEQI_FORM_MIDDLE)."<br />";	

		$this->synchronization_groups_config();
		return array('groups'=>$groups,
					 'form_addgroup'=>$form_addgroup
		);
		//header("Location:".$this->getAdminurl('groups')); 
	}
	/** 
	 * 同步groups数据库和配置文件 * @copyright   Copyright(c) 2014 
	 * @author      chengyuan* @version     1.0 
	 */
	public function	synchronization_groups_config(){
		$this->db->init('groups','groupid','system');
		$this->db->setCriteria();
		//$this->db->criteria->add(new Criteria('modname', $mod, '='));
		$this->db->criteria->setSort('groupid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		$groupary=array();
		while($v = $this->db->getObject()){
		    $groupary[$v->getVar('groupid')]=$v->getVar('name');
		}
		jieqi_setconfigs('groups', 'jieqiGroups', $groupary, 'system');
    $publicdata=str_replace('?><?php', '', jieqi_readfile(JIEQI_ROOT_PATH.'/configs/system.php').jieqi_readfile(JIEQI_ROOT_PATH.'/lang/lang_system.php').jieqi_readfile(JIEQI_ROOT_PATH.'/configs/groups.php'));
	jieqi_writefile(JIEQI_ROOT_PATH.'/configs/define.php', $publicdata);
		//$this->cache_write('system','groups',$this->db->lists(),'groupid');
	}
	/*更新模型配置文件
	* para array
	* return void
	
	function updateCfgFile($groupary){
		//数据有变动。更新文件
		if((!empty($_REQUEST['id']) || !empty($_REQUEST['groupname'])) && count($groupary)>0){
			jieqi_setconfigs('groups', 'jieqiGroups', $groupary, 'system');
			$publicdata=str_replace('?><?php', '', jieqi_readfile(JIEQI_ROOT_PATH.'/configs/system.php').jieqi_readfile(JIEQI_ROOT_PATH.'/lang/lang_system.php').jieqi_readfile(JIEQI_ROOT_PATH.'/configs/groups.php'));
			jieqi_writefile(JIEQI_ROOT_PATH.'/configs/define.php', $publicdata);
		}
	}*/
} 
?>