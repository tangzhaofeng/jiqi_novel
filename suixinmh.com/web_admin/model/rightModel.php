<?php 
/** 
 * 系统管理->权限设置 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class rightModel extends Model{
	public function main($params = array()){
		global $jieqiRight;
//		$_REQUEST = $this->getRequest();
		if(empty($params['mod'])) $params['mod']='system';

		//检查权限
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', $params['mod'], "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[$modname][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}

		//载入权利设置
		include_once(JIEQI_ROOT_PATH.'/class/right.php');
		$right_handler =& JieqiRightHandler::getInstance('JieqiRightHandler');
		$right_handler->getSavedVars($params['mod']);

		//载入语言
		$this->addLang('system', 'right');
		$jieqiLang['system'] = $this->getLang('system');
		if(count($jieqiRight[$params['mod']])>0){
			if(isset($params['action']) && $params['action']=='update'){
				foreach($jieqiRight[$params['mod']] as $k => $v){
					if(isset($params[$k]) && $v['honors'] != $params[$k]){
						$jieqiRight[$params['mod']][$k]['honors']=$params[$k];
						$this->db->query("UPDATE ".jieqi_dbprefix('system_right')." SET rhonors='".jieqi_dbslashes(serialize($params[$k]))."' WHERE modname='".jieqi_dbslashes($params['mod'])."' AND rname='".jieqi_dbslashes($k)."'");
					}
				}
				jieqi_setconfigs('right', 'jieqiRight', $jieqiRight, $params['mod']);
				jieqi_jumppage($this->getAdminurl('right','mod=system'),LANG_DO_SUCCESS, $jieqiLang['system']['edit_right_success']);
			}else{
				//显示权限设置
				include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
            	$this->db->init('honors','honorid','system');
				$this->db->setCriteria();
				$this->db->criteria->setSort('minscore');
	       		$this->db->criteria->setOrder('ASC');
				$this->db->queryObjects();
				while($v = $this->db->getObject()){
					$tmpvar=$v->getVar('caption');
					$tmpary=explode(' ', $tmpvar);
					$honors[]=array('honorid'=>$v->getVar('honorid'), 'caption'=>$tmpary[0]);
				}
				unset($criteria);
				$right_form = new JieqiThemeForm($jieqiLang['system']['edit_right'], 'right', $this->getAdminurl('right'));
				foreach($jieqiRight[$params['mod']] as $k => $v){
					$tmpvar='';
					foreach($honors as $honor){
						$right_text = new JieqiFormText($honor['caption'], $k.'['.$honor['honorid'].']', 20, 60, $v['honors'][$honor['honorid']]);
						$tmpvar.=$right_text->getCaption().' '.$right_text->render().'<br />';
					}
					$right_form->addElement(new JieqiFormLabel($v['caption'], $tmpvar));
				}
				$right_form->addElement(new JieqiFormHidden('mod', $params['mod']));
				$right_form->addElement(new JieqiFormHidden('action', 'update'));
				$right_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['save_right'], 'submit'));
				$jieqi_contents = '<br />'.$right_form->render(JIEQI_FORM_MIDDLE).'<br />';
				return $jieqi_contents;
			}
		}else{
			jieqi_msgwin(LANG_NOTICE, $jieqiLang['system']['no_usage_right']);
		}
	}
} 
?>