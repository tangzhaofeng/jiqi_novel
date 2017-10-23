<?php
/**
 * 充值分成渠道管理
 * @author chengyuan  2014-6-12
 *
 */
class homeModel extends Model{
		
	/*
	 * 渠道用户充值列表
	 * 
	 */
	public function main($params = array()){//print_r($params);exit;
		$markname = $this->checklogin();
		if(empty($params['payflag'])) $params['payflag']=1;
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay');
		$this->addConfig('pay','paytype');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$this->db->init('paylog','payid','pay');

		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('source', $markname));
		if($params['payflag']==1||$params['payflag']==2){
			$this->db->criteria->add(new Criteria('payflag', $params['payflag']));
		}
		if($params['payflag']==3){
			$this->db->criteria->add(new Criteria('payflag', 0));
		}
		if($params['start']){
			$params['start'] = urldecode($params['start']);
			$start = strtotime($params['start']);
			$this->db->criteria->add(new Criteria('buytime', $start,'>='));
		}
		if($params['end']){
			$params['end'] = urldecode($params['end']);
			$end = strtotime($params['end']);
			$this->db->criteria->add(new Criteria('buytime', $end,'<='));
		}
		if($params['paytype']&&$params['paytype']!='all'){
			$this->db->criteria->add(new Criteria('paytype', $params['paytype']));
		}
//		if($params['keyword']){
//			if($params['keytype']==0){
//				$this->db->criteria->add(new Criteria('payid', '%'.$params['keyword'].'%','LIKE'));
//			}else if($params['keytype']==1){
//				$this->db->criteria->add(new Criteria('buyname', '%'.$params['keyword'].'%','LIKE'));
//			}
//		}
		$this->db->criteria->setSort('buytime DESC,payid');
		$this->db->criteria->setOrder('DESC');
		$payrows = $this->db->lists($jieqiConfigs['system']['messagepnum'], $params['page']);
		$sum = $this->db->getSum('money');
		$sum = sprintf('%0.2f',$sum/100);//echo $sum;
		$sumegold = $this->db->getSum('egold');
		foreach($payrows as $k=>$v)
		{
			if($v['payflag']==0){
				$v['payflag_c'] = $jieqiLang['pay']['state_unconfirm'];
			}elseif($v['payflag']==1){
				$v['payflag_c'] = $jieqiLang['pay']['state_paysuccess'];
			}elseif($v['payflag']==2){
				$v['payflag_c'] = $jieqiLang['pay']['state_handconfirm'];
			}
			$v['money'] = sprintf('%0.2f',$v['money']/100);
			$payrows[$k] = $v;
			$k++;
		}
		return array(
			'payrows'=>$payrows,
			'sum'=>$sum,
			'start'=>$params['start'],
			'end'=>$params['end'],
			'sumegold'=>$sumegold,
			'source'=>$_SESSION['sourcemanage']['sname'],
//			'keyword'=>$params['keyword'],
//			'keytype'=>$params['keytype'],
			'payflag'=>$params['payflag'],
			'paytype'=>$params['paytype'],
			'paytyperows'=>$this->getConfig('pay','paytype'),
			'url_jumppage'=>$this->db->getPage(),
			'totalnum'=>$this->db->getCount()
		);
	}
	/*
	 * 渠道用户登录
	 * 
	 */
	public function login($params = array()){
		$this->addLang('system', 'users');
		$jieqiLang['system'] = $this->getLang('system');
		if($this->submitcheck()){
			if(empty($params['username'])) $this->printfail($jieqiLang['system']['need_username']);
			if(empty($params['password'])) $this->printfail($jieqiLang['system']['need_password']);
			if($_SESSION['jieqiCheckCode']==$params['checkcode']){
				$this->db->init('sources','sid','pooling');
				$this->db->setCriteria(new Criteria('name', $params['username']));
				$userinfo = $this->db->lists();
				if($userinfo && $userinfo[0]['locked']==1){
					if($params['password']==$userinfo[0]['password']){
						$_SESSION['sourcemanage']=array(
							'markname' => $userinfo[0]['markname'],
							'sname' => $userinfo[0]['sname']
						);
						$this->jumppage($GLOBALS ['jieqiModules'] ['pooling'] ['url'].'/hezuo/');
					}else $this->printfail($jieqiLang['system']['error_userpass']);
				}else $this->printfail($jieqiLang['system']['no_this_user']);
			}else $this->printfail($jieqiLang['system']['error_checkcode']);
		}
	}
	/*
	 * 渠道用户检测登录状态
	 * 
	 */
	public function checklogin(){
		if($_SESSION['sourcemanage']['markname']){
			return $_SESSION['sourcemanage']['markname'];
		}else{
			header('Location: ?method=login');
			exit;
		}
	}
	/*
	 * 渠道用户退出登录
	 * 
	 */
	public function logout(){
		if($_SESSION['sourcemanage']['markname']){
			$_SESSION['sourcemanage']=array();
		}
		header('Location: ?method=login');
		exit;
	}
}
?>
	