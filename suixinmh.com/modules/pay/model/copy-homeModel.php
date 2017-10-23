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
					if($payflag == 0){
						$users_handler =  $this->getUserObject();
						$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], 0);
						if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
						else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
						$paylog->setVar('rettime', JIEQI_NOW_TIME);
						$paylog->setVar('note', $note);
						$paylog->setVar('payflag', 1);
						if(!$this->db->edit($orderid, $paylog)){ 
							if($showmode) $this->printfail($jieqiLang['pay']['save_paylog_failure']);
						}else{
							if($showmode) $this->msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
						}
					}else{
						if($showmode) $this->msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
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
			 require_once($GLOBALS['jieqiModules']['pay']['path'].'/function/yeepaycommon.php'); //易宝支付接口公共函数
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
						//$p0_Cmd = $jieqiPayset[JIEQI_PAY_TYPE]['messageType'];    //支付请求，固定值"Buy" 
						$merchantId = $jieqiPayset[JIEQI_PAY_TYPE]['payid'];  //商户编号
						$orderId = $payid;     //订单编号[商户网站]
						$cur = $jieqiPayset[JIEQI_PAY_TYPE]['cur'];    //货币单位CNY
						$productId = empty($jieqiPayset[JIEQI_PAY_TYPE]['productId']) ? JIEQI_EGOLD_NAME : $jieqiPayset[JIEQI_PAY_TYPE]['productId'];    //商品名称
						$productCat = $jieqiPayset[JIEQI_PAY_TYPE]['productCat'];    //货币单位CNY
						$productDesc = $jieqiPayset[JIEQI_PAY_TYPE]['productDesc'];    //货币单位CNY
						$sMctProperties = $jieqiPayset[JIEQI_PAY_TYPE]['sMctProperties'];    //sMctProperties
						$frpId = trim($_POST['pd_FrpId']) != '' ? trim($_POST['pd_FrpId']) : $jieqiPayset[JIEQI_PAY_TYPE]['frpId'];    //货币单位CNY
						$frpId = 'ICBC-NET-B2C';
						$needResponse = $jieqiPayset[JIEQI_PAY_TYPE]['needResponse'];    //货币单位CNY
						$nodeAuthorizationURL = $jieqiPayset[JIEQI_PAY_TYPE]['payurl'];    //货币单位CNY
						$merchantCallbackURL = $jieqiPayset[JIEQI_PAY_TYPE]['payreturn'];    //货币单位CNY
						$p0_Cmd = $jieqiPayset[JIEQI_PAY_TYPE]['messageType'];    //货币单位CNY
						$addressFlag = $jieqiPayset[JIEQI_PAY_TYPE]['addressFlag'];    //货币单位CNY
				
						
						$mac = getReqHmacString($orderId,$amount,$cur,$productId,$productCat,$productDesc,$merchantCallbackURL,$sMctProperties,$frpId,$needResponse); //对参数串进行私钥加密取得值
						$urlvars=array();
						$urlvars['url_pay']= $jieqiPayset[JIEQI_PAY_TYPE]['payurl'];
						$urlvars['buyname']= $auth['username'];
						$urlvars['egold']= $params['egold'];
						$urlvars['egoldname']= JIEQI_EGOLD_NAME;
						$urlvars['money']= sprintf('%0.2f', $money / 100);
						$urlvars['p0_Cmd']= $p0_Cmd;
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
			$return = getCallBackValue($sCmd,$sErrorCode,$sTrxId,$amount,$cur,$productId,$orderId,$userId,$MP,$bType,$svrHmac);
			$bRet = CheckHmac($sCmd,$sErrorCode,$sTrxId,$orderId,$amount,$cur,$productId,$userId,$MP,$bType,$svrHmac);
			if($bRet){
				if($sErrorCode=='1'){
					$orderid=intval($getvars['out_trade_no']);
					$this->db->init( 'paylog', 'payid', 'pay' );
					$this->db->setCriteria(new Criteria( 'payid', $orderid, '=' ));
					$paylog=$this->db->get($this->db->criteria);
					include_once($jieqiModules['pay']['path'].'/class/paylog.php');
					$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
					$paylog=$paylog_handler->get($orderid);
					if(is_object($paylog)){
						$buyname=$paylog->getVar('buyname');
						$buyid=$paylog->getVar('buyid');
						$payflag=$paylog->getVar('payflag');
						$egold=$paylog->getVar('egold');
						if($payflag == 0){
							if(intval($amount)>5){
//								include_once(JIEQI_ROOT_PATH.'/class/users.php');
								$users_handler = $this->getUserObject();
								$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold]);
							}
							if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
							else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
							$paylog->setVar('rettime', JIEQI_NOW_TIME);
							$paylog->setVar('money', intval($amount * 100));
							$paylog->setVar('paytype', $paytype);
							$paylog->setVar('note', $note);
							$paylog->setVar('payflag', 1);
							if(!$this->db->edit($paylog)) $this->printfail($jieqiLang['pay']['save_paylog_failure']);
						}
						if($bType=="2") echo "success";
						$this->msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
					}
				}else{
					$this->printfail($jieqiLang['pay']['pay_return_error']);
				}
			}else{
				$this->printfail($jieqiLang['pay']['return_checkcode_error']);
			
			}
		}
		//手机充值
        function cardpay($params = array()){ 
		     
        } 
		//盛大
        function sdopay($params = array()){ 
		     
        } 
		//Q币
        function qpay($params = array()){ 
		     
        } 
		//骏卡
        function jcardpay($params = array()){ 
		     
        } 
		//Paypal
        function paypal($params = array()){ 
		     
        } 
} 

?>