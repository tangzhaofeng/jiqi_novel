<?php 
/** 
 * 小说连载->销售统计 * @copyright   Copyright(c) 2014 
 * @author      zhuyunlong* @version     1.0 
 */ 

class salestatModel extends Model{

	public function main($params){
		global $jieqiModules;
		$package = $this->load('article','article');//加载文章处理类
		if(!$params['page']) $params['page']=1;
		$daystart = $this->getTime();
		$weekstart = $this->getTime('week');
		$monthstart = $this->getTime('month');
		$params['keyword'] = urldecode($params['keyword']);
		$statstr = $package->getStatStr();
		$order = preg_replace("/$statstr/",'',$params['type']);
		$visitfield = $order;
		if(array_key_exists(JIEQI_MODULE_NAME,$jieqiModules)){
			$siteid = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];
		}else{
			$siteid = $jieqiModules['system']['siteid'];
		}
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$this->db->init('stat','statid','article');
		$this->db->setCriteria(new Criteria('mid', 'sale'));
		$this->db->criteria->add(new Criteria('display',0));
// 		$this->db->criteria->add(new Criteria('siteid',$siteid,'='));//不需要添加此查询条件
		if($params['keyword']){
			if($params['keytype']==0){
				$this->db->criteria->add(new Criteria('articlename', '%'.$params['keyword'].'%','LIKE'));
			}else if($params['keytype']==1){
				$this->db->criteria->add(new Criteria('author', '%'.$params['keyword'].'%','LIKE'));
			}
		}
		if($params['nowagent']){
			$this->db->criteria->add(new Criteria('agentid', $params['nowagent']));
		}
		if(is_numeric($params['firstflag'])){
			$this->db->criteria->add(new Criteria('firstflag', $params['firstflag']));
		}
		$tablSql = $this->dbprefix('article_article')." AS a RIGHT JOIN ".$this->dbprefix('article_stat')." AS s ON a.articleid=s.articleid";
		$this->db->criteria->setTables($tablSql);
		//render sql,子查询-符合查询条件的总销售额
		$saledSql = "select COALESCE(sum(s.day),0) from $tablSql ".$this->db->criteria->renderWhere()." and s.lasttime >= $daystart";
		$salewSql = "select COALESCE(sum(s.week),0) from $tablSql ".$this->db->criteria->renderWhere()." and s.lasttime >= $weekstart";
		$salemSql = "select COALESCE(sum(s.month),0) from $tablSql ".$this->db->criteria->renderWhere()." and s.lasttime >= $monthstart";
		$this->db->criteria->setFields("($saledSql) as days,($salewSql) as weeks,($salemSql) as months,s.*,a.articlename,a.author,a.authorid,a.display,a.articleid,a.postdate,a.lastupdate,a.agent,a.agentid,a.firstflag,a.fullflag");
		if($params['type']){
			if($order!='total'){
				$this->db->criteria->add (new Criteria('lasttime',$this->getTime($order),'>='));
			}
			$this->db->criteria->setSort("$order");
		}else{
			$this->db->criteria->setSort('lastupdate');
		}
		$this->db->criteria->setOrder('DESC');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$package = $this->load('article','article');//加载文章处理类
		$data ['pay'] = $this->db->lists ($jieqiConfigs['system']['messagepnum'], $params['page'],JIEQI_PAGE_TAG);
		$k=0;
		foreach($data['pay'] as $k=>$v)
		{
			if($daystart > $v['lasttime']){
				$data['pay'][$k]['day'] = 0;
			}
			if($weekstart > $v['lasttime']){
				$data['pay'][$k]['week'] = 0;
			}
			if($monthstart > $v['lasttime']){
				$data['pay'][$k]['month'] = 0;
			}
// 			if($monthstart == $weekstart && $v['lasttime'] >= $weekstart){		//如果周开始和月开始是同一天,月销售就是周销售.
// 				$data['pay'][$k]['week'] = $data['pay'][$k]['month'];
// 			}
			$data['pay'][$k]['day'] = sprintf("%1\$.2f",$data['pay'][$k]['day']);
			$data['pay'][$k]['week'] = sprintf("%1\$.2f",$data['pay'][$k]['week']);
			$data['pay'][$k]['month'] = sprintf("%1\$.2f",$data['pay'][$k]['month']);
			$data['pay'][$k]['total'] = sprintf("%1\$.2f",$data['pay'][$k]['total']);
			$k++;
		}
		// 处理页面跳转
		include_once(HLM_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($this->db->getCount($this->db->criteria),$jieqiConfigs['system']['messagepnum'],$params['page']);
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
				  $agents = $this->db->lists();
			 }
		 }
		$source = $package->getSources();
 		$data ['url_jumppage'] = $jumppage->whole_bar();
		$articleLib = $this->load('article','article');
		foreach($data ['pay'] as $k=>$v){
			$data ['pay'][$k] = $articleLib->article_vars($v);
		}
		$days = sprintf("%1\$.2f",$data['pay'][0]['days']);
		$weeks = sprintf("%1\$.2f",$data['pay'][0]['weeks']);
		$months = sprintf("%1\$.2f",$data['pay'][0]['months']);
		$data['days'] = $days;
		$data['weeks'] = $weeks;
		$data['months'] = $months;
		if($monthstart == $weekstart) $data['weeks'] = $data['months'];		//如果周开始和月开始是同一天,月销售就是周销售.
		$data ['keyword'] = $params['keyword'];
		$data ['keytype'] = $params['keytype'];
		$data['agents'] = $agents;
		$data['channel'] = $source['channel'];
		$data['soruce'] = $source['firstflag']['items'];
		$data['groups'] = $jieqiGroups;
		$data['nowagent'] = $params['nowagent'];
		$data['firstflag'] = $params['firstflag'];
		$data['article_dynamic_url'] = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		return $data;
	}
	
	/**
	 * 章节销售查询
	 */
	public function chapterstat($params){
		if(!$params['page']) $params['page']=1;
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$chapterModel = $this->db->init('chapter','chapterid','article');
		$chapterModel->setCriteria();
		// ----------原代码-------------------
//		$chapterModel->criteria->setTables($this->dbprefix('article_chapter')." AS c LEFT JOIN ".$this->dbprefix('article_sale')." AS s ON c.chapterid = s.chapterid");
//		$chapterModel->criteria->setFields('c.chapterid,c.chaptername,s.saleprice,c.display,s.articlename,s.articleid,(select count(*) from '.$this->dbprefix('article_sale').' where chapterid = s.chapterid ) as num ,(select SUM(saleprice*100) from '.$this->dbprefix('article_sale').' where chapterid = c.chapterid ) as sum ');
//		$chapterModel->criteria->add(new Criteria('s.articleid', $params['aid']));
//		$chapterModel->criteria->setGroupby('s.chapterid');
//		$chapterModel->criteria->setSort('s.chapterid');
//		$chapterModel->criteria->setOrder('DESC');
		// ---------liuxiangbin 重写----------
		$chapterModel->criteria->setFields('chapterid,chaptername,saleprice,display,articlename,articleid');
		$chapterModel->criteria->add(new Criteria('articleid', intval($params['aid']), '='));
		$chapterModel->criteria->add(new Criteria('isvip', 1, '='));
		$chapterModel->criteria->add(new Criteria('chaptertype', 0, '='));
		$chapterModel->criteria->setSort('chapterid');
		$chapterModel->criteria->setOrder('ASC');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$res = $chapterModel->lists ($jieqiConfigs['system']['messagepnum'], $params['page'],JIEQI_PAGE_TAG);
		// 处理页面跳转
		include_once(HLM_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($chapterModel->getCount($chapterModel->criteria),$jieqiConfigs['system']['messagepnum'],$params['page']);
		// 重新设置数据：添加销售合计信息
		$data['pay'] = $res;

            $saletable='salelog_'.date("Ym");
            $saleModel = $this->db->init($saletable, 'saleid', 'article');
            foreach ($res as $k => $v) {
                $saleModel->setCriteria();
                $saleModel->criteria->add(new Criteria('chapterid', $v['chapterid'], '='));
                $saleModel->criteria->setFields('count(saleid) as num,sum(saleprice) as sum');
                $saleModel->criteria->setGroupby('chapterid');
                $tmp_res = $saleModel->lists();
                $data['pay'][$k]['saleprice'] = $v['saleprice'] != 0 ? number_format($v['saleprice'], 2, '.', '') : 0;
                $data['pay'][$k]['num'] = $tmp_res[0]['num'] != 0 ? $tmp_res[0]['num'] : 0;
                $data['pay'][$k]['sum'] = $tmp_res[0]['sum'] != 0 ? number_format($tmp_res[0]['sum'], 2, '.', '') : 0;
            }
		// 页面显示数据重组
		$jumppage->setlink('', true, true);
 		$data ['url_jumppage'] = $jumppage->whole_bar();
		$articleLib = $this->load('article','article');
		foreach($data ['pay'] as $k=>$v){
			$data ['pay'][$k] = $articleLib->article_vars($v);
		}
		$data['articlename'] = $data['pay'][0]['articlename'];
		return $data;
	}
	
	public function chapterbuylog($params){
		if(!$params['page']) $params['page']=1;
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');

			$table='salelog_'.date("Ym");
			$this->db->init($table, 'saleid', 'article');
			$this->db->setCriteria();
			$this->db->criteria->add(new Criteria('chapterid', $params['cid']));
			$this->db->criteria->setSort('saleid');
			$this->db->criteria->setOrder('DESC');
			$this->addConfig('system', 'configs');
			$jieqiConfigs['system'] = $this->getConfig('system', 'configs');
			if (!is_array($data['pay'])) {
				$data['pay'] = $this->db->lists($jieqiConfigs['system']['messagepnum'], $params['page'], JIEQI_PAGE_TAG);
			}
			else {
				$data['pay'] = array_merge($data['pay'],$this->db->lists($jieqiConfigs['system']['messagepnum'], $params['page'], JIEQI_PAGE_TAG));
			}


		// 处理页面跳转
		include_once(HLM_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($this->db->getCount($this->db->criteria),$jieqiConfigs['system']['messagepnum'],$params['page']);
		$jumppage->setlink('', true, true);
 		$data ['url_jumppage'] = $jumppage->whole_bar();
		$articleLib = $this->load('article','article');
		foreach($data ['pay'] as $k=>$v){
			$data ['pay'][$k] = $articleLib->article_vars($v);
		}
		$data ['keyword'] = $params['keyword'];
		$data ['keytype'] = $params['keytype'];
		return $data;
	}
}
?>