<?php 
/**
 * 单篇采集
 * @author huliming  2014-9-12
 *
 */
include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
class collectModel extends Model{
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
		//提交数据
		if($this->submitcheck()){
		    //if($params['action'] == 'newarticle') return $this->newarticle($params);
			//elseif($params['action'] == 'updatecollect') return $this->updatecollect($params);
			//else return $this->collectView($params);
			$function = $params['action'];
			return $this->$function($params);
		}
		
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$collect_form = new JieqiThemeForm($jieqiLang['article']['article_collect_title'], 'frmcollect', $this->getAdminurl());

		if(empty($params['siteid'])) $params['siteid']=1;
		$siteobj=new JieqiFormSelect($jieqiLang['article']['collect_source_site'], 'siteid', $params['siteid']);
		foreach($jieqiCollectsite as $k=>$v){
			$siteobj->addOption($k, $v['name']);
		}
		$collect_form->addElement($siteobj);

		if(empty($params['fromid'])) $params['fromid']='';
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['source_article_id'], 'fromid', 30, 100, $params['fromid']), true);
		if(empty($params['toid'])) $params['toid']='';
		$toidobj=new JieqiFormText($jieqiLang['article']['target_article_id'], 'toid', 30, 11, $params['toid']);
		$toidobj->setDescription($jieqiLang['article']['target_article_note']);
		$collect_form->addElement($toidobj);
        $collect_form->addElement(new JieqiFormHidden('action', 'collectView'));
		$collect_form->addElement(new JieqiFormHidden('formhash', form_hash()));
		$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['collect_next_button'], 'submit'));

		$post_form = new JieqiThemeForm('文章单本上传', 'frmpost', $this->getAdminurl());
		$post_form->setExtra('enctype="multipart/form-data"');
		$post_form->addElement(new JieqiFormFile($jieqiLang['article']['attachment_name'].$i, 'attachfile', 60));
		$volumestart=new JieqiFormText('分卷的开始标记', 'volumestart', 30, 11);
		$volumestart->setDescription('不填默认为：@@@');
		$post_form->addElement($volumestart, false);
		$chapterstart=new JieqiFormText('章节的开始标记', 'chapterstart', 30, 11);
		$chapterstart->setDescription('不填默认为：###');
		$post_form->addElement($chapterstart, false);

		$charset=new JieqiFormSelect('文件编码', 'filecharset', 30);
		$charset->addOption('gbk');
		$charset->addOption('utf-8');
		$charset->setDescription('默认为GBK');
		$post_form->addElement($charset);

		$post_form->addElement(new JieqiFormHidden('action', 'postbook'));
		$post_form->addElement(new JieqiFormHidden('formhash', form_hash()));
		$post_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['collect_start_button'], 'submit'));

		$data ['jieqi_contents'] = '<br />'.$collect_form->render(JIEQI_FORM_MIDDLE).'<br />'.$post_form->render(JIEQI_FORM_MIDDLE);
		return $data;
	}
	
	function postbook($params = array()){
        echo "----------1------------<br>";
        echo $params['filecharset'];
	    global $jieqiConfigs, $jieqiLang,$jieqi_file_postfix;
		include_once ($GLOBALS['jieqiModules']['article']['path'] . '/include/collectfunction.php');
		$postbook = $this->load('postbook','article');
		$auth = $this->getAuth();
		if($params['bookfile']){//print_r($params);


		    if(!is_file($params['bookfile'])) jieqi_printfail('文件不存在！');
			else{
				@set_time_limit(0);
				@session_write_close();
				ini_set('memory_limit', '800M');
				$collect = $this->load('collect', 'article');


				$postbook->init($params['bookfile'],$params['filecharset']);
				$articlerows = $postbook->getArticles();//批量分析
				$chapters = $articlerows['chapters'];
				if(count($chapters)<1) jieqi_printfail('空内容！');
				//检查文章是否已存在
				//$articlename = $params['articlename'] ? $params['articlename'] : $articlerows['articlename'];
				$article=$collect->getArticleByName($parames);
				if(!$article){
				    if(!$params['articlename']) jieqi_printfail('空书名！');
					if(!$article = $collect->newArticle($params)) jieqi_printfail($jieqiLang['article']['collect_newarticle_failure']);
				}
				
				echo str_pad('获取到《'.jieqi_htmlstr($article['articlename']).'》有 '.count($chapters).' 个章节,分析章节中.....<br>',1024*64);
				ob_flush();
				flush();
				$jieqi_collect_time=time(); //采集时间
				include_once(JIEQI_ROOT_PATH.'/lib/text/texttypeset.php');
		     	$texttypeset=new TextTypeset();
				
			    $size=$article['size'];
			    $lastchapter = $startchapter = $article['lastchapter'];
			    $lastchapterid=$article['lastchapterid'];
			    $lastvolume=$article['lastvolume'];
			    $lastvolumeid=$article['lastvolumeid'];
			    $lastchapterorder=$article['chapters'];
				$i=0;
				$startupdate = false;
				foreach($chapters as $key =>$chapter){
				    if(!$startupdate){
						if($startchapter){//寻找上次更新的位置
							if(jieqi_equichapter(preg_replace(array("/\((.*?){3}\)/","/\（(.*?){3}\）/","/\((.*?){3}\）/","/\（(.*?){3}\)/","/\【(.*?){3}\】/","/\《(.*?){3}\》/"),'',$startchapter), preg_replace(array("/\((.*?){3}\)/","/\（(.*?){3}\）/","/\((.*?){3}\）/","/\（(.*?){3}\)/","/\【(.*?){3}\】/","/\《(.*?){3}\》/"),'',$chapter['chaptername'])) ){
								$startupdate = true;
								echo str_pad('获取到《'.jieqi_htmlstr($article['articlename']).'》有 '.(count($chapters)-$key).' 个章节需要更新<br>',1024*64);
								ob_flush();
								flush();
								continue;
							}else continue;
						}else{
							$startupdate = true;
							echo str_pad('《'.jieqi_htmlstr($article['articlename']).'》有 '.count($chapters).' 个章节需要更新<br>',1024*64);
							ob_flush();
							flush();
						}
					}
				    $i++;
				    $this->db->init ( 'chapter', 'chapterid', 'article' );
					$chaptercontent=$texttypeset->doTypeset($chapter['chaptercontent']);
                    $chaptersize = jieqi_strlen ( $chaptercontent );
					$newChapter = array ();
		            $newChapter ['siteid'] = JIEQI_SITE_ID;
					$newChapter ['articleid'] = $article['articleid'];
					$newChapter ['articlename'] = $article['articlename'];
					$newChapter ['volumeid'] = 0;
					if(!empty($auth['uid'])){
					    $newChapter ['posterid'] = $auth['uid'];
					    $newChapter ['poster'] = $auth['username'];
					}else{
						$newChapter ['posterid'] = 0;
					    $newChapter ['poster'] = '';
					}
					$newChapter ['postdate'] = $jieqi_collect_time;
					$newChapter ['lastupdate'] = $jieqi_collect_time;
					$newChapter ['chaptername'] = $chapter['chaptername'];
					$newChapter ['chapterorder'] = $lastchapterorder+1;
					$newChapter ['size'] = $chaptersize;
					$newChapter ['chaptertype'] = $chapter['chaptertype'];
					$newChapter ['saleprice'] = 0;
					$newChapter ['attachment'] = '';
					$newChapter ['isvip'] = 0;
					$newChapter ['display'] = 0;//print_r($newChapter);exit;
					if (!$newid = $this->db->add ( $newChapter )){
						jieqi_printfail($jieqiLang['article']['add_chapter_failure']);
					}else{
						$txtdir=jieqi_uploadpath($jieqiConfigs['article']['txtdir'], 'article');
						if (!file_exists($txtdir)) jieqi_createdir($txtdir);
						$txtdir = $txtdir.jieqi_getsubdir($article['articleid']);
						if (!file_exists($txtdir)) jieqi_createdir($txtdir);
						$txtdir = $txtdir.'/'.$article['articleid'];
						if (!file_exists($txtdir)) jieqi_createdir($txtdir);
						//echo $txtdir.'/'.$newid.$jieqi_file_postfix['txt'];exit;
						if($chapter['chaptertype']==1){
							jieqi_writefile($txtdir.'/'.$newid.$jieqi_file_postfix['txt'], $chaptercontent);
							$lastvolume=$chapter['chaptername'];
							$lastvolumeid=$newid;
						}else{
							jieqi_writefile($txtdir.'/'.$newid.$jieqi_file_postfix['txt'], $chaptercontent);
							$lastchapter=$chapter['chaptername'];
							$lastchapterid=$newid;
							$size+=$chaptersize;
						}
					}
					unset($newChapter);
					//更新文章信息(采集一章更新一下)
					$this->db->init( 'article', 'articleid', 'article' );
					$article['lastchapter'] = $lastchapter;
					$article['lastchapterid'] = $lastchapterid;
					$article['lastvolume'] = $lastvolume;
					$article['lastvolumeid'] = $lastvolumeid;
					$article['chapters'] = $lastchapterorder+1;
					$article['size'] = $size;
					$article['lastupdate'] = $jieqi_collect_time;
					$this->db->edit($article['articleid'], $article);
					$lastchapterorder++;
					//echo $i.'.'.jieqi_htmlstr($chapter['chaptername']).' ';
					echo str_pad($i.'.'.jieqi_htmlstr($chapter['chaptername']).' ',1024*64);
					ob_flush();
					flush();
				}
				echo '<br />'.$jieqiLang['article']['chapter_collect_success'];
				ob_flush();
				flush();
				jieqi_delfile($params['bookfile']);
				$articlelib = $this->load('article', 'article');
				$articlelib->article_repack($article['articleid'], array('makeopf'=>1, 'makehtml'=>0, 'makezip'=>0, 'makefull'=>0, 'maketxtfull'=>0, 'makeumd'=>0, 'makejar'=>0), 1);
				jieqi_jumppage($this->geturl('article','chapter',"SYS=aid={$article['articleid']}&method=cmView"), LANG_DO_SUCCESS, $jieqiLang['article']['update_collect_success']);
			}
		}
		
		$ups = $this->load('upload', 'system');
		$ups->__set('subName','');
		$ups->__set('savePath','/files/');
		$ups->__set('maxSize','62914560');
		$res = $ups->upload();
		if ($res) {
			$bookfile = JIEQI_ROOT_PATH.$res['attachfile']['savepath'].$res['attachfile']['savename'];
			$postbook->init($bookfile,$params['filecharset']);
			if(strlen($params['volumestart'])>0) $postbook->volumestart = $params['volumestart'];
			if(strlen($params['chapterstart'])>0) $postbook->chapterstart = $params['chapterstart'];
			$article = $postbook->getArticles();
			//if($auth['uid']==2299) $this->dump($article);
			 $this->addConfig('article','sort');
			 $jieqiSort['article'] = $this->getConfig('article','sort');
			//检查文章是否已存在
			$collect = $this->load('collect', 'article');
			if($articleobj = $collect->getArticleByName($article)){
				jieqi_msgwin($jieqiLang['article']['article_already_exists'],sprintf($jieqiLang['article']['collect_exists_note'], jieqi_htmlstr($articleobj['articlename']), $this->getadminurl('collect','action=postbook&bookfile='.$bookfile.'&formhash='.form_hash()), $this->geturl('article', 'articleinfo', 'aid='.$articleobj['articleid'])));
			}
			
			 include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
			 $article_form = new JieqiThemeForm($jieqiLang['article']['collect_add_new'], 'newarticle', $this->getAdminurl());

			$sort_select = new JieqiFormSelect($jieqiLang['article']['table_article_sortid'], 'sortid', $sortid);
			foreach($jieqiSort['article'] as $key => $val){
				$tmpstr = '';
				if ($val['layer']>0){
					for($i=0; $i<$val['layer']; $i++) $tmpstr .= '&nbsp;&nbsp;';
					$tmpstr .= '├';
				}
				$tmpstr .= $val['caption'];
				$sort_select->addOption($key, $tmpstr);
			}
			//定制版允许二级分类采集
			if(in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM'))){
				//if(!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');
				$sort_select->setExtra('onchange="showtypes(this)"');
				
				$jsstr = '';
				$jsstr='<script language="javascript">';
				$jsstr.='function showtypes(obj){';
				$jsstr.='var typeselect=document.getElementById(\'typeselect\');';
				$jsstr.='typeselect.innerHTML=\'\';';
				foreach($jieqiSort['article'] as $key => $val){
				 if(count($val['types'])>0){
					$jsstr.='if(obj.options[obj.selectedIndex].value == '.$key.') typeselect.innerHTML=\'<select class="select" size="1" name="typeid" id="typeid">';
					foreach($val['types'] as $k=>$v){
					  $defaultselect = '';
					  if($typeid==$k) $defaultselect = 'selected';
					  $jsstr.='<option value="'.$k.'" '.$defaultselect.'>'.$v.'</option>';
					  $defaultselect = '';
					}
					$jsstr.='</select>\';
					';
				 }
				}
				$jsstr.='}</script>';
				if($sortid) $jsstr.='<script language="javascript">showtypes($(\'newarticle\').sortid);</script>';
				$typeside = '<span id="typeselect" name="typeselect"></span>';
			}
			$sort_select->setDescription($typeside.$jsstr.$jieqiLang['article']['collect_sort_guide']);
			$article_form->addElement($sort_select, true);
			$articlelib = new JieqiFormText($jieqiLang['article']['table_article_articlename'], 'articlename', 30, 50, $article['articlename']);
			//if($articleisexists) $articlelib->setDescription('站内书名已存！');
			$article_form->addElement($articlelib, true);
			
			$keywords=new JieqiFormText($jieqiLang['article']['table_article_keywords'], 'keywords', 30, 50, $article['keywords']);
			$keywords->setDescription($jieqiLang['article']['note_keywords']);
			$article_form->addElement($keywords);
			if($this->checkpower($this->getDbPower('article','transarticle'), $this->getUsersStatus(), $this->getUsersGroup(), true)){
				$authorname=new JieqiFormText($jieqiLang['article']['table_article_author'], 'author', 30, 30, $article['author']);
				$article_form->addElement($authorname);
				$tmpvar='0';
				$authorflag=new JieqiFormRadio($jieqiLang['article']['article_author_flag'], 'authorflag', $tmpvar);
				$authorflag->addOption('1', $jieqiLang['article']['auth_to_author']);
				$authorflag->addOption('0', $jieqiLang['article']['not_auth_author']);
				$article_form->addElement($authorflag);
			}
			$auth = $this->getAuth();
			$tmpvar='';
			if(!empty($auth ['uid'])) $tmpvar=jieqi_htmlstr($auth ['username'],ENT_QUOTES);;
			$agent=new JieqiFormText($jieqiLang['article']['table_article_agent'], 'agent', 30, 30, $tmpvar);
			$agent->setDescription($jieqiLang['article']['note_agent']);
			$article_form->addElement($agent);
			$this->addConfig('article','option');
			$jieqiConfigs['option'] = $this->getConfig('article','option');
			$permission=new JieqiFormRadio($jieqiLang['article']['table_article_permission'], 'permission', $jieqiConfigs['option']['permission']['default']);
			foreach($jieqiConfigs['option']['permission']['items'] as $k=>$v){
				$permission->addOption($k, $jieqiConfigs['option']['permission']['items'][$k]);
			}
			$article_form->addElement($permission);
			
			//可选项配置
			$this->addConfig('article','option', 'jieqiOption');
			$jieqiOption['article'] = $this->getConfig('article','option');
			$items = $jieqiOption['article']['firstflag']['items'];
			$firstflag = new JieqiFormSelect($jieqiLang['article']['table_article_firstflag'], 'firstflag');
			foreach($items as $k=>$v){
				$firstflag->addOption($k, $v);
			}
			$article_form->addElement($firstflag);
			
			
			$fullflag=new JieqiFormRadio($jieqiLang['article']['table_article_fullflag'], 'fullflag', 0);
			$fullflag->addOption('0', $jieqiLang['article']['article_not_full']);
			$fullflag->addOption('1', $jieqiLang['article']['article_is_full']);
			$article_form->addElement($fullflag);
			
			
			$article_form->addElement(new JieqiFormText($jieqiLang['article']['article_image_url'], 'articleimage', 60, 250, $article['articleimage']));
			$article_form->addElement(new JieqiFormTextArea($jieqiLang['article']['table_article_intro'], 'intro', $article['intro'], 10, 80));
			$article_form->addElement(new JieqiFormHidden('action', 'postbook'));
			$article_form->addElement(new JieqiFormHidden('bookfile', $bookfile));

			$article_form->addElement(new JieqiFormHidden('filecharset', $params['filecharset']));
			$article_form->addElement(new JieqiFormHidden('formhash', form_hash()));
			$article_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['collect_next_button'], 'submit'));

			$data['jieqi_contents'] = '<br />'.$article_form->render(JIEQI_FORM_MIDDLE).'<br />';
			return $data;
		} else {
			jieqi_printfail($ups->getError());
		}
	}
	
	function collectView($params = array()){
	    global $jieqiConfigs, $jieqiLang, $jieqiCollectsite;//配置文件
		$errtext='';
		if(empty($params['siteid'])) $errtext .= $jieqiLang['article']['need_source_site'].'<br />';
		if(empty($params['fromid'])) $errtext .= $jieqiLang['article']['need_source_articleid'].'<br />';
		if(empty($errtext)) {
			if(!empty($params['toid'])){
				header('Location: '.$this->getadminurl('collect','action=updatecollect&siteid='.urlencode($params['siteid']).'&fromid='.urlencode($params['fromid']).'&toid='.urlencode($params['toid']).'&formhash='.form_hash()));
				exit;
			}
		    if(array_key_exists($params['siteid'], $jieqiCollectsite) && $jieqiCollectsite[$params['siteid']]['enable']=='1'){
			
			     $collect = $this->load('collect', 'article');
				 $collect->init($params['siteid']);
				 $article = $collect->getBook($params['fromid']);
				 //检查文章是否已存在
				 $temparticle = $article;
				 if(!$collect->jieqiCollect['cleansiterepeatarticle']){
					  unset($temparticle['author']);
				 }
				 $articleobj=$collect->getArticleByName($temparticle);
				 if($articleobj){
					 jieqi_msgwin($jieqiLang['article']['article_already_exists'],sprintf($jieqiLang['article']['collect_exists_note'], jieqi_htmlstr($article['articlename']), $this->getadminurl('collect','action=updatecollect&siteid='.urlencode($params['siteid']).'&fromid='.urlencode($params['fromid']).'&toid='.urlencode($articleobj['articleid']).'&formhash='.form_hash()), $this->geturl('article', 'articleinfo', 'aid='.$articleobj['articleid'])));
				 }
				 
				 $this->addConfig('article','sort');
				 $jieqiSort['article'] = $this->getConfig('article','sort');
				 
				 include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
				 $article_form = new JieqiThemeForm($jieqiLang['article']['collect_add_new'], 'newarticle', $this->getAdminurl());

                $sort = $article['sort'];
				$type = $article['type'];
				$sortid = $collect->getSortidBySort($sort);
				$typeid = $collect->getTypeidByType($sort, $type);
				/*if(in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM'))){
				    //if(!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');
					if(!empty($type) && isset($jieqiCollect['typeid'][$sort][$type]) && is_numeric($jieqiCollect['typeid'][$sort][$type])){
						$typeid=$jieqiCollect['typeid'][$sort][$type];
					}elseif(isset($jieqiCollect['typeid'][$sort]['default'])){
						$typeid=$jieqiCollect['typeid'][$sort]['default'];
					}elseif(!empty($jieqiCollect['typeid'][$sort][$type]) && substr_count($jieqiCollect['typeid'][$sort][$type], '|')){
						$tmparr = explode('|', $jieqiCollect['typeid'][$sort][$type]);
						$sortid = $tmparr[0];
						$typeid = $tmparr[1];
					}else{
						$typeid=0;
					}
				}*/
				
				$sort_select = new JieqiFormSelect($jieqiLang['article']['table_article_sortid'], 'sortid', $sortid);
				foreach($jieqiSort['article'] as $key => $val){
					$tmpstr = '';
					if ($val['layer']>0){
						for($i=0; $i<$val['layer']; $i++) $tmpstr .= '&nbsp;&nbsp;';
						$tmpstr .= '├';
					}
					$tmpstr .= $val['caption'];
					$sort_select->addOption($key, $tmpstr);
				}
				//定制版允许二级分类采集
				if(in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM'))){
					//if(!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');
					$sort_select->setExtra('onchange="showtypes(this)"');
					
					$jsstr = '';
					$jsstr='<script language="javascript">';
					$jsstr.='function showtypes(obj){';
					$jsstr.='var typeselect=document.getElementById(\'typeselect\');';
					$jsstr.='typeselect.innerHTML=\'\';';
					foreach($jieqiSort['article'] as $key => $val){
					 if(count($val['types'])>0){
						$jsstr.='if(obj.options[obj.selectedIndex].value == '.$key.') typeselect.innerHTML=\'<select class="select" size="1" name="typeid" id="typeid">';
						foreach($val['types'] as $k=>$v){
						  $defaultselect = '';
						  if($typeid==$k) $defaultselect = 'selected';
						  $jsstr.='<option value="'.$k.'" '.$defaultselect.'>'.$v.'</option>';
						  $defaultselect = '';
						}
						$jsstr.='</select>\';
						';
					 }
					}
					$jsstr.='}</script>';
					if($sortid) $jsstr.='<script language="javascript">showtypes($(\'newarticle\').sortid);</script>';
					$typeside = '<span id="typeselect" name="typeselect"></span>';
				}
				$sort_select->setDescription($typeside.$jsstr.$jieqiLang['article']['collect_sort_guide'].jieqi_htmlstr($sort).'-'.jieqi_htmlstr($type));
				$article_form->addElement($sort_select, true);
				$article_form->addElement(new JieqiFormText($jieqiLang['article']['table_article_articlename'], 'articlename', 30, 50, $article['articlename']), true);
				$keywords=new JieqiFormText($jieqiLang['article']['table_article_keywords'], 'keywords', 30, 50, $article['keywords']);
				$keywords->setDescription($jieqiLang['article']['note_keywords']);
				$article_form->addElement($keywords);
				if($this->checkpower($this->getDbPower('article','transarticle'), $this->getUsersStatus(), $this->getUsersGroup(), true)){
					$authorname=new JieqiFormText($jieqiLang['article']['table_article_author'], 'author', 30, 30, $article['author']);
					$article_form->addElement($authorname);
					$tmpvar='0';
					$authorflag=new JieqiFormRadio($jieqiLang['article']['article_author_flag'], 'authorflag', $tmpvar);
					$authorflag->addOption('1', $jieqiLang['article']['auth_to_author']);
					$authorflag->addOption('0', $jieqiLang['article']['not_auth_author']);
					$article_form->addElement($authorflag);
				}
				$auth = $this->getAuth();
				$tmpvar='';
				if(!empty($auth ['uid'])) $tmpvar=jieqi_htmlstr($auth ['username'],ENT_QUOTES);;
				$agent=new JieqiFormText($jieqiLang['article']['table_article_agent'], 'agent', 30, 30, $tmpvar);
				$agent->setDescription($jieqiLang['article']['note_agent']);
				$article_form->addElement($agent);
                $this->addConfig('article','option');
				$jieqiConfigs['option'] = $this->getConfig('article','option');
				$permission=new JieqiFormRadio($jieqiLang['article']['table_article_permission'], 'permission', $jieqiConfigs['option']['permission']['default']);
				foreach($jieqiConfigs['option']['permission']['items'] as $k=>$v){
				    $permission->addOption($k, $jieqiConfigs['option']['permission']['items'][$k]);
				//$permission->addOption('3', $jieqiLang['article']['article_permission_special']);
				//$permission->addOption('2', $jieqiLang['article']['article_permission_insite']);
				//$permission->addOption('1', $jieqiLang['article']['article_permission_yes']);
				//$permission->addOption('0', $jieqiLang['article']['article_permission_no']);
				}
				$article_form->addElement($permission);
				
				//可选项配置
				$this->addConfig('article','option', 'jieqiOption');
				$jieqiOption['article'] = $this->getConfig('article','option');
				$items = $jieqiOption['article']['firstflag']['items'];
				$firstflag = new JieqiFormSelect($jieqiLang['article']['table_article_firstflag'], 'firstflag', $collect->jieqiCollect['firstflag']);
				foreach($items as $k=>$v){
					$firstflag->addOption($k, $v);
				}
				$article_form->addElement($firstflag);
				
				
				$fullflag=new JieqiFormRadio($jieqiLang['article']['table_article_fullflag'], 'fullflag', $article['fullflag']);
				$fullflag->addOption('0', $jieqiLang['article']['article_not_full']);
				$fullflag->addOption('1', $jieqiLang['article']['article_is_full']);
				$article_form->addElement($fullflag);
				
				$display=new JieqiFormRadio('文章显示状态', 'display', $article['display']);
				$display->addOption('0', '显示');
				$display->addOption('1', '待审');
				$article_form->addElement($display);			
				
				$article_form->addElement(new JieqiFormText($jieqiLang['article']['article_image_url'], 'articleimage', 60, 250, $article['articleimage']));
				$article_form->addElement(new JieqiFormTextArea($jieqiLang['article']['table_article_intro'], 'intro', $article['intro'], 10, 80));
				$article_form->addElement(new JieqiFormHidden('action', 'newarticle'));
				$article_form->addElement(new JieqiFormHidden('siteid', $params['siteid']));
				$article_form->addElement(new JieqiFormHidden('fromid', $params['fromid']));
				$article_form->addElement(new JieqiFormHidden('oldaid', $params['fromid']));
				$article_form->addElement(new JieqiFormHidden('collecturl', $collect->pageary['infourl']));
				$article_form->addElement(new JieqiFormHidden('formhash', form_hash()));
				$article_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['collect_next_button'], 'submit'));

				$data['jieqi_contents'] = '<br />'.$article_form->render(JIEQI_FORM_MIDDLE).'<br />';
				return $data;
			}else{
				jieqi_printfail($jieqiLang['article']['not_support_collectsite']);
			}
		}else{
			jieqi_printfail($errtext);
		}
	}
	
	//数据入库
	function newarticle($params = array()){
	    global $jieqiConfigs, $jieqiLang, $jieqiCollectsite;//配置文件
		$params['articlename'] = trim($params['articlename']);
		$params['author'] = trim($params['author']);
		$params['agent'] = trim($params['agent']);
		$errtext='';
		//检查标题
		if (strlen($params['articlename'])==0) $errtext .= $jieqiLang['article']['need_article_title'].'<br />';
		elseif (!jieqi_safestring($params['articlename'])) $errtext .= $jieqiLang['article']['limit_article_title'].'<br />';
		
		if(empty($errtext)) {
			$collect = $this->load('collect', 'article');
			//检查文章是否已存在
			$temparticle = $params;
			$temparticle['oldaid'] = $params['fromid'];
			if(!$collect->jieqiCollect['cleansiterepeatarticle']){
				unset($temparticle['author']);
			}
			
			$article=$collect->getArticleByName($temparticle);
			if($article){
			    jieqi_msgwin($jieqiLang['article']['article_already_exists'],sprintf($jieqiLang['article']['collect_exists_note'], jieqi_htmlstr($params['articlename']), $this->getadminurl('collect','action=updatecollect&siteid='.urlencode($params['siteid']).'&fromid='.urlencode($params['fromid']).'&toid='.urlencode($article['articleid']).'&formhash='.form_hash()), $this->geturl('article', 'articleinfo', 'aid='.$article['articleid'])));
			}
			$collect->init($params['siteid']);
			if (!$article = $collect->newArticle($params)) jieqi_printfail($jieqiLang['article']['collect_newarticle_failure']);
			else {
				$url = $this->getadminurl('collect','action=updatecollect&siteid='.urlencode($params['siteid']).'&fromid='.urlencode($params['fromid']).'&toid='.urlencode($article['articleid']).'&formhash='.form_hash());
				$data['jieqi_contents'] = jieqi_msgbox(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['collect_newarticle_success'], $url));
				return $data;
			}
		}else{
		    jieqi_printfail($errtext);
		}
	}
	
	//采集一篇文章
	function updatecollect($params = array()){
	    global $jieqiConfigs, $jieqiLang, $jieqiCollectsite;//配置文件
		@set_time_limit(0);
		@session_write_close();
		ini_set('memory_limit', '800M');
		$collect = $this->load('collect', 'article');
		$collect->init($params['siteid']);
        $article = $collect->getBook($params['fromid']);
		$retflag = $collect->colectArticle($params['fromid'],$params['toid']);
		//0 初始状态 1 采集完成 2 不需要采集 3 采集失败 4 需要采集但是对应不上
		if($retflag==1){
		    jieqi_jumppage($this->geturl('article','chapter',"SYS=aid={$params['toid']}&method=cmView"), LANG_DO_SUCCESS, $jieqiLang['article']['update_collect_success']);
		}elseif($retflag==2){
		    $data = array();
		    $data['jieqi_contents'] = jieqi_msgbox(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['collect_no_update'], $this->geturl('article', 'articleinfo', 'aid='.$params['toid']), $this->geturl('article','article',"SYS=aid={$params['toid']}&method=articleClean&jumpurl=".urlencode( $this->getadminurl('collect','action=updatecollect&siteid='.$params['siteid']."&fromid=".$params['fromid']."&toid=".$params['toid'].'&formhash='.form_hash()))), $this->getadminurl('collect')));
			return $data;
		}elseif($retflag==4){
		    $data = array();
			$errchapter='';
			foreach ($collect->retchapinfo as $v){
				$errchapter.=$v['fchapter'].' => '.$v['tchapter'].'<br />';
			}
			//取得上一次采集站的出处start
			$jieqiCollect = false;
			$setting=unserialize($collect->article['setting']);
			if(!is_array($setting)) $setting=array();
			if(isset($setting['fromsite'])){
				$fromsite = $collect->jieqiCollectsite[$setting['fromsite']]['name'];
				if ( $setting['fromsite'] != $params['siteid'] ){
					$jieqiCollect = $collect->loadConfig($setting['fromsite']);
				}
			}
			if($jieqiCollect){//如果上一次采集源站存在
				$collectsiteurl = str_replace( "<{articleid}>", $setting['fromarticle'], $jieqiCollect['urlindex'] );
				if ( !empty( $jieqiCollect['subarticleid'] ) ){
					$subarticleid = 0;
					$articleid = $setting['fromarticle'];
					$tmpstr = "\$subarticleid = ".$jieqiCollect['subarticleid'].";";
					eval( $tmpstr );
					$collectsiteurl = str_replace( "<{subarticleid}>", $subarticleid, $collectsiteurl );
				}
				$old_url =$this->getadminurl('collect','action=updatecollect&siteid='.$setting['fromsite']."&fromid=".$setting['fromarticle']."&toid=".$params['toid']."&formhash=".form_hash());
				$fromsite = "<a href=".$collectsiteurl." target=_blank>{$fromsite}</a> <a href=".$old_url." target=_blank>采".($collect->article['fullflag'] ? '(已完本)' : '(连载中)')."</a>" ;
				$articleinindexurl = jieqi_htmlstr( $collect->article['articlename'] );
				$articleinindexurl = "<a href=".$url." target=_blank>{$articleinindexurl}</a>";
				$data['jieqi_contents'] = jieqi_msgbox(LANG_DO_SUCCESS, sprintf( $jieqiLang['article']['collect_cant_update'], $articleinindexurl, date( "Y/m/d H:i:s", $this->article['lastupdate']), $fromsite, $errchapter, $this->geturl('article','article',"SYS=aid={$params['toid']}&method=cmView"), $this->getadminurl('collect') ,$this->geturl('article','article',"SYS=aid={$params['toid']}&method=articleClean&jumpurl=".urlencode( $this->getadminurl('collect','action=updatecollect&siteid='.$params['siteid']."&fromid=".$params['fromid']."&toid=".$params['toid'].'&formhash='.form_hash())))) );
			}else{
				$data['jieqi_contents'] = jieqi_msgbox(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['collect_cant_update_noformsite'], $errchapter, $this->geturl('article','article',"SYS=aid={$params['toid']}&method=cmView"), $this->geturl('article','article',"SYS=aid={$params['toid']}&method=articleClean&jumpurl=".urlencode( $this->getadminurl('collect','action=updatecollect&siteid='.$params['siteid']."&fromid=".$params['fromid']."&toid=".$params['toid'].'&formhash='.form_hash()))) , $this->getadminurl('collect')) );
			}
		}
		
	}
}

?>
