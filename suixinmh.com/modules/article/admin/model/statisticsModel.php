<?php 
class statisticsModel extends Model{
	
	
	public function statistics($param = array()){
		$data = array();
		$articleLib = $this->load('article','article');//加载文章处理类
		$statArr = $articleLib->getStatArray();
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		if(empty($param['duration'])){
			$data['duration'] = 'total';
		}else{
			$data['duration'] = $param['duration'];
		}
		if(empty($param['mid'])){
			$data['mid'] = 'visit';
		}else{
			$data['mid'] = $param['mid'];
		}
		if(empty($param['order'])){
			$data['order'] = 'desc';
		}else{
			$data['order'] = $param['order'];
		}
		$data['mid_name'] = $statArr[$data['mid']]['name'];
		//jieqi_article_statamout
		$this->db->init('stat','statid','article');
		$this->db->setCriteria(new Criteria('a.siteid', JIEQI_SITE_ID, '='));
		$this->db->criteria->setTables($this->dbprefix('article_article')." AS a RIGHT JOIN ".$this->dbprefix('article_stat')." AS s ON a.articleid=s.articleid");
		$this->db->criteria->setFields("s.*,a.articlename");
		$this->db->criteria->add(new Criteria('s.mid', $data['mid'], '='));
		if($data['duration'] != 'total'){
			$this->db->criteria->add(new Criteria('s.lasttime', $this->getTime($data['duration']), '>='));
		}
		$this->db->criteria->setSort('s.'.$data['duration']);
		$this->db->criteria->setOrder($data['order']);
		$data ['articles'] = $this->db->lists ($jieqiConfigs['article']['pagenum'], $param['page']);
		// 处理页面跳转
		$data ['url_jumppage'] = $this->db->getPage ($this->getUrl('article','statistics','evalpage=0','SYS=method=main'));
		return $data;
	}
	
	public function main($params = array()){
	     global $jieqiModules;
	     $this->addConfig('article','configs');
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
		 return array(
		      'agents'=>$agents,
			  'groups'=>$jieqiGroups,
		      'articletitle'=>$articletitle,
			  'articlerows'=>$articlerows,
			  'article_static_url'=>(empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'],
			  'article_dynamic_url'=>(empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'],
			  'url_jumppage'=>$jumppage->whole_bar()
		 );
	}
	
	public function action($params){
		 if(isset($params['action']) && !empty($params['id'])){
		      //$this->db->init('article','articleid','article');
		      switch($params['action']){
			       case 'show'://显示
				       $query = $this->db->edit($params['id'],array('display'=>0));
				   break;
			       case 'hide'://待审
				       $query = $this->db->edit($params['id'],array('display'=>1));
				   break;
			       case 'isvip'://设置VIP
				       $query = $this->db->edit($params['id'],array('articletype'=>1));
				   break;
				   case 'novip'://取消VIP
				       $query = $this->db->edit($params['id'],array('articletype'=>0));
				   break;
				   case 'permission'://设置A签
				       $query = $this->db->edit($params['id'],array('permission'=>4, 'signdate'=>JIEQI_NOW_TIME));
				   break;
			       case 'fullflag'://设置完本
				       $query = $this->db->edit($params['id'],array('fullflag'=>1));
				   break;
				   case 'nofullflag'://取消完本
				       $query = $this->db->edit($params['id'],array('fullflag'=>0));
				   break;
				   case 'setagent'://取消完本
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
				   break;
			  }//jieqi_jumppage();
		      if($query) jieqi_jumppage();
			  else jieqi_printfail();
		 }	
	}
	
} 
?>