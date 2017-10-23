<?php
/**
 * 支付模型
 * @author zhangxue
 *
 */
class payModel extends Model {
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
		 $params['money']=intval($params['money']);
		 if(isset($params['money']) && is_numeric($params['money']) && $params['money']>0){
		  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
			  if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['money']]))
				  $egold=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['money']]);
			  else $this->printfail($jieqiLang['pay']['buy_type_error']);
		  }else{
			  $this->printfail($jieqiLang['pay']['buy_type_error']);
		  }
             $money=$params['money']*100;

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
				$paylog['egold'] = $egold;
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
					//$mark="ID:".$auth['username'];
					$remarks= "ID:".$auth['username']."|".$jieqiPayset[JIEQI_PAY_TYPE]['remarks'][$params['money']];
					
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

		if (strpos($sdcustomno,'|')!==false) {
			$tmp1=explode('|',$sdcustomno);
			$sdcustomno=$tmp1[0];
		}
		
		$key=$jieqiPayset[JIEQI_PAY_TYPE]['key'];  //key可从星启天网关客服处获取
		$sign2=strtoupper(md5("customerid=".$customerid."&sd51no=".$sd51no."&sdcustomno=".$sdcustomno."&mark=".$mark."&key=".$key));
		$resign2=strtoupper(md5("sign=".$sign."&customerid=".$customerid."&ordermoney=".$ordermoney."&sd51no=".$sd51no."&state=".$state."&key=".$key));

        if ($sign == $sign2 && $resign == $resign2) {
            if ($state == 1) {
                $this->db->init('paylog', 'payid', 'pay');
                $this->db->setCriteria(new Criteria('payid', $sdcustomno));
                $paylog = $this->db->get($this->db->criteria);
                if (is_object($paylog)) {
                    $payflag = $paylog->getVar('payflag');//echo $payflag;
                    $egold = $paylog->getVar('egold');
                    $buyname = $paylog->getVar('buyname');
                    if ($payflag == 0) {
                        $money = $paylog->getVar('money');
                        $totalFee = ceil($money / 100);
                        include_once(JIEQI_ROOT_PATH . '/class/users.php');
                        $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
                        $ret = $users_handler->income($paylog->getVar('buyid'), $paylog->getVar('egold'), $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], ceil($totalFee * 50), $money, $money);
                        if ($ret) {
                            $paylog->setVar('rettime', JIEQI_NOW_TIME);
                            $paylog->setVar('payflag', 1);
                            $paylog->setVar('buyinfo', 'des:' . $des . '&mark:' . $mark);//备注
                            $paylog->setVar('retserialno', $sd51no);//对方交易订单号

                            if (!$this->db->edit($sdcustomno, $paylog)) {
                                $retMsg = '-1';    //充值成功，修改记录失败
                            } else {
                                $this->insert_activity(array(
                                    'uid'=>$paylog->getVar('buyid'),
                                    'money'=>$paylog->getVar('money')
                                ));
                                $retMsg = '1';    //交易成功
                            }
                        } else {
                            $retMsg = '1';    //交易成功
                        }
                    } else {
                        $retMsg = '1';    //重复提交
                    }
                } else {
                    $retMsg = '3';    //无此交易记录
                }
            } else {
                $this->db->add($dat);
                $retMsg = '4';    //支付未成功
            }
        } else{$this->db->add($dat);
			echo "sign=$sign<br>sign2=$sign2<br>resign=$resign<br>resign2=$resign2<br>";
			$retMsg = '5';	//参数错误
		}
        if ($retMsg == '1') {
            exit('<result>' . $retMsg . '</result>');
        }
        else {
            exit('fail:'.$retMsg);
        }
	}


	/**
	 * 微信 同步：
	 * 不带参数，单纯跳转，没有加币功能
	 */
	function checkwechat($params = array(), $jieqiPayset = array()){//print_r($params);exit;
	    define('JIEQI_PAY_TYPE', 'wechat_wap');
		$this->addLang('pay', 'pay');
        $jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值

        $users_handler = $this->getUserObject();
        $auth = $this->getAuth();
        if ($auth['uid']) {//更新用户SESSIO，防止出现充值到账未显示的情况
            if ($users = $users_handler->get($auth['uid'])) {
                $users->saveToSession();
                $_SESSION['buyonechapter']=1;
            }
        }
        return array(
            'title' => 1,
            'msg' => '请进入个人中心查看充值记录。<br />如果余额不变，请尝试重新登录。'
//							'msg'=>sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold)
        );

    }



	/**
	 * 梓微兴支付
	 */
	function wechat_zwx($params = array(), $jieqiPayset = array()){
		define('JIEQI_PAY_TYPE', 'wechat_zwx');
        require_once(dirname(__FILE__)."/../../../include/funsystem.php");
        require(dirname(__FILE__).'/../wechat_zwx/Utils.class.php');
        require(dirname(__FILE__).'/../wechat_zwx/Props.php');
        require(dirname(__FILE__).'/../wechat_zwx/RequestHandler.class.php');
        require(dirname(__FILE__).'/../wechat_zwx/ResponseHandler.class.php');
        require(dirname(__FILE__).'/../wechat_zwx/HttpClient.php');
        require(dirname(__FILE__).'/../wechat_zwx/Log.php');

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
			if(!$payid = $this->db->add($paylog)) {
                $this->printfail($jieqiLang['pay']['add_paylog_error']);
            }
			else{
				$pay_vars=array(
                    'mch_id'=>$jieqiPayset[JIEQI_PAY_TYPE]['customerid'],
                    'nonce_str'=>mt_rand(time(),time()+rand()),
                    'body'=>'test',
                    'detail'=>'test',
                    'attach'=>'test',
                    'out_trade_no'=>$payid,
                    'total_fee'=>$money,
                    'fee_type'=>'CNY',
                    'spbill_create_ip'=>$this->getip(),
                    'goods_tag'=>'',
                    'notify_url'=>$jieqiPayset[JIEQI_PAY_TYPE]['noticeurl'],
                    'return_url'=>$jieqiPayset[JIEQI_PAY_TYPE]['backurl'],
                    'trade_type'=>'trade.weixin.jspay'
                );



                $pay_vars=Utils::createSign($pay_vars,$jieqiPayset[JIEQI_PAY_TYPE]['key']);
                $data=Utils::to($pay_vars);

                $data=array("payid"=>$payid,
                    "money"=>$money,
                    "body"=>iconv("gbk","utf-8","书币充值"),
                    "detail"=>iconv("gbk","utf-8","用户:".$auth['uname']."充值".(round($money/100))."元获得".$paylog['egold']."书币"),
                    "ip"=>$_SERVER['REMOTE_ADDR'], //$this->getip(),
                    "attach"=>$payid,
                    "trade_type"=> is_weixin() ? 'trade.weixin.jspay' : 'trade.weixin.h5pay'
                );

                //print_r($pay_vars);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_TIMEOUT, 120);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_URL, 'http://'.JIEQI_HTTP_HOST.'/wechat_zwx/pay.php');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                $res = curl_exec($ch);


                $result = Utils::parse($res);

                if ($result['result_code'] == 'SUCCESS') {
                    header("location:".$result['prepay_url']);
                    exit();
                }
                else {
                    //echo htmlspecialchars($res)."\n";
                    $this->printfail($result['err_code'].$result['return_msg']);
                    exit();
                }
			}
		}else{
			$this->printfail($jieqiLang['pay']['buy_type_error']);
		}
	}
	/**
	 *  微信：异步通知
	 */
	function wechat_zwx_notify($params = array(), $jieqiPayset = array()){
        require_once(dirname(__FILE__)."/../../../include/funsystem.php");
        require(dirname(__FILE__).'/../wechat_zwx/Utils.class.php');
        require(dirname(__FILE__).'/../wechat_zwx/Props.php');
        require(dirname(__FILE__).'/../wechat_zwx/RequestHandler.class.php');
        require(dirname(__FILE__).'/../wechat_zwx/ResponseHandler.class.php');
        require(dirname(__FILE__).'/../wechat_zwx/HttpClient.php');
        require(dirname(__FILE__).'/../wechat_zwx/Log.php');
		define('JIEQI_PAY_TYPE', 'wechat_zwx');

        $xml = file_get_contents("php://input");
        sysdebug($xml);

        if (empty($xml)) {
            die("fail");
        }
        // 格式化数据为数组
        $data = Utils::parse($xml);
        if ($data === false) {
            die("fail");
        }
        // 检查是否完成支付
        if ($data['result_code'] !== 'SUCCESS' || $data['return_code'] !== 'SUCCESS') {
            die("fail");
        }
        // 验证返回的结果签名
        if (! Utils::checkSign($data,$jieqiPayset[JIEQI_PAY_TYPE]['key'])) {
            die("fail");
        }else {
            $sdcustomno=$data['out_trade_no'];
            $sd51no = $data['transaction_id'];
            $third_trans_id=$data['third_trans_id'];
            $is_subscribe=$data['is_subscribe'];
            $sub_is_subscribe = $data['sub_is_subscribe'];
            $attach=$data['attach'];

            $insert_arr=array(
                'payid'=>$sdcustomno,
                'transaction_id'=>$sd51no,
                'third_trans_id'=>$third_trans_id,
                'out_trade_no'=>$data['out_trade_no'],
                'is_subscribe'=>$is_subscribe,
                'sub_is_subscribe'=>$sub_is_subscribe,
                'trade_type'=>$data['trade_type'],
                'bank_type'=>$data['bank_type'],
                'total_fee'=>$data['total_fee'],
                'time_end'=>$data['time_end']
            );
            $this->db->init('wechat','id','pay');
            $this->db->add($insert_arr);

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
                            echo "fail";	//充值成功，修改记录失败
                        }else{
                            echo "success";
                        }
                    }else{
                        echo "fail";	//交易成功
                    }
                }else{
                    echo "success";
                }
            }
        }
	}

	function insert_activity($param){
        $uid=$param['uid'];
		$date=date("Y-m-d");
        $num=0;
        if ($date<='2017-02-05') {
            $hid=2017;
            switch($param['money']) {
                case 5000:$num=1;break;
                case 10000:$num=2;break;
                case 20000:$num=5;break;
                case 50000:$num=15;break;
                case 100000:$num=35;break;
                default:$num=0;break;
            }
        }
        if ($num>0) {

            $this->db->init("activity","id","system");
            $this->db->setCriteria(new Criteria( 'uid', $uid));
            $this->db->criteria->add ( new Criteria ( 'hid', $hid) );
            $activity=$this->db->get($this->db->criteria);

            if (is_object($activity) && $activity->getVar('id')) {
                $id=$activity->getVar('id');
                $sql="update jieqi_system_activity set num=num+$num where id=$id";
                $this->db->query($sql);
            }
            else {
                $activity=array(
                    'hid'=>$hid,
                    'uid'=>$uid,
                    'num'=>$num
                );
                $this->db->add($activity);
            }
        }
	}

	/**
	 * 分站微信支付
	 * 标示 $_SERVER['ESHUKU_SUB']
	 */
	function wechat_sub($params = array(), $jieqiPayset = array()){//print_r($params);exit;
		define('JIEQI_PAY_TYPE', 'wechat_wap');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		//判断代理
		$user_is_agent = '';
		if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
			$user_is_agent = 'agent';
		}
		$params['egold']=intval($params['money']);
		//echo "gold=".$params['egold'];
		if(isset($params['egold']) && is_numeric($params['egold']) && $params['egold']>0){
			if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
				if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']])){
					$money=$params['egold']*100;
					$params['egold']=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$params['egold']]);
				}
				else{
					$this->printfail($jieqiLang['pay']['buy_type_error']);
				}
			}else{
				$this->printfail($jieqiLang['pay']['buy_type_error']);
			}
			$auth = $this->getAuth();
			if ($params['click_from_qd'])
				$pay_qd=$params['click_from_qd'];
			else
				$pay_qd=$auth['source'];

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
			$paylog['note'] = $pay_qd;
			$paylog['payflag'] = 0;
			$paylog['source'] = $auth['source'];
			if(!$payid = $this->db->add($paylog)) $this->printfail($jieqiLang['pay']['add_paylog_error']);
			else{
				//组合信息通知微信接口
				$wx_param['goods']=($params['egold']/100);
				$wx_param['order_id']=$payid;
				$wx_param['money']=$money;
				$wx_param['ymdhis']=date('YmdHis');

				$this->db->init( 'connect', 'openid', 'system' );
				$this->db->setCriteria(new Criteria( 'uid', $auth['uid']));
				$openid=$this->db->get($this->db->criteria);
				$wx_param['open_id']=$openid->getVar('openid');

				//				include JIEQI_ROOT_PATH.'/modules/new_wxpay/lib/WxPay.Config.php';
				//				include JIEQI_ROOT_PATH.'/modules/new_wxpay/jsapi/WxPay.JsSdk.php';
				//				$jssdk = new JSSDK(WxPayConfig::APPID, WxPayConfig::APPSECRET);
				//				$signPackage = $jssdk->GetSignPackage();
				//
				//				require JIEQI_ROOT_PATH.'/modules/new_wxpay/lib/WxPay.Api.php';
				//				require JIEQI_ROOT_PATH.'/modules/new_wxpay/jsapi/WxPay.JsApiPay.php';
				//				$tools=new JsApiPay();
				//				$input=new WxPayUnifiedOrder();
				//				$input->SetBody($wx_param['goods']);
				//				$input->SetAttach($wx_param['goods']);
				//				$input->SetOut_trade_no($wx_param['order_id']);
				//				$input->SetTotal_fee($wx_param['money']);
				//				$input->SetTime_start($wx_param['ymdhis']);
				//				$input->SetTime_expire(date("YmdHis",time()+600));
				//				$input->SetGoods_tag($wx_param['goods']);
				//				$input->SetNotify_url($wx_param['notify_url']);
				//				$input->SetTrade_type("JSAPI");
				//				$input->SetOpenid($wx_param['open_id']);
				//				$order=WxPayApi::unifiedOrder($input);
				//				$jsApiParameters=json_decode($tools->GetJsApiParameters($order),TRUE);

				$url='http://wx.'.JIEQI_COOKIE_DOMAIN.'/wx.php?'.http_build_query($wx_param);
				//echo $url;
				$wx_result=@file_get_contents($url);

				//echo $wx_result;
				//exit();
                //print_r(json_decode($wx_result,TRUE));

				return json_decode($wx_result,TRUE);
				//				return array('sign'=>$signPackage,'jsapi'=>$jsApiParameters);
			}
		}else{
			$this->printfail($jieqiLang['pay']['buy_type_error']);
		}
	}
	/**
	 * 微信：异步通知
	 */
	function wechat_sub_notify($params = array(), $jieqiPayset = array()){
		define('JIEQI_PAY_TYPE', 'wechat_wap');
		$retMsg='';
		$dat = array();
		if($_GET['openid']){
			if(md5($_GET['openid'].'fyeshuku.com,')!=$_GET['fy_sub']){
//				exit('error1');
			}
			$this->db->init( 'connect', 'openid', 'system' );
			$this->db->setCriteria(new Criteria( 'openid', $_GET['openid']));
			$openid=$this->db->get($this->db->criteria);
			$dat['fromid'] = $openid->getVar('uid');
			if($dat['fromid']){
				$this->db->init( 'users', 'uid', 'system' );
				$this->db->setCriteria(new Criteria( 'uid', $dat['fromid']));
				$user=$this->db->get($this->db->criteria);
				$dat['fromname'] = $user->getVar('name');
			}
		}else{
			exit('error2');
		}

		//记录日志
		$this->db->init('userlog','logid','system');
		$dat['siteid'] = JIEQI_SITE_ID;
		$dat['logtime'] = JIEQI_NOW_TIME;
		$dat['toid'] = 0;
		$dat['toname'] = '';
		$dat['chginfo'] = "微信测试";
		$dat['chglog'] = '';
		$dat['isdel'] = '0';
		$dat['userlog'] = '';
		$dat['reason'] = 'get::';
		foreach($_GET as $k=>$v){
			$dat['reason'] .= $k.'='.$v.'&';
		}
		$this->db->add($dat);
		if($_GET['return_code']=='SUCCESS'){
			$this->db->init( 'paylog', 'payid', 'pay' );
			$this->db->setCriteria(new Criteria( 'payid', $_GET['out_trade_no']));
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
						$paylog->setVar('buyinfo', 'des:'.$_GET['result_code'].'&mark:'.$_GET['result_msg']);//备注
						$paylog->setVar('retserialno', $_GET['transaction_id']);//对方交易订单号
						if(!$this->db->edit($_GET['out_trade_no'], $paylog)){
							exit('error3');
						}else{
							exit('succ');
						}
					}
					exit('succ');
				}
				exit('succ');
			}
			exit('error4');
		}else{
			exit('error5');
		}
	}
}






?>