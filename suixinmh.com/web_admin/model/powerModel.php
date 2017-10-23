<?php 
/** 
 * 系统管理->权限设置 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class powerModel extends Model{
	public function main($params = array()){
		//global $jieqiPower;
//		$_REQUEST = $this->getRequest();
		if(empty($params['mod'])) $params['mod']='system';
        $jieqiPower[$params['mod']] = $this->getDbPower($params['mod']);
		//载入语言
		$this->addLang('system', 'power');
		$jieqiLang['system'] = $this->getLang('system');
		if(count($jieqiPower[$params['mod']])>0){
			if(isset($params['action']) && $params['action']=='update'){
				foreach($jieqiPower[$params['mod']] as $k => $v){
					if(!isset($params[$k])) $params[$k]='';
					if($v['groups'] != $params[$k]){
						$jieqiPower[$params['mod']][$k]['groups']=$params[$k];
						$this->db->query("UPDATE ".jieqi_dbprefix('system_power')." SET pgroups='".jieqi_dbslashes(serialize($params[$k]))."' WHERE modname='".jieqi_dbslashes($params['mod'])."' AND pname='".jieqi_dbslashes($k)."'");
					}
				}
				jieqi_setconfigs('power', 'jieqiPower', $jieqiPower, $params['mod']);
				jieqi_jumppage($this->getAdminurl('power','mod='.$params['mod']),LANG_DO_SUCCESS, $jieqiLang['system']['edit_power_success']);
			}else{
				//显示权限设置
				include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
            	$this->db->init('groups','groupid','system');
				$this->db->setCriteria();
				$this->db->criteria->setSort('groupid');
	       		$this->db->criteria->setOrder('ASC');
				$this->db->queryObjects();
				while($v = $this->db->getObject()){
					if($v->getVar('groupid') != JIEQI_GROUP_ADMIN) $groups[]=array('groupid'=>$v->getVar('groupid'), 'name'=>$v->getVar('name'));
				}
				$power_form = new JieqiThemeForm($jieqiLang['system']['edit_power'], 'power', $this->getAdminurl('power'));
				foreach($jieqiPower[$params['mod']] as $k => $v){
					$params[$k]=new JieqiFormCheckBox($v['caption'], $k, $v['groups']);
					foreach($groups as $group){
						$params[$k]->addOption($group['groupid'], $group['name']);
					}
					$power_form->addElement($params[$k], false);
				}
				$power_form->addElement(new JieqiFormHidden('mod', $params['mod']));
				$power_form->addElement(new JieqiFormHidden('action', 'update'));
				$power_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['save_power'], 'submit'));
				$jieqi_contents = '<br />'.$power_form->render(JIEQI_FORM_MIDDLE).'<br />';
				return $jieqi_contents;
			}
		}else{
			jieqi_msgwin(LANG_NOTICE, $jieqiLang['system']['no_usage_power']);
		}	
	}

} 
?>