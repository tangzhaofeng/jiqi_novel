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
		if($params['keyword']&&$params['keytype']==1){
			$this->db->init('users','uid','system');
			$this->db->setCriteria(new Criteria('uname', $params['keyword'], 'like'));
            $user = $this->db->get($this->db->criteria);
			if($user) {
                $uid = $user->getVar('uid');
                $uname = $params['keyword'];
            }
		}
        elseif ($params['keyword']&&$params['keytype']==0) {
            $uid = intval(trim($params['keyword']));
        }
        $saletable=get_salle_table(intval($uid));

		$this->db->init($saletable,'saleid','article');
		$this->db->setCriteria();
        if ($uid) {
            $this->db->criteria->add(new Criteria('accountid', $uid));
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
        foreach($data['pay'] as $id=> $salelog) {
            if (!isset($uname)) {
                $this->db->init('users','uid','system');
                $this->db->setCriteria(new Criteria('uid', $salelog['accountid'], '='));
                $user = $this->db->get($this->db->criteria);
                $username=$user->getVar('uname');
            }
            else {
                $username=$uname;
            }
            $this->db->init('article','articleid','article');
            $this->db->setCriteria(new Criteria('articleid', $salelog['articleid'], '='));
            $article = $this->db->get($this->db->criteria);
            if ($article) {
                $articlename = $article->getVar('articlename');
                $this->db->init('chapter','chapterid','article');
                $this->db->setCriteria(new Criteria('chapterid', $salelog['chapterid'], '='));
                $chapter = $this->db->get($this->db->criteria);
                $chaptername=$chapter->getVar('chaptername');
            }
            else {
                $articlename = "unknow";
                $chaptername = "unknow";
            }



            $data['pay'][$id]['account']=$username;
            $data['pay'][$id]['articlename']=$articlename;
            $data['pay'][$id]['chaptername']=$chaptername;
        }
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