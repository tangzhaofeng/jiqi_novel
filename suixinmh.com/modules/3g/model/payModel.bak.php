<?php
/**
 * 支付模型
 * @author zhangxue
 *
 */
class payModel extends Model {
	//畅天游订单
	public function unicom($params = array(), $jieqiPayset = array()) {
		 define('JIEQI_PAY_TYPE', 'unicom');
		 $this->addLang('pay', 'pay');
		 $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		 //判断代理
		 $user_is_agent = '';
		 if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
			 $user_is_agent = 'agent';
		 }
		 if($params['mobile']=='请输入用来充值的手机号'){
		 	$this->printfail($params['mobile']);
		 }elseif(strlen($params['mobile'])<11){
		 	$this->printfail('请输入标准11位手机号');
		 }else{
			 $params['egold']=intval($params['egold']);
			 if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
				  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
					  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])) $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
					  else $this->printfail($jieqiLang['pay']['buy_type_error']);
				  }else{
					  $this->printfail($jieqiLang['pay']['buy_type_error']);
				  }
				  $auth = $this->getAuth();
				  
				    $this->db->init( 'paylog', 'payid', 'pay' );
					$paylog['siteid'] = $GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['siteid'];
					$paylog['buytime'] = JIEQI_NOW_TIME;
					$paylog['rettime'] = 0;
					$paylog['buyid'] = $auth['uid'];
					$paylog['buyname'] = $auth['username'];
					$paylog['buyinfo'] = '';
					$paylog['moneytype'] = $jieqiPayset[JIEQI_PAY_TYPE]['moneytype'];
					$paylog['money'] = $money;
					$paylog['egoldtype'] = $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'];
					$paylog['egold'] = $params['egold'];
					$paylog['paytype'] = JIEQI_PAY_TYPE;
					$paylog['retserialno'] = '';
					$paylog['retaccount'] = '';
					$paylog['retinfo'] = $this->getip();
					$paylog['masterid'] = 0;
					$paylog['mastername'] = '';
					$paylog['masterinfo'] = '';
					$paylog['note'] = $user_is_agent;
					$paylog['payflag'] = 0;
					$paylog['source'] = $auth['source'];
					if(!$payid = $this->db->add($paylog)) $this->printfail($jieqiLang['pay']['add_paylog_error']);
					else{
						$urlvars=array();
						$urlvars['Productsid']=$jieqiPayset[JIEQI_PAY_TYPE]['product_id'][$params['egold']];//产品编号
						$urlvars['orderid']=$payid; //订单号
						$urlvars['mobile']=$params['mobile']; //手机号
						$amount=$money/100;
						$urlvars['Amount']=$amount; //金额 单位：元
						$urlvars['key']=md5($payid.$params['mobile'].$amount.$jieqiPayset[JIEQI_PAY_TYPE]['orderkey']);
						$query='';
						foreach($urlvars as $k=>$v){
							if(!empty($query)) $query.='&';
							$query.=$k.'='.urlencode($v);
						}
						$query=$jieqiPayset[JIEQI_PAY_TYPE]['payurl'].'?'.$query;
						
						$XML = $this->load('getxml','system');
						$data = $XML->getData($query);
						if($data['Root']['Result']['value']==0){
							$buyname = $auth['username'];
							$money = sprintf('%0.2f', $money / 100);
							$msg = $data['Root']['Msg']['value'];
							if($msg!='请将收到的短信验证码回填于页面，进行支付。')$this->printfail($msg);
							 return array(
								  'urlvars'=>$urlvars,
								  'msg'=>$msg,
								  'paytype'=>$jieqiPayset[JIEQI_PAY_TYPE]['paytype'],
								  'buyname'=>$buyname,
								  'egold'=>$params['egold'],
								  'mobile'=>$params['mobile'],
								  'egoldname'=>JIEQI_EGOLD_NAME,
								  'money'=>$money,
								  'moneytype'=>1
							 );
						}elseif($data['Root']['Result']['value']==4){
//							echo $data['Root']['Result']['value'];//print_r($urlvars);
							$this->printfail($jieqiLang['pay']['customer_id_error']);
						}elseif($data['Root']['Result']['value']==3){
							$this->printfail($jieqiLang['pay']['return_checkcode_error']);
						}else{
							$this->printfail($jieqiLang['pay']['pay_failure_message']);
						}
					}
			 }else{
			      $this->printfail($jieqiLang['pay']['buy_type_error']);
			 }
		 }
	}
	//畅天游验证
	public function yanunicom($params = array(), $jieqiPayset = array()) {//print_r($jieqiPayset);//exit();
		    define('JIEQI_PAY_TYPE', 'unicom');
			$this->addLang('pay', 'pay');
			$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
			$urlvars['Productsid']=$jieqiPayset[JIEQI_PAY_TYPE]['product_id'][$params['egold']]; //合作商户号
			$urlvars['orderid']=$params['orderid']; //订单号
			$urlvars['mobile']=$params['mobile']; //手机号
			$urlvars['Amount']=$params['Amount']; //金额 单位：元
			$urlvars['VerificationCode']=$params['VerificationCode']; //短信验证码
			$urlvars['key']=md5($params['orderid'].$params['mobile'].$params['Amount'].$params['VerificationCode'].$jieqiPayset[JIEQI_PAY_TYPE]['orderkey']);
			$query='';
			foreach($urlvars as $k=>$v){
				if(!empty($query)) $query.='&';
				$query.=$k.'='.urlencode($v);
			}
			$query=$jieqiPayset['unicom']['yanurl'].'?'.$query;
			
			$XML = $this->load('getxml','system');
			$data = $XML->getData($query);
			if($data['Root']['Result']['value']==0){
				 return array(
					  'msg'=>$data['Root']['Msg']['value']
				 );
//				$this->msgwin(LANG_DO_SUCCESS,$data['Root']['Msg']['value']);
			}elseif($data['Root']['Result']['value']==4){
				$this->printfail($jieqiLang['pay']['customer_id_error']);
			}elseif($data['Root']['Result']['value']==3){
				$this->printfail($jieqiLang['pay']['return_checkcode_error']);
			}else{
//				echo $data['Root']['Result']['value'];//print_r($urlvars);
				$this->printfail($jieqiLang['pay']['pay_failure_message']);
			}
	}
	//畅天游通知(联通、电信)
	function checkunicom($params = array(), $jieqiPayset = array()){
		define('JIEQI_PAY_TYPE', 'unicom');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		
		$retMsg='';
		$keyValue = $jieqiPayset[JIEQI_PAY_TYPE]['callbackkey'];
		$getvars=$_REQUEST;
		$paytype=JIEQI_PAY_TYPE;
		$orderid = trim($getvars['orderid']);
		$mobile = trim($getvars['mobile']);
		$Amount = trim($getvars['Amount']);
		$key = trim($getvars['key']);
		
		$checkkey = md5($orderid.$mobile.$Amount.$keyValue);
		if($checkkey!=$key){
			$retMsg = '3';	//校验码错误
		}else{
			$orderid=intval($orderid);
			$this->db->init( 'paylog', 'payid', 'pay' );
			$this->db->setCriteria(new Criteria( 'payid', $orderid, '=' ));
			$paylog=$this->db->get($this->db->criteria);
			if(is_object($paylog)){
				$buyname=$paylog->getVar('buyname');
				$buyid=$paylog->getVar('buyid');
				$payflag=$paylog->getVar('payflag');
				$egold=$paylog->getVar('egold');
				if($payflag == 0){
					$money=$paylog->getVar('money');
					include_once(JIEQI_ROOT_PATH.'/class/users.php');
					$users_handler = $this->getUserObject();
					$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $money/2, $money);
					if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
					else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
					$paylog->setVar('rettime', JIEQI_NOW_TIME);
					$paylog->setVar('note', $note);
					$paylog->setVar('payflag', 1);
					if(!$this->db->edit($orderid, $paylog)){
						$retMsg='7';	//充值成功，修改记录失败
					}else{
						$retMsg='0';	//交易成功
					}
				}else{
					$retMsg = '6';	//重复提交
				}
			}else{
				$retMsg = '8';	//无此交易记录
			}
		}
		exit('<Result>'.$retMsg.'</Result>');
	}
	//电信订单
	public function telecom($params = array(), $jieqiPayset = array()) {
		 define('JIEQI_PAY_TYPE', 'telecom');
		 $this->addLang('pay', 'pay');
		 $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		 //判断代理
		 $user_is_agent = '';
		 if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
			 $user_is_agent = 'agent';
		 }
		 if($params['mobile']=='请输入用来充值的手机号'){
		 	$this->printfail("请输入用来充值的手机号");
		 }else{
			 $params['egold']=intval($params['egold']);
			 if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
				  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
					  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])) $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
					  else $this->printfail($jieqiLang['pay']['buy_type_error']);
				  }else{
					  $this->printfail($jieqiLang['pay']['buy_type_error']);
				  }
				  $auth = $this->getAuth();
				  
				    $this->db->init( 'paylog', 'payid', 'pay' );
					$paylog['siteid'] = $GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['siteid'];
					$paylog['buytime'] = JIEQI_NOW_TIME;
					$paylog['rettime'] = 0;
					$paylog['buyid'] = $auth['uid'];
					$paylog['buyname'] = $auth['username'];
					$paylog['buyinfo'] = '';
					$paylog['moneytype'] = $jieqiPayset[JIEQI_PAY_TYPE]['moneytype'];
					$paylog['money'] = $money;
					$paylog['egoldtype'] = $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'];
					$paylog['egold'] = $params['egold'];
					$paylog['paytype'] = JIEQI_PAY_TYPE;
					$paylog['retserialno'] = '';
					$paylog['retaccount'] = '';
					$paylog['retinfo'] = $this->getip();
					$paylog['masterid'] = 0;
					$paylog['mastername'] = '';
					$paylog['masterinfo'] = '';
					$paylog['note'] = $user_is_agent;
					$paylog['payflag'] = 0;
					$paylog['source'] = $auth['source'];
					if(!$payid = $this->db->add($paylog)) $this->printfail($jieqiLang['pay']['add_paylog_error']);
					else{
						$urlvars=array();
						$urlvars['Productsid']=$jieqiPayset[JIEQI_PAY_TYPE]['product_id'][$params['egold']];//产品编号
						$urlvars['orderid']=$payid; //订单号
						$urlvars['mobile']=$params['mobile']; //手机号
						$amount=$money/100;
						$urlvars['Amount']=$amount; //金额 单位：元
						$urlvars['key']=md5($payid.$params['mobile'].$amount.$jieqiPayset[JIEQI_PAY_TYPE]['orderkey']);
						$query='';
						foreach($urlvars as $k=>$v){
							if(!empty($query)) $query.='&';
							$query.=$k.'='.urlencode($v);
						}
						$query=$jieqiPayset[JIEQI_PAY_TYPE]['payurl'].'?'.$query;
						
						$XML = $this->load('getxml','system');
						$data = $XML->getData($query);//print_r($data);exit();
						if($data['Root']['Result']['value']==0){
							 return array(
								  'msg'=>$data['Root']['Msg']['value']
							 );
//							$this->msgwin(LANG_DO_SUCCESS,$data['Root']['Msg']['value']);
						}elseif($data['Root']['Result']['value']==4){
//							echo $data['Root']['Result']['value'];//print_r($urlvars);
							$this->printfail($jieqiLang['pay']['customer_id_error']);
						}elseif($data['Root']['Result']['value']==3){
							$this->printfail($jieqiLang['pay']['return_checkcode_error']);
						}else{
							$this->printfail($jieqiLang['pay']['pay_failure_message']);
						}
					}
			 }else{
			      $this->printfail($jieqiLang['pay']['buy_type_error']);
			 }
		 }
	}
	/**
	 * 悦蓝 H5版：创建支付
	 */
	public function mobile($params = array(), $jieqiPayset = array()) {
		 define('JIEQI_PAY_TYPE', 'mobile');
		 $this->addLang('pay', 'pay');
		 $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		 //判断代理
		 $user_is_agent = '';
		 if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
			 $user_is_agent = 'agent';
		 }
		 $params['egold']=intval($params['egold']);
		 if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
			  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
				  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])) $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
				  else $this->printfail($jieqiLang['pay']['buy_type_error']);
			  }else{
				  $this->printfail($jieqiLang['pay']['buy_type_error']);
			  }
			  $auth = $this->getAuth();
			    $this->db->init( 'paylog', 'payid', 'pay' );
				$paylog['siteid'] = $GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['siteid'];
				$paylog['buytime'] = JIEQI_NOW_TIME;
				$paylog['rettime'] = 0;
				$paylog['buyid'] = $auth['uid'];
				$paylog['buyname'] = $auth['username'];
				$paylog['buyinfo'] = '';
				$paylog['moneytype'] = $jieqiPayset[JIEQI_PAY_TYPE]['moneytype'];
				$paylog['money'] = $money;
				$paylog['egoldtype'] = $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'];
				$paylog['egold'] = $params['egold'];
				$paylog['paytype'] = JIEQI_PAY_TYPE;
				$paylog['retserialno'] = '';
				$paylog['retaccount'] = '';
				$paylog['retinfo'] = $this->getip();
				$paylog['masterid'] = 0;
				$paylog['mastername'] = '';
				$paylog['masterinfo'] = '';
				$paylog['note'] = $user_is_agent;
				$paylog['payflag'] = 0;
				$paylog['source'] = $auth['source'];
				if(!$payid = $this->db->add($paylog)) $this->printfail($jieqiLang['pay']['add_paylog_error']);
				else{
					$urlvars=array();
					$urlvars['spid']	= $jieqiPayset[JIEQI_PAY_TYPE]['spid'];
					$urlvars['cpid']	= $jieqiPayset[JIEQI_PAY_TYPE]['cpid'];
					$urlvars['appid']	= $jieqiPayset[JIEQI_PAY_TYPE]['appid'];
					$urlvars['ctimid']	= $jieqiPayset[JIEQI_PAY_TYPE]['ctimid'];
					$urlvars['passid']	= $jieqiPayset[JIEQI_PAY_TYPE]['passid'];
					$urlvars['price']	= $money/100;
					$urlvars['orderno']	= $payid;
					$urlvars['channelcode']= $jieqiPayset[JIEQI_PAY_TYPE]['channelcode'];
					$urlvars['callback']= $jieqiPayset[JIEQI_PAY_TYPE]['callback'];
					$urlvars['forward']= $jieqiPayset[JIEQI_PAY_TYPE]['forward'];
					
					$query='';
					foreach($urlvars as $k=>$v){
						if(!empty($query)) $query.='&';
						$query.=$k.'='.$v;
					}
					$appoint = strtolower(md5($query));
					
					$query = $jieqiPayset[JIEQI_PAY_TYPE]['payurl'].'?'.$query.'&appoint='.$appoint;
					$data = jieqi_readfile($query);
//					$data = iconv('utf-8','gbk',$data);
					$arr = json_decode($data,true);
					if($arr['res']==1){
						header('Location: '.$arr['url']);
					}else{
						$this->printfail($jieqiLang['pay']['pay_failure_message']);
					}
				}
		 }else{
		      $this->printfail($jieqiLang['pay']['buy_type_error']);
		 }
	}
	/**
	 * 悦蓝 H5版：回调接口
	 */
	function checkmobile($params = array(), $jieqiPayset = array()){
		define('JIEQI_PAY_TYPE', 'mobile');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		
		$retMsg='';
		$getvars=$_REQUEST;
//		$paytype=JIEQI_PAY_TYPE;
		$orderno = trim($getvars['orderno']);//??
		$phone = trim($getvars['phone']); //付费手机号
		$op = trim($getvars['op']);	//运营商：1：移动；2：联通；3：电信（暂不支持）
//		$resultDescription = iconv('utf-8','gbk',$getvars['resultDescription']); //结果描述
		if($jieqiPayset[JIEQI_PAY_TYPE]['spid']==$getvars['spid'] && $jieqiPayset[JIEQI_PAY_TYPE]['cpid']==$getvars['cpid'] && $jieqiPayset[JIEQI_PAY_TYPE]['appid']==$getvars['appid'] && $jieqiPayset[JIEQI_PAY_TYPE]['ctimid']==$getvars['ctimid'] && $jieqiPayset[JIEQI_PAY_TYPE]['channelcode']==$getvars['channelcode'] && $jieqiPayset[JIEQI_PAY_TYPE]['passid']==$getvars['passid'] && $getvars['resultCode']==2){
			$this->db->init( 'paylog', 'payid', 'pay' );
			$this->db->setCriteria(new Criteria( 'payid', $orderno));
			$paylog=$this->db->get($this->db->criteria);
			if(is_object($paylog)){
				$payid=$paylog->getVar('payid');
				$buyname=$paylog->getVar('buyname');
				$buyid=$paylog->getVar('buyid');
				$payflag=$paylog->getVar('payflag');
				$egold=$paylog->getVar('egold');
				if($payflag == 0){
					$money=$paylog->getVar('money');
					include_once(JIEQI_ROOT_PATH.'/class/users.php');
					$users_handler = $this->getUserObject();
					$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $money/2, $money);
					if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
					else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
					$paylog->setVar('rettime', JIEQI_NOW_TIME);
					$paylog->setVar('note', $note);
//					if(!empty($resultDescription)) $paylog->setVar('buyinfo', $resultDescription);
					$paylog->setVar('retserialno', $phone);
					$paylog->setVar('retaccount', $jieqiPayset[JIEQI_PAY_TYPE]['service_provider'][$op]);
					$paylog->setVar('payflag', 1);
					if(!$this->db->edit($payid, $paylog)){
						$retMsg='editfail';	//充值成功，修改记录失败
					}else{
						$retMsg='true';	//交易成功
					}
				}else{
					$retMsg = 'true';	//重复提交
				}
			}else{
				$retMsg = 'ordernull';	//无此交易记录
			}
		}else{
			$retMsg = 'verify_error';	//参数错误
		}
		exit($retMsg);
	}
	//天下付
    function txfpay($params = array(), $jieqiPayset = array()){
	     define('JIEQI_PAY_TYPE', 'txfpay');
		 $this->addLang('pay', 'pay');
		 $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		 //判断代理
		 $user_is_agent = '';
		 if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
		 	 $user_is_agent = 'agent';
		 }
		 $ip = $this->getip();
		 $ip = $ip ? $ip : '124.116.176.82';
		 $params['egold']=intval($params['egold']);
		 if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
			  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
				  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])) $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
				  else $this->printfail($jieqiLang['pay']['buy_type_error']);
			  }else{
				  $this->printfail($jieqiLang['pay']['buy_type_error']);
			  }
			  $auth = $this->getAuth();
			  
			    $this->db->init( 'paylog', 'payid', 'pay' );
				$paylog['siteid'] = $GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['siteid'];
				$paylog['buytime'] = JIEQI_NOW_TIME;
				$paylog['rettime'] = 0;
				$paylog['buyid'] = $auth['uid'];
				$paylog['buyname'] = $auth['username'];
				$paylog['buyinfo'] = '';
				$paylog['moneytype'] = $jieqiPayset[JIEQI_PAY_TYPE]['moneytype'];
				$paylog['money'] = $money;
				$paylog['egoldtype'] = $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'];
				$paylog['egold'] = $params['egold'];
				$paylog['paytype'] = JIEQI_PAY_TYPE;
				$paylog['retserialno'] = '';
				$paylog['retaccount'] = '';
				$paylog['retinfo'] = $ip;
				$paylog['masterid'] = 0;
				$paylog['mastername'] = '';
				$paylog['masterinfo'] = '';
				$paylog['note'] = $user_is_agent;
				$paylog['payflag'] = 0;
				$paylog['source'] = $auth['source'];
				if(!$payid = $this->db->add($paylog)) $this->printfail($jieqiLang['pay']['add_paylog_error']);
				else{				
					$paytype = $jieqiPayset[JIEQI_PAY_TYPE]['paytype'];
					$url_pay = $jieqiPayset[JIEQI_PAY_TYPE]['payurl'];
					$buyname = $auth['username'];
					$egold = $params['egold'];
					$egoldname = JIEQI_EGOLD_NAME;
					
					$urlvars=array();
					$urlvars['product_id']= $jieqiPayset[JIEQI_PAY_TYPE]['product_id'][$params['egold']];
					$urlvars['merchant_no']= $jieqiPayset[JIEQI_PAY_TYPE]['merchant_no_wap'];
					$urlvars['charge_amt']= $money / 100;
					$urlvars['num']= 1;
					$urlvars['user_account']= urlencode($auth['username']);
					$urlvars['order_id']= $payid;
					$urlvars['user_ip']= $ip;
					$urlvars['ret_type']= 2;
					$urlvars['url_tag']= $jieqiPayset[JIEQI_PAY_TYPE]['return_wap'];
					$urlvars['ext_param']= '';
					$urlvars['c']= 8;
					$sign = strtolower(md5('merchant_no='.$jieqiPayset[JIEQI_PAY_TYPE]['merchant_no_wap'].'||FaweB0Yo9dO0SD5xde&product_id='.$urlvars['product_id'].'&charge_amt='.($money / 100).'&num=1&user_account='.urlencode($auth['username']).'&order_id='.$payid.'&user_ip='.$urlvars['user_ip'].'&ret_type=2||zghsswfz1dbjaq'));

					$urlvars['sign']= $sign;
					$urlvars['view']= 'wap';
					 return array(
						  'urlvars'=>$urlvars,
						  'paytype'=>$paytype,
						  'url_pay'=>$url_pay,
						  'buyname'=>$buyname,
						  'egold'=>$egold,
						  'egoldname'=>$egoldname,
						  'money'=>$urlvars['charge_amt']
					 );
				}
		 }else{
		      $this->printfail($jieqiLang['pay']['buy_type_error']);
		 }
	}
	//天下付返回结果
	function checktxfpay($params = array(), $jieqiPayset = array()){
	    define('JIEQI_PAY_TYPE', 'txfpay');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		
		$retMsg='';
		$key3 = 'RH1tq8WJT5nDYx9v';//RH1tq8WJT5nDYx9v  RHXtq8WJT5nDYx9v
		$getvars=$_REQUEST;
		
		$ip = jieqi_userip();
		
		if(!$getvars['tag']) $retMsg = '0';// || !preg_match('/(122.224.178.4|124.160.238.111|60.191.73.11)/is', $ip)
		else{//如果充值成功
		
			//验证validate
			if($getvars['validate']!=strtolower(md5('orderid='.$getvars['orderid'].'&tag='.$getvars['tag'].'&trade_no='.$getvars['trade_no'].$key3))){
				$retMsg = '2';
			}else{
				if($getvars['validate2']!=strtolower(md5('orderid='.$getvars['orderid'].'&validate='.$getvars['validate'].'&face_value='.$getvars['face_value'].$key3))){
					$retMsg = '2';
				}else{
					$this->db->init( 'paylog', 'payid', 'pay' );
					$orderid=(int)$getvars['orderid'];
					$this->db->setCriteria(new Criteria( 'payid', $orderid, '=' ));
					$paylog=$this->db->get($this->db->criteria);
					if(is_object($paylog)){
						 $payflag=$paylog->getVar('payflag');
						 $egold=$paylog->getVar('egold');
						 $buyname=$paylog->getVar('buyname');
						 if($payflag == 0){
							 $money=$paylog->getVar('money');
							 $totalFee = ceil($money/100);
							 $face_value=(int)$getvars['face_value'];
							 if($totalFee!=$face_value){
								$retMsg = '2';
							 }else{
								 include_once(JIEQI_ROOT_PATH.'/class/users.php');
								 $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
								 $ret=$users_handler->income($paylog->getVar('buyid'), $paylog->getVar('egold'), $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], ceil($totalFee * 50), $money);
								 if($ret){
									 $paylog->setVar('rettime', JIEQI_NOW_TIME);
									 $paylog->setVar('payflag', 1);
									 if(!$this->db->edit($orderid, $paylog)){ 
										$retMsg="2";
									 }else{
										$retMsg="1";
									 }
								 }else $retMsg="2";
							 }
						 }else $retMsg="3";
					}else{
						$retMsg = '2';
					}
				}
			}
		}
		exit($retMsg);
	}
	//支付宝
    function alipay($params = array(), $jieqiPayset = array()){ 
	     //header("Content-type:text/html;charset=utf-8");
	     define('JIEQI_PAY_TYPE', 'alipay_wap');
		 $this->addLang('pay', 'pay');
		 $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		 //判断代理
		 $user_is_agent = '';
		 if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
		 	 $user_is_agent = 'agent';
		 }
		 $params['egold']=intval($params['egold']);
		 if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
			  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
				  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])) $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
				  else $this->printfail($jieqiLang['pay']['buy_type_error']);
			  }else{
				  $this->printfail($jieqiLang['pay']['buy_type_error']);
			  }
			  $auth = $this->getAuth();
			  
			    $this->db->init( 'paylog', 'payid', 'pay' );
				$paylog['siteid'] = $GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['siteid'];
				$paylog['buytime'] = JIEQI_NOW_TIME;
				$paylog['rettime'] = 0;
				$paylog['buyid'] = $auth['uid'];
				$paylog['buyname'] = $auth['username'];
				$paylog['buyinfo'] = '';
				$paylog['moneytype'] = $jieqiPayset[JIEQI_PAY_TYPE]['moneytype'];
				$paylog['money'] = $money;
				$paylog['egoldtype'] = $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'];
				$paylog['egold'] = $params['egold'];
				$paylog['paytype'] = JIEQI_PAY_TYPE;
				$paylog['retserialno'] = '';
				$paylog['retaccount'] = '';
				$paylog['retinfo'] = $this->getip();
				$paylog['masterid'] = 0;
				$paylog['mastername'] = '';
				$paylog['masterinfo'] = '';
				$paylog['note'] = $user_is_agent;
				$paylog['payflag'] = 0;
				$paylog['source'] = $auth['source'];
				if(!$payid = $this->db->add($paylog)) $this->printfail($jieqiLang['pay']['add_paylog_error']);
				else{
					require_once($GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['path'].'/alipay/alipay.config.php');//print_r($alipay_config);exit();
					require_once($GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['path'].'/alipay/alipay_submit.class.php');//print_r($alipay_config);exit();
//					$this->dump($alipay_config);
					$out_trade_no = $payid;
					$subject = $jieqiPayset[JIEQI_PAY_TYPE]['subject'][$params['egold']];//JIEQI_EGOLD_NAME;
					$total_fee = sprintf("%.2f",$money/100);
					include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
					/*$para_token = array(
							"service" => "alipay.wap.trade.create.direct",
							"partner" => trim($alipay_config['partner']),
							"sec_id" => trim($alipay_config['sign_type']),
							"format"	=> $format,
							"v"	=> $v,
							"req_id"	=> $payid,
							"req_data"	=> jieqi_gb2utf8($req_data),
							"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
					);*/
					$parameter = array(
							"service" => "alipay.wap.create.direct.pay.by.user",
							"partner" => trim($alipay_config['partner']),
							"seller_id" => trim($alipay_config['seller_id']),
							"payment_type"	=> '1',
							"notify_url"	=> $notify_url,
							"return_url"	=> $call_back_url,
							"out_trade_no"	=> $out_trade_no,
							"subject"	=> jieqi_gb2utf8($subject),
							"total_fee"	=> $total_fee,
							"show_url"	=> $merchant_url,
							"body"	=> '',
							"it_b_pay"	=> '',
							"extern_token"	=> '',
							"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
					);
					//print_r($parameter);exit;
					//建立请求
					$alipaySubmit = new AlipaySubmit($alipay_config);
					$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '提交');
					echo $html_text;
					exit;
				}
		 }else{
		      $this->printfail($jieqiLang['pay']['buy_type_error']);
		 }
    } 
	//支付宝返回结果
	function checkalipay($params = array(), $jieqiPayset = array()){
	    define('JIEQI_PAY_TYPE', 'alipay_wap');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		require_once($GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['path'].'/alipay/alipay.config.php');//print_r($alipay_config);exit();
		require_once($GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['path'].'/alipay/alipay_notify.class.php');//print_r($alipay_config);exit();
		
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		if($verify_result) {//验证成功
			//商户订单号
			$out_trade_no = $_GET['out_trade_no'];
		
			//支付宝交易号
			$trade_no = $_GET['trade_no'];
		
			//交易状态
			$trade_status = $_GET['trade_status'];
		
			if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
				$this->db->init( 'paylog', 'payid', 'pay' );
				$this->db->setCriteria(new Criteria( 'payid', $out_trade_no));
				$paylog=$this->db->get($this->db->criteria);
				if(is_object($paylog)){
					$buyname=$paylog->getVar('buyname');
					$buyid=$paylog->getVar('buyid');
					$payflag=$paylog->getVar('payflag');
					$egold=$paylog->getVar('egold');
					$money=$paylog->getVar('money');
					$users_handler =  $this->getUserObject();
					$auth = $this->getAuth();
					if($payflag == 0){
						$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold], $money, $money);
						if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
						else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
						$paylog->setVar('rettime', JIEQI_NOW_TIME);
						$paylog->setVar('note', $note);
						$paylog->setVar('retserialno', $trade_no);//支付宝交易号
						$paylog->setVar('payflag', 1);
						if(!$this->db->edit($out_trade_no, $paylog)){ 
							$this->printfail($jieqiLang['pay']['save_paylog_failure']);
						}else{
//							$this->msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
//							 return array(
//								  'msg'=>sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold)
//							 );
							 header('Location: '.$this->geturl(JIEQI_MODULE_NAME,'pay'));exit;
						}
					}else{
//						$this->msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
                         if($auth['uid']){//更新用户SESSIO，防止出现充值到账未显示的情况 
						     if($users = $users_handler->get($auth['uid'])){
								 $users->saveToSession();
							 }
						 }
//						 return array(
//							  'msg'=>sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold)
//						 );
						 header('Location: '.$this->geturl(JIEQI_MODULE_NAME,'pay'));exit;
					}
				}else{
					$this->printfail($jieqiLang['pay']['no_buy_record']);
				}
			}else{
				$this->printfail($jieqiLang['pay']['pay_failure_message']);
			}
		}else{
			$this->printfail($jieqiLang['pay']['return_checkcode_error']);
		}
	}
	function alipay_notify($params = array(), $jieqiPayset = array()){
		include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
		
	    define('JIEQI_PAY_TYPE', 'alipay_wap');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		require_once($GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['path'].'/alipay/alipay.config.php');//print_r($alipay_config);exit();
		require_once($GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['path'].'/alipay/alipay_notify.class.php');//print_r($alipay_config);exit();
		
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		if($verify_result) {//验证成功
		    //交易状态
		    $trade_status = $_POST['trade_status'];
			if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
			    //商户订单号
				$out_trade_no = $_POST['out_trade_no'];
				//支付宝交易号
				$trade_no = $_POST['trade_no'];
				$this->db->init( 'paylog', 'payid', 'pay' );
				$this->db->setCriteria(new Criteria( 'payid', $out_trade_no));
				$paylog=$this->db->get($this->db->criteria);
				if(is_object($paylog)){
					$buyname=$paylog->getVar('buyname');
					$buyid=$paylog->getVar('buyid');
					$payflag=$paylog->getVar('payflag');
					$egold=$paylog->getVar('egold');
					$money=$paylog->getVar('money');
					if($payflag == 0){
						$users_handler =  $this->getUserObject();
						$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold], $money, $money);
						if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
						else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
						$paylog->setVar('rettime', JIEQI_NOW_TIME);
						$paylog->setVar('note', $note);
						$paylog->setVar('retserialno', $trade_no);//支付宝交易号
						$paylog->setVar('payflag', 1);
						if(!$this->db->edit($out_trade_no, $paylog)){ 
							echo "fail";exit;
						}else{
							echo "success";	exit;
						}
					}else{
						echo "success";	exit;
					}
				}else{
					echo "fail";exit;
				}
			}
		}else{//验证失败
		    echo "fail";exit;
		}
	}
	/**
	 * 掌维
	 */
	public function zhangwei($params = array(), $jieqiPayset = array()) {
		 define('JIEQI_PAY_TYPE', 'zhangwei');
		 $this->addLang('pay', 'pay');
		 $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		 //判断代理
		 $user_is_agent = '';
		 if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
			 $user_is_agent = 'agent';
		 }
		 $params['egold']=intval($params['egold']);
		 if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
			  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
				  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])) $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
				  else $this->printfail($jieqiLang['pay']['buy_type_error']);
			  }else{
				  $this->printfail($jieqiLang['pay']['buy_type_error']);
			  }
			  $auth = $this->getAuth();
			  
			    $this->db->init( 'paylog', 'payid', 'pay' );
				$paylog['siteid'] = $GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['siteid'];
				$paylog['buytime'] = JIEQI_NOW_TIME;
				$paylog['rettime'] = 0;
				$paylog['buyid'] = $auth['uid'];
				$paylog['buyname'] = $auth['username'];
				$paylog['buyinfo'] = '';
				$paylog['moneytype'] = $jieqiPayset[JIEQI_PAY_TYPE]['moneytype'];
				$paylog['money'] = $money;
				$paylog['egoldtype'] = $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'];
				$paylog['egold'] = $params['egold'];
				$paylog['paytype'] = JIEQI_PAY_TYPE;
				$paylog['retserialno'] = '';
				$paylog['retaccount'] = '';
				$paylog['retinfo'] = $this->getip();
				$paylog['masterid'] = 0;
				$paylog['mastername'] = '';
				$paylog['masterinfo'] = '';
				$paylog['note'] = $user_is_agent;
				$paylog['payflag'] = 0;
				$paylog['source'] = $auth['source'];
				if(!$payid = $this->db->add($paylog)) $this->printfail($jieqiLang['pay']['add_paylog_error']);
				else{
					$urlvars=array();
					$urlvars['app_id'] = $jieqiPayset[JIEQI_PAY_TYPE]['app_id'];//商户编号 
					$urlvars['ptype'] = $jieqiPayset[JIEQI_PAY_TYPE]['pType']; //类型，固定值
					$urlvars['product_id']=$jieqiPayset[JIEQI_PAY_TYPE]['product_id'][$params['egold']]; //产品编码
					$urlvars['cm']=$jieqiPayset[JIEQI_PAY_TYPE]['cm']; //渠道代码
					$urlvars['amt']=$money/100; //金额 
					$urlvars['order_no']=$payid; //页面版式
					$urlvars['display']=2; //页面版式
					$urlvars['notify_url']=$jieqiPayset[JIEQI_PAY_TYPE]['Notify_Url']; //异步通知地址
					$urlvars['return_url']=$jieqiPayset[JIEQI_PAY_TYPE]['Return_Url']; //同步跳转地址
					ksort($urlvars);
					reset($urlvars);
					$sign='';
					$query='';
					foreach($urlvars as $k=>$v){
						if(!empty($sign)) $sign.='&';
						$sign.=$k.'='.$v;
						if(!empty($query)) $query.='&';
						$query.=$k.'='.urlencode($v);
					}//echo $sign.' '.$query;exit;
					$sign=md5($sign.'&key='.$jieqiPayset[JIEQI_PAY_TYPE]['access_key']);
					$query.='&sign='.$sign;
					$query=$jieqiPayset[JIEQI_PAY_TYPE]['payurl'].'?'.$query;
					header('Location: '.$query);
					
				}
		 }else{
		      $this->printfail($jieqiLang['pay']['buy_type_error']);
		 }
	}
	/**
	 * 掌维回调接口
	 */
	function checkzhangwei($params = array(), $jieqiPayset = array()){
	    define('JIEQI_PAY_TYPE', 'zhangwei');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		require_once($GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['path'].'/alipay/alipay_core.function.php');
		$para = $this->filter($_GET);
		$para = argSort($para);
		$prestr = createLinkstring($para);
//		$prestr = strtolower($prestr);
		$mysign = md5($prestr.'&key='.$jieqiPayset[JIEQI_PAY_TYPE]['secret_key']);//var_dump($mysign.' true'.$_GET['sign']);exit;
		if($mysign == $_GET['sign']){
			if($para['trade_status']=='TRADE_SUCCESS' || $para['trade_status']=='trade_success'){
				$this->db->init( 'paylog', 'payid', 'pay' );
				$orderid=(int)$para['order_no'];
				$this->db->setCriteria(new Criteria( 'payid', $orderid));
				$paylog=$this->db->get($this->db->criteria);
				if(is_object($paylog)){
					 $payflag=$paylog->getVar('payflag');
					 $egold=$paylog->getVar('egold');
					 $buyname=$paylog->getVar('buyname');
					 if($payflag == 0){
						 $money=$paylog->getVar('money');
						 $totalFee = ceil($money/100);
						 include_once(JIEQI_ROOT_PATH.'/class/users.php');
						 $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
						 $ret=$users_handler->income($paylog->getVar('buyid'), $paylog->getVar('egold'), $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], ceil($totalFee * 50), $money);
						 if($ret){
							 $paylog->setVar('rettime', JIEQI_NOW_TIME);
							 $paylog->setVar('payflag', 1);
							$paylog->setVar('buyinfo', $para['notify_id']);//通知校验ID
							$paylog->setVar('retserialno', $para['trade_no']);//对方交易订单号
							 if(!$this->db->edit($orderid, $paylog)){ 
								$this->printfail($jieqiLang['pay']['save_paylog_failure']);
							 }else{
//								 return array(
//									  'msg'=>sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold)
//								 );
								 header('Location: '.$this->geturl(JIEQI_MODULE_NAME,'pay'));exit;
							 }
						 }else{
						 	$this->printfail($jieqiLang['pay']['pay_failure_message']);
						 }
					 }else{
					     $users_handler =  $this->getUserObject();
					     $auth = $this->getAuth();
                         if($auth['uid']){//更新用户SESSIO，防止出现充值到账未显示的情况 
						     if($users = $users_handler->get($auth['uid'])){
								 $users->saveToSession();
							 }
						 }
//						 return array(
//							  'msg'=>sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold)
//						 );
						 header('Location: '.$this->geturl(JIEQI_MODULE_NAME,'pay'));exit;
					 }
				}else{
					$this->printfail($jieqiLang['pay']['no_buy_record']);
				}
			}else{
//				echo 111;die;
				$this->printfail($jieqiLang['pay']['pay_failure_message']);
			}
		}else{
			$this->printfail($jieqiLang['pay']['return_checkcode_error']);
		}
	}
	/**
	 * 掌维异步通知
	 */
	function zhangwei_notify($params = array(), $jieqiPayset = array()){
		//记录日志
		$this->db->init('userlog','logid','system');
		$data = array();
		$data['siteid'] = JIEQI_SITE_ID;
		$data['logtime'] = JIEQI_NOW_TIME;
		$data['fromid'] = $_SESSION['jieqiUserId'];
		$data['fromname'] = $_SESSION['jieqiUserName'];
		$data['toid'] = 0;
		$data['toname'] = '';
		$data['chginfo'] = "掌维异步通知。";
		$data['chglog'] = '';
		$data['isdel'] = '0';
		$data['userlog'] = '';
		$data['reason'] = 'appid='.$_POST['app_id'].'&notifyid='.$_POST['notify_id'].'&';
		$data['reason'] .= 'order_no='.$_POST['order_no'].'&trade_no='.$_POST['trade_no'].'&';
		$data['reason'] .= 'status='.$_POST['trade_status'].'&fee='.$_POST['trade_total'].'&';
		$data['reason'] .= 'timestamp='.$_POST['timestamp'].'&sign='.$_POST['sign'].'&';
		
		
	    define('JIEQI_PAY_TYPE', 'zhangwei');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		
		require_once($GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['path'].'/alipay/alipay_core.function.php');
		$para = $this->filter($_POST);//print_r($_POST);
		$para = argSort($para);
		
		$parastr = 'app_id='.$para['app_id'].'&notify_id='.$para['notify_id'];
		$query = $jieqiPayset[JIEQI_PAY_TYPE]['checkurl'].'?'.$parastr;
		$checksign = md5($parastr.'&key='.$jieqiPayset[JIEQI_PAY_TYPE]['access_key']);
		$query .= '&sign='.$checksign;
		$data['reason'] .= 'query='.$query.'&';
		$ret = jieqi_readfile($query);
//		$data['reason'] .= 'data='.$data.'&';
//		include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
//		
//		$ret = iconv('utf-8', 'gbk', $ret);//jieqi_utf82gb($ret);
//		$data['reason'] .= 'datagb='.$ret.'&';
//		$arr = json_decode($ret,true);//var_dump($arr);
//		$result = TRUE;
//		$result = $this->checkNotify();exit;
//		if($ret=='TRADE_SUCCESS'){
			$data['reason'] .= 'notify_id验证成功'.'&';
			$prestr = createLinkstring($para);
			$data['reason'] .= 'createLinkstring.'.$prestr.'&';
//			$prestr = strtolower($prestr);
			$data['reason'] .= 'strtolower.'.$prestr.'&';
			$mysign = md5($prestr.'&key='.$jieqiPayset[JIEQI_PAY_TYPE]['secret_key']);//echo $mysign.' '.$_POST['sign'];
			$data['reason'] .= 'mysign.'.$mysign.'&';
			if($mysign == $_POST['sign']){
				if($para['trade_status']=='trade_success' || $para['trade_status']=='TRADE_SUCCESS'){
					$this->db->init( 'paylog', 'payid', 'pay' );
					$orderid=(int)$para['order_no'];
					$this->db->setCriteria(new Criteria( 'payid', $orderid));
					$paylog=$this->db->get($this->db->criteria);
					if(is_object($paylog)){
						 $payflag=$paylog->getVar('payflag');
						 $egold=$paylog->getVar('egold');
						 $buyname=$paylog->getVar('buyname');
						 if($payflag == 0){
							 $money=$paylog->getVar('money');
							 $totalFee = ceil($money/100);
							 include_once(JIEQI_ROOT_PATH.'/class/users.php');
							 $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
							 $ret=$users_handler->income($paylog->getVar('buyid'), $paylog->getVar('egold'), $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], ceil($totalFee * 50), $money);
							 if($ret){
								 $paylog->setVar('rettime', JIEQI_NOW_TIME);
								 $paylog->setVar('payflag', 1);
								$paylog->setVar('buyinfo', $para['notify_id']);//通知校验ID
								$paylog->setVar('retserialno', $para['trade_no']);//对方交易订单号
								 if(!$this->db->edit($orderid, $paylog)){ 
									echo "editfail";
									 $data['reason'] .= 'editfail'.'&';
								 }else{
									echo "success";exit;
									$data['reason'] .= 'success'.'&';
								 }
							 }else{
							 	$data['reason'] .= 'incomefail'.'&';
								echo "incomefail";
							 }
						 }else{
						 	$data['reason'] .= 'payflag=1'.'&';
							echo "success";exit;
						 }
					}else{
						echo 'no_order';
						$data['reason'] .= 'order_no验证失败'.'&';
					}					
				}else{
					echo 'trade_fail';
					$data['reason'] .= 'trade_fail验证失败'.'&';
				}
			}else{
				echo 'sign_error';
				$data['reason'] .= 'sign验证失败'.'&';
			}
//		}else{
//			echo 'notify_error';
//			$data['reason'] .= 'notify_id验证失败'.'&';
//		}
		$this->db->add($data);
	}

	/**
	 * 掌维：验证异步通知是否有效
	 */
	function checkNotify(){
//		$checkurl ='http://gateway.zw88.net/v1/Pay/Verify';
//		$app_id = '13';
//		$notify_id = '201';
////		$query = $checkurl.'?app_id='.$app_id.'&notify_id='.$notify_id.'&sign=df8d7c910f727611ef68d4a819f5b16f';
//		$query = 'http://gateway.zw88.net/v1/Pay/Verify?app_id=13&notify_id=201&sign=df8d7c910f727611ef68d4a819f5b16f';
//		$data = jieqi_readfile($query);//echo $result;
//		include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
//		
//					$data = jieqi_utf82gb($data);echo '   '.$data;
//					$arr = json_decode($data,true);print_r($arr);
	}
	private function filter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $key == "sign_type" || $key == "controller" || $key == "method")continue;
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}
	//易宝一键支付：银行卡
    function yeepay($params = array(), $jieqiPayset = array()){
	     define('JIEQI_PAY_TYPE', 'yeepay_wap');
		 $this->addLang('pay', 'pay');
		 $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		 //判断代理
		 $user_is_agent = '';
		 if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
		 	 $user_is_agent = 'agent';
		 }
		 $params['egold']=intval($params['egold']);
		 if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
		  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
			  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])) $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
			  else $this->printfail($jieqiLang['pay']['buy_type_error']);
		  }else{
			  $this->printfail($jieqiLang['pay']['buy_type_error']);
		  }
			  $auth = $this->getAuth();
			  
			    $this->db->init( 'paylog', 'payid', 'pay' );
				$paylog['siteid'] = $GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['siteid'];
				$paylog['buytime'] = JIEQI_NOW_TIME;
				$paylog['rettime'] = 0;
				$paylog['buyid'] = $auth['uid'];
				$paylog['buyname'] = $auth['username'];
				$paylog['buyinfo'] = '';
				$paylog['moneytype'] = $jieqiPayset[JIEQI_PAY_TYPE]['moneytype'];
				$paylog['money'] = $money;
				$paylog['egoldtype'] = $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'];
				$paylog['egold'] = $params['egold'];
				$paylog['paytype'] = JIEQI_PAY_TYPE;
				$paylog['retserialno'] = '';
				$paylog['retaccount'] = '';
				$paylog['retinfo'] = $this->getip();
				$paylog['masterid'] = 0;
				$paylog['mastername'] = '';
				$paylog['masterinfo'] = '';
				$paylog['note'] = $user_is_agent;
				$paylog['payflag'] = 0;
				$paylog['source'] = $auth['source'];
				if(!$payid = $this->db->add($paylog)) $this->printfail($jieqiLang['pay']['add_paylog_error']);
				else{
					include('yeepay/yeepayMPay.php');
					include('yeepay/config.php');
					
					$yeepay = new yeepayMPay($merchantaccount,$merchantPublicKey,$merchantPrivateKey,$yeepayPublicKey);
//					$order_id = $this->create_str(15);
					$order_id = 'shuhai'.$payid;//网页支付的订单

					$transtime = time();//交易时间，是每次支付请求的时间
					$product_catalog ='1';//商品类编码
					$identity_id = $auth['uid'];//用户身份标识
					$identity_type = 2;     //支付身份标识类型码
					$user_ip = $paylog['retinfo']; //用户每次支付时使用的网络终端IP
					$user_ua = $_SERVER['HTTP_USER_AGENT'];//用户ua
					$callbackurl =$jieqiPayset[JIEQI_PAY_TYPE]['callbackurl'];//商户后台系统回调地址
					$fcallbackurl =$jieqiPayset[JIEQI_PAY_TYPE]['fcallbackurl'];//商户前台系统回调地址
					include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
					$product_name = jieqi_gb2utf8($jieqiPayset[JIEQI_PAY_TYPE]['subject'][$params['egold']]);//出于风控考虑，请按下面的格式传递值：应用-商品名称，如“诛仙-3 阶成品天琊”
					$terminaltype = 3;
					$terminalid = '05-16-DC-59-C2-34';//其他支付身份信息
					$amount =$money;//订单金额单位为分，支付时最低金额为2分，因为测试和生产环境的商户都有手续费（如2%），易宝支付收取手续费如果不满1分钱将按照1分钱收取。
					//$idcardtype='01';
					//$idcard = '';
					$version = '0';
					//$owner = jieqi_gb2utf8('张三');	     
					$url = $yeepay->webPay($order_id,$transtime,$amount,$product_catalog,$identity_id,$identity_type,$user_ip,$user_ua,$callbackurl,$fcallbackurl,$product_name,$terminaltype,$terminalid,$version);
					header('Location: '.$url);
				}
		 }else{
		      $this->printfail($jieqiLang['pay']['buy_type_error']);
		 }
	}
function create_str( $length = 8 ) {  
// 密码字符集，可任意添加你需要的字符  
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';  
$str = '';  
for ( $i = 0; $i < $length; $i++ )  
{  
// 这里提供两种字符获取方式  
// 第一种是使用 substr 截取$chars中的任意一位字符；  
// 第二种是取字符数组 $chars 的任意元素  
// $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);  
$str .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
}  
return $str;  
}
	//网银返回结果
	function checkyeepay($params = array(), $jieqiPayset = array()){
	    define('JIEQI_PAY_TYPE', 'yeepay_wap');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值		

		include('yeepay/yeepayMPay.php');
		include('yeepay/config.php');
		/**
		*此类文件是有关回调的数据处理文件，根据易宝回调进行数据处理
		
		*/
		$yeepay = new yeepayMPay($merchantaccount, $merchantPublicKey, $merchantPrivateKey, $yeepayPublicKey);
		$return = $yeepay->callback($_REQUEST['data'], $_REQUEST['encryptkey'], $dat['reason']);//
//		print_r($return);//echo $return;
		if($return){
			if($yeepay->RSAVerify($return, $return['sign'])){//print_r($return);
				if($return['status']==1){
					$this->db->init( 'paylog', 'payid', 'pay' );//echo str_replace('shuhai','',$return['orderid']);
//					$orderid=(int)str_replace('shuhai','',$return['orderid']);
					$orderid=str_replace('shuhai','',$return['orderid']);//echo $orderid;
					$this->db->setCriteria(new Criteria( 'payid', $orderid));
					$paylog=$this->db->get($this->db->criteria);
					if(is_object($paylog)){
						 $payflag=$paylog->getVar('payflag');//echo $payflag;
						 $egold=$paylog->getVar('egold');
						 $buyname=$paylog->getVar('buyname');
						 if($payflag == 0){
							 $money=$paylog->getVar('money');
							 $totalFee = ceil($money/100);
							 include_once(JIEQI_ROOT_PATH.'/class/users.php');
							 $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
							 $ret=$users_handler->income($paylog->getVar('buyid'), $paylog->getVar('egold'), $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], ceil($totalFee * 50), $money, $money);
							 if($ret){
								 $paylog->setVar('rettime', JIEQI_NOW_TIME);
								 $paylog->setVar('payflag', 1);
								 include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
								$paylog->setVar('buyinfo', 'cardtype:'.$return['cardtype'].'&bankcode:'.$return['bankcode'].'&bank:'.jieqi_utf82gb($return['bank']).'&lastno:'.$return['lastno']);//通知校验ID
								$paylog->setVar('retserialno', $return['yborderid']);//对方交易订单号
								 if(!$this->db->edit($orderid, $paylog)){ 
									$this->printfail($jieqiLang['pay']['save_paylog_failure']);
								 }else{
//									 return array(
//										  'msg'=>sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold)
//									 );
									 header('Location: '.$this->geturl(JIEQI_MODULE_NAME,'pay'));exit;
								 }
							 }else{
							 	$this->printfail($jieqiLang['pay']['pay_failure_message']);
							 }
						 }else{
//							 return array(
//								  'msg'=>sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold)
//							 );
							 header('Location: '.$this->geturl(JIEQI_MODULE_NAME,'pay'));exit;
						 }
					}else{
						$this->printfail($jieqiLang['pay']['no_buy_record']);
					}
				}else{
					$this->printfail($jieqiLang['pay']['pay_failure_message']);
				}				
			}else{
				$this->printfail($jieqiLang['pay']['return_checkcode_error']);
			}

		}else{
			$this->printfail($jieqiLang['pay']['return_checkcode_error']);
		}
	}
	/**
	 * 易宝一键支付：异步通知接口
	 */
	function yeepay_notify($params = array(), $jieqiPayset = array()){
	    define('JIEQI_PAY_TYPE', 'yeepay_wap');
		$retMsg='';
		//记录日志
		$this->db->init('userlog','logid','system');
		$dat = array();
		$dat['siteid'] = JIEQI_SITE_ID;
		$dat['logtime'] = JIEQI_NOW_TIME;
		$dat['fromid'] = $_SESSION['jieqiUserId'];
		$dat['fromname'] = $_SESSION['jieqiUserName'];
		$dat['toid'] = 0;
		$dat['toname'] = '';
		$dat['chginfo'] = "易宝一键测试。";
		$dat['chglog'] = '';
		$dat['isdel'] = '0';
		$dat['userlog'] = '';
		$dat['reason'] = 'post::';
		foreach($_REQUEST as $k=>$v){
			$dat['reason'] .= $k.'='.$v.'&';
		}
		$dat['reason'] .= 'return::';
		include('yeepay/yeepayMPay.php');
		include('yeepay/config.php');
		/**
		*此类文件是有关回调的数据处理文件，根据易宝回调进行数据处理
		
		*/
		$yeepay = new yeepayMPay($merchantaccount, $merchantPublicKey, $merchantPrivateKey, $yeepayPublicKey);
		$return = $yeepay->callback($_REQUEST['data'], $_REQUEST['encryptkey'], $dat['reason']);//
//		print_r($return);//echo $return;
		if($return){
			foreach($return as $k=>$v){
				$dat['reason'] .= $k.'='.$v.'&';
			}
			if($yeepay->RSAVerify($return, $return['sign'])){//print_r($return);
				if($return['status']==1){
					$this->db->init( 'paylog', 'payid', 'pay' );//echo str_replace('shuhai','',$return['orderid']);
//					$orderid=(int)str_replace('shuhai','',$return['orderid']);
					$orderid=str_replace('shuhai','',$return['orderid']);//echo $orderid;
					$this->db->setCriteria(new Criteria( 'payid', $orderid));
					$paylog=$this->db->get($this->db->criteria);
					if(is_object($paylog)){
						 $payflag=$paylog->getVar('payflag');//echo $payflag;
						 $egold=$paylog->getVar('egold');
						 $buyname=$paylog->getVar('buyname');
						 if($payflag == 0){
							 $money=$paylog->getVar('money');
							 $totalFee = ceil($money/100);
							 include_once(JIEQI_ROOT_PATH.'/class/users.php');
							 $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
							 $ret=$users_handler->income($paylog->getVar('buyid'), $paylog->getVar('egold'), $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], ceil($totalFee * 50), $money, $money);
							 if($ret){
								 $paylog->setVar('rettime', JIEQI_NOW_TIME);
								 $paylog->setVar('payflag', 1);
								 include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
								$paylog->setVar('buyinfo', 'cardtype:'.$return['cardtype'].'&bankcode:'.$return['bankcode'].'&bank:'.jieqi_utf82gb($return['bank']).'&lastno:'.$return['lastno']);//通知校验ID
								$paylog->setVar('retserialno', $return['yborderid']);//对方交易订单号
								 if(!$this->db->edit($orderid, $paylog)){ 
									$retMsg='editfail';	//充值成功，修改记录失败
								 }else{
									 $retMsg='success';	//交易成功
								 }
							 }else{
							 	$retMsg='incomefail';	//交易成功
							 }
						 }else{
							 $retMsg = 'success';	//重复提交
						 }
					}else{
						$retMsg = 'ordernull';	//无此交易记录
					}
				}else{
					$retMsg = 'pay_status_fail';	//支付未成功
				}				
			}else{
				$retMsg = 'sign_error';	//参数错误
			}
		}else{
			$retMsg = 'decrypt_error';	//参数错误
		}
		$this->db->add($dat);
		exit($retMsg);
	}
	/**
	 * 星启天 微信
	 */
	function wechat($params = array(), $jieqiPayset = array()){//print_r($params);exit;
	     define('JIEQI_PAY_TYPE', 'wechat_wap');
		 $this->addLang('pay', 'pay');
		 $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		 //判断代理
		 $user_is_agent = '';
		 if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
		 	 $user_is_agent = 'agent';
		 }
		 $params['egold']=intval($params['egold']);
		 if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
		  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
			  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])) $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
			  else $this->printfail($jieqiLang['pay']['buy_type_error']);
		  }else{
			  $this->printfail($jieqiLang['pay']['buy_type_error']);
		  }
			  $auth = $this->getAuth();
			  
			    $this->db->init( 'paylog', 'payid', 'pay' );
				$paylog['siteid'] = $GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['siteid'];
				$paylog['buytime'] = JIEQI_NOW_TIME;
				$paylog['rettime'] = 0;
				$paylog['buyid'] = $auth['uid'];
				$paylog['buyname'] = $auth['username'];
				$paylog['buyinfo'] = '';
				$paylog['moneytype'] = $jieqiPayset[JIEQI_PAY_TYPE]['moneytype'];
				$paylog['money'] = $money;
				$paylog['egoldtype'] = $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'];
				$paylog['egold'] = $params['egold'];
				$paylog['paytype'] = JIEQI_PAY_TYPE;
				$paylog['retserialno'] = '';
				$paylog['retaccount'] = '';
				$paylog['retinfo'] = $this->getip();
				$paylog['masterid'] = 0;
				$paylog['mastername'] = '';
				$paylog['masterinfo'] = '';
				$paylog['note'] = $user_is_agent;
				$paylog['payflag'] = 0;
				$paylog['source'] = $auth['source'];
				if(!$payid = $this->db->add($paylog)) $this->printfail($jieqiLang['pay']['add_paylog_error']);
				else{
					$urlvars=array();
					$urlvars['customerid']	= $jieqiPayset[JIEQI_PAY_TYPE]['customerid'];
					$urlvars['sdcustomno']	= $payid;
					$urlvars['orderAmount']	= $money;
					$urlvars['cardno']	= $jieqiPayset[JIEQI_PAY_TYPE]['cardno'];
					$urlvars['noticeurl']	= $jieqiPayset[JIEQI_PAY_TYPE]['noticeurl'];
					$urlvars['backurl']	= $jieqiPayset[JIEQI_PAY_TYPE]['backurl']; //回调地址无效，但参数不能为空
					$key = $jieqiPayset[JIEQI_PAY_TYPE]['key'];
					$mark= '3g';
					$remarks= $jieqiPayset[JIEQI_PAY_TYPE]['remarks'][$params['egold']];
					
					$query='';
					foreach($urlvars as $k=>$v){
						if(!empty($query)) $query.='&';
						$query.=$k.'='.$v;
					}
					$sign = strtoupper(md5($query.$key));
					
					$url = $jieqiPayset[JIEQI_PAY_TYPE]['payurl'].'?'.$query.'&sign='.$sign.'&mark='.$mark.'&remarks='.$remarks;
					//echo $url;exit;
					header('Location: '.$url);
				}
		 }else{
		      $this->printfail($jieqiLang['pay']['buy_type_error']);
		 }
	}
	/**
	 * 星启天 微信：异步通知
	 */
	function wechat_notify($params = array(), $jieqiPayset = array()){
	    define('JIEQI_PAY_TYPE', 'wechat_wap');
		$retMsg='';
		//记录日志
		$this->db->init('userlog','logid','system');
		$dat = array();
		$dat['siteid'] = JIEQI_SITE_ID;
		$dat['logtime'] = JIEQI_NOW_TIME;
		$dat['fromid'] = $_SESSION['jieqiUserId'];
		$dat['fromname'] = $_SESSION['jieqiUserName'];
		$dat['toid'] = 0;
		$dat['toname'] = '';
		$dat['chginfo'] = "星启天微信测试。";
		$dat['chglog'] = '';
		$dat['isdel'] = '0';
		$dat['userlog'] = '';
		$dat['reason'] = 'get::';
		foreach($_GET as $k=>$v){
			$dat['reason'] .= $k.'='.$v.'&';
		}
	
		$state=trim($_GET["state"]);            // 1:充值成功 2:充值失败
		$customerid=trim($_GET["customerid"]);	//商户注册的时候，网关自动分配的商户ID
		$sd51no=trim($_GET["sd51no"]);          //该订单在网关系统的订单号
		$sdcustomno=trim($_GET["sdcustomno"]);  //该订单在商户系统的流水号
		$ordermoney=trim($_GET["ordermoney"]);  //商户订单实际金额单位：（元）
		$cardno=trim($_GET["cardno"]);          //支付类型，为固定值 32
		$mark=trim($_GET["mark"]);              //未启用暂时返回空值
		$sign=trim($_GET["sign"]);              //发送给商户的签名字符串
		$resign=trim($_GET["resign"]);          //发送给商户的签名字符串
		$des=trim($_GET["des"]);                //描述订单支付成功或失败的系统备注
		
		$key=$jieqiPayset[JIEQI_PAY_TYPE]['key'];  //key可从星启天网关客服处获取
		$sign2=strtoupper(md5("customerid=".$customerid."&sd51no=".$sd51no."&sdcustomno=".$sdcustomno."&mark=".$mark."&key=".$key));
		$resign2=strtoupper(md5("sign=".$sign."&customerid=".$customerid."&ordermoney=".$ordermoney."&sd51no=".$sd51no."&state=".$state."&key=".$key));
		
		if($sign==$sign2 && $resign==$resign2){
			if($state==1){
				$this->db->init( 'paylog', 'payid', 'pay' );
				$this->db->setCriteria(new Criteria( 'payid', $sdcustomno));
				$paylog=$this->db->get($this->db->criteria);
				if(is_object($paylog)){
					 $payflag=$paylog->getVar('payflag');//echo $payflag;
					 $egold=$paylog->getVar('egold');
					 $buyname=$paylog->getVar('buyname');
					 if($payflag == 0){
						 $money=$paylog->getVar('money');
						 $totalFee = ceil($money/100);
						 include_once(JIEQI_ROOT_PATH.'/class/users.php');
						 $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
						 $ret=$users_handler->income($paylog->getVar('buyid'), $paylog->getVar('egold'), $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], ceil($totalFee * 50), $money, $money);
						 if($ret){
							 $paylog->setVar('rettime', JIEQI_NOW_TIME);
							 $paylog->setVar('payflag', 1);
							$paylog->setVar('buyinfo', 'des:'.$des.'&mark:'.$mark);//备注
							$paylog->setVar('retserialno', $sd51no);//对方交易订单号
							 if(!$this->db->edit($sdcustomno, $paylog)){ 
								$retMsg='0';	//充值成功，修改记录失败
							 }else{
								 $retMsg='1';	//交易成功
							 }
						 }else{
						 	$retMsg='0';	//交易成功
						 }
					 }else{
						 $retMsg = '1';	//重复提交
					 }
				}else{
					$retMsg = '0';	//无此交易记录
				}
			}else{$this->db->add($dat);
				$retMsg = '0';	//支付未成功
			}				
		}else{$this->db->add($dat);
			$retMsg = '0';	//参数错误
		}
		exit('<result>'.$retMsg.'</result>');
	}
	/**
	 * 微信 同步：
	 * 不带参数，单纯跳转，没有加币功能
	 */
	function checkwechat($params = array(), $jieqiPayset = array()){//print_r($params);exit;
	    define('JIEQI_PAY_TYPE', 'wechat_wap');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值		
	
//		$state=trim($_GET["state"]);            // 1:充值成功 2:充值失败
//		$customerid=trim($_GET["customerid"]);	//商户注册的时候，网关自动分配的商户ID
//		$sd51no=trim($_GET["sd51no"]);          //该订单在网关系统的订单号
//		$sdcustomno=trim($_GET["sdcustomno"]);  //该订单在商户系统的流水号
//		$ordermoney=trim($_GET["ordermoney"]);  //商户订单实际金额单位：（元）
//		$cardno=trim($_GET["cardno"]);          //支付类型，为固定值 32
//		$mark=trim($_GET["mark"]);              //未启用暂时返回空值
//		$sign=trim($_GET["sign"]);              //发送给商户的签名字符串
//		$resign=trim($_GET["resign"]);          //发送给商户的签名字符串
//		$des=trim($_GET["des"]);                //描述订单支付成功或失败的系统备注
//		
//		$key=$jieqiPayset[JIEQI_PAY_TYPE]['key'];  //key可从星启天网关客服处获取
//		$sign2=strtoupper(md5("customerid=".$customerid."&sd51no=".$sd51no."&sdcustomno=".$sdcustomno."&mark=".$mark."&key=".$key));
//		$resign2=strtoupper(md5("sign=".$sign."&customerid=".$customerid."&ordermoney=".$ordermoney."&sd51no=".$sd51no."&state=".$state."&key=".$key));
//		
//		if($sign==$sign2 && $resign==$resign2){
//			if($state==1){
//				$this->db->init( 'paylog', 'payid', 'pay' );
//				$this->db->setCriteria(new Criteria( 'payid', $sdcustomno));
//				$paylog=$this->db->get($this->db->criteria);
//				if(is_object($paylog)){
//					 $payflag=$paylog->getVar('payflag');//echo $payflag;
//					 $egold=$paylog->getVar('egold');
//					 $buyname=$paylog->getVar('buyname');
//					 if($payflag == 0){
//						 $money=$paylog->getVar('money');
//						 $totalFee = ceil($money/100);
//						 include_once(JIEQI_ROOT_PATH.'/class/users.php');
//						 $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
//						 $ret=$users_handler->income($paylog->getVar('buyid'), $paylog->getVar('egold'), $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], ceil($totalFee * 50), $money, $money);
//						 if($ret){
//							 $paylog->setVar('rettime', JIEQI_NOW_TIME);
//							 $paylog->setVar('payflag', 1);
//							 $paylog->setVar('buyinfo', 'des:'.$des.'&mark:'.$mark);//备注
// 							$paylog->setVar('retserialno', $sd51no);//对方交易订单号
//							 if(!$this->db->edit($sdcustomno, $paylog)){ 
//								$this->printfail($jieqiLang['pay']['save_paylog_failure']);
//							 }else{
//								 header('Location: '.$this->geturl(JIEQI_MODULE_NAME,'pay'));exit;
//							 }
//						 }else{
//						 	$this->printfail($jieqiLang['pay']['pay_failure_message']);
//						 }
//					 }else{
					     $users_handler =  $this->getUserObject();
					     $auth = $this->getAuth();
                         if($auth['uid']){//更新用户SESSIO，防止出现充值到账未显示的情况 
						     if($users = $users_handler->get($auth['uid'])){
								 $users->saveToSession();
							 }
						 }
						 return array(
						 	'title'=>1,
						 	'msg'=>'请进入个人中心查看充值记录。<br />如果余额不变，请尝试重新登录。'
//							'msg'=>sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold)
						 );
//						 header('Location: '.$this->geturl(JIEQI_MODULE_NAME,'userhub','SYS=method=czView'));exit;
//						 header('Location: '.$this->geturl(JIEQI_MODULE_NAME,'pay'));exit;
//					 }
//				}else{
//					$this->printfail($jieqiLang['pay']['no_buy_record']);
//				}
//			}else{
//				$this->printfail($jieqiLang['pay']['pay_failure_message']);
//			}				
//		}else{
//			$this->printfail($jieqiLang['pay']['return_checkcode_error']);
//		}
	}
}

?>