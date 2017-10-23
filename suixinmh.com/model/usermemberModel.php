<?php
/**
 * 用户中心->会员专区
 * @author zhuyunlong  2014-6-20
 *
 */
 
class usermemberModel extends Model{
	public function usermember($param){
		$this->addConfig('system','configs');
		$this->addConfig('article','configs');
		$this->addConfig('system','right');
		$this->addConfig('article','right');
		$this->addConfig('system','honors');
		$jieqiHonors = $this->getconfig('system', 'honors');
		$jieqiRight['system'] = $this->getConfig('system','right');
		$jieqiRight['article'] = $this->getConfig('article','right');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');

		foreach($jieqiHonors as $k=>$v){
			$v['maxbookmarks'] = $jieqiRight['article']['maxbookmarks']['honors'][$k];
			$v['dayvotes'] = $jieqiRight['article']['dayvotes']['honors'][$k];
			$v['maxfriends'] = $jieqiRight['system']['maxfriends']['honors'][$k];
			$v['maxdaymsg'] = $jieqiRight['system']['maxdaymsg']['honors'][$k];
			$jieqiHonors[$k] = $v;
		}
		
		return array('honors'=>$jieqiHonors,'score'=>$jieqiConfigs);
	}
	public function uservip($param){
		$this->addConfig('system','vipgrade');
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$this->db->init('vipgrade','vipgradeid','system');
		$this->db->setCriteria();
		$this->db->queryObjects();
		$i = 0;
		$viparr = array();
		while($v = $this->db->getObject()){
			 
			$viparr[$i]=unserialize($v->getVar('setting','n'));
			$i++;
		}
		return array('vipgrade'=>$jieqiVipgrade,'vip'=>$viparr,'configs'=>$jieqiConfigs);
		
	}
}
?>