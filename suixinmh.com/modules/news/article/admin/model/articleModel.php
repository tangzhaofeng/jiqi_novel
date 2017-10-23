<?php
/**
 * 小说连载->文章管理 * @copyright   Copyright(c) 2014
 * @author      gaoli* @version     1.0
 */

class articleModel extends Model{
	public function main($params = array()){
	     global $jieqiModules;
	     if(!isset($params['siteid'])){
	     	$params['siteid'] = -1;//默认-1不指定频道
	     }
	     $this->addConfig('article','configs');//echo md5(JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME.JIEQI_SITE_KEY);
		 $this->addConfig('article','sort');
		 $this->addLang('article','manage');
		 $this->addLang('article','list');
		 $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
		 //print_r($this->getLang('article','article_not_exists'));exit;
		 //jieqi_loadlang('manage', JIEQI_MODULE_NAME);
         //jieqi_loadlang('list', JIEQI_MODULE_NAME);
	     $jieqiConfigs['article'] = $this->getConfig('article','configs');
		 $jieqiSort['article'] = $this->getConfig('article','sort');
         $articletitle=$jieqiLang['article']['all_article'];//定义标题

		 /*$this->db->init('chapter','chapterid','article');
		 $this->db->setCriteria(new Criteria('isvip', 0, '>'));
		 $this->db->criteria->add(new Criteria('saleprice', 0, '='));
		  $this->db->criteria->add(new Criteria('chaptertype', 0, '='));
		 $this->db->criteria->add(new Criteria('postdate', $this->getTime('month'), '>'));
		 $this->db->criteria->setSort('chapterid');
		 $this->db->criteria->setOrder('ASC');
		 $this->db->queryObjects($this->db->criteria);
		 while($v = $this->db->getObject()){
		      $saleprice = round($v->getVar('size') / 666);//echo $v->getVar('chaptername');exit;
		      $this->db->updatetable('article_chapter', array('saleprice'=>$saleprice), "chapterid='".$v->getVar('chapterid')."'");
		 }*/
	     //$_REQUEST = $this->getRequest();//格式化参数

         $this->db->init('article','articleid','article');

		 $this->action($params);//当页面有动作的时候，调用执行
		 $this->db->setCriteria();
// 		 $this->db->setCriteria(new Criteria('a.siteid', JIEQI_SITE_ID, '='));
		 $this->db->criteria->setTables($this->dbprefix('article_article')." AS a LEFT JOIN ".$this->dbprefix('article_statamout')." AS s ON a.articleid=s.articleid");
		 $this->db->criteria->setFields("a.*,s.visit,s.vote,s.goodnum,s.vipvote,s.reward");
		 //提交数据
		 if($this->submitcheck()){
			 if(!empty($params['keyword'])){
				if($params['keytype']==1) $this->db->criteria->add(new Criteria('author', $params['keyword'], '='));
				elseif($params['keytype']==2) $this->db->criteria->add(new Criteria('poster', $params['keyword'], '='));
				else $this->db->criteria->add(new Criteria('articlename', '%'.$params['keyword'].'%', 'LIKE'));
			 }
			 if(!empty($params['isvip'])){
				$this->db->criteria->add(new Criteria('articletype', 0, '>'));
			 }
			 if($params['nowagent']){
				$this->db->criteria->add(new Criteria('agentid', $params['nowagent']));
			 }
			 if(is_numeric($params['firstflag'])){
			 	$this->db->criteria->add(new Criteria('firstflag', $params['firstflag']));
			 }
			 if($params['siteid'] != -1){
			 	$this->db->criteria->add(new Criteria('siteid', $params['siteid']));
			 }
		 }
		 if(!empty($params['display'])){
			 switch ($params['display']){
				case 'unshow':
					$this->db->criteria->add(new Criteria('display', 0, '>'));
					$articletitle=$jieqiLang['article']['no_audit_article'];
					break;
				case 'hide':
					$this->db->criteria->add(new Criteria('display', 0, '>'));
					$articletitle=$jieqiLang['article']['no_audit_article'];
					break;
				case 'show':
					$this->db->criteria->add(new Criteria('display', 0, '='));
					$articletitle=$jieqiLang['article']['audit_article'];
					break;
				case 'sign':
					$this->db->criteria->add(new Criteria('permission', 4, '>='));
					$articletitle=$jieqiLang['article']['top_signnew'];
					break;
				case 'empty':
					$this->db->criteria->add(new Criteria('size', 0, '='));
					$articletitle=$jieqiLang['article']['empty_article'];
					break;
				case 'cool':
					$this->db->criteria->add(new Criteria('postdate', time()-3600*24*30, '<'));
					$articletitle=$jieqiLang['article']['cool_article'];
					break;
				default:
					$params['display']='';
					break;
			 }
		 }

		 if(!$params['page']) $params['page'] = 1;
		 $this->db->criteria->setSort('a.lastupdate');
		 $this->db->criteria->setOrder('DESC');
		 $this->db->criteria->setLimit($jieqiConfigs['article']['pagenum']);
		 $this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['article']['pagenum']);
		 $this->db->queryObjects($this->db->criteria);
		 $k=0;
		 $package = $this->load('article','article');//加载文章处理类
		 while($v = $this->db->getObject()){
		      $articlerows[$k] = $package->article_vars($v);
			  $articlerows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('articleid').'">';  //选择框
			  $k++;
		 }
		 include_once(HLM_ROOT_PATH.'/lib/html/page.php');
		 $jumppage = new JieqiPage($this->db->getCount($this->db->criteria),$jieqiConfigs['article']['pagenum'],$params['page']);
		 $jumppage->setlink('', true, true);

		 //获取编辑组
		 $agents = array();
		 if($jieqiConfigs['article']['agentgroup']){
			 global $jieqiGroups;
			 $group_array = explode('|',$jieqiConfigs['article']['agentgroup']);
			 $groups = array();
			 foreach($group_array as $key=>$group){
				  $groups[] = array_search($group, $jieqiGroups);
			 }
			 if(is_array($groups)){
			      $this->db->init('users','uid','system');
				  $this->db->setCriteria();
				  $this->db->criteria->setFields("uid,uname,name,groupid");
				  foreach($groups as $k=>$groupid){
				      $this->db->criteria->add(new Criteria('groupid', $groupid), 'OR');
				  }
				  $this->db->criteria->setSort('uid');
				  $this->db->criteria->setOrder('ASC');
				  $agents = $this->db->lists();//print_r($agents);
			 }
		 }
		 $source = $package->getSources();
		return array (
				'agents' => $agents,
				'channel' => $source ['channel'],
				'soruce' => $source ['firstflag']['items'],
				'firstflag' => $params ['firstflag'],
				'groups' => $jieqiGroups,
				'articletitle' => $articletitle,
				'articlerows' => $articlerows,
				'article_static_url' => (empty ( $jieqiConfigs ['article'] ['staticurl'] )) ? $jieqiModules ['article'] ['url'] : $jieqiConfigs ['article'] ['staticurl'],
				'article_dynamic_url' => (empty ( $jieqiConfigs ['article'] ['dynamicurl'] )) ? $jieqiModules ['article'] ['url'] : $jieqiConfigs ['article'] ['dynamicurl'],
				'url_jumppage' => $jumppage->whole_bar (),
				'siteid' => $params ['siteid'],
				'nowagent' => $params ['nowagent'] 
		);
	}

	public function action($params){
		 if($params['action']=='downexcel'){
		      $this->db->setCriteria(new Criteria('a.siteid', JIEQI_SITE_ID, '='));
			  $this->db->criteria->setTables($this->dbprefix('article_article')." AS a LEFT JOIN ".$this->dbprefix('article_statamout')." AS s ON a.articleid=s.articleid");
			  $this->db->criteria->setFields("a.*,s.visit,s.vote,s.goodnum,s.vipvote,s.reward,s.sale,(select count(*) from ".$this->dbprefix('article_chapter')." where articleid=a.articleid and chaptertype=0 and isvip=0) as freechapters");
			  $this->db->criteria->add(new Criteria('permission', 4, '>='));
			  $this->db->queryObjects($this->db->criteria);
			  $this->db->criteria->setLimit(20);
		      $this->db->criteria->setStart(0);
			  $package = $this->load('article','article');//加载文章处理类
			  $createexcel = $this->load('createexcel','system');
			  $titlearr=array(
				  'articleid'=>'作品编号',
				  'sort'=>'分类',
				  'articlename'=>'书籍名称',
				  'author'=>'作者笔名',
				  'articletype'=>'是否VIP',
				  'size'=>'字数',
				  'fullflag'=>'写作进程',
				  'lastupdate'=>'最后更新日期',
				  //'intro'=>'简介',
				  'visit'=>'点击',
				  'chapters'=>'总章节数',
				  'freechapters'=>'免费章节数',
				  'url_articleinfo'=>'作品网址',
				  'sale'=>'VIP销售额/书海币'
			  );
			  $data = array();
			  $k=0;
			  while($v = $this->db->getObject()){
				   $articlerows[$k] = $package->article_vars($v);
				   $data[$k] = array(
						  'articleid'=>$articlerows[$k]['articleid'],
						  'sort'=>$articlerows[$k]['sort'],
						  'articlename'=>$articlerows[$k]['articlename'],
						  'author'=>$articlerows[$k]['author'],
						  'articletype'=>$articlerows[$k]['articletype'] ? '是' : '否',
						  'size'=>$articlerows[$k]['size_c'],
						  'fullflag'=>$articlerows[$k]['fullflag_tag'],
						  'lastupdate'=>date('Y-m-d H:i:s', $articlerows[$k]['lastupdate']),
						  //'intro'=>$articlerows[$k]['intro'],
						  'visit'=>$articlerows[$k]['visit'],
						  'chapters'=>$articlerows[$k]['chapters'],
						  'freechapters'=>$articlerows[$k]['freechapters'],
						  'url_articleinfo'=>$articlerows[$k]['url_articleinfo'],
						  'sale'=>$articlerows[$k]['sale']
			       );
				   $k++;
			  }
			  $createexcel->getExcel($titlearr,$data,'书海签约文章');
			  exit;
		 }elseif(isset($params['action']) && !empty($params['id'])){
		      $package = $this->load('article','article');//加载文章处理类
		      switch($params['action']){
			       case 'show'://显示
				       $query = $this->db->edit($params['id'],array('display'=>0));
					   $package->article_repack($params['id'], array('makeopf'=>1), 1);
				   break;
			       case 'hide'://待审
				       $query = $this->db->edit($params['id'],array('display'=>1));
					   $package->article_repack($params['id'], array('makeopf'=>1), 1);
				   break;
			       case 'isvip'://设置VIP
				       $query = $this->db->edit($params['id'],array('articletype'=>1, 'vipdate'=>JIEQI_NOW_TIME));
					   $package->article_repack($params['id'], array('makeopf'=>1), 1);
				   break;
				   case 'novip'://取消VIP
				       $query = $this->db->edit($params['id'],array('articletype'=>0, 'vipdate'=>''));
					   $package->article_repack($params['id'], array('makeopf'=>1), 1);
				   break;
				   case 'permission'://设置A签
				       $query = $this->db->edit($params['id'],array('permission'=>4, 'signdate'=>JIEQI_NOW_TIME));
					   $package->article_repack($params['id'], array('makeopf'=>1), 1);
				   break;
			       case 'fullflag'://设置完本
				       $query = $this->db->edit($params['id'],array('fullflag'=>1));
					   $package->article_repack($params['id'], array('makeopf'=>1), 1);
				   break;
				   case 'nofullflag'://取消完本
				       $query = $this->db->edit($params['id'],array('fullflag'=>0));
					   $package->article_repack($params['id'], array('makeopf'=>1), 1);
				   break;
				   case 'setagent'://
				       if($params['uid']=='-1'){
					       $query = $this->db->edit($params['id'],array('agentid'=>0, 'agent'=>''));
					   }else{
						   $this->db->init('users','uid','system');
						   $this->db->setCriteria(new Criteria('uid', $params['uid'], '='));
						   if($users=$this->db->get($this->db->criteria)){
							  $this->db->init('article','articleid','article');
							  $query = $this->db->edit($params['id'],array('agentid'=>$params['uid'], 'agent'=>( $users->getVar('name','n') ? $users->getVar('name','n') : $users->getVar('uname','n')) ));
						   }
					   }
					   $package->article_repack($params['id'], array('makeopf'=>1), 1);
				   break;
				   case 'download':
				        parent::checkpower($this->getDbPower('article','articlemodify'), $this->getUsersStatus(), $this->getUsersGroup(), false, true);
						$package = $this->load('article','article');
						if($package->loadOPF($params['id'])){
						    $this->db->init('article','articleid','article');
							$article=$this->db->get($params['id']);
							header("Content-type: text/plain");
							header("Accept-Ranges: bytes");
							header("Content-Disposition: attachment; filename=".$article['articlename'].".txt");
							$br="\r\n";
							$content = '';
							$volume='';
							$txtdir=$package->getDir('txtdir', true, false);
							$i=0;
							foreach($package->chapters as $k => $chapter){
								if($chapter['content-type']=='volume'){
									$volume=$chapter['id'];
								}else{
									$i++;

									$content=($i>1 ? $br.$br.$br.$br : '').$volume.' '.$chapter['id'].$br.$br;
									$content .= jieqi_readfile($txtdir.'/'.$chapter['href']);
									echo $content;
									ob_flush();
									flush();
								}
							}
							exit;
						 }
					break;
				   case 'del'://单个文章删除
						$articleLib = $this->load ( 'article', 'article' );
						$article = $articleLib->isExists ($params['id']);
						// 检查权限
						$articleLib->canedit($article);
						$articleLib->delPower($params['id']);
						$articleLib->articleDelete ( $article, true );
						$query = true;
				       //$query = $this->db->delete($params['id']);
				   break;
			  }//jieqi_jumppage();
		      if($query) jieqi_jumppage();
			  else jieqi_printfail();
		 }
	}
	function doauthor($params){
@ignore_user_abort(true);
@set_time_limit(3600);
@session_write_close();
@ini_set('memory_limit', '256M');
	     $jieqiConfigs['article']['pagenum'] = 500;
		 if(!$params['page']) $params['page'] = 1;
         $this->db->init('article','articleid','article');
		 $this->db->setCriteria();
		 //$this->db->criteria->add(new Criteria('articleid', 1162));
		 $this->db->criteria->add(new Criteria('authorid', 0,'>'));
		 $this->db->criteria->setSort('articleid');
		 $this->db->criteria->setOrder('asc');
		 $this->db->criteria->setLimit($jieqiConfigs['article']['pagenum']);
		 $this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['article']['pagenum']);
		 $query = $this->db->queryObjects($this->db->criteria);//echo $this->db->getCount($this->db->criteria);exit;
		 $totalpage  = ceil($this->db->getCount($this->db->criteria)/$jieqiConfigs['article']['pagenum']);
		 $i=0;
		 $users_handler =  $this->getUserObject();
		 while($v = $this->db->getObject($query)){
			 echo '<b>'.($i+1).'、'.$v->getVar('articlename').'</b><br>';
			 $criteria=new CriteriaCompo();
		     $criteria->add(new Criteria('uid', $v->getVar('authorid')));
			 $users_handler->updatefields(array('groupid'=>6),$criteria);
			 $i++;
		 }
		 if($params['page']>=$totalpage) exit('全文文章处理完成！');
		 //echo '第('.$params['page'].')页处理完毕！共('.$totalpage.')页!';
		 jieqi_jumppage('?controller=article&method=doauthor&page='.($params['page']+1),'文章处理中...','第('.$params['page'].')页处理完毕！共('.$totalpage.')页!');
	}
    function doarticle($params){
@ignore_user_abort(true);
@set_time_limit(3600);
@session_write_close();
@ini_set('memory_limit', '256M');
	     $jieqiConfigs['article']['pagenum'] = 100;
		 if(!$params['page']) $params['page'] = 1;
         $this->db->init('article2','articleid','article');
		 $this->db->setCriteria(new Criteria('siteid', JIEQI_SITE_ID, '='));
		 //$this->db->criteria->add(new Criteria('articleid', 1162));
		 //$this->db->criteria->add(new Criteria('articletype', 1,'>'));
		 $this->db->criteria->setSort('articleid');
		 $this->db->criteria->setOrder('asc');
		 $this->db->criteria->setLimit($jieqiConfigs['article']['pagenum']);
		 $this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['article']['pagenum']);
		 $query = $this->db->queryObjects($this->db->criteria);//echo $this->db->getCount($this->db->criteria);exit;
		 $totalpage  = ceil($this->db->getCount($this->db->criteria)/$jieqiConfigs['article']['pagenum']);
		 $i=0;
		 while($v = $this->db->getObject($query)){
			 echo '<b>'.($i+1).'、'.$v->getVar('articlename').'</b><br>';
		     $this->doOneBook($v);
			 //$this->moveChapters($v);
			 $i++;
		 }
		 if($params['page']>=$totalpage) exit('全文文章处理完成！');
		 //echo '第('.$params['page'].')页处理完毕！共('.$totalpage.')页!';
		 jieqi_jumppage('?controller=article&method=doarticle&page='.($params['page']+1),'文章处理中...','第('.$params['page'].')页处理完毕！共('.$totalpage.')页!');
		 //header('location:?controller=article&method=doarticle&page='.($params['page']+1));exit;
	}
	//合并章节
	function joinChapters($article, $obook){
	     $articleid = $article->getVar('articleid', 'n');
		 $startorder = $chapters = $article->getVar('chapters', 'n');
		 $lastvolumeid = $article->getVar('lastvolumeid', 'n');
		 $lastvolume = $article->getVar('lastvolume', 'n');
		 $lastchapterid = $article->getVar('lastchapterid', 'n');
		 $lastchapter = $article->getVar('lastchapter', 'n');
		 $lastchaptervip = $article->getVar('lastchaptervip', 'n');
		 $size = $article->getVar('size', 'n');
		 $lastupdate = $article->getVar('lastupdate', 'n');//print_r($obook);;
	     //if($ochapters = $this->db->selectsql('select r.*,a.ocontent from '.$this->dbprefix("obook_ochapter")."  AS r LEFT JOIN ".$this->dbprefix('obook_ocontent')." AS a ON r.ochapterid=a.ochapterid WHERE obookid={$obook['obookid']} and flag=0 order by chapterorder asc")){//有VIP
		 if($ochapters = $this->db->selectsql('select * from '.$this->dbprefix("article_chapter")." WHERE articleid={$articleid} and vchapterid>0 order by chapterorder asc")){//有VIP
		 //print_r($ochapters);exit;
			  foreach($ochapters as $k=>$ochapter){
			      $startorder++;
				  $this->db->updatetable('article_sale', array('articleid'=>$articleid,'chapterid'=>$ochapter['chapterid']), "chapterid='{$ochapter['vchapterid']}'");
				  /*$ochapter['chaptername'] = mb_ereg_replace('^(　| )+', '', $ochapter['chaptername']);
				  $ochapter['chaptername'] = mb_ereg_replace('(　| )+$', '', $ochapter['chaptername']);
				  $data = array(
					   'siteid'=>JIEQI_SITE_ID,
					   'articleid'=>$articleid,
					   'articlename'=>$article->getVar('articlename', 'n'),
					   'volumeid'=>0,
					   'posterid'=>$ochapter['posterid'],
					   'poster'=>$ochapter['poster'],
					   'postdate'=>$ochapter['postdate'],
					   'lastupdate'=>$ochapter['lastupdate'],
					   'chaptername'=>$ochapter['chaptername'],
					   'chapterorder'=>$startorder,
					   'size'=>$ochapter['size'],
					   'chaptertype'=>$ochapter['chaptertype'],
					   'saleprice'=>$ochapter['saleprice'],
					   'isvip'=>1,
					   'display'=>$ochapter['display'],
					   'vchapterid'=>$ochapter['ochapterid']
				  );
				  if(!$chapterid = $this->db->inserttable('article_chapter',$data,true)) echo "《".$article->getVar('articlename', 'n')."》".$ochapter['chaptername'].' 写入错误！';
				  else{
				      if(!$ochapter['display']){
						  if($ochapter['chaptertype']){
							   $lastvolumeid = $chapterid;
							   $lastvolume = $ochapter['chaptername'];
						  }else{
							   $size+=$ochapter['size'];
							   $lastupdate = $ochapter['postdate'];
							   $lastchapterid = $chapterid;
							   $lastchapter = $ochapter['chaptername'];
							   $lastchaptervip = 1;
							   $chapters++;
						  }
					  }
					  $txtdir=jieqi_uploadpath('txt', 'article').jieqi_getsubdir($articleid).'/'.$articleid;
					  $this->swritefile($txtdir."/{$chapterid}.txt", $ochapter['ocontent']);
					  $this->db->updatetable('obook_ochapter',array('flag'=>1),"ochapterid='{$ochapter['ochapterid']}'");
					  if($this->db->updatetable('article_article',array(
							  'chapters'=>$chapters,
							  'lastupdate'=>$ochapter['postdate'],
							  'lastvolumeid'=>$lastvolumeid,
							  'lastvolume'=>$lastvolume,
							  'lastchapterid'=>$lastchapterid,
							  'lastchapter'=>$lastchapter,
							  'lastchaptervip'=>$lastchaptervip,
							  'size'=>$size
					  	   ),"articleid='{$articleid}'")){
					       $this->db->updatetable('article_sale', array('chapterid'=>$chapterid), "chapterid='{$ochapter['ochapterid']}'");
						   //echo('成功更新《'.$article->getVar('articlename', 'n').'》<br />');
					  }//else echo('更新书《'.$article->getVar('articlename', 'n').'》.失败');
				  }*/
			  }
			  echo('成功更新《'.$article->getVar('articlename', 'n').'》<br />');
			  //$this->db->updatetable('article_sale', array('articleid'=>$articleid), "articleid='{$obook['obookid']}'");
			  //$package = $this->load('article','article');//加载文章处理类
			  //$package->article_repack($params['id'], array('makeopf'=>1), 1);
		 }else echo ('跳过《'.$article->getVar('articlename', 'n').'》<br />');
		//print_r($txtdir);exit;
	}
	//移动章节
	function moveChapters($article){
	     $articleid = $article->getVar('articleid');
	     //$newtxtdir = JIEQI_ROOT_PATH.'/files/article/txt'.jieqi_getsubdir($articleid).'/'.$articleid;
		 $newtxtdir = jieqi_uploadpath('txt', 'article').jieqi_getsubdir($articleid).'/'.$articleid;
		 if(!is_dir($newtxtdir)) jieqi_createdir($newtxtdir, 0777, true);
	     //处理免费章节
		 if($chapters = $this->db->selectsql('select chapterid from '.$this->dbprefix("article_chapter")." WHERE articleid={$articleid} and chaptertype=0 order by chapterorder asc")){
		      $package = $this->load('article','article');//加载文章处理类
			  $package->instantPackage($articleid);
			  //$txtdir = $package->getDir('txtdir');
			  $txtdir =JIEQI_ROOT_PATH.'/files/article/txtnew'.jieqi_getsubdir($articleid).'/'.$articleid;
		      foreach($chapters as $k=>$chapter){
			      $chapterfile = $txtdir."/{$chapter['chapterid']}.txt";
				  //echo $chapterfile.'<br />';echo $newtxtdir."/{$chapter['chapterid']}.txt";exit;
			      if(is_file($chapterfile)){
				      jieqi_copyfile($chapterfile, $newtxtdir."/{$chapter['chapterid']}.txt", 0777, false );
				  }else $this->swritefile($newtxtdir."/{$chapter['chapterid']}.txt", ' ');
			  }
		 }
	}
	//处理书的扩展信息
	function doOneBook($article){
	     echo('===开始更新《'.$article->getVar('articlename', 'n').'》===<br />');
	     $articleid = $article->getVar('articleid');

		 //电子书部分
		 $articletype=intval($article->getVar('articletype'));
		 if($articletype==1) $hasobook=1;
		 else{
			 if(($articletype & 2)>0) $hasobook=1;
			 else $hasobook=0;
		 }

		 $tmpvar=explode('-',date('Y-m-d',JIEQI_NOW_TIME));
		 $daystart=mktime(0,0,0,(int)$tmpvar[1],(int)$tmpvar[2],(int)$tmpvar[0]);

		 $tmpvar=date('w',JIEQI_NOW_TIME);
		 if($tmpvar==0) $tmpvar=7; //星期天是0，国人习惯作为作为一星期的最后一天
		 $weekstart=$daystart;
		 if($tmpvar>1) $weekstart-=($tmpvar-1) * 86400;

         //处理销售
		 $allsales = 0;
		 if($hasobook==1){//$obook = $this->db->selectsql('select * from '.$this->dbprefix("obook_obook")." WHERE articleid={$articleid}");
			if($obook = $this->db->selectsql('select * from '.$this->dbprefix("obook_obook")." WHERE articleid={$articleid}")){//有VIP
			     $this->joinChapters($article, $obook[0]);
			     /*if($allsales = $obook[0]['allsales']){
					 $this->db->inserttable('article_stat',
						 array(
							   'articleid'=>$articleid,
							   'mid'=>'sale',
							   'total'=>$obook[0]['allsales'],
							   'month'=>$obook[0]['monthsales'],
							   'week'=>$obook[0]['weeksales'],
							   'day'=>$obook[0]['daysales'],
							   'totalnum'=>$obook[0]['totalsale'],
							   'monthnum'=>$obook[0]['monthsale'],
							   'weeknum'=>$obook[0]['weeksale'],
							   'daynum'=>$obook[0]['daysale'],
							   'lasttime'=>$obook[0]['lastsale']
						  )
					  );
				  } */
			}else $hasobook=0;
		 }
		  //修改文章内容
		/* if(!$this->db->updatetable('article_article',
			 array(
				   'articleid'=>$articleid,
				   'articletype'=>$hasobook,
				   'firstflag'=>$article->getVar('sourcesite')
			  ),"articleid='{$articleid}'")) echo '失败!<br />';

		 //处理点击
		 $lastvisittime = $article->getVar('lastvisit');
		 if($allvisit = $article->getVar('allvisit')){
			 if(date('Y-m-d',  JIEQI_NOW_TIME)==date('Y-m-d',  $lastvisittime)){
				  $monthvisit = $article->getVar('monthvisit');
				  $weekvisit = $article->getVar('weekvisit');
				  $dayvisit = $article->getVar('dayvisit');
			 }else{

				  if($lastvisittime>=$weekstart){
					   $weekvisit = $article->getVar('weekvisit');
				  }else{
					   $weekvisit = 0;
				  }
				   if(date('Y-m',  JIEQI_NOW_TIME)==date('Y-m',  $lastvisittime)){
						$monthvisit = $article->getVar('monthvisit');
				   }else{
						$monthvisit = 0;
				   }
				  $dayvisit = 0;
			 }
			 $this->db->inserttable('article_stat',
				 array(
					   'articleid'=>$articleid,
					   'mid'=>'visit',
					   'total'=>$allvisit,
					   'month'=>$monthvisit,
					   'week'=>$weekvisit,
					   'day'=>$dayvisit,
					   'totalnum'=>$allvisit,
					   'monthnum'=>$monthvisit,
					   'weeknum'=>$weekvisit,
					   'daynum'=>$dayvisit,
					   'lasttime'=>($lastvisittime ? $lastvisittime : JIEQI_NOW_TIME)
				  )
			  );
		  }
		  //推荐
		 $lastvotetime = $article->getVar('lastvote');
		 if($allvote = $article->getVar('allvote')){
			 if(date('Y-m-d',  JIEQI_NOW_TIME)==date('Y-m-d',  $lastvotetime)){
				  $monthvote = $article->getVar('monthvote');
				  $weekvote = $article->getVar('weekvote');
				  $dayvote = $article->getVar('dayvote');
			 }else{
				  if($lastvotetime>=$weekstart){
					   $weekvote = $article->getVar('weekvote');
				  }else{
					   $weekvote = 0;
				  }
				   if(date('Y-m',  JIEQI_NOW_TIME)==date('Y-m',  $lastvotetime)){
						$monthvote = $article->getVar('monthvote');
				   }else{
						$monthvote = 0;
				   }
				  $dayvote = 0;
			 }
			 $this->db->inserttable('article_stat',
				 array(
					   'articleid'=>$articleid,
					   'mid'=>'vote',
					   'total'=>$allvote,
					   'month'=>$monthvote,
					   'week'=>$weekvote,
					   'day'=>$dayvote,
					   'totalnum'=>$allvote,
					   'monthnum'=>$monthvote,
					   'weeknum'=>$weekvote,
					   'daynum'=>$dayvote,
					   'lasttime'=>($lastvotetime ? $lastvotetime : JIEQI_NOW_TIME)
				  )
			  );
		  }
         //收藏
		 if($allgoodnum = $article->getVar('goodnum')){
			 $this->db->inserttable('article_stat',
				 array(
					   'articleid'=>$articleid,
					   'mid'=>'goodnum',
					   'total'=>$allgoodnum,
					   'month'=>0,
					   'week'=>0,
					   'day'=>0,
					   'totalnum'=>$allgoodnum,
					   'monthnum'=>0,
					   'weeknum'=>0,
					   'daynum'=>0,
					   'lasttime'=>JIEQI_NOW_TIME
				  )
			  );
		  }

		 //修改文章属性表
		 $this->db->inserttable('article_statamout',
			 array(
				   'articleid'=>$articleid,
				   'visit'=>$allvisit,
				   'vote'=>$allvote,
				   'goodnum'=>$allgoodnum,
				   'vipvote'=>0,
				   'sale'=>$allsales,
				   'reward'=>0
			  )
		  );
*/
		 //echo $article->getVar('articlename').'-'.$allvisit.'-'.$monthvisit.'-'.$weekvisit.'-'.$dayvisit;//exit;
	}
	//批量删除文章
	public function batchdel($params = array()){
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');//print_r($jieqiConfigs['article']);
		$this->addLang('article','manage');
		$jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
		// 检查权限
		$this->checkpower($this->jieqiPower['article']['delallarticle'], $this->getUsersStatus (), $this->getUsersGroup ());
		if(empty($params['checkid'])) $this->printfail($jieqiLang['article']['need_delete_ids']);
		$articleLib = $this->load ( 'article', 'article' );
		foreach($params['checkid'] as $deleteid){//echo $deleteid;
			$article = $articleLib->isExists ($deleteid);
//			$articleLib->canedit($article);
//			$articleLib->delPower($deleteid);
			$articleLib->articleDelete ( $article, true );
		}
		$this->jumppage($this->getAdminurl(),LANG_DO_SUCCESS,$jieqiLang['article']['batch_delete_complete']);
	}
}
?>