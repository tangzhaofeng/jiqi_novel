<?php
/** 
 * 系统管理->头衔管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class honorsModel extends Model{
	public function main($params = array()){
//		global $jieqiPower;
//		$_REQUEST = $this->getRequest();
		
		//检查权限
//		$this->db->init('power','pid','system');
//		$this->db->setCriteria(new Criteria('modname', 'system', "="));
//		$this->db->criteria->setSort('pid');
//		$this->db->criteria->setOrder('ASC');
//		$this->db->queryObjects();
//		while($v = $this->db->getObject()){
//			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
//		}
		
		//载入语言
		$this->addLang('system', 'honors');
		$jieqiLang['system'] = $this->getLang('system');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		
		//include_once(JIEQI_ROOT_PATH.'/class/honors.php');
		//$honors_handler =& JieqihonorsHandler::getInstance('JieqihonorsHandler');
		/*if(empty($params['action'])) $params['action']='show';
		switch($params['action']){
			case 'delete':
				if(!empty($params['id'])){
					$this->db->init('honors','honorid','system');
					$this->db->delete($params['id']);
				}
				break;
			case 'update':
				if(!empty($params['id']) && !empty($params['caption'])){
					$this->db->init('honors','honorid','system');
					$dataDb = $this->db->get($params['id']);
					if(is_array($dataDb)){
						$errtext='';
						if(empty($params['caption'])) $errtext .= $jieqiLang['system']['need_honor_caption'].'<br />';
						if(!is_numeric($params['minscore'])) $errtext .= $jieqiLang['system']['need_minscore_num'].'<br />';
						if(!is_numeric($params['maxscore'])) $errtext .= $jieqiLang['system']['need_maxscore_num'].'<br />';
						$params['minscore']=intval($params['minscore']);
						$params['maxscore']=intval($params['maxscore']);
						if($params['maxscore'] < $params['minscore']) $errtext .= $jieqiLang['system']['max_than_min'].'<br />';
						if(empty($errtext)) {
							$data = array('caption'=>$params['caption'],
								'minscore'=>$params['minscore'],
								'maxscore'=>$params['maxscore'],
								'photo'=>$params['photo']);
								print_r($data );exit;
							if(!$this->db->edit($params['id'], $data)) jieqi_printfail($jieqiLang['system']['edit_honor_failure']);
						}else{
							jieqi_printfail($errtext);
						}
					}
				}
				break;
			case 'edit':
			if(!empty($params['id'])){
				$dataDb = $this->db->get($params['id']);
				if(is_array($dataDb)){
					//include_once(JIEQI_ROOT_PATH.'/admin/header.php');
					$honors_form = new JieqiThemeForm($jieqiLang['system']['edit_honor'], 'honorsedit', $this->getAdminurl('honors'));
					$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_caption'], 'caption', 30, 250, $dataDb['caption']), true);
					$honors_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_honors_minscore'], 'minscore', $dataDb['minscore'], 5, 50));
					$honors_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_honors_maxscore'], 'maxscore', $dataDb['maxscore'], 5, 50));
					$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_photo'], 'photo', 30, 50,$dataDb['photo']), true);
					$honors_form->addElement(new JieqiFormHidden('action', 'update'));
					$honors_form->addElement(new JieqiFormHidden('id', $params['id']));
					$honors_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SAVE, 'submit'));
					$jieqi_contents = '<br />'.$honors_form->render(JIEQI_FORM_MIDDLE).'<br />';
					return jieqi_contents;
					//include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
					exit;
				}
			}
			break;
		}*/
		
		//include_once(JIEQI_ROOT_PATH.'/admin/header.php');
		$this->db->init('honors','honorid','system');
		$this->db->setCriteria();
		$this->db->criteria->setSort('minscore');
	    $this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		$honors=array();
		$honorary=array();
		$i=0;
		while($v = $this->db->getObject()){
			$nameary=explode(' ', $v->getVar('caption'));
			$honorary[$v->getVar('honorid')]=array('photo'=>$v->getVar('photo'),'caption'=>$nameary[0], 'name'=>$nameary, 'minscore'=>$v->getVar('minscore'), 'maxscore'=>$v->getVar('maxscore'));
			
			$honors[$i]['honorid']=$v->getVar('honorid');
			$honors[$i]['photo']=$v->getVar('photo');
			$honors[$i]['caption']=implode('<br />', $nameary);
			$honors[$i]['minscore']=$v->getVar('minscore');
			$honors[$i]['maxscore']=$v->getVar('maxscore');
			$honors[$i]['honortype']=$v->getVar('honortype');
			$honors[$i]['url_edit']=$this->getAdminurl('honors','method=edit_view&id='.$v->getVar('honorid'));
			$honors[$i]['url_del']=$this->getAdminurl('honors','method=del&id='.$v->getVar('honorid'));
			$i++;
		}
		//$jieqiTpl->assign_by_ref('honors', $honors);
		$honors_form = new JieqiThemeForm($jieqiLang['system']['add_honor'], 'honorsnew', $this->getAdminurl('honors', 'method=newHonor'));
		$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_caption'], 'caption', 30, 250, ''), true);
		$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_minscore'], 'minscore', 30, 50, ''), true);
		$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_maxscore'], 'maxscore', 30, 50, ''), true);
		$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_photo'], 'photo', 30, 50,''), false);
		$honors_form->addElement(new JieqiFormHidden("action", "new"));
		$honors_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['add_honor'], 'submit'));
		$form_addhonor = "<br />".$honors_form->render(JIEQI_FORM_MIDDLE)."<br />";
		
		//数据有变动。更新文件
		if((!empty($params['id']) || !empty($params['caption'])) && count($honorary)>0){
			jieqi_setconfigs('honors', 'jieqiHonors', $honorary, 'system');
		}
		
		return array('honors'=>$honors,'form_addhonor'=>$form_addhonor);
	}
	//modify
	function modify($obj){
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		//载入语言
		$this->addLang('system', 'honors');
		$jieqiLang['system'] = $this->getLang('system');
		if(!empty($obj['id']) && !empty($obj['caption'])){
			$this->db->init('honors','honorid','system');
			$dataDb = $this->db->get($obj['id']);
			if(is_array($dataDb)){
				$errtext='';
				if(empty($obj['caption'])) $errtext .= $jieqiLang['system']['need_honor_caption'].'<br />';
				if(!is_numeric($obj['minscore'])) $errtext .= $jieqiLang['system']['need_minscore_num'].'<br />';
				if(!is_numeric($obj['maxscore'])) $errtext .= $jieqiLang['system']['need_maxscore_num'].'<br />';
				$obj['minscore']=intval($obj['minscore']);
				$obj['maxscore']=intval($obj['maxscore']);
				if($obj['maxscore'] < $obj['minscore']) $errtext .= $jieqiLang['system']['max_than_min'].'<br />';
				if(empty($errtext)) {
					$id = $obj['id'];
					unset($obj['id']);
					if(!$this->db->edit($id,$obj)) jieqi_printfail($jieqiLang['system']['edit_honor_failure']);
				}else{
					jieqi_printfail($errtext);
				}
			}
		}
		header("Location:".$this->getAdminurl('honors','id='.$id)); 
	}
	//edit form
	function editForm($id){
		$form = '';
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		//载入语言
		$this->addLang('system', 'honors');
		$jieqiLang['system'] = $this->getLang('system');
		if(!empty($id)){
			$this->db->init('honors','honorid','system');
			$dataDb = $this->db->get($id);
			$dataDb = $this->getFormat($dataDb,'e');
			if(is_array($dataDb)){
				$honors_form = new JieqiThemeForm($jieqiLang['system']['edit_honor'], 'honorsedit', $this->getAdminurl('honors'));
				$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_caption'], 'caption', 30, 250, $dataDb['caption']), true);
				$honors_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_honors_minscore'], 'minscore', $dataDb['minscore'], 5, 50));
				$honors_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_honors_maxscore'], 'maxscore', $dataDb['maxscore'], 5, 50));
				$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_photo'], 'photo', 30, 50,$dataDb['photo']), false);
				$honors_form->addElement(new JieqiFormHidden('method', 'modify'));
				$honors_form->addElement(new JieqiFormHidden('id', $id));
				$honors_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SAVE, 'submit'));
				$form = '<br />'.$honors_form->render(JIEQI_FORM_MIDDLE).'<br />';
			}
		}
		return $form;
	}
	
	//del
	function del($id){
		if(!empty($id)){
			$this->db->init('honors','honorid','system');
			$this->db->delete($id);
		}
		header("Location:".$this->getAdminurl('honors','id='.$id)); 
	}
	
	/*增加头衔
	* para void
	* return array
	*/
	function newHonor($params = array()){
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
		$this->addLang('system', 'honors');
		$jieqiLang['system'] = $this->getLang('system');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		
		$errtext='';
		if(empty($params['caption'])) $errtext .= $jieqiLang['system']['need_honor_caption'].'<br />';
		if(!is_numeric($params['minscore'])) $errtext .= $jieqiLang['system']['need_minscore_num'].'<br />';
		if(!is_numeric($params['maxscore'])) $errtext .= $jieqiLang['system']['need_maxscore_num'].'<br />';
		$params['minscore']=intval($params['minscore']);
		$params['maxscore']=intval($params['maxscore']);
		if($params['maxscore'] < $params['minscore']) $errtext .= $jieqiLang['system']['max_than_min'].'<br />';

		if(empty($errtext)) {
			$this->db->init('honors','honorid','system');
			$data = array('caption'=>$params['caption'],
						'minscore'=>$params['minscore'],
						'maxscore'=>$params['maxscore'],
						'setting'=>'',
						'honortype'=>'0',
						'photo' =>$params['photo']
						);
			if(!$this->db->add($data)) jieqi_printfail($jieqiLang['system']['add_honor_failure']);
		}else{
			jieqi_printfail($errtext);
		}
		
		$this->db->init('honors','honorid','system');
		$this->db->setCriteria();
		$this->db->criteria->setSort('minscore');
	    $this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		$honors=array();
		$honorary=array();
		$i=0;
		while($v = $this->db->getObject()){
			$nameary=explode(' ', $v->getVar('caption'));
			$honorary[$v->getVar('honorid')]=array('photo'=>$v->getVar('photo'), 'caption'=>$nameary[0], 'name'=>$nameary, 'minscore'=>$v->getVar('minscore'), 'maxscore'=>$v->getVar('maxscore'));
			$honors[$i]['honorid']=$v->getVar('honorid');
			$honors[$i]['photo']=$v->getVar('photo');
			$honors[$i]['caption']=implode('<br />', $nameary);
			$honors[$i]['minscore']=$v->getVar('minscore');
			$honors[$i]['maxscore']=$v->getVar('maxscore');
			$honors[$i]['honortype']=$v->getVar('honortype');
			$honors[$i]['url_edit']=$this->getAdminurl('honors','method=edit_view&id='.$v->getVar('honorid'));
			$honors[$i]['url_del']=$this->getAdminurl('honors','method=del&id='.$v->getVar('honorid'));
			$i++;
		}
		//$jieqiTpl->assign_by_ref('honors', $honors);
		$honors_form = new JieqiThemeForm($jieqiLang['system']['add_honor'], 'honorsnew', $this->getAdminurl('honors', 'method=newHonor'));
		$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_caption'], 'caption', 30, 250, ''), true);
		$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_minscore'], 'minscore', 30, 50, ''), true);
		$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_maxscore'], 'maxscore', 30, 50, ''), true);
		$honors_form->addElement(new JieqiFormText($jieqiLang['system']['table_honors_photo'], 'photo', 30, 50, ''), true);
		$honors_form->addElement(new JieqiFormHidden("action", "new"));
		$honors_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['add_honor'], 'submit'));
		$form_addhonor = "<br />".$honors_form->render(JIEQI_FORM_MIDDLE)."<br />";
		
		//数据有变动。更新文件
		if((!empty($params['id']) || !empty($params['caption'])) && count($honorary)>0){
			jieqi_setconfigs('honors', 'jieqiHonors', $honorary, 'system');
		}
		
		return array('honors'=>$honors,'form_addhonor'=>$form_addhonor);
	}
}
?>