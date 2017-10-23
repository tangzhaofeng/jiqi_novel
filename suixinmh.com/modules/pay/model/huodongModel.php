<?php 
/** 
 * 冲值模型 * @copyright   Copyright(c) 2014 
 * @author      zhangxue* @version     1.0 
 */ 
class huodongModel extends Model{ 
	//书海卡
	function main($params = array()){
//		define('JIEQI_EGOLD_NAME', '银币');
		$params['cardno'] = trim($params['cardno']);
		$params['cardpass'] = trim($params['cardpass']);
		$auth = $this->getAuth();
		$this->db->init( 'paycard', 'cardid', 'pay' );
		$this->db->setCriteria(new Criteria( 'cardno', $params['cardno'], '=' ));
		if($paycard = $this->db->get($this->db->criteria)){
			if($paycard->getVar('ispay','n')) $this->printfail('冲值卡号已经被使用了，请不要重复提交！');
			if(strlen($params['cardno'])==14 && $paycard->getVar('cardpass','n')!=$params['cardpass']) $this->printfail('冲值卡密码不对应，请返回重试！');
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			if($users_handler->income($auth['uid'], $paycard->getVar('payemoney','n'), $paycard->getVar('emoneytype','n'), 0)){
				$cardid = $paycard->getVar('cardid','n');
				$paycard->setVar('ispay',1);
				$paycard->setVar('paytime',JIEQI_NOW_TIME);
				$paycard->setVar('payuid',$auth['uid']);
				$paycard->setVar('payname',$auth['username']);
				if(!$this->db->edit($cardid, $paycard)){
					$this->printfail('系统出错，请重试！');
				}else{
					$buy_egold_success='恭喜您，%s:<br /><br />您使用冲值卡(%s)：%s 点银币已经入帐，请检查 <a href="/user" class="f14 f_blue" title="进入个人中心">您的帐号</a>。<br /><br />感谢您对我们的支持！';
					$this->jumppage($_SERVER['HTTP_REFERER'], LANG_DO_SUCCESS, sprintf($buy_egold_success,$auth['username'],$paycard->getVar('cardno','n'),$paycard->getVar('payemoney','n')));
				}
			}else{
				$this->printfail('系统出错，请重试！');
			}
		}else{
			$this->printfail('卡号不存在!');
		}
	} 
} 

?>