<?php 
/** 
 * 冲值模型 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class homeModel extends Model{ 
		//支付宝
        function alipay($params = array(), $jieqiPayset = array()){ 
		     define('JIEQI_PAY_TYPE', 'alipay');
			 $this->addLang('pay', 'pay');
			 $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
			 //判断代理
			 $user_is_agent = '';
			 if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
			 	 $user_is_agent = 'agent';
			 }
			 $params['egold']=intval($params['egold']);
			 if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
				  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])&&!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])){
					  $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
					  //else $this->printfail($jieqiLang['pay']['buy_type_error']);
				  }else{
				      if(intval($params['money_yuan'])<1) $this->printfail($jieqiLang['pay']['buy_type_error']);
				      if(in_array($params['money_yuan'], $jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
					      foreach($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'] as $k=>$v){
						      if($params['money_yuan']==$v){
							       $money=intval($params['money_yuan'] * 100);
								   $params['egold'] = $k;
							  }
						  }
					  }else{
						  $money = intval($params['money_yuan'])*100;
						  $params['egold'] = $money;
					  }
				  }
				  $auth = $this->getAuth();
				  
				    $this->db->init( 'paylog', 'payid', 'pay' );
					$paylog['siteid'] = JIEQI_SITE_ID;
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
						$urlvars['service']=$jieqiPayset[JIEQI_PAY_TYPE]['service']; //交易类型
						$urlvars['partner']=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //合作商户号
						//$urlvars['agent']=$jieqiPayset[JIEQI_PAY_TYPE]['agent']; //代理商id
						$urlvars['return_url']=$jieqiPayset[JIEQI_PAY_TYPE]['payreturn'];  //同步返回
						$urlvars['notify_url']=$jieqiPayset[JIEQI_PAY_TYPE]['notify_url'];  //异步返回
						$urlvars['_input_charset']=$jieqiPayset[JIEQI_PAY_TYPE]['_input_charset'];  //字符集，默认为GBK
						
						$urlvars['subject']= JIEQI_EGOLD_NAME;  //商品名称，必填
						//$urlvars['body']= $jieqiPayset[JIEQI_PAY_TYPE]['body']; //商品描述
						$urlvars['out_trade_no']=$payid; //商品外部交易号，必填,每次测试都须修改
						$urlvars['total_fee']=$money / 100;          //商品总价
						//$price=$total_fee; //商品单价
						//$quantity=1; //商品数量
						$urlvars['payment_type']=$jieqiPayset[JIEQI_PAY_TYPE]['payment_type']; // 商品支付类型 1 ＝商品购买 2＝服务购买 3＝网络拍卖 4＝捐赠 5＝邮费补偿 6＝奖金
						$urlvars['show_url']=$jieqiPayset[JIEQI_PAY_TYPE]['show_url'];  //商品相关网站公司
						$urlvars['seller_email']=$jieqiPayset[JIEQI_PAY_TYPE]['seller_email'];   //卖家邮箱，必填
						ksort($urlvars);
						reset($urlvars);
						$sign='';
						$query='';
						foreach($urlvars as $k=>$v){
							if(!empty($sign)) $sign.='&';
							$sign.=$k.'='.$v;
							if(!empty($query)) $query.='&';
							$query.=$k.'='.urlencode($v);
						}
						$sign=md5($sign.$jieqiPayset[JIEQI_PAY_TYPE]['paykey']);
						$query.='&sign_type='.$jieqiPayset[JIEQI_PAY_TYPE]['sign_type'].'&sign='.$sign;
						$query=$jieqiPayset['alipay']['payurl'].'?'.$query;
						header('Location: '.$query);
						exit;
					}
			 }else{
			      $this->printfail($jieqiLang['pay']['buy_type_error']);
			 }
        } 
		//支付宝返回结果
		function checkalipay($params = array(), $jieqiPayset = array()){
		    define('JIEQI_PAY_TYPE', 'alipay');
			$this->addLang('pay', 'pay');
			$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
			$mymerchant_id=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号
			$key=$jieqiPayset[JIEQI_PAY_TYPE]['paykey']; //密钥
			
			$logflag = 0; //是否记录日志
			if($logflag){
				ob_start();
				print_r($_REQUEST);
				$log = ob_get_contents();
				ob_end_clean();
				jieqi_writefile(JIEQI_ROOT_PATH.'/cache/alipayrecv.txt',$log,'ab');
			}
			
			if(!empty($_GET['notify_id']) && !empty($_GET['buyer_email']) && !empty($_GET['out_trade_no'])){
				//直接返回模式
				$getvars=$_GET;
				$showmode=1;
			}elseif(!empty($_POST['notify_id']) && !empty($_POST['buyer_email']) && !empty($_POST['out_trade_no'])){
				//异步返回模式
				$getvars=$_POST;
				$showmode=0;
				echo 'success';
			}else{
				echo 'fail';
				exit;
			}
			
			//检查交易状态(是不是付款成功)
			if(strtoupper($getvars['trade_status']) != 'TRADE_FINISHED' && $getvars['trade_status'] != 'TRADE_SUCCESS'){
				if($showmode) $this->printfail($jieqiLang['pay']['pay_return_error'].'<br /><br />RETCODE:'.$getvars['trade_status']);
				else exit;
			}
			
			//通知校验
			if($logflag){
				$checkurl=$jieqiPayset[JIEQI_PAY_TYPE]['notifycheck'].'?msg_id='.urlencode($getvars['notify_id']).'&email='.urlencode($getvars['buyer_email']).'&order_no='.urlencode($getvars['out_trade_no']);
				$checkret=strtolower(file_get_contents($checkurl));  //success or failure
				$log=$checkurl.'['.$checkret.']';
				jieqi_writefile(JIEQI_ROOT_PATH.'/cache/alipaycheck.txt',$log,'ab');
			}
			//md5校验
			ksort($getvars);
			reset($getvars);
			$signtext='';
			$signdecode='';
			foreach($getvars as $k=>$v){
				if($k != 'sign' && $k != 'sign_type' && $k != 'controller' && $k != 'method'){
					if(!empty($signtext)){
						$signtext.='&';
						$signdecode.='&';
					}
					$signtext.=$k.'='.$v;
					$signdecode.=$k.'='.urldecode($v);
				}
			}
			if(strtolower($getvars['sign']) == strtolower(md5($signtext.$jieqiPayset[JIEQI_PAY_TYPE]['paykey'])) || strtolower($getvars['sign']) == strtolower(md5($signdecode.$jieqiPayset[JIEQI_PAY_TYPE]['paykey']))){
				$orderid=intval($getvars['out_trade_no']);
				$this->db->init( 'paylog', 'payid', 'pay' );
				$this->db->setCriteria(new Criteria( 'payid', $orderid, '=' ));
				$paylog=$this->db->get($this->db->criteria);
				if(is_object($paylog)){
					$buyname=$paylog->getVar('buyname');
					$buyid=$paylog->getVar('buyid');
					$payflag=$paylog->getVar('payflag');
					$egold=$paylog->getVar('egold');
					$money=$paylog->getVar('money');
					if($payflag == 0){
						$users_handler =  $this->getUserObject();
						if(!isset($jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold])) $payscore=$money/2;
						else $payscore=$jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold];
						
						$package =  $this->load('rechargetask','task');//加载充值任务类
						$package->judge($buyid, $buyname, $money);//判断是否满赠
						
						$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $payscore, $money, $money);
						if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
						else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
						$paylog->setVar('rettime', JIEQI_NOW_TIME);
						$paylog->setVar('note', $note);
						$paylog->setVar('payflag', 1);
						if(!$this->db->edit($orderid, $paylog)){ 
							if($showmode) $this->printfail($jieqiLang['pay']['save_paylog_failure']);
						}else{
							if($showmode) $this->jumppage($this->geturl('system','userhub','SYS=method=czView'),LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
						}
					}else{
						if($showmode) $this->jumppage($this->geturl('system','userhub','SYS=method=czView'),LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
					}
				}else{
					if($showmode) $this->printfail($jieqiLang['pay']['no_buy_record']);
				}
			}else{
				if($showmode) $this->printfail($jieqiLang['pay']['return_checkcode_error']);
			}
		}
		//网银
        function yeepay($params = array(), $jieqiPayset = array()){
		     define('JIEQI_PAY_TYPE', 'yeepay');
			 include_once($GLOBALS['jieqiModules']['pay']['path'].'/function/yeepaycommon.php'); //易宝支付接口公共函数
			 $this->addLang('pay', 'pay');
			 $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
			 //判断代理
			 $user_is_agent = '';
			 if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
			 	 $user_is_agent = 'agent';
			 }
			 $params['egold']=intval($params['egold']);
			 if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
				  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])&&!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])){
					  $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
					  //else $this->printfail($jieqiLang['pay']['buy_type_error']);
				  }else{
				      if(intval($params['money_yuan'])<1) $this->printfail($jieqiLang['pay']['buy_type_error']);
				      if(in_array($params['money_yuan'], $jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
					      foreach($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'] as $k=>$v){
						      if($params['money_yuan']==$v){
							       $money=intval($params['money_yuan'] * 100);
								   $params['egold'] = $k;
							  }
						  }
					  }else{
						  $money = intval($params['money_yuan'])*100;
						  $params['egold'] = $money;
					  }
				  }
				  $auth = $this->getAuth();
				  
				    $this->db->init( 'paylog', 'payid', 'pay' );
					$paylog['siteid'] = JIEQI_SITE_ID;
					$paylog['buytime'] = JIEQI_NOW_TIME;
					$paylog['rettime'] = 0;
					$paylog['buyid'] = $auth['uid'];
					$paylog['buyname'] = $auth['username'];
					$paylog['buyinfo'] = '';
					$paylog['moneytype'] = $jieqiPayset[JIEQI_PAY_TYPE]['moneytype'];
					$paylog['money'] = $money;
					$paylog['egoldtype'] = $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'];
					$paylog['egold'] = $params['egold'];
					if(!empty($_POST['pd_FrpId']) && isset($jieqiPayset[JIEQI_PAY_TYPE]['paytype'][$_POST['pd_FrpId']])) $paytype=$jieqiPayset[JIEQI_PAY_TYPE]['paytype'][$_POST['pd_FrpId']];
					else $paytype='yeepay-bank';
					$paylog['paytype'] = $paytype;
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
						$amount=$money / 100;
						$merchantId = $jieqiPayset[JIEQI_PAY_TYPE]['payid'];  //商户编号
						$orderId = $payid;     //订单编号[商户网站]
						$cur = $jieqiPayset[JIEQI_PAY_TYPE]['cur'];    //货币单位CNY
						$productId = empty($jieqiPayset[JIEQI_PAY_TYPE]['productId']) ? JIEQI_EGOLD_NAME : $jieqiPayset[JIEQI_PAY_TYPE]['productId'];    //商品名称
						$productCat = $jieqiPayset[JIEQI_PAY_TYPE]['productCat'];    //
						$productDesc = $jieqiPayset[JIEQI_PAY_TYPE]['productDesc'];    //
						$sMctProperties = $jieqiPayset[JIEQI_PAY_TYPE]['sMctProperties'];    //
						$frpId = trim($_POST['pd_FrpId']) != '' ? trim($_POST['pd_FrpId']) : $jieqiPayset[JIEQI_PAY_TYPE]['frpId'];    //
						$needResponse = $jieqiPayset[JIEQI_PAY_TYPE]['needResponse'];    //
						$nodeAuthorizationURL = $jieqiPayset[JIEQI_PAY_TYPE]['payurl'];    //
						$merchantCallbackURL = $jieqiPayset[JIEQI_PAY_TYPE]['payreturn'];    //
						$messageType = $jieqiPayset[JIEQI_PAY_TYPE]['messageType'];    //
						$addressFlag = $jieqiPayset[JIEQI_PAY_TYPE]['addressFlag'];    //
				
						//$merchant_url = $jieqiPayset[JIEQI_PAY_TYPE]['payreturn'];   //商家接受支付结果的URL
						//$commodity_info = urlencode(JIEQI_EGOLD_NAME);
						//$pname = urlencode($_SESSION['jieqiUserName']);
						$keyValue = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];
						
						$P['nodeAuthorizationURL'] = $nodeAuthorizationURL;
						$P['messageType'] = $messageType;
						$P['addressFlag'] = $addressFlag;
						$P['merchantId'] = $merchantId;
						$P['merchantCallbackURL'] = $merchantCallbackURL;
						$P['keyValue'] = $keyValue;
						$mac = getReqHmacString($orderId,$amount,$cur,$productId,$productCat,$productDesc,$sMctProperties,$frpId,$needResponse,$P); //对参数串进行私钥加密取得值
						
						if($params['pd_FrpId']!='')$paytype_n = $jieqiPayset[JIEQI_PAY_TYPE]['payfrom'][$_POST['pd_FrpId']];
						else $paytype_n = '其他银行';
						$url_pay = $jieqiPayset[JIEQI_PAY_TYPE]['payurl'];
						$buyname = $auth['username'];
						$egold = $params['egold'];
						$egoldname = JIEQI_EGOLD_NAME;
						$money = sprintf('%0.2f', $money / 100);
						
						$urlvars=array();
						$urlvars['p0_Cmd']= $messageType;
						$urlvars['p1_MerId']= $merchantId;
						$urlvars['p2_Order']= $orderId;
						$urlvars['p3_Amt']= $amount;
						$urlvars['p4_Cur']= $cur;
						$urlvars['p5_Pid']= $productId;
						$urlvars['p6_Pcat']= $productCat;
						$urlvars['p7_Pdesc']= $productDesc;
						$urlvars['p8_Url']= $merchantCallbackURL;
						$urlvars['p9_SAF']= $addressFlag;
						$urlvars['pa_MP']= $sMctProperties;
						$urlvars['pd_FrpId']= $frpId;
						$urlvars['pr_NeedResponse']= $needResponse;
						$urlvars['hmac']= $mac;
						 return array(
							  'urlvars'=>$urlvars,
							  'paytype'=>$paytype_n,
							  'url_pay'=>$url_pay,
							  'buyname'=>$buyname,
							  'egold'=>$egold,
							  'egoldname'=>$egoldname,
							  'money'=>$money,
							  'moneytype'=>0
						 );
					}
			 }else{
			      $this->printfail($jieqiLang['pay']['buy_type_error']);
			 }
		}
		//网银返回结果
		function checkyeepay($params = array(), $jieqiPayset = array()){
		    define('JIEQI_PAY_TYPE', 'yeepay');
			require_once($GLOBALS['jieqiModules']['pay']['path'].'/function/yeepaycommon.php'); //易宝支付接口公共函数
			$this->addLang('pay', 'pay');
			$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
			$merchantId = $jieqiPayset[JIEQI_PAY_TYPE]['payid'];  //商户编号
			$keyValue = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];
			$paytype=JIEQI_PAY_TYPE;
			if(isset($_REQUEST['rb_BankId'])) $_REQUEST['rb_BankId']=trim($_REQUEST['rb_BankId']);
			if(!empty($_REQUEST['rb_BankId']) && isset($jieqiPayset[JIEQI_PAY_TYPE]['paytype'][$_REQUEST['rb_BankId']])) $paytype=$jieqiPayset[JIEQI_PAY_TYPE]['paytype'][$_REQUEST['rb_BankId']];
			
			$sCmd = trim($_REQUEST['sCmd']);
			$sErrorCode = trim($_REQUEST['sErrorCode']);
			$sTrxId = trim($_REQUEST['sTrxId']);
			$amount = trim($_REQUEST['amount']);
			$cur = trim($_REQUEST['cur']);
			$productId = trim($_REQUEST['productId']);
			$orderId = trim($_REQUEST['orderId']);
			$userId = trim($_REQUEST['userId']);
			$MP = trim($_REQUEST['MP']);
			$bType = trim($_REQUEST['bType']);
			$svrHmac = trim($_REQUEST['svrHmac']);

			$return = getCallBackValue($sCmd,$sErrorCode,$sTrxId,$amount,$cur,$productId,$orderId,$userId,$MP,$bType,$svrHmac);
			$bRet = CheckHmac($sCmd,$sErrorCode,$sTrxId,$orderId,$amount,$cur,$productId,$userId,$MP,$bType,$svrHmac,$keyValue,$merchantId);
			if($bRet){
				if($sErrorCode=='1'){
					//$orderid=intval($getvars['out_trade_no']);
					$this->db->init( 'paylog', 'payid', 'pay' );
					$this->db->setCriteria(new Criteria( 'payid', $orderId, '=' ));
					$paylog=$this->db->get($this->db->criteria);
					//include_once($jieqiModules['pay']['path'].'/class/paylog.php');
					//$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
					//$paylog=$paylog_handler->get($orderid);
					if(is_object($paylog)){
						$buyname=$paylog->getVar('buyname');
						$buyid=$paylog->getVar('buyid');
						$payflag=$paylog->getVar('payflag');
						$egold=$paylog->getVar('egold');
						if($payflag == 0){
							//if(intval($amount)>5){
//								include_once(JIEQI_ROOT_PATH.'/class/users.php');
								$users_handler = $this->getUserObject();
						
								$package =  $this->load('rechargetask','task');//加载充值任务类
								$package->judge($buyid, $buyname, intval($amount * 100));//判断是否满赠
						
								$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], ceil($amount * 50), intval($amount * 100), intval($amount * 100));
							//}
							if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
							else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
							if($order<142699){
								$this->swritefile(JIEQI_ROOT_PATH.'/yeepay.txt', "ORDERID=>{$orderId} MONEY=>{$amount} EGOLD=>{$egold} TIME=>".date('Y-m-d H:i:s',time())."\r\n","a+");
							}
							$paylog->setVar('rettime', JIEQI_NOW_TIME);
							$paylog->setVar('money', intval($amount * 100));
							$paylog->setVar('paytype', $paytype);
							$paylog->setVar('note', $note);
							$paylog->setVar('payflag', 1);
							if(!$this->db->edit($orderId, $paylog)) $this->printfail($jieqiLang['pay']['save_paylog_failure']);
						}
						if($bType=="2") echo "success";
						else $this->msgbox(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
					}
				}else{
					$this->printfail($jieqiLang['pay']['pay_return_error']);
				}
			}else{
				$this->printfail($jieqiLang['pay']['return_checkcode_error']);
			
			}
		}
//		//手机充值
//        function cardpay($params = array()){ 
//		     
//        } 
//		//盛大
//        function sdopay($params = array()){ 
//		     
//        } 
//		//Q币
//        function qpay($params = array()){ 
//		     
//        } 
//		//骏卡
//        function jcardpay($params = array()){ 
//		     
//        } 
		//Paypal
        function paypal($params = array(), $jieqiPayset = array()){ 
		    define('JIEQI_PAY_TYPE', 'paypal');
			include_once ($GLOBALS['jieqiModules']['pay']['path'].'/function/paypal.class.php');
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
					$paylog['siteid'] = JIEQI_SITE_ID;
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
					$paylog['retinfo'] = $this->getip();//''
					$paylog['masterid'] = 0;
					$paylog['mastername'] = '';
					$paylog['masterinfo'] = '';
					$paylog['note'] = $user_is_agent;//''
					$paylog['payflag'] = 0;
					$paylog['source'] = $auth['source'];
					if(!$payid = $this->db->add($paylog)) $this->printfail($jieqiLang['pay']['add_paylog_error']);
					else {
						//构造要请求的参数数组
						$api_info = array(
							'api_username' =>$jieqiPayset [JIEQI_PAY_TYPE]['apiinfo']['api_username'],
							'api_password' =>$jieqiPayset [JIEQI_PAY_TYPE]['apiinfo']['api_password'],
							'pai_signatuer' =>$jieqiPayset [JIEQI_PAY_TYPE]['apiinfo']['pai_signatuer']
						);
						$paypal = new paypal($api_info);
						
						$urlvars ['amount'] = $money / 100;
						$urlvars ['currency'] = $jieqiPayset [JIEQI_PAY_TYPE]['apiinfo']['currency'];
						$urlvars ['desc'] = $jieqiPayset [JIEQI_PAY_TYPE]['apiinfo']['desc'];
						$urlvars ['order'] = $payid;
						$urlvars['returnPage'] = $jieqiPayset [JIEQI_PAY_TYPE]['apiinfo']['return_page'];
						$urlvars['cancelPage'] = $jieqiPayset [JIEQI_PAY_TYPE]['apiinfo']['cancel_page'];
						
						$pay_url = $paypal->SetExpressCheckout($urlvars);
						$buyname = $auth['username'];
						$money = sprintf('%0.2f', $money / 100);
						 return array(
							  'urlvars'=>$urlvars,
							  'paytype'=>'PayPal',
							  'url_pay'=>$pay_url,
							  'buyname'=>$buyname,
							  'egold'=>$params['egold'],
							  'egoldname'=>JIEQI_EGOLD_NAME,
							  'money'=>$money,
							  'moneytype'=>1
						 );
						
					}
				} else {
					$this->printfail ( $jieqiLang ['pay'] ['need_buy_type'] );
				}
        } 
		//网银返回结果
		function checkpaypal($params = array(), $jieqiPayset = array()){
		    define('JIEQI_PAY_TYPE', 'paypal');
			include_once ($GLOBALS['jieqiModules']['pay']['path'].'/function/paypal.class.php');
			$this->addLang('pay', 'pay');
			$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
			
			$api_info = array(
					'api_username' =>$jieqiPayset[JIEQI_PAY_TYPE]['apiinfo']['api_username'],
					'api_password' =>$jieqiPayset[JIEQI_PAY_TYPE]['apiinfo']['api_password'],
					'pai_signatuer' =>$jieqiPayset[JIEQI_PAY_TYPE]['apiinfo']['pai_signatuer']
			);
			$paypal = new paypal($api_info);
			$order = $_GET['order'];
			$P['token'] = $_GET['token'];
			$pay_info = $paypal->GetExpressCheckoutDetails($P);
			
			if($pay_info['ACK'] == 'Success'){
				$array ['token'] = $pay_info['TOKEN'];
				$array ['payAmount'] = $pay_info['AMT'];
				$array ['PayerID'] = $pay_info['PAYERID'];
			
				$return = $paypal->DoExpressCheckoutPayment($array);
				
				if($return['ACK'] == 'Success'){
					//付款成功,置为真实订单
					$pay_order = $return['PAYMENTINFO_0_TRANSACTIONID'];
					$amount = $return['PAYMENTINFO_0_AMT'];
					$rs = $this->trueOrder($order,$pay_order,$amount,$jieqiPayset);
					if($rs !== false){
						$this->jumppage($this->geturl('system','userhub','SYS=method=czView'),LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $rs['buyname'], JIEQI_EGOLD_NAME, $rs['egold']));
						exit();
					}else{
						$this->printfail($jieqiLang['pay']['no_buy_record']);
						exit();
					}
				}else{
					$this->printfail($jieqiLang['pay']['return_checkcode_error']);
					exit();
				}
				
			}else{
				$this->printfail($jieqiLang['pay']['pay_failure_message']);
				exit();
			}
		}
		function trueOrder($order,$pay_order,$amount, $jieqiPayset){
		    global $jieqiLang;
		    define('JIEQI_PAY_TYPE', 'paypal');
			$state = array();
			global $jieqiModules;
			$order=intval($order);
			$this->db->init( 'paylog', 'payid', 'pay' );
            $this->db->setCriteria(new Criteria( 'payid', $order, '=' ));
			$paylog=$this->db->get($this->db->criteria);
			//订单是否存在
			if(is_object($paylog)){
				$buyname=$paylog->getVar('buyname');
				$buyid=$paylog->getVar('buyid');
				$payflag=$paylog->getVar('payflag');
				$egold=$paylog->getVar('egold');
				$money=$paylog->getVar('money');
				//交易是否完成
				if($payflag == 0){
					include_once(JIEQI_ROOT_PATH.'/class/users.php');
					$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
					$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold], intval($money * 6));
					if($ret) {
						$note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
					}else{
						$note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
					}
					$paylog->setVar('rettime', JIEQI_NOW_TIME);
					// $paylog->setVar('money', intval($amount * $jieqiPayset[JIEQI_PAY_TYPE]['exchange_rate'] * 100));
					$paylog->setVar('note', $note);
					$paylog->setVar('payflag', 1);
					if($this->db->edit($order,$paylog)) {
						return array('buyname'=>$buyname,'egold'=>$egold);
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
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
					$paylog['siteid'] = JIEQI_SITE_ID;
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
						$moneyshow = sprintf('%0.2f', $money / 100);
						
						$urlvars=array();
						$urlvars['product_id']= $jieqiPayset[JIEQI_PAY_TYPE]['product_id'][$params['egold']];
						$params['product_id'] = $jieqiPayset[JIEQI_PAY_TYPE]['product_id'][$params['egold']];
						$urlvars['merchant_no']= $jieqiPayset[JIEQI_PAY_TYPE]['merchant_no'];
						$urlvars['charge_amt']= $money / 100;
						$urlvars['num']= 1;
						$urlvars['user_account']= urlencode($auth['username']);
						$urlvars['order_id']= $payid;
						$urlvars['user_ip']= $ip;
						$urlvars['ret_type']= 2;
						$urlvars['url_tag']= $jieqiPayset[JIEQI_PAY_TYPE]['return_page'];
						$urlvars['ext_param']= '';
						$urlvars['c']= 8;
						$sign = strtolower(md5('merchant_no='.$jieqiPayset[JIEQI_PAY_TYPE]['merchant_no'].'||FaweB0Yo9dO0SD5xde&product_id='.$params['product_id'].'&charge_amt='.($money / 100).'&num=1&user_account='.urlencode($auth['username']).'&order_id='.$payid.'&user_ip='.$urlvars['user_ip'].'&ret_type=2||zghsswfz1dbjaq'));

						$urlvars['sign']= $sign;
						 return array(
							  'urlvars'=>$urlvars,
							  'paytype'=>$paytype,
							  'url_pay'=>$url_pay,
							  'buyname'=>$buyname,
							  'egold'=>$egold,
							  'egoldname'=>$egoldname,
							  'money'=>$moneyshow
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
			$key3 = 'RHXtq8WJT5nDYx9v';
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
						
									$package =  $this->load('rechargetask','task');//加载充值任务类
									$package->judge($paylog->getVar('buyid'), $buyname, $money);//判断是否满赠
						
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
		//威富通 微信
        function wftpay($params = array(), $jieqiPayset = array()){
		     define('JIEQI_PAY_TYPE', 'wftpay');
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
				  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])&&!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])){
					  $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']] * 100);
				  }else{
					  //if(intval($params['money_yuan'])<1) $this->printfail($jieqiLang['pay']['buy_type_error']);
					  $money = intval($params['money_yuan'])*100;
					  $params['egold'] = $money;
				  }
				  $auth = $this->getAuth();
				  
				    $this->db->init( 'paylog', 'payid', 'pay' );
					$paylog['siteid'] = JIEQI_SITE_ID;
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
						include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
						
						require('function/Utils.class.php');
						require('function/RequestHandler.class.php');
						require('function/ClientResponseHandler.class.php');
						require('function/PayHttpClient.class.php');
						$resHandler = new ClientResponseHandler();
						$reqHandler = new RequestHandler();
						$pay = new PayHttpClient();
						
						$reqHandler->setKey($jieqiPayset[JIEQI_PAY_TYPE]['sign']);
						
						$reqHandler->setParameter('service',$jieqiPayset[JIEQI_PAY_TYPE]['service']);	//接口类型
						$reqHandler->setParameter('version',$jieqiPayset[JIEQI_PAY_TYPE]['version']);	//版本号
						$reqHandler->setParameter('mch_id',$jieqiPayset[JIEQI_PAY_TYPE]['mch_id']);	//商户号
						$reqHandler->setParameter('out_trade_no',$payid);							//商户订单号
						$body = jieqi_gb2utf8($jieqiPayset[JIEQI_PAY_TYPE]['body'][$params['egold']]);
						$reqHandler->setParameter('body',$body);//商品描述
						$reqHandler->setParameter('total_fee',$money);								//总金额
						$reqHandler->setParameter('mch_create_ip',$this->getip());					//终端IP
						$reqHandler->setParameter('notify_url',$jieqiPayset[JIEQI_PAY_TYPE]['notify_url']);//通知地址
						$reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串
						$reqHandler->createSign();//签名
						
						$data = Utils::toXml($reqHandler->getAllParameters());
						$pay->setReqContent($jieqiPayset[JIEQI_PAY_TYPE]['payurl'],$data);
						if($pay->call()){
							$resHandler->setContent($pay->getResContent());
							$resHandler->setKey($reqHandler->getKey());
							if($resHandler->getParameter('status') == 0 && $resHandler->getParameter('result_code') == 0){
								//$code_url = $resHandler->getParameter('code_url');
								$data = $resHandler->getAllParameters();//print_r($data);
								return $data;
							}else{
								$this->printfail($jieqiLang['pay']['buy_type_error']);
							}
						}else{
							$this->printfail($jieqiLang['pay']['add_paylog_error']);
						}
					}
			 }else{
				 $this->printfail($jieqiLang['pay']['buy_type_error']);
			}
		}
		//威富通 返回结果
		function checkwftpay($params = array(), $jieqiPayset = array()){
		     define('JIEQI_PAY_TYPE', 'wftpay');
			 
			require('function/Utils.class.php');
			require('function/ClientResponseHandler.class.php');
			$resHandler = new ClientResponseHandler();
			 
			$xml = file_get_contents('php://input');
			
			$resHandler->setContent($xml);
			$resHandler->setKey($jieqiPayset[JIEQI_PAY_TYPE]['sign']);
			if($resHandler->isTenpaySign()){
				if($resHandler->getParameter('status') == 0 && $resHandler->getParameter('result_code') == 0){
					
					
				$this->db->init( 'paylog', 'payid', 'pay' );
				$orderid = $resHandler->getParameter('out_trade_no');
				$this->db->setCriteria(new Criteria( 'payid', $orderid));
				$paylog = $this->db->get($this->db->criteria);
				if(is_object($paylog)){
					 $payflag = $paylog->getVar('payflag');
					 if($payflag == 0){
						 $money = $paylog->getVar('money');
						 include_once(JIEQI_ROOT_PATH.'/class/users.php');
						 $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
						
						$package =  $this->load('rechargetask','task');//加载充值任务类
						$package->judge($paylog->getVar('buyid'), $paylog->getVar('buyname', 'n'), $money);//判断是否满赠
						
						 $ret=$users_handler->income($paylog->getVar('buyid'), $paylog->getVar('egold'), $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], ceil($money/2), $money, $money);
						 if($ret){
							 $paylog->setVar('rettime', JIEQI_NOW_TIME);
							 $paylog->setVar('payflag', 1);
							 $paylog->setVar('retserialno', $resHandler->getParameter('transaction_id'));
							 //$paylog->setVar('retinfo', $resHandler->getParameter('time_end'));
							 if(!$this->db->edit($orderid, $paylog)){ 
								exit('failure');
							 }else{
								exit('success');
							 }
						 }else exit('failure');
					 }else exit('success');
				}else{
					exit('failure');
				}
			 
					
					
					
					
					
//					Utils::dataRecodes('接口回调',$resHandler->getAllParameters());
//					exit('success');
				}else{
					exit('failure');
				}
			}else{
				exit('failure');
			}
		}
} 

?>