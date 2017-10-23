<?php 
/** 
 * 小说连载->文章管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class articleModel extends Model{
	public function main($params = array()){
	     global $jieqiModules;
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
		 
	     //$_REQUEST = $this->getRequest();//格式化参数
		 
         $this->db->init('article','articleid','article');
		 
		 $this->action($params);//当页面有动作的时候，调用执行
		 
		 $this->db->setCriteria(new Criteria('a.siteid', JIEQI_SITE_ID, '='));
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
		 $this->db->criteria->setSort('a.articleid');
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
			 forea