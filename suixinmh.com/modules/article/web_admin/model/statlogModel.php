<?php 
class statlogModel extends Model{
	
	
	public function statlog($param = array()){
		$data = array();
		$articleLib = $this->load('article','article');//加载文章处理类
		$statArr = $articleLib->getStatArray();
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$this->db->init('statlog','statlogid','article');
		$this->db->setCriteria();
		$this->db->criteria->setTables($this->dbprefix('article_article')." AS a RIGHT JOIN ".$this->dbprefix('article_statlog')." AS s ON a.articleid=s.articleid");
		$this->db->criteria->setFields("s.*,a.articlename");
		$param['keyword'] = urldecode($param['keyword']);//汉字url解码
		if($param['type'] == 'sm' && !empty($param['keyword'])){
			$this->db->criteria->add(new Criteria('a.articlename', '%'.$param['keyword'].'%', 'like'));
			$data ['type']=$param['type'];
			$data ['keyword']=$param['keyword'];
		}elseif ($param['type'] == 'czr' && !empty($param['keyword'])){
			$this->db->criteria->add(new Criteria('s.username', '%'.$param['keyword'].'%', 'like'));
			$data ['type']=$param['type'];
			$data ['keyword']=$param['keyword'];
		}
		$this->db->criteria->setSort('s.addtime');
		$this->db->criteria->setOrder('desc');
		$data ['statlogs'] = $this->db->lists ($jieqiConfigs['article']['pagenum'], $param['page']);
		foreach($data['statlogs'] as $k=>$v){
			$data ['statlogs'][$k]  = $articleLib ->article_statlog_vars($v);
		}
		// 处理页面跳转
		$data ['url_jumppage'] = $this->db->getPage ($this->getUrl('article','statistics','evalpage=0','SYS=method=main'));
		return $data;
	}
} 
?>