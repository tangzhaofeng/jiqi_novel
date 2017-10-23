<?php
/** 
 * 系统管理->VIP级别管理 * @copyright   Copyright(c) 2014 
 * @author      liujilei* @version     1.0 
 */ 
class vipgradeModel extends Model{
	public function main($params = array()){
		//载入语言
		$this->addLang('system', 'vipgrade');
		$jieqiLang['system'] = $this->getLang('system');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$this->db->init('vipgrade','vipgradeid','system');
		$this->db->setCriteria();
		$this->db->criteria->setSort('minscore');
	    $this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		$vipgrade=array();
		$vipgradearr=array();
		$i=0;
		while($v = $this->db->getObject()){
			$nameary=explode(' ', $v->getVar('caption'));
			$vipgradearr[$v->getVar('vipgradeid')]=array('photo'=>$v->getVar('photo'),'caption'=>$nameary[0], 'name'=>$nameary, 'minscore'=>$v->getVar('minscore'), 'maxscore'=>$v->getVar('maxscore'),'setting'=>$v->getVar('setting'));
			
			$vipgrade[$i]['vipgradeid']=$v->getVar('vipgradeid');
			$vipgrade[$i]['photo']=$v->getVar('photo');
			$vipgrade[$i]['caption']=implode('<br />', $nameary);
			$vipgrade[$i]['minscore']=$v->getVar('minscore');
			$vipgrade[$i]['maxscore']=$v->getVar('maxscore');
			$vipgrade[$i]['vipgradetype']=$v->getVar('vipgradetype');
			$vipgrade[$i]['url_edit']=$this->getAdminurl('vipgrade','method=edit&id='.$v->getVar('vipgradeid'));
			$vipgrade[$i]['url_del']=$this->getAdminurl('vipgrade','method=del&id='.$v->getVar('vipgradeid'));
			$i++;
		}
		//$jieqiTpl->assign_by_ref('vipgrade', $vipgrade);
		$vipgrade_form = new JieqiThemeForm($jieqiLang['system']['add_vipgrade'], 'vipgradenew', $this->getAdminurl('vipgrade', 'method=add'));
		$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_caption'], 'caption', 30, 250, ''), true);
		$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_minscore'], 'minscore', 30, 50, ''), true);
		$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_maxscore'], 'maxscore', 30, 50, ''), true);
		$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_photo'], 'photo', 30, 50,''), false);
		$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_head1'], 'setting[jifenjiasu]', 30, 50,''), false);
		$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_head2'], 'setting[dingyuezhekou]', 30, 50,''), false);
		$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_head3'], 'setting[baodiyuepiao]', 30, 50,''), false);
		$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_head4'], 'setting[xiaofeiyuepiao]', 30, 50,''), false);
		$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_head5'], 'setting[tuijianpiao]', 30, 50,''), false);
		$vipgrade_form->addElement(new JieqiFormHidden("action", "add"));
		$vipgrade_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['add_vipgrade'], 'submit'));
		$form_addvipgrade = "<br />".$vipgrade_form->render(JIEQI_FORM_MIDDLE)."<br />";
		
		//数据有变动。更新文件
		if((!empty($params['id']) || !empty($params['caption'])) && count($vipgradearr)>0){
			jieqi_setconfigs('vipgrade', 'jieqiVipgrade', $vipgradearr, 'system');
		}

		return array('vipgrade'=>$vipgrade,'form_addvipgrade'=>$form_addvipgrade);
	}
	
	//modify
	function modify($obj){
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		//载入语言
		$this->addLang('system', 'vipgrade');
		$jieqiLang['system'] = $this->getLang('system');
		if(!empty($obj['id']) && !empty($obj['caption'])){
			$this->db->init('vipgrade','vipgradeid','system');
			$dataDb = $this->db->get($obj['id']);
			/*$head['head1']=$obj['head1'];
			$head['head2']=$obj['head2'];
			$head['head3']=$obj['head3'];
			$head['head4']=$obj['head4'];
			$head['head5']=$obj['head5'];*/
			if(is_array($dataDb)){
				$errtext='';
				if(empty($obj['caption'])) $errtext .= $jieqiLang['system']['need_vipgrade_caption'].'<br />';
				if(!is_numeric($obj['minscore'])) $errtext .= $jieqiLang['system']['need_minscore_num'].'<br />';
				if(!is_numeric($obj['maxscore'])) $errtext .= $jieqiLang['system']['need_maxscore_num'].'<br />';
				$dataDb['minscore']=intval($obj['minscore']);
				$dataDb['maxscore']=intval($obj['maxscore']);
				$dataDb['photo']=$obj['photo'];
				$dataDb['caption']=$obj['caption'];
				$dataDb['setting']=serialize($obj['setting']) ;
				if($obj['maxscore'] < $obj['minscore']) $errtext .= $jieqiLang['system']['max_than_min'].'<br />';
				if(empty($errtext)) {
					$id = $obj['id'];
					if(!$this->db->edit($id,$dataDb)) jieqi_printfail($jieqiLang['system']['edit_vipgrade_failure']);
				}else{
					jieqi_printfail($errtext);
				}
			}
		}
		header("Location:".$this->getAdminurl('vipgrade','id='.$id)); 
	}
	//edit form
	function edit($id){
		$form = '';
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		//载入语言
		$this->addLang('system', 'vipgrade');
		$jieqiLang['system'] = $this->getLang('system');
		if(!empty($id)){
			$this->db->init('vipgrade','vipgradeid','system');
			$dataDb = $this->db->get($id);
			$setting = unserialize($dataDb['setting']);
			//$dataDb = $this->getFormat($dataDb,'n');
			if(is_array($dataDb)){
				$vipgrade_form = new JieqiThemeForm($jieqiLang['system']['edit_vipgrade'], 'vipgradeedit', $this->getAdminurl('vipgrade'));
				$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_caption'], 'caption', 30, 250, $dataDb['caption']), true);
				$vipgrade_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_vipgrade_minscore'], 'minscore', $dataDb['minscore'], 5, 50));
				$vipgrade_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_vipgrade_maxscore'], 'maxscore', $dataDb['maxscore'], 5, 50));
				$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_photo'], 'photo', 30, 50,$dataDb['photo']), false);
				$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_head1'], 'setting[jifenjiasu]', 30, 50,$setting['jifenjiasu']), false);
				$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_head2'], 'setting[dingyuezhekou]', 30, 50,$setting['dingyuezhekou']), false);
				$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_head3'], 'setting[baodiyuepiao]', 30, 50,$setting['baodiyuepiao']), false);
				$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_head4'], 'setting[xiaofeiyuepiao]', 30, 50,$setting['xiaofeiyuepiao']), false);
				$vipgrade_form->addElement(new JieqiFormText($jieqiLang['system']['table_vipgrade_head5'], 'setting[tuijianpiao]', 30, 50,$setting['tuijianpiao']), false);
		
				$vipgrade_form->addElement(new JieqiFormHidden('method', 'modify'));
				$vipgrade_form->addElement(new JieqiFormHidden('id', $id));
				$vipgrade_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SAVE, 'submit'));
				$form = '<br />'.$vipgrade_form->render(JIEQI_FORM_MIDDLE).'<br />';
				
			}
		}
		return $form;
	}
	
	//del
	function del($id){
		if(!empty($id)){
			$this->db->init('vipgrade','vipgradeid','system');
			$this->db->delete($id);
		}
		
		header("Location:".$this->getAdminurl('vipgrade','id='.$id)); 
	}
	
	/*增加头衔
	* para void
	* return array
	*/
	function add($params = array()){

		//载入语言
		$this->addLang('system', 'vipgrade');
		$jieqiLang['system'] = $this->getLang('system');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		
		$errtext='';
		if(empty($params['caption'])) $errtext .= $jieqiLang['system']['need_vipgrade_caption'].'<br />';
		if(!is_numeric($params['minscore'])) $errtext .= $jieqiLang['system']['need_minscore_num'].'<br />';
		if(!is_numeric($params['maxscore'])) $errtext .= $jieqiLang['system']['need_maxscore_num'].'<br />';
		$params['minscore']=intval($params['minscore']);
		$params['maxscore']=intval($params['maxscore']);
		if($params['maxscore'] < $params['minscore']) $errtext .= $jieqiLang['system']['max_than_min'].'<br />';
		/*$head['head1']=$params['head1'];
		$head['head2']=$params['head2'];
		$head['head3']=$params['head3'];
		$head['head4']=$params['head4'];
		$head['head5']=$params['head5'];*/
		
		if(empty($errtext)) {
			$this->db->init('vipgrade','vipgradeid','system');
			$data = array('caption'=>$params['caption'],
						'minscore'=>$params['minscore'],
						'maxscore'=>$params['maxscore'],
						'setting'=>serialize($params['setting']) ,
						//'setting'=>jieqi_strtosary($params['setting'],'=',',') ,
						'vipgradetype'=>'0',
						'photo' =>$params['photo']
						);
						//print_r($data);exit;
			if(!$id =$this->db->add($data)){
				jieqi_printfail($jieqiLang['system']['add_vipgrade_failure']);
			} else {
			header("Location:".$this->getAdminurl('vipgrade','id='.$id));
			}
		}else{
			jieqi_printfail($errtext);
		}
	}
}
?>