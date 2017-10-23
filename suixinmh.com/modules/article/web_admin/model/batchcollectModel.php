<?php 
/**
 * 批量采集
 * @author huliming  2014-9-12
 *
 */
include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
class batchcollectModel extends Model{
	public function main($params = array()){
		//加载文章配置
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		//加载采集配置
		$this->addConfig('article','collectsite');
		$jieqiCollectsite = $this->getConfig('article','collectsite');
		//加载语言包
		$this->addLang('article','collect');
		$this->addLang('article','article');
		$jieqiLang['article'] = $this->getLang('article');
		//提交数据$this->submitcheck() || 
		if($params['action']){
		    if($params['action'] == 'collect') return $this->collect($params);
			elseif($params['action'] == 'pagecollect') return $this->pagecollect($params);
			else return $this->bcollect($params);
		}
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$collect_form = new JieqiThemeForm($jieqiLang['article']['batch_collect_useid'], 'frmcollect', $this->getAdminurl());
		$siteid=new JieqiFormSelect($jieqiLang['article']['collect_siteid'], 'siteid', '1');
		foreach($jieqiCollectsite as $k=>$v){
			$siteid->addOption($k, $v['name']);
		}
		$collect_form->addElement($siteid);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['collect_start_id'], 'startid', 30, 11), true);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['collect_end_id'], 'endid', 30, 11), true);
		$notaddnew=new JieqiFormRadio($jieqiLang['article']['collect_or_addnew'], 'notaddnew', 0);
		$notaddnew->addOption(0, $jieqiLang['article']['collect_is_addnew']);
		$notaddnew->addOption(1, $jieqiLang['article']['collect_not_addnew']);
		$collect_form->addElement($notaddnew);
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_note'],$jieqiLang['article']['collect_addnew_note']));
		$collect_form->addElement(new JieqiFormHidden('action', 'collect'));
		$collect_form->addElement(new JieqiFormHidden('formhash', form_hash()));
		$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['collect_start_button'], 'submit'));
		
		$page_form = new JieqiThemeForm($jieqiLang['article']['batch_collect_usepage'], 'frmpcollect', $this->getAdminurl());
		$siteid=new JieqiFormSelect($jieqiLang['article']['collect_siteid'], 'siteid', '1');
		foreach($jieqiCollectsite as $k=>$v){
			$siteid->addOption($k, $v['name']);
		}
		$page_form->addElement($siteid);
		$page_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_note'],$jieqiLang['article']['collect_page_note']));
		$page_form->addElement(new JieqiFormHidden('action', 'pagecollect'));
		$page_form->addElement(new JieqiFormHidden('formhash', form_hash()));
		$page_form->addElement(new JieqiFormButton('&nbsp;', 'psubmit', $jieqiLang['article']['collect_next_button'], 'submit'));
		
		$batchid_form = new JieqiThemeForm($jieqiLang['article']['batch_collect_uselist'], 'frmbcollect', $this->getAdminurl());
		$siteid=new JieqiFormSelect($jieqiLang['article']['collect_siteid'], 'siteid', '1');
		foreach($jieqiCollectsite as $k=>$v){
			$siteid->addOption($k, $v['name']);
		}
		$batchid_form->addElement($siteid);
		$batchids=new JieqiFormTextArea($jieqiLang['article']['collect_batch_id'],'batchids',"",5,60);
		$batchid_form->addElement($batchids, true);
		$notaddnew=new JieqiFormRadio($jieqiLang['article']['collect_or_addnew'], 'notaddnew', 0);
		$notaddnew->addOption(0, $jieqiLang['article']['collect_is_addnew']);
		$notaddnew->addOption(1, $jieqiLang['article']['collect_not_addnew']);
		$batchid_form->addElement($notaddnew);
		$batchid_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_note'],$jieqiLang['article']['collect_batchid_note']));
		$batchid_form->addElement(new JieqiFormHidden('action', 'bcollect'));
		$batchid_form->addElement(new JieqiFormHidden('formhash', form_hash()));
		$batchid_form->addElement(new JieqiFormButton('&nbsp;', 'bsubmit', $jieqiLang['article']['collect_start_button'], 'submit'));

		$data ['jieqi_contents'] = '<br />'.$collect_form->render(JIEQI_FORM_MIDDLE).'<br />'.$page_form->render(JIEQI_FORM_MIDDLE).'<br />'.$batchid_form->render(JIEQI_FORM_MIDDLE);
		return $data;
	}
	//按序号批量采集
	function collect($params){
	    global $jieqiConfigs, $jieqiLang, $jieqiCollectsite;//配置文件
		$errtext='';
		if(!is_numeric($params['siteid'])) $errtext .= $jieqiLang['article']['need_source_site'].'<br />';
		if(!is_numeric($params['startid'])) $errtext .= $jieqiLang['article']['need_start_articleid'].'<br />';
		if(!is_numeric($params['endid'])) $errtext .= $jieqiLang['article']['need_end_articleid'].'<br />';
		if(empty($errtext)) {
			if($params['startid']>$params['endid']){
				jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['batch_collect_success']);
				exit;
			}
			if(array_key_exists($params['siteid'], $jieqiCollectsite) && $jieqiCollectsite[$params['siteid']]['enable']=='1'){
			    @set_time_limit(0);
			    @session_write_close();
			    ini_set('memory_limit', '800M');
			    $collect = $this->load('collect', 'article');
		        $collect->init($params['siteid']);
				echo sprintf($jieqiLang['article']['checking_article_now'], $params['startid']).'<br />';
				ob_flush();
                flush();
				$aid=$params['startid'];
				$collect->updateone($aid, $params['notaddnew']);
				//采集下一个
				$params['startid']++;
				$url=$this->getAdminurl('batchcollect','action=collect&siteid='.urlencode($params['siteid']).'&startid='.urlencode($params['startid']).'&endid='.urlencode($params['endid']).'&notaddnew='.urlencode($params['notaddnew']).'&formhash='.form_hash());//echo $url;exit;
				echo sprintf($jieqiLang['article']['collect_next_html'], JIEQI_CHAR_SET, $showinfo, $url, $url);
			}else{
				jieqi_printfail($jieqiLang['article']['not_support_collectsite']);
			}
		}else{
			jieqi_printfail($errtext);
		}
	}
	
	//按序号列表批量采集
	function bcollect($params){
	    global $jieqiConfigs, $jieqiLang, $jieqiCollectsite;//配置文件
		$errtext='';
		if(!is_numeric($params['siteid'])) $errtext .= $jieqiLang['article']['need_source_site'].'<br />';
		if(empty($params['batchids'])) $errtext .= $jieqiLang['article']['need_collect_articleid'].'<br />';
		if(empty($errtext)) {
			if(array_key_exists($params['siteid'], $jieqiCollectsite) && $jieqiCollectsite[$params['siteid']]['enable']=='1'){
			    @set_time_limit(0);
			    @session_write_close();
			    ini_set('memory_limit', '800M');
			    $collect = $this->load('collect', 'article');
		        $collect->init($params['siteid']);
				$idsary=explode(',', $params['batchids']);
				foreach($idsary as $aid){
					$aid=trim($aid);
					if(!empty($aid)){
						echo sprintf($jieqiLang['article']['checking_article_now'], $aid).'<br />';
						ob_flush();
						flush();
						$collect->updateone($aid, $params['notaddnew']);
					}
				}
				jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['batch_collect_success']);	
			}else{
				jieqi_printfail($jieqiLang['article']['not_support_collectsite']);
			}
		}else{
			jieqi_printfail($errtext);
		}
	}
	
	//按页面批量采集
	function pagecollect($params){
	    global $jieqiConfigs, $jieqiLang, $jieqiCollectsite;//配置文件
		$collect = $this->load('collect', 'article');
		$collect->init($params['siteid']);
		if(isset($params['notaddnew']) && isset($params['collectname'])){
		    @set_time_limit(0);
			@session_write_close();
			ini_set('memory_limit', '800M');
			$params['collectname']=intval($params['collectname']);
			if(!isset($collect->jieqiCollect['listcollect'][$params['collectname']])) jieqi_printfail($jieqiLang['article']['rule_not_exists']);
			if(empty($params['collectpagenum']) || !is_numeric($params['collectpagenum'])) $params['collectpagenum']=1;
			if(!empty($params['startpageid'])) $startpageid=$params['startpageid'];
			else $startpageid=trim($collect->jieqiCollect['listcollect'][$params['collectname']]['startpageid']);
			$startpageid = $startpageid ? $startpageid : 1;
			if(!empty($params['maxpagenum']) && is_numeric($params['maxpagenum'])) $maxpagenum=intval($params['maxpagenum']);
			else $maxpagenum=intval($collect->jieqiCollect['listcollect'][$params['collectname']]['maxpagenum']);
			
			$url=str_replace('<{pageid}>', $startpageid, $collect->jieqiCollect['listcollect'][$params['collectname']]['urlpage']);
			$source=$collect->getPage($url,'listcollect');
		    if(empty($source)) jieqi_printfail(sprintf($jieqiLang['article']['collect_url_failure'], $url, $url));
			
			//获取文章序号
			$pregstr=jieqi_collectstoe($collect->jieqiCollect['listcollect'][$params['collectname']]['articleid']);
			if(!empty($pregstr)) $matchvar=jieqi_cmatchall($pregstr, $source);
	
			if(empty($matchvar)) jieqi_printfail($jieqiLang['article']['parse_articleid_failure']);
			if(is_array($matchvar)) $aidsary=$matchvar;
			else $aidsary=array();
	        if($aidsary){
				//下一页参数
				$nextpageid='';
				if($collect->jieqiCollect['listcollect'][$params['collectname']]['nextpageid']=='++'){
					$nextpageid=intval($startpageid)+1;
				}else{
					$pregstr=jieqi_collectstoe($collect->jieqiCollect['listcollect'][$params['collectname']]['nextpageid']);
					if(!empty($pregstr)) $matchvar=jieqi_cmatchone($pregstr, $source);
					if(!empty($matchvar)) $nextpageid=trim(jieqi_textstr($matchvar));
				}
				$aid=0;
				//echo str_repeat(' ',1024*4);
				echo sprintf($jieqiLang['article']['page_collect_doing'], $collect->jieqiCollect['sitename'], $collect->jieqiCollect['listcollect'][$params['collectname']]['title'], $params['collectpagenum'], count($aidsary));
				ob_flush();
				flush();
				$cpoint=1;
				foreach($aidsary as $v){
					$aid=trim($v);
					echo sprintf($jieqiLang['article']['page_checkid_doing'], $cpoint, $aid);
					ob_flush();
					flush();
					$cpoint++;
					$collect->updateone($aid, $params['notaddnew']);
				}
			}
			//采集下一页
			if($nextpageid=='' || ($maxpagenum>0 && $params['collectpagenum']>=$maxpagenum) || !$aidsary){
				jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['batch_collect_success']);
				exit;
			}else{
				$_REQUEST['startid']++;
				$url=$this->getAdminurl('batchcollect','action=pagecollect&siteid='.$params['siteid'].'&collectname='.$params['collectname'].'&startpageid='.urlencode($nextpageid).'&maxpagenum='.$maxpagenum.'&collectpagenum='.($params['collectpagenum']+1).'&notaddnew='.urlencode($params['notaddnew']).'&formhash='.form_hash());
				$showinfo=sprintf($jieqiLang['article']['page_collect_next'], $params['collectpagenum'], $maxpagenum ? $maxpagenum : '全部');
				echo sprintf($jieqiLang['article']['page_collect_html'], JIEQI_CHAR_SET, $showinfo, $url, $url);
				exit;
			}
		}
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$collect_form = new JieqiThemeForm($jieqiLang['article']['batch_collect_usepage'], 'frmcollect', $this->getAdminurl());
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_siteid'],$collect->jieqiCollect['sitename']));
		$collectname=new JieqiFormSelect($jieqiLang['article']['collect_name'], 'collectname', '0');
		if(is_array($collect->jieqiCollect['listcollect'])){
			foreach($collect->jieqiCollect['listcollect'] as $k=>$v){
				$collectname->addOption($k, $v['title']);
			}
		}
		$collect_form->addElement($collectname);
		$startpageid=new JieqiFormText($jieqiLang['article']['collect_start_pageid'], 'startpageid', 30, 11);
		$startpageid->setDescription($jieqiLang['article']['collect_page_emptynote']);
		$collect_form->addElement($startpageid);
		$maxpagenum=new JieqiFormText($jieqiLang['article']['collect_max_pagenum'], 'maxpagenum', 30, 11);
		$maxpagenum->setDescription($jieqiLang['article']['collect_page_note']);
		$collect_form->addElement($maxpagenum);
		$notaddnew=new JieqiFormRadio($jieqiLang['article']['collect_or_addnew'], 'notaddnew', 0);
		$notaddnew->addOption(0, $jieqiLang['article']['collect_is_addnew']);
		$notaddnew->addOption(1, $jieqiLang['article']['collect_not_addnew']);
		$collect_form->addElement($notaddnew);
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_not_addnew'], $jieqiLang['article']['collect_page_note']));
		$collect_form->addElement(new JieqiFormHidden('siteid', $params['siteid']));
		$collect_form->addElement(new JieqiFormHidden('action', 'pagecollect'));
		$collect_form->addElement(new JieqiFormHidden('formhash', form_hash()));
		$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['collect_start_button'], 'submit'));
		$data ['jieqi_contents'] = '<br />'.$collect_form->render(JIEQI_FORM_MIDDLE);
		return $data;
	}
}
?>