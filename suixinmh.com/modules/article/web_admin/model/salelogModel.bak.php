<?php 
/** 
 * 小说连载->订阅管理 * @copyright   Copyright(c) 2014 
 * @author      zhuyunlong* @version     1.0 
 */ 

class salelogModel extends Model{

	public function main($params){
		if(!$params['page']) $params['page']=1;
		$params['keyword'] = urldecode($params['keyword']);
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		if($params['keyword']&&$params['keytype']==0){
			$this->db->init('article','articleid','article');
			$this->db->setCriteria(new Criteria('articlename', $params['keyword']));
			if($article = $this->db->get($this->db->criteria)) $aid = $article->getVar('articleid');
		}
		$this->db->init('sale','saleid','article');
		$this->db->setCriteria();
		if($params['keyword']){
			if($params['keytype']==0&&$aid){
				$this->db->criteria->add(new Criteria('articleid', $aid));
//				$this->db->criteria->add(new Criteria('articlename', '%'.$params['keyword'].'%','LIKE'));
			}else if($params['keytype']==1){
				$this->db->criteria->add(new Criteria('account', '%'.$params['keyword'].'%','LIKE'));
			}
		}
		$this->db->criteria->setSort('saleid');
		$this->db->criteria->setOrder('DESC');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$data ['pay'] = $this->db->lists ($jieqiConfigs['system']['messagepnum'], $params['page'],JIEQI_PAGE_TAG);
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