<?php 
/** 
 * 小说连载->后台搜索管理 * @copyright   Copyright(c) 2014 
 * @author      liujilei* @version     1.0 
 */ 

class searchModel extends Model{
	public function main($params = array()){
	    global $jieqiModules;
		$this->addConfig('article','configs');
		$this->addLang('article', 'search');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$jieqiLang['article'] = $this->getLang('search');

		$this->db->init('searchcache','cacheid','article');
		$this->db->setCriteria();	
		$this->db->criteria->setSort('cacheid');
        $this->db->criteria->setOrder('DESC');
		switch($params['flag']){
		case 'no':
			$this->db->criteria->add(new Criteria('results', 0, '=')); 
			break;
		default:
			break;
	  }
	
	  $data = $this->db->lists($jieqiConfigs['article']['pagenum'],$params['page']);
	 return array(
	 'cacherows'=>$data,
	 'url_jumppage'=>$this->db->getPage(),
	 'article_static_url' => (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'],
	 'article_dynamic_url' => (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl']
	 );
	 
	}
}