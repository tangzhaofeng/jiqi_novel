<?php 
/**
 * 作家申请记录
 * @author chengyuan  2014-4-24
 *
 */
class applyModel extends Model{
	/**
	 * 申请列表
	 * @param unknown $params
	 */
	public function applyList($params = array()){
		global $jieqiModules;
		$this->addConfig('article','configs');
		$data = array();
		$this->db->init('applywriter', 'applyid', 'article' );
		$this->action($params);
		//显示列表
		$this->db->setCriteria();
		$this->db->criteria->setSort('applyid');
		switch ($params['display']){
			case 'ready':
				$this->db->criteria->add(new Criteria('applyflag', 0));
				break;
			case 'success':
				$this->db->criteria->add(new Criteria('applyflag', 1));
				$this->db->criteria->setSort('authtime');
				break;
			case 'failure':
				$this->db->criteria->add(new Criteria('applyflag', 2));
				$this->db->criteria->setSort('authtime');
				break;
		}
		$this->db->criteria->setOrder('DESC');
		$data ['rows'] = $this->db->lists ($this->getConfig('article','configs','pagenum'), $params ['page'] );
		foreach($data['rows'] as $k=>$v){
			$v['applytext'] = $this->getFormat($v['applytext'],'s');
			$data['rows'][$k] = $v;
		}
		$data ['url_jumppage'] = $this->db->getPage ();
		$data ['display'] = $params ['display'];
		$data ['article_dynamic_url'] = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		return $data;
	}
	
	
	private function action($params){
		$auth = $this->getAuth();
		if(isset($params['action']) && !empty($params['aid'])){
			//$this->db->init('article','articleid','article');
			switch($params['action']){
				case 'del'://删除
					$this->db->delete($params['aid']);
					break;
				case 'refusal'://拒绝
					$this->db->edit($params['aid'],array('applyflag'=>2,'authtime'=>JIEQI_NOW_TIME,'authuid'=>$auth['uid'],'authname'=>$auth['useruname']));
					break;
				case 'audit'://通过
					$articleLib = $this->load ( 'article', 'article' );
					$this->db->edit($params['aid'],array('applyflag'=>1,'authtime'=>JIEQI_NOW_TIME,'authuid'=>$auth['uid'],'authname'=>$auth['useruname']));
					$applyUser = $articleLib->updateGroup( $params['applyuid'],$articleLib->jieqiConfigs['article']['writergroup'],false);
					//发送短信
					$apply = $this->model('message','system');
					$apply->auditApproval($params['applyuid'], $applyUser->getVar('uname', 'n'), $articleLib->jieqiLang['article']['apply_confirm_title'],$articleLib->jieqiLang['article']['apply_confirm_text']);
					//发送消息更改了操作表，这里在做applywriter表
					$this->db->init('applywriter', 'applyid', 'article');
					break;
			}
	
		}
	}
} 
?>