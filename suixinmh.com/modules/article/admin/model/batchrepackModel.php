<?php 
/** 
 * 小说连载->文章生成HTML * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class batchrepackModel extends Model{
	public function main($params = array()){
	     global $jieqiModules;
	     $this->addConfig('article','configs');
		 $this->addLang('article','manage');
		 $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
	     $jieqiConfigs['article'] = $this->getConfig('article','configs');
		 $article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		 $article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		 //提交数据
		 if($this->submitcheck()){
		      if($params['action']=='packwithid'){
					if(!empty($params['flagary'])){
						$params['flagary']=unserialize(urldecode($params['flagary']));
					}else $params['flagary']=$params['packflag'];
					if(!is_array($params['flagary']) || count($params['flagary'])<1) jieqi_printfail($jieqiLang['article']['need_repack_option']);
					if(empty($params['fromid']) || !is_numeric($params['fromid'])) jieqi_printfail($jieqiLang['article']['repack_start_id']);
					if(empty($params['toid'])) $params['toid']=0;
					if($params['fromid']>$params['toid']){
						jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['batch_repack_success'], $article_static_url.'/admin/?controller=batchrepack'));
					}
					$this->db->init('article','articleid','article');
					if(!$article=$this->db->get($params['fromid'])) $showinfo=$jieqiLang['article']['repack_noid_next'];
					else{
						$articlename=$article['articlename'];
						$package = $this->load('article','article');//加载文章处理类
						$ptypes=array();
						foreach($params['flagary'] as $v) $ptypes[$v]=1;
						echo '                                                                                                                                                                                                                                                                ';
						echo sprintf($jieqiLang['article']['repack_fromto_id'], $articlename, $params['fromid'], $params['toid']);
						ob_flush();
				        flush();
						$package->article_repack($params['fromid'], $ptypes, 1);
						$showinfo=$jieqiLang['article']['repack_success_next'];
					}
					$params['fromid']++;
					$url=$article_static_url.'/admin/?controller=batchrepack&action=packwithid&fromid='.$params['fromid'].'&toid='.$params['toid'];
					foreach($params['packflag'] as $k=>$v){
						$url.='&packflag['.$k.']='.$v;
					}
					echo sprintf($jieqiLang['article']['repack_next_html'], JIEQI_CHAR_SET, $showinfo, $url, $url);
			  }else{
					if(!empty($params['flagary'])){
						$params['flagary']=unserialize(trim($params['flagary']));
					}else $params['flagary']=$params['packflag'];
					if(!is_array($params['flagary']) || count($params['flagary'])<1) jieqi_printfail($jieqiLang['article']['need_repack_option']);
					$starttime=trim($params['starttime']);
					$stoptime=trim($params['stoptime']);
					$startlimit=trim($params['startlimit']);
					if(empty($starttime)) jieqi_printfail($jieqiLang['article']['need_time_format']);
					if(!is_numeric($starttime)){
						$starttime=mktime((int)substr($starttime,11,2), (int)substr($starttime,14,2), (int)substr($starttime,17,2), (int)substr($starttime,5,2), (int)substr($starttime,8,2), (int)substr($starttime,0,5));
					}
					if(empty($stoptime)) $stoptime=JIEQI_NOW_TIME;
					if(!is_numeric($stoptime)){
						$stoptime=mktime((int)substr($stoptime,11,2), (int)substr($stoptime,14,2), (int)substr($stoptime,17,2), (int)substr($stoptime,5,2), (int)substr($stoptime,8,2), (int)substr($stoptime,0,5));
					}
					$this->db->init('article','articleid','article');
					$this->db->setCriteria(new Criteria('siteid', JIEQI_SITE_ID, '='));
					if(empty($startlimit)) $startlimit=0;
					$this->db->criteria->add(new Criteria('lastupdate', $starttime, '>='));
					$this->db->criteria->add(new Criteria('lastupdate', $stoptime, '<='));
					$this->db->criteria->setSort('lastupdate');
					$this->db->criteria->setOrder('ASC');
					$this->db->criteria->setStart($startlimit);
					$this->db->criteria->setLimit(1);
					$this->db->queryObjects($this->db->criteria);
					$article=$this->db->getObject();
					if(is_object($article)){
						$articlename=$article->getVar('articlename');
						$package = $this->load('article','article');//加载文章处理类
						$ptypes=array();
						foreach($params['flagary'] as $v) $ptypes[$v]=1;
						echo '                                                                                                                                                                                                                                                                ';
						echo sprintf($jieqiLang['article']['batch_repack_doing'], $articlename, date('Y-m-d H:i:s', $starttime), date('Y-m-d H:i:s', $stoptime), date('Y-m-d H:i:s', $article->getVar('lastupdate')), $article->getVar('articleid'));
						ob_flush();
				flush();
						$package->article_repack($article->getVar('articleid'), $ptypes, 1);
						$showinfo=$jieqiLang['article']['repack_success_next'];
					}else{
						jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['batch_repack_success'], $article_static_url.'/admin/?controller=batchrepack'));
						exit;
					}
					$startlimit++;
					$url=$article_static_url.'/admin/?controller=batchrepack&action=packwithtime&starttime='.$starttime.'&stoptime='.$stoptime.'&startlimit='.$startlimit.'&formhash='.$params['formhash'];
					foreach($params['packflag'] as $k=>$v){
						$url.='&packflag['.$k.']='.$v;
					}
					echo sprintf($jieqiLang['article']['repack_next_html'], JIEQI_CHAR_SET, $showinfo, $url, $url);
			  }
		 }
		//重新生成
		include_once(HLM_ROOT_PATH.'/lib/html/formloader.php');
		$repack_form = new JieqiThemeForm($jieqiLang['article']['repack_use_id'], 'batchrepack', $article_static_url.'/admin/?controller=batchrepack');
		$repack_form->addElement(new JieqiFormText($jieqiLang['article']['repack_start_id'], 'fromid', 20, 15),true);
		$repack_form->addElement(new JieqiFormText($jieqiLang['article']['repack_end_id'], 'toid', 20, 15));
		$checkbox=new JieqiFormCheckBox($jieqiLang['article']['repack_select'], 'packflag');
		$checkbox->addOption('makeopf', $jieqiLang['article']['repack_opf']);
		$checkbox->addOption('makehtml', $jieqiLang['article']['repack_html']);
		$checkbox->addOption('makezip', $jieqiLang['article']['repack_zip']);
		//$checkbox->addOption('makefull', $jieqiLang['article']['repack_fullpage']);
		$checkbox->addOption('maketxtfull', $jieqiLang['article']['repack_txtfullpage']);
		$checkbox->addOption('makeumd', $jieqiLang['article']['repack_umdpage']);
		$checkbox->addOption('makejar', $jieqiLang['article']['repack_jarpage']);
		$repack_form->addElement($checkbox, false);
		$repack_form->addElement(new JieqiFormHidden('action', 'packwithid'));
		$repack_form->addElement(new JieqiFormHidden('formhash', form_hash()));
		$repack_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['repack_start_button'], 'submit'));
		
		$timepack_form = new JieqiThemeForm($jieqiLang['article']['repack_use_time'], 'timerepack', $article_static_url.'/admin/?controller=batchrepack');
		$starttime=new JieqiFormText($jieqiLang['article']['repack_start_time'], 'starttime', 20, 20,  date('Y-m-d 00:00:00'));
		$starttime->setDescription($jieqiLang['article']['repack_time_format']);
		$timepack_form->addElement($starttime,true);
		$stoptime=new JieqiFormText($jieqiLang['article']['repack_end_time'], 'stoptime', 20, 20, date('Y-m-d H:i:s'));
		$stoptime->setDescription($jieqiLang['article']['repack_time_format']);
		$timepack_form->addElement($stoptime);
		$checkbox1=new JieqiFormCheckBox($jieqiLang['article']['repack_select'], 'packflag');
		$checkbox1->addOption('makeopf', $jieqiLang['article']['repack_opf']);
		$checkbox1->addOption('makehtml', $jieqiLang['article']['repack_html']);
		$checkbox1->addOption('makezip', $jieqiLang['article']['repack_zip']);
		$checkbox1->addOption('makefull', $jieqiLang['article']['repack_fullpage']);
		$checkbox1->addOption('maketxtfull', $jieqiLang['article']['repack_txtfullpage']);
		$checkbox1->addOption('makeumd', $jieqiLang['article']['repack_umdpage']);
		$checkbox1->addOption('makejar', $jieqiLang['article']['repack_jarpage']);
		$timepack_form->addElement($checkbox1, false);
		$timepack_form->addElement(new JieqiFormHidden('action', 'packwithtime'));
		$timepack_form->addElement(new JieqiFormHidden('formhash', form_hash()));
		$timepack_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['repack_start_button'], 'submit'));

		 return array(
		      'articletitle'=>$articletitle,
			  'articlerows'=>$articlerows,
			  'article_static_url'=>$article_static_url,
			  'article_dynamic_url'=>$article_dynamic_url,
			  'repack_form'=>$repack_form->render(JIEQI_FORM_MIDDLE).'<br /><br />'.$timepack_form->render(JIEQI_FORM_MIDDLE)
		 );
	}
	
	public function action($params){
		 if(isset($params['action']) && !empty($params['id'])){
		      //$this->db->init('article','articleid','article');
		      switch($params['action']){
			       case 'show'://显示
				       $this->db->edit($params['id'],array('display'=>0));
				   break;
			       case 'hide'://待审
				       $this->db->edit($params['id'],array('display'=>1));
				   break;
			  }
		      
		 }	
	}
	
} 
?>